<?php
if (!isset($_SESSION['loggedIn'])) {
	die('Sorry, not logged in.');
}
?>
<script>
ICEcoder = parent.ICEcoder;
CodeMirror.commands.autocomplete = function(cm) {
	var langType = ICEcoder.caretLocType;
	if (["JavaScript","CoffeeScript","TypeScript","SQL","CSS","HTML","XML","Content"].indexOf(langType)>-1) {
		if (langType=="XML"||langType=="Content") {langType="HTML"};
		CodeMirror.showHint(cm,CodeMirror.hint[langType.toLowerCase()]);
	}
}

// Switch the CodeMirror mode on demand
ICEcoder.switchMode = function(mode) {
	var cM, cMdiff, fileName, fileExt;

	cM = ICEcoder.getcMInstance();
	cMdiff = ICEcoder.getcMdiffInstance();
	fileName = ICEcoder.openFiles[ICEcoder.selectedTab-1];

	if (cM && mode) {
		if (mode != cM.getOption("mode")) {
			cM.setOption("mode",mode);
			cMdiff.setOption("mode",mode);
		}
	} else if (cM && fileName) {
		<?php include(dirname(__FILE__)."/../assets/js/language-modes-partial.js");?>

		if (mode != cM.getOption("mode")) {
			cM.setOption("mode",mode);
			cM.setOption("lint",(fileExt == "js" || fileExt == "json") && ICEcoder.codeAssist ? true : false);
			cMdiff.setOption("mode",mode);
			cMdiff.setOption("lint",(fileExt == "js" || fileExt == "json") && ICEcoder.codeAssist ? true : false);
		}
	}
}

// Comment/uncomment line or selected range on keypress
ICEcoder.lineCommentToggleSub = function(cM, cursorPos, linePos, lineContent, lCLen) {
	var comments, startLine, endLine, commentCH, commentBS, commentBE;

	// Language specific commenting
	if (["JavaScript","CoffeeScript","TypeScript","PHP","Python","Ruby","CSS","SQL","Erlang","Julia","Java","YAML","C","C++","C#","Go","Lua","Perl","Sass"].indexOf(ICEcoder.caretLocType)>-1) {

		comments = {
			"JavaScript"	: ["// ", "/* ", " */"],
			"CoffeeScript"	: ["# ", "### ", " ###"],
			"TypeScript"	: ["// ", "/* ", " */"],
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
			"Sass"		: ["// ", "/* ", " */"]
		}

		// Identify the single line, block start and block end comment chars
		commentCH = comments[ICEcoder.caretLocType][0];
		commentBS = comments[ICEcoder.caretLocType][1];
		commentBE = comments[ICEcoder.caretLocType][2];

		// Block commenting
		if (cM.somethingSelected()) {
			// Language has no block commenting, so repeating singles are needed
			if (["Ruby","Python","Erlang","Julia","YAML","Perl"].indexOf(ICEcoder.caretLocType)>-1) {
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
			if (["CSS","SQL"].indexOf(ICEcoder.caretLocType)>-1) {
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
};

// Determine which area of the document we're in
ICEcoder.caretLocationType = function() {
	var cM, cMdiff, thisCM, caretLocType, caretChunk, fileName, fileExt;

	cM = ICEcoder.getcMInstance();
	cMdiff = ICEcoder.getcMdiffInstance();
	thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
	caretLocType = "Unknown";
	caretChunk = thisCM.getValue().substr(0,ICEcoder.caretPos+1);

	if(caretChunk.lastIndexOf("<script")>caretChunk.lastIndexOf("/script>")&&caretLocType=="Unknown") {caretLocType = "JavaScript";}
	else if (caretChunk.lastIndexOf("<\?")>caretChunk.lastIndexOf("?\>")&&caretLocType=="Unknown") {caretLocType = "PHP";}
	else if (caretChunk.lastIndexOf("<\%")>caretChunk.lastIndexOf("%\>")&&caretLocType=="Unknown") {caretLocType = "Ruby";}
	else if (caretChunk.lastIndexOf("<style")>caretChunk.lastIndexOf("/style>")&&caretLocType=="Unknown") {caretLocType = "CSS";}
	else if (caretChunk.lastIndexOf("<")>caretChunk.lastIndexOf(">")&&caretLocType=="Unknown") {caretLocType = "HTML";}
	else if (caretLocType=="Unknown") {caretLocType = "Content";};

	fileName = ICEcoder.openFiles[ICEcoder.selectedTab-1];
	if (caretLocType == "Content" && fileName) {
		fileExt = fileName.split(".");
		fileExt = fileExt[fileExt.length-1];
		caretLocType =
			  fileExt == "js"	? "JavaScript"
			: fileExt == "coffee"	? "CoffeeScript"
			: fileExt == "ts"	? "TypeScript"
			: fileExt == "py"	? "Python"
			: fileExt == "mpy"	? "Python"
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
			: fileExt == "scss"	? "Sass"
			: "Content";
	}

	ICEcoder.caretLocType = caretLocType;
}
</script>
