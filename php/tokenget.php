<?php
define("ACC_TOKEN_KEYNAME", "token");
$mem = new Memcached();
$token = $mem->get(ACC_TOKEN_KEYNAME);
if (empty($token))
{
    $appId = "";
    $appsecret = "";
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appId}&secret={$appsecret}";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $a = curl_exec($ch);
    ##error_log("curl_exec = ".$a, 0);
    $strjson = json_decode($a);
    $access_token = $strjson->access_token;
    $mem->set(ACC_TOKEN_KEYNAME, $access_token, 7200);
    $token = $mem->get(ACC_TOKEN_KEYNAME);
    curl_close($ch);
}
##error_log("ATK = ".ACC_TOKEN_KEYNAME, 0);
##error_log("token = ".$token, 0);
echo $token;
?>