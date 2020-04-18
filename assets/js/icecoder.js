// Get any elem by ID
var get = function(elem) {
    return document.getElementById(elem);
};

var iceLoc = window.location.pathname;

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
        var aliasArray = ["header","files", "fileOptions", "optionsFile", "optionsEdit", "optionsSource", "optionsHelp", "filesFrame", "editor", "tabsBar", "findBar", "terminal", "output", "database", "git", "content", "tools", "footer", "nestValid", "versionsDisplay", "splitPaneControls", "splitPaneNamesMain", "splitPaneNamesDiff", "charDisplay", "byteDisplay"];

        // Create our ID aliases
        for (var i=0;i<aliasArray.length;i++) {
            this[aliasArray[i]] = get(aliasArray[i]);
        }
    },

    // On load, set the layout and check any nesting is valid
    init: function() {
        // Contract the file manager if the user has set to have it hidden
        if (!this.lockedNav) {
            this.filesW = this.minFilesW;
        }

        // Set layout
        this.setLayout();

        this.overFileFolder('folder', '|');
        this.selectFileFolder('init');
        this.filesFrame.contentWindow.focus();

        // Hide the loading screen & auto open last files?
        this.showHide('hide',get('loadingMask'));
        this.autoOpenInt = setInterval(function(ic) {
            if (ic.fmReady) {
                if (ic.openLastFiles) {ic.autoOpenFiles();};
                clearInterval(ic.autoOpenInt);
            }
        }, 4, this);

        // Update the nesting indicator every 30ms
        setInterval(this.updateNestingIndicator,30);

        // Start bug checking
        this.startBugChecking();

        // Set the time since last user interaction
        this.autoLogoutTimer = 0;
        // Start our interval timer, runs every second
        this.oneSecondInt = setInterval(function(ic) {
            ic.autoLogoutTimer++;
            var unsavedFiles = false;
            // Check if we have any unsaved files
            for(var i=1;i<=ic.savedPoints.length;i++) {
                if (ic.savedPoints[i-1]!=ic.getcMInstance(i).changeGeneration()) {
                    unsavedFiles = true;
                }
            }
            // Show an auto-logout warning 60 secs before a logout
            if(!unsavedFiles && ic.autoLogoutMins > 1 && ic.autoLogoutTimer == (ic.autoLogoutMins*60)-60) {
                ic.autoLogoutWarningScreen();
            }
            if (get('autoLogoutIFrame') && get('autoLogoutIFrame').contentWindow.document.getElementById('timeRemaning')) {
                get('autoLogoutIFrame').contentWindow.document.getElementById('timeRemaning').innerHTML =
                    ic.autoLogoutTimer > 0
                        ? (ic.autoLogoutMins*60) - ic.autoLogoutTimer
                        : 0;
            }
            // If there aren't any unsaved files, we have a timeout period > 0 and the time is up, we can logout
            if(!unsavedFiles && ic.autoLogoutMins > 0 && ic.autoLogoutTimer >= ic.autoLogoutMins*60) {
                ic.logout('autoLogout');
            }
            // Increase number of seconds ICEcoder has been open for by 1
            ic.openSeconds++;
            // Every 5 mins, ping our file to keep the session alive
            if (ic.openSeconds % 300 == 0) {
                ic.filesFrame.contentWindow.frames['pingActive'].location.href = iceLoc+"/lib/session-active-ping.php";
            }
            // Every 3 seconds, re-index if we're not already busy
            if (!ic.indexing && !ic.loadingFile && ic.serverQueueItems.length === 0 && ic.openSeconds % 3 == 0) {
                ic.indexing = true;
                // Get new data
                var timestampExtra = ic.indexData
                    ? "?timestamp="+ic.indexData.timestamps.indexed+"&csrf="+ic.csrf
                    : "";
                fetch(iceLoc+'/lib/indexer.php'+timestampExtra)
                    .then(function(response) {
                        // Convert to JSON
                        return response.json();
                    }).then(function(data) {
                    if (data.timestamps.changed) {
                        ic.indexData = data;
                        // If we have git diff data
                        if (data.gitDiff) {
                            ic.updateGitDiffPane();
                        }
                        // If we have git content data
                        if (data.gitContent) {
                            ic.highlightGitDiffs();
                        }
                    }
                    ic.indexing = false;
                });
            }
        },1000,this);

        // ICEcoder is ready to start using
        this.ready = true;
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
        this.splitPaneNamesMain.style.left = (parseInt((winW-this.filesW)*0.25,10)-50+this.filesW) + "px";
        this.splitPaneNamesDiff.style.left = (parseInt((winW-this.filesW)*0.75,10)-50+this.filesW) + "px";
        this.setTabWidths();

        // If we need to set the editor sizes
        if (!dontSetEditor) {
            this.editor.style.width = this.content.style.width = (winW-this.filesW) + "px";
            this.terminal.style.width = (winW-this.filesW) + "px";
            this.output.style.width = (winW-this.filesW-31) + "px";
            this.database.style.width = (winW-this.filesW) + "px";
            this.git.style.width = (winW-this.filesW-31) + "px";
            this.content.style.height = (winH-headerH-tabsBarH-findBarH-26) + "px";
            this.terminal.style.height = winH + "px";
            this.output.style.height = winH + "px";
            this.database.style.height = winH + "px";
            this.git.style.height = winH + "px";
            this.terminal.style.top = winH + "px";
            this.output.style.top = winH + "px";
            this.database.style.top = winH + "px";
            this.git.style.top = winH + "px";
            if (this.showingTool !== false) {
                get(this.showingTool).style.top = 0;
            }

            // Resize the CodeMirror instances to match the window size
            setTimeout(function(ic){
                for (var i=0;i<ic.openFiles.length;i++) {
                    // Done the long way here as we need to call them in specific order to stop showing background and so avoiding a flicker effect
                    if (!ic.splitPane) {
                        ic.content.contentWindow['cM'+ic.cMInstances[i]].setSize(ic.splitPaneLeftPerc+"%",ic.content.style.height);
                    }
                    ic.content.contentWindow['cM'+ic.cMInstances[i]+'diff'].setSize((100-ic.splitPaneLeftPerc)+"%",ic.content.style.height);
                    ic.content.contentWindow['cM'+ic.cMInstances[i]+'diff'].getWrapperElement().style.left = ic.splitPaneLeftPerc+"%";
                    if (ic.splitPane) {
                        ic.content.contentWindow['cM'+ic.cMInstances[i]].setSize(ic.splitPaneLeftPerc+"%",ic.content.style.height);
                    }
                }
                // Place resultsBar ontop scrollbar
                ic.content.contentWindow.document.getElementById('resultsBar').style.right = !ic.splitPane
                    ? 0
                    : parseInt(parseInt(ic.content.style.width,10)/2,10)+"px";
            },4,this);
        }
    },

    // Set the layout as split pane or not
    setSplitPane: function(onOff) {
        var cM, cMdiff;

        this.splitPane = onOff == "on" ? true : false;
        get('splitPaneControlsOff').style.opacity = this.splitPane ? 0.2 : 0.5;
        get('splitPaneControlsOn').style.opacity = this.splitPane ? 0.5 : 0.2;
        get('splitPaneNamesMain').style.opacity = get('splitPaneNamesDiff').style.opacity = this.splitPane ? 1 : 0;
        this.setLayout();

        // Also clear marks (if going to a single pane) or redo the marks (if split pane)
        if (this.splitPane) {
            this.updateDiffs();
            // Also set the scroll position to match
            cM = this.getcMInstance();
            this.cMonScroll(cM,'cM'+this.cMInstances[this.selectedTab-1]);
        } else {
            cM = this.getcMInstance();
            cMdiff = this.getcMdiffInstance();

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
        if ("undefined" != typeof this.animSplitPaneInt) {
            clearInterval(this.animSplitPaneInt);
        }
        // Now set the interval to animate it in/out
        this.animSplitPaneInt = setInterval(function(ic) {
            // Animate split pane in
            if (ic.splitPane && ic.splitPaneLeftPerc > 50.1) {
                ic.splitPaneLeftPerc = ((ic.splitPaneLeftPerc-50)/1.8)+50;
                // Animate split pane out
            } else if (!ic.splitPane && ic.splitPaneLeftPerc < 99.9) {
                ic.splitPaneLeftPerc = (50-((100-ic.splitPaneLeftPerc)/1.8))+50;
                // Finish animating split pane in/out
            } else {
                ic.splitPaneLeftPerc = ic.splitPane ? 50 : 100;
                clearInterval(ic.animSplitPaneInt);
            }
            ic.setLayout();
        },4,this);
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
            get(tool).style.top = this.showingTool === tool ? winH + "px" : 0;

            // Carry out any extras...
            if (tool === "terminal") {
                // Focus on command prompt
                setTimeout(function(ic){
                    ic.terminal.contentWindow.document.getElementById('command').focus();
                },0,this);
            }

            // Note which tool we're showing
            this.showingTool = this.showingTool !== tool ? tool : false;
        }
    },

    // Set the width of the file manager on demand
    changeFilesW: function(expandContract) {
        if (!this.lockedNav||this.filesW==this.minFilesW) {
            if ("undefined" != typeof this.changeFilesInt) {clearInterval(this.changeFilesInt)};
            this.changeFilesInt = setInterval(function(ic) {ic.changeFilesWStep(expandContract)},10,this);
        }
    },

    // Expand/contract the file manager in half-steps
    changeFilesWStep: function (expandContract) {
        if (expandContract=="expand") {
            this.filesW < this.maxFilesW-1 ? this.filesW += Math.ceil((this.maxFilesW-this.filesW)/2) : this.filesW = this.maxFilesW;
        } else {
            this.filesW > this.minFilesW+1 ? this.filesW -= Math.ceil((this.filesW-this.minFilesW)/2) : this.filesW = this.minFilesW;
        }
        if ((expandContract=="expand" && this.filesW == this.maxFilesW)||(expandContract=="contract" && this.filesW == this.minFilesW)) {
            clearInterval(this.changeFilesInt);
        }
        // Redo the layout to match
        this.setLayout();
    },

    // Can click-drag file manager width?
    canResizeFilesW: function() {
        // If we have the cursor set we must be able!
        if (this.ready && document.body.style.cursor == "w-resize") {
            // If our mouse is down (and went down on the CM instance's gutter) and we're within a 250-400px range
            if (this.mouseDown && this.mouseDownInCM == "gutter") {
                this.filesW = this.maxFilesW = this.mouseX >=250 && this.mouseX <= 400
                    ? this.mouseX : this.mouseX <250 ? 250 : 400;
                // Set various widths based on the new width
                this.files.style.width = this.filesFrame.style.width = this.filesW + "px";
                this.setLayout();
                this.draggingFilesW = true;
            }
        } else {
            this.draggingFilesW = false;
        }
    },

    // Lock & unlock the file manager navigation on demand
    lockUnlockNav: function() {
        var lockIcon;

        lockIcon = this.filesFrame.contentWindow.document.getElementById('fmLock');
        this.lockedNav = !this.lockedNav;
        lockIcon.style.backgroundPosition = this.lockedNav ? "0 0" : "-16px 0";
    },

    // Show/hide the plugins on demand
    showHidePlugins: function(showHide) {
        get('plugins').style.width = showHide=="show" ? '55px' : '3px';
        get('plugins').style.background = showHide=="show" ? '#333' : 'transparent';
        if (showHide=="show") {
            this.changeFilesW('expand');
        }
    },

// ==============
// EDITOR
// ==============

    // On focus
    cMonFocus: function(thisCM,cMinstance) {
        this.getCaretPosition();
        this.updateCharDisplay();
        this.updateByteDisplay();
        this.editorFocusInstance = cMinstance;
        this.getCaretPosition();
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
        doFind = setTimeout(function(ic) {
            ic.findReplace(get('find').value,true,false);
        },500,this);
        this.getCaretPosition();
        this.updateCharDisplay();
        this.updateByteDisplay();
    },

    // On cursor activity
    cMonCursorActivity: function(thisCM,cMinstance) {
        var thisCMPrevLine;

        this.getCaretPosition();
        this.updateCharDisplay();
        this.updateByteDisplay();
        thisCM.removeLineClass(this['cMActiveLine'+cMinstance], "background");
        if(thisCM.getCursor('start').line == thisCM.getCursor().line) {
            this['cMActiveLine'+cMinstance] = thisCM.addLineClass(thisCM.getCursor().line, "background","cm-s-activeLine");
        }
        if (this.caretLocType=="CSS") {
            this.cssColorPreview();
        }

        thisCMPrevLine = this.editorFocusInstance.indexOf('diff') > -1 ? this.prevLineDiff : this.prevLine;
        if (thisCMPrevLine != thisCM.getCursor().line &&
            thisCM.getLine(thisCMPrevLine) &&
            thisCM.getLine(thisCMPrevLine).length > 0 &&
            thisCM.getLine(thisCMPrevLine).replace(/\s/g, '').length == 0) {
            thisCM.replaceRange("",{line: thisCMPrevLine, ch: 0},{line: thisCMPrevLine, ch: 1000000});
        }
        // Set the cursor to text height, not line height
        setTimeout(function(ic) {
            var paneMatch;

            // Loop through styles to check if we have to adjust cursor height
            for (var i=0; i<ic.renderLineStyle.length; i++) {

                // We have no matching pane to start with
                paneMatch = false;

                // Is the pane we need to set the cursor on this pane?
                if (
                    (ic.renderLineStyle[i][0] != "diff" && cMinstance.indexOf("diff") == -1) ||
                    (ic.renderLineStyle[i][0] == "diff" && cMinstance.indexOf("diff") > -1)
                )
                {paneMatch = true;}

                // If the pane matches & also the line we're on is the line we have a style set for, set that cursor height
                if (paneMatch && thisCM.getCursor().line+1 == ic.renderLineStyle[i][1]) {
                    thisCM.setOption("cursorHeight",thisCM.defaultTextHeight() / thisCM.lineInfo(thisCM.getCursor().line).handle.height);
                } else {
                    thisCM.setOption("cursorHeight",1);
                }

            }
        },0,this);
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
                        if (this.oppTagReplaceData.indexOf(thisData) == -1) {
                            this.oppTagReplaceData.push(thisData);
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
        if (!this.loadingFile) {
            this.redoTabHighlight(this.selectedTab);
        }

        // Detect if we have a scrollbar & set layout again
        setTimeout(function(ic) {
            ic.scrollBarVisible = thisCM.getScrollInfo().height > thisCM.getScrollInfo().clientHeight;
            ic.setLayout();
        },0,this);

        // If we're replacing opposite tag strings, do that
        if ("undefined" != typeof this.oppTagReplaceData[0]) {
            // For each one of them, grab our data to work with
            for (var i=0; i<this.oppTagReplaceData.length; i++) {
                // Extract data from that string
                rData = this.oppTagReplaceData[i].split(";");
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
                        this.autocompleteSkip = true;
                    }
                }

            }

        }
        // Reset our array for next time
        this.oppTagReplaceData = [];

        this.getCaretPosition();
        this.updateCharDisplay();
        this.updateByteDisplay();
        this.updateNestingIndicator();
        if (this.findMode) {
            this.results.splice(this.findResult,1);
            get('results').innerHTML = this.results.length + " " + t['results'];
            this.findMode = false;
        }
        // Update the list of functions and classes
        this.updateFunctionClassList();
        filepath = this.openFiles[this.selectedTab-1];
        if (filepath) {
            filename = filepath.substr(filepath.lastIndexOf("/")+1);
            fileExt = filename.substr(filename.lastIndexOf(".")+1);
        }
        // Update diffs if we have a split pane
        if (this.splitPane) {
            // Need 0ms tickover so we handle char change first
            setTimeout(function(ic){ic.updateDiffs();},0,this);
        }

        // Highlight Git diff colors in gutter
        if (this.indexData) {
            this.highlightGitDiffs();
        }

        // Update HTML edited files live
        if (filepath && this.previewWindow.location && filepath != "/[NEW]") {
            this.updatePreviewWindow(thisCM,filepath,filename,fileExt);
        }
        // Update the title tag to indicate any changes
        this.indicateChanges();
    },

    cMonUpdate: function(thisCM,cMinstance) {
    },

    // On scroll
    cMonScroll: function(thisCM,cMinstance) {
        var cM, cMdiff, otherCM;

        this.mouseDown=false;
        this.mouseDownInCM=false;

        if (this.splitPane) {
            // Get both main & diff instance and work out the instance we're not scrolling
            cM = this.getcMInstance();
            cMdiff = this.getcMdiffInstance();
            otherCM = cMinstance.indexOf('diff') > -1 ? cM : cMdiff;

            if (cM) {
                // Scroll other pane x & y to match this one we're scrolling, after a 0ms tickover to avoid judder
                setTimeout(function(){otherCM.scrollTo(thisCM.getScrollInfo().left, thisCM.getScrollInfo().top);},0);
            }
        }
    },

    // On input read
    cMonInputRead: function(thisCM,cMinstance) {
        if (this.autoComplete == "keypress" && this.codeAssist) {
            // Debounce timeout wrapper left here for now, but can be removed in future if no negative effects seen
            // clearTimeout(this.debounce);
            if (!thisCM.state.completionActive) {
                // this.debounce = setTimeout(function() {
                if (!this.autocompleteSkip) {
                    this.autocomplete();
                } else {
                    this.autocompleteSkip = false;
                }
                // },0);
            }
        }
    },

    // On gutter click
    cMonGutterClick: function(thisCM,line,gutter,evt,cMinstance) {
        this.mouseDownInCM = "gutter";
    },

    // On mouse down
    cMonMouseDown: function(thisCM,cMinstance,evt) {
        this.mouseDownInCM = "editor";
    },

    // On context menu
    cMonContextMenu: function(thisCM,cMinstance,evt) {
        // Set cursor
        var currCoords = thisCM.coordsChar({left: evt.pageX, top: evt.pageY});
        thisCM.setCursor(currCoords);

        // If CTRL key down
        if (evt.ctrlKey) {
            setTimeout(function(ic) {
                // Get cM and word under mouse pointer
                var cM = thisCM;
                var word = (cM.getRange(cM.findWordAt(cM.getCursor()).anchor, cM.findWordAt(cM.getCursor()).head));

                // Get result and number of results for word in functions and classes from index JSON object list
                var result = null;
                var numResults = 0;
                var filePath = ic.openFiles[ic.selectedTab-1];
                var filePathExt = filePath.substr(filePath.lastIndexOf(".")+1);
                for(i in ic.indexData.functions[filePathExt]) {
                    if (i === word) {
                        result = ic.indexData.functions[filePathExt][i];
                        numResults++;
                    }
                };
                for(i in ic.indexData.classes[filePathExt]) {
                    if (i === word) {
                        result = ic.indexData.classes[filePathExt][i];
                        numResults++;
                    }
                };

                // If we have a single result and the cursor isn't already on the definition of it we can jump to where it's defined
                if (numResults === 1 && [null,"def"].indexOf(cM.getTokenTypeAt(cM.getCursor())) === -1) {
                    ic.openFile(result.filePath.replace(docRoot,""));
                    ic.goFindAfterOpenInt = setInterval(function(result){
                        if (ic.openFiles[ic.selectedTab-1] == result.filePath.replace(docRoot,"") && !ic.loadingFile) {
                            cM = ic.getcMInstance();
                            setTimeout(function(result) {
                                ic.goToLine(result.range.from.line+1);
                                cM.setSelection({line: result.range.from.line, ch: result.range.from.ch}, {line: result.range.to.line, ch: result.range.to.ch});
                            },20,result);
                            clearInterval(ic.goFindAfterOpenInt);
                        }
                    },20,result);
                }

                ic.mouseDownInCM = "editor";
            },0,this);
        }
    },

    // On drag over
    cMonDragOver: function(thisCM,evt,cMinstance) {
        this.setDragCursor(evt,'editor');
    },

    // On render line
    cMonRenderLine: function(thisCM,cMinstance,line,element) {
        var paneMatch;

        // Loop through styles to use when rendering lines
        for (var i=0; i<this.renderLineStyle.length; i++) {

            // We have no matching pane to start with
            paneMatch = false;

            // Is the pane we need to style this pane?
            if (
                (this.renderLineStyle[i][0] != "diff" && cMinstance.indexOf("diff") == -1) ||
                (this.renderLineStyle[i][0] == "diff" && cMinstance.indexOf("diff") > -1)
            )
            {paneMatch = true;}

            // If the pane matches & also the line we're rendering is the line we have a style set for, set that style
            if (paneMatch && thisCM.lineInfo(line).line+1 == this.renderLineStyle[i][1]) {
                element.style[this.renderLineStyle[i][2]] = this.renderLineStyle[i][3];
            }

        }
    },

    // Show function args tooltip
    functionArgsTooltip: function(e, area) {
        if (this.indexData) {
            // If we have no files open, return early
            if (this.openFiles.length === 0) {
                get('tooltip').style.display = "none";
                return true;
            }

            var i;
            // Get cM instance, and the word under mouse pointer
            var cM = this.getcMInstance();
            var coordsChar = cM.coordsChar({left: this.mouseX-this.maxFilesW, top: this.mouseY-72});
            var word = (cM.getRange(cM.findWordAt(coordsChar).anchor, cM.findWordAt(coordsChar).head));

            // If it's not a word, return early
            if (word === "") {
                get('tooltip').style.display = "none";
                return true;
            }

            // Get result and number of results for word in functions from index JSON object list
            var result = null;
            var numResults = 0;
            var filePath = this.openFiles[this.selectedTab-1];
            var filePathExt = filePath.substr(filePath.lastIndexOf(".")+1);
            for(i in this.indexData.functions[filePathExt]) {
                if (i === word) {
                    result = this.indexData.functions[filePathExt][i];
                    numResults++;
                }
            };

            // If we have a single result and the mouse pointer is not over the definition of it (that would be pointless), show tooltip
            if (numResults === 1 && [null,"def"].indexOf(cM.getTokenTypeAt(coordsChar)) === -1) {
                get('tooltip').style.display = "block";
                get('tooltip').style.left = (this.mouseX-this.maxFilesW+10) + "px";
                get('tooltip').style.top = (this.mouseY-30) + "px";
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
        this.renderLineStyle = [];
        this.renderPaneShiftAmount = 0;

        cM = this.getcMInstance();
        cMdiff = this.getcMdiffInstance();

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
                        amt = ((opcodes[i][4] - opcodes[i][2] + 1 + this.renderPaneShiftAmount) * cM.defaultTextHeight());
                        // Add on the extra heights for any wrapped lines
                        for (var j=opcodes[i][4]-1; j<=opcodes[i][2]-1; j++) {
                            if (cMdiff.getLineHandle(j).height > cM.defaultTextHeight()) {
                                amt += cMdiff.getLineHandle(j).height - cM.defaultTextHeight();
                            }
                        }
                        // If we have an height greater than the default text height, add a new style
                        if (amt > cM.defaultTextHeight()) {
                            this.renderLineStyle.push(["main", opcodes[i][2], "height", amt + "px"]);
                        }
                        // Mark text in 2 colours, for each line
                        for (var j=0; j<(opcodes[i][2]) - (opcodes[i][1]); j++)  {
                            sDiffs = (this.findStringDiffs(cM.getLine(opcodes[i][1]+j),cMdiff.getLine(opcodes[i][3]+j)));
                            cM.markText({line: opcodes[i][1]+j, ch: 0}, {line: opcodes[i][3]+j + this.renderPaneShiftAmount, ch: sDiffs[0]}, {className: "diffGreyLighter"});
                            cM.markText({line: opcodes[i][1]+j, ch: sDiffs[0]}, {line: opcodes[i][3]+j + this.renderPaneShiftAmount, ch: sDiffs[0]+sDiffs[1]}, {className: "diffGrey"});
                            cM.markText({line: opcodes[i][1]+j, ch: sDiffs[0]+sDiffs[1]}, {line: opcodes[i][3]+j + this.renderPaneShiftAmount, ch: 1000000}, {className: "diffGreyLighter"});
                        }
                        // Inserting
                    } else {
                        cM.markText({line: opcodes[i][1], ch: 0}, {line: opcodes[i][2]-1, ch: 1000000}, {className: "diffGreen"});
                    }

                    // If inserting or deleting and the main pane hasn't changed, we need to pad out the line in that pane
                    if (opcodes[i][0] != "replace" && opcodes[i][1] == opcodes[i][2]) {
                        this.renderLineStyle.push(["main", opcodes[i][2], "height", ((opcodes[i][4] - opcodes[i][3] + 1) * cM.defaultTextHeight()) + "px"]);
                        // Mark the range with empty class
                        cM.markText({line: opcodes[i][2]-1, ch: 0}, {line: opcodes[i][2]-1, ch: 1000000}, {className: "diffNone"});
                    }

                    // =========
                    // DIFF PANE
                    // =========

                    // Replacing? Pad out diff pane line to match equivalent last line in main pane
                    if (opcodes[i][0] == "replace") {
                        // Line amount is diff between end of both panes at this point in our loop, plus 1 line and our overall document shift, multiplied by font size
                        amt = ((opcodes[i][2] - opcodes[i][4] + 1 - this.renderPaneShiftAmount) * cM.defaultTextHeight());
                        // Add on the extra heights for any wrapped lines
                        for (var j=opcodes[i][4]-1; j<=opcodes[i][2]-1; j++) {
                            if (cM.getLineHandle(j).height > cM.defaultTextHeight()) {
                                amt += cM.getLineHandle(j).height - cM.defaultTextHeight();
                            }
                        }
                        // If we have an height greater than the default text height, add a new style
                        if (amt > cM.defaultTextHeight()) {
                            this.renderLineStyle.push(["diff", opcodes[i][4], "height", amt + "px"]);
                        }
                        // Mark text in 2 colours, for each line
                        for (var j=0; j<(opcodes[i][4]) - (opcodes[i][3]); j++)  {
                            sDiffs = (this.findStringDiffs(cM.getLine(opcodes[i][1]+j),cMdiff.getLine(opcodes[i][3]+j)));
                            cMdiff.markText({line: opcodes[i][1]+j - this.renderPaneShiftAmount, ch: 0}, {line: opcodes[i][3]+j, ch: sDiffs[0]}, {className: "diffGreyLighter"});
                            cMdiff.markText({line: opcodes[i][1]+j - this.renderPaneShiftAmount, ch: sDiffs[0]}, {line: opcodes[i][3]+j, ch: sDiffs[0]+sDiffs[2]}, {className: "diffGrey"});
                            cMdiff.markText({line: opcodes[i][1]+j - this.renderPaneShiftAmount, ch: sDiffs[0]+sDiffs[2]}, {line: opcodes[i][3]+j, ch: 1000000}, {className: "diffGreyLighter"});
                        }
                        // Deleting
                    } else {
                        cMdiff.markText({line: opcodes[i][3], ch: 0}, {line: opcodes[i][4]-1, ch: 1000000}, {className: "diffRed"});
                    }

                    // If inserting or deleting and the diff pane hasn't changed, we need to pad out the line in that pane
                    if (opcodes[i][0] != "replace" && opcodes[i][3] == opcodes[i][4]) {
                        this.renderLineStyle.push(["diff", opcodes[i][4], "height", ((opcodes[i][2] - opcodes[i][1] + 1) * cM.defaultTextHeight()) + "px"]);
                        // Mark the range with empty class
                        cMdiff.markText({line: opcodes[i][4]-1, ch: 0}, {line: opcodes[i][4]-1, ch: 1000000}, {className: "diffNone"});
                    }

                    // Finally, set the last amount shifted for this change
                    this.renderPaneShiftAmount = (opcodes[i][2] - opcodes[i][4]);
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
        if (this.indexData && this.indexData.gitContent) {
            highlightGitDiffTimeout = setTimeout(function(ic) {
                if (ic.indexData.gitContent[docRoot+ic.openFiles[ic.selectedTab-1]]) {
                    // Get the CodeMirror instance and clear the gutter for it
                    cM = ic.getcMInstance();
                    cM.clearGutter("CodeMirror-linenumbers");
                    // Get the baseText and gitText values from the two sources, and split them into lines
                    var mainText = cM ? difflib.stringAsLines(cM.getValue()) : "";
                    var gitText = difflib.stringAsLines(ic.indexData.gitContent[docRoot+ic.openFiles[ic.selectedTab-1]].lastHashContent ?? "");

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
            },this.loadingFile ? 100 : 0, this);
        }
    },

    // Update Git diff pane (the diffs between saved content and git commits)
    updateGitDiffPane: function() {
        var gitDiffList = "";
        for (var i=0; i<this.indexData.gitDiff.paths.length; i++) {
            gitDiffList +=
                '<div class="link" onclick="this.toolShowHideToggle(\'git\'); this.openFile(\'/' +
                this.indexData.gitDiff.paths[i] +
                "')\">" +
                this.indexData.gitDiff.paths[i] +
                "</div>" +
                "\n";
            get("git").innerHTML = gitDiffList + "<br><br>";
        }
    },

    // Update preview window content
    updatePreviewWindow: function(thisCM,filepath,filename,fileExt) {
        if (this.previewWindow.location.pathname==filepath) {
            if (["htm","html","txt"].indexOf(fileExt) > -1) {
                this.previewWindow.document.documentElement.innerHTML = thisCM.getValue();
            } else if (["md"].indexOf(fileExt) > -1) {
                this.previewWindow.document.documentElement.innerHTML = mmd(thisCM.getValue());
            }
        } else if (["css"].indexOf(fileExt) > -1) {
            if (this.previewWindow.document.documentElement.innerHTML.indexOf(filename) > -1) {
                var css = thisCM.getValue();
                var style = document.createElement('style');
                style.type = 'text/css';
                style.id = "ICEcoder"+filepath.replace(/\//g,"_");
                if (style.styleSheet){
                    style.styleSheet.cssText = css;
                } else {
                    style.appendChild(document.createTextNode(css));
                }
                if (this.previewWindow.document.getElementById(style.id)) {
                    this.previewWindow.document.documentElement.removeChild(this.previewWindow.document.getElementById(style.id));
                }
                this.previewWindow.document.documentElement.appendChild(style);
            }
        }
        // Do the pesticide plugin if it exists
        try {this.doPesticide();} catch(err) {};
        // Do the stats.js plugin if it exists
        try {this.doStatsJS('update');} catch(err) {};
        // Do the responsive plugin if it exists
        try {this.doResponsive();} catch(err) {};
    },

    // Clean up our loaded code
    contentCleanUp: function() {
        var cM, cMdiff, thisCM, content;

        // Replace any temp /textarea value
        cM = this.getcMInstance();
        cMdiff = this.getcMdiffInstance();
        thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
        content = thisCM.getValue();
        content = content.replace(/<ICEcoder:\/:textarea>/g,'<\/textarea>');

        // Then set the content in the editor & clear the history
        thisCM.setValue(content);
        thisCM.clearHistory();
        this.savedPoints[this.selectedTab-1] = thisCM.changeGeneration();
        this.savedContents[this.selectedTab-1] = thisCM.getValue();
    },

    // Undo last change
    undo: function() {
        var cM, cMdiff, thisCM;

        cM = this.getcMInstance();
        cMdiff = this.getcMdiffInstance();
        thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
        thisCM.undo();
    },

    // Redo change
    redo: function() {
        var cM, cMdiff, thisCM;

        cM = this.getcMInstance();
        cMdiff = this.getcMdiffInstance();
        thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
        thisCM.redo();
    },

    // Indent more/less
    indent: function(moreLess) {
        var cM, cMdiff, thisCM;

        cM = this.getcMInstance();
        cMdiff = this.getcMdiffInstance();
        thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
        if (moreLess=="more") {
            this.content.contentWindow.CodeMirror.commands.indentMore(thisCM);
        } else {
            this.content.contentWindow.CodeMirror.commands.indentLess(thisCM);
        }
    },

    // Move current line up/down
    moveLines: function(dir) {
        var cM, cMdiff, thisCM, lineStart, lineEnd, swapLineNo, swapLine;

        cM = this.getcMInstance();
        cMdiff = this.getcMdiffInstance();
        thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;

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

        cM = this.getcMInstance();
        cMdiff = this.getcMdiffInstance();
        thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
        thisCM.setSelection({line:line,ch:0}, {line:line,ch:thisCM.lineInfo(line).text.length});
    },

    // Focus the editor
    focus: function(diff) {
        var cM, cMdiff, thisCM;

        if (!(/iPhone|iPad|iPod/i.test(navigator.userAgent))) {
            cM = this.getcMInstance();
            cMdiff = this.getcMdiffInstance();
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

        cM = this.getcMInstance();
        cMdiff = this.getcMdiffInstance();
        thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;

        this.scrollingOnLine = thisCM.getCursor().line;

        // Scroll cursor into middle of view
        if ("undefined" != typeof this.scrollInt) {
            clearInterval(this.scrollInt);
        }

        this.scrollInt = setInterval(function(ic) {
            ic.scrollingOnLine = ic.scrollingOnLine+((lineNo-ic.scrollingOnLine)/5);
            thisCM.scrollTo(0,(thisCM.defaultTextHeight()*ic.scrollingOnLine)-(thisCM.getScrollInfo().clientHeight/10));
            if (Math.round(ic.scrollingOnLine) == lineNo) {
                clearInterval(ic.scrollInt);
            }
        },10,this);

        thisCM.setCursor(lineNo, charNo);
        if (!noFocus) {
            this.focus();
            // Also do this after a 0ms tickover incase DOM wasn't ready
            setTimeout(function(ic){ic.focus();},0,this);
        }
        return false;
    },

    // Comment/uncomment line or selected range on keypress
    lineCommentToggle: function() {
        var cM, cMdiff, thisCM, cursorPos, linePos, lineContent, lCLen;

        cM = this.getcMInstance();
        cMdiff = this.getcMdiffInstance();
        thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;

        cursorPos = thisCM.getCursor().ch;
        linePos = thisCM.getCursor().line;
        lineContent = thisCM.getLine(linePos);
        lCLen = lineContent.length;

        this.lineCommentToggleSub(thisCM, cursorPos, linePos, lineContent, lCLen);
    },

    // Wrap our selected text/cursor with tags
    tagWrapper: function(tag) {
        var cM, cMdiff, thisCM, tagStart, tagEnd, startLine, endLine;

        cM = this.getcMInstance();
        cMdiff = this.getcMdiffInstance();
        thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
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

        cM = this.getcMInstance();
        cMdiff = this.getcMdiffInstance();
        thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
        if (!line) {line = thisCM.getCursor().line};
        thisCM.replaceRange(thisCM.getLine(line)+"<br>",{line:line,ch:0},{line:line,ch:1000000});
    },

    // Insert a line before and auto-indent
    insertLineBefore: function(line) {
        var cM, cMdiff, thisCM;

        cM = this.getcMInstance();
        cMdiff = this.getcMdiffInstance();
        thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
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

        cM = this.getcMInstance();
        cMdiff = this.getcMdiffInstance();
        thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
        if (!line) {line = thisCM.getCursor().line};
        thisCM.operation(function() {
            thisCM.replaceRange(thisCM.getLine(line)+"\n",{line:line,ch:0},{line:line,ch:1000000});
            thisCM.execCommand('indentAuto');
        });
    },

    // Duplicate line
    duplicateLines: function(line) {
        var cM, cMdiff, thisCM, ch, lineExtra, userSelStart, userSelEnd, lineBreak;

        cM = this.getcMInstance();
        cMdiff = this.getcMdiffInstance();
        thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
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

        cM = this.getcMInstance();
        cMdiff = this.getcMdiffInstance();
        thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
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

        cM = this.getcMInstance();
        cMdiff = this.getcMdiffInstance();
        thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
        tokenString = thisCM.getTokenAt(thisCM.getCursor()).string;

        if (thisCM.somethingSelected() && this.origCurorPos) {
            thisCM.setCursor(this.origCurorPos);
        } else {
            this.origCurorPos = thisCM.getCursor();
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
                if (this.findReplace(defVars[i],false,false)) {
                    break;
                }
            }
        }
    },

    // Update function & class list {
    updateFunctionClassList: function() {
        var cM, functionClassList;

        cM = this.getcMInstance();
        this.functionClassList = [];

        if(cM) {
            // For each line, establish if there's a function or class item on it
            cM.doc.eachLine(function(handle){ICEcoder.updateFunctionClassListItems(handle)});
        }
    },

    // Update function/class list items
    updateFunctionClassListItems: function(handle) {
        var cM, functionClassText;

        cM = this.getcMInstance();
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
            this.functionClassList.push({
                line: cM.getLineNumber(handle),
                name: functionClassText[0],
                params: "("+(functionClassText[1] ? functionClassText[1].replace(/[,]/g,", ") : ""),
                verified: false
            });
            // After a 0ms tickover, verify the item
            setTimeout(function(ic) {
                // If we're defining a function/class
                if (!handle.styles || (handle.styles && handle.styles.indexOf('def') > -1 && cM.getLineNumber(handle))) {
                    // Find our item in the array and mark it as verified
                    for (var i=0; i< ic.functionClassList.length; i++) {
                        if (ic.functionClassList[i]['line'] == cM.getLineNumber(handle)) {
                            ic.functionClassList[i]['verified'] = true;
                        }
                    };
                }
            },0,this);
        }
    },

    // Autocomplete
    autocomplete: function() {
        var cM, cMdiff, thisCM;

        cM = this.getcMInstance();
        cMdiff = this.getcMdiffInstance();
        thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
        this.content.contentWindow.CodeMirror.commands.autocomplete(thisCM);
    },

    // Paste a URL, locally or absolutely if CTRL/Cmd key down
    pasteURL: function(url) {
        var cM, cMdiff, thisCM;

        cM = this.getcMInstance();
        cMdiff = this.getcMdiffInstance();
        thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
        if(this.draggingWithKey == "CTRL") {
            url = window.location.protocol + "//" + window.location.hostname + url;
        }
        thisCM.replaceSelection(url,"around");
    },

    // Search for selected text online
    searchForSelected: function() {
        var cM, cMdiff, thisCM;

        cM = this.getcMInstance();
        cMdiff = this.getcMdiffInstance();
        thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
        if (this.caretLocType) {
            if (thisCM.getSelection() != "") {
                var searchPrefix = this.caretLocType.toLowerCase()+" ";
                if (this.caretLocType=="Content") {
                    searchPrefix = "";
                }
                window.open("http://www.google.com/#output=search&q="+searchPrefix+thisCM.getSelection());
            } else {
                this.message(t['No text selected...']);
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
            selElem = get('filesFrame').contentWindow.document.getElementById(this.selectedFiles[this.selectedFiles.length-1]+"_perms").parentNode;
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
                this.openCloseDir(selElem,false);				// contract dir
            }
        }
        if (action == "right" || action == "enter") {
            fileFolder == "folder"
                ? this.openCloseDir(selElem,true)				// expand dir
                : this.openFile(selElem.childNodes[1].id.replace(/\|/g,"/"));	// open file
        }
        if (goElem && goElem.childNodes[1]) {
            this.overFileFolder(fileFolder, goElem.childNodes[1].id);		// If we have an elem to go to, select it
            this.selectFileFolder(evt);
        }
    },

    // Open/close dirs on demand
    openCloseDir: function(dir,load) {
        var node, d;

        dir.onclick = function(event) {
            if(!event.ctrlKey && !this.cmdKey) {
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
            this.filesFrame.contentWindow.frames['fileControl'].location.href = iceLoc+"/lib/get-branch.php?location="+dir.childNodes[1].id+"&csrf="+this.csrf;
        } else if(node.tagName == "UL") {
            node.parentNode.removeChild(node);
        }
        return false;
    },

    // Note which files or folders we are over on mouseover/mouseout
    overFileFolder: function(type, link) {
        this.thisFileFolderType=type;
        this.thisFileFolderLink=link;
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
        if (this.thisFileFolderLink=="") {
            if (!ctrlSim && !evt.ctrlKey && !this.cmdKey) {
                this.deselectAllFiles();
            }
        } else if (this.thisFileFolderLink) {
            // Get file URL, with pipes instead of slashes & target DOM elem
            shortURL = this.thisFileFolderLink.replace(/\//g,"|");
            tgtFile = this.filesFrame.contentWindow.document.getElementById(shortURL);

            // If we have the CTRL/Cmd key down
            if (ctrlSim || evt.ctrlKey || this.cmdKey) {
                // Deselect or select file
                if (this.selectedFiles.indexOf(shortURL)>-1) {
                    this.selectDeselectFile('deselect',tgtFile);
                    this.selectedFiles.splice(this.selectedFiles.indexOf(shortURL),1);
                } else {
                    this.selectDeselectFile('select',tgtFile);
                    this.selectedFiles.push(shortURL);
                }
                // Select from last click to this one
            } else if (shiftSim || evt.shiftKey) {
                selecting = false;
                dirList = tgtFile.parentNode.parentNode.parentNode;
                lastFileClicked = this.selectedFiles[this.selectedFiles.length-1];

                // Prefix numbers with up to 20 leading zeros
                // This is so we can have some kind of natural comparison on the regex below
                function prefixer(match, p1, offset, string) {
                    return ('00000000000000000000'+match).substr(-20);
                }

                startFile = shortURL.replace(/\d+/g, prefixer) < lastFileClicked.replace(/\d+/g, prefixer) ? shortURL : lastFileClicked;
                endFile = shortURL.replace(/\d+/g, prefixer) > lastFileClicked.replace(/\d+/g, prefixer) ? shortURL : lastFileClicked;

                if (this.selectedFiles.length > 0 && startFile.substr(0,startFile.lastIndexOf("|")) == endFile.substr(0,endFile.lastIndexOf("|"))) {
                    for (var i=0; i<1000000; i+=2) {
                        if(dirList.childNodes[i].nodeName != "LI") {i++;};
                        thisFileObj = dirList.childNodes[i].childNodes[0].childNodes[1];
                        if (thisFileObj.id == startFile) {
                            selecting = true;
                        }
                        if (selecting==true && this.selectedFiles.indexOf(thisFileObj.id)==-1) {
                            this.selectDeselectFile('select',thisFileObj);
                            this.selectedFiles.push(thisFileObj.id);
                        }
                        if (thisFileObj.id == endFile) {
                            break;
                        }
                    }
                } else {
                    this.selectDeselectFile('select',tgtFile);
                    this.selectedFiles.push(shortURL);
                }
                // We are single clicking
            } else {
                this.deselectAllFiles();

                // Add our URL and highlight the file
                this.selectDeselectFile('select',tgtFile);
                this.selectedFiles.push(shortURL);
            }
        }

        // Adjust the file & replace select dropdown values accordingly
        document.findAndReplace.target[2].innerHTML = !this.selectedFiles[0] ? t['all files'] : t['selected files'];
        document.findAndReplace.target[3].innerHTML = !this.selectedFiles[0] ? t['all filenames'] : t['selected filenames'];

        // Hide the file menu incase it's showing
        this.hideFileMenu();
    },

    // Deselect all files
    deselectAllFiles: function() {
        var tgtFile;

        for (var i=0;i<this.selectedFiles.length;i++) {
            tgtFile = this.filesFrame.contentWindow.document.getElementById(this.selectedFiles[i]);
            this.selectDeselectFile('deselect',tgtFile);
        }
        this.selectedFiles.length = 0;
    },

    // Select or deselect file
    selectDeselectFile: function(action,file) {
        var isOpen;

        if (file) {
            isOpen = this.openFiles.indexOf(file.id.replace(/\|/g,"/")) > -1 ? true : false;

            if (this.openFiles[this.selectedTab-1] == file.id.replace(/\|/g,"/")) {
                file.style.backgroundColor = action=="select"
                    ? this.tabBGselected : this.tabBGcurrent;
            } else {
                file.style.backgroundColor = action=="select"
                    ? this.tabBGselected : file.style.backgroundColor = isOpen
                        ? this.tabBGopen : this.tabBGnormal;
            }
            file.style.color = action=="select" ? this.tabFGselected : this.tabFGnormalFile;
        }
    },

    // Box select files
    boxSelect: function(evt, mouseAction) {
        var fmDragBox, positive;

        fmDragBox = this.filesFrame.contentWindow.document.getElementById('fmDragBox');

        // On mouse down, set start X & Y and reset first and last items in box area select
        if (mouseAction == "down") {
            this.fmDragBoxStartX = this.mouseX;
            this.fmDragBoxStartY = this.mouseY;
            this.fmDragSelectFirst = "";
            this.fmDragSelectLast = "";
        }

        // On mouse drag, state we're dragging, set the box size and position properties and select files
        if(this.mouseDown && !this.mouseDownInCM && mouseAction == "drag") {
            this.fmDraggedBox = true;

            // Handle X-axis properties
            positive = this.mouseX-this.fmDragBoxStartX > 0;
            fmDragBox.style.left = (positive ? this.fmDragBoxStartX : this.mouseX) + "px";
            fmDragBox.style.width = Math.abs(this.mouseX-this.fmDragBoxStartX) + "px";

            // Handle Y-axis properties
            positive = this.mouseY-this.fmDragBoxStartY > 0;
            fmDragBox.style.top = (positive ? this.fmDragBoxStartY-70 : this.mouseY-70) + "px";
            fmDragBox.style.height = Math.abs(this.mouseY-this.fmDragBoxStartY) + "px";

            // Select the files
            if (this.thisFileFolderLink != "") {
                if (this.fmDragSelectFirst == "") {
                    this.fmDragSelectFirst = this.thisFileFolderLink;
                    this.overFileFolder(this.thisFileFolderLink.indexOf('.') > 0 ? 'file' : 'folder', this.fmDragSelectFirst);
                    this.selectFileFolder(evt);
                } else {
                    this.fmDragSelectLast = this.thisFileFolderLink;
                    this.overFileFolder(this.thisFileFolderLink.indexOf('.') > 0 ? 'file' : 'folder', this.fmDragSelectLast);
                    this.selectFileFolder(evt,false,'shiftSim');
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
        this.newTab('alsoSave');
    },

    // Create a new folder
    newFolder: function() {
        var shortURL, newFolder;

        shortURL = this.selectedFiles[this.selectedFiles.length-1].replace(/\|/g,"/");
        newFolder = this.getInput('Enter new folder name at '+shortURL,'');
        if (newFolder) {
            newFolder = (shortURL + "/" + newFolder).replace(/\/\//,"/");
            this.serverQueue("add",iceLoc+"/lib/file-control-xhr.php?action=newFolder&csrf="+this.csrf,encodeURIComponent(newFolder.replace(/\//g,"|")));
            this.serverMessage('<b>'+t['Creating Folder']+'</b><br>'+newFolder);
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
            flSplit = this.returnFileAndLine(fileLink);
            fileLink = flSplit[0];
            line     = flSplit[1];
        }

        if (fileLink) {
            this.thisFileFolderLink=fileLink;
            this.thisFileFolderType="file";
        }
        if (this.thisFileFolderLink != "/[NEW]" && this.isOpen(this.thisFileFolderLink)!==false) {
            this.switchTab(this.isOpen(this.thisFileFolderLink)+1);
            if (line > 1){
                this.goToLine(line);
            }
        } else if (this.thisFileFolderLink!="" && this.thisFileFolderType=="file") {

            // work out a shortened URL for the file
            shortURL = this.thisFileFolderLink.replace(/\|/g,"/");
            // No reason why we can't open a file (so far)
            canOpenFile = true;
            // Limit to 100 files open at a time
            if (this.openFiles.length>=100) {
                this.message(t['Sorry you can...']);
                canOpenFile = false;
            }

            // if we're still OK to open it...
            if (canOpenFile) {
                this.shortURL = shortURL;

                if (shortURL!="/[NEW]") {
                    this.thisFileFolderLink = this.thisFileFolderLink.replace(/\//g,"|");
                    this.serverQueue("add",iceLoc+"/lib/file-control.php?action=load&file="+encodeURIComponent(this.thisFileFolderLink)+"&csrf="+this.csrf+"&lineNumber="+line);
                    this.serverMessage('<b>'+t['Opening File']+'</b><br>'+this.shortURL);
                } else {
                    this.createNewTab('new');
                }
                this.fMIconVis('fMView',1);
            }
        }
    },

    // Open selected files
    openFilesFromList: function(fileList) {
        for (var i=0;i<fileList.length;i++) {
            this.thisFileFolderLink=fileList[i].replace('|','/');
            this.thisFileFolderType='file';
            this.openFile();
        }
    },

    // Show file prompt to open file
    openPrompt: function() {
        var fileLink;

        if(fileLink = this.getInput(t['Enter relative file...'],'')) {
            fileLink.indexOf("://")>-1
                ? this.getRemoteFile(fileLink)
                : this.openFile(fileLink);
        }
    },

    // Get remote file contents
    getRemoteFile: function(remoteFile) {
        var flSplit, line;

        if ("undefined" != typeof remoteFile) {
            flSplit = this.returnFileAndLine(remoteFile);
            remoteFile = flSplit[0];
            line       = flSplit[1];
        }

        this.serverQueue("add",iceLoc+"/lib/file-control-xhr.php?action=getRemoteFile&csrf="+this.csrf+"&lineNumber="+line,encodeURIComponent(remoteFile));
        this.serverMessage('<b>'+t['Getting']+'</b><br>'+remoteFile);
    },

    // Get changes to save (used when simply saving, gets diff changes between current and last known version)
    getChangesToSave: function() {
        var cM, savedText, newText, sm, opcodes;

        cM = this.getcMInstance();

        // Get the last known saved version of file from array
        savedText = this.savedContents[this.selectedTab-1];

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
            changes = this.getChangesToSave();
        }

        saveType = saveAs ? "saveAs" : "save";
        filePath = this.openFiles[this.selectedTab-1].replace(iceRoot,"").replace(/\//g,"|");
        if (filePath=="|[NEW]" && this.selectedFiles.length>0) {
            pathPrefix = this.selectedFiles[0];
            filePath = pathPrefix.lastIndexOf(".") == -1 || pathPrefix.lastIndexOf(".") < pathPrefix.lastIndexOf("|")
                ? pathPrefix+filePath
                : "|[NEW]";
        }
        filePath = filePath.replace("||","|");
        this.serverQueue("add",iceLoc+"/lib/file-control-xhr.php?action=save&fileMDT="+this.openFileMDTs[this.selectedTab-1]+"&fileVersion="+this.openFileVersions[this.selectedTab-1]+"&saveType="+saveType+"&csrf="+this.csrf,encodeURIComponent(filePath),changes);
        this.serverMessage('<b>'+t['Saving']+'</b><br>'+this.openFiles[this.selectedTab-1].replace(iceRoot,""));
    },

    // Prompt a rename dialog
    renameFile: function(oldName,newName) {
        var shortURL, fileName, i;

        if (!oldName) {
            shortURL = this.selectedFiles[this.selectedFiles.length-1].replace(/\|/g,"/");
            oldName = this.selectedFiles[this.selectedFiles.length-1].replace(/\|/g,"/");
        } else {
            shortURL = oldName.replace(/\|/g,"/");
        }
        if (!newName) {
            newName = this.getInput(t['Please enter the...'],shortURL);
        }
        if (newName) {
            i = this.openFiles.indexOf(shortURL.replace(/\|/g,"/"));
            if(i>-1) {
                // rename array item and the tab
                this.openFiles[i] = newName;
                closeTabLink = '<a nohref onClick="ICEcoder.closeTab(parseInt(this.parentNode.id.slice(3),10))"><img src="'+iceLoc+'/assets/images/nav-close.gif" class="closeTab" onMouseOver="prevBG=this.style.backgroundColor;this.style.backgroundColor=\'#333\'; this.overCloseLink=true" onMouseOut="this.style.backgroundColor=prevBG; this.overCloseLink=false"></a>';
                fileName = this.openFiles[i];
                get('tab'+(i+1)).innerHTML = closeTabLink + " " + fileName.slice(fileName.lastIndexOf("/")).replace(/\//,"");
                get('tab'+(i+1)).title = newName;
            }
            this.serverQueue("add",iceLoc+"/lib/file-control-xhr.php?action=rename&oldFileName="+encodeURIComponent(oldName.replace(/\|/g,"/"))+"&csrf="+this.csrf,encodeURIComponent(newName));
            this.serverMessage('<b>'+t['Renaming to']+'</b><br>'+newName);

            this.setPreviousFiles();
        }
    },

    // Move a file from old location to new
    moveFile: function(oldName,newName) {
        var fileName, i;

        if (newName && newName != oldName) {
            i = this.openFiles.indexOf(oldName.replace(/\|/g,"/"));
            if(i>-1) {
                // rename array item and the tab
                this.openFiles[i] = newName;
                closeTabLink = '<a nohref onClick="ICEcoder.closeTab(parseInt(this.parentNode.id.slice(3),10))"><img src="'+iceLoc+'/assets/images/nav-close.gif" class="closeTab" onMouseOver="prevBG=this.style.backgroundColor;this.style.backgroundColor=\'#333\'; this.overCloseLink=true" onMouseOut="this.style.backgroundColor=prevBG; this.overCloseLink=false"></a>';
                fileName = this.openFiles[i];
                get('tab'+(i+1)).innerHTML = closeTabLink + " " + fileName.slice(fileName.lastIndexOf("/")).replace(/\//,"");
                get('tab'+(i+1)).title = newName;
            }
            if (this.ask("Are you sure you want to move file " + oldName + " to " + newName + " ?")){
                this.serverQueue("add",iceLoc+"/lib/file-control-xhr.php?action=move&oldFileName="+encodeURIComponent(oldName.replace(/\//g,"|"))+"&csrf="+this.csrf,encodeURIComponent(newName.replace(/\//g,"|")));
                this.serverMessage('<b>'+t['Moving to']+'</b><br>'+newName);
            }

            this.setPreviousFiles();
        }
    },

    // Delete a file
    deleteFiles: function(fileList) {
        var tgtFiles, tgtListDisplay;

        tgtFiles = fileList ? fileList : this.selectedFiles;
        tgtListDisplay = tgtFiles.toString().replace(/\|/g,"/").replace(/,/g,"\n");
        if (tgtFiles.length>0 && this.ask('Delete:\n\n'+tgtListDisplay+'?')) {
            this.serverQueue("add",iceLoc+"/lib/file-control-xhr.php?action=delete&&csrf="+this.csrf,encodeURIComponent(tgtFiles.join(";")));
            this.serverMessage('<b>'+t['Deleting File']+'</b><br>'+tgtListDisplay);
        };
    },

    // Copy files
    copyFiles: function(fileList,dontShowPaste,dontHide) {
        this.copiedFiles = [];
        for (var i=0; i<fileList.length; i++) {
            this.copiedFiles[i] = fileList[i];
        }
        if (!dontShowPaste) {
            get('fmMenuPasteOption').style.display = "block";
        }
        if (!dontHide) {
            this.hideFileMenu();
        }
    },

    // Paste files
    pasteFiles: function(location) {
        if (this.copiedFiles) {
            for (var i=0; i<this.copiedFiles.length; i++) {
                if (this.copiedFiles[i]!="|") {
                    this.serverQueue("add",iceLoc+"/lib/file-control-xhr.php?action=paste&location="+location+"&csrf="+this.csrf,encodeURIComponent(this.copiedFiles[i]));
                    this.serverMessage('<b>'+t['Pasting File']+'</b><br>'+this.copiedFiles[i].toString().replace(/\|/g,"/").replace(/,/g,"\n"));
                } else {
                    this.message(t['Sorry cannot paste...']);
                }
            }
        } else {
            this.message(t['Nothing to paste...']);
        }
    },

    // Duplicate (copy & paste) files
    duplicateFiles: function(fileList) {
        var copiedFiles, location;

        // Take a snapshot of copied files
        if (this.copiedFiles) {
            copiedFiles = this.copiedFiles;
        }

        this.copyFiles(fileList,'dontShowPaste','dontHide');
        location = fileList[0].substr(0,fileList[0].lastIndexOf("|"));
        this.pasteFiles(location);

        // Restore copied files back to the snapshot
        if ("undefined" != typeof copiedFiles) {
            this.copiedFiles = copiedFiles;
        }
    },

    // Upload file(s) - select & submit
    uploadFilesSelect: function(location) {
        get('uploadDir').value = location;
        get("fileInput").click();
    },
    uploadFilesSubmit: function(obj) {
        if (get('fileInput').value!="") {
            this.showHide('show',get('loadingMask'));
            get('uploadFilesForm').submit();
            event.preventDefault();
        }
    },

    // Show/hide file manager nav options
    showHideFileNav: function(vis,elem) {
        var options = ["optionsFile","optionsEdit","optionsSource","optionsHelp"];
        if (vis=="hide") {
            fileNavInt = setTimeout(function(ic) {
                for (var i=0; i<options.length; i++) {
                    ic.showHide('hide',get(options[i]));
                    get(options[i]+'Nav').style.color = '';
                }
            },150,this);
        } else {
            for (var i=0; i<options.length; i++) {
                this.showHide('hide',get(options[i]));
                get(options[i]+'Nav').style.color = '';
            }
        }
        get('fileOptions').style.opacity = 0;
        if (vis=="show") {
            if ("undefined" != typeof fileNavInt) {
                clearTimeout(fileNavInt);
            }
            this.showHide(vis,get(elem));
            get(elem+'Nav').style.color = '#fff';
            get('fileOptions').style.opacity = 1;
        }
    },

    // Is a specified path a folder? (Note: path is string encoded path with / replaced with |)
    isPathFolder: function(path){
        // let's enumerate all folders to find whether clicked file is a folder or not
        var dir = this.filesFrame.contentDocument.getElementsByClassName("pft-directory");
        var thisFileId = this.selectedFiles[0];
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
        xhr = this.xhrObj();
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
                    if (["raw","both"].indexOf(this.fileDirResOutput) >= 0) {
                        console.log(xhr.responseText);
                    }
                    // User wanted object (or both) output of the response?
                    if (["object","both"].indexOf(this.fileDirResOutput) >= 0) {
                        console.log(statusObj);
                    }

                    // Also store the statusObj
                    this.lastFileDirCheckStatusObj = statusObj;

                    // If error, show that, otherwise do whatever we're required to do next
                    if (statusObj.status.error) {
                        this.message(statusObj.status.errorMsg);
                        console.log("ICEcoder error info for your request...");
                        console.log(statusObj);
                        this.serverMessage();
                        this.serverQueue('del',0);
                    } else {
                        eval(statusObj.action.doNext);
                    }
                    // Some other response? Display a message about that
                } else {
                    this.message(t['Sorry there was...']);
                    console.log("ICEcoder error info for your request...");
                    console.log(statusObj);
                    this.serverMessage();
                    this.serverQueue('del',0);
                }
            }
        };
        xhr.open("POST",iceLoc+"/lib/file-control-xhr.php?action=checkExists&csrf="+this.csrf,true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        timeStart = new Date().getTime();
        xhr.send('timeStart='+timeStart+'&file='+encodeURIComponent(path));
    },

    // Show menu on right clicking in file manager
    showMenu: function(evt) {
        var menuType, menuHeight, winH, fmYPos;

        if (	this.selectedFiles.length == 0 ||
            this.selectedFiles.indexOf(this.selectedFiles[this.selectedFiles.length-1].replace(/\//g,"|")) == -1) {
            this.selectFileFolder(evt);
        }

        menuHeight = 124+5; // general options height in px plus 5px space
        winH = window.innerHeight;
        if ("undefined" != typeof this.thisFileFolderLink && this.thisFileFolderLink!="") {
            menuType = this.isPathFolder(this.selectedFiles[0]) ? "folder" : "file";
            get('folderMenuItems').style.display = menuType == "folder" && this.selectedFiles.length == 1 ? "block" : "none";
            if (menuType == "folder" && this.selectedFiles.length == 1) {
                menuHeight += 20+20+1+23+1+2; // new file, new folder, hr, upload files(s), hr, padding
                if (get('fmMenuPasteOption').style.display == "block") {
                    menuHeight += 19;
                }
            }
            get('singleFileMenuItems').style.display = this.selectedFiles.length > 1 ? "none" : "block";
            if (this.selectedFiles.length == 1) {
                menuHeight += 43;
            }
            get('fileMenu').style.display = "inline-block";
            setTimeout(function() {get('fileMenu').style.opacity = "1"},4);
            get('fileMenu').style.left = (this.mouseX+20) + "px";
            fmYPos = this.mouseY-this.filesFrame.contentWindow.document.body.scrollTop-10;
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
            perms = actionElemType=="file" ? this.newFilePerms : this.newDirPerms;

            // Identify our target element & the first child element in it's location
            if (!location) {location="/"}
            location = location.replace(iceRoot,"/");
            location = location.replace("//","/");
            targetElem = get('filesFrame').contentWindow.document.getElementById(location.replace(/\//g,"|"));
            locNest = targetElem.parentNode.parentNode.nextSibling;
            newText = document.createTextNode("\n");
            permColors = perms == 777 ? 'background: #800; color: #eee' : 'color: #888';
            innerLI = '<a nohref title="'+location.replace(/\/$/, "")+"/"+file+'" onMouseOver="parentNode.draggable=true;ICEcoder.overFileFolder(\''+actionElemType+'\',this.childNodes[1].id)" onMouseOut="parentNode.draggable=false;ICEcoder.overFileFolder(\''+actionElemType+'\',\'\')" '+

                (actionElemType == "folder" ? 'ondragover="if(parentNode.nextSibling && parentNode.nextSibling.tagName != \'UL\' && this.thisFileFolderLink != this.childNodes[1].id) {this.openCloseDir(this,true);}"':'')+

                ' onClick="if(!event.ctrlKey && !this.cmdKey) {'+

                (actionElemType == "folder" ? 'ICEcoder.openCloseDir(this,'+(actionElemType=="folder" ? 'true' : 'false')+');':'')+

                ' if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {this.openFile()}}" style="position: relative; left:-22px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id="'+location.replace(/\/$/, "").replace(/\//g,"|")+"|"+file+'">'+file+'</span> <span style="'+permColors+'; font-size: 8px" id="'+location.replace(/\/$/, "").replace(/\//g,"|")+"|"+file+'_perms">'+perms+'</span></a>';

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
                newLI.ondragstart = function(event) {this.addDefaultDragData(this,event)};
                newLI.ondrag = function(event) {this.draggingWithKeyTest(event);if(this.getcMInstance()){this.editorFocusInstance.indexOf('diff') == -1 ? this.getcMInstance().focus() : this.getcMdiffInstance().focus()}};
                newLI.ondragover = function(event) {this.setDragCursor(event,actionElemType=="folder" ? 'folder' : 'file')};
                newLI.ondragend = function() {this.dropFile(this)};
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
                            newLI.ondragstart = function(event) {this.addDefaultDragData(this,event)};
                            newLI.ondrag = function(event) {this.draggingWithKeyTest(event);if(this.getcMInstance()){this.editorFocusInstance.indexOf('diff') == -1 ? this.getcMInstance().focus() : this.getcMdiffInstance().focus()}};
                            newLI.ondragover = function(event) {this.setDragCursor(event,actionElemType=="folder" ? 'folder' : 'file')};
                            newLI.ondragend = function() {this.dropFile(this)};
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
                this.openFiles[this.selectedTab-1]=location+file;
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
            this.renameInChildren(targetElem, oldName, location, file);
        }

        // Moving files
        if (action=="move") {
            this.updateFileManagerList("add",location,file,false,false,false,fileOrFolder);
            this.updateFileManagerList("delete",oldName.substr(0,oldName.lastIndexOf("/")),file);
        }

        // Chmod on files
        if (action=="chmod") {
            // Get short URL for our file and get our target elem based on this
            shortURL = this.selectedFiles[this.selectedFiles.length-1].replace(/\|/g,"/");
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
            this.openCloseDir(targetElem.childNodes[0],false);
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
                    this.renameInChildren(targetElem, oldName, location, file);
                }
            }
        }
    },

    // Refresh file manager
    refreshFileManager: function() {
        this.showHide('show',get('loadingMask'));
        this.filesFrame.contentWindow.location.reload(true);
        this.filesFrame.style.opacity="0";
        this.filesFrame.onload = function() {
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
            this.cmdKey = true;
        }

        this.draggingWithKey = evt.ctrlKey||this.cmdKey ? "CTRL" : false;
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
        this.draggingWithKeyTest(evt);
        // Establish the cursor to show
        cursorIcon =
            dropType == "editor"
                ? this.draggingWithKey == "CTRL"
                ? "copy"
                : "link"
                : dropType == "folder"
                ? this.draggingWithKey == "CTRL"
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
        if (this.area=='editor') {
            this.pasteURL(filePath);
        };
        if (this.area=='files') {
            setTimeout(function() {
                tgtPath = ic.thisFileFolderType == "folder" ? ic.thisFileFolderLink : ic.thisFileFolderLink.substr(0,ic.thisFileFolderLink.lastIndexOf("|"));
                if(ic.draggingWithKey == "CTRL") {
                    ic.copyFiles(ic.selectedFiles);
                    ic.pasteFiles(tgtPath);
                } else {
                    ic.moveFile(filePath,tgtPath.replace(/\|/g,"/") + "/" + fileName);
                }
            },4,this);
        };
        this.mouseDown=false;
        this.mouseDownInCM=false;
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
            this.focus();
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
        cM = this.getcMInstance();
        cMdiff = this.getcMdiffInstance();
        thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
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
            if (!this.findMode||find!=this.lastsearch) {
                this.results = [];
                this.resultsLines = [];

                for (var i=0;i<content.length;i++) {
                    if (content.substr(i,find.length)==find && this.results.indexOf(i) == -1) {
                        this.results.push(i);
                        if (this.resultsLines.indexOf(thisCM.posFromIndex(i).line+1)==-1) {
                            this.resultsLines.push(thisCM.posFromIndex(i).line+1);
                        }
                    }
                }

                // Also remember the last search term made
                this.lastsearch = find;
            }

            // If we have results
            if (this.results.length>0) {

                // Show results only
                if (resultsOnly) {
                    results.innerHTML = this.results.length + " results";
                    // We need to take action instead
                } else {
                    // Find our cursor position relative to results
                    // Go next
                    if (!findPrevious) {
                        this.findResult = 0;
                        for (var i=0;i<this.results.length;i++) {
                            if (this.results[i]<thisCM.indexFromPos({"ch": thisCM.getCursor().ch+1, "line": thisCM.getCursor().line})) {
                                this.findResult++;
                            }
                        }
                        // Go previous
                    } else {
                        if("undefined" == typeof this.findResult) {
                            this.findResult = this.results.length+1;
                        } else {
                            this.findResult = this.results.length;
                        }
                        for (var i=this.results.length-1;i>=0;i--) {
                            if (this.results[i]>thisCM.indexFromPos({"ch": thisCM.getCursor().ch-1, "line": thisCM.getCursor().line})) {
                                this.findResult--;
                            }
                        }
                    }

                    // Loop round to start
                    if (!findPrevious && this.findResult>this.results.length-1) {
                        this.findResult = 0
                    }
                    // Loop round to end
                    if (findPrevious && this.findResult==1) {
                        this.findResult = this.results.length+1;
                    }

                    // Update results display
                    results.innerHTML = "Highlighted result "+(this.findResult+(!findPrevious ? 1 : -1))+" of "+this.results.length+" results";

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
                    this.focus();
                    this.findMode = true;
                }

                // Display the find results bar
                // The avg block is either line height or fraction of space available
                avgBlockH = !this.scrollBarVisible ? thisCM.defaultTextHeight() : parseInt(this.content.style.height,10)/thisCM.lineCount();
                // Need to add padding if there's no scrollbar, so current line highlighting lines up with it
                addPadding = !this.scrollBarVisible ? thisCM.heightAtLine(0) : 0;
                rBlocks = "";
                for (var i=1; i<=thisCM.lineCount(); i++) {
                    blockColor = this.resultsLines.indexOf(i)>-1 ? thisCM.getCursor().line+1 == i ? "rgba(192,0,0,0.3)" : "rgba(128,128,128,0.3)" : "transparent";
                    rBlocks += '<div style="position: absolute; display: block; width: 12px; height:'+avgBlockH+'px; background: '+blockColor+'; top: '+parseInt((avgBlockH*(i-1))+addPadding,10)+'px"></div>';
                }
                this.content.contentWindow.document.getElementById('resultsBar').innerHTML = rBlocks;
                this.content.contentWindow.document.getElementById('resultsBar').style.display = "inline-block";
                return true;

            } else {
                results.innerHTML = "No results";
                this.content.contentWindow.document.getElementById('resultsBar').innerHTML = "";
                this.content.contentWindow.document.getElementById('resultsBar').style.display = "none";
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
                    filesQS = "&selectedFiles="+this.selectedFiles.join(":");
                }
                find = find.replace(/\'/g, '\&#39;');
                find != encodeURIComponent(find) ? find = 'ICEcoder:'+encodeURIComponent(find) : find;
                this.showHide('show',get('loadingMask'));
                get('mediaContainer').innerHTML = '<iframe src="'+iceLoc+'/lib/multiple-results.php?find='+find+replaceQS+targetQS+filesQS+'&csrf='+this.csrf+'" id="multipleResultsIFrame" class="whiteGlow" style="width: 700px; height: 500px"></iframe>';
                // We have nothing to search for, blank it all out
            } else {
                results.innerHTML = "No results";
                this.content.contentWindow.document.getElementById('resultsBar').innerHTML = "";
                this.content.contentWindow.document.getElementById('resultsBar').style.display = "none";
            }
        }
    },

    // Replace text in a file
    replaceInFile: function(fileRef,find,replace) {
        this.serverQueue("add",iceLoc+"/lib/file-control-xhr.php?action=replaceText&find="+find+"&replace="+replace+"&csrf="+this.csrf,encodeURIComponent(fileRef.replace(/\//g,"|")));
        this.serverMessage('<b>'+t['Replacing text in']+'</b><br>'+fileRef);
    },

// ==============
// INFO & DISPLAY
// ==============

    // Get the caret position
    getCaretPosition: function() {
        var cM, cMdiff, thisCM, line, ch, chPos;

        cM = this.getcMInstance();
        cMdiff = this.getcMdiffInstance();
        thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;

        line = thisCM.getCursor().line;
        ch = thisCM.getCursor().ch;
        chPos = 0;
        for (var i=0;i<line;i++) {
            chPos += thisCM.getLine(i).length+1;
        }
        this.caretPos=(chPos+ch-1);
    },

    // Update the code type, line & character display
    updateCharDisplay: function() {
        var cM, cMdiff, thisCM;

        cM = this.getcMInstance();
        cMdiff = this.getcMdiffInstance();
        thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
        this.caretLocationType();
        this.charDisplay.innerHTML = this.caretLocType + ", Line: " + (thisCM.getCursor().line+1) + ", Char: " + thisCM.getCursor().ch;
    },

    // Update version display
    updateVersionsDisplay: function() {
        var versionsCount = this.openFileVersions[this.selectedTab-1];

        get('versionsDisplay').innerHTML = "undefined" != typeof versionsCount
            ? this.openFileVersions[this.selectedTab-1] + " backup" +
            (versionsCount != 1 ? "s" : "")
            : "";
    },

    // Update the byte display
    updateByteDisplay: function() {
        var cM, cMdiff, thisCM;

        cM = this.getcMInstance();
        cMdiff = this.getcMdiffInstance();
        thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
        this.byteDisplay.innerHTML = thisCM.getValue().length.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + " bytes";
    },

    // Toggle the char/byte display
    showDisplay: function(show) {
        this.byteDisplay.style.display = show == "byte" ? "inline-block" : "none";
        this.charDisplay.style.display = show == "char" ? "inline-block" : "none";
    },

    // Show & hide target element
    showHide: function(doVis,elem) {
        elem.style.visibility = doVis=="show" ? 'visible' : 'hidden';
    },

    // Determine the CodeMirror instance we're using
    getcMInstance: function(tab) {
        ic = this;
        if (!ic.content) {
            ic = parent.ICEcoder;
        }
        if (!ic.content) {
            ic = parent.ICEcoder;
        }
        return ic.content.contentWindow[
            // target specific tab
            !isNaN(tab)
                ? 'cM'+ICEcoder.cMInstances[tab-1]
                // new tab or selected tab
                : tab=="new"||(tab!="new" && this.openFiles.length>0)
                ? 'cM'+ICEcoder.cMInstances[this.selectedTab-1]
                // fallback to first tab
                : 'cM1'
            ];
    },

    // Determine the CodeMirror instance we're using
    getcMdiffInstance: function(tab, context) {
        if ("undefined" === typeof context) {
            context = ICEcoder;
        }
        return context.content.contentWindow[
        (// target specific tab
            !isNaN(tab)
                ? 'cM'+ICEcoder.cMInstances[tab-1]
                // new tab or selected tab
                : tab=="new"||(tab!="new" && this.openFiles.length>0)
                ? 'cM'+ICEcoder.cMInstances[this.selectedTab-1]
                // fallback to first tab
                : 'cM1')
        + 'diff'
            ];
    },

    // Get the mouse position
    getMouseXY: function(e,area) {
        var tempX, tempY;

        this.mouseX = e.pageX ? e.pageX : e.clientX + document.body.scrollLeft;
        this.mouseY = e.pageY ? e.pageY : e.clientY + document.body.scrollTop;

        this.area = area;
        if (area!="top") {
            this.mouseY += 25 + 45;
        }
        if (area=="editor") {
            this.mouseX += this.filesW;
        }
        this.dragCursorTest();
        if (this.mouseY>62) {this.setTabWidths();};
    },

    // Test if we need to show a drag cursor or not
    dragCursorTest: function() {
        var diffX, winH, cursorName, zone;

        // Dragging tabs, started after dragging for 10px from origin
        diffX = this.mouseX - this.diffStartX;
        if (this.draggingTab!==false && this.diffStartX && (diffX <= -10 || diffX >= 10)) {
            if (this.mouseX > parseInt(this.files.style.width,10)) {
                this.tabDragMouseX = this.mouseX - parseInt(this.files.style.width,10) - this.tabDragMouseXStart;
                this.tabDragMove();
            }
        }

        // Dragging file manager, possible within 7px of file manager edge
        if (this.ready) {
            winH = window.innerHeight;
            if (!this.mouseDown) {this.draggingFilesW = false};

            cursorName = (!this.draggingTab && ((this.mouseX > this.filesW-7 && this.mouseX < this.filesW+7) || this.draggingFilesW))
                ? "w-resize"
                : "auto";
            if (this.content.contentWindow.document && this.filesFrame.contentWindow) {
                document.body.style.cursor = cursorName;
                if (zone = this.content.contentWindow.document.body)	{zone.style.cursor = cursorName};
                if (zone = this.filesFrame.contentWindow.document.body)	{zone.style.cursor = cursorName};
            }
        }
    },

    // Show or hide a server message
    serverMessage: function(message) {
        var serverMessage;

        serverMessage =	get('serverMessage');
        if (message) {
            serverMessage.innerHTML = this.xssClean(message).replace(/\&lt;b\&gt;/g,"<b>").replace(/\&lt;\/b\&gt;/g,"</b>").replace(/\&lt;br\&gt;/g,"<br>");
            serverMessage.style.left = "0";
        } else {
            setTimeout(function() {serverMessage.style.left = "2000px";},200);
        }
        serverMessage.style.opacity = message ? 1 : 0;
    },

    // Show a CSS color block next to our text cursor
    cssColorPreview: function() {
        var cM, cMdiff, thisCM, string, rx, match, oldBlock, newBlock;

        cM = this.getcMInstance();
        cMdiff = this.getcMdiffInstance();
        thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;

        if (thisCM) {
            string = thisCM.getLine(thisCM.getCursor().line);
            rx = /(#[\da-f]{3}(?:[\da-f]{3})?\b|\b(?:rgb|hsl)a?\([\s\d%,.-]+\)|\b[a-z]+\b)/gi;

            while((match = rx.exec(string)) && thisCM.getCursor().ch > match.index+match[0].length);

            oldBlock = get('content').contentWindow.document.getElementById('cssColor');
            if (oldBlock) {oldBlock.parentNode.removeChild(oldBlock)};
            if (this.codeAssist && this.caretLocType=="CSS") {
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
            '<input type="button" onClick="this.insertColorValue(get(\'color\').value)" value="insert &gt;" class="insertColorValue"><br>'+
            '<input type="text" id="colorRGB" name="colorRGB" value="rgb(0,0,0)" class="colorValue">'+
            '<input type="button" onClick="this.insertColorValue(get(\'colorRGB\').value)" value="insert &gt;" class="insertColorValue">';
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
            hex = this.rgbToHex(R,G,B);
            // set the values & BG colours of the input boxes
            get('rgbMouseXY').value = rgb;
            get('hexMouseXY').value = '#' + hex;
            get('hexMouseXY').style.backgroundColor = get('rgbMouseXY').style.backgroundColor = '#' + hex;
            textColor = R<128 || G<128 || B<128 && (R<200 && G<200 && B>50) ? '#fff' : '#000';
            get('hexMouseXY').style.color = get('rgbMouseXY').style.color = textColor;

            // Move the floating container to follow mouse pointer
            fcElem = get('floatingContainer');
            fcElem.style.left = this.mouseX+20 + "px";
            fcElem.style.top = this.mouseY + "px";
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
        return this.toHex(R)+this.toHex(G)+this.toHex(B);
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

        cM = this.getcMInstance();
        cMdiff = this.getcMdiffInstance();
        thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
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
        i = this.openFiles.indexOf(file);
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
            plugURL = plugURL+"&csrf="+this.csrf;
        }
        this['plugTimer'+plugRef] =
            // This window instances
            ["_parent","_top","_self",""].indexOf(plugTarget) > -1
                ? this['plugTimer'+plugRef] = setInterval('window.location=\''+plugURL+'\'',plugTimer*1000*60)
                // fileControl iframe instances
                : plugTarget.indexOf("fileControl") == 0
                ? this['plugTimer'+plugRef] = setInterval(function(ic) {
                    ic.serverQueue("add",plugURL);ic.serverMessage(plugTarget.split(":")[1]);
                },plugTimer*1000*60,this)
                // _blank or named target window instances
                : this['plugTimer'+plugRef] = setInterval('window.open(\''+plugURL+'\',\''+plugTarget+'\')',plugTimer*1000*60);

        // push the plugin ref into our array
        this.pluginIntervalRefs.push(plugRef);
    },

    // Turning on/off the Code Assist
    codeAssistToggle: function() {
        var cM, cMdiff, fileName, fileExt;

        this.codeAssist = !this.codeAssist;
        this.cssColorPreview();
        this.focus(this.editorFocusInstance.indexOf('diff') > -1 ? 'diff' : false);

        for (i=0;i<this.cMInstances.length;i++) {
            fileName = this.openFiles[i];
            fileExt = fileName.split(".");
            fileExt = fileExt[fileExt.length-1];
            if (fileExt == "js" || fileExt == "json") {
                cM = this.content.contentWindow['cM'+this.cMInstances[i]];
                cMdiff = this.content.contentWindow['cM'+this.cMInstances[i]+'diff'];
                if (!this.codeAssist) {
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
        if (this.serverQueueItems.indexOf(item) !== -1) {
            this.serverMessage();
            this.serverQueue("del",0);
            return;
        }

        cM = this.getcMInstance();
        // Firstly, work out how many saves we have to carry out
        nextSaveID=0;
        for (var i=0;i<this.serverQueueItems.length;i++) {
            if (this.serverQueueItems[i].indexOf('action=save')>0) {
                nextSaveID++;
            }
        }
        nextSaveID++;
        // Add to end of array or remove from beginning on demand, plus add or remove if necessary
        if (action=="add") {
            this.serverQueueItems.push(item);
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
            // console.log(this);
            if (this.serverQueueItems[0] && this.serverQueueItems[0].indexOf('action=save')>0) {
                topSaveID = nextSaveID-1;
                for (var i=1;i<topSaveID;i++) {
                    get('saveTemp'+i).value = get('saveTemp'+(i+1)).value;
                }
                element = get('saveTemp'+topSaveID);
                element.parentNode.removeChild(element);
            }
            this.serverQueueItems.splice(0,1);
        }

        // If we've just removed from the array and there's another action queued up, or we're triggering for the first time
        // then do the next requested process, stored at array pos 0
        if (action=="del" && this.serverQueueItems.length>=1 || this.serverQueueItems.length==1) {
            // If we have an item, we're not saving previous file refs and not loading
            if (item && (item.indexOf('saveFiles=')==-1 && item.indexOf('action=load')==-1)) {
                xhr = this.xhrObj();
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
                            if (["raw","both"].indexOf(this.fileDirResOutput) >= 0) {
                                console.log(xhr.responseText);
                            }
                            // User wanted object (or both) output of the response?
                            if (["object","both"].indexOf(this.fileDirResOutput) >= 0) {
                                console.log(statusObj);
                            }
                            // If error, show that, otherwise do whatever we're required to do next
                            if (statusObj.status.error) {
                                this.message(statusObj.status.errorMsg);
                                console.log("ICEcoder error info for your request...");
                                console.log(statusObj);
                                this.serverMessage();
                                this.serverQueue('del',0);
                            } else {
                                eval(statusObj.action.doNext);
                            }
                            // Some other response? Display a message about that
                        } else {
                            this.message(t['Sorry there was...']);
                            console.log("ICEcoder error info for your request...");
                            console.log(statusObj);
                            this.serverMessage();
                            this.serverQueue('del',0);
                        }
                    }
                };
                xhr.open("POST",this.serverQueueItems[0],true);
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

                setTimeout(function(ic) {
                    if ("undefined" != typeof ic.serverQueueItems[0]) {
                        ic.filesFrame.contentWindow.frames['fileControl'].location.href=ic.serverQueueItems[0];
                    }
                },1,this);

            }
        }
    },

    // Cancel all actions on pressing Esc in non content areas
    cancelAllActions: function() {
        // Stop whatever the parent may be loading and clear tasks other than the current one
        window.stop();
        if (this.serverQueueItems.length>0) {
            this.serverQueueItems.splice(1,this.serverQueueItems.length);
        }
        this.showHide('hide',get('loadingMask'));
        this.serverMessage('<b style="color: #d00">'+t['Cancelled tasks']+'</b>');
        setTimeout(function(ic) {ic.serverMessage();},2000,this);
    },

    // Set the current previousFiles in the settings file
    setPreviousFiles: function() {
        var previousFiles;

        previousFiles = this.openFiles.join(',').replace(/\//g,"|").replace(/(\|\[NEW\])|(,\|\[NEW\])/g,"").replace(/(^,)|(,$)/g,"");
        if (previousFiles=="") {previousFiles="CLEAR"};
        // Then send through to the settings page to update setting
        this.serverQueue("add",iceLoc+"/lib/settings.php?saveFiles="+encodeURIComponent(previousFiles)+"&csrf="+this.csrf);
        this.updateLast10List(previousFiles);
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
                newFile = "<li class=\"pft-file ext-"+previousFiles[i].substring(previousFiles[i].lastIndexOf(".")+1)+"\" style=\"margin-left: -21px\"><a style=\"cursor:pointer\" onclick=\"this.openFile('"+previousFiles[i].replace(/\|/g,"/")+"')\">"+previousFiles[i].replace(/\|/g,"/")+"</a></li>\n";

                // Get DOM elem for last 10 files
                last10Files = this.content.contentWindow.document.getElementById('last10Files');

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
        if (this.previousFiles.length>0 && this.ask(t['Open previous files']+'\n\n'+this.previousFiles.length+' files:\n'+this.previousFiles.join('\n').replace(/\|/g,"/").replace(new RegExp(docRoot+iceRoot,'gi'),""))) {
            for (var i=0;i<this.previousFiles.length;i++) {
                this.thisFileFolderLink=this.previousFiles[i].replace('|','/');
                this.thisFileFolderType='file';
                this.openFile();
            }
        }
    },

    // Show the settings screen
    settingsScreen: function(hide) {
        if (!hide) {
            get('mediaContainer').innerHTML = '<iframe src="'+iceLoc+'/lib/settings-screen.php" id="settingsIFrame" class="whiteGlow" style="width: 970px; height: 610px"></iframe>';
        }
        this.showHide(hide?'hide':'show',get('blackMask'));
    },

    // Show the help screen
    helpScreen: function() {
        get('mediaContainer').innerHTML = '<iframe src="'+iceLoc+'/lib/help.php" id="helpIFrame" class="whiteGlow" style="width: 840px; height: 485px"></iframe>';
        this.showHide('show',get('blackMask'));
    },

    // Show the backup versions screen
    versionsScreen: function(file,versions) {
        get('mediaContainer').innerHTML = '<iframe src="'+iceLoc+'/lib/backup-versions.php?file='+file+'&csrf='+this.csrf+'" id="versionsIFrame" class="whiteGlow" style="width: 970px; height: 640px"></iframe>';
        this.showHide('show',get('blackMask'));
    },

    // Show the ICEcoder manual, loaded remotely
    showManual: function(version,section) {
        var sectionExtra;

        sectionExtra = section ? "#"+section : "";
        get('mediaContainer').innerHTML = '<iframe src="https://icecoder.net/manual?version='+version+sectionExtra+'" id="manualIFrame" class="whiteGlow" style="width: 800px; height: 470px"></iframe>';
        this.showHide('show',get('blackMask'));
    },

    // Show the properties screen
    propertiesScreen: function(fileName) {
        get('mediaContainer').innerHTML = '<iframe src="'+iceLoc+'/lib/properties.php?fileName='+fileName.replace(/\//g,"|")+'&csrf='+this.csrf+'" id="propertiesIFrame" class="whiteGlow" style="width: 660px; height: 330px"></iframe>';
        this.showHide('show',get('blackMask'));
    },

    // Show the auto-logout warning screen
    autoLogoutWarningScreen: function() {
        get('mediaContainer').innerHTML = '<iframe src="'+iceLoc+'/lib/auto-logout-warning.php" id="autoLogoutIFrame" class="whiteGlow" style="width: 400px; height: 160px"></iframe>';
        this.showHide('show',get('blackMask'));
    },

    // Show the plugins manager
    pluginsManager: function() {
        get('mediaContainer').innerHTML = '<iframe src="'+iceLoc+'/lib/plugins-manager.php" id="pluginsManagerIFrame" class="whiteGlow" style="width: 800px; height: 450px"></iframe>';
        this.showHide('show',get('blackMask'));
    },

    // Go to localhost root
    goLocalhostRoot: function() {
        this.filesFrame.contentWindow.frames['fileControl'].location.href = iceLoc+"/lib/go-localhost-root.php";
    },

    // Show the FTP manager
    ftpManager: function() {
        get('mediaContainer').innerHTML = '<iframe src="'+iceLoc+'/lib/ftp-manager.php" id="ftpManagerIFrame" class="whiteGlow" style="width: 660px; height: 550px"></iframe>';
        this.showHide('show',get('blackMask'));
    },

    // Update the settings used when we make a change to them
    useNewSettings: function(themeURL,codeAssist,lockedNav,tagWrapperCommand,autoComplete,visibleTabs,fontSize,lineWrapping,lineNumbers,showTrailingSpace,matchBrackets,autoCloseTags,autoCloseBrackets,indentWithTabs,indentAuto,indentSize,pluginPanelAligned,scrollbarStyle,bugFilePaths,bugFileCheckTimer,bugFileMaxLines,updateDiffOnSave,autoLogoutMins,refreshFM) {
        var styleNode, thisCSS, strCSS, activeLineBG;

        // cut out ?microtime= at the end
        var cleanThemeUrl = themeURL.slice(0, themeURL.lastIndexOf("?"));
        // find out new theme name without leading path and trailing ".css"
        var newTheme = cleanThemeUrl.slice(cleanThemeUrl.lastIndexOf("/")+1,cleanThemeUrl.lastIndexOf("."));
        // if theme was not changed - no need to do all these tricks
        if (this.theme !== newTheme){
            // Add new stylesheet for selected theme to editor
            this.theme = newTheme;
            if (this.theme=="editor") {this.theme="icecoder"};
            styleNode = document.createElement('link');
            styleNode.setAttribute('rel', 'stylesheet');
            styleNode.setAttribute('type', 'text/css');
            styleNode.setAttribute('href', themeURL);
            this.content.contentWindow.document.getElementsByTagName('head')[0].appendChild(styleNode);
            if (["3024-day","base16-light","eclipse","elegant","mdn-like","neat","neo","paraiso-light","solarized","the-matrix","xq-light"].indexOf(this.theme)>-1) {
                activeLineBG = "#ccc";
            } else if (["3024-night","blackboard","colorforth","liquibyte","night","tomorrow-night-bright","tomorrow-night-eighties","vibrant-ink"].indexOf(this.theme)>-1) {
                activeLineBG = "#888";
            } else {
                activeLineBG = "#000";
            }
            this.switchTab(this.selectedTab);
        }

        // Check/uncheck Code Assist setting
        if (codeAssist != this.codeAssist) {
            this.codeAssistToggle();
        }

        // Unlock/lock the file manager
        if (lockedNav != this.lockedNav) {
            this.lockUnlockNav();
            this.changeFilesW(!lockedNav ? 'contract' : 'expand');
            this.hideFileMenu();
        };

        // Update font size at top level
        thisCSS = document.styleSheets[0];
        strCSS = thisCSS.rules ? 'rules' : 'cssRules';
        thisCSS[strCSS][0].style['fontSize'] = fontSize;

        // Update font size in file manager
        thisCSS = this.filesFrame.contentWindow.document.styleSheets[3];
        strCSS = thisCSS.rules ? 'rules' : 'cssRules';
        thisCSS[strCSS][0].style['fontSize'] = fontSize;

        // Update styles in editor
        thisCSS = this.content.contentWindow.document.styleSheets[6];
        strCSS = thisCSS.rules ? 'rules' : 'cssRules';
        thisCSS[strCSS][0].style['fontSize'] = fontSize;
        thisCSS[strCSS][4].style['border-left-width'] = visibleTabs ? '1px' : '0';
        thisCSS[strCSS][4].style['margin-left'] = visibleTabs ? '-1px' : '0';
        thisCSS[strCSS][2].style.cssText = "background-color: " + activeLineBG + " !important";

        this.lineWrapping = lineWrapping;
        this.lineNumbers = lineNumbers;
        this.showTrailingSpace = showTrailingSpace;
        this.matchBrackets = matchBrackets;
        this.autoCloseTags = autoCloseTags;
        this.autoCloseBrackets = autoCloseBrackets;
        this.indentWithTabs = indentWithTabs;
        this.indentSize = indentSize;
        this.indentAuto = indentAuto;
        this.scrollbarStyle = scrollbarStyle;
        for (var i=0;i<this.cMInstances.length;i++) {
            // Main pane
            this.content.contentWindow['cM'+this.cMInstances[i]].setOption("lineWrapping", this.lineWrapping);
            this.content.contentWindow['cM'+this.cMInstances[i]].setOption("lineNumbers", this.lineNumbers);
            this.content.contentWindow['cM'+this.cMInstances[i]].setOption("showTrailingSpace", this.showTrailingSpace);
            this.content.contentWindow['cM'+this.cMInstances[i]].setOption("matchBrackets", this.matchBrackets);
            this.content.contentWindow['cM'+this.cMInstances[i]].setOption("autoCloseTags", this.autoCloseTags);
            this.content.contentWindow['cM'+this.cMInstances[i]].setOption("autoCloseBrackets", this.autoCloseBrackets);
            this.content.contentWindow['cM'+this.cMInstances[i]].setOption("indentWithTabs", this.indentWithTabs);
            this.content.contentWindow['cM'+this.cMInstances[i]].setOption("indentUnit", this.indentSize);
            this.content.contentWindow['cM'+this.cMInstances[i]].setOption("tabSize", this.indentSize);
            this.content.contentWindow['cM'+this.cMInstances[i]].setOption("scrollbarStyle", this.scrollbarStyle);
            this.content.contentWindow['cM'+this.cMInstances[i]].refresh();
            // Diff pane
            this.content.contentWindow['cM'+this.cMInstances[i]+'diff'].setOption("lineWrapping", this.lineWrapping);
            this.content.contentWindow['cM'+this.cMInstances[i]+'diff'].setOption("lineNumbers", this.lineNumbers);
            this.content.contentWindow['cM'+this.cMInstances[i]+'diff'].setOption("showTrailingSpace", this.showTrailingSpace);
            this.content.contentWindow['cM'+this.cMInstances[i]+'diff'].setOption("matchBrackets", this.matchBrackets);
            this.content.contentWindow['cM'+this.cMInstances[i]+'diff'].setOption("autoCloseTags", this.autoCloseTags);
            this.content.contentWindow['cM'+this.cMInstances[i]+'diff'].setOption("autoCloseBrackets", this.autoCloseBrackets);
            this.content.contentWindow['cM'+this.cMInstances[i]+'diff'].setOption("indentWithTabs", this.indentWithTabs);
            this.content.contentWindow['cM'+this.cMInstances[i]+'diff'].setOption("indentUnit", this.indentSize);
            this.content.contentWindow['cM'+this.cMInstances[i]+'diff'].setOption("tabSize", this.indentSize);
            this.content.contentWindow['cM'+this.cMInstances[i]+'diff'].setOption("scrollbarStyle", this.scrollbarStyle);
            this.content.contentWindow['cM'+this.cMInstances[i]+'diff'].refresh();
        }

        if (tagWrapperCommand != this.tagWrapperCommand) {
            this.tagWrapperCommand = tagWrapperCommand;
        }

        if (autoComplete != this.autoComplete) {
            this.autoComplete = autoComplete;
        }

        get('plugins').style.left = pluginPanelAligned == "left" ? "0" : "auto";
        get('plugins').style.right = pluginPanelAligned == "right" ? "0" : "auto";

        // Restart bug checking
        this.bugFilePaths = bugFilePaths;
        this.bugFileCheckTimer = bugFileCheckTimer;
        this.bugFileMaxLines = bugFileMaxLines;

        if (this.bugFilePaths[0] != "") {
            this.startBugChecking();
        } else {
            if ("undefined" != typeof this.bugFileCheckInt) {
                clearInterval(this.bugFileCheckInt);
            }
        }

        // Update diffs if we have a split pane
        if (this.splitPane) {
            this.updateDiffs();
        }

        // Set the flag to indicate if we update diff pane on save
        this.updateDiffOnSave = updateDiffOnSave;

        // Set the auto-logout mins value
        this.autoLogoutMins = autoLogoutMins;

        // Finally, refresh the file manager if we need to
        if (refreshFM) {this.refreshFileManager()};
    },

    // Update and show/hide found results display?
    updateResultsDisplay: function(showHide) {
        this.findReplace(get('find').value,true,false);
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
        this.filesFrame.contentWindow.frames['fileControl'].location.href="plugins/zip-it/index.php?zip="+tgt+"&csrf="+this.csrf;
    },

    // Prompt to download our file
    downloadFile: function(file) {
        file=file.replace(/\//g,"|");
        this.filesFrame.contentWindow.frames['fileControl'].location.href=iceLoc+"/lib/download.php?file="+file+"&csrf="+this.csrf;
    },

    // Change permissions on a file/folder
    chmod: function(file,perms) {
        file = file.replace(iceRoot,"");
        this.showHide('hide',get('blackMask'));
        this.serverQueue("add",iceLoc+"/lib/file-control-xhr.php?action=perms&perms="+perms+"&csrf="+this.csrf,encodeURIComponent(file));
        this.serverMessage('<b>chMod '+perms+' on </b><br>'+file.replace(/\|/g,"/"));
    },

    // Open/show the preview window
    openPreviewWindow: function() {
        if (this.openFiles.length>0) {
            var cM, cMdiff, thisCM, filepath, filename, fileExt;

            filepath = this.openFiles[this.selectedTab-1];
            filename = filepath.substr(filepath.lastIndexOf("/")+1);
            fileExt = filename.substr(filename.lastIndexOf(".")+1);
            cM = this.getcMInstance();
            cMdiff = this.getcMdiffInstance();
            thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;

            this.previewWindowLoading = true;
            this.previewWindow = window.open(filepath,"previewWindow",500,500);
            if (["md"].indexOf(fileExt) > -1) {
                this.previewWindow.onload = function() {
                    this.previewWindowLoading = false;
                    this.previewWindow.document.documentElement.innerHTML = mmd(thisCM.getValue())
                };
            } else {
                this.previewWindow.onload = function() {
                    this.previewWindowLoading = false;
                    // Do the pesticide plugin if it exists
                    try {this.doPesticide();} catch(err) {};
                    // Do the stats.js plugin if it exists
                    try {this.doStatsJS('open');} catch(err) {};
                    // Do the responsive plugin if it exists
                    try {this.doResponsive();} catch(err) {};
                }
            }
        }
    },

    // Reset auto-logout timer
    resetAutoLogoutTimer: function() {
        if(this.autoLogoutMins > 1 && this.autoLogoutTimer > (this.autoLogoutMins*60)-60) {
            this.showHide('hide',get('blackMask'));
        }
        this.autoLogoutTimer = 0;
    },

    // Logout of ICEcoder
    logout: function(type) {
        window.location = window.location + "?logout&"+(type ? "type="+type+"&" : "")+"csrf="+this.csrf;
    },

    // Show a message
    outputMsg: function(msg) {
        this.output.innerHTML += msg + "<br>";
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

        dM = this.content.contentWindow.document.getElementById('dataMessage');
        dM.style.display = "block";
        dM.innerHTML = message;
    },

    // Update ICEcoder
    update: function() {
        var autoUpdate;

        autoUpdate = confirm(t['Please note for...']);
        if (autoUpdate) {
            this.showHide('show',get('loadingMask'));
            window.location = iceLoc+"/lib/updater.php";
        } else {
            window.open("https://this.net");
        }
    },

    // ICEcoder just updated
    updated: function() {
        get('blackMask').style.visibility = "visible";
        get('mediaContainer').innerHTML 	= '<h1 style="color: #fff; cursor: default">Thanks for updating to v'+this.versionNo+'!</h1>'
            + '<h2 style="color: #888; cursor: default">Click anywhere to continue using this...</h2>';
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

        if(this.bugReportStatus=="off") {
            this.message(t['You can start...']);
        }
        if(this.bugReportStatus=="error") {
            this.message(t['Error cannot find...']);
        }
        if(this.bugReportStatus=="ok") {
            this.message(t['No new errors...']);
        }
        if(this.bugReportStatus=="bugs") {
            // Close bug-report without saving previousFiles and without confirming close if we made changes on the bug report
            var bugReportOpenFilePos = this.openFiles.indexOf(this.bugReportPath.replace(/\|/g,"/"));
            if (bugReportOpenFilePos > -1) {
                this.closeTab(bugReportOpenFilePos+1,'dontSetPV','dontAsk');
            }
            this.openFile(this.bugReportPath);
            this.bugFilesSizesSeen = this.bugFilesSizesActual;
        }
    },

    // Start bug checking by looking in bug file paths on a timer
    startBugChecking: function() {
        var bugCheckURL;

        if (this.bugFileCheckTimer !== 0) {
            // Clear any existing interval
            if ("undefined" != typeof this.bugFileCheckInt) {
                clearInterval(this.bugFileCheckInt);
            }
            // Start a new timer
            this.bugFilesSizesSeen = [];
            this.bugFileCheckInt = setInterval(function(ic) {
                bugCheckURL =  iceLoc+"/lib/bug-files-check.php?";
                bugCheckURL += "files="+(ic.bugFilePaths[0] !== "" ? ic.bugFilePaths.join() : "null").replace(/\//g,"|");
                bugCheckURL += "&filesSizesSeen=";
                if (ic.bugFilesSizesSeen.length != ic.bugFilePaths.length) {
                    // Fill the array with nulls
                    for (var i=0; i<ic.bugFilePaths.length; i++) {
                        ic.bugFilesSizesSeen[i] = "null";
                    }
                }
                bugCheckURL += ic.bugFilesSizesSeen.join();
                bugCheckURL += "&maxLines="+ic.bugFileMaxLines;
                bugCheckURL += "&csrf="+ic.csrf;

                var xhr = ic.xhrObj();

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
                        ic.bugReportStatus = statusArray['result'];
                        if (ic.bugFilesSizesSeen[0]=="null") {
                            ic.bugFilesSizesSeen = statusArray['filesSizesSeen'];
                        }
                        ic.bugFilesSizesActual = statusArray['filesSizesSeen'];
                        ic.bugReportPath = statusArray['bugReportPath'];

                    }
                };
                // console.log('Calling '+bugCheckURL+' via XHR');
                xhr.open("GET",bugCheckURL,true);
                xhr.send();

            },parseInt(this.bugFileCheckTimer*1000,10),this);
            // State that we're checking for bugs
            this.bugReportStatus = "ok";
        } else {
            if ("undefined" != typeof this.bugFileCheckInt) {
                clearInterval(this.bugFileCheckInt);
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

        cM = this.getcMInstance();
        cMdiff = this.getcMdiffInstance();
        thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
        printIFrame = this.filesFrame.contentWindow.frames['fileControl'];
        // Print page content injected into iFrame, escaped with pre and xssClean
        printIFrame.window.document.body.innerHTML = '<!DOCTYPE html><head><title>ICEcoder code output</title></head><body><pre style="white-space: pre-wrap">'+this.xssClean(thisCM.getValue())+'</pre></body></html>';
        printIFrame.focus();
        printIFrame.print();
        // Focus back on code
        thisCM.focus();
    },

    // Update the title tag to indicate any changes
    indicateChanges: function() {
        var winTitle;

        if (!this.loadingFile) {
            winTitle = "ICEcoder v "+this.versionNo;
            for(var i=1;i<=this.savedPoints.length;i++) {
                if (this.savedPoints[i-1]!=this.getcMInstance(i).changeGeneration()) {
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
        if (newTab !== this.selectedTab) {
            this.prevTab = this.selectedTab;
        }

        // Identify tab that's currently selected & get the instance
        this.selectedTab = newTab;
        cM = this.getcMInstance();
        cMdiff = this.getcMdiffInstance();
        thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;

        if (thisCM) {
            // Switch mode to HTML, PHP, CSS etc
            this.switchMode();

            // Set all cM instances to be hidden, then make our selected instance visible
            for (var i=0;i<this.cMInstances.length;i++) {
                this.content.contentWindow['cM'+this.cMInstances[i]].getWrapperElement().style.display = "none";
                this.content.contentWindow['cM'+this.cMInstances[i]+'diff'].getWrapperElement().style.display = "none";
            }
            cM.setOption('theme',this.theme);
            cMdiff.setOption('theme',this.theme + " diff");
            cM.getWrapperElement().style.display = "block";
            cMdiff.getWrapperElement().style.display = "block";

            // Redo our diffs if split pane
            if (this.splitPane) {
                this.updateDiffs();
            }

            // Focus on & refresh our selected instance
            if (!noFocus) {setTimeout(function(ic) {ic.focus();},4,this);}
            cM.refresh();
            cMdiff.refresh();

            this.updateFunctionClassList();
            this.highlightGitDiffs();

            // Highlight the selected tab
            this.redoTabHighlight(this.selectedTab);

            // Redo our find display
            this.findMode = false;
            this.findReplace(get('find').value,true,false);

            // Update our versions display
            this.updateVersionsDisplay();

            // Detect if we have a scrollbar & set layout again
            setTimeout(function(ic) {
                ic.scrollBarVisible = thisCM.getScrollInfo().height > thisCM.getScrollInfo().clientHeight;
                ic.setLayout();
            },0,this);

            // Finally, update the cursor display
            this.getCaretPosition();
            this.updateCharDisplay();
            this.updateByteDisplay();
        }
    },

    // Starts a new file by setting a few vars & creating a new cM instance
    newTab: function(alsoSave) {
        var cM;

        this.cMInstances.push(this.nextcMInstance);
        this.selectedTab = this.cMInstances.length;
        this.showHide('show',this.content);
        this.content.contentWindow.createNewCMInstance(this.nextcMInstance);
        this.setLayout();

        this.thisFileFolderType='file';
        this.thisFileFolderLink='/[NEW]';
        this.openFile();

        cM = this.getcMInstance('new');
        this.switchTab(this.openFiles.length);

        cM.removeLineClass(this['cMActiveLinecM'+this.cMInstances[this.selectedTab-1]], "background");
        this['cMActiveLinecM'+this.selectedTab] = cM.addLineClass(0, "background", "cm-s-activeLine");
        this.nextcMInstance++;

        // Also save?
        if (alsoSave) {
            this.saveFile();
        }
    },

    // Create a new tab for a file
    createNewTab: function(isNew) {
        var closeTabLink, fileName;

        // Push new file into array
        this.openFiles.push(this.shortURL);

        // Setup a new tab
        closeTabLink = '<a nohref onClick="ICEcoder.closeTab(parseInt(this.parentNode.id.slice(3),10))"><img src="'+iceLoc+'/assets/images/nav-close.gif" class="closeTab" onMouseOver="prevBG=this.style.backgroundColor;this.style.backgroundColor=\'#333\'; this.overCloseLink=true" onMouseOut="this.style.backgroundColor=prevBG; this.overCloseLink=false"></a>';
        get('tab'+(this.openFiles.length)).style.display = "inline-block";
        fileName = this.openFiles[this.openFiles.length-1];
        get('tab'+(this.openFiles.length)).innerHTML = closeTabLink + " " + fileName.slice(fileName.lastIndexOf("/")).replace(/\//,"");
        get('tab'+(this.openFiles.length)).title = "/" + this.openFiles[this.openFiles.length-1].replace(/\//,"");

        // Set the widths
        this.setTabWidths();

        // Highlight it and state it's selected
        this.redoTabHighlight(this.openFiles.length);
        this.selectedTab=this.openFiles.length;

        // Add a new value ready to indicate if this content has been changed
        this.savedPoints.push(0);
        this.savedContents.push("");

        if (!isNew) {
            this.setPreviousFiles();
        }
    },

    // Cycle to next tab
    nextTab: function() {
        var goToTab;

        goToTab = this.selectedTab+1 <= this.openFiles.length ? this.selectedTab+1 : 1;
        this.switchTab(goToTab,'noFocus');
    },

    // Cycle to next tab
    previousTab: function() {
        var goToTab;

        goToTab = this.selectedTab-1 >= 1 ? this.selectedTab-1 : this.openFiles.length;
        this.switchTab(goToTab,'noFocus');
    },

    // Create a new tab for a file
    renameTab: function(tabNum,newName) {
        var closeTabLink, fileName;

        // Push new file into array
        this.openFiles[tabNum-1] = newName;

        // Setup a new tab
        closeTabLink = '<a nohref onClick="ICEcoder.closeTab(parseInt(this.parentNode.id.slice(3),10))"><img src="'+iceLoc+'/assets/images/nav-close.gif" class="closeTab" onMouseOver="prevBG=this.style.backgroundColor;this.style.backgroundColor=\'#333\'; this.overCloseLink=true" onMouseOut="this.style.backgroundColor=prevBG; this.overCloseLink=false"></a>';
        fileName = this.openFiles[tabNum-1];
        get('tab'+tabNum).innerHTML = closeTabLink + " " + fileName.slice(fileName.lastIndexOf("/")).replace(/\//,"");
        get('tab'+tabNum).title = "/" + this.openFiles[tabNum-1].replace(/\//,"");
    },

    // Reset all tabs to be without a highlight and then highlight the selected
    redoTabHighlight: function(selectedTab) {
        var tColor, fileLink;

        for(var i=1;i<=this.savedPoints.length;i++) {
            if (get('tab'+i).childNodes[0]) {
                get('tab'+i).childNodes[0].childNodes[0].style.backgroundColor = this.savedPoints[i-1]!=this.getcMInstance(i).changeGeneration()
                    ? "#b00" : "transparent";
            }

            tColor = i==selectedTab ? this.tabFGselected : this.tabFGnormalTab;
            if ("undefined" != typeof this.openFiles[i-1] && this.openFiles[i-1] != "/[NEW]") {
                fileLink = this.filesFrame.contentWindow.document.getElementById(this.openFiles[i-1].replace(/\//g,"|"));
                if (fileLink) {
                    fileLink.style.backgroundColor = i==selectedTab ? this.tabBGcurrent : this.tabBGopen;
                    fileLink.style.color = i==selectedTab ? this.tabFGcurrent : this.tabFGopenFile;
                };
            }
            get('tab'+i).style.color = tColor;
            get('tab'+i).style.background = i==selectedTab ? this.tabBGcurrent : this.tabBGopen;
        }
    },

    // Close the tab upon request
    closeTab: function(closeTabNum, dontSetPV, dontAsk) {
        var cM, cMdiff, thisCM, okToRemove, closeFileName;

        // If we haven't specified, close current tab
        if (!closeTabNum) {closeTabNum = this.selectedTab};

        cM = this.getcMInstance();
        cMdiff = this.getcMdiffInstance();
        okToRemove = true;
        if (!dontAsk && this.savedPoints[closeTabNum-1]!=this.getcMInstance(closeTabNum).changeGeneration()) {
            okToRemove = this.ask(t['You have made...']);
        }

        if (okToRemove) {
            // Get the filename of tab we're closing
            closeFileName = this.openFiles[closeTabNum-1];

            // recursively copy over all tabs & data from the tab to the right, if there is one
            for (var i=closeTabNum;i<this.openFiles.length;i++) {
                get('tab'+i).innerHTML = get('tab'+(i+1)).innerHTML;
                get('tab'+i).title = get('tab'+(i+1)).title;
                this.openFiles[i-1] = this.openFiles[i];
                this.openFileMDTs[i-1] = this.openFileMDTs[i];
                this.openFileVersions[i-1] = this.openFileVersions[i];
            }
            // hide the instance we're closing by setting the hide class and removing from the array
            this.content.contentWindow['cM'+this.cMInstances[closeTabNum-1]].getWrapperElement().style.display = "none";
            this.content.contentWindow['cM'+this.cMInstances[closeTabNum-1]+'diff'].getWrapperElement().style.display = "none";
            this.cMInstances.splice(closeTabNum-1,1);
            // clear the rightmost tab (or only one left in a 1 tab scenario) & remove from the array
            get('tab'+this.openFiles.length).style.display = "none";
            get('tab'+this.openFiles.length).innerHTML = "";
            get('tab'+this.openFiles.length).title = "";
            this.openFiles.pop();
            this.openFileMDTs.pop();
            this.openFileVersions.pop();
            // If we're closing the selected tab, determin the new selectedTab number, reduced by 1 if we have some tabs, 0 for a reset state
            if (this.selectedTab==closeTabNum) {
                this.openFiles.length>0 ? this.selectedTab-=1 : this.selectedTab = 0;
            }
            if (this.openFiles.length>0 && this.selectedTab==0) {this.selectedTab=1};

            // grey out the view icon
            if (this.openFiles.length==0) {
                this.fMIconVis('fMView',0.3);
            } else {
                // Switch the mode & the tab
                this.switchMode();
                this.switchTab(this.selectedTab);
            }
            // Highlight the selected tab after splicing the change state out of the array
            this.savedPoints.splice(closeTabNum-1,1);
            this.savedContents.splice(closeTabNum-1,1);
            this.redoTabHighlight(this.selectedTab);

            // Remove any highlighting from the file manager
            this.selectDeselectFile('deselect',this.filesFrame.contentWindow.document.getElementById(closeFileName.replace(/\//g,"|")));

            if (!dontSetPV) {
                this.setPreviousFiles();
            }

            // Update the versions display
            this.updateVersionsDisplay();

            // Update the title tag to indicate any changes
            this.indicateChanges();
        }
        // Lastly, stop it from trying to also switch tab
        this.canSwitchTabs=false;
        // and set the widths
        this.setTabWidths('posOnlyNewTab');
        setTimeout(function(ic) {ic.canSwitchTabs=true;},100,this);
    },

    // Close all tabs
    closeAllTabs: function() {
        if (this.cMInstances.length>0 && this.ask(t['Close all tabs'])) {
            for (var i=this.cMInstances.length; i>0; i--) {
                this.closeTab(i, i>1? true:false);
            }
        }
        // Update the title tag to indicate any changes
        this.indicateChanges();
    },

    // Set the tabs width
    setTabWidths: function(posOnlyNewTab) {
        var availWidth, avgWidth, tabWidth, lastLeft, lastWidth;

        if (this.ready) {
            availWidth = parseInt(this.content.style.width,10)-53-22-10; // - left margin - new tab - right margin
            avgWidth = (availWidth/this.openFiles.length)-18;
            tabWidth = -18; // Incl 18px offset
            lastLeft = 53;
            lastWidth = 0;
            this.tabLeftPos = [];
            for (var i=0;i<this.openFiles.length;i++) {
                if (posOnlyNewTab) {i=this.openFiles.length};
                tabWidth = this.openFiles.length*(150+18) > availWidth ? parseInt(avgWidth*i,10) - parseInt(avgWidth*(i-1),10) : 150;
                lastLeft = i==0 ? 53 : parseInt(get('tab'+(i)).style.left,10);
                lastWidth = i==0 ? 0 : parseInt(get('tab'+(i)).style.width,10)+18;
                if (!posOnlyNewTab) {
                    get('tab'+(i+1)).style.left = (lastLeft+lastWidth) + "px";
                    get('tab'+(i+1)).style.width = tabWidth + "px";
                } else {
                    tabWidth = -18;
                }
                this.tabLeftPos.push(lastLeft+lastWidth);
            }
            get('newTab').style.left = (lastLeft+lastWidth+tabWidth+18) + "px";
        }
    },

    // Tab dragging start
    tabDragStart: function(tab) {
        this.draggingTab = tab;
        this.diffStartX = this.mouseX;
        this.tabDragMouseXStart = (this.mouseX - (parseInt(this.files.style.width,10)+53+18)) % 150;
        // Put tab we're dragging over others
        get('tab'+tab).style.zIndex = 2;
        // Set classes for other tabs (tabSlide) and the one we're dragging (tabDrag)
        for (var i=1; i<=this.openFiles.length; i++) {
            get('tab'+i).className = i!==tab
                ? "tab tabSlide"
                : "tab tabDrag";
        }
    },

    // Tab dragging
    tabDragMove: function() {
        var lastTabWidth, thisLeft, dragTabNo, tabWidth;

        lastTabWidth = parseInt(get('tab'+this.openFiles.length).style.width,10)+18;

        // Set the left position but stay within left side (53) and new tab
        this.thisLeft = thisLeft = this.tabDragMouseX >= 53
            ? this.tabDragMouseX <= parseInt(get('newTab').style.left,10) - lastTabWidth
                ? this.tabDragMouseX : (parseInt(get('newTab').style.left,10) - lastTabWidth) : 53;

        get('tab'+this.draggingTab).style.left = thisLeft + "px";

        this.dragTabNo = dragTabNo = this.draggingTab;

        // Set the opacities of tabs then positions of tabs we're not dragging
        for (var i=1; i<=this.openFiles.length; i++) {
            get('tab'+i).style.opacity = i == this.draggingTab ? 1 : 0.5;
            tabWidth = this.tabLeftPos[i] ? this.tabLeftPos[i] - this.tabLeftPos[i-1] : tabWidth;
            if (i!=this.draggingTab) {
                if (i < this.draggingTab) {
                    get('tab'+i).style.left = thisLeft <= this.tabLeftPos[i-1]
                        ? this.tabLeftPos[i-1]+tabWidth
                        : this.tabLeftPos[i-1];
                } else {
                    get('tab'+i).style.left = thisLeft >= this.tabLeftPos[i-1]
                        ? this.tabLeftPos[i-1]-tabWidth
                        : this.tabLeftPos[i-1];
                }
            }
        }
    },

    // Tab dragging end
    tabDragEnd: function() {
        var swapWith, tempArray;

        // Set the tab widths
        this.setTabWidths();
        // Determin what tabs we've swapped and reset classname, opacity & z-index for all
        for (var i=1; i<=this.openFiles.length; i++) {
            if (this.thisLeft >= this.tabLeftPos[i-1]) {
                swapWith = this.thisLeft == this.tabLeftPos[0] ? 1 : this.dragTabNo > i ? i+1 : i;
            }
            get('tab'+i).className = "tab";
            get('tab'+i).style.opacity = 1;
            if (i!=this.dragTabNo) {
                get('tab'+i).style.zIndex = 1;
            } else {
                setTimeout(function() {
                    get('tab'+i).style.zIndex = 1;
                },150);
            }
        }
        if (this.thisLeft && this.thisLeft!==false) {
            // Make a number ascending array
            tempArray = [];
            for (var i=1;i<=this.openFiles.length;i++) {
                tempArray.push(i);
            }
            // Then swap our tab numbers
            tempArray.splice(this.dragTabNo-1,1);
            tempArray.splice(swapWith-1,0,this.dragTabNo);
            // Now we have an order to sort against
            this.sortTabs(tempArray);
        }
        this.setTabWidths();
        this.draggingTab = false;
        this.thisLeft = false;
    },

    // Sort tabs into new order
    sortTabs: function(newOrder) {
        var a, b, savedPoints = [], savedContents = [], openFiles = [], openFileMDTs = [], openFileVersions = [], cMInstances = [], selectedTabWillBe;

        // Setup an array of our actual arrays and the blank ones
        a = [this.savedPoints, this.savedContents, this.openFiles, this.openFileMDTs, this.openFileVersions, this.cMInstances];
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
            if (this.selectedTab == newOrder[i]) {
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
        this.savedPoints = a[0];
        this.savedContents = a[1];
        this.openFiles = a[2];
        this.openFileMDTs = a[3];
        this.openFileVersions = a[4];
        this.cMInstances = a[5];
        this.setTabWidths();
        this.switchTab(selectedTabWillBe);
    },

    // Alphabetize tabs
    alphaTabs: function() {
        if (this.openFiles.length>0) {
            var currentArray, currentArrayFull, alphaArray, nextValue, nextPos;

            currentArray = [];
            currentArrayFull = [];
            alphaArray = [];
            // Get filenames, full paths and set classname for sliding
            for (var i=0;i<this.openFiles.length;i++) {
                currentArray.push(this.openFiles[i].slice(this.openFiles[i].lastIndexOf('/')+1));
                currentArrayFull.push(this.openFiles[i]);
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
                        nextValueFull  = this.openFiles[this.openFiles.indexOf(currentArrayFull[i])];
                        nextPos = i;
                    }
                }
                // When we've got it, push into alphaArray and splice out of arrays
                alphaArray.push((this.openFiles.indexOf(nextValueFull)+1));
                currentArray.splice(nextPos,1);
                currentArrayFull.splice(nextPos,1);
            }
            // Once done, sort our tabs into new order
            this.sortTabs(alphaArray);
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
        this.resetAutoLogoutTimer();

        // Detect if we type s,n,a,k,e keys with content saved, if so start snake game
        if (!this.last5Keys) {this.last5Keys = [];}
        this.last5Keys.push(key);
        if (this.last5Keys.length == 6) {
            this.last5Keys.shift();
        }
        if (this.last5Keys.join() == "83,78,65,75,69") {
            setTimeout(function(ic) {
                // Undo back to pre 'snake' word
                cM = ic.getcMInstance();
                var undoCounts = 0;
                var startCG = cM.changeGeneration();
                while (cM.changeGeneration() > startCG-5) {
                    cM.undo();
                    undoCounts++;
                }
                // If we have content saved
                if (ic.savedPoints[ic.selectedTab-1] == cM.changeGeneration()) {
                    // Start snake game
                    ic.startSnake();
                    // If we don't, redo snake word
                } else {
                    for (var i=1; i<=undoCounts; i++) {
                        cM.redo();
                    }
                }
            },0,this);
        }

        // Detect arrow keys if playing snake
        if (this.snakePlaying) {
            if (key==37) {this.snakeDir = 'left'}
            if (key==39) {this.snakeDir = 'right'}
            if (key==38) {this.snakeDir = 'up'}
            if (key==40) {this.snakeDir = 'down'}
            return false;
        }

        // Mac command key handling (224 = Moz, 91/93 = Webkit Left/Right Apple)
        if (key==224 || key==91 || key==93) {
            this.cmdKey = true;
        }

        // F1 (zoom code out non declaration lines)
        if (key === 112) {
            if (this.codeZoomedOut) {
                return;
            }
            this.codeZoomedOut = true;

            cM = this.getcMInstance();
            // For every line in the current editor, add code-zoomed-out class if not a function/class declaration line
            for (var i=0; i<cM.lineCount(); i++) {
                var nonDeclareLine = true;
                for (var j=0; j<this.functionClassList.length; j++) {
                    if (this.functionClassList[j].line == i) {
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
            this.deleteFiles();
            return false;
        };

        // Alt key down?
        if (evt.altKey) {
            // detect alt right
            var isAltRight	= (evt.ctrlKey||this.cmdKey) ? true:false;

            // tag wrapper, add line break at end or focus on file manager
            if (
                (this.tagWrapperCommand=="ctrl+alt" && isAltRight) // CTRL/Cmd + alt left + key || alt right + key
                || (this.tagWrapperCommand=="alt-left" && !isAltRight) // alt left + key
            ) {
                if (area=="content") {
                    if (key==68) {this.tagWrapper('div'); return false;}
                    else if (key==83) {this.tagWrapper('span'); return false;}
                    else if (key==80) {this.tagWrapper('p'); return false;}
                    else if (key==65) {this.tagWrapper('a'); return false;}
                    else if (key==49) {this.tagWrapper('h1'); return false;}
                    else if (key==50) {this.tagWrapper('h2'); return false;}
                    else if (key==51) {this.tagWrapper('h3'); return false;}
                    else if (key==13) {this.addLineBreakAtEnd(); return false;}
                    else if (key==37) {this.filesFrame.contentWindow.focus();return false;}
                    else {return key;}
                }
                // Focus on file manager (outside of content area) or last editor pane
                if (key==37) {this.filesFrame.contentWindow.focus();return false;}
                else if (key==39) {this.focus(this.editorFocusInstance.indexOf('diff') > -1 ? true : false);return false;}
                else {return key;}
                // Alt+Enter (Insert Line After)
            } else if (key==13) {
                this.insertLineAfter();
                return false;
            } else {return key;}

        } else {

            // Shift+Enter (Insert Line Before)
            if(key==13 && evt.shiftKey) {
                this.insertLineBefore();
                return false;

                // CTRL/Cmd+F (Find next)
                // and
                // CTRL/Cmd+G (Find previous)
            } else if((key==70||key==71) && (evt.ctrlKey||this.cmdKey)) {
                var find = get('find');
                cM = this.getcMInstance();
                cMdiff = this.getcMdiffInstance();
                thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
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
                    this.findReplace(document.getElementById('find').value,false,true,false,'findPrevious');
                }
                return false;

                // CTRL/Cmd+L (Go to line)
            } else if(key==76 && (evt.ctrlKey||this.cmdKey)) {
                var goToLineInput = get('goToLineNo');
                goToLineInput.select();
                // this is trick for Chrome - after you have used Ctrl-F once, when
                // you try using Ctrl-F another time, somewhy Chrome still thinks,
                // that find has focus and refuses to give it focus second time.
                get('find').focus();
                goToLineInput.focus();
                return false;

                // CTRL/Cmd+I (Get info)
            } else if(key==73 && (evt.ctrlKey||this.cmdKey) && area == "content") {
                this.searchForSelected();
                return false;

                // CTRL/Cmd+backspace arrow (Go to previous tab selected)
            } else if(key==8 && (evt.ctrlKey||this.cmdKey)) {
                if (this.prevTab !== 0) {
                    this.switchTab(this.prevTab);
                }
                return false;

                // CTRL/Cmd+right arrow (Tab to right)
            } else if(key==39 && (evt.ctrlKey||this.cmdKey) && area!="content") {
                this.nextTab();
                return false;

                // CTRL/Cmd+left arrow (Tab to left)
            } else if(key==37 && (evt.ctrlKey||this.cmdKey) && area!="content") {
                this.previousTab();
                return false;

                // CTRL/Cmd+up arrow (Move line up)
            } else if(key==38 && (evt.ctrlKey||this.cmdKey) && area=="content") {
                this.moveLines('up');
                return false;

                // CTRL/Cmd+down arrow (Move line down)
            } else if(key==40 && (evt.ctrlKey||this.cmdKey) && area=="content") {
                this.moveLines('down');
                return false;

                // CTRL/Cmd+numeric plus (New tab)
            } else if((key==107 || key==187) && (evt.ctrlKey||this.cmdKey)) {
                area=="content"
                    ? this.duplicateLines()
                    : this.newTab();
                return false;

                // CTRL/Cmd+numeric minus (Close tab)
            } else if((key==109 || key==189) && (evt.ctrlKey||this.cmdKey)) {
                area=="content"
                    ? this.removeLines()
                    : this.closeTab(this.selectedTab);
                return false;

                // CTRL/Cmd+S (Save), CTRL/Cmd+Shift+S (Save As)
            } else if(key==83 && (evt.ctrlKey||this.cmdKey)) {
                if(evt.shiftKey) {
                    this.saveFile('saveAs');
                } else {
                    this.saveFile();
                }
                return false;

                // CTRL/Cmd+Enter (Open Webpage)
            } else if(key==13 && (evt.ctrlKey||this.cmdKey) && this.openFiles[this.selectedTab-1] != "/[NEW]") {
                this.resetKeys(evt);
                window.open(this.openFiles[this.selectedTab-1]);
                return false;

                // Enter (Expand dir/open file)
            } else if(key==13 && area=="files") {
                if(!evt.ctrlKey && !this.cmdKey) {
                    if (this.selectedFiles.length == 0) {
                        this.overFileFolder('folder', '|');
                        this.selectFileFolder('init');
                    }
                    this.fmAction(evt,'enter');
                }
                return false;

                // Up/down/left/right arrows (Traverse files)
            } else if((key==38||key==40||key==37||key==39) && area=="files") {
                if(!evt.ctrlKey && !this.cmdKey) {
                    if (this.selectedFiles.length == 0) {
                        this.overFileFolder('folder', '|');
                        this.selectFileFolder('init');
                    }
                    this.fmAction(evt,
                        key==38 ?	'up' :
                            key==40 ?	'down' :
                                key==37 ?	'left' :
                                    'right');
                }
                return false;

                // CTRL/Cmd+O (Open Prompt)
            } else if(key==79 && (evt.ctrlKey||this.cmdKey)) {
                this.openPrompt();
                return false;

                // CTRL/Cmd+Space (Add snippet)
            } else if(key==32 && (evt.ctrlKey||this.cmdKey) && area=="content") {
                this.addSnippet();
                return false;

                // CTRL/Cmd+J (Jump to definition/back again)
            } else if(key==74 && (evt.ctrlKey||this.cmdKey) && area=="content") {
                this.jumpToDefinition();
                return false;

                // CTRL + Tab (lock/unlock file manager)
            } else if(key==223 && (evt.ctrlKey||this.cmdKey)) {
                this.lockUnlockNav();
                this.changeFilesW(this.lockedNav ? 'expand' : 'contract');
                return false;

                // CTRL + . (Fold/unfold current line)
            } else if(key==190 && (evt.ctrlKey||this.cmdKey)) {
                cM = this.getcMInstance();
                cMdiff = this.getcMdiffInstance();
                thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
                var line = thisCM.getCursor().line;

                return false;

                // ESC in content area (Comment/Uncomment line)
            } else if(key==27 && area == "content") {
                cM = this.getcMInstance();
                cMdiff = this.getcMdiffInstance();
                thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;

                if (thisCM.getSelections().length > 1) {
                    thisCM.execCommand("singleSelection");
                } else {
                    this.lineCommentToggle();
                }
                return false;

                // ESC not in content area (Cancel all actions)
            } else if(key==27 && area != "content") {
                this.cancelAllActions();
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

        if (key == 112 && this.codeZoomedOut) {
            cM = this.getcMInstance();
            // For every line in the current editor, remove code-zoomed-out class if not a function/class declaration line
            for (var i=0; i<cM.lineCount(); i++) {
                var nonDeclareLine = true;
                for (var j=0; j<this.functionClassList.length; j++) {
                    if (this.functionClassList[j].line == i) {
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
            this.goToLine(cursor.line + 1, cursor.ch, false);

            this.codeZoomedOut = false;
        }
        this.cmdKey = false;
    },

    handleModalKeyUp: function(evt, page) {
        key = evt.keyCode ? evt.keyCode : evt.which ? evt.which : evt.charCode;

        if ("settings" === page && 13 === key) {
            get(page + 'IFrame').contentWindow.submitSettings();
        }
        if (27 === key) {
            parent.ICEcoder.showHide('hide',parent.get('blackMask'));
        }
    },

    // Add snippet code completion
    addSnippet: function() {
        var cM, cMdiff, thisCM, lineNo, whiteSpace, content;

        // Get line content after trimming whitespace
        cM = this.getcMInstance();
        cMdiff = this.getcMdiffInstance();
        thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
        lineNo = thisCM.getCursor().line;
        whiteSpace = thisCM.getLine(lineNo).length - thisCM.getLine(lineNo).replace(/^\s\s*/, '').length;
        content = thisCM.getLine(lineNo).slice(whiteSpace);
        // function snippet
        if (content.slice(0,8)=="function") {
            this.doSnippet('function','function VAR() {\nINDENT\tCURSOR\nINDENT}');
            // if snippet
        } else if (content.slice(0,2)=="if") {
            this.doSnippet('if','if (CURSOR) {\nINDENT\t\nINDENT}');
            // for snippet
        } else if (content.slice(0,3)=="for") {
            this.doSnippet('for','for (var i=0; i<CURSOR; i++) {\nINDENT\t\nINDENT}');
        }
    },

    // Action a snippet
    doSnippet: function(tgtString,replaceString) {
        var cM, cMdiff, thisCM, lineNo, lineContents, remainder, strPos, replacedLine, whiteSpace, curPos, sPos, lineNoCount;

        // Get line contents
        cM = this.getcMInstance();
        cMdiff = this.getcMdiffInstance();
        thisCM = this.editorFocusInstance.indexOf('diff') > -1 ? cMdiff : cM;
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
            this.focus(this.editorFocusInstance.indexOf('diff') > -1 ? true : false);
        }
    },

    viewTutorial: function(step, delay) {
        var winW, winH;

        winW = window.innerWidth;
        winH = window.innerHeight;

        var steps = {
            0: {
                "width": 250,
                "height": 55,
                "top": -55,
                "left": 0,
                "title": "<img src=\"assets/images/icecoder.png\" style=\"position: absolute; margin: -105px 0 0 -55px\"><br><br>Code editor awesomeness ...in your browser",
                "message": "View the quick start tutorial? (Well worthwhile!) or <a onclick=\"ICEcoder.viewTutorial(12, 0)\" style=\"font-size: 14px; text-decoration: underline; cursor: pointer\">skip it</a>",
                "button": "view tutorial"
            },
            1: {
                "width": 250,
                "height": 55,
                "top": 0,
                "left": 0,
                "title": "Options and settings",
                "message": "Here you can perform file and editor content actions, plus also customise ICEcoders' settings, switch to other file manager sources, view help, search for info and more.",
                "button": "next &gt;"
            },
            2: {
                "width": 250,
                "height": winH - 85,
                "top": 50,
                "left": 0,
                "title": "File manager",
                "message": "This is the file manager. Click a dir to open/close, double click a file to open it and right click on dirs/files to get relevant options. You can drag and drop too.",
                "button": "next &gt;"
            },
            3: {
                "width": 45,
                "height": 85,
                "top": 50,
                "left": 205,
                "title": "File manager options and plugins",
                "message": "Here you can unlock/lock the file manager to collapse/expand it, refresh the file manager plus view and install plugins. (Also, move your mouse to left edge of file manager for quick access to the plugins).",
                "button": "next &gt;"
            },
            4: {
                "width": 250,
                "height": 35,
                "top": winH - 35,
                "left": 0,
                "title": "Extra tools",
                "message": "Get access to the terminal, output, database and Git interfaces here, displayed as an overlay to get the largest display, click option again to slide overlay out.",
                "button": "next &gt;"
            },
            5: {
                "width": winW - 250,
                "height": 42,
                "top": 0,
                "left": 250,
                "title": "Editor tabs",
                "message": "Your opened tabs will appear here. Icons displayed are to close all, alphabetize tabs and add new tab. You can drag your open tabs left/right to sort them too.",
                "button": "next &gt;"
            },
            6: {
                "width": 440,
                "height": 28,
                "top": 42,
                "left": 250,
                "title": "Find and replace builder",
                "message": "This is the find and replace builder. Here you can use the text fields and dropdown menus to build up sentences of what you'd like to do, such as find and replace in editor content, files and filenames.",
                "button": "next &gt;"
            },
            7: {
                "width": 200,
                "height": 28,
                "top": 42,
                "left": winW - 200,
                "title": "Editor options and bug reporting",
                "message": "Here you can specify the line to jump to (editor jumps as you type, hit Enter to focus on editor), plus options to view the current tab as a webpage in new browser window and view bugs as you code (once targeted at error logs).",
                "button": "next &gt;"
            },
            8: {
                "width": 520,
                "height": 380,
                "top": 70,
                "left": 250,
                "title": "System info",
                "message": "This is general info about your server, paths, browser and more. Worth noting to ensure settings seem correct.",
                "button": "next &gt;"
            },
            9: {
                "width": 32,
                "height": 30,
                "top": winH - 30,
                "left": 250,
                "title": "Editor content error indicator and version control",
                "message": "Error indicator for syntax or structural issues in the editor content. Also, when you have a tab open, on every save, it makes a copy - click the number of backups it indicates, to view differences and options to restore old versions.",
                "button": "next &gt;"
            },
            10: {
                "width": 100,
                "height": 30,
                "top": winH - 30,
                "left": (((winW + 250) / 2) - 50),
                "title": "Editor pane mode",
                "message": "Switch between single pane and diff pane modes. The diff pane automatically sets a copy on each save, so you can undo/redo to cycle through those. The gutters indicate additions, changes and deletions on each line.",
                "button": "next &gt;"
            },
            11: {
                "width": 100,
                "height": 30,
                "top": winH,
                "left": (((winW + 250) / 2) - 50),
                "title": "Let's get started!",
                "message": "This really only scratches the surface of what ICEcoder can do. Have a look around, test out the features and don't forget to checkout the website for more advanced tips & tricks. You can get to this tutorial again under Help > Tutorial.",
                "button": "start!"
            },
        };

        // Make both the info black mask and message display
        get("infoBlackMask").style.display = "block";
        get("infoMessageContainer").style.display = "block";

        // No step specified means starting from beginning
        if (false === step) {
            // Set margin-top to be above screen
            get("infoMessageContainer").style.marginTop = -300 + "px";
            // After 100ms show border and message text (still above screen)
            setTimeout(function() {
                get("infoBlackMask").style.border = "solid 10000px rgba(0,0,0,0.8)";
                get("infoMessageContainer").style.opacity = "1";
            }, 100);
            // After requested delay, slide in message but account for logo
            setTimeout(function() {
                get("infoMessageContainer").style.marginTop = (winH / 2) + 70 + "px";
            }, delay);
            // Set message text and return to go no further
            ICEcoder.viewTutorial(0);
            return;
        }

        // If we're going beyond the last step, we're finishing
        if (11 < step) {
            // Reset styles ready for next time
            get("infoBlackMask").style.border = "solid 10000px rgba(0,0,0,0)";
            get("infoMessageContainer").style.opacity = "0";
            setTimeout(function() {
                get("infoBlackMask").style.display = "none";
                get("infoMessageContainer").style.display = "none";
            }, 500);
            // Mark tutorial as done in users settings and return
            xhr = this.xhrObj();
            xhr.open("POST",iceLoc+"/lib/settings.php?action=turnOffTutorialOnLogin&csrf="+this.csrf,true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.send();
            return;
        }

        // Steps 1 onwards have no logo, normal margin-top needed
        if (1 <= step) {
            get("infoMessageContainer").style.marginTop = (winH / 2) + "px";
        }

        // We have something do display, so set info black mask to highlight item
        get("infoBlackMask").style.height = steps[step].height;
        get("infoBlackMask").style.width = steps[step].width;
        get("infoBlackMask").style.top = -10000 + steps[step].top + "px";
        get("infoBlackMask").style.left = -10000 + steps[step].left + "px";
        // Set info title, message and button
        get("infoMessage").innerHTML = '<div class="title">' + steps[step].title + '</div>' + steps[step].message + '<br><br><div class="button" onclick="ICEcoder.viewTutorial(' + (step + 1) + ')">' + steps[step].button + '</div>';
    },

    // Snart snake
    startSnake: function() {
        this.snakePlaying = true;
        this.showHide('show',get('blackMask'));
        get('mediaContainer').innerHTML = '<span style="font-size: 14px">Let\'s play<br><img src="'+iceLoc+'/assets/images/snake.png" alt="snake"><br><br><br>Use arrow keys to eat your code<br><br>(it returns afterwards of course) :-)</span>';
        setTimeout(function(ic) {
            ic.showHide('hide',get('blackMask'));
            get('mediaContainer').innerHTML = '';
            ic.playSnake();
        },2000,this);
    },

    // Play snake
    playSnake: function() {
        var cM;

        cM = this.getcMInstance();
        cM.setOption('readOnly', 'nocursor');
        cM.focus();

        // Get state of editor at present
        this.snakePreHistory = cM.getHistory();
        this.snakePreContent = cM.getValue();
        this.snakePreCursor = cM.getCursor();

        // Pick a random point for snake to come in and set head and 4 body parts off screen
        var randPos = Math.floor(Math.random()*50);
        this.snakePos = [
            [randPos,0],
            [randPos,-1],
            [randPos,-2],
            [randPos,-3],
            [randPos,-4]
        ];

        // Show game layer, set direction and do 1st frame of snake
        this.content.contentWindow.document.getElementById('game').style.display = 'block';
        this.snakeDir = "down";
        this.doSnake();

        // Every 0.1s, move snake
        this.snakeInt = setInterval(function(ic) {
            // Set new head X & Y pos according to direction
            var newHead = [];
            newHead[0] = ic.snakePos[0][0]+(ic.snakeDir == "right" ? 1 : ic.snakeDir == "left" ? -1 : 0);
            newHead[1] = ic.snakePos[0][1]+(ic.snakeDir == "down" ? 1 : ic.snakeDir == "up" ? -1 : 0);
            // Add new head and remove tail
            ic.snakePos.unshift(newHead);
            ic.snakePos.pop();
            // Do next frame of snake
            ic.doSnake();
        },100,this);
    },

    doSnake: function() {
        var cM, cW, cH, newInnerHTML, lineData, lineContent, spaceReplaceChars, collision, scrollInfo;

        // Get CodeMirror instance, plus char width and height
        cM = this.getcMInstance();
        cW = cM.defaultCharWidth();
        cH = cM.defaultTextHeight();

        // Clear content of game layer
        this.content.contentWindow.document.getElementById('game').innerHTML = "";
        // Start a new set of contents
        newInnerHTML = "";
        // For every part of snake, draw it's block in position
        for (var i=0; i<this.snakePos.length; i++) {
            newInnerHTML += '<div style="position: absolute; diplay: inline-block; width: '+cW+'px; height: '+cH+'px; top: '+((this.snakePos[i][1]*cH)+4)+'px; left: '+((this.snakePos[i][0]*cW)+60)+'px; background: #fff"></div>';
        }
        // Set new content in game layer
        this.content.contentWindow.document.getElementById('game').innerHTML = newInnerHTML;

        // Get line & ch value under snake head then line content
        lineData = cM.coordsChar({top: ((this.snakePos[0][1]*cH)+4), left: ((this.snakePos[0][0]*cW)+60)});
        lineContent = cM.getLine(lineData.line);

        // If not the last char on the line
        if (this.snakePos[0][0]-1 <= lineContent.length-2) {
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
            this.snakePos.push([this.snakePos[this.snakePos.length-1][0],this.snakePos[this.snakePos.length-1][1]]);
            // Replace char under head with nothing if end of line, else with our replacement string
            cM.doc.replaceRange(this.snakePos[0][0]-1 == lineContent.length-2 ? "" : spaceReplaceChars,lineData,{line: lineData.line, ch: lineData.ch+1});
            // Remove any trailing space at end
            if (this.snakePos[0][0]-1 == lineContent.length-2) {
                cM.doc.replaceRange(cM.getLine(lineData.line).replace(/[ \t]+$/,''),{line: lineData.line, ch: 0},{line: lineData.line, ch: 1000000});
            }
        } else {
            // Reduce snake length if over 5 chars and not on content
            if (this.snakePos.length >= 5) {
                this.snakePos.pop();
            }
        }
        // Detect if snake head has collided into itself
        collision = false;
        for (var i=1; i<this.snakePos.length; i++) {
            if (this.snakePos[i][0] == this.snakePos[0][0] && this.snakePos[i][1] == this.snakePos[0][1]) {
                collision = true;
            }
        }
        // Get scroll info to get width and height of editor area shown
        scrollInfo = cM.getScrollInfo();
        if (
            // If snake out of bounds or a collision, game over!
            this.snakePos[0][0] < 0 || this.snakePos[0][1] < 0 ||
            ((this.snakePos[0][0]*cW)+60) > scrollInfo.clientWidth || ((this.snakePos[0][1]*cH)+4) > scrollInfo.clientHeight ||
            collision
        ) {
            // Clear interval and hide game layer
            clearInterval(this.snakeInt);
            this.content.contentWindow.document.getElementById('game').style.display = 'none';
            // Set content, saved point, saved contents and history back to what they were pre game
            cM.setValue(this.snakePreContent);
            this.savedPoints[this.selectedTab-1] = cM.changeGeneration();
            this.savedContents[this.selectedTab-1] = this.snakePreContent;
            cM.setHistory(this.snakePreHistory);
            // Redo changes indicator in title tag and tab highlight save indicator also to what they are now (pre game state)
            this.indicateChanges();
            this.redoTabHighlight(this.selectedTab);
            // Set editor to be editable again
            cM.setOption('readOnly', false);
            // Set cursor back to what it was pre game and focus on editor
            cM.setCursor(this.snakePreCursor);
            cM.focus();
            // State we are no longer playing snake
            this.snakePlaying = false;
        }

    }
};
