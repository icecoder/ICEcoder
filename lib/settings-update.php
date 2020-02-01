<?php
include_once("settings-common.php");
$text = $_SESSION['text'];
$t = $text['settings-update'];

// Update this config file?
if (!$demoMode && isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] && isset($_POST["theme"]) && $_POST["theme"]) {
	$settingsContents = getData("../data/".$settingsFile);
	// Replace our settings vars
	$repPosStart = strpos($settingsContents,'"root"');
	$repPosEnd = strpos($settingsContents,'"plugins"');

	// Has there been a language change?
	$languageUserChanged = $ICEcoder['languageUser'] != $_POST['languageUser'];

	// Prepare all our vars
    $ICEcoder = array_merge(
        $ICEcoder,
	    [
	        "root"               => xssClean($_POST['root'],"html"),
            "checkUpdates"       => isset($_POST['checkUpdates']) && $_POST['checkUpdates'] ? "true" : "false",
            "openLastFiles"      => isset($_POST['openLastFiles']) && $_POST['openLastFiles'] ? "true" : "false",
            "updateDiffOnSave"   => isset($_POST['updateDiffOnSave']) && $_POST['updateDiffOnSave'] ? "true" : "false",
            "languageUser"       => $_POST['languageUser'],
            "backupsKept"        => isset($_POST['backupsKept']) && $_POST['backupsKept'] ? "true" : "false",
            "backupsDays"        => intval($_POST['backupsDays']),
            "deleteToTmp"        => isset($_POST['deleteToTmp']) && $_POST['deleteToTmp'] ? "true" : "false",
            "findFilesExclude"   => 'array("'.str_replace(',','","',str_replace(" ","",$_POST['findFilesExclude'])).'")',
            "codeAssist"         => isset($_POST['codeAssist']) && $_POST['codeAssist'] ? "true" : "false",
            "visibleTabs"        => isset($_POST['visibleTabs']) && $_POST['visibleTabs'] ? "true" : "false",
            "lockedNav"          => isset($_POST['lockedNav']) && $_POST['lockedNav'] ? "true" : "false",
            "tagWrapperCommand"  => $_POST['tagWrapperCommand'],
            "autoComplete"       => $_POST['autoComplete'],
            "bannedFiles"        => 'array("'.str_replace(',','","',str_replace(" ","",$_POST['bannedFiles'])).'")',
            "bannedPaths"        => 'array("'.str_replace(',','","',str_replace(" ","",$_POST['bannedPaths'])).'")',
            "allowedIPs"         => 'array("'.str_replace(',','","',str_replace(" ","",$_POST['allowedIPs'])).'")',
            "autoLogoutMins"     => intval($_POST['autoLogoutMins']),
            "theme"              => $_POST['theme'],
            "fontSize"           => $_POST['fontSize'],
            "lineWrapping"       => $_POST['lineWrapping'],
            "lineNumbers"        => $_POST['lineNumbers'],
            "showTrailingSpace"  => $_POST['showTrailingSpace'],
            "matchBrackets"      => $_POST['matchBrackets'],
            "autoCloseTags"      => $_POST['autoCloseTags'],
            "autoCloseBrackets"  => $_POST['autoCloseBrackets'],
            "indentWithTabs"     => $_POST['indentWithTabs'],
            "indentAuto"         => $_POST['indentAuto'],
            "indentSize"         => intval($_POST['indentSize']),
            "pluginPanelAligned" => $_POST['pluginPanelAligned'],
            "bugFilePaths"       => 'array("'.str_replace(',','","',str_replace(" ","",$_POST['bugFilePaths'])).'")',
            "bugFileCheckTimer"  => intval($_POST['bugFileCheckTimer']) >= 0 ? intval($_POST['bugFileCheckTimer']) : 0,
            "bugFileMaxLines"    => intval($_POST['bugFileMaxLines']),
            "githubAuthToken"    => $_POST['githubAuthToken']
        ]
    );

    if ($_POST['password']!="")		{$ICEcoder["password"] = generateHash($_POST['password']);};

	$settingsArray = array("root","checkUpdates","openLastFiles","updateDiffOnSave","languageUser","backupsKept","backupsDays","deleteToTmp","findFilesExclude","codeAssist","visibleTabs","lockedNav","tagWrapperCommand","autoComplete","password","bannedFiles","bannedPaths","allowedIPs","autoLogoutMins","theme","fontSize","lineWrapping","lineNumbers","showTrailingSpace","matchBrackets","autoCloseTags","autoCloseBrackets","indentWithTabs","indentAuto","indentSize","pluginPanelAligned","bugFilePaths","bugFileCheckTimer","bugFileMaxLines","githubAuthToken");
	$settingsNew = "";
	for ($i=0;$i<count($settingsArray);$i++) {
		$settingsNew .= '"'.$settingsArray[$i].'"	=> ';
		// Wrap certain values in double quotes
		$settingWrap = $settingsArray[$i]=="root"||$settingsArray[$i]=="password"||$settingsArray[$i]=="languageUser"||$settingsArray[$i]=="theme"||$settingsArray[$i]=="fontSize"||$settingsArray[$i]=="tagWrapperCommand"||$settingsArray[$i]=="autoComplete"||$settingsArray[$i]=="pluginPanelAligned"||$settingsArray[$i]=="githubAuthToken" ? '"' : '';

		if ($settingsArray[$i]=="password") {
			$settingsNew .= str_replace("\$", "\\$", $settingWrap.$ICEcoder[$settingsArray[$i]].$settingWrap.','.PHP_EOL);
		} else {
			$settingsNew .= $settingWrap.$ICEcoder[$settingsArray[$i]].$settingWrap.','.PHP_EOL;
		}
	}

	// Compile our new settings
	$settingsContents = substr($settingsContents,0,$repPosStart).$settingsNew.substr($settingsContents,($repPosEnd),strlen($settingsContents));

	// Now update the config file
	if (is_writeable("../data/".$settingsFile)) {
		$fh = fopen("../data/".$settingsFile, 'w');
		fwrite($fh, $settingsContents);
		fclose($fh);
	} else {
		echo "<script>top.ICEcoder.message('".$t['Cannot update config']." data/".$settingsFile." ".$t['and try again']."');</script>";
	}

	// OK, now the config file has been updated, update our current session with new arrays
	$settingsArray = array("findFilesExclude","bannedFiles","allowedIPs");
	for ($i=0;$i<count($settingsArray);$i++) {
		$_SESSION[$settingsArray[$i]] = $ICEcoder[$settingsArray[$i]] = explode(",",str_replace(" ","",$_POST[$settingsArray[$i]]));
	}

	// Work out the theme to use now
	$ICEcoder["theme"]=="default" ? $themeURL = 'lib/editor.css' : $themeURL = $ICEcoder["codeMirrorDir"].'/theme/'.$ICEcoder["theme"].'.css';
	$themeURL .= "?microtime=".microtime(true);

	// Do we need a file manager refresh?
	$refreshFM = $_POST['changedFileSettings']=="true" ? "true" : "false";

	// Change multiUser and enableRegistration in config___settings.php
	$generalSettingsContents = getData(dirname(__FILE__)."/../data/".$configSettings);
	$isMultiUser = isset($_POST['multiUser']) && $_POST['multiUser'] ? "true" : "false";
	$generalSettingsContents = str_replace('"multiUser"		=> true,','"multiUser"		=> '.$isMultiUser.',',$generalSettingsContents);
	$generalSettingsContents = str_replace('"multiUser"		=> false,','"multiUser"		=> '.$isMultiUser.',',$generalSettingsContents);

	$isEnableRegistration = isset($_POST['enableRegistration']) && $_POST['enableRegistration'] ? "true" : "false";
	$generalSettingsContents = str_replace('"enableRegistration"	=> true','"enableRegistration"	=> '.$isEnableRegistration,$generalSettingsContents);
	$generalSettingsContents = str_replace('"enableRegistration"	=> false','"enableRegistration"	=> '.$isEnableRegistration,$generalSettingsContents);

	if (is_writeable(dirname(__FILE__)."/../data/".$configSettings)) {
		$fConfigSettings = fopen(dirname(__FILE__)."/../data/".$configSettings, 'w');
		fwrite($fConfigSettings, $generalSettingsContents);
		fclose($fConfigSettings);
	} else {
		echo "<script>top.ICEcoder.message('".$t['Cannot update config']." data/".$configSettings." ".$t['and try again']."');</script>";
	}

	$githubAuthTokenSet = $ICEcoder["githubAuthToken"] != "" ? "true" : "false";

	// If we've changed langugage, reload ICEcoder now
	if ($languageUserChanged) {
		echo '<script>top.window.location = "../";</script>';
		die('Reloading ICEcoder after language change');
	}

	// With all that worked out, we can now hide the settings screen and apply the new settings
	$jsBugFilePaths = "['".str_replace(",","','",str_replace(" ","",$_POST['bugFilePaths']))."']";
	echo "<script>top.ICEcoder.settingsScreen('hide');top.ICEcoder.useNewSettings('".$themeURL."',".$ICEcoder["codeAssist"].",".$ICEcoder["lockedNav"].",'".$ICEcoder["tagWrapperCommand"]."','".$ICEcoder["autoComplete"]."',".$ICEcoder["visibleTabs"].",'".$ICEcoder["fontSize"]."',".$ICEcoder["lineWrapping"].",".$ICEcoder["lineNumbers"].",".$ICEcoder["showTrailingSpace"].",".$ICEcoder["matchBrackets"].",".$ICEcoder["autoCloseTags"].",".$ICEcoder["autoCloseBrackets"].",".$ICEcoder["indentWithTabs"].",".$ICEcoder["indentAuto"].",".$ICEcoder["indentSize"].",'".$ICEcoder["pluginPanelAligned"]."',".$jsBugFilePaths.",".$ICEcoder["bugFileCheckTimer"].",".$ICEcoder["bugFileMaxLines"].",'".$githubAuthTokenSet."',".$ICEcoder["updateDiffOnSave"].",".$ICEcoder["autoLogoutMins"].",".$refreshFM.");top.iceRoot = '".$ICEcoder["root"]."';</script>";
}
