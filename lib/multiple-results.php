<?php include("settings.php");?>
<!DOCTYPE html>

<html>
<head>
<title>ICE Coder - <?php echo $versionNo;?> :: Multiple Results Screen</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="multiple-results.css">
</head>

<body class="results">

<div class="resultsPane">
	<h1 id="title"></h1>
	<div id="results"></div>
</div>
<?php if (isset($_GET['replace'])) { ?>
<div class="replaceAll" id="replaceAll" onClick="replaceAll()" style="opacity: 0.1">replace all</div>
<?php ;}; ?>

<script>
var resultsDisplay = "";
var foundTabArray = [];
var startTab = top.ICEcoder.selectedTab;
var rExp = new RegExp("<?php echo $_GET['find']; ?>","g");
for (var i=1;i<=top.ICEcoder.openFiles.length;i++) {
	top.ICEcoder.switchTab(i);
	var cM = top.ICEcoder.getcMInstance();
	var content = cM.getValue();
	if (content.match(rExp)) {
		resultsDisplay += '<a href="javascript:gotoTab('+i+')">'+ top.ICEcoder.openFiles[i-1]+ '</a><br><div id="foundCount'+i+'">Found '+content.match(rExp).length+' times</div>';
		<?php if (isset($_GET['replace'])) { ?>
		resultsDisplay += '<div class="replace" id="replace" onClick="replaceSingle('+i+');this.style.display=\'none\'">replace</div>';
		<?php ;}; ?>
		resultsDisplay += '<hr>';
		foundTabArray.push(i);
	}
}
if (startTab!=top.ICEcoder.selectedTab) {
	top.ICEcoder.switchTab(startTab);
}
foundTabArray.length==0 ? showHide = "hide" : showHide = "show";
top.ICEcoder.showHide(showHide,top.document.getElementById('blackMask'));
if (foundTabArray.length==0) {alert('No matches found')};
<?php if (isset($_GET['replace'])) { ?>
if (foundTabArray.length!=0) {document.getElementById('replaceAll').style.opacity = 1};
<?php ;}; ?>
foundTabArray.length >= 2 ? plural = "s" : plural = "";
document.getElementById('title').innerHTML = "'<?php echo $_GET['find']; ?>' found in "+foundTabArray.length+" file"+plural;
document.getElementById('results').innerHTML = resultsDisplay;

var gotoTab = function(tab) {
	top.ICEcoder.switchTab(tab);
	top.ICEcoder.showHide('hide',top.document.getElementById('blackMask'));
}

var replaceSingle = function(tab) {
	top.ICEcoder.switchTab(tab);
	cM = top.ICEcoder.getcMInstance();
	content = cM.getValue();
	cM.setValue(cM.getValue().replace(rExp,top.document.getElementById('replace').value));
	document.getElementById('foundCount'+tab).innerHTML = document.getElementById('foundCount'+tab).innerHTML.replace('Found','Replaced');
}

var replaceAll = function() {
	for (var i=0;i<=foundTabArray.length-1;i++) {
		replaceSingle(foundTabArray[i]);
	}
	top.ICEcoder.showHide('hide',top.document.getElementById('blackMask'));
}
</script>

</body>

</html>