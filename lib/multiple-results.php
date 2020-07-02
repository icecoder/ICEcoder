<?php
include "headers.php";
include "settings.php";
$t = $text['multiple-results'];
?>
<?php
if (true === isset($_GET['selectedFiles'])) {
	$selectedFiles = explode(":", $_GET['selectedFiles']);
}
?>
<!DOCTYPE html>

<html>
<head>
<title>ICEcoder <?php echo $ICEcoder["versionNo"];?> multiple results screen</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" type="text/css" href="../assets/css/resets.css?microtime=<?php echo microtime(true);?>">
<link rel="stylesheet" type="text/css" href="../assets/css/multiple-results.css?microtime=<?php echo microtime(true);?>">
</head>

<body class="results" onkeyup="parent.ICEcoder.handleModalKeyUp(event, 'multipleResults')" onload="parent.document.getElementById('loadingMask').style.visibility = 'hidden'; this.focus();">

<h1 id="title"></h1>
<div class="resultsPane" id="resultsPane">
    <div id="results"></div>
</div>

<?php
if (true == isset($_GET['replace'])) { ?>
<div class="replaceAll" id="replaceAll" onClick="<?php
if (true === isset($_GET['target']) && false !== strpos($_GET['target'], "filenames")) {
    echo 'renameAll()';
} else if (true === isset($_GET['target']) && false !== strpos($_GET['target'], "files")) {
    echo 'replaceInFilesAll()';
} else {
    echo 'replaceAll()';
}
?>" style="opacity: 0.1"><?php echo true === isset($_GET['target']) && false !== strpos($_GET['target'], "filenames") ? $t['rename all'] : $t['replace all'];?></div>
<?php ;}; ?>

<script>
    <?php if (false === isset($_GET['replace'])) { ?>
    document.getElementById('resultsPane').style.height = "380px";
    <?php ;}; ?>
    let resultsDisplay = "";
    let foundArray = [];
    let foundInSelected = false;
    const userTarget = parent.document.findAndReplace.target.value;
    const findText = parent.document.findAndReplace.find.value;
    <?php
    $findText = str_replace("ICEcoder:", "", str_replace("&#39;", "\'", $_GET['find']));
    // Find in open docs?
    if (false === isset($_GET['target'])) {
        $targetName = $t['document'];
        ?>
        let startTab = parent.ICEcoder.selectedTab;
        const rExp = new RegExp(findText, "gi");
        for (let i = 1; i <= parent.ICEcoder.openFiles.length; i++) {
            parent.ICEcoder.switchTab(i);
            const cM = parent.ICEcoder.getcMInstance();
            const content = cM.getValue();
            if (content.match(rExp)) {
                resultsDisplay +=
                    '<a href="javascript:gotoTab(' + i + '); goFind()">' +
                    parent.ICEcoder.openFiles[i - 1] +
                    '</a><br><div id="foundCount' + i + '"><?php echo $t['Found'];?> ' +
                    content.match(rExp).length +
                    ' <?php echo $t['times'];?></div>';
                <?php if (isset($_GET['replace'])) { ?>
                resultsDisplay +=
                    '<div class="replace" id="replace" onClick="replaceSingle(' + i + '); this.style.display = \'none\'"><?php echo $t['replace'];?></div>';
                <?php ;}; ?>
                resultsDisplay += '<hr>';
                foundArray.push(i);
            }
        }
        if (startTab !== parent.ICEcoder.selectedTab) {
            parent.ICEcoder.switchTab(startTab);
        }
        <?php
        // Find in files or filenames
    } else {
        if (0 < strpos($_GET['target'], "filenames")) {
        $targetName = $t['file folder'];
        ?>
        const spansArray = parent.ICEcoder.filesFrame.contentWindow.document.getElementsByTagName('span');
        for (let i = 0; i < spansArray.length; i++) {
            let foundInSelected = false;
            const targetURL = spansArray[i].id.replace(/\|/g, "/").toLowerCase();
            if (
                targetURL.lastIndexOf(findText.toLowerCase()) > targetURL.lastIndexOf("/")
                && -1 < targetURL.indexOf(findText.toLowerCase()) && targetURL.indexOf('_perms')>-1) {
                if (-1 < userTarget.indexOf("selected")) {
                    for (let j = 0; j < parent.ICEcoder.selectedFiles.length; j++) {
                        if (
                            0 === targetURL.replace(/\//g, "|").indexOf(parent.ICEcoder.selectedFiles[j].replace(/\//g, "|").replace(/_perms/g, ""))
                            && (
                            targetURL.replace(/\|/g, "/").replace(/_perms/g, "") === parent.ICEcoder.selectedFiles[j].replace(/\|/g, "/").replace(/_perms/g, "")
                            ||
                            (targetURL.replace(/\|/g, "/").split("/").length > parent.ICEcoder.selectedFiles[j].replace(/\|/g, "/").split("/").length && "/" === targetURL.charAt(parent.ICEcoder.selectedFiles[j].length)))) {
                            foundInSelected = true;
                        }
                    }
                }
                if (-1 < userTarget.indexOf("all") || (-1 < userTarget.indexOf("selected") && foundInSelected)) {
                    resultsDisplay +=
                        '<a href="javascript:parent.ICEcoder.openFile(\'<?php echo $docRoot;?>' +
                        targetURL.replace(/\|/g, "/").replace(/_perms/g,"") +
                        '\');parent.ICEcoder.goFindAfterOpenInt = setInterval(function(){goFindAfterOpen(\'<?php echo $docRoot;?>' +
                        targetURL.replace(/\|/g, "/").replace(/_perms/g, "") +
                        '\')}, 20);parent.ICEcoder.showHide(\'hide\', parent.document.getElementById(\'blackMask\'))">';
                    resultsDisplay +=
                        targetURL.replace(/\|/g, "/").replace(/_perms/g, "").replace(/<?php
                            echo str_replace("/", "\/",strtolower($findText)); ?>/g, "<b>" +
                            findText.toLowerCase() + "</b>");
                        resultsDisplay += '</a><br>';
                    <?php if (false === isset($_GET['replace'])) { ?>
                    resultsDisplay += '<div id="foundCount' + i +'">' + spansArray[i].innerHTML + '</div>';
                    <?php ;} else { ?>
                    resultsDisplay +=
                        '<div id="foundCount' + i + '">' + spansArray[i].innerHTML +
                        ', <?php echo $t['rename to'];?> ' +
                        targetURL.replace(/\|/g, "/").replace(/_perms/g, "").replace(/<?php echo str_replace("/", "\/",strtolower($findText)); ?>/g,"<b><?php
                            if (isset($_GET['replace'])) {echo strtolower($_GET['replace']);};
                        ?></b>")+'</div>';
                        <?php
                        ;};
                        if (true === isset($_GET['replace'])) { ?>
                        resultsDisplay += '<div class="replace" id="replace" onClick="renameSingle(' + i + ');this.style.display=\'none\'"><?php echo $t['rename'];?></div>';
                    <?php ;}; ?>
                    resultsDisplay += '<hr>';
                    foundArray.push(i);
                }
            }
        }
        <?php
        } else {
        $targetName = $t['file'];
        $r = 0;
        function phpGrep($q, $path, $base) {
            $fp = opendir($path);
            global $t, $r, $ICEcoder, $selectedFiles, $docRoot, $ICEcoderDir;
            if (false === isset($ret)) {$ret="";};
            $slash = -1 < strpos($path, "\\") ? "\\" : "/";
            while ($f = readdir($fp)) {
                if (preg_match("#^\.+$#", $f)) continue;
                $fullPath = $path . $slash . $f;
                if (is_dir($fullPath)) {
                    $ret .= phpGrep($q, $fullPath, $base);
                } else if(stristr(toUTF8noBOM(getData($fullPath), false), $q)) {
                    $bFile = false;
                    $foundInSelFile = false;
                    // Exclude banned files
                    for ($i = 0;$i < count($ICEcoder['bannedFiles']); $i++) {
                        if (false !== strpos($f, str_replace("*", "", $ICEcoder['bannedFiles'][$i]))) {
                            $bFile = true;
                        };
                    }
                    // Exclude the folder ICEcoder is running from
                    $rootPrefix = '/' . str_replace("/", "\/", preg_quote(str_replace("\\", "/", $docRoot))) . '/';
                    $localPath = preg_replace($rootPrefix, '', $fullPath, 1);
                    if (0 === strpos($localPath, $ICEcoderDir)) {
                        $bFile = true;
                    }
                    $findPath = str_replace($base, "", $fullPath);
                    for ($i = 0; $i < count($selectedFiles); $i++) {
                        $stringExtra = "|" !== $selectedFiles[$i] ? "/" : "";
                        if (strpos($findPath . $stringExtra, str_replace("|", "/", $selectedFiles[$i]) . $stringExtra) === 0) {
                            $foundInSelFile = true;
                        }
                    }
                    if (false === $bFile && (0 === count($selectedFiles) || 0 < count($selectedFiles) && true === $foundInSelFile)) {
                        $ret .= "<a href=\\\"javascript:parent.ICEcoder.openFile('" . $fullPath . "');parent.ICEcoder.goFindAfterOpenInt = setInterval(function(){goFindAfterOpen('" . $fullPath . "')}, 20);parent.ICEcoder.showHide('hide',parent.document.getElementById('blackMask'))\\\">";
                        $ret .= str_replace($base, "", $fullPath) . "</a><div id=\\\"foundCount" . $r . "\\\">" .
                            $t['Found'] . " " . substr_count(strtolower(toUTF8noBOM(getData($fullPath), false)), strtolower($q)) . " " . $t['times'] . "</div>";
                        if (isset($_GET['replace'])) {
                            $ret .= "<div class=\\\"replace\\\" id=\\\"replace\\\" onClick=\\\"replaceInFileSingle('" . $fullPath . "'); this.style.display=\'none\'\\\">" . $t['replace'] . "</div>";
                        };
                        $ret .= '<hr>';
                        echo 'foundArray.push("' . $fullPath . '");' . PHP_EOL;
                        $r++;
                    }
                }
            }
            return $ret;
        }

        $results = phpGrep($findText, $docRoot . $iceRoot, $docRoot . $iceRoot);
        echo 'resultsDisplay += "' . $results . '";';
        ?>
        <?php
        }
    }
    ?>
    showHide = 0 === foundArray.length ? "hide" : "show";
    parent.ICEcoder.showHide(showHide, parent.document.getElementById('blackMask'));
    if (0 === foundArray.length) {
        parent.ICEcoder.message('<?php echo $t['No matches found'];?>')
    };
    <?php if (isset($_GET['replace'])) { ?>
        if (0 !== foundArray.length) {
            document.getElementById('replaceAll').style.opacity = 1
        };
    <?php ;}; ?>
    plural = foundArray.length >= 2 ? "s" : "";
    targetName = "<?php echo $targetName;?>";
    selectedText = foundInSelected ? "<?php echo $t['selected'];?> " : "";
    document.getElementById('title').innerHTML =
        findText.replace(/&/g, "&amp;").replace(/>/g, "&gt;").replace(/</g, "&lt;").replace(/"/g, "&quot;").replace(/'/g, "&apos;") +
        " <?php echo $t['found in'];?> " + foundArray.length + " " + selectedText + targetName + plural;
    document.getElementById('results').innerHTML = resultsDisplay;

    const gotoTab = function(tab) {
        parent.ICEcoder.switchTab(tab);
        parent.ICEcoder.showHide('hide',parent.document.getElementById('blackMask'));
    };

    const replaceSingle = function(tab) {
        parent.ICEcoder.switchTab(tab);
        const cM = parent.ICEcoder.getcMInstance();
        cM.setValue(cM.getValue().replace(rExp, parent.document.getElementById('replace').value));
        document.getElementById('foundCount' + tab).innerHTML = document.getElementById('foundCount' + tab).innerHTML.replace('<?php echo $t['Found'];?>', '<?php echo $t['Replaced'];?>');
    };

    const replaceAll = function() {
        for (let i = 0; i <= foundArray.length - 1; i++) {
            replaceSingle(foundArray[i]);
        }
        parent.ICEcoder.showHide('hide', parent.document.getElementById('blackMask'));
    };

    const replaceInFileSingle = function(fileRef) {
        parent.ICEcoder.replaceInFile(fileRef, findText, '<?php if (isset($_GET['replace'])) {echo $_GET['replace'];}; ?>');
    };

    const replaceInFilesAll = function() {
        for (let i = 0;i <= foundArray.length - 1; i++) {
            replaceInFileSingle(foundArray[i]);
        }
        parent.ICEcoder.showHide('hide', parent.document.getElementById('blackMask'));
    };

    const renameSingle = function(arrayRef) {
        fileRef = spansArray[arrayRef].id.replace(/\|/g, "/").replace(/_perms/g, "");
        const rExp = new RegExp(findText, "gi");
        newName = spansArray[arrayRef].id.replace(/\|/g, "/").replace(/_perms/g, "").replace(rExp, "<?php if (isset($_GET['replace'])) {echo $_GET['replace'];}; ?>");
        parent.ICEcoder.renameFile(fileRef,newName);
    };

    const renameAll = function() {
        for (let i = 0; i<= foundArray.length - 1; i++) {
            renameSingle(foundArray[i]);
        }
        parent.ICEcoder.showHide('hide', parent.document.getElementById('blackMask'));
    };

    const goFindAfterOpen = function(fileName) {
        if (parent.ICEcoder.openFiles[parent.ICEcoder.selectedTab - 1] === fileName.replace(parent.docRoot, "") && false === parent.ICEcoder.loadingFile) {
            goFind();
            clearInterval(parent.ICEcoder.goFindAfterOpenInt);
        }
    };

    const goFind = function() {
        // Change options back to finding only in this document
        parent.document.findAndReplace.connector.selectedIndex = 0;
        parent.ICEcoder.findReplaceOptions();
        parent.document.findAndReplace.target.selectedIndex = 0;
        // Re-show the results stats
        parent.document.getElementById('results').style.display = 'inline-block';
        // Action the find and then focus on find input box
        setTimeout(function() {
            parent.ICEcoder.findReplace(findText, true, false, false);
            parent.document.getElementById("find").focus();
        }, 0);
    };
</script>

</body>

</html>
