<?php
include "lib/headers.php";
include "lib/settings.php";
$t = $text['editor'];
?>
<!DOCTYPE html>

<html style="margin: 0" onmousedown="parent.ICEcoder.mouseDown = true; parent.ICEcoder.resetAutoLogoutTimer()" onmouseup="parent.ICEcoder.mouseDown = false; parent.ICEcoder.mouseDownInCM = false; parent.ICEcoder.resetAutoLogoutTimer(); if (!parent.ICEcoder.overCloseLink) {parent.ICEcoder.tabDragEnd()}" onmousemove="if (parent.ICEcoder) {parent.ICEcoder.getMouseXY(event, 'editor'); parent.ICEcoder.functionArgsTooltip(event, 'editor'); parent.ICEcoder.resetAutoLogoutTimer(); parent.ICEcoder.canResizeFilesW()}" ondrop="if (parent.ICEcoder) {parent.ICEcoder.getMouseXY(event, 'editor')}">
<head>
<title>ICEcoder <?php echo $ICEcoder["versionNo"];?> editor</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" href="<?php echo $settingsClass->assetsRoot;?>/css/codemirror.css?microtime=<?php echo microtime(true);?>">
<link rel="stylesheet" href="<?php echo $settingsClass->assetsRoot;?>/css/show-hint.css?microtime=<?php echo microtime(true);?>">
<link rel="stylesheet" href="<?php echo $settingsClass->assetsRoot;?>/css/lint.css?microtime=<?php echo microtime(true);?>">
<!--
codemirror-compressed.js
- incls: codemirror
- modes: clike, coffeescript, css, erlang, go, htmlmixed, javascript, julia, lua, markdown, perl, php, python, ruby, sass, sql, xml, yaml
- addon: brace-fold, closebrackets, closetag, css-hint, foldcode, foldgutter, html-hint, javascript-hint, javascript-lint, lint, match-highlighter, matchbrackets, runmode, searchcursor, show-hint, simplescrollbars, sql-hint, trailingspace, xml-fold, xml-hint
//-->
<script src="<?php echo $settingsClass->assetsRoot;?>/js/codemirror-compressed.js?microtime=<?php echo microtime(true);?>"></script>
<?php
$pluginFiles = [
	"jshint/jshint-2.5.6.min.js",
	"emmet/emmet.min.js",
	"pesticide/pesticide.js",
	"stats.js/stats.min.js",
	"responsive-helper/responsive-helper.js"
];

for ($i = 0; $i < count($pluginFiles); $i++) {
	if (true === file_exists(dirname(__FILE__) . "/plugins/" . $pluginFiles[$i])) {
		echo '<script src="plugins/' . $pluginFiles[$i] . '?microtime=' . microtime(true) . '"></script>' . PHP_EOL;
	}
}
?>
<link rel="stylesheet" href="<?php
echo $settingsClass->assetsRoot . '/css/theme/';
echo "default" === $ICEcoder["theme"] ? 'icecoder.css' : $ICEcoder["theme"] . '.css';
echo "?microtime=" . microtime(true);
// Light themes
if (false !== array_search($ICEcoder["theme"], ["base16-light", "chrome-devtools", "duotone-light", "eclipse", "eiffel", "elegant", "mdn-like", "idle", "iplastic", "ir_white", "johnny", "juicy", "neat", "neo", "solarized", "ttcn", "xq-light"])) {
	$activeLineBG = "#ccc";
	$activeLineNum = "#222";
// Dark themes
} elseif (false !== array_search($ICEcoder["theme"], ["3024-night", "all-hallow-eve", "black-pearl-ii", "blackboard", "colorforth", "django", "emacs-strict", "fade-to-grey", "fake", "glitterbomb", "isotope", "ir_black", "liquibyte", "monokai-fannonedition", "oceanic", "night", "spectacular", "sunburst", "the-matrix", "tomorrow-night-blue", "tomorrow-night-bright", "tomorrow-night-eighties", "vibrant-ink", "xq-dark", "zenburn"])) {
	$activeLineBG = "#222";
	$activeLineNum = "#ccc";
// Other themes
} else {
	$activeLineBG = "#000";
	$activeLineNum = "#ccc";
}
?>">
<script src="<?php echo $settingsClass->assetsRoot;?>/js/mmd.js?microtime=<?php echo microtime(true);?>"></script>
<link rel="stylesheet" href="<?php echo $settingsClass->assetsRoot;?>/css/foldgutter.css?microtime=<?php echo microtime(true);?>">
<link rel="stylesheet" href="<?php echo $settingsClass->assetsRoot;?>/css/simplescrollbars.css?microtime=<?php echo microtime(true);?>">

<style type="text/css">
/* Make sure this next one remains the 1st item, updated with JS */
.CodeMirror {position: absolute; top: 0; width: 100%; font-size: <?php echo $ICEcoder["fontSize"];?>; transition: font-size 0.25s ease; line-height: 1.3; z-index: 1}
.CodeMirror-scroll {} /* was: height: auto; overflow: visible */
/* Make sure this next one remains the 3rd item, updated with JS */
.cm-s-activeLine {background: <?php echo $activeLineBG;?> !important}
.cm-matchhighlight, .CodeMirror-focused .cm-matchhighlight {color: #fff !important; background: #06c !important}
/* Make sure this next one remains the 5th item, updated with JS */
.cm-tab {border-left-width: <?php echo $ICEcoder["visibleTabs"] ? "1px" : "0";?>; margin-left: <?php echo $ICEcoder["visibleTabs"] ? "-1px" : "0";?>; border-left-style: solid; border-left-color: rgba(255,255,255,0.15)}
/* Make sure this next one remains the 6th item, updated with JS */
.cm-s-activeLine + .CodeMirror-gutter-wrapper > .CodeMirror-linenumber {color: <?php echo $activeLineNum;?> !important}
.cm-trailingspace {
        background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAQAAAACCAYAAAB/qH1jAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH3QUXCToH00Y1UgAAACFJREFUCNdjPMDBUc/AwNDAAAFMTAwMDA0OP34wQgX/AQBYgwYEx4f9lQAAAABJRU5ErkJggg==);
        background-position: bottom left;
        background-repeat: repeat-x;
      }
.code-zoomed-out {font-size: 2px}
.CodeMirror-foldmarker {font-family: arial; line-height: 0.3; color: #b00; cursor: pointer;
	text-shadow: #fff 1px 1px 2px, #fff -1px -1px 2px, #fff 1px -1px 2px, #fff -1px 1px 2px;
}
.CodeMirror-foldgutter {display: inline-block; width: 13px}
.CodeMirror-foldgutter-open, .CodeMirror-foldgutter-folded {position: absolute; display: inline-block; width: 13px; height: 13px; font-size: 14px; text-align: center; cursor: pointer}
.CodeMirror-foldgutter-open {background: rgba(255,255,255,0.04); color: #666}
.CodeMirror-foldgutter-open:after {position: relative; top: -4px}
.CodeMirror-foldgutter-folded {background: #800; color: #ddd}
.CodeMirror-foldgutter-folded:after {position: relative; top: -4px}
h2 {color: rgba(0,198,255,0.7)}
.cm-s-diff {left: 50%}
</style>
<link rel="stylesheet" href="<?php echo $settingsClass->assetsRoot;?>/css/editor.css?microtime=<?php echo microtime(true);?>">
<link rel="stylesheet" href="<?php echo $settingsClass->assetsRoot;?>/css/file-types.css?microtime=<?php echo microtime(true);?>">
<link rel="stylesheet" href="<?php echo $settingsClass->assetsRoot;?>/css/file-type-icons.css?microtime=<?php echo microtime(true);?>">
</head>

<body style="color: #fff; margin: 0" onkeydown="return parent.ICEcoder.interceptKeys('content', event);" onkeyup="parent.ICEcoder.resetKeys(event);" onblur="parent.ICEcoder.resetKeys(event);" oncontextmenu="return false">

<div class="dataMessage" id="dataMessage"></div>

<div class="dataItemsContainer">
	<div class="dataItems" style="width: 300px; margin-right: 50px">
		<h2><?php echo $t['server'];?></h2>
		<span class="heading"><?php echo $t['Server name, OS...'];?></span><br>
		<?php
        $serverAddr = $_SERVER['SERVER_ADDR'] ?? "1";
        if ("1" == $serverAddr || "::1" == $serverAddr) {
            $serverAddr = "127.0.0.1";
        }
        echo
            $_SERVER['SERVER_NAME'] . " &nbsp;&nbsp " .
            $serverType . " &nbsp;&nbsp " .
            $serverAddr . ":" . $_SERVER['SERVER_PORT'] . "<br>" .
            "(" . $_SERVER['SERVER_SOFTWARE'] . ")";?><br><br>
		<span class="heading"><?php echo $t['Root'];?></span><br>
		<?php echo $docRoot;?><br><br>
		<span class="heading"><?php echo $t['ICEcoder root'];?></span><br>
		<div id="iceRootDisplay"><?php echo "" !== $iceRoot ? $iceRoot : "[Default]";?></div><br><br>
		<span class="heading"><?php echo $t['PHP version'];?></span><br>
		<?php echo phpversion();?><br><br>
		<span class="heading"><?php echo $t['Date & time'];?></span><br>
		<span id="serverDT"></span><br><br>
		<h2><?php echo $t['your device'];?></h2>
		<span class="heading"><?php echo $t['Browser'];?></span><br>
		<?php echo xssClean($_SERVER['HTTP_USER_AGENT'], "html");?><br><br>
		<span class="heading"><?php echo $t['Your IP'];?></span><br>
		<?php echo getUserIP();?><br><br>
	</div>

	<div class="dataItems">
		<h2><?php echo $t['files'];?></h2>
		<span class="heading"><?php echo $t['Last 10 files...'];?></span><br>
		<ul class="fileManager" id="last10Files" style="margin-top: 5px; margin-left: 0; line-height: 21px"><?php
            if (empty($ICEcoder["last10Files"])) {
                echo '<div style="display: inline-block; margin-left: -39px; margin-top: -4px">' . $t['none'] . '</div><br>';
            } else {
                for ($i = count($ICEcoder["last10Files"]) - 1; $i >= 0; $i--) {
                    $fileFolderName = str_replace("\\", "/", $ICEcoder["last10Files"][$i]);
                    // Get extension (prefix 'ext-' to prevent invalid classes from extensions that begin with numbers)
                    $ext = "ext-" . pathinfo($docRoot . $iceRoot . $fileFolderName, PATHINFO_EXTENSION);
                    echo '<li class="pft-file ' . strtolower($ext) . '" style="margin-left: -21px">';
                    echo '<a style="position: relative; top: -2px; left: 5px; cursor: pointer" onclick="parent.ICEcoder.openFile(\'' . str_replace($docRoot, "", str_replace("|", "/", $ICEcoder["last10Files"][$i])) . '\')">';
                    echo preg_replace("/^\//", "", str_replace($docRoot, "", str_replace("|", "/", $ICEcoder["last10Files"][$i])));
                    echo '</a></li>';
                    if ($i > 0) {
                        echo PHP_EOL;
                    };
                }
			}
		;?></ul>
        <?php
        if ("" !== $_SESSION['username']) {
            ?>
            <h2><?php echo $t['multi-user']; ?></h2>
            <span class="heading"><?php echo $t['Username']; ?></span><br>
            <?php echo xssClean($_SESSION['username'], "html");?><br><br>
            <?php
        }
        ?>
		<h2>ICEcoder</h2>
		<span class="heading"><?php echo $t['version'];?></span><br>
		<?php
		// If we have a .git dir, get the Git short commit hash to display as a link
		$gitCommitTextLink = "";
		if (true === $systemClass->functionEnabled("shell_exec") && is_dir(dirname(__FILE__) . "/.git")) {
			$gitCommit = trim(shell_exec('git log --pretty="%h" -n1 HEAD'));
			$gitCommitTextLink = ' (Git commit: <a href="https://github.com/icecoder/ICEcoder/commit/' . $gitCommit . '" style="color: #eee; text-decoration: none" target="_blank">' . $gitCommit . '</a>)';
		}
		echo $ICEcoder["versionNo"] . $gitCommitTextLink;
		?><br><br>
		<span class="heading"><?php echo $t['codemirror version'];?></span><br>
		<script>
		document.write(CodeMirror.version);
		</script>
		<br><br>
	</div>

	<div style="clear: both"></div>
	<script>
	let nDT = <?php echo time() * 1000;?>;
	setInterval(function(){
		let s = (new Date(nDT += 1e3) + '').split(' '),
		d = s[2] * 1,
		t = s[4].split(':'),
		p = t[0] > 11 ? 'pm' : 'am',
		e = d % 20 === 1 | d > 30 ? 'st' : d % 20 === 2 ? 'nd' : d % 20 === 3 ? 'rd' : 'th';
		t[0] = --t[0] % 12 + 1;
		if (document.getElementById('serverDT')) {
			document.getElementById('serverDT').innerHTML = [s[0], d + e, s[1], s[3], t.join(':') + p].join(' ');
		}
	}, 1000);
	</script>
</div>

<script>
CodeMirror.keyMap.ICEcoder = {
	"Tab": function(cm) {
		return cm.somethingSelected()
		? (parent.ICEcoder.indentAuto
			? cm.execCommand("indentAuto") // Honor our own setting indentAuto
			: cm.indentSelection("add") // Add indent (this is default handler in CodeMirror)
		  )
		: CodeMirror.Pass // Falls through to default or Emmet plugin
	},
	"Shift-Tab": "indentLess",
	"Ctrl-Space": "autocomplete",
	"Ctrl-Up" : false,
	"Ctrl-Down" : false,
	"Ctrl-Backspace" : false,
	"Esc" : false,
	fallthrough: ["default"]
};

// CodeMirror does not honor indentWithTabs = false properly when handling Tab key
// It does this by design, so we need to make a workaround of our own
(function(){
	// let's back up original insertTab function which actually puts
	var originalInsertTabFunction = CodeMirror.commands.insertTab;
	// and replace it with our own, which branches on whether our ICEcoder.indentType value is "tabs"
	CodeMirror.commands.insertTab = function(cm){
		if ("tabs" === parent.ICEcoder.indentType){
			// if it is true, then we should still put there, let's use original function
			return originalInsertTabFunction(cm);
		} else {
			// otherwise - let's call another handler, insertSoftTab which will do the job
			return cm.execCommand("insertSoftTab");
		}
	}
}());

function createNewCMInstance(num) {
	// Establish the filename for the tab
	var fileName = parent.ICEcoder.openFiles[parent.ICEcoder.selectedTab - 1];

	var indentWithTabs = "tabs" === parent.ICEcoder.indentType;

	// Define our CodeMirror options
	var cMOptions = {
		mode: "application/x-httpd-php",
		lineNumbers: parent.ICEcoder.lineNumbers,
		gutters: ["CodeMirror-foldgutter", "CodeMirror-lint-markers", "CodeMirror-linenumbers"],
		foldGutter: {gutter: "CodeMirror-foldgutter"},
		foldOptions: {minFoldSize: 1},
		lineWrapping: parent.ICEcoder.lineWrapping,
		indentWithTabs: indentWithTabs,
		indentUnit: parent.ICEcoder.indentSize,
		tabSize: parent.ICEcoder.indentSize,
		matchBrackets: parent.ICEcoder.matchBrackets,
		electricChars: false,
		autoCloseTags: parent.ICEcoder.autoCloseTags,
		autoCloseBrackets: parent.ICEcoder.autoCloseBrackets,
		highlightSelectionMatches: true,
		scrollbarStyle: parent.ICEcoder.scrollbarStyle,
		showTrailingSpace: parent.ICEcoder.showTrailingSpace,
		lint: false,
		keyMap: "ICEcoder"
	};

	// Start editor instances, main and diff
	window['cM' + num] = CodeMirror(document.body, cMOptions);
	window['cM' + num + 'diff'] = CodeMirror(document.body, cMOptions);

	// Define actions for those...
    createNewCMInstanceEvents(num, '');
    createNewCMInstanceEvents(num, 'diff');

	// Now create the active lines for them
	parent.ICEcoder['cMActiveLinecM' + num] = window['cM' + num].addLineClass(0, "background", "cm-s-activeLine");
	parent.ICEcoder['cMActiveLinecM' + num + 'diff'] = window['cM' + num + 'diff'].addLineClass(0, "background", "cm-s-activeLine");
};

function createNewCMInstanceEvents(num, pane) {
    window['cM' + num + pane].on("focus", function(thisCM) {parent.ICEcoder.cMonFocus(thisCM, 'cM' + num + pane)});
    window['cM' + num + pane].on("blur", function(thisCM) {parent.ICEcoder.cMonBlur(thisCM, 'cM' + num + pane)});
    window['cM' + num + pane].on("keyup", function(thisCM, evt) {parent.ICEcoder.cMonKeyUp(thisCM, 'cM' + num + pane, evt)});
    window['cM' + num + pane].on("cursorActivity", function(thisCM) {parent.ICEcoder.cMonCursorActivity(thisCM, 'cM' + num + pane)});
    window['cM' + num + pane].on("beforeSelectionChange", function(thisCM, changeObj) {parent.ICEcoder . prevLine = thisCM.getCursor().line;});
    window['cM' + num + pane].on("change", function(thisCM, changeObj) {parent.ICEcoder.cMonChange(thisCM, 'cM' + num + pane, changeObj, CodeMirror)});
    window['cM' + num + pane].on("beforeChange", function(thisCM, changeObj) {parent.ICEcoder.cMonBeforeChange(thisCM, 'cM' + num + pane, changeObj, CodeMirror)});
    window['cM' + num + pane].on("scroll", function(thisCM) {parent.ICEcoder.cMonScroll(thisCM, 'cM' + num + pane)});
    window['cM' + num + pane].on("update", function(thisCM) {parent.ICEcoder.cMonUpdate(thisCM, 'cM' + num + pane)});
    window['cM' + num + pane].on("inputRead", function(thisCM) {parent.ICEcoder.cMonInputRead(thisCM, 'cM' + num + pane)});
    window['cM' + num + pane].on("gutterClick", function(thisCM, line, gutter, evt) {parent.ICEcoder.cMonGutterClick(thisCM, line, gutter, evt, 'cM' + num + pane)});
    window['cM' + num + pane].on("mousedown", function(thisCM, evt) {parent.ICEcoder.cMonMouseDown(thisCM, 'cM' + num + pane, evt)});
    window['cM' + num + pane].on("paste", function(thisCM, evt) {parent.ICEcoder.cMonPaste(thisCM, 'cM' + num + pane, evt, (evt.clipboardData || window.clipboardData))});
    window['cM' + num + pane].on("contextmenu", function(thisCM, evt) {parent.ICEcoder.cMonContextMenu(thisCM, 'cM' + num + pane, evt)});
    window['cM' + num + pane].on("dragover", function(thisCM) {parent.ICEcoder.cMonDragOver(thisCM, event, 'cM' + num + pane)});
    window['cM' + num + pane].on("renderLine", function(thisCM, line, element) {parent.ICEcoder.cMonRenderLine(thisCM, 'cM' + num + pane, line, element)});
}
</script>

<div class="resultsBar" id="resultsBar"></div>

<script>
CodeMirror.commands.autocomplete = function(cM) {
    let langType = parent.ICEcoder.caretLocType;
    if (-1 < ["JavaScript", "CoffeeScript", "TypeScript", "SQL", "CSS", "HTML", "XML", "Content"].indexOf(langType)) {
        if ("XML" === langType || "Content" === langType) {
            langType = "HTML";
        }
        CodeMirror.showHint(cM, CodeMirror.hint[langType.toLowerCase()]);
    }
}

// Switch the CodeMirror mode on demand
parent.ICEcoder.switchMode = function(mode) {
    let cM, cMdiff, fileName, fileExt;

    cM = parent.ICEcoder.getcMInstance();
    cMdiff = parent.ICEcoder.getcMdiffInstance();
    fileName = parent.ICEcoder.openFiles[parent.ICEcoder.selectedTab - 1];

    if (cM && mode) {
        if (mode != cM.getOption("mode")) {
            cM.setOption("mode", mode);
            cMdiff.setOption("mode", mode);
        }
    } else if (cM && fileName) {
		<?php include(dirname(__FILE__) . "/assets/js/language-modes-partial.js");?>
        if (mode != cM.getOption("mode")) {
            cM.setOption("mode", mode);
            cM.setOption("lint", ("js" === fileExt || "json" === fileExt) && parent.ICEcoder.codeAssist ? true : false);
            cMdiff.setOption("mode", mode);
            cMdiff.setOption("lint", ("js" === fileExt || "json" === fileExt) && parent.ICEcoder.codeAssist ? true : false);
        }
    }
}
</script>

</body>

</html>
