<?php
include("lib/settings.php");

// Check IP permissions
if (!in_array($_SERVER["REMOTE_ADDR"], $_SESSION['allowedIPs']) && !in_array("*", $_SESSION['allowedIPs'])) {
	header('Location: /');
};

// Check for updates of ICEcoder & CodeMirror
if ($ICEcoder["checkUpdates"]) {
	$icv = json_encode(file_get_contents("http://icecoder.net/latest-version.txt"));
	$icv = rtrim(ltrim($icv,'"'),'"\\n');
	if (ltrim($ICEcoder["versionNo"],"v ")<$icv) {
		$updateMsg = ';top.ICEcoder.message(\'ICEcoder '.$icv.' now released\n\nPlease upgrade\')';
	} else {
		$cmv = json_encode(file_get_contents("http://codemirror.net/latest-version.txt"));
		$cmv = rtrim(ltrim($cmv,'"'),'"\\n');
		if ($ICEcoder["cMThisVer"]<$cmv) {
			$updateMsg = ';top.ICEcoder.message(\'CodeMirror '.$cmv.' now released\n\nPlease upgrade\')';
		}
	}
}
?>
<!DOCTYPE html>

<html onMouseDown="top.ICEcoder.mouseDown=true" onMouseUp="top.ICEcoder.mouseDown=false" onMouseMove="if(top.ICEcoder) {top.ICEcoder.getMouseXY(event,'top');top.ICEcoder.canResizeFilesW()}">
<head>
<title>ICEcoder <?php echo $ICEcoder["versionNo"];?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" type="text/css" href="lib/coder.css">
<script>
theme = "<?php echo $ICEcoder["theme"]=="default" ? 'icecoder' : $ICEcoder["theme"];?>";
tabsIndent = <?php echo $ICEcoder["tabsIndent"] ? 'true' : 'false';?>;
openLastFiles = <?php echo $ICEcoder["openLastFiles"] ? 'true' : 'false';?>;
tabWidth = <?php echo $ICEcoder["tabWidth"]; ?>;
<?php
echo 'iceRoot = "'.$ICEcoder["root"].'";'.PHP_EOL;
?>
window.onbeforeunload = function() {
	for (var i=0; i<=top.ICEcoder.changedContent.length; i++) {
		if (top.ICEcoder.changedContent[i]==1) {
			return "You have some unsaved changes.";
		}
	}
}

previousFiles = [<?php
	if ($ICEcoder["previousFiles"]!="" && $_SESSION['userLevel'] == 10) {
		$openFilesArray = explode(",",$ICEcoder["previousFiles"]);
		for ($i=0;$i<count($openFilesArray);$i++) {
			echo "'".$openFilesArray[$i]."'";
			if ($i<count($openFilesArray)-1) {echo ",";};
		}
	}
?>];
</script>
<script language="JavaScript" src="lib/coder.js"></script>
</head>

<body onLoad="ICEcoder.init(<?php if ($_SESSION['userLevel'] == 10) {echo "'login'";} ?>)<?php echo $updateMsg;?><?php echo $onLoadExtras;?>" onResize="ICEcoder.setLayout()" onKeyDown="return ICEcoder.interceptKeys('coder', event);" onKeyUp="parent.ICEcoder.resetKeys(event);">

<div id="blackMask" class="blackMask" onClick="ICEcoder.showHide('hide',this)">
	<div class="popupVCenter">
		<div class="popup" id="mediaContainer"></div>
	</div>
</div>

<div id="loadingMask" class="blackMask" style="visibility: visible">
	<span class="progressBar" id="progressBar" style="-webkit-animation:fullexpand 10s ease-out; -moz-animation:fullexpand 10s ease-out"></span>
	<div class="popupVCenter">
		<div class="popup">
			<div class="circleOutside"></div>
			<div class="circleInside"></div>
			&nbsp;&nbsp;&nbsp;working...
		</div>
	</div>
</div>

<div id="fileMenu" class="fileMenu" onMouseOver="ICEcoder.changeFilesW('expand')" onMouseOut="ICEcoder.changeFilesW('contract');this.style.display='none'">
	<span id="folderMenuItems">
		<a href="javascript:top.ICEcoder.newFile()" onMouseOver="document.getElementById('fileMenu').style.display='inline-block'">New File</a>
		<a href="javascript:top.ICEcoder.newFolder()" onMouseOver="document.getElementById('fileMenu').style.display='inline-block'">New Folder</a>
	</span>
	<a href="javascript:top.ICEcoder.deleteFile(top.ICEcoder.rightClickedFile)" onMouseOver="document.getElementById('fileMenu').style.display='inline-block'">Delete</a>
	<span id="singleFileMenuItems">
		<a href="javascript:top.ICEcoder.renameFile(top.ICEcoder.rightClickedFile)" onMouseOver="document.getElementById('fileMenu').style.display='inline-block'">Rename</a>
		<a href="javascript:window.open(top.ICEcoder.rightClickedFile)" onMouseOver="document.getElementById('fileMenu').style.display='inline-block'">View Webpage</a>
	</span>
	<a href="javascript:top.ICEcoder.zipIt(top.ICEcoder.rightClickedFile)" onMouseOver="document.getElementById('fileMenu').style.display='inline-block'">Zip It!</a>
	<a href="javascript:top.ICEcoder.propertiesScreen(top.ICEcoder.rightClickedFile)" onMouseOver="document.getElementById('fileMenu').style.display='inline-block'">Properties</a>
</div>

<div id="header" class="header" onContextMenu="return false">
	<div class="plugins" id="pluginsContainer">
	<?php echo $pluginsDisplay; ?>
	</div>
	<div class="version"><?php echo $ICEcoder["versionNo"];?></div><img src="images/full-screen.gif" id="screenMode" class="screenModeIcon" onClick="top.ICEcoder.fullScreenSwitcher()">
	<img src="images/ice-coder.png" class="logo" onClick="ICEcoder.helpScreen()" onContextMenu="ICEcoder.settingsScreen()">
</div>

<div id="files" class="files" onMouseOver="ICEcoder.changeFilesW('expand')" onMouseOut="ICEcoder.changeFilesW('contract'); top.document.getElementById('fileMenu').style.display='none';">
	<div class="account" id="account">
		<div class="accountLoginContainer" id="accountLoginContainer">
			<div class="accountLogin" id="accountLogin">
				<form name="login" action="lib/settings.php" method="POST" target="ff">
				<input type="password" name="loginPassword" class="accountPassword">
				<input type="submit" name="submit" value="Login" class="button">
				</form>
			</div>
		</div>
		<div class="accountOptions">
			<div title="Save" onClick="ICEcoder.fMIcon('save')" id="fMSave" style="width: 30px; height: 32px; opacity: 0.3"></div>
			<div title="Open" onClick="ICEcoder.fMIcon('open')" id="fMOpen" style="width: 25px; height: 32px; background-position: -32px -3px; margin: 3px 0 0 7px; opacity: 0.3"></div>
			<div title="New File" onClick="ICEcoder.fMIcon('newFile')" id="fMNewFile" style="width: 15px; height: 16px; background-position: -64px 0; margin: 8px 0 0 10px; opacity: 0.3"></div>
			<div title="New Folder" onClick="ICEcoder.fMIcon('newFolder')" id="fMNewFolder" style="width: 20px; height: 16px; background-position: -80px 0; margin: 8px 0 0 5px; opacity: 0.3"></div>
			<div title="Delete" onClick="ICEcoder.fMIcon('delete')" id="fMDelete" style="width: 16px; height: 16px; background-position: -100px 0; margin: 8px 0 0 5px; opacity: 0.3"></div>
			<div title="Rename" onClick="ICEcoder.fMIcon('rename')" id="fMRename" style="width: 16px; height: 16px; background-position: -116px 0; margin: 8px 0 0 5px; opacity: 0.3"></div>
			<div title="View" onClick="ICEcoder.fMIcon('view')" id="fMView" style="width: 16px; height: 16px; background-position: -132px 0; margin: 8px 0 0 5px; opacity: 0.3"></div>

			<div title="Lock" onClick="ICEcoder.lockUnlockNav()" id="fmLock" style="position: relative; margin-left: 208px; margin-top: -27px; width: 12px; height: 16px; background-position: -64px -16px; z-index: 1"></div>
		</div>
	</div>
	<iframe id="filesFrame" class="frame" name="ff" src="files.php" style="opacity: 0" onLoad="this.style.opacity='1'"></iframe>
	<div class="serverMessage" id="serverMessage"></div>
</div>

<div id="editor" class="editor">
	<div id="tabsBar" class="tabsBar" onContextMenu="return false">
		<?php
		for ($i=1;$i<=10;$i++) {
			echo '<div id="tab'.$i.'" class="tab" draggable="true" onClick="if(ICEcoder.canSwitchTabs) {ICEcoder.switchTab('.$i.')} else {ICEcoder.canSwitchTabs=true}"></div>';
		}
		?><div class="newTab" onClick="ICEcoder.newTab()"><img src="images/nav-new.png"></div>
	</div>
	<div id="findBar" class="findBar" onContextMenu="return false">
		<form name="findAndReplace">
			<div class="findReplace">
				<div class="findText">Find</div>
				<input type="text" name="find" value="" id="find" class="textbox find" onKeyUp="ICEcoder.findReplace('find',true,false)">
				
				<select name="connector" onChange="ICEcoder.findReplaceOptions()">
				<option>in</option>
				<option>and</option>
				</select>
				<div class="replaceText" id="rText" style="display: none">
					<select name="replaceAction" class="replaceAction">
					<option>replace</option>
					<option>replace all</option>
					</select>
					 with
				</div>
				<input type="text" name="replace" value="" id="replace" class="textbox replace" style="display: none">
				<div class="targetText" id="rTarget" style="display: none">in</div>
				<select name="target" onChange="ICEcoder.updateResultsDisplay(this.value=='this document' ? 'show' : 'hide')">
				<option>this document</option>
				<option>open documents</option>
				<option>all files</option>
				<option>all filenames</option>
				</select>
				<input type="button" name="submit" value="&gt;&gt;" class="submit" onClick="ICEcoder.findReplace('findReplace',false,true)">
				<div class="results" id="results"></div>
			</div>
		</form>
		<form onSubmit="return ICEcoder.goToLine()">
		<div class="codeAssist"><input type="checkbox" name="codeAssist" id="codeAssist" checked onClick="top.ICEcoder.codeAssistToggle()">Code Assist</div>
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

<script>
ICEcoder.initAliases();
ICEcoder.setLayout('dontSetEditor');
</script>

</body>

</html>