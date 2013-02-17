<?php
// Display & log all errors
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__).'/../error-log.txt');
error_reporting(-1);

// Set our default timezone and supress warning with @
@date_default_timezone_set(date_default_timezone_get());

// Start a session if we haven't already
if(!isset($_SESSION)) {session_start();}

// Logout if that's the action we're taking
if (isset($_GET['logout'])) {
	$_SESSION['loggedIn']=false;
	session_destroy();
	header("Location: dirname(__FILE__)./?loggedOut");
}

// Function to handle salted hashing
define('SALT_LENGTH',9);
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

// Settings are stored in this file
$settingsTemplate = 'config-template.php';
$settingsFile = 'config.php';
if (!file_exists(dirname(__FILE__)."/".$settingsFile)) {
	if (!copy(dirname(__FILE__)."/".$settingsTemplate, dirname(__FILE__)."/".$settingsFile)) {
		die("Couldn't create $settingsFile. Maybe you need write permissions on the lib folder?");
	}
}
include($settingsFile);

// Add ICEcoder settings to beginning of $ICEcoder array
$ICEcoder = array(
	"versionNo"		=> "1.6",
	"codeMirrorDir"		=> "CodeMirror-3.02",
	"demoMode"		=> false
)+$ICEcoder;

$onLoadExtras = "";
$pluginsDisplay = "";

if ($ICEcoder['demoMode'] && $ICEcoder['accountPassword']!="") {$_SESSION['loggedIn']=true;};
$demoMode = $ICEcoder['demoMode'];

// Update this config file?
if (!$demoMode && isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] && isset($_POST["theme"]) && $_POST["theme"]) {
	$settingsContents = file_get_contents($settingsFile);
	// Replace our settings vars
	$repPosStart = strpos($settingsContents,'"root"');
	$repPosEnd = strpos($settingsContents,'"previousFiles"');

	// Prepare all our vars
	$ICEcoder["root"]			= strClean($_POST['root']);
	$ICEcoder["tabsIndent"]			= isset($_POST['tabsIndent']) && $_POST['tabsIndent'] ? "true" : "false";
	$ICEcoder["checkUpdates"]		= isset($_POST['checkUpdates']) && $_POST['checkUpdates'] ? "true" : "false";
	$ICEcoder["openLastFiles"]		= isset($_POST['openLastFiles']) && $_POST['openLastFiles'] ? "true" : "false";
	$ICEcoder["findFilesExclude"]		= 'array("'.str_replace(',','","',str_replace(" ","",strClean($_POST['findFilesExclude']))).'")';
	$ICEcoder["codeAssist"]			= isset($_POST['codeAssist']) && $_POST['codeAssist'] ? "true" : "false";
	$ICEcoder["visibleTabs"]		= isset($_POST['visibleTabs']) && $_POST['visibleTabs'] ? "true" : "false";
	$ICEcoder["lockedNav"]			= isset($_POST['lockedNav']) && $_POST['lockedNav'] ? "true" : "false";
	if ($_POST['accountPassword']!="")	{$ICEcoder["accountPassword"] = generateHash(strClean($_POST['accountPassword']));};
	$ICEcoder["bannedFiles"]		= 'array("'.str_replace(',','","',str_replace(" ","",strClean($_POST['bannedFiles']))).'")';
	$ICEcoder["bannedPaths"]		= 'array("'.str_replace(',','","',str_replace(" ","",strClean($_POST['bannedPaths']))).'")';
	$ICEcoder["allowedIPs"]			= 'array("'.str_replace(',','","',str_replace(" ","",strClean($_POST['allowedIPs']))).'")';
	$ICEcoder["plugins"]			= 'array('.PHP_EOL.'	array('.PHP_EOL.'	'.str_replace('====================','),'.PHP_EOL.'	array(',$_POST['plugins']).'))';
	$ICEcoder["theme"]			= strClean($_POST['theme']);
	$ICEcoder["lineWrapping"]		= strClean($_POST['lineWrapping']);
	$ICEcoder["tabWidth"]			= numClean($_POST['tabWidth']);

	$settingsArray = array("root","tabsIndent","checkUpdates","openLastFiles","findFilesExclude","codeAssist","visibleTabs","lockedNav","accountPassword","bannedFiles","bannedPaths","allowedIPs","plugins","theme","lineWrapping","tabWidth");
	$settingsNew = "";
	for ($i=0;$i<count($settingsArray);$i++) {
		$settingsNew .= '"'.$settingsArray[$i].'"'.PHP_EOL.'	=> ';
		$settingWrap = $settingsArray[$i]=="root"||$settingsArray[$i]=="accountPassword"||$settingsArray[$i]=="theme" ? '"' : '';
		$settingsNew .= $settingWrap.$ICEcoder[$settingsArray[$i]].$settingWrap.','.PHP_EOL.PHP_EOL;
	}

	// Compile our new settings
	$settingsContents = substr($settingsContents,0,$repPosStart).$settingsNew.substr($settingsContents,($repPosEnd),strlen($settingsContents));
	// Now update the config file
	$fh = fopen($settingsFile, 'w') or die("Can't update config file. Please set public write permissions on lib/".$settingsFile." and press refresh");
	fwrite($fh, $settingsContents);
	fclose($fh);

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
	echo "<script>top.ICEcoder.settingsScreen('hide');top.ICEcoder.useNewSettings('".$themeURL."',".$ICEcoder["tabsIndent"].",".$ICEcoder["codeAssist"].",".$ICEcoder["lockedNav"].",".$ICEcoder["visibleTabs"].",".$ICEcoder["lineWrapping"].",".$ICEcoder["tabWidth"].",".$refreshFM.");</script>";
}

// Establish our user level
if (!isset($_SESSION['loggedIn'])) {$_SESSION['loggedIn'] = false;};
if(isset($_POST['loginPassword']) && generateHash(strClean($_POST['loginPassword']),$ICEcoder["accountPassword"])==$ICEcoder["accountPassword"]) {$_SESSION['loggedIn'] = true; header('Location: ../');};
$_SESSION['loggedIn'] = $_SESSION['loggedIn'];

// Define the serverType, docRoot & iceRoot
$serverType = stristr($_SERVER['SERVER_SOFTWARE'], "win") ? "Windows" : "Linux";
$docRoot = rtrim(str_replace("\\","/",$_SERVER['DOCUMENT_ROOT']));
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
	$settingsContents = file_get_contents($settingsFile);

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
		$fh = fopen($settingsFile, 'w') or die("Can't update config file. Please set public write permissions on lib/".$settingsFile." and press refresh");
		fwrite($fh, $settingsContents);

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
				$fh = fopen($settingsFile, 'w') or die("Can't update config file. Please set public write permissions on lib/".$settingsFile." and press refresh");
				fwrite($fh, $settingsContents);
			}
		}
		fclose($fh);
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

if ((!$_SESSION['loggedIn'] || $ICEcoder["accountPassword"] == "") && !strpos($_SERVER['SCRIPT_NAME'],"lib/settings.php")) {
	header('Location: lib/settings.php');
// If we're due to show the settings screen
} elseif (!$_SESSION['loggedIn']) {
	// If the password hasn't been set and we're setting it
	if ($ICEcoder["accountPassword"] == "" && isset($_POST['accountPassword'])) {
		$password = generateHash(strClean($_POST['accountPassword']));
		$settingsFile = $settingsFile;
		$settingsContents = file_get_contents($settingsFile);
		// Replace our empty password with the one submitted by user
		$settingsContents = str_replace('"accountPassword"	=> "",','"accountPassword"	=> "'.$password.'",',$settingsContents);
		// Now update the config file
		$fh = fopen($settingsFile, 'w') or die("Can't update config file. Please set public write permissions on ".$settingsFile." and press refresh");
		fwrite($fh, $settingsContents);
		fclose($fh);
		// Set the session user level
		$_SESSION['loggedIn'] = true;
		// Finally, load again as now this file has changed and auto login
		header('Location: ../');
	}
?>
<!DOCTYPE html>

<html>
<head>
<title>ICEcoder <?php
echo $ICEcoder["versionNo"]." : ";
echo $ICEcoder["accountPassword"] == "" ? "Setup" : "Login";
?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" type="text/css" href="ice-coder.css">
<link rel="icon" type="image/png" href="../favicon.png">
</head>

<body onLoad="document.settingsUpdate.<?php echo $ICEcoder["accountPassword"] == "" ? "account" : "login"; ?>Password.focus()">
	
<div class="screenContainer" style="background-color: #141414">
	<div class="screenVCenter">
		<div class="screenCenter">
		<img src="../images/ice-coder.png">
		<div class="version">v <?php echo $ICEcoder["versionNo"];?></div>
		<form name="settingsUpdate" action="settings.php" method="POST">
		<input type="password" name="<?php echo $ICEcoder["accountPassword"] == "" ? "account" : "login"; ?>Password" class="accountPassword"><br><br>
		<input type="submit" name="submit" value="<?php echo $ICEcoder["accountPassword"] == "" ? "set password" : "login"; ?>" class="button">
		</form>
		</div>
	</div>
</div>

</body>

</html>
<?php
}
?>