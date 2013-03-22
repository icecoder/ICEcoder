<?php include("lib/settings.php");?>
<!DOCTYPE html>

<html style="margin: 0" onMouseDown="top.ICEcoder.mouseDown=true" onMouseUp="top.ICEcoder.mouseDown=false; top.ICEcoder.tabDragEnd()" onMouseMove="if(top.ICEcoder) {top.ICEcoder.getMouseXY(event,'editor');top.ICEcoder.canResizeFilesW()}">
<head>
<title>ICEcoder v <?php echo $ICEcoder["versionNo"];?> editor</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" href="<?php echo $ICEcoder["codeMirrorDir"]; ?>/lib/codemirror.css">
<link rel="stylesheet" href="<?php echo $ICEcoder["codeMirrorDir"]; ?>/addon/hint/show-hint.css">
<!--
codemirror-compressed.js
incls:	codemirror
modes:	clike, coffeescript, css, javascript, less, php, ruby & xml
utils:	foldcode, xml-fold, brace-fold, show-hint, javascript-hint, html-hint, closetag, searchcursor, match-highlighter
//-->
<script src="<?php echo $ICEcoder["codeMirrorDir"]; ?>/lib/codemirror-compressed.js"></script>
<script>
// -- HOTFIX
// show-hint.js not in compression helper, so added here
CodeMirror.showHint = function(cm, getHints, options) {
  if (!options) options = {};
  var startCh = cm.getCursor().ch, continued = false;
  var closeOn = options.closeCharacters || /[\s()\[\]{};:]/;

  function startHinting() {
    // We want a single cursor position.
    if (cm.somethingSelected()) return;

    if (options.async)
      getHints(cm, showHints, options);
    else
      return showHints(getHints(cm, options));
  }

  function getText(completion) {
    if (typeof completion == "string") return completion;
    else return completion.text;
  }

  function pickCompletion(cm, data, completion) {
    if (completion.hint) completion.hint(cm, data, completion);
    else cm.replaceRange(getText(completion), data.from, data.to);
  }

  function showHints(data) {
    if (!data || !data.list.length) return;
    var completions = data.list;
    // When there is only one completion, use it directly.
    if (!continued && options.completeSingle !== false && completions.length == 1) {
      pickCompletion(cm, data, completions[0]);
      return true;
    }

    // Build the select widget
    var hints = document.createElement("ul"), selectedHint = 0;
    hints.className = "CodeMirror-hints";
    for (var i = 0; i < completions.length; ++i) {
      var elt = hints.appendChild(document.createElement("li")), completion = completions[i];
      var className = "CodeMirror-hint" + (i ? "" : " CodeMirror-hint-active");
      if (completion.className != null) className = completion.className + " " + className;
      elt.className = className;
      if (completion.render) completion.render(elt, data, completion);
      else elt.appendChild(document.createTextNode(getText(completion)));
      elt.hintId = i;
    }
    var pos = cm.cursorCoords(options.alignWithWord !== false ? data.from : null);
    var left = pos.left, top = pos.bottom, below = true;
    hints.style.left = left + "px";
    hints.style.top = top + "px";
    document.body.appendChild(hints);

    // If we're at the edge of the screen, then we want the menu to appear on the left of the cursor.
    var winW = window.innerWidth || Math.max(document.body.offsetWidth, document.documentElement.offsetWidth);
    var winH = window.innerHeight || Math.max(document.body.offsetHeight, document.documentElement.offsetHeight);
    var box = hints.getBoundingClientRect();
    var overlapX = box.right - winW, overlapY = box.bottom - winH;
    if (overlapX > 0) {
      if (box.right - box.left > winW) {
        hints.style.width = (winW - 5) + "px";
        overlapX -= (box.right - box.left) - winW;
      }
      hints.style.left = (left = pos.left - overlapX) + "px";
    }
    if (overlapY > 0) {
      var height = box.bottom - box.top;
      if (box.top - (pos.bottom - pos.top) - height > 0) {
        overlapY = height + (pos.bottom - pos.top);
        below = false;
      } else if (height > winH) {
        hints.style.height = (winH - 5) + "px";
        overlapY -= height - winH;
      }
      hints.style.top = (top = pos.bottom - overlapY) + "px";
    }

    function changeActive(i) {
      i = Math.max(0, Math.min(i, completions.length - 1));
      if (selectedHint == i) return;
      var node = hints.childNodes[selectedHint];
      node.className = node.className.replace(" CodeMirror-hint-active", "");
      node = hints.childNodes[selectedHint = i];
      node.className += " CodeMirror-hint-active";
      if (node.offsetTop < hints.scrollTop)
        hints.scrollTop = node.offsetTop - 3;
      else if (node.offsetTop + node.offsetHeight > hints.scrollTop + hints.clientHeight)
        hints.scrollTop = node.offsetTop + node.offsetHeight - hints.clientHeight + 3;
    }

    function screenAmount() {
      return Math.floor(hints.clientHeight / hints.firstChild.offsetHeight) || 1;
    }

    var ourMap = {
      Up: function() {changeActive(selectedHint - 1);},
      Down: function() {changeActive(selectedHint + 1);},
      PageUp: function() {changeActive(selectedHint - screenAmount());},
      PageDown: function() {changeActive(selectedHint + screenAmount());},
      Home: function() {changeActive(0);},
      End: function() {changeActive(completions.length - 1);},
      Enter: pick,
      Tab: pick,
      Esc: close
    };
    if (options.customKeys) for (var key in options.customKeys) if (options.customKeys.hasOwnProperty(key)) {
      var val = options.customKeys[key];
      if (/^(Up|Down|Enter|Esc)$/.test(key)) val = ourMap[val];
      ourMap[key] = val;
    }

    cm.addKeyMap(ourMap);
    cm.on("cursorActivity", cursorActivity);
    var closingOnBlur;
    function onBlur(){ closingOnBlur = setTimeout(close, 100); };
    function onFocus(){ clearTimeout(closingOnBlur); };
    cm.on("blur", onBlur);
    cm.on("focus", onFocus);
    var startScroll = cm.getScrollInfo();
    function onScroll() {
      var curScroll = cm.getScrollInfo(), editor = cm.getWrapperElement().getBoundingClientRect();
      var newTop = top + startScroll.top - curScroll.top, point = newTop;
      if (!below) point += hints.offsetHeight;
      if (point <= editor.top || point >= editor.bottom) return close();
      hints.style.top = newTop + "px";
      hints.style.left = (left + startScroll.left - curScroll.left) + "px";
    }
    cm.on("scroll", onScroll);
    CodeMirror.on(hints, "dblclick", function(e) {
      var t = e.target || e.srcElement;
      if (t.hintId != null) {selectedHint = t.hintId; pick();}
    });
    CodeMirror.on(hints, "click", function(e) {
      var t = e.target || e.srcElement;
      if (t.hintId != null) changeActive(t.hintId);
    });
    CodeMirror.on(hints, "mousedown", function() {
      setTimeout(function(){cm.focus();}, 20);
    });

    var done = false, once;
    function close() {
      if (done) return;
      done = true;
      clearTimeout(once);
      hints.parentNode.removeChild(hints);
      cm.removeKeyMap(ourMap);
      cm.off("cursorActivity", cursorActivity);
      cm.off("blur", onBlur);
      cm.off("focus", onFocus);
      cm.off("scroll", onScroll);
    }
    function pick() {
      pickCompletion(cm, data, completions[selectedHint]);
      close();
    }
    var once, lastPos = cm.getCursor(), lastLen = cm.getLine(lastPos.line).length;
    function cursorActivity() {
      clearTimeout(once);

      var pos = cm.getCursor(), line = cm.getLine(pos.line);
      if (pos.line != lastPos.line || line.length - pos.ch != lastLen - lastPos.ch ||
          pos.ch < startCh || cm.somethingSelected() ||
          (pos.ch && closeOn.test(line.charAt(pos.ch - 1))))
        close();
      else
        once = setTimeout(function(){close(); continued = true; startHinting();}, 70);
    }
    return true;
  }

  return startHinting();
};
// Also temporarily added html-hint.js
(function () {
  function htmlHint(editor, htmlStructure, getToken) {
    var cur = editor.getCursor();
    var token = getToken(editor, cur);
    var keywords = [];
    var i = 0;
    var j = 0;
    var k = 0;
    var from = {line: cur.line, ch: cur.ch};
    var to = {line: cur.line, ch: cur.ch};
    var flagClean = true;

    var text = editor.getRange({line: 0, ch: 0}, cur);

    var open = text.lastIndexOf('<');
    var close = text.lastIndexOf('>');
    var tokenString = token.string.replace("<","");

    if(open > close) {
      var last = editor.getRange({line: cur.line, ch: cur.ch - 1}, cur);
      if(last == "<") {
        for(i = 0; i < htmlStructure.length; i++) {
          keywords.push(htmlStructure[i].tag);
        }
        from.ch = token.start + 1;
      } else {
        var counter = 0;
        var found = function(token, type, position) {
          counter++;
          if(counter > 50) return;
          if(token.type == type) {
            return token;
          } else {
            position.ch = token.start;
            var newToken = editor.getTokenAt(position);
            return found(newToken, type, position);
          }
        };

        var nodeToken = found(token, "tag", {line: cur.line, ch: cur.ch});
        var node = nodeToken.string.substring(1);

        if(token.type === null && token.string.trim() === "") {
          for(i = 0; i < htmlStructure.length; i++) {
            if(htmlStructure[i].tag == node) {
              for(j = 0; j < htmlStructure[i].attr.length; j++) {
                keywords.push(htmlStructure[i].attr[j].key + "=\"\" ");
              }

              for(k = 0; k < globalAttributes.length; k++) {
                keywords.push(globalAttributes[k].key + "=\"\" ");
              }
            }
          }
        } else if(token.type == "string") {
          tokenString = tokenString.substring(1, tokenString.length - 1);
          var attributeToken = found(token, "attribute", {line: cur.line, ch: cur.ch});
          var attribute = attributeToken.string;

          for(i = 0; i < htmlStructure.length; i++) {
            if(htmlStructure[i].tag == node) {
              for(j = 0; j < htmlStructure[i].attr.length; j++) {
                if(htmlStructure[i].attr[j].key == attribute) {
                  for(k = 0; k < htmlStructure[i].attr[j].values.length; k++) {
                    keywords.push(htmlStructure[i].attr[j].values[k]);
                  }
                }
              }

              for(j = 0; j < globalAttributes.length; j++) {
                if(globalAttributes[j].key == attribute) {
                  for(k = 0; k < globalAttributes[j].values.length; k++) {
                    keywords.push(globalAttributes[j].values[k]);
                  }
                }
              }
            }
          }
          from.ch = token.start + 1;
        } else if(token.type == "attribute") {
          for(i = 0; i < htmlStructure.length; i++) {
            if(htmlStructure[i].tag == node) {
              for(j = 0; j < htmlStructure[i].attr.length; j++) {
                keywords.push(htmlStructure[i].attr[j].key + "=\"\" ");
              }

              for(k = 0; k < globalAttributes.length; k++) {
                keywords.push(globalAttributes[k].key + "=\"\" ");
              }
            }
          }
          from.ch = token.start;
        } else if(token.type == "tag") {
          for(i = 0; i < htmlStructure.length; i++) {
            keywords.push(htmlStructure[i].tag);
          }

          from.ch = token.start + 1;
        }
      }
    } else {
      for(i = 0; i < htmlStructure.length; i++) {
        keywords.push("<" + htmlStructure[i].tag);
      }

      tokenString = ("<" + tokenString).trim();
      from.ch = token.start;
    }

    if(flagClean === true && tokenString.trim() === "") {
      flagClean = false;
    }

    if(flagClean) {
      keywords = cleanResults(tokenString, keywords);
    }

    return {list: keywords, from: from, to: to};
  }


  var cleanResults = function(text, keywords) {
    var results = [];
    var i = 0;

    for(i = 0; i < keywords.length; i++) {
      if(keywords[i].substring(0, text.length) == text) {
        results.push(keywords[i]);
      }
    }

    return results;
  };

  var htmlStructure = [
    {tag: '!DOCTYPE', attr: []},
    {tag: 'a', attr: [
      {key: 'href', values: ["#"]},
      {key: 'target', values: ["_blank","_self","_top","_parent"]},
      {key: 'ping', values: [""]},
      {key: 'media', values: ["#"]},
      {key: 'hreflang', values: ["en","es"]},
      {key: 'type', values: []}
    ]},
    {tag: 'abbr', attr: []},
    {tag: 'acronym', attr: []},
    {tag: 'address', attr: []},
    {tag: 'applet', attr: []},
    {tag: 'area', attr: [
      {key: 'alt', values: [""]},
      {key: 'coords', values: ["rect: left, top, right, bottom","circle: center-x, center-y, radius","poly: x1, y1, x2, y2, ..."]},
      {key: 'shape', values: ["default","rect","circle","poly"]},
      {key: 'href', values: ["#"]},
      {key: 'target', values: ["#"]},
      {key: 'ping', values: []},
      {key: 'media', values: []},
      {key: 'hreflang', values: []},
      {key: 'type', values: []}

    ]},
    {tag: 'article', attr: []},
    {tag: 'aside', attr: []},
    {tag: 'audio', attr: [
      {key: 'src', values: []},
      {key: 'crossorigin', values: ["anonymous","use-credentials"]},
      {key: 'preload', values: ["none","metadata","auto"]},
      {key: 'autoplay', values: ["","autoplay"]},
      {key: 'mediagroup', values: []},
      {key: 'loop', values: ["","loop"]},
      {key: 'controls', values: ["","controls"]}
    ]},
    {tag: 'b', attr: []},
    {tag: 'base', attr: [
      {key: 'href', values: ["#"]},
      {key: 'target', values: ["_blank","_self","_top","_parent"]}
    ]},
    {tag: 'basefont', attr: []},
    {tag: 'bdi', attr: []},
    {tag: 'bdo', attr: []},
    {tag: 'big', attr: []},
    {tag: 'blockquote', attr: [
      {key: 'cite', values: ["http://"]}
    ]},
    {tag: 'body', attr: []},
    {tag: 'br', attr: []},
    {tag: 'button', attr: [
      {key: 'autofocus', values: ["","autofocus"]},
      {key: 'disabled', values: ["","disabled"]},
      {key: 'form', values: []},
      {key: 'formaction', values: []},
      {key: 'formenctype', values: ["application/x-www-form-urlencoded","multipart/form-data","text/plain"]},
      {key: 'formmethod', values: ["get","post","put","delete"]},
      {key: 'formnovalidate', values: ["","novalidate"]},
      {key: 'formtarget', values: ["_blank","_self","_top","_parent"]},
      {key: 'name', values: []},
      {key: 'type', values: ["submit","reset","button"]},
      {key: 'value', values: []}
    ]},
    {tag: 'canvas', attr: [
      {key: 'width', values: []},
      {key: 'height', values: []}
    ]},
    {tag: 'caption', attr: []},
    {tag: 'center', attr: []},
    {tag: 'cite', attr: []},
    {tag: 'code', attr: []},
    {tag: 'col', attr: [
      {key: 'span', values: []}
    ]},
    {tag: 'colgroup', attr: [
      {key: 'span', values: []}
    ]},
    {tag: 'command', attr: [
      {key: 'type', values: ["command","checkbox","radio"]},
      {key: 'label', values: []},
      {key: 'icon', values: []},
      {key: 'disabled', values: ["","disabled"]},
      {key: 'checked', values: ["","checked"]},
      {key: 'radiogroup', values: []},
      {key: 'command', values: []},
      {key: 'title', values: []}
    ]},
    {tag: 'data', attr: [
      {key: 'value', values: []}
    ]},
    {tag: 'datagrid', attr: [
      {key: 'disabled', values: ["","disabled"]},
      {key: 'multiple', values: ["","multiple"]}
    ]},
    {tag: 'datalist', attr: [
      {key: 'data', values: []}
    ]},
    {tag: 'dd', attr: []},
    {tag: 'del', attr: [
      {key: 'cite', values: []},
      {key: 'datetime', values: []}
    ]},
    {tag: 'details', attr: [
      {key: 'open', values: ["","open"]}
    ]},
    {tag: 'dfn', attr: []},
    {tag: 'dir', attr: []},
    {tag: 'div', attr: [
      {key: 'id', values: []},
      {key: 'class', values: []},
      {key: 'style', values: []}
    ]},
    {tag: 'dl', attr: []},
    {tag: 'dt', attr: []},
    {tag: 'em', attr: []},
    {tag: 'embed', attr: [
      {key: 'src', values: []},
      {key: 'type', values: []},
      {key: 'width', values: []},
      {key: 'height', values: []}
    ]},
    {tag: 'eventsource', attr: [
      {key: 'src', values: []}
    ]},
    {tag: 'fieldset', attr: [
      {key: 'disabled', values: ["","disabled"]},
      {key: 'form', values: []},
      {key: 'name', values: []}
    ]},
    {tag: 'figcaption', attr: []},
    {tag: 'figure', attr: []},
    {tag: 'font', attr: []},
    {tag: 'footer', attr: []},
    {tag: 'form', attr: [
      {key: 'accept-charset', values: ["UNKNOWN","utf-8"]},
      {key: 'action', values: []},
      {key: 'autocomplete', values: ["on","off"]},
      {key: 'enctype', values: ["application/x-www-form-urlencoded","multipart/form-data","text/plain"]},
      {key: 'method', values: ["get","post","put","delete","dialog"]},
      {key: 'name', values: []},
      {key: 'novalidate', values: ["","novalidate"]},
      {key: 'target', values: ["_blank","_self","_top","_parent"]}
    ]},
    {tag: 'frame', attr: []},
    {tag: 'frameset', attr: []},
    {tag: 'h1', attr: []},
    {tag: 'h2', attr: []},
    {tag: 'h3', attr: []},
    {tag: 'h4', attr: []},
    {tag: 'h5', attr: []},
    {tag: 'h6', attr: []},
    {tag: 'head', attr: []},
    {tag: 'header', attr: []},
    {tag: 'hgroup', attr: []},
    {tag: 'hr', attr: []},
    {tag: 'html', attr: [
      {key: 'manifest', values: []}
    ]},
    {tag: 'i', attr: []},
    {tag: 'iframe', attr: [
      {key: 'src', values: []},
      {key: 'srcdoc', values: []},
      {key: 'name', values: []},
      {key: 'sandbox', values: ["allow-top-navigation","allow-same-origin","allow-forms","allow-scripts"]},
      {key: 'seamless', values: ["","seamless"]},
      {key: 'width', values: []},
      {key: 'height', values: []}
    ]},
    {tag: 'img', attr: [
      {key: 'alt', values: []},
      {key: 'src', values: []},
      {key: 'crossorigin', values: ["anonymous","use-credentials"]},
      {key: 'ismap', values: []},
      {key: 'usemap', values: []},
      {key: 'width', values: []},
      {key: 'height', values: []}
    ]},
    {tag: 'input', attr: [
      {key: 'accept', values: ["audio/*","video/*","image/*"]},
      {key: 'alt', values: []},
      {key: 'autocomplete', values: ["on","off"]},
      {key: 'autofocus', values: ["","autofocus"]},
      {key: 'checked', values: ["","checked"]},
      {key: 'disabled', values: ["","disabled"]},
      {key: 'dirname', values: []},
      {key: 'form', values: []},
      {key: 'formaction', values: []},
      {key: 'formenctype', values: ["application/x-www-form-urlencoded","multipart/form-data","text/plain"]},
      {key: 'formmethod', values: ["get","post","put","delete"]},
      {key: 'formnovalidate', values: ["","novalidate"]},
      {key: 'formtarget', values: ["_blank","_self","_top","_parent"]},
      {key: 'height', values: []},
      {key: 'list', values: []},
      {key: 'max', values: []},
      {key: 'maxlength', values: []},
      {key: 'min', values: []},
      {key: 'multiple', values: ["","multiple"]},
      {key: 'name', values: []},
      {key: 'pattern', values: []},
      {key: 'placeholder', values: []},
      {key: 'readonly', values: ["","readonly"]},
      {key: 'required', values: ["","required"]},
      {key: 'size', values: []},
      {key: 'src', values: []},
      {key: 'step', values: []},
      {key: 'type', values: [
        "hidden","text","search","tel","url","email","password","datetime","date","month","week","time","datetime-local",
        "number","range","color","checkbox","radio","file","submit","image","reset","button"
      ]},
      {key: 'value', values: []},
      {key: 'width', values: []}
    ]},
    {tag: 'ins', attr: [
      {key: 'cite', values: []},
      {key: 'datetime', values: []}
    ]},
    {tag: 'kbd', attr: []},
    {tag: 'keygen', attr: [
      {key: 'autofocus', values: ["","autofocus"]},
      {key: 'challenge', values: []},
      {key: 'disabled', values: ["","disabled"]},
      {key: 'form', values: []},
      {key: 'keytype', values: ["RSA"]},
      {key: 'name', values: []}
    ]},
    {tag: 'label', attr: [
      {key: 'for', values: []},
      {key: 'form', values: []}
    ]},
    {tag: 'legend', attr: []},
    {tag: 'li', attr: [
      {key: 'value', values: []}
    ]},
    {tag: 'link', attr: [
      {key: 'href', values: []},
      {key: 'hreflang', values: ["en","es"]},
      {key: 'media', values: [
        "all","screen","print","embossed","braille","handheld","print","projection","screen","tty","tv","speech","3d-glasses",
        "resolution [>][<][=] [X]dpi","resolution [>][<][=] [X]dpcm","device-aspect-ratio: 16/9","device-aspect-ratio: 4/3",
        "device-aspect-ratio: 32/18","device-aspect-ratio: 1280/720","device-aspect-ratio: 2560/1440","orientation:portrait",
        "orientation:landscape","device-height: [X]px","device-width: [X]px","-webkit-min-device-pixel-ratio: 2"
      ]},
      {key: 'type', values: []},
      {key: 'sizes', values: ["all","16x16","16x16 32x32","16x16 32x32 64x64"]}
    ]},
    {tag: 'map', attr: [
      {key: 'name', values: []}
    ]},
    {tag: 'mark', attr: []},
    {tag: 'menu', attr: [
      {key: 'type', values: ["list","context","toolbar"]},
      {key: 'label', values: []}
    ]},
    {tag: 'meta', attr: [
      {key: 'charset', attr: ["utf-8"]},
      {key: 'name', attr: ["viewport","application-name","author","description","generator","keywords"]},
      {key: 'content', attr: ["","width=device-width","initial-scale=1, maximum-scale=1, minimun-scale=1, user-scale=no"]},
      {key: 'http-equiv', attr: ["content-language","content-type","default-style","refresh"]}
    ]},
    {tag: 'meter', attr: [
      {key: 'value', values: []},
      {key: 'min', values: []},
      {key: 'low', values: []},
      {key: 'high', values: []},
      {key: 'max', values: []},
      {key: 'optimum', values: []}
    ]},
    {tag: 'nav', attr: []},
    {tag: 'noframes', attr: []},
    {tag: 'noscript', attr: []},
    {tag: 'object', attr: [
      {key: 'data', values: []},
      {key: 'type', values: []},
      {key: 'typemustmatch', values: ["","typemustmatch"]},
      {key: 'name', values: []},
      {key: 'usemap', values: []},
      {key: 'form', values: []},
      {key: 'width', values: []},
      {key: 'height', values: []}
    ]},
    {tag: 'ol', attr: [
      {key: 'reversed', values: ["", "reversed"]},
      {key: 'start', values: []},
      {key: 'type', values: ["1","a","A","i","I"]}
    ]},
    {tag: 'optgroup', attr: [
      {key: 'disabled', values: ["","disabled"]},
      {key: 'label', values: []}
    ]},
    {tag: 'option', attr: [
      {key: 'disabled', values: ["", "disabled"]},
      {key: 'label', values: []},
      {key: 'selected', values: ["", "selected"]},
      {key: 'value', values: []}
    ]},
    {tag: 'output', attr: [
      {key: 'for', values: []},
      {key: 'form', values: []},
      {key: 'name', values: []}
    ]},
    {tag: 'p', attr: []},
    {tag: 'param', attr: [
      {key: 'name', values: []},
      {key: 'value', values: []}
    ]},
    {tag: 'pre', attr: []},
    {tag: 'progress', attr: [
      {key: 'value', values: []},
      {key: 'max', values: []}
    ]},
    {tag: 'q', attr: [
      {key: 'cite', values: []}
    ]},
    {tag: 'rp', attr: []},
    {tag: 'rt', attr: []},
    {tag: 'ruby', attr: []},
    {tag: 's', attr: []},
    {tag: 'samp', attr: []},
    {tag: 'script', attr: [
      {key: 'type', values: ["text/javascript"]},
      {key: 'src', values: []},
      {key: 'async', values: ["","async"]},
      {key: 'defer', values: ["","defer"]},
      {key: 'charset', values: ["utf-8"]}
    ]},
    {tag: 'section', attr: []},
    {tag: 'select', attr: [
      {key: 'autofocus', values: ["", "autofocus"]},
      {key: 'disabled', values: ["", "disabled"]},
      {key: 'form', values: []},
      {key: 'multiple', values: ["", "multiple"]},
      {key: 'name', values: []},
      {key: 'size', values: []}
    ]},
    {tag: 'small', attr: []},
    {tag: 'source', attr: [
      {key: 'src', values: []},
      {key: 'type', values: []},
      {key: 'media', values: []}
    ]},
    {tag: 'span', attr: []},
    {tag: 'strike', attr: []},
    {tag: 'strong', attr: []},
    {tag: 'style', attr: [
      {key: 'type', values: ["text/css"]},
      {key: 'media', values: ["all","braille","print","projection","screen","speech"]},
      {key: 'scoped', values: []}
    ]},
    {tag: 'sub', attr: []},
    {tag: 'summary', attr: []},
    {tag: 'sup', attr: []},
    {tag: 'table', attr: [
      {key: 'border', values: []}
    ]},
    {tag: 'tbody', attr: []},
    {tag: 'td', attr: [
      {key: 'colspan', values: []},
      {key: 'rowspan', values: []},
      {key: 'headers', values: []}
    ]},
    {tag: 'textarea', attr: [
      {key: 'autofocus', values: ["","autofocus"]},
      {key: 'disabled', values: ["","disabled"]},
      {key: 'dirname', values: []},
      {key: 'form', values: []},
      {key: 'maxlength', values: []},
      {key: 'name', values: []},
      {key: 'placeholder', values: []},
      {key: 'readonly', values: ["","readonly"]},
      {key: 'required', values: ["","required"]},
      {key: 'rows', values: []},
      {key: 'cols', values: []},
      {key: 'wrap', values: ["soft","hard"]}
    ]},
    {tag: 'tfoot', attr: []},
    {tag: 'th', attr: [
      {key: 'colspan', values: []},
      {key: 'rowspan', values: []},
      {key: 'headers', values: []},
      {key: 'scope', values: ["row","col","rowgroup","colgroup"]}
    ]},
    {tag: 'thead', attr: []},
    {tag: 'time', attr: [
      {key: 'datetime', values: []}
    ]},
    {tag: 'title', attr: []},
    {tag: 'tr', attr: []},
    {tag: 'track', attr: [
      {key: 'kind', values: ["subtitles","captions","descriptions","chapters","metadata"]},
      {key: 'src', values: []},
      {key: 'srclang', values: ["en","es"]},
      {key: 'label', values: []},
      {key: 'default', values: []}
    ]},
    {tag: 'tt', attr: []},
    {tag: 'u', attr: []},
    {tag: 'ul', attr: []},
    {tag: 'var', attr: []},
    {tag: 'video', attr: [
      {key: "src", values: []},
      {key: "crossorigin", values: ["anonymous","use-credentials"]},
      {key: "poster", values: []},
      {key: "preload", values: ["auto","metadata","none"]},
      {key: "autoplay", values: ["","autoplay"]},
      {key: "mediagroup", values: ["movie"]},
      {key: "loop", values: ["","loop"]},
      {key: "muted", values: ["","muted"]},
      {key: "controls", values: ["","controls"]},
      {key: "width", values: []},
      {key: "height", values: []}
    ]},
    {tag: 'wbr', attr: []}
  ];

  var globalAttributes = [
    {key: "accesskey", values: ["a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","0","1","2","3","4","5","6","7","8","9"]},
    {key: "class", values: []},
    {key: "contenteditable", values: ["true", "false"]},
    {key: "contextmenu", values: []},
    {key: "dir", values: ["ltr","rtl","auto"]},
    {key: "draggable", values: ["true","false","auto"]},
    {key: "dropzone", values: ["copy","move","link","string:","file:"]},
    {key: "hidden", values: ["hidden"]},
    {key: "id", values: []},
    {key: "inert", values: ["inert"]},
    {key: "itemid", values: []},
    {key: "itemprop", values: []},
    {key: "itemref", values: []},
    {key: "itemscope", values: ["itemscope"]},
    {key: "itemtype", values: []},
    {key: "lang", values: ["en","es"]},
    {key: "spellcheck", values: ["true","false"]},
    {key: "style", values: []},
    {key: "tabindex", values: ["1","2","3","4","5","6","7","8","9"]},
    {key: "title", values: []},
    {key: "translate", values: ["yes","no"]},
    {key: "onclick", values: []},
    {key: 'rel', values: ["stylesheet","alternate","author","bookmark","help","license","next","nofollow","noreferrer","prefetch","prev","search","tag"]}
  ];

  CodeMirror.htmlHint = function(editor) {
    if(String.prototype.trim == undefined) {
      String.prototype.trim=function(){return this.replace(/^\s+|\s+$/g, '');};
    }
    return htmlHint(editor, htmlStructure, function (e, cur) { return e.getTokenAt(cur); });
  };
})();
</script>
<?php
if (file_exists(dirname(__FILE__)."/plugins/emmet/emmet.min.js")) {
	echo '<script src="plugins/emmet/emmet.min.js"></script>';
};?>
<link rel="stylesheet" href="<?php
if ($ICEcoder["theme"]=="default") {echo 'lib/editor.css';} else {echo $ICEcoder["codeMirrorDir"].'/theme/'.$ICEcoder["theme"].'.css';};
$activeLineBG = array_search($ICEcoder["theme"],array("eclipse","elegant","neat")) !== false ? "#ccc" : "#000";
?>">
<style type="text/css">
.CodeMirror {position: absolute; top: 0px; width: 100%; font-size: 13px; z-index: 1}
.CodeMirror-scroll {} /* was: height: auto; overflow: visible */
/* Make sure this next one remains the 3rd item, updated with JS */
.cm-s-activeLine {background: <?php echo $activeLineBG;?> !important}
.cm-matchhighlight, .CodeMirror-focused .cm-matchhighlight {color: #fff !important; background: #06c !important}
/* Make sure this next one remains the 5th item, updated with JS */
.cm-tab:after {position: relative; display: inline-block; width: 0; left: -1.4em; overflow: visible; color: #aaa; content: "<?php if($ICEcoder["visibleTabs"]) {echo '\\21e5';};?>";}
.lint-error {font-family: arial; font-size: 80%; background: #ccc; color: #b00; padding: 3px 5px}
.lint-error-icon {background: #b00; color: #fff; font-weight: bold; border-radius: 50%; padding: 0 3px; margin-right: 5px}
</style>
</head>

<body style="color: #fff; margin: 0" onKeyDown="return top.ICEcoder.interceptKeys('content', event);" onKeyUp="top.ICEcoder.resetKeys(event);">

<?php if ($ICEcoder['demoMode']) {?>
<div style="position: absolute; display: inline-block; width: 99px; height: 50px; top: 0; right: 30px; background: url('images/big-arrow.gif') 0 -10px no-repeat; text-align: center; font-family: arial; font-size: 10px; padding-top: 60px"><b>Click logo<br>for help &amp;<br>usage info</b></div>
<?php ;}; ?>

<div style="display: none; margin: 32px 43px 0 43px; padding: 10px; width: 500px; font-family: arial; font-size: 10px; color: #ddd; background: #333" id="dataMessage"></div>

<div style="margin: 20px 43px 32px 43px; font-family: arial; font-size: 10px; color: #ddd">
	<div style="float: left; margin-right: 50px">
		<h2 style="color: rgba(0,198,255,0.7)">server</h2>
		<span style="color:#888">Server name, OS & IP:</span><br>
		<?php echo $_SERVER['SERVER_NAME']." &nbsp;&nbsp ".$_SERVER['SERVER_SOFTWARE']." &nbsp;&nbsp ".$_SERVER['SERVER_ADDR'];?><br><br>
		<span style="color:#888">Root:</span><br>
		<?php echo $docRoot;?><br><br>
		<span style="color:#888">ICEcoder root:</span><br>
		<?php echo $docRoot.$iceRoot;?><br><br>
		<span style="color:#888">PHP version:</span><br>
		<?php echo phpversion();?><br><br>
		<span style="color:#888">Date & time:</span><br>
		<span id="serverDT"></span><br><br><br>
	</div>

	<div style="float: left">
		<h2 style="color: rgba(0,198,255,0.7)">files</h2>
		<span style="color:#888">Last 10 files opened:</span><br>
		<?php
			$last10FilesArray = explode(",",$ICEcoder["last10Files"]);
			for ($i=0;$i<count($last10FilesArray);$i++) {
				if ($ICEcoder["last10Files"]=="") {
					echo '[none]<br><br>';
				} else {
					echo '<a style="cursor:pointer" onClick="top.ICEcoder.openFile(\''.str_replace("|","/",$last10FilesArray[$i]).'\')">';
					echo str_replace($docRoot,"",str_replace("|","/",$last10FilesArray[$i]));
					echo '</a><br>'.PHP_EOL;
					if ($i==count($last10FilesArray)-1) {echo '<br>'.PHP_EOL;};
				}
			}
		;?>
	</div>

	<div style="clear: both">
		<h2 style="color: rgba(0,198,255,0.7)">your device</h2>
		<span style="color:#888">Browser:</span><br>
		<?php echo $_SERVER['HTTP_USER_AGENT'];?><br><br>
		<span style="color:#888">Your IP:</span><br>
		<?php echo $_SERVER['REMOTE_ADDR'];?>
	</div>
	<script>
	var nDT=<?php echo time()*1000;?>;
	setInterval(function(){
		var s=(new Date(nDT+=1e3)+'').split(' '),
		d=s[2]*1,
		t=s[4].split(':'),
		p=t[0]>11?'pm':'am',
		e=d%20==1|d>30?'st':d%20==2?'nd':d%20==3?'rd':'th';
		t[0]=--t[0]%12+1;
		if (document.getElementById('serverDT')) {
			document.getElementById('serverDT').innerHTML=[s[0],d+e,s[1],s[3],t.join(':')+p].join(' ');
		}
	},1000);
	</script>
	<?php if(is_dir('test') && !$ICEcoder['demoMode']) {?>
	<div style="clear: both">
		<br><br>
		<h2 style="color: rgba(0,198,255,0.7)">test suite</h2>
		<span style="color:#888">Run unit tests:</span><br>
		<a href="javascript:top.ICEcoder.filesFrame.contentWindow.frames['testControl'].location.href = 'test'" style="color: #fff">Run unit tests</a><div id="unitTestResults"></div>
	</div>
	<?php ;};?>
</div>

<script>
CodeMirror.keyMap.ICEcoder = {
	// "Tab": "defaultTab", **Now used by Emmet**
	"Shift-Tab": "indentLess",
	"Ctrl-Space": "autocomplete",
	fallthrough: ["default"]
};
CodeMirror.commands.autocomplete = function(cm) {
	if (top.ICEcoder.caretLocType=="JavaScript") {
		CodeMirror.showHint(cm, CodeMirror.javascriptHint);
	} else {
		CodeMirror.showHint(cm, CodeMirror.htmlHint);
	}
}

function createNewCMInstance(num) {
	var fileName = top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1];
	top.ICEcoder['cM'+num+'waiting'] = "";
	top.ICEcoder['cM'+num+'widgets'] = [];

	window['cM'+num] = CodeMirror(document.body, {
		mode: "application/x-httpd-php",
		lineNumbers: true,
		lineWrapping: top.ICEcoder.lineWrapping,
		indentUnit: top.ICEcoder.tabWidth,
		tabSize: top.ICEcoder.tabWidth,
		indentWithTabs: true,
		electricChars: false,
		autoCloseTags: true,
		highlightSelectionMatches: true,
		keyMap: "ICEcoder",
		onKeyEvent: function(thisCM, e) {
			top.ICEcoder.redoChangedContent(e);
			top.ICEcoder.findReplace('find',true,false);
			top.ICEcoder.getCaretPosition();
			top.ICEcoder.updateCharDisplay();
			tok = thisCM.getTokenAt(thisCM.getCursor());
		}
	});

	window['cM'+num].on("cursorActivity", function(thisCM) {
			top.ICEcoder.getCaretPosition();
			top.ICEcoder.updateCharDisplay();
			window['cM'+num].removeLineClass(top.ICEcoder['cMActiveLine'+num], "background");
			if(window['cM'+num].getCursor('start').line == window['cM'+num].getCursor().line) {
				top.ICEcoder['cMActiveLine'+num] = window['cM'+num].addLineClass(window['cM'+num].getCursor().line, "background","cm-s-activeLine");
			}
			top.ICEcoder.cssColorPreview();
		}
	);

	window['cM'+num].on("change", function(thisCM, changeObj) {
			// If we're not loading the file, it's a change, so update tab
			if (!top.ICEcoder.loadingFile) {
				top.ICEcoder.changedContent[top.ICEcoder.selectedTab-1] = 1;
				top.ICEcoder.redoTabHighlight(top.ICEcoder.selectedTab);
			}
			top.ICEcoder.getCaretPosition();
			top.ICEcoder.dontUpdateNest = false;
			top.ICEcoder.updateCharDisplay();
			top.ICEcoder.updateNestingIndicator();
			if (top.ICEcoder.findMode) {
				top.ICEcoder.results.splice(top.ICEcoder.findResult,1);
				top.document.getElementById('results').innerHTML = top.ICEcoder.results.length + " results";
				top.ICEcoder.findMode = false;
			}
			if (top.ICEcoder.codeAssist) {
				clearTimeout(window['cM'+num+'waiting']);
				window['cM'+num+'waiting'] = setTimeout(top.ICEcoder.updateHints, 100);
			}
		}
	);

	window['cM'+num].on("scroll", function(thisCM) {
			top.ICEcoder.mouseDown=false;
		}
	);

	window['cM'+num].on("gutterClick", function(thisCM, line, gutter, clickEvent) {
			["JavaScript","CoffeeScript","PHP","Ruby"].indexOf(top.ICEcoder.caretLocType) > -1
			? codeFoldBrace(window['cM'+num], line) : codeFoldTag(window['cM'+num], line);
			window['cM'+num].setGutterMarker(line, "CodeMirror-linenumbers", document.createTextNode("+ "+(line+1)));
			setTimeout(function() {
				window['cM'+num].setGutterMarker(line, "CodeMirror-linenumbers", null);
			},1000);
		}
	);

	// Now create the active line for this CodeMirror object
	top.ICEcoder['cMActiveLine'+num] = window['cM'+num].addLineClass(0, "background", "cm-s-activeLine");
};

	// var top.ICEcoder.foldStyle = '<span style="position: absolute; display: inline-block; width: 13px; height: 13px; left: 0; background-color: #b00; color: #fff; text-align: center; cursor: pointer"><span style="position: relative; left: -1px">+</span></span> %N%';
	var codeFoldTag = CodeMirror.newFoldFunction(CodeMirror.tagRangeFinder);
	var codeFoldBrace = CodeMirror.newFoldFunction(CodeMirror.braceRangeFinder);
</script>

</body>

</html>