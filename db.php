<?php
$host = "sql1.njit.edu";
$user = "";
$password = "";
$database = "";


$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
