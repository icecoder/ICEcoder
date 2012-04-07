// ICE coder by Matt Pass
// Free to use it for your own purposes, commercial or not, just let me know of any cool uses or customisations. :)
// No warranty or liability accepted for anything, all responsibility of use is your own.
// Latest version: https://github.com/mattpass/ICEcoder
// Twitter: @mattpass

var ICEcoder = {
	// Define settings
	filesW: 250,			// Initial width of the files pane
	minFilesW: 15,			// Min width of the files pane
	maxFilesW: 250,			// Max width of the files pane
	selectedTab: 0,			// The tab that's currently selected
	changedContent: [],		// Binary array to indicate which tabs have changed
	ctrlKeyDown: false,		// Indicates if CTRL keydown
	shiftKeyDown: false,		// Indicates if Shift keydown
	delKeyDown: false,		// Indicates if DEL keydown
	canSwitchTabs: true,		// Stops switching of tabs when trying to close
	openFiles: [],			// Array of open file URLs
	cMInstances: [],		// List of CodeMirror instance no's
	nextcMInstance: 1,		// Next available CodeMirror instance no
	selectedFiles: [],		// Array of selected files
	findMode: false,		// States if we're in find/replace mode
	lockedNav: true, 		// Nav is locked or not
	codeAssist: true,		// Assist user with their coding
	mouseDown: false,		// If the mouse is down or not
	draggingFilesW: false,		// If we're dragging the file manager width or not
	serverQueueItems: [],		// Array of URLs to call in order

	// Don't consider these tags as part of nesting as they're singles, JS or PHP code blocks
	tagNestExceptions: ["!DOCTYPE","meta","link","img","br","hr","input","script","?php","?"],

	// On load, set aliases, set the layout and get the nest location
	init: function() {
		var aliasArray = ["header","files","account","fmLock","filesFrame","editor","tabsBar","findBar","content","footer","nestValid","nestDisplay","charDisplay"];

		// Create our ID aliases
		for (var i=0;i<aliasArray.length;i++) {
			ICEcoder[aliasArray[i]] = top.document.getElementById(aliasArray[i]);
		}

		// Set layout & the nest location
		ICEcoder.setLayout();

		// Hide the loading screen
		top.document.getElementById('loadingMask').style.visibility = "hidden";
	},

	// Set our layout according to the browser size
	setLayout: function(dontSetEditor) {
		var winW, winH, headerH, footerH, accountH, tabsBarH, findBarH, cMCSS;

		// Determin width & height available
		window.innerWidth  ? winW = window.innerWidth  : winW = document.body.clientWidth;
		window.innerHeight ? winH = window.innerHeight : winH = document.body.clientHeight;

		// Apply sizes to various elements of the page
		headerH = 40, footerH = 30, accountH = 50, tabsBarH = 21, findBarH = 28;
		header.style.width = tabsBar.style.width = findBar.style.width = winW + "px";
		files.style.width = editor.style.left = this.filesW + "px";
		account.style.height = accountH + "px";
		fmLock.style.marginLeft = (this.filesW-27) + "px";
		filesFrame.style.height = (winH-headerH-accountH-footerH) + "px";

		// If we need to set the editor sizes
		if (!dontSetEditor) {
			editor.style.width = ICEcoder.content.style.width = (winW-this.filesW) + "px";
			ICEcoder.content.style.height = (winH-headerH-footerH-tabsBarH-findBarH) + "px";

			// Resize the CodeMirror instances to match the window size
			document.all ? strCSS = 'rules' : strCSS = 'cssRules';
			cMCSS = ICEcoder.content.contentWindow.document;
			cMCSS.styleSheets[2][strCSS][1].style['width'] = ICEcoder.content.style.width;
			cMCSS.styleSheets[2][strCSS][1].style['height'] = ICEcoder.content.style.height;
			cMCSS.styleSheets[2][strCSS][2].style['width'] = ICEcoder.content.style.width;
		}
	},

	// Clean up our loaded code
	contentCleanUp: function() {
		var fileName, cM, content;

		// If it's not a JS or CSS file, replace & and our temp </textarea> value
		fileName = ICEcoder.openFiles[ICEcoder.selectedTab-1];
		if (fileName.indexOf(".js")<0&&fileName.indexOf(".css")<0) {
			cM = ICEcoder.getcMInstance();
			content = cM.getValue();
			if (top.ICEcoder.codeAssist) {content = content.replace(/ & /g,' &amp; ');};
			content = content.replace(/<ICEcoder:\/:textarea>/g,'</textarea>');

			// Then set the content in the editor & clear the history
			cM.setValue(content);
			cM.clearHistory();
		}
	},

	// Work out the nesting depth location on demand and update our display if required
	getNestLocation: function(updateNestDisplay) {
		var cM, openTag, nestCheck, startPos, tagStart, canDoTheEndTag, tagEnd, tagEndJS, fileName;

		cM = ICEcoder.getcMInstance();
		nestCheck = cM.getValue();

		// Set up array to store nest data, a var to establish if a tag is open and another to establish if we're in a code block
		ICEcoder.htmlTagArray = [], openTag = false, ICEcoder.codeBlock = false;

		// For every character from the start to our caret position
		for(var i=0;i<=ICEcoder.caretPos;i++) {

			// If we find a < tag and we're not within a tag, change the open tag state & set our start position
			if(nestCheck.charAt(i)=="<" && openTag==false) {
				openTag=true;
				startPos=i+1;

				// Get the tag name and if it's the start of a code block, set the var for that
				tagStart=nestCheck.substr(startPos,nestCheck.length).split(" ")[0].split(">")[0].split("\n")[0];
				if (tagStart=="script"||tagStart=="?php"||tagStart=="?") {ICEcoder.codeBlock=true}
				if (tagStart!="") {ICEcoder.tagStart = tagStart}
			};

			// If we find a > tag and we're within a tag or codeblock
			if(nestCheck.charAt(i)==">" && (openTag||ICEcoder.codeBlock)) {

				// Get the tag name
				tagString=nestCheck.substr(0,i);
				tagString=tagString.substr(tagString.lastIndexOf('<')+1,tagString.length);
				tagString=tagString.split(" ")[0];
				ICEcoder.tagString = tagString;
				canDoTheEndTag=true;

				// Check it's not on our list of exceptions
				for (var j=0;j<ICEcoder.tagNestExceptions.length;j++) {
					if (tagString==ICEcoder.tagNestExceptions[j]) {
					canDoTheEndTag=false;
					}
				}

				if (canDoTheEndTag) {
					// Get this end tag name
					tagEnd=nestCheck.substr(0,i);
					tagEndJS=tagEnd.substr(tagEnd.lastIndexOf('<'),tagEnd.length);
					tagEnd=tagEnd.substr(tagEnd.lastIndexOf('<')+1,tagEnd.length);
					tagEnd=tagEnd.substr(tagEnd.lastIndexOf(' ')+1,tagEnd.length);
					tagEnd=tagEnd.substr(tagEnd.lastIndexOf('\t')+1,tagEnd.length);
					tagEnd=tagEnd.substr(tagEnd.lastIndexOf('\n')+1,tagEnd.length);
					tagEnd=tagEnd.substr(tagEnd.lastIndexOf(';')+1,tagEnd.length);

					if (!ICEcoder.codeBlock) {
						// OK, we can do something further as we're not in a code block
						// If it's the same as the previously logged tag preceeded by /, it's the equivalent end tag
						if (tagEnd=="/"+ICEcoder.htmlTagArray[ICEcoder.htmlTagArray.length-1]) {
							// So remove the last logged tag, thereby going up one in the nest
							ICEcoder.htmlTagArray.pop();
						} else {
							// Otherwise it's a different tag, add it to the end
							ICEcoder.htmlTagArray.push(tagString);
						}
					} else if (
						((ICEcoder.tagStart=="script"||ICEcoder.tagStart=="/script")&&tagEndJS=="</script")||
						((ICEcoder.tagStart=="?php"||ICEcoder.tagStart=="?")&&tagEnd=="?")) {
						ICEcoder.codeBlock=false;
					}
				}

				// Reset our open tag state ready for next time
				openTag=false;
			}	
		}

		// Now we've built up our nest depth array, if we're due to show it in the display
		if (updateNestDisplay && !top.ICEcoder.dontUpdateNest) {

			// Clear the display
			ICEcoder.nestDisplay.innerHTML = "";
			if ("undefined" != typeof ICEcoder.openFiles[ICEcoder.selectedTab-1]) {
				fileName = ICEcoder.openFiles[ICEcoder.selectedTab-1];
				if (fileName.indexOf(".js")<0&&fileName.indexOf(".css")<0) {

					// Then for all the array items, output as the nest display
					for (var i=0;i<ICEcoder.htmlTagArray.length;i++) {
						ICEcoder.nestDisplay.innerHTML += '<a onMouseover="top.ICEcoder.highlightBlock('+i+')" onMouseout="top.ICEcoder.highlightBlock('+i+',\'hide\')" onClick="top.ICEcoder.setPosition('+i+',top.ICEcoder.startPosLine,\''+ICEcoder.htmlTagArray[i]+'\')" style="cursor: pointer">'+ICEcoder.htmlTagArray[i]+'</a>';
						if(i<ICEcoder.htmlTagArray.length-1) {ICEcoder.nestDisplay.innerHTML += " &gt; "};
					}
				}
			}
		}
	},

	// Detect keys/combos plus identify our area and set the vars, perform actions
	interceptKeys: function(area, evt) {
		var key;

		key = evt.keyCode ? evt.keyCode : evt.which ? evt.which : evt.charCode;

		// DEL (Delete file)
		if (key==46 && area == "files") {
			top.ICEcoder.delKeyDown = true;
			top.ICEcoder.deleteFile();
	        	return false;

		// CTRL key down
       		} else if(key==17) {
			top.ICEcoder.ctrlKeyDown = true;
	        	return false;

		// Shift key down
       		} else if(key==16) {
			top.ICEcoder.shiftKeyDown = true;
	        	return false;

		// CTRL+F (Find)
		} else if(key==70 && top.ICEcoder.ctrlKeyDown==true) {
			top.document.getElementById('find').focus();
			top.ICEcoder.ctrlKeyDown = false;
	        	return false;

		// CTRL+G (Go to line)
		} else if(key==71 && top.ICEcoder.ctrlKeyDown==true) {
			top.document.getElementById('goToLineNo').focus();
			top.ICEcoder.ctrlKeyDown = false;
	        	return false;

		// CTRL+I (Get info)
		} else if(key==73 && top.ICEcoder.ctrlKeyDown==true && area == "content") {
			var searchPrefix = "";
			if (top.ICEcoder.caretLocType!="Content") {
				searchPrefix = top.ICEcoder.caretLocType.toLowerCase()+" ";
			}
			window.open("http://www.google.com/#output=search&q="+searchPrefix+top.ICEcoder.getcMInstance().getSelection());
			top.ICEcoder.ctrlKeyDown = false;
	        	return false;

		// CTRL+S (Save), CTRL+Shift+S (Save As)
		} else if(key==83 && top.ICEcoder.ctrlKeyDown==true) {
			if(top.ICEcoder.shiftKeyDown==true) {
				top.ICEcoder.saveFile('saveAs');
				top.ICEcoder.shiftKeyDown = false;
			} else {
				top.ICEcoder.saveFile();
			}
			top.ICEcoder.ctrlKeyDown = false;
	        	return false;

		// CTRL+Enter (Open Webpage)
		} else if(key==13 && top.ICEcoder.ctrlKeyDown==true && top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1] != "/[NEW]") {
			window.open(top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1]);
			top.ICEcoder.ctrlKeyDown = false;
	        	return false;

		// ESC in content area (Comment/Uncomment line)
       		} else if(key==27 && area == "content") {
			top.ICEcoder.lineCommentToggle();
	        	return false;

		// ESC not in content area (Cancel all actions)
       		} else if(key==27 && area != "content") {
			top.ICEcoder.cancelAllActions();
	        	return false;

		// Any other key
		} else {
	        	return key;
        	}
	},

	// Reset the state of keys back to the normal state
	resetKeys: function(evt) {
		var key;

		key = evt.keyCode ? evt.keyCode : evt.which ? evt.which : evt.charCode;

		if (key==17) {top.ICEcoder.ctrlKeyDown = false;}
		if (key==16) {top.ICEcoder.shiftKeyDown = false;}
		if (key==46) {top.ICEcoder.delKeyDown = false;}
	},

	// Set the width of the file manager on demand
	changeFilesW: function(expandContract) {

		if (!ICEcoder.lockedNav) {
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
		if (top.document.body.style.cursor == "w-resize") {
			// If our mouse is down and we're within a 250-400px range
			if (top.ICEcoder.mouseDown) {
				if (top.ICEcoder.mouseX >=250 && top.ICEcoder.mouseX <= 400) {
					top.ICEcoder.filesW = top.ICEcoder.maxFilesW = top.ICEcoder.mouseX;
				} else if (top.ICEcoder.mouseX <250) {
					top.ICEcoder.filesW = top.ICEcoder.maxFilesW = 250;
				} else {
					top.ICEcoder.filesW = top.ICEcoder.maxFilesW = 400;
				}
				// Set various widths based on the new width
				top.ICEcoder.files.style.width = top.ICEcoder.account.style.width = top.ICEcoder.filesFrame.style.width = top.ICEcoder.filesW + "px";
				top.ICEcoder.setLayout();
				top.ICEcoder.draggingFilesW = true;
			}
		} else {
			top.ICEcoder.draggingFilesW = false;
		}
	},

	// Change tabs by switching visibility of instances
	switchTab: function(newTab) {
		var cM;

		// Identify tab that's currently selected & get the instance
		ICEcoder.selectedTab = newTab;
		cM = ICEcoder.getcMInstance();

		// Switch mode to HTML, PHP, CSS etc
		ICEcoder.switchMode();

		// Set all cM instances to be hidden, then make our selected instance visable
		for (var i=0;i<ICEcoder.cMInstances.length;i++) {
			ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[i]].setOption('theme','icecoder hidden');
		}
		cM.setOption('theme','icecoder visible');

		// Focus on & refresh our selected instance
		cM.focus();
		cM.refresh();

		// Highlight the selected tab
		ICEcoder.redoTabHighlight(ICEcoder.selectedTab);
	},

	// Reset all tabs to be without a highlight and then highlight the selected
	redoTabHighlight: function(selectedTab) {
		var bgVPos;

		for(var i=1;i<=ICEcoder.changedContent.length;i++) {
			ICEcoder.changedContent[i-1]==1 ? bgVPos = -44 : bgVPos = 0;
			i==selectedTab ? ICEcoder.changedContent[selectedTab-1]==1 ? bgVPos = -33 : bgVPos = -22 : bgVPos = bgVPos;
			document.getElementById('tab'+i).style.backgroundPosition = "0px "+bgVPos+"px";
		}
		ICEcoder.changedContent[selectedTab-1]==1 ? top.ICEcoder.fMIconVis('fMSave',1) : top.ICEcoder.fMIconVis('fMSave',0.3);
	},

	// Starts a new file by setting a few vars & creating a new cM instance
	newTab: function() {
		var cM;

		ICEcoder.cMInstances.push(ICEcoder.nextcMInstance);
		ICEcoder.selectedTab = ICEcoder.cMInstances.length;
		ICEcoder.content.contentWindow.createNewCMInstance(ICEcoder.nextcMInstance);

		ICEcoder.thisFileFolderType='file';
		ICEcoder.thisFileFolderLink=shortURLStarts+'/[NEW]';
		ICEcoder.openFile();

		cM = ICEcoder.getcMInstance('new');
		ICEcoder.switchTab(ICEcoder.openFiles.length);
		ICEcoder.content.style.visibility='visible';

		cM.setLineClass(ICEcoder['cMActiveLine'+ICEcoder.selectedTab], null);
		ICEcoder['cMActiveLine'+ICEcoder.selectedTab] = cM.setLineClass(0, "cm-s-activeLine");
		ICEcoder.nextcMInstance++;
	},

	// Create a new tab for a file
	createNewTab: function() {
		var closeTabLink;

		// Push new file into array
		top.ICEcoder.openFiles.push(top.ICEcoder.shortURL);

		// Setup a new tab
		closeTabLink = '<a nohref onClick="parent.ICEcoder.closeTab('+(top.ICEcoder.openFiles.length)+')"><img src="images/nav-close.gif"></a>';
		top.document.getElementById('tab'+(top.ICEcoder.openFiles.length)).style.display = "inline-block";
		top.document.getElementById('tab'+(top.ICEcoder.openFiles.length)).innerHTML = top.ICEcoder.openFiles[top.ICEcoder.openFiles.length-1] + " " + closeTabLink;

		// Highlight it and state it's selected
		top.ICEcoder.redoTabHighlight(top.ICEcoder.openFiles.length);
		top.ICEcoder.selectedTab=top.ICEcoder.openFiles.length;

		// Add a new value ready to indicate if this content has been changed
		top.ICEcoder.changedContent.push(0);
	},

	// Create a new tab for a file
	renameTab: function(tabNum,newName) {
		var closeTabLink;

		// Push new file into array
		top.ICEcoder.openFiles[tabNum] = newName;

		// Setup a new tab
		closeTabLink = '<a nohref onClick="parent.ICEcoder.closeTab('+tabNum+')"><img src="images/nav-close.gif"></a>';
		top.document.getElementById('tab'+tabNum).innerHTML = top.ICEcoder.openFiles[tabNum] + " " + closeTabLink;
	},

	// Indicate if the nesting structure of the code is OK
	updateNestingIndicator: function () {
		var cM, fileName;

		cM = ICEcoder.getcMInstance();
		fileName = ICEcoder.openFiles[ICEcoder.selectedTab-1];
		ICEcoder.caretPos=cM.getValue().length;
		ICEcoder.getNestLocation();
		// Nesting is OK if at the end of the file we have no nests left, or it's a JS or CSS file
		if (ICEcoder.htmlTagArray.length==0||fileName.indexOf(".js")>0||fileName.indexOf(".css")>0) {
			ICEcoder.nestValid.style.backgroundColor="#00bb00";
			ICEcoder.nestValid.innerHTML = "Nesting OK";
		} else {
			ICEcoder.nestValid.style.backgroundColor="#ff0000";
			ICEcoder.nestValid.innerHTML = "Nesting Broken";
		}
	},

	// Get the caret position on demand
	getCaretPosition: function() {
		var cM, content, line, char, charPos, charCount;

		cM = ICEcoder.getcMInstance();
		content = cM.getValue();
		line = cM.getCursor().line;
		char = cM.getCursor().ch;
		charPos = 0;
		for (var i=0;i<line;i++) {
			charCount = content.indexOf("\n",charPos);
			charPos=charCount+1;
		}
		ICEcoder.caretPos=(charPos+char-1);
		ICEcoder.getNestLocation('yes');
	},

	// Update the code type, line & character display
	updateCharDisplay: function() {
		var cM;

		cM = ICEcoder.getcMInstance();
		ICEcoder.caretLocationType();
		ICEcoder.charDisplay.innerHTML = ICEcoder.caretLocType + ", Line: " + (cM.getCursor().line+1) + ", Char: " + cM.getCursor().ch;
	},

	// Determine which area of the document we're in
	caretLocationType: function () {
		var cM, caretLocType, caretChunk, fileName;

		cM = ICEcoder.getcMInstance();
		caretLocType = "Unknown";
		caretChunk = cM.getValue().substr(0,ICEcoder.caretPos+1);
		if (caretChunk.lastIndexOf("<script")>caretChunk.lastIndexOf("</script>")&&caretLocType=="Unknown") {caretLocType = "JavaScript"};
		if (caretChunk.lastIndexOf("<?")>caretChunk.lastIndexOf("?>")&&caretLocType=="Unknown") {caretLocType = "PHP"};
		if (caretChunk.lastIndexOf("<")>caretChunk.lastIndexOf(">")&&caretLocType=="Unknown") {caretLocType = "HTML"};
		if (caretLocType=="Unknown") {caretLocType = "Content"};

		fileName = ICEcoder.openFiles[ICEcoder.selectedTab-1];
		if (fileName.indexOf(".js")>0) {caretLocType="JavaScript"};
		if (fileName.indexOf(".css")>0) {caretLocType="CSS"};

		ICEcoder.caretLocType = caretLocType;

		// If we're in a JS or PHP code block, add that to the nest display
		if (caretLocType=="JavaScript"||caretLocType=="PHP") {
			ICEcoder.nestDisplay.innerHTML += " &gt; " + caretLocType;
		}
	},

	// Alter array indicating which files have changed
	redoChangedContent: function(evt) {
		var key;

		key = evt.keyCode ? evt.keyCode : evt.which ? evt.which : evt.charCode;
		// Exclude a few keys such as Escape...
		if (top.ICEcoder.ctrlKeyDown==false && key!=27 && key!=20 && (key<16||key>19) && (key<37||key>40) && (key!=144||key!=145) && (key!=44||key!=45) && (key<33||key>36) && (key!=91||key!=92) && (key<112||key>123)) {
			ICEcoder.changedContent[ICEcoder.selectedTab-1] = 1;
			ICEcoder.redoTabHighlight(ICEcoder.selectedTab);
		}
	},

	// Close the tab upon request
	closeTab: function(closeTabNum) {
		var cM, okToRemove;

		cM = ICEcoder.getcMInstance();
		okToRemove = true;
		if (ICEcoder.changedContent[closeTabNum-1]==1) {
			okToRemove = confirm('You have made changes.\n\nAre you sure you want to close without saving?');
		}

		if (okToRemove) {
			// recursively copy over all tabs & data from the tab to the right, if there is one
			for (var i=closeTabNum;i<ICEcoder.openFiles.length;i++) {
				top.document.getElementById('tab'+i).innerHTML = top.document.getElementById('tab'+(i+1)).innerHTML;
				ICEcoder.openFiles[i-1] = ICEcoder.openFiles[i];

				// reduce the tab reference number on the closeTab link by 1
				top.document.getElementById('tab'+i).innerHTML = top.document.getElementById('tab'+i).innerHTML.replace(("closeTab("+(i+1)+")"),"closeTab("+i+")");
			}

			// hide the instance we're closing by setting the hide class, clear the value & remove from the array
			ICEcoder.content.contentWindow['cM'+top.ICEcoder.cMInstances[closeTabNum-1]].setOption('theme','icecoder hidden');
			ICEcoder.content.contentWindow['cM'+top.ICEcoder.cMInstances[closeTabNum-1]].setValue('');
			top.ICEcoder.cMInstances.splice(closeTabNum-1,1);

			// clear the rightmost tab (or only one left in a 1 tab scenario) & remove from the array
			top.document.getElementById('tab'+ICEcoder.openFiles.length).style.display = "none";
			top.document.getElementById('tab'+ICEcoder.openFiles.length).innerHTML = "";
			ICEcoder.openFiles.pop();

			// Determin the new selectedTab number, reduced by 1 if we have some tabs, 0 for a reset state
			ICEcoder.openFiles.length>0 ? ICEcoder.selectedTab-=1 : ICEcoder.selectedTab = 0;
			if (ICEcoder.openFiles.length>0 && ICEcoder.selectedTab==0) {ICEcoder.selectedTab=1};

			// hide the content area if we have no tabs open
			if (ICEcoder.openFiles.length==0) {
				top.document.getElementById('content').style.visibility = "hidden";
				top.ICEcoder.fMIconVis('fMView',0.3);
			} else {
				// Switch the mode & the tab
				ICEcoder.switchMode();
				ICEcoder.switchTab(ICEcoder.selectedTab);
			}

			// Highlight the selected tab after splicing the change state out of the array
			top.ICEcoder.changedContent.splice(closeTabNum-1,1);
			top.parent.ICEcoder.redoTabHighlight(ICEcoder.selectedTab);
		}
		// Lastly, stop it from trying to also switch tab
		top.ICEcoder.canSwitchTabs=false;
	},

	// Setup the file manager
	fileManager: function() {
		ICEcoder.filesFrame = top.document.getElementById('filesFrame');
		if (!ICEcoder.filesFrame.contentWindow.document.getElementsByTagName) {return;};
	
		var aMenus = ICEcoder.filesFrame.contentWindow.document.getElementsByTagName("LI");
		for (var i=0; i<aMenus.length; i++) {
			var mclass = aMenus[i].className;
			if (mclass.indexOf("pft-directory") > -1) {
				var submenu=aMenus[i].childNodes;
				for (var j=0; j<submenu.length; j++) {
					if (submenu[j].tagName == "A") {
						submenu[j].onclick = function() {
						var node = this.nextSibling;			
							while (1) {
								if (node != null) {
									if (node.tagName == "UL") {
										var d = (node.style.display == "none")
										node.style.display = (d) ? "block" : "none";
										this.className = (d) ? "open" : "closed";
										return false;
									}
									node = node.nextSibling;
								} else {
									return false;
								}
							}
							return false;
						}
						submenu[j].className = (mclass.indexOf("open") > -1) ? "open" : "closed";
					}
					if (submenu[j].tagName == "UL") {
						submenu[j].style.display = (mclass.indexOf("open") > -1) ? "block" : "none";
					}
				}
			}
		}
		return false;
	},

	// Note which files or foldets we are over on mouseover/mouseout
	overFileFolder: function(type, link) {
		ICEcoder.thisFileFolderType=type;
		ICEcoder.thisFileFolderLink=link;
	},

	// Select file or folder on demand
	selectFileFolder: function() {
		var resetFile, shortURL, foundSelectedFile, foundShortURL, foundFile;

		// If we've clicked somewhere other than a file/folder
		if (top.ICEcoder.thisFileFolderLink=="") {
			if (!top.ICEcoder.ctrlKeyDown) {
				// Deselect all files
				for (var i=0;i<=top.ICEcoder.selectedFiles.length;i++) {
					if (top.ICEcoder.selectedFiles[i]) {
						resetFile = top.ICEcoder.filesFrame.contentWindow.document.getElementById(top.ICEcoder.selectedFiles[i]);
						ICEcoder.selectDeselectFile('deselect',resetFile);
					}
				}
				// Set our array to contain 0 items
				top.ICEcoder.selectedFiles.length = 0;
			}
		} else if (top.ICEcoder.thisFileFolderLink) {
			// We clicked a file/folder. Work out a shortened URL for the file, with pipes instead of slashes
			shortURL = top.ICEcoder.thisFileFolderLink.substr((top.ICEcoder.thisFileFolderLink.indexOf(shortURLStarts)+top.shortURLStarts.length),top.ICEcoder.thisFileFolderLink.length).replace(/\//g,"|");
		
			// If we have the CTRL key down
			if (top.ICEcoder.ctrlKeyDown) {
				foundSelectedFile=false;
				// Deselect previously selected file?
				for (i=0;i<=top.ICEcoder.selectedFiles.length;i++) {
					if (top.ICEcoder.selectedFiles[i]==shortURL) {
						resetFile = ICEcoder.filesFrame.contentWindow.document.getElementById(top.ICEcoder.selectedFiles[i]);
						ICEcoder.selectDeselectFile('deselect',resetFile);
						top.ICEcoder.selectedFiles.splice(i);
						foundSelectedFile=true;
					}
				}
				if (!foundSelectedFile) {
					foundFile = ICEcoder.filesFrame.contentWindow.document.getElementById(shortURL);
					ICEcoder.selectDeselectFile('select',foundFile);
					top.ICEcoder.selectedFiles.push(shortURL);
				}
			// We are single clicking
			} else {
				// First deselect all files
				for (i=0;i<top.ICEcoder.selectedFiles.length;i++) {
					resetFile = ICEcoder.filesFrame.contentWindow.document.getElementById(top.ICEcoder.selectedFiles[i]);
					ICEcoder.selectDeselectFile('deselect',resetFile);
				}
				// Set our arrray to contain 0 items
				top.ICEcoder.selectedFiles.length = 0;

				// Add our URL and highlight the file
				top.ICEcoder.selectedFiles.push(shortURL);
				foundFile = ICEcoder.filesFrame.contentWindow.document.getElementById(shortURL);
				ICEcoder.selectDeselectFile('select',foundFile);
			}
		}
		// Adjust the file & replace select values depending on if we have files selected
		if (!top.ICEcoder.selectedFiles[0]) {
			document.findAndReplace.target[2].innerHTML = "all files";
			document.findAndReplace.target[3].innerHTML = "all filenames";
		} else {
			document.findAndReplace.target[2].innerHTML = "selected files";
			document.findAndReplace.target[3].innerHTML = "selected filenames";
		}

		// Finally, show or grey out the relevant file manager icons
		top.ICEcoder.selectedFiles.length == 1 ? top.ICEcoder.fMIconVis('fMOpen',1) : top.ICEcoder.fMIconVis('fMOpen',0.3);
		top.ICEcoder.selectedFiles.length == 1 && top.ICEcoder.thisFileFolderType == "folder" ? top.ICEcoder.fMIconVis('fMNewFile',1) : top.ICEcoder.fMIconVis('fMNewFile',0.3);
		top.ICEcoder.selectedFiles.length == 1 && top.ICEcoder.thisFileFolderType == "folder" ? top.ICEcoder.fMIconVis('fMNewFolder',1) : top.ICEcoder.fMIconVis('fMNewFolder',0.3);
		top.ICEcoder.selectedFiles.length == 1 ? top.ICEcoder.fMIconVis('fMDelete',1) : top.ICEcoder.fMIconVis('fMDelete',0.3);
		top.ICEcoder.selectedFiles.length == 1 ? top.ICEcoder.fMIconVis('fMRename',1) : top.ICEcoder.fMIconVis('fMRename',0.3);
	},

	// Select or deselect file
	selectDeselectFile: function(action,file) {
		action == "select" ? file.style.backgroundColor="#888888" : file.style.backgroundColor="#dddddd";
		action == "select" ? file.style.color="#f8f8f8" : file.style.color="#000000";
	},

	// Create a new file (start & instant save)
	newFile: function() {
		top.ICEcoder.newTab();
		top.ICEcoder.saveFile();
	},

	// Create a new folder
	newFolder: function() {
		var newFolder, shortURL;

		shortURL = top.ICEcoder.rightClickedFile.substr((top.ICEcoder.rightClickedFile.indexOf(shortURLStarts)+top.shortURLStarts.length),top.ICEcoder.rightClickedFile.length).replace(/\|/g,"/");
		newFolder = prompt('Enter New Folder Name at '+shortURL+'/','');
		if (newFolder) {
			newFolder = shortURL + "/" + newFolder;
			top.ICEcoder.serverQueue("add","lib/file-control.php?action=newFolder&file="+newFolder.replace(/\//g,"|"));
			top.ICEcoder.serverMessage('<b>Creating Folder</b><br>'+newFolder);
		}
	},

	// Open a file on demand
	openFile: function() {
		if (top.ICEcoder.thisFileFolderLink!="" && top.ICEcoder.thisFileFolderType=="file") {
			var shortURL, canOpenFile;

			// work out a shortened URL for the file
			shortURL = top.ICEcoder.thisFileFolderLink.replace(/\|/g,"/");
			shortURL = shortURL.substr((shortURL.indexOf(shortURLStarts)+shortURLStarts.length),shortURL.length);

			// No reason why we can't open a file (so far)
			canOpenFile = true;

			// Limit to 10 files open at a time
			if (top.ICEcoder.openFiles.length<10) {
				// check if we've already got it in our array
				for (var i=0;i<top.ICEcoder.openFiles.length;i++) {
					if (top.ICEcoder.openFiles[i]==shortURL && shortURL!="/[NEW]") {
						// we have, so don't bother opening again
						canOpenFile = false;
						// instead, switch to that tab
						top.ICEcoder.switchTab(i+1);
					}
				}
			} else {
			// show a message because we have 10 files open
				alert('Sorry, you can only have 10 files open at a time!');
				canOpenFile = false;
			}

			// if we're still OK to open it...
			if (canOpenFile) {
				top.ICEcoder.shortURL = shortURL;
				if (shortURL!="/[NEW]") {
					// replace forward slashes with pipes so it get be placed in a querystring
					top.ICEcoder.thisFileFolderLink = top.ICEcoder.thisFileFolderLink.replace(/\//g,"|");
					top.ICEcoder.serverQueue("add","lib/file-control.php?action=load&file="+top.ICEcoder.thisFileFolderLink);
					top.ICEcoder.serverMessage('<b>Opening File</b><br>'+top.ICEcoder.shortURL);
				} else {
					top.ICEcoder.createNewTab();
				}
				top.ICEcoder.fMIconVis('fMView',1);
			}
		}
	},

	// Save a file on demand
	saveFile: function(saveAs) {
		var saveType;

		saveAs ? saveType = "saveAs" : saveType = "save";
		
		top.ICEcoder.serverQueue("add","lib/file-control.php?action=save&file="+ICEcoder.openFiles[ICEcoder.selectedTab-1].replace(/\//g,"|")+"&saveType="+saveType);
		top.ICEcoder.serverMessage('<b>Saving</b><br>'+ICEcoder.openFiles[ICEcoder.selectedTab-1]);
	},

	// Prompt a rename dialog on demand
	renameFile: function() {
		var renamedFile, shortURL;

		shortURL = top.ICEcoder.rightClickedFile.substr((top.ICEcoder.rightClickedFile.indexOf(shortURLStarts)+top.shortURLStarts.length),top.ICEcoder.rightClickedFile.length).replace(/\|/g,"/");
		renamedFile = prompt('Please enter the new name for',shortURL);
		if (renamedFile) {
			for (var i=0;i<top.ICEcoder.openFiles.length;i++) {
				if(top.ICEcoder.openFiles[i]==shortURL.replace(/\|/g,"/")) {
					// rename array item and the tab
					top.ICEcoder.openFiles[i] = renamedFile;
					closeTabLink = '<a nohref onClick="top.ICEcoder.files.contentWindow.closeTab('+(i+1)+')"><img src="images/nav-close.gif"></a>';
					top.document.getElementById('tab'+(i+1)).innerHTML = top.ICEcoder.openFiles[i] + " " + closeTabLink;
				}
			}
		top.ICEcoder.serverQueue("add","lib/file-control.php?action=rename&file="+renamedFile+"&oldFileName="+top.ICEcoder.rightClickedFile.replace(/\|/g,"/"));
		top.ICEcoder.serverMessage('<b>Renaming to</b><br>'+renamedFile);
		}
	},

	// Delete a file on demand
	deleteFile: function() {
		var delFiles, selectedFilesList;

		delFiles = confirm('Delete:\n\n'+top.ICEcoder.selectedFiles.toString().replace(/\|/g,"/").replace(/,/g,"\n")+'?');
		// Upon supply a new name, rename tabs and update filename on server
		if (delFiles) {
			selectedFilesList = "";
			for (var i=0;i<top.ICEcoder.selectedFiles.length;i++) {
				selectedFilesList += top.ICEcoder.selectedFiles[i];
				if (i<top.ICEcoder.selectedFiles.length-1) {selectedFilesList+=";"};
			}
			top.ICEcoder.serverQueue("add","lib/file-control.php?action=delete&file="+selectedFilesList);
			top.ICEcoder.serverMessage('<b>Deleting File</b><br>'+top.ICEcoder.selectedFiles.toString().replace(/\|/g,"/").replace(/,/g,"\n"));
		};
	},

	// Show menu on right clicking in file manager
	showMenu: function() {
		var menuType, folderMenuItems;

		if ("undefined" != typeof top.ICEcoder.thisFileFolderLink && top.ICEcoder.thisFileFolderLink!="") {
			top.ICEcoder.selectedFiles[0].indexOf(".")>0 ? menuType = "file" : menuType = "folder";
			folderMenuItems = top.document.getElementById('folderMenuItems');
			menuType == "folder" ? folderMenuItems.style.display = "block" : folderMenuItems.style.display = "none";
			document.getElementById('fileMenu').style.display = "inline-block";
			document.getElementById('fileMenu').style.left = (top.ICEcoder.mouseX+20) + "px";
			document.getElementById('fileMenu').style.top = (top.ICEcoder.mouseY-top.document.getElementById('filesFrame').contentWindow.document.body.scrollTop+80) + "px";
		}
		return false;
	},

	// Show & hide target element
	showHide: function(doVis,elem) {
		doVis=="show" ? elem.style.visibility='visible' : elem.style.visibility='hidden';
	},

	// Update find & replace options based on user selection
	findReplaceOptions: function() {
		var rText, replace, rTarget;

		rText = document.getElementById('rText').style.display;
		replace = document.getElementById('replace').style.display;
		rTarget = document.getElementById('rTarget').style.display;

		document.findAndReplace.connector.value=="and" ? document.getElementById('rText').style.display = document.getElementById('replace').style.display = document.getElementById('rTarget').style.display = "inline-block" : document.getElementById('rText').style.display = document.getElementById('replace').style.display = document.getElementById('rTarget').style.display = "none";
	},

	// Find & replace text according to user selections
	findReplace: function(action,resultsOnly) {
		var find, findLen, cM, content, lineCount, numChars, charsToCursor, charCount, startPos, endPos;

		// Determine our find string, in lowercase and the length of that
		find = parent.parent.document.getElementById('find').value.toLowerCase();
		findLen = find.length;

		// If we have something to find
		if (findLen>0) {
			cM = ICEcoder.getcMInstance();
			content = cM.getValue().toLowerCase();

			// Find & replace the next instance?
			if (document.findAndReplace.connector.value=="and" && cM.getSelection()==find) {
				cM.replaceSelection(document.getElementById('replace').value);
			}

			if (!top.ICEcoder.findMode||parent.parent.document.getElementById('find').value!=ICEcoder.lastsearch) {
				ICEcoder.results = [];
				for (var i=0;i<content.length;i++) {
					if (content.substr(i,findLen)==find) {
						ICEcoder.results.push(i);
					}
				}
				// Also remember the last search term made
				ICEcoder.lastsearch = find;
			}

			// If we have results
			if (ICEcoder.results.length>0) {
				// Show results only
				if (resultsOnly) {
					parent.parent.document.getElementById('results').innerHTML = ICEcoder.results.length + " results";
				// We need to take action instead
				} else {
					lineCount=1;
					numChars=0;

					for (var i=0;i<content.length;i++) {
						if (content.indexOf('\n',i)==i && lineCount<=cM.getCursor().line) {
							lineCount++;
							numChars=i;
						}
					}

					charsToCursor = numChars+cM.getCursor().ch;
					ICEcoder.findResult = 0;
					for (var i=0;i<ICEcoder.results.length;i++) {
						if (ICEcoder.results[i]<=charsToCursor) {
							ICEcoder.findResult++;
						}
					}

					if (ICEcoder.findResult>ICEcoder.results.length-1) {ICEcoder.findResult=0};
					parent.parent.document.getElementById('results').innerHTML = "Highlighted result "+(ICEcoder.findResult+1)+" of "+ICEcoder.results.length+" results";
					lineCount=0;

					for (var i=0;i<ICEcoder.results[ICEcoder.findResult];i++) {
						if (content.indexOf('\n',i)==i) {
							lineCount++;
						}
					}

					charCount = ICEcoder.results[ICEcoder.findResult]-content.lastIndexOf('\n',ICEcoder.results[ICEcoder.findResult])-1;
					startPos = new Object();
					startPos.line = lineCount;
					startPos.ch = charCount;
					endPos = new Object();
					endPos.line = lineCount;
					endPos.ch = charCount+findLen;

					// Finally, highlight our selection
					cM = ICEcoder.getcMInstance();
					cM.setSelection(startPos, endPos);
					cM.focus();
					top.ICEcoder.findMode = true;
				}
			} else {
				parent.parent.document.getElementById('results').innerHTML = "No results";
			}
		} else {
			parent.parent.document.getElementById('results').innerHTML = "";
		}
	},

	// Go to a specific line number
	goToLine: function() {
		var cM;

		cM = ICEcoder.getcMInstance();
		cM.setCursor(document.getElementById('goToLineNo').value-1);
		cM.focus();
		return false;
	},

	// Switch the CodeMirror mode on demand
	switchMode: function() {
		var cM;

		cM = ICEcoder.getcMInstance();
		fileName = ICEcoder.openFiles[ICEcoder.selectedTab-1];
		if (fileName.indexOf('.js')>0) {
			cM.setOption("mode","javascript");
		} else if (fileName.indexOf('.css')>0) {
			cM.setOption("mode","css");
		} else {
			cM.setOption("mode","application/x-httpd-php");
		}
	},

	// Lock & unlock the file manager navigation on demand
	lockUnlockNav: function() {
		var lockIcon;

		lockIcon = top.document.getElementById('fmLock');
		ICEcoder.lockedNav ? ICEcoder.lockedNav = false : ICEcoder.lockedNav = true;
		ICEcoder.lockedNav ? lockIcon.src="images/file-manager-icons/padlock.png" : lockIcon.src="images/file-manager-icons/padlock-disabled.png";
	},

	// Determine the CodeMirror instance we're using on demand
	getcMInstance: function(newTab) {
		var cM;

		if (newTab=="new"||(newTab!="new" && ICEcoder.openFiles.length>0)) {
			cM = top.ICEcoder.content.contentWindow['cM'+ICEcoder.cMInstances[ICEcoder.selectedTab-1]];
		} else {
			cM = top.ICEcoder.content.contentWindow['cM1'];
		}
		return cM;
	},

	// Start running plugin intervals according to given specifics
	startPluginIntervals: function(plugURL,plugTarget,plugTimer) {
		// For this window instances
		if (plugTarget=="_parent"||plugTarget=="_top"||plugTarget=="_self"||plugTarget=="") {
			setInterval('window.location=\''+plugURL+'\'',plugTimer*1000*60);

		// for fileControl iframe instances
		} else if (plugTarget.indexOf("fileControl")==0) {
			setInterval(function() {top.ICEcoder.serverQueue("add",plugURL);top.ICEcoder.serverMessage(plugTarget.split(":")[1]);},plugTimer*1000*60);

		// for _blank or named target window instances
		} else {
			setInterval('window.open(\''+plugURL+'\',\''+plugTarget+'\')',plugTimer*1000*60);
		}
	},

	// Comment or uncomment line on keypress
	lineCommentToggle: function() {
		var cM, cursorPos, linePos, lineContent, lCLen, adjustCursor;

		cM = ICEcoder.getcMInstance();
		cursorPos = cM.getCursor().ch;
		linePos = cM.getCursor().line;
		lineContent = cM.getLine(linePos);
		lCLen = lineContent.length;
		adjustCursor = 3;

		if (ICEcoder.caretLocType=="JavaScript"||ICEcoder.caretLocType=="PHP"||ICEcoder.caretLocType=="CSS") {
			if (cM.somethingSelected()) {
				if (cM.getSelection().slice(0,2)!="/*") { 
					cM.replaceSelection("/*" + cM.getSelection() + "*/");
				} else {
					cM.replaceSelection(cM.getSelection().slice(2,cM.getSelection().length-2));
				}
			} else {
				if (ICEcoder.caretLocType=="CSS") {
					lineContent.slice(0,3)!="/* " ? cM.setLine(linePos, "/* " + lineContent + " */") : cM.setLine(linePos, lineContent.slice(3,lCLen).slice(0,lCLen-5));
					if (lineContent.slice(0,3)=="/* ") {adjustCursor = -adjustCursor};
				} else {
					lineContent.slice(0,3)!="// " ? cM.setLine(linePos, "// " + lineContent) : cM.setLine(linePos, lineContent.slice(3,lCLen));
					if (lineContent.slice(0,3)=="// ") {adjustCursor = -adjustCursor};
				}
			}
		} else {
			if (cM.somethingSelected()) {
				if (cM.getSelection().slice(0,4)!="<!--") { 
					cM.replaceSelection("<!--" + cM.getSelection() + "//-->");
				} else {
					cM.replaceSelection(cM.getSelection().slice(4,cM.getSelection().length-5));
				}
			} else {
				lineContent.slice(0,4)!="<!--" ? cM.setLine(linePos, "<!--" + lineContent + "//-->") : cM.setLine(linePos, lineContent.slice(4,lCLen).slice(0,lCLen-9));
				lineContent.slice(0,4)=="<!--" ? adjustCursor = -4 : adjustCursor = 4;
			}
		}
		if (!cM.somethingSelected()) {cM.setCursor(linePos, cursorPos+adjustCursor)};
	},

	// Get the mouse position on demand
	getMouseXY: function(e) {
		var tempX, tempY, scrollTop, IE;

		IE = document.all ? true : false;
		if (!IE) {document.captureEvents(Event.MOUSEMOVE)};
		if (IE) {
			top.ICEcoder.mouseX = event.clientX + document.body.scrollLeft;
			top.ICEcoder.mouseY = event.clientY + document.body.scrollTop;
		} else {
			top.ICEcoder.mouseX = e.pageX;
			top.ICEcoder.mouseY = e.pageY;
		}
		top.ICEcoder.dragCursorTest();
	},

	// Test if we need to show a drag cursor or not
	dragCursorTest: function() {
		var winH;

		window.innerWidth ? winH = window.innerHeight : winH = document.body.clientHeight;
		if (!top.ICEcoder.mouseDown) {top.ICEcoder.draggingFilesW = false};

		if ((top.ICEcoder.mouseX > top.ICEcoder.filesW-7 && top.ICEcoder.mouseX < top.ICEcoder.filesW+7 && top.ICEcoder.mouseY > 40+50 && top.ICEcoder.mouseY < winH-30-40) || top.ICEcoder.draggingFilesW) {
			top.document.body.style.cursor = "w-resize";
		} else {
			top.document.body.style.cursor = "auto";
		}
	},

	// Update the file manager tree list on demand
	updateFileManagerList: function(action,location,file) {
		var actionElemType, cssStyle, hrefLink, targetElem, locNest, newUL, newLI, nameLI, shortURL, newMouseOver;

		// Adding files
		if (action=="add") {

			// Determin if this is a file or folder and based on that, set the CSS styling & link
			file.indexOf(".")>0 ? actionElemType = "file" : actionElemType = "folder";
			actionElemType=="file" ? cssStyle = "pft-file ext-" + file.substr(file.indexOf(".")+1,file.length) : cssStyle = "pft-directory";
			actionElemType=="file" ? hrefLink = "nohref" : hrefLink = "href=\"#\"";

			// Identify our target element & the first child element in it's location
			targetElem = document.getElementById('filesFrame').contentWindow.document.getElementById(location.replace(/\//g,"|"));
			locNest = targetElem.parentNode.parentNode.childNodes[1];

			// If we don't have a nest location, it's an empty folder
			if(!locNest) {

				// We now need to begin a new UL list
				newUL = document.createElement("ul");
				locNest = targetElem.parentNode.parentNode;
				locNest.appendChild(newUL);

				// Now we have a list to insert into, we can identify the first child element
				locNest = targetElem.parentNode.parentNode.childNodes[1];

				// Finally we can add the first list item for this file/folder we're adding
				newLI = document.createElement("li");
				newLI.className = cssStyle;
				newLI.innerHTML = '<a '+hrefLink+' onMouseOver="top.ICEcoder.overFileFolder(\''+actionElemType+'\',\''+fullPath+location+'/'+file+'\')" onMouseOut="top.ICEcoder.overFileFolder(\''+actionElemType+'\',\'\')" style="position: relative; left:-22px" class="closed">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id="'+location.replace(/\//g,"|")+'|'+file+'">'+file+'</a>';
				locNest.appendChild(newLI,locNest.childNodes[0]);

			// There are items in that location, so add our new item in the right position
			} else {

				for (var i=0;i<=locNest.childNodes.length-1;i++) {
					// Identify if the item we're considering is a file or folder
					locNest.childNodes[i].className.indexOf('directory')>0 ? elemType = "folder" : elemType = "file";
					
					// Get the name of the item
					nameLI = locNest.childNodes[i].getElementsByTagName('span')[0].innerHTML;

					// If it's of the same type & the name is greater, or we're adding a folder and it's a file or if we're at the end of the list
					// then we can add in here
					if ((elemType==actionElemType && nameLI > file) || (actionElemType=="folder" && elemType=="file") || i==locNest.childNodes.length-1) {
						newLI = document.createElement("li");
						newLI.className = cssStyle;
						newLI.innerHTML = '<a '+hrefLink+' onMouseOver="top.ICEcoder.overFileFolder(\''+elemType+'\',\''+fullPath+location+'/'+file+'\')" onMouseOut="top.ICEcoder.overFileFolder(\''+elemType+'\',\'\')" style="position: relative; left:-22px" class="closed">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id="'+location.replace(/\//g,"|")+'|'+file+'">'+file+'</a>';

						// Append or insert depending on which of the above if statements is true
						i==locNest.childNodes.length-1 ? locNest.appendChild(newLI,locNest.childNodes[i]) : locNest.insertBefore(newLI,locNest.childNodes[i]);

						// Escape from this loop now
						i=locNest.childNodes.length;
					}
				}
			}
		}

		// Renaming files
		if (action=="rename") {
			// Identify a shortened URL for our right clicked file and get our target element based on this
			shortURL = top.ICEcoder.rightClickedFile.substr((top.ICEcoder.rightClickedFile.indexOf(shortURLStarts)+top.shortURLStarts.length),top.ICEcoder.rightClickedFile.length).replace(/\|/g,"/");
			targetElem = document.getElementById('filesFrame').contentWindow.document.getElementById(shortURL.replace(/\//g,"|"));
			// Set the name to be as per our new file/folder name
			targetElem.innerHTML = file;
			// Finally, update the ID of the target & set a new mouseover function for the parent too
			targetElem.id = location.replace(/\//g,"|") + "|" + file;
			newMouseOver = targetElem.parentNode.onmouseover.toString().replace(shortURL.substring(shortURL.lastIndexOf("/")+1,shortURL.length),file).split('\'');
			eval("targetElem.parentNode.onmouseover = function() { top.ICEcoder.overFileFolder('"+newMouseOver[1]+"','"+newMouseOver[3]+"');}");
		}

		// Deleting files
		if (action=="delete") {
			// Simply get our target and make it dissapear
			targetElem = document.getElementById('filesFrame').contentWindow.document.getElementById(location.replace(/\//g,"|")+file);
			targetElem.parentNode.parentNode.style.display = "none";
		}
	},

	// Turning on/off the Code Assist
	codeAssistToggle: function() {
		var cM;

		cM = ICEcoder.getcMInstance();
		top.ICEcoder.codeAssist ? top.ICEcoder.codeAssist = false : top.ICEcoder.codeAssist = true;
		top.ICEcoder.cssColorPreview();
		cM.focus();
	},

	// Show or hide a server message
	serverMessage: function(message) {
		var serverMessage;

		serverMessage =	document.getElementById('serverMessage');
		if (message) {serverMessage.innerHTML = message};
		message ? serverMessage.style.opacity = 1 : serverMessage.style.opacity = 0;
	},

	// Queue items up for processing in turn
	serverQueue: function(action,item) {
		var cM,nextSaveID,txtArea,topSaveID,element;

		cM = ICEcoder.getcMInstance();
		// Firstly, work out how many saves we have to carry out
		nextSaveID=0;
		for (i=0;i<ICEcoder.serverQueueItems.length;i++) {
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
				document.getElementById('saveTemp'+nextSaveID).value = cM.getValue();
			}
		}
		if (action=="del") {
			if (ICEcoder.serverQueueItems[0] && ICEcoder.serverQueueItems[0].indexOf('action=save')>0) {
				topSaveID = nextSaveID-1;
				for (i=1;i<topSaveID;i++) {
					document.getElementById('saveTemp'+i).value = document.getElementById('saveTemp'+(i+1)).value;
				}
				element = document.getElementById('saveTemp'+topSaveID);
				element.parentNode.removeChild(element);
			}
			ICEcoder.serverQueueItems.splice(0,1);
		}

		// If we've just removed from the array and there's another action queued up, or we're triggering for the first time
		// then do the next requested process, stored at array pos 0
		if (action=="del" && ICEcoder.serverQueueItems.length>=1 || ICEcoder.serverQueueItems.length==1) {
			setTimeout(function() {top.ICEcoder.filesFrame.contentWindow.frames['fileControl'].location.href=ICEcoder.serverQueueItems[0]},1);
		}
	},

	// Show a CSS color block next to our text cursor
	cssColorPreview: function() {
		var cM,string,startPosAdj,endPosAdj,nextSpace,oldBlock,newBlock;

		cM = ICEcoder.getcMInstance();
		string = cM.getLine(cM.getCursor().line);
		startPosAdj = string.slice(0,cM.getCursor().ch).length - string.slice(0,cM.getCursor().ch).lastIndexOf(' ') - 1;
		nextSpace = string.slice(cM.getCursor().ch,string.length).indexOf(' ');
		nextSpace > -1 ? endPosAdj = nextSpace : endPosAdj = string.length - cM.getCursor().ch;
		string = string.slice(cM.getCursor().ch-startPosAdj,cM.getCursor().ch+endPosAdj);
		string = string.replace(/[^a-z0-9#(),.]/gi,'');

		oldBlock = top.document.getElementById('content').contentWindow.document.getElementById('cssColor');
		if (oldBlock) {oldBlock.parentNode.removeChild(oldBlock)};
		if (top.ICEcoder.codeAssist) {
			newBlock = top.document.createElement("div");
			newBlock.id="cssColor";
			newBlock.style.position = "absolute";
			newBlock.style.display = "block";
			newBlock.style.width = newBlock.style.height = "20px";
			newBlock.style.backgroundColor = string;
			if (newBlock.style.backgroundColor=="") {newBlock.style.display = "none"};
			top.document.getElementById('header').appendChild(newBlock);
			cM.addWidget(cM.getCursor(), top.document.getElementById('cssColor'), true);
		}
	},

	// Carry out actions when clicking icons above file manager
	fMIcon: function(action) {

		if (action=="save" && ICEcoder.openFiles.length>0) {
			top.ICEcoder.saveFile();
		}

		if (ICEcoder.selectedFiles.length==1) {
			top.ICEcoder.rightClickedFile=top.ICEcoder.thisFileFolderLink=top.fullPath+top.ICEcoder.selectedFiles[0].replace('|','/');

			if (action=="open" && ICEcoder.selectedFiles[0].indexOf(".")>0) {
				top.ICEcoder.thisFileFolderType='file';
				top.ICEcoder.openFile();
			}
			if (action=="newFile")	 {top.ICEcoder.newFile();}
			if (action=="newFolder") {top.ICEcoder.newFolder();}
			if (action=="delete")	 {top.ICEcoder.deleteFile(top.ICEcoder.rightClickedFile);}
			if (action=="rename")	 {top.ICEcoder.renameFile(top.ICEcoder.rightClickedFile);}
		}

		if (action=="view" && ICEcoder.openFiles.length>0) {
			window.open(top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1]);
		}
	},

	// Change opacity of the file manager icons on demand
	fMIconVis: function(icon, vis) {
		if (top.document.getElementById(icon)) {
			top.document.getElementById(icon).style.opacity = vis;
		}
	},

	// Cancel all actions on pressing Esc in non content areas
	cancelAllActions: function() {
		// Stop whatever the parent may be loading, plus clear any file manager tasks other than the current one
		window.stop();
		if (ICEcoder.serverQueueItems.length>0) {
			ICEcoder.serverQueueItems.splice(1,ICEcoder.serverQueueItems.length);
		}
		top.document.getElementById('loadingMask').style.visibility = "hidden";
		top.ICEcoder.serverMessage('<b style="color: #dd0000">Cancelled tasks</b>');
		setTimeout(function() {top.ICEcoder.serverMessage();},2000);
	},

	// Highlight or hide block upon roll over/out of nest positions
	highlightBlock: function(nestPos,hide) {
		var cM;

		cM = ICEcoder.getcMInstance();
		// Hiding the block
		if (hide) {
			// Either set our cursor back to the orig position if we have't clicked or redo the nest display if we have
			top.ICEcoder.dontUpdateNest ? cM.setCursor(top.ICEcoder.cursorOrigLine,top.ICEcoder.cursorOrigCh) : top.ICEcoder.getNestLocation('updateNestDisplay');
			top.ICEcoder.dontUpdateNest = false;
		} else {
			// Showing the block, get orig cursor position
			top.ICEcoder.cursorOrigCh = cM.getCursor().ch;
			top.ICEcoder.cursorOrigLine = cM.getCursor().line;
			top.ICEcoder.dontUpdateNest = true;
		
			// Set a cursor position object to begin with
			searchPos = new Object();
			searchPos.ch = cM.getCursor().ch;
			searchPos.line = cM.getCursor().line;
			// Then find our cursor position for our target nest depth
			for (var i=top.ICEcoder.htmlTagArray.length-1;i>=nestPos;i--) {
				cursor = cM.getSearchCursor("<"+top.ICEcoder.htmlTagArray[i],searchPos);
				cursor.findPrevious();
				searchPos.ch = cursor.from().ch;
				searchPos.line = cursor.from().line;
			}
			// Once we've found our tag
			if (cursor.from()) {
				// Set our vars to match the start position
				startPos = new Object();
				top.ICEcoder.startPosCh = startPos.ch = cursor.from().ch;
				top.ICEcoder.startPosLine = startPos.line = cursor.from().line;
				// Now set an end position object that matches this start tag
				endPos = new Object();
				endPos.line = top.ICEcoder.content.contentWindow.CodeMirror.tagRangeFinder(cM,startPos.line)-1 || startPos.line;
				endPos.ch = cM.getLine(endPos.line).indexOf("</"+top.ICEcoder.htmlTagArray[nestPos]+">")+top.ICEcoder.htmlTagArray[nestPos].length+3;
				// Set the selection or escape out of not selecting
				!top.ICEcoder.dontSelect ? cM.setSelection(startPos,endPos) : top.ICEcoder.dontSelect = false;
			}
		}
	},

	// Set our cursor position upon mouse click of the nest position
	setPosition: function(nestPos,line,tag) {
		var cM;

		cM = ICEcoder.getcMInstance();
		// Set out char position just after the tag, and refocus on the editor
		char = cM.getLine(line).indexOf(">",cM.getLine(line).indexOf("<"+tag))+1;
		cM.setCursor(line,char);
		cM.focus();
		// Now update the nest display up to this nest depth & without any HTML tags to kill further interactivity
		charPos = 0;
		for (i=0;i<=nestPos;i++) {
			charPos = ICEcoder.nestDisplay.innerHTML.indexOf("&gt;",charPos+1);
		}
		ICEcoder.nestDisplay.innerHTML = ICEcoder.nestDisplay.innerHTML.substr(0,charPos).replace(/<(?:.|\n)*?>/gm, '');
		top.ICEcoder.dontUpdateNest = false;
		top.ICEcoder.dontSelect = true;
	}
};