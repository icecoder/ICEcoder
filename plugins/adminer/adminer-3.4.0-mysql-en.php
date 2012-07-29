<?php
/** Adminer - Compact database management
* @link http://www.adminer.org/
* @author Jakub Vrana, http://www.vrana.cz/
* @copyright 2007 Jakub Vrana
* @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
* @version 3.4.0
*/error_reporting(6135);$Wb=!ereg('^(unsafe_raw)?$',ini_get("filter.default"));if($Wb||ini_get("filter.default_flags")){foreach(array('_GET','_POST','_COOKIE','_SERVER')as$X){$Cf=filter_input_array(constant("INPUT$X"),FILTER_UNSAFE_RAW);if($Cf)$$X=$Cf;}}if(isset($_GET["file"])){header("Expires: ".gmdate("D, d M Y H:i:s",time()+365*24*60*60)." GMT");if($_GET["file"]=="favicon.ico"){header("Content-Type: image/x-icon");echo
base64_decode("AAABAAEAEBAQAAEABAAoAQAAFgAAACgAAAAQAAAAIAAAAAEABAAAAAAAwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA////AAAA/wBhTgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAERERAAAAAAETMzEQAAAAATERExAAAAABMRETEAAAAAExERMQAAAAATERExAAAAABMRETEAAAAAEzMzMREREQATERExEhEhABEzMxEhEREAAREREhERIRAAAAARIRESEAAAAAESEiEQAAAAABEREQAAAAAAAAAAD//9UAwP/VAIB/AACAf/AAgH+kAIB/gACAfwAAgH8AAIABAACAAf8AgAH/AMAA/wD+AP8A/wAIAf+B1QD//9UA");}elseif($_GET["file"]=="default.css"){header("Content-Type: text/css; charset=utf-8");echo'body{color:#000;background:#fff;font:90%/1.25 Verdana,Arial,Helvetica,sans-serif;margin:0;}a{color:blue;}a:visited{color:navy;}a:hover{color:red;}a.text{text-decoration:none;}h1{font-size:150%;margin:0;padding:.8em 1em;border-bottom:1px solid #999;font-weight:normal;color:#777;background:#eee;}h2{font-size:150%;margin:0 0 20px -18px;padding:.8em 1em;border-bottom:1px solid #000;color:#000;font-weight:normal;background:#ddf;}h3{font-weight:normal;font-size:130%;margin:1em 0 0;}form{margin:0;}table{margin:1em 20px 0 0;border:0;border-top:1px solid #999;border-left:1px solid #999;font-size:90%;}td,th{border:0;border-right:1px solid #999;border-bottom:1px solid #999;padding:.2em .3em;}th{background:#eee;text-align:left;}thead th{text-align:center;}thead td,thead th{background:#ddf;}fieldset{display:inline;vertical-align:top;padding:.5em .8em;margin:.8em .5em 0 0;border:1px solid #999;}p{margin:.8em 20px 0 0;}img{vertical-align:middle;border:0;}td img{max-width:200px;max-height:200px;}code{background:#eee;}tbody tr:hover td,tbody tr:hover th{background:#eee;}pre{margin:1em 0 0;}input[type=image]{vertical-align:middle;}.version{color:#777;font-size:67%;}.js .hidden,.nojs .jsonly{display:none;}.nowrap td,.nowrap th,td.nowrap{white-space:pre;}.wrap td{white-space:normal;}.error{color:red;background:#fee;}.error b{background:#fff;font-weight:normal;}.message{color:green;background:#efe;}.error,.message{padding:.5em .8em;margin:1em 20px 0 0;}.char{color:#007F00;}.date{color:#7F007F;}.enum{color:#007F7F;}.binary{color:red;}.odd td{background:#F5F5F5;}.js .checked td,.js .checked th{background:#ddf;}.time{color:silver;font-size:70%;}.function{text-align:right;}.number{text-align:right;}.datetime{text-align:right;}.type{width:15ex;width:auto\\9;}.options select{width:20ex;width:auto\\9;}.active{font-weight:bold;}.sqlarea{width:98%;}#menu{position:absolute;margin:10px 0 0;padding:0 0 30px 0;top:2em;left:0;width:19em;overflow:auto;overflow-y:hidden;white-space:nowrap;}#menu p{padding:.8em 1em;margin:0;border-bottom:1px solid #ccc;}#content{margin:2em 0 0 21em;padding:10px 20px 20px 0;}#lang{position:absolute;top:0;left:0;line-height:1.8em;padding:.3em 1em;}#breadcrumb{white-space:nowrap;position:absolute;top:0;left:21em;background:#eee;height:2em;line-height:1.8em;padding:0 1em;margin:0 0 0 -18px;}#h1{color:#777;text-decoration:none;font-style:italic;}#version{font-size:67%;color:red;}#schema{margin-left:60px;position:relative;-moz-user-select:none;-webkit-user-select:none;}#schema .table{border:1px solid silver;padding:0 2px;cursor:move;position:absolute;}#schema .references{position:absolute;}.rtl h2{margin:0 -18px 20px 0;}.rtl p,.rtl table,.rtl .error,.rtl .message{margin:1em 0 0 20px;}.rtl #content{margin:2em 21em 0 0;padding:10px 0 20px 20px;}.rtl #breadcrumb{left:auto;right:21em;margin:0 -18px 0 0;}.rtl #lang,.rtl #menu{left:auto;right:0;}@media print{#lang,#menu{display:none;}#content{margin-left:1em;}#breadcrumb{left:1em;}.nowrap td,.nowrap th,td.nowrap{white-space:normal;}}';}elseif($_GET["file"]=="functions.js"){header("Content-Type: text/javascript; charset=utf-8");?>function
toggle(id){var
el=document.getElementById(id);el.className=(el.className=='hidden'?'':'hidden');return true;}function
cookie(assign,days){var
date=new
Date();date.setDate(date.getDate()+days);document.cookie=assign+'; expires='+date;}function
verifyVersion(){cookie('adminer_version=0',1);var
script=document.createElement('script');script.src=location.protocol+'//www.adminer.org/version.php';document.body.appendChild(script);}function
selectValue(select){var
selected=select.options[select.selectedIndex];return((selected.attributes.value||{}).specified?selected.value:selected.text);}function
trCheck(el){var
tr=el.parentNode.parentNode;tr.className=tr.className.replace(/(^|\s)checked(\s|$)/,'$2')+(el.checked?' checked':'');}function
formCheck(el,name){var
elems=el.form.elements;for(var
i=0;i<elems.length;i++){if(name.test(elems[i].name)){elems[i].checked=el.checked;trCheck(elems[i]);}}}function
tableCheck(){var
tables=document.getElementsByTagName('table');for(var
i=0;i<tables.length;i++){if(/(^|\s)checkable(\s|$)/.test(tables[i].className)){var
trs=tables[i].getElementsByTagName('tr');for(var
j=0;j<trs.length;j++){trCheck(trs[j].firstChild.firstChild);}}}}function
formUncheck(id){var
el=document.getElementById(id);el.checked=false;trCheck(el);}function
formChecked(el,name){var
checked=0;var
elems=el.form.elements;for(var
i=0;i<elems.length;i++){if(name.test(elems[i].name)&&elems[i].checked){checked++;}}return checked;}function
tableClick(event){var
click=(!window.getSelection||getSelection().isCollapsed);var
el=event.target||event.srcElement;while(!/^tr$/i.test(el.tagName)){if(/^(table|a|input|textarea)$/i.test(el.tagName)){if(el.type!='checkbox'){return;}checkboxClick(event,el);click=false;}el=el.parentNode;}el=el.firstChild.firstChild;if(click){el.click&&el.click();el.onclick&&el.onclick();}trCheck(el);}var
lastChecked;function
checkboxClick(event,el){if(!el.name){return;}if(event.shiftKey&&(!lastChecked||lastChecked.name==el.name)){var
checked=(lastChecked?lastChecked.checked:true);var
inputs=el.parentNode.parentNode.parentNode.getElementsByTagName('input');var
checking=!lastChecked;for(var
i=0;i<inputs.length;i++){var
input=inputs[i];if(input.name===el.name){if(checking){input.checked=checked;trCheck(input);}if(input===el||input===lastChecked){if(checking){break;}checking=true;}}}}lastChecked=el;}function
setHtml(id,html){var
el=document.getElementById(id);if(el){if(html==undefined){el.parentNode.innerHTML='&nbsp;';}else{el.innerHTML=html;}}}function
nodePosition(el){var
pos=0;while(el=el.previousSibling){pos++;}return pos;}function
pageClick(href,page,event){if(!isNaN(page)&&page){href+=(page!=1?'&page='+(page-1):'');location.href=href;}}function
selectAddRow(field){field.onchange=function(){selectFieldChange(field.form);};field.onchange();var
row=field.parentNode.cloneNode(true);var
selects=row.getElementsByTagName('select');for(var
i=0;i<selects.length;i++){selects[i].name=selects[i].name.replace(/[a-z]\[\d+/,'$&1');selects[i].selectedIndex=0;}var
inputs=row.getElementsByTagName('input');if(inputs.length){inputs[0].name=inputs[0].name.replace(/[a-z]\[\d+/,'$&1');inputs[0].value='';inputs[0].className='';}field.parentNode.parentNode.appendChild(row);}function
selectFieldChange(form){var
ok=(function(){var
inputs=form.getElementsByTagName('input');for(var
i=0;i<inputs.length;i++){var
input=inputs[i];if(/^fulltext/.test(input.name)&&input.value){return true;}}var
ok=true;var
selects=form.getElementsByTagName('select');for(var
i=0;i<selects.length;i++){var
select=selects[i];var
col=selectValue(select);var
match=/^(where.+)col\]/.exec(select.name);if(match){var
op=selectValue(form[match[1]+'op]']);var
val=form[match[1]+'val]'].value;if(col
in
indexColumns&&(!/LIKE|REGEXP/.test(op)||(op=='LIKE'&&val.charAt(0)!='%'))){return true;}else
if(col||val){ok=false;}}if(col&&/^order/.test(select.name)){if(!(col
in
indexColumns)){ok=false;}break;}}return ok;})();setHtml('noindex',(ok?'':'!'));}function
bodyKeydown(event,button){var
target=event.target||event.srcElement;if(event.ctrlKey&&(event.keyCode==13||event.keyCode==10)&&!event.altKey&&!event.metaKey&&/select|textarea|input/i.test(target.tagName)){target.blur();if(button){target.form[button].click();}else{target.form.submit();}return false;}return true;}function
editingKeydown(event){if((event.keyCode==40||event.keyCode==38)&&event.ctrlKey&&!event.altKey&&!event.metaKey){var
target=event.target||event.srcElement;var
sibling=(event.keyCode==40?'nextSibling':'previousSibling');var
el=target.parentNode.parentNode[sibling];if(el&&(/^tr$/i.test(el.tagName)||(el=el[sibling]))&&/^tr$/i.test(el.tagName)&&(el=el.childNodes[nodePosition(target.parentNode)])&&(el=el.childNodes[nodePosition(target)])){el.focus();}return false;}if(event.shiftKey&&!bodyKeydown(event,'insert')){eventStop(event);return false;}return true;}function
functionChange(select){var
input=select.form[select.name.replace(/^function/,'fields')];if(selectValue(select)){if(input.origMaxLength===undefined){input.origMaxLength=input.maxLength;}input.removeAttribute('maxlength');}else
if(input.origMaxLength>=0){input.maxLength=input.origMaxLength;}}function
ajax(url,callback,data){var
request=(window.XMLHttpRequest?new
XMLHttpRequest():(window.ActiveXObject?new
ActiveXObject('Microsoft.XMLHTTP'):false));if(request){request.open((data?'POST':'GET'),url);if(data){request.setRequestHeader('Content-Type','application/x-www-form-urlencoded');}request.setRequestHeader('X-Requested-With','XMLHttpRequest');request.onreadystatechange=function(){if(request.readyState==4){callback(request);}};request.send(data);}return request;}function
ajaxSetHtml(url){return ajax(url,function(request){if(request.status){var
data=eval('('+request.responseText+')');for(var
key
in
data){setHtml(key,data[key]);}}});}function
selectDblClick(td,event,text){if(/input|textarea/i.test(td.firstChild.tagName)){return;}var
original=td.innerHTML;var
input=document.createElement(text?'textarea':'input');input.onkeydown=function(event){if(!event){event=window.event;}if(event.keyCode==27&&!(event.ctrlKey||event.shiftKey||event.altKey||event.metaKey)){td.innerHTML=original;}};var
pos=event.rangeOffset;var
value=td.firstChild.alt||td.textContent||td.innerText;input.style.width=Math.max(td.clientWidth-14,20)+'px';if(text){var
rows=1;value.replace(/\n/g,function(){rows++;});input.rows=rows;}if(value=='\u00A0'||td.getElementsByTagName('i').length){value='';}if(document.selection){var
range=document.selection.createRange();range.moveToPoint(event.clientX,event.clientY);var
range2=range.duplicate();range2.moveToElementText(td);range2.setEndPoint('EndToEnd',range);pos=range2.text.length;}td.innerHTML='';td.appendChild(input);input.focus();if(text==2){return ajax(location.href+'&'+encodeURIComponent(td.id)+'=',function(request){if(request.status){input.value=request.responseText;input.name=td.id;}});}input.value=value;input.name=td.id;input.selectionStart=pos;input.selectionEnd=pos;if(document.selection){var
range=document.selection.createRange();range.moveEnd('character',-input.value.length+pos);range.select();}}function
eventStop(event){if(event.stopPropagation){event.stopPropagation();}else{event.cancelBubble=true;}}var
jushRoot=location.protocol + '//www.adminer.org/static/';function
bodyLoad(version){if(jushRoot){var
link=document.createElement('link');link.rel='stylesheet';link.type='text/css';link.href=jushRoot+'jush.css';document.getElementsByTagName('head')[0].appendChild(link);var
script=document.createElement('script');script.src=jushRoot+'jush.js';script.onload=function(){if(window.jush){jush.create_links=' target="_blank" rel="noreferrer"';jush.urls.sql_sqlset=jush.urls.sql[0]=jush.urls.sqlset[0]=jush.urls.sqlstatus[0]='http://dev.mysql.com/doc/refman/'+version+'/en/$key';var
pgsql='http://www.postgresql.org/docs/'+version+'/static/';jush.urls.pgsql_pgsqlset=jush.urls.pgsql[0]=pgsql+'$key';jush.urls.pgsqlset[0]=pgsql+'runtime-config-$key.html#GUC-$1';if(window.jushLinks){jush.custom_links=jushLinks;}jush.highlight_tag('code',0);}};script.onreadystatechange=function(){if(/^(loaded|complete)$/.test(script.readyState)){script.onload();}};document.body.appendChild(script);}}function
formField(form,name){for(var
i=0;i<form.length;i++){if(form[i].name==name){return form[i];}}}function
typePassword(el,disable){try{el.type=(disable?'text':'password');}catch(e){}}function
loginDriver(driver){var
trs=driver.parentNode.parentNode.parentNode.rows;for(var
i=1;i<trs.length-1;i++){trs[i].className=(/sqlite/.test(driver.value)?'hidden':'');}}function
textareaKeydown(target,event){if(!event.shiftKey&&!event.altKey&&!event.ctrlKey&&!event.metaKey){if(event.keyCode==9){if(target.setSelectionRange){var
start=target.selectionStart;var
scrolled=target.scrollTop;target.value=target.value.substr(0,start)+'\t'+target.value.substr(target.selectionEnd);target.setSelectionRange(start+1,start+1);target.scrollTop=scrolled;return false;}else
if(target.createTextRange){document.selection.createRange().text='\t';return false;}}if(event.keyCode==27){var
els=target.form.elements;for(var
i=1;i<els.length;i++){if(els[i-1]==target){els[i].focus();break;}}return false;}}return true;}var
added='.',rowCount;function
delimiterEqual(val,a,b){return(val==a+'_'+b||val==a+b||val==a+b.charAt(0).toUpperCase()+b.substr(1));}function
idfEscape(s){return s.replace(/`/,'``');}function
editingNameChange(field){var
name=field.name.substr(0,field.name.length-7);var
type=formField(field.form,name+'[type]');var
opts=type.options;var
candidate;var
val=field.value;for(var
i=opts.length;i--;){var
match=/(.+)`(.+)/.exec(opts[i].value);if(!match){if(candidate&&i==opts.length-2&&val==opts[candidate].value.replace(/.+`/,'')&&name=='fields[1]'){return;}break;}var
table=match[1];var
column=match[2];var
tables=[table,table.replace(/s$/,''),table.replace(/es$/,'')];for(var
j=0;j<tables.length;j++){table=tables[j];if(val==column||val==table||delimiterEqual(val,table,column)||delimiterEqual(val,column,table)){if(candidate){return;}candidate=i;break;}}}if(candidate){type.selectedIndex=candidate;type.onchange();}}function
editingAddRow(button,allowed,focus){if(allowed&&rowCount>=allowed){return false;}var
match=/(\d+)(\.\d+)?/.exec(button.name);var
x=match[0]+(match[2]?added.substr(match[2].length):added)+'1';var
row=button.parentNode.parentNode;var
row2=row.cloneNode(true);var
tags=row.getElementsByTagName('select');var
tags2=row2.getElementsByTagName('select');for(var
i=0;i<tags.length;i++){tags2[i].name=tags[i].name.replace(/([0-9.]+)/,x);tags2[i].selectedIndex=tags[i].selectedIndex;}tags=row.getElementsByTagName('input');tags2=row2.getElementsByTagName('input');var
input=tags2[0];for(var
i=0;i<tags.length;i++){if(tags[i].name=='auto_increment_col'){tags2[i].value=x;tags2[i].checked=false;}tags2[i].name=tags[i].name.replace(/([0-9.]+)/,x);if(/\[(orig|field|comment|default)/.test(tags[i].name)){tags2[i].value='';}if(/\[(has_default)/.test(tags[i].name)){tags2[i].checked=false;}}tags[0].onchange=function(){editingNameChange(tags[0]);};row.parentNode.insertBefore(row2,row.nextSibling);if(focus){input.onchange=function(){editingNameChange(input);};input.focus();}added+='0';rowCount++;return true;}function
editingRemoveRow(button){var
field=formField(button.form,button.name.replace(/drop_col(.+)/,'fields$1[field]'));field.parentNode.removeChild(field);button.parentNode.parentNode.style.display='none';return true;}var
lastType='';function
editingTypeChange(type){var
name=type.name.substr(0,type.name.length-6);var
text=selectValue(type);for(var
i=0;i<type.form.elements.length;i++){var
el=type.form.elements[i];if(el.name==name+'[length]'&&!((/(char|binary)$/.test(lastType)&&/(char|binary)$/.test(text))||(/(enum|set)$/.test(lastType)&&/(enum|set)$/.test(text)))){el.value='';}if(lastType=='timestamp'&&el.name==name+'[has_default]'&&/timestamp/i.test(formField(type.form,name+'[default]').value)){el.checked=false;}if(el.name==name+'[collation]'){el.className=(/(char|text|enum|set)$/.test(text)?'':'hidden');}if(el.name==name+'[unsigned]'){el.className=(/(int|float|double|decimal)$/.test(text)?'':'hidden');}if(el.name==name+'[on_delete]'){el.className=(/`/.test(text)?'':'hidden');}}}function
editingLengthFocus(field){var
td=field.parentNode;if(/(enum|set)$/.test(selectValue(td.previousSibling.firstChild))){var
edit=document.getElementById('enum-edit');var
val=field.value;edit.value=(/^'.+','.+'$/.test(val)?val.substr(1,val.length-2).replace(/','/g,"\n").replace(/''/g,"'"):val);td.appendChild(edit);field.style.display='none';edit.style.display='inline';edit.focus();}}function
editingLengthBlur(edit){var
field=edit.parentNode.firstChild;var
val=edit.value;field.value=(/\n/.test(val)?"'"+val.replace(/\n+$/,'').replace(/'/g,"''").replace(/\n/g,"','")+"'":val);field.style.display='inline';edit.style.display='none';}function
columnShow(checked,column){var
trs=document.getElementById('edit-fields').getElementsByTagName('tr');for(var
i=0;i<trs.length;i++){trs[i].getElementsByTagName('td')[column].className=(checked?'':'hidden');}}function
partitionByChange(el){var
partitionTable=/RANGE|LIST/.test(selectValue(el));el.form['partitions'].className=(partitionTable||!el.selectedIndex?'hidden':'');document.getElementById('partition-table').className=(partitionTable?'':'hidden');}function
partitionNameChange(el){var
row=el.parentNode.parentNode.cloneNode(true);row.firstChild.firstChild.value='';el.parentNode.parentNode.parentNode.appendChild(row);el.onchange=function(){};}function
foreignAddRow(field){field.onchange=function(){};var
row=field.parentNode.parentNode.cloneNode(true);var
selects=row.getElementsByTagName('select');for(var
i=0;i<selects.length;i++){selects[i].name=selects[i].name.replace(/\]/,'1$&');selects[i].selectedIndex=0;}field.parentNode.parentNode.parentNode.appendChild(row);}function
indexesAddRow(field){field.onchange=function(){};var
parent=field.parentNode.parentNode;var
row=parent.cloneNode(true);var
selects=row.getElementsByTagName('select');for(var
i=0;i<selects.length;i++){selects[i].name=selects[i].name.replace(/indexes\[\d+/,'$&1');selects[i].selectedIndex=0;}var
inputs=row.getElementsByTagName('input');for(var
i=0;i<inputs.length;i++){inputs[i].name=inputs[i].name.replace(/indexes\[\d+/,'$&1');inputs[i].value='';}parent.parentNode.appendChild(row);}function
indexesChangeColumn(field,prefix){var
columns=field.parentNode.parentNode.getElementsByTagName('select');var
names=[];for(var
i=0;i<columns.length;i++){var
value=selectValue(columns[i]);if(value){names.push(value);}}field.form[field.name.replace(/\].*/,'][name]')].value=prefix+names.join('_');}function
indexesAddColumn(field,prefix){field.onchange=function(){indexesChangeColumn(field,prefix);};var
select=field.form[field.name.replace(/\].*/,'][type]')];if(!select.selectedIndex){select.selectedIndex=3;select.onchange();}var
column=field.parentNode.cloneNode(true);select=column.getElementsByTagName('select')[0];select.name=select.name.replace(/\]\[\d+/,'$&1');select.selectedIndex=0;var
input=column.getElementsByTagName('input')[0];input.name=input.name.replace(/\]\[\d+/,'$&1');input.value='';field.parentNode.parentNode.appendChild(column);field.onchange();}var
that,x,y;function
schemaMousedown(el,event){if((event.which?event.which:event.button)==1){that=el;x=event.clientX-el.offsetLeft;y=event.clientY-el.offsetTop;}}function
schemaMousemove(ev){if(that!==undefined){ev=ev||event;var
left=(ev.clientX-x)/em;var
top=(ev.clientY-y)/em;var
divs=that.getElementsByTagName('div');var
lineSet={};for(var
i=0;i<divs.length;i++){if(divs[i].className=='references'){var
div2=document.getElementById((/^refs/.test(divs[i].id)?'refd':'refs')+divs[i].id.substr(4));var
ref=(tablePos[divs[i].title]?tablePos[divs[i].title]:[div2.parentNode.offsetTop/em,0]);var
left1=-1;var
id=divs[i].id.replace(/^ref.(.+)-.+/,'$1');if(divs[i].parentNode!=div2.parentNode){left1=Math.min(0,ref[1]-left)-1;divs[i].style.left=left1+'em';divs[i].getElementsByTagName('div')[0].style.width=-left1+'em';var
left2=Math.min(0,left-ref[1])-1;div2.style.left=left2+'em';div2.getElementsByTagName('div')[0].style.width=-left2+'em';}if(!lineSet[id]){var
line=document.getElementById(divs[i].id.replace(/^....(.+)-.+$/,'refl$1'));var
top1=top+divs[i].offsetTop/em;var
top2=top+div2.offsetTop/em;if(divs[i].parentNode!=div2.parentNode){top2+=ref[0]-top;line.getElementsByTagName('div')[0].style.height=Math.abs(top1-top2)+'em';}line.style.left=(left+left1)+'em';line.style.top=Math.min(top1,top2)+'em';lineSet[id]=true;}}}that.style.left=left+'em';that.style.top=top+'em';}}function
schemaMouseup(ev,db){if(that!==undefined){ev=ev||event;tablePos[that.firstChild.firstChild.firstChild.data]=[(ev.clientY-y)/em,(ev.clientX-x)/em];that=undefined;var
s='';for(var
key
in
tablePos){s+='_'+key+':'+Math.round(tablePos[key][0]*10000)/10000+'x'+Math.round(tablePos[key][1]*10000)/10000;}s=encodeURIComponent(s.substr(1));var
link=document.getElementById('schema-link');link.href=link.href.replace(/[^=]+$/,'')+s;cookie('adminer_schema-'+db+'='+s,30);}}<?php
}else{header("Content-Type: image/gif");switch($_GET["file"]){case"plus.gif":echo
base64_decode("R0lGODdhEgASAKEAAO7u7gAAAJmZmQAAACwAAAAAEgASAAACIYSPqcvtD00I8cwqKb5v+q8pIAhxlRmhZYi17iPE8kzLBQA7");break;case"cross.gif":echo
base64_decode("R0lGODdhEgASAKEAAO7u7gAAAJmZmQAAACwAAAAAEgASAAACI4SPqcvtDyMKYdZGb355wy6BX3dhlOEx57FK7gtHwkzXNl0AADs=");break;case"up.gif":echo
base64_decode("R0lGODdhEgASAKEAAO7u7gAAAJmZmQAAACwAAAAAEgASAAACIISPqcvtD00IUU4K730T9J5hFTiKEXmaYcW2rgDH8hwXADs=");break;case"down.gif":echo
base64_decode("R0lGODdhEgASAKEAAO7u7gAAAJmZmQAAACwAAAAAEgASAAACIISPqcvtD00I8cwqKb5bV/5cosdMJtmcHca2lQDH8hwXADs=");break;case"arrow.gif":echo
base64_decode("R0lGODlhCAAKAIAAAICAgP///yH5BAEAAAEALAAAAAAIAAoAAAIPBIJplrGLnpQRqtOy3rsAADs=");break;}}exit;}function
connection(){global$f;return$f;}function
adminer(){global$b;return$b;}function
idf_unescape($qc){$Fc=substr($qc,-1);return
str_replace($Fc.$Fc,$Fc,substr($qc,1,-1));}function
escape_string($X){return
substr(q($X),1,-1);}function
remove_slashes($fe,$Wb=false){if(get_magic_quotes_gpc()){while(list($y,$X)=each($fe)){foreach($X
as$Bc=>$W){unset($fe[$y][$Bc]);if(is_array($W)){$fe[$y][stripslashes($Bc)]=$W;$fe[]=&$fe[$y][stripslashes($Bc)];}else$fe[$y][stripslashes($Bc)]=($Wb?$W:stripslashes($W));}}}}function
bracket_escape($qc,$wa=false){static$qf=array(':'=>':1',']'=>':2','['=>':3');return
strtr($qc,($wa?array_flip($qf):$qf));}function
h($Q){return
htmlspecialchars(str_replace("\0","",$Q),ENT_QUOTES);}function
nbsp($Q){return(trim($Q)!=""?h($Q):"&nbsp;");}function
nl_br($Q){return
str_replace("\n","<br>",$Q);}function
checkbox($D,$Y,$Ga,$Dc="",$ud="",$Ac=false){static$t=0;$t++;$J="<input type='checkbox' name='$D' value='".h($Y)."'".($Ga?" checked":"").($ud?' onclick="'.h($ud).'"':'').($Ac?" class='jsonly'":"")." id='checkbox-$t'>";return($Dc!=""?"<label for='checkbox-$t'>$J".h($Dc)."</label>":$J);}function
optionlist($xd,$Ce=null,$Hf=false){$J="";foreach($xd
as$Bc=>$W){$yd=array($Bc=>$W);if(is_array($W)){$J.='<optgroup label="'.h($Bc).'">';$yd=$W;}foreach($yd
as$y=>$X)$J.='<option'.($Hf||is_string($y)?' value="'.h($y).'"':'').(($Hf||is_string($y)?(string)$y:$X)===$Ce?' selected':'').'>'.h($X);if(is_array($W))$J.='</optgroup>';}return$J;}function
html_select($D,$xd,$Y="",$td=true){if($td)return"<select name='".h($D)."'".(is_string($td)?' onchange="'.h($td).'"':"").">".optionlist($xd,$Y)."</select>";$J="";foreach($xd
as$y=>$X)$J.="<label><input type='radio' name='".h($D)."' value='".h($y)."'".($y==$Y?" checked":"").">".h($X)."</label>";return$J;}function
confirm($Ya=""){return" onclick=\"return confirm('".'Are you sure?'.($Ya?" (' + $Ya + ')":"")."');\"";}function
print_fieldset($t,$Kc,$Nf=false,$ud=""){echo"<fieldset><legend><a href='#fieldset-$t' onclick=\"".h($ud)."return !toggle('fieldset-$t');\">$Kc</a></legend><div id='fieldset-$t'".($Nf?"":" class='hidden'").">\n";}function
bold($Aa){return($Aa?" class='active'":"");}function
odd($J=' class="odd"'){static$s=0;if(!$J)$s=-1;return($s++%
2?$J:'');}function
js_escape($Q){return
addcslashes($Q,"\r\n'\\/");}function
json_row($y,$X=null){static$Xb=true;if($Xb)echo"{";if($y!=""){echo($Xb?"":",")."\n\t\"".addcslashes($y,"\r\n\"\\").'": '.($X!==null?'"'.addcslashes($X,"\r\n\"\\").'"':'undefined');$Xb=false;}else{echo"\n}\n";$Xb=true;}}function
ini_bool($uc){$X=ini_get($uc);return(eregi('^(on|true|yes)$',$X)||(int)$X);}function
sid(){static$J;if($J===null)$J=(SID&&!($_COOKIE&&ini_bool("session.use_cookies")));return$J;}function
q($Q){global$f;return$f->quote($Q);}function
get_vals($H,$Na=0){global$f;$J=array();$I=$f->query($H);if(is_object($I)){while($K=$I->fetch_row())$J[]=$K[$Na];}return$J;}function
get_key_vals($H,$g=null){global$f;if(!is_object($g))$g=$f;$J=array();$I=$g->query($H);if(is_object($I)){while($K=$I->fetch_row())$J[$K[0]]=$K[1];}return$J;}function
get_rows($H,$g=null,$j="<p class='error'>"){global$f;$Ua=(is_object($g)?$g:$f);$J=array();$I=$Ua->query($H);if(is_object($I)){while($K=$I->fetch_assoc())$J[]=$K;}elseif(!$I&&!is_object($g)&&$j&&defined("PAGE_HEADER"))echo$j.error()."\n";return$J;}function
unique_array($K,$v){foreach($v
as$u){if(ereg("PRIMARY|UNIQUE",$u["type"])){$J=array();foreach($u["columns"]as$y){if(!isset($K[$y]))continue
2;$J[$y]=$K[$y];}return$J;}}$J=array();foreach($K
as$y=>$X){if(!preg_match('~^(COUNT\\((\\*|(DISTINCT )?`(?:[^`]|``)+`)\\)|(AVG|GROUP_CONCAT|MAX|MIN|SUM)\\(`(?:[^`]|``)+`\\))$~',$y))$J[$y]=$X;}return$J;}function
where($Z){global$x;$J=array();foreach((array)$Z["where"]as$y=>$X)$J[]=idf_escape(bracket_escape($y,1)).(($x=="sql"&&ereg('\\.',$X))||$x=="mssql"?" LIKE ".exact_value(addcslashes($X,"%_\\")):" = ".exact_value($X));foreach((array)$Z["null"]as$y)$J[]=idf_escape($y)." IS NULL";return
implode(" AND ",$J);}function
where_check($X){parse_str($X,$Fa);remove_slashes(array(&$Fa));return
where($Fa);}function
where_link($s,$Na,$Y,$vd="="){return"&where%5B$s%5D%5Bcol%5D=".urlencode($Na)."&where%5B$s%5D%5Bop%5D=".urlencode(($Y!==null?$vd:"IS NULL"))."&where%5B$s%5D%5Bval%5D=".urlencode($Y);}function
cookie($D,$Y){global$ba;$Kd=array($D,(ereg("\n",$Y)?"":$Y),time()+2592000,preg_replace('~\\?.*~','',$_SERVER["REQUEST_URI"]),"",$ba);if(version_compare(PHP_VERSION,'5.2.0')>=0)$Kd[]=true;return
call_user_func_array('setcookie',$Kd);}function
restart_session(){if(!ini_bool("session.use_cookies"))session_start();}function&get_session($y){return$_SESSION[$y][DRIVER][SERVER][$_GET["username"]];}function
set_session($y,$X){$_SESSION[$y][DRIVER][SERVER][$_GET["username"]]=$X;}function
auth_url($pb,$O,$If,$i=null){global$qb;preg_match('~([^?]*)\\??(.*)~',remove_from_uri(implode("|",array_keys($qb))."|username|".($i!==null?"db|":"").session_name()),$B);return"$B[1]?".(sid()?SID."&":"").($pb!="server"||$O!=""?urlencode($pb)."=".urlencode($O)."&":"")."username=".urlencode($If).($i!=""?"&db=".urlencode($i):"").($B[2]?"&$B[2]":"");}function
is_ajax(){return($_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest");}function
redirect($A,$Yc=null){if($Yc!==null){restart_session();$_SESSION["messages"][preg_replace('~^[^?]*~','',($A!==null?$A:$_SERVER["REQUEST_URI"]))][]=$Yc;}if($A!==null){if($A=="")$A=".";header("Location: $A");exit;}}function
query_redirect($H,$A,$Yc,$ke=true,$Mb=true,$Sb=false){global$f,$j,$b;if($Mb)$Sb=!$f->query($H);$Ke="";if($H)$Ke=$b->messageQuery("$H;");if($Sb){$j=error().$Ke;return
false;}if($ke)redirect($A,$Yc.$Ke);return
true;}function
queries($H=null){global$f;static$ie=array();if($H===null)return
implode(";\n",$ie);$ie[]=(ereg(';$',$H)?"DELIMITER ;;\n$H;\nDELIMITER ":$H);return$f->query($H);}function
apply_queries($H,$bf,$Hb='table'){foreach($bf
as$S){if(!queries("$H ".$Hb($S)))return
false;}return
true;}function
queries_redirect($A,$Yc,$ke){return
query_redirect(queries(),$A,$Yc,$ke,false,!$ke);}function
remove_from_uri($Jd=""){return
substr(preg_replace("~(?<=[?&])($Jd".(SID?"":"|".session_name()).")=[^&]*&~",'',"$_SERVER[REQUEST_URI]&"),0,-1);}function
pagination($E,$db){return" ".($E==$db?$E+1:'<a href="'.h(remove_from_uri("page").($E?"&page=$E":"")).'">'.($E+1)."</a>");}function
get_file($y,$ib=false){$Ub=$_FILES[$y];if(!$Ub||$Ub["error"])return$Ub["error"];$J=file_get_contents($ib&&ereg('\\.gz$',$Ub["name"])?"compress.zlib://$Ub[tmp_name]":($ib&&ereg('\\.bz2$',$Ub["name"])?"compress.bzip2://$Ub[tmp_name]":$Ub["tmp_name"]));if($ib){$Le=substr($J,0,3);if(function_exists("iconv")&&ereg("^\xFE\xFF|^\xFF\xFE",$Le,$qe))$J=iconv("utf-16","utf-8",$J);elseif($Le=="\xEF\xBB\xBF")$J=substr($J,3);}return$J;}function
upload_error($j){$Wc=($j==UPLOAD_ERR_INI_SIZE?ini_get("upload_max_filesize"):0);return($j?'Unable to upload a file.'.($Wc?" ".sprintf('Maximum allowed file size is %sB.',$Wc):""):'File does not exist.');}function
repeat_pattern($F,$Lc){return
str_repeat("$F{0,65535}",$Lc/65535)."$F{0,".($Lc
%
65535)."}";}function
is_utf8($X){return(preg_match('~~u',$X)&&!preg_match('~[\\0-\\x8\\xB\\xC\\xE-\\x1F]~',$X));}function
shorten_utf8($Q,$Lc=80,$Re=""){if(!preg_match("(^(".repeat_pattern("[\t\r\n -\x{FFFF}]",$Lc).")($)?)u",$Q,$B))preg_match("(^(".repeat_pattern("[\t\r\n -~]",$Lc).")($)?)",$Q,$B);return
h($B[1]).$Re.(isset($B[2])?"":"<i>...</i>");}function
friendly_url($X){return
preg_replace('~[^a-z0-9_]~i','-',$X);}function
hidden_fields($fe,$rc=array()){while(list($y,$X)=each($fe)){if(is_array($X)){foreach($X
as$Bc=>$W)$fe[$y."[$Bc]"]=$W;}elseif(!in_array($y,$rc))echo'<input type="hidden" name="'.h($y).'" value="'.h($X).'">';}}function
hidden_fields_get(){echo(sid()?'<input type="hidden" name="'.session_name().'" value="'.h(session_id()).'">':''),(SERVER!==null?'<input type="hidden" name="'.DRIVER.'" value="'.h(SERVER).'">':""),'<input type="hidden" name="username" value="'.h($_GET["username"]).'">';}function
column_foreign_keys($S){global$b;$J=array();foreach($b->foreignKeys($S)as$m){foreach($m["source"]as$X)$J[$X][]=$m;}return$J;}function
enum_input($V,$ta,$k,$Y,$Ab=null){global$b;preg_match_all("~'((?:[^']|'')*)'~",$k["length"],$Rc);$J=($Ab!==null?"<label><input type='$V'$ta value='$Ab'".((is_array($Y)?in_array($Ab,$Y):$Y===0)?" checked":"")."><i>".'empty'."</i></label>":"");foreach($Rc[1]as$s=>$X){$X=stripcslashes(str_replace("''","'",$X));$Ga=(is_int($Y)?$Y==$s+1:(is_array($Y)?in_array($s+1,$Y):$Y===$X));$J.=" <label><input type='$V'$ta value='".($s+1)."'".($Ga?' checked':'').'>'.h($b->editVal($X,$k)).'</label>';}return$J;}function
input($k,$Y,$p){global$yf,$b,$x;$D=h(bracket_escape($k["field"]));echo"<td class='function'>";$se=($x=="mssql"&&$k["auto_increment"]);if($se&&!$_POST["save"])$p=null;$q=(isset($_GET["select"])||$se?array("orig"=>'original'):array())+$b->editFunctions($k);$ta=" name='fields[$D]'";if($k["type"]=="enum")echo
nbsp($q[""])."<td>".$b->editInput($_GET["edit"],$k,$ta,$Y);else{$Xb=0;foreach($q
as$y=>$X){if($y===""||!$X)break;$Xb++;}$td=($Xb?" onchange=\"var f = this.form['function[".h(js_escape(bracket_escape($k["field"])))."]']; if ($Xb > f.selectedIndex) f.selectedIndex = $Xb;\"":"");$ta.=$td;echo(count($q)>1?html_select("function[$D]",$q,$p===null||in_array($p,$q)||isset($q[$p])?$p:"","functionChange(this);"):nbsp(reset($q))).'<td>';$wc=$b->editInput($_GET["edit"],$k,$ta,$Y);if($wc!="")echo$wc;elseif($k["type"]=="set"){preg_match_all("~'((?:[^']|'')*)'~",$k["length"],$Rc);foreach($Rc[1]as$s=>$X){$X=stripcslashes(str_replace("''","'",$X));$Ga=(is_int($Y)?($Y>>$s)&1:in_array($X,explode(",",$Y),true));echo" <label><input type='checkbox' name='fields[$D][$s]' value='".(1<<$s)."'".($Ga?' checked':'')."$td>".h($b->editVal($X,$k)).'</label>';}}elseif(ereg('blob|bytea|raw|file',$k["type"])&&ini_bool("file_uploads"))echo"<input type='file' name='fields-$D'$td>";elseif(ereg('text|lob',$k["type"]))echo"<textarea ".($x!="sqlite"||ereg("\n",$Y)?"cols='50' rows='12'":"cols='30' rows='1' style='height: 1.2em;'")."$ta>".h($Y).'</textarea>';else{$Xc=(!ereg('int',$k["type"])&&preg_match('~^(\\d+)(,(\\d+))?$~',$k["length"],$B)?((ereg("binary",$k["type"])?2:1)*$B[1]+($B[3]?1:0)+($B[2]&&!$k["unsigned"]?1:0)):($yf[$k["type"]]?$yf[$k["type"]]+($k["unsigned"]?0:1):0));echo"<input value='".h($Y)."'".($Xc?" maxlength='$Xc'":"").(ereg('char|binary',$k["type"])&&$Xc>20?" size='40'":"")."$ta>";}}}function
process_input($k){global$b;$qc=bracket_escape($k["field"]);$p=$_POST["function"][$qc];$Y=$_POST["fields"][$qc];if($k["type"]=="enum"){if($Y==-1)return
false;if($Y=="")return"NULL";return+$Y;}if($k["auto_increment"]&&$Y=="")return
null;if($p=="orig")return($k["on_update"]=="CURRENT_TIMESTAMP"?idf_escape($k["field"]):false);if($p=="NULL")return"NULL";if($k["type"]=="set")return
array_sum((array)$Y);if(ereg('blob|bytea|raw|file',$k["type"])&&ini_bool("file_uploads")){$Ub=get_file("fields-$qc");if(!is_string($Ub))return
false;return
q($Ub);}return$b->processInput($k,$Y,$p);}function
search_tables(){global$b,$f;$_GET["where"][0]["op"]="LIKE %%";$_GET["where"][0]["val"]=$_POST["query"];$o=false;foreach(table_status()as$S=>$T){$D=$b->tableName($T);if(isset($T["Engine"])&&$D!=""&&(!$_POST["tables"]||in_array($S,$_POST["tables"]))){$I=$f->query("SELECT".limit("1 FROM ".table($S)," WHERE ".implode(" AND ",$b->selectSearchProcess(fields($S),array())),1));if($I->fetch_row()){if(!$o){echo"<ul>\n";$o=true;}echo"<li><a href='".h(ME."select=".urlencode($S)."&where[0][op]=".urlencode($_GET["where"][0]["op"])."&where[0][val]=".urlencode($_GET["where"][0]["val"]))."'>$D</a>\n";}}}echo($o?"</ul>":"<p class='message'>".'No tables.')."\n";}function
dump_headers($pc,$fd=false){global$b;$J=$b->dumpHeaders($pc,$fd);$Hd=$_POST["output"];if($Hd!="text")header("Content-Disposition: attachment; filename=".$b->dumpFilename($pc).".$J".($Hd!="file"&&!ereg('[^0-9a-z]',$Hd)?".$Hd":""));session_write_close();return$J;}function
dump_csv($K){foreach($K
as$y=>$X){if(preg_match("~[\"\n,;\t]~",$X)||$X==="")$K[$y]='"'.str_replace('"','""',$X).'"';}echo
implode(($_POST["format"]=="csv"?",":($_POST["format"]=="tsv"?"\t":";")),$K)."\r\n";}function
apply_sql_function($p,$Na){return($p?($p=="unixepoch"?"DATETIME($Na, '$p')":($p=="count distinct"?"COUNT(DISTINCT ":strtoupper("$p("))."$Na)"):$Na);}function
password_file(){$mb=ini_get("upload_tmp_dir");if(!$mb){if(function_exists('sys_get_temp_dir'))$mb=sys_get_temp_dir();else{$Vb=@tempnam("","");if(!$Vb)return
false;$mb=dirname($Vb);unlink($Vb);}}$Vb="$mb/adminer.key";$J=@file_get_contents($Vb);if($J)return$J;$dc=@fopen($Vb,"w");if($dc){$J=md5(uniqid(mt_rand(),true));fwrite($dc,$J);fclose($dc);}return$J;}function
is_mail($yb){$sa='[-a-z0-9!#$%&\'*+/=?^_`{|}~]';$ob='[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';$F="$sa+(\\.$sa+)*@($ob?\\.)+$ob";return
preg_match("(^$F(,\\s*$F)*\$)i",$yb);}function
is_url($Q){$ob='[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';return(preg_match("~^(https?)://($ob?\\.)+$ob(:\\d+)?(/.*)?(\\?.*)?(#.*)?\$~i",$Q,$B)?strtolower($B[1]):"");}global$b,$f,$qb,$wb,$Eb,$j,$q,$jc,$ba,$vc,$x,$ca,$Ec,$sd,$Pe,$U,$sf,$yf,$Ef,$ga;if(!$_SERVER["REQUEST_URI"])$_SERVER["REQUEST_URI"]=$_SERVER["ORIG_PATH_INFO"];if(!strpos($_SERVER["REQUEST_URI"],'?')&&$_SERVER["QUERY_STRING"]!="")$_SERVER["REQUEST_URI"].="?$_SERVER[QUERY_STRING]";$ba=$_SERVER["HTTPS"]&&strcasecmp($_SERVER["HTTPS"],"off");@ini_set("session.use_trans_sid",false);if(!defined("SID")){session_name("adminer_sid");$Kd=array(0,preg_replace('~\\?.*~','',$_SERVER["REQUEST_URI"]),"",$ba);if(version_compare(PHP_VERSION,'5.2.0')>=0)$Kd[]=true;call_user_func_array('session_set_cookie_params',$Kd);session_start();}remove_slashes(array(&$_GET,&$_POST,&$_COOKIE),$Wb);if(function_exists("set_magic_quotes_runtime"))set_magic_quotes_runtime(false);@set_time_limit(0);@ini_set("zend.ze1_compatibility_mode",false);@ini_set("precision",20);function
get_lang(){return'en';}function
lang($rf,$ld){$Ud=($ld==1?0:1);$rf=str_replace("%d","%s",$rf[$Ud]);$ld=number_format($ld,0,".",',');return
sprintf($rf,$ld);}if(extension_loaded('pdo')){class
Min_PDO
extends
PDO{var$_result,$server_info,$affected_rows,$error;function
__construct(){global$b;$Ud=array_search("",$b->operators);if($Ud!==false)unset($b->operators[$Ud]);}function
dsn($tb,$If,$Rd,$Lb='auth_error'){set_exception_handler($Lb);parent::__construct($tb,$If,$Rd);restore_exception_handler();$this->setAttribute(13,array('Min_PDOStatement'));$this->server_info=$this->getAttribute(4);}function
query($H,$zf=false){$I=parent::query($H);if(!$I){$Fb=$this->errorInfo();$this->error=$Fb[2];return
false;}$this->store_result($I);return$I;}function
multi_query($H){return$this->_result=$this->query($H);}function
store_result($I=null){if(!$I)$I=$this->_result;if($I->columnCount()){$I->num_rows=$I->rowCount();return$I;}$this->affected_rows=$I->rowCount();return
true;}function
next_result(){$this->_result->_offset=0;return@$this->_result->nextRowset();}function
result($H,$k=0){$I=$this->query($H);if(!$I)return
false;$K=$I->fetch();return$K[$k];}}class
Min_PDOStatement
extends
PDOStatement{var$_offset=0,$num_rows;function
fetch_assoc(){return$this->fetch(2);}function
fetch_row(){return$this->fetch(3);}function
fetch_field(){$K=(object)$this->getColumnMeta($this->_offset++);$K->orgtable=$K->table;$K->orgname=$K->name;$K->charsetnr=(in_array("blob",(array)$K->flags)?63:0);return$K;}}}$qb=array();$qb=array("server"=>"MySQL")+$qb;if(!defined("DRIVER")){$Xd=array("MySQLi","MySQL","PDO_MySQL");define("DRIVER","server");if(extension_loaded("mysqli")){class
Min_DB
extends
MySQLi{var$extension="MySQLi";function
Min_DB(){parent::init();}function
connect($O,$If,$Rd){mysqli_report(MYSQLI_REPORT_OFF);list($nc,$Td)=explode(":",$O,2);$J=@$this->real_connect(($O!=""?$nc:ini_get("mysqli.default_host")),($O.$If!=""?$If:ini_get("mysqli.default_user")),($O.$If.$Rd!=""?$Rd:ini_get("mysqli.default_pw")),null,(is_numeric($Td)?$Td:ini_get("mysqli.default_port")),(!is_numeric($Td)?$Td:null));if($J){if(method_exists($this,'set_charset'))$this->set_charset("utf8");else$this->query("SET NAMES utf8");}return$J;}function
result($H,$k=0){$I=$this->query($H);if(!$I)return
false;$K=$I->fetch_array();return$K[$k];}function
quote($Q){return"'".$this->escape_string($Q)."'";}}}elseif(extension_loaded("mysql")&&!(ini_get("sql.safe_mode")&&extension_loaded("pdo_mysql"))){class
Min_DB{var$extension="MySQL",$server_info,$affected_rows,$error,$_link,$_result;function
connect($O,$If,$Rd){$this->_link=@mysql_connect(($O!=""?$O:ini_get("mysql.default_host")),("$O$If"!=""?$If:ini_get("mysql.default_user")),("$O$If$Rd"!=""?$Rd:ini_get("mysql.default_password")),true,131072);if($this->_link){$this->server_info=mysql_get_server_info($this->_link);if(function_exists('mysql_set_charset'))mysql_set_charset("utf8",$this->_link);else$this->query("SET NAMES utf8");}else$this->error=mysql_error();return(bool)$this->_link;}function
quote($Q){return"'".mysql_real_escape_string($Q,$this->_link)."'";}function
select_db($gb){return
mysql_select_db($gb,$this->_link);}function
query($H,$zf=false){$I=@($zf?mysql_unbuffered_query($H,$this->_link):mysql_query($H,$this->_link));if(!$I){$this->error=mysql_error($this->_link);return
false;}if($I===true){$this->affected_rows=mysql_affected_rows($this->_link);$this->info=mysql_info($this->_link);return
true;}return
new
Min_Result($I);}function
multi_query($H){return$this->_result=$this->query($H);}function
store_result(){return$this->_result;}function
next_result(){return
false;}function
result($H,$k=0){$I=$this->query($H);if(!$I||!$I->num_rows)return
false;return
mysql_result($I->_result,0,$k);}}class
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
connect($O,$If,$Rd){$this->dsn("mysql:host=".str_replace(":",";unix_socket=",preg_replace('~:(\\d)~',';port=\\1',$O)),$If,$Rd);$this->query("SET NAMES utf8");return
true;}function
select_db($gb){return$this->query("USE ".idf_escape($gb));}function
query($H,$zf=false){$this->setAttribute(1000,!$zf);return
parent::query($H,$zf);}}}function
idf_escape($qc){return"`".str_replace("`","``",$qc)."`";}function
table($qc){return
idf_escape($qc);}function
connect(){global$b;$f=new
Min_DB;$cb=$b->credentials();if($f->connect($cb[0],$cb[1],$cb[2])){$f->query("SET sql_quote_show_create = 1, autocommit = 1");return$f;}$J=$f->error;if(function_exists('iconv')&&!is_utf8($J)&&strlen($M=iconv("windows-1250","utf-8",$J))>strlen($J))$J=$M;return$J;}function
get_databases($Yb=true){global$f;$J=&get_session("dbs");if($J===null){if($Yb){restart_session();ob_flush();flush();}$J=get_vals($f->server_info>=5?"SELECT SCHEMA_NAME FROM information_schema.SCHEMATA":"SHOW DATABASES");}return$J;}function
limit($H,$Z,$z,$nd=0,$Ee=" "){return" $H$Z".($z!==null?$Ee."LIMIT $z".($nd?" OFFSET $nd":""):"");}function
limit1($H,$Z){return
limit($H,$Z,1);}function
db_collation($i,$d){global$f;$J=null;$Za=$f->result("SHOW CREATE DATABASE ".idf_escape($i),1);if(preg_match('~ COLLATE ([^ ]+)~',$Za,$B))$J=$B[1];elseif(preg_match('~ CHARACTER SET ([^ ]+)~',$Za,$B))$J=$d[$B[1]][-1];return$J;}function
engines(){$J=array();foreach(get_rows("SHOW ENGINES")as$K){if(ereg("YES|DEFAULT",$K["Support"]))$J[]=$K["Engine"];}return$J;}function
logged_user(){global$f;return$f->result("SELECT USER()");}function
tables_list(){global$f;return
get_key_vals("SHOW".($f->server_info>=5?" FULL":"")." TABLES");}function
count_tables($h){$J=array();foreach($h
as$i)$J[$i]=count(get_vals("SHOW TABLES IN ".idf_escape($i)));return$J;}function
table_status($D=""){$J=array();foreach(get_rows("SHOW TABLE STATUS".($D!=""?" LIKE ".q(addcslashes($D,"%_")):""))as$K){if($K["Engine"]=="InnoDB")$K["Comment"]=preg_replace('~(?:(.+); )?InnoDB free: .*~','\\1',$K["Comment"]);if(!isset($K["Rows"]))$K["Comment"]="";if($D!="")return$K;$J[$K["Name"]]=$K;}return$J;}function
is_view($T){return!isset($T["Rows"]);}function
fk_support($T){return
eregi("InnoDB|IBMDB2I",$T["Engine"]);}function
fields($S){$J=array();foreach(get_rows("SHOW FULL COLUMNS FROM ".table($S))as$K){preg_match('~^([^( ]+)(?:\\((.+)\\))?( unsigned)?( zerofill)?$~',$K["Type"],$B);$J[$K["Field"]]=array("field"=>$K["Field"],"full_type"=>$K["Type"],"type"=>$B[1],"length"=>$B[2],"unsigned"=>ltrim($B[3].$B[4]),"default"=>($K["Default"]!=""||ereg("char",$B[1])?$K["Default"]:null),"null"=>($K["Null"]=="YES"),"auto_increment"=>($K["Extra"]=="auto_increment"),"on_update"=>(eregi('^on update (.+)',$K["Extra"],$B)?$B[1]:""),"collation"=>$K["Collation"],"privileges"=>array_flip(explode(",",$K["Privileges"])),"comment"=>$K["Comment"],"primary"=>($K["Key"]=="PRI"),);}return$J;}function
indexes($S,$g=null){$J=array();foreach(get_rows("SHOW INDEX FROM ".table($S),$g)as$K){$J[$K["Key_name"]]["type"]=($K["Key_name"]=="PRIMARY"?"PRIMARY":($K["Index_type"]=="FULLTEXT"?"FULLTEXT":($K["Non_unique"]?"INDEX":"UNIQUE")));$J[$K["Key_name"]]["columns"][]=$K["Column_name"];$J[$K["Key_name"]]["lengths"][]=$K["Sub_part"];}return$J;}function
foreign_keys($S){global$f,$sd;static$F='`(?:[^`]|``)+`';$J=array();$ab=$f->result("SHOW CREATE TABLE ".table($S),1);if($ab){preg_match_all("~CONSTRAINT ($F) FOREIGN KEY \\(((?:$F,? ?)+)\\) REFERENCES ($F)(?:\\.($F))? \\(((?:$F,? ?)+)\\)(?: ON DELETE ($sd))?(?: ON UPDATE ($sd))?~",$ab,$Rc,PREG_SET_ORDER);foreach($Rc
as$B){preg_match_all("~$F~",$B[2],$Ie);preg_match_all("~$F~",$B[5],$ef);$J[idf_unescape($B[1])]=array("db"=>idf_unescape($B[4]!=""?$B[3]:$B[4]),"table"=>idf_unescape($B[4]!=""?$B[4]:$B[3]),"source"=>array_map('idf_unescape',$Ie[0]),"target"=>array_map('idf_unescape',$ef[0]),"on_delete"=>($B[6]?$B[6]:"RESTRICT"),"on_update"=>($B[7]?$B[7]:"RESTRICT"),);}}return$J;}function
view($D){global$f;return
array("select"=>preg_replace('~^(?:[^`]|`[^`]*`)*\\s+AS\\s+~isU','',$f->result("SHOW CREATE VIEW ".table($D),1)));}function
collations(){$J=array();foreach(get_rows("SHOW COLLATION")as$K){if($K["Default"])$J[$K["Charset"]][-1]=$K["Collation"];else$J[$K["Charset"]][]=$K["Collation"];}ksort($J);foreach($J
as$y=>$X)asort($J[$y]);return$J;}function
information_schema($i){global$f;return($f->server_info>=5&&$i=="information_schema");}function
error(){global$f;return
h(preg_replace('~^You have an error.*syntax to use~U',"Syntax error",$f->error));}function
error_line(){global$f;if(ereg(' at line ([0-9]+)$',$f->error,$qe))return$qe[1]-1;}function
exact_value($X){return
q($X)." COLLATE utf8_bin";}function
create_database($i,$La){set_session("dbs",null);return
queries("CREATE DATABASE ".idf_escape($i).($La?" COLLATE ".q($La):""));}function
drop_databases($h){set_session("dbs",null);return
apply_queries("DROP DATABASE",$h,'idf_escape');}function
rename_database($D,$La){if(create_database($D,$La)){$re=array();foreach(tables_list()as$S=>$V)$re[]=table($S)." TO ".idf_escape($D).".".table($S);if(!$re||queries("RENAME TABLE ".implode(", ",$re))){queries("DROP DATABASE ".idf_escape(DB));return
true;}}return
false;}function
auto_increment(){$va=" PRIMARY KEY";if($_GET["create"]!=""&&$_POST["auto_increment_col"]){foreach(indexes($_GET["create"])as$u){if(in_array($_POST["fields"][$_POST["auto_increment_col"]]["orig"],$u["columns"],true)){$va="";break;}if($u["type"]=="PRIMARY")$va=" UNIQUE";}}return" AUTO_INCREMENT$va";}function
alter_table($S,$D,$l,$Zb,$Qa,$Cb,$La,$ua,$Od){$ra=array();foreach($l
as$k)$ra[]=($k[1]?($S!=""?($k[0]!=""?"CHANGE ".idf_escape($k[0]):"ADD"):" ")." ".implode($k[1]).($S!=""?" $k[2]":""):"DROP ".idf_escape($k[0]));$ra=array_merge($ra,$Zb);$Me="COMMENT=".q($Qa).($Cb?" ENGINE=".q($Cb):"").($La?" COLLATE ".q($La):"").($ua!=""?" AUTO_INCREMENT=$ua":"").$Od;if($S=="")return
queries("CREATE TABLE ".table($D)." (\n".implode(",\n",$ra)."\n) $Me");if($S!=$D)$ra[]="RENAME TO ".table($D);$ra[]=$Me;return
queries("ALTER TABLE ".table($S)."\n".implode(",\n",$ra));}function
alter_indexes($S,$ra){foreach($ra
as$y=>$X)$ra[$y]=($X[2]=="DROP"?"\nDROP INDEX ".idf_escape($X[1]):"\nADD $X[0] ".($X[0]=="PRIMARY"?"KEY ":"").($X[1]!=""?idf_escape($X[1])." ":"").$X[2]);return
queries("ALTER TABLE ".table($S).implode(",",$ra));}function
truncate_tables($bf){return
apply_queries("TRUNCATE TABLE",$bf);}function
drop_views($Mf){return
queries("DROP VIEW ".implode(", ",array_map('table',$Mf)));}function
drop_tables($bf){return
queries("DROP TABLE ".implode(", ",array_map('table',$bf)));}function
move_tables($bf,$Mf,$ef){$re=array();foreach(array_merge($bf,$Mf)as$S)$re[]=table($S)." TO ".idf_escape($ef).".".table($S);return
queries("RENAME TABLE ".implode(", ",$re));}function
copy_tables($bf,$Mf,$ef){queries("SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO'");foreach($bf
as$S){$D=($ef==DB?table("copy_$S"):idf_escape($ef).".".table($S));if(!queries("DROP TABLE IF EXISTS $D")||!queries("CREATE TABLE $D LIKE ".table($S))||!queries("INSERT INTO $D SELECT * FROM ".table($S)))return
false;}foreach($Mf
as$S){$D=($ef==DB?table("copy_$S"):idf_escape($ef).".".table($S));$Lf=view($S);if(!queries("DROP VIEW IF EXISTS $D")||!queries("CREATE VIEW $D AS $Lf[select]"))return
false;}return
true;}function
trigger($D){if($D=="")return
array();$L=get_rows("SHOW TRIGGERS WHERE `Trigger` = ".q($D));return
reset($L);}function
triggers($S){$J=array();foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($S,"%_")))as$K)$J[$K["Trigger"]]=array($K["Timing"],$K["Event"]);return$J;}function
trigger_options(){return
array("Timing"=>array("BEFORE","AFTER"),"Type"=>array("FOR EACH ROW"),);}function
routine($D,$V){global$f,$Eb,$vc,$yf;$pa=array("bool","boolean","integer","double precision","real","dec","numeric","fixed","national char","national varchar");$xf="((".implode("|",array_merge(array_keys($yf),$pa)).")\\b(?:\\s*\\(((?:[^'\")]*|$Eb)+)\\))?\\s*(zerofill\\s*)?(unsigned(?:\\s+zerofill)?)?)(?:\\s*(?:CHARSET|CHARACTER\\s+SET)\\s*['\"]?([^'\"\\s]+)['\"]?)?";$F="\\s*(".($V=="FUNCTION"?"":$vc).")?\\s*(?:`((?:[^`]|``)*)`\\s*|\\b(\\S+)\\s+)$xf";$Za=$f->result("SHOW CREATE $V ".idf_escape($D),2);preg_match("~\\(((?:$F\\s*,?)*)\\)\\s*".($V=="FUNCTION"?"RETURNS\\s+$xf\\s+":"")."(.*)~is",$Za,$B);$l=array();preg_match_all("~$F\\s*,?~is",$B[1],$Rc,PREG_SET_ORDER);foreach($Rc
as$Jd){$D=str_replace("``","`",$Jd[2]).$Jd[3];$l[]=array("field"=>$D,"type"=>strtolower($Jd[5]),"length"=>preg_replace_callback("~$Eb~s",'normalize_enum',$Jd[6]),"unsigned"=>strtolower(preg_replace('~\\s+~',' ',trim("$Jd[8] $Jd[7]"))),"full_type"=>$Jd[4],"inout"=>strtoupper($Jd[1]),"collation"=>strtolower($Jd[9]),);}if($V!="FUNCTION")return
array("fields"=>$l,"definition"=>$B[11]);return
array("fields"=>$l,"returns"=>array("type"=>$B[12],"length"=>$B[13],"unsigned"=>$B[15],"collation"=>$B[16]),"definition"=>$B[17],"language"=>"SQL",);}function
routines(){return
get_rows("SELECT * FROM information_schema.ROUTINES WHERE ROUTINE_SCHEMA = ".q(DB));}function
routine_languages(){return
array();}function
begin(){return
queries("BEGIN");}function
insert_into($S,$P){return
queries("INSERT INTO ".table($S)." (".implode(", ",array_keys($P)).")\nVALUES (".implode(", ",$P).")");}function
insert_update($S,$P,$ae){foreach($P
as$y=>$X)$P[$y]="$y = $X";$Ff=implode(", ",$P);return
queries("INSERT INTO ".table($S)." SET $Ff ON DUPLICATE KEY UPDATE $Ff");}function
last_id(){global$f;return$f->result("SELECT LAST_INSERT_ID()");}function
explain($f,$H){return$f->query("EXPLAIN $H");}function
found_rows($T,$Z){return($Z||$T["Engine"]!="InnoDB"?null:$T["Rows"]);}function
types(){return
array();}function
schemas(){return
array();}function
get_schema(){return"";}function
set_schema($Ae){return
true;}function
create_sql($S,$ua){global$f;$J=$f->result("SHOW CREATE TABLE ".table($S),1);if(!$ua)$J=preg_replace('~ AUTO_INCREMENT=\\d+~','',$J);return$J;}function
truncate_sql($S){return"TRUNCATE ".table($S);}function
use_sql($gb){return"USE ".idf_escape($gb);}function
trigger_sql($S,$R){$J="";foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($S,"%_")),null,"-- ")as$K)$J.="\n".($R=='CREATE+ALTER'?"DROP TRIGGER IF EXISTS ".idf_escape($K["Trigger"]).";;\n":"")."CREATE TRIGGER ".idf_escape($K["Trigger"])." $K[Timing] $K[Event] ON ".table($K["Table"])." FOR EACH ROW\n$K[Statement];;\n";return$J;}function
show_variables(){return
get_key_vals("SHOW VARIABLES");}function
process_list(){return
get_rows("SHOW FULL PROCESSLIST");}function
show_status(){return
get_key_vals("SHOW STATUS");}function
support($Tb){global$f;return!ereg("scheme|sequence|type".($f->server_info<5.1?"|event|partitioning".($f->server_info<5?"|view|routine|trigger":""):""),$Tb);}$x="sql";$yf=array();$Pe=array();foreach(array('Numbers'=>array("tinyint"=>3,"smallint"=>5,"mediumint"=>8,"int"=>10,"bigint"=>20,"decimal"=>66,"float"=>12,"double"=>21),'Date and time'=>array("date"=>10,"datetime"=>19,"timestamp"=>19,"time"=>10,"year"=>4),'Strings'=>array("char"=>255,"varchar"=>65535,"tinytext"=>255,"text"=>65535,"mediumtext"=>16777215,"longtext"=>4294967295),'Binary'=>array("bit"=>20,"binary"=>255,"varbinary"=>65535,"tinyblob"=>255,"blob"=>65535,"mediumblob"=>16777215,"longblob"=>4294967295),'Lists'=>array("enum"=>65535,"set"=>64),)as$y=>$X){$yf+=$X;$Pe[$y]=array_keys($X);}$Ef=array("unsigned","zerofill","unsigned zerofill");$wd=array("=","<",">","<=",">=","!=","LIKE","LIKE %%","REGEXP","IN","IS NULL","NOT LIKE","NOT REGEXP","NOT IN","IS NOT NULL","");$q=array("char_length","date","from_unixtime","hex","lower","round","sec_to_time","time_to_sec","upper");$jc=array("avg","count","count distinct","group_concat","max","min","sum");$wb=array(array("char"=>"md5/sha1/password/encrypt/uuid","binary"=>"md5/sha1/hex","date|time"=>"now",),array("int|float|double|decimal"=>"+/-","date"=>"+ interval/- interval","time"=>"addtime/subtime","char|text"=>"concat",));}define("SERVER",$_GET[DRIVER]);define("DB",$_GET["db"]);define("ME",preg_replace('~^[^?]*/([^?]*).*~','\\1',$_SERVER["REQUEST_URI"]).'?'.(sid()?SID.'&':'').(SERVER!==null?DRIVER."=".urlencode(SERVER).'&':'').(isset($_GET["username"])?"username=".urlencode($_GET["username"]).'&':'').(DB!=""?'db='.urlencode(DB).'&'.(isset($_GET["ns"])?"ns=".urlencode($_GET["ns"])."&":""):''));$ga="3.4.0";class
Adminer{var$operators;function
name(){return"<a href='http://www.adminer.org/' id='h1'>Adminer</a>";}function
credentials(){return
array(SERVER,$_GET["username"],get_session("pwds"));}function
permanentLogin(){return
password_file();}function
database(){return
DB;}function
databases($Yb=true){return
get_databases($Yb);}function
headers(){return
true;}function
head(){return
true;}function
loginForm(){global$qb;echo'<table cellspacing="0">
<tr><th>System<td>',html_select("auth[driver]",$qb,DRIVER,"loginDriver(this);"),'<tr><th>Server<td><input name="auth[server]" value="',h(SERVER),'" title="hostname[:port]">
<tr><th>Username<td><input id="username" name="auth[username]" value="',h($_GET["username"]),'">
<tr><th>Password<td><input type="password" name="auth[password]">
<tr><th>Database<td><input name="auth[db]" value="',h($_GET["db"]);?>">
</table>
<script type="text/javascript">
var username = document.getElementById('username');
username.focus();
username.form['auth[driver]'].onchange();
</script>
<?php

echo"<p><input type='submit' value='".'Login'."'>\n",checkbox("auth[permanent]",1,$_COOKIE["adminer_permanent"],'Permanent login')."\n";}function
login($Pc,$Rd){return
true;}function
tableName($We){return
h($We["Name"]);}function
fieldName($k,$zd=0){return'<span title="'.h($k["full_type"]).'">'.h($k["field"]).'</span>';}function
selectLinks($We,$P=""){echo'<p class="tabs">';$Oc=array("select"=>'Select data',"table"=>'Show structure');if(is_view($We))$Oc["view"]='Alter view';else$Oc["create"]='Alter table';if($P!==null)$Oc["edit"]='New item';foreach($Oc
as$y=>$X)echo" <a href='".h(ME)."$y=".urlencode($We["Name"]).($y=="edit"?$P:"")."'".bold(isset($_GET[$y])).">$X</a>";echo"\n";}function
foreignKeys($S){return
foreign_keys($S);}function
backwardKeys($S,$Ve){return
array();}function
backwardKeysPrint($xa,$K){}function
selectQuery($H){global$x;return"<p><a href='".h(remove_from_uri("page"))."&amp;page=last' title='".'Last page'."'>&gt;&gt;</a> <code class='jush-$x'>".h(str_replace("\n"," ",$H))."</code> <a href='".h(ME)."sql=".urlencode($H)."'>".'Edit'."</a></p>\n";}function
rowDescription($S){return"";}function
rowDescriptions($L,$ac){return$L;}function
selectVal($X,$_,$k){$J=($X===null?"<i>NULL</i>":(ereg("char|binary",$k["type"])&&!ereg("var",$k["type"])?"<code>$X</code>":$X));if(ereg('blob|bytea|raw|file',$k["type"])&&!is_utf8($X))$J=lang(array('%d byte','%d bytes'),strlen($X));return($_?"<a href='$_'>$J</a>":$J);}function
editVal($X,$k){return(ereg("binary",$k["type"])?reset(unpack("H*",$X)):$X);}function
selectColumnsPrint($N,$e){global$q,$jc;print_fieldset("select",'Select',$N);$s=0;$fc=array('Functions'=>$q,'Aggregation'=>$jc);foreach($N
as$y=>$X){$X=$_GET["columns"][$y];echo"<div>".html_select("columns[$s][fun]",array(-1=>"")+$fc,$X["fun"]),"(<select name='columns[$s][col]'><option>".optionlist($e,$X["col"],true)."</select>)</div>\n";$s++;}echo"<div>".html_select("columns[$s][fun]",array(-1=>"")+$fc,"","this.nextSibling.nextSibling.onchange();"),"(<select name='columns[$s][col]' onchange='selectAddRow(this);'><option>".optionlist($e,null,true)."</select>)</div>\n","</div></fieldset>\n";}function
selectSearchPrint($Z,$e,$v){print_fieldset("search",'Search',$Z);foreach($v
as$s=>$u){if($u["type"]=="FULLTEXT"){echo"(<i>".implode("</i>, <i>",array_map('h',$u["columns"]))."</i>) AGAINST"," <input name='fulltext[$s]' value='".h($_GET["fulltext"][$s])."' onchange='selectFieldChange(this.form);'>",checkbox("boolean[$s]",1,isset($_GET["boolean"][$s]),"BOOL"),"<br>\n";}}$_GET["where"]=(array)$_GET["where"];reset($_GET["where"]);$Ea="this.nextSibling.onchange();";for($s=0;$s<=count($_GET["where"]);$s++){list(,$X)=each($_GET["where"]);if(!$X||("$X[col]$X[val]"!=""&&in_array($X["op"],$this->operators))){echo"<div><select name='where[$s][col]' onchange='$Ea'><option value=''>(".'anywhere'.")".optionlist($e,$X["col"],true)."</select>",html_select("where[$s][op]",$this->operators,$X["op"],$Ea),"<input name='where[$s][val]' value='".h($X["val"])."' onchange='".($X?"selectFieldChange(this.form)":"selectAddRow(this)").";'></div>\n";}}echo"</div></fieldset>\n";}function
selectOrderPrint($zd,$e,$v){print_fieldset("sort",'Sort',$zd);$s=0;foreach((array)$_GET["order"]as$y=>$X){if(isset($e[$X])){echo"<div><select name='order[$s]' onchange='selectFieldChange(this.form);'><option>".optionlist($e,$X,true)."</select>",checkbox("desc[$s]",1,isset($_GET["desc"][$y]),'descending')."</div>\n";$s++;}}echo"<div><select name='order[$s]' onchange='selectAddRow(this);'><option>".optionlist($e,null,true)."</select>","<label><input type='checkbox' name='desc[$s]' value='1'>".'descending'."</label></div>\n";echo"</div></fieldset>\n";}function
selectLimitPrint($z){echo"<fieldset><legend>".'Limit'."</legend><div>";echo"<input name='limit' size='3' value='".h($z)."'>","</div></fieldset>\n";}function
selectLengthPrint($hf){if($hf!==null){echo"<fieldset><legend>".'Text length'."</legend><div>",'<input name="text_length" size="3" value="'.h($hf).'">',"</div></fieldset>\n";}}function
selectActionPrint($v){echo"<fieldset><legend>".'Action'."</legend><div>","<input type='submit' value='".'Select'."'>"," <span id='noindex' title='".'Full table scan'."'></span>","<script type='text/javascript'>\n","var indexColumns = ";$e=array();foreach($v
as$u){if($u["type"]!="FULLTEXT")$e[reset($u["columns"])]=1;}$e[""]=1;foreach($e
as$y=>$X)json_row($y);echo";\n","selectFieldChange(document.getElementById('form'));\n","</script>\n","</div></fieldset>\n";}function
selectCommandPrint(){return!information_schema(DB);}function
selectImportPrint(){return
true;}function
selectEmailPrint($zb,$e){}function
selectColumnsProcess($e,$v){global$q,$jc;$N=array();$hc=array();foreach((array)$_GET["columns"]as$y=>$X){if($X["fun"]=="count"||(isset($e[$X["col"]])&&(!$X["fun"]||in_array($X["fun"],$q)||in_array($X["fun"],$jc)))){$N[$y]=apply_sql_function($X["fun"],(isset($e[$X["col"]])?idf_escape($X["col"]):"*"));if(!in_array($X["fun"],$jc))$hc[]=$N[$y];}}return
array($N,$hc);}function
selectSearchProcess($l,$v){global$x;$J=array();foreach($v
as$s=>$u){if($u["type"]=="FULLTEXT"&&$_GET["fulltext"][$s]!="")$J[]="MATCH (".implode(", ",array_map('idf_escape',$u["columns"])).") AGAINST (".q($_GET["fulltext"][$s]).(isset($_GET["boolean"][$s])?" IN BOOLEAN MODE":"").")";}foreach((array)$_GET["where"]as$X){if("$X[col]$X[val]"!=""&&in_array($X["op"],$this->operators)){$Ta=" $X[op]";if(ereg('IN$',$X["op"])){$sc=process_length($X["val"]);$Ta.=" (".($sc!=""?$sc:"NULL").")";}elseif(!$X["op"])$Ta.=$X["val"];elseif($X["op"]=="LIKE %%")$Ta=" LIKE ".$this->processInput($l[$X["col"]],"%$X[val]%");elseif(!ereg('NULL$',$X["op"]))$Ta.=" ".$this->processInput($l[$X["col"]],$X["val"]);if($X["col"]!="")$J[]=idf_escape($X["col"]).$Ta;else{$Ma=array();foreach($l
as$D=>$k){if(is_numeric($X["val"])||!ereg('int|float|double|decimal',$k["type"])){$D=idf_escape($D);$Ma[]=($x=="sql"&&ereg('char|text|enum|set',$k["type"])&&!ereg('^utf8',$k["collation"])?"CONVERT($D USING utf8)":$D);}}$J[]=($Ma?"(".implode("$Ta OR ",$Ma)."$Ta)":"0");}}}return$J;}function
selectOrderProcess($l,$v){$J=array();foreach((array)$_GET["order"]as$y=>$X){if(isset($l[$X])||preg_match('~^((COUNT\\(DISTINCT |[A-Z0-9_]+\\()(`(?:[^`]|``)+`|"(?:[^"]|"")+")\\)|COUNT\\(\\*\\))$~',$X))$J[]=(isset($l[$X])?idf_escape($X):$X).(isset($_GET["desc"][$y])?" DESC":"");}return$J;}function
selectLimitProcess(){return(isset($_GET["limit"])?$_GET["limit"]:"30");}function
selectLengthProcess(){return(isset($_GET["text_length"])?$_GET["text_length"]:"100");}function
selectEmailProcess($Z,$ac){return
false;}function
messageQuery($H){global$x;static$Ya=0;restart_session();$t="sql-".($Ya++);$lc=&get_session("queries");if(strlen($H)>1e6)$H=ereg_replace('[\x80-\xFF]+$','',substr($H,0,1e6))."\n...";$lc[$_GET["db"]][]=array($H,time());return" <span class='time'>".@date("H:i:s")."</span> <a href='#$t' onclick=\"return !toggle('$t');\">".'SQL command'."</a><div id='$t' class='hidden'><pre><code class='jush-$x'>".shorten_utf8($H,1000).'</code></pre><p><a href="'.h(str_replace("db=".urlencode(DB),"db=".urlencode($_GET["db"]),ME).'sql=&history='.(count($lc[$_GET["db"]])-1)).'">'.'Edit'.'</a></div>';}function
editFunctions($k){global$wb;$J=($k["null"]?"NULL/":"");foreach($wb
as$y=>$q){if(!$y||(!isset($_GET["call"])&&(isset($_GET["select"])||where($_GET)))){foreach($q
as$F=>$X){if(!$F||ereg($F,$k["type"]))$J.="/$X";}if($y&&!ereg('set|blob|bytea|raw|file',$k["type"]))$J.="/=";}}return
explode("/",$J);}function
editInput($S,$k,$ta,$Y){if($k["type"]=="enum")return(isset($_GET["select"])?"<label><input type='radio'$ta value='-1' checked><i>".'original'."</i></label> ":"").($k["null"]?"<label><input type='radio'$ta value=''".($Y!==null||isset($_GET["select"])?"":" checked")."><i>NULL</i></label> ":"").enum_input("radio",$ta,$k,$Y,0);return"";}function
processInput($k,$Y,$p=""){if($p=="=")return$Y;$D=$k["field"];$J=($k["type"]=="bit"&&ereg("^([0-9]+|b'[0-1]+')\$",$Y)?$Y:q($Y));if(ereg('^(now|getdate|uuid)$',$p))$J="$p()";elseif(ereg('^current_(date|timestamp)$',$p))$J=$p;elseif(ereg('^([+-]|\\|\\|)$',$p))$J=idf_escape($D)." $p $J";elseif(ereg('^[+-] interval$',$p))$J=idf_escape($D)." $p ".(preg_match("~^(\\d+|'[0-9.: -]') [A-Z_]+$~i",$Y)?$Y:$J);elseif(ereg('^(addtime|subtime|concat)$',$p))$J="$p(".idf_escape($D).", $J)";elseif(ereg('^(md5|sha1|password|encrypt|hex)$',$p))$J="$p($J)";if(ereg("binary",$k["type"]))$J="unhex($J)";return$J;}function
dumpOutput(){$J=array('text'=>'open','file'=>'save');if(function_exists('gzencode'))$J['gz']='gzip';if(function_exists('bzcompress'))$J['bz2']='bzip2';return$J;}function
dumpFormat(){return
array('sql'=>'SQL','csv'=>'CSV,','csv;'=>'CSV;','tsv'=>'TSV');}function
dumpTable($S,$R,$_c=false){if($_POST["format"]!="sql"){echo"\xef\xbb\xbf";if($R)dump_csv(array_keys(fields($S)));}elseif($R){$Za=create_sql($S,$_POST["auto_increment"]);if($Za){if($R=="DROP+CREATE")echo"DROP ".($_c?"VIEW":"TABLE")." IF EXISTS ".table($S).";\n";if($_c)$Za=preg_replace('~^([A-Z =]+) DEFINER=`'.preg_replace('~@(.*)~','`@`(%|\\1)',logged_user()).'`~','\\1',$Za);echo($R!="CREATE+ALTER"?$Za:($_c?substr_replace($Za," OR REPLACE",6,0):substr_replace($Za," IF NOT EXISTS",12,0))).";\n\n";}if($R=="CREATE+ALTER"&&!$_c){$H="SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, COLLATION_NAME, COLUMN_TYPE, EXTRA, COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ".q($S)." ORDER BY ORDINAL_POSITION";echo"DELIMITER ;;
CREATE PROCEDURE adminer_alter (INOUT alter_command text) BEGIN
	DECLARE _column_name, _collation_name, after varchar(64) DEFAULT '';
	DECLARE _column_type, _column_default text;
	DECLARE _is_nullable char(3);
	DECLARE _extra varchar(30);
	DECLARE _column_comment varchar(255);
	DECLARE done, set_after bool DEFAULT 0;
	DECLARE add_columns text DEFAULT '";$l=array();$oa="";foreach(get_rows($H)as$K){$jb=$K["COLUMN_DEFAULT"];$K["default"]=($jb!==null?q($jb):"NULL");$K["after"]=q($oa);$K["alter"]=escape_string(idf_escape($K["COLUMN_NAME"])." $K[COLUMN_TYPE]".($K["COLLATION_NAME"]?" COLLATE $K[COLLATION_NAME]":"").($jb!==null?" DEFAULT ".($jb=="CURRENT_TIMESTAMP"?$jb:$K["default"]):"").($K["IS_NULLABLE"]=="YES"?"":" NOT NULL").($K["EXTRA"]?" $K[EXTRA]":"").($K["COLUMN_COMMENT"]?" COMMENT ".q($K["COLUMN_COMMENT"]):"").($oa?" AFTER ".idf_escape($oa):" FIRST"));echo", ADD $K[alter]";$l[]=$K;$oa=$K["COLUMN_NAME"];}echo"';
	DECLARE columns CURSOR FOR $H;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	SET @alter_table = '';
	OPEN columns;
	REPEAT
		FETCH columns INTO _column_name, _column_default, _is_nullable, _collation_name, _column_type, _extra, _column_comment;
		IF NOT done THEN
			SET set_after = 1;
			CASE _column_name";foreach($l
as$K)echo"
				WHEN ".q($K["COLUMN_NAME"])." THEN
					SET add_columns = REPLACE(add_columns, ', ADD $K[alter]', IF(
						_column_default <=> $K[default] AND _is_nullable = '$K[IS_NULLABLE]' AND _collation_name <=> ".(isset($K["COLLATION_NAME"])?"'$K[COLLATION_NAME]'":"NULL")." AND _column_type = ".q($K["COLUMN_TYPE"])." AND _extra = '$K[EXTRA]' AND _column_comment = ".q($K["COLUMN_COMMENT"])." AND after = $K[after]
					, '', ', MODIFY $K[alter]'));";echo"
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
dumpData($S,$R,$H){global$f,$x;$Tc=($x=="sqlite"?0:1048576);if($R){if($_POST["format"]=="sql"&&$R=="TRUNCATE+INSERT")echo
truncate_sql($S).";\n";if($_POST["format"]=="sql")$l=fields($S);$I=$f->query($H,1);if($I){$xc="";$Ca="";while($K=$I->fetch_assoc()){if($_POST["format"]!="sql"){if($R=="table"){dump_csv(array_keys($K));$R="INSERT";}dump_csv($K);}else{if(!$xc)$xc="INSERT INTO ".table($S)." (".implode(", ",array_map('idf_escape',array_keys($K))).") VALUES";foreach($K
as$y=>$X)$K[$y]=($X!==null?(ereg('int|float|double|decimal|bit',$l[$y]["type"])?$X:q($X)):"NULL");$M=implode(",\t",$K);if($R=="INSERT+UPDATE"){$P=array();foreach($K
as$y=>$X)$P[]=idf_escape($y)." = $X";echo"$xc ($M) ON DUPLICATE KEY UPDATE ".implode(", ",$P).";\n";}else{$M=($Tc?"\n":" ")."($M)";if(!$Ca)$Ca=$xc.$M;elseif(strlen($Ca)+4+strlen($M)<$Tc)$Ca.=",$M";else{echo"$Ca;\n";$Ca=$xc.$M;}}}}if($_POST["format"]=="sql"&&$R!="INSERT+UPDATE"&&$Ca){$Ca.=";\n";echo$Ca;}}elseif($_POST["format"]=="sql")echo"-- ".str_replace("\n"," ",$f->error)."\n";}}function
dumpFilename($pc){return
friendly_url($pc!=""?$pc:(SERVER!=""?SERVER:"localhost"));}function
dumpHeaders($pc,$fd=false){$Hd=$_POST["output"];$Qb=($_POST["format"]=="sql"?"sql":($fd?"tar":"csv"));header("Content-Type: ".($Hd=="bz2"?"application/x-bzip":($Hd=="gz"?"application/x-gzip":($Qb=="tar"?"application/x-tar":($Qb=="sql"||$Hd!="file"?"text/plain":"text/csv")."; charset=utf-8"))));if($Hd=="bz2")ob_start('bzcompress',1e6);if($Hd=="gz")ob_start('gzencode',1e6);return$Qb;}function
homepage(){echo'<p>'.($_GET["ns"]==""?'<a href="'.h(ME).'database=">'.'Alter database'."</a>\n":""),(support("scheme")?"<a href='".h(ME)."scheme='>".($_GET["ns"]!=""?'Alter schema':'Create schema')."</a>\n":""),($_GET["ns"]!==""?'<a href="'.h(ME).'schema=">'.'Database schema'."</a>\n":""),(support("privileges")?"<a href='".h(ME)."privileges='>".'Privileges'."</a>\n":"");return
true;}function
navigation($ed){global$ga,$f,$U,$x,$qb;echo'<h1>
',$this->name(),' <span class="version">',$ga,'</span>
<a href="http://www.adminer.org/#download" id="version">',(version_compare($ga,$_COOKIE["adminer_version"])<0?h($_COOKIE["adminer_version"]):""),'</a>
</h1>
';if($ed=="auth"){$Xb=true;foreach((array)$_SESSION["pwds"]as$pb=>$Ge){foreach($Ge
as$O=>$Jf){foreach($Jf
as$If=>$Rd){if($Rd!==null){if($Xb){echo"<p>\n";$Xb=false;}echo"<a href='".h(auth_url($pb,$O,$If))."'>($qb[$pb]) ".h($If.($O!=""?"@$O":""))."</a><br>\n";}}}}}else{$h=$this->databases();echo'<form action="" method="post">
<p class="logout">
';if(DB==""||!$ed){echo"<a href='".h(ME)."sql='".bold(isset($_GET["sql"])).">".'SQL command'."</a>\n";if(support("dump"))echo"<a href='".h(ME)."dump=".urlencode(isset($_GET["table"])?$_GET["table"]:$_GET["select"])."' id='dump'".bold(isset($_GET["dump"])).">".'Dump'."</a>\n";}echo'<input type="submit" name="logout" value="Logout">
<input type="hidden" name="token" value="',$U,'">
</p>
</form>
<form action="">
<p>
';hidden_fields_get();echo($h?html_select("db",array(""=>"(".'database'.")")+$h,DB,"this.form.submit();"):'<input name="db" value="'.h(DB).'">'),'<input type="submit" value="Use"',($h?" class='hidden'":""),'>
';if($ed!="db"&&DB!=""&&$f->select_db(DB)){if($_GET["ns"]!==""&&!$ed){echo'<p><a href="'.h(ME).'create="'.bold($_GET["create"]==="").">".'Create new table'."</a>\n";$bf=tables_list();if(!$bf)echo"<p class='message'>".'No tables.'."\n";else{$this->tablesPrint($bf);$Oc=array();foreach($bf
as$S=>$V)$Oc[]=preg_quote($S,'/');echo"<script type='text/javascript'>\n","var jushLinks = { $x: [ '".js_escape(ME)."table=\$&', /\\b(".implode("|",$Oc).")\\b/g ] };\n";foreach(array("bac","bra","sqlite_quo","mssql_bra")as$X)echo"jushLinks.$X = jushLinks.$x;\n";echo"</script>\n";}}}echo(isset($_GET["sql"])?'<input type="hidden" name="sql" value="">':(isset($_GET["schema"])?'<input type="hidden" name="schema" value="">':(isset($_GET["dump"])?'<input type="hidden" name="dump" value="">':""))),"</p></form>\n";}}function
tablesPrint($bf){echo"<p id='tables'>\n";foreach($bf
as$S=>$V){echo'<a href="'.h(ME).'select='.urlencode($S).'"'.bold($_GET["select"]==$S).">".'select'."</a> ",'<a href="'.h(ME).'table='.urlencode($S).'"'.bold($_GET["table"]==$S)." title='".'Show structure'."'>".$this->tableName(array("Name"=>$S))."</a><br>\n";}}}$b=(function_exists('adminer_object')?adminer_object():new
Adminer);if($b->operators===null)$b->operators=$wd;function
page_header($kf,$j="",$Ba=array(),$lf=""){global$ca,$b,$f,$qb;header("Content-Type: text/html; charset=utf-8");if($b->headers()){header("X-Frame-Options: deny");header("X-XSS-Protection: 0");}$mf=$kf.($lf!=""?": ".h($lf):"");$nf=strip_tags($mf.(SERVER!=""&&SERVER!="localhost"?h(" - ".SERVER):"")." - ".$b->name());echo'<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html lang="en" dir="ltr">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="robots" content="noindex">
<title>',$nf,'</title>
<link rel="stylesheet" type="text/css" href="',h(preg_replace("~\\?.*~","",ME))."?file=default.css&amp;version=3.4.0",'">
<script type="text/javascript" src="',h(preg_replace("~\\?.*~","",ME))."?file=functions.js&amp;version=3.4.0",'"></script>
';if($b->head()){echo'<link rel="shortcut icon" type="image/x-icon" href="',h(preg_replace("~\\?.*~","",ME))."?file=favicon.ico&amp;version=3.4.0",'" id="favicon">
';if(file_exists("adminer.css")){echo'<link rel="stylesheet" type="text/css" href="adminer.css">
';}}echo'
<body class="ltr nojs" onkeydown="bodyKeydown(event);" onload="bodyLoad(\'',(is_object($f)?substr($f->server_info,0,3):""),'\');',(isset($_COOKIE["adminer_version"])?"":" verifyVersion();"),'">
<script type="text/javascript">
document.body.className = document.body.className.replace(/ nojs/, \' js\');
</script>

<div id="content">
';if($Ba!==null){$_=substr(preg_replace('~(username|db|ns)=[^&]*&~','',ME),0,-1);echo'<p id="breadcrumb"><a href="'.h($_?$_:".").'">'.$qb[DRIVER].'</a> &raquo; ';$_=substr(preg_replace('~(db|ns)=[^&]*&~','',ME),0,-1);$O=(SERVER!=""?h(SERVER):'Server');if($Ba===false)echo"$O\n";else{echo"<a href='".($_?h($_):".")."' accesskey='1' title='Alt+Shift+1'>$O</a> &raquo; ";if($_GET["ns"]!=""||(DB!=""&&is_array($Ba)))echo'<a href="'.h($_."&db=".urlencode(DB).(support("scheme")?"&ns=":"")).'">'.h(DB).'</a> &raquo; ';if(is_array($Ba)){if($_GET["ns"]!="")echo'<a href="'.h(substr(ME,0,-1)).'">'.h($_GET["ns"]).'</a> &raquo; ';foreach($Ba
as$y=>$X){$lb=(is_array($X)?$X[1]:$X);if($lb!="")echo'<a href="'.h(ME."$y=").urlencode(is_array($X)?$X[0]:$X).'">'.h($lb).'</a> &raquo; ';}}echo"$kf\n";}}echo"<h2>$mf</h2>\n";restart_session();$Gf=preg_replace('~^[^?]*~','',$_SERVER["REQUEST_URI"]);$cd=$_SESSION["messages"][$Gf];if($cd){echo"<div class='message'>".implode("</div>\n<div class='message'>",$cd)."</div>\n";unset($_SESSION["messages"][$Gf]);}$h=&get_session("dbs");if(DB!=""&&$h&&!in_array(DB,$h,true))$h=null;if($j)echo"<div class='error'>$j</div>\n";define("PAGE_HEADER",1);}function
page_footer($ed=""){global$b;echo'</div>

<div id="menu">
';$b->navigation($ed);echo'</div>
';}function
int32($C){while($C>=2147483648)$C-=4294967296;while($C<=-2147483649)$C+=4294967296;return(int)$C;}function
long2str($W,$Of){$M='';foreach($W
as$X)$M.=pack('V',$X);if($Of)return
substr($M,0,end($W));return$M;}function
str2long($M,$Of){$W=array_values(unpack('V*',str_pad($M,4*ceil(strlen($M)/4),"\0")));if($Of)$W[]=strlen($M);return$W;}function
xxtea_mx($Sf,$Rf,$Te,$Bc){return
int32((($Sf>>5&0x7FFFFFF)^$Rf<<2)+(($Rf>>3&0x1FFFFFFF)^$Sf<<4))^int32(($Te^$Rf)+($Bc^$Sf));}function
encrypt_string($Oe,$y){if($Oe=="")return"";$y=array_values(unpack("V*",pack("H*",md5($y))));$W=str2long($Oe,true);$C=count($W)-1;$Sf=$W[$C];$Rf=$W[0];$G=floor(6+52/($C+1));$Te=0;while($G-->0){$Te=int32($Te+0x9E3779B9);$vb=$Te>>2&3;for($Id=0;$Id<$C;$Id++){$Rf=$W[$Id+1];$gd=xxtea_mx($Sf,$Rf,$Te,$y[$Id&3^$vb]);$Sf=int32($W[$Id]+$gd);$W[$Id]=$Sf;}$Rf=$W[0];$gd=xxtea_mx($Sf,$Rf,$Te,$y[$Id&3^$vb]);$Sf=int32($W[$C]+$gd);$W[$C]=$Sf;}return
long2str($W,false);}function
decrypt_string($Oe,$y){if($Oe=="")return"";$y=array_values(unpack("V*",pack("H*",md5($y))));$W=str2long($Oe,false);$C=count($W)-1;$Sf=$W[$C];$Rf=$W[0];$G=floor(6+52/($C+1));$Te=int32($G*0x9E3779B9);while($Te){$vb=$Te>>2&3;for($Id=$C;$Id>0;$Id--){$Sf=$W[$Id-1];$gd=xxtea_mx($Sf,$Rf,$Te,$y[$Id&3^$vb]);$Rf=int32($W[$Id]-$gd);$W[$Id]=$Rf;}$Sf=$W[$C];$gd=xxtea_mx($Sf,$Rf,$Te,$y[$Id&3^$vb]);$Rf=int32($W[0]-$gd);$W[0]=$Rf;$Te=int32($Te-0x9E3779B9);}return
long2str($W,true);}$f='';$U=$_SESSION["token"];if(!$_SESSION["token"])$_SESSION["token"]=rand(1,1e6);$Sd=array();if($_COOKIE["adminer_permanent"]){foreach(explode(" ",$_COOKIE["adminer_permanent"])as$X){list($y)=explode(":",$X);$Sd[$y]=$X;}}$c=$_POST["auth"];if($c){session_regenerate_id();$_SESSION["pwds"][$c["driver"]][$c["server"]][$c["username"]]=$c["password"];if($c["permanent"]){$y=base64_encode($c["driver"])."-".base64_encode($c["server"])."-".base64_encode($c["username"]);$ce=$b->permanentLogin();$Sd[$y]="$y:".base64_encode($ce?encrypt_string($c["password"],$ce):"");cookie("adminer_permanent",implode(" ",$Sd));}if(count($_POST)==1||DRIVER!=$c["driver"]||SERVER!=$c["server"]||$_GET["username"]!==$c["username"]||DB!=$c["db"])redirect(auth_url($c["driver"],$c["server"],$c["username"],$c["db"]));}elseif($_POST["logout"]){if($U&&$_POST["token"]!=$U){page_header('Logout','Invalid CSRF token. Send the form again.');page_footer("db");exit;}else{foreach(array("pwds","dbs","queries")as$y)set_session($y,null);$y=base64_encode(DRIVER)."-".base64_encode(SERVER)."-".base64_encode($_GET["username"]);if($Sd[$y]){unset($Sd[$y]);cookie("adminer_permanent",implode(" ",$Sd));}redirect(substr(preg_replace('~(username|db|ns)=[^&]*&~','',ME),0,-1),'Logout successful.');}}elseif($Sd&&!$_SESSION["pwds"]){session_regenerate_id();$ce=$b->permanentLogin();foreach($Sd
as$y=>$X){list(,$Ia)=explode(":",$X);list($pb,$O,$If)=array_map('base64_decode',explode("-",$y));$_SESSION["pwds"][$pb][$O][$If]=decrypt_string(base64_decode($Ia),$ce);}}function
auth_error($Kb=null){global$f,$b,$U;$He=session_name();$j="";if(!$_COOKIE[$He]&&$_GET[$He]&&ini_bool("session.use_only_cookies"))$j='Session support must be enabled.';elseif(isset($_GET["username"])){if(($_COOKIE[$He]||$_GET[$He])&&!$U)$j='Session expired, please login again.';else{$Rd=&get_session("pwds");if($Rd!==null){$j=h($Kb?$Kb->getMessage():(is_string($f)?$f:'Invalid credentials.'));$Rd=null;}}}page_header('Login',$j,null);echo"<form action='' method='post'>\n";$b->loginForm();echo"<div>";hidden_fields($_POST,array("auth"));echo"</div>\n","</form>\n";page_footer("auth");}if(isset($_GET["username"])){if(!class_exists("Min_DB")){unset($_SESSION["pwds"][DRIVER]);page_header('No extension',sprintf('None of the supported PHP extensions (%s) are available.',implode(", ",$Xd)),false);page_footer("auth");exit;}$f=connect();}if(is_string($f)||!$b->login($_GET["username"],get_session("pwds"))){auth_error();exit;}$U=$_SESSION["token"];if($c&&$_POST["token"])$_POST["token"]=$U;$j=($_POST?($_POST["token"]==$U?"":'Invalid CSRF token. Send the form again.'):($_SERVER["REQUEST_METHOD"]!="POST"?"":sprintf('Too big POST data. Reduce the data or increase the %s configuration directive.','"post_max_size"')));function
connect_error(){global$b,$f,$U,$j,$qb;$h=array();if(DB!="")page_header('Database'.": ".h(DB),'Invalid database.',true);else{if($_POST["db"]&&!$j)queries_redirect(substr(ME,0,-1),'Databases have been dropped.',drop_databases($_POST["db"]));page_header('Select database',$j,false);echo"<p><a href='".h(ME)."database='>".'Create new database'."</a>\n";foreach(array('privileges'=>'Privileges','processlist'=>'Process list','variables'=>'Variables','status'=>'Status',)as$y=>$X){if(support($y))echo"<a href='".h(ME)."$y='>$X</a>\n";}echo"<p>".sprintf('%s version: %s through PHP extension %s',$qb[DRIVER],"<b>$f->server_info</b>","<b>$f->extension</b>")."\n","<p>".sprintf('Logged as: %s',"<b>".h(logged_user())."</b>")."\n";if($_GET["refresh"])set_session("dbs",null);$h=$b->databases();if($h){$Be=support("scheme");$d=collations();echo"<form action='' method='post'>\n","<table cellspacing='0' class='checkable' onclick='tableClick(event);'>\n","<thead><tr><td>&nbsp;<th>".'Database'."<td>".'Collation'."<td>".'Tables'."</thead>\n";foreach($h
as$i){$ve=h(ME)."db=".urlencode($i);echo"<tr".odd()."><td>".checkbox("db[]",$i,in_array($i,(array)$_POST["db"])),"<th><a href='$ve'>".h($i)."</a>","<td><a href='$ve".($Be?"&amp;ns=":"")."&amp;database=' title='".'Alter database'."'>".nbsp(db_collation($i,$d))."</a>","<td align='right'><a href='$ve&amp;schema=' id='tables-".h($i)."' title='".'Database schema'."'>?</a>","\n";}echo"</table>\n","<script type='text/javascript'>tableCheck();</script>\n","<p><input type='submit' name='drop' value='".'Drop'."'".confirm("formChecked(this, /db/)").">\n","<input type='hidden' name='token' value='$U'>\n","<a href='".h(ME)."refresh=1'>".'Refresh'."</a>\n","</form>\n";}}page_footer("db");if($h)echo"<script type='text/javascript'>ajaxSetHtml('".js_escape(ME)."script=connect');</script>\n";}if(isset($_GET["status"]))$_GET["variables"]=$_GET["status"];if(!(DB!=""?$f->select_db(DB):isset($_GET["sql"])||isset($_GET["dump"])||isset($_GET["database"])||isset($_GET["processlist"])||isset($_GET["privileges"])||isset($_GET["user"])||isset($_GET["variables"])||$_GET["script"]=="connect")){if(DB!="")set_session("dbs",null);connect_error();exit;}function
select($I,$g=null,$oc="",$Bd=array()){$Oc=array();$v=array();$e=array();$_a=array();$yf=array();$J=array();odd('');for($s=0;$K=$I->fetch_row();$s++){if(!$s){echo"<table cellspacing='0' class='nowrap'>\n","<thead><tr>";for($w=0;$w<count($K);$w++){$k=$I->fetch_field();$D=$k->name;$Ad=$k->orgtable;$_d=$k->orgname;$J[$k->table]=$Ad;if($oc)$Oc[$w]=($D=="table"?"table=":($D=="possible_keys"?"indexes=":null));elseif($Ad!=""){if(!isset($v[$Ad])){$v[$Ad]=array();foreach(indexes($Ad,$g)as$u){if($u["type"]=="PRIMARY"){$v[$Ad]=array_flip($u["columns"]);break;}}$e[$Ad]=$v[$Ad];}if(isset($e[$Ad][$_d])){unset($e[$Ad][$_d]);$v[$Ad][$_d]=$w;$Oc[$w]=$Ad;}}if($k->charsetnr==63)$_a[$w]=true;$yf[$w]=$k->type;$D=h($D);echo"<th".($Ad!=""||$k->name!=$_d?" title='".h(($Ad!=""?"$Ad.":"").$_d)."'":"").">".($oc?"<a href='$oc".strtolower($D)."' target='_blank' rel='noreferrer'>$D</a>":$D);}echo"</thead>\n";}echo"<tr".odd().">";foreach($K
as$y=>$X){if($X===null)$X="<i>NULL</i>";elseif($_a[$y]&&!is_utf8($X))$X="<i>".lang(array('%d byte','%d bytes'),strlen($X))."</i>";elseif(!strlen($X))$X="&nbsp;";else{$X=h($X);if($yf[$y]==254)$X="<code>$X</code>";}if(isset($Oc[$y])&&!$e[$Oc[$y]]){if($oc){$S=$K[array_search("table=",$Oc)];$_=$Oc[$y].urlencode($Bd[$S]!=""?$Bd[$S]:$S);}else{$_="edit=".urlencode($Oc[$y]);foreach($v[$Oc[$y]]as$Ja=>$w)$_.="&where".urlencode("[".bracket_escape($Ja)."]")."=".urlencode($K[$w]);}$X="<a href='".h(ME.$_)."'>$X</a>";}echo"<td>$X";}}echo($s?"</table>":"<p class='message'>".'No rows.')."\n";return$J;}function
referencable_primary($De){$J=array();foreach(table_status()as$Xe=>$S){if($Xe!=$De&&fk_support($S)){foreach(fields($Xe)as$k){if($k["primary"]){if($J[$Xe]){unset($J[$Xe]);break;}$J[$Xe]=$k;}}}}return$J;}function
textarea($D,$Y,$L=10,$Ma=80){echo"<textarea name='$D' rows='$L' cols='$Ma' class='sqlarea' spellcheck='false' wrap='off' onkeydown='return textareaKeydown(this, event);'>";if(is_array($Y)){foreach($Y
as$X)echo
h($X[0])."\n\n\n";}else
echo
h($Y);echo"</textarea>";}function
format_time($Le,$Bb){return" <span class='time'>(".sprintf('%.3f s',max(0,array_sum(explode(" ",$Bb))-array_sum(explode(" ",$Le)))).")</span>";}function
edit_type($y,$k,$d,$n=array()){global$Pe,$yf,$Ef,$sd;echo'<td><select name="',$y,'[type]" class="type" onfocus="lastType = selectValue(this);" onchange="editingTypeChange(this);">',optionlist((!$k["type"]||isset($yf[$k["type"]])?array():array($k["type"]))+$Pe+($n?array('Foreign keys'=>$n):array()),$k["type"]),'</select>
<td><input name="',$y,'[length]" value="',h($k["length"]),'" size="3" onfocus="editingLengthFocus(this);"><td class="options">',"<select name='$y"."[collation]'".(ereg('(char|text|enum|set)$',$k["type"])?"":" class='hidden'").'><option value="">('.'collation'.')'.optionlist($d,$k["collation"]).'</select>',($Ef?"<select name='$y"."[unsigned]'".(!$k["type"]||ereg('(int|float|double|decimal)$',$k["type"])?"":" class='hidden'").'><option>'.optionlist($Ef,$k["unsigned"]).'</select>':''),($n?"<select name='$y"."[on_delete]'".(ereg("`",$k["type"])?"":" class='hidden'")."><option value=''>(".'ON DELETE'.")".optionlist(explode("|",$sd),$k["on_delete"])."</select> ":" ");}function
process_length($Lc){global$Eb;return(preg_match("~^\\s*(?:$Eb)(?:\\s*,\\s*(?:$Eb))*\\s*\$~",$Lc)&&preg_match_all("~$Eb~",$Lc,$Rc)?implode(",",$Rc[0]):preg_replace('~[^0-9,+-]~','',$Lc));}function
process_type($k,$Ka="COLLATE"){global$Ef;return" $k[type]".($k["length"]!=""?"(".process_length($k["length"]).")":"").(ereg('int|float|double|decimal',$k["type"])&&in_array($k["unsigned"],$Ef)?" $k[unsigned]":"").(ereg('char|text|enum|set',$k["type"])&&$k["collation"]?" $Ka ".q($k["collation"]):"");}function
process_field($k,$wf){return
array(idf_escape(trim($k["field"])),process_type($wf),($k["null"]?" NULL":" NOT NULL"),(isset($k["default"])?" DEFAULT ".(($k["type"]=="timestamp"&&eregi('^CURRENT_TIMESTAMP$',$k["default"]))||($k["type"]=="bit"&&ereg("^([0-9]+|b'[0-1]+')\$",$k["default"]))?$k["default"]:q($k["default"])):""),($k["on_update"]?" ON UPDATE $k[on_update]":""),(support("comment")&&$k["comment"]!=""?" COMMENT ".q($k["comment"]):""),($k["auto_increment"]?auto_increment():null),);}function
type_class($V){foreach(array('char'=>'text','date'=>'time|year','binary'=>'blob','enum'=>'set',)as$y=>$X){if(ereg("$y|$X",$V))return" class='$y'";}}function
edit_fields($l,$d,$V="TABLE",$qa=0,$n=array(),$Ra=false){global$vc;echo'<thead><tr class="wrap">
';if($V=="PROCEDURE"){echo'<td>&nbsp;';}echo'<th>',($V=="TABLE"?'Column name':'Parameter name'),'<td>Type<textarea id="enum-edit" rows="4" cols="12" wrap="off" style="display: none;" onblur="editingLengthBlur(this);"></textarea>
<td>Length
<td>Options
';if($V=="TABLE"){echo'<td>NULL
<td><input type="radio" name="auto_increment_col" value=""><acronym title="Auto Increment">AI</acronym>
<td',($_POST["defaults"]?"":" class='hidden'"),'>Default values
',(support("comment")?"<td".($Ra?"":" class='hidden'").">".'Comment':"");}echo'<td>',"<input type='image' name='add[".(support("move_col")?0:count($l))."]' src='".h(preg_replace("~\\?.*~","",ME))."?file=plus.gif&amp;version=3.4.0' alt='+' title='".'Add next'."'>",'<script type="text/javascript">row_count = ',count($l),';</script>
</thead>
<tbody onkeydown="return editingKeydown(event);">
';foreach($l
as$s=>$k){$s++;$Cd=$k[($_POST?"orig":"field")];$nb=(isset($_POST["add"][$s-1])||(isset($k["field"])&&!$_POST["drop_col"][$s]))&&(support("drop_col")||$Cd=="");echo'<tr',($nb?"":" style='display: none;'"),'>
',($V=="PROCEDURE"?"<td>".html_select("fields[$s][inout]",explode("|",$vc),$k["inout"]):""),'<th>';if($nb){echo'<input name="fields[',$s,'][field]" value="',h($k["field"]),'" onchange="',($k["field"]!=""||count($l)>1?"":"editingAddRow(this, $qa); "),'editingNameChange(this);" maxlength="64">';}echo'<input type="hidden" name="fields[',$s,'][orig]" value="',h($Cd),'">
';edit_type("fields[$s]",$k,$d,$n);if($V=="TABLE"){echo'<td>',checkbox("fields[$s][null]",1,$k["null"]),'<td><input type="radio" name="auto_increment_col" value="',$s,'"';if($k["auto_increment"]){echo' checked';}?> onclick="var field = this.form['fields[' + this.value + '][field]']; if (!field.value) { field.value = 'id'; field.onchange(); }">
<td<?php echo($_POST["defaults"]?"":" class='hidden'"),'>',checkbox("fields[$s][has_default]",1,$k["has_default"]),'<input name="fields[',$s,'][default]" value="',h($k["default"]),'" onchange="this.previousSibling.checked = true;">
',(support("comment")?"<td".($Ra?"":" class='hidden'")."><input name='fields[$s][comment]' value='".h($k["comment"])."' maxlength='255'>":"");}echo"<td>",(support("move_col")?"<input type='image' name='add[$s]' src='".h(preg_replace("~\\?.*~","",ME))."?file=plus.gif&amp;version=3.4.0' alt='+' title='".'Add next'."' onclick='return !editingAddRow(this, $qa, 1);'>&nbsp;"."<input type='image' name='up[$s]' src='".h(preg_replace("~\\?.*~","",ME))."?file=up.gif&amp;version=3.4.0' alt='^' title='".'Move up'."'>&nbsp;"."<input type='image' name='down[$s]' src='".h(preg_replace("~\\?.*~","",ME))."?file=down.gif&amp;version=3.4.0' alt='v' title='".'Move down'."'>&nbsp;":""),($Cd==""||support("drop_col")?"<input type='image' name='drop_col[$s]' src='".h(preg_replace("~\\?.*~","",ME))."?file=cross.gif&amp;version=3.4.0' alt='x' title='".'Remove'."' onclick='return !editingRemoveRow(this);'>":""),"\n";}}function
process_fields(&$l){ksort($l);$nd=0;if($_POST["up"]){$Fc=0;foreach($l
as$y=>$k){if(key($_POST["up"])==$y){unset($l[$y]);array_splice($l,$Fc,0,array($k));break;}if(isset($k["field"]))$Fc=$nd;$nd++;}}if($_POST["down"]){$o=false;foreach($l
as$y=>$k){if(isset($k["field"])&&$o){unset($l[key($_POST["down"])]);array_splice($l,$nd,0,array($o));break;}if(key($_POST["down"])==$y)$o=$k;$nd++;}}$l=array_values($l);if($_POST["add"])array_splice($l,key($_POST["add"]),0,array(array()));}function
normalize_enum($B){return"'".str_replace("'","''",addcslashes(stripcslashes(str_replace($B[0][0].$B[0][0],$B[0][0],substr($B[0],1,-1))),'\\'))."'";}function
grant($r,$ee,$e,$rd){if(!$ee)return
true;if($ee==array("ALL PRIVILEGES","GRANT OPTION"))return($r=="GRANT"?queries("$r ALL PRIVILEGES$rd WITH GRANT OPTION"):queries("$r ALL PRIVILEGES$rd")&&queries("$r GRANT OPTION$rd"));return
queries("$r ".preg_replace('~(GRANT OPTION)\\([^)]*\\)~','\\1',implode("$e, ",$ee).$e).$rd);}function
drop_create($rb,$Za,$A,$bd,$Zc,$ad,$D){if($_POST["drop"])return
query_redirect($rb,$A,$bd,true,!$_POST["dropped"]);$sb=$D!=""&&($_POST["dropped"]||queries($rb));$bb=queries($Za);if(!queries_redirect($A,($D!=""?$Zc:$ad),$bb)&&$sb)redirect(null,$bd);return$sb;}function
tar_file($Vb,$Va){$J=pack("a100a8a8a8a12a12",$Vb,644,0,0,decoct(strlen($Va)),decoct(time()));$Ha=8*32;for($s=0;$s<strlen($J);$s++)$Ha+=ord($J[$s]);$J.=sprintf("%06o",$Ha)."\0 ";return$J.str_repeat("\0",512-strlen($J)).$Va.str_repeat("\0",511-(strlen($Va)+511)%
512);}function
ini_bytes($uc){$X=ini_get($uc);switch(strtolower(substr($X,-1))){case'g':$X*=1024;case'm':$X*=1024;case'k':$X*=1024;}return$X;}session_cache_limiter("");if(!ini_bool("session.use_cookies")||@ini_set("session.use_cookies",false)!==false)session_write_close();$sd="RESTRICT|NO ACTION|CASCADE|SET NULL|SET DEFAULT";$Eb="'(?:''|[^'\\\\]|\\\\.)*+'";$vc="IN|OUT|INOUT";if(isset($_GET["select"])&&($_POST["edit"]||$_POST["clone"])&&!$_POST["save"])$_GET["edit"]=$_GET["select"];if(isset($_GET["callf"]))$_GET["call"]=$_GET["callf"];if(isset($_GET["function"]))$_GET["procedure"]=$_GET["function"];if(isset($_GET["download"])){$a=$_GET["download"];header("Content-Type: application/octet-stream");header("Content-Disposition: attachment; filename=".friendly_url("$a-".implode("_",$_GET["where"])).".".friendly_url($_GET["field"]));echo$f->result("SELECT".limit(idf_escape($_GET["field"])." FROM ".table($a)," WHERE ".where($_GET),1));exit;}elseif(isset($_GET["table"])){$a=$_GET["table"];$l=fields($a);if(!$l)$j=error();$T=($l?table_status($a):array());page_header(($l&&is_view($T)?'View':'Table').": ".h($a),$j);$b->selectLinks($T);$Qa=$T["Comment"];if($Qa!="")echo"<p>".'Comment'.": ".h($Qa)."\n";if($l){echo"<table cellspacing='0'>\n","<thead><tr><th>".'Column'."<td>".'Type'.(support("comment")?"<td>".'Comment':"")."</thead>\n";foreach($l
as$k){echo"<tr".odd()."><th>".h($k["field"]),"<td title='".h($k["collation"])."'>".h($k["full_type"]).($k["null"]?" <i>NULL</i>":"").($k["auto_increment"]?" <i>".'Auto Increment'."</i>":""),(isset($k["default"])?" [<b>".h($k["default"])."</b>]":""),(support("comment")?"<td>".nbsp($k["comment"]):""),"\n";}echo"</table>\n";if(!is_view($T)){echo"<h3>".'Indexes'."</h3>\n";$v=indexes($a);if($v){echo"<table cellspacing='0'>\n";foreach($v
as$D=>$u){ksort($u["columns"]);$be=array();foreach($u["columns"]as$y=>$X)$be[]="<i>".h($X)."</i>".($u["lengths"][$y]?"(".$u["lengths"][$y].")":"");echo"<tr title='".h($D)."'><th>$u[type]<td>".implode(", ",$be)."\n";}echo"</table>\n";}echo'<p><a href="'.h(ME).'indexes='.urlencode($a).'">'.'Alter indexes'."</a>\n";if(fk_support($T)){echo"<h3>".'Foreign keys'."</h3>\n";$n=foreign_keys($a);if($n){echo"<table cellspacing='0'>\n","<thead><tr><th>".'Source'."<td>".'Target'."<td>".'ON DELETE'."<td>".'ON UPDATE'.($x!="sqlite"?"<td>&nbsp;":"")."</thead>\n";foreach($n
as$D=>$m){echo"<tr title='".h($D)."'>","<th><i>".implode("</i>, <i>",array_map('h',$m["source"]))."</i>","<td><a href='".h($m["db"]!=""?preg_replace('~db=[^&]*~',"db=".urlencode($m["db"]),ME):($m["ns"]!=""?preg_replace('~ns=[^&]*~',"ns=".urlencode($m["ns"]),ME):ME))."table=".urlencode($m["table"])."'>".($m["db"]!=""?"<b>".h($m["db"])."</b>.":"").($m["ns"]!=""?"<b>".h($m["ns"])."</b>.":"").h($m["table"])."</a>","(<i>".implode("</i>, <i>",array_map('h',$m["target"]))."</i>)","<td>".nbsp($m["on_delete"])."\n","<td>".nbsp($m["on_update"])."\n";if($x!="sqlite")echo'<td><a href="'.h(ME.'foreign='.urlencode($a).'&name='.urlencode($D)).'">'.'Alter'.'</a>';}echo"</table>\n";}if($x!="sqlite")echo'<p><a href="'.h(ME).'foreign='.urlencode($a).'">'.'Add foreign key'."</a>\n";}if(support("trigger")){echo"<h3>".'Triggers'."</h3>\n";$vf=triggers($a);if($vf){echo"<table cellspacing='0'>\n";foreach($vf
as$y=>$X)echo"<tr valign='top'><td>$X[0]<td>$X[1]<th>".h($y)."<td><a href='".h(ME.'trigger='.urlencode($a).'&name='.urlencode($y))."'>".'Alter'."</a>\n";echo"</table>\n";}echo'<p><a href="'.h(ME).'trigger='.urlencode($a).'">'.'Add trigger'."</a>\n";}}}}elseif(isset($_GET["schema"])){page_header('Database schema',"",array(),DB.($_GET["ns"]?".$_GET[ns]":""));$Ye=array();$Ze=array();$D="adminer_schema";$ea=($_GET["schema"]?$_GET["schema"]:$_COOKIE[($_COOKIE["$D-".DB]?"$D-".DB:$D)]);preg_match_all('~([^:]+):([-0-9.]+)x([-0-9.]+)(_|$)~',$ea,$Rc,PREG_SET_ORDER);foreach($Rc
as$s=>$B){$Ye[$B[1]]=array($B[2],$B[3]);$Ze[]="\n\t'".js_escape($B[1])."': [ $B[2], $B[3] ]";}$of=0;$za=-1;$Ae=array();$oe=array();$Jc=array();foreach(table_status()as$T){if(!isset($T["Engine"]))continue;$Ud=0;$Ae[$T["Name"]]["fields"]=array();foreach(fields($T["Name"])as$D=>$k){$Ud+=1.25;$k["pos"]=$Ud;$Ae[$T["Name"]]["fields"][$D]=$k;}$Ae[$T["Name"]]["pos"]=($Ye[$T["Name"]]?$Ye[$T["Name"]]:array($of,0));foreach($b->foreignKeys($T["Name"])as$X){if(!$X["db"]){$Hc=$za;if($Ye[$T["Name"]][1]||$Ye[$X["table"]][1])$Hc=min(floatval($Ye[$T["Name"]][1]),floatval($Ye[$X["table"]][1]))-1;else$za-=.1;while($Jc[(string)$Hc])$Hc-=.0001;$Ae[$T["Name"]]["references"][$X["table"]][(string)$Hc]=array($X["source"],$X["target"]);$oe[$X["table"]][$T["Name"]][(string)$Hc]=$X["target"];$Jc[(string)$Hc]=true;}}$of=max($of,$Ae[$T["Name"]]["pos"][0]+2.5+$Ud);}echo'<div id="schema" style="height: ',$of,'em;" onselectstart="return false;">
<script type="text/javascript">
var tablePos = {',implode(",",$Ze)."\n",'};
var em = document.getElementById(\'schema\').offsetHeight / ',$of,';
document.onmousemove = schemaMousemove;
document.onmouseup = function (ev) {
	schemaMouseup(ev, \'',js_escape(DB),'\');
};
</script>
';foreach($Ae
as$D=>$S){echo"<div class='table' style='top: ".$S["pos"][0]."em; left: ".$S["pos"][1]."em;' onmousedown='schemaMousedown(this, event);'>",'<a href="'.h(ME).'table='.urlencode($D).'"><b>'.h($D)."</b></a>";foreach($S["fields"]as$k){$X='<span'.type_class($k["type"]).' title="'.h($k["full_type"].($k["null"]?" NULL":'')).'">'.h($k["field"]).'</span>';echo"<br>".($k["primary"]?"<i>$X</i>":$X);}foreach((array)$S["references"]as$ff=>$pe){foreach($pe
as$Hc=>$le){$Ic=$Hc-$Ye[$D][1];$s=0;foreach($le[0]as$Ie)echo"\n<div class='references' title='".h($ff)."' id='refs$Hc-".($s++)."' style='left: $Ic"."em; top: ".$S["fields"][$Ie]["pos"]."em; padding-top: .5em;'><div style='border-top: 1px solid Gray; width: ".(-$Ic)."em;'></div></div>";}}foreach((array)$oe[$D]as$ff=>$pe){foreach($pe
as$Hc=>$e){$Ic=$Hc-$Ye[$D][1];$s=0;foreach($e
as$ef)echo"\n<div class='references' title='".h($ff)."' id='refd$Hc-".($s++)."' style='left: $Ic"."em; top: ".$S["fields"][$ef]["pos"]."em; height: 1.25em; background: url(".h(preg_replace("~\\?.*~","",ME))."?file=arrow.gif) no-repeat right center;&amp;version=3.4.0'><div style='height: .5em; border-bottom: 1px solid Gray; width: ".(-$Ic)."em;'></div></div>";}}echo"\n</div>\n";}foreach($Ae
as$D=>$S){foreach((array)$S["references"]as$ff=>$pe){foreach($pe
as$Hc=>$le){$dd=$of;$Vc=-10;foreach($le[0]as$y=>$Ie){$Vd=$S["pos"][0]+$S["fields"][$Ie]["pos"];$Wd=$Ae[$ff]["pos"][0]+$Ae[$ff]["fields"][$le[1][$y]]["pos"];$dd=min($dd,$Vd,$Wd);$Vc=max($Vc,$Vd,$Wd);}echo"<div class='references' id='refl$Hc' style='left: $Hc"."em; top: $dd"."em; padding: .5em 0;'><div style='border-right: 1px solid Gray; margin-top: 1px; height: ".($Vc-$dd)."em;'></div></div>\n";}}}echo'</div>
<p><a href="',h(ME."schema=".urlencode($ea)),'" id="schema-link">Permanent link</a>
';}elseif(isset($_GET["dump"])){$a=$_GET["dump"];if($_POST){$Xa="";foreach(array("output","format","db_style","routines","events","table_style","auto_increment","triggers","data_style")as$y)$Xa.="&$y=".urlencode($_POST[$y]);cookie("adminer_export",substr($Xa,1));$Qb=dump_headers(($a!=""?$a:DB),(DB==""||count((array)$_POST["tables"]+(array)$_POST["data"])>1));$zc=($_POST["format"]=="sql");if($zc)echo"-- Adminer $ga ".$qb[DRIVER]." dump

".($x!="sql"?"":"SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = ".q($f->result("SELECT @@time_zone")).";
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

");$R=$_POST["db_style"];$h=array(DB);if(DB==""){$h=$_POST["databases"];if(is_string($h))$h=explode("\n",rtrim(str_replace("\r","",$h),"\n"));}foreach((array)$h
as$i){if($f->select_db($i)){if($zc&&ereg('CREATE',$R)&&($Za=$f->result("SHOW CREATE DATABASE ".idf_escape($i),1))){if($R=="DROP+CREATE")echo"DROP DATABASE IF EXISTS ".idf_escape($i).";\n";echo($R=="CREATE+ALTER"?preg_replace('~^CREATE DATABASE ~','\\0IF NOT EXISTS ',$Za):$Za).";\n";}if($zc){if($R)echo
use_sql($i).";\n\n";if(in_array("CREATE+ALTER",array($R,$_POST["table_style"])))echo"SET @adminer_alter = '';\n\n";$Gd="";if($_POST["routines"]){foreach(array("FUNCTION","PROCEDURE")as$we){foreach(get_rows("SHOW $we STATUS WHERE Db = ".q($i),null,"-- ")as$K)$Gd.=($R!='DROP+CREATE'?"DROP $we IF EXISTS ".idf_escape($K["Name"]).";;\n":"").$f->result("SHOW CREATE $we ".idf_escape($K["Name"]),2).";;\n\n";}}if($_POST["events"]){foreach(get_rows("SHOW EVENTS",null,"-- ")as$K)$Gd.=($R!='DROP+CREATE'?"DROP EVENT IF EXISTS ".idf_escape($K["Name"]).";;\n":"").$f->result("SHOW CREATE EVENT ".idf_escape($K["Name"]),3).";;\n\n";}if($Gd)echo"DELIMITER ;;\n\n$Gd"."DELIMITER ;\n\n";}if($_POST["table_style"]||$_POST["data_style"]){$Mf=array();foreach(table_status()as$T){$S=(DB==""||in_array($T["Name"],(array)$_POST["tables"]));$eb=(DB==""||in_array($T["Name"],(array)$_POST["data"]));if($S||$eb){if(!is_view($T)){if($Qb=="tar")ob_start();$b->dumpTable($T["Name"],($S?$_POST["table_style"]:""));if($eb)$b->dumpData($T["Name"],$_POST["data_style"],"SELECT * FROM ".table($T["Name"]));if($zc&&$_POST["triggers"]&&$S&&($vf=trigger_sql($T["Name"],$_POST["table_style"])))echo"\nDELIMITER ;;\n$vf\nDELIMITER ;\n";if($Qb=="tar")echo
tar_file((DB!=""?"":"$i/")."$T[Name].csv",ob_get_clean());elseif($zc)echo"\n";}elseif($zc)$Mf[]=$T["Name"];}}foreach($Mf
as$Lf)$b->dumpTable($Lf,$_POST["table_style"],true);if($Qb=="tar")echo
pack("x512");}if($R=="CREATE+ALTER"&&$zc){$H="SELECT TABLE_NAME, ENGINE, TABLE_COLLATION, TABLE_COMMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE()";echo"DELIMITER ;;
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
			CASE _table_name";foreach(get_rows($H)as$K){$Qa=q($K["ENGINE"]=="InnoDB"?preg_replace('~(?:(.+); )?InnoDB free: .*~','\\1',$K["TABLE_COMMENT"]):$K["TABLE_COMMENT"]);echo"
				WHEN ".q($K["TABLE_NAME"])." THEN
					".(isset($K["ENGINE"])?"IF _engine != '$K[ENGINE]' OR _table_collation != '$K[TABLE_COLLATION]' OR _table_comment != $Qa THEN
						ALTER TABLE ".idf_escape($K["TABLE_NAME"])." ENGINE=$K[ENGINE] COLLATE=$K[TABLE_COLLATION] COMMENT=$Qa;
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
";}if(in_array("CREATE+ALTER",array($R,$_POST["table_style"]))&&$zc)echo"SELECT @adminer_alter;\n";}}if($zc)echo"-- ".$f->result("SELECT NOW()")."\n";exit;}page_header('Export',"",($_GET["export"]!=""?array("table"=>$_GET["export"]):array()),DB);echo'
<form action="" method="post">
<table cellspacing="0">
';$hb=array('','USE','DROP+CREATE','CREATE');$af=array('','DROP+CREATE','CREATE');$fb=array('','TRUNCATE+INSERT','INSERT');if($x=="sql"){$hb[]='CREATE+ALTER';$af[]='CREATE+ALTER';$fb[]='INSERT+UPDATE';}parse_str($_COOKIE["adminer_export"],$K);if(!$K)$K=array("output"=>"text","format"=>"sql","db_style"=>(DB!=""?"":"CREATE"),"table_style"=>"DROP+CREATE","data_style"=>"INSERT");if(!isset($K["events"])){$K["routines"]=$K["events"]=($_GET["dump"]=="");$K["triggers"]=$K["table_style"];}echo"<tr><th>".'Output'."<td>".html_select("output",$b->dumpOutput(),$K["output"],0)."\n";echo"<tr><th>".'Format'."<td>".html_select("format",$b->dumpFormat(),$K["format"],0)."\n";echo($x=="sqlite"?"":"<tr><th>".'Database'."<td>".html_select('db_style',$hb,$K["db_style"]).(support("routine")?checkbox("routines",1,$K["routines"],'Routines'):"").(support("event")?checkbox("events",1,$K["events"],'Events'):"")),"<tr><th>".'Tables'."<td>".html_select('table_style',$af,$K["table_style"]).checkbox("auto_increment",1,$K["auto_increment"],'Auto Increment').(support("trigger")?checkbox("triggers",1,$K["triggers"],'Triggers'):""),"<tr><th>".'Data'."<td>".html_select('data_style',$fb,$K["data_style"]),'</table>
<p><input type="submit" value="Export">

<table cellspacing="0">
';$Zd=array();if(DB!=""){$Ga=($a!=""?"":" checked");echo"<thead><tr>","<th style='text-align: left;'><label><input type='checkbox' id='check-tables'$Ga onclick='formCheck(this, /^tables\\[/);'>".'Tables'."</label>","<th style='text-align: right;'><label>".'Data'."<input type='checkbox' id='check-data'$Ga onclick='formCheck(this, /^data\\[/);'></label>","</thead>\n";$Mf="";foreach(table_status()as$T){$D=$T["Name"];$Yd=ereg_replace("_.*","",$D);$Ga=($a==""||$a==(substr($a,-1)=="%"?"$Yd%":$D));$be="<tr><td>".checkbox("tables[]",$D,$Ga,$D,"formUncheck('check-tables');");if(is_view($T))$Mf.="$be\n";else
echo"$be<td align='right'><label>".($T["Engine"]=="InnoDB"&&$T["Rows"]?"~ ":"").$T["Rows"].checkbox("data[]",$D,$Ga,"","formUncheck('check-data');")."</label>\n";$Zd[$Yd]++;}echo$Mf;}else{echo"<thead><tr><th style='text-align: left;'><label><input type='checkbox' id='check-databases'".($a==""?" checked":"")." onclick='formCheck(this, /^databases\\[/);'>".'Database'."</label></thead>\n";$h=$b->databases();if($h){foreach($h
as$i){if(!information_schema($i)){$Yd=ereg_replace("_.*","",$i);echo"<tr><td>".checkbox("databases[]",$i,$a==""||$a=="$Yd%",$i,"formUncheck('check-databases');")."</label>\n";$Zd[$Yd]++;}}}else
echo"<tr><td><textarea name='databases' rows='10' cols='20'></textarea>";}echo'</table>
</form>
';$Xb=true;foreach($Zd
as$y=>$X){if($y!=""&&$X>1){echo($Xb?"<p>":" ")."<a href='".h(ME)."dump=".urlencode("$y%")."'>".h($y)."</a>";$Xb=false;}}}elseif(isset($_GET["privileges"])){page_header('Privileges');$I=$f->query("SELECT User, Host FROM mysql.".(DB==""?"user":"db WHERE ".q(DB)." LIKE Db")." ORDER BY Host, User");$r=$I;if(!$I)$I=$f->query("SELECT SUBSTRING_INDEX(CURRENT_USER, '@', 1) AS User, SUBSTRING_INDEX(CURRENT_USER, '@', -1) AS Host");echo"<form action=''><p>\n";hidden_fields_get();echo"<input type='hidden' name='db' value='".h(DB)."'>\n",($r?"":"<input type='hidden' name='grant' value=''>\n"),"<table cellspacing='0'>\n","<thead><tr><th>".'Username'."<th>".'Server'."<th>&nbsp;</thead>\n";while($K=$I->fetch_assoc())echo'<tr'.odd().'><td>'.h($K["User"])."<td>".h($K["Host"]).'<td><a href="'.h(ME.'user='.urlencode($K["User"]).'&host='.urlencode($K["Host"])).'">'.'Edit'."</a>\n";if(!$r||DB!="")echo"<tr".odd()."><td><input name='user'><td><input name='host' value='localhost'><td><input type='submit' value='".'Edit'."'>\n";echo"</table>\n","</form>\n",'<p><a href="'.h(ME).'user=">'.'Create user'."</a>";}elseif(isset($_GET["sql"])){if(!$j&&$_POST["export"]){dump_headers("sql");$b->dumpTable("","");$b->dumpData("","table",$_POST["query"]);exit;}restart_session();$mc=&get_session("queries");$lc=&$mc[DB];if(!$j&&$_POST["clear"]){$lc=array();redirect(remove_from_uri("history"));}page_header('SQL command',$j);if(!$j&&$_POST){$dc=false;$H=$_POST["query"];if($_POST["webfile"]){$dc=@fopen((file_exists("adminer.sql")?"adminer.sql":(file_exists("adminer.sql.gz")?"compress.zlib://adminer.sql.gz":"compress.bzip2://adminer.sql.bz2")),"rb");$H=($dc?fread($dc,1e6):false);}elseif($_FILES&&$_FILES["sql_file"]["error"]!=UPLOAD_ERR_NO_FILE)$H=get_file("sql_file",true);if(is_string($H)){if(function_exists('memory_get_usage'))@ini_set("memory_limit",max(ini_bytes("memory_limit"),2*strlen($H)+memory_get_usage()+8e6));if($H!=""&&strlen($H)<1e6){$G=$H.(ereg(";[ \t\r\n]*\$",$H)?"":";");if(!$lc||reset(end($lc))!=$G)$lc[]=array($G,time());}$Je="(?:\\s|/\\*.*\\*/|(?:#|-- )[^\n]*\n|--\n)";if(!ini_bool("session.use_cookies"))session_write_close();$kb=";";$nd=0;$Ab=true;$g=connect();if(is_object($g)&&DB!="")$g->select_db(DB);$Pa=0;$Gb=array();$Nc=0;$Ld='[\'"'.($x=="sql"?'`#':($x=="sqlite"?'`[':($x=="mssql"?'[':''))).']|/\\*|-- |$'.($x=="pgsql"?'|\\$[^$]*\\$':'');$pf=microtime();parse_str($_COOKIE["adminer_export"],$ka);$ub=$b->dumpFormat();unset($ub["sql"]);while($H!=""){if(!$nd&&preg_match("~^$Je*DELIMITER\\s+(.+)~i",$H,$B)){$kb=$B[1];$H=substr($H,strlen($B[0]));}else{preg_match('('.preg_quote($kb)."\\s*|$Ld)",$H,$B,PREG_OFFSET_CAPTURE,$nd);list($o,$Ud)=$B[0];if(!$o&&$dc&&!feof($dc))$H.=fread($dc,1e5);else{if(!$o&&rtrim($H)=="")break;$nd=$Ud+strlen($o);if($o&&rtrim($o)!=$kb){while(preg_match('('.($o=='/*'?'\\*/':($o=='['?']':(ereg('^-- |^#',$o)?"\n":preg_quote($o)."|\\\\."))).'|$)s',$H,$B,PREG_OFFSET_CAPTURE,$nd)){$M=$B[0][0];if(!$M&&$dc&&!feof($dc))$H.=fread($dc,1e5);else{$nd=$B[0][1]+strlen($M);if($M[0]!="\\")break;}}}else{$Ab=false;$G=substr($H,0,$Ud);$Pa++;$be="<pre id='sql-$Pa'><code class='jush-$x'>".shorten_utf8(trim($G),1000)."</code></pre>\n";if(!$_POST["only_errors"]){echo$be;ob_flush();flush();}$Le=microtime();if($f->multi_query($G)&&is_object($g)&&preg_match("~^$Je*USE\\b~isU",$G))$g->query($G);do{$I=$f->store_result();$Bb=microtime();$if=format_time($Le,$Bb).(strlen($G)<1000?" <a href='".h(ME)."sql=".urlencode(trim($G))."'>".'Edit'."</a>":"");if($f->error){echo($_POST["only_errors"]?$be:""),"<p class='error'>".'Error in query'.": ".error()."\n";$Gb[]=" <a href='#sql-$Pa'>$Pa</a>";if($_POST["error_stops"])break
2;}elseif(is_object($I)){$Bd=select($I,$g);if(!$_POST["only_errors"]){echo"<form action='' method='post'>\n","<p>".($I->num_rows?lang(array('%d row','%d rows'),$I->num_rows):"").$if;$t="export-$Pa";$Pb=", <a href='#$t' onclick=\"return !toggle('$t');\">".'Export'."</a><span id='$t' class='hidden'>: ".html_select("output",$b->dumpOutput(),$ka["output"])." ".html_select("format",$ub,$ka["format"])."<input type='hidden' name='query' value='".h($G)."'>"." <input type='submit' name='export' value='".'Export'."'><input type='hidden' name='token' value='$U'></span>\n";if($g&&preg_match("~^($Je|\\()*SELECT\\b~isU",$G)&&($Ob=explain($g,$G))){$t="explain-$Pa";echo", <a href='#$t' onclick=\"return !toggle('$t');\">EXPLAIN</a>$Pb","<div id='$t' class='hidden'>\n";select($Ob,$g,($x=="sql"?"http://dev.mysql.com/doc/refman/".substr($f->server_info,0,3)."/en/explain-output.html#explain_":""),$Bd);echo"</div>\n";}else
echo$Pb;echo"</form>\n";}}else{if(preg_match("~^$Je*(CREATE|DROP|ALTER)$Je+(DATABASE|SCHEMA)\\b~isU",$G)){restart_session();set_session("dbs",null);session_write_close();}if(!$_POST["only_errors"])echo"<p class='message' title='".h($f->info)."'>".lang(array('Query executed OK, %d row affected.','Query executed OK, %d rows affected.'),$f->affected_rows)."$if\n";}$Le=$Bb;}while($f->next_result());$Nc+=substr_count($G.$o,"\n");$H=substr($H,$nd);$nd=0;}}}}if($Ab)echo"<p class='message'>".'No commands to execute.'."\n";elseif($_POST["only_errors"])echo"<p class='message'>".lang(array('%d query executed OK.','%d queries executed OK.'),$Pa-count($Gb)).format_time($pf,microtime())."\n";elseif($Gb&&$Pa>1)echo"<p class='error'>".'Error in query'.": ".implode("",$Gb)."\n";}else
echo"<p class='error'>".upload_error($H)."\n";}echo'
<form action="" method="post" enctype="multipart/form-data" id="form">
<p>';$G=$_GET["sql"];if($_POST)$G=$_POST["query"];elseif($_GET["history"]=="all")$G=$lc;elseif($_GET["history"]!="")$G=$lc[$_GET["history"]][0];textarea("query",$G,20);echo($_POST?"":"<script type='text/javascript'>document.getElementsByTagName('textarea')[0].focus();</script>\n"),"<p>".(ini_bool("file_uploads")?'File upload'.': <input type="file" name="sql_file"'.($_FILES&&$_FILES["sql_file"]["error"]!=4?'':' onchange="this.form[\'only_errors\'].checked = true;"').'> (&lt; '.ini_get("upload_max_filesize").'B)':'File uploads are disabled.'),'<p>
<input type="submit" value="Execute" title="Ctrl+Enter">
<input type="hidden" name="token" value="',$U,'">
',checkbox("error_stops",1,$_POST["error_stops"],'Stop on error')."\n",checkbox("only_errors",1,$_POST["only_errors"],'Show only errors')."\n";print_fieldset("webfile",'From server',$_POST["webfile"],"document.getElementById('form')['only_errors'].checked = true; ");$Sa=array();foreach(array("gz"=>"zlib","bz2"=>"bz2")as$y=>$X){if(extension_loaded($X))$Sa[]=".$y";}echo
sprintf('Webserver file %s',"<code>adminer.sql".($Sa?"[".implode("|",$Sa)."]":"")."</code>"),' <input type="submit" name="webfile" value="'.'Run file'.'">',"</div></fieldset>\n";if($lc){print_fieldset("history",'History',$_GET["history"]!="");foreach($lc
as$y=>$X){list($G,$if)=$X;echo'<a href="'.h(ME."sql=&history=$y").'">'.'Edit'."</a> <span class='time'>".@date("H:i:s",$if)."</span> <code class='jush-$x'>".shorten_utf8(ltrim(str_replace("\n"," ",str_replace("\r","",preg_replace('~^(#|-- ).*~m','',$G)))),80,"</code>")."<br>\n";}echo"<input type='submit' name='clear' value='".'Clear'."'>\n","<a href='".h(ME."sql=&history=all")."'>".'Edit all'."</a>\n","</div></fieldset>\n";}echo'
</form>
';}elseif(isset($_GET["edit"])){$a=$_GET["edit"];$Z=(isset($_GET["select"])?(count($_POST["check"])==1?where_check($_POST["check"][0]):""):where($_GET));$Ff=(isset($_GET["select"])?$_POST["edit"]:$Z);$l=fields($a);foreach($l
as$D=>$k){if(!isset($k["privileges"][$Ff?"update":"insert"])||$b->fieldName($k)=="")unset($l[$D]);}if($_POST&&!$j&&!isset($_GET["select"])){$A=$_POST["referer"];if($_POST["insert"])$A=($Ff?null:$_SERVER["REQUEST_URI"]);elseif(!ereg('^.+&select=.+$',$A))$A=ME."select=".urlencode($a);if(isset($_POST["delete"]))query_redirect("DELETE".limit1("FROM ".table($a)," WHERE $Z"),$A,'Item has been deleted.');else{$P=array();foreach($l
as$D=>$k){$X=process_input($k);if($X!==false&&$X!==null)$P[idf_escape($D)]=($Ff?"\n".idf_escape($D)." = $X":$X);}if($Ff){if(!$P)redirect($A);query_redirect("UPDATE".limit1(table($a)." SET".implode(",",$P),"\nWHERE $Z"),$A,'Item has been updated.');}else{$I=insert_into($a,$P);$Gc=($I?last_id():0);queries_redirect($A,sprintf('Item%s has been inserted.',($Gc?" $Gc":"")),$I);}}}$Xe=$b->tableName(table_status($a));page_header(($Ff?'Edit':'Insert'),$j,array("select"=>array($a,$Xe)),$Xe);$K=null;if($_POST["save"])$K=(array)$_POST["fields"];elseif($Z){$N=array();foreach($l
as$D=>$k){if(isset($k["privileges"]["select"]))$N[]=($_POST["clone"]&&$k["auto_increment"]?"'' AS ":(ereg("enum|set",$k["type"])?"1*".idf_escape($D)." AS ":"")).idf_escape($D);}$K=array();if($N){$L=get_rows("SELECT".limit(implode(", ",$N)." FROM ".table($a)," WHERE $Z",(isset($_GET["select"])?2:1)));$K=(isset($_GET["select"])&&count($L)!=1?null:reset($L));}}if($K===false)echo"<p class='error'>".'No rows.'."\n";echo'
<form action="" method="post" enctype="multipart/form-data" id="form">
';if($l){echo"<table cellspacing='0' onkeydown='return editingKeydown(event);'>\n";foreach($l
as$D=>$k){echo"<tr><th>".$b->fieldName($k);$jb=$_GET["set"][bracket_escape($D)];$Y=($K!==null?($K[$D]!=""&&ereg("enum|set",$k["type"])?(is_array($K[$D])?array_sum($K[$D]):+$K[$D]):$K[$D]):(!$Ff&&$k["auto_increment"]?"":(isset($_GET["select"])?false:($jb!==null?$jb:$k["default"]))));if(!$_POST["save"]&&is_string($Y))$Y=$b->editVal($Y,$k);$p=($_POST["save"]?(string)$_POST["function"][$D]:($Ff&&$k["on_update"]=="CURRENT_TIMESTAMP"?"now":($Y===false?null:($Y!==null?'':'NULL'))));if($k["type"]=="timestamp"&&$Y=="CURRENT_TIMESTAMP"){$Y="";$p="now";}input($k,$Y,$p);echo"\n";}echo"</table>\n";}echo'<p>
';if($l){echo"<input type='submit' value='".'Save'."'>\n";if(!isset($_GET["select"]))echo"<input type='submit' name='insert' value='".($Ff?'Save and continue edit':'Save and insert next')."' title='Ctrl+Shift+Enter'>\n";}echo($Ff?"<input type='submit' name='delete' value='".'Delete'."' onclick=\"return confirm('".'Are you sure?'."');\">\n":($_POST||!$l?"":"<script type='text/javascript'>document.getElementById('form').getElementsByTagName('td')[1].firstChild.focus();</script>\n"));if(isset($_GET["select"]))hidden_fields(array("check"=>(array)$_POST["check"],"clone"=>$_POST["clone"],"all"=>$_POST["all"]));echo'<input type="hidden" name="referer" value="',h(isset($_POST["referer"])?$_POST["referer"]:$_SERVER["HTTP_REFERER"]),'">
<input type="hidden" name="save" value="1">
<input type="hidden" name="token" value="',$U,'">
</form>
';}elseif(isset($_GET["create"])){$a=$_GET["create"];$Md=array('HASH','LINEAR HASH','KEY','LINEAR KEY','RANGE','LIST');$ne=referencable_primary($a);$n=array();foreach($ne
as$Xe=>$k)$n[str_replace("`","``",$Xe)."`".str_replace("`","``",$k["field"])]=$Xe;$Ed=array();$Fd=array();if($a!=""){$Ed=fields($a);$Fd=table_status($a);}if($_POST&&!$_POST["fields"])$_POST["fields"]=array();if($_POST&&!$j&&!$_POST["add"]&&!$_POST["drop_col"]&&!$_POST["up"]&&!$_POST["down"]){if($_POST["drop"])query_redirect("DROP TABLE ".table($a),substr(ME,0,-1),'Table has been dropped.');else{$l=array();$Zb=array();ksort($_POST["fields"]);$Dd=reset($Ed);$oa="FIRST";foreach($_POST["fields"]as$y=>$k){$m=$n[$k["type"]];$wf=($m!==null?$ne[$m]:$k);if($k["field"]!=""){if(!$k["has_default"])$k["default"]=null;$jb=eregi_replace(" *on update CURRENT_TIMESTAMP","",$k["default"]);if($jb!=$k["default"]){$k["on_update"]="CURRENT_TIMESTAMP";$k["default"]=$jb;}if($y==$_POST["auto_increment_col"])$k["auto_increment"]=true;$ge=process_field($k,$wf);if($ge!=process_field($Dd,$Dd))$l[]=array($k["orig"],$ge,$oa);if($m!==null)$Zb[idf_escape($k["field"])]=($a!=""?"ADD":" ")." FOREIGN KEY (".idf_escape($k["field"]).") REFERENCES ".table($n[$k["type"]])." (".idf_escape($wf["field"]).")".(ereg("^($sd)\$",$k["on_delete"])?" ON DELETE $k[on_delete]":"");$oa="AFTER ".idf_escape($k["field"]);}elseif($k["orig"]!="")$l[]=array($k["orig"]);if($k["orig"]!="")$Dd=next($Ed);}$Od="";if(in_array($_POST["partition_by"],$Md)){$Pd=array();if($_POST["partition_by"]=='RANGE'||$_POST["partition_by"]=='LIST'){foreach(array_filter($_POST["partition_names"])as$y=>$X){$Y=$_POST["partition_values"][$y];$Pd[]="\nPARTITION ".idf_escape($X)." VALUES ".($_POST["partition_by"]=='RANGE'?"LESS THAN":"IN").($Y!=""?" ($Y)":" MAXVALUE");}}$Od.="\nPARTITION BY $_POST[partition_by]($_POST[partition])".($Pd?" (".implode(",",$Pd)."\n)":($_POST["partitions"]?" PARTITIONS ".(+$_POST["partitions"]):""));}elseif($a!=""&&support("partitioning"))$Od.="\nREMOVE PARTITIONING";$Yc='Table has been altered.';if($a==""){cookie("adminer_engine",$_POST["Engine"]);$Yc='Table has been created.';}$D=trim($_POST["name"]);queries_redirect(ME."table=".urlencode($D),$Yc,alter_table($a,$D,$l,$Zb,$_POST["Comment"],($_POST["Engine"]&&$_POST["Engine"]!=$Fd["Engine"]?$_POST["Engine"]:""),($_POST["Collation"]&&$_POST["Collation"]!=$Fd["Collation"]?$_POST["Collation"]:""),($_POST["Auto_increment"]!=""?+$_POST["Auto_increment"]:""),$Od));}}page_header(($a!=""?'Alter table':'Create table'),$j,array("table"=>$a),$a);$K=array("Engine"=>$_COOKIE["adminer_engine"],"fields"=>array(array("field"=>"","type"=>(isset($yf["int"])?"int":(isset($yf["integer"])?"integer":"")))),"partition_names"=>array(""),);if($_POST){$K=$_POST;if($K["auto_increment_col"])$K["fields"][$K["auto_increment_col"]]["auto_increment"]=true;process_fields($K["fields"]);}elseif($a!=""){$K=$Fd;$K["name"]=$a;$K["fields"]=array();if(!$_GET["auto_increment"])$K["Auto_increment"]="";foreach($Ed
as$k){$k["has_default"]=isset($k["default"]);if($k["on_update"])$k["default"].=" ON UPDATE $k[on_update]";$K["fields"][]=$k;}if(support("partitioning")){$ec="FROM information_schema.PARTITIONS WHERE TABLE_SCHEMA = ".q(DB)." AND TABLE_NAME = ".q($a);$I=$f->query("SELECT PARTITION_METHOD, PARTITION_ORDINAL_POSITION, PARTITION_EXPRESSION $ec ORDER BY PARTITION_ORDINAL_POSITION DESC LIMIT 1");list($K["partition_by"],$K["partitions"],$K["partition"])=$I->fetch_row();$K["partition_names"]=array();$K["partition_values"]=array();foreach(get_rows("SELECT PARTITION_NAME, PARTITION_DESCRIPTION $ec AND PARTITION_NAME != '' ORDER BY PARTITION_ORDINAL_POSITION")as$ze){$K["partition_names"][]=$ze["PARTITION_NAME"];$K["partition_values"][]=$ze["PARTITION_DESCRIPTION"];}$K["partition_names"][]="";}}$d=collations();$Se=floor(extension_loaded("suhosin")?(min(ini_get("suhosin.request.max_vars"),ini_get("suhosin.post.max_vars"))-13)/10:0);if($Se&&count($K["fields"])>$Se)echo"<p class='error'>".h(sprintf('Maximum number of allowed fields exceeded. Please increase %s and %s.','suhosin.post.max_vars','suhosin.request.max_vars'))."\n";$Db=engines();foreach($Db
as$Cb){if(!strcasecmp($Cb,$K["Engine"])){$K["Engine"]=$Cb;break;}}echo'
<form action="" method="post" id="form">
<p>
Table name: <input name="name" maxlength="64" value="',h($K["name"]),'">
';if($a==""&&!$_POST){?><script type='text/javascript'>document.getElementById('form')['name'].focus();</script><?php }echo($Db?html_select("Engine",array(""=>"(".'engine'.")")+$Db,$K["Engine"]):""),' ',($d&&!ereg("sqlite|mssql",$x)?html_select("Collation",array(""=>"(".'collation'.")")+$d,$K["Collation"]):""),' <input type="submit" value="Save">
<table cellspacing="0" id="edit-fields" class="nowrap">
';$Ra=($_POST?$_POST["comments"]:$K["Comment"]!="");if(!$_POST&&!$Ra){foreach($K["fields"]as$k){if($k["comment"]!=""){$Ra=true;break;}}}edit_fields($K["fields"],$d,"TABLE",$Se,$n,$Ra);echo'</table>
<p>
Auto Increment: <input name="Auto_increment" size="6" value="',h($K["Auto_increment"]),'">
<label class="jsonly"><input type="checkbox" name="defaults" value="1"',($_POST["defaults"]?" checked":""),' onclick="columnShow(this.checked, 5);">Default values</label>
',(support("comment")?checkbox("comments",1,$Ra,'Comment',"columnShow(this.checked, 6); toggle('Comment'); if (this.checked) this.form['Comment'].focus();",true).' <input id="Comment" name="Comment" value="'.h($K["Comment"]).'" maxlength="60"'.($Ra?'':' class="hidden"').'>':''),'<p>
<input type="submit" value="Save">
';if($_GET["create"]!=""){echo'<input type="submit" name="drop" value="Drop"',confirm(),'>';}echo'<input type="hidden" name="token" value="',$U,'">
';if(support("partitioning")){$Nd=ereg('RANGE|LIST',$K["partition_by"]);print_fieldset("partition",'Partition by',$K["partition_by"]);echo'<p>
',html_select("partition_by",array(-1=>"")+$Md,$K["partition_by"],"partitionByChange(this);"),'(<input name="partition" value="',h($K["partition"]),'">)
Partitions: <input name="partitions" size="2" value="',h($K["partitions"]),'"',($Nd||!$K["partition_by"]?" class='hidden'":""),'>
<table cellspacing="0" id="partition-table"',($Nd?"":" class='hidden'"),'>
<thead><tr><th>Partition name<th>Values</thead>
';foreach($K["partition_names"]as$y=>$X){echo'<tr>','<td><input name="partition_names[]" value="'.h($X).'"'.($y==count($K["partition_names"])-1?' onchange="partitionNameChange(this);"':'').'>','<td><input name="partition_values[]" value="'.h($K["partition_values"][$y]).'">';}echo'</table>
</div></fieldset>
';}echo'</form>
';}elseif(isset($_GET["indexes"])){$a=$_GET["indexes"];$tc=array("PRIMARY","UNIQUE","INDEX");$T=table_status($a);if(eregi("MyISAM|M?aria",$T["Engine"]))$tc[]="FULLTEXT";$v=indexes($a);if($x=="sqlite"){unset($tc[0]);unset($v[""]);}if($_POST&&!$j&&!$_POST["add"]){$ra=array();foreach($_POST["indexes"]as$u){$D=$u["name"];if(in_array($u["type"],$tc)){$e=array();$Mc=array();$P=array();ksort($u["columns"]);foreach($u["columns"]as$y=>$Na){if($Na!=""){$Lc=$u["lengths"][$y];$P[]=idf_escape($Na).($Lc?"(".(+$Lc).")":"");$e[]=$Na;$Mc[]=($Lc?$Lc:null);}}if($e){$Nb=$v[$D];if($Nb){ksort($Nb["columns"]);ksort($Nb["lengths"]);if($u["type"]==$Nb["type"]&&array_values($Nb["columns"])===$e&&(!$Nb["lengths"]||array_values($Nb["lengths"])===$Mc)){unset($v[$D]);continue;}}$ra[]=array($u["type"],$D,"(".implode(", ",$P).")");}}}foreach($v
as$D=>$Nb)$ra[]=array($Nb["type"],$D,"DROP");if(!$ra)redirect(ME."table=".urlencode($a));queries_redirect(ME."table=".urlencode($a),'Indexes have been altered.',alter_indexes($a,$ra));}page_header('Indexes',$j,array("table"=>$a),$a);$l=array_keys(fields($a));$K=array("indexes"=>$v);if($_POST){$K=$_POST;if($_POST["add"]){foreach($K["indexes"]as$y=>$u){if($u["columns"][count($u["columns"])]!="")$K["indexes"][$y]["columns"][]="";}$u=end($K["indexes"]);if($u["type"]||array_filter($u["columns"],'strlen')||array_filter($u["lengths"],'strlen'))$K["indexes"][]=array("columns"=>array(1=>""));}}else{foreach($K["indexes"]as$y=>$u){$K["indexes"][$y]["name"]=$y;$K["indexes"][$y]["columns"][]="";}$K["indexes"][]=array("columns"=>array(1=>""));}echo'
<form action="" method="post">
<table cellspacing="0" class="nowrap">
<thead><tr><th>Index Type<th>Column (length)<th>Name</thead>
';$w=1;foreach($K["indexes"]as$u){echo"<tr><td>".html_select("indexes[$w][type]",array(-1=>"")+$tc,$u["type"],($w==count($K["indexes"])?"indexesAddRow(this);":1))."<td>";ksort($u["columns"]);$s=1;foreach($u["columns"]as$y=>$Na){echo"<span>".html_select("indexes[$w][columns][$s]",array(-1=>"")+$l,$Na,($s==count($u["columns"])?"indexesAddColumn":"indexesChangeColumn")."(this, '".js_escape($x=="sql"?"":$_GET["indexes"]."_")."');"),"<input name='indexes[$w][lengths][$s]' size='2' value='".h($u["lengths"][$y])."'> </span>";$s++;}echo"<td><input name='indexes[$w][name]' value='".h($u["name"])."'>\n";$w++;}echo'</table>
<p>
<input type="submit" value="Save">
<noscript><p><input type="submit" name="add" value="Add next"></noscript>
<input type="hidden" name="token" value="',$U,'">
</form>
';}elseif(isset($_GET["database"])){if($_POST&&!$j&&!isset($_POST["add_x"])){restart_session();$D=trim($_POST["name"]);if($_POST["drop"]){$_GET["db"]="";queries_redirect(remove_from_uri("db|database"),'Database has been dropped.',drop_databases(array(DB)));}elseif(DB!==$D){if(DB!=""){$_GET["db"]=$D;queries_redirect(preg_replace('~db=[^&]*&~','',ME)."db=".urlencode($D),'Database has been renamed.',rename_database($D,$_POST["collation"]));}else{$h=explode("\n",str_replace("\r","",$D));$Qe=true;$Fc="";foreach($h
as$i){if(count($h)==1||$i!=""){if(!create_database($i,$_POST["collation"]))$Qe=false;$Fc=$i;}}queries_redirect(ME."db=".urlencode($Fc),'Database has been created.',$Qe);}}else{if(!$_POST["collation"])redirect(substr(ME,0,-1));query_redirect("ALTER DATABASE ".idf_escape($D).(eregi('^[a-z0-9_]+$',$_POST["collation"])?" COLLATE $_POST[collation]":""),substr(ME,0,-1),'Database has been altered.');}}page_header(DB!=""?'Alter database':'Create database',$j,array(),DB);$d=collations();$D=DB;$Ka=null;if($_POST){$D=$_POST["name"];$Ka=$_POST["collation"];}elseif(DB!="")$Ka=db_collation(DB,$d);elseif($x=="sql"){foreach(get_vals("SHOW GRANTS")as$r){if(preg_match('~ ON (`(([^\\\\`]|``|\\\\.)*)%`\\.\\*)?~',$r,$B)&&$B[1]){$D=stripcslashes(idf_unescape("`$B[2]`"));break;}}}echo'
<form action="" method="post">
<p>
',($_POST["add_x"]||strpos($D,"\n")?'<textarea id="name" name="name" rows="10" cols="40">'.h($D).'</textarea><br>':'<input id="name" name="name" value="'.h($D).'" maxlength="64">')."\n".($d?html_select("collation",array(""=>"(".'collation'.")")+$d,$Ka):"");?>
<script type='text/javascript'>document.getElementById('name').focus();</script>
<input type="submit" value="Save">
<?php
if(DB!="")echo"<input type='submit' name='drop' value='".'Drop'."'".confirm().">\n";elseif(!$_POST["add_x"]&&$_GET["db"]=="")echo"<input type='image' name='add' src='".h(preg_replace("~\\?.*~","",ME))."?file=plus.gif&amp;version=3.4.0' alt='+' title='".'Add next'."'>\n";echo'<input type="hidden" name="token" value="',$U,'">
</form>
';}elseif(isset($_GET["call"])){$da=$_GET["call"];page_header('Call'.": ".h($da),$j);$we=routine($da,(isset($_GET["callf"])?"FUNCTION":"PROCEDURE"));$sc=array();$Gd=array();foreach($we["fields"]as$s=>$k){if(substr($k["inout"],-3)=="OUT")$Gd[$s]="@".idf_escape($k["field"])." AS ".idf_escape($k["field"]);if(!$k["inout"]||substr($k["inout"],0,2)=="IN")$sc[]=$s;}if(!$j&&$_POST){$Da=array();foreach($we["fields"]as$y=>$k){if(in_array($y,$sc)){$X=process_input($k);if($X===false)$X="''";if(isset($Gd[$y]))$f->query("SET @".idf_escape($k["field"])." = $X");}$Da[]=(isset($Gd[$y])?"@".idf_escape($k["field"]):$X);}$H=(isset($_GET["callf"])?"SELECT":"CALL")." ".idf_escape($da)."(".implode(", ",$Da).")";echo"<p><code class='jush-$x'>".h($H)."</code> <a href='".h(ME)."sql=".urlencode($H)."'>".'Edit'."</a>\n";if(!$f->multi_query($H))echo"<p class='error'>".error()."\n";else{$g=connect();if(is_object($g))$g->select_db(DB);do{$I=$f->store_result();if(is_object($I))select($I,$g);else
echo"<p class='message'>".lang(array('Routine has been called, %d row affected.','Routine has been called, %d rows affected.'),$f->affected_rows)."\n";}while($f->next_result());if($Gd)select($f->query("SELECT ".implode(", ",$Gd)));}}echo'
<form action="" method="post">
';if($sc){echo"<table cellspacing='0'>\n";foreach($sc
as$y){$k=$we["fields"][$y];$D=$k["field"];echo"<tr><th>".$b->fieldName($k);$Y=$_POST["fields"][$D];if($Y!=""){if($k["type"]=="enum")$Y=+$Y;if($k["type"]=="set")$Y=array_sum($Y);}input($k,$Y,(string)$_POST["function"][$D]);echo"\n";}echo"</table>\n";}echo'<p>
<input type="submit" value="Call">
<input type="hidden" name="token" value="',$U,'">
</form>
';}elseif(isset($_GET["foreign"])){$a=$_GET["foreign"];if($_POST&&!$j&&!$_POST["add"]&&!$_POST["change"]&&!$_POST["change-js"]){if($_POST["drop"])query_redirect("ALTER TABLE ".table($a)."\nDROP ".($x=="sql"?"FOREIGN KEY ":"CONSTRAINT ").idf_escape($_GET["name"]),ME."table=".urlencode($a),'Foreign key has been dropped.');else{$Ie=array_filter($_POST["source"],'strlen');ksort($Ie);$ef=array();foreach($Ie
as$y=>$X)$ef[$y]=$_POST["target"][$y];query_redirect("ALTER TABLE ".table($a).($_GET["name"]!=""?"\nDROP ".($x=="sql"?"FOREIGN KEY ":"CONSTRAINT ").idf_escape($_GET["name"]).",":"")."\nADD FOREIGN KEY (".implode(", ",array_map('idf_escape',$Ie)).") REFERENCES ".table($_POST["table"])." (".implode(", ",array_map('idf_escape',$ef)).")".(ereg("^($sd)\$",$_POST["on_delete"])?" ON DELETE $_POST[on_delete]":"").(ereg("^($sd)\$",$_POST["on_update"])?" ON UPDATE $_POST[on_update]":""),ME."table=".urlencode($a),($_GET["name"]!=""?'Foreign key has been altered.':'Foreign key has been created.'));$j='Source and target columns must have the same data type, there must be an index on the target columns and referenced data must exist.'."<br>$j";}}page_header('Foreign key',$j,array("table"=>$a),$a);$K=array("table"=>$a,"source"=>array(""));if($_POST){$K=$_POST;ksort($K["source"]);if($_POST["add"])$K["source"][]="";elseif($_POST["change"]||$_POST["change-js"])$K["target"]=array();}elseif($_GET["name"]!=""){$n=foreign_keys($a);$K=$n[$_GET["name"]];$K["source"][]="";}$Ie=array_keys(fields($a));$ef=($a===$K["table"]?$Ie:array_keys(fields($K["table"])));$me=array();foreach(table_status()as$D=>$T){if(fk_support($T))$me[]=$D;}echo'
<form action="" method="post">
<p>
';if($K["db"]==""&&$K["ns"]==""){echo'Target table:
',html_select("table",$me,$K["table"],"this.form['change-js'].value = '1'; this.form.submit();"),'<input type="hidden" name="change-js" value="">
<noscript><p><input type="submit" name="change" value="Change"></noscript>
<table cellspacing="0">
<thead><tr><th>Source<th>Target</thead>
';$w=0;foreach($K["source"]as$y=>$X){echo"<tr>","<td>".html_select("source[".(+$y)."]",array(-1=>"")+$Ie,$X,($w==count($K["source"])-1?"foreignAddRow(this);":1)),"<td>".html_select("target[".(+$y)."]",$ef,$K["target"][$y]);$w++;}echo'</table>
<p>
ON DELETE: ',html_select("on_delete",array(-1=>"")+explode("|",$sd),$K["on_delete"]),' ON UPDATE: ',html_select("on_update",array(-1=>"")+explode("|",$sd),$K["on_update"]),'<p>
<input type="submit" value="Save">
<noscript><p><input type="submit" name="add" value="Add column"></noscript>
';}if($_GET["name"]!=""){echo'<input type="submit" name="drop" value="Drop"',confirm(),'>';}echo'<input type="hidden" name="token" value="',$U,'">
</form>
';}elseif(isset($_GET["view"])){$a=$_GET["view"];$sb=false;if($_POST&&!$j){$D=trim($_POST["name"]);$sb=drop_create("DROP VIEW ".table($a),"CREATE VIEW ".table($D)." AS\n$_POST[select]",($_POST["drop"]?substr(ME,0,-1):ME."table=".urlencode($D)),'View has been dropped.','View has been altered.','View has been created.',$a);}page_header(($a!=""?'Alter view':'Create view'),$j,array("table"=>$a),$a);$K=$_POST;if(!$K&&$a!=""){$K=view($a);$K["name"]=$a;}echo'
<form action="" method="post">
<p>Name: <input name="name" value="',h($K["name"]),'" maxlength="64">
<p>';textarea("select",$K["select"]);echo'<p>
';if($sb){echo'<input type="hidden" name="dropped" value="1">';}echo'<input type="submit" value="Save">
';if($_GET["view"]!=""){echo'<input type="submit" name="drop" value="Drop"',confirm(),'>';}echo'<input type="hidden" name="token" value="',$U,'">
</form>
';}elseif(isset($_GET["event"])){$aa=$_GET["event"];$yc=array("YEAR","QUARTER","MONTH","DAY","HOUR","MINUTE","WEEK","SECOND","YEAR_MONTH","DAY_HOUR","DAY_MINUTE","DAY_SECOND","HOUR_MINUTE","HOUR_SECOND","MINUTE_SECOND");$Ne=array("ENABLED"=>"ENABLE","DISABLED"=>"DISABLE","SLAVESIDE_DISABLED"=>"DISABLE ON SLAVE");if($_POST&&!$j){if($_POST["drop"])query_redirect("DROP EVENT ".idf_escape($aa),substr(ME,0,-1),'Event has been dropped.');elseif(in_array($_POST["INTERVAL_FIELD"],$yc)&&isset($Ne[$_POST["STATUS"]])){$_e="\nON SCHEDULE ".($_POST["INTERVAL_VALUE"]?"EVERY ".q($_POST["INTERVAL_VALUE"])." $_POST[INTERVAL_FIELD]".($_POST["STARTS"]?" STARTS ".q($_POST["STARTS"]):"").($_POST["ENDS"]?" ENDS ".q($_POST["ENDS"]):""):"AT ".q($_POST["STARTS"]))." ON COMPLETION".($_POST["ON_COMPLETION"]?"":" NOT")." PRESERVE";queries_redirect(substr(ME,0,-1),($aa!=""?'Event has been altered.':'Event has been created.'),queries(($aa!=""?"ALTER EVENT ".idf_escape($aa).$_e.($aa!=$_POST["EVENT_NAME"]?"\nRENAME TO ".idf_escape($_POST["EVENT_NAME"]):""):"CREATE EVENT ".idf_escape($_POST["EVENT_NAME"]).$_e)."\n".$Ne[$_POST["STATUS"]]." COMMENT ".q($_POST["EVENT_COMMENT"]).rtrim(" DO\n$_POST[EVENT_DEFINITION]",";").";"));}}page_header(($aa!=""?'Alter event'.": ".h($aa):'Create event'),$j);$K=$_POST;if(!$K&&$aa!=""){$L=get_rows("SELECT * FROM information_schema.EVENTS WHERE EVENT_SCHEMA = ".q(DB)." AND EVENT_NAME = ".q($aa));$K=reset($L);}echo'
<form action="" method="post">
<table cellspacing="0">
<tr><th>Name<td><input name="EVENT_NAME" value="',h($K["EVENT_NAME"]),'" maxlength="64">
<tr><th>Start<td><input name="STARTS" value="',h("$K[EXECUTE_AT]$K[STARTS]"),'">
<tr><th>End<td><input name="ENDS" value="',h($K["ENDS"]),'">
<tr><th>Every<td><input name="INTERVAL_VALUE" value="',h($K["INTERVAL_VALUE"]),'" size="6"> ',html_select("INTERVAL_FIELD",$yc,$K["INTERVAL_FIELD"]),'<tr><th>Status<td>',html_select("STATUS",$Ne,$K["STATUS"]),'<tr><th>Comment<td><input name="EVENT_COMMENT" value="',h($K["EVENT_COMMENT"]),'" maxlength="64">
<tr><th>&nbsp;<td>',checkbox("ON_COMPLETION","PRESERVE",$K["ON_COMPLETION"]=="PRESERVE",'On completion preserve'),'</table>
<p>';textarea("EVENT_DEFINITION",$K["EVENT_DEFINITION"]);echo'<p>
<input type="submit" value="Save">
';if($aa!=""){echo'<input type="submit" name="drop" value="Drop"',confirm(),'>';}echo'<input type="hidden" name="token" value="',$U,'">
</form>
';}elseif(isset($_GET["procedure"])){$da=$_GET["procedure"];$we=(isset($_GET["function"])?"FUNCTION":"PROCEDURE");$xe=routine_languages();$sb=false;if($_POST&&!$j&&!$_POST["add"]&&!$_POST["drop_col"]&&!$_POST["up"]&&!$_POST["down"]){$P=array();$l=(array)$_POST["fields"];ksort($l);foreach($l
as$k){if($k["field"]!="")$P[]=(ereg("^($vc)\$",$k["inout"])?"$k[inout] ":"").idf_escape($k["field"]).process_type($k,"CHARACTER SET");}$sb=drop_create("DROP $we ".idf_escape($da),"CREATE $we ".idf_escape(trim($_POST["name"]))." (".implode(", ",$P).")".(isset($_GET["function"])?" RETURNS".process_type($_POST["returns"],"CHARACTER SET"):"").(in_array($_POST["language"],$xe)?" LANGUAGE $_POST[language]":"").rtrim("\n$_POST[definition]",";").";",substr(ME,0,-1),'Routine has been dropped.','Routine has been altered.','Routine has been created.',$da);}page_header(($da!=""?(isset($_GET["function"])?'Alter function':'Alter procedure').": ".h($da):(isset($_GET["function"])?'Create function':'Create procedure')),$j);$d=get_vals("SHOW CHARACTER SET");sort($d);$K=array("fields"=>array());if($_POST){$K=$_POST;$K["fields"]=(array)$K["fields"];process_fields($K["fields"]);}elseif($da!=""){$K=routine($da,$we);$K["name"]=$da;}echo'
<form action="" method="post" id="form">
<p>Name: <input name="name" value="',h($K["name"]),'" maxlength="64">
',($xe?'Language'.": ".html_select("language",$xe,$K["language"]):""),'<table cellspacing="0" class="nowrap">
';edit_fields($K["fields"],$d,$we);if(isset($_GET["function"])){echo"<tr><td>".'Return type';edit_type("returns",$K["returns"],$d);}echo'</table>
<p>';textarea("definition",$K["definition"]);echo'<p>
<input type="submit" value="Save">
';if($da!=""){echo'<input type="submit" name="drop" value="Drop"',confirm(),'>';}if($sb){echo'<input type="hidden" name="dropped" value="1">';}echo'<input type="hidden" name="token" value="',$U,'">
</form>
';}elseif(isset($_GET["trigger"])){$a=$_GET["trigger"];$uf=trigger_options();$tf=array("INSERT","UPDATE","DELETE");$sb=false;if($_POST&&!$j&&in_array($_POST["Timing"],$uf["Timing"])&&in_array($_POST["Event"],$tf)&&in_array($_POST["Type"],$uf["Type"])){$jf=" $_POST[Timing] $_POST[Event]";$rd=" ON ".table($a);$sb=drop_create("DROP TRIGGER ".idf_escape($_GET["name"]).($x=="pgsql"?$rd:""),"CREATE TRIGGER ".idf_escape($_POST["Trigger"]).($x=="mssql"?$rd.$jf:$jf.$rd).rtrim(" $_POST[Type]\n$_POST[Statement]",";").";",ME."table=".urlencode($a),'Trigger has been dropped.','Trigger has been altered.','Trigger has been created.',$_GET["name"]);}page_header(($_GET["name"]!=""?'Alter trigger'.": ".h($_GET["name"]):'Create trigger'),$j,array("table"=>$a));$K=$_POST;if(!$K)$K=trigger($_GET["name"])+array("Trigger"=>$a."_bi");echo'
<form action="" method="post" id="form">
<table cellspacing="0">
<tr><th>Time<td>',html_select("Timing",$uf["Timing"],$K["Timing"],"if (/^".preg_quote($a,"/")."_[ba][iud]$/.test(this.form['Trigger'].value)) this.form['Trigger'].value = '".js_escape($a)."_' + selectValue(this).charAt(0).toLowerCase() + selectValue(this.form['Event']).charAt(0).toLowerCase();"),'<tr><th>Event<td>',html_select("Event",$tf,$K["Event"],"this.form['Timing'].onchange();"),'<tr><th>Type<td>',html_select("Type",$uf["Type"],$K["Type"]),'</table>
<p>Name: <input name="Trigger" value="',h($K["Trigger"]),'" maxlength="64">
<p>';textarea("Statement",$K["Statement"]);echo'<p>
<input type="submit" value="Save">
';if($_GET["name"]!=""){echo'<input type="submit" name="drop" value="Drop"',confirm(),'>';}if($sb){echo'<input type="hidden" name="dropped" value="1">';}echo'<input type="hidden" name="token" value="',$U,'">
</form>
';}elseif(isset($_GET["user"])){$fa=$_GET["user"];$ee=array(""=>array("All privileges"=>""));foreach(get_rows("SHOW PRIVILEGES")as$K){foreach(explode(",",($K["Privilege"]=="Grant option"?"":$K["Context"]))as$Wa)$ee[$Wa][$K["Privilege"]]=$K["Comment"];}$ee["Server Admin"]+=$ee["File access on server"];$ee["Databases"]["Create routine"]=$ee["Procedures"]["Create routine"];unset($ee["Procedures"]["Create routine"]);$ee["Columns"]=array();foreach(array("Select","Insert","Update","References")as$X)$ee["Columns"][$X]=$ee["Tables"][$X];unset($ee["Server Admin"]["Usage"]);foreach($ee["Tables"]as$y=>$X)unset($ee["Databases"][$y]);$id=array();if($_POST){foreach($_POST["objects"]as$y=>$X)$id[$X]=(array)$id[$X]+(array)$_POST["grants"][$y];}$gc=array();$pd="";if(isset($_GET["host"])&&($I=$f->query("SHOW GRANTS FOR ".q($fa)."@".q($_GET["host"])))){while($K=$I->fetch_row()){if(preg_match('~GRANT (.*) ON (.*) TO ~',$K[0],$B)&&preg_match_all('~ *([^(,]*[^ ,(])( *\\([^)]+\\))?~',$B[1],$Rc,PREG_SET_ORDER)){foreach($Rc
as$X){if($X[1]!="USAGE")$gc["$B[2]$X[2]"][$X[1]]=true;if(ereg(' WITH GRANT OPTION',$K[0]))$gc["$B[2]$X[2]"]["GRANT OPTION"]=true;}}if(preg_match("~ IDENTIFIED BY PASSWORD '([^']+)~",$K[0],$B))$pd=$B[1];}}if($_POST&&!$j){$qd=(isset($_GET["host"])?q($fa)."@".q($_GET["host"]):"''");$jd=q($_POST["user"])."@".q($_POST["host"]);$Qd=q($_POST["pass"]);if($_POST["drop"])query_redirect("DROP USER $qd",ME."privileges=",'User has been dropped.');else{$bb=false;if($qd!=$jd){$bb=queries(($f->server_info<5?"GRANT USAGE ON *.* TO":"CREATE USER")." $jd IDENTIFIED BY".($_POST["hashed"]?" PASSWORD":"")." $Qd");$j=!$bb;}elseif($_POST["pass"]!=$pd||!$_POST["hashed"])queries("SET PASSWORD FOR $jd = ".($_POST["hashed"]?$Qd:"PASSWORD($Qd)"));if(!$j){$te=array();foreach($id
as$md=>$r){if(isset($_GET["grant"]))$r=array_filter($r);$r=array_keys($r);if(isset($_GET["grant"]))$te=array_diff(array_keys(array_filter($id[$md],'strlen')),$r);elseif($qd==$jd){$od=array_keys((array)$gc[$md]);$te=array_diff($od,$r);$r=array_diff($r,$od);unset($gc[$md]);}if(preg_match('~^(.+)\\s*(\\(.*\\))?$~U',$md,$B)&&(!grant("REVOKE",$te,$B[2]," ON $B[1] FROM $jd")||!grant("GRANT",$r,$B[2]," ON $B[1] TO $jd"))){$j=true;break;}}}if(!$j&&isset($_GET["host"])){if($qd!=$jd)queries("DROP USER $qd");elseif(!isset($_GET["grant"])){foreach($gc
as$md=>$te){if(preg_match('~^(.+)(\\(.*\\))?$~U',$md,$B))grant("REVOKE",array_keys($te),$B[2]," ON $B[1] FROM $jd");}}}queries_redirect(ME."privileges=",(isset($_GET["host"])?'User has been altered.':'User has been created.'),!$j);if($bb)$f->query("DROP USER $jd");}}page_header((isset($_GET["host"])?'Username'.": ".h("$fa@$_GET[host]"):'Create user'),$j,array("privileges"=>array('','Privileges')));if($_POST){$K=$_POST;$gc=$id;}else{$K=$_GET+array("host"=>$f->result("SELECT SUBSTRING_INDEX(CURRENT_USER, '@', -1)"));$K["pass"]=$pd;if($pd!="")$K["hashed"]=true;$gc[(DB!=""&&!isset($_GET["host"])?idf_escape(addcslashes(DB,"%_")):"").".*"]=array();}echo'<form action="" method="post">
<table cellspacing="0">
<tr><th>Server<td><input name="host" maxlength="60" value="',h($K["host"]),'">
<tr><th>Username<td><input name="user" maxlength="16" value="',h($K["user"]),'">
<tr><th>Password<td><input id="pass" name="pass" value="',h($K["pass"]),'">
';if(!$K["hashed"]){echo'<script type="text/javascript">typePassword(document.getElementById(\'pass\'));</script>';}echo
checkbox("hashed",1,$K["hashed"],'Hashed',"typePassword(this.form['pass'], this.checked);"),'</table>

';echo"<table cellspacing='0'>\n","<thead><tr><th colspan='2'><a href='http://dev.mysql.com/doc/refman/".substr($f->server_info,0,3)."/en/grant.html#priv_level' target='_blank' rel='noreferrer'>".'Privileges'."</a>";$s=0;foreach($gc
as$md=>$r){echo'<th>'.($md!="*.*"?"<input name='objects[$s]' value='".h($md)."' size='10'>":"<input type='hidden' name='objects[$s]' value='*.*' size='10'>*.*");$s++;}echo"</thead>\n";foreach(array(""=>"","Server Admin"=>'Server',"Databases"=>'Database',"Tables"=>'Table',"Columns"=>'Column',"Procedures"=>'Routine',)as$Wa=>$lb){foreach((array)$ee[$Wa]as$de=>$Qa){echo"<tr".odd()."><td".($lb?">$lb<td":" colspan='2'").' lang="en" title="'.h($Qa).'">'.h($de);$s=0;foreach($gc
as$md=>$r){$D="'grants[$s][".h(strtoupper($de))."]'";$Y=$r[strtoupper($de)];if($Wa=="Server Admin"&&$md!=(isset($gc["*.*"])?"*.*":".*"))echo"<td>&nbsp;";elseif(isset($_GET["grant"]))echo"<td><select name=$D><option><option value='1'".($Y?" selected":"").">".'Grant'."<option value='0'".($Y=="0"?" selected":"").">".'Revoke'."</select>";else
echo"<td align='center'><input type='checkbox' name=$D value='1'".($Y?" checked":"").($de=="All privileges"?" id='grants-$s-all'":($de=="Grant option"?"":" onclick=\"if (this.checked) formUncheck('grants-$s-all');\"")).">";$s++;}}}echo"</table>\n",'<p>
<input type="submit" value="Save">
';if(isset($_GET["host"])){echo'<input type="submit" name="drop" value="Drop"',confirm(),'>';}echo'<input type="hidden" name="token" value="',$U,'">
</form>
';}elseif(isset($_GET["processlist"])){if(support("kill")&&$_POST&&!$j){$Cc=0;foreach((array)$_POST["kill"]as$X){if(queries("KILL ".(+$X)))$Cc++;}queries_redirect(ME."processlist=",lang(array('%d process has been killed.','%d processes have been killed.'),$Cc),$Cc||!$_POST["kill"]);}page_header('Process list',$j);echo'
<form action="" method="post">
<table cellspacing="0" onclick="tableClick(event);" class="nowrap checkable">
';$s=-1;foreach(process_list()as$s=>$K){if(!$s)echo"<thead><tr lang='en'>".(support("kill")?"<th>&nbsp;":"")."<th>".implode("<th>",array_keys($K))."</thead>\n";echo"<tr".odd().">".(support("kill")?"<td>".checkbox("kill[]",$K["Id"],0):"");foreach($K
as$y=>$X)echo"<td>".(($x=="sql"&&$y=="Info"&&$K["Command"]=="Query"&&$X!="")||($x=="pgsql"&&$y=="current_query"&&$X!="<IDLE>")||($x=="oracle"&&$y=="sql_text"&&$X!="")?"<code class='jush-$x'>".shorten_utf8($X,100,"</code>").' <a href="'.h(ME.($K["db"]!=""?"db=".urlencode($K["db"])."&":"")."sql=".urlencode($X)).'">'.'Edit'.'</a>':nbsp($X));echo"\n";}echo'</table>
<script type=\'text/javascript\'>tableCheck();</script>
<p>
';if(support("kill")){echo($s+1)."/".sprintf('%d in total',$f->result("SELECT @@max_connections")),"<p><input type='submit' value='".'Kill'."'>\n";}echo'<input type="hidden" name="token" value="',$U,'">
</form>
';}elseif(isset($_GET["select"])){$a=$_GET["select"];$T=table_status($a);$v=indexes($a);$l=fields($a);$n=column_foreign_keys($a);if($T["Oid"]=="t")$v[]=array("type"=>"PRIMARY","columns"=>array("oid"));parse_str($_COOKIE["adminer_import"],$la);$ue=array();$e=array();$hf=null;foreach($l
as$y=>$k){$D=$b->fieldName($k);if(isset($k["privileges"]["select"])&&$D!=""){$e[$y]=html_entity_decode(strip_tags($D));if(ereg('text|lob',$k["type"]))$hf=$b->selectLengthProcess();}$ue+=$k["privileges"];}list($N,$hc)=$b->selectColumnsProcess($e,$v);$Z=$b->selectSearchProcess($l,$v);$zd=$b->selectOrderProcess($l,$v);$z=$b->selectLimitProcess();$ec=($N?implode(", ",$N):($T["Oid"]=="t"?"oid, ":"")."*")."\nFROM ".table($a);$ic=($hc&&count($hc)<count($N)?"\nGROUP BY ".implode(", ",$hc):"").($zd?"\nORDER BY ".implode(", ",$zd):"");if($_GET["val"]&&is_ajax()){header("Content-Type: text/plain; charset=utf-8");foreach($_GET["val"]as$Bf=>$K)echo$f->result("SELECT".limit(idf_escape(key($K))." FROM ".table($a)," WHERE ".where_check($Bf).($Z?" AND ".implode(" AND ",$Z):"").($zd?" ORDER BY ".implode(", ",$zd):""),1));exit;}if($_POST&&!$j){$Qf="(".implode(") OR (",array_map('where_check',(array)$_POST["check"])).")";$ae=$Df=null;foreach($v
as$u){if($u["type"]=="PRIMARY"){$ae=array_flip($u["columns"]);$Df=($N?$ae:array());break;}}foreach((array)$Df
as$y=>$X){if(in_array(idf_escape($y),$N))unset($Df[$y]);}if($_POST["export"]){cookie("adminer_import","output=".urlencode($_POST["output"])."&format=".urlencode($_POST["format"]));dump_headers($a);$b->dumpTable($a,"");if(!is_array($_POST["check"])||$Df===array()){$Pf=$Z;if(is_array($_POST["check"]))$Pf[]="($Qf)";$H="SELECT $ec".($Pf?"\nWHERE ".implode(" AND ",$Pf):"").$ic;}else{$_f=array();foreach($_POST["check"]as$X)$_f[]="(SELECT".limit($ec,"\nWHERE ".($Z?implode(" AND ",$Z)." AND ":"").where_check($X).$ic,1).")";$H=implode(" UNION ALL ",$_f);}$b->dumpData($a,"table",$H);exit;}if(!$b->selectEmailProcess($Z,$n)){if($_POST["save"]||$_POST["delete"]){$I=true;$ma=0;$H=table($a);$P=array();if(!$_POST["delete"]){foreach($e
as$D=>$X){$X=process_input($l[$D]);if($X!==null){if($_POST["clone"])$P[idf_escape($D)]=($X!==false?$X:idf_escape($D));elseif($X!==false)$P[]=idf_escape($D)." = $X";}}$H.=($_POST["clone"]?" (".implode(", ",array_keys($P)).")\nSELECT ".implode(", ",$P)."\nFROM ".table($a):" SET\n".implode(",\n",$P));}if($_POST["delete"]||$P){$Oa="UPDATE";if($_POST["delete"]){$Oa="DELETE";$H="FROM $H";}if($_POST["clone"]){$Oa="INSERT";$H="INTO $H";}if($_POST["all"]||($Df===array()&&$_POST["check"])||count($hc)<count($N)){$I=queries($Oa." $H".($_POST["all"]?($Z?"\nWHERE ".implode(" AND ",$Z):""):"\nWHERE $Qf"));$ma=$f->affected_rows;}else{foreach((array)$_POST["check"]as$X){$I=queries($Oa.limit1($H,"\nWHERE ".where_check($X)));if(!$I)break;$ma+=$f->affected_rows;}}}queries_redirect(remove_from_uri("page"),lang(array('%d item has been affected.','%d items have been affected.'),$ma),$I);}elseif(!$_POST["import"]){if(!$_POST["val"])$j='Double click on a value to modify it.';else{$I=true;$ma=0;foreach($_POST["val"]as$Bf=>$K){$P=array();foreach($K
as$y=>$X){$y=bracket_escape($y,1);$P[]=idf_escape($y)." = ".(ereg('char|text',$l[$y]["type"])||$X!=""?$b->processInput($l[$y],$X):"NULL");}$H=table($a)." SET ".implode(", ",$P);$Pf=" WHERE ".where_check($Bf).($Z?" AND ".implode(" AND ",$Z):"");$I=queries("UPDATE".(count($hc)<count($N)?" $H$Pf":limit1($H,$Pf)));if(!$I)break;$ma+=$f->affected_rows;}queries_redirect(remove_from_uri(),lang(array('%d item has been affected.','%d items have been affected.'),$ma),$I);}}elseif(is_string($Ub=get_file("csv_file",true))){cookie("adminer_import","output=".urlencode($la["output"])."&format=".urlencode($_POST["separator"]));$I=true;$Ma=array_keys($l);preg_match_all('~(?>"[^"]*"|[^"\\r\\n]+)+~',$Ub,$Rc);$ma=count($Rc[0]);begin();$Ee=($_POST["separator"]=="csv"?",":($_POST["separator"]=="tsv"?"\t":";"));foreach($Rc[0]as$y=>$X){preg_match_all("~((\"[^\"]*\")+|[^$Ee]*)$Ee~",$X.$Ee,$Sc);if(!$y&&!array_diff($Sc[1],$Ma)){$Ma=$Sc[1];$ma--;}else{$P=array();foreach($Sc[1]as$s=>$Ja)$P[idf_escape($Ma[$s])]=($Ja==""&&$l[$Ma[$s]]["null"]?"NULL":q(str_replace('""','"',preg_replace('~^"|"$~','',$Ja))));$I=insert_update($a,$P,$ae);if(!$I)break;}}if($I)queries("COMMIT");queries_redirect(remove_from_uri("page"),lang(array('%d row has been imported.','%d rows have been imported.'),$ma),$I);queries("ROLLBACK");}else$j=upload_error($Ub);}}$Xe=$b->tableName($T);page_header('Select'.": $Xe",$j);session_write_close();$P=null;if(isset($ue["insert"])){$P="";foreach((array)$_GET["where"]as$X){if(count($n[$X["col"]])==1&&($X["op"]=="="||(!$X["op"]&&!ereg('[_%]',$X["val"]))))$P.="&set".urlencode("[".bracket_escape($X["col"])."]")."=".urlencode($X["val"]);}}$b->selectLinks($T,$P);if(!$e)echo"<p class='error'>".'Unable to select the table'.($l?".":": ".error())."\n";else{echo"<form action='' id='form'>\n","<div style='display: none;'>";hidden_fields_get();echo(DB!=""?'<input type="hidden" name="db" value="'.h(DB).'">'.(isset($_GET["ns"])?'<input type="hidden" name="ns" value="'.h($_GET["ns"]).'">':""):"");echo'<input type="hidden" name="select" value="'.h($a).'">',"</div>\n";$b->selectColumnsPrint($N,$e);$b->selectSearchPrint($Z,$e,$v);$b->selectOrderPrint($zd,$e,$v);$b->selectLimitPrint($z);$b->selectLengthPrint($hf);$b->selectActionPrint($v);echo"</form>\n";$E=$_GET["page"];if($E=="last"){$cc=$f->result("SELECT COUNT(*) FROM ".table($a).($Z?" WHERE ".implode(" AND ",$Z):""));$E=floor(max(0,$cc-1)/$z);}$H="SELECT".limit((+$z&&$hc&&count($hc)<count($N)&&$x=="sql"?"SQL_CALC_FOUND_ROWS ":"").$ec,($Z?"\nWHERE ".implode(" AND ",$Z):"").$ic,($z!=""?+$z:null),($E?$z*$E:0),"\n");echo$b->selectQuery($H);$I=$f->query($H);if(!$I)echo"<p class='error'>".error()."\n";else{if($x=="mssql")$I->seek($z*$E);$_b=array();echo"<form action='' method='post' enctype='multipart/form-data'>\n";$L=array();while($K=$I->fetch_assoc()){if($E&&$x=="oracle")unset($K["RNUM"]);$L[]=$K;}if($_GET["page"]!="last")$cc=(+$z&&$hc&&count($hc)<count($N)?($x=="sql"?$f->result(" SELECT FOUND_ROWS()"):$f->result("SELECT COUNT(*) FROM ($H) x")):count($L));if(!$L)echo"<p class='message'>".'No rows.'."\n";else{$ya=$b->backwardKeys($a,$Xe);echo"<table cellspacing='0' class='nowrap checkable' onclick='tableClick(event);' onkeydown='return editingKeydown(event);'>\n","<thead><tr>".(!$hc&&$N?"":"<td><input type='checkbox' id='all-page' onclick='formCheck(this, /check/);'> <a href='".h($_GET["modify"]?remove_from_uri("modify"):$_SERVER["REQUEST_URI"]."&modify=1")."'>".'edit'."</a>");$hd=array();$q=array();reset($N);$je=1;foreach($L[0]as$y=>$X){if($T["Oid"]!="t"||$y!="oid"){$X=$_GET["columns"][key($N)];$k=$l[$N?($X?$X["col"]:current($N)):$y];$D=($k?$b->fieldName($k,$je):"*");if($D!=""){$je++;$hd[$y]=$D;$Na=idf_escape($y);$oc=remove_from_uri('(order|desc)[^=]*|page').'&order%5B0%5D='.urlencode($y);echo'<th><a href="'.h($oc).'">'.(!$N||$X?apply_sql_function($X["fun"],$D):h(current($N)))."</a>";echo"<a href='".h("$oc&desc%5B0%5D=1")."' title='".'descending'."' class='text'> ↓</a>";}$q[$y]=$X["fun"];next($N);}}$Mc=array();if($_GET["modify"]){foreach($L
as$K){foreach($K
as$y=>$X)$Mc[$y]=max($Mc[$y],min(40,strlen(utf8_decode($X))));}}echo($ya?"<th>".'Relations':"")."</thead>\n";foreach($b->rowDescriptions($L,$n)as$C=>$K){$Af=unique_array($L[$C],$v);$Bf="";foreach($Af
as$y=>$X)$Bf.="&".($X!==null?urlencode("where[".bracket_escape($y)."]")."=".urlencode($X):"null%5B%5D=".urlencode($y));echo"<tr".odd().">".(!$hc&&$N?"":"<td>".checkbox("check[]",substr($Bf,1),in_array(substr($Bf,1),(array)$_POST["check"]),"","this.form['all'].checked = false; formUncheck('all-page');").(count($hc)<count($N)||information_schema(DB)?"":" <a href='".h(ME."edit=".urlencode($a).$Bf)."'>".'edit'."</a>"));foreach($K
as$y=>$X){if(isset($hd[$y])){$k=$l[$y];if($X!=""&&(!isset($_b[$y])||$_b[$y]!=""))$_b[$y]=(is_mail($X)?$hd[$y]:"");$_="";$X=$b->editVal($X,$k);if($X!==null){if(ereg('blob|bytea|raw|file',$k["type"])&&$X!="")$_=h(ME.'download='.urlencode($a).'&field='.urlencode($y).$Bf);if($X==="")$X="&nbsp;";elseif(is_utf8($X)){if($hf!=""&&ereg('text|blob',$k["type"]))$X=shorten_utf8($X,max(0,+$hf));else$X=h($X);}if(!$_){foreach((array)$n[$y]as$m){if(count($n[$y])==1||end($m["source"])==$y){$_="";foreach($m["source"]as$s=>$Ie)$_.=where_link($s,$m["target"][$s],$L[$C][$Ie]);$_=h(($m["db"]!=""?preg_replace('~([?&]db=)[^&]+~','\\1'.urlencode($m["db"]),ME):ME).'select='.urlencode($m["table"]).$_);if(count($m["source"])==1)break;}}}if($y=="COUNT(*)"){$_=h(ME."select=".urlencode($a));$s=0;foreach((array)$_GET["where"]as$W){if(!array_key_exists($W["col"],$Af))$_.=h(where_link($s++,$W["col"],$W["val"],$W["op"]));}foreach($Af
as$Bc=>$W)$_.=h(where_link($s++,$Bc,$W));}}if(!$_){if(is_mail($X))$_="mailto:$X";if($he=is_url($K[$y]))$_=($he=="http"&&$ba?$K[$y]:"$he://www.adminer.org/redirect/?url=".urlencode($K[$y]));}$t=h("val[$Bf][".bracket_escape($y)."]");$Y=$_POST["val"][$Bf][bracket_escape($y)];$kc=h($Y!==null?$Y:$K[$y]);$Qc=strpos($X,"<i>...</i>");$xb=is_utf8($X)&&$L[$C][$y]==$K[$y]&&!$q[$y];$gf=ereg('text|lob',$k["type"]);echo(($_GET["modify"]&&$xb)||$Y!==null?"<td>".($gf?"<textarea name='$t' cols='30' rows='".(substr_count($K[$y],"\n")+1)."'>$kc</textarea>":"<input name='$t' value='$kc' size='$Mc[$y]'>"):"<td id='$t' ondblclick=\"".($xb?"selectDblClick(this, event".($Qc?", 2":($gf?", 1":"")).")":"alert('".h('Use edit link to modify this value.')."')").";\">".$b->selectVal($X,$_,$k));}}if($ya)echo"<td>";$b->backwardKeysPrint($ya,$L[$C]);echo"</tr>\n";}echo"</table>\n",(!$hc&&$N?"":"<script type='text/javascript'>tableCheck();</script>\n");}if($L||$E){$Jb=true;if($_GET["page"]!="last"&&+$z&&count($hc)>=count($N)&&($cc>=$z||$E)){$cc=found_rows($T,$Z);if($cc<max(1e4,2*($E+1)*$z)){ob_flush();flush();$cc=$f->result("SELECT COUNT(*) FROM ".table($a).($Z?" WHERE ".implode(" AND ",$Z):""));}else$Jb=false;}echo"<p class='pages'>";if(+$z&&$cc>$z){$Uc=floor(($cc-1)/$z);echo'<a href="'.h(remove_from_uri("page"))."\" onclick=\"pageClick(this.href, +prompt('".'Page'."', '".($E+1)."'), event); return false;\">".'Page'."</a>:",pagination(0,$E).($E>5?" ...":"");for($s=max(1,$E-4);$s<min($Uc,$E+5);$s++)echo
pagination($s,$E);echo($E+5<$Uc?" ...":"").($Jb?pagination($Uc,$E):' <a href="'.h(remove_from_uri()."&page=last").'">'.'last'."</a>");}echo" (".($Jb?"":"~ ").lang(array('%d row','%d rows'),$cc).") ".checkbox("all",1,0,'whole result')."\n";if($b->selectCommandPrint()){echo'<fieldset><legend>Edit</legend><div>
<input type="submit" value="Save"',($_GET["modify"]?'':' title="'.'Double click on a value to modify it.'.'" class="jsonly"');?>>
<input type="submit" name="edit" value="Edit">
<input type="submit" name="clone" value="Clone">
<input type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure? (' + (this.form['all'].checked ? <?php echo$cc,' : formChecked(this, /check/)) + \')\');">
</div></fieldset>
';}$bc=$b->dumpFormat();if($bc){print_fieldset("export",'Export');$Hd=$b->dumpOutput();echo($Hd?html_select("output",$Hd,$la["output"])." ":""),html_select("format",$bc,$la["format"])," <input type='submit' name='export' value='".'Export'."'>\n","</div></fieldset>\n";}}if($b->selectImportPrint()){print_fieldset("import",'Import',!$L);echo"<input type='file' name='csv_file'> ",html_select("separator",array("csv"=>"CSV,","csv;"=>"CSV;","tsv"=>"TSV"),$la["format"],1);echo" <input type='submit' name='import' value='".'Import'."'>","<input type='hidden' name='token' value='$U'>\n","</div></fieldset>\n";}$b->selectEmailPrint(array_filter($_b,'strlen'),$e);echo"</form>\n";}}}elseif(isset($_GET["variables"])){$Me=isset($_GET["status"]);page_header($Me?'Status':'Variables');$Kf=($Me?show_status():show_variables());if(!$Kf)echo"<p class='message'>".'No rows.'."\n";else{echo"<table cellspacing='0'>\n";foreach($Kf
as$y=>$X){echo"<tr>","<th><code class='jush-".$x.($Me?"status":"set")."'>".h($y)."</code>","<td>".nbsp($X);}echo"</table>\n";}}elseif(isset($_GET["script"])){header("Content-Type: text/javascript; charset=utf-8");if($_GET["script"]=="db"){$Ue=array("Data_length"=>0,"Index_length"=>0,"Data_free"=>0);foreach(table_status()as$T){$t=js_escape($T["Name"]);json_row("Comment-$t",nbsp($T["Comment"]));if(!is_view($T)){foreach(array("Engine","Collation")as$y)json_row("$y-$t",nbsp($T[$y]));foreach($Ue+array("Auto_increment"=>0,"Rows"=>0)as$y=>$X){if($T[$y]!=""){$X=number_format($T[$y],0,'.',',');json_row("$y-$t",($y=="Rows"&&$X&&($T["Engine"]=="InnoDB"||$T["Engine"]=="table")?"~ $X":$X));if(isset($Ue[$y]))$Ue[$y]+=($T["Engine"]!="InnoDB"||$y!="Data_free"?$T[$y]:0);}elseif(array_key_exists($y,$T))json_row("$y-$t");}}}foreach($Ue
as$y=>$X)json_row("sum-$y",number_format($X,0,'.',','));json_row("");}else{foreach(count_tables($b->databases())as$i=>$X)json_row("tables-".js_escape($i),$X);json_row("");}exit;}else{$df=array_merge((array)$_POST["tables"],(array)$_POST["views"]);if($df&&!$j&&!$_POST["search"]){$I=true;$Yc="";if($x=="sql"&&count($_POST["tables"])>1&&($_POST["drop"]||$_POST["truncate"]||$_POST["copy"]))queries("SET foreign_key_checks = 0");if($_POST["truncate"]){if($_POST["tables"])$I=truncate_tables($_POST["tables"]);$Yc='Tables have been truncated.';}elseif($_POST["move"]){$I=move_tables((array)$_POST["tables"],(array)$_POST["views"],$_POST["target"]);$Yc='Tables have been moved.';}elseif($_POST["copy"]){$I=copy_tables((array)$_POST["tables"],(array)$_POST["views"],$_POST["target"]);$Yc='Tables have been copied.';}elseif($_POST["drop"]){if($_POST["views"])$I=drop_views($_POST["views"]);if($I&&$_POST["tables"])$I=drop_tables($_POST["tables"]);$Yc='Tables have been dropped.';}elseif($x!="sql"){$I=($x=="sqlite"?queries("VACUUM"):apply_queries("VACUUM".($_POST["optimize"]?"":" ANALYZE"),$_POST["tables"]));$Yc='Tables have been optimized.';}elseif($_POST["tables"]&&($I=queries(($_POST["optimize"]?"OPTIMIZE":($_POST["check"]?"CHECK":($_POST["repair"]?"REPAIR":"ANALYZE")))." TABLE ".implode(", ",array_map('idf_escape',$_POST["tables"]))))){while($K=$I->fetch_assoc())$Yc.="<b>".h($K["Table"])."</b>: ".h($K["Msg_text"])."<br>";}queries_redirect(substr(ME,0,-1),$Yc,$I);}page_header(($_GET["ns"]==""?'Database'.": ".h(DB):'Schema'.": ".h($_GET["ns"])),$j,true);if($b->homepage()){if($_GET["ns"]!==""){echo"<h3>".'Tables and views'."</h3>\n";$cf=tables_list();if(!$cf)echo"<p class='message'>".'No tables.'."\n";else{echo"<form action='' method='post'>\n","<p>".'Search data in tables'.": <input name='query' value='".h($_POST["query"])."'> <input type='submit' name='search' value='".'Search'."'>\n";if($_POST["search"]&&$_POST["query"]!="")search_tables();echo"<table cellspacing='0' class='nowrap checkable' onclick='tableClick(event);'>\n",'<thead><tr class="wrap"><td><input id="check-all" type="checkbox" onclick="formCheck(this, /^(tables|views)\[/);">','<th>'.'Table','<td>'.'Engine','<td>'.'Collation','<td>'.'Data Length','<td>'.'Index Length','<td>'.'Data Free','<td>'.'Auto Increment','<td>'.'Rows',(support("comment")?'<td>'.'Comment':''),"</thead>\n";foreach($cf
as$D=>$V){$Lf=($V!==null&&!eregi("table",$V));echo'<tr'.odd().'><td>'.checkbox(($Lf?"views[]":"tables[]"),$D,in_array($D,$df,true),"","formUncheck('check-all');"),'<th><a href="'.h(ME).'table='.urlencode($D).'" title="'.'Show structure'.'">'.h($D).'</a>';if($Lf){echo'<td colspan="6"><a href="'.h(ME)."view=".urlencode($D).'" title="'.'Alter view'.'">'.'View'.'</a>','<td align="right"><a href="'.h(ME)."select=".urlencode($D).'" title="'.'Select data'.'">?</a>';}else{foreach(array("Engine"=>array(),"Collation"=>array(),"Data_length"=>array("create",'Alter table'),"Index_length"=>array("indexes",'Alter indexes'),"Data_free"=>array("edit",'New item'),"Auto_increment"=>array("auto_increment=1&create",'Alter table'),"Rows"=>array("select",'Select data'),)as$y=>$_)echo($_?"<td align='right'><a href='".h(ME."$_[0]=").urlencode($D)."' id='$y-".h($D)."' title='$_[1]'>?</a>":"<td id='$y-".h($D)."'>&nbsp;");}echo(support("comment")?"<td id='Comment-".h($D)."'>&nbsp;":"");}echo"<tr><td>&nbsp;<th>".sprintf('%d in total',count($cf)),"<td>".nbsp($x=="sql"?$f->result("SELECT @@storage_engine"):""),"<td>".nbsp(db_collation(DB,collations()));foreach(array("Data_length","Index_length","Data_free")as$y)echo"<td align='right' id='sum-$y'>&nbsp;";echo"</table>\n","<script type='text/javascript'>tableCheck();</script>\n";if(!information_schema(DB)){echo"<p>".(ereg('^(sql|sqlite|pgsql)$',$x)?($x!="sqlite"?"<input type='submit' value='".'Analyze'."'> ":"")."<input type='submit' name='optimize' value='".'Optimize'."'> ":"").($x=="sql"?"<input type='submit' name='check' value='".'Check'."'> <input type='submit' name='repair' value='".'Repair'."'> ":"")."<input type='submit' name='truncate' value='".'Truncate'."'".confirm("formChecked(this, /tables/)")."> <input type='submit' name='drop' value='".'Drop'."'".confirm("formChecked(this, /tables|views/)").">\n";$h=(support("scheme")?schemas():$b->databases());if(count($h)!=1&&$x!="sqlite"){$i=(isset($_POST["target"])?$_POST["target"]:(support("scheme")?$_GET["ns"]:DB));echo"<p>".'Move to other database'.": ",($h?html_select("target",$h,$i):'<input name="target" value="'.h($i).'">')," <input type='submit' name='move' value='".'Move'."'>",(support("copy")?" <input type='submit' name='copy' value='".'Copy'."'>":""),"\n";}echo"<input type='hidden' name='token' value='$U'>\n";}echo"</form>\n";}echo'<p><a href="'.h(ME).'create=">'.'Create table'."</a>\n";if(support("view"))echo'<a href="'.h(ME).'view=">'.'Create view'."</a>\n";if(support("routine")){echo"<h3>".'Routines'."</h3>\n";$ye=routines();if($ye){echo"<table cellspacing='0'>\n",'<thead><tr><th>'.'Name'.'<td>'.'Type'.'<td>'.'Return type'."<td>&nbsp;</thead>\n";odd('');foreach($ye
as$K){echo'<tr'.odd().'>','<th><a href="'.h(ME).($K["ROUTINE_TYPE"]!="PROCEDURE"?'callf=':'call=').urlencode($K["ROUTINE_NAME"]).'">'.h($K["ROUTINE_NAME"]).'</a>','<td>'.h($K["ROUTINE_TYPE"]),'<td>'.h($K["DTD_IDENTIFIER"]),'<td><a href="'.h(ME).($K["ROUTINE_TYPE"]!="PROCEDURE"?'function=':'procedure=').urlencode($K["ROUTINE_NAME"]).'">'.'Alter'."</a>";}echo"</table>\n";}echo'<p>'.(support("procedure")?'<a href="'.h(ME).'procedure=">'.'Create procedure'.'</a> ':'').'<a href="'.h(ME).'function=">'.'Create function'."</a>\n";}if(support("event")){echo"<h3>".'Events'."</h3>\n";$L=get_rows("SHOW EVENTS");if($L){echo"<table cellspacing='0'>\n","<thead><tr><th>".'Name'."<td>".'Schedule'."<td>".'Start'."<td>".'End'."</thead>\n";foreach($L
as$K){echo"<tr>",'<th><a href="'.h(ME).'event='.urlencode($K["Name"]).'">'.h($K["Name"])."</a>","<td>".($K["Execute at"]?'At given time'."<td>".$K["Execute at"]:'Every'." ".$K["Interval value"]." ".$K["Interval field"]."<td>$K[Starts]"),"<td>$K[Ends]";}echo"</table>\n";$Ib=$f->result("SELECT @@event_scheduler");if($Ib&&$Ib!="ON")echo"<p class='error'><code class='jush-sqlset'>event_scheduler</code>: ".h($Ib)."\n";}echo'<p><a href="'.h(ME).'event=">'.'Create event'."</a>\n";}if($cf)echo"<script type='text/javascript'>ajaxSetHtml('".js_escape(ME)."script=db');</script>\n";}}}page_footer();