<?php
#---------------------------
# PHP Navigator 3.2 (4.0)
# dated: 03-8-2006
# Coded by: Cyril Sebastian,
# Kerala,India
# web: navphp.sourceforge.net
#---------------------------
# PHP Navigator 4.12.8
# dated: 20-07-2007
# edited: 31-12-2007
# Modified by: Paul Wratt,
# Melbourne,Australia
# web: phpnav.isource.net.nz
#---------------------------


$dir     = @$_REQUEST['dir'];
$file    = @$_REQUEST['file'];
$change  = @$_REQUEST['change'];
$action  = @$_REQUEST['action'];
$ajax    = isset($_REQUEST['ajax']);
$details = isset($_REQUEST['details']);

$file   = urldecode($file);
$change = urldecode($change);
$dir    = urldecode($dir);

include_once("config.php");
include_once("functions.php");
include_once("lib/pclzip.class.php");
include_once("lib/tar.class.php");

getcookies();

include_once("skin.php");
#if ($use_groups){
#  if(file_exists("{$realdir}skins/{$skin}skin.php"))
#   @include_once("{$realdir}skins/{$skin}skin.php");
#  else
#   @include_once("{$realdir}skin.php");
#}

$reply=0;
$refresh="";

authenticate();	//user login
if($ajax) $refresh=" Refresh to view them. ";
if(!$dir) $dir=$homedir;
chdir($dir);

$exists = false;
if(is_file($file))
  $exists = true;
$ext = strtolower(substr(strrchr($file, "."),1));
$is_gnu = array("tar","gz","tgz");

if(!$exists)
  $msg="Error: File '$file' does not exists!";
else if($ext=="zip")
{
 $isold = file_exists(str_replace(".zip","",$file));

 $zip = new PclZip($file);
 $list = $zip->extract(".");
 if($list>0) 
   {
   $msg= count($list)." Files were extracted.";
   $reply=1;
   }
 else
    $msg= "Error: Unexpected error during extraction!";
}
else if(in_array($ext,$is_gnu))
{
 if($ext=="gz" && strripos(strtolower($file),".tar.gz"))
   $ext = "tar.gz";

 $zip = new Tar($file);
 if($zip->is_gzipped())
   $zip->ungzip();
 $list = $zip->extract(".");
 if($list>0)
   {
   $msg= count($list)." Files were extracted.";
   $reply=1;
   }
 else
    $msg= "Error: Unexpected error during extraction!";

}
else $msg="Error: '$file' is not a supported archive!";

if($ajax)
{
	$ext = ".".$ext;
	expired();
	if(!$reply)
	  print "|0|$msg|";
	elseif($reply && !$isold && file_exists(str_replace($ext,"",$file))){
	  print "|$reply|$msg|";
	  if (!$details) filestatus(str_replace($ext,"",$file))."|";
	  if ($details) filedetails(str_replace($ext,"",$file))."|";
	}else
	  print "|0|$msg $refresh|";
}