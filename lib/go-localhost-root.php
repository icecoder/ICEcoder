<?php
include_once("settings.php");
$text = $_SESSION['text'];
$t = $text['settings-update'];

// Update our 'root' value to be blank
// which resets the file manager to localhost root again
if (!$demoMode && isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']) {
	$settingsContents = file_get_contents($settingsFile,false,$context);
	// Replace our root var
	$repPosStart = strpos($settingsContents,'"root"');
	$repPosEnd = strpos($settingsContents,'"checkUpdates"');

	// Compile our new settings
	$settingsContents =
		substr($settingsContents,0,$repPosStart).
		'"root"			=> "",'.PHP_EOL.
		substr($settingsContents,($repPosEnd),strlen($settingsContents));

	// Now update the config file
	if (is_writeable($settingsFile)) {
		$fh = fopen($settingsFile, 'w');
		fwrite($fh, $settingsContents);
		fclose($fh);
		// Now we've reset the root path to localhost root, refresh the file manager to show it
		echo "<script>top.ICEcoder.refreshFileManager();</script>";
	} else {
		echo "<script>top.ICEcoder.message('".$t['Cannot update config']." lib/".$settingsFile." ".$t['and try again']."');</script>";
	}
	?>
<?php
;};
?>