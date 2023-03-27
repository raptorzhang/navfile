/*
#-----------------------------
# PHP Navigator 4.0
# dated: 03-8-2006
# Coded by: Cyril Sebastian
# web: navphp.sourceforge.net
#-----------------------------
# PHP Navigator 4.12.20
# dated: 25-07-2007
# edited: 06-06-2011
# Modified by: Paul Wratt
# Melbourne, Australia
# Auckland, New Zealand
# web: phpnav.isource.net.nz
#-----------------------------*/

(window.XMLHttpRequest) ? (true) : (XMLHttpRequest=false); // fix for IE/Opera test
function newobj() {
  var ro;
  if(XMLHttpRequest){			// Non-IE browsers
    ro = new XMLHttpRequest();
  }else if (window.ActiveXObject){	// IE
    ro = new ActiveXObject('Msxml2.XMLHTTP');
    if (!ro)
      ro = new ActiveXObject('Microsoft.XMLHTTP');
  }
  return ro;
}

skinpath = '';
function skinPath(){
  sk = 'navphp_skin=';
  skl = sk.length;
  skin = '';
  skst = document.cookie.indexOf(sk);
  skend = document.cookie.indexOf(';',skst);
  if(skst!=-1)
    skin = document.cookie.substring(skst+skl,skend);
  else
    if(skinpath!='') skin = skinpath;
  return skin+'/';
}

skintype = '';
function skinType(){
  sk = 'navphp_skintype=';
  skl = sk.length;
  skin = '';
  skst = document.cookie.indexOf(sk);
  skend = document.cookie.indexOf(';',skst);
  if(skst!=-1)
   skin = document.cookie.substring(skst+skl,skend);
  else
    if(skintype!='') skin = skintype;
    else skin = '.gif';
  return skin;
}

var http = newobj();
var fo_new
var error_string = '<img src="skins/' + skinPath() + 'error' + skinType() + '" width=24 height=24 onError="this.src=\'skins/error.gif\';">';
var info_string = '<img src="skins/' + skinPath() + 'info' + skinType() + '" width=24 height=24 onError="this.src=\'skins/info.gif\';">';

function delet(){
  if (fname=='')
    alert('First select a file by clicking on it');
  else{
    msg = '';
    if (oldficon.getAttribute('spec').indexOf('d')>0)
      msg = 'All files/folders in this folder will be deleted!';
    if (confirm("Delete '"+ fname+"' ?\n" + msg)){
      oldname = encode(fname);
      dir = encode(f.dir.value);
      http.open('get', 'delete.php?file=' + oldname + '&dir=' + dir + '&ajax=1&rand=' + xTime());
      http.onreadystatechange = showresult;
      http.send('');
      thestatus.innerHTML = 'Please wait...';
      fo_new = fo;
    }
  }
}

function rename(){
  if (fname=='')
    alert('First select a file by clicking on it');
  else{
    oldname = fname;
    newname = window.prompt('Rename - Enter the new file name:', oldname);
    if (newname && (newname!=oldname) && (newname.indexOf(' ')!=0)){
      oldname = encode(oldname);
      newname = encode(newname);
      dir = encode(f.dir.value);
      details = '';
      if(location.href.indexOf('details.php')!=-1)
        details = '&details=1';
      http.open('get', 'rename.php?file=' + oldname + '&change=' + newname + '&dir=' + dir + details + '&ajax=1&rand=' + xTime());
      http.onreadystatechange = showresult;
      http.send('');
      fo_new = fo;
    }
  }
}

function chmode(f,i){
  change = eval('f.mode' + i + '[f.mode' + i + '.selectedIndex].value');
  if (fname=='')
    alert('First select a file by clicking on it');
  else{
    oldname = encode(fname);
    dir = encode(f.dir.value);
    details = '';
    if (location.href.indexOf('details.php')!=-1)
      details = '&details=1';
    http.open('get', 'chmod.php?file=' + oldname + '&change=' + change + '&dir=' + dir + details + '&ajax=1&rand=' + xTime());
    http.onreadystatechange = showresult;
    http.send('');
    fo_new = fo;
  }
}

function copy(){ // no rename yet
  if (fname=='')
    alert('First select a file by clicking on it');
  else{
    sourcedir = f.dir.value;
    destdir = window.prompt("Copy '" + fname + "' to folder:", sourcedir);
    if (destdir && (destdir!=sourcedir) && (destdir!='./') && (destdir!='.\\')){
      oldname = encode(fname);
      sourcedir = encode(sourcedir);
      destdir = destdir.replace('\\','/').replace('//','/');
      destdir = encode(destdir);
      dir = encode(f.dir.value);
      http.open('get', 'copy.php?file=' + oldname + '&change=' + destdir + '&dir=' + dir + '&ajax=1&rand=' + xTime());
      http.onreadystatechange = showresult;
      http.send('');
      fo_new = fo;
    }
  }	
}

function move(){
  if (fname=='')
    alert('First select a file by clicking on it');
  else{
    sourcedir = f.dir.value;
    destdir = window.prompt("Move '" + fname + "' to folder:", sourcedir);
    if (destdir && (destdir!=sourcedir) && (destdir!='./') && (destdir!='.\\')){
      oldname = encode(fname);
      sourcedir = encode(sourcedir);
      destdir = destdir.replace('\\','/').replace('//','/');
      destdir = encode(destdir);
      dir = encode(f.dir.value);
      http.open('get', 'move.php?file=' + oldname + '&change=' + destdir + '&dir=' + dir + '&ajax=1&rand=' + xTime());
      http.onreadystatechange = showresult;
      http.send('');
      fo_new = fo;
    }
  }	
}

function extract(){
  if (fname=='')
    alert('First select a file by clicking on it');
  else if (oldficon.getAttribute('spec').indexOf('z')>0){
    if (confirm("Extract all files from '"+fname+"' to the current folder?")){
      oldname = encode(fname);
      dir = encode(f.dir.value);
      details = '';
      if(location.href.indexOf('details.php')!=-1)
        details = '&details=1';
      if (window.ActiveXObject)
        location.href = '?action=wget&file=' + newname + '&dir=' + dir;
      else{
        http.open('get', 'extract.php?file=' + oldname + '&dir=' + dir + details + '&ajax=1&rand=' + xTime());
        http.onreadystatechange = shownewitem;
        http.send('');
        fo_new = fo;
      }
    }
  }
}

function wget(){  // get remote file (no rename yet)
  oldname = 'http://';
  newname = window.prompt('wget - Enter url:', oldname);
  if (newname && (newname!=oldname) && (newname.indexOf(' ')!=0)){
    oldname = encode(oldname);
    newname = encode(newname);
    dir = encode(f.dir.value);
    details = '';
    if(location.href.indexOf('details.php')!=-1)
      details = '&details=1';
    if (window.ActiveXObject)
      location.href = '?action=wget&file=' + newname + '&dir=' + dir;
    else{
//      http.open('get', 'rename.php?file=' + oldname + '&change=' + newname + '&dir=' + dir + details + '&ajax=1&rand=' + xTime());
      http.open('get', 'wget.php?file=' + newname + '&dir=' + dir + details + '&ajax=1&rand=' + xTime());
      http.onreadystatechange = shownewitem;
      http.send('');
      fo_new = fo;
    }
  }
}

function getzipinfo(){
  oldname = encode(fname);
  dir = encode(f.dir.value);
  http.open('get', 'tooltip.php?file=' + oldname + '&dir=' + dir + '&ajax=1&action=zipinfo&rand=' + xTime());
  http.onreadystatechange = showtooltip;
  http.send('');
}

function getfolderinfo(){
  oldname = encode(fname);
  dir = encode(f.dir.value);
  http.open('get', 'tooltip.php?file=' + oldname + '&dir=' + dir + '&ajax=1&action=dirinfo&rand=' + xTime());
  http.onreadystatechange = showtooltip;
  http.send('');
}

function getimageinfo(){
  oldname = encode(fname);
  dir = encode(f.dir.value);
  http.open('get', 'tooltip.php?file=' + oldname + '&dir=' + dir + '&ajax=1&action=imginfo&rand=' + xTime());
  http.onreadystatechange = showtooltip;
  http.send('');
}

function newfolder(){
  newname=prompt('Enter the new folder name:', 'new_folder');
  if(newname && newname.indexOf(' ')!=0){
    newname = encode(newname);
    dir = encode(f.dir.value);
    details = '';
    if (location.href.indexOf('details.php')!=-1)
      details = '&details=1';
    if (window.ActiveXObject)
      location.href = '?action=New folder&change=' + newname + '&dir=' + f.dir.value;
    else{
      http.open('get', 'newfolder.php?change=' + newname + '&dir=' + dir + details + '&ajax=1&rand=' + xTime());
      http.onreadystatechange = shownewitem;
      http.send('');
      fo_new = fo;
//prompt("Debug","newfolder.php?change="+newname+"&dir="+dir+details+"&ajax=1&rand="+Math.floor(Math.random()*100));
    }
  }
}

function newfile(){
  newname=prompt('Enter the new file name:', 'new_file');
  if (newname && newname.indexOf(' ')!=0){
    newname = encode(newname);
    dir = encode(f.dir.value);
    details = '';
    if (location.href.indexOf('details.php')!=-1)
      details = '&details=1';
    if (window.ActiveXObject)
      location.href = '?action=New file&change=' + newname + '&dir=' + dir;
    else{
      http.open('get', 'newfile.php?change=' + newname + '&dir=' + dir + details + '&ajax=1&rand=' + xTime());
      http.onreadystatechange = shownewitem;
      http.send('');
      fo_new=fo;
    }
  }
}

function dom_newfolder(file_status){			// fn for new <td> obj & display file_status
  var tab = document.getElementById('filestable');
  var i;
  var cells;
  firstrow= tab.rows[0];
  if (firstrow.cells.length>4){
    tab.insertRow(0);
    tab.rows[0].insertCell(0);
  }else
    firstrow.insertCell(0);
  firstrow= tab.rows[0];
  firstrow.cells[0].setAttribute('onmousedown','loadtd(this)');
  firstrow.cells[0].innerHTML = file_status;
}

function shownewitem(){				// callback fn for new items (folder,file)
  if (http.readyState==4){
    if(http.status!=200){
      thestatus.innerHTML = error_string + '<font color=red><b>Error!:</b> ' + http.status + ' ' + http.statusText + '. Please retry.</font>';
      newtooltip(thestatus.innerHTML,10000);
      return 0;
    }
    var reply = http.responseText;
    var update = new Array();
    update = reply.split('|');
    thestatus.innerHTML = update[2];
    if(update[1]==1) {
      dom_newfolder(update[3]);
      info.innerHTML = '';
      unload();
      newtooltip(info_string + update[2],5000);		// show tooltip
    }else{
      newtooltip(error_string + update[2],5000);	// show tooltip
      alert(update[2]);
    }
  }else
    thestatus.innerHTML = 'Processing...';
}

function showresult(){					// callback fn for all other fn's
  if (http.readyState == 4){
    if (http.status!=200){
      thestatus.innerHTML = error_string + '<font color=red><b>HTTP Error!:</b> ' + http.status + ' ' + http.statusText + '. Please retry.</font>'; 
      newtooltip(thestatus.innerHTML,10000);
      return 0;
    }
    var reply = http.responseText;
    var update = new Array();
    update = reply.split('|');
    thestatus.innerHTML = update[2];
    if (update[1]==1){
      if (fo_new)
        fo_new.innerHTML = update[3];
      else 
        alert('Your browser does not provide full DOM support to display the results');
      info.innerHTML = '';
      unload();
      newtooltip(info_string + update[2],10000);	// show tooltip
    }else if (update[1]==3){				// for copy command (and failed move)
      info.innerHTML = '';
      newtooltip(info_string + update[2],10000);	// show tooltip
    }else{
      newtooltip(error_string + update[2],10000);	// show error
      alert(update[2]);
    }
  }else
    thestatus.innerHTML = 'Processing...';
}

function showtooltip(){	// callback fn for getzipinfo(), getimageinfo() and getfolderinfo()
  if (http.readyState == 4){
    if (http.status==200){
      var reply = http.responseText;
      var update = new Array();
      update = reply.split('|');
      if (update[1]==1)
        newtooltip(update[2],20000);
    }
  }
}       

function newtooltip(tip,timer){
  zipinfo.innerHTML = tip;
  if (window.innerHeight) {
    zipinfo.style.bottom = 10-(getWindowScrollTop()) + 'px';
//    zipinfo.style.right = 10-(getWindowScrollLeft()) + 'px';
  }else{
    zipinfo.style.bottom = '10px';
//    zipinfo.style.right = '10px';
  }
  zipinfo.style.visibility = 'visible';
  if(typeof ModalPhileProp!=undefined && typeof ModalPhileProp!='undefined') scrollSet(ModalPhilePropScrollV,PhilePropList);
  window.setTimeout('hidecontext()',timer);
}

function movetooltip(){
  if (zipinfo.style.visibility=='visible'){
    if (window.innerHeight){
      zipinfo.style.bottom = 10-(getWindowScrollTop()) + 'px';
//      zipinfo.style.right = 10-(getWindowScrollLeft()) + 'px';
    }else{
      zipinfo.style.visibility = 'hidden';
      zipinfo.style.visibility = 'visible';
    }
  }
}

if (document.documentElement){
  window.readScroll = document.documentElement;
}else if (document.body) {
  window.readScroll = document.body;
}

function getWindowScrollTop() {
  if (window.pageYOffset > 0 ) {
    return window.pageYOffset;
  }else{
    return window.readScroll.scrollTop || 0;
  }
}

function getWindowScrollLeft() {
  if(window.pageXOffset > 0 ) {
    return window.pageXOffset;
  }else{
    return window.readScroll.scrollLeft || 0;
  }
}
