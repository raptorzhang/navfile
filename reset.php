<?php
#---------------------------
# PHP Navigator 4.12
# dated: 20-07-2007
# edited: 13-08-2007
# Modified by: Paul Wratt,
# Melbourne,Australia
# web: phpnav.isource.net.nz
#---------------------------

@include_once("config.php");
@include_once("functions.php");
delcookies();
setcookies();

$returnto = $_SERVER['QUERY_STRING'];
print "<style>body{background-color:ButtonFace;}</style><script>location.href='$returnto';</script>";
