<?php
require('inc/config.php');
require('inc/function.php');
header("Content-type:text/html;charset=utf-8");
$lrc = isset($_GET['lrc']) ? urldecode($_GET['lrc']) : 0;
if($lrc){
    $file_name = md5($lrc);
    if(!F($file_name) || !$cache){
        $file = file_get_contents($lrc);
        $file = str_replace(array("\"","\n","\r"), array("'","<br>",""), $file);
        $file = preg_replace('/\[ti:(.*?)\[00/i','[00', $file,1);
        $encode = mb_detect_encoding($file,array('ASCII','GB2312','GBK','UTF-8'));
        if($encode != 'UTF-8'){
            $file = iconv($encode, 'UTF-8', $file);
        }
        F($file_name,$file);
        echo $file;
    }else{
        echo F($file_name);
    }
}else{
    if(!F('list') || !$cache){
        $file = file_get_contents($listurl);
        $trackList = json_decode(json_encode((array)(simplexml_load_file($listurl,null,LIBXML_NOCDATA)->trackList)),1);
        $track=$trackList['track'];
        $data=array();
        for($i=0;$i<sizeof($track);$i++){
            $data[$i]=array();
            $data[$i]['id']=$track[$i]['song_id'];
            $data[$i]['title']=$track[$i]['title'];
            $data[$i]['mp3']=getSongUrl($track[$i]['location']);
            $data[$i]['poster']=$track[$i]['pic'];
            $data[$i]['lyric']=urlencode($track[$i]['lyric']);
            $data[$i]['artist'] = $track[$i]['artist'];
        }
        F('list',$data);
        echo json_encode($data);
    }else{
        echo json_encode(F('list'));
    }
}