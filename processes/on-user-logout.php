<?php
if (!isset($_SESSION['loggedIn'])) {
	die('Sorry, not logged in.');
}
// Purpose:	This file is run when a user logs out
// Langs:	PHP only
// Example:
// $fh = fopen(dirname(__FILE__)."/../user-access.log", 'a');
// fwrite($fh, "logout  ".date("D dS M Y h:i:sa").": ".$_SESSION['username']."\n");
// fclose($fh);
?>