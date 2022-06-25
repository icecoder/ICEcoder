<?php
include "headers.php";
include "settings.php";
$t = $text['settings-screen'];

$assetsPath = "assets" === $settingsClass->assetsRoot
    ? "../" . $settingsClass->assetsRoot
    : $settingsClass->assetsRoot
?>
<!DOCTYPE html>

<html>
<head>
<title>ICEcoder <?php echo $ICEcoder["versionNo"];?> settings screen</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" type="text/css" href="<?php echo $assetsPath;?>/css/settings-screen.css?microtime=<?php echo microtime(true);?>">
<link rel="stylesheet" href="<?php echo $assetsPath;?>/css/codemirror.css?microtime=<?php echo microtime(true);?>">
<script src="<?php echo $assetsPath;?>/js/codemirror-compressed.js?microtime=<?php echo microtime(true);?>"></script>

<style type="text/css">
.CodeMirror {position: absolute; width: 309px; height: 180px; font-size: <?php echo $ICEcoder["fontSize"];?>; transition: font-size 0.25s ease}
.CodeMirror-scroll {overflow: hidden}
/* Make sure this next one remains the 3rd item, updated with JS */
.cm-tab {border-left-width: <?php echo $ICEcoder["visibleTabs"] ? "1px" : "0";?>; margin-left: <?php echo $ICEcoder["visibleTabs"] ? "-1px" : "0";?>; border-left-style: solid; border-left-color: rgba(255,255,255,0.2)}
</style>

<link rel="stylesheet" href="<?php echo $assetsPath;?>/css/theme/icecoder.css?microtime=<?php echo microtime(true);?>">
<?php
$themeArray = [];
$handle = opendir('../assets/css/theme/');
while (false !== ($file = readdir($handle))) {
	if ($file !== "." && $file !== ".." && $file !== "icecoder.css") {
		array_push($themeArray,basename($file,".css"));
	}
}
closedir($handle);
sort($themeArray);
for ($i = 0;$i < count($themeArray); $i++) {
	echo '<link rel="stylesheet" href="' . $assetsPath . '/css/theme/' . $themeArray[$i] . '.css?microtime=' . microtime(true) . '">' . PHP_EOL;
}

?>
<link rel="stylesheet" href="<?php echo $assetsPath;?>/css/simplescrollbars.css?microtime=<?php echo microtime(true);?>">
</head>

<body class="settings" onkeyup="parent.ICEcoder.handleModalKeyUp(event, 'settings')" onload="this.focus();">

<div class="infoPane">
	<a href="https://icecoder.net" target="_blank"><img src="<?php echo $assetsPath;?>/images/icecoder.png" alt="ICEcoder" class="logo"></a>

	<h1 style="margin: 10px 0"><?php echo $t['settings'];?></h1>

	<p>
	<?php echo $t['version'];?>:<br>
	<?php
	// If we have a .git dir, get the Git short commit hash to display as a link
	$gitCommitTextLink = "";
	if (true === $systemClass->functionEnabled("shell_exec") && is_dir(dirname(__FILE__) . "/../.git")) {
		$gitCommit = trim(shell_exec('git log --pretty="%h" -n1 HEAD'));
		$gitCommitTextLink = ' (Git commit: <a href="https://github.com/icecoder/ICEcoder/commit/' . $gitCommit . '" target="_blank">' . $gitCommit . '</a>)';
	}
	?>
	<?php echo $ICEcoder["versionNo"] . $gitCommitTextLink;?>
	<br><br>

	<?php echo $t['website'];?>:<br>
	<a href="https://icecoder.net" target="_blank">https://icecoder.net</a>
	<br><br>

	<?php echo $t['git'];?>:<br>
	<a href="https://github.com/icecoder/ICEcoder" target="_blank">https://github.com/icecoder/ICEcoder</a>
	<br><br>

	<?php echo $t['codemirror version'];?>:<br>
	<script>
	document.write(CodeMirror.version);
	</script>
	<br><br>

	<?php echo $t['file manager root'];?>:<br>
	<?php echo "" === $ICEcoder['root'] ? "/" : $ICEcoder['root'];?>
	<br><br>

	<div style="font-size: 10px; line-height: 12px">
		<?php echo $t['Get in contact...'];?><br>
		<a href="https://www.twitter.com/icecoder" style="font-size: 10px" target="_blank">Twitter</a><br>
		<a href="https://facebook.com/ICEcoder.net" style="font-size: 10px" target="_blank">Facebook</a><br>
		<a href="https://github.com/icecoder/ICEcoder" style="font-size: 10px" target="_blank">GitHub</a><br>
		<a href="mailto:info@icecoder.net" style="font-size: 10px">Email</a><br><br>
		<?php echo $t['You may use...'];?>
	</div>
	</p>
</div>

<form name="settings" action="settings.php" method="POST">

<div class="tabContainer">
	<div id="generalTab" class="tab tabActive" onclick="switchTab('general')">General</div>
	<div id="styleTab" class="tab" onclick="switchTab('style')">Style</div>
	<div id="accountsTab" class="tab" onclick="switchTab('accounts')">Accounts</div>
	<div id="securityTab" class="tab" onclick="switchTab('security')">Security</div>
</div>

<div id="generalSection" class="section" style="display: block">

	<div style="display: inline-block; margin-right: 40px">

		<h2>language</h2><br>
		<select onchange="showButton()" name="languageUser">
		<?php
		$langFiles = [
		    "chinese-simplified.php",
            "chinese-traditional.php",
            "dutch.php",
            "english.php",
            "french.php",
            "german.php",
            "italian.php",
            "norwegian.php",
            "persian.php",
            "portuguese-brazilian.php",
            "spanish.php"
        ];
		$langText = [
		    "中国（简体）",
            "中國（繁體）",
            "Nederlands",
            "English",
            "Français",
            "Deutsch",
            "Italiano",
            "Norsk",
            "فارسی",
            "Portugues(br)",
            "Espa&ntilde;ol"
        ];
		for ($i = 0; $i < count($langFiles); $i++) {
			echo '<option value="' . $langFiles[$i] . '"' . ($ICEcoder["languageUser"] === $langFiles[$i] ? ' selected' : '') . '>' . $langText[$i] . '</option>' . PHP_EOL;
		}
		?>
		</select> <span class="info" style="display: inline-block; padding-top: 2px" title="Reload required after changing"><?php echo file_get_contents(dirname(__FILE__) . "/../assets/images/icons/info-circle.svg");?></span>
		<br><br>

		<h2><?php echo $t['functionality'];?></h2><br>
		<input type="checkbox" onclick="showButton()" name="checkUpdates" value="true"<?php if (true === $ICEcoder["checkUpdates"]) {echo ' checked';};?>> <?php echo $t['check for updates...'];?><br>
		<input type="checkbox" onclick="showButton()" name="openLastFiles" value="true"<?php if (true === $ICEcoder["openLastFiles"]) {echo ' checked';};?>> <?php echo $t['auto open last...'];?><br>
		<input type="checkbox" onclick="showButton()" name="updateDiffOnSave" value="true"<?php if (true === $ICEcoder["updateDiffOnSave"]) {echo ' checked';};?>> update diff pane on save
		<br><br>

		<h2><?php echo $t['assisting'];?></h2><br>
		<input type="checkbox" onclick="showButton()" name="codeAssist" value="true"<?php if($ICEcoder["codeAssist"]) {echo ' checked';};?>> <?php echo $t['code assist'];?><br>
		<br>
		<?php echo $t['tag wrapper command'];?><br>
		<select onchange="showButton()" name="tagWrapperCommand">
			<option value="ctrl+alt"<?php if ($ICEcoder["tagWrapperCommand"]=='ctrl+alt') {echo " selected";};?>>ctrl/cmd + alt</option>
			<option value="alt-left"<?php if ($ICEcoder["tagWrapperCommand"]=='alt-left') {echo " selected";};?>>alt left</option>
		</select>
		<br><br>

		<?php echo $t['auto-complete on'];?><br>
		<select onchange="showButton()" name="autoComplete">
			<option value="ctrl+space"<?php if ($ICEcoder["autoComplete"]=='ctrl+space') {echo " selected";};?>>ctrl/cmd + space</option>
			<option value="keypress"<?php if ($ICEcoder["autoComplete"]=='keypress') {echo " selected";};?>>keypress</option>
		</select>
		<br><br>

		<h2>go to line</h2><br>
		scroll speed<br>
		<input type="range" name="goToLineScrollSpeed" min="1" max="20" value="<?php echo $ICEcoder["goToLineScrollSpeed"];?>" onchange="showButton()" style="width: 150px"><br>
		<div style="position: relative; width: 150px; padding: 0 0 5px 5px; color: #888">instant<div style="position: absolute; top: 0; right: 0">slow</div></div>
	</div>

	<div style="display: inline-block">

		<h2>find &amp; replace</h2><br>
		<?php echo $t['when finding in...'];?>:<br>
		<input type="text" onkeydown="showButton()" name="findFilesExclude" style="width: 300px" value="<?php echo implode(", ",$ICEcoder["findFilesExclude"]); ?>"><br><br>
		<input type="checkbox" onclick="showButton()" name="selectNextOnFindInput" value="true"<?php if (true === $ICEcoder["selectNextOnFindInput"]) {echo ' checked';};?>> select next result on find input
		<br><br>

		<h2><?php echo $t['bug reporting'];?></h2><br>
		<?php echo $t['check in files'];?> <span class="info" title="<?php echo $t['Slash prefixed comma...'];?>"><?php echo file_get_contents(dirname(__FILE__) . "/../assets/images/icons/info-circle.svg");?></span><br>
		<input type="text" name="bugFilePaths" style="width: 300px" onkeydown="showButton()" value="<?php echo implode(", ", $ICEcoder["bugFilePaths"]);?>"><br>
		<span style="display: inline-block; padding: 6px 5px 0 0">...<?php echo $t['every'];?></span>
		<input type="text" name="bugFileCheckTimer" style="width: 50px; margin-top: 3px" onkeydown="showButton()" value="<?php echo $ICEcoder["bugFileCheckTimer"];?>">
		<span style="display: inline-block; padding: 6px 5px 0 5px"><?php echo $t['secs getting last'];?></span>
		<input type="text" name="bugFileMaxLines" style="width: 50px; margin-top: 3px" onkeydown="showButton()" value="<?php echo $ICEcoder["bugFileMaxLines"];?>">
		<span style="display: inline-block; padding: 6px 5px 0 5px"><?php echo $t['lines'];?></span>
		<br><br>

		<h2><?php echo $t['file manager'];?></h2><br>
		<?php echo $t['root'];?> <span class="info" title="<?php echo $t['Slash prefixed'];?>"><?php echo file_get_contents(dirname(__FILE__) . "/../assets/images/icons/info-circle.svg");?></span><br>
		<input type="text" name="root" style="width: 300px" onkeydown="document.settings.changedFileSettings.value = 'true'; showButton()" value="<?php echo $ICEcoder["root"];?>">
		<br><br>

		<h2><?php echo $t['backups'];?></h2><br>
		<input type="checkbox" onclick="showButton(); changeBackupsDaysStatus();" name="backupsKept" value="true"<?php if ($ICEcoder["backupsKept"]) {echo ' checked';};?>> <?php echo $t['keep version control...'];?> <input type="text" name="backupsDays" id="backupsDays" style="width: 50px; margin: 3px 5px 0 5px" onkeydown="document.settings.changedFileSettings.value = 'true'; showButton()" value="<?php echo $ICEcoder["backupsDays"];?>" <?php
			if (false === $ICEcoder["backupsKept"]){
			echo ' disabled=""';
			}?>> <?php echo $t['days'];?><br>
		<div style="padding: 5px 5px 5px 5px; color: #888">
		<?php
		// Display number of days backups available
		$backupDirBase = str_replace("\\", "/", dirname(__FILE__)) . "/../data/backups/";
		$backupDirHost = "localhost";
		if (true === is_dir($backupDirBase . $backupDirHost)) {
			$backupDirsList = scandir($backupDirBase . $backupDirHost);
			// Remove . and .. from array
			for ($i = 0; $i < count($backupDirsList); $i++) {
				if ($backupDirsList[$i] === "." || $backupDirsList[$i] === "..") {
					array_splice($backupDirsList, $i, 1);
					$i--;
				}
			}
		} else {
			$backupDirsList = [];
		}
		// Display text re the number of days backups have taken place
		$backupNumDays = "" != $backupDirsList[0] && count($backupDirsList) > 0 ? count($backupDirsList) : 0;
		echo $backupNumDays . " " . (1 !== $backupNumDays ? $t['days'] : $t['day']) . " " . $t['of backups stored...'];
		?>
		</div><br>

		<input type="checkbox" onclick="showButton();" name="deleteToTmp" value="true"<?php if ($ICEcoder["deleteToTmp"]) {echo ' checked';};?>> <?php echo $t['deleting actually moves...'];?> <span class="info" title="<?php echo $t['local/server items...'];?>" style="position: absolute; margin-top: 6px"> &nbsp; <?php echo file_get_contents(dirname(__FILE__) . "/../assets/images/icons/info-circle.svg");?></span>
		<br><br>

	</div>
</div>

<div id="styleSection" class="section" style="display: none">

	<div style="display: inline-block; width: 300px; margin-right: 35px">
		<div style="height: 220px">
			<h2>preview</h2><br>
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
		</div>
		<br><br>

		<h2><?php echo $t['layout'];?></h2><br>
		<input type="checkbox" onclick="showButton()" name="lockedNav" value="true"<?php if (true === $ICEcoder["lockedNav"]) {echo ' checked';};?>> <?php echo $t['locked nav'];?><br><br>
		<?php echo $t['plugin panel aligned'];?><br>
		<select onchange="showButton()" name="pluginPanelAligned">
			<option value="left"<?php if ("left" === $ICEcoder["pluginPanelAligned"]) {echo " selected";};?>>left</option>
			<option value="right"<?php if ("right" === $ICEcoder["pluginPanelAligned"]) {echo " selected";};?>>right</option>
		</select>
		<br><br>

	</div><div style="display: inline-block">
		<h2><?php echo $t['style'];?></h2><br>
		<?php echo $t['theme'];?><br>
		<select onchange="selectTheme(); showButton()" id="theme" name="theme" style="width: 145px">
		    <option value="default" <?php if ("default" === $ICEcoder["theme"]) {echo ' selected';}; ?>>Default</option>
		<?php
		$lightThemes = ["base16-light", "chrome-devtools", "duotone-light", "eclipse", "eiffel", "elegant", "idle", "iplastic", "ir_white", "johnny", "juicy", "mdn-like", "neat", "neo", "solarized", "ttcn", "xq-light"];
		$midThemes = ["ambiance", "clouds-midnight", "darkpastel", "friendship-bracelet", "idlefingers", "lesser-dark", "lowlight", "mbo", "monoindustrial", "monokai", "monokai-bright", "mreq", "nightlion", "panda-syntax", "pastel-on-dark", "railscasts", "rdark", "zenburn"];
		$colorThemes = ["2019-torres-digital-theme", "amy", "bespin", "blackboard", "cobalt", "django", "dracula", "duotone-dark", "erlang-dark", "hopscotch", "made-of-code", "material", "midnight", "night", "oceanic", "paraiso-dark", "plasticcodewrap", "rubyblue", "tomorrow-night-blue", "xq-dark"];
		
		function getThemeDisplayName($optionName) {
			$wordCasings = [
				"Ii" => "II",
				"Ir" => "IR",
				"Mdn" => "MDN",
				"Ttcn" => "TTCN",
				"Xq" => "XQ"
			];

			$optionName = ucwords(preg_replace("/_|\-/", " ", $optionName));
			foreach ($wordCasings as $key => $value) {
				$optionName = str_replace($key, $value, $optionName);
			}

			return $optionName;
		}
		

		echo '<optgroup label="Dark">';
		for ($i = 0;$i < count($themeArray); $i++) {
			if (
				false === in_array($themeArray[$i], $lightThemes) &&
				false === in_array($themeArray[$i], $midThemes) &&
				false === in_array($themeArray[$i], $colorThemes)
			) {
				$optionSelected = $ICEcoder["theme"] === $themeArray[$i] ? ' selected' : '';
				$optionName = getThemeDisplayName($themeArray[$i]);
				echo '<option value="' . $themeArray[$i] . '" ' . $optionSelected . '>' . $optionName . '</option>' . PHP_EOL;
			}
		}
		echo '</optgroup>';
		echo '<optgroup label="Grey">';
		for ($i = 0;$i < count($themeArray); $i++) {
			if (true === in_array($themeArray[$i], $midThemes)) {
				$optionSelected = $ICEcoder["theme"] === $themeArray[$i] ? ' selected' : '';
				$optionName = getThemeDisplayName($themeArray[$i]);
				echo '<option value="' . $themeArray[$i] . '" ' . $optionSelected . '>' . $optionName . '</option>' . PHP_EOL;
			}
		}
		echo '</optgroup>';
		echo '<optgroup label="Color">';
		for ($i = 0;$i < count($themeArray); $i++) {
			if (true === in_array($themeArray[$i], $colorThemes)) {
				$optionSelected = $ICEcoder["theme"] === $themeArray[$i] ? ' selected' : '';
				$optionName = getThemeDisplayName($themeArray[$i]);
				echo '<option value="' . $themeArray[$i] . '" ' . $optionSelected . '>' . $optionName . '</option>' . PHP_EOL;
			}
		}
		echo '</optgroup>';
		echo '<optgroup label="Light">';
		for ($i = 0;$i < count($themeArray); $i++) {
			if (true === in_array($themeArray[$i], $lightThemes)) {
				$optionSelected = $ICEcoder["theme"] === $themeArray[$i] ? ' selected' : '';
				$optionName = getThemeDisplayName($themeArray[$i]);
				echo '<option value="' . $themeArray[$i] . '" ' . $optionSelected . '>' . $optionName . '</option>' . PHP_EOL;
			}
		}
		echo '</optgroup>';
		?>
		</select>
		<br><br>

		<div style="display: inline-block; width: 145px">
			<?php echo $t['font size'];?><br>
			<input type="text" name="fontSize" id="fontSize" style="width: 44px" onkeydown="showButton()" onkeyup="changeFontSize()" value="<?php echo $ICEcoder["fontSize"];?>">
		</div><div style="display: inline-block">
			<?php echo $t['indent size'];?><br>
			<input type="text" name="indentSize" id="indentSize" style="width: 44px" onkeydown="showButton()" onkeyup="changeIndentSize()" value="<?php echo $ICEcoder["indentSize"];?>">
		</div>
		<br><br>

		<div style="display: inline-block; width: 145px">
            <input type="checkbox" onclick="showButton()" name="matchBrackets" value="true"<?php if (true === $ICEcoder["matchBrackets"]) {echo ' checked';};?>> <?php echo $t['match brackets'];?><br>
		</div><div style="display: inline-block">
            <input type="checkbox" onclick="showButton()" name="showTrailingSpace" value="true"<?php if (true === $ICEcoder["showTrailingSpace"]) {echo ' checked';};?>> <?php echo $t['show trailing space'];?><br>
		</div>
        <br>

		<div style="display: inline-block; width: 145px">
            <input type="checkbox" onclick="showButton()" name="lineWrapping" value="true"<?php if (true === $ICEcoder["lineWrapping"]) {echo ' checked';};?>> <?php echo $t['line wrapping'];?><br>
		</div><div style="display: inline-block">
            <input type="checkbox" onclick="changeLineNumbersToggle(); showButton()" name="lineNumbers" id="lineNumbers" value="true"<?php if (true === $ICEcoder["lineNumbers"]) {echo ' checked';};?>> <?php echo $t['line numbers'];?><br>
		</div>
		<br>

        <input type="checkbox" onclick="showButton();showHideTabs()" name="visibleTabs" value="true"<?php if (true === $ICEcoder["visibleTabs"]) {echo ' checked';};?>> <?php echo $t['visible tabs'];?>
        <br><br>

        <div style="display: inline-block; width: 145px">
            <?php echo $t['scrollbars'];?><br>
            <select onchange="changeScrollbarStyle(); showButton()" name="scrollbarStyle" id="scrollbarStyle">
                <option value="overlay"<?php if ($ICEcoder["scrollbarStyle"] === "overlay") {echo " selected";};?>>overlay</option>
                <option value="simple"<?php if ($ICEcoder["scrollbarStyle"] === "simple") {echo " selected";};?>>simple</option>
                <option value="native"<?php if ($ICEcoder["scrollbarStyle"] === "native") {echo " selected";};?>>native</option>
            </select>
        </div>
        <br><br>

		<h2><?php echo $t['functionality'];?></h2><br>

		<div style="display: inline-block; width: 145px">
			<?php echo $t['indent type'];?><br>
			<select onchange="showButton()" name="indentType">
				<option value="spaces"<?php if ("spaces" === $ICEcoder["indentType"]) {echo " selected";};?>>spaces</option>
				<option value="tabs"<?php if ("tabs" === $ICEcoder["indentType"]) {echo " selected";};?>>tabs</option>
			</select>
			<br><br>

		</div><div style="display: inline-block">
            <input type="checkbox" onclick="showButton()" name="indentAuto" value="true"<?php if (true === $ICEcoder["indentAuto"]) {echo ' checked';};?>> <?php echo $t['auto indent'];?><br>
            <input type="checkbox" onclick="showButton()" name="autoCloseTags" value="true"<?php if (true === $ICEcoder["autoCloseTags"]) {echo ' checked';};?>> <?php echo $t['auto close tags'];?><br>
            <input type="checkbox" onclick="showButton()" name="autoCloseBrackets" value="true"<?php if (true === $ICEcoder["autoCloseBrackets"]) {echo ' checked';};?>> <?php echo $t['auto close brackets'];?><br>
		</div>
		<br><br>

	</div>

</div>

<div id="accountsSection" class="section" style="display: none">

	<h2>password</h2><br>
	<span id="newPasswordText"><?php echo $t['new password'];?></span><br>
	<input type="password" name="password" style="width: 320px" id="password" onkeydown="showButton()" onkeyup="checkCase(event); pwStrength(this.value)" onchange="pwStrength(this.value)" onpaste="pwStrength(this.value)"><div class="iconCapsLock" style="display: none" id="iconCapsLock" title="Caps lock on"><?php echo file_get_contents(dirname(__FILE__) . "/../assets/images/icons/alert-triangle.svg");?></div>
	<div id="pwReqs">
		<div style="display: inline-block" id="pwChars">10+</div> &nbsp;
		<div style="display: inline-block" id="pwUpper">upper</div> &nbsp;
		<div style="display: inline-block" id="pwLower">lower</div> &nbsp;
		<div style="display: inline-block" id="pwNum">number</div> &nbsp;
		<div style="display: inline-block" id="pwSpecial">special</div>
	</div>
	<br>

	<span id="passwordConfirmText"><?php echo $t['confirm password'];?></span><br>
	<input type="password" name="passwordConfirm" style="width: 320px" id="passwordConfirm" onkeydown="showButton()">
	<br><br>

	<h2><?php echo $t['multi-user'];?> <span class="info" title="<?php echo $t['Make sure you...'];?>"><?php echo file_get_contents(dirname(__FILE__) . "/../assets/images/icons/info-circle.svg");?></span></h2><br>
	<input type="checkbox" name="multiUser" value="true" onclick="showButton(); changeEnableRegistrationStatus();"<?php if (true === $ICEcoder["multiUser"]) {echo ' checked';} ?>>Multi-User
	<?php
		echo '<input type="checkbox" name="enableRegistration" value="true"';
		if (true === $ICEcoder["enableRegistration"]) {echo ' checked';}
		if (false === $ICEcoder["multiUser"]){
		echo ' disabled=""';
		}
		echo ' onclick="showButton()" id="enableRegistration"> ' . $t['Registration'] . ' </input>';
	?>

    <br><br>

    <input type="checkbox" onclick="showButton()" name="tutorialOnLogin" value="true"<?php if (true === $ICEcoder["tutorialOnLogin"]) {echo ' checked';};?>> Tutorial on Login<br><br>
</div>

<div id="securitySection" class="section" style="display: none">

	<h2><?php echo $t['security'];?></h2><br>
	<?php echo $t['banned files/folders'];?> <span class="info" title="<?php echo $t['Comma delimited'];?>"><?php echo file_get_contents(dirname(__FILE__) . "/../assets/images/icons/info-circle.svg");?></span><br>
	<input type="text" onkeydown="document.settings.changedFileSettings.value = 'true'; showButton()" name="bannedFiles" style="width: 660px" value="<?php echo implode(", ",$ICEcoder["bannedFiles"]); ?>">
	<br><br>

	<?php echo $t['banned paths'];?> <span class="info" title="<?php echo $t['Slash prefixed comma...'];?>"><?php echo file_get_contents(dirname(__FILE__) . "/../assets/images/icons/info-circle.svg");?></span><br>
	<input type="text" onkeydown="document.settings.changedFileSettings.value = 'true'; showButton()" name="bannedPaths" style="width: 660px" value="<?php echo implode(", ",$ICEcoder["bannedPaths"]); ?>">
	<br><br>

	<input type="hidden" name="changedFileSettings" value="false">
	<?php echo $t['ip addresses'];?> <span class="info" title="<?php echo $t['Comma delimited'];?>"><?php echo file_get_contents(dirname(__FILE__) . "/../assets/images/icons/info-circle.svg");?></span><br>
	<input type="text" onkeydown="showButton()" name="allowedIPs" style="width: 660px" value="<?php echo implode(", ",$ICEcoder["allowedIPs"]); ?>">
	<br><br>

	<?php echo $t['auto-logout after'];?> <span class="info" title="<?php echo $t['Set 0 to...'];?>"><?php echo file_get_contents(dirname(__FILE__) . "/../assets/images/icons/info-circle.svg");?></span><br>
	<input type="text" onkeydown="showButton()" name="autoLogoutMins" style="width: 100px" value="<?php echo $ICEcoder["autoLogoutMins"]; ?>"> <span style="display: inline-block; padding: 2px 5px"><?php echo $t['mins of inactivity...'];?></span>
	<br><br>
</div>

<script>
// Get any elem by ID
const get = function(elem) {
	return document.getElementById(elem);
};

var editor = CodeMirror.fromTextArea(get("code"), {
	lineNumbers: parent.ICEcoder.lineNumbers,
	readOnly: "nocursor",
	indentUnit: parent.ICEcoder.indentSize,
	tabSize: parent.ICEcoder.indentSize,
	mode: "javascript",
	theme: "<?php echo "default" === $ICEcoder["theme"] ? 'icecoder' : $ICEcoder["theme"];?>",
    scrollbarStyle: parent.ICEcoder.scrollbarStyle
});

function selectTheme() {
    const input = get("theme");
	let theme = input.options[input.selectedIndex].value;
	if ("default" === theme) {theme = "icecoder"}
	editor.setOption("theme", theme);
}

function changeIndentSize() {
	const indentSize = get("indentSize").value;
	editor.setOption("indentUnit", indentSize);
	editor.setOption("tabSize", indentSize);
	editor.refresh();
}


function changeLineNumbersToggle() {
	const lineNumbers = get("lineNumbers").checked;
	editor.setOption("lineNumbers", lineNumbers);
	editor.refresh();
}

function changeScrollbarStyle() {
    const scrollbarStyle = get("scrollbarStyle").value;
    editor.setOption("scrollbarStyle", scrollbarStyle);
    editor.refresh();
}

function changeFontSize() {
	let cMCSS = document.styleSheets[2];
	let strCSS = cMCSS.rules ? 'rules' : 'cssRules';
	cMCSS[strCSS][0].style['fontSize'] = get("fontSize").value;
	editor.refresh();
}

function changeEnableRegistrationStatus(){
	get('enableRegistration').disabled = !get('enableRegistration').disabled;
}

function changeBackupsDaysStatus(){
	get('backupsDays').disabled = !get('backupsDays').disabled;
}

function showButton() {
	get('updateButton').style.opacity = 1;
}

function showHideTabs() {
	let cMCSS = document.styleSheets[2];
    let strCSS = cMCSS.rules ? 'rules' : 'cssRules';
	cMCSS[strCSS][2].style['border-left-width'] = document.settings.visibleTabs.checked ? '1px' : '0';
	cMCSS[strCSS][2].style['margin-left'] = document.settings.visibleTabs.checked ? '-1px' : '0';
}

// Check password strength and color requirements not met
const pwStrength = function(pw) {
	// Set variables
    const hlCol = "rgba(0, 198, 255, 0.7)";
	let chars, upper, lower, num, special;

	// Test password for requirements
	chars = pw.length >= 10;
	upper = pw.replace(/[A-Z]/g, "").length < pw.length;
	lower = pw.replace(/[a-z]/g, "").length < pw.length;
	num = pw.replace(/[0-9]/g, "").length < pw.length;
	special = pw.replace(/[A-Za-z0-9]/g, "").length > 0;

	// Set colors based on each requirements
	get("pwChars").style.color = true === chars ? hlCol : "";
	get("pwUpper").style.color = true === upper ? hlCol : "";
	get("pwLower").style.color = true === lower ? hlCol : "";
	get("pwNum").style.color = true === num ? hlCol : "";
	get("pwSpecial").style.color = true === special ? hlCol : "";

	// Return a bool based on meeting the requirements
	return (true === chars && true === upper && true === lower && true === num && true === special);
};

const checkCase = function(evt) {
    const key = evt.keyCode ?? evt.which ?? evt.charCode;

    // Not caps lock key
    if (20 !== key) {
        get("iconCapsLock").style.display = true === evt.getModifierState("CapsLock")
            ? "inline-block"
            : "none";
    }
};

// Check if we can submit, else shake requirements
const checkCanSubmit = function() {
	// Password isn't strong enough, shake requirements
	if("" !== get("password").value && false === pwStrength(get("password").value)) {
		switchTab('accounts');
		shake("newPasswordText");
		shake("password");
		shake("pwReqs");
		return false;
	}
	return true;
}

function validatePasswords() {
	if (true === checkCanSubmit()) {
		if (document.settings.password.value !== document.settings.passwordConfirm.value) {
			switchTab('accounts');
            shake("passwordConfirmText");
            shake("passwordConfirm");
		} else {
			document.settings.submit();
		}
	}
}

function shake(elem) {
	var posArray = [24, -24, 12, -12, 6, -6, 3, -3, 0];
	var pos = -1;
	var anim = setInterval(function() {
		if (pos < posArray.length) {
			pos++;
			get(elem).style.marginLeft = posArray[pos] + "px";
		} else {
			clearInterval(anim);
		}
	}, 50);
}

tabNames = ['general','style','accounts','security'];

function switchTab(tab) {
	for (var i = 0; i < tabNames.length; i++) {
		get(tabNames[i] + 'Tab').className = tabNames[i] === tab ? "tab tabActive" : "tab";
		get(tabNames[i] + 'Section').style.display = tabNames[i] === tab ? "block" : "none";
	}
	editor.refresh();
}

function submitSettings() {
    <?php echo true === $ICEcoder['demoMode'] ? "parent.ICEcoder.message('Sorry, can\'t commit settings in demo mode')" : "validatePasswords()"; ?>;
}
<?php
// Do we have a tab to switch to?
if (true === isset($_GET['tab'])) {
    echo "switchTab('" . $_GET['tab'] . "');";
}
?>
</script>

<div class="update" id="updateButton" onclick="submitSettings()">update</div>
<input type="hidden" name="csrf" value="<?php echo $_SESSION["csrf"]; ?>">
</form>

<?php
echo $systemClass->getDemoModeIndicator(false);
?>

</body>

</html>
