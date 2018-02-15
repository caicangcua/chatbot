<?php

// Set default timezone
date_default_timezone_set('UTC');

try {
    // Create (connect to) SQLite database in file
    $file_db = new PDO('sqlite:messaging.sqlite3');
    // Set errormode to exceptions
    $file_db->setAttribute(PDO::ATTR_ERRMODE,
                            PDO::ERRMODE_EXCEPTION);


    // Select all data from memory db messages table
    $result = $file_db->query('SELECT * FROM messages');

    foreach($result as $row) {
        echo "Id: " . $row['id'] . "\n";
        echo "Title: " . $row['title'] . "\n";
        echo "Message: " . $row['message'] . "\n";
        echo "Time: " . $row['time'] . "\n";
        echo "\n";
    }
    // Close file db connection
    $file_db = null;
    // Close memory db connection
    $memory_db = null;
}
catch(PDOException $e) {
    // Print PDOException message
    echo $e->getMessage();
}


?>