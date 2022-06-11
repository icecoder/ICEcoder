<?php
include "headers.php";
include "settings.php";
$t = $text['properties'];

// Establish the real absolute path to the file/folder
$fileName=realpath($docRoot . $iceRoot . str_replace("|", "/", $_GET['fileName']));
// If it doesn't exist, or doesn't start with the $docRoot, stop here
if (!file_exists($fileName) || 0 !== strpos(str_replace("\\", "/", $fileName),$docRoot)) {
        die("<script>alert('Sorry - problem with file/folder requested'); window.history.back();</script>");
}

$assetsPath = "assets" === $settingsClass->assetsRoot
    ? "../" . $settingsClass->assetsRoot
    : $settingsClass->assetsRoot
?>
<!DOCTYPE html>

<html oncontextmenu="return false">
<head>
<title>ICEcoder <?php echo $ICEcoder["versionNo"];?> file/folder properties</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" type="text/css" href="<?php echo $assetsPath;?>/css/resets.css?microtime=<?php echo microtime(true);?>">
<link rel="stylesheet" type="text/css" href="<?php echo $assetsPath;?>/css/properties.css?microtime=<?php echo microtime(true);?>">
</head>

<body class="properties" onkeyup="parent.ICEcoder.handleModalKeyUp(event, 'properties')" onload="this.focus();">

<h1 id="title"><?php echo $t['properties'];?></h1>

<h2><?php echo basename($fileName); ?></h2><br>
<span class="column" style="width: 180px"><?php echo $t['Size'];?>: <?php
$bytes = filesize($fileName);
// If it's a dir, get the dir size
if (is_dir($fileName)) {
	$io = popen('/usr/bin/du -sb ' . $fileName, 'r');
	$bytes = intval(fgets($io, 80));
	pclose($io);
}
// Change into kilobytes
$outputSize = ($bytes / 1024);
$outputUnit = "kb";
// Maybe we should show in megabytes?
if ($outputSize >= 1024) {
	$outputSize = ($outputSize / 1024);
	$outputUnit = "mb";
}
echo number_format($outputSize, 2, '.', '') . $outputUnit . " (" . number_format($bytes) . " bytes)";
?></span>
<span class="column" style="margin: 0 10px"><?php echo $t['Modified'];?>: <?php echo date( "D jS M Y g:i:sa", filemtime($fileName)); ?></span>
<span class="column"><?php echo $t['Last access'];?>: <?php echo date( "D jS M Y g:i:sa", fileatime($fileName)); ?></span>
<br><br>
<span class="column" style="width: 180px"><?php echo $t['Type'];?>: <?php echo is_dir($fileName) ? "Folder" : "File"; ?></span>
<span class="column" style="margin: 0 10px"><?php echo $t['Readable Writeable'];?>:
<?php echo is_readable($fileName) ? "Yes" : "No"; ?> / <?php echo is_writeable($fileName) ? "Yes" : "No";?>
</span>
<span class="column"><?php echo $t['Relative path'];?>: <?php echo str_replace($docRoot, "", $fileName);?></span>
<br><br>
<span class="column" style="width: 410px"><?php echo $t['Absolute path'];?>:<br><?php echo $fileName;?></span>
<?php if (is_dir($fileName)) {?>
<span class="column" style="width: 180px"><?php echo $t['Contains'];?>:<br><?php
$dirList = scandir($fileName);
$dirCount = 0;
$fileCount = 0;
for ($i = 0; $i < count($dirList); $i++) {
	if ("." !== $dirList[$i] && ".." !== $dirList[$i]) {
		if (is_dir($fileName . "/" . $dirList[$i])) {
			$dirCount++;
		} else {
			$fileCount++;
		}
	}
}
$dirPlural = $dirCount != 1 ? "s" : "";
$filePlural = $fileCount != 1 ? "s" : "";
echo $dirCount . " sub-folder" . $dirPlural . ", " . $fileCount . " file" . $filePlural;
?>
</span>
<?php ;};?>
<br><br><br>
<span class="column" style="width: 180px">
<?php echo $t['Permissions'];?>:
<?php
$chmodInfo = substr(sprintf('%o', fileperms($fileName)), -4);
echo $chmodInfo;
?>
</span>
<span class="column" style="margin: 0 10px">
<?php
$perms = str_split(substr($chmodInfo, 1, 3)); // reduces 0755 down to 755
$readVars  = array(4, 5, 6, 7);
$writeVars = array(2, 3, 6, 7);
$execVars  = array(1, 3, 5, 7);
?>
<table>
<tr><th><?php echo $t['Owner'];?></th><th><?php echo $t['Group'];?></th><th><?php echo $t['Public'];?></th></tr>
<tr>
<td><input type="checkbox" name="ownerR" id="owner4"<?php if (true === in_array($perms[0], $readVars)) {echo ' checked';};?> onClick="changePerms(); showButton()"> <?php echo $t['Read'];?></td>
<td><input type="checkbox" name="groupR" id="group4"<?php if (true === in_array($perms[1], $readVars)) {echo ' checked';};?> onClick="changePerms(); showButton()"> <?php echo $t['Read'];?></td>
<td><input type="checkbox" name="publicR" id="public4"<?php if (true === in_array($perms[2], $readVars)) {echo ' checked';};?> onClick="changePerms(); showButton()"> <?php echo $t['Read'];?></td>
</tr>
<tr>
<td><input type="checkbox" name="ownerW" id="owner2"<?php if (true === in_array($perms[0], $writeVars)) {echo ' checked';};?> onClick="changePerms(); showButton()"> <?php echo $t['Write'];?></td>
<td><input type="checkbox" name="groupW" id="group2"<?php if (true === in_array($perms[1], $writeVars)) {echo ' checked';};?> onClick="changePerms(); showButton()"> <?php echo $t['Write'];?></td>
<td><input type="checkbox" name="publicW" id="public2"<?php if (true === in_array($perms[2], $writeVars)) {echo ' checked';};?> onClick="changePerms(); showButton()"> <?php echo $t['Write'];?></td>
</tr>
<tr>
<td><input type="checkbox" name="ownerE" id="owner1"<?php if (true === in_array($perms[0], $execVars)) {echo ' checked';};?> onClick="changePerms(); showButton()"> <?php echo $t['Execute'];?></td>
<td><input type="checkbox" name="groupE" id="group1"<?php if (true === in_array($perms[1], $execVars)) {echo ' checked';};?> onClick="changePerms(); showButton()"> <?php echo $t['Execute'];?></td>
<td><input type="checkbox" name="publicE" id="public1"<?php if (true === in_array($perms[2], $execVars)) {echo ' checked';};?> onClick="changePerms(); showButton()"> <?php echo $t['Execute'];?></td>
</tr>
</table>
</span>
<span class="column">
<?php echo $t['Change to'];?>:<br>
<form name="chmod" onsubmit="validatePerms(); return false" method="GET">
<input type="text" name="chmod" class="permText" id="permText" style="width: 30px" maxlength="3" value="<?php echo substr($chmodInfo, 1, 3); ?>" onKeyUp="changePerms(this.value); showButton()" onChange="changePerms(this.value); showButton()">
<input type="hidden" name="csrf" value="<?php echo $_SESSION["csrf"]; ?>">
</form>
</span>

<div class="update" id="updateButton" onClick="validatePerms()"><?php echo $t['update'];?></div>

<script>
readVars   = [4, 5, 6, 7];
writeVars  = [2, 3, 6, 7];
execVars   = [1, 3, 5, 7];
permGroups = ['owner', 'group', 'public'];
permValues = [4, 2, 1];
permTypes  = ['read', 'write', 'exec'];

function changePerms(val) {
	const permText = document.getElementById('permText').value;
	// change checkboxes
	if (val) {
		// set values
		if (3 === permText.length) {
			for (let i = 0; i <= 2; i++) {
				for (let j = 0; j <= 2; j++) {
				document.getElementById(permGroups[i] + permValues[j]).checked = window[permTypes[j] + 'Vars'].indexOf(permText.split("")[i] * 1) > -1;
				}
			}
		// clear values
		} else {
			for (let i = 0; i <= 2; i++) {
				for (let j = 0; j <= 2; j++) {
					document.getElementById(permGroups[i]+permValues[j]).checked  = false;
				}
			}
		}
	// change text value
	} else {
		ownerPerms = (document.getElementById('owner4').checked * 4) + (document.getElementById('owner2').checked * 2) + (document.getElementById('owner1').checked * 1);
		groupPerms = (document.getElementById('group4').checked * 4) + (document.getElementById('group2').checked * 2) + (document.getElementById('group1').checked * 1);
		publicPerms = (document.getElementById('public4').checked * 4) + (document.getElementById('public2').checked * 2) + (document.getElementById('public1').checked * 1);
		document.getElementById('permText').value = ownerPerms.toString() + groupPerms.toString() + publicPerms.toString();
	}
}

const showButton = function() {
	document.getElementById('updateButton').style.opacity = "1";
}

const validatePerms = function() {
	const permText = document.getElementById('permText').value;
	canUpdate = true;
	if (3 !== permText.length || isNaN(permText)) {canUpdate = false}
	if (
	    0 > permText.split("")[0] * 1 || 7 < permText.split("")[0] * 1 ||
		0 > permText.split("")[1] * 1 || 7 < permText.split("")[1] * 1 ||
		0 > permText.split("")[2] * 1 || 7 < permText.split("")[2] * 1
    ) {
		canUpdate = false;
	}
	if (canUpdate) {parent.ICEcoder.chmod('<?php echo str_replace($docRoot,"",$fileName);?>', permText)}
}
</script>

<?php
echo $systemClass->getDemoModeIndicator(true);
?>

</body>

</html>
