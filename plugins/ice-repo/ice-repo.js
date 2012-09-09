top.selRowArray = [];
top.selRepoDirArray = [];
top.selActionArray = [];
top.filetypesArray = ['coffee','css','gif','htm','html','jpg','jpeg','js','less','php','png','rb','rbx','rhtml','ruby','txt','zip'];

doRepo = function(repo) {
	document.showRepo.repo.value = repo;
	document.showRepo.submit();
}

updateSelection = function(elem,row,repoDir,action) {
	if (elem.checked) {
		top.selRowArray.push(row);
		top.selRepoDirArray.push(repoDir);
		top.selActionArray.push(action);
	} else {
		arrayIndex = top.selRowArray.indexOf(row);
		top.selRowArray.splice(arrayIndex,1);
		top.selRepoDirArray.splice(arrayIndex,1);
		top.selActionArray.splice(arrayIndex,1);
	};
}

getContent = function(thisRow,path) {
	if("undefined" == typeof overOption || !overOption) {
		if ("undefined" == typeof lastRow || lastRow!=thisRow || get('row'+thisRow+'Content').innerHTML=="") {
			for (i=1;i<=rowID;i++) {
				get('row'+i+'Content').innerHTML = "";
				get('row'+i+'Content').style.display = "none";
			}
			repo = top.repo + "/" + path;
			dir = top.path + "/" + path;
			document.fcForm.rowID.value = thisRow;
			document.fcForm.repo.value = repo;
			document.fcForm.dir.value = dir;
			document.fcForm.action.value = "view";
			document.fcForm.submit();
		} else {
			get('row'+thisRow+'Content').innerHTML = "";
			get('row'+thisRow+'Content').style.display = "none";
		}
		lastRow = thisRow;
	}
}
	
commitChanges = function() {
	if(top.selRowArray.length>0) {
		if (document.fcForm.title.value!="Title..." && document.fcForm.message.value!="Message...") {
			get('blackMask','top').style.display = "block";
			top.selRowValue = "";
			top.selDirValue = "";
			top.selRepoValue = "";
			top.selActionValue = "";
			for (i=0;i<top.selRowArray.length;i++) {
				top.selRowValue += top.selRowArray[i];
				if (top.selActionArray[i]=="changed") {
					top.selDirValue += top.selRepoDirArray[i].split('@')[0];
					top.selRepoValue += top.selRepoDirArray[i].split('@')[1];
				}
				if (top.selActionArray[i]=="new") {
					top.selDirValue += top.selRepoDirArray[i];
					top.selRepoValue += "";
				}
				if (top.selActionArray[i]=="deleted") {
					top.selDirValue += "";
					top.selRepoValue += top.selRepoDirArray[i];
				}
				top.selActionValue += top.selActionArray[i];
				if (i<top.selRowArray.length-1) {
					top.selRowValue += ",";
					top.selDirValue += ",";
					top.selRepoValue += ",";
					top.selActionValue += ",";
				}
			}
			document.fcForm.rowID.value = top.selRowValue;
			document.fcForm.dir.value = top.selDirValue;
			document.fcForm.repo.value = top.selRepoValue;
			document.fcForm.action.value = top.selActionValue;
			document.fcForm.submit();
		} else {
			alert('Please enter a title & message for the commit');		
		}
	} else {
		alert('Please select some files/folders to commit');
	}
	return false;
}

pullContent = function(thisRow,thisPath,thisRepo,thisAction) {
	get('blackMask','top').style.display = "block";
	if (thisRow=="selected") {
		top.selRowValue = "";
		top.selDirValue = "";
		top.selRepoValue = "";
		top.selActionValue = "";
		for (i=0;i<top.selRowArray.length;i++) {
			top.selRowValue += top.selRowArray[i];
			if (top.selActionArray[i]=="changed") {
				repoUser = top.selRepoDirArray[i].split('@')[1].split('/')[0];
				repoName = top.selRepoDirArray[i].split('@')[1].split('/')[1];
				top.selDirValue += top.selRepoDirArray[i].split('@')[0];
				top.selRepoValue += top.selRepoDirArray[i].split('@')[1].replace(repoUser+"/"+repoName+"/","");
			}
			if (top.selActionArray[i]=="new") {
				top.selDirValue += top.selRepoDirArray[i];
				top.selRepoValue += "";
			}
			if (top.selActionArray[i]=="deleted") {
				repoUser = top.selRepoDirArray[i].split('/')[0];
				repoName = top.selRepoDirArray[i].split('/')[1];
				top.selDirValue += "";
				top.selRepoValue += top.selRepoDirArray[i].replace(repoUser+"/"+repoName+"/","");
			}
			top.selActionValue += top.selActionArray[i];
			if (i<top.selRowArray.length-1) {
				top.selRowValue += ",";
				top.selDirValue += ",";
				top.selRepoValue += ",";
				top.selActionValue += ",";
			}
		}
	} else {
		top.selRowValue = thisRow;
		top.selDirValue = thisPath;
		top.selRepoValue = thisRepo;
		top.selActionValue = thisAction;
	}
	top.fcFormAlias.rowID.value = top.selRowValue;
	top.fcFormAlias.dir.value = top.selDirValue;
	top.fcFormAlias.repo.value = top.selRepoValue;
	top.fcFormAlias.action.value = "PULL:"+top.selActionValue;
	top.fcFormAlias.submit();
}

getData = function() {
	if (actionArray[0]!="new") {
		repo.read('master', repoArray[0], function(err, data) {
			document.fcForm['repoContents'+rowIDArray[0]].innerHTML=data;
			if(!err) {
				removeFirstArrayItems();
				rowIDArray.length>0 ? getData() : document.fcForm.submit();
			} else {
				alert('Sorry, there was an error reading '+repoArray[0]);
			}
		});
	} else {
		removeFirstArrayItems();
		rowIDArray.length>0 ? getData() : document.fcForm.submit();
	}
}
	
sendData = function(baseTextName,newTextName) {
	repo.read('master', filePath, function(err, data) {
		dirContent = document.fcForm.fileContents.value;
		repoContent = data;
		diffUsingJS(dirContent,repoContent,baseTextName,newTextName);
		get("row"+rowID+"Content","parent").style.display = "inline-block";
	});
}
	
removeFirstArrayItems = function() {
	rowIDArray.splice(0,1);
	repoArray.splice(0,1);
	dirArray.splice(0,1);
	actionArray.splice(0,1);	
}
	
hideRow = function(row) {
	top.rowCount--;
	updateInfo('parent');
	get('checkbox'+row,'parent').checked=false;
	parent.updateSelection(get('checkbox'+row,'parent'));
	get('row'+row,'parent').style.display = get('row'+row+'Content','parent').style.display = "none";
}

function diffUsingJS (dirContent,repoContent,baseTextName,newTextName) {
	var base = difflib.stringAsLines(dirContent);
	var newtxt = difflib.stringAsLines(repoContent);
	var sm = new difflib.SequenceMatcher(base, newtxt);
	var opcodes = sm.get_opcodes();
	var diffoutputdiv = get("row"+rowID+"Content","parent");
	while (diffoutputdiv.firstChild) diffoutputdiv.removeChild(diffoutputdiv.firstChild);
	var contextSize = ""; // optional
	contextSize = contextSize ? contextSize : null;
	diffoutputdiv.appendChild(
		diffview.buildView(
			{
			baseTextLines:base,
			newTextLines:newtxt,
			opcodes:opcodes,
			baseTextName:baseTextName,
			newTextName:newTextName,
			contextSize:contextSize,
			viewType: 1 // 0 = side by side, 1 = inline
			}
		)
	)
}
	
// Add or Update files...
ffAddOrUpdate = function(row,gitRepo,action) {
	repo.write('master', gitRepo, document.fcForm['fileContents'+row].value,parent.document.fcForm.title.value+ '\n\n'+parent.document.fcForm.message.value, function(err) {
		if(!err) {
			removeFirstArrayItems();
			hideRow(row);
			top.newCount--;
			rowIDArray.length>0 ? startProcess() : get('blackMask','top').style.display = "none";
		} else {
			alert('Sorry, there was an error adding '+gitRepo);
		}
	});
}
// Delete files...
ffDelete = function(row,gitRepo,action) {
	repo.remove('master', gitRepo, function(err) {
		if(!err) {
			removeFirstArrayItems();
			hideRow(row);
			top.deletedCount--;
			rowIDArray.length>0 ? startProcess() : get('blackMask','top').style.display = "none";
		} else {
			alert('Sorry, there was an error deleting '+gitRepo);
		}
	});
}

startProcess = function() {
	if(actionArray[0]=="changed"||actionArray[0]=="new") {
		if(actionArray[0]=="changed")	{repoLoc = repoArray[0].replace(repoUser+"/"+repoName+"/","")}
		if(actionArray[0]=="new")		{repoLoc = dirArray[0].replace(top.path,'')}
		ffAddOrUpdate(rowIDArray[0],repoLoc,actionArray[0]);
	}
	if(actionArray[0]=="deleted") {
		repoLoc = repoArray[0].replace(repoUser+"/"+repoName+"/","");
		ffDelete(rowIDArray[0],repoLoc,actionArray[0]);
	}
}	

get = function(elem,context) {
	return context ? window[context].document.getElementById(elem) : document.getElementById(elem);
}
	
updateInfo = function(context) {
	get('infoPane',context).innerHTML = "<b style='font-size: 18px'>INFO:</b><br><br><b>"+top.rowCount+" files</b><br><br>"+top.changedCount+" changed<br>"+top.newCount+" new<br>"+top.deletedCount+" deleted";		
}
	
gitCommand = function(comm,value) {
	if (comm=="repo.show") {
		userRepo = value.split("@")[0].split("/");
		dir = value.split("@")[1];		
		var repo = github.getRepo(userRepo[0],userRepo[1]);
		rowID = 0;
 		repo.getTree('master?recursive=true', function(err, tree) {
			for (i=0;i<tree.length;i++) {
				repoListArray.push(tree[i].path);
				repoSHAArray.push(tree[i].sha);
			}
			var c = "", n = "", d = "";
			top.rowCount=0, top.changedCount=0, top.newCount=0, top.deletedCount=0;
			for (i=0;i<dirListArray.length;i++) {
				repoArrayPos = repoListArray.indexOf(dirListArray[i]);
				ext = dirTypeArray[i]=="dir"
					? "folder"
					: dirListArray[i].substr(dirListArray[i].lastIndexOf('.')+1);
				if (repoArrayPos == "-1") {
					rowID++;
					sE = ext == 'folder' ? ' style="cursor: default"' : '';
					cE = ext != 'folder' ? ' onClick="getContent('+rowID+',\''+dirListArray[i]+'\')"' : '';
					gE = 'onClick="pullContent('+rowID+',\''+top.path+'/'+dirListArray[i]+'\',\''+dirListArray[i]+'\',\'new\')"';
					
					n += "<div class='row' id='row"+rowID+"'"+cE+sE+">";
					n += "<input type='checkbox' class='checkbox' id='checkbox"+rowID+"' onMouseOver='overOption=true' onMouseOut='overOption=false' onClick='updateSelection(this,"+rowID+",\""+top.path+"/"+dirListArray[i]+"\",\"new\")'>";
					if (ext != 'folder' && top.filetypesArray.indexOf(ext)==-1) {ext = 'file'};
					n += "<div class='icon ext-"+ext+"'></div>"+dirListArray[i];
					n += "<div class='pullGithub' style='left: 815px' onMouseOver='overOption=true' onMouseOut='overOption=false' "+gE+">Delete from server</div><br>";
					n += "</div>";
					
					n += "<span class='rowContent' id='row"+rowID+"Content'></span>";
					top.rowCount++;
					top.newCount++;
					
				} else if (dirTypeArray[i] == "file" && dirSHAArray[i] != repoSHAArray[repoArrayPos]) {
					rowID++;
					sE = ext == 'folder' ? ' style="cursor: default"' : '';
					cE = ext != 'folder' ? ' onClick="getContent('+rowID+',\''+dirListArray[i]+'\')"' : '';
					gE = 'onClick="pullContent('+rowID+',\''+top.path+'/'+dirListArray[i]+'\',\''+dirListArray[i]+'\',\'changed\')"';
					
					c += "<div class='row' id='row"+rowID+"'"+cE+sE+">";
					c += "<input type='checkbox' class='checkbox' id='checkbox"+rowID+"' onMouseOver='overOption=true' onMouseOut='overOption=false' onClick='updateSelection(this,"+rowID+",\""+top.path+"/"+dirListArray[i]+"@"+top.repo+"/"+dirListArray[i]+"\",\"changed\")'>";
					if (ext != 'folder' && top.filetypesArray.indexOf(ext)==-1) {ext = 'file'};
					c += "<div class='icon ext-"+ext+"'></div>"+dirListArray[i];
					c += "<div class='pullGithub' onMouseOver='overOption=true' onMouseOut='overOption=false' "+gE+">Pull from Github</div><br>";
					c += "</div>";
					
					c += "<span class='rowContent' id='row"+rowID+"Content'></span>";
					top.rowCount++;
					top.changedCount++;
				}
			}
			
			for (i=0;i<repoListArray.length;i++) {
				dirArrayPos = dirListArray.indexOf(repoListArray[i]);
				ext = repoListArray[i].lastIndexOf('/') > repoListArray[i].lastIndexOf('.')
					? "folder"
					: repoListArray[i].substr(repoListArray[i].lastIndexOf('.')+1);
				if (dirArrayPos == "-1") {
					rowID++;
					sE = ext == 'folder' ? ' style="cursor: default"' : '';
					cE = ext != 'folder' ? ' onClick="getContent('+rowID+',\''+repoListArray[i]+'\')"' : '';
					gE = 'onClick="pullContent('+rowID+',\''+top.path+'/'+repoListArray[i]+'\',\''+repoListArray[i]+'\',\'deleted\')"';
					
					d += "<div class='row' id='row"+rowID+"'"+cE+sE+">";
					d += "<input type='checkbox' class='checkbox' id='checkbox"+rowID+"' onMouseOver='overOption=true' onMouseOut='overOption=false' onClick='updateSelection(this,"+rowID+",\""+top.repo+"/"+repoListArray[i]+"\",\"deleted\")'>";
					if (ext != 'folder' && top.filetypesArray.indexOf(ext)==-1) {ext = 'file'};
					d += "<div class='icon ext-"+ext+"'></div>"+repoListArray[i];
					d += "<div class='pullGithub' onMouseOver='overOption=true' onMouseOut='overOption=false' "+gE+">Pull from Github</div><br>";
					d += "</div>";
					
					d += "<span class='rowContent' id='row"+rowID+"Content'></span>";
					top.rowCount++;
					top.deletedCount++;
				}
			}
			
			c = "<b style='font-size: 18px'>CHANGED FILES:</b><br><br>"+c;
			n = "<b style='font-size: 18px'>NEW FILES:</b><br><br>"+n;
			d = "<b style='font-size: 18px'>DELETED FILES:</b><br><br>"+d
			
			get('compareList').innerHTML = c+"<br><br>"+n+"<br><br>"+d;
			updateInfo();
			get('blackMask','top').style.display='none';
			}
		)
	}
}