<?php
require "icecoder.php";

use ICEcoder\File;

// Establish the real absolute path to the file
$filePath = realpath($docRoot . $iceRoot . str_replace("|", "/", $_GET['file']));
// If it doesn't exist, or doesn't start with the $docRoot, stop here
if (false === file_exists($filePath) || 0 !== strpos(str_replace("\\", "/", $filePath), $docRoot)) {
	die("<script>ICEcoder.message('Sorry, that file doesn\'t appear to exist');</script>");
}

if (true === file_exists($filePath)) {
    $file = new File();
    $file->download($filePath);
    exit;
}
