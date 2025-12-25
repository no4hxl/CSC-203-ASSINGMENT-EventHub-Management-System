<?php
/**
 * Database Connection Helper
 * This file handles the connection to our MySQL database.
 * We define the credentials once and create a single connection object ($conn).
 */

$host = "localhost";
$user = "root";
$pass = "";
$db   = "event_db"; // Make sure this matches your database name in phpMyAdmin

// Create connection using the variables above
$conn = mysqli_connect($host, $user, $pass, $db);

// Verify the connection
if (!$conn) {
    // If connection fails, stop everything and show the error
    die("Database connection failed: " . mysqli_connect_error());
}
?>
