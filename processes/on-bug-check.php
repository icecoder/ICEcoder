<?php
if (!isset($_SESSION['loggedIn'])) {
	die('Sorry, not logged in.');
}
// Purpose:	This file is run after a bug check, has $result string and $status arrays available to it
// Langs:	PHP only
// Example:
// $fh = fopen(dirname(__FILE__)."/../bug-logs.log", 'a');
// fwrite($fh, "Bug check on: ".date("D dS M Y h:i:sa")."\nresult: ".$result.", status array:".var_export($status, true)."\n");
// fclose($fh);
?>