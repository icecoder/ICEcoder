<?php declare(strict_types=1);

namespace ICEcoder;

class Settings
{

    public function updateConfigCreateDate(): void
    {
        global $settingsFile, $ICEcoderUserSettings;

        $settingsContents = getData(dirname(__FILE__) . "/../data/" . $settingsFile);
        clearstatcache();
        $configfilemtime = filemtime(dirname(__FILE__) . "/../data/" . $settingsFile);
        // Make it a number (avoids null, undefined etc)
        $configfilemtime = intval($configfilemtime);
        // Set it to the epoch time now if we don't have a real value
        if (0 === $configfilemtime) {
            $configfilemtime = time();
        }
        $settingsContents = str_replace('"configCreateDate"	=> 0,', '"configCreateDate"	=> ' . $configfilemtime . ',', $settingsContents);
        // Now update the config file
        if (!$fh = fopen(dirname(__FILE__) . "/../data/" . $settingsFile, 'w')) {
            $reqsPassed = false;
            $reqsFailures = ["phpUpdateSettings"];
            include dirname(__FILE__) . "/../lib/requirements.php";
        }
        fwrite($fh, $settingsContents);
        fclose($fh);
        // Set the new value in array
        $ICEcoderUserSettings['configCreateDate'] = $configfilemtime;
    }

    public function updatePasswordCheckUpdates(): void
    {
        global $settingsFile, $password;

        $settingsContents = getData("../data/" . $settingsFile);
        // Replace our empty password with the one submitted by user
        $settingsContents = str_replace('"password"		=> "",','"password"		=> "' . $password . '",', $settingsContents);
        // Also set the update checker preference
        $checkUpdates = $_POST['checkUpdates'] == "true" ? "true" : "false";
        // once to cover the true setting, once to cover false
        $settingsContents = str_replace('"checkUpdates"		=> true,','"checkUpdates"		=> ' . $checkUpdates . ',', $settingsContents);
        $settingsContents = str_replace('"checkUpdates"		=> false,','"checkUpdates"		=> ' . $checkUpdates . ',', $settingsContents);
        // Now update the config file
        if (!$fh = fopen(dirname(__FILE__) . "/../data/" . $settingsFile, 'w')) {
            $reqsPassed = false;
            $reqsFailures = ["phpUpdateSettings"];
            include(dirname(__FILE__) . "/../lib/requirements.php");
        }
        fwrite($fh, $settingsContents);
        fclose($fh);
    }

    public function createIPSettingsFileIfNotExist(): void
    {
        global $username, $settingsFile;

        // Create a duplicate version for the IP address of the domain if it doesn't exist yet
        $serverAddr = $_SERVER['SERVER_ADDR'] ?? "1";
        if ($serverAddr == "1" || $serverAddr == "::1") {
            $serverAddr = "127.0.0.1";
        }
        $settingsFileAddr = 'config-' . $username . str_replace(".", "_", $serverAddr) . '.php';
        if (true === file_exists(dirname(__FILE__) . "/../data/" . $settingsFileAddr)) {
            if (false === copy(dirname(__FILE__) . "/../data/" . $settingsFile, dirname(__FILE__) . "/../data/" . $settingsFileAddr)) {
                $reqsPassed = false;
                $reqsFailures = ["phpCreateSettingsFileAddr"];
                include dirname(__FILE__) . "/../lib/requirements.php";
            }
        }
    }

    public function disableFurtherRegistration(): void
    {
        global $configSettings;

        // Disable the enableRegistration config setting if the user had that option chosen
        if (true === isset($_POST['disableFurtherRegistration'])) {
            $updatedConfigSettingsFile = getData(dirname(__FILE__) . "/../data/" . $configSettings);
            if ($fUConfigSettings = fopen(dirname(__FILE__) . "/../data/" . $configSettings, 'w')) {
                $updatedConfigSettingsFile = str_replace('"enableRegistration"	=> true','"enableRegistration"	=> false', $updatedConfigSettingsFile);
                fwrite($fUConfigSettings, $updatedConfigSettingsFile);
                fclose($fUConfigSettings);
            } else {
                $reqsPassed = false;
                $reqsFailures = ["phpUpdateConfig"];
                include dirname(__FILE__)."/../lib/requirements.php";
            }
        }
    }
}
