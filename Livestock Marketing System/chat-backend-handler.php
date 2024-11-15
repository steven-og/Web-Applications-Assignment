<?php
// get_messages.php
session_start();
require_once 'db_connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not authenticated']);
    exit();
}

$receiver_id = isset($_GET['receiver_id']) ? (int)$_GET['receiver_id'] : 0;
$last_id = isset($_GET['last_id']) ? (int)$_GET['last_id'] : 0;

try {
    $stmt = $pdo->prepare("
        SELECT message_id, sender_id, content, sent_at 
        FROM Messages 
        WHERE ((sender_id = ? AND receiver_id = ?) 
            OR (sender_id = ? AND receiver_id = ?))
            AND message_id > ?
        ORDER BY sent_at ASC
    ");
    
    $stmt->execute([
        $_SESSION['user_id'], 
        $receiver_id, 
        $receiver_id, 
        $_SESSION['user_id'],
        $last_id
    ]);
    
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($messages);
    
} catch(PDOException $e) {
    echo json_encode(['error' => 'Database error']);
}

// send_message.php
session_start();
require_once 'db_connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit();
}

$receiver_id = isset($_POST['receiver_id']) ? (int)$_POST['receiver_id'] : 0;
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if (empty($message) || $receiver_id === 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
    exit();
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO Messages (sender_id, receiver_id, content) 
        VALUES (?, ?, ?)
    ");
    
    $stmt->execute([$_SESSION['user_id'], $receiver_id, $message]);
    echo json_encode(['success' => true, 'message_id' => $pdo->lastInsertId()]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error']);
}