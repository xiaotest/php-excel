<?php

class Helpfunction{
    //json输出格式
    public static  function printf_info($array)
    {
        ob_clean();
        if(version_compare(PHP_VERSION,'5.4.0','<')){
            $str = json_encode($array);
            $str = preg_replace_callback("#\\\u([0-9a-f]{4})#i",function($matchs){
                return iconv('UCS-2BE', 'UTF-8', pack('H4', $matchs[1]));
            },$str);
            echo  $str;
        }else{
            echo  json_encode($array, JSON_UNESCAPED_UNICODE);
        }
    }


//腾讯地图api  根据地址获取经纬度

    public static   function getLocation($address){
        $ak = 'KMKBZ-5AYRX-W7X4U-TBGUI-WLBAQ-C2FJ4';//百度ak密钥
        $url = "https://apis.map.qq.com/ws/geocoder/v1/?address='$address'&key=$ak";
        $result = file_get_contents($url);
        if($result)
        {
            $data = array();
            $res= json_decode($result,true);
            if ($res['status'] == 0) {
                $results = $res['result'];
                $data['jd'] = $results['location']['lng'];
                $data['wd'] = $results['location']['lat'];
            }
            return $data;
        }else{
            return 0;
        }
    }


    public static   function _get($str)
    {
        $val = !empty($_POST[$str]) ? $_POST[$str] : null;
        return $val;
    }

    /**
     * 获取客户端IP地址
     * @param integer   $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @param boolean   $adv 是否进行高级模式获取（有可能被伪装）
     * @return mixed
     */
    public static    function ip(){
        static $ip = NULL;
        if ( $ip !== NULL ) return $ip;
        if ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) ){
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown',$arr);
            if(false !== $pos){
                unset($arr[$pos]);
            }
            $ip = trim($arr[0]);
        }elseif( isset($_SERVER['HTTP_CLIENT_IP']) ){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif( isset($_SERVER['REMOTE_ADDR']) ){
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u",ip2long($ip));
        $ip   = $long ? $ip : 'unknow';
        return $ip;
    }

//创建随机数  微信签名用的
    public static   function createNonceStr($length = 32) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

//输入二维码
    public static function generateQRfromGoogle($chl,$widhtHeight ='150',$EC_level='L',$margin='0')

    {
        $chl = urlencode($chl);

        echo '<img src="http://chart.apis.google.com/chart?chs='.$widhtHeight.'x'.$widhtHeight.' 

    &cht=qr&chld='.$EC_level.'|'.$margin.'&chl='.$chl.'" alt="QR code" widhtHeight="'.$widhtHeight.' 

    " widhtHeight="'.$widhtHeight.'"/>';

    }

//格式换输出
    public static  function  dump($arr){
        echo "<pre/>";
        var_dump($arr);
    }

    /**
     * 发送post请求
     * @param string $url
     * @return bool|mixed
     */
    public static  function request_post($url = '', $param = '')
    {
        if (empty($url) || empty($param)) {
            return false;
        }
        $postUrl = $url;
        $curlPost = $param;
        $ch = curl_init(); //初始化curl
        curl_setopt($ch, CURLOPT_URL, $postUrl); //抓取指定网页
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);//严格校验2
        curl_setopt($ch, CURLOPT_HEADER, 0); //设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1); //post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10000);
        $data = curl_exec($ch); //运行curl
        curl_close($ch);
        return $data;
    }


//自定义填充函数  重复填充空格
    public static   function str_padz($input,$pad_length ,$pad_string , $pad_type){
        $strlen = (strlen($input) + mb_strlen($input,'UTF8')) / 2;;
        if($strlen < $pad_length){
            $difference = $pad_length - $strlen;
            switch ($pad_type) {
                case STR_PAD_RIGHT:
                    return $input . str_repeat($pad_string, $difference);
                    break;
                case STR_PAD_LEFT:
                    return str_repeat($pad_string, $difference) . $input;
                    break;
                default:
                    $left = $difference / 2;
                    $right = $difference - $left;
                    return str_repeat($pad_string, $left) . $input . str_repeat($pad_string, $right);
                    break;
            }
        }else{
            return $input;
        }
    }
    /**
     * 发送get请求
     * @param string $url
     * @return bool|mixed
     */
    public static   function request_get($url = '')
    {
        if (empty($url)) {
            return false;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);//严格校验2
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

//提交json 格式
    public static  function http_post_json($url, $jsonStr)
    {
        if (empty($url) || empty($jsonStr)) {
            return false;
        }
        $headers = array('Content-Type: application/json; charset=utf-8');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }


//form 表单     //模拟表单 key=>value    $headers  http_build_query($curlPost)
    public static    function httprequest_post($url = '', $param = '')
    {
        if (empty($url) || empty($param)) {
            return false;
        }
        $headers = array('Content-Type: application/x-www-form-urlencoded');
        $postUrl = $url;
        $curlPost = $param;
        $ch = curl_init(); //初始化curl
        curl_setopt($ch, CURLOPT_URL, $postUrl); //抓取指定网页
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);//严格校验2
        curl_setopt($ch, CURLOPT_HEADER, 0); //设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1); //post提交方式
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($curlPost));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10000);
        $data = curl_exec($ch); //运行curl
        curl_close($ch);
        return $data;
    }

    public static   function getSign($params)
    {
        ksort($params, SORT_STRING);
        $unSignParaString =formatQueryParaMap($params, false);
        $signStr = strtoupper(md5($unSignParaString));
        return $signStr;
    }

    public static  function formatQueryParaMap($paraMap, $urlEncode = false)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if (null != $v && "null" != $v) {
                if ($urlEncode) {
                    $v = urlencode($v);
                }
                $buff .= $k . "=" . $v . "&";
            }
        }
        $reqPar = '';
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }

    /**
     * @param $string
     * @return int|string
     *   解密
     */

    public static function getcrc($string)
    {
        @$string = pack('H*', $string);
        $crc = 0xFFFF;
        for ($x = 0; $x < strlen($string); $x++) {
            $crc = $crc ^ ord($string[$x]);
            for ($y = 0; $y < 8; $y++) {
                if (($crc & 0x0001) == 0x0001) {
                    $crc = (($crc >> 1) ^ 0xA001);
                } else {
                    $crc = $crc >> 1;
                }
            }
        }
        $crc = sprintf('%02x%02x', $crc % 256, floor($crc / 256));
        $crc = strtolower($crc);
        return $crc;
    }
}