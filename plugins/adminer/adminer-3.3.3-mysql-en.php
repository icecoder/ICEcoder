<?php
/** Adminer - Compact database management
* @link http://www.adminer.org/
* @author Jakub Vrana, http://www.vrana.cz/
* @copyright 2007 Jakub Vrana
* @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
* @version 3.3.3
*/error_reporting(6135);$Tb=(!ereg('^(unsafe_raw)?$',ini_get("filter.default")));if($Tb||ini_get("filter.default_flags")){foreach(array('_GET','_POST','_COOKIE','_SERVER')as$X){$xf=filter_input_array(constant("INPUT$X"),FILTER_UNSAFE_RAW);if($xf){$$X=$xf;}}}if(isset($_GET["file"])){header("Expires: ".gmdate("D, d M Y H:i:s",time()+365*24*60*60)." GMT");if($_GET["file"]=="favicon.ico"){header("Content-Type: image/x-icon");echo
base64_decode("AAABAAEAEBAQAAEABAAoAQAAFgAAACgAAAAQAAAAIAAAAAEABAAAAAAAwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA////AAAA/wBhTgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAERERAAAAAAETMzEQAAAAATERExAAAAABMRETEAAAAAExERMQAAAAATERExAAAAABMRETEAAAAAEzMzMREREQATERExEhEhABEzMxEhEREAAREREhERIRAAAAARIRESEAAAAAESEiEQAAAAABEREQAAAAAAAAAAD//9UAwP/VAIB/AACAf/AAgH+kAIB/gACAfwAAgH8AAIABAACAAf8AgAH/AMAA/wD+AP8A/wAIAf+B1QD//9UA");}elseif($_GET["file"]=="default.css"){header("Content-Type: text/css; charset=utf-8");echo'body{color:#000;background:#fff;font:90%/1.25 Verdana,Arial,Helvetica,sans-serif;margin:0;}a{color:blue;}a:visited{color:navy;}a:hover{color:red;}h1{font-size:150%;margin:0;padding:.8em 1em;border-bottom:1px solid #999;font-weight:normal;color:#777;background:#eee;}h2{font-size:150%;margin:0 0 20px -18px;padding:.8em 1em;border-bottom:1px solid #000;color:#000;font-weight:normal;background:#ddf;}h3{font-weight:normal;font-size:130%;margin:1em 0 0;}form{margin:0;}table{margin:1em 20px 0 0;border:0;border-top:1px solid #999;border-left:1px solid #999;font-size:90%;}td,th{border:0;border-right:1px solid #999;border-bottom:1px solid #999;padding:.2em .3em;}th{background:#eee;text-align:left;}thead th{text-align:center;}thead td,thead th{background:#ddf;}fieldset{display:inline;vertical-align:top;padding:.5em .8em;margin:.8em .5em 0 0;border:1px solid #999;}p{margin:.8em 20px 0 0;}img{vertical-align:middle;border:0;}td img{max-width:200px;max-height:200px;}code{background:#eee;}tbody tr:hover td,tbody tr:hover th{background:#eee;}pre{margin:1em 0 0;}input[type=image]{vertical-align:middle;}.version{color:#777;font-size:67%;}.js .hidden,.nojs .jsonly{display:none;}.nowrap td,.nowrap th,td.nowrap{white-space:pre;}.wrap td{white-space:normal;}.error{color:red;background:#fee;}.error b{background:#fff;font-weight:normal;}.message{color:green;background:#efe;}.error,.message{padding:.5em .8em;margin:1em 20px 0 0;}.char{color:#007F00;}.date{color:#7F007F;}.enum{color:#007F7F;}.binary{color:red;}.odd td{background:#F5F5F5;}.js .checked td,.js .checked th{background:#ddf;}.time{color:silver;font-size:70%;}.function{text-align:right;}.number{text-align:right;}.datetime{text-align:right;}.type{width:15ex;width:auto\\9;}.options select{width:20ex;width:auto\\9;}.active{font-weight:bold;}.sqlarea{width:98%;}#menu{position:absolute;margin:10px 0 0;padding:0 0 30px 0;top:2em;left:0;width:19em;overflow:auto;overflow-y:hidden;white-space:nowrap;}#menu p{padding:.8em 1em;margin:0;border-bottom:1px solid #ccc;}#content{margin:2em 0 0 21em;padding:10px 20px 20px 0;}#lang{position:absolute;top:0;left:0;line-height:1.8em;padding:.3em 1em;}#breadcrumb{white-space:nowrap;position:absolute;top:0;left:21em;background:#eee;height:2em;line-height:1.8em;padding:0 1em;margin:0 0 0 -18px;}#loader{position:fixed;top:0;left:18em;z-index:1;}#h1{color:#777;text-decoration:none;font-style:italic;}#version{font-size:67%;color:red;}#schema{margin-left:60px;position:relative;}#schema .table{border:1px solid silver;padding:0 2px;cursor:move;position:absolute;}#schema .references{position:absolute;}.rtl h2{margin:0 -18px 20px 0;}.rtl p,.rtl table,.rtl .error,.rtl .message{margin:1em 0 0 20px;}.rtl #content{margin:2em 21em 0 0;padding:10px 0 20px 20px;}.rtl #breadcrumb{left:auto;right:21em;margin:0 -18px 0 0;}.rtl #lang,.rtl #menu{left:auto;right:0;}@media print{#lang,#menu{display:none;}#content{margin-left:1em;}#breadcrumb{left:1em;}.nowrap td,.nowrap th,td.nowrap{white-space:normal;}}';}elseif($_GET["file"]=="functions.js"){header("Content-Type: text/javascript; charset=utf-8");?>
function toggle(id){var el=document.getElementById(id);el.className=(el.className=='hidden'?'':'hidden');return true;}
function cookie(assign,days){var date=new Date();date.setDate(date.getDate()+days);document.cookie=assign+'; expires='+date;}
function verifyVersion(){cookie('adminer_version=0',1);var script=document.createElement('script');script.src=location.protocol+'//www.adminer.org/version.php';document.body.appendChild(script);}
function selectValue(select){var selected=select.options[select.selectedIndex];return((selected.attributes.value||{}).specified?selected.value:selected.text);}
function trCheck(el){var tr=el.parentNode.parentNode;tr.className=tr.className.replace(/(^|\s)checked(\s|$)/,'$2')+(el.checked?' checked':'');}
function formCheck(el,name){var elems=el.form.elements;for(var i=0;i<elems.length;i++){if(name.test(elems[i].name)){elems[i].checked=el.checked;trCheck(elems[i]);}}}
function tableCheck(){var tables=document.getElementsByTagName('table');for(var i=0;i<tables.length;i++){if(/(^|\s)checkable(\s|$)/.test(tables[i].className)){var trs=tables[i].getElementsByTagName('tr');for(var j=0;j<trs.length;j++){trCheck(trs[j].firstChild.firstChild);}}}}
function formUncheck(id){var el=document.getElementById(id);el.checked=false;trCheck(el);}
function formChecked(el,name){var checked=0;var elems=el.form.elements;for(var i=0;i<elems.length;i++){if(name.test(elems[i].name)&&elems[i].checked){checked++;}}
return checked;}
function tableClick(event){var click=true;var el=event.target||event.srcElement;while(!/^tr$/i.test(el.tagName)){if(/^table$/i.test(el.tagName)){return;}
if(/^(a|input|textarea)$/i.test(el.tagName)){click=false;}
el=el.parentNode;}
el=el.firstChild.firstChild;if(click){el.click&&el.click();el.onclick&&el.onclick();}
trCheck(el);}
function setHtml(id,html){var el=document.getElementById(id);if(el){if(html==undefined){el.parentNode.innerHTML='&nbsp;';}else{el.innerHTML=html;}}}
function nodePosition(el){var pos=0;while(el=el.previousSibling){pos++;}
return pos;}
function pageClick(href,page,event){if(!isNaN(page)&&page){href+=(page!=1?'&page='+(page-1):'');if(!ajaxSend(href)){location.href=href;}}}
function selectAddRow(field){field.onchange=function(){};var row=field.parentNode.cloneNode(true);var selects=row.getElementsByTagName('select');for(var i=0;i<selects.length;i++){selects[i].name=selects[i].name.replace(/[a-z]\[\d+/,'$&1');selects[i].selectedIndex=0;}
var inputs=row.getElementsByTagName('input');if(inputs.length){inputs[0].name=inputs[0].name.replace(/[a-z]\[\d+/,'$&1');inputs[0].value='';inputs[0].className='';}
field.parentNode.parentNode.appendChild(row);}
function bodyKeydown(event,button){var target=event.target||event.srcElement;if(event.ctrlKey&&(event.keyCode==13||event.keyCode==10)&&!event.altKey&&!event.metaKey&&/select|textarea|input/i.test(target.tagName)){target.blur();if(!ajaxForm(target.form,(button?button+'=1':''))){if(button){target.form[button].click();}else{target.form.submit();}}
return false;}
return true;}
function editingKeydown(event){if((event.keyCode==40||event.keyCode==38)&&event.ctrlKey&&!event.altKey&&!event.metaKey){var target=event.target||event.srcElement;var sibling=(event.keyCode==40?'nextSibling':'previousSibling');var el=target.parentNode.parentNode[sibling];if(el&&(/^tr$/i.test(el.tagName)||(el=el[sibling]))&&/^tr$/i.test(el.tagName)&&(el=el.childNodes[nodePosition(target.parentNode)])&&(el=el.childNodes[nodePosition(target)])){el.focus();}
return false;}
if(event.shiftKey&&!bodyKeydown(event,'insert')){eventStop(event);return false;}
return true;}
function functionChange(select){var input=select.form[select.name.replace(/^function/,'fields')];if(selectValue(select)){if(input.origMaxLength===undefined){input.origMaxLength=input.maxLength;}
input.removeAttribute('maxlength');}else if(input.origMaxLength>=0){input.maxLength=input.origMaxLength;}}
function ajax(url,callback,data){var xmlhttp=(window.XMLHttpRequest?new XMLHttpRequest():(window.ActiveXObject?new ActiveXObject('Microsoft.XMLHTTP'):false));if(xmlhttp){xmlhttp.open((data?'POST':'GET'),url);if(data){xmlhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded');}
xmlhttp.setRequestHeader('X-Requested-With','XMLHttpRequest');xmlhttp.onreadystatechange=function(){if(xmlhttp.readyState==4){callback(xmlhttp);}};xmlhttp.send(data);}
return xmlhttp;}
function ajaxSetHtml(url){return ajax(url,function(xmlhttp){if(xmlhttp.status){var data=eval('('+xmlhttp.responseText+')');for(var key in data){setHtml(key,data[key]);}}});}
var originalFavicon;function replaceFavicon(href){var favicon=document.getElementById('favicon');if(favicon){favicon.href=href;favicon.parentNode.appendChild(favicon);}}
var ajaxState=0;function ajaxSend(url,data,popState,noscroll){if(!history.pushState){return false;}
var currentState=++ajaxState;onblur=function(){if(!originalFavicon){originalFavicon=(document.getElementById('favicon')||{}).href;}
replaceFavicon(location.pathname+'?file=loader.gif&amp;version=3.3.3');};setHtml('loader','<img src="'+location.pathname+'?file=loader.gif&amp;version=3.3.3" alt="">');return ajax(url,function(xmlhttp){if(currentState==ajaxState){var title=xmlhttp.getResponseHeader('X-AJAX-Title');if(title){document.title=decodeURIComponent(title);}
var redirect=xmlhttp.getResponseHeader('X-AJAX-Redirect');if(redirect){return ajaxSend(redirect,'',popState);}
onblur=function(){};if(originalFavicon){replaceFavicon(originalFavicon);}
if(!xmlhttp.status){setHtml('loader','');}else{if(!popState){if(data||url!=location.href){history.pushState(data,'',url);}}
if(!noscroll&&!/&order/.test(url)){scrollTo(0,0);}
setHtml('content',xmlhttp.responseText);var content=document.getElementById('content');var scripts=content.getElementsByTagName('script');var length=scripts.length;for(var i=0;i<length;i++){var script=document.createElement('script');script.text=scripts[i].text;content.appendChild(script);}
var as=document.getElementById('menu').getElementsByTagName('a');var href=location.href.replace(/(&(sql=|dump=|(select|table)=[^&]*)).*/,'$1');for(var i=0;i<as.length;i++){as[i].className=(href==as[i].href?'active':'');}
var dump=document.getElementById('dump');if(dump){var match=/&(select|table)=([^&]+)/.exec(href);dump.href=dump.href.replace(/[^=]+$/,'')+(match?match[2]:'');}
if(window.jush){jush.highlight_tag('code',0);}}}},data);}
onpopstate=function(event){if((ajaxState||event.state)&&!/#/.test(location.href)){ajaxSend(location.href,(event.state&&confirm(areYouSure)?event.state:''),1);}else{ajaxState++;}};function ajaxForm(form,data,noscroll){if((/&(database|scheme|create|view|sql|user|dump|call)=/.test(location.href)&&!/\./.test(data))||(form.onsubmit&&form.onsubmit()===false)){return false;}
var params=[];for(var i=0;i<form.elements.length;i++){var el=form.elements[i];if(/file/i.test(el.type)&&el.value){return false;}else if(el.name&&(!/checkbox|radio|submit|file/i.test(el.type)||el.checked)){params.push(encodeURIComponent(el.name)+'='+encodeURIComponent(/select/i.test(el.tagName)?selectValue(el):el.value));}}
if(data){params.push(data);}
if(form.method=='post'){return ajaxSend((/\?/.test(form.action)?form.action:location.href),params.join('&'),false,noscroll);}
return ajaxSend((form.action||location.href).replace(/\?.*/,'')+'?'+params.join('&'),'',false,noscroll);}
function selectDblClick(td,event,text){if(/input|textarea/i.test(td.firstChild.tagName)){return;}
var original=td.innerHTML;var input=document.createElement(text?'textarea':'input');input.onkeydown=function(event){if(!event){event=window.event;}
if(event.keyCode==27&&!(event.ctrlKey||event.shiftKey||event.altKey||event.metaKey)){td.innerHTML=original;}};var pos=event.rangeOffset;var value=td.firstChild.alt||td.textContent||td.innerText;input.style.width=Math.max(td.clientWidth-14,20)+'px';if(text){var rows=1;value.replace(/\n/g,function(){rows++;});input.rows=rows;}
if(value=='\u00A0'||td.getElementsByTagName('i').length){value='';}
if(document.selection){var range=document.selection.createRange();range.moveToPoint(event.clientX,event.clientY);var range2=range.duplicate();range2.moveToElementText(td);range2.setEndPoint('EndToEnd',range);pos=range2.text.length;}
td.innerHTML='';td.appendChild(input);input.focus();if(text==2){return ajax(location.href+'&'+encodeURIComponent(td.id)+'=',function(xmlhttp){if(xmlhttp.status){input.value=xmlhttp.responseText;input.name=td.id;}});}
input.value=value;input.name=td.id;input.selectionStart=pos;input.selectionEnd=pos;if(document.selection){var range=document.selection.createRange();range.moveEnd('character',-input.value.length+pos);range.select();}}
function bodyClick(event,db,ns){if(event.button||event.ctrlKey||event.shiftKey||event.altKey||event.metaKey){return;}
if(event.getPreventDefault?event.getPreventDefault():event.returnValue===false||event.defaultPrevented){return false;}
var el=event.target||event.srcElement;if(/^a$/i.test(el.parentNode.tagName)){el=el.parentNode;}
if(/^a$/i.test(el.tagName)&&!/:|#|&download=/i.test(el.getAttribute('href'))&&/[&?]username=/.test(el.href)){var match=/&db=([^&]*)/.exec(el.href);var match2=/&ns=([^&]*)/.exec(el.href);return!(db==(match?match[1]:'')&&ns==(match2?match2[1]:'')&&ajaxSend(el.href));}
if(/^input$/i.test(el.tagName)&&/image|submit/.test(el.type)){return!ajaxForm(el.form,(el.name?encodeURIComponent(el.name)+(el.type=='image'?'.x':'')+'=1':''),el.type=='image');}
return true;}
function eventStop(event){if(event.stopPropagation){event.stopPropagation();}else{event.cancelBubble=true;}}
var jushRoot=location.protocol + '//www.adminer.org/static/';function bodyLoad(version){if(history.state!==undefined){onpopstate(history);}
if(jushRoot){var script=document.createElement('script');script.src=jushRoot+'jush.js';script.onload=function(){if(window.jush){jush.create_links=' target="_blank" rel="noreferrer"';jush.urls.sql_sqlset=jush.urls.sql[0]=jush.urls.sqlset[0]=jush.urls.sqlstatus[0]='http://dev.mysql.com/doc/refman/'+version+'/en/$key';var pgsql='http://www.postgresql.org/docs/'+version+'/static/';jush.urls.pgsql_pgsqlset=jush.urls.pgsql[0]=pgsql+'$key';jush.urls.pgsqlset[0]=pgsql+'runtime-config-$key.html#GUC-$1';jush.style(jushRoot+'jush.css');if(window.jushLinks){jush.custom_links=jushLinks;}
jush.highlight_tag('code',0);}};script.onreadystatechange=function(){if(/^(loaded|complete)$/.test(script.readyState)){script.onload();}};document.body.appendChild(script);}}
function formField(form,name){for(var i=0;i<form.length;i++){if(form[i].name==name){return form[i];}}}
function typePassword(el,disable){try{el.type=(disable?'text':'password');}catch(e){}}
function loginDriver(driver){var trs=driver.parentNode.parentNode.parentNode.rows;for(var i=1;i<trs.length;i++){trs[i].className=(/sqlite/.test(driver.value)?'hidden':'');}}
function textareaKeydown(target,event){if(!event.shiftKey&&!event.altKey&&!event.ctrlKey&&!event.metaKey){if(event.keyCode==9){if(target.setSelectionRange){var start=target.selectionStart;var scrolled=target.scrollTop;target.value=target.value.substr(0,start)+'\t'+target.value.substr(target.selectionEnd);target.setSelectionRange(start+1,start+1);target.scrollTop=scrolled;return false;}else if(target.createTextRange){document.selection.createRange().text='\t';return false;}}
if(event.keyCode==27){var els=target.form.elements;for(var i=1;i<els.length;i++){if(els[i-1]==target){els[i].focus();break;}}
return false;}}
return true;}
var added='.',rowCount;function delimiterEqual(val,a,b){return(val==a+'_'+b||val==a+b||val==a+b.charAt(0).toUpperCase()+b.substr(1));}
function idfEscape(s){return s.replace(/`/,'``');}
function editingNameChange(field){var name=field.name.substr(0,field.name.length-7);var type=formField(field.form,name+'[type]');var opts=type.options;var candidate;var val=field.value;for(var i=opts.length;i--;){var match=/(.+)`(.+)/.exec(opts[i].value);if(!match){if(candidate&&i==opts.length-2&&val==opts[candidate].value.replace(/.+`/,'')&&name=='fields[1]'){return;}
break;}
var table=match[1];var column=match[2];var tables=[table,table.replace(/s$/,''),table.replace(/es$/,'')];for(var j=0;j<tables.length;j++){table=tables[j];if(val==column||val==table||delimiterEqual(val,table,column)||delimiterEqual(val,column,table)){if(candidate){return;}
candidate=i;break;}}}
if(candidate){type.selectedIndex=candidate;type.onchange();}}
function editingAddRow(button,allowed,focus){if(allowed&&rowCount>=allowed){return false;}
var match=/(\d+)(\.\d+)?/.exec(button.name);var x=match[0]+(match[2]?added.substr(match[2].length):added)+'1';var row=button.parentNode.parentNode;var row2=row.cloneNode(true);var tags=row.getElementsByTagName('select');var tags2=row2.getElementsByTagName('select');for(var i=0;i<tags.length;i++){tags2[i].name=tags[i].name.replace(/([0-9.]+)/,x);tags2[i].selectedIndex=tags[i].selectedIndex;}
tags=row.getElementsByTagName('input');tags2=row2.getElementsByTagName('input');var input=tags2[0];for(var i=0;i<tags.length;i++){if(tags[i].name=='auto_increment_col'){tags2[i].value=x;tags2[i].checked=false;}
tags2[i].name=tags[i].name.replace(/([0-9.]+)/,x);if(/\[(orig|field|comment|default)/.test(tags[i].name)){tags2[i].value='';}
if(/\[(has_default)/.test(tags[i].name)){tags2[i].checked=false;}}
tags[0].onchange=function(){editingNameChange(tags[0]);};row.parentNode.insertBefore(row2,row.nextSibling);if(focus){input.onchange=function(){editingNameChange(input);};input.focus();}
added+='0';rowCount++;return true;}
function editingRemoveRow(button){var field=formField(button.form,button.name.replace(/drop_col(.+)/,'fields$1[field]'));field.parentNode.removeChild(field);button.parentNode.parentNode.style.display='none';return true;}
var lastType='';function editingTypeChange(type){var name=type.name.substr(0,type.name.length-6);var text=selectValue(type);for(var i=0;i<type.form.elements.length;i++){var el=type.form.elements[i];if(el.name==name+'[length]'&&!((/(char|binary)$/.test(lastType)&&/(char|binary)$/.test(text))||(/(enum|set)$/.test(lastType)&&/(enum|set)$/.test(text)))){el.value='';}
if(lastType=='timestamp'&&el.name==name+'[has_default]'&&/timestamp/i.test(formField(type.form,name+'[default]').value)){el.checked=false;}
if(el.name==name+'[collation]'){el.className=(/(char|text|enum|set)$/.test(text)?'':'hidden');}
if(el.name==name+'[unsigned]'){el.className=(/(int|float|double|decimal)$/.test(text)?'':'hidden');}
if(el.name==name+'[on_delete]'){el.className=(/`/.test(text)?'':'hidden');}}}
function editingLengthFocus(field){var td=field.parentNode;if(/(enum|set)$/.test(selectValue(td.previousSibling.firstChild))){var edit=document.getElementById('enum-edit');var val=field.value;edit.value=(/^'.+','.+'$/.test(val)?val.substr(1,val.length-2).replace(/','/g,"\n").replace(/''/g,"'"):val);td.appendChild(edit);field.style.display='none';edit.style.display='inline';edit.focus();}}
function editingLengthBlur(edit){var field=edit.parentNode.firstChild;var val=edit.value;field.value=(/\n/.test(val)?"'"+val.replace(/\n+$/,'').replace(/'/g,"''").replace(/\n/g,"','")+"'":val);field.style.display='inline';edit.style.display='none';}
function columnShow(checked,column){var trs=document.getElementById('edit-fields').getElementsByTagName('tr');for(var i=0;i<trs.length;i++){trs[i].getElementsByTagName('td')[column].className=(checked?'':'hidden');}}
function partitionByChange(el){var partitionTable=/RANGE|LIST/.test(selectValue(el));el.form['partitions'].className=(partitionTable||!el.selectedIndex?'hidden':'');document.getElementById('partition-table').className=(partitionTable?'':'hidden');}
function partitionNameChange(el){var row=el.parentNode.parentNode.cloneNode(true);row.firstChild.firstChild.value='';el.parentNode.parentNode.parentNode.appendChild(row);el.onchange=function(){};}
function foreignAddRow(field){field.onchange=function(){};var row=field.parentNode.parentNode.cloneNode(true);var selects=row.getElementsByTagName('select');for(var i=0;i<selects.length;i++){selects[i].name=selects[i].name.replace(/\]/,'1$&');selects[i].selectedIndex=0;}
field.parentNode.parentNode.parentNode.appendChild(row);}
function indexesAddRow(field){field.onchange=function(){};var parent=field.parentNode.parentNode;var row=parent.cloneNode(true);var selects=row.getElementsByTagName('select');for(var i=0;i<selects.length;i++){selects[i].name=selects[i].name.replace(/indexes\[\d+/,'$&1');selects[i].selectedIndex=0;}
var inputs=row.getElementsByTagName('input');for(var i=0;i<inputs.length;i++){inputs[i].name=inputs[i].name.replace(/indexes\[\d+/,'$&1');inputs[i].value='';}
parent.parentNode.appendChild(row);}
function indexesChangeColumn(field,prefix){var columns=field.parentNode.parentNode.getElementsByTagName('select');var names=[];for(var i=0;i<columns.length;i++){var value=selectValue(columns[i]);if(value){names.push(value);}}
field.form[field.name.replace(/\].*/,'][name]')].value=prefix+names.join('_');}
function indexesAddColumn(field,prefix){field.onchange=function(){indexesChangeColumn(field,prefix);};var select=field.form[field.name.replace(/\].*/,'][type]')];if(!select.selectedIndex){select.selectedIndex=3;select.onchange();}
var column=field.parentNode.cloneNode(true);select=column.getElementsByTagName('select')[0];select.name=select.name.replace(/\]\[\d+/,'$&1');select.selectedIndex=0;var input=column.getElementsByTagName('input')[0];input.name=input.name.replace(/\]\[\d+/,'$&1');input.value='';field.parentNode.parentNode.appendChild(column);field.onchange();}
var that,x,y,em,tablePos;function schemaMousedown(el,event){that=el;x=event.clientX-el.offsetLeft;y=event.clientY-el.offsetTop;}
function schemaMousemove(ev){if(that!==undefined){ev=ev||event;var left=(ev.clientX-x)/em;var top=(ev.clientY-y)/em;var divs=that.getElementsByTagName('div');var lineSet={};for(var i=0;i<divs.length;i++){if(divs[i].className=='references'){var div2=document.getElementById((divs[i].id.substr(0,4)=='refs'?'refd':'refs')+divs[i].id.substr(4));var ref=(tablePos[divs[i].title]?tablePos[divs[i].title]:[div2.parentNode.offsetTop/em,0]);var left1=-1;var isTop=true;var id=divs[i].id.replace(/^ref.(.+)-.+/,'$1');if(divs[i].parentNode!=div2.parentNode){left1=Math.min(0,ref[1]-left)-1;divs[i].style.left=left1+'em';divs[i].getElementsByTagName('div')[0].style.width=-left1+'em';var left2=Math.min(0,left-ref[1])-1;div2.style.left=left2+'em';div2.getElementsByTagName('div')[0].style.width=-left2+'em';isTop=(div2.offsetTop+ref[0]*em>divs[i].offsetTop+top*em);}
if(!lineSet[id]){var line=document.getElementById(divs[i].id.replace(/^....(.+)-\d+$/,'refl$1'));var shift=ev.clientY-y-that.offsetTop;line.style.left=(left+left1)+'em';if(isTop){line.style.top=(line.offsetTop+shift)/em+'em';}
if(divs[i].parentNode!=div2.parentNode){line=line.getElementsByTagName('div')[0];line.style.height=(line.offsetHeight+(isTop?-1:1)*shift)/em+'em';}
lineSet[id]=true;}}}
that.style.left=left+'em';that.style.top=top+'em';}}
function schemaMouseup(ev,db){if(that!==undefined){ev=ev||event;tablePos[that.firstChild.firstChild.firstChild.data]=[(ev.clientY-y)/em,(ev.clientX-x)/em];that=undefined;var s='';for(var key in tablePos){s+='_'+key+':'+Math.round(tablePos[key][0]*10000)/10000+'x'+Math.round(tablePos[key][1]*10000)/10000;}
s=encodeURIComponent(s.substr(1));var link=document.getElementById('schema-link');link.href=link.href.replace(/[^=]+$/,'')+s;cookie('adminer_schema-'+db+'='+s,30);}}<?php
}else{header("Content-Type: image/gif");switch($_GET["file"]){case"plus.gif":echo
base64_decode("R0lGODdhEgASAKEAAO7u7gAAAJmZmQAAACwAAAAAEgASAAACIYSPqcvtD00I8cwqKb5v+q8pIAhxlRmhZYi17iPE8kzLBQA7");break;case"cross.gif":echo
base64_decode("R0lGODdhEgASAKEAAO7u7gAAAJmZmQAAACwAAAAAEgASAAACI4SPqcvtDyMKYdZGb355wy6BX3dhlOEx57FK7gtHwkzXNl0AADs=");break;case"up.gif":echo
base64_decode("R0lGODdhEgASAKEAAO7u7gAAAJmZmQAAACwAAAAAEgASAAACIISPqcvtD00IUU4K730T9J5hFTiKEXmaYcW2rgDH8hwXADs=");break;case"down.gif":echo
base64_decode("R0lGODdhEgASAKEAAO7u7gAAAJmZmQAAACwAAAAAEgASAAACIISPqcvtD00I8cwqKb5bV/5cosdMJtmcHca2lQDH8hwXADs=");break;case"arrow.gif":echo
base64_decode("R0lGODlhCAAKAIAAAICAgP///yH5BAEAAAEALAAAAAAIAAoAAAIPBIJplrGLnpQRqtOy3rsAADs=");break;case"loader.gif":echo
base64_decode("R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==");break;}}exit;}function
connection(){global$e;return$e;}function
adminer(){global$b;return$b;}function
idf_unescape($lc){$Ac=substr($lc,-1);return
str_replace($Ac.$Ac,$Ac,substr($lc,1,-1));}function
escape_string($X){return
substr(q($X),1,-1);}function
remove_slashes($Yd,$Tb=false){if(get_magic_quotes_gpc()){while(list($y,$X)=each($Yd)){foreach($X
as$xc=>$W){unset($Yd[$y][$xc]);if(is_array($W)){$Yd[$y][stripslashes($xc)]=$W;$Yd[]=&$Yd[$y][stripslashes($xc)];}else{$Yd[$y][stripslashes($xc)]=($Tb?$W:stripslashes($W));}}}}}function
bracket_escape($lc,$wa=false){static$kf=array(':'=>':1',']'=>':2','['=>':3');return
strtr($lc,($wa?array_flip($kf):$kf));}function
h($Q){return
htmlspecialchars(str_replace("\0","",$Q),ENT_QUOTES);}function
nbsp($Q){return(trim($Q)!=""?h($Q):"&nbsp;");}function
nl_br($Q){return
str_replace("\n","<br>",$Q);}function
checkbox($D,$Y,$Fa,$zc="",$od="",$wc=false){static$t=0;$t++;$J="<input type='checkbox' name='$D' value='".h($Y)."'".($Fa?" checked":"").($od?' onclick="'.h($od).'"':'').($wc?" class='jsonly'":"")." id='checkbox-$t'>";return($zc!=""?"<label for='checkbox-$t'>$J".h($zc)."</label>":$J);}function
optionlist($rd,$we=null,$Bf=false){$J="";foreach($rd
as$xc=>$W){$sd=array($xc=>$W);if(is_array($W)){$J.='<optgroup label="'.h($xc).'">';$sd=$W;}foreach($sd
as$y=>$X){$J.='<option'.($Bf||is_string($y)?' value="'.h($y).'"':'').(($Bf||is_string($y)?(string)$y:$X)===$we?' selected':'').'>'.h($X);}if(is_array($W)){$J.='</optgroup>';}}return$J;}function
html_select($D,$rd,$Y="",$nd=true){if($nd){return"<select name='".h($D)."'".(is_string($nd)?' onchange="'.h($nd).'"':"").">".optionlist($rd,$Y)."</select>";}$J="";foreach($rd
as$y=>$X){$J.="<label><input type='radio' name='".h($D)."' value='".h($y)."'".($y==$Y?" checked":"").">".h($X)."</label>";}return$J;}function
confirm($Wa="",$He=false){return" onclick=\"".($He?"eventStop(event); ":"")."return confirm('".'Are you sure?'.($Wa?" (' + $Wa + ')":"")."');\"";}function
print_fieldset($t,$Fc,$Hf=false,$od=""){echo"<fieldset><legend><a href='#fieldset-$t' onclick=\"".h($od)."return !toggle('fieldset-$t');\">$Fc</a></legend><div id='fieldset-$t'".($Hf?"":" class='hidden'").">\n";}function
bold($Aa){return($Aa?" class='active'":"");}function
odd($J=' class="odd"'){static$s=0;if(!$J){$s=-1;}return($s++%
2?$J:'');}function
js_escape($Q){return
addcslashes($Q,"\r\n'\\/");}function
json_row($y,$X=null){static$Ub=true;if($Ub){echo"{";}if($y!=""){echo($Ub?"":",")."\n\t\"".addcslashes($y,"\r\n\"\\").'": '.(isset($X)?'"'.addcslashes($X,"\r\n\"\\").'"':'undefined');$Ub=false;}else{echo"\n}\n";$Ub=true;}}function
ini_bool($pc){$X=ini_get($pc);return(eregi('^(on|true|yes)$',$X)||(int)$X);}function
sid(){static$J;if(!isset($J)){$J=(SID&&!($_COOKIE&&ini_bool("session.use_cookies")));}return$J;}function
q($Q){global$e;return$e->quote($Q);}function
get_vals($H,$Ma=0){global$e;$J=array();$I=$e->query($H);if(is_object($I)){while($K=$I->fetch_row()){$J[]=$K[$Ma];}}return$J;}function
get_key_vals($H,$f=null){global$e;if(!is_object($f)){$f=$e;}$J=array();$I=$f->query($H);if(is_object($I)){while($K=$I->fetch_row()){$J[$K[0]]=$K[1];}}return$J;}function
get_rows($H,$f=null,$i="<p class='error'>"){global$e;if(!is_object($f)){$f=$e;}$J=array();$I=$f->query($H);if(is_object($I)){while($K=$I->fetch_assoc()){$J[]=$K;}}elseif(!$I&&$e->error&&$i&&defined("PAGE_HEADER")){echo$i.error()."\n";}return$J;}function
unique_array($K,$v){foreach($v
as$u){if(ereg("PRIMARY|UNIQUE",$u["type"])){$J=array();foreach($u["columns"]as$y){if(!isset($K[$y])){continue
2;}$J[$y]=$K[$y];}return$J;}}$J=array();foreach($K
as$y=>$X){if(!preg_match('~^(COUNT\\((\\*|(DISTINCT )?`(?:[^`]|``)+`)\\)|(AVG|GROUP_CONCAT|MAX|MIN|SUM)\\(`(?:[^`]|``)+`\\))$~',$y)){$J[$y]=$X;}}return$J;}function
where($Z){global$x;$J=array();foreach((array)$Z["where"]as$y=>$X){$J[]=idf_escape(bracket_escape($y,1)).(ereg('\\.',$X)||$x=="mssql"?" LIKE ".exact_value(addcslashes($X,"%_\\")):" = ".exact_value($X));}foreach((array)$Z["null"]as$y){$J[]=idf_escape($y)." IS NULL";}return
implode(" AND ",$J);}function
where_check($X){parse_str($X,$Ea);remove_slashes(array(&$Ea));return
where($Ea);}function
where_link($s,$Ma,$Y,$pd="="){return"&where%5B$s%5D%5Bcol%5D=".urlencode($Ma)."&where%5B$s%5D%5Bop%5D=".urlencode((isset($Y)?$pd:"IS NULL"))."&where%5B$s%5D%5Bval%5D=".urlencode($Y);}function
cookie($D,$Y){global$ba;$Dd=array($D,(ereg("\n",$Y)?"":$Y),time()+2592000,preg_replace('~\\?.*~','',$_SERVER["REQUEST_URI"]),"",$ba);if(version_compare(PHP_VERSION,'5.2.0')>=0){$Dd[]=true;}return
call_user_func_array('setcookie',$Dd);}function
restart_session(){if(!ini_bool("session.use_cookies")){session_start();}}function&get_session($y){return$_SESSION[$y][DRIVER][SERVER][$_GET["username"]];}function
set_session($y,$X){$_SESSION[$y][DRIVER][SERVER][$_GET["username"]]=$X;}function
auth_url($nb,$O,$Cf){global$ob;preg_match('~([^?]*)\\??(.*)~',remove_from_uri(implode("|",array_keys($ob))."|username|".session_name()),$B);return"$B[1]?".(sid()?SID."&":"").($nb!="server"||$O!=""?urlencode($nb)."=".urlencode($O)."&":"")."username=".urlencode($Cf).($B[2]?"&$B[2]":"");}function
is_ajax(){return($_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest");}function
redirect($A,$Sc=null){if(isset($Sc)){restart_session();$_SESSION["messages"][preg_replace('~^[^?]*~','',(isset($A)?$A:$_SERVER["REQUEST_URI"]))][]=$Sc;}if(isset($A)){if($A==""){$A=".";}header((is_ajax()?"X-AJAX-Redirect":"Location").": $A");exit;}}function
query_redirect($H,$A,$Sc,$de=true,$Jb=true,$Pb=false){global$e,$i,$b;if($Jb){$Pb=!$e->query($H);}$De="";if($H){$De=$b->messageQuery("$H;");}if($Pb){$i=error().$De;return
false;}if($de){redirect($A,$Sc.$De);}return
true;}function
queries($H=null){global$e;static$be=array();if(!isset($H)){return
implode(";\n",$be);}$be[]=(ereg(';$',$H)?"DELIMITER ;;\n$H;\nDELIMITER ":$H);return$e->query($H);}function
apply_queries($H,$Ve,$Fb='table'){foreach($Ve
as$S){if(!queries("$H ".$Fb($S))){return
false;}}return
true;}function
queries_redirect($A,$Sc,$de){return
query_redirect(queries(),$A,$Sc,$de,false,!$de);}function
remove_from_uri($Cd=""){return
substr(preg_replace("~(?<=[?&])($Cd".(SID?"":"|".session_name()).")=[^&]*&~",'',"$_SERVER[REQUEST_URI]&"),0,-1);}function
pagination($E,$bb){return" ".($E==$bb?$E+1:'<a href="'.h(remove_from_uri("page").($E?"&page=$E":"")).'">'.($E+1)."</a>");}function
get_file($y,$gb=false){$Rb=$_FILES[$y];if(!$Rb||$Rb["error"]){return$Rb["error"];}$J=file_get_contents($gb&&ereg('\\.gz$',$Rb["name"])?"compress.zlib://$Rb[tmp_name]":($gb&&ereg('\\.bz2$',$Rb["name"])?"compress.bzip2://$Rb[tmp_name]":$Rb["tmp_name"]));if($gb){$Ee=substr($J,0,3);if(function_exists("iconv")&&ereg("^\xFE\xFF|^\xFF\xFE",$Ee,$je)){$J=iconv("utf-16","utf-8",$J);}elseif($Ee=="\xEF\xBB\xBF"){$J=substr($J,3);}}return$J;}function
upload_error($i){$Qc=($i==UPLOAD_ERR_INI_SIZE?ini_get("upload_max_filesize"):null);return($i?'Unable to upload a file.'.($Qc?" ".sprintf('Maximum allowed file size is %sB.',$Qc):""):'File does not exist.');}function
repeat_pattern($F,$Gc){return
str_repeat("$F{0,65535}",$Gc/65535)."$F{0,".($Gc
%
65535)."}";}function
is_utf8($X){return(preg_match('~~u',$X)&&!preg_match('~[\\0-\\x8\\xB\\xC\\xE-\\x1F]~',$X));}function
shorten_utf8($Q,$Gc=80,$Le=""){if(!preg_match("(^(".repeat_pattern("[\t\r\n -\x{FFFF}]",$Gc).")($)?)u",$Q,$B)){preg_match("(^(".repeat_pattern("[\t\r\n -~]",$Gc).")($)?)",$Q,$B);}return
h($B[1]).$Le.(isset($B[2])?"":"<i>...</i>");}function
friendly_url($X){return
preg_replace('~[^a-z0-9_]~i','-',$X);}function
hidden_fields($Yd,$mc=array()){while(list($y,$X)=each($Yd)){if(is_array($X)){foreach($X
as$xc=>$W){$Yd[$y."[$xc]"]=$W;}}elseif(!in_array($y,$mc)){echo'<input type="hidden" name="'.h($y).'" value="'.h($X).'">';}}}function
hidden_fields_get(){echo(sid()?'<input type="hidden" name="'.session_name().'" value="'.h(session_id()).'">':''),(SERVER!==null?'<input type="hidden" name="'.DRIVER.'" value="'.h(SERVER).'">':""),'<input type="hidden" name="username" value="'.h($_GET["username"]).'">';}function
column_foreign_keys($S){global$b;$J=array();foreach($b->foreignKeys($S)as$l){foreach($l["source"]as$X){$J[$X][]=$l;}}return$J;}function
enum_input($V,$ta,$j,$Y,$zb=null){global$b;preg_match_all("~'((?:[^']|'')*)'~",$j["length"],$Lc);$J=(isset($zb)?"<label><input type='$V'$ta value='$zb'".((is_array($Y)?in_array($zb,$Y):$Y===0)?" checked":"")."><i>".'empty'."</i></label>":"");foreach($Lc[1]as$s=>$X){$X=stripcslashes(str_replace("''","'",$X));$Fa=(is_int($Y)?$Y==$s+1:(is_array($Y)?in_array($s+1,$Y):$Y===$X));$J.=" <label><input type='$V'$ta value='".($s+1)."'".($Fa?' checked':'').'>'.h($b->editVal($X,$j)).'</label>';}return$J;}function
input($j,$Y,$o){global$sf,$b,$x;$D=h(bracket_escape($j["field"]));echo"<td class='function'>";$le=($x=="mssql"&&$j["auto_increment"]);if($le&&!$_POST["save"]){$o=null;}$p=(isset($_GET["select"])||$le?array("orig"=>'original'):array())+$b->editFunctions($j);$ta=" name='fields[$D]'";if($j["type"]=="enum"){echo
nbsp($p[""])."<td>".$b->editInput($_GET["edit"],$j,$ta,$Y);}else{$Ub=0;foreach($p
as$y=>$X){if($y===""||!$X){break;}$Ub++;}$nd=($Ub?" onchange=\"var f = this.form['function[".h(js_escape(bracket_escape($j["field"])))."]']; if ($Ub > f.selectedIndex) f.selectedIndex = $Ub;\"":"");$ta.=$nd;echo(count($p)>1?html_select("function[$D]",$p,!isset($o)||in_array($o,$p)||isset($p[$o])?$o:"","functionChange(this);"):nbsp(reset($p))).'<td>';$rc=$b->editInput($_GET["edit"],$j,$ta,$Y);if($rc!=""){echo$rc;}elseif($j["type"]=="set"){preg_match_all("~'((?:[^']|'')*)'~",$j["length"],$Lc);foreach($Lc[1]as$s=>$X){$X=stripcslashes(str_replace("''","'",$X));$Fa=(is_int($Y)?($Y>>$s)&1:in_array($X,explode(",",$Y),true));echo" <label><input type='checkbox' name='fields[$D][$s]' value='".(1<<$s)."'".($Fa?' checked':'')."$nd>".h($b->editVal($X,$j)).'</label>';}}elseif(ereg('blob|bytea|raw|file',$j["type"])&&ini_bool("file_uploads")){echo"<input type='file' name='fields-$D'$nd>";}elseif(ereg('text|lob',$j["type"])){echo"<textarea ".($x!="sqlite"||ereg("\n",$Y)?"cols='50' rows='12'":"cols='30' rows='1' style='height: 1.2em;'")."$ta>".h($Y).'</textarea>';}else{$Rc=(!ereg('int',$j["type"])&&preg_match('~^(\\d+)(,(\\d+))?$~',$j["length"],$B)?((ereg("binary",$j["type"])?2:1)*$B[1]+($B[3]?1:0)+($B[2]&&!$j["unsigned"]?1:0)):($sf[$j["type"]]?$sf[$j["type"]]+($j["unsigned"]?0:1):0));echo"<input value='".h($Y)."'".($Rc?" maxlength='$Rc'":"").(ereg('char|binary',$j["type"])&&$Rc>20?" size='40'":"")."$ta>";}}}function
process_input($j){global$b;$lc=bracket_escape($j["field"]);$o=$_POST["function"][$lc];$Y=$_POST["fields"][$lc];if($j["type"]=="enum"){if($Y==-1){return
false;}if($Y==""){return"NULL";}return+$Y;}if($j["auto_increment"]&&$Y==""){return
null;}if($o=="orig"){return($j["on_update"]=="CURRENT_TIMESTAMP"?idf_escape($j["field"]):false);}if($o=="NULL"){return"NULL";}if($j["type"]=="set"){return
array_sum((array)$Y);}if(ereg('blob|bytea|raw|file',$j["type"])&&ini_bool("file_uploads")){$Rb=get_file("fields-$lc");if(!is_string($Rb)){return
false;}return
q($Rb);}return$b->processInput($j,$Y,$o);}function
search_tables(){global$b,$e;$_GET["where"][0]["op"]="LIKE %%";$_GET["where"][0]["val"]=$_POST["query"];$n=false;foreach(table_status()as$S=>$T){$D=$b->tableName($T);if(isset($T["Engine"])&&$D!=""&&(!$_POST["tables"]||in_array($S,$_POST["tables"]))){$I=$e->query("SELECT".limit("1 FROM ".table($S)," WHERE ".implode(" AND ",$b->selectSearchProcess(fields($S),array())),1));if($I->fetch_row()){if(!$n){echo"<ul>\n";$n=true;}echo"<li><a href='".h(ME."select=".urlencode($S)."&where[0][op]=".urlencode($_GET["where"][0]["op"])."&where[0][val]=".urlencode($_GET["where"][0]["val"]))."'>$D</a>\n";}}}echo($n?"</ul>":"<p class='message'>".'No tables.')."\n";}function
dump_headers($kc,$Zc=false){global$b;$J=$b->dumpHeaders($kc,$Zc);$Ad=$_POST["output"];if($Ad!="text"){header("Content-Disposition: attachment; filename=".friendly_url($kc!=""?$kc:(SERVER!=""?SERVER:"localhost")).".$J".($Ad!="file"&&!ereg('[^0-9a-z]',$Ad)?".$Ad":""));}session_write_close();return$J;}function
dump_csv($K){foreach($K
as$y=>$X){if(preg_match("~[\"\n,;\t]~",$X)||$X===""){$K[$y]='"'.str_replace('"','""',$X).'"';}}echo
implode(($_POST["format"]=="csv"?",":($_POST["format"]=="tsv"?"\t":";")),$K)."\r\n";}function
apply_sql_function($o,$Ma){return($o?($o=="unixepoch"?"DATETIME($Ma, '$o')":($o=="count distinct"?"COUNT(DISTINCT ":strtoupper("$o("))."$Ma)"):$Ma);}function
password_file(){$kb=ini_get("upload_tmp_dir");if(!$kb){if(function_exists('sys_get_temp_dir')){$kb=sys_get_temp_dir();}else{$Sb=@tempnam("","");if(!$Sb){return
false;}$kb=dirname($Sb);unlink($Sb);}}$Sb="$kb/adminer.key";$J=@file_get_contents($Sb);if($J){return$J;}$Zb=@fopen($Sb,"w");if($Zb){$J=md5(uniqid(mt_rand(),true));fwrite($Zb,$J);fclose($Zb);}return$J;}function
is_mail($wb){$sa='[-a-z0-9!#$%&\'*+/=?^_`{|}~]';$mb='[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';$F="$sa+(\\.$sa+)*@($mb?\\.)+$mb";return
preg_match("(^$F(,\\s*$F)*\$)i",$wb);}function
is_url($Q){$mb='[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';return(preg_match("~^(https?)://($mb?\\.)+$mb(:\\d+)?(/.*)?(\\?.*)?(#.*)?\$~i",$Q,$B)?strtolower($B[1]):"");}global$b,$e,$ob,$ub,$Cb,$i,$p,$ec,$ba,$qc,$x,$ca,$_c,$md,$Je,$U,$mf,$sf,$zf,$ga;if(!isset($_SERVER["REQUEST_URI"])){$_SERVER["REQUEST_URI"]=$_SERVER["ORIG_PATH_INFO"].($_SERVER["QUERY_STRING"]!=""?"?$_SERVER[QUERY_STRING]":"");}$ba=$_SERVER["HTTPS"]&&strcasecmp($_SERVER["HTTPS"],"off");@ini_set("session.use_trans_sid",false);if(!defined("SID")){session_name("adminer_sid");$Dd=array(0,preg_replace('~\\?.*~','',$_SERVER["REQUEST_URI"]),"",$ba);if(version_compare(PHP_VERSION,'5.2.0')>=0){$Dd[]=true;}call_user_func_array('session_set_cookie_params',$Dd);session_start();}remove_slashes(array(&$_GET,&$_POST,&$_COOKIE),$Tb);if(function_exists("set_magic_quotes_runtime")){set_magic_quotes_runtime(false);}@set_time_limit(0);@ini_set("zend.ze1_compatibility_mode",false);@ini_set("precision",20);function
get_lang(){return'en';}function
lang($lf,$fd){$Nd=($fd==1?0:1);return
sprintf($lf[$Nd],$fd);}if(extension_loaded('pdo')){class
Min_PDO
extends
PDO{var$_result,$server_info,$affected_rows,$error;function
__construct(){}function
dsn($rb,$Cf,$Kd,$Ib='auth_error'){set_exception_handler($Ib);parent::__construct($rb,$Cf,$Kd);restore_exception_handler();$this->setAttribute(13,array('Min_PDOStatement'));$this->server_info=$this->getAttribute(4);}function
query($H,$tf=false){$I=parent::query($H);if(!$I){$Db=$this->errorInfo();$this->error=$Db[2];return
false;}$this->store_result($I);return$I;}function
multi_query($H){return$this->_result=$this->query($H);}function
store_result($I=null){if(!$I){$I=$this->_result;}if($I->columnCount()){$I->num_rows=$I->rowCount();return$I;}$this->affected_rows=$I->rowCount();return
true;}function
next_result(){return$this->_result->nextRowset();}function
result($H,$j=0){$I=$this->query($H);if(!$I){return
false;}$K=$I->fetch();return$K[$j];}}class
Min_PDOStatement
extends
PDOStatement{var$_offset=0,$num_rows;function
fetch_assoc(){return$this->fetch(2);}function
fetch_row(){return$this->fetch(3);}function
fetch_field(){$K=(object)$this->getColumnMeta($this->_offset++);$K->orgtable=$K->table;$K->orgname=$K->name;$K->charsetnr=(in_array("blob",$K->flags)?63:0);return$K;}}}$ob=array();$ob=array("server"=>"MySQL")+$ob;if(!defined("DRIVER")){$Qd=array("MySQLi","MySQL","PDO_MySQL");define("DRIVER","server");if(extension_loaded("mysqli")){class
Min_DB
extends
MySQLi{var$extension="MySQLi";function
Min_DB(){parent::init();}function
connect($O,$Cf,$Kd){mysqli_report(MYSQLI_REPORT_OFF);list($ic,$Md)=explode(":",$O,2);$J=@$this->real_connect(($O!=""?$ic:ini_get("mysqli.default_host")),($O.$Cf!=""?$Cf:ini_get("mysqli.default_user")),($O.$Cf.$Kd!=""?$Kd:ini_get("mysqli.default_pw")),null,(is_numeric($Md)?$Md:ini_get("mysqli.default_port")),(!is_numeric($Md)?$Md:null));if($J){if(method_exists($this,'set_charset')){$this->set_charset("utf8");}else{$this->query("SET NAMES utf8");}}return$J;}function
result($H,$j=0){$I=$this->query($H);if(!$I){return
false;}$K=$I->fetch_array();return$K[$j];}function
quote($Q){return"'".$this->escape_string($Q)."'";}}}elseif(extension_loaded("mysql")){class
Min_DB{var$extension="MySQL",$server_info,$affected_rows,$error,$_link,$_result;function
connect($O,$Cf,$Kd){$this->_link=@mysql_connect(($O!=""?$O:ini_get("mysql.default_host")),("$O$Cf"!=""?$Cf:ini_get("mysql.default_user")),("$O$Cf$Kd"!=""?$Kd:ini_get("mysql.default_password")),true,131072);if($this->_link){$this->server_info=mysql_get_server_info($this->_link);if(function_exists('mysql_set_charset')){mysql_set_charset("utf8",$this->_link);}else{$this->query("SET NAMES utf8");}}else{$this->error=mysql_error();}return(bool)$this->_link;}function
quote($Q){return"'".mysql_real_escape_string($Q,$this->_link)."'";}function
select_db($eb){return
mysql_select_db($eb,$this->_link);}function
query($H,$tf=false){$I=@($tf?mysql_unbuffered_query($H,$this->_link):mysql_query($H,$this->_link));if(!$I){$this->error=mysql_error($this->_link);return
false;}if($I===true){$this->affected_rows=mysql_affected_rows($this->_link);$this->info=mysql_info($this->_link);return
true;}return
new
Min_Result($I);}function
multi_query($H){return$this->_result=$this->query($H);}function
store_result(){return$this->_result;}function
next_result(){return
false;}function
result($H,$j=0){$I=$this->query($H);if(!$I||!$I->num_rows){return
false;}return
mysql_result($I->_result,0,$j);}}class
Min_Result{var$num_rows,$_result,$_offset=0;function
Min_Result($I){$this->_result=$I;$this->num_rows=mysql_num_rows($I);}function
fetch_assoc(){return
mysql_fetch_assoc($this->_result);}function
fetch_row(){return
mysql_fetch_row($this->_result);}function
fetch_field(){$J=mysql_fetch_field($this->_result,$this->_offset++);$J->orgtable=$J->table;$J->orgname=$J->name;$J->charsetnr=($J->blob?63:0);return$J;}function
__destruct(){mysql_free_result($this->_result);}}}elseif(extension_loaded("pdo_mysql")){class
Min_DB
extends
Min_PDO{var$extension="PDO_MySQL";function
connect($O,$Cf,$Kd){$this->dsn("mysql:host=".str_replace(":",";unix_socket=",preg_replace('~:(\\d)~',';port=\\1',$O)),$Cf,$Kd);$this->query("SET NAMES utf8");return
true;}function
select_db($eb){return$this->query("USE ".idf_escape($eb));}function
query($H,$tf=false){$this->setAttribute(1000,!$tf);return
parent::query($H,$tf);}}}function
idf_escape($lc){return"`".str_replace("`","``",$lc)."`";}function
table($lc){return
idf_escape($lc);}function
connect(){global$b;$e=new
Min_DB;$ab=$b->credentials();if($e->connect($ab[0],$ab[1],$ab[2])){$e->query("SET sql_quote_show_create = 1");return$e;}$J=$e->error;if(function_exists('iconv')&&!is_utf8($J)&&strlen($M=iconv("windows-1250","utf-8",$J))>strlen($J)){$J=$M;}return$J;}function
get_databases($Vb=true){global$e;$J=&get_session("dbs");if(!isset($J)){if($Vb){restart_session();ob_flush();flush();}$J=get_vals($e->server_info>=5?"SELECT SCHEMA_NAME FROM information_schema.SCHEMATA":"SHOW DATABASES");}return$J;}function
limit($H,$Z,$z,$hd=0,$ye=" "){return" $H$Z".(isset($z)?$ye."LIMIT $z".($hd?" OFFSET $hd":""):"");}function
limit1($H,$Z){return
limit($H,$Z,1);}function
db_collation($h,$c){global$e;$J=null;$Xa=$e->result("SHOW CREATE DATABASE ".idf_escape($h),1);if(preg_match('~ COLLATE ([^ ]+)~',$Xa,$B)){$J=$B[1];}elseif(preg_match('~ CHARACTER SET ([^ ]+)~',$Xa,$B)){$J=$c[$B[1]][-1];}return$J;}function
engines(){$J=array();foreach(get_rows("SHOW ENGINES")as$K){if(ereg("YES|DEFAULT",$K["Support"])){$J[]=$K["Engine"];}}return$J;}function
logged_user(){global$e;return$e->result("SELECT USER()");}function
tables_list(){global$e;return
get_key_vals("SHOW".($e->server_info>=5?" FULL":"")." TABLES");}function
count_tables($g){$J=array();foreach($g
as$h){$J[$h]=count(get_vals("SHOW TABLES IN ".idf_escape($h)));}return$J;}function
table_status($D=""){$J=array();foreach(get_rows("SHOW TABLE STATUS".($D!=""?" LIKE ".q(addcslashes($D,"%_")):""))as$K){if($K["Engine"]=="InnoDB"){$K["Comment"]=preg_replace('~(?:(.+); )?InnoDB free: .*~','\\1',$K["Comment"]);}if(!isset($K["Rows"])){$K["Comment"]="";}if($D!=""){return$K;}$J[$K["Name"]]=$K;}return$J;}function
is_view($T){return!isset($T["Rows"]);}function
fk_support($T){return
eregi("InnoDB|IBMDB2I",$T["Engine"]);}function
fields($S){$J=array();foreach(get_rows("SHOW FULL COLUMNS FROM ".table($S))as$K){preg_match('~^([^( ]+)(?:\\((.+)\\))?( unsigned)?( zerofill)?$~',$K["Type"],$B);$J[$K["Field"]]=array("field"=>$K["Field"],"full_type"=>$K["Type"],"type"=>$B[1],"length"=>$B[2],"unsigned"=>ltrim($B[3].$B[4]),"default"=>($K["Default"]!=""||ereg("char",$B[1])?$K["Default"]:null),"null"=>($K["Null"]=="YES"),"auto_increment"=>($K["Extra"]=="auto_increment"),"on_update"=>(eregi('^on update (.+)',$K["Extra"],$B)?$B[1]:""),"collation"=>$K["Collation"],"privileges"=>array_flip(explode(",",$K["Privileges"])),"comment"=>$K["Comment"],"primary"=>($K["Key"]=="PRI"),);}return$J;}function
indexes($S,$f=null){$J=array();foreach(get_rows("SHOW INDEX FROM ".table($S),$f)as$K){$J[$K["Key_name"]]["type"]=($K["Key_name"]=="PRIMARY"?"PRIMARY":($K["Index_type"]=="FULLTEXT"?"FULLTEXT":($K["Non_unique"]?"INDEX":"UNIQUE")));$J[$K["Key_name"]]["columns"][]=$K["Column_name"];$J[$K["Key_name"]]["lengths"][]=$K["Sub_part"];}return$J;}function
foreign_keys($S){global$e,$md;static$F='`(?:[^`]|``)+`';$J=array();$Ya=$e->result("SHOW CREATE TABLE ".table($S),1);if($Ya){preg_match_all("~CONSTRAINT ($F) FOREIGN KEY \\(((?:$F,? ?)+)\\) REFERENCES ($F)(?:\\.($F))? \\(((?:$F,? ?)+)\\)(?: ON DELETE ($md))?(?: ON UPDATE ($md))?~",$Ya,$Lc,PREG_SET_ORDER);foreach($Lc
as$B){preg_match_all("~$F~",$B[2],$Be);preg_match_all("~$F~",$B[5],$Ye);$J[idf_unescape($B[1])]=array("db"=>idf_unescape($B[4]!=""?$B[3]:$B[4]),"table"=>idf_unescape($B[4]!=""?$B[4]:$B[3]),"source"=>array_map('idf_unescape',$Be[0]),"target"=>array_map('idf_unescape',$Ye[0]),"on_delete"=>$B[6],"on_update"=>$B[7],);}}return$J;}function
view($D){global$e;return
array("select"=>preg_replace('~^(?:[^`]|`[^`]*`)*\\s+AS\\s+~isU','',$e->result("SHOW CREATE VIEW ".table($D),1)));}function
collations(){$J=array();foreach(get_rows("SHOW COLLATION")as$K){if($K["Default"]){$J[$K["Charset"]][-1]=$K["Collation"];}else{$J[$K["Charset"]][]=$K["Collation"];}}ksort($J);foreach($J
as$y=>$X){asort($J[$y]);}return$J;}function
information_schema($h){global$e;return($e->server_info>=5&&$h=="information_schema");}function
error(){global$e;return
h(preg_replace('~^You have an error.*syntax to use~U',"Syntax error",$e->error));}function
exact_value($X){return
q($X)." COLLATE utf8_bin";}function
create_database($h,$Ka){set_session("dbs",null);return
queries("CREATE DATABASE ".idf_escape($h).($Ka?" COLLATE ".q($Ka):""));}function
drop_databases($g){set_session("dbs",null);return
apply_queries("DROP DATABASE",$g,'idf_escape');}function
rename_database($D,$Ka){if(create_database($D,$Ka)){$ke=array();foreach(tables_list()as$S=>$V){$ke[]=table($S)." TO ".idf_escape($D).".".table($S);}if(!$ke||queries("RENAME TABLE ".implode(", ",$ke))){queries("DROP DATABASE ".idf_escape(DB));return
true;}}return
false;}function
auto_increment(){$va=" PRIMARY KEY";if($_GET["create"]!=""&&$_POST["auto_increment_col"]){foreach(indexes($_GET["create"])as$u){if(in_array($_POST["fields"][$_POST["auto_increment_col"]]["orig"],$u["columns"],true)){$va="";break;}if($u["type"]=="PRIMARY"){$va=" UNIQUE";}}}return" AUTO_INCREMENT$va";}function
alter_table($S,$D,$k,$Wb,$Pa,$Ab,$Ka,$ua,$Hd){$ra=array();foreach($k
as$j){$ra[]=($j[1]?($S!=""?($j[0]!=""?"CHANGE ".idf_escape($j[0]):"ADD"):" ")." ".implode($j[1]).($S!=""?" $j[2]":""):"DROP ".idf_escape($j[0]));}$ra=array_merge($ra,$Wb);$Fe="COMMENT=".q($Pa).($Ab?" ENGINE=".q($Ab):"").($Ka?" COLLATE ".q($Ka):"").($ua!=""?" AUTO_INCREMENT=$ua":"").$Hd;if($S==""){return
queries("CREATE TABLE ".table($D)." (\n".implode(",\n",$ra)."\n) $Fe");}if($S!=$D){$ra[]="RENAME TO ".table($D);}$ra[]=$Fe;return
queries("ALTER TABLE ".table($S)."\n".implode(",\n",$ra));}function
alter_indexes($S,$ra){foreach($ra
as$y=>$X){$ra[$y]=($X[2]=="DROP"?"\nDROP INDEX ".idf_escape($X[1]):"\nADD $X[0] ".($X[0]=="PRIMARY"?"KEY ":"").($X[1]!=""?idf_escape($X[1])." ":"").$X[2]);}return
queries("ALTER TABLE ".table($S).implode(",",$ra));}function
truncate_tables($Ve){return
apply_queries("TRUNCATE TABLE",$Ve);}function
drop_views($Gf){return
queries("DROP VIEW ".implode(", ",array_map('table',$Gf)));}function
drop_tables($Ve){return
queries("DROP TABLE ".implode(", ",array_map('table',$Ve)));}function
move_tables($Ve,$Gf,$Ye){$ke=array();foreach(array_merge($Ve,$Gf)as$S){$ke[]=table($S)." TO ".idf_escape($Ye).".".table($S);}return
queries("RENAME TABLE ".implode(", ",$ke));}function
copy_tables($Ve,$Gf,$Ye){queries("SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO'");foreach($Ve
as$S){$D=($Ye==DB?table("copy_$S"):idf_escape($Ye).".".table($S));if(!queries("DROP TABLE IF EXISTS $D")||!queries("CREATE TABLE $D LIKE ".table($S))||!queries("INSERT INTO $D SELECT * FROM ".table($S))){return
false;}}foreach($Gf
as$S){$D=($Ye==DB?table("copy_$S"):idf_escape($Ye).".".table($S));$Ff=view($S);if(!queries("DROP VIEW IF EXISTS $D")||!queries("CREATE VIEW $D AS $Ff[select]")){return
false;}}return
true;}function
trigger($D){if($D==""){return
array();}$L=get_rows("SHOW TRIGGERS WHERE `Trigger` = ".q($D));return
reset($L);}function
triggers($S){$J=array();foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($S,"%_")))as$K){$J[$K["Trigger"]]=array($K["Timing"],$K["Event"]);}return$J;}function
trigger_options(){return
array("Timing"=>array("BEFORE","AFTER"),"Type"=>array("FOR EACH ROW"),);}function
routine($D,$V){global$e,$Cb,$qc,$sf;$pa=array("bool","boolean","integer","double precision","real","dec","numeric","fixed","national char","national varchar");$rf="((".implode("|",array_merge(array_keys($sf),$pa)).")(?:\\s*\\(((?:[^'\")]*|$Cb)+)\\))?\\s*(zerofill\\s*)?(unsigned(?:\\s+zerofill)?)?)(?:\\s*(?:CHARSET|CHARACTER\\s+SET)\\s*['\"]?([^'\"\\s]+)['\"]?)?";$F="\\s*(".($V=="FUNCTION"?"":$qc).")?\\s*(?:`((?:[^`]|``)*)`\\s*|\\b(\\S+)\\s+)$rf";$Xa=$e->result("SHOW CREATE $V ".idf_escape($D),2);preg_match("~\\(((?:$F\\s*,?)*)\\)".($V=="FUNCTION"?"\\s*RETURNS\\s+$rf":"")."\\s*(.*)~is",$Xa,$B);$k=array();preg_match_all("~$F\\s*,?~is",$B[1],$Lc,PREG_SET_ORDER);foreach($Lc
as$Cd){$D=str_replace("``","`",$Cd[2]).$Cd[3];$k[]=array("field"=>$D,"type"=>strtolower($Cd[5]),"length"=>preg_replace_callback("~$Cb~s",'normalize_enum',$Cd[6]),"unsigned"=>strtolower(preg_replace('~\\s+~',' ',trim("$Cd[8] $Cd[7]"))),"full_type"=>$Cd[4],"inout"=>strtoupper($Cd[1]),"collation"=>strtolower($Cd[9]),);}if($V!="FUNCTION"){return
array("fields"=>$k,"definition"=>$B[11]);}return
array("fields"=>$k,"returns"=>array("type"=>$B[12],"length"=>$B[13],"unsigned"=>$B[15],"collation"=>$B[16]),"definition"=>$B[17],"language"=>"SQL",);}function
routines(){return
get_rows("SELECT * FROM information_schema.ROUTINES WHERE ROUTINE_SCHEMA = ".q(DB));}function
routine_languages(){return
array();}function
begin(){return
queries("BEGIN");}function
insert_into($S,$P){return
queries("INSERT INTO ".table($S)." (".implode(", ",array_keys($P)).")\nVALUES (".implode(", ",$P).")");}function
insert_update($S,$P,$Td){foreach($P
as$y=>$X){$P[$y]="$y = $X";}$_f=implode(", ",$P);return
queries("INSERT INTO ".table($S)." SET $_f ON DUPLICATE KEY UPDATE $_f");}function
last_id(){global$e;return$e->result("SELECT LAST_INSERT_ID()");}function
explain($e,$H){return$e->query("EXPLAIN $H");}function
found_rows($T,$Z){return($Z||$T["Engine"]!="InnoDB"?null:$T["Rows"]);}function
types(){return
array();}function
schemas(){return
array();}function
get_schema(){return"";}function
set_schema($ue){return
true;}function
create_sql($S,$ua){global$e;$J=$e->result("SHOW CREATE TABLE ".table($S),1);if(!$ua){$J=preg_replace('~ AUTO_INCREMENT=\\d+~','',$J);}return$J;}function
truncate_sql($S){return"TRUNCATE ".table($S);}function
use_sql($eb){return"USE ".idf_escape($eb);}function
trigger_sql($S,$R){$J="";foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($S,"%_")),null,"-- ")as$K){$J.="\n".($R=='CREATE+ALTER'?"DROP TRIGGER IF EXISTS ".idf_escape($K["Trigger"]).";;\n":"")."CREATE TRIGGER ".idf_escape($K["Trigger"])." $K[Timing] $K[Event] ON ".table($K["Table"])." FOR EACH ROW\n$K[Statement];;\n";}return$J;}function
show_variables(){return
get_key_vals("SHOW VARIABLES");}function
process_list(){return
get_rows("SHOW FULL PROCESSLIST");}function
show_status(){return
get_key_vals("SHOW STATUS");}function
support($Qb){global$e;return!ereg("scheme|sequence|type".($e->server_info<5.1?"|event|partitioning".($e->server_info<5?"|view|routine|trigger":""):""),$Qb);}$x="sql";$sf=array();$Je=array();foreach(array('Numbers'=>array("tinyint"=>3,"smallint"=>5,"mediumint"=>8,"int"=>10,"bigint"=>20,"decimal"=>66,"float"=>12,"double"=>21),'Date and time'=>array("date"=>10,"datetime"=>19,"timestamp"=>19,"time"=>10,"year"=>4),'Strings'=>array("char"=>255,"varchar"=>65535,"tinytext"=>255,"text"=>65535,"mediumtext"=>16777215,"longtext"=>4294967295),'Binary'=>array("bit"=>20,"binary"=>255,"varbinary"=>65535,"tinyblob"=>255,"blob"=>65535,"mediumblob"=>16777215,"longblob"=>4294967295),'Lists'=>array("enum"=>65535,"set"=>64),)as$y=>$X){$sf+=$X;$Je[$y]=array_keys($X);}$zf=array("unsigned","zerofill","unsigned zerofill");$qd=array("=","<",">","<=",">=","!=","LIKE","LIKE %%","REGEXP","IN","IS NULL","NOT LIKE","NOT REGEXP","NOT IN","IS NOT NULL","");$p=array("char_length","date","from_unixtime","hex","lower","round","sec_to_time","time_to_sec","upper");$ec=array("avg","count","count distinct","group_concat","max","min","sum");$ub=array(array("char"=>"md5/sha1/password/encrypt/uuid","binary"=>"md5/sha1/hex","date|time"=>"now",),array("int|float|double|decimal"=>"+/-","date"=>"+ interval/- interval","time"=>"addtime/subtime","char|text"=>"concat",));}define("SERVER",$_GET[DRIVER]);define("DB",$_GET["db"]);define("ME",preg_replace('~^[^?]*/([^?]*).*~','\\1',$_SERVER["REQUEST_URI"]).'?'.(sid()?SID.'&':'').(SERVER!==null?DRIVER."=".urlencode(SERVER).'&':'').(isset($_GET["username"])?"username=".urlencode($_GET["username"]).'&':'').(DB!=""?'db='.urlencode(DB).'&'.(isset($_GET["ns"])?"ns=".urlencode($_GET["ns"])."&":""):''));$ga="3.3.3";class
Adminer{var$operators;function
name(){return"<a href='http://www.adminer.org/' id='h1'>Adminer</a>";}function
credentials(){return
array(SERVER,$_GET["username"],get_session("pwds"));}function
permanentLogin(){return
password_file();}function
database(){return
DB;}function
headers(){return
true;}function
head(){return
true;}function
loginForm(){global$ob;echo'<table cellspacing="0">
<tr><th>System<td>',html_select("driver",$ob,DRIVER,"loginDriver(this);"),'<tr><th>Server<td><input name="server" value="',h(SERVER),'" title="hostname[:port]">
<tr><th>Username<td><input id="username" name="username" value="',h($_GET["username"]);?>">
<tr><th>Password<td><input type="password" name="password">
</table>
<script type="text/javascript">
var username = document.getElementById('username');
username.focus();
username.form['driver'].onchange();
</script>
<?php

echo"<p><input type='submit' value='".'Login'."'>\n",checkbox("permanent",1,$_COOKIE["adminer_permanent"],'Permanent login')."\n";}function
login($Jc,$Kd){return
true;}function
tableName($Qe){return
h($Qe["Name"]);}function
fieldName($j,$td=0){return'<span title="'.h($j["full_type"]).'">'.h($j["field"]).'</span>';}function
selectLinks($Qe,$P=""){echo'<p class="tabs">';$Ic=array("select"=>'Select data',"table"=>'Show structure');if(is_view($Qe)){$Ic["view"]='Alter view';}else{$Ic["create"]='Alter table';}if(isset($P)){$Ic["edit"]='New item';}foreach($Ic
as$y=>$X){echo" <a href='".h(ME)."$y=".urlencode($Qe["Name"]).($y=="edit"?$P:"")."'".bold(isset($_GET[$y])).">$X</a>";}echo"\n";}function
foreignKeys($S){return
foreign_keys($S);}function
backwardKeys($S,$Pe){return
array();}function
backwardKeysPrint($xa,$K){}function
selectQuery($H){global$x;return"<p><a href='".h(remove_from_uri("page"))."&amp;page=last' title='".'Last page'."'>&gt;&gt;</a> <code class='jush-$x'>".h(str_replace("\n"," ",$H))."</code> <a href='".h(ME)."sql=".urlencode($H)."'>".'Edit'."</a></p>\n";}function
rowDescription($S){return"";}function
rowDescriptions($L,$Xb){return$L;}function
selectVal($X,$_,$j){$J=($X!="<i>NULL</i>"&&ereg("char|binary",$j["type"])&&!ereg("var",$j["type"])?"<code>$X</code>":$X);if(ereg('blob|bytea|raw|file',$j["type"])&&!is_utf8($X)){$J=lang(array('%d byte','%d bytes'),strlen(html_entity_decode($X,ENT_QUOTES)));}return($_?"<a href='$_'>$J</a>":$J);}function
editVal($X,$j){return(ereg("binary",$j["type"])?reset(unpack("H*",$X)):$X);}function
selectColumnsPrint($N,$d){global$p,$ec;print_fieldset("select",'Select',$N);$s=0;$bc=array('Functions'=>$p,'Aggregation'=>$ec);foreach($N
as$y=>$X){$X=$_GET["columns"][$y];echo"<div>".html_select("columns[$s][fun]",array(-1=>"")+$bc,$X["fun"]),"(<select name='columns[$s][col]'><option>".optionlist($d,$X["col"],true)."</select>)</div>\n";$s++;}echo"<div>".html_select("columns[$s][fun]",array(-1=>"")+$bc,"","this.nextSibling.nextSibling.onchange();"),"(<select name='columns[$s][col]' onchange='selectAddRow(this);'><option>".optionlist($d,null,true)."</select>)</div>\n","</div></fieldset>\n";}function
selectSearchPrint($Z,$d,$v){print_fieldset("search",'Search',$Z);foreach($v
as$s=>$u){if($u["type"]=="FULLTEXT"){echo"(<i>".implode("</i>, <i>",array_map('h',$u["columns"]))."</i>) AGAINST"," <input name='fulltext[$s]' value='".h($_GET["fulltext"][$s])."'>",checkbox("boolean[$s]",1,isset($_GET["boolean"][$s]),"BOOL"),"<br>\n";}}$s=0;foreach((array)$_GET["where"]as$X){if("$X[col]$X[val]"!=""&&in_array($X["op"],$this->operators)){echo"<div><select name='where[$s][col]'><option value=''>(".'anywhere'.")".optionlist($d,$X["col"],true)."</select>",html_select("where[$s][op]",$this->operators,$X["op"]),"<input name='where[$s][val]' value='".h($X["val"])."'></div>\n";$s++;}}echo"<div><select name='where[$s][col]' onchange='selectAddRow(this);'><option value=''>(".'anywhere'.")".optionlist($d,null,true)."</select>",html_select("where[$s][op]",$this->operators,"="),"<input name='where[$s][val]'></div>\n","</div></fieldset>\n";}function
selectOrderPrint($td,$d,$v){print_fieldset("sort",'Sort',$td);$s=0;foreach((array)$_GET["order"]as$y=>$X){if(isset($d[$X])){echo"<div><select name='order[$s]'><option>".optionlist($d,$X,true)."</select>",checkbox("desc[$s]",1,isset($_GET["desc"][$y]),'descending')."</div>\n";$s++;}}echo"<div><select name='order[$s]' onchange='selectAddRow(this);'><option>".optionlist($d,null,true)."</select>","<label><input type='checkbox' name='desc[$s]' value='1'>".'descending'."</label></div>\n";echo"</div></fieldset>\n";}function
selectLimitPrint($z){echo"<fieldset><legend>".'Limit'."</legend><div>";echo"<input name='limit' size='3' value='".h($z)."'>","</div></fieldset>\n";}function
selectLengthPrint($bf){if(isset($bf)){echo"<fieldset><legend>".'Text length'."</legend><div>",'<input name="text_length" size="3" value="'.h($bf).'">',"</div></fieldset>\n";}}function
selectActionPrint(){echo"<fieldset><legend>".'Action'."</legend><div>","<input type='submit' value='".'Select'."'>","</div></fieldset>\n";}function
selectCommandPrint(){return!information_schema(DB);}function
selectImportPrint(){return
true;}function
selectEmailPrint($xb,$d){}function
selectColumnsProcess($d,$v){global$p,$ec;$N=array();$r=array();foreach((array)$_GET["columns"]as$y=>$X){if($X["fun"]=="count"||(isset($d[$X["col"]])&&(!$X["fun"]||in_array($X["fun"],$p)||in_array($X["fun"],$ec)))){$N[$y]=apply_sql_function($X["fun"],(isset($d[$X["col"]])?idf_escape($X["col"]):"*"));if(!in_array($X["fun"],$ec)){$r[]=$N[$y];}}}return
array($N,$r);}function
selectSearchProcess($k,$v){global$x;$J=array();foreach($v
as$s=>$u){if($u["type"]=="FULLTEXT"&&$_GET["fulltext"][$s]!=""){$J[]="MATCH (".implode(", ",array_map('idf_escape',$u["columns"])).") AGAINST (".q($_GET["fulltext"][$s]).(isset($_GET["boolean"][$s])?" IN BOOLEAN MODE":"").")";}}foreach((array)$_GET["where"]as$X){if("$X[col]$X[val]"!=""&&in_array($X["op"],$this->operators)){$Sa=" $X[op]";if(ereg('IN$',$X["op"])){$nc=process_length($X["val"]);$Sa.=" (".($nc!=""?$nc:"NULL").")";}elseif(!$X["op"]){$Sa.=$X["val"];}elseif($X["op"]=="LIKE %%"){$Sa=" LIKE ".$this->processInput($k[$X["col"]],"%$X[val]%");}elseif(!ereg('NULL$',$X["op"])){$Sa.=" ".$this->processInput($k[$X["col"]],$X["val"]);}if($X["col"]!=""){$J[]=idf_escape($X["col"]).$Sa;}else{$La=array();foreach($k
as$D=>$j){if(is_numeric($X["val"])||!ereg('int|float|double|decimal',$j["type"])){$D=idf_escape($D);$La[]=($x=="sql"&&ereg('char|text|enum|set',$j["type"])&&!ereg('^utf8',$j["collation"])?"CONVERT($D USING utf8)":$D);}}$J[]=($La?"(".implode("$Sa OR ",$La)."$Sa)":"0");}}}return$J;}function
selectOrderProcess($k,$v){$J=array();foreach((array)$_GET["order"]as$y=>$X){if(isset($k[$X])||preg_match('~^((COUNT\\(DISTINCT |[A-Z0-9_]+\\()(`(?:[^`]|``)+`|"(?:[^"]|"")+")\\)|COUNT\\(\\*\\))$~',$X)){$J[]=(isset($k[$X])?idf_escape($X):$X).(isset($_GET["desc"][$y])?" DESC":"");}}return$J;}function
selectLimitProcess(){return(isset($_GET["limit"])?$_GET["limit"]:"30");}function
selectLengthProcess(){return(isset($_GET["text_length"])?$_GET["text_length"]:"100");}function
selectEmailProcess($Z,$Xb){return
false;}function
messageQuery($H){global$x;static$Wa=0;restart_session();$t="sql-".($Wa++);$gc=&get_session("queries");if(strlen($H)>1e6){$H=ereg_replace('[\x80-\xFF]+$','',substr($H,0,1e6))."\n...";}$gc[$_GET["db"]][]=$H;return" <a href='#$t' onclick=\"return !toggle('$t');\">".'SQL command'."</a><div id='$t' class='hidden'><pre><code class='jush-$x'>".shorten_utf8($H,1000).'</code></pre><p><a href="'.h(str_replace("db=".urlencode(DB),"db=".urlencode($_GET["db"]),ME).'sql=&history='.(count($gc[$_GET["db"]])-1)).'">'.'Edit'.'</a></div>';}function
editFunctions($j){global$ub;$J=($j["null"]?"NULL/":"");foreach($ub
as$y=>$p){if(!$y||(!isset($_GET["call"])&&(isset($_GET["select"])||where($_GET)))){foreach($p
as$F=>$X){if(!$F||ereg($F,$j["type"])){$J.="/$X";}}if($y&&!ereg('set|blob|bytea|raw|file',$j["type"])){$J.="/=";}}}return
explode("/",$J);}function
editInput($S,$j,$ta,$Y){if($j["type"]=="enum"){return(isset($_GET["select"])?"<label><input type='radio'$ta value='-1' checked><i>".'original'."</i></label> ":"").($j["null"]?"<label><input type='radio'$ta value=''".(isset($Y)||isset($_GET["select"])?"":" checked")."><i>NULL</i></label> ":"").enum_input("radio",$ta,$j,$Y,0);}return"";}function
processInput($j,$Y,$o=""){if($o=="="){return$Y;}$D=$j["field"];$J=($j["type"]=="bit"&&ereg("^([0-9]+|b'[0-1]+')\$",$Y)?$Y:q($Y));if(ereg('^(now|getdate|uuid)$',$o)){$J="$o()";}elseif(ereg('^current_(date|timestamp)$',$o)){$J=$o;}elseif(ereg('^([+-]|\\|\\|)$',$o)){$J=idf_escape($D)." $o $J";}elseif(ereg('^[+-] interval$',$o)){$J=idf_escape($D)." $o ".(preg_match("~^(\\d+|'[0-9.: -]') [A-Z_]+$~i",$Y)?$Y:$J);}elseif(ereg('^(addtime|subtime|concat)$',$o)){$J="$o(".idf_escape($D).", $J)";}elseif(ereg('^(md5|sha1|password|encrypt|hex)$',$o)){$J="$o($J)";}if(ereg("binary",$j["type"])){$J="unhex($J)";}return$J;}function
dumpOutput(){$J=array('text'=>'open','file'=>'save');if(function_exists('gzencode')){$J['gz']='gzip';}if(function_exists('bzcompress')){$J['bz2']='bzip2';}return$J;}function
dumpFormat(){return
array('sql'=>'SQL','csv'=>'CSV,','csv;'=>'CSV;','tsv'=>'TSV');}function
dumpTable($S,$R,$vc=false){if($_POST["format"]!="sql"){echo"\xef\xbb\xbf";if($R){dump_csv(array_keys(fields($S)));}}elseif($R){$Xa=create_sql($S,$_POST["auto_increment"]);if($Xa){if($R=="DROP+CREATE"){echo"DROP ".($vc?"VIEW":"TABLE")." IF EXISTS ".table($S).";\n";}if($vc){$Xa=preg_replace('~^([A-Z =]+) DEFINER=`'.preg_replace('~@(.*)~','`@`(%|\\1)',logged_user()).'`~','\\1',$Xa);}echo($R!="CREATE+ALTER"?$Xa:($vc?substr_replace($Xa," OR REPLACE",6,0):substr_replace($Xa," IF NOT EXISTS",12,0))).";\n\n";}if($R=="CREATE+ALTER"&&!$vc){$H="SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, COLLATION_NAME, COLUMN_TYPE, EXTRA, COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ".q($S)." ORDER BY ORDINAL_POSITION";echo"DELIMITER ;;
CREATE PROCEDURE adminer_alter (INOUT alter_command text) BEGIN
	DECLARE _column_name, _collation_name, after varchar(64) DEFAULT '';
	DECLARE _column_type, _column_default text;
	DECLARE _is_nullable char(3);
	DECLARE _extra varchar(30);
	DECLARE _column_comment varchar(255);
	DECLARE done, set_after bool DEFAULT 0;
	DECLARE add_columns text DEFAULT '";$k=array();$oa="";foreach(get_rows($H)as$K){$hb=$K["COLUMN_DEFAULT"];$K["default"]=(isset($hb)?q($hb):"NULL");$K["after"]=q($oa);$K["alter"]=escape_string(idf_escape($K["COLUMN_NAME"])." $K[COLUMN_TYPE]".($K["COLLATION_NAME"]?" COLLATE $K[COLLATION_NAME]":"").(isset($hb)?" DEFAULT ".($hb=="CURRENT_TIMESTAMP"?$hb:$K["default"]):"").($K["IS_NULLABLE"]=="YES"?"":" NOT NULL").($K["EXTRA"]?" $K[EXTRA]":"").($K["COLUMN_COMMENT"]?" COMMENT ".q($K["COLUMN_COMMENT"]):"").($oa?" AFTER ".idf_escape($oa):" FIRST"));echo", ADD $K[alter]";$k[]=$K;$oa=$K["COLUMN_NAME"];}echo"';
	DECLARE columns CURSOR FOR $H;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	SET @alter_table = '';
	OPEN columns;
	REPEAT
		FETCH columns INTO _column_name, _column_default, _is_nullable, _collation_name, _column_type, _extra, _column_comment;
		IF NOT done THEN
			SET set_after = 1;
			CASE _column_name";foreach($k
as$K){echo"
				WHEN ".q($K["COLUMN_NAME"])." THEN
					SET add_columns = REPLACE(add_columns, ', ADD $K[alter]', IF(
						_column_default <=> $K[default] AND _is_nullable = '$K[IS_NULLABLE]' AND _collation_name <=> ".(isset($K["COLLATION_NAME"])?"'$K[COLLATION_NAME]'":"NULL")." AND _column_type = ".q($K["COLUMN_TYPE"])." AND _extra = '$K[EXTRA]' AND _column_comment = ".q($K["COLUMN_COMMENT"])." AND after = $K[after]
					, '', ', MODIFY $K[alter]'));";}echo"
				ELSE
					SET @alter_table = CONCAT(@alter_table, ', DROP ', _column_name);
					SET set_after = 0;
			END CASE;
			IF set_after THEN
				SET after = _column_name;
			END IF;
		END IF;
	UNTIL done END REPEAT;
	CLOSE columns;
	IF @alter_table != '' OR add_columns != '' THEN
		SET alter_command = CONCAT(alter_command, 'ALTER TABLE ".table($S)."', SUBSTR(CONCAT(add_columns, @alter_table), 2), ';\\n');
	END IF;
END;;
DELIMITER ;
CALL adminer_alter(@adminer_alter);
DROP PROCEDURE adminer_alter;

";}}}function
dumpData($S,$R,$H){global$e,$x;$Nc=($x=="sqlite"?0:1048576);if($R){if($_POST["format"]=="sql"&&$R=="TRUNCATE+INSERT"){echo
truncate_sql($S).";\n";}if($_POST["format"]=="sql"){$k=fields($S);}$I=$e->query($H,1);if($I){$sc="";$Ca="";while($K=$I->fetch_assoc()){if($_POST["format"]!="sql"){if($R=="table"){dump_csv(array_keys($K));$R="INSERT";}dump_csv($K);}else{if(!$sc){$sc="INSERT INTO ".table($S)." (".implode(", ",array_map('idf_escape',array_keys($K))).") VALUES";}foreach($K
as$y=>$X){$K[$y]=(isset($X)?(ereg('int|float|double|decimal',$k[$y]["type"])?$X:q($X)):"NULL");}$M=implode(",\t",$K);if($R=="INSERT+UPDATE"){$P=array();foreach($K
as$y=>$X){$P[]=idf_escape($y)." = $X";}echo"$sc ($M) ON DUPLICATE KEY UPDATE ".implode(", ",$P).";\n";}else{$M=($Nc?"\n":" ")."($M)";if(!$Ca){$Ca=$sc.$M;}elseif(strlen($Ca)+4+strlen($M)<$Nc){$Ca.=",$M";}else{echo"$Ca;\n";$Ca=$sc.$M;}}}}if($_POST["format"]=="sql"&&$R!="INSERT+UPDATE"&&$Ca){$Ca.=";\n";echo$Ca;}}elseif($_POST["format"]=="sql"){echo"-- ".str_replace("\n"," ",$e->error)."\n";}}}function
dumpHeaders($kc,$Zc=false){$Ad=$_POST["output"];$Nb=($_POST["format"]=="sql"?"sql":($Zc?"tar":"csv"));header("Content-Type: ".($Ad=="bz2"?"application/x-bzip":($Ad=="gz"?"application/x-gzip":($Nb=="tar"?"application/x-tar":($Nb=="sql"||$Ad!="file"?"text/plain":"text/csv")."; charset=utf-8"))));if($Ad=="bz2"){ob_start('bzcompress',1e6);}if($Ad=="gz"){ob_start('gzencode',1e6);}return$Nb;}function
homepage(){echo'<p>'.($_GET["ns"]==""?'<a href="'.h(ME).'database=">'.'Alter database'."</a>\n":""),(support("scheme")?"<a href='".h(ME)."scheme='>".($_GET["ns"]!=""?'Alter schema':'Create schema')."</a>\n":""),($_GET["ns"]!==""?'<a href="'.h(ME).'schema=">'.'Database schema'."</a>\n":""),(support("privileges")?"<a href='".h(ME)."privileges='>".'Privileges'."</a>\n":"");return
true;}function
navigation($Yc){global$ga,$e,$U,$x,$ob;echo'<h1>
',$this->name(),' <span class="version">',$ga,'</span>
<a href="http://www.adminer.org/#download" id="version">',(version_compare($ga,$_COOKIE["adminer_version"])<0?h($_COOKIE["adminer_version"]):""),'</a>
</h1>
';if($Yc=="auth"){$Ub=true;foreach((array)$_SESSION["pwds"]as$nb=>$_e){foreach($_e
as$O=>$Df){foreach($Df
as$Cf=>$Kd){if(isset($Kd)){if($Ub){echo"<p onclick='eventStop(event);'>\n";$Ub=false;}echo"<a href='".h(auth_url($nb,$O,$Cf))."'>($ob[$nb]) ".h($Cf.($O!=""?"@$O":""))."</a><br>\n";}}}}}else{$g=get_databases();echo'<form action="" method="post">
<p class="logout">
';if(DB==""||!$Yc){echo"<a href='".h(ME)."sql='".bold(isset($_GET["sql"])).">".'SQL command'."</a>\n";if(support("dump")){echo"<a href='".h(ME)."dump=".urlencode(isset($_GET["table"])?$_GET["table"]:$_GET["select"])."' id='dump'".bold(isset($_GET["dump"])).">".'Dump'."</a>\n";}}echo'<input type="submit" name="logout" value="Logout" onclick="eventStop(event);">
<input type="hidden" name="token" value="',$U,'">
</p>
</form>
<form action="">
<p>
';hidden_fields_get();echo($g?html_select("db",array(""=>"(".'database'.")")+$g,DB,"this.form.submit();"):'<input name="db" value="'.h(DB).'">'),'<input type="submit" value="Use"',($g?" class='hidden'":""),' onclick="eventStop(event);">
';if($Yc!="db"&&DB!=""&&$e->select_db(DB)){if($_GET["ns"]!==""&&!$Yc){echo'<p><a href="'.h(ME).'create="'.bold($_GET["create"]==="").">".'Create new table'."</a>\n";$Ve=tables_list();if(!$Ve){echo"<p class='message'>".'No tables.'."\n";}else{$this->tablesPrint($Ve);$Ic=array();foreach($Ve
as$S=>$V){$Ic[]=preg_quote($S,'/');}echo"<script type='text/javascript'>\n","var jushLinks = { $x: [ '".js_escape(ME)."table=\$&', /\\b(".implode("|",$Ic).")\\b/g ] };\n";foreach(array("bac","bra","sqlite_quo","mssql_bra")as$X){echo"jushLinks.$X = jushLinks.$x;\n";}echo"</script>\n";}}}echo(isset($_GET["sql"])?'<input type="hidden" name="sql" value="">':(isset($_GET["schema"])?'<input type="hidden" name="schema" value="">':(isset($_GET["dump"])?'<input type="hidden" name="dump" value="">':""))),"</p></form>\n";}}function
tablesPrint($Ve){echo"<p id='tables'>\n";foreach($Ve
as$S=>$V){echo'<a href="'.h(ME).'select='.urlencode($S).'"'.bold($_GET["select"]==$S).">".'select'."</a> ",'<a href="'.h(ME).'table='.urlencode($S).'"'.bold($_GET["table"]==$S)." title='".'Show structure'."'>".$this->tableName(array("Name"=>$S))."</a><br>\n";}}}$b=(function_exists('adminer_object')?adminer_object():new
Adminer);if(!isset($b->operators)){$b->operators=$qd;}function
page_header($ef,$i="",$Ba=array(),$ff=""){global$ca,$b,$e,$ob;header("Content-Type: text/html; charset=utf-8");if($b->headers()){header("X-Frame-Options: deny");header("X-XSS-Protection: 0");}$gf=$ef.($ff!=""?": ".h($ff):"");$hf=strip_tags($gf.(SERVER!=""&&SERVER!="localhost"?h(" - ".SERVER):"")." - ".$b->name());if(is_ajax()){header("X-AJAX-Title: ".rawurlencode($hf));}else{echo'<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html lang="en" dir="ltr">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="robots" content="noindex">
<title>',$hf,'</title>
<link rel="stylesheet" type="text/css" href="',h(preg_replace("~\\?.*~","",ME))."?file=default.css&amp;version=3.3.3",'">
<script type="text/javascript">
var areYouSure = \'Resend POST data?\';
</script>
<script type="text/javascript" src="',h(preg_replace("~\\?.*~","",ME))."?file=functions.js&amp;version=3.3.3",'"></script>
';if($b->head()){echo'<link rel="shortcut icon" type="image/x-icon" href="',h(preg_replace("~\\?.*~","",ME))."?file=favicon.ico&amp;version=3.3.3",'" id="favicon">
';if(file_exists("adminer.css")){echo'<link rel="stylesheet" type="text/css" href="adminer.css">
';}}echo'
<body class="ltr nojs"',($_POST?"":" onclick=\"return bodyClick(event, '".h(js_escape(DB)."', '".js_escape($_GET["ns"]))."');\"");echo' onkeydown="bodyKeydown(event);" onload="bodyLoad(\'',(is_object($e)?substr($e->server_info,0,3):""),'\');',(isset($_COOKIE["adminer_version"])?"":" verifyVersion();");?>">
<script type="text/javascript">
document.body.className = document.body.className.replace(/(^|\s)nojs(\s|$)/, '$1js$2');
</script>

<div id="content">
<?php
}if(isset($Ba)){$_=substr(preg_replace('~(username|db|ns)=[^&]*&~','',ME),0,-1);echo'<p id="breadcrumb"><a href="'.h($_?$_:".").'">'.$ob[DRIVER].'</a> &raquo; ';$_=substr(preg_replace('~(db|ns)=[^&]*&~','',ME),0,-1);$O=(SERVER!=""?h(SERVER):'Server');if($Ba===false){echo"$O\n";}else{echo"<a href='".($_?h($_):".")."' accesskey='1' title='Alt+Shift+1'>$O</a> &raquo; ";if($_GET["ns"]!=""||(DB!=""&&is_array($Ba))){echo'<a href="'.h($_."&db=".urlencode(DB).(support("scheme")?"&ns=":"")).'">'.h(DB).'</a> &raquo; ';}if(is_array($Ba)){if($_GET["ns"]!=""){echo'<a href="'.h(substr(ME,0,-1)).'">'.h($_GET["ns"]).'</a> &raquo; ';}foreach($Ba
as$y=>$X){$jb=(is_array($X)?$X[1]:$X);if($jb!=""){echo'<a href="'.h(ME."$y=").urlencode(is_array($X)?$X[0]:$X).'">'.h($jb).'</a> &raquo; ';}}}echo"$ef\n";}}echo"<span id='loader'></span>\n","<h2>$gf</h2>\n";restart_session();$Af=preg_replace('~^[^?]*~','',$_SERVER["REQUEST_URI"]);$Wc=$_SESSION["messages"][$Af];if($Wc){echo"<div class='message'>".implode("</div>\n<div class='message'>",$Wc)."</div>\n";unset($_SESSION["messages"][$Af]);}$g=&get_session("dbs");if(DB!=""&&$g&&!in_array(DB,$g,true)){$g=null;}if($i){echo"<div class='error'>$i</div>\n";}define("PAGE_HEADER",1);}function
page_footer($Yc=""){global$b;if(!is_ajax()){echo'</div>

<div id="menu">
';$b->navigation($Yc);echo'</div>
';}}function
int32($C){while($C>=2147483648){$C-=4294967296;}while($C<=-2147483649){$C+=4294967296;}return(int)$C;}function
long2str($W,$If){$M='';foreach($W
as$X){$M.=pack('V',$X);}if($If){return
substr($M,0,end($W));}return$M;}function
str2long($M,$If){$W=array_values(unpack('V*',str_pad($M,4*ceil(strlen($M)/4),"\0")));if($If){$W[]=strlen($M);}return$W;}function
xxtea_mx($Mf,$Lf,$Ne,$xc){return
int32((($Mf>>5&0x7FFFFFF)^$Lf<<2)+(($Lf>>3&0x1FFFFFFF)^$Mf<<4))^int32(($Ne^$Lf)+($xc^$Mf));}function
encrypt_string($Ie,$y){if($Ie==""){return"";}$y=array_values(unpack("V*",pack("H*",md5($y))));$W=str2long($Ie,true);$C=count($W)-1;$Mf=$W[$C];$Lf=$W[0];$G=floor(6+52/($C+1));$Ne=0;while($G-->0){$Ne=int32($Ne+0x9E3779B9);$tb=$Ne>>2&3;for($Bd=0;$Bd<$C;$Bd++){$Lf=$W[$Bd+1];$ad=xxtea_mx($Mf,$Lf,$Ne,$y[$Bd&3^$tb]);$Mf=int32($W[$Bd]+$ad);$W[$Bd]=$Mf;}$Lf=$W[0];$ad=xxtea_mx($Mf,$Lf,$Ne,$y[$Bd&3^$tb]);$Mf=int32($W[$C]+$ad);$W[$C]=$Mf;}return
long2str($W,false);}function
decrypt_string($Ie,$y){if($Ie==""){return"";}$y=array_values(unpack("V*",pack("H*",md5($y))));$W=str2long($Ie,false);$C=count($W)-1;$Mf=$W[$C];$Lf=$W[0];$G=floor(6+52/($C+1));$Ne=int32($G*0x9E3779B9);while($Ne){$tb=$Ne>>2&3;for($Bd=$C;$Bd>0;$Bd--){$Mf=$W[$Bd-1];$ad=xxtea_mx($Mf,$Lf,$Ne,$y[$Bd&3^$tb]);$Lf=int32($W[$Bd]-$ad);$W[$Bd]=$Lf;}$Mf=$W[$C];$ad=xxtea_mx($Mf,$Lf,$Ne,$y[$Bd&3^$tb]);$Lf=int32($W[0]-$ad);$W[0]=$Lf;$Ne=int32($Ne-0x9E3779B9);}return
long2str($W,true);}$e='';$U=$_SESSION["token"];if(!$_SESSION["token"]){$_SESSION["token"]=rand(1,1e6);}$Ld=array();if($_COOKIE["adminer_permanent"]){foreach(explode(" ",$_COOKIE["adminer_permanent"])as$X){list($y)=explode(":",$X);$Ld[$y]=$X;}}if(isset($_POST["server"])){session_regenerate_id();$_SESSION["pwds"][$_POST["driver"]][$_POST["server"]][$_POST["username"]]=$_POST["password"];if($_POST["permanent"]){$y=base64_encode($_POST["driver"])."-".base64_encode($_POST["server"])."-".base64_encode($_POST["username"]);$Vd=$b->permanentLogin();$Ld[$y]="$y:".base64_encode($Vd?encrypt_string($_POST["password"],$Vd):"");cookie("adminer_permanent",implode(" ",$Ld));}if(count($_POST)==($_POST["permanent"]?5:4)||DRIVER!=$_POST["driver"]||SERVER!=$_POST["server"]||$_GET["username"]!==$_POST["username"]){redirect(auth_url($_POST["driver"],$_POST["server"],$_POST["username"]));}}elseif($_POST["logout"]){if($U&&$_POST["token"]!=$U){page_header('Logout','Invalid CSRF token. Send the form again.');page_footer("db");exit;}else{foreach(array("pwds","dbs","queries")as$y){set_session($y,null);}$y=base64_encode(DRIVER)."-".base64_encode(SERVER)."-".base64_encode($_GET["username"]);if($Ld[$y]){unset($Ld[$y]);cookie("adminer_permanent",implode(" ",$Ld));}redirect(substr(preg_replace('~(username|db|ns)=[^&]*&~','',ME),0,-1),'Logout successful.');}}elseif($Ld&&!$_SESSION["pwds"]){session_regenerate_id();$Vd=$b->permanentLogin();foreach($Ld
as$y=>$X){list(,$Ha)=explode(":",$X);list($nb,$O,$Cf)=array_map('base64_decode',explode("-",$y));$_SESSION["pwds"][$nb][$O][$Cf]=decrypt_string(base64_decode($Ha),$Vd);}}function
auth_error($Hb=null){global$e,$b,$U;$Ae=session_name();$i="";if(!$_COOKIE[$Ae]&&$_GET[$Ae]&&ini_bool("session.use_only_cookies")){$i='Session support must be enabled.';}elseif(isset($_GET["username"])){if(($_COOKIE[$Ae]||$_GET[$Ae])&&!$U){$i='Session expired, please login again.';}else{$Kd=&get_session("pwds");if(isset($Kd)){$i=h($Hb?$Hb->getMessage():(is_string($e)?$e:'Invalid credentials.'));$Kd=null;}}}page_header('Login',$i,null);echo"<form action='' method='post' onclick='eventStop(event);'>\n";$b->loginForm();echo"<div>";hidden_fields($_POST,array("driver","server","username","password","permanent"));echo"</div>\n","</form>\n";page_footer("auth");}if(isset($_GET["username"])){if(!class_exists("Min_DB")){unset($_SESSION["pwds"][DRIVER]);page_header('No extension',sprintf('None of the supported PHP extensions (%s) are available.',implode(", ",$Qd)),false);page_footer("auth");exit;}$e=connect();}if(is_string($e)||!$b->login($_GET["username"],get_session("pwds"))){auth_error();exit;}$U=$_SESSION["token"];if(isset($_POST["server"])&&$_POST["token"]){$_POST["token"]=$U;}$i=($_POST?($_POST["token"]==$U?"":'Invalid CSRF token. Send the form again.'):($_SERVER["REQUEST_METHOD"]!="POST"?"":sprintf('Too big POST data. Reduce the data or increase the %s configuration directive.','"post_max_size"')));function
connect_error(){global$e,$U,$i,$ob;$g=array();if(DB!=""){page_header('Database'.": ".h(DB),'Invalid database.',true);}else{if($_POST["db"]&&!$i){queries_redirect(substr(ME,0,-1),'Databases have been dropped.',drop_databases($_POST["db"]));}page_header('Select database',$i,false);echo"<p><a href='".h(ME)."database='>".'Create new database'."</a>\n";foreach(array('privileges'=>'Privileges','processlist'=>'Process list','variables'=>'Variables','status'=>'Status',)as$y=>$X){if(support($y)){echo"<a href='".h(ME)."$y='>$X</a>\n";}}echo"<p>".sprintf('%s version: %s through PHP extension %s',$ob[DRIVER],"<b>$e->server_info</b>","<b>$e->extension</b>")."\n","<p>".sprintf('Logged as: %s',"<b>".h(logged_user())."</b>")."\n";if($_GET["refresh"]){set_session("dbs",null);}$g=get_databases();if($g){$ve=support("scheme");$c=collations();echo"<form action='' method='post'>\n","<table cellspacing='0' class='checkable' onclick='tableClick(event);'>\n","<thead><tr><td>&nbsp;<th>".'Database'."<td>".'Collation'."<td>".'Tables'."</thead>\n";foreach($g
as$h){$oe=h(ME)."db=".urlencode($h);echo"<tr".odd()."><td>".checkbox("db[]",$h,in_array($h,(array)$_POST["db"])),"<th><a href='$oe'>".h($h)."</a>","<td><a href='$oe".($ve?"&amp;ns=":"")."&amp;database=' title='".'Alter database'."'>".nbsp(db_collation($h,$c))."</a>","<td align='right'><a href='$oe&amp;schema=' id='tables-".h($h)."' title='".'Database schema'."'>?</a>","\n";}echo"</table>\n","<script type='text/javascript'>tableCheck();</script>\n","<p><input type='submit' name='drop' value='".'Drop'."'".confirm("formChecked(this, /db/)",1).">\n";echo"<input type='hidden' name='token' value='$U'>\n","<a href='".h(ME)."refresh=1' onclick='eventStop(event);'>".'Refresh'."</a>\n","</form>\n";}}page_footer("db");if($g){echo"<script type='text/javascript'>ajaxSetHtml('".js_escape(ME)."script=connect');</script>\n";}}if(isset($_GET["status"])){$_GET["variables"]=$_GET["status"];}if(!(DB!=""?$e->select_db(DB):isset($_GET["sql"])||isset($_GET["dump"])||isset($_GET["database"])||isset($_GET["processlist"])||isset($_GET["privileges"])||isset($_GET["user"])||isset($_GET["variables"])||$_GET["script"]=="connect")){if(DB!=""){set_session("dbs",null);}connect_error();exit;}function
select($I,$f=null,$jc=""){$Ic=array();$v=array();$d=array();$_a=array();$sf=array();odd('');for($s=0;$K=$I->fetch_row();$s++){if(!$s){echo"<table cellspacing='0' class='nowrap'>\n","<thead><tr>";for($w=0;$w<count($K);$w++){$j=$I->fetch_field();$D=$j->name;$vd=$j->orgtable;$ud=$j->orgname;if($jc){$Ic[$w]=($D=="table"?"table=":($D=="possible_keys"?"indexes=":null));}elseif($vd!=""){if(!isset($v[$vd])){$v[$vd]=array();foreach(indexes($vd,$f)as$u){if($u["type"]=="PRIMARY"){$v[$vd]=array_flip($u["columns"]);break;}}$d[$vd]=$v[$vd];}if(isset($d[$vd][$ud])){unset($d[$vd][$ud]);$v[$vd][$ud]=$w;$Ic[$w]=$vd;}}if($j->charsetnr==63){$_a[$w]=true;}$sf[$w]=$j->type;$D=h($D);echo"<th".($vd!=""||$j->name!=$ud?" title='".h(($vd!=""?"$vd.":"").$ud)."'":"").">".($jc?"<a href='$jc".strtolower($D)."' target='_blank' rel='noreferrer'>$D</a>":$D);}echo"</thead>\n";}echo"<tr".odd().">";foreach($K
as$y=>$X){if(!isset($X)){$X="<i>NULL</i>";}elseif($_a[$y]&&!is_utf8($X)){$X="<i>".lang(array('%d byte','%d bytes'),strlen($X))."</i>";}elseif(!strlen($X)){$X="&nbsp;";}else{$X=h($X);if($sf[$y]==254){$X="<code>$X</code>";}}if(isset($Ic[$y])&&!$d[$Ic[$y]]){if($jc){$_=$Ic[$y].urlencode($K[array_search("table=",$Ic)]);}else{$_="edit=".urlencode($Ic[$y]);foreach($v[$Ic[$y]]as$Ia=>$w){$_.="&where".urlencode("[".bracket_escape($Ia)."]")."=".urlencode($K[$w]);}}$X="<a href='".h(ME.$_)."'>$X</a>";}echo"<td>$X";}}echo($s?"</table>":"<p class='message'>".'No rows.')."\n";}function
referencable_primary($xe){$J=array();foreach(table_status()as$Re=>$S){if($Re!=$xe&&fk_support($S)){foreach(fields($Re)as$j){if($j["primary"]){if($J[$Re]){unset($J[$Re]);break;}$J[$Re]=$j;}}}}return$J;}function
textarea($D,$Y,$L=10,$La=80){echo"<textarea name='$D' rows='$L' cols='$La' class='sqlarea' spellcheck='false' wrap='off' onkeydown='return textareaKeydown(this, event);'>";if(is_array($Y)){foreach($Y
as$X){echo
h($X)."\n\n\n";}}else{echo
h($Y);}echo"</textarea>";}function
format_time($Ee,$_b){return" <span class='time'>(".sprintf('%.3f s',max(0,array_sum(explode(" ",$_b))-array_sum(explode(" ",$Ee)))).")</span>";}function
edit_type($y,$j,$c,$m=array()){global$Je,$sf,$zf,$md;echo'<td><select name="',$y,'[type]" class="type" onfocus="lastType = selectValue(this);" onchange="editingTypeChange(this);">',optionlist((!$j["type"]||isset($sf[$j["type"]])?array():array($j["type"]))+$Je+($m?array('Foreign keys'=>$m):array()),$j["type"]),'</select>
<td><input name="',$y,'[length]" value="',h($j["length"]),'" size="3" onfocus="editingLengthFocus(this);"><td class="options">',"<select name='$y"."[collation]'".(ereg('(char|text|enum|set)$',$j["type"])?"":" class='hidden'").'><option value="">('.'collation'.')'.optionlist($c,$j["collation"]).'</select>',($zf?"<select name='$y"."[unsigned]'".(!$j["type"]||ereg('(int|float|double|decimal)$',$j["type"])?"":" class='hidden'").'><option>'.optionlist($zf,$j["unsigned"]).'</select>':''),($m?"<select name='$y"."[on_delete]'".(ereg("`",$j["type"])?"":" class='hidden'")."><option value=''>(".'ON DELETE'.")".optionlist(explode("|",$md),$j["on_delete"])."</select> ":" ");}function
process_length($Gc){global$Cb;return(preg_match("~^\\s*(?:$Cb)(?:\\s*,\\s*(?:$Cb))*\\s*\$~",$Gc)&&preg_match_all("~$Cb~",$Gc,$Lc)?implode(",",$Lc[0]):preg_replace('~[^0-9,+-]~','',$Gc));}function
process_type($j,$Ja="COLLATE"){global$zf;return" $j[type]".($j["length"]!=""?"(".process_length($j["length"]).")":"").(ereg('int|float|double|decimal',$j["type"])&&in_array($j["unsigned"],$zf)?" $j[unsigned]":"").(ereg('char|text|enum|set',$j["type"])&&$j["collation"]?" $Ja ".q($j["collation"]):"");}function
process_field($j,$qf){return
array(idf_escape($j["field"]),process_type($qf),($j["null"]?" NULL":" NOT NULL"),(isset($j["default"])?" DEFAULT ".(($j["type"]=="timestamp"&&eregi('^CURRENT_TIMESTAMP$',$j["default"]))||($j["type"]=="bit"&&ereg("^([0-9]+|b'[0-1]+')\$",$j["default"]))?$j["default"]:q($j["default"])):""),($j["on_update"]?" ON UPDATE $j[on_update]":""),(support("comment")&&$j["comment"]!=""?" COMMENT ".q($j["comment"]):""),($j["auto_increment"]?auto_increment():null),);}function
type_class($V){foreach(array('char'=>'text','date'=>'time|year','binary'=>'blob','enum'=>'set',)as$y=>$X){if(ereg("$y|$X",$V)){return" class='$y'";}}}function
edit_fields($k,$c,$V="TABLE",$qa=0,$m=array(),$Qa=false){global$qc;echo'<thead><tr class="wrap">
';if($V=="PROCEDURE"){echo'<td>&nbsp;';}echo'<th>',($V=="TABLE"?'Column name':'Parameter name'),'<td>Type<textarea id="enum-edit" rows="4" cols="12" wrap="off" style="display: none;" onblur="editingLengthBlur(this);"></textarea>
<td>Length
<td>Options
';if($V=="TABLE"){echo'<td>NULL
<td><input type="radio" name="auto_increment_col" value=""><acronym title="Auto Increment">AI</acronym>
<td',($_POST["defaults"]?"":" class='hidden'"),'>Default values
',(support("comment")?"<td".($Qa?"":" class='hidden'").">".'Comment':"");}echo'<td>',"<input type='image' name='add[".(support("move_col")?0:count($k))."]' src='".h(preg_replace("~\\?.*~","",ME))."?file=plus.gif&amp;version=3.3.3' alt='+' title='".'Add next'."'>",'<script type="text/javascript">row_count = ',count($k),';</script>
</thead>
<tbody onkeydown="return editingKeydown(event);">
';foreach($k
as$s=>$j){$s++;$wd=$j[($_POST?"orig":"field")];$lb=(isset($_POST["add"][$s-1])||(isset($j["field"])&&!$_POST["drop_col"][$s]))&&(support("drop_col")||$wd=="");echo'<tr',($lb?"":" style='display: none;'"),'>
',($V=="PROCEDURE"?"<td>".html_select("fields[$s][inout]",explode("|",$qc),$j["inout"]):""),'<th>';if($lb){echo'<input name="fields[',$s,'][field]" value="',h($j["field"]),'" onchange="',($j["field"]!=""||count($k)>1?"":"editingAddRow(this, $qa); "),'editingNameChange(this);" maxlength="64">';}echo'<input type="hidden" name="fields[',$s,'][orig]" value="',h($wd),'">
';edit_type("fields[$s]",$j,$c,$m);if($V=="TABLE"){echo'<td>',checkbox("fields[$s][null]",1,$j["null"]),'<td><input type="radio" name="auto_increment_col" value="',$s,'"';if($j["auto_increment"]){echo' checked';}?> onclick="var field = this.form['fields[' + this.value + '][field]']; if (!field.value) { field.value = 'id'; field.onchange(); }">
<td<?php echo($_POST["defaults"]?"":" class='hidden'"),'>',checkbox("fields[$s][has_default]",1,$j["has_default"]),'<input name="fields[',$s,'][default]" value="',h($j["default"]),'" onchange="this.previousSibling.checked = true;">
',(support("comment")?"<td".($Qa?"":" class='hidden'")."><input name='fields[$s][comment]' value='".h($j["comment"])."' maxlength='255'>":"");}echo"<td>",(support("move_col")?"<input type='image' name='add[$s]' src='".h(preg_replace("~\\?.*~","",ME))."?file=plus.gif&amp;version=3.3.3' alt='+' title='".'Add next'."' onclick='return !editingAddRow(this, $qa, 1);'>&nbsp;"."<input type='image' name='up[$s]' src='".h(preg_replace("~\\?.*~","",ME))."?file=up.gif&amp;version=3.3.3' alt='^' title='".'Move up'."'>&nbsp;"."<input type='image' name='down[$s]' src='".h(preg_replace("~\\?.*~","",ME))."?file=down.gif&amp;version=3.3.3' alt='v' title='".'Move down'."'>&nbsp;":""),($wd==""||support("drop_col")?"<input type='image' name='drop_col[$s]' src='".h(preg_replace("~\\?.*~","",ME))."?file=cross.gif&amp;version=3.3.3' alt='x' title='".'Remove'."' onclick='return !editingRemoveRow(this);'>":""),"\n";}}function
process_fields(&$k){ksort($k);$hd=0;if($_POST["up"]){$Ac=0;foreach($k
as$y=>$j){if(key($_POST["up"])==$y){unset($k[$y]);array_splice($k,$Ac,0,array($j));break;}if(isset($j["field"])){$Ac=$hd;}$hd++;}}if($_POST["down"]){$n=false;foreach($k
as$y=>$j){if(isset($j["field"])&&$n){unset($k[key($_POST["down"])]);array_splice($k,$hd,0,array($n));break;}if(key($_POST["down"])==$y){$n=$j;}$hd++;}}$k=array_values($k);if($_POST["add"]){array_splice($k,key($_POST["add"]),0,array(array()));}}function
normalize_enum($B){return"'".str_replace("'","''",addcslashes(stripcslashes(str_replace($B[0][0].$B[0][0],$B[0][0],substr($B[0],1,-1))),'\\'))."'";}function
grant($q,$Xd,$d,$ld){if(!$Xd){return
true;}if($Xd==array("ALL PRIVILEGES","GRANT OPTION")){return($q=="GRANT"?queries("$q ALL PRIVILEGES$ld WITH GRANT OPTION"):queries("$q ALL PRIVILEGES$ld")&&queries("$q GRANT OPTION$ld"));}return
queries("$q ".preg_replace('~(GRANT OPTION)\\([^)]*\\)~','\\1',implode("$d, ",$Xd).$d).$ld);}function
drop_create($pb,$Xa,$A,$Vc,$Tc,$Uc,$D){if($_POST["drop"]){return
query_redirect($pb,$A,$Vc,true,!$_POST["dropped"]);}$qb=$D!=""&&($_POST["dropped"]||queries($pb));$Za=queries($Xa);if(!queries_redirect($A,($D!=""?$Tc:$Uc),$Za)&&$qb){redirect(null,$Vc);}return$qb;}function
tar_file($Sb,$Ta){$J=pack("a100a8a8a8a12a12",$Sb,644,0,0,decoct(strlen($Ta)),decoct(time()));$Ga=8*32;for($s=0;$s<strlen($J);$s++){$Ga+=ord($J{$s});}$J.=sprintf("%06o",$Ga)."\0 ";return$J.str_repeat("\0",512-strlen($J)).$Ta.str_repeat("\0",511-(strlen($Ta)+511)%
512);}session_cache_limiter("");if(!ini_bool("session.use_cookies")||@ini_set("session.use_cookies",false)!==false){session_write_close();}$md="RESTRICT|CASCADE|SET NULL|NO ACTION";$Cb="'(?:''|[^'\\\\]|\\\\.)*+'";$qc="IN|OUT|INOUT";if(isset($_GET["select"])&&($_POST["edit"]||$_POST["clone"])&&!$_POST["save"]){$_GET["edit"]=$_GET["select"];}if(isset($_GET["callf"])){$_GET["call"]=$_GET["callf"];}if(isset($_GET["function"])){$_GET["procedure"]=$_GET["function"];}if(isset($_GET["download"])){$a=$_GET["download"];header("Content-Type: application/octet-stream");header("Content-Disposition: attachment; filename=".friendly_url("$a-".implode("_",$_GET["where"])).".".friendly_url($_GET["field"]));echo$e->result("SELECT".limit(idf_escape($_GET["field"])." FROM ".table($a)," WHERE ".where($_GET),1));exit;}elseif(isset($_GET["table"])){$a=$_GET["table"];$k=fields($a);if(!$k){$i=error();}$T=($k?table_status($a):array());page_header(($k&&is_view($T)?'View':'Table').": ".h($a),$i);$b->selectLinks($T);$Pa=$T["Comment"];if($Pa!=""){echo"<p>".'Comment'.": ".h($Pa)."\n";}if($k){echo"<table cellspacing='0'>\n","<thead><tr><th>".'Column'."<td>".'Type'.(support("comment")?"<td>".'Comment':"")."</thead>\n";foreach($k
as$j){echo"<tr".odd()."><th>".h($j["field"]),"<td title='".h($j["collation"])."'>".h($j["full_type"]).($j["null"]?" <i>NULL</i>":"").($j["auto_increment"]?" <i>".'Auto Increment'."</i>":""),(isset($j["default"])?" [<b>".h($j["default"])."</b>]":""),(support("comment")?"<td>".nbsp($j["comment"]):""),"\n";}echo"</table>\n";if(!is_view($T)){echo"<h3>".'Indexes'."</h3>\n";$v=indexes($a);if($v){echo"<table cellspacing='0'>\n";foreach($v
as$D=>$u){ksort($u["columns"]);$Ud=array();foreach($u["columns"]as$y=>$X){$Ud[]="<i>".h($X)."</i>".($u["lengths"][$y]?"(".$u["lengths"][$y].")":"");}echo"<tr title='".h($D)."'><th>$u[type]<td>".implode(", ",$Ud)."\n";}echo"</table>\n";}echo'<p><a href="'.h(ME).'indexes='.urlencode($a).'">'.'Alter indexes'."</a>\n";if(fk_support($T)){echo"<h3>".'Foreign keys'."</h3>\n";$m=foreign_keys($a);if($m){echo"<table cellspacing='0'>\n","<thead><tr><th>".'Source'."<td>".'Target'."<td>".'ON DELETE'."<td>".'ON UPDATE'.($x!="sqlite"?"<td>&nbsp;":"")."</thead>\n";foreach($m
as$D=>$l){echo"<tr title='".h($D)."'>","<th><i>".implode("</i>, <i>",array_map('h',$l["source"]))."</i>","<td><a href='".h($l["db"]!=""?preg_replace('~db=[^&]*~',"db=".urlencode($l["db"]),ME):($l["ns"]!=""?preg_replace('~ns=[^&]*~',"ns=".urlencode($l["ns"]),ME):ME))."table=".urlencode($l["table"])."'>".($l["db"]!=""?"<b>".h($l["db"])."</b>.":"").($l["ns"]!=""?"<b>".h($l["ns"])."</b>.":"").h($l["table"])."</a>","(<i>".implode("</i>, <i>",array_map('h',$l["target"]))."</i>)","<td>".nbsp($l["on_delete"])."\n","<td>".nbsp($l["on_update"])."\n";if($x!="sqlite"){echo'<td><a href="'.h(ME.'foreign='.urlencode($a).'&name='.urlencode($D)).'">'.'Alter'.'</a>';}}echo"</table>\n";}if($x!="sqlite"){echo'<p><a href="'.h(ME).'foreign='.urlencode($a).'">'.'Add foreign key'."</a>\n";}}if(support("trigger")){echo"<h3>".'Triggers'."</h3>\n";$pf=triggers($a);if($pf){echo"<table cellspacing='0'>\n";foreach($pf
as$y=>$X){echo"<tr valign='top'><td>$X[0]<td>$X[1]<th>".h($y)."<td><a href='".h(ME.'trigger='.urlencode($a).'&name='.urlencode($y))."'>".'Alter'."</a>\n";}echo"</table>\n";}echo'<p><a href="'.h(ME).'trigger='.urlencode($a).'">'.'Add trigger'."</a>\n";}}}}elseif(isset($_GET["schema"])){page_header('Database schema',"",array(),DB.($_GET["ns"]?".$_GET[ns]":""));$Se=array();$Te=array();$D="adminer_schema";$ea=($_GET["schema"]?$_GET["schema"]:$_COOKIE[($_COOKIE["$D-".DB]?"$D-".DB:$D)]);preg_match_all('~([^:]+):([-0-9.]+)x([-0-9.]+)(_|$)~',$ea,$Lc,PREG_SET_ORDER);foreach($Lc
as$s=>$B){$Se[$B[1]]=array($B[2],$B[3]);$Te[]="\n\t'".js_escape($B[1])."': [ $B[2], $B[3] ]";}$if=0;$za=-1;$ue=array();$he=array();$Ec=array();foreach(table_status()as$T){if(!isset($T["Engine"])){continue;}$Nd=0;$ue[$T["Name"]]["fields"]=array();foreach(fields($T["Name"])as$D=>$j){$Nd+=1.25;$j["pos"]=$Nd;$ue[$T["Name"]]["fields"][$D]=$j;}$ue[$T["Name"]]["pos"]=($Se[$T["Name"]]?$Se[$T["Name"]]:array($if,0));foreach($b->foreignKeys($T["Name"])as$X){if(!$X["db"]){$Cc=$za;if($Se[$T["Name"]][1]||$Se[$X["table"]][1]){$Cc=min(floatval($Se[$T["Name"]][1]),floatval($Se[$X["table"]][1]))-1;}else{$za-=.1;}while($Ec[(string)$Cc]){$Cc-=.0001;}$ue[$T["Name"]]["references"][$X["table"]][(string)$Cc]=array($X["source"],$X["target"]);$he[$X["table"]][$T["Name"]][(string)$Cc]=$X["target"];$Ec[(string)$Cc]=true;}}$if=max($if,$ue[$T["Name"]]["pos"][0]+2.5+$Nd);}echo'<div id="schema" style="height: ',$if,'em;">
<script type="text/javascript">
tablePos = {',implode(",",$Te)."\n",'};
em = document.getElementById(\'schema\').offsetHeight / ',$if,';
document.onmousemove = schemaMousemove;
document.onmouseup = function (ev) {
	schemaMouseup(ev, \'',js_escape(DB),'\');
};
</script>
';foreach($ue
as$D=>$S){echo"<div class='table' style='top: ".$S["pos"][0]."em; left: ".$S["pos"][1]."em;' onmousedown='schemaMousedown(this, event);'>",'<a href="'.h(ME).'table='.urlencode($D).'"><b>'.h($D)."</b></a><br>\n";foreach($S["fields"]as$j){$X='<span'.type_class($j["type"]).' title="'.h($j["full_type"].($j["null"]?" NULL":'')).'">'.h($j["field"]).'</span>';echo($j["primary"]?"<i>$X</i>":$X)."<br>\n";}foreach((array)$S["references"]as$Ze=>$ie){foreach($ie
as$Cc=>$ee){$Dc=$Cc-$Se[$D][1];$s=0;foreach($ee[0]as$Be){echo"<div class='references' title='".h($Ze)."' id='refs$Cc-".($s++)."' style='left: $Dc"."em; top: ".$S["fields"][$Be]["pos"]."em; padding-top: .5em;'><div style='border-top: 1px solid Gray; width: ".(-$Dc)."em;'></div></div>\n";}}}foreach((array)$he[$D]as$Ze=>$ie){foreach($ie
as$Cc=>$d){$Dc=$Cc-$Se[$D][1];$s=0;foreach($d
as$Ye){echo"<div class='references' title='".h($Ze)."' id='refd$Cc-".($s++)."' style='left: $Dc"."em; top: ".$S["fields"][$Ye]["pos"]."em; height: 1.25em; background: url(".h(preg_replace("~\\?.*~","",ME))."?file=arrow.gif) no-repeat right center;&amp;version=3.3.3'><div style='height: .5em; border-bottom: 1px solid Gray; width: ".(-$Dc)."em;'></div></div>\n";}}}echo"</div>\n";}foreach($ue
as$D=>$S){foreach((array)$S["references"]as$Ze=>$ie){foreach($ie
as$Cc=>$ee){$Xc=$if;$Pc=-10;foreach($ee[0]as$y=>$Be){$Od=$S["pos"][0]+$S["fields"][$Be]["pos"];$Pd=$ue[$Ze]["pos"][0]+$ue[$Ze]["fields"][$ee[1][$y]]["pos"];$Xc=min($Xc,$Od,$Pd);$Pc=max($Pc,$Od,$Pd);}echo"<div class='references' id='refl$Cc' style='left: $Cc"."em; top: $Xc"."em; padding: .5em 0;'><div style='border-right: 1px solid Gray; margin-top: 1px; height: ".($Pc-$Xc)."em;'></div></div>\n";}}}echo'</div>
<p><a href="',h(ME."schema=".urlencode($ea)),'" id="schema-link">Permanent link</a>
';}elseif(isset($_GET["dump"])){$a=$_GET["dump"];if($_POST){$Va="";foreach(array("output","format","db_style","routines","events","table_style","auto_increment","triggers","data_style")as$y){$Va.="&$y=".urlencode($_POST[$y]);}cookie("adminer_export",substr($Va,1));$Nb=dump_headers(($a!=""?$a:DB),(DB==""||count((array)$_POST["tables"]+(array)$_POST["data"])>1));$uc=($_POST["format"]=="sql");if($uc){echo"-- Adminer $ga ".$ob[DRIVER]." dump

".($x!="sql"?"":"SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = ".q($e->result("SELECT @@time_zone")).";
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

");}$R=$_POST["db_style"];$g=array(DB);if(DB==""){$g=$_POST["databases"];if(is_string($g)){$g=explode("\n",rtrim(str_replace("\r","",$g),"\n"));}}foreach((array)$g
as$h){if($e->select_db($h)){if($uc&&ereg('CREATE',$R)&&($Xa=$e->result("SHOW CREATE DATABASE ".idf_escape($h),1))){if($R=="DROP+CREATE"){echo"DROP DATABASE IF EXISTS ".idf_escape($h).";\n";}echo($R=="CREATE+ALTER"?preg_replace('~^CREATE DATABASE ~','\\0IF NOT EXISTS ',$Xa):$Xa).";\n";}if($uc){if($R){echo
use_sql($h).";\n\n";}if(in_array("CREATE+ALTER",array($R,$_POST["table_style"]))){echo"SET @adminer_alter = '';\n\n";}$_d="";if($_POST["routines"]){foreach(array("FUNCTION","PROCEDURE")as$pe){foreach(get_rows("SHOW $pe STATUS WHERE Db = ".q($h),null,"-- ")as$K){$_d.=($R!='DROP+CREATE'?"DROP $pe IF EXISTS ".idf_escape($K["Name"]).";;\n":"").$e->result("SHOW CREATE $pe ".idf_escape($K["Name"]),2).";;\n\n";}}}if($_POST["events"]){foreach(get_rows("SHOW EVENTS",null,"-- ")as$K){$_d.=($R!='DROP+CREATE'?"DROP EVENT IF EXISTS ".idf_escape($K["Name"]).";;\n":"").$e->result("SHOW CREATE EVENT ".idf_escape($K["Name"]),3).";;\n\n";}}if($_d){echo"DELIMITER ;;\n\n$_d"."DELIMITER ;\n\n";}}if($_POST["table_style"]||$_POST["data_style"]){$Gf=array();foreach(table_status()as$T){$S=(DB==""||in_array($T["Name"],(array)$_POST["tables"]));$cb=(DB==""||in_array($T["Name"],(array)$_POST["data"]));if($S||$cb){if(!is_view($T)){if($Nb=="tar"){ob_start();}$b->dumpTable($T["Name"],($S?$_POST["table_style"]:""));if($cb){$b->dumpData($T["Name"],$_POST["data_style"],"SELECT * FROM ".table($T["Name"]));}if($uc&&$_POST["triggers"]&&$S&&($pf=trigger_sql($T["Name"],$_POST["table_style"]))){echo"\nDELIMITER ;;\n$pf\nDELIMITER ;\n";}if($Nb=="tar"){echo
tar_file((DB!=""?"":"$h/")."$T[Name].csv",ob_get_clean());}elseif($uc){echo"\n";}}elseif($uc){$Gf[]=$T["Name"];}}}foreach($Gf
as$Ff){$b->dumpTable($Ff,$_POST["table_style"],true);}if($Nb=="tar"){echo
pack("x512");}}if($R=="CREATE+ALTER"&&$uc){$H="SELECT TABLE_NAME, ENGINE, TABLE_COLLATION, TABLE_COMMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE()";echo"DELIMITER ;;
CREATE PROCEDURE adminer_alter (INOUT alter_command text) BEGIN
	DECLARE _table_name, _engine, _table_collation varchar(64);
	DECLARE _table_comment varchar(64);
	DECLARE done bool DEFAULT 0;
	DECLARE tables CURSOR FOR $H;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	OPEN tables;
	REPEAT
		FETCH tables INTO _table_name, _engine, _table_collation, _table_comment;
		IF NOT done THEN
			CASE _table_name";foreach(get_rows($H)as$K){$Pa=q($K["ENGINE"]=="InnoDB"?preg_replace('~(?:(.+); )?InnoDB free: .*~','\\1',$K["TABLE_COMMENT"]):$K["TABLE_COMMENT"]);echo"
				WHEN ".q($K["TABLE_NAME"])." THEN
					".(isset($K["ENGINE"])?"IF _engine != '$K[ENGINE]' OR _table_collation != '$K[TABLE_COLLATION]' OR _table_comment != $Pa THEN
						ALTER TABLE ".idf_escape($K["TABLE_NAME"])." ENGINE=$K[ENGINE] COLLATE=$K[TABLE_COLLATION] COMMENT=$Pa;
					END IF":"BEGIN END").";";}echo"
				ELSE
					SET alter_command = CONCAT(alter_command, 'DROP TABLE `', REPLACE(_table_name, '`', '``'), '`;\\n');
			END CASE;
		END IF;
	UNTIL done END REPEAT;
	CLOSE tables;
END;;
DELIMITER ;
CALL adminer_alter(@adminer_alter);
DROP PROCEDURE adminer_alter;
";}if(in_array("CREATE+ALTER",array($R,$_POST["table_style"]))&&$uc){echo"SELECT @adminer_alter;\n";}}}if($uc){echo"-- ".$e->result("SELECT NOW()")."\n";}exit;}page_header('Export',"",($_GET["export"]!=""?array("table"=>$_GET["export"]):array()),DB);echo'
<form action="" method="post">
<table cellspacing="0">
';$fb=array('','USE','DROP+CREATE','CREATE');$Ue=array('','DROP+CREATE','CREATE');$db=array('','TRUNCATE+INSERT','INSERT');if($x=="sql"){$fb[]='CREATE+ALTER';$Ue[]='CREATE+ALTER';$db[]='INSERT+UPDATE';}parse_str($_COOKIE["adminer_export"],$K);if(!$K){$K=array("output"=>"text","format"=>"sql","db_style"=>(DB!=""?"":"CREATE"),"table_style"=>"DROP+CREATE","data_style"=>"INSERT");}if(!isset($K["events"])){$K["routines"]=$K["events"]=($_GET["dump"]=="");$K["triggers"]=$K["table_style"];}echo"<tr><th>".'Output'."<td>".html_select("output",$b->dumpOutput(),$K["output"],0)."\n";echo"<tr><th>".'Format'."<td>".html_select("format",$b->dumpFormat(),$K["format"],0)."\n";echo($x=="sqlite"?"":"<tr><th>".'Database'."<td>".html_select('db_style',$fb,$K["db_style"]).(support("routine")?checkbox("routines",1,$K["routines"],'Routines'):"").(support("event")?checkbox("events",1,$K["events"],'Events'):"")),"<tr><th>".'Tables'."<td>".html_select('table_style',$Ue,$K["table_style"]).checkbox("auto_increment",1,$K["auto_increment"],'Auto Increment').(support("trigger")?checkbox("triggers",1,$K["triggers"],'Triggers'):""),"<tr><th>".'Data'."<td>".html_select('data_style',$db,$K["data_style"]),'</table>
<p><input type="submit" value="Export">

<table cellspacing="0">
';$Sd=array();if(DB!=""){$Fa=($a!=""?"":" checked");echo"<thead><tr>","<th style='text-align: left;'><label><input type='checkbox' id='check-tables'$Fa onclick='formCheck(this, /^tables\\[/);'>".'Tables'."</label>","<th style='text-align: right;'><label>".'Data'."<input type='checkbox' id='check-data'$Fa onclick='formCheck(this, /^data\\[/);'></label>","</thead>\n";$Gf="";foreach(table_status()as$T){$D=$T["Name"];$Rd=ereg_replace("_.*","",$D);$Fa=($a==""||$a==(substr($a,-1)=="%"?"$Rd%":$D));$Ud="<tr><td>".checkbox("tables[]",$D,$Fa,$D,"formUncheck('check-tables');");if(is_view($T)){$Gf.="$Ud\n";}else{echo"$Ud<td align='right'><label>".($T["Engine"]=="InnoDB"&&$T["Rows"]?"~ ":"").$T["Rows"].checkbox("data[]",$D,$Fa,"","formUncheck('check-data');")."</label>\n";}$Sd[$Rd]++;}echo$Gf;}else{echo"<thead><tr><th style='text-align: left;'><label><input type='checkbox' id='check-databases'".($a==""?" checked":"")." onclick='formCheck(this, /^databases\\[/);'>".'Database'."</label></thead>\n";$g=get_databases();if($g){foreach($g
as$h){if(!information_schema($h)){$Rd=ereg_replace("_.*","",$h);echo"<tr><td>".checkbox("databases[]",$h,$a==""||$a=="$Rd%",$h,"formUncheck('check-databases');")."</label>\n";$Sd[$Rd]++;}}}else{echo"<tr><td><textarea name='databases' rows='10' cols='20'></textarea>";}}echo'</table>
</form>
';$Ub=true;foreach($Sd
as$y=>$X){if($y!=""&&$X>1){echo($Ub?"<p>":" ")."<a href='".h(ME)."dump=".urlencode("$y%")."'>".h($y)."</a>";$Ub=false;}}}elseif(isset($_GET["privileges"])){page_header('Privileges');$I=$e->query("SELECT User, Host FROM mysql.".(DB==""?"user":"db WHERE ".q(DB)." LIKE Db")." ORDER BY Host, User");$q=$I;if(!$I){$I=$e->query("SELECT SUBSTRING_INDEX(CURRENT_USER, '@', 1) AS User, SUBSTRING_INDEX(CURRENT_USER, '@', -1) AS Host");}echo"<form action=''><p>\n";hidden_fields_get();echo"<input type='hidden' name='db' value='".h(DB)."'>\n",($q?"":"<input type='hidden' name='grant' value=''>\n"),"<table cellspacing='0'>\n","<thead><tr><th>".'Username'."<th>".'Server'."<th>&nbsp;</thead>\n";while($K=$I->fetch_assoc()){echo'<tr'.odd().'><td>'.h($K["User"])."<td>".h($K["Host"]).'<td><a href="'.h(ME.'user='.urlencode($K["User"]).'&host='.urlencode($K["Host"])).'">'.'Edit'."</a>\n";}if(!$q||DB!=""){echo"<tr".odd()."><td><input name='user'><td><input name='host' value='localhost'><td><input type='submit' value='".'Edit'."'>\n";}echo"</table>\n","</form>\n",'<p><a href="'.h(ME).'user=">'.'Create user'."</a>";}elseif(isset($_GET["sql"])){if(!$i&&$_POST["export"]){dump_headers("sql");$b->dumpTable("","");$b->dumpData("","table",$_POST["query"]);exit;}restart_session();$hc=&get_session("queries");$gc=&$hc[DB];if(!$i&&$_POST["clear"]){$gc=array();redirect(remove_from_uri("history"));}page_header('SQL command',$i);if(!$i&&$_POST){$Zb=false;$H=$_POST["query"];if($_POST["webfile"]){$Zb=@fopen((file_exists("adminer.sql")?"adminer.sql":(file_exists("adminer.sql.gz")?"compress.zlib://adminer.sql.gz":"compress.bzip2://adminer.sql.bz2")),"rb");$H=($Zb?fread($Zb,1e6):false);}elseif($_FILES&&$_FILES["sql_file"]["error"]!=4){$H=get_file("sql_file",true);}if(is_string($H)){if(function_exists('memory_get_usage')){@ini_set("memory_limit",max(ini_get("memory_limit"),2*strlen($H)+memory_get_usage()+8e6));}if($H!=""&&strlen($H)<1e6){$G=$H.(ereg(';$',$H)?"":";");if(!$gc||end($gc)!=$G){$gc[]=$G;}}$Ce="(?:\\s|/\\*.*\\*/|(?:#|-- )[^\n]*\n|--\n)";if(!ini_bool("session.use_cookies")){session_write_close();}$ib=";";$hd=0;$zb=true;$f=connect();if(is_object($f)&&DB!=""){$f->select_db(DB);}$Oa=0;$Eb=array();$Ed='[\'"'.($x=="sql"?'`#':($x=="sqlite"?'`[':($x=="mssql"?'[':''))).']|/\\*|-- |$'.($x=="pgsql"?'|\\$[^$]*\\$':'');$jf=microtime();parse_str($_COOKIE["adminer_export"],$ka);$sb=$b->dumpFormat();unset($sb["sql"]);while($H!=""){if(!$hd&&preg_match("~^$Ce*DELIMITER\\s+(.+)~i",$H,$B)){$ib=$B[1];$H=substr($H,strlen($B[0]));}else{preg_match('('.preg_quote($ib)."|$Ed)",$H,$B,PREG_OFFSET_CAPTURE,$hd);$n=$B[0][0];if(!$n&&$Zb&&!feof($Zb)){$H.=fread($Zb,1e5);}else{$hd=$B[0][1]+strlen($n);if(!$n&&rtrim($H)==""){break;}if($n&&$n!=$ib){while(preg_match('('.($n=='/*'?'\\*/':($n=='['?']':(ereg('^-- |^#',$n)?"\n":preg_quote($n)."|\\\\."))).'|$)s',$H,$B,PREG_OFFSET_CAPTURE,$hd)){$M=$B[0][0];$hd=$B[0][1]+strlen($M);if(!$M&&$Zb&&!feof($Zb)){$hd-=strlen($n);$H.=fread($Zb,1e5);}elseif($M[0]!="\\"){break;}}}else{$zb=false;$G=substr($H,0,$B[0][1]);$Oa++;$Ud="<pre id='sql-$Oa'><code class='jush-$x'>".shorten_utf8(trim($G),1000)."</code></pre>\n";if(!$_POST["only_errors"]){echo$Ud;ob_flush();flush();}$Ee=microtime();if($e->multi_query($G)&&is_object($f)&&preg_match("~^$Ce*USE\\b~isU",$G)){$f->query($G);}do{$I=$e->store_result();$_b=microtime();$cf=format_time($Ee,$_b).(strlen($G)<1000?" <a href='".h(ME)."sql=".urlencode(trim($G))."'>".'Edit'."</a>":"");if($e->error){echo($_POST["only_errors"]?$Ud:""),"<p class='error'>".'Error in query'.": ".error()."\n";$Eb[]=" <a href='#sql-$Oa'>$Oa</a>";if($_POST["error_stops"]){break
2;}}elseif(is_object($I)){select($I,$f);if(!$_POST["only_errors"]){echo"<form action='' method='post'>\n","<p>".($I->num_rows?lang(array('%d row','%d rows'),$I->num_rows):"").$cf;$t="export-$Oa";$Mb=", <a href='#$t' onclick=\"return !toggle('$t');\">".'Export'."</a><span id='$t' class='hidden'>: ".html_select("output",$b->dumpOutput(),$ka["output"])." ".html_select("format",$sb,$ka["format"])."<input type='hidden' name='query' value='".h($G)."'>"." <input type='submit' name='export' value='".'Export'."' onclick='eventStop(event);'><input type='hidden' name='token' value='$U'></span>\n";if($f&&preg_match("~^($Ce|\\()*SELECT\\b~isU",$G)&&($Lb=explain($f,$G))){$t="explain-$Oa";echo", <a href='#$t' onclick=\"return !toggle('$t');\">EXPLAIN</a>$Mb","<div id='$t' class='hidden'>\n";select($Lb,$f,($x=="sql"?"http://dev.mysql.com/doc/refman/".substr($e->server_info,0,3)."/en/explain-output.html#explain_":""));echo"</div>\n";}else{echo$Mb;}echo"</form>\n";}}else{if(preg_match("~^$Ce*(CREATE|DROP|ALTER)$Ce+(DATABASE|SCHEMA)\\b~isU",$G)){restart_session();set_session("dbs",null);session_write_close();}if(!$_POST["only_errors"]){echo"<p class='message' title='".h($e->info)."'>".lang(array('Query executed OK, %d row affected.','Query executed OK, %d rows affected.'),$e->affected_rows)."$cf\n";}}$Ee=$_b;}while($e->next_result());$H=substr($H,$hd);$hd=0;}}}}if($zb){echo"<p class='message'>".'No commands to execute.'."\n";}elseif($_POST["only_errors"]){echo"<p class='message'>".lang(array('%d query executed OK.','%d queries executed OK.'),$Oa-count($Eb)).format_time($jf,microtime())."\n";}elseif($Eb&&$Oa>1){echo"<p class='error'>".'Error in query'.": ".implode("",$Eb)."\n";}}else{echo"<p class='error'>".upload_error($H)."\n";}}echo'
<form action="" method="post" enctype="multipart/form-data" id="form">
<p>';$G=$_GET["sql"];if($_POST){$G=$_POST["query"];}elseif($_GET["history"]=="all"){$G=$gc;}elseif($_GET["history"]!=""){$G=$gc[$_GET["history"]];}textarea("query",$G,20);echo($_POST?"":"<script type='text/javascript'>document.getElementsByTagName('textarea')[0].focus();</script>\n"),"<p>".(ini_bool("file_uploads")?'File upload'.': <input type="file" name="sql_file"'.($_FILES&&$_FILES["sql_file"]["error"]!=4?'':' onchange="this.form[\'only_errors\'].checked = true;"').'> (&lt; '.ini_get("upload_max_filesize").'B)':'File uploads are disabled.'),'<p>
<input type="submit" value="Execute" title="Ctrl+Enter">
<input type="hidden" name="token" value="',$U,'">
',checkbox("error_stops",1,$_POST["error_stops"],'Stop on error')."\n",checkbox("only_errors",1,$_POST["only_errors"],'Show only errors')."\n";print_fieldset("webfile",'From server',$_POST["webfile"],"document.getElementById('form')['only_errors'].checked = true; ");$Ra=array();foreach(array("gz"=>"zlib","bz2"=>"bz2")as$y=>$X){if(extension_loaded($X)){$Ra[]=".$y";}}echo
sprintf('Webserver file %s',"<code>adminer.sql".($Ra?"[".implode("|",$Ra)."]":"")."</code>"),' <input type="submit" name="webfile" value="'.'Run file'.'">',"</div></fieldset>\n";if($gc){print_fieldset("history",'History',$_GET["history"]!="");foreach($gc
as$y=>$X){echo'<a href="'.h(ME."sql=&history=$y").'">'.'Edit'."</a> <code class='jush-$x'>".shorten_utf8(ltrim(str_replace("\n"," ",str_replace("\r","",preg_replace('~^(#|-- ).*~m','',$X)))),80,"</code>")."<br>\n";}echo"<input type='submit' name='clear' value='".'Clear'."'>\n","<a href='".h(ME."sql=&history=all")."'>".'Edit all'."</a>\n","</div></fieldset>\n";}echo'
</form>
';}elseif(isset($_GET["edit"])){$a=$_GET["edit"];$Z=(isset($_GET["select"])?(count($_POST["check"])==1?where_check($_POST["check"][0]):""):where($_GET));$_f=(isset($_GET["select"])?$_POST["edit"]:$Z);$k=fields($a);foreach($k
as$D=>$j){if(!isset($j["privileges"][$_f?"update":"insert"])||$b->fieldName($j)==""){unset($k[$D]);}}if($_POST&&!$i&&!isset($_GET["select"])){$A=$_POST["referer"];if($_POST["insert"]){$A=($_f?null:$_SERVER["REQUEST_URI"]);}elseif(!ereg('^.+&select=.+$',$A)){$A=ME."select=".urlencode($a);}if(isset($_POST["delete"])){query_redirect("DELETE".limit1("FROM ".table($a)," WHERE $Z"),$A,'Item has been deleted.');}else{$P=array();foreach($k
as$D=>$j){$X=process_input($j);if($X!==false&&$X!==null){$P[idf_escape($D)]=($_f?"\n".idf_escape($D)." = $X":$X);}}if($_f){if(!$P){redirect($A);}query_redirect("UPDATE".limit1(table($a)." SET".implode(",",$P),"\nWHERE $Z"),$A,'Item has been updated.');}else{$I=insert_into($a,$P);$Bc=($I?last_id():0);queries_redirect($A,sprintf('Item%s has been inserted.',($Bc?" $Bc":"")),$I);}}}$Re=$b->tableName(table_status($a));page_header(($_f?'Edit':'Insert'),$i,array("select"=>array($a,$Re)),$Re);$K=null;if($_POST["save"]){$K=(array)$_POST["fields"];}elseif($Z){$N=array();foreach($k
as$D=>$j){if(isset($j["privileges"]["select"])){$N[]=($_POST["clone"]&&$j["auto_increment"]?"'' AS ":(ereg("enum|set",$j["type"])?"1*".idf_escape($D)." AS ":"")).idf_escape($D);}}$K=array();if($N){$L=get_rows("SELECT".limit(implode(", ",$N)." FROM ".table($a)," WHERE $Z",(isset($_GET["select"])?2:1)));$K=(isset($_GET["select"])&&count($L)!=1?null:reset($L));}}if($K===false){echo"<p class='error'>".'No rows.'."\n";}echo'
<form action="" method="post" enctype="multipart/form-data" id="form">
';if($k){echo"<table cellspacing='0' onkeydown='return editingKeydown(event);'>\n";foreach($k
as$D=>$j){echo"<tr><th>".$b->fieldName($j);$hb=$_GET["set"][bracket_escape($D)];$Y=(isset($K)?($K[$D]!=""&&ereg("enum|set",$j["type"])?(is_array($K[$D])?array_sum($K[$D]):+$K[$D]):$K[$D]):(!$_f&&$j["auto_increment"]?"":(isset($_GET["select"])?false:(isset($hb)?$hb:$j["default"]))));if(!$_POST["save"]&&is_string($Y)){$Y=$b->editVal($Y,$j);}$o=($_POST["save"]?(string)$_POST["function"][$D]:($_f&&$j["on_update"]=="CURRENT_TIMESTAMP"?"now":($Y===false?null:(isset($Y)?'':'NULL'))));if($j["type"]=="timestamp"&&$Y=="CURRENT_TIMESTAMP"){$Y="";$o="now";}input($j,$Y,$o);echo"\n";}echo"</table>\n";}echo'<p>
';if($k){echo"<input type='submit' value='".'Save'."'>\n";if(!isset($_GET["select"])){echo"<input type='submit' name='insert' value='".($_f?'Save and continue edit':'Save and insert next')."' title='Ctrl+Shift+Enter'>\n";}}echo($_f?"<input type='submit' name='delete' value='".'Delete'."' onclick=\"return confirm('".'Are you sure?'."');\">\n":($_POST||!$k?"":"<script type='text/javascript'>document.getElementById('form').getElementsByTagName('td')[1].firstChild.focus();</script>\n"));if(isset($_GET["select"])){hidden_fields(array("check"=>(array)$_POST["check"],"clone"=>$_POST["clone"],"all"=>$_POST["all"]));}echo'<input type="hidden" name="referer" value="',h(isset($_POST["referer"])?$_POST["referer"]:$_SERVER["HTTP_REFERER"]),'">
<input type="hidden" name="save" value="1">
<input type="hidden" name="token" value="',$U,'">
</form>
';}elseif(isset($_GET["create"])){$a=$_GET["create"];$Fd=array('HASH','LINEAR HASH','KEY','LINEAR KEY','RANGE','LIST');$ge=referencable_primary($a);$m=array();foreach($ge
as$Re=>$j){$m[str_replace("`","``",$Re)."`".str_replace("`","``",$j["field"])]=$Re;}$yd=array();$zd=array();if($a!=""){$yd=fields($a);$zd=table_status($a);}if($_POST&&!$_POST["fields"]){$_POST["fields"]=array();}if($_POST&&!$i&&!$_POST["add"]&&!$_POST["drop_col"]&&!$_POST["up"]&&!$_POST["down"]){if($_POST["drop"]){query_redirect("DROP TABLE ".table($a),substr(ME,0,-1),'Table has been dropped.');}else{$k=array();$Wb=array();ksort($_POST["fields"]);$xd=reset($yd);$oa="FIRST";foreach($_POST["fields"]as$y=>$j){$l=$m[$j["type"]];$qf=(isset($l)?$ge[$l]:$j);if($j["field"]!=""){if(!$j["has_default"]){$j["default"]=null;}$hb=eregi_replace(" *on update CURRENT_TIMESTAMP","",$j["default"]);if($hb!=$j["default"]){$j["on_update"]="CURRENT_TIMESTAMP";$j["default"]=$hb;}if($y==$_POST["auto_increment_col"]){$j["auto_increment"]=true;}$Zd=process_field($j,$qf);if($Zd!=process_field($xd,$xd)){$k[]=array($j["orig"],$Zd,$oa);}if(isset($l)){$Wb[idf_escape($j["field"])]=($a!=""?"ADD":" ")." FOREIGN KEY (".idf_escape($j["field"]).") REFERENCES ".table($m[$j["type"]])." (".idf_escape($qf["field"]).")".(ereg("^($md)\$",$j["on_delete"])?" ON DELETE $j[on_delete]":"");}$oa="AFTER ".idf_escape($j["field"]);}elseif($j["orig"]!=""){$k[]=array($j["orig"]);}if($j["orig"]!=""){$xd=next($yd);}}$Hd="";if(in_array($_POST["partition_by"],$Fd)){$Id=array();if($_POST["partition_by"]=='RANGE'||$_POST["partition_by"]=='LIST'){foreach(array_filter($_POST["partition_names"])as$y=>$X){$Y=$_POST["partition_values"][$y];$Id[]="\nPARTITION ".idf_escape($X)." VALUES ".($_POST["partition_by"]=='RANGE'?"LESS THAN":"IN").($Y!=""?" ($Y)":" MAXVALUE");}}$Hd.="\nPARTITION BY $_POST[partition_by]($_POST[partition])".($Id?" (".implode(",",$Id)."\n)":($_POST["partitions"]?" PARTITIONS ".(+$_POST["partitions"]):""));}elseif($a!=""&&support("partitioning")){$Hd.="\nREMOVE PARTITIONING";}$Sc='Table has been altered.';if($a==""){cookie("adminer_engine",$_POST["Engine"]);$Sc='Table has been created.';}queries_redirect(ME."table=".urlencode($_POST["name"]),$Sc,alter_table($a,$_POST["name"],$k,$Wb,$_POST["Comment"],($_POST["Engine"]&&$_POST["Engine"]!=$zd["Engine"]?$_POST["Engine"]:""),($_POST["Collation"]&&$_POST["Collation"]!=$zd["Collation"]?$_POST["Collation"]:""),($_POST["Auto_increment"]!=""?+$_POST["Auto_increment"]:""),$Hd));}}page_header(($a!=""?'Alter table':'Create table'),$i,array("table"=>$a),$a);$K=array("Engine"=>$_COOKIE["adminer_engine"],"fields"=>array(array("field"=>"","type"=>(isset($sf["int"])?"int":(isset($sf["integer"])?"integer":"")))),"partition_names"=>array(""),);if($_POST){$K=$_POST;if($K["auto_increment_col"]){$K["fields"][$K["auto_increment_col"]]["auto_increment"]=true;}process_fields($K["fields"]);}elseif($a!=""){$K=$zd;$K["name"]=$a;$K["fields"]=array();if(!$_GET["auto_increment"]){$K["Auto_increment"]="";}foreach($yd
as$j){$j["has_default"]=isset($j["default"]);if($j["on_update"]){$j["default"].=" ON UPDATE $j[on_update]";}$K["fields"][]=$j;}if(support("partitioning")){$ac="FROM information_schema.PARTITIONS WHERE TABLE_SCHEMA = ".q(DB)." AND TABLE_NAME = ".q($a);$I=$e->query("SELECT PARTITION_METHOD, PARTITION_ORDINAL_POSITION, PARTITION_EXPRESSION $ac ORDER BY PARTITION_ORDINAL_POSITION DESC LIMIT 1");list($K["partition_by"],$K["partitions"],$K["partition"])=$I->fetch_row();$K["partition_names"]=array();$K["partition_values"]=array();foreach(get_rows("SELECT PARTITION_NAME, PARTITION_DESCRIPTION $ac AND PARTITION_NAME != '' ORDER BY PARTITION_ORDINAL_POSITION")as$se){$K["partition_names"][]=$se["PARTITION_NAME"];$K["partition_values"][]=$se["PARTITION_DESCRIPTION"];}$K["partition_names"][]="";}}$c=collations();$Me=floor(extension_loaded("suhosin")?(min(ini_get("suhosin.request.max_vars"),ini_get("suhosin.post.max_vars"))-13)/10:0);if($Me&&count($K["fields"])>$Me){echo"<p class='error'>".h(sprintf('Maximum number of allowed fields exceeded. Please increase %s and %s.','suhosin.post.max_vars','suhosin.request.max_vars'))."\n";}$Bb=engines();foreach($Bb
as$Ab){if(!strcasecmp($Ab,$K["Engine"])){$K["Engine"]=$Ab;break;}}echo'
<form action="" method="post" id="form">
<p>
Table name: <input name="name" maxlength="64" value="',h($K["name"]),'">
';if($a==""&&!$_POST){?><script type='text/javascript'>document.getElementById('form')['name'].focus();</script><?php }echo($Bb?html_select("Engine",array(""=>"(".'engine'.")")+$Bb,$K["Engine"]):""),' ',($c&&!ereg("sqlite|mssql",$x)?html_select("Collation",array(""=>"(".'collation'.")")+$c,$K["Collation"]):""),' <input type="submit" value="Save">
<table cellspacing="0" id="edit-fields" class="nowrap">
';$Qa=($_POST?$_POST["comments"]:$K["Comment"]!="");if(!$_POST&&!$Qa){foreach($K["fields"]as$j){if($j["comment"]!=""){$Qa=true;break;}}}edit_fields($K["fields"],$c,"TABLE",$Me,$m,$Qa);echo'</table>
<p>
Auto Increment: <input name="Auto_increment" size="6" value="',h($K["Auto_increment"]),'">
<label class="jsonly"><input type="checkbox" name="defaults" value="1"',($_POST["defaults"]?" checked":""),' onclick="columnShow(this.checked, 5);">Default values</label>
',(support("comment")?checkbox("comments",1,$Qa,'Comment',"columnShow(this.checked, 6); toggle('Comment'); if (this.checked) this.form['Comment'].focus();",true).' <input id="Comment" name="Comment" value="'.h($K["Comment"]).'" maxlength="60"'.($Qa?'':' class="hidden"').'>':''),'<p>
<input type="submit" value="Save">
';if($_GET["create"]!=""){echo'<input type="submit" name="drop" value="Drop"',confirm(),'>';}echo'<input type="hidden" name="token" value="',$U,'">
';if(support("partitioning")){$Gd=ereg('RANGE|LIST',$K["partition_by"]);print_fieldset("partition",'Partition by',$K["partition_by"]);echo'<p>
',html_select("partition_by",array(-1=>"")+$Fd,$K["partition_by"],"partitionByChange(this);"),'(<input name="partition" value="',h($K["partition"]),'">)
Partitions: <input name="partitions" size="2" value="',h($K["partitions"]),'"',($Gd||!$K["partition_by"]?" class='hidden'":""),'>
<table cellspacing="0" id="partition-table"',($Gd?"":" class='hidden'"),'>
<thead><tr><th>Partition name<th>Values</thead>
';foreach($K["partition_names"]as$y=>$X){echo'<tr>','<td><input name="partition_names[]" value="'.h($X).'"'.($y==count($K["partition_names"])-1?' onchange="partitionNameChange(this);"':'').'>','<td><input name="partition_values[]" value="'.h($K["partition_values"][$y]).'">';}echo'</table>
</div></fieldset>
';}echo'</form>
';}elseif(isset($_GET["indexes"])){$a=$_GET["indexes"];$oc=array("PRIMARY","UNIQUE","INDEX");$T=table_status($a);if(eregi("MyISAM|M?aria",$T["Engine"])){$oc[]="FULLTEXT";}$v=indexes($a);if($x=="sqlite"){unset($oc[0]);unset($v[""]);}if($_POST&&!$i&&!$_POST["add"]){$ra=array();foreach($_POST["indexes"]as$u){$D=$u["name"];if(in_array($u["type"],$oc)){$d=array();$Hc=array();$P=array();ksort($u["columns"]);foreach($u["columns"]as$y=>$Ma){if($Ma!=""){$Gc=$u["lengths"][$y];$P[]=idf_escape($Ma).($Gc?"(".(+$Gc).")":"");$d[]=$Ma;$Hc[]=($Gc?$Gc:null);}}if($d){$Kb=$v[$D];if($Kb){ksort($Kb["columns"]);ksort($Kb["lengths"]);if($u["type"]==$Kb["type"]&&array_values($Kb["columns"])===$d&&(!$Kb["lengths"]||array_values($Kb["lengths"])===$Hc)){unset($v[$D]);continue;}}$ra[]=array($u["type"],$D,"(".implode(", ",$P).")");}}}foreach($v
as$D=>$Kb){$ra[]=array($Kb["type"],$D,"DROP");}if(!$ra){redirect(ME."table=".urlencode($a));}queries_redirect(ME."table=".urlencode($a),'Indexes have been altered.',alter_indexes($a,$ra));}page_header('Indexes',$i,array("table"=>$a),$a);$k=array_keys(fields($a));$K=array("indexes"=>$v);if($_POST){$K=$_POST;if($_POST["add"]){foreach($K["indexes"]as$y=>$u){if($u["columns"][count($u["columns"])]!=""){$K["indexes"][$y]["columns"][]="";}}$u=end($K["indexes"]);if($u["type"]||array_filter($u["columns"],'strlen')||array_filter($u["lengths"],'strlen')){$K["indexes"][]=array("columns"=>array(1=>""));}}}else{foreach($K["indexes"]as$y=>$u){$K["indexes"][$y]["name"]=$y;$K["indexes"][$y]["columns"][]="";}$K["indexes"][]=array("columns"=>array(1=>""));}echo'
<form action="" method="post">
<table cellspacing="0" class="nowrap">
<thead><tr><th>Index Type<th>Column (length)<th>Name</thead>
';$w=1;foreach($K["indexes"]as$u){echo"<tr><td>".html_select("indexes[$w][type]",array(-1=>"")+$oc,$u["type"],($w==count($K["indexes"])?"indexesAddRow(this);":1))."<td>";ksort($u["columns"]);$s=1;foreach($u["columns"]as$y=>$Ma){echo"<span>".html_select("indexes[$w][columns][$s]",array(-1=>"")+$k,$Ma,($s==count($u["columns"])?"indexesAddColumn":"indexesChangeColumn")."(this, '".js_escape($x=="sql"?"":$_GET["indexes"]."_")."');"),"<input name='indexes[$w][lengths][$s]' size='2' value='".h($u["lengths"][$y])."'> </span>";$s++;}echo"<td><input name='indexes[$w][name]' value='".h($u["name"])."'>\n";$w++;}echo'</table>
<p>
<input type="submit" value="Save">
<noscript><p><input type="submit" name="add" value="Add next"></noscript>
<input type="hidden" name="token" value="',$U,'">
</form>
';}elseif(isset($_GET["database"])){if($_POST&&!$i&&!isset($_POST["add_x"])){restart_session();if($_POST["drop"]){$_GET["db"]="";queries_redirect(remove_from_uri("db|database"),'Database has been dropped.',drop_databases(array(DB)));}elseif(DB!==$_POST["name"]){if(DB!=""){$_GET["db"]=$_POST["name"];queries_redirect(preg_replace('~db=[^&]*&~','',ME)."db=".urlencode($_POST["name"]),'Database has been renamed.',rename_database($_POST["name"],$_POST["collation"]));}else{$g=explode("\n",str_replace("\r","",$_POST["name"]));$Ke=true;$Ac="";foreach($g
as$h){if(count($g)==1||$h!=""){if(!create_database($h,$_POST["collation"])){$Ke=false;}$Ac=$h;}}queries_redirect(ME."db=".urlencode($Ac),'Database has been created.',$Ke);}}else{if(!$_POST["collation"]){redirect(substr(ME,0,-1));}query_redirect("ALTER DATABASE ".idf_escape($_POST["name"]).(eregi('^[a-z0-9_]+$',$_POST["collation"])?" COLLATE $_POST[collation]":""),substr(ME,0,-1),'Database has been altered.');}}page_header(DB!=""?'Alter database':'Create database',$i,array(),DB);$c=collations();$D=DB;$Ja=null;if($_POST){$D=$_POST["name"];$Ja=$_POST["collation"];}elseif(DB!=""){$Ja=db_collation(DB,$c);}elseif($x=="sql"){foreach(get_vals("SHOW GRANTS")as$q){if(preg_match('~ ON (`(([^\\\\`]|``|\\\\.)*)%`\\.\\*)?~',$q,$B)&&$B[1]){$D=stripcslashes(idf_unescape("`$B[2]`"));break;}}}echo'
<form action="" method="post">
<p>
',($_POST["add_x"]||strpos($D,"\n")?'<textarea id="name" name="name" rows="10" cols="40">'.h($D).'</textarea><br>':'<input id="name" name="name" value="'.h($D).'" maxlength="64">')."\n".($c?html_select("collation",array(""=>"(".'collation'.")")+$c,$Ja):"");?>
<script type='text/javascript'>document.getElementById('name').focus();</script>
<input type="submit" value="Save">
<?php
if(DB!=""){echo"<input type='submit' name='drop' value='".'Drop'."'".confirm().">\n";}elseif(!$_POST["add_x"]&&$_GET["db"]==""){echo"<input type='image' name='add' src='".h(preg_replace("~\\?.*~","",ME))."?file=plus.gif&amp;version=3.3.3' alt='+' title='".'Add next'."'>\n";}echo'<input type="hidden" name="token" value="',$U,'">
</form>
';}elseif(isset($_GET["call"])){$da=$_GET["call"];page_header('Call'.": ".h($da),$i);$pe=routine($da,(isset($_GET["callf"])?"FUNCTION":"PROCEDURE"));$nc=array();$_d=array();foreach($pe["fields"]as$s=>$j){if(substr($j["inout"],-3)=="OUT"){$_d[$s]="@".idf_escape($j["field"])." AS ".idf_escape($j["field"]);}if(!$j["inout"]||substr($j["inout"],0,2)=="IN"){$nc[]=$s;}}if(!$i&&$_POST){$Da=array();foreach($pe["fields"]as$y=>$j){if(in_array($y,$nc)){$X=process_input($j);if($X===false){$X="''";}if(isset($_d[$y])){$e->query("SET @".idf_escape($j["field"])." = $X");}}$Da[]=(isset($_d[$y])?"@".idf_escape($j["field"]):$X);}$H=(isset($_GET["callf"])?"SELECT":"CALL")." ".idf_escape($da)."(".implode(", ",$Da).")";echo"<p><code class='jush-$x'>".h($H)."</code> <a href='".h(ME)."sql=".urlencode($H)."'>".'Edit'."</a>\n";if(!$e->multi_query($H)){echo"<p class='error'>".error()."\n";}else{$f=connect();if(is_object($f)){$f->select_db(DB);}do{$I=$e->store_result();if(is_object($I)){select($I,$f);}else{echo"<p class='message'>".lang(array('Routine has been called, %d row affected.','Routine has been called, %d rows affected.'),$e->affected_rows)."\n";}}while($e->next_result());if($_d){select($e->query("SELECT ".implode(", ",$_d)));}}}echo'
<form action="" method="post">
';if($nc){echo"<table cellspacing='0'>\n";foreach($nc
as$y){$j=$pe["fields"][$y];$D=$j["field"];echo"<tr><th>".$b->fieldName($j);$Y=$_POST["fields"][$D];if($Y!=""){if($j["type"]=="enum"){$Y=+$Y;}if($j["type"]=="set"){$Y=array_sum($Y);}}input($j,$Y,(string)$_POST["function"][$D]);echo"\n";}echo"</table>\n";}echo'<p>
<input type="submit" value="Call">
<input type="hidden" name="token" value="',$U,'">
</form>
';}elseif(isset($_GET["foreign"])){$a=$_GET["foreign"];if($_POST&&!$i&&!$_POST["add"]&&!$_POST["change"]&&!$_POST["change-js"]){if($_POST["drop"]){query_redirect("ALTER TABLE ".table($a)."\nDROP ".($x=="sql"?"FOREIGN KEY ":"CONSTRAINT ").idf_escape($_GET["name"]),ME."table=".urlencode($a),'Foreign key has been dropped.');}else{$Be=array_filter($_POST["source"],'strlen');ksort($Be);$Ye=array();foreach($Be
as$y=>$X){$Ye[$y]=$_POST["target"][$y];}query_redirect("ALTER TABLE ".table($a).($_GET["name"]!=""?"\nDROP FOREIGN KEY ".idf_escape($_GET["name"]).",":"")."\nADD FOREIGN KEY (".implode(", ",array_map('idf_escape',$Be)).") REFERENCES ".table($_POST["table"])." (".implode(", ",array_map('idf_escape',$Ye)).")".(ereg("^($md)\$",$_POST["on_delete"])?" ON DELETE $_POST[on_delete]":"").(ereg("^($md)\$",$_POST["on_update"])?" ON UPDATE $_POST[on_update]":""),ME."table=".urlencode($a),($_GET["name"]!=""?'Foreign key has been altered.':'Foreign key has been created.'));$i='Source and target columns must have the same data type, there must be an index on the target columns and referenced data must exist.'."<br>$i";}}page_header('Foreign key',$i,array("table"=>$a),$a);$K=array("table"=>$a,"source"=>array(""));if($_POST){$K=$_POST;ksort($K["source"]);if($_POST["add"]){$K["source"][]="";}elseif($_POST["change"]||$_POST["change-js"]){$K["target"]=array();}}elseif($_GET["name"]!=""){$m=foreign_keys($a);$K=$m[$_GET["name"]];$K["source"][]="";}$Be=array_keys(fields($a));$Ye=($a===$K["table"]?$Be:array_keys(fields($K["table"])));$fe=array();foreach(table_status()as$D=>$T){if(fk_support($T)){$fe[]=$D;}}echo'
<form action="" method="post">
<p>
';if($K["db"]==""&&$K["ns"]==""){echo'Target table:
',html_select("table",$fe,$K["table"],"this.form['change-js'].value = '1'; if (!ajaxForm(this.form)) this.form.submit();"),'<input type="hidden" name="change-js" value="">
<noscript><p><input type="submit" name="change" value="Change"></noscript>
<table cellspacing="0">
<thead><tr><th>Source<th>Target</thead>
';$w=0;foreach($K["source"]as$y=>$X){echo"<tr>","<td>".html_select("source[".(+$y)."]",array(-1=>"")+$Be,$X,($w==count($K["source"])-1?"foreignAddRow(this);":1)),"<td>".html_select("target[".(+$y)."]",$Ye,$K["target"][$y]);$w++;}echo'</table>
<p>
ON DELETE: ',html_select("on_delete",array(-1=>"")+explode("|",$md),$K["on_delete"]),' ON UPDATE: ',html_select("on_update",array(-1=>"")+explode("|",$md),$K["on_update"]),'<p>
<input type="submit" value="Save">
<noscript><p><input type="submit" name="add" value="Add column"></noscript>
';}if($_GET["name"]!=""){echo'<input type="submit" name="drop" value="Drop"',confirm(),'>';}echo'<input type="hidden" name="token" value="',$U,'">
</form>
';}elseif(isset($_GET["view"])){$a=$_GET["view"];$qb=false;if($_POST&&!$i){$qb=drop_create("DROP VIEW ".table($a),"CREATE VIEW ".table($_POST["name"])." AS\n$_POST[select]",($_POST["drop"]?substr(ME,0,-1):ME."table=".urlencode($_POST["name"])),'View has been dropped.','View has been altered.','View has been created.',$a);}page_header(($a!=""?'Alter view':'Create view'),$i,array("table"=>$a),$a);$K=$_POST;if(!$K&&$a!=""){$K=view($a);$K["name"]=$a;}echo'
<form action="" method="post">
<p>Name: <input name="name" value="',h($K["name"]),'" maxlength="64">
<p>';textarea("select",$K["select"]);echo'<p>
';if($qb){echo'<input type="hidden" name="dropped" value="1">';}echo'<input type="submit" value="Save">
';if($_GET["view"]!=""){echo'<input type="submit" name="drop" value="Drop"',confirm(),'>';}echo'<input type="hidden" name="token" value="',$U,'">
</form>
';}elseif(isset($_GET["event"])){$aa=$_GET["event"];$tc=array("YEAR","QUARTER","MONTH","DAY","HOUR","MINUTE","WEEK","SECOND","YEAR_MONTH","DAY_HOUR","DAY_MINUTE","DAY_SECOND","HOUR_MINUTE","HOUR_SECOND","MINUTE_SECOND");$Ge=array("ENABLED"=>"ENABLE","DISABLED"=>"DISABLE","SLAVESIDE_DISABLED"=>"DISABLE ON SLAVE");if($_POST&&!$i){if($_POST["drop"]){query_redirect("DROP EVENT ".idf_escape($aa),substr(ME,0,-1),'Event has been dropped.');}elseif(in_array($_POST["INTERVAL_FIELD"],$tc)&&isset($Ge[$_POST["STATUS"]])){$te="\nON SCHEDULE ".($_POST["INTERVAL_VALUE"]?"EVERY ".q($_POST["INTERVAL_VALUE"])." $_POST[INTERVAL_FIELD]".($_POST["STARTS"]?" STARTS ".q($_POST["STARTS"]):"").($_POST["ENDS"]?" ENDS ".q($_POST["ENDS"]):""):"AT ".q($_POST["STARTS"]))." ON COMPLETION".($_POST["ON_COMPLETION"]?"":" NOT")." PRESERVE";queries_redirect(substr(ME,0,-1),($aa!=""?'Event has been altered.':'Event has been created.'),queries(($aa!=""?"ALTER EVENT ".idf_escape($aa).$te.($aa!=$_POST["EVENT_NAME"]?"\nRENAME TO ".idf_escape($_POST["EVENT_NAME"]):""):"CREATE EVENT ".idf_escape($_POST["EVENT_NAME"]).$te)."\n".$Ge[$_POST["STATUS"]]." COMMENT ".q($_POST["EVENT_COMMENT"]).rtrim(" DO\n$_POST[EVENT_DEFINITION]",";").";"));}}page_header(($aa!=""?'Alter event'.": ".h($aa):'Create event'),$i);$K=$_POST;if(!$K&&$aa!=""){$L=get_rows("SELECT * FROM information_schema.EVENTS WHERE EVENT_SCHEMA = ".q(DB)." AND EVENT_NAME = ".q($aa));$K=reset($L);}echo'
<form action="" method="post">
<table cellspacing="0">
<tr><th>Name<td><input name="EVENT_NAME" value="',h($K["EVENT_NAME"]),'" maxlength="64">
<tr><th>Start<td><input name="STARTS" value="',h("$K[EXECUTE_AT]$K[STARTS]"),'">
<tr><th>End<td><input name="ENDS" value="',h($K["ENDS"]),'">
<tr><th>Every<td><input name="INTERVAL_VALUE" value="',h($K["INTERVAL_VALUE"]),'" size="6"> ',html_select("INTERVAL_FIELD",$tc,$K["INTERVAL_FIELD"]),'<tr><th>Status<td>',html_select("STATUS",$Ge,$K["STATUS"]),'<tr><th>Comment<td><input name="EVENT_COMMENT" value="',h($K["EVENT_COMMENT"]),'" maxlength="64">
<tr><th>&nbsp;<td>',checkbox("ON_COMPLETION","PRESERVE",$K["ON_COMPLETION"]=="PRESERVE",'On completion preserve'),'</table>
<p>';textarea("EVENT_DEFINITION",$K["EVENT_DEFINITION"]);echo'<p>
<input type="submit" value="Save">
';if($aa!=""){echo'<input type="submit" name="drop" value="Drop"',confirm(),'>';}echo'<input type="hidden" name="token" value="',$U,'">
</form>
';}elseif(isset($_GET["procedure"])){$da=$_GET["procedure"];$pe=(isset($_GET["function"])?"FUNCTION":"PROCEDURE");$qe=routine_languages();$qb=false;if($_POST&&!$i&&!$_POST["add"]&&!$_POST["drop_col"]&&!$_POST["up"]&&!$_POST["down"]){$P=array();$k=(array)$_POST["fields"];ksort($k);foreach($k
as$j){if($j["field"]!=""){$P[]=(ereg("^($qc)\$",$j["inout"])?"$j[inout] ":"").idf_escape($j["field"]).process_type($j,"CHARACTER SET");}}$qb=drop_create("DROP $pe ".idf_escape($da),"CREATE $pe ".idf_escape($_POST["name"])." (".implode(", ",$P).")".(isset($_GET["function"])?" RETURNS".process_type($_POST["returns"],"CHARACTER SET"):"").(in_array($_POST["language"],$qe)?" LANGUAGE $_POST[language]":"").rtrim("\n$_POST[definition]",";").";",substr(ME,0,-1),'Routine has been dropped.','Routine has been altered.','Routine has been created.',$da);}page_header(($da!=""?(isset($_GET["function"])?'Alter function':'Alter procedure').": ".h($da):(isset($_GET["function"])?'Create function':'Create procedure')),$i);$c=get_vals("SHOW CHARACTER SET");sort($c);$K=array("fields"=>array());if($_POST){$K=$_POST;$K["fields"]=(array)$K["fields"];process_fields($K["fields"]);}elseif($da!=""){$K=routine($da,$pe);$K["name"]=$da;}echo'
<form action="" method="post" id="form">
<p>Name: <input name="name" value="',h($K["name"]),'" maxlength="64">
',($qe?'Language'.": ".html_select("language",$qe,$K["language"]):""),'<table cellspacing="0" class="nowrap">
';edit_fields($K["fields"],$c,$pe);if(isset($_GET["function"])){echo"<tr><td>".'Return type';edit_type("returns",$K["returns"],$c);}echo'</table>
<p>';textarea("definition",$K["definition"]);echo'<p>
<input type="submit" value="Save">
';if($da!=""){echo'<input type="submit" name="drop" value="Drop"',confirm(),'>';}if($qb){echo'<input type="hidden" name="dropped" value="1">';}echo'<input type="hidden" name="token" value="',$U,'">
</form>
';}elseif(isset($_GET["trigger"])){$a=$_GET["trigger"];$of=trigger_options();$nf=array("INSERT","UPDATE","DELETE");$qb=false;if($_POST&&!$i&&in_array($_POST["Timing"],$of["Timing"])&&in_array($_POST["Event"],$nf)&&in_array($_POST["Type"],$of["Type"])){$df=" $_POST[Timing] $_POST[Event]";$ld=" ON ".table($a);$qb=drop_create("DROP TRIGGER ".idf_escape($_GET["name"]).($x=="pgsql"?$ld:""),"CREATE TRIGGER ".idf_escape($_POST["Trigger"]).($x=="mssql"?$ld.$df:$df.$ld).rtrim(" $_POST[Type]\n$_POST[Statement]",";").";",ME."table=".urlencode($a),'Trigger has been dropped.','Trigger has been altered.','Trigger has been created.',$_GET["name"]);}page_header(($_GET["name"]!=""?'Alter trigger'.": ".h($_GET["name"]):'Create trigger'),$i,array("table"=>$a));$K=$_POST;if(!$K){$K=trigger($_GET["name"])+array("Trigger"=>$a."_bi");}echo'
<form action="" method="post" id="form">
<table cellspacing="0">
<tr><th>Time<td>',html_select("Timing",$of["Timing"],$K["Timing"],"if (/^".preg_quote($a,"/")."_[ba][iud]$/.test(this.form['Trigger'].value)) this.form['Trigger'].value = '".js_escape($a)."_' + selectValue(this).charAt(0).toLowerCase() + selectValue(this.form['Event']).charAt(0).toLowerCase();"),'<tr><th>Event<td>',html_select("Event",$nf,$K["Event"],"this.form['Timing'].onchange();"),'<tr><th>Type<td>',html_select("Type",$of["Type"],$K["Type"]),'</table>
<p>Name: <input name="Trigger" value="',h($K["Trigger"]),'" maxlength="64">
<p>';textarea("Statement",$K["Statement"]);echo'<p>
<input type="submit" value="Save">
';if($_GET["name"]!=""){echo'<input type="submit" name="drop" value="Drop"',confirm(),'>';}if($qb){echo'<input type="hidden" name="dropped" value="1">';}echo'<input type="hidden" name="token" value="',$U,'">
</form>
';}elseif(isset($_GET["user"])){$fa=$_GET["user"];$Xd=array(""=>array("All privileges"=>""));foreach(get_rows("SHOW PRIVILEGES")as$K){foreach(explode(",",($K["Privilege"]=="Grant option"?"":$K["Context"]))as$Ua){$Xd[$Ua][$K["Privilege"]]=$K["Comment"];}}$Xd["Server Admin"]+=$Xd["File access on server"];$Xd["Databases"]["Create routine"]=$Xd["Procedures"]["Create routine"];unset($Xd["Procedures"]["Create routine"]);$Xd["Columns"]=array();foreach(array("Select","Insert","Update","References")as$X){$Xd["Columns"][$X]=$Xd["Tables"][$X];}unset($Xd["Server Admin"]["Usage"]);foreach($Xd["Tables"]as$y=>$X){unset($Xd["Databases"][$y]);}$cd=array();if($_POST){foreach($_POST["objects"]as$y=>$X){$cd[$X]=(array)$cd[$X]+(array)$_POST["grants"][$y];}}$cc=array();$jd="";if(isset($_GET["host"])&&($I=$e->query("SHOW GRANTS FOR ".q($fa)."@".q($_GET["host"])))){while($K=$I->fetch_row()){if(preg_match('~GRANT (.*) ON (.*) TO ~',$K[0],$B)&&preg_match_all('~ *([^(,]*[^ ,(])( *\\([^)]+\\))?~',$B[1],$Lc,PREG_SET_ORDER)){foreach($Lc
as$X){if($X[1]!="USAGE"){$cc["$B[2]$X[2]"][$X[1]]=true;}if(ereg(' WITH GRANT OPTION',$K[0])){$cc["$B[2]$X[2]"]["GRANT OPTION"]=true;}}}if(preg_match("~ IDENTIFIED BY PASSWORD '([^']+)~",$K[0],$B)){$jd=$B[1];}}}if($_POST&&!$i){$kd=(isset($_GET["host"])?q($fa)."@".q($_GET["host"]):"''");$dd=q($_POST["user"])."@".q($_POST["host"]);$Jd=q($_POST["pass"]);if($_POST["drop"]){query_redirect("DROP USER $kd",ME."privileges=",'User has been dropped.');}else{$Za=false;if($kd!=$dd){$Za=queries(($e->server_info<5?"GRANT USAGE ON *.* TO":"CREATE USER")." $dd IDENTIFIED BY".($_POST["hashed"]?" PASSWORD":"")." $Jd");$i=!$Za;}elseif($_POST["pass"]!=$jd||!$_POST["hashed"]){queries("SET PASSWORD FOR $dd = ".($_POST["hashed"]?$Jd:"PASSWORD($Jd)"));}if(!$i){$me=array();foreach($cd
as$gd=>$q){if(isset($_GET["grant"])){$q=array_filter($q);}$q=array_keys($q);if(isset($_GET["grant"])){$me=array_diff(array_keys(array_filter($cd[$gd],'strlen')),$q);}elseif($kd==$dd){$id=array_keys((array)$cc[$gd]);$me=array_diff($id,$q);$q=array_diff($q,$id);unset($cc[$gd]);}if(preg_match('~^(.+)\\s*(\\(.*\\))?$~U',$gd,$B)&&(!grant("REVOKE",$me,$B[2]," ON $B[1] FROM $dd")||!grant("GRANT",$q,$B[2]," ON $B[1] TO $dd"))){$i=true;break;}}}if(!$i&&isset($_GET["host"])){if($kd!=$dd){queries("DROP USER $kd");}elseif(!isset($_GET["grant"])){foreach($cc
as$gd=>$me){if(preg_match('~^(.+)(\\(.*\\))?$~U',$gd,$B)){grant("REVOKE",array_keys($me),$B[2]," ON $B[1] FROM $dd");}}}}queries_redirect(ME."privileges=",(isset($_GET["host"])?'User has been altered.':'User has been created.'),!$i);if($Za){$e->query("DROP USER $dd");}}}page_header((isset($_GET["host"])?'Username'.": ".h("$fa@$_GET[host]"):'Create user'),$i,array("privileges"=>array('','Privileges')));if($_POST){$K=$_POST;$cc=$cd;}else{$K=$_GET+array("host"=>$e->result("SELECT SUBSTRING_INDEX(CURRENT_USER, '@', -1)"));$K["pass"]=$jd;if($jd!=""){$K["hashed"]=true;}$cc[DB!=""&&!isset($_GET["host"])?idf_escape(addcslashes(DB,"%_")).".*":""]=array();}echo'<form action="" method="post">
<table cellspacing="0">
<tr><th>Server<td><input name="host" maxlength="60" value="',h($K["host"]),'">
<tr><th>Username<td><input name="user" maxlength="16" value="',h($K["user"]),'">
<tr><th>Password<td><input id="pass" name="pass" value="',h($K["pass"]),'">
';if(!$K["hashed"]){echo'<script type="text/javascript">typePassword(document.getElementById(\'pass\'));</script>';}echo
checkbox("hashed",1,$K["hashed"],'Hashed',"typePassword(this.form['pass'], this.checked);"),'</table>

';echo"<table cellspacing='0'>\n","<thead><tr><th colspan='2'><a href='http://dev.mysql.com/doc/refman/".substr($e->server_info,0,3)."/en/grant.html#priv_level' target='_blank' rel='noreferrer'>".'Privileges'."</a>";$s=0;foreach($cc
as$gd=>$q){echo'<th>'.($gd!="*.*"?"<input name='objects[$s]' value='".h($gd)."' size='10'>":"<input type='hidden' name='objects[$s]' value='*.*' size='10'>*.*");$s++;}echo"</thead>\n";foreach(array(""=>"","Server Admin"=>'Server',"Databases"=>'Database',"Tables"=>'Table',"Columns"=>'Column',"Procedures"=>'Routine',)as$Ua=>$jb){foreach((array)$Xd[$Ua]as$Wd=>$Pa){echo"<tr".odd()."><td".($jb?">$jb<td":" colspan='2'").' lang="en" title="'.h($Pa).'">'.h($Wd);$s=0;foreach($cc
as$gd=>$q){$D="'grants[$s][".h(strtoupper($Wd))."]'";$Y=$q[strtoupper($Wd)];if($Ua=="Server Admin"&&$gd!=(isset($cc["*.*"])?"*.*":"")){echo"<td>&nbsp;";}elseif(isset($_GET["grant"])){echo"<td><select name=$D><option><option value='1'".($Y?" selected":"").">".'Grant'."<option value='0'".($Y=="0"?" selected":"").">".'Revoke'."</select>";}else{echo"<td align='center'><input type='checkbox' name=$D value='1'".($Y?" checked":"").($Wd=="All privileges"?" id='grants-$s-all'":($Wd=="Grant option"?"":" onclick=\"if (this.checked) formUncheck('grants-$s-all');\"")).">";}$s++;}}}echo"</table>\n",'<p>
<input type="submit" value="Save">
';if(isset($_GET["host"])){echo'<input type="submit" name="drop" value="Drop"',confirm(),'>';}echo'<input type="hidden" name="token" value="',$U,'">
</form>
';}elseif(isset($_GET["processlist"])){if(support("kill")&&$_POST&&!$i){$yc=0;foreach((array)$_POST["kill"]as$X){if(queries("KILL ".(+$X))){$yc++;}}queries_redirect(ME."processlist=",lang(array('%d process has been killed.','%d processes have been killed.'),$yc),$yc||!$_POST["kill"]);}page_header('Process list',$i);echo'
<form action="" method="post">
<table cellspacing="0" onclick="tableClick(event);" class="nowrap checkable">
';$s=-1;foreach(process_list()as$s=>$K){if(!$s){echo"<thead><tr lang='en'>".(support("kill")?"<th>&nbsp;":"")."<th>".implode("<th>",array_keys($K))."</thead>\n";}echo"<tr".odd().">".(support("kill")?"<td>".checkbox("kill[]",$K["Id"],0):"");foreach($K
as$y=>$X){echo"<td>".(($x=="sql"?$y=="Info"&&$X!="":$y=="current_query"&&$X!="<IDLE>")?"<code class='jush-$x'>".shorten_utf8($X,100,"</code>").' <a href="'.h(ME.($K["db"]!=""?"db=".urlencode($K["db"])."&":"")."sql=".urlencode($X)).'">'.'Edit'.'</a>':nbsp($X));}echo"\n";}echo'</table>
<script type=\'text/javascript\'>tableCheck();</script>
<p>
';if(support("kill")){echo($s+1)."/".sprintf('%d in total',$e->result("SELECT @@max_connections")),"<p><input type='submit' value='".'Kill'."'>\n";}echo'<input type="hidden" name="token" value="',$U,'">
</form>
';}elseif(isset($_GET["select"])){$a=$_GET["select"];$T=table_status($a);$v=indexes($a);$k=fields($a);$m=column_foreign_keys($a);if($T["Oid"]=="t"){$v[]=array("type"=>"PRIMARY","columns"=>array("oid"));}parse_str($_COOKIE["adminer_import"],$la);$ne=array();$d=array();$bf=null;foreach($k
as$y=>$j){$D=$b->fieldName($j);if(isset($j["privileges"]["select"])&&$D!=""){$d[$y]=html_entity_decode(strip_tags($D));if(ereg('text|lob',$j["type"])){$bf=$b->selectLengthProcess();}}$ne+=$j["privileges"];}list($N,$r)=$b->selectColumnsProcess($d,$v);$Z=$b->selectSearchProcess($k,$v);$td=$b->selectOrderProcess($k,$v);$z=$b->selectLimitProcess();$ac=($N?implode(", ",$N):($T["Oid"]=="t"?"oid, ":"")."*")."\nFROM ".table($a);$dc=($r&&count($r)<count($N)?"\nGROUP BY ".implode(", ",$r):"").($td?"\nORDER BY ".implode(", ",$td):"");if($_GET["val"]&&is_ajax()){header("Content-Type: text/plain; charset=utf-8");foreach($_GET["val"]as$wf=>$K){echo$e->result("SELECT".limit(idf_escape(key($K))." FROM ".table($a)," WHERE ".where_check($wf).($Z?" AND ".implode(" AND ",$Z):"").($td?" ORDER BY ".implode(", ",$td):""),1));}exit;}if($_POST&&!$i){$Kf="(".implode(") OR (",array_map('where_check',(array)$_POST["check"])).")";$Td=$yf=null;foreach($v
as$u){if($u["type"]=="PRIMARY"){$Td=array_flip($u["columns"]);$yf=($N?$Td:array());break;}}foreach((array)$yf
as$y=>$X){if(in_array(idf_escape($y),$N)){unset($yf[$y]);}}if($_POST["export"]){cookie("adminer_import","output=".urlencode($_POST["output"])."&format=".urlencode($_POST["format"]));dump_headers($a);$b->dumpTable($a,"");if(!is_array($_POST["check"])||$yf===array()){$Jf=$Z;if(is_array($_POST["check"])){$Jf[]="($Kf)";}$H="SELECT $ac".($Jf?"\nWHERE ".implode(" AND ",$Jf):"").$dc;}else{$uf=array();foreach($_POST["check"]as$X){$uf[]="(SELECT".limit($ac,"\nWHERE ".($Z?implode(" AND ",$Z)." AND ":"").where_check($X).$dc,1).")";}$H=implode(" UNION ALL ",$uf);}$b->dumpData($a,"table",$H);exit;}if(!$b->selectEmailProcess($Z,$m)){if($_POST["save"]||$_POST["delete"]){$I=true;$ma=0;$H=table($a);$P=array();if(!$_POST["delete"]){foreach($d
as$D=>$X){$X=process_input($k[$D]);if($X!==null){if($_POST["clone"]){$P[idf_escape($D)]=($X!==false?$X:idf_escape($D));}elseif($X!==false){$P[]=idf_escape($D)." = $X";}}}$H.=($_POST["clone"]?" (".implode(", ",array_keys($P)).")\nSELECT ".implode(", ",$P)."\nFROM ".table($a):" SET\n".implode(",\n",$P));}if($_POST["delete"]||$P){$Na="UPDATE";if($_POST["delete"]){$Na="DELETE";$H="FROM $H";}if($_POST["clone"]){$Na="INSERT";$H="INTO $H";}if($_POST["all"]||($yf===array()&&$_POST["check"])||count($r)<count($N)){$I=queries($Na." $H".($_POST["all"]?($Z?"\nWHERE ".implode(" AND ",$Z):""):"\nWHERE $Kf"));$ma=$e->affected_rows;}else{foreach((array)$_POST["check"]as$X){$I=queries($Na.limit1($H,"\nWHERE ".where_check($X)));if(!$I){break;}$ma+=$e->affected_rows;}}}queries_redirect(remove_from_uri("page"),lang(array('%d item has been affected.','%d items have been affected.'),$ma),$I);}elseif(!$_POST["import"]){if(!$_POST["val"]){$i='Double click on a value to modify it.';}else{$I=true;$ma=0;foreach($_POST["val"]as$wf=>$K){$P=array();foreach($K
as$y=>$X){$y=bracket_escape($y,1);$P[]=idf_escape($y)." = ".(ereg('char|text',$k[$y]["type"])||$X!=""?$b->processInput($k[$y],$X):"NULL");}$H=table($a)." SET ".implode(", ",$P);$Jf=" WHERE ".where_check($wf).($Z?" AND ".implode(" AND ",$Z):"");$I=queries("UPDATE".(count($r)<count($N)?" $H$Jf":limit1($H,$Jf)));if(!$I){break;}$ma+=$e->affected_rows;}queries_redirect(remove_from_uri(),lang(array('%d item has been affected.','%d items have been affected.'),$ma),$I);}}elseif(is_string($Rb=get_file("csv_file",true))){cookie("adminer_import","output=".urlencode($la["output"])."&format=".urlencode($_POST["separator"]));$I=true;$La=array_keys($k);preg_match_all('~(?>"[^"]*"|[^"\\r\\n]+)+~',$Rb,$Lc);$ma=count($Lc[0]);begin();$ye=($_POST["separator"]=="csv"?",":($_POST["separator"]=="tsv"?"\t":";"));foreach($Lc[0]as$y=>$X){preg_match_all("~((\"[^\"]*\")+|[^$ye]*)$ye~",$X.$ye,$Mc);if(!$y&&!array_diff($Mc[1],$La)){$La=$Mc[1];$ma--;}else{$P=array();foreach($Mc[1]as$s=>$Ia){$P[idf_escape($La[$s])]=($Ia==""&&$k[$La[$s]]["null"]?"NULL":q(str_replace('""','"',preg_replace('~^"|"$~','',$Ia))));}$I=insert_update($a,$P,$Td);if(!$I){break;}}}if($I){queries("COMMIT");}queries_redirect(remove_from_uri("page"),lang(array('%d row has been imported.','%d rows have been imported.'),$ma),$I);queries("ROLLBACK");}else{$i=upload_error($Rb);}}}$Re=$b->tableName($T);page_header('Select'.": $Re",$i);session_write_close();$P=null;if(isset($ne["insert"])){$P="";foreach((array)$_GET["where"]as$X){if(count($m[$X["col"]])==1&&($X["op"]=="="||(!$X["op"]&&!ereg('[_%]',$X["val"])))){$P.="&set".urlencode("[".bracket_escape($X["col"])."]")."=".urlencode($X["val"]);}}}$b->selectLinks($T,$P);if(!$d){echo"<p class='error'>".'Unable to select the table'.($k?".":": ".error())."\n";}else{echo"<form action='' id='form'>\n","<div style='display: none;'>";hidden_fields_get();echo(DB!=""?'<input type="hidden" name="db" value="'.h(DB).'">'.(isset($_GET["ns"])?'<input type="hidden" name="ns" value="'.h($_GET["ns"]).'">':""):"");echo'<input type="hidden" name="select" value="'.h($a).'">',"</div>\n";$b->selectColumnsPrint($N,$d);$b->selectSearchPrint($Z,$d,$v);$b->selectOrderPrint($td,$d,$v);$b->selectLimitPrint($z);$b->selectLengthPrint($bf);$b->selectActionPrint();echo"</form>\n";$E=$_GET["page"];if($E=="last"){$Yb=$e->result("SELECT COUNT(*) FROM ".table($a).($Z?" WHERE ".implode(" AND ",$Z):""));$E=floor(max(0,$Yb-1)/$z);}$H="SELECT".limit((+$z&&$r&&count($r)<count($N)&&$x=="sql"?"SQL_CALC_FOUND_ROWS ":"").$ac,($Z?"\nWHERE ".implode(" AND ",$Z):"").$dc,($z!=""?+$z:null),($E?$z*$E:0),"\n");echo$b->selectQuery($H);$I=$e->query($H);if(!$I){echo"<p class='error'>".error()."\n";}else{if($x=="mssql"){$I->seek($z*$E);}$yb=array();echo"<form action='' method='post' enctype='multipart/form-data'>\n";$L=array();while($K=$I->fetch_assoc()){if($E&&$x=="oracle"){unset($K["RNUM"]);}$L[]=$K;}if($_GET["page"]!="last"){$Yb=(+$z&&$r&&count($r)<count($N)?($x=="sql"?$e->result(" SELECT FOUND_ROWS()"):$e->result("SELECT COUNT(*) FROM ($H) x")):count($L));}if(!$L){echo"<p class='message'>".'No rows.'."\n";}else{$ya=$b->backwardKeys($a,$Re);echo"<table cellspacing='0' class='nowrap checkable' onclick='tableClick(event);' onkeydown='return editingKeydown(event);'>\n","<thead><tr>".(!$r&&$N?"":"<td><input type='checkbox' id='all-page' onclick='formCheck(this, /check/);'> <a href='".h($_GET["modify"]?remove_from_uri("modify"):$_SERVER["REQUEST_URI"]."&modify=1")."'>".'edit'."</a>");$bd=array();$p=array();reset($N);$ce=1;foreach($L[0]as$y=>$X){if($T["Oid"]!="t"||$y!="oid"){$X=$_GET["columns"][key($N)];$j=$k[$N?($X?$X["col"]:current($N)):$y];$D=($j?$b->fieldName($j,$ce):"*");if($D!=""){$ce++;$bd[$y]=$D;$Ma=idf_escape($y);echo'<th><a href="'.h(remove_from_uri('(order|desc)[^=]*|page').'&order%5B0%5D='.urlencode($y).($td[0]==$Ma||$td[0]==$y||(!$td&&count($r)<count($N)&&$r[0]==$Ma)?'&desc%5B0%5D=1':'')).'">'.(!$N||$X?apply_sql_function($X["fun"],$D):h(current($N)))."</a>";}$p[$y]=$X["fun"];next($N);}}$Hc=array();if($_GET["modify"]){foreach($L
as$K){foreach($K
as$y=>$X){$Hc[$y]=max($Hc[$y],min(40,strlen(utf8_decode($X))));}}}echo($ya?"<th>".'Relations':"")."</thead>\n";foreach($b->rowDescriptions($L,$m)as$C=>$K){$vf=unique_array($L[$C],$v);$wf="";foreach($vf
as$y=>$X){$wf.="&".(isset($X)?urlencode("where[".bracket_escape($y)."]")."=".urlencode($X):"null%5B%5D=".urlencode($y));}echo"<tr".odd().">".(!$r&&$N?"":"<td>".checkbox("check[]",substr($wf,1),in_array(substr($wf,1),(array)$_POST["check"]),"","this.form['all'].checked = false; formUncheck('all-page');").(count($r)<count($N)||information_schema(DB)?"":" <a href='".h(ME."edit=".urlencode($a).$wf)."'>".'edit'."</a>"));foreach($K
as$y=>$X){if(isset($bd[$y])){$j=$k[$y];if($X!=""&&(!isset($yb[$y])||$yb[$y]!="")){$yb[$y]=(is_mail($X)?$bd[$y]:"");}$_="";$X=$b->editVal($X,$j);if(!isset($X)){$X="<i>NULL</i>";}else{if(ereg('blob|bytea|raw|file',$j["type"])&&$X!=""){$_=h(ME.'download='.urlencode($a).'&field='.urlencode($y).$wf);}if($X===""){$X="&nbsp;";}elseif($bf!=""&&ereg('text|blob',$j["type"])&&is_utf8($X)){$X=shorten_utf8($X,max(0,+$bf));}else{$X=h($X);}if(!$_){foreach((array)$m[$y]as$l){if(count($m[$y])==1||end($l["source"])==$y){$_="";foreach($l["source"]as$s=>$Be){$_.=where_link($s,$l["target"][$s],$L[$C][$Be]);}$_=h(($l["db"]!=""?preg_replace('~([?&]db=)[^&]+~','\\1'.urlencode($l["db"]),ME):ME).'select='.urlencode($l["table"]).$_);if(count($l["source"])==1){break;}}}}if($y=="COUNT(*)"){$_=h(ME."select=".urlencode($a));$s=0;foreach((array)$_GET["where"]as$W){if(!array_key_exists($W["col"],$vf)){$_.=h(where_link($s++,$W["col"],$W["val"],$W["op"]));}}foreach($vf
as$xc=>$W){$_.=h(where_link($s++,$xc,$W));}}}if(!$_){if(is_mail($X)){$_="mailto:$X";}if($ae=is_url($K[$y])){$_=($ae=="http"&&$ba?$K[$y]:"$ae://www.adminer.org/redirect/?url=".urlencode($K[$y]));}}$t=h("val[$wf][".bracket_escape($y)."]");$Y=$_POST["val"][$wf][bracket_escape($y)];$fc=h(isset($Y)?$Y:$K[$y]);$Kc=strpos($X,"<i>...</i>");$vb=is_utf8($X)&&$L[$C][$y]==$K[$y]&&!$p[$y];$af=ereg('text|lob',$j["type"]);echo(($_GET["modify"]&&$vb)||isset($Y)?"<td>".($af?"<textarea name='$t' cols='30' rows='".(substr_count($K[$y],"\n")+1)."'>$fc</textarea>":"<input name='$t' value='$fc' size='$Hc[$y]'>"):"<td id='$t' ondblclick=\"".($vb?"selectDblClick(this, event".($Kc?", 2":($af?", 1":"")).")":"alert('".h('Use edit link to modify this value.')."')").";\">".$b->selectVal($X,$_,$j));}}if($ya){echo"<td>";}$b->backwardKeysPrint($ya,$L[$C]);echo"</tr>\n";}echo"</table>\n",(!$r&&$N?"":"<script type='text/javascript'>tableCheck();</script>\n");}if($L||$E){$Gb=true;if($_GET["page"]!="last"&&+$z&&count($r)>=count($N)&&($Yb>=$z||$E)){$Yb=found_rows($T,$Z);if($Yb<max(1e4,2*($E+1)*$z)){ob_flush();flush();$Yb=$e->result("SELECT COUNT(*) FROM ".table($a).($Z?" WHERE ".implode(" AND ",$Z):""));}else{$Gb=false;}}echo"<p class='pages'>";if(+$z&&$Yb>$z){$Oc=floor(($Yb-1)/$z);echo'<a href="'.h(remove_from_uri("page"))."\" onclick=\"pageClick(this.href, +prompt('".'Page'."', '".($E+1)."'), event); return false;\">".'Page'."</a>:",pagination(0,$E).($E>5?" ...":"");for($s=max(1,$E-4);$s<min($Oc,$E+5);$s++){echo
pagination($s,$E);}echo($E+5<$Oc?" ...":"").($Gb?pagination($Oc,$E):' <a href="'.h(remove_from_uri()."&page=last").'">'.'last'."</a>");}echo" (".($Gb?"":"~ ").lang(array('%d row','%d rows'),$Yb).") ".checkbox("all",1,0,'whole result')."\n";if($b->selectCommandPrint()){echo'<fieldset><legend>Edit</legend><div>
<input type="submit" value="Save"',($_GET["modify"]?'':' title="'.'Double click on a value to modify it.'.'" class="jsonly"');?>>
<input type="submit" name="edit" value="Edit">
<input type="submit" name="clone" value="Clone">
<input type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure? (' + (this.form['all'].checked ? <?php echo$Yb,' : formChecked(this, /check/)) + \')\');">
</div></fieldset>
';}print_fieldset("export",'Export');$Ad=$b->dumpOutput();echo($Ad?html_select("output",$Ad,$la["output"])." ":""),html_select("format",$b->dumpFormat(),$la["format"])," <input type='submit' name='export' value='".'Export'."' onclick='eventStop(event);'>\n","</div></fieldset>\n";}if($b->selectImportPrint()){print_fieldset("import",'Import',!$L);echo"<input type='file' name='csv_file'> ",html_select("separator",array("csv"=>"CSV,","csv;"=>"CSV;","tsv"=>"TSV"),$la["format"],1);echo" <input type='submit' name='import' value='".'Import'."'>","<input type='hidden' name='token' value='$U'>\n","</div></fieldset>\n";}$b->selectEmailPrint(array_filter($yb,'strlen'),$d);echo"</form>\n";}}}elseif(isset($_GET["variables"])){$Fe=isset($_GET["status"]);page_header($Fe?'Status':'Variables');$Ef=($Fe?show_status():show_variables());if(!$Ef){echo"<p class='message'>".'No rows.'."\n";}else{echo"<table cellspacing='0'>\n";foreach($Ef
as$y=>$X){echo"<tr>","<th><code class='jush-".$x.($Fe?"status":"set")."'>".h($y)."</code>","<td>".nbsp($X);}echo"</table>\n";}}elseif(isset($_GET["script"])){header("Content-Type: text/javascript; charset=utf-8");if($_GET["script"]=="db"){$Oe=array("Data_length"=>0,"Index_length"=>0,"Data_free"=>0);foreach(table_status()as$T){$t=js_escape($T["Name"]);json_row("Comment-$t",nbsp($T["Comment"]));if(!is_view($T)){foreach(array("Engine","Collation")as$y){json_row("$y-$t",nbsp($T[$y]));}foreach($Oe+array("Auto_increment"=>0,"Rows"=>0)as$y=>$X){if($T[$y]!=""){$X=number_format($T[$y],0,'.',',');json_row("$y-$t",($y=="Rows"&&$T["Engine"]=="InnoDB"&&$X?"~ $X":$X));if(isset($Oe[$y])){$Oe[$y]+=($T["Engine"]!="InnoDB"||$y!="Data_free"?$T[$y]:0);}}elseif(array_key_exists($y,$T)){json_row("$y-$t");}}}}foreach($Oe
as$y=>$X){json_row("sum-$y",number_format($X,0,'.',','));}json_row("");}else{foreach(count_tables(get_databases())as$h=>$X){json_row("tables-".js_escape($h),$X);}json_row("");}exit;}else{$Xe=array_merge((array)$_POST["tables"],(array)$_POST["views"]);if($Xe&&!$i&&!$_POST["search"]){$I=true;$Sc="";if($x=="sql"&&count($_POST["tables"])>1&&($_POST["drop"]||$_POST["truncate"]||$_POST["copy"])){queries("SET foreign_key_checks = 0");}if($_POST["truncate"]){if($_POST["tables"]){$I=truncate_tables($_POST["tables"]);}$Sc='Tables have been truncated.';}elseif($_POST["move"]){$I=move_tables((array)$_POST["tables"],(array)$_POST["views"],$_POST["target"]);$Sc='Tables have been moved.';}elseif($_POST["copy"]){$I=copy_tables((array)$_POST["tables"],(array)$_POST["views"],$_POST["target"]);$Sc='Tables have been copied.';}elseif($_POST["drop"]){if($_POST["views"]){$I=drop_views($_POST["views"]);}if($I&&$_POST["tables"]){$I=drop_tables($_POST["tables"]);}$Sc='Tables have been dropped.';}elseif($_POST["tables"]&&($I=queries(($_POST["optimize"]?"OPTIMIZE":($_POST["check"]?"CHECK":($_POST["repair"]?"REPAIR":"ANALYZE")))." TABLE ".implode(", ",array_map('idf_escape',$_POST["tables"]))))){while($K=$I->fetch_assoc()){$Sc.="<b>".h($K["Table"])."</b>: ".h($K["Msg_text"])."<br>";}}queries_redirect(substr(ME,0,-1),$Sc,$I);}page_header(($_GET["ns"]==""?'Database'.": ".h(DB):'Schema'.": ".h($_GET["ns"])),$i,true);if($b->homepage()){if($_GET["ns"]!==""){echo"<h3>".'Tables and views'."</h3>\n";$We=tables_list();if(!$We){echo"<p class='message'>".'No tables.'."\n";}else{echo"<form action='' method='post'>\n","<p>".'Search data in tables'.": <input name='query' value='".h($_POST["query"])."'> <input type='submit' name='search' value='".'Search'."'>\n";if($_POST["search"]&&$_POST["query"]!=""){search_tables();}echo"<table cellspacing='0' class='nowrap checkable' onclick='tableClick(event);'>\n",'<thead><tr class="wrap"><td><input id="check-all" type="checkbox" onclick="formCheck(this, /^(tables|views)\[/);">','<th>'.'Table','<td>'.'Engine','<td>'.'Collation','<td>'.'Data Length','<td>'.'Index Length','<td>'.'Data Free','<td>'.'Auto Increment','<td>'.'Rows',(support("comment")?'<td>'.'Comment':''),"</thead>\n";foreach($We
as$D=>$V){$Ff=(isset($V)&&!eregi("table",$V));echo'<tr'.odd().'><td>'.checkbox(($Ff?"views[]":"tables[]"),$D,in_array($D,$Xe,true),"","formUncheck('check-all');"),'<th><a href="'.h(ME).'table='.urlencode($D).'" title="'.'Show structure'.'">'.h($D).'</a>';if($Ff){echo'<td colspan="6"><a href="'.h(ME)."view=".urlencode($D).'" title="'.'Alter view'.'">'.'View'.'</a>','<td align="right"><a href="'.h(ME)."select=".urlencode($D).'" title="'.'Select data'.'">?</a>';}else{foreach(array("Engine"=>array(),"Collation"=>array(),"Data_length"=>array("create",'Alter table'),"Index_length"=>array("indexes",'Alter indexes'),"Data_free"=>array("edit",'New item'),"Auto_increment"=>array("auto_increment=1&create",'Alter table'),"Rows"=>array("select",'Select data'),)as$y=>$_){echo($_?"<td align='right'><a href='".h(ME."$_[0]=").urlencode($D)."' id='$y-".h($D)."' title='$_[1]'>?</a>":"<td id='$y-".h($D)."'>&nbsp;");}}echo(support("comment")?"<td id='Comment-".h($D)."'>&nbsp;":"");}echo"<tr><td>&nbsp;<th>".sprintf('%d in total',count($We)),"<td>".nbsp($x=="sql"?$e->result("SELECT @@storage_engine"):""),"<td>".nbsp(db_collation(DB,collations()));foreach(array("Data_length","Index_length","Data_free")as$y){echo"<td align='right' id='sum-$y'>&nbsp;";}echo"</table>\n","<script type='text/javascript'>tableCheck();</script>\n";if(!information_schema(DB)){echo"<p>".($x=="sql"?"<input type='submit' value='".'Analyze'."'> <input type='submit' name='optimize' value='".'Optimize'."'> <input type='submit' name='check' value='".'Check'."'> <input type='submit' name='repair' value='".'Repair'."'> ":"")."<input type='submit' name='truncate' value='".'Truncate'."'".confirm("formChecked(this, /tables/)")."> <input type='submit' name='drop' value='".'Drop'."'".confirm("formChecked(this, /tables|views/)",1).">\n";$g=(support("scheme")?schemas():get_databases());if(count($g)!=1&&$x!="sqlite"){$h=(isset($_POST["target"])?$_POST["target"]:(support("scheme")?$_GET["ns"]:DB));echo"<p>".'Move to other database'.": ",($g?html_select("target",$g,$h):'<input name="target" value="'.h($h).'">')," <input type='submit' name='move' value='".'Move'."' onclick='eventStop(event);'>",(support("copy")?" <input type='submit' name='copy' value='".'Copy'."' onclick='eventStop(event);'>":""),"\n";}echo"<input type='hidden' name='token' value='$U'>\n";}echo"</form>\n";}echo'<p><a href="'.h(ME).'create=">'.'Create table'."</a>\n";if(support("view")){echo'<a href="'.h(ME).'view=">'.'Create view'."</a>\n";}if(support("routine")){echo"<h3>".'Routines'."</h3>\n";$re=routines();if($re){echo"<table cellspacing='0'>\n",'<thead><tr><th>'.'Name'.'<td>'.'Type'.'<td>'.'Return type'."<td>&nbsp;</thead>\n";odd('');foreach($re
as$K){echo'<tr'.odd().'>','<th><a href="'.h(ME).($K["ROUTINE_TYPE"]!="PROCEDURE"?'callf=':'call=').urlencode($K["ROUTINE_NAME"]).'">'.h($K["ROUTINE_NAME"]).'</a>','<td>'.h($K["ROUTINE_TYPE"]),'<td>'.h($K["DTD_IDENTIFIER"]),'<td><a href="'.h(ME).($K["ROUTINE_TYPE"]!="PROCEDURE"?'function=':'procedure=').urlencode($K["ROUTINE_NAME"]).'">'.'Alter'."</a>";}echo"</table>\n";}echo'<p>'.(support("procedure")?'<a href="'.h(ME).'procedure=">'.'Create procedure'.'</a> ':'').'<a href="'.h(ME).'function=">'.'Create function'."</a>\n";}if(support("event")){echo"<h3>".'Events'."</h3>\n";$L=get_rows("SHOW EVENTS");if($L){echo"<table cellspacing='0'>\n","<thead><tr><th>".'Name'."<td>".'Schedule'."<td>".'Start'."<td>".'End'."</thead>\n";foreach($L
as$K){echo"<tr>",'<th><a href="'.h(ME).'event='.urlencode($K["Name"]).'">'.h($K["Name"])."</a>","<td>".($K["Execute at"]?'At given time'."<td>".$K["Execute at"]:'Every'." ".$K["Interval value"]." ".$K["Interval field"]."<td>$K[Starts]"),"<td>$K[Ends]";}echo"</table>\n";}echo'<p><a href="'.h(ME).'event=">'.'Create event'."</a>\n";}if($We){echo"<script type='text/javascript'>ajaxSetHtml('".js_escape(ME)."script=db');</script>\n";}}}}page_footer();