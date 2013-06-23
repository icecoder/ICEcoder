<?php
// -----------------
// START USER CONFIG
// -----------------

// SYSTEM SETTINGS
// Document root and ICErepo version no:

$docRoot = $_SERVER['DOCUMENT_ROOT'];
$version = "0.8.0";

// AUTHENTICATION
// Can either be done by oauth (recommended), or username & password (less secure)

// oauth
$token = "";
// Basic
$username = "";
$password = "";

// REPOS & SERVER DIRS
// Here you identify the repo location and related path on your server
// (the last param is to identify which dropdown option to select by default).

$repos = array(
		"mattpass/dirTree",$docRoot."/TEST2","",
		"mattpass/TEST",$docRoot."/TEST","selected"
		);

// User level setting. Set at 1 or above to use
$_SESSION['userLevel'] = 10;

// ---------------
// END USER CONFIG
// ---------------

// Don't display, but log all errors
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__).'/error-log.txt');
error_reporting(-1);

// Set our default timezone and supress warning with @
@date_default_timezone_set(date_default_timezone_get());

// Set a stream context timeout for file reading
$context = stream_context_create(array('http'=>
	array(
		'timeout' => 60 // secs
	)
));

// Start a session if we haven't already
if(!isset($_SESSION)) {@session_start();}

if (!isset($_SESSION['userLevel']) || $_SESSION['userLevel'] < 1) {
	die("Sorry, you need to be logged in to use ICErepo");
}

// Set session vars if we're logging in via a session
if (isset($_REQUEST['token']) && $_REQUEST['token']!="") {
	$_SESSION['token'] = $_REQUEST['token'];
}
if (isset($_REQUEST['username']) && $_REQUEST['username']!="") {
	$_SESSION['username'] = $_REQUEST['username'];
	$_SESSION['password'] = $_REQUEST['password'];
}

// Reestablish those session vars in an ongoing way
if (isset($_SESSION['token'])) {
	$token = $_SESSION['token'] = $_SESSION['token'];
}
if (isset($_SESSION['username'])) {
	$username = $_SESSION['username'] = $_SESSION['username'];
	$password = $_SESSION['password'] = $_SESSION['password'];
}

if ($token=="" && $username=="" && !isset($_GET['sessionLogin'])) {
	header("Location: ?sessionLogin=true");
}

// returns converted entities where there are HTML entity equivalents
function strClean($var) {
	return htmlentities($var, ENT_QUOTES, "UTF-8");
}

// returns a number, whole or decimal or null
function numClean($var) {
	return is_numeric($var) ? floatval($var) : false;
}

// Function to sort given values alphabetically
function alphasort($a, $b) {
	return strcmp($a->getPathname(), $b->getPathname());
}

// Class to put forward the values for sorting
class SortingIterator implements IteratorAggregate {
	private $iterator = null;
	public function __construct(Traversable $iterator, $callback) {
		$array = iterator_to_array($iterator);
		usort($array, $callback);
		$this->iterator = new ArrayIterator($array);
	}
	public function getIterator() {
	return $this->iterator;
	}
}

// If magic quotes are still on (attempted to switch off in php.ini)
if (get_magic_quotes_gpc ()) {
	function stripslashes_deep($value) {
		$value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
		return $value;
	}
	$_POST = (isset($_POST) && !empty($_POST)) ? array_map('stripslashes_deep', $_POST) : array();
	$_GET = (isset($_GET) && !empty($_GET)) ? array_map('stripslashes_deep', $_GET) : array();
	$_COOKIE = (isset($_COOKIE) && !empty($_COOKIE)) ? array_map('stripslashes_deep', $_COOKIE) : array();
	$_REQUEST = (isset($_REQUEST) && !empty($_REQUEST)) ? array_map('stripslashes_deep', $_REQUEST) : array();
}
?>
