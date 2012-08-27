//     This file contains the select, reject, forEach and has functions
//     from underscore.js, then minified to make it extra tiny

//     Underscore.js 1.3.3
//     (c) 2009-2012 Jeremy Ashkenas, DocumentCloud Inc.
//     Underscore may be freely distributed under the MIT license.
//     Portions of Underscore are inspired or borrowed from Prototype,
//     Oliver Steele's Functional, and John Resig's Micro-Templating.
//     For all details and documentation:
//     http://documentcloud.github.com/underscore

(function(){var e=this,t=Array.prototype,n=Object.prototype,r=t.forEach,i=t.filter,s=n.hasOwnProperty,o={};_=function(obj) {return new wrapper(obj);};_.filter=_.select=function(e,t,n){var r=[];return e==null?r:i&&e.filter===i?e.filter(t,n):(u(e,function(e,i,s){t.call(n,e,i,s)&&(r[r.length]=e)}),r)},_.reject=function(e,t,n){var r=[];return e==null?r:(u(e,function(e,i,s){t.call(n,e,i,s)||(r[r.length]=e)}),r)};var u=_.each=_.forEach=function(e,t,n){if(e==null)return;if(r&&e.forEach===r)e.forEach(t,n);else if(e.length===+e.length){for(var i=0,s=e.length;i<s;i++)if(i in e&&t.call(n,e[i],i,e)===o)return}else for(var u in e)if(_.has(e,u)&&t.call(n,e[u],u,e)===o)return};_.has=function(e,t){return s.call(e,t)}}).call(this)