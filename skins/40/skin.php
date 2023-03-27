<?php
#===============================
#  PHP Navigator 4.12
#  dated: 13-11-2007
#  edited: 31-12-2007
#  Coded by: Paul Wratt,
#  Melbourne,Australia
#  web: phpnav.isource.net.nz
#  alt: isource.net.nz/phpnav
#===============================

#========================================
#  PHP Navigator v4.0 - Default Icons
#              � 2007 - Cyril Sebastian
#========================================

// match the "NAME.gif" to "NAME" in "groups" to a "gr_NAME[]" array. 'file.gif' is for generic, unknown, or misc. file extensions
// 'image' MUST be in "$groups" somewhere, if you want to support thumbnails
// and the VERY LAST ONE is the generic, miscellaneous or unknown file icon (below eg "file.gif")

$groupimgs = ".gif"; // ".png" looks BAD in IE with transparencey/alpha channel, use for non-IE only
$groups   = array('html','cgi','zip','bin','image','file');
$gr_web   = array('htm','html','xml','shtml','phtml','mht');
$gr_cgi   = array('cgi','pl','py','php3','cf','asp','aspx','jsp','txt','php','inc','css','js','vbs','sql','pyw');
$gr_zip   = array('zip','arj','rar','gz','tar','tgz','bz2');
$gr_bin   = array('exe','bin','bat','sh','com','run','pyc','pyd','dll');
$gr_image = array('bmp','gd','gd2','gd2part','gif','iff','jpg','jpeg','jpc','jpx','jb2','jp2','swf','swc','svg','png','psd','tif','tiff','wbm','wbmp','xbm','xbmp','xbitmap','xpm','xpixmap');
$icn_size = array('32','32');
$btn_size = array('24','24');
$tsk_size = array('16','16');

// see "SAMPLE" for complete example (@ http://isource.net.nz/phpnav/ )
?>