// Modified version of CodeMirror's codefold.js to show guttermarkers

CodeMirror.doFold = function(foldType, widget, markOn, markOff, dontCollapse) {

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
    markOn.className = "fold foldOn";
  }
  if (markOff == null) markOff = "-";
  if (typeof markOff == "string") {
    var text = document.createTextNode(markOff);
    markOff = document.createElement("span");
    markOff.appendChild(text);
    markOff.className = "fold foldOff";
  }

  return function(cm, pos) {
    if (typeof pos == "number") pos = CodeMirror.Pos(pos, 0);
    var range = CodeMirror.fold[foldType](cm, pos);
    foldable = range && (range.from.line != range.to.line || range.from.ch != range.to.ch) ? true : false;
    if (!foldable) cm.setGutterMarker(pos.line, "folds", null);
    if (!range) return;

    var present = cm.findMarksAt(range.from), cleared = 0;
    for (var i = 0; i < present.length; ++i) {
      if (present[i].__isFold) {
        ++cleared;
        present[i].clear();
      }
    }

    if (foldable) {
      cm.setGutterMarker(pos.line, "folds", cleared || dontCollapse ? markOff.cloneNode(true) : markOn.cloneNode(true));
    }

    if (cleared || dontCollapse) return;   
    var myWidget = widget.cloneNode(true);
    CodeMirror.on(myWidget, "mousedown", function() {myRange.clear();cm.setGutterMarker(pos.line, "folds", markOff.cloneNode(true));});
    var myRange = cm.markText(range.from, range.to, {
      replacedWith: myWidget,
      clearOnEnter: true,
      __isFold: true
    });
  };
};