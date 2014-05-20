<?php
// Load common functions
include_once(dirname(__FILE__)."/settings-common.php");

// CSRF synchronizer token pattern, 32 chars
if (!isset($_SESSION["csrf"])) {
	$_SESSION["csrf"] = md5(uniqid(mt_rand(), true));	
}

if (($_GET || $_POST) && (!isset($_REQUEST["csrf"]) || $_REQUEST["csrf"] !== $_SESSION["csrf"])) {
	$req = isset($_REQUEST["csrf"]) ? xssClean($_REQUEST["csrf"],"html") : "";
	die("Bad CSRF token. Please report the error info at https://github.com/mattpass/ICEcoder so it can be fixed.<br><br>
		CSRF issue:<br>
		REQUEST: ".$req."<br>
		SESSION: ".xssClean($_SESSION["csrf"],"html")."<br>
		FILE: ".xssClean($_SERVER["SCRIPT_NAME"],"html")."<br>
		GET: ".xssClean(var_export($_GET, true),"html")."<br>
		POST: ".xssClean(var_export($_POST, true),"html")."<br>
		<br>Many thanks!");
}

// Set our security related headers
header("X-Frame-Options: SAMEORIGIN");					// Only frames of same origin
header("X-XSS-Protection: 1; mode=block");				// Turn on IE8-9 XSS prevention tools
// header("X-Content-Security-Policy: allow 'self'");			// Only allows JS on same domain & not inline to run
header("X-Content-Type-Options: nosniff");				// Prevent MIME based attacks
?>