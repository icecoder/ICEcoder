<!DOCTYPE html>

<!-- Automated test suite to test some of the core functionality of ICEcoder including:
Open file, update document, save file, highlight line, add tag wrappers, duplicate line, add line break, comment line, remove line, contract, expand, unlock & lock file manager, open new tab, previous & next tab, switch tab, close tab, get remote file, move and go to line
//-->

<html>
<head>
<title>ICEcoder Test Suite</title>
<meta name="robots" content="noindex, nofollow">
<script src="object-watch.js?microtime=<?php echo microtime(true);?>"></script>
</head>

<script>
ICEcoder=top.ICEcoder;
if(ICEcoder.openFiles.length>0) {ICEcoder.closeAllTabs();}
unitTestResults = top.ICEcoder.content.contentWindow.document.getElementById('unitTestResults');
unitTestResults.innerHTML = "";
</script>

<?php
// Set test file name & location
$testFile = "test-file1.txt";
$testFileFullPath = str_replace("\\","/",dirname($_SERVER['PHP_SELF']))."/".$testFile;
// Delete any existing test file and create a new one
if (file_exists($testFile)) {unlink ($testFile);};
$fh = fopen($testFile, 'w') or die("<script>noCreate='FAIL Could not create test file';console.log(noCreate);unitTestResults.innerHTML = noCreate;</script>");
fwrite($fh, 'initial text');
fclose($fh);
?>

<body onLoad="runTests()">

<script>
function runTests() {
	o = {p: 'start'};	// var used to test another var has changed
	t = 0;			// tries
	s = 0;			// successful tests
	test.openFile();	// start the first test
}

test = {
	openFile: function() {
		title = "Open file";
		o.p = ICEcoder.openFiles[0];
		t = 0;
		x = setInterval(function() {
			wait();
			cM = ICEcoder.getcMInstance();
			if (cM && "undefined" != typeof ICEcoder.openFiles[0]) {
				testResult("+ GOOD",title+". Took "+t+"ms",x);
				ICEcoder.serverMessage();
				top.ICEcoder.serverQueue("del",0);
				test.updateDoc();
			} else if (t==1000) {
				testResult("- FAIL",title+". Took "+t+"ms",x);
				testStopped();
			}
			o.p = ICEcoder.openFiles[0];
			t++;
		},1);
		result = ICEcoder.openFile('<?php echo $testFileFullPath?>');
	},

	updateDoc: function() {
		title = "Change file contents";
		cM.setValue('Updated');
		if (cM.getValue()=="Updated") {
			testResult("+ GOOD",title);
			test.saveFile();
		} else {
			testResult("+ FAIL",title);
			testStopped();
		}	
	},

	saveFile: function() {
		title = "Save file";
		o.p = ICEcoder.savedPoints[0];
		t = 0;
		x = setInterval(function() {
			wait();
			cM = ICEcoder.getcMInstance();
			if (cM && ICEcoder.savedPoints[0]==cM.changeGeneration()-1) {
				testResult("+ GOOD",title+". Took "+t+"ms",x);
				test.tagWrapper();
			} else if (t==1000) {
				testResult("- FAIL",title+". Took "+t+"ms",x);
				testStopped();
			}
			o.p = ICEcoder.savedPoints[0];
			t++;
		},1);
		result = ICEcoder.saveFile();
	},

	tagWrapper: function() {
		title = "Highlight line and add <p> and <div> tag wrappers";
		ICEcoder.highlightLine(0);
		ICEcoder.tagWrapper('p');
		ICEcoder.tagWrapper('div');
		if (cM.getValue() == "<div>\n\t<p>Updated</p>\n</div>") {
			testResult("+ GOOD",title);
			test.lineDupBreakCommentRemove();
		} else {
			testResult("- FAIL",title);
			testStopped();
		}
	},

	lineDupBreakCommentRemove: function() {
		title = "Duplicate, add break, comment and remove line";
		ICEcoder.duplicateLines(1);
		ICEcoder.addLineBreakAtEnd(2);
		ICEcoder.lineCommentToggle();
		line2 = cM.getLine(2);
		ICEcoder.removeLines(2);
		line2Now = cM.getLine(2);
		if (line2 == "<!--	<p>Updated</p><br>//-->" && line2Now == "</div>") {
			testResult("+ GOOD",title);
			test.lockUnlockNav();
		} else {
			testResult("- FAIL",title);
			testStopped();
		}
	},

	lockUnlockNav: function() {
		title = "Expand/contract and lock/unlock";
		ICEcoder.lockUnlockNav();
		ICEcoder.changeFilesW('contract');
		setTimeout(function(){
			lockChanged = top.ICEcoder.lockedNav;
			widthChanged = ICEcoder.filesW;
			ICEcoder.changeFilesW('expand');
			setTimeout(function(){
				ICEcoder.lockUnlockNav();
				if (ICEcoder.lockedNav != lockChanged && ICEcoder.filesW != widthChanged) {
					testResult("+ GOOD",title);
					test.newTab();
				} else {
					testResult("- FAIL",title);
					testStopped();
				}
			},500);
		},500);
	},

	newTab: function() {
		title = "Open new tab";
		ICEcoder.newTab();
		if (ICEcoder.openFiles.length==2) {
			testResult("+ GOOD",title);
			setTimeout(function(){
				test.prevNextTab();
			},500);
		} else {
			testResult("- FAIL",title);
			testStopped();
		}
	},

	prevNextTab: function() {
		title = "Previous and next tab";
		ICEcoder.previousTab();
		tPrev = ICEcoder.selectedTab;
		setTimeout(function(){
			if (tPrev==1) {
				ICEcoder.nextTab();
				tNext = ICEcoder.selectedTab;
			}
		},300);
		setTimeout(function(){
			if (tPrev==1 && tNext==2) {
				testResult("+ GOOD",title);
				test.switchTab();
			} else {
				testResult("- FAIL",title);
				testStopped();
			}
		},600);
	},

	switchTab: function() {
		title = "Switch tab";
		ICEcoder.switchTab(1);
		if (ICEcoder.selectedTab==1) {
			testResult("+ GOOD",title);
			test.closeTab();
		} else {
			testResult("- FAIL",title);
			testStopped();
		}
	},

	closeTab: function() {
		title = "Close tab";
		ICEcoder.closeTab(2,false,true);
		setTimeout(function() {
			if (ICEcoder.openFiles.length==1) {
				testResult("+ GOOD",title);
				test.getRemoteFile();
			} else {
				testResult("- FAIL",title);
				testStopped();		
			}
		},200);
	},

	getRemoteFile: function() {
		title = "Get remote file";
		o.p = ICEcoder.openFiles[1];
		t = 0;
		x = setInterval(function() {
			wait();
			cM = ICEcoder.getcMInstance();
			if (cM && ICEcoder.openFiles[1]== "/[NEW]") {
				testResult("+ GOOD",title+". Took "+t+"ms",x);
				setTimeout(function() {
					ICEcoder.closeTab(2,false,true);
					test.moveGoToLine();
				},1000);
			} else if (t==1000) {
				testResult("- FAIL",title+". Took "+t+"ms",x);
				testStopped();
			}
			o.p = ICEcoder.openFiles[1];
			t++;
		},1);
		result = ICEcoder.getRemoteFile('http://icecoder.net/latest-version');
	},

	moveGoToLine: function() {
		title = "Move and goto line";
		cM = ICEcoder.getcMInstance();
		cM.setValue('ICEcoder = "awesome";\n<script>\n<\/script>');
		ICEcoder.goToLine(1);
		line1Text = cM.lineInfo(cM.getCursor().line).text;
		setTimeout(function() {
			ICEcoder.moveLines('down');
			if (cM.getValue() == '<script>\nICEcoder = "awesome";\n<\/script>') {
				testResult("+ GOOD",title);
				setTimeout(function() {
					ICEcoder.closeTab(1,false,true);
					console.log('TEST COMPLETE!');
					alert('Test Complete!\n\nRan '+s+' of '+total+' tests OK.\nSee console for more details.');
				},200);
			} else {
				testResult("- FAIL",title);
				testStopped();
			}
		},200);
	}
}

function wait() {
o.watch("p", function (id, oldval, newval) {
		if (oldval != newval) {
			o.unwatch('p');
			return newval;
		}
	});
}

function testResult(result,output,timer) {
	if (result=="+ GOOD") {s++};
	displayResults(s);
	if (timer) {clearInterval(timer)}
	console.log(result+" "+output);
}

function displayResults(successful) {
	total = Object.keys(test).length;
	unitTestResults.innerHTML = "Ran "+successful+" of "+total+" tests OK";
}

function testStopped() {
	unitTestResults.innerHTML += " - Test stopped";
	alert("Test stopped, see console for details.");
}
</script>

</body>

</html>