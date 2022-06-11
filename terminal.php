<?php
include "lib/headers.php";
include "lib/settings.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>ICEcoder <?php echo $ICEcoder["versionNo"];?> Terminal</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<meta name="viewport" content="width=device-width, initial-scale=0.5, user-scalable=no">
<link rel="stylesheet" type="text/css" href="<?php echo $settingsClass->assetsRoot;?>/css/terminal.css?microtime=<?php echo microtime(true);?>" />
<script type="text/javascript" language="JavaScript">
commandHistory = [];
currentLine = 0;

// Handle command history as user cycles with up/down arrow keys or types other keys
key = function(e) {
	if (!e) {
		var e = window.event;
	}

	// Up
	if (38 === e.keyCode) {
		// If blank, set a blank line as current
		if ("" == document.getElementById('command').value) {
			currentCommand = "";
		}
		// If we have history and the last command in history isn't this one
		if (commandHistory[commandHistory.length - 1] && commandHistory[commandHistory.length - 1].replace("[[ICEcoder]]:", "") !== currentCommand) {
			// Push or append as last item in array with string to indicate temp nature
			if (0 !== commandHistory[commandHistory.length - 1].indexOf("[[ICEcoder]]:")) {
				commandHistory.push("[[ICEcoder]]:" + currentCommand);
			} else {
				commandHistory[commandHistory.length - 1] = "[[ICEcoder]]:" + currentCommand;
			}
		}
		// If we have at least some items in history, step back a level and display the previous command
		if (0 < currentLine) {
			currentLine--;
			document.getElementById('command').value = commandHistory[currentLine].replace("[[ICEcoder]]:", "");
		}
	// Down
	// If the current line isn't the last in the array, take a step forward and display the command
	} else if(40 === e.keyCode && currentLine < commandHistory.length - 1) {
		currentLine++;
		document.getElementById('command').value = commandHistory[currentLine].replace("[[ICEcoder]]:", "");
	// Set the current command value to that of the user input
	} else {
		currentCommand = document.getElementById('command').value;
	}
}

sendCmd = function(command) {
	// Send command over XHR for response and display
	xhr = parent.ICEcoder.xhrObj();
	xhr.onreadystatechange = function() {
		if (4 === xhr.readyState) {
			// OK reponse?
			if (200 === xhr.status) {
				// Set the output to also include our response and scroll down to bottom
				var newOutput = document.createElement("DIV");
				responseText = xhr.responseText;
				responseJSON = JSON.parse(responseText);
				newOutput.innerHTML = responseJSON.output;
				document.getElementById("user").innerHTML = "&nbsp;&nbsp" + responseJSON.user + "&nbsp;";
				document.getElementById("cwd").innerHTML = "&nbsp;" + responseJSON.cwd + "&nbsp;";
				var cmdElem = document.getElementById("commandLine");
				cmdElem.parentNode.insertBefore(newOutput, cmdElem);
				parent.document.getElementById("terminal").contentWindow.document.documentElement.scrollTop = document.getElementById('output').scrollHeight;

				// Add command onto end of history array or set as last item in array
				if (0 === currentLine || 0 !== commandHistory[commandHistory.length - 1].indexOf("[[ICEcoder]]:")) {
					commandHistory.push(document.getElementById('command').value);
				} else {
					commandHistory[commandHistory.length - 1] = document.getElementById('command').value;
				}

				// Set the current line to be the length of the array and clear the command
				currentLine = commandHistory.length;
				document.getElementById('command').value = "";
			}
		}
	};

	// Send the XHR request
	xhr.open("POST", parent.ICEcoder.iceLoc + "/lib/terminal-xhr.php?csrf=" + parent.ICEcoder.csrf, true);
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xhr.send('command=' + encodeURIComponent(command));
}
</script>
</head>

<body onclick="document.getElementById('command').focus()">
<?php
// If we have no cwd set in session, set it now
if (false === isset($_SESSION['cwd'])) {
	$_SESSION['cwd'] = $docRoot . $iceRoot;
}

// Change to cwd
chdir($_SESSION['cwd']);

if (true === $systemClass->functionEnabled("shell_exec")) {
	// Get current user and cwd
	$user = str_replace("\n", "", shell_exec("whoami"));
	$cwd = str_replace("\n", "", shell_exec("pwd"));
?>

<form name="shell" onsubmit="sendCmd(document.getElementById('command').value); return false" method="POST">
	<pre class="output" id="output"><span style="color: #0a0">ICEcoder <?php echo $ICEcoder["versionNo"];?> terminal</span>
This is a full powered terminal, but will have the permissions of the '<?php echo $user;?>' user.
The more access rights you give that user, the more this terminal has.

<div class="commandLine" id="commandLine"><div class="user" id="user">&nbsp;&nbsp;<?php echo $user;?>&nbsp;</div><div class="cwd" id="cwd">&nbsp;<?php echo $cwd;?>&nbsp;</div> : <?php echo date("H:m:s");?><br><div class="promptVLine"></div><div class="promptHLine">─<div class="promptArrow">▶</div></div> <input type="text" class="command" id="command" onkeyup="key(event)" tabindex="1" autocomplete="off"></div></pre>
</form>
<?php
} else {
?>
<pre class="output" id="output">shell_exec not available on the server, unable to use terminal.</pre>
<?php } ?>

</body>
</html>
