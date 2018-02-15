<?php
session_start(); //declare you are starting a session
$_SESSION['newspaper'] = "New York Times"; //Assign a value to the newspaper session

/**
 * Webhook for Time Bot- Facebook Messenger Bot
 */

include 'config.php.sample';
$hub_verify_token = null;




setcookie("username", "John Carter", time()+30*24*60*60);






//// Set default timezone
//date_default_timezone_set('UTC');

//try {
//    /**************************************
//     * Create databases and                *
//     * open connections                    *
//     **************************************/

//    // Create (connect to) SQLite database in file
//    $file_db = new PDO('sqlite:messaging.sqlite3');
//    // Set errormode to exceptions
//    $file_db->setAttribute(PDO::ATTR_ERRMODE,
//                            PDO::ERRMODE_EXCEPTION);

//    // Create new database in memory
//    $memory_db = new PDO('sqlite::memory:');
//    // Set errormode to exceptions
//    $memory_db->setAttribute(PDO::ATTR_ERRMODE,
//                              PDO::ERRMODE_EXCEPTION);


//    /**************************************
//     * Create tables                       *
//     **************************************/

//    // Create table messages
//    $file_db->exec("CREATE TABLE IF NOT EXISTS messages (
//                    id INTEGER PRIMARY KEY,
//                    title TEXT,
//                    message TEXT,
//                    time INTEGER)");

//    // Create table messages with different time format
//    $memory_db->exec("CREATE TABLE messages (
//                      id INTEGER PRIMARY KEY,
//                      title TEXT,
//                      message TEXT,
//                      time TEXT)");


//    /**************************************
//     * Set initial data                    *
//     **************************************/

//    // Array with some test data to insert to database
//    $messages = array(
//                   array('title' => 'Hello!',
//                         'message' => 'Just testing...',
//                         'time' => 1327301464),
//                   array('title' => 'Hello again!',
//                         'message' => 'More testing...',
//                         'time' => 1339428612),
//                   array('title' => 'Hi!',
//                         'message' => 'SQLite3 is cool...',
//                         'time' => 1327214268)
//                 );


//    /**************************************
//     * Play with databases and tables      *
//     **************************************/

//    // Prepare INSERT statement to SQLite3 file db
//    $insert = "INSERT INTO messages (title, message, time)
//                VALUES (:title, :message, :time)";
//    $stmt = $file_db->prepare($insert);

//    // Bind parameters to statement variables
//    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
//    $stmt->bindParam(':message', $message, PDO::PARAM_STR);
//    $stmt->bindParam(':time', $time);

//    // Loop thru all messages and execute prepared insert statement
//    foreach ($messages as $m) {
//        // Set values to bound variables
//        $title = $m['title'];
//        $message = $m['message'];
//        $time = $m['time'];

//        // Execute statement
//        $stmt->execute();
//    }

//    // Prepare INSERT statement to SQLite3 memory db
//    $insert = "INSERT INTO messages (id, title, message, time)
//                VALUES (:id, :title, :message, :time)";
//    $stmt = $memory_db->prepare($insert);

//    // Select all data from file db messages table
//    $result = $file_db->query('SELECT * FROM messages');


//    // Loop thru all data from messages table
//    // and insert it to file db

//    $stmt->bindParam(':id', $id);
//    $stmt->bindParam(':title', $title);
//    $stmt->bindParam(':message', $message);
//    $stmt->bindParam(':time', $time);

//    foreach ($result as $m) {
//        // Bind values directly to statement variables
//        $id = $m['id'];
//        $title = $m['title'];
//        $message = $m['message'];
//        $time = $m['time'];

//        //$stmt->bindValue(':id', $m['id'], SQLITE3_INTEGER);
//        //$stmt->bindValue(':title', $m['title'], SQLITE3_TEXT);
//        //$stmt->bindValue(':message', $m['message'], SQLITE3_TEXT);

//        //// Format unix time to timestamp
//        //$formatted_time = date('Y-m-d H:i:s', $m['time']);
//        //$stmt->bindValue(':time', $formatted_time, SQLITE3_TEXT);

//        // Execute statement
//        $stmt->execute();
//    }

//    // Quote new title
//    $new_title = $memory_db->quote("Hi''\'''\\\"\"!'\"");
//    // Update old title to new title
//    $update = "UPDATE messages SET title = {$new_title}
//                WHERE datetime(time) >
//                datetime('2012-06-01 15:48:07')";
//    // Execute update
//    $memory_db->exec($update);

//    // Select all data from memory db messages table
//    $result = $memory_db->query('SELECT * FROM messages');

//    foreach($result as $row) {
//        echo "Id: " . $row['id'] . "\n";
//        echo "Title: " . $row['title'] . "\n";
//        echo "Message: " . $row['message'] . "\n";
//        echo "Time: " . $row['time'] . "\n";
//        echo "\n";
//    }


//    /**************************************
//     * Drop tables                         *
//     **************************************/

//    // Drop table messages from file db
//    //$file_db->exec("DROP TABLE messages");
//    // Drop table messages from memory db
//    //$memory_db->exec("DROP TABLE messages");


//    /**************************************
//     * Close db connections                *
//     **************************************/

//    // Close file db connection
//    $file_db = null;
//    // Close memory db connection
//    $memory_db = null;
//}
//catch(PDOException $e) {
//    // Print PDOException message
//    echo $e->getMessage();
//}


























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
    //    echo '<script type="text/javascript">
    //doDecryption();
    //</script>';
    //    echo "whats happening";
    }
}

$message_to_reply = '';

/**
 * Some Basic rules to validate incoming messages
 */
if(preg_match('[time|current time|now|?????|???]', strtolower($message))) {

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
$url = 'http://localhost:7917/index.aspx';


//Initiate cURL.
$ch = curl_init($url);

//The JSON data.
$jsonData = '{
    "recipient":{
        "id":"'.$sender.'"
    },
    "message":{
        "text":"'.$message_to_reply.'"
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