<?php
$conn = mysqli_connect("localhost", "root", "", "event_db");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
$host = "localhost";
$user = "root";
$pass = "";
$db = "event_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
