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
	echo 'Copying over current ICEcoder files...<br>';
	foreach ($iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),RecursiveIteratorIterator::SELF_FIRST) as $item) {
		if (strpos($source.DIRECTORY_SEPARATOR.$iterator->getSubPathName(),"oldVersion")==false) {
			if ($item->isDir()) {
				mkdir($dest.DIRECTORY_SEPARATOR.$iterator->getSubPathName(), 0705);
			} else {
				rename($item, $dest.DIRECTORY_SEPARATOR.$iterator->getSubPathName());
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
	global $updateDone;
	
	echo 'Retrieving zip from ICEcoder site...<br>';
	$source = 'ICEcoder v'.$icvInfo;
	$target = '../';

    	$file = "../tmp/new-version.zip";
    	$client = curl_init('http://icecoder.net/ICEcoder-v'.$icvInfo.'.zip');
    	curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);  //fixed this line
	$fileData = curl_exec($client);

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