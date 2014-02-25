<!--
Purpose:	This file is run when ICEcoder editor has loaded
Langs:		Anything - PHP, JS etc
//-->
<script>
CodeMirror.commands.autocomplete = function(cm) {
	var langType = top.ICEcoder.caretLocType;
	if (["JavaScript","CoffeeScript","SQL","CSS","HTML","XML","Content"].indexOf(langType)>-1) {
		if (langType=="XML"||langType=="Content") {langType="HTML"};
		CodeMirror.showHint(cm,CodeMirror.hint[langType.toLowerCase()]);
	}
}

// Switch the CodeMirror mode on demand
top.ICEcoder.switchMode = function(mode) {
	var cM, fileName;

	cM = top.ICEcoder.getcMInstance();
	fileName = top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1];
	if (cM && mode) {
		cM.setOption("mode",mode);
	} else if (cM && fileName) {
		fileName.indexOf('.js')>0	? cM.setOption("mode","javascript")
		: fileName.indexOf('.coffee')>0	? cM.setOption("mode","coffeescript")
		: fileName.indexOf('.rb')>0	? cM.setOption("mode","ruby")
		: fileName.indexOf('.py')>0	? cM.setOption("mode","python")
		: fileName.indexOf('.css')>0	? cM.setOption("mode","css")
		: fileName.indexOf('.less')>0	? cM.setOption("mode","less")
		: fileName.indexOf('.md')>0	? cM.setOption("mode","markdown")
		: fileName.indexOf('.xml')>0	? cM.setOption("mode","xml")
		: fileName.indexOf('.sql')>0	? cM.setOption("mode","text/x-mysql") // also text/x-sql, text/x-mariadb, text/x-cassandra or text/x-plsql
		: cM.setOption("mode","application/x-httpd-php");
	}
}

top.ICEcoder.lineCommentToggleSub = function(cM, cursorPos, linePos, lineContent, lCLen, adjustCursor) {
	var startLine, endLine;

	if (["JavaScript","CoffeeScript","PHP","Python","Ruby","CSS","SQL"].indexOf(top.ICEcoder.caretLocType)>-1) {
		if (cM.somethingSelected()) {
			if (top.ICEcoder.caretLocType=="Ruby"||top.ICEcoder.caretLocType=="Python") {
				startLine = cM.getCursor(true).line;
				endLine = cM.getCursor().line;
				for (var i=startLine; i<=endLine; i++) {
					cM.setLine(i, cM.getLine(i).slice(0,1)!="#"
					? "#" + cM.getLine(i)
					: cM.getLine(i).slice(1,cM.getLine(i).length));
				}
			} else {
				cM.replaceSelection(cM.getSelection().slice(0,2)!="/*"
				? "/*" + cM.getSelection() + "*/"
				: cM.getSelection().slice(2,cM.getSelection().length-2));
			}
		} else {
			if (["CoffeeScript","CSS","SQL"].indexOf(top.ICEcoder.caretLocType)>-1) {
				cM.setLine(linePos, lineContent.slice(0,2)!="/*"
				? "/*" + lineContent + "*/"
				: lineContent.slice(2,lCLen).slice(0,lCLen-4));
				if (lineContent.slice(0,2)=="/*") {adjustCursor = -adjustCursor};
			} else if (top.ICEcoder.caretLocType=="Ruby") {
				cM.setLine(linePos, lineContent.slice(0,1)!="#"
				? "#" + lineContent
				: lineContent.slice(1,lCLen));
				if (lineContent.slice(0,1)=="#") {adjustCursor = -adjustCursor};
			} else {
				cM.setLine(linePos, lineContent.slice(0,2)!="//"
				? "//" + lineContent
				: lineContent.slice(2,lCLen));
				if (lineContent.slice(0,2)=="//") {adjustCursor = -adjustCursor};
			}
		}
	} else {
		if (cM.somethingSelected()) {
			cM.replaceSelection(cM.getSelection().slice(0,4)!="<\!--"
			? "<\!--" + cM.getSelection() + "//-->"
			: cM.getSelection().slice(4,cM.getSelection().length-5));
		} else {
			cM.setLine(linePos, lineContent.slice(0,4)!="<\!--"
			? "<\!--" + lineContent + "//-->"
			: lineContent.slice(4,lCLen).slice(0,lCLen-9));
			adjustCursor = lineContent.slice(0,4)=="<\!--" ? -4 : 4;
		}
	}
}

top.ICEcoder.getNestLocationSub = function(nestCheck, fileName) {
	var events;

	if (["js","coffee","css","less","sql"].indexOf(fileName.split(".")[1])<0 &&
		(nestCheck.indexOf("include(")==-1)&&(nestCheck.indexOf("include_once(")==-1)) {

		// Then for all the array items, output as the nest display
		for (var i=0;i<top.ICEcoder.htmlTagArray.length;i++) {
			events = 'onMouseover="top.ICEcoder.highlightBlock('+i+')" onMouseout="top.ICEcoder.highlightBlock('+i+',\'hide\')" onClick="top.ICEcoder.setPosition('+i+',top.ICEcoder.startPosLine,\''+top.ICEcoder.htmlTagArray[i]+'\')"';
			if (i==0) {top.ICEcoder.nestDisplay.innerHTML += '<div '+events+' style="display: inline-block; width: 7px; margin-top: -5px; height: 30px; background-image: url(images/nest-tag-bg.gif)"></div>'};
			top.ICEcoder.nestDisplay.innerHTML += '<a '+events+' style="display: inline-block; cursor: pointer; background: #333; padding: 7px 2px 7px 7px; margin-top: -5px; height: 30px">'+top.ICEcoder.htmlTagArray[i]+'</a>';
			top.ICEcoder.nestDisplay.innerHTML += i<top.ICEcoder.htmlTagArray.length-1
			? '<div '+events+' style="display: inline-block; width: 8px; margin-top: -5px; height: 30px; background-image: url(images/nest-tag-bg.gif); background-position: -7px 0; cursor: pointer"></div>'
			: '<div '+events+' style="display: inline-block; width: 7px; margin-top: -5px; height: 30px; background-image: url(images/nest-tag-bg.gif); background-position: -15px 0; cursor: pointer"></div>';
		}
		if (top.ICEcoder.tagString != "script") {
			top.ICEcoder.nestDisplay.innerHTML += '<a style="display: inline-block; cursor: default; padding: 7px 2px 7px 7px; margin-top: -5px; height: 30px; color: #666">content</a>';
		}
	}
}

// Indicate if the nesting structure of the code is OK
top.ICEcoder.updateNestingIndicator = function() {
	var cM, nestOK, fileName;

	cM = top.ICEcoder.getcMInstance();
	nestOK = true;
	fileName = top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1];
	if (cM && fileName && ["js","coffee","css","less","sql"].indexOf(fileName.split(".")[1])==-1) {
		nestOK = cM.getTokenAt({line:cM.lineCount(),ch:cM.lineInfo(cM.lineCount()-1).text.length}).className != "error" ? true : false;
	}
	top.ICEcoder.nestValid.style.background = nestOK ? "#0b0" : "#f00";
	top.ICEcoder.nestValid.title = nestOK ? "Nesting OK" : "Nesting Broken";
}

// Determine which area of the document we're in
top.ICEcoder.caretLocationType = function() {
	var cM, caretLocType, caretChunk, fileName;

	cM = top.ICEcoder.getcMInstance();
	caretLocType = "Unknown";
	caretChunk = cM.getValue().substr(0,top.ICEcoder.caretPos+1);

	if(caretChunk.lastIndexOf("<script")>caretChunk.lastIndexOf("/script>")&&caretLocType=="Unknown") {caretLocType = "JavaScript";}
	else if (caretChunk.lastIndexOf("<?")>caretChunk.lastIndexOf("?>")&&caretLocType=="Unknown") {caretLocType = "PHP";}
	else if (caretChunk.lastIndexOf("<%")>caretChunk.lastIndexOf("%>")&&caretLocType=="Unknown") {caretLocType = "Ruby";}
	else if (caretChunk.lastIndexOf("<")>caretChunk.lastIndexOf(">")&&caretLocType=="Unknown") {caretLocType = "HTML";}
	else if (caretLocType=="Unknown") {caretLocType = "Content";};

	fileName = top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1];
	if (fileName) {
		if (fileName.indexOf(".js")>0) {caretLocType="JavaScript"} 
		else if (fileName.indexOf(".coffee")>0)	{caretLocType="CoffeeScript"} 
		else if (fileName.indexOf(".py")>0)	{caretLocType="Python"} 
		else if (fileName.indexOf(".rb")>0)	{caretLocType="Ruby"} 
		else if (fileName.indexOf(".css")>0)	{caretLocType="CSS"} 
		else if (fileName.indexOf(".less")>0)	{caretLocType="LESS"} 
		else if (fileName.indexOf(".md")>0)	{caretLocType="Markdown"}
		else if (fileName.indexOf(".xml")>0)	{caretLocType="XML"}
		else if (fileName.indexOf(".sql")>0)	{caretLocType="SQL"};
	}

	top.ICEcoder.caretLocType = caretLocType;
}
</script>