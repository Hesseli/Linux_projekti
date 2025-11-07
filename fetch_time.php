<?php
header("Content-Type: application/json");

$servername = "localhost";     // tai MariaDB IP jos erillinen palvelin
$username = "youruser";
$password = "yourpass";
$dbname = "yourdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["error" => "DB connection failed"]);
    exit;
}

$result = $conn->query("SELECT NOW() AS time");
$row = $result->fetch_assoc();

echo json_encode(["server_time" => $row["time"]]);
