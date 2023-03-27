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
#  PHP Navigator v4.4 - Default Icons
#            (C) 2010 - Cyril Sebastian
#========================================

// match the "NAME.gif" in "groups" to a "gr_NAME[]" array. 'file.gif' is for generic, unknown, or misc. file extensions

$groupimgs = ".gif"; // ".png" looks BAD in IE with transparencey/alpha channel
$groups   = array('html','cgi','zip','bin','doc','js','css','php','image','file');
$gr_web   = array('htm','html','xml','shtml','mht');
$gr_cgi   = array('cgi','pl','php3','sql','txt','cf','asp','aspx','jsp');
$gr_zip   = array('zip','rar','gz','tar','tgz','bz2');
$gr_bin   = array('exe','bin','bat','sh','com');
$gr_doc   = array('doc','pdf','ps','odf','docx');
$gr_js    = array('js','vbs');
$gr_css   = array('css');
$gr_php   = array('php');
$gr_image = array('gif','jpg','jpeg','png','bmp','psd','svg');
$icn_size = array('32','32');
$btn_size = array('24','24');
$tsk_size = array('16','16');

// the above is not complete, just ripped directly from v4.2's fileicon() function code
// see "SAMPLE" for complete example (@ http://isource.net.nz/phpnav/ )
?>