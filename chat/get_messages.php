<?php
$dbconfig = require 'dbcreds.php';
$chat = $dbconfig['chat'];

// Luodaan PDO-yhteys chat-kantaan
$pdo = new PDO(
    "mysql:host={$chat['host']};dbname={$chat['db']};charset=utf8mb4",
    $chat['user'],
    $chat['pass'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

$stmt = $pdo->query("SELECT * FROM chat_messages ORDER BY created_at ASC");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($messages);
?>
