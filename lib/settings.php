<?php
// Don't display, but log all errors
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__).'/../error-log.txt');
error_reporting(-1);

// Set our default timezone and supress warning with @
@date_default_timezone_set(date_default_timezone_get());

// Set a stream context timeout for file reading
$context = stream_context_create(array('http'=>
	array(
		'timeout' => 60 // secs
	)
));

// Start a session if we haven't already
if(!isset($_SESSION)) {@session_start();}

// Logout if that's the action we're taking
if (isset($_GET['logout'])) {
	include(dirname(__FILE__)."/../processes/on-user-logout.php");
	$_SESSION['loggedIn']=false;
	$_SESSION['username']=false;
	session_destroy();
	header("Location: dirname(__FILE__)./?loggedOut");
}

// If magic quotes are still on (attempted to switch off in php.ini)
if (get_magic_quotes_gpc ()) {
	function stripslashes_deep($value) {
		$value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
		return $value;
	}
	$_POST = (isset($_POST) && !empty($_POST)) ? array_map('stripslashes_deep', $_POST) : array();
	$_GET = (isset($_GET) && !empty($_GET)) ? array_map('stripslashes_deep', $_GET) : array();
	$_COOKIE = (isset($_COOKIE) && !empty($_COOKIE)) ? array_map('stripslashes_deep', $_COOKIE) : array();
	$_REQUEST = (isset($_REQUEST) && !empty($_REQUEST)) ? array_map('stripslashes_deep', $_REQUEST) : array();
}

// Function to handle salted hashing
define('SALT_LENGTH',12);
function generateHash($plainText,$salt=null) {
	if ($salt === null) {
		$salt = substr(md5(uniqid(rand(), true)),0,SALT_LENGTH);
	} else {
		$salt = substr($salt,0,SALT_LENGTH);
	}
	return $salt.sha1($salt.$plainText);
}

// returns converted entities which have HTML entity equivalents
function strClean($var) {
	return htmlentities($var, ENT_QUOTES, "UTF-8");
}

// returns a number, whole or decimal or null
function numClean($var) {
	return is_numeric($var) ? floatval($var) : false;
}

// returns a UTF8 based string with any UFT8 BOM removed
function toUTF8noBOM($string,$message) {
	// Attempt to detect encoding
	if (function_exists('mb_detect_encoding')) {
		$encType = mb_detect_encoding($string);
		// Get rid of any UTF-8 BOM
		$string = preg_replace('/\x{EF}\x{BB}\x{BF}/','',$string);
		// Test for any bad characters
		$teststring = $string;
		$teststringBroken = utf8_decode($teststring);
		$teststringConverted = iconv("UTF-8", "UTF-8//IGNORE", $teststringBroken);
		// If we have a matching length, UTF8 encode it
		if ($encType != "ASCII" && $encType != "UTF-8" && strlen($teststringConverted) == strlen($teststringBroken)) {
			$string = utf8_encode($string);
			if ($message) {
				echo "top.ICEcoder.message('Your document doesn\'t appear to be in UTF-8 encoding so has been converted.');";
			}
		}
	}
	return $string;
}

// Load system settings
$configSettings = 'config___settings.php';
include(dirname(__FILE__)."/".$configSettings);

// Settings are stored in this file
$configUsersTemplate = 'config___users-template.php';
$username = "";
if (isset($_POST['username']) && $_POST['username'] != "") {$username = strClean($_POST['username']."-");};
if (isset($_SESSION['username']) && $_SESSION['username'] != "") {$username = strClean($_SESSION['username']."-");};
$settingsFile = 'config-'.$username.str_replace(".","_",str_replace("www.","",$_SERVER['SERVER_NAME'])).'.php';
$setPWorLogin = "login";
if (!file_exists(dirname(__FILE__)."/".$settingsFile)) {
	if (!copy(dirname(__FILE__)."/".$configUsersTemplate, dirname(__FILE__)."/".$settingsFile)) {
		die("Couldn't create $settingsFile. Maybe you need write permissions on the lib folder?");
	}
	$setPWorLogin = "set password";
}
include(dirname(__FILE__)."/".$settingsFile);

// On mismatch of settings file to system, rename to .old and reload
If ($ICEcoderUserSettings["versionNo"] != $ICEcoderSettings["versionNo"]) {
	rename(dirname(__FILE__)."/".$settingsFile,dirname(__FILE__)."/".str_replace(".php",".old",$settingsFile));
	header("Location: settings.php");
}

// Join ICEcoder settings and user settings together to make our final ICEcoder array
$ICEcoder = $ICEcoderSettings + $ICEcoderUserSettings;

$onLoadExtras = "";
$pluginsDisplay = "";

if ($ICEcoder['demoMode'] && $ICEcoder['password']!="") {$_SESSION['loggedIn']=true;};
$demoMode = $ICEcoder['demoMode'];

// Update this config file?
if (!$demoMode && isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] && isset($_POST["theme"]) && $_POST["theme"]) {
	$settingsContents = file_get_contents($settingsFile,false,$context);
	// Replace our settings vars
	$repPosStart = strpos($settingsContents,'"root"');
	$repPosEnd = strpos($settingsContents,'"previousFiles"');

	// Prepare all our vars
	$ICEcoder["root"]			= strClean($_POST['root']);
	$ICEcoder["checkUpdates"]		= isset($_POST['checkUpdates']) && $_POST['checkUpdates'] ? "true" : "false";
	$ICEcoder["openLastFiles"]		= isset($_POST['openLastFiles']) && $_POST['openLastFiles'] ? "true" : "false";
	$ICEcoder["findFilesExclude"]		= 'array("'.str_replace(',','","',str_replace(" ","",strClean($_POST['findFilesExclude']))).'")';
	$ICEcoder["codeAssist"]			= isset($_POST['codeAssist']) && $_POST['codeAssist'] ? "true" : "false";
	$ICEcoder["visibleTabs"]		= isset($_POST['visibleTabs']) && $_POST['visibleTabs'] ? "true" : "false";
	$ICEcoder["lockedNav"]			= isset($_POST['lockedNav']) && $_POST['lockedNav'] ? "true" : "false";
	if ($_POST['password']!="")		{$ICEcoder["password"] = generateHash(strClean($_POST['password']));};
	$ICEcoder["bannedFiles"]		= 'array("'.str_replace(',','","',str_replace(" ","",strClean($_POST['bannedFiles']))).'")';
	$ICEcoder["bannedPaths"]		= 'array("'.str_replace(',','","',str_replace(" ","",strClean($_POST['bannedPaths']))).'")';
	$ICEcoder["allowedIPs"]			= 'array("'.str_replace(',','","',str_replace(" ","",strClean($_POST['allowedIPs']))).'")';
	$ICEcoder["plugins"]			= 'array('.PHP_EOL.'	array('.PHP_EOL.'	'.str_replace('====================','),'.PHP_EOL.'	array(',str_replace("\\","\\\\",$_POST['plugins'])).'))';
	$ICEcoder["theme"]			= strClean($_POST['theme']);
	$ICEcoder["fontSize"]			= strClean($_POST['fontSize']);
	$ICEcoder["lineWrapping"]		= strClean($_POST['lineWrapping']);
	$ICEcoder["indentWithTabs"]		= strClean($_POST['indentWithTabs']);
	$ICEcoder["indentSize"]			= numClean($_POST['indentSize']);
	$ICEcoder["tagWrapperCommand"]	= strClean($_POST['tagWrapperCommand']);

	$settingsArray = array("root","checkUpdates","openLastFiles","findFilesExclude","codeAssist","visibleTabs","lockedNav","password","bannedFiles","bannedPaths","allowedIPs","plugins","theme","fontSize","lineWrapping","indentWithTabs","indentSize","tagWrapperCommand");
	$settingsNew = "";
	for ($i=0;$i<count($settingsArray);$i++) {
		$settingsNew .= '"'.$settingsArray[$i].'"'.PHP_EOL.'	=> ';
		$settingWrap = $settingsArray[$i]=="root"||$settingsArray[$i]=="password"||$settingsArray[$i]=="theme"||$settingsArray[$i]=="fontSize"||$settingsArray[$i]=="tagWrapperCommand" ? '"' : '';
		$settingsNew .= $settingWrap.$ICEcoder[$settingsArray[$i]].$settingWrap.','.PHP_EOL.PHP_EOL;
	}

	// Compile our new settings
	$settingsContents = substr($settingsContents,0,$repPosStart).$settingsNew.substr($settingsContents,($repPosEnd),strlen($settingsContents));
	// Now update the config file
	if (is_writeable($settingsFile)) {
		$fh = fopen($settingsFile, 'w');
		fwrite($fh, $settingsContents);
		fclose($fh);
	} else {
		echo "<script>top.ICEcoder.message('Cannot update config file. Please set public write permissions on lib/".$settingsFile." and try again');</script>";
	}

	// OK, now the config file has been updated, update our current session with new arrays
	$settingsArray = array("findFilesExclude","bannedFiles","allowedIPs");
	for ($i=0;$i<count($settingsArray);$i++) {
		$_SESSION[$settingsArray[$i]] = $ICEcoder[$settingsArray[$i]] = explode(",",str_replace(" ","",strClean($_POST[$settingsArray[$i]])));
	}
	// Work out the theme to use now
	$ICEcoder["theme"]=="default" ? $themeURL = 'lib/editor.css' : $themeURL = $ICEcoder["codeMirrorDir"].'/theme/'.$ICEcoder["theme"].'.css';
	// Do we need a file manager refresh?
	$refreshFM = $_POST['changedFileSettings']=="true" ? "true" : "false";
	// With all that worked out, we can now hide the settings screen and apply the new settings
	echo "<script>top.ICEcoder.settingsScreen('hide');top.ICEcoder.useNewSettings('".$themeURL."',".$ICEcoder["codeAssist"].",".$ICEcoder["lockedNav"].",".$ICEcoder["visibleTabs"].",'".$ICEcoder["fontSize"]."',".$ICEcoder["lineWrapping"].",".$ICEcoder["indentWithTabs"].",".$ICEcoder["indentSize"].",'".$ICEcoder["tagWrapperCommand"]."',".$refreshFM.");</script>";
}

// Establish our user level
if (!isset($_SESSION['loggedIn'])) {$_SESSION['loggedIn'] = false;};
if (!isset($_SESSION['username'])) {$_SESSION['username'] = false;};
if(isset($_POST['submit']) && $setPWorLogin=="login") {
	if (generateHash(strClean($_POST['password']),$ICEcoder["password"])==$ICEcoder["password"]) {
		if ($ICEcoder["multiUser"]) {
			$_SESSION['username'] = $_POST['username'];
		}
		$_SESSION['loggedIn'] = true;
		include(dirname(__FILE__)."/../processes/on-user-login.php");
		header('Location: ../');
	} else {
		include(dirname(__FILE__)."/../processes/on-user-login-fail.php");
	}
};
$_SESSION['loggedIn'] = $_SESSION['loggedIn'];
$_SESSION['username'] = $_SESSION['username'];

// Define the serverType, docRoot & iceRoot
$serverType = stristr($_SERVER['SERVER_SOFTWARE'], "win") ? "Windows" : "Linux";
$docRoot = rtrim(str_replace("\\","/",$ICEcoder['docRoot']));
$iceRoot = rtrim(str_replace("\\","/",$ICEcoder["root"]));
if ($_SESSION['loggedIn']) {
	echo "<script>top.docRoot='".$docRoot."';top.iceRoot='".$iceRoot."'</script>";
}

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
// If user not allowed to view, boot to site root
if (!$allowedIP) {
	echo '<script>top.window.location="/";</script>';
};

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
			echo "<script>top.ICEcoder.message('Cannot update config file. Please set public write permissions on lib/".$settingsFile." and try again');</script>";
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
					echo "<script>top.ICEcoder.message('Cannot update config file. Please set public write permissions on lib/".$settingsFile." and try again');</script>";
				}
			}
		}
	}
	echo '<script>top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);</script>';
}

// If we're updating, do/redo plugins
if ($_SESSION['loggedIn']) {
	// If we're updating, we need to recreate the plugins array
	if (isset($_POST["theme"]) && $_POST["theme"]) {
		$ICEcoder["plugins"] = array();
		$pluginsArray = explode("====================",str_replace("\"","",str_replace("\r","",str_replace("\n","",$_POST['plugins']))));
		for ($i=0;$i<count($pluginsArray);$i++) {
			array_push($ICEcoder["plugins"], explode(",",$pluginsArray[$i]));
		}
	}

	// Work out the plugins to display to the user
	$pluginsDisplay = "";
	for ($i=0;$i<count($ICEcoder["plugins"]);$i++) {
		$target = explode(":",$ICEcoder["plugins"][$i][4]);
		$pluginsDisplay .= '<a href="'.$ICEcoder["plugins"][$i][3].'" title="'.$ICEcoder["plugins"][$i][0].'" target="'.$target[0].'"><img src="'.$ICEcoder["plugins"][$i][1].'" style="'.$ICEcoder["plugins"][$i][2].'" alt="'.$ICEcoder["plugins"][$i][0].'"></a>';
	};

	// If we're updating, replace the plugin display with our newly established one
	echo "<script>if(top.document.getElementById('pluginsContainer')) {top.document.getElementById('pluginsContainer').innerHTML = '".$pluginsDisplay."'};</script>";

	// Work out what plugins we'll need to set on a setInterval
	$onLoadExtras = "";
	for ($i=0;$i<count($ICEcoder["plugins"]);$i++) {
		if ($ICEcoder["plugins"][$i][5]!="") {
			$onLoadExtras .= ";top.ICEcoder.startPluginIntervals(".$i.",'".$ICEcoder["plugins"][$i][3]."','".$ICEcoder["plugins"][$i][4]."','".$ICEcoder["plugins"][$i][5]."')";
		};
	};

	// If we're updating our settings, clear existing setIntervals & the array refs, then start new ones
	if (isset($_POST["theme"]) && $_POST["theme"]) {
		?>
		<script>
		for (i=0;i<=top.ICEcoder.pluginIntervalRefs.length-1;i++) {
			clearInterval(top.ICEcoder['plugTimer'+top.ICEcoder.pluginIntervalRefs[i]]);
		}
		top.ICEcoder.pluginIntervalRefs = [];
		<?php echo $onLoadExtras.PHP_EOL; ?>
		</script>
		<?php
	}

	// Finally, show server data
	$onLoadExtras .= ";top.ICEcoder.content.style.visibility='visible'";
}

if ((!$_SESSION['loggedIn'] || $ICEcoder["password"] == "") && !strpos($_SERVER['SCRIPT_NAME'],"lib/settings.php")) {
	header('Location: lib/settings.php');
// If we're due to show the settings screen
} elseif (!$_SESSION['loggedIn']) {
	// If the password hasn't been set and we're setting it
	if ($ICEcoder["password"] == "" && isset($_POST['submit']) && (strpos($_POST['submit'],"set password")>-1)) {
		$password = generateHash(strClean($_POST['password']));
		$settingsFile = $settingsFile;
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
	}
?>
<!DOCTYPE html>

<html>
<head>
<title>ICEcoder <?php
echo $ICEcoder["versionNo"]." : ";
echo $ICEcoder["password"] == "" && !$ICEcoder["multiUser"] ? "Setup" : "Login";
?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" type="text/css" href="ice-coder.css">
<link rel="icon" type="image/png" href="../favicon.png">
</head>

<body onLoad="document.settingsUpdate.<?php echo $ICEcoder["multiUser"] ? "username" : "password";?>.focus()">
	
<div class="screenContainer" style="background-color: #141414">
	<div class="screenVCenter">
		<div class="screenCenter">
		<img src="../images/ice-coder.png" style="margin-right: 22px">
		<div class="version">v <?php echo $ICEcoder["versionNo"];?></div>
		<form name="settingsUpdate" action="settings.php" method="POST">
		<?php if ($ICEcoder["multiUser"]) {echo '<input type="text" name="username" class="password"><br><br>';};?>
		<input type="password" name="password" class="password"><br><br>
		<input type="submit" name="submit" value="<?php if ($ICEcoder["multiUser"]) {echo "set password / login";} else {echo $ICEcoder["password"] == "" ? "set password" : "login";}; ?>" class="button">
		<?php
		if ($ICEcoder["password"] == "" || $ICEcoder["multiUser"]) {
			echo '<div class="text"><input type="checkbox" name="checkUpdates" value="true" checked> auto-check for updates</div>';
		}
		if (!$ICEcoder["multiUser"]) { echo '<div class="text"><a href="javascript:alert(\'To put into multi-user mode, open lib/config___settings.php and change multiUser to true then reload this page\')">multi-user?</a></div>';};
		?>
		</form>
		</div>
	</div>
</div>

</body>

</html>
<?php
} elseif ($_SESSION['loggedIn'] && $ICEcoder["password"]=="") {
	header("Location: ../?logout");
}
?>