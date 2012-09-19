<?php include("lib/settings.php");?>
<!DOCTYPE html>

<html style="margin: 0" onMouseDown="top.ICEcoder.mouseDown=true" onMouseUp="top.ICEcoder.mouseDown=false" onMouseMove="if(top.ICEcoder) {top.ICEcoder.getMouseXY(event,'editor');top.ICEcoder.canResizeFilesW()}">
<head>
<title>ICEcoder v <?php echo $ICEcoder["versionNo"];?> editor</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="<?php echo $ICEcoder["codeMirrorDir"]; ?>/lib/codemirror.css">
<!--
codemirror-compressed.js
incls:	codemirror.js
modes:	clike, coffeescript, css, javascript, less, php, ruby & xml
utils:	foldcode, searchcursor, match-highlighter
//-->
<script src="<?php echo $ICEcoder["codeMirrorDir"]; ?>/lib/codemirror-compressed.js"></script>
<link rel="stylesheet" href="<?php
if ($ICEcoder["theme"]=="default") {echo 'lib/editor.css';} else {echo $ICEcoder["codeMirrorDir"].'/theme/'.$ICEcoder["theme"].'.css';};
$activeLineBG = $ICEcoder["theme"]=="eclipse" || $ICEcoder["theme"]=="elegant" || $ICEcoder["theme"]=="neat" ? "#ccc" : "#000";
?>">
<style type="text/css">
.CodeMirror {position: absolute; width: 0; background-color: #fff; color: #000; top: 0px; width: 100%; z-index: 1}
.CodeMirror-scroll {height: auto; overflow: visible}
/* Make sure this next one remains the 3rd item, updated with JS */
.cm-s-activeLine {background: <?php echo $activeLineBG;?> !important}
span.CodeMirror-matchhighlight {background: #555}
.CodeMirror-focused span.CodeMirror-matchhighlight {color: #000; background: #555; !important}
/* Make sure this next one remains the 6th item, updated with JS */
.cm-tab:after {position: relative; display: inline-block; width: 0; left: -1.4em; overflow: visible; color: #aaa; content: "<?php if($ICEcoder["visibleTabs"]) {echo '\\21e5';};?>";}
</style>
</head>

<body onLoad="top.ICEcoder.updateFileFolderCount()" style="color: #fff; margin: 0" onKeyDown="return top.ICEcoder.interceptKeys('content', event);" onKeyUp="top.ICEcoder.resetKeys(event);">

<div style="margin: 32px 43px; font-family: arial; font-size: 10px; color: #ddd">
	<div style="float: left; margin-right: 50px">
		<h2 style="color: rgba(0,198,255,0.7)">server</h2>
		<span style="color:#888">Server name, OS & IP:</span><br>
		<?php echo $_SERVER['SERVER_NAME'];?> &nbsp;&nbsp; <?php echo $_SERVER['SERVER_SOFTWARE'];?> &nbsp;&nbsp; <?php echo $_SERVER['SERVER_ADDR'];?><br><br>
		<span style="color:#888">Root:</span><br>
		<?php echo $docRoot;?><br><br>
		<span style="color:#888">ICEcoder root:</span><br>
		<?php echo $docRoot.$ICEcoder['root'];?><br><br>
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
		<span style="color:#888">File & folder count:</span><br>
		<div id="fileFolderCounts"></div><br><br><br>
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
</div>

<script>
function createNewCMInstance(num) {
	var fileName = top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1];
	top.foldStyle = '<span style="position: absolute; display: inline-block; width: 13px; height: 13px; left: 0; background-color: #b00; color: #fff; text-align: center; cursor: pointer"><span style="position: relative; left: -1px">+</span></span> %N%';
	var codeFoldTag = CodeMirror.newFoldFunction(CodeMirror.tagRangeFinder,top.foldStyle);
	var codeFoldBrace = CodeMirror.newFoldFunction(CodeMirror.braceRangeFinder,top.foldStyle);

	window['cM'+num] = CodeMirror(document.body, {
		mode: "application/x-httpd-php",
		lineNumbers: true,
		lineWrapping: true,
		indentUnit: top.tabWidth,
		tabSize: top.tabWidth,
		indentWithTabs: true,
		electricChars: false,
		onCursorActivity: function(thisCM) {
			top.ICEcoder.getCaretPosition();
			top.ICEcoder.updateCharDisplay();
			window['cM'+num].setLineClass(top.ICEcoder['cMActiveLine'+num], null);
			if(!window['cM'+num].somethingSelected()) {
				top.ICEcoder['cMActiveLine'+num] = window['cM'+num].setLineClass(window['cM'+num].getCursor().line, "cm-s-activeLine");
			}
			thisCM.matchHighlight("CodeMirror-matchhighlight");
			top.ICEcoder.cssColorPreview();
		},
		onChange: function() {
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
		},
		onKeyEvent: function(thisCM, e) {
			top.ICEcoder.redoChangedContent(e);
			top.ICEcoder.findReplace('find',true,false);
			top.ICEcoder.getCaretPosition();
			top.ICEcoder.updateCharDisplay();
			tok = thisCM.getTokenAt(thisCM.getCursor());
			if (tok.string!=">") {lastString=tok.string};
			if (e.type=="keyup"&&e.keyCode=="16"&&lastKeyCode=="190") {
				canDoEndTag=true;
				for (i=0;i<top.ICEcoder.tagNestExceptions.length;i++) {
					if(top.ICEcoder.tagString!="script" && top.ICEcoder.tagString==top.ICEcoder.tagNestExceptions[i]) {
					canDoEndTag=false;
					}
				}
				if	(
					top.ICEcoder.tagString.slice(0,1)=="/"||
					top.ICEcoder.tagString.slice(0,1)=="?"||
					!top.ICEcoder.codeAssist||
					fileName && (fileName.indexOf(".js")>0||fileName.indexOf(".css")>0||fileName.indexOf(".less")>0)
					) {canDoEndTag=false}

				contentType = top.ICEcoder.caretLocType;
				if (canDoEndTag && (contentType!="JavaScript"||(contentType=="JavaScript"&&top.ICEcoder.tagString=="script"))) {
					numTabs = top.ICEcoder.htmlTagArray.length;
					if (top.ICEcoder.htmlTagArray[0]=="html") {numTabs--};
					tabs = "";
					for (i=0;i<numTabs-1;i++) {
						tabs += "\t";
					}
					endTag = "</" + top.ICEcoder.tagString + ">";
					if (top.ICEcoder.tagString=="script") {endTag="</"+"script>"};
					if(top.ICEcoder.tagString=="title"||top.ICEcoder.tagString=="a"||top.ICEcoder.tagString=="li"||top.ICEcoder.tagString=="span"||(top.ICEcoder.tagString.slice(0,1)=="h"&&parseInt(top.ICEcoder.tagString.slice(1,2),10)>=1&&parseInt(top.ICEcoder.tagString.slice(1,2),10)<=7)) {
						thisCM.replaceSelection(endTag);
						thisCM.setCursor(thisCM.getCursor().line,thisCM.getCursor().ch-top.ICEcoder.tagString.length-3);
					} else if(top.ICEcoder.tagString=="html"||top.ICEcoder.tagString=="head") {
						thisCM.replaceSelection("\n\n"+endTag);
						thisCM.setCursor(thisCM.getCursor().line-1,numTabs);
					} else {
						thisCM.replaceSelection("\n"+tabs+"\t\n"+tabs+endTag);
						thisCM.setCursor(thisCM.getCursor().line-1,numTabs);
					}
				}
			};
			lastKeyCode = e.keyCode;
		},
		onGutterClick: !fileName || (fileName && fileName.indexOf(".js") == -1 && fileName.indexOf(".coffee") == -1 && fileName.indexOf(".php") && fileName.indexOf(".rb") == -1) ? codeFoldTag : codeFoldBrace,
		extraKeys: {
			"Tab": function(cm) {CodeMirror.commands[top.tabsIndent ? "defaultTab" : "insertTab"](cm);},
			"Shift-Tab": "indentLess",
			"Ctrl-Up": function() {},
			"Ctrl-Down": function() {}
		}
	});

	// Now create the active line for this CodeMirror object
	top.ICEcoder['cMActiveLine'+num] = window['cM'+num].setLineClass(0, "cm-s-activeLine");
};

</script>

</body>

</html>