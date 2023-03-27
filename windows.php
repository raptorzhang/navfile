<?php
#---------------------------
# PHP Navigator 3.2 (4.0)
# dated: 03-8-2006
# Coded by: Cyril Sebastian
# Kerala,India
# web: navphp.sourceforge.net
#---------------------------
# PHP Navigator 4.12.20
# dated: 20-07-2007
# edited: 05-06-2011
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

authenticate();								# user login & other restrictions

$dir = str_replace("\\\\","\\",$dir);					# For Windows, a workaround on magic quotes.

if ($action=="Save")
  header("X-XSS-Protection: 0"); # bugfix for new browsers
if ($action=="Download")
  {
  download();
  die();
  }
else
  www_page_open();


if(file_exists("skins/{$layout}favicon{$groupimgs}"))
print "<link rel=icon href=\"skins/{$layout}favicon{$groupimgs}\" onerror=\"this.href='favicon.png';\" type=image/x-icon />";
elseif(file_exists("skins/{$skin}favicon{$groupimgs}"))
print "<link rel=icon href=\"skins/{$skin}favicon{$groupimgs}\" onerror=\"this.href='favicon.png';\" type=image/x-icon />";
else
print "<link rel=icon href=\"favicon.png\" type=image/x-icon />";

print "<link href=\"$skincss\" rel=stylesheet type=text/css />";
if($overridecss)
print "<link href=\"$overridecss\" rel=stylesheet type=text/css />";
print "<link href=inc/skin.css rel=stylesheet type=text/css />"; # <-- override skin settings. useful for testing

if ($action=="Open" && !is_dir("$dir/$file")){
  $D = explode("/",$dir);
  print"
<title>$file in ".end($D)." - Edit - PHP Navigator</title>
<link rel=stylesheet href='{$skincss}' type=text/css />
<body style='margin:0px; background-color:ButtonFace; width:100%;' onResize='fixResize();'>";
}else
  print"
<script src=inc/windows.js type=text/javascript></script><script src=inc/$mode.js type=text/javascript></script>
<title>PHP Navigator</title><body onLoad='select();' onscroll='movetooltip();' onResize='fixResize();' on ContextMenu='showcontext();return(false);' on RightClick='showcontext();return(false);' style='margin:0px;'>";

//if ($action=="Open" && is_file("$dir/$file") && is_editable("$dir/$file"))
if ($action=="Open" && is_file("$dir/$file")){
  view($file,$dir);
  www_page_close();
  die();
}


$class = 'head';
if (substr_count($_SERVER['HTTP_USER_AGENT'],"Linux")>0){
  if (substr_count($_SERVER['HTTP_USER_AGENT'],"Opera")>0) $class = 'lxophead';
  if (substr_count($_SERVER['HTTP_USER_AGENT'],"Firefox")>0) $class = 'lxffhead';
  if (substr_count($_SERVER['HTTP_USER_AGENT'],"BonEcho")>0) $class = 'lxffhead';
  if (substr_count($_SERVER['HTTP_USER_AGENT'],"Iceweasel")>0) $class = 'lxffhead';
}

print"
<table width=100% class=window><tr><td colspan=2 class=$class height=20 valign=middle><center>PHP Navigator 4.12 <font color=orange><i>xp</i></font></center></td></tr>
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
if ($action=="Open" && is_dir("$dir/$file"))
  @chdir($file);


$dir = getcwd();

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
$IE = "<a>";
if (substr_count($_SERVER["HTTP_USER_AGENT"],"MSIE")>0)
  $IE = '<a href="" onclick="return(false);" class=IEbutton>';
print"</td></tr><tr><td>
	<input type=hidden name=dir value='$dir'>
	Address :<input type=text value='$dispdir' size=80 name=go id=go style='width:expression(document.body.clientWidth-100);'></td>
	<td valign=middle>$IE<img src=skins/{$layout}go{$groupimgs} width=20 height=20 alt=go class=button onclick='gotodir(f)' onError=\"this.src='skins/go.gif';\"></a> </td></tr></table>
	<script language=JavaScript>
	function fixResize(){
	  if (window.innerHeight){
		xObj = document.getElementById('go');
		xObj.style.width = window.innerWidth-120;
	  }
	}
	fixResize();
	skintype='$groupimgs';
	skinpath='$layout';
	</script>
<table class=mainarea width=100%><tr><td class=left>";
leftdata();
print '</td><td class=right><center><br><div id=thestatus style="font-size:xx-small;">';

if (is_array($msg))							# printing all error messages
  foreach($msg as $m)
    print "$m<br>";
else
  print "$msg "; 
print "Click on a file icon to view its details</div>";

if ($action!="Edit" && $action!="Search")				# exploring the files
   explore($dir); 

if ($action!="Edit" && $action=="Search")				# file & contents search
   search($dir); 

$xTitle = folderin($dir);

?>
</td></tr></table>
<table id= context class= context border=0 cellpadding=0 cellspacing=0 style="top:100px">
<!-- <table border=0 cellpadding=0 cellspacing=0> -->
<tr id=conDir ><td class=contbar><img src=skins/<?= $layout ?>dir<?= $groupimgs ?> height=16 width=16 onError="this.src='skins/dir.gif';"></td><td><a href="javascript:opendir()" class="contitem"><b>Open </b></a></td></tr>
<tr id=conSep0><td class=contbar></td><td><hr/></td></tr>
<tr id=conRen ><td class=contbar><img src=skins/<?= $layout ?>rename<?= $groupimgs ?> height=16 width=16 onError="this.src='skins/rename.gif';"></td><td><a href="" onClick="rename(f);return(false);" class="contitem">Rename </a></td></tr>
<tr id=conDel ><td class=contbar><img src=skins/<?= $layout ?>delete<?= $groupimgs ?> height=16 width=16 onError="this.src='skins/delete.gif';"></td><td><a href="" onClick="delet(f);return(false);" class="contitem">Delete </a></td></tr>
<tr id=conCopy><td class=contbar><img src=skins/<?= $layout ?>copy<?= $groupimgs ?> height=16 width=16 onError="this.src='skins/copy.gif';"></td><td><a href="" onClick="copy(f);return(false);" class="contitem">Copy to</a></td></tr>
<tr id=conMove><td class=contbar><img src=skins/<?= $layout ?>move<?= $groupimgs ?> height=16 width=16 onError="this.src='skins/move.gif';"></td><td><a href="" onClick="move(f);return(false);" class="contitem">Move to</a></td></tr>
<tr id=conSep1><td class=contbar></td><td><hr/></td></tr>
<tr id=conNewF><td class=contbar><img src=skins/<?= $layout ?>newfile<?= $groupimgs ?> height=16 width=16 onError="this.src='skins/newfile.gif';"></td><td><a href="" onClick="newfile(f);return(false);" class="contitem">New File </a></td></tr>
<tr id=conNew ><td class=contbar><img src=skins/<?= $layout ?>newfolder<?= $groupimgs ?> height=16 width=16 onError="this.src='skins/newfolder.gif';"></td><td><a href="" onClick="newfolder(f);return(false);" class="contitem">New Folder </a></td></tr>
<!--
<tr id=conSep2><td class=contbar></td><td><hr/></td></tr>
<tr id=conThum><td class=contbar><img src=skins/<?= $layout ?>view<?= $groupimgs ?> height=16 width=16 onError="this.src='skins/view.gif';"></td><td><a href="" onClick="thumbnail();return(false);" class="contitem">Thumbnail </a></td></tr>
<tr id=conPrev ><td class=contbar><img src=skins/<?= $layout ?>image<?= $groupimgs ?> height=16 width=16 onError="this.src='skins/image.gif';"></td><td><a href="" onClick="thumbnail(f);return(false);" class="contitem">Preview </a></td></tr>
<tr id=conSep3><td class=contbar></td><td><hr/></td></tr>
<tr id=conExtr><td class=contbar><img src=skins/<?= $layout ?>extract<?= $groupimgs ?> height=16 width=16 onError="this.src='skins/extract.gif';"></td><td><a href="" onClick="extract(f);return(false);" class="contitem">Extract </a></td></tr>
<tr id=conCmpr ><td class=contbar><img src=skins/<?= $layout ?>zip<?= $groupimgs ?> height=16 width=16 onError="this.src='skins/zip.gif';"></td><td><a href="" onClick="extract(f);return(false);" class="contitem">Compress </a></td></tr>
-->
</table>
<div id=zipinfo name=zipinfo onClick=hide_info() style="bottom:10px; right:10px; position:absolute; background:InfoBackground; border:1px solid black; font-size:8pt; padding:4px; visibility:hidden; opacity:.75; filter:alpha(opacity=75);"></div>
<div style="position:absolute; visibility:hidden; top:100px; right:8px;">
<img src="skins/<?= $layout ?>warning<?= $groupimgs ?>" onerror="this.src='skins/warning.gif';">
<img src="skins/<?= $layout ?>info<?= $groupimgs ?>" src="skins/info.gif">
<img src="skins/<?= $layout ?>error<?= $groupimgs ?>" src="skins/error.gif"></div>
<?php
print <<<HTML

<script language=JavaScript>
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

</script>

HTML;

www_page_close();