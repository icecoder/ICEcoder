<?php
if (!isset($_SESSION['loggedIn'])) {
	die('Sorry, not logged in.');
}
// Purpose:	This file is run when a login fail occurs
// Langs:	PHP only
// Example:
// $fh = fopen(dirname(__FILE__)."/../user-access.log", 'a');
// fwrite($fh, "FAIL    ".date("D dS M Y h:i:sa").": ".$_POST['username']."\n");
// fclose($fh);
?>