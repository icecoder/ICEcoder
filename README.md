#ICEcoder
##Web IDE / browser code editor awesomeness

ICEcoder is a web IDE / browser based code editor, which allows you to develop websites directly within the web browser. It uses the brilliant CodeMirror for code highlighting & editing, with a slick IDE wrapped around it to make the whole thing work.

<img src="https://icecoder.net/images/icecoder-v5-2-browser-code-editor.png" alt="ICEcoder web IDE">

###Requirements
You can run ICEcoder either online or locally, on Linux, Windows or Mac based platforms. The only requirement is to have PHP 5 available (5.3 recommended). You can have this either as a vanilla installation or via a program such as WAMP or XAMPP (for Windows) or MAMP (for Mac).

###Installation

####Step 1: Get ICEcoder
Either download the zip or clone from Github using:

```
$ git clone git://github.com/mattpass/ICEcoder
```

####Step 2: Place in your document root (online or local)
* Put in a new sub-dir URL such as yourdomain.com/ICEcoder or localhost/ICEcoder
* Set write permissions (757 or 775 depending on your system) on the 'backups', 'lib', 'plugins', 'test' and 'tmp' folders

*(Note: A small number of web servers give an internal server error here, if you get this, try 755 instead)*

####Step 3: Start coding
* Visit the sub-dir URL in your browser and enter a password

**Now you're setup, auto-logged in and ready to code!**

Suitable for commercial & non-commercial projects, just let me know if it's useful to you and any cool customisations you make to it. I take no responsibility for anything, your usage is all down to you.

It's fully open source and MIT licensed. I'm happy for you to take it, make it your own and customise to your hearts content and/or contribute to this main repo! :)

Plenty of comments included in the code to assist with understanding, customising etc.

Comments, improvements & feedback welcomed!
