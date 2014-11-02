<?php
if (!isset($_SESSION['loggedIn'])) {
	die('Sorry, not logged in.');
}
// Purpose:	This file is run when a file or dir is renamed, has $fileLoc and $fileName strings available to it
// Langs:	PHP only
// Example:
// $fh = fopen(dirname(__FILE__)."/../file-dir-access.log", 'a');
// fwrite($fh, "RENAMED >>> ".date("D dS M Y h:i:sa").": ".$fileLoc."/".$fileName."\n");
// fclose($fh);
?>