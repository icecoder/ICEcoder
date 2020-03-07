<?php
include("lib/headers.php");
include("lib/settings.php");
$t = $text['index'];

$updateMsg = '';
// Check for updates
if ($ICEcoder["checkUpdates"]) {
	$icv_url = "https://icecoder.net/latest-version?thisVersion=".$ICEcoder["versionNo"];
	$icvData = getData($icv_url,'curl',false,5);
	if ($icvData == "no data") {
		$icvData = "1.0\nICEcoder version placeholder";
	}
	$icvInfo = str_replace("\n", "", $icvData);
	$icv = preg_match("/^[0-9.]+/", $icvInfo, $matches);
	$icv = floatval($matches[0]);
	$icvInfo = [
		0 => $icv,
		1 => substr($icvInfo, strlen($matches[0]))
	];
	$icvI = str_replace('"','\\\'',$icvInfo[1]);
	$thisV = $ICEcoder["versionNo"];
	if (strpos($thisV,"beta")>-1 && !strpos($icv,"beta") && str_replace(" beta","",$thisV) == $icv) {$thisV-=0.1;};
	if ($thisV<$icv) {
		$updateMsg = ";ICEcoder.dataMessage('<b>".$t['UPDATE INFO'].":</b> ICEcoder v ".$icv." ".$t['now available'].". (".$t['Your version is']." v ".$ICEcoder["versionNo"].").<br><br><a onclick=\\'ICEcoder.update()\\' style=\\'color:#fff; background: #b00; padding: 5px; text-decoration: none; cursor: pointer\\'>".$t['Update now']."</a><br><br>".$icvI."');";
	}
}

$isMac = strpos($_SERVER['HTTP_USER_AGENT'], "Macintosh")>-1 ? true : false;
?>
<!DOCTYPE html>
<html onMouseDown="ICEcoder.mouseDown=true; ICEcoder.resetAutoLogoutTimer();" onMouseUp="ICEcoder.mouseDown=false; ICEcoder.resetAutoLogoutTimer(); ICEcoder.mouseDownInCM=false; if (!ICEcoder.overCloseLink) {ICEcoder.tabDragEnd()}" onMouseMove="if(ICEcoder) {ICEcoder.getMouseXY(event,'top'); ICEcoder.resetAutoLogoutTimer(); ICEcoder.canResizeFilesW()}" onMouseWheel="ICEcoder.resetAutoLogoutTimer(); if (ICEcoder.getcMInstance() && !ICEcoder.getcMInstance().hasFocus() && !ICEcoder.getcMdiffInstance().hasFocus()) {event.wheelDelta > 0 ? ICEcoder.nextTab() : ICEcoder.previousTab();}">
<head>
<title>ICEcoder v <?php echo $ICEcoder["versionNo"];?></title>
<!--Updated via settings so must remain 1st stylesheet//-->
<style>
	#tabsBar.tabsBar .tab { font-size: <?php echo $ICEcoder["fontSize"];?>; }
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<meta name="viewport" content="width=device-width, initial-scale=0.5, user-scalable=no">
<link rel="stylesheet" type="text/css" href="<?php echo $iceURLPath;?>/lib/ice-coder.css?microtime=<?php echo microtime(true);?>">
<link rel="stylesheet" href="<?php echo $iceURLPath . "/";
if ($ICEcoder["theme"]=="default") {echo 'lib/editor.css';} else {echo $ICEcoder["codeMirrorDir"].'/theme/'.$ICEcoder["theme"].'.css';};
echo "?microtime=".microtime(true);
?>">
<link rel="icon" type="image/png" href="favicon.png">
<script>
iceRoot = "<?php echo $ICEcoder['root']; ?>";

window.onbeforeunload = function() {
	if(ICEcoder.autoLogoutTimer < ICEcoder.autoLogoutMins*60) {
		for(var i=1;i<=ICEcoder.savedPoints.length;i++) {
			if (ICEcoder.savedPoints[i-1]!=ICEcoder.getcMInstance(i).changeGeneration()) {
				return "<?php echo $t['You have some...'];?>.";
			}
		}
		return "<?php echo $t['Are you sure...'];?>";
	}
}

t = {
<?php
// Load the lang array for what's in the JS file
$t = $text['ice-coder'];
$tOutput = "";
foreach ($t as $key => $value) {
	$tOutput .= '"'.$key.'" : "'.$value.'",'.PHP_EOL;
}
echo rtrim($tOutput,",".PHP_EOL).PHP_EOL;

// Back to the lang array for index
$t = $text['index'];
?>
}
</script>
<script language="JavaScript" src="<?php echo $iceURLPath;?>/lib/ice-coder<?php if (!$ICEcoder['devMode']) {echo '.min';};?>.js?microtime=<?php echo microtime(true);?>"></script>
<script src="<?php echo $iceURLPath;?>/lib/mmd.js?microtime=<?php echo microtime(true);?>"></script>
<script src="<?php echo $iceURLPath;?>/farbtastic/farbtastic.js?microtime=<?php echo microtime(true);?>"></script>
<script src="<?php echo $iceURLPath;?>/lib/difflib.js?microtime=<?php echo microtime(true);?>"></script>
<link rel="stylesheet" href="<?php echo $iceURLPath;?>/farbtastic/farbtastic.css?microtime=<?php echo microtime(true);?>" type="text/css">
</head>

<body onLoad="<?php
	echo "ICEcoder.versionNo = '".$ICEcoder["versionNo"]."';".
		'ICEcoder.previousFiles = [';
		if ($ICEcoder["previousFiles"]!="") {
			$openFilesArray = explode(",",$ICEcoder["previousFiles"]);
			echo "'".implode("','",$openFilesArray)."'";
		}
	echo "];";
	echo "ICEcoder.theme = '".($ICEcoder["theme"]=="default" ? 'icecoder' : $ICEcoder["theme"])."';".
		"ICEcoder.autoLogoutMins = ".$ICEcoder["autoLogoutMins"].";".
		"ICEcoder.fontSize = '".$ICEcoder["fontSize"]."';".
		"ICEcoder.openLastFiles = ".($ICEcoder["openLastFiles"] ? 'true' : 'false').";".
		"ICEcoder.updateDiffOnSave = ".($ICEcoder["updateDiffOnSave"] ? 'true' : 'false').";".
		"ICEcoder.languageUser = '".$ICEcoder["languageUser"]."';".
		"ICEcoder.codeAssist = ".($ICEcoder["codeAssist"] ? 'true' : 'false').";".
		"ICEcoder.lockedNav = ".($ICEcoder["lockedNav"] ? 'true' : 'false').";".
		"ICEcoder.lineWrapping = ".($ICEcoder["lineWrapping"] ? 'true' : 'false').";".
		"ICEcoder.lineNumbers = ".($ICEcoder["lineNumbers"] ? 'true' : 'false').";".
		"ICEcoder.showTrailingSpace = ".($ICEcoder["showTrailingSpace"] ? 'true' : 'false').";".
		"ICEcoder.matchBrackets = ".($ICEcoder["matchBrackets"] ? 'true' : 'false').";".
		"ICEcoder.autoCloseTags = ".($ICEcoder["autoCloseTags"] ? 'true' : 'false').";".
		"ICEcoder.autoCloseBrackets = ".($ICEcoder["autoCloseBrackets"] ? 'true' : 'false').";".
		"ICEcoder.indentWithTabs = ".($ICEcoder["indentWithTabs"] ? 'true' : 'false').";".
		"ICEcoder.indentAuto = ".($ICEcoder["indentAuto"] ? 'true' : 'false').";".
		"ICEcoder.indentSize = ".$ICEcoder["indentSize"].";".
		"ICEcoder.demoMode = ".($ICEcoder["demoMode"] ? 'true' : 'false').";".
		"ICEcoder.tagWrapperCommand = '".$ICEcoder["tagWrapperCommand"]."';".
		"ICEcoder.autoComplete = '".$ICEcoder["autoComplete"]."';".
		"ICEcoder.bugFilePaths = ['".implode("','",$ICEcoder["bugFilePaths"])."'];".
		"ICEcoder.bugFileCheckTimer = ".$ICEcoder["bugFileCheckTimer"].";".
		"ICEcoder.bugFileMaxLines = ".$ICEcoder["bugFileMaxLines"].";".
		"ICEcoder.fileDirResOutput = '".$ICEcoder["fileDirResOutput"]."';".
		"ICEcoder.newDirPerms = ".$ICEcoder["newDirPerms"].";".
		"ICEcoder.newFilePerms = ".$ICEcoder["newFilePerms"].";";
		if($ICEcoder["githubAuthToken"] != "") {
			$_SESSION['githubAuthToken'] = $ICEcoder["githubAuthToken"];
			echo "ICEcoder.githubAuthTokenSet = true;";
		}
		echo "ICEcoder.csrf = '".$_SESSION["csrf"]."';";
?>ICEcoder.init()<?php echo $updateMsg.$onLoadExtras;?>;ICEcoder.content.style.visibility='visible';ICEcoder.filesFrame.contentWindow.frames['processControl'].location.href = iceLoc+'/processes/on-load.php';<?php if(isset($_GET["display"]) && $_GET["display"] == "updated") {echo "ICEcoder.updated();";};?>" onResize="ICEcoder.setLayout()" onKeyDown="return ICEcoder.interceptKeys('coder',event);" onKeyUp="ICEcoder.resetKeys(event);" onBlur="ICEcoder.resetKeys(event);">

<div id="blackMask" class="blackMask" onClick="if (!ICEcoder.overPopup) {ICEcoder.showHide('hide',this)}" onContextMenu="return false">
	<div class="popupVCenter">
		<div class="popup" id="mediaContainer"></div>
	</div>
	<div class="floatingContainer" id="floatingContainer"></div>
</div>

<div id="loadingMask" class="blackMask" style="visibility: visible" onContextMenu="return false">
	<div class="popupVCenter">
		<div class="popup">
			<div class="spinner"></div>
			<?php echo $t['working'];?>...
		</div>
	</div>
</div>

<div id="plugins" class="plugins" style="<?php echo $ICEcoder["pluginPanelAligned"];?>: 0" onMouseOver="ICEcoder.showHidePlugins('show')" onMouseOut="ICEcoder.showHidePlugins('hide')" onClick="ICEcoder.showHidePlugins('hide')">
	<div style="padding: 15px">
		<a nohref onClick="ICEcoder.showColorPicker(document.getElementById('color') ? document.getElementById('color').value : '#123456')" title="Farbtastic
<?php echo $t['Color picker'];?>"><img src="<?php echo $iceURLPath;?>/images/color-picker.png" style="cursor: pointer" alt="Color Picker"></a><br><br>
		<div id="pluginsOptional"><?php echo $pluginsDisplay; ?></div>
		<a nohref onclick="ICEcoder.pluginsManager()" title="<?php echo $t['Plugins Manager'];?>" style="color: #fff; cursor: pointer">+ / -</a>
	</div>
</div>

<div id="fileMenu" class="fileMenu" onMouseOver="ICEcoder.changeFilesW('expand')" onMouseOut="ICEcoder.changeFilesW('contract');ICEcoder.hideFileMenu()" style="opacity: 0" onContextMenu="return false">
	<span id="folderMenuItems">
		<a href="javascript:ICEcoder.newFile()" onMouseOver="ICEcoder.showFileMenu()"><?php echo $t['New File'];?></a>
		<a href="javascript:ICEcoder.newFolder()" onMouseOver="ICEcoder.showFileMenu()"><?php echo $t['New Folder'];?></a>
		<div onMouseOver="ICEcoder.showFileMenu()" style="padding: 2px 0"><hr></div>
		<a href="javascript:ICEcoder.uploadFilesSelect(ICEcoder.selectedFiles[ICEcoder.selectedFiles.length-1])" onMouseOver="ICEcoder.showFileMenu()"><?php echo $t['Upload File(s)'];?></a>
		<div style="display: none">
			<form enctype="multipart/form-data" id="uploadFilesForm" action="<?php echo $iceURLPath;?>/lib/file-control-xhr.php?action=upload&file=/uploaded" method="POST" target="fileControl">
				<input type="hidden" name="folder" id="uploadDir" value="/">
				<input type="file" name="filesInput[]" id="fileInput" onchange="ICEcoder.uploadFilesSubmit(this)" multiple>
				<input type="submit" value="Upload File">
				<input type="hidden" name="csrf" value="<?php echo $_SESSION["csrf"]; ?>">
			</form>
		</div>
		<a href="javascript:ICEcoder.pasteFiles(ICEcoder.selectedFiles[ICEcoder.selectedFiles.length-1])" onMouseOver="ICEcoder.showFileMenu()" id="fmMenuPasteOption" style="display: none"><?php echo $t['Paste'];?></a>
		<div onMouseOver="ICEcoder.showFileMenu()" style="padding: 2px 0"><hr></div>
	</span>
	<a href="javascript:ICEcoder.openFilesFromList(ICEcoder.selectedFiles)" onMouseOver="ICEcoder.showFileMenu()"><?php echo $t['Open'];?></a>
	<a href="javascript:ICEcoder.copyFiles(ICEcoder.selectedFiles)" onMouseOver="ICEcoder.showFileMenu()"><?php echo $t['Copy'];?></a>
	<a href="javascript:ICEcoder.duplicateFiles(ICEcoder.selectedFiles)" onMouseOver="ICEcoder.showFileMenu()"><?php echo $t['Duplicate'];?></a>
	<a href="javascript:ICEcoder.deleteFiles(ICEcoder.selectedFiles)" onMouseOver="ICEcoder.showFileMenu()"><?php echo $t['Delete'];?></a>
	<span id="singleFileMenuItems">
		<a href="javascript:ICEcoder.renameFile(ICEcoder.selectedFiles[ICEcoder.selectedFiles.length-1])" onMouseOver="ICEcoder.showFileMenu()"><?php echo $t['Rename'];?></a>
		<div onMouseOver="ICEcoder.showFileMenu()" style="padding: 2px 0"><hr></div>
		<a nohref onClick="window.open('//<?php echo $_SERVER['HTTP_HOST'];?>' + iceRoot + ICEcoder.selectedFiles[ICEcoder.selectedFiles.length-1].replace(/\|/g,'/'))" onMouseOver="ICEcoder.showFileMenu()" style="cursor: pointer"><?php echo $t['View Webpage'];?></a>
	</span>
	<div onMouseOver="ICEcoder.showFileMenu()" style="padding: 2px 0"><hr></div>
	<?php
	if (file_exists(dirname(__FILE__)."/plugins/zip-it/index.php")) {
		echo '<a href="javascript:ICEcoder.zipIt(ICEcoder.selectedFiles[ICEcoder.selectedFiles.length-1])" onMouseOver="ICEcoder.showFileMenu()">Zip It!</a>'.PHP_EOL;
	};
	?>
	<a href="javascript:ICEcoder.downloadFile(ICEcoder.selectedFiles[ICEcoder.selectedFiles.length-1])" onMouseOver="ICEcoder.showFileMenu()"><?php echo $t['Download'];?></a>
	<div onMouseOver="ICEcoder.showFileMenu()" style="padding: 2px 0"><hr></div>
	<a href="javascript:ICEcoder.propertiesScreen(ICEcoder.selectedFiles[ICEcoder.selectedFiles.length-1])" onMouseOver="ICEcoder.showFileMenu()"><?php echo $t['Properties'];?></a>
</div>

<div id="header" class="header" onContextMenu="return false"></div>

<div id="files" class="files" onMouseOver="ICEcoder.changeFilesW('expand')" onMouseOut="ICEcoder.changeFilesW('contract'); ICEcoder.hideFileMenu();" onContextMenu="return false">
	<div id="fileNav" class="fileNav">
		<ul>
			<li><a nohref onclick="ICEcoder.canShowFMNav=true;ICEcoder.showHideFileNav('show','optionsFile')" onmouseover="if(ICEcoder.canShowFMNav) {ICEcoder.showHideFileNav('show','optionsFile')}" id="optionsFileNav"><?php echo $t['File'];?></a></li>
			<li><a nohref onclick="ICEcoder.canShowFMNav=true;ICEcoder.showHideFileNav('show','optionsEdit')" onmouseover="if(ICEcoder.canShowFMNav) {ICEcoder.showHideFileNav('show','optionsEdit')}" id="optionsEditNav"><?php echo $t['Edit'];?></a></li>
			<li><a nohref onclick="ICEcoder.canShowFMNav=true;ICEcoder.showHideFileNav('show','optionsSource')" onmouseover="if(ICEcoder.canShowFMNav) {ICEcoder.showHideFileNav('show','optionsSource')}" id="optionsSourceNav"><?php echo $t['Source'];?></a></li>
			<li><a nohref onclick="ICEcoder.canShowFMNav=true;ICEcoder.showHideFileNav('show','optionsHelp')" onmouseover="if(ICEcoder.canShowFMNav) {ICEcoder.showHideFileNav('show','optionsHelp')}" id="optionsHelpNav"><?php echo $t['Help'];?></a></li>
		</ul>
	</div>
	<div id="githubNav" class="githubNav">
		<div class="commit" id="githubNavCommit" onclick="ICEcoder.githubAction('commit')">Commit</div>
		<div class="selected" id="githubNavSelectedCount">Selected: 0</div>
		<div class="pull" id="githubNavPull" onclick="ICEcoder.githubAction('pull')">Pull</div>
	</div>
	<div class="options" id="fileOptions">
		<div id="optionsFile" class="optionsList" onmouseover="ICEcoder.showHideFileNav('show',this.id)" onmouseout="ICEcoder.showHideFileNav('hide',this.id);ICEcoder.canShowFMNav=false">
			<ul>
				<li><a nohref onClick="ICEcoder.newFile()"><?php echo $t['New File'];?>...</a></li>
				<li><a nohref onClick="ICEcoder.newFolder()"><?php echo $t['New Folder'];?>...</a></li>
				<li><a nohref onClick="ICEcoder.openPrompt()"><?php echo $t['Open'];?>...</a></li>
				<li><a nohref onClick="ICEcoder.saveFile()"><?php echo $t['Save'];?></a></li>
				<li><a nohref onclick="ICEcoder.saveFile('saveAs')"><?php echo $t['Save As'];?>...</a></li>
				<li><a nohref onclick="ICEcoder.openPreviewWindow()"><?php echo $t['Live Preview'];?></a></li>
				<li><a nohref onclick="ICEcoder.downloadFile(ICEcoder.selectedFiles[ICEcoder.selectedFiles.length-1])"><?php echo $t['Download'];?></a></li>
				<li><a nohref onclick="ICEcoder.copyFiles(ICEcoder.selectedFiles)"><?php echo $t['Copy'];?></a></li>
				<li><a nohref onclick="ICEcoder.pasteFiles(ICEcoder.selectedFiles[ICEcoder.selectedFiles.length-1])"><?php echo $t['Paste'];?></a></li>
				<li><a nohref onclick="ICEcoder.deleteFiles(ICEcoder.selectedFiles)"><?php echo $t['Delete'];?></a></li>
				<li><a nohref onclick="ICEcoder.duplicateFiles(ICEcoder.selectedFiles)"><?php echo $t['Duplicate'];?></a></li>
				<li><a nohref onclick="ICEcoder.renameFile(ICEcoder.selectedFiles[ICEcoder.selectedFiles.length-1])"><?php echo $t['Rename'];?></a></li>
				<li><a nohref onclick="ICEcoder.uploadFilesSelect(ICEcoder.selectedFiles[ICEcoder.selectedFiles.length-1])"><?php echo $t['Upload'];?>...</a></li>
				<li><a nohref onclick="ICEcoder.zipIt(ICEcoder.selectedFiles[ICEcoder.selectedFiles.length-1])"><?php echo $t['Zip'];?></a></li>
				<li><a nohref onclick="ICEcoder.propertiesScreen(ICEcoder.selectedFiles[ICEcoder.selectedFiles.length-1])"><?php echo $t['Properties'];?>...</a></li>
				<li><a nohref onClick="ICEcoder.printCode()"><?php echo $t['Print'];?>...</a></li>
				<li><a nohref onClick="ICEcoder.fullScreenSwitcher()"><?php echo $t['Fullscreen toggle'];?></a></li>
				<li><a nohref onClick="ICEcoder.logout()"><?php echo $t['Logout'];?></a></li>
			</ul>
		</div>
		<div id="optionsEdit" class="optionsList" onmouseover="ICEcoder.showHideFileNav('show',this.id)" onmouseout="ICEcoder.showHideFileNav('hide',this.id);ICEcoder.canShowFMNav=false">
			<ul>
				<li><a nohref onclick="ICEcoder.undo()"><?php echo $t['Undo'];?></a></li>
				<li><a nohref onclick="ICEcoder.redo()"><?php echo $t['Redo'];?></a></li>
				<li><a nohref onclick="ICEcoder.indent('more')"><?php echo $t['Indent more'];?></a></li>
				<li><a nohref onclick="ICEcoder.indent('less')"><?php echo $t['Indent less'];?></a></li>
				<li><a nohref onclick="ICEcoder.autocomplete()"><?php echo $t['Autocomplete'];?></a></li>
				<li><a nohref onclick="ICEcoder.lineCommentToggle()"><?php echo $t['Comment/Uncomment'];?></a></li>
				<li><a nohref onclick="ICEcoder.jumpToDefinition()"><?php echo $t['Jump to Definition'];?></a></li>
				<li><a nohref onClick="ICEcoder.settingsScreen()"><?php echo $t['Settings'];?></a></li>
			</ul>
		</div>
		<div id="optionsSource" class="optionsList" onmouseover="ICEcoder.showHideFileNav('show',this.id)" onmouseout="ICEcoder.showHideFileNav('hide',this.id);ICEcoder.canShowFMNav=false">
			<ul>
				<li><a nohref onclick="ICEcoder.goLocalhostRoot()">Localhost</a></li>
				<li><a nohref onclick="ICEcoder.ftpManager()">FTP</a></li>
				<li><a nohref onclick="ICEcoder.githubManager()">GitHub</a></li>
				<!--
				<li><a nohref onclick="ICEcoder.message('SVN integration coming soon')">SVN</a></li>
				<li><a nohref onclick="ICEcoder.message('Bitbucket integration coming soon\n\nCan you help with this? Get involved at icecoder.net')">Bitbucket</a></li>
				<li><a nohref onclick="ICEcoder.message('Amazon AWS integration coming soon\n\nCan you help with this? Get involved at icecoder.net')">Amazon AWS</a></li>
				<li><a nohref onclick="ICEcoder.message('Dropbox integration coming soon\n\nCan you help with this? Get involved at icecoder.net')">Dropbox</a></li>
				<li><a nohref onclick="ICEcoder.message('SSH integration coming soon\n\nCan you help with this? Get involved at icecoder.net')">SSH</a></li>
				//-->
			</ul>
		</div>
		<div id="optionsHelp" class="optionsList" onmouseover="ICEcoder.showHideFileNav('show',this.id)" onmouseout="ICEcoder.showHideFileNav('hide',this.id);ICEcoder.canShowFMNav=false">
			<ul>
				<li><a nohref onclick="ICEcoder.showManual('<?php echo $ICEcoder["versionNo"];?>')"><?php echo $t['Manual'];?></a></li>
				<li><a nohref onClick="ICEcoder.helpScreen()"><?php echo $t['Shortcuts'];?></a></li>
				<li><a nohref onclick="ICEcoder.searchForSelected()"><?php echo $t['Search for selected'];?></a></li>
				<li><a href="https://icecoder.net" target="_blank">ICEcoder <?php echo $t['website'];?></a></li>
			</ul>
		</div>
	</div>
	<iframe id="filesFrame" class="frame" name="ff" src="<?php echo $iceURLPath;?>/files.php" style="opacity: 0" onLoad="this.style.opacity='1';this.contentWindow.onscroll=function(){ICEcoder.mouseDown=false; ICEcoder.mouseDownInCM=false}"></iframe>
	<div class="serverMessage" id="serverMessage"></div>

    <div class="tools" id="tools">
        <div onclick="ICEcoder.toolShowHideToggle('terminal')">Terminal</div>
        <div onclick="ICEcoder.toolShowHideToggle('output')">Output</div>
        <div onclick="ICEcoder.toolShowHideToggle('database')">Database</div>
        <div onclick="ICEcoder.toolShowHideToggle('git')">Git</div>
    </div>
</div>

<div id="editor" class="editor">
	<div id="tabsBar" class="tabsBar" onContextMenu="return false">
		<a nohref onClick="ICEcoder.closeAllTabs()"><img src="<?php echo $iceURLPath;?>/images/nav-close-all.gif" class="closeAllTabs" title="<?php echo $t['Close all tabs'];?>"></a>
		<a nohref onClick="ICEcoder.alphaTabs()"><img src="<?php echo $iceURLPath;?>/images/nav-alpha.png" class="alphaTabs" title="<?php echo $t['Alphabetize tabs'];?>"></a>
		<?php
		for ($i=1;$i<=100;$i++) {
			echo '<div id="tab'.$i.'" class="tab" onMouseDown="ICEcoder.canSwitchTabs ? ICEcoder.switchTab(parseInt(this.id.slice(3),10)) : ICEcoder.canSwitchTabs=true; thisColor=ICEcoder.tabFGselected; if (!ICEcoder.overCloseLink) {ICEcoder.tabDragStart(parseInt(this.id.slice(3),10))}; if (event.button==1) {ICEcoder.closeTab(parseInt(this.id.slice(3),10)); return false};" onMouseOver="thisColor=this.style.color;this.style.color=ICEcoder.tabFGselected" onMouseOut="this.style.color=thisColor"></div>';
		}
		?><div class="newTab" onClick="ICEcoder.newTab()" id="newTab">+</div>
	</div>
	<div id="findBar" class="findBar" onContextMenu="return false">
		<form name="findAndReplace" onSubmit="ICEcoder.findReplace(document.getElementById('find').value,false,true);return false">
			<div class="findReplace">
				<div class="findText"><?php echo $t['Find'];?></div>
				<input type="text" name="find" value="" id="find" class="textbox find" onKeyUp="ICEcoder.findReplace(document.getElementById('find').value,true,false,event.keyCode == 27)">

				<div class="selectWrapper" style="width: 41px">
					<select name="connector" onChange="ICEcoder.findReplaceOptions()" style="width: 40px; margin-top: 4px">
					<option><?php echo $t['in'];?></option>
					<option><?php echo $t['and'];?></option>
					</select>
				</div>
				<div class="replaceText" id="rText" style="display: none">
					<div class="selectWrapper" style="width: 75px; overflow: visible">
						<select name="replaceAction" style="width: 72px; margin-top: -2px">
							<option><?php echo $t['replace'];?></option>
							<option><?php echo $t['replace all'];?></option>
						</select>
					</div>
					 with
				</div>
				<input type="text" name="replace" value="" id="replace" class="textbox replace" style="display: none">
				<div class="targetText" id="rTarget" style="display: none">in</div>
					<div class="selectWrapper" style="width: 104px">
						<select name="target" onChange="ICEcoder.updateResultsDisplay(this.value=='this document' ? 'show' : 'hide')" style="width: 101px; margin-top: 4px; margin-left: 2px">
							<option><?php echo $t['this document'];?></option>
							<option><?php echo $t['open documents'];?></option>
							<option><?php echo $t['all files'];?></option>
							<option><?php echo $t['all filenames'];?></option>
						</select>
					</div>
				<input type="submit" name="submit" id="findReplaceSubmit" value="&gt;&gt;" class="submit">
				<div class="results" id="results"></div>
			</div>
			<input type="hidden" name="csrf" value="<?php echo $_SESSION["csrf"]; ?>">
		</form>
		<form onSubmit="return ICEcoder.goToLine(get('goToLineNo').value, 0, false)">
			<div class="codeAssist" title="<?php echo $t['Turn on/off...'];?>">
				<input type="checkbox" name="codeAssist" id="codeAssist" class="codeAssistCheckbox" <?php if ($ICEcoder['codeAssist']) {echo 'checked ';};?>>
				<span class="codeAssistDisplay" id="codeAssistDisplay" style="background-position: <?php echo $ICEcoder['codeAssist'] ? "0" : "-16";?> 0" onClick="ICEcoder.codeAssistToggle()"></span> <?php echo $t['Code Assist'];?>
			</div>
			<div class="goLine"><?php echo $t['Go to Line'];?> <input type="text" name="goToLine" value="" id="goToLineNo" onkeyup="ICEcoder.goToLine(this.value, 0, true)" class="textbox goToLine">
			<div class="view" title="<?php echo $t['View'];?>" onClick="ICEcoder.openPreviewWindow()" id="fMView"></div>
			<div class="bug" title="<?php echo $t['Bug reporting not active'];?>" onClick="ICEcoder.openBugReport()" id="bugIcon"></div>
			<div class="minimapLink" onclick="ICEcoder.docExplorerShow('miniMap')"></div>
			<div class="functionClassListLink" onclick="ICEcoder.docExplorerShow('functionClassList')"></div>
			<input type="hidden" name="csrf" value="<?php echo $_SESSION["csrf"]; ?>">
		</form>
	</div>
	<iframe name="terminalFrame" id="terminal" src="<?php echo $iceURLPath;?>/terminal.php" class="terminal"></iframe>
	<pre id="output" class="output"><b>Output</b><br>via ICEcoder.output(message);<br><br></pre>
	<iframe name="databaseFrame" id="database" src="<?php echo $iceURLPath;?>/lib/database.php" class="database"></iframe>
	<div id="git" class="git" style="font-family: monospace"><?php
	if (file_exists($docRoot.$ICEcoderDir."/data/git-diff.php")) {
		echo "Looking for git status...";
	} else {
		echo "To provide git diff data to ICEcoder, please run...<br><br>sudo nohup php processes/system.php > data/nohup.log 2>&1 &<br><br>...to run as a background process";
	};?></div>
	<iframe name="contentFrame" id="content" src="<?php echo $iceURLPath;?>/editor.php" class="code" scrolling="no"></iframe>
</div>

<div class="footer" id="footer" onContextMenu="return false">
	<div class="nesting" id="nestValid"></div>
	<div class="versionsDisplay" id="versionsDisplay" onclick="ICEcoder.versionsScreen(ICEcoder.openFiles[ICEcoder.selectedTab-1].replace(/\//g,'|'))"></div>
	<div class="splitPaneControls" id="splitPaneControls"><div class="off" id="splitPaneControlsOff" title="<?php echo $t['Single pane'];?>" onclick="ICEcoder.setSplitPane('off')" style="opacity: 0.5"></div><div class="on" id="splitPaneControlsOn" title="<?php echo $t['Diff pane also'];?>" onclick="ICEcoder.setSplitPane('on')" style="opacity: 0.2"></div></div>
	<div class="splitPaneNames" id="splitPaneNamesMain">Main Pane</div>
	<div class="splitPaneNames" id="splitPaneNamesDiff">Diff Pane</div>
	<div class="byteDisplay" id="byteDisplay" style="display: none" onClick="ICEcoder.showDisplay('char')"></div>
	<div class="charDisplay" id="charDisplay" style="display: inline-block" onClick="ICEcoder.showDisplay('byte')"></div>
</div>

<div class="docExplorer" id="docExplorer">
	<div class="miniMap" id="miniMap" onmousedown="document.body.style.cursor='pointer'"><div class="miniMapContainer" id="miniMapContainer"></div><div class="miniMapContent" id="miniMapContent" onmousedown="ICEcoder.mouseDownMinimap = true; ICEcoder.jumpMinimapPositon(event.y, event.buttons)" onmousemove="ICEcoder.jumpMinimapPositon(event.y, event.buttons)" onmouseup="ICEcoder.mouseDownMinimap = false"></div></div>
	<div class="functionClassList" id="functionClassList"></div>
</div>

<div id="tooltip" class="tooltip" style="display: none"></div>

<script>
ICEcoder.initAliases();
ICEcoder.setLayout('dontSetEditor');
</script>

</body>

</html>
