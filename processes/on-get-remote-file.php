<?php
if (!isset($_SESSION['loggedIn'])) {
	die('Sorry, not logged in.');
}
// Purpose:	This file is run when a remote file is loaded, has $file string available to it
// Langs:	PHP only
// Example:
// $fh = fopen(dirname(__FILE__)."/../file-dir-access.log", 'a');
// fwrite($fh, "GET REMOTE FILE >>> ".date("D dS M Y h:i:sa").": ".$file."\n");
// fclose($fh);
?>