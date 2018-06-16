<?php
// Stop if we're running an old version in the tmp dir
if(strpos(str_replace("\\","/",__DIR__),"tmp/oldVersion") !== false) {
	die("This is an old version of ICEcoder. Won't run from tmp/oldVersion/ dir.");
}

// Load common functions
include_once(__DIR__."/settings-common.php");
if (isset($_SESSION['text'])) {
	$text = $_SESSION['text'];
	$t = $text['headers'];
}

// CSRF synchronizer token pattern, 32 chars
if (!isset($_SESSION["csrf"])) {
	$_SESSION["csrf"] = md5(uniqid(mt_rand(), true));
}

if (($_GET || $_POST) && (!isset($_REQUEST["csrf"]) || $_REQUEST["csrf"] !== $_SESSION["csrf"])) {
	$req = isset($_REQUEST["csrf"]) ? xssClean($_REQUEST["csrf"],"html") : "";
	die($t['Bad CSRF token...']."<br><br>
		CSRF issue:<br>
		REQUEST: ".$req."<br>
		SESSION: ".xssClean($_SESSION["csrf"],"html")."<br>
		FILE: ".xssClean($_SERVER["SCRIPT_NAME"],"html")."<br>
		GET: ".xssClean(var_export($_GET, true),"html")."<br>
		POST: ".xssClean(var_export($_POST, true),"html"));
}

if (!headers_sent()) {
	// Set our security related headers
	header("X-Frame-Options: SAMEORIGIN");					// Only frames of same origin
	header("X-XSS-Protection: 1; mode=block");				// Turn on IE8-9 XSS prevention tools
	// header("X-Content-Security-Policy: allow 'self'");			// Only allows JS on same domain & not inline to run
	header("X-Content-Type-Options: nosniff");				// Prevent MIME based attacks
	header('Cache-Control: no-cache, no-store, must-revalidate');		// Caching over HTTP 1.1 covered
	header('Pragma: no-cache');						// Caching over HTTP 1.0 covered
	header('Expires: 0');							// Caching over Proxies covered
}
?>
