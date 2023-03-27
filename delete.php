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


$dir = @$_REQUEST['dir'];
$ajax=@$_REQUEST['ajax'];
$file=@$_REQUEST['file'];
$change = @$_REQUEST['change'];

$file = urldecode($file);
$change = urldecode($change);
$dir = urldecode($dir);

include_once("config.php");
include_once("functions.php");

$reply=0;

authenticate();	//user login

chdir($dir);

if(!file_exists($file)) $msg="Error: File '$file' does not exist!";
else if(is_dir($file)) traverse("$dir/$file");
else if(unlink($file)) {$msg= "File: '$file' deleted succesfully"; $reply=1;}
else $msg="Error: Can't delete file: $file";

function traverse($dir)	# For recursive deleting
{
global $msg, $reply;
$D = explode("/",$dir);
$curdir = end($D);
if($dh = opendir($dir)) 
  {
  while (($file = readdir($dh)))  {$files[] = $file;}
   foreach($files as $file)
   {
	if($file!="."&&$file!=".."&&!is_dir("$dir/$file"))
    {
     if(@unlink("$dir/$file")) {$msg= "File: '$file' deleted succesfully"; $reply=1;}
     else  { $msg="Error: Can't delete file $file";  return 0;}  	 
    }
   }
  foreach($files as $file)
   {
   if($file!="."&&$file!=".."&&is_dir("$dir/$file"))
    {
    traverse("$dir/$file");
    }
   }
  closedir($dh);
  if(rmdir("$dir")) {$msg="Folder: '$curdir' deleted"; $reply=1;}
  else { $msg="Error: Can't delete folder: $file"; return 0;}
 
  }
}

if($ajax)
	{expired();
	print"|$reply|$msg||";}