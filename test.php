<!DOCTYPE html>
<html>
<head>
<title>ICEcoder requirements tests</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<meta name="viewport" content="width=device-width, initial-scale=0.5, user-scalable=yes">
<link rel="icon" type="image/png" href="favicon.png">
</head>

<body style="font-family: Arial">

This file checks requirements needed by ICEcoder to run.<br><br>
If there are no test results with red items below, you should be able to run it fine. If not, consider the items in red.<br><br>
<?php
echo "<b>Test date, time & microtime:</b><br>".date("jS M Y g:i:sa")." (".microtime(true).")<br><br><hr><br>";

$success = 0;


echo '<b>TEST 1 of 3: PHP Version:</b><br>ICEcoder needs at least PHP 5.0, tho 5.3 and above is recommended:<br><br>';
echo "Your PHP Version: ".phpversion()."<br><br>";

echo '<b>TEST RESULT:</b> ';
if (phpversion()*1 < 5.0) {
	echo '<span style="color: #b00; font-weight: bold">Your version of PHP doesn\'t seem to be high enough!</span>';
} else {
	echo '<span style="color: #080; font-weight: bold">PHP version is OK</span>';
	$success++;
}
echo "<br><br><hr><br>";



if(!isset($_SESSION)) {@session_start();}
echo '<b>TEST 2 of 3: Sessions:</b></b><br>Session ID: '.session_id().'. These values should all be the same:<br><br>';
echo '<div style="display: inline-block; width: 200px">SESSION, BEFORE SET:</div>'.$_SESSION["string"]."<br>";
if (!isset($_GET["string"])) {
	$_SESSION["string"] = md5(uniqid(mt_rand(), true));
	header("Location: test.php?string=".$_SESSION["string"]);
	echo "<script>window.location = 'test.php?string=".$_SESSION["string"]."';</script>";
	die('Rerirect didn\'t happen...');
}

echo '<div style="display: inline-block; width: 200px">SESSION, AFTER SET:</div>'.$_SESSION["string"]."<br>";
echo '<div style="display: inline-block; width: 200px">GET:</div>'.str_replace("<", "&lt;", str_replace(">", "&gt;", $_GET["string"]))."<br>";
echo '<div style="display: inline-block; width: 200px">REQUEST:</div>'.str_replace("<", "&lt;", str_replace(">", "&gt;", $_REQUEST["string"]))."<br>";
echo '<br>...and hitting this button shouldn\'t change the values:<br><br>';
echo '<div onclick="window.location=\'test.php?string='.$_SESSION["string"].'\'" style="display: inline-block; background: #ccc; padding: 10px; cursor: pointer">Reload page with GET param</div>';
echo "<br><br>";
echo '<b>TEST RESULT:</b> ';
if ($_REQUEST["string"] !== $_SESSION["string"]) {
	echo '<span style="color: #b00; font-weight: bold">Values do not match!</span>';
} else {
	echo '<span style="color: #080; font-weight: bold">Values match</span>';
	$success++;
}

echo "<br><br><hr><br>";



echo '<b>TEST 3 of 3: Includes:</b><br>Attempt to include settings file:<br><br>';
$configSettings = 'config___settings.php';

// Load config settings
include(dirname(__FILE__)."/lib/".$configSettings);

echo "This version of ICEcoder is: ".$ICEcoderSettings['versionNo']."<br><br>";
echo '<b>TEST RESULT:</b> ';
if (!isset($ICEcoderSettings['versionNo'])) {
	echo '<span style="color: #b00; font-weight: bold">Couldn\'t establish version, probably couldn\'t include settings file!</span>';
} else {
	echo '<span style="color: #080; font-weight: bold">Version established</span>';
	$success++;
}

echo "<br><br><hr><br>";

echo "<b>Overall Test result:</b><br>";
echo $success." of 3 tests passed successfully<br><br>";
?>

</body>

</html>
