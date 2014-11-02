<?php
if (!isset($_SESSION['loggedIn'])) {
	die('Sorry, not logged in.');
}
// Purpose:	This file is run when files are uploaded, has $uploads array available to it
// Langs:	PHP only
// Example:
// $fh = fopen(dirname(__FILE__)."/../file-dir-access.log", 'a');
// fwrite($fh, "UPLOAD >>> ".date("D dS M Y h:i:sa").": ".($uploads[0]->name)."\n");
// fclose($fh);
?>