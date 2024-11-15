<?php
// Create delete_listing.php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $item_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];
    
    // First verify that the item belongs to the logged-in user
    $check_query = "SELECT user_id FROM Items WHERE item_id = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("i", $item_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $item = $result->fetch_assoc();
    
    if ($item && $item['user_id'] == $user_id) {
        // Delete the item
        $delete_query = "DELETE FROM Items WHERE item_id = ? AND user_id = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("ii", $item_id, $user_id);
        
        if ($delete_stmt->execute()) {
            $_SESSION['success_message'] = "Listing deleted successfully!";
        } else {
            $_SESSION['error_message'] = "Error deleting listing.";
        }
        $delete_stmt->close();
    } else {
        $_SESSION['error_message'] = "You don't have permission to delete this listing.";
    }
    $check_stmt->close();
}

// Redirect back to profile page
header("Location: userprofile.php");
exit();
?>