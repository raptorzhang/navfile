// 									-= Utilities =-
function hex(xVal){
  xInt = parseInt(xVal)
  xHex = xInt.toString(16);
  if(xHex.length<1) return('00');
  if(xHex.length==1 || xHex.length==3 || xHex.length==5 || xHex.length==7){
    return('0'+xHex);
  }
  return(xHex);
}
function hash2rgb(xVal){
  xArray = new Array()
  for(i=0;i<3;i++){
    xTemp = xVal.substr((i*2),2);
    xArray[i] = parseInt(xTemp,16);
  }
  xNewVal = xArray.join(',');
  return(xNewVal);
}

// 									-= Image =-
Painting = new Array(10);
nextPainting = 0;
updatePixelColor = false;

function painting(xObj){
  xObj.xW = 100;
  xObj.yH = 100;
  xObj.guides = true;
  xObj.zoom = 1;
  xObj.backgroundColor  = 'rgb(255,0,255)';
  xObj.transparent  = false;
  xObj.typ='color'
  xObj.colorTable=''
  xObj.palette = '32bit';
  xObj.paletteTable = '';
  xObj.format = 'gif';
  xObj.compression = '';
  xObj.comment = 'created by DevPaint';
}
function createNewImage(){
  PaintWindow = null;
  for(i=0;i<PaintWindows.length;i++) {
    if(PaintWindows[i]==false) {
      PaintWindow = i;
      xCreate = false;
      break;
    }
    if(PaintWindow==null && PaintWindows[i]==null){
      PaintWindow = i;
      xCreate = true;
    }
  }
  if(xCreate) { newPaintWindow(); }
  eval('xObj=DevPaint' + PaintWindow + ';');
  xObj.style.display = 'inline';
  if(xCreate) { painting(xObj); }
  xW = parseInt(f.sizex.value) + 0;
  yH = parseInt(f.sizey.value) + 0;
  if(xW==0 || yH==0) {
    oCenter(NewImage);
    NewImage.style.display='inline';
    DesktopDialog=true;
    return;
  }
  xObj.xW = xW;
  xObj.yH = yH;
  xObjT = eval(xObj.id+'T');
  xObjO = eval(xObj.id+'O');
  xObjA = eval(xObj.id+'A');
  fname = f.newname.value;
  document.getElementById('eFile').getElementsByTagName('a')[1].innerHTML = f.newname.value;
  if(xObj.guides==true) {
    bdr = ' border:1px dotted grey;';
    cs = 1;
  }else{
    bdr = '';
    cs = 0;
  }
  zm = DefaultZoom;
  xObj.zoom = zm;
  typ = (ButtonColor.style.backgroundColor=='black')?'colors':'greyscale';
  bg = OptionColor.style.backgroundColor;
  xObj.backgroundColor = bg;
  xObj.transparent = (OptionTransYes.style.backgroundColor=='black')?true:false;
  xObjT.innerHTML = f.newname.value + ' in ' + currentFolder;
  xObjA.innerHTML = '';
  updateInfoRow(xObj);
  eval(xObj.id+'bg=document.getElementById("'+xObj.id+'bg");');
  xObjA.style.width = ((xW*(zm+cs))+4) + 'px';
  xObjA.style.height = ((yH*(zm+cs))+4) + 'px';
  ni = '';
  xLine = '';
  xLine = xLine + '<tr>';
  for(x=0;x<xW;x++){
    xLine = xLine + '<td width='+zm+' height='+zm+' onMouseDown="setPixelColor(this,selectedColor.style.backgroundColor);" style="background-color:'+bg+';" >';
  }
  xLine = xLine + '</tr>\n';
  for(y=0;y<yH;y++){
    ni = ni + xLine ;
  }
  xObjA.innerHTML = '<table border=0 bgcolor=#CCCCCC cellpadding=0 cellspacing='+cs+' style="'+bdr+'">\n' + ni + '</table>\n';
  xLine = '';
  ni = '';
  PaintWindows[PaintWindow] = true;
  toFront(xObj);
}
function updateInfoRow(xObj){
  trans = (xObj.transparent)? ' transparent' : '';
//  trans = xObj.transparent;
  document.getElementById(xObj.id+'O').innerHTML = ' ' + xObj.xW + 'x' + xObj.yH + '; ' + xObj.typ + '; zoom ' + xObj.zoom + 'x; background <font id='+xObj.id+'bg onClick="if (DesktopDialog){return(false);} selectedColor=this; updatePixelColor=true; updateSelectedColor();" style="background-color:' + xObj.backgroundColor + '; border:1px solid gray;">&nbsp;&nbsp;</font>' + trans + ';';
}
function setBackgroundColor(){
  if(!updatePixelColor) return;
  xObg = currentImg.backgroundColor;
  xNbg = selectedColor.style.backgroundColor;
  if(xObg==xNbg) return;
  currentImg.backgroundColor = xNbg;
  tSetPixelColor = setTimeout('changePixelColor(' + currentImg.id + ',"' + xObg + '","' + xNbg + '");');
}
function changePixelColor(xObj,xOldColor,xNewColor){
  clearTimeout(tSetPixelColor);
  xObjA = document.getElementById(xObj.id+'A');
  xObjs = document.getElementById(xObjA.id).getElementsByTagName('td');
  for(i=0;i<xObjs.length;i++){
    if(xObjs[i].style.backgroundColor==xOldColor) xObjs[i].style.backgroundColor = xNewColor;
  }
  xObjs = null;
}
function setPixelColor(xObj,xColor){
  xObj.style.backgroundColor = xColor;
}

// 									-= Color Chooser =-
selectedColor = null;
colorChooserDisplay = null;
colorChooserX = null;
colorChooserY = null;
colorChooserZ = null;

function selectColor(xRGB){
  if(DesktopDialog && colorChooserDisplay==null) return;
  if(selectedColor!=null){
    xObj = selectedColor;
    xObj.style.backgroundColor = 'rgb('+xRGB+')';
  }
  rgb = xRGB.split(',');
  f.WebColor.value = hex(rgb[0])+hex(rgb[1])+hex(rgb[2]);
  f.Red.value = parseInt(rgb[0]);
  f.Green.value = parseInt(rgb[1]);
  f.Blue.value = parseInt(rgb[2]);
}
function setColor(xName){
  if (xName=='WebColor'){
    xRGB = hash2rgb(f.WebColor.value);
  }else{
    xRGB = f.Red.value +','+ f.Green.value +','+ f.Blue.value;
  }
  if(selectedColor!=null){
    xObj = selectedColor;
    xObj.style.backgroundColor = 'rgb('+xRGB+')';
  }
  rgb = xRGB.split(',');
  f.WebColor.value = hex(rgb[0])+hex(rgb[1])+hex(rgb[2]);
  f.Red.value = rgb[0];
  f.Green.value = rgb[1];
  f.Blue.value = rgb[2];
  setBackgroundColor();
}
function updateSelectedColor(){
  xObj = selectedColor;
  xRGB = xObj.style.backgroundColor;
  xRGB = xRGB.replace('rgb(','');
  xRGB = xRGB.replace(')','');
  selectColor(xRGB);
  setHighliteColor(xObj);
}
function setSelectedColor(xObj){
  xRGB = xObj.style.backgroundColor;
  xRGB = xRGB.replace('rgb(','');
  xRGB = xRGB.replace(')','');
  selectColor(xRGB);
}
function chooseColor(xName){
  colorChooserDisplay = DevColors.style.display;
  colorChooserX = DevColors.style.left;
  colorChooserY = DevColors.style.top;
  colorChooserZ = DevColors.style.zIndex;
  if(xName=='NewImage') {
    xLeft = 308;
    xTop = 193;
  }else if(xName=='ImageProp') {
    xLeft = 280;
    xTop = 200;
  }
  DevColors.style.display = 'none';
  DevColors.style.zIndex = '65534';
  DevColors.style.left = (parseInt(document.getElementById(xName).style.left)+xLeft) + 'px';
  DevColors.style.top = (parseInt(document.getElementById(xName).style.top)+xTop) + 'px';
  DevColors.style.display = 'inline';
}
function resetColorChooser(){
  if(colorChooserDisplay==null) return;
  DevColors.style.display = 'none';
  DevColors.style.zIndex = colorChooserZ;
  DevColors.style.left = colorChooserX;
  DevColors.style.top = colorChooserY;
  DevColors.style.display = colorChooserDisplay;
  colorChooserDisplay = null;
}

// 									-= Zoom =-
DefaultZoom = 2;
function setPixelSize(xObj,xNum){
  xObjA = document.getElementById(xObj.id+'A');
  xObjs = document.getElementById(xObjA.id).getElementsByTagName('td');
  for(i=0;i<xObjs.length;i++){
    xObjs[i].setAttribute('width',xNum);
    xObjs[i].setAttribute('height',xNum);
  }
  xObjs = null;
  updateInfoRow(xObj);
  xObjA.style.width = ((xObj.xW*(xNum+1))+4) + 'px';
  xObjA.style.height = ((xObj.yH*(xNum+1))+4) + 'px';
}
function zoom(xObj,xNum){
  if(!xObj.guides) toggleGuides(xObj);
  zm = parseInt(xObj.zoom);
  zm = zm + xNum;
  if(zm<1) zm = 1;
  xObj.zoom = zm;
  setPixelSize(xObj,zm);
}
function zoomTo(xObj,xNum){
  if(!xObj.guides) toggleGuides(xObj);
  xObj.zoom = xNum;
  setPixelSize(xObj,xNum);
}
function zoomActual(xObj){
  xObj.guides = false;
  xObj.zoom = 1;
  setGuides(document.getElementById(xObj.id+'A').getElementsByTagName('table')[0],false);
  setPixelSize(xObj,1);
}

// 									-= Guides =-
function setGuides(xObj,xVal){
  if(xVal){
    xObj.style.border = '1px dotted grey';
    xObj.setAttribute('cellspacing',1);
  }else{
    xObj.style.border = 'none';
    xObj.setAttribute('cellspacing',0);
  }
}
function toggleGuides(xObj){
  xVal =   xObj.guides;
  xObjA = document.getElementById(xObj.id+'A');
  if(xVal=='true'){
    xObj.guides = false;
    xObjA .style.width = (xObj.xW+4) + 'px';
    xObjA.style.height = (xObj.yH+4) + 'px';
    setGuides(xObjA.getElementsByTagName('table')[0],false);
  }else{
    xObj.guides = true;
    xObjA.style.width = ((xObj.xW*(xObj.zoom+1))+4) + 'px';
    xObjA.style.height = ((xObj.yH*(xObj.zoom+1))+4) + 'px';
    setGuides(xObjA.getElementsByTagName('table')[0],true);
  }
}

// 									-= highlite =-
highlite = '';
hlObject = null;
colorhighlite = '';
colorhlObject = null;
function setHighlite(xObj){
  if(hlObject==xObj) return;
  if(hlObject!=null){
    eval('clearTimeout(hl'+hlObject.id+');');
    hlObject.style.border = highlite;
  }
  hlObject = xObj;
  highlite = xObj.style.border;
  xObj.style.border = '1px dashed gray';
  eval("hl"+xObj.id+"=setTimeout('highliteSelected("+xObj.id+");',500);");
}
function setHighliteColor(xObj){
  if(colorhlObject==xObj) return;
  if(colorhlObject!=null){
    eval('clearTimeout(hl'+colorhlObject.id+');');
    colorhlObject.style.border = colorhighlite;
  }
  colorhlObject = xObj;
  colorhighlite = (xObj.style.border=='')? '1px solid gray' : xObj.style.border;
  xObj.style.border = '1px dashed gray';
  eval("hl"+xObj.id+"=setTimeout('highliteSelected("+xObj.id+");',500);");
}
function highliteSelected(xObj){
  eval('clearTimeout(hl'+xObj.id+');');
  if(xObj.style.borderStyle.indexOf('dotted')>-1){
    xObj.style.borderStyle = 'dashed';
  }else{
    xObj.style.borderStyle = 'dotted';
  }
  eval("hl"+xObj.id+"=setTimeout('highliteSelected("+xObj.id+");',500);");
}

// 									-= Actions =-
Tool = 'paint';
OldTool = '';
function doTool(xObj){
  if(Tool=='paint')    setPixelColor(xObj,selectedColor.style.backgroundColor);
  if(Tool=='chooser'){ setSelectedColor(xObj); Tool = OldTool; }
}
