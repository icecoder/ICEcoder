<?php include("lib/settings.php");?>
<!DOCTYPE html>

<html onMouseDown="top.ICEcoder.mouseDown=true" onMouseUp="top.ICEcoder.mouseDown=false; top.ICEcoder.tabDragEnd()" onMouseMove="if(top.ICEcoder) {top.ICEcoder.getMouseXY(event,'files');top.ICEcoder.canResizeFilesW()}" onContextMenu="top.ICEcoder.rightClickedFile=top.ICEcoder.thisFileFolderLink; return top.ICEcoder.showMenu()" onClick="top.ICEcoder.selectFileFolder()">
<head>
<title>ICEcoder v <?php echo $ICEcoder["versionNo"];?> file manager</title>
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" type="text/css" href="lib/files.css">
<script src="lib/ice-coder.js" type="text/javascript"></script>
</head>

<body onDblClick="top.ICEcoder.openFile()" onKeyDown="return top.ICEcoder.interceptKeys('files', event);" onKeyUp="top.ICEcoder.resetKeys(event);">

<div title="Refresh" onClick="top.ICEcoder.refreshFileManager()" class="refresh"></div>

<?php
$fileAtts = "";
if ($serverType=="Linux") {
	$chmodInfo = substr(sprintf('%o', fileperms($docRoot.$iceRoot)), -3);
	$fileAtts = '<span style="color: #888; font-size: 8px" id="|_perms">'.$chmodInfo.'</span>';
}
?>
<ul class="fileManager">
<li class="pft-directory dirOpen">
<a nohref title="/" onMouseOver="top.ICEcoder.overFileFolder('folder','/')" onMouseOut="top.ICEcoder.overFileFolder('folder','')" onClick="top.ICEcoder.openCloseDir(this)" style="position: relative; left:-22px">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
<span id="|">/ 
<?php echo $iceRoot == "" ? "[ROOT]" : trim($iceRoot,"/");?>
</span> 
<?php echo $fileAtts;?>
</a>
</li>
<?php
include("lib/get-tree.php");
?>
</ul>

<?php
// Output the JS vars
echo "<script>\n";
echo "top.ICEcoder.dirCount=";
echo $dirCount ? $dirCount : "0";
echo ";\ntop.ICEcoder.fileCount=";
echo $fileCount ? $fileCount : "0";
echo ";\ntop.ICEcoder.fileBytes=";
echo $fileBytes ? $fileBytes : "0";
echo ";\n</script>";
?>

<iframe name="fileControl" style="display: none"></iframe>
		
</body>
	
</html>