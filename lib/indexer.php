<?php
include("headers.php");
include("settings.php");

// File extensions to look for functions & classes in
$indexableFileExts = ["php", "js", "coffee", "ts", "rb", "py", "sql", "erl", "java", "jl", "c", "cpp", "ino", "cs", "go", "lua", "pl"];

// Fallback for prevIndexData to start off initially
$prevIndexData = [];

// If we have a data/index.php file
if (file_exists($docRoot.$ICEcoderDir."/data/index.php")) {
    // Get serialized array back out of PHP file inside a comment block as prevIndexData
    $prevIndexData = file_get_contents($docRoot.$ICEcoderDir."/data/index.php");
    if (strpos($prevIndexData, "<?php") !== false) {
        $prevIndexData = str_replace("<?php\n/*\n\n", "", $prevIndexData);
        $prevIndexData = str_replace("\n\n*/\n?>", "", $prevIndexData);
        $prevIndexData = unserialize($prevIndexData);
    }
}

// Roughly 1 in 100 index runs, we'll do a full index
if (mt_rand(1,100) === 50) {
    $prevIndexData = [];
}

// Start a new indexData for this run
$indexData = [];

function phpGrep($path, $base) {
    global $indexableFileExts, $prevIndexData, $indexData;

    $fp = opendir($path);
    global $ICEcoder, $serverType, $docRoot, $ICEcoderDir;
    if (!isset($ret)) {$ret="";};
    $slash = $serverType == strpos($path,"\\")>-1 ? "\\" : "/";
    while($f = readdir($fp)) {
        // Ignore . and .. paths
        if ($f == "." || $f == "..") continue;
        $filePath = $path.$slash.$f;
        $filePathExt = pathinfo($filePath, PATHINFO_EXTENSION);
        // Exclude the folder ICEcoder is running from
        $rootPrefix = '/'.str_replace("/","\/",preg_quote(str_replace("\\","/",$docRoot))).'/';
        $localPath = preg_replace($rootPrefix, '', $filePath, 1);
        if (strpos($localPath, $ICEcoderDir)===0) {
            continue;
        }
        if(is_dir($filePath)) {
            $ret .= phpGrep($filePath, $base);
        } else {
            // Check if we should scan within this file, by only considering files that may contain functions & classes
            if (in_array($filePathExt, $indexableFileExts) === false) {
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
            for ($i=0;$i<count($ICEcoder['bannedFiles']);$i++) {
                if (strpos($f,str_replace("*","",$ICEcoder['bannedFiles'][$i]))!==false) {$bFile = true;};
            }
            if (!$bFile) {
                $lines = file($filePath);
                foreach ($lines as $lineNum => $line) {
                    $functionText = "";
                    $classText = "";
                    // Get function declaration lines
                    if (strpos($line, "function ") !== false) {
                        $functionText = substr($line, strpos($line, "function ") + 9);
                        // Get just the name of the function/class
                        $functionText = explode("(", explode("{", trim($functionText))[0]);
                    }
                    // Get class declaration lines
                    if (strpos($line, "class ") !== false) {
                        $classText = substr($line, strpos($line, "class ") + 6);
                        // Get just the name of the function/class
                        $classText = explode("(", explode("{", trim($classText))[0]);
                    }

                    // Function data
                    if (!empty($functionText)) {
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
                            "params" => trim("(".$functionText[1])
                        ];
                    }

                    // Class data
                    if (!empty($classText)) {
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
                            "filePathExt" => $filePathExt,
                            "params" => trim("(".$classText[1])
                        ];
                    }
                }
            }
        }
    }
    return $ret;
}

// If something in the doc root changed, we can do an index...
if (stat($docRoot)['mtime'] !== $prevIndexData["timestamps"]["indexed"]) {
	// Start a new indexData for this run
	$indexData["timestamps"] = [
		"indexed" => stat($docRoot)['mtime']
	];

	// Start running function to index data
	$results = phpGrep($docRoot.$iceRoot, $docRoot.$iceRoot);

	// Overlay indexData ontop of prevIndexData
	$output = array_replace_recursive($prevIndexData, $indexData);

	// Store the serialized array in PHP comment block for next time
	file_put_contents($docRoot.$ICEcoderDir."/data/index.php", "<?php\n/*\n\n".serialize($output)."\n\n*/\n?".">");
// Else it's the same as last time so do nothing...
} else {
	$output = $prevIndexData;
}

// Output the JSON
echo json_encode($output, JSON_PRETTY_PRINT);
?>
