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

$message = trim($_POST['message'] ?? '');
if ($message === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Empty message']);
    exit;
}

// Tallennetaan viesti
$stmt = $pdo->prepare("INSERT INTO chat_messages (message) VALUES (:msg)");
$stmt->execute(['msg' => $message]);

// Poistetaan vanhimmat, jätetään 10 viimeisintä
$stmt = $pdo->query("
    DELETE FROM chat_messages
    WHERE id NOT IN (
        SELECT id FROM (SELECT id FROM chat_messages ORDER BY created_at DESC LIMIT 10) AS t
    )
");

echo json_encode(['success' => true]);
?>
