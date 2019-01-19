<?php  
    define("TOKEN", "michaellaoliu"); //TOKEN值  
    $wechatObj = new wechat();  
    ####$wechatObj->valid();  
    $wechatObj->responseMSG();

    // error_log("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa", 0);
    // error_log("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa", 0);
    // error_log("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa", 0);
    // error_log("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa", 0);
    // trigger_error("bbbbbbbbbbbbbbbbbbbbbbbbbbbb", E_USER_ERROR);
    class wechat {  

        public function valid() {  
            $echoStr = $_GET["echostr"];  

            if($this->checkSignature()){  
                echo $echoStr;  
                exit;  
            }  
        }  
      
        private function checkSignature() {  

            if (!defined("TOKEN"))
            {
                throw new Exception('TOKEN is not defined');
            }

            $signature = $_GET["signature"];  
            $timestamp = $_GET["timestamp"];  
            $nonce = $_GET["nonce"];  
            $token = TOKEN;  
            $tmpArr = array($token, $timestamp, $nonce);  
            sort($tmpArr, SORT_STRING);  
            $tmpStr = implode($tmpArr);  
            $tmpStr = sha1($tmpStr);  
            if( $tmpStr == $signature ) {  
                return true;  
            } else {  
                return false;  
            }  
        }  

        public function responseMSG() {

            // foreach ($_GET as $key => $value) {
            // //trigger_error("_GET = ".$_GET, E_USER_ERROR);
            // error_log("_GET[key] = ".$key." _GET[value] = ".$value, 0);
            // }
            // foreach ($_POST as $key => $value) {
            // //trigger_error("_POST = ".$_POST, E_USER_ERROR);
            // error_log("_POST[key] = ".$key." _POST[value] = ".$value, 0);
            // }
            //copy from demo.php
            include_once "wxBizMsgCrypt.php";
            include_once "errorCode.php";
            //////////////////
            ##$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
            //copy from demo.php
            $encodingAesKey = "";
            $appId = "";
            $appsecret = "";
            $token = TOKEN;
            $timeStamp = $_GET["timestamp"];
            $nonce = $_GET["nonce"];
            $msg_sign = $_GET["msg_signature"];//not from demo.php
            #$encrypt_type = $_GET["encrypt_type"];//not from demo.php
            #$signature = $_GET["signature"];//not from demo.php
            #$openid = $_GET["openid"];//not from demo.php

            $pc = new WXBizMsgCrypt($token, $encodingAesKey, $appId);

            $mem = new Memcached();
            //get content from socket
            $postStr = file_get_contents('php://input');
            ##print("liulijin \n");
            ##error_log('----------------------', 0);
            $msg = '';
            $errCode = $pc->decryptMsg($msg_sign,$timeStamp,$nonce,$postStr,$msg);
            if ($errCode == ErrorCode::$OK)
            {
            $postStr = $msg;
            if (!empty($postStr)) {

                /////////xml xml xml
                libxml_disable_entity_loader(true);
                ##xml parser | type = SimpleXMLElement
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

                /////////////get xml elements
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
                        // $contentStr = "------ \n press 1 \n press 2 \n press 3";
                        // $sql = "select * from user where openid='{$fromUsername}';";
                        // $stmt = $pdo->query($sql);
                        // while ($row = $stmt->fetch()) {
                        //     $user = $row['openid'];
                        // }

                        // if (empty($user))
                        // {
                        //     $sql = "insert into user (id,openid,menu) values (null,'$fromUsername',0);";
                        //     $stmt = $pdo->exec($sql);
                             $contentStr = "welcome new user\n press 1 to AAA\n press 2 to BBB";
                        // }
                        // $pdo = null;
                    }
                    else if ($event == "unsubscribe")
                    {
                    //     $sql = "select * from user where openid='{$fromUsername}';";
                    //     $stmt = $pdo->query($sql);
                    //     while ($row = $stmt->fetch()) {
                    //         $user = $row['openid'];
                    //     }

                    //     //delete this user from db by openid
                    //     if (!empty($user))
                    //     {
                    //         $sql = "delete from user where openid='{$fromUsername}';";
                    //         $stmt = $pdo->exec($sql);
                            $contentStr = "byebye!";
                        // }
                        // $pdo = null;
                    }
                    break;





                    // case "text":
                    // switch ($keyword)
                    // {
                    //     case "1":
                    //     $contentStr = "123";
                    //     break;

                    //     case "2":
                    //     $contentStr = "ABC";
                    //     break;

                    //     case "3":
                    //     $contentStr = "xyz";
                    //     break;

                    //     default:
                    //     $contentStr = "nothing found!";
                    //     break;
                    // }
                    // break;

                    //mysql pdo practices
                    // case "text":
                    // switch ($keyword)
                    // {
                    //     case "c":
                    //     // $mysql = new SaeMysql();
                    //     // $sql = "insert into 'weixin' ('id', 'title', 'content') values (null, 'what is your name', 'who is your name');";
                    //     // $mysql->runSql($sql);
                    //     $sql = "insert into weixin (id,title,content) values (null, 'what is your name', 'who is your name');";
                    //     $stmt = $pdo->exec($sql);
                    //     $contentStr = "insert a record success";
                    //     $pdo = null;
                    //     break;

                    //     case "d":
                    //     $sql = "delete from weixin where title like '%what%';";
                    //     $stmt = $pdo->exec($sql);
                    //     $contentStr = "delete a record success";
                    //     $pdo = null;
                    //     break;

                    //     case "u":
                    //     $sql = "update weixin set content='why why why' where title='what is your name';";
                    //     $stmt = $pdo->exec($sql);
                    //     $contentStr = "update a record success";
                    //     $pdo = null;
                    //     break;

                    //     case "r":
                    //     // for mysql driver
                    //     // if ($mysqli->connect_error) {
                    //     //     exit($mysqli->connect_error);
                    //     // }
                    //     // $mysqli->close();
                    
                    //     // for pdo
                    //     // try {
                        
                    //     $sql = "select * from weixin where title = 'r';";
                    //     $stmt = $pdo->query($sql);
                    //     while ($row = $stmt->fetch()) {
                    //         $contentStr = $row['content'];
                    //     }
                    //     // foreach ($pdo->query($sql) as $row)
                    //     // {
                    //     //     // $contentStr = "query a record success";
                    //     //     $contentStr = $row['content'];
                    //     // }
                    //     $pdo = null;
                    //     // } catch (PDOException $e) {
                    //     //     //$contentStr = "error!";
                    //     //     //$contentStr = $e->getMessage();
                    //     //     //die(); exit();
                    //     // } finally { //only php7+
                    //     //     $pdo = null;//release this inst
                    //     // }
                    //     break;

                    //     default:
                    //     $contentStr = "nothing found!";
                    //     break;
                    // }
                    // break;

                    // case "image":
                    // $picUrl = $postObj->picUrl;
                    // $contentStr = "image url ".$picUrl;
                    // break;

                    // case "voice":
                    // $format = $postObj->Format;
                    // $recog = $postObj->Recognition;
                    // $contentStr = "voice format is ".$format."conetnt is ".$recog;
                    // break;

                    // case "video":
                    // $contentStr = "video video...";
                    // break;

                    // case "location":
                    // $title = urldecode("腾讯");
                    // $content = urlencode("腾讯");
                    // $contentStr = "<a href=\"http://api.map.baidu.com/marker?location=40.047669,116.313082&title={$title}&content={$content}&output=html&src=webapp.baidu.openAPIdemo\">linklinklin</a>";
                    // // $locationx = $postObj->location_X;
                    // // $locationy = $postObj->location_Y;
                    // // $contentStr = "map position x is ".$locationx." y is ".$locationy;
                    // break;

                    // case "link":
                    // $title = $postObj->Title;
                    // $contentStr = "link title is ".$title;
                    // break;


                    // case "text":
                    // if ($keyword) 
                    // {
                    //     $sql = "select * from CRM where USER='{$fromUsername}';";
                    //     $stmt = $pdo->query($sql);
                    //     while ($row = $stmt->fetch()) {
                    //         $user = $row['USER'];
                    //     }
                    //     if (empty($user))
                    //     {
                    //     $sql = "insert into CRM (ID,USER) values (null,'$fromUsername');";
                    //     $stmt = $pdo->exec($sql);
                    //     $contentStr = "welcome new user";
                    //     $pdo = null;
                    //     }
                    //     else {
                    //         $contentStr = "oh! my old friend";
                    //     }
                    // }


                    case "text":
                    if ($keyword == "@")
                    {
                        // $sql = "update user set menu=0 where openid='{$fromUsername}';";
                        // $stmt = $pdo->exec($sql);
                        $mem->set($fromUsername."key", "0");
                        $contentStr = "press 1 to AAA\n press 2 to BBB";
                        // $pdo = null;
                    }
                    else if ($keyword == "1")
                    {
                        // $sql = "update user set menu=1 where openid='{$fromUsername}';";
                        // $stmt = $pdo->exec($sql);
                        $mem->set($fromUsername."key", "1");
                        $contentStr = "AAA!!!";
                        // $pdo = null;
                    }
                    else if ($keyword == "2")
                    {
                        // $sql = "update user set menu=2 where openid='{$fromUsername}';";
                        // $stmt = $pdo->exec($sql);
                        $mem->set($fromUsername."key", "2");
                        $contentStr = "BBB???";
                        // $pdo = null;
                    }
                    else 
                    {
                        // $sql = "select * from user where openid='{$fromUsername}';";
                        // $stmt = $pdo->query($sql);
                        // while ($row = $stmt->fetch()) {
                        //     $menu = $row['menu'];
                        // }
                        $menu = $mem->get($fromUsername."key");

                        if (empty($menu))
                        {
                            // $sql = "update user set menu=0 where openid='{$fromUsername}';";
                            // $stmt = $pdo->exec($sql);
                            $contentStr = "press 1 to AAA\n press 2 to BBB";
                            // $pdo = null;
                        }

                        if ($menu == 1)
                        {
                            //$contentStr = "AAA Got it!!!";
                            $contentStr = "<a href=\"www.baidu.com\">baidu</a>\n\n<a href=\"www.sina.com.cn\">Sina</a>";
                        }
                        else if ($menu == 2)
                        {
                            $contentStr = "BBB Got it!!";
                        }
                    }
                    break;
                } /** switch case */
                
                $msgType = "text";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                ###echo $resultStr;
                $encryptMsg = '';
                $errCode = $pc->encryptMsg($resultStr,$timestamp,$nonce,$encryptMsg);
                if ($errCode == ErrorCode::$OK)
                {
                    echo $encryptMsg;
                }
                else
                {
                    print($errCode."\n");
                }

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
        }//errorCode
        }//responseMSG
    }//wechat class  
?>  