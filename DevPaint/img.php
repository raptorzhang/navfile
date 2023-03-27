<?php

$dir=urldecode($_REQUEST['dir']);
$file=urldecode($_REQUEST['file']);

if(strtolower(strrchr($file,"."))=='.bmp')
  include_once("lib/fromBMP.php");

  $img = "";
  $imgObj = "";
  $guides ='true';
  if($guides=='true') {
    $bdr = ' border:1px dotted grey;';
    $cs = 1;
  }else{
    $bdr = '';
    $cs = 0;
  }
  $zm = 2;

$src_file = $dir."/".$file;
if(is_file($src_file)){
  $imginfo = @getimagesize($src_file);
if($imginfo){
  $srcX = $imginfo[0];
  $srcY = $imginfo[1];
  $type = $imginfo[2];

  switch($type)
    {
        case 1:
            if(function_exists('imagecreatefromgif'))
                $srcImage = imagecreatefromgif($src_file);
                $imgType = 'gif';
            break;
        case 2:
            if(function_exists('imagecreatefromjpeg'))
                $srcImage = imagecreatefromjpeg($src_file);
                $imgType = 'jpg';
                $comment = exif_read_data($src_file);
            break;
        case 3:
            if(function_exists('imagecreatefrompng'))
                $srcImage = imagecreatefrompng($src_file);
                $imgType = 'png';
            break;
        case 6:
            if(function_exists('imagecreatefrombmp'))
                $srcImage = imagecreatefrombmp($src_file);
                $imgType = 'bmp';
            break;
        case 15:
            if(function_exists('imagecreatefromwbmp'))
                $srcImage = imagecreatefromwbmp($src_file);
                $imgType = 'wbmp';
            break;
        case 16:
            if(function_exists('imagecreatefromxbm'))
                $srcImage = imagecreatefromxbm($src_file);
                $imgType = 'xbm';
            break;
        case 17:
            if(function_exists('imagecreatefromxpm'))
                $srcImage = imagecreatefromxpm($src_file);
                $imgType = 'xpm';
            break;
    }
  if(!$srcImage) $srcImage = @imagecreatefromstring(file_get_contents($src_file));
  if($srcImage) {
    $comment = '';
    $palette = $imginfo['bits']."bits";
    $tc = imageistruecolor($srcImage);
    $typ = ($tc)?'truecolor':'palette';
    $total = imagecolorstotal($srcImage);
    $trns = imagecolortransparent($srcImage);
    $trans = ($trns==-1)?'false':'true';
//    list($r,$g,$b,$alpha) = imagecolorsforindex($srcImage,$trans);
    if($trans=='true') $colors = imagecolorsforindex($srcImage,$trns);
    else $colors = imagecolorsforindex($srcImage,0);
    $bgcolor = "rgb(".$colors['red'].",".$colors['green'].",".$colors['blue'].")";
    $w = ($srcX*($zm+$cs))+4;
    $h = ($srcY*($zm+$cs))+4;
    echo "<table border=0 bgcolor=#CCCCCC width=$w cellpadding=0 cellspacing=$cs style=\"$bdr\">\n";
    for($y=0;$y<$srcY;$y++){
      echo "<tr>";
      for($x=0;$x<$srcX;$x++){
        $color = imagecolorat($srcImage,$x,$y);
//        list($r,$g,$b,$a) = imagecolorsforindex($srcImage,$color);
        $colors = imagecolorsforindex($srcImage,$color);
        echo "<td width=$zm height=$zm onMouseDown=\"parent.doTool(this);\" colno=$color style=\"background-color:rgb(".$colors['red'].",".$colors['green'].",".$colors['blue'].");\" alpha=".$colors['alpha']." >";
      }
      echo "</tr>\n";
    }
    imagedestroy($srcImage);
    echo "</table>\n";
    $imgUpdate = "updateInfoRow(DevPaint0);\n";
    $wh = " width:$w; height:$h; border-color:red;";
  }
}
}

if(!$srcX) {
  $srcX = 100;
  $srcY = 100;
  $imgType = 'gif';
//  $bgcolor = '#FF00FF';
  $bgcolor = 'rgb(255,0,255)';
  $trans = 'true';
  $total = 0;
  $palette = '32bit';
  $comment = 'created by AtariPainter';
  $typ = 'truecolor';
}

  $imgObj .= "DevPaint0.xW = $srcX;\n";
  $imgObj .= "DevPaint0.yH = $srcY;\n";
  $imgObj .= "DevPaint0.guides = $guides;\n";
  $imgObj .= "DevPaint0.zoom = $zm;\n";
  $imgObj .= "DevPaint0.backgroundColor = '$bgcolor';\n";
  $imgObj .= "DevPaint0.transparent = $trans;\n";
  $imgObj .= "DevPaint0.typ = '$typ';\n";
  $imgObj .= "DevPaint0.colorTable = '';\n";
  $imgObj .= "DevPaint0.colorsTotal = '$total';\n";
  $imgObj .= "DevPaint0.palette = '$palette';\n";
  $imgObj .= "DevPaint0.paletteTable = '';\n";
  $imgObj .= "DevPaint0.format = '$imgType';\n";
  $imgObj .= "DevPaint0.compression = '';\n";
  $imgObj .= "DevPaint0.comment = '$comment';\n";
  $imgObj .= $imgUpdate;