<?php
# Atari DevEdit skin - 1.0b
# Edited: 09/09/2008
# _new_ prototype "Atari" desktop/skin
#


#------------- NEW FUNCTIONS ------------
# v5 prototype skin with new functions
#
# www_page_open()  - start data output encoding to browser
# www_page_close() - end data output encoding, apply compression
# folderin(dir)    - return "end_folder in end_folder-1" from full path
#

# 04/09/2008 - added to funtions.php


# standalone/included/attached patch (re PHP Navigator)
$usephpnav = true;
$DE_rootdir = "../"; // PHP Navigator directory (for includes & skins) otherwise "./"
if($usephpnav){
  if(!file_exists($DE_rootdir."atari.php")){
    $usephpnav = false;
    $DE_rootdir = "./"; // needs functions in DevEdit
  }
}

chdir($DE_rootdir); 


 $dir=urldecode($_REQUEST['dir']);
 $file=urldecode($_REQUEST['file']);
 
include_once("functions.php");
include_once("config.php");

www_page_open();
authenticate();

getcookies();

include_once("skin.php"); // setup skins

include_once("config_patch.php"); // extra data required for "browse this"


$body = '<body id=DesktopBody topmargin=0 leftmargin=0 marginheight=0 marginwidth=0 onload="startUp();" onBeforeUnload="storeWindows();" onClick="dcheck(event);" onDragStart="return(false);" onSelectStart="event.cancelBubble=true; return(false);" onDrag="doMover(event);" onMouseMove="doMover(event);" onDragEnd="doEnd(event);" onMouseUp="doEnd(event);" style="width:100%; height:100%; background-color:#00FF00;">';
if (substr_count($_SERVER['HTTP_USER_AGENT'],"Linux")>0){
  $brows = '2';
  $browsp = '0';
  $font_large = "Fixed";    // 16pt
  $font_size = "19px";
  $font_weight = "bold";  // Doesn't need it.. (just like windows..)
  $font_small = "Fixed"; // 8pt
  $font_size_small = "11px";
  $font_fix = "line-height:20px;";
  if (substr_count($_SERVER['HTTP_USER_AGENT'],"Opera")>0) {
//    $body = '<body topmargin=0 leftmargin=0 marginheight=0 marginwidth=0 onload="startUp();" onunload="storeWindows();" style="width:100%; height:100%; background-color:#00FF00;">
//<div id=DesktopBody onClick="dcheck(event);" onDragStart="return(false);" onSelectStart="event.cancelBubble=true; return(false);" onDrag="doMover(event);" onMouseMove="doMover(event);" onDragEnd="adoEnd(event);" onMouseUp="adoEnd(event);" style="width:100%; height:100%; background-color:#00FF00; z-index:-65530;"></div>';
    $brows = '-3';
    $browsp = '2';
    $font_large = "Fixed";
    $font_size = "20px";
    $font_weight = "normal";
    $font_small = "Clean";
    $font_size_small = "11px";
    $font_fix = "";
  }
  if (substr_count($_SERVER['HTTP_USER_AGENT'],"Firefox")>0) {
    $ed_fix = "overflow:hidden;";
    $top = '';
    $brows = '0';
    $font_large = "Fixed";
    $font_size = "20px";
    $font_weight = "normal";
    $font_small = "Clean";
    $font_size_small = "11px";
    $font_fix = "";
  }
}elseif (substr_count($_SERVER['HTTP_USER_AGENT'],"Mac OS X")>0){
  $ed_fix = "";
  $font_large = "'Andale Mono'";
  $font_weight = "normal";
  $font_size = "17px";
  $font_small = "'Andale Mono'";
  $font_size_small = "10px";
  $font_fix = "line-height:20px;";
  if (substr_count($_SERVER['HTTP_USER_AGENT'],"Firefox")>0) {
    $ed_fix = "overflow:hidden;";
  }
}else{
  $ed_fix = "overflow:hidden;";
  $font_large = "Terminal";
  $font_weight = "normal";
  $font_size = "20px";
  $font_small = "Terminal";
  $font_size_small = "11px";
  if (substr_count($_SERVER['HTTP_USER_AGENT'],"Windows")>0){
    $menufix = "fixIEmenus();";
    if (substr_count($_SERVER['HTTP_USER_AGENT'],"Firefox")>0) {
      $menufix = "";
      $font_large = "'MS Gothic'";
      $font_size = "18px";
      $font_weight = "bold";
      $font_small = "'MS Gothic'";
      $font_size_small = "12px";
//      $font_large = "'Lucida Console'";
//      $font_size = "17px";
//      $font_small = "'Lucida Console'";
//      $font_size_small = "11px";
// Hattensweiger
// Impact
// Lucida Sans Typewriter
     $font_fix = "line-height:20px;";
    }
    if (substr_count($_SERVER['HTTP_USER_AGENT'],"MSIE 7")>0) {
$top = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
//      $font_large = "Terminal";
//      $font_weight = "bold";
//      $font_size = "20px";
//      $font_small = "'Small Fonts'";
//      $font_size_small = "9px";
      $font_large = "'MS Gothic'";
      $font_size = "18px";
      $font_weight = "bold";
      $font_small = "'MS Gothic'";
      $font_size_small = "12px";
      $font_fix = "line-height:20px;";
    }
  }
}
// above to be deleted

$top = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';

if (substr_count($_SERVER['HTTP_USER_AGENT'],"Windows")>0){
  $ed_fix = "overflow:hidden;";
  $font_large = "'MS Gothic'";
  $font_size = "18px";
  $font_weight = "bold";
  $font_small = "'MS Gothic'";
  $font_size_small = "12px";
  $font_fix = "line-height:20px;";
}else{
  $ed_fix = "overflow:hidden;";
  $font_large = "'Andale Mono'";
  $font_weight = "normal";
  $font_size = "17px";
  $font_small = "'Andale Mono'";
  $font_size_small = "10px";
  $font_fix = "line-height:20px;";
}

$iconHeight = (2*(0+$font_size_small))+$icn_size[1]+5;

function atarieditfile($file)
{
  global $dir, $realdir, $no_icn, $icn_size, $use_layout;
  $skin = $GLOBALS['skin'];
  $gi   = $GLOBALS['groupimgs'];
  if(is_array($icn_size)){
    $w = $icn_size[0];
    $h = $icn_size[1];
  }else{
    $w = '32';
    $h = $w;
  }
  if(end(explode("/",$_SERVER['PHP_SELF']))=="details.php"){
    $w = '16';
    $h = $w;
  }

  $scale = array(" Bytes"," KB"," MB"," GB");
  $stat = @stat($dir."/".$file);

  $size = $stat[7];
  for($s=0;$size>1024&&$s<4;$s++) $size=$size/1024;	//Calculate in Bytes,KB,MB etc.
  if($s>0) $size= number_format($size,2).$scale[$s];
  else $size= number_format($size).$scale[$s];
  $spec=filespec($file);

  $filename_t = htmlentities($file,ENT_QUOTES);
  $filename_e = urlencode($file);
  $dir_e = urlencode($dir);
  $filename = wordwrap($filename_t, 15, "<br>\n",1);
  $ts = time()-strtotime("4 April 2008");
  $fn = preg_replace("/[[:space:][:punct:]]/","_",$file);
  $aid = "f".$ts.$fn;
  $iid = "i".$ts.$fn;

	$ficon = groupicon($file);
	$img = "{$DE_rootdir}skins/{$skin}$ficon";
	$imgsel = "{$DE_rootdir}skins/{$skin}".str_replace($gi,"_sel{$gi}",$ficon);
	if (!file_exists($realdir.$img)) $img = "./skins/$ficon";
	if (strstr($ficon,"thumb")==$ficon) $img = $ficon."&border=false";
	if (!file_exists($realdir.$imgsel)) $imgsel = $img;
	return "<center><a class=icon><img class=ficon aid=\"$aid\"
	    src=\"$img\" width=$w height=$h name=\"$iid\" id=\"$iid\" 
	    onMouseDown=\"clearMenus(); atariIcon(this,'i');\" title=\"$filename_t\"
	    alt=\"File: $filename_t<br>Size: $size<br>
	    Permissions: ".decoct(@fileperms($dir."/".$file)%01000)."<br>
	    Modified: ".date('d-m-y, G:i', $stat[9])."<br>
	    Accessed: ".date('d-m-y, G:i', $stat[8])."\" spec='$spec' 
	    onDblClick=\"if(DesktopDialog){return(false);} DevEditor0.style.display='inline'; atariClear(); return(false);\"
	    onError=\"this.src='../skins/atari/file.gif';\" atariimg='$img' atarisel='$imgsel'></a><br><a 
	    class=name name=\"$aid\" id=\"$aid\" aid=\"$iid\" href='javascript:void(0)' onClick=\"if(DesktopDialog){return(false);}\" 
	    onDblClick=\"if(DesktopDialog){return(false);} DevEditor0.style.display='inline'; atariClear(); return(false);\"
	    onMouseDown=\"clearMenus(); atariIcon(this,'f');\">$filename</a>";
}

#-------- ATARI WINDOW COOKIES ---------

$windows = array();

function loadWindows($desktop){
  global $windows, $winDefaults;
//  if(isset($_COOKIE['windows'.$desktop]) && $_COOKIE['windows'.$desktop]!="")
//    $xWindows = explode("|",$_COOKIE['windows'.$desktop]);
//  else
    $xWindows = explode("|",$winDefaults);
  if(count($xWindows)<=1) return;
  $xEval = ""; $c="";
  for($i=1;$i<count($xWindows);$i++){
    $xW = explode(",",$xWindows[$i]);
    $xEval .= $c."'".$xW[6]."' => explode(',',\$xWindows[".$i."])";
    $c = ", ";
  }
//print "\$windows=array(".$xEval.");";
  eval("\$windows=array(".$xEval.");");
}

function browseHere($xPath){
  global $server_root, $browser_root;
  for($i=0;$i<count($server_root);$i++){
    if(substr_count($xPath,$server_root[$i])>0){ return array(str_replace('/','\/',$server_root[$i]),$browser_root[$i]); }
  }
  return array('undefined','undefined');
}

#----------------------------------------


if (isset($_POST['save'])) save($file);

if(filesize("$dir/$file")>$max_edit_size)
  print "File size exceeds the limit of $max_edit_size bytes<br>Have the Site Admin edit config.php to customize this";
else
  $content = htmlentities(file_get_contents("$dir/$file"));

$title = "$file in ".end(explode("/",$dir))." - DevEdit - PHP Navigator";
print <<<HTML
{$top}
<html>
<head>
<link rel='icon' href='./DevEdit.gif' type='image/x-icon' />
<style type=text/css >
body{   
  height: 100%;
  margin: 0px;
  padding: 0px;
  font-family: {$font_large};
  font-size: {$font_size};
  font-weight: {$font_weight};
  background: transparent;
  
}

a, a:link, a:active, a:visited {
  text-decoration: none;
}
.name{
  font-family: {$font_small};
  font-size: {$font_size_small};
  font-weight: normal;
  color: black;
  background-color: white;
  text-decoration: none;
  padding: 0px;
  padding-top: 1px;
  padding-left: 4px;
  padding-right: 4px;
  border: none;
}
.nameSel{
  font-family: {$font_small};
  font-size: {$font_size_small};
  font-weight: normal;
  color: white;
  background-color: black;
  text-decoration: none;
  padding: 0px;
  padding-top: 1px;
  padding-left: 4px;
  padding-right: 4px;
  border: none;
}
.ficon{
border: 1px solid transparent;
}
.ficonSel{
border: 1px solid black;
}
.context{
  font-family: {$font_large};
  font-size: {$font_size};
  font-weight: {$font_weight};
  border: 3px double black;
  background-color: white;
  line-height: 18px;
  cursor: default;
  z-index: 65535;
  visibility: hidden;
  position: absolute;
}
.contbar{
  background-color: white;
}
.contitem{
  display: block;
  color: black;
  background-color: white;
  text-decoration: none;
}
.contitem:hover {
  color: white;
  background-color: black;
}

form {
  border: 0px;
  margin: 0px;
  padding: 0px;
}

.iconFile {
  float: left;
  height: {$iconHeight}px;
}

.PropHead {
  font-family: {$font_small};
  font-size: {$font_size_small};
  color: white;
  background-color: gray;
  width: 100%;
  border: none;
  border-top: 1px solid black;
  border-bottom: 1px solid black;
  padding-left: 2px;
  margin-top: 1px;
}

.PropInfo {
  font-family: {$font_small};
  font-size: {$font_size_small};
  color: black;
  background-color: white;
  width: 100%;
  border: none;
  border-bottom: 1px solid black;
  padding-left: 4px;
  padding-bottom: {$font_size_small};
}

.MenuBlank {
  font-family: {$font_large};
  font-size: {$font_size};
  font-weight: {$font_weight};
  {$font_fix}
  color: gray;
  cursor: default;
}

a.MenuItem, a.ButtonItem {
	font-family: {$font_large};
	font-size: {$font_size};
	font-weight: {$font_weight};
	{$font_fix}
	color: black;
	text-decoration: none;
	cursor: default;
}
a.MenuItem:hover {
	color: white;
	background: black;
}
a.MenuItem:active {
	color: white;
	background: black;
}

.ScrollV{
  position:relative;
  top:0px;
  left:0px;
  width:100%;
  height:0px;
  border:none;
  border-top:1px solid black;
  border-bottom:1px solid black;
  font-size:0px;
}

.ScrollH{
  position:relative;
  top:0px;
  left:0px;
  width:0px;
  height:100%;
  border:none;
  border-left:1px solid black;
  border-right:1px solid black;
  font-size:0px;
}

.Scroll{
  border:none;
}

.big {
  font-weight: {$font_weight};
  {$font_fix}
}

.area{
  font-weight:normal;
}
</style>
<style type=text/css >
.win_part_close{
  position:absolute;
  border:1px solid black;
  top:0px;
  left:0px;
  height:19px;
  width:19px;
  background-color:white;
  background-image:url("{$DE_rootdir}skins/atari/images/CLOSE.GIF");
  background-repeat:no-repeat;
  background-position:6px 4px;
}
.win_part_title{
  position:absolute;
  background-color:white;
  border:1px solid black;
  top:0px;
  left:20px;
  height:19px;
  right:20px;
  overflow:hidden;
  padding: 0 10px 0 10px;
}
.win_part_max{
  position:absolute;
  border:1px solid black;
  top:0px;
  right:0px;
  height:19px;
  width:19px;
  background-color:white;
  background-image:url("{$DE_rootdir}skins/atari/images/MAX.GIF");
  background-repeat:no-repeat;
  background-position:6px 4px;
}
.win_part_option{
  position:absolute;
  background-color:white;
  border:1px solid black;
  top:20px;
  left:0px;
  height:19px;
  right:0px;
  overflow:hidden;
}
.win_part_body{
  position:absolute;
  background-color:white;
  border:1px solid black;
  top:40px;
  left:0px;
  bottom:20px;
  right:20px;
  overflow:hidden;
}
.win_part_scrollV{
  position:absolute;
  background-color:white;
  border:1px solid black;
  top:40px;
  right:0px;
  bottom:20px;
  width:19px;
}
.win_part_scrollV_slide{
  position:absolute;
  background-color:white;
  border-top:1px solid black;
  border-bottom:1px solid black;
  top:20px;
  right:0px;
  bottom:20px;
  width:19px;
}
.win_part_scrollV_up{
  position:absolute;
  top:0px;
  left:0px;
  height:19px;
  width:19px;
  background-color:white;
  background-image:url("{$DE_rootdir}skins/atari/images/UP.GIF");
  background-repeat:no-repeat;
  background-position:5px 4px;
}
.win_part_scrollV_down{
  position:absolute;
  left:0px;
  bottom:0px;
  height:19px;
  width:19px;
  background-color:white;
  background-image:url("{$DE_rootdir}skins/atari/images/DOWN.GIF");
  background-repeat:no-repeat;
  background-position:5px 4px;
}
.win_part_scrollH{
  position:absolute;
  background-color:white;
  border:1px solid black;
  left:0px;
  bottom:0px;
  right:20px;
  height:19px;
}
.win_part_scrollH_slide{
  position:absolute;
  background-color:white;
  border:none;
  border-left:1px solid black;
  border-right:1px solid black;
  left:20px;
  bottom:0px;
  right:20px;
  height:19px;
}
.win_part_scrollH_left{
  position:absolute;
  top:0px;
  left:0px;
  height:19px;
  width:19px;
  background-color:white;
  background-image:url("{$DE_rootdir}skins/atari/images/LEFT.GIF");
  background-repeat:no-repeat;
  background-position:6px 4px;
}
.win_part_scrollH_right{
  position:absolute;
  top:0px;
  right:0px;
  height:19px;
  width:19px;
  background-color:white;
  background-image:url("{$DE_rootdir}skins/atari/images/RIGHT.GIF");
  background-repeat:no-repeat;
  background-position:5px 4px;
}
.win_part_resize{
  position:absolute;
  border:1px solid black;
  bottom:0px;
  right:0px;
  height:19px;
  width:19px;
  background-color:white;
  background-image:url("{$DE_rootdir}skins/atari/images/RESIZE.GIF");
  background-repeat:no-repeat;
  background-position:6px 4px;
}

</style>
<!--[if lte IE 6]>
<link rel=stylesheet type=text/css href=../inc/pngfix.css />
<![endif]-->
<script src=atari/atari.js type=text/javascript></script><!-- _new_ include dir, for final version, fix before -->
HTML;

  $desktop = 'DevEdit';
//new windowOrder,   X,   Y,   W,   H, W-63, H-65, H-65-42, display, id
  $winDefaults = '|0,50px,20px,600px,350px,inline,DevEditor0,3|1,450px,20px,395px,117px,none,DevOutput,2|2,50px,650px,215px,247px,none,DevCalc,2|3,40px,500px,423px,447px,inline,DevFont,2|4,40px,625px,180px,447px,inline,DevLibs,2|5,420px,20px,600px,150px,inline,DevLibHelp,2';
  loadWindows($desktop);

  $dir_e = urlencode($dir);
  $file_e = urlencode($file);
  $folder = end(explode("/",$dir));

  $dir_bh = browseHere($dir);

?>
<script language=JavaScript>
function browseHere(){
  if (fname=='') {
    extWindow(decodeURIComponent(f.dir.value).replace(/<?= $dir_bh[0] ?>/,'<?= $dir_bh[1] ?>')+'/');
    return; }
  if(oldficon.getAttribute('spec').indexOf('d')>0) 
    extWindow(decodeURIComponent(f.dir.value).replace(/<?= $dir_bh[0] ?>/,'<?= $dir_bh[1] ?>')+'/'+fname+'/');
  else
    extWindow(decodeURIComponent(f.dir.value).replace(/<?= $dir_bh[0] ?>/,'<?= $dir_bh[1] ?>')+'/'+fname);
}

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
  f.action.value = 'Save';
  f.submit();
}

function dcheck(eObj){
	if (!eObj) eObj = window.event;
	xObj=(eObj.target) ? eObj.target : eObj.srcElement;
	if (xObj.id=='DesktopBody' || xObj.tagName=='body'){
		clearMenus();
		atariClear();
	}
	return;
}

function clearMenus(){
	Desk.style.visibility = 'hidden';
	MenuDesk.style.color = 'black';
	MenuDesk.style.background = 'white';
	File.style.visibility = 'hidden';
	MenuFile.style.color = 'black';
	MenuFile.style.background = 'white';
	Edit.style.visibility = 'hidden';
	MenuEdit.style.color = 'black';
	MenuEdit.style.background = 'white';
	View.style.visibility = 'hidden';0
	MenuView.style.color = 'black';
	MenuView.style.background = 'white';
	Help.style.visibility = 'hidden';
	MenuHelp.style.color = 'black';
	MenuHelp.style.background = 'white';
	return;
}

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

function searchReplace(xObj){
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
//    xObj.value = eval('xV.replace(/'+xS+'/gi,xR)');
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
    return(xObj.selectionEnd-data.selectionStart);
  }
}

function textCut(xObj){
  saveEditor(xObj);
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
  xObj.focus();
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
  xObj.focus();
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
  scrollSetBoth('DevEditor0',xObj);
}

function startUp(){
  xCookie = 'windows' + DesktopName + '=';
  xCst = document.cookie.indexOf(xCookie);
  if (xCst==-1){
//    OpenEditor.style.display='inline';
  }else{
//    restoreWindows();
  }
}

DesktopName = '<?= $desktop ?>';

</script>

<script src=devedit.js type="text/javascript"></script>
<script language=JavaScript type="text/javascript">

isPlain = true;
Paste = new Array();

function displayFunction(xLang,xLib,xFn){
  Paste[xLang] = xFn;
}

function insertScript(xLang){
  if(!isPlain){

    xObj.editor.insertSnippet(Paste[xLang]);

  }else insertSnippet(Paste[xLang]);
}

function insertSnippet(xSnippet){
  textPaste(xObj,xSnippet)
}

function submitform(){
	if(isPlain) xObj.toggleEditor();
	return(true);
}
</script></head>
<?= $body ?><form action='' method=POST style="padding:0px; margin:0px; border:0px;">
<input type=hidden name=dir value="<?= $dir ?>">
<input type=hidden name=file value="<?= $file ?>">
<input type=hidden name=action value=Save>
<!-- MENU: main menu-->
<span style="position:absolute; left:0px; top:0px; width:100%;"><table border=0 width=100% height=20 cellpadding=0 cellspacing=0>
  <tr width=100% height=100%>
    <td width=100% height=20 valign=top><table class=big valign=top border=1 width=100% height=20 bgcolor=white cellpadding=0 cellspacing=0 style="border-collapse:collapse; border-width:0px 0px 1px 0px; border-color:black;">
  <tr>
    <td width=100% height=100% valign=bottom name=Menu style="border:none; cursor:default; font-family:{$font_large}; font-size:{$font_size};">&nbsp;&nbsp;<a
	class=MenuItem name=MenuDesk id=MenuDesk href="javascript:void(0)" onMouseOver="if (DesktopDialog || Drag || ReSize){return(false);}
								clearMenus();
								Desk.style.visibility='visible';
								this.style.color='white';
								this.style.background='black';">&nbsp;Desk&nbsp;</a><a
	class=MenuItem name=MenuFile id=MenuFile href="javascript:void(0)" onMouseOver="if (DesktopDialog || Drag || ReSize){return(false);}
								clearMenus();
								File.style.visibility='visible';
								this.style.color='white';
								this.style.background='black';">&nbsp;File&nbsp;</a><a
	class=MenuItem name=MenuEdit id=MenuEdit href="javascript:void(0)" onMouseOver="if (DesktopDialog || Drag || ReSize){return(false);}
								clearMenus();
								Edit.style.visibility='visible';
								this.style.color='white';
								this.style.background='black';">&nbsp;Edit&nbsp;</a><a
	class=MenuItem name=MenuView id=MenuView href="javascript:void(0)" onMouseOver="if (DesktopDialog || Drag || ReSize){return(false);}
								clearMenus();
								View.style.visibility='visible';
								this.style.color='white';
								this.style.background='black';">&nbsp;View&nbsp;</a><a
	class=MenuItem name=MenuHelp id=MenuHelp href="javascript:void(0)" onMouseOver="if (DesktopDialog || Drag || ReSize){return(false);}
								clearMenus();
								Help.style.visibility='visible';
								this.style.color='white';
								this.style.background='black';">&nbsp;Help&nbsp;</a>

    </td>
  </tr>
</table></td>
  </tr>
</table></span>
<!-- DROP MENUS -->
<!-- MENU: Desk -->
<span name=Desk id=Desk class=big style="visibility:hidden; top:19px; left:18px; position:absolute; width:200px; height:180px; z-index:65535; background-color:white; border:1px solid black;"> 
<a class=MenuItem href="javascript:void(0)" onClick="	Desk.style.visibility='hidden';
							oCenter(DesktopInfo);
							DesktopInfo.style.display='inline';
							DesktopDialog=true;
							">&nbsp;&nbsp;Desktop&nbsp;Inf..&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Desk.style.visibility='hidden';
							clearMenus();
							resetWindows();
							">&nbsp;&nbsp;Reset&nbsp;Windows&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<font class=MenuBlank>--------------------</font><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Desk.style.visibility='hidden';
							clearMenus();
							extWindow('?dir=$dir_e');
							">&nbsp;&nbsp;Open&nbsp;Explorer&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Desk.style.visibility='hidden';
							clearMenus();
							ModalCalc.style.display='inline';
							toFront(ModalCalc);
							">&nbsp;&nbsp;Calculator&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Desk.style.visibility='hidden';
							clearMenus();
							OpenEditor.style.display='inline';
							toFront(OpenEditor);
							">&nbsp;&nbsp;File&nbsp;Editor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Desk.style.visibility='hidden';
							clearMenus();
							ModalFont.style.display='inline';
							toFront(ModalFont);
							scrollSet(ModalFontScrollV,FontList);
							">&nbsp;&nbsp;Font&nbsp;Select&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Desk.style.visibility='hidden';
							clearMenus();
							OpenOutput.style.display='inline';
							toFront(OpenOutput);
							">&nbsp;&nbsp;Info&nbsp;Output&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)">&nbsp;&nbsp;Exit&nbsp;webTOS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
</span>
<!-- MENU: File -->
<span name=File id=File class=big style="visibility:hidden; top:19px; left:78px; position:absolute; width:260px; height:220px; z-index:65535; background-color:white; border:1px solid black;"> 
<a class=MenuItem href="javascript:void(0)" onClick="	File.style.visibility='hidden';
							clearMenus();
							reloadfile();
							">&nbsp;&nbsp;Reload&nbsp;File&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<font class=MenuBlank>--------------------------</font><br>
<a class=MenuItem href="javascript:void(0)" onClick="	File.style.visibility='hidden';
							clearMenus();
							browseHere();
							">&nbsp;&nbsp;Open&nbsp;in&nbsp;Browser&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	File.style.visibility='hidden';
							clearMenus();
							openeditor();
							">&nbsp;&nbsp;Open&nbsp;in&nbsp;HTML&nbsp;Editor&nbsp;&nbsp;[H]</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	File.style.visibility='hidden';
							clearMenus();
							opensource();
							">&nbsp;&nbsp;Open&nbsp;in&nbsp;Code&nbsp;Editor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<font class=MenuBlank>--------------------------</font><br>
<a class=MenuItem href="javascript:void(0)" onClick="	File.style.visibility='hidden';
							clearMenus();
							saveclose();
							">&nbsp;&nbsp;Save&nbsp;&&nbsp;Exit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	File.style.visibility='hidden';
							clearMenus();
							saveas();
							">&nbsp;&nbsp;Save&nbsp;As...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[A]</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	File.style.visibility='hidden';
							clearMenus();
							save();
							">&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[S]</a><br>
<font class=MenuBlank>--------------------------</font><br>
<a class=MenuItem href="?go=$dir_e" onClick="		File.style.visibility='hidden';
							clearMenus();
							saveEditer();
							">&nbsp;&nbsp;Exit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[Q]</a><br>
</span>
<!-- MENU: Edit -->
<span name=Edit id=Edit class=big style="visibility:hidden; top:19px; left:138px; position:absolute; width:210px; height:180px; z-index:65535; background-color:white; border:1px solid black;"> 
<a class=MenuItem href="javascript:void(0)" onClick="	Edit.style.visibility='hidden';
							clearMenus();
							">&nbsp;&nbsp;Undo&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[Z]</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Edit.style.visibility='hidden';
							clearMenus();
							">&nbsp;&nbsp;Redo&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<font class=MenuBlank>---------------------</font><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Edit.style.visibility='hidden';
							clearMenus();
							textCut(data);
							">&nbsp;&nbsp;Cut&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[X]</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Edit.style.visibility='hidden';
							clearMenus();
							textCopy(data);
							">&nbsp;&nbsp;Copy&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[C]</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Edit.style.visibility='hidden';
							clearMenus();
							textPaste(data,'_test_');
							">&nbsp;&nbsp;Paste&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[V]</a><br>
<font class=MenuBlank>---------------------</font><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Edit.style.visibility='hidden';
							clearMenus();
							oCenter(EditorFind);
							EditorFind.style.display='inline';
							DesktopDialog=true;
							">&nbsp;&nbsp;Find...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[F]</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Edit.style.visibility='hidden';
							oCenter(EditorReplace);
							EditorReplace.style.display='inline';
							DesktopDialog=true;
							">&nbsp;&nbsp;Replace...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[G]</a><br>
</span>
<!-- MENU: View -->
<span name=View id=View class=big style="visibility:hidden; top:19px; left:198px; position:absolute; width:210px; height:180px; z-index:65535; background-color:white; border: 1px solid black;"> 
<a class=MenuItem href="javascript:void(0)" onClick="	View.style.visibility='hidden';
							clearMenus();
							ModalFont.style.display='inline';
							toFront(ModalFont);
							">&nbsp;&nbsp;Font...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	View.style.visibility='hidden';
							clearMenus();
							fontdefault();
							">&nbsp;&nbsp;as&nbsp;Default&nbsp;Font&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	View.style.visibility='hidden';
							clearMenus();
							fontlarge();
							">&nbsp;&nbsp;as&nbsp;Large&nbsp;Font&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	View.style.visibility='hidden';
							clearMenus();
							fontsmall();
							">&nbsp;&nbsp;as&nbsp;Small&nbsp;Font&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<font class=MenuBlank>---------------------</font><br>
<a class=MenuItem href="javascript:void(0)" onClick="	View.style.visibility='hidden';
							clearMenus();
							storeLines();
							linenumber(prompt('Goto Line Number:',currentLine()));
							">&nbsp;&nbsp;goto&nbsp;Line...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	View.style.visibility='hidden';
							clearMenus();
							data.scrollTop=0;
							">&nbsp;&nbsp;goto&nbsp;Top&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	View.style.visibility='hidden';
							clearMenus();
							data.scrollTop=data.scrollHeight/2;
							">&nbsp;&nbsp;goto&nbsp;Middle&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	View.style.visibility='hidden';
							clearMenus();
							data.scrollTop=data.scrollHeight;
							">&nbsp;&nbsp;goto&nbsp;Bottom&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
</span>
<!-- MENU: Help -->
<span name=Help id=Help class=big style="visibility: hidden; top: 19px; left: 258px; position:absolute; width:200px; height:60px; z-index:65535; background-color: white; border: 1px solid black;"> 
<a class=MenuItem href="javascript:void(0)" onClick="	Help.style.visibility='hidden';
							clearMenus();
							help();
							">&nbsp;&nbsp;Quick&nbsp;Help&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<font class=MenuBlank>--------------------</font><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Help.style.visibility='hidden';
							clearMenus();
							about();
							">&nbsp;&nbsp;About&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
</span>
<!-- WINDOWS -->
<?

//chdir($dir);
$ficon = atarieditfile($file);

$xTitle = $file." in ".$folder;

print <<<HTML
<!-- ICON: editing file -->
<title>{$file} in {$folder} - Editing - Atari - DevEdit</title>
<!-- div id=eFile style="top:20px; left:2px; position:absolute; width:100px; height:100px; font-size:0px; border:1px solid black;"><table border=0 width=100><tr><td width=100% onmousedown=loadtd(this)>{$ficon}</td></tr></table></div>-->
<table id=eFile border=0 style="top:20px; left:2px; position:absolute; font-size:0px;"><tr><td onmousedown=loadtd(this)>{$ficon}</td></tr></table>
<!-- WINDOW: Editor 0 -->
<div name={$windows['DevEditor0'][6]} id={$windows['DevEditor0'][6]} xHnd=0 zid=2 minW=225 minH=160 style="position:absolute; display:{$windows['DevEditor0'][5]}; top:{$windows['DevEditor0'][1]}; left:{$windows['DevEditor0'][2]}; width:{$windows['DevEditor0'][3]}; height:{$windows['DevEditor0'][4]}; z-index:{$windows['DevEditor0'][7]};" 
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront({$windows['DevEditor0'][6]});"
><div class=win_part_close onClick="if (DesktopDialog){return(false);} {$windows['DevEditor0'][6]}.style.display='none'; clearMenus();"></div>
<div class=win_part_title onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront({$windows['DevEditor0'][6]}); dragWindow({$windows['DevEditor0'][6]},event);"><center><table class=big border=0 cellspacing=0 cellpadding=0><tr><td class=big align=right nowrap>Editing {$file} in {$folder}</td></tr></table></center></div>
<div class=win_part_max onClick="if (DesktopDialog){return(false);} toFront({$windows['DevEditor0'][6]}); MaxMin({$windows['DevEditor0'][6]}); event.cancelBubble=true;"></div>
<div class=win_part_option></div>
<div class=win_part_body><textarea name={$windows['DevEditor0'][6]}A id={$windows['DevEditor0'][6]}A wrap=off class=win_part_body style="border:none; top:0px; width:100%; height:100%;" name={$windows['DevEditor0'][6]}Body id={$windows['DevEditor0'][6]}Body >{$content}</textarea></div>
<!--<textarea wrap=off class=win_part_body  st yle="border:none; top:0px; width:100%; height:100%;" name={$windows['DevEditor0'][6]}Body id={$windows['DevEditor0'][6]}Body >{$_content}</textarea>-->
<div class=win_part_scrollV><div class=win_part_scrollV_up></div><div class=win_part_scrollV_slide></div><div class=win_part_scrollV_down></div></div>
<div class=win_part_scrollH><div class=win_part_scrollH_left></div><div class=win_part_scrollH_slide></div><div class=win_part_scrollH_right></div></div>
<div class=win_part_resize onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront({$windows['DevEditor0'][6]}); windowReSize({$windows['DevEditor0'][6]},event); event.cancelBubble=true;"></div>
<script language=JavaScript>
regWindow('{$windows['DevEditor0'][6]}',{$windows['DevEditor0'][6]});
fname = "{$file}";
ficon = document.getElementById('eFile').getElementsByTagName('img')[0];
oldficon = ficon;
//restoreEditor();
//scrollSetBoth('OpenEditor',{$windows['DevEditor0'][6]}Body);
</script>
</div>
<!-- WINDOW: Libraries -->
<div name={$windows['DevLibs'][6]} id={$windows['DevLibs'][6]} xHnd=0 zid=2 minW=125 minH=160 style="position:absolute; display:{$windows['DevLibs'][5]}; top:{$windows['DevLibs'][1]}; left:{$windows['DevLibs'][2]}; width:{$windows['DevLibs'][3]}; height:{$windows['DevLibs'][4]}; z-index:{$windows['DevLibs'][7]};" 
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront({$windows['DevLibs'][6]});"
><div class=win_part_close onClick="if (DesktopDialog){return(false);} {$windows['DevLibs'][6]}.style.display='none'; clearMenus();"></div>
<div class=win_part_title onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront({$windows['DevLibs'][6]}); dragWindow({$windows['DevLibs'][6]},event);"><table class=big border=0 cellspacing=0 cellpadding=0 align=center><tr><td align=right style="cursor:default; font-family:{$font_large}; font-size:{$font_size};">Libraries</td></tr></table></div>
<div class=win_part_max onClick="if (DesktopDialog){return(false);} toFront({$windows['DevLibs'][6]}); MaxMin({$windows['DevLibs'][6]}); event.cancelBubble=true;"></div>
<div class=win_part_option></div>
<div class=win_part_body s tyle="padding:0px;"><iframe name={$windows['DevLibs'][6]}A id={$windows['DevLibs'][6]}A border=0 frameborder=0 width=100% height=100% class=win_part_body style="top:0px; border:1px solid gray;" src="libraries.php"></iframe></div>
<div class=win_part_scrollV><div class=win_part_scrollV_up></div><div class=win_part_scrollV_slide></div><div class=win_part_scrollV_down></div></div>
<div class=win_part_scrollH><div class=win_part_scrollH_left></div><div class=win_part_scrollH_slide></div><div class=win_part_scrollH_right></div></div>
<div class=win_part_resize onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront({$windows['DevLibs'][6]}); windowReSize({$windows['DevLibs'][6]},event); event.cancelBubble=true;"></div>
<script language=JavaScript>
regWindow('{$windows['DevLibs'][6]}',{$windows['DevLibs'][6]});
</script>
</div>
<!-- WINDOW: Function Help -->
<div name={$windows['DevLibHelp'][6]} id={$windows['DevLibHelp'][6]} xHnd=0 zid=2 minW=225 minH=160 style="position:absolute; display:{$windows['DevLibHelp'][5]}; top:{$windows['DevLibHelp'][1]}; left:{$windows['DevLibHelp'][2]}; width:{$windows['DevLibHelp'][3]}; height:{$windows['DevLibHelp'][4]}; z-index:{$windows['DevLibHelp'][7]};" 
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront({$windows['DevLibHelp'][6]});"
><div class=win_part_close onClick="if (DesktopDialog){return(false);} {$windows['DevLibHelp'][6]}.style.display='none'; clearMenus();"></div>
<div class=win_part_title onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront({$windows['DevLibHelp'][6]}); dragWindow({$windows['DevLibHelp'][6]},event);"><table class=big border=0 cellspacing=0 cellpadding=0 align=center><tr><td align=right style="cursor:default; font-family:{$font_large}; font-size:{$font_size};">Function Information</td></tr></table></div>
<div class=win_part_max onClick="if (DesktopDialog){return(false);} toFront({$windows['DevLibHelp'][6]}); MaxMin({$windows['DevLibHelp'][6]}); event.cancelBubble=true;"></div>
<div class=win_part_option></div>
<div class=win_part_body><div name={$windows['DevLibHelp'][6]}A id={$windows['DevLibHelp'][6]}A ></div></div>
<div class=win_part_scrollV><div class=win_part_scrollV_up></div><div class=win_part_scrollV_slide></div><div class=win_part_scrollV_down></div></div>
<div class=win_part_scrollH><div class=win_part_scrollH_left></div><div class=win_part_scrollH_slide></div><div class=win_part_scrollH_right></div></div>
<div class=win_part_resize onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront({$windows['DevLibHelp'][6]}); windowReSize({$windows['DevLibHelp'][6]},event); event.cancelBubble=true;"></div>
<script language=JavaScript>
regWindow('{$windows['DevLibHelp'][6]}',{$windows['DevLibHelp'][6]});
</script>
</div>
<!-- DIALOG: Desktop Inf.. -->
<div name=DesktopInfo id=DesktopInfo style="display:none; top:0px; left:0px; position:absolute; width:375px; height:336px; z-index:65535; background-color: white; border: 1px solid black; padding: 2px"> 
<table class=big valign=top border=0 width=100% height=100% bgcolor=white cellpadding=0 cellspacing=0 style="border-collapse:collapse; border:3px solid black;">
  <tr>
    <td width=100% height=100% nowrap align=center style="border:0px; cursor:default; font-family:{$font_large}; font-size:{$font_size};"><br>
GEM, Graphic Environment Manager<br><br>
webTOS DevEdit<br>
Integrated Development Environment<br>
<font class=MenuBlank>-----------------------------</font><br>
<img src="skins/atari/images/IRLOGO.GIF" width=33 height=34 borber=0><br><br><br>
Copyright <b>©</b> 2000-2008<br>
Paul Wratt<br>
HaXoR.co.za & iSource.net.nz<br>
All Rights Reserved<br>
</td></tr>
<tr><td width=100% height=100% nowrap align=center style="border: 0px; cursor: default; padding: 15px"><table 
border=1 bgcolor=white cellpadding=0 cellspacing=0 style="border-collapse: collapse; border: 3px solid black;">
<tr><td style="border: none; cursor: default;"><a href="javascript:void(0)" name="OK" class="ButtonItem" 
onMouseDown="this.style.color='white'; this.style.background='black';event.cancelBubble=true;" 
onClick="DesktopInfo.style.display='none'; clearMenus(); this.style.color='black'; this.style.background='white'; DesktopDialog=false;event.cancelBubble=true;" 
>&nbsp;&nbsp;&nbsp;&nbsp;OK&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
</tr></table></td></tr>
</table>
</div>
<!-- DIALOG: EditorReplace -->
<div name=EditorReplace id=EditorReplace style="display:none; top:0px; left:0px; position:absolute; width:376px; height:238px; z-index:65533; background-color:white; border:1px solid black; padding:2px"> 
<table class=big valign=top border=0 width=100% height=100% bgcolor=white cellpadding=0 cellspacing=0 style="border-collapse:collapse; border:3px solid black;">
  <tr>
    <td width=100% height=100% nowrap align=center style="border:none; cursor:default; font-family:{$font_large}; font-size:{$font_size};
"><br>SEARCH:<br>
<input name=search type=text size=37 value="" v alue="_____________________________________" style="border:none; border-bottom:1px dashed black; c ursor:default; font-family:{$font_large}; font-size:{$font_size}; color:black; background:white;"><br>
<br>REPLACE:<br>
<input name=replace type=text size=37 value="" v alue="_____________________________________" style="border:none; border-bottom:1px dashed black; c ursor:default; font-family:{$font_large}; font-size:{$font_size}; color:black; background:white;"><br>
</td></tr>
<!-- BUTTON: options -->
<tr><td width=100% height=100% nowrap align=center style="border:none; cursor:default; padding:15px"><table 
border=0 bgcolor=white cellpadding=0 cellspacing=0 style="border-collapse:collapse;border: none; cursor:default; font-family:{$font_large}; font-size:{$font_size};">
<tr><td>Type:</td><td>&nbsp;</td>
<td style="border:1px solid black;"><a href="javascript:void(0)" name=OptionButtonOne id=OptionButtonOne class=ButtonItem 
onMouseDown="	this.style.color='white'; this.style.backgroundColor='black';
		OptionButtonAll.style.color='black';
		OptionButtonAll.style.backgroundColor='white';" 
onClick="" 
>&nbsp;&nbsp;&nbsp;1ST&nbsp;&nbsp;&nbsp;</a></td>
<td>&nbsp;&nbsp;</td>
<td style="border:1px solid black;cursor:default;"><a href="javascript:void(0)" name=OptionButtonAll id=OptionButtonAll class=ButtonItem style="color: white; background: black;" 
onMouseDown="	this.style.color='white'; this.style.backgroundColor='black';
		OptionButtonOne.style.color='black'; 
		OptionButtonOne.style.backgroundColor='white';" 
onClick="" 
>&nbsp;&nbsp;&nbsp;ALL&nbsp;&nbsp;&nbsp;</a></td>
</tr></table></td></tr>
<!-- BUTTON: OK Cancel -->
<tr><td width=100% height=100% nowrap align=center style="border:none; cursor:default; padding:15px"><table 
border=0 bgcolor=white cellpadding=0 cellspacing=0 style="border-collapse:collapse; border:0px">
<tr><td style="border:3px solid black; cursor: default;"><a href="javascript:void(0)" class=ButtonItem 
onMouseDown="	this.style.color='white';
		this.style.background='black';" 
onClick="	EditorReplace.style.display='none';
		clearMenus();
		this.style.color='black';
		this.style.background='white';
		DesktopDialog=false;
		searchReplace();
" >&nbsp;&nbsp;&nbsp;&nbsp;OK&nbsp;&nbsp;&nbsp;&nbsp;</a></td><td style="border:none; cursor:default;">&nbsp;&nbsp;</td>
<td style="border:3px solid black; cursor:default;"><a href="javascript:void(0)" class=ButtonItem 
onMouseDown="	this.style.color='white';
		this.style.background='black';" 
onClick="	EditorReplace.style.display='none';
		textReset(f.search);
		textReset(f.replace);
		clearMenus();
		this.style.color='black';
		this.style.background='white';
		DesktopDialog=false;
" >&nbsp;&nbsp;Cancel&nbsp;&nbsp;</a></td>
</tr></table></td></tr>
</table>
</div>
<!-- DIALOG: EditorFind -->
<div name=EditorFind id=EditorFind style="display:none; top:0px; left:0px; position:absolute; width:396px; height:278px; z-index:65533; background-color:white; border:1px solid black; padding:2px"> 
<table class=big valign=top border=0 width=100% height=100% bgcolor=white cellpadding=0 cellspacing=0 style="border-collapse:collapse; border:3px solid black;">
  <tr>
    <td width=100% height=100% nowrap align=center style="border:none; cursor:default; font-family:{$font_large}; font-size:{$font_size};
"><br>Find:<br>
<input name=find type=text size=37 value="" v alue="_____________________________________" style="border:none; border-bottom:1px dashed black; c ursor:default; font-family:{$font_large}; font-size:{$font_size}; color:black; background:white;"><br>
</td></tr>
<!-- BUTTON: options -->
<tr><td width=100% height=100% nowrap align=center style="border:none; cursor:default; padding:15px"><table 
border=0 bgcolor=white cellpadding=0 cellspacing=0 style="border-collapse:collapse; border:none; cursor:default; font-family:{$font_large}; font-size:{$font_size};">
<tr><td>Type:</td><td>&nbsp;</td>
<td style="border:1px solid black; cursor:default;"><a href="javascript:void(0)" name=OptionButtonTop id=OptionButtonTop class=ButtonItem 
onMouseDown="	this.style.color='white'; this.style.backgroundColor='black';
		OptionButtonPrev.style.color='black';
		OptionButtonPrev.style.backgroundColor='white';
		OptionButtonNext.style.color='black';
		OptionButtonNext.style.backgroundColor='white';" 
onClick="" 
>&nbsp;&nbsp;&nbsp;1ST&nbsp;&nbsp;&nbsp;</a></td>
<td>&nbsp;&nbsp;</td>
<td style="border:1px solid black; cursor:default;"><a href="javascript:void(0)" name=OptionButtonPrev id=OptionButtonPrev class=ButtonItem
onMouseDown="	this.style.color='white'; this.style.backgroundColor='black';
		OptionButtonTop.style.color='black'; 
		OptionButtonTop.style.backgroundColor='white';
		OptionButtonNext.style.color='black';
		OptionButtonNext.style.backgroundColor='white';" 
onClick="" 
>&nbsp;&nbsp;PREV&nbsp;&nbsp;</a></td>
<td>&nbsp;&nbsp;</td>
<td style="border:1px solid black; cursor:default;"><a href="javascript:void(0)" name=OptionButtonNext id=OptionButtonNext class=ButtonItem style="color:white; background:black;" 
onMouseDown="	this.style.color='white'; this.style.backgroundColor='black';
		OptionButtonTop.style.color='black'; 
		OptionButtonTop.style.backgroundColor='white';
		OptionButtonPrev.style.color='black';
		OptionButtonPrev.style.backgroundColor='white';" 
onClick="" 
>&nbsp;&nbsp;NEXT&nbsp;&nbsp;</a></td>
</tr></table></td></tr>
<!-- BUTTON: OK Cancel -->
<tr><td width=100% height=100% nowrap align=center style="border:none; cursor:default; padding:15px"><table 
border=0 bgcolor=white cellpadding=0 cellspacing=0 style="border-collapse:collapse; border:0px">
<tr><td style="border:3px solid black; cursor:default;"><a href="javascript:void(0)" class=ButtonItem 
onMouseDown="	this.style.color='white';
		this.style.background='black';" 
onClick="	EditorFind.style.display='none';
		clearMenus();
		this.style.color='black';
		this.style.background='white';
		DesktopDialog=false;
		searchFind();
" >&nbsp;&nbsp;&nbsp;&nbsp;OK&nbsp;&nbsp;&nbsp;&nbsp;</a></td><td style="border:none; cursor:default;">&nbsp;&nbsp;</td>
<td style="border:3px solid black; cursor:default;"><a href="javascript:void(0)" class=ButtonItem 
onMouseDown="	this.style.color='white';
		this.style.background='black';" 
onClick="	EditorFind.style.display='none';
		textReset(f.find);
		clearMenus();
		this.style.color='black';
		this.style.background='white';
		DesktopDialog=false;
" >&nbsp;&nbsp;Cancel&nbsp;&nbsp;</a></td>
</tr></table></td></tr>
</table>
</div>
<!-- DIALOG: SearchNone -->
<div name=SearchNone id=SearchNone style="display:none; top:0px; left:0px; position:absolute; width:376px; height:178px; z-index:65533; background-color:white; border:1px solid black; padding:2px"> 
<table class=big valign=top border=0 width=100% height=100% bgcolor=white cellpadding=0 cellspacing=0 style="border-collapse:collapse; border:3px solid black;">
  <tr>
    <td width=100% height=100% nowrap align=center style="border:none; cursor:default; font-family:{$font_large}; font-size:{$font_size};
"><br>Cant Find:<br>
<input name=cant type=text size=37 value="" v alue="_____________________________________" onFocus="this.blur();" style="border:none; cursor:default; font-family:{$font_large}; font-size:{$font_size}; color:black; background:white;"><br>
</td></tr>
<!-- BUTTON: OK -->
<tr><td width=100% height=100% nowrap align=center style="border:none; cursor:default; padding:15px"><table 
border=0 bgcolor=white cellpadding=0 cellspacing=0 style="border-collapse:collapse; border:0px">
<tr><td style="border:3px solid black; cursor:default;"><a href="javascript:void(0)" class=ButtonItem 
onMouseDown="	this.style.color='white';
		this.style.background='black';" 
onClick="	SearchNone.style.display='none';
		textReset(f.cant);
		clearMenus();
		this.style.color='black';
		this.style.background='white';
		DesktopDialog=false;
		data.focus();
" >&nbsp;&nbsp;&nbsp;&nbsp;OK&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
</tr></table></td></tr>
</table>
</div>

</form>
<script language=JavaScript>
document.title = '{$xTitle} - Editing - Atari - DevEdit';
skintype = '{$groupimgs}';
imgW = {$icn_size[0]};
imgH = {$icn_size[1]};

width = (window.innerWidth) ? window.innerWidth : document.body.clientWidth;
height = (window.innerHeight) ? window.innerHeight : document.body.clientHeight;
//width=0;
//height=0;

document.cookie = 'defaults' + DesktopName + '={$winDefaults};';
//setini();
xObj = document.getElementById('{$windows['DevEditor0'][6]}Body');

fname = "{$file}";
ficon = document.getElementById('eFile').getElementsByTagName('img')[0];
oldficon = ficon;
//restoreEditor();
//scrollSetBoth('OpenEditor',data);

</script>
</body></html>
HTML;

www_page_close();