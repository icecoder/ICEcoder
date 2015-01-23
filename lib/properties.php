<?php
include("headers.php");
include("settings.php");
$t = $text['properties'];

// Establish the real absolute path to the file/folder
$fileName=realpath($docRoot.$iceRoot.str_replace("|","/",strClean($_GET['fileName'])));
// If it doesn't exist, or doesn't start with the $docRoot, stop here
if (!file_exists($fileName) || strpos(str_replace("\\","/",$fileName),$docRoot) !== 0) {
        die("<script>alert('Sorry - problem with file/folder requested');window.history.back();</script>");
}
?>
<!DOCTYPE html>

<html onContextMenu="return false">
<head>
<title>ICEcoder <?php echo $ICEcoder["versionNo"];?> file/folder properties</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" type="text/css" href="properties.css?microtime=<?php echo microtime(true);?>">
</head>

<body class="properties">

<h1 id="title"><?php echo $t['properties'];?></h1>

<h2><?php echo basename($fileName); ?></h2><br>
<span class="column" style="width: 180px"><?php echo $t['Size'];?>: <?php
$bytes = filesize($fileName);
// If it's a dir, get the dir size
if (is_dir($fileName)) {
	$io = popen('/usr/bin/du -sb '.$fileName, 'r');
	$bytes = intval(fgets($io,80));
	pclose($io);
}
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
<span class="column" style="margin: 0 10px"><?php echo $t['Modified'];?>: <?php echo date( "D jS M Y g:i:sa", filemtime($fileName)); ?></span>
<span class="column"><?php echo $t['Last access'];?>: <?php echo date( "D jS M Y g:i:sa", fileatime($fileName)); ?></span>
<br><br>
<span class="column" style="width: 180px"><?php echo $t['Type'];?>: <?php echo is_dir($fileName) ? "Folder" : "File"; ?></span>
<span class="column" style="margin: 0 10px"><?php echo $t['Readable Writeable'];?>: 
<?php echo is_readable($fileName) ? "Yes" : "No"; ?> / <?php echo is_writeable($fileName) ? "Yes" : "No";?>
</span>
<span class="column"><?php echo $t['Relative path'];?>: <?php echo str_replace($docRoot,"",$fileName);?></span>
<br><br>
<span class="column" style="width: 410px"><?php echo $t['Absolute path'];?>:<br><?php echo $fileName;?></span>
<?php if (is_dir($fileName)) {?>
<span class="column" style="width: 180px"><?php echo $t['Contains'];?>:<br><?php
$dirList = scandir($fileName);
$dirCount = 0;
$fileCount = 0;
for ($i=0; $i<count($dirList); $i++) {
	if ($dirList[$i] != "." && $dirList[$i] != "..") {
		if (is_dir($fileName."/".$dirList[$i])) {
			$dirCount++;
		} else {
			$fileCount++;
		}
	}
}
$dirPlural = $dirCount != 1 ? "s" : "";
$filePlural = $fileCount != 1 ? "s" : "";
echo $dirCount." sub-folder".$dirPlural.", ".$fileCount." file".$filePlural;
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
$perms = str_split(substr($chmodInfo,1,3)); // reduces 0755 down to 755
$readVars  = array(4,5,6,7);
$writeVars = array(2,3,6,7);
$execVars  = array(1,3,5,7);
?>
<table>
<tr><th><?php echo $t['Owner'];?></th><th><?php echo $t['Group'];?></th><th><?php echo $t['Public'];?></th></tr>
<tr>
<td><input type="checkbox" name="ownerR" id="owner4"<?php if(in_array($perms[0],$readVars)!="") {echo ' checked';};?> onClick="changePerms();showButton()"> <?php echo $t['Read'];?></td>
<td><input type="checkbox" name="groupR" id="group4"<?php if(in_array($perms[1],$readVars)!="") {echo ' checked';};?> onClick="changePerms();showButton()"> <?php echo $t['Read'];?></td>
<td><input type="checkbox" name="publicR" id="public4"<?php if(in_array($perms[2],$readVars)!="") {echo ' checked';};?> onClick="changePerms();showButton()"> <?php echo $t['Read'];?></td>
</tr>
<tr>
<td><input type="checkbox" name="ownerW" id="owner2"<?php if(in_array($perms[0],$writeVars)!="") {echo ' checked';};?> onClick="changePerms();showButton()"> <?php echo $t['Write'];?></td>
<td><input type="checkbox" name="groupW" id="group2"<?php if(in_array($perms[1],$writeVars)!="") {echo ' checked';};?> onClick="changePerms();showButton()"> <?php echo $t['Write'];?></td>
<td><input type="checkbox" name="publicW" id="public2"<?php if(in_array($perms[2],$writeVars)!="") {echo ' checked';};?> onClick="changePerms();showButton()"> <?php echo $t['Write'];?></td>
</tr>
<tr>
<td><input type="checkbox" name="ownerE" id="owner1"<?php if(in_array($perms[0],$execVars)!="") {echo ' checked';};?> onClick="changePerms();showButton()"> <?php echo $t['Execute'];?></td>
<td><input type="checkbox" name="groupE" id="group1"<?php if(in_array($perms[1],$execVars)!="") {echo ' checked';};?> onClick="changePerms();showButton()"> <?php echo $t['Execute'];?></td>
<td><input type="checkbox" name="publicE" id="public1"<?php if(in_array($perms[2],$execVars)!="") {echo ' checked';};?> onClick="changePerms();showButton()"> <?php echo $t['Execute'];?></td>
</tr>
</table>
</span>
<span class="column">
<?php echo $t['Change to'];?>:<br>
<form name="chmod" action="#" method="GET">
<input type="text" name="chmod" id="permText" style="width: 30px; border: 0; background-color: #444; font-size: 10px; color: #fff" maxlength="3" value="<?php echo substr($chmodInfo,1,3); ?>" onKeyUp="changePerms(this.value);showButton()" onChange="changePerms(this.value);showButton()">
<input type="hidden" name="csrf" value="<?php echo $_SESSION["csrf"]; ?>">
</form>
</span>

<div class="update" id="updateButton" onClick="validatePerms()"><?php echo $t['update'];?></div>

<script>
readVars   = [4,5,6,7];
writeVars  = [2,3,6,7];
execVars   = [1,3,5,7];
permGroups = ['owner','group','public'];
permValues = [4,2,1];
permTypes  = ['read','write','exec'];

function changePerms(val) {
	var permText = document.getElementById('permText').value;
	// change checkboxes
	if (val) {
		// set values
		if (permText.length==3) {
			for (var i=0;i<=2;i++) {
				for (var j=0;j<=2;j++) {
				document.getElementById(permGroups[i]+permValues[j]).checked = window[permTypes[j]+'Vars'].indexOf(permText.split("")[i]*1)>-1;
				}
			}
		// clear values
		} else {
			for (var i=0;i<=2;i++) {
				for (var j=0;j<=2;j++) {
					document.getElementById(permGroups[i]+permValues[j]).checked  = false;
				}
			}
		}
	// change text value
	} else {
		ownerPerms = (document.getElementById('owner4').checked*4)+(document.getElementById('owner2').checked*2)+(document.getElementById('owner1').checked*1);
		groupPerms = (document.getElementById('group4').checked*4)+(document.getElementById('group2').checked*2)+(document.getElementById('group1').checked*1);
		publicPerms = (document.getElementById('public4').checked*4)+(document.getElementById('public2').checked*2)+(document.getElementById('public1').checked*1);
		document.getElementById('permText').value = ownerPerms.toString() + groupPerms.toString() + publicPerms.toString();
	}
}

var showButton = function() {
	document.getElementById('updateButton').style.opacity = 1;
}

var validatePerms = function() {
	var permText = document.getElementById('permText').value;
	canUpdate = true;
	if (permText.length!=3 || isNaN(permText)) {canUpdate = false};
	if (	permText.split("")[0]*1 <0 || permText.split("")[0]*1 >7 ||
		permText.split("")[1]*1 <0 || permText.split("")[1]*1 >7 ||
		permText.split("")[2]*1 <0 || permText.split("")[2]*1 >7) {
		canUpdate = false;
	}
	if (canUpdate) {top.ICEcoder.chmod('<?php echo str_replace($docRoot,"",$fileName);?>',permText)};
}
</script>

</body>

</html>