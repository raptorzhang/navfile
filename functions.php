<?php
#---------------------------
# PHP Navigator 3.2 (4.0)
# dated: 03-08-2006
# Coded by: Cyril Sebastian,
# Kerala,India
# web: navphp.sourceforge.net
#---------------------------
# PHP Navigator 4.12.20
# dated: 20-07-2007
# edited: 07-06-2011
# Modified by: Paul Wratt
# Melbourne, Australia
# Auckland, New Zealand
# web: phpnav.isource.net.nz
#---------------------------

//error_reporting(E_ALL);

#----------OTHER FUNCTIONS------- 

$realdir = realpath(".")."/";

#------------- NEW FUNCTIONS ------------
#
# www_page_open()  - start data output encoding to browser
# www_page_close() - end data output encoding, apply compression
# folderin(dir)    - return "end_folder in end_folder-1" from full path
#

$encoding = 'none';

# fix for STRICT "end(explode(..))"
$P = explode("/",$_SERVER['PHP_SELF']);

function www_page_open(){
  global $compress, $encoding, $deflate, $gzip, $bzip2;
  if($compress){
    $deflate = false;
    $gzip = false;
    $bzip2 = false;
    $en = $_SERVER['HTTP_ACCEPT_ENCODING'];
    ob_start();
    if(strstr($en,"bzip2")){
      $bzip = true;
      header("Content-Encoding: bzip2");	// start buffering for gzip encoding
      header("Vary: Accept-Encoding");	// fix for certain cache/browser/proxy combinations
      $encoding = 'bzip2';
    }elseif(strstr($en,"gzip")){
      $gzip = true;
      header("Content-Encoding: gzip");	// start buffering for gzip encoding
      header("Vary: Accept-Encoding");	// fix for certain cache/browser/proxy combinations
      $encoding = 'gzip';
    }elseif(strstr($en,"deflate")){
      $deflate = true;
      header("Content-Encoding: deflate");	// start buffering for deflate encoding
      $encoding = 'deflate';
    }
  }
}

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

function www_page_close(){
  global $compress, $deflate, $gzip, $bzip2, $patch_output, $output_patch;
  if($patch_output) print $output_patch;
  if($compress){
    $data = ob_get_clean();
    if($deflate){
      echo gzdeflate($data);
    }elseif($gzip){
      $c = 9 ;
      if (substr_count($_SERVER['HTTP_USER_AGENT'],"Windows")>0) $c = 5;
//      if (substr_count($_SERVER['HTTP_USER_AGENT'],"Windows")>0 && substr_count($_SERVER['HTTP_USER_AGENT'],"Firefox")>0) $c = 5;
// needed to fix Mozilla FF gzip on Windows (Windows compression algorithym?)
      echo gzencode($data,$c);
    }elseif($bzip2){
      $c = 9 ;
      echo bzcompress($data,$c,30);
    }else{
      echo $data;
    }
  }
}

$cookies = " cont cols thumb wrap editall aszip arrange skin icons layout groups colors";

function delcookies(){
  global $cookies ;
  print "<script>
document.cookie = 'navphp=; expires=Fri, 21 Dec 1976 04:31:24 GMT;';
document.cookie = 'filewalker=; expires=Fri, 21 Dec 1976 04:31:24 GMT;';
while(document.cookie.indexOf('navphp')!=-1){
";
  $cookiejar = explode(" ",$cookies);
  foreach ($cookiejar as $cookie){
    print "document.cookie = 'navphp_$cookie=; expires=Fri, 21 Dec 1976 04:31:24 GMT;';";
    print "document.cookie = 'filewalker_$cookie=; expires=Fri, 21 Dec 1976 04:31:24 GMT;';
";
  }
  print "}
</script>
";
}

function setcookies(){
  global $mode, $cols, $thumb, $word_wrap, $allow_edit_any, $download_as_zip, $use_skin, $use_icons, $use_groups, $use_layout, $use_colors ;

  print "<script>
if(document.cookie.indexOf('navphp')==-1){
";
  if ($mode=="auto"){
    print "
(window.XMLHttpRequest) ? (true) : (XMLHttpRequest=false); 
if (XMLHttpRequest||ActiveXObject) {
	document.cookie='filewalker_=ajax'; document.cookie='navphp_=ajax';
}else{
	document.cookie='filewalker_=normal'; document.cookie='navphp_=normal';
}

";
  }else{
    print "document.cookie='filewalker_=$mode'; document.cookie='navphp_=$mode';
";
  }

  if ($cols=="auto"){
    print "if(screen.width>1280) {
	document.cookie='filewalker_cols=11'; document.cookie='navphp_cols=11';
}else if(screen.width>1024) {
	document.cookie='filewalker_cols=9'; document.cookie='navphp_cols=9';
}else if(screen.width>800) {
	document.cookie='filewalker_cols=7'; document.cookie='navphp_cols=7';
}else{
	document.cookie='filewalker_cols=5'; document.cookie='navphp_cols=5';
}

";
  }else{
    print "document.cookie='filewalker_cols=$cols'; document.cookie='navphp_cols=$cols';
";
  }

  if ($thumb){
    print "document.cookie='filewalker_thumb=yes'; document.cookie='navphp_thumb=yes';
";
  }else{
    print "document.cookie='filewalker_thumb=no'; document.cookie='navphp_thumb=no;';
";
  }

  if ($word_wrap=="virtual"){
    print "document.cookie='filewalker_wrap=yes'; document.cookie='navphp_wrap=yes';
";
  }else{
    print "document.cookie='filewalker_wrap=no'; document.cookie='navphp_wrap=no';
";
  }

  if ($allow_edit_any){
    print "document.cookie='filewalker_editall=yes'; document.cookie='navphp_editall=yes';
";
  }else{
    print "document.cookie='filewalker_editall=no'; document.cookie='navphp_editall=no';
";
  }

  if ($download_as_zip){
    print "document.cookie='filewalker_aszip=yes'; document.cookie='navphp_aszip=yes';
";
  }else{
    print "document.cookie='filewalker_aszip=no'; document.cookie='navphp_aszip=no';
";
  }

  print "document.cookie='filewalker_arrange=name'; document.cookie='navphp_arrange=name';
";
  print "document.cookie='filewalker_skin=$use_skin'; document.cookie='navphp_skin=$use_skin';
";
/*
  if ($use_groups){
    print "document.cookie='filewalker_groups=yes'; document.cookie='navphp_groups=yes';
";
  }else{
    print "document.cookie='filewalker_groups=no'; document.cookie='navphp_groups=no';
";
  }
  if ($use_layout){
    print "document.cookie='filewalker_layout=yes'; document.cookie='navphp_layout=yes';
";
  }else{
    print "document.cookie='filewalker_layout=no'; document.cookie='navphp_layout=no';
";
  }
  if ($use_colors){
    print "document.cookie='filewalker_colors=yes'; document.cookie='navphp_colors=yes';
";
  }else{
    print "document.cookie='filewalker_colors=no'; document.cookie='navphp_colors=no';
";
  }
*/
  print "document.cookie='filewalker_icons=".($use_icons?$use_icons:$use_skin)."'; document.cookie='navphp_icons=".($use_icons?$use_icons:$use_skin)."';
";
  print "document.cookie='filewalker_layout=".($use_layout?$use_layout:$use_skin)."'; document.cookie='navphp_layout=".($use_layout?$use_layout:$use_skin)."';
";
  print "document.cookie='filewalker_groups=".($use_groups?$use_groups:$use_skin)."'; document.cookie='navphp_groups=".($use_groups?$use_groups:$use_skin)."';
";
  print "document.cookie='filewalker_colors=".($use_colors?$use_colors:$use_skin)."'; document.cookie='navphp_colors=".($use_colors?$use_colors:$use_skin)."';
";
  print "}
</script>
";
}

function getcookies(){
  global $mode, $cols, $thumb, $word_wrap, $allow_edit_any, $download_as_zip, $arrange_by, $skin, $icons, $layout, $group, $colors ;
  $cookie_mode    = @$_COOKIE['navphp_'];
  $cookie_cols    = @$_COOKIE['navphp_cols'];
  $cookie_thumb   = @$_COOKIE['navphp_thumb'];
  $cookie_wrap    = @$_COOKIE['navphp_wrap'];
  $cookie_editall = @$_COOKIE['navphp_editall'];
  $cookie_aszip   = @$_COOKIE['navphp_aszip'];
  $cookie_arrange = @$_COOKIE['navphp_arrange'];
  $cookie_skin    = @$_COOKIE['navphp_skin'];
  $cookie_icons   = @$_COOKIE['navphp_icons'];
  $cookie_groups  = @$_COOKIE['navphp_groups'];
  $cookie_layout  = @$_COOKIE['navphp_layout'];
  $cookie_colors  = @$_COOKIE['navphp_colors'];

  if     (   $cookie_mode       ) $mode            = $cookie_mode;
  elseif ( ajax_enabled()       ) $mode            = "ajax";
  else                            $mode            = "normal";
  if     (   $cookie_cols       ) $cols            = $cookie_cols;
  else                            $cols            = 5;
  if     (  $cookie_thumb=="yes") $thumb           = true;
  elseif (  $cookie_thumb=="no" ) $thumb           = false;
  if     (   $cookie_wrap=="yes") $word_wrap       = "virtual";
  elseif (   $cookie_wrap=="no" ) $word_wrap       = "off";
  if     ($cookie_editall=="yes") $allow_edit_any  = true;
  elseif ($cookie_editall=="no" ) $allow_edit_any  = false;
  if     (  $cookie_aszip=="yes") $download_as_zip = true;
  elseif (  $cookie_aszip=="no" ) $download_as_zip = false;
  if     ($cookie_arrange=="")    $arrange_by      = "name";
  else                            $arrange_by      = $cookie_arrange;
  if     (   $cookie_skin!=""   ) $skin            = $cookie_skin."/";
/*
  if     ( $cookie_groups=="yes") $use_groups      = true;
  elseif ( $cookie_groups=="no" ) $use_groups      = false;
  if     ( $cookie_layout=="yes") $use_layout      = true;
  elseif ( $cookie_layout=="no" ) $use_layout      = false;
  if     ( $cookie_colors=="yes") $use_colors      = true;
  elseif ( $cookie_colors=="no" ) $use_colors      = false;
*/
  if     (  $cookie_icons!=""   ) $icons           = $cookie_icons."/";
  else                            $icons           = $skin;
  if     ( $cookie_layout!=""   ) $layout          = $cookie_layout."/";
  else                            $layout          = $skin;
  if     ( $cookie_groups!=""   ) $group           = $cookie_groups."/";
  else                            $group           = $skin;
  if     ( $cookie_colors!=""   ) $colors          = $cookie_colors."/";
  else                            $colors          = $skin;

}

function upload($dir){
  global $msg, $uploads, $allow_uploads;
  chdir($dir);

  if(!$allow_uploads){
    $msg[] = "Uploads not allowed!";
    return;
  }
								# Calculate Max Upload Size
  $size_str = ini_get('upload_max_filesize');
  $i=0; $size=0;
  while (ctype_digit($size_str[$i])){
    $size .= $size_str[$i];
    $i++;
  }
  if     ($size_str[$i]=="M" || $size_str[$i]=="m")
    $size = $size*1024*1024;
  elseif ($size_str[$i]=="K" || $size_str[$i]=="k")
    $size = $size*1024;
  else
    $size = 1024*1024*1024;
								# Start Upload
  for ($i=0;$i<$uploads;$i++)
    if ($_FILES['upfile']['name'][$i]!=""){
      if ($_FILES['upfile']['size'][$i]!=0 && $_FILES['upfile']['size'][$i]<=$size){
        $file = $_FILES['upfile']['name'][$i];
        $uploadfile = $dir."/".$file;
        if (move_uploaded_file($_FILES['upfile']['tmp_name'][$i], $uploadfile))
          $msg[] = "$file uploaded";
        else
          $msg[] = "Upload failed for $file!";
      }else
        $msg[] = "Upload failed for $file due to exceeding file size limits, or zero length file!"; 
    }
}

function view($file,$dir){
  global $msg, $max_edit_size, $deflate, $word_wrap, $view_charset ;
  $dir_e = urlencode($dir);
  $file_e = urlencode($file);

  $ua = $_SERVER['HTTP_USER_AGENT'];
  $IExS = 75;
  $IEyS = 35;
  if (substr_count($ua,"MSIE")>0 && substr_count($ua,"Windows NT 5.0")>0){
    $IExS = 75;
    $IEyS = 45;
  }
  $MZxS = 75;
  $MZyS = 45;
  if (substr_count($ua,"Linux")>0){
    $MZxS = 75;
    $MZyS = 55;
  }
  echo "<center>";
  echo '<link rel=stylesheet href="codemirror-5.65.9/doc/docs.css">

<link rel="stylesheet" href="codemirror-5.65.9/lib/codemirror.css">
<script src="codemirror-5.65.9/lib/codemirror.js"></script>
<script src="codemirror-5.65.9/addon/edit/matchbrackets.js"></script>
<script src="codemirror-5.65.9/mode/htmlmixed/htmlmixed.js"></script>
<script src="codemirror-5.65.9/mode/xml/xml.js"></script>
<script src="codemirror-5.65.9/mode/javascript/javascript.js"></script>
<script src="codemirror-5.65.9/mode/css/css.js"></script>
<script src="codemirror-5.65.9/mode/clike/clike.js"></script>
<script src="codemirror-5.65.9/mode/php/php.js"></script>
<style>
      .CodeMirror {border-top: 1px solid black; border-bottom: 1px solid black;}
    </style>';
  if(filesize("$dir/$file")>$max_edit_size) 
    print"File size exceeds the limit of $max_edit_size bytes<br>Have the Site Admin edit config.php to customize this";
  else{
   print"<a href='?dir=$dir_e' title=' cancel '>&nbsp;BACK&nbsp;</a> <b><font size=1>$dir</font>/$file</b><br></center>";
   print("<form action='?dir=$dir_e&file=$file_e' method=POST >
       <textarea  id=dataBox  name=data  wrap=$word_wrap >".htmlentities(file_get_contents("$dir/$file"),ENT_QUOTES,$view_charset)."</textarea>
       <input type=hidden name=dir value='$dir_e'>
       <input type=hidden name=file value='$file_e'><br>
       <div id=savBttn style='position:absolute; left: expression(document.body.clientWidth-$IExS); top: expression(document.body.clientHeight-$IEyS);'><input type=submit name=action value=Save></div></form>
      
	
	 
	 <script>
      var editor = CodeMirror.fromTextArea(document.getElementById('dataBox'), {
        lineNumbers: true,
        matchBrackets: true,
        mode: 'application/x-httpd-php',
        indentUnit: 4,
        indentWithTabs: true
      });
    </script>
       
       <style>
	body{overflow:hidden; }
	a, a:link, a:visited, a:active{
	 color:black;
	 text-decoration:none;
	 border:ButtonFace 1px solid;
	 padding-left:5px;
	 padding-right:5px;
	}
	.CodeMirror { height:600px; font-size: 16px;}
	a:hover{
	 color:black;
	 background-color:ThreeDFace;
	 border-top:ButtonHighlight 1px solid;
	 border-left:ButtonHighlight 1px solid;
	 border-right:ButtonShadow 1px solid;
	 border-bottom:ButtonShadow 1px solid;
	 text-decoration:underline overline;
	}
	</style>\r\n");
  }
}

function getFileMod($file){
    return substr(sprintf('%o', fileperms($file)), -2);
}

function save($file){
  global $msg, $dir ;
    $file = $dir.DIRECTORY_SEPARATOR.$file;

    $mod = getFileMod($file);
    if($mod!='77'&& $mod!='55'){
        chmod($file,'0755');
    }//

    $data = $_POST['data'];
    $f = fopen($file,"w");
    if (fwrite($f,$data))
        $msg = "File ['$file'] saved!";
    fclose($f);
}

function up($dir) {
  global $homedir, $restrict_to_home, $dir ;
  $dirup = substr($dir,0,-1);
  $pos   = strrpos($dir, "/");
  if ($pos===false)
    $pos = strrpos($dir, "\\");					# for windows
  if ($pos!=0)
    $up = substr($dir,0,-(strlen($dir)-$pos));
  else
    $up="/";
  if ((strlen(strstr($up,$homedir))!=strlen($up)) && $restrict_to_home)
    $up=$homedir;	// restrict to home dir!
  $dir = $up;
  chdir($up);
}

function gethost($uri){
  if ($uri=="")
    $uri = $_SERVER['SCRIPT_URI'];
  if (!$uri)
    return $_SERVER['HTTP_HOST'];
  preg_match('@^(?:http://)?([^/]+)@i',$uri, $matches);
  $host = $matches[1];

  return $host;
}

function gethostedroot(){
  global $realdir ;
  $thisdir = $realdir;
  $uri     = isset($_SERVER['SCRIPT_URI']) ? $_SERVER['SCRIPT_URI'] : "";
  $ruri    = $_SERVER['SCRIPT_NAME'];
//  $ruri    = $_SERVER['REQUEST_URI'];
  if (!$uri)
    $uri = 'http://'.$_SERVER['HTTP_HOST'].$ruri;
  preg_match('@^(?:http://)?([^/]+)@i',$uri, $matches);
  $host = $matches[1];
//echo "host=".$host."<br>";
//echo "thisdir=".$thisdir."<br>";
//echo "uri=".$uri."<br>";
//echo "ruri=".$ruri."<br>";
  $s = strpos($uri,$host)+strlen($host);
  $e = strpos($uri,"/",$s+1) - $s+1;
//echo "e=".$e."<br>";
if ($e<1)
    $e = strlen($uri)-strrpos($uri,"/");
//echo "s=".$s."<br>";
//echo "e=".$e."<br>";
  $startdir = substr($uri,$s,$e);
//echo "startdir=".$startdir."<br>";
  $hostpath = substr($thisdir,0,strpos($thisdir,$startdir));
//  if(!$hostpath) $hostpath = getalthostedroot();
//echo "hp:$hostpath;";

  return $hostpath;
}

function getalthostedroot(){
  $filepath = explode('/',$_SERVER['SCRIPT_FILENAME']);
  $webpath  = explode('/',$_SERVER['SCRIPT_NAME']);
  while (!$finished && count($webpath)!=0) {
    if (end($filepath)==end($webpath)) {
      pop($filepath);
      pop($webpath);
    }else
      $finished = true;
  }

  return implode('/',$filepath);
}

function gethostedpath($dir){
  global $pathtype, $realdir ;
  $addresspath = $dir;
//echo "addresspath=".$addresspath."<br>";
  $hostroot = gethostedroot();
//echo "hostroot=".$hostroot."<br>";
  $hostpath = str_replace($hostroot,"",$addresspath);
//echo "hostpath=".$hostpath."<br>";
  if (!$hostpath)
    $hostpath = "/";
//echo "hostpath=".$hostpath."<br>";
  if ($pathtype=="url"){
    $host = gethost("");
    $addresspath = $host.$hostpath;
  }elseif ($pathtype=="root"){
    $addresspath = $hostpath;
//    if ( $addresspath."/" == $realdir )
//       $addresspath = "/";
  }
//    $addresspath = $hostpath;
//echo "addresspath=".$addresspath."<br>";

  return $addresspath;
}

function securepath($dir){
  global $hide_hack ;
  if ($hide_hack)
    return $dir;
  $retdir = gethostedroot();
  if ($retdir==$dir)
    return $dir;
  $tempdir = substr($dir,strlen($retdir));
  $folder = explode("/",$tempdir);
  foreach ($folder as $name)
    if ($name!=""){
      if (hidethis(strtolower($name)))
        break;
      $retdir .= "/$name";
    }

  return $retdir;
}

function dirfrom($dir){
//  $dir = str_replace("\\","/",$dir);
  $tempdir = $dir;
  if (strpos($tempdir,"/")===false) $tempdir = "/";
  $tempdir = strstr($tempdir,"/");
  $hostroot = gethostedroot();
//echo "hr:$hostroot";
  if($hostroot)
  if (strpos($dir,$hostroot)===false)
    $tempdir = $hostroot.$tempdir;

  return $tempdir;
}

function folderin($path){
  global $homedir ;
  $hpath = str_replace("\\","/",$homedir);
  $path = str_replace("\\","/",$path);
  if ($hpath==$path)
    return "home";
  $tempdir = str_replace($hpath."/","",$path);
  $folders = explode("/",$tempdir);
  if (count($folders)==1)
    return $folders[0]." in home";

  return end($folders)." in ".$folders[count($folders)-2];
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
//  if (!is_array($btn_size) || $no_btn)
//    $skin = "";

  $dir_e = urlencode($dir);
  $homedir_e = urlencode($homedir);
  $IE1 = "class=buttonrow";
  $IE2 = "<a>";
  if (substr_count($_SERVER["HTTP_USER_AGENT"],"MSIE")>0){
    $IE1 = "class=IEbutton";
    $IE2 = "<a href='' onclick='return(false);' class=IEbutton>";
  }

  print"<a href='?dir=$homedir_e' $IE1><img width=$w height=$h src=skins/{$si}home{$gi} class=button title=Home border=0 onError=\"this.src='skins/home.gif';\"></a>
	<a href='?action=Up&dir=$dir_e' $IE1><img width=$w height=$h src=skins/{$si}up{$gi} class=button title=Up border=0 onError=\"this.src='skins/up.gif';\"></a>
	<a href='?dir=$dir_e' $IE1><img width=$w height=$h src=skins/{$si}reload{$gi} class=button title=Refresh border=0 onError=\"this.src='skins/reload.gif';\"></a>
	<img width=1 height=$h class=seperator>
	$IE2<img width=$w height=$h src=skins/{$si}copy{$gi} onClick='copy(f);' class=button title='Copy [Shift+Ctrl+C]' onError=\"this.src='skins/copy.gif';\"></a>
	$IE2<img width=$w height=$h src=skins/{$si}move{$gi} onClick='move(f);' class=button title='Move [Shift+Ctrl+M]' onError=\"this.src='skins/move.gif';\"></a>
	$IE2<img width=$w height=$h src=skins/{$si}delete{$gi} onClick='delet(f);' class=button title='Delete [Shift+Ctrl+X]' onError=\"this.src='skins/delete.gif';\"></a>
	$IE2<img width=$w height=$h src=skins/{$si}rename{$gi} onClick='rename(f);' class=button title='Rename [F2]' onError=\"this.src='skins/rename.gif';\"></a>
	<img width=1 height=$h class=seperator>
	$IE2<img width=$w height=$h src=skins/{$si}newfolder{$gi}  onClick='newfolder(f);' class=button title='New Folder [Shift+Ctrl+N]' onError=\"this.src='skins/newfolder.gif';\"></a>
	$IE2<img width=$w height=$h src=skins/{$si}newfile{$gi}  onClick='newfile(f);' class=button title='New File [Shift+Ctrl+F]' onError=\"this.src='skins/newfile.gif';\"></a>
	<img width=1 height=$h class=seperator>
	$IE2<img width=$w height=$h src=skins/{$si}chmod{$gi}  onClick='chmode(f,$i);' class=button title='Change Permissions' onError=\"this.src='skins/chmod.gif';\"></a>
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
                <option value=0555>555</option>
		<option value=0444>444</option>
                <option value=0004>004</option>
		<option value=755>default</option>
		<option value=444>readonly</option>
		<option value=666>readwrite</option>
	</select>
	<img width=1 height=$h class=seperator>
	$IE2<img width=$w height=$h src=skins/{$si}view{$gi}  onClick='thumbnail();' class=button title='Thumbnail view [Shift+Ctrl+T]' onError=\"this.src='skins/view.gif';\"></a>
	$IE2<img width=$w height=$h src=skins/{$si}extract{$gi}  onClick='extract();' class=button title='Extract Zip  [Shift+Ctrl+E]' onError=\"this.src='skins/extract.gif';\"></a>";
  if ($i==0){
    $arr_type=""; $arr_size=""; $arr_name="";
    if ($arrange_by=="type")
      $arr_type="selected";
    elseif($arrange_by=="size")
      $arr_size="selected";
    else
      $arr_name="selected";
    print"
	<img width=1 height=$h class=seperator>
	<select name=arr style='margin-top:2px; vertical-align:top;' onChange='arrange(this)'>
		<option value=name $arr_name>By Name</option>
		<option value=type $arr_type>By Type</option>
		<option value=size $arr_size>By Size</option>
	</select>";
    echo '<a style="color: #9d1e15 ; margin-left: 100px; font-size: 14px;" href="login.php?action=logout">退出</a>';
  }
}

function leftdata(){
  global $dir, $mode, $encoding, $realdir, $no_tsk, $tsk_size, $P ;

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
	<tr><td><a href='' onClick='searchfile();return(false);'><img src=skins/{$si}search{$gi} width=$w height=$h border=0 onError=\"this.src='skins/search.gif';\"></a></td><td><a href='' onClick='searchfile();return(false);' class=leftitem>File Search</a><td></tr>
	<tr><td><a href='' onClick='thumbnail();return(false);'><img src=skins/{$si}view{$gi} width=$w height=$h border=0 onError=\"this.src='skins/view.gif';\"></a></td><td><a href='' onClick='thumbnail();return(false);' class=leftitem>View as thumbnail</a><td></tr>
	<tr><td><a href='' onClick='extract();return(false);'><img src=skins/{$si}extract{$gi} width=$w height=$h border=0 onError=\"this.src='skins/extract.gif';\"></a></td><td><a href='' onClick='extract();return(false);' class=leftitem>Extract Here</a><td></tr>
	<tr><td><a href='' onClick='wget();return(false);'><img src=skins/{$si}file{$gi} width=$w height=$h border=0 onError=\"this.src='skins/file.gif';\"></a></td><td><a href='' onClick='wget();return(false);' class=leftitem>Get Remote File</a><td></tr>
	<tr><td><a href='' onClick='openeditor();return(false);' title='Edit HTML [Shift+Ctrl+H]'><img src={$eh} width=$w height=$h border=0 onError=\"this.src='skins/edithtml.gif';\"></a></td><td><a href='' onClick='openeditor();return(false);' title='Edit HTML [Shift+Ctrl+H]' class=leftitem>Open in HTML Editor</a><td></tr>
	<tr><td><a href='' onClick='opensource();return(false);' title='Edit Code [Shift+Ctrl+S]'><img src={$ec} width=$w height=$h border=0 onError=\"this.src='skins/editcode.gif';\"></a></td><td><a href='' onClick='opensource();return(false);' title='Edit Code [Shift+Ctrl+S]' class=leftitem>Open in Code Editor</a><td></tr>
	<tr><td><a href='' onClick='browseHere();return(false);' title='View in Browser'><img src={$vb} width=$w height=$h border=0 onError=\"this.src='skins/html.gif';\"></a></td><td><a href='' onClick='browseHere();return(false);' title='View in Browser' class=leftitem>View in Browser</a><td></tr>
	<tr><td><a href=\"?dir=$dir_e\" onClick='' title='Explore from Here' target=_blank><img src=$ex_here width=$w height=$h border=0 onError=\"this.src='favicon.png';\"></a></td><td><a href=\"?dir=$dir_e\" onClick='' target=_blank title='Explore from Here' class=leftitem>Explore from Here</a><td></tr>";
  if (file_exists($realdir."DevEdit/editor.php"))print "<tr><td><a href='' onClick='opendevedit();return(false);' title='Edit with DevEdit (IDE) [Shift+Ctrl+I]'><img src=thumb.php?x=$w&y=$h&img=DevEdit/DevEdit.gif width=$w height=$h border=0 onError=\"this.src='DevEdit/DevEdit.gif';\"></a></td><td><a href='' onClick='opendevedit();return(false);' title='Edit with DevEdit (IDE) [Shift+Ctrl+I]' class=leftitem>Open in DevEdit</a><td></tr>";
  if (end($P)!="details.php" && file_exists($realdir."details.php"))       print "<tr><td><a href='details.php?dir=$dir_e'><img src={$ad} width=$w height=$h border=0 onError=\"this.src='skins/asdetails.gif';\"></a></td><td><a href='details.php?dir=$dir_e' class=leftitem>View as Details</a></td></tr>";
  elseif (end($P)!="windows.php")       print "<tr><td><a href='windows.php?dir=$dir_e'><img src={$ai} width=$w height=$h border=0 onError=\"this.src='skins/asicons.gif';\"></a></td><td><a href='windows.php?dir=$dir_e' class=leftitem>View as Icons</a></td></tr>";
  if (file_exists($realdir."console.php"))       print "<tr><td><a href='console.php?dir=$dir_e'><img src=skins/{$si}console{$gi} width=$w height=$h border=0 onError=\"this.src='skins/console.gif';\"></a></td><td><a href='console.php?dir=$dir_e' class=leftitem>Console</a></td></tr>";
  if (file_exists($realdir."database.php"))      print "<tr><td><a href='database.php?dir=$dir_e'><img src=skins/{$si}database{$gi} width=$w height=$h border=0 onError=\"this.src='skins/database.gif';\"></a></td><td><a href='database.php?dir=$dir_e' class=leftitem>Database</a></td></tr>";
  if (file_exists($realdir."archive.php"))       print "<tr><td><a href='archive.php?dir=$dir_e'><img src=skins/{$si}archive{$gi} width=$w height=$h border=0 onError=\"this.src='skins/archive.gif';\"></a></td><td><a href='archive.php?dir=$dir_e' class=leftitem>Archiver</a></td></tr>";
  if (file_exists($realdir."imageview.php"))     print "<tr><td><a href='images.php?dir=$dir_e'><img src=skins/{$si}imgview{$gi} width=$w height=$h border=0 onError=\"this.src='skins/imgview.gif';\"></a></td><td><a href='images.php?dir=$dir_e' class=leftitem>ImageView</a></td></tr>";
  if (file_exists($realdir."media.php"))         print "<tr><td><a href='media.php?dir=$dir_e'><img src=skins/{$si}media{$gi} width=$w height=$h border=0 onError=\"this.src='skins/media.gif';\"></a></td><td><a href='media.php?dir=$dir_e' class=leftitem>MediaMan</a></td></tr>";
  if (file_exists($realdir."administrator.php")) print "<tr><td><a href='administrator.php?dir=$dir_e'><img src=skins/{$si}admin{$gi} width=16 height=16 border=0 onError=\"this.src='skins/admin.gif';\"></a></td><td><a href='administrator.php?dir=$dir_e' class=leftitem>Administrator</a></td></tr>";
  if (file_exists($realdir."controlpanel.php"))  print "<tr><td><a href='controlpanel.php?dir=$dir_e'><img src=skins/{$si}controlpanel{$gi} width=$w height=$h border=0 onError=\"this.src='skins/controlpanel.gif';\"></a></td><td><a href='controlpanel.php?dir=$dir_e' class=leftitem>Control Panel</a></td></tr>";
  if (file_exists($realdir."readonly.php"))      print "<tr><td><a href='readonly.php?dir=$dir_e'><img src=skins/{$si}readonly{$gi} width=$w height=$h border=0 onError=\"this.src='skins/readonly.gif';\"></a></td><td><a href='readonly.php?dir=$dir' class=leftitem>roFileWalker</a></td></tr>";
  if (file_exists($realdir."atari.php"))         print "<tr><td><a href='atari.php?dir=$dir_e'><img src=thumb.php?x=$w&y=$h&img=skins/atari/screenshot.png width=$w height=$h border=0 onError=\"this.src='skins/atari/screenshot.png';\"></a></td><td><a href='atari.php?dir=$dir' class=leftitem>AtariST Skin</a></td></tr>";
  print"</table></div></td></tr></table><br>";
  print"<table cellspacing=0 width=100%>";
  print"<tr><td class=lefthead onClick='thumbnail();'><b>Thumbnail View</b></td><tr>";
  print"<tr><td class=leftsub><div width=100% class=info id=thumb></div></td></tr></table><br>";
  print"<table cellspacing=0 width=100%><tr><td class=lefthead><b>User Info</b></td><tr>";
  print"<tr><td class=leftsub><div width=100% class=info>User IP: ".$_SERVER['REMOTE_ADDR']."<br>
	Working in <b>'$mode'</b> mode<br>
	Encoding: <b>$encoding</b><br>
	&#00149;  <a href='' onClick='config();return(false);'><u>Configure PHP Navigator</u></a><br>
	&#00149;  <a href='' onClick='about();return(false);'><u>About PHP Navigator</u></a><br>
	&#00149;  <a href=server.php target='_blank'><u>View Server Info</u></a><br>
	&#00149;  <a href='' onClick='favourites();return(false);'><u>Favourites</u></a><br>
	&#00149;  <a href='' onClick='help();return(false);'><u>Quick Help</u></a><br>
	&#00149;  <a href='./'><u>Restart</u></a><br>
	</div></td></tr>";
  print"</table><br>
	<center><a href=http://navphp.sourceforge.net target=_blank ><b>navphp.sourceforge.net</b></a><br>&nbsp;</center>";
}

function groupicon($file){
  global $thumb, $dir, $realdir, $icn_size, $groups, $groupimgs, $P ;

  $data = pathinfo($file);
  $ext  = @strtolower($data['extension']);

  $found = false;
  $foundimg = false;
  if (in_array($ext,$groups)) {
    $img = $ext.$groupimgs;
    $found = true;
  }else{
    foreach ($groups as $group){
      $gr_array = isset($GLOBALS["gr_$group"]) ? $GLOBALS["gr_$group"] : "";
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
    print"<center><a class=icon><img
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

function filedetails($file){
  global $dir, $realdir, $no_icn, $icn_size, $skin;

  $si = $GLOBALS['fileicons'];
  $gi = $GLOBALS['groupimgs'];

  $w = '16';
  $h = $w;

//  if(!is_array($icn_size) || $no_icn) $skin = "";

  $scale = array(" Bytes"," KB"," MB"," GB");
  $stat  = stat($file);
  $size  = $stat[7];
  for ($s=0;$size>1024&&$s<4;$s++)
    $size=$size/1024;						# Calculate in Bytes,KB,MB etc.
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
  $dir_e      = urlencode($dir);
  $filename   = wordwrap($filename_t, 15, "<br>",1);

  if (is_dir($file)){
    $img = "skins/{$si}dir{$gi}";
    if (!file_exists($realdir.$img))
      $img = "skins/{$skin}dir{$gi}";
    if (!file_exists($realdir.$img))
      $img = "skins/dir.gif";
    print "<a class=icon title='Double click to download'><img
	src=\"$img\" width=$w height=$h valign=bottom
	alt=\"<b>$filename</b><br>File Folder<br><br>
	Permissions:".decoct(fileperms($file)%01000)."<br>
	Modified: ".date('d-m-y, G:i', $stat[9])."\" 
	onMouseDown=\"loadfile(this,'');\" id=file title=\"$filename_t\" 
	onDblClick=\"download('$filename_t');\"  spec=\"$spec\" 
	onError=\"this.src='skins/dir.gif';\">&nbsp;<a 
	class=icon onDblClick='opendir();' spec='$spec' 
	title=\"$filename_t\" style='cursor:pointer;' 
	onMouseDown=\"loadfile(this,'<b>$filename</b><br>File Folder<br><br>Permissions:".decoct(fileperms($file)%01000)."<br>Modified: ".date('d-m-y, G:i', $stat[9])."');\" 
	>$filename_t Folder 
	Permissions:".decoct(fileperms($file)%01000)." 
	Modified: ".date('d-m-y, G:i', $stat[9])."</a><br>";
  }else{
    $ficon = groupicon($file);
    $img = "skins/{$si}$ficon";
    if (!file_exists($realdir.$img))
      $img = "skins/{$skin}$ficon";
    if (!file_exists($realdir.$img))
      $img = "skins/$ficon";
    if (strstr($ficon,"thumb")==$ficon)
      $img = $ficon;
    print"<a class=icon title='Double click to download'><img
	src=\"$img\" width=$w height=$h valign=bottom
	alt=\"<b>$filename</b><br><br>Size: $size<br>
	Permissions:".decoct(fileperms($file)%01000)."<br><br>
	Modified: ".date('d-m-y, G:i', $stat[9])."<br>
	Accessed: ".date('d-m-y, G:i', $stat[8])."\" 
	onMouseDown=\"loadfile(this,'');\" id=file title=\"$filename_t\" 
	onDblClick=\"location.href='?action=Download&file=$filename_e&dir=$dir_e';\" spec='$spec' 
	onError=\"this.src='skins/file.gif';\">&nbsp;<a 
	class=icon onDblClick='$dblclick;' spec='$spec' 
	title=\"$filename_t\" style='cursor:pointer;' 
	onMouseDown=\"loadfile(this,'<b>$filename</b><br><br>Size: $size<br>Permissions:".decoct(fileperms($file)%01000)."<br><br>Modified: ".date('d-m-y, G:i', $stat[9])."<br>Accessed: ".date('d-m-y, G:i', $stat[8])."');\">$filename_t Size: $size 
	Permissions:".decoct(fileperms($file)%01000)." 
	Modified: ".date('d-m-y, G:i', $stat[9])." 
	Accessed: ".date('d-m-y, G:i', $stat[8])."</a><br>";
  }
}

function filespec($file){	# Attributes z-zip, t-thumb, d-dir, h-html
  global $HTMLfiles;

  $spec ="f";

  if (is_dir($file)){
    $spec.="d";
    return $spec;
  }

  $data = pathinfo($file);
  $ext  = isset($data["extension"]) ? strtolower($data["extension"]) : "";
  $html=false;

  if (is_editable($file))
    $spec.="e";
  if ($ext=="png" || $ext=="gif" || $ext=="jpg" || $ext=="jpeg" || $ext=="jpc" || $ext=="jpx" || $ext=="jb2" || $ext=="jp2" || $ext=="bmp" || $ext=="swf" || $ext=="swc" || $ext=="psd" || $ext=="tif" || $ext=="tiff" || $ext=="wbm" || $ext=="wbmp" || $ext=="xbm" || $ext=="xbmp" || $ext=="xbitmap" || $ext=="iff")
    $spec.="t";
  if ($ext=="zip" || $ext=="tar" || $ext=="gz" || $ext=="tgz")
    $spec.="z";

  foreach (explode(" ",$HTMLfiles) as $type)
    if ($ext==$type)
      $html=true;

  if($html==true)
    $spec.="h";

  return $spec;
}

function authenticate(){
  global $user, $passwd, $enable_login, $dir, $go, $homedir, $restrict_to_home, $action, $file, $change, $msg ;
  if(isset($_SESSION['loginBl']) && $_SESSION['loginBl'] ===true){
      $dir     = realpath($dir);
      $homedir = realpath($homedir);
      $file    = basename(stripslashes(urldecode($file)));
      $change  = stripslashes(urldecode($change));
      $dir     = str_replace("\\","/",$dir);			# For Windows
      $homedir = str_replace("\\","/",$homedir);			# For Windows

      if($go && !$action) $dir = realpath(dirfrom($go));

      if(!$dir) $dir = $homedir;

//  if ((strlen(strstr($dir,$homedir))!=strlen($dir)) && $restrict_to_home)
      if(strpos($dir,$homedir)===false && $restrict_to_home) 	# restrict to homedir!
      {
          $msg[] .= "<b>Warning: Access restricted to home dir!</b>";
          $dir = $homedir;						# restrict to homedir!
      }
      if (!is_dir($dir)){
          $msg[] .= "<b>Warning: Reference to invalid directory!</b>";
          $dir = $homedir;						# default to homedir!
      }
  }else{
      header('Location:login.php');
  } //?true :false;
 return ;
  exit;
  if( !isset($_SERVER['PHP_AUTH_USER']) )
  {
    if (isset($_SERVER['HTTP_AUTHORIZATION']) && (strlen($_SERVER['HTTP_AUTHORIZATION']) > 0)){
        @list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':', base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
        if( strlen($_SERVER['PHP_AUTH_USER']) == 0 || strlen($_SERVER['PHP_AUTH_PW']) == 0 )
        {
#           unset($_SERVER['PHP_AUTH_USER']);
#           unset($_SERVER['PHP_AUTH_PW']);
        }
    }else{
      $_SERVER['PHP_AUTH_USER']='';
      $_SERVER['PHP_AUTH_PW']='';
    }
  }
  //登陆失败的
  if ((@$_SERVER['PHP_AUTH_USER']!=$user||@$_SERVER['PHP_AUTH_PW']!=$passwd)&&$enable_login) {
    header('WWW-Authenticate: Basic realm="PHP Navigator"');
    header('HTTP/1.0 401 Unauthorized');
    if (file_exists("readonly.php")){
      include("readonly.php");
    }else{
      print "<h2><font face=Tahoma>You are not authorised to view this file!</font></h2>";
    }
    exit;
  } 

  $dir     = realpath($dir);
  $homedir = realpath($homedir);
  $file    = basename(stripslashes(urldecode($file)));
  $change  = stripslashes(urldecode($change));
  $dir     = str_replace("\\","/",$dir);			# For Windows
  $homedir = str_replace("\\","/",$homedir);			# For Windows

  if($go && !$action) $dir = realpath(dirfrom($go));

  if(!$dir) $dir = $homedir;

//  if ((strlen(strstr($dir,$homedir))!=strlen($dir)) && $restrict_to_home)
  if(strpos($dir,$homedir)===false && $restrict_to_home) 	# restrict to homedir!
  {
    $msg[] .= "<b>Warning: Access restricted to home dir!</b>";
    $dir = $homedir;						# restrict to homedir!
  }
  if (!is_dir($dir)){
    $msg[] .= "<b>Warning: Reference to invalid directory!</b>";
    $dir = $homedir;						# default to homedir!
  }
}

function download(){						# Download file and folder-zip;
  global $dir, $file, $allow_downloads, $download_as_zip ;

  if ($allow_downloads){
    include_once("lib/zip.lib.php");

    if (is_file($dir."/".$file)){
      $ext = strtolower(substr(strrchr($file, "."),1));
      if ($ext=="zip" || !$download_as_zip)  {
        header("Content-Disposition: attachment; filename=$file");
        header("Content-Type: file/x-msdownload");
        header("Content-Length: ".filesize($dir."/".$file));
        echo file_get_contents($dir."/".$file);
      }else{
        $newzip = new zipfile();
        $data = file_get_contents("$file");
        $newzip->addFile($data,"$file",0);	
        header("Content-Disposition: attachment; filename=".$file.".zip");
        header("Content-Type: file/x-msdownload");
        $data = $newzip->file();
        header("Content-Length: ".strlen($data));
        echo $data;
      }
    }elseif (is_dir($dir."/".$file)){
      $newzip = new zipfile();
      chdir($dir);
      $name = $file;
      add_dir($name,$newzip);
      header("Content-Disposition: attachment; filename=".$name."_FileWalker.zip");
      header("Content-Type: file/x-msdownload");
      $data = $newzip->file();
      header("Content-Length: ".strlen($data));
      echo $data;
    }else
      die("File or Folder does not exist.<br>What are you trying to download?");
  }else
    die("ReadOnly: Downloading of files not allowed");
}

function add_dir($dir,$newzip){					# recursive adding of files to zip
  static $no;
  $no = $no+1;
//  if(($no>10)|| (strlen($newzip->file())>5000000)) die("Too many sub directories (>$no) or Total size > 5MB!<br>Try them by parts. [Some security measures!] ");
  if (strlen($newzip->file())>5000000) die("Total size > 5MB!<br>Try them by parts. ($no folders processed) ");
  if ($dh = opendir($dir)){
    while ($file = readdir($dh))
      $files[] = $file;

    $del = false;
    if (count($files)==2){
      $perms = fileperms("$dir");
      chmod("$dir",0777);
      $fh = fopen("$dir/empty.dir","w");
      fclose($fh);
      chmod("$dir/empty.dir",0777);
      $files[2] = "empty.dir";
      $del = true;
    }

    foreach ($files as $file){
      if ($file!="." && $file!=".." && !is_dir("$dir/$file")){
        $data = file_get_contents("$dir/$file");
        $newzip->addFile($data,"$dir/$file",0);
      }
    }
    foreach ($files as $file){
      if ($file!="." && $file!=".." && is_dir("$dir/$file"))
        add_dir("$dir/$file",$newzip);
    }

    if ($del){
      @unlink("$dir/empty.dir");
      chmod("$dir",$perm);
    }

    closedir($dh);
  }
}

function expired(){
  header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");		# Date in the past
  header ("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");	# always modified
  header ("Cache-Control: no-cache, must-revalidate");		# HTTP/1.1
  header ("Pragma: no-cache");
}

								# I assume only IE6 and mozilla support AJAX.
								# This is called only if client side check fails..

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

function is_editable($filename){				# Checks whether a file is editable
  global $EditableFiles;
  $ext = strtolower(substr(strrchr($filename, "."),1));

  foreach (explode(" ", $EditableFiles) as $type)
    if ($ext==$type)
      return TRUE;

  return FALSE;
}