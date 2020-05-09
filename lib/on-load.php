<?php
require "icecoder.php";

use ICEcoder\ExtraProcesses;

if (!isset($_SESSION['loggedIn'])) {
	die('Sorry, not logged in.');
}

$extraProcesses = new ExtraProcesses();
$doNext = $extraProcesses->onLoad();
echo '<script>' . $doNext . '</script>';
?>
