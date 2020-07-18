<?php
require_once dirname(__FILE__) . "/../classes/Settings.php";

$settingsClass = new \ICEcoder\Settings();

include_once "settings-common.php";
$text = $_SESSION['text'];
$t = $text['settings-update'];

// Update this config file?
if (!$demoMode && true === isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] &&
    (true === isset($_POST["theme"]) && $_POST["theme"] || true === isset($_GET['action']) && "turnOffTutorialOnLogin" === $_GET['action'])
) {
	// Just updating tutorialOnLogin setting
	if (true === isset($_GET['action']) && "turnOffTutorialOnLogin" === $_GET['action']) {
        if (true === $settingsClass->updateConfigUsersSettings($settingsFile, ['tutorialOnLogin' => false])) {
            $ICEcoder['tutorialOnLogin'] = false;
        } else {
            echo "<script>parent.ICEcoder.message('" . $t['Cannot update config'] . " data/" . $settingsFile . " " . $t['and try again'] . "');</script>";
        }
        exit;
    }

	$currentSettings = $settingsClass->getConfigUsersSettings($settingsFile);

	// Has there been a language change?
	$languageUserChanged = $ICEcoder['languageUser'] != $_POST['languageUser'];

	// Prepare all our vars
    $updatedSettings = [
        "versionNo"          => $currentSettings['versionNo'],
        "configCreateDate"   => $currentSettings['configCreateDate'],
        "root"               => xssClean($_POST['root'], "html"),
        "checkUpdates"       => isset($_POST['checkUpdates']) && $_POST['checkUpdates'],
        "openLastFiles"      => isset($_POST['openLastFiles']) && $_POST['openLastFiles'],
        "updateDiffOnSave"   => isset($_POST['updateDiffOnSave']) && $_POST['updateDiffOnSave'],
        "languageUser"       => $_POST['languageUser'],
        "backupsKept"        => isset($_POST['backupsKept']) && $_POST['backupsKept'],
        "backupsDays"        => intval($_POST['backupsDays']),
        "deleteToTmp"        => isset($_POST['deleteToTmp']) && $_POST['deleteToTmp'],
        "findFilesExclude"   => explode(",", str_replace(" ", "", $_POST['findFilesExclude'])),
        "codeAssist"         => isset($_POST['codeAssist']) && $_POST['codeAssist'],
        "visibleTabs"        => isset($_POST['visibleTabs']) && $_POST['visibleTabs'],
        "lockedNav"          => isset($_POST['lockedNav']) && $_POST['lockedNav'],
        "tagWrapperCommand"  => $_POST['tagWrapperCommand'],
        "autoComplete"       => $_POST['autoComplete'],
        "password"           => $currentSettings['password'],
        "bannedFiles"        => explode(",", str_replace(" ", "", $_POST['bannedFiles'])),
        "bannedPaths"        => explode(",", str_replace(" ", "", $_POST['bannedPaths'])),
        "allowedIPs"         => explode(",", str_replace(" ", "", $_POST['allowedIPs'])),
        "autoLogoutMins"     => intval($_POST['autoLogoutMins']),
        "theme"              => $_POST['theme'],
        "fontSize"           => $_POST['fontSize'],
        "lineWrapping"       => $_POST['lineWrapping'],
        "lineNumbers"        => $_POST['lineNumbers'],
        "showTrailingSpace"  => $_POST['showTrailingSpace'],
        "matchBrackets"      => $_POST['matchBrackets'],
        "autoCloseTags"      => $_POST['autoCloseTags'],
        "autoCloseBrackets"  => $_POST['autoCloseBrackets'],
        "indentType"         => $_POST['indentType'],
        "indentAuto"         => $_POST['indentAuto'],
        "indentSize"         => intval($_POST['indentSize']),
        "pluginPanelAligned" => $_POST['pluginPanelAligned'],
        "scrollbarStyle"     => $_POST['scrollbarStyle'],
        "bugFilePaths"       => explode(",", str_replace(" ", "", $_POST['bugFilePaths'])),
        "bugFileCheckTimer"  => intval($_POST['bugFileCheckTimer']) >= 0 ? intval($_POST['bugFileCheckTimer']) : 0,
        "bugFileMaxLines"    => intval($_POST['bugFileMaxLines']),
        "plugins"            => $currentSettings['plugins'],
        "ftpSites"           => $currentSettings['ftpSites'],
        "tutorialOnLogin"    => isset($_POST['tutorialOnLogin']) && $_POST['tutorialOnLogin'],
        "tipsOnLogin"        => isset($_POST['tipsOnLogin']) && $_POST['tipsOnLogin'],
        "previousFiles"      => $currentSettings['previousFiles'],
        "last10Files"        => $currentSettings['last10Files'],
        "favoritePaths"      => $currentSettings['favoritePaths'],
    ];

    if ($_POST['password']!="")		{$updatedSettings["password"] = generateHash($_POST['password']);};

    $ICEcoder = array_merge($ICEcoder, $updatedSettings);

	// Now update the config file
	if (is_writeable("../data/".$settingsFile)) {
	    $settingsClass->setConfigUsersSettings($settingsFile, $updatedSettings);
	} else {
		echo "<script>parent.ICEcoder.message('" . $t['Cannot update config'] . " data/" . $settingsFile . " " . $t['and try again'] . "');</script>";
	}

	// OK, now the config file has been updated, update our current session with new arrays
	$settingsArray = array("findFilesExclude", "bannedFiles", "allowedIPs");
	for ($i = 0; $i <count($settingsArray); $i++) {
		$_SESSION[$settingsArray[$i]] = $ICEcoder[$settingsArray[$i]] = explode(",", str_replace(" ", "", $_POST[$settingsArray[$i]]));
	}

	// Work out the theme to use now
    $themeURL = 'assets/css/theme/';
	$themeURL .= "default" === $ICEcoder["theme"] ? 'icecoder.css' : $ICEcoder["theme"] . '.css';
	$themeURL .= "?microtime=" . microtime(true);

	// Do we need a file manager refresh?
	$refreshFM = $_POST['changedFileSettings'] == "true" ? "true" : "false";

    // Update global config settings file
    $ICEcoderGlobalFileName = $settingsClass->getConfigGlobalFileDetails()['fileName'];
    $ICEcoderSettingsFromFile = $settingsClass->getConfigGlobalSettings();
    $ICEcoderSettingsFromFile['multiUser'] = isset($_POST['multiUser']) && $_POST['multiUser'];
    $ICEcoderSettingsFromFile['enableRegistration'] = isset($_POST['enableRegistration']) && $_POST['enableRegistration'];
    if (false === $settingsClass->setConfigGlobalSettings($ICEcoderSettingsFromFile)) {
        echo "<script>parent.ICEcoder.message('" . $t['Cannot update config'] . " data/" . $ICEcoderGlobalFileName . " " . $t['and try again'] . "');</script>";
    }

	// If we've changed language, reload ICEcoder now
	if ($languageUserChanged) {
		echo '<script>parent.window.location = "../";</script>';
		die('Reloading ICEcoder after language change');
	}

	// With all that worked out, we can now hide the settings screen and apply the new settings
	$jsBugFilePaths = "['" . str_replace(",", "','", str_replace(" ", "", $_POST['bugFilePaths'])) . "']";
	echo "<script>parent.ICEcoder.settingsScreen('hide'); parent.ICEcoder.useNewSettings('" .
        $themeURL . "'," .
        (true === $ICEcoder["codeAssist"] ? "true" : "false") . "," .
        (true === $ICEcoder["lockedNav"] ? "true" : "false") . ",'" .
        $ICEcoder["tagWrapperCommand"] . "','" .
        (true === $ICEcoder["autoComplete"] ? "true" : "false") . "'," .
        (true === $ICEcoder["visibleTabs"] ? "true" : "false") . ",'" .
        $ICEcoder["fontSize"] . "'," .
        (true === $ICEcoder["lineWrapping"] ? "true" : "false") . "," .
        (true === $ICEcoder["lineNumbers"] ? "true" : "false") . "," .
        (true === $ICEcoder["showTrailingSpace"] ? "true" : "false") . "," .
        (true === $ICEcoder["matchBrackets"] ? "true" : "false") . "," .
        (true === $ICEcoder["autoCloseTags"] ? "true" : "false") . "," .
        (true === $ICEcoder["autoCloseBrackets"] ? "true" : "false") . ",'" .
        $ICEcoder["indentType"] . "'," .
        (true === $ICEcoder["indentAuto"] ? "true" : "false") . "," .
        $ICEcoder["indentSize"] . ",'" .
        $ICEcoder["pluginPanelAligned"] . "','" .
        $ICEcoder["scrollbarStyle"] . "'," .
        $jsBugFilePaths . "," .
        $ICEcoder["bugFileCheckTimer"] . "," .
        $ICEcoder["bugFileMaxLines"] . "," .
        (true === $ICEcoder["updateDiffOnSave"] ? "true" : "false") . "," .
        $ICEcoder["autoLogoutMins"] . "," .
        $refreshFM .
        ");iceRoot = '" . $ICEcoder["root"] .
        "';</script>";
}
