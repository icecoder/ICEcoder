<?php
// *** Define your host, username, and password
define('FTP_HOST', '192.168.1.88');
define('FTP_USER', 'Blimpf');
define('FTP_PASS', 'catfish');
 
 
// *** Include the class
include('ftp_class.php');
 
// *** Create the FTP object
$ftpObj = new FTPClient();
 
// *** Connect
if ($ftpObj -> connect(FTP_HOST, FTP_USER, FTP_PASS)) {
 
    // *** Then add FTP code here
 
    echo 'connected';
 
} else {
    echo 'Failed to connect';
}

print_r($ftpObj -> getMessages());

$dir = 'photos';    
 
// *** Make directory
$ftpObj->makeDir($dir);

$fileFrom = 'zoe.jpg';              
$fileTo = $dir . '/' . $fileFrom;
 
// *** Upload local file to new directory on server
$ftpObj -> uploadFile($fileFrom, $fileTo);

// *** Change to folder
$ftpObj->changeDir($dir);
 
// *** Get folder contents
$contentsArray = $ftpObj->getDirListing();
 
// *** Output our array of folder contents
echo '<pre>';
print_r($contentsArray);
echo '';

$fileFrom = 'zoe.jpg';      # The location on the server
$fileTo = 'zoe-new.jpg';            # Local dir to save to
 
// *** Download file
$ftpObj->downloadFile($fileFrom, $fileTo);
?>
