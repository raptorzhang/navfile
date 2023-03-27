<?php
#---------------------------
# PHP Navigator 4.12.18
# windowsxp prototype (for v5)
# dated: 05-03-2008
# edited: 30-05-2011
# Modified by: Paul Wratt,
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

#----------------------------------------

if ($action=="Save")
  header("X-XSS-Protection: 0"); # bugfix for new browsers
if ($action=="Download")
  {}
else
  www_page_open();

$dir=str_replace("\\\\","\\",$dir); #For Windows, a workaround on magic quotes.
if ($go&&!$action) $dir = dirfrom($go);
if (!$dir) $dir = $homedir;

if ($action=="Download"){
  download();
  die();
}

if($action=="Open"&&!is_dir("$dir/$file")){
  $D = explode("/",$dir);
  print"
  <title>$file in ".end($D)." - Edit - PHP Navigator</title>
  <link rel=stylesheet href=\"{$skincss}\" type=text/css />
  <body style=\"margin:0px; background-color:ButtonFace; width:100%;\" onResize=\"fixResize();\">";
  view($file,$dir);
  www_page_close();
  die();
}
else{
#------------- THE NEW BIT --------------

  $xTitle = folderin($dir);
  $incCSS = file_get_contents("skins/windowsxp/inc/xppanel.css");
  $incJS = file_get_contents("skins/windowsxp/inc/dthmlgoodies_xp_panel.js");

  print <<<HTML
<!D OCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>$xTitle - Browsing - PHP Navigator</title>
<link rel=icon href="./favicon.png" type=image/x-icon />
<!-- <link rel=stylesheet href="{$skincss}" type=text/css /> -->
<style type=text/css >
html{
  height:100%;
}
body{   
  height:100%;
  margin:0px;
  padding:0px;
  font-family: 'Trebuchet MS', 'Lucida Sans Unicode', Arial, sans-serif;
  background:transparent;
}
td{
  vertical-align:top;
}
table.window{
  font-size:11px;
  background-color:ThreeDFace;
  border-bottom:ButtonShadow 1px solid;
}
table.tasks, table#filestable, table#context{
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
</head>
<body onLoad='select();' onscroll='movetooltip();' onResize='fixResize();'>
HTML;

#----------------------------------------
}

$class = 'head';
if (substr_count($_SERVER['HTTP_USER_AGENT'],"Linux")>0){
  if (substr_count($_SERVER['HTTP_USER_AGENT'],"Opera")>0)
    $class = 'lxophead';
  if (substr_count($_SERVER['HTTP_USER_AGENT'],"Firefox")>0 || substr_count($_SERVER['HTTP_USER_AGENT'],"BonEcho")>0 || substr_count($_SERVER['HTTP_USER_AGENT'],"Iceweasel")>0)
    $class = 'lxffhead';
}

print"<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%><tr><td colspan=2 align=center width=100%><table width=100% class=window>
<tr><td colspan=2 class=$class height=20 valign=middle><center>PHP Navigator 4.12 <font color=orange><i>xp</i></font></center></td></tr>
	<form action='?' method=POST name=f ><tr><td colspan=2 class=buttonrow nowrap>";

#------------------ACTIONS----------------

if ($action=="Search")
  require_once("search.php");
else
  require_once("explore.php");

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


$dir=getcwd();

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

#---------------ALL BUTTONS--------------------
printbuttons($dir,0); 
$dispdir = gethostedpath($dir);
$IE = "<a>"; if (substr_count($_SERVER["HTTP_USER_AGENT"],"MSIE")>0) {$IE = "<a href='' onclick='return(false);' class=IEbutton>"; }
print"</td></tr><tr><td>
	<input type=hidden name=dir value='$dir'>
	Address :<input type=text value='$dispdir' size=80 name=go id=go style='width:expression(document.body.clientWidth-100);'></td>
	<td valign=middle>$IE<img src=skins/{$skin}go{$groupimgs} width=20 height=20 alt=go class=button onclick='gotodir(f)' onError=\"this.src='skins/go.gif';\"></a> </td></tr></table>
	<script language=JavaScript>
	function fixResize(){
	  if (window.innerHeight){
		xObj = document.getElementById('go');
		xObj.style.width = window.innerWidth-120;
	  }
	}
	fixResize();
	skintype='$groupimgs';
	</script>
</td></tr>
<tr><td width=5% height=100%>";

#------------- THE NEW PANEL ------------

$taskskin = $GLOBALS['skin'];
$gi   = $GLOBALS['groupimgs'];
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
$ajax="<br>Working in <b>'$mode'</b> mode";
$eh = file_exists("skins/".$taskskin."edithtml".$gi) ? "skins/".$taskskin."edithtml".$gi : 'skins/edithtml.gif';
$ec = file_exists("skins/".$taskskin."editcode".$gi) ? "skins/".$taskskin."editcode".$gi : 'skins/editcode.gif';
$vb = file_exists("skins/".$taskskin."html".$gi)     ? "skins/".$taskskin."html".$gi     : 'skins/html.gif';

$filetasks  = "<table id=filetasks class=tasks border=0 cellspacing=0>";
$filetasks .= "<tr><td><a href='' onClick='searchfile();return(false);'><img src=skins/{$taskskin}search{$gi} width=$w height=$h border=0 onError=\"this.src='skins/search.gif';\"></a></td><td><a href='' onClick='searchfile();return(false);' class=leftitem>File Search</a><td></tr>";
$filetasks .= "<tr><td><a href='' onClick='extract();return(false);'><img src=skins/{$taskskin}extract{$gi} width=$w height=$h border=0 onError=\"this.src='skins/extract.gif';\"></a></td><td><a href='' onClick='extract();return(false);' class=leftitem>Extract Here</a><td></tr>";
$filetasks .= "<tr><td><a href='' onClick='thumbnail();return(false);'><img src=skins/{$taskskin}view{$gi} width=$w height=$h border=0 onError=\"this.src='skins/view.gif';\"></a></td><td><a href='' onClick='thumbnail();return(false);' class=leftitem>View as thumbnail</a><td></tr>";
$filetasks .= "<tr><td><a href='' onClick='openeditor();return(false);' title='Edit HTML [Shift+Ctrl+H]'><img src={$eh} width=$w height=$h border=0 onError=\"this.src='skins/edithtml.gif';\"></a></td><td><a href='' onClick='openeditor();return(false);' title='Edit HTML [Shift+Ctrl+H]' class=leftitem>Open in HTML Editor</a><td></tr>";
$filetasks .= "<tr><td><a href='' onClick='opensource();return(false);' title='Edit Code [Shift+Ctrl+S]'><img src={$ec} width=$w height=$h border=0 onError=\"this.src='skins/editcode.gif';\"></a></td><td><a href='' onClick='opensource();return(false);' title='Edit Code [Shift+Ctrl+S]' class=leftitem>Open in Code Editor</a><td></tr>";
if (file_exists($realdir."DevEdit/editor.php")) $filetasks .= "<tr><td><a href='' onClick='opendevedit();return(false);' title='Edit with DevEdit (IDE) [Shift+Ctrl+I]'><img src=thumb.php?x=$w&y=$h&img=DevEdit/DevEdit.gif width=$w height=$h border=0 onError=\"this.src='DevEdit/DevEdit.gif';\"></a></td><td><a href='' onClick='opendevedit();return(false);' title='Edit with DevEdit (IDE) [Shift+Ctrl+I]' class=leftitem>Open in DevEdit </a><td></tr>";
$filetasks .= "<tr><td><a href='' onClick='opendevedit();return(false);' title='View in Browser'><img src={$vb} width=$w height=$h border=0 onError=\"this.src='skins/html.gif';\"></a></td><td><a href='' onClick='browseHere();return(false);' title='View in Browser' class=leftitem>View in Browser</a><td></tr>";
$filetasks .= "<tr><td><a href=\"?dir=$dir_e\" onClick='' title='Explore from Here' target=_blank><img src=$ex_here width=$w height=$h border=0 onError=\"this.src='favicon.png';\"></a></td><td><a href=\"?dir=$dir_e\" onClick='' target=_blank title='Explore from Here' class=leftitem>Explore from Here</a><td></tr>";
if (end($P)!="detailsxp.php" && file_exists($realdir."detailsxp.php"))
$filetasks .= "<tr><td><a href='details.php?dir=$dir_e'><img src=skins/{$taskskin}asdetails{$gi} width=$w height=$h border=0 onError=\"this.src='skins/asdetails.gif';\"></a></td><td><a href='details.php?dir=$dir_e' class=leftitem>View as Details</a></td></tr>";
elseif (end($P)!="windowsxp.php")
$filetasks .= "<tr><td><a href='windowsxp.php?dir=$dir_e'><img src=skins/{$taskskin}asicons{$gi} width=$w height=$h border=0 onError=\"this.src='skins/asicons.gif';\"></a></td><td><a href='windowsxp.php?dir=$dir_e' class=leftitem>View as Icons</a></td></tr>";
$filetasks .= "</table>";

$othertasks  = "<table id=othertasks class=tasks border=0 cellspacing=0>";
$othertasks .= "<tr><td><a href='windows.php?dir=$dir_e'><img src=skins/{$taskskin}asicons{$gi} width=$w height=$h border=0 onError=\"this.src='skins/asicons.gif';\"></a></td><td><a href='windows.php?dir=$dir_e' class=leftitem>Original Explorer</a></td></tr>";
$othertasks .= "<tr><td><a href='details.php?dir=$dir_e'><img src=skins/{$taskskin}asdetails{$gi} width=$w height=$h border=0 onError=\"this.src='skins/asdetails.gif';\"></a></td><td><a href='details.php?dir=$dir_e' class=leftitem>Original as Details</a></td></tr>";
$othertasks .= "<tr><td><a href='windowsalt.php?dir=$dir_e'><img src=skins/{$taskskin}asdetails{$gi} width=$w height=$h border=0 onError=\"this.src='skins/asdetails.gif';\"></a></td><td><a href='windowsalt.php?dir=$dir_e' class=leftitem>Alternate Explorer</a></td></tr>";
$othertasks .= "<tr><td><a href='atari.php?dir=$dir_e'><img src=thumb.php?x=$w&y=$h&img=skins/atari/screenshot.png width=$w height=$h border=0 onError=\"this.src='skins/atari/screenshot.png';\"></a></td><td><a href='atari.php?dir=$dir_e' class=leftitem>Atari Explorer</a></td></tr>";
$othertasks .= "</table>";

print <<<HTML
<div id="dhtmlgoodies_xpPane">
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
<div width=100% id=thumb style="text-align:center;">
</div>
</div>
</div>
<div class="dhtmlgoodies_panel">
<div>
User IP: {$_SERVER['REMOTE_ADDR']}$ajax<br>
Encoding: <b>$encoding</b><br>
&#00149; <a href='' onClick='config();return(false);'><u>Configure PHP Navigator</u></a><br>
&#00149; <a href='' onClick='about();return(false);'><u>About PHP Navigator</u></a><br>
&#00149; <a href=server.php target='_blank'><u>View Server Info</u></a><br>
&#00149; <a href='' onClick='favourites();return(false);'><u>Favourites</u></a><br>
&#00149; <a href='' onClick='help();return(false);'><u>Quick Help</u></a><br>
&#00149; <a href='./'><u>Restart</u></a><br>
</div>
</div>
<div style="text-align:center;width:100%;font-size:0.7em;padding-top:10px;padding-bottom:10px;">prototype skin: <b>windowsxp</b></div>
</div>
</td>
HTML;

#----------------------------------------

print"<td><center><br><div id=thestatus style='font-size:xx-small;'>";

if (is_array($msg))				#printing all error messages
  foreach ($msg as $m)
    print "$m<br>";
else
  print "$msg "; 
print "Click on a file icon to view its details</div>";

if ($action!="Edit" && $action!="Search"){	# exploring the files
  explore($dir); 
  $se = "This Folder";
}
if ($action!="Edit" && $action=="Search"){	# file & contents search
  search($dir); 
  $se = "Serach";
}

$xTitle = folderin($dir);

print <<<HTML
</td></tr></table>
<table id=context class=context border=0 cellpadding=0 cellspacing=0 style="top:100px;">
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
</table>
<div id=zipinfo name=zipinfo onClick=hide_info() style="position:absolute; bottom:10px; right:10px; cursor:default; background:InfoBackground; border:1px solid black; font-size:8pt; padding:4px; visibility:hidden; opacity:.75; filter:alpha(opacity=75);"></div>
<script type="text/javascript">
initDhtmlgoodies_xpPane(Array('{$se}','File and Folder Tasks','Other Tasks','Details','Thumbnail View','Information'),Array(true,false,false,true,true,true),Array('ThisFolder','ThisTasks','OtherTasks','Details','Thumb','Info'));
</script>
<script type="text/javascript">
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

</script><!-- blank stuff -->
</body>
</html>
HTML;

www_page_close();