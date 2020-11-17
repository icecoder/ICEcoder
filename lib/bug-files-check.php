<?php
// Load common functions
include "headers.php";
include "settings.php";
$t = $text['bug-files-check'];

// Classes
require_once dirname(__FILE__) . "/../classes/_ExtraProcesses.php";
require_once dirname(__FILE__) . "/../classes/System.php";
use ICEcoder\ExtraProcesses;
use ICEcoder\System;

$systemClass = new System;

$files	= explode(",", str_replace("|", "/", xssClean($_GET['files'], "html")));
$filesSizesSeen	= explode(",", xssClean($_GET['filesSizesSeen'], "html"));
$maxLines = xssClean($_GET['maxLines'], "html");

$result = "ok";

for ($i = 0; $i < count($files); $i++) {
	// Work out the real path for a file
	$files[$i] = realpath($_SERVER['DOCUMENT_ROOT'] . $files[$i]);
	// If we can't find that file or it doesn't start with the doc root, it's an error
	if (!file_exists($files[$i]) || strpos(str_replace("\\", "/", $files[$i]), $_SERVER['DOCUMENT_ROOT']) !== 0) {
		$result = "error";
	} else {
		$filesSizesSeen[$i] = filesize($files[$i]);
	}
}

if ("error" !== $result) {

	$filesWithNewBugs = 0;
    $output = "";

	for ($i = 0; $i < count($files); $i++) {
		// If we have set a filesize value previously and it's different to now, there's new bugs
		$fileSizesSeenArray = explode(",", xssClean($_GET['filesSizesSeen'], "html"));
		if ($fileSizesSeenArray[$i] != "null" && $fileSizesSeenArray[$i] != $filesSizesSeen[$i]) {
			$result = "bugs";
			$filesWithNewBugs++;

			$filename = $files[$i];
			$chars = ($filesSizesSeen[$i] - $fileSizesSeenArray[$i]);
			$buffer = 4096;
			$lines = $maxLines + 1 + 1; // 1 (possibly) for end of file and 1 for partial lines

			// Open the file
			$systemClass->invalidateOPCache($filename);
			$f = fopen($filename, "rb");

			// Jump to last character
			fseek($f, 0, SEEK_END);

			// If we don't have a line at end, deduct 1 from $lines to get
			if("\n" !== fread($f, 1)) $lines -= 1;

			// Start reading
			$chunk = "";

			// While we would like more
			while(0 < ftell($f) && 0 < $chars && 0 < $lines) {

				// Figure out how far back we should jump
				$seek = min($chars, $buffer);

				// Do the jump (backwards, relative to where we are)
				fseek($f, -$seek, SEEK_CUR);

				// Read a chunk and prepend it to our output
				$output = ($chunk = fread($f, $seek)) . $output;

				// Jump back to where we started reading
				fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);

				// Take this seek chunk off the number of chars
				$chars -= $seek;

				// Deduct new lines found in this chunk from $lines
				$lines -= substr_count($chunk, "\n");
			}

			// Close file
			fclose($f);

			// OK, now we have bug lines to output, concat onto output
			$output = rtrim(str_replace("\r\n", "\n", $output));
			$output = explode("\n", $output);
			$output = array_slice($output, -$maxLines);
			$output = "\n" . $t['Found in'] . " " . $filename . "...\n" . implode("\n", $output);
		}

	}

	// Save all output to the bug report file
    if (0 < $filesWithNewBugs) {
        $settingsClass->serializedFileData("set", $docRoot . $ICEcoderDir . "/data/bug-report.php", serialize($output));
    }
}

// Get dir name tmp dir's parent
$dataLoc = dirname(__FILE__);
$dataLoc = explode(DIRECTORY_SEPARATOR, $dataLoc);
$dataLoc = $dataLoc[count($dataLoc) - 2];

// Output result and status array
$status = array(
	"files" => $files,
	"filesSizesSeen" => $filesSizesSeen,
	"maxLines" => $maxLines,
	"bugReportPath" => $dataLoc . "|data|bug-report.php",
	"result" => $result
);

// Include our process once our bug checking work is done
$extraProcessesClass = new ExtraProcesses();
$doNext = $extraProcessesClass->onBugCheckResult($result, $status);

// Finally, display our status in JSON format as the XHR response text
echo json_encode($status);

