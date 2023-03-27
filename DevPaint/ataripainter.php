<?php
# Atari DevPaint skin - 1.0b
# Edited: 26/10/2008
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
    $DE_rootdir = "./"; // needs functions in DevPaint
  }
}

chdir($DE_rootdir); 

$dir    = @$_REQUEST['dir'];
$action = @$_REQUEST['action'];
$file   = @$_REQUEST['file'];

$dir    = urldecode($dir);
$file   = urldecode($file);
$DEBUG  = isset($_REQUEST['debug'])?'inline':'none';
 
$skin = "";

include_once("functions.php");
include_once("config.php");

//$compress = false; // for dev work
www_page_open();
authenticate();

getcookies();

include_once("skin.php"); // setup skins

include_once("config_patch.php"); // extra data required for "browse this"


$body = '<body id=DesktopBody name=DesktopBody topmargin=0 leftmargin=0 marginheight=0 marginwidth=0 onload="startUp();" onbeforeunload="storeWindows();" onclick="dcheck(event);" onselectstart="event.cancelBubble=true; return(false);" ondragstart="return(false);" ondrag="doMover(event);" ondragend="doEnd(event);" onmousemove="doMover(event);" onmouseup="doEnd(event);" style="width:100%; height:100%; background-color:#00FF00;">';

$top = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';

if (substr_count($_SERVER['HTTP_USER_AGENT'],"Windows")>0){
  $ed_fix = "overflow:hidden;";
  $font_large = "'MS Gothic'";
  $font_size = "18px";
  $font_weight = "bold";
  $font_small = "'MS Gothic'";
  $font_size_small = "12px";
  $font_fix = "line-height:20px;";
}elseif (substr_count($_SERVER['HTTP_USER_AGENT'],"PCLinuxOS")>0){
  $ed_fix = "overflow:hidden;";
  $font_large = "Fixed";
  $font_weight = "normal";
  $font_size = "20px";
  $font_small = "Clean";
  $font_size_small = "11px";
  $font_fix = "line-height:20px;";
}else{
  $ed_fix = "overflow:hidden;";
  $font_large = "'Andale Mono', Fixed";
  $font_weight = "normal";
  $font_size = "17px";
  $font_small = "'Andale Mono', Clean";
  $font_size_small = "10px";
  $font_fix = "line-height:20px;";
}

// IE patch
if (substr_count($_SERVER['HTTP_USER_AGENT'],"MSIE")==0) $jsinit = '<script src=./atari/init.js type="text/javascript"></script>';
//else $jsinit = '<script type="text/javascript">fgColor.style.border = "1px solid gray";</script><script type="text/javascript">bgColor.style.border = "1px solid #C0C0C0";</script>';

$iconHeight = (2*intval($font_size_small))+$icn_size[1]+5;

function atarieditfile($file)
{
  global $dir, $realdir, $no_icn, $icn_size, $use_layout, $DE_rootdir;
  $skin = $GLOBALS['skin'];
  $gi   = $GLOBALS['groupimgs'];
  if(is_array($icn_size)){
    $w = $icn_size[0];
    $h = $icn_size[1];
  }else{
    $w = '32';
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
	$img = "skins/{$skin}$ficon";
	$imgsel = "skins/{$skin}".str_replace($gi,"_sel{$gi}",$ficon);
	if (!file_exists($realdir.$img)) $img = "skins/$ficon";
	if (strstr($ficon,"thumb")==$ficon) $img = $ficon."&border=false";
	if (!file_exists($realdir.$imgsel)) $imgsel = $img;
	return "<center><a class=icon><img class=ficon aid=\"$aid\"
	    src=\"{$DE_rootdir}$img\" width=$w height=$h name=\"$iid\" id=\"$iid\" 
	    onMouseDown=\"clearMenus(); atariIcon(this,'i');\" title=\"$filename_t\"
	    alt=\"File: $filename_t<br>Size: $size<br>
	    Permissions: ".decoct(@fileperms($dir."/".$file)%01000)."<br>
	    Modified: ".date('d-m-y, G:i', $stat[9])."<br>
	    Accessed: ".date('d-m-y, G:i', $stat[8])."\" spec='$spec' 
	    onDblClick=\"if(DesktopDialog){return(false);} DevPaint0.style.display='inline'; atariClear(); return(false);\"
	    onError=\"this.src='{$DE_rootdir}skins/atari/file.gif';\" atariimg='$img' atarisel='$imgsel'></a><br><a 
	    class=name name=\"$aid\" id=\"$aid\" aid=\"$iid\" href='javascript:void(0)' onClick=\"if(DesktopDialog){return(false);}\" 
	    onDblClick=\"if(DesktopDialog){return(false);} DevPaint0.style.display='inline'; atariClear(); return(false);\"
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


// if (isset($_POST['save'])) imgsave($file);
$D = explode("/",$dir);
$title = "$file in ".end($D)." - DevPaint";
print <<<HTML
{$top}
<html>
<head>
<link rel='icon' href='./DevPaint.gif' type='image/x-icon' />
<style type=text/css >
html{
  height: 100%;
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
form {
  border: 0px;
  margin: 0px;
  padding: 0px;
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
.iconFile {
  float: left;
  height: {$iconHeight}px;
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

.bigFont {
  font-family: {$font_large};
  font-size: {$font_size};
  font-weight: {$font_weight};
  {$font_fix}
}

.smallFont{
  font-family: {$font_small};
  font-size: {$font_size_small};
  font-weight:normal;
}

.thinBox{
  border:1px solid black;
  padding:0px;
  margin:0px;
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
  padding: 0 2px 0 2px;
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
<script src=./atari/atari.js type=text/javascript></script><!-- _new_ include dir, for final version, fix before -->
<script src=./atari/ataripainter.js type="text/javascript"></script>
<script src=./atari/devpaint.js type="text/javascript"></script>

HTML;

  $desktop = 'DevPaint';
//new windowOrder,   X,   Y,   W,   H, W-63, H-65, H-65-42, display, id
  $winDefaults = '|0,70px,20px,600px,500px,inline,DevPaint0,3|1,450px,20px,395px,117px,none,DevOutput,2|2,50px,650px,215px,247px,none,DevCalc,2|3,40px,500px,423px,447px,inline,DevFont,2|4,40px,625px,220px,335px,inline,DevTools,2|5,380px,625px,210px,260px,inline,DevColors,2|6,40px,325px,220px,335px,inline,DevHistory,2|7,40px,25px,220px,335px,inline,DevLayers,2|8,380px,325px,210px,260px,inline,DevPalette,2';
  loadWindows($desktop);

  $dir_e = urlencode($dir);
  $file_e = urlencode($file);
  $folder = end($D);

  $dir_bh = browseHere($dir);

?>
<script language=JavaScript>
function browseHere(){
  if (fname=='') {
    extWindow(decodeURIComponent(f.dir.value).replace(/<?= $dir_bh[0] ?>/,'<?= $dir_bh[1] ?>')+'/');
    return;
  }
  if(oldficon.getAttribute('spec').indexOf('d')>0) 
    extWindow(decodeURIComponent(f.dir.value).replace(/<?= $dir_bh[0] ?>/,'<?= $dir_bh[1] ?>')+'/'+fname+'/');
  else
    extWindow(decodeURIComponent(f.dir.value).replace(/<?= $dir_bh[0] ?>/,'<?= $dir_bh[1] ?>')+'/'+fname);
}

DesktopName = '<?= $desktop ?>';

</script>
<script language=JavaScript>
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
}
</script>
</head>
<?= $body ?><form name=f id=f action='' method=POST style="padding:0px; margin:0px; border:0px;"  onSubmit="return(false);">
<input type=hidden name=dir id=dir value="<?= $dir ?>">
<input type=hidden name=file id=file value="<?= $file ?>">
<input type=hidden name=action id=action value=Save>
<!-- MENU: main menu-->
<span style="position:absolute; left:0px; top:0px; width:100%;"><table border=0 width=100% height=20 cellpadding=0 cellspacing=0>
  <tr width=100% height=100%>
    <td width=100% height=20 valign=top><table class=big valign=top border=1 width=100% height=20 bgcolor=white cellpadding=0 cellspacing=0 style="border-collapse:collapse; border-width:0px 0px 1px 0px; border-color:black;">
  <tr>
    <td width=100% height=100% valign=bottom name=Menu style="border:none; cursor:default;">&nbsp;&nbsp;<a
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
							DevColors.style.display='inline';
							toFront(DevColors);
							">&nbsp;&nbsp;Color&nbsp;Chart&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Desk.style.visibility='hidden';
							clearMenus();
							ModalFont.style.display='inline';
							toFront(ModalFont);
							scrollSet(ModalFontScrollV,FontList);
							">&nbsp;&nbsp;Font&nbsp;Select&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	Desk.style.visibility='hidden';
							clearMenus();
							DevTools.style.display='inline';
							toFront(DevTools);
							">&nbsp;&nbsp;Tools&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)">&nbsp;&nbsp;Exit&nbsp;webTOS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
</span>
<!-- MENU: File -->
<span name=File id=File class=big style="visibility:hidden; top:19px; left:78px; position:absolute; width:260px; height:240px; z-index:65535; background-color:white; border:1px solid black;"> 
<a class=MenuItem href="javascript:void(0)" onClick="	File.style.visibility='hidden';
							oCenter(NewImage);
							NewImage.style.display='inline';
							DesktopDialog=true;
							">&nbsp;&nbsp;New Image&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[N]</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	File.style.visibility='hidden';
							oCenter(OpenImage);
							OpenImage.style.display='inline';
							DesktopDialog=true;
							">&nbsp;&nbsp;Open&nbsp;Image&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[O]</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	File.style.visibility='hidden';
							clearMenus();
							reloadfile();
							">&nbsp;&nbsp;Reload&nbsp;Image&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	File.style.visibility='hidden';
							dNum = currentImg.id.replace('DevPaint','');
							eval(currentImg.id + 'A.innerHTML=\'\';');
							eval(currentImg.id + '.style.display=\'none\';');
							eval('PaintWindows[' + dNum + '] = false;');
							clearMenus();
							">&nbsp;&nbsp;Close&nbsp;Image&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[C]</a><br>
<font class=MenuBlank>--------------------------</font><br>
<a class=MenuItem href="javascript:void(0)" onClick="	File.style.visibility='hidden';
							clearMenus();
							browseHere();
							">&nbsp;&nbsp;Open&nbsp;in&nbsp;Browser&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
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
							oCenter(Zoom);
							Zoom.style.display='inline';
							DesktopDialog=true;
							">&nbsp;&nbsp;Zoom...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	View.style.visibility='hidden';
							clearMenus();
							zoom(currentImg,1);
							">&nbsp;&nbsp;Zoom&nbsp;In&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[+]</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	View.style.visibility='hidden';
							clearMenus();
							zoom(currentImg,-1);
							">&nbsp;&nbsp;Zoom&nbsp;Out&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[-]</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	View.style.visibility='hidden';
							clearMenus();
							zoomTo(currentImg,DefaultZoom);
							">&nbsp;&nbsp;Default&nbsp;Zoom&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<a class=MenuItem href="javascript:void(0)" onClick="	View.style.visibility='hidden';
							clearMenus();
							zoomActual(currentImg);
							">&nbsp;&nbsp;Actual&nbsp;Size&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
<font class=MenuBlank>---------------------</font><br>
<a class=MenuItem href="javascript:void(0)" onClick="	View.style.visibility='hidden';
							clearMenus();
							toggleGuides(currentImg);
							">&nbsp;&nbsp;Guides&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><br>
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
<?php

$ficon = atarieditfile($file);

$xTitle = $file." in ".$folder;

if(strtolower(strrchr($file,"."))=='.bmp')
  include_once("lib/fromBMP.php");

  $img = "";
  $imgObj = "";
  $guides ='true';
  if($guides=='true') {
    $bdr = ' border:1px dotted grey;';
    $cs = 1;
  }else{
    $bdr = '';
    $cs = 0;
  }
  $zm = 2;


  $srcX = 0;
  $srcY = 0;
  $type = "";
  $src_file = $dir."/".$file;

if(is_file($src_file)){
  $imginfo = @getimagesize($src_file);
  $srcImage = "";
  $imgType = "";
if($imginfo){
  $srcX = $imginfo[0];
  $srcY = $imginfo[1];
  $type = $imginfo[2];

  switch($type)
    {
        case 1:
            if(function_exists('imagecreatefromgif'))
                $srcImage = imagecreatefromgif($src_file);
                $imgType = 'gif';
            break;
        case 2:
            if(function_exists('imagecreatefromjpeg'))
                $srcImage = imagecreatefromjpeg($src_file);
                $imgType = 'jpg';
                $comment = exif_read_data($src_file);
            break;
        case 3:
            if(function_exists('imagecreatefrompng'))
                $srcImage = imagecreatefrompng($src_file);
                $imgType = 'png';
            break;
        case 6:
            if(function_exists('imagecreatefrombmp'))
                $srcImage = imagecreatefrombmp($src_file);
                $imgType = 'bmp';
            break;
        case 15:
            if(function_exists('imagecreatefromwbmp'))
                $srcImage = imagecreatefromwbmp($src_file);
                $imgType = 'wbmp';
            break;
        case 16:
            if(function_exists('imagecreatefromxbm'))
                $srcImage = imagecreatefromxbm($src_file);
                $imgType = 'xbm';
            break;
        case 17:
            if(function_exists('imagecreatefromxpm'))
                $srcImage = imagecreatefromxpm($src_file);
                $imgType = 'xpm';
            break;
    }
  if(!$srcImage) $srcImage = @imagecreatefromstring(file_get_contents($src_file));
  if($srcImage) {
    $comment = '';
    $palette = $imginfo['bits']."bits";
    $tc = imageistruecolor($srcImage);
    $typ = ($tc)?'truecolor':'palette';
    $total = imagecolorstotal($srcImage);
    $trns = imagecolortransparent($srcImage);
    $trans = ($trns==-1)?'false':'true';
//    list($r,$g,$b,$alpha) = imagecolorsforindex($srcImage,$trans);
    if($trans=='true') $colors = imagecolorsforindex($srcImage,$trns);
    else $colors = imagecolorsforindex($srcImage,0);
    $bgcolor = "rgb(".$colors['red'].",".$colors['green'].",".$colors['blue'].")";
    $img = "<table border=0 bgcolor=#CCCCCC cellpadding=0 cellspacing=$cs style=\"$bdr\">\n";
    for($y=0;$y<$srcY;$y++){
      $img .= "<tr>";
      for($x=0;$x<$srcX;$x++){
        $color = imagecolorat($srcImage,$x,$y);
        $cn = ($typ=='palette')?"colno=$color":'';
        $colors = imagecolorsforindex($srcImage,$color);
        $img .= "<td width=$zm height=$zm onMouseDown=\"doTool(this);\" $cn style=\"background-color:rgb(".$colors['red'].",".$colors['green'].",".$colors['blue'].");\" alpha=".$colors['alpha']." >";
      }
      $img .= "</tr>\n";
    }
    imagedestroy($srcImage);
    $img .= "</table>\n";
    $imgUpdate = "updateInfoRow(DevPaint0); DevPaint0bg = document.getElementById('DevPaint0bg'); PaintWindows[0] = true; currentImg = DevPaint0;\n";
    $w = ($srcX*($zm+$cs))+4;
    $h = ($srcY*($zm+$cs))+4;
    $wh = " width:{$w}px; height:{$h}px; border-color:red;";
  }
}
}

if(!$srcX) {
  $srcX = 100;
  $srcY = 100;
  $imgType = 'gif';
//  $bgcolor = '#FF00FF';
  $bgcolor = 'rgb(255,0,255)';
  $trans = 'true';
  $total = 0;
  $palette = '32bit';
  $comment = 'created by AtariPainter';
  $typ = 'truecolor';
    $imgUpdate = "updateInfoRow(DevPaint0); DevPaint0bg = document.getElementById('DevPaint0bg'); PaintWindows[0] = true; currentImg = DevPaint0;\n";
    $w = ($srcX*($zm+$cs))+4;
    $h = ($srcY*($zm+$cs))+4;
    $wh = " width:{$w}px; height:{$h}px; border-color:red;";
}

  $imgObj .= "DevPaint0.xW = $srcX;\n";
  $imgObj .= "DevPaint0.yH = $srcY;\n";
  $imgObj .= "DevPaint0.guides = $guides;\n";
  $imgObj .= "DevPaint0.zoom = $zm;\n";
  $imgObj .= "DevPaint0.backgroundColor = '$bgcolor';\n";
  $imgObj .= "DevPaint0.transparent = $trans;\n";
  $imgObj .= "DevPaint0.typ = '$typ';\n";
  $imgObj .= "DevPaint0.colorTable = '';\n";
  $imgObj .= "DevPaint0.colorsTotal = '$total';\n";
  $imgObj .= "DevPaint0.palette = '$palette';\n";
  $imgObj .= "DevPaint0.paletteTable = '';\n";
  $imgObj .= "DevPaint0.format = '$imgType';\n";
  $imgObj .= "DevPaint0.compression = '';\n";
  $imgObj .= "DevPaint0.comment = '$comment';\n";
  $imgObj .= $imgUpdate;

print <<<HTML
<!-- ICON: editing file -->
<title>{$file} in {$folder} - Editing - Atari - DevPaint</title>
<table id=eFile border=0 style="top:20px; left:2px; position:absolute; font-size:0px;"><tr><td onmousedown=loadtd(this)>{$ficon}</td></tr></table>
<!-- WINDOW: Painter 0 -->
<div name={$windows['DevPaint0'][6]} id={$windows['DevPaint0'][6]} scrollBars=both xHnd=0 zid={$windows['DevPaint0'][7]} minW=120 minH=160 style="position:absolute; display:{$windows['DevPaint0'][5]}; top:{$windows['DevPaint0'][1]}; left:{$windows['DevPaint0'][2]}; width:{$windows['DevPaint0'][3]}; height:{$windows['DevPaint0'][4]}; z-index:{$windows['DevPaint0'][7]};" 
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront({$windows['DevPaint0'][6]});"
 onMouseDown="currentImg={$windows['DevPaint0'][6]} ;"
><div class=win_part_close onClick="if (DesktopDialog){return(false);} {$windows['DevPaint0'][6]}.style.display='none'; clearMenus();"></div>
<div class=win_part_title onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront({$windows['DevPaint0'][6]}); dragWindow({$windows['DevPaint0'][6]},event);"><center><table class=big border=0 cellspacing=0 cellpadding=0><tr><td class=big align=right nowrap name={$windows['DevPaint0'][6]}T id={$windows['DevPaint0'][6]}T style="cursor:default;">Editing {$file} in {$folder}</td></tr></table></center></div>
<div class=win_part_max onClick="if (DesktopDialog){return(false);} toFront({$windows['DevPaint0'][6]}); MaxMin({$windows['DevPaint0'][6]}); event.cancelBubble=true;"></div>
<div class=win_part_option name={$windows['DevPaint0'][6]}O id={$windows['DevPaint0'][6]}O style='cursor:default;'></div>
<div class=win_part_body style="overflow:hidden;"><table border=0 cellspacing=0 cellpadding=0 width=100% height=100% align=center><tr><td width=100% height=100% align=center valign=middle><div name={$windows['DevPaint0'][6]}A id={$windows['DevPaint0'][6]}A style="overflow:hidden;cursor:crosshair;$wh">{$img}</div></td></tr></table></div>
<div class=win_part_scrollV><div class=win_part_scrollV_up></div><div class=win_part_scrollV_slide></div><div class=win_part_scrollV_down></div></div>
<div class=win_part_scrollH><div class=win_part_scrollH_left></div><div class=win_part_scrollH_slide></div><div class=win_part_scrollH_right></div></div>
<div class=win_part_resize onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront({$windows['DevPaint0'][6]}); windowReSize({$windows['DevPaint0'][6]},event); event.cancelBubble=true;"></div>
<script language=JavaScript>
preRegister('{$windows['DevPaint0'][6]}');
//fname = "{$file}";
//ficon = document.getElementById('eFile').getElementsByTagName('img')[0];
//oldficon = ficon;
//restoreEditor();
//scrollSetBoth('DevPaint0',{$windows['DevPaint0'][6]}Body);
</script>
</div>
<!-- WINDOW: Tools -->
<div name={$windows['DevTools'][6]} id={$windows['DevTools'][6]} scrollBars=none xHnd=0 zid={$windows['DevTools'][7]} minW=240 minH=160 style="position:absolute; display:{$windows['DevTools'][5]}; top:{$windows['DevTools'][1]}; left:{$windows['DevTools'][2]}; width:{$windows['DevTools'][3]}; height:{$windows['DevTools'][4]}; z-index:{$windows['DevTools'][7]};" 
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront({$windows['DevTools'][6]});"
><div class=win_part_close onClick="if (DesktopDialog){return(false);} {$windows['DevTools'][6]}.style.display='none'; clearMenus();"></div>
<div class=win_part_title onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront({$windows['DevTools'][6]}); dragWindow({$windows['DevTools'][6]},event);"><table class=big border=0 cellspacing=0 cellpadding=0 align=center><tr><td align=right style="cursor:default; font-family:{$font_large}; font-size:{$font_size};">Tools</td></tr></table></div>
<div class=win_part_max onClick="if (DesktopDialog){return(false);} toFront({$windows['DevTools'][6]}); MaxMin({$windows['DevTools'][6]}); event.cancelBubble=true;"></div>
<div class=win_part_option></div>
<div class=win_part_body><div name={$windows['DevTools'][6]}A id={$windows['DevTools'][6]}A>
<table width=100% border=0 bgcolor=#CCCCCC cellpadding=0 cellspacing=1 style="border:1px dotted gray;">
<tr>
<td name=fgColor id=fgColor width=50 height=30 style="border:1px solid gray; background-color:rgb(0,0,0);" onClick="if (DesktopDialog){return(false);} selectedColor=this; updatePixelColor=false; updateSelectedColor();" onDblClick="selectedColor=this; openColorStore();"></td>
<td name=bgColor id=bgColor width=50 height=30 style="border:1px solid gray; background-color:rgb(255,255,255);" onClick="if (DesktopDialog){return(false);} selectedColor=this; updatePixelColor=false; updateSelectedColor();" onDblClick="selectedColor=this; openColorStore();"></td>
<td align=center>#<input class="thinBox bigFont" style="width:66px;" type=text name=WebColor id=WebColor size=5 maxlength=6 value='' onChange="setColor(this.name)" onFocus="if(DesktopDialog && colorChooserDisplay==null) this.blur();"></td>
<tr><td width=100% align=center colspan=3 class="smallFont">
Red:<input class="smallFont thinBox" type=text name=Red size=3 maxlength=3 onChange="setColor(this.name)" onFocus="if(DesktopDialog && colorChooserDisplay==null) this.blur();">
Green:<input class="smallFont thinBox" type=text name=Green size=3 maxlength=3 onChange="setColor(this.name)" onFocus="if(DesktopDialog && colorChooser.Display==null) this.blur();">
Blue:<input class="smallFont thinBox" type=text name=Blue size=3 maxlength=3 onChange="setColor(this.name)" onFocus="if(DesktopDialog && colorChooserDisplay==null) this.blur();">
</td></tr>
</table>
<table width=100% border=0 bgcolor=#CCCCCC cellpadding=0 cellspacing=1 style="border:1px dotted gray;">
<tr>
<td nowrap align=center style="cursor:default; padding:15px"><table 
border=0 bgcolor=white cellpadding=0 cellspacing=0 style="border-collapse:collapse; border:none; cursor:default; font-family:{$font_large}; font-size:{$font_size};">
<tr><td style="cursor:default; font-family:{$font_large}; font-size:{$font_size};">Draw:</td><td>&nbsp;</td>
<td style="border:1px solid black;"><a href="javascript:void(0)" name=ToolPencil id=ToolPencil class=ButtonItem 
onMouseDown="	this.style.color='white'; this.style.backgroundColor='black';
		ToolCrosshair.style.color='black';
		ToolCrosshair.style.backgroundColor='white';" 
		Tool = 'pencil';
		document.getElementById(currentImage.id+'A').style.cursor = 'default';
onClick="" 
>Pointer</a></td>
<td>&nbsp;&nbsp;</td>
<td style="border:1px solid black;cursor:default;"><a href="javascript:void(0)" name=ToolCrosshair id=ToolCrosshair class=ButtonItem style="color: white; background: black;" 
onMouseDown="	this.style.color='white'; this.style.backgroundColor='black';
		ToolPencil.style.color='black'; 
		ToolPencil.style.backgroundColor='white';" 
		Tool = 'crosshair';
		document.getElementById(currentImage.id+'A').style.cursor = 'crosshair';
onClick="" 
>Crosshair</a></td>
</tr></table></td></tr>
</table>
</div></div>
<div class=win_part_scrollV><div class=win_part_scrollV_up></div><div class=win_part_scrollV_slide></div><div class=win_part_scrollV_down></div></div>
<div class=win_part_scrollH><div class=win_part_scrollH_left></div><div class=win_part_scrollH_slide></div><div class=win_part_scrollH_right></div></div>
<div class=win_part_resize onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront({$windows['DevTools'][6]}); windowReSize({$windows['DevTools'][6]},event); event.cancelBubble=true;"></div>
<script language=JavaScript>
preRegister('{$windows['DevTools'][6]}');
</script>
</div>
<!-- WINDOW: Colors -->
<div name={$windows['DevColors'][6]} id={$windows['DevColors'][6]} scrollBars=none xHnd=0 zid={$windows['DevColors'][7]} minW=120 minH=160 style="position:absolute; display:{$windows['DevColors'][5]}; top:{$windows['DevColors'][1]}; left:{$windows['DevColors'][2]}; width:{$windows['DevColors'][3]}; height:{$windows['DevColors'][4]}; z-index:{$windows['DevColors'][7]};" 
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront({$windows['DevColors'][6]});"
 onMouseUp="setBackgroundColor();"
><div class=win_part_close onClick="if (colorChooserDisplay!=null) {resetColorChooser(); return(false);} if (DesktopDialog){return(false);} {$windows['DevColors'][6]}.style.display='none'; clearMenus();"></div>
<div class=win_part_title onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront({$windows['DevColors'][6]}); dragWindow({$windows['DevColors'][6]},event);"><table class=big border=0 cellspacing=0 cellpadding=0 align=center><tr><td align=right style="cursor:default; font-family:{$font_large}; font-size:{$font_size};">Colors</td></tr></table></div>
<div class=win_part_max onClick="if (DesktopDialog){return(false);} toFront({$windows['DevColors'][6]}); MaxMin({$windows['DevColors'][6]}); event.cancelBubble=true;"></div>
<div class=win_part_option></div>
<div class=win_part_body><div name={$windows['DevColors'][6]}A id={$windows['DevColors'][6]}A width=180 onMouseUp="setBackgroundColor();"><table border=0 cellpadding=0 cellspacing=0>
<tr><td width=180><script language=JavaScript>
document.write('<table id=ColorSquare width=180 border=0 cellspacing=0 cellpadding=0 style="cursor:crosshair;"><tr>');
/*
(top of fifth color bar)
   red 255,196,196 - 255,254,196
yellow 254,255,196 - 197,255,196
 green 196,255,197 - 196,255,255
L blue 196,252,255 - 196,196,255
D blue 197,196,255 - 255,196,255
  pink 255,196,254 - 255,196,197

bottom 225,225,225
(try row - see: 1stRowGenerator.html)
(255,0,0)-(255,249,0)(249,255,0)-(6,255,0)(0,255,6)-(0,255,255)(0,242,255)-(0,0,255)(13,0,255)-(255,0,255)(255,0,249)-(255,0,6)

(middle of color bar)
   red 255, 0 , 0  - 255,249, 0
yellow 249,255, 0  -  6 ,255, 0
 green  0 ,255, 6  -  0 ,255,255
L blue  0 ,242,255 -  0 , 0 ,255
D blue 13 , 0 ,255 - 255, 0 ,255
  pink 255, 0 ,249 - 255, 0 , 6 

bottom 127,127,127
(steps 6 or 13)

(3x6 or 2x6)
*/

baseColorRanges = new Array('255,0,0-255,249,0','249,255,0-6,255,0','0,255,6-0,255,255','0,242,255-0,0,255','13,0,255-255,0,255','255,0,249-255,0,6');

function colorRangeFill(xColorRangeArray){ // uses values in baseColors array
  xfirstColor = xColorRangeArray[0].split(',');
  xlastColor = xColorRangeArray[9].split(',');
  for (xi=0;xi<xfirstColor.length;xi++) { xfirstColor[xi] = parseInt(xfirstColor[xi]); xlastColor[xi] = parseInt(xlastColor[xi]); }
  if (xfirstColor[0]!=xlastColor[0]) { xCC = 0; }
  if (xfirstColor[1]!=xlastColor[1]) { xCC = 1; }
  if (xfirstColor[2]!=xlastColor[2]) { xCC = 2; }
  xDivisor = ((xlastColor[xCC]-xfirstColor[xCC])/10);
  for (xX=1;xX<9;xX++) {
    xNewColor = parseInt(xfirstColor[xCC]+(xX*xDivisor)).toString();
    if (xX!=9) {
      if (xCC==0) { xColorRangeArray[xX] = xNewColor + ',' + xfirstColor[1] + ',' + xfirstColor[2]; }
      if (xCC==1) { xColorRangeArray[xX] = xfirstColor[0] + ',' + xNewColor + ',' + xfirstColor[2]; }
      if (xCC==2) { xColorRangeArray[xX] = xfirstColor[0] + ',' + xfirstColor[1] + ',' + xNewColor; }
    }
  }
  return (xCC==0?'red':(xCC==1?'green':'blue'));
}

baseColors = new Array(baseColorRanges.length*10);
for (i=0;i<baseColorRanges.length;i++){
  colorBlock = new Array(10);
  colorBlock = baseColorRanges[i].split('-');
  colorBlock[9] = colorBlock[1];
  colorBlock[1] = '';
  colorVary = colorRangeFill(colorBlock);
  for (x=0;x<colorBlock.length;x++){
    baseColors[((i*10)+x)] = colorBlock[x];
  }
  colorBlock = null;
}
</script><script language=JavaScript>
xMD=false;
for (x=0;x<baseColors.length;x++) {
  document.writeln('<td><table cellspacing=0 cellpadding=0 border=0><tr><td width=3 height=6 style="background-color:rgb('+baseColors[x]+');" onMouseDown="selectColor(\''+baseColors[x]+'\');xMD=true;" onMouseMove="if(xMD){selectColor(\''+baseColors[x]+'\');}return(false);" onMouseUp="xMD=false;"></td></tr>');
  colorValue = baseColors[x].split(',');
  for (i=0;i<colorValue.length;i++) { colorValue[i] = parseInt(colorValue[i]); }
  for (y=256;y>127;y=y-4) {
    if (y!=256){
      if (colorValue[0]<=127) { colorValue[0] = colorValue[0] + 4; }
      else { colorValue[0] = colorValue[0] - 4; }
      if (colorValue[1]<=127) { colorValue[1] = colorValue[1] + 4; }
      else { colorValue[1] = colorValue[1] - 4; }
      if (colorValue[2]<=127) { colorValue[2] = colorValue[2] + 4; }
      else { colorValue[2] = colorValue[2] - 4; }
      newColor = colorValue.join(',');
      document.writeln('<tr><td width=3 height=6 style="background-color:rgb('+newColor+');" onMouseDown="selectColor(\''+newColor+'\');xMD=true;" onMouseMove="if(xMD){selectColor(\''+newColor+'\');}return(false);" onMouseUp="xMD=false;"></td></tr>');
    }
  }
  document.writeln('</table></td>');
}
document.writeln('</tr></table>');
</script></td><td width=10><script language=JavaScript>
// colorChooserGreyBar
function changeGreyBar(xColor){

}
document.write('<table id=GrayBar width=10 border=0 cellspacing=0 cellpadding=0 style="cursor:crosshair;">');
for(j=256;j>=0;j=j-8){
  if (j==256) {gray='255';}
  else {gray = j.toString();}
document.write('<tr><td width=10 height=6 style="background-color:rgb('+gray+','+gray+','+gray+');" onMouseDown="selectColor(\''+gray+','+gray+','+gray+'\');xMD=true;" onMouseMove="if(xMD){selectColor(\''+gray+','+gray+','+gray+'\');}return(false);" onMouseUp="xMD=false;"></td></tr>');
}
document.writeln('</table>');
</script></td></tr>
</table></div></div>
<div class=win_part_scrollV><div class=win_part_scrollV_up></div><div class=win_part_scrollV_slide></div><div class=win_part_scrollV_down></div></div>
<div class=win_part_scrollH><div class=win_part_scrollH_left></div><div class=win_part_scrollH_slide></div><div class=win_part_scrollH_right></div></div>
<div class=win_part_resize onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront({$windows['DevColors'][6]}); windowReSize({$windows['DevColors'][6]},event); event.cancelBubble=true;"></div>
<script language=JavaScript>
preRegister('{$windows['DevColors'][6]}');
</script>
</div>
<!-- WINDOW: History -->
<div name={$windows['DevHistory'][6]} id={$windows['DevHistory'][6]} scrollBars=both xHnd=0 zid={$windows['DevHistory'][7]} minW=120 minH=160 style="position:absolute; display:{$windows['DevHistory'][5]}; top:{$windows['DevHistory'][1]}; left:{$windows['DevHistory'][2]}; width:{$windows['DevHistory'][3]}; height:{$windows['DevHistory'][4]}; z-index:{$windows['DevHistory'][7]};" 
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront({$windows['DevHistory'][6]});"
><div class=win_part_close onClick="if (DesktopDialog){return(false);} {$windows['DevHistory'][6]}.style.display='none'; clearMenus();"></div>
<div class=win_part_title onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront({$windows['DevHistory'][6]}); dragWindow({$windows['DevHistory'][6]},event);"><center><table class=big border=0 cellspacing=0 cellpadding=0><tr><td class=big align=right nowrap name={$windows['DevHistory'][6]}T id={$windows['DevHistory'][6]}T style="cursor:default;">History</td></tr></table></center></div>
<div class=win_part_max onClick="if (DesktopDialog){return(false);} toFront({$windows['DevHistory'][6]}); MaxMin({$windows['DevHistory'][6]}); event.cancelBubble=true;"></div>
<div class=win_part_option name={$windows['DevHistory'][6]}O id={$windows['DevHistory'][6]}O style='cursor:default;'></div>
<div class=win_part_body style="overflow:hidden;"><table border=0 cellspacing=0 cellpadding=0 width=100% height=100% align=center><tr><td width=100% height=100% align=center valign=middle><div name={$windows['DevHistory'][6]}A id={$windows['DevHistory'][6]}A style="overflow:hidden;cursor:crosshair;"></div></td></tr></table></div>
<div class=win_part_scrollV><div class=win_part_scrollV_up></div><div class=win_part_scrollV_slide></div><div class=win_part_scrollV_down></div></div>
<div class=win_part_scrollH><div class=win_part_scrollH_left></div><div class=win_part_scrollH_slide></div><div class=win_part_scrollH_right></div></div>
<div class=win_part_resize onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront({$windows['DevHistory'][6]}); windowReSize({$windows['DevHistory'][6]},event); event.cancelBubble=true;"></div>
<script language=JavaScript>
preRegister('{$windows['DevHistory'][6]}');
//scrollSetBoth('DevHistory',{$windows['DevHistory'][6]}Body);
</script>
</div>
<!-- WINDOW: Layers -->
<div name={$windows['DevLayers'][6]} id={$windows['DevLayers'][6]} scrollBars=both xHnd=0 zid={$windows['DevLayers'][7]} minW=120 minH=160 style="position:absolute; display:{$windows['DevLayers'][5]}; top:{$windows['DevLayers'][1]}; left:{$windows['DevLayers'][2]}; width:{$windows['DevLayers'][3]}; height:{$windows['DevLayers'][4]}; z-index:{$windows['DevLayers'][7]};" 
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront({$windows['DevLayers'][6]});"
><div class=win_part_close onClick="if (DesktopDialog){return(false);} {$windows['DevLayers'][6]}.style.display='none'; clearMenus();"></div>
<div class=win_part_title onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront({$windows['DevLayers'][6]}); dragWindow({$windows['DevLayers'][6]},event);"><center><table class=big border=0 cellspacing=0 cellpadding=0><tr><td class=big align=right nowrap name={$windows['DevLayers'][6]}T id={$windows['DevLayers'][6]}T style="cursor:default;">Layers</td></tr></table></center></div>
<div class=win_part_max onClick="if (DesktopDialog){return(false);} toFront({$windows['DevLayers'][6]}); MaxMin({$windows['DevLayers'][6]}); event.cancelBubble=true;"></div>
<div class=win_part_option name={$windows['DevLayers'][6]}O id={$windows['DevLayers'][6]}O style='cursor:default;'></div>
<div class=win_part_body style="overflow:hidden;"><table border=0 cellspacing=0 cellpadding=0 width=100% height=100% align=center><tr><td width=100% height=100% align=center valign=middle><div name={$windows['DevLayers'][6]}A id={$windows['DevLayers'][6]}A style="overflow:hidden;cursor:crosshair;"></div></td></tr></table></div>
<div class=win_part_scrollV><div class=win_part_scrollV_up></div><div class=win_part_scrollV_slide></div><div class=win_part_scrollV_down></div></div>
<div class=win_part_scrollH><div class=win_part_scrollH_left></div><div class=win_part_scrollH_slide></div><div class=win_part_scrollH_right></div></div>
<div class=win_part_resize onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront({$windows['DevLayers'][6]}); windowReSize({$windows['DevLayers'][6]},event); event.cancelBubble=true;"></div>
<script language=JavaScript>
preRegister('{$windows['DevLayers'][6]}');
//scrollSetBoth('DevLayers',{$windows['DevLayers'][6]}Body);
</script>
</div>
<!-- WINDOW: Palette -->
<div name={$windows['DevPalette'][6]} id={$windows['DevPalette'][6]} scrollBars=none xHnd=0 zid={$windows['DevPalette'][7]} minW=120 minH=160 style="position:absolute; display:{$windows['DevPalette'][5]}; top:{$windows['DevPalette'][1]}; left:{$windows['DevPalette'][2]}; width:{$windows['DevPalette'][3]}; height:{$windows['DevPalette'][4]}; z-index:{$windows['DevPalette'][7]};" 
onClick="if (DesktopDialog){return(false);} clearMenus(); toFront({$windows['DevPalette'][6]});"
><div class=win_part_close onClick="if (colorChooserPalette!=null) {resetColorPalette(); return(false);} if (DesktopDialog){return(false);} {$windows['DevPalette'][6]}.style.display='none'; clearMenus();"></div>
<div class=win_part_title onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront({$windows['DevPalette'][6]}); dragWindow({$windows['DevPalette'][6]},event);"><table class=big border=0 cellspacing=0 cellpadding=0 align=center><tr><td align=right style="cursor:default; font-family:{$font_large}; font-size:{$font_size};">Palette</td></tr></table></div>
<div class=win_part_max onClick="if (DesktopDialog){return(false);} toFront({$windows['DevPalette'][6]}); MaxMin({$windows['DevPalette'][6]}); event.cancelBubble=true;"></div>
<div class=win_part_option></div>
<div class=win_part_body><div name={$windows['DevPalette'][6]}A id={$windows['DevPalette'][6]}A></div></div>
<div class=win_part_scrollV><div class=win_part_scrollV_up></div><div class=win_part_scrollV_slide></div><div class=win_part_scrollV_down></div></div>
<div class=win_part_scrollH><div class=win_part_scrollH_left></div><div class=win_part_scrollH_slide></div><div class=win_part_scrollH_right></div></div>
<div class=win_part_resize onMouseDown="if (DesktopDialog){return(false);} clearMenus(); toFront({$windows['DevPalette'][6]}); windowReSize({$windows['DevPalette'][6]},event); event.cancelBubble=true;"></div>
<script language=JavaScript>
preRegister('{$windows['DevPalette'][6]}');
//scrollSetBoth('DevPalette',{$windows['DevPalette'][6]}Body);
</script>
</div>
<!-- DIALOG: Desktop Info -->
<span name=DesktopInfo id=DesktopInfo style="display:none; top:0px; left:0px; position:absolute; width:375px; height:336px; z-index:65535; background-color:red; border:none; padding: 0px;"> 
<table border=0 width=375 height=336 cellpadding=0 cellspacing=2 style="background-color:white; border:1px solid black;"> 
<tr><td width=100% height=100% style="border:3px solid black;"><table class=big valign=top border=0 width=100% height=100% cellpadding=0 cellspacing=0>
  <tr>
    <td width=100% height=100% nowrap align=center style="cursor:default; font-family:{$font_large}; font-size:{$font_size};"><br>
GEM, Graphic Environment Manager<br><br>
webTOS DevPaint<br>
Atari Pixel Painter<br>
<font class=MenuBlank>-----------------------------</font><br>
<img src="{$DE_rootdir}skins/atari/images/IRLOGO.GIF" width=33 height=34 borber=0><br><br><br>
Copyright <b>©</b> 2000-2008<br>
Paul Wratt<br>
iSource.net.nz<br>
All Rights Reserved<br>
</td></tr>
<tr><td width=100% height=100% nowrap align=center style="cursor:default; padding:15px;"><table 
border=1 bgcolor=white cellpadding=0 cellspacing=0 style="border-collapse:collapse; border:3px solid black;">
<tr><td style="border:none; cursor:default;"><a href="javascript:void(0)" name="OK" class="ButtonItem" 
onMouseDown="this.style.color='white'; this.style.background='black';event.cancelBubble=true;" 
onClick="DesktopInfo.style.display='none'; clearMenus(); this.style.color='black'; this.style.background='white'; DesktopDialog=false;event.cancelBubble=true;" 
>&nbsp;&nbsp;&nbsp;&nbsp;OK&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
</tr></table></td></tr>
</table></td></tr>
</table>
</span>
<!-- DIALOG: New Image -->
<span name=NewImage id=NewImage style="display:none;  position:absolute;  top:0px; left:0px; width:390px; height:340px; z-index:65533; background-color:red; border:none; padding:0px;">
<table border=0 width=390 height=340 cellpadding=0 cellspacing=2 style="background-color:white; border:1px solid black;"> 
<tr><td width=100% height=100% style="border:3px solid black;"><table class=big valign=top border=0 width=100% height=100% cellpadding=0 cellspacing=0>
  <tr>
    <td nowrap align=center style="cursor:default; font-family:{$font_large}; font-size:{$font_size};
"><br>NEW IMAGE:<br>
<input name=newname type=text size=35 value="untitled" style="border:none; border-bottom:1px dashed black; font-family:{$font_large}; font-size:{$font_size}; color:black; background:white;"><br>
<br>Size: 
<input name=sizex type=text size=3 value="100" style="text-align:right; border:1px solid black; font-family:{$font_large}; font-size:{$font_size}; color:black; background:white;">
 X 
<input name=sizey type=text size=3 value="100" style="border:none; border:1px solid black; font-family:{$font_large}; font-size:{$font_size}; color:black; background:white;">
<br>
</td></tr>
<!-- BUTTON: options -->
<tr><td nowrap align=center style="cursor:default; padding:15px"><table 
border=0 bgcolor=white cellpadding=0 cellspacing=0 style="border-collapse:collapse; border:none; cursor:default; font-family:{$font_large}; font-size:{$font_size};">
<tr><td style="cursor:default; font-family:{$font_large}; font-size:{$font_size};">Type:</td><td>&nbsp;</td>
<td style="border:1px solid black;"><a href="javascript:void(0)" name=ButtonGrey id=ButtonGrey class=ButtonItem 
onMouseDown="	this.style.color='white'; this.style.backgroundColor='black';
		ButtonColor.style.color='black';
		ButtonColor.style.backgroundColor='white';" 
onClick="" 
>&nbsp;&nbsp;&nbsp;Grey&nbsp;&nbsp;&nbsp;</a></td>
<td>&nbsp;&nbsp;</td>
<td style="border:1px solid black;cursor:default;"><a href="javascript:void(0)" name=ButtonColor id=ButtonColor class=ButtonItem style="color: white; background: black;" 
onMouseDown="	this.style.color='white'; this.style.backgroundColor='black';
		ButtonGrey.style.color='black'; 
		ButtonGrey.style.backgroundColor='white';" 
onClick="" 
>&nbsp;&nbsp;&nbsp;Color&nbsp;&nbsp;&nbsp;</a></td>
</tr></table></td></tr>
<tr><td nowrap align=center style="cursor:default; padding:15px">
Background Color: <font name=OptionColor id=OptionColor onClick="selectedColor=this; updatePixelColor=false; chooseColor('NewImage'); updateSelectedColor();" style="border-collapse:collapse; border:1px solid gray; background-color:rgb(255,0,255);">&nbsp;&nbsp;&nbsp;&nbsp;</font>
</td></tr>
<tr><td nowrap align=center style="cursor:default; padding:15px"><table 
border=0 bgcolor=white cellpadding=0 cellspacing=0 style="border-collapse:collapse; border:none; cursor:default; font-family:{$font_large}; font-size:{$font_size};">
<tr><td style="cursor:default; font-family:{$font_large}; font-size:{$font_size};">Transparent:</td><td>&nbsp;</td>
<td style="border:1px solid black;"><a href="javascript:void(0)" name=OptionTransNo id=OptionTransNo class=ButtonItem 
onMouseDown="	this.style.color='white'; this.style.backgroundColor='black';
		OptionTransYes.style.color='black';
		OptionTransYes.style.backgroundColor='white';" 
onClick="" 
>&nbsp;&nbsp;&nbsp;No&nbsp;&nbsp;&nbsp;</a></td>
<td>&nbsp;&nbsp;</td>
<td style="border:1px solid black;cursor:default;"><a href="javascript:void(0)" name=OptionTransYes id=OptionTransYes class=ButtonItem style="color: white; background: black;" 
onMouseDown="	this.style.color='white'; this.style.backgroundColor='black';
		OptionTransNo.style.color='black'; 
		OptionTransNo.style.backgroundColor='white';" 
onClick="" 
>&nbsp;&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;</a></td>
</tr></table></td></tr>
<!-- BUTTON: OK Cancel -->
<tr><td nowrap align=center style="cursor:default; padding:15px"><table 
border=0 bgcolor=white cellpadding=0 cellspacing=0 style="border-collapse:collapse; border:0px">
<tr><td style="border:3px solid black; cursor: default;"><a href="javascript:void(0)" class=ButtonItem 
onMouseDown="	this.style.color='white';
		this.style.background='black';" 
onClick="	resetColorChooser();
		NewImage.style.display='none';
		clearMenus();
		this.style.color='black';
		this.style.background='white';
		DesktopDialog=false;
		createNewImage(DevPaint0);
" >&nbsp;&nbsp;&nbsp;&nbsp;OK&nbsp;&nbsp;&nbsp;&nbsp;</a></td><td style="border:none; cursor:default;">&nbsp;&nbsp;</td>
<td style="border:3px solid black; cursor:default;"><a href="javascript:void(0)" class=ButtonItem 
onMouseDown="	this.style.color='white';
		this.style.background='black';" 
onClick="	resetColorChooser();
		NewImage.style.display='none';
		textReset(f.newname);
		textReset(f.sizex);
		textReset(f.sizey);
		clearMenus();
		this.style.color='black';
		this.style.background='white';
		DesktopDialog=false;
" >&nbsp;&nbsp;Cancel&nbsp;&nbsp;</a></td>
</tr></table></td></tr>
</table></td></tr>
</table>
</span>
<!-- DIALOG: Zoom -->
<span name=Zoom id=Zoom style="display:none; top:0px; left:0px; position:absolute; width:377px; height:210px; z-index:65533; background-color:red; border:none; padding:0px"> 
<table border=0 width=377 height=210 cellpadding=0 cellspacing=2 style="background-color:white; border:1px solid black;"> 
<tr><td width=100% height=100% style="border:3px solid black;"><table class=big valign=top border=0 width=100% height=100% cellpadding=0 cellspacing=0>
  <tr>
    <td width=100% nowrap align=center style="cursor:default; font-family:{$font_large}; font-size:{$font_size};
"><br>ZOOM:<br>
</td></tr>
<!-- BUTTON: options -->
<tr><td nowrap align=center style="cursor:default; padding:15px"><table 
border=0 bgcolor=white cellpadding=0 cellspacing=0 style="border-collapse:collapse; border:none; cursor:default; font-family:{$font_large}; font-size:{$font_size};">
<tr><td style="border:1px solid black; cursor:default;"><a href="javascript:void(0);" name=ButtonOne id=ButtonOne class=ButtonItem 
onMouseDown="	this.style.color='white'; this.style.backgroundColor='black';
		ButtonTwo.style.color='black';
		ButtonTwo.style.backgroundColor='white';
		ButtonThree.style.color='black';
		ButtonThree.style.backgroundColor='white';
		ButtonFour.style.color='black';
		ButtonFour.style.backgroundColor='white';
		ButtonFive.style.color='black';
		ButtonFive.style.backgroundColor='white';
		ButtonSix.style.color='black';
		ButtonSix.style.backgroundColor='white';
		ButtonSeven.style.color='black';
		ButtonSeven.style.backgroundColor='white';
		ButtonEight.style.color='black';
		ButtonEight.style.backgroundColor='white';" 
onClick="" 
>&nbsp;1&nbsp;</a></td>
<td>&nbsp;</td>
<td style="border:1px solid black; cursor:default;"><a href="javascript:void(0);" name=ButtonTwo id=ButtonTwo class=ButtonItem style="color:white; background:black;" 
onMouseDown="	this.style.color='white'; this.style.backgroundColor='black';
		ButtonOne.style.color='black';
		ButtonOne.style.backgroundColor='white';
		ButtonThree.style.color='black';
		ButtonThree.style.backgroundColor='white';
		ButtonFour.style.color='black';
		ButtonFour.style.backgroundColor='white';
		ButtonFive.style.color='black';
		ButtonFive.style.backgroundColor='white';
		ButtonSix.style.color='black';
		ButtonSix.style.backgroundColor='white';
		ButtonSeven.style.color='black';
		ButtonSeven.style.backgroundColor='white';
		ButtonEight.style.color='black';
		ButtonEight.style.backgroundColor='white';" 
onClick="" 
>&nbsp;2&nbsp;</a></td>
<td>&nbsp;</td>
<td style="border:1px solid black; cursor:default;"><a href="javascript:void(0);" name=ButtonThree id=ButtonThree class=ButtonItem
onMouseDown="	this.style.color='white'; this.style.backgroundColor='black';
		ButtonOne.style.color='black';
		ButtonOne.style.backgroundColor='white';
		ButtonTwo.style.color='black';
		ButtonTwo.style.backgroundColor='white';
		ButtonFour.style.color='black';
		ButtonFour.style.backgroundColor='white';
		ButtonFive.style.color='black';
		ButtonFive.style.backgroundColor='white';
		ButtonSix.style.color='black';
		ButtonSix.style.backgroundColor='white';
		ButtonSeven.style.color='black';
		ButtonSeven.style.backgroundColor='white';
		ButtonEight.style.color='black';
		ButtonEight.style.backgroundColor='white';"
onClick="" 
>&nbsp;3&nbsp;</a></td>
<td>&nbsp;</td>
<td style="border:1px solid black; cursor:default;"><a href="javascript:void(0);" name=ButtonFour id=ButtonFour class=ButtonItem
onMouseDown="	this.style.color='white'; this.style.backgroundColor='black';
		ButtonOne.style.color='black';
		ButtonOne.style.backgroundColor='white';
		ButtonTwo.style.color='black';
		ButtonTwo.style.backgroundColor='white';
		ButtonThree.style.color='black';
		ButtonThree.style.backgroundColor='white';
		ButtonFive.style.color='black';
		ButtonFive.style.backgroundColor='white';
		ButtonSix.style.color='black';
		ButtonSix.style.backgroundColor='white';
		ButtonSeven.style.color='black';
		ButtonSeven.style.backgroundColor='white';
		ButtonEight.style.color='black';
		ButtonEight.style.backgroundColor='white';" 
onClick="" 
>&nbsp;4&nbsp;</a></td>
<td>&nbsp;</td>
<td style="border:1px solid black; cursor:default;"><a href="javascript:void(0);" name=ButtonFive id=ButtonFive class=ButtonItem
onMouseDown="	this.style.color='white'; this.style.backgroundColor='black';
		ButtonOne.style.color='black';
		ButtonOne.style.backgroundColor='white';
		ButtonTwo.style.color='black';
		ButtonTwo.style.backgroundColor='white';
		ButtonThree.style.color='black';
		ButtonThree.style.backgroundColor='white';
		ButtonFour.style.color='black';
		ButtonFour.style.backgroundColor='white';
		ButtonSix.style.color='black';
		ButtonSix.style.backgroundColor='white';
		ButtonSeven.style.color='black';
		ButtonSeven.style.backgroundColor='white';
		ButtonEight.style.color='black';
		ButtonEight.style.backgroundColor='white';" 
onClick="" 
>&nbsp;5&nbsp;</a></td>
<td>&nbsp;</td>
<td style="border:1px solid black; cursor:default;"><a href="javascript:void(0);" name=ButtonSix id=ButtonSix class=ButtonItem
onMouseDown="	this.style.color='white'; this.style.backgroundColor='black';
		ButtonOne.style.color='black';
		ButtonOne.style.backgroundColor='white';
		ButtonTwo.style.color='black';
		ButtonTwo.style.backgroundColor='white';
		ButtonThree.style.color='black';
		ButtonThree.style.backgroundColor='white';
		ButtonFour.style.color='black';
		ButtonFour.style.backgroundColor='white';
		ButtonFive.style.color='black';
		ButtonFive.style.backgroundColor='white';
		ButtonSeven.style.color='black';
		ButtonSeven.style.backgroundColor='white';
		ButtonEight.style.color='black';
		ButtonEight.style.backgroundColor='white';" 
onClick="" 
>&nbsp;6&nbsp;</a></td>
<td>&nbsp;</td>
<td style="border:1px solid black; cursor:default;"><a href="javascript:void(0);" name=ButtonSeven id=ButtonSeven class=ButtonItem
onMouseDown="	this.style.color='white'; this.style.backgroundColor='black';
		ButtonOne.style.color='black';
		ButtonOne.style.backgroundColor='white';
		ButtonTwo.style.color='black';
		ButtonTwo.style.backgroundColor='white';
		ButtonThree.style.color='black';
		ButtonThree.style.backgroundColor='white';
		ButtonFour.style.color='black';
		ButtonFour.style.backgroundColor='white';
		ButtonFive.style.color='black';
		ButtonFive.style.backgroundColor='white';
		ButtonSix.style.color='black';
		ButtonSix.style.backgroundColor='white';
		ButtonEight.style.color='black';
		ButtonEight.style.backgroundColor='white';" 
onClick="" 
>&nbsp;7&nbsp;</a></td>
<td>&nbsp;</td>
<td style="border:1px solid black; cursor:default;"><a href="javascript:void(0);" name=ButtonEight id=ButtonEight class=ButtonItem
onMouseDown="	this.style.color='white'; this.style.backgroundColor='black';
		ButtonOne.style.color='black';
		ButtonOne.style.backgroundColor='white';
		ButtonTwo.style.color='black';
		ButtonTwo.style.backgroundColor='white';
		ButtonThree.style.color='black';
		ButtonThree.style.backgroundColor='white';
		ButtonFour.style.color='black';
		ButtonFour.style.backgroundColor='white';
		ButtonFive.style.color='black';
		ButtonFive.style.backgroundColor='white';
		ButtonSix.style.color='black';
		ButtonSix.style.backgroundColor='white';
		ButtonSeven.style.color='black';
		ButtonSeven.style.backgroundColor='white';" 
onClick="" 
>&nbsp;8&nbsp;</a></td>
</tr></table></td></tr>
<!-- BUTTON: Set as Default -->
<tr><td nowrap align=center style="cursor:default; padding:15px"><table 
border=0 bgcolor=white cellpadding=0 cellspacing=0 style="border-collapse:collapse; border:0px">
<tr><td style="border:3px solid black; cursor: default;"><a href="javascript:void(0)" class=ButtonItem 
onMouseDown="	this.style.color='white';
		this.style.background='black';" 
onClick="	DefaultZoom = (ButtonEight.style.backgroundColor=='black')?8:((ButtonSeven.style.backgroundColor=='black')?7:((ButtonSix.style.backgroundColor=='black')?6:((ButtonFive.style.backgroundColor=='black')?5:((ButtonFour.style.backgroundColor=='black')?4:((ButtonThree.style.backgroundColor=='black')?3:((ButtonTwo.style.backgroundColor=='black')?2:1))))));
		this.style.color='black';
		this.style.background='white';
" >&nbsp;&nbsp;Set&nbsp;As&nbsp;Default&nbsp;&nbsp;&nbsp;</a></td>
</tr></table></td></tr>
<!-- BUTTON: OK Cancel -->
<tr><td nowrap align=center style="border:none; cursor:default; padding:15px"><table 
border=0 bgcolor=white cellpadding=0 cellspacing=0 style="border-collapse:collapse; border:0px">
<tr><td style="border:3px solid black; cursor:default;"><a href="javascript:void(0)" class=ButtonItem 
onMouseDown="	this.style.color='white';
		this.style.background='black';" 
onClick="	Zoom.style.display='none';
		clearMenus();
		this.style.color='black';
		this.style.background='white';
		DesktopDialog=false;
		zoomTo(currentImg,(ButtonEight.style.backgroundColor=='black')?8:((ButtonSeven.style.backgroundColor=='black')?7:((ButtonSix.style.backgroundColor=='black')?6:((ButtonFive.style.backgroundColor=='black')?5:((ButtonFour.style.backgroundColor=='black')?4:((ButtonThree.style.backgroundColor=='black')?3:((ButtonTwo.style.backgroundColor=='black')?2:1)))))));
" >&nbsp;&nbsp;&nbsp;&nbsp;OK&nbsp;&nbsp;&nbsp;&nbsp;</a></td><td style="border:none; cursor:default;">&nbsp;&nbsp;</td>
<td style="border:3px solid black; cursor:default;"><a href="javascript:void(0)" class=ButtonItem 
onMouseDown="	this.style.color='white';
		this.style.background='black';" 
onClick="	Zoom.style.display='none';
		clearMenus();
		this.style.color='black';
		this.style.background='white';
		DesktopDialog=false;
" >&nbsp;&nbsp;Cancel&nbsp;&nbsp;</a></td>
</tr></table></td></tr>
</table></td></tr>
</table>
</span>
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
<!-- WINDOW: Debug -->
<div name=DevDebug id=DevDebug scrollBars=both xHnd=0 zid=65535 minW=120 minH=160 style="position:absolute; display:{$DEBUG}; top:650px; left:20px; width:800px; height:200px; z-index:65535;" 
><div class=win_part_close onClick="DevDebug.style.display='none';"></div>
<div class=win_part_title onMouseDown="dragWindow(DevDebug,event);"><center>Debug</center></div>
<div class=win_part_max onClick="MaxMin(DevDebug);"></div>
<div class=win_part_option></div>
<div class=win_part_body><div name=DevDebugA id=DevDebugA style="overflow:hidden;"><div name=Debug id=Debug></div></div></div>
<div class=win_part_scrollV><div class=win_part_scrollV_up></div><div class=win_part_scrollV_slide></div><div class=win_part_scrollV_down></div></div>
<div class=win_part_scrollH><div class=win_part_scrollH_left></div><div class=win_part_scrollH_slide></div><div class=win_part_scrollH_right></div></div>
<div class=win_part_resize onMouseDown="windowReSize(DevDebug,event);"></div>
<script language=JavaScript>
preRegister('DevDebug');
</script>
</div>
<a id=insertWindowHere style="display:none;"></a>
</form>
<script language=JavaScript>
currentFolder = '{$folder}';
skintype = '{$groupimgs}';
imgW = {$icn_size[0]};
imgH = {$icn_size[1]};

document.cookie = 'defaults' + DesktopName + '={$winDefaults};';

fname = "{$file}";
ficon = document.getElementById('eFile').getElementsByTagName('img')[0];
oldficon = ficon;

</script>
{$jsinit}
<script language=JavaScript>

Register();

currentImg = DevPaint0;
DevPaint0bg = null;

{$imgObj}

toFront(currentImg);

window.onclick = dcheck;
document.onclick = dcheck;
</script>
<!-- 

-->
</body></html>
HTML;

www_page_close();