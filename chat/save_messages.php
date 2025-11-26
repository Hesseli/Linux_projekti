<?php
$dbconfig = require __DIR__ . '/../../dbcreds.php';
$chat = $dbconfig['chat'];

// Päätetään sender palvelimella
$isAdmin = ($_POST['admin'] ?? '0') === '1';
$sender = $isAdmin ? 'admin' : 'vierailija';

try {
    $pdo = new PDO(
        "mysql:host={$chat['host']};dbname={$chat['db']};charset=utf8mb4",
        $chat['user'],
        $chat['pass'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (Exception $e) {
    file_put_contents('/tmp/chat_error.log', "DB CONNECT ERROR: " . $e->getMessage() . "\n", FILE_APPEND);
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$message = trim($_POST['message'] ?? '');

if ($message === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Empty message']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO chat_messages (message, sender) VALUES (:msg, :sender)");
    $stmt->execute(['msg' => $message, 'sender' => $sender]);
} catch (Exception $e) {
    file_put_contents('/tmp/chat_error.log', "INSERT ERROR: " . $e->getMessage() . "\n", FILE_APPEND);
    http_response_code(500);
    echo json_encode(['error' => 'DB insert failed']);
    exit;
}

echo json_encode(['success' => true, 'sender' => $sender]);
