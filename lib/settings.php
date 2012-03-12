<?php
$versionNo		= "v 0.5.8";
$codeMirrorDir		= "CodeMirror-2.22";
$cMThisVer		= 2.22;
$testcMVersion		= false; // test if we're using the latest CodeMirror version
$restrictedFiles	= array("wp-",".php",".asp",".aspx");
$bannedFiles		= array("_coder","wp-",".exe");
$allowedIPs		= array("*"); // allowed IPs, * for any
$plugins		= array(
			array("Database Admin","images/database.png","margin-top: 3px","plugins/adminer/adminer-3.3.3-mysql-en.php","_blank",""),
			array("Batch Image Processor","images/images.png","margin-top: 5px","http://birme.net","_blank",""),
			array("Backup","images/backup-open-files.png","margin-top: 3px","plugins/backupOpenFiles/index.php","pluginActions","10"),
			array("Clipboard","images/clipboard.png","","javascript:alert('Doesn\'t do anything yet but will be a clipboard for copied text items, up to 100 levels')","_self","")
			);
$accountPassword	= "";

// ---------------
// End of settings
// ---------------

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

session_start();
// Establish our user level
if (!isset($_SESSION['userLevel'])) {$_SESSION['userLevel'] = 0;};
if(isset($_POST['loginPassword']) && generateHash($_POST['loginPassword'],$accountPassword)==$accountPassword) {$_SESSION['userLevel'] = 10;};
$_SESSION['userLevel'] = $_SESSION['userLevel'];

if (!isset($_SESSION['restrictedFiles'])) {$_SESSION['restrictedFiles'] = $restrictedFiles;}
if (!isset($_SESSION['bannedFiles'])) {$_SESSION['bannedFiles'] = $bannedFiles;}

// Establish our shortened URL, explode the path based on server type (Linux or Windows)
if (strpos($_SERVER['DOCUMENT_ROOT'],"/")>-1) {$slashType = "/";} else {$slashType = "\\";};
$shortURLStarts = explode($slashType,$_SERVER['DOCUMENT_ROOT']);

// Then clear item at the end if there is one, plus trailing slash
// We end up with the directory name of the server root
if ($shortURLStarts[count($shortURLStarts)-1]!="") {$trimArray=1;} else {$trimArray=2;}
$shortURLStarts = $shortURLStarts[count($shortURLStarts)-$trimArray];

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
}
?>