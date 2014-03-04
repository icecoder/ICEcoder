<?php
// Load common functions
include("settings-common.php");

$files		= explode(",",str_replace("|","/",$_GET['files']));
$filesSizesSeen	= explode(",",$_GET['filesSizesSeen']);
$maxLines	= $_GET['maxLines'];

$result = "ok";

for ($i=0; $i<count($files); $i++) {
	$files[$i] = $_SERVER['DOCUMENT_ROOT'].$files[$i];
	$filesSizesSeen[$i] = filesize($files[$i]);
	if (!file_exists($files[$i])) {
		$result = "error";
	}
}

if ($result != "error") {

	// If we have a value here, we can test, otherwise don't
	if (explode(",",$_GET['filesSizesSeen'])[0]!="null" && explode(",",$_GET['filesSizesSeen'])[0] != $filesSizesSeen[0]) {
		$result = "bugs";

		$filename = $files[0];
		$chars = ($filesSizesSeen[0]-explode(",",$_GET['filesSizesSeen'])[0]);
		$buffer = 4096;
		$lines = $maxLines+1+1; // 1 (possibly) for end of file and 1 for partial lines

		// Open the file
		$f = fopen($filename, "rb");

		// Jump to last character
		fseek($f, 0, SEEK_END);

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

			$lines -= substr_count($chunk, "\n");	
		}

		// Close file
		fclose($f); 

		$output = rtrim(str_replace("\r\n","\n",$output));
		$output = explode("\n",$output);
		$output = array_slice($output, -$maxLines);
		$output = implode("\n",$output);

		file_put_contents("../tmp/bug-report.log", $output);
	}

}

$tmpLoc = dirname(__FILE__);
$tmpLoc = explode(DIRECTORY_SEPARATOR,$tmpLoc);
$tmpLoc = $tmpLoc[count($tmpLoc)-2];

// Output result and status array
$status = array(
	"files" => $files,
	"filesSizesSeen" => $filesSizesSeen,
	"maxLines" => $maxLines,
	"chars" => (isset($chars) ? $chars : null),
	"lines" => substr_count($output, "\n"),
	"seek" => (isset($seek) ? $seek : null),
	"bugReportPath" => "|".$tmpLoc."|tmp|bug-report.log",
	"result" => $result
);

// Include our process once our bug checking work is done
include("../processes/on-bug-check.php");

// Finally, display our status in JSON format as the XHR response text
echo json_encode($status);

?>