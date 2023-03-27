<?php
#---------------------------
# PHP Navigator 4.12.20
# dated: 20-07-2007
# edited: 05-06-2011
# Modified by: Paul Wratt
# Melbourne, Australia
# Auckland, New Zealand
# web: phpnav.isource.net.nz
#---------------------------

  $sdir = "skins/";
  if($dh = opendir($sdir)) {
    while (($file = readdir($dh)))  { if($file!="." && $file!=".." && is_dir("skins/$file")) {
	$skins[] = $file;
	if(file_exists($sdir.$file."/file.gif") || file_exists($sdir.$file."/file.png") || file_exists($sdir.$file."/file.jpg")) $icons[] = $file;
	if(file_exists($sdir.$file."/copy.gif") || file_exists($sdir.$file."/copy.png") || file_exists($sdir.$file."/copy.jpg") || file_exists($sdir.$file."/layout.php")) $layouts[] = $file;
	if(file_exists($sdir.$file."/skin.php") || file_exists($sdir.$file."/groups.php")) $groups[] = $file;
	if(file_exists($sdir.$file."/skin.css")) $csses[] = $file;
    }}
  sort($skins);
  sort($icons);
  sort($layouts);
  sort($groups);
  sort($csses);
  array_unshift($icons,'');
  array_unshift($layouts, '');
  array_unshift($groups,'');
  array_unshift($csses,'');

  }

$skins_list = '';
foreach($skins as $skin){
  $skins_list .= '<option value="'.$skin.'">'.$skin;
}

$icons_list = '';
foreach($icons as $icon){
  $icons_list .= '<option value="'.$icon.'">'.$icon;
}

$layouts_list = '';
foreach($layouts as $layout){
  $layouts_list .= '<option value="'.$layout.'">'.$layout;
}

$groups_list = '';
foreach($groups as $group){
  $groups_list .= '<option value="'.$group.'">'.$group;
}

$css_list = '';
foreach($csses as $css){
  $css_list .= '<option value="'.$css.'">'.$css;
}

?><title>Settings - PHP Navigator v4.12</title>
<script defer>

function init()
{
if(document.cookie.indexOf("navphp_cont=no")!=-1)
 {document.conf.context[1].checked=true;}
else {document.conf.context[0].checked=true;}
if(document.cookie.indexOf("navphp_thumb=no")!=-1)
 {document.conf.thumb[1].checked=true;}
else {document.conf.thumb[0].checked=true;}
if(document.cookie.indexOf("navphp_wrap=no")!=-1)
 {document.conf.wrap[1].checked=true;}
else {document.conf.wrap[0].checked=true;}
if(document.cookie.indexOf("navphp_aszip=no")!=-1)
 {document.conf.aszip[1].checked=true;}
else {document.conf.aszip[0].checked=true;}

sk = "navphp_skin="; skl = sk.length; skin = "";
skst = document.cookie.indexOf(sk); skend = document.cookie.indexOf(";",skst);
if(skst!=-1) {skin = document.cookie.substring(skst+skl,skend);}
for(i=0;i<document.conf.skins.length;i++){
  if(document.conf.skins.options[i].value==skin) document.conf.skins.options[i].selected=true;
}

/*
if(document.cookie.indexOf("navphp_groups=no")!=-1)
 {document.conf.groups[1].checked=true;}
else {document.conf.groups[0].checked=true;}
if(document.cookie.indexOf("navphp_layout=no")!=-1)
 {document.conf.layout[1].checked=true;}
else {document.conf.layout[0].checked=true;}
if(document.cookie.indexOf("navphp_colors=no")!=-1)
 {document.conf.colors[1].checked=true;}
else {document.conf.colors[0].checked=true;}
*/
sk = "navphp_icons="; skl = sk.length; skin = "";
skst = document.cookie.indexOf(sk); skend = document.cookie.indexOf(";",skst);
if(skst!=-1) {skin = document.cookie.substring(skst+skl,skend);}
for(i=0;i<document.conf.icons.length;i++){
  if(document.conf.icons.options[i].value==skin) document.conf.icons.options[i].selected=true;
}

sk = "navphp_layout="; skl = sk.length; skin = "";
skst = document.cookie.indexOf(sk); skend = document.cookie.indexOf(";",skst);
if(skst!=-1) {skin = document.cookie.substring(skst+skl,skend);}
for(i=0;i<document.conf.layouts.length;i++){
  if(document.conf.layouts.options[i].value==skin) document.conf.layouts.options[i].selected=true;
}

sk = "navphp_groups="; skl = sk.length; skin = "";
skst = document.cookie.indexOf(sk); skend = document.cookie.indexOf(";",skst);
if(skst!=-1) {skin = document.cookie.substring(skst+skl,skend);}
for(i=0;i<document.conf.groups.length;i++){
  if(document.conf.groups.options[i].value==skin) document.conf.groups.options[i].selected=true;
}

sk = "navphp_colors="; skl = sk.length; skin = "";
skst = document.cookie.indexOf(sk); skend = document.cookie.indexOf(";",skst);
if(skend==-1) {skend = document.cookie.length;}
if(skst!=-1) {skin = document.cookie.substring(skst+skl,skend);}
for(i=0;i<document.conf.colors.length;i++){
  if(document.conf.colors.options[i].value==skin) document.conf.colors.options[i].selected=true;
}

}

function cancel_click(){window.close();}

function save_click()
{
while (document.cookie.indexOf("navphp_thumb=")!=-1){
document.cookie = "navphp_cont=; expires=Fri, 21 Dec 1976 04:31:24 GMT;";
document.cookie = "navphp_thumb=; expires=Fri, 21 Dec 1976 04:31:24 GMT;";
document.cookie = "navphp_wrap=; expires=Fri, 21 Dec 1976 04:31:24 GMT;";
document.cookie = "navphp_aszip=; expires=Fri, 21 Dec 1976 04:31:24 GMT;";
document.cookie = "navphp_skin=; expires=Fri, 21 Dec 1976 04:31:24 GMT;";
document.cookie = "navphp_icons=; expires=Fri, 21 Dec 1976 04:31:24 GMT;";
document.cookie = "navphp_groups=; expires=Fri, 21 Dec 1976 04:31:24 GMT;";
document.cookie = "navphp_layout=; expires=Fri, 21 Dec 1976 04:31:24 GMT;";
document.cookie = "navphp_colors=; expires=Fri, 21 Dec 1976 04:31:24 GMT;";
}
if(document.conf.context[0].checked) cnt="navphp_cont=yes"; else cnt="navphp_cont=no";
if(document.conf.thumb[0].checked) thmb="navphp_thumb=yes"; else thmb="navphp_thumb=no";
if(document.conf.wrap[0].checked) wrp="navphp_wrap=yes"; else wrp="navphp_wrap=no";
if(document.conf.aszip[0].checked) azp="navphp_aszip=yes"; else azp="navphp_aszip=no";
skn="navphp_skin=" + document.conf.skins.options[document.conf.skins.selectedIndex].value;
icn="navphp_icons=" + document.conf.icons.options[document.conf.icons.selectedIndex].value;
lyt="navphp_layout=" + document.conf.layouts.options[document.conf.layouts.selectedIndex].value;
grp="navphp_groups=" + document.conf.groups.options[document.conf.groups.selectedIndex].value;
clr="navphp_colors=" + document.conf.colors.options[document.conf.colors.selectedIndex].value;
//if(document.conf.groups[0].checked) grp="navphp_groups=yes"; else grp="navphp_groups=no";
//if(document.conf.layout[0].checked) lyt="navphp_layout=yes"; else lyt="navphp_layout=no";
//if(document.conf.colors[0].checked) clr="navphp_colors=yes"; else clr="navphp_colors=no";
document.cookie=cnt;
document.cookie=thmb;
document.cookie=wrp;
document.cookie=azp;
document.cookie=skn;
document.cookie=icn;
document.cookie=grp;
document.cookie=lyt;
document.cookie=clr;
}

</script>
<style>
body,table,td,tr{
  background-color:ButtonFace;
  font-family:Verdana, Arial, Helvetica, sans-serif;
  font-size:12px;
}
</style>
<body onload="init();">
<fieldset>
<legend><b>Settings</b></legend>
<table width="100%" border="0" cellpadding="0" cellspacing="0"><form id="conf" name="conf">
  <tr>
    <td>Context Menu </td>
    <td><input name="context" type="radio"> Yes <input name="context" type="radio"> No</td>
  </tr>
  <tr>
    <td>View Thumbnail </td>
    <td><input name="thumb" type="radio"> Yes <input name="thumb" type="radio"> No</td>
  </tr>
  <tr>
    <td>Editor Word Wrap </td>
    <td><input name="wrap" type="radio"> Yes <input name="wrap" type="radio"> No</td>
  </tr>
  <tr>
    <td>Download as Zip </td>
    <td><input name="aszip" type="radio"> Yes <input name="aszip" type="radio"> No</td>
  </tr>
  <tr>
    <td>Current Skin </td>
    <td><select name="skins"><option value="">default<?= $skins_list ?></select></td>
  </tr>
  <tr>
    <td>Use File Icons </td>
    <td><select name="icons"><?= $icons_list ?></select></td>
  </tr>
  <tr>
    <td>Use Layout Icons </td>
    <td><select name="layouts"><?= $layouts_list ?></select></td>
  </tr>
  <tr>
    <td>Use File Groups </td>
    <td><select name="groups"><?= $groups_list ?></select></td>
  </tr>
  <tr>
    <td>Use Color Style </td>
    <td><select name="colors"><?= $css_list ?></select></td>
  </tr>
<!--  <tr>
    <td>Use Groups </td>
    <td><input name="groups" type="radio"> Yes <input name="groups" type="radio"> No</td>
  </tr>
  <tr>
    <td>Use Layout </td>
    <td><input name="layout" type="radio"> Yes <input name="layout" type="radio"> No</td>
  </tr>
  <tr>
    <td>Use Colors </td>
    <td><input name="colors" type="radio"> Yes <input name="colors" type="radio"> No</td>
  </tr> -->
</form></table>
<center><br>
<input name="reset" type="button" value="Default" onclick="location.href='reset.php?settings.php';">
<input name="save" type="button" value=" Save " onclick="save_click()">
<input name="cancel" type="button" id="cancel" value=" Close " onclick="cancel_click()">
</center>
</fieldset>
