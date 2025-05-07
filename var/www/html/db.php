<?php
// Database configuration
$host = 'p2ppaymentsdb.cmp8eas2geyo.us-east-1.rds.amazonaws.com'; // typically 'localhost' or specific IP address
$dbname = 'p2ppaymentsdb';
$username = 'admin';
$password = 'bmmgSjIB03FH4KeKkFiz';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// The connection is successful if no error is thrown
?>
