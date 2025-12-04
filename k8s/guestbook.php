<?php
$dbconfig = include '/var/www/dbcreds.php';
$demo = $dbconfig['demo'];

try {
    $pdo = new PDO(
        "mysql:host={$demo['host']};dbname={$demo['db']};charset=utf8mb4",
        $demo['user'],
        $demo['pass'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (Exception $e) {
    file_put_contents('/tmp/guestbook_error.log', "DB CONNECT ERROR: " . $e->getMessage() . "\n", FILE_APPEND);
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$name = trim($_POST['name'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $message === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Empty name or message']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO guestbook (name, message, created_at) VALUES (:name, :message, NOW())");
    $stmt->execute(['name' => $name, 'message' => $message]);
} catch (Exception $e) {
    file_put_contents('/tmp/guestbook_error.log', "INSERT ERROR: " . $e->getMessage() . "\n", FILE_APPEND);
    http_response_code(500);
    echo json_encode(['error' => 'DB insert failed']);
    exit;
}

// Hae kaikki viestit
$stmt = $pdo->query("SELECT name, message, created_at FROM guestbook ORDER BY id DESC");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($messages);
