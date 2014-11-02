<?php
if (!isset($_SESSION['loggedIn'])) {
	die('Sorry, not logged in.');
}
// Purpose:	This file is run when a file or dir has its perms changed, has $fileLoc, $fileName and $_GET['perms'] strings available to it
// Langs:	PHP only
// Example:
// $fh = fopen(dirname(__FILE__)."/../file-dir-access.log", 'a');
// fwrite($fh, "PERMS >>> ".date("D dS M Y h:i:sa").": ".$fileLoc."/".$fileName." = ".$_GET['perms']."\n");
// fclose($fh);
?>