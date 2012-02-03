// ICE coder by Matt Pass
// Free to use it for your own purposes, commercial or not, just let me know of any cool uses or customisations. :)
// No warranty or liability accepted for anything, all responsibility of use is your own.
// Latest version: https://github.com/mattpass/ICEcoder
// Twitter: @mattpass

var ICEcoder = {
	// Define settings
	filesW: 15,			// Initial width of the files pane
	minFilesW: 15,			// Min width of the files pane
	maxFilesW: 250,			// Max width of the files pane
	selectedTab: 0,			// The tab that's currently selected
	changedContent: [],		// Binary array to indicate which tabs have changed
	ctrlKeyDown: false,		// Indicates if CTRL keydown
	delKeyDown: false,		// Indicates if DEL keydown
	canSwitchTabs: true,		// Stops switching of tabs when trying to close
	openFiles: [],			// Array of open file URLs
	selectedFiles: [],		// Array of selected files
	findMode: false,		// States if we're in find/replace mode
	lockedNav: false, 		// Nav is locked or not

	// Don't consider these tags as part of nesting as they're singles, JS or PHP code blocks
	tagNestExceptions: ["!DOCTYPE","meta","link","img","br","hr","input","script","?"],

	// On load, set aliases, set the layout and get the nest location
	init: function() {
		var aliasArray = ["header","files","account","filesFrame","editor","tabsBar","findBar","content","footer","nestValid","nestDisplay","charDisplay"];
		ICEcoder.tD = top.document;

		// Create our ID aliases
		for (var i=0;i<aliasArray.length;i++) {
			ICEcoder[aliasArray[i]] = ICEcoder.tD.getElementById(aliasArray[i]);
		}

		// Set layout & the nest location
		ICEcoder.setLayout();
		ICEcoder.getNestLocation('yes');
	},

	// Set out our layout according to the browser size
	setLayout: function() {
		var winW, winH, headerH, footerH, accountH, uploadH, tabsBarH, findBarH, cMCSS;

		// Determin width & height available
		if (window.innerWidth) {
			var winW = window.innerWidth;
			var winH = window.innerHeight;
		} else {
			var winW = document.body.clientWidth;
			var winH = document.body.clientHeight;
		}

		// Apply sizes to various elements of the page
		headerH = 40, footerH = 30, accountH = 50, uploadH = 40, tabsBarH = 21, findBarH = 28;
		header.style.width = tabsBar.style.width = findBar.style.width = winW + "px";
		files.style.width = editor.style.left = this.filesW + "px";
		account.style.height = accountH + "px";
		filesFrame.style.height = (winH-headerH-accountH-footerH-uploadH) + "px";
		editor.style.width = ICEcoder.content.style.width = (winW-this.filesW) + "px";
		ICEcoder.content.style.height = (winH-headerH-footerH-tabsBarH-findBarH) + "px";

		// Resize the CodeMirror instances to match the window size
		document.all ? strCSS = 'rules' : strCSS = 'cssRules';
		cMCSS = ICEcoder.content.contentWindow.document;
		cMCSS.styleSheets[2][strCSS][1].style['width'] = ICEcoder.content.style.width;
		cMCSS.styleSheets[2][strCSS][1].style['height'] = ICEcoder.content.style.height;
		cMCSS.styleSheets[2][strCSS][2].style['width'] = ICEcoder.content.style.width;
	},

	// Clean up our loaded code
	contentCleanUp: function() {
		var fileName;
		fileName = ICEcoder.openFiles[ICEcoder.selectedTab-1];
		if (fileName.indexOf(".js")<0&&fileName.indexOf(".css")<0) {

			var cM, content;
			cM = ICEcoder.getcMInstance();
			content = cM.getValue();
			content = content.replace(' & ',' &amp; ');
			content = content.replace('<ICEcoder:/:textarea>','</textarea>');

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
				if (tagStart=="script"||tagStart=="?") {ICEcoder.codeBlock=true}
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
						(ICEcoder.tagStart=="?"&&tagEnd=="?")) {
						ICEcoder.codeBlock=false;
					}
				}

				// Reset our open tag state ready for next time
				openTag=false;
			}	
		}

		// Now we've built up our nest depth array, if we're due to show it in the display
		if (updateNestDisplay) {

			// Clear the display
			ICEcoder.nestDisplay.innerHTML = "";
			if ("undefined" != typeof ICEcoder.openFiles[ICEcoder.selectedTab-1]) {
				fileName = ICEcoder.openFiles[ICEcoder.selectedTab-1];
				if (fileName.indexOf(".js")<0&&fileName.indexOf(".css")<0) {

					// Then for all the array items, output as the nest display
					for (var i=0;i<ICEcoder.htmlTagArray.length;i++) {
						ICEcoder.nestDisplay.innerHTML += ICEcoder.htmlTagArray[i];
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

		// CTRL+F (Find)
		} else if(key==70 && top.ICEcoder.ctrlKeyDown==true) {
			top.document.getElementById('find').focus();
	        	return false;

		// CTRL+S (Save)
		} else if(key==83 && top.ICEcoder.ctrlKeyDown==true) {
			top.ICEcoder.saveFile();
	        	return false;

		// CTRL+Enter (Open Webpage)
		} else if(key==13 && top.ICEcoder.ctrlKeyDown==true) {
			window.open(top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1]);
	        	return false;

		// ESC (Comment/Uncomment line)
       		} else if(key==27 && area == "content") {
			top.ICEcoder.lineCommentToggle();
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
		if (key==46) {top.ICEcoder.delKeyDown = false;}
	},

	// Set the width of the file manager on demand
	changeFilesW: function(expandContract) {
		var expandContract;

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

	// Change tabs by reloading content
	switchTab: function(newTab) {
		var cM;

		// Identify tab that's currently selected & show the instance
		ICEcoder.selectedTab = newTab;
		cM = ICEcoder.getcMInstance();

		// Switch mode
		ICEcoder.switchMode();

		// Set all 10 cM instances to be hidden, then make our selected instance visable
		for (var i=1;i<=10;i++) {
			ICEcoder.content.contentWindow['cM'+i].setOption('theme','icecoder hidden');
		}
		cM.setOption('theme','icecoder visible');

		// Focus on our selected instance
		cM = ICEcoder.getcMInstance();
		cM.focus();

		// Highlight the selected tab
		ICEcoder.redoTabHighlight(ICEcoder.selectedTab);
	},

	// Reset all tabs to be without a highlight and then highlight the selected
	redoTabHighlight: function(selectedTab) {
		for(var i=1;i<=10;i++) {
			ICEcoder.changedContent[i-1]==1 ? bgVPos = -44 : bgVPos = 0;
			i==selectedTab ? ICEcoder.changedContent[selectedTab-1]==1 ? bgVPos = -33 : bgVPos = -22 : bgVPos = bgVPos;
			document.getElementById('tab'+i).style.backgroundPosition = "0px "+bgVPos+"px";
		}
	},

	// Starts a new file by setting a few vars & clearing the editor
	newTab: function() {
		var cM;

		ICEcoder.thisFileFolderType='file';
		ICEcoder.thisFileFolderLink=shortURLStarts+'/[NEW]';
		ICEcoder.openFile();
		cM = ICEcoder.getcMInstance('new');
		cM.setValue('');
		cM.clearHistory();
		ICEcoder.switchTab(ICEcoder.openFiles.length);
		ICEcoder.content.style.visibility='visible';
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

		var fileName;
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
			// recursively copy over all tab data from the tab to the right, if there is one
			for (var i=closeTabNum;i<ICEcoder.openFiles.length;i++) {
				ICEcoder.tD.getElementById('tab'+i).innerHTML = ICEcoder.tD.getElementById('tab'+(i+1)).innerHTML;
				ICEcoder.openFiles[i-1] = ICEcoder.openFiles[i];

				// reduce the tab reference number on the closeTab link by 1
				ICEcoder.tD.getElementById('tab'+i).innerHTML = ICEcoder.tD.getElementById('tab'+i).innerHTML.replace(("closeTab("+(i+1)+")"),"closeTab("+i+")");
			}

			// clear the rightmost tab (or only one left in a 1 tab scenario)
			ICEcoder.tD.getElementById('tab'+ICEcoder.openFiles.length).style.display = "none";
			ICEcoder.tD.getElementById('tab'+ICEcoder.openFiles.length).innerHTML = "";
			ICEcoder.openFiles.pop();

			// Determin the new selectedTab number, reduced by 1 if we have some tabs, 0 for a reset state
			ICEcoder.openFiles.length>0 ? ICEcoder.selectedTab-=1 : ICEcoder.selectedTab = 0;
			if (ICEcoder.openFiles.length>0 && ICEcoder.selectedTab==0) {ICEcoder.selectedTab=1};

			// clear & hide the code textarea if we have no tabs open
			if (ICEcoder.openFiles.length==0) {
				// clear the value & HTML of the code textarea and also hide it
				cM = ICEcoder.getcMInstance();
				cM.setValue('');
				cM.clearHistory();
				ICEcoder.tD.getElementById('content').style.visibility = "hidden";
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
					if (submenu[j].tagName == "UL")
						submenu[j].style.display = (mclass.indexOf("open") > -1) ? "block" : "none";
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
		var resetFile, shortURL, foundSelectedFile, foundShortURL, foundFile, resetFile;

	// If we've clicked somewhere other than a file/folder
	if (top.ICEcoder.thisFileFolderLink=="") {
		if (!top.ICEcoder.ctrlKeyDown) {
			// Deselect all files
			for (var i=0;i<=top.ICEcoder.selectedFiles.length;i++) {
				if (top.ICEcoder.selectedFiles[i]) {
					resetFile = top.ICEcoder.filesFrame.contentWindow.document.getElementById(top.ICEcoder.selectedFiles[i]);
					resetFile.style.backgroundColor="#dddddd";
					resetFile.style.color="#000000";
				}
			}
			// Set our arrray to contain 0 items
			top.ICEcoder.selectedFiles.length = 0;
		}
	} else {
		// We clicked a file/folder. Work out a shortened URL for the file, with pipes instead of slashes
		shortURL = top.ICEcoder.thisFileFolderLink.substr((top.ICEcoder.thisFileFolderLink.indexOf(shortURLStarts)+top.shortURLStarts.length),top.ICEcoder.thisFileFolderLink.length).replace(/\//g,"|");

		// If we have the CTRL key down
		if (top.ICEcoder.ctrlKeyDown) {
			foundSelectedFile=false;
			// Reset all files to not be highlighted
			for (i=0;i<=top.ICEcoder.selectedFiles.length;i++) {
				if (top.ICEcoder.selectedFiles[i]==shortURL) {
					resetFile = ICEcoder.filesFrame.contentWindow.document.getElementById(top.ICEcoder.selectedFiles[i]);
					resetFile.style.backgroundColor="#dddddd";
					resetFile.style.color="#000000";
					top.ICEcoder.selectedFiles.splice(i);
					foundSelectedFile=true;
				}
			}
			if (!foundSelectedFile) {
				foundFile = ICEcoder.filesFrame.contentWindow.document.getElementById(shortURL);
				foundFile.style.backgroundColor="#888888";
				foundFile.style.color="#f8f8f8";
				top.ICEcoder.selectedFiles.push(shortURL);
			}
		// We are single clicking
		} else {
			if (top.ICEcoder.selectedFiles.length==1 && shortURL==top.ICEcoder.selectedFiles[0]) {
				// Go into edit mode...
				if (top.ICEcoder.slowClick) {top.ICEcoder.renameFile()}
			} else {
				// First deselect all files
				for (i=0;i<top.ICEcoder.selectedFiles.length;i++) {
					resetFile = ICEcoder.filesFrame.contentWindow.document.getElementById(top.ICEcoder.selectedFiles[i]);
					resetFile.style.backgroundColor="#dddddd";
					resetFile.style.color="#000000";
				}
				// Set our arrray to contain 0 items
				top.ICEcoder.selectedFiles.length = 0;

				// Add our URL and highlight the file
				top.ICEcoder.selectedFiles.push(shortURL);
				foundFile = ICEcoder.filesFrame.contentWindow.document.getElementById(shortURL);
				foundFile.style.backgroundColor="#888888";
				foundFile.style.color="#f8f8f8";

				// Set a variable to test for slow double clicking (1-3secs)
				top.ICEcoder.slowClick = false;
				clearInterval(top.ICEcoder.slowClickS);
				clearInterval(top.ICEcoder.slowClickE);
				top.ICEcoder.slowClickS = setTimeout('top.ICEcoder.slowClick = true',1000);
				top.ICEcoder.slowClickE = setTimeout('top.ICEcoder.slowClick = false',3000);
			}
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
				ICEcoder.filesFrame.contentWindow.frames['fileControl'].location.href = "lib/file-control.php?action=load&file="+top.ICEcoder.thisFileFolderLink;
			} else {
				top.ICEcoder.createNewTab();
			}
		}
	}
	},

	// Save a file on demand
	saveFile: function() {
		filesFrame.contentWindow.frames['fileControl'].location.href="lib/file-control.php?action=save&file="+ICEcoder.openFiles[ICEcoder.selectedTab-1].replace(/\//g,"|");
	},

	// Prompt a rename dialog on demand
	renameFile: function() {
		var renamedFile, shortURL;

		shortURL = top.ICEcoder.thisFileFolderLink.replace(/\|/g,"/");

		renamedFile = false;
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
		ICEcoder.filesFrame.contentWindow.frames['fileControl'].location.href = "lib/file-control.php?action=rename&file="+renamedFile+"&oldFileName="+top.ICEcoder.thisFileFolderLink;
		}
	},

	// Delete a file on demand
	deleteFile: function() {
		var delFiles, selectedFilesList;

		delFiles = confirm('Delete: '+top.ICEcoder.selectedFiles+'?');

		// Upon supply a new name, rename tabs and update filename on server
		if (delFiles) {
			selectedFilesList = "";
			for (var i=0;i<top.ICEcoder.selectedFiles.length;i++) {
				selectedFilesList += top.ICEcoder.selectedFiles[i];
				if (i<top.ICEcoder.selectedFiles.length-1) {selectedFilesList+=";"}
			}
			ICEcoder.filesFrame.contentWindow.frames['fileControl'].location.href = "lib/file-control.php?action=delete&file="+selectedFilesList;
		};
	},

	// Show menu on right clicking in file manager
	showMenu: function() {
		if ("undefined" != typeof top.ICEcoder.thisFileFolderLink && top.ICEcoder.thisFileFolderLink!="") {
			alert('show menu');
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

		if (newTab=="new") {
			cM = top.ICEcoder.content.contentWindow['cM'+ICEcoder.openFiles.length];
		} else if (ICEcoder.openFiles.length==0) {
			cM = top.ICEcoder.content.contentWindow['cM1'];
		} else {
			cM = top.ICEcoder.content.contentWindow['cM'+ICEcoder.selectedTab];
		}
		return cM;
	},

	// Start running plugin intervals according to given specifics
	startPluginIntervals: function(plugURL,plugTarget,plugTimer) {

		// For this window instances
		if (plugTarget=="_parent"||plugTarget=="_top"||plugTarget=="_self"||plugTarget=="") {
			setInterval('window.location=\''+plugURL+'\'',plugTimer*1000*60);

		// for pluginActions iframe instances
		} else if (plugTarget=="pluginActions") {
			setInterval('document.getElementById(\'pluginActions\').src=\''+plugURL+'\'',plugTimer*1000*60);

		// for _blank or named target window instances
		} else {
			setInterval('window.open(\''+plugURL+'\',\''+plugTarget+'\')',plugTimer*1000*60);
		}
	},

	// Comment or uncomment line on keypress
	lineCommentToggle: function() {
		var cM, lineContent, cursorPos;
		cM = ICEcoder.getcMInstance();

		cursorPos = cM.getCursor().ch;
		lineContent = cM.getLine(cM.getCursor().line);

		if (ICEcoder.caretLocType=="JavaScript"||ICEcoder.caretLocType=="PHP") {
			if (lineContent.slice(0,3)!="// ") {
				cM.setLine(cM.getCursor().line, "// " + lineContent);
				cM.setCursor(cM.getCursor().line, cursorPos+3);
			} else {
				cM.setLine(cM.getCursor().line, lineContent.slice(3,lineContent.length));
				cM.setCursor(cM.getCursor().line, cursorPos-3);
			}
		} else if (ICEcoder.caretLocType=="CSS") {
			if (lineContent.slice(0,3)!="/* ") {
				cM.setLine(cM.getCursor().line, "/* " + lineContent + " */");
				cM.setCursor(cM.getCursor().line, cursorPos+3);
			} else {
				cM.setLine(cM.getCursor().line, lineContent.slice(3,lineContent.length).slice(0,lineContent.length-5));
				cM.setCursor(cM.getCursor().line, cursorPos-3);
			}
		} else {
			if (lineContent.slice(0,4)!="<!--") {
				cM.setLine(cM.getCursor().line, "<!--" + lineContent + "//-->");
				cM.setCursor(cM.getCursor().line, cursorPos+4);
			} else {
				cM.setLine(cM.getCursor().line, lineContent.slice(4,lineContent.length).slice(0,lineContent.length-9));
				cM.setCursor(cM.getCursor().line, cursorPos-4);
			}
		}
	}
};