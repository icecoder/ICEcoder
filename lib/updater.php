<?php include("settings.php");?>
<!DOCTYPE html>
<head>
<title>Updating ICEcoder...</title>
</head>

<body style="background: #141414; color: #fff; font-size: 10px; font-family: arial, helvetica, swiss, verdana">
<?php
define('PATH', '../tmp/oldVersion/');
$updateDone = false;

function startUpdate() {
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
	// Set a stream context timeout for file reading
	$context = stream_context_create(array('http'=>
		array(
			'timeout' => 60 // secs
		)
	));
	echo 'Moving current ICEcoder files...<br>';
	foreach ($iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),RecursiveIteratorIterator::SELF_FIRST) as $item) {
		if (strpos($source.DIRECTORY_SEPARATOR.$iterator->getSubPathName(),"oldVersion")==false) {
			// Don't move plugins away
			$testPath = $source.DIRECTORY_SEPARATOR.$iterator->getSubPathName();
			$testPath = str_replace("\\","/",$testPath);
			if (strpos($testPath,"/plugins")==false) {
				if ($item->isDir()) {
					mkdir($dest.DIRECTORY_SEPARATOR.$iterator->getSubPathName(), 0705);
				} else {
					rename($item, $dest.DIRECTORY_SEPARATOR.$iterator->getSubPathName());
				}
			}
		}
	}
	$icv_url = "http://icecoder.net/latest-version.txt";
	echo 'Detecting current version of ICEcoder...<br>';
	if (ini_get('allow_url_fopen')) {
		$icvInfo = file_get_contents($icv_url,false,$context);
	} elseif (function_exists('curl_init')) {
		$ch = curl_init($icv_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$icvInfo = curl_exec($ch);
	} else {
		die('Sorry, couldn\'t figure out latest version.');
	}
	echo 'Latest version of ICEcoder is '.$icvInfo.'<br>';
	openZipNew($icvInfo);
}

function openZipNew($icvInfo) {
	echo 'Retrieving zip from ICEcoder site...<br>';
	$source = 'ICEcoder v'.$icvInfo;
	$target = '../';

	$remoteFile = 'http://icecoder.net/ICEcoder-v'.(str_replace(" beta", "-beta",$icvInfo)).'.zip';
    	$file = "../tmp/new-version.zip";
	if (ini_get('allow_url_fopen')) {
		$fileData = file_get_contents($remoteFile,false,$context);
	} elseif (function_exists('curl_init')) {
	    	$client = curl_init($remoteFile);
    		curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);  //fixed this line
		$fileData = curl_exec($client);
	} else {
		die('Sorry, couldn\'t get latest version zip file.');
	}
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

function copyOverSettings($icvInfo) {
	global $updateDone;

	echo 'Getting old and new settings...<br>';
	// Get old and new settings and start a new $contents
	$oldSettingsContent = file_get_contents(PATH."lib/config___settings.php",false,$context);
	$oldSettingsArray = explode("\n",$oldSettingsContent);
	$newSettingsContent = file_get_contents("config___settings.php",false,$context);
	$newSettingsArray = explode("\n",$newSettingsContent);
	$contents = "";

	echo 'Transposing old settings to new settings...<br>';
	// Now need to copy the old settings over to new settings...
	for ($i=0; $i<count($newSettingsArray); $i++) {
		$thisKey = explode('"',$newSettingsArray[$i]);
		$thisKey = $thisKey[1];
		// We set the new line to begin with
		$contentLine = $newSettingsArray[$i].PHP_EOL;
		for ($j=0; $j<count($oldSettingsArray); $j++) {
			// And override with old setting if not versionNo or codeMirrorDir and we have a match
			if ($thisKey != "versionNo" && $thisKey != "codeMirrorDir" && strpos($oldSettingsArray[$j],'"'.$thisKey.'"') > -1) {
				$contentLine = $oldSettingsArray[$j].PHP_EOL;
			}
		}
		$contents .= $contentLine;
	}
	echo 'Saving old settings to new ICEcoder settings file...<br>';
	$fh = fopen('config___settings.php', 'w') or die("Sorry, cannot update the new ICEcoder settings file");
	fwrite($fh, $contents);
	fclose($fh);

	echo 'All update tasks completed...<br>';
	$updateDone = true;
}

startUpdate();
if ($updateDone) {
	echo 'Updated successfully!<br><br>';
	echo 'Restarting ICEcoder...';
	echo '<script>alert("Update appears to be successful");window.location = "../";</script>';
} else {
	echo 'Something appears to have gone wrong :-/<br><br>';
	echo 'Please report bugs at <a href="http://github.com/mattpass/ICEcoder" style="color: #fff">http://github.com/mattpass/ICEcoder</a><br><br>';
	echo 'You can recover the old version from ICEcoder\'s tmp dir';
}
?>
</body>

</html>