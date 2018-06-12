<?php
use Org\Net\IpLocation;
/*
 * 将含有汉字的数组转化为json
 */
header("Content-Type: text/html; charset=utf-8");

function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
{
    static $recursive_counter = 0;
    if (++$recursive_counter > 1000) {
        die('possible deep recursion attack');
    }
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            arrayRecursive($array[$key], $function, $apply_to_keys_also);
        } else {
            $array[$key] = $function($value);
        }
        if ($apply_to_keys_also && is_string($key)) {
            $new_key = $function($key);
            if ($new_key != $key) {
                $array[$new_key] = $array[$key];
                unset($array[$key]);
            }
        }
    }
    $recursive_counter--;
}

/**************************************************************
 *
*  将数组转换为JSON字符串（兼容中文）
*  @param  array   $array      要转换的数组
*  @return string      转换得到的json字符串
*  @access public
*  @JSON函数，兼容中文，可以将数组转化为json数据格式。
*************************************************************/
function JSON($array) {
    arrayRecursive($array, 'urlencode', true);
    $json = json_encode($array);
    return urldecode($json);
}

/**
 * -默认当前时间
 */
function default_time($time){
	if(!$time){
		return date('Y-m-d',time());
	}else{
		return $time;
	}
}
 /*
  * 获取24位随机码
  */
function get_randcode($num='24'){
    $arr = array(
        'A','B','C','D','E','F','G','H','I','J','K','L','M',
        'N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
        'a','b','c','d','e','f','g','h','i','j','k','l','m',
        'n','o','p','q','r','s','t','u','v','w','x','y','z',
        '0','1','2','3','4','5','6','7','8','9'
    );
    $tmpstr = '';
    $max = count($arr);
    for($i=0;$i<$num;$i++){ //生成24位随机串
         //随机数
        $key = rand(0,$max-1); //'A' -> array['0'];'9' => array[$max-1]
        $tmpstr .= $arr[$key];
    }
    return $tmpstr;
}
/**
* Mac地址加密：生成48位随机字符串
*/
function mac_encode($mac){
    $num = 24;
    $secret_key = get_randcode($num);   //24位随机字符串
    $mac_Arr = str_split(base64_encode($mac));    //24位二维数组
    $secret_key_Arr = str_split($secret_key);
    foreach ($secret_key_Arr as $key => $value){
        $mac_Arr[$key].=$value;
    }
    $mac_encode =  implode('', $mac_Arr);
    //将加密数据插入到数据库
    $data['mac'] = $mac;
    $data['secret_key'] = $secret_key;
    $data['mac_encode_str'] = $mac_encode;
    $data['time'] = time();
    $data['date'] = date("Y-m-d H:i:s",time());
    M('Ap_mac') -> add($data);
    //返回加密字符串
    return $mac_encode;
}
/**
* Mac地址解密：还原mac地址
*/
function mac_decode($mac_encode){
    $mac_Arr = str_split($mac_encode,2);
    $secret_key = M("Ap_mac") -> where(array("mac_encode_str"=>$mac_encode)) -> order("time") -> getField("secret_key");
    $secret_key_Arr =  str_split($secret_key);
    foreach ($secret_key_Arr as $key => $value){
        $mac_Arr[$key] = $mac_Arr[$key][0];         //还原mac地址的base64数组
    }
    $mac_decode = base64_decode(implode("",$mac_Arr));
    return $mac_decode;

}

