<?php
include_once("settings-common.php");
$text = $_SESSION['text'];
$t = $text['settings-save-current-files'];

// Save the currently opened files for next time
if ($_SESSION['loggedIn'] && isset($_GET["saveFiles"]) && $_GET['saveFiles']) {
	$settingsContents = file_get_contents($settingsFile,false,$context);

	// Replace our previousFiles var with the the current
	$repPosStart = strpos($settingsContents,'previousFiles"		=> "')+20;
	$repPosEnd = strpos($settingsContents,'",',$repPosStart)-$repPosStart;
	if (!$demoMode) {
		if ($_GET['saveFiles']!="CLEAR") {
			$saveFiles=strClean($_GET['saveFiles']);
			$saveFilesArray = explode(",",$saveFiles);
			$saveFiles="";
			for ($i=0;$i<count($saveFilesArray);$i++) {
				$saveFilesArray[$i] = str_replace("/","|",$docRoot).$saveFilesArray[$i];
				$saveFiles .= $saveFilesArray[$i].",";
			}
			$saveFiles = rtrim($saveFiles,",");
		} else {
			$saveFilesArray = array();
			$saveFiles = "";
		}
		$settingsContents = substr($settingsContents,0,$repPosStart).$saveFiles.substr($settingsContents,($repPosStart+$repPosEnd),strlen($settingsContents));
		// Now update the config file
		if (is_writeable($settingsFile)) {
			$fh = fopen($settingsFile, 'w');
			fwrite($fh, $settingsContents);
			fclose($fh);
		} else {
			echo "<script>top.ICEcoder.message('".$t['Cannot update config...']." lib/".$settingsFile." ".$t['and try again']."');</script>";
		}

		// Update our last10Files var?
		$last10FilesArray = explode(",",$ICEcoder["last10Files"]);
		for ($i=0;$i<count($saveFilesArray);$i++) {
			$inLast10Files = in_array($saveFilesArray[$i],$last10FilesArray);
			if (!$inLast10Files && $saveFilesArray[$i] !="") {
				$repPosStart = strpos($settingsContents,'last10Files"		=> "')+18;
				$repPosEnd = strpos($settingsContents,'"',$repPosStart)-$repPosStart;
				$commaExtra = $ICEcoder["last10Files"]!="" ? "," : "";
				if (count($last10FilesArray)>=10) {$ICEcoder["last10Files"]=substr($ICEcoder["last10Files"],0,strrpos($ICEcoder["last10Files"],','));};
				$settingsContents = substr($settingsContents,0,$repPosStart).$saveFilesArray[$i].$commaExtra.$ICEcoder["last10Files"].substr($settingsContents,($repPosStart+$repPosEnd),strlen($settingsContents));
				// Now update the config file
				if (is_writeable($settingsFile)) {
					$fh = fopen($settingsFile, 'w');
					fwrite($fh, $settingsContents);
					fclose($fh);
				} else {
					echo "<script>top.ICEcoder.message('".$t['Cannot update config...']." lib/".$settingsFile." ".$t['and try again']."');</script>";
				}
			}
		}
	}
	echo '<script>top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);</script>';
}
?>