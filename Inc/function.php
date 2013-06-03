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
 * 快速文件数据读取和保存 针对简单类型数据 字符串、数组
 * @param string $name 缓存名称
 * @param mixed $value 缓存值
 * @param string $path 缓存路径
 * @param integer $json 是否以JSON格式返回  
 * @return mixed
 */
function F($name, $value='',$path='./Cache/') {
    static $_cache  = array();
    $filename       = $path . $name . '.php';
    if ('' !== $value) {
        if (is_null($value)) {
            // 删除缓存
            return false !== strpos($name,'*')?array_map("unlink", glob($filename)):unlink($filename);
        } else {
            // 缓存数据
            $dir            =   dirname($filename);
            // 目录不存在则创建
            if (!is_dir($dir))
                mkdir($dir,0755,true);
            $_cache[$name]  =   $value;
            return file_put_contents($filename, '<?php return json_decode(\''.json_encode($value, true).'\',true);?>');
        }
    }
    if (isset($_cache[$name]))
        return $_cache[$name];
    // 获取缓存数据
    if (is_file($filename)) {
        $value          =   include $filename;
        $_cache[$name]  =   $value;
    } else {
        $value          =   false;
    }
    return $value;
}