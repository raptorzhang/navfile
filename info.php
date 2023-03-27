<font face="Tahoma,sans-serif">
<?php
$path = realpath(".");
$path_type="realpath";
echo $path_type."=".$path."<br>";

$path_type="CONTEXT_DOCUMENT_ROOT";
if (isset($_SERVER[$path_type])){
  $path = $_SERVER[$path_type];
  echo $path_type."=".$path."<br>";
};

$path_type="PHP_DOCUMENT_ROOT";
if (isset($_SERVER[$path_type])){
  $path = $_SERVER[$path_type];
  echo $path_type."=".$path."<br>";
};

$path_type="DOCUMENT_ROOT";
if (isset($_SERVER[$path_type])){
  $path = $_SERVER[$path_type];
  echo $path_type."=".$path."<br>";
};

if (isset($_SERVER['SERVER_ADDR'])){
  echo "SERVER_ADDR=".$_SERVER['SERVER_ADDR']."<br>";
}

if (isset($_SERVER['REMOTE_ADDR'])){
  echo "REMOTE_ADDR=".$_SERVER['REMOTE_ADDR']."<br>";
}