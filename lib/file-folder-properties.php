<?php include("settings.php");?>
<!DOCTYPE html>

<html>
<head>
<title>ICE Coder - <?php echo $versionNo;?> :: File/Folder Properties</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="file-folder-properties.css">
</head>

<body class="properties">

<h1 id="title">properties</h1>

<?php
$fileName=str_replace("|","/",strClean($_GET['fileName']));
?>
<h2><?php echo basename($fileName); ?></h2><br>
<span class="column" style="width: 150px">Size: <?php
$bytes = filesize($fileName);
// Change into kilobytes
$outputSize = ($bytes/1024);
$outputUnit = "kb";
// Maybe we should show in megabytes?
if ($outputSize >= 1024) {
	$outputSize = ($outputSize/1024);
	$outputUnit = "mb";
}
echo number_format($outputSize, 2, '.', '').$outputUnit." (".number_format($bytes)." bytes)";
?></span>
<span class="column" style="margin: 0 10px">Modified: <?php echo date( "D d M Y g:i:sa", filemtime($fileName)); ?></span>
<span class="column">Last Accessed: <?php echo date( "D d M Y g:i:sa", fileatime($fileName)); ?></span>
<br><br>
<span class="column" style="width: 150px">Type: <?php echo is_dir($fileName) ? "Folder" : "File"; ?></span>
<span class="column" style="margin: 0 10px">Readable / Writeable: <?php echo is_readable($fileName) ? "Yes" : "No"; ?> / <?php echo is_writeable($fileName) ? "Yes" : "No"; ?></span>
<span class="column">Root path: <?php echo str_replace($docRoot,"",$fileName);?></span>
<span style="font-size:10px">
<br><br>
Full path:<br><?php echo $fileName;?>
<br><br>
</span>
<span class="column" style="width: 150px">
Permissions:
<?
$chmodInfo = substr(sprintf('%o', fileperms($fileName)), -4);
echo $chmodInfo;
?>
</span>
<span class="column" style="margin: 0 10px">
<?php
$perms = str_split($chmodInfo);
?>
</span>

</body>

</html>