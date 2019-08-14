<?php
include("headers.php");
include("settings.php");

$resultsArray = [];

function phpGrep($q, $path, $base) {
    global $resultsArray;

    $fp = opendir($path);
    global $ICEcoder, $serverType, $docRoot, $ICEcoderDir;
    if (!isset($ret)) {$ret="";};
    $slash = $serverType == strpos($path,"\\")>-1 ? "\\" : "/";
    while($f = readdir($fp)) {
        // Ignore . and .. paths
        if(preg_match("#^\.+$#", $f)) continue;
        $fullPath = $path.$slash.$f;

        // Exclude the folder ICEcoder is running from
        $rootPrefix = '/'.str_replace("/","\/",preg_quote(str_replace("\\","/",$docRoot))).'/';
        $localPath = preg_replace($rootPrefix, '', $fullPath, 1);
        if (strpos($localPath, $ICEcoderDir)===0) {
            continue;
        }
        if(is_dir($fullPath)) {
            $ret .= phpGrep($q, $fullPath, $base);
        } else {
            $bFile = false;
            // Exclude banned files
            for ($i=0;$i<count($ICEcoder['bannedFiles']);$i++) {
                if (strpos($f,str_replace("*","",$ICEcoder['bannedFiles'][$i]))!==false) {$bFile = true;};
            }
            if (!$bFile) {
                $lines = file($fullPath);
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
                    if (!empty($functionText)) {
                        $resultsArray['functions'][$functionText[0]] = [
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
                            "fullPath" => $fullPath,
                            "params" => trim("(".$functionText[1])
                        ];
                    }
                    if (!empty($classText)) {
                        $resultsArray['classes'][$classText[0]] = [
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
                            "fullPath" => $fullPath,
                            "params" => trim("(".$classText[1])
                        ];
                    }
                }
            }
        }
    }
    return $ret;
}

$results = phpGrep("function", $docRoot.$iceRoot, $docRoot.$iceRoot);
echo json_encode($resultsArray, JSON_PRETTY_PRINT);
?>
