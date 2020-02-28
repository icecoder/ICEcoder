// Get any elem by ID
var get = function(elem) {
	return document.getElementById(elem);
};

var iceLoc = window.location.pathname;
// var iceDir = iceLoc.substring(0, iceLoc.lastIndexOf('/'));

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
	savedContents:		[],		// Array of last known saved contents
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
	mouseDownInCM:		false,		// If the mouse is down within CodeMirror instance (can be false, 'editor' or 'gutter')
	mouseDownMinimap:	false,		// If the mouse is down on Minimap nav box
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
	prevTab:		0,		// Previous tab to current
	serverQueueItems:	[],		// Array of URLs to call in order
	miniMapBoxTop: 		0,		// Top of the minimap box highlighter
	miniMapBoxHeight: 	0,		// Height of the minimap box highlighter
	previewWindow:		false,		// Target variable for the preview window
	previewWindowLoading:	false,		// Loading state of preview window
	pluginIntervalRefs:	[],		// Array of plugin interval refs
	overPopup:		false,		// Indicates if we're over a popup or not
	cmdKey:			false,		// Tracking apple Command key up/down state
	codeZoomedOut:		false,		// If true, code on non declaration lines is zoomed out
	showingTool:		false,		// Which tool is showing right now (terminal, output, database, git etc)
	oppTagReplaceData:	[],		// Will contain data for automatic opposite tag replacement to sync them
	fmReady:		false,		// Indicates if the file manager is ready for action
	bugReportStatus:	"off",		// Values of: off, error, ok, bugs
	bugReportPath:		"",		// Bug report file path
	bugFilesSizesSeen:	[],		// Array of last seen sizes of bug files
	bugFilesSizesActual:	[],		// Array of actual sizes of bug files
	githubDiff:		false,		// Toggle for viewing GitHub/FM diff view
	githubAuthTokenSet:	false,		// Has the user set their GitHub token yet
	splitPane:		false,		// Single or split pane editing
	splitPaneLeftPerc:	100,		// Width of left pane as a percentage
	renderLineStyle:	[],		// Array of styles to apply on renderLine event
	renderPaneShiftAmount:	0,		// Shift comparison main (negative) vs diff pane (positive)
	debounce:		"",		// Contains debounce timeout object
	editorFocusInstance:	"",		// Name of editor instance that has focus
	openSeconds:		0,		// Number of seconds ICEcoder has been open for
	indexing:		false,		// Indicates if ICEcoder is currently indexing
	ready:			false,		// Indicates if ICEcoder is ready for action

	// Set our aliases
	initAliases: function() {
		var aliasArray = ["header","files", "fileOptions", "optionsFile", "optionsEdit", "optionsSource", "optionsHelp", "filesFrame", "editor", "tabsBar", "findBar", "terminal", "output", "database", "git", "content", "tools", "footer", "nestValid", "versionsDisplay", "splitPaneControls", "splitPaneNamesMain", "splitPaneNamesDiff", "charDisplay", "byteDisplay", "docExplorer", "miniMap", "miniMapContainer", "miniMapContent", "functionClassList"];

		// Create our ID aliases
		for (var i=0;i<aliasArray.length;i++) {
			ICEcoder[aliasArray[i]] = get(aliasArray[i]);
		}
	},

	// On load, set the layout and check any nesting is valid
	init: function() {
		var screenIcon, sISrc;

		// Contract the file manager if the user has set to have it hidden
		if (!ICEcoder.lockedNav) {
			ICEcoder.filesW = ICEcoder.minFilesW;
		}

		// Set layout
		ICEcoder.setLayout();

		ICEcoder.overFileFolder('folder', '|');
		ICEcoder.selectFileFolder('init');
		ICEcoder.filesFrame.contentWindow.focus();

		// Hide the loading screen & auto open last files?
		ICEcoder.showHide('hide',get('loadingMask'));
		ICEcoder.autoOpenInt = setInterval(function() {
			if (ICEcoder.fmReady) {
				if (ICEcoder.openLastFiles) {ICEcoder.autoOpenFiles();};
				clearInterval(ICEcoder.autoOpenInt);
			}
		}, 4);

		// Update the nesting indicator every 30ms
		setInterval(ICEcoder.updateNestingIndicator,30);

		// Start bug checking
		ICEcoder.startBugChecking();

		// Set the time since last user interaction
		ICEcoder.autoLogoutTimer = 0;
		// Start our interval timer, runs every second
		ICEcoder.oneSecondInt = setInterval(function() {
			ICEcoder.autoLogoutTimer++;
			var unsavedFiles = false;
			// Check if we have any unsaved files
			for(var i=1;i<=ICEcoder.savedPoints.length;i++) {
				if (ICEcoder.savedPoints[i-1]!=ICEcoder.getcMInstance(i).changeGeneration()) {
					unsavedFiles = true;
				}
			}
			// Show an auto-logout warning 60 secs before a logout
			if(!unsavedFiles && ICEcoder.autoLogoutMins > 1 && ICEcoder.autoLogoutTimer == (ICEcoder.autoLogoutMins*60)-60) {
				ICEcoder.autoLogoutWarningScreen();
			}
			if (get('autoLogoutIFrame') && get('autoLogoutIFrame').contentWindow.document.getElementById('timeRemaning')) {
				get('autoLogoutIFrame').contentWindow.document.getElementById('timeRemaning').innerHTML =
					ICEcoder.autoLogoutTimer > 0
					? (ICEcoder.autoLogoutMins*60) - ICEcoder.autoLogoutTimer
					: 0;
			}
			// If there aren't any unsaved files, we have a timeout period > 0 and the time is up, we can logout
			if(!unsavedFiles && ICEcoder.autoLogoutMins > 0 && ICEcoder.autoLogoutTimer >= ICEcoder.autoLogoutMins*60) {
				ICEcoder.logout('autoLogout');
			}
			// Increase number of seconds ICEcoder has been open for by 1
			ICEcoder.openSeconds++;
			// Every 5 mins, ping our file to keep the session alive
			if (ICEcoder.openSeconds % 300 == 0) {
				ICEcoder.filesFrame.contentWindow.frames['pingActive'].location.href = iceLoc+"/lib/session-active-ping.php";
			}
			// Every 3 seconds, re-index if we're not already busy
			if (!ICEcoder.indexing && !ICEcoder.loadingFile && ICEcoder.serverQueueItems.length === 0 && ICEcoder.openSeconds % 3 == 0) {
				ICEcoder.indexing = true;
                		// Get new data
				var timestampExtra = ICEcoder.indexData
					? "?timestamp="+ICEcoder.indexData.timestamps.indexed+"&csrf="+ICEcoder.csrf
					: "";
				fetch(iceLoc+'/lib/indexer.php'+timestampExtra)
				    .then(function(response) {
				    // Convert to JSON
				    return response.json();
				}).then(function(data) {
					if (data.timestamps.changed) {
						ICEcoder.indexData = data;
						// If we have git diff data
						if (data.gitDiff) {
							ICEcoder.updateGitDiffPane();
						}
						// If we have git content data
						if (data.gitContent) {
							ICEcoder.highlightGitDiffs();
						}
					}
					ICEcoder.indexing = false;
				});
			}
		},1000);

		// ICEcoder is ready to start using
		ICEcoder.ready = true;
	},

// ==============
// LAYOUT
// ==============

	// Set our layout according to the browser size
	setLayout: function(dontSetEditor) {
		var winW, winH, headerH, fileNavH, tabsBarH, findBarH, toolsBarH;

		// Determin width & height available
		winW = window.innerWidth;
		winH = window.innerHeight;

		// Apply sizes to various elements of the page
		headerH = 25, fileNavH = 35, tabsBarH = 21, findBarH = 28, toolsBarH = 30;
		this.header.style.width = this.tabsBar.style.width = this.findBar.style.width = winW + "px";
		this.files.style.width = this.editor.style.left = this.filesW + "px";
		this.optionsFile.style.width = this.optionsEdit.style.width = this.optionsSource.style.width = this.optionsHelp.style.width = (this.filesW-60) + "px";
		this.filesFrame.style.height = (winH-headerH-fileNavH-toolsBarH) + "px";
		this.nestValid.style.left = (this.filesW+10) + "px";
		this.versionsDisplay.style.left = (this.filesW+25) + "px";
		this.splitPaneControls.style.left =
			parseInt(
				((winW-this.filesW)/2) +
				this.filesW -
				(get("splitPaneControls").getBoundingClientRect().width / 2)
			, 10) + "px";
		this.splitPaneNamesMain.style.left = (parseInt((winW-this.filesW)*0.25,10)-50+this.filesW) - 60 + "px";
		this.splitPaneNamesDiff.style.left = (parseInt((winW-this.filesW)*0.75,10)-50+this.filesW) - 135 + "px";
		ICEcoder.setTabWidths();

		// If we need to set the editor sizes
		if (!dontSetEditor) {
			this.editor.style.width = ICEcoder.content.style.width = (winW-this.filesW) - 200 + "px";
			ICEcoder.terminal.style.width = (winW-this.filesW) + "px";
			ICEcoder.output.style.width = (winW-this.filesW-31) + "px";
			ICEcoder.database.style.width = (winW-this.filesW) + "px";
			ICEcoder.git.style.width = (winW-this.filesW-31) + "px";
			ICEcoder.content.style.height = (winH-headerH-tabsBarH-findBarH-26) + "px";
			ICEcoder.terminal.style.height = winH + "px";
			ICEcoder.output.style.height = winH + "px";
			ICEcoder.database.style.height = winH + "px";
			ICEcoder.git.style.height = winH + "px";
			ICEcoder.terminal.style.top = winH + "px";
			ICEcoder.output.style.top = winH + "px";
			ICEcoder.database.style.top = winH + "px";
			ICEcoder.git.style.top = winH + "px";
			if (ICEcoder.showingTool !== false) {
				get(ICEcoder.showingTool).style.top = 0;
			}

			// Resize the CodeMirror instances to match the window size
			setTimeout(function(){
				for (var i=0;i<ICEcoder.openFiles.length;i++) {
					// Done the long way here as we need to call them in specific order to stop showing background and so avoiding a flicker effect
					if (!ICEcoder.splitPane) {
						ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]].setSize(ICEcoder.splitPaneLeftPerc+"%",ICEcoder.content.style.height);
					}
					ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]+'diff'].setSize((100-ICEcoder.splitPaneLeftPerc)+"%",ICEcoder.content.style.height);
					ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]+'diff'].getWrapperElement().style.left = ICEcoder.splitPaneLeftPerc+"%";
					if (ICEcoder.splitPane) {
						ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]].setSize(ICEcoder.splitPaneLeftPerc+"%",ICEcoder.content.style.height);
					}
				}
				// Set height of docExplorer
				this.docExplorer.style.height = ICEcoder.content.style.height;
				// Place resultsBar to edge of pane or it's scrollbar
				if (!ICEcoder.splitPane) {
					ICEcoder.content.contentWindow.document.getElementById('resultsBar').style.right = !ICEcoder.scrollBarVisible ? "0" : "17px";
				} else {
					ICEcoder.content.contentWindow.document.getElementById('resultsBar').style.right = !ICEcoder.scrollBarVisible ? parseInt(parseInt(ICEcoder.content.style.width,10)/2,10)+"px" : (parseInt(parseInt(ICEcoder.content.style.width,10)/2,10)+17)+"px";
				}
			},4);
		}
	},

	// Set the layout as split pane or not
	setSplitPane: function(onOff) {
		var cM, cMdiff;

		ICEcoder.splitPane = onOff == "on" ? true : false;
		get('splitPaneControlsOff').style.opacity = ICEcoder.splitPane ? 0.2 : 0.5;
		get('splitPaneControlsOn').style.opacity = ICEcoder.splitPane ? 0.5 : 0.2;
		get('splitPaneNamesMain').style.opacity = get('splitPaneNamesDiff').style.opacity = ICEcoder.splitPane ? 1 : 0;
		ICEcoder.setLayout();

		// Also clear marks (if going to a single pane) or redo the marks (if split pane)
		if (ICEcoder.splitPane) {
			ICEcoder.updateDiffs();
			// Also set the scroll position to match
			cM = ICEcoder.getcMInstance();
			ICEcoder.cMonScroll(cM,'cM'+ICEcoder.cMInstances[ICEcoder.selectedTab-1]);
		} else {
			cM = ICEcoder.getcMInstance();
			cMdiff = ICEcoder.getcMdiffInstance();

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
		}

		// Animate in/out the split pane
		// First, clear any existing split pane interval anim
		if ("undefined" != typeof ICEcoder.animSplitPaneInt) {
			clearInterval(ICEcoder.animSplitPaneInt);
		}
		// Now set the interval to animate it in/out
		ICEcoder.animSplitPaneInt = setInterval(function() {
			// Animate split pane in
			if (ICEcoder.splitPane && ICEcoder.splitPaneLeftPerc > 50.1) {
				ICEcoder.splitPaneLeftPerc = ((ICEcoder.splitPaneLeftPerc-50)/1.8)+50;
			// Animate split pane out
			} else if (!ICEcoder.splitPane && ICEcoder.splitPaneLeftPerc < 99.9) {
				ICEcoder.splitPaneLeftPerc = (50-((100-ICEcoder.splitPaneLeftPerc)/1.8))+50;
			// Finish animating split pane in/out
			} else {
				ICEcoder.splitPaneLeftPerc = ICEcoder.splitPane ? 50 : 100;
				clearInterval(ICEcoder.animSplitPaneInt);
			}
			ICEcoder.setLayout();
		},4);
	},

	// Tool show/hide toggle
	toolShowHideToggle: function(tool) {
		var winH;

		winH = window.innerHeight;

		if (["terminal","output","database","git"].indexOf(tool) > -1) {
			// Set out of view as a start point
			get('terminal').style.top = winH + "px";
			get('output').style.top = winH + "px";
			get('database').style.top = winH + "px";
			get('git').style.top = winH + "px";

			// Now set tool requested, out of view, or in view
			get(tool).style.top = ICEcoder.showingTool === tool ? winH + "px" : 0;

			// Carry out any extras...
			if (tool === "terminal") {
				// Focus on command prompt
				setTimeout(function(){
					ICEcoder.terminal.contentWindow.document.getElementById('command').focus();
				},0);
			}

			// Note which tool we're showing
			ICEcoder.showingTool = ICEcoder.showingTool !== tool ? tool : false;
		}
	},

	// Doc Explorer show item
	docExplorerShow: function(item) {
		var cM;

		get('miniMap').style.display = item == "miniMap" ? 'block' : 'none';
		get('functionClassList').style.display = item == "functionClassList" ? 'block' : 'none';
		if (item == "miniMap") {
			miniMapInt = setInterval(function(){
				if (get('miniMapContent').getBoundingClientRect().height != 0) {
					cM = ICEcoder.getcMInstance();
					ICEcoder.setMinimapLayout(cM);
					clearInterval(miniMapInt);
				}
			},10);
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
		if (ICEcoder.ready && document.body.style.cursor == "w-resize") {
			// If our mouse is down (and went down on the CM instance's gutter) and we're within a 250-400px range
			if (ICEcoder.mouseDown && ICEcoder.mouseDownInCM == "gutter") {
				ICEcoder.filesW = ICEcoder.maxFilesW = ICEcoder.mouseX >=250 && ICEcoder.mouseX <= 400
					? ICEcoder.mouseX : ICEcoder.mouseX <250 ? 250 : 400;
				// Set various widths based on the new width
				ICEcoder.files.style.width = ICEcoder.filesFrame.style.width = ICEcoder.filesW + "px";
				ICEcoder.setLayout();
				ICEcoder.draggingFilesW = true;
			}
		} else {
			ICEcoder.draggingFilesW = false;
		}
	},

	// Lock & unlock the file manager navigation on demand
	lockUnlockNav: function() {
		var lockIcon;

		lockIcon = ICEcoder.filesFrame.contentWindow.document.getElementById('fmLock');
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

	// On focus
	cMonFocus: function(thisCM,cMinstance) {
		ICEcoder.getCaretPosition();
		ICEcoder.updateCharDisplay();
		ICEcoder.updateByteDisplay();
		ICEcoder.editorFocusInstance = cMinstance;
		ICEcoder.getCaretPosition();
	},

	// On blur
	cMonBlur: function(thisCM,cMinstance) {
		// Nothing as yet
	},

	// On key up
	cMonKeyUp: function(thisCM,cMinstance) {
		if ("undefined" != typeof doFind) {
			clearInterval(doFind);
		}
		doFind = setTimeout(function() {
			ICEcoder.findReplace(get('find').value,true,false);
		},500);
		ICEcoder.getCaretPosition();
		ICEcoder.updateCharDisplay();
		ICEcoder.updateByteDisplay();
	},

	// On cursor activity
	cMonCursorActivity: function(thisCM,cMinstance) {
		var thisCMPrevLine;

		ICEcoder.getCaretPosition();
		ICEcoder.updateCharDisplay();
		ICEcoder.updateByteDisplay();
		thisCM.removeLineClass(ICEcoder['cMActiveLine'+cMinstance], "background");
		if(thisCM.getCursor('start').line == thisCM.getCursor().line) {
			ICEcoder['cMActiveLine'+cMinstance] = thisCM.addLineClass(thisCM.getCursor().line, "background","cm-s-activeLine");
		}
		if (ICEcoder.caretLocType=="CSS") {
			ICEcoder.cssColorPreview();
		}

		thisCMPrevLine = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? ICEcoder.prevLineDiff : ICEcoder.prevLine;
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
			for (var i=0; i<ICEcoder.renderLineStyle.length; i++) {

				// We have no matching pane to start with
				paneMatch = false;

				// Is the pane we need to set the cursor on this pane?
				if (
					(ICEcoder.renderLineStyle[i][0] != "diff" && cMinstance.indexOf("diff") == -1) ||
					(ICEcoder.renderLineStyle[i][0] == "diff" && cMinstance.indexOf("diff") > -1)
				)
				{paneMatch = true;}

				// If the pane matches & also the line we're on is the line we have a style set for, set that cursor height
				if (paneMatch && thisCM.getCursor().line+1 == ICEcoder.renderLineStyle[i][1]) {
					thisCM.setOption("cursorHeight",thisCM.defaultTextHeight() / thisCM.lineInfo(thisCM.getCursor().line).handle.height);
				} else {
					thisCM.setOption("cursorHeight",1);
				}

			}
		},0);
	},

	// On before change
	cMonBeforeChange: function(thisCM,cMinstance,changeObj,cM) {
		var sels, tagInfo, tagOpp, thisData;

		// Get the selections
		sels = thisCM.listSelections();

		// For each of the user selections
		for (var i=0; i<sels.length; i++) {
			// Get the matching tagInfo for current cursor position
			tagInfo = cM.findMatchingTag(thisCM, sels[i].anchor);
			// If we're not ending a tag (autocompletion) and we have tagInfo and not undoing/redoing (which handles changes itself)
			if (changeObj.text[0].indexOf(">") !== 0 && "undefined" != typeof tagInfo && changeObj.origin != "undo" && changeObj.origin != "redo") {
				// If we also have both open and close tag info
				if ("undefined" != typeof tagInfo.open && "undefined" != typeof tagInfo.close) {
					// Log the opposite tag info
					tagOpp = tagInfo.at == "open" ? "close" : "open";
					if (tagInfo[tagOpp] !== null) {
						thisData = tagInfo[tagOpp].tag + ";" + tagInfo[tagOpp].from.line + ":" + tagInfo[tagOpp].from.ch;
						// Check that string firstly isn't in array and if not, push it in
						if (ICEcoder.oppTagReplaceData.indexOf(thisData) == -1) {
							ICEcoder.oppTagReplaceData.push(thisData);
						}
					}
				}
			}
		}
	},

	// On change
	cMonChange: function(thisCM,cMinstance,changeObj,cM) {
		var sels, rData, theTag, thisLine, thisChar, tagInfo, charDiff, closeDiff, repl1, repl2, thisToken, tTS, filepath, filename, fileExt;

		// Get the selections
		sels = thisCM.listSelections();

		// If we're not loading the file, it's a change, so update tab
		if (!ICEcoder.loadingFile) {
			ICEcoder.redoTabHighlight(ICEcoder.selectedTab);
		// File load needs to force a simple change to get minimap functional
		} else {
			setTimeout(function(){
				thisCM.replaceRange('X',{line: 1, ch: 1},{line: 1, ch: 1});
				thisCM.undo();
				thisCM.clearHistory();
				ICEcoder.savedPoints[ICEcoder.selectedTab-1] = thisCM.changeGeneration();
				ICEcoder.savedContents[ICEcoder.selectedTab-1] = thisCM.getValue();
			},0);

		}

		// Detect if we have a scrollbar & set layout again
		setTimeout(function() {
			ICEcoder.scrollBarVisible = thisCM.getScrollInfo().height > thisCM.getScrollInfo().clientHeight;
			ICEcoder.setLayout();
		},0);

		// If we're replacing opposite tag strings, do that
		if ("undefined" != typeof ICEcoder.oppTagReplaceData[0]) {
			// For each one of them, grab our data to work with
			for (var i=0; i<ICEcoder.oppTagReplaceData.length; i++) {
				// Extract data from that string
				rData = ICEcoder.oppTagReplaceData[i].split(";");
				theTag = rData[0];
				thisLine = rData[1].split(":")[0]*1;
				thisChar = rData[1].split(":")[1]*1;

				// Get the tag info for matching tag
				if (sels[i]) {
					tagInfo = cM.findMatchingTag(thisCM, sels[i].anchor);
				}

				// If we have tagInfo
				if ("undefined" != typeof tagInfo) {
					// Get the opposite tag string
					theTag = tagInfo.at == "open" ? tagInfo.open.tag : tagInfo.close.tag;
					// If we have changeObj.from info to work with
					if ("undefined" != typeof changeObj.from) {
						// Same line changing needs a chararacter pos shift
						charDiff = thisLine == changeObj.from.line
							? changeObj.text[0].length - changeObj.removed[0].length
							: 0;
						// Also need to adjust if we're in the close tag on same line
						closeDiff = tagInfo.at == "close" && thisLine == changeObj.from.line
							? changeObj.removed[0].length - changeObj.text[0].length + 1
							: 1
						// Work out the replace from and to positions
						repl1 = {line: thisLine, ch: thisChar+charDiff+(tagInfo.at == "open" ? 2 : closeDiff)};
						repl2 = {line: thisLine, ch: thisChar+charDiff+(tagInfo.at == "open" ? 2 : closeDiff)+rData[0].length};
					}
				}

				// Replace our string over the range, if this token string isn't blank and the end tag matches our original tag
				if (theTag.trim() != "" && "undefined" != typeof repl1 && "undefined" != typeof repl2 && thisCM.getRange(repl1,repl2) == rData[0]) {
					thisCM.replaceRange(theTag, repl1, repl2);
					// If at the close tag, don't autocomplete
					if (tagInfo.at == "close") {
						ICEcoder.autocompleteSkip = true;
					}
				}

			}

		}
		// Reset our array for next time
		ICEcoder.oppTagReplaceData = [];

		ICEcoder.getCaretPosition();
		ICEcoder.updateCharDisplay();
		ICEcoder.updateByteDisplay();
		ICEcoder.updateNestingIndicator();
		if (ICEcoder.findMode) {
			ICEcoder.results.splice(ICEcoder.findResult,1);
			get('results').innerHTML = ICEcoder.results.length + " " + t['results'];
			ICEcoder.findMode = false;
		}
		// Update the list of functions and classes
		ICEcoder.updateFunctionClassList();
		// Update the minimap nav
		if ("undefined" != typeof doMiniNav) {
			clearTimeout(doMiniNav);
		}
		if (ICEcoder.loadingFile) {
			// Load event means set it straight away
			ICEcoder.setMinimap();
		} else {
			// Update event means do it after 1 sec of inactivity
			doMiniNav = setTimeout(function() {
				ICEcoder.setMinimap();
			},1000);
		}
		filepath = ICEcoder.openFiles[ICEcoder.selectedTab-1];
		if (filepath) {
			filename = filepath.substr(filepath.lastIndexOf("/")+1);
			fileExt = filename.substr(filename.lastIndexOf(".")+1);
		}
		// Update diffs if we have a split pane
		if (ICEcoder.splitPane) {
			// Need 0ms tickover so we handle char change first
			setTimeout(function(){ICEcoder.updateDiffs();},0);
		}

		// Highlight Git diff colors in gutter
		if (ICEcoder.indexData) {
			ICEcoder.highlightGitDiffs();
		}

		// Update HTML edited files live
		if (filepath && ICEcoder.previewWindow.location && filepath != "/[NEW]") {
			ICEcoder.updatePreviewWindow(thisCM,filepath,filename,fileExt);
		}
		// Update the title tag to indicate any changes
		ICEcoder.indicateChanges();
	},

	cMonUpdate: function(thisCM,cMinstance) {
		// Update the minimap background to match theme
		setTimeout(function() {
			get('docExplorer').style.background = window.getComputedStyle(thisCM.getWrapperElement(),null).getPropertyValue('background');
		},0);
		// Set the Minimap layout
		ICEcoder.setMinimapLayout(thisCM,cMinstance);
	},

	// On scroll
	cMonScroll: function(thisCM,cMinstance) {
		var cM, cMdiff, otherCM;

		ICEcoder.mouseDown=false;
		ICEcoder.mouseDownInCM=false;

		if (ICEcoder.splitPane) {
			// Get both main & diff instance and work out the instance we're not scrolling
			cM = ICEcoder.getcMInstance();
			cMdiff = ICEcoder.getcMdiffInstance();
			otherCM = cMinstance.indexOf('diff') > -1 ? cM : cMdiff;

			if (cM) {
				// Scroll other pane x & y to match this one we're scrolling, after a 0ms tickover to avoid judder
				setTimeout(function(){otherCM.scrollTo(thisCM.getScrollInfo().left, thisCM.getScrollInfo().top);},0);
			}
		}
		// Set the Minimap layout
		ICEcoder.setMinimapLayout(thisCM,cMinstance);
	},

	// On input read
	cMonInputRead: function(thisCM,cMinstance) {
		if (ICEcoder.autoComplete == "keypress" && ICEcoder.codeAssist) {
			// Debounce timeout wrapper left here for now, but can be removed in future if no negative effects seen
			// clearTimeout(ICEcoder.debounce);
			if (!thisCM.state.completionActive) {
				// ICEcoder.debounce = setTimeout(function() {
					if (!ICEcoder.autocompleteSkip) {
						ICEcoder.autocomplete();
					} else {
						ICEcoder.autocompleteSkip = false;
					}
				// },0);
			}
		}
	},

	// On gutter click
	cMonGutterClick: function(thisCM,line,gutter,evt,cMinstance) {
		ICEcoder.mouseDownInCM = "gutter";
	},

	// On mouse down
	cMonMouseDown: function(thisCM,cMinstance,evt) {
		ICEcoder.mouseDownInCM = "editor";
	},

	// On context menu
        cMonContextMenu: function(thisCM,cMinstance,evt) {
            // Set cursor
            var currCoords = thisCM.coordsChar({left: evt.pageX, top: evt.pageY});
            thisCM.setCursor(currCoords);

            // If CTRL key down
            if (evt.ctrlKey) {
                setTimeout(function() {
                    // Get cM and word under mouse pointer
                    var cM = thisCM;
                    var word = (cM.getRange(cM.findWordAt(cM.getCursor()).anchor, cM.findWordAt(cM.getCursor()).head));

                    // Get result and number of results for word in functions and classes from index JSON object list
                    var result = null;
                    var numResults = 0;
                    var filePath = ICEcoder.openFiles[ICEcoder.selectedTab-1];
	            var filePathExt = filePath.substr(filePath.lastIndexOf(".")+1);
                    for(i in ICEcoder.indexData.functions[filePathExt]) {
                        if (i === word) {
                            result = ICEcoder.indexData.functions[filePathExt][i];
                            numResults++;
                        }
                    };
                    for(i in ICEcoder.indexData.classes[filePathExt]) {
                        if (i === word) {
                            result = ICEcoder.indexData.classes[filePathExt][i];
                            numResults++;
                        }
                    };

                    // If we have a single result and the cursor isn't already on the definition of it we can jump to where it's defined
                    if (numResults === 1 && [null,"def"].indexOf(cM.getTokenTypeAt(cM.getCursor())) === -1) {
                        ICEcoder.openFile(result.filePath.replace(docRoot,""));
                        ICEcoder.goFindAfterOpenInt = setInterval(function(result){
                            if (ICEcoder.openFiles[ICEcoder.selectedTab-1] == result.filePath.replace(docRoot,"") && !ICEcoder.loadingFile) {
                                cM = ICEcoder.getcMInstance();
				setTimeout(function(result) {
					ICEcoder.goToLine(result.range.from.line+1);
					cM.setSelection({line: result.range.from.line, ch: result.range.from.ch}, {line: result.range.to.line, ch: result.range.to.ch});
				},20,result);
                                clearInterval(ICEcoder.goFindAfterOpenInt);
                            }
                        },20,result);
                    }

                    ICEcoder.mouseDownInCM = "editor";
                },0);
            }
        },

	// On drag over
	cMonDragOver: function(thisCM,evt,cMinstance) {
		ICEcoder.setDragCursor(evt,'editor');
	},

	// On render line
	cMonRenderLine: function(thisCM,cMinstance,line,element) {
		var paneMatch;

		// Loop through styles to use when rendering lines
		for (var i=0; i<ICEcoder.renderLineStyle.length; i++) {

			// We have no matching pane to start with
			paneMatch = false;

			// Is the pane we need to style this pane?
			if (
				(ICEcoder.renderLineStyle[i][0] != "diff" && cMinstance.indexOf("diff") == -1) ||
				(ICEcoder.renderLineStyle[i][0] == "diff" && cMinstance.indexOf("diff") > -1)
			)
			{paneMatch = true;}

			// If the pane matches & also the line we're rendering is the line we have a style set for, set that style
			if (paneMatch && thisCM.lineInfo(line).line+1 == ICEcoder.renderLineStyle[i][1]) {
				element.style[ICEcoder.renderLineStyle[i][2]] = ICEcoder.renderLineStyle[i][3];
			}

		}
	},

	// Show function args tooltip
	functionArgsTooltip: function(e, area) {
	    if (ICEcoder.indexData) {
		// If we have no files open, return early
                if (ICEcoder.openFiles.length === 0) {
                    get('tooltip').style.display = "none";
                    return true;
                }

            	var i;
                // Get cM instance, and the word under mouse pointer
                var cM = ICEcoder.getcMInstance();
                var coordsChar = cM.coordsChar({left: ICEcoder.mouseX-ICEcoder.maxFilesW, top: ICEcoder.mouseY-72});
                var word = (cM.getRange(cM.findWordAt(coordsChar).anchor, cM.findWordAt(coordsChar).head));

		// If it's not a word, return early
		if (word === "") {
			get('tooltip').style.display = "none";
			return true;
		}

                // Get result and number of results for word in functions from index JSON object list
                var result = null;
                var numResults = 0;
                var filePath = ICEcoder.openFiles[ICEcoder.selectedTab-1];
                var filePathExt = filePath.substr(filePath.lastIndexOf(".")+1);
                for(i in ICEcoder.indexData.functions[filePathExt]) {
                    if (i === word) {
                        result = ICEcoder.indexData.functions[filePathExt][i];
                        numResults++;
                    }
                };

                // If we have a single result and the mouse pointer is not over the definition of it (that would be pointless), show tooltip
                if (numResults === 1 && [null,"def"].indexOf(cM.getTokenTypeAt(coordsChar)) === -1) {
                    get('tooltip').style.display = "block";
                    get('tooltip').style.left = (ICEcoder.mouseX-ICEcoder.maxFilesW+10) + "px";
                    get('tooltip').style.top = (ICEcoder.mouseY-30) + "px";
                    get('tooltip').style.zIndex = "1";
                    get('tooltip').innerHTML = result.params;
                // Else hide it
                } else {
                    get('tooltip').style.display = "none";
                }
            }
	},

	// Update diffs shown to the user in each pane
	updateDiffs: function() {
		var cM, cMdiff, mainText, diffText, sm, opcodes, cMmarks, cMdiffMarks, amt, sDiffs;

		// Reset the style array container and main vs diff pane shift difference
		ICEcoder.renderLineStyle = [];
		ICEcoder.renderPaneShiftAmount = 0;

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();

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
						amt = ((opcodes[i][4] - opcodes[i][2] + 1 + ICEcoder.renderPaneShiftAmount) * cM.defaultTextHeight());
						// Add on the extra heights for any wrapped lines
						for (var j=opcodes[i][4]-1; j<=opcodes[i][2]-1; j++) {
							if (cMdiff.getLineHandle(j).height > cM.defaultTextHeight()) {
								amt += cMdiff.getLineHandle(j).height - cM.defaultTextHeight();
							}
						}
						// If we have an height greater than the default text height, add a new style
						if (amt > cM.defaultTextHeight()) {
							ICEcoder.renderLineStyle.push(["main", opcodes[i][2], "height", amt + "px"]);
						}
						// Mark text in 2 colours, for each line
						for (var j=0; j<(opcodes[i][2]) - (opcodes[i][1]); j++)  {
							sDiffs = (ICEcoder.findStringDiffs(cM.getLine(opcodes[i][1]+j),cMdiff.getLine(opcodes[i][3]+j)));
							cM.markText({line: opcodes[i][1]+j, ch: 0}, {line: opcodes[i][3]+j + ICEcoder.renderPaneShiftAmount, ch: sDiffs[0]}, {className: "diffGreyLighter"});
							cM.markText({line: opcodes[i][1]+j, ch: sDiffs[0]}, {line: opcodes[i][3]+j + ICEcoder.renderPaneShiftAmount, ch: sDiffs[0]+sDiffs[1]}, {className: "diffGrey"});
							cM.markText({line: opcodes[i][1]+j, ch: sDiffs[0]+sDiffs[1]}, {line: opcodes[i][3]+j + ICEcoder.renderPaneShiftAmount, ch: 1000000}, {className: "diffGreyLighter"});
						}
					// Inserting
					} else {
						cM.markText({line: opcodes[i][1], ch: 0}, {line: opcodes[i][2]-1, ch: 1000000}, {className: "diffGreen"});
					}

					// If inserting or deleting and the main pane hasn't changed, we need to pad out the line in that pane
					if (opcodes[i][0] != "replace" && opcodes[i][1] == opcodes[i][2]) {
						ICEcoder.renderLineStyle.push(["main", opcodes[i][2], "height", ((opcodes[i][4] - opcodes[i][3] + 1) * cM.defaultTextHeight()) + "px"]);
						// Mark the range with empty class
						cM.markText({line: opcodes[i][2]-1, ch: 0}, {line: opcodes[i][2]-1, ch: 1000000}, {className: "diffNone"});
					}

					// =========
					// DIFF PANE
					// =========

					// Replacing? Pad out diff pane line to match equivalent last line in main pane
					if (opcodes[i][0] == "replace") {
						// Line amount is diff between end of both panes at this point in our loop, plus 1 line and our overall document shift, multiplied by font size
						amt = ((opcodes[i][2] - opcodes[i][4] + 1 - ICEcoder.renderPaneShiftAmount) * cM.defaultTextHeight());
						// Add on the extra heights for any wrapped lines
						for (var j=opcodes[i][4]-1; j<=opcodes[i][2]-1; j++) {
							if (cM.getLineHandle(j).height > cM.defaultTextHeight()) {
								amt += cM.getLineHandle(j).height - cM.defaultTextHeight();
							}
						}
						// If we have an height greater than the default text height, add a new style
						if (amt > cM.defaultTextHeight()) {
							ICEcoder.renderLineStyle.push(["diff", opcodes[i][4], "height", amt + "px"]);
						}
						// Mark text in 2 colours, for each line
						for (var j=0; j<(opcodes[i][4]) - (opcodes[i][3]); j++)  {
							sDiffs = (ICEcoder.findStringDiffs(cM.getLine(opcodes[i][1]+j),cMdiff.getLine(opcodes[i][3]+j)));
							cMdiff.markText({line: opcodes[i][1]+j - ICEcoder.renderPaneShiftAmount, ch: 0}, {line: opcodes[i][3]+j, ch: sDiffs[0]}, {className: "diffGreyLighter"});
							cMdiff.markText({line: opcodes[i][1]+j - ICEcoder.renderPaneShiftAmount, ch: sDiffs[0]}, {line: opcodes[i][3]+j, ch: sDiffs[0]+sDiffs[2]}, {className: "diffGrey"});
							cMdiff.markText({line: opcodes[i][1]+j - ICEcoder.renderPaneShiftAmount, ch: sDiffs[0]+sDiffs[2]}, {line: opcodes[i][3]+j, ch: 1000000}, {className: "diffGreyLighter"});
						}
					// Deleting
					} else {
						cMdiff.markText({line: opcodes[i][3], ch: 0}, {line: opcodes[i][4]-1, ch: 1000000}, {className: "diffRed"});
					}

					// If inserting or deleting and the diff pane hasn't changed, we need to pad out the line in that pane
					if (opcodes[i][0] != "replace" && opcodes[i][3] == opcodes[i][4]) {
						ICEcoder.renderLineStyle.push(["diff", opcodes[i][4], "height", ((opcodes[i][2] - opcodes[i][1] + 1) * cM.defaultTextHeight()) + "px"]);
						// Mark the range with empty class
						cMdiff.markText({line: opcodes[i][4]-1, ch: 0}, {line: opcodes[i][4]-1, ch: 1000000}, {className: "diffNone"});
					}

					// Finally, set the last amount shifted for this change
					ICEcoder.renderPaneShiftAmount = (opcodes[i][2] - opcodes[i][4]);
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

	// Highlight git diffs (between what is in browser and in Git commits)
	highlightGitDiffs: function() {
		// Clear the timeout if we have one already
		if ("undefined" != typeof highlightGitDiffTimeout) {
			clearTimeout(highlightGitDiffTimeout);
		}
		// If we have index data & Git data, after a timeout, if we have a matching path in that Git data
		if (ICEcoder.indexData && ICEcoder.indexData.gitContent) {
			highlightGitDiffTimeout = setTimeout(function() {
				if (ICEcoder.indexData.gitContent[docRoot+ICEcoder.openFiles[ICEcoder.selectedTab-1]]) {
					// Get the CodeMirror instance and clear the gutter for it
					cM = ICEcoder.getcMInstance();
					cM.clearGutter("CodeMirror-linenumbers");

					// Get the baseText and gitText values from the two sources, and split them into lines
					var mainText = cM ? difflib.stringAsLines(cM.getValue()) : "";
					var gitText = difflib.stringAsLines(ICEcoder.indexData.gitContent[docRoot+ICEcoder.openFiles[ICEcoder.selectedTab-1]].lastHashContent);

					// Create a SequenceMatcher instance that diffs the two sets of lines
					var sm = new difflib.SequenceMatcher(gitText, mainText);

					// Get the opcodes from the SequenceMatcher instance
					// Opcodes is a list of 3-tuples describing what changes should be made to the base text in order to yield the new text
					var opcodes = sm.get_opcodes();

					// For each opcode returned by jsdifflib
					for (var i=0; i<opcodes.length; i++) {
						// If not 'equal' status for the section, we have a 'replace', 'delete' or 'insert' status, so do something
						if (opcodes[i][0] !== "equal") {
							// Replacing
							if (opcodes[i][0] == "replace") {
								// Mark text in one of 2 colours, for each line
								for (var j=opcodes[i][3]; j<opcodes[i][4]; j++)  {
									var elem = document.createElement("DIV");
									elem.className="CodeMirror-linenumber";
									// Only whitespace is different, grey
									if (gitText[j-(opcodes[i][4]-opcodes[i][2])] && mainText[j].trim() === gitText[j-(opcodes[i][4]-opcodes[i][2])].trim()) {
										elem.style.background = "#888";
									// Something other than whitespace is different, orange
									} else {
										elem.style.background = "#f80";
									}
									elem.style.color = "#111";
									elem.innerHTML = j+1;
									cM.setGutterMarker(j, "CodeMirror-linenumbers", elem);
								}
							// Inserting
							} else if (opcodes[i][0] == "insert") {
								// Mark text in green for each line
								for (var j=opcodes[i][3]; j<opcodes[i][4]; j++)  {
									var elem = document.createElement("DIV");
									elem.className="CodeMirror-linenumber";
									elem.style.background = "#080";
									elem.style.color = "#fff";
									elem.innerHTML = j+1;
									cM.setGutterMarker(j, "CodeMirror-linenumbers", elem);
								}
							// Deleting
							} else {
								// Add a red line to indicate where lines where deleted
								var elem = document.createElement("DIV");
								elem.className="CodeMirror-linenumber";
								// If we haven't deleted content at end, line is above numbers
								if (cM.lineCount() > opcodes[i][3]) {
									elem.style.borderTop = "solid #b00 1px";
									elem.innerHTML = opcodes[i][3]+1;
									cM.setGutterMarker(opcodes[i][3], "CodeMirror-linenumbers", elem);
								// Otherwise, line is below last number
								} else {
									elem.style.borderBottom = "solid #b00 1px";
									elem.innerHTML = opcodes[i][3];
									cM.setGutterMarker(opcodes[i][3]-1, "CodeMirror-linenumbers", elem);
								}
							}
						}
					}
				}
			},ICEcoder.loadingFile ? 100 : 0);
		}
	},

	// Update Git diff pane (the diffs between saved content and git commits)
	updateGitDiffPane: function() {
		var gitDiffList = "";
		for (var i=0; i<ICEcoder.indexData.gitDiff.paths.length; i++) {
			gitDiffList +=
			'<div class="link" onclick="ICEcoder.toolShowHideToggle(\'git\'); ICEcoder.openFile(\'/' +
			ICEcoder.indexData.gitDiff.paths[i] +
			"')\">" +
			ICEcoder.indexData.gitDiff.paths[i] +
			"</div>" +
			"\n";
			get("git").innerHTML = gitDiffList + "<br><br>";
		}
	},

	// Update preview window content
	updatePreviewWindow: function(thisCM,filepath,filename,fileExt) {
		if (ICEcoder.previewWindow.location.pathname==filepath) {
			if (["htm","html","txt"].indexOf(fileExt) > -1) {
				ICEcoder.previewWindow.document.documentElement.innerHTML = thisCM.getValue();
			} else if (["md"].indexOf(fileExt) > -1) {
				ICEcoder.previewWindow.document.documentElement.innerHTML = mmd(thisCM.getValue());
			}
		} else if (["css"].indexOf(fileExt) > -1) {
			if (ICEcoder.previewWindow.document.documentElement.innerHTML.indexOf(filename) > -1) {
				var css = thisCM.getValue();
				var style = document.createElement('style');
				style.type = 'text/css';
				style.id = "ICEcoder"+filepath.replace(/\//g,"_");
				if (style.styleSheet){
					style.styleSheet.cssText = css;
				} else {
					style.appendChild(document.createTextNode(css));
				}
				if (ICEcoder.previewWindow.document.getElementById(style.id)) {
					ICEcoder.previewWindow.document.documentElement.removeChild(ICEcoder.previewWindow.document.getElementById(style.id));
				}
				ICEcoder.previewWindow.document.documentElement.appendChild(style);
			}
		}
		// Do the pesticide plugin if it exists
		try {ICEcoder.doPesticide();} catch(err) {};
		// Do the stats.js plugin if it exists
		try {ICEcoder.doStatsJS('update');} catch(err) {};
		// Do the responsive plugin if it exists
		try {ICEcoder.doResponsive();} catch(err) {};
	},

	// Clean up our loaded code
	contentCleanUp: function() {
		var cM, cMdiff, thisCM, content;

		// Replace any temp /textarea value
		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		content = thisCM.getValue();
		content = content.replace(/<ICEcoder:\/:textarea>/g,'<\/textarea>');

		// Then set the content in the editor & clear the history
		thisCM.setValue(content);
		thisCM.clearHistory();
		ICEcoder.savedPoints[ICEcoder.selectedTab-1] = thisCM.changeGeneration();
		ICEcoder.savedContents[ICEcoder.selectedTab-1] = thisCM.getValue();
	},

	// Undo last change
	undo: function() {
		var cM, cMdiff, thisCM;

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		thisCM.undo();
	},

	// Redo change
	redo: function() {
		var cM, cMdiff, thisCM;

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		thisCM.redo();
	},

	// Indent more/less
	indent: function(moreLess) {
		var cM, cMdiff, thisCM;

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		if (moreLess=="more") {
			ICEcoder.content.contentWindow.CodeMirror.commands.indentMore(thisCM);
		} else {
			ICEcoder.content.contentWindow.CodeMirror.commands.indentLess(thisCM);
		}
	},

	// Move current line up/down
	moveLines: function(dir) {
		var cM, cMdiff, thisCM, lineStart, lineEnd, swapLineNo, swapLine;

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;

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

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		thisCM.setSelection({line:line,ch:0}, {line:line,ch:thisCM.lineInfo(line).text.length});
	},

	// Focus the editor
	focus: function(diff) {
		var cM, cMdiff, thisCM;

		if (!(/iPhone|iPad|iPod/i.test(navigator.userAgent))) {
			cM = ICEcoder.getcMInstance();
			cMdiff = ICEcoder.getcMdiffInstance();
			thisCM = diff ? cMdiff : cM;
			if (thisCM) {
				thisCM.focus();
			}
		}
	},

	// Go to a specific line number
	goToLine: function(lineNo, charNo, noFocus) {
		var cM, cMdiff, thisCM;

		lineNo = lineNo ? lineNo-1 : get('goToLineNo').value-1;
		charNo = charNo ? charNo : 0;

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;

		ICEcoder.scrollingOnLine = thisCM.getCursor().line;

		// Scroll cursor into middle of view
		if ("undefined" != typeof ICEcoder.scrollInt) {
			clearInterval(ICEcoder.scrollInt);
		}

		ICEcoder.scrollInt = setInterval(function() {
			ICEcoder.scrollingOnLine = ICEcoder.scrollingOnLine+((lineNo-ICEcoder.scrollingOnLine)/5);
			thisCM.scrollTo(0,(thisCM.defaultTextHeight()*ICEcoder.scrollingOnLine)-(thisCM.getScrollInfo().clientHeight/10));
			ICEcoder.setMinimapLayout(thisCM);
			if (Math.round(ICEcoder.scrollingOnLine) == lineNo) {
				clearInterval(ICEcoder.scrollInt);
			}
		},10);

		thisCM.setCursor(lineNo, charNo);
		if (!noFocus) {
			ICEcoder.focus();
			// Also do this after a 0ms tickover incase DOM wasn't ready
			setTimeout(function(){ICEcoder.focus();},0);
		}
		return false;
	},

	// Comment/uncomment line or selected range on keypress
	lineCommentToggle: function() {
		var cM, cMdiff, thisCM, cursorPos, linePos, lineContent, lCLen;

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;

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
		thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
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
		thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		if (!line) {line = thisCM.getCursor().line};
		thisCM.replaceRange(thisCM.getLine(line)+"<br>",{line:line,ch:0},{line:line,ch:1000000});
	},

	// Insert a line before and auto-indent
	insertLineBefore: function(line) {
		var cM, cMdiff, thisCM;

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
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
		thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
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
		thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
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
		thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
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
		thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		tokenString = thisCM.getTokenAt(thisCM.getCursor()).string;

		if (thisCM.somethingSelected() && ICEcoder.origCurorPos) {
			thisCM.setCursor(ICEcoder.origCurorPos);
		} else {
			ICEcoder.origCurorPos = thisCM.getCursor();
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
				if (ICEcoder.findReplace(defVars[i],false,false)) {
					break;
				}
			}
		}
	},

	// Update function & class list {
	updateFunctionClassList: function() {
		var cM, functionClassList;

		cM = ICEcoder.getcMInstance();
		ICEcoder.functionClassList = [];

		if(cM) {
			// For each line, establish if there's a function or class item on it
			cM.doc.eachLine(function(handle){ICEcoder.updateFunctionClassListItems(handle)});
			// Update the list displayed
			setTimeout(function() {
				functionClassList = '';
				// For each of the items in our array, if it's verified, add it into string
				for (var i=0; i<ICEcoder.functionClassList.length; i++) {
					if (ICEcoder.functionClassList[i]['verified']) {
						functionClassList += '<div onclick="ICEcoder.goToLine('+(ICEcoder.functionClassList[i]['line']+1)+')" class="functionClassListItem"><span class="name">'+ICEcoder.functionClassList[i]['name']+'</span><br><span class="params">'+ICEcoder.functionClassList[i]['params']+'</span></div>';
					}
				}
				// Update our list
				get('functionClassList').innerHTML = functionClassList;
			},0);
		}
	},

	// Update function/class list items
	updateFunctionClassListItems: function(handle) {
		var cM, functionClassText;

		cM = ICEcoder.getcMInstance();
		functionClassText = "";
		// Get function declaration lines
		if (handle.text.indexOf("function ") > -1 && handle.text.replace(/\$function/g,"").indexOf("function ") > -1) {
			functionClassText = handle.text.substring(handle.text.indexOf("function ") + 9);
		}
		// Get class declaration lines
		if (handle.text.indexOf("class ") > -1 && handle.text.replace(/\$class/g,"").indexOf("class ") > -1) {
			functionClassText = handle.text.substring(handle.text.indexOf("class ") + 6);
		}
		// Get just the name of the function/class
		functionClassText = functionClassText.trim().split("{")[0].split("(");

		// Push items into array
		if (functionClassText[0] != "") {
			ICEcoder.functionClassList.push({
				line: cM.getLineNumber(handle),
				name: functionClassText[0],
				params: "("+(functionClassText[1] ? functionClassText[1].replace(/[,]/g,", ") : ""),
				verified: false
			});
			// After a 0ms tickover, verify the item
			setTimeout(function() {
				// If we're defining a function/class
				if (!handle.styles || (handle.styles && handle.styles.indexOf('def') > -1 && cM.getLineNumber(handle))) {
					// Find our item in the array and mark it as verified
					for (var i=0; i< ICEcoder.functionClassList.length; i++) {
						if (ICEcoder.functionClassList[i]['line'] == cM.getLineNumber(handle)) {
							ICEcoder.functionClassList[i]['verified'] = true;
						}
					};
				}
			},0);
		}
	},

	// Set the Minimap
	setMinimap: function() {
		var cM;

		cM = ICEcoder.getcMInstance();

		if(cM) {
			// Get syntax formatted content and output to miniMapContent
			ICEcoder.content.contentWindow.CodeMirror.runMode(cM.getValue(),cM.getOption('mode'),get('miniMapContent'));
			// white-space: pre vs pre-wrap depending on line wrapping
			get('miniMapContent').innerHTML = '<div class="cm-s-'+ICEcoder.theme+'" style="font-family: monospace; white-space: '+(ICEcoder.lineWrapping == true ? 'pre-wrap' : 'pre')+'; font-size: 2px; line-height: 2px">'+get('miniMapContent').innerHTML+'</div>';
			get('miniMapContent').innerHTML = get('miniMapContent').innerHTML.replace(/\<span /g,'<span style="font-size: 2px; font-family: monospace" ');

			get('miniMapContainer').innerHTML = '<div style="position: absolute; display: inline-block; top: '+
				ICEcoder.miniMapBoxTop+
				'px; left: 0; width: 200px; height: '+
				ICEcoder.miniMapBoxHeight+'px; background: rgba(0,198,255,0.1); z-index: 1; cursor: pointer" id="miniMapBox"></div>';

			var elem = get('miniMapBox');
			var draggie = new Draggabilly( elem, {
				axis: 'y',
				containment: true
			});

			draggie.on( 'dragMove', function( event, pointer, moveVector ) {
				yPos = this.position.y;
				maxHeight = parseInt(get('docExplorer').style.height,10) <= parseInt(get('miniMapContent').getBoundingClientRect().height,10)
					? parseInt(get('docExplorer').style.height,10)
					: parseInt(get('miniMapContent').getBoundingClientRect().height,10);
				newPerc = (this.position.y/(maxHeight-ICEcoder.miniMapBoxHeight));
				yPos = (cM.getScrollInfo().height-cM.getScrollInfo().clientHeight)*newPerc;
				cM.scrollTo(0,yPos); // this.position.y
			});
			draggie.on( 'pointerDown', function( event, pointer ) {
				ICEcoder.mouseDownMinimap = true;
			});
			draggie.on( 'pointerUp', function( event, pointer ) {
				ICEcoder.mouseDownMinimap = false;
			});

			ICEcoder.setMinimapLayout(cM);

			get('docExplorer').style.right = "-220px";
		}
	},

	setMinimapLayout: function(thisCM,cMinstance) {
		var cM, percThru;

		// If we've got a minimap box ready
		if (get('miniMapBox') && thisCM) {

			// Get CM instance and percentage through document
			cM = ICEcoder.getcMInstance();
			percThru = thisCM.getScrollInfo().top/(thisCM.getScrollInfo().height-thisCM.getScrollInfo().clientHeight);

			// If content to display has a greater height than docExplorer
			if (parseInt(get('miniMapContent').getBoundingClientRect().height,10) > parseInt(get('docExplorer').style.height,10)) {
				// Set the minimap container to same height
				get('miniMapContainer').style.height = parseInt(get('docExplorer').style.height,10)+"px";
				// Set box height relative to font height
				ICEcoder.miniMapBoxHeight = (parseInt(get('docExplorer').style.height,10)/cM.defaultTextHeight()*2);
				get('miniMapBox').style.height = ICEcoder.miniMapBoxHeight + "px";
				// Set top position of it according to percentage through document and account for height of nav box
				ICEcoder.miniMapBoxTop = (percThru*parseInt(get('docExplorer').style.height,10)) - (percThru*ICEcoder.miniMapBoxHeight);
				// Set the minimap position according to scroll position (used if we move cursor in document)
				get('miniMapContent').style.marginTop = (-(parseInt(get('miniMapContent').getBoundingClientRect().height,10) - parseInt(get('docExplorer').style.height,10))*percThru) + "px";
			// If less than the docExplorer height
			} else {
				// Set height of container to that of the content
				get('miniMapContainer').style.height = parseInt(get('miniMapContent').getBoundingClientRect().height,10)+"px";
				// Set box height relative to font height
				ICEcoder.miniMapBoxHeight = (parseInt(get('docExplorer').style.height,10)/cM.defaultTextHeight()*2);
				get('miniMapBox').style.height = ICEcoder.miniMapBoxHeight + "px";
				// Set top position of it according to percentage through minimap and account for height of nav box
				ICEcoder.miniMapBoxTop = (percThru*parseInt(get('miniMapContainer').getBoundingClientRect().height,10)) - (percThru*ICEcoder.miniMapBoxHeight);
				// Set the minimap position to 0
				get('miniMapContent').style.marginTop = 0;
			}
			// Can set the Minimap nav position if not dragging it (let Draggabilly handle that)
			if (!ICEcoder.mouseDownMinimap) {
				get('miniMapBox').style.top = ICEcoder.miniMapBoxTop + "px";
			}
		}
	},

	// Autocomplete
	autocomplete: function() {
		var cM, cMdiff, thisCM;

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		ICEcoder.content.contentWindow.CodeMirror.commands.autocomplete(thisCM);
	},

	// Paste a URL, locally or absolutely if CTRL/Cmd key down
	pasteURL: function(url) {
		var cM, cMdiff, thisCM;

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		if(ICEcoder.draggingWithKey == "CTRL") {
			url = window.location.protocol + "//" + window.location.hostname + url;
		}
		thisCM.replaceSelection(url,"around");
	},

	// Search for selected text online
	searchForSelected: function() {
		var cM, cMdiff, thisCM;

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		if (ICEcoder.caretLocType) {
			if (thisCM.getSelection() != "") {
				var searchPrefix = ICEcoder.caretLocType.toLowerCase()+" ";
				if (ICEcoder.caretLocType=="Content") {
					searchPrefix = "";
				}
				window.open("http://www.google.com/#output=search&q="+searchPrefix+thisCM.getSelection());
			} else {
				ICEcoder.message(t['No text selected...']);
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
		selElem = get('filesFrame').contentWindow.document.getElementById(ICEcoder.selectedFiles[ICEcoder.selectedFiles.length-1]+"_perms").parentNode;
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
				ICEcoder.openCloseDir(selElem,false);				// contract dir
			}
		}
		if (action == "right" || action == "enter") {
			fileFolder == "folder"
				? ICEcoder.openCloseDir(selElem,true)				// expand dir
				: ICEcoder.openFile(selElem.childNodes[1].id.replace(/\|/g,"/"));	// open file
		}
		if (goElem && goElem.childNodes[1]) {
			ICEcoder.overFileFolder(fileFolder, goElem.childNodes[1].id);		// If we have an elem to go to, select it
			ICEcoder.selectFileFolder(evt);
		}
	},

	// Open/close dirs on demand
	openCloseDir: function(dir,load) {
		var node, d;

		dir.onclick = function(event) {
			if(!event.ctrlKey && !ICEcoder.cmdKey) {
				ICEcoder.openCloseDir(this,!load);
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
			ICEcoder.filesFrame.contentWindow.frames['fileControl'].location.href = iceLoc+"/lib/get-branch.php?location="+dir.childNodes[1].id+"&csrf="+ICEcoder.csrf;
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

		domElem = get('filesFrame').contentWindow.document.getElementById(ref.replace(iceRoot,"").replace(/\/$/, "").replace(/\//g,"|"));
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
		if (ICEcoder.thisFileFolderLink=="") {
			if (!ctrlSim && !evt.ctrlKey && !ICEcoder.cmdKey) {
				ICEcoder.deselectAllFiles();
			}
		} else if (ICEcoder.thisFileFolderLink) {
			// Get file URL, with pipes instead of slashes & target DOM elem
			shortURL = ICEcoder.thisFileFolderLink.replace(/\//g,"|");
			tgtFile = ICEcoder.filesFrame.contentWindow.document.getElementById(shortURL);

			// If we have the CTRL/Cmd key down
			if (ctrlSim || evt.ctrlKey || ICEcoder.cmdKey) {
				// Deselect or select file
				if (ICEcoder.selectedFiles.indexOf(shortURL)>-1) {
					ICEcoder.selectDeselectFile('deselect',tgtFile);
					ICEcoder.selectedFiles.splice(ICEcoder.selectedFiles.indexOf(shortURL),1);
				} else {
					ICEcoder.selectDeselectFile('select',tgtFile);
					ICEcoder.selectedFiles.push(shortURL);
				}
			// Select from last click to this one
			} else if (shiftSim || evt.shiftKey) {
				selecting = false;
				dirList = tgtFile.parentNode.parentNode.parentNode;
				lastFileClicked = ICEcoder.selectedFiles[ICEcoder.selectedFiles.length-1];

				// Prefix numbers with up to 20 leading zeros
				// This is so we can have some kind of natural comparison on the regex below
				function prefixer(match, p1, offset, string) {
					return ('00000000000000000000'+match).substr(-20);
				}

				startFile = shortURL.replace(/\d+/g, prefixer) < lastFileClicked.replace(/\d+/g, prefixer) ? shortURL : lastFileClicked;
				endFile = shortURL.replace(/\d+/g, prefixer) > lastFileClicked.replace(/\d+/g, prefixer) ? shortURL : lastFileClicked;

				if (ICEcoder.selectedFiles.length > 0 && startFile.substr(0,startFile.lastIndexOf("|")) == endFile.substr(0,endFile.lastIndexOf("|"))) {
					for (var i=0; i<1000000; i+=2) {
						if(dirList.childNodes[i].nodeName != "LI") {i++;};
						thisFileObj = dirList.childNodes[i].childNodes[0].childNodes[1];
						if (thisFileObj.id == startFile) {
							selecting = true;
						}
						if (selecting==true && ICEcoder.selectedFiles.indexOf(thisFileObj.id)==-1) {
							ICEcoder.selectDeselectFile('select',thisFileObj);
							ICEcoder.selectedFiles.push(thisFileObj.id);
						}
						if (thisFileObj.id == endFile) {
							break;
						}
					}
				} else {
					ICEcoder.selectDeselectFile('select',tgtFile);
					ICEcoder.selectedFiles.push(shortURL);
				}
			// We are single clicking
			} else {
				ICEcoder.deselectAllFiles();

				// Add our URL and highlight the file
				ICEcoder.selectDeselectFile('select',tgtFile);
				ICEcoder.selectedFiles.push(shortURL);
			}
		}

		// If in GitHub mode, update the selected count and button colours
		if (ICEcoder.githubDiff) {
			get('githubNavSelectedCount').innerHTML	= "Selected: " + ICEcoder.selectedFiles.length;
			get('githubNavCommit').style.color		= ICEcoder.selectedFiles.length > 0 ? "#fff" : "#333";
			get('githubNavCommit').style.background	= ICEcoder.selectedFiles.length > 0 ? "#2187e7" : "#555";
			get('githubNavSelectedCount').style.color	= ICEcoder.selectedFiles.length > 0 ? "#fff" : "#333";
			get('githubNavPull').style.color		= ICEcoder.selectedFiles.length > 0 ? "#fff" : "#333";
			get('githubNavPull').style.background	= ICEcoder.selectedFiles.length > 0 ? "#2187e7" : "#555";
		}

		// Adjust the file & replace select dropdown values accordingly
		document.findAndReplace.target[2].innerHTML = !ICEcoder.selectedFiles[0] ? t['all files'] : t['selected files'];
		document.findAndReplace.target[3].innerHTML = !ICEcoder.selectedFiles[0] ? t['all filenames'] : t['selected filenames'];

		// Hide the file menu incase it's showing
		ICEcoder.hideFileMenu();
	},

	// Deselect all files
	deselectAllFiles: function() {
		var tgtFile;

		for (var i=0;i<ICEcoder.selectedFiles.length;i++) {
			tgtFile = ICEcoder.filesFrame.contentWindow.document.getElementById(ICEcoder.selectedFiles[i]);
			ICEcoder.selectDeselectFile('deselect',tgtFile);
		}
		ICEcoder.selectedFiles.length = 0;
	},

	// Select or deselect file
	selectDeselectFile: function(action,file) {
		var isOpen;

		if (file) {
			isOpen = ICEcoder.openFiles.indexOf(file.id.replace(/\|/g,"/")) > -1 ? true : false;

			if (ICEcoder.openFiles[ICEcoder.selectedTab-1] == file.id.replace(/\|/g,"/")) {
				file.style.backgroundColor = action=="select"
				? ICEcoder.tabBGselected : ICEcoder.tabBGcurrent;
			} else {
				file.style.backgroundColor = action=="select"
				? ICEcoder.tabBGselected : file.style.backgroundColor = isOpen
				? ICEcoder.tabBGopen : ICEcoder.tabBGnormal;
			}
			file.style.color = action=="select" ? ICEcoder.tabFGselected : ICEcoder.tabFGnormalFile;
		}
	},

	// Box select files
	boxSelect: function(evt, mouseAction) {
		var fmDragBox, positive;

		fmDragBox = ICEcoder.filesFrame.contentWindow.document.getElementById('fmDragBox');

		// On mouse down, set start X & Y and reset first and last items in box area select
		if (mouseAction == "down") {
			ICEcoder.fmDragBoxStartX = ICEcoder.mouseX;
			ICEcoder.fmDragBoxStartY = ICEcoder.mouseY;
			ICEcoder.fmDragSelectFirst = "";
			ICEcoder.fmDragSelectLast = "";
		}

		// On mouse drag, state we're dragging, set the box size and position properties and select files
		if(ICEcoder.mouseDown && !ICEcoder.mouseDownInCM && mouseAction == "drag") {
			ICEcoder.fmDraggedBox = true;

			// Handle X-axis properties
			positive = ICEcoder.mouseX-ICEcoder.fmDragBoxStartX > 0;
			fmDragBox.style.left = (positive ? ICEcoder.fmDragBoxStartX : ICEcoder.mouseX) + "px";
			fmDragBox.style.width = Math.abs(ICEcoder.mouseX-ICEcoder.fmDragBoxStartX) + "px";

			// Handle Y-axis properties
			positive = ICEcoder.mouseY-ICEcoder.fmDragBoxStartY > 0;
			fmDragBox.style.top = (positive ? ICEcoder.fmDragBoxStartY-70 : ICEcoder.mouseY-70) + "px";
			fmDragBox.style.height = Math.abs(ICEcoder.mouseY-ICEcoder.fmDragBoxStartY) + "px";

			// Select the files
			if (ICEcoder.thisFileFolderLink != "") {
				if (ICEcoder.fmDragSelectFirst == "") {
					ICEcoder.fmDragSelectFirst = ICEcoder.thisFileFolderLink;
					ICEcoder.overFileFolder(ICEcoder.thisFileFolderLink.indexOf('.') > 0 ? 'file' : 'folder', ICEcoder.fmDragSelectFirst);
					ICEcoder.selectFileFolder(evt);
				} else {
					ICEcoder.fmDragSelectLast = ICEcoder.thisFileFolderLink;
					ICEcoder.overFileFolder(ICEcoder.thisFileFolderLink.indexOf('.') > 0 ? 'file' : 'folder', ICEcoder.fmDragSelectLast);
					ICEcoder.selectFileFolder(evt,false,'shiftSim');
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
		ICEcoder.newTab('alsoSave');
	},

	// Create a new folder
	newFolder: function() {
		var shortURL, newFolder;

		shortURL = ICEcoder.selectedFiles[ICEcoder.selectedFiles.length-1].replace(/\|/g,"/");
		newFolder = ICEcoder.getInput('Enter new folder name at '+shortURL,'');
		if (newFolder) {
			newFolder = (shortURL + "/" + newFolder).replace(/\/\//,"/");
			ICEcoder.serverQueue("add",iceLoc+"/lib/file-control-xhr.php?action=newFolder&csrf="+ICEcoder.csrf,encodeURIComponent(newFolder.replace(/\//g,"|")));
			ICEcoder.serverMessage('<b>'+t['Creating Folder']+'</b><br>'+newFolder);
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
			flSplit = ICEcoder.returnFileAndLine(fileLink);
			fileLink = flSplit[0];
			line     = flSplit[1];
		}

		if (fileLink) {
			ICEcoder.thisFileFolderLink=fileLink;
			ICEcoder.thisFileFolderType="file";
		}
		if (ICEcoder.thisFileFolderLink != "/[NEW]" && ICEcoder.isOpen(ICEcoder.thisFileFolderLink)!==false) {
			ICEcoder.switchTab(ICEcoder.isOpen(ICEcoder.thisFileFolderLink)+1);
			if (line > 1){
				ICEcoder.goToLine(line);
			}
		} else if (ICEcoder.thisFileFolderLink!="" && ICEcoder.thisFileFolderType=="file") {

			// work out a shortened URL for the file
			shortURL = ICEcoder.thisFileFolderLink.replace(/\|/g,"/");
			// No reason why we can't open a file (so far)
			canOpenFile = true;
			// Limit to 100 files open at a time
			if (ICEcoder.openFiles.length>=100) {
				ICEcoder.message(t['Sorry you can...']);
				canOpenFile = false;
			}

			// if we're still OK to open it...
			if (canOpenFile) {
				ICEcoder.shortURL = shortURL;

				if (shortURL!="/[NEW]") {
					ICEcoder.thisFileFolderLink = ICEcoder.thisFileFolderLink.replace(/\//g,"|");
					ICEcoder.serverQueue("add",iceLoc+"/lib/file-control.php?action=load&file="+encodeURIComponent(ICEcoder.thisFileFolderLink)+"&csrf="+ICEcoder.csrf+"&lineNumber="+line);
					ICEcoder.serverMessage('<b>'+t['Opening File']+'</b><br>'+ICEcoder.shortURL);
				} else {
					ICEcoder.createNewTab('new');
				}
				ICEcoder.fMIconVis('fMView',1);
			}
		}
	},

	// Open selected files
	openFilesFromList: function(fileList) {
		for (var i=0;i<fileList.length;i++) {
			ICEcoder.thisFileFolderLink=fileList[i].replace('|','/');
			ICEcoder.thisFileFolderType='file';
			ICEcoder.openFile();
		}
	},

	// Show file prompt to open file
	openPrompt: function() {
		var fileLink;

		if(fileLink = ICEcoder.getInput(t['Enter relative file...'],'')) {
			fileLink.indexOf("://")>-1
			? ICEcoder.getRemoteFile(fileLink)
			: ICEcoder.openFile(fileLink);
		}
	},

	// Get remote file contents
	getRemoteFile: function(remoteFile) {
		var flSplit, line;

		if ("undefined" != typeof remoteFile) {
			flSplit = ICEcoder.returnFileAndLine(remoteFile);
			remoteFile = flSplit[0];
			line       = flSplit[1];
		}

		ICEcoder.serverQueue("add",iceLoc+"/lib/file-control-xhr.php?action=getRemoteFile&csrf="+ICEcoder.csrf+"&lineNumber="+line,encodeURIComponent(remoteFile));
		ICEcoder.serverMessage('<b>'+t['Getting']+'</b><br>'+remoteFile);
	},

	// Get changes to save (used when simply saving, gets diff changes between current and last known version)
	getChangesToSave: function() {
		var cM, savedText, newText, sm, opcodes;

		cM = ICEcoder.getcMInstance();

		// Get the last known saved version of file from array
		savedText = ICEcoder.savedContents[ICEcoder.selectedTab-1];

		// Get the text values and split it into lines
		newText = difflib.stringAsLines(cM.getValue());
		savedText = difflib.stringAsLines(savedText);

		// Create a SequenceMatcher instance that diffs the two sets of lines
		sm = new difflib.SequenceMatcher(savedText, newText);

		// Get the opcodes from the SequenceMatcher instance
		// Opcodes is a list of 3-tuples describing what changes should be made to the base text in order to yield the new text
		opcodes = sm.get_opcodes();

		for (var i=0; i<opcodes.length; i++) {
			// opcode events may be:
			// equal   = do nothing for this range
			// replace = replace [1]-[2] with [3]-[4]
			// insert  = replace [1]-[2] with [3]-[4]
			// delete  = replace [1]-[2] with [3]-[4]
			for (j=opcodes[i][3]; j<opcodes[i][4]; j++) {
				if (opcodes[i][0] != "equal") {
					// Add a new array item if we don't have one yet
					if ("undefined" == typeof opcodes[i][5]) {
						opcodes[i][5] = "";
					}
					// Add text line from newText to that array item along with line break
					opcodes[i][5] += newText[j]+"\n";
				}
			}
		}

		return JSON.stringify(opcodes);
	},

	// Save a file
	saveFile: function(saveAs) {
		var changes, saveType, filePath, pathPrefix;

		// If we're not 'saving as', establish changes between current and known saved version from array
		if (!saveAs) {
			changes = ICEcoder.getChangesToSave();
		}

		saveType = saveAs ? "saveAs" : "save";
		filePath = ICEcoder.openFiles[ICEcoder.selectedTab-1].replace(iceRoot,"").replace(/\//g,"|");
		if (filePath=="|[NEW]" && ICEcoder.selectedFiles.length>0) {
			pathPrefix = ICEcoder.selectedFiles[0];
			filePath = pathPrefix.lastIndexOf(".") == -1 || pathPrefix.lastIndexOf(".") < pathPrefix.lastIndexOf("|")
			? pathPrefix+filePath
			: "|[NEW]";
		}
		filePath = filePath.replace("||","|");
		ICEcoder.serverQueue("add",iceLoc+"/lib/file-control-xhr.php?action=save&fileMDT="+ICEcoder.openFileMDTs[ICEcoder.selectedTab-1]+"&fileVersion="+ICEcoder.openFileVersions[ICEcoder.selectedTab-1]+"&saveType="+saveType+"&csrf="+ICEcoder.csrf,encodeURIComponent(filePath),changes);
		ICEcoder.serverMessage('<b>'+t['Saving']+'</b><br>'+ICEcoder.openFiles[ICEcoder.selectedTab-1].replace(iceRoot,""));
	},

	// Prompt a rename dialog
	renameFile: function(oldName,newName) {
		var shortURL, fileName, i;

		if (!oldName) {
			shortURL = ICEcoder.selectedFiles[ICEcoder.selectedFiles.length-1].replace(/\|/g,"/");
			oldName = ICEcoder.selectedFiles[ICEcoder.selectedFiles.length-1].replace(/\|/g,"/");
		} else {
			shortURL = oldName.replace(/\|/g,"/");
		}
		if (!newName) {
			newName = ICEcoder.getInput(t['Please enter the...'],shortURL);
		}
		if (newName) {
			i = ICEcoder.openFiles.indexOf(shortURL.replace(/\|/g,"/"));
			if(i>-1) {
				// rename array item and the tab
				ICEcoder.openFiles[i] = newName;
				closeTabLink = '<a nohref onClick="ICEcoder.closeTab(parseInt(this.parentNode.id.slice(3),10))"><img src="'+iceLoc+'/images/nav-close.gif" class="closeTab" onMouseOver="prevBG=this.style.backgroundColor;this.style.backgroundColor=\'#333\'; ICEcoder.overCloseLink=true" onMouseOut="this.style.backgroundColor=prevBG; ICEcoder.overCloseLink=false"></a>';
				fileName = ICEcoder.openFiles[i];
				get('tab'+(i+1)).innerHTML = closeTabLink + " " + fileName.slice(fileName.lastIndexOf("/")).replace(/\//,"");
				get('tab'+(i+1)).title = newName;
			}
		ICEcoder.serverQueue("add",iceLoc+"/lib/file-control-xhr.php?action=rename&oldFileName="+encodeURIComponent(oldName.replace(/\|/g,"/"))+"&csrf="+ICEcoder.csrf,encodeURIComponent(newName));
		ICEcoder.serverMessage('<b>'+t['Renaming to']+'</b><br>'+newName);

		ICEcoder.setPreviousFiles();
		}
	},

	// Move a file from old location to new
	moveFile: function(oldName,newName) {
		var fileName, i;

		if (newName && newName != oldName) {
			i = ICEcoder.openFiles.indexOf(oldName.replace(/\|/g,"/"));
			if(i>-1) {
				// rename array item and the tab
				ICEcoder.openFiles[i] = newName;
				closeTabLink = '<a nohref onClick="ICEcoder.closeTab(parseInt(this.parentNode.id.slice(3),10))"><img src="'+iceLoc+'/images/nav-close.gif" class="closeTab" onMouseOver="prevBG=this.style.backgroundColor;this.style.backgroundColor=\'#333\'; ICEcoder.overCloseLink=true" onMouseOut="this.style.backgroundColor=prevBG; ICEcoder.overCloseLink=false"></a>';
				fileName = ICEcoder.openFiles[i];
				get('tab'+(i+1)).innerHTML = closeTabLink + " " + fileName.slice(fileName.lastIndexOf("/")).replace(/\//,"");
				get('tab'+(i+1)).title = newName;
			}
			if (ICEcoder.ask("Are you sure you want to move file " + oldName + " to " + newName + " ?")){
				ICEcoder.serverQueue("add",iceLoc+"/lib/file-control-xhr.php?action=move&oldFileName="+encodeURIComponent(oldName.replace(/\//g,"|"))+"&csrf="+ICEcoder.csrf,encodeURIComponent(newName.replace(/\//g,"|")));
				ICEcoder.serverMessage('<b>'+t['Moving to']+'</b><br>'+newName);
			}

			ICEcoder.setPreviousFiles();
		}
	},

	// Delete a file
	deleteFiles: function(fileList) {
		var tgtFiles, tgtListDisplay;

		tgtFiles = fileList ? fileList : ICEcoder.selectedFiles;
		tgtListDisplay = tgtFiles.toString().replace(/\|/g,"/").replace(/,/g,"\n");
		if (tgtFiles.length>0 && ICEcoder.ask('Delete:\n\n'+tgtListDisplay+'?')) {
			ICEcoder.serverQueue("add",iceLoc+"/lib/file-control-xhr.php?action=delete&&csrf="+ICEcoder.csrf,encodeURIComponent(tgtFiles.join(";")));
			ICEcoder.serverMessage('<b>'+t['Deleting File']+'</b><br>'+tgtListDisplay);
		};
	},

	// Copy files
	copyFiles: function(fileList,dontShowPaste,dontHide) {
		ICEcoder.copiedFiles = [];
		for (var i=0; i<fileList.length; i++) {
			ICEcoder.copiedFiles[i] = fileList[i];
		}
		if (!dontShowPaste) {
			get('fmMenuPasteOption').style.display = "block";
		}
		if (!dontHide) {
			ICEcoder.hideFileMenu();
		}
	},

	// Paste files
	pasteFiles: function(location) {
		if (ICEcoder.copiedFiles) {
			for (var i=0; i<ICEcoder.copiedFiles.length; i++) {
				if (ICEcoder.copiedFiles[i]!="|") {
					ICEcoder.serverQueue("add",iceLoc+"/lib/file-control-xhr.php?action=paste&location="+location+"&csrf="+ICEcoder.csrf,encodeURIComponent(ICEcoder.copiedFiles[i]));
					ICEcoder.serverMessage('<b>'+t['Pasting File']+'</b><br>'+ICEcoder.copiedFiles[i].toString().replace(/\|/g,"/").replace(/,/g,"\n"));
				} else {
					ICEcoder.message(t['Sorry cannot paste...']);
				}
			}
		} else {
			ICEcoder.message(t['Nothing to paste...']);
		}
	},

	// Duplicate (copy & paste) files
	duplicateFiles: function(fileList) {
		var copiedFiles, location;

		// Take a snapshot of copied files
		if (ICEcoder.copiedFiles) {
			copiedFiles = ICEcoder.copiedFiles;
		}

		ICEcoder.copyFiles(fileList,'dontShowPaste','dontHide');
		location = fileList[0].substr(0,fileList[0].lastIndexOf("|"));
		ICEcoder.pasteFiles(location);

		// Restore copied files back to the snapshot
		if ("undefined" != typeof copiedFiles) {
			ICEcoder.copiedFiles = copiedFiles;
		}
	},

	// Upload file(s) - select & submit
	uploadFilesSelect: function(location) {
		get('uploadDir').value = location;
		get("fileInput").click();
	},
	uploadFilesSubmit: function(obj) {
		if (get('fileInput').value!="") {
			ICEcoder.showHide('show',get('loadingMask'));
			get('uploadFilesForm').submit();
			event.preventDefault();
		}
	},

	// Show/hide file manager nav options
	showHideFileNav: function(vis,elem) {
		var options = ["optionsFile","optionsEdit","optionsSource","optionsHelp"];
		if (vis=="hide") {
			fileNavInt = setTimeout(function() {
				for (var i=0; i<options.length; i++) {
					ICEcoder.showHide('hide',get(options[i]));
					get(options[i]+'Nav').style.color = '';
				}
			},150);
		} else {
			for (var i=0; i<options.length; i++) {
				ICEcoder.showHide('hide',get(options[i]));
				get(options[i]+'Nav').style.color = '';
			}
		}
		get('fileOptions').style.opacity = 0;
		if (vis=="show") {
			if ("undefined" != typeof fileNavInt) {
				clearTimeout(fileNavInt);
			}
			ICEcoder.showHide(vis,get(elem));
			get(elem+'Nav').style.color = '#fff';
			get('fileOptions').style.opacity = 1;
		}
	},

	// Is a specified path a folder? (Note: path is string encoded path with / replaced with |)
	isPathFolder: function(path){
		// let's enumerate all folders to find whether clicked file is a folder or not
		var dir = ICEcoder.filesFrame.contentDocument.getElementsByClassName("pft-directory");
		var thisFileId = ICEcoder.selectedFiles[0];
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
		if (path.indexOf(iceRoot) === 0) {
			path = path.replace(iceRoot,"");
		}

		// Start a seperate XHR call. We run seperately rather than add into the serverQueue because we may need to run
		// immediately, eg need to if a file/dir exists mid flow in 'Save As' function, so can't go into queue
		xhr = ICEcoder.xhrObj();
		xhr.onreadystatechange=function() {
			if (xhr.readyState==4) {
				// OK reponse?
				if (xhr.status==200) {
					// Parse the response as a JSON object
					statusObj = JSON.parse(xhr.responseText);

					// Set the action end time and time taken in JSON object
					statusObj.action.timeEnd = new Date().getTime();
					statusObj.action.timeTaken = statusObj.action.timeEnd - statusObj.action.timeStart;

					// User wanted raw (or both) output of the response?
					if (["raw","both"].indexOf(ICEcoder.fileDirResOutput) >= 0) {
						console.log(xhr.responseText);
					}
					// User wanted object (or both) output of the response?
					if (["object","both"].indexOf(ICEcoder.fileDirResOutput) >= 0) {
						console.log(statusObj);
					}

					// Also store the statusObj
					ICEcoder.lastFileDirCheckStatusObj = statusObj;

					// If error, show that, otherwise do whatever we're required to do next
					if (statusObj.status.error) {
						ICEcoder.message(statusObj.status.errorMsg);
						console.log("ICEcoder error info for your request...");
						console.log(statusObj);
						ICEcoder.serverMessage();
						ICEcoder.serverQueue('del',0);
					} else {
						eval(statusObj.action.doNext);
					}
				// Some other response? Display a message about that
				} else {
					ICEcoder.message(t['Sorry there was...']);
					console.log("ICEcoder error info for your request...");
					console.log(statusObj);
					ICEcoder.serverMessage();
					ICEcoder.serverQueue('del',0);
				}
			}
		};
		xhr.open("POST",iceLoc+"/lib/file-control-xhr.php?action=checkExists&csrf="+ICEcoder.csrf,true);
		xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		timeStart = new Date().getTime();
		xhr.send('timeStart='+timeStart+'&file='+encodeURIComponent(path));
	},

	// Show menu on right clicking in file manager
	showMenu: function(evt) {
		var menuType, menuHeight, winH, fmYPos;

		if (	ICEcoder.selectedFiles.length == 0 ||
			ICEcoder.selectedFiles.indexOf(ICEcoder.selectedFiles[ICEcoder.selectedFiles.length-1].replace(/\//g,"|")) == -1) {
			ICEcoder.selectFileFolder(evt);
		}

		menuHeight = 124+5; // general options height in px plus 5px space
		winH = window.innerHeight;
		if ("undefined" != typeof ICEcoder.thisFileFolderLink && ICEcoder.thisFileFolderLink!="") {
			menuType = this.isPathFolder(ICEcoder.selectedFiles[0]) ? "folder" : "file";
			get('folderMenuItems').style.display = menuType == "folder" && ICEcoder.selectedFiles.length == 1 ? "block" : "none";
			if (menuType == "folder" && ICEcoder.selectedFiles.length == 1) {
				menuHeight += 20+20+1+23+1+2; // new file, new folder, hr, upload files(s), hr, padding
				if (get('fmMenuPasteOption').style.display == "block") {
					menuHeight += 19;
				}
			}
			get('singleFileMenuItems').style.display = ICEcoder.selectedFiles.length > 1 ? "none" : "block";
			if (ICEcoder.selectedFiles.length == 1) {
				menuHeight += 43;
			}
			get('fileMenu').style.display = "inline-block";
			setTimeout(function() {get('fileMenu').style.opacity = "1"},4);
			get('fileMenu').style.left = (ICEcoder.mouseX+20) + "px";
			fmYPos = ICEcoder.mouseY-ICEcoder.filesFrame.contentWindow.document.body.scrollTop-10;
			if (fmYPos+menuHeight > winH) {
				fmYPos -= (fmYPos+menuHeight-winH);
			}
			get('fileMenu').style.top = fmYPos + "px";
		}
		return false;
	},

	// Continue to show the file manager
	showFileMenu: function() {
		get('fileMenu').style.display='inline-block';
		setTimeout(function() {get('fileMenu').style.opacity = "1"},4);
	},

	// Hide the file manager
	hideFileMenu: function() {
		get('fileMenu').style.display='none';
		get('fileMenu').style.opacity = "0";
	},

	// Update the file manager tree list on demand
	updateFileManagerList: function(action,location,file,perms,oldName,uploaded,fileOrFolder) {
		var actionElemType, cssStyle, perms, targetElem, locNest, newText, innerLI, permColors, newUL, newLI, elemType, nameLI, shortURL, newMouseOver;

		// Adding files
		if (action=="add" && !get('filesFrame').contentWindow.document.getElementById(location.replace(iceRoot,"").replace(/\/$/, "").replace(/\//g,"|")+"|"+file)) {
			// Is this is a file or folder and based on that, set the CSS styling & link
			actionElemType = fileOrFolder;
			cssStyle = actionElemType=="file" ? "pft-file ext-" + file.substr(file.indexOf(".")+1) : "pft-directory";
			perms = actionElemType=="file" ? ICEcoder.newFilePerms : ICEcoder.newDirPerms;

			// Identify our target element & the first child element in it's location
			if (!location) {location="/"}
			location = location.replace(iceRoot,"/");
			location = location.replace("//","/");
			targetElem = get('filesFrame').contentWindow.document.getElementById(location.replace(/\//g,"|"));
			locNest = targetElem.parentNode.parentNode.nextSibling;
			newText = document.createTextNode("\n");
			permColors = perms == 777 ? 'background: #800; color: #eee' : 'color: #888';
			innerLI = '<a nohref title="'+location.replace(/\/$/, "")+"/"+file+'" onMouseOver="parentNode.draggable=true;ICEcoder.overFileFolder(\''+actionElemType+'\',this.childNodes[1].id)" onMouseOut="parentNode.draggable=false;ICEcoder.overFileFolder(\''+actionElemType+'\',\'\')" '+

					(actionElemType == "folder" ? 'ondragover="if(parentNode.nextSibling && parentNode.nextSibling.tagName != \'UL\' && ICEcoder.thisFileFolderLink != this.childNodes[1].id) {ICEcoder.openCloseDir(this,true);}"':'')+

					' onClick="if(!event.ctrlKey && !ICEcoder.cmdKey) {'+

					(actionElemType == "folder" ? 'ICEcoder.openCloseDir(this,'+(actionElemType=="folder" ? 'true' : 'false')+');':'')+

					' if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {ICEcoder.openFile()}}" style="position: relative; left:-22px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id="'+location.replace(/\/$/, "").replace(/\//g,"|")+"|"+file+'">'+file+'</span> <span style="'+permColors+'; font-size: 8px" id="'+location.replace(/\/$/, "").replace(/\//g,"|")+"|"+file+'_perms">'+perms+'</span></a>';

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
				newLI.ondragstart = function(event) {ICEcoder.addDefaultDragData(this,event)};
				newLI.ondrag = function(event) {ICEcoder.draggingWithKeyTest(event);if(ICEcoder.getcMInstance()){ICEcoder.editorFocusInstance.indexOf('diff') == -1 ? ICEcoder.getcMInstance().focus() : ICEcoder.getcMdiffInstance().focus()}};
				newLI.ondragover = function(event) {ICEcoder.setDragCursor(event,actionElemType=="folder" ? 'folder' : 'file')};
				newLI.ondragend = function() {ICEcoder.dropFile(this)};
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
							newLI.ondragstart = function(event) {ICEcoder.addDefaultDragData(this,event)};
							newLI.ondrag = function(event) {ICEcoder.draggingWithKeyTest(event);if(ICEcoder.getcMInstance()){ICEcoder.editorFocusInstance.indexOf('diff') == -1 ? ICEcoder.getcMInstance().focus() : ICEcoder.getcMdiffInstance().focus()}};
							newLI.ondragover = function(event) {ICEcoder.setDragCursor(event,actionElemType=="folder" ? 'folder' : 'file')};
							newLI.ondragend = function() {ICEcoder.dropFile(this)};
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
				ICEcoder.openFiles[ICEcoder.selectedTab-1]=location+file;
			}
		}

		// Renaming files
		if (action=="rename") {
			// Get short URL of our right clicked file and get target elem based on this
			shortURL = oldName.replace(/\//g,"|");
			targetElem = get('filesFrame').contentWindow.document.getElementById(shortURL);
			// Set the name to be as per our new file/folder name
			targetElem.innerHTML = file;
			// Update the ID of the target & set a new title and perms ID
			targetElem.id = location.replace(/\//g,"|") + "|" + file;
			targetElem.parentNode.title = targetElem.id.replace(/\|/g,"/");
			targetElemPerms = get('filesFrame').contentWindow.document.getElementById(shortURL+"_perms");
			targetElemPerms.id = location.replace(/\//g,"|") + "|" + file + "_perms";
			// Finally, rename also within any children
			ICEcoder.renameInChildren(targetElem, oldName, location, file);
		}

		// Moving files
		if (action=="move") {
			ICEcoder.updateFileManagerList("add",location,file,false,false,false,fileOrFolder);
			ICEcoder.updateFileManagerList("delete",oldName.substr(0,oldName.lastIndexOf("/")),file);
		}

		// Chmod on files
		if (action=="chmod") {
			// Get short URL for our file and get our target elem based on this
			shortURL = ICEcoder.selectedFiles[ICEcoder.selectedFiles.length-1].replace(/\|/g,"/");
			targetElem = get('filesFrame').contentWindow.document.getElementById(shortURL.replace(/\//g,"|")+"_perms");
			// Set the color for the perms
			targetElem.style.background = perms == 777 ? '#800' : 'none';
			targetElem.style.color = perms == 777 ? '#eee' : '#888';
			// Set the new perms
			targetElem.innerHTML = perms;
			}

		// Deleting files
		if (action=="delete") {
		if (!location) {location=""}
			location = location.replace(iceRoot,"/");
			location = location.replace("//","/");
			location = location.replace(/\/$/, "").replace(/\//g,"|");
			targetElem = (location +"|"+file).replace("||","|");
			targetElem = get('filesFrame').contentWindow.document.getElementById(targetElem).parentNode.parentNode;
			ICEcoder.openCloseDir(targetElem.childNodes[0],false);
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
					targetElemPerms = get('filesFrame').contentWindow.document.getElementById(targetElem.id).nextSibling.nextSibling;
					targetElemPerms.id = targetElem.id + "_perms";
					// Finally, test this node for ULs next to it also, incase it's a dir
					ICEcoder.renameInChildren(targetElem, oldName, location, file);
				}
			}
		}
	},

	// Refresh file manager
	refreshFileManager: function() {
		ICEcoder.showHide('show',get('loadingMask'));
		ICEcoder.filesFrame.contentWindow.location.reload(true);
		ICEcoder.filesFrame.style.opacity="0";
		ICEcoder.filesFrame.onload = function() {
			ICEcoder.filesFrame.style.opacity="1";
			ICEcoder.showHide('hide',get('loadingMask'));
		}
	},

	// Detect CTRL/Cmd key whilst dragging files
	draggingWithKeyTest: function(evt) {
		var key;

		key = evt.keyCode ? evt.keyCode : evt.which ? evt.which : evt.charCode;

		// Mac command key handling (224 = Moz, 91/93 = Webkit Left/Right Apple)
		if (key==224 || key==91 || key==93) {
			ICEcoder.cmdKey = true;
		}

		ICEcoder.draggingWithKey = evt.ctrlKey||ICEcoder.cmdKey ? "CTRL" : false;
	},

	// Add default drag data (dragging in Firefox on DOM elems not possible otherwise)
	addDefaultDragData: function(elem,evt) {
		evt.dataTransfer.setData('Text', elem.id);
	},

	// Set a copy, move or none drag cursor type
	setDragCursor: function(evt,dropType) {
		var cursorIcon;

		// Prevent the default and establish if CTRL key is down
		evt.preventDefault();
		ICEcoder.draggingWithKeyTest(evt);
		// Establish the cursor to show
		cursorIcon =
			dropType == "editor"
				? ICEcoder.draggingWithKey == "CTRL"
					? "copy"
					: "link"
				: dropType == "folder"
					? ICEcoder.draggingWithKey == "CTRL"
						? "copy"
						: "move"
					: "none";

		evt.dataTransfer.dropEffect = cursorIcon;
	},

	// On dropping a file, do something
	dropFile: function(elem) {
		var filePath, tgtPath;

		filePath = elem.childNodes[0].childNodes[1].id.replace(/\|/g,"/");
		fileName = filePath.substr(filePath.lastIndexOf("/")+1);
		if (ICEcoder.area=='editor') {
			ICEcoder.pasteURL(filePath);
		};
		if (ICEcoder.area=='files') {
			setTimeout(function() {
				tgtPath = ICEcoder.thisFileFolderType == "folder" ? ICEcoder.thisFileFolderLink : ICEcoder.thisFileFolderLink.substr(0,ICEcoder.thisFileFolderLink.lastIndexOf("|"));
				if(ICEcoder.draggingWithKey == "CTRL") {
					ICEcoder.copyFiles(ICEcoder.selectedFiles);
					ICEcoder.pasteFiles(tgtPath);
				} else {
					ICEcoder.moveFile(filePath,tgtPath.replace(/\|/g,"/") + "/" + fileName);
				}
			},4);
		};
		ICEcoder.mouseDown=false;
		ICEcoder.mouseDownInCM=false;
	},

// ==============
// FIND & REPLACE
// ==============

	// Update find & replace options based on user selection
	findReplaceOptions: function() {
		get('rText').style.display =
		get('replace').style.display =
		get('rTarget').style.display =
		document.findAndReplace.connector.value==t['and']
			? "inline-block" : "none";
	},

	// Find & replace text according to user selections
	findReplace: function(findString,resultsOnly,buttonClick,isCancel,findPrevious) {
		var find, replace, results, cM, cMdiff, thisCM, content, cursor, avgBlockH, addPadding, rBlocks, blockColor, replaceQS, targetQS, filesQS;

		if (isCancel){
			// Deselect by setting value to itself, then focus on editor
			get('find').value = get('find').value;
			ICEcoder.focus();
			return;
		}
		// Set findPrevious to false if not passed in
		if ("undefined" == typeof findPrevious) {
			findPrevious = false;
		}

		// Determine our find & replace strings and results display
		find		= findString.toLowerCase();
		replace		= get('replace').value;
		results		= get('results');

		// If we have something to find in currrent document
		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		if (thisCM && find.length>0 && document.findAndReplace.target.value==t['this document']) {
			content = thisCM.getValue().toLowerCase();
			// Find & replace the next instance, or all?
			if (document.findAndReplace.connector.value==t['and'] && buttonClick) {
				if (document.findAndReplace.replaceAction.value==t['replace'] && thisCM.getSelection().toLowerCase()==find) {
					thisCM.replaceSelection(replace,"around");
				} else if (document.findAndReplace.replaceAction.value==t['replace all']) {
					var rExp = new RegExp(find,"gi");
					thisCM.setValue(thisCM.getValue().replace(rExp,replace));
				}
			}

			// Get the content again, as it might of changed
			content = thisCM.getValue().toLowerCase();
			if (!ICEcoder.findMode||find!=ICEcoder.lastsearch) {
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
					ICEcoder.focus();
					ICEcoder.findMode = true;
				}

				// Display the find results bar
				// The avg block is either line height or fraction of space available
				avgBlockH = !ICEcoder.scrollBarVisible ? thisCM.defaultTextHeight() : parseInt(ICEcoder.content.style.height,10)/thisCM.lineCount();
				// Need to add padding if there's no scrollbar, so current line highlighting lines up with it
				addPadding = !ICEcoder.scrollBarVisible ? thisCM.heightAtLine(0) : 0;
				rBlocks = "";
				for (var i=1; i<=thisCM.lineCount(); i++) {
					blockColor = ICEcoder.resultsLines.indexOf(i)>-1 ? thisCM.getCursor().line+1 == i ? "#b00" : "#888" : "transparent"
					rBlocks += '<div style="position: absolute; display: block; width: 5px; height:'+avgBlockH+'px; background: '+blockColor+'; top: '+parseInt((avgBlockH*(i-1))+addPadding,10)+'px"></div>';
				}
				ICEcoder.content.contentWindow.document.getElementById('resultsBar').innerHTML = rBlocks;
				ICEcoder.content.contentWindow.document.getElementById('resultsBar').style.display = "inline-block";
				return true;

			} else {
				results.innerHTML = "No results";
				ICEcoder.content.contentWindow.document.getElementById('resultsBar').innerHTML = "";
				ICEcoder.content.contentWindow.document.getElementById('resultsBar').style.display = "none";
				return false;
			}
		} else {
			// Show the relevant multiple results popup
			if (find != "" && buttonClick) {
				replaceQS = "";
				targetQS = "";
				filesQS = "";
				if (document.findAndReplace.connector.value==t['and']) {
					replaceQS = "&replace="+replace;
				}
				if (document.findAndReplace.target.value.indexOf(t['file'])>=0) {
					targetQS = "&target="+document.findAndReplace.target.value.replace(/ /g,"-");
				}
				if (document.findAndReplace.target.value==t['selected files']) {
					filesQS = "&selectedFiles="+ICEcoder.selectedFiles.join(":");
				}
				find = find.replace(/\'/g, '\&#39;');
				find != encodeURIComponent(find) ? find = 'ICEcoder:'+encodeURIComponent(find) : find;
				ICEcoder.showHide('show',get('loadingMask'));
				get('mediaContainer').innerHTML = '<iframe src="'+iceLoc+'/lib/multiple-results.php?find='+find+replaceQS+targetQS+filesQS+'&csrf='+ICEcoder.csrf+'" class="whiteGlow" style="width: 700px; height: 500px"></iframe>';
			// We have nothing to search for, blank it all out
			} else {
				results.innerHTML = "No results";
				ICEcoder.content.contentWindow.document.getElementById('resultsBar').innerHTML = "";
				ICEcoder.content.contentWindow.document.getElementById('resultsBar').style.display = "none";
			}
		}
	},

	// Replace text in a file
	replaceInFile: function(fileRef,find,replace) {
		ICEcoder.serverQueue("add",iceLoc+"/lib/file-control-xhr.php?action=replaceText&find="+find+"&replace="+replace+"&csrf="+ICEcoder.csrf,encodeURIComponent(fileRef.replace(/\//g,"|")));
		ICEcoder.serverMessage('<b>'+t['Replacing text in']+'</b><br>'+fileRef);
	},

// ==============
// INFO & DISPLAY
// ==============

	// Get the caret position
	getCaretPosition: function() {
		var cM, cMdiff, thisCM, line, ch, chPos;

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;

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
		thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		ICEcoder.caretLocationType();
		ICEcoder.charDisplay.innerHTML = ICEcoder.caretLocType + ", Line: " + (thisCM.getCursor().line+1) + ", Char: " + thisCM.getCursor().ch;
	},

	// Update version display
	updateVersionsDisplay: function() {
		var versionsCount = ICEcoder.openFileVersions[ICEcoder.selectedTab-1];

		get('versionsDisplay').innerHTML = "undefined" != typeof versionsCount
			? ICEcoder.openFileVersions[ICEcoder.selectedTab-1] + " backup" +
				(versionsCount != 1 ? "s" : "")
			: "";
	},

	// Update the byte display
	updateByteDisplay: function() {
		var cM, cMdiff, thisCM;

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		ICEcoder.byteDisplay.innerHTML = thisCM.getValue().length.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + " bytes";
	},

	// Toggle the char/byte display
	showDisplay: function(show) {
		ICEcoder.byteDisplay.style.display = show == "byte" ? "inline-block" : "none";
		ICEcoder.charDisplay.style.display = show == "char" ? "inline-block" : "none";
	},

	// Show & hide target element
	showHide: function(doVis,elem) {
		elem.style.visibility = doVis=="show" ? 'visible' : 'hidden';
	},

	// Determine the CodeMirror instance we're using
	getcMInstance: function(tab) {
		return parent.ICEcoder.content.contentWindow[
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
		return parent.ICEcoder.content.contentWindow[
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

		ICEcoder.mouseX = e.pageX ? e.pageX : e.clientX + document.body.scrollLeft;
		ICEcoder.mouseY = e.pageY ? e.pageY : e.clientY + document.body.scrollTop;

		ICEcoder.area = area;
		if (area!="top") {
			ICEcoder.mouseY += 25 + 45;
		}
		if (area=="editor") {
			ICEcoder.mouseX += ICEcoder.filesW;
		}
		ICEcoder.dragCursorTest();
		if (ICEcoder.mouseY>62) {ICEcoder.setTabWidths();};
	},

	// Test if we need to show a drag cursor or not
	dragCursorTest: function() {
		var diffX, winH, cursorName, zone;

		// Dragging tabs, started after dragging for 10px from origin
		diffX = ICEcoder.mouseX - ICEcoder.diffStartX;
		if (ICEcoder.draggingTab!==false && ICEcoder.diffStartX && (diffX <= -10 || diffX >= 10)) {
			if (ICEcoder.mouseX > parseInt(ICEcoder.files.style.width,10)) {
				ICEcoder.tabDragMouseX = ICEcoder.mouseX - parseInt(ICEcoder.files.style.width,10) - ICEcoder.tabDragMouseXStart;
				ICEcoder.tabDragMove();
			}
		}

		// Dragging file manager, possible within 7px of file manager edge
		if (ICEcoder.ready) {
			winH = window.innerHeight;
			if (!ICEcoder.mouseDown) {ICEcoder.draggingFilesW = false};

			cursorName = (!ICEcoder.draggingTab && ((ICEcoder.mouseX > ICEcoder.filesW-7 && ICEcoder.mouseX < ICEcoder.filesW+7) || ICEcoder.draggingFilesW))
				? "w-resize"
				: "auto";
			if (ICEcoder.content.contentWindow.document && ICEcoder.filesFrame.contentWindow) {
				document.body.style.cursor = cursorName;
				if (zone = ICEcoder.content.contentWindow.document.body)	{zone.style.cursor = cursorName};
				if (zone = ICEcoder.filesFrame.contentWindow.document.body)	{zone.style.cursor = cursorName};
			}
		}
	},

	// Show or hide a server message
	serverMessage: function(message) {
		var serverMessage;

		serverMessage =	parent.get('serverMessage');
		if (message) {
			serverMessage.innerHTML = ICEcoder.xssClean(message).replace(/\&lt;b\&gt;/g,"<b>").replace(/\&lt;\/b\&gt;/g,"</b>").replace(/\&lt;br\&gt;/g,"<br>");
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
		thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;

		if (thisCM) {
			string = thisCM.getLine(thisCM.getCursor().line);
			rx = /(#[\da-f]{3}(?:[\da-f]{3})?\b|\b(?:rgb|hsl)a?\([\s\d%,.-]+\)|\b[a-z]+\b)/gi;

			while((match = rx.exec(string)) && thisCM.getCursor().ch > match.index+match[0].length);

			oldBlock = get('content').contentWindow.document.getElementById('cssColor');
			if (oldBlock) {oldBlock.parentNode.removeChild(oldBlock)};
			if (ICEcoder.codeAssist && ICEcoder.caretLocType=="CSS") {
				newBlock = document.createElement("div");
				newBlock.id = "cssColor";
				newBlock.style.position = "absolute";
				newBlock.style.display = "block";
				newBlock.style.width = newBlock.style.height = "20px";
				newBlock.style.zIndex = "1000";
				newBlock.style.background = match ? match[0] : '';
				newBlock.style.cursor = "pointer";
				newBlock.onclick = function() {ICEcoder.showColorPicker(match[0])};
				if (newBlock.style.backgroundColor=="") {newBlock.style.display = "none"};
				get('header').appendChild(newBlock);
				thisCM.addWidget(thisCM.getCursor(), get('cssColor'), true);
			}
		}
	},

	// Show color picker
	showColorPicker: function(color) {
		get('blackMask').style.visibility = "visible";
		get('mediaContainer').innerHTML = 	'<div id="picker" class="picker"></div><br><br>'+
							'<input type="text" id="color" name="color" value="#000" class="colorValue">'+
							'<input type="button" onClick="ICEcoder.insertColorValue(get(\'color\').value)" value="insert &gt;" class="insertColorValue"><br>'+
							'<input type="text" id="colorRGB" name="colorRGB" value="rgb(0,0,0)" class="colorValue">'+
							'<input type="button" onClick="ICEcoder.insertColorValue(get(\'colorRGB\').value)" value="insert &gt;" class="insertColorValue">';
		farbtastic('picker','color');
		if (color) {
			get('picker').farbtastic.setColor(color);
		}
	},

	// Init the canvas by drawing the image and setting the floating containers background size (5x zoom)
	initCanvasImage: function (imgThis) {
		var canvas, img;

		canvas = get('canvasPicker').getContext('2d');

		img = new Image();
		img.crossOrigin = "Anonymous";
		img.src = imgThis.src;

		// Issue with loading, display CORS error info
		img.onerror = function() {
			get('floatingContainer').style.visibility = "hidden";
			get('canvasPickerColorInfo').style.display = "none";
			get('canvasPickerCORSInfo').style.display = "block";
		}

		// On image load
		img.onload = function() {
			// Get width and height and draw this image into the canvas
			get('canvasPicker').width = imgThis.width;
			get('canvasPicker').height = imgThis.height;
			canvas.drawImage(img,0,0,imgThis.width,imgThis.height);

			// Display color picker info and hide CORS message
			get('canvasPickerColorInfo').style.display = "block";
			get('canvasPickerCORSInfo').style.display = "none";

			// Show image preview box on mouse over
			get('canvasPicker').onmouseover = function(event) {
				get('floatingContainer').style.visibility = "visible";
			};
			// Hide image preview box on mouse out
			get('canvasPicker').onmouseout = function(event) {
				get('floatingContainer').style.visibility = "hidden";
			};
		}

		document.getElementById('floatingContainer').style.backgroundSize = (imgThis.naturalWidth*5)+"px "+(imgThis.naturalHeight*5)+"px";
	},

	// Interact with the canvas image
	interactCanvasImage: function (imgThis) {
		var canvas, x, y, imgData, R, G, B, rgb, hex, textColor, fcElem, fcBGX, fcBGY;

		canvas = get('canvasPicker').getContext('2d');

		// Show pointer colors on mouse move over canvas
		get('canvasPicker').onmousemove = function(event) {
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
			hex = ICEcoder.rgbToHex(R,G,B);
			// set the values & BG colours of the input boxes
			get('rgbMouseXY').value = rgb;
			get('hexMouseXY').value = '#' + hex;
			get('hexMouseXY').style.backgroundColor = get('rgbMouseXY').style.backgroundColor = '#' + hex;
			textColor = R<128 || G<128 || B<128 && (R<200 && G<200 && B>50) ? '#fff' : '#000';
			get('hexMouseXY').style.color = get('rgbMouseXY').style.color = textColor;

			// Move the floating container to follow mouse pointer
			fcElem = get('floatingContainer');
			fcElem.style.left = ICEcoder.mouseX+20 + "px";
			fcElem.style.top = ICEcoder.mouseY + "px";
			// Move the background image for the container to match also
			// 5 x zoom, account for scaling down of large images and shift 25px of the hover div size
			// (55px is the 11x11 grid of pixels), minus 5px for centre row/col
			fcBGX = -((x*5)*(imgThis.naturalWidth/imgThis.width))+25;
			fcBGY = -((y*5)*(imgThis.naturalHeight/imgThis.height))+25;
			fcElem.style.backgroundPosition = fcBGX+"px "+fcBGY+"px";
		};

		// Set pointer colors on clicking canvas
		get('canvasPicker').onclick = function() {
			get('rgb').value = get('rgbMouseXY').value;
  			get('hex').value = get('hexMouseXY').value;
			get('hex').style.backgroundColor = get('rgb').style.backgroundColor = get('hex').value;
			get('hex').style.color = get('rgb').style.color = textColor;
		}
	},

	// Convert RGB values to Hex
	rgbToHex: function(R,G,B) {
		return ICEcoder.toHex(R)+ICEcoder.toHex(G)+ICEcoder.toHex(B);
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
		thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		cursor = thisCM.getTokenAt(thisCM.getCursor());
		thisCM.replaceRange(color,{line:thisCM.getCursor().line,ch:cursor.start},{line:thisCM.getCursor().line,ch:1000000});
	},

	// Change opacity of the file manager icons
	fMIconVis: function(icon, vis) {
		var i;

		if (i = get(icon)) {
			i.style.opacity = vis;
		}
	},

	// Check if a file is already open
	isOpen: function(file) {
		var i;

		file = file.replace(/\|/g, "/").replace(docRoot+iceRoot,"");
		i = ICEcoder.openFiles.indexOf(file);
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
			plugURL = plugURL+"&csrf="+ICEcoder.csrf;
		}
		ICEcoder['plugTimer'+plugRef] =
		// This window instances
			["_parent","_top","_self",""].indexOf(plugTarget) > -1
			? ICEcoder['plugTimer'+plugRef] = setInterval('window.location=\''+plugURL+'\'',plugTimer*1000*60)
		// fileControl iframe instances
			: plugTarget.indexOf("fileControl") == 0
			? ICEcoder['plugTimer'+plugRef] = setInterval(function() {
				ICEcoder.serverQueue("add",plugURL);ICEcoder.serverMessage(plugTarget.split(":")[1]);
				},plugTimer*1000*60)
		// _blank or named target window instances
			: ICEcoder['plugTimer'+plugRef] = setInterval('window.open(\''+plugURL+'\',\''+plugTarget+'\')',plugTimer*1000*60);

		// push the plugin ref into our array
		ICEcoder.pluginIntervalRefs.push(plugRef);
	},

	// Turning on/off the Code Assist
	codeAssistToggle: function() {
		var cM, cMdiff, fileName, fileExt;

		ICEcoder.codeAssist = !ICEcoder.codeAssist;
		get('codeAssistDisplay').style.backgroundPosition = ICEcoder.codeAssist ? "0 0" : "-16px 0";
		ICEcoder.cssColorPreview();
		ICEcoder.focus(ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? 'diff' : false);

		for (i=0;i<ICEcoder.cMInstances.length;i++) {
			fileName = ICEcoder.openFiles[i];
			fileExt = fileName.split(".");
			fileExt = fileExt[fileExt.length-1];
			if (fileExt == "js" || fileExt == "json") {
				cM = ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]];
				cMdiff = ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]+'diff'];
				if (!ICEcoder.codeAssist) {
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
	serverQueue: function(action,item,file,changes) {
		var cM, nextSaveID, txtArea, topSaveID, element, xhr, statusObj, timeStart;

		// If we have this exact item URL, it's almost certain we've got a repetitive save
		// situation and so clear the message and server queue item to avoid save jamming
		if (ICEcoder.serverQueueItems.indexOf(item) !== -1) {
			ICEcoder.serverMessage();
			ICEcoder.serverQueue("del",0);
			return;
		}

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
				// If we're saving as or the file version is undefined, set the temp save value as the contents
				if (item.indexOf('saveType=saveAs')>0 || item.indexOf('fileVersion=undefined')>0) {
					get('saveTemp'+nextSaveID).value = cM.getValue();
				// Else we can save the JSON version of the changes to implement
				} else {
					get('saveTemp'+nextSaveID).value = changes;
				}
			}
		} else if (action=="del") {
			if (ICEcoder.serverQueueItems[0] && ICEcoder.serverQueueItems[0].indexOf('action=save')>0) {
				topSaveID = nextSaveID-1;
				for (var i=1;i<topSaveID;i++) {
					get('saveTemp'+i).value = get('saveTemp'+(i+1)).value;
				}
				element = get('saveTemp'+topSaveID);
				element.parentNode.removeChild(element);
			}
			ICEcoder.serverQueueItems.splice(0,1);
		}

		// If we've just removed from the array and there's another action queued up, or we're triggering for the first time
		// then do the next requested process, stored at array pos 0
		if (action=="del" && ICEcoder.serverQueueItems.length>=1 || ICEcoder.serverQueueItems.length==1) {
			// If we have an item, we're not saving previous file refs and not loading
			if (item && (item.indexOf('saveFiles=')==-1 && item.indexOf('action=load')==-1)) {
				xhr = ICEcoder.xhrObj();
				xhr.onreadystatechange=function() {
					if (xhr.readyState==4) {
						// OK reponse?
						if (xhr.status==200) {
							// Parse the response as a JSON object
							statusObj = JSON.parse(xhr.responseText);

							// Set the action end time and time taken in JSON object
							statusObj.action.timeEnd = new Date().getTime();
							statusObj.action.timeTaken = statusObj.action.timeEnd - statusObj.action.timeStart;

							// User wanted raw (or both) output of the response?
							if (["raw","both"].indexOf(ICEcoder.fileDirResOutput) >= 0) {
								console.log(xhr.responseText);
							}
							// User wanted object (or both) output of the response?
							if (["object","both"].indexOf(ICEcoder.fileDirResOutput) >= 0) {
								console.log(statusObj);
							}
							// If error, show that, otherwise do whatever we're required to do next
							if (statusObj.status.error) {
								ICEcoder.message(statusObj.status.errorMsg);
								console.log("ICEcoder error info for your request...");
								console.log(statusObj);
								ICEcoder.serverMessage();
								ICEcoder.serverQueue('del',0);
							} else {
								eval(statusObj.action.doNext);
							}
						// Some other response? Display a message about that
						} else {
							ICEcoder.message(t['Sorry there was...']);
							console.log("ICEcoder error info for your request...");
							console.log(statusObj);
							ICEcoder.serverMessage();
							ICEcoder.serverQueue('del',0);
						}
					}
				};
				xhr.open("POST",ICEcoder.serverQueueItems[0],true);
				xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
				timeStart = new Date().getTime();

				// Save as events need to send all contents
				if (item.indexOf('action=saveAs')>0) {
					xhr.send('timeStart='+timeStart+'&file='+file+'&contents='+encodeURIComponent(document.getElementById('saveTemp1').value));
				// Save evens can just sent the changes
				} else if (item.indexOf('action=save')>0) {
					xhr.send('timeStart='+timeStart+'&file='+file+'&changes='+encodeURIComponent(document.getElementById('saveTemp1').value));
				// Another type of event
				} else {
					xhr.send('timeStart='+timeStart+'&file='+file);
				}
			} else {

				setTimeout(function() {
					if ("undefined" != typeof ICEcoder.serverQueueItems[0]) {
						ICEcoder.filesFrame.contentWindow.frames['fileControl'].location.href=ICEcoder.serverQueueItems[0];
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
		ICEcoder.showHide('hide',get('loadingMask'));
		ICEcoder.serverMessage('<b style="color: #d00">'+t['Cancelled tasks']+'</b>');
		setTimeout(function() {ICEcoder.serverMessage();},2000);
	},

	// Set the current previousFiles in the settings file
	setPreviousFiles: function() {
		var previousFiles;

		previousFiles = ICEcoder.openFiles.join(',').replace(/\//g,"|").replace(/(\|\[NEW\])|(,\|\[NEW\])/g,"").replace(/(^,)|(,$)/g,"");
		if (previousFiles=="") {previousFiles="CLEAR"};
		// Then send through to the settings page to update setting
		ICEcoder.serverQueue("add",iceLoc+"/lib/settings.php?saveFiles="+encodeURIComponent(previousFiles)+"&csrf="+ICEcoder.csrf);
		ICEcoder.updateLast10List(previousFiles);
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
				newFile = "<li class=\"pft-file ext-"+previousFiles[i].substring(previousFiles[i].lastIndexOf(".")+1)+"\" style=\"margin-left: -21px\"><a style=\"cursor:pointer\" onclick=\"ICEcoder.openFile('"+previousFiles[i].replace(/\|/g,"/")+"')\">"+previousFiles[i].replace(/\|/g,"/")+"</a></li>\n";

				// Get DOM elem for last 10 files
				last10Files = ICEcoder.content.contentWindow.document.getElementById('last10Files');

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
		if (ICEcoder.previousFiles.length>0 && ICEcoder.ask(t['Open previous files']+'\n\n'+ICEcoder.previousFiles.length+' files:\n'+ICEcoder.previousFiles.join('\n').replace(/\|/g,"/").replace(new RegExp(docRoot+iceRoot,'gi'),""))) {
			for (var i=0;i<ICEcoder.previousFiles.length;i++) {
				ICEcoder.thisFileFolderLink=ICEcoder.previousFiles[i].replace('|','/');
				ICEcoder.thisFileFolderType='file';
				ICEcoder.openFile();
			}
		}
	},

	// Show the settings screen
	settingsScreen: function(hide) {
		if (!hide) {
			get('mediaContainer').innerHTML = '<iframe src="'+iceLoc+'/lib/settings-screen.php" class="whiteGlow" style="width: 970px; height: 610px"></iframe>';
		}
		ICEcoder.showHide(hide?'hide':'show',get('blackMask'));
	},

	// Show the help screen
	helpScreen: function() {
		get('mediaContainer').innerHTML = '<iframe src="'+iceLoc+'/lib/help.php" class="whiteGlow" style="width: 840px; height: 485px"></iframe>';
		ICEcoder.showHide('show',get('blackMask'));
	},

	// Show the backup versions screen
	versionsScreen: function(file,versions) {
		get('mediaContainer').innerHTML = '<iframe src="'+iceLoc+'/lib/backup-versions.php?file='+file+'&csrf='+ICEcoder.csrf+'" class="whiteGlow" style="width: 970px; height: 640px"></iframe>';
		ICEcoder.showHide('show',get('blackMask'));
	},

	// Show the ICEcoder manual, loaded remotely
	showManual: function(version,section) {
		var sectionExtra;

		sectionExtra = section ? "#"+section : "";
		get('mediaContainer').innerHTML = '<iframe src="'+iceLoc+'/https://icecoder.net/manual?version='+version+sectionExtra+'" class="whiteGlow" style="width: 800px; height: 470px"></iframe>';
		ICEcoder.showHide('show',get('blackMask'));
	},

	// Show the properties screen
	propertiesScreen: function(fileName) {
		get('mediaContainer').innerHTML = '<iframe src="'+iceLoc+'/lib/properties.php?fileName='+fileName.replace(/\//g,"|")+'&csrf='+ICEcoder.csrf+'" class="whiteGlow" style="width: 660px; height: 330px"></iframe>';
		ICEcoder.showHide('show',get('blackMask'));
	},

	// Show the auto-logout warning screen
	autoLogoutWarningScreen: function() {
		get('mediaContainer').innerHTML = '<iframe src="'+iceLoc+'/lib/auto-logout-warning.php" id="autoLogoutIFrame" class="whiteGlow" style="width: 400px; height: 160px"></iframe>';
		ICEcoder.showHide('show',get('blackMask'));
	},

	// Show the plugins manager
	pluginsManager: function() {
		get('mediaContainer').innerHTML = '<iframe src="'+iceLoc+'/lib/plugins-manager.php" class="whiteGlow" style="width: 800px; height: 450px"></iframe>';
		ICEcoder.showHide('show',get('blackMask'));
	},

	// Go to localhost root
	goLocalhostRoot: function() {
		ICEcoder.filesFrame.contentWindow.frames['fileControl'].location.href = iceLoc+"/lib/go-localhost-root.php";
	},

	// Show the GitHub commit screen
	githubAction: function(action) {
		get('mediaContainer').innerHTML = '<iframe src="'+iceLoc+'/lib/github.php?action='+action+'&selectedFiles='+ICEcoder.selectedFiles.join(";")+'&csrf='+ICEcoder.csrf+'" class="whiteGlow" style="width: 340px; height: 340px"></iframe>';
		ICEcoder.showHide('show',get('blackMask'));
	},

	// Ask user for GitHub token
	githubTokenAsk: function(goNext) {
		if (githubAuthToken = ICEcoder.getInput(t['Please enter your...'],'')) {
			ICEcoder.filesFrame.contentWindow.frames['fileControl'].location.href = iceLoc+"/lib/github.php?action=auth&token="+githubAuthToken+"&goNext="+goNext+"&csrf="+ICEcoder.csrf;
			// Clear the token from the var for security
			githubAuthToken = "";
		}
	},

	// Show/Hide the GitHub file nav
	showHideGithubNav: function(vis) {
		get('githubNav').style.display	= vis == "show" ? "block" : "none";
		get('fileNav').style.display	= vis == "show" ? "none" : "block";
	},

	// Show the GitHub manager
	githubManager: function() {
		var githubAuthToken;
		if (ICEcoder.githubAuthTokenSet) {
			get('mediaContainer').innerHTML = '<iframe src="'+iceLoc+'/lib/github-manager.php" class="whiteGlow" style="width: 660px; height: 450px"></iframe>';
			ICEcoder.showHide('show',get('blackMask'));
		} else {
			ICEcoder.githubTokenAsk('showManager');
		}
	},

	// Toggle the GitHub diff on/off view
	githubDiffToggle: function() {
		var gHDiff;

		if (!ICEcoder.githubAuthTokenSet) {
			ICEcoder.githubTokenAsk('loadFiles');
		} else if (ICEcoder.githubDiff || ICEcoder.ask(t['This will compare...'])) {
			ICEcoder.githubDiff = !ICEcoder.githubDiff;
			gHDiff = ICEcoder.githubDiff ? "true" : "false";

			ICEcoder.filesFrame.src = "files.php?githubDiff="+gHDiff+"&csrf="+ICEcoder.csrf;
		}
	},

	// Show the FTP manager
	ftpManager: function() {
		get('mediaContainer').innerHTML = '<iframe src="'+iceLoc+'/lib/ftp-manager.php" class="whiteGlow" style="width: 660px; height: 550px"></iframe>';
		ICEcoder.showHide('show',get('blackMask'));
	},

	// Update the settings used when we make a change to them
	useNewSettings: function(themeURL,codeAssist,lockedNav,tagWrapperCommand,autoComplete,visibleTabs,fontSize,lineWrapping,lineNumbers,showTrailingSpace,matchBrackets,autoCloseTags,autoCloseBrackets,indentWithTabs,indentAuto,indentSize,pluginPanelAligned,bugFilePaths,bugFileCheckTimer,bugFileMaxLines,githubAuthTokenSet,updateDiffOnSave,autoLogoutMins,refreshFM) {
		var styleNode, thisCSS, strCSS, activeLineBG;

		// cut out ?microtime= at the end
		var cleanThemeUrl = themeURL.slice(0, themeURL.lastIndexOf("?"));
		// find out new theme name without leading path and trailing ".css"
		var newTheme = cleanThemeUrl.slice(cleanThemeUrl.lastIndexOf("/")+1,cleanThemeUrl.lastIndexOf("."));
		// if theme was not changed - no need to do all these tricks
		if (ICEcoder.theme !== newTheme){
			// Add new stylesheet for selected theme to editor
			ICEcoder.theme = newTheme;
			if (ICEcoder.theme=="editor") {ICEcoder.theme="icecoder"};
			styleNode = document.createElement('link');
			styleNode.setAttribute('rel', 'stylesheet');
			styleNode.setAttribute('type', 'text/css');
			styleNode.setAttribute('href', themeURL);
			ICEcoder.content.contentWindow.document.getElementsByTagName('head')[0].appendChild(styleNode);
			// Add new stylesheet for selected theme to top level (used by Minimap)
			styleNode = document.createElement('link');
			styleNode.setAttribute('rel', 'stylesheet');
			styleNode.setAttribute('type', 'text/css');
			styleNode.setAttribute('href', themeURL);
			document.getElementsByTagName('head')[0].appendChild(styleNode);
			if (["3024-day","base16-light","eclipse","elegant","mdn-like","neat","neo","paraiso-light","solarized","the-matrix","xq-light"].indexOf(ICEcoder.theme)>-1) {
				activeLineBG = "#ccc";
			} else if (["3024-night","blackboard","colorforth","liquibyte","night","tomorrow-night-bright","tomorrow-night-eighties","vibrant-ink"].indexOf(ICEcoder.theme)>-1) {
				activeLineBG = "#888";
			} else {
				activeLineBG = "#000";
			}
			ICEcoder.switchTab(ICEcoder.selectedTab);
		}

		// Check/uncheck Code Assist setting
		if (codeAssist != ICEcoder.codeAssist) {
			get('codeAssist').checked = codeAssist;
			ICEcoder.codeAssistToggle();
		}

		// Unlock/lock the file manager
		if (lockedNav != ICEcoder.lockedNav) {
			ICEcoder.lockUnlockNav();
			ICEcoder.changeFilesW(!lockedNav ? 'contract' : 'expand');
			ICEcoder.hideFileMenu();
		};

		// Update font size at top level
		thisCSS = document.styleSheets[0];
		strCSS = thisCSS.rules ? 'rules' : 'cssRules';
		thisCSS[strCSS][0].style['fontSize'] = fontSize;

		// Update font size in file manager
		thisCSS = ICEcoder.filesFrame.contentWindow.document.styleSheets[3];
		strCSS = thisCSS.rules ? 'rules' : 'cssRules';
		thisCSS[strCSS][0].style['fontSize'] = fontSize;

		// Update styles in editor
		thisCSS = ICEcoder.content.contentWindow.document.styleSheets[6];
		strCSS = thisCSS.rules ? 'rules' : 'cssRules';
		thisCSS[strCSS][0].style['fontSize'] = fontSize;
		thisCSS[strCSS][4].style['border-left-width'] = visibleTabs ? '1px' : '0';
		thisCSS[strCSS][4].style['margin-left'] = visibleTabs ? '-1px' : '0';
		thisCSS[strCSS][2].style.cssText = "background-color: " + activeLineBG + " !important";

		ICEcoder.lineWrapping = lineWrapping;
		ICEcoder.lineNumbers = lineNumbers;
		ICEcoder.showTrailingSpace = showTrailingSpace;
		ICEcoder.matchBrackets = matchBrackets;
		ICEcoder.autoCloseTags = autoCloseTags;
		ICEcoder.autoCloseBrackets = autoCloseBrackets;
		ICEcoder.indentWithTabs = indentWithTabs;
		ICEcoder.indentSize = indentSize;
		ICEcoder.indentAuto = indentAuto;
		for (var i=0;i<ICEcoder.cMInstances.length;i++) {
			// Main pane
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]].setOption("lineWrapping", ICEcoder.lineWrapping);
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]].setOption("lineNumbers", ICEcoder.lineNumbers);
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]].setOption("showTrailingSpace", ICEcoder.showTrailingSpace);
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]].setOption("matchBrackets", ICEcoder.matchBrackets);
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]].setOption("autoCloseTags", ICEcoder.autoCloseTags);
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]].setOption("autoCloseBrackets", ICEcoder.autoCloseBrackets);
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]].setOption("indentWithTabs", ICEcoder.indentWithTabs);
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]].setOption("indentUnit", ICEcoder.indentSize);
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]].setOption("tabSize", ICEcoder.indentSize);
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]].refresh();
			// Diff pane
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]+'diff'].setOption("lineWrapping", ICEcoder.lineWrapping);
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]+'diff'].setOption("lineNumbers", ICEcoder.lineNumbers);
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]+'diff'].setOption("showTrailingSpace", ICEcoder.showTrailingSpace);
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]+'diff'].setOption("matchBrackets", ICEcoder.matchBrackets);
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]+'diff'].setOption("autoCloseTags", ICEcoder.autoCloseTags);
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]+'diff'].setOption("autoCloseBrackets", ICEcoder.autoCloseBrackets);
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]+'diff'].setOption("indentWithTabs", ICEcoder.indentWithTabs);
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]+'diff'].setOption("indentUnit", ICEcoder.indentSize);
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]+'diff'].setOption("tabSize", ICEcoder.indentSize);
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]+'diff'].refresh();
		}

		if (tagWrapperCommand != ICEcoder.tagWrapperCommand) {
			ICEcoder.tagWrapperCommand = tagWrapperCommand;
		}

		if (autoComplete != ICEcoder.autoComplete) {
			ICEcoder.autoComplete = autoComplete;
		}

		get('plugins').style.left = pluginPanelAligned == "left" ? "0" : "auto";
		get('plugins').style.right = pluginPanelAligned == "right" ? "0" : "auto";

		// Restart bug checking
		ICEcoder.bugFilePaths = bugFilePaths;
		ICEcoder.bugFileCheckTimer = bugFileCheckTimer;
		ICEcoder.bugFileMaxLines = bugFileMaxLines;

		if (ICEcoder.bugFilePaths[0] != "") {
			ICEcoder.startBugChecking();
		} else {
			if ("undefined" != typeof ICEcoder.bugFileCheckInt) {
				clearInterval(ICEcoder.bugFileCheckInt);
			}
		}

		// Update diffs if we have a split pane
		if (ICEcoder.splitPane) {
			ICEcoder.updateDiffs();
		}

		// Set the flag to indicate if the GitHub auth token is set
		ICEcoder.githubAuthTokenSet = githubAuthTokenSet;

		// Set the flag to indicate if we update diff pane on save
		ICEcoder.updateDiffOnSave = updateDiffOnSave;

		// Set the auto-logout mins value
		ICEcoder.autoLogoutMins = autoLogoutMins;

		// Finally, refresh the file manager if we need to
		if (refreshFM) {ICEcoder.refreshFileManager()};
	},

	// Update and show/hide found results display?
	updateResultsDisplay: function(showHide) {
		ICEcoder.findReplace(get('find').value,true,false);
		get('results').style.display = showHide=="show" ? 'inline-block' : 'none';
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
		ICEcoder.filesFrame.contentWindow.frames['fileControl'].location.href="plugins/zip-it/index.php?zip="+tgt+"&csrf="+ICEcoder.csrf;
	},

	// Prompt to download our file
	downloadFile: function(file) {
		file=file.replace(/\//g,"|");
		ICEcoder.filesFrame.contentWindow.frames['fileControl'].location.href=iceLoc+"/lib/download.php?file="+file+"&csrf="+ICEcoder.csrf;
	},

	// Change permissions on a file/folder
	chmod: function(file,perms) {
		file = file.replace(iceRoot,"");
		ICEcoder.showHide('hide',get('blackMask'));
		ICEcoder.serverQueue("add",iceLoc+"/lib/file-control-xhr.php?action=perms&perms="+perms+"&csrf="+ICEcoder.csrf,encodeURIComponent(file));
		ICEcoder.serverMessage('<b>chMod '+perms+' on </b><br>'+file.replace(/\|/g,"/"));
	},

	// Open/show the preview window
	openPreviewWindow: function() {
		if (ICEcoder.openFiles.length>0) {
			var cM, cMdiff, thisCM, filepath, filename, fileExt;

			filepath = ICEcoder.openFiles[ICEcoder.selectedTab-1];
			filename = filepath.substr(filepath.lastIndexOf("/")+1);
			fileExt = filename.substr(filename.lastIndexOf(".")+1);
			cM = ICEcoder.getcMInstance();
			cMdiff = ICEcoder.getcMdiffInstance();
			thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;

			ICEcoder.previewWindowLoading = true;
			ICEcoder.previewWindow = window.open(filepath,"previewWindow",500,500);
			if (["md"].indexOf(fileExt) > -1) {
				ICEcoder.previewWindow.onload = function() {
					ICEcoder.previewWindowLoading = false;
					ICEcoder.previewWindow.document.documentElement.innerHTML = mmd(thisCM.getValue())
				};
			} else {
				ICEcoder.previewWindow.onload = function() {
					ICEcoder.previewWindowLoading = false;
					// Do the pesticide plugin if it exists
					try {ICEcoder.doPesticide();} catch(err) {};
					// Do the stats.js plugin if it exists
					try {ICEcoder.doStatsJS('open');} catch(err) {};
					// Do the responsive plugin if it exists
					try {ICEcoder.doResponsive();} catch(err) {};
				}
			}
		}
	},

	// Reset auto-logout timer
	resetAutoLogoutTimer: function() {
		if(ICEcoder.autoLogoutMins > 1 && ICEcoder.autoLogoutTimer > (ICEcoder.autoLogoutMins*60)-60) {
			ICEcoder.showHide('hide',get('blackMask'));
		}
		ICEcoder.autoLogoutTimer = 0;
	},

	// Logout of ICEcoder
	logout: function(type) {
		window.location = window.location + "?logout&"+(type ? "type="+type+"&" : "")+"csrf="+ICEcoder.csrf;
	},

	// Show a message
	outputMsg: function(msg) {
		ICEcoder.output.innerHTML += msg + "<br>";
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

		dM = ICEcoder.content.contentWindow.document.getElementById('dataMessage');
		dM.style.display = "block";
		dM.innerHTML = message;
	},

	// Update ICEcoder
	update: function() {
		var autoUpdate;

		autoUpdate = confirm(t['Please note for...']);
		if (autoUpdate) {
			ICEcoder.showHide('show',get('loadingMask'));
			window.location = iceLoc+"/lib/updater.php";
		} else {
			window.open("https://icecoder.net");
		}
	},

	// ICEcoder just updated
	updated: function() {
		get('blackMask').style.visibility = "visible";
		get('mediaContainer').innerHTML 	= '<h1 style="color: #fff; cursor: default">Thanks for updating to v'+ICEcoder.versionNo+'!</h1>'
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

		if(ICEcoder.bugReportStatus=="off") {
			ICEcoder.message(t['You can start...']);
		}
		if(ICEcoder.bugReportStatus=="error") {
			ICEcoder.message(t['Error cannot find...']);
		}
		if(ICEcoder.bugReportStatus=="ok") {
			ICEcoder.message(t['No new errors...']);
		}
		if(ICEcoder.bugReportStatus=="bugs") {
			// Close bug-report without saving previousFiles and without confirming close if we made changes on the bug report
			var bugReportOpenFilePos = ICEcoder.openFiles.indexOf(ICEcoder.bugReportPath.replace(/\|/g,"/"));
			if (bugReportOpenFilePos > -1) {
				ICEcoder.closeTab(bugReportOpenFilePos+1,'dontSetPV','dontAsk');
			}
			ICEcoder.openFile(ICEcoder.bugReportPath);
			ICEcoder.bugFilesSizesSeen = ICEcoder.bugFilesSizesActual;
		}
	},

	// Start bug checking by looking in bug file paths on a timer
	startBugChecking: function() {
		var bugCheckURL;

		if (ICEcoder.bugFileCheckTimer !== 0) {
			// Clear any existing interval
			if ("undefined" != typeof ICEcoder.bugFileCheckInt) {
				clearInterval(ICEcoder.bugFileCheckInt);
			}
			// Start a new timer
			ICEcoder.bugFilesSizesSeen = [];
			ICEcoder.bugFileCheckInt = setInterval(function() {
				bugCheckURL =  iceLoc+"/lib/bug-files-check.php?";
				bugCheckURL += "files="+(ICEcoder.bugFilePaths[0] !== "" ? ICEcoder.bugFilePaths.join() : "null").replace(/\//g,"|");
				bugCheckURL += "&filesSizesSeen=";
				if (ICEcoder.bugFilesSizesSeen.length != ICEcoder.bugFilePaths.length) {
					// Fill the array with nulls
					for (var i=0; i<ICEcoder.bugFilePaths.length; i++) {
						ICEcoder.bugFilesSizesSeen[i] = "null";
					}
				}
				bugCheckURL += ICEcoder.bugFilesSizesSeen.join();
				bugCheckURL += "&maxLines="+ICEcoder.bugFileMaxLines;
				bugCheckURL += "&csrf="+ICEcoder.csrf;

				var xhr = ICEcoder.xhrObj();

				xhr.onreadystatechange=function() {
					if (xhr.readyState==4 && xhr.status==200) {
						// console.log(xhr.responseText);
						var statusArray = JSON.parse(xhr.responseText);
						// console.log(statusArray);

						get('bugIcon').style.backgroundPosition =
						statusArray['result'] == "off" ? "0 0" :
						statusArray['result'] == "ok" ? "-32px 0" :
						statusArray['result'] == "bugs" ? "-48px 0" :
						"-16px 0"; // if the result is 'error' or another value
						ICEcoder.bugReportStatus = statusArray['result'];
						if (ICEcoder.bugFilesSizesSeen[0]=="null") {
							ICEcoder.bugFilesSizesSeen = statusArray['filesSizesSeen'];
						}
						ICEcoder.bugFilesSizesActual = statusArray['filesSizesSeen'];
						ICEcoder.bugReportPath = statusArray['bugReportPath'];

					}
				};
				// console.log('Calling '+bugCheckURL+' via XHR');
				xhr.open("GET",bugCheckURL,true);
				xhr.send();

			},parseInt(ICEcoder.bugFileCheckTimer*1000,10));
			// State that we're checking for bugs
			ICEcoder.bugReportStatus = "ok";
		} else {
			if ("undefined" != typeof ICEcoder.bugFileCheckInt) {
				clearInterval(ICEcoder.bugFileCheckInt);
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

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		printIFrame = ICEcoder.filesFrame.contentWindow.frames['fileControl'];
		// Print page content injected into iFrame, escaped with pre and xssClean
		printIFrame.window.document.body.innerHTML = '<!DOCTYPE html><head><title>ICEcoder code output</title></head><body><pre style="white-space: pre-wrap">'+ICEcoder.xssClean(thisCM.getValue())+'</pre></body></html>';
		printIFrame.focus();
		printIFrame.print();
		// Focus back on code
		thisCM.focus();
	},

	// Update the title tag to indicate any changes
	indicateChanges: function() {
		var winTitle;

		if (!ICEcoder.loadingFile) {
			winTitle = "ICEcoder v "+ICEcoder.versionNo;
			for(var i=1;i<=ICEcoder.savedPoints.length;i++) {
				if (ICEcoder.savedPoints[i-1]!=ICEcoder.getcMInstance(i).changeGeneration()) {
					// We have an unsaved tab, indicate that in the title
					winTitle += " \u2744";
					break;
				}
			}
			document.title = winTitle;
		}
	},

// ==============
// TABS
// ==============

	// Change tabs by switching visibility of instances
	switchTab: function(newTab,noFocus) {
		var cM, cMdiff, thisCM;

		// If we're not switching to same tab (for some reason), note the previous tab
		if (newTab !== ICEcoder.selectedTab) {
			ICEcoder.prevTab = ICEcoder.selectedTab;
		}

		// Identify tab that's currently selected & get the instance
		ICEcoder.selectedTab = newTab;
		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;

		if (thisCM) {
			// Switch mode to HTML, PHP, CSS etc
			ICEcoder.switchMode();

			// Set all cM instances to be hidden, then make our selected instance visible
			for (var i=0;i<ICEcoder.cMInstances.length;i++) {
				ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]].getWrapperElement().style.display = "none";
				ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]+'diff'].getWrapperElement().style.display = "none";
			}
			cM.setOption('theme',ICEcoder.theme);
			cMdiff.setOption('theme',ICEcoder.theme + " diff");
			cM.getWrapperElement().style.display = "block";
			cMdiff.getWrapperElement().style.display = "block";

			// Redo our diffs if split pane
			if (ICEcoder.splitPane) {
				ICEcoder.updateDiffs();
			}

			// Focus on & refresh our selected instance
			if (!noFocus) {setTimeout(function() {ICEcoder.focus();},4);}
			cM.refresh();
			cMdiff.refresh();

			ICEcoder.updateFunctionClassList();
			// Update the minimap nav
			if ("undefined" != typeof doMiniNav) {
				clearTimeout(doMiniNav);
			}
			doMiniNav = setTimeout(function() {
				ICEcoder.setMinimap();
			},ICEcoder.loadingFile ? 0 : 100);
			get('docExplorer').style.display = "block";
			get('docExplorer').style.right = "-400px";

			ICEcoder.highlightGitDiffs();

			// Highlight the selected tab
			ICEcoder.redoTabHighlight(ICEcoder.selectedTab);

			// Redo our find display
			ICEcoder.findMode = false;
			ICEcoder.findReplace(get('find').value,true,false);

			// Update our versions display
			ICEcoder.updateVersionsDisplay();

			// Finally, update the cursor display
			ICEcoder.getCaretPosition();
			ICEcoder.updateCharDisplay();
			ICEcoder.updateByteDisplay();
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

		cM.removeLineClass(ICEcoder['cMActiveLinecM'+ICEcoder.cMInstances[ICEcoder.selectedTab-1]], "background");
		ICEcoder['cMActiveLinecM'+ICEcoder.selectedTab] = cM.addLineClass(0, "background", "cm-s-activeLine");
		ICEcoder.nextcMInstance++;

		// Also save?
		if (alsoSave) {
			ICEcoder.saveFile();
		}
	},

	// Create a new tab for a file
	createNewTab: function(isNew) {
		var closeTabLink, fileName;

		// Push new file into array
		ICEcoder.openFiles.push(ICEcoder.shortURL);

		// Setup a new tab
		closeTabLink = '<a nohref onClick="ICEcoder.closeTab(parseInt(this.parentNode.id.slice(3),10))"><img src="'+iceLoc+'/images/nav-close.gif" class="closeTab" onMouseOver="prevBG=this.style.backgroundColor;this.style.backgroundColor=\'#333\'; ICEcoder.overCloseLink=true" onMouseOut="this.style.backgroundColor=prevBG; ICEcoder.overCloseLink=false"></a>';
		get('tab'+(ICEcoder.openFiles.length)).style.display = "inline-block";
		fileName = ICEcoder.openFiles[ICEcoder.openFiles.length-1];
		get('tab'+(ICEcoder.openFiles.length)).innerHTML = closeTabLink + " " + fileName.slice(fileName.lastIndexOf("/")).replace(/\//,"");
		get('tab'+(ICEcoder.openFiles.length)).title = "/" + ICEcoder.openFiles[ICEcoder.openFiles.length-1].replace(/\//,"");

		// Set the widths
		ICEcoder.setTabWidths();

		// Highlight it and state it's selected
		ICEcoder.redoTabHighlight(ICEcoder.openFiles.length);
		ICEcoder.selectedTab=ICEcoder.openFiles.length;

		// Add a new value ready to indicate if this content has been changed
		ICEcoder.savedPoints.push(0);
		ICEcoder.savedContents.push("");

		if (!isNew) {
			ICEcoder.setPreviousFiles();
		}
	},

	// Cycle to next tab
	nextTab: function() {
		var goToTab;

		goToTab = ICEcoder.selectedTab+1 <= ICEcoder.openFiles.length ? ICEcoder.selectedTab+1 : 1;
		ICEcoder.switchTab(goToTab,'noFocus');
	},

	// Cycle to next tab
	previousTab: function() {
		var goToTab;

		goToTab = ICEcoder.selectedTab-1 >= 1 ? ICEcoder.selectedTab-1 : ICEcoder.openFiles.length;
		ICEcoder.switchTab(goToTab,'noFocus');
	},

	// Create a new tab for a file
	renameTab: function(tabNum,newName) {
		var closeTabLink, fileName;

		// Push new file into array
		ICEcoder.openFiles[tabNum-1] = newName;

		// Setup a new tab
		closeTabLink = '<a nohref onClick="ICEcoder.closeTab(parseInt(this.parentNode.id.slice(3),10))"><img src="'+iceLoc+'/images/nav-close.gif" class="closeTab" onMouseOver="prevBG=this.style.backgroundColor;this.style.backgroundColor=\'#333\'; ICEcoder.overCloseLink=true" onMouseOut="this.style.backgroundColor=prevBG; ICEcoder.overCloseLink=false"></a>';
		fileName = ICEcoder.openFiles[tabNum-1];
		get('tab'+tabNum).innerHTML = closeTabLink + " " + fileName.slice(fileName.lastIndexOf("/")).replace(/\//,"");
		get('tab'+tabNum).title = "/" + ICEcoder.openFiles[tabNum-1].replace(/\//,"");
	},

	// Reset all tabs to be without a highlight and then highlight the selected
	redoTabHighlight: function(selectedTab) {
		var tColor, fileLink;

		for(var i=1;i<=ICEcoder.savedPoints.length;i++) {
			if (get('tab'+i).childNodes[0]) {
				get('tab'+i).childNodes[0].childNodes[0].style.backgroundColor = ICEcoder.savedPoints[i-1]!=ICEcoder.getcMInstance(i).changeGeneration()
				? "#b00" : "transparent";
			}

			tColor = i==selectedTab ? ICEcoder.tabFGselected : ICEcoder.tabFGnormalTab;
			if ("undefined" != typeof ICEcoder.openFiles[i-1] && ICEcoder.openFiles[i-1] != "/[NEW]") {
				fileLink = ICEcoder.filesFrame.contentWindow.document.getElementById(ICEcoder.openFiles[i-1].replace(/\//g,"|"));
				if (fileLink) {
					fileLink.style.backgroundColor = i==selectedTab ? ICEcoder.tabBGcurrent : ICEcoder.tabBGopen;
					fileLink.style.color = i==selectedTab ? ICEcoder.tabFGcurrent : ICEcoder.tabFGopenFile;
				};
			}
			get('tab'+i).style.color = tColor;
			get('tab'+i).style.background = i==selectedTab ? ICEcoder.tabBGcurrent : ICEcoder.tabBGopen;
		}
	},

	// Close the tab upon request
	closeTab: function(closeTabNum, dontSetPV, dontAsk) {
		var cM, cMdiff, thisCM, okToRemove, closeFileName;

		// If we haven't specified, close current tab
		if (!closeTabNum) {closeTabNum = ICEcoder.selectedTab};

		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		okToRemove = true;
		if (!dontAsk && ICEcoder.savedPoints[closeTabNum-1]!=ICEcoder.getcMInstance(closeTabNum).changeGeneration()) {
			okToRemove = ICEcoder.ask(t['You have made...']);
		}

		if (okToRemove) {
			// Get the filename of tab we're closing
			closeFileName = ICEcoder.openFiles[closeTabNum-1];

			// recursively copy over all tabs & data from the tab to the right, if there is one
			for (var i=closeTabNum;i<ICEcoder.openFiles.length;i++) {
				get('tab'+i).innerHTML = get('tab'+(i+1)).innerHTML;
				get('tab'+i).title = get('tab'+(i+1)).title;
				ICEcoder.openFiles[i-1] = ICEcoder.openFiles[i];
				ICEcoder.openFileMDTs[i-1] = ICEcoder.openFileMDTs[i];
				ICEcoder.openFileVersions[i-1] = ICEcoder.openFileVersions[i];
			}
			// hide the instance we're closing by setting the hide class and removing from the array
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[closeTabNum-1]].getWrapperElement().style.display = "none";
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[closeTabNum-1]+'diff'].getWrapperElement().style.display = "none";
			ICEcoder.cMInstances.splice(closeTabNum-1,1);
			// clear the rightmost tab (or only one left in a 1 tab scenario) & remove from the array
			get('tab'+ICEcoder.openFiles.length).style.display = "none";
			get('tab'+ICEcoder.openFiles.length).innerHTML = "";
			get('tab'+ICEcoder.openFiles.length).title = "";
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
				ICEcoder.fMIconVis('fMView',0.3);
				get('docExplorer').style.display = "none";
			} else {
				// Switch the mode & the tab
				ICEcoder.switchMode();
				ICEcoder.switchTab(ICEcoder.selectedTab);
			}
			// Highlight the selected tab after splicing the change state out of the array
			ICEcoder.savedPoints.splice(closeTabNum-1,1);
			ICEcoder.savedContents.splice(closeTabNum-1,1);
			ICEcoder.redoTabHighlight(ICEcoder.selectedTab);

			// Remove any highlighting from the file manager
			ICEcoder.selectDeselectFile('deselect',ICEcoder.filesFrame.contentWindow.document.getElementById(closeFileName.replace(/\//g,"|")));

			if (!dontSetPV) {
				ICEcoder.setPreviousFiles();
			}

			// Update the versions display
			ICEcoder.updateVersionsDisplay();

			// Update the title tag to indicate any changes
			ICEcoder.indicateChanges();
		}
		// Lastly, stop it from trying to also switch tab
		ICEcoder.canSwitchTabs=false;
		// and set the widths
		ICEcoder.setTabWidths('posOnlyNewTab');
		setTimeout(function() {ICEcoder.canSwitchTabs=true;},100);
	},

	// Close all tabs
	closeAllTabs: function() {
		if (ICEcoder.cMInstances.length>0 && ICEcoder.ask(t['Close all tabs'])) {
			for (var i=ICEcoder.cMInstances.length; i>0; i--) {
				ICEcoder.closeTab(i, i>1? true:false);
			}
		}
		// Update the title tag to indicate any changes
		ICEcoder.indicateChanges();
	},

	// Set the tabs width
	setTabWidths: function(posOnlyNewTab) {
		var availWidth, avgWidth, tabWidth, lastLeft, lastWidth;

		if (ICEcoder.ready) {
			availWidth = parseInt(ICEcoder.content.style.width,10)-53-22-10; // - left margin - new tab - right margin
			avgWidth = (availWidth/ICEcoder.openFiles.length)-18;
			tabWidth = -18; // Incl 18px offset
			lastLeft = 53;
			lastWidth = 0;
			ICEcoder.tabLeftPos = [];
			for (var i=0;i<ICEcoder.openFiles.length;i++) {
				if (posOnlyNewTab) {i=ICEcoder.openFiles.length};
				tabWidth = ICEcoder.openFiles.length*(150+18) > availWidth ? parseInt(avgWidth*i,10) - parseInt(avgWidth*(i-1),10) : 150;
				lastLeft = i==0 ? 53 : parseInt(get('tab'+(i)).style.left,10);
				lastWidth = i==0 ? 0 : parseInt(get('tab'+(i)).style.width,10)+18;
				if (!posOnlyNewTab) {
					get('tab'+(i+1)).style.left = (lastLeft+lastWidth) + "px";
					get('tab'+(i+1)).style.width = tabWidth + "px";
				} else {
					tabWidth = -18;
				}
				ICEcoder.tabLeftPos.push(lastLeft+lastWidth);
			}
			get('newTab').style.left = (lastLeft+lastWidth+tabWidth+18) + "px";
		}
	},

	// Tab dragging start
	tabDragStart: function(tab) {
		ICEcoder.draggingTab = tab;
		ICEcoder.diffStartX = ICEcoder.mouseX;
		ICEcoder.tabDragMouseXStart = (ICEcoder.mouseX - (parseInt(ICEcoder.files.style.width,10)+53+18)) % 150;
		// Put tab we're dragging over others
		get('tab'+tab).style.zIndex = 2;
		// Set classes for other tabs (tabSlide) and the one we're dragging (tabDrag)
		for (var i=1; i<=ICEcoder.openFiles.length; i++) {
			get('tab'+i).className = i!==tab
			? "tab tabSlide"
			: "tab tabDrag";
		}
	},

	// Tab dragging
	tabDragMove: function() {
		var lastTabWidth, thisLeft, dragTabNo, tabWidth;

		lastTabWidth = parseInt(get('tab'+ICEcoder.openFiles.length).style.width,10)+18;

		// Set the left position but stay within left side (53) and new tab
		ICEcoder.thisLeft = thisLeft = ICEcoder.tabDragMouseX >= 53
		? ICEcoder.tabDragMouseX <= parseInt(get('newTab').style.left,10) - lastTabWidth
		? ICEcoder.tabDragMouseX : (parseInt(get('newTab').style.left,10) - lastTabWidth) : 53;

		get('tab'+ICEcoder.draggingTab).style.left = thisLeft + "px";

		ICEcoder.dragTabNo = dragTabNo = ICEcoder.draggingTab;

		// Set the opacities of tabs then positions of tabs we're not dragging
		for (var i=1; i<=ICEcoder.openFiles.length; i++) {
			get('tab'+i).style.opacity = i == ICEcoder.draggingTab ? 1 : 0.5;
			tabWidth = ICEcoder.tabLeftPos[i] ? ICEcoder.tabLeftPos[i] - ICEcoder.tabLeftPos[i-1] : tabWidth;
			if (i!=ICEcoder.draggingTab) {
				if (i < ICEcoder.draggingTab) {
					get('tab'+i).style.left = thisLeft <= ICEcoder.tabLeftPos[i-1]
					? ICEcoder.tabLeftPos[i-1]+tabWidth
					: ICEcoder.tabLeftPos[i-1];
				} else {
					get('tab'+i).style.left = thisLeft >= ICEcoder.tabLeftPos[i-1]
					? ICEcoder.tabLeftPos[i-1]-tabWidth
					: ICEcoder.tabLeftPos[i-1];
				}
			}
		}
	},

	// Tab dragging end
	tabDragEnd: function() {
		var swapWith, tempArray;

		// Set the tab widths
		ICEcoder.setTabWidths();
		// Determin what tabs we've swapped and reset classname, opacity & z-index for all
		for (var i=1; i<=ICEcoder.openFiles.length; i++) {
			if (ICEcoder.thisLeft >= ICEcoder.tabLeftPos[i-1]) {
				swapWith = ICEcoder.thisLeft == ICEcoder.tabLeftPos[0] ? 1 : ICEcoder.dragTabNo > i ? i+1 : i;
			}
			get('tab'+i).className = "tab";
			get('tab'+i).style.opacity = 1;
			if (i!=ICEcoder.dragTabNo) {
				get('tab'+i).style.zIndex = 1;
			} else {
				setTimeout(function() {
					get('tab'+i).style.zIndex = 1;
				},150);
			}
		}
		if (ICEcoder.thisLeft && ICEcoder.thisLeft!==false) {
			// Make a number ascending array
			tempArray = [];
			for (var i=1;i<=ICEcoder.openFiles.length;i++) {
				tempArray.push(i);
			}
			// Then swap our tab numbers
			tempArray.splice(ICEcoder.dragTabNo-1,1);
			tempArray.splice(swapWith-1,0,ICEcoder.dragTabNo);
			// Now we have an order to sort against
			ICEcoder.sortTabs(tempArray);
		}
		ICEcoder.setTabWidths();
		ICEcoder.draggingTab = false;
		ICEcoder.thisLeft = false;
	},

	// Sort tabs into new order
	sortTabs: function(newOrder) {
		var a, b, savedPoints = [], savedContents = [], openFiles = [], openFileMDTs = [], openFileVersions = [], cMInstances = [], selectedTabWillBe;

		// Setup an array of our actual arrays and the blank ones
		a = [ICEcoder.savedPoints, ICEcoder.savedContents, ICEcoder.openFiles, ICEcoder.openFileMDTs, ICEcoder.openFileVersions, ICEcoder.cMInstances];
		b = [savedPoints, savedContents, openFiles, openFileMDTs, openFileVersions, cMInstances];
		// Push the new order values into array b then set into array a
		for (var i=0;i<a.length;i++) {
			for (var j=0;j<a[i].length;j++) {
				b[i].push(a[i][newOrder[j]-1]);
			}
			a[i] = b[i];
		}
		// Begin swapping tab id's around to an ascending order and work out new selectedTab
		for (var i=0;i<newOrder.length;i++) {
			get('tab'+newOrder[i]).id = "tab" + (i+1) + ".temp";
			if (ICEcoder.selectedTab == newOrder[i]) {
				selectedTabWillBe = (i+1);
			}
		}
		// Now remove the .temp part from all tabs to get new ascending order
		for (var i=0;i<newOrder.length;i++) {
			get('tab'+(i+1)+'.temp').id = "tab"+(i+1);
		}
		// Set the classname for sliding
		if (get('tab'+selectedTabWillBe)) {
			get('tab'+selectedTabWillBe).className = "tab tabSlide";
		}
		// Finally, set the array values, tab widths and switch tab
		ICEcoder.savedPoints = a[0];
		ICEcoder.savedContents = a[1];
		ICEcoder.openFiles = a[2];
		ICEcoder.openFileMDTs = a[3];
		ICEcoder.openFileVersions = a[4];
		ICEcoder.cMInstances = a[5];
		ICEcoder.setTabWidths();
		ICEcoder.switchTab(selectedTabWillBe);
	},

	// Alphabetize tabs
	alphaTabs: function() {
		if (ICEcoder.openFiles.length>0) {
			var currentArray, currentArrayFull, alphaArray, nextValue, nextPos;

			currentArray = [];
			currentArrayFull = [];
			alphaArray = [];
			// Get filenames, full paths and set classname for sliding
			for (var i=0;i<ICEcoder.openFiles.length;i++) {
				currentArray.push(ICEcoder.openFiles[i].slice(ICEcoder.openFiles[i].lastIndexOf('/')+1));
				currentArrayFull.push(ICEcoder.openFiles[i]);
				get('tab'+(i+1)).className = "tab tabSlide";
			}
			// Get our next value, which is the next filename alpha lowest value and full path
			while (currentArray.length>0) {
				nextValue = currentArray[0];
				nextValueFull = currentArrayFull[0];
				nextPos = 0;
				for (var i=0;i<currentArray.length;i++) {
					if (currentArray[i] < nextValue) {
						nextValue  = currentArray[i];
						nextValueFull  = ICEcoder.openFiles[ICEcoder.openFiles.indexOf(currentArrayFull[i])];
						nextPos = i;
					}
				}
				// When we've got it, push into alphaArray and splice out of arrays
				alphaArray.push((ICEcoder.openFiles.indexOf(nextValueFull)+1));
				currentArray.splice(nextPos,1);
				currentArrayFull.splice(nextPos,1);
			}
			// Once done, sort our tabs into new order
			ICEcoder.sortTabs(alphaArray);
		}
	},

// ==============
// UI
// ==============

	// Detect keys/combos plus identify our area and set the vars, perform actions
	interceptKeys: function(area, evt) {
		var key, cM, cMdiff, thisCM;

		key = evt.keyCode ? evt.keyCode : evt.which ? evt.which : evt.charCode;

		// Reset the auto-logout timer
		ICEcoder.resetAutoLogoutTimer();

		// Detect if we type s,n,a,k,e keys with content saved, if so start snake game
		if (!ICEcoder.last5Keys) {ICEcoder.last5Keys = [];}
		ICEcoder.last5Keys.push(key);
		if (ICEcoder.last5Keys.length == 6) {
			ICEcoder.last5Keys.shift();
		}
		if (ICEcoder.last5Keys.join() == "83,78,65,75,69") {
			setTimeout(function() {
				// Undo back to pre 'snake' word
				cM = ICEcoder.getcMInstance();
				var undoCounts = 0;
				var startCG = cM.changeGeneration();
				while (cM.changeGeneration() > startCG-5) {
					cM.undo();
					undoCounts++;
				}
				// If we have content saved
				if (ICEcoder.savedPoints[ICEcoder.selectedTab-1] == cM.changeGeneration()) {
					// Start snake game
					ICEcoder.startSnake();
				// If we don't, redo snake word
				} else {
					for (var i=1; i<=undoCounts; i++) {
						cM.redo();
					}
				}
			},0);
		}

		// Detect arrow keys if playing snake
		if (ICEcoder.snakePlaying) {
			if (key==37) {ICEcoder.snakeDir = 'left'}
			if (key==39) {ICEcoder.snakeDir = 'right'}
			if (key==38) {ICEcoder.snakeDir = 'up'}
			if (key==40) {ICEcoder.snakeDir = 'down'}
			return false;
		}

		// Mac command key handling (224 = Moz, 91/93 = Webkit Left/Right Apple)
		if (key==224 || key==91 || key==93) {
			ICEcoder.cmdKey = true;
		}

		// F1 (zoom code out non declaration lines)
		if (key === 112) {
			if (ICEcoder.codeZoomedOut) {
				return;
			}
			ICEcoder.codeZoomedOut = true;

			cM = ICEcoder.getcMInstance();
			// For every line in the current editor, add code-zoomed-out class if not a function/class declaration line
			for (var i=0; i<cM.lineCount(); i++) {
				var nonDeclareLine = true;
				for (var j=0; j<ICEcoder.functionClassList.length; j++) {
					if (ICEcoder.functionClassList[j].line == i) {
						nonDeclareLine = false;
					}
				}
				if (nonDeclareLine) {
					cM.addLineClass(i, "wrap", "code-zoomed-out");
				}
			}
			// Refresh is necessary to re-draw lines
			cM.refresh();
			return false;
		};

		// DEL (Delete file)
		if (key==46 && area == "files") {
			ICEcoder.deleteFiles();
	        	return false;
		};

		// Alt key down?
		if (evt.altKey) {
			// detect alt right
			var isAltRight	= (evt.ctrlKey||ICEcoder.cmdKey) ? true:false;

			// tag wrapper, add line break at end or focus on file manager
			if (
				(ICEcoder.tagWrapperCommand=="ctrl+alt" && isAltRight) // CTRL/Cmd + alt left + key || alt right + key
				|| (ICEcoder.tagWrapperCommand=="alt-left" && !isAltRight) // alt left + key
			) {
				if (area=="content") {
					if (key==68) {ICEcoder.tagWrapper('div'); return false;}
					else if (key==83) {ICEcoder.tagWrapper('span'); return false;}
					else if (key==80) {ICEcoder.tagWrapper('p'); return false;}
					else if (key==65) {ICEcoder.tagWrapper('a'); return false;}
					else if (key==49) {ICEcoder.tagWrapper('h1'); return false;}
					else if (key==50) {ICEcoder.tagWrapper('h2'); return false;}
					else if (key==51) {ICEcoder.tagWrapper('h3'); return false;}
					else if (key==13) {ICEcoder.addLineBreakAtEnd(); return false;}
					else if (key==37) {ICEcoder.filesFrame.contentWindow.focus();return false;}
					else {return key;}
				}
				// Focus on file manager (outside of content area) or last editor pane
				if (key==37) {ICEcoder.filesFrame.contentWindow.focus();return false;}
				else if (key==39) {ICEcoder.focus(ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? true : false);return false;}
				else {return key;}
			// Alt+Enter (Insert Line After)
			} else if (key==13) {
				ICEcoder.insertLineAfter();
				return false;
			} else {return key;}

		} else {

			// Shift+Enter (Insert Line Before)
			if(key==13 && evt.shiftKey) {
				ICEcoder.insertLineBefore();
	        		return false;

			// CTRL/Cmd+F (Find next)
			// and
			// CTRL/Cmd+G (Find previous)
			} else if((key==70||key==71) && (evt.ctrlKey||ICEcoder.cmdKey)) {
				var find = get('find');
				cM = ICEcoder.getcMInstance();
				cMdiff = ICEcoder.getcMdiffInstance();
				thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
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
				get('goToLineNo').focus();
				find.focus();
				// Trigger the find/replace operation
				if(key==70) {
					// Find next
					get('findReplaceSubmit').click();
				} else {
					// Find previous
					ICEcoder.findReplace(document.getElementById('find').value,false,true,false,'findPrevious');
				}
	        		return false;

			// CTRL/Cmd+L (Go to line)
			} else if(key==76 && (evt.ctrlKey||ICEcoder.cmdKey)) {
				var goToLineInput = get('goToLineNo');
				goToLineInput.select();
				// this is trick for Chrome - after you have used Ctrl-F once, when
				// you try using Ctrl-F another time, somewhy Chrome still thinks,
				// that find has focus and refuses to give it focus second time.
				get('find').focus();
				goToLineInput.focus();
	        		return false;

			// CTRL/Cmd+I (Get info)
			} else if(key==73 && (evt.ctrlKey||ICEcoder.cmdKey) && area == "content") {
				ICEcoder.searchForSelected();
	        		return false;

			// CTRL/Cmd+backspace arrow (Go to previous tab selected)
			} else if(key==8 && (evt.ctrlKey||ICEcoder.cmdKey)) {
				if (ICEcoder.prevTab !== 0) {
					ICEcoder.switchTab(ICEcoder.prevTab);
				}
	        		return false;

			// CTRL/Cmd+right arrow (Tab to right)
			} else if(key==39 && (evt.ctrlKey||ICEcoder.cmdKey) && area!="content") {
				ICEcoder.nextTab();
	        		return false;

			// CTRL/Cmd+left arrow (Tab to left)
			} else if(key==37 && (evt.ctrlKey||ICEcoder.cmdKey) && area!="content") {
				ICEcoder.previousTab();
	        		return false;

			// CTRL/Cmd+up arrow (Move line up)
			} else if(key==38 && (evt.ctrlKey||ICEcoder.cmdKey) && area=="content") {
				ICEcoder.moveLines('up');
	        		return false;

			// CTRL/Cmd+down arrow (Move line down)
			} else if(key==40 && (evt.ctrlKey||ICEcoder.cmdKey) && area=="content") {
				ICEcoder.moveLines('down');
	        		return false;

			// CTRL/Cmd+numeric plus (New tab)
			} else if((key==107 || key==187) && (evt.ctrlKey||ICEcoder.cmdKey)) {
				area=="content"
				? ICEcoder.duplicateLines()
				: ICEcoder.newTab();
	        		return false;

			// CTRL/Cmd+numeric minus (Close tab)
			} else if((key==109 || key==189) && (evt.ctrlKey||ICEcoder.cmdKey)) {
				area=="content"
				? ICEcoder.removeLines()
				: ICEcoder.closeTab(ICEcoder.selectedTab);
	        		return false;

			// CTRL/Cmd+S (Save), CTRL/Cmd+Shift+S (Save As)
			} else if(key==83 && (evt.ctrlKey||ICEcoder.cmdKey)) {
				if(evt.shiftKey) {
					ICEcoder.saveFile('saveAs');
				} else {
					ICEcoder.saveFile();
				}
	        		return false;

			// CTRL/Cmd+Enter (Open Webpage)
			} else if(key==13 && (evt.ctrlKey||ICEcoder.cmdKey) && ICEcoder.openFiles[ICEcoder.selectedTab-1] != "/[NEW]") {
				ICEcoder.resetKeys(evt);
				window.open(ICEcoder.openFiles[ICEcoder.selectedTab-1]);
	        		return false;

			// Enter (Expand dir/open file)
			} else if(key==13 && area=="files") {
				if(!evt.ctrlKey && !ICEcoder.cmdKey) {
					if (ICEcoder.selectedFiles.length == 0) {
						ICEcoder.overFileFolder('folder', '|');
						ICEcoder.selectFileFolder('init');
					}
					ICEcoder.fmAction(evt,'enter');
				}
				return false;

			// Up/down/left/right arrows (Traverse files)
			} else if((key==38||key==40||key==37||key==39) && area=="files") {
				if(!evt.ctrlKey && !ICEcoder.cmdKey) {
					if (ICEcoder.selectedFiles.length == 0) {
						ICEcoder.overFileFolder('folder', '|');
						ICEcoder.selectFileFolder('init');
					}
					ICEcoder.fmAction(evt,
						key==38 ?	'up' :
						key==40 ?	'down' :
						key==37 ?	'left' :
								'right');
				}
	        		return false;

			// CTRL/Cmd+O (Open Prompt)
			} else if(key==79 && (evt.ctrlKey||ICEcoder.cmdKey)) {
				ICEcoder.openPrompt();
	        		return false;

			// CTRL/Cmd+Space (Add snippet)
			} else if(key==32 && (evt.ctrlKey||ICEcoder.cmdKey) && area=="content") {
				ICEcoder.addSnippet();
	        		return false;

			// CTRL/Cmd+J (Jump to definition/back again)
			} else if(key==74 && (evt.ctrlKey||ICEcoder.cmdKey) && area=="content") {
				ICEcoder.jumpToDefinition();
	        		return false;

			// CTRL + Tab (lock/unlock file manager)
			} else if(key==223 && (evt.ctrlKey||ICEcoder.cmdKey)) {
				ICEcoder.lockUnlockNav();
				ICEcoder.changeFilesW(ICEcoder.lockedNav ? 'expand' : 'contract');
				return false;

			// CTRL + . (Fold/unfold current line)
			} else if(key==190 && (evt.ctrlKey||ICEcoder.cmdKey)) {
				cM = ICEcoder.getcMInstance();
				cMdiff = ICEcoder.getcMdiffInstance();
				thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
				var line = thisCM.getCursor().line;

				return false;

			// ESC in content area (Comment/Uncomment line)
       			} else if(key==27 && area == "content") {
				cM = ICEcoder.getcMInstance();
				cMdiff = ICEcoder.getcMdiffInstance();
				thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;

				if (thisCM.getSelections().length > 1) {
					thisCM.execCommand("singleSelection");
				} else {
					ICEcoder.lineCommentToggle();
				}
	        		return false;

			// ESC not in content area (Cancel all actions)
	       		} else if(key==27 && area != "content") {
				ICEcoder.cancelAllActions();
		        	return false;

			// Any other key
			} else {
	        		return key;
        		}
        	}
	},

	// Reset the state of keys back to the normal state
	resetKeys: function(evt) {
		var key, cM;

		key = evt.keyCode ? evt.keyCode : evt.which ? evt.which : evt.charCode;

		if (key == 112 && ICEcoder.codeZoomedOut) {
			cM = ICEcoder.getcMInstance();
			// For every line in the current editor, remove code-zoomed-out class if not a function/class declaration line
			for (var i=0; i<cM.lineCount(); i++) {
				var nonDeclareLine = true;
				for (var j=0; j<ICEcoder.functionClassList.length; j++) {
					if (ICEcoder.functionClassList[j].line == i) {
						nonDeclareLine = false;
					}
				}
				if (nonDeclareLine) {
					cM.removeLineClass(i, "wrap", "code-zoomed-out");
				}
			}
			// Refresh is necessary to re-draw lines
			cM.refresh();

			// Go to line chosen if any
			var cursor = cM.getCursor();
			ICEcoder.goToLine(cursor.line + 1, cursor.ch, false);

			ICEcoder.codeZoomedOut = false;
		}
		ICEcoder.cmdKey = false;
	},

	// Add snippet code completion
	addSnippet: function() {
		var cM, cMdiff, thisCM, lineNo, whiteSpace, content;

		// Get line content after trimming whitespace
		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
		lineNo = thisCM.getCursor().line;
		whiteSpace = thisCM.getLine(lineNo).length - thisCM.getLine(lineNo).replace(/^\s\s*/, '').length;
		content = thisCM.getLine(lineNo).slice(whiteSpace);
		// function snippet
		if (content.slice(0,8)=="function") {
			ICEcoder.doSnippet('function','function VAR() {\nINDENT\tCURSOR\nINDENT}');
		// if snippet
		} else if (content.slice(0,2)=="if") {
			ICEcoder.doSnippet('if','if (CURSOR) {\nINDENT\t\nINDENT}');
		// for snippet
		} else if (content.slice(0,3)=="for") {
			ICEcoder.doSnippet('for','for (var i=0; i<CURSOR; i++) {\nINDENT\t\nINDENT}');
		}
	},

	// Action a snippet
	doSnippet: function(tgtString,replaceString) {
		var cM, cMdiff, thisCM, lineNo, lineContents, remainder, strPos, replacedLine, whiteSpace, curPos, sPos, lineNoCount;

		// Get line contents
		cM = ICEcoder.getcMInstance();
		cMdiff = ICEcoder.getcMdiffInstance();
		thisCM = ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
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
			ICEcoder.focus(ICEcoder.editorFocusInstance.indexOf('diff') > -1 ? true : false);
		}
	},

	// Snart snake
	startSnake: function() {
		ICEcoder.snakePlaying = true;
		ICEcoder.showHide('show',get('blackMask'));
		get('mediaContainer').innerHTML = '<span style="font-size: 14px">Let\'s play<br><img src="'+iceLoc+'/images/snake.png" alt="snake"><br><br><br>Use arrow keys to eat your code<br><br>(it returns afterwards of course) :-)</span>';
		setTimeout(function() {
			ICEcoder.showHide('hide',get('blackMask'));
			get('mediaContainer').innerHTML = '';
			ICEcoder.playSnake();
		},2000);
	},

	// Play snake
	playSnake: function() {
		var cM;

		cM = ICEcoder.getcMInstance();
		cM.setOption('readOnly', 'nocursor');
		cM.focus();

		// Get state of editor at present
		ICEcoder.snakePreHistory = cM.getHistory();
		ICEcoder.snakePreContent = cM.getValue();
		ICEcoder.snakePreCursor = cM.getCursor();

		// Pick a random point for snake to come in and set head and 4 body parts off screen
		var randPos = Math.floor(Math.random()*50);
		ICEcoder.snakePos = [
			[randPos,0],
			[randPos,-1],
			[randPos,-2],
			[randPos,-3],
			[randPos,-4]
		];

		// Show game layer, set direction and do 1st frame of snake
		ICEcoder.content.contentWindow.document.getElementById('game').style.display = 'block';
		ICEcoder.snakeDir = "down";
		ICEcoder.doSnake();

		// Every 0.1s, move snake
		ICEcoder.snakeInt = setInterval(function() {
			// Set new head X & Y pos according to direction
			var newHead = [];
			newHead[0] = ICEcoder.snakePos[0][0]+(ICEcoder.snakeDir == "right" ? 1 : ICEcoder.snakeDir == "left" ? -1 : 0);
			newHead[1] = ICEcoder.snakePos[0][1]+(ICEcoder.snakeDir == "down" ? 1 : ICEcoder.snakeDir == "up" ? -1 : 0);
			// Add new head and remove tail
			ICEcoder.snakePos.unshift(newHead);
			ICEcoder.snakePos.pop();
			// Do next frame of snake
			ICEcoder.doSnake();
		},100);
	},

	doSnake: function() {
		var cM, cW, cH, newInnerHTML, lineData, lineContent, spaceReplaceChars, collision, scrollInfo;

		// Get CodeMirror instance, plus char width and height
		cM = ICEcoder.getcMInstance();
		cW = cM.defaultCharWidth();
		cH = cM.defaultTextHeight();

		// Clear content of game layer
		ICEcoder.content.contentWindow.document.getElementById('game').innerHTML = "";
		// Start a new set of contents
		newInnerHTML = "";
		// For every part of snake, draw it's block in position
		for (var i=0; i<ICEcoder.snakePos.length; i++) {
			newInnerHTML += '<div style="position: absolute; diplay: inline-block; width: '+cW+'px; height: '+cH+'px; top: '+((ICEcoder.snakePos[i][1]*cH)+4)+'px; left: '+((ICEcoder.snakePos[i][0]*cW)+60)+'px; background: #fff"></div>';
		}
		// Set new content in game layer
		ICEcoder.content.contentWindow.document.getElementById('game').innerHTML = newInnerHTML;

		// Get line & ch value under snake head then line content
		lineData = cM.coordsChar({top: ((ICEcoder.snakePos[0][1]*cH)+4), left: ((ICEcoder.snakePos[0][0]*cW)+60)});
		lineContent = cM.getLine(lineData.line);

		// If not the last char on the line
		if (ICEcoder.snakePos[0][0]-1 <= lineContent.length-2) {
			spaceReplaceChars = "";
			// If char under snake head is a tab, replace string contains spaces of same width
			if (lineContent.substr(lineData.ch,1) === "\t") {
				for (var i=0; i<cM.getOption('tabSize'); i++) {
					spaceReplaceChars += " ";
				}
			// Else replace string is a single space
			} else {
				spaceReplaceChars = " ";
			}
			// Push a duplicate of tail onto end, to increase snake length by 1 block
			ICEcoder.snakePos.push([ICEcoder.snakePos[ICEcoder.snakePos.length-1][0],ICEcoder.snakePos[ICEcoder.snakePos.length-1][1]]);
			// Replace char under head with nothing if end of line, else with our replacement string
			cM.doc.replaceRange(ICEcoder.snakePos[0][0]-1 == lineContent.length-2 ? "" : spaceReplaceChars,lineData,{line: lineData.line, ch: lineData.ch+1});
			// Remove any trailing space at end
			if (ICEcoder.snakePos[0][0]-1 == lineContent.length-2) {
				cM.doc.replaceRange(cM.getLine(lineData.line).replace(/[ \t]+$/,''),{line: lineData.line, ch: 0},{line: lineData.line, ch: 1000000});
			}
		} else {
			// Reduce snake length if over 5 chars and not on content
			if (ICEcoder.snakePos.length >= 5) {
				ICEcoder.snakePos.pop();
			}
		}
		// Detect if snake head has collided into itself
		collision = false;
		for (var i=1; i<ICEcoder.snakePos.length; i++) {
			if (ICEcoder.snakePos[i][0] == ICEcoder.snakePos[0][0] && ICEcoder.snakePos[i][1] == ICEcoder.snakePos[0][1]) {
				collision = true;
			}
		}
		// Get scroll info to get width and height of editor area shown
		scrollInfo = cM.getScrollInfo();
		if (
			// If snake out of bounds or a collision, game over!
			ICEcoder.snakePos[0][0] < 0 || ICEcoder.snakePos[0][1] < 0 ||
			((ICEcoder.snakePos[0][0]*cW)+60) > scrollInfo.clientWidth || ((ICEcoder.snakePos[0][1]*cH)+4) > scrollInfo.clientHeight ||
			collision
			) {
			// Clear interval and hide game layer
			clearInterval(ICEcoder.snakeInt);
			ICEcoder.content.contentWindow.document.getElementById('game').style.display = 'none';
			// Set content, saved point, saved contents and history back to what they were pre game
			cM.setValue(ICEcoder.snakePreContent);
			ICEcoder.savedPoints[ICEcoder.selectedTab-1] = cM.changeGeneration();
			ICEcoder.savedContents[ICEcoder.selectedTab-1] = ICEcoder.snakePreContent;
			cM.setHistory(ICEcoder.snakePreHistory);
			// Redo changes indicator in title tag and tab highlight save indicator also to what they are now (pre game state)
			ICEcoder.indicateChanges();
			ICEcoder.redoTabHighlight(ICEcoder.selectedTab);
			// Set editor to be editable again
			cM.setOption('readOnly', false);
			// Set cursor back to what it was pre game and focus on editor
			cM.setCursor(ICEcoder.snakePreCursor);
			cM.focus();
			// State we are no longer playing snake
			ICEcoder.snakePlaying = false;
		}

	}
};
