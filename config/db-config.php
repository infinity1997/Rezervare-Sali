<?php
function connectToDB()
{
    $serverName = "localhost";
    $userName = "adminCalendar";
    $password = "vfvfFN1JNVgMFpAp";
    $dbName = "TIcalendarac";

// Create connection
    $conn = new mysqli($serverName, $userName, $password, $dbName);
// Check connection
    $conn->query('SET character_set_client="utf8",character_set_connection="utf8",character_set_results="utf8";');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}


