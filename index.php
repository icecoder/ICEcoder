<?php
include("lib/settings.php");
$allowedIP = false;
for($i=0;$i<count($allowedIPs);$i++) {
	if ($allowedIPs[$i]==$_SERVER["REMOTE_ADDR"]||$allowedIPs[$i]=="*") {
		$allowedIP = true;
	}
}
if (!$allowedIP) {
	// 404 the page
	header('HTTP/1.0 404 Not Found');
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested URL <?php echo str_replace("/index.php","",$_SERVER["SCRIPT_NAME"]);?> was not found on this server.</p>
<hr>
<address>Apache/2.2.3 (CentOS) Server at www.mattpass.com Port 80</address>
</body></html>
<?php
	exit();
};

// Test for latest CodeMirror version
if ($testcMVersion) {
	$cMLatestVer = json_encode(file_get_contents("http://codemirror.net/latest-version.txt"));
	$cMLatestVer = rtrim(ltrim($cMLatestVer,"\""),"\"\\n");
	if ($cMThisVer<$cMLatestVer) {
		echo '<script>alert(\'Code Mirror '.$cMLatestVer.' now released\n\nPlease upgrade\');</script>';
	}
}
?>
<!DOCTYPE html>

<html>
<head>
<title>ICE Coder - <?php echo $versionNo;?></title>
<link rel="stylesheet" type="text/css" href="lib/coder.css">
<script>
shortURLStarts = "<?php echo $shortURLStarts;?>";
</script>
<script language="JavaScript" src="lib/coder.js"></script>
</head><?php
	$onLoadExtras = "";
	for ($i=0;$i<count($plugins);$i++) {
		if ($plugins[$i][5]!="") {
			$onLoadExtras .= ";ICEcoder.startPluginIntervals('".$plugins[$i][3]."','".$plugins[$i][4]."','".$plugins[$i][5]."')";
		};
	};
?>
<body onLoad="ICEcoder.init()<?php echo $onLoadExtras;?>" onResize="ICEcoder.setLayout()" onKeyDown="return ICEcoder.interceptKeys('coder', event);" onKeyUp="parent.ICEcoder.resetKeys(event);">

<div id="blackMask" class="blackMask" onClick="ICEcoder.showHide('hide',this)">
	<div class="popupVCenter">
		<div class="popup" id="mediaContainer"></div>
	</div>
</div>


<div id="loadingMask" class="blackMask" style="visibility: visible">
	<span class="progressBar"></span>
	<div class="popupVCenter">
		<div class="popup">
			<div class="circleOutside"></div>
			<div class="circleInside"></div>
			&nbsp;&nbsp;&nbsp;loading...
		</div>
	</div>
</div>

<div id="fileMenu" class="fileMenu" onMouseOut="this.style.display='none'" onMouseOver="ICEcoder.changeFilesW('expand')" onMouseOut="ICEcoder.changeFilesW('contract')">
	<a href="javascript:top.ICEcoder.newFile()" onMouseOver="document.getElementById('fileMenu').style.display='inline-block'">New File</a>
	<a href="javascript:top.ICEcoder.newFolder()" onMouseOver="document.getElementById('fileMenu').style.display='inline-block'">New Folder</a>
	<a href="javascript:top.ICEcoder.deleteFile(top.ICEcoder.rightClickedFile)" onMouseOver="document.getElementById('fileMenu').style.display='inline-block'">Delete</a>
	<a href="javascript:top.ICEcoder.renameFile(top.ICEcoder.rightClickedFile)" onMouseOver="document.getElementById('fileMenu').style.display='inline-block'">Rename</a>
	<a href="javascript:window.open(top.ICEcoder.rightClickedFile.substr((top.ICEcoder.rightClickedFile.indexOf(shortURLStarts)+top.shortURLStarts.length),top.ICEcoder.rightClickedFile.length))" onMouseOver="document.getElementById('fileMenu').style.display='inline-block'">View Webpage</a>
</div>

<div id="header" class="header" onContextMenu="return false">
	<div class="plugins">
	<?php
	for ($i=0;$i<count($plugins);$i++) {
		echo '<a href="'.$plugins[$i][3].'" target="'.$plugins[$i][4].'"><img src="'.$plugins[$i][1].'" style="'.$plugins[$i][2].'" alt="'.$plugins[$i][0].'"></a>';
	};
	?>
	<iframe id="pluginActions" style="display: none"></iframe>
	</div>
	<div class="version"><?php echo $versionNo;?></div>
	<img src="images/ice-coder.gif" class="logo">
</div>

<div id="files" class="files" onMouseOver="ICEcoder.changeFilesW('expand')" onMouseOut="ICEcoder.changeFilesW('contract'); top.document.getElementById('fileMenu').style.display='none';">
	<div class="account" id="account">
		<form name="login" action="index.php" method="POST">
		<input type="password" name="loginPassword" class="accountPassword">
		<input type="submit" name="submit" value="Login" class="button">
		</form>
		<a nohref style="cursor: pointer" onClick="ICEcoder.lockUnlockNav()"><img src="images/file-manager-icons/padlock-disabled.png" id="fmLock" class="lock"></a>
	</div>
	<iframe id="filesFrame" class="frame" name="ff" src="files.php"></iframe>
	<div class="upload"><a href="javascript:alert('Doesn\'t do anything yet but will be a drag & drop style uploader')"><img src="images/upload.png"></a></div>
</div>

<div id="editor" class="editor">
	<div id="tabsBar" class="tabsBar" onContextMenu="return false">
		<?php
		for ($i=1;$i<=10;$i++) {
			echo '<div id="tab'.$i.'" class="tab" onClick="if(ICEcoder.canSwitchTabs) {ICEcoder.switchTab('.$i.')} else {ICEcoder.canSwitchTabs=true}"></div>';
		}
		?><div class="newTab" onClick="ICEcoder.newTab()"><img src="images/nav-new.png"></div>
	</div>
	<div id="findBar" class="findBar" onContextMenu="return false">
		<form name="findAndReplace">
			<div class="findReplace">
				<div class="findText">Find</div>
				<input type="text" name="find" value="" id="find" class="textbox find" onKeyUp="ICEcoder.findReplace('find',true)">
				<div class="findTextPlural">'s</div>
				<select name="connector" onChange="ICEcoder.findReplaceOptions()">
				<option>in</option>
				<option>and</option>
				</select>
				<div class="replaceText" id="rText" style="display: none">replace with</div>
				<input type="text" name="replace" value="" id="replace" class="textbox replace" style="display: none">
				<div class="targetText" id="rTarget" style="display: none">in</div>
				<select name="target">
				<option>this document</option>
				<option>open documents</option>
				<option>all files</option>
				<option>all filenames</option>
				</select>
				<input type="button" name="submit" value="&gt;&gt;" class="submit" onClick="ICEcoder.findReplace('findReplace',false)">
				<div class="results" id="results"></div>
			</div>
		</form>
		<form onSubmit="return ICEcoder.goToLine()">
		<div class="goLine">Go to Line<input type="text" name="goToLine" value="" id="goToLineNo" class="textbox goToLine">
		</form>
	</div>
	<iframe name="contentFrame" id="content" src="editor.php" class="code">
	</iframe>
</div>

<div class="footer" id="footer" onContextMenu="return false">
	<div class="nesting" id="nestValid">Nesting OK</div>
	<div class="nestLoc">Cursor nest location</div>
	<div class="nestDisplay" id="nestDisplay"></div>
	<div class="charDisplay" id="charDisplay"><span id="char"></span></div>
</div>

</body>

</html>