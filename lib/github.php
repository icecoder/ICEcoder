<?php
include("headers.php");
include("settings.php");

// SSL check, as everything is over https
$wrappers = stream_get_wrappers();
$sslAvail = true;
if (!extension_loaded('openssl') || !in_array('https', $wrappers)) {
	$sslAvail = false;
	echo "<script>top.ICEcoder.message('Sorry, you don\'t appear to have OpenSSL loaded on your PHP instance, so https is not available. This is required for GitHub data transfer, please amend php.ini settings, restart your server and try again');top.ICEcoder.showHide('hide',top.get('loadingMask'));</script>";
	die();
}

// If we have an action to perform
if (!$demoMode && isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] && isset($_GET['action']) && $sslAvail) {

	// =====
	// CLONE
	// =====
	if ($_GET['action']=="clone") {

		$iceGithubLocalPaths = $ICEcoder["githubLocalPaths"];
		$iceGithubRemotePaths = $ICEcoder["githubRemotePaths"];
		$pathPos = array_search($iceRoot,$iceGithubLocalPaths);
		if ($pathPos !== false) {

			// USE: https://github.com/mattpass/ICEcoder/zipball/master
			// Store the plugin zip to the tmp dir
			$target = $docRoot.$iceGithubLocalPaths[$pathPos]."/";
			$zipURL = $iceGithubRemotePaths[$pathPos].'/zipball/master';
	    		$zipFile = "../tmp/".basename($zipURL);

			if (ini_get('allow_url_fopen')) {
				$fileData = file_get_contents($zipURL, false, $context);
			} elseif (function_exists('curl_init')) {
    				$client = curl_init($zipURL);
				curl_setopt($client, CURLOPT_SSL_VERIFYPEER, false);
		    		curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);  //fixed this line
				$fileData = curl_exec($client);
			}
			file_put_contents($zipFile, $fileData);

			// Now unpack the zip
			$zip = new ZipArchive;
			$zip->open($zipFile);

			// Create all files & dirs, in 1kb chunks
			for($i=0; $i<$zip->numFiles; $i++) {

				$name = $zip->getNameIndex($i);
				if ($i==0) {
					$dirName = $name;
				} else {
					$tgtName = str_replace($dirName,"",$name);
					// Determine output filename
					$file = $target.$tgtName;

					// Create the directories if necessary
					$dir = dirname($file);
					if (!is_dir($dir)) mkdir($dir, 0777, true);

					// Read from zip and write to disk
					$fpr = $zip->getStream($name);
					if (!is_dir($file)) {
						$fpw = fopen($file, 'w');
						while ($data = fread($fpr, 1024)) {
							fwrite($fpw, $data);
						}
						fclose($fpw);
					}
					fclose($fpr);
				}
			}
			$zip->close();

			// Remove the tmp zip file
			unlink($zipFile);

			// Refresh the file manager
			echo "<script>top.ICEcoder.refreshFileManager();</script>";

		}

	}

}
?>