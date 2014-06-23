<?php
include("lib/headers.php");
include("lib/settings.php");

// Is our dir in the list of GitHub local paths?
$isGitHubRepoDir = in_array($ICEcoder["root"],$ICEcoder['githubLocalPaths']);
?>
<!DOCTYPE html>

<html onMouseDown="top.ICEcoder.mouseDown=true" onMouseUp="top.ICEcoder.mouseDown=false; if (!top.ICEcoder.overCloseLink) {top.ICEcoder.tabDragEnd()}" onMouseMove="if(top.ICEcoder) {top.ICEcoder.getMouseXY(event,'files');top.ICEcoder.canResizeFilesW()}" onDrop="if(top.ICEcoder) {top.ICEcoder.getMouseXY(event,'files')}" onContextMenu="top.ICEcoder.selectFileFolder(event); return top.ICEcoder.showMenu(event)" onClick="top.ICEcoder.selectFileFolder(event)" onDragStart="top.ICEcoder.selectFileFolder(event);" onDragOver="event.preventDefault();event.stopPropagation()">
<head>
<title>ICEcoder v <?php echo $ICEcoder["versionNo"];?> file manager</title>
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" type="text/css" href="lib/files.css">
<link rel="stylesheet" type="text/css" href="lib/file-types.css">
<link rel="stylesheet" type="text/css" href="lib/file-type-icons.css">
<script src="lib/ice-coder<?php if (!$ICEcoder['devMode']) {echo '.min';};?>.js" type="text/javascript"></script>
</head>

<body onDblClick="top.ICEcoder.openFile()" onKeyDown="return top.ICEcoder.interceptKeys('files', event);" onKeyUp="top.ICEcoder.resetKeys(event);" onBlur="parent.ICEcoder.resetKeys(event);">

<div title="Lock" onClick="top.ICEcoder.lockUnlockNav()" id="fmLock" class="lock"></div>
<div title="Refresh" onClick="top.ICEcoder.refreshFileManager()" class="refresh"></div>
<?php
if ($isGitHubRepoDir) {
	$classExtra = !isset($_GET["githubDiff"]) || $_GET["githubDiff"] == "false" ? "Off" : "On";
	echo '<div title="GitHub" onClick="top.ICEcoder.githubDiffToggle()" class="github'.$classExtra.'"></div>';
}
?>

<ul class="fileManager">
<li class="pft-directory dirOpen"><a nohref title="/" onMouseOver="top.ICEcoder.overFileFolder('folder','/')" onMouseOut="top.ICEcoder.overFileFolder('folder','')" onClick="top.ICEcoder.openCloseDir(this)" style="position: relative; left:-22px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id="|">/ <?php echo $iceRoot == "" ? "[ROOT]" : trim($iceRoot,"/");?></span> <span style="color: #888; font-size: 8px" id="|_perms"><?php echo $serverType=="Linux" ? substr(sprintf('%o', fileperms($docRoot.$iceRoot)), -3) : "";?></span></a></li><?php
$_GET['location'] = "|";
include("lib/get-branch.php");
?>
</ul>

<iframe name="fileControl" style="display: none"></iframe>

<iframe name="testControl" style="display: none"></iframe>

<iframe name="processControl" style="display: none"></iframe>

</body>
	
</html>