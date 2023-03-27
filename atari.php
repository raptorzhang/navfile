<?php
#---------------------------
# PHP Navigator 4.12.16
# atari prototype (for v5)
# dated: 05-03-2008
# edited: 13-01-2011
# Modified by: Paul Wratt,
# Melbourne, Australia
# web: phpnav.isource.net.nz
#---------------------------

$dir    = @$_REQUEST['dir'];
$action = @$_REQUEST['action'];
$file   = @$_REQUEST['file'];
$change = @$_REQUEST['change'];
$go     = @$_REQUEST['go'];

$file   = urldecode($file);
$change = urldecode($change);
$dir    = urldecode($dir);
$go     = urldecode($go);

$arrange_by = "name";
$skin = ""; $skincss = "inc/windows.css";

include_once("config.php");
include_once("functions.php");
getcookies();
include_once("skin.php"); // setup skins

include_once("config_patch.php"); // extra data required for "browse this"

authenticate();	//user login & other restrictions

#------------- NEW FUNCTIONS ------------
# v5 prototype skin with new functions
#
# www_page_open()  - start data output encoding to browser
# www_page_close() - end data output encoding, apply compression
# folderin(dir)    - return "end_folder in end_folder-1" from full path
#

# 01/09/2008 - moved to "functions.php"

$fiIE=""; $fiIE7="//"; $fi =""; $fiM = "  }\n"; $menufix = "";
$body = '<body id=DesktopBody topmargin=0 leftmargin=0 marginheight=0 marginwidth=0 onload="startUp();" onBeforeUnload="storeWindows();" onClick="dcheck(event);" onDragStart="return(false);" onSelectStart="event.cancelBubble=true; return(false);" onDrag="doMover(event);" onMouseMove="doMover(event);" onDragEnd="doEnd(event);" onMouseUp="doEnd(event);" style="width:100%; height:100%; background-color:#00FF00;">';

// default non-windows font
  $font_large = "'Liberation Mono','Andale Mono',Fixed";
  $font_weight = "normal";
  $font_size = "17px";
  $font_small = "'Liberation Mono','Andale Mono',Clean";
  $font_size_small = "10px";
  $font_fix = "line-height:20px;";
  $ed_fix = "overflow:hidden;";

//if (substr_count($_SERVER['HTTP_USER_AGENT'],"Linux")>0 && (substr_count($_SERVER['HTTP_USER_AGENT'],"rv:1.9")>0 || substr_count($_SERVER['HTTP_USER_AGENT'],"rv:2.0")>0 || substr_count($_SERVER['HTTP_USER_AGENT'],"rv:2.1")>0) || substr_count($_SERVER['HTTP_USER_AGENT'],"rv:2.2")>0){
//  $top = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
//}

//if (substr_count($_SERVER['HTTP_USER_AGENT'],"Linux")>0 && substr_count($_SERVER['HTTP_USER_AGENT'],"PCLinuxOS")>0  && substr_count($_SERVER['HTTP_USER_AGENT'],"(2007)")>0){
if (substr_count($_SERVER['HTTP_USER_AGENT'],"Linux")>0){
  $top = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
  $brows = '2';
  $browsp = '0';

  $font_large = "Fixed";    // 16pt
  $font_size = "19px";
  $font_weight = "bold";  // Doesn't need it.. (just like windows..)
  $font_small = "Fixed"; // 8pt
  $font_size_small = "11px";
  $font_fix = "line-height:20px;";

  if (substr_count($_SERVER['HTTP_USER_AGENT'],"Chrome")>0) {
    $brows = '-3';
    $browsp = '2';
    $font_large = "mono";
    $font_size = "17px";
    $font_weight = "normal";
    $font_small = "mono";
    $font_size_small = "11px";
    $font_fix = "";
  }

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

  if (substr_count($_SERVER['HTTP_USER_AGENT'],"Firefox")>0 || substr_count($_SERVER['HTTP_USER_AGENT'],"BonEcho")>0 || substr_count($_SERVER['HTTP_USER_AGENT'],"Iceweasel")>0) {
    $top = '';
    $ed_fix = "overflow:hidden;";
    $brows = '0';
    $font_large = "Fixed";
    $font_size = "20px";
    $font_weight = "normal";
    $font_small = "Clean";
    $font_size_small = "11px";
    $font_fix = "";

//    if (substr_count($_SERVER['HTTP_USER_AGENT'],"rv:1.8")>0 || substr_count($_SERVER['HTTP_USER_AGENT'],"rv:1.7")>0 || substr_count($_SERVER['HTTP_USER_AGENT'],"rv:1.6")>0 || substr_count($_SERVER['HTTP_USER_AGENT'],"rv:1.5")>0){
//      $top = '';
//    }

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

}elseif (substr_count($_SERVER['HTTP_USER_AGENT'],"Windows")>0){

    $ed_fix = "overflow:hidden;";
    $font_large = "'MS Gothic'";
    $font_size = "18px";
    $font_weight = "bold";
    $font_small = "'MS Gothic'";
    $font_size_small = "12px";
    $font_fix = "line-height:20px;";

    if (substr_count($_SERVER['HTTP_USER_AGENT'],"Firefox")>0) {
//      $font_large = "'Lucida Console'";
//      $font_size = "17px";
//      $font_small = "'Lucida Console'";
//      $font_size_small = "11px";
// Hattensweiger
// Impact
// Lucida Sans Typewriter
    }
    if (substr_count($_SERVER['HTTP_USER_AGENT'],"MSIE")>0) {
      $fiIE=""; $fiIE7="//"; $fi ="//";
      $menufix = "fixIEmenus();";

      $ed_fix = "overflow:hidden;";
      $font_large = "Terminal";
      $font_weight = "normal";
      $font_size = "20px";
      $font_small = "Terminal";
      $font_size_small = "11px";
      $font_fix = "line-height:20px;";
    }
    if ((substr_count($_SERVER['HTTP_USER_AGENT'],"MSIE 7")>0))// || (substr_count($_SERVER['HTTP_USER_AGENT'],"MSIE 7")>0))
{
      $fiIE="//"; $fiIE7=""; $fi ="//"; $fiM = "";
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
$iconHeight = (2*intval($font_size_small))+$icn_size[1]+5;
$val_font_size = intval($font_size);
$val_font_size_small = intval($font_size_small);

function explore($dir)
{
global $cols, $uploads, $i, $arrange_by, $disp, $bytes, $total;

if (is_dir($dir))
 {
 if($dh = opendir($dir))
  {
  while (($file = readdir($dh)))  {$files[] = $file;}
  sort($files); 	#default is sort by name
  $i=1;
  foreach($files as $file)
   {
   if($file!="."&&$file!=".."&&is_dir($file))
    {
     print "<span class=iconFile $disp><table border=0 width=100><tr><td width=100% onmousedown=loadtd(this)>";
     ataristatus($file);	# function to print file icon & details
     print "</td></tr></table></span>";
     $i++;
    }
   }
  if($arrange_by=="type")	#sort by type
  {
	  foreach($files as $file)
		{
		$data=pathinfo($file);
		$exts[]=strtolower($data["extension"]);
		}
	  array_multisort($exts,SORT_STRING ,SORT_ASC,$files); 
  }
  elseif($arrange_by=="size")	#sort by size
  {
	  foreach($files as $file)
		{
		$sizes[]=0+filesize($file);
		}
	  array_multisort($sizes,SORT_NUMERIC ,SORT_DESC,$files); 
  }
  foreach($files as $file)	#default is sort by name
   {
   if($file!="."&&$file!=".."&&!is_dir($file))
    {
     print "<span class=iconFile $disp ><table border=0 width=100><tr><td width=100% onmousedown=loadtd(this)>";
     ataristatus($file);	# function to print file icon & details
     print "</td></tr></table></span>";
     $bytes += filesize($file);
     $i++;
    }
   }
  closedir($dh);
  }
 }
else
 $msg[]= "Directory $dir does not exist!";
$total = count($files)-2;
$perms = decoct(fileperms($file)%01000);

print"
<input type=hidden name=total value='$total'>
<input type=hidden name=perms value='$perms'><br>";

}

function ataristatus($file)
{
  global $dir, $realdir, $no_icn, $icn_size, $use_layout, $P;
  $skin = $GLOBALS['skin'];
  $gi   = $GLOBALS['groupimgs'];
  if(is_array($icn_size)){
    $w = $icn_size[0];
    $h = $icn_size[1];
  }else{
    $w = '32';
    $h = $w;
  }
  if(end($P)=="details.php"){
    $w = '16';
    $h = $w;
  }

  $scale = array(" Bytes"," KB"," MB"," GB");
  $stat = stat($file);

  $size = $stat[7];
  for($s=0;$size>1024&&$s<4;$s++) $size=$size/1024;	//Calculate in Bytes,KB,MB etc.
  if($s>0) $size= number_format($size,2).$scale[$s];
  else $size= number_format($size).$scale[$s];
  if(is_editable($file)) $dblclick="opendir()"; else $dblclick="not_editable()";
  $spec=filespec($file);

  $filename_t = htmlentities($file,ENT_QUOTES);
  $filename_e = urlencode($file);
  $dir_e = urlencode($dir);
  $filename = wordwrap($filename_t, 15, "<br>\n",1);
  $ts = time()-strtotime("4 April 2008");
  $fn = preg_replace("/[[:space:][:punct:]]/","_",$file);
  $aid = "f".$ts.$fn;

  if(is_dir($file)){
	$img = "skins/{$skin}dir{$gi}";
	$imgsel = "skins/{$skin}dir_sel{$gi}";
	if (!file_exists($realdir.$img)) $img = "skins/dir.gif";
	if (!file_exists($realdir.$imgsel)) $imgsel = $img;
	print "<center><a class=icon><img class=ficon aid=\"$aid\"
	src=\"$img\" width=$w height=$h  alt=\"Folder: $filename_t<br>
	    Permissions: ".decoct(fileperms($file)%01000)."<br>
	    Modified: ".date('d-m-y, G:i', $stat[9])."\" 
	    onMouseDown=\"atarifile(this,'',event);\" id=file title=\"$filename_t\" onDblClick=\"if(DesktopDialog){return(false);}opendir();\" spec='$spec' 
	    onError=\"this.src='skins/atari/dir.gif';\" atariimg='$img' atarisel='$imgsel'></a><br><a 
	    class=name name=\"$aid\" id=\"$aid\" href=\"\" onClick=\"if(DesktopDialog){return(false);}else{download('$filename_t');}\" 
	    title=\"Download as zip\">$filename</a>";
  }else{
	$ficon = groupicon($file);
	$img = "skins/{$skin}$ficon";
	$imgsel = "skins/{$skin}".str_replace($gi,"_sel{$gi}",$ficon);
	if (!file_exists($realdir.$img)) $img = "skins/$ficon";
	if (strstr($ficon,"thumb")==$ficon) $img = $ficon."&border=false";
	if (!file_exists($realdir.$imgsel)) $imgsel = $img;
	print"<center><a class=icon><img class=ficon aid=\"$aid\"
	src=\"$img\" width=$w height=$h 
	    onMouseDown=\"atarifile(this,'',event)\" title=\"$filename_t\" id=file
	    alt=\"File: $filename_t<br>Size: $size<br>
	    Permissions: ".decoct(fileperms($file)%01000)."<br>
	    Modified: ".date('d-m-y, G:i', $stat[9])."<br>
	    Accessed: ".date('d-m-y, G:i', $stat[8])."\" onDblClick=\"if(DesktopDialog){return(false);}$dblclick;\" spec='$spec' 
	    onError=\"this.src='skins/atari/file.gif';\" atariimg='$img' atarisel='$imgsel'></a><br><a 
	    class=name name=\"$aid\" id=\"$aid\" href=\"\" onClick=\"if(DesktopDialog){return(false)}else{downloadFile('$filename_t');}\" 
	    title=Download>$filename</a>";
  }
}

function atarieditfile($file)
{
  global $dir, $realdir, $no_icn, $icn_size, $use_layout, $P;
  $skin = $GLOBALS['skin'];
  $gi   = $GLOBALS['groupimgs'];
  if(is_array($icn_size)){
    $w = $icn_size[0];
    $h = $icn_size[1];
  }else{
    $w = '32';
    $h = $w;
  }
  if(end($P)=="details.php"){
    $w = '16';
    $h = $w;
  }

  $scale = array(" Bytes"," KB"," MB"," GB");
  $stat = stat($file);

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
	$img = "skins/{$skin}$ficon";
	$imgsel = "skins/{$skin}".str_replace($gi,"_sel{$gi}",$ficon);
	if (!file_exists($realdir.$img)) $img = "skins/$ficon";
	if (strstr($ficon,"thumb")==$ficon) $img = $ficon."&border=false";
	if (!file_exists($realdir.$imgsel)) $imgsel = $img;
	return "<center><a class=icon><img class=ficon aid=\"$aid\"
	    src=\"$img\" width=$w height=$h name=\"$iid\" id=\"$iid\" 
	    onMouseDown=\"clearMenus(); atariIcon(this,'i');\" title=\"$filename_t\"
	    alt=\"File: $filename_t<br>Size: $size<br>
	    Permissions: ".decoct(fileperms($file)%01000)."<br>
	    Modified: ".date('d-m-y, G:i', $stat[9])."<br>
	    Accessed: ".date('d-m-y, G:i', $stat[8])."\" spec='$spec' 
	    onDblClick=\"if(DesktopDialog){return(false);} OpenEditor.style.display='inline'; atariClear(); return(false);\"
	    onError=\"this.src='skins/atari/file.gif';\" atariimg='$img' atarisel='$imgsel'></a><br><a 
	    class=name name=\"$aid\" id=\"$aid\" aid=\"$iid\" href='javascript:void(0)' onClick=\"if(DesktopDialog){return(false);}\" 
	    onDblClick=\"if(DesktopDialog){return(false);} OpenEditor.style.display='inline'; atariClear(); return(false);\"
	    onMouseDown=\"clearMenus(); atariIcon(this,'f');\">$filename</a>";
}

#-------- ATARI WINDOW COOKIES ---------

$windows = array();

function loadWindows($desktop){
  global $windows, $winDefaults, $desktop;
//  if($_REQUEST['windows'.$desktop]!="") $xWindows = explode("|",$_REQUEST['windows'.$desktop]);
  if(isset($_COOKIE['windows'.$desktop])) $xWindows = explode("|",$_COOKIE['windows'.$desktop]);
  else $xWindows = explode("|",$winDefaults);
  if(count($xWindows)<=1) return;
  $xEval = ""; $c="";
  for($i=1;$i<count($xWindows);$i++){
    $xW = explode(",",$xWindows[$i]);
    $xEval .= $c."'".$xW[9]."' => explode(',',\$xWindows[".$i."])";
    $c = ", ";
  }
//print "\$windows=array(".$xEval.");";
  eval("\$windows=array(".$xEval.");");
}

function browseHere($xPath){
  global $server_root, $browser_root;
  for($i=0;$i<count($server_root);$i++){
    if (substr_count($xPath,$server_root[$i])>0)
      return array(str_replace('/','\/',str_replace("\\","\\\\",$server_root[$i])),$browser_root[$i]);
  }
  return array('undefined','undefined');
}

#----------------------------------------

if ($action=="Save")
  header("X-XSS-Protection: 0"); # bugfix for new browsers
if ($action=="Download"){}
else www_page_open();

$dir=str_replace("\\\\","\\",$dir); #For Windows, a workaround on magic quotes.
if($go&&!$action) $dir = dirfrom($go);
if(!$dir) $dir=$homedir;

//$favicon = (file_exists('skins/'.$skin.'screenshot.png')) ? 'thumb.php?size=16&img=skins'.$skin.'screenshot.png' : './favicon.png';
$favicon = './thumb.php?size=16&img=skins/atari/screenshot.png';

if($action=="Download")
  {
  download();
  die();
  }

  print <<<HTML
{$top}
<html>
<head>
<link rel='icon' href='{$favicon}' type='image/x-icon' />
<style type=text/css >
html{
  height:100%;
}
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
  word-wrap: normal;
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
  cursor:default;
}
</style>
<script src=skins/atari/inc/atari.js type=text/javascript></script>
<script src=inc/windows.js type=text/javascript></script>
<script src=inc/$mode.js type=text/javascript></script>
<!--[if lte IE 6]>
<link rel=stylesheet type=text/css href=inc/pngfix.css />
<![endif]-->

HTML;

#-------------- EDITOR BIT -------------

if(($action=="Open" || $action=="UpdateSave") && !is_dir("$dir/$file")){

  if($action=="UpdateSave") save($file);

  $desktop = 'Editor';
//new windowOrder,   X,   Y,   W,   H, W-63, H-65, H-65-42, display, id
  $winDefaults = '|0,50px,20px,603px,365px,540px,300px,258px,inline,OpenEditor,2|1,450px,20px,395px,117px,332px,52px,10px,none,OpenOutput,2|2,50px,650px,215px,247px,152px,182px,140px,none,ModalCalc,2|3,40px,500px,423px,447px,360px,402px,360px,inline,ModalFont,2';
  loadWindows($desktop);

  $dir_e = urlencode($dir);
  $file_e = urlencode($file);
  $D = explode("/",$dir);
  $folder = end($D);

  $dir_bh = browseHere($dir);

  print <<<HTML
<script language=JavaScript>
function browseHere(){
//alert(decodeURIComponent(f.dir.value).replace(/{$dir_bh[0]}/,'{$dir_bh[1]}')+'/');
  if (fname=='') {
    extWindow(decodeURIComponent(f.dir.value).replace(/{$dir_bh[0]}/,'{$dir_bh[1]}')+'/');
    return; }
  if(oldficon.getAttribute('spec').indexOf('d')>0) 
    extWindow(decodeURIComponent(f.dir.value).replace(/{$dir_bh[0]}/,'{$dir_bh[1]}')+'/'+fname+'/');
  else
    extWindow(decodeURIComponent(f.dir.value).replace(/{$dir_bh[0]}/,'{$dir_bh[1]}')+'/'+fname);
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
  thestatus.innerHTML = "<center>Editing: <b>'"+fname+"'</b>";

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

  thestatus.innerHTML = "<center>Double Click icon to reopen <b>'"+fname+"'</b>";

}

function fontlarge(){
  data.style.fontFamily = "{$font_large}";
  data.style.fontSize = '{$font_size}';
}

function fontsmall(){
  data.style.fontFamily = "{$font_small}";
  data.style.fontSize = '{$font_size_small}';
}

function fontdefault(){
  data.style.fontFamily = '';
  data.style.fontSize = '';
}

function fontchange(){

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
	Phile.style.visibility = 'hidden';
	MenuPhile.style.color = 'black';
	MenuPhile.style.background = 'white';
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

function saveEditer(){
  xfont = data.style.font.replace(/x/g,'~');
  document.cookie = 'editorScroll=0x0x' + xfont + 'x0x0;';
}

function saveEditur(){
  xfont = data.style.font.replace(/x/g,'~');
  document.cookie = 'editorScroll=' + data.scrollTop + 'x' + data.scrollLeft + 'x' + xfont + ';';
}

function saveEditor(){
  xfont = data.style.font.replace(/x/g,'~');
  document.cookie = 'editorScroll=' + data.scrollTop + 'x' + data.scrollLeft + 'x' + xfont + 'x' + selectStart(data) + 'x' + selectEnd(data) + ';';
}

function restoreEditor(){
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
  data.style.font = xWs[2].replace(/~/g,'x');
  if (xWs.length>3) {
    data.selectionStart = xWs[3];
    data.selectionEnd = xWs[4];
  }
  data.scrollTop = xWs[0];
  data.scrollLeft = xWs[1];
  data.focus();
}

function searchReplace(){
  xSl = String.fromCharCode(92)
//  xSl = '\\';
  xSls = xSl + xSl;
  xS = f.search.value;
  xR = f.replace.value;
  xV = f.data.value;
  saveEditur();

  if(OptionButtonAll.style.backgroundColor=='black'){
//    xS = xS.replace(/\//g,'\/');
//    xS = xS.replace(/\\\\/g,'\\\\\\\\');
    xS = xS.replace(/\(/g,xSl+'(');
    xS = xS.replace(/\)/g,xSl+')');
    xPtn = new RegExp(xS,'g');
    f.data.value = xV.replace(xPtn,xR);
//    f.data.value = eval('xV.replace(/'+xS+'/gi,xR)');
  }else
    f.data.value = xV.replace(xS,xR);
  restoreEditor();
}

function searchFind(){
  xS = f.find.value;
  xN = 0; if(data.selectionStart!=data.selectionEnd) xN = 1;

  if(OptionButtonNext.style.backgroundColor=='black')
    xStart = f.data.value.indexOf(xS,data.selectionStart+xN);
  else if(OptionButtonPrev.style.backgroundColor=='black')
    xStart = f.data.value.lastIndexOf(xS,data.selectionStart-xN);
  else
    xStart = f.data.value.indexOf(xS,0);

  if (xStart==-1) {
    f.cant.value = xS;
    oCenter(SearchNone);
    SearchNone.style.display = 'inline';
    return;
  }

  storeLines();
  data.selectionStart = xStart;
  data.selectionEnd = xStart + xS.length;
  data.focus();
  scrollViewLine(currentLine());
}

function textSelect(){
  if (document.selection){
    data.focus();
    sel = document.selection.createRange();
    alert(sel.text);
  }else{
    alert(data.selectionStart+':'+data.selectionEnd);
    alert(data.value.substring(data.selectionStart,data.selectionEnd));
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
  saveEditur();
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
  restoreEditor();
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
  saveEditor();
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
  restoreEditor();
}

var lines = new Array();
function linenumber(xLine){
  if(!xLine || xLine==0){
//    data.selectionStart = 0;
//    data.selectionEnd = 0;
    data.focus();
    return;
  }else if(xLine>=lines.length){
    data.selectionStart = data.value.length;
    data.selectionEnd = data.value.length;
    scrollViewLine(xLine);
    data.focus();
    return;
  }else{
//alert(lines[xLine]);
    data.selectionStart = lines[xLine-1]+1;
    data.selectionEnd = lines[xLine];
    scrollViewLine(xLine);
    data.focus();
    return;
  }
//  return (lines.length);
}

function currentLine(){
//alert(data.selectionStart);
  if(data.selectionStart==data.value.length) return (lines.length-1);
  for(i=0;i<lines.length;i++){
    if(data.selectionStart<lines[i]+1) return (i);
  }
  return (lines.length-1);
}

function storeLines(){
  i = 0;
  j = 0;
  lines[0] = 0;
  x = data.value;
  while(i!=-1){
    i++;
    i = x.indexOf('\\n',i);
    j++;
    lines[j] = i;
  }
  lines[j] = x.length;
  x = '';
}

function scrollViewLine(xLine){
  xLineHeight = (data.scrollHeight)/(lines.length);
  data.scrollTop = (xLineHeight*xLine)-xLineHeight;
  scrollSetBoth('OpenEditor',data);
}

function opendevatari(){
if(fname!="") {
  window.open("./DevEdit/atarieditor.php?file=" + encode(fname) + "&dir="+encode(f.dir.value), "DevEdit"+xTime(),"width=750, height=500, left=10, top=10, resizable=yes, scrollbars=no, location=no, toolbar=no,menubar=no");
 }
}

function fixIEmenus(){
  xMenu = document.getElementsByTagName('span');
  for(i=0;i<xMenu.length;i++){
    if(xMenu[i].children.length>0){
      c = xMenu[i].firstChild;
      if(c.tagName=='A'){
        if(c.className=='MenuItem'){
          xMenu[i].style.width = (parseInt(xMenu[i].style.width)+2)+'px';
        }
      }
    }
  }
}

function startUp(){
  xCookie = 'windows' + DesktopName + '=';
  xCst = document.cookie.indexOf(xCookie);
  if (xCst==-1){
    OpenEditor.style.display='inline';
  }else{
//    restoreWindows();
  }
//  storeLines();
  {$menufix}
}

DesktopName = '{$desktop}';

</script>
</head>
{$body}
<form action='?' method=POST name=f >
<div style='position:absolute; font-size:0.7em; bottom:10px; right:10px; color:#40FF40;'>prototype skin: <b>atari</b></div>
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
	class=MenuItem name=MenuPhile id=MenuPhile href="javascript:void(0)" onMouseOver="if (DesktopDialog || Drag || ReSize){return(false);}
								clearMenus();
								Phile.style.visibility='visible';
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
<!-- MENU: Phile -->
<span name=Phile id=Phile class=big style="visibility:hidden; top:19px; left:78px; position:absolute; width:260px; height:240px; z-index:65535; background-color:white; border:1px solid black;"> 
<a class=MenuItem href="javascript:void(0)" onClick="	Phile.style.visibility='hidden';
							clearMenus();
							reloadfile();
							">&nbsp;&nbsp;Reload&nbsp;File&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<font class=MenuBlank>--------------------------</font><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Phile.style.visibility='hidden';
							clearMenus();
							browseHere();
							">&nbsp;&nbsp;Open&nbsp;in&nbsp;Browser&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Phile.style.visibility='hidden';
							clearMenus();
							openeditor();
							">&nbsp;&nbsp;Open&nbsp;in&nbsp;HTML&nbsp;Editor&nbsp;&nbsp;[H]</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Phile.style.visibility='hidden';
							clearMenus();
							opensource();
							">&nbsp;&nbsp;Open&nbsp;in&nbsp;Code&nbsp;Editor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Phile.style.visibility='hidden';
							clearMenus();
							opendevatari();
							">&nbsp;&nbsp;Open&nbsp;in&nbsp;Dev&nbsp;Edit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[I]</a><br>
<font class=MenuBlank>--------------------------</font><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Phile.style.visibility='hidden';
							clearMenus();
							saveclose();
							">&nbsp;&nbsp;Save&nbsp;&&nbsp;Exit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Phile.style.visibility='hidden';
							clearMenus();
							saveas();
							">&nbsp;&nbsp;Save&nbsp;As...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[A]</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Phile.style.visibility='hidden';
							clearMenus();
							save();
							">&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[S]</a><br>
<font class=MenuBlank>--------------------------</font><br>
<a class=MenuItem href="?go=$dir_e" onClick="		Phile.style.visibility='hidden';
							clearMenus();
							saveEditer();
							">&nbsp;&nbsp;Exit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[Q]</a><br>
</span>
<!-- MENU: Edit -->
<span name=Edit id=Edit class=big style="visibility:hidden; top:19px; left:138px; position:absolute; width:210px; height:200px; z-index:65535; background-color:white; border:1px solid black;"> 
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
							storeLines();
							linenumber(prompt('Goto Line Number:',currentLine()));
							">&nbsp;&nbsp;Goto...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
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
HTML;
  if(filesize("$dir/$file")>$max_edit_size) 
    $msg = "File size exceeds the limit of $max_edit_size bytes<br>Have the Site Admin edit config.php to customize this";
  else{
    chdir($dir);
    $ficon = atarieditfile($file);
    print <<<HTML
<!-- ICON: editing file -->
<title>{$file} in {$folder} - Editing - Atari - PHP Navigator</title>
<!-- div id=eFile style="top:20px; left:2px; position:absolute; width:100px; height:100px; font-size:0px; border:1px solid black;"><table border=0 width=100><tr><td width=100% onmousedown=loadtd(this)>{$ficon}</td></tr></table></div>-->
<table id=eFile border=0 style="top:20px; left:2px; position:absolute; font-size:0px;"><tr><td onmousedown=loadtd(this)>{$ficon}</td></tr></table>
<!-- WINDOW: Editor -->
<span name=OpenEditor id=OpenEditor xHnd=0 zid=2 minW=125 minH=160 style="position:absolute; display:{$windows['OpenEditor'][8]}; top:{$windows['OpenEditor'][1]}; left:{$windows['OpenEditor'][2]}; width:{$windows['OpenEditor'][3]}; height:{$windows['OpenEditor'][4]}; z-index:{$windows['OpenEditor'][10]}; background-color:white;" 
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenEditor); data.focus();"
> 
<table name=OpenEditorW id=OpenEditorW valign=top border=1 cellpadding=0 cellspacing=0 style="background-color:white; width:{$windows['OpenEditor'][3]}; height:{$windows['OpenEditor'][4]};border-collapse:collapse; border:1px solid black; border-top:none;  border-top: 0px;">
  <tr>
    <td style="border:none; cursor:default; font-size:0px; width:20px; height:0px"></td>
    <td name=OpenEditorC id=OpenEditorC style="border:none; cursor:default; font-size:0px; width:{$windows['OpenEditor'][5]};"></td>
    <td style="border:none; cursor:default; font-size:0px; width:20px;"></td>
    <td style="border:none;cursor:default; font-size:0px; width:20px;"></td>
  </tr>
  <tr>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};" height=21 
onClick="if (DesktopDialog){return(false);} OpenEditor.style.display='none'; clearMenus(); DesktopDialog=false;"><img src="skins/atari/images/CLOSE.GIF" border=0></td>
    <td class=big colspan=2 nowrap align=center style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};"
 onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenEditor); dragWindow(OpenEditor,event);"
>Editing {$file}</td>
    <td align=center valign=middle style="border:1px solid black; cursor: default;font-family: {$font_large}; font-size: {$font_size};"
onClick="if (DesktopDialog){return(false);} toFront(OpenEditor); edMaxMin(); data.focus(); event.cancelBubble=true;"><img name=OpenEditorMM id=OpenEditorMM src="skins/atari/images/MAX.GIF" border=0></td>
  </tr>
  <tr>
    <td name=OpenEditorName id=OpenEditorName class=big colspan=4 align=left style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};" height=21>&nbsp;</td>
  </tr>
  <tr>
    <td name=OpenEditorArea id=OpenEditorArea class=area align=left valign=top colspan=3 rowspan=3 style="border:1px solid black; overflow:hidden;"
 onMouseWheel="mwOpenEditor(event);"
><div name=OpenEditorBody id=OpenEditorBody style="border:none; height:{$windows['OpenEditor'][6]}; overflow: hidden;"><div id=Editor class=Editor style="border:none; w idth:100%; he ight:100%; o verflow: hidden;"
><textarea name=data id=data rows=10000 cols=1024 onChange="" onBlur="scrollSetBoth('OpenEditor',this)" style="width:100%; height:100%; border:none; padding:0px; margin:0px; {$ed_fix} height:{$windows['OpenEditor'][6]}" wrap={$word_wrap} >
HTML;

#------------ FILE CONTENTS -------------

    print htmlentities(file_get_contents("$file"));

#----------------------------------------

    print <<<HTML
</textarea></div></td>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};" height=20 
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenEditor); ScrollBar=true; scrollIt=setTimeout('scrollClick(-(imgH),OpenEditorScrollV,data)',50);"
 onMouseUp="ScrollBar=false; data.focus();"><img src="skins/atari/images/UP.GIF" border=0></td>
  </tr>
  <tr>
    <td name=OpenEditorScroll id=OpenEditorScroll valign=top style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size}; height:{$windows['OpenEditor'][7]};"><div style="width:20px; height:100%; position:relative;" 
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenEditor); scrollPage(OpenEditorScrollV,event,data);"
><div name=OpenEditorScrollV id=OpenEditorScrollV class=ScrollV 
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenEditor); dragScroll(OpenEditorScrollV,event,data);"
 onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenEditor); data.focus(); e=event?event:window.event; e.cancelBubble=true;"></div></div></td>
  </tr>
  <tr>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};" height=20 
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenEditor); ScrollBar=true; scrollIt=setTimeout('scrollClick(imgH,OpenEditorScrollV,data)',50);"
 onMouseUp="ScrollBar=false; data.focus();"><img src="skins/atari/images/DOWN.GIF" border=0></td>
  </tr>
  <tr>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};" height=20 
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenEditor); ScrollBar=true; scrollIt=setTimeout('scrollClick(-(imgW),OpenEditorScrollH,data)',50);"
 onMouseUp="ScrollBar=false; data.focus();"><img src="skins/atari/images/LEFT.GIF" border=0></td>
    <td nowrap style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};"><div style="width:100%; height:20px; position:relative;" 
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenEditor); scrollPage(OpenEditorScrollH,event,data);"
><div name=OpenEditorScrollH id=OpenEditorScrollH class=ScrollH
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenEditor); dragScroll(OpenEditorScrollH,event,data);"
 onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenEditor); data.focus(); e=event?event:window.event; e.cancelBubble=true;"></div></div></td>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};" 
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenEditor); ScrollBar=true; scrollIt=setTimeout('scrollClick(imgW,OpenEditorScrollH,data)',50);"
 onMouseUp="ScrollBar=false; data.focus();"><img src="skins/atari/images/RIGHT.GIF" border=0></td>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};" 
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenEditor); data.focus(); event.cancelBubble=true;"
 onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenEditor); windowReSize(OpenEditor,event); event.cancelBubble=true;"
><img src="skins/atari/images/RESIZE.GIF" border=0
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenEditor); data.focus(); event.cancelBubble=true;"
 onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenEditor); windowReSize(OpenEditor,event); event.cancelBubble=true;"
  onDrag="event.cancelBubble=true;"
></td>
  </tr>
</table>
<script language=JavaScript>
EditWinNum = regWindow('OpenEditor',OpenEditor);
function edMaxMin(){
	MaxMin(EditWinNum);
}

fname = "{$file}";
ficon = document.getElementById('eFile').getElementsByTagName('img')[0];
oldficon = ficon;
restoreEditor();
scrollSetBoth('OpenEditor',data);

function mwOpenEditor(event){
	if (DesktopDialog){return(false);}
	clearMenus();
	toFront(OpenEditor);
	ScrollBar=true;
	doMouseWheel(imgH,event,OpenEditor,data);
	ScrollBar=false;
	data.focus();
}

if((/Firefox\/3/i.test(navigator.userAgent)) || (/Iceweasel\/3/i.test(navigator.userAgent))){
	 OpenEditorArea.addEventListener("DOMMouseScroll", mwOpenEditor, false)
}

</script>
</span>
<input type=hidden name=action value=""><input type=hidden name=file value="$file_e">
HTML;
}

# if there is no file or its too big there is NO window or icon

print <<<HTML
<!-- WINDOW: Output -->
<span name=OpenOutput id=OpenOutput xHnd=0 zid=2 minW=305 minH=100 style="position:absolute; display:{$windows['OpenOutput'][8]}; top:{$windows['OpenOutput'][1]}; left:{$windows['OpenOutput'][2]}; width:{$windows['OpenOutput'][3]}; height:{$windows['OpenOutput'][4]}; z-index:{$windows['OpenOutput'][10]}; background-color:white;" 
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenOutput);"
 onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenOutput); dragWindow(OpenOutput,event);"
> 
<table name=OpenOutputW id=OpenOutputW valign=top border=1 cellpadding=0 cellspacing=0 style="background-color:white; width:{$windows['OpenOutput'][3]}; height:{$windows['OpenOutput'][4]}; border-collapse:collapse; border:1px solid black; border-top: 0px;">
  <tr>
    <td style="border:none; cursor:default; font-size:0px; width:20px; height:0px"></td>
    <td name=OpenOutputC id=OpenOutputC style="border:none; cursor:default; font-size:0px; width:{$windows['OpenOutput'][5]};"></td>
    <td style="border:none; cursor:default; font-size:0px; width:20px;"></td>
    <td style="border:none; cursor:default; font-size:0px; width:20px;"></td>
  </tr>
  <tr>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size}; height:21px;"
onClick="if (DesktopDialog){return(false);} OpenOutput.style.display='none'; clearMenus(); DesktopDialog=false;" ><img src="skins/atari/images/CLOSE.GIF" border=0></td>
    <td class=big colspan=2 nowrap align="center" style="border: 1px solid black; border-top: none; cursor: default; font-family: {$font_large}; font-size: {$font_size};"
 onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenOutput); dragWindow(OpenOutput,event);"
>Information Output</td>
    <td align=center valign=middle style="border: none; border-left: 1px solid black; border-bottom: 1px solid black; cursor: default; font-family: {$font_large}; font-size: {$font_size};"
onClick="if (DesktopDialog){return(false);} toFront(OpenOutput); opMaxMin(); event.cancelBubble=true;"><img name=OpenOutputMM src="skins/atari/images/MAX.GIF" border=0></td>
  </tr>
  <tr>
    <td name=OpenOutputName id=OpenOutputName class=big colspan=4 align=left style="border: none; border-bottom: 1px solid black; cursor: default; font-family: {$font_large}; font-size: {$font_size};" height=21>&nbsp;</td>
  </tr>
  <tr>
    <td name=OpenOutputBody id=OpenOutputBody class=area align=left valign=top colspan=3 rowspan=3 bgcolor=gray style="border:none; height:{$windows['OpenOutput'][6]};"><div align=left valign=top id=OutputList name=OutputList style="width:100%; height:100%; overflow: hidden;">
<div name=thestatus id=thestatus style="width:100%; cursor:default; font-family: {$font_small}; font-size: xx-small;"><center>
HTML;

#--------------- MESSAGES ---------------

   print "$msg<br>";

#----------------------------------------

   print <<<HTML
</div></td>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size: {$font_size};" height=20 onClick="OutputList.scrollTop=OutputList.scrollTop-({$font_size_small});"><img src="skins/atari/images/UP.GIF" border=0></td>
  </tr>
  <tr>
    <td id=OpenOutputScroll style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:0px; height:{$windows['OpenOutput'][7]}"></td>
  </tr>
  <tr>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};" height=20 onClick="OutputList.scrollTop=OutputList.scrollTop+({$font_size_small});"><img src="skins/atari/images/DOWN.GIF" border=0></td>
  </tr>
  <tr>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};" height=20 onClick="OutputList.scrollLeft=OutputList.scrollLeft-(imgW);"><img src="skins/atari/images/LEFT.GIF" border=0></td>
    <td nowrap align=center style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};">&nbsp;</td>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};" onClick="OutputList.scrollLeft=OutputList.scrollLeft+(imgW);"><img src="skins/atari/images/RIGHT.GIF" border=0></td>
    <td
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenOutput); event.cancelBubble=true;"
 onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenOutput); windowReSize(OpenOutput,event); event.cancelBubble=true;"
 align="center" style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};" ><img src="skins/atari/images/RESIZE.GIF" border=0
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenOutput); event.cancelBubble=true;"
 onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenOutput); windowReSize(OpenOutput,event); event.cancelBubble=true;"
></td>
  </tr>
</table>
<script language=JavaScript>
OutputWinNum = regWindow('OpenOutput',OpenOutput);
function opMaxMin(){
	MaxMin(OutputWinNum);
}
</script>
</span>
<!-- WINDOW: Calculator -->
<span name=ModalCalc id=ModalCalc xHnd=0 zid=2 minW=216 minH=224 style="position:absolute; display:{$windows['ModalCalc'][8]}; top:{$windows['ModalCalc'][1]}; left:{$windows['ModalCalc'][2]}; width:{$windows['ModalCalc'][3]}; height:{$windows['ModalCalc'][4]}; z-index:{$windows['ModalCalc'][10]};" 
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(ModalCalc);"
> 
<table name=ModalCalcW id=ModalCalcW class=big valign=top border=1 cellpadding=0 cellspacing=0 style="background-color:white; border-collapse:collapse; border:1px solid black; border-top: 0px;">
  <tr>
    <td style="border:none; cursor:default; font-size:0px; width:20px; height:0px"></td>
    <td name=ModalCalcC id=ModalCalcC style="border:none; cursor:default; font-size:0px; width:{$windows['ModalCalc'][5]};"></td>
    <td style="border:none; cursor:default; font-size:0px; width:20px;"></td>
    <td style="border:none; cursor:default; font-size:0px; width:1px;"></td>
  </tr>
  <tr>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size}; height:21px;"
onClick="if (DesktopDialog){return(false);} ModalCalc.style.display='none'; clearMenus(); DesktopDialog=false;" ><img src="skins/atari/images/CLOSE.GIF" border=0></td>
    <td colspan=3 nowrap align=center style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size}; padding-right:2px"
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(ModalCalc); dragWindow(ModalCalc,event);"
>Calculator</td>
  </tr>
  <tr>
    <td name=ModalCalcName id=ModalCalcName colspan=4 align=right style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size}; height:21px;" height=21>&nbsp;</td>
  </tr>
  <tr>
    <td name=ModalCalcBody id=ModalCalcBody align=left valign=top colspan=3 rowspan=3 height=184 style="border:none; border-right:1px solid black; h eight:{$windows['ModalCalc'][6]};"><div align=left valign=top style="width:100%; height:100%; overflow:hidden; font-family:{$font_large}; font-size:{$font_size};">
<table border=1 cellpadding=1 cellspacing=2 style="border:25px solid lightgray; border-top:1px solid lightgray; font-family:{$font_large}; font-size:{$font_size}; cursor:default;">
<tr><td align=center width=20 onMouseDown="this.style.color='white';this.style.backgroundColor='black';" onMouseUp="this.style.color='black';this.style.backgroundColor='white';" onClick="ModalCalcName.innerHTML=ModalCalcName.innerHTML+'7';">7</td><td align=center width=20 onMouseDown="this.style.color='white';this.style.backgroundColor='black';" onMouseUp="this.style.color='black';this.style.backgroundColor='white';" onClick="ModalCalcName.innerHTML=ModalCalcName.innerHTML+'8';">8</td><td align=center width=20 onMouseDown="this.style.color='white';this.style.backgroundColor='black';" onMouseUp="this.style.color='black';this.style.backgroundColor='white';" onClick="ModalCalcName.innerHTML=ModalCalcName.innerHTML+'9';">9</td><td align=center width=20 onMouseDown="this.style.color='white';this.style.backgroundColor='black';" onMouseUp="this.style.color='black';this.style.backgroundColor='white';" onClick="ModalCalcName.innerHTML=ModalCalcName.innerHTML+'+';">+</td><td align=center width=50 onMouseDown="this.style.color='white';this.style.backgroundColor='black';" onMouseUp="this.style.color='black';this.style.backgroundColor='white';" onClick="ModalCalcName.innerHTML=ModalCalcName.innerHTML+'%';">MOD</td></tr>
<tr><td align=center onMouseDown="this.style.color='white';this.style.backgroundColor='black';" onMouseUp="this.style.color='black';this.style.backgroundColor='white';" onClick="ModalCalcName.innerHTML=ModalCalcName.innerHTML+'4';">4</td><td align=center onMouseDown="this.style.color='white';this.style.backgroundColor='black';" onMouseUp="this.style.color='black';this.style.backgroundColor='white';" onClick="ModalCalcName.innerHTML=ModalCalcName.innerHTML+'5';">5</td><td align=center onMouseDown="this.style.color='white';this.style.backgroundColor='black';" onMouseUp="this.style.color='black';this.style.backgroundColor='white';" onClick="ModalCalcName.innerHTML=ModalCalcName.innerHTML+'6';">6</td><td align=center onMouseDown="this.style.color='white';this.style.backgroundColor='black';" onMouseUp="this.style.color='black';this.style.backgroundColor='white';" onClick="ModalCalcName.innerHTML=ModalCalcName.innerHTML+'-';">-</td><td align=center onMouseDown="this.style.color='white';this.style.backgroundColor='black';" onMouseUp="this.style.color='black';this.style.backgroundColor='white';" onClick="ModalCalcName.innerHTML=ModalCalcName.innerHTML+'sin(';">SIN</td></tr>
<tr><td align=center onMouseDown="this.style.color='white';this.style.backgroundColor='black';" onMouseUp="this.style.color='black';this.style.backgroundColor='white';" onClick="ModalCalcName.innerHTML=ModalCalcName.innerHTML+'1';">1</td><td align=center onMouseDown="this.style.color='white';this.style.backgroundColor='black';" onMouseUp="this.style.color='black';this.style.backgroundColor='white';" onClick="ModalCalcName.innerHTML=ModalCalcName.innerHTML+'2';">2</td><td align=center onMouseDown="this.style.color='white';this.style.backgroundColor='black';" onMouseUp="this.style.color='black';this.style.backgroundColor='white';" onClick="ModalCalcName.innerHTML=ModalCalcName.innerHTML+'3';">3</td><td align=center onMouseDown="this.style.color='white';this.style.backgroundColor='black';" onMouseUp="this.style.color='black';this.style.backgroundColor='white';" onClick="ModalCalcName.innerHTML=ModalCalcName.innerHTML+'*';">x</td><td align=center onMouseDown="this.style.color='white';this.style.backgroundColor='black';" onMouseUp="this.style.color='black';this.style.backgroundColor='white';" onClick="ModalCalcName.innerHTML=ModalCalcName.innerHTML+'cos(';">COS</td></tr>
<tr><td align=center onMouseDown="this.style.color='white';this.style.backgroundColor='black';" onMouseUp="this.style.color='black';this.style.backgroundColor='white';" onClick="ModalCalcName.innerHTML=ModalCalcName.innerHTML+'/100*';">%</td><td align=center onMouseDown="this.style.color='white';this.style.backgroundColor='black';" onMouseUp="this.style.color='black';this.style.backgroundColor='white';" onClick="ModalCalcName.innerHTML=ModalCalcName.innerHTML+'0';">0</td><td align=center onMouseDown="this.style.color='white';this.style.backgroundColor='black';" onMouseUp="this.style.color='black';this.style.backgroundColor='white';" onClick="ModalCalcName.innerHTML=ModalCalcName.innerHTML+'^';">^</td><td align=center onMouseDown="this.style.color='white';this.style.backgroundColor='black';" onMouseUp="this.style.color='black';this.style.backgroundColor='white';" onClick="ModalCalcName.innerHTML=ModalCalcName.innerHTML+'/';">/</td><td align=center onMouseDown="this.style.color='white';this.style.backgroundColor='black';" onMouseUp="this.style.color='black';this.style.backgroundColor='white';" onClick="ModalCalcName.innerHTML=ModalCalcName.innerHTML+'tan(';">TAN</td></tr>
<tr><td align=center colspan=5 onMouseDown="this.style.color='white';this.style.backgroundColor='black';" onMouseUp="this.style.color='black';this.style.backgroundColor='white';" onClick="ModalCalcName.innerHTML=eval(ModalCalcName.innerHTML);">=</td></tr>
<tr><td align=center onMouseDown="this.style.color='white';this.style.backgroundColor='black';" onMouseUp="this.style.color='black';this.style.backgroundColor='white';" onClick="ModalCalcName.innerHTML=ModalCalcName.innerHTML+'.';">.</td><td align=center onMouseDown="this.style.color='white';this.style.backgroundColor='black';" onMouseUp="this.style.color='black';this.style.backgroundColor='white';" onClick="ModalCalcName.innerHTML=ModalCalcName.innerHTML+'(';">(</td><td align=center onMouseDown="this.style.color='white';this.style.backgroundColor='black';" onMouseUp="this.style.color='black';this.style.backgroundColor='white';" onClick="ModalCalcName.innerHTML=ModalCalcName.innerHTML+')';">)</td><td align=center onMouseDown="this.style.color='white';this.style.backgroundColor='black';" onMouseUp="this.style.color='black';this.style.backgroundColor='white';" onClick="ModalCalcName.innerHTML=ModalCalcName.innerHTML.substring(0,ModalCalcName.innerHTML.length-1);">C</td><td align=center onMouseDown="this.style.color='white';this.style.backgroundColor='black';" onMouseUp="this.style.color='black';this.style.backgroundColor='white';" onClick="ModalCalcName.innerHTML='';">CE</td></tr>
</table></td>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};" height=20></td>
  </tr>
  <tr>
    <td name=ModalCalcScroll id=ModalCalcScroll style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size}; height:{$windows['ModalCalc'][7]};"></td>
  </tr>
  <tr>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};" height=20></td>
  </tr>
  <tr>
    <td colspan=4 align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:0px; height:2px;" height=2></td>
  </tr>
</table>
<script language=JavaScript>
CalcWinNum = regWindow('ModalCalc',ModalCalc);
</script>
</span>
<!-- WINDOW: FontList -->
<span name=ModalFont id=ModalFont xHnd=0 zid=2 minW=265 minH=310 style="position:absolute; display:{$windows['ModalFont'][8]}; top:{$windows['ModalFont'][1]}; left:{$windows['ModalFont'][2]}; width:{$windows['ModalFont'][3]}; height:{$windows['ModalFont'][4]}; z-index:{$windows['ModalFont'][10]}; background-color:white;" 
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(ModalFont);"
> 
<table name=ModalFontW id=ModalFontW valign=top border=1 cellpadding=0 cellspacing=0 style="background-color:white; width:{$windows['ModalFont'][3]}; height:{$windows['ModalFont'][4]}; border-collapse:collapse; border:1px solid black; border-top:0px;">
  <tr>
    <td style="border:none; cursor:default; font-size:0px; width:20px; height:0px"></td>
    <td name=ModalFontC id=ModalFontC style="border:none; cursor:default; font-size:0px; width:{$windows['ModalFont'][5]};"></td>
    <td style="border:none; cursor:default; font-size:0px; width:20px;"></td>
    <td style="border:none; cursor:default; font-size:0px; width:20px;"></td>
  </tr>
  <tr>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size}; height:21px;"
onClick="if (DesktopDialog){return(false);} ModalFont.style.display='none'; clearMenus(); DesktopDialog=false;" ><img src="skins/atari/images/CLOSE.GIF" border=0></td>
    <td class=big colspan=3 nowrap align=center style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size}; padding-right:20px"
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(ModalFont); dragWindow(ModalFont,event);"
>Font List</td>
  </tr>
  <tr>
    <td name=ModalFontName id=ModalFontName class=big colspan=4 align=left style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};" height=21>double click to choose</td>
  </tr>
  <tr>
    <td name=ModalFontBody id=ModalFontBody class=area align=left valign=top colspan=3 rowspan=3 style="border:1px solid black; height:{$windows['ModalFont'][6]}; overflow:hidden; font-family:{$font_small}; font-size:{$font_size_small}"
 onMouseWheel="mwModalFont(event);"
><div align=left valign=top name=FontList id=FontList style="border:none; width:400px; height:{$windows['ModalFont'][6]}; overflow:hidden;">
HTML;

#------------- BASIC FONTS --------------

    $fonts = array('mono','monospace','fixed','terminal','courier','"courier new"','clean','"A.D. Mono"','"Andale Mono"','AvQest','"Bitstream Vera Sans Mono"','"Courier 10 Pitch"','"DejaVu Sans Mono"','Diablo','"Lucida Console"','"Lucida Sans Typewriter"','LucidaTypewriter','"Luxi Mono"','"Nimbus Mono L"');
    $text = array('1234567890-=!@#\$%^&*()_+','~{}|[]\\:;"\'&lt;>?,./','abcdefghijklmnopqrstuvwxyz','The&nbsp;Quick&nbsp;Brown&nbsp;Fox&nbsp;Jumps&nbsp;Over&nbsp;The&nbsp;Lazy&nbsp;Dog.');
    $sizes = array('6px','8pt','11px','16pt','20px');

    foreach($fonts as $font){
      print "<div class=PropHead>$font :</div>\n";
      foreach($sizes as $size){
        print "<div class=PropHead onDblClick=\"data.style.font='$size ".str_replace('"',"\\'",$font)."'; scrollSetBoth('OpenEditor',data);\" style='background-color:lightgray; cursor:default;'>&nbsp;$size</div>\n";
        print "<div class=PropInfo onDblClick=\"data.style.font='$size ".str_replace('"',"\\'",$font)."'; scrollSetBoth('OpenEditor',data);\" style='cursor:default;'>\n";
        print "<font style='font-family:$font;font-size:$size;'>".$text[0].$text[1]."<br>\n";
        print $text[2].strtoupper($text[2])."<br>\n";
        print $text[3]."<br>\n";
        print "</font></div>\n";
      }
    }

#----------------------------------------

    print <<<HTML
<div class=PropHead style="margin-bottom:1px;">&nbsp;</div>
</div></div></td>
    <td align=center valign=middle style="border:1px solid black; cursor: default; font-family: {$font_large}; font-size: {$font_size};" height=20 
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(ModalFont); ScrollBar=true; scrollIt=setTimeout('scrollClick(-(parseInt(\'{$font_size}\')),ModalFontScrollV,FontList)',50);"
 onMouseUp="ScrollBar=false;"><img src="skins/atari/images/UP.GIF" border=0></td>
  </tr>
  <tr>
    <td name=ModalFontScroll id=ModalFontScroll valign=top style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size}; height:{$windows['ModalFont'][7]};"
><div style="width:20px; height:100%; position:relative;" onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(ModalFont); scrollPage(ModalFontScrollV,event,FontList);"
><div name=ModalFontScrollV id=ModalFontScrollV class=ScrollV 
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(ModalFont); dragScroll(ModalFontScrollV,event,FontList);"
 onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(ModalFont); e=event?event:window.event; e.cancelBubble=true;"></div></div></td>
  </tr>
  <tr>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};" height=20 
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(ModalFont); ScrollBar=true; scrollIt=setTimeout('scrollClick(parseInt(\'{$font_size}\'),ModalFontScrollV,FontList)',50);"
 onMouseUp="ScrollBar=false;"><img src="skins/atari/images/DOWN.GIF" border=0></td>
  </tr>
  <tr>
    <td colspan=4 align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:0px; height:2px;" height=2
></td>
  </tr>
</table>
<script language=JavaScript>
FontWinNum = regWindow('ModalFont',ModalFont);
scrollSet(ModalFontScrollV,FontList);

function mwModalFont(event){
	if (DesktopDialog){return(false);}
	clearMenus();
	toFront(ModalFont);
	ScrollBar=true;
	doMouseWheel(imgH,event,ModalFont,FontList);
	ScrollBar=false;
}

if((/Firefox\/3/i.test(navigator.userAgent)) || (/Iceweasel\/3/i.test(navigator.userAgent))){
	 ModalFontBody.addEventListener("DOMMouseScroll", mwModalFont, false)
}

</script>
</span>
<!-- DIALOG: Desktop Inf.. -->
<div name=DesktopInfo id=DesktopInfo style="display:none; top:0px; left:0px; position:absolute; width:375px; height:336px; z-index:65535; background-color: white; border: 1px solid black; padding: 2px"> 
<table class=big valign=top border=0 width=100% height=100% bgcolor=white cellpadding=0 cellspacing=0 style="border-collapse:collapse; border:3px solid black;">
  <tr>
    <td width=100% height=100% nowrap align=center style="border:0px; cursor:default; font-family:{$font_large}; font-size:{$font_size};"><br>
GEM, Graphic Environment Manager<br><br>
webTOS Editor<br>
<font class=MenuBlank>-----------------------------</font><br>
<img src="skins/atari/images/IRLOGO.GIF" width=33 height=34 borber=0><br><br><br><br>
Copyright <b>&copy;</b> 2000-2009<br>
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
<input type=hidden name=dir value='$dir_e'>
</form>
<div style="display:none;">
<!-- MENU: Context -->
<table id=context class=context border=0 cellpadding=0 cellspacing=0 style="top:100px;{$font_fix}">
<tr id=conDir ><td class=contbar><a href="" class=contitem onClick="opendir();return(false);">&nbsp;<b>Open</b></a></td></tr>
<tr id=conSep0><td class=contbar><font class=MenuBlank>------------</font></td></tr>
<tr id=conRen ><td class=contbar><a href="" class=contitem onClick="rename(f);return(false);">&nbsp;Rename</a></td></tr>
<tr id=conDel ><td class=contbar><a href="" class=contitem onClick="delet(f);return(false);">&nbsp;Delete</a></td></tr>
<tr id=conCopy><td class=contbar><a href="" class=contitem onClick="copy(f);return(false);">&nbsp;Copy to</a></td></tr>
<tr id=conMove><td class=contbar><a href="" class=contitem onClick="move(f);return(false);">&nbsp;Move to</a></td></tr>
<tr id=conSep1><td class=contbar><font class=MenuBlank>------------</font></td></tr>
<tr id=conNewF><td class=contbar><a href="" class=contitem onClick="atarinewfile(f);return(false);">&nbsp;New File</a></td></tr>
<tr id=conNew ><td class=contbar><a href="" class=contitem onClick="atarinewfolder(f);return(false);">&nbsp;New Folder</a></td></tr>
<!--
<tr id=conSep2><td class=contbar><font class=MenuBlank>------------</font></td></tr>
<tr id=conNewF><td class=contbar><a href="" class=contitem onClick="atarithumbnail();return(false);">&nbsp;Thumbnail</a></td></tr>
<tr id=conNew ><td class=contbar><a href="" class=contitem onClick="thumbnail(f);return(false);">&nbsp;Preview</a></td></tr>
<tr id=conSep3><td class=contbar><font class=MenuBlank>------------</font></td></tr>
<tr id=conNewF><td class=contbar><a href="" class=contitem onClick="extract(f);return(false);">&nbsp;Extract</a></td></tr>
<tr id=conNew ><td class=contbar><a href="" class=contitem onClick="compress(f);return(false);">&nbsp;Compress</a></td></tr>
-->
</table>
</div>
<!-- CODE: Last -->
<script language=JavaScript>
skintype='$groupimgs';
imgW = $icn_size[0];
imgH = $icn_size[1];

width = (window.innerWidth) ? window.innerWidth : document.body.clientWidth;
height = (window.innerHeight) ? window.innerHeight : document.body.clientHeight;

document.cookie = 'defaults' + DesktopName + '={$winDefaults};';
</script>
</body>
</html>
HTML;

  www_page_close();
  die();
}

#------------- EXPLORER BIT --------------

  $desktop = 'Explorer';

  $dir_bh = browseHere($dir);

  print <<<HTML
<script type=text/javascript>
function browseHere(){
//alert(decodeURIComponent(f.dir.value).replace(/{$dir_bh[0]}/,'{$dir_bh[1]}')+'/');
  if (fname=='') {
    extWindow(decodeURIComponent(f.dir.value).replace(/{$dir_bh[0]}/,'{$dir_bh[1]}')+'/');
    return; }
  if(oldficon.getAttribute('spec').indexOf('d')>0) 
    extWindow(decodeURIComponent(f.dir.value).replace(/{$dir_bh[0]}/,'{$dir_bh[1]}')+'/'+fname+'/');
  else
    extWindow(decodeURIComponent(f.dir.value).replace(/{$dir_bh[0]}/,'{$dir_bh[1]}')+'/'+fname);
}

function atarifile(ficon,fdetails,e){
  window.clearTimeout(timer);
// IE does not support hasAttribute()
{$fiIE}  if(oldficon && oldficon!=null)
{
{$fiIE7}  if(oldficon && oldficon!=null && eval(oldficon.getAttribute('aid')+'!=null'))
  {
{$fi}    if(oldficon.hasAttribute('atariimg'))
    {

    if(oldficon.getAttribute('atariimg')!=''){
      if(oldficon.getAttribute('atariimg')==oldficon.getAttribute('atarisel')) {
        oldficon.style.background = 'none';
        if(oldficon.getAttribute('spec').indexOf('t')>0) { oldficon.className = 'ficon'; }
      }else{
        oldficon.src = oldficon.getAttribute('atariimg');
      }
      eval(oldficon.getAttribute('aid')+".className = 'name';");
    }else{
      oldficon.style.background="none";
    }
    if(oldficon.parentNode){
      oldficon.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.style.position = '';
      oldficon.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.style.top = '';
      oldficon.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.style.left = '';
    }

//{$fiM}
    }
  }
}
//  }

  oldficon = ficon;

  fname = ficon.title;
//  if(ficon.hasAttribute('atariimg')){
  if(ficon.getAttribute('atariimg')!=''){
    if(ficon.getAttribute('atariimg')==ficon.getAttribute('atarisel')) {
      ficon.style.background = 'black';
      if(ficon.getAttribute('spec').indexOf('t')>0) { ficon.className = 'ficonSel'; }
    }else{
      ficon.src = ficon.getAttribute('atarisel');
    }
    eval(ficon.getAttribute('aid')+".className = 'nameSel';");
  }else{
    ficon.style.background = 'black';
  }
//alert(ficon.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.tagName);
  ficon.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.style.position = 'relative';
  ficon.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.style.top = '0px';
  ficon.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.style.left = '0px';

  if(fdetails==''){
    info.innerHTML = ficon.alt;
    thestatus.innerHTML = "<center>Double click to open: <b>'"+fname+"'</b><br>Click name to download";
  }else{
    info.innerHTML = fdetails;
    thestatus.innerHTML = "<center>Double click to download: <b>'"+fname+"'</b><br>Click name to open";
  }

  if(ficon.getAttribute('spec').indexOf('z')>0) 
	timer=window.setTimeout("getzipinfo();",100);
  if(ficon.getAttribute('spec').indexOf('d')>0) 
	timer=window.setTimeout("getfolderinfo();",100);	
  if(ficon.getAttribute('spec').indexOf('t')>0)
	timer=window.setTimeout("getimageinfo();",100);
}

function atarithumbnail(){
  if(oldficon.getAttribute("spec").indexOf("t")>0) {
    filepath = encode(f.dir.value+"/"+fname);
    OpenViewerName.innerHTML = fname;
    normthumb(filepath);
  }
}

function normthumb(xfn){
  xSize = pxStrip(OpenViewerScroll.style.height);
  Viewer.innerHTML = "<center><br><img onload=\"scrollSetBoth('OpenViewer',Viewer);\" src='thumb.php?img="+filepath+"&size="+xSize+"' alt=' Loading... ' width="+xSize+" height="+xSize+" title=' click for actual size ' style='vertical-align:middle;' onClick=zoomthumb('"+xfn+"') >";
  Viewer.scrollTop = 0;
  Viewer.scrollLeft = 0;
//  scrollSetBoth('OpenViewer',Viewer);
}

function zoomthumb(xfn){
  Viewer.innerHTML = "<img onload=\"scrollSetBoth('OpenViewer',Viewer);\" src='thumb.php?img="+filepath+"&size=real' alt=' Loading Full Size... ' title=' click for thumbnail ' onClick=normthumb('"+xfn+"') >";
}

function atariarrange(arrang){
  document.cookie="navphp_arrange="+arrang;
  gotodir(f);	//refresh
}

function clearMenus(){
	Desk.style.visibility = 'hidden';
	MenuDesk.style.color = 'black';
	MenuDesk.style.background = 'white';
	Phile.style.visibility = 'hidden';
	MenuPhile.style.color = 'black';
	MenuPhile.style.background = 'white';
	View.style.visibility = 'hidden';
	MenuView.style.color = 'black';
	MenuView.style.background = 'white';
	Options.style.visibility = 'hidden';
	MenuOptions.style.color = 'black';
	MenuOptions.style.background = 'white';
	Help.style.visibility = 'hidden';
	MenuHelp.style.color = 'black';
	MenuHelp.style.background = 'white';
	return;
}

function dcheck(eObj){
	if (DesktopDialog){return(false);}
	if (!eObj) eObj = window.event;
	xObj=(eObj.target) ? eObj.target : eObj.srcElement;
	if (xObj.id=='DesktopBody' || xObj.tagName=='body'){
		clearMenus();
		hidecontext();
	}
	if (xObj.id=='upfile'){
		return(true);
	}
	return(false);
}

function ctrlCheck(eObj){
	if (!eObj) eObj = window.event;
	if (eObj.ctrlKey) return(true)
	return(false);
}

function fixIEmenus(){
  xMenu = document.getElementsByTagName('span');
  for(i=0;i<xMenu.length;i++){
    if(xMenu[i].children.length>0){
      c = xMenu[i].firstChild;
      if(c.tagName=='A'){
        if(c.className=='MenuItem'){
          xMenu[i].style.width = (parseInt(xMenu[i].style.width)+2)+'px';
        }
      }
    }
  }
}

function setini(){
  folderinfo.innerHTML = 'Total files: ' + f.total.value + '<br>Permissions: ' + f.perms.value;
  document.onclick = dcheck;
  PhileList.oncontextmenu = showcontext;
  document.oncontextmenu = dcheck;
  {$menufix}
}

function atarinewfolder()
{
 newname=prompt("Enter the new folder name:","new_folder");
 if(newname && newname.indexOf(' ')!=0)
 {
	newname = encode(newname);
	dir = encode(f.dir.value);
	details=''; if(location.href.indexOf('details.php')!=-1) details='&details=1';
	http.open("get", "newfolder.php?change="+newname+"&dir="+dir+details+"&ajax=1&rand=" + xTime());
	http.onreadystatechange = showatarinew;
	http.send("");
	fo_new=fo;
 }
}

function atarinewfile(){
 newname=prompt("Enter the new file name:","new_file");
 if(newname && newname.indexOf(' ')!=0)
 {
	newname = encode(newname);
	dir = encode(f.dir.value);
	details=''; if(location.href.indexOf('details.php')!=-1) details='&details=1';
	http.open("get", "newfile.php?change="+newname+"&dir="+dir+details+"&ajax=1&rand=" + xTime());
	http.onreadystatechange = showatarinew;
	http.send("");
	fo_new=fo;
 }
}

function dom_atarinew(file_status){
//  xObj = document.getElementById('startHere');
  xObj = document.getElementById('PhileList');
  nID = 'i' + xTime();
//  if (window.innerHeight) {
    xElement = document.createElement('span');
    xElement.setAttribute('id',nID);
    xElement.setAttribute('class','iconFile');
//  }else{
//    xElement = document.createElement('<span id='+xIid+' onMouseDown="'+xIomdown+'" onMouseOver="'+xIomover+'" onMouseOut="'+xIomout+'" alt="'+xIalt+'" src='+xIsrc+' onLoad="'+xIoload+'" onError="'+xIoerror+'" style ="'+xIstyle+'" />');
//  }
//  xObj.parentNode.insertAfter(xElement,xObj);
  xObj.insertBefore(xElement,xObj.firstChild);
//     print "<span class=iconFile $ disp><table border=0 width=100><tr><td width=100% onmousedown=loadtd(this)>";
//     ataristatus($file);	# function to print file icon & details
//     print "</td></tr></table></span>";

  xObj = document.getElementById(nID);
  xObj.innerHTML = '<table border=0 width=100><tr><td width=100% onmousedown=loadtd(this)>' + file_status + '</td></tr></table>';
}

function showatarinew() {
    if(http.readyState == 4){
	if(http.status!=200) {
	    thestatus.innerHTML=error_string+"<font color=red><b>Error!:</b>  "+http.status+" "+http.statusText+". Please retry.</font>";
	    newtooltip(thestatus.innerHTML,10000);
	    return 0;
	    }
	var reply = http.responseText;
        var update = new Array();
        update = reply.split('|');
        thestatus.innerHTML = update[2];
        if(update[1]==1) {
		dom_atarinew(update[3]);
		info.innerHTML="";
		unload();
		newtooltip(info_string+update[2],5000);	//show tooltip
	}else{
		newtooltip(error_string+update[2],5000);	//show tooltip
		alert(update[2]);
        }
    }
    else thestatus.innerHTML = "Processing..";
}

function atariUp(xEvnt){
  if(ctrlCheck(xEvnt)) return;
  location.href = '?action=Up&dir=' + encode(f.dir.value);
}

function explorer(xExplorer,xEvnt){
  if(ctrlCheck(xEvnt)) return;
  location.href = xExplorer + '.php?go=' + encode(f.dir.value);
}

function opendevatari(){
if(fname!="") {
  window.open("DevEdit/atarieditor.php?file=" + encode(fname) + "&dir="+encode(f.dir.value), "DevEdit"+xTime(),"width=750, height=500, left=10, top=10, resizable=yes, scrollbars=no, location=no, toolbar=no,menubar=no");
 }
}

function startUp(){
return;
  xCookie = 'windows' + DesktopName + '=';
  xCst = document.cookie.indexOf(xCookie);
  if (xCst==-1){
    OpenDrive.style.display='inline';
    OpenOutput.style.display='inline';
    ModalPhileProp.style.display='inline';
  }
}

DesktopName = '{$desktop}';

</script>
</head>
{$body}
<form action='?' method=POST name=f >
HTML;

//new    windowOrder,    X,   Y,    W,    H, W-63, H-65, H-65-42, display, id, z-index
  $winDefaults = '|0,450px,20px,395px,115px,332px,52px,10px,inline,OpenOutput,2|1,50px,650px,263px,283px,200px,238px,196px,inline,ModalPhileProp,2|2,50px,20px,563px,367px,500px,302px,260px,inline,OpenDrive,2|3,70px,620px,300px,298px,237px,235px,192px,none,OpenViewer,2|4,400px,650px,283px,135px,220px,72px,30px,none,OpenUpload,2';
  loadWindows($desktop);

  print"<div name=warnings id=warnings style='display:none;'>";

#------------------ACTIONS----------------

if($action=="Up")
 up($dir);

if($action=="Upload")
 upload($dir);
 
if($action=="Save")
 save($file);

if($action=="New folder")
 require_once("newfolder.php");

if($action=="New file")
 require_once("newfile.php");
 
if($action=="Chmode")
 require_once("chmod.php");
 
if($action=="Delete")
 require_once("delete.php");
 
if($action=="Rename")
 require_once("rename.php");
 
if($action=="Copy")
 require_once("copy.php");

if($action=="Move")
 require_once("move.php");

if($action=="Extract")
 require_once("extract.php");

if($dir)
 chdir($dir);
if($action=="Open"&&is_dir("$dir/$file"))
 @chdir($file);

#-----Calculate Max Upload Size--
  $size_str = ini_get('upload_max_filesize');
  $z=0; $size=0;
  while(ctype_digit($size_str[$z])) {$size.=$size_str[$z]; $z++;}
  $size = intval($size);
  $max_size = $size.$size_str[$z];
  if($size_str[$z]=="G"||$size_str[$z]=="g")     $size = $size*1024*1024*1024;
  elseif($size_str[$z]=="M"||$size_str[$z]=="m") $size = $size*1024*1024;
  elseif($size_str[$z]=="K"||$size_str[$z]=="k") $size = $size*1024;
#----------------------------------------

$dir=getcwd();
$dir_e = urlencode($dir);
$homedir_e = urlencode($homedir);

print "</div>
";

$dispdir = gethostedpath($dir);

$xTitle = folderin($dir);

$gi   = $GLOBALS['groupimgs'];


#--------------- NEW STUFF --------------

print <<<HTML
<input type=hidden name=dir value="$dir"><title>{$xTitle} - Browsing - Atari - PHP Navigator</title>
<div style='position:absolute; font-size:0.7em; bottom:10px; right:10px; color:#80FF80'>prototype skin: <b>atari</b></div>
<!-- MENU: main menu-->
<span style="position:absolute; left:0px; top:0px; width:100%;"><table border=0 width=100% height=20 cellpadding=0 cellspacing=0>
  <tr width=100% height=100%>
    <td width=100% height=20 valign=top><table class=big valign=top border=1 width=100% height=20 bgcolor=white cellpadding=0 cellspacing=0 style="border-collapse: collapse; border-width: 0 0 1px 0; border-color: black;">
  <tr>
    <td width=100% height=100% valign=bottom name=Menu id=Menu style="border: none;cursor: default;font-family: {$font_large}; font-size: {$font_size};">&nbsp;&nbsp;<a
	class=MenuItem name=MenuDesk id=MenuDesk href="javascript:void(0)" onMouseOver="if (DesktopDialog || Drag || ReSize){return(false);}
								clearMenus();
								Desk.style.visibility='visible';
								this.style.color='white';
								this.style.background='black';">&nbsp;Desk&nbsp;</a><a
	class=MenuItem name=MenuPhile id=MenuPhile href="javascript:void(0)" onMouseOver="if (DesktopDialog || Drag || ReSize){return(false);}
								clearMenus();
								Phile.style.visibility='visible';
								this.style.color='white';
								this.style.background='black';">&nbsp;File&nbsp;</a><a
	class=MenuItem name=MenuView id=MenuView href="javascript:void(0)" onMouseOver="if (DesktopDialog || Drag || ReSize){return(false);}
								clearMenus();
								View.style.visibility='visible';
								this.style.color='white';
								this.style.background='black';">&nbsp;View&nbsp;</a><a
	class=MenuItem name=MenuOptions id=MenuOptions href="javascript:void(0)" onMouseOver="if (DesktopDialog || Drag || ReSize){return(false);}
								clearMenus();
								Options.style.visibility='visible';
								this.style.color='white';
								this.style.background='black';">&nbsp;Options&nbsp;</a><a
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
<span name=Desk id=Desk class=big style="visibility: hidden; top: 19px; left: 18px; position:absolute; width:200px; height:160px; z-index:65535; background-color: white; border: 1px solid black;"> 
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
							ModalPhileProp.style.display='inline';
							toFront(ModalPhileProp);
							">&nbsp;&nbsp;Item&nbsp;Information&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Desk.style.visibility='hidden';
							clearMenus();
							OpenViewer.style.display='inline';
							toFront(OpenViewer);
							">&nbsp;&nbsp;Thumbnail&nbsp;Viewer&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Desk.style.visibility='hidden';
							clearMenus();
							OpenUpload.style.display='inline';
							toFront(OpenUpload);
							">&nbsp;&nbsp;File&nbsp;Uploads&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Desk.style.visibility='hidden';
							clearMenus();
							OpenOutput.style.display='inline';
							toFront(OpenOutput);
							">&nbsp;&nbsp;Info&nbsp;Output&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)">&nbsp;&nbsp;Exit&nbsp;webTOS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
</span>
<!-- MENU: Phile -->
<span name=Phile id=Phile class=big style="visibility: hidden; top: 19px; left: 78px; position:absolute; width:260px; height:360px; z-index:65535; background-color: white; border: 1px solid black;"> 
<a class=MenuItem href="javascript:void(0)" onClick="	Phile.style.visibility='hidden';
							clearMenus();
							opendir();
							" Key="O" name="_MF0">&nbsp;&nbsp;Open...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[O]</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Phile.style.visibility='hidden';
							clearMenus();
							ModalPhileProp.style.display='inline';
							toFront(ModalPhileProp);
							">&nbsp;&nbsp;Show&nbsp;Information...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Phile.style.visibility='hidden';
							clearMenus();
							searchfile();
							">&nbsp;&nbsp;Search...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<font class=MenuBlank>--------------------------</font><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Phile.style.visibility='hidden';
							clearMenus();
							copy();
							">&nbsp;&nbsp;Copy&nbsp;Item...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[C]</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Phile.style.visibility='hidden';
							clearMenus();
							delet();
							">&nbsp;&nbsp;Delete&nbsp;Item...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[X]</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Phile.style.visibility='hidden';
							clearMenus();
							move();
							">&nbsp;&nbsp;Move&nbsp;Item...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Phile.style.visibility='hidden';
							clearMenus();
							rename();
							">&nbsp;&nbsp;Rename&nbsp;Item...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[f2]</a><br>
<font class=MenuBlank>--------------------------</font><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Phile.style.visibility='hidden';
							clearMenus();
							doDownload();
							">&nbsp;&nbsp;Download...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Phile.style.visibility='hidden';
							clearMenus();
							extract();
							">&nbsp;&nbsp;Extract...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[E]</a><br>
<font class=MenuBlank>--------------------------</font><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Phile.style.visibility='hidden';
							clearMenus();
							atarinewfile();
							">&nbsp;&nbsp;Create&nbsp;Item&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Phile.style.visibility='hidden';
							clearMenus();
							atarinewfolder();
							">&nbsp;&nbsp;Create&nbsp;Folder&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<font class=MenuBlank>--------------------------</font><br>
<a class=MenuItem href="?action=Up&dir={$dir_e}" onClick="Phile.style.visibility='hidden';
							clearMenus();
							atariUp(event);
							">&nbsp;&nbsp;Close&nbsp;Directory&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[B]</a><br>
<a class=MenuItem href="javascript:void(0)">&nbsp;&nbsp;Close&nbsp;Top&nbsp;Window&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)">&nbsp;&nbsp;Bottom&nbsp;To&nbsp;Top&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[W]</a><br>
</span>
<!-- MENU: View -->
<span name=View id=View class=big style="visibility: hidden; top: 19px; left: 138px; position:absolute; width:210px; height:240px; z-index:65535; background-color: white; border: 1px solid black;"> 
<a class=MenuItem href="javascript:void(0)" onClick="	View.style.visibility='hidden';
							clearMenus();
							refreshdir(f);
							">&nbsp;&nbsp;Refresh&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<font class=MenuBlank>---------------------</font><br>
<a class=MenuItem href="javascript:void(0)">&nbsp;&nbsp;Show&nbsp;As&nbsp;Icons&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)">&nbsp;&nbsp;Show&nbsp;As&nbsp;Text&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<font class=MenuBlank>---------------------</font><br>
<a class=MenuItem href="javascript:void(0)" onClick="	View.style.visibility='hidden';
							clearMenus();
							atariarrange('name');
							">&nbsp;&nbsp;Sort&nbsp;By&nbsp;Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	View.style.visibility='hidden';
							clearMenus();
							atariarrange('date');
							">&nbsp;&nbsp;Sort&nbsp;By&nbsp;Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	View.style.visibility='hidden';
							clearMenus();
							atariarrange('size');
							">&nbsp;&nbsp;Sort&nbsp;By&nbsp;Size&nbsp;&nbsp;&nbsp;&nbsp;[O]</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	View.style.visibility='hidden';
							clearMenus();
							atariarrange('type');
							">&nbsp;&nbsp;Sort&nbsp;By&nbsp;Type&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	View.style.visibility='hidden';
							clearMenus();
							atariarrange('none');
							">&nbsp;&nbsp;No&nbsp;Sort&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[L]</a><br>
<font class=MenuBlank>---------------------</font><br>
<a class=MenuItem href="javascript:void(0)" onClick="	View.style.visibility='hidden';
							clearMenus();
							atarithumbnail();
							OpenViewer.style.display='inline';
							toFront(OpenViewer);
							">&nbsp;&nbsp;Show&nbsp;Thumbnail&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
</span>
<!-- MENU: Options -->
<span name=Options id=Options class=big style="visibility: hidden; top: 19px; left: 198px; position:absolute; width:290px; height:280px; z-index:65535; background-color: white; border: 1px solid black;"> 
<a class=MenuItem href="javascript:void(0)" onClick="	Options.style.visibility='hidden';
							clearMenus();
							openeditor();
							">&nbsp;&nbsp;Open&nbsp;in&nbsp;HTML&nbsp;Editor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[H]</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Options.style.visibility='hidden';
							clearMenus();
							opensource();
							">&nbsp;&nbsp;Open&nbsp;in&nbsp;Code&nbsp;Editor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[S]</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Options.style.visibility='hidden';
							clearMenus();
							opendevatari();
							">&nbsp;&nbsp;Open&nbsp;in&nbsp;Dev&nbsp;Edit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[I]</a><br>
<font class=MenuBlank>-----------------------------</font><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Options.style.visibility='hidden';
							oCenter(OpenUrl);
							OpenUrl.style.display='inline';
							DesktopDialog=true;
							">&nbsp;&nbsp;Change&nbsp;Folder&nbsp;Path...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Options.style.visibility='hidden';
							clearMenus();
							browseHere();
							">&nbsp;&nbsp;Open&nbsp;in&nbsp;Browser&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Options.style.visibility='hidden';
							clearMenus();
							extWindow('?dir={$dir_e}');
							">&nbsp;&nbsp;Explore&nbsp;from&nbsp;Here&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<font class=MenuBlank>-----------------------------</font><br>
<a class=MenuItem href="windows.php?dir={$dir_e}" onClick="Options.style.visibility='hidden';
							clearMenus();
							explorer('windows',event);
							">&nbsp;&nbsp;Original&nbsp;Explorer&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="details.php?dir={$dir_e}" onClick="Options.style.visibility='hidden';
							clearMenus();
							explorer('details',event);
							">&nbsp;&nbsp;Original&nbsp;as&nbsp;Details&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="windowsxp.php?dir={$dir_e}" onClick="Options.style.visibility='hidden';
							clearMenus();
							explorer('windowsxp',event);
							">&nbsp;&nbsp;WindowsXP&nbsp;Explorer&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="windowsalt.php?dir={$dir_e}" onClick="Options.style.visibility='hidden';
							clearMenus();
							explorer('windowsalt',event);
							">&nbsp;&nbsp;Alternate&nbsp;Explorer&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<font class=MenuBlank>-----------------------------</font><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Options.style.visibility='hidden';
							clearMenus();
							config();
							">&nbsp;&nbsp;Desktop&nbsp;Configuration...&nbsp;&nbsp;&nbsp;</a><br>
</span>
<!-- MENU: Help -->
<span name=Help id=Help class=big style="visibility: hidden; top: 19px; left: 288px; position:absolute; width:200px; height:100px; z-index:65535; background-color: white; border: 1px solid black;"> 
<a class=MenuItem href="javascript:void(0)" onClick="	Help.style.visibility='hidden';
							clearMenus();
							help();
							">&nbsp;&nbsp;Quick&nbsp;Help&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<font class=MenuBlank>--------------------</font><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Help.style.visibility='hidden';
							clearMenus();
							Favourties();
							">&nbsp;&nbsp;Favourites&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<font class=MenuBlank>--------------------</font><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Help.style.visibility='hidden';
							clearMenus();
							about();
							">&nbsp;&nbsp;About&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
</span>
<!-- WINDOWS -->
<!-- WINDOW: Output -->
<span name=OpenOutput id=OpenOutput xHnd=0 zid=2 minW=305 minH=100 style="position:absolute; display:{$windows['OpenOutput'][8]}; top:{$windows['OpenOutput'][1]}; left:{$windows['OpenOutput'][2]}; width:{$windows['OpenOutput'][3]}; height:{$windows['OpenOutput'][4]}; z-index:{$windows['OpenOutput'][10]};" 
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenOutput);"
> 
<table name=OpenOutputW id=OpenOutputW border=1 cellpadding=0 cellspacing=0 style="background-color:white; width:{$windows['OpenOutput'][3]}; height:{$windows['OpenOutput'][4]}; border-collapse:collapse; border:1px solid black; border-top:0px;">
  <tr>
    <td style="border:none; cursor:default; font-size:0px; width:20px; height:0px"></td>
    <td name=OpenOutputC id=OpenOutputC style="border:none; cursor:default; font-size:0px; width:{$windows['OpenOutput'][5]};"></td>
    <td style="border:none; cursor:default; font-size:0px; width:20px"></td>
    <td style="border:none; cursor:default; font-size:0px; width:20px"></td>
  </tr>
  <tr>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size}; height:20px;" 
onClick="if (DesktopDialog){return(false);} OpenOutput.style.display='none'; clearMenus(); DesktopDialog=false;"
 ><img src="skins/atari/images/CLOSE.GIF" border=0></td>
    <td class=big colspan=2 nowrap align="center" style="border: 1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};"
 onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenOutput); dragWindow(OpenOutput,event);"
>Information Output</td>
    <td align="center" valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};"
onClick="if (DesktopDialog){return(false);} toFront(OpenOutput); opMaxMin(); event.cancelBubble=true;"><img name=OpenOutputMM src="skins/atari/images/MAX.GIF" border=0></td>
  </tr>
  <tr>
    <td name=OpenOutputName id=OpenOutputName class=big colspan=4 align=left style="border:1px solid black; cursor: default; font-family: {$font_large}; font-size: {$font_size};" height=21>&nbsp;</td>
  </tr>
  <tr>
    <td name=OpenOutputBody id=OpenOutputBody class=area align=left valign=top colspan=3 rowspan=3 style="border:1px solid black; height:{$windows['OpenOutput'][6]};"><div align=left valign=top id=OutputList name=OutputList style="width:100%; height:100%; overflow: hidden;">
<div name=thestatus id=thestatus style="width:100%; cursor:default; font-family:{$font_small}; font-size:xx-small;"><center>
HTML;

#--------------- MESSAGES ---------------

if(is_array($msg))  #printing all error messages
 foreach($msg as $m)
 print "$m<br>";
else print "$msg "; 
print "Click on a file icon to view its details</div>
";

#----------------------------------------

print <<<HTML
</div></td>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size}; height:20px;" onClick="OutputList.scrollTop=OutputList.scrollTop-({$font_size_small});"><img src="skins/atari/images/UP.GIF" border=0></td>
  </tr>
  <tr>
    <td name=OpenOutputScroll id=OpenOutputScroll style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:0px; height:{$windows['OpenOutput'][7]};"></td>
  </tr>
  <tr>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};" onClick="OutputList.scrollTop=OutputList.scrollTop+({$font_size_small});"><img src="skins/atari/images/DOWN.GIF" border=0></td>
  </tr>
  <tr>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size}; height:20px;" onClick="OutputList.scrollLeft=OutputList.scrollLeft-(imgW);"><img src="skins/atari/images/LEFT.GIF" border=0></td>
    <td nowrap align=left style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};">&nbsp;</td>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};" onClick="OutputList.scrollLeft=OutputList.scrollLeft+(imgW);"><img src="skins/atari/images/RIGHT.GIF" border=0></td>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};"
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenOutput); event.cancelBubble=true;"
 onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenOutput); windowReSize(OpenOutput,event); event.cancelBubble=true;"
><img src="skins/atari/images/RESIZE.GIF" border=0
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenOutput); event.cancelBubble=true;"
 onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenOutput); windowReSize(OpenOutput,event); event.cancelBubble=true;"
></td>
  </tr>
</table>
<script language=JavaScript>
OutputWinNum = regWindow('OpenOutput',OpenOutput);
function opMaxMin(){
	MaxMin(OutputWinNum);
}
</script>
</span>
<!-- WINDOW: Information -->
<span name=ModalPhileProp id=ModalPhileProp xHnd=0 zid=2 minW=265 minH=310 style="position:absolute; display:{$windows['ModalPhileProp'][8]}; top:{$windows['ModalPhileProp'][1]}; left:{$windows['ModalPhileProp'][2]}; width:{$windows['ModalPhileProp'][3]}; height:{$windows['ModalPhileProp'][4]}; z-index:{$windows['ModalPhileProp'][10]};" 
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(ModalPhileProp);"
> 
<table name=ModalPhilePropW id=ModalPhilePropW valign=top border=1 cellpadding=0 cellspacing=0 style="background-color:white; width:{$windows['ModalPhileProp'][3]}; height:{$windows['ModalPhileProp'][4]}; border-collapse:collapse; border:1px solid black; border-top:0px;">
  <tr>
    <td style="border:none; cursor:default; font-size:0px; width:20px; height:0px;"></td>
    <td name=ModalPhilePropC id=ModalPhilePropC style="border:none; cursor:default; font-size:0px; width:{$windows['ModalPhileProp'][5]};"></td>
    <td style="border:none; cursor:default; font-size:0px; width:20px;"></td>
    <td style="border:none; cursor:default; font-size:0px; width:20px;"></td>
  </tr>
  <tr>
    <td align=center valign=middle style="border:1px solid black; cursor: default; font-family: {$font_large}; font-size: {$font_size};" height=20 
onClick="if (DesktopDialog){return(false);} ModalPhileProp.style.display='none'; clearMenus(); DesktopDialog=false;" ><img src="skins/atari/images/CLOSE.GIF" border=0></td>
    <td class=big colspan=3 nowrap align=center style="border:1px solid black; cursor: default; font-family: {$font_large}; font-size: {$font_size}; padding-right:20px"
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(ModalPhileProp); dragWindow(ModalPhileProp,event);"
>Information</td>
  </tr>
  <tr>
    <td name=ModalPhilePropName id=ModalPhilePropName class=big colspan=4 align=left style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};" height=21>maximum file size {$max_size}</td>
  </tr>
  <tr>
    <td name=ModalPhilePropBody id=ModalPhilePropBody class=area align=left valign=top colspan=3 rowspan=3 style="border:1px solid black; height:{$windows['ModalPhileProp'][6]}; overflow:hidden;"
 onMouseWheel="mwModalPhileProp(event);"
><div align=left valign=top class=small name=PhilePropList id=PhilePropList style="width:242px; height:{$windows['ModalPhileProp'][6]}; overflow:hidden;" 
><div class=PropHead>Client Information:</div>
<div class=PropInfo>
User IP: <b>{$_SERVER['REMOTE_ADDR']}</b><br>
Web Mode: <b>{$mode}</b><br>
Encoding: <b>{$encoding}</b><br>
</div>
<div class=PropHead>Folder Properties:</div>
<div id=folderinfo name=folderinfo class=PropInfo>Total files:<br>Permissions:<br></div>
<div class=PropHead>Item Properties:</div>
<div name=info id=info class=PropInfo>
File:<br>
Size:<br>
Permissions:<br>
Modified:<br>
Accessed:<br>
</div>
<div class=PropHead>Item Details</div>
<div name=zipinfo id=zipinfo class=PropInfo>no details</div>
<div class=PropHead style="margin-bottom:1px;">&nbsp;</div>
</div></td>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};" height=20 
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(ModalPhileProp); ScrollBar=true; scrollIt=setTimeout('scrollClick(-(parseInt(\'{$font_size}\')),ModalPhilePropScrollV,PhilePropList)',50);"
 onMouseUp="ScrollBar=false;"><img src="skins/atari/images/UP.GIF" border=0></td>
  </tr>
  <tr>
    <td name=ModalPhilePropScroll id=ModalPhilePropScroll style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size}; height:{$windows['ModalPhileProp'][7]};"
><div style="width:20px; height:100%; position:relative;" onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(ModalPhileProp); scrollPage(ModalPhilePropScrollV,event,PhilePropList);"
><div name=ModalPhilePropScrollV id=ModalPhilePropScrollV class=ScrollV 
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(ModalPhileProp); dragScroll(ModalPhilePropScrollV,event,PhilePropList);"
 onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(ModalPhileProp); e=event?event:window.event; e.cancelBubble=true;"></div></div></td>
  </tr>
  <tr>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};" height=20 
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(ModalPhileProp); ScrollBar=true; scrollIt=setTimeout('scrollClick(parseInt(\'{$font_size}\'),ModalPhilePropScrollV,PhilePropList)',50);"
 onMouseUp="ScrollBar=false;"><img src="skins/atari/images/DOWN.GIF" border=0></td>
  </tr>
  <tr>
    <td colspan=4 align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:0px; height:2px;" height=2></td>
  </tr>
</table>
<script language=JavaScript>
PhilePropWinNum = regWindow('ModalPhileProp',ModalPhileProp);
scrollSet(ModalPhilePropScrollV,PhilePropList);

function mwModalPhileProp(event){
	if (DesktopDialog){return(false);}
	clearMenus();
	toFront(ModalPhileProp);
	ScrollBar=true;
	doMouseWheel({$val_font_size_small},event,ModalPhileProp,PhilePropList);
	ScrollBar=false;
}

if((/Firefox\/3/i.test(navigator.userAgent)) || (/Iceweasel\/3/i.test(navigator.userAgent))){
	 ModalPhilePropBody.addEventListener("DOMMouseScroll", mwModalPhileProp, false)
}

</script>
</span>
<!-- WINDOW: Explorer -->
<span name=OpenDrive id=OpenDrive xHnd=0 zid=2 minW=125 minH=160 style="position:absolute; display:{$windows['OpenDrive'][8]}; top:{$windows['OpenDrive'][1]}; left:{$windows['OpenDrive'][2]}; width:{$windows['OpenDrive'][3]}; height:{$windows['OpenDrive'][4]}; z-index:{$windows['OpenDrive'][10]};" 
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenDrive);"
> 
<table name=OpenDriveW id=OpenDriveW valign=top border=1 cellpadding=0 cellspacing=0 style="background-color:white; width:{$windows['OpenDrive'][3]}; height:{$windows['OpenDrive'][4]}; border-collapse:collapse; border:1px solid black; border-top:0px;"
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenDrive);"
>
  <tr>
    <td style="border:none; cursor:default; font-size:0px; width:20px; height:0px;"></td>
    <td name=OpenDriveC id=OpenDriveC style="border:none; cursor:default; font-size:0px; width:{$windows['OpenDrive'][5]};"></td>
    <td style="border:none; cursor:default; font-size:0px; width:20px;"></td>
    <td style="border:none; cursor:default; font-size:0px; width:20px;"></td>
  </tr>
  <tr>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size}; height:21px;" 
onClick="if (DesktopDialog){return(false);} clearMenus(); location.href='?action=Up&dir=$dir_e'; DesktopDialog=false;" ><img src="skins/atari/images/CLOSE.GIF" border=0></td>
    <td class=big colspan=2 nowrap align=center style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size}; overflow:hidden;"
 onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenDrive); dragWindow(OpenDrive,event);"
><div style="width:{$windows['OpenDrive'][5]}; overflow:hidden;"><center><div style="overflow:hidden;">$dir</div></center></div></td>
    <td align=center valign=middle style="border: 1px solid black; cursor: default;font-family: {$font_large}; font-size: {$font_size};"
onClick="if (DesktopDialog){return(false);} toFront(OpenDrive); drMaxMin(); event.cancelBubble=true;"><img name=OpenDriveMM src="skins/atari/images/MAX.GIF" border=0></td>
  </tr>
  <tr>
    <td name=OpenDriveName id=OpenDriveName class=big colspan=4 align=left style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size}; height:21px;" height=21>&nbsp;</td>
  </tr>
  <tr>
    <td name=OpenDriveArea id=OpenDriveArea class=area align=left valign=top colspan=3 rowspan=3 style="border:1px solid black; overflow:hidden;"><div name=OpenDriveBody id=OpenDriveBody style="background-color:gray; width:100%; overflow:hidden; height:{$windows['OpenDrive'][6]};"><div name=PhileList id=PhileList style="width:100%; height:100%; font-size:0px; overflow: hidden;"
 onMouseWheel="mwOpenDrive(event);"
>
<span id=startHere style="float:left; width:0px; height:0px;"></span>
HTML;

#-------------- ICON OUTPUT -------------

if($action!="Edit" && $action!="Search") { # exploring the files
  explore($dir); 
  $se = "This Folder";
}
if($action!="Edit" && $action=="Search") { # file & contents search
include("search.php");
  search($dir); 
  $se = "Serach";
}

#----------------------------------------

print <<<HTML
</div></div></td>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};" height=20 
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenDrive); ScrollBar=true; scrollIt=setTimeout('scrollClick(-(imgH),OpenDriveScrollV,PhileList)',50);"
 onMouseUp="ScrollBar=false;"><img src="skins/atari/images/UP.GIF" border=0></td>
  </tr>
  <tr>
    <td name=OpenDriveScroll id=OpenDriveScroll style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size}; height:{$windows['OpenDrive'][7]};"><div style="width:20px; height:100%; position:relative;" 
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenDrive); scrollPage(OpenDriveScrollV,event,PhileList);"
 ><div name=OpenDriveScrollV id=OpenDriveScrollV class=ScrollV 
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenDrive); dragScroll(OpenDriveScrollV,event,PhileList);"
 onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenDrive); e=event?event:window.event; e.cancelBubble=true;"></div></div></td>
  </tr>
  <tr>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};" height=20 
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenDrive); ScrollBar=true; scrollIt=setTimeout('scrollClick(imgH,OpenDriveScrollV,PhileList)',50);"
 onMouseUp="ScrollBar=false;"><img src="skins/atari/images/DOWN.GIF" border=0></td>
  </tr>
  <tr>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};" height=20 
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenDrive); ScrollBar=true; scrollIt=setTimeout('scrollClick(-(imgW),OpenDriveScrollH,PhileList)',50);"
 onMouseUp="ScrollBar=false;"><img src="skins/atari/images/LEFT.GIF" border=0></td>
    <td nowrap style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};"><div style="width:100%; height:20px; position:relative;" 
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenDrive); scrollPage(OpenDriveScrollH,event,PhileList);"
 ><div name=OpenDriveScrollH id=OpenDriveScrollH class=ScrollH
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenDrive); dragScroll(OpenDriveScrollH,event,PhileList);"
 onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenDrive); e=event?event:window.event; e.cancelBubble=true;"></div></div></td>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};" 
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenDrive); ScrollBar=true; scrollIt=setTimeout('scrollClick(imgW,OpenDriveScrollH,PhileList)',50);"
 onMouseUp="ScrollBar=false;"><img src="skins/atari/images/RIGHT.GIF" border=0></td>

    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};"
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenDrive); event.cancelBubble=true;"
 onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenDrive); windowReSize(OpenDrive,event); event.cancelBubble=true;"
  ><img src="skins/atari/images/RESIZE.GIF" border=0
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenDrive);"
 onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenDrive); windowReSize(OpenDrive,event); event.cancelBubble=true;"
  onDrag="event.cancelBubble=true;"
></td>
  </tr>
</table>
<script language=JavaScript>
DriveWinNum = regWindow('OpenDrive',OpenDrive);
function drMaxMin(){
	MaxMin(DriveWinNum);
}
OpenDriveName.innerHTML = '$bytes bytes used in $total items.';
scrollSetBoth('OpenDrive',PhileList);

function mwOpenDrive(event){
	if (DesktopDialog){return(false);}
	clearMenus();
	toFront(OpenDrive);
	ScrollBar=true;
	doMouseWheel(imgH,event,OpenDrive,PhileList);
	ScrollBar=false;
}

if((/Firefox\/3/i.test(navigator.userAgent)) || (/Iceweasel\/3/i.test(navigator.userAgent))){
	 OpenDriveArea.addEventListener("DOMMouseScroll", mwOpenDrive, false)
}
</script>
</span>
<!-- MENU: Context -->
<table id=context class=context border=0 cellpadding=0 cellspacing=0 style="top:100px;{$font_fix}">
<tr id=conDir ><td class=contbar><a href="" class=contitem onClick="opendir();return(false);">&nbsp;<b>Open</b></a></td></tr>
<tr id=conSep0><td class=contbar><font class=MenuBlank>------------</font></td></tr>
<tr id=conRen ><td class=contbar><a href="" class=contitem onClick="rename();return(false);">&nbsp;Rename</a></td></tr>
<tr id=conDel ><td class=contbar><a href="" class=contitem onClick="delet();return(false);">&nbsp;Delete</a></td></tr>
<tr id=conCopy><td class=contbar><a href="" class=contitem onClick="copy();return(false);">&nbsp;Copy to</a></td></tr>
<tr id=conMove><td class=contbar><a href="" class=contitem onClick="move();return(false);">&nbsp;Move to</a></td></tr>
<tr id=conSep1><td class=contbar><font class=MenuBlank>------------</font></td></tr>
<tr id=conNewF><td class=contbar><a href="" class=contitem onClick="atarinewfile();return(false);">&nbsp;New File</a></td></tr>
<tr id=conNew ><td class=contbar><a href="" class=contitem onClick="atarinewfolder();return(false);">&nbsp;New Folder</a></td></tr>
<!--
<tr id=conSep2><td class=contbar><font class=MenuBlank>------------</font></td></tr>
<tr id=conNewF><td class=contbar><a href="" class=contitem onClick="atarithumbnail();return(false);">&nbsp;Thumbnail</a></td></tr>
<tr id=conNew ><td class=contbar><a href="" class=contitem onClick="thumbnail();return(false);">&nbsp;Preview</a></td></tr>
<tr id=conSep3><td class=contbar><font class=MenuBlank>------------</font></td></tr>
<tr id=conNewF><td class=contbar><a href="" class=contitem onClick="extract();return(false);">&nbsp;Extract</a></td></tr>
<tr id=conNew ><td class=contbar><a href="" class=contitem onClick="compress();return(false);">&nbsp;Compress</a></td></tr>
-->
</table>
<!-- DIALOG: Desktop Inf.. -->
<span name=DesktopInfo id=DesktopInfo style="display:none; top:0px; left:0px; position:absolute; width:375px; height:337px; z-index:65535; background-color: white; border: 1px solid black; padding: 2px"> 
<table class=big valign=top border=0 width=100% height=100% bgcolor=white cellpadding=0 cellspacing=0 style="border-collapse: collapse; border: 3px solid black;">
  <tr>
    <td width=100% height=100% nowrap align=center style="border: 0px; cursor: default;font-family: {$font_large}; font-size: {$font_size};"><br>
GEM, Graphic Environment Manager<br><br>
TOS<br>
<font color=gray>-----------------------------</font><br>
<img src="skins/atari/images/DILOGO.GIF" width=33 height=34 borber=0><br><br><br><br>
Copyright <b>&copy;</b> 1985,86,87,88,89,90,91<br>
Atari Corporation<br>
Digital Research, Inc.<br>
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
</span>
<!-- DIALOG: OpenUrl -->
<span name=OpenUrl id=OpenUrl style="display:none; top:0px; left:0px; position:absolute; width:376px; height:196px; z-index:65533; background-color: white; border: 1px solid black; padding: 2px"> 
<table class=big valign=top border=0 width=100% height=100% bgcolor=white cellpadding=0 cellspacing=0 style="border-collapse: collapse; border: 3px solid black;">
  <tr>
    <td width=100% height=100% nowrap align=center style="border: none; cursor: default; font-family: {$font_large}; font-size: {$font_size};
"><br>OPEN PATH:<br><br>
<input name=go type=text size=37 value="$dir" v alue="_____________________________________" style="border: none; cursor: default;font-family: {$font_large}; font-size: {$font_size}; color: black; background: white;"><br>
</td></tr>
<!-- BUTTON: options -->
<tr><td width=100% height=100% nowrap align=center style="border: none; cursor: default; padding: 15px"><table 
border=0 bgcolor=white cellpadding=0 cellspacing=0 style="border-collapse: collapse; border: none; cursor: default; font-family: {$font_large}; font-size: {$font_size};">
<tr><td>Type:</td><td>&nbsp;</td>
<td style="border: 1px solid black;"><a href="javascript:void(0)" name=OptionButtonGEM id=OptionButtonGEM class=ButtonItem style="color: white; background: black;"
onMouseDown="	this.style.color='white'; this.style.background='black';
		OptionButtonTOS.style.color='black';
		OptionButtonTOS.style.background='white';" 
onClick="" 
>&nbsp;&nbsp;&nbsp;GEM&nbsp;&nbsp;&nbsp;</a></td>
<td>&nbsp;&nbsp;</td>
<td style="border: 1px solid black; cursor: default;"><a href="javascript:void(0)" name=OptionButtonTOS id=OptionButtonTOS class=ButtonItem 
onMouseDown="	this.style.color='white'; this.style.background='black';
		OptionButtonGEM.style.color='black'; 
		OptionButtonGEM.style.background='white';" 
onClick="" 
>&nbsp;&nbsp;&nbsp;TOS&nbsp;&nbsp;&nbsp;</a></td>
</tr></table></td></tr>
<!-- BUTTON: OK Cancel -->
<tr><td width=100% height=100% nowrap align=center style="border: none; cursor: default; padding: 15px"><table 
border=0 bgcolor=white cellpadding=0 cellspacing=0 style="border-collapse: collapse; border: 0px">
<tr><td style="border: 3px solid black; cursor: default;"><a href="javascript:void(0)" class=ButtonItem 
onMouseDown="	this.style.color='white';
		this.style.background='black';" 
onClick="	OpenUrl.style.display='none';
		clearMenus();
		this.style.color='black';
		this.style.background='white';
		DesktopDialog=false;
		gotodir(f);
" >&nbsp;&nbsp;&nbsp;&nbsp;OK&nbsp;&nbsp;&nbsp;&nbsp;</a></td><td style="border: none; cursor: default;">&nbsp;&nbsp;</td>
<td style="border: 3px solid black; cursor: default;"><a href="javascript:void(0)" class=ButtonItem 
onMouseDown="	this.style.color='white';
		this.style.background='black';" 
onClick="	OpenUrl.style.display='none';
		textReset(f.go);
		clearMenus();
		this.style.color='black';
		this.style.background='white';
		DesktopDialog=false;
" >&nbsp;&nbsp;Cancel&nbsp;&nbsp;</a></td>
</tr></table></td></tr>
</table>
</span>
<!-- WINDOW: Viewer -->
<div name=OpenViewer id=OpenViewer xHnd=0 zid=2 minW=125 minH=160 style="position:absolute; display:{$windows['OpenViewer'][8]}; top:{$windows['OpenViewer'][1]}; left:{$windows['OpenViewer'][2]}; width:{$windows['OpenViewer'][3]}; height:{$windows['OpenViewer'][4]}; z-index:{$windows['OpenViewer'][10]};" 
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenViewer);"
> 
<table name=OpenViewerW id=OpenViewerW valign=top border=1 cellpadding=0 cellspacing=0 style="background-color:white; width:{$windows['OpenViewer'][3]}; height:{$windows['OpenViewer'][4]}; border-collapse:collapse; border:1px solid black; border-top:0px;" 
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenViewer);"
>
  <tr>
    <td style="border:none; cursor:default; font-size:0px; width:20px; height:0px"></td>
    <td name=OpenViewerC id=OpenViewerC style="border:none; cursor:default; font-size:0px; width:{$windows['OpenViewer'][5]};"></td>
    <td style="border:none; cursor:default; font-size:0px; width:20px;"></td>
    <td style="border:none; cursor:default; font-size:0px; width:20px;"></td>
  </tr>
  <tr>
    <td align=center style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size}; height:21px;" 
onClick="if (DesktopDialog){return(false);} OpenViewer.style.display='none'; clearMenus(); DesktopDialog=false;"><img src="skins/atari/images/CLOSE.GIF" border=0></td>
    <td class=big colspan=2 nowrap align=center style="border: 1px solid black; cursor: default; font-family: {$font_large}; font-size: {$font_size};"
 onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenViewer); dragWindow(OpenViewer,event);"
>Viewer</td>
    <td align=center style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};"
onClick="if (DesktopDialog){return(false);} toFront(OpenViewer); vrMaxMin(); event.cancelBubble=true;"><img name=OpenViewerMM src="skins/atari/images/MAX.GIF" border=0></td>
  </tr>
  <tr>
    <td class=big colspan=4 style="border:1px solid black; cursor:default; overflow:hidden; height:21px;"><div name=OpenViewerName id=OpenViewerName style="width:100%; height:100%; cursor:default; font-family:{$font_large}; font-size:{$font_size}; overflow:hidden;"></td>
  </tr>
  <tr>
    <td class=area valign=top colspan=3 rowspan=3 style="border:1px solid black; overflow:hidden;"><div name=OpenViewerBody id=OpenViewerBody style="border:none; height:{$windows['OpenViewer'][6]}; overflow: hidden;"><div name=Viewer id=Viewer class=MenuBlank style="width:100%; height:100%; overflow:hidden;"></div></td>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size}; height:20px;" height=20
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenViewer); ScrollBar=true; scrollIt=setTimeout('scrollClick(-(imgH),OpenEditorScrollV,Viewer)',50);"
 onMouseUp="ScrollBar=false;"><img src="skins/atari/images/UP.GIF" border=0></td>
  </tr>
  <tr>
    <td valign=top name=OpenViewerScroll id=OpenViewerScroll style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size}; height:{$windows['OpenViewer'][7]};"><div style="width:20px; height:100%; position:relative;" 
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenViewer); scrollPage(OpenViewerScrollV,event,Viewer);"
><div name=OpenViewerScrollV id=OpenViewerScrollV class=ScrollV 
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenViewer); dragScroll(OpenViewerScrollV,event,Viewer);"
 onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenViewer); e=event?event:window.event; e.cancelBubble=true;"></div></div></td>
  </tr>
  <tr>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size: $font_size}; height:20px;" height=20 
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenViewer); ScrollBar=true; scrollIt=setTimeout('scrollClick(imgH,OpenEditorScrollV,Viewer)',50);"
 onMouseUp="ScrollBar=false;"><img src="skins/atari/images/DOWN.GIF" border=0></td>
  </tr>
  <tr>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size}; height:20px;" height=20 
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenViewer); ScrollBar=true; scrollIt=setTimeout('scrollClick(-(imgW),OpenViewerScrollH,Viewer)',50);"
 onMouseUp="ScrollBar=false;"><img src="skins/atari/images/LEFT.GIF" border=0></td>
    <td nowrap align=left style="border:1px solid black; cursor:default; font-family: {$font_large}; font-size: {$font_size}"><div style="width:100%; height:20px; position:relative;" 
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenViewer); scrollPage(OpenViewerScrollH,event,Viewer);"
><div name=OpenViewerScrollH id=OpenViewerScrollH class=ScrollH
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenViewer); dragScroll(OpenViewerScrollH,event,Viewer);"
 onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenViewer); e=event?event:window.event; e.cancelBubble=true;"></div></div></td>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};"
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenViewer); ScrollBar=true; scrollIt=setTimeout('scrollClick(imgW,OpenViewerScrollH,Viewer)',50);"
 onMouseUp="ScrollBar=false;"><img src="skins/atari/images/RIGHT.GIF" border=0></td>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};"
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenViewer); event.cancelBubble=true;"
 onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenViewer); windowReSize(OpenViewer,event); event.cancelBubble=true;"
  ><img src="skins/atari/images/RESIZE.GIF" border=0
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenViewer); event.cancelBubble=true;"
 onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenViewer); windowReSize(OpenViewer,event); event.cancelBubble=true;"
></td>
  </tr>
</table>
<script language=JavaScript>
ViewWinNum = regWindow('OpenViewer',OpenViewer);
function vrMaxMin(){
	MaxMin(ViewWinNum);
}
scrollSetBoth('OpenViewer',Viewer);
</script>
</div>
</form>
<!-- WINDOW: Upload -->
<span name=OpenUpload id=OpenUpload xHnd=0 zid=2 minW=125 minH=100 style="position:absolute; display:{$windows['OpenUpload'][8]}; top:{$windows['OpenUpload'][1]}; left:{$windows['OpenUpload'][2]}; width:{$windows['OpenUpload'][3]}; height:{$windows['OpenUpload'][4]}; z-index:{$windows['OpenUpload'][10]};" 
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenUpload);"
 onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenUpload); dragWindow(OpenUpload,event);"
> 
<table name=OpenUploadW id=OpenUploadW valign=top border=1 cellpadding=0 cellspacing=0 style="background-color:white; width:{$windows['OpenUpload'][3]}; height:{$windows['OpenUpload'][4]}; border-collapse:collapse; border:1px solid black; border-top:0px;">
  <tr>
    <td style="border:none; cursor:default; font-size:0px; width:20px; height:0px;"></td>
    <td name=OpenUploadC id=OpenUploadC style="border:none; cursor:default; font-size:0px; width:{$windows['OpenUpload'][5]};"></td>
    <td style="border:none; cursor:default; font-size:0px; width:20px;"></td>
    <td style="border:none; cursor:default; font-size:0px; width:20px;"></td>
  </tr>
  <tr>
    <td align=center valign=middle style="border:1px solid black; cursor: default; font-family: {$font_large}; font-size: {$font_size};" height=21 
onClick="if (DesktopDialog){return(false);} OpenUpload.style.display='none'; clearMenus(); DesktopDialog=false;" ><img src="skins/atari/images/CLOSE.GIF" border=0></td>
    <td class=big colspan=2 nowrap align=center style="border:1px solid black; cursor: default; font-family: {$font_large}; font-size: {$font_size};">Upload Files</td>
    <td align=center valign=middle style="border:1px solid black; cursor: default;font-family: {$font_large}; font-size: {$font_size};"
onClick="if (DesktopDialog){return(false);} toFront(OpenUpload); ulMaxMin(); event.cancelBubble=true;"><img name=OpenUploadMM src="skins/atari/images/MAX.GIF" border=0></td>
  </tr>
  <tr>
    <td name=OpenUploadName id=OpenUploadName class=big colspan=4 align=left style="border:1px solid black; cursor: default; font-family: {$font_large}; font-size: {$font_size};" height=21>maximum file size $max_size</td>
  </tr>
  <tr>
    <td name=OpenUploadBody id=OpenUploadBody class=area align=left valign=top colspan=3 rowspan=3 bgcolor=gray style="border:1px solid black; height:{$windows['OpenUpload'][6]}; overflow:hidden;"><div class=big align=left valign=top name=UploadList id=UploadList style="width:100%; height:{$windows['OpenUpload'][6]}; overflow:hidden;">
HTML;

#------------- UPLOAD FORM --------------

print"<form name=f2 id=f2 enctype=multipart/form-data method=POST action='?' onSubmit='return upload();'><center>
<input type=hidden name=MAX_FILE_SIZE value='$size'><input type=hidden name=dir value='$dir'>";
for($i=1;$i<=$uploads;$i++){
//  print"$i<input type=file name=upfile[] id=upfile$i ><br>";
  print"$i<input type=file name=upfile[] id=upfile ><br>";
//  if($i%2==0) print"<br>";
}
print <<<HTML
<input id=upfile type=submit name=action value=Upload title=" max file size {$max_size} ">
<script>
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
</script></form>
HTML;

#----------------------------------------

print <<<HTML
</div></td>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};" height=20 
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenUpload); ScrollBar=true; scrollIt=setTimeout('scrollClick(-(imgH),OpenUploadScrollV,UploadList)',50);"
 onMouseUp="ScrollBar=false;"><img src="skins/atari/images/UP.GIF" border=0></td>
  </tr>
  <tr>
    <td name=OpenUploadScroll id=OpenUploadScroll style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size}; height:{$windows['OpenUpload'][7]};"><div style="width:20px; height:100%; position:relative;" 
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenUpload); scrollPage(OpenUploadScrollV,event,UploadList);"
 ><div name=OpenUploadScrollV id=OpenUploadScrollV class=ScrollV 
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenUpload); dragScroll(OpenUploadScrollV,event,UploadList);"
 onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenUpload); e=event?event:window.event; e.cancelBubble=true;"></div></div></td>
  </tr>
  <tr>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};" height=20 
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenUpload); ScrollBar=true; scrollIt=setTimeout('scrollClick(imgH,OpenUploadScrollV,UploadList)',50);"
 onMouseUp="ScrollBar=false;"><img src="skins/atari/images/DOWN.GIF" border=0></td>
  </tr>
  <tr>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};" height=20 
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenUpload); ScrollBar=true; scrollIt=setTimeout('scrollClick(-(imgW),OpenUploadScrollH,UploadList)',50);"
 onMouseUp="ScrollBar=false;"><img src="skins/atari/images/LEFT.GIF" border=0></td>
    <td nowrap style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};"><div style="width:100%; height:20px; position:relative;" 
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenUpload); scrollPage(OpenUploadScrollH,event,UploadList);"
 ><div name=OpenUploadScrollH id=OpenUploadScrollH class=ScrollH
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenUpload); dragScroll(OpenUploadScrollH,event,UploadList);"
 onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenUpload); e=event?event:window.event; e.cancelBubble=true;"></div></div></td>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};" 
onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenUpload); ScrollBar=true; scrollIt=setTimeout('scrollClick(imgW,OpenUploadScrollH,UploadList)',50);"
 onMouseUp="ScrollBar=false;"><img src="skins/atari/images/RIGHT.GIF" border=0></td>
    <td align=center valign=middle style="border:1px solid black; cursor:default; font-family:{$font_large}; font-size:{$font_size};"
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenUpload); event.cancelBubble=true;"
 onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenUpload); windowReSize(OpenUpload,event); event.cancelBubble=true;"
  ><img src="skins/atari/images/RESIZE.GIF" border=0
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenUpload); event.cancelBubble=true;"
 onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront(OpenUpload); windowReSize(OpenUpload,event); event.cancelBubble=true;"
></td>
  </tr>
</table>
<script language=JavaScript>
UploadWinNum = regWindow('OpenUpload',OpenUpload);
function ulMaxMin(){
	MaxMin(UploadWinNum);
}
scrollSetBoth('OpenUpload',UploadList);
</script>
</span>
<!-- CODE: Last -->
<script language=JavaScript>
document.title = '{$xTitle} - Browsing - Atari - PHP Navigator';
skintype = '$groupimgs';
imgW = $icn_size[0];
imgH = $icn_size[1];

//width = (window.innerWidth) ? window.innerWidth : document.body.clientWidth;
//height = (window.innerHeight) ? window.innerHeight : document.body.clientHeight;
width=0;
height=0;

document.cookie = 'defaults' + DesktopName + '={$winDefaults};';
setini();
</script>
</body>
</html>
HTML;

www_page_close();