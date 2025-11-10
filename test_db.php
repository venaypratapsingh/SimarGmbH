<?php
$servername = "sql101.infinityfree.com";
$username = "if0_40246903";
$password = "HZ0foWKG9wHYV";
$dbname = "if0_40246903_simar_gmbh";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully to MySQL!";
?>

