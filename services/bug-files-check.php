<?php
include_once("../lib/bug-tail.php");

if($_SESSION['loggedIn']) {
	$output = bugCheck();
	echo json_encode($output);
}
?>
