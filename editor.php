<?php include("lib/config.php");?>
<!DOCTYPE html>

<html style="margin: 0">
<head>
<title>CodeMirror 2: ICE Coders Editor of Choice</title>
<?php include("lib/settings.php");?>
<link rel="stylesheet" href="<?php echo $codeMirrorDir; ?>/lib/codemirror.css">
<!--codemirror-compressed.js includes codemirror.js plus the mode files for clike, coffeescript, css, javascript, less, php, ruby & xml //-->
<script src="<?php echo $codeMirrorDir; ?>/lib/codemirror-compressed.js"></script>
<script src="<?php echo $codeMirrorDir; ?>/lib/util/searchcursor.js"></script>
<script src="<?php echo $codeMirrorDir; ?>/lib/util/match-highlighter.js"></script>
<script src="<?php echo $codeMirrorDir; ?>/lib/util/foldcode.js"></script>
<?php
if ($theme=="default") {
	echo '<link rel="stylesheet" href="lib/editor.css">';
} else {
	echo '<link rel="stylesheet" href="'.$codeMirrorDir.'/theme/'.$theme.'.css">';
}
?>
<style type="text/css">
.CodeMirror {position: absolute; width: 0; background-color: #fff; top: 0px; z-index: 1}
.CodeMirror-scroll {width: 100px; height: 100px;}
.cm-s-visible {display: block; top: 0}
.cm-s-hidden {display: none; top: 4000px}
.cm-s-activeLine {background: #000 !important;}
/* Make sure this next one remains the 5th item, updated with JS */
.cm-tab:after {position: relative; display: inline-block; width: 0; left: -1.4em; overflow: visible; color: #aaa; content: "<?php if($visibleTabs) {echo '\\21e5';};?>";}
span.CodeMirror-matchhighlight {background: #555}
.CodeMirror-focused span.CodeMirror-matchhighlight {color: #000; background: #555; !important}
</style>
</head>

<body onLoad="top.ICEcoder.updateFileFolderCount()" style="color: #fff; margin: 0" onKeyDown="return top.ICEcoder.interceptKeys('content', event);" onKeyUp="top.ICEcoder.resetKeys(event);">

<div style="margin: 32px 43px; font-family: arial; font-size: 10px; color: #dddddd">
<?php if($_SESSION['userLevel'] == 10) {
	echo '<div style="float: left; margin-right: 50px">'.PHP_EOL;
	echo '<h2 style="color: rgba(0,198,255,0.7)">server</h2>'.PHP_EOL;
	echo '<span style="color:#888">Server name, OS & IP:</span><br>'.PHP_EOL;
	echo $_SERVER['SERVER_NAME'].' &nbsp;&nbsp; '.$_SERVER['SERVER_SOFTWARE'].' &nbsp;&nbsp; '.$_SERVER['SERVER_ADDR'].'<br><br>'.PHP_EOL;
	echo '<span style="color:#888">Root:</span><br>'.PHP_EOL;
	echo $_SERVER['DOCUMENT_ROOT'].'<br><br>'.PHP_EOL;
	echo '<span style="color:#888">PHP version:</span><br>'.PHP_EOL;
	echo phpversion().'<br><br>'.PHP_EOL;
	echo '<span style="color:#888">Date & time:</span><br>'.PHP_EOL;
	echo '<span id="serverDT"></span><br><br><br>'.PHP_EOL;
	echo '</div>'.PHP_EOL;

	echo '<div style="float: left">'.PHP_EOL;
	echo '<h2 style="color: rgba(0,198,255,0.7)">files</h2>'.PHP_EOL;
	echo '<span style="color:#888">Last 10 files opened:</span><br>'.PHP_EOL;
	$last10FilesArray = explode(",",$last10Files);
	for ($i=0;$i<count($last10FilesArray);$i++) {
		if ($last10Files=="") {
			echo '[none]<br><br>';
		} else {
			echo '<a style="cursor:pointer" onClick="top.ICEcoder.openFile(top.fullPath+\''.str_replace("|","/",$last10FilesArray[$i]).'\')">';
			echo str_replace("|","/",$last10FilesArray[$i]);
			echo '</a><br>'.PHP_EOL;
			if ($i==count($last10FilesArray)-1) {echo '<br>'.PHP_EOL;};
		}
	}
	echo '<span style="color:#888">File & folder count:</span><br>'.PHP_EOL;
	echo '<div id="fileFolderCounts"></div><br><br><br>'.PHP_EOL;
	echo '</div>'.PHP_EOL;

	echo '<div style="clear: both">'.PHP_EOL;
	echo '<h2 style="color: rgba(0,198,255,0.7)">your device</h2>'.PHP_EOL;
	echo '<span style="color:#888">Browser:</span><br>'.PHP_EOL;
	echo $_SERVER['HTTP_USER_AGENT'].'<br><br>'.PHP_EOL;
	echo '<span style="color:#888">Your IP:</span><br>'.PHP_EOL;
	echo $_SERVER['REMOTE_ADDR'].PHP_EOL;
	echo '</div>'.PHP_EOL;
}; ?>
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
	var codeFold		 = CodeMirror.newFoldFunction(CodeMirror.tagRangeFinder,'<span style=\"display: inline-block; width: 13px; height: 13px; background-color: #b00; color: #fff; text-align: center; cursor: pointer\"><span style="position: relative; top: -1px">+</span></span> %N%');
	var codeFold_JS_Coffee_PHP_Ruby = CodeMirror.newFoldFunction(CodeMirror.braceRangeFinder,'<span style=\"display: inline-block; width: 13px; height: 13px; background-color: #b00; color: #fff; text-align: center; cursor: pointer\"><span style="position: relative; top: -1px">+</span></span> %N%');

	window['cM'+num] = CodeMirror(document.body, {
        mode: "application/x-httpd-php",
        lineNumbers: true,
	lineWrapping: true,
	indentUnit: top.tabWidth,
	tabSize: top.tabWidth,
        indentWithTabs: true,
	electricChars: false,
	onCursorActivity: function() {
		top.ICEcoder.getCaretPosition();
		top.ICEcoder.updateCharDisplay();
		window['cM'+num].setLineClass(top.ICEcoder['cMActiveLine'+num], null);
		if(!window['cM'+num].somethingSelected()) {
			top.ICEcoder['cMActiveLine'+num] = window['cM'+num].setLineClass(window['cM'+num].getCursor().line, "cm-s-activeLine");
		}
		window['cM'+top.ICEcoder.cMInstances[top.ICEcoder.selectedTab-1]].matchHighlight("CodeMirror-matchhighlight");
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
	onKeyEvent: function(instance, e) {
		top.ICEcoder.redoChangedContent(e);
		top.ICEcoder.findReplace('find',true,false);
		top.ICEcoder.getCaretPosition();
		top.ICEcoder.updateCharDisplay();
		tok = window['cM'+top.ICEcoder.cMInstances[top.ICEcoder.selectedTab-1]].getTokenAt(window['cM'+top.ICEcoder.cMInstances[top.ICEcoder.selectedTab-1]].getCursor());
		if (tok.string!=">") {lastString=tok.string};
		if (e.type=="keyup"&&e.keyCode=="16"&&lastKeyCode=="190") {
			canDoEndTag=true;
			for (i=0;i<top.ICEcoder.tagNestExceptions.length;i++) {
				if(top.ICEcoder.tagString!="script" && top.ICEcoder.tagString==top.ICEcoder.tagNestExceptions[i]) {
				canDoEndTag=false;
				}
			}
			if(top.ICEcoder.tagString.slice(0,1)=="/"||top.ICEcoder.tagString.slice(0,1)=="?") {
				canDoEndTag=false;
			}
			if (!top.ICEcoder.codeAssist||fileName && (fileName.indexOf(".js")>0||fileName.indexOf(".css")>0||fileName.indexOf(".less")>0)) {
				canDoEndTag=false;
			}
			contentType = top.ICEcoder.caretLocType;
			if (canDoEndTag && (contentType!="JavaScript"||(contentType=="JavaScript"&&top.ICEcoder.tagString=="script"))) {
				numTabs = top.ICEcoder.htmlTagArray.length;
				if (top.ICEcoder.htmlTagArray[0]=="html") {numTabs--};
				tabs = "";
				for (i=0;i<numTabs-1;i++) {
					tabs += "\t";
				}
				//endTag = "</" + top.ICEcoder.htmlTagArray[top.ICEcoder.htmlTagArray.length-1] + ">";
				endTag = "</" + top.ICEcoder.tagString + ">";
				if (top.ICEcoder.tagString=="script") {endTag="</"+"script>"};
				if(top.ICEcoder.tagString=="title"||top.ICEcoder.tagString=="a"||top.ICEcoder.tagString=="li"||top.ICEcoder.tagString=="span"||(top.ICEcoder.tagString.slice(0,1)=="h"&&parseInt(top.ICEcoder.tagString.slice(1,2),10)>=1&&parseInt(top.ICEcoder.tagString.slice(1,2),10)<=7)) {
					window['cM'+top.ICEcoder.cMInstances[top.ICEcoder.selectedTab-1]].replaceSelection(endTag);
					window['cM'+top.ICEcoder.cMInstances[top.ICEcoder.selectedTab-1]].setCursor(window['cM'+top.ICEcoder.cMInstances[top.ICEcoder.selectedTab-1]].getCursor().line,window['cM'+top.ICEcoder.cMInstances[top.ICEcoder.selectedTab-1]].getCursor().ch-top.ICEcoder.tagString.length-3);
				} else if(top.ICEcoder.tagString=="html"||top.ICEcoder.tagString=="head") {
					window['cM'+top.ICEcoder.cMInstances[top.ICEcoder.selectedTab-1]].replaceSelection("\n\n"+endTag);
					window['cM'+top.ICEcoder.cMInstances[top.ICEcoder.selectedTab-1]].setCursor(window['cM'+top.ICEcoder.cMInstances[top.ICEcoder.selectedTab-1]].getCursor().line-1,numTabs);
				} else {
					window['cM'+top.ICEcoder.cMInstances[top.ICEcoder.selectedTab-1]].replaceSelection("\n"+tabs+"\t\n"+tabs+endTag);
					window['cM'+top.ICEcoder.cMInstances[top.ICEcoder.selectedTab-1]].setCursor(window['cM'+top.ICEcoder.cMInstances[top.ICEcoder.selectedTab-1]].getCursor().line-1,numTabs);
				}
			}
		};
		lastKeyCode = e.keyCode;
	},
	onGutterClick: !fileName || (fileName && fileName.indexOf(".js") == -1 && fileName.indexOf(".coffee") == -1 && fileName.indexOf(".php") && fileName.indexOf(".rb") == -1) ? codeFold : codeFold_JS_Coffee_PHP_Ruby,
	extraKeys: {
		"Tab": function(cm) {CodeMirror.commands[top.tabsIndent ? "defaultTab" : "insertTab"](cm);},
		"Shift-Tab": "indentLess"
	}
	});

	// Now create the active line for this CodeMirror object
	top.ICEcoder['cMActiveLine'+num] = window['cM'+num].setLineClass(0, "cm-s-activeLine");
};

</script>

</body>

</html>