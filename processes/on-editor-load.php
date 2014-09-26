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
	var cM, fileName, fileExt;

	cM = top.ICEcoder.getcMInstance();
	fileName = top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1];
	if (cM && mode) {
		cM.setOption("mode",mode);
	} else if (cM && fileName) {
		fileExt = fileName.split(".");
		fileExt = fileExt[fileExt.length-1];
		cM.setOption("mode",
			  fileExt == "js"	? "text/javascript"
			: fileExt == "coffee"	? "text/x-coffeescript"
			: fileExt == "rb"	? "text/x-ruby"
			: fileExt == "py"	? "text/x-python"
			: fileExt == "css"	? "text/css"
			: fileExt == "less"	? "text/x-less"
			: fileExt == "md"	? "text/x-markdown"
			: fileExt == "xml"	? "application/xml"
			: fileExt == "sql"	? "text/x-mysql" // also text/x-sql, text/x-mariadb, text/x-cassandra or text/x-plsql
			: fileExt == "erl"	? "text/x-erlang"
			: fileExt == "yaml"	? "text/x-yaml"
			: fileExt == "java"	? "text/x-java"
			: fileExt == "jl"	? "text/x-julia"
			: fileExt == "c"	? "text/x-csrc"
			: fileExt == "cpp"	? "text/x-c++src"
			: fileExt == "cs"	? "text/x-csharp"
			: fileExt == "go"	? "text/x-go"
			: fileExt == "lua"	? "text/x-lua"
			: fileExt == "pl"	? "text/x-perl"
			: fileExt == "rs"	? "text/x-rustsrc"
			: fileExt == "scss"	? "text/x-sass"
			: "application/x-httpd-php"
		);
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
	var events, fileExt;

	fileExt = fileName.split(".");
	fileExt = fileExt[fileExt.length-1];

	if (["js","coffee","css","less","sql","erl","yaml","java","jl","c","cpp","cs","go","lua","pl","rs","scss"].indexOf(fileExt)<0 &&
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
	var cM, testToken, nestOK, fileName, fileExt;

	cM = top.ICEcoder.getcMInstance();
	nestOK = true;
	fileName = top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1];
	if (fileName) {
		fileExt = fileName.split(".");
		fileExt = fileExt[fileExt.length-1];
	}
	if (cM && fileName && ["js","coffee","css","less","sql","erl","yaml","java","jl","c","cpp","cs","go","lua","pl","rs","scss"].indexOf(fileExt)==-1) {
		testToken = cM.getTokenAt({line:cM.lineCount(),ch:cM.lineInfo(cM.lineCount()-1).text.length});
		nestOK = testToken.type && testToken.type.indexOf("error") == -1 ? true : false;
	}
	top.ICEcoder.nestValid.style.background = nestOK ? "#0b0" : "#f00";
	top.ICEcoder.nestValid.title = nestOK ? "Nesting OK" : "Nesting Broken";
}

// Determine which area of the document we're in
top.ICEcoder.caretLocationType = function() {
	var cM, caretLocType, caretChunk, fileName, fileExt;

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
		fileExt = fileName.split(".");
		fileExt = fileExt[fileExt.length-1];
		caretLocType =
			  fileExt == "js"	? "JavaScript"
			: fileExt == "coffee"	? "CoffeeScript"
			: fileExt == "py"	? "Python"
			: fileExt == "rb"	? "Ruby"
			: fileExt == "css"	? "CSS"
			: fileExt == "less"	? "LESS"
			: fileExt == "md"	? "Markdown"
			: fileExt == "xml"	? "XML"
			: fileExt == "sql"	? "SQL"
			: fileExt == "yaml"	? "YAML"
			: fileExt == "java"	? "Java"
			: fileExt == "erl"	? "Erlang"
			: fileExt == "jl"	? "Julia"
			: fileExt == "c"	? "C"
			: fileExt == "cpp"	? "C++"
			: fileExt == "cs"	? "C#"
			: fileExt == "go"	? "Go"
			: fileExt == "lua"	? "Lua"
			: fileExt == "pl"	? "Perl"
			: fileExt == "rs"	? "Rust"
			: fileExt == "scss"	? "Sass"
			: "Content";
	}

	top.ICEcoder.caretLocType = caretLocType;
}
</script>