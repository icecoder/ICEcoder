<?php
// include("headers.php");
// include("settings.php");
$gitData = shell_exec("{ git diff --name-only --staged ; git ls-files --other --modified --exclude-standard ;} | sort | uniq");
$diffLines = explode("\n", $gitData);
$output = ["paths" => array_filter($diffLines)];
// Store the serialized array in PHP comment block for pick up
file_put_contents($docRoot.$ICEcoderDir."/data/git-diff.php", "<?php\n/\n\n".serialize($output)."\n\n/\n?".">");
?>
