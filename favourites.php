<?php
include("config.php");
include("functions.php");

authenticate();	//user login & other restrictions

function ffolderin($path){
  global $homedir;
  if($path==$homedir) return "root";
  $tempdir = str_replace($homedir."/","",$path);
  $folders = explode("/",$tempdir);
  if(count($folders)==1) return $folders[0]." in root";
  return end($folders)." in ".$folders[count($folders)-2];
}

function checkPath($path){
  $ret = false;
  $fhandle = fopen("favorites.txt","rb");
  while(!feof($fhandle)){
    $line = str_replace("\r\n","",fgets($fhandle));
    if(feof($fhandle)) break;
    if($line==$path) {$ret = true; break; }
  }
  fclose($fhandle);
  return $ret;
}

if(!isset($multi_user)){
  if(!is_file("favorites.txt")) {
    $fhandle = fopen("favorites.txt","w");
    fclose($fhandle);
    chmod("favorites.txt",0666);
  }

  if((isset($_GET['add']) && $_GET['add']=='1') && !checkPath(urldecode($_GET['dir']))){
    $fhandle = fopen("favorites.txt","a");
    fwrite($fhandle,urldecode($_GET['dir'])."\r\n".urldecode($_GET['des'])."\r\n");
    fclose($fhandle);
  }elseif(isset($_POST['save']) && $_POST['save']==' Save '){
    $fhandle = fopen("favorites.txt","wb");
    $urls = $_POST['fdir'];
    $desc = $_POST['fdes'];
    for($i=0;$i<count($urls);$i++){
      fwrite($fhandle,urldecode($urls[$i])."\r\n".urldecode($desc[$i])."\r\n");
    }
    fclose($fhandle);
  }

  $fdir=array();$fdes=array();
  $fhandle = fopen("favorites.txt","rb");
  while(!feof($fhandle)){
    $line = str_replace("\r\n","",fgets($fhandle));
    if(feof($fhandle)) break;
    if(strlen($line)==0 || strstr($line,"#")==$line || strstr($line,"//")==$line) continue;
    $fdir[] = $line;
    $line = str_replace("\r\n","",fgets($fhandle));
    $fdes[] = $line;
  }
  fclose($fhandle);

}

?><html>
<head>
<title>Favourites - PHP Navigator v4.12</title>
<script>
function doFavourite(x,dir){
if(x.className=='edit') return;
window.opener.location.href=window.opener.location.pathname+'?go='+window.opener.encode(dir);
window.close();
}

function add_click(){
dir = window.opener.f.dir.value;
des = prompt('description for:\n'+dir,'');
if(!des || des=='') { return; }
location.href='?add=1&dir='+window.opener.encode(dir)+'&des='+window.opener.encode(des);
}

function cancel_click(x){
if(x.value=='Cancel') {
  df = document.forms[0];
  df.add.disabled = false;
  df.save.value = ' Edit ';
  df.cancel.value = ' Close ';
  xRows = document.getElementById('favbody').getElementsByTagName('tr');
  for(i=0;i<xRows.length;i++){
    if(xRows[i].className=='editpath'){
      xRows[i].className='favpath';
    }
    if(xRows[i].className=='favinfo') {
      xFav = xRows[i].getElementsByTagName('input')
      xFav[0].className='noedit';
    }
  }
}else{
  window.close();
}
}

function edit_click(x){
if(x.value==' Edit '){
  df = document.forms[0];
  df.add.disabled = true;
  df.save.value = ' Save ';
  df.cancel.value = 'Cancel';
  xRows = document.getElementById('favbody').getElementsByTagName('tr');
  for(i=0;i<xRows.length;i++){
    if(xRows[i].className=='favpath') {
      xRows[i].className='editpath';
    }
    if(xRows[i].className=='favinfo') {
      xFav = xRows[i].getElementsByTagName('input')
      xFav[0].className='edit';
    }
  }
  return(false);
}else{
  return(true);
}
}

</script>
<style>
body,table,td{
  background-color:ButtonFace;
  font-family:Verdana, Arial, Helvetica, sans-serif;
  font-size:12px;
}
.noedit{
  cursor:hand;
  width:100%;
  background:transparent;
  border:0px;
}
.edit{
  width:100%;
}
#pathname {
font-size:xx-small;
}
.favpath{
  width:100%;
  display:none;
}
</style>
</head>
<body><table border=0 width=480 cellpadding=0 cellspacing=0>
<form id=fav name=fav method=POST>
<tr><td width=100% valign=top align=center><fieldset><legend><b>Favourites</b></legend><table width=460 height=240 border=0>
  <tr height=20>
    <th width=60% align=left>&nbsp;Description</th>
    <th width=40% align=left>&nbsp;Where</th>
  </tr>
  <tr height=180>
    <td colspan=2 width=100% valign=top><div style="height:180px; overflow-y: scroll;"><table id=favbody width=100% border=0>
<?php for($i=0;$i<count($fdir);$i++){ ?>
  <tr height=20 class=favinfo>
    <td width=60%><input type=text name=fdes[] value="<?= $fdes[$i] ?>" class=noedit style="display:inline;" onClick="doFavourite(this,'<?= $fdir[$i] ?>')"></td><td width=40% style="overflow:hidden;" nowrap><?= folderin($fdir[$i]) ?></td>
  </tr>
  <tr height=20 class=favpath>
    <td colspan=2 width=100%><input id=pathname type=text name=fdir[] value="<?= $fdir[$i] ?>" class=edit></td>
  </tr>
<?php } ?>
</table></div></td>
  </tr>
  <tr>
    <td colspan=2><center><br>
    <input name=add    type=button value=" Add "   onClick="add_click(this);">
    <input name=save   type=submit value=" Edit "  onClick="return(edit_click(this));">
    <input name=cancel type=reset  value=" Close " onClick="cancel_click(this);">
    </center></td>
  </tr>
</table>
</fieldset></td>
</tr></form></table>
</body>
</html>
