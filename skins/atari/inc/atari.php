<?php 

#------------- NEW FUNCTIONS ------------
# v5 prototype functions
#
# www_page_open()  - start data output encoding to browser
# www_page_close() - end data output encoding, apply compression
# folderin(dir)    - return "end_folder in end_folder-1" from full path
#

function www_page_open(){
  global $compress, $encoding, $deflate, $gzip;
  $encoding = 'none';
  if($compress){
    $deflate = false;
    $gzip = false;
    $en = $_SERVER['HTTP_ACCEPT_ENCODING'];
    ob_start();
    if(strstr($en,"gzip")){
      $gzip = true;
      header("Content-Encoding: gzip");	// start buffering for gzip encoding
      $encoding = 'gzip';
    }elseif(strstr($en,"deflate")){
      $deflate = true;
      header("Content-Encoding: deflate");	// start buffering for deflate encoding
      $encoding = 'deflate';
    }
  }
}

function www_page_close(){
  global $compress, $deflate, $gzip;
  if($compress){
    $data = ob_get_clean();
    if($deflate){
      echo gzdeflate($data);
    }elseif($gzip){
      echo gzencode($data,9);
    }
  }
}

function setWindow(name,id,x,y,w,h,minW,minH,V=true,H=true,scrollarea=null,title,info,display){

}

function drawWindow(){

}

function drawModal(){

}

?>
