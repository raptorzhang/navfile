function atariIcon(ficon,ftype){
  window.clearTimeout(timer);
  if(ftype=='f') ficon = eval(ficon.getAttribute('iid'));

  fname = ficon.title;
  if(ficon.getAttribute('atariimg')==ficon.getAttribute('atarisel')) {
    ficon.style.background = 'black';
    if(ficon.getAttribute('spec').indexOf('t')>0) { ficon.className = 'ficonSel'; }
  }else{
    ficon.src = ficon.getAttribute('atarisel');
  }
  eval(ficon.getAttribute('aid')+".className = 'nameSel';");

  info.innerHTML = ficon.alt;
//  thestatus.innerHTML = "<center>Editing: <b>'"+fname+"'</b>";

  oldficon = ficon;

  if(ficon.getAttribute('spec').indexOf('z')>0) 
	timer=window.setTimeout("getzipinfo()",100);
  if(ficon.getAttribute('spec').indexOf('d')>0) 
	timer=window.setTimeout("getfolderinfo()",100);	
  if(ficon.getAttribute('spec').indexOf('t')>0)
	timer=window.setTimeout("getimageinfo()",100);
}

function atariClear(){
  if(ficon.getAttribute('atariimg')==ficon.getAttribute('atarisel')) {
    ficon.style.background = 'none';
    if(ficon.getAttribute('spec').indexOf('t')>0) { ficon.className = 'ficon'; }
  }else{
    ficon.src = ficon.getAttribute('atariimg');
  }
  eval(ficon.getAttribute('aid')+".className = 'name';");

//  thestatus.innerHTML = "<center>Double Click icon to reopen <b>'"+fname+"'</b>";

}

function fontlarge(xObj){
  xObj.style.fontFamily = "<?= $font_large ?>";
  xObj.style.fontSize = '<?= $font_size ?>';
}

function fontsmall(xObj){
  xObj.style.fontFamily = "<?= $font_small ?>";
  xObj.style.fontSize = '<?= $font_size_small ?>';
}

function fontdefault(xObj){
  xObj.style.fontFamily = '';
  xObj.style.fontSize = '';
}

function fontchange(xObj){

}

function reloadfile(){
  storeWindows();
  saveEditor();
  data.style.backgroundColor = 'lightgray';
  location.href="?action=Open&file=$file_e&dir=$dir_e";
}

function saveas(){
  xFile = prompt('Save As:','{$file}');
  if(!xFile || xFile=='' || xFile=='{$file}') return;
  f.file.value = xFile;
  save();
}

function save(){
  storeWindows();
  saveEditor();
  data.style.backgroundColor = 'lightgray';
  f.action.value = 'UpdateSave';
  f.submit();
}

function saveclose(){
  storeWindows();
  saveEditer()
  data.style.backgroundColor = 'lightgray';
  f.action.value = 'Save';
  f.submit();
}

function dcheck(eObj){
	if (!eObj) eObj = window.event;
	xObj=(eObj.target) ? eObj.target : eObj.srcElement;
	if (xObj.id=='DesktopBody' || xObj.tagName=='HTML'){
		clearMenus();
		atariClear();
	}
	return;
}

function saveEditer(){
  document.cookie = 'editorScroll=0x0x0x0;';
}

function saveEditor(xObj){
  document.cookie = 'editorScroll=' + xObj.scrollTop + 'x' + xObj.scrollLeft + ';';
}

function restoreEditor(xObj){
  xCookie = 'editorScroll=';
  xCst = document.cookie.indexOf(xCookie);
  if (xCst==-1) return;
  xCl = xCookie.length;
  xCend = document.cookie.indexOf(";",xCst);
  if (xCend==-1) xSettings = document.cookie.substring(xCst+xCl);
  else xSettings = document.cookie.substring(xCst+xCl,xCend);
  xWs = xSettings.split('x');
  document.cookie = 'editorScroll=; expires=Fri, 21 Dec 1976 04:31:24 GMT;';
  xObj.scrollTop = xWs[0];
  xObj.scrollLeft = xWs[1];
  xObj.focus();
}

function startUp(){

}

PaintWindow = null;
PaintWindows = new Array(false,null,null,null,null,null,null,null,null,null);
function newPaintWindow(){
  xObj = document.getElementById('insertWindowHere');
  sObj = document.getElementById('DevPaint0').style;
// actual windows
  xIid = 'DevPaint'+PaintWindow;
  xIomdown = 'currentImg='+xIid+';';
  xIoclick = 'if (DesktopDialog){return(false);} clearMenus(); toFront('+xIid+');';
  xIsbrs = 'both';
  xIzid = '3';
  xIhnd = '0';
  xIminw = '120';
  xIminh = '160';
  xIstyle = 'position:absolute; display:inline; top:'+(parseInt(sObj.top)+(PaintWindow*20))+'px; left:'+(parseInt(sObj.left)+(PaintWindow*20))+'px; width:'+(parseInt(sObj.width))+'px; height:'+(parseInt(sObj.height))+'px; z-index:101;';
  if (window.innerHeight) {
    xElement = document.createElement('div');
    xElement.setAttribute('id',xIid);
    xElement.setAttribute('name',xIid);
    xElement.setAttribute('onClick',xIoclick);
    xElement.setAttribute('onMouseDown',xIomdown);
    xElement.setAttribute('scrollBars',xIsbrs);
    xElement.setAttribute('zid',xIzid);
    xElement.setAttribute('xHnd',xIhnd);
    xElement.setAttribute('minW',xIminw);
    xElement.setAttribute('minH',xIminh);
    xElement.setAttribute('style',xIstyle);
  }else{
    xElement = document.createElement('<div id='+xIid+' name='+xIid+' onClick="'+xIoclick+'" onMouseDown="'+xIomdown+'" scrollBars='+xIsbrs+' xHnd='+xIhnd+' zid='+xIzid+' minW='+xIminw+' minh='+xIminh+' style="'+xIstyle+'" />');
  }
  xObj.parentNode.insertBefore(xElement,xObj);
// window parts
  xI = '';
  xI = xI + '<div class=win_part_close onClick="if (DesktopDialog){return(false);} $.style.display=\'none\'; clearMenus();"></div>\n';
  xI = xI + '<div class=win_part_title onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront($); dragWindow($,event);"><center><table class=big border=0 cellspacing=0 cellpadding=0><tr><td class=big align=right nowrap name=$T id=$T style="cursor:default;">new image</td></tr></table></center></div>\n';
  xI = xI + '<div class=win_part_max onClick="if (DesktopDialog){return(false);} toFront($); MaxMin($); event.cancelBubble=true;"></div>\n';
  xI = xI + '<div class=win_part_option name=$O id=$O style="cursor:default;"></div>\n';
  xI = xI + '<div class=win_part_body style="overflow:hidden;"><table border=0 cellspacing=0 cellpadding=0 width=100% height=100% align=center><tr><td width=100% height=100% align=center valign=middle><div name=$A id=$A style="overflow:hidden;cursor:crosshair;"></div></td></tr></table></div>\n';
  xI = xI + '<div class=win_part_scrollV><div class=win_part_scrollV_up></div><div class=win_part_scrollV_slide></div><div class=win_part_scrollV_down></div></div>\n';
  xI = xI + '<div class=win_part_scrollH><div class=win_part_scrollH_left></div><div class=win_part_scrollH_slide></div><div class=win_part_scrollH_right></div></div>\n';
  xI = xI + '<div class=win_part_resize onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront($); windowReSize($,event); event.cancelBubble=true;"></div>\n';
  xObj = document.getElementById(xIid);
  xObj.innerHTML = xI.replace(/\$/g,xIid);
  xI = '';
  regWindowObjects(xIid);
  regWindow(xIid);
  PaintWindows[PaintWindow] = false;
}

xObjs = new Array('','T','O','A');
for(j=1;j<10;j++){
  for(i=0;i<xObjs.length;i++){
    eval('DevPaint'+(j)+xObjs[i]+'=null;');
  }
}

function regWindowObjects(xName){
  if (window.innerHeight) {
    xObjs = new Array('','T','O','A');
    for(i=0;i<xObjs.length;i++){
      eval(xName+xObjs[i]+'=document.getElementById("'+xName+xObjs[i]+'");');
    }
    xObjs = null;
  }
}