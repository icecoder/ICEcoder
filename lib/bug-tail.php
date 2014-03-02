<?php
include_once("../lib/settings.php");

//temporary entry of ICEcoder config values
$ICEcoder["bugFilePaths"] = "/var/log/apache2/access.log,/var/log/apache2/error.log";
$ICEcoder["bugFileSizes"] = "133663,1389153";
$ICEcoder["bugFileMaxLines"] = "10";

function getBugs() {
	tailFiles();
	return(getTails());
}

function bugCheck() {
	return(tailFiles());
}

function tailFiles() {
	global $ICEcoder;
	$files = explode(",", $ICEcoder["bugFilePaths"]);
	$sizes = explode(",", $ICEcoder["bugFileSizes"]);
	$new_bugs = false;
	for ($i=0; $i<count($files); $i++) {
		$file = $files[$i];
		$size = $sizes[$i];
		clearstatcache();
		$currentSize = filesize($file);
		if ($currentSize > $size) {
			$new_bugs = true;
			$content = "/n" . $file . "/n" . file_get_contents($file, NULL, NULL, $size);
			file_put_contents($ICEcoder["docRoot"] . "/icecoder/tmp/log.log", $content, FILE_APPEND);
			$sizes[$i] = $currentSize;
		}
	}
	saveFileSizes(implode(",", $sizes));
	return($new_bugs);
}

function saveFileSizes($sizes) {
	//need to save the FileSizes back to the config file
}

function getTails() {
	global $ICEcoder;
	//Lorenzo Stanco's tail-by-row-number solution
	//https://gist.github.com/lorenzos/1711e81a9162320fde20
	$filepath = $ICEcoder["docRoot"] . "/icecoder/tmp/log.log";
	$lines = $ICEcoder["bugFileMaxLines"];
	$adaptive = true;
	// Open file
	$f = @fopen($filepath, "rb");
	if ($f === false) return false;
	// Sets buffer size
	if (!$adaptive) $buffer = 4096;
	else $buffer = ($lines < 2 ? 64 : ($lines < 10 ? 512 : 4096));
	// Jump to last character
	fseek($f, -1, SEEK_END);
	// Read it and adjust line number if necessary
	// (Otherwise the result would be wrong if file doesn't end with a blank line)
	if (fread($f, 1) != "\n") $lines -= 1;
	// Start reading
	$output = '';
	$chunk = '';
	// While we would like more
	while (ftell($f) > 0 && $lines >= 0) {
		// Figure out how far back we should jump
		$seek = min(ftell($f), $buffer);
		// Do the jump (backwards, relative to where we are)
		fseek($f, -$seek, SEEK_CUR);
		// Read a chunk and prepend it to our output
		$output = ($chunk = fread($f, $seek)) . $output;
		// Jump back to where we started reading
		fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);
		// Decrease our line counter
		$lines -= substr_count($chunk, "\n");
	}
	// While we have too many lines
	// (Because of buffer size we might have read too many)
	while ($lines++ < 0) {
		// Find first newline and remove all text before that
		$output = substr($output, strpos($output, "\n") + 1);
	}
	// Close file and return
	fclose($f);
	return trim($output);
}
?>