<?php
if (!isset($_SESSION['loggedIn'])) {
	die('Sorry, not logged in.');
}
// Purpose:	This file is run when a new user sets up
// Langs:	PHP (tho can concat JS within $doNext string, see below)
// Example:
// $fh = fopen(dirname(__FILE__)."/../user-access.log", 'a');
// fwrite($fh, "NEW >>> ".date("D dS M Y h:i:sa").": ".$_SESSION['username']."\n");
// fclose($fh);

// If JS is needed, add within $doNext string below, eg $doNext .= ";alert('new user');";
$doNext .= "";
