#ICEcoder
##Web based IDE for smart web development

Early version of the web based IDE which allows for creation of websites in the web browser. Uses the brilliant CodeMirror for editing, with a plus a slick IDE wrapped around it to make the whole thing work.

###Features you'd expect
* Context aware code highlighting
* Supports HTML, CSS, JavaScript, PHP & Ruby
* Smart tab key system (selected text indents line)
* File manager
* Find & replace/replace all
* Tabs indicate current doc & changes made
* Open last files on load
* Code folding
* Web based, access from anywhere
* Free, open source & customisable

###Cool features you wouldn't expect
* Find & replace in current doc, open docs, files & filenames
* Found no of matches & current match position counter
* Indicates content type cursor is on
* Account login to keep certain files secure
* Settings to change functionality & editor theme
* Code Assist system
* Displays nest position of text cursor, hover to select, click to set cursor
* Nest structure OK/broken indicator
* Highlight word and press CTRL+I to Google search that
* Adds end tags for you in a context aware way (ie, begin typing <html> or <meta> or <li> and <div>, all handled differently)
* Can rename open files (whoaah!)
* CTRL+Enter open current webpage in new tab
* ESC = Comment/Uncomment line, incl partial lines
* Image viewer
* MySQL Database management via Adminer
* Backs up files every 10 mins or on click of plugin icon
* Click on color values such as red, #ff0000 or * RGBA(255,0,0,0.5) to see colored block
* CTRL+S+Enter Sticky tabs showing live edits

Is fully open source and I'd encourage you to take it, make it your own and customise to your hearts content! :)

Suitable for commercial & non-commercial projects, just let me know if it's useful to you and any cool customisations you make to it.

INSTALLATION

#####Step 1: Clone the repo

```
$git clone git@github:mattpass/ICEcoder
```

#####Step 2: Upload all the files
```
Linux or Windows hosting
Upload to a new sub-dir URL such as yourdomain.com/_coder
Set public write permissions on the lib/settings.php file
```

#####Step 3: Start coding
```
Visit the sub-dir URL in your browser and set a password
Now you're setup, auto-logged in too and ready to code
```

Plenty of comments included in the code to assist with understanding, customising etc.

Comments, improvements & feedback welcomed!