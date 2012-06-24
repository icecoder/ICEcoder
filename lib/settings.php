<?php
session_start();

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
include("config.php");

// Update this config file?
if (isset($_POST["theme"]) && $_POST["theme"] && $_SESSION['userLevel'] == 10) {
	$settingsFile = 'config.php';
	$settingsContents = file_get_contents($settingsFile);
	// Replace our settings vars
	$repPosStart = strpos($settingsContents,'$tabsIndent');
	$repPosEnd = strpos($settingsContents,'$previousFiles');

	// Prepare all our vars
	if ($_POST['tabsIndent'])		{$tabsIndent = "true";} else {$tabsIndent = "false";};
	if ($_POST['checkUpdates'])		{$checkUpdates = "true";} else {$checkUpdates = "false";};
	if ($_POST['openLastFiles'])		{$openLastFiles = "true";} else {$openLastFiles = "false";};
	$findFilesExclude			= 'array("'.str_replace(', ','","',strClean($_POST['findFilesExclude'])).'")';
	if ($_POST['codeAssist'])		{$codeAssist = "true";} else {$codeAssist = "false";};
	if ($_POST['visibleTabs'])		{$visibleTabs = "true";} else {$visibleTabs = "false";};
	if ($_POST['lockedNav'])		{$lockedNav = "true";} else {$lockedNav = "false";};
	if ($_POST['accountPassword']!="")	{$accountPassword = generateHash(strClean($_POST['accountPassword']));};
	$restrictedFiles			= 'array("'.str_replace(', ','","',strClean($_POST['restrictedFiles'])).'")';
	$bannedFiles				= 'array("'.str_replace(', ','","',strClean($_POST['bannedFiles'])).'")';
	$allowedIPs				= 'array("'.str_replace(', ','","',strClean($_POST['allowedIPs'])).'")';
	$plugins				= 'array('.PHP_EOL.'	array('.PHP_EOL.'	'.str_replace('====================','),'.PHP_EOL.'	array(',$_POST['plugins']).'))';
	$theme					= strClean($_POST['theme']);
	$tabWidth				= numClean($_POST['tabWidth']);

	$settingsNew  = '$tabsIndent		= '.$tabsIndent.';'.PHP_EOL;
	$settingsNew .= '$checkUpdates		= '.$checkUpdates.';'.PHP_EOL;
	$settingsNew .= '$openLastFiles		= '.$openLastFiles.';'.PHP_EOL;
	$settingsNew .= '$findFilesExclude	= '.$findFilesExclude.';'.PHP_EOL;
	$settingsNew .= '$codeAssist		= '.$codeAssist.';'.PHP_EOL;
	$settingsNew .= '$visibleTabs		= '.$visibleTabs.';'.PHP_EOL;
	$settingsNew .= '$lockedNav		= '.$lockedNav.';'.PHP_EOL;
	$settingsNew .= '$accountPassword	= "'.$accountPassword.'";'.PHP_EOL;
	$settingsNew .= '$restrictedFiles	= '.$restrictedFiles.';'.PHP_EOL;
	$settingsNew .= '$bannedFiles		= '.$bannedFiles.';'.PHP_EOL;
	$settingsNew .= '$allowedIPs		= '.$allowedIPs.';'.PHP_EOL;
	$settingsNew .= '$plugins		= '.$plugins.';'.PHP_EOL;
	$settingsNew .= '$theme			= "'.$theme.'";'.PHP_EOL;
	$settingsNew .= '$tabWidth		= '.$tabWidth.';'.PHP_EOL;

	// Compile our new settings
	$settingsContents = substr($settingsContents,0,$repPosStart).$settingsNew.substr($settingsContents,($repPosEnd),strlen($settingsContents));
	// Now update the config file
	$fh = fopen($settingsFile, 'w') or die("Can't update config file. Please set public write permissions on lib/config.php");
	fwrite($fh, $settingsContents);
	fclose($fh);

	// OK, now the config file has been updated, update our current session with new arrays
	$_SESSION['findFilesExclude'] = $findFilesExclude = explode(", ",strClean($_POST['findFilesExclude']));
	$_SESSION['restrictedFiles'] = $restrictedFiles = explode(", ",strClean($_POST['restrictedFiles']));
	$_SESSION['bannedFiles'] = $bannedFiles = explode(", ",strClean($_POST['bannedFiles']));
	$_SESSION['allowedIPs'] = $allowedIPs = explode(", ",strClean($_POST['allowedIPs']));
	// Work out the theme to use now
	if ($theme=="default") {$themeURL="lib/editor.css";} else {$themeURL=$codeMirrorDir."/theme/".$theme.".css";};
	// Do we need a file manager refresh?
	if ($_POST['changedFileSettings']=="true") {$refreshFM="true";} else {$refreshFM="false";};
	// With all that worked out, we can now hide the settings screen and apply the new settings
	echo "<script>top.ICEcoder.settingsScreen('hide');top.ICEcoder.useNewSettings('".$themeURL."',".$tabsIndent.",".$codeAssist.",".$lockedNav.",".$visibleTabs.",".$tabWidth.",".$refreshFM.");</script>";
}

// Save the currently opened files for next time
if (isset($_GET["saveFiles"]) && $_GET['saveFiles']) {
	if ($_SESSION['userLevel'] == 10) {
		$settingsFile = 'config.php';
		$settingsContents = file_get_contents($settingsFile);

		// Replace our previousFiles var with the the current
		$repPosStart = strpos($settingsContents,'previousFiles		= "')+18;
		$repPosEnd = strpos($settingsContents,'";',$repPosStart)-$repPosStart;
		if ($_GET['saveFiles']!="CLEAR") {$saveFiles=strClean($_GET['saveFiles']);} else {$saveFiles="";};
		$settingsContents1 = substr($settingsContents,0,$repPosStart).$saveFiles.substr($settingsContents,($repPosStart+$repPosEnd),strlen($settingsContents));
		// Now update the config file
		$fh = fopen($settingsFile, 'w') or die("Can't update config file. Please set public write permissions on lib/config.php");
		fwrite($fh, $settingsContents1);

		// Update our top10Files var?
		$saveFilesArray = explode(",",$saveFiles);
		$last10FilesArray = explode(",",$last10Files);
		for ($i=0;$i<count($saveFilesArray);$i++) {
			$inLast10Files = in_array($saveFilesArray[$i],$last10FilesArray);
			if (!$inLast10Files && $saveFilesArray[$i] !="") {
				$repPosStart = strpos($settingsContents1,'last10Files		= "')+16;
				$repPosEnd = strpos($settingsContents1,'";',$repPosStart)-$repPosStart;
				if ($last10Files!="") {$commaExtra=",";} else {$commaExtra="";};
				if (count($last10FilesArray)>=10) {$last10Files=substr($last10Files,0,strrpos($last10Files,','));};
				$settingsContents2 = substr($settingsContents1,0,$repPosStart).$saveFilesArray[$i].$commaExtra.$last10Files.substr($settingsContents1,($repPosStart+$repPosEnd),strlen($settingsContents1));
				// Now update the config file
				$fh = fopen($settingsFile, 'w') or die("Can't update config file. Please set public write permissions on lib/config.php");
				fwrite($fh, $settingsContents2);
			}
		}
		fclose($fh);
	}
	echo '<script>top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);</script>';
}

// Establish our user level
if (!isset($_SESSION['userLevel'])) {$_SESSION['userLevel'] = 0;};
if(isset($_POST['loginPassword']) && generateHash(strClean($_POST['loginPassword']),$accountPassword)==$accountPassword) {$_SESSION['userLevel'] = 10;};
$_SESSION['userLevel'] = $_SESSION['userLevel'];

if (!isset($_SESSION['findFilesExclude'])) {$_SESSION['findFilesExclude'] = $findFilesExclude;}
if (!isset($_SESSION['restrictedFiles'])) {$_SESSION['restrictedFiles'] = $restrictedFiles;}
if (!isset($_SESSION['bannedFiles'])) {$_SESSION['bannedFiles'] = $bannedFiles;}
if (!isset($_SESSION['allowedIPs'])) {$_SESSION['allowedIPs'] = $allowedIPs;}

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

// Establish our shortened URL, explode the path based on server type (Linux or Windows)
if (strpos($_SERVER['DOCUMENT_ROOT'],"/")>-1) {$slashType = "/";} else {$slashType = "\\";};
$shortURLStarts = explode($slashType,$_SERVER['DOCUMENT_ROOT']);

// Then clear item at the end if there is one, plus trailing slash
// We end up with the directory name of the server root
if ($shortURLStarts[count($shortURLStarts)-1]!="") {$trimArray=1;} else {$trimArray=2;}
$shortURLStarts = $shortURLStarts[count($shortURLStarts)-$trimArray];

// If we're updating or calling from the index.php page, do/redo plugins & last opened files
if ((isset($_POST["theme"]) && $_POST["theme"] && $_SESSION['userLevel'] == 10) || strpos($_SERVER['PHP_SELF'],"index.php")>0) {
	// If we're updating, we need to recreate the plugins array
	if (isset($_POST["theme"]) && $_POST["theme"] && $_SESSION['userLevel'] == 10) {
		$plugins = array();
		$pluginsArray = explode("====================",str_replace("\"","",str_replace("\r","",str_replace("\n","",$_POST['plugins']))));
		for ($i=0;$i<count($pluginsArray);$i++) {
			array_push($plugins, explode(",",$pluginsArray[$i]));
		}
	}

	// Work out the plugins to display to the user
	$pluginsDisplay = "";
	for ($i=0;$i<count($plugins);$i++) {
		$target = explode(":",$plugins[$i][4]);
		$pluginsDisplay .= '<a href="'.$plugins[$i][3].'" title="'.$plugins[$i][0].'" target="'.$target[0].'"><img src="'.$plugins[$i][1].'" style="'.$plugins[$i][2].'" alt="'.$plugins[$i][0].'"></a>';
	};

	// If we're updating, replace the plugin display with our newly established one
	if (isset($_POST["theme"]) && $_POST["theme"] && $_SESSION['userLevel'] == 10) {
		echo "<script>top.document.getElementById('pluginsContainer').innerHTML = '".$pluginsDisplay."';</script>";
	}

	// Work out what plugins we'll need to set on a setInterval
	$onLoadExtras = "";
	for ($i=0;$i<count($plugins);$i++) {
		if ($plugins[$i][5]!="") {
			$onLoadExtras .= ";top.ICEcoder.startPluginIntervals(".$i.",'".$plugins[$i][3]."','".$plugins[$i][4]."','".$plugins[$i][5]."')";
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

	// Finally, open last opened files if we need to (applies to index.php only)
	if ($openLastFiles) {
		$onLoadExtras .= ";top.ICEcoder.autoOpenFiles()";
	}

	// Show server data if we're logged in
	if ($_SESSION['userLevel'] == 10) {
		$onLoadExtras .= ";top.ICEcoder.content.style.visibility='visible'";
	}
}

// If we're due to show the settings screen
if ($accountPassword == "" && isset($_GET['settings'])) {
?>
	<!DOCTYPE html>

	<html>
	<head>
	<title>ICE Coder - <?php echo $versionNo;?> :: Settings</title>
	<link rel="stylesheet" type="text/css" href="coder.css">
	</head>

	<body>
	
	<div class="screenContainer">
		<div class="screenVCenter">
			<div class="screenCenter">
			<img src="../images/ice-coder.png">
			<div class="version"><?php echo $versionNo;?></div>
			<form name="settingsUpdate" action="../index.php" method="POST">
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
	if ($accountPassword == "" && strpos($_SERVER['PHP_SELF'],"index.php")>0) {
		// If we're setting a password
		if (isset($_POST['accountPassword'])) {
			$password = generateHash(strClean($_POST['accountPassword']));
			$settingsFile = 'lib/config.php';
			$settingsContents = file_get_contents($settingsFile);
			// Replace our empty password with the one submitted by user
			$settingsContents = str_replace('$accountPassword	= "";','$accountPassword	= "'.$password.'";',$settingsContents);
			// Now update the config file
			$fh = fopen($settingsFile, 'w') or die("Can't update config file. Please set public write permissions on lib/config.php");
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
		if(isset($_POST['loginPassword']) && generateHash(strClean($_POST['loginPassword']),$accountPassword)==$accountPassword) {
			$loginAttempt = 'loginOK';
		} else {
			$loginAttempt = 'loginFailed';
		}
		echo "<script>top.ICEcoder.refreshFileManager('".$loginAttempt."');</script>";
	}
}
?>