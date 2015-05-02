<?php
include("headers.php");
include("settings.php");
$t = $text['settings-screen'];
?>
<!DOCTYPE html>

<html>
<head>
<title>ICEcoder <?php echo $ICEcoder["versionNo"];?> settings screen</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" type="text/css" href="settings-screen.css?microtime=<?php echo microtime(true);?>">
<link rel="stylesheet" href="../<?php echo $ICEcoder["codeMirrorDir"]; ?>/lib/codemirror.css?microtime=<?php echo microtime(true);?>">
<script src="../<?php echo $ICEcoder["codeMirrorDir"]; ?>/lib/codemirror-compressed.js?microtime=<?php echo microtime(true);?>"></script>

<style type="text/css">
.CodeMirror {position: absolute; width: 409px; height: 180px; font-size: <?php echo $ICEcoder["fontSize"];?>}
.CodeMirror-scroll {overflow: hidden}
/* Make sure this next one remains the 3rd item, updated with JS */
.cm-tab {border-left-width: <?php echo $ICEcoder["visibleTabs"] ? "1px" : "0";?>; margin-left: <?php echo $ICEcoder["visibleTabs"] ? "-1px" : "0";?>; border-left-style: solid; border-left-color: rgba(255,255,255,0.2)}
</style>

<link rel="stylesheet" href="editor.css?microtime=<?php echo microtime(true);?>">
<?php
$themeArray = array();
$handle = opendir('../'.$ICEcoder["codeMirrorDir"].'/theme/');
while (false !== ($file = readdir($handle))) {
	if ($file !== "." && $file != "..") {
		array_push($themeArray,basename($file,".css"));
	}
}
sort($themeArray);
for ($i=0;$i<count($themeArray);$i++) {
	echo '<link rel="stylesheet" href="../'.$ICEcoder["codeMirrorDir"].'/theme/'.$themeArray[$i].'.css?microtime='.microtime(true).'">'.PHP_EOL;
}
?>
</head>

<body class="settings">

<div class="infoPane">
	<a href="https://icecoder.net" target="_blank"><img src="../images/ice-coder.png" alt="ICEcoder" class="logo"></a>

	<h1 style="margin: 10px 0"><?php echo $t['settings'];?></h1>

	<p>
	<?php echo $t['version'];?>:<br>
	v <?php echo $ICEcoder["versionNo"];?>
	<br><br>

	<?php echo $t['website'];?>:<br>
	<a href="https://icecoder.net" target="_blank">https://icecoder.net</a>
	<br><br>

	<?php echo $t['git'];?>:<br>
	<a href="https://github.com/mattpass/ICEcoder" target="_blank">https://github.com/mattpass/ICEcoder</a>
	<br><br>

	<?php echo $t['codemirror dir'];?>:<br>
	<?php echo $ICEcoder["codeMirrorDir"]; ?>
	<br><br>

	<?php echo $t['codemirror version'];?>:<br>
	<script>
	document.write(CodeMirror.version);
	</script>
	<br><br>

	<?php echo $t['file manager root'];?>:<br>
	<?php echo $ICEcoder['root'] == "" ? "/" : $ICEcoder['root'];?>
	<br><br>

	<div style="font-size: 10px; line-height: 12px">
		<?php echo $t['Get in contact...'];?><br>
		<a href="https://www.twitter.com/icecoder" style="font-size: 10px" target="_blank">Twitter</a><br>
		<a href="https://facebook.com/ICEcoder.net" style="font-size: 10px" target="_blank">Facebook</a><br>
		<a href="https://groups.google.com/forum/#!forum/icecoder" style="font-size: 10px" target="_blank">Google Groups</a><br>
		<a href="https://github.com/mattpass/ICEcoder" style="font-size: 10px" target="_blank">GitHub</a><br>
		<a href="mailto:info@icecoder.net" style="font-size: 10px">Email</a><br><br>
		<?php echo $t['You may use...'];?>
	</div>
	</p>
</div>

<form name="settings" action="settings.php" method="POST">
<div class="settingsColumn1">
<h2><?php echo $t['functionality'];?></h2>
<input type="checkbox" onclick="showButton()" name="checkUpdates" value="true"<?php if($ICEcoder["checkUpdates"]) {echo ' checked';};?>> <?php echo $t['check for updates...'];?><br>
<input type="checkbox" onclick="showButton()" name="openLastFiles" value="true"<?php if($ICEcoder["openLastFiles"]) {echo ' checked';};?>> <?php echo $t['auto open last...'];?><br>
<input type="checkbox" onclick="showButton()" name="updateDiffOnSave" value="true"<?php if($ICEcoder["updateDiffOnSave"]) {echo ' checked';};?>> update diff pane on save<br>
language <span class="info" title="Reload required after changing">[?]</span><br>
<select onchange="showButton()" name="languageUser">
<?php
$langFiles = array("chinese-simplified.php","chinese-traditional.php","dutch.php","english.php","french.php","german.php","italian.php","norwegian.php","persian.php","portuguese-brazilian.php","spanish.php");
$langText = array("中国（简体）","中國（繁體）","Nederlands","English","Français","Deutsch","Italiano","Norsk","فارسی","Portugues(br)","Espa&ntilde;ol");
for ($i=0; $i<count($langFiles); $i++) {
	echo '<option value="'.$langFiles[$i].'"'.($ICEcoder["languageUser"]==$langFiles[$i] ? ' selected' : '').'>'.$langText[$i].'</option>'.PHP_EOL;
}
?>
</select><br>
<br>
<?php echo $t['when finding in...'];?>:<br>
<input type="text" onkeydown="showButton()" name="findFilesExclude" value="<?php echo implode(", ",$ICEcoder["findFilesExclude"]); ?>"><br>
<br>

<h2><?php echo $t['assisting'];?></h2>
<input type="checkbox" onclick="showButton()" name="codeAssist" value="true"<?php if($ICEcoder["codeAssist"]) {echo ' checked';};?>> <?php echo $t['code assist'];?><br>
<input type="checkbox" onclick="showButton();showHideTabs()" name="visibleTabs" value="true"<?php if($ICEcoder["visibleTabs"]) {echo ' checked';};?>> <?php echo $t['visible tabs'];?><br>
<input type="checkbox" onclick="showButton()" name="lockedNav" value="true"<?php if($ICEcoder["lockedNav"]) {echo ' checked';};?>> <?php echo $t['locked nav'];?><br><br>
<?php echo $t['tag wrapper command'];?><br>
<select onchange="showButton()" name="tagWrapperCommand">
	<option value="ctrl+alt"<?php if($ICEcoder["tagWrapperCommand"]=='ctrl+alt') {echo " selected";};?>>ctrl/cmd + alt</option>
	<option value="alt-left"<?php if($ICEcoder["tagWrapperCommand"]=='alt-left') {echo " selected";};?>>alt left</option>
</select><br>
<br>
<?php echo $t['auto-complete on'];?><br>
<select onchange="showButton()" name="autoComplete">
	<option value="ctrl+space"<?php if($ICEcoder["autoComplete"]=='ctrl+space') {echo " selected";};?>>ctrl/cmd + space</option>
	<option value="keypress"<?php if($ICEcoder["autoComplete"]=='keypress') {echo " selected";};?>>keypress</option>
</select><br>
<br>

<h2><?php echo $t['security'];?></h2>
<?php echo $t['new password'];?> <span class="info" title="<?php echo $t['8 chars min'];?>">[?]</span><br>
<input type="password" name="password" onkeydown="showButton()"><br>
<?php echo $t['confirm password'];?><br>
<input type="password" name="passwordConfirm" onkeydown="showButton()"><br>
<br>
<?php echo $t['banned files/folders'];?><br>
<input type="text" onkeydown="document.settings.changedFileSettings.value='true';showButton()" name="bannedFiles" value="<?php echo implode(", ",$ICEcoder["bannedFiles"]); ?>"><br>
<?php echo $t['banned paths'];?> <span class="info" title="<?php echo $t['Slash prefixed comma...'];?>">[?]</span><br>
<input type="text" onkeydown="document.settings.changedFileSettings.value='true';showButton()" name="bannedPaths" value="<?php echo implode(", ",$ICEcoder["bannedPaths"]); ?>"><br>
<input type="hidden" name="changedFileSettings" value="false">
<?php echo $t['ip addresses'];?> <span class="info" title="<?php echo $t['Comma delimited'];?>">[?]</span><br>
<input type="text" onkeydown="showButton()" name="allowedIPs" value="<?php echo implode(", ",$ICEcoder["allowedIPs"]); ?>"><br>
</div>

<div class="settingsColumn2">
<h2><?php echo $t['style'];?></h2>
<?php echo $t['theme'];?><br>
<select onchange="selectTheme();showButton()" id="select" name="theme" style="width: 95px">
    <option<?php if ($ICEcoder["theme"]=="default") {echo ' selected';}; ?>>default</option>
<?php
for ($i=0;$i<count($themeArray);$i++) {
	$optionSelected = $ICEcoder["theme"]==$themeArray[$i] ? ' selected' : '';
	echo '<option'.$optionSelected.'>'.$themeArray[$i].'</option>'.PHP_EOL;
}
?>
</select>

<span style="position: absolute; margin: -15px 0 0 15px">
	<?php echo $t['indent type'];?><br>
	<select onchange="showButton()" name="indentWithTabs">
		<option value="true"<?php if($ICEcoder["indentWithTabs"]) {echo " selected";};?>>tabs</option>
		<option value="false"<?php if(!$ICEcoder["indentWithTabs"]) {echo " selected";};?>>spaces</option>
	</select>
</span>

<span style="position: absolute; margin: -15px 0 0 100px">
	<?php echo $t['indent size'];?><br>
	<input type="text" name="indentSize" id="indentSize" style="width: 30px" onkeydown="showButton()" onkeyup="changeIndentSize()" value="<?php echo $ICEcoder["indentSize"];?>">
</span>

<span style="position: absolute; margin: -15px 0 15px 175px">
	<?php echo $t['auto indent'];?><br>
	<select onchange="showButton()" name="indentAuto">
		<option value="true"<?php if($ICEcoder["indentAuto"]) {echo " selected";};?>>yes</option>
		<option value="false"<?php if(!$ICEcoder["indentAuto"]) {echo " selected";};?>>no</option>
	</select>
</span>
<br>
<span style="position: absolute; margin: 5px 0 0 0">
	<?php echo $t['line wrapping'];?><br>
	<select onchange="showButton()" name="lineWrapping">
		<option value="true"<?php if($ICEcoder["lineWrapping"]) {echo " selected";};?>>yes</option>
		<option value="false"<?php if(!$ICEcoder["lineWrapping"]) {echo " selected";};?>>no</option>
	</select>
</span>

<span style="position: absolute; margin: 5px 0 0 95px">
	<?php echo $t['font size'];?><br>
	<input type="text" name="fontSize" id="fontSize" style="width: 44px" onkeydown="showButton()" onkeyup="changeFontSize()" value="<?php echo $ICEcoder["fontSize"];?>">
</span>

<br><br><br><br>

<textarea id="code" name="code">
function findSequence(goal) {
	function find(start,history) {
		if (start==goal)
			return history;
		else if (start>goal)
			return null;
		else
			return find(start+5,"("+history+"+5)") ||
			find(start*3,"("+history+"*3)");
	}
	return find(1,"1");
}</textarea>
<br>

<span style="position: absolute; top: 325px">

	<div style="position: relative; display: inline-block; margin-right: 20px">
		<h2><?php echo $t['layout'];?></h2>
		<?php echo $t['plugin panel aligned'];?><br>
		<select onchange="showButton()" name="pluginPanelAligned">
			<option value="left"<?php if($ICEcoder["pluginPanelAligned"] == "left") {echo " selected";};?>>left</option>
			<option value="right"<?php if($ICEcoder["pluginPanelAligned"] == "right") {echo " selected";};?>>right</option>
		</select>
	</div>

	<div style="position: relative; display: inline-block">
		<h2><?php echo $t['file manager'];?></h2>
		<?php echo $t['root'];?> <span class="info" title="<?php echo $t['Slash prefixed'];?>">[?]</span><br>
		<input type="text" name="root" style="width: 200px" onkeydown="document.settings.changedFileSettings.value='true';showButton()" value="<?php echo $ICEcoder["root"];?>">
	</div>
	<br><br>

	<h2><?php echo $t['bug reporting'];?></h2>
	<?php echo $t['check in files'];?> <span class="info" title="<?php echo $t['Slash prefixed comma...'];?>">[?]</span><br>
	<input type="text" name="bugFilePaths" style="width: 120px" onkeydown="showButton()" value="<?php echo implode(", ",$ICEcoder["bugFilePaths"]);?>">
	<span style="display: inline-block; padding: 4px 5px 0 5px"><?php echo $t['every'];?></span>
	<input type="text" name="bugFileCheckTimer" style="width: 50px" onkeydown="showButton()" value="<?php echo $ICEcoder["bugFileCheckTimer"];?>">
	<span style="display: inline-block; padding: 4px 5px 0 5px"><?php echo $t['secs getting last'];?></span>
	<input type="text" name="bugFileMaxLines" style="width: 50px" onkeydown="showButton()" value="<?php echo $ICEcoder["bugFileMaxLines"];?>">
	<span style="display: inline-block; padding: 4px 5px 0 5px"><?php echo $t['lines'];?></span>
	<br><br>

	<div>
	<h2><?php echo $t['multi-user'];?> <span class="info" title="<?php echo $t['Make sure you...'];?>">[?]</span></h2>
	<input type="checkbox" name="multiUser" value="true" onclick="showButton();changeEnableRegistrationStatus();"<?php if($ICEcoder["multiUser"]){echo ' checked';} ?>>Multi-User
	<?php
		echo '<input type="checkbox" name="enableRegistration" value="true"';
		if($ICEcoder["enableRegistration"]){echo ' checked';}
		if(!$ICEcoder["multiUser"]){
		echo ' disabled=""';
		}
		echo ' onclick="showButton()" id="enableRegistration"> '.$t['Registration'].' </input>';
	?>
	</div>
	<br>

	<div>
	<h2>github</h2>
	<?php echo $t['auth token'];?> <span class="info" title="<?php echo $t['Required to get...'];?>">[?]</span> &nbsp; <a href="https://help.github.com/articles/creating-an-access-token-for-command-line-use" target="_blank" class="info">Personal Access Token</a> &nbsp; <a href="(http://developer.github.com/v3/oauth" target="_blank" class="info">Client/Secret Pair Token</a><br>
	<input type="text" name="githubAuthToken" style="width: 320px" onkeydown="showButton()" value="<?php echo $ICEcoder["githubAuthToken"];?>" autocomplete="off">
	</div>
</span>

<script>
var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
	lineNumbers: true,
	readOnly: "nocursor",
	indentUnit: top.ICEcoder.indentSize,
	tabSize: top.ICEcoder.indentSize,
	mode: "javascript",
	theme: "<?php echo $ICEcoder["theme"]=="default" ? 'icecoder' : $ICEcoder["theme"];?>"
	});

var input = document.getElementById("select");
function selectTheme() {
	var theme = input.options[input.selectedIndex].innerHTML;
	if (theme=="default") {theme = "icecoder"};
	editor.setOption("theme", theme);
}

function changeIndentSize() {
	var indentSize = document.getElementById("indentSize").value;
	editor.setOption("indentUnit", indentSize);
	editor.setOption("tabSize", indentSize);
}

function changeFontSize() {
	cMCSS = document.styleSheets[2];
	cMCSS.rules ? strCSS = 'rules' : strCSS = 'cssRules';
	cMCSS[strCSS][0].style['fontSize'] = document.getElementById("fontSize").value;
}

var changeEnableRegistrationStatus = function(){
	document.getElementById('enableRegistration').disabled=!document.getElementById('enableRegistration').disabled;
}
var showButton = function() {
	document.getElementById('updateButton').style.opacity = 1;
}

var showHideTabs = function() {
	cMCSS = document.styleSheets[2];
	cMCSS.rules ? strCSS = 'rules' : strCSS = 'cssRules';
	cMCSS[strCSS][2].style['border-left-width'] = document.settings.visibleTabs.checked ? '1px' : '0';
	cMCSS[strCSS][2].style['margin-left'] = document.settings.visibleTabs.checked ? '-1px' : '0';
}

var validatePasswords = function() {
	if (document.settings.password.value != 0 && document.settings.password.value.length<8) {
		top.ICEcoder.message('Please use at least 8 chars in the password');
	} else {
		if (document.settings.password.value != document.settings.passwordConfirm.value) {
			top.ICEcoder.message('Sorry, your passwords don\'t match')
		} else {
			document.settings.submit();
		}
	}
}
</script>

<div class="update" id="updateButton" onClick="<?php echo $ICEcoder['demoMode'] ? "top.ICEcoder.message('Sorry, can\'t commit settings in demo mode')" : "validatePasswords()"; ?>">update</div>

</div>

<input type="hidden" name="csrf" value="<?php echo $_SESSION["csrf"]; ?>">
</form>

</body>

</html>