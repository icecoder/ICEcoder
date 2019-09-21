<?php
if (!isset($_SESSION['loggedIn'])) {
	die('Sorry, not logged in.');
}
// Purpose:	This file is run when a file is loaded, has $fileLoc and $fileName strings available to it
// Langs:	PHP (tho can output JS via PHP echo, see below)
// Example:
// $fh = fopen(dirname(__FILE__)."/../file-dir-access.log", 'a');
// fwrite($fh, "LOAD >>> ".date("D dS M Y h:i:sa").": ".$fileLoc."/".$fileName."\n");
// fclose($fh);

// If JS is needed, echo within string below, eg echo "alert('loaded');";
echo "";
