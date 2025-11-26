<?php
header("Content-Type: application/json");

// Ladataan dbcreds
$config = include __DIR__ . "/../dbcreds.php";

// Käytetään 'demo'-tietokantaa
$demo = $config['demo'];

$conn = new mysqli(
    $demo['host'],
    $demo['user'],
    $demo['pass'],
    $demo['db']
);

if ($conn->connect_error) {
    echo json_encode(["error" => "DB connection failed"]);
    exit;
}

$result = $conn->query("SELECT NOW() AS time");
$row = $result->fetch_assoc();

echo json_encode(["server_time" => $row["time"]]);
