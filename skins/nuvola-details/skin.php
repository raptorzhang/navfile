<?php
#===============================
#  PHP Navigator 4.12
#  dated: 04-12-2007
#  edited: 04-12-2007
#  Coded by: Paul Wratt,
#  Melbourne,Australia
#  web: phpnav.isource.net.nz
#  alt: isource.net.nz/phpnav
#===============================

#========================================
#  nuvola - details
#  � 2007 - Paul Wratt
#========================================

// match the "NAME.png" in "groups" to a "gr_NAME[]" array. 'file.png' is for generic, unknown, or misc. file extensions
// 'image' MUST be in "$groups" somewhere, if you want to support thumbnails
// and the VERY LAST ONE is the generic, miscellaneous or unknown file icon (below eg "file.gif")

$groupimgs = ".png"; // code now includes ".png" workaround for IE
$groups   = array('hqx','zip','tar','tgz','jar','class','rpm','bin','dll','msi','cgi','php','jav','js','css','web','txt','msg','doc','odf','pdf','ps','ttf','wav','mid','mp3','ra','swf','mov','mpg','image','file');
$gr_hqx   = array('hqx','sit','sea');
$gr_zip   = array('zip','rar','ace','arj','cab');
$gr_tar   = array('tar','uu','uue','z');
$gr_tgz   = array('tgz','gz','bzip','bz2');
$gr_jar   = array('jar');
$gr_class = array('class','cla');
$gr_rpm   = array('rpm','deb');
$gr_bin   = array('exe','bin','bat','sh','com');
$gr_dll   = array('dll','so');
$gr_dll   = array('msi','run');
$gr_cgi   = array('cgi','pl','cf','cfm','asp','aspx','jsp');
$gr_php   = array('php','php3','php4','php5');
$gr_jav   = array('jav','java');
$gr_js    = array('js','vbs','sql','wsh','wsc','src');
$gr_css   = array('css');
$gr_web   = array('htm','html','shtml','mht');
$gr_txt   = array('txt','text','me');
$gr_msg   = array('msg','eml');
$gr_doc   = array('doc','rtf','rtx','wri','docx');
$gr_odf   = array('odf');
$gr_pdf   = array('pdf');
$gr_ps    = array('ps','eps','ai');
$gr_ttf   = array('ttf','fon');
$gr_wav   = array('wav','snd','au','aif','aifc','aiff');
$gr_mid   = array('mid','midi','kar');
$gr_mp3   = array('mp3','mpga','wma','m3u','ogg');
$gr_ra    = array('ra','ram','rm','rmm','rv','rnx');
$gr_swf   = array('swf','swc','dcr','fla');
$gr_mov   = array('mov','qt','moov','movie');
$gr_mpg   = array('mpg','mpe','mpeg','wmv','mp2','vcs','mxu','avi','divx');
$gr_image = array('bmp','cgm','ief','djv','djvu','gd','gd2','gd2part','gif','iff','ico','jfif','jpg','jpeg','jpc','jpx','jb2','jp2','svg','png','psd','pbm','pnm','ppm','pgm','ras','raw','rgb','tif','tiff','wbm','wbmp','xbm','xbmp','xbitmap','xpm','xpixmap','wxd','pic','xcf','psp','xgm','tga','pix');
$icn_size = array('16','16');
$btn_size = array('16','16');
$tsk_size = array('16','16');

?>