<?php
// Establish settings and users template filenames
$configSettings = 'config___settings.php';
$configUsersTemplate = 'config___users-template.php';

// Load config settings
include(dirname(__FILE__)."/".$configSettings);

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
if (!file_exists(dirname(__FILE__)."/".$settingsFile) && $ICEcoderSettings['enableRegistration']) {
	if (!copy(dirname(__FILE__)."/".$configUsersTemplate, dirname(__FILE__)."/".$settingsFile)) {
		die("Couldn't create $settingsFile. Maybe you need write permissions on the lib folder?");
	}
	$setPWorLogin = "set password";
}

// Load user settings
include(dirname(__FILE__)."/".$settingsFile);

// On mismatch of settings file to system, rename to .old and reload
If ($ICEcoderUserSettings["versionNo"] != $ICEcoderSettings["versionNo"]) {
	rename(dirname(__FILE__)."/".$settingsFile,dirname(__FILE__)."/".str_replace(".php",".old",$settingsFile));
	header("Location: settings.php");
	die('Found old settings file, reloading...');
}

// Join ICEcoder settings and user settings together to make our final ICEcoder array
$ICEcoder = $ICEcoderSettings + $ICEcoderUserSettings;

// Include language file
// Load English first as foundation
include(dirname(__FILE__)."/../lang/english.php");
$englishText = $text;
// Load chosen language ontop to replace English
include(dirname(__FILE__)."/../lang/english.php");
$text = array_replace_recursive ($englishText, $text);
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
	if (generateHash(strClean($_POST['password']),$ICEcoder["password"])==$ICEcoder["password"]) {
		if ($ICEcoder["multiUser"]) {
			$_SESSION['username'] = $_POST['username'];
		}
		$_SESSION['loggedIn'] = true;
		include(dirname(__FILE__)."/../processes/on-user-login.php");
		header('Location: ../');
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
if ($_SESSION['loggedIn']) {
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

// Determin our allowed IP addresses
$allowedIP = false;
for($i=0;$i<count($_SESSION['allowedIPs']);$i++) {
	if ($_SESSION['allowedIPs'][$i]==$_SERVER["REMOTE_ADDR"]||$_SESSION['allowedIPs'][$i]=="*") {
		$allowedIP = true;
	}
}
// If user not allowed to view, display message
if (!$allowedIP) {
	die('Sorry, access not permitted');
};

// Save currently opened files in previousFiles and last10Files arrays
include(dirname(__FILE__)."/settings-save-current-files.php");

// Display the plugins
include(dirname(__FILE__)."/plugins-display.php");

// If loggedIn is false or we don't have a password set yet and we're not on login screen, boot user to that
if ((!$_SESSION['loggedIn'] || $ICEcoder["password"] == "") && !strpos($_SERVER['SCRIPT_NAME'],"lib/login.php")) {
	if (file_exists('lib/login.php')) {
		header('Location: lib/login.php');
	} else {
		header('Location: login.php');
	}
	die('Redirecting to login...');
// If we are on the login screen and not logged in
} elseif (!$_SESSION['loggedIn']) {
	// If the password hasn't been set and we're setting it
	if ($ICEcoder["password"] == "" && isset($_POST['submit']) && (strpos($_POST['submit'],"set password")>-1)) {
		$password = generateHash(strClean($_POST['password']));
		$settingsContents = file_get_contents($settingsFile,false,$context);
		// Replace our empty password with the one submitted by user
		$settingsContents = str_replace('"password"		=> "",','"password"		=> "'.$password.'",',$settingsContents);
		// Also set the update checker preference
		$checkUpdates = $_POST['checkUpdates']=="true" ? "true" : "false";
		// once to cover the true setting, once to cover false
		$settingsContents = str_replace('"checkUpdates"		=> true,','"checkUpdates"		=> '.$checkUpdates.',',$settingsContents);
		$settingsContents = str_replace('"checkUpdates"		=> false,','"checkUpdates"		=> '.$checkUpdates.',',$settingsContents);
		// Now update the config file
		$fh = fopen($settingsFile, 'w') or die("Can't update config file. Please set public write permissions on ".$settingsFile." and press refresh");
		fwrite($fh, $settingsContents);
		fclose($fh);
		// Set the session user level
		if ($ICEcoder["multiUser"]) {
			$_SESSION['username']=$_POST['username'];
		}
		$_SESSION['loggedIn'] = true;
		include(dirname(__FILE__)."/../processes/on-user-new.php");
		// Finally, load again as now this file has changed and auto login
		header('Location: ../');
		die('Logging you in...');
	}
	// ===================================================
	// We're likely showing the login screen at this point
	// ===================================================
} elseif ($ICEcoder['loginRequired'] && $_SESSION['loggedIn'] && $ICEcoder["password"]=="") {
	header("Location: ../?logout");
	die('Logging you out...');
} else {
	// ==================================
	// Continue with whatever we're doing
	// ==================================
}
?>