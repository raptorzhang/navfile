<?php
#===============================
#  PHP Navigator 4.12.20
#  dated: 13-11-2007
#  edited: 05-06-2011
#  Coded by: Paul Wratt
#  Melbourne, Australia
#  Auckland, New Zealand
#  web: phpnav.isource.net.nz
#  alt: isource.net.nz/phpnav
#===============================

function image_exists($skin,$file){
global $realdir,$groupimgs;
if(file_exists($realdir."skins/".$skin.$file.$groupimgs)) return true;
if(file_exists($realdir."skins/".$skin.$file.".gif")) return true;
if(file_exists($realdir."skins/".$skin.$file.".png")) return true;
if(file_exists($realdir."skins/".$skin.$file.".jpg")) return true;
return false;
}

function skin_exists($skin,$file='skin.css'){
global $realdir;
if(file_exists($realdir."skins/".$skin.$file)) return true;
return false;
}

if ($colors!=''){
  if(skin_exists($colors))
    $overridecss = "skins/{$colors}skin.css";
}

if(skin_exists($skin))
  $skincss = "skins/{$skin}skin.css";
else
  $skincss = "inc/windows.css";


if ($group!=''){
  if(file_exists("{$realdir}skins/{$group}skin.php"))
   @include_once("{$realdir}skins/{$group}skin.php");
  if(file_exists("{$realdir}skins/{$group}groups.php"))
   @include_once("{$realdir}skins/{$group}groups.php");
}

if((!$groups || !is_array($groups) || @count($groups)==0)) {

#========================================================================
#  default groups, over-ride with "skins/_skin_/skin.php" & $use_groups
#========================================================================

// match the "NAME.gif" to "NAME" in "groups" to a "gr_NAME[]" array. "file.gif" is for generic, unknown, or misc. file extensions
// 'image' MUST be in "$groups" somewhere, if you want to support thumbnails
// and the VERY LAST ONE is the generic, miscellaneous or unknown file icon (below eg "file.gif")

if (!$groupimgs) $groupimgs = ".gif"; // ".png" looks BAD in IE with transparencey/alpha channel, use for non-IE only
$groups   = array('html','cgi','zip','bin','doc','txt','js','css','php','image','file');
$gr_web   = array('htm','html','xml','chtml','shtml','phtml','mht');
$gr_cgi   = array('cgi','pl','py','php3','cf','cfm','asp','aspx','jsp');
$gr_zip   = array('zip','rar','gz','tar','tgz','bz2');
$gr_bin   = array('exe','bin','bat','sh','com','run');
$gr_doc   = array('doc','pdf','ps','odf','docx','wri','rtf');
$gr_js    = array('js','vbs','sql','wsh','wsc');
$gr_txt   = array('txt','text');
$gr_css   = array('css');
$gr_php   = array('php','phps','php3','php4','php5','php7');
$gr_image = array('bmp','gd','gd2','gd2part','gif','iff','jpg','jpeg','jpc','jpx','jb2','jp2','swf','swc','svg','png','psd','pbm','ppm','tif','tiff','wbm','wbmp','xcf','xbm','xbmp','xbitmap','xpm','xpixmap');
if (!$icn_size || !$use_groups) { $no_icn = true; $icn_size = array('32','32'); }
if (!$btn_size || (!$use_groups && !$use_layout)) { $no_btn = true; $btn_size = array('24','24'); }
if (!$tsk_size || (!$use_groups && !$use_layout)) { $no_tsk = true; $tsk_size = array('16','16'); }

// see "SAMPLE" for complete example (@ http://isource.net.nz/phpnav/ )
}

if (!$btn_size || !$use_layout) { $no_btn = true; $btn_size = array('24','24'); }
if (!$tsk_size || !$use_layout) { $no_tsk = true; $tsk_size = array('16','16'); }

  if(image_exists($layout,'copy'))
    $skinicons = $layout;
  else
    $skinicons = $skin;
  if(!image_exists($skinicons,'copy'))
    $skinicons = "";

  if(image_exists($icons,'file'))
    $fileicons = $icons;
  else
    $fileicons = $skin;
  if(!image_exists($fileicons,'file'))
    $fileicons = "";