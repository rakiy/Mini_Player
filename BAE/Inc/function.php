<?php
/**
 *
 * 虾米地址解析
 * @param string $name 加密地址
 * @param mixed $value 缓存值
 * @return mixed
 *
 */
function getSongUrl($location){
    $loc_2 = (int)substr($location, 0, 1);
    $loc_3 = substr($location, 1);
    $loc_4 = floor(strlen($loc_3) / $loc_2);
    $loc_5 = strlen($loc_3) % $loc_2;
    $loc_6 = array();
    $loc_7 = 0;
    $loc_8 = '';
    $loc_9 = '';
    $loc_10 = '';
    while ($loc_7 < $loc_5) {
        $loc_6[$loc_7] = substr($loc_3, ($loc_4 + 1) * $loc_7, $loc_4 + 1);
        $loc_7++;
    }
    $loc_7 = $loc_5;
    while ($loc_7 < $loc_2) {
        $loc_6[$loc_7] = substr($loc_3, $loc_4 * ($loc_7 - $loc_5) + ($loc_4 + 1) * $loc_5, $loc_4);
        $loc_7++;
    }
    $loc_7 = 0;
    while ($loc_7 < strlen($loc_6[0])) {
        $loc_10 = 0;
        while ($loc_10 < count($loc_6)) {
            @$loc_8 .= $loc_6[$loc_10][$loc_7];
            $loc_10++;
        }
        $loc_7++;
    }
    $loc_9 = str_replace('^', 0, urldecode($loc_8));
    
    return $loc_9;
}
/**
 * 正则获取XML并解析为数组
 * @param string $xml 缓存名称
 * @return mixed
 */
function Xml2Arr($xml) {
    preg_match_all( "/\<track\>(.*?)\<\/track\>/s", $xml, $track);
    $i = 0;
    foreach($track[1] as $k=>$v ){ 
        preg_match( "/\<song_id\>(.*?)\<\/song_id\>/", $v, $id); 
        preg_match( "/\<location\>(.*?)\<\/location\>/", $v, $mp3); 
        preg_match( "/\<pic\>(.*?)\<\/pic\>/", $v, $poster);
        preg_match( "/\<lyric\>(.*?)\<\/lyric\>/", $v, $lyric); 
        preg_match( "/\<artist\><!\[CDATA\[(.*?)\]\]>\<\/artist\>/", $v, $artist);
        preg_match( "/\<title\><!\[CDATA\[(.*?)\]\]>\<\/title\>/", $v, $title);
        $tracks[$i]['id']       =   $id[1];
        $tracks[$i]['title']    =   $title[1];
        $tracks[$i]['mp3']      =   getSongUrl($mp3[1]);
        $tracks[$i]['poster']   =   $poster[1];
        $tracks[$i]['lyric']    =   urlencode($lyric[1]);
        $tracks[$i]['artist']   =   isset($artist[1]) ? $artist[1] : '';
        $i++;
    }
    return $tracks;
}
