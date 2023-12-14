# ICEcoder is for sale! Please contact info@icecoder.net. Serious offers only.

---

# ICEcoder

## Code editor awesomeness ...in your browser

ICEcoder is a browser based code editor, which provides a modern approach to building websites. By allowing you to code directly within the web browser, online or offline, it means you only need one program (your browser) to develop sites, plus can test on actual web servers. After development, you can also maintain the website easily, all of which make for speedy and smart development.

<img src="https://assets.icecoder.net/images/icecoder-8-1-browser-code-editor.png" alt="ICEcoder code editor">

### Requirements

You can run ICEcoder either online or locally, on Linux, Windows or Mac based platforms. The only requirement is to have PHP 7 available (7.4 recommended). You can have this either as a vanilla installation or via a program such as WAMP or XAMPP (for Windows) or MAMP (for Mac).

### Installation

#### Step 1: Get ICEcoder

Either download the zip or clone from Github into your wwwroot (document root) dir for your website (this is typically `/var/www/html/`) via:

```
$ git clone git@github.com:icecoder/icecoder /var/www/html/icecoder
```

#### Step 2: Set permissions on dirs & files

You'll need to ensure both the ICEcoder dir and the wwwroot dir have permissions to read, write and execute. This can be done by changing permissions (using `chmod`), but it it safer and so better, to use `chown`:

`chown -R www-data.www-data /var/www/html`

This will recursively set the `www-data` user as both the owner and group users for files on the `/var/www/html` dir (which ICEcoder dir is of course inside of, at say `/var/www/html/ICEcoder`).

#### Step 3: Start coding

Now you can visit `yoursite.com/ICEcoder` to view ICEcoder, sign in and start coding!

#### Tip: If using ICEcoder locally, you can use:

`php -S localhost:8080`

...to get PHP to start a simple web server. You can then visit `localhost:8080/ICEcoder`

#### Want to setup in other environments?

It's now possible to setup ICEcoder in a Docker container, via Composer, as an executable and more. Checkout https://icecoder.net/downloads for info on these setups!

#### It's free & open source for everyone!

Suitable for commercial & non-commercial projects, just let us know if it's useful to you and any cool customizations you make to it. We take no responsibility for anything, all usage is all down to you.

It's fully open source and MIT licensed. So we're happy for you to take it, make it your own and customize to your hearts content and/or contribute to this main repo! :)

Plenty of comments included in the code to assist with understanding, customizing etc.

Comments, improvements & feedback welcomed!
