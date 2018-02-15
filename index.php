<?php

/**
 * Webhook for Time Bot- Facebook Messenger Bot
 */

include 'config.php.sample';
$hub_verify_token = null;


if(isset($_REQUEST['hub_challenge'])) {
    $challenge = $_REQUEST['hub_challenge'];
    $hub_verify_token = $_REQUEST['hub_verify_token'];
}


if ($hub_verify_token === $verify_token) {
    echo $challenge;
}


$input = json_decode(file_get_contents('php://input'), true);

$sender = $input['entry'][0]['messaging'][0]['sender']['id'];
$message =$input['entry'][0]['messaging'][0]['message']['attachments'][0]['url'];

if ($message) {
    $dogPHP= urldecode($message);
    $dogIndex =explode('https://www.youtube.com/watch?v=',$dogPHP);
    if (count($dogIndex)>1){
        //API Url
        $url = 'http://brick.dnd.vn/act.aspx?dogadd='. urlencode($dogIndex[1]);


        //Initiate cURL.
        $ch = curl_init($url);

        //The JSON data.
        $jsonData = '{}';

        //Encode the array into JSON.
        $jsonDataEncoded = $jsonData;

        //Tell cURL that we want to send a POST request.
        curl_setopt($ch, CURLOPT_POST, 1);

        //Attach our encoded JSON string to the POST fields.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

        //Set the content type to application/json
        //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: text/html'));

        //Execute the request
        $result = curl_exec($ch);
    }
}


?>