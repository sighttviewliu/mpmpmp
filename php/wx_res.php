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
            //huo de nei rong
            $postStr = file_get_contents('php://input');
            ##print("liulijin \n");

            if (!empty($postStr)) {

                libxml_disable_entity_loader(true);
                ##xml parser | type = SimpleXMLElement
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $msgType = $postObj->MsgType;
                $event = $postObj->Event;
                $keyword = trim($postObj->Content);
                $time = time();

                // if (!empty($keyword))
                // {
                //     if ($keyword==1) {
                //         $singlenews = "<xml>
                //         <ToUserName><![CDATA[%s]]></ToUserName>
                //         <FromUserName><![CDATA[%s]]></FromUserName>
                //         <CreateTime>%s</CreateTime>
                //         <MsgType><![CDATA[%s]]></MsgType>
                //         <ArticleCount>1</ArticleCount>
                //         <Articles>
                //         <item>
                //         <Title><![CDATA[%s]]></Title>
                //         <Description><![CDATA[%s]]></Description>
                //         <PicUrl><![CDATA[%s]]></PicUrl>
                //         <Url><![CDATA[%s]]></Url>
                //         </item>
                //         </Articles>
                //     </xml>";
                //     $msgType = "news";
                //     $title = "1111111111111111111111111";
                //     $description = "22222222222222222222222";
                //     $picurl = "http://a.cphotos.bdimg.com/timg?image&quality=100&size=b4000_4000&sec=1547450899&di=c1756f6121b4107bb2a738bb7573b6e7&src=http://m.tuniucdn.com/filebroker/cdn/olb/91/25/9125f24abe6baab9173fb34320759ced_w640_h320_c1_t0.jpg";
                //     $url = "http://sports.sina.com.cn/basketball/nba/2019-01-14/doc-ihqfskcn6909004.shtml";
                //     $resultStr = sprintf($singlenews, $fromUsername, $toUsername, $time, 
                //     $msgType, $title, $description, $picurl, $url);
                //     echo $resultStr;
                //     }
                //     else if ($keyword==3) {
                //         //from 20181012, multinews could not be supported
                //         $multinews = "<xml>
                //         <ToUserName>{$fromUsername}</ToUserName>
                //         <FromUserName>{$toUsername}</FromUserName>
                //         <CreateTime>{$time}</CreateTime>
                //         <MsgType><![CDATA[news]]></MsgType>
                //         <ArticleCount>2</ArticleCount>
                //         <Articles>
                //         <item>
                //         <Title><![CDATA[I have a child]]></Title>
                //         <Description><![CDATA[1]]></Description>
                //         <PicUrl><![CDATA[http://a.cphotos.bdimg.com/timg?image&quality=100&size=b4000_4000&sec=1547450899&di=c1756f6121b4107bb2a738bb7573b6e7&src=http://m.tuniucdn.com/filebroker/cdn/olb/91/25/9125f24abe6baab9173fb34320759ced_w640_h320_c1_t0.jpg]]></PicUrl>
                //         <Url><![CDATA[http://sports.sina.com.cn/basketball/nba/2019-01-14/doc-ihqfskcn6909004.shtml]]></Url>
                //         </item>
                //         <item>
                //         <Title><![CDATA[you are a good boy]]></Title>
                //         <Description><![CDATA[2]]></Description>
                //         <PicUrl><![CDATA[http://a.cphotos.bdimg.com/timg?image&quality=100&size=b4000_4000&sec=1547450899&di=c1756f6121b4107bb2a738bb7573b6e7&src=http://m.tuniucdn.com/filebroker/cdn/olb/91/25/9125f24abe6baab9173fb34320759ced_w640_h320_c1_t0.jpg]]></PicUrl>
                //         <Url><![CDATA[https://zhidao.baidu.com/question/391622899228841685.html]]></Url>
                //         </item>
                //         </Articles>
                //     </xml>";
                //     echo $multinews;
                //     }
                //     else {
                //         echo "input something...";
                //     }
                // }


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
                    $title = urldecode("腾讯");
                    $content = urlencode("腾讯");
                    $contentStr = "<a href=\"http://api.map.baidu.com/marker?location=40.047669,116.313082&title={$title}&content={$content}&output=html&src=webapp.baidu.openAPIdemo\">linklinklin</a>";
                    // $locationx = $postObj->location_X;
                    // $locationy = $postObj->location_Y;
                    // $contentStr = "map position x is ".$locationx." y is ".$locationy;
                    break;

                    case "link":
                    $title = $postObj->Title;
                    $contentStr = "link title is ".$title;
                    break;
                }
                
                $msgType = "text";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;

                // if ($keyword == "aaa")
                // {
                //     $musicTpl = "<xml>
                //     <ToUserName><![CDATA[%s]]></ToUserName>
                //     <FromUserName><![CDATA[%s]]></FromUserName>
                //     <CreateTime>%s</CreateTime>
                //     <MsgType><![CDATA[%s]]></MsgType>
                //     <Music>
                //     <Title><![CDATA[%s]]></Title>
                //     <Description><![CDATA[%s]]></Description>
                //     <MusicUrl><![CDATA[%s]]></MusicUrl>
                //     <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
                //     </Music>
                //     </xml>";

                //     $msgType = "music";
                //     $title = "aaa";
                //     $description = "bbb";
                //     $url = "http://url.mcvmc.com/qq.php/221569632.mp3";
                //     $resultStr = sprintf($musicTpl, $fromUsername, $toUsername, $time, 
                //     $msgType, $title, $description, $url, $url);
                //     echo $resultStr;
                // }
                // else {
                //     echo "nothing to do!";
                // }
                
            }
            else {
                echo "";
                exit;
            }
        }
    }  
?>  