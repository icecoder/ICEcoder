<?php
require_once dirname(__FILE__) . "/../classes/_ExtraProcesses.php";
require_once dirname(__FILE__) . "/../classes/Settings.php";
require_once dirname(__FILE__) . "/../classes/System.php";

use ICEcoder\ExtraProcesses;

$settingsClass = new \ICEcoder\Settings();
$systemClass = new \ICEcoder\System();

// Check data dir exists, is readable and writable
if (false === $settingsClass->getDataDirDetails()['exists']) {
    $reqsFailures = ["phpDataDirDoesntExist"];
    include dirname(__FILE__) . "/requirements.php";
}

if (false === $settingsClass->getDataDirDetails()['readable']) {
    $reqsFailures = ["phpDataDirNotReadable"];
    include dirname(__FILE__) . "/requirements.php";
}

if (false === $ICEcoder["demoMode"] && false === $settingsClass->getDataDirDetails()['writable']) {
    $reqsFailures = ["phpDataDirNotWritable"];
    include dirname(__FILE__) . "/requirements.php";
}

// Create a new global config file if it doesn't exist yet.
// The reason we create it, is so it has PHP write permissions, meaning we can update it later
if (false === $settingsClass->getConfigGlobalFileDetails()['exists']) {
    if (false === $settingsClass->setConfigGlobalSettings($settingsClass->getConfigGlobalTemplate(false))) {
        $reqsFailures = ["phpGlobalConfigFileCreate"];
        include dirname(__FILE__) . "/requirements.php";
    }
}

// Check global config settings file exists
if (false === $settingsClass->getConfigGlobalFileDetails()['exists']) {
    $reqsFailures = ["phpGlobalConfigFileExists"];
    include dirname(__FILE__) . "/requirements.php";
}

// Check we can read global config settings file
if (false === $settingsClass->getConfigGlobalFileDetails()['readable']) {
    $reqsFailures = ["phpGlobalConfigReadFile"];
    include dirname(__FILE__) . "/requirements.php";
}

// Check we can write global config settings file
if (false === $ICEcoder["demoMode"] && false === $settingsClass->getConfigGlobalFileDetails()['writable']) {
    $reqsFailures = ["phpGlobalConfigWriteFile"];
    include dirname(__FILE__) . "/requirements.php";
}

// Load global config settings
$ICEcoderSettings = $settingsClass->getConfigGlobalSettings();

// Load common functions
include_once dirname(__FILE__) . "/settings-common.php";

$postUsername = true === isset($_POST['username']) && is_string($_POST['username'])
    ? preg_replace("/[^\w_\-]/", "", $_POST['username'])
    : "";

// Establish user settings file
$username = "admin-";
if ("" !== $postUsername) {$username = $postUsername . "-";};
if (true === isset($_SESSION['username']) && "" !== $_SESSION['username']) {$username = $_SESSION['username'] . "-";};
$settingsFile = 'config-' . $username . str_replace(".", "_", str_replace("www.", "", $_SERVER['SERVER_NAME'])) . '.php';

// Login is default
$setPWorLogin = "login";

// Create user settings file if it doesn't exist
if (true === $ICEcoderSettings['enableRegistration'] && false === $settingsClass->getConfigUsersFileDetails($settingsFile)['exists']) {
    if (false === $settingsClass->setConfigUsersSettings($settingsFile, $settingsClass->getConfigUsersTemplate(false))) {
        $reqsFailures = ["phpUsersConfigCreateConfig"];
        include dirname(__FILE__) . "/requirements.php";
    }
    // Initial setup,triggered from index,php...
    if ("index.php" === basename($_SERVER['SCRIPT_NAME'])) {
        // Set bug reporting for ICEcoders error.log file
        $settingsClass->updateConfigUsersSettings($settingsFile, ["bugFilePaths" => [dirname($_SERVER['SCRIPT_NAME']) . "/data/logs/error/error.log"]]);
        $settingsClass->updateConfigUsersSettings($settingsFile, ["bugFileCheckTimer" => 10]);
        $settingsClass->updateConfigUsersSettings($settingsFile, ["bugFileMaxLines" => 10]);
    }
    $setPWorLogin = "set password";
}

// Check users config settings file exists
if (false === $settingsClass->getConfigUsersFileDetails($settingsFile)['exists']) {
    // If on the login page and we couldn't find the file, boot back to login page
    if ("login.php" === basename($_SERVER['SCRIPT_NAME'])) {
        header('Location: login.php');
        echo "<script>window.location = 'login.php';</script>";
        die('Redirecting to login...');
    }
    $reqsFailures = ["phpUsersConfigFileExists"];
    include dirname(__FILE__) . "/requirements.php";
}

// Check we can read users config settings file
if (false === $settingsClass->getConfigUsersFileDetails($settingsFile)['readable']) {
    $reqsFailures = ["phpUsersConfigReadFile"];
    include dirname(__FILE__) . "/requirements.php";
}

// Check we can write users config settings file
if (false === $ICEcoder["demoMode"] && false === $settingsClass->getConfigUsersFileDetails($settingsFile)['writable']) {
    $reqsFailures = ["phpUsersConfigWriteFile"];
    include dirname(__FILE__) . "/requirements.php";
}

// Load users config settings
$ICEcoderUserSettings = $settingsClass->getConfigUsersSettings($settingsFile);

// Remove any previous files that are no longer there
for ($i = 0; $i < count($ICEcoderUserSettings['previousFiles']); $i++) {
    if (false === file_exists(str_replace("|", "/", $ICEcoderUserSettings['previousFiles'][$i]))) {
        array_splice($ICEcoderUserSettings['previousFiles'], $i, 1);
    }
}

// Replace our config created date with the filemtime?
if ("index.php" === basename($_SERVER['SCRIPT_NAME']) && 0 === $ICEcoderUserSettings['configCreateDate']) {
    $settingsClass->updateConfigUsersCreateDate($settingsFile);
}

// On mismatch of settings file to system, rename to .old and reload
If ($ICEcoderUserSettings["versionNo"] !== $ICEcoderSettings["versionNo"]) {
    $reqsFailures = ["phpUsersConfigVersionMismatch"];
    include dirname(__FILE__) . "/requirements.php";
}

// Set ICEcoder settings array to (global + user) template and layer ontop (global + user) from current settings
$ICEcoder = array_merge(
    $settingsClass->getConfigGlobalTemplate(true),
    $settingsClass->getConfigUsersTemplate(true),
    $ICEcoderSettings,
    $ICEcoderUserSettings
);

// Include language file
// Load base first as foundation
include dirname(__FILE__) . "/../lang/" . basename($ICEcoder['languageBase']);
$baseText = $text;

// Load chosen language ontop to replace base
include dirname(__FILE__) . "/../lang/" . basename($ICEcoder['languageUser']);
$text = array_replace_recursive($baseText, $text);
$_SESSION['text'] = $text;

// Login not required, log us straight in
if (false === $ICEcoder['loginRequired']) {
    $_SESSION['loggedIn'] = true;
};
$demoMode = $ICEcoder['demoMode'];

// Update global config and users config files?
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
            $_SESSION['username'] = $postUsername;
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

// Define the serverType, docRoot & iceRoot
$serverType = $systemClass->getOS();
$docRoot = rtrim(str_replace("\\", "/", $ICEcoder['docRoot']));
$iceRoot = rtrim(str_replace("\\", "/", $ICEcoder["root"]));

// Establish the dir ICEcoders running from
$ICEcoderDirFullPath = rtrim(str_replace("\\", "/", dirname($_SERVER['SCRIPT_FILENAME'])), "/lib");
$rootPrefix = '/' . str_replace("/", "\/", preg_quote(str_replace("\\", "/", $docRoot))) . '/';
$ICEcoderDir = preg_replace($rootPrefix, '', $ICEcoderDirFullPath, 1);

// Setup our file security vars
$settingsArray = ["findFilesExclude", "bannedFiles", "allowedIPs"];
for ($i = 0; $i < count($settingsArray); $i++) {
    if (false === isset($_SESSION[$settingsArray[$i]])) {
        $_SESSION[$settingsArray[$i]] = $ICEcoder[$settingsArray[$i]];
    }
}

// Check IP permissions
if (false === in_array(getUserIP(), $_SESSION['allowedIPs']) && false === in_array("*", $_SESSION['allowedIPs'])) {
    header('Location: /');
    $reqsFailures = ["systemIPRestriction"];
    include(dirname(__FILE__) . "/requirements.php");
};

// Save currently opened files in previousFiles and last10Files arrays
include(dirname(__FILE__) . "/settings-save-current-files.php");

// Display the plugins
include(dirname(__FILE__) . "/plugins-display.php");

// If we require a login, loggedIn is false or we don't have a password set yet and we're not on login screen, boot user to that
if (true === $ICEcoder['loginRequired'] && false === isset($_POST['password']) && (!$_SESSION['loggedIn'] || "" === $ICEcoder["password"]) && false === strpos($_SERVER['SCRIPT_NAME'], "lib/login.php")) {
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
    if ("" === $ICEcoder["password"] && true === isset($_POST['submit']) && -1 < strpos($_POST['submit'], "set password")) {
        $password = generateHash($_POST['password']);
        $settingsClass->updateConfigUsersSettings($settingsFile, ["password" => $password, "checkUpdates" => isset($_POST["checkUpdates"])]);
        $settingsClass->createIPSettingsFileIfNotExist();
        if (true === isset($_POST['disableFurtherRegistration'])) {
            $settingsClass->updateConfigGlobalSettings(['enableRegistration' => false]);
        }
        // Set the session user level
        if ($ICEcoder["multiUser"]) {
            $_SESSION['username'] = $postUsername;
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
