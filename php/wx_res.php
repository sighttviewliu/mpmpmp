<?php  
    define("TOKEN", "michaellaoliu"); //TOKEN值  
    $wechatObj = new wechat();  
    ##$wechatObj->valid();  
    $wechatObj->responseMSG();

    class wechat {  

        public function valid() {  
            $echoStr = $_GET["echostr"];  

            if($this->checkSignature()){  
                echo $echoStr;  
                exit;  
            }  
        }  
      
        private function checkSignature() {  

            $signature = $_GET["signature"];  
            $timestamp = $_GET["timestamp"];  
            $nonce = $_GET["nonce"];  
            $token = TOKEN;  
            $tmpArr = array($token, $timestamp, $nonce);  
            sort($tmpArr);  
            $tmpStr = implode( $tmpArr );  
            $tmpStr = sha1( $tmpStr );  
            if( $tmpStr == $signature ) {  
                return true;  
            } else {  
                return false;  
            }  
        }  

        public function responseMSG() {

            ##$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
            $postStr = file_get_contents('php://input');
            ##print("liulijin \n");

            if (!empty($postStr)) {

                libxml_disable_entity_loader(true);
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $msgType = $postObj->MsgType;
                $event = $postObj->Event;
                $keyword = trim($postObj->Content);
                $time = time();

                $textTpl = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                <FuncFlag>0</FuncFlag>
                            </xml>";

                switch ($msgType)
                {
                    case "event":
                    if ($event == "subscribe")
                    {
                        $contentStr = "------ \n press 1 \n press 2 \n press 3";
                    }
                    break;

                    case "text":
                    switch ($keyword)
                    {
                        case "1":
                        $contentStr = "123";
                        break;

                        case "2":
                        $contentStr = "ABC";
                        break;

                        case "3":
                        $contentStr = "xyz";
                        break;

                        default:
                        $contentStr = "nothing found!";
                        break;
                    }
                    break;

                    case "image":
                    $picUrl = $postObj->picUrl;
                    $contentStr = "image url ".$picUrl;
                    break;

                    case "voice":
                    $format = $postObj->Format;
                    $recog = $postObj->Recognition;
                    $contentStr = "voice format is ".$format."conetnt is ".$recog;
                    break;

                    case "video":
                    $contentStr = "video video...";
                    break;

                    case "location":
                    $locationx = $postObj->location_X;
                    $locationy = $postObj->location_Y;
                    $contentStr = "map position x is ".$locationx." y is ".$locationy;
                    break;

                    case "link":
                    $title = $postObj->Title;
                    $contentStr = "link title is ".$title;
                    break;
                }
                
                // $msgType = "text";
                // $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                // echo $resultStr;

                if ($keyword == "aaa")
                {
                    $musicTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Music>
                    <Title><![CDATA[%s]]></Title>
                    <Description><![CDATA[%s]]></Description>
                    <MusicUrl><![CDATA[%s]]></MusicUrl>
                    <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
                    </Music>
                    </xml>";

                    $msgType = "music";
                    $title = "aaa";
                    $description = "bbb";
                    $url = "http://url.mcvmc.com/qq.php/221569632.mp3";
                    $resultStr = sprintf($musicTpl, $fromUsername, $toUsername, $time, 
                    $msgType, $title, $description, $url, $url);
                    echo $resultStr;
                }
                else {
                    echo "nothing to do!";
                }
                
            }
            else {
                echo "";
                exit;
            }
        }
    }  
?>  