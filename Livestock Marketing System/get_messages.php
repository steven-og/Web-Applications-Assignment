<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "marketplacedb";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $current_user_id = $_SESSION['user_id'];
    $partner_id = isset($_GET['partner_id']) ? (int)$_GET['partner_id'] : 0;
    $last_id = isset($_GET['last_id']) ? (int)$_GET['last_id'] : 0;

    // Get messages between the current user and partner
    $stmt = $pdo->prepare("
        SELECT message_id, user_id, content, sent_at 
        FROM messages 
        WHERE user_id IN (?, ?)
        AND message_id > ?
        ORDER BY sent_at ASC
    ");

    $stmt->execute([$current_user_id, $partner_id, $last_id]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($messages);

} catch(PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>