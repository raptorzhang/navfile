<?php
#---------------------------
# PHP Navigator 3.2 (4.0)
# dated: 03-8-2006
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

$dir    = @$_REQUEST['dir'];
$file   = @$_REQUEST['file'];
$change = @$_REQUEST['change'];
$action = @$_REQUEST['action'];
$ajax   = isset($_REQUEST['ajax']);

$file   = urldecode($file);
$changeto = urldecode($change);
$dir    = urldecode($dir);

include_once("config.php");
include_once("functions.php");

$reply = 0;

authenticate(); //user login

chdir($dir);

$force = isset($_REQUEST['force']);         // <-- to be added (force overwrite)
$mkrecurse = isset($_REQUEST['mkrecurse']); // <-- to be added (force create destdir)

$destdir = realpath(dodotpath($changeto,$dir));
$source = realpath($file);
$copyto = $destdir.DIRECTORY_SEPARATOR.$file;
$copyto = str_replace("\\",DIRECTORY_SEPARATOR,$copyto);
$copyto = str_replace("//",DIRECTORY_SEPARATOR,$copyto);

if(strpos($destdir,$homedir)===false && $restrict_to_home)
  $msg="Error: Restricted to home dir!";
elseif(!file_exists($file))
  $msg="Error: '$file' does not exists!";
elseif(is_dir($file)){
  if(!is_dir($destdir) && !$force) $msg="Error: Folder '$changeto' does not exist!";
  elseif(is_dir($copyto)) $msg="Error: Folder '$file' already exists in '$changeto'!";
  elseif(traverse($source,$copyto)) {$msg.="<br>Folder '$file' copied to '$changeto'."; $reply=3;}
  else $msg="Error: Folder '$file' could not be copied!";
}else{
  if(file_exists($copyto) && !$force) $msg="Error: File '$file' already exists in '$changeto'!"; 
  else if(copy($file,$copyto)) {$msg="File '$file' copied to '$changeto'"; $reply = 3;}
  else $msg="Error: File copy failed!";
}

function dodotpath($changeto,$dir)
{
  if(is_dir(realpath($changeto))) return $changeto;

  $realdir = $dir;
  $newdir = $changeto;

# do parent loop until no more "../" or "./"
  $parentcount = substr_count($newdir,"..".DIRECTORY_SEPARATOR);
  if($parentcount>0){
    $dirarray = explode(DIRECTORY_SEPARATOR,$newdir);
    foreach($dirarray as $name){
      if ($name==".."){
        $realdir = substr($realdir,0,strrpos($realdir,DIRECTORY_SEPARATOR));
      }elseif ($name!='.' && $name!=''){
        $realdir = $realdir.DIRECTORY_SEPARATOR.$name;
      }
    }
  }
  return $realdir;
}

function traverse($dir,$todir)	# For recursive copying using realpath()
{
global $msg, $reply;
$l = strlen($dir); $i = 0; $j = 0; $k = 0;
if(!is_dir($todir)){
      if(!mkdir($todir)) return false;
}
try{
$copydir = new RecursiveIteratorIterator( new RecursiveDirectoryIterator($dir), true);
foreach ($copydir as $copyfile)
 {
  $file = $copyfile->getPathname();
  $path2file = substr($file,$l);
  $i++;
  if(is_dir($file)){
    if(!is_dir("$todir$path2file")){
      if(mkdir("$todir$path2file")) $k++;
      else $j++;
    }
  }else{
      if(@copy($file,"$todir$path2file")) $k++;
      else $j++;
  }
 }
}catch (Exception $ex){
//throw $ex;
}
$msg = "$k of $i files copied";
if ($j>0) $msg .= ", $j failed";
return true;
}

if($ajax)
{
	expired();
	print "|$reply|$msg||";
}