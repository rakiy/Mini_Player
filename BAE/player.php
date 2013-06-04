<?php
require('Inc/config.php');
require('Inc/function.php');
header("Content-type:text/html;charset=utf-8");
//if(strpos($_SERVER['SERVER_NAME'], "mplayer.duapp.com") === false) exit("Access Denied");
$lrc = isset($_GET['lrc']) ? urldecode($_GET['lrc']) : 0;
if($lrc){
    $file_name = md5($lrc);
    $file = file_get_contents($lrc);
    $file = str_replace(array("\"","\n","\r"), array("'","<br>",""), $file);
    $file = preg_replace('/\[ti:(.*?)\[00/i','[00', $file,1);
    $encode = mb_detect_encoding($file,array('ASCII','GB2312','GBK','UTF-8'));
    if($encode != 'UTF-8'){
        $file = iconv($encode, 'UTF-8', $file);
    }
    echo $file;
}else{
    $file = file_get_contents($listurl);
    $trackList = Xml2Arr($file);
    echo json_encode($trackList);
}