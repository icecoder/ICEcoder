<script>
/*
 Copyright (C) 2013 Sencha Inc.
 Copyright (C) 2012 Sencha Inc.
 Copyright (C) 2011 Sencha Inc.

 Author: Ariya Hidayat.

 Permission is hereby granted, free of charge, to any person obtaining a copy
 of this software and associated documentation files (the "Software"), to deal
 in the Software without restriction, including without limitation the rights
 to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the Software is
 furnished to do so, subject to the following conditions:

 The above copyright notice and this permission notice shall be included in
 all copies or substantial portions of the Software.

 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 THE SOFTWARE.
*/

/*jslint continue: true, indent: 4 */
/*global exports:true, module:true, window:true */

(function(){function p(m,p){function q(a){return" "===a||"\n"===a||"\t"===a||"\r"===a||"\f"===a}function t(a){return"a"<=b&&"z">=b||"A"<=b&&"Z">=b||"0"<=b&&"9">=b||0<="-_*.:#".indexOf(a)}function r(){var b;for(b=g;0<b;b-=1)a+=h.indent}function u(){a=f(a);v?a+=" {":(a+="\n",r(),a+="{");"\n"!==d&&(a+="\n");g+=1}function n(){var b;g-=1;a=f(a);w&&(b=a.charAt(a.length-1),";"!==b&&"{"!==b&&(a+=";"));a+="\n";r();a+="}";k.push(a);a=""}var h,e=0,x=m.length,k,a="",b,d,c,g,l,s,v=!0,w=!1,f;h=1<arguments.length?
p:{};"undefined"===typeof h.indent&&(h.indent="    ");"string"===typeof h.openbrace&&(v="end-of-line"===h.openbrace);"boolean"===typeof h.autosemicolon&&(w=h.autosemicolon);f=String.prototype.trimRight?function(a){return a.trimRight()}:function(a){return a.replace(/\s+$/,"")};c=g=0;s=!1;k=[];for(m=m.replace(/\r\n/g,"\n");e<x;)if(b=m.charAt(e),d=m.charAt(e+1),e+=1,"'"===l||'"'===l)a+=b,b===l&&(l=null),"\\"===b&&d===l&&(a+=d,e+=1);else if("'"===b||'"'===b)a+=b,l=b;else if(s)a+=b,"*"===b&&"/"===d&&(s=
!1,a+=d,e+=1);else if("/"===b&&"*"===d)s=!0,a+=b,a+=d,e+=1;else{if(0===c){if(0===k.length&&q(b)&&0===a.length)continue;if(" ">=b||128<=b.charCodeAt(0)){c=0;a+=b;continue}if(t(b)||"["===b||"@"===b){c=f(a);if(0===c.length)0<k.length&&(a="\n\n");else if("}"===c.charAt(c.length-1)||";"===c.charAt(c.length-1))a=c+"\n\n";else for(;;){d=a.charAt(a.length-1);if(" "!==d&&9!==d.charCodeAt(0))break;a=a.substr(0,a.length-1)}a+=b;c="@"===b?1:3;continue}}if(1===c)";"===b?(a+=b,c=0):"{"===b?(c=f(a),u(),c="@font-face"===
c?4:2):a+=b;else if(2===c)if(t(b)){c=f(a);if(0===c.length)0<k.length&&(a="\n\n");else if("}"===c.charAt(c.length-1))a=c+"\n\n";else for(;;){d=a.charAt(a.length-1);if(" "!==d&&9!==d.charCodeAt(0))break;a=a.substr(0,a.length-1)}r();a+=b;c=3}else"}"===b?(n(),c=0):a+=b;else if(3===c)"{"===b?(u(),c=4):"}"===b?(n(),c=0):a+=b;else if(4===c)"}"===b?(n(),c=0,0<g&&(c=2)):"\n"===b?(a=f(a),a+="\n"):q(b)?a+=b:(a=f(a),a+="\n",r(),a+=b,c=5);else if(5===c)":"===b?(a=f(a),a+=": ",c=7,q(d)&&(c=6)):"}"===b?(n(),c=0,
0<g&&(c=2)):a+=b;else if(6===c)if(!q(b))a+=b,c=7;else{if("'"===d||'"'===d)c=7}else 7===c?"}"===b?(n(),c=0,0<g&&(c=2)):";"===b?(a=f(a),a+=";\n",c=4):(a+=b,"("===b&&"l"===a.charAt(a.length-2)&&"r"===a.charAt(a.length-3)&&"u"===a.charAt(a.length-4)&&(c=8)):8===c&&")"===b&&a.charAt("\\"!==a.length-1)?(a+=b,c=7):a+=b}return a=k.join("")+a}"undefined"!==typeof exports?module.exports=exports=p:"object"===typeof window&&(window.cssbeautify=p)})();

cM = top.ICEcoder.getcMInstance();
if (cM && top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1].indexOf('.css') > -1) {
	cM.setValue(cssbeautify(cM.getValue(), {
		indent: '	',
		openbrace: 'end-of-line',
		autosemicolon: false
	}));
}
</script>