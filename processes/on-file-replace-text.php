<?php
if (!isset($_SESSION['loggedIn'])) {
	die('Sorry, not logged in.');
}
// Purpose:	This file is run when a file has text in it replaced, has $_GET['fileRef'] string available to it
// Langs:	PHP (tho can concat JS within $doNext string, see below)
// Example:
// $fh = fopen(dirname(__FILE__)."/../file-dir-access.log", 'a');
// fwrite($fh, "REPLACED TEXT >>> ".date("D dS M Y h:i:sa").": ".$_GET['fileRef']."\n");
// fclose($fh);

// If JS is needed, add within $doNext string below, eg $doNext .= ";alert('renamed');";
$doNext .= "";
