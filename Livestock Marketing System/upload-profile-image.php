<?php
// upload_profile_image.php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_image'])) {
    $user_id = $_SESSION['user_id'];
    $file = $_FILES['profile_image'];
    
    // Validate file
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($file['type'], $allowed_types)) {
        $_SESSION['error_message'] = "Invalid file type. Please upload a JPG, PNG, or GIF.";
        header("Location: userprofile.php");
        exit();
    }
    
    if ($file['size'] > $max_size) {
        $_SESSION['error_message'] = "File is too large. Maximum size is 5MB.";
        header("Location: userprofile.php");
        exit();
    }
    
    // Create uploads directory if it doesn't exist
    $upload_dir = 'uploads/profile_images/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Generate unique filename
    $filename = $user_id . '_' . time() . '_' . basename($file['name']);
    $filepath = $upload_dir . $filename;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        // Update database with new image path
        $query = "UPDATE Users SET profile_image = ? WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $filepath, $user_id);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Profile picture updated successfully!";
        } else {
            $_SESSION['error_message'] = "Error updating profile picture in database.";
        }
        $stmt->close();
    } else {
        $_SESSION['error_message'] = "Error uploading file.";
    }
    
    header("Location: userprofile.php");
    exit();
}
?>