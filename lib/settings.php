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

// -----------------
// Start of settings
// -----------------

$versionNo		= "v 0.6.7";
$codeMirrorDir		= "CodeMirror-2.24";
$cMThisVer		= 2.24;
$tabsIndent		= true;
$testcMVersion		= false;
$openLastFiles		= true;
$codeAssist		= true;
$visibleTabs		= false;
$lockedNav		= true;
$accountPassword	= "";
$restrictedFiles	= array("wp-",".php",".rb",".sql");
$bannedFiles		= array("_coder","wp-",".exe");
$allowedIPs		= array("*");
$plugins		= array(
			array("Database Admin","images/database.png","margin-top: 3px","plugins/adminer/adminer-3.3.3-mysql-en.php","_blank",""),
			array("Batch Image Processor","images/images.png","margin-top: 5px","http://birme.net","_blank",""),
			array("Backup","images/backup-open-files.png","margin-top: 3px","plugins/backupOpenFiles/index.php","fileControl:<b>Zipping Open Files</b>","10")
			);
$theme			= "default";
$lastOpenedFiles	= "";

// ---------------
// End of settings
// ---------------


// Update this settings file?
if (isset($_POST["theme"]) && $_POST["theme"] && $_SESSION['userLevel'] == 10) {
	$settingsFile = 'settings.php';
	$settingsContents = file_get_contents($settingsFile);
	// Replace our lastOpenedFiles var with the the current
	$repPosStart = strpos($settingsContents,'$tabsIndent');
	$repPosEnd = strpos($settingsContents,'$lastOpenedFiles');

	// Prepare all our vars
	if ($_POST['tabsIndent'])		{$tabsIndent = "true";} else {$tabsIndent = "false";};
	if ($_POST['testcMVersion'])		{$testcMVersion = "true";} else {$testcMVersion = "false";};
	if ($_POST['openLastFiles'])		{$openLastFiles = "true";} else {$openLastFiles = "false";};
	if ($_POST['codeAssist'])		{$codeAssist = "true";} else {$codeAssist = "false";};
	if ($_POST['visibleTabs'])		{$visibleTabs = "true";} else {$visibleTabs = "false";};
	if ($_POST['lockedNav'])		{$lockedNav = "true";} else {$lockedNav = "false";};
	if ($_POST['accountPassword']!="")	{$accountPassword = generateHash($_POST['accountPassword']);} else {$accountPassword = $_POST['oldPassword'];};
	$restrictedFiles			= 'array("'.str_replace(', ','","',$_POST['restrictedFiles']).'")';
	$bannedFiles				= 'array("'.str_replace(', ','","',$_POST['bannedFiles']).'")';
	$allowedIPs				= 'array("'.str_replace(', ','","',$_POST['allowedIPs']).'")';
	$plugins				= 'array('.PHP_EOL.'	array('.PHP_EOL.'	'.str_replace('====================','),'.PHP_EOL.'	array(',$_POST['plugins']).'))';
	$theme					= $_POST['theme'];

	$settingsNew  = '$tabsIndent		= '.$tabsIndent.';'.PHP_EOL;
	$settingsNew .= '$testcMVersion		= '.$testcMVersion.';'.PHP_EOL;
	$settingsNew .= '$openLastFiles		= '.$openLastFiles.';'.PHP_EOL;
	$settingsNew .= '$codeAssist		= '.$codeAssist.';'.PHP_EOL;
	$settingsNew .= '$visibleTabs		= '.$visibleTabs.';'.PHP_EOL;
	$settingsNew .= '$lockedNav		= '.$lockedNav.';'.PHP_EOL;
	$settingsNew .= '$accountPassword	= "'.$accountPassword.'";'.PHP_EOL;
	$settingsNew .= '$restrictedFiles	= '.$restrictedFiles.';'.PHP_EOL;
	$settingsNew .= '$bannedFiles		= '.$bannedFiles.';'.PHP_EOL;
	$settingsNew .= '$allowedIPs		= '.$allowedIPs.';'.PHP_EOL;
	$settingsNew .= '$plugins		= '.$plugins.';'.PHP_EOL;
	$settingsNew .= '$theme			= "'.$theme.'";'.PHP_EOL;

	// Compile our new settings
	$settingsContents = substr($settingsContents,0,$repPosStart).$settingsNew.substr($settingsContents,($repPosEnd),strlen($settingsContents));
	// Now update this file
	$fh = fopen($settingsFile, 'w') or die("can't update settings file");
	fwrite($fh, $settingsContents);
	fclose($fh);

	// OK, now this file is updated, update our current session with new arrays
	$_SESSION['restrictedFiles'] = $restrictedFiles = explode(", ",$_POST['restrictedFiles']);
	$_SESSION['bannedFiles'] = $bannedFiles = explode(", ",$_POST['bannedFiles']);
	$_SESSION['allowedIPs'] = $allowedIPs = explode(", ",$_POST['allowedIPs']);
	// Work out the theme to use now
	if ($theme=="default") {$themeURL="lib/editor.css";} else {$themeURL=$codeMirrorDir."/theme/".$theme.".css";};
	// Do we need a file manager refresh?
	if ($_POST['changedFileSettings']=="true") {$refreshFM="true";} else {$refreshFM="false";};
	// With all that worked out, we can now hide the settings screen and apply the new settings
	echo "<script>top.ICEcoder.settingsScreen('hide');top.ICEcoder.useNewSettings('".$themeURL."',".$tabsIndent.",".$codeAssist.",".$lockedNav.",".$visibleTabs.",".$refreshFM.");</script>";
}

// Save the currently opened files for next time
if (isset($_GET["saveFiles"]) && $_GET['saveFiles']) {
	if ($_SESSION['userLevel'] == 10) {
		$settingsFile = 'settings.php';
		$settingsContents = file_get_contents($settingsFile);
		// Replace our lastOpenedFiles var with the the current
		$repPosStart = strpos($settingsContents,'lastOpenedFiles	= "')+19;
		$repPosEnd = strpos($settingsContents,'";',$repPosStart)-$repPosStart;
		$settingsContents = substr($settingsContents,0,$repPosStart).$_GET['saveFiles'].substr($settingsContents,($repPosStart+$repPosEnd),strlen($settingsContents));
		// Now update this file
		$fh = fopen($settingsFile, 'w') or die("can't update settings file");
		fwrite($fh, $settingsContents);
		fclose($fh);
	}
	echo '<script>top.ICEcoder.serverMessage();top.ICEcoder.serverQueue("del",0);</script>';
}

// Establish our user level
if (!isset($_SESSION['userLevel'])) {$_SESSION['userLevel'] = 0;};
if(isset($_POST['loginPassword']) && generateHash($_POST['loginPassword'],$accountPassword)==$accountPassword) {$_SESSION['userLevel'] = 10;};
$_SESSION['userLevel'] = $_SESSION['userLevel'];

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
		$pluginsDisplay .= '<a href="'.$plugins[$i][3].'" target="'.$target[0].'"><img src="'.$plugins[$i][1].'" style="'.$plugins[$i][2].'" alt="'.$plugins[$i][0].'"></a>';
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

	<body style="background-color: #ffffff">
	
	<div class="screenContainer">
		<div class="screenVCenter">
			<div class="screenCenter">
			<img src="../images/ice-coder.gif">
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
			$password = generateHash($_POST['accountPassword']);
			$settingsFile = 'lib/settings.php';
			$settingsContents = file_get_contents($settingsFile);
			// Replace our empty password with the one submitted by user
			$settingsContents = str_replace('$accountPassword	= "";','$accountPassword	= "'.$password.'";',$settingsContents);
			// Now update this file
			$fh = fopen($settingsFile, 'w') or die("can't update settings file");
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
		if(isset($_POST['loginPassword']) && generateHash($_POST['loginPassword'],$accountPassword)==$accountPassword) {
			$loginAttempt = 'loginOK';
		} else {
			$loginAttempt = 'loginFailed';
		}
		echo "<script>top.ICEcoder.refreshFileManager('".$loginAttempt."');</script>";
	}
}
?>