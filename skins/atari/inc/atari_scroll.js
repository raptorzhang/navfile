/*************************************************************************
  based on scrollObject code by Sharon Paine at http://www.dyn-web.com/
*************************************************************************/

var desktop_event={
  add: function(obj,etype,fp,cap) {
    cap = cap || false;
    if(obj.addEventListener) obj.addEventListener(etype,fp,cap);
    else if(obj.attachEvent) obj.attachEvent('on' + etype,fp);
  }, 

  remove: function(obj,etype,fp,cap) {
    cap = cap || false;
    if(obj.removeEventListener) obj.removeEventListener(etype,fp,cap);
    else if(obj.detachEvent) obj.detachEvent('on' + etype,fp);
  }, 

  DOMit: function(e) { 
    e = e? e: window.event;
    e.tgt = e.srcElement? e.srcElement: e.target;
    if(!e.preventDefault) e.preventDefault = function () { return false; }
    if(!e.stopPropagation) e.stopPropagation = function () { if(window.event) window.event.cancelBubble = true; }
    return e;
  }
  
}

win_scrollObjs = {};
win_scrollObj.speed = 100;

function win_scrollObj(wnId,lyrId,cntId){
  this.id = wnId;
  win_scrollObjs[this.id] = this;
  this.animString = 'win_scrollObjs.' + this.id;
  this.load(lyrId,cntId);
}

win_scrollObj.loadLayer=function(wnId,id,cntId){
  if(win_scrollObjs[wnId]) win_scrollObjs[wnId].load(id,cntId);
}

win_scrollObj.prototype.load=function(lyrId,cntId){
  if(!document.getElementById) return;
  var wndo,lyr;
  if(this.lyrId){
    lyr = document.getElementById(this.lyrId);
    lyr.style.visibility = 'hidden';
  }
  lyr = document.getElementById(lyrId);
  wndo = document.getElementById(this.id);
  lyr.style.top = this.y = 0;
  lyr.style.left = this.x = 0;
  this.maxY = (lyr.offsetHeight-wndo.offsetHeight>0) ? lyr.offsetHeight-wndo.offsetHeight : 0;
  this.wd = cntId ? document.getElementById(cntId).offsetWidth : lyr.offsetWidth;
  this.maxX = (this.wd-wndo.offsetWidth>0) ? this.wd-wndo.offsetWidth : 0;
  this.lyrId = lyrId;
  lyr.style.visibility = 'visible';
  this.on_load();
  this.ready = true;
}

win_scrollObj.prototype.on_load=function(){};

win_scrollObj.prototype.shiftTo=function(lyr,x,y){
  if(!lyr.style) return;
  lyr.style.left = (this.x=x) + 'px';
  lyr.style.top = (this.y=y) + 'px';
}

win_scrollObj.GeckoTableBugFix=function(){
  var ua=navigator.userAgent;
  if(ua.indexOf('Gecko')>-1 && ua.indexOf('Firefox')==-1 && ua.indexOf('Safari')==-1 && ua.indexOf('Konqueror')==-1){
    win_scrollObj.hold = [];
    for(var i=0;arguments[i];i++){
      if(win_scrollObjs[arguments[i]]){
        var wndo = document.getElementById(arguments[i]);
        var holderId = wndo.parentNode.id;
        var holder = document.getElementById(holderId);
        document.body.appendChild(holder.removeChild(wndo));
        wndo.style.zIndex = 1000;
        var pos = getPageOffsets(holder);
        wndo.style.left = pos.x + 'px';
        wndo.style.top = pos.y + 'px';
        win_scrollObj.hold[i] = [arguments[i],holderId];
      }
    }
    window.addEventListener('resize',win_scrollObj.rePositionGecko,true);
  }
}

win_scrollObj.rePositionGecko=function(){
  if(win_scrollObj.hold){
    for(var i=0;win_scrollObj.hold[i];i++){
      var wndo=document.getElementById(win_scrollObj.hold[i][0]);
      var holder=document.getElementById(win_scrollObj.hold[i][1]);
      var pos=getPageOffsets(holder);
      wndo.style.left = pos.x + 'px';
      wndo.style.top = pos.y + 'px';
    }
  }
}

win_scrollObj.prototype.bSizeDragBar = true;

win_scrollObj.prototype.setUpScrollbar=function(id, trkId, axis, offx, offy){
  if(!document.getElementById) return;
  var bar = document.getElementById(id);
  var trk = document.getElementById(trkId);
  win_slidebar.init(bar,trk,axis,offx,offy);
  bar.wn = win_scrollObjs[this.id];
  if(axis=='v') this.vBarId = id;
  else this.hBarId = id;
  if(this.bSizeDragBar) this.setBarSize();
  bar.on_drag_start = bar.on_slide_start = win_scrollObj.getWndoLyrRef;
  bar.on_drag_end =   bar.on_slide_end =   win_scrollObj.tossWndoLyrRef;
  bar.on_drag =       bar.on_slide =       win_scrollObj.UpdateWndoLyrPos;
}

win_scrollObj.getWndoLyrRef=function(){
  this.wnLyr = document.getElementById(this.wn.lyrId);
}

win_scrollObj.tossWndoLyrRef=function(){
  this.wnLyr = null;
}

win_scrollObj.UpdateWndoLyrPos=function(x,y){
  var nx, ny;
  if(this.axis=='v'){
    nx = this.wn.x;
    ny = -(y-this.minY)*(this.wn.maxY/(this.maxY-this.minY)) || 0;
  }else{
    ny = this.wn.y;
    nx = -(x-this.minX)*(this.wn.maxX/(this.maxX-this.minX)) || 0;
  }
  this.wn.shiftTo(this.wnLyr,nx,ny);
}

win_scrollObj.prototype.updateScrollbar=function(x,y){
  var nx, ny;
  if(this.vBarId){
    if(!this.maxY) return;
    ny = -( y * ( (this.vbar.maxY - this.vbar.minY) / this.maxY ) - this.vbar.minY );
    ny = Math.min( Math.max(ny, this.vbar.minY), this.vbar.maxY);  
    nx = parseInt(this.vbar.style.left);
    this.vbar.style.left = nx + 'px';
    this.vbar.style.top = ny + 'px';
  }if(this.hBarId){
    if(!this.maxX) return;
    nx = -(x*((this.hbar.maxX-this.hbar.minX)/this.maxX)-this.hbar.minX );
    nx = Math.min(Math.max(nx,this.hbar.minX),this.hbar.maxX);
    ny = parseInt(this.hbar.style.top);
    this.hbar.style.left = nx + 'px';
    this.hbar.style.top = ny + 'px';
  } 
  
}

win_scrollObj.prototype.restoreScrollbars=function(){
  var bar;
  if(this.vBarId){
    bar = document.getElementById(this.vBarId);
    bar.style.left = bar.minX + 'px';
    bar.style.top = bar.minY + 'px';
  }
  if(this.hBarId){
    bar = document.getElementById(this.hBarId);
    bar.style.left = bar.minX + 'px';
    bar.style.top = bar.minY + 'px';
  }
}

win_scrollObj.prototype.setBarSize=function(){
  var bar;
  var lyr = document.getElementById(this.lyrId);
  var wn = document.getElementById(this.id);
  if (this.vBarId){
    bar = document.getElementById(this.vBarId);
    bar.style.height = (lyr.offsetHeight>wn.offsetHeight) ? bar.trkHt/(lyr.offsetHeight/wn.offsetHeight) + 'px' : bar.trkHt-2*bar.minY + 'px';
    bar.maxY = bar.trkHt - bar.offsetHeight - bar.minY; 
  }
  if(this.hBarId){
    bar = document.getElementById(this.hBarId);
    bar.style.width = (this.wd>wn.offsetWidth) ? bar.trkWd/(this.wd/wn.offsetWidth) + 'px' : bar.trkWd-2*bar.minX + 'px';
    bar.maxX = bar.trkWd - bar.offsetWidth - bar.minX; 
  }
}

win_scrollObj.prototype.on_load=function(){ 
  this.restoreScrollbars();
  if(this.bSizeDragBar) this.setBarSize();
}

win_scrollObj.prototype.on_scroll=win_scrollObj.prototype.on_slide=function(x,y){
  this.updateScrollbar(x,y);
}

win_scrollObj.prototype.on_scroll_start=win_scrollObj.prototype.on_slide_start=function(){
  if(this.vBarId) this.vbar = document.getElementById(this.vBarId);
  if(this.hBarId) this.hbar = document.getElementById(this.hBarId);
}

win_scrollObj.prototype.on_scroll_end=win_scrollObj.prototype.on_slide_end=function(x, y){ 
  this.updateScrollbar(x,y);
  this.lyr = null;
  this.bar = null; 
}

var win_slidebar={
  obj: null,
  slideDur: 500,  
  init: function(bar,track,axis,x,y) {
    x = x || 0;
    y = y || 0;
    bar.style.left = x + 'px';
    bar.style.top = y + 'px';
    bar.axis = axis;
    track.bar = bar;
    if(axis=='h'){
      bar.trkWd = track.offsetWidth;
      bar.maxX = bar.trkWd - bar.offsetWidth - x; 
      bar.minX = x;
      bar.maxY = y;
      bar.minY = y;
    }else{
      bar.trkHt = track.offsetHeight;
      bar.maxY = bar.trkHt - bar.offsetHeight - y; 
      bar.maxX = x;
      bar.minX = x;
      bar.minY = y;
    }
    bar.on_drag_start =  bar.on_drag =   bar.on_drag_end =  function() {}
    bar.on_slide_start = bar.on_slide =  bar.on_slide_end = function() {}
    bar.onmousedown = this.startDrag;
    track.onmousedown = this.startSlide;
  },
  
  startSlide: function(e){
    if(win_slidebar.aniTimer) clearInterval(win_slidebar.aniTimer);
    e = e ? e : window.event;
    var bar = win_slidebar.obj = this.bar;
    e.offX = (typeof e.layerX != 'undefined') ? e.layerX : e.offsetX;
    e.offY = (typeof e.layerY != 'undefined') ? e.layerY : e.offsetY;
    bar.startX = parseInt(bar.style.left);
    bar.startY = parseInt(bar.style.top);
    if (bar.axis=='v'){
      bar.destX = bar.startX;
      bar.destY = (e.offY<bar.startY) ? e.offY : e.offY - bar.offsetHeight;
      bar.destY = Math.min(Math.max(bar.destY,bar.minY),bar.maxY);
    } else {
      bar.destX = (e.offX<bar.startX) ? e.offX : e.offX - bar.offsetWidth;
      bar.destX = Math.min(Math.max(bar.destX,bar.minX),bar.maxX);
      bar.destY = bar.startY;
    }
    bar.distX = bar.destX - bar.startX;
    bar.distY = bar.destY - bar.startY;
    win_slidebar.per = Math.PI/(2*win_slidebar.slideDur);
    win_slidebar.slideStart = (new Date()).getTime();
    bar.on_slide_start(bar.startX,bar.startY);
    win_slidebar.aniTimer = setInterval('win_slidebar.doSlide()',10);
  },
  
  doSlide: function(){
    if(!win_slidebar.obj) { clearInterval(win_slidebar.aniTimer); return; }    
    var bar = win_slidebar.obj;
    var elapsed = (new Date()).getTime() - this.slideStart;
    if(elapsed<this.slideDur) {
      var x = bar.startX + bar.distX * Math.sin(this.per*elapsed);
      var y = bar.startY + bar.distY * Math.sin(this.per*elapsed);
      bar.style.left = x + 'px';
      bar.style.top = y + 'px';
      bar.on_slide(x,y);
    }else{
      clearInterval(this.aniTimer);
      bar.style.left = bar.destX + 'px';
      bar.style.top = bar.destY + 'px';
      bar.on_slide_end(bar.destX, bar.destY);
      this.obj = null;
    }
  },
  
  startDrag: function(e){
    e = desktop_event.DOMit(e);
    if(win_slidebar.aniTimer) clearInterval(win_slidebar.aniTimer);
    var bar = win_slidebar.obj = this;
    bar.downX = e.clientX;
    bar.downY = e.clientY;
    bar.startX = parseInt(bar.style.left);
    bar.startY = parseInt(bar.style.top);
    bar.on_drag_start(bar.startX,bar.startY);
    desktop_event.add(document,'mousemove',win_slidebar.doDrag,true);
    desktop_event.add(document,'mouseup',win_slidebar.endDrag,true);
    e.stopPropagation();
  },

  doDrag: function(e){
    e = e ? e : window.event;
    if(!win_slidebar.obj) return;
    var bar = win_slidebar.obj; 
    var nx = bar.startX + e.clientX - bar.downX;
    var ny = bar.startY + e.clientY - bar.downY;
    nx = Math.min(Math.max(bar.minX,nx),bar.maxX);
    ny = Math.min(Math.max(bar.minY,ny),bar.maxY);
    bar.style.left = nx + 'px';
    bar.style.top  = ny + 'px';
    bar.on_drag(nx,ny);
    return false;  
  },
  
  endDrag: function(){
    desktop_event.remove(document,'mousemove',win_slidebar.doDrag,true);
    desktop_event.remove(document,'mouseup',win_slidebar.endDrag,true);
    if(!win_slidebar.obj) return;
    win_slidebar.obj.on_drag_end(parseInt(win_slidebar.obj.style.left),parseInt(win_slidebar.obj.style.top));
    win_slidebar.obj = null;  
  }
 
}

function getPageOffsets(el){
  var left = el.offsetLeft;
  var top = el.offsetTop;
  if(el.offsetParent && el.offsetParent.clientLeft || el.offsetParent.clientTop){
    left += el.offsetParent.clientLeft;
    top += el.offsetParent.clientTop;
  }
  while(el=el.offsetParent){
    left += el.offsetLeft;
    top += el.offsetTop;
  }
  return{x:left,y:top};
}
