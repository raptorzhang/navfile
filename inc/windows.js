/*
#---------------------------
# PHP Navigator 4.0
# dated: 03-8-2006
# Coded by: Cyril Sebastian,
# web: navphp.sourceforge.net
#---------------------------
# PHP Navigator 4.12.12
# dated: 26-07-2007
# edited: 02-12-2008
# Modified by: Paul Wratt,
# Melbourne, Australia
# web: phpnav.isource.net.nz
#---------------------------*/

var i = -1;
var fname = '', ficon = null, oldficon = null, fo, tempY, tempX, timer;
var k = 0;

function setupContext(){
  if (!document.rightClickDisabled)
    document.rightClickDisabled = true;
  document.oncontextmenu = showcontext;
  document.onclick = hidecontext;
  document.onblur = hidecontext;
  document.onkeydown = shortcut;
}

function encode(str){
  if (!str || str=='')
    return '';
  str = encodeURIComponent(str);
  return str;
}

function xTime(){
  return (new Date().getTime());
}

function select(){
  if (location.search.indexOf('action=Search')!=-1) {
    folderinfo.innerHTML = 'Total files: ' + f.total.value + '<br>Matches: ' + f.perms.value;
    eval("folderthis.innerHTML = '<b>Search Results</b>';");
  }else
    folderinfo.innerHTML = 'Total files: ' + f.total.value + '<br>Permissions: ' + f.perms.value;
  setupContext();
}

function upload(){
  i = 0, flag = 0;
  while (f2.upfile[i]){
    if(f2.upfile[i].value!="") flag=1;
    i++;
  }
  if (!flag){
    alert("Select the file to upload");
    return false;
  }else
    return true;
}

function gotodir(){
  window.location.href = '?go=' + encode(f.go.value.replace('\\','/').replace('//','/'));
}

function updir(){
  window.location.href = '?action=Up&dir=' + encode(f.go.value);
}

function homedir(){
  window.location.href = 'windows.php';
}

function refreshdir(){
  window.location.href = '?dir=' + encode(f.dir.value);
}

function thumbnail(){
  if (oldficon.getAttribute('spec').indexOf('t')>0){
    filepath = encode(f.dir.value + '/' + fname);
    thumb.innerHTML = '<center><img src="thumb.php?img=' + filepath + '&size=150" width=150 height=150 alt=" Loading... "><br>' + fname;
  }
}

function arrange(arrang){
  document.cookie = 'navphp_arrange=' + arrang.value;
  gotodir(f);	// refresh
}

function showinfo(file){
  info.innerHTML = file.alt;
  file.style.background = 'Highlight';
}

function showdetails(file,details){
  info.innerHTML = details;
  file.style.background = 'Highlight';
}

function hideinfo(file){
  if (fname!=file.title)
    file.style.background = 'none';
}

function hide_info(){
  zipinfo.style.visibility = 'hidden';
}

function loadfile(ficon,fdetails){
  window.clearTimeout(timer);
  if (oldficon)
    oldficon.style.background = 'none'; // clear old icon
  fname = ficon.title;
  ficon.style.background = 'Highlight';
  oldficon = ficon;
  if (fdetails==''){
    showinfo(ficon);
    thestatus.innerHTML = "Double click to open: <b>'" + fname + "'</b>";
  }else{
    showdetails(ficon,fdetails);
    thestatus.innerHTML = "Double click to download: <b>'" + fname + "'</b>";
  }
  if (oldficon.getAttribute('spec').indexOf('z')>0)
    timer = window.setTimeout('getzipinfo()',100);
  if (oldficon.getAttribute('spec').indexOf('d')>0)
    timer = window.setTimeout('getfolderinfo()',100);	
  if (oldficon.getAttribute('spec').indexOf('t')>0)
    timer = window.setTimeout('getimageinfo()',100);	
}

function unload(){
  fname = '';
  if (oldficon)
    oldficon.style.background = 'none'; // clear old icon
}

function loadtd(fobj){
  fo = fobj;
}

function opendir(){
  if (!oldficon || oldficon==null)
    return;
  spec = oldficon.getAttribute('spec');
  if ((spec.indexOf('d')>0)||(spec.indexOf('e')>0))
    if (fname!='')
      window.location.href = '?action=Open&dir=' + encode(f.dir.value) + '&file=' + encode(fname); 
}

function openeditor(){
  if (fname!=''){
    browser=navigator.userAgent;
    if (browser.indexOf('pera')>0)
      alert('HTML Editor is not available in Opera (< v9)!');
    else if (oldficon.getAttribute('spec').indexOf('h')>0) 
      window.open('editor/editor.php?file=' + encode(fname) + '&dir=' + encode(f.dir.value), 'Editor' + xTime(), 'width=750, height=500, left=10, top=10, resizable=yes, scrollbars=no, location=no, toolbar=no,menubar=no');
  }
}

function opensource(){
  if (fname!='')
    window.open('code_editor/editor.php?file=' + encode(fname) + '&dir=' + encode(f.dir.value), 'SourceEditor' + xTime(), 'width=750, height=500, left=10, top=10, resizable=yes, scrollbars=no, location=no, toolbar=no,menubar=no');
}

function opendevedit(){
  if (fname!='')
    window.open('DevEdit/editor.php?file=' + encode(fname) + '&dir=' + encode(f.dir.value), 'DevEdit' + xTime(), 'width=750, height=500, left=10, top=10, resizable=yes, scrollbars=no, location=no, toolbar=no,menubar=no');
}

function download(filen){
  if (confirm("Do you want to download folder '" + filen + "' as zip?"))
    window.location.href = '?action=Download&file=' + encode(filen) + '&dir=' + encode(f.dir.value);
}

function downloadFile(filen){
  window.location.href = '?action=Download&file=' + encode(filen) + '&dir=' + encode(f.dir.value);
}

function doDownload(){
  if (fname!='')
    window.location.href = '?action=Download&dir=' + encode(f.dir.value) + '&file=' + encode(fname); 
}

function not_editable(){
  if (document.cookie.indexOf('navphp_editall=yes')!=-1){
    if (confirm("File '" + fname + "' does not seem to be an editable file type!\n Do you really want to open it?"))
      window.location.href = '?action=Open&dir=' + encode(f.dir.value) + '&file=' + encode(fname);
  }else
    newtooltip(info_string + " This file type is not editable!!<br>To download this click the filename below its icon.",8000);
}

function centerWinStr(xWidth,xHeight){
  return 'width=' + xWidth + ', height=' + xHeight + ', left=' + ((screen.width/2)-(xWidth/2)) + ', top=' + ((screen.height/2)-(xHeight/2));
}

function favourites(){
  w = 500; h = 300;
  window.open('favourites.php', 'Favourites', centerWinStr(w,h) + ', resizable=no, scrollbars=no, location=no, status=no, dirctories=no, toolbar=no, menubar=no, titlebar=no ');
}

function config(){
  w = 300; h = 240;
  window.open('settings.php', 'Settings', centerWinStr(w,h) + ', resizable=no, scrollbars=no, location=no, status=no, dirctories=no, toolbar=no, menubar=no, titlebar=no ');
}

function about(){
  w = 500; h = 240;
  window.open('about.html', 'About', centerWinStr(w,h) + ', resizable=no, scrollbars=no, location=no, status=no, dirctories=no, toolbar=no, menubar=no, titlebar=no ');
}

function help(){
  w = 500; h = 500;
  window.open('help.html', 'Help', centerWinStr(w,h) + ', resizable=no, scrollbars=no, location=no, status=no, dirctories=no, toolbar=no, menubar=no, titlebar=no ');
}

function searchfile(){
  w = 400; h = 145;
  window.open('search_form.php?action=Search&dir=' + encode(f.dir.value) + '&file=' + encode(fname), 'Search', centerWinStr(w,h) + ', resizable=no, scrollbars=no, location=no, status=no, dirctories=no, toolbar=no, menubar=no, titlebar=no ');
}

function oldsearchfile() { // should be a custom dialog
//window.open("search.php","Search","width=600, height=400,  resizable=yes, scrollbars=yes, location=no, status=no, toolbar=no, menubar=no, titlebar=no ");
  search4 = prompt('Search for:','');
  if (!search4 || search4=='') return;
  search4 = encode(search4);
  searchopt = '';
  if (confirm("Search through each folder as well as the current one?")) searchopt = '&subdir=yes';
  if (confirm("Search for anything with '"+search4+"' in their name or contents?")) searchopt = searchopt + '&search=' + search4;
  else{
    if (confirm("Search for files & folders with '"+search4+"' in their name?")) searchopt = searchopt + '&file=' + search4;
    if (searchopt.indexOf('&file=')!=-1) {
      if (confirm("Search files with '"+search4+"' in their name and their contents?")) searchopt = searchopt + '&content=' + search4;
    }else if(confirm("Search for files with '"+search4+"' in their contents?")) searchopt = searchopt + '&content=' + search4;
  }
  if (searchopt!='' && searchopt!='&subdir=yes')
    window.location.href = '?action=Search&dir=' + encode(f.dir.value) + searchopt; 
}

function shortcut(evt){
  var key;
  if (!evt)
    evt = window.event;
  if (!evt.keyCode)
    key = evt.charCode;
  else
    key = evt.keyCode;
  hidecontext();

  var relTarg = (window.event) ? evt.srcElement : evt.target;

  if(key==113) rename();
  if(key==13 && relTarg.id=='go'){
    evt.cancelBubble = true;
    gotodir(f);
    return(false);
  }
  if (key==13) opendir();
  if (evt.shiftKey && evt.ctrlKey){
    if (key==67) copy(f);
    if (key==70) newfile(f);
    if (key==72) openeditor();
    if (key==78) newfolder(f);
    if (key==82) rename();
    if (key==84) thumbnail();
    if (key==69) extract();
    if (key==88) delet();
  }
  if ((key>=37) && (key<=40) && !fo&&filestable.rows[0].cells[0].innerHTML)	
    fo = filestable.rows[0].cells[0];
  if (key==39){		// right arrow
    if (fo && fo.nextSibling){
      sibling = fo.nextSibling;
      if (sibling.nodeType!=1)			// a workaround for firefox
        sibling=sibling.nextSibling;
      if (!sibling) return 0;		//right end
      var atags = sibling.getElementsByTagName('img');
      loadtd(sibling);
      if(!sibling.innerHTML){			// Empty cell found!
        unload();
        return 0;
      }
      loadfile(atags[0]);
    }
  }
  if (key==37){		// left arrow
    if (fo && fo.previousSibling){
      sibling = fo.previousSibling;
      if (sibling.nodeType!=1)
        sibling = sibling.previousSibling;	// a workaround for firefox
      if (!sibling)				// left end
        return 0;
      var atags = sibling.getElementsByTagName('img');
      loadtd(sibling);
      if (!sibling.innerHTML){
        unload();
        return 0;
      }
      loadfile(atags[0]);
    }
  }
  if (key==38){		// up arrow
    if (fo && (fo.parentNode.rowIndex>0)){
      if (filestable.rows[fo.parentNode.rowIndex-1].cells[fo.cellIndex]){
        sibling = filestable.rows[fo.parentNode.rowIndex-1].cells[fo.cellIndex];
        var atags = sibling.getElementsByTagName('img');
        loadtd(sibling);
        if (!sibling.innerHTML){
          unload();
          return 0;
        }
        loadfile(atags[0]);
      }
    }
  }
  if (key==40){		// down arrow
    if (fo && (fo.parentNode.rowIndex<filestable.rows.length-1)){
      if (filestable.rows[fo.parentNode.rowIndex+1].cells[fo.cellIndex]){
        sibling = filestable.rows[fo.parentNode.rowIndex+1].cells[fo.cellIndex];
        var atags = sibling.getElementsByTagName('img');
        loadtd(sibling);
        if (!sibling.innerHTML){
          unload();
          return 0;
        }
        loadfile(atags[0]);
      }
    }
  }
}


function showcontext(evt){	// right click context menu
  if (!fname)
    return true;
  if (!evt)
    evt = window.event;
  if (document.cookie.indexOf('navphp_cont=no')!=-1)
    return true;
  cont = document.getElementById('context');
  getMouseXY(evt);

  span = document.body.clientHeight + document.body.scrollTop;
  if ((tempY+150)>span){	// ensure full y-visibilty
    span -= 162;
    cont.style.top = span + 'px';
  }else
    cont.style.top = tempY + 'px';
	
  span = document.body.clientWidth + document.body.scrollLeft;	
  if ((tempX+90)>span){		// ensure full x-visibilty
    tempX -= 90;
    cont.style.left = tempX + 'px';
  }else
    cont.style.left = tempX + 'px';
	
  cont.style.visibility = 'visible';

//  cont.scrollIntoView(false);

// remove customization
  for(i=1;i<cont.rows.length-8;i++)
    cont.deleteRow(i);

// customize context menu
  if (oldficon.getAttribute('spec').length>=2){
	cont.insertRow(1);
	cont.rows[1].insertCell(0);
	cont.rows[1].insertCell(1);
	cont.rows[1].cells[0].className = 'contbar';
	cont.rows[1].cells[1].className = 'contitem';
  }
  if (oldficon.getAttribute('spec').indexOf('z')>0){
	cont.rows[1].cells[0].innerHTML = '<img src=skins/extract.gif height=16 width=16 class=contbar>';
	cont.rows[1].cells[1].innerHTML = '<a href="javascript:extract()">Extract Here </a>';
	}
  else if (oldficon.getAttribute('spec').indexOf('t')>0){
	cont.rows[1].cells[0].innerHTML = '<img src=skins/view.gif height=16 width=16 class=contbar>';
	cont.rows[1].cells[1].innerHTML = '<a href="javascript:thumbnail()">Thumbnail</a>';
  }
  else if(oldficon.getAttribute('spec').indexOf('h')>0){
	cont.rows[1].cells[0].innerHTML = '<img src=skins/edithtml.gif height=16 width=16 class=contbar>';
	cont.rows[1].cells[1].innerHTML = '<a href="javascript:openeditor()">Edit HTML</a>';
  }

  return false;
}

function hidecontext(){
  cont = document.getElementById('context');
  cont.style.visibility = 'hidden';
//  zipinfo.style.visibility = 'hidden';
}

function getMouseXY(e){		// get mouse position
  if (!e)
    e = window.event;
  if (document.all){ 
    tempX = e.clientX + document.body.scrollLeft
    tempY = e.clientY + document.body.scrollTop
  }else{  
    tempX = e.pageX
    tempY = e.pageY
  }  
  if (tempX<0)
    tempX = 0;
  if (tempY<0)
    tempY = 0;  
  return true
}

