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


$dir     = @$_REQUEST['dir'];
$file    = @$_REQUEST['file'];
$change  = @$_REQUEST['change'];
$ajax    = isset($_REQUEST['ajax']);
$details = isset($_REQUEST['details']);

$file   = urldecode($file);
$change = urldecode($change);
$dir    = urldecode($dir);

include_once("config.php");
include_once("functions.php");

getcookies();

include_once("skin.php");

$reply=0;

authenticate();	//user login

chdir($dir);

$change = urldecode($change);

if(file_exists($change)) $msg="Error: Folder or file '$change' already exists!";
  elseif(mkdir($change)) { chmod($change,0777); $msg="New folder '$change' created"; $reply=1;}
  else $msg="Error: Can't create new folder!";

if($ajax)
{
	expired();
	print"|$reply|$msg|";
	if($reply) {
	  if (!$details) filestatus($change)."|";
	  if ($details) filedetails($change)."|";
	}
}