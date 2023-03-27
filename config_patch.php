<?php
#---------------------------
# PHP Navigator 4.12.19
# dated: 01-07-2008
# edited: 06-03-2011
# Modified by: Paul Wratt,
# Melbourne, Australia
# Auckland, New Zealand
# web: phpnav.isource.net.nz
#---------------------------

# additions to config.php for new modules and extentions
//$server_root[] = $_SERVER['DOCUMENT_ROOT']; # not valid on every server installation
$server_root[] = $_SERVER['DOCUMENT_ROOT'];
$browser_root[] = "";

/*
# server/browser pairs for View in Browser
# if you use the same phpnav on 2 urls
# or if you have a localhost & production servers
# Note: the order of pairs is important

# examples for linux
$server_root[] = "/home/users/username/www";
$browser_root[] = "~/username/";
$server_root[] = "/var/www/html/www.example.com";
$browser_root[] = "http://www.example.com/";
$server_root[] = "/var/www/html";
$browser_root[] = "";

# examples for windows
$server_root[] = "G:\\webserver\\";
$browser_root[] = "http://manns/";
$server_root[] = "G:\\iis\\wwwroot\\www.example.com";
$browser_root[] = "http://www.example.com/";
$server_root[] = "G:\\iis\\wwwroot\\";
$browser_root[] = "http://localhost/";
*/

/* stops a lot of page injection scripts */
$patch_output = true;
$output_patch = "<div style='display:none'><noscript><!--";