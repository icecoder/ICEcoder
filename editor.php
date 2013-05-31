<?php include("lib/settings.php");?>
<!DOCTYPE html>

<html style="margin: 0" onMouseDown="top.ICEcoder.mouseDown=true" onMouseUp="top.ICEcoder.mouseDown=false; if (!top.ICEcoder.overCloseLink) {top.ICEcoder.tabDragEnd()}" onMouseMove="if(top.ICEcoder) {top.ICEcoder.getMouseXY(event,'editor');top.ICEcoder.canResizeFilesW()}">
<head>
<title>ICEcoder v <?php echo $ICEcoder["versionNo"];?> editor</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" href="<?php echo $ICEcoder["codeMirrorDir"]; ?>/lib/codemirror.css">
<link rel="stylesheet" href="<?php echo $ICEcoder["codeMirrorDir"]; ?>/addon/hint/show-hint.css">
<!--
codemirror-compressed.js
incls:	codemirror
modes:	clike, coffeescript, css, htmlmixed, javascript, less, markdown, php, python, ruby & xml
utils:	closetag, xml-fold, brace-fold, show-hint, javascript-hint, html-hint, searchcursor, match-highlighter
//-->
<script src="<?php echo $ICEcoder["codeMirrorDir"]; ?>/lib/codemirror-compressed.js"></script>
<script src="lib/mmd.js"></script>
<script src="lib/foldcode.js"></script>
<?php
if (file_exists(dirname(__FILE__)."/plugins/emmet/emmet.min.js")) {
	echo '<script src="plugins/emmet/emmet.min.js"></script>';
};?>
<link rel="stylesheet" href="<?php
if ($ICEcoder["theme"]=="default") {echo 'lib/editor.css';} else {echo $ICEcoder["codeMirrorDir"].'/theme/'.$ICEcoder["theme"].'.css';};
$activeLineBG = array_search($ICEcoder["theme"],array("eclipse","elegant","neat")) !== false ? "#ccc" : "#000";
?>">

<style type="text/css">
/* Make sure this next one remains the 1st item, updated with JS */
.CodeMirror {position: absolute; top: 0px; width: 100%; font-size: <?php echo $ICEcoder["fontSize"];?>; z-index: 1}
.CodeMirror-scroll {} /* was: height: auto; overflow: visible */
/* Make sure this next one remains the 3rd item, updated with JS */
.cm-s-activeLine {background: <?php echo $activeLineBG;?> !important}
.cm-matchhighlight, .CodeMirror-focused .cm-matchhighlight {color: #fff !important; background: #06c !important}
/* Make sure this next one remains the 5th item, updated with JS */
.cm-tab:after {position: relative; display: inline-block; width: 0; left: -1.4em; overflow: visible; color: #aaa; content: "<?php if($ICEcoder["visibleTabs"]) {echo '\\21e5';};?>";}
.lint-error {font-family: arial; font-size: 80%; background: #ccc; color: #b00; padding: 3px 5px}
.lint-error-icon {background: #b00; color: #fff; font-weight: bold; border-radius: 50%; padding: 0 3px; margin-right: 5px}
.CodeMirror-foldmarker {font-family: arial; line-height: .3; color: #b00; cursor: pointer;
	text-shadow: #fff 1px 1px 2px, #fff -1px -1px 2px, #fff 1px -1px 2px, #fff -1px 1px 2px;
}
.folds {display: inline-block; width: 13px}
.fold {position: absolute; display: inline-block; width: 13px; height: 13px; font-size: 14px; text-align: center; cursor: pointer}
.foldOn {background: #800; color: #ddd}
.foldOff {background: #383838; color: #666}
</style>

<link rel="stylesheet" href="lib/file-types.css">
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
		<ul class="fileManager" style="margin-left: 0; line-height: 20px">
		<?php
			$last10FilesArray = explode(",",$ICEcoder["last10Files"]);
			for ($i=0;$i<count($last10FilesArray);$i++) {
				if ($ICEcoder["last10Files"]=="") {
					echo '<div style="display: inline-block; margin-left: -39px; margin-top: -18px">[none]</div><br><br>';
				} else {
					$fileFolderName = str_replace("\\","/",$last10FilesArray[$i]);
					// Get extension (prefix 'ext-' to prevent invalid classes from extensions that begin with numbers)
					$ext = "ext-".pathinfo($docRoot.$iceRoot.$fileFolderName, PATHINFO_EXTENSION);
					$class = 'pft-file '.strtolower($ext);
					echo '<li class="'.$class.'" style="margin-left: -16px">';
					echo '<a style="cursor:pointer" onClick="top.ICEcoder.openFile(\''.str_replace("|","/",$last10FilesArray[$i]).'\')">';
					echo str_replace($docRoot,"",str_replace("|","/",$last10FilesArray[$i]));
					echo '</a></li>'.PHP_EOL;
					if ($i==count($last10FilesArray)-1) {echo '<br>'.PHP_EOL;};
				}
			}
		;?>
		</ul>
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
	<div style="float: left; margin-right: 50px">
		<br><br>
		<h2 style="color: rgba(0,198,255,0.7)">test suite</h2>
		<span style="color:#888">Run unit tests:</span><br>
		<a href="javascript:top.ICEcoder.filesFrame.contentWindow.frames['testControl'].location.href = 'test'" style="color: #fff">Run unit tests</a><div id="unitTestResults"></div>
	</div>
	<?php ;};?>
	<?php if($ICEcoder['devMode']) {?>
	<div style="float: left">
		<br><br>
		<h2 style="color: rgba(0,198,255,0.7)">dev mode on</h2>
		<span style="color:#888">Status:</span><br>
		Using ice-coder.js
	</div>
	<?php ;};?>
	<div style="clear: both"></div>
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
		CodeMirror.showHint(cm, CodeMirror.javascriptHint);
	} else {
		CodeMirror.showHint(cm, CodeMirror.htmlHint);
	}
}

function createNewCMInstance(num) {
	var fileName = top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1];
	top.ICEcoder['cM'+num+'waiting'] = "";
	top.ICEcoder['cM'+num+'widgets'] = [];

	window['cM'+num] = CodeMirror(document.body, {
		mode: "application/x-httpd-php",
		lineNumbers: true,
		gutters: ["folds","CodeMirror-linenumbers"],
		lineWrapping: top.ICEcoder.lineWrapping,
		indentWithTabs: top.ICEcoder.indentWithTabs,
		indentUnit: top.ICEcoder.indentSize,
		tabSize: top.ICEcoder.indentSize,
		electricChars: false,
		autoCloseTags: true,
		highlightSelectionMatches: true,
		keyMap: "ICEcoder",
		onKeyEvent: function(thisCM, e) {
			top.ICEcoder.redoChangedContent(e);
			top.ICEcoder.findReplace(top.document.getElementById('find').value,true,false);
			top.ICEcoder.getCaretPosition();
			top.ICEcoder.updateCharDisplay();
			top.ICEcoder.updateByteDisplay();
			tok = thisCM.getTokenAt(thisCM.getCursor());
		}
	});

	window['cM'+num].on("cursorActivity", function(thisCM) {
			top.ICEcoder.getCaretPosition();
			top.ICEcoder.updateCharDisplay();
			top.ICEcoder.updateByteDisplay();
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
			top.ICEcoder.updateByteDisplay();
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
			var filepath = top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1];
			var filename = filepath.substr(filepath.lastIndexOf("/")+1);
			var fileExt = filename.substr(filename.lastIndexOf(".")+1);
			for (var i=changeObj.from.line; i<changeObj.from.line+changeObj.text.length; i++) {
				top.ICEcoder.content.contentWindow.CodeMirror.newFoldFunction(top.ICEcoder.content.contentWindow.CodeMirror[["coffee","css","js","less","php","py","rb","ruby"].indexOf(fileExt) > -1 ? "braceRangeFinder" : "tagRangeFinder"],null,"+","-",true)(thisCM, i);
			}
			// Update HTML edited files live
			if (top.ICEcoder.previewWindow.location) {
				if (top.ICEcoder.previewWindow.location.pathname==filepath) {
					if (["htm","html","txt"].indexOf(fileExt) > -1) {
						top.ICEcoder.previewWindow.document.documentElement.innerHTML = window['cM'+num].getValue();
					} else if (["md"].indexOf(fileExt) > -1) {
						top.ICEcoder.previewWindow.document.documentElement.innerHTML = mmd(window['cM'+num].getValue());
					}
				} else if (["css"].indexOf(fileExt) > -1) {
					if (top.ICEcoder.previewWindow.document.documentElement.innerHTML.indexOf(filename) > -1) {
						var css = window['cM'+num].getValue();
						var style = document.createElement('style');
						style.type = 'text/css';
						style.id = "ICEcoder"+filepath.replace(/\//g,"_");
						if (style.styleSheet){
							style.styleSheet.cssText = css;
						} else {
							style.appendChild(document.createTextNode(css));
						}
						if (top.ICEcoder.previewWindow.document.getElementById(style.id)) {
							top.ICEcoder.previewWindow.document.documentElement.removeChild(top.ICEcoder.previewWindow.document.getElementById(style.id));
						}
						top.ICEcoder.previewWindow.document.documentElement.appendChild(style);
					}
				}
			}
		}
	);

	window['cM'+num].on("scroll", function(thisCM) {
			top.ICEcoder.mouseDown=false;
		}
	);

	window['cM'+num].on("gutterClick", function(thisCM, line, gutter, clickEvent) {
			var filepath = top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1];
			var filename = filepath.substr(filepath.lastIndexOf("/")+1);
			var fileExt = filename.substr(filename.lastIndexOf(".")+1);
			["coffee","css","js","less","php","py","rb","ruby"].indexOf(fileExt) > -1
			? codeFoldBrace(window['cM'+num], line) : codeFoldTag(window['cM'+num], line);
		}
	);

	// Now create the active line for this CodeMirror object
	top.ICEcoder['cMActiveLine'+num] = window['cM'+num].addLineClass(0, "background", "cm-s-activeLine");
};

var codeFoldTag = CodeMirror.newFoldFunction(CodeMirror.tagRangeFinder,null,"+","-",false);
var codeFoldBrace = CodeMirror.newFoldFunction(CodeMirror.braceRangeFinder,null,"+","-",false);
</script>

</body>

</html>