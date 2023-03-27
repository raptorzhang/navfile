<?php
#---------------------------
# PHP Navigator 3.2
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

#----------FUNCTION EXPLORE----------
#This is the core of the script which lists the files and folders
#and display them in windows style.
#------------------------------------

function explore($dir)
{
global $cols, $uploads, $i, $arrange_by;
print"<table cellspacing=8  id=filestable><tr class=center>";

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
     print "<td onmousedown=loadtd(this)>";
     filestatus($file);	# function to print file icon & details
     print "</td>\r\n";
	 if($i%$cols==0)
      print"</tr><tr class=center>";
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
		$sizes[]=0+filesize($file);
		}
	  array_multisort($sizes,SORT_NUMERIC ,SORT_DESC,$files); 
  }
  foreach($files as $file)	#default is sort by name
   {
   if($file!="."&&$file!=".."&&!is_dir($file))
    {
     print "<td onmousedown=loadtd(this)>";
     filestatus($file);	# function to print file icon & details
     print "</td>\r\n";
     if($i%$cols==0)
      print"</tr><tr class=center>";
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
      <input type=hidden name=perms value='$perms'><br>";
print"<table border=0 class=window width=100% cellspacing=0><tr><td width=100% align=center class=buttonrow nowrap><tr><td width=100% align=center class=buttonrow nowrap>";
printbuttons($dir,1);
print"</table></form>\r\n";


#-----Calculate Max Upload Size--
  $size_str = ini_get('upload_max_filesize');
  $z=0; $size=0;
  while(ctype_digit($size_str[$z])) {$size.=$size_str[$z]; $z++;}
  $size = intval($size);
  $max_size = $size.$size_str[$z];
  if($size_str[$z]=="G"||$size_str[$z]=="g")     $size = $size*1024*1024*1024;
  elseif($size_str[$z]=="M"||$size_str[$z]=="m") $size = $size*1024*1024;
  elseif($size_str[$z]=="K"||$size_str[$z]=="k") $size = $size*1024;

#--------UPLOAD FORM----------
print"</form><form name=f2 id=f2 enctype=multipart/form-data method=POST action='windows.php' onSubmit='return upload();'>
      <input type=hidden name=MAX_FILE_SIZE value='$size'><input type=hidden name=dir value='$dir'>";
for($i=1;$i<=$uploads;$i++)
 {
 print"<input type=file name=upfile[] id=upfile>&nbsp;";
 if($i%2==0) print"<br>";
 }
print"<input type=submit name=action value=Upload title=' max file size $max_size '></form><br>";
}