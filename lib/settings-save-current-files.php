<?php
require_once dirname(__FILE__) . "/../classes/Settings.php";

$settingsClass = new \ICEcoder\Settings();

include_once("settings-common.php");
$text = $_SESSION['text'];
$t = $text['settings-save-current-files'];

// Save the currently opened files for next time
if ($_SESSION['loggedIn'] && isset($_GET["saveFiles"]) && $_GET['saveFiles']) {
	if (!$demoMode) {
        $saveFilesArray = [];
		if ("CLEAR" !== $_GET['saveFiles']) {
			$saveFilesArray = explode(",", $_GET['saveFiles']);
			for ($i = 0; $i < count($saveFilesArray); $i++) {
				$saveFilesArray[$i] = str_replace("/", "|", $docRoot) . $saveFilesArray[$i];
			}
		}
		// Now update the config file
		if (false === $settingsClass->savePreviousFiles($settingsFile, $saveFilesArray)) {
			echo "<script>parent.parent.ICEcoder.message('".$t['Cannot update config...']." data/".$settingsFile." ".$t['and try again']."');</script>";
		}
		// Update our last10Files var?
		for ($i = 0; $i < count($saveFilesArray); $i++) {
			$inLast10Files = in_array($saveFilesArray[$i], $ICEcoder["last10Files"]);
			if (false === $inLast10Files && "" !== $saveFilesArray[$i]) {
                $ICEcoder["last10Files"][] = $saveFilesArray[$i];
                echo "<script>console.log(".count($saveFilesArray).count($ICEcoder["last10Files"]).");</script>";
				if (10 <= count($ICEcoder["last10Files"])) {
				    $ICEcoder["last10Files"] = array_slice($ICEcoder["last10Files"], 0, 10);
				};
				// Now update the config file
				if (false === $settingsClass->saveLast10Files($settingsFile, $ICEcoder["last10Files"])) {
					echo "<script>parent.parent.ICEcoder.message('".$t['Cannot update config...']." data/".$settingsFile." ".$t['and try again']."');</script>";
				} else {
                    echo "<script>console.log(".$ICEcoder["last10Files"][$i].");</script>";
                }
			}
		}
	}
	echo '<script>parent.parent.ICEcoder.serverMessage();parent.parent.ICEcoder.serverQueue("del",0);</script>';
}
