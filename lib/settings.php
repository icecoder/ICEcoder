<?php
// Establish settings and users template filenames
$configUsersTemplate = 'template-users.php';

require_once dirname(__FILE__) . "/../classes/_ExtraProcesses.php";
require_once dirname(__FILE__) . "/../classes/Settings.php";
require_once dirname(__FILE__) . "/../classes/System.php";

use ICEcoder\ExtraProcesses;

$settingsClass = new \ICEcoder\Settings();
$systemClass = new \ICEcoder\System();

// Create a new config file if it doesn't exist yet.
// The reason we create it, is so it has PHP write permissions, meaning we can update it later
if (false === $settingsClass->getConfigGlobalFileDetails()['exists']) {
    if (false === $settingsClass->setConfigGlobalSettings($settingsClass->getConfigGlobalTemplate())) {
        $reqsPassed = false;
        $reqsFailures = ["phpCreateConfig"];
        include dirname(__FILE__) . "/requirements.php";
    }
}

// Check config settings file exists
if (false === $settingsClass->getConfigGlobalFileDetails()['exists']) {
    $reqsPassed = false;
    $reqsFailures = ["phpFileExists"];
    include dirname(__FILE__) . "/requirements.php";
}

// Check we can read config settings file
if (false === $settingsClass->getConfigGlobalFileDetails()['readable']) {
    $reqsPassed = false;
    $reqsFailures = ["phpReadFile"];
    include dirname(__FILE__) . "/requirements.php";
}

// Check we can write config settings file
if (false === $settingsClass->getConfigGlobalFileDetails()['writable']) {
    $reqsPassed = false;
    $reqsFailures = ["phpWriteFile"];
    include dirname(__FILE__) . "/requirements.php";
}

// Load config settings
$ICEcoderSettings = $settingsClass->getConfigGlobalSettings();

// Load common functions
include_once dirname(__FILE__) . "/settings-common.php";

// Establish user settings file
$username = "";
if (true === isset($_POST['username']) && "" !== $_POST['username']) {$username = $_POST['username'] . "-";};
if (true === isset($_SESSION['username']) && "" !== $_SESSION['username']) {$username = $_SESSION['username'] . "-";};
$settingsFile = 'config-' . $username . str_replace(".", "_", str_replace("www.", "", $_SERVER['SERVER_NAME'])) . '.php';

// Login is default
$setPWorLogin = "login";

// Create user settings file if it doesn't exist
if (false === file_exists(dirname(__FILE__) . "/../data/" . $settingsFile) && $ICEcoderSettings['enableRegistration']) {
    if (false === copy(dirname(__FILE__) . "/" . $configUsersTemplate, dirname(__FILE__) . "/../data/" . $settingsFile)) {
        $reqsPassed = false;
        $reqsFailures = ["phpCreateSettings"];
        include dirname(__FILE__) . "/requirements.php";
    }
    $setPWorLogin = "set password";
}

// Load user settings
$systemClass->invalidateOPCache(dirname(__FILE__) . "/../data/" . $settingsFile);
include dirname(__FILE__) . "/../data/" . $settingsFile;

// Remove any previous files that are no longer there
$prevFiles = explode(",", $ICEcoderUserSettings['previousFiles']);
$prevFilesAvail = "";
for ($i = 0; $i < count($prevFiles); $i++) {
    if (true === file_exists(str_replace("|", "/", $prevFiles[$i]))) {
        $prevFilesAvail .= $prevFiles[$i] . ",";
    }
}
$prevFilesAvail = rtrim($prevFilesAvail, ",");
$ICEcoderUserSettings['previousFiles'] = $prevFilesAvail;

// Replace our config created date with the filemtime?
if ("index.php" === basename($_SERVER['SCRIPT_NAME']) && 0 === $ICEcoderUserSettings['configCreateDate']) {
    $settingsClass->updateConfigCreateDate();
}

// On mismatch of settings file to system, rename to .old and reload
If ($ICEcoderUserSettings["versionNo"] != $ICEcoderSettings["versionNo"]) {
    rename(dirname(__FILE__) . "/../data/" . $settingsFile, dirname(__FILE__) . "/../data/" . str_replace(".php", ".old", $settingsFile));
    header("Location: settings.php");
    echo "<script>window.location = 'settings.php';</script>";
    die('Found old settings file, reloading...');
}

// Join ICEcoder settings and user settings together to make our final ICEcoder array
$ICEcoder = $ICEcoderSettings + $ICEcoderUserSettings;

// Include language file
// Load base first as foundation
include dirname(__FILE__) . "/../lang/" . basename($ICEcoder['languageBase']);
$baseText = $text;

// Load chosen language ontop to replace base
include dirname(__FILE__) . "/../lang/" . basename($ICEcoder['languageUser']);
$text = array_replace_recursive($baseText, $text);
$_SESSION['text'] = $text;

// Login not required or we're in demo mode and have password set in our settings, log us straight in
if ((false === $ICEcoder['loginRequired'] || true === $ICEcoder['demoMode']) && "" !== $ICEcoder['password']) {
    $_SESSION['loggedIn'] = true;
};
$demoMode = $ICEcoder['demoMode'];

// Update this config file?
include dirname(__FILE__) . "/settings-update.php";

// Set loggedIn and username to false if not set as yet
if (false === isset($_SESSION['loggedIn'])) {$_SESSION['loggedIn'] = false;};
if (false === isset($_SESSION['username'])) {$_SESSION['username'] = "";};

// Attempt a login with password
if (true === isset($_POST['submit']) && "login" === $setPWorLogin) {
    // On success, set username if multiUser, loggedIn to true and redirect
    if (verifyHash($_POST['password'], $ICEcoder["password"]) === $ICEcoder["password"]) {
        session_regenerate_id();
        if ($ICEcoder["multiUser"]) {
            $_SESSION['username'] = $_POST['username'];
        }
        $_SESSION['loggedIn'] = true;
        $extraProcessesClass = new ExtraProcesses();
        $extraProcessesClass->onUserLogin($_SESSION['username'] ?? "");
        header('Location: ../');
        echo "<script>window.location = '../';</script>";
        die('Logging you in...');
    } else {
        $extraProcessesClass = new ExtraProcesses();
        $extraProcessesClass->onUserLoginFail($_SESSION['username'] ?? "");
    }
};

// Re-establish our loggedIn state and username
$_SESSION['loggedIn'] = $_SESSION['loggedIn'];
$_SESSION['username'] = $_SESSION['username'];

// Define the serverType, docRoot & iceRoot
$serverType = $systemClass->getOS();
$docRoot = rtrim(str_replace("\\", "/", $ICEcoder['docRoot']));
$iceRoot = rtrim(str_replace("\\", "/", $ICEcoder["root"]));
if ($_SESSION['loggedIn'] && "index.php" === basename($_SERVER['SCRIPT_NAME'])) {
    echo "<script>docRoot = '" . $docRoot . "'; iceRoot='" . $iceRoot . "'</script>";
}

// Establish the dir ICEcoders running from
$ICEcoderDirFullPath = rtrim(str_replace("\\", "/", dirname($_SERVER['SCRIPT_FILENAME'])), "/lib");
$rootPrefix = '/' . str_replace("/", "\/", preg_quote(str_replace("\\", "/", $docRoot))) . '/';
$ICEcoderDir = preg_replace($rootPrefix, '', $ICEcoderDirFullPath, 1);

// Setup our file security vars
$settingsArray = array("findFilesExclude", "bannedFiles", "allowedIPs");
for ($i = 0; $i < count($settingsArray); $i++) {
    if (false === isset($_SESSION[$settingsArray[$i]])) {
        $_SESSION[$settingsArray[$i]] = $ICEcoder[$settingsArray[$i]];
    }
}

// Check IP permissions
if (false === in_array(getUserIP(), $_SESSION['allowedIPs']) && false === in_array("*", $_SESSION['allowedIPs'])) {
    header('Location: /');
    $reqsPassed = false;
    $reqsFailures = ["systemIPRestriction"];
    include(dirname(__FILE__) . "/requirements.php");
    exit;
};

// Establish any FTP site to use
if (true === isset($_SESSION['ftpSiteRef']) && false !== $_SESSION['ftpSiteRef']) {
    $ftpSiteArray = $ICEcoder['ftpSites'][$_SESSION['ftpSiteRef']];
    $ftpSite = $ftpSiteArray['site'];                                         // FTP site domain, eg http://yourdomain.com
    $ftpHost = $ftpSiteArray['host'];                                         // FTP host, eg ftp.yourdomain.com
    $ftpUser = $ftpSiteArray['user'];                                         // FTP username
    $ftpPass = $ftpSiteArray['pass'];                                         // FTP password
    $ftpPasv = $ftpSiteArray['pasv'];                                         // FTP account requires PASV mode?
    $ftpMode = $ftpSiteArray['mode'] == "FTP_ASCII" ? FTP_ASCII : FTP_BINARY; // FTP transfer mode, FTP_ASCII or FTP_BINARY
    $ftpRoot = $ftpSiteArray['root'];                                         // FTP root dir to use as base, eg /htdocs
}

// Save currently opened files in previousFiles and last10Files arrays
include(dirname(__FILE__) . "/settings-save-current-files.php");

// Display the plugins
include(dirname(__FILE__) . "/plugins-display.php");

// If loggedIn is false or we don't have a password set yet and we're not on login screen, boot user to that
if (false === isset($_POST['password']) && (!$_SESSION['loggedIn'] || "" === $ICEcoder["password"]) && false === strpos($_SERVER['SCRIPT_NAME'], "lib/login.php")) {
    if (file_exists('lib/login.php')) {
        header('Location: ' . rtrim($_SERVER['REQUEST_URI'], "/") . '/lib/login.php');
        echo "<script>window.location = 'lib/login.php';</script>";
    } else {
        header('Location: login.php');
        echo "<script>window.location = 'login.php';</script>";
    }
    die('Redirecting to login...');

// If we are on the login screen and not logged in
} elseif (!$_SESSION['loggedIn']) {
    // If the password hasn't been set and we're setting it
    if ("" === $ICEcoder["password"] && true === isset($_POST['submit']) && -1 < strpos($_POST['submit'],"set password")) {
        $password = str_replace("\$", "\\$", generateHash($_POST['password']));
        $settingsClass->updatePasswordCheckUpdates();
        $settingsClass->createIPSettingsFileIfNotExist();
        $settingsClass->disableFurtherRegistration();
        // Set the session user level
        if ($ICEcoder["multiUser"]) {
            $_SESSION['username'] = $_POST['username'];
        }
        $_SESSION['loggedIn'] = true;
        $extraProcessesClass = new ExtraProcesses();
        $extraProcessesClass->onUserNew($_SESSION['username'] ?? "");
        // Finally, load again as now this file has changed and auto login
        header('Location: ../');
        echo "<script>window.location = '../';</script>";
        die('Logging you in...');
    }
    // ===================================================
    // We're likely showing the login screen at this point
    // ===================================================
} elseif ($ICEcoder['loginRequired'] && $_SESSION['loggedIn'] && "" === $ICEcoder["password"]) {
    header("Location: ../?logout");
    echo "<script>window.location = '../?logout';</script>";
    die('Logging you out...');
} else {
    // ==================================
    // Continue with whatever we're doing
    // ==================================
}
