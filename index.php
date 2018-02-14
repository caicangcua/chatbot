<?php
/**
 * Webhook for Time Bot- Facebook Messenger Bot
 */

include 'config.php.sample';
$hub_verify_token = null;


function str_putcsv($data) {
        # Generate CSV data from array
        $fh = fopen('php://temp', 'rw'); # don't create a file, attempt
                                         # to use memory instead

        # write out the headers
        fputcsv($fh, array_keys(current($data)));

        # write out the data
        foreach ( $data as $row ) {
                fputcsv($fh, $row);
        }
        rewind($fh);
        $csv = stream_get_contents($fh);
        fclose($fh);

        return $csv;
}


if(isset($_REQUEST['hub_challenge'])) {
    $challenge = $_REQUEST['hub_challenge'];
    $hub_verify_token = $_REQUEST['hub_verify_token'];
}


if ($hub_verify_token === $verify_token) {
    echo $challenge;
}

$input = json_decode(file_get_contents('php://input'), true);

$sender = $input['entry'][0]['messaging'][0]['sender']['id'];
$message = str_putcsv($input['entry'][0]['messaging'][0]['message']) ;

$message_to_reply = '';

/**
 * Some Basic rules to validate incoming messages
 */
if(preg_match('[time|current time|now|время|час]', strtolower($message))) {

    $time = getdate();
    $hours = $time['hours'];
    if ($time['minutes']<10) {
        $minutes = "0".$time['minutes'];
    } else {
        $minutes = $time['minutes'];
    }
    $response = $hours.":".$minutes;
    if($response != '') {
        $message_to_reply = $response;
    } else {
        $message_to_reply = "Sorry, I don't know.";
    }
} else {

    $message_to_reply ='fuck';

}

//API Url
$url = 'https://graph.facebook.com/v2.8/me/messages?access_token='.$access_token;


//Initiate cURL.
$ch = curl_init($url);

//The JSON data.
$jsonData = '{
    "recipient":{
        "id":"'.$sender.'"
    },
    "message":{
        "text":"'.$message.'"
    }
}';

//Encode the array into JSON.
$jsonDataEncoded = $jsonData;

//Tell cURL that we want to send a POST request.
curl_setopt($ch, CURLOPT_POST, 1);

//Attach our encoded JSON string to the POST fields.
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

//Set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

//Execute the request
if(!empty($input['entry'][0]['messaging'][0]['message'])){
    $result = curl_exec($ch);
}