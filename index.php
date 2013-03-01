<?php include("lib/settings.php");

// Check IP permissions
if (!in_array($_SERVER["REMOTE_ADDR"], $_SESSION['allowedIPs']) && !in_array("*", $_SESSION['allowedIPs'])) {
	header('Location: /');
};

$updateMsg = '';
// Check for updates
if ($ICEcoder["checkUpdates"]) {
	$icv = explode("\n",file_get_contents("http://icecoder.net/latest-version?thisVersion=".$ICEcoder["versionNo"]));
	$icv = $icv[0];
	if ($ICEcoder["versionNo"]<$icv) {
		$updateMsg = ";top.ICEcoder.dataMessage('<b>UPDATE INFO:</b> ICEcoder v ".$icv." now available. (Your version is v ".$ICEcoder["versionNo"]."). Get it free from <a href=\\'http://icecoder.net\\' target=\\'_blank\\' style=\\'color:#ddd\\'>icecoder.net</a>');";
	}
}
?>
<!DOCTYPE html>
<html onMouseDown="top.ICEcoder.mouseDown=true" onMouseUp="top.ICEcoder.mouseDown=false;top.ICEcoder.tabDragEnd()" onMouseMove="if(top.ICEcoder) {top.ICEcoder.getMouseXY(event,'top');top.ICEcoder.canResizeFilesW()}">
<head>
<title>ICEcoder v <?php echo $ICEcoder["versionNo"];?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" type="text/css" href="lib/ice-coder.css">
<link rel="icon" type="image/png" href="favicon.png">
<script>
iceRoot = "<?php echo $ICEcoder['root']; ?>";

window.onbeforeunload = function() {
	if (top.ICEcoder.changedContent.indexOf(1)>-1) {
		return "You have some unsaved changes.";
	}
}
</script>
<script language="JavaScript" src="lib/ice-coder.js"></script>
<?php
if (file_exists(dirname(__FILE__)."/plugins/jshint/jshint.js")) {
	echo '<script src="plugins/jshint/jshint.js"></script>';
}
?>
</head>

<body onLoad="<?php
	echo 'top.ICEcoder.previousFiles = [';
	if ($ICEcoder["previousFiles"]!="") {
		$openFilesArray = explode(",",$ICEcoder["previousFiles"]);
		echo "'".implode("','",$openFilesArray)."'";
	}
	echo "];top.ICEcoder.theme = '";
	echo $ICEcoder["theme"]=="default" ? 'icecoder' : $ICEcoder["theme"];
	echo "'";
	echo ';top.ICEcoder.tabsIndent = ';
	echo $ICEcoder["tabsIndent"] ? 'true' : 'false';
	echo ';top.ICEcoder.openLastFiles = ';
	echo $ICEcoder["openLastFiles"] ? 'true' : 'false';
	echo ';top.ICEcoder.lineWrapping = ';
	echo $ICEcoder["lineWrapping"] ? 'true' : 'false';
	echo ';top.ICEcoder.tabWidth = ';
	echo $ICEcoder["tabWidth"];
?>;ICEcoder.init()<?php echo $updateMsg.$onLoadExtras;?>" onResize="ICEcoder.setLayout()" onKeyDown="return ICEcoder.interceptKeys('coder',event);" onKeyUp="parent.ICEcoder.resetKeys(event);">

<div id="blackMask" class="blackMask" onClick="ICEcoder.showHide('hide',this)" onContextMenu="return false">
	<div class="popupVCenter">
		<div class="popup" id="mediaContainer"></div>
	</div>
</div>

<div id="loadingMask" class="blackMask" style="visibility: visible" onContextMenu="return false">
	<span class="progressBar" id="progressBar" style="-webkit-animation:fullexpand 10s ease-out; -moz-animation:fullexpand 10s ease-out"></span>
	<div class="popupVCenter">
		<div class="popup">
			<div class="circleOutside"></div>
			<div class="circleInside"></div>
			&nbsp;&nbsp;&nbsp;working...
		</div>
	</div>
</div>

<div id="fileMenu" class="fileMenu" onMouseOver="ICEcoder.changeFilesW('expand')" onMouseOut="ICEcoder.changeFilesW('contract');this.style.display='none'" onContextMenu="return false">
	<span id="folderMenuItems">
		<a href="javascript:top.ICEcoder.newFile()" onMouseOver="ICEcoder.showFileMenu()">New File</a>
		<a href="javascript:top.ICEcoder.newFolder()" onMouseOver="ICEcoder.showFileMenu()">New Folder</a>
		<a href="javascript:top.ICEcoder.pasteFile(top.ICEcoder.rightClickedFile)" onMouseOver="ICEcoder.showFileMenu()" id="fmMenuPasteOption" style="display: none">Paste</a>
		<a href="javascript:top.ICEcoder.uploadFilesSelect(top.ICEcoder.rightClickedFile)" onMouseOver="ICEcoder.showFileMenu()">Upload File(s)</a>
		<div style="display: none">
			<form enctype="multipart/form-data" id="uploadFilesForm" action="lib/file-control.php?action=upload&file=/uploaded" method="POST" target="fileControl">
				<input type="hidden" name="folder" id="uploadDir" value="/">
				<input type="file" name="filesInput[]" id="fileInput" onchange="top.ICEcoder.uploadFilesSubmit(this)" multiple>
				<input type="submit" value="Upload File">
			</form>
		</div>
	</span>
	<a href="javascript:top.ICEcoder.deleteFile(top.ICEcoder.rightClickedFile)" onMouseOver="ICEcoder.showFileMenu()">Delete</a>
	<span id="singleFileMenuItems">
		<a href="javascript:top.ICEcoder.copyFile(top.ICEcoder.rightClickedFile)" onMouseOver="ICEcoder.showFileMenu()">Copy</a>
		<a href="javascript:top.ICEcoder.renameFile(top.ICEcoder.rightClickedFile)" onMouseOver="ICEcoder.showFileMenu()">Rename</a>
		<a href="javascript:window.open(top.ICEcoder.rightClickedFile.replace(/\|/g,'/'))" onMouseOver="ICEcoder.showFileMenu()">View Webpage</a>
	</span>
	<a href="javascript:top.ICEcoder.zipIt(top.ICEcoder.rightClickedFile)" onMouseOver="ICEcoder.showFileMenu()">Zip It!</a>
	<a href="javascript:top.ICEcoder.propertiesScreen(top.ICEcoder.rightClickedFile)" onMouseOver="ICEcoder.showFileMenu()">Properties</a>
</div>

<div id="header" class="header" onContextMenu="return false">
	<div class="plugins" id="pluginsContainer">
	<?php echo $pluginsDisplay; ?>
	</div>
	<div class="version"><a href="javascript:top:ICEcoder.logout()">logout</a> : v <?php echo $ICEcoder["versionNo"];?></div><img src="images/full-screen.gif" id="screenMode" class="screenModeIcon" onClick="top.ICEcoder.fullScreenSwitcher()">
	<img src="images/ice-coder.png" class="logo" onClick="ICEcoder.helpScreen()" onContextMenu="ICEcoder.settingsScreen()">
</div>

<div id="files" class="files" onMouseOver="ICEcoder.changeFilesW('expand')" onMouseOut="ICEcoder.changeFilesW('contract'); top.document.getElementById('fileMenu').style.display='none';">
	<div class="account" id="account">
		<div class="accountOptions">
			<div title="Save" onClick="ICEcoder.fMIcon('save')" id="fMSave" class="save"></div>
			<div title="Open" onClick="ICEcoder.fMIcon('open')" id="fMOpen" class="open"></div>
			<div title="New File" onClick="ICEcoder.fMIcon('newFile')" id="fMNewFile" class="newFile"></div>
			<div title="New Folder" onClick="ICEcoder.fMIcon('newFolder')" id="fMNewFolder" class="newFolder"></div>
			<div title="Delete" onClick="ICEcoder.fMIcon('delete')" id="fMDelete" class="delete"></div>
			<div title="Rename" onClick="ICEcoder.fMIcon('rename')" id="fMRename" class="rename"></div>
			<div title="View" onClick="ICEcoder.fMIcon('view')" id="fMView" class="view"></div>

			<div title="Lock" onClick="ICEcoder.lockUnlockNav()" id="fmLock" class="lock"></div>
		</div>
	</div>
	<iframe id="filesFrame" class="frame" name="ff" src="files.php" style="opacity: 0" onLoad="this.style.opacity='1';this.contentWindow.onscroll=function(){top.ICEcoder.mouseDown=false}"></iframe>
	<div class="serverMessage" id="serverMessage"></div>
</div>

<div id="editor" class="editor">
	<div id="tabsBar" class="tabsBar" onContextMenu="return false">
		<a nohref onClick="top.ICEcoder.closeAllTabs()"><img src="images/nav-close.gif" class="closeAllTabs" title="Close all tabs"></a>
		<a nohref onClick="top.ICEcoder.alphaTabs()"><img src="images/nav-alpha.png" class="alphaTabs" title="Alphabetize tabs"></a>
		<?php
		for ($i=1;$i<=100;$i++) {
			echo '<div id="tab'.$i.'" class="tab" onMouseDown="ICEcoder.canSwitchTabs ? ICEcoder.switchTab(parseInt(this.id.slice(3),10)) : ICEcoder.canSwitchTabs=true; thisColor=\'#000\'; ICEcoder.tabDragStart(parseInt(this.id.slice(3),10))" onMouseOver="thisColor=this.style.color;this.style.color=\'#000\'" onMouseOut="this.style.color=thisColor"></div>';
		}
		?><div class="newTab" onClick="ICEcoder.newTab()" id="newTab"><img src="images/nav-new.png"></div>
	</div>
	<div id="findBar" class="findBar" onContextMenu="return false">
		<form name="findAndReplace" onSubmit="ICEcoder.findReplace('findReplace',false,true);return false">
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
				<input type="submit" name="submit" value="&gt;&gt;" class="submit">
				<div class="results" id="results"></div>
			</div>
		</form>
		<form onSubmit="return ICEcoder.goToLine()">
			<div class="codeAssist"><input type="checkbox" name="codeAssist" id="codeAssist" checked onClick="top.ICEcoder.codeAssistToggle()">Code Assist</div>
			<div class="goLine">Go to Line<input type="text" name="goToLine" value="" id="goToLineNo" class="textbox goToLine">
		</form>
	</div>
	<iframe name="contentFrame" id="content" src="editor.php" class="code"></iframe>
</div>

<div class="footer" id="footer" onContextMenu="return false">
	<div class="nesting" id="nestValid">Nesting OK</div>
	<div class="nestLoc">cursor nest location</div>
	<div class="nestDisplay" id="nestDisplay"></div>
	<div class="charDisplay" id="charDisplay"><span id="char"></span></div>
</div>

<script>
ICEcoder.initAliases();
ICEcoder.setLayout('dontSetEditor');
</script>

</body>

</html>