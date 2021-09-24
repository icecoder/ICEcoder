<?php
include "headers.php";
include "settings.php";

// File extensions to look for functions & classes in
$indexableFileExts = ["php", "js", "coffee", "ts", "rb", "py", "mpy", "sql", "erl", "java", "jl", "c", "h", "cpp", "ino", "cs", "go", "lua", "pl"];

// Fallback for prevIndexData to start off initially
$prevIndexData = [];

// If we have a data/index.php file
if (file_exists($docRoot . $ICEcoderDir . "/data/index.php")) {
    // Get serialized array back out of PHP file inside a comment block as prevIndexData
    $systemClass->invalidateOPCache($docRoot . $ICEcoderDir . "/data/index.php");
    $prevIndexData = file_get_contents($docRoot . $ICEcoderDir . "/data/index.php");
    if (false !== strpos($prevIndexData, "<?php")) {
        $prevIndexData = $settingsClass->serializedFileData("get", $docRoot . $ICEcoderDir . "/data/index.php");
    }
}

// Roughly 1 in 100 index runs, we'll do a full index
if (mt_rand(1, 100) === 50) {
    $prevIndexData = [];
}

// Start a new indexData for this run
$indexData = [];

function phpGrep($path, $base) {
    global $indexableFileExts, $prevIndexData, $indexData;

    $fp = opendir($path);
    global $ICEcoder, $docRoot, $ICEcoderDir;
    if (!isset($ret)) {$ret = "";};
    $slash = -1 < strpos($path, "\\") ? "\\" : "/";
    while($f = readdir($fp)) {
        // Ignore . and .. paths
        if ("." === $f || ".." === $f) continue;
        $filePath = $path . $slash . $f;
        $filePathExt = pathinfo($filePath, PATHINFO_EXTENSION);
        // Exclude the folder ICEcoder is running from
        $rootPrefix = '/' . str_replace("/", "\/", preg_quote(str_replace("\\", "/", $docRoot))) . '/';
        $localPath = preg_replace($rootPrefix, '', $filePath, 1);
        if (0 === strpos($localPath, $ICEcoderDir)) {
            continue;
        }
        if (is_dir($filePath)) {
            $ret .= phpGrep($filePath, $base);
        } else {
            // Check if we should scan within this file, by only considering files that may contain functions & classes...
            // Must be in the indexableFileExts list
            if (false === in_array($filePathExt, $indexableFileExts)) {
                continue;
            }
            $finfo = "text";
            // Must have a MIME type string starting with "text" (also avoids "empty" files)
            if (function_exists('finfo_open')) {
                $finfoMIME = finfo_open(FILEINFO_MIME);
                $finfo = finfo_file($finfoMIME, $filePath);
            }
            if (0 !== strpos($finfo, "text")) {
                continue;
            }
            // Check if file appears to be the same (same size and mtime), if so, continue as we'll assume it's not changed
            if (isset($prevIndexData['files'][$filePath]) &&
                $prevIndexData['files'][$filePath]['size'] === stat($filePath)['size'] &&
                $prevIndexData['files'][$filePath]['mtime'] === stat($filePath)['mtime']
               ) {
                // Continue, as data will be the same and we'll use data from prevIndexData
                continue;
            }
            // Start file data block if we don't have one yet
            if (!isset($indexData['files'][$filePath])) {
                $indexData['files'][$filePath] = [
                    "size" => stat($filePath)['size'],
                    "mtime" => stat($filePath)['mtime']
                ];
            }
            $bFile = false;
            // Exclude banned files
            for ($i = 0;$i < count($ICEcoder['bannedFiles']); $i++) {
                if ("" !== $ICEcoder['bannedFiles'][$i]) {
                    if (false !== strpos($f, str_replace("*", "", $ICEcoder['bannedFiles'][$i]))) {$bFile = true;};
                }
            }
            // Exclude *.min.* minified files
            $minFileText = pathinfo(pathinfo($f)['filename']);
            if (isset($minFileText['extension']) && "min" === $minFileText['extension']) {
                continue;
            }
            if (!$bFile) {
                $lines = file($filePath);
                foreach ($lines as $lineNum => $line) {
                    $functionText = "";
                    $classText = "";
                    // Get function declaration lines, covering most language formats
                    if (
                        // If we have both parens in ( then ) order on the line and...
                        (strpos($line, "(") !== false && strpos($line, "(") < strpos($line, ")")) &&
                        // ...if a particular language and we have a valid format on the same line for it
                        (($filePathExt === "py" || $filePathExt === "mpy" || $filePathExt === "rb") && strpos($line, "def") !== false && strpos($line, "def") < strpos($line, "(")) ||
                        (($filePathExt === "js" || $filePathExt === "ts") && strpos($line, "=>") !== false) ||
                        (($filePathExt === "erl" || $filePathExt === "coffee") && strpos($line, "->") !== false) ||
                        (($filePathExt === "c" || $filePathExt === "h" || $filePathExt === "cpp" || $filePathExt === "ino") && strpos($line, "{") !== false && strpos($line, "{") > strpos($line, "(")) ||
                        ($filePathExt === "go" && strpos($line, "func") !== false && strpos($line, "func") < strpos($line, "(")) ||
                        // ...or if the line contains "function" before opening parens...
                        (strpos($line, "function") !== false && strpos($line, "function") < strpos($line, "("))
                    ) {
                        // ...it's enough of an indication this is a function declaration line, so grab name and args from the line

                        // First, function name - strip away all non alphanum, underscore and parens chars, plus the word "function"
                        // (No need to remove "def" or "func" as we're only concerned by the string between function name and parens and both "def" and "func"
                        // appear before function name in Python, Ruby and Go languages, it's only "function" that's between name and args in some languages)
                        $functionLine = preg_replace('/[^\da-z\s_\(\)]|\bfunction\b/i', '', $line);
                        // Then replace one or more spaces that are followed by an open parens with just the open parens
                        // then explode on the open parens to get the split between name and start of args
                        $functionLine = preg_replace('/\s+\(/', '(', $functionLine);
                        $functionLine = explode("(", $functionLine);
                        // Now we have our function name we can put into an array after some string manipulation
                        $functionName = ltrim(substr($functionLine[0], strrpos($functionLine[0], " ")));

                        // Now, arguments. We need to deal with these seperately as the params may have = chars in them
                        // Grab the parens and everything between and establish the first match
                        preg_match('/\(.*\)/i', $line, $matches);
                        $functionArgs = $matches[0] ?? "";
                        // Postfix commas and equals chars with spaces, then reduce multiple spaces to singles
                        // (This all provides tidy formating of tooltip text on hover rather than what may be in file)
                        $functionArgs = str_replace(',', ', ', $functionArgs);
                        $functionArgs = str_replace('=', ' = ', $functionArgs);
                        $functionArgs = preg_replace('/\s+/', ' ', $functionArgs);

                        // Limit function args list to 200 char max
                        if (strlen($functionArgs) > 200) {
                            $functionArgs = substr($functionArgs, 0, 200) . "...)";
                        }

                        // Finally, we have our function name and args
                        $functionText = [
                            0 => $functionName,
                            1 => $functionArgs
                        ];
                    }
                    // Get class declaration lines (far simpler than functions, as all languages have a very similar format
                    if (strpos($line, "class ") !== false) {
                        $classText = substr($line, strpos($line, "class ") + 6);
                        // Get just the name of the class
                        $classText = explode(" ", $classText);
                    }

                    // Function data
                    if (!empty($functionText) && $functionText[0] !== "") {
                        // Start language block if we don't have one yet
                        if (!isset($indexData['functions'][$filePathExt])) {
                            $indexData['functions'][$filePathExt] = [];
                        }
                        // Set all the data for this function
                        $indexData['functions'][$filePathExt][$functionText[0]] = [
                            "name" => $functionText[0],
                            "range" => [
                                "from" => [
                                    "line" => $lineNum,
                                    "ch" => strpos($line, $functionText[0])
                                ],
                                "to" => [
                                    "line" => $lineNum,
                                    "ch" => (strpos($line, $functionText[0]) + strlen($functionText[0]))
                                ]
                            ],
                            "filePath" => $filePath,
                            "filePathExt" => $filePathExt,
                            "params" => $functionText[1]
                        ];
                    }

                    // Class data
                    if (!empty($classText) && "" !== $classText[0]) {
                        // Start language block if we don't have one yet
                        if (!isset($indexData['classes'][$filePathExt])) {
                            $indexData['classes'][$filePathExt] = [];
                        }
                        // Set all the data for this class
                        $indexData['classes'][$filePathExt][$classText[0]] = [
                            "name" => $classText[0],
                            "range" => [
                                "from" => [
                                    "line" => $lineNum,
                                    "ch" => strpos($line, $classText[0])
                                ],
                                "to" => [
                                    "line" => $lineNum,
                                    "ch" => (strpos($line, $classText[0]) + strlen($classText[0]))
                                ]
                            ],
                            "filePath" => $filePath,
                            "filePathExt" => $filePathExt
                        ];
                    }
                }
            }
        }
    }
    closedir($fp);
    return $ret;
}

// If we don't have a timestamp passed in, in prev data, or it's not the same as what's in the index...
if (!isset($_GET['timestamp']) || !isset($prevIndexData["timestamps"]) || $_GET['timestamp'] != $prevIndexData["timestamps"]["indexed"]) {
	// If we don't have any prev data or something in the doc root changed, we can do an index...
	if (!isset($prevIndexData["timestamps"]) || $prevIndexData["timestamps"]["indexed"] !== stat($docRoot)['mtime']) {
		// Start a new indexData for this run
		$indexData["timestamps"] = [
			"indexed" => stat($docRoot)['mtime'],
			"browser" => $_GET['timestamp'] ?? 0,
			"changed" => true
		];

		// Start running function to index data
		$results = phpGrep($docRoot . $iceRoot, $docRoot . $iceRoot);

		// Overlay indexData ontop of prevIndexData
		$output = array_replace_recursive($prevIndexData, $indexData);

		// If we have a data/git-diff.php file
		if (file_exists($docRoot . $ICEcoderDir . "/data/git-diff.php")) {
			// Get serialized array back out of PHP file inside a comment block as git data for git diff display
			$systemClass->invalidateOPCache($docRoot . $ICEcoderDir . "/data/git-diff.php");
			$gitDiffData = file_get_contents($docRoot . $ICEcoderDir . "/data/git-diff.php");
			if (strpos($gitDiffData, "<?php") !== false) {
                $output["gitDiff"] = $settingsClass->serializedFileData("get", $docRoot . $ICEcoderDir . "/data/git-diff.php");
			}
		}

		// If we have a data/git-content.php file
		if (file_exists($docRoot . $ICEcoderDir . "/data/git-content.php")) {
			// Get serialized array back out of PHP file inside a comment block as git data for git content usage
			$systemClass->invalidateOPCache($docRoot . $ICEcoderDir . "/data/git-content.php");
			$gitContent = file_get_contents($docRoot . $ICEcoderDir . "/data/git-content.php");
			if (strpos($gitContent, "<?php") !== false) {
                $output["gitContent"] = $settingsClass->serializedFileData("get", $docRoot . $ICEcoderDir . "/data/git-content.php");
			}
		}

		// Store the serialized array in PHP comment block for next time
        $settingsClass->serializedFileData("set", $docRoot . $ICEcoderDir . "/data/index.php", $output);
	// Output what we have in our index...
	} else {
		$output = $prevIndexData;
	}
// Else it's the same as last time so do nothing...
} else {
	$output = [
		"timestamps" => [
			"indexed" => stat($docRoot)['mtime'],
			"browser" => (int) $_GET['timestamp'],
			"changed" => false
		]
	];
}

// Output the JSON
echo json_encode($output, JSON_PRETTY_PRINT);
