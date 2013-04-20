#ICEcoder
##Web IDE / browser code editor awesomeness

ICEcoder is a web IDE / browser based code editor, which allows you to develop websites directly within the web browser. It uses the brilliant CodeMirror for code highlighting & editing, with a slick IDE wrapped around it to make the whole thing work.

<img src="http://icecoder.net/images/icecoder-v2-browser-code-editor.png" alt="ICEcoder web IDE">

###Requirements
You can run ICEcoder either online or locally, on Linux, Windows or Mac based platforms. The only requirement is to have PHP 5 available (5.3 recommended). You can have this either as a vanilla installation or via a program such as WAMP or XAMPP (for Windows) or MAMP (for Mac).

###Features you'd expect
* Context aware code highlighting
* Supports HTML, CSS, LESS, JavaScript, CoffeeScript, PHP & Ruby
* Smart tab key system (selected text indents line)
* File manager
* Find & replace/replace all
* Document tabs indicate current doc & changes made
* Code folding
* Open last files on load
* Web based, access from anywhere
* Free, open source & customisable

###Cool features you wouldn't expect
* Find & replace in current doc, open docs, files & filenames
* Found match & current position counter
* Indicates content type cursor is on
* Account login to keep certain files secure
* Restrict files, ban files and restrict by IP
* Settings to change functionality & editor theme
* Code Assist system
* Displays nest position of text cursor, hover to select, click to set cursor
* Nest structure OK/broken indicator
* Highlight word and press CTRL+I to Google search that
* Adds end tags as you type and in a context aware way
* Can rename open files (whoaah!)
* CTRL+Enter open current webpage in new tab
* CTRL+S+Enter opens a sticky tab to show live edits
* ESC = Comment/Uncomment line, incl partial lines
* Image viewer
* Colour preview block on CSS colours, ie red, #f00 or  RGBA(255,0,0,0.5)
* MySQL Database management via Adminer plugin
* Backs up files every 30 mins or on click of backup plugin icon
* Github repo syncing with ICErepo plugin
* Shell terminal
* JS Hint validation as you type
* Emmet snippet typing booster
* HTML & JavaScript code hinting
* Alphanumeric tab sorting
* Tag wrappers
* Config template
* Farbtastic color picker integrated

###Installation

####Step 1: Clone the repo

```
$ git clone git@github:mattpass/ICEcoder
```

####Step 2: Place in your document root (online or local)
```
Put in a new sub-dir URL such as yourdomain.com/_coder or localhost/_coder
Set public write permissions (757 recommended) on the 'backups' and 'lib' folders
```

####Step 3: Start coding
```
Visit the sub-dir URL in your browser and enter a password
Now you're setup, auto-logged in and ready to code!
```

Suitable for commercial & non-commercial projects, just let me know if it's useful to you and any cool customisations you make to it. I take no responsibility for anything, your usage is all down to you.

It's fully open source and MIT licensed. I'm happy for you to take it, make it your own and customise to your hearts content and/or contribute to this main repo! :)

Plenty of comments included in the code to assist with understanding, customising etc.

Comments, improvements & feedback welcomed!