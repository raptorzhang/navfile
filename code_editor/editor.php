<?php
#---------------------------
# PHP Navigator 4.12
# dated: 12-11-2007
# edited: 28-11-2007
# Modified by: Paul Wratt,
# Melbourne,Australia
# web: phpnav.isource.net.nz
#---------------------------
# PHP Navigator
# by: Cyril Sebastian
# web: navphp.sourceforge.net
#---------------------------


 $dir=urldecode($_GET['dir']);
 $file=urldecode($_GET['file']);
 
include_once("../functions.php");
include_once("../config.php");

authenticate();

getcookies();

chdir($dir);

function saver($file)
 {
  global $msg;
  if(get_magic_quotes_gpc()){
  $data = stripslashes($_POST['data']);
  } else {
   $data = $_POST['data'];
  }
  $f=fopen($file,"w");
  if(fwrite($f,$data)) $msg= "File $file saved!";
  fclose($f);
 }

if (isset($_POST['save'])) saver(file);

 $data=pathinfo($file);
 $ext=strtolower($data["extension"]);
  if($ext=="htm"||$ext=="html"||$ext=="xml"||$ext=="shtml"||$ext=="mht") {
  $lan="html";
  }
  else if($ext=="js") {
  $lan="javascript";
  }
  else if($ext=="php"||$ext=="php3"||$ext=="php4"){
  $lan="php";
  }
  else if($ext=="c"||$ext=="cpp"){
  $lan="generic";
  }
  else if($ext=="css") {
  $lan="css";
  }
  else if($ext=="sql") {
  $lan="sql";
  }
  else if($ext=="java") {
  $lan="java";
  }
  else {
  $lan="text";
  }

 if(filesize("$dir/$file")>$max_edit_size) 
  print"File size exceeds the limit of $max_edit_size bytes<br>Have the Site Admin edit config.php to customize this";
 else
  {print"<center><font face=tahoma size=2><b><font size=1>$dir</font>/$file</b><br><style>body{background-color:ThreeDFace; margin:0px;}</style>";
 print("<script src=\"codepress.js\" type=\"text/javascript\"></script>
	<script language=\"javascript\" type=\"text/javascript\">
	function submitform()
	{
	dataBox.toggleEditor();
	return(true);
	}
	</script>
 <form action='' method=POST>
       <textarea rows=22 cols=80 class=\"codepress $lan\" id=\"dataBox\" name='data' style=\"width: expression(document.body.clientWidth-40); height: expression(document.body.clientWidth-50);\">".htmlentities(file_get_contents("$dir/$file"))."</textarea>
       <input type=hidden name=dir value='$dir'>
       <input type=hidden name=file value='$file'>
       <input type=hidden name=action value=Save><br>
       <input name=save type=Submit value=Save onclick=submitform();>
       <input type=button onClick='history.back()' value=Undo>
       <input type=button onClick='window.close();' value=Close></form>
	   <script language=JavaScript>
		function fixResize(){
		if (window.innerHeight){
			xObj = document.getElementById('dataBox');
			xObj.style.width = window.innerWidth-40;
			xObj.style.height = window.innerHeight-50;
		}}
		fixResize();
		</script></center>");
   }

 if($deflate){
 $data= ob_get_clean();
 echo gzdeflate($data);} 

?>