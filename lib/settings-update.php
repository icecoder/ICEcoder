<?php
include_once("settings-common.php");
$text = $_SESSION['text'];
$t = $text['settings-update'];

// Update this config file?
if (!$demoMode && isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] && isset($_POST["theme"]) && $_POST["theme"]) {
	$settingsContents = file_get_contents($settingsFile,false,$context);
	// Replace our settings vars
	$repPosStart = strpos($settingsContents,'"root"');
	$repPosEnd = strpos($settingsContents,'"plugins"');

	// Has there been a language change?
	$languageUserChanged = $ICEcoder['languageUser'] != $_POST['languageUser'];

	// Prepare all our vars
	$ICEcoder["root"]			= xssClean($_POST['root'],"html");
	$ICEcoder["checkUpdates"]		= isset($_POST['checkUpdates']) && $_POST['checkUpdates'] ? "true" : "false";
	$ICEcoder["openLastFiles"]		= isset($_POST['openLastFiles']) && $_POST['openLastFiles'] ? "true" : "false";
	$ICEcoder["updateDiffOnSave"]		= isset($_POST['updateDiffOnSave']) && $_POST['updateDiffOnSave'] ? "true" : "false";
	$ICEcoder["languageUser"]		= strClean($_POST['languageUser']);
	$ICEcoder["backupsKept"]		= isset($_POST['backupsKept']) && $_POST['backupsKept'] ? "true" : "false";
	$ICEcoder["backupsDays"]		= intval($_POST['backupsDays']);
	$ICEcoder["findFilesExclude"]		= 'array("'.str_replace(',','","',str_replace(" ","",strClean($_POST['findFilesExclude']))).'")';
	$ICEcoder["codeAssist"]			= isset($_POST['codeAssist']) && $_POST['codeAssist'] ? "true" : "false";
	$ICEcoder["visibleTabs"]		= isset($_POST['visibleTabs']) && $_POST['visibleTabs'] ? "true" : "false";
	$ICEcoder["lockedNav"]			= isset($_POST['lockedNav']) && $_POST['lockedNav'] ? "true" : "false";
	$ICEcoder["tagWrapperCommand"]		= strClean($_POST['tagWrapperCommand']);
	$ICEcoder["autoComplete"]		= strClean($_POST['autoComplete']);
	if ($_POST['password']!="")		{$ICEcoder["password"] = generateHash(strClean($_POST['password']));};
	$ICEcoder["bannedFiles"]		= 'array("'.str_replace(',','","',str_replace(" ","",strClean($_POST['bannedFiles']))).'")';
	$ICEcoder["bannedPaths"]		= 'array("'.str_replace(',','","',str_replace(" ","",strClean($_POST['bannedPaths']))).'")';
	$ICEcoder["allowedIPs"]			= 'array("'.str_replace(',','","',str_replace(" ","",strClean($_POST['allowedIPs']))).'")';
	$ICEcoder["theme"]			= strClean($_POST['theme']);
	$ICEcoder["fontSize"]			= strClean($_POST['fontSize']);
	$ICEcoder["lineWrapping"]		= strClean($_POST['lineWrapping']);
	$ICEcoder["indentWithTabs"]		= strClean($_POST['indentWithTabs']);
	$ICEcoder["indentAuto"]			= strClean($_POST['indentAuto']);
	$ICEcoder["indentSize"]			= intval($_POST['indentSize']);
	$ICEcoder["pluginPanelAligned"]		= strClean($_POST['pluginPanelAligned']);
	$ICEcoder["bugFilePaths"]		= 'array("'.str_replace(',','","',str_replace(" ","",strClean($_POST['bugFilePaths']))).'")';
	$ICEcoder["bugFileCheckTimer"]		= intval($_POST['bugFileCheckTimer']);
	$ICEcoder["bugFileMaxLines"]		= intval($_POST['bugFileMaxLines']);
	$ICEcoder["githubAuthToken"]		= strClean($_POST['githubAuthToken']);

	$settingsArray = array("root","checkUpdates","openLastFiles","updateDiffOnSave","languageUser","backupsKept","backupsDays","findFilesExclude","codeAssist","visibleTabs","lockedNav","tagWrapperCommand","autoComplete","password","bannedFiles","bannedPaths","allowedIPs","theme","fontSize","lineWrapping","indentWithTabs","indentAuto","indentSize","pluginPanelAligned","bugFilePaths","bugFileCheckTimer","bugFileMaxLines","githubAuthToken");
	$settingsNew = "";
	for ($i=0;$i<count($settingsArray);$i++) {
		$settingsNew .= '"'.$settingsArray[$i].'"	=> ';
		// Wrap certain values in double quotes
		$settingWrap = $settingsArray[$i]=="root"||$settingsArray[$i]=="password"||$settingsArray[$i]=="languageUser"||$settingsArray[$i]=="theme"||$settingsArray[$i]=="fontSize"||$settingsArray[$i]=="tagWrapperCommand"||$settingsArray[$i]=="autoComplete"||$settingsArray[$i]=="pluginPanelAligned"||$settingsArray[$i]=="githubAuthToken" ? '"' : '';
		$settingsNew .= $settingWrap.$ICEcoder[$settingsArray[$i]].$settingWrap.','.PHP_EOL;
	}

	// Compile our new settings
	$settingsContents = substr($settingsContents,0,$repPosStart).$settingsNew.substr($settingsContents,($repPosEnd),strlen($settingsContents));

	// Now update the config file
	if (is_writeable($settingsFile)) {
		$fh = fopen($settingsFile, 'w');
		fwrite($fh, $settingsContents);
		fclose($fh);
	} else {
		echo "<script>top.ICEcoder.message('".$t['Cannot update config']." lib/".$settingsFile." ".$t['and try again']."');</script>";
	}

	// OK, now the config file has been updated, update our current session with new arrays
	$settingsArray = array("findFilesExclude","bannedFiles","allowedIPs");
	for ($i=0;$i<count($settingsArray);$i++) {
		$_SESSION[$settingsArray[$i]] = $ICEcoder[$settingsArray[$i]] = explode(",",str_replace(" ","",strClean($_POST[$settingsArray[$i]])));
	}

	// Work out the theme to use now
	$ICEcoder["theme"]=="default" ? $themeURL = 'lib/editor.css' : $themeURL = $ICEcoder["codeMirrorDir"].'/theme/'.$ICEcoder["theme"].'.css';
	$themeURL .= "?microtime=".microtime(true);

	// Do we need a file manager refresh?
	$refreshFM = $_POST['changedFileSettings']=="true" ? "true" : "false";

	// Change multiUser and enableRegistration in config___settings.php
	$generalSettingsContents = file_get_contents($configSettings,false,$context);
	$isMultiUser = isset($_POST['multiUser']) && $_POST['multiUser'] ? "true" : "false";
	$generalSettingsContents = str_replace('"multiUser"		=> true,','"multiUser"		=> '.$isMultiUser.',',$generalSettingsContents);
	$generalSettingsContents = str_replace('"multiUser"		=> false,','"multiUser"		=> '.$isMultiUser.',',$generalSettingsContents);

	$isEnableRegistration = isset($_POST['enableRegistration']) && $_POST['enableRegistration'] ? "true" : "false";
	$generalSettingsContents = str_replace('"enableRegistration"	=> true','"enableRegistration"	=> '.$isEnableRegistration,$generalSettingsContents);
	$generalSettingsContents = str_replace('"enableRegistration"	=> false','"enableRegistration"	=> '.$isEnableRegistration,$generalSettingsContents);

	if (is_writeable($configSettings)) {
		$fConfigSettings = fopen($configSettings, 'w');
		fwrite($fConfigSettings, $generalSettingsContents);
		fclose($fConfigSettings);
	} else {
		echo "<script>top.ICEcoder.message('".$t['Cannot update config']." lib/".$configSettings." ".$t['and try again']."');</script>";
	}

	$githubAuthTokenSet = $ICEcoder["githubAuthToken"] != "" ? "true" : "false";

	// If we've changed langugage, reload ICEcoder now
	if ($languageUserChanged) {
		echo '<script>top.window.location = "../";</script>';
		die('Reloading ICEcoder after language change');
	}

	// With all that worked out, we can now hide the settings screen and apply the new settings
	$jsBugFilePaths = "['".str_replace(",","','",str_replace(" ","",strClean($_POST['bugFilePaths'])))."']";
	echo "<script>top.ICEcoder.settingsScreen('hide');top.ICEcoder.useNewSettings('".$themeURL."',".$ICEcoder["codeAssist"].",".$ICEcoder["lockedNav"].",'".$ICEcoder["tagWrapperCommand"]."','".$ICEcoder["autoComplete"]."',".$ICEcoder["visibleTabs"].",'".$ICEcoder["fontSize"]."',".$ICEcoder["lineWrapping"].",".$ICEcoder["indentWithTabs"].",".$ICEcoder["indentAuto"].",".$ICEcoder["indentSize"].",'".$ICEcoder["pluginPanelAligned"]."',".$jsBugFilePaths.",".$ICEcoder["bugFileCheckTimer"].",".$ICEcoder["bugFileMaxLines"].",'".$githubAuthTokenSet."',".$ICEcoder["updateDiffOnSave"].",".$refreshFM.");top.iceRoot = '".$ICEcoder["root"]."';</script>";
}
?>