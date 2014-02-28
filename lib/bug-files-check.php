<?php
// TO DO:

// After setting 3 new bug file params in settings, open dev console to see the URL that's being called

// This file will be called upon by XHR, on the timer value specified (eg 2 secs), with 3 querystring keys - files, filesMDTs and maxLines
// eg: bug-files-check.php?files=|var|domain|log.txt,|var|domain|another|example.txt&filesMDTs=123456,123478&maxLines=100

// You can then grab the list of files and MDTs (modified datetimes) from the querystring into 2 PHP arrays

// The first time this file is run, there will be no MDTs in top.ICEcoder.openFileMDTs, so the querysting will contain filesMDTs=null,null
// If there are null MDTs, get the files MDT (eg using filemtime($file) on Linux) for each file and push into the array at top.ICEcoder.openFileMDTs

// The 2nd and all subsequent time this file runs, we'll of course have MDTs in that JS array and that's what gets passed in the querysting from then on
// From the 2nd call onwards, we can compare the querystring MDT against the actual file MDT and if different, we need to get results as there's new bugs

// So at that point, we can get the maxLines value from the querystring, tail that number of lines from the file in question into tmp/bug-report.log

// After all of this, we can set $result to one of 4 states - off, error, ok or bugs
// You can change the value below now to see the icon change




// Output result and status array
$result = "off";
$status = array("result" => $result);

// Include our process once our bug checking work is done
include("../processes/on-bug-check.php");

// Finally, display our status in JSON format as the XHR response text
echo json_encode($status);

?>