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
<link rel="stylesheet" href="lib/editor.css">
<style type="text/css">
.CodeMirror {position: absolute; width: 0px; background-color: #ffffff}
.CodeMirror-scroll {width: 100px; height: 100px;}
.cm-s-visible {width: 100px}
.cm-s-hidden {width: 0px}
</style>
</head>

<body onKeyDown="return top.ICEcoder.interceptKeys('content', event);" onKeyUp="top.ICEcoder.resetKeys(event);">

<script>
<?php
for ($i=1;$i<=10;$i++) {
?>
var cM<?php echo $i;?> = CodeMirror(document.body, {
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
	},
	onChange: function() {
		top.ICEcoder.getCaretPosition();
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
		tok = cM<?php echo $i;?>.getTokenAt(cM<?php echo $i;?>.getCursor());
		if (tok.string!=">") {lastString=tok.string};
		if (e.type=="keyup"&&e.keyCode=="16"&&lastKeyCode=="190") {
			canDoEndTag=true;
			for (i=0;i<top.ICEcoder.tagNestExceptions.length;i++) {
				if(top.ICEcoder.tagString!="script" && top.ICEcoder.tagString==top.ICEcoder.tagNestExceptions[i]) {
				canDoEndTag=false;
				}
			}
			contentType = top.ICEcoder.caretLocType;
			if (canDoEndTag && (contentType!="JavaScript"||(contentType=="JavaScript"&&top.ICEcoder.tagString=="script"))) {
				numTabs = top.ICEcoder.htmlTagArray.length;
				if (top.ICEcoder.htmlTagArray[0]=="html") {numTabs--};
				tabs = "";
				for (i=0;i<numTabs-1;i++) {
					tabs += "\t";
				}
				endTag = "</" + top.ICEcoder.htmlTagArray[top.ICEcoder.htmlTagArray.length-1] + ">";
				if (top.ICEcoder.tagString=="script") {endTag="</"+"script>"};
				if(top.ICEcoder.tagString=="title") {
					cM<?php echo $i;?>.replaceSelection(endTag);
					cM<?php echo $i;?>.setCursor(cM<?php echo $i;?>.getCursor().line,top.ICEcoder.tagString.length+2);
				} else if(top.ICEcoder.tagString=="html"||top.ICEcoder.tagString=="head") {
					cM<?php echo $i;?>.replaceSelection("\n\n"+endTag);
					cM<?php echo $i;?>.setCursor(cM<?php echo $i;?>.getCursor().line-1,numTabs);
				} else {
					cM<?php echo $i;?>.replaceSelection("\n"+tabs+"\t\n"+tabs+endTag);
					cM<?php echo $i;?>.setCursor(cM<?php echo $i;?>.getCursor().line-1,numTabs);
				}
			}
		};
		lastKeyCode = e.keyCode;
	},
	extraKeys: {"Enter": false}
});
cM<?php echo $i;?>.getWrapperElement().getElementsByClassName("CodeMirror-lines")[0].addEventListener("click", function(){
	if (top.ICEcoder.results) {
		top.document.getElementById('results').innerHTML = top.ICEcoder.results.length + " results";
	}
	top.ICEcoder.findMode = false;
}, false);
<?php
;};
?>
</script>

</body>

</html>