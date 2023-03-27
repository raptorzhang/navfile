<?php

#------------- NEW FUNCTIONS ------------
# v5 prototype skin with new functions
#
# www_page_open() - start data output encoding to browser
# www_page_close() - end data output encoding, apply compression
# folderin(dir) - return "end_folder in end_folder-1" from full path
#
$compress = true;

function www_page_open(){
  global $compress, $encoding, $deflate, $gzip;
  $encoding = 'none';
  if($compress){
    $deflate = false;
    $gzip = false;
    $en = $_SERVER['HTTP_ACCEPT_ENCODING'];
    ob_start();
    if(strstr($en,"gzip")){
      $gzip = true;
      header("Content-Encoding: gzip");	// start buffering for gzip encoding
      $encoding = 'gzip';
    }elseif(strstr($en,"deflate")){
      $deflate = true;
      header("Content-Encoding: deflate");	// start buffering for deflate encoding
      $encoding = 'deflate';
    }
  }
}

function www_page_close(){
  global $compress, $deflate, $gzip;
  if($compress){
    $data = ob_get_clean();
    if($deflate){
      echo gzdeflate($data);
    }elseif($gzip){
      echo gzencode($data,9);
    }else{
      echo $data;
    }
  }
}

www_page_open();
?>
<style>
body{
  font-family: Trebuchet MS, Lucida Sans Unicode, Arial, sans-serif;
  font-size:11px;
  margin:0px;
  padding:0px;
}
div.head{
  cursor:default;
  color:white;
  background-color:gray;
  border:1px solid black;
}
table{
  cursor:default;
  border:1px solid gray;
  border-collapse;collapse;
  font-family: Trebuchet MS, Lucida Sans Unicode, Arial, sans-serif;
  font-size:11px;
}
table.fnst{
  background-color:white;
}
td{
  border-bottom:1px solid gray;
}
td.fnlib{
  background-color:lightgray;
}
td.fn{
  background-color:darkgray;
  color:white;
}
</style>
<script language=JavaScript>
function Cancel(e) { 
  e = e ? e : window.event;
  e.tgt = e.srcElement ? e.srcElement: e.target;
  if(!e.preventDefault) e.preventDefault = function () { return true; }
  if(!e.stopPropagation) e.stopPropagation = function () { if(window.event) window.event.cancelBubble = true; }
  if(e.cancelBubble) e.cancelBubble = true;
  e.preventDefault();
  e.stopPropagation();
  return false;
}

function dq(xStr){
  return ('"'+xStr+'"');
}

function toggleHead(xLib){
  if(xLib.style.display=='none') {
    xLib.style.display = '';
  }else{
    xLib.style.display = 'none';
  }
}
fnlib = null;
fnlibt = null;
function toggleLib(xLib,xFns){
  if(fnlib!=null && fnlib!=xLib){
    fnlib.className = '';
    fnlibt.style.display = 'none';
  }
  if(xFns.style.display=='none') {
    xLib.className = 'fnlib';
    xFns.style.display = '';
    fnlib = xLib;
    fnlibt = xFns;
  }else{
    xLib.className = '';
    xFns.style.display = 'none';
    fnlib = null;
    fnlibt = null;
  }
}
fnold = null;
function clickFunction(xFn,xLang,xLib,xFname){
  if(fnold!=null && fnold!=xFn) fnold.className = '';
  xFn.className = 'fn';
  fnold = xFn;
  parent.displayFunction(xLang,xLib,xFname);
}
</script>
<body>
<?
  if(isset($_REQUEST['lib'])){
    $lib = $_REQUEST['lib'];
  }else{
    $lib = array('html','style','javascript','vbscript','php');
  }

  if(in_array('html',$lib)){

?>
<div class=head onclick="toggleHead(htmlAll)"><b>HTML (all)</b></div>
<div name=htmlAll id=htmlAll style="display:none;">
<table border=0 cellpadding=1 cellspacing=0 width=100%>
<tr><td class= width=100% onClick="clickFunction(this,'html','anchor','<a>$0</a>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">a</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','anchor','<a href='+dq('$0')+'></a>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">a href</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','anchor','<a name='+dq('$0')+'></a>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">a name</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','anchor','<a href='+dq('javascript:void(0)')+' onclick='+dq('')+'>$0</a>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">a javascript</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','image','<area shape= coords='+dq('')+' href='+dq('')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">area</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','image','<area shape=rect coords='+dq('X1,Y1,X2,Y2')+' href='+dq('')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">area rect</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','image','<area shape=circle coords='+dq('X1,Y1,RADIUS')+' href='+dq('')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">area circle</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','image','<area shape=poly coords='+dq('X1,Y1,X2,Y2,X3,Y3,..')+' href='+dq('')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">area polygon</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<b>$0</b>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">b</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','head','<base $0>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">base</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','head','<base href='+dq('$0')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">base href</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','head','<base target=$0>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">base target</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<big>$0</big>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">big</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<body>\n$0\n</body>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">body</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','linebreak','<br>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">br</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<cite>$0</cite>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">cite</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<code>$0</code>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">code</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','misc','<!-- $0 -->'); return(Cancel(event));" onDblClick="parent.insertScript('html')">comment</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','list','<dd>\n\t$0\n</dd>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">dd</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','area','<div>$0</div>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">div</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','area','<div id=$0 ></div>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">div id</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','area','<div class=$0 ></div>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">div class</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','area','<div style='+dq('$0')+' >\n</div>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">div style</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','area','<div id=$0 class= ></div>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">div id class</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','area','<div id=$0 style='+dq('')+'></div>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">div id style</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','area','<div id=$0 class= style='+dq('')+'>\n</div>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">div id class style</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','list','<dl>\n$0<dl>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">dl</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','list','<dl>\n\t<dt>$0<dt>\n\t<dd>\n\t\t\n\t</dd>\n<dl>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">dl dt dd</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','list','<dt>\n$0<dt>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">dt</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<em>$0</em>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">em</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','object','embed'); return(Cancel(event));" onDblClick="parent.insertScript('html')">embed</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','fieldset','<fieldset>\n</fieldset>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">fieldset</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<form>\n$0\n</form>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">form</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<form name=$0>\n\n</form>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">form name</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<form action='+dq('$0')+'>\n\n</form>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">form action</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<form onSubmit='+dq('return(checkform(this));')+'>\n\n</form>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">form onSubmit</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<form name= id= method=POST action='+dq('$0')+'>\n\n</form>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">form POST</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<form name= id= method=GET action='+dq('$0')+'>\n\n</form>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">form GET</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<form name=$0 id= method=POST action= onSubmit='+dq('return(checkform(this));')+'>\n\n</form>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">form FULL POST</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<form name=$0 id= method=GET action= onSubmit='+dq('return(checkform(this));')+'>\n\n</form>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">form FULL GET</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','frame','<frame>');  return(Cancel(event));" onDblClick="parent.insertScript('html')">frame</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','frame','<frameset>\n\t<frame name=$0 src='+dq('')+'>\n\t<frame name= src='+dq('')+'>\n</frameset>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">frameset</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','frame','<frameset>\n\t<frame name=$0 src='+dq('')+' width= >\n\t<frame name= src='+dq('')+' width= >\n</frameset>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">frameset width</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','frame','<frameset>\n\t<frame name=$0 src='+dq('')+' height= >\n\t<frame name= src='+dq('')+' height= >\n</frameset>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">frameset height</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<h1>$0</h1>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">h1</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<h2>$0</h2>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">h2</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<h3>$0</h3>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">h3</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<h4>$0</h4>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">h4</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<h5>$0</h5>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">h5</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<h6>$0</h6>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">h6</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<head>\n$0\n</head>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">head</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<head>\n<title>$0</title>\n</head>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">head title</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<head>\n<title>$0</title>\n<meta content='+dq('')+'>\n</head>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">head title meta</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<head>\n<title>$0</title>\n<style>\n\n</style>\n</head>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">head title style</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<head>\n<title>$0</title>\n<script>\n\n</script>\n</head>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">head title script</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<head>\n<title>$0</title>\n<meta content='+dq('')+'>\n<style>\n\n</style>\n<script>\n\n</script>\n</head>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">head FULL</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','misc','<hr>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">hr</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<html>\n\n</html>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">html</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<html>\n<body>\n\n</body>\n</html>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">html body</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<html>\n<head>\n<title>$0</title>\n</head>\n<body>\n\n</body>\n</html>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">html head body</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<html>\n<head>\n<title>$0</title>\n<meta content='+dq('')+'>\n<style>\n\n</style>\n<script>\n\n</script>\n</head>\n<body>\n\n</body>\n</html>');return(Cancel(event));" onDblClick="parent.insertScript('html')">html FULL</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<i>$0</i>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">i</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','frame','<iframe src='+dq('$0')+'></iframe>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">iframe</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','image','<img src='+dq('$0')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">img</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<input type=$0 value='+dq('')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">input</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<input name=$0 type=text value='+dq('')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">input text</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<input name=$0 type=radio value='+dq('')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">input radio</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<input name=$0 type=checkbox value='+dq('')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">input checkbox</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<input name=$0 type=password value='+dq('')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">input password</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<input name=$0 type=image value='+dq('')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">input image</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<input name=$0 type=button value='+dq('')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">input button</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<input name=$0 type=submit value='+dq('submit')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">input SUBMIT</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<input name=$0 type=cancel value='+dq('clear')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">input CANCEL</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<ins>$0</ins>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">ins</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','fieldset','<legend>$0</legend>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">legend</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','list','<li>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">li</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','list','<li>$0</li>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">li</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','object','link'); return(Cancel(event));" onDblClick="parent.insertScript('html')">link</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','image','<map name=$0 >\n\n</map>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">map</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','head','<meta content='+dq('$0')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">meta</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','object','object'); return(Cancel(event));" onDblClick="parent.insertScript('html')">object</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','list','<ol>\n\t$0\n</ol>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">ol</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','list','<ol>\n\t<li>$0</li>\n</ol>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">ol li</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<option>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">option</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<option>$0</option>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">option</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<option value=$0 >'); return(Cancel(event));" onDblClick="parent.insertScript('html')">option value</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<option value=$0 ></option>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">option value</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<p>$0</p>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">p</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<p id= >$0</p>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">p id</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<p class= >$0</p>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">p class</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<p id= class= >$0</p>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">p id class</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<pre>$0</pre>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">pre</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<s>$0</s>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">s</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','object','<script>\n$0</script>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">script</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','object','<script language=JavaScript>\n$0</script>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">script javascript</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','object','script language=vbScript>\n$0</script>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">script vbscript</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<select>$0</select>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">select</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<select name=$0>\n\t<option>\n</select>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">select name option</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<select name=$0>\n\t<option></option>\n</select>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">select name option</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<select name=$0>\n\t<option value= >\n</select>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">select name value</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<select name=$0>\n\t<option value= ></option>\n</select>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">select name value</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','area','<span>$0</span>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">span</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','area','<span id=$0></span>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">span id</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','area','<span class=$0></span>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">span class</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','area','<span style='+dq('$0')+'></span>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">span style</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','area','<span id=$0 style='+dq('')+'></span>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">span id style</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','area','<span id=$0 class= ></span>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">span id class</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','area','<span id=$0 class= style='+dq('')+'></span>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">span FULL</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<strong>$0</strong>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">strong</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','object','<style>/n$0/n</style>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">style</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','object','<style type=text/css >/n$0/n</style>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">style type</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<sup>$0</sup>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">sup</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<sub>$0</sub>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">sub</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<table>\n\n</table>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">table</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<tbody>\n$0\n</tbody>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">tbody</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<td>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">td</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<td>$0</td>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">td</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<td width=$0></td>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">td width</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<td width=100%>$0</td>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">td 100%</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<td width=50%>$0</td>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">td 50%</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<td width=50%>$0</td>\n<td width=50%></td>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">td 50% 2</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<td width=33%>$0</td>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">td 33%</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<td width=33%>$0</td>\n<td width=33%></td>\n<td width=33%></td>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">td 33% 3</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<td width=25%>$0</td>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">td 25%</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<td width=25%>$0</td>\n<td width=25%></td>\n<td width=25%></td>\n<td width=25%></td>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">td 25% 4</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<td>$0</td><td></td>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">td 2</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<td>$0</td><td></td><td></td>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">td 3</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<td>$0</td><td></td><td></td><td></td>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">td 4</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<td>$0</td><td></td><td></td><td></td><td></td>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">td 5</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<tfoot>\n$0\n</tfoot>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">tfoot</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<textarea></textarea>');return(Cancel(event));" onDblClick="parent.insertScript('html')">textarea</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<textarea name=$0 rows= cols= ></textarea>');return(Cancel(event));" onDblClick="parent.insertScript('html')">textarea name</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<th>$0</th>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">th</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','head','<title>$0</title>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">title</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<tr>$0</tr>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">tr</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<tr><td>$0</td></tr>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">tr td</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<tr><td>$0</td><td></td></tr>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">tr td 2</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<tr><td>$0</td><td></td><td></td></tr>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">tr td 3</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<tr><td>$0</td><td></td><td></td><td></td></tr>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">tr td 4</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<tr><td width=100%>$0</td></tr>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">tr 100%</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<tr><td width=50%>$0</td><td width=50%></td></tr>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">tr 50% 2</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<tr><td width=33%>$0</td><td width=33%></td><td width=33%></td></tr>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">tr 33% 3</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<tr><td width=25%>$0</td><td width=25%></td><td width=25%></td><td width=25%></td></tr>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">tr 25% 4</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<tt>$0</tt>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">tt</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','list','<ul>\n$0\n</ul>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">ul</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','list','<ul>\n\t<li>$0\n</ul>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">ul li</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','list','<ul>\n\t<li>$0</li>\n</ul>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">ul li</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<u>$0</u>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">u</td></tr>
</table>
</div>
<div class=head onclick="toggleHead(htmlObj)"><b>HTML (objects)</b></div>
<div name=htmlObj id=htmlObj style="display:none;">
<table border=0 cellpadding=1 cellspacing=0 width=100%>
<tr><td class= width=100% onClick="toggleLib(this,htmlObj_document)">document
<table name=htmlObj_document id=htmlObj_document class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<body>\n$0\n</body>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">body</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<head>\n$0\n</head>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">head</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<head>\n<title>$0</title>\n</head>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">head title</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<head>\n<title>$0</title>\n<meta content='+dq('')+'>\n</head>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">head title meta</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<head>\n<title>$0</title>\n<style>\n\n</style>\n</head>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">head title style</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<head>\n<title>$0</title>\n<script>\n\n</script>\n</head>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">head title script</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<head>\n<title>$0</title>\n<meta content='+dq('')+'>\n<style>\n\n</style>\n<script>\n\n</script>\n</head>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">head FULL</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<html>\n\n</html>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">html</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<html>\n<body>\n\n</body>\n</html>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">html body</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<html>\n<head>\n<title>$0</title>\n</head>\n<body>\n\n</body>\n</html>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">html head body</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<html>\n<head>\n<title>$0</title>\n<meta content='+dq('')+'>\n<style>\n\n</style>\n<script>\n\n</script>\n</head>\n<body>\n\n</body>\n</html>');return(Cancel(event));" onDblClick="parent.insertScript('html')">html FULL</td></tr>
</table></td></tr>
<tr><td class= width=100% onClick="toggleLib(this,htmlObj_anchor)">anchor
<table name=htmlObj_anchor id=htmlObj_anchor class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
<tr><td class= width=100% onClick="clickFunction(this,'html','anchor','<a>$0</a>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">a</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','anchor','<a href='+dq('$0')+'></a>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">a href</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','anchor','<a name='+dq('$0')+'></a>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">a name</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','anchor','<a href='+dq('javascript:void(0)')+' onclick='+dq('')+'>$0</a>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">a javascript</td></tr>
</table></td></tr>
<tr><td class= width=100% onClick="toggleLib(this,htmlObj_area)">area
<table name=htmlObj_area id=htmlObj_area class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
<tr><td class= width=100% onClick="clickFunction(this,'html','area','<div>$0</div>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">div</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','area','<div id=$0 ></div>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">div id</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','area','<div class=$0 ></div>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">div class</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','area','<div style='+dq('$0')+' >\n</div>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">div style</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','area','<div id=$0 class= ></div>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">div id class</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','area','<div id=$0 style='+dq('')+'></div>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">div id style</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','area','<div id=$0 class= style='+dq('')+'>\n</div>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">div id class style</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','area','<span>$0</span>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">span</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','area','<span id=$0></span>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">span id</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','area','<span class=$0></span>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">span class</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','area','<span style='+dq('$0')+'></span>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">span style</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','area','<span id=$0 style='+dq('')+'></span>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">span id style</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','area','<span id=$0 class= ></span>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">span id class</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','area','<span id=$0 class= style='+dq('')+'></span>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">span FULL</td></tr>
</table></td></tr>
<tr><td class= width=100% onClick="toggleLib(this,htmlObj_frame)">frame
<table name=htmlObj_frame id=htmlObj_frame class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
<tr><td class= width=100% onClick="clickFunction(this,'html','frame','<frame>');  return(Cancel(event));" onDblClick="parent.insertScript('html')">frame</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','frame','<frameset>\n\t<frame name=$0 src='+dq('')+'>\n\t<frame name= src='+dq('')+'>\n</frameset>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">frameset</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','frame','<frameset>\n\t<frame name=$0 src='+dq('')+' width= >\n\t<frame name= src='+dq('')+' width= >\n</frameset>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">frameset width</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','frame','<frameset>\n\t<frame name=$0 src='+dq('')+' height= >\n\t<frame name= src='+dq('')+' height= >\n</frameset>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">frameset height</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','frame','<iframe src='+dq('$0')+'></iframe>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">iframe</td></tr>
</table></td></tr>
<tr><td class= width=100% onClick="toggleLib(this,htmlObj_form)">form
<table name=htmlObj_form id=htmlObj_form class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<form>\n$0\n</form>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">form</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<form name=$0>\n\n</form>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">form name</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<form action='+dq('$0')+'>\n\n</form>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">form action</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<form onSubmit='+dq('return(checkform(this));')+'>\n\n</form>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">form onSubmit</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<form name= id= method=POST action='+dq('$0')+'>\n\n</form>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">form POST</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<form name= id= method=GET action='+dq('$0')+'>\n\n</form>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">form GET</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<form name=$0 id= method=POST action= onSubmit='+dq('return(checkform(this));')+'>\n\n</form>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">form FULL POST</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<form name=$0 id= method=GET action= onSubmit='+dq('return(checkform(this));')+'>\n\n</form>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">form FULL GET</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<input type=$0 value='+dq('')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">input</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<input name=$0 type=text value='+dq('')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">input text</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<input name=$0 type=radio value='+dq('')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">input radio</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<input name=$0 type=checkbox value='+dq('')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">input checkbox</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<input name=$0 type=password value='+dq('')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">input password</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<input name=$0 type=image value='+dq('')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">input image</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<input name=$0 type=button value='+dq('')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">input button</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<input name=$0 type=submit value='+dq('submit')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">input SUBMIT</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<input name=$0 type=cancel value='+dq('clear')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">input CANCEL</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<option>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">option</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<option>$0</option>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">option</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<option value=$0 >'); return(Cancel(event));" onDblClick="parent.insertScript('html')">option value</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<option value=$0 ></option>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">option value</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<select>$0</select>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">select</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<select name=$0>\n\t<option>\n</select>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">select name option</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<select name=$0>\n\t<option></option>\n</select>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">select name option</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<select name=$0>\n\t<option value= >\n</select>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">select name value</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<select name=$0>\n\t<option value= ></option>\n</select>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">select name value</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<textarea></textarea>');return(Cancel(event));" onDblClick="parent.insertScript('html')">textarea</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','form','<textarea name=$0 rows= cols= ></textarea>');return(Cancel(event));" onDblClick="parent.insertScript('html')">textarea name</td></tr>
</table></td></tr>
<tr><td class= width=100% onClick="toggleLib(this,htmlObj_head)">head
<table name=htmlObj_head id=htmlObj_head class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
<tr><td class= width=100% onClick="clickFunction(this,'html','head','<base $0>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">base</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','head','<base href='+dq('$0')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">base href</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','head','<base target=$0>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">base target</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','object','link'); return(Cancel(event));" onDblClick="parent.insertScript('html')">link</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','object','<script language=JavaScript>\n$0</script>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">script javascript</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','object','<style type=text/css >/n$0/n</style>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">style type</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','head','<meta content='+dq('$0')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">meta</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','head','<title>$0</title>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">title</td></tr>
</table></td></tr>
<tr><td class= width=100% onClick="toggleLib(this,htmlObj_image)">image
<table name=htmlObj_image id=htmlObj_image class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
<tr><td class= width=100% onClick="clickFunction(this,'html','image','<area shape= coords='+dq('')+' href='+dq('')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">area</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','image','<area shape=rect coords='+dq('X1,Y1,X2,Y2')+' href='+dq('')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">area rect</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','image','<area shape=circle coords='+dq('X1,Y1,RADIUS')+' href='+dq('')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">area circle</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','image','<area shape=poly coords='+dq('X1,Y1,X2,Y2,X3,Y3,..')+' href='+dq('')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">area polygon</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','image','<img src='+dq('$0')+'>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">img</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','image','<map name=$0 >\n\n</map>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">map</td></tr>
</table></td></tr>
<tr><td class= width=100% onClick="toggleLib(this,htmlObj_insert)">insert
<table name=htmlObj_insert id=htmlObj_insert class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
<tr><td class= width=100% onClick="clickFunction(this,'html','object','<script>\n$0</script>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">script</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','object','<script language=JavaScript>\n$0</script>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">script javascript</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','object','<script language=vbScript>\n$0</script>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">script vbscript</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','object','<style>/n$0/n</style>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">style</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','object','<style type=text/css >/n$0/n</style>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">style type</td></tr>
</table></td></tr>
<tr><td class= width=100% onClick="toggleLib(this,htmlObj_list)">list
<table name=htmlObj_list id=htmlObj_list class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
<tr><td class= width=100% onClick="clickFunction(this,'html','list','<dd>\n\t$0\n</dd>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">dd</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','list','<dl>\n$0<dl>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">dl</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','list','<dl>\n\t<dt>$0<dt>\n\t<dd>\n\t\t\n\t</dd>\n<dl>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">dl dt dd</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','list','<dt>\n$0<dt>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">dt</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','list','<li>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">li</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','list','<li>$0</li>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">li</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','list','<ol>\n\t$0\n</ol>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">ol</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','list','<ol>\n\t<li>$0</li>\n</ol>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">ol li</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','list','<ul>\n$0\n</ul>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">ul</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','list','<ul>\n\t<li>$0\n</ul>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">ul li</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','list','<ul>\n\t<li>$0</li>\n</ul>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">ul li</td></tr>
</table></td></tr>
<tr><td class= width=100% onClick="toggleLib(this,htmlObj_link)">link
<table name=htmlObj_link id=htmlObj_link class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
</table></td></tr>
<tr><td class= width=100% onClick="toggleLib(this,htmlObj_meta)">meta
<table name=htmlObj_meta id=htmlObj_meta class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
</table></td></tr>
<tr><td class= width=100% onClick="toggleLib(this,htmlObj_object)">object
<table name=htmlObj_object id=htmlObj_object class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
<tr><td class= width=100% onClick="clickFunction(this,'html','object','embed'); return(Cancel(event));" onDblClick="parent.insertScript('html')">embed</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','object','object'); return(Cancel(event));" onDblClick="parent.insertScript('html')">object</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','object','<script>\n$0</script>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">script</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','object','<script language=JavaScript>\n$0</script>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">script javascript</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','object','<script language=vbScript>\n$0</script>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">script vbscript</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','object','<style>/n$0/n</style>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">style</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','object','<style type=text/css >/n$0/n</style>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">style type</td></tr>
</table></td></tr>
<tr><td class= width=100% onClick="toggleLib(this,htmlObj_table)">table
<table name=htmlObj_table id=htmlObj_table class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<table>\n\n</table>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">table</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<table border=0>\n\n</table>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">table border 0</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<table border=0 cellspacing=0 cellpadding=0>\n\n</table>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">table cell 0</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<table border=0 cellspacing=0 cellpadding=0 width=100% height=100% align=center valign=middle><tr><td width=100% height=100% align=center valign=middle><table borde=0><tr><td>\n\n</td></tr></table></td></tr></table>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">table page center</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<tbody>\n$0\n</tbody>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">tbody</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<td>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">td</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<td>$0</td>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">td</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<td width=$0></td>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">td width</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<td width=100%>$0</td>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">td 100%</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<td width=50%>$0</td>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">td 50%</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<td width=50%>$0</td>\n<td width=50%></td>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">td 50% 2</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<td width=33%>$0</td>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">td 33%</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<td width=33%>$0</td>\n<td width=33%></td>\n<td width=33%></td>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">td 33% 3</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<td width=25%>$0</td>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">td 25%</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<td width=25%>$0</td>\n<td width=25%></td>\n<td width=25%></td>\n<td width=25%></td>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">td 25% 4</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<td>$0</td><td></td>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">td 2</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<td>$0</td><td></td><td></td>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">td 3</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<td>$0</td><td></td><td></td><td></td>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">td 4</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<td>$0</td><td></td><td></td><td></td><td></td>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">td 5</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<tfoot>\n$0\n</tfoot>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">tfoot</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<th>$0</th>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">th</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<tr>$0</tr>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">tr</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<tr><td>$0</td></tr>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">tr td</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<tr><td>$0</td><td></td></tr>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">tr td 2</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<tr><td>$0</td><td></td><td></td></tr>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">tr td 3</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<tr><td>$0</td><td></td><td></td><td></td></tr>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">tr td 4</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<tr><td width=100%>$0</td></tr>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">tr 100%</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<tr><td width=50%>$0</td><td width=50%></td></tr>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">tr 50% 2</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<tr><td width=33%>$0</td><td width=33%></td><td width=33%></td></tr>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">tr 33% 3</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','table','<tr><td width=25%>$0</td><td width=25%></td><td width=25%></td><td width=25%></td></tr>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">tr 25% 4</td></tr>
</table></td></tr>
<tr><td class= width=100% onClick="toggleLib(this,htmlObj_text)">text
<table name=htmlObj_text id=htmlObj_text class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<b>$0</b>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">b</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<big>$0</big>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">big</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','linebreak','<br>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">br</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<cite>$0</cite>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">cite</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<code>$0</code>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">code</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<em>$0</em>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">em</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<h1>$0</h1>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">h1</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<h2>$0</h2>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">h2</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<h3>$0</h3>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">h3</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<h4>$0</h4>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">h4</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<h5>$0</h5>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">h5</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<h6>$0</h6>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">h6</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<i>$0</i>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">i</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<ins>$0</ins>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">ins</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<p>$0</p>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">p</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<p id= >$0</p>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">p id</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<p class= >$0</p>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">p class</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<p id= class= >$0</p>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">p id class</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<pre>$0</pre>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">pre</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<s>$0</s>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">s</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<small>$0</small>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">small</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<strong>$0</strong>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">strong</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<sup>$0</sup>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">sup</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<sub>$0</sub>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">sub</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<tt>$0</tt>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">tt</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','text','<u>$0</u>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">u</td></tr>
</table></td></tr>
<tr><td class= width=100% onClick="toggleLib(this,htmlObj_misc)">misc
<table name=htmlObj_misc id=htmlObj_misc class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<body>\n$0\n</body>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">body</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','linebreak','<br>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">br</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','misc','<!-- $0 -->'); return(Cancel(event));" onDblClick="parent.insertScript('html')">comment</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','fieldset','<fieldset>\n$0\n</fieldset>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">fieldset</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<head>\n$0\n</head>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">head</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<head>\n<title>$0</title>\n</head>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">head title</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<head>\n<title>$0</title>\n<meta content='+dq('')+'>\n</head>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">head title meta</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<head>\n<title>$0</title>\n<style>\n\n</style>\n</head>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">head title style</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<head>\n<title>$0</title>\n<script>\n\n</script>\n</head>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">head title script</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<head>\n<title>$0</title>\n<meta content='+dq('')+'>\n<style>\n\n</style>\n<script>\n\n</script>\n</head>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">head FULL</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','misc','<hr>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">hr</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<html>\n\n</html>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">html</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<html>\n<body>\n\n</body>\n</html>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">html body</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<html>\n<head>\n<title>$0</title>\n</head>\n<body>\n\n</body>\n</html>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">html head body</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','html','<html>\n<head>\n<title>$0</title>\n<meta content='+dq('')+'>\n<style>\n\n</style>\n<script>\n\n</script>\n</head>\n<body>\n\n</body>\n</html>');return(Cancel(event));" onDblClick="parent.insertScript('html')">html FULL</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','fieldset','<legend>$0</legend>'); return(Cancel(event));" onDblClick="parent.insertScript('html')">legend</td></tr>
</table></td></tr>
<tr><td class= width=100% onClick="toggleLib(this,htmlObj_align)">align
<table name=htmlObj_align id=htmlObj_align class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
</table></td></tr>
<tr><td class= width=100% onClick="toggleLib(this,htmlObj_valign)">valign
<table name=htmlObj_valign id=htmlObj_valign class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
</table></td></tr>
<tr><td class= width=100% onClick="toggleLib(this,htmlObj_color)">color
<table name=htmlObj_color id=htmlObj_color class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
<tr><td class= width=100% align=justify>Color Name HEX</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">AliceBlue  	#F0F8FF</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">AntiqueWhite  	#FAEBD7</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Aqua  	#00FFFF #0FF</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Aquamarine  	#7FFFD4</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Azure  	#F0FFFF</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Beige  	#F5F5DC</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Bisque  	#FFE4C4</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Black  	#000000 #000</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">BlanchedAlmond  	#FFEBCD</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Blue  	#0000FF #00F</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">BlueViolet  	#8A2BE2</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Brown  	#A52A2A</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">BurlyWood  	#DEB887</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">CadetBlue  	#5F9EA0</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Chartreuse  	#7FFF00</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Chocolate  	#D2691E</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Coral  	#FF7F50</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">CornflowerBlue  	#6495ED</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Cornsilk  	#FFF8DC</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Crimson  	#DC143C</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Cyan  	#00FFFF #0FF</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">DarkBlue  	#00008B</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">DarkCyan  	#008B8B</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">DarkGoldenRod  	#B8860B</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">DarkGray  	#A9A9A9</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">DarkGrey  	#A9A9A9</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">DarkGreen  	#006400</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">DarkKhaki  	#BDB76B</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">DarkMagenta  	#8B008B</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">DarkOliveGreen  	#556B2F</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Darkorange  	#FF8C00</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">DarkOrchid  	#9932CC</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">DarkRed  	#8B0000</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">DarkSalmon  	#E9967A</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">DarkSeaGreen  	#8FBC8F</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">DarkSlateBlue  	#483D8B</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">DarkSlateGray  	#2F4F4F</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">DarkSlateGrey  	#2F4F4F</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">DarkTurquoise  	#00CED1</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">DarkViolet  	#9400D3</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">DeepPink  	#FF1493</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">DeepSkyBlue  	#00BFFF</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">DimGray  	#696969</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">DimGrey  	#696969</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">DodgerBlue  	#1E90FF</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">FireBrick  	#B22222</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">FloralWhite  	#FFFAF0</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">ForestGreen  	#228B22</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Fuchsia  	#FF00FF #F0F</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Gainsboro  	#DCDCDC</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">GhostWhite  	#F8F8FF</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Gold  	#FFD700</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">GoldenRod  	#DAA520</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Gray  	#808080</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Grey  	#808080</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Green  	#008000</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">GreenYellow  	#ADFF2F</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">HoneyDew  	#F0FFF0</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">HotPink  	#FF69B4</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">IndianRed   	#CD5C5C</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Indigo   	#4B0082</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Ivory  	#FFFFF0</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Khaki  	#F0E68C</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Lavender  	#E6E6FA</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">LavenderBlush  	#FFF0F5</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">LawnGreen  	#7CFC00</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">LemonChiffon  	#FFFACD</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">LightBlue  	#ADD8E6</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">LightCoral  	#F08080</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">LightCyan  	#E0FFFF</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">LightGoldenRodYellow  	#FAFAD2</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">LightGray  	#D3D3D3</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">LightGrey  	#D3D3D3</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">LightGreen  	#90EE90</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">LightPink  	#FFB6C1</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">LightSalmon  	#FFA07A</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">LightSeaGreen  	#20B2AA</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">LightSkyBlue  	#87CEFA</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">LightSlateGray  	#778899 #789</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">LightSlateGrey  	#778899 #789</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">LightSteelBlue  	#B0C4DE</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">LightYellow  	#FFFFE0</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Lime  	#00FF00</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">LimeGreen  	#32CD32</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Linen  	#FAF0E6</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Magenta  	#FF00FF #F0F</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Maroon  	#800000</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">MediumAquaMarine  	#66CDAA</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">MediumBlue  	#0000CD</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">MediumOrchid  	#BA55D3</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">MediumPurple  	#9370D8</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">MediumSeaGreen  	#3CB371</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">MediumSlateBlue  	#7B68EE</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">MediumSpringGreen  	#00FA9A</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">MediumTurquoise  	#48D1CC</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">MediumVioletRed  	#C71585</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">MidnightBlue  	#191970</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">MintCream  	#F5FFFA</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">MistyRose  	#FFE4E1</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Moccasin  	#FFE4B5</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">NavajoWhite  	#FFDEAD</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Navy  	#000080</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">OldLace  	#FDF5E6</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Olive  	#808000</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">OliveDrab  	#6B8E23</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Orange  	#FFA500</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">OrangeRed  	#FF4500</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Orchid  	#DA70D6</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">PaleGoldenRod  	#EEE8AA</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">PaleGreen  	#98FB98</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">PaleTurquoise  	#AFEEEE</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">PaleVioletRed  	#D87093</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">PapayaWhip  	#FFEFD5</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">PeachPuff  	#FFDAB9</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Peru  	#CD853F</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Pink  	#FFC0CB</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Plum  	#DDA0DD</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">PowderBlue  	#B0E0E6</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Purple  	#800080</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Red  	#FF0000 #F00</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">RosyBrown  	#BC8F8F</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">RoyalBlue  	#4169E1</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">SaddleBrown  	#8B4513</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Salmon  	#FA8072</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">SandyBrown  	#F4A460</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">SeaGreen  	#2E8B57</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">SeaShell  	#FFF5EE</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Sienna  	#A0522D</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Silver  	#C0C0C0</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">SkyBlue  	#87CEEB</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">SlateBlue  	#6A5ACD</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">SlateGray  	#708090</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">SlateGrey  	#708090</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Snow  	#FFFAFA</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">SpringGreen  	#00FF7F</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">SteelBlue  	#4682B4</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Tan  	#D2B48C</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Teal  	#008080</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Thistle  	#D8BFD8</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Tomato  	#FF6347</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Turquoise  	#40E0D0</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Violet  	#EE82EE</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Wheat  	#F5DEB3</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">White  	#FFFFFF #FFF</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">WhiteSmoke  	#F5F5F5</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">Yellow  	#FFFF00</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'html','color',''); return(Cancel(event));" onDblClick="parent.insertScript('html')">YellowGreen  	#9ACD32</td></tr> 
</table></td></tr>
</table>
</div>
<div class=head onclick="toggleHead(htmlProp)"><b>HTML (properties)</b></div>
<div name=htmlProp id=htmlProp style="display:none;">
<table border=0 cellpadding=1 cellspacing=0 width=100%>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','align','align=');     return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">align</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','valign','valign=');       return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">valign</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','valign','valign=baseline'); return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">baseline</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','valign','valign=absbottom');    return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">absbottom</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','valign','valign=bottom');          return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">bottom</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','valign','valign=top');          return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">top</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','valign','valign=middle');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">middle</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','align','align=center');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">center</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','align','align=right');       return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">right</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','align','align=left');     return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">left</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','border','border=');  return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">border</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','border','border=0');  return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">border 0</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','border','border=1');  return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">border 1</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','border','frameborder=');  return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">frameborder</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','border','frameborder=0');  return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">frameborder 0</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','border','frameborder=1');  return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">frameborder 1</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','table','height=');     return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">height</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','table','height=100%');     return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">height 100%</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','table','width=');     return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">width</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','table','width=100%');     return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">width 100%</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','table','cellspacing=');     return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">cellspacing</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','table','cellspacing=0');     return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">cellspacing 0</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','table','cellpadding=');     return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">celpadding</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','table','cellpadding=0');     return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">celpadding 0</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','style','style='+dq('$0'));      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">style</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','form','name=');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">name</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','id','id=');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">id</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','class','class=');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">class</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','form','value='+dq('$0'));      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">value</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','form','type=');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">type</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','form','type=hidden');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">hidden</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','form','type=submit');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">submit</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','form','type=cancel');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">cancel</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','form','type=text');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">text</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','form','type=password');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">password</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','form','type=checkbox');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">checkbox</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','form','type=radio');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">radio</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','form','type=image');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">image</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','form','type=button');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">button</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','image','src='+dq('$0'));      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">src</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','image','alt='+dq(' $0 '));      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">alt</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','title','title='+dq(' $0 '));      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">title</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','form','method=');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">method</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','form','method=GET');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">GET</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','form','method=POST');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">PUT</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','form','action='+dq('$0'));      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">action</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','form','wrap=virtual');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">wrap virtual</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','form','wrap=no');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">wrap no</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','table','nowrap');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">nowrap</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','form','onSubmit='+dq('$0'));      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">onSubmit</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','form','onChange='+dq('$0'));      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">onChange</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','gen','onFocus='+dq('$0'));      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">onFocus</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','gen','onBlur='+dq('$0'));      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">onBlur</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','gen','onClick='+dq('$0'));      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">onClick</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','gen','onDblClick='+dq('$0'));      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">onDblClick</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','gen','onMouseDown='+dq('$0'));      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">onMouseDown</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','gen','onMouseUp='+dq('$0'));      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">onMouseUp</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','gen','onContext='+dq('$0'));      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">onContext</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','gen','onMouseOver='+dq('$0'));      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">onMouseOver</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','gen','onMouseOut='+dq('$0'));      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">onMouseOut</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','gen','onMouseMove='+dq('$0'));      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">onMouseMove</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','gen','onDrag='+dq('$0'));      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">onDrag</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','gen','onBeforeDrag='+dq('$0'));      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">onBeforeDrag</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','gen','onAfterDrag='+dq('$0'));      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">onAfterDrag</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','page','onLoad='+dq('$0'));      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">onLoad</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','page','onUnload='+dq('$0'));      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">onUnload</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','page','onSelect='+dq('$0'));      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">onSelect</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','image','onError='+dq('$0'));      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">onError</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','anchor','href='+dq('$0'));      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">href</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','anchor','target=');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">target</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','anchor','target=_blank');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">target blank</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','anchor','target=_parent');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">target parent</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','anchor','target=_self');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">target self</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','font','face=');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">face</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','font','size=');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">size</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','gen','color=');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">color</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','gen','bgcolor=');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">bgcolor</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','page','background='+dq('$0'));      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">background</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','page','bgsound='+dq('$0'));      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">bgsound</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','image','usemap=#');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">bgsound</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','image','shape=');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">bgsound</td></tr>
<tr><td class= width=100% onClick="clickFunction(this,'htmlprop','image','coords='+dq('$0')+'');      return(Cancel(event));" onDblClick="parent.insertScript('htmlprop')">bgsound</td></tr>
</table>
</div>
<?

  }if(in_array('style',$lib)){

?>
<div class=head onclick="toggleHead(cssstyle)"><b>CSS Style</b></div>
<div name=cssstyle id=cssstyle style="display:none;">
<table border=0 cellpadding=1 cellspacing=0 width=100%>
<tr><td>count(ARRAY)</td></tr>
<tr><td>strstr(TEXT,STRING)</td></tr>
<tr><td>str_replace(FIND,TEXT,STRING)</td></tr>
<tr><td>for(I;I<=>?;I++){}</td></tr>
<tr><td>foreach(ARRAY as ITEM){}</td></tr>
</table>
</div>
<?

  }if(in_array('javascript',$lib)){

?>
<div class=head onclick="toggleHead(javascript)"><b>JavaScript (all)</b></div>
<div name=javascript id=javascript style="display:none;">
<table border=0 cellpadding=1 cellspacing=0 width=100%>
<tr><td>abs</td></tr>
<tr><td>Array</td></tr>
<tr><td>clearTimeout</td></tr>
<tr><td>eval</td></tr>
<tr><td>for</td></tr>
<tr><td>indexOf</td></tr>
<tr><td>lastIndexOf</td></tr>
<tr><td>legnth</td></tr>
<tr><td>location</td></tr>
<tr><td>location.hash</td></tr>
<tr><td>location.host</td></tr>
<tr><td>location.href</td></tr>
<tr><td>location.search</td></tr>
<tr><td>location.protocol</td></tr>
<tr><td>Math.abs</td></tr>
<tr><td>Math.cos</td></tr>
<tr><td>Math.max</td></tr>
<tr><td>Math.min</td></tr>
<tr><td>Math.mod</td></tr>
<tr><td>Math.sin</td></tr>
<tr><td>Math.tan</td></tr>
<tr><td>navigator</td></tr>
<tr><td>navigator.agent</td></tr>
<tr><td>navigator.os</td></tr>
<tr><td>navigator.ver</td></tr>
<tr><td>opener</td></tr>
<tr><td>open</td></tr>
<tr><td>close</td></tr>
<tr><td>clear</td></tr>
<tr><td>write</td></tr>
<tr><td>writeln</td></tr>
<tr><td>replace</td></tr>
<tr><td>setTimeout</td></tr>
<tr><td>setInterval</td></tr>
<tr><td>wait</td></tr>
<tr><td>with</td></tr>
</table>
</div>
<div class=head onclick="toggleHead(jsSyntax)"><b>JavaScript (syntax)</b></div>
<div name=jsSyntax id=jsSyntax style="display:none;">
<table border=0 cellpadding=1 cellspacing=0 width=100%>
<tr><td>VAR variable name</td></tr>
<tr><td>VAL variable value</td></tr>
<tr><td>EXP expression</td></tr>
<tr><td>STR string value</td></tr>
<tr><td>INT integer value</td></tr>
<tr><td>ARR array</td></tr>
<tr><td>OBJ object</td></tr>
<tr><td><hr></td></tr>
<tr><td>var VAR</td></tr>
<tr><td>int VAR</td></tr>
<tr><td>VAR++</td></tr>
<tr><td>VAR--</td></tr>
<tr><td>new Array(MAX)</td></tr>
<tr><td>new Array(VAL0,VAL1,..,VALn)</td></tr>
<tr><td>for(I;I<==>?;I++){}</td></tr>
<tr><td>if(EXPRESSION){TRUE}</td></tr>
<tr><td>if(EXPRESSION){TRUE}else{FALSE}</td></tr>
<tr><td>EXPRESSION ? TRUE : FALSE</td></tr>
<tr><td>STRING.indexOf(FIND,START)</td></tr>
<tr><td>STRING.lastIndexOf(FIND,START)</td></tr>
<tr><td>ARRAY.legnth</td></tr>
<tr><td>STRING.legnth</td></tr>
<tr><td>STRING.replace(FIND,REPLACE)</td></tr>
<tr><td>STRING.substring(FIRST,LAST)</td></tr>
<tr><td>STRING.Substring(FIRST,LENGTH)</td></tr>
<tr><td>STRING.split(FIND,COUNT)</td></tr>
<tr><td>ARRAY.join(STRING)</td></tr>
<tr><td>while(EXPRESSION){TRUE}</td></tr>
<tr><td>with(ITEM in OBJECT){}</td></tr>
</table>
</div>
<div class=head onclick="toggleHead(jsDom)"><b>JavaScript (dom)</b></div>
<div name=jsDom id=jsDom style="display:none;">
<table border=0 cellpadding=1 cellspacing=0 width=100%>
<tr><td class= width=100% onClick="toggleLib(this,jsDom_body)">body
<table name=jsDom_body id=jsDom_body class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
<tr><td>new Array(MAX)</td></tr>
<tr><td>new Array(VAL0,VAL1,..,VALn)</td></tr>
<tr><td>for(I;I<=>?;I++){}</td></tr>
<tr><td>indexOf(FIND,START)</td></tr>
<tr><td>legnth</td></tr>
<tr><td>replace(FIND,REPLACE)</td></tr>
<tr><td>with(ITEM in OBJECT){}</td></tr>
</table></td></tr>
<tr><td class= width=100% onClick="toggleLib(this,jsDom_style)">style
<table name=jsDom_style id=jsDom_style class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
<tr><td>new Array(MAX)</td></tr>
<tr><td>new Array(VAL0,VAL1,..,VALn)</td></tr>
<tr><td>for(I;I<=>?;I++){}</td></tr>
<tr><td>indexOf(FIND,START)</td></tr>
<tr><td>legnth</td></tr>
<tr><td>replace(FIND,REPLACE)</td></tr>
<tr><td>with(ITEM in OBJECT){}</td></tr>
</table></td></tr>
</table>
</div>
<div class=head onclick="toggleHead(jsFun)"><b>JavaScript (functions)</b></div>
<div name=jsFun id=jsFun style="display:none;">
<table border=0 cellpadding=1 cellspacing=0 width=100%>
<tr><td class= width=100% onClick="toggleLib(this,jsFun_standard)">standard
<table name=jsFun_standard id=jsFun_standard class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
<tr><td>I++</td></tr>
<tr><td>I--</td></tr>
<tr><td>new Array(NUM)</td></tr>
<tr><td>new Array(VAL0,VAL1,..,VALn)</td></tr>
<tr><td>for(I;I<=>?;I++){I<=>TRUE}</td></tr>
<tr><td>if(EXP){TRUE}</td></tr>
<tr><td>if(EXP){TRUE}else{FALSE}</td></tr>
<tr><td>switch(VAR/EXP){case VAL: break;}</tr>
<tr><td>switch(VAR/EXP){default: break;}</tr>
<tr><td>while(EXP){TRUE}</tr>
<tr><td>with(ITEM in OBJECT){}</td></tr>
<tr><td>break</tr>
<tr><td>continue</tr>
<tr><td>eval(JAVASCRIPT)</tr>
<tr><td>function {}</tr>
<tr><td>function NAME(){}</tr>
<tr><td>function NAME(VARn){}</tr>
<tr><td>parseInt(STRING/NUM)</tr>
<tr><td>parseFloat(STRING/NUM)</tr>
<tr><td>String(STRING/EXP)</tr>
<tr><td>VAR.tostring()</tr>
</table></td></tr>
<tr><td class= width=100% onClick="toggleLib(this,jsFun_array)">array
<table name=jsFun_array id=jsFun_array class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
<tr><td>length</td></tr>
<tr><td>STRING.split(FIND,COUNT)</td></tr>
<tr><td>join(STRING)</td></tr>
</table></td></tr>
<tr><td class= width=100% onClick="toggleLib(this,jsFun_string)">string
<table name=jsFun_string id=jsFun_string class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
<tr><td>indexOf(FIND,START)</td></tr>
<tr><td>lastIndexOf(FIND,START)</td></tr>
<tr><td>length</td></tr>
<tr><td>replace(FIND,REPLACE)</td></tr>
<tr><td>substring(FIRST,LAST)</td></tr>
<tr><td>Substring(FIRST,LENGTH)</td></tr>
<tr><td>split(FIND,COUNT)</td></tr>
<tr><td>ARRAY.join(STRING)</td></tr>
</table></td></tr>
</table>
</div>
<div class=head onclick="toggleHead(jsProp)"><b>JavaScript (properties)</b></div>
<div name=jsProp id=jsProp style="display:none;">
<table border=0 cellpadding=1 cellspacing=0 width=100%>
<tr><td class= width=100% onClick="toggleLib(this,jsProp_standard)">standard
<table name=jsProp_standard id=jsProp_standard class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
<tr><td>new Array(MAX)</td></tr>
<tr><td>new Array(VAL0,VAL1,..,VALn)</td></tr>
<tr><td>for(I;I<=>?;I++){}</td></tr>
<tr><td>indexOf(FIND,START)</td></tr>
<tr><td>legnth</td></tr>
<tr><td>replace(FIND,REPLACE)</td></tr>
<tr><td>with(ITEM in OBJECT){}</td></tr>
</table></td></tr>
<tr><td class= width=100% onClick="toggleLib(this,jsFun_objects)">document
<table name=jsFun_objects id=jsFun_objects class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
<tr><td>new Array(MAX)</td></tr>
<tr><td>new Array(VAL0,VAL1,..,VALn)</td></tr>
<tr><td>for(I;I<=>?;I++){}</td></tr>
<tr><td>indexOf(FIND,START)</td></tr>
<tr><td>legnth</td></tr>
<tr><td>replace(FIND,REPLACE)</td></tr>
<tr><td>with(ITEM in OBJECT){}</td></tr>
</table></td></tr>
</table>
</div>
<div class=head onclick="toggleHead(jsObj)"><b>JavaScript (objects)</b></div>
<div name=jsObj id=jsObj style="display:none;">
<table border=0 cellpadding=1 cellspacing=0 width=100%>
<tr><td class= width=100% onClick="toggleLib(this,jsObj_generic)">generic
<table name=jsObj_generic id=jsObj_generic class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
<tr><td>appendAfter(NEW,OBJECT)</td></tr>
<tr><td>appendBefore(NEW,OBJECT)</td></tr>
<tr><td>className</td></tr>
<tr><td>tagName</td></tr>
<tr><td>id</td></tr>
<tr><td>hasAttribute(STRING)</td></tr>
<tr><td>getAttribute(STRING)</td></tr>
<tr><td>setAttribute(STRING,VALUE)</td></tr>
<tr><td>hasChildren()</td></tr>
<tr><td>firstChild</td></tr>
<tr><td>children</td></tr>
<tr><td>lastChild</td></tr>
<tr><td>name</td></tr>
<tr><td>type</td></tr>
<tr><td>value</td></tr>
<tr><td>parent</td></tr>
<tr><td>hasSyblings()</td></tr>
<tr><td>nextSybling</td></tr>
<tr><td>prevSybling</td></tr>
<tr><td>scrollHeight</td></tr>
<tr><td>scrollWidth</td></tr>
<tr><td>scrollTop</td></tr>
<tr><td>scrollLeft</td></tr>
<tr><td>scrollIntoView()</td></tr>
<tr><td>onclick</td></tr>
<tr><td>ondblclick</td></tr>
<tr><td>oncontext</td></tr>
<tr><td>onmousedown</td></tr>
<tr><td>onmouseup</td></tr>
<tr><td>onmouseover</td></tr>
<tr><td>onmouseout</td></tr>
<tr><td>onmousemove</td></tr>
<tr><td>ondrag</td></tr>
<tr><td>onbeforedrag</td></tr>
<tr><td>onafterdrag</td></tr>
<tr><td>onfocus</td></tr>
<tr><td>onblur</td></tr>
<tr><td>onchange</td></tr>
<tr><td></td></tr>
</table></td></tr>
<tr><td class= width=100% onClick="toggleLib(this,jsObj_Date)">Date
<table name=jsObj_Date id=jsObj_Date class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
<tr><td>time</td></tr>
<tr><td>now</td></tr>
<tr><td>month</td></tr>
<tr><td>year</td></tr>
</table></td></tr>
<tr><td class= width=100% onClick="toggleLib(this,jsObj_document)">document
<table name=jsObj_document id=jsObj_document class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
<tr><td>all (IE only)</td></tr>
<tr><td>anchors</td></tr>
<tr><td>body</td></tr>
<tr><td>cookies</td></tr>
<tr><td>images</td></tr>
<tr><td>forms</td></tr>
<tr><td>frames</td></tr>
</table></td></tr>
<tr><td class= width=100% onClick="toggleLib(this,jsObj_forms)">forms
<table name=jsObj_forms id=jsObj_forms class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
<tr><td>items</td></tr>
<tr><td>length</td></tr>
</table></td></tr>
<tr><td class= width=100% onClick="toggleLib(this,jsObj_form)">form
<table name=jsObj_form id=jsObj_form class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
<tr><td>action</td></tr>
<tr><td>method</td></tr>
<tr><td>target</td></tr>
<tr><td>elements</td></tr>
<tr><td>submit()</td></tr>
<tr><td>reset()</td></tr>
<tr><td>onsubmit</td></tr>
<tr><td>form</td></tr>
</table></td></tr>
<tr><td class= width=100% onClick="toggleLib(this,jsObj_frames)">frames
<table name=jsObj_frames id=jsObj_frames class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
<tr><td>length</td></tr>
</table></td></tr>
<tr><td class= width=100% onClick="toggleLib(this,jsObj_frame)">frame
<table name=jsObj_frame id=jsObj_frame class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
<tr><td>src</td></tr>
<tr><td>parent</td></tr>
</table></td></tr>
<tr><td class= width=100% onClick="toggleLib(this,jsObj_Math)">Math
<table name=jsObj_Math id=jsObj_Math class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
<tr><td>abs</td></tr>
<tr><td>cos</td></tr>
<tr><td>max</td></tr>
<tr><td>min</td></tr>
<tr><td>mod</td></tr>
<tr><td>sin</td></tr>
<tr><td>tan</td></tr>
</table></td></tr>
<tr><td class= width=100% onClick="toggleLib(this,jsObj_style)">style
<table name=jsObj_style id=jsObj_style class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
<tr><td>background</td></tr>
<tr><td>backgroundRepeat</td></tr>
<tr><td>backgroundImage</td></tr>
<tr><td>backgroundOffsetX</td></tr>
<tr><td>backgroundOffsetY</td></tr>
<tr><td>backgroundRepeat</td></tr>
<tr><td>border</td></tr>
<tr><td>borderColor</td></tr>
<tr><td>borderStyle</td></tr>
<tr><td>borderWidth</td></tr>
<tr><td>borderBottom</td></tr>
<tr><td>borderLeft</td></tr>
<tr><td>borderRight</td></tr>
<tr><td>borderTop</td></tr>
<tr><td>cursor</td></tr>
<tr><td>margin</td></tr>
<tr><td>marginBottom</td></tr>
<tr><td>marginLeft</td></tr>
<tr><td>marginRight</td></tr>
<tr><td>marginTop</td></tr>
<tr><td>padding</td></tr>
<tr><td>paddingBottom</td></tr>
<tr><td>paddingLeft</td></tr>
<tr><td>paddingRight</td></tr>
<tr><td>paddingTop</td></tr>
<tr><td>color</td></tr>
<tr><td>display</td></tr>
<tr><td>font</td></tr>
<tr><td>fontColor</td></tr>
<tr><td>fontFamily</td></tr>
<tr><td>fontSize</td></tr>
<tr><td>fontStyle</td></tr>
<tr><td>fontWeight</td></tr>
<tr><td>float</td></tr>
<tr><td>lineheight</td></tr>
<tr><td>position</td></tr>
<tr><td>ruby</td></tr>
<tr><td>textAlign</td></tr>
<tr><td>textWrap</td></tr>
<tr><td>verticalAlign</td></tr>
<tr><td>visibility</td></tr>
<tr><td>zIndex</td></tr>
<tr><td>top</td></tr>
<tr><td>bottom</td></tr>
<tr><td>left</td></tr>
<tr><td>right</td></tr>
<tr><td>height</td></tr>
<tr><td>width</td></tr>
</table></td></tr>
<tr><td class= width=100% onClick="toggleLib(this,jsObj_window)">window
<table name=jsObj_window id=jsObj_window class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style="display:none;">
<tr><td>location</td></tr>
<tr><td>location.hash</td></tr>
<tr><td>location.host</td></tr>
<tr><td>location.href</td></tr>
<tr><td>location.search</td></tr>
<tr><td>location.protocol</td></tr>
<tr><td>navigator</td></tr>
<tr><td>navigator.agent</td></tr>
<tr><td>navigator.os</td></tr>
<tr><td>navigator.ver</td></tr>
<tr><td>opener</td></tr>
</table></td></tr>
</table>
</div>
<?

  }if(in_array('vbscript',$lib)){

?>
<div class=head onclick="toggleHead(vbscript)"><b>vbScript</b></div>
<div name=vbscript id=vbscript style="display:none;">
<table border=0 cellpadding=1 cellspacing=0 width=100%>
<tr><td>VAR variable name</td></tr>
<tr><td>VAL variable value</td></tr>
<tr><td>EXP expression</td></tr>
<tr><td>STR string value</td></tr>
<tr><td>INT integer value</td></tr>
<tr><td>ARR array</td></tr>
<tr><td>OBJ object</td></tr>
<tr><td><hr></td></tr>
<tr><td>if(EXP)</td></tr>
<tr><td>if(EXP)else</td></tr>
<tr><td>left(STR,COUNT)</td></tr>
<tr><td>right(STR,COUNT)</td></tr>
<tr><td>mid(STR,START,COUNT)</td></tr>
<tr><td>instr(STR,FIND,START)</td></tr>
<tr><td>replace(FIND,TEXT,STR)</td></tr>
<tr><td>for(I=NUM) next</td></tr>
<tr><td>while(EXP) wend</td></tr>
</table>
</div>
<?

  }if(in_array('php',$lib)){

?>
<div class=head onclick="toggleHead(phpAll)"><b>PHP (all functions)</b></div>
<div name=phpAll id=phpAll style="display:none;">
<table border=0 cellpadding=1 cellspacing=0 width=100%>
</table>
</div>
<div class=head onclick="toggleHead(phpServer)"><b>PHP (<?= phpversion() ?> on server)</b></div>
<div name=phpServer id=phpServer style="display:none;">
<table border=0 cellpadding=1 cellspacing=0 width=100%>
<?
  $xtns = get_loaded_extensions();
  if(is_array($xtns)){
    natcasesort($xtns); 	#default is sort by name
    $i=0;
    foreach($xtns as $xtn){
      $fns = get_extension_funcs($xtn);
      $xtnn = str_replace(' ','_',$xtn);
      if($fns){
        print "<tr><td class= width=100% onClick=\"toggleLib(this,phpServer_$xtnn)\">$xtn ".(phpversion($xtn) ? "(".(0+phpversion($xtn)).")":"");
        print "<table name=phpServer_$xtnn id=phpServer_$xtnn class=fnst border=0 cellpadding=1 cellspacing=0 width=100% style=\"display:none;\">\n";       
        foreach($fns as $fn){
          print "<tr><td class= width=100% onClick=\"clickFunction(this,'php','$xtn','$fn'); return(Cancel(event));\" onDblClick=\"parent.insertScript('php')\">$fn</td></tr>\n";
        }
        print "</table></td></tr>\n";
      }
      $i++;
    }
  }

?>
</table>
</div>
<div class=head onclick="toggleHead(phpExt)"><b>PHP (all extensions)</b></div>
<div name=phpExt id=phpExt style="display:none;">
<table border=0 cellpadding=1 cellspacing=0 width=100%>
</table>
</div>
<div class=head onclick="toggleHead(phpPecl)"><b>PHP (PECL extensions)</b></div>
<div name=phpPecl id=phpPecl style="display:none;">
<table border=0 cellpadding=1 cellspacing=0 width=100%>
</table>
</div>
<?

  }elseif($lib=='asp'){

?>
<div class=head onclick="toggleHead(asp)"><b>ASP</b></div>
<div name=asp id=asp style="display:none;">
<table border=0 cellpadding=1 cellspacing=0 width=100%>
<tr><td>count(ARRAY)</td></tr>
<tr><td>strstr(TEXT,STRING)</td></tr>
<tr><td>str_replace(FIND,TEXT,STRING)</td></tr>
<tr><td>for(I;I<=>?;I++){}</td></tr>
<tr><td>foreach(ARRAY as ITEM){}</td></tr>
</table>
</div>
<? 
  }

print "</body></html>";
www_page_close();