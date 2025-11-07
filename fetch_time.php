<?php
header("Content-Type: application/json");

$config = include __DIR__ . "/../dbcreds.php";

$conn = new mysqli(
    $config['host'],
    $config['user'],
    $config['pass'],
    $config['db']
);

if ($conn->connect_error) {
    echo json_encode(["error" => "DB connection failed"]);
    exit;
}

$result = $conn->query("SELECT NOW() AS time");
$row = $result->fetch_assoc();

echo json_encode(["server_time" => $row["time"]]);
