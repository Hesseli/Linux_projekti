<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

$dbconfig = require __DIR__ . '/../../dbcreds.php'; // sama kuin save_messages.php
$chat = $dbconfig['chat'];

try {
    $pdo = new PDO(
        "mysql:host={$chat['host']};dbname={$chat['db']};charset=utf8mb4",
        $chat['user'],
        $chat['pass'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (Exception $e) {
    echo json_encode(['error' => 'DB connect failed', 'msg' => $e->getMessage()]);
    exit;
}

try {
    $stmt = $pdo->query("SELECT id, message, sender, created_at FROM chat_messages ORDER BY created_at ASC");
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($messages);
} catch (Exception $e) {
    echo json_encode(['error' => 'Query failed', 'msg' => $e->getMessage()]);
}
