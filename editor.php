<?php include("lib/settings.php");?>
<!DOCTYPE html>

<html style="margin: 0" onMouseDown="top.ICEcoder.mouseDown=true" onMouseUp="top.ICEcoder.mouseDown=false; top.ICEcoder.tabDragEnd()" onMouseMove="if(top.ICEcoder) {top.ICEcoder.getMouseXY(event,'editor');top.ICEcoder.canResizeFilesW()}">
<head>
<title>ICEcoder v <?php echo $ICEcoder["versionNo"];?> editor</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" href="<?php echo $ICEcoder["codeMirrorDir"]; ?>/lib/codemirror.css">
<link rel="stylesheet" href="<?php echo $ICEcoder["codeMirrorDir"]; ?>/addon/hint/simple-hint.css">
<!--
codemirror-compressed.js
incls:	codemirror
modes:	clike, coffeescript, css, javascript, less, php, ruby & xml
utils:	foldcode, searchcursor, match-highlighter, simple-hint, javascript-hint, closetag
//-->
<script src="<?php echo $ICEcoder["codeMirrorDir"]; ?>/lib/codemirror-compressed.js"></script>
<script>
// -- HOTFIX
// to CodeMirrors tagRangeFinder
// -- Can delete this when CodeMirror 3.2 is released
CodeMirror.tagRangeFinder = (function() {
  var nameStartChar = "A-Z_a-z\\u00C0-\\u00D6\\u00D8-\\u00F6\\u00F8-\\u02FF\\u0370-\\u037D\\u037F-\\u1FFF\\u200C-\\u200D\\u2070-\\u218F\\u2C00-\\u2FEF\\u3001-\\uD7FF\\uF900-\\uFDCF\\uFDF0-\\uFFFD";
  var nameChar = nameStartChar + "\-\:\.0-9\\u00B7\\u0300-\\u036F\\u203F-\\u2040";
  var xmlTagStart = new RegExp("<(/?)([" + nameStartChar + "][" + nameChar + "]*)", "g");

  return function(cm, start) {
    var line = start.line, ch = start.ch, lineText = cm.getLine(line);

    function nextLine() {
      if (line >= cm.lastLine()) return;
      ch = 0;
      lineText = cm.getLine(++line);
      return true;
    }
    function toTagEnd() {
      for (;;) {
        var gt = lineText.indexOf(">", ch);
        if (gt == -1) { if (nextLine()) continue; else return; }
        var lastSlash = lineText.lastIndexOf("/", gt);
        var selfClose = lastSlash > -1 && /^\s*$/.test(lineText.slice(lastSlash + 1, gt));
        ch = gt + 1;
        return selfClose ? "selfClose" : "regular";
      }
    }
    function toNextTag() {
      for (;;) {
        xmlTagStart.lastIndex = ch;
        var found = xmlTagStart.exec(lineText);
        if (!found) { if (nextLine()) continue; else return; }
        ch = found.index + found[0].length;
        return found;
      }
    }

    var stack = [], startCh;
    for (;;) {
      var openTag = toNextTag(), end;
      if (!openTag || line != start.line || !(end = toTagEnd())) return;
      if (!openTag[1] && end != "selfClose") {
        stack.push(openTag[2]);
        startCh = ch;
        break;
      }
    }

    for (;;) {
      var next = toNextTag(), end, tagLine = line, tagCh = ch - (next ? next[0].length : 0);
      if (!next || !(end = toTagEnd())) return;
      if (end == "selfClose") continue;
      if (next[1]) { // closing tag
        for (var i = stack.length - 1; i >= 0; --i) if (stack[i] == next[2]) {
          stack.length = i;
          break;
        }
        if (!stack.length) return {
          from: CodeMirror.Pos(start.line, startCh),
          to: CodeMirror.Pos(tagLine, tagCh)
        };
      } else { // opening tag
        stack.push(next[2]);
      }
    }
  };
})();
</script>
<?php
if (file_exists(dirname(__FILE__)."/plugins/emmet/emmet.min.js")) {
	echo '<script src="plugins/emmet/emmet.min.js"></script>';
};?>
<link rel="stylesheet" href="<?php
if ($ICEcoder["theme"]=="default") {echo 'lib/editor.css';} else {echo $ICEcoder["codeMirrorDir"].'/theme/'.$ICEcoder["theme"].'.css';};
$activeLineBG = array_search($ICEcoder["theme"],array("eclipse","elegant","neat")) !== false ? "#ccc" : "#000";
?>">
<style type="text/css">
.CodeMirror {position: absolute; top: 0px; width: 100%; font-size: 13px; z-index: 1}
.CodeMirror-scroll {} /* was: height: auto; overflow: visible */
/* Make sure this next one remains the 3rd item, updated with JS */
.cm-s-activeLine {background: <?php echo $activeLineBG;?> !important}
.cm-matchhighlight, .CodeMirror-focused .cm-matchhighlight {color: #fff !important; background: #06c !important}
/* Make sure this next one remains the 5th item, updated with JS */
.cm-tab:after {position: relative; display: inline-block; width: 0; left: -1.4em; overflow: visible; color: #aaa; content: "<?php if($ICEcoder["visibleTabs"]) {echo '\\21e5';};?>";}
.lint-error {font-family: arial; font-size: 80%; background: #ccc; color: #b00; padding: 3px 5px}
.lint-error-icon {background: #b00; color: #fff; font-weight: bold; border-radius: 50%; padding: 0 3px; margin-right: 5px}
</style>
</head>

<body style="color: #fff; margin: 0" onKeyDown="return top.ICEcoder.interceptKeys('content', event);" onKeyUp="top.ICEcoder.resetKeys(event);">

<?php if ($ICEcoder['demoMode']) {?>
<div style="position: absolute; display: inline-block; width: 99px; height: 50px; top: 0; right: 30px; background: url('images/big-arrow.gif') 0 -10px no-repeat; text-align: center; font-family: arial; font-size: 10px; padding-top: 60px"><b>Click logo<br>for help &amp;<br>usage info</b></div>
<?php ;}; ?>

<div style="display: none; margin: 32px 43px 0 43px; padding: 10px; width: 500px; font-family: arial; font-size: 10px; color: #ddd; background: #333" id="dataMessage"></div>

<div style="margin: 20px 43px 32px 43px; font-family: arial; font-size: 10px; color: #ddd">
	<div style="float: left; margin-right: 50px">
		<h2 style="color: rgba(0,198,255,0.7)">server</h2>
		<span style="color:#888">Server name, OS & IP:</span><br>
		<?php echo $_SERVER['SERVER_NAME']." &nbsp;&nbsp ".$_SERVER['SERVER_SOFTWARE']." &nbsp;&nbsp ".$_SERVER['SERVER_ADDR'];?><br><br>
		<span style="color:#888">Root:</span><br>
		<?php echo $docRoot;?><br><br>
		<span style="color:#888">ICEcoder root:</span><br>
		<?php echo $docRoot.$iceRoot;?><br><br>
		<span style="color:#888">PHP version:</span><br>
		<?php echo phpversion();?><br><br>
		<span style="color:#888">Date & time:</span><br>
		<span id="serverDT"></span><br><br><br>
	</div>

	<div style="float: left">
		<h2 style="color: rgba(0,198,255,0.7)">files</h2>
		<span style="color:#888">Last 10 files opened:</span><br>
		<?php
			$last10FilesArray = explode(",",$ICEcoder["last10Files"]);
			for ($i=0;$i<count($last10FilesArray);$i++) {
				if ($ICEcoder["last10Files"]=="") {
					echo '[none]<br><br>';
				} else {
					echo '<a style="cursor:pointer" onClick="top.ICEcoder.openFile(\''.str_replace("|","/",$last10FilesArray[$i]).'\')">';
					echo str_replace($docRoot,"",str_replace("|","/",$last10FilesArray[$i]));
					echo '</a><br>'.PHP_EOL;
					if ($i==count($last10FilesArray)-1) {echo '<br>'.PHP_EOL;};
				}
			}
		;?>
	</div>

	<div style="clear: both">
		<h2 style="color: rgba(0,198,255,0.7)">your device</h2>
		<span style="color:#888">Browser:</span><br>
		<?php echo $_SERVER['HTTP_USER_AGENT'];?><br><br>
		<span style="color:#888">Your IP:</span><br>
		<?php echo $_SERVER['REMOTE_ADDR'];?>
	</div>
	<script>
	var nDT=<?php echo time()*1000;?>;
	setInterval(function(){
		var s=(new Date(nDT+=1e3)+'').split(' '),
		d=s[2]*1,
		t=s[4].split(':'),
		p=t[0]>11?'pm':'am',
		e=d%20==1|d>30?'st':d%20==2?'nd':d%20==3?'rd':'th';
		t[0]=--t[0]%12+1;
		if (document.getElementById('serverDT')) {
			document.getElementById('serverDT').innerHTML=[s[0],d+e,s[1],s[3],t.join(':')+p].join(' ');
		}
	},1000);
	</script>
	<?php if(is_dir('test') && !$ICEcoder['demoMode']) {?>
	<div style="clear: both">
		<br><br>
		<h2 style="color: rgba(0,198,255,0.7)">test suite</h2>
		<span style="color:#888">Run unit tests:</span><br>
		<a href="javascript:top.ICEcoder.filesFrame.contentWindow.frames['testControl'].location.href = 'test'" style="color: #fff">Run unit tests</a><div id="unitTestResults"></div>
	</div>
	<?php ;};?>
</div>

<script>
CodeMirror.keyMap.ICEcoder = {
	// "Tab": "defaultTab", **Now used by Emmet**
	"Shift-Tab": "indentLess",
	"Ctrl-Space": "autocomplete",
	fallthrough: ["default"]
};
CodeMirror.commands.autocomplete = function(cm) {
	if (top.ICEcoder.caretLocType=="JavaScript") {
		CodeMirror.simpleHint(cm, CodeMirror.javascriptHint);
	}
}

function createNewCMInstance(num) {
	var fileName = top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1];
	top.ICEcoder['cM'+num+'waiting'] = "";
	top.ICEcoder['cM'+num+'widgets'] = [];

	window['cM'+num] = CodeMirror(document.body, {
		mode: "application/x-httpd-php",
		lineNumbers: true,
		lineWrapping: top.ICEcoder.lineWrapping,
		indentUnit: top.ICEcoder.tabWidth,
		tabSize: top.ICEcoder.tabWidth,
		indentWithTabs: true,
		electricChars: false,
		autoCloseTags: true,
		highlightSelectionMatches: true,
		keyMap: "ICEcoder",
		onKeyEvent: function(thisCM, e) {
			top.ICEcoder.redoChangedContent(e);
			top.ICEcoder.findReplace('find',true,false);
			top.ICEcoder.getCaretPosition();
			top.ICEcoder.updateCharDisplay();
			tok = thisCM.getTokenAt(thisCM.getCursor());
		}
	});

	window['cM'+num].on("cursorActivity", function(thisCM) {
			top.ICEcoder.getCaretPosition();
			top.ICEcoder.updateCharDisplay();
			window['cM'+num].removeLineClass(top.ICEcoder['cMActiveLine'+num], "background");
			if(window['cM'+num].getCursor('start').line == window['cM'+num].getCursor().line) {
				top.ICEcoder['cMActiveLine'+num] = window['cM'+num].addLineClass(window['cM'+num].getCursor().line, "background","cm-s-activeLine");
			}
			top.ICEcoder.cssColorPreview();
		}
	);

	window['cM'+num].on("change", function(thisCM, changeObj) {
			// If we're not loading the file, it's a change, so update tab
			if (!top.ICEcoder.loadingFile) {
				top.ICEcoder.changedContent[top.ICEcoder.selectedTab-1] = 1;
				top.ICEcoder.redoTabHighlight(top.ICEcoder.selectedTab);
			}
			top.ICEcoder.getCaretPosition();
			top.ICEcoder.dontUpdateNest = false;
			top.ICEcoder.updateCharDisplay();
			top.ICEcoder.updateNestingIndicator();
			if (top.ICEcoder.findMode) {
				top.ICEcoder.results.splice(top.ICEcoder.findResult,1);
				top.document.getElementById('results').innerHTML = top.ICEcoder.results.length + " results";
				top.ICEcoder.findMode = false;
			}
			if (top.ICEcoder.codeAssist) {
				clearTimeout(window['cM'+num+'waiting']);
				window['cM'+num+'waiting'] = setTimeout(top.ICEcoder.updateHints, 100);
			}
		}
	);

	window['cM'+num].on("scroll", function(thisCM) {
			top.ICEcoder.mouseDown=false;
		}
	);

	window['cM'+num].on("gutterClick", function(thisCM, line, gutter, clickEvent) {
			["JavaScript","CoffeeScript","PHP","Ruby"].indexOf(top.ICEcoder.caretLocType) > -1
			? codeFoldBrace(window['cM'+num], line) : codeFoldTag(window['cM'+num], line);
			window['cM'+num].setGutterMarker(line, "CodeMirror-linenumbers", document.createTextNode("+ "+(line+1)));
			setTimeout(function() {
				window['cM'+num].setGutterMarker(line, "CodeMirror-linenumbers", null);
			},1000);
		}
	);

	// Now create the active line for this CodeMirror object
	top.ICEcoder['cMActiveLine'+num] = window['cM'+num].addLineClass(0, "background", "cm-s-activeLine");
};

	// var top.ICEcoder.foldStyle = '<span style="position: absolute; display: inline-block; width: 13px; height: 13px; left: 0; background-color: #b00; color: #fff; text-align: center; cursor: pointer"><span style="position: relative; left: -1px">+</span></span> %N%';
	var codeFoldTag = CodeMirror.newFoldFunction(CodeMirror.tagRangeFinder);
	var codeFoldBrace = CodeMirror.newFoldFunction(CodeMirror.braceRangeFinder);
</script>

</body>

</html>