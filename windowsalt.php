<?php
#---------------------------
# PHP Navigator 4.12.20
# alternate prototype (for v5)
# dated: 05-03-2008
# edited: 07-06-2011
# Modified by: Paul Wratt
# Melbourne, Australia
# Auckland, New Zealand
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
include_once("config_patch.php");
include_once("functions.php");
getcookies();
include_once("skin.php"); // setup skins

authenticate();	//user login & other restrictions

#------------- NEW FUNCTIONS ------------
# v5 prototype skin with new functions
#
# www_page_open()  - start data output encoding to browser
# www_page_close() - end data output encoding, apply compression
# folderin(dir)    - return "end_folder in end_folder-1" from full path
#

# 01/09/2008 - moved to "functions.php"

#------------- browser patches ----------------
$class = 'head';
$top = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
$browsp = 0;
$ULp = '';
$ULheight = '0';
$displ = "style='position:relative; display:block; left:0px; top:0px;'";

  if (substr_count($_SERVER['HTTP_USER_AGENT'],"Opera")>0){
    $class = 'lxophead';
  }elseif (substr_count($_SERVER['HTTP_USER_AGENT'],"Firefox")>0 || substr_count($_SERVER['HTTP_USER_AGENT'],"BonEcho") || substr_count($_SERVER['HTTP_USER_AGENT'],"Iceweasel")>0){
    $top = '';
    $class = 'lxffhead';
  }elseif (substr_count($_SERVER['HTTP_USER_AGENT'],"MSIE 7")>0){
    $top = '';
    $ULp = "    xObj.style.height = '1px';";
    $ULheight = '1';
    $browsp = 6;
  }elseif (substr_count($_SERVER['HTTP_USER_AGENT'],"MSIE")>0){
    $browsp = 6;
  }

#----------------------------------------------

function specialfolder($file){
  global $dir, $realdir, $skin, $icn_size;

  $si = $GLOBALS['fileicons'];
  $gi = $GLOBALS['groupimgs'];
  $action = "";

  if($file==".") $folder = "Reload This";
  if($file==".."){
    $folder = "Open Parent";
    $action = "action=Up&";
  }

  if (is_array($icn_size)){
    $w = $icn_size[0];
    $h = $icn_size[1];
  }else{
    $w = '32';
    $h = $w;
  }
  $stat = stat($file);
  $spec = filespec($file);

  $dir_e = urlencode($dir);

    $img = "skins/{$si}dir{$gi}";
    if (!file_exists($realdir.$img))
      $img = "skins/{$skin}dir{$gi}";
    if (!file_exists($realdir.$img))
      $img = "skins/dir.gif";
    print "
	<center><a class=icon><img
	src=\"$img\" width=$w height=$h  alt=\"<b>$folder Folder</b><br><br>
	Permissions:".decoct(fileperms($file)%01000)."<br>
	Modified: ".date('d-m-y, G:i', $stat[9])."\" 
	onMouseDown=\"loadfile(this,'');\" id=file title=\"$folder Folder\" onDblClick=\"location.href='?{$action}dir=$dir_e'\"  spec=\"$spec\" 
	onError=\"this.src='skins/dir.gif';\"></a><br><a 
	class=name 
	title=\"$folder Folder\">$file</a>";
}

function explore($dir){
  global $cols, $uploads, $i, $arrange_by, $disp, $dir, $homedir, $restrict_to_home;
//  print"<table width=100% border=0 cellspacing=0 cellpadding=0 id=filestable ><tr class=center><td width=100% align=center >";

  if (is_dir($dir)){
    if ($dh = opendir($dir)){
      while (($file = readdir($dh)))
        $files[] = $file;
      sort($files); 						#default is sort by name
      $i=1;
      foreach ($files as $file){
        if ($file=="." || $file==".."){
          if($file==".." && $dir==$homedir && $restrict_to_home) continue;
          print "<span class=iconFile $disp ><table border=0 width=100><tr><td width=100% onmousedown=loadtd(this,'')>";
          specialfolder($file);					# function to print special folder icon & details
          print "</td></tr></table></span>";
        }elseif ($file!="." && $file!=".." && is_dir($file)){
          print "<span class=iconFile $disp ><table border=0 width=100><tr><td width=100% onmousedown=loadtd(this,'')>";
          filestatus($file);					# function to print file icon & details
          print "</td></tr></table></span>";
          $i++;
        }
      }
      if ($arrange_by=="type"){					#sort by type
        foreach ($files as $file){
          $data = pathinfo($file);
          $exts[] = strtolower($data["extension"]);
        }
        array_multisort($exts,SORT_STRING ,SORT_ASC,$files); 
      }elseif ($arrange_by=="size"){				#sort by size
        foreach ($files as $file)
          $sizes[]=0+filesize($file);
        array_multisort($sizes,SORT_NUMERIC ,SORT_DESC,$files); 
      }
      foreach($files as $file){					#default is sort by name
        if($file!="."&&$file!=".."&&!is_dir($file)){
          print "<span class=iconFile $disp><table border=0 width=100><tr><td width=100% onmousedown=loadtd(this)>";
          filestatus($file);					# function to print file icon & details
          print "</td></tr></table></span>";
          $i++;
        }
      }
      closedir($dh);
    }
  }else
    $msg[]= "Directory $dir does not exist!";

  $total = count($files)-2;
  $perms = decoct(fileperms($file)%01000);

  print"
<input type=hidden name=total value='$total'>
<input type=hidden name=perms value='$perms'>";

}

function koviastatus($file){
  global $dir, $realdir, $no_icn, $icn_size, $skin, $P ;

  $si = $GLOBALS['fileicons'];
  $gi = $GLOBALS['groupimgs'];

  if (is_array($icn_size)){
    $w = $icn_size[0];
    $h = $icn_size[1];
  }else{
    $w = '32';
    $h = $w;
  }
  if (end($P)=="details.php"){
    $w = '16';
    $h = $w;
  }

  $scale = array(" Bytes"," KB"," MB"," GB");
  $stat = stat($file);

  $size = $stat[7];
  for ($s=0;$size>1024&&$s<4;$s++)				# Calculate in Bytes,KB,MB etc.
    $size=$size/1024;
  if ($s>0)
    $size= number_format($size,2).$scale[$s];
  else
    $size= number_format($size).$scale[$s];

  if (is_editable($file))
    $dblclick="opendir()";
  else
   $dblclick="not_editable()";

  $spec=filespec($file);

  $filename_t = htmlentities($file,ENT_QUOTES);
  $filename_e = urlencode($file);
  $dir_e = urlencode($dir);
  $filename = wordwrap($filename_t, 15, "<br>\n",1);

  if (is_dir($file)){
    $img = "skins/{$si}dir{$gi}";
    if (!file_exists($realdir.$img))
      $img = "skins/{$skin}dir{$gi}";
    if (!file_exists($realdir.$img))
      $img = "skins/dir.gif";
    print "
	<center><a class=icon><img
	src=\"$img\" width=$w height=$h  alt=\"<b>$filename_t</b><br>File Folder<br><br>
	Permissions:".decoct(fileperms($file)%01000)."<br>
	Modified: ".date('d-m-y, G:i', $stat[9])."\" 
	onMouseDown=\"loadfile(this,'');\" id=file title=\"$filename_t\" onDblClick=\"opendir();\"  spec=\"$spec\" 
	onError=\"this.src='skins/dir.gif';\"></a><br><a 
	class=name href=\"javascript:download('$filename_t')\" 
	title=\"Download as zip\">$filename</a>";
  }else{
    $ficon = groupicon($file);
    $img = "skins/{$si}$ficon";
    if (!file_exists($realdir.$img))
      $img = "skins/{$skin}$ficon";
    if (!file_exists($realdir.$img))
      $img = "skins/$ficon";
    if (strstr($ficon,"thumb")==$ficon)
      $img = $ficon;
    print"
	<center><a class=icon><img
	src=\"$img\" width=$w height=$h 
	onMouseDown=\"loadfile(this,'')\" title=\"$filename_t\" id=file
	alt=\"<b>$filename_t</b><br><br>Size: $size<br>
	Permissions:".decoct(fileperms($file)%01000)."<br><br>
	Modified: ".date('d-m-y, G:i', $stat[9])."<br>
	Accessed: ".date('d-m-y, G:i', $stat[8])."\" onDblClick=\"$dblclick;\" spec='$spec' 
	onError=\"this.src='skins/file.gif';\"></a><br><a 
	class=name href='?action=Download&file=$filename_e&dir=$dir_e' 
	title=Download>$filename</a>";
  }
}

#----------------------------------------

if ($action=="Save")
  header("X-XSS-Protection: 0"); # bugfix for new browsers
if ($action=="Download")
  {
  download();
  die();
  }
else
  www_page_open();


if ($action=="Open" && !is_dir("$dir/$file")){
  $D = explode("/",$dir);
  print"<title>$file in ".end($D)." - Edit - PHP Navigator</title>
  <link rel=stylesheet href='{$skincss}' type=text/css />
  <body style='margin:0px; background-color:ButtonFace; width:100%;' onResize='fixResize();'>";
  view($file,$dir);
  www_page_close();
  die();
}else{
#------------- THE NEW BIT --------------

  $incCSS = file_get_contents("skins/windowsxp/inc/xppanel.css");
  $incJS = file_get_contents("skins/windowsxp/inc/dthmlgoodies_xp_panel.js");
  $icnh = is_array($icn_size) ? $icn_size[1]+32 : '64';
  $icnh += $browsp;

  print <<<HTML
{$top}
<html>
<head>
<link rel='icon' href='./favicon.png' type='image/x-icon' />
<!-- <link rel=stylesheet href='{$skincss}' type=text/css /> -->
<style type=text/css >
html,form#f{
  height:100%;
}
body{   
  width:100%;
  height:100%;
  margin:0px;
  padding:0px;
  font-family: 'Trebuchet MS', 'Lucida Sans Unicode', Arial, sans-serif;
  background:transparent;
  overflow: hidden;
}
td{
  vertical-align:top;
}
table.window{
  font-size:11px;
  background-color:ThreeDFace;
  border-bottom:ButtonShadow 1px solid;
}
table.tasks,table#filestable,table#context{
  font-size:11px;
}
td.head{background-color:ActiveCaption; color:CaptionText; font-weight:bold;}
td.lxophead{background-color:ActiveCaption; color:white; font-weight:bold;}
td.lxffhead{background-color:blue; color:CaptionText; font-weight:bold;}

a,a:link,a:active,a:visited{text-decoration:none; color: black;}
.button{
  margin-left:5;
  margin-right:5;
}
.button,.IEbutton{
  cursor:default;
  border:ButtonFace 1px solid;
  background-color:ButtonFace;
}
.button:hover,.IEbutton:hover{
  border-right:ButtonShadow 1px solid;
  border-top:ButtonHighlight 1px solid;
  border-left:ButtonHighlight 1px solid;
  border-bottom:ButtonShadow 1px solid;
}
.buttonrow{cursor:default;}
.leftbutton{border:none;}
.seperator{
  background:ButtonFace;
  border-right:ButtonHighlight 1px solid;
  border-top:ButtonShadow 1px solid;
  border-left:ButtonShadow 1px solid;
  border-bottom:ButtonHighlight 1px solid;
  margin-left:10px;
  margin-right:10px;
}
.name{
  background-color:#FFFFFF;
  color:black;
  text-decoration:none;
  border:none;
  z-index:100;
}
.info{
  font-size:11px;
  background-color:#BBDDFF;
  border:none;
  color:MenuText;
}
.context{
  border:1px solid black;
  background-color:ButtonFace;
  line-height:18px;
  cursor:default;
  font-size:x-small;
  z-index:99;
  visibility:hidden;
  position:absolute;
}
.contbar{background-color:ButtonFace;}
.contitem{
  display:block;
  text-decoration:none;
  color:black;
  padding-left:4px;
  padding-right:4px;
  background-color:ButtonFace;
}
.contitem:hover{
  background-color:Highlight;
  color:white;
}

form {
  border:0px;
  padding:0px;
}

.FileList{
font-size: 0px;
}

.iconFile {
w idth:100px;
height:{$icnh}px;
p osition:relative;
float:left;
b order:1px red solid;
}

.iconFile table{
b order:1px blue solid;
}

.name {
f ont-family: clean, terminal;
font-size: 11px;
padding: 0 4 0 4;
}

{$incCSS}
</style>
<script type="text/javascript">
{$incJS}
</script>
<!--[if lte IE 6]>
<link rel=stylesheet type=text/css href=inc/pngfix.css />
<![endif]-->
<script src=inc/windows.js type=text/javascript></script>
<script src=inc/$mode.js type=text/javascript></script>
<script language=JavaScript>
sID = null;

function addressDown(){
  clearTimeout(sID);
  xObj = document.getElementById('addressBar');
  xObj.style.zIndex = 1;
  xTop = parseInt(xObj.style.top);
  if (xTop<-1) {
    xObj.style.top = (xTop+1)+'px';  
    sID=setTimeout('addressDown();',15);
  }
  document.forms[0].go.focus();
}

function addressUp(){
  clearTimeout(sID);
  xObj = document.getElementById('addressBar');
  xTop = parseInt(xObj.style.top);
  if (xTop>-20){
    xObj.style.top = (xTop-1)+'px';
    sID=setTimeout('addressUp();',15);
  }else{
    xObj.style.zIndex = -1;
  }
}

uID = null;
isMovingU = false;
isUpU = false;

function uploadUp(){
  clearTimeout(uID);
  isMovingU = true;
  xObj = document.getElementById('uploadBar');
  xHgt = parseInt(xObj.style.height);
  if (xHgt<26){
    xObj.style.height = (xHgt+2)+'px';
    uID=setTimeout('uploadUp();',15);
  }else{
    isUpU = true;
    isMovingU = false;
  }
}

function uploadDown(){
  clearTimeout(uID);
  isMovingU = true;
  xObj = document.getElementById('uploadBar');
  xHgt = parseInt(xObj.style.height);
  if (xHgt>{$ULheight}) {
    xObj.style.height = (xHgt-2)+'px';
    uID=setTimeout('uploadDown();',15);
  }else{
{$ULp}
    isUpU = false;
    isMovingU = false;
  }
}

function checkUploadUp(){
  if(!isMovingU && isUpU) uID=setTimeout('uploadDown();',15);
}

</script>
<script language=JavaScript>
function doFList(){
  if (window.innerWidth){
    width = window.innerWidth;
    height = window.innerHeight;
  }else{
    width = document.body.clientWidth;
    height = document.body.clientHeight;
  }
  document.getElementById('Upload').style.width = (width-200) + 'px';
  document.getElementById('Stat').style.width = (width-200) + 'px';
  xObj = document.getElementById('FileList');
  xObj.style.width = (width-200) + 'px';
  xObj.style.height = (height-36) + 'px';
}
</script>
</head>
<body onLoad='select();' onScroll='movetooltip();' onResize='doFList();'><form action='?' method=POST name=f id=f >
HTML;

#----------------------------------------
}

// printbuttons($dir,0); 
$IE = "<a>";
if (substr_count($_SERVER["HTTP_USER_AGENT"],"MSIE")>0) 
  $IE = '<a href="" onclick="return(false);" class=IEbutton>';

print "<div id=warnings>";

#------------------ACTIONS----------------

if ($action=="Up")
  up($dir);

if ($action=="Upload")
  upload($dir);
 
if ($action=="Save")
  save($file);

if ($action=="New folder")
  require_once("newfolder.php");

if ($action=="New file")
  require_once("newfile.php");
 
if ($action=="Chmode")
  require_once("chmod.php");
 
if ($action=="Delete")
  require_once("delete.php");
 
if ($action=="Rename")
  require_once("rename.php");
 
if ($action=="Copy")
  require_once("copy.php");

if ($action=="Move")
  require_once("move.php");

if ($action=="Extract")
  require_once("extract.php");

if ($action=="wget")
  require_once("wget.php");

if ($dir)
  chdir($dir);
if ($action=="Open"&&is_dir("$dir/$file"))
  @chdir($file);


$dir = getcwd();
$dispdir = gethostedpath($dir);
$xTitle = folderin($dir);

#-------------------- NEW ---------------------

function browseHere($xPath){
  global $server_root, $browser_root;
  for ($i=0;$i<count($server_root);$i++){
    if (substr_count($xPath,$server_root[$i])>0)
      return array(str_replace('/','\/',str_replace("\\","\\\\",$server_root[$i])),$browser_root[$i]);
  }
  return array('undefined','undefined');
}

$dir_bh = browseHere($dir);

#----------------------------------
print "</div>
<div id=addressBar style='position:absolute; width:100%; border:0px; top:-20px; left:0px; z-index:-1;'><input name=go id=go type=text style='width:100%' value='$dispdir' onMouseOver=\"sID=setTimeout('addressDown();',15);\" onBlur=\"sID=setTimeout('addressUp();',15);\"></div>
<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
<tr><td><div style='font-size:3px; height:3px; width:100%;' onMouseOver=\"document.getElementById('addressBar').style.zIndex=1; sID=setTimeout('addressDown();',15);\">&nbsp;</div>
<div id=Stat style='position:absolute; t op:20px; left:0px; width:100%;' ><center><div id=thestatus style='font-size:xx-small;'>
";

if (is_array($msg))  #printing all error messages
  foreach($msg as $m)
    print "$m :";
else
  print "$msg "; 
print "<br>Click on a file icon to view its details</div></div>";

print "<div id=FileList class=FileList align=center style='position:absolute; top:6px; left:0px border:0px red solid; width:100%; height:100%; margin-top:30px; overflow:hidden; overflow-y:scroll;'>";
if ($action!="Edit" && $action!="Search") { # exploring the files
  explore($dir); 
  $se = "This Folder";
}
if ($action!="Edit" && $action=="Search") { # file & contents search
  search($dir); 
  $se = "Serach";
}
print "</div>";

$xTitle = folderin($dir);

#------------- THE NEW PANEL ------------

print "</td><td width=5% height=100% style='height:100%;'>";

$taskskin = $GLOBALS['skin'];
$gi       = $GLOBALS['groupimgs'];
if (is_array($tsk_size)){
  $w = $tsk_size[0];
  $h = $tsk_size[1];
}else{
  $w = '16';
  $h = $w;
}
if (!is_array($tsk_size) || $no_tsk)
  $taskskin = "";

if (file_exists("{$realdir}skins/{$skin}screenshot.png"))
  $ex_here = "thumb.php?x=".$w."&y=".$h."&img=skins/{$skin}screenshot.png";
else
  $ex_here = "thumb.php?x=".$w."&y=".$h."&img=screenshot.png";

$dir_e = urlencode($dir);
$ajax = "<br>Working in <b>'$mode'</b> mode";
$eh = file_exists("skins/".$taskskin."edithtml".$gi) ? "skins/".$taskskin."edithtml".$gi : 'skins/edithtml.gif';
$ec = file_exists("skins/".$taskskin."editcode".$gi) ? "skins/".$taskskin."editcode".$gi : 'skins/editcode.gif';
$vb = file_exists("skins/".$taskskin."html".$gi)     ? "skins/".$taskskin."html".$gi     : 'skins/html.gif';

$filetasks  = "<table id=filetasks class=tasks border=0 cellspacing=0>";
$filetasks .= "<tr><td><a href='' onClick='searchfile();return(false);'><img src=skins/{$taskskin}search{$gi} width=$w height=$h border=0 onError=\"this.src='skins/search.gif';\"></a></td><td><a href='' onClick='searchfile();return(false);' class=leftitem>File Search</a><td></tr>";
$filetasks .= "<tr><td><a href='' onClick='extract();return(false);'><img src=skins/{$taskskin}extract{$gi} width=$w height=$h border=0 onError=\"this.src='skins/extract.gif';\"></a></td><td><a href='' onClick='extract();return(false);' class=leftitem>Extract Here</a><td></tr>";
$filetasks .= "<tr><td><a href='' onClick='thumbnail();return(false);'><img src=skins/{$taskskin}view{$gi} width=$w height=$h border=0 onError=\"this.src='skins/view.gif';\"></a></td><td><a href='' onClick='thumbnail();return(false);' class=leftitem>View as thumbnail</a><td></tr>";
$filetasks .= "<tr><td><a href='' onClick='openeditor();return(false);' title='Edit HTML [Shift+Ctrl+H]'><img src=skins/{$taskskin}edithtml{$gi} width=$w height=$h border=0 onError=\"this.src='skins/edithtml.gif';\"></a></td><td><a href='' onClick='openeditor();return(false);' title='Edit HTML [Shift+Ctrl+H]' class=leftitem>Open in HTML Editor</a><td></tr>";
$filetasks .= "<tr><td><a href='' onClick='opensource();return(false);' title='Edit Code [Shift+Ctrl+S]'><img src=skins/{$taskskin}editcode{$gi} width=$w height=$h border=0 onError=\"this.src='skins/editcode.gif';\"></a></td><td><a href='' onClick='opensource();return(false);' title='Edit Code [Shift+Ctrl+S]' class=leftitem>Open in Code Editor</a><td></tr>";
if (file_exists($realdir."DevEdit/editor.php"))
$filetasks .= "<tr><td><a href='' onClick='opendevedit();return(false);' title='Edit with DevEdit (IDE) [Shift+Ctrl+I]'><img src=thumb.php?x=$w&y=$h&img=DevEdit/DevEdit.gif width=$w height=$h border=0 onError=\"this.src='DevEdit/DevEdit.gif';\"></a></td><td><a href='' onClick='opendevedit();return(false);' title='Edit with DevEdit (IDE) [Shift+Ctrl+I]' class=leftitem>Open in DevEdit </a><td></tr>";
$filetasks .= "<tr><td><a href='' onClick='browseHere();return(false);' title='View in Browser'><img src={$vb} width=$w height=$h border=0 onError=\"this.src='skins/html.gif';\"></a></td><td><a href='' onClick='browseHere();return(false);' title='View in Browser' class=leftitem>View in Browser</a><td></tr>";
$filetasks .= "<tr><td><a href=\"?dir=$dir_e\" onClick='' title='Explore from Here' target=_blank><img src=$ex_here width=$w height=$h border=0 onError=\"this.src='favicon.png';\"></a></td><td><a href=\"?dir=$dir_e\" onClick='' target=_blank title='Explore from Here' class=leftitem>Explore from Here</a><td></tr>";
if (file_exists($realdir."kovia.php"))
$filetasks .= "<tr><td><a href='kovia.php?dir=$dir_e'><img src=skins/{$taskskin}asicons{$gi} width=$w height=$h border=0 onError=\"this.src='skins/asicons.gif';\"></a></td><td><a href='kovia.php?dir=$dir_e' class=leftitem>View as Icons</a></td></tr>";
$filetasks .= "</table>";

$othertasks  = "<table id=othertasks class=tasks border=0 cellspacing=0>";
$othertasks .= "<tr><td><a href='windows.php?dir=$dir_e'><img src=skins/{$taskskin}asicons{$gi} width=$w height=$h border=0 onError=\"this.src='skins/asicons.gif';\"></a></td><td><a href='windows.php?dir=$dir_e' class=leftitem>Original Explorer</a></td></tr>";
$othertasks .= "<tr><td><a href='details.php?dir=$dir_e'><img src=skins/{$taskskin}asdetails{$gi} width=$w height=$h border=0 onError=\"this.src='skins/asdetails.gif';\"></a></td><td><a href='details.php?dir=$dir_e' class=leftitem>Original as Details</a></td></tr>";
$othertasks .= "<tr><td><a href='windowsxp.php?dir=$dir_e'><img src=skins/{$taskskin}asdetails{$gi} width=$w height=$h border=0 onError=\"this.src='skins/asdetails.gif';\"></a></td><td><a href='windowsxp.php?dir=$dir_e' class=leftitem>WindowsXP Explorer</a></td></tr>";
$othertasks .= "<tr><td><a href='atari.php?dir=$dir_e'><img src=thumb.php?x=$w&y=$h&img=skins/atari/screenshot.png width=$w height=$h border=0 onError=\"this.src='skins/atari/screenshot.png';\"></a></td><td><a href='atari.php?dir=$dir_e' class=leftitem>Atari Explorer</a></td></tr>";
$othertasks .= "</table>";

print <<<HTML
<div id="dhtmlgoodies_xpPane" style="z-index:2;">
<div class="dhtmlgoodies_panel" >
<div id=folderinfo name=folderinfo>
Total files: 56<br>
Permissions: 775<br>
</div>   
</div>
<div class="dhtmlgoodies_panel">
<div id=tasks>
{$filetasks}
</div>
</div>
<div class="dhtmlgoodies_panel">
<div id=othertasks>
{$othertasks}
</div>
</div>
<div class="dhtmlgoodies_panel">
<div id=info name=info>
</div>
</div>
<div class="dhtmlgoodies_panel">
<div>
<div width=100% id=thumb name=thumb style="text-align:center;">
</div>
</div>
</div>
<div class="dhtmlgoodies_panel">
<div>
User IP: {$_SERVER['REMOTE_ADDR']}$ajax<br>
Encoding: <b>{$encoding}</b><br>
&#00149;  <a href='' onClick='config();return(false);'><u>Configure PHP Navigator</u></a><br>
&#00149;  <a href='' onClick='about();return(false);'><u>About PHP Navigator</u></a><br>
&#00149;  <a href=server.php target='_blank'><u>View Server Info</u></a><br>
&#00149;  <a href='' onClick='favourites();return(false);'><u>Favourites</u></a><br>
&#00149;  <a href='' onClick='help();return(false);'><u>Quick Help</u></a><br>
&#00149;  <a href='./'><u>Restart</u></a><br>
</div>
</div>
<div style="text-align:center;width:100%;font-size:0.7em;padding-top:10px;padding-bottom:10px;">prototype skin: <b>alternate</b></div>
</div>
HTML;

#----------------------------------------

print <<<HTML
</td></tr></table>
<div id=rckovia style="d isplay:none;"><table id=context class=context border=0 cellpadding=0 cellspacing=0 style="top:100px;">
<tr id=conDir ><td class=contbar><img src=skins/{$skin}dir{$groupimgs} height=16 width=16 onError="this.src='skins/dir.gif';"></td><td><a href="javascript:opendir()" class="contitem"><b>Open </b></a></td></tr>
<tr id=conSep0><td class=contbar colspan=2><hr/></td></tr>
<tr id=conRen ><td class=contbar><img src=skins/{$skin}rename{$groupimgs} height=16 width=16 onError="this.src='skins/rename.gif';"></td><td><a href="" onClick="rename(f);return(false);" class="contitem">Rename </a></td></tr>
<tr id=conDel ><td class=contbar><img src=skins/{$skin}delete{$groupimgs} height=16 width=16 onError="this.src='skins/delete.gif';"></td><td><a href="" onClick="delet(f);return(false);" class="contitem">Delete </a></td></tr>
<tr id=conCopy><td class=contbar><img src=skins/{$skin}copy{$groupimgs} height=16 width=16 onError="this.src='skins/copy.gif';"></td><td><a href="" onClick="copy(f);return(false);" class="contitem">Copy to</a></td></tr>
<tr id=conMove><td class=contbar><img src=skins/{$skin}move{$groupimgs} height=16 width=16 onError="this.src='skins/move.gif';"></td><td><a href="" onClick="move(f);return(false);" class="contitem">Move to</a></td></tr>
<tr id=conSep1><td class=contbar colspan=2><hr/></td></tr>
<tr id=conNewF><td class=contbar><img src=skins/{$skin}newfile{$groupimgs} height=16 width=16 onError="this.src='skins/newfile.gif';"></td><td><a href="" onClick="newfile(f);return(false);" class="contitem">New File </a></td></tr>
<tr id=conNew ><td class=contbar><img src=skins/{$skin}newfolder{$groupimgs} height=16 width=16 onError="this.src='skins/newfolder.gif';"></td><td><a href="" onClick="newfolder(f);return(false);" class="contitem">New Folder </a></td></tr>
<!--
<tr id=conSep2><td class=contbar colspan=2><hr/></td></tr>
<tr id=conNewF><td class=contbar><img src=skins/{$skin}view{$groupimgs} height=16 width=16 onError="this.src='skins/view.gif';"></td><td><a href="" onClick="thumbnail();return(false);" class="contitem">Thumbnail </a></td></tr>
<tr id=conNew ><td class=contbar><img src=skins/{$skin}image{$groupimgs} height=16 width=16 onError="this.src='skins/image.gif';"></td><td><a href="" onClick="thumbnail(f);return(false);" class="contitem">Preview </a></td></tr>
<tr id=conSep3><td class=contbar colspan=2><hr/></td></tr>
<tr id=conNewF><td class=contbar><img src=skins/{$skin}extract{$groupimgs} height=16 width=16 onError="this.src='skins/extract.gif';"></td><td><a href="" onClick="extract(f);return(false);" class="contitem">Extract </a></td></tr>
<tr id=conNew ><td class=contbar><img src=skins/{$skin}zip{$groupimgs} height=16 width=16 onError="this.src='skins/zip.gif';"></td><td><a href="" onClick="extract(f);return(false);" class="contitem">Compress </a></td></tr>
-->
</table></div>
<div id=zipinfo name=zipinfo onClick=hide_info() style="position:absolute; bottom:10px; right:230px; cursor:default; background:InfoBackground; border:1px solid black; font-size:8pt; padding:4px; visibility:hidden; opacity:.75; filter:alpha(opacity=75);"></div>
<input name=dir id=dir type=hidden value='$dir'>
</form>
HTML;
#----------------------------------------

#-----Calculate Max Upload Size--
  $size_str = ini_get('upload_max_filesize');
  $z=0; $size=0;
  while (ctype_digit($size_str[$z])){
    $size .= $size_str[$z];
    $z++;
  }
  $size = intval($size);
  $max_size = $size.$size_str[$z];
  if ($size_str[$z]=="G"||$size_str[$z]=="g")
    $size = $size*1024*1024*1024;
  elseif ($size_str[$z]=="M"||$size_str[$z]=="m")
    $size = $size*1024*1024;
  elseif ($size_str[$z]=="K"||$size_str[$z]=="k")
    $size = $size*1024;

#--------UPLOAD FORM----------
print"
<div id=Upload style='position:absolute; width:100%; border:0px; bottom:0px; left:0px;'>
<div style='font-size:3px; line-height:3px; width:100%; height:3px; border:0px blue solid; cursor:hand; background-color:grey;' onClick=\"uID=setTimeout('uploadDown();',15);\" onMouseOver=\"if(isMovingU || isUpU) return; uID=setTimeout('uploadUp();',15);\">&nbsp;</div>
<div id=uploadBar style='width:100%; border:0px; overflow:hidden; height:{$ULheight}px; background-color:lightgrey;' onClick=\"uID=setTimeout('uploadDown();',15);\"><form name=f2 id=f2 enctype=multipart/form-data method=POST action='?' onSubmit='return upload();'>
<input type=hidden name=MAX_FILE_SIZE value='$size'><input type=hidden id=gdir name=dir value='$dir'>
&nbsp;<input type=submit name=action value=Upload title=' max file size $max_size '>&nbsp;";
for($i=1;$i<=$uploads;$i++){
  print" &nbsp;<input type=file name=upfile[] id=upfile$i >&nbsp;";
}
print"</form></div>
</div>
";

#----------------------------------------
print <<<HTML
<script type="text/javascript">
initDhtmlgoodies_xpPane(Array('{$se}','File and Folder Tasks','Other Tasks','Details','Thumbnail View','Information'),Array(true,false,false,true,true,true),Array('ThisFolder','ThisTasks','OtherTasks','Details','Thumb','Info'));
</script>
<script type="text/javascript">
// patched from atari skin

document.title = '{$xTitle} - Browsing - PHP Navigator';

function browseHere(){
//alert(decodeURIComponent(f.dir.value).replace(/{$dir_bh[0]}/,'{$dir_bh[1]}')+'/');
  if (fname=='') {
    extWindow(decodeURIComponent(f.dir.value).replace(/{$dir_bh[0]}/,'{$dir_bh[1]}')+'/');
    return; }
  if (oldficon.getAttribute('spec').indexOf('d')>0) 
    extWindow(decodeURIComponent(f.dir.value).replace(/{$dir_bh[0]}/,'{$dir_bh[1]}')+'/'+fname+'/');
  else
    extWindow(decodeURIComponent(f.dir.value).replace(/{$dir_bh[0]}/,'{$dir_bh[1]}')+'/'+fname);
}

function extWindow(xUrl){
  window.open(xUrl,"","");
}

//document.title = '{$xTitle} - Browsing - Kovia - PHP Navigator';
skintype='$groupimgs';
imgW = $icn_size[0];
imgH = $icn_size[1];

if (window.innerWidth){
  width = window.innerWidth;
  height = window.innerHeight;
}else{
  width = document.body.clientWidth;
  height = document.body.clientHeight;
}

doFList();

</script>
<title>$xTitle - Browsing</title>
</body>
</html>
HTML;

www_page_close();