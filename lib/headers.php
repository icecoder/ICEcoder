<?php
// Start a session if we haven't already
if(!isset($_SESSION)) {@session_start();}

// CSRF synchronizer token pattern, 32 chars
if (!isset($_SESSION["csrf"])) {
	$_SESSION["csrf"] = md5(uniqid(mt_rand(), true));	
}

if (($_GET || $_POST) && (!isset($_REQUEST["csrf"]) || $_REQUEST["csrf"] !== $_SESSION["csrf"])) {
	die("Bad CSRF token. Please report the error info at https://github.com/mattpass/ICEcoder so it can be fixed.<br><br>
		CSRF issue:<br>
		REQUEST: ".$_REQUEST["csrf"]."<br>
		SESSION: ".$_SESSION["csrf"]."<br>
		FILE: ".$_SERVER["SCRIPT_NAME"]."<br>
		GET: ".var_export($_GET, true)."<br>
		POST: ".var_export($_POST, true)."<br>
		<br>Many thanks!");
}

// Set our security related headers
header("X-Frame-Options: SAMEORIGIN");					// Only frames of same origin
header("X-XSS-Protection: 1; mode=block");				// Turn on IE8-9 XSS prevention tools
// header("X-Content-Security-Policy: allow 'self'");			// Only allows JS on same domain & not inline to run
header("X-Content-Type-Options: nosniff");				// Prevent MIME based attacks
?>