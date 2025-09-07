<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "gym_fitnest";  // make sure the database exists in phpMyAdmin

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if ($conn) {
    // echo "Connected OK";
} else {
    die("Connection failed: " . mysqli_connect_error());
}

