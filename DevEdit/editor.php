<?php

#------------- NEW FUNCTIONS ------------
# v5 prototype skin with new functions
#
# www_page_open()  - start data output encoding to browser
# www_page_close() - end data output encoding, apply compression
# folderin(dir)    - return "end_folder in end_folder-1" from full path
#

# 04/09/2008 - added to funtions.php


 $dir=urldecode($_GET['dir']);
 $file=urldecode($_GET['file']);
 
include_once("../functions.php");
include_once("../config.php");

www_page_open();
authenticate();

//getcookies();

if (isset($_POST['save'])) save($file);

if(filesize("$dir/$file")>$max_edit_size) {
    print "File size exceeds the limit of $max_edit_size bytes<br>Have the Site Admin edit config.php to customize this";
    exit;
}else {
    $content = htmlentities(file_get_contents("$dir/$file"));
    $d = explode("/", $dir);
    $dirName = $d[count($d)-1];
    $title = "$file in " . $dirName . " - DevEdit - PHP Navigator";
//print <<<HTML
}
?>
<html>
<head>
<script src=devedit.js type="text/javascript"></script>
<script language=JavaScript type="text/javascript">
xObj = null;

function saveEditer(xObj){
  xfont = xObj.style.font.replace(/x/g,'~');
  document.cookie = 'editorScroll=0x0x' + xfont + 'x0x0;';
}

function saveEditur(xObj){
  xfont = xObj.style.font.replace(/x/g,'~');
  document.cookie = 'editorScroll=' + xObj.scrollTop + 'x' + xObj.scrollLeft + 'x' + xfont + ';';
}

function saveEditor(xObj){
  xfont = xObj.style.font.replace(/x/g,'~');
  document.cookie = 'editorScroll=' + xObj.scrollTop + 'x' + xObj.scrollLeft + 'x' + xfont + 'x' + selectStart(xObj) + 'x' + selectEnd(xObj) + ';';
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
  if (xWs.length<2) return;
  xObj.style.font = xWs[2].replace(/~/g,'x');
  if (xWs.length>3) {
    xObj.selectionStart = xWs[3];
    xObj.selectionEnd = xWs[4];
  }
  xObj.scrollTop = xWs[0];
  xObj.scrollLeft = xWs[1];
  xObj.focus();
}

function searchReplace(){
  xSl = String.fromCharCode(92)
//  xSl = '\\';
  xSls = xSl + xSl;
  xS = f.search.value;
  xR = f.replace.value;
  xV = xObj.value;
  saveEditur(xObj);

  if(OptionButtonAll.style.backgroundColor=='black'){
//    xS = xS.replace(/\//g,'\/');
//    xS = xS.replace(/\\\\/g,'\\\\\\\\');
    xS = xS.replace(/\(/g,xSl+'(');
    xS = xS.replace(/\)/g,xSl+')');
    xPtn = new RegExp(xS,'g');
   xObj.value = xV.replace(xPtn,xR);
//   xObj.value = eval('xV.replace(/'+xS+'/gi,xR)');
  }else
    xObj.value = xV.replace(xS,xR);
  restoreEditor(xObj);
}

function searchFind(xObj){
  xS = f.find.value;
  xN = 0; if(xObj.selectionStart!=xObj.selectionEnd) xN = 1;

  if(OptionButtonNext.style.backgroundColor=='black')
    xStart = xObj.value.indexOf(xS,xObj.selectionStart+xN);
  else if(OptionButtonPrev.style.backgroundColor=='black')
    xStart = xObj.value.lastIndexOf(xS,xObj.selectionStart-xN);
  else
    xStart = xObj.value.indexOf(xS,0);

  if (xStart==-1) {
    f.cant.value = xS;
    oCenter(SearchNone);
    SearchNone.style.display = 'inline';
    return;
  }

  storeLines(xObj);
  xObj.selectionStart = xStart;
  xObj.selectionEnd = xStart + xS.length;
  xObj.focus();
  scrollViewLine(currentLine(xObj),xObj);
}

function textSelect(xObj){
  if (document.selection){
    xObj.focus();
    sel = document.selection.createRange();
    alert(sel.text);
  }else{
    alert(xObj.selectionStart+':'+xObj.selectionEnd);
    alert(xObj.value.substring(xObj.selectionStart,xObj.selectionEnd));
  }
}
function selectStart(xObj){
  if (document.selection){
    xObj.focus();
    sel = document.selection.createRange();
    alert(sel.text);
  }else{
    return(xObj.selectionStart);
  }
}
function selectEnd(xObj){
  if (document.selection){
    xObj.focus();
    sel = document.selection.createRange();
    alert(sel.text);
  }else{
    return(xObj.selectionEnd);
  }
}
function selectLength(xObj){
  if (document.selection){
    xObj.focus();
    sel = document.selection.createRange();
    return(sel.text.length);
  }else{
    return(xObj.selectionEnd-xObj.selectionStart);
  }
}

function textCut(xObj){
  saveEditur(xObj);
  if (document.selection){
    xObj.focus();
    xSel = document.selection.createRange();
    xSel.text = '';
  }else{
    xStart = xObj.selectionStart;
    xText = xObj.value.substring(xObj.selectionStart,xObj.selectionEnd);
    xObj.value = xObj.value.substring(0,xObj.selectionStart) + xObj.value.substring(xObj.selectionEnd,xObj.value.length);
    xObj.selectionStart = xStart;
    xObj.selectionEnd = xStart;
  }
  restoreEditor(xObj);
}

function textCopy(xObj){
  if (document.selection){
    xObj.focus();
    xSel = document.selection.createRange();
    xText = xSel.text;
  }else{
    xText = xObj.value.substring(xObj.selectionStart,xObj.selectionEnd);
  }
  xObj.focus();
}

function textPaste(xObj,xPaste){
  saveEditor(xObj);
  if (document.selection){
    xObj.focus();
    xSel = document.selection.createRange();
    xSel.text = xPaste;
  }else{
    xStart = xObj.selectionStart;
    xText = xObj.value.substring(xObj.selectionStart,xObj.selectionEnd);
    xObj.value = xObj.value.substring(0,xObj.selectionStart) + xPaste + xObj.value.substring(xObj.selectionEnd,xObj.value.length);
    xObj.selectionStart = xStart + xPaste.length;
    xObj.selectionEnd = xStart + xPaste.length;
  }
  restoreEditor(xObj);
}

var lines = new Array();
function linenumber(xLine,xObj){
  if(!xLine || xLine==0){
//    xObj.selectionStart = 0;
//    xObj.selectionEnd = 0;
    xObj.focus();
    return;
  }else if(xLine>=lines.length){
    xObj.selectionStart = xObj.value.length;
    xObj.selectionEnd = xObj.value.length;
    scrollViewLine(xLine,xObj);
    xObj.focus();
    return;
  }else{
//alert(lines[xLine]);
    xObj.selectionStart = lines[xLine-1]+1;
    xObj.selectionEnd = lines[xLine];
    scrollViewLine(xLine,xObj);
    xObj.focus();
    return;
  }
//  return (lines.length);
}

function currentLine(xObj){
//alert(xObj.selectionStart);
  if(xObj.selectionStart==xObj.value.length) return (lines.length-1);
  for(i=0;i<lines.length;i++){
    if(xObj.selectionStart<lines[i]+1) return (i);
  }
  return (lines.length-1);
}

function storeLines(xObj){
  i = 0;
  j = 0;
  lines[0] = 0;
  x = xObj.value;
  while(i!=-1){
    i++;
    i = x.indexOf('\\n',i);
    j++;
    lines[j] = i;
  }
  lines[j] = x.length;
  x = '';
}

function scrollViewLine(xLine,xObj){
  xLineHeight = (xObj.scrollHeight)/(lines.length);
  xObj.scrollTop = (xLineHeight*xLine)-xLineHeight;
//  scrollSetBoth('OpenEditor',xObj);
}

</script>
<script language=JavaScript type="text/javascript">

Paste = new Array();

function displayFunction(xLang,xLib,xFn){
//  alert('language:'+xLang+'\\nextension:'+xLib+'\\nfunction:'+xFn);
  Paste[xLang] = xFn;
//  alert(dataBox.editor.getFocus());
}

function insertScript(xLang){
  if(isPlain){
    if(eval('dataBox.textarea')) xObj = dataBox.textarea;
    insertSnippet(Paste[xLang]);
  }else{
    if(eval('dataBox.textarea')) { dataBox.editor.insertSnippet(Paste[xLang]); dataBox.contentWindow.focus();}
    else insertSnippet(Paste[xLang]);
  }
}

function insertSnippet(xSnippet){
  textPaste(xObj,xSnippet);
  xObj.focus();
}

isPlain = false;
function submitform(){
	if(!isPlain && (dataBox.textarea)) dataBox.toggleEditor();
	return(true);
}
</script></head>
<body onResize='fixResize()'><form action='' method=POST style="padding:0px; margin:0px; border:0px;"><input type=hidden name=dir value="<?= $dir ?>">
<input type=hidden name=file value="<?= $file ?>">
<input type=hidden name=action value=Save>
<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
<tr><td colspan=2 align=center><font face=tahoma size=2><b><font size=1><?= $dir ?></font>/<?= $file ?></b></td>
<tr><td width=* style="overflow:hidden"><!--
<img src=images/html.gif border=1 />
<input name=save type=image src=images/save.gif value=Save onclick=submitform();>
<input type=button onClick="history.back()" value=Undo>
<input type=button onClick="window.close();" value=Close>
--><script language=JavaScript type="text/javascript">
var sbtns = [
	["save", "Save", "Saves this document", "submitform();document.forms[0].submit();"],
	[""],
	["selectall", "Select all (Ctrl+A)", "Select the entire document", ""],
	["cut", "Cut (Ctrl+X)", "Cut the selection to the clipboard", ""],
	["copy", "Copy (Ctrl+C)", "Copy the selection to the clipboard", ""],
	["paste", "Paste (Ctrl+V)", "Insert clipboard contents", ""],
	[""],
	["undo", "Undo (Ctrl+Z)", "Undo the last action", ""],
	["redo", "Redo (Ctrl+Y)", "Redo the previously undone action", ""],
	[""],
	["html", "Change mode", "Change between lay-out and HTML mode", "isPlain=(isPlain)?false:true; if(dataBox.textarea) dataBox.toggleEditor();else isPlain=true;"]
];
for(i=0;i<sbtns.length;i++){
  if(sbtns[i][0] != "")document.write("<img src=\"images/"+sbtns[i][0]+".gif\" id=\""+sbtns[i][0]+"\" alt=\""+sbtns[i][1]+"\" title=\""+sbtns[i][1]+"\" onclick=\""+sbtns[i][3]+"\" width=20 height=20 style=\"border:ButtonFace 1px outset;\" onmouseover=\"this.style.border='ButtonFace 1px inset'; window.status='"+sbtns[i][2]+"'\" onmouseout=\"this.style.border='ButtonFace 1px outset'; \">");
  else document.write("<img src=\"images/blank.gif\" width=\"8\" height=\"20\" style='border:none'>");
}
</script></td>
<td width=200><select name=libs title=" library " style="width:200px; border:1px solid gray; padding:0px; margin:0px;"><option>..libraries<option>favorites<option>3rd Patry<option>standard<option>PHP</select></td></tr>
<tr><td><textarea rows=24 cols=80 class="codepress devedit" id=dataBox name=data style="border:1px solid gray; width:expression(document.body.clientWidth-220); height:expression(document.body.clientHeight-58);"><?= $content ?></textarea></td>
<script language=JavaScript>
xObj = document.getElementById('dataBox');

function fixResize(){
  if (window.innerHeight){
    if(dataBox){
	dataBox.style.width = window.innerWidth-220;
	dataBox.style.height = window.innerHeight-58;
	dataBox.textarea.style.width = window.innerWidth-220;
	dataBox.textarea.style.height = window.innerHeight-58;
    }else{
	xObj.style.width = window.innerWidth-220;
	xObj.style.height = window.innerHeight-58;
    }
  }else{
	xObj.style.width = document.body.clientWidth-220;
	xObj.style.height = document.body.clientHeight-58;
  }
}
if (window.innerHeight){
	xObj.style.width = window.innerWidth-220;
	xObj.style.height = window.innerHeight-58;
}

function showerr(err){
  if(!err) err = Error;
//  xErr = 'Error: '+err+'\n constructor:'+err.constructor+'\n message:'+err.message+'\n name:'+err.name;
  xErr = 'Error: '+err+'\n message:'+err.message+'\n name:'+err.name;
alert(xErr);
  if(window.innerHeight) xErr = xErr+'\n lineNumber:'+err.lineNumber+'\n fileName:'+err.fileName+'\n stack:'+err.stack;
  else xErr = xErr+'\n number:'+err.number+'\n description:'+err.description;
//  alert(xErr+err.toSource);
//  alert(xErr+err.toString);
  alert(xErr);
}

document.title = '<?= $title ?>';

window.onerror = showerr;
</script>
<td><iframe border=0 frameborder=0 width=100% height=100% style="border:1px solid gray;" src="libraries.php"></iframe></td></tr>
</table></form></body></html>
<?php

www_page_close();