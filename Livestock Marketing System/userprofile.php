<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user information
$user_id = $_SESSION['user_id'];
$user_query = "SELECT * FROM Users WHERE user_id = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if (!$user_result || $user_result->num_rows === 0) {
    $_SESSION['error_message'] = "User not found.";
    header("Location: login.php");
    exit();
}

$user = $user_result->fetch_assoc();

// Fetch user's listings
$listings_query = "SELECT * FROM Items WHERE user_id = ? ORDER BY created_at DESC";
$listings_stmt = $conn->prepare($listings_query);
$listings_stmt->bind_param("i", $user_id);
$listings_stmt->execute();
$listings_result = $listings_stmt->get_result();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    // Sanitize and validate inputs
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $location = filter_var($_POST['location'], FILTER_SANITIZE_STRING);
    $experience = filter_var($_POST['experience'], FILTER_SANITIZE_STRING);
    $specialty = filter_var($_POST['specialty'], FILTER_SANITIZE_STRING);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } else {
        $update_query = "UPDATE Users SET 
                        email = ?, 
                        phone = ?, 
                        location = ?, 
                        experience = ?, 
                        specialty = ? 
                        WHERE user_id = ?";
                        
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("sssssi", $email, $phone, $location, $experience, $specialty, $user_id);
        
        if ($update_stmt->execute()) {
            $success_message = "Profile updated successfully!";
            // Refresh user data
            $user_stmt->execute();
            $user_result = $user_stmt->get_result();
            $user = $user_result->fetch_assoc();
        } else {
            $error_message = "Error updating profile: " . $conn->error;
        }
        $update_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Profile</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="productListing.php">Product Listings</a>
        <a href="chat.php">Chat</a>
        <a href="Post_item.php">Post an Item</a>
        <a href="userprofile.php">User Profile</a>
        <a href="aboutus.php">About Us</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="profile-container">
        <h1>Farmer Profile</h1>
        
        <?php if(isset($success_message)): ?>
            <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        
        <?php if(isset($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <div class="profile-content">
            <div class="profile-image">
                <?php if(!empty($user['profile_image'])): ?>
                    <img src="<?php echo htmlspecialchars($user['profile_image']); ?>" 
                         alt="<?php echo htmlspecialchars($user['username']); ?>">
                <?php else: ?>
                    <img src="images/placeholder.jpg" alt="No image available">
                <?php endif; ?>
            </div>

            <div class="personal-info">
                <h2>Personal Information</h2>
                <form method="POST" action="">
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                    <p>
                        <strong>Email:</strong>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </p>
                    <p>
                        <strong>Phone:</strong>
                        <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                    </p>
                    <p>
                        <strong>Location:</strong>
                        <input type="text" name="location" value="<?php echo htmlspecialchars($user['location'] ?? ''); ?>">
                    </p>
                    <p>
                        <strong>Experience:</strong>
                        <input type="text" name="experience" value="<?php echo htmlspecialchars($user['experience'] ?? ''); ?>">
                    </p>
                    <p>
                        <strong>Specialty:</strong>
                        <input type="text" name="specialty" value="<?php echo htmlspecialchars($user['specialty'] ?? ''); ?>">
                    </p>
                    <button type="submit" name="update_profile">Update Profile</button>
                </form>
            </div>
        </div>

        <div class="listings">
            <h2>My Listings</h2>
            <div class="listings-grid">
                <?php if($listings_result->num_rows > 0): ?>
                    <?php while($listing = $listings_result->fetch_assoc()): ?>
                        <div class="listing-card">
                            <?php if(!empty($listing['image_path'])): ?>
                                <img src="<?php echo htmlspecialchars($listing['image_path']); ?>" 
                                     alt="<?php echo htmlspecialchars($listing['title']); ?>">
                            <?php else: ?>
                                <img src="images/default-listing.jpg" alt="No image available">
                            <?php endif; ?>
                            <h3><?php echo htmlspecialchars($listing['title']); ?></h3>
                            <p>Price: N$<?php echo number_format($listing['price'], 2); ?></p>
                            <p>Category: <?php echo htmlspecialchars($listing['category']); ?></p>
                            <p>Posted: <?php echo date('M d, Y', strtotime($listing['created_at'])); ?></p>
                            <a href="delete_listing.php?id=<?php echo $listing['item_id']; ?>" 
                               onclick="return confirm('Are you sure you want to delete this listing?');" 
                               class="edit-btn">Delete</a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No listings found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>