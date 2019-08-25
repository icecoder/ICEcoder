<?php
include_once("settings.php");
$text = $_SESSION['text'];
$t = $text['settings-update'];

// Update our 'root' value to be blank
// which resets the file manager to localhost root again
if (!$demoMode && isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']) {
	$settingsContents = getData("../data/".$settingsFile);
	// Replace our root var
	$repPosStart = strpos($settingsContents,'"root"');
	$repPosEnd = strpos($settingsContents,'"checkUpdates"');

	// Compile our new settings
	$settingsContents =
		substr($settingsContents,0,$repPosStart).
		'"root"			=> "",'.PHP_EOL.
		substr($settingsContents,($repPosEnd),strlen($settingsContents));

	// Now update the config file
	if (is_writeable("../data/".$settingsFile)) {
		$fh = fopen("../data/".$settingsFile, 'w');
		fwrite($fh, "../data/".$settingsContents);
		fclose($fh);

		// Clear any FTP session we may have
		$_SESSION['ftpSiteRef'] = false;

		// Now we've reset the root path to localhost root, refresh the file manager to show it
		echo "<script>top.ICEcoder.refreshFileManager();</script>";
	} else {
		echo "<script>top.ICEcoder.message('".$t['Cannot update config']." data/".$settingsFile." ".$t['and try again']."');</script>";
	}
	?>
<?php
;};
