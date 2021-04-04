<?php
// Exit early if no .git dir
if (false === file_exists(dirname(__FILE__) . "/../.git")) {
    exit;
}
// Only run internally on server, not from internet
if (isset($_SERVER['HTTP_HOST'])) {
    die("Server side script only");
} else {
    echo "Checking for changes via git diff every 2 secs...\n";
}

// Require a re-index dir/file data next time we index
function requireReIndexNextTime() {
        // If we have a data/index.php file
        if (file_exists(dirname(__FILE__)."/../data/index.php")) {
                // Get serialized array back out of PHP file inside a comment block as prevIndexData
                if (function_exists('opcache_invalidate')) {
                        opcache_invalidate(dirname(__FILE__)."/../data/index.php", true);
                }
                $prevIndexData = file_get_contents(dirname(__FILE__)."/../data/index.php");
                if (strpos($prevIndexData, "<?php") !== false) {
                        $prevIndexData = str_replace("<?php\n/*\n\n", "", $prevIndexData);
                        $prevIndexData = str_replace("\n\n*/\n?>", "", $prevIndexData);
                        $prevIndexData = unserialize($prevIndexData);
                        // Set timestamp back to epoch to force a re-index next time
                        $prevIndexData['timestamps']['indexed'] = 0;
                        file_put_contents(dirname(__FILE__)."/../data/index.php", "<?php\n/*\n\n".serialize($prevIndexData)."\n\n*/\n?".">");
                }
        }
}

// Run continuously
while(true) {
    if (true === is_callable("shell_exec") && false === stripos(ini_get('disable_functions'), "shell_exec")) {
        // Get git diff output as a string and MD5 it as a checksum
        $thisMD5 = shell_exec("cd .. && git diff | md5sum");
        // If we have a previous checksum value and the current is different to it
        if (isset($prevMD5) && $thisMD5 !== $prevMD5) {
                echo "Changed ".date("Y-m-d g:i:s a (U)")."\n";
                // Set timestamp of last index to 0 to force a re-index next time we index
                requireReIndexNextTime();
                // Get a list of all that's changed, into an array (filtered to remove false entries)
                $gitData = shell_exec("cd .. && { git diff --name-only --staged ; git ls-files --other --modified --exclude-standard ;} | sort | uniq");
                $diffLines = explode("\n", $gitData);
                $output = ["paths" => array_filter($diffLines)];
                // Store the serialized array in PHP comment block for pick up
                file_put_contents(dirname(__FILE__)."/../data/git-diff.php", "<?php\n/*\n\n".serialize($output)."\n\n*/\n?".">");
                // Set Git contents
                $output = [];
                $paths = array_filter($diffLines);
                shell_exec("cd ..");
                for ($i=0; $i<count($paths); $i++) {
                        if (strpos(mime_content_type(dirname(__FILE__)."/../../".$paths[$i]), "text") !== false) {
                                $content = shell_exec("cd .. && git show HEAD:".$paths[$i]);
                                $wd = dirname(dirname(dirname(__FILE__)));
                                if ($content !== "") {
                                        $output[$wd."/".$paths[$i]] = [
                                                "type" => "modified",
                                                "lastHashContent" => $content
                                        ];
                                }
                        }
                }
                file_put_contents(dirname(__FILE__)."/../data/git-content.php", "<?php\n/*\n\n".serialize($output)."\n\n*/\n?".">");
        }
        // Set prev MD5 to this one, ready for next time
        $prevMD5 = $thisMD5;
    }
    // sleep for 2 secs before loop starts again
    sleep(2);

}
?>
