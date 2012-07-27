<?php
// Start a session if we haven't already
if(!isset($_SESSION)) {session_start();}

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
$settingsFile = 'config.php';
include($settingsFile);

// Add ICEcoder settings to beginning of $ICEcoder array
$ICEcoder = array(
	"versionNo"		=> "v 0.7.9",
	"codeMirrorDir"		=> "CodeMirror-2.32",
	"cMThisVer"		=> 2.32
)+$ICEcoder;

// Update this config file?
if (isset($_POST["theme"]) && $_POST["theme"] && $_SESSION['userLevel'] == 10) {
	$settingsContents = file_get_contents($settingsFile);
	// Replace our settings vars
	$repPosStart = strpos($settingsContents,'"root"');
	$repPosEnd = strpos($settingsContents,'"previousFiles"');

	// Prepare all our vars
	$ICEcoder["root"]			= strClean($_POST['root']);
	$ICEcoder["tabsIndent"]			= $_POST['tabsIndent'] ? "true" : "false";
	$ICEcoder["checkUpdates"]		= $_POST['checkUpdates'] ? "true" : "false";
	$ICEcoder["openLastFiles"]		= $_POST['openLastFiles'] ? "true" : "false";
	$ICEcoder["findFilesExclude"]		= 'array("'.str_replace(', ','","',strClean($_POST['findFilesExclude'])).'")';
	$ICEcoder["codeAssist"]			= $_POST['codeAssist'] ? "true" : "false";
	$ICEcoder["visibleTabs"]		= $_POST['visibleTabs'] ? "true" : "false";
	$ICEcoder["lockedNav"]			= $_POST['lockedNav'] ? "true" : "false";
	if ($_POST['accountPassword']!="")	{$ICEcoder["accountPassword"] = generateHash(strClean($_POST['accountPassword']));};
	$ICEcoder["restrictedFiles"]		= 'array("'.str_replace(', ','","',strClean($_POST['restrictedFiles'])).'")';
	$ICEcoder["bannedFiles"]		= 'array("'.str_replace(', ','","',strClean($_POST['bannedFiles'])).'")';
	$ICEcoder["allowedIPs"]			= 'array("'.str_replace(', ','","',strClean($_POST['allowedIPs'])).'")';
	$ICEcoder["plugins"]			= 'array('.PHP_EOL.'	array('.PHP_EOL.'	'.str_replace('====================','),'.PHP_EOL.'	array(',$_POST['plugins']).'))';
	$ICEcoder["theme"]			= strClean($_POST['theme']);
	$ICEcoder["tabWidth"]			= numClean($_POST['tabWidth']);

	$settingsArray = array("root","tabsIndent","checkUpdates","openLastFiles","findFilesExclude","codeAssist","visibleTabs","lockedNav","accountPassword","restrictedFiles","bannedFiles","allowedIPs","plugins","theme","tabWidth");
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
	$settingsArray = array("findFilesExclude","restrictedFiles","bannedFiles","allowedIPs");
	for ($i=0;$i<count($settingsArray);$i++) {
		$_SESSION[$settingsArray[$i]] = $ICEcoder[$settingsArray[$i]] = explode(", ",strClean($_POST[$settingsArray[$i]]));
	}
	// Work out the theme to use now
	$ICEcoder["theme"]=="default" ? $themeURL = 'lib/editor.css' : $themeURL = $ICEcoder["codeMirrorDir"].'/theme/'.$ICEcoder["theme"].'.css';
	// Do we need a file manager refresh?
	$refreshFM = $_POST['changedFileSettings']=="true" ? "true" : "false";
	// With all that worked out, we can now hide the settings screen and apply the new settings
	echo "<script>top.ICEcoder.settingsScreen('hide');top.ICEcoder.useNewSettings('".$themeURL."',".$ICEcoder["tabsIndent"].",".$ICEcoder["codeAssist"].",".$ICEcoder["lockedNav"].",".$ICEcoder["visibleTabs"].",".$ICEcoder["tabWidth"].",".$refreshFM.");</script>";
}

// Define the docRoot & iceRoot
$docRoot = rtrim(str_replace("\\","/",$_SERVER['DOCUMENT_ROOT']));
$iceRoot = rtrim(str_replace("\\","/",$ICEcoder["root"]));

// Establish our user level
if (!isset($_SESSION['userLevel'])) {$_SESSION['userLevel'] = 0;};
if(isset($_POST['loginPassword']) && generateHash(strClean($_POST['loginPassword']),$ICEcoder["accountPassword"])==$ICEcoder["accountPassword"]) {$_SESSION['userLevel'] = 10;};
$_SESSION['userLevel'] = $_SESSION['userLevel'];

// Setup our file security vars
$settingsArray = array("findFilesExclude","restrictedFiles","bannedFiles","allowedIPs");
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
if (isset($_GET["saveFiles"]) && $_GET['saveFiles']) {
	if ($_SESSION['userLevel'] == 10) {
		$settingsContents = file_get_contents($settingsFile);

		// Replace our previousFiles var with the the current
		$repPosStart = strpos($settingsContents,'previousFiles"		=> "')+20;
		$repPosEnd = strpos($settingsContents,'",',$repPosStart)-$repPosStart;
		if ($_GET['saveFiles']!="CLEAR") {
			$saveFiles=strClean($_GET['saveFiles']);
			$saveFilesArray = explode(",",$saveFiles);
			$saveFiles="";
			for ($i=0;$i<count($saveFilesArray);$i++) {
				$saveFilesArray[$i] = str_replace("/","|",$docRoot).$saveFilesArray[$i];
				$saveFiles .= $saveFilesArray[$i].",";
			}
			$saveFiles = rtrim($saveFiles,",");
			$settingsContents = substr($settingsContents,0,$repPosStart).$saveFiles.substr($settingsContents,($repPosStart+$repPosEnd),strlen($settingsContents));
			// Now update the config file
			$fh = fopen($settingsFile, 'w') or die("Can't update config file. Please set public write permissions on lib/".$settingsFile);
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
					$fh = fopen($settingsFile, 'w') or die("Can't update config file. Please set public write permissions on lib/".$settingsFile);
					fwrite($fh, $settingsContents);
				}
			}
		}
		fclose($fh);
	}
	echo '<script>top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);</script>';
}

// If we're updating or calling from the index.php page, do/redo plugins
if ((isset($_POST["theme"]) && $_POST["theme"] && $_SESSION['userLevel'] == 10) || strpos($_SERVER['PHP_SELF'],"index.php")>0) {
	// If we're updating, we need to recreate the plugins array
	if (isset($_POST["theme"]) && $_POST["theme"] && $_SESSION['userLevel'] == 10) {
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
	if (isset($_POST["theme"]) && $_POST["theme"] && $_SESSION['userLevel'] == 10) {
		echo "<script>top.document.getElementById('pluginsContainer').innerHTML = '".$pluginsDisplay."';</script>";
	}

	// Work out what plugins we'll need to set on a setInterval
	$onLoadExtras = "";
	for ($i=0;$i<count($ICEcoder["plugins"]);$i++) {
		if ($ICEcoder["plugins"][$i][5]!="") {
			$onLoadExtras .= ";top.ICEcoder.startPluginIntervals(".$i.",'".$ICEcoder["plugins"][$i][3]."','".$ICEcoder["plugins"][$i][4]."','".$ICEcoder["plugins"][$i][5]."')";
		};
	};

	// If we're updating our settings, clear existing setIntervals & the array refs, then start new ones
	if (isset($_POST["theme"]) && $_POST["theme"] && $_SESSION['userLevel'] == 10) {
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

	// Finally, show server data if we're logged in
	if ($_SESSION['userLevel'] == 10) {
		$onLoadExtras .= ";top.ICEcoder.content.style.visibility='visible'";
	}
}

// If we're due to show the settings screen
if ($ICEcoder["accountPassword"] == "" && isset($_GET['settings'])) {
?>
	<!DOCTYPE html>

	<html>
	<head>
	<title>ICEcoder <?php echo $ICEcoder["versionNo"];?> setup</title>
	<link rel="stylesheet" type="text/css" href="coder.css">
	</head>

	<body onLoad="document.settingsUpdate.accountPassword.focus()">
	
	<div class="screenContainer" style="background-color: #141414">
		<div class="screenVCenter">
			<div class="screenCenter">
			<img src="../images/ice-coder.png">
			<div class="version"><?php echo $ICEcoder["versionNo"];?></div>
			<form name="settingsUpdate" action="../" method="POST">
			<input type="password" name="accountPassword" class="accountPassword">
			<input type="submit" name="submit" value="Set Password" class="button">
			</form>
			</div>
		</div>
	</div>

	</body>

	</html>
<?php
} else {
	// If the password hasn't been set, set it, but only if we're including
	// from the index.php file (as this file is included from multiple places)
	if ($ICEcoder["accountPassword"] == "" && strpos($_SERVER['PHP_SELF'],"index.php")>0) {
		// If we're setting a password

		if (isset($_POST['accountPassword'])) {
			$password = generateHash(strClean($_POST['accountPassword']));
			$settingsFile = 'lib/'.$settingsFile;
			$settingsContents = file_get_contents($settingsFile);
			// Replace our empty password with the one submitted by user
			$settingsContents = str_replace('"accountPassword"	=> "",','"accountPassword"	=> "'.$password.'",',$settingsContents);
			// Now update the config file
			$fh = fopen($settingsFile, 'w') or die("Can't update config file. Please set public write permissions on ".$settingsFile);
			fwrite($fh, $settingsContents);
			fclose($fh);
			// Set the session user level
			$_SESSION['userLevel'] = 10;
			// Finally, load again as now this file has changed and auto login
			header('Location: index.php');
		} else {
			// We need to set the password
			header('Location: lib/settings.php?settings=set');
		}
	}

	// If we're logging in, refresh the file manager and show icons if login is correct
	if(isset($_POST['loginPassword'])) {
		if(isset($_POST['loginPassword']) && generateHash(strClean($_POST['loginPassword']),$ICEcoder["accountPassword"])==$ICEcoder["accountPassword"]) {
			$loginAttempt = 'loginOK';
			if ($ICEcoder["previousFiles"]!="") {
				$openFilesArray = explode(",",$ICEcoder["previousFiles"]);
				echo '<script>';
				echo 'top.previousFiles = [';
				for ($i=0;$i<count($openFilesArray);$i++) {
					echo "'".$openFilesArray[$i]."'";
					if ($i<count($openFilesArray)-1) {echo ",";};
				}
				echo '];';
				echo '</script>';
			}
		} else {
			$loginAttempt = 'loginFailed';
		}
		echo "<script>top.ICEcoder.refreshFileManager('".$loginAttempt."');</script>";
	}
}
?>