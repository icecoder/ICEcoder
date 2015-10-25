<?php
// Load common functions
include("headers.php");
include("settings.php");

$file = str_replace("|","/",xssClean($_GET['file'],'html'));

$loadedFile = toUTF8noBOM(file_get_contents("../backups/".$file,false,$context),true);
$encoding=ini_get("default_charset");
if($encoding=="")
	$encoding="UTF-8";
echo '<textarea name="loadedFile" id="loadedFile">'.htmlentities($loadedFile,ENT_COMPAT,$encoding).'</textarea>';
echo "<script>parent.document.getElementById('buttonsContainer').style.display = 'inline-block';parent.editor.setValue(document.getElementById('loadedFile').value)</script>";
?>