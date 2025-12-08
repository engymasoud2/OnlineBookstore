<?php
$host = "sql1.njit.edu";
$user = "eam64";
$password = "Summer0100$";
$database = "eam64";


$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
