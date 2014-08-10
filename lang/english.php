<?php
// English language translation
// by: @mattpass (GitHub)
//     @mattpass (Twitter)

// Please preserve formatting, line breaks, special characters, anything in <tags> and HTML equivalents (eg &amp;). Translations on right side.

$text = array(

// / [ROOT LEVEL]

	"editor" =>
	array(
		"Click icons for..."		=> "<b>Click icons<br>for help &amp;<br>usage info</b>",
		"server"			=> "server",
		"Server name, OS..."		=> "Server name, OS & IP:",
		"Root"				=> "Root:",
		"ICEcoder root"			=> "ICEcoder root:",
		"PHP version"			=> "PHP version:",
		"Date & time"			=> "Date & time:",
		"your device"			=> "your device",
		"Browser"			=> "Browser:",
		"Your IP"			=> "Your IP:",
		"files"				=> "files",
		"Last 10 files..."		=> "Last 10 files opened:",
		"none"				=> "[none]",
		"test suite"			=> "test suite",
		"Run unit tests"		=> "Run unit tests",
		"dev mode"			=> "dev mode",
		"Status"			=> "Status",
		"Using"				=> "Using",
		"You can switch..."		=> "You can switch dev mode on/off
in lib/config__settings.php",
		"results"			=> "results"

	),

	"files" =>
	array(
		"Lock"				=> "Lock",
		"Refresh"			=> "Refresh",
		"ROOT"				=> "[ROOT]"

	),

	"index" =>
	array(
		"UPDATE INFO"			=> "UPDATE INFO",
		"now available"			=> "now available",
		"Your version is"		=> "Your version is",
		"Update now"			=> "Update now",
		"You have some..."		=> "You have some unsaved changes",
		"working"			=> "working",
		"Color picker"			=> "Color picker",
		"New File"			=> "New File",
		"New Folder"			=> "New Folder",
		"Upload File(s)"		=> "Upload File(s)",
		"Paste"				=> "Paste",
		"Open"				=> "Open",
		"Copy"				=> "Copy",
		"Duplicate"			=> "Duplicate",
		"Delete"			=> "Delete",
		"Rename"			=> "Rename",
		"View Webpage"			=> "View Webpage",
		"Download"			=> "Download",
		"Properties"			=> "Properties",
		"File"				=> "File",
		"Edit"				=> "Edit",
		"Remote"			=> "Remote",
		"Help"				=> "Help",
		"Save"				=> "Save",
		"Save As"			=> "Save As",
		"Live Preview"			=> "Live Preview",
		"Upload"			=> "Upload",
		"Zip"				=> "Zip",
		"Print"				=> "Print",
		"Fullscreen toggle"		=> "Fullscreen toggle",
		"Logout"			=> "Logout",
		"Undo"				=> "Undo",
		"Redo"				=> "Redo",
		"Indent more"			=> "Indent more",
		"Indent less"			=> "Indent less",
		"Autocomplete"			=> "Autocomplete",
		"Comment/Uncomment"		=> "Comment/Uncomment",
		"Jump to Definition"		=> "Jump to Definition",
		"Manual"			=> "Manual",
		"Shortcuts"			=> "Shortcuts",
		"Settings"			=> "Settings",
		"Search for selected"		=> "Search for selected",
		"website"			=> "website",
		"Close all tabs"		=> "Close all tabs",
		"Alphabetize tabs"		=> "Alphabetize tabs",
		"Find"				=> "Find",
		"in"				=> "in",
		"and"				=> "and",
		"replace"			=> "replace",
		"replace all"			=> "replace all",
		"this document"			=> "this document",
		"open documents"		=> "open documents",
		"all files"			=> "all files",
		"all filenames"			=> "all filenames",
		"Turn on/off..."		=> "Turn on/off code assist",
		"Code Assist"			=> "Code Assist",
		"Go to Line"			=> "Go to Line",
		"View"				=> "View",
		"Bug reporting not active"	=> "Bug reporting not active"
	),

// /LIB

	"bug-files-check" =>
	array(
		"Found in"			=> "Found in:"
	),

	"file-control" =>
	array(
		"Sorry"				=> "Sorry",
		"does not seem..."		=> "does not seem to exist on the server",
		"Sorry, could not..."		=> "Sorry, could not get contents of",
		"Sorry, cannot create..."	=> "Sorry, cannot create folder at",
		"Sorry, cannot copy"		=> "Sorry, cannot copy",
		"into"				=> "into",
		"Uploaded file(s) OK"		=> "Uploaded file(s) OK",
		"Sorry, cannot upload"		=> "Sorry, cannot upload",
		"Sorry, cannot upload..."	=> "Sorry, cannot upload whilst in demo mode",
		"Sorry, cannot rename"		=> "Sorry, cannot rename",
		"Maybe public write..."		=> "Maybe public write permissions needed on this or parent folder?",
		"Sorry, cannot move"		=> "Sorry, cannot move",
		"Sorry, cannot save"		=> "Sorry, cannot save",
		"Sorry, cannot replace..."	=> "Sorry, cannot replace text in",
		"Sorry, cannot change..."	=> "Sorry, cannot change permissions on",
		"Sorry, cannot delete..."	=> "Sorry, cannot delete the root level",
		"Sorry, cannot delete"		=> "Sorry, cannot delete",
		"Sorry, this file..."		=> "Sorry, this file has changed, cannot save",
		"Reload this file..."		=> "Reload this file and copy your version to a new document?",
		"There was a..."		=> "There was a tech hiccup, likely something was not quite ready. So ICEcoder reloaded its file control again.",
		"displayed at"			=> "displayed at",
		"Enter filename to..."		=> "Enter filename to save at",
		"That file exists..."		=> "That file exists already, overwrite?",
		"Saving"			=> "Saving"
	),

	"get-branch" =>
	array(
		"There are no..."		=> "There are no differences between the local and GitHub repo. Switch back to regular mode?",
		"Sorry, there was..."		=> "Sorry, there was an error, code:",
		"Your local folder..."		=> "Your local folder is empty, would you like to clone"
	),

	"github-manager" =>
	array(
		"Sorry, cannot create..."	=> "Sorry, cannot create folder at",
		"Cannot update config..."	=> "Cannot update config file. Please set public write permissions on",
		"and try again"			=> "and try again",
		"saving github paths"		=> "saving github paths...",
		"github paths"			=> "github paths",
		"Choose existing path"		=> "Choose existing path",
		"Local path"			=> "Local path",
		"Remote GitHub path"		=> "Remote GitHub path",
		"Choose"			=> "Choose",
		"Set local and..."		=> "Set local and remote path to blank to remove",
		"Update"			=> "Update",
		"Add new path"			=> "Add new path",
		"Add"				=> "Add",
		"Usage Info"			=> "Usage Info:",
		"Enter relative local..."	=> "Enter relative local paths (eg /server/myfiles) and absolute GitHub paths (eg https://github.com/user/repo or https://github.com/user/repo/tree/branch for branches), as per the examples. With this done you have established the source paths at both locations, as a pair.",
		"You can then..."		=> "You can then choose a path pair and this then becomes your new root path in ICEcoder.",
		"The file manager..."		=> "The file manager then displays a new GitHub icon, which you can click on to perform and show a diff check between the 2 sources. These diffs can then be committed and pushed to the remote path at GitHub or cloned to your local path, to sync your files.",
		"If you want..."		=> "If you want to set another root path, this can be done in the Settings screen."
	),

	"github" =>
	array(
		"Sorry, you do..."		=> "Sorry, you do not appear to have OpenSSL loaded on your PHP instance, so https is not available. This is required for GitHub data transfer, please amend php.ini settings, restart your server and try again"
	),

	"headers" =>
	array(
		"Bad CSRF token..."		=> "Baddd CSRF token. Please report the error info at https://github.com/mattpass/ICEcoder so it can be fixed."
	),

	"help" =>
	array(
		"shortcuts"			=> "shortcuts",
		"Within document"		=> "Within document",
		"On Tabs"			=> "On Tabs",
		"Within file manager"		=> "Within file manager",
		"Anywhere"			=> "Anywhere",
		"Space"				=> "Space",
		"Click"				=> "Click",
		"or"				=> "or",
		"Left click"			=> "Left click",
		"Middle click"			=> "Middle click",
		"Double click tap..."		=> "Double click / tap (mobile)",
		"Right click"			=> "Right click",
		"Middle scrollwheel"		=> "Middle scrollwheel",
		"Drag"				=> "Drag",
		"Autocomplete add snippet"	=> "Autocomplete / add snippet",
		"Multiple select"		=> "Multiple select",
		"Move line up"			=> "Move line up",
		"Move line down"		=> "Move line down",
		"Duplicate lines"		=> "Duplicate line(s)",
		"Remove lines"			=> "Remove line(s)",
		"Insert line before"		=> "Insert line before",
		"Insert line after"		=> "Insert line after",
		"Search for selected"		=> "Search for selected",
		"Jump to definition"		=> "Jump to definition / jump back",
		"Comment uncomment"		=> "Comment / uncomment",
		"Insert tab indent"		=> "Insert tab / indent selected",
		"Wrap with div"			=> "Wrap with &lt;div&gt;",
		"Wrap with span"		=> "Wrap with &lt;span&gt;",
		"Wrap unwrap p"			=> "Wrap / unwrap with &lt;p&gt;",
		"Wrap unwrap a"			=> "Wrap / unwrap with &lt;a&gt;",
		"Wrap unwrap b"			=> "Wrap / unwrap with &lt;b&gt;",
		"Wrap unwrap i"			=> "Wrap / unwrap with &lt;i&gt;",
		"Wrap unwrap strong"		=> "Wrap / unwrap with &lt;strong&gt;",
		"Wrap unwrap em"		=> "Wrap / unwrap with &lt;em&gt;",
		"Wrap unwrap h1..."		=> "Wrap / unwrap with &lt;h1&gt; - &lt;h3&gt;",
		"End line with..."		=> "End line with &lt;br&gt;",
		"Close tab"			=> "Close tab",
		"Select file folder"		=> "Select file / folder",
		"Open file"			=> "Open file",
		"Range select"			=> "Range select",
		"Options for selected"		=> "Options for selected",
		"Delete selected"		=> "Delete selected",
		"Next previous tab"		=> "Next / previous tab",
		"Next tab"			=> "Next tab",
		"Previous tab"			=> "Previous tab",
		"New tab"			=> "New tab",
		"Close current tab"		=> "Close current tab",
		"Open file prompt"		=> "Open file prompt",
		"Find"				=> "Find",
		"Focus on Go..."		=> "Focus on Go to line input",
		"Save"				=> "Save",
		"Save as"			=> "Save as...",
		"View webpage"			=> "View webpage",
		"Contract expand file..."	=> "Contract / expand file manager",
		"Fold unfold current..."	=> "Fold / unfold current line",
		"Refocus on document"		=> "Refocus on document",
		"Cancel tasks"			=> "Cancel tasks"
	),

	"login" =>
	array(
		"set password"			=> "set password",
		"login"				=> "login",
		"To disable registration..."	=> "To disable registration mode, open the settings menu or open lib/config___settings.php and change enableRegistration to false then reload this page",
		"Registration mode enabled"	=> "Registration mode enabled",
		"auto-check for updates"	=> "auto-check for updates",
		"To put into..."		=> "To put into multi-user mode, open the settings menu or open lib/config___settings.php and change multiUser to true then reload this page",
		"multi-user"			=> "multi-user"
	),

	"multiple-results" =>
	array(
		"rename all"			=> "rename all",
		"replace all"			=> "replace all",
		"document"			=> "document",
		"Found"				=> "Found",
		"times"				=> "times",
		"replace"			=> "replace",
		"file folder"			=> "file/folder",
		"rename to"			=> "rename to",
		"rename"			=> "rename",
		"file"				=> "file",
		"No matches found"		=> "No matches found",
		"selected"			=> "selected",
		"found in"			=> "found in",
		"Replaced"			=> "Replaced"
	)

);
?>