<?php
$versionNo		= "v 0.5.1";
$codeMirrorDir		= "CodeMirror-2.21";
$cMThisVer		= 2.21;
$testcMVersion		= false; // test if we're using the latest CodeMirror version
$restrictedFiles	= array(".php",".asp",".aspx");
$bannedFiles		= array("_coder","wp-",".exe");
$allowedIPs		= array("*"); // allowed IPs, * for any
$plugins		= array(
			array("Database Admin","images/database.png","margin-top: 3px","plugins/adminer/adminer-3.3.3-mysql-en.php","_blank",""),
			array("Batch Image Processor","images/images.png","margin-top: 5px","http://birme.net","_blank",""),
			array("Backup","images/backup-open-files.png","margin-top: 3px","plugins/backupOpenFiles/index.php","pluginActions","10"),
			array("Clipboard","images/clipboard.png","","javascript:alert('Doesn\'t do anything yet but will be a clipboard for copied text items, up to 100 levels')","_self","")
			);

// Establish our user level
if (!isset($_SESSION['userLevel'])) {
	session_start();
	$_SESSION['userLevel'] = 0;
}

if(isset($_GET['login']) && $_GET['login']=="acesHigh") {$_SESSION['userLevel'] = 10;};

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
?>