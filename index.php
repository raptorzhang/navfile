<?php
<<<<<<< HEAD
#---------------------------
#---------------------------
=======
>>>>>>> 6969aca9e776f59a63937d3b6bbce31e10d28d60

@include_once("config.php");
@include_once("functions.php");

setcookies();

print "<script>";
if ($startpage=='') print "window.location.href='windows.php';";
elseif (file_exists("$startpage.php")) print "window.location.href='$startpage.php';";
elseif (file_exists("windows.php")) print "window.location.href='windows.php';";
print "</script>";
