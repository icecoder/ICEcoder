// Get any elem by ID
var get = function(elem) {
	return top.document.getElementById(elem);
};

// Main ICEcoder object
var ICEcoder = {

// ==============
// INIT
// ==============

	// Define settings
	filesW:			250,		// Width of files pane
	minFilesW:		14,		// Min width of files pane
	maxFilesW:		250,		// Max width of files pane
	selectedTab:		0,		// The tab that's currently selected
	savedPoints:		[],		// Ints array to indicate save points for docs
	canSwitchTabs:		true,		// Stops switching of tabs when trying to close
	openFiles:		[],		// Array of open file URLs
	openFileMDTs:		[],		// Array of open file modification datetimes
	openFileVersions:	[],		// Array of open file version counts
	cMInstances:		[],		// List of CodeMirror instance no's
	nextcMInstance:		1,		// Next available CodeMirror instance no
	selectedFiles:		[],		// Array of selected files
	findMode:		false,		// States if we're in find/replace mode
	scrollbarVisible:	false,		// Indicates if the main pane has a scrollbar
	mouseDown:		false,		// If the mouse is down
	draggingFilesW:		false,		// If we're dragging the file manager width
	draggingTab:		false,		// If we're dragging a tab
	draggingWithKey:	false,		// The key that's down while dragging, false if no key
	tabLeftPos:		[],		// Array of left positions of tabs inside content area
	tabBGcurrent:		'#1d1d1b',	// BG of current tab
	tabBGselected:		'#49d',		// BG of selected tab
	tabBGopen:		'#c3c3c3',	// BG of open tab
	tabBGnormal:		'transparent',	// BG of normal tab
	tabFGcurrent:		'#fff',		// FG of selected tab
	tabFGselected:		'#fff',		// FG of selected tab
	tabFGopenFile:		'#000',		// FG of open file
	tabFGnormalFile:	'#eee',		// FG of normal file
	tabFGnormalTab:		'#888',		// FG of normal tab
	serverQueueItems:	[],		// Array of URLs to call in order
	previewWindow:		false,		// Target variable for the preview window
	previewWindowLoading:	false,		// Loading state of preview window
	pluginIntervalRefs:	[],		// Array of plugin interval refs
	overPopup:		false,		// Indicates if we're over a popup or not
	cmdKey:			false,		// Tracking apple Command key up/down state
	endTagReplaceData:	[],		// Will contain data for automatic end tag replacement
	fmReady:		false,		// Indicates if the file manager is ready for action
	bugReportStatus:	"off",		// Values of: off, error, ok, bugs
	bugReportPath:		"",		// Bug report file path
	bugFilesSizesSeen:	[],		// Array of last seen sizes of bug files
	bugFilesSizesActual:	[],		// Array of actual sizes of bug files
	githubDiff:		false,		// Toggle for viewing GitHub/FM diff view
	githubAuthTokenSet:	false,		// Has the user set their GitHub token yet
	splitPane:		false,		// Single or split pane editing
	renderLineStyle:	[],		// Array of styles to apply on renderLine event
	renderPaneShiftAmount:	0,		// Shift comparison main (negative) vs diff pane (positive)
	debounce:		"",		// Contains debounce timeout object
	editorFocusInstance:	"",		// Name of editor instance that has focus
	ready:			false,		// Indicates if ICEcoder is ready for action

	// Set our aliases
	initAliases: function() {
		var aliasArray = ["header","files", "fileOptions", "optionsFile", "optionsEdit", "optionsSource", "optionsHelp", "filesFrame","editor","tabsBar","findBar","content","footer","nestValid","versionsDisplay","splitPaneControls","charDisplay","byteDisplay"];

		// Create our ID aliases
		for (var i=0;i<aliasArray.length;i++) {
			ICEcoder[aliasArray[i]] = top.get(aliasArray[i]);
		}
	},

	// On load, set the layout and check any nesting is valid
	init: function() {
		var screenIcon, sISrc;

		// Contract the file manager if the user has set to have it hidden
		if (!top.ICEcoder.lockedNav) {
			top.ICEcoder.filesW = ICEcoder.minFilesW;
		}

		// Set layout
		ICEcoder.setLayout();

		top.ICEcoder.overFileFolder('folder', '|');
		top.ICEcoder.selectFileFolder('init');
		top.ICEcoder.filesFrame.contentWindow.focus();

		// Hide the loading screen & auto open last files?
		top.ICEcoder.showHide('hide',top.get('loadingMask'));
		top.ICEcoder.autoOpenInt = setInterval(function() {
			if (top.ICEcoder.fmReady) {
				// Delay auto open process by 200ms to give trial bar time to begin animation
				if (top.ICEcoder.openLastFiles) {setTimeout(function() {top.ICEcoder.autoOpenFiles()},200);};
				clearInterval(top.ICEcoder.autoOpenInt);
			}
		}, 4);

		// Update the nesting indicator every 30ms
		setInterval(ICEcoder.updateNestingIndicator,30);

		// Start bug checking
		top.ICEcoder.startBugChecking();

		// ICEcoder is ready to start using
		top.ICEcoder.ready = true;
	},

// ==============
// LAYOUT
// ==============

	// Set our layout according to the browser size
	setLayout: function(dontSetEditor) {
		var winW, winH, headerH, fileNavH, tabsBarH, findBarH;

		// Determin width & height available
		winW = window.innerWidth;
		winH = window.innerHeight;

		// Apply sizes to various elements of the page
		headerH = 25, fileNavH = 35, tabsBarH = 21, findBarH = 28;
		this.header.style.width = this.tabsBar.style.width = this.findBar.style.width = winW + "px";
		this.files.style.width = this.editor.style.left = this.filesW + "px";
		this.optionsFile.style.width = this.optionsEdit.style.width = this.optionsSource.style.width = this.optionsHelp.style.width = (this.filesW-60) + "px";
		this.filesFrame.style.height = (winH-headerH-fileNavH) + "px";
		this.nestValid.style.left = (this.filesW+10) + "px";
		this.versionsDisplay.style.left = (this.filesW+25) + "px";
		this.splitPaneControls.style.left = (parseInt((winW-this.filesW)/2,10)-25-4+this.filesW) + "px";
		top.ICEcoder.setTabWidths();

		// If we need to set the editor sizes
		if (!dontSetEditor) {
			this.editor.style.width = ICEcoder.content.style.width = (winW-this.filesW) + "px";
			ICEcoder.content.style.height = (winH-headerH-tabsBarH-findBarH-26) + "px";

			// Resize the CodeMirror instances to match the window size
			setTimeout(function(){
				for (var i=0;i<top.ICEcoder.openFiles.length;i++) {
					// Done the long way here as we need to call them in specific order to stop showing background and so avoiding a flicker effect
					if (top.ICEcoder.splitPane) {
						top.ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]+'diff'].setSize("50%",top.ICEcoder.content.style.height);
						top.ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]].setSize("50%",top.ICEcoder.content.style.height);
					} else {
						top.ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]].setSize("100%",top.ICEcoder.content.style.height);
						top.ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]+'diff'].setSize("0",top.ICEcoder.content.style.height);
					}
				}
				// Place resultsBar to edge of pane or it's scrollbar
				if (!top.ICEcoder.splitPane) {
					top.ICEcoder.content.contentWindow.document.getElementById('resultsBar').style.right = !top.ICEcoder.scrollBarVisible ? "0" : "17px";
				} else {
					top.ICEcoder.content.contentWindow.document.getElementById('resultsBar').style.right = !top.ICEcoder.scrollBarVisible ? parseInt(parseInt(ICEcoder.content.style.width,10)/2,10)+"px" : (parseInt(parseInt(ICEcoder.content.style.width,10)/2,10)+17)+"px";
				}
			},4);
		}
	},

	// Set the layout as split pane or not
	setSplitPane: function(onOff) {
		var cM, cMdiff;

		top.ICEcoder.splitPane = onOff == "on" ? true : false;
		top.get('splitPaneControlsOff').style.opacity = top.ICEcoder.splitPane ? 0.5 : 1;
		top.get('splitPaneControlsOn').style.opacity = top.ICEcoder.splitPane ? 1 : 0.5;
		top.ICEcoder.setLayout();

		// Also clear marks (if going to a single pane) or redo the marks (if split pane)
		if (top.ICEcoder.splitPane) {
			top.ICEcoder.updateDiffs();
			// Also set the scroll position to match
			cM = top.ICEcoder.getcMInstance();
			top.ICEcoder.cMonScroll(cM,'cM'+ICEcoder.cMInstances[ICEcoder.selectedTab-1]);
		} else {
			cM = top.ICEcoder.getcMInstance();
			cMdiff = top.ICEcoder.getcMdiffInstance();

			// Clear all main pane marks
			cMmarks = cM.getAllMarks();
			for (var i=0; i<cMmarks.length; i++) {
				cMmarks[i].clear();
			}
			// Clear all diff pane marks
			cMdiffMarks = cMdiff.getAllMarks();
			for (var i=0; i<cMdiffMarks.length; i++) {
				cMdiffMarks[i].clear();
			}
		}
	},

	// Set the width of the file manager on demand
	changeFilesW: function(expandContract) {
		if (!ICEcoder.lockedNav||ICEcoder.filesW==ICEcoder.minFilesW) {
			if ("undefined" != typeof ICEcoder.changeFilesInt) {clearInterval(ICEcoder.changeFilesInt)};
			ICEcoder.changeFilesInt = setInterval(function() {ICEcoder.changeFilesWStep(expandContract)},10);
		}
	},

	// Expand/contract the file manager in half-steps
	changeFilesWStep: function (expandContract) {
		if (expandContract=="expand") {
			ICEcoder.filesW < ICEcoder.maxFilesW-1 ? ICEcoder.filesW += Math.ceil((ICEcoder.maxFilesW-ICEcoder.filesW)/2) : ICEcoder.filesW = ICEcoder.maxFilesW;
		} else {
			ICEcoder.filesW > ICEcoder.minFilesW+1 ? ICEcoder.filesW -= Math.ceil((ICEcoder.filesW-ICEcoder.minFilesW)/2) : ICEcoder.filesW = ICEcoder.minFilesW;
		}
		if ((expandContract=="expand" && ICEcoder.filesW == ICEcoder.maxFilesW)||(expandContract=="contract" && ICEcoder.filesW == ICEcoder.minFilesW)) {
			clearInterval(ICEcoder.changeFilesInt);
		}
		// Redo the layout to match
		ICEcoder.setLayout();
	},

	// Can click-drag file manager width?
	canResizeFilesW: function() {
		// If we have the cursor set we must be able!
		if (top.ICEcoder.ready && top.document.body.style.cursor == "w-resize") {
			// If our mouse is down and we're within a 250-400px range
			if (top.ICEcoder.mouseDown) {
				top.ICEcoder.filesW = top.ICEcoder.maxFilesW = top.ICEcoder.mouseX >=250 && top.ICEcoder.mouseX <= 400
					? top.ICEcoder.mouseX : top.ICEcoder.mouseX <250 ? 250 : 400;
				// Set various widths based on the new width
				top.ICEcoder.files.style.width = top.ICEcoder.filesFrame.style.width = top.ICEcoder.filesW + "px";
				top.ICEcoder.setLayout();
				top.ICEcoder.draggingFilesW = true;
			}
		} else {
			top.ICEcoder.draggingFilesW = false;
		}
	},

	// Lock & unlock the file manager navigation on demand
	lockUnlockNav: function() {
		var lockIcon;

		lockIcon = top.ICEcoder.filesFrame.contentWindow.document.getElementById('fmLock');
		ICEcoder.lockedNav = !ICEcoder.lockedNav;
		lockIcon.style.backgroundPosition = ICEcoder.lockedNav ? "0 0" : "-16px 0";
	},

	// Show/hide the plugins on demand
	showHidePlugins: function(showHide) {
		get('plugins').style.width = showHide=="show" ? '55px' : '3px';
		get('plugins').style.background = showHide=="show" ? '#333' : 'transparent';
		if (showHide=="show") {
			ICEcoder.changeFilesW('expand');
		}
	},

// ==============
// EDITOR
// ==============

	// On key up
	cMonFocus: function(thisCM,cMinstance) {
		top.ICEcoder.getCaretPosition();
		top.ICEcoder.updateCharDisplay();
		top.ICEcoder.updateByteDisplay();
		top.ICEcoder.editorFocusInstance = cMinstance;
		top.ICEcoder.getCaretPosition();
	},

	// On blur
	cMonBlur: function(thisCM,cMinstance) {
		// Nothing as yet
	},

	// On key up
	cMonKeyUp: function(thisCM,cMinstance) {
		if ("undefined" != typeof top.doFind) {
			clearInterval(top.doFind);
		}
		top.doFind = setTimeout(function() {
			top.ICEcoder.findReplace(top.get('find').value,true,false);
		},500);
		top.ICEcoder.getCaretPosition();
		top.ICEcoder.updateCharDisplay();
		top.ICEcoder.updateByteDisplay();
	},

	// On cursor activity
	cMonCursorActivity: function(thisCM,cMinstance) {
		var thisCMPrevLine;

		top.ICEcoder.getCaretPosition();
		top.ICEcoder.updateCharDisplay();
		top.ICEcoder.updateByteDisplay();
		thisCM.removeLineClass(top.ICEcoder['cMActiveLine'+cMinstance], "background");
		if(thisCM.getCursor('start').line == thisCM.getCursor().line) {
			top.ICEcoder['cMActiveLine'+cMinstance] = thisCM.addLineClass(thisCM.getCursor().line, "background","cm-s-activeLine");
		}
		if (top.ICEcoder.caretLocType=="CSS") {
			top.ICEcoder.cssColorPreview();
		}

		thisCMPrevLine = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? top.ICEcoder.prevLineDiff : top.ICEcoder.prevLine;
		if (thisCMPrevLine != thisCM.getCursor().line && 
			thisCM.getLine(thisCMPrevLine) && 
			thisCM.getLine(thisCMPrevLine).length > 0 && 
			thisCM.getLine(thisCMPrevLine).replace(/\s/g, '').length == 0) {
				thisCM.replaceRange("",{line: thisCMPrevLine, ch: 0},{line: thisCMPrevLine, ch: 1000000});
		}
		// Set the cursor to text height, not line height
		setTimeout(function() {
			var paneMatch;

			// Loop through styles to check if we have to adjust cursor height
			for (var i=0; i<top.ICEcoder.renderLineStyle.length; i++) {

				// We have no matching pane to start with
				paneMatch = false;

				// Is the pane we need to set the cursor on this pane?
				if (
					(top.ICEcoder.renderLineStyle[i][0] != "diff" && cMinstance.indexOf("diff") == -1) ||
					(top.ICEcoder.renderLineStyle[i][0] == "diff" && cMinstance.indexOf("diff") > -1)
				)
				{paneMatch = true;}

				// If the pane matches & also the line we're on is the line we have a style set for, set that cursor height
				if (paneMatch && thisCM.getCursor().line+1 == top.ICEcoder.renderLineStyle[i][1]) {
					thisCM.setOption("cursorHeight",thisCM.defaultTextHeight() / thisCM.lineInfo(thisCM.getCursor().line).handle.height);
				} else {
					thisCM.setOption("cursorHeight",1);
				}

			}
		},0);
	},

	// On before change
	cMonBeforeChange: function(thisCM,cMinstance,changeObj,cM) {
		var sels, tokenString, range, canMaybeReplace, thisData;

		// For each of the user selections
		sels = thisCM.listSelections();
		for (var i=0; i<sels.length; i++) {
			// Get the token at the cursor start (anchor) position
			tokenString = thisCM.getTokenAt(sels[i].anchor);
			// If we're just inside a tag, move along 1 char pos and get token info at that position
			if (tokenString.type == "tag bracket" && tokenString.string == "<") {
				tokenString = thisCM.getTokenAt({line: sels[i].anchor.line, ch: sels[i].anchor.ch+1});
			}
			// If we're inside a tag now
			if (tokenString.type == "tag") {
				// Test for range info
				range = cM.fold['xml'](thisCM, sels[i].anchor);
				canMaybeReplace = true;
				for (var j=0; j<top.ICEcoder.endTagReplaceData.length; j++) {
					// If we have range info and we're start and end are on the same line
					if ("undefined" != typeof range && top.ICEcoder.endTagReplaceData[j].split(";")[1] == range.to.line + ":" + range.to.ch) {
						canMaybeReplace = false;
					}
				}
				// If we can still replace and have range info and not undoing/redoing (as that replaces chunks itself)
				if (canMaybeReplace && "undefined" != typeof range && changeObj.origin != "undo" && changeObj.origin != "redo") {
					// Work out the data string to set and if not in array, push in ready to handle on change event
					thisData = tokenString.string + ";" + range.to.line + ":" + range.to.ch;
					if (top.ICEcoder.endTagReplaceData.indexOf(thisData) == -1) {
						top.ICEcoder.endTagReplaceData.push(thisData);
					}
				}
			}
		}
	},

	// On change
	cMonChange: function(thisCM,cMinstance,changeObj) {
		var rData, thisToken, repl1, repl2, tTS, filepath, filename, fileExt;

		// If we're not loading the file, it's a change, so update tab
		if (!top.ICEcoder.loadingFile) {
			top.ICEcoder.redoTabHighlight(top.ICEcoder.selectedTab);
		}

		// Detect if we have a scrollbar & set layout again
		setTimeout(function() {
			top.ICEcoder.scrollBarVisible = thisCM.getScrollInfo().height > thisCM.getScrollInfo().clientHeight;
			top.ICEcoder.setLayout();
		},0);

		// If we're replacing end tag strings, do that
		if (top.ICEcoder.endTagReplaceData.length > 0) {

			// For each one of them, grab our data to work with
			for (var i=0; i<top.ICEcoder.endTagReplaceData.length; i++) {
				rData = top.ICEcoder.endTagReplaceData[i].split(";");

				// Don't do anything if it's the same line, as we can't rely on fold range data due to nested tags
				if (rData[1].split(":")[0]*1 == changeObj.from.line) {
					continue;
				}
				// Otherwise, work out the replace ranges
				repl1 = {line: rData[1].split(":")[0]*1, ch: (rData[1].split(":")[1]*1)+2};
				repl2 = {line: rData[1].split(":")[0]*1, ch: (rData[1].split(":")[1]*1)+2+rData[0].length};
				// Establish the string to replace with
				thisToken = thisCM.getTokenAt(thisCM.listSelections()[i].anchor);
				tTS = thisToken.string;
				if (tTS == "<" ) {
					tTS = "";
				}
				// Replace our string over the range
				thisCM.replaceRange(tTS, repl1, repl2);
			}

		}
		// Reset the array ready for next time
		top.ICEcoder.endTagReplaceData = [];

		top.ICEcoder.getCaretPosition();
		top.ICEcoder.updateCharDisplay();
		top.ICEcoder.updateByteDisplay();
		top.ICEcoder.updateNestingIndicator();
		if (top.ICEcoder.findMode) {
			top.ICEcoder.results.splice(top.ICEcoder.findResult,1);
			top.get('results').innerHTML = top.ICEcoder.results.length + " " + top.t['results'];
			top.ICEcoder.findMode = false;
		}
		filepath = top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1];
		if (filepath) {
			filename = filepath.substr(filepath.lastIndexOf("/")+1);
			fileExt = filename.substr(filename.lastIndexOf(".")+1);
			for (var i=changeObj.from.line; i<changeObj.from.line+changeObj.text.length; i++) {
				top.ICEcoder.content.contentWindow.CodeMirror.doFold(thisCM.getLine(i).indexOf("{")>-1 ? "brace" : "xml" ,null ,"+" ,"-", true)(thisCM, i);
			}
			if (changeObj.text[0] == "}" || changeObj.removed && changeObj.removed[0] == "}") {
				cursor = thisCM.getSearchCursor("{",thisCM.getCursor(),false);
				cursor.findPrevious();
				for (var i=cursor.from().line; i<thisCM.getCursor().line; i++) {
					top.ICEcoder.content.contentWindow.CodeMirror.doFold(thisCM.getLine(i).indexOf("{")>-1 ? "brace" : "xml" ,null ,"+" ,"-", true)(thisCM, i);
				}
			}
		}
		// Update diffs if we have a split pane
		if (top.ICEcoder.splitPane) {
			// Need 0ms tickover so we handle char change first
			setTimeout(function(){top.ICEcoder.updateDiffs();},0);
		}
		// Update HTML edited files live
		if (filepath && top.ICEcoder.previewWindow.location && filepath != "/[NEW]") {
			top.ICEcoder.updatePreviewWindow(thisCM,filepath,filename,fileExt);
		}
		// Update the title tag to indicate any changes
		top.ICEcoder.indicateChanges();
	},

	// On scroll
	cMonScroll: function(thisCM,cMinstance) {
		var cM, cMdiff, otherCM;

		top.ICEcoder.mouseDown=false;

		if (top.ICEcoder.splitPane) {
			// Get both main & diff instance and work out the instance we're not scrolling
			cM = top.ICEcoder.getcMInstance();
			cMdiff = top.ICEcoder.getcMdiffInstance();
			otherCM = cMinstance.indexOf('diff') > -1 ? cM : cMdiff;

			// Scroll other pane x & y to match this one we're scrolling, after a 0ms tickover to avoid judder
			setTimeout(function(){otherCM.scrollTo(thisCM.getScrollInfo().left, thisCM.getScrollInfo().top);},0);
		}

	},

	// On input read
	cMonInputRead: function(thisCM,cMinstance) {
		if (top.ICEcoder.autoComplete == "keypress" && top.ICEcoder.codeAssist) {
			// Debounce timeout wrapper left here for now, but can be removed in future if no negative effects seen
			// clearTimeout(top.ICEcoder.debounce);
			if (!thisCM.state.completionActive) {
				// top.ICEcoder.debounce = setTimeout(function() {
					top.ICEcoder.autocomplete();
				// },0);
			}
		}
	},

	// On render line
	cMonRenderLine: function(thisCM,cMinstance,line,element) {
		var paneMatch;

		// Loop through styles to use when rendering lines
		for (var i=0; i<top.ICEcoder.renderLineStyle.length; i++) {

			// We have no matching pane to start with
			paneMatch = false;

			// Is the pane we need to style this pane?
			if (
				(top.ICEcoder.renderLineStyle[i][0] != "diff" && cMinstance.indexOf("diff") == -1) ||
				(top.ICEcoder.renderLineStyle[i][0] == "diff" && cMinstance.indexOf("diff") > -1)
			)
			{paneMatch = true;}

			// If the pane matches & also the line we're rendering is the line we have a style set for, set that style
			if (paneMatch && thisCM.lineInfo(line).line+1 == top.ICEcoder.renderLineStyle[i][1]) {
				element.style[top.ICEcoder.renderLineStyle[i][2]] = top.ICEcoder.renderLineStyle[i][3];
			}

		}
	},

	// Update diffs shown to the user in each pane
	updateDiffs: function() {
		var cM, cMdiff, mainText, diffText, sm, opcodes, cMmarks, cMdiffMarks, amt, sDiffs;

		// Reset the style array container and main vs diff pane shift difference
		top.ICEcoder.renderLineStyle = [];
		top.ICEcoder.renderPaneShiftAmount = 0;

		cM = top.ICEcoder.getcMInstance();
		cMdiff = top.ICEcoder.getcMdiffInstance();

		// Get the baseText and newText values from the two textboxes, and split them into lines
		mainText = cM ? difflib.stringAsLines(cM.getValue()) : "";
		diffText = cMdiff ? difflib.stringAsLines(cMdiff.getValue()) : "";

		// Create a SequenceMatcher instance that diffs the two sets of lines
		sm = new difflib.SequenceMatcher(mainText, diffText);

		// Get the opcodes from the SequenceMatcher instance
		// Opcodes is a list of 3-tuples describing what changes should be made to the base text in order to yield the new text
		opcodes = sm.get_opcodes();

		if (cM) {
			// Clear all main pane marks
			cMmarks = cM.getAllMarks();
			for (var i=0; i<cMmarks.length; i++) {
				cMmarks[i].clear();
			}
			// Clear all diff pane marks
			cMdiffMarks = cMdiff.getAllMarks();
			for (var i=0; i<cMdiffMarks.length; i++) {
				cMdiffMarks[i].clear();
			}
		}

		if (cM && cMdiff.getValue() != "") {
			// For each opcode returned by jsdifflib
			for (var i=0; i<opcodes.length; i++) {
				// If not 'equal' status for the section, we have a 'replace', 'delete' or 'insert' status, so do something
				if (opcodes[i][0] !== "equal") {

					// =========
					// MAIN PANE
					// =========

					// Replacing? Pad out main pane line to match equivalent last line in diff pane
					if (opcodes[i][0] == "replace") {
						// Line amount is diff between end of both panes at this point in our loop, plus 1 line and our overall document shift, multiplied by font size
						amt = ((opcodes[i][4] - opcodes[i][2] + 1 + top.ICEcoder.renderPaneShiftAmount) * cM.defaultTextHeight());
						// Add on the extra heights for any wrapped lines
						for (var j=opcodes[i][4]-1; j<=opcodes[i][2]-1; j++) {
							if (cMdiff.getLineHandle(j).height > cM.defaultTextHeight()) {
								amt += cMdiff.getLineHandle(j).height - cM.defaultTextHeight();
							}
						}
						// If we have an height greater than the default text height, add a new style
						if (amt > cM.defaultTextHeight()) {
							top.ICEcoder.renderLineStyle.push(["main", opcodes[i][2], "height", amt + "px"]);
						}
						// Mark text in 2 colours, for each line
						for (var j=0; j<(opcodes[i][2]) - (opcodes[i][1]); j++)  {
							sDiffs = (top.ICEcoder.findStringDiffs(cM.getLine(opcodes[i][1]+j),cMdiff.getLine(opcodes[i][3]+j)));
							cM.markText({line: opcodes[i][1]+j, ch: 0}, {line: opcodes[i][3]+j + top.ICEcoder.renderPaneShiftAmount, ch: sDiffs[0]}, {className: "diffGreyLighter"});
							cM.markText({line: opcodes[i][1]+j, ch: sDiffs[0]}, {line: opcodes[i][3]+j + top.ICEcoder.renderPaneShiftAmount, ch: sDiffs[0]+sDiffs[1]}, {className: "diffGrey"});
							cM.markText({line: opcodes[i][1]+j, ch: sDiffs[0]+sDiffs[1]}, {line: opcodes[i][3]+j + top.ICEcoder.renderPaneShiftAmount, ch: 1000000}, {className: "diffGreyLighter"});
						}	
					// Inserting
					} else {
						cM.markText({line: opcodes[i][1], ch: 0}, {line: opcodes[i][2]-1, ch: 1000000}, {className: "diffGreen"});
					}

					// If inserting or deleting and the main pane hasn't changed, we need to pad out the line in that pane
					if (opcodes[i][0] != "replace" && opcodes[i][1] == opcodes[i][2]) {
						top.ICEcoder.renderLineStyle.push(["main", opcodes[i][2], "height", ((opcodes[i][4] - opcodes[i][3] + 1) * cM.defaultTextHeight()) + "px"]);
						// Mark the range with empty class
						cM.markText({line: opcodes[i][2]-1, ch: 0}, {line: opcodes[i][2]-1, ch: 1000000}, {className: "diffNone"});
					}

					// =========
					// DIFF PANE
					// =========

					// Replacing? Pad out diff pane line to match equivalent last line in main pane
					if (opcodes[i][0] == "replace") {
						// Line amount is diff between end of both panes at this point in our loop, plus 1 line and our overall document shift, multiplied by font size
						amt = ((opcodes[i][2] - opcodes[i][4] + 1 - top.ICEcoder.renderPaneShiftAmount) * cM.defaultTextHeight());
						// Add on the extra heights for any wrapped lines
						for (var j=opcodes[i][4]-1; j<=opcodes[i][2]-1; j++) {
							if (cM.getLineHandle(j).height > cM.defaultTextHeight()) {
								amt += cM.getLineHandle(j).height - cM.defaultTextHeight();
							}
						}
						// If we have an height greater than the default text height, add a new style
						if (amt > cM.defaultTextHeight()) {
							top.ICEcoder.renderLineStyle.push(["diff", opcodes[i][4], "height", amt + "px"]);
						}
						// Mark text in 2 colours, for each line
						for (var j=0; j<(opcodes[i][4]) - (opcodes[i][3]); j++)  {
							sDiffs = (top.ICEcoder.findStringDiffs(cM.getLine(opcodes[i][1]+j),cMdiff.getLine(opcodes[i][3]+j)));
							cMdiff.markText({line: opcodes[i][1]+j - top.ICEcoder.renderPaneShiftAmount, ch: 0}, {line: opcodes[i][3]+j, ch: sDiffs[0]}, {className: "diffGreyLighter"});
							cMdiff.markText({line: opcodes[i][1]+j - top.ICEcoder.renderPaneShiftAmount, ch: sDiffs[0]}, {line: opcodes[i][3]+j, ch: sDiffs[0]+sDiffs[2]}, {className: "diffGrey"});
							cMdiff.markText({line: opcodes[i][1]+j - top.ICEcoder.renderPaneShiftAmount, ch: sDiffs[0]+sDiffs[2]}, {line: opcodes[i][3]+j, ch: 1000000}, {className: "diffGreyLighter"});
						}
					// Deleting
					} else {
						cMdiff.markText({line: opcodes[i][3], ch: 0}, {line: opcodes[i][4]-1, ch: 1000000}, {className: "diffRed"});
					}

					// If inserting or deleting and the diff pane hasn't changed, we need to pad out the line in that pane
					if (opcodes[i][0] != "replace" && opcodes[i][3] == opcodes[i][4]) {
						top.ICEcoder.renderLineStyle.push(["diff", opcodes[i][4], "height", ((opcodes[i][2] - opcodes[i][1] + 1) * cM.defaultTextHeight()) + "px"]);
						// Mark the range with empty class
						cMdiff.markText({line: opcodes[i][4]-1, ch: 0}, {line: opcodes[i][4]-1, ch: 1000000}, {className: "diffNone"});
					}

					// Finally, set the last amount shifted for this change
					top.ICEcoder.renderPaneShiftAmount = (opcodes[i][2] - opcodes[i][4]);
				}
			}
		}
	},

	// Find diffs between 2 strings
	findStringDiffs: function(a, b) {
		if ("undefined" == typeof a) {a = ""};
		if ("undefined" == typeof b) {b = ""};
		for (var c = 0,				// start from the first character
			d = a.length, e = b.length;	// and from the last characters of both strings
			a[c] &&				// if not at the end of the string and
			a[c] == b[c];			// if both strings are equal at this position
			c++);				// go forward
		for (; d > c & e > c &			// stop at the position found by the first loop
			a[d - 1] == b[e - 1];		// if both strings are equal at this position
			d--) e--;			// go backward
		return[c, d - c, e - c]			// return position and lengths of the two substrings found
	},


	// Update preview window content
	updatePreviewWindow: function(thisCM,filepath,filename,fileExt) {
		if (top.ICEcoder.previewWindow.location.pathname==filepath) {
			if (["htm","html","txt"].indexOf(fileExt) > -1) {
				top.ICEcoder.previewWindow.document.documentElement.innerHTML = thisCM.getValue();
			} else if (["md"].indexOf(fileExt) > -1) {
				top.ICEcoder.previewWindow.document.documentElement.innerHTML = mmd(thisCM.getValue());
			}
		} else if (["css"].indexOf(fileExt) > -1) {
			if (top.ICEcoder.previewWindow.document.documentElement.innerHTML.indexOf(filename) > -1) {
				var css = thisCM.getValue();
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
		// Do the pesticide plugin if it exists
		try {top.ICEcoder.doPesticide();} catch(err) {};
		// Do the stats.js plugin if it exists
		try {top.ICEcoder.doStatsJS('update');} catch(err) {};
		// Do the responsive plugin if it exists
		try {top.ICEcoder.doResponsive();} catch(err) {};
	},

	// Clean up our loaded code
	contentCleanUp: function() {
		var cM, cMdiff, thisCM, content;

		// Replace any temp /textarea value
		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		content = thisCM.getValue();
		content = content.replace(/<ICEcoder:\/:textarea>/g,'<\/textarea>');

		// Then set the content in the editor & clear the history
		thisCM.setValue(content);
		thisCM.clearHistory();
		top.ICEcoder.savedPoints[top.ICEcoder.selectedTab-1] = thisCM.changeGeneration();
	},

	// Undo last change
	undo: function() {
		var cM, cMdiff, thisCM;

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		thisCM.undo();
	},

	// Redo change
	redo: function() {
		var cM, cMdiff, thisCM;

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		thisCM.redo();
	},

	// Indent more/less
	indent: function(moreLess) {
		var cM, cMdiff, thisCM;

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		if (moreLess=="more") {
			top.ICEcoder.content.contentWindow.CodeMirror.commands.indentMore(thisCM);
		} else {
			top.ICEcoder.content.contentWindow.CodeMirror.commands.indentLess(thisCM);
		}
	},

	// Move current line up/down
	moveLines: function(dir) {
		var cM, cMdiff, thisCM, lineStart, lineEnd, swapLineNo, swapLine;

		cM = top.ICEcoder.getcMInstance();
		cMdiff = top.ICEcoder.getcMdiffInstance();
		thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;

		// Get start & end lines plus the line we'll swap with
		lineStart = thisCM.getCursor('start');
		lineEnd = thisCM.getCursor('end');
		if (dir=="up" && lineStart.line>0) {swapLineNo = lineStart.line-1}
		if (dir=="down" && lineEnd.line<thisCM.lineCount()-1) {swapLineNo = lineEnd.line+1}

		// If we have a line to swap with
		if (!isNaN(swapLineNo)) {
			// Get the content of the swap line and carry out the swap in a single operation
			swapLine = thisCM.getLine(swapLineNo);
			thisCM.operation(function() {
				// Move lines in turn up
				if (dir=="up") {
					for (var i=lineStart.line; i<=lineEnd.line; i++) {
						thisCM.replaceRange(thisCM.getLine(i),{line:i-1,ch:0},{line:i-1,ch:1000000});
					}
				// ...or down
				} else {
					for (var i=lineEnd.line; i>=lineStart.line; i--) {
						thisCM.replaceRange(thisCM.getLine(i),{line:i+1,ch:0},{line:i+1,ch:1000000});
					}
				}
				// Now swap our final line with the swap line to complete the move
				thisCM.replaceRange(swapLine,{line: dir=="up" ? lineEnd.line : lineStart.line, ch: 0},{line: dir=="up" ? lineEnd.line : lineStart.line, ch:1000000});
				// Finally set the moved selection
				thisCM.setSelection(
					{line: lineStart.line+(dir=="up" ? -1 : 1), ch: lineStart.ch},
					{line: lineEnd.line+(dir=="up" ? -1 : 1), ch: lineEnd.ch}
				);
			});
		}
	},

	// Highlight specified line
	highlightLine: function(line) {
		var cM, cMdiff, thisCM;

		cM = top.ICEcoder.getcMInstance();
		cMdiff = top.ICEcoder.getcMdiffInstance();
		thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		thisCM.setSelection({line:line,ch:0}, {line:line,ch:thisCM.lineInfo(line).text.length});
	},

	// Focus the editor
	focus: function(diff) {
		var cM, cMdiff, thisCM;

		if (!(/iPhone|iPad|iPod/i.test(navigator.userAgent))) {
			cM = top.ICEcoder.getcMInstance();
			cMdiff = top.ICEcoder.getcMdiffInstance();
			thisCM = diff ? cMdiff : cM;
			if (thisCM) {
				thisCM.focus();
			}
		}
	},

	// Go to a specific line number
	goToLine: function(lineNo) {
		var cM, cMdiff, thisCM;

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;

		thisCM.setCursor(lineNo ? lineNo-1 : top.get('goToLineNo').value-1);
		top.ICEcoder.focus();
		// Also do this after a 0ms tickover incase DOM wasn't ready
		setTimeout(function(){top.ICEcoder.focus();},0);
		return false;
	},

	// Comment/uncomment line or selected range on keypress
	lineCommentToggle: function() {
		var cM, cMdiff, thisCM, cursorPos, linePos, lineContent, lCLen;

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;

		cursorPos = thisCM.getCursor().ch;
		linePos = thisCM.getCursor().line;
		lineContent = thisCM.getLine(linePos);
		lCLen = lineContent.length;

		ICEcoder.lineCommentToggleSub(thisCM, cursorPos, linePos, lineContent, lCLen);
	},

	// Wrap our selected text/cursor with tags
	tagWrapper: function(tag) {
		var cM, cMdiff, thisCM, tagStart, tagEnd, startLine, endLine;

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		tagStart = tag;
		tagEnd = tag;
		if (tag=='div') {
			startLine = thisCM.getCursor('start').line;
			endLine = thisCM.getCursor().line;
			thisCM.operation(function() {
				thisCM.replaceSelection("<div>\n"+thisCM.getSelection()+"\n</div>","around");
				for (var i=startLine+1; i<=endLine+1; i++) {
					thisCM.indentLine(i);
				}
				thisCM.indentLine(endLine+2,'prev');
				thisCM.indentLine(endLine+2,'subtract');
			});
		} else {
			if (	['p','a','h1','h2','h3'].indexOf(tag)>-1 && 
				thisCM.getSelection().substr(0,tag.length+1) == "<"+tagStart && 
				thisCM.getSelection().substr(-(tag.length+3)) == "</"+tagEnd+">") {
					// Undo wrapper
					thisCM.replaceSelection(thisCM.getSelection().substr(thisCM.getSelection().indexOf(">")+1,thisCM.getSelection().length-thisCM.getSelection().indexOf(">")-1-tag.length-3),"around");
			} else {
					if (tag=='a') {tagStart='a href=""';}
					// Do wrapper
					thisCM.replaceSelection("<"+tagStart+">"+thisCM.getSelection()+"</"+tagEnd+">","around");
					if (tag=='a') {thisCM.setCursor({line:thisCM.getCursor('start').line,ch:thisCM.getCursor('start').ch+9})}
			}
		}
	},

	// Add a line break at end of current or specified line
	addLineBreakAtEnd: function(line) {
		var cM,cMdiff, thisCM;

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		if (!line) {line = thisCM.getCursor().line};
		thisCM.replaceRange(thisCM.getLine(line)+"<br>",{line:line,ch:0},{line:line,ch:1000000});
	},

	// Insert a line before and auto-indent
	insertLineBefore: function(line) {
		var cM, cMdiff, thisCM;

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		if (!line) {line = thisCM.getCursor().line};
		thisCM.operation(function() {
			thisCM.replaceRange("\n"+thisCM.getLine(line),{line:line,ch:0},{line:line,ch:1000000});
			thisCM.setCursor({line: thisCM.getCursor().line-1, ch: 0});
			thisCM.execCommand('indentAuto');
		});
	},

	// Insert a line after and auto-indent
	insertLineAfter: function(line) {
		var cM, cMdiff, thisCM;

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		if (!line) {line = thisCM.getCursor().line};
		thisCM.operation(function() {
			thisCM.replaceRange(thisCM.getLine(line)+"\n",{line:line,ch:0},{line:line,ch:1000000});
			thisCM.execCommand('indentAuto');
		});
	},

	// Duplicate line
	duplicateLines: function(line) {
		var cM, cMdiff, thisCM, ch, lineExtra, userSelStart, userSelEnd, lineBreak;

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		if (!line && thisCM.somethingSelected()) {
			userSelStart = thisCM.getCursor('start');
			userSelEnd = thisCM.getCursor('end');
			lineExtra = userSelStart.line != userSelEnd.line && userSelEnd.ch == thisCM.getLine(userSelEnd.line).length ? "\n" : "";
			thisCM.replaceSelection(thisCM.getSelection()+lineExtra+thisCM.getSelection(), "end");
			thisCM.setSelection(userSelStart, userSelEnd);
		} else {
			if (!line) {line = thisCM.getCursor().line};
			ch = thisCM.getCursor().ch;
			thisCM.replaceRange(thisCM.getLine(line)+"\n"+thisCM.getLine(line),{line:line,ch:0},{line:line,ch:1000000});
			thisCM.setCursor(line+1,ch);
		}
	},

	// Remove line
	removeLines: function(line) {
		var cM, cMdiff, thisCM, ch;

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		if (!line && thisCM.somethingSelected()) {
			thisCM.replaceSelection("","end");
		} else {
			if (!line) {line = thisCM.getCursor().line};
			ch = thisCM.getCursor().ch;
			thisCM.execCommand('deleteLine');
			thisCM.setCursor(line-1,ch);
		}
	},

	// Jump to and highlight the function definition current token
	jumpToDefinition: function() {
		var cM, cMdiff, thisCM, tokenString, defVars;

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		tokenString = thisCM.getTokenAt(thisCM.getCursor()).string;

		if (thisCM.somethingSelected() && top.ICEcoder.origCurorPos) {
			thisCM.setCursor(top.ICEcoder.origCurorPos);
		} else {
			top.ICEcoder.origCurorPos = thisCM.getCursor();
			defVars = [
				"var "+tokenString,
				"function "+tokenString,
				tokenString+"=function", tokenString+"= function", tokenString+" =function", tokenString+" = function",
				tokenString+"=new function", tokenString+"= new function", tokenString+" =new function", tokenString+" = new function",
				"window['"+tokenString+"']", "window[\""+tokenString+"\"]", 
				"this['"+tokenString+"']", "this[\""+tokenString+"\"]", 
				tokenString+":", tokenString+" :",
				"def "+tokenString,
				"class "+tokenString];
			for (var i=0; i<defVars.length; i++) {
				if (top.ICEcoder.findReplace(defVars[i],false,false)) {
					break;
				}
			}
		}
	},

	// Autocomplete
	autocomplete: function() {
		var cM, cMdiff, thisCM;

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		top.ICEcoder.content.contentWindow.CodeMirror.commands.autocomplete(thisCM);
	},

	// Paste a URL, locally or absolutely if CTRL/Cmd key down
	pasteURL: function(url) {
		var cM, cMdiff, thisCM;

		cM = top.ICEcoder.getcMInstance();
		cMdiff = top.ICEcoder.getcMdiffInstance();
		thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		if(top.ICEcoder.draggingWithKey == "CTRL") {
			url = window.location.protocol + "//" + window.location.hostname + url;
		}
		thisCM.replaceSelection(url,"around");
	},

	// Search for selected text online
	searchForSelected: function() {
		var cM, cMdiff, thisCM;

		cM = top.ICEcoder.getcMInstance();
		cMdiff = top.ICEcoder.getcMdiffInstance();
		thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		if (top.ICEcoder.caretLocType) {
			if (thisCM.getSelection() != "") {
				var searchPrefix = top.ICEcoder.caretLocType.toLowerCase()+" ";
				if (top.ICEcoder.caretLocType=="Content") {
					searchPrefix = "";
				}
				window.open("http://www.google.com/#output=search&q="+searchPrefix+thisCM.getSelection());
			} else {
				top.ICEcoder.message(top.t['No text selected...']);
			}
		}
	},

// ==============
// FILES
// ==============

	// Actions on file manager
	fmAction: function(evt,action) {
		var selElem, sPN, fileFolder, goElem,

		// Get selected elem, the parent node of that, if it's a file/folder and set elem to go to next
		selElem = top.get('filesFrame').contentWindow.document.getElementById(top.ICEcoder.selectedFiles[top.ICEcoder.selectedFiles.length-1]+"_perms").parentNode;
		sPN = selElem.parentNode;
		fileFolder = selElem.onmouseover.toString().indexOf("'folder'") > -1 ? "folder" : "file";
		goElem = false;

		if (action == "up") {
			if (sPN.previousSibling && sPN.previousSibling.previousSibling) {
				goElem = sPN.previousSibling.previousSibling;				// Jump to previous sibling
				if (goElem.tagName == "UL") {
					goElem = goElem.childNodes[goElem.childNodes.length-1];		// Jump to last item in previous sibling dir
				}
			} else if (sPN.parentNode.previousSibling) {
				goElem = sPN.parentNode.previousSibling;				// Jump to parent dir
			}
			if (goElem) {goElem = goElem.childNodes[0]};
		}
		if (action == "down") {
			if (sPN.nextSibling && sPN.nextSibling.childNodes[0]) {
				goElem = sPN.nextSibling.childNodes[0];					// Jump to first item in dir
			} else if (sPN.nextSibling && sPN.nextSibling.nextSibling) {
				goElem = sPN.nextSibling.nextSibling;					// Jump to next sibling
			} else if (sPN.parentNode.nextSibling) {
				goElem = sPN.parentNode.nextSibling.nextSibling;			// Jump to next parent sibling item
			}
			if (goElem) {goElem = goElem.childNodes[0]};
		}
		if (action == "left") {
			if (fileFolder == "folder" && sPN.parentNode.previousSibling) {
				top.ICEcoder.openCloseDir(selElem,false);				// contract dir
			}
		}
		if (action == "right" || action == "enter") {
			fileFolder == "folder"
				? top.ICEcoder.openCloseDir(selElem,true)				// expand dir
				: top.ICEcoder.openFile(selElem.childNodes[1].id.replace(/\|/g,"/"));	// open file
		}
		if (goElem && goElem.childNodes[1]) {
			top.ICEcoder.overFileFolder(fileFolder, goElem.childNodes[1].id);		// If we have an elem to go to, select it
			top.ICEcoder.selectFileFolder(evt);
		}
	},

	// Open/close dirs on demand
	openCloseDir: function(dir,load) {
		var node, d;

		dir.onclick = function(event) {
			if(!event.ctrlKey && !top.ICEcoder.cmdKey) {
				top.ICEcoder.openCloseDir(this,!load);
			}
		};
		node = dir.parentNode;
		if (node.nextSibling) {node = node.nextSibling};
		if (node && node.tagName=="UL") {
			d = node.style.display=="none";
			d ? load = true : node.style.display = "none";
			dir.parentNode.className = dir.className = d ? "pft-directory dirOpen" : "pft-directory";
		}
		if (load) {
			top.ICEcoder.filesFrame.contentWindow.frames['fileControl'].location.href = "lib/get-branch.php?location="+dir.childNodes[1].id+"&csrf="+top.ICEcoder.csrf;
		} else if(node.tagName == "UL") {
			node.parentNode.removeChild(node);
		}
		return false;
	},

	// Note which files or folders we are over on mouseover/mouseout
	overFileFolder: function(type, link) {
		ICEcoder.thisFileFolderType=type;
		ICEcoder.thisFileFolderLink=link;
	},

	// Detect and return dir/file/false for this DOM ref (false for not found)
	isFileFolder: function(ref) {
		var domElem;

		domElem = top.get('filesFrame').contentWindow.document.getElementById(ref.replace(top.iceRoot,"").replace(/\/$/, "").replace(/\//g,"|"));
		if (domElem) {
			return domElem.parentNode.parentNode.className.indexOf("directory") > -1
			? "folder"
			: "file";
		} else {
			return false;
		}
	},

	// Select file or folder on demand
	selectFileFolder: function(evt,ctrlSim,shiftSim) {
		var tgtFile, shortURL, selecting, dirList, lastFileClicked, startFile, endFile, thisFileObj;

		// If we've clicked somewhere other than a file/folder
		if (top.ICEcoder.thisFileFolderLink=="") {
			if (!ctrlSim && !evt.ctrlKey && !top.ICEcoder.cmdKey) {
				top.ICEcoder.deselectAllFiles();
			}
		} else if (top.ICEcoder.thisFileFolderLink) {
			// Get file URL, with pipes instead of slashes & target DOM elem
			shortURL = top.ICEcoder.thisFileFolderLink.replace(/\//g,"|");
			tgtFile = top.ICEcoder.filesFrame.contentWindow.document.getElementById(shortURL);

			// If we have the CTRL/Cmd key down
			if (ctrlSim || evt.ctrlKey || top.ICEcoder.cmdKey) {
				// Deselect or select file
				if (top.ICEcoder.selectedFiles.indexOf(shortURL)>-1) {
					ICEcoder.selectDeselectFile('deselect',tgtFile);
					top.ICEcoder.selectedFiles.splice(top.ICEcoder.selectedFiles.indexOf(shortURL),1);
				} else {
					ICEcoder.selectDeselectFile('select',tgtFile);
					top.ICEcoder.selectedFiles.push(shortURL);
				}
			// Select from last click to this one
			} else if (shiftSim || evt.shiftKey) {
				selecting = false;
				dirList = tgtFile.parentNode.parentNode.parentNode;
				lastFileClicked = top.ICEcoder.selectedFiles[top.ICEcoder.selectedFiles.length-1];

				// Prefix numbers with up to 20 leading zeros
				// This is so we can have some kind of natural comparison on the regex below
				function prefixer(match, p1, offset, string) {
					return ('00000000000000000000'+match).substr(-20);
				}

				startFile = shortURL.replace(/\d+/g, prefixer) < lastFileClicked.replace(/\d+/g, prefixer) ? shortURL : lastFileClicked;
				endFile = shortURL.replace(/\d+/g, prefixer) > lastFileClicked.replace(/\d+/g, prefixer) ? shortURL : lastFileClicked;

				if (top.ICEcoder.selectedFiles.length > 0 && startFile.substr(0,startFile.lastIndexOf("|")) == endFile.substr(0,endFile.lastIndexOf("|"))) {
					for (var i=0; i<1000000; i+=2) {
						if(dirList.childNodes[i].nodeName != "LI") {i++;};
						thisFileObj = dirList.childNodes[i].childNodes[0].childNodes[1];
						if (thisFileObj.id == startFile) {
							selecting = true;
						}
						if (selecting==true && top.ICEcoder.selectedFiles.indexOf(thisFileObj.id)==-1) {
							ICEcoder.selectDeselectFile('select',thisFileObj);
							top.ICEcoder.selectedFiles.push(thisFileObj.id);
						}
						if (thisFileObj.id == endFile) {
							break;
						}
					}
				} else {
					ICEcoder.selectDeselectFile('select',tgtFile);
					top.ICEcoder.selectedFiles.push(shortURL);
				}
			// We are single clicking
			} else {
				top.ICEcoder.deselectAllFiles();

				// Add our URL and highlight the file
				ICEcoder.selectDeselectFile('select',tgtFile);
				top.ICEcoder.selectedFiles.push(shortURL);
			}
		}

		// If in GitHub mode, update the selected count and button colours
		if (top.ICEcoder.githubDiff) {
			top.get('githubNavSelectedCount').innerHTML	= "Selected: " + top.ICEcoder.selectedFiles.length;
			top.get('githubNavCommit').style.color		= top.ICEcoder.selectedFiles.length > 0 ? "#fff" : "#333";
			top.get('githubNavCommit').style.background	= top.ICEcoder.selectedFiles.length > 0 ? "#2187e7" : "#555";
			top.get('githubNavSelectedCount').style.color	= top.ICEcoder.selectedFiles.length > 0 ? "#fff" : "#333";
			top.get('githubNavPull').style.color		= top.ICEcoder.selectedFiles.length > 0 ? "#fff" : "#333";
			top.get('githubNavPull').style.background	= top.ICEcoder.selectedFiles.length > 0 ? "#2187e7" : "#555";
		}

		// Adjust the file & replace select dropdown values accordingly
		document.findAndReplace.target[2].innerHTML = !top.ICEcoder.selectedFiles[0] ? top.t['all files'] : top.t['selected files'];
		document.findAndReplace.target[3].innerHTML = !top.ICEcoder.selectedFiles[0] ? top.t['all filenames'] : top.t['selected filenames'];

		// Hide the file menu incase it's showing
		top.ICEcoder.hideFileMenu();
	},

	// Deselect all files
	deselectAllFiles: function() {
		var tgtFile;

		for (var i=0;i<top.ICEcoder.selectedFiles.length;i++) {
			tgtFile = top.ICEcoder.filesFrame.contentWindow.document.getElementById(top.ICEcoder.selectedFiles[i]);
			ICEcoder.selectDeselectFile('deselect',tgtFile);
		}
		top.ICEcoder.selectedFiles.length = 0;
	},

	// Select or deselect file
	selectDeselectFile: function(action,file) {
		var isOpen;

		if (file) {
			isOpen = top.ICEcoder.openFiles.indexOf(file.id.replace(/\|/g,"/")) > -1 ? true : false;

			if (top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1] == file.id.replace(/\|/g,"/")) {
				file.style.backgroundColor = action=="select"
				? top.ICEcoder.tabBGselected : top.ICEcoder.tabBGcurrent;
			} else {
				file.style.backgroundColor = action=="select"
				? top.ICEcoder.tabBGselected : file.style.backgroundColor = isOpen
				? top.ICEcoder.tabBGopen : top.ICEcoder.tabBGnormal;
			}
			file.style.color = action=="select" ? top.ICEcoder.tabFGselected : top.ICEcoder.tabFGnormalFile;
		}
	},

	// Box select files
	boxSelect: function(evt, mouseAction) {
		var fmDragBox, positive;

		fmDragBox = top.ICEcoder.filesFrame.contentWindow.document.getElementById('fmDragBox');

		// On mouse down, set start X & Y and reset first and last items in box area select
		if (mouseAction == "down") {
			top.ICEcoder.fmDragBoxStartX = top.ICEcoder.mouseX;
			top.ICEcoder.fmDragBoxStartY = top.ICEcoder.mouseY;
			top.ICEcoder.fmDragSelectFirst = "";
			top.ICEcoder.fmDragSelectLast = "";
		}

		// On mouse drag, state we're dragging, set the box size and position properties and select files
		if(top.ICEcoder.mouseDown && mouseAction == "drag") {
			top.ICEcoder.fmDraggedBox = true;

			// Handle X-axis properties
			positive = top.ICEcoder.mouseX-top.ICEcoder.fmDragBoxStartX > 0;
			fmDragBox.style.left = (positive ? top.ICEcoder.fmDragBoxStartX : top.ICEcoder.mouseX) + "px";
			fmDragBox.style.width = Math.abs(top.ICEcoder.mouseX-top.ICEcoder.fmDragBoxStartX) + "px";

			// Handle Y-axis properties
			positive = top.ICEcoder.mouseY-top.ICEcoder.fmDragBoxStartY > 0;
			fmDragBox.style.top = (positive ? top.ICEcoder.fmDragBoxStartY-70 : top.ICEcoder.mouseY-70) + "px";
			fmDragBox.style.height = Math.abs(top.ICEcoder.mouseY-top.ICEcoder.fmDragBoxStartY) + "px";

			// Select the files
			if (top.ICEcoder.thisFileFolderLink != "") {
				if (top.ICEcoder.fmDragSelectFirst == "") {
					top.ICEcoder.fmDragSelectFirst = top.ICEcoder.thisFileFolderLink;
					top.ICEcoder.overFileFolder(top.ICEcoder.thisFileFolderLink.indexOf('.') > 0 ? 'file' : 'folder', top.ICEcoder.fmDragSelectFirst);
					top.ICEcoder.selectFileFolder(evt);
				} else {
					top.ICEcoder.fmDragSelectLast = top.ICEcoder.thisFileFolderLink;
					top.ICEcoder.overFileFolder(top.ICEcoder.thisFileFolderLink.indexOf('.') > 0 ? 'file' : 'folder', top.ICEcoder.fmDragSelectLast);
					top.ICEcoder.selectFileFolder(evt,false,'shiftSim');
				}
			}
		}

		// On mouse up, set width and height to 0 to hide
		if(mouseAction == "up") {
			fmDragBox.style.width = 0;
			fmDragBox.style.height = 0;
		}
	},

	// Create a new file (start & instant save)
	newFile: function() {
		top.ICEcoder.newTab('alsoSave');
	},

	// Create a new folder
	newFolder: function() {
		var shortURL, newFolder;

		shortURL = top.ICEcoder.selectedFiles[top.ICEcoder.selectedFiles.length-1].replace(/\|/g,"/");
		newFolder = top.ICEcoder.getInput('Enter new folder name at '+shortURL,'');
		if (newFolder) {
			newFolder = (shortURL + "/" + newFolder).replace(/\/\//,"/");
			top.ICEcoder.serverQueue("add","lib/file-control-xhr.php?action=newFolder&csrf="+top.ICEcoder.csrf,newFolder.replace(/\//g,"|"));
			top.ICEcoder.serverMessage('<b>'+top.t['Creating Folder']+'</b><br>'+newFolder);
		}
	},

	// Provide a path and line ref and we return the seperate pieces
	returnFileAndLine: function(fileLink) {
		var line = 1;
		var re = /^([^ ]*)\s+(on\s+)?(line\s+)?(\d+)/;
		var reMatch = re.exec(fileLink);

		if (null !== reMatch) {
			line = reMatch[4];
			fileLink = reMatch[1];
		} else if (fileLink.indexOf('://') > 0){
			if (fileLink.lastIndexOf(':') !== fileLink.indexOf('://')) {
				line = fileLink.split(':')[2];
				fileLink = fileLink.substr(0,fileLink.lastIndexOf(":"));
			}
		} else if (fileLink.indexOf(':') > 0){
			line = fileLink.split(':')[1];
			fileLink = fileLink.split(':')[0];
		}
		if ((fileLink.indexOf('(') > 0) && (fileLink.indexOf(')') > 0)){
			line = fileLink.split('(')[1].split(')')[0];
			fileLink = fileLink.split('(')[0];
		}
		return [fileLink,line];
	},

	// Open a file
	openFile: function(fileLink) {
		var flSplit, line, shortURL, canOpenFile;

		if ("undefined" != typeof fileLink) {
			flSplit = top.ICEcoder.returnFileAndLine(fileLink);
			fileLink = flSplit[0];
			line     = flSplit[1];
		}

		if (fileLink) {
			top.ICEcoder.thisFileFolderLink=fileLink;
			top.ICEcoder.thisFileFolderType="file";
		}
		if (top.ICEcoder.thisFileFolderLink != "/[NEW]" && top.ICEcoder.isOpen(top.ICEcoder.thisFileFolderLink)!==false) {
			top.ICEcoder.switchTab(top.ICEcoder.isOpen(top.ICEcoder.thisFileFolderLink)+1);
			if (line > 1){
				top.ICEcoder.goToLine(line);
			}
		} else if (top.ICEcoder.thisFileFolderLink!="" && top.ICEcoder.thisFileFolderType=="file") {

			// work out a shortened URL for the file
			shortURL = top.ICEcoder.thisFileFolderLink.replace(/\|/g,"/");
			// No reason why we can't open a file (so far)
			canOpenFile = true;
			// Limit to 100 files open at a time
			if (top.ICEcoder.openFiles.length>=100) {
				top.ICEcoder.message(top.t['Sorry you can...']);
				canOpenFile = false;
			}

			// if we're still OK to open it...
			if (canOpenFile) {
				top.ICEcoder.shortURL = shortURL;

				if (shortURL!="/[NEW]") {
					top.ICEcoder.thisFileFolderLink = top.ICEcoder.thisFileFolderLink.replace(/\//g,"|");
					top.ICEcoder.serverQueue("add","lib/file-control.php?action=load&file="+top.ICEcoder.thisFileFolderLink+"&csrf="+top.ICEcoder.csrf+"&lineNumber="+line);
					top.ICEcoder.serverMessage('<b>'+top.t['Opening File']+'</b><br>'+top.ICEcoder.shortURL);
				} else {
					top.ICEcoder.createNewTab('new');
				}
				top.ICEcoder.fMIconVis('fMView',1);
			}
		}
	},

	// Open selected files
	openFilesFromList: function(fileList) {
		for (var i=0;i<fileList.length;i++) {
			top.ICEcoder.thisFileFolderLink=fileList[i].replace('|','/');
			top.ICEcoder.thisFileFolderType='file';
			top.ICEcoder.openFile();
		}
	},

	// Show file prompt to open file
	openPrompt: function() {
		var fileLink;

		if(fileLink = top.ICEcoder.getInput(top.t['Enter relative file...'],'')) {
			fileLink.indexOf("://")>-1
			? top.ICEcoder.getRemoteFile(fileLink)
			: top.ICEcoder.openFile(fileLink);
		}
	},

	// Get remote file contents
	getRemoteFile: function(remoteFile) {
		var flSplit, line;

		if ("undefined" != typeof remoteFile) {
			flSplit = top.ICEcoder.returnFileAndLine(remoteFile);
			remoteFile = flSplit[0];
			line       = flSplit[1];
		}

		top.ICEcoder.serverQueue("add","lib/file-control-xhr.php?action=getRemoteFile&csrf="+top.ICEcoder.csrf+"&lineNumber="+line,remoteFile);
		top.ICEcoder.serverMessage('<b>'+top.t['Getting']+'</b><br>'+remoteFile);
	},

	// Save a file
	saveFile: function(saveAs) {
		var saveType, filePath, pathPrefix;

		saveType = saveAs ? "saveAs" : "save";
		filePath = ICEcoder.openFiles[ICEcoder.selectedTab-1].replace(top.iceRoot,"").replace(/\//g,"|");
		if (filePath=="|[NEW]" && top.ICEcoder.selectedFiles.length>0) {
			pathPrefix = top.ICEcoder.selectedFiles[0];
			filePath = pathPrefix.lastIndexOf(".") == -1 || pathPrefix.lastIndexOf(".") < pathPrefix.lastIndexOf("|")
			? pathPrefix+filePath
			: "|[NEW]";
		}
		filePath = filePath.replace("||","|");
		top.ICEcoder.serverQueue("add","lib/file-control-xhr.php?action=save&fileMDT="+ICEcoder.openFileMDTs[ICEcoder.selectedTab-1]+"&fileVersion="+ICEcoder.openFileVersions[ICEcoder.selectedTab-1]+"&saveType="+saveType+"&csrf="+top.ICEcoder.csrf,filePath);
		top.ICEcoder.serverMessage('<b>'+top.t['Saving']+'</b><br>'+ICEcoder.openFiles[ICEcoder.selectedTab-1].replace(top.iceRoot,""));
	},

	// Prompt a rename dialog
	renameFile: function(oldName,newName) {
		var shortURL, fileName, i;

		if (!oldName) {
			shortURL = top.ICEcoder.selectedFiles[top.ICEcoder.selectedFiles.length-1].replace(/\|/g,"/");
			oldName = top.ICEcoder.selectedFiles[top.ICEcoder.selectedFiles.length-1].replace(/\|/g,"/");
		} else {
			shortURL = oldName.replace(/\|/g,"/");
		}
		if (!newName) {
			newName = top.ICEcoder.getInput(top.t['Please enter the...'],shortURL);
		}
		if (newName) {
			i = top.ICEcoder.openFiles.indexOf(shortURL.replace(/\|/g,"/"));
			if(i>-1) {
				// rename array item and the tab
				top.ICEcoder.openFiles[i] = newName;
				closeTabLink = '<a nohref onClick="top.ICEcoder.closeTab(parseInt(this.parentNode.id.slice(3),10))"><img src="images/nav-close.gif" class="closeTab" onMouseOver="prevBG=this.style.backgroundColor;this.style.backgroundColor=\'#333\'; top.ICEcoder.overCloseLink=true" onMouseOut="this.style.backgroundColor=prevBG; top.ICEcoder.overCloseLink=false"></a>';
				fileName = top.ICEcoder.openFiles[i];
				top.get('tab'+(i+1)).innerHTML = closeTabLink + " " + fileName.slice(fileName.lastIndexOf("/")).replace(/\//,"");
				top.get('tab'+(i+1)).title = newName;
			}
		top.ICEcoder.serverQueue("add","lib/file-control-xhr.php?action=rename&oldFileName="+oldName.replace(/\|/g,"/")+"&csrf="+top.ICEcoder.csrf,newName);
		top.ICEcoder.serverMessage('<b>'+top.t['Renaming to']+'</b><br>'+newName);

		top.ICEcoder.setPreviousFiles();
		}
	},

	// Move a file from old location to new
	moveFile: function(oldName,newName) {
		var fileName, i;

		if (newName) {
			i = top.ICEcoder.openFiles.indexOf(oldName.replace(/\|/g,"/"));
			if(i>-1) {
				// rename array item and the tab
				top.ICEcoder.openFiles[i] = newName;
				closeTabLink = '<a nohref onClick="top.ICEcoder.closeTab(parseInt(this.parentNode.id.slice(3),10))"><img src="images/nav-close.gif" class="closeTab" onMouseOver="prevBG=this.style.backgroundColor;this.style.backgroundColor=\'#333\'; top.ICEcoder.overCloseLink=true" onMouseOut="this.style.backgroundColor=prevBG; top.ICEcoder.overCloseLink=false"></a>';
				fileName = top.ICEcoder.openFiles[i];
				top.get('tab'+(i+1)).innerHTML = closeTabLink + " " + fileName.slice(fileName.lastIndexOf("/")).replace(/\//,"");
				top.get('tab'+(i+1)).title = newName;
			}
			if (top.ICEcoder.ask("Are you sure you want to move file " + oldName + " to " + newName + " ?")){
				top.ICEcoder.serverQueue("add","lib/file-control-xhr.php?action=move&oldFileName="+oldName.replace(/\//g,"|")+"&csrf="+top.ICEcoder.csrf,newName.replace(/\//g,"|"));
				top.ICEcoder.serverMessage('<b>'+top.t['Moving to']+'</b><br>'+newName);
			}

			top.ICEcoder.setPreviousFiles();
		}
	},

	// Delete a file
	deleteFiles: function(fileList) {
		var tgtFiles, tgtListDisplay;

		tgtFiles = fileList ? fileList : top.ICEcoder.selectedFiles;
		tgtListDisplay = tgtFiles.toString().replace(/\|/g,"/").replace(/,/g,"\n");
		if (tgtFiles.length>0 && top.ICEcoder.ask('Delete:\n\n'+tgtListDisplay+'?')) {
			top.ICEcoder.serverQueue("add","lib/file-control-xhr.php?action=delete&&csrf="+top.ICEcoder.csrf,tgtFiles.join(";"));
			top.ICEcoder.serverMessage('<b>'+top.t['Deleting File']+'</b><br>'+tgtListDisplay);
		};
	},

	// Copy files
	copyFiles: function(fileList,dontShowPaste,dontHide) {
		top.ICEcoder.copiedFiles = [];
		for (var i=0; i<fileList.length; i++) {
			top.ICEcoder.copiedFiles[i] = fileList[i];
		}
		if (!dontShowPaste) {
			top.get('fmMenuPasteOption').style.display = "block";
		}
		if (!dontHide) {
			top.ICEcoder.hideFileMenu();
		}
	},

	// Paste files
	pasteFiles: function(location) {
		if (top.ICEcoder.copiedFiles) {
			for (var i=0; i<top.ICEcoder.copiedFiles.length; i++) {
				if (top.ICEcoder.copiedFiles[i]!="|") {
					top.ICEcoder.serverQueue("add","lib/file-control-xhr.php?action=paste&location="+location+"&csrf="+top.ICEcoder.csrf,top.ICEcoder.copiedFiles[i]);
					top.ICEcoder.serverMessage('<b>'+top.t['Pasting File']+'</b><br>'+top.ICEcoder.copiedFiles[i].toString().replace(/\|/g,"/").replace(/,/g,"\n"));
				} else {
					top.ICEcoder.message(top.t['Sorry cannot paste...']);
				}
			}
		} else {
			top.ICEcoder.message(top.t['Nothing to paste...']);
		}
	},

	// Duplicate (copy & paste) files
	duplicateFiles: function(fileList) {
		var copiedFiles, location;

		// Take a snapshot of copied files
		if (top.ICEcoder.copiedFiles) {
			copiedFiles = top.ICEcoder.copiedFiles;
		}

		top.ICEcoder.copyFiles(fileList,'dontShowPaste','dontHide');
		location = fileList[0].substr(0,fileList[0].lastIndexOf("|"));
		top.ICEcoder.pasteFiles(location);

		// Restore copied files back to the snapshot
		if ("undefined" != typeof copiedFiles) {
			top.ICEcoder.copiedFiles = copiedFiles;
		}
	},

	// Upload file(s) - select & submit
	uploadFilesSelect: function(location) {
		top.get('uploadDir').value = location;
		top.get("fileInput").click();
	},
	uploadFilesSubmit: function(obj) {
		if (top.get('fileInput').value!="") {
			top.ICEcoder.showHide('show',top.get('loadingMask'));
			top.get('uploadFilesForm').submit();
			event.preventDefault();
		}
	},

	// Show/hide file manager nav options
	showHideFileNav: function(vis,elem) {
		var options = ["optionsFile","optionsEdit","optionsSource","optionsHelp"];
		if (vis=="hide") {
			fileNavInt = setTimeout(function() {
				for (var i=0; i<options.length; i++) {
					top.ICEcoder.showHide('hide',top.get(options[i]));
					top.get(options[i]+'Nav').style.color = '';
				}
			},150);
		} else {
			for (var i=0; i<options.length; i++) {
				top.ICEcoder.showHide('hide',top.get(options[i]));
				top.get(options[i]+'Nav').style.color = '';
			}
		}
		get('fileOptions').style.opacity = 0;
		if (vis=="show") {
			if ("undefined" != typeof fileNavInt) {
				clearTimeout(fileNavInt);
			}
			top.ICEcoder.showHide(vis,top.get(elem));
			top.get(elem+'Nav').style.color = '#fff';
			get('fileOptions').style.opacity = 1;
		}
	},

	// Is a specified path a folder? (Note: path is string encoded path with / replaced with |)
	isPathFolder: function(path){
		// let's enumerate all folders to find whether clicked file is a folder or not
		var dir = top.ICEcoder.filesFrame.contentDocument.getElementsByClassName("pft-directory");
		var thisFileId = top.ICEcoder.selectedFiles[0];
		var liNode, aNode, spanNode; 
		for (var i = 0 ; i < dir.length; i++){
			liNode = dir[i];
			if ("underfined" != typeof liNode){
				aNode = liNode.childNodes[0];
				if ("undefined" != typeof aNode){
					spanNode = aNode.childNodes[1];
					if ("undefined" != typeof spanNode){
						if (thisFileId === spanNode.getAttribute('id')){
							// It's a folder
							return true;
						}
					}
				}
			}
		}
		// It's a file
		return false;
	},

	// Check for existance of a file/dir
	checkExists: function(path) {
		var xhr, statusObj, timeStart;

		path = path.replace(/\|/g,"/");
		// Clear any prefixed iceRoot from path
		if (path.indexOf(top.iceRoot) === 0) {
			path = path.replace(top.iceRoot,"");
		}

		// Start a seperate XHR call. We run seperately rather than add into the serverQueue because we may need to run
		// immediately, eg need to if a file/dir exists mid flow in 'Save As' function, so can't go into queue
		xhr = top.ICEcoder.xhrObj();
		xhr.onreadystatechange=function() {
			if (xhr.readyState==4) {
				// Parse the response as a JSON object
				statusObj = JSON.parse(xhr.responseText);

				// Set the action end time and time taken in JSON object
				statusObj.action.timeEnd = new Date().getTime();
				statusObj.action.timeTaken = statusObj.action.timeEnd - statusObj.action.timeStart;

				// User wanted raw (or both) output of the response?
				if (["raw","both"].indexOf(top.ICEcoder.fileDirResOutput) >= 0) {
					console.log(xhr.responseText);
				}
				// User wanted object (or both) output of the response?
				if (["object","both"].indexOf(top.ICEcoder.fileDirResOutput) >= 0) {
					console.log(statusObj);
				}

				// Also store the statusObj
				top.ICEcoder.lastFileDirCheckStatusObj = statusObj;

				// OK reponse? If error, show that, otherwise do whatever we're required to do next
				if (xhr.status==200) {
					if (statusObj.status.error) {
						top.ICEcoder.message(statusObj.status.errorMsg);
						console.log("ICEcoder error info for your request...");
						console.log(statusObj);
						top.ICEcoder.serverMessage();
						top.ICEcoder.serverQueue('del',0);
					} else {
						eval(statusObj.action.doNext);
					}
				// Some other response? Display a message about that
				} else {
					top.ICEcoder.message(top.t['Sorry there was...']);
					console.log("ICEcoder error info for your request...");
					console.log(statusObj);
					top.ICEcoder.serverMessage();
					top.ICEcoder.serverQueue('del',0);
				}
			}
		};
		xhr.open("POST","lib/file-control-xhr.php?action=checkExists&csrf="+top.ICEcoder.csrf,true);
		xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		timeStart = new Date().getTime();
		xhr.send('timeStart='+timeStart+'&file='+path);
	},

	// Show menu on right clicking in file manager
	showMenu: function(evt) {
		var menuType, menuHeight, winH, fmYPos;

		if (	top.ICEcoder.selectedFiles.length == 0 ||
			top.ICEcoder.selectedFiles.indexOf(top.ICEcoder.selectedFiles[top.ICEcoder.selectedFiles.length-1].replace(/\//g,"|")) == -1) {
			top.ICEcoder.selectFileFolder(evt);
		}

		menuHeight = 124+5; // general options height in px plus 5px space
		winH = window.innerHeight;
		if ("undefined" != typeof top.ICEcoder.thisFileFolderLink && top.ICEcoder.thisFileFolderLink!="") {
			menuType = this.isPathFolder(top.ICEcoder.selectedFiles[0]) ? "folder" : "file";
			top.get('folderMenuItems').style.display = menuType == "folder" && top.ICEcoder.selectedFiles.length == 1 ? "block" : "none";
			if (menuType == "folder" && top.ICEcoder.selectedFiles.length == 1) {
				menuHeight += 20+20+1+23+1+2; // new file, new folder, hr, upload files(s), hr, padding
				if (top.get('fmMenuPasteOption').style.display == "block") {
					menuHeight += 19;
				}
			}
			top.get('singleFileMenuItems').style.display = top.ICEcoder.selectedFiles.length > 1 ? "none" : "block";
			if (top.ICEcoder.selectedFiles.length == 1) {
				menuHeight += 43;
			}
			top.get('fileMenu').style.display = "inline-block";
			setTimeout(function() {top.get('fileMenu').style.opacity = "1"},4);
			top.get('fileMenu').style.left = (top.ICEcoder.mouseX+20) + "px";
			fmYPos = top.ICEcoder.mouseY-top.ICEcoder.filesFrame.contentWindow.document.body.scrollTop-10;
			if (fmYPos+menuHeight > winH) {
				fmYPos -= (fmYPos+menuHeight-winH);
			}
			top.get('fileMenu').style.top = fmYPos + "px";
		}
		return false;
	},

	// Continue to show the file manager
	showFileMenu: function() {
		top.get('fileMenu').style.display='inline-block';
		setTimeout(function() {top.get('fileMenu').style.opacity = "1"},4);
	},

	// Hide the file manager
	hideFileMenu: function() {
		top.get('fileMenu').style.display='none';
		top.get('fileMenu').style.opacity = "0";
	},

	// Update the file manager tree list on demand
	updateFileManagerList: function(action,location,file,perms,oldName,uploaded,fileOrFolder) {
		var actionElemType, cssStyle, perms, targetElem, locNest, newText, innerLI, permColors, newUL, newLI, elemType, nameLI, shortURL, newMouseOver;		

		// Adding files
		if (action=="add" && !top.get('filesFrame').contentWindow.document.getElementById(location.replace(top.iceRoot,"").replace(/\/$/, "").replace(/\//g,"|")+"|"+file)) {
			// Is this is a file or folder and based on that, set the CSS styling & link
			actionElemType = fileOrFolder;
			cssStyle = actionElemType=="file" ? "pft-file ext-" + file.substr(file.indexOf(".")+1) : "pft-directory";
			perms = actionElemType=="file" ? top.ICEcoder.newFilePerms : top.ICEcoder.newDirPerms;

			// Identify our target element & the first child element in it's location
			if (!location) {location="/"}
			location = location.replace(top.iceRoot,"/");
			location = location.replace("//","/");
			targetElem = top.get('filesFrame').contentWindow.document.getElementById(location.replace(/\//g,"|"));
			locNest = targetElem.parentNode.parentNode.nextSibling;
			newText = document.createTextNode("\n");
			permColors = perms == 777 ? 'background: #800; color: #eee' : 'color: #888';
			innerLI = '<a nohref title="'+location.replace(/\/$/, "")+"/"+file+'" onMouseOver="parentNode.draggable=true;top.ICEcoder.overFileFolder(\''+actionElemType+'\',this.childNodes[1].id)" onMouseOut="parentNode.draggable=false;top.ICEcoder.overFileFolder(\''+actionElemType+'\',\'\')" '+

					(actionElemType == "folder" ? 'ondragover="if(parentNode.nextSibling && parentNode.nextSibling.tagName != \'UL\' && top.ICEcoder.thisFileFolderLink != this.childNodes[1].id) {top.ICEcoder.openCloseDir(this,true);}"':'')+

					' onClick="if(!event.ctrlKey && !top.ICEcoder.cmdKey) {'+

					(actionElemType == "folder" ? 'top.ICEcoder.openCloseDir(this,'+(actionElemType=="folder" ? 'true' : 'false')+');':'')+

					' if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {top.ICEcoder.openFile()}}" style="position: relative; left:-22px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id="'+location.replace(/\/$/, "").replace(/\//g,"|")+"|"+file+'">'+file+'</span> <span style="'+permColors+'; font-size: 8px" id="'+location.replace(/\/$/, "").replace(/\//g,"|")+"|"+file+'_perms">'+perms+'</span></a>';

			// If we don't have a locNest or at least 3 DOM items in there, it's an empty folder
			if(!locNest || locNest.childNodes.length<3) {
				// We now need to begin a new UL list
				newUL = document.createElement("ul");
				locNest = targetElem.parentNode.parentNode;
				locNest.parentNode.insertBefore(newUL,locNest.nextSibling);

				// Now we can add the first LI for this file/folder we're adding
				newLI = document.createElement("li");
				newLI.className = cssStyle;
				newLI.draggable = false;
				newLI.ondrag = function(event) {top.ICEcoder.draggingWithKeyTest(event);if(top.ICEcoder.getcMInstance()){top.ICEcoder.editorFocusInstance.indexOf('diff') == -1 ? top.ICEcoder.getcMInstance().focus() : top.ICEcoder.getcMdiffInstance().focus()}};
				newLI.ondragend = function() {top.ICEcoder.dropFile(this)};
				newLI.innerHTML = innerLI
				locNest.nextSibling.appendChild(newLI);
				locNest.nextSibling.appendChild(newText);

			// There are items in that location, so add our new item in the right position
			} else {
				for (var i=0;i<locNest.childNodes.length;i++) {
					if (locNest.childNodes[i].className) {
						// Identify if the item we're considering is a file or folder
						elemType = locNest.childNodes[i].className.indexOf('directory')>0 ? "folder" : "file";

						// Get the name of the item
						nameLI = locNest.childNodes[i].getElementsByTagName('span')[0].innerHTML;

						// If it's of the same type & the name is greater, or we're adding a folder and it's a file or if we're at the end of the list
						if ((elemType==actionElemType && nameLI > file) || (actionElemType=="folder" && elemType=="file") || i==locNest.childNodes.length-1) {
							newLI = document.createElement("li");
							newLI.className = cssStyle;
							newLI.draggable = false;
							newLI.ondrag = function(event) {top.ICEcoder.draggingWithKeyTest(event);if(top.ICEcoder.getcMInstance()){top.ICEcoder.editorFocusInstance.indexOf('diff') == -1 ? top.ICEcoder.getcMInstance().focus() : top.ICEcoder.getcMdiffInstance().focus()}};
							newLI.ondragend = function() {top.ICEcoder.dropFile(this)};
							newLI.innerHTML = innerLI;
							// Append or insert depending on which of the above if statements is true
							if (i==locNest.childNodes.length-1) {
								locNest.appendChild(newLI);
								locNest.appendChild(newText);
							} else {
								locNest.insertBefore(newLI,locNest.childNodes[i]);
								locNest.insertBefore(newText,locNest.childNodes[i+1]);
							}
							break;
						}
					}
				}
			}
			// If we added a new file, we've saved it under a new filename, so set that
			if (actionElemType=="file" && !uploaded) {
				top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1]=location+file;
			}
		}

		// Renaming files
		if (action=="rename") {
			// Get short URL of our right clicked file and get target elem based on this
			shortURL = oldName.replace(/\//g,"|");
			targetElem = top.get('filesFrame').contentWindow.document.getElementById(shortURL);
			// Set the name to be as per our new file/folder name
			targetElem.innerHTML = file;
			// Update the ID of the target & set a new title and perms ID
			targetElem.id = location.replace(/\//g,"|") + "|" + file;
			targetElem.parentNode.title = targetElem.id.replace(/\|/g,"/");
			targetElemPerms = top.get('filesFrame').contentWindow.document.getElementById(shortURL+"_perms");
			targetElemPerms.id = location.replace(/\//g,"|") + "|" + file + "_perms";
			// Finally, rename also within any children
			top.ICEcoder.renameInChildren(targetElem, oldName, location, file);
		}

		// Moving files
		if (action=="move") {
			top.ICEcoder.updateFileManagerList("add",location,file,false,false,false,fileOrFolder);
			top.ICEcoder.updateFileManagerList("delete",oldName.substr(0,oldName.lastIndexOf("/")),file);
		}

		// Chmod on files
		if (action=="chmod") {
			// Get short URL for our file and get our target elem based on this
			shortURL = top.ICEcoder.selectedFiles[top.ICEcoder.selectedFiles.length-1].replace(/\|/g,"/");
			targetElem = top.get('filesFrame').contentWindow.document.getElementById(shortURL.replace(/\//g,"|")+"_perms");
			// Set the color for the perms
			targetElem.style.background = perms == 777 ? '#800' : 'none';
			targetElem.style.color = perms == 777 ? '#eee' : '#888';
			// Set the new perms
			targetElem.innerHTML = perms;
			}

		// Deleting files
		if (action=="delete") {
		if (!location) {location=""}
			location = location.replace(top.iceRoot,"/");
			location = location.replace("//","/");
			location = location.replace(/\/$/, "").replace(/\//g,"|");
			targetElem = (location +"|"+file).replace("||","|");
			targetElem = top.get('filesFrame').contentWindow.document.getElementById(targetElem).parentNode.parentNode;
			top.ICEcoder.openCloseDir(targetElem.childNodes[0],false);
			targetElem.parentNode.removeChild(targetElem);
		}
	},

	// Rename node attributes within any renamed dirs recursively
	renameInChildren: function(elem, oldName, location, file) {
		var innerItems, targetElem, targetElemPerms;

		// If our elem has a sibling and it's a UL, we renamed a dir
		if(elem.parentNode.parentNode.nextSibling && elem.parentNode.parentNode.nextSibling.nodeName == "UL") {
			innerItems = elem.parentNode.parentNode.nextSibling;

			// For each one of the children in the UL, if it's a LI (may be a file or dir)
			for (var i=0; i<innerItems.childNodes.length; i++) {
				if (innerItems.childNodes[i].nodeName == "LI") {
					// Get the span elem inside as our targetElem
					targetElem = innerItems.childNodes[i].childNodes[0].childNodes[1];
					// Update the ID of the target & set a new title
					targetElem.id = targetElem.id.replace(oldName.replace(/\//g,"|"),location.replace(/\//g,"|")+"|"+file);
					targetElem.parentNode.title = targetElem.id.replace(/\|/g,"/");
					// Also update the perms ID
					targetElemPerms = top.get('filesFrame').contentWindow.document.getElementById(targetElem.id).nextSibling.nextSibling;
					targetElemPerms.id = targetElem.id + "_perms";
					// Finally, test this node for ULs next to it also, incase it's a dir
					top.ICEcoder.renameInChildren(targetElem, oldName, location, file);
				}
			}
		}
	},

	// Refresh file manager
	refreshFileManager: function() {
		top.ICEcoder.showHide('show',top.get('loadingMask'));
		top.ICEcoder.filesFrame.contentWindow.location.reload(true);
		top.ICEcoder.filesFrame.style.opacity="0";
		top.ICEcoder.filesFrame.onload = function() {
			top.ICEcoder.filesFrame.style.opacity="1";
			top.ICEcoder.showHide('hide',top.get('loadingMask'));
		}
	},

	// Detect CTRL/Cmd key whilst dragging files
	draggingWithKeyTest: function(evt) {
		var key;

		key = evt.keyCode ? evt.keyCode : evt.which ? evt.which : evt.charCode;

		// Mac command key handling (224 = Moz, 91/93 = Webkit Left/Right Apple)
		if (key==224 || key==91 || key==93) {
			top.ICEcoder.cmdKey = true;
		}

		top.ICEcoder.draggingWithKey = evt.ctrlKey||top.ICEcoder.cmdKey ? "CTRL" : false;
	},

	// On dropping a file, do something
	dropFile: function(elem) {
		var filePath, tgtPath;

		filePath = elem.childNodes[0].childNodes[1].id.replace(/\|/g,"/");
		fileName = filePath.substr(filePath.lastIndexOf("/")+1);
		if (top.ICEcoder.area=='editor') {
			top.ICEcoder.pasteURL(filePath);
		};
		if (top.ICEcoder.area=='files') {
			setTimeout(function() {
				tgtPath = ICEcoder.thisFileFolderType == "folder" ? ICEcoder.thisFileFolderLink : ICEcoder.thisFileFolderLink.substr(0,ICEcoder.thisFileFolderLink.lastIndexOf("|"));
				if(top.ICEcoder.draggingWithKey == "CTRL") {
					top.ICEcoder.copyFiles(top.ICEcoder.selectedFiles);
					top.ICEcoder.pasteFiles(tgtPath);
				} else {
					top.ICEcoder.moveFile(filePath,tgtPath.replace(/\|/g,"/") + "/" + fileName);
				}
			},4);
		};
		top.ICEcoder.mouseDown=false;
	},

// ==============
// FIND & REPLACE
// ==============

	// Update find & replace options based on user selection
	findReplaceOptions: function() {
		top.get('rText').style.display =
		top.get('replace').style.display =
		top.get('rTarget').style.display =
		document.findAndReplace.connector.value==top.t['and']
			? "inline-block" : "none";
	},

	// Find & replace text according to user selections
	findReplace: function(findString,resultsOnly,buttonClick,isCancel,findPrevious) {
		var find, replace, results, cM, cMdiff, thisCM, content, cursor, avgBlockH, addPadding, rBlocks, blockColor, replaceQS, targetQS, filesQS;

		if (isCancel){
			// Deselect by setting value to itself, then focus on editor
			top.get('find').value = top.get('find').value;
			top.ICEcoder.focus();
			return;
		}
		// Set findPrevious to false if not passed in
		if ("undefined" == typeof findPrevious) {
			findPrevious = false;
		}

		// Determine our find & replace strings and results display
		find		= findString.toLowerCase();
		replace		= top.get('replace').value;
		results		= top.get('results');

		// If we have something to find in currrent document
		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		if (thisCM && find.length>0 && document.findAndReplace.target.value==top.t['this document']) {
			content = thisCM.getValue().toLowerCase();
			// Find & replace the next instance, or all?
			if (document.findAndReplace.connector.value==top.t['and'] && buttonClick) {
				if (document.findAndReplace.replaceAction.value==top.t['replace'] && thisCM.getSelection().toLowerCase()==find) {
					thisCM.replaceSelection(replace,"around");
				} else if (document.findAndReplace.replaceAction.value==top.t['replace all']) {
					var rExp = new RegExp(find,"gi");
					thisCM.setValue(thisCM.getValue().replace(rExp,replace));
				}
			}

			// Get the content again, as it might of changed
			content = thisCM.getValue().toLowerCase();
			if (!top.ICEcoder.findMode||find!=top.ICEcoder.lastsearch) {
				ICEcoder.results = [];
				ICEcoder.resultsLines = [];

				for (var i=0;i<content.length;i++) {
					if (content.substr(i,find.length)==find && ICEcoder.results.indexOf(i) == -1) {
						ICEcoder.results.push(i);
						if (ICEcoder.resultsLines.indexOf(thisCM.posFromIndex(i).line+1)==-1) {
							ICEcoder.resultsLines.push(thisCM.posFromIndex(i).line+1);
						}
					}
				}

				// Also remember the last search term made
				ICEcoder.lastsearch = find;
			}

			// If we have results
			if (ICEcoder.results.length>0) {

				// Show results only
				if (resultsOnly) {
					results.innerHTML = ICEcoder.results.length + " results";
				// We need to take action instead
				} else {
					// Find our cursor position relative to results
					// Go next
					if (!findPrevious) {
						ICEcoder.findResult = 0;
						for (var i=0;i<ICEcoder.results.length;i++) {					
							if (ICEcoder.results[i]<thisCM.indexFromPos({"ch": thisCM.getCursor().ch+1, "line": thisCM.getCursor().line})) {
								ICEcoder.findResult++;
							}
						}
					// Go previous
					} else {
						if("undefined" == typeof ICEcoder.findResult) {
							ICEcoder.findResult = ICEcoder.results.length+1;
						} else {
							ICEcoder.findResult = ICEcoder.results.length;
						}
						for (var i=ICEcoder.results.length-1;i>=0;i--) {
							if (ICEcoder.results[i]>thisCM.indexFromPos({"ch": thisCM.getCursor().ch-1, "line": thisCM.getCursor().line})) {
								ICEcoder.findResult--;
							}
						}
					}

					// Loop round to start
					if (!findPrevious && ICEcoder.findResult>ICEcoder.results.length-1) {
						ICEcoder.findResult = 0
					}
					// Loop round to end
					if (findPrevious && ICEcoder.findResult==1) {
						ICEcoder.findResult = ICEcoder.results.length+1;
					}

					// Update results display
					results.innerHTML = "Highlighted result "+(ICEcoder.findResult+(!findPrevious ? 1 : -1))+" of "+ICEcoder.results.length+" results";

					// Now actually perform the movement in the editor
					if (!findPrevious) {
						// Find next instance
						cursor = thisCM.getSearchCursor(find,{"ch": thisCM.getCursor().ch+1, "line": thisCM.getCursor().line},true);
						cursor.findNext();
						// Find next from start of doc
						if (!cursor.from()) {
							cursor = thisCM.getSearchCursor(find,{line:0,ch:0},true);
							cursor.findNext();
						}
					} else {
						// Find previous instance
						cursor = thisCM.getSearchCursor(find,{"ch": thisCM.getCursor().ch-1, "line": thisCM.getCursor().line},true);
						cursor.findPrevious();
						// Find previous from end of doc
						if (!cursor.from()) {
							cursor = thisCM.getSearchCursor(find,{line:1000000,ch:1000000},true);
							cursor.findPrevious();
						}
					}
					// Finally, highlight our selection
					thisCM.setSelection(cursor.from(), cursor.to());
					top.ICEcoder.focus();
					top.ICEcoder.findMode = true;
				}

				// Display the find results bar
				// The avg block is either line height or fraction of space available
				avgBlockH = !top.ICEcoder.scrollBarVisible ? thisCM.defaultTextHeight() : parseInt(top.ICEcoder.content.style.height,10)/thisCM.lineCount();
				// Need to add padding if there's no scrollbar, so current line highlighting lines up with it
				addPadding = !top.ICEcoder.scrollBarVisible ? thisCM.heightAtLine(0) : 0;
				rBlocks = "";
				for (var i=1; i<=thisCM.lineCount(); i++) {
					blockColor = ICEcoder.resultsLines.indexOf(i)>-1 ? thisCM.getCursor().line+1 == i ? "#b00" : "#888" : "transparent"
					rBlocks += '<div style="position: absolute; display: block; width: 5px; height:'+avgBlockH+'px; background: '+blockColor+'; top: '+parseInt((avgBlockH*(i-1))+addPadding,10)+'px"></div>';
				}
				top.ICEcoder.content.contentWindow.document.getElementById('resultsBar').innerHTML = rBlocks;
				top.ICEcoder.content.contentWindow.document.getElementById('resultsBar').style.display = "inline-block";
				return true;

			} else {
				results.innerHTML = "No results";
				top.ICEcoder.content.contentWindow.document.getElementById('resultsBar').innerHTML = "";
				top.ICEcoder.content.contentWindow.document.getElementById('resultsBar').style.display = "none";
				return false;
			}
		} else {
			// Show the relevant multiple results popup
			if (find != "" && buttonClick) {
				replaceQS = "";
				targetQS = "";
				filesQS = "";
				if (document.findAndReplace.connector.value==top.t['and']) {
					replaceQS = "&replace="+replace;
				}
				if (document.findAndReplace.target.value.indexOf(top.t['file'])>=0) {
					targetQS = "&target="+document.findAndReplace.target.value.replace(/ /g,"-");
				}
				if (document.findAndReplace.target.value==top.t['selected files']) {
					filesQS = "&selectedFiles="+top.ICEcoder.selectedFiles.join(":");
				}
				find = find.replace(/\'/g, '\&#39;');
				find != encodeURIComponent(find) ? find = 'ICEcoder:'+encodeURIComponent(find) : find;
				top.ICEcoder.showHide('show',top.get('loadingMask'));
				top.get('mediaContainer').innerHTML = '<iframe src="lib/multiple-results.php?find='+find+replaceQS+targetQS+filesQS+'&csrf='+top.ICEcoder.csrf+'" class="whiteGlow" style="width: 700px; height: 500px"></iframe>';
			// We have nothing to search for, blank it all out
			} else {
				results.innerHTML = "No results";
				top.ICEcoder.content.contentWindow.document.getElementById('resultsBar').innerHTML = "";
				top.ICEcoder.content.contentWindow.document.getElementById('resultsBar').style.display = "none";
			}
		}
	},

	// Replace text in a file
	replaceInFile: function(fileRef,find,replace) {
		top.ICEcoder.serverQueue("add","lib/file-control-xhr.php?action=replaceText&find="+find+"&replace="+replace+"&csrf="+top.ICEcoder.csrf,fileRef.replace(/\//g,"|"));
		top.ICEcoder.serverMessage('<b>'+top.t['Replacing text in']+'</b><br>'+fileRef);
	},

// ==============
// INFO & DISPLAY
// ==============

	// Get the caret position
	getCaretPosition: function() {
		var cM, cMdiff, thisCM, line, ch, chPos;

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;

		line = thisCM.getCursor().line;
		ch = thisCM.getCursor().ch;
		chPos = 0;
		for (var i=0;i<line;i++) {
			chPos += thisCM.getLine(i).length+1;
		}
		ICEcoder.caretPos=(chPos+ch-1);
	},

	// Update the code type, line & character display
	updateCharDisplay: function() {
		var cM, cMdiff, thisCM;

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		ICEcoder.caretLocationType();
		ICEcoder.charDisplay.innerHTML = ICEcoder.caretLocType + ", Line: " + (thisCM.getCursor().line+1) + ", Char: " + thisCM.getCursor().ch;
	},

	// Update version display
	updateVersionsDisplay: function() {
		var versionsCount = top.ICEcoder.openFileVersions[ICEcoder.selectedTab-1];

		get('versionsDisplay').innerHTML = "undefined" != typeof versionsCount
			? top.ICEcoder.openFileVersions[ICEcoder.selectedTab-1] + " backup" +
				(versionsCount != 1 ? "s" : "")
			: "";
	},

	// Update the byte display
	updateByteDisplay: function() {
		var cM, cMdiff, thisCM;

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		ICEcoder.byteDisplay.innerHTML = thisCM.getValue().length.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + " bytes";
	},

	// Toggle the char/byte display
	showDisplay: function(show) {
		top.ICEcoder.byteDisplay.style.display = show == "byte" ? "inline-block" : "none";
		top.ICEcoder.charDisplay.style.display = show == "char" ? "inline-block" : "none";
	},

	// Show & hide target element
	showHide: function(doVis,elem) {
		elem.style.visibility = doVis=="show" ? 'visible' : 'hidden';
	},

	// Determine the CodeMirror instance we're using
	getcMInstance: function(tab) {
		return top.ICEcoder.content.contentWindow[
			// target specific tab
			!isNaN(tab)
			? 'cM'+ICEcoder.cMInstances[tab-1]
			// new tab or selected tab
			: tab=="new"||(tab!="new" && ICEcoder.openFiles.length>0)
			? 'cM'+ICEcoder.cMInstances[ICEcoder.selectedTab-1]
			// fallback to first tab
			: 'cM1'
		];
	},

	// Determine the CodeMirror instance we're using
	getcMdiffInstance: function(tab) {
		return top.ICEcoder.content.contentWindow[
			(// target specific tab
			!isNaN(tab)
			? 'cM'+ICEcoder.cMInstances[tab-1]
			// new tab or selected tab
			: tab=="new"||(tab!="new" && ICEcoder.openFiles.length>0)
			? 'cM'+ICEcoder.cMInstances[ICEcoder.selectedTab-1]
			// fallback to first tab
			: 'cM1')
			+ 'diff'
		];
	},

	// Get the mouse position
	getMouseXY: function(e,area) {
		var tempX, tempY;

		top.ICEcoder.mouseX = e.pageX ? e.pageX : e.clientX + document.body.scrollLeft;
		top.ICEcoder.mouseY = e.pageY ? e.pageY : e.clientY + document.body.scrollTop;

		top.ICEcoder.area = area;
		if (area!="top") {
			top.ICEcoder.mouseY += 25 + 45;
		}
		if (area=="editor") {
			top.ICEcoder.mouseX += top.ICEcoder.filesW;
		}
		top.ICEcoder.dragCursorTest();
		if (top.ICEcoder.mouseY>62) {top.ICEcoder.setTabWidths();};
	},

	// Test if we need to show a drag cursor or not
	dragCursorTest: function() {
		var diffX, winH, cursorName, zone;

		// Dragging tabs, started after dragging for 10px from origin
		diffX = top.ICEcoder.mouseX - top.ICEcoder.diffStartX;
		if (top.ICEcoder.draggingTab!==false && top.ICEcoder.diffStartX && (diffX <= -10 || diffX >= 10)) {
			if (top.ICEcoder.mouseX > parseInt(top.ICEcoder.files.style.width,10)) {
				top.ICEcoder.tabDragMouseX = top.ICEcoder.mouseX - parseInt(top.ICEcoder.files.style.width,10) - top.ICEcoder.tabDragMouseXStart;
				top.ICEcoder.tabDragMove();
			}
		}

		// Dragging file manager, possible within 7px of file manager edge
		if (top.ICEcoder.ready) {
			winH = window.innerHeight;
			if (!top.ICEcoder.mouseDown) {top.ICEcoder.draggingFilesW = false};

			cursorName = (!ICEcoder.draggingTab && ((top.ICEcoder.mouseX > top.ICEcoder.filesW-7 && top.ICEcoder.mouseX < top.ICEcoder.filesW+7) || top.ICEcoder.draggingFilesW))
				? "w-resize"
				: "auto";
			if (top.ICEcoder.content.contentWindow.document && top.ICEcoder.filesFrame.contentWindow) {
				top.document.body.style.cursor = cursorName;
				if (zone = top.ICEcoder.content.contentWindow.document.body)	{zone.style.cursor = cursorName};
				if (zone = top.ICEcoder.filesFrame.contentWindow.document.body)	{zone.style.cursor = cursorName};
			}
		}
	},

	// Show or hide a server message
	serverMessage: function(message) {
		var serverMessage;

		serverMessage =	top.get('serverMessage');
		if (message) {
			serverMessage.innerHTML = top.ICEcoder.xssClean(message).replace(/\&lt;b\&gt;/g,"<b>").replace(/\&lt;\/b\&gt;/g,"</b>").replace(/\&lt;br\&gt;/g,"<br>");
			serverMessage.style.left = "0";
		} else {
			setTimeout(function() {serverMessage.style.left = "2000px";},200);
		}
		serverMessage.style.opacity = message ? 1 : 0;
	},

	// Show a CSS color block next to our text cursor
	cssColorPreview: function() {
		var cM, cMdiff, thisCM, string, rx, match, oldBlock, newBlock;

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;

		if (thisCM) {
			string = thisCM.getLine(thisCM.getCursor().line);
			rx = /(#[\da-f]{3}(?:[\da-f]{3})?\b|\b(?:rgb|hsl)a?\([\s\d%,.-]+\)|\b[a-z]+\b)/gi;

			while((match = rx.exec(string)) && thisCM.getCursor().ch > match.index+match[0].length);

			oldBlock = top.get('content').contentWindow.document.getElementById('cssColor');
			if (oldBlock) {oldBlock.parentNode.removeChild(oldBlock)};
			if (top.ICEcoder.codeAssist && top.ICEcoder.caretLocType=="CSS") {
				newBlock = top.document.createElement("div");
				newBlock.id = "cssColor";
				newBlock.style.position = "absolute";
				newBlock.style.display = "block";
				newBlock.style.width = newBlock.style.height = "20px";
				newBlock.style.zIndex = "1000";
				newBlock.style.background = match ? match[0] : '';
				newBlock.style.cursor = "pointer";
				newBlock.onclick = function() {top.ICEcoder.showColorPicker(match[0])};
				if (newBlock.style.backgroundColor=="") {newBlock.style.display = "none"};
				top.get('header').appendChild(newBlock);
				thisCM.addWidget(thisCM.getCursor(), top.get('cssColor'), true);
			}
		}
	},

	// Show color picker
	showColorPicker: function(color) {
		top.get('blackMask').style.visibility = "visible";
		top.get('mediaContainer').innerHTML = 	'<div id="picker" class="picker"></div><br><br>'+
							'<input type="text" id="color" name="color" value="#000" class="colorValue">'+
							'<input type="button" onClick="top.ICEcoder.insertColorValue(top.get(\'color\').value)" value="insert &gt;" class="insertColorValue"><br>'+
							'<input type="text" id="colorRGB" name="colorRGB" value="rgb(0,0,0)" class="colorValue">'+
							'<input type="button" onClick="top.ICEcoder.insertColorValue(top.get(\'colorRGB\').value)" value="insert &gt;" class="insertColorValue">';
		farbtastic('picker','color');
		if (color) {
			top.get('picker').farbtastic.setColor(color);
		}
	},

	// Init the canvas by drawing the image and setting the floating containers background size (5x zoom)
	initCanvasImage: function (imgThis) {
		var canvas, img;

		canvas = top.get('canvasPicker').getContext('2d');

		img = new Image();
		img.crossOrigin = "Anonymous";
		img.src = imgThis.src;
		img.onload = function() {
			top.get('canvasPicker').width = imgThis.width;
			top.get('canvasPicker').height = imgThis.height;
			canvas.drawImage(img,0,0,imgThis.width,imgThis.height);
		}

		top.document.getElementById('floatingContainer').style.backgroundSize = (imgThis.naturalWidth*5)+"px "+(imgThis.naturalHeight*5)+"px";
	},

	// Interact with the canvas image
	interactCanvasImage: function (imgThis) {
		var canvas, x, y, imgData, R, G, B, rgb, hex, textColor, fcElem, fcBGX, fcBGY;

		canvas = top.get('canvasPicker').getContext('2d');

		// Show pointer colors on mouse move over canvas
		top.get('canvasPicker').onmousemove = function(event) {
			// get mouse x & y
			x = event.pageX - this.offsetLeft;
			y = event.pageY - this.offsetTop;
			// get image data & then RGB values
			imgData = canvas.getImageData(x, y, 1, 1).data;
			R = imgData[0];
			G = imgData[1];
			B = imgData[2];
			rgb = R+','+G+','+B;
	 		// Get hex from RGB value
			hex = top.ICEcoder.rgbToHex(R,G,B);
			// set the values & BG colours of the input boxes
			top.get('rgbMouseXY').value = rgb;
			top.get('hexMouseXY').value = '#' + hex;
			top.get('hexMouseXY').style.backgroundColor = top.get('rgbMouseXY').style.backgroundColor = '#' + hex;
			textColor = R<128 || G<128 || B<128 && (R<200 && G<200 && B>50) ? '#fff' : '#000';
			top.get('hexMouseXY').style.color = top.get('rgbMouseXY').style.color = textColor;

			// Move the floating container to follow mouse pointer
			fcElem = get('floatingContainer');
			fcElem.style.left = top.ICEcoder.mouseX+20 + "px";
			fcElem.style.top = top.ICEcoder.mouseY + "px";
			// Move the background image for the container to match also
			// 5 x zoom, account for scaling down of large images and shift 25px of the hover div size
			// (55px is the 11x11 grid of pixels), minus 5px for centre row/col
			fcBGX = -((x*5)*(imgThis.naturalWidth/imgThis.width))+25;
			fcBGY = -((y*5)*(imgThis.naturalHeight/imgThis.height))+25;
			fcElem.style.backgroundPosition = fcBGX+"px "+fcBGY+"px";
		};
		// Show image preview box on mouse over
		top.get('canvasPicker').onmouseover = function(event) {
			get('floatingContainer').style.visibility = "visible";
		};
		// Hide image preview box on mouse out
		top.get('canvasPicker').onmouseout = function(event) {
			get('floatingContainer').style.visibility = "hidden";
		};
		// Set pointer colors on clicking canvas
		top.get('canvasPicker').onclick = function() {
			top.get('rgb').value = top.get('rgbMouseXY').value;
	  		top.get('hex').value = top.get('hexMouseXY').value;
			top.get('hex').style.backgroundColor = top.get('rgb').style.backgroundColor = top.get('hex').value;
			top.get('hex').style.color = top.get('rgb').style.color = textColor;
		}
	},

	// Convert RGB values to Hex
	rgbToHex: function(R,G,B) {
		return top.ICEcoder.toHex(R)+top.ICEcoder.toHex(G)+top.ICEcoder.toHex(B);
	},

	// Return numbers as hex equivalent
	toHex: function(n) {
		n = parseInt(n,10);
		if (isNaN(n)) return "00";
		n = Math.max(0,Math.min(n,255));
		return "0123456789abcdef".charAt((n-n%16)/16) + "0123456789abcdef".charAt(n%16);
	},

	// Insert new color value
	insertColorValue: function(color) {
		var cM, cMdiff, thisCM, cursor;

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		cursor = thisCM.getTokenAt(thisCM.getCursor());
		thisCM.replaceRange(color,{line:thisCM.getCursor().line,ch:cursor.start},{line:thisCM.getCursor().line,ch:1000000});
	},

	// Change opacity of the file manager icons
	fMIconVis: function(icon, vis) {
		var i;

		if (i = top.get(icon)) {
			i.style.opacity = vis;
		}
	},

	// Check if a file is already open
	isOpen: function(file) {
		var i;

		file = file.replace(/\|/g, "/").replace(top.docRoot+top.iceRoot,"");
		i = top.ICEcoder.openFiles.indexOf(file);
		// return the array position or false
		return i!=-1 ? i : false;
	},

// ==============
// SYSTEM
// ==============

	// Start running plugin intervals according to given specifics
	startPluginIntervals: function(plugRef,plugURL,plugTarget,plugTimer) {
		// Add CSRF to URL if it has QS params
		if (plugURL.indexOf("?") > -1) {
			plugURL = plugURL+"&csrf="+top.ICEcoder.csrf;
		}
		top.ICEcoder['plugTimer'+plugRef] = 
		// This window instances
			["_parent","_top","_self",""].indexOf(plugTarget) > -1
			? top.ICEcoder['plugTimer'+plugRef] = setInterval('window.location=\''+plugURL+'\'',plugTimer*1000*60)
		// fileControl iframe instances
			: plugTarget.indexOf("fileControl") == 0
			? top.ICEcoder['plugTimer'+plugRef] = setInterval(function() {
				top.ICEcoder.serverQueue("add",plugURL);top.ICEcoder.serverMessage(plugTarget.split(":")[1]);
				},plugTimer*1000*60)
		// _blank or named target window instances
			: top.ICEcoder['plugTimer'+plugRef] = setInterval('window.open(\''+plugURL+'\',\''+plugTarget+'\')',plugTimer*1000*60);

		// push the plugin ref into our array
		top.ICEcoder.pluginIntervalRefs.push(plugRef);
	},

	// Turning on/off the Code Assist
	codeAssistToggle: function() {
		var cM, cMdiff, fileName, fileExt;

		top.ICEcoder.codeAssist = !top.ICEcoder.codeAssist;
		top.get('codeAssistDisplay').style.backgroundPosition = top.ICEcoder.codeAssist ? "0 0" : "-16px 0";
		top.ICEcoder.cssColorPreview();
		top.ICEcoder.focus(top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? 'diff' : false);

		for (i=0;i<top.ICEcoder.cMInstances.length;i++) {
			fileName = top.ICEcoder.openFiles[i];
			fileExt = fileName.split(".");
			fileExt = fileExt[fileExt.length-1];
			if (fileExt == "js" || fileExt == "json") {
				cM = top.ICEcoder.content.contentWindow['cM'+top.ICEcoder.cMInstances[i]];
				cMdiff = top.ICEcoder.content.contentWindow['cM'+top.ICEcoder.cMInstances[i]+'diff'];
				if (!top.ICEcoder.codeAssist) {
					cM.clearGutter("CodeMirror-lint-markers");
					cM.setOption("lint",false);
					cMdiff.clearGutter("CodeMirror-lint-markers");
					cMdiff.setOption("lint",false);
				} else {
					cM.setOption("lint", true);
					cMdiff.setOption("lint", true);
				}
			}
		}
	},

	// Queue items up for processing in turn
	serverQueue: function(action,item,file) {
		var cM, nextSaveID, txtArea, topSaveID, element, xhr, statusObj, timeStart;

		cM = ICEcoder.getcMInstance();
		// Firstly, work out how many saves we have to carry out
		nextSaveID=0;
		for (var i=0;i<ICEcoder.serverQueueItems.length;i++) {
			if (ICEcoder.serverQueueItems[i].indexOf('action=save')>0) {
				nextSaveID++;
			}
		}
		nextSaveID++;

		// Add to end of array or remove from beginning on demand, plus add or remove if necessary
		if (action=="add") {
			ICEcoder.serverQueueItems.push(item);
			if (item.indexOf('action=save')>0) {
				txtArea = document.createElement('textarea');
				txtArea.setAttribute('id', 'saveTemp'+nextSaveID);
				document.body.appendChild(txtArea);
				top.get('saveTemp'+nextSaveID).value = cM.getValue();
			}
		} else if (action=="del") {
			if (ICEcoder.serverQueueItems[0] && ICEcoder.serverQueueItems[0].indexOf('action=save')>0) {
				topSaveID = nextSaveID-1;
				for (var i=1;i<topSaveID;i++) {
					top.get('saveTemp'+i).value = top.get('saveTemp'+(i+1)).value;
				}
				element = top.get('saveTemp'+topSaveID);
				element.parentNode.removeChild(element);
			}
			ICEcoder.serverQueueItems.splice(0,1);
		}

		// If we've just removed from the array and there's another action queued up, or we're triggering for the first time
		// then do the next requested process, stored at array pos 0
		if (action=="del" && ICEcoder.serverQueueItems.length>=1 || ICEcoder.serverQueueItems.length==1) {
			// If we have an item, we're not saving previous file refs and not loading
			if (item && (item.indexOf('saveFiles=')==-1 && item.indexOf('action=load')==-1)) {
				xhr = top.ICEcoder.xhrObj();
				xhr.onreadystatechange=function() {
					if (xhr.readyState==4) {
						// Parse the response as a JSON object
						statusObj = JSON.parse(xhr.responseText);

						// Set the action end time and time taken in JSON object
						statusObj.action.timeEnd = new Date().getTime();
						statusObj.action.timeTaken = statusObj.action.timeEnd - statusObj.action.timeStart;

						// User wanted raw (or both) output of the response?
						if (["raw","both"].indexOf(top.ICEcoder.fileDirResOutput) >= 0) {
							console.log(xhr.responseText);
						}
						// User wanted object (or both) output of the response?
						if (["object","both"].indexOf(top.ICEcoder.fileDirResOutput) >= 0) {
							console.log(statusObj);
						}

						// OK reponse? If error, show that, otherwise do whatever we're required to do next
						if (xhr.status==200) {
							if (statusObj.status.error) {
								top.ICEcoder.message(statusObj.status.errorMsg);
								console.log("ICEcoder error info for your request...");
								console.log(statusObj);
								top.ICEcoder.serverMessage();
								top.ICEcoder.serverQueue('del',0);
							} else {
								eval(statusObj.action.doNext);
							}
						// Some other response? Display a message about that
						} else {
							top.ICEcoder.message(top.t['Sorry there was...']);
							console.log("ICEcoder error info for your request...");
							console.log(statusObj);
							top.ICEcoder.serverMessage();
							top.ICEcoder.serverQueue('del',0);
						}
					}
				};
				xhr.open("POST",ICEcoder.serverQueueItems[0],true);
				xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
				timeStart = new Date().getTime();
				if (item.indexOf('action=save')>0) {
					xhr.send('timeStart='+timeStart+'&file='+file+'&contents='+encodeURIComponent(top.document.getElementById('saveTemp1').value));
				} else {
					xhr.send('timeStart='+timeStart+'&file='+file);
				}
			} else {

				setTimeout(function() {
					if ("undefined" != typeof ICEcoder.serverQueueItems[0]) {
						top.ICEcoder.filesFrame.contentWindow.frames['fileControl'].location.href=ICEcoder.serverQueueItems[0];
					}
				},1);

			}
		}
	},

	// Cancel all actions on pressing Esc in non content areas
	cancelAllActions: function() {
		// Stop whatever the parent may be loading and clear tasks other than the current one
		window.stop();
		if (ICEcoder.serverQueueItems.length>0) {
			ICEcoder.serverQueueItems.splice(1,ICEcoder.serverQueueItems.length);
		}
		top.ICEcoder.showHide('hide',top.get('loadingMask'));
		top.ICEcoder.serverMessage('<b style="color: #d00">'+top.t['Cancelled tasks']+'</b>');
		setTimeout(function() {top.ICEcoder.serverMessage();},2000);
	},

	// Set the current previousFiles in the settings file
	setPreviousFiles: function() {
		var previousFiles;

		previousFiles = top.ICEcoder.openFiles.join(',').replace(/\//g,"|").replace(/(\|\[NEW\])|(,\|\[NEW\])/g,"").replace(/(^,)|(,$)/g,"");
		if (previousFiles=="") {previousFiles="CLEAR"};
		// Then send through to the settings page to update setting
		top.ICEcoder.serverQueue("add","lib/settings.php?saveFiles="+previousFiles+"&csrf="+top.ICEcoder.csrf);
		top.ICEcoder.updateLast10List(previousFiles);
	},

	// Update the list of 10 previous files in browser
	updateLast10List: function(previousFiles) {
		var newFile, last10Files, last10FilesList;

		// Split our previous files string into an array
		previousFiles = previousFiles.split(',');
		// For each one of those, if it's not 'CLEAR' we can maybe rotate the list
		for (var i=0; i<previousFiles.length; i++) {
			if (previousFiles[i] != "CLEAR") {
				// Set the new file LI item to maybe insert at top of the list, including trailing new line to split on in future
				newFile = "<li class=\"pft-file ext-"+previousFiles[i].substring(previousFiles[i].lastIndexOf(".")+1)+"\" style=\"margin-left: -21px\"><a style=\"cursor:pointer\" onclick=\"top.ICEcoder.openFile('"+previousFiles[i].replace(/\|/g,"/")+"')\">"+previousFiles[i].replace(/\|/g,"/")+"</a></li>\n";

				// Get DOM elem for last 10 files
				last10Files = top.ICEcoder.content.contentWindow.document.getElementById('last10Files');

				// If the innerHTML of that doesn't contain our new item, we can insert it
				if(last10Files.innerHTML.indexOf(newFile) == -1) {
					// Get the last 10 files list, pop the last one off and add newFile at start
					last10FilesList = last10Files.innerHTML.split("\n");
					if (
						last10FilesList.length >= 10 ||													// No more than 10
						last10FilesList[0] == '<div style="display: inline-block; margin-left: -39px; margin-top: -4px">[none]</div><br><br>' ||	// Clear out placeholder
						last10FilesList[last10FilesList.length-1] == ""											// No empty array items
					) {
						last10FilesList.pop();
					}
					// Update the list
					last10Files.innerHTML = newFile + (last10FilesList.join("\n"));
				}
			}
		}
	},

	// Opens the last files we had open
	autoOpenFiles: function() {
		if (top.ICEcoder.previousFiles.length>0 && top.ICEcoder.ask(top.t['Open previous files']+'\n\n'+top.ICEcoder.previousFiles.length+' files:\n'+top.ICEcoder.previousFiles.join('\n').replace(/\|/g,"/").replace(new RegExp(top.docRoot+top.iceRoot,'gi'),""))) {
			for (var i=0;i<top.ICEcoder.previousFiles.length;i++) {
				top.ICEcoder.thisFileFolderLink=top.ICEcoder.previousFiles[i].replace('|','/');
				top.ICEcoder.thisFileFolderType='file';
				top.ICEcoder.openFile();
			}
		}
	},

	// Show the settings screen
	settingsScreen: function(hide) {
		if (!hide) {
			top.get('mediaContainer').innerHTML = '<iframe src="lib/settings-screen.php" class="whiteGlow" style="width: 970px; height: 610px"></iframe>';
		}
		top.ICEcoder.showHide(hide?'hide':'show',top.get('blackMask'));
	},

	// Show the help screen
	helpScreen: function() {
		top.get('mediaContainer').innerHTML = '<iframe src="lib/help.php" class="whiteGlow" style="width: 840px; height: 465px"></iframe>';
		top.ICEcoder.showHide('show',top.get('blackMask'));
	},

	// Show the backup versions screen
	versionsScreen: function(file,versions) {
		top.get('mediaContainer').innerHTML = '<iframe src="lib/backup-versions.php?file='+file+'&csrf='+top.ICEcoder.csrf+'" class="whiteGlow" style="width: 840px; height: 465px"></iframe>';
		top.ICEcoder.showHide('show',top.get('blackMask'));
	},

	// Show the ICEcoder manual, loaded remotely
	showManual: function(version,section) {
		var sectionExtra;

		sectionExtra = section ? "#"+section : "";
		top.get('mediaContainer').innerHTML = '<iframe src="https://icecoder.net/manual?version='+version+sectionExtra+'" class="whiteGlow" style="width: 800px; height: 470px"></iframe>';
		top.ICEcoder.showHide('show',top.get('blackMask'));
	},

	// Show the properties screen
	propertiesScreen: function(fileName) {
		top.get('mediaContainer').innerHTML = '<iframe src="lib/properties.php?fileName='+fileName.replace(/\//g,"|")+'&csrf='+top.ICEcoder.csrf+'" class="whiteGlow" style="width: 660px; height: 330px"></iframe>';
		top.ICEcoder.showHide('show',top.get('blackMask'));
	},

	// Show the plugins manager
	pluginsManager: function() {
		top.get('mediaContainer').innerHTML = '<iframe src="lib/plugins-manager.php" class="whiteGlow" style="width: 800px; height: 450px"></iframe>';
		top.ICEcoder.showHide('show',top.get('blackMask'));
	},

	// Show the GitHub commit screen
	githubAction: function(action) {
		top.get('mediaContainer').innerHTML = '<iframe src="lib/github.php?action='+action+'&selectedFiles='+top.ICEcoder.selectedFiles.join(";")+'&csrf='+top.ICEcoder.csrf+'" class="whiteGlow" style="width: 340px; height: 340px"></iframe>';
		top.ICEcoder.showHide('show',top.get('blackMask'));
	},

	// Ask user for GitHub token
	githubTokenAsk: function(goNext) {
		if (githubAuthToken = top.ICEcoder.getInput(top.t['Please enter your...'],'')) {
			top.ICEcoder.filesFrame.contentWindow.frames['fileControl'].location.href = "lib/github.php?action=auth&token="+githubAuthToken+"&goNext="+goNext+"&csrf="+top.ICEcoder.csrf;
			// Clear the token from the var for security
			githubAuthToken = "";
		}
	},

	// Show/Hide the GitHub file nav
	showHideGithubNav: function(vis) {
		top.get('githubNav').style.display	= vis == "show" ? "block" : "none";
		top.get('fileNav').style.display	= vis == "show" ? "none" : "block";
	},

	// Show the GitHub manager
	githubManager: function() {
		var githubAuthToken;
		if (top.ICEcoder.githubAuthTokenSet) {
			top.get('mediaContainer').innerHTML = '<iframe src="lib/github-manager.php" class="whiteGlow" style="width: 660px; height: 450px"></iframe>';
			top.ICEcoder.showHide('show',top.get('blackMask'));
		} else {
			top.ICEcoder.githubTokenAsk('showManager');
		}
	},

	// Toggle the GitHub diff on/off view
	githubDiffToggle: function() {
		var gHDiff;

		if (!top.ICEcoder.githubAuthTokenSet) {
			top.ICEcoder.githubTokenAsk('loadFiles');
		} else if (top.ICEcoder.githubDiff || top.ICEcoder.ask(top.t['This will compare...'])) {
			top.ICEcoder.githubDiff = !top.ICEcoder.githubDiff;
			gHDiff = top.ICEcoder.githubDiff ? "true" : "false";

			top.ICEcoder.filesFrame.src = "files.php?githubDiff="+gHDiff+"&csrf="+top.ICEcoder.csrf;
		}
	},

	// Update the settings used when we make a change to them
	useNewSettings: function(themeURL,codeAssist,lockedNav,tagWrapperCommand,autoComplete,visibleTabs,fontSize,lineWrapping,indentWithTabs,indentAuto,indentSize,pluginPanelAligned,bugFilePaths,bugFileCheckTimer,bugFileMaxLines,githubAuthTokenSet,updateDiffOnSave,refreshFM) {
		var styleNode, thisCSS, strCSS, activeLineBG;

		// cut out ?microtime= at the end
		var cleanThemeUrl = themeURL.slice(0, themeURL.lastIndexOf("?"));
		// find out new theme name without leading path and trailing ".css"
		var newTheme = cleanThemeUrl.slice(cleanThemeUrl.lastIndexOf("/")+1,cleanThemeUrl.lastIndexOf("."));
		// if theme was not changed - no need to do all these tricks
		if (top.ICEcoder.theme !== newTheme){
			// Add new stylesheet for selected theme
			top.ICEcoder.theme = newTheme;
			if (top.ICEcoder.theme=="editor") {top.ICEcoder.theme="icecoder"};
			styleNode = document.createElement('link');
			styleNode.setAttribute('rel', 'stylesheet');
			styleNode.setAttribute('type', 'text/css');
			styleNode.setAttribute('href', themeURL);
			top.ICEcoder.content.contentWindow.document.getElementsByTagName('head')[0].appendChild(styleNode);
			if (["3024-day","base16-light","eclipse","elegant","mdn-like","neat","neo","paraiso-light","solarized","the-matrix","xq-light"].indexOf(top.ICEcoder.theme)>-1) {
				activeLineBG = "#ccc";
			} else if (["3024-night","blackboard","colorforth","liquibyte","night","tomorrow-night-bright","tomorrow-night-eighties","vibrant-ink"].indexOf(top.ICEcoder.theme)>-1) {
				activeLineBG = "#888";
			} else {
				activeLineBG = "#000";
			}
			top.ICEcoder.switchTab(top.ICEcoder.selectedTab);
		}

		// Check/uncheck Code Assist setting
		if (codeAssist != top.ICEcoder.codeAssist) {
			top.get('codeAssist').checked = codeAssist;
			top.ICEcoder.codeAssistToggle();
		}

		// Unlock/lock the file manager
		if (lockedNav != top.ICEcoder.lockedNav) {
			top.ICEcoder.lockUnlockNav();
			ICEcoder.changeFilesW(!lockedNav ? 'contract' : 'expand'); 
			top.ICEcoder.hideFileMenu();
		};

		// Update font size at top level
		thisCSS = top.document.styleSheets[0];
		strCSS = thisCSS.rules ? 'rules' : 'cssRules';
		thisCSS[strCSS][0].style['fontSize'] = fontSize;

		// Update font size in file manager
		thisCSS = ICEcoder.filesFrame.contentWindow.document.styleSheets[3];
		strCSS = thisCSS.rules ? 'rules' : 'cssRules';
		thisCSS[strCSS][0].style['fontSize'] = fontSize;

		// Update styles in editor
		thisCSS = ICEcoder.content.contentWindow.document.styleSheets[4];
		strCSS = thisCSS.rules ? 'rules' : 'cssRules';
		thisCSS[strCSS][0].style['fontSize'] = fontSize;
		thisCSS[strCSS][4].style['border-left-width'] = visibleTabs ? '1px' : '0';
		thisCSS[strCSS][4].style['margin-left'] = visibleTabs ? '-1px' : '0';
		thisCSS[strCSS][2].style.cssText = "background-color: " + activeLineBG + " !important";

		top.ICEcoder.lineWrapping = lineWrapping;
		top.ICEcoder.indentWithTabs = indentWithTabs;
		top.ICEcoder.indentSize = indentSize;
		top.ICEcoder.indentAuto = indentAuto;
		for (var i=0;i<ICEcoder.cMInstances.length;i++) {
			// Main pane
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]].setOption("lineWrapping", top.ICEcoder.lineWrapping);
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]].setOption("indentWithTabs", top.ICEcoder.indentWithTabs);
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]].setOption("indentUnit", top.ICEcoder.indentSize);
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]].setOption("tabSize", top.ICEcoder.indentSize);
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]].refresh();
			// Diff pane
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]+'diff'].setOption("lineWrapping", top.ICEcoder.lineWrapping);
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]+'diff'].setOption("indentWithTabs", top.ICEcoder.indentWithTabs);
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]+'diff'].setOption("indentUnit", top.ICEcoder.indentSize);
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]+'diff'].setOption("tabSize", top.ICEcoder.indentSize);
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]+'diff'].refresh();
		}

		if (tagWrapperCommand != top.ICEcoder.tagWrapperCommand) {
			top.ICEcoder.tagWrapperCommand = tagWrapperCommand;
		}

		if (autoComplete != top.ICEcoder.autoComplete) {
			top.ICEcoder.autoComplete = autoComplete;
		}

		top.get('plugins').style.left = pluginPanelAligned == "left" ? "0" : "auto";
		top.get('plugins').style.right = pluginPanelAligned == "right" ? "0" : "auto";

		// Restart bug checking
		top.ICEcoder.bugFilePaths = bugFilePaths;
		top.ICEcoder.bugFileCheckTimer = bugFileCheckTimer;
		top.ICEcoder.bugFileMaxLines = bugFileMaxLines;

		if (top.ICEcoder.bugFilePaths[0] != "") {
			top.ICEcoder.startBugChecking();
		} else {
			if ("undefined" != typeof top.ICEcoder.bugFileCheckInt) {
				clearInterval(top.ICEcoder.bugFileCheckInt);
			}
		}

		// Update diffs if we have a split pane
		if (top.ICEcoder.splitPane) {
			top.ICEcoder.updateDiffs();
		}

		// Set the flag to indicate if the GitHub auth token is set
		top.ICEcoder.githubAuthTokenSet = githubAuthTokenSet;

		// Set the flag to indicate if we update diff pane on save
		top.ICEcoder.updateDiffOnSave = updateDiffOnSave;

		// Finally, refresh the file manager if we need to
		if (refreshFM) {top.ICEcoder.refreshFileManager()};
	},

	// Update and show/hide found results display?
	updateResultsDisplay: function(showHide) {
		ICEcoder.findReplace(top.get('find').value,true,false);
		top.get('results').style.display = showHide=="show" ? 'inline-block' : 'none';
	},

	// Toggle full screen on/off
	fullScreenSwitcher: function() {
		// Future use
		if ("undefined" != typeof document.cancelFullScreen) {
			document.fullScreen ? document.cancelFullScreen() : document.body.requestFullScreen();
		// Moz specific
		} else if ("undefined" != typeof document.mozCancelFullScreen) {
			document.mozFullScreen ? document.mozCancelFullScreen() : document.body.mozRequestFullScreen();
		// Chrome specific
		} else if ("undefined" != typeof document.webkitCancelFullScreen) {
			document.webkitIsFullScreen ? document.webkitCancelFullScreen() : document.body.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
		}
	},

	// Pass target file/folder to Zip It!
	zipIt: function(tgt) {
		tgt=tgt.replace(/\//g,"|");
		top.ICEcoder.filesFrame.contentWindow.frames['fileControl'].location.href="plugins/zip-it/index.php?zip="+tgt+"&csrf="+top.ICEcoder.csrf;
	},

	// Prompt to download our file
	downloadFile: function(file) {
		file=file.replace(/\//g,"|");
		top.ICEcoder.filesFrame.contentWindow.frames['fileControl'].location.href="lib/download.php?file="+file+"&csrf="+top.ICEcoder.csrf;
	},

	// Change permissions on a file/folder
	chmod: function(file,perms) {
		file = file.replace(top.iceRoot,"");
		top.ICEcoder.showHide('hide',top.get('blackMask'));
		top.ICEcoder.serverQueue("add","lib/file-control-xhr.php?action=perms&perms="+perms+"&csrf="+top.ICEcoder.csrf,file);
		top.ICEcoder.serverMessage('<b>chMod '+perms+' on </b><br>'+file.replace(/\|/g,"/"));
	},

	// Open/show the preview window
	openPreviewWindow: function() {
		if (top.ICEcoder.openFiles.length>0) {
			var cM, cMdiff, thisCM, filepath, filename, fileExt;

			filepath = top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1];
			filename = filepath.substr(filepath.lastIndexOf("/")+1);
			fileExt = filename.substr(filename.lastIndexOf(".")+1);
			cM = ICEcoder.getcMInstance();
			cMdiff = ICEcoder.getcMdiffInstance();
			thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;

			top.ICEcoder.previewWindowLoading = true;
			top.ICEcoder.previewWindow = window.open(filepath,"previewWindow",500,500);
			if (["md"].indexOf(fileExt) > -1) {
				top.ICEcoder.previewWindow.onload = function() {
					top.ICEcoder.previewWindowLoading = false;
					top.ICEcoder.previewWindow.document.documentElement.innerHTML = mmd(thisCM.getValue())
				};
			} else {
				top.ICEcoder.previewWindow.onload = function() {
					top.ICEcoder.previewWindowLoading = false;
					// Do the pesticide plugin if it exists
					try {top.ICEcoder.doPesticide();} catch(err) {};
					// Do the stats.js plugin if it exists
					try {top.ICEcoder.doStatsJS('open');} catch(err) {};
					// Do the responsive plugin if it exists
					try {top.ICEcoder.doResponsive();} catch(err) {};
				}
			}
		}
	},

	// Logout of ICEcoder
	logout: function() {
		window.location = window.location + "?logout&csrf="+top.ICEcoder.csrf;
	},

	// Show a message
	message: function(msg) {
		alert(msg);
	},

	// Ask for confirmation
	ask: function(question) {
		return confirm(question);
	},

	// Get the users input
	getInput: function(question,defaultValue) {
		return prompt(question,defaultValue);
	},

	// Show a data screen message
	dataMessage: function(message) {
		var dM;

		dM = top.ICEcoder.content.contentWindow.document.getElementById('dataMessage');
		dM.style.display = "block";
		dM.innerHTML = message;
	},

	// Update ICEcoder
	update: function() {
		var autoUpdate;

		autoUpdate = confirm(top.t['Please note for...']);
		if (autoUpdate) {
			top.ICEcoder.showHide('show',top.get('loadingMask'));
			window.location = "lib/updater.php";
		} else {
			window.open("https://icecoder.net");
		}
	},

	// ICEcoder just updated
	updated: function() {
		top.get('blackMask').style.visibility = "visible";
		top.get('mediaContainer').innerHTML 	= '<h1 style="color: #fff; cursor: default">Thanks for updating to v'+top.ICEcoder.versionNo+'!</h1>'
							+ '<h2 style="color: #888; cursor: default">Click anywhere to continue using ICEcoder...</h2>';
	},

	// XHR object
	xhrObj: function(){
		try {return new XMLHttpRequest();}catch(e){}
		try {return new ActiveXObject("Msxml3.XMLHTTP");}catch(e){}
		try {return new ActiveXObject("Msxml2.XMLHTTP.6.0");}catch(e){}
		try {return new ActiveXObject("Msxml2.XMLHTTP.3.0");}catch(e){}
		try {return new ActiveXObject("Msxml2.XMLHTTP");}catch(e){}
		try {return new ActiveXObject("Microsoft.XMLHTTP");}catch(e){}
		return null;
	},

	// Open bug report
	openBugReport: function() {
		var bugReportOpenFilePos;

		if(top.ICEcoder.bugReportStatus=="off") {
			top.ICEcoder.message(top.t['You can start...']);
		}
		if(top.ICEcoder.bugReportStatus=="error") {
			top.ICEcoder.message(top.t['Error cannot find...']);
		}
		if(top.ICEcoder.bugReportStatus=="ok") {
			top.ICEcoder.message(top.t['No new errors...']);
		}
		if(top.ICEcoder.bugReportStatus=="bugs") {
			// Close bug-report without saving previousFiles and without confirming close if we made changes on the bug report
			var bugReportOpenFilePos = top.ICEcoder.openFiles.indexOf(top.ICEcoder.bugReportPath.replace(/\|/g,"/"));
			if (bugReportOpenFilePos > -1) {
				top.ICEcoder.closeTab(bugReportOpenFilePos+1,'dontSetPV','dontAsk');
			}
			top.ICEcoder.openFile(top.ICEcoder.bugReportPath);
			top.ICEcoder.bugFilesSizesSeen = top.ICEcoder.bugFilesSizesActual;
		}
	},

	// Start bug checking by looking in bug file paths on a timer
	startBugChecking: function() {
		var bugCheckURL;

		if (top.ICEcoder.bugFileCheckTimer !== 0) {
			// Clear any existing interval
			if ("undefined" != typeof top.ICEcoder.bugFileCheckInt) {
				clearInterval(top.ICEcoder.bugFileCheckInt);
			}
			// Start a new timer
			top.ICEcoder.bugFilesSizesSeen = [];
			top.ICEcoder.bugFileCheckInt = setInterval(function() {
				bugCheckURL =  "lib/bug-files-check.php?";
				bugCheckURL += "files="+(top.ICEcoder.bugFilePaths[0] !== "" ? top.ICEcoder.bugFilePaths.join() : "null").replace(/\//g,"|");
				bugCheckURL += "&filesSizesSeen=";
				if (top.ICEcoder.bugFilesSizesSeen.length != top.ICEcoder.bugFilePaths.length) {
					// Fill the array with nulls
					for (var i=0; i<top.ICEcoder.bugFilePaths.length; i++) {
						top.ICEcoder.bugFilesSizesSeen[i] = "null";
					}
				}
				bugCheckURL += top.ICEcoder.bugFilesSizesSeen.join();
				bugCheckURL += "&maxLines="+top.ICEcoder.bugFileMaxLines;
				bugCheckURL += "&csrf="+top.ICEcoder.csrf;

				var xhr = top.ICEcoder.xhrObj();

				xhr.onreadystatechange=function() {
					if (xhr.readyState==4 && xhr.status==200) {
						// console.log(xhr.responseText);
						var statusArray = JSON.parse(xhr.responseText);
						// console.log(statusArray);

						top.get('bugIcon').style.backgroundPosition = 
						statusArray['result'] == "off" ? "0 0" :
						statusArray['result'] == "ok" ? "-32px 0" :
						statusArray['result'] == "bugs" ? "-48px 0" :
						"-16px 0"; // if the result is 'error' or another value
						top.ICEcoder.bugReportStatus = statusArray['result'];
						if (top.ICEcoder.bugFilesSizesSeen[0]=="null") {
							top.ICEcoder.bugFilesSizesSeen = statusArray['filesSizesSeen'];
						}
						top.ICEcoder.bugFilesSizesActual = statusArray['filesSizesSeen'];
						top.ICEcoder.bugReportPath = statusArray['bugReportPath'];

					}
				};
				// console.log('Calling '+bugCheckURL+' via XHR');
				xhr.open("GET",bugCheckURL,true);
				xhr.send();

			},parseInt(top.ICEcoder.bugFileCheckTimer*1000,10));
			// State that we're checking for bugs
			top.ICEcoder.bugReportStatus = "ok";
		} else {
			if ("undefined" != typeof top.ICEcoder.bugFileCheckInt) {
				clearInterval(top.ICEcoder.bugFileCheckInt);
			}
		}
	},

	// Return safe HTML equivalents
	xssClean: function(data) {
		return data
		.replace(/&/g, "&amp;")
		.replace(/</g, "&lt;")
		.replace(/>/g, "&gt;")
		.replace(/"/g, "&quot;")
		.replace(/'/g, "&#039;");
	},

	// Print code of current tab
	printCode: function() {
		var cM, cMdiff, thisCM, printIFrame;

		cM = top.ICEcoder.getcMInstance();
		cMdiff = top.ICEcoder.getcMdiffInstance();
		thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		printIFrame = top.ICEcoder.filesFrame.contentWindow.frames['fileControl'];
		// Print page content injected into iFrame, escaped with pre and xssClean
		printIFrame.window.document.body.innerHTML = '<!DOCTYPE html><head><title>ICEcoder code output</title></head><body><pre style="white-space: pre-wrap">'+top.ICEcoder.xssClean(thisCM.getValue())+'</pre></body></html>';
		printIFrame.focus();
		printIFrame.print();
		// Focus back on code
		thisCM.focus();
	},

	// Update the title tag to indicate any changes
	indicateChanges: function() {
		var winTitle;

		if (!top.ICEcoder.loadingFile) {
			winTitle = "ICEcoder v "+top.ICEcoder.versionNo;
			for(var i=1;i<=top.ICEcoder.savedPoints.length;i++) {
				if (top.ICEcoder.savedPoints[i-1]!=top.ICEcoder.getcMInstance(i).changeGeneration()) {
					// We have an unsaved tab, indicate that in the title
					winTitle += " \u2744";
					break;
				}
			}
			top.document.title = winTitle;
		}
	},

// ==============
// TABS
// ==============

	// Change tabs by switching visibility of instances
	switchTab: function(newTab,noFocus) {
		var cM, cMdiff, thisCM;

		// Identify tab that's currently selected & get the instance
		ICEcoder.selectedTab = newTab;
		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;

		if (thisCM) {
			// Switch mode to HTML, PHP, CSS etc
			ICEcoder.switchMode();

			// Set all cM instances to be hidden, then make our selected instance visible
			for (var i=0;i<ICEcoder.cMInstances.length;i++) {
				ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]].getWrapperElement().style.display = "none";
				ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]+'diff'].getWrapperElement().style.display = "none";
			}
			cM.setOption('theme',top.ICEcoder.theme);
			cMdiff.setOption('theme',top.ICEcoder.theme + " diff");
			cM.getWrapperElement().style.display = "block";
			cMdiff.getWrapperElement().style.display = "block";

			// Redo our diffs if split pane
			if (top.ICEcoder.splitPane) {
				top.ICEcoder.updateDiffs();
			}

			// Focus on & refresh our selected instance
			if (!noFocus) {setTimeout(function() {top.ICEcoder.focus();},4);}
			cM.refresh();
			cMdiff.refresh();

			// Highlight the selected tab
			ICEcoder.redoTabHighlight(ICEcoder.selectedTab);

			// Redo our find display
			top.ICEcoder.findMode = false;
			ICEcoder.findReplace(top.get('find').value,true,false);

			// Update our versions display
			top.ICEcoder.updateVersionsDisplay();

			// Finally, update the cursor display
			top.ICEcoder.getCaretPosition();
			top.ICEcoder.updateCharDisplay();
			top.ICEcoder.updateByteDisplay();
		}
	},

	// Starts a new file by setting a few vars & creating a new cM instance
	newTab: function(alsoSave) {
		var cM;

		ICEcoder.cMInstances.push(ICEcoder.nextcMInstance);
		ICEcoder.selectedTab = ICEcoder.cMInstances.length;
		ICEcoder.showHide('show',ICEcoder.content);
		ICEcoder.content.contentWindow.createNewCMInstance(ICEcoder.nextcMInstance);
		ICEcoder.setLayout();

		ICEcoder.thisFileFolderType='file';
		ICEcoder.thisFileFolderLink='/[NEW]';
		ICEcoder.openFile();

		cM = ICEcoder.getcMInstance('new');
		ICEcoder.switchTab(ICEcoder.openFiles.length);

		cM.removeLineClass(ICEcoder['cMActiveLinecM'+ICEcoder.cMInstances[top.ICEcoder.selectedTab-1]], "background");
		ICEcoder['cMActiveLinecM'+ICEcoder.selectedTab] = cM.addLineClass(0, "background", "cm-s-activeLine");
		ICEcoder.nextcMInstance++;

		// Also save?
		if (alsoSave) {
			top.ICEcoder.saveFile();
		}
	},

	// Create a new tab for a file
	createNewTab: function(isNew) {
		var closeTabLink, fileName;

		// Push new file into array
		top.ICEcoder.openFiles.push(top.ICEcoder.shortURL);

		// Setup a new tab
		closeTabLink = '<a nohref onClick="top.ICEcoder.closeTab(parseInt(this.parentNode.id.slice(3),10))"><img src="images/nav-close.gif" class="closeTab" onMouseOver="prevBG=this.style.backgroundColor;this.style.backgroundColor=\'#333\'; top.ICEcoder.overCloseLink=true" onMouseOut="this.style.backgroundColor=prevBG; top.ICEcoder.overCloseLink=false"></a>';
		top.get('tab'+(top.ICEcoder.openFiles.length)).style.display = "inline-block";
		fileName = top.ICEcoder.openFiles[top.ICEcoder.openFiles.length-1];
		top.get('tab'+(top.ICEcoder.openFiles.length)).innerHTML = closeTabLink + " " + fileName.slice(fileName.lastIndexOf("/")).replace(/\//,"");
		top.get('tab'+(top.ICEcoder.openFiles.length)).title = "/" + top.ICEcoder.openFiles[top.ICEcoder.openFiles.length-1].replace(/\//,"");

		// Set the widths
		top.ICEcoder.setTabWidths();

		// Highlight it and state it's selected
		top.ICEcoder.redoTabHighlight(top.ICEcoder.openFiles.length);
		top.ICEcoder.selectedTab=top.ICEcoder.openFiles.length;

		// Add a new value ready to indicate if this content has been changed
		top.ICEcoder.savedPoints.push(0);

		if (!isNew) {
			top.ICEcoder.setPreviousFiles();
		}
	},

	// Cycle to next tab
	nextTab: function() {
		var goToTab;

		goToTab = top.ICEcoder.selectedTab+1 <= top.ICEcoder.openFiles.length ? top.ICEcoder.selectedTab+1 : 1;
		top.ICEcoder.switchTab(goToTab,'noFocus');
	},

	// Cycle to next tab
	previousTab: function() {
		var goToTab;

		goToTab = top.ICEcoder.selectedTab-1 >= 1 ? top.ICEcoder.selectedTab-1 : top.ICEcoder.openFiles.length;
		top.ICEcoder.switchTab(goToTab,'noFocus');
	},

	// Create a new tab for a file
	renameTab: function(tabNum,newName) {
		var closeTabLink, fileName;

		// Push new file into array
		top.ICEcoder.openFiles[tabNum-1] = newName;

		// Setup a new tab
		closeTabLink = '<a nohref onClick="top.ICEcoder.closeTab(parseInt(this.parentNode.id.slice(3),10))"><img src="images/nav-close.gif" class="closeTab" onMouseOver="prevBG=this.style.backgroundColor;this.style.backgroundColor=\'#333\'; top.ICEcoder.overCloseLink=true" onMouseOut="this.style.backgroundColor=prevBG; top.ICEcoder.overCloseLink=false"></a>';
		fileName = top.ICEcoder.openFiles[tabNum-1];
		top.get('tab'+tabNum).innerHTML = closeTabLink + " " + fileName.slice(fileName.lastIndexOf("/")).replace(/\//,"");
		top.get('tab'+tabNum).title = "/" + top.ICEcoder.openFiles[tabNum-1].replace(/\//,"");
	},

	// Reset all tabs to be without a highlight and then highlight the selected
	redoTabHighlight: function(selectedTab) {
		var tColor, fileLink;

		for(var i=1;i<=ICEcoder.savedPoints.length;i++) {
			if (top.get('tab'+i).childNodes[0]) {
				top.get('tab'+i).childNodes[0].childNodes[0].style.backgroundColor = ICEcoder.savedPoints[i-1]!=top.ICEcoder.getcMInstance(i).changeGeneration()
				? "#b00" : "transparent";
			}

			tColor = i==selectedTab ? top.ICEcoder.tabFGselected : top.ICEcoder.tabFGnormalTab;
			if ("undefined" != typeof top.ICEcoder.openFiles[i-1] && top.ICEcoder.openFiles[i-1] != "/[NEW]") {
				fileLink = top.ICEcoder.filesFrame.contentWindow.document.getElementById(top.ICEcoder.openFiles[i-1].replace(/\//g,"|"));
				if (fileLink) {
					fileLink.style.backgroundColor = i==selectedTab ? top.ICEcoder.tabBGcurrent : top.ICEcoder.tabBGopen;
					fileLink.style.color = i==selectedTab ? top.ICEcoder.tabFGcurrent : top.ICEcoder.tabFGopenFile;
				};
			}
			top.get('tab'+i).style.color = tColor;
			top.get('tab'+i).style.background = i==selectedTab ? top.ICEcoder.tabBGcurrent : top.ICEcoder.tabBGopen;
		}
	},

	// Close the tab upon request
	closeTab: function(closeTabNum, dontSetPV, dontAsk) {
		var cM, cMdiff, thisCM, okToRemove, closeFileName;

		// If we haven't specified, close current tab
		if (!closeTabNum) {closeTabNum = top.ICEcoder.selectedTab};

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		okToRemove = true;
		if (!dontAsk && ICEcoder.savedPoints[closeTabNum-1]!=top.ICEcoder.getcMInstance(closeTabNum).changeGeneration()) {
			okToRemove = top.ICEcoder.ask(top.t['You have made...']);
		}

		if (okToRemove) {
			// Get the filename of tab we're closing
			closeFileName = top.ICEcoder.openFiles[closeTabNum-1];

			// recursively copy over all tabs & data from the tab to the right, if there is one
			for (var i=closeTabNum;i<ICEcoder.openFiles.length;i++) {
				top.get('tab'+i).innerHTML = top.get('tab'+(i+1)).innerHTML;
				top.get('tab'+i).title = top.get('tab'+(i+1)).title;
				ICEcoder.openFiles[i-1] = ICEcoder.openFiles[i];
				ICEcoder.openFileMDTs[i-1] = ICEcoder.openFileMDTs[i];
				ICEcoder.openFileVersions[i-1] = ICEcoder.openFileVersions[i];
			}
			// hide the instance we're closing by setting the hide class and removing from the array
			ICEcoder.content.contentWindow['cM'+top.ICEcoder.cMInstances[closeTabNum-1]].getWrapperElement().style.display = "none";
			ICEcoder.content.contentWindow['cM'+top.ICEcoder.cMInstances[closeTabNum-1]+'diff'].getWrapperElement().style.display = "none";
			top.ICEcoder.cMInstances.splice(closeTabNum-1,1);
			// clear the rightmost tab (or only one left in a 1 tab scenario) & remove from the array
			top.get('tab'+ICEcoder.openFiles.length).style.display = "none";
			top.get('tab'+ICEcoder.openFiles.length).innerHTML = "";
			top.get('tab'+ICEcoder.openFiles.length).title = "";
			ICEcoder.openFiles.pop();
			ICEcoder.openFileMDTs.pop();
			ICEcoder.openFileVersions.pop();
			// If we're closing the selected tab, determin the new selectedTab number, reduced by 1 if we have some tabs, 0 for a reset state
			if (ICEcoder.selectedTab==closeTabNum) {
				ICEcoder.openFiles.length>0 ? ICEcoder.selectedTab-=1 : ICEcoder.selectedTab = 0;
			}
			if (ICEcoder.openFiles.length>0 && ICEcoder.selectedTab==0) {ICEcoder.selectedTab=1};

			// grey out the view icon
			if (ICEcoder.openFiles.length==0) {
				top.ICEcoder.fMIconVis('fMView',0.3);
			} else {
				// Switch the mode & the tab
				ICEcoder.switchMode();
				ICEcoder.switchTab(ICEcoder.selectedTab);
			}
			// Highlight the selected tab after splicing the change state out of the array
			top.ICEcoder.savedPoints.splice(closeTabNum-1,1);
			top.ICEcoder.redoTabHighlight(ICEcoder.selectedTab);

			// Remove any highlighting from the file manager
			top.ICEcoder.selectDeselectFile('deselect',top.ICEcoder.filesFrame.contentWindow.document.getElementById(closeFileName.replace(/\//g,"|")));

			if (!dontSetPV) {
				top.ICEcoder.setPreviousFiles();
			}

			// Update the versions display
			top.ICEcoder.updateVersionsDisplay();

			// Update the title tag to indicate any changes
			top.ICEcoder.indicateChanges();
		}
		// Lastly, stop it from trying to also switch tab
		top.ICEcoder.canSwitchTabs=false;
		// and set the widths
		top.ICEcoder.setTabWidths('posOnlyNewTab');
		setTimeout(function() {top.ICEcoder.canSwitchTabs=true;},100);
	},

	// Close all tabs
	closeAllTabs: function() {
		if (top.ICEcoder.cMInstances.length>0 && ICEcoder.ask(top.t['Close all tabs'])) {
			for (var i=top.ICEcoder.cMInstances.length; i>0; i--) {
				top.ICEcoder.closeTab(i, i>1? true:false);
			}
		}		
		// Update the title tag to indicate any changes
		top.ICEcoder.indicateChanges();
	},

	// Set the tabs width
	setTabWidths: function(posOnlyNewTab) {
		var availWidth, avgWidth, tabWidth, lastLeft, lastWidth;

		if (top.ICEcoder.ready) {
			availWidth = parseInt(top.ICEcoder.content.style.width,10)-53-22-10; // - left margin - new tab - right margin
			avgWidth = (availWidth/top.ICEcoder.openFiles.length)-18;
			tabWidth = -18; // Incl 18px offset
			lastLeft = 53;
			lastWidth = 0;
			top.ICEcoder.tabLeftPos = [];
			for (var i=0;i<top.ICEcoder.openFiles.length;i++) {
				if (posOnlyNewTab) {i=top.ICEcoder.openFiles.length};
				tabWidth = top.ICEcoder.openFiles.length*(150+18) > availWidth ? parseInt(avgWidth*i,10) - parseInt(avgWidth*(i-1),10) : 150;
				lastLeft = i==0 ? 53 : parseInt(top.get('tab'+(i)).style.left,10);
				lastWidth = i==0 ? 0 : parseInt(top.get('tab'+(i)).style.width,10)+18;
				if (!posOnlyNewTab) {
					top.get('tab'+(i+1)).style.left = (lastLeft+lastWidth) + "px";
					top.get('tab'+(i+1)).style.width = tabWidth + "px";
				} else {
					tabWidth = -18;
				}
				top.ICEcoder.tabLeftPos.push(lastLeft+lastWidth);
			}
			top.get('newTab').style.left = (lastLeft+lastWidth+tabWidth+18) + "px";
		}
	},

	// Tab dragging start
	tabDragStart: function(tab) {
		top.ICEcoder.draggingTab = tab;
		top.ICEcoder.diffStartX = top.ICEcoder.mouseX;
		top.ICEcoder.tabDragMouseXStart = (top.ICEcoder.mouseX - (parseInt(top.ICEcoder.files.style.width,10)+53+18)) % 150;
		// Put tab we're dragging over others
		top.get('tab'+tab).style.zIndex = 2;
		// Set classes for other tabs (tabSlide) and the one we're dragging (tabDrag)
		for (var i=1; i<=top.ICEcoder.openFiles.length; i++) {
			top.get('tab'+i).className = i!==tab
			? "tab tabSlide"
			: "tab tabDrag";
		}
	},

	// Tab dragging
	tabDragMove: function() {
		var lastTabWidth, thisLeft, dragTabNo, tabWidth;

		lastTabWidth = parseInt(top.get('tab'+top.ICEcoder.openFiles.length).style.width,10)+18;

		// Set the left position but stay within left side (53) and new tab
		top.ICEcoder.thisLeft = thisLeft = top.ICEcoder.tabDragMouseX >= 53
		? top.ICEcoder.tabDragMouseX <= parseInt(top.get('newTab').style.left,10) - lastTabWidth
		? top.ICEcoder.tabDragMouseX : (parseInt(top.get('newTab').style.left,10) - lastTabWidth) : 53;

		top.get('tab'+top.ICEcoder.draggingTab).style.left = thisLeft + "px";

		top.ICEcoder.dragTabNo = dragTabNo = top.ICEcoder.draggingTab;

		// Set the opacities of tabs then positions of tabs we're not dragging
		for (var i=1; i<=top.ICEcoder.openFiles.length; i++) {
			top.get('tab'+i).style.opacity = i == top.ICEcoder.draggingTab ? 1 : 0.5;
			tabWidth = top.ICEcoder.tabLeftPos[i] ? top.ICEcoder.tabLeftPos[i] - top.ICEcoder.tabLeftPos[i-1] : tabWidth;
			if (i!=top.ICEcoder.draggingTab) {
				if (i < top.ICEcoder.draggingTab) {
					top.get('tab'+i).style.left = thisLeft <= top.ICEcoder.tabLeftPos[i-1]
					? top.ICEcoder.tabLeftPos[i-1]+tabWidth
					: top.ICEcoder.tabLeftPos[i-1];
				} else {
					top.get('tab'+i).style.left = thisLeft >= top.ICEcoder.tabLeftPos[i-1]
					? top.ICEcoder.tabLeftPos[i-1]-tabWidth
					: top.ICEcoder.tabLeftPos[i-1];
				}
			}
		}
	},

	// Tab dragging end
	tabDragEnd: function() {
		var swapWith, tempArray;

		// Set the tab widths
		top.ICEcoder.setTabWidths();
		// Determin what tabs we've swapped and reset classname, opacity & z-index for all
		for (var i=1; i<=top.ICEcoder.openFiles.length; i++) {
			if (top.ICEcoder.thisLeft >= top.ICEcoder.tabLeftPos[i-1]) {
				swapWith = top.ICEcoder.thisLeft == top.ICEcoder.tabLeftPos[0] ? 1 : top.ICEcoder.dragTabNo > i ? i+1 : i;
			}
			top.get('tab'+i).className = "tab";
			top.get('tab'+i).style.opacity = 1;
			if (i!=top.ICEcoder.dragTabNo) {
				top.get('tab'+i).style.zIndex = 1;
			} else {
				setTimeout(function() {
					top.get('tab'+i).style.zIndex = 1;
				},150);
			}
		}
		if (top.ICEcoder.thisLeft && top.ICEcoder.thisLeft!==false) {
			// Make a number ascending array
			tempArray = [];
			for (var i=1;i<=top.ICEcoder.openFiles.length;i++) {
				tempArray.push(i);
			}
			// Then swap our tab numbers
			tempArray.splice(top.ICEcoder.dragTabNo-1,1);
			tempArray.splice(swapWith-1,0,top.ICEcoder.dragTabNo);
			// Now we have an order to sort against
			ICEcoder.sortTabs(tempArray);
		}
		top.ICEcoder.setTabWidths();
		top.ICEcoder.draggingTab = false;
		top.ICEcoder.thisLeft = false;
	},

	// Sort tabs into new order
	sortTabs: function(newOrder) {
		var a, b, savedPoints = [], openFiles = [], openFileMDTs = [], openFileVersions = [], cMInstances = [], selectedTabWillBe;

		// Setup an array of our actual arrays and the blank ones
		a = [ICEcoder.savedPoints, ICEcoder.openFiles, ICEcoder.openFileMDTs, ICEcoder.openFileVersions, ICEcoder.cMInstances];
		b = [savedPoints, openFiles, openFileMDTs, openFileVersions, cMInstances];
		// Push the new order values into array b then set into array a
		for (var i=0;i<a.length;i++) {
			for (var j=0;j<a[i].length;j++) {
				b[i].push(a[i][newOrder[j]-1]);
			}
			a[i] = b[i];
		}
		// Begin swapping tab id's around to an ascending order and work out new selectedTab
		for (var i=0;i<newOrder.length;i++) {
			top.get('tab'+newOrder[i]).id = "tab" + (i+1) + ".temp";
			if (top.ICEcoder.selectedTab == newOrder[i]) {
				selectedTabWillBe = (i+1);
			}
		}
		// Now remove the .temp part from all tabs to get new ascending order
		for (var i=0;i<newOrder.length;i++) {
			top.get('tab'+(i+1)+'.temp').id = "tab"+(i+1);
		}
		// Set the classname for sliding
		if (top.get('tab'+selectedTabWillBe)) {
			top.get('tab'+selectedTabWillBe).className = "tab tabSlide";
		}
		// Finally, set the array values, tab widths and switch tab
		ICEcoder.savedPoints = a[0];
		ICEcoder.openFiles = a[1];
		ICEcoder.openFileMDTs = a[2];
		ICEcoder.openFileVersions = a[3];
		ICEcoder.cMInstances = a[4];
		top.ICEcoder.setTabWidths();
		top.ICEcoder.switchTab(selectedTabWillBe);
	},

	// Alphabetize tabs
	alphaTabs: function() {
		if (top.ICEcoder.openFiles.length>0) {
			var currentArray, currentArrayFull, alphaArray, nextValue, nextPos;
	
			currentArray = [];
			currentArrayFull = [];
			alphaArray = [];
			// Get filenames, full paths and set classname for sliding
			for (var i=0;i<top.ICEcoder.openFiles.length;i++) {
				currentArray.push(top.ICEcoder.openFiles[i].slice(top.ICEcoder.openFiles[i].lastIndexOf('/')+1));
				currentArrayFull.push(top.ICEcoder.openFiles[i]);
				top.get('tab'+(i+1)).className = "tab tabSlide";
			}
			// Get our next value, which is the next filename alpha lowest value and full path
			while (currentArray.length>0) {
				nextValue = currentArray[0];
				nextValueFull = currentArrayFull[0];
				nextPos = 0;
				for (var i=0;i<currentArray.length;i++) {
					if (currentArray[i] < nextValue) {
						nextValue  = currentArray[i];
						nextValueFull  = top.ICEcoder.openFiles[top.ICEcoder.openFiles.indexOf(currentArrayFull[i])];
						nextPos = i;
					}
				}
				// When we've got it, push into alphaArray and splice out of arrays
				alphaArray.push((top.ICEcoder.openFiles.indexOf(nextValueFull)+1));
				currentArray.splice(nextPos,1);
				currentArrayFull.splice(nextPos,1);
			}
			// Once done, sort our tabs into new order
			top.ICEcoder.sortTabs(alphaArray);
		}
	},

// ==============
// UI
// ==============

	// Detect keys/combos plus identify our area and set the vars, perform actions
	interceptKeys: function(area, evt) {
		var key, cM, cMdiff, thisCM;

		key = evt.keyCode ? evt.keyCode : evt.which ? evt.which : evt.charCode;

		// Mac command key handling (224 = Moz, 91/93 = Webkit Left/Right Apple)
		if (key==224 || key==91 || key==93) {
			top.ICEcoder.cmdKey = true;
		}

		// DEL (Delete file)
		if (key==46 && area == "files") {
			top.ICEcoder.deleteFiles();
	        	return false;
		};

		// Alt key down?
		if (evt.altKey) {
			// detect alt right
			var isAltRight	= (evt.ctrlKey||top.ICEcoder.cmdKey) ? true:false;

			// tag wrapper, add line break at end or focus on file manager
			if (
				(top.ICEcoder.tagWrapperCommand=="ctrl+alt" && isAltRight) // CTRL/Cmd + alt left + key || alt right + key
				|| (top.ICEcoder.tagWrapperCommand=="alt-left" && !isAltRight) // alt left + key
			) {
				if (area=="content") {
					if (key==68) {top.ICEcoder.tagWrapper('div'); return false;}
					else if (key==83) {top.ICEcoder.tagWrapper('span'); return false;}
					else if (key==80) {top.ICEcoder.tagWrapper('p'); return false;}
					else if (key==65) {top.ICEcoder.tagWrapper('a'); return false;}
					else if (key==49) {top.ICEcoder.tagWrapper('h1'); return false;}
					else if (key==50) {top.ICEcoder.tagWrapper('h2'); return false;}
					else if (key==51) {top.ICEcoder.tagWrapper('h3'); return false;}
					else if (key==13) {top.ICEcoder.addLineBreakAtEnd(); return false;}
					else if (key==37) {top.ICEcoder.filesFrame.contentWindow.focus();return false;}
					else {return key;}
				}
				// Focus on file manager (outside of content area) or last editor pane
				if (key==37) {top.ICEcoder.filesFrame.contentWindow.focus();return false;}
				else if (key==39) {top.ICEcoder.focus(top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? true : false);return false;}
				else {return key;}
			// Alt+Enter (Insert Line After)
			} else if (key==13) {
				top.ICEcoder.insertLineAfter();
				return false;
			} else {return key;}

		} else {

			// Shift+Enter (Insert Line Before)
			if(key==13 && evt.shiftKey) {
				top.ICEcoder.insertLineBefore();
	        		return false;

			// CTRL/Cmd+F (Find next)
			// and
			// CTRL/Cmd+G (Find previous)
			} else if((key==70||key==71) && (evt.ctrlKey||top.ICEcoder.cmdKey)) {
				var find = top.get('find');
				cM = ICEcoder.getcMInstance();
				cMdiff = ICEcoder.getcMdiffInstance();
				thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
				var selections = thisCM.getSelections();
				if (selections.length > 0){
					if (selections[0].length > 0){
						find.value = selections[0];
					}
				}
				find.select();
				// this is trick for Chrome - after you have used Ctrl-F once, when 
				// you try using Ctrl-F another time, somewhy Chrome still thinks, 
				// that find has focus and refuses to give it focus second time.
				top.get('goToLineNo').focus();
				find.focus();
				// Trigger the find/replace operation
				if(key==70) {
					// Find next
					top.get('findReplaceSubmit').click();
				} else {
					// Find previous
					ICEcoder.findReplace(top.document.getElementById('find').value,false,true,false,'findPrevious');
				}
	        		return false;

			// CTRL/Cmd+L (Go to line)
			} else if(key==76 && (evt.ctrlKey||top.ICEcoder.cmdKey)) {
				var goToLineInput = top.get('goToLineNo');
				goToLineInput.select();
				// this is trick for Chrome - after you have used Ctrl-F once, when
				// you try using Ctrl-F another time, somewhy Chrome still thinks,
				// that find has focus and refuses to give it focus second time.
				top.get('find').focus();
				goToLineInput.focus();
	        		return false;

			// CTRL/Cmd+I (Get info)
			} else if(key==73 && (evt.ctrlKey||top.ICEcoder.cmdKey) && area == "content") {
				top.ICEcoder.searchForSelected();
	        		return false;

			// CTRL/Cmd+right arrow (Next tab)
			} else if(key==39 && (evt.ctrlKey||top.ICEcoder.cmdKey) && area!="content") {
				top.ICEcoder.nextTab();
	        		return false;

			// CTRL/Cmd+left arrow (Previous tab)
			} else if(key==37 && (evt.ctrlKey||top.ICEcoder.cmdKey) && area!="content") {
				top.ICEcoder.previousTab();
	        		return false;

			// CTRL/Cmd+up arrow (Move line up)
			} else if(key==38 && (evt.ctrlKey||top.ICEcoder.cmdKey) && area=="content") {
				top.ICEcoder.moveLines('up');
	        		return false;

			// CTRL/Cmd+down arrow (Move line down)
			} else if(key==40 && (evt.ctrlKey||top.ICEcoder.cmdKey) && area=="content") {
				top.ICEcoder.moveLines('down');
	        		return false;

			// CTRL/Cmd+numeric plus (New tab)
			} else if((key==107 || key==187) && (evt.ctrlKey||top.ICEcoder.cmdKey)) {
				area=="content"
				? top.ICEcoder.duplicateLines()
				: top.ICEcoder.newTab();
	        		return false;

			// CTRL/Cmd+numeric minus (Close tab)
			} else if((key==109 || key==189) && (evt.ctrlKey||top.ICEcoder.cmdKey)) {
				area=="content"
				? top.ICEcoder.removeLines()
				: top.ICEcoder.closeTab(top.ICEcoder.selectedTab);
	        		return false;

			// CTRL/Cmd+S (Save), CTRL/Cmd+Shift+S (Save As)
			} else if(key==83 && (evt.ctrlKey||top.ICEcoder.cmdKey)) {
				if(evt.shiftKey) {
					top.ICEcoder.saveFile('saveAs');
				} else {
					top.ICEcoder.saveFile();
				}
	        		return false;

			// CTRL/Cmd+Enter (Open Webpage)
			} else if(key==13 && (evt.ctrlKey||top.ICEcoder.cmdKey) && top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1] != "/[NEW]") {
				top.ICEcoder.resetKeys(evt);
				window.open(top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1]);
	        		return false;

			// Enter (Expand dir/open file)
			} else if(key==13 && area=="files") {
				if(!evt.ctrlKey && !top.ICEcoder.cmdKey) {
					if (top.ICEcoder.selectedFiles.length == 0) {
						top.ICEcoder.overFileFolder('folder', '|');
						top.ICEcoder.selectFileFolder('init');
					}
					top.ICEcoder.fmAction(evt,'enter');
				}
				return false;

			// Up/down/left/right arrows (Traverse files)
			} else if((key==38||key==40||key==37||key==39) && area=="files") {
				if(!evt.ctrlKey && !top.ICEcoder.cmdKey) {
					if (top.ICEcoder.selectedFiles.length == 0) {
						top.ICEcoder.overFileFolder('folder', '|');
						top.ICEcoder.selectFileFolder('init');
					}
					top.ICEcoder.fmAction(evt,
						key==38 ?	'up' :
						key==40 ?	'down' :
						key==37 ?	'left' :
								'right');
				}
	        		return false;

			// CTRL/Cmd+O (Open Prompt)
			} else if(key==79 && (evt.ctrlKey||top.ICEcoder.cmdKey)) {
				top.ICEcoder.openPrompt();
	        		return false;

			// CTRL/Cmd+Space (Add snippet)
			} else if(key==32 && (evt.ctrlKey||top.ICEcoder.cmdKey) && area=="content") {
				top.ICEcoder.addSnippet();
	        		return false;

			// CTRL/Cmd+J (Jump to definition/back again)
			} else if(key==74 && (evt.ctrlKey||top.ICEcoder.cmdKey) && area=="content") {
				top.ICEcoder.jumpToDefinition();
	        		return false;

			// CTRL + Tab (lock/unlock file manager)
			} else if(key==223 && (evt.ctrlKey||top.ICEcoder.cmdKey)) {
				top.ICEcoder.lockUnlockNav();
				ICEcoder.changeFilesW(top.ICEcoder.lockedNav ? 'expand' : 'contract');
				return false;

			// CTRL + . (Fold/unfold current line)
			} else if(key==190 && (evt.ctrlKey||top.ICEcoder.cmdKey)) {
				cM = ICEcoder.getcMInstance();
				cMdiff = ICEcoder.getcMdiffInstance();
				thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
				var line = thisCM.getCursor().line;
				top.contentFrame.CodeMirror.doFold(thisCM.getLine(line).indexOf("{")>-1 ? "brace" : "xml",null,"+","-",false)(thisCM, line);
				
				return false;

			// ESC in content area (Comment/Uncomment line)
       			} else if(key==27 && area == "content") {
				cM = ICEcoder.getcMInstance();
				cMdiff = ICEcoder.getcMdiffInstance();
				thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;

				if (thisCM.getSelections().length > 1) {
					thisCM.execCommand("singleSelection");
				} else {
					top.ICEcoder.lineCommentToggle();
				}
	        		return false;

			// ESC not in content area (Cancel all actions)
	       		} else if(key==27 && area != "content") {
				top.ICEcoder.cancelAllActions();
		        	return false;

			// Any other key
			} else {
	        		return key;
        		}
        	}
	},

	// Reset the state of keys back to the normal state
	resetKeys: function(evt) {
		top.ICEcoder.cmdKey = false;
	}, 

	// Add snippet code completion
	addSnippet: function() {
		var cM, cMdiff, thisCM, lineNo, whiteSpace, content;

		// Get line content after trimming whitespace
		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		lineNo = thisCM.getCursor().line;
		whiteSpace = thisCM.getLine(lineNo).length - thisCM.getLine(lineNo).replace(/^\s\s*/, '').length;
		content = thisCM.getLine(lineNo).slice(whiteSpace);
		// function snippet
		if (content.slice(0,8)=="function") {
			top.ICEcoder.doSnippet('function','function VAR() {\nINDENT\tCURSOR\nINDENT}');
		// if snippet
		} else if (content.slice(0,2)=="if") {
			top.ICEcoder.doSnippet('if','if (CURSOR) {\nINDENT\t\nINDENT}');
		// for snippet
		} else if (content.slice(0,3)=="for") {
			top.ICEcoder.doSnippet('for','for (var i=0; i<CURSOR; i++) {\nINDENT\t\nINDENT}');
		}
	},

	// Action a snippet
	doSnippet: function(tgtString,replaceString) {
		var cM, cMdiff, thisCM, lineNo, lineContents, remainder, strPos, replacedLine, whiteSpace, curPos, sPos, lineNoCount;

		// Get line contents
		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		lineNo = thisCM.getCursor().line;
		lineContents = thisCM.getLine(lineNo);

		// Find our target string
		if (lineContents.indexOf(tgtString)>-1) {
			// Get text on the line from our target to the end
			remainder = thisCM.getLine(lineNo);
			strPos = remainder.indexOf(tgtString);
			remainder = remainder.slice(remainder.indexOf(tgtString)+tgtString.length+1);
			// Replace the function name if any
			replaceString = replaceString.replace(/VAR/g,remainder);
			// Get replaced string from start to our strPos
			replacedLine = thisCM.getLine(lineNo).slice(0,strPos);
			// Trim whitespace from start
			whiteSpace = thisCM.getLine(lineNo).length - thisCM.getLine(lineNo).replace(/^\s\s*/, '').length;
			whiteSpace = thisCM.getLine(lineNo).slice(0,whiteSpace);
			// Replace indent with whatever whitespace we have
			replaceString = replaceString.replace(/INDENT/g,whiteSpace);
			replacedLine += replaceString;
			// Get cursor position
			curPos = replacedLine.indexOf("CURSOR");
			sPos = 0;
			lineNoCount = lineNo;
			for (i=0;i<replacedLine.length;i++) {
				if (replacedLine.indexOf("\n",sPos)<replacedLine.indexOf("CURSOR")) {
					sPos = replacedLine.indexOf("\n",sPos)+1;
					lineNoCount = lineNoCount+1;
				}
			}
			// Clear the cursor string and set the cursor there
			thisCM.replaceRange(replacedLine.replace("CURSOR",""),{line:lineNo,ch:0},{line:lineNo,ch:1000000});
			thisCM.setCursor(lineNoCount,curPos);
			// Finally, focus on the editor
			top.ICEcoder.focus(top.ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? true : false);
		}
	}
};
