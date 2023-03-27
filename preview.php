<?php
#---------------------------
# PHP Navigator 4.12.20
# dated: 06-06-2011
# edited: 06-06-2011
# Modified by: Paul Wratt
# Melbourne, Australia
# Auckland, New Zealand
# web: phpnav.isource.net.nz
#---------------------------

$dir     = realpath(".")."/editor";
$realdir = realpath(".")."/";

$arrange_by = "name";
$skin = "";
$P = explode("/",$_SERVER['PHP_SELF']);

include_once("config.php");

function browser_encoding(){
// debug for incorrect encoding (MSIE)
  global $compress, $encoding, $deflate, $gzip, $bzip2;
  $encoding = 'none';
  $en = $_SERVER['HTTP_ACCEPT_ENCODING'];
  if($compress){
    $deflate = false;
    $gzip = false;
    $bzip2 = false;
    if(strstr($en,"bzip2")){
      $bzip2 = true;
      $encoding = 'bzip2';
    }elseif(strstr($en,"gzip")){
      $gzip = true;
      $encoding = 'gzip';
    }elseif(strstr($en,"deflate")){
      $deflate = true;
      $encoding = 'deflate';
    }
  }
  return $en;
}

function printbuttons($dir,$i){
  global $homedir, $arrange_by, $no_btn, $btn_size ;

  $si = $GLOBALS['skinicons'];
  $gi = $GLOBALS['groupimgs'];

  if (is_array($btn_size)){
    $w = $btn_size[0];
    $h = $btn_size[1];
  }else{
    $w = '24';
    $h = $w;
  }

  $dir_e = urlencode($dir);
  $homedir_e = urlencode($homedir);
  $IE1 = "class=buttonrow";
  $IE2 = "<a>";
  if (substr_count($_SERVER["HTTP_USER_AGENT"],"MSIE")>0){
    $IE1 = "class=IEbutton";
    $IE2 = "<a href='' onclick='return(false);' class=IEbutton>";
  }

  print"<a href='' onClick='return(false);' $IE1><img width=$w height=$h src=skins/{$si}home{$gi} class=button title=Home border=0 onError=\"this.src='skins/home.gif';\"></a>
	<a href='' onClick='return(false);' $IE1><img width=$w height=$h src=skins/{$si}up{$gi} class=button title=Up border=0 onError=\"this.src='skins/up.gif';\"></a>
	<a href='' onClick='return(false);' $IE1><img width=$w height=$h src=skins/{$si}reload{$gi} class=button title=Refresh border=0 onError=\"this.src='skins/reload.gif';\"></a>
	<img width=1 height=$h class=seperator>
	$IE2<img width=$w height=$h src=skins/{$si}copy{$gi} class=button title='Copy [Shift+Ctrl+C]' onError=\"this.src='skins/copy.gif';\"></a>
	$IE2<img width=$w height=$h src=skins/{$si}move{$gi} class=button title='Move [Shift+Ctrl+M]' onError=\"this.src='skins/move.gif';\"></a>
	$IE2<img width=$w height=$h src=skins/{$skin}delete{$gi} class=button title='Delete [Shift+Ctrl+X]' onError=\"this.src='skins/delete.gif';\"></a>
	$IE2<img width=$w height=$h src=skins/{$si}rename{$gi} class=button title='Rename [F2]' onError=\"this.src='skins/rename.gif';\"></a>
	<img width=1 height=$h class=seperator>
	$IE2<img width=$w height=$h src=skins/{$si}newfolder{$gi}  class=button title='New Folder [Shift+Ctrl+N]' onError=\"this.src='skins/newfolder.gif';\"></a>
	$IE2<img width=$w height=$h src=skins/{$si}newfile{$gi} class=button title='New File [Shift+Ctrl+F]' onError=\"this.src='skins/newfile.gif';\"></a>
	<img width=1 height=$h class=seperator>
	$IE2<img width=$w height=$h src=skins/{$si}chmod{$gi}  class=button title='Change Permissions' onError=\"this.src='skins/chmod.gif';\"></a>
	<select name=mode$i style='margin-top:2px; vertical-align:top;'>
		<option value=0777>777</option>
		<option value=0775>775</option>
		<option value=0770>770</option>
		<option value=0755 selected>755</option>
		<option value=0750>750</option>
		<option value=0666>666</option>
		<option value=0665>665</option>
		<option value=0660>660</option>
		<option value=0644>644</option>
		<option value=0600>600</option>
		<option value=0444>444</option>
		<option value=755>default</option>
		<option value=666>readonly</option>
		<option value=777>readwrite</option>
	</select>
	<img width=1 height=$h class=seperator>
	$IE2<img width=$w height=$h src=skins/{$si}view{$gi}  class=button title='Thumbnail view [Shift+Ctrl+T]' onError=\"this.src='skins/view.gif';\"></a>
	$IE2<img width=$w height=$h src=skins/{$si}extract{$gi}  class=button title='Extract Zip  [Shift+Ctrl+E]' onError=\"this.src='skins/extract.gif';\"></a>";
  if ($i==0){
    if ($arrange_by=="type")
      $arr_type="selected";
    elseif($arrange_by=="size")
      $arr_size="selected";
    else
      $arr_name="selected";
    print"
	<img width=1 height=$h class=seperator>
	<select name=arr style='margin-top:2px; vertical-align:top;' onChange=''>
		<option value=name $arr_name>By Name</option>
		<option value=type $arr_type>By Type</option>
		<option value=size $arr_size>By Size</option>
	</select>";
  }
}

function leftdata(){
  global $dir, $mode, $encoding, $realdir, $no_tsk, $tsk_size, $P;

  $si = $GLOBALS['skinicons'];
  $gi = $GLOBALS['groupimgs'];

  if(is_array($tsk_size)){
    $w = $tsk_size[0];
    $h = $tsk_size[1];
  }else{
    $w = '16';
    $h = $w;
  }
  if (!is_array($tsk_size) || $no_tsk)
    $skin = "";

  if (file_exists("{$realdir}skins/{$si}screenshot.png"))
    $ex_here = "thumb.php?x=".$w."&y=".$h."&img=skins/{$si}screenshot.png";
  else
    $ex_here = "thumb.php?x=".$w."&y=".$h."&img=screenshot.png";

  $dir_e = urlencode($dir);

  $eh = file_exists("skins/".$si."edithtml".$gi) ? "skins/".$si."edithtml".$gi : 'skins/edithtml.gif';
  $ec = file_exists("skins/".$si."editcode".$gi) ? "skins/".$si."editcode".$gi : 'skins/editcode.gif';
  $de = file_exists("skins/".$si."devedit".$gi)  ? "skins/".$si."devedit".$gi  : 'skins/devedit.gif';
  $vb = file_exists("skins/".$si."html".$gi)     ? "skins/".$si."html".$gi     : 'skins/html.gif';
  $ad = file_exists("skins/".$si."asdetails".$gi)? "skins/".$si."asdetails".$gi: 'skins/asdetails.gif';
  $ai = file_exists("skins/".$si."asicons".$gi)  ? "skins/".$si."asicons".$gi  : 'skins/asicons.gif';

  print"<table cellspacing=0 width=100%>";
  print"<tr><td class=lefthead id=folderthis name=folderthis ><b>This Folder</b></td><tr>";
  print"<tr><td class=leftsub><div id=folderinfo name=folderinfo width=100% class=info></div></td><tr></table><br>";
  print"<table cellspacing=0 width=100%><tr><td class=lefthead><b>File Properties</b></td><tr>";
  print"<tr><td class=leftsub><div id=info name=info width=100% class=info></div></td></tr>";
  print"</table><br>";
  print"<table cellspacing=0 width=100%>";
  print"<tr><td class=lefthead ><b>File and Folder tasks</b></td><tr>";
  print"<tr><td class=leftsub><div width=100% class=info id=tasks><table border=0>
	<tr><td><a href='' onClick='return(false);'><img src=skins/{$si}search{$gi} width=$w height=$h border=0 onError=\"this.src='skins/search.gif';\"></a></td><td><a href='' onClick='return(false);' class=leftitem>File Search</a><td></tr>
	<tr><td><a href='' onClick='return(false);'><img src=skins/{$si}view{$gi} width=$w height=$h border=0 onError=\"this.src='skins/view.gif';\"></a></td><td><a href='' onClick='return(false);' class=leftitem>View as thumbnail</a><td></tr>
	<tr><td><a href='' onClick='return(false);'><img src=skins/{$si}extract{$gi} width=$w height=$h border=0 onError=\"this.src='skins/extract.gif';\"></a></td><td><a href='' onClick='return(false);' class=leftitem>Extract Here</a><td></tr>
	<tr><td><a href='' onClick='return(false);'><img src=skins/{$si}file{$gi} width=$w height=$h border=0 onError=\"this.src='skins/file.gif';\"></a></td><td><a href='' onClick='return(false);' class=leftitem>Get Remote File</a><td></tr>
	<tr><td><a href='' onClick='return(false);' title='Edit HTML [Shift+Ctrl+H]'><img src={$eh} width=$w height=$h border=0 onError=\"this.src='skins/edithtml.gif';\"></a></td><td><a href='' onClick='return(false);' title='Edit HTML [Shift+Ctrl+H]' class=leftitem>Open in HTML Editor</a><td></tr>
	<tr><td><a href='' onClick='return(false);' title='Edit Code [Shift+Ctrl+S]'><img src={$ec} width=$w height=$h border=0 onError=\"this.src='skins/editcode.gif';\"></a></td><td><a href='' onClick='return(false);' title='Edit Code [Shift+Ctrl+S]' class=leftitem>Open in Code Editor</a><td></tr>
	<tr><td><a href='' onClick='return(false);' title='View in Browser'><img src={$vb} width=$w height=$h border=0 onError=\"this.src='skins/html.gif';\"></a></td><td><a href='' onClick='return(false);' title='View in Browser' class=leftitem>View in Browser</a><td></tr>
	<tr><td><a href='' onClick='return(false);' title='Explore from Here' target=_blank><img src=$ex_here width=$w height=$h border=0 onError=\"this.src='favicon.png';\"></a></td><td><a href='' onClick='return(false);' target=_blank title='Explore from Here' class=leftitem>Explore from Here</a><td></tr>";
  if (file_exists($realdir."DevEdit/editor.php"))print "<tr><td><a href='' onClick='return(false);' title='Edit with DevEdit (IDE) [Shift+Ctrl+I]'><img src=thumb.php?x=$w&y=$h&img=DevEdit/DevEdit.gif width=$w height=$h border=0 onError=\"this.src='DevEdit/DevEdit.gif';\"></a></td><td><a href='' onClick='return(false);' title='Edit with DevEdit (IDE) [Shift+Ctrl+I]' class=leftitem>Open in DevEdit</a><td></tr>";
  if (end($P)!="details.php" && file_exists($realdir."details.php"))       print "<tr><td><a href='' onClick='return(false);'><img src={$ad} width=$w height=$h border=0 onError=\"this.src='skins/asdetails.gif';\"></a></td><td><a href='' onClick='return(false);' class=leftitem>View as Details</a></td></tr>";
  elseif (end($P)!="windows.php")       print "<tr><td><a href='' onClick='return(false);'><img src={$ai} width=$w height=$h border=0 onError=\"this.src='skins/asicons.gif';\"></a></td><td><a href='' onClick='return(false);' class=leftitem>View as Icons</a></td></tr>";
  if (file_exists($realdir."atari.php"))         print "<tr><td><a href='' onClick='return(false);'><img src=thumb.php?x=$w&y=$h&img=skins/atari/screenshot.png width=$w height=$h border=0 onError=\"this.src='skins/atari/screenshot.png';\"></a></td><td><a href='' onClick='return(false);' class=leftitem>AtariST Skin</a></td></tr>";
  print"</table></div></td></tr></table><br>";
  print"<table cellspacing=0 width=100%>";
  print"<tr><td class=lefthead ><b>Thumbnail View</b></td><tr>";
  print"<tr><td class=leftsub><div width=100% class=info id=thumb></div></td></tr></table><br>";
  print"<table cellspacing=0 width=100%><tr><td class=lefthead><b>User Info</b></td><tr>";
  print"<tr><td class=leftsub><div width=100% class=info>User IP: ".$_SERVER['REMOTE_ADDR']."<br>
	Working in <b>'$mode'</b> mode<br>
	Encoding: <b>$encoding</b><br>
	&#00149;  <a href='' onClick='return(false);'><u>Configure PHP Navigator</u></a><br>
	&#00149;  <a href='' onClick='return(false);'><u>About PHP Navigator</u></a><br>
	&#00149;  <a href='' onClick='return(false);'><u>View Server Info</u></a><br>
	&#00149;  <a href='' onClick='return(false);'><u>Favourites</u></a><br>
	&#00149;  <a href='' onClick='return(false);'><u>Quick Help</u></a><br>
	</div></td></tr>";
  print"</table><br>
	<center><a href=http://navphp.sourceforge.net target=_blank ><b>navphp.sourceforge.net</b></a><br>&nbsp;</center>";
}

function groupicon($file){
  global $thumb, $dir, $realdir, $icn_size, $groups, $groupimgs, $P ;

  $data = pathinfo($file);
  $ext  = strtolower($data['extension']);

  $found = false;
  $foundimg = false;
  if (in_array($ext,$groups)) {
    $img = $ext.$groupimgs;
    $found = true;
  }else{
    foreach ($groups as $group){
      $gr_array = $GLOBALS["gr_$group"];
      if (is_array($gr_array)){
        if (in_array($ext,$gr_array)){
          $img = $group.$groupimgs;
          $found = true;
          if ($group=="image")
            $foundimg = true;
        }
      }
      if ($found)
        break;
    }
  }
  if (!$found) $img = end($groups).$groupimgs;

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

  if ($foundimg){
    $info = @getimagesize($file);
    if ($thumb&&$info)
      if ($info[2]==1 || $info[2]==2 || $info[2]==3 || $info[2]==6 || $info[2]==15 || $info[2]==16)
        $img = "thumb.php?x=".$w."&y=".$h."&img=".urlencode("$dir/$file");	# thumbnail path
  }

  return $img;
}

function filestatus($file){
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
  for ($s=0;$size>1024&&$s<4;$s++)
    $size=$size/1024;						#Calculate in Bytes,KB,MB etc.
  if ($s>0)
    $size= number_format($size,2).$scale[$s];
  else
    $size= number_format($size).$scale[$s];

  if (is_editable($file))
    $dblclick="opendir()";
  else
    $dblclick="not_editable()";
  $spec=filespec($file);

  $filename_t = htmlentities($file,ENT_QUOTES,"utf-8");
  $filename_e = urlencode($file);
  $dir_e      = urlencode($dir);
  $filename   = wordwrap($filename_t, 15, "<br>\n",1);

  if (is_dir($file)){
    $img = "skins/{$si}dir{$gi}";
    if (!file_exists($realdir.$img))
      $img = "skins/{$skin}dir{$gi}";
    if (!file_exists($realdir.$img))
      $img = "skins/dir.gif";
    print "<center><a class=icon><img
	src=\"$img\" width=$w height=$h  alt=\"<b>$filename_t</b><br>File Folder<br><br>
	Permissions:".decoct(fileperms($file)%01000)."<br>
	Modified: ".date('d-m-y, G:i', $stat[9])."\" 
	onMouseDown=\"loadfile(this,'');\" id=file title=\"$filename_t\" spec=\"$spec\" 
	onError=\"this.src='skins/dir.gif';\"></a><br><a 
	class=name href=\"#\"  onclick=\"return false;\"
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
    print"<center><a class=icon><img
	src=\"$img\" width=$w height=$h 
	onMouseDown=\"loadfile(this,'')\" title=\"$filename_t\" id=file
	alt=\"<b>$filename_t</b><br><br>Size: $size<br>
	Permissions:".decoct(fileperms($file)%01000)."<br><br>
	Modified: ".date('d-m-y, G:i', $stat[9])."<br>
	Accessed: ".date('d-m-y, G:i', $stat[8])."\" spec='$spec' 
	onError=\"this.src='skins/file.gif';\"></a><br><a 
	class=name href='#'  onclick=\"return false;\"
	title=Download>$filename</a>";
  }
}

function filespec($file){	# Attributes z-zip, t-thumb, d-dir, h-html
  global $HTMLfiles;

  $data = pathinfo($file);
  $ext  = strtolower($data["extension"]);
  $spec ="f";

  if (is_dir($file))
    $spec.="d";
  if (is_editable($file))
    $spec.="e";
  if ($ext=="png" || $ext=="gif" || $ext=="jpg" || $ext=="jpeg" || $ext=="jpc" || $ext=="jpx" || $ext=="jb2" || $ext=="jp2" || $ext=="bmp" || $ext=="swf" || $ext=="swc" || $ext=="psd" || $ext=="tif" || $ext=="tiff" || $ext=="wbm" || $ext=="wbmp" || $ext=="xbm" || $ext=="xbmp" || $ext=="xbitmap" || $ext=="iff")
    $spec.="t";
  if ($ext=="zip")
    $spec.="z";

  foreach (explode(" ",$HTMLfiles) as $type)
    if ($ext==$type)
      $html=true;

  if($html==true)
    $spec.="h";

  return $spec;
}

function is_editable($filename){				# Checks whether a file is editable
  global $EditableFiles;
  $ext = strtolower(substr(strrchr($filename, "."),1));

  foreach (explode(" ", $EditableFiles) as $type)
    if ($ext==$type)
      return TRUE;

  return FALSE;
}

function ajax_enabled(){
  $agt = strtolower($_SERVER['HTTP_USER_AGENT']);

  $brwsr['ie']      = (strpos($agt, 'msie') !== false);
  $brwsr['ienot']   = $brwsr['ie'] && ((strpos($agt, 'msie 5.') !== false) || (strpos($agt, 'msie 4.') !== false) || (strpos($agt, 'msie 3.') !== false));
  $brwsr['opera']   = (strpos($agt, 'opera') !== false) && (strpos($agt, 'opera/9.8') !== false);
  $brwsr['gecko']   = (strpos($agt, 'gecko') !== false);

  if (($brwsr['ie'] && !$brwsr['ienot']) || $brwsr['opera'] || $brwsr['gecko'])
    return 1;
  else return 0;
}

if(!$mode || $mode=='auto' || $mode='ajax'){
  if(ajax_enabled()) $mode = "ajax";
  else $mode = "normal";
}else
  $mode= "normal";
$cols = 5;

$skin   = $use_skin?$use_skin."/":'';
$icons  = $use_icons?$use_icons."/":$skin;
$layout = $use_layout?$use_layout."/":$skin;
$group  = $use_groups?$use_groups."/":$skin;
$colors = $use_colors?$use_colors."/":$skin;

browser_encoding();

include_once("skin.php"); // setup skins

print '
<!--[if lte IE 6]>
<link rel=stylesheet type=text/css href="inc/pngfix.css" />
<![endif]-->';

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

  print"
<script src=inc/windows.js type=text/javascript></script><script src=inc/$mode.js type=text/javascript></script>
<title>PHP Navigator</title><body onLoad='select();' onscroll='movetooltip();' onResize='fixResize();' on ContextMenu='showcontext();return(false);' on RightClick='showcontext();return(false);' style='margin:0px;'>";

$class = 'head';
if (substr_count($_SERVER['HTTP_USER_AGENT'],"Linux")>0){
  if (substr_count($_SERVER['HTTP_USER_AGENT'],"Opera")>0) $class = 'lxophead';
  if (substr_count($_SERVER['HTTP_USER_AGENT'],"Firefox")>0) $class = 'lxffhead';
  if (substr_count($_SERVER['HTTP_USER_AGENT'],"BonEcho")>0) $class = 'lxffhead';
  if (substr_count($_SERVER['HTTP_USER_AGENT'],"Iceweasel")>0) $class = 'lxffhead';
}

print"
<table width=100% class=window><tr><td colspan=2 class=$class height=20 valign=middle><center>PHP Navigator 4.12 <font color=orange><i>xp</i></font></center></td></tr>
	<form action='?' method=POST name=f onSubmit=\"return(false);\"><tr><td colspan=2 class=buttonrow nowrap>";

#------------------ACTIONS----------------

require_once("explore.php");

chdir($dir);

#---------------ALL BUTTONS--------------------
printbuttons($dir,0); 
$dispdir = '/home';
$IE = "<a>";
if (substr_count($_SERVER["HTTP_USER_AGENT"],"MSIE")>0)
  $IE = '<a href="" onclick="return(false);" class=IEbutton>';
print"</td></tr><tr><td>
	<input type=hidden name=dir value='$dir'>
	Address :<input type=text value='$dispdir' size=80 style='width:expression(document.body.clientWidth-100);'></td>
	<td valign=middle>$IE<img src=skins/{$layout}go{$groupimgs} width=20 height=20 alt=go class=button onError=\"this.src='skins/go.gif';\"></a> </td></tr></table>
	<script language=JavaScript>
	function fixResize(){
	  if (window.innerHeight){
		xObj = document.getElementById('go');
		xObj.style.width = window.innerWidth-120;
	  }
	}
	fixResize();
	</script>
	<script>
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

   explore($dir); 

$xTitle = "Skin Preview - PHP Navigator";

?>
</td></tr></table>
<table id= context class= context border=0 cellpadding=0 cellspacing=0 style="top:100px">
<tr id=conDir ><td class=contbar><img src=skins/<?= $layout ?>dir<?= $groupimgs ?> height=16 width=16 onError="this.src='skins/dir.gif';"></td><td><a href="javascript:opendir()" class="contitem"><b>Open </b></a></td></tr>
<tr id=conSep0><td class=contbar></td><td><hr/></td></tr>
<tr id=conRen ><td class=contbar><img src=skins/<?= $layout ?>rename<?= $groupimgs ?> height=16 width=16 onError="this.src='skins/rename.gif';"></td><td><a href="" onClick="rename(f);return(false);" class="contitem">Rename </a></td></tr>
<tr id=conDel ><td class=contbar><img src=skins/<?= $layout ?>delete<?= $groupimgs ?> height=16 width=16 onError="this.src='skins/delete.gif';"></td><td><a href="" onClick="delet(f);return(false);" class="contitem">Delete </a></td></tr>
<tr id=conCopy><td class=contbar><img src=skins/<?= $layout ?>copy<?= $groupimgs ?> height=16 width=16 onError="this.src='skins/copy.gif';"></td><td><a href="" onClick="copy(f);return(false);" class="contitem">Copy to</a></td></tr>
<tr id=conMove><td class=contbar><img src=skins/<?= $layout ?>move<?= $groupimgs ?> height=16 width=16 onError="this.src='skins/move.gif';"></td><td><a href="" onClick="move(f);return(false);" class="contitem">Move to</a></td></tr>
<tr id=conSep1><td class=contbar></td><td><hr/></td></tr>
<tr id=conNewF><td class=contbar><img src=skins/<?= $layout ?>newfile<?= $groupimgs ?> height=16 width=16 onError="this.src='skins/newfile.gif';"></td><td><a href="" onClick="newfile(f);return(false);" class="contitem">New File </a></td></tr>
<tr id=conNew ><td class=contbar><img src=skins/<?= $layout ?>newfolder<?= $groupimgs ?> height=16 width=16 onError="this.src='skins/newfolder.gif';"></td><td><a href="" onClick="newfolder(f);return(false);" class="contitem">New Folder </a></td></tr>
</table>
<div id=zipinfo name=zipinfo onClick=hide_info() style="bottom:10px; right:10px; position:absolute; background:InfoBackground; border:1px solid black; font-size:8pt; padding:4px; visibility:hidden; opacity:.75; filter:alpha(opacity=75);"></div>
<div style="position:absolute; visibility:hidden; top:100px; right:8px;">
<img src="skins/<?= $layout ?>warning<?= $groupimgs ?>" onerror="this.src='skins/warning.gif';">
<img src="skins/<?= $layout ?>info<?= $groupimgs ?>" src="skins/info.gif">
<img src="skins/<?= $layout ?>error<?= $groupimgs ?>" src="skins/error.gif"></div>
<?php
print <<<HTML

<script language=JavaScript>

document.title = '{$xTitle} - Browsing - PHP Navigator';

function browseHere(){
}

function extWindow(xUrl){
}

function nogo(){return false;}
f.onsubmit = nogo;
f2.onsubmit = nogo;
</script>

HTML;

include("config_patch.php");
if($patch_output) print $output_patch;