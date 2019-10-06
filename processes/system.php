<?php
// include("headers.php");
// include("settings.php");
while(true) {
       $gitData = shell_exec("cd .. && { git diff --name-only --staged ; git ls-files --other --modified --exclude-standard ;} | sort | uniq");
       $diffLines = explode("\n", $gitData);
       $output = ["paths" => array_filter($diffLines)];
       // Store the serialized array in PHP comment block for pick up
       file_put_contents(dirname(__FILE__)."/../data/git-diff.php", "<?php\n/*\n\n".serialize($output)."\n\n*/\n?".">");
       sleep(2);
}
?>
