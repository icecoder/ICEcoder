<?php declare(strict_types=1);

namespace ICEcoder;

class Settings
{
    public function __construct()
    {
        // Set version number and document root as core settings
        $this->versionNo = "7.0";
        $this->docRoot = $_SERVER['DOCUMENT_ROOT'];
    }

    public function getConfigGlobalFileDetails()
    {
        // Return details about the global config file
        $fileName = 'config-global.php';
        $fullPath = dirname(__FILE__) . "/../data/" . $fileName;
        $exists = file_exists($fullPath);
        $readable = is_readable($fullPath);
        $writable = is_writable($fullPath);
        return [
            "fileName" => $fileName,
            "fullPath" => $fullPath,
            "exists" => $exists,
            "readable" => $readable,
            "writable" => $writable,
        ];
    }

    public function getConfigGlobalTemplate()
    {
        // Return the serialized global config template
        $fileName = 'template-config-global.php';
        $fullPath = dirname(__FILE__) . "/../lib/" . $fileName;
        if (function_exists('opcache_invalidate')) {
            opcache_invalidate($fullPath, true);
        }
        $settings = file_get_contents($fullPath);
        return $settings;
    }

    public function getConfigGlobalSettings()
    {
        // Start an array with version number and document root
        $settings = [];
        $settings['versionNo'] = $this->versionNo;
        $settings['docRoot'] = $this->docRoot;
        // Get global config file details
        $fullPath = $this->getConfigGlobalFileDetails()['fullPath'];
        // Load serialized data from the global config and convert to an array
        if (function_exists('opcache_invalidate')) {
            opcache_invalidate($fullPath, true);
        }
        $settingsFromFile = file_get_contents($fullPath);
        $settingsFromFile = str_replace("<?php\n/*\n\n", "", $settingsFromFile);
        $settingsFromFile = str_replace("\n\n*/\n?>", "", $settingsFromFile);
        $settingsFromFile = unserialize($settingsFromFile);
        // Merge that with the array we started with and return
        $settings = array_merge($settings, $settingsFromFile);
        return $settings;
    }

    public function setConfigGlobalSettings($settings)
    {
        // Get the global config file details
        $fullPath = $this->getConfigGlobalFileDetails()['fullPath'];
        if ($fConfigSettings = fopen($fullPath, 'w')) {
            // If the settings we've received aren't in serialized format yet, do that now
            // As $settings could be a serialized string or array
            if (is_array($settings)) {
                unset($settings['versionNo']);
                unset($settings['docRoot']);
                $settings = "<?php\n/*\n\n" . serialize($settings) . "\n\n*/\n?" . ">";
            }
            // Now we have a serialized string, save it in the global config file
            fwrite($fConfigSettings, $settings);
            fclose($fConfigSettings);
            return true;
        } else {
            return false;
        }
    }

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
        // Disable the enableRegistration config setting if the user had that option chosen
        if (true === isset($_POST['disableFurtherRegistration'])) {
            // Update global config settings file
            $ICEcoderSettingsFromFile = $this->getConfigGlobalSettings();
            $ICEcoderSettingsFromFile['enableRegistration'] = false;
            $this->setConfigGlobalSettings($ICEcoderSettingsFromFile);
        }
    }
}
