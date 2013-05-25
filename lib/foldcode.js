// Modified version of CodeMirror's codefold.js to show guttermarkers

CodeMirror.newFoldFunction = function(rangeFinder, widget, markOn, markOff, initMarks) {
  if (widget == null) widget = "\u2194";
  if (typeof widget == "string") {
    var text = document.createTextNode(widget);
    widget = document.createElement("span");
    widget.appendChild(text);
    widget.className = "CodeMirror-foldmarker";
  }
  if (markOn == null) markOn = "+";
  if (typeof markOn == "string") {
    var text = document.createTextNode(markOn);
    markOn = document.createElement("span");
    markOn.appendChild(text);
    markOn.className = "CodeMirror-guttermarker CodeMirror-guttermarkerOn";
  }
  if (markOff == null) markOff = "-";
  if (typeof markOff == "string") {
    var text = document.createTextNode(markOff);
    markOff = document.createElement("span");
    markOff.appendChild(text);
    markOff.className = "CodeMirror-guttermarker CodeMirror-guttermarkerOff";
  }

  return function(cm, pos) {
    if (typeof pos == "number") pos = CodeMirror.Pos(pos, 0);
    var range = rangeFinder(cm, pos);
    foldable = range && (range.from.line != range.to.line || range.from.ch != range.to.ch) ? true : false;
    if (!range) return;

    var present = cm.findMarksAt(range.from), cleared = 0;
    for (var i = 0; i < present.length; ++i) {
      if (present[i].__isFold) {
        ++cleared;
        present[i].clear();
      }
    }

    if (foldable) {
      cm.setGutterMarker(pos.line, "CodeMirror-linenumbers", cleared || initMarks ? markOff.cloneNode(true) : markOn.cloneNode(true));
    }

    if (cleared || initMarks) return;   
    var myWidget = widget.cloneNode(true);
    CodeMirror.on(myWidget, "mousedown", function() {myRange.clear();cm.setGutterMarker(pos.line, "CodeMirror-linenumbers", markOff.cloneNode(true));});
    var myRange = cm.markText(range.from, range.to, {
      replacedWith: myWidget,
      clearOnEnter: true,
      __isFold: true
    });
  };
};