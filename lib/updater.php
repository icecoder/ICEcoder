<?php
die("Updater system unused till a future point in time");

include("headers.php");
include("settings.php");
$t = $text['updater'];
?>
<!DOCTYPE html>
<head>
<title>Updating ICEcoder...</title>
</head>

<body style="background: #181817; color: #fff; font-size: 10px; font-family: arial, helvetica, swiss, verdana">
<?php
define('PATH', '../tmp/oldVersion/');
$updateDone = false;

function startUpdate() {
	// First, check old version is entirely moveable
	$source = "../";
	$cantMoveArray = array();
	echo 'Checking we can entirely move old ICEcoder version...<br>';
	foreach ($iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),RecursiveIteratorIterator::SELF_FIRST) as $item) {
		if (strpos($source.DIRECTORY_SEPARATOR.$iterator->getSubPathName(),"oldVersion")==false) {
			// Don't move backups, plugins or .git away
			$testPath = $source.DIRECTORY_SEPARATOR.$iterator->getSubPathName();
			$testPath = str_replace("\\","/",$testPath);
			if (strpos($testPath,"/data/backups/")==false && strpos($testPath,"/plugins/")==false && strpos($testPath,"/.git/")==false) {
				if (!is_writeable($item)) {
					array_push($cantMoveArray,substr($item,count($source)+2));
				}
			}
		}
	}
	if (count($cantMoveArray) > 0) {
		echo '<br>Sorry, there are dirs/files that cannot be moved. Please set write permissions on them so ICEcoder may move the old version, to make way for the new.<br><br>You can reload this page after making perms changes to check the list again.<br><br>';
		for ($i=0; $i<count($cantMoveArray); $i++) {
			echo $cantMoveArray[$i]."<br>";
		}
		die('<br><a href="'.$source.'" style="color: #fff">&lt;&lt; Back to ICEcoder</a>');
	}
	renameOldVersion();
}

function renameOldVersion() {
	if (is_dir(PATH)) {
		echo 'Postfixing oldVersion dir with a timestamp...<br>';
		rename(PATH,trim(PATH,"/")."-".time());
	}
	copyOldVersion();
}

function copyOldVersion() {
	if (!is_dir(PATH)) {
		echo 'Creating new oldVersion dir...<br>';
		mkdir(PATH);
	}
	$source = "../";
	$dest = PATH;

	echo 'Moving current ICEcoder files...<br>';
	foreach ($iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),RecursiveIteratorIterator::SELF_FIRST) as $item) {
		if (strpos($source.DIRECTORY_SEPARATOR.$iterator->getSubPathName(),"oldVersion")==false) {
			// Don't move backups, plugins or .git away
			$testPath = $source.DIRECTORY_SEPARATOR.$iterator->getSubPathName();
			$testPath = str_replace("\\","/",$testPath);
			if (strpos($testPath,"/data/backups/")==false && strpos($testPath,"/plugins/")==false && strpos($testPath,"/.git/")==false) {
				if ($item->isDir()) {
					mkdir($dest.DIRECTORY_SEPARATOR.$iterator->getSubPathName(), 0755);
				} else {
					rename($item, $dest.DIRECTORY_SEPARATOR.$iterator->getSubPathName());
				}
			}
		}
	}
	$icvURL = "https://icecoder.net/latest-version.txt";
	echo 'Detecting current version of ICEcoder...<br>';
	$icvInfo = getData($icvURL,'curl','Sorry, couldn\'t figure out latest version.');
	echo 'Latest version of ICEcoder is '.$icvInfo.'<br>';
	openZipNew($icvInfo);
}

function openZipNew($icvInfo) {
	echo 'Retrieving zip from ICEcoder site...<br>';
	$source = 'ICEcoder '.$icvInfo;
	$target = '../';

	$remoteFile = 'https://icecoder.net/ICEcoder-'.(str_replace(" beta", "-beta",$icvInfo)).'.zip';
    	$file = "../tmp/new-version.zip";
	$fileData = getData($remoteFile,'curl','Sorry, couldn\'t get latest version zip file.');
	echo 'Storing zip file...<br>';
	file_put_contents($file, $fileData);

	$zip = new ZipArchive;
	$zip->open($file);

	echo 'Copying over zip dirs & files...<br>';
	for($i=0; $i<$zip->numFiles; $i++) {
		$name = $zip->getNameIndex($i);

		// Skip files not in $source
		if (strpos($name, "{$source}/") !== 0) continue;

		// Determine output filename (removing the $source prefix and trimming traiing slashes)
		$file = $target.substr($name, strlen($source)+1);

		// Create the directories if necessary
		$dir = dirname($file);
		if (!is_dir($dir)) mkdir($dir, 0777, true);

		// Read from Zip and write to disk
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
	echo 'Finished copying over zip dirs & files...<br>';
	copyOverSettings($icvInfo);
}

function transposeSettings($oldFile,$newFile,$saveFile) {
	echo '- Getting old and new settings...<br>';
	// Get old and new settings and start a new $contents
	$oldSettingsContent = getData($oldFile);
	$oldSettingsArray = explode("\n",$oldSettingsContent);
	$newSettingsContent = getData($newFile);
	$newSettingsArray = explode("\n",$newSettingsContent);
	$contents = "";

	echo '- Transposing settings...<br>';
	// Now need to copy the old settings over to new settings...
	for ($i=0; $i<count($newSettingsArray); $i++) {
		$thisKey = "";
		if (strpos($newSettingsArray[$i],'"') > -1) {
			$thisKey = explode('"',$newSettingsArray[$i]);
		}
		if (is_array($thisKey)) {
			$thisKey = $thisKey[1];
		}
		// We set the new line to begin with
		$contentLine = $newSettingsArray[$i].PHP_EOL;
		for ($j=0; $j<count($oldSettingsArray); $j++) {
			// And override with old setting if not blank, not in excluded array and we have a match
			if ($thisKey != "" && $thisKey != "versionNo" && strpos($oldSettingsArray[$j],'"'.$thisKey.'"') > -1) {
				$contentLine = $oldSettingsArray[$j].PHP_EOL;
				// If the old setting we're copying over isn't replacing the last line and doesn't end in a comma (after an rtrim to remove line endings), and doesn't contain a comment, add one
				if ($i != count($newSettingsArray)-1 && substr(rtrim($contentLine),-1) != "," && strpos($contentLine,"//") == -1) {
					$contentLine = str_replace(PHP_EOL,",".PHP_EOL,$contentLine);
				}
			}
		}
		$contents .= $contentLine;
	}
	echo '- Saving old settings to new settings file...<br>';
	$fh = fopen($saveFile, 'w') or die("Sorry, cannot update ".$saveFile);
	fwrite($fh, $contents);
	fclose($fh);
}

function copyOverSettings($icvInfo) {
	global $updateDone, $configSettings;

	// System settings
	echo 'Transposing system settings...<br>';
	// Create a new config file if it doesn't exist yet.
	// The reason we create it, is so it has PHP write permissions, meaning we can update it later
	if (!file_exists(dirname(__FILE__)."/../data/".$configSettings)) {
		echo 'Creating new settings file...<br>';
		// TODO: Needs overhauling as newConfigSettingsFile no longer exists
		// Include our params to make use of (as $newConfigSettingsFile)
		include(dirname(__FILE__)."/settings-system-params.php");
		if ($fConfigSettings = fopen(dirname(__FILE__)."/../data/".$configSettings, 'w')) {
			fwrite($fConfigSettings, $newConfigSettingsFile);
			fclose($fConfigSettings);
		} else {
			die("Cannot update config file data/".$configSettings.". Please check write permissions on data/ and try again");
		}
	}
	transposeSettings(PATH."data/template-config-global.php","config-global.php","config-global.php");

	// Users template settings
	echo 'Transposing users template settings...<br>';
	transposeSettings(PATH."data/template-config-users.php","template-config-users.php","template-config-users.php");

	// Users settings files
	$fileList = scanDir(PATH."data/");
	for ($i=0; $i<count($fileList); $i++) {
		if (strpos($fileList[$i],"config-") > -1) {
			echo 'Transposing users settings file '.$fileList[$i].'...<br>';
			transposeSettings(PATH."data/".$fileList[$i],"template-config-users.php",$fileList[$i]);
		}
	}

	echo 'All update tasks completed...<br>';
	$updateDone = true;
}

startUpdate();
if ($updateDone) {
	echo 'Updated successfully!<br><br>';
	echo 'Restarting ICEcoder...';
	echo '<script>alert("'.$t['Update appears to...'].'");window.location = "../?display=updated&csrf='.$_SESSION["csrf"].'";</script>';
} else {
	echo 'Something appears to have gone wrong :-/<br><br>';
	echo 'Please report bugs at <a href=\"https://github.com/icecoder/ICEcoder\" style=\"color: #fff\">https://github.com/icecoder/ICEcoder</a><br><br>';
	echo 'You can recover the old version from ICEcoder\'s tmp dir';
}
?>
</body>

</html>
