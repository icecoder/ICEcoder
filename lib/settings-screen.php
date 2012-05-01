<?php include("settings.php");?>

<!DOCTYPE html>

<html>
<head>
<title>ICE Coder - <?php echo $versionNo;?> :: Settings Screen</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="settings-screen.css">
<link rel="stylesheet" href="../<?php echo $codeMirrorDir; ?>/lib/codemirror.css">
<script src="../<?php echo $codeMirrorDir; ?>/lib/codemirror.js"></script>
<script src="../<?php echo $codeMirrorDir; ?>/mode/javascript/javascript.js"></script>

<style type="text/css">
.CodeMirror {position: absolute; width: 0px; background-color: #ffffff; font-family: monospace}
.CodeMirror-scroll {height: 220px; width: 420px; overflow: hidden}
.cm-s-visible {display: block; top: 0px}
.cm-s-hidden {display: none; top: 4000px}
.cm-s-activeLine {background: #002 !important;}
// Make sure this next one remains the 5th item, updated with JS
.cm-tab:after {position: relative; display: inline-block; width: 0px; left: -1.4em; overflow: visible; color: #aaa; content: "<?php if($visibleTabs) {echo '\21e5';};?>";}
span.CodeMirror-matchhighlight {background: #555555}
.CodeMirror-focused span.CodeMirror-matchhighlight {color: #000000; background: #555555; !important}
</style>

<link rel="stylesheet" href="editor.css">
<?php
$themeArray = array("ambiance","blackboard","cobalt","eclipse","elegant","lesser-dark","monokai","neat","night","rubyblue","xq-dark");
for ($i=0;$i<count($themeArray)-1;$i++) {
	echo '<link rel="stylesheet" href="../'.$codeMirrorDir.'/theme/'.$themeArray[$i].'.css">'.PHP_EOL;
}
?>
</head>

<body class="settings">

<div class="infoPane">
	<img src="../images/ice-coder.gif" class="logo">
	<div class="version"><?php echo $versionNo;?></div>

	<p>
	git:<br>
	<a href="http://github.com/mattpass/ICEcoder" target="_blank">http://github.com/mattpass/ICEcoder</a>
	<br><br>

	codemirror dir:<br>
	<?php echo $codeMirrorDir; ?>
	<br><br>

	codemirror version:<br>
	<?php echo $cMThisVer; ?>
	<br><br>

	doc root:<br>
	<?php if($_SESSION['userLevel']==10) { echo $_SERVER['DOCUMENT_ROOT']; } else { echo '[HIDDEN]'; }; ?>
	<br><br><br><br>

	<div style="font-size: 10px; line-height: 12px">ICE coder by Matt Pass (<a href="http://www.twitter.com/mattpass" style="font-size: 10px" target="_blank">@mattpass</a>)<br><br>
		Free to use it for your own purposes, commercial or not, just let me know of any cool uses or customisations. :)<br><br>
		No warranty or liability accepted for anything, all responsibility of use is your own.<br><br>

		Thanks go to the following people who have inspired me to create this and in the odd case, provided feedback or code:<br>
		<?php
			$peopleArray = array("marijnjh", "maettig", "wimtibackx", "jakubvrana", "davidwalshblog", "kuvos", "paul_irish", "mathias", "rem");
			for ($i=0;$i<count($peopleArray)-1;$i++) {
				echo '<a href="http://www.twitter.com/'.$peopleArray[$i].'" style="font-size: 10px" target="_blank">@'.$peopleArray[$i].'</a>';
				if ($i<count($peopleArray)-2) {
					echo ", ";
				}
			}			
		?>
		
	</div>
	</p>
</div>

<form name="settings" action="settings.php" method="POST">
<div class="settingsColumn1">
<h1>settings</h1>
<h2>functionality</h2>
<input type="checkbox" onclick="showButton()" name="tabsIndent" value="true"<?php if($tabsIndent) {echo ' checked';};?>> tab indents selection<br>
<input type="checkbox" onclick="showButton()" name="testcMVersion" value="true"<?php if($testcMVersion) {echo ' checked';};?>> test codemirror version on load<br>
<input type="checkbox" onclick="showButton()" name="openLastFiles" value="true"<?php if($openLastFiles) {echo ' checked';};?>> auto open last files on login<br>
<br>

<h2>assisting</h2>
<input type="checkbox" onclick="showButton()" name="codeAssist" value="true"<?php if($codeAssist) {echo ' checked';};?>> code assist<br>
<input type="checkbox" onclick="showButton();showHideTabs()" name="visibleTabs" value="true"<?php if($visibleTabs) {echo ' checked';};?>> visible tabs<br>
<input type="checkbox" onclick="showButton()" name="lockedNav" value="true"<?php if($lockedNav) {echo ' checked';};?>> locked nav<br>
<br>

<h2>security</h2>
new password <span style="font-size: 10px; color: #888888">8 chars</span><br>
<input type="password" name="accountPassword" onkeydown="showButton()"><br>
confirm password<br>
<input type="password" name="confirmPassword" onkeydown="showButton()"><br>
<input type="hidden" name="oldPassword" value="<?php echo $accountPassword; ?>">
<br>
restricted files/folders<br>
<input type="text" onkeydown="document.settings.changedFileSettings.value='true';showButton()" name="restrictedFiles" value="<?php for($i=0;$i<=count($restrictedFiles)-1;$i++) {echo $restrictedFiles[$i]; if ($i<count($restrictedFiles)-1) {echo ', ';};}; ?>"><br>
banned files/folders<br>
<input type="text" onkeydown="document.settings.changedFileSettings.value='true';showButton()" name="bannedFiles" value="<?php for($i=0;$i<=count($bannedFiles)-1;$i++) {echo $bannedFiles[$i]; if ($i<count($bannedFiles)-1) {echo ', ';};}; ?>"><br>
<input type="hidden" name="changedFileSettings" value="false">
<br>
ip addresses<br>
<input type="text" onkeydown="showButton()" name="allowedIPs" value="<?php for($i=0;$i<=count($allowedIPs)-1;$i++) {echo $allowedIPs[$i]; if ($i<count($allowedIPs)-1) {echo ', ';};}; ?>"><br>
</div>

<div class="settingsColumn2">
<h2>plugins</h2>
plugins array <span style="font-size: 10px; color: #888888">name, img src, style, url, target, setInterval (mins)</span><br>
<textarea name="plugins" class="plugins" onkeydown="showButton()"><?php
for($i=0;$i<count($plugins);$i++) {
	for($j=0;$j<count($plugins[$i]);$j++) {
		echo '"'.$plugins[$i][$j].'"';
		if ($j<count($plugins[$i])-1) {
			echo ',';
		};
		if (!($i==count($plugins)-1 && $j==count($plugins[$i])-1)) {
			echo PHP_EOL;
		}
		if (($i<count($plugins)-1 && $j==count($plugins[$i])-1)) {
			echo "====================".PHP_EOL;
		}
	}
}
?></textarea>
<br><br>

<h2>style</h2>
theme<br>
<select onchange="selectTheme();showButton()" id="select" name="theme">
    <option<?php if ($theme=="default") {echo ' selected';}; ?>>default</option>
<?php
for ($i=0;$i<count($themeArray)-1;$i++) {
	if ($theme==$themeArray[$i]) {$optionSelected = ' selected';} else {$optionSelected = '';};
	echo '<option'.$optionSelected.'>'.$themeArray[$i].'</option>'.PHP_EOL;
}
?>
</select>
<br><br>

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

<script>
var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
	lineNumbers: true,
	readOnly: "nocursor",
	theme: "<?php if ($theme=="default") {echo 'icecoder';} else {echo $theme;}; ?>"
	});

var input = document.getElementById("select");
function selectTheme() {
	var theme = input.options[input.selectedIndex].innerHTML;
	if (theme=="default") {theme = "icecoder"};
	editor.setOption("theme", theme);
}

var showButton = function() {
	document.getElementById('updateButton').style.opacity = 1;
}

var showHideTabs = function() {
	document.all ? strCSS = 'rules' : strCSS = 'cssRules';
	document.settings.visibleTabs.checked ? document.styleSheets[2][strCSS][5].style['content'] = '"\\21e5"' : document.styleSheets[2][strCSS][5].style['content'] = '" "';
}

var validatePasswords = function() {
	<?php if($_SESSION['userLevel']==10) { ?>
	if (document.settings.accountPassword.value != 0 && document.settings.accountPassword.value.length<8) {
		alert('Please use at least 8 chars in the password');
	} else {
		if (document.settings.accountPassword.value != document.settings.confirmPassword.value) {
			alert('Sorry, your passwords don\'t match')
		} else {
			document.settings.submit();
		}
	}
	<?php } else { ?>
		alert('Sorry, you need to be logged in to change settings');
	<?php ;}; ?>
}
</script>

<div class="update" id="updateButton" onClick="validatePasswords()">update</div>

</div>

</form>

</body>

</html>