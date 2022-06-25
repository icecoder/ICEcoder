<?php
use ICEcoder\ExtraProcesses;

include "lib/headers.php";
include "lib/settings.php";
$t = $text['index'];

$updateMsg = '';
// Check for updates
if (true === $ICEcoder["checkUpdates"]) {
	$icvURL = "https://icecoder.net/latest-version?thisVersion=" . $ICEcoder["versionNo"];
	$icvData = getData($icvURL, 'curl', false, 5);
	if ("" == $icvData) {
		$icvData = "1.0\nICEcoder version placeholder";
	}
	$icvInfo = str_replace("\n", "", $icvData);
	$icv = preg_match("/^[0-9.]+/", $icvInfo, $matches);
	$icv = $matches[0];
	$icvInfo = [
		0 => $icv,
		1 => substr($icvInfo, strlen($matches[0]))
	];
	$icvI = str_replace('"', '\\\'', $icvInfo[1]);
	$thisV = $ICEcoder["versionNo"];
	if (-1 < strpos($thisV, "beta") && false === strpos($icv, "beta") && str_replace(" beta", "", $thisV) === $icv) {
	    $thisV-=0.1;
	};
	if ($thisV < $icv) {
		$updateMsg =
            ";ICEcoder.dataMessage('<b>" . $t['UPDATE INFO'] .
            ":</b> ICEcoder " . explode("\n", $icvData)[0] ." " . $t['now available'] . ". (" . $t['Your version is'] . " " . $ICEcoder["versionNo"] .
            ").<br><br><a href=\\'https://icecoder.net\\' target=\\'_blank\\' style=\\'color:#fff; background: #b00; padding: 5px; text-decoration: none; cursor: pointer\\'>" .
            $t['Update now'] . "</a><br><br>" . $icvI ."');";
	}
}

$isMac = false !== strpos($_SERVER['HTTP_USER_AGENT'], "Macintosh") ? true : false;
?>
<!DOCTYPE html>
<html onmousedown="ICEcoder.mouseDown = true; ICEcoder.resetAutoLogoutTimer();" onmouseup="ICEcoder.mouseDown = false; ICEcoder.resetAutoLogoutTimer(); ICEcoder.mouseDownInCM = false; if (!ICEcoder.overCloseLink) {ICEcoder.tabDragEnd()}" onmousemove="if ('undefined' !== typeof ICEcoder) {ICEcoder.getMouseXY(event, 'top'); ICEcoder.resetAutoLogoutTimer(); ICEcoder.canResizeFilesW()}" onmousewheel="ICEcoder.resetAutoLogoutTimer(); if (ICEcoder.getcMInstance() && !ICEcoder.getcMInstance().hasFocus() && !ICEcoder.getcMdiffInstance().hasFocus()) {event.wheelDelta > 0 ? ICEcoder.nextTab() : ICEcoder.previousTab();}">
<head>
<title>ICEcoder <?php echo $ICEcoder["versionNo"];?></title>
<!--Updated via settings so must remain 1st stylesheet//-->
<style>
	#tabsBar.tabsBar .tab { font-size: <?php echo $ICEcoder["fontSize"];?>; }
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<meta name="viewport" content="width=device-width, initial-scale=0.5, user-scalable=no">
<link rel="stylesheet" type="text/css" href="<?php echo $settingsClass->assetsRoot;?>/css/resets.css?microtime=<?php echo microtime(true);?>">
<link rel="stylesheet" type="text/css" href="<?php echo $settingsClass->assetsRoot;?>/css/icecoder.css?microtime=<?php echo microtime(true);?>">
<link rel="stylesheet" type="text/css" href="<?php echo $settingsClass->assetsRoot;?>/css/file-type-icons.css?microtime=<?php echo microtime(true);?>">
<link rel="stylesheet" href="<?php
echo $settingsClass->assetsRoot . "/css/theme/";
echo "default" === $ICEcoder["theme"] ? 'icecoder.css' : $ICEcoder["theme"] . '.css';
echo "?microtime=" . microtime(true);
?>">
<link rel="icon" type="image/png" href="<?php echo $settingsClass->assetsRoot;?>/images/favicon.png">
<script>
docRoot = "<?php echo $ICEcoder['docRoot']; ?>";
iceRoot = "<?php echo $ICEcoder['root']; ?>";

window.onbeforeunload = function() {
	if (ICEcoder.autoLogoutTimer < ICEcoder.autoLogoutMins * 60) {
		for (var i = 1; i <= ICEcoder.savedPoints.length; i++) {
			if (ICEcoder.savedPoints[i - 1] != ICEcoder.getcMInstance(i).changeGeneration()) {
				return "<?php echo $t['You have some...'];?>.";
			}
		}
		return "<?php echo $t['Are you sure...'];?>";
	}
}

t = {
<?php
// Load the lang array for what's in the JS file
$t = $text['icecoder'];
$tOutput = "";
foreach ($t as $key => $value) {
	$tOutput .= '"' . $key . '" : "' . $value . '",' . PHP_EOL;
}
echo rtrim($tOutput, "," . PHP_EOL) . PHP_EOL;

// Back to the lang array for index
$t = $text['index'];
?>
}
</script>
<script language="JavaScript" src="<?php echo $settingsClass->assetsRoot;?>/js/icecoder.js?microtime=<?php echo microtime(true);?>" id="icecoderJSFile" data-assets-root="<?php echo $settingsClass->assetsRoot;?>"></script>
<?php
$havePrettier = false;
foreach ($ICEcoder['plugins'] as $plugin) {
    if ("Prettier" === $plugin[0]) {
        $havePrettier = true;
    }
}
if (true === $havePrettier && true === file_exists(dirname(__FILE__) . "/plugins/prettier/standalone.js")) {
?>
<script language="JavaScript" src="<?php echo $iceURLPath;?>/plugins/prettier/standalone.js?microtime=<?php echo microtime(true);?>"></script>
<script language="JavaScript" src="<?php echo $iceURLPath;?>/plugins/prettier/parser-babel.js?microtime=<?php echo microtime(true);?>"></script>
<script language="JavaScript" src="<?php echo $iceURLPath;?>/plugins/prettier/parser-postcss.js?microtime=<?php echo microtime(true);?>"></script>
<script language="JavaScript" src="<?php echo $iceURLPath;?>/plugins/prettier/parser-typescript.js?microtime=<?php echo microtime(true);?>"></script>
<script language="JavaScript" src="<?php echo $iceURLPath;?>/plugins/prettier/parser-html.js?microtime=<?php echo microtime(true);?>"></script>
<script language="JavaScript" src="<?php echo $iceURLPath;?>/plugins/prettier/parser-markdown.js?microtime=<?php echo microtime(true);?>"></script>
<script language="JavaScript" src="<?php echo $iceURLPath;?>/plugins/prettier/parser-yaml.js?microtime=<?php echo microtime(true);?>"></script>
<script language="JavaScript" src="<?php echo $iceURLPath;?>/plugins/prettier/parser-php.js?microtime=<?php echo microtime(true);?>"></script>
<?php
}
?>
<script src="<?php echo $settingsClass->assetsRoot;?>/js/mmd.js?microtime=<?php echo microtime(true);?>"></script>
<script src="<?php echo $settingsClass->assetsRoot;?>/js/farbtastic.js?microtime=<?php echo microtime(true);?>"></script>
<script src="<?php echo $settingsClass->assetsRoot;?>/js/difflib.js?microtime=<?php echo microtime(true);?>"></script>
<link rel="stylesheet" href="<?php echo $settingsClass->assetsRoot;?>/css/farbtastic.css?microtime=<?php echo microtime(true);?>" type="text/css">
</head>

<body onload="<?php
	echo "ICEcoder.versionNo = '" . $ICEcoder["versionNo"] . "';".
		'ICEcoder.previousFiles = [';
		if (false === empty($ICEcoder["previousFiles"])) {
			echo "'" . implode("','", $ICEcoder["previousFiles"]) . "'";
		}
	echo "];";
	echo "ICEcoder.theme = '" . ("default" === $ICEcoder["theme"] ? 'icecoder' : $ICEcoder["theme"]) . "';" .
		"ICEcoder.autoLogoutMins = " . $ICEcoder["autoLogoutMins"] . ";" .
		"ICEcoder.fontSize = '" . $ICEcoder["fontSize"] . "';" .
		"ICEcoder.openLastFiles = " . ($ICEcoder["openLastFiles"] ? 'true' : 'false') . ";" .
		"ICEcoder.updateDiffOnSave = " . ($ICEcoder["updateDiffOnSave"] ? 'true' : 'false') . ";" .
		"ICEcoder.languageUser = '".$ICEcoder["languageUser"] . "';" .
		"ICEcoder.codeAssist = " . ($ICEcoder["codeAssist"] ? 'true' : 'false') . ";" .
		"ICEcoder.lockedNav = " . ($ICEcoder["lockedNav"] ? 'true' : 'false') . ";" .
		"ICEcoder.lineWrapping = " . ($ICEcoder["lineWrapping"] ? 'true' : 'false') . ";" .
		"ICEcoder.lineNumbers = " . ($ICEcoder["lineNumbers"] ? 'true' : 'false') . ";" .
		"ICEcoder.showTrailingSpace = " . ($ICEcoder["showTrailingSpace"] ? 'true' : 'false') . ";" .
		"ICEcoder.matchBrackets = " . ($ICEcoder["matchBrackets"] ? 'true' : 'false') . ";" .
		"ICEcoder.autoCloseTags = " . ($ICEcoder["autoCloseTags"] ? 'true' : 'false') . ";" .
		"ICEcoder.autoCloseBrackets = " . ($ICEcoder["autoCloseBrackets"] ? 'true' : 'false') . ";" .
		"ICEcoder.indentType = '" . $ICEcoder["indentType"] . "';" .
		"ICEcoder.indentAuto = " . ($ICEcoder["indentAuto"] ? 'true' : 'false') . ";" .
		"ICEcoder.indentSize = " . $ICEcoder["indentSize"] . ";" .
		"ICEcoder.scrollbarStyle = '" . $ICEcoder["scrollbarStyle"] . "';" .
		"ICEcoder.demoMode = " . ($ICEcoder["demoMode"] ? 'true' : 'false') . ";" .
		"ICEcoder.tagWrapperCommand = '" . $ICEcoder["tagWrapperCommand"] . "';" .
		"ICEcoder.autoComplete = '" . $ICEcoder["autoComplete"] . "';" .
		"ICEcoder.selectNextOnFindInput = " . ($ICEcoder["selectNextOnFindInput"] ? 'true' : 'false') . ";" .
		"ICEcoder.goToLineScrollSpeed = '" . $ICEcoder["goToLineScrollSpeed"] . "';" .
		"ICEcoder.bugFilePaths = ['" . implode("','",$ICEcoder["bugFilePaths"]) . "'];" .
		"ICEcoder.bugFileCheckTimer = ".$ICEcoder["bugFileCheckTimer"] . ";" .
		"ICEcoder.bugFileMaxLines = " . $ICEcoder["bugFileMaxLines"] . ";" .
		"ICEcoder.fileDirResOutput = '" . $ICEcoder["fileDirResOutput"] . "';" .
		"ICEcoder.newDirPerms = " . $ICEcoder["newDirPerms"] . ";" .
		"ICEcoder.newFilePerms = " . $ICEcoder["newFilePerms"] . ";";
		echo "ICEcoder.csrf = '" . $_SESSION["csrf"] . "';";
        if (true === $ICEcoder["tutorialOnLogin"]) {
            echo "ICEcoder.viewTutorial(false, 700);";
        }
    $extraProcessesClass = new ExtraProcesses();
    $onLoad = $extraProcessesClass->onLoad();
?>ICEcoder.init()<?php echo $updateMsg . $onLoadExtras;?>;ICEcoder.content.style.visibility = 'visible';<?php echo $onLoad;?><?php if (true === isset($_GET["display"]) && "updated" === $_GET["display"]) {echo "ICEcoder.updated();";};?>" onresize="ICEcoder.setLayout()" onkeydown="return ICEcoder.interceptKeys('coder', event);" onkeyup="if ('visible' === get('blackMask').style.visibility) {ICEcoder.handleModalKeyUp(event, 'modalGeneralCatch')}; ICEcoder.resetKeys(event);" onblur="ICEcoder.resetKeys(event);">

<div id="blackMask" class="blackMask" onclick="if (!ICEcoder.overPopup) {ICEcoder.showHide('hide', this)}" oncontextmenu="return false">
	<div class="popupVCenter">
		<div class="popup" id="mediaContainer"></div>
	</div>
	<div class="floatingContainer" id="floatingContainer"></div>
</div>

<div id="loadingMask" class="blackMask" style="visibility: visible" oncontextmenu="return false">
	<div class="popupVCenter">
		<div class="popup">
			<div class="spinner"></div>
			<?php echo $t['working'];?>...
		</div>
	</div>
</div>

<div id="infoBlackMask" class="infoBlackMask" oncontextmenu="return false"></div>
<div id="infoMessageContainer" class="infoMessageContainer" oncontextmenu="return false">
    <div id="infoMessage" class="infoMessage"></div>
</div>

<div id="plugins" class="plugins" style="<?php echo $ICEcoder["pluginPanelAligned"];?>: 0" onmouseover="ICEcoder.showHidePlugins('show')" onmouseout="ICEcoder.showHidePlugins('hide')" onclick="ICEcoder.showHidePlugins('hide')">
	<div style="padding: 15px">
		<a nohref onclick="ICEcoder.showColorPicker(document.getElementById('color') ? document.getElementById('color').value : '#123456')" title="Farbtastic
<?php echo $t['Color picker'];?>"><img src="<?php echo $settingsClass->assetsRoot;?>/images/color-picker.png" style="cursor: pointer" alt="Color Picker"></a><br><br>
		<div id="pluginsOptional"><?php echo $pluginsDisplay; ?></div>
		<a nohref onclick="ICEcoder.pluginsManager()" title="<?php echo $t['Plugins Manager'];?>" style="color: #ddd; margin-left: 2px; cursor: pointer"><?php echo file_get_contents(dirname(__FILE__) . "/assets/images/icons/plus.svg");?></a>
	</div>
</div>

<div id="fileMenu" class="fileMenu" onmouseover="ICEcoder.changeFilesW('expand')" onmouseout="ICEcoder.changeFilesW('contract'); ICEcoder.hideFileMenu()" style="opacity: 0" oncontextmenu="return false">
	<span id="folderMenuItems">
		<a href="javascript:ICEcoder.newFile()" onmouseover="ICEcoder.showFileMenu()"><?php echo $t['New File'];?></a>
		<a href="javascript:ICEcoder.newFolder()" onmouseover="ICEcoder.showFileMenu()"><?php echo $t['New Folder'];?></a>
		<div onmouseover="ICEcoder.showFileMenu()" style="padding: 2px 0"><hr></div>
		<a href="javascript:ICEcoder.uploadFilesSelect(ICEcoder.selectedFiles[ICEcoder.selectedFiles.length - 1])" onmouseover="ICEcoder.showFileMenu()"><?php echo $t['Upload File(s)'];?></a>
		<div style="display: none">
			<form enctype="multipart/form-data" id="uploadFilesForm" action="<?php echo $iceURLPath;?>/lib/file-control.php?action=upload&file=/uploaded" method="POST" target="fileControl">
				<input type="hidden" name="folder" id="uploadDir" value="/">
				<input type="file" name="filesInput[]" id="fileInput" onchange="ICEcoder.uploadFilesSubmit(this)" multiple>
				<input type="submit" value="Upload File">
				<input type="hidden" name="csrf" value="<?php echo $_SESSION["csrf"]; ?>">
			</form>
		</div>
		<a href="javascript:ICEcoder.pasteFiles(ICEcoder.selectedFiles[ICEcoder.selectedFiles.length - 1])" onmouseover="ICEcoder.showFileMenu()" id="fmMenuPasteOption" style="display: none"><?php echo $t['Paste'];?></a>
		<div onmouseover="ICEcoder.showFileMenu()" style="padding: 2px 0"><hr></div>
	</span>
	<a href="javascript:ICEcoder.openFilesFromList(ICEcoder.selectedFiles)" onmouseover="ICEcoder.showFileMenu()"><?php echo $t['Open'];?></a>
	<a href="javascript:ICEcoder.copyFiles(ICEcoder.selectedFiles)" onmouseover="ICEcoder.showFileMenu()"><?php echo $t['Copy'];?></a>
	<a href="javascript:ICEcoder.duplicateFiles(ICEcoder.selectedFiles)" onmouseover="ICEcoder.showFileMenu()"><?php echo $t['Duplicate'];?></a>
	<a href="javascript:ICEcoder.deleteFiles(ICEcoder.selectedFiles)" onmouseover="ICEcoder.showFileMenu()"><?php echo $t['Delete'];?></a>
	<span id="singleFileMenuItems">
		<a href="javascript:ICEcoder.renameFile(ICEcoder.selectedFiles[ICEcoder.selectedFiles.length - 1])" onmouseover="ICEcoder.showFileMenu()"><?php echo $t['Rename'];?></a>
		<div onmouseover="ICEcoder.showFileMenu()" style="padding: 2px 0"><hr></div>
		<a nohref onClick="window.open('//<?php echo $_SERVER['HTTP_HOST'];?>' + iceRoot + ICEcoder.selectedFiles[ICEcoder.selectedFiles.length - 1].replace(/\|/g, '/'))" onmouseover="ICEcoder.showFileMenu()" style="cursor: pointer"><?php echo $t['View Webpage'];?></a>
	</span>
	<div onmouseover="ICEcoder.showFileMenu()" style="padding: 2px 0"><hr></div>
	<?php
	if (true === file_exists(dirname(__FILE__) . "/plugins/zip-it/index.php")) {
		echo '<a href="javascript:ICEcoder.zipIt(ICEcoder.selectedFiles[ICEcoder.selectedFiles.length - 1])" onmouseover="ICEcoder.showFileMenu()">Zip It!</a>' . PHP_EOL;
	};
	?>
	<a href="javascript:ICEcoder.downloadFile(ICEcoder.selectedFiles[ICEcoder.selectedFiles.length - 1])" onmouseover="ICEcoder.showFileMenu()"><?php echo $t['Download'];?></a>
	<div onmouseover="ICEcoder.showFileMenu()" style="padding: 2px 0"><hr></div>
	<a href="javascript:ICEcoder.propertiesScreen(ICEcoder.selectedFiles[ICEcoder.selectedFiles.length - 1])" onmouseover="ICEcoder.showFileMenu()"><?php echo $t['Properties'];?></a>
</div>

<div id="header" class="header" oncontextmenu="return false"></div>

<div id="files" class="files" onmouseover="ICEcoder.changeFilesW('expand')" onmouseout="ICEcoder.changeFilesW('contract'); ICEcoder.hideFileMenu();" oncontextmenu="return false">
	<div id="fileNav" class="fileNav">
		<ul>
			<li><a nohref onclick="ICEcoder.canShowFMNav = true; ICEcoder.showHideFileNav('show', 'optionsFile')" onmouseover="if (ICEcoder.canShowFMNav) {ICEcoder.showHideFileNav('show', 'optionsFile')}" id="optionsFileNav"><?php echo $t['File'];?></a></li>
			<li><a nohref onclick="ICEcoder.canShowFMNav = true; ICEcoder.showHideFileNav('show', 'optionsEdit')" onmouseover="if (ICEcoder.canShowFMNav) {ICEcoder.showHideFileNav('show', 'optionsEdit')}" id="optionsEditNav"><?php echo $t['Edit'];?></a></li>
            <li><a nohref onclick="ICEcoder.canShowFMNav = true; ICEcoder.showHideFileNav('show', 'optionsSettings')" onmouseover="if (ICEcoder.canShowFMNav) {ICEcoder.showHideFileNav('show', 'optionsSettings')}" id="optionsSettingsNav"><?php echo $t['Settings'];?></a></li>
			<li><a nohref onclick="ICEcoder.canShowFMNav = true; ICEcoder.showHideFileNav('show', 'optionsHelp')" onmouseover="if (ICEcoder.canShowFMNav) {ICEcoder.showHideFileNav('show', 'optionsHelp')}" id="optionsHelpNav"><?php echo $t['Help'];?></a></li>
        </ul>
	</div>
	<div class="options" id="fileOptions">
		<div id="optionsFile" class="optionsList" onmouseover="ICEcoder.showHideFileNav('show', this.id)" onmouseout="ICEcoder.showHideFileNav('hide', this.id); ICEcoder.canShowFMNav = false">
			<ul>
				<li><a nohref onClick="ICEcoder.newFile()"><?php echo $t['New File'];?>...</a></li>
				<li><a nohref onClick="ICEcoder.newFolder()"><?php echo $t['New Folder'];?>...</a></li>
				<li><a nohref onClick="ICEcoder.openPrompt()"><?php echo $t['Open'];?>...</a></li>
				<li><a nohref onClick="ICEcoder.saveFile(false, false)"><?php echo $t['Save'];?></a></li>
				<li><a nohref onclick="ICEcoder.saveFile(true, false)"><?php echo $t['Save As'];?>...</a></li>
				<li><a nohref onclick="ICEcoder.openPreviewWindow()"><?php echo $t['Live Preview'];?></a></li>
				<li><a nohref onclick="ICEcoder.downloadFile(ICEcoder.selectedFiles[ICEcoder.selectedFiles.length - 1])"><?php echo $t['Download'];?></a></li>
				<li><a nohref onclick="ICEcoder.copyFiles(ICEcoder.selectedFiles)"><?php echo $t['Copy'];?></a></li>
				<li><a nohref onclick="ICEcoder.pasteFiles(ICEcoder.selectedFiles[ICEcoder.selectedFiles.length - 1])"><?php echo $t['Paste'];?></a></li>
				<li><a nohref onclick="ICEcoder.deleteFiles(ICEcoder.selectedFiles)"><?php echo $t['Delete'];?></a></li>
				<li><a nohref onclick="ICEcoder.duplicateFiles(ICEcoder.selectedFiles)"><?php echo $t['Duplicate'];?></a></li>
				<li><a nohref onclick="ICEcoder.renameFile(ICEcoder.selectedFiles[ICEcoder.selectedFiles.length - 1])"><?php echo $t['Rename'];?></a></li>
				<li><a nohref onclick="ICEcoder.uploadFilesSelect(ICEcoder.selectedFiles[ICEcoder.selectedFiles.length - 1])"><?php echo $t['Upload'];?>...</a></li>
				<li><a nohref onclick="ICEcoder.zipIt(ICEcoder.selectedFiles[ICEcoder.selectedFiles.length - 1])"><?php echo $t['Zip'];?></a></li>
				<li><a nohref onclick="ICEcoder.propertiesScreen(ICEcoder.selectedFiles[ICEcoder.selectedFiles.length - 1])"><?php echo $t['Properties'];?>...</a></li>
				<li><a nohref onClick="ICEcoder.printCode()"><?php echo $t['Print'];?>...</a></li>
				<li><a nohref onClick="ICEcoder.fullScreenSwitcher()"><?php echo $t['Fullscreen toggle'];?></a></li>
				<?php if (true === $ICEcoder['loginRequired']) {?>
				<li><a nohref onClick="ICEcoder.logout()"><?php echo $t['Logout'];?></a></li>
				<?php ;}; ?>
			</ul>
		</div>
		<div id="optionsEdit" class="optionsList" onmouseover="ICEcoder.showHideFileNav('show', this.id)" onmouseout="ICEcoder.showHideFileNav('hide', this.id); ICEcoder.canShowFMNav = false">
			<ul>
				<li><a nohref onclick="ICEcoder.undo()"><?php echo $t['Undo'];?></a></li>
				<li><a nohref onclick="ICEcoder.redo()"><?php echo $t['Redo'];?></a></li>
				<li><a nohref onclick="ICEcoder.indent('more')"><?php echo $t['Indent more'];?></a></li>
				<li><a nohref onclick="ICEcoder.indent('less')"><?php echo $t['Indent less'];?></a></li>
				<li><a nohref onclick="ICEcoder.autocomplete()"><?php echo $t['Autocomplete'];?></a></li>
				<li><a nohref onclick="ICEcoder.lineCommentToggle()"><?php echo $t['Comment/Uncomment'];?></a></li>
				<li><a nohref onclick="ICEcoder.jumpToDefinition()"><?php echo $t['Jump to Definition'];?></a></li>
			</ul>
		</div>
        <div id="optionsSettings" class="optionsList" onmouseover="ICEcoder.showHideFileNav('show', this.id)" onmouseout="ICEcoder.showHideFileNav('hide', this.id);ICEcoder.canShowFMNav = false">
            <ul>
                <li><a nohref onclick="ICEcoder.settingsScreen(false, 'general')">General</a></li>
                <li><a nohref onclick="ICEcoder.settingsScreen(false, 'style')">Style</a></li>
                <li><a nohref onclick="ICEcoder.settingsScreen(false, 'accounts')">Accounts</a></li>
                <li><a nohref onclick="ICEcoder.settingsScreen(false, 'security')">Security</a></li>
                <li><a nohref onclick="ICEcoder.pluginsManager()">Plugins</a></li>
            </ul>
        </div>
		<div id="optionsHelp" class="optionsList" onmouseover="ICEcoder.showHideFileNav('show', this.id)" onmouseout="ICEcoder.showHideFileNav('hide', this.id);ICEcoder.canShowFMNav = false">
			<ul>
				<li><a nohref onclick="ICEcoder.viewTutorial(false, 500)">Tutorial</a></li>
				<li><a href="https://icecoder.net/usage" target="_blank">Usage</a></li>
				<li><a href="https://icecoder.net/tips-tricks" target="_blank">Tips &amp; Tricks</a></li>
				<li><a nohref onclick="ICEcoder.showManual('<?php echo $ICEcoder["versionNo"];?>')"><?php echo $t['Manual'];?></a></li>
				<li><a nohref onClick="ICEcoder.helpScreen()"><?php echo $t['Shortcuts'];?></a></li>
				<li><a nohref onclick="ICEcoder.searchForSelected()"><?php echo $t['Search for selected'];?></a></li>
				<li><a href="https://icecoder.net" target="_blank">ICEcoder <?php echo $t['website'];?></a></li>
			</ul>
		</div>
	</div>
	<iframe id="filesFrame" class="frame" name="ff" src="<?php echo $iceURLPath;?>/files.php" style="opacity: 0" onLoad="this.style.opacity = '1'; this.contentWindow.onscroll = function(){ICEcoder.mouseDown = false; ICEcoder.mouseDownInCM = false}"></iframe>

    <div class="tools" id="tools">
        <div onclick="ICEcoder.toolShowHideToggle('terminal')" id="toolLinkTerminal" title="Terminal"><?php echo file_get_contents(dirname(__FILE__) . "/assets/images/icons/terminal-2.svg");?></div>
        <div onclick="ICEcoder.toolShowHideToggle('output'); this.className = ''" id="toolLinkOutput" title="Output"><?php echo file_get_contents(dirname(__FILE__) . "/assets/images/icons/layout-list.svg");?></div>
        <div onclick="ICEcoder.toolShowHideToggle('database')" id="toolLinkDatabase" title="Database"><?php echo file_get_contents(dirname(__FILE__) . "/assets/images/icons/database.svg");?></div>
        <div onclick="ICEcoder.toolShowHideToggle('git')" id="toolLinkGit" title="Git"><?php echo file_get_contents(dirname(__FILE__) . "/assets/images/icons/git-compare.svg");?></div>
    </div>
</div>

<div id="editor" class="editor">
	<div id="tabsBar" class="tabsBar" oncontextmenu="return false">
		<a nohref onClick="ICEcoder.closeAllTabs()"><img src="<?php echo $settingsClass->assetsRoot;?>/images/nav-close-all.gif" class="closeAllTabs" title="<?php echo $t['Close all tabs'];?>"></a>
		<a nohref onClick="ICEcoder.alphaTabs()"><img src="<?php echo $settingsClass->assetsRoot;?>/images/nav-alpha.png" class="alphaTabs" title="<?php echo $t['Alphabetize tabs'];?>"></a>
		<?php
		for ($i = 1; $i <= 100; $i++) {
			echo '<div id="tab' . $i . '" class="tab" onmousedown="if (false === ICEcoder.overCloseLink) {ICEcoder.switchTab(parseInt(this.id.slice(3), 10)); ICEcoder.tabDragStart(parseInt(this.id.slice(3), 10))}; if (1 === event.button) {ICEcoder.closeTab(parseInt(this.id.slice(3), 10)); return false}; thisColor = ICEcoder.colorSelectedText;" onmouseover="thisColor = this.style.color; this.style.color = ICEcoder.colorSelectedText" onmouseout="this.style.color = thisColor" ondblclick="ICEcoder.focusUnfocusTab()"></div>';
		}
		?><div class="newTab" onClick="ICEcoder.newTab(false)" id="newTab">+</div>
	</div>
	<div id="findBar" class="findBar" oncontextmenu="return false">
		<form name="findAndReplace" onsubmit="ICEcoder.findReplace(get('find').value, false, false, false); ICEcoder.getcMInstance().focus(); return false">
			<div class="findReplace">
				<div class="findRegexToggle" id="findRegexToggle" onclick="ICEcoder.findRegexToggle()" title="RegEx">^$</div><div class="findText"><?php echo $t['Find'];?></div>
				<input type="text" name="find" value="" id="find" class="textbox find" oninput="ICEcoder.findOnInput()">

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
					 <div class="withText">with</div>
				</div>
				<input type="text" name="replace" value="" id="replace" class="textbox replace" style="display: none">
				<div class="targetText" id="rTarget" style="display: none">in</div>
					<div class="selectWrapper" style="width: 104px">
						<select name="target" onChange="ICEcoder.updateResultsDisplay('this document' === this.value ? 'show' : 'hide')" style="width: 101px; margin-top: 4px; margin-left: 2px">
							<option><?php echo $t['this document'];?></option>
							<option><?php echo $t['open documents'];?></option>
							<option><?php echo $t['all files'];?></option>
							<option><?php echo $t['all filenames'];?></option>
						</select>
					</div>
				<input type="button" name="prev" value="&lt;&lt;" class="button" onclick="ICEcoder.findReplace(get('find').value, true, true, true);">
				<input type="button" name="next" value="&gt;&gt;" class="button" onclick="ICEcoder.findReplace(get('find').value, true, true, false);">
				<input type="submit" name="sub" value="sub" style="display: none">
				<div class="results" id="results"></div>
			</div>
			<input type="hidden" name="csrf" value="<?php echo $_SESSION["csrf"]; ?>">
		</form>
		<form onSubmit="return ICEcoder.goToLine(get('goToLineNo').value, 0, false)">
			<div class="goLine"><?php echo $t['Go to Line'];?> <input type="text" name="goToLine" value="" maxlength="5" id="goToLineNo" onkeyup="ICEcoder.goToLine(this.value, 0, true)" class="textbox goToLine"></div>
			<div class="view" title="<?php echo $t['View'];?>" onClick="ICEcoder.openPreviewWindow()" id="fMView"><?php echo file_get_contents(dirname(__FILE__) . "/assets/images/icons/browser.svg");?></div>
			<div class="bug" title="<?php echo $t['Bug reporting not active'];?>" onClick="ICEcoder.openBugReport()" id="bugIcon"><?php echo file_get_contents(dirname(__FILE__) . "/assets/images/icons/bug.svg");?></div>
			<input type="hidden" name="csrf" value="<?php echo $_SESSION["csrf"]; ?>">
		</form>
	</div>
	<iframe name="terminalFrame" id="terminal" src="<?php echo $iceURLPath;?>/terminal.php" class="terminal"></iframe>
	<pre id="output" class="output" style="font-family: monospace"><b>Output</b><br>via ICEcoder.output(message);<br><br></pre>
	<iframe name="databaseFrame" id="database" src="<?php echo $iceURLPath;?>/lib/database.php" class="database"></iframe>
	<div id="git" class="git" style="font-family: monospace"><?php
	if (file_exists($docRoot . $ICEcoderDir . "/data/git-diff.php")) {
		echo "Looking for git status...";
	} else {
		echo "To provide git diff data to ICEcoder, please run...<br><br>sudo nohup php server/run-tasks.php > data/nohup.log 2>&1 &<br><br>...to run as a background process";
	};?></div>
	<iframe name="contentFrame" id="content" src="<?php echo $iceURLPath;?>/editor.php" class="code" scrolling="no"></iframe>
</div>

<div class="footer" id="footer" oncontextmenu="return false">
	<div class="versionsDisplay" id="versionsDisplay" onclick="ICEcoder.versionsScreen(ICEcoder.openFiles[ICEcoder.selectedTab - 1].replace(/\//g, '|'))"></div>
    <div class="serverMessage" id="serverMessage"></div>
	<div class="splitPaneControls" id="splitPaneControls"><div class="off" id="splitPaneControlsOff" title="<?php echo $t['Single pane'];?>" onclick="ICEcoder.setSplitPane('off')" style="opacity: 0.8"><?php echo file_get_contents(dirname(__FILE__) . "/assets/images/icons/square.svg");?></div><div class="on" id="splitPaneControlsOn" title="<?php echo $t['Diff pane also'];?>" onclick="ICEcoder.setSplitPane('on')" style="opacity: 0.2"><?php echo file_get_contents(dirname(__FILE__) . "/assets/images/icons/layout-columns.svg");?></div></div>
	<div class="splitPaneNames" id="splitPaneNamesMain">Main Pane</div>
	<div class="splitPaneNames" id="splitPaneNamesDiff">Diff Pane</div>
	<div class="byteDisplay" id="byteDisplay" style="display: none" onClick="ICEcoder.showDisplay('char')"></div>
	<div class="charDisplay" id="charDisplay" style="display: inline-block" onClick="ICEcoder.showDisplay('byte')"></div>
</div>

<div id="tooltip" class="tooltip" style="display: none"></div>

<div class="closeIcon" style="display: none" id="closeIcon"><?php echo file_get_contents(dirname(__FILE__) . "/assets/images/icons/x.svg");?></div>

<?php
echo $systemClass->getDemoModeIndicator(false);
?>

<script>
ICEcoder.initAliases();
ICEcoder.setLayout(false);
</script>

</body>

</html>
