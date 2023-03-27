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
# edited: 06-06-2011
# Modified by: Paul Wratt
# Melbourne, Australia
# Auckland, New Zealand
# web: phpnav.isource.net.nz
#---------------------------

$dir = @$_REQUEST['dir'];
$ajax=@$_REQUEST['ajax'];
$file=@$_REQUEST['file'];
$change = @$_REQUEST['change'];
$action = @$_REQUEST['action'];

$file = urldecode($file);
$change = urldecode($change);
$dir = urldecode($dir);

include_once("config.php");
include_once("functions.php");
include_once("lib/pclzip.class.php");
include_once("lib/tar.class.php");

getcookies();

include_once("skin.php");

$msg = ""; $reply = 0;

//if(!$dir) $dir=$homedir;
authenticate(); # user login

chdir($dir);

$not_dir = false;
if(is_file($file))
  $not_dir = true;

if($action!="dirinfo") {
  $ext = strtolower(substr(strrchr($file, "."),1));
  $is_gnu = array("tar","gz","tgz");
  if(!$not_dir)
    $msg = "Error: '$file' is a directory";
}

#----for zip tooltip-------
if($not_dir && $action=="zipinfo") {
  $msg = "<img src=skins/{$layout}zip{$groupimgs} width=16 height=16 onerror=\"this.src='skins/image.gif';\"> <b>$file</b><br>";
  if($ext=="zip") {
    $reply = 1;
    $zip = new PclZip($file);
    $info = $zip->properties();
    $files = $zip->listContent();

    if($info) {
      $msg .= "Files/Folders in zip file: ".$info['nb']."<br>Comment: ".substr($info['comment'],0,120)."...<br>Files: ";
      for($i=0;$i<3&&$i<count($files);$i++) {
        $path_parts = pathinfo($files[$i]['filename']);
        $msg .= $path_parts["basename"].", ";
      }
      $msg .= "...";
    }else {
      $msg .= " Corrupted zip file";
    }
  }else if(in_array($ext,$is_gnu)){
    $reply = 1;
    $zip = new Tar($file);
    $files = $zip->files();
    if(!$files && $zip->is_gzipped()){
      $msg .= " gzipped tar archive";  
    }else if($files){
     $files = array_keys($files);
     $info = count($files);
     if($info){
      $msg .= "Files/Folders in zip file: ".$info."<br>Files: ";
      for($i=0;$i<3&&$i<count($files);$i++) {
        $path_parts = pathinfo($files[$i]);
        $msg .= $path_parts["basename"].", ";
      }
      $msg .= "...";
     }else
      $msg .= " Corrupted tar archive file";
    }else{
      $msg .= " Corrupted archive file";
    }
  }else
    $msg .= " Not a supported archive!";
}

#------------ For folder tooltip----------------
if(!$not_dir && $action=="dirinfo") {
  $msg = "<img src=skins/{$layout}dir{$groupimgs} width=16 height=16 onerror=\"this.src='skins/image.gif';\"><b>$file</b><br>";
  $dir = "$dir/$file";
  $dir_total = 0;
  $file_total=0;

  if(file_exists($dir)) $reply = 1;

  if (is_dir($dir)) {
    if($dh=opendir($dir)) {
      while ($file=readdir($dh)) { $files[] = $file; }
      sort($files);
      foreach($files as $file) {
        if(is_dir("$dir/$file") && $file!="." && $file!="..") {
          if($dir_no<3) {
            $dir_msg .= $file.", ";
            $dir_no++;
          }
          $dir_total++;
        }elseif(!is_dir("$dir/$file") && $file!="." && $file!="..") {
          if($file_no<3) {
            $file_msg .= $file.", ";
            $file_no++;
          }
          $file_total++;
        }
      }
    }
  }
  $msg .= "$dir_total Folders and $file_total Files<br>";
  if($dir_total) $msg .= "Folders: $dir_msg...<br>";
  if($file_total) $msg .= "Files: $file_msg...";
}

#------------ For image tooltip----------------
if($not_dir && $action=="imginfo") {
  if($size=getimagesize($file)) {
    $reply = 1;
    $msg = "<img src=skins/{$layout}image{$groupimgs} width=16 height=16 onerror=\"this.src='skins/image.gif';\"> <b>$file</b><br>";
    $bits = eval('return $size["bits"];'); 
    if($bits) {
      $bitspc = $bits." bits color <br>";
      $colors = number_format(pow(2,$bits));
    }
    $channels = eval('return $size["channels"];');
    if($channels) {
      $bitspc = $bits." bits per channel <br>".number_format(pow(2,$bits))." colors per channel <br>";
      $channelno = $channels." channels<br>";
      $colors = number_format(pow(pow(2,$bits),$channels));
    }
    $msg .= $size[0]." x ".$size[1]." pixels <br>";
    if($colors) $msg .= "$channelno$bitspc"."$colors colours maximum <br>";
  }
}

if($ajax) {
	expired();
	print"|$reply|$msg|";
	}