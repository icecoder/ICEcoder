<?php
include("headers.php");
include("settings.php");

// Establish the real absolute path to the file
$file = realpath($docRoot.$iceRoot.str_replace("|","/",strClean($_GET['file'])));
// If it doesn't exist, or doesn't start with the $docRoot, stop here
if (!file_exists($file) || strpos(str_replace("\\","/",$file),$docRoot) !== 0) {
	die("<script>alert('Sorry, that file doesn\'t appear to exist');</script>");
}

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Length: '.filesize($file));
    ob_clean();
    flush();
    readfile($file);
    exit;
}
?>