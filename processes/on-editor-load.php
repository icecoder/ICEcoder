<?php
if (!isset($_SESSION['loggedIn'])) {
	die('Sorry, not logged in.');
}
?>
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
	var cM, cMdiff, fileName, fileExt;

	cM = top.ICEcoder.getcMInstance();
	cMdiff = top.ICEcoder.getcMdiffInstance();
	fileName = top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1];

	if (cM && mode) {
		cM.setOption("mode",mode);
		cMdiff.setOption("mode",mode);
	} else if (cM && fileName) {
		fileExt = fileName.split(".");
		fileExt = fileExt[fileExt.length-1];
		var mode =
			  fileExt == "js" || fileExt == "json"	? "text/javascript"
			: fileExt == "coffee"			? "text/x-coffeescript"
			: fileExt == "rb"			? "text/x-ruby"
			: fileExt == "py"			? "text/x-python"
			: fileExt == "css"			? "text/css"
			: fileExt == "less"			? "text/x-less"
			: fileExt == "md"			? "text/x-markdown"
			: fileExt == "xml"			? "application/xml"
			: fileExt == "sql"			? "text/x-mysql" // also text/x-sql, text/x-mariadb, text/x-cassandra or text/x-plsql
			: fileExt == "erl"			? "text/x-erlang"
			: fileExt == "yaml"			? "text/x-yaml"
			: fileExt == "java"			? "text/x-java"
			: fileExt == "jl"			? "text/x-julia"
			: fileExt == "c"			? "text/x-csrc"
			: fileExt == "cpp"			? "text/x-c++src"
			: fileExt == "ino"			? "text/x-c++src"
			: fileExt == "cs"			? "text/x-csharp"
			: fileExt == "go"			? "text/x-go"
			: fileExt == "lua"			? "text/x-lua"
			: fileExt == "pl"			? "text/x-perl"
			: fileExt == "rs"			? "text/x-rustsrc"
			: fileExt == "scss"			? "text/x-sass"
			: "application/x-httpd-php";

		cM.setOption("mode",mode);
		cM.setOption("lint",(fileExt == "js" || fileExt == "json") && top.ICEcoder.codeAssist ? true : false);
		cMdiff.setOption("mode",mode);
		cMdiff.setOption("lint",(fileExt == "js" || fileExt == "json") && top.ICEcoder.codeAssist ? true : false);
	}
}

// Comment/uncomment line or selected range on keypress
top.ICEcoder.lineCommentToggleSub = function(cM, cursorPos, linePos, lineContent, lCLen) {
	var comments, startLine, endLine, commentCH, commentBS, commentBE;

	// Language specific commenting
	if (["JavaScript","CoffeeScript","PHP","Python","Ruby","CSS","SQL","Erlang","Julia","Java","YAML","C","C++","C#","Go","Lua","Perl","Rust","Sass"].indexOf(top.ICEcoder.caretLocType)>-1) {

		comments = {
			"JavaScript"	: ["// ", "/* ", " */"],
			"CoffeeScript"	: ["// ", "/* ", " */"],
			"PHP"		: ["// ", "/* ", " */"],
			"Python"	: ["# ", "/* ", " */"],
			"Ruby"		: ["# ", "/* ", " */"],
			"CSS"		: ["// ", "/* ", " */"],
			"SQL"		: ["// ", "/* ", " */"],
			"Erlang"	: ["% ", "/* ", " */"],
			"Julia"		: ["# ", "/* ", " */"],
			"Java"		: ["// ", "/* ", " */"],
			"YAML"		: ["# ", "/* ", " */"],
			"C"		: ["// ", "/* ", " */"],
			"C++"		: ["// ", "/* ", " */"],
			"C#"		: ["// ", "/* ", " */"],
			"Go"		: ["// ", "/* ", " */"],
			"Lua"		: ["-- ", "--[[ ", " ]]"],
			"Perl"		: ["# ", "/* ", " */"],
			"Rust"		: ["// ", "/* ", " */"],
			"Sass"		: ["// ", "/* ", " */"]
		}

		// Identify the single line, block start and block end comment chars
		commentCH = comments[top.ICEcoder.caretLocType][0];
		commentBS = comments[top.ICEcoder.caretLocType][1];
		commentBE = comments[top.ICEcoder.caretLocType][2];

		// Block commenting
		if (cM.somethingSelected()) {
			// Language has no block commenting, so repeating singles are needed
			if (["Ruby","Python","Erlang","Julia","YAML","Perl"].indexOf(top.ICEcoder.caretLocType)>-1) {
				startLine = cM.getCursor(true).line;
				endLine = cM.getCursor().line;
				for (var i=startLine; i<=endLine; i++) {
					cM.replaceRange(cM.getLine(i).slice(0,commentCH.length)!=commentCH
					? commentCH + cM.getLine(i)
					: cM.getLine(i).slice(commentCH.length,cM.getLine(i).length), {line:i, ch:0}, {line:i, ch:1000000});
				}
			// Language has block commenting
			} else {
				cM.replaceSelection(cM.getSelection().slice(0,commentBS.length)!=commentBS
				? commentBS + cM.getSelection() + commentBE
				: cM.getSelection().slice(commentBS.length,cM.getSelection().length-commentBE.length),"around");
			}
		// Single line commenting
		} else {
			if (["CoffeeScript","CSS","SQL"].indexOf(top.ICEcoder.caretLocType)>-1) {
				cM.replaceRange(lineContent.slice(0,commentBS.length)!=commentBS
				? commentBS + lineContent + commentBE
				: lineContent.slice(commentBS.length,lCLen-commentBE.length), {line: linePos, ch: 0}, {line: linePos, ch: 1000000});
				adjustCursor = commentBS.length;
				if (lineContent.slice(0,commentBS.length)==commentBS) {adjustCursor = -adjustCursor};
			} else {
				cM.replaceRange(lineContent.slice(0,commentCH.length)!=commentCH
				? commentCH + lineContent
				: lineContent.slice(commentCH.length,lCLen), {line: linePos, ch: 0}, {line: linePos, ch: 1000000});
				adjustCursor = commentCH.length;
				if (lineContent.slice(0,commentCH.length)==commentCH) {adjustCursor = -adjustCursor};
			}
		}
	// HTML style commenting
	} else {
		if (cM.somethingSelected()) {
			cM.replaceSelection(cM.getSelection().slice(0,4)!="<\!--"
			? "<\!--" + cM.getSelection() + "//-->"
			: cM.getSelection().slice(4,cM.getSelection().length-5),"around");
		} else {
			cM.replaceRange(lineContent.slice(0,4)!="<\!--"
			? "<\!--" + lineContent + "//-->"
			: lineContent.slice(4,lCLen-5), {line: linePos, ch: 0}, {line: linePos, ch: 1000000});
			adjustCursor = lineContent.slice(0,4)=="<\!--" ? -4 : 4;
		}
	}

	if (!cM.somethingSelected()) {cM.setCursor(linePos, cursorPos+adjustCursor)};
}

// Indicate if the nesting structure of the code is OK
top.ICEcoder.updateNestingIndicator = function() {
	var cM, cMdiff, thisCM, testToken, nestOK, fileName, fileExt;

	cM = top.ICEcoder.getcMInstance();
	cMdiff = top.ICEcoder.getcMdiffInstance();
	thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
	nestOK = true;
	fileName = top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1];
	if (fileName) {
		fileExt = fileName.split(".");
		fileExt = fileExt[fileExt.length-1];
	}
	if (thisCM && fileName && ["js","coffee","css","less","sql","erl","yaml","java","jl","c","cpp","ino","cs","go","lua","pl","rs","scss"].indexOf(fileExt)==-1) {
		testToken = thisCM.getTokenAt({line:thisCM.lineCount(),ch:thisCM.lineInfo(thisCM.lineCount()-1).text.length});
		nestOK = testToken.type && testToken.type.indexOf("error") == -1 ? true : false;
	}
	top.ICEcoder.nestValid.style.background = nestOK ? "#0b0" : "#f00";
	top.ICEcoder.nestValid.title = nestOK ? "Nesting OK" : "Nesting Broken";
}

// Determine which area of the document we're in
top.ICEcoder.caretLocationType = function() {
	var cM, cMdiff, thisCM, caretLocType, caretChunk, fileName, fileExt;

	cM = top.ICEcoder.getcMInstance();
	cMdiff = top.ICEcoder.getcMdiffInstance();
	thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
	caretLocType = "Unknown";
	caretChunk = thisCM.getValue().substr(0,top.ICEcoder.caretPos+1);

	if(caretChunk.lastIndexOf("<script")>caretChunk.lastIndexOf("/script>")&&caretLocType=="Unknown") {caretLocType = "JavaScript";}
	else if (caretChunk.lastIndexOf("<?")>caretChunk.lastIndexOf("?>")&&caretLocType=="Unknown") {caretLocType = "PHP";}
	else if (caretChunk.lastIndexOf("<%")>caretChunk.lastIndexOf("%>")&&caretLocType=="Unknown") {caretLocType = "Ruby";}
	else if (caretChunk.lastIndexOf("<style")>caretChunk.lastIndexOf("/style>")&&caretLocType=="Unknown") {caretLocType = "CSS";}
	else if (caretChunk.lastIndexOf("<")>caretChunk.lastIndexOf(">")&&caretLocType=="Unknown") {caretLocType = "HTML";}
	else if (caretLocType=="Unknown") {caretLocType = "Content";};

	fileName = top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1];
	if (caretLocType == "Content" && fileName) {
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
			: fileExt == "ino"	? "C++"
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