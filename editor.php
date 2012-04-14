<!DOCTYPE html>

<html>
<head>
<title>CodeMirror 2: ICE Coders Editor of Choice</title>
<?php include("lib/settings.php");?>
<link rel="stylesheet" href="<?php echo $codeMirrorDir; ?>/lib/codemirror.css">
<script src="<?php echo $codeMirrorDir; ?>/lib/codemirror.js"></script>
<script src="<?php echo $codeMirrorDir; ?>/mode/xml/xml.js"></script>
<script src="<?php echo $codeMirrorDir; ?>/mode/javascript/javascript.js"></script>
<script src="<?php echo $codeMirrorDir; ?>/mode/css/css.js"></script>
<script src="<?php echo $codeMirrorDir; ?>/mode/clike/clike.js"></script>
<script src="<?php echo $codeMirrorDir; ?>/mode/php/php.js"></script>
<script src="<?php echo $codeMirrorDir; ?>/mode/ruby/ruby.js"></script>
<script src="<?php echo $codeMirrorDir; ?>/lib/util/searchcursor.js"></script>
<script src="<?php echo $codeMirrorDir; ?>/lib/util/match-highlighter.js"></script>
<script src="<?php echo $codeMirrorDir; ?>/lib/util/foldcode.js"></script>
<link rel="stylesheet" href="lib/editor.css">
<style type="text/css">
.CodeMirror {position: absolute; width: 0px; background-color: #ffffff}
.CodeMirror-scroll {width: 100px; height: 100px;}
.cm-s-visible {display: block; top: 0px}
.cm-s-hidden {display: none; top: 4000px}
.cm-s-activeLine {background: #002 !important;}
<?php if ($visibleTabs) {?>
.cm-tab:after {position: relative; display: inline-block; width: 0px; left: -1.4em; overflow: visible; color: #aaa; content: "\21e5";}
<?;};?>
</style>
</head>

<body onKeyDown="return top.ICEcoder.interceptKeys('content', event);" onKeyUp="top.ICEcoder.resetKeys(event);">

<script>
function createNewCMInstance(num) {
	var fileName = top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1];
	var codeFold 		 = CodeMirror.newFoldFunction(CodeMirror.tagRangeFinder,'<span style=\"display: inline-block; width: 13px; height: 13px; background-color: #bb0000; color: #ffffff; text-align: center; cursor: pointer\"><span style="position: relative; top: -1px">+</span></span> %N%');
	var codeFold_JS_PHP_Ruby = CodeMirror.newFoldFunction(CodeMirror.braceRangeFinder,'<span style=\"display: inline-block; width: 13px; height: 13px; background-color: #bb0000; color: #ffffff; text-align: center; cursor: pointer\"><span style="position: relative; top: -1px">+</span></span> %N%');

	window['cM'+num] = CodeMirror(document.body, {
        mode: "application/x-httpd-php",
	theme: "icecoder",
        lineNumbers: true,
	lineWrapping: true,
	indentUnit: 4,
	tabSize: 4,
        indentWithTabs: true,
	electricChars: false,
	onCursorActivity: function() {
		top.ICEcoder.getCaretPosition();
		top.ICEcoder.updateCharDisplay();
		window['cM'+top.ICEcoder.cMInstances[top.ICEcoder.selectedTab-1]].setLineClass(top.ICEcoder['cMActiveLine'+top.ICEcoder.selectedTab], null);
		if(!window['cM'+top.ICEcoder.cMInstances[top.ICEcoder.selectedTab-1]].somethingSelected()) {
			top.ICEcoder['cMActiveLine'+top.ICEcoder.selectedTab] = window['cM'+top.ICEcoder.cMInstances[top.ICEcoder.selectedTab-1]].setLineClass(window['cM'+top.ICEcoder.cMInstances[top.ICEcoder.selectedTab-1]].getCursor().line, "cm-s-activeLine");
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
		top.ICEcoder.redoChangedContent(event);
		top.ICEcoder.findReplace('find',true);
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
			if (!top.ICEcoder.codeAssist||fileName.indexOf(".js")>0||fileName.indexOf(".css")>0) {
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
	onGutterClick: !fileName || (fileName && fileName.indexOf(".js") == -1 && fileName.indexOf(".php") && fileName.indexOf(".rb") == -1) ? codeFold : codeFold_JS_PHP_Ruby,
	extraKeys: {"Tab": "indentMore", "Shift-Tab": "indentLess"}
	});

	// Now create the active line for this CodeMirror object
	top.ICEcoder['cMActiveLine'+top.ICEcoder.selectedTab] = window['cM'+top.ICEcoder.cMInstances[top.ICEcoder.selectedTab-1]].setLineClass(0, "cm-s-activeLine");
};

</script>

</body>

</html>