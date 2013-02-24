<?php
// Finally, we have our ordered list, so display in a UL
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
$lastPath="";
$fileCount=0;
$fileBytes=0;
$dirCount=0;
for ($i=0;$i<count($finalArray);$i++) {
	$fileFolderName = str_replace("\\","/",$finalArray[$i]);
	$type = is_dir($docRoot.$iceRoot.$fileFolderName) ? "folder" : "file";
	$type=="folder" ? $dirCount++ : $fileCount++;
	if ($type=="file") {
		$fileBytes+=filesize($docRoot.$iceRoot.$fileFolderName);
		// Get extension (prefix 'ext-' to prevent invalid classes from extensions that begin with numbers)
		$ext = "ext-".pathinfo($docRoot.$iceRoot.$fileFolderName, PATHINFO_EXTENSION);
	}
	$thisDepth = count(explode("/",$fileFolderName));
	$lastDepth = count(explode("/",$lastPath));
	$ulDisplay = $i==0 ?  ' style="display: block"' : ' style="display: none"';
	if ($thisDepth > $lastDepth) {echo "<ul".$ulDisplay.">\n";}
	if ($thisDepth < $lastDepth) {
		for ($j=$lastDepth;$j>$thisDepth;$j--) {
			echo "</ul>\n";
		}
	}
	if ($serverType=="Linux") {
		$chmodInfo = substr(sprintf('%o', fileperms($docRoot.$iceRoot.$fileFolderName)), -3);
		$fileAtts = '<span style="color: #888; font-size: 8px" id="'.str_replace($docRoot,"",str_replace("/","|",$fileFolderName)).'_perms">'.$chmodInfo.'</span>';
	}
	$type == "folder" ? $class = 'pft-directory' : $class = 'pft-file '.strtolower($ext);
	echo "<li class=\"".$class."\"><a nohref title=\"$fileFolderName\" onMouseOver=\"top.ICEcoder.overFileFolder('$type','".str_replace($docRoot,"",str_replace("/","|",$fileFolderName))."')\" onMouseOut=\"top.ICEcoder.overFileFolder('$type','')\" onClick=\"top.ICEcoder.openCloseDir(this)\" style=\"position: relative; left:-22px\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id=\"".str_replace($docRoot,"",str_replace("/","|",$fileFolderName))."\">".basename($fileFolderName)."</span> ".$fileAtts."</a>\n";
	if ($i<count($finalArray)) {echo "</li>\n";}
	$lastPath = $fileFolderName;
}
echo "</ul>\n</ul>\n";
?>