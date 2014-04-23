<?php
// Start a session if we haven't already
if(!isset($_SESSION)) {@session_start();}

// CSRF synchronizer token pattern, 32 chars
if (!isset($_SESSION["csrf"])) {
	$_SESSION["csrf"] = md5(uniqid(mt_rand(), true));	
}

if (($_GET || $_POST) && $_REQUEST["csrf"] !== $_SESSION["csrf"]) {
	echo '<script>alert("Bad CSRF token. Please press F12, view the console and report the error, including file & line number, so it can be fixed. Many thanks!");</script>';
	echo '<script>console.log("CSRF issue: REQUEST: "+$_REQUEST["csrf"]+", SESSION: "+$_SESSION["csrf"]);</script>';
	die('Bad CSRF token');
}

// Set our security related headers
header("X-Frame-Options: SAMEORIGIN");					// Only frames of same origin
header("X-XSS-Protection: 1; mode=block");				// Turn on IE8-9 XSS prevention tools
// header("X-Content-Security-Policy: allow 'self'");			// Only allows JS on same domain & not inline to run
header("X-Content-Type-Options: nosniff");				// Prevent MIME based attacks
?>