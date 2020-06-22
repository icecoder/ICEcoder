<?php
include("lib/headers.php");
include("lib/settings.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>ICEcoder v <?php echo $ICEcoder["versionNo"];?> Terminal</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<meta name="viewport" content="width=device-width, initial-scale=0.5, user-scalable=no">
<link rel="stylesheet" type="text/css" href="lib/terminal.css?microtime=<?php echo microtime(true);?>" />  
<script type="text/javascript" language="JavaScript">
commandHistory = [];
currentLine = 0;

// Handle command history as user cycles with up/down arrow keys or types other keys
key = function(e) {
	if (!e) {
		var e = window.event;
	}

	// Up
	if (e.keyCode == 38) {
		// If blank, set a blank line as current
		if (document.getElementById('command').value == "") {
			currentCommand = "";
		}
		// If we have history and the last command in history isn't this one
		if (commandHistory[commandHistory.length-1] && commandHistory[commandHistory.length-1].replace("[[ICEcoder]]:") != currentCommand) {
			// Push or append as last item in array with string to indicate temp nature
			if (commandHistory[commandHistory.length-1].indexOf("[[ICEcoder]]:") !== 0) {
				commandHistory.push("[[ICEcoder]]:"+currentCommand);
			} else {
				commandHistory[commandHistory.length-1] = "[[ICEcoder]]:"+currentCommand;
			}
		}
		// If ee have at least some items in history, step back a level and display the previous command
		if (currentLine > 0) {
			currentLine--;
			document.getElementById('command').value = commandHistory[currentLine].replace("[[ICEcoder]]:","");
		}
	// Down
	// If the current line isn't the last in the array, take a step forward and display the command
	} else if(e.keyCode == 40 && currentLine < commandHistory.length-1) {
		currentLine++;
		document.getElementById('command').value = commandHistory[currentLine].replace("[[ICEcoder]]:","");
	// Set the current command value to that of the user input
	} else {
		currentCommand = document.getElementById('command').value;
	}
}

sendCmd = function(command) {
	// Send command over XHR for response and display
	xhr = top.ICEcoder.xhrObj();
	xhr.onreadystatechange=function() {
		if (xhr.readyState==4) {
			// OK reponse?
			if (xhr.status==200) {
				// Set the output to also include our response and scroll down to bottom
				var newOutput = document.createElement("DIV");
				newOutput.innerHTML = xhr.responseText;
				var cmdElem = document.getElementById("commandLine");
				cmdElem.parentNode.insertBefore(newOutput, cmdElem);
				top.document.getElementById("terminal").contentWindow.document.documentElement.scrollTop = document.getElementById('output').scrollHeight;

				// Add command onto end of history array or set as last item in array
				if (currentLine == 0 || commandHistory[commandHistory.length-1].indexOf("[[ICEcoder]]:") !== 0) {
					commandHistory.push(document.getElementById('command').value);
				} else {
					commandHistory[commandHistory.length-1] = document.getElementById('command').value;
				}

				// Set the current line to be the length of the array and clear the command
				currentLine = commandHistory.length;
				document.getElementById('command').value = "";
			}
		}
	};
	// Send the XHR request
	xhr.open("POST","lib/terminal-xhr.php?csrf="+top.ICEcoder.csrf,true);
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xhr.send('command='+encodeURIComponent(command));
}
</script>
</head>

<body>
<?php
$user = str_replace("\n","",shell_exec("whoami"));
$cwd = getcwd();
?>

<form name="shell" onsubmit="sendCmd(document.getElementById('command').value); return false" method="POST">
	<pre class="output" id="output"><span style="color: #0a0">ICEcoder v<?php echo $ICEcoder["versionNo"];?> terminal</span>
This is a full powered terminal, but will have the permissions of the '<?php echo $user;?>' user.
The more access rights you give that user, the more this terminal has.

<div class="commandLine" id="commandLine"><div class="user">&nbsp;&nbsp;<?php echo $user;?>&nbsp;</div><div class="path">&nbsp;<?php echo $cwd;?>&nbsp;</div>
<div class="promptVLine"></div><div class="promptHLine">─<div class="promptArrow">▶</div></div> <input type="text" class="command" id="command" onkeyup="key(event)" tabindex="1" autocomplete="off"></div></pre>
</form>

</body>
</html>
