<?php

$servername = "localhost";
$username = "MyDb";
$password = 'MyWebApp1@';
$dbname = "Project";

// Create connection
$db = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($db->connect_error) {
  die("Connection failed: " . $conn->connect_error);

}

?>