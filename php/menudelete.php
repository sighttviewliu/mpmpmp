<?php
$token = "";
$url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token={$token}";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
#curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
#curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
#curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
##curl_setopt($ch, CURLOPT_USERAGENT, '');
##curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
#curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
#curl_setopt($ch, CURLOPT_POST, 1);
#curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
#curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$r = curl_exec($ch);
$jsonobj = json_decode($r);

// if (curl_errno($ch)) {
//     error_log("Err = ".curl_error($ch));
// }

curl_close($ch);
#var_dump($r);
echo $r;
?>