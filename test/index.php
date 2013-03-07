<!DOCTYPE html>
<head>
<script src="object-watch.js"></script>
</head>

<body onLoad="runTests()">

<script>
ICEcoder=top.ICEcoder;
if(ICEcoder.openFiles.length>0) {ICEcoder.closeAllTabs();}

function runTests() {
	o = {p: 'start'};	// var used to test another var has changed
	s = 0;			// successful tests
	test.openFile();	// start the first test
	t = 0;			// tries
}

test = {
	openFile: function() {
		o.p = ICEcoder.openFiles[0];
		t = 0;
		x = setInterval(function() {
			wait();
			cM = ICEcoder.getcMInstance();
			if (cM && ICEcoder.openFiles[0]!="") {
				s++;
				displayResults(s);
				clearInterval(x);
				console.log('+ GOOD Opened file OK. Took '+(t*10)+'ms');
				test.updateDoc();
			} else if (t==100) {
				displayResults(s);
				clearInterval(x);
				console.log('- FAIL Failed to open file');
			}
			o.p = ICEcoder.openFiles[0];
			t++;
		},10);
		result = ICEcoder.openFile('<?php echo str_replace("\\","/",dirname(__FILE__))."/test-file1.txt";?>');
	},

	updateDoc: function() {
		cM.setValue('Updated');
		if (cM.getValue()=="Updated") {s++};
		displayResults(s);
		console.log((cM.getValue()=="Updated") ? '+ GOOD Change file contents OK' : '- FAIL Didnt update contents');
		test.saveFile();
	},

	saveFile: function() {
		o.p = ICEcoder.changedContent[0];
		t = 0;
		x = setInterval(function() {
			w = wait();
			cM = ICEcoder.getcMInstance();
			if (cM && ICEcoder.changedContent[0]==0) {
				s++;
				displayResults(s);
				clearInterval(x);
				console.log('+ GOOD Saved file OK. Took '+(t*10)+'ms');
				test.closeTab();
			} else if (t==100) {
				displayResults(s);
				clearInterval(x);
				console.log('- FAIL Didnt save file');
			}
			o.p = ICEcoder.changedContent[0];
			t++;
		},10);
		result = ICEcoder.saveFile();
	},

	closeTab: function() {
		ICEcoder.closeTab(1);
		if (cM.getValue()=="Updated") {s++};
		displayResults(s);
		console.log(ICEcoder.openFiles.length==0 ? '+ GOOD Closed file OK' : '- FAIL Didnt close file OK');
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

function displayResults(successful) {
	total = Object.keys(test).length;
	top.ICEcoder.content.contentWindow.document.getElementById('unitTestResults').innerHTML = "Ran "+successful+" of "+total+" tests OK";
}
</script>

</body>

</html>