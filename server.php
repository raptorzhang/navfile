<head>
<link href='inc/windows.css' rel=stylesheet type=text/css>
</head>
<body><center>
<table width=70% cellspacing=0 cellpadding=4>
<tr><td class=head>
<b>Server Information </td><td class=head> - PHP Navigator 4.12 <font color=orange><i>xp</i></font></b>
</td></tr>
<tr><td class=info>
<?php
print "<br>Server Name: ";
print "<br><br>Operating System: ";
print "<br><br>Processor: ";
print "<br><br>Server Software: ";
print "<br><br>Server Protocol: ";
print "<br><br>Server Port: ";
print "<br><br>Server Host: ";
print "<br><br>File Size Limit: ";
print "<br><br>Document Root: <br>";
?>
</td><td class=info>
<?php
$server = php_uname("n");
if($_ENV['OS']) $os = $_ENV['OS'];
else $os = php_uname("s")." ".php_uname("n")." ".php_uname("r");
if($_ENV['PROCESSOR_IDENTIFIER']) $cpu = $_ENV['PROCESSOR_IDENTIFIER'];
else $cpu = php_uname("m");

print "<b><br>".$server;
print "<br><br>".$os;
print "<br><br>".$cpu;
print "<br><br>".$_SERVER['SERVER_SOFTWARE'];
print "<br><br>".$_SERVER['SERVER_PROTOCOL'];
print "<br><br>".$_SERVER['SERVER_PORT'];
print "<br><br>".$_SERVER['HTTP_HOST'];

function get_file_size_limits(){
#-----Calculate Max Upload Size--
  $size_str = ini_get('upload_max_filesize');
  $z=0; $size=0;
  while(ctype_digit($size_str[$z])) {$size.=$size_str[$z]; $z++;}
  $size = intval($size);
  $max_size = $size.$size_str[$z];
  if($size_str[$z]=="M"||$size_str[$z]=="m") $size = $size*1024*1024;
  else if($size_str[$z]=="K"||$size_str[$z]=="k") $size = $size*1024;
  else $size = 1024*1024*1024;
  return array($max_size,$size);
}
list($max_size,$size) = get_file_size_limits();
print "<br><br>$max_size ($size)";

function gethostedroot()
{
  $thisdir = realpath(".")."/";
  $uri = $_SERVER['SCRIPT_URI'];
  $ruri = $_SERVER['REQUEST_URI'];
  preg_match('@^(?:http://)?([^/]+)@i',$uri, $matches);
  $host = $matches[1];
  $s = strpos($uri,$host)+strlen($host);
  $e = strpos($uri,"/",$s+1)-$s+1;
  if($e<1) $e = strrpos($uri,"/")-strlen($uri);
  $startdir = substr($uri,$s,$e);
  $hostpath = substr($thisdir,0,strpos($thisdir,$startdir));
  return $hostpath;
}

print "<br><br>".gethostedroot()."<br></b>";
?>
</td></tr>
<?php
print "<tr><td class=info valign=top><br><br>GD Library: <br></td><td class=info><br><br>";
$gd = gd_info();
if(is_array($gd)) {
  for(reset($gd); $key = key($gd); next($gd)) {
    $data = $gd[$key];
    if(!$data) $data = "false";
    if($data==1) $data = "true";
    echo "$key : ".$data."<br>\n";
  }
}else{
  print "not installed";
}
?><br>
</td></tr>
</table>
</body>
