Desktop = new Array(100);
DesktopNext = 0;
Windows = new Array(100);
WindowNext = 0;
windowLast = '';
DesktopDialog = false;
Keys = new Array(100);
KeysNext = 0;
// 									-= Desktop =-

// 									-= Window =-
function WindowObject(xObjName,xObj,xHnd){
	this.xHnd = xHnd;
	this.Name = xObjName;
	this.top = xObj.style.top;
	this.left = xObj.style.left; 
	this.width = xObj.style.width;
	this.height = xObj.style.height;
//	this.ScrollHeight = eval('' + xObjName + 'Scroll.style.height');
	this.zID = xObj.getAttribute('zid');
	this.ID = xObj.id;
	xObj.setAttribute('xHnd',WindowNext);
	return;
}
function regWindow(xObjName,xObj){
	if (xObjName=='' || xObj==null){return;}
	Windows[WindowNext] = new WindowObject(xObjName,xObj,WindowNext);
	WindowNext++;
	return (WindowNext-1);
}
function toFront(xObj){
	if (xObj.style.zIndex==100){return;}
	for (i=0; i<WindowNext; i++){
		eval('zObj = ' + Windows[i].ID);
		if (zObj.style.zIndex<=100 && zObj.style.zIndex!=2){
			--zObj.style.zIndex;
		}else{
			zObj.style.zIndex = zObj.getAttribute('zid');
		}
//		windowRedraw(Windows[i].xHnd);
	}
	xObj.style.zIndex=100;
	storeWindows();
	return;
}
function MaxMin(xHnd){
	xObj = eval('' + Windows[xHnd].ID);
	clearMenus();
	if (xObj.style.width=='100%'){
		Windows[xHnd].top = windowLast.substring(0,windowLast.indexOf(' '));
		Windows[xHnd].left = windowLast.substring(windowLast.indexOf(' ')+1,windowLast.length);
		xObj.style.top = Windows[xHnd].top;
		xObj.style.left = Windows[xHnd].left;
		xObj.style.width = Windows[xHnd].width;
		xObj.style.height = Windows[xHnd].height;
//		eval('' + Windows[xHnd].ID + 'Scroll.style.height = Windows[xHnd].ScrollHeight;');
//		eval('' + Windows[xHnd].ID + 'MM.src = \'skins/atari/images/MAX.GIF\';');
	}else{
		updateClientSizes();
		windowLast = Windows[xHnd].top + ' ' + Windows[xHnd].left;
		xObj.style.top = '20px';
		xObj.style.left = '0px';
		xObj.style.width = '100%';
		xObj.style.height = height-25 + 'px';
//		eval('' + Windows[xHnd].ID + 'Scroll.style.height = height-25-65-42;');
//		eval('' + Windows[xHnd].ID + 'MM.src = \'skins/atari/images/MIN.GIF\';');
	}
	return;
}
function windowRedraw(xHnd){
	xObj = eval('' + Windows[xHnd].ID);
	xObj.style.width = Windows[xHnd].width;
	xObj.style.height = Windows[xHnd].height;
//	eval('' + Windows[xHnd].ID + 'Scroll.style.height = Windows[xHnd].ScrollHeight;');
}

// 									-= movement =-
Drag = false;
xDragOffset = 0;
yDragOffset = 0;
DragObject = null;
DragWindow = -1;
DragScroll = -1;
xScrollOffset = 0;
yScrollOffset = 0;
ScrollBar = null;
ScrollArea = null;
ScrollPer = 0;
ScrollStart = 0;
ReSize = false;
wReSizeWidth = 0;
wReSizeHeight = 0;
ReSizeObject = null;
ReSizeWindow = -1;

function dragWindow(xObj,eObj){
	if (Drag){return(false);}
	if (!eObj) eObj = window.event;
	status = eObj.clientX + ' x ' + eObj.clientY;
	objX = pxStrip(xObj.style.left)-1;
	objY = pxStrip(xObj.style.top)-1;
	xDragOffset = eObj.clientX - objX;
	yDragOffset = eObj.clientY - objY;
	DragWindow = xObj.getAttribute('xHnd');
	DragObject = xObj;
	Drag = true;
}
function windowReSize(xObj,eObj){
	if (ReSize){return(false);}
	if (!eObj) eObj = window.event;
	status = eObj.clientX + ' x ' + eObj.clientY;
	xDragOffset = eObj.clientX;
	yDragOffset = eObj.clientY;
	ReSizeWindow = xObj.getAttribute('xHnd');
	ReSizeObject = xObj;
	wReSizeWidth = parseInt(pxStrip(Windows[ReSizeWindow].width));
	wReSizeHeight = parseInt(pxStrip(Windows[ReSizeWindow].height));
	ReSize = true;
}
function dragScroll(xObj,eObj,mObj){
	if (Drag){return(false);}
	if (!eObj) eObj = window.event;
	DragScroll = xObj.id.substring(xObj.id.length-1);
	ScrollBar = xObj.id.substring(0,xObj.id.indexOf('Scroll'));
	if (DragScroll=='V'){
		if (xObj.style.height=='100%') {Drag=false; DragScroll=-1; ScrollBar=null; return(false); }
		yScrollOffset = parseInt(pxStrip(xObj.style.top));
		yDragOffset = eObj.clientY;
		ScrollPer = eval('mObj.scrollHeight/parseInt('+ScrollBar+'Scroll.style.height)');
		ScrollStart = mObj.scrollTop;
	}else{
		if (xObj.style.width=='100%') { Drag=false; DragScroll=-1; ScrollBar=null; return(false); }
		xScrollOffset = parseInt(pxStrip(xObj.style.left));
		xDragOffset = eObj.clientX;
		ScrollPer = eval('mObj.scrollWidth/parseInt('+ScrollBar+'C.style.width)');
		ScrollStart = mObj.scrollLeft;
	}
//	status = eObj.clientX + ' x ' + eObj.clientY;
	ScrollArea = mObj;
	DragObject = xObj;
	Drag = true;
}
function doMover(eObj){
	if (!eObj) eObj = window.event;
	if (Drag && (DragScroll!=-1)){
		if (DragScroll=='V'){
			if (DragObject.style.height=='100%') { doEnd(eObj); return(false); }
			tmpY = yScrollOffset + (eObj.clientY - yDragOffset);
                        tmpS = ScrollPer * (eObj.clientY - yDragOffset);
			if (tmpY<0) tmpY=0;
                        tmpH = eval('parseInt('+ScrollBar+'Scroll.style.height)-parseInt(DragObject.style.height)-1');
			if (tmpY>tmpH) tmpY=tmpH;
			DragObject.style.top = tmpY + 'px';
			ScrollArea.scrollTop = ScrollStart + tmpS;
		}else{
			if (DragObject.style.width=='100%') { doEnd(eObj); return(false); }
			tmpX = xScrollOffset + (eObj.clientX - xDragOffset);
                        tmpS = ScrollPer * (eObj.clientX - xDragOffset);
			if (tmpX<0) tmpX=0;
                        tmpW = eval('parseInt('+ScrollBar+'C.style.width)-parseInt(DragObject.style.width)-5');
			if (tmpX>tmpW) tmpX=tmpW;
			DragObject.style.left = tmpX + 'px';
			ScrollArea.scrollLeft = ScrollStart + tmpS;
		}
	}
	if (Drag && (DragWindow!=-1)) {
		updateClientSizes();
		DragObject.style.cursor = 'default';
		status = eObj.clientX + ' x ' + eObj.clientY;
		tmpX = eObj.clientX - xDragOffset; if (tmpX<0) tmpX=0;
		if (tmpX>(width-74)) tmpX=width-74;
		tmpY = eObj.clientY - yDragOffset; if (tmpY<20) tmpY=20;
		if (tmpY>(height-45)) tmpY=height-45;
		DragObject.style.left = tmpX + 'px';
		DragObject.style.top = tmpY + 'px';
		Windows[DragWindow].top = DragObject.style.top;
		Windows[DragWindow].left = DragObject.style.left;
		return(false);
	}
	if (ReSize && (ReSizeWindow!=-1)) {
		status = eObj.clientX + ' x ' + eObj.clientY;
		objX = eObj.clientX;
		objY = eObj.clientY;
		minW = eval('parseInt(' + Windows[ReSizeWindow].ID + '.getAttribute("minW"))');
		minH = eval('parseInt(' + Windows[ReSizeWindow].ID + '.getAttribute("minH"))');
		tmpX = wReSizeWidth + (objX - xDragOffset); if (tmpX<minW) tmpX=minW;
		tmpY = wReSizeHeight + (objY - yDragOffset); if (tmpY<minH) tmpY=minH;
		ReSizeObject.style.width = tmpX + 'px';
		ReSizeObject.style.height = tmpY + 'px';
//		eval('' + Windows[ReSizeWindow].ID + 'Scroll.style.height = (tmpY-65-42) + "px";');
		return(false);
	}
	if (Drag && (DragWindow==-1 && DragScroll==-1)){
		iconMover(event);
		return(false);
	}
	return(true);
}
function doEnd(eObj){
	if (!eObj) eObj = window.event;
	if (Drag && (DragScroll!=-1)){
		Drag = false;
		DragScroll = -1;
		DragObject = null;
		xDragOffset = 0;
		yDragOffset = 0;
		ScrollBar = null;
		ScrollArea = null;
		ScrollPer = 0;
		return (false);
	}
	if (Drag && (DragWindow!=-1)){
		Drag = false;
		Windows[DragWindow].top = DragObject.style.top;
		Windows[DragWindow].left = DragObject.style.left;
		DragWindow = -1;
		DragObject = null;
		xDragOffset = 0;
		yDragOffset = 0;
		storeWindows();
		return (false);
	}
	if (ReSize && (ReSizeWindow!=-1)){
		ReSize = false;
		Windows[ReSizeWindow].width = ReSizeObject.style.width;
		Windows[ReSizeWindow].height = ReSizeObject.style.height;
//		Windows[ReSizeWindow].ScrollHeight = eval('' + Windows[ReSizeWindow].ID + 'Scroll.style.height;');
		ReSizeWindow = -1;
		ReSizeObject = null;
		xDragOffset = 0;
		yDragOffset = 0;
		storeWindows();
		return(false);
	}
	if (Drag && (DragWindow==-1)){
		dragIconEnd(eObj);
		return(false);
	}
	return(true);
}

// 									-= ScrollBars =-
function scrollSetBoth(xName,mObj){
	eval('scrollSet('+xName+'ScrollV,'+mObj.id+')');
	eval('scrollSet('+xName+'ScrollH,'+mObj.id+')');
}
function scrollSet(xObj,mObj){
	xWin = xObj.id.substring(0,xObj.id.indexOf('Scroll'));
	if(xObj.id.substring(xObj.id.length-1)=='V'){
		sTmp = eval('parseInt('+xWin+'Scroll.style.height)');
		if(mObj.scrollHeight>(eval('parseInt('+xWin+'Body.style.height)'))){
			xTmp = sTmp/(mObj.scrollHeight/eval('parseInt('+xWin+'Body.style.height)'));
			vScrollH = (xTmp>10) ? (xTmp-1) + 'px' : '10px';
			if(mObj.scrollTop>=(mObj.scrollHeight-sTmp)){
				vScrollY = (sTmp-parseInt(vScrollH)) + 'px';
			}else{
				vScrollY = (mObj.scrollTop>0) ? (sTmp/(mObj.scrollHeight/mObj.scrollTop)) + 'px': '0px';
			}
		}else{ 
			vScrollH = '100%';
			vScrollY = '0px';
		}
		xObj.style.height = vScrollH;
		xObj.style.top = vScrollY;
		
	}else{
		sTmp = eval('parseInt('+xWin+'C.style.width)');
		if(mObj.scrollWidth>(sTmp+40)){
			xTmp = sTmp/(mObj.scrollWidth/(sTmp+40));
			hScrollW = (xTmp>10) ? (xTmp-5) + 'px' : '10px';
			if(mObj.scrollLeft>=(mObj.scrollWidth-sTmp)){
				hScrollX = (sTmp-parseInt(hScrollW)) + 'px';
			}else{
				hScrollX = (mObj.scrollLeft>0) ? (sTmp/(mObj.scrollWidth/mObj.scrollLeft)) + 'px': '0px';
			}
		}else{
			hScrollW = '100%';
			hScrollX = '0px';
		}
		xObj.style.width = hScrollW;
		xObj.style.left = hScrollX;
	}
}
function scrollPage(xObj,eObj,mObj){
	if (!eObj) eObj = window.event;
	xWin = xObj.id.substring(0,xObj.id.indexOf('Scroll'));
	if(xObj.id.substring(xObj.id.length-1)=='V'){
		tmpY = (typeof eObj.layerY!='undefined') ? eObj.layerY : eObj.offsetY;
		xTmp = eval('parseInt('+xWin+'Body.style.height)');
		if (tmpY<(parseInt(xObj.style.top))) {
			mObj.scrollTop -= xTmp;
		}else if (tmpY>(parseInt(xObj.style.top)+parseInt(xObj.style.height))) {
			mObj.scrollTop += xTmp;
		}
	}else{
		tmpX = (typeof eObj.layerX!='undefined') ? eObj.layerX : eObj.offsetX;
		xTmp = eval('parseInt('+xWin+'C.style.width)+40');
		if (tmpX<(parseInt(xObj.style.left))) {
			mObj.scrollLeft -= xTmp;
		}else if (tmpX>(parseInt(xObj.style.left)+parseInt(xObj.style.width))) {
			mObj.scrollLeft += xTmp;
		}
	}
	scrollMove(xObj,mObj);
}
function scrollMove(xObj,mObj){
	xWin = xObj.id.substring(0,xObj.id.indexOf('Scroll'));
	if(xObj.id.substring(xObj.id.length-1)=='V'){
		sTmp = eval('parseInt('+xWin+'Scroll.style.height)');
		if(mObj.scrollHeight>(sTmp+40)){
			xTmp = sTmp/(mObj.scrollHeight/eval('parseInt('+xWin+'Body.style.height)'));
			vScrollH = (xTmp>10) ? (xTmp-1) + 'px' : '10px';
			if(mObj.scrollTop>=(mObj.scrollHeight-sTmp)){
				vScrollY = (sTmp-parseInt(vScrollH)) + 'px';
			}else{
				vScrollY = (mObj.scrollTop>0) ? (sTmp/(mObj.scrollHeight/mObj.scrollTop)) + 'px': '0px';
			}
		}else{ 
			vScrollY = '0px';
		}
		xObj.style.top = vScrollY;
		
	}else{
		sTmp = eval('parseInt('+xWin+'C.style.width)');
		if(mObj.scrollWidth>(sTmp+40)){
			xTmp = sTmp/(mObj.scrollWidth/(sTmp-40));
			hScrollW = (xTmp>10) ? (xTmp-5) + 'px' : '10px';
			if(mObj.scrollLeft>=(mObj.scrollWidth-sTmp)){
				hScrollX = (sTmp-parseInt(hScrollW)) + 'px';
			}else{
				hScrollX = (mObj.scrollLeft>0) ? (sTmp/(mObj.scrollWidth/mObj.scrollLeft)) + 'px': '0px';
			}
		}else{
			hScrollX = '0px';
		}
		xObj.style.left = hScrollX;
	}
}
scrollIt = null;
function scrollClick(xHowMuch,xObj,mObj){
	clearTimeout(scrollIt);
	xHM = parseFloat(xHowMuch)
	if(xObj.id.substring(xObj.id.length-1)=='V'){
		mObj.scrollTop += xHM;
	}else{
		mObj.scrollLeft += xHM;
	}
	scrollMove(xObj,mObj);
	if(!ScrollBar) return;
	scrollIt = setTimeout('scrollClick("'+xHowMuch+'",'+xObj.id+','+mObj.id+')',125);
}

// 									-= utils =-
function pxStrip(xStr){
	n = xStr;
	n = xStr.substring(0,xStr.indexOf('px'));
	return n;
}
function oCenter(xObj){
	updateClientSizes();
	xObj.style.top = (height/2)-(pxStrip(xObj.style.height)/2)+'px';
	xObj.style.left = (width/2)-(pxStrip(xObj.style.width)/2)+'px';
	return;
}
function textReset(xObj){
	if (xObj.value!=xObj.defaultValue){xObj.value=xObj.defaultValue;}
	return;
}
function clearDesktopSelected(){
	if (DesktopNext==0) {return;}
	for (i=0; i<DesktopNext; i++) {
		if (Desktop[i].Selected) {eval(Desktop[i].deSelect);}
	}
	return;
}
function check(eObj){
	if (!eObj) eObj = window.event;
	xObj = (eObj.target) ? eObj.target : eObj.srcElement;
	if (xObj.id=='DesktopBody'){
		clearMenus();
//		clearDesktopSelected();
	}
	return;
}
function updateClientSizes(){
  if (window.innerWidth){
    width = window.innerWidth;
    height = window.innerHeight;
  }else{
    width = document.body.clientWidth;
    height = document.body.clientHeight;
  }
}
function extWindow(xUrl){
  window.open(xUrl,"","");
}

// 									-= dynamic settings =-
DesktopName = '';

function clearCookie(){
  document.cookie = 'windows' + DesktopName + '=; expires=Fri, 21 Dec 1976 04:31:24 GMT;';
  document.cookie = 'reset' + DesktopName + '=; expires=Fri, 21 Dec 1976 04:31:24 GMT;';
}
function storeWindows(){
  xWindows = '';
  for (i=0; i<WindowNext; i++){
        xW = Windows[i];
	xWindows = xWindows + '|' + xW.xHnd + ',';
	xWindows = xWindows + xW.top + ',';
	xWindows = xWindows + xW.left + ','; 
	xWindows = xWindows + xW.width + ',';
	xWindows = xWindows + xW.height + ',';
//	xWindows = xWindows + xW.ScrollHeight + ',';
	xWindows = xWindows + (eval('' + xW.ID + '.style.display')) + ',';
	xWindows = xWindows + xW.ID + ',';
	xWindows = xWindows + (eval('' + xW.ID + '.style.zIndex'));
  }
  document.cookie = 'windows' + DesktopName + '=' + xWindows + ';';
//  eval('document.forms[0].windows' + DesktopName + '.value = xWindows');
}
function restoreWindows(){
  xCookie = 'reset' + DesktopName + '=';
  xCst = document.cookie.indexOf(xCookie);
  if (xCst!=-1) {
    clearCookie();
    xCookie = 'defaults' + DesktopName + '=';
  }else{
    xCookie = 'windows' + DesktopName + '=';
  }
  xCst = document.cookie.indexOf(xCookie);
  if (xCst==-1) return;
  xCl = xCookie.length;
  xCend = document.cookie.indexOf(";",xCst);
  if (xCend==-1) xSettings = document.cookie.substring(xCst+xCl);
  else xSettings = document.cookie.substring(xCst+xCl,xCend);
  xWs = xSettings.split('|');
  if (xWs.length<=1) return;
  for(i=1;i<xWs.length;i++){
    xS = xWs[i].split(',');
    xW = Windows[xS[0]];
    xW.top = xS[1];
    xW.left = xS[2]; 
    xW.width = xS[3];
    xW.height = xS[4];
//    xW.ScrollHeight = xS[7];
    eval(''+xW.ID+'.style.top="'+xS[1]+'";');
    eval(''+xW.ID+'.style.left="'+xS[2]+'";');
    windowRedraw(xS[0]);
    eval(''+xW.ID+'.style.zIndex="'+xS[10]+'";');
    eval(''+xW.ID+'.style.display="'+xS[8]+'";');
  }
}
function resetWindows(){
  document.cookie = 'reset' + DesktopName + '=now;';
  restoreWindows();
}

// 									-= old stuff =-
function clearMenu(){
	Desk.style.visibility = 'hidden';
	MenuDesk.style.color = 'black';
	MenuDesk.style.background = 'white';
	File.style.visibility = 'hidden';
	MenuFile.style.color = 'black';
	MenuFile.style.background = 'white';
	View.style.visibility = 'hidden';
	MenuView.style.color = 'black';
	MenuView.style.background = 'white';
	Options.style.visibility = 'hidden';
	MenuOptions.style.color = 'black';
	MenuOptions.style.background = 'white';
	Help.style.visibility = 'hidden';
	MenuHelp.style.color = 'black';
	MenuHelp.style.background = 'white';
	Edit.style.visibility = 'hidden';
	MenuEdit.style.color = 'black';
	MenuEdit.style.background = 'white';
	return;
}
function doDesktop(){
	TrashCan.style.top = document.body.clientHeight-pxStrip(TrashCan.style.height)-2;
	TrashCan.style.left = 2;
	TrashCan.style.display = 'inline';
	iFile.style.top = 20;
	iFile.style.left = document.body.clientWidth-pxStrip(TrashCan.style.width)-2;
	iFile.style.display = 'inline';
	iFolder.style.top = 20;
	iFolder.style.left = document.body.clientWidth-pxStrip(TrashCan.style.width)-2-74;
	iFolder.style.display = 'inline';
	iProgram.style.top = document.body.clientHeight-pxStrip(TrashCan.style.height)-2;
	iProgram.style.left = document.body.clientWidth-pxStrip(TrashCan.style.width)-2;
	iProgram.style.display = 'inline';
	iFloppy.style.top = 20;
	iFloppy.style.left = 2;
	iFloppy.style.display = 'inline';
	iDrive.style.top = 20;
	iDrive.style.left = 2 + 74;
	iDrive.style.display = 'inline';
	return;
}
