<?php
// ------------------------------------------------------------------
// Check Bugs for ICEcoder v1.0 by Last Mile Synergy, LLC
// Provides a service endpoint for checking on bug files
// ------------------------------------------------------------------
include_once("../lib/settings.php");
include_once("../lib/bug-tail.php");

if($_SESSION['loggedIn']) {
	$bugTail = new BugTail($ICEcoder);
	$output = $bugTail->bugCheck();

	echo  nl2br(htmlentities($output));
}
?>
