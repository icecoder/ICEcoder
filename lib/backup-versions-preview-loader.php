<?php
// Load common functions
include "headers.php";
include "settings.php";

$file = str_replace("|", "/", xssClean($_GET['file'], 'html'));

// Get contents
$loadedFile = toUTF8noBOM(getData("../data/backups/" . $file), true);
$encoding = ini_get("default_charset");
if ($encoding == "") {
    $encoding = "UTF-8";
}

// Set content in a textarea
echo '<textarea name="loadedFile" id="loadedFile">' . htmlentities($loadedFile, ENT_COMPAT, $encoding) . '</textarea>';

// Get bytes for this file
$bytes = filesize("../data/backups/" . $file);
// Change into kilobytes
$outputSize = ($bytes / 1024);
$outputUnit = "kb";
// Maybe we should show in megabytes?
if ($outputSize >= 1024) {
	$outputSize = ($outputSize / 1024);
	$outputUnit = "mb";
}
$size = number_format($outputSize, 2, '.', '') . $outputUnit . " (" . number_format($bytes) . " bytes)";

// Get date & time of file
$datetime = str_replace("-", "<br>", date("D jS M Y-g:i:sa", filemtime("../data/backups/" . $file)));
?>
<script>
parent.document.getElementById('buttonsContainer').style.display = 'inline-block';
parent.editor.setValue(document.getElementById('loadedFile').value);
parent.document.getElementById('infoContainer').innerHTML = 'Date & Time:<br><?php echo $datetime;?><br><br>Size:<br><?php echo $size;?>';
</script>
