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
		fileName.indexOf('.js')>0	? cM.setOption("mode","text/javascript")
		: fileName.indexOf('.coffee')>0	? cM.setOption("mode","text/x-coffeescript")
		: fileName.indexOf('.rb')>0	? cM.setOption("mode","text/x-ruby")
		: fileName.indexOf('.py')>0	? cM.setOption("mode","text/x-python")
		: fileName.indexOf('.css')>0	? cM.setOption("mode","text/css")
		: fileName.indexOf('.less')>0	? cM.setOption("mode","text/x-less")
		: fileName.indexOf('.md')>0	? cM.setOption("mode","text/x-markdown")
		: fileName.indexOf('.xml')>0	? cM.setOption("mode","application/xml")
		: fileName.indexOf('.sql')>0	? cM.setOption("mode","text/x-mysql") // also text/x-sql, text/x-mariadb, text/x-cassandra or text/x-plsql
		: fileName.indexOf('.erl')>0	? cM.setOption("mode","text/x-erlang")
		: fileName.indexOf('.yaml')>0	? cM.setOption("mode","text/x-yaml")
		: fileName.indexOf('.java')>0	? cM.setOption("mode","text/x-java")
		: fileName.indexOf('.jl')>0	? cM.setOption("mode","text/x-julia")
		: fileName.indexOf('.c')>0	? cM.setOption("mode","text/x-csrc")
		: fileName.indexOf('.cpp')>0	? cM.setOption("mode","text/x-c++src")
		: fileName.indexOf('.cs')>0	? cM.setOption("mode","text/x-csharp")
		: fileName.indexOf('.go')>0	? cM.setOption("mode","text/x-go")
		: fileName.indexOf('.lua')>0	? cM.setOption("mode","text/x-lua")
		: fileName.indexOf('.pl')>0	? cM.setOption("mode","text/x-perl")
		: fileName.indexOf('.rs')>0	? cM.setOption("mode","text/x-rustsrc")
		: fileName.indexOf('.scss')>0	? cM.setOption("mode","text/x-sass")
		: cM.setOption("mode","application/x-httpd-php");
	}
}

// Comment/uncomment line or selected range on keypress
top.ICEcoder.lineCommentToggleSub = function(cM, cursorPos, linePos, lineContent, lCLen, adjustCursor) {
	var startLine, endLine, commentChar;

	if (["JavaScript","CoffeeScript","PHP","Python","Ruby","CSS","SQL","Erlang","Julia","Java","YAML","C","C++","C#","Go","Lua","Perl","Rust","Sass"].indexOf(top.ICEcoder.caretLocType)>-1) {
		if (cM.somethingSelected()) {
			if (["Ruby","Python","Erlang","Julia","YAML","Perl"].indexOf(top.ICEcoder.caretLocType)>-1) {
				commentChar = top.ICEcoder.caretLocType == "Erlang" ? "%" : "#";
				startLine = cM.getCursor(true).line;
				endLine = cM.getCursor().line;
				for (var i=startLine; i<=endLine; i++) {
					cM.replaceRange(cM.getLine(i).slice(0,1)!=commentChar
					? commentChar + cM.getLine(i)
					: cM.getLine(i).slice(1,cM.getLine(i).length), {line:i, ch:0}, {line:i, ch:1000000});
				}
			} else if (["Lua"].indexOf(top.ICEcoder.caretLocType)>-1) {
				cM.replaceSelection(cM.getSelection().slice(0,4)!="--[["
				? "--[[" + cM.getSelection() + "]]"
				: cM.getSelection().slice(4,cM.getSelection().length-2),"around");
			} else {
				cM.replaceSelection(cM.getSelection().slice(0,2)!="/*"
				? "/*" + cM.getSelection() + "*/"
				: cM.getSelection().slice(2,cM.getSelection().length-2),"around");
			}
		} else {
			if (["CoffeeScript","CSS","SQL"].indexOf(top.ICEcoder.caretLocType)>-1) {
				cM.replaceRange(lineContent.slice(0,2)!="/*"
				? "/*" + lineContent + "*/"
				: lineContent.slice(2,lCLen).slice(0,lCLen-4), {line: linePos, ch: 0}, {line: linePos, ch: 1000000});
				if (lineContent.slice(0,2)=="/*") {adjustCursor = -adjustCursor};
			} else if (["Ruby","Python","Erlang","Julia","YAML","Perl"].indexOf(top.ICEcoder.caretLocType)>-1) {
				commentChar = top.ICEcoder.caretLocType == "Erlang" ? "%" : "#";
				cM.replaceRange(lineContent.slice(0,1)!=commentChar
				? commentChar + lineContent
				: lineContent.slice(1,lCLen), {line: linePos, ch: 0}, {line: linePos, ch: 1000000});
				adjustCursor = 1;
				if (lineContent.slice(0,1)==commentChar) {adjustCursor = -adjustCursor};
			} else if (["Lua"].indexOf(top.ICEcoder.caretLocType)>-1) {
				cM.replaceRange(lineContent.slice(0,2)!="--"
				? "--" + lineContent
				: lineContent.slice(2,lCLen), {line: linePos, ch: 0}, {line: linePos, ch: 1000000});
				if (lineContent.slice(0,2)=="//") {adjustCursor = -adjustCursor};
			} else {
				cM.replaceRange(lineContent.slice(0,2)!="//"
				? "//" + lineContent
				: lineContent.slice(2,lCLen), {line: linePos, ch: 0}, {line: linePos, ch: 1000000});
				if (lineContent.slice(0,2)=="//") {adjustCursor = -adjustCursor};
			}
		}
	} else {
		if (cM.somethingSelected()) {
			cM.replaceSelection(cM.getSelection().slice(0,4)!="<\!--"
			? "<\!--" + cM.getSelection() + "//-->"
			: cM.getSelection().slice(4,cM.getSelection().length-5),"around");
		} else {
			cM.replaceRange(lineContent.slice(0,4)!="<\!--"
			? "<\!--" + lineContent + "//-->"
			: lineContent.slice(4,lCLen).slice(0,lCLen-9), {line: linePos, ch: 0}, {line: linePos, ch: 1000000});
			adjustCursor = lineContent.slice(0,4)=="<\!--" ? -4 : 4;
		}
	}

	if (!cM.somethingSelected()) {cM.setCursor(linePos, cursorPos+adjustCursor)};
}

// Work out the nesting depth location on demand and update our display if required
top.ICEcoder.getNestLocationSub = function(nestCheck, fileName) {
	var events;

	if (["js","coffee","css","less","sql","erl","yaml","java","jl","c","cpp","cs","go","lua","pl","rs","scss"].indexOf(fileName.split(".")[1])<0 &&
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
	var cM, testToken, nestOK, fileName;

	cM = top.ICEcoder.getcMInstance();
	nestOK = true;
	fileName = top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1];
	if (cM && fileName && ["js","coffee","css","less","sql","erl","yaml","java","jl","c","cpp","cs","go","lua","pl","rs","scss"].indexOf(fileName.split(".")[1])==-1) {
		testToken = cM.getTokenAt({line:cM.lineCount(),ch:cM.lineInfo(cM.lineCount()-1).text.length});
		nestOK = testToken.type && testToken.type.indexOf("error") == -1 ? true : false;
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
		else if (fileName.indexOf(".sql")>0)	{caretLocType="SQL"}
		else if (fileName.indexOf(".yaml")>0)	{caretLocType="YAML"}
		else if (fileName.indexOf(".java")>0)	{caretLocType="Java"}
		else if (fileName.indexOf(".erl")>0)	{caretLocType="Erlang"}
		else if (fileName.indexOf(".jl")>0)	{caretLocType="Julia"}
		else if (fileName.indexOf(".c")>0 && fileName.indexOf(".cpp")<0 && fileName.indexOf(".cs")<0)	{caretLocType="C"}
		else if (fileName.indexOf(".cpp")>0)	{caretLocType="C++"}
		else if (fileName.indexOf(".cs")>0)	{caretLocType="C#"}
		else if (fileName.indexOf(".go")>0)	{caretLocType="Go"}
		else if (fileName.indexOf(".lua")>0)	{caretLocType="Lua"}
		else if (fileName.indexOf(".pl")>0)	{caretLocType="Perl"}
		else if (fileName.indexOf(".rs")>0)	{caretLocType="Rust"}
		else if (fileName.indexOf(".scss")>0)	{caretLocType="Sass"};
	}

	top.ICEcoder.caretLocType = caretLocType;
}
</script>