<?php
include_once("../lib/bug-tail.php");

if($_SESSION['loggedIn']) {
	$output = getBugs();
	echo  nl2br(htmlentities($output));
}
?>
