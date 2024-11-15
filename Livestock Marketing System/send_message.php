<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit();
}

if (!isset($_POST['partner_id']) || !isset($_POST['message'])) {
    echo json_encode(['success' => false, 'error' => 'Missing required parameters']);
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "marketplacedb";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $user_id = $_SESSION['user_id'];
    $message = trim($_POST['message']);

    // Insert the message
    $stmt = $pdo->prepare("
        INSERT INTO messages (user_id, content, sent_at) 
        VALUES (?, ?, CURRENT_TIMESTAMP)
    ");

    $stmt->execute([$user_id, $message]);
    
    echo json_encode(['success' => true, 'message_id' => $pdo->lastInsertId()]);

} catch(PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>