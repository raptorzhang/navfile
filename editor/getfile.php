<?php
#=================================
#  PHP Navigator 3.2
#  9:36 PM; August 16, 2006	
#  http://navphp.sourceforge.net
#=================================

if(strstr($_SERVER['HTTP_ACCEPT_ENCODING'],"deflate")){ ob_start(); $deflate=1;
header("Content-Encoding: deflate"); }	// start buffering for deflate encoding

$dir = @$_REQUEST['dir'];
$file = @$_REQUEST['file'];

include_once("../functions.php");
include_once("../config.php");
authenticate();

if(is_file("$dir/$file")) echo file_get_contents("$dir/$file");
else print "<h3>File $dir/$file not found!</h3>";
if($deflate){
$data= ob_get_clean();
echo gzdeflate($data);}