<?php
// Load common functions
include("headers.php");
include_once("settings-common.php");
$text = $_SESSION['text'];
$t = $text['bug-files-check'];

$files		= explode(",",str_replace("|","/",$_GET['files']));
$filesSizesSeen	= explode(",",$_GET['filesSizesSeen']);
$maxLines	= $_GET['maxLines'];

$result = "ok";

for ($i=0; $i<count($files); $i++) {
	// Work out the real path for a file
	$files[$i] = realpath($_SERVER['DOCUMENT_ROOT'].$files[$i]);
	// If we can't find that file or it doesn't start with the doc root, it's an error
	if (!file_exists($files[$i]) || strpos(str_replace("\\","/",$files[$i]),$_SERVER['DOCUMENT_ROOT']) !== 0) {
		$result = "error";
	} else {
		$filesSizesSeen[$i] = filesize($files[$i]);
	}
}

if ($result != "error") {

	$filesWithNewBugs = 0;

	for ($i=0; $i<count($files); $i++) {
		// If we have set a filesize value previously and it's different to now, there's new bugs
		$fileSizesSeenArray = explode(",",$_GET['filesSizesSeen']);
		if ($fileSizesSeenArray[$i]!="null" && $fileSizesSeenArray[$i] != $filesSizesSeen[$i]) {
			$result = "bugs";
			$filesWithNewBugs++;

			$filename = $files[$i];
			$chars = ($filesSizesSeen[$i]-$fileSizesSeenArray[$i]);
			$buffer = 4096;
			$lines = $maxLines+1+1; // 1 (possibly) for end of file and 1 for partial lines

			// Open the file
			$f = fopen($filename, "rb");

			// Jump to last character
			fseek($f, 0, SEEK_END);

			// If we don't have a line at end, deduct 1 from $lines to get
			if(fread($f, 1) != "\n") $lines -= 1;

			// Start reading
			$output = "";
			$chunk = "";

			// While we would like more
			while(ftell($f) > 0 && $chars > 0 && $lines > 0) {

				// Figure out how far back we should jump
				$seek = min($chars, $buffer);

				// Do the jump (backwards, relative to where we are)
				fseek($f, -$seek, SEEK_CUR);

				// Read a chunk and prepend it to our output
				$output = ($chunk = fread($f, $seek)).$output;

				// Jump back to where we started reading
				fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);

				// Take this seek chunk off the number of chars
				$chars -= $seek;

				// Deduct new lines found in this chunk from $lines
				$lines -= substr_count($chunk, "\n");	
			}

			// Close file
			fclose($f); 

			// OK, now we have bug lines to output, save to our file
			$output = rtrim(str_replace("\r\n","\n",$output));
			$output = explode("\n",$output);
			$output = array_slice($output, -$maxLines);
			$output = $t['Found in']." ".$filename."...\n".implode("\n",$output);

			if ($filesWithNewBugs==1) {
				file_put_contents("../tmp/bug-report.log", $output);
			} else {
				file_put_contents("../tmp/bug-report.log", "\n\n".$output, FILE_APPEND);
			}
		}

	}
}

// Get dir name tmp dir's parent
$tmpLoc = dirname(__FILE__);
$tmpLoc = explode(DIRECTORY_SEPARATOR,$tmpLoc);
$tmpLoc = $tmpLoc[count($tmpLoc)-2];

// Output result and status array
$status = array(
	"files" => $files,
	"filesSizesSeen" => $filesSizesSeen,
	"maxLines" => $maxLines,
	"bugReportPath" => "|".$tmpLoc."|tmp|bug-report.log",
	"result" => $result
);

// Include our process once our bug checking work is done
include("../processes/on-bug-check.php");

// Finally, display our status in JSON format as the XHR response text
echo json_encode($status);

?>