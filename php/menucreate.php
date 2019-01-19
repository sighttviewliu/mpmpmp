<?php
##require_once "token.php";
##we use the hard code but not auto get
##error_log("start -------------------------------", 0);

$token = "";

$post = '



';

##error_log("menu.php token = ".$token, 0);

$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$token}";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
#curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
##curl_setopt($ch, CURLOPT_USERAGENT, '');
##curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
#curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
#curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$r = curl_exec($ch);

if (curl_errno($ch)) {
    error_log("Err = ".curl_error($ch));
}

curl_close($ch);
var_dump($r);
##error_log("end -------------------------------", 0);
?>