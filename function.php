<?php 
$conn = new mysqli("localhost","root","","databasemu"); 

function curl($url, $data=null, $method="POST", $content_type="application/json", $headers=array()){
    $header[] = "Accept: ".$content_type;
    $header[] = "Content-Type: ".$content_type;
    if($headers){
        $header = array_merge($header, $headers);
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    // curl_setopt($ch, CURLOPT_USERAGENT, Yii::$app->request->userAgent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $CURLOPT_POST = false;
    if($method=="POST"){
        $CURLOPT_POST = true;
    }
    curl_setopt($ch, CURLOPT_POST, $CURLOPT_POST);
    $rs = curl_exec($ch);
    if(empty($rs)){
        $error = curl_error($ch);
        $rs = json_encode(array("error"=>true, "message"=>$error));
    }
    if(strpos($rs, "Internal Server Error")){
        $rs = json_encode(array("error"=>true, "message"=>$rs));
    }
    if(strpos($rs, "couldn't connect to host")){
        $rs = json_encode(array("error"=>true, "message"=>$rs));
    }
    curl_close($ch);
    return $rs;
}
function getCredidential($bot_name,$act='broadcast')
{
    global $conn;
    $query = mysqli_query($conn,
        "SELECT * from bot_setting_list bsl 
        join bot_setting_service bss 
        where 
        bsl.bot_name = '".$bot_name."' 
        and (bsl.is_active = 1 and bss.is_active = 1)"
    );
    return $query;
}
function sendtelegram($type,$act,$message)
{
    $credential = getCredidential($type,$act);
    foreach ($credential as $c) {
        $query = array();
        $query['disable_web_page_preview']  = 1;
        // $query['parse_mode']                = 'markdown';
        $query['parse_mode']                = 'html';
        $query['chat_id']                   = $c['chat_id'];
        // $query['text']                      = rawurlencode($message);
        $query['text']                      = $message;
        $url                                = $c['url']."bot".$c['token']."/".$c['action']."?".http_build_query($query);
        $ack = curl($url,NULL,"GET");
    }
    return $ack;
} ?>