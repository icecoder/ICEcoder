<?php
// Establish settings and users template filenames
$configSettings = 'config-settings.php';
$configUsersTemplate = 'template-users.php';

// Create a new config file if it doesn't exist yet.
// The reason we create it, is so it has PHP write permissions, meaning we can update it later
if (!file_exists(dirname(__FILE__)."/../data/".$configSettings)) {
	// Include our params to make use of (as $newConfigSettingsFile)
	include(dirname(__FILE__)."/template-system.php");
	if ($fConfigSettings = fopen(dirname(__FILE__)."/../data/".$configSettings, 'w')) {
		fwrite($fConfigSettings, $newConfigSettingsFile);
		fclose($fConfigSettings);
	} else {
		$reqsPassed = false;
		$reqsFailures = ["phpCreateConfig"];
		include(dirname(__FILE__)."/requirements.php");
	}
}

// Load config settings
include(dirname(__FILE__)."/../data/".$configSettings);

// Load common functions
include_once(dirname(__FILE__)."/settings-common.php");

// Establish user settings file
$username = "";
if (isset($_POST['username']) && $_POST['username'] != "") {$username = strClean($_POST['username']."-");};
if (isset($_SESSION['username']) && $_SESSION['username'] != "") {$username = strClean($_SESSION['username']."-");};
$settingsFile = 'config-'.$username.str_replace(".","_",str_replace("www.","",$_SERVER['SERVER_NAME'])).'.php';

// Login is default
$setPWorLogin = "login";

// Create user settings file if it doesn't exist
if (!file_exists(dirname(__FILE__)."/../data/".$settingsFile) && $ICEcoderSettings['enableRegistration']) {
	if (!copy(dirname(__FILE__)."/".$configUsersTemplate, dirname(__FILE__)."/../data/".$settingsFile)) {
		$reqsPassed = false;
		$reqsFailures = ["phpCreateSettings"];
		include(dirname(__FILE__)."/requirements.php");
	}
	$setPWorLogin = "set password";
}

// Load user settings
include(dirname(__FILE__)."/../data/".$settingsFile);

// Remove any previous files that are no longer there
$prevFiles = explode(",",$ICEcoderUserSettings['previousFiles']);
$prevFilesAvail = "";
for ($i=0; $i<count($prevFiles); $i++) {
	if (file_exists(str_replace("|","/",$prevFiles[$i]))) {
		$prevFilesAvail .= $prevFiles[$i].",";
	}
}
$prevFilesAvail = rtrim($prevFilesAvail,",");
$ICEcoderUserSettings['previousFiles'] = $prevFilesAvail;

// Replace our config created date with the filemtime?
if (basename($_SERVER['SCRIPT_NAME']) == "index.php" && $ICEcoderUserSettings['configCreateDate'] == 0) {
	$settingsContents = getData(dirname(__FILE__)."/../data/".$settingsFile);
	clearstatcache();
	$configfilemtime = filemtime(dirname(__FILE__)."/../data/"."config-settings.php");
	// Make it a number (avoids null, undefined etc)
	$configfilemtime = intval($configfilemtime);
	// Set it to the epoch time now if we don't have a real value
	if ($configfilemtime == 0) {
		$configfilemtime = time();
	}
	$settingsContents = str_replace('"configCreateDate"	=> 0,','"configCreateDate"	=> '.$configfilemtime.',',$settingsContents);
	// Now update the config file
	if (!$fh = fopen(dirname(__FILE__)."/../data/".$settingsFile, 'w')) {
		$reqsPassed = false;
		$reqsFailures = ["phpUpdateSettings"];
		include(dirname(__FILE__)."/requirements.php");
	}
	fwrite($fh, $settingsContents);
	fclose($fh);
	// Set the new value in array
	$ICEcoderUserSettings['configCreateDate'] = $configfilemtime;
}

// On mismatch of settings file to system, rename to .old and reload
If ($ICEcoderUserSettings["versionNo"] != $ICEcoderSettings["versionNo"]) {
	rename(dirname(__FILE__)."/../data/".$settingsFile,dirname(__FILE__)."/../data/".str_replace(".php",".old",$settingsFile));
	header("Location: settings.php");
	echo "<script>window.location='settings.php';</script>";
	die('Found old settings file, reloading...');
}

// Join ICEcoder settings and user settings together to make our final ICEcoder array
$ICEcoder = $ICEcoderSettings + $ICEcoderUserSettings;

// Include language file
// Load base first as foundation
include(dirname(__FILE__)."/../lang/".basename($ICEcoder['languageBase']));
$baseText = $text;
// Load chosen language ontop to replace base
include(dirname(__FILE__)."/../lang/".basename($ICEcoder['languageUser']));
$text = array_replace_recursive($baseText, $text);
$_SESSION['text'] = $text;

// Login not required or we're in demo mode and have password set in our settings, log us straight in
if ((!$ICEcoder['loginRequired'] || $ICEcoder['demoMode']) && $ICEcoder['password']!="") {$_SESSION['loggedIn']=true;};
$demoMode = $ICEcoder['demoMode'];

// Update this config file?
include(dirname(__FILE__)."/settings-update.php");

// Set loggedIn and username to false if not set as yet
if (!isset($_SESSION['loggedIn'])) {$_SESSION['loggedIn'] = false;};
if (!isset($_SESSION['username'])) {$_SESSION['username'] = false;};

// Attempt a login with password
if(isset($_POST['submit']) && $setPWorLogin=="login") {
	// On success, set username if multiUser, loggedIn to true and redirect
	if (verifyHash(strClean($_POST['password']),$ICEcoder["password"])==$ICEcoder["password"]) {
		session_regenerate_id();
		if ($ICEcoder["multiUser"]) {
			$_SESSION['username'] = $_POST['username'];
		}
		$_SESSION['loggedIn'] = true;
		include(dirname(__FILE__)."/../processes/on-user-login.php");
		header('Location: ../');
		echo "<script>window.location='../';</script>";
		die('Logging you in...');
	} else {
		include(dirname(__FILE__)."/../processes/on-user-login-fail.php");
	}
};

// Re-establish our loggedIn state and username
$_SESSION['loggedIn'] = $_SESSION['loggedIn'];
$_SESSION['username'] = $_SESSION['username'];

// Define the serverType, docRoot & iceRoot
$serverType = stristr($_SERVER['SERVER_SOFTWARE'], "win") ? "Windows" : "Linux";
$docRoot = rtrim(str_replace("\\","/",$ICEcoder['docRoot']));
$iceRoot = rtrim(str_replace("\\","/",$ICEcoder["root"]));
if ($_SESSION['loggedIn'] && basename($_SERVER['SCRIPT_NAME']) == "index.php") {
	echo "<script>top.docRoot='".$docRoot."';top.iceRoot='".$iceRoot."'</script>";
}

// Establish the dir ICEcoders running from
$ICEcoderDirFullPath = rtrim(str_replace("\\","/",dirname($_SERVER['SCRIPT_FILENAME'])),"/lib");
$rootPrefix = '/'.str_replace("/","\/",preg_quote(str_replace("\\","/",$docRoot))).'/';
$ICEcoderDir = preg_replace($rootPrefix, '', $ICEcoderDirFullPath, 1);

// Setup our file security vars
$settingsArray = array("findFilesExclude","bannedFiles","allowedIPs");
for ($i=0;$i<count($settingsArray);$i++) {
	if (!isset($_SESSION[$settingsArray[$i]])) {$_SESSION[$settingsArray[$i]] = $ICEcoder[$settingsArray[$i]];}
}

// Check IP permissions
if (!in_array($_SERVER["REMOTE_ADDR"], $_SESSION['allowedIPs']) && !in_array("*", $_SESSION['allowedIPs'])) {
	header('Location: /');
	$reqsPassed = false;
	$reqsFailures = ["systemIPRestriction"];
	include(dirname(__FILE__)."/requirements.php");
	exit;
};

// Establish any FTP site to use
if (isset($_SESSION['ftpSiteRef']) && $_SESSION['ftpSiteRef'] !== false) {
	$ftpSiteArray = $ICEcoder['ftpSites'][$_SESSION['ftpSiteRef']];
	$ftpSite = $ftpSiteArray['site'];                                         // FTP site domain, eg http://yourdomain.com
	$ftpHost = $ftpSiteArray['host'];                                         // FTP host, eg ftp.yourdomain.com
	$ftpUser = $ftpSiteArray['user'];                                         // FTP username
	$ftpPass = $ftpSiteArray['pass'];                                         // FTP password
	$ftpPasv = $ftpSiteArray['pasv'];                                         // FTP account requires PASV mode?
	$ftpMode = $ftpSiteArray['mode'] == "FTP_ASCII" ? FTP_ASCII : FTP_BINARY; // FTP transfer mode, FTP_ASCII or FTP_BINARY
	$ftpRoot = $ftpSiteArray['root'];                                         // FTP root dir to use as base, eg /htdocs
}

// Save currently opened files in previousFiles and last10Files arrays
include(dirname(__FILE__)."/settings-save-current-files.php");

// Display the plugins
include(dirname(__FILE__)."/plugins-display.php");

// If loggedIn is false or we don't have a password set yet and we're not on login screen, boot user to that
if ((!$_SESSION['loggedIn'] || $ICEcoder["password"] == "") && !strpos($_SERVER['SCRIPT_NAME'],"lib/login.php")) {
	if (file_exists('lib/login.php')) {
		header('Location: lib/login.php');
		echo "<script>window.location='lib/login.php';</script>";
	} else {
		header('Location: login.php');
		echo "<script>window.location='login.php';</script>";
	}
	die('Redirecting to login...');

// If we are on the login screen and not logged in
} elseif (!$_SESSION['loggedIn']) {
	// If the password hasn't been set and we're setting it
	if ($ICEcoder["password"] == "" && isset($_POST['submit']) && (strpos($_POST['submit'],"set password")>-1)) {
		$password = generateHash(strClean($_POST['password']));
		$settingsContents = getData("../data/".$settingsFile);
		// Replace our empty password with the one submitted by user
		$settingsContents = str_replace('"password"		=> "",','"password"		=> "'.$password.'",',$settingsContents);
		// Also set the update checker preference
		$checkUpdates = $_POST['checkUpdates']=="true" ? "true" : "false";
		// once to cover the true setting, once to cover false
		$settingsContents = str_replace('"checkUpdates"		=> true,','"checkUpdates"		=> '.$checkUpdates.',',$settingsContents);
		$settingsContents = str_replace('"checkUpdates"		=> false,','"checkUpdates"		=> '.$checkUpdates.',',$settingsContents);
		// Now update the config file
		if (!$fh = fopen(dirname(__FILE__)."/../data/".$settingsFile, 'w')) {
			$reqsPassed = false;
			$reqsFailures = ["phpUpdateSettings"];
			include(dirname(__FILE__)."/requirements.php");
		}
		fwrite($fh, $settingsContents);
		fclose($fh);
		// Create a duplicate version for the IP address of the domain if it doesn't exist yet
		$serverAddr = $_SERVER['SERVER_ADDR'];
		if ($serverAddr == "1" || $serverAddr == "::1") {
			$serverAddr = "127.0.0.1";
		}
		$settingsFileAddr = 'config-'.$username.str_replace(".","_",$serverAddr).'.php';
		if (!file_exists(dirname(__FILE__)."/../data/".$settingsFileAddr)) {
			if (!copy(dirname(__FILE__)."/../data/".$settingsFile, dirname(__FILE__)."/../data/".$settingsFileAddr)) {
				$reqsPassed = false;
				$reqsFailures = ["phpCreateSettingsFileAddr"];
				include(dirname(__FILE__)."/requirements.php");
			}
		}
		// Disable the enableRegistration config setting if the user had that option chosen
		if (isset($_POST['disableFurtherRegistration'])) {
			$updatedConfigSettingsFile = getData(dirname(__FILE__)."/../data/".$configSettings);
			if ($fUConfigSettings = fopen(dirname(__FILE__)."/../data/".$configSettings, 'w')) {
				$updatedConfigSettingsFile = str_replace('"enableRegistration"	=> true','"enableRegistration"	=> false',$updatedConfigSettingsFile);
				fwrite($fUConfigSettings, $updatedConfigSettingsFile);
				fclose($fUConfigSettings);
			} else {
				$reqsPassed = false;
				$reqsFailures = ["phpUpdateConfig"];
				include(dirname(__FILE__)."/requirements.php");
			}
		}
		// Set the session user level
		if ($ICEcoder["multiUser"]) {
			$_SESSION['username']=$_POST['username'];
		}
		$_SESSION['loggedIn'] = true;
		include(dirname(__FILE__)."/../processes/on-user-new.php");
		// Finally, load again as now this file has changed and auto login
		header('Location: ../');
		echo "<script>window.location='../';</script>";
		die('Logging you in...');
	}
	// ===================================================
	// We're likely showing the login screen at this point
	// ===================================================
} elseif ($ICEcoder['loginRequired'] && $_SESSION['loggedIn'] && $ICEcoder["password"]=="") {
	header("Location: ../?logout");
	echo "<script>window.location='../?logout';</script>";
	die('Logging you out...');
} else {
	// ==================================
	// Continue with whatever we're doing
	// ==================================
}
