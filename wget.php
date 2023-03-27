<?php
#--------------------------------
# ConsoleFileWalker v1.0
# weBASH - wget
# Date: 18-11-2007
#---------------------------
# PHP Navigator 4.12.18
# Date: 30-05-2011
# Last: 30-05-2011
# Created by: Paul Wratt,
# Melbourne, Australia
# Auckland, New Zealand
# web: phpnav.isource.net.nz
#--------------------------------

error_reporting(E_ALL);

/*
$cmdline = urldecode($_REQUEST['cmdline']);
if(!$dir) $dir = urldecode($_REQUEST['dir']);
if(!$options) $options = urldecode($_REQUEST['options']);
$ajax = isset($_REQUEST['ajax']);
$file = urldecode($_REQUEST['file']);
$changeto = urldecode($_REQUEST['changeto']);

include_once("cmdconfig.php");
include_once("cmdfunctions.php");
*/

function areoptions($x_null){
// dummy function (see:cmdfunctions)
  return false;
}

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
$http = $file;

if ($use_groups){
  if(file_exists("{$realdir}skins/{$skin}skin.php"))
   @include_once("{$realdir}skins/{$skin}skin.php");
  else
   @include_once("{$realdir}skin.php");
}

authenticate();	//user login

$reply=0;

/*
    if (isoption("ajaxhelp"))    { $out = "cmd=wget&[options=[]]&file=[http://]server.com/path/name&changeto=[newname]&dir=fullsrcdir\n"; $help = true; }
    if (areoptions("? help"))    { $out = "wget [http://]server.com/path/name [newname]\n"; $help = true; }
    if (isoption("help"))        { $out = file_get_contents("console/man/webash/wget")."\n".$out; $help = true; }
    if (isoption("version"))     { $out = "weBASH - WGET version 1.0.0.1\n".$out; $help = true; }
*/

if(is_dir($dir) && !$help) {
  chdir($dir);

  $verbose = false;
  $silent = false;
  $continue = false;
  $headers = false;
  $download = true;

  if (areoptions("c continue"))           { $continue = true; }
  if (areoptions("spider"))               { $download = false; }
  if (areoptions("S server-response"))    { $headers = true; }
  if (areoptions("s silent"))             { $silent = true;  $verbose = false; }
  if (areoptions("v verbose"))            { $silent = false; $verbose = true; }

//  if(!ajax) list($changeto,$file) = getparts(2);

  $written = false;

  if(strstr($http,"http://")!=$http) $http = "http://".$http;
  $url_stuff = parse_url($http);
  $U = explode("/",$url_stuff['path']);
  $fn = end($U);
  if(!$fn) $fn = "index.html";
  if($change) $fn = $change;
  else $change = $fn;

  $fc = http_get($http);
  if($fc){
    if($fp=fopen($fn,'wb')) {
      fwrite($fp,$fc,strlen($fc));
      fclose($fp);
      $written = true;
    }
  }

  if ($written) { $out .= "'$fn' - ".filesize($fn)." bytes written"; $reply = 1; }
  elseif ($fc) $out .= filesize($fn)." bytes - unable to write '$fn'";
  else $out .= "unable to connect to $http\n";

  if ($silent) $out = "";

  $msg = $out;
}

function http_get($url){
  global $verbose, $headers, $out;

  $url_stuff = parse_url($url);
  $port = isset($url_stuff['port']) ? $url_stuff['port'] : 80;
  $fp = fsockopen($url_stuff['host'],$port);
  $query  = 'GET '.$url_stuff['path']." HTTP/1.0\n";
  $query .= 'Host: '.$url_stuff['host'];
  $query .= "\n\n";
  fwrite($fp, $query);

  while ($tmp=fread($fp,1024)){
    $buffer .= $tmp;
  }
  fclose($fp);
  preg_match('/Content-Length: ([0-9]+)/', $buffer, $parts);
  $content = substr($buffer,-$parts[1]);
  if ($verbose || $headers) $out .= substr($buffer,0,strlen($buffer)-$parts[1])."\n";
  return $content;
}

if($ajax) {
	expired();
	print"|$reply|$msg|";
	if($reply) {
	  if (!$details) filestatus($change)."|";
	  if ($details) filedetails($change)."|";
	}
}