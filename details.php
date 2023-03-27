<?php
#---------------------------
# PHP Navigator 4.12
# dated: 31-12-2007
# edited: 31-12-2007
# Modified by: Paul Wratt,
# Melbourne,Australia
# web: phpnav.isource.net.nz
#---------------------------

$dir = @$_REQUEST['dir'];
$action = @$_REQUEST['action'];
$file = @$_REQUEST['file'];
$change = @$_REQUEST['change'];
$go = @$_REQUEST['go'];

$file = urldecode($file);
$change = urldecode($change);
$dir = urldecode($dir);
$go = urldecode($go);

$arrange_by = "name";
$skin = "";$skincss = "inc/windows.css";

include_once("config.php");
include_once("functions.php");
getcookies();
include_once("skin.php"); // setup skins

 // add details output functions



function explore_details($dir)
{
global $cols, $msg, $i, $arrange_by;
print"<table id=filestable><tr class=c enter>";

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
     print "<td onmousedown=loadtd(this) nowrap>";
     filedetails($file);	# function to print file icon & details
     print "</td>\r\n";
//	 if($i%$cols==0)
      print"</tr><tr class=c enter>";
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
		$sizes[]=filesize($file);
		}
	  array_multisort($sizes,SORT_STRING ,SORT_ASC,$files); 
  }
  foreach($files as $file)	#default is sort by name
   {
   if($file!="."&&$file!=".."&&!is_dir($file))
    {
     print "<td onmousedown=loadtd(this) nowrap>";
     filedetails($file);	# function to print file icon & details
     print "</td>\r\n";
//     if($i%$cols==0)
      print"</tr><tr class=c enter>";
     $i++;
    }
   }
  closedir($dh);
  print"\r\n";
  while($i%$cols!=0){
    print "<td></td>";
    $i++;
  }
  print"<td></td></tr>";
  }
 }
else
 $msg[]= "Directory $dir does not exist!";
$total = count($files)-2;
$perms = decoct(fileperms($file)%01000);
print"</table><input type=hidden name=total value='$total'>
      <input type=hidden name=perms value='$perms'></form><br>";
print"<table class=window width=100%><tr><td align=center class=buttonrow nowrap>";
printbuttons($dir,1);
print"</table>\r\n";
printupload();
}

function searchstatus($filepath)
{
global $dir, $realdir, $no_icn, $icn_size, $use_layout;
$skin = $GLOBALS['skin'];
$gi   = $GLOBALS['groupimgs'];

  $w = '16';
  $h = $w;

$scale = array(" Bytes"," KB"," MB"," GB");
$stat = stat($filepath);
$size = $stat[7];
for($s=0;$size>1024&&$s<4;$s++) $size=$size/1024;	//Calculate in Bytes,KB,MB etc.
if($s>0) $size= number_format($size,2).$scale[$s];
else $size= number_format($size).$scale[$s];

//$data = pathinfo($filepath);
//$folder = $data['dirname'];
//$file = $data['basename'];
//$fldrs = explode('/',$filepath);
//$last2fold = $fldrs[count($fldrs)-2]."/".end($fldrs);

$fldrs = explode('/',$filepath);
$file = array_pop($fldrs);
$last2fold = $fldrs[count($fldrs)-2]."/".end($fldrs);
$folder = implode('/',$fldrs);

$filename_t = htmlentities($file,ENT_QUOTES);
$filename_e = urlencode($file);
$pathname_e = urlencode($folder);
$dir_e = urlencode($dir);
$filename=wordwrap($filename_t, 15, "<br>",1);

$o = posix_getpwuid($stat[4]);
$owner = (is_array($o)) ? $o['name'] : $stat[4];
$g = posix_getgrgid($stat[5]);
$group = (is_array($g)) ? $g['name'] : $stat[5];

// ".decoct(fileperms($filepath)%01000)."
$pa = preg_split('//', base_convert((decoct(fileperms($filepath)%01000)),8,2), -1, PREG_SPLIT_NO_EMPTY);
for($i=0;$i<9;$i+=3){
$pa[0+$i] = ($pa[0+$i]=='1') ? 'r' : '-' ;
$pa[1+$i] = ($pa[1+$i]=='1') ? 'w' : '-' ;
$pa[2+$i] = ($pa[2+$i]=='1') ? 'x' : '-' ;
}
$perms = implode('',$pa);
//$perms = decoct(fileperms($filepath)%01000);

$dblclick="location.href='?action=Open&file=$filename_e&dir=$pathname_e';";
$spec=filespec($file);

if(is_dir($filepath)){
	$img = "skins/{$skin}dir{$gi}";
	if (!file_exists($realdir.$img)) $img = "skins/dir.gif";
	print "<a class=icon title='Double click to download'><img
	src=\"$img\" width=$w height=$h valign=bottom
	    alt=\"<b>$filename_t</b><br>
	    Folder in: $last2fold<br><br>
	    Permissions: $perms<br>
	    Owner: $owner<br>
	    Group: $group<br>
	    C Time: ".date('d-m-y, G:i', $stat[8])."<br>
	    Modified: ".date('d-m-y, G:i', $stat[9])."\" 
	    onMouseDown=\"loadfile(this,'');\" id=file title=\"$filename_t in $last2fold\" 
	    onDblClick=\"location.href='?action=Download&file=$filename_e&dir=$pathname_e';\" spec=\"$spec\" 
	    onError=\"this.src='skins/dir.gif';\"></a>&nbsp;<a 
	    class=icon onDblClick=\"$dblclick\" spec=\"$spec\" 
	    title=\"$filename_t in $last2fold\" 
	    onMouseDown=\"loadfile(this,'<b>$filename</b><br>Folder in: $last2fold<br><br>Permissions: $perms<br>Owner: $owner<br>Group: $group<br>C Time: ".date('d-m-y, G:i', $stat[8])."<br>Modified: ".date('d-m-y, G:i', $stat[9])."');\" 
	    >$filename_t Folder in: $last2fold Permissions: $perms Owner: $owner Group: $group<br>&nbsp;&nbsp;&nbsp;&nbsp; C Time: ".date('d-m-y, G:i', $stat[8])." Modified: ".date('d-m-y, G:i', $stat[9])."</a>";
}else{
	if(!is_editable($file)) $dblclick="location='?go=$pathname_e';";
	$spec=filespec($file);
	$ficon = groupicon($file);
	$img = "skins/{$skin}$ficon";
	if (!file_exists($realdir.$img)) $img = "skins/$ficon";
	if (strstr($ficon,"thumb")==$ficon) $img = $ficon;
	print"<a class=icon title='Double click to download'><img
	src=\"$img\" width=$w height=$h valign=bottom
	    alt=\"<b>$filename_t</b><br>
	    in: $last2fold<br>
	    Size: $size<br>
	    Permissions: $perms<br>
	    Owner: $owner<br>
	    Group: $group<br>
	    C Time: ".date('d-m-y, G:i', $stat[8])."<br>
	    Modified: ".date('d-m-y, G:i', $stat[9])."<br>
	    Accessed: ".date('d-m-y, G:i', $stat[10])."\" 
	    onMouseDown=\"loadfile(this,'');\" id=file title=\"$filename_t in $last2fold\" 
	    onDblClick=\"location.href='?action=Download&file=$filename_e&dir=$pathname_e';\" spec=\"$spec\" 
	    onError=\"this.src='skins/$ficon';\"></a>&nbsp;<a 
	    class=icon onDblClick=\"$dblclick\" spec=\"$spec\" 
	    title=\"$filename_t in $last2fold\"
	    onMouseDown=\"loadfile(this,'<b>$filename</b><br>in: $last2fold<br>Size: $size<br>Permissions: $perms<br>Owner: $owner<br>Group: $group<br>C Time: ".date('d-m-y, G:i', $stat[8])."<br>Modified: ".date('d-m-y, G:i', $stat[9])."<br>Accessed: ".date('d-m-y, G:i', $stat[10])."');\" 
	    >$filename_t in: $last2fold Size: $size Permissions: $perms Owner: $owner Group: $group<br>&nbsp;&nbsp;&nbsp;&nbsp; C Time: ".date('d-m-y, G:i', $stat[8])." Modified: ".date('d-m-y, G:i', $stat[9])." Accessed: ".date('d-m-y, G:i', $stat[10])."</a>";
 }
}  

function search_details($dir)
{
global $cols, $uploads, $i, $arrange_by, $msg;
print"<table id=filestable><tr class=c enter>";

$sf = urldecode($_REQUEST['search']);
$sd = isset($_REQUEST['subdir']);
$fn = urldecode($_REQUEST['file']);
$cn = urldecode($_REQUEST['content']);
$ifn = false;
if ($fn & $cn) {
  $ifn = true;
}elseif (!$fn & !$cn) {
  $fn = $sf;
  $cn = $sf;
}

if (is_dir($dir)) 
 {

$files = array();
if (is_dir($dir) && !$sd){ // search directory
  if ($handle=opendir($dir)){
    chdir($dir);
    $d = $f = 0;
    while (false!==($file=readdir($handle))){
      if ($file!='.' && $file!='..') {
        $f++;
        if ($ifn) {
          if (stripos($file,$fn)!==false) {
            $filecontents = file_get_contents($file);
            $filecontains = stripos($filecontents,$cn);
            if ($filecontains!==false) {
              $files[] = "$dir/$file";
            }
          }
        }else{
          if (@stripos($file,$fn)!==false) {
            $files[] = "$dir/$file";
          }
          if (!is_dir($file) && $cn && !$ifn && !in_array("$dir/$file",$files)) {
            $filecontents = file_get_contents($file);
            $filecontains = stripos($filecontents,$cn);
            if ($filecontains!==false) {
              $files[] = "$dir/$file";
            }
          }
        }
      }
    }
    closedir($handle);
    $msg[]= "$i Entries searched";
  }
}elseif (is_dir($dir) && $sd) { // search directory & sub directories
  try{
    chdir("/");
    $l = strlen($dir); $d = $f = 0;
    $searchdir = new RecursiveIteratorIterator( new RecursiveDirectoryIterator($dir), true);
    foreach ($searchdir as $searchfile) {
      $filepath = $searchfile->getPathname();
//      $filepath = $searchfile;
//      $data = pathinfo($filepath);
//      $file = $data['basename'];
      $F = explode("/",$filepath);
      $file = end($F);
      if(is_dir($filepath)){
        $d++;
        if (!$ifn) {
          if (@stripos($file,$fn)!==false) {
            $files[] = $filepath;
          }
        }
      }else{
        $f++;
        if ($ifn) {
          if (stripos($file,$fn)!==false) {
            $filecontents = file_get_contents($filepath);
            $filecontains = stripos($filecontents,$cn);
            if ($filecontains!==false) {
              $files[] = $filepath;
            }
          }
        }else{
          if (@stripos($file,$fn)!==false) {
            $files[] = $filepath;
          }
          if ($cn && !$ifn && !in_array($file,$files)) {
            $filecontents = file_get_contents($filepath);
            $filecontains = stripos($filecontents,$cn);
            if ($filecontains!==false) {
              $files[] = $filepath;
            }
          }
        }
      }
    }
    $msg[]= "$j Files in $i Directories searched";

  }catch (Exception $ex){
//echo "owch";
//throw $ex;
  }
}

$names = array();
foreach($files as $file){ // sort a-z by filename
  $data=pathinfo($file);
  $names[]=strtolower($data["basename"]);
}
//array_multisort($names,SORT_STRING,SORT_ASC,$files); 

$srch = ($fn) ? $fn : $cn;
$msg[]= "Search results: <b>".count($files)."</b> matches for <b>srch</b>";

// below code from explore()
  $i=1;
  foreach($files as $file)
   {
   if($file!="."&&$file!=".."&&is_dir($file))
    {
     print "<td onmousedown=loadtd(this) nowrap>";
     searchstatus($file);	# function to print file icon & details
     print "</td>\r\n";
//	 if($i%$cols==0)
      print"</tr><tr class=c enter>";
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
		$sizes[]=filesize($file);
		}
	  array_multisort($sizes,SORT_STRING ,SORT_ASC,$files); 
  }
  foreach($files as $file)	#default is sort by name
   {
   if($file!="."&&$file!=".."&&!is_dir($file))
    {
     print "<td onmousedown=loadtd(this) nowrap>";
     searchstatus($file);	# function to print file icon & details
     print "</td>\r\n";
//     if($i%$cols==0)
      print"</tr><tr class=c enter>";
     $i++;
    }
   }
  print"\r\n";
  print"<td></td></tr>";
 }
else
 $msg[]= "Directory $dir does not exist!";
$total = $d+$f;
$perms = count($files);
print"</table><input type=hidden name=total value='$total'>
      <input type=hidden name=perms value='$perms'></form><br>";
print"<table class=window width=100%><tr><td align=center class=buttonrow nowrap>";
printbuttons($dir,1);
print"</table>\r\n";
printupload();
}

function printupload(){
global $dir, $uploads;

#-----Calculate Max Upload Size--
  $size_str = ini_get('upload_max_filesize');
  $z=0; $size=0;
  while(ctype_digit($size_str[$z])) {$size.=$size_str[$z]; $z++;}
  $size = intval($size);
  $max_size = $size.$size_str[$z];
  if($size_str[$z]=="M"||$size_str[$z]=="m") $size = $size*1024*1024;
  else if($size_str[$z]=="K"||$size_str[$z]=="k") $size = $size*1024;
  else $size = 1024*1024*1024;

#--------UPLOAD FORM----------
print"<form id=f2 enctype=multipart/form-data method=POST action='windows.php' onSubmit='return upload();'>
      <input type=hidden name=MAX_FILE_SIZE value='$size'><input type=hidden name=dir value='$dir'>";
for($i=1;$i<=$uploads;$i++)
 {
 print"<input type=file name=upfile[] id=upfile>&nbsp;";
 if($i%2==0) print"<br>";
 }
print"<input type=submit name=action value=Upload title=' max file size $max_size '></form><br>";
}

 // back to normal

if ($action=="Save")
  header("X-XSS-Protection: 0"); # bugfix for new browsers
if ($action=="Download"){}
//else if(strstr($_SERVER['HTTP_ACCEPT_ENCODING'],"deflate")&&$compress){ ob_start(); $deflate=1;
//header("Content-Encoding: deflate"); }	// start buffering for deflate encoding
else www_page_open();

$dir=str_replace("\\\\","\\",$dir); #For Windows, a workaround on magic quotes.
if($go&&!$action) $dir = dirfrom($go);
if(!$dir) $dir=$homedir;

authenticate();	//user login & other restrictions

if($action=="Download")
  {
  download();
  die();
  }
print"<link rel='icon' href='./favicon.png' type='image/x-icon' />";
print"<link href='$skincss' rel=stylesheet type=text/css>";
if($action=="Open"&&!is_dir("$dir/$file")) print"<title>Edit- PHP Navigator</title>
<body style='margin:0px; background-color:ButtonFace; width:100%;' onResize='fixResize();'>";
else  print"<script src=inc/windows.js type=text/javascript></script><script src=inc/$mode.js type=text/javascript></script>
<title>PHP Navigator</title><body onLoad='select();' onscroll='movetooltip();' onResize='fixResize();' on ContextMenu='showcontext();return(false);' on RightClick='showcontext();return(false);' style='margin:0px;'>";

//if($action=="Open"&&is_file("$dir/$file")&&is_editable("$dir/$file"))
if($action=="Open"&&is_file("$dir/$file"))
  {
  view($file,$dir);
  die();
  } 


$class = 'head';
if (substr_count($_SERVER['HTTP_USER_AGENT'],"Linux")>0){
  if (substr_count($_SERVER['HTTP_USER_AGENT'],"Opera")>0) $class = 'lxophead';
  if (substr_count($_SERVER['HTTP_USER_AGENT'],"Firefox")>0) $class = 'lxffhead';
}

print"<table width=100% class=window><tr><td colspan=2 class=$class height=20 valign=middle><center>PHP Navigator 4.12 <font color=orange><i>xp</i></font></center></td></tr>
	<form action='' method=POST name=f ><tr><td colspan=2 class=buttonrow nowrap>";

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


$dir=getcwd();

#---------------ALL BUTTONS--------------------
printbuttons($dir,0); 
$dispdir = gethostedpath($dir);
$IE = ""; if (substr_count($_SERVER["HTTP_USER_AGENT"],"MSIE")>0) {$IE = "<a href='' onclick='return(false);' class=IEbutton>"; }
print"</td></tr><tr><td>
	<input type=hidden name=dir value='$dir'>
	Address :<input type=text value='$dispdir' size=80 name=go id=go style='width:expression(document.body.clientWidth-100);'></td>
	<td valign=middle>$IE<img src=\"skins/{$skin}go{$groupimgs}\" width=20 height=20 alt=go class=button onclick='gotodir(f)' onError=\"this.src='skins/go.gif';\"></a> </td></tr></table>
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
<table width=100%><tr><td class=left>";
leftdata();
print"</td><td><center><br><div id=thestatus style='font-size:xx-small;'>";

if(is_array($msg))  #printing all error messages
 foreach($msg as $m)
 print "$m<br>";
else print "$msg ";
print "Click on a file icon to view its details</div>";

if($action!="Edit" && $action!="Search") # exploring the files
   explore_details($dir);

if($action!="Edit" && $action=="Search") # file & contents search
   search_details($dir);

?>
</td></tr></table>

<table id=context class="context" border="0" cellpadding="0" cellspacing="0" style="top:100px;">
<tr id=conDir ><td class=contbar><img src=skins/<?= $skin ?>dir{$groupimgs} height=16 width=16 onError="this.src='skins/dir.gif';"></td><td><a href="javascript:opendir()" class="contitem"><b>Open </b></a></td></tr>
<tr id=conSep0><td class=contbar></td><td><hr/></td></tr>
<tr id=conRen ><td class=contbar><img src=skins/<?= $skin ?>rename{$groupimgs} height=16 width=16 onError="this.src='skins/rename.gif';"></td><td><a href="" onClick="rename(f);return(false);" class="contitem">Rename </a></td></tr>
<tr id=conDel ><td class=contbar><img src=skins/<?= $skin ?>delete{$groupimgs} height=16 width=16 onError="this.src='skins/delete.gif';"></td><td><a href="" onClick="delet(f);return(false);" class="contitem">Delete </a></td></tr>
<tr id=conCopy><td class=contbar><img src=skins/<?= $skin ?>copy{$groupimgs} height=16 width=16 onError="this.src='skins/copy.gif';"></td><td><a href="" onClick="copy(f);return(false);" class="contitem">Copy to</a></td></tr>
<tr id=conMove><td class=contbar><img src=skins/<?= $skin ?>move{$groupimgs} height=16 width=16 onError="this.src='skins/move.gif';"></td><td><a href="" onClick="move(f);return(false);" class="contitem">Move to</a></td></tr>
<tr id=conSep1><td class=contbar></td><td><hr/></td></tr>
<tr id=conNewF><td class=contbar><img src=skins/<?= $skin ?>newfile{$groupimgs} height=16 width=16 onError="this.src='skins/newfile.gif';"></td><td><a href="" onClick="newfile(f);return(false);" class="contitem">New File </a></td></tr>
<tr id=conNew ><td class=contbar><img src=skins/<?= $skin ?>newfolder{$groupimgs} height=16 width=16 onError="this.src='skins/newfolder.gif';"></td><td><a href="" onClick="newfolder(f);return(false);" class="contitem">New Folder </a></td></tr>
<!--
<tr id=conSep2><td class=contbar></td><td><hr/></td></tr>
<tr id=conNewF><td class=contbar><img src=skins/<?= $skin ?>view{$gi} height=16 width=16 onError="this.src='skins/view{$gi}';"></td><td><a href="" onClick="thumbnail();return(false);" class="contitem">Thumbnail </a></td></tr>
<tr id=conNew ><td class=contbar><img src=skins/<?= $skin ?>image{$gi} height=16 width=16 onError="this.src='skins/image{$gi}';"></td><td><a href="" onClick="thumbnail(f);return(false);" class="contitem">Preview </a></td></tr>
<tr id=conSep3><td class=contbar></td><td><hr/></td></tr>
<tr id=conNewF><td class=contbar><img src=skins/<?= $skin ?>extract{$gi} height=16 width=16 onError="this.src='skins/extract{$gi}';"></td><td><a href="" onClick="extract(f);return(false);" class="contitem">Extract </a></td></tr>
<tr id=conNew ><td class=contbar><img src=skins/<?= $skin ?>zip{$gi} height=16 width=16 onError="this.src='skins/zip{$gi}';"></td><td><a href="" onClick="extract(f);return(false);" class="contitem">Compress </a></td></tr>
-->
</table>

<div id=zipinfo name=zipinfo onClick=hide_info() style="bottom:10px; right:10px; position:absolute; background:InfoBackground; border:1px solid black; font-size:8pt; padding:4px; visibility:hidden; opacity:.75; filter:alpha(opacity=75);"></div>
<?php
//if($deflate){
//$data= ob_get_clean();
//echo gzdeflate($data);}
www_page_close();