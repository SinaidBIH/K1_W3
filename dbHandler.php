<?php

function connectDB()
{
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'categorieen';

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        error_log("Connection failed: " . $conn->connect_error);
        throw new Exception("Database connection failed");
    }

    // Set character set to UTF-8
    if (!$conn->set_charset("utf8")) {
        error_log("Error loading character set utf8: " . $conn->error);
        throw new Exception("Error loading character set utf8");
    }

    return $conn;
}

function closeConn($conn)
{
    // Close the database connection
    if ($conn) {
        $conn->close();
    }
}
