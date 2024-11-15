<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Marketplacedb";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Fetch items from database
$stmt = $conn->prepare("
    SELECT i.*, u.username 
    FROM Items i 
    JOIN Users u ON i.user_id = u.user_id 
    ORDER BY i.created_at DESC
");
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livestock Product Listings</title>
    <link rel="stylesheet" href="productListing.css">
</head>
<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
        <a href="productListing.php">Product Listings</a>
        <a href="chat.php">Chat</a>
        <a href="Post_item.php">Post an Item</a>
        <a href="userprofile.php">User Profile</a>
        <a href="aboutus.php">About Us</a>  
    </div>

    <div>
        <header class="main-header">
            <img class="logo-image" src="images/Logo.png" alt="Page logo">
        </header> 
    </div>

    <main>
        <div class="container">
            <h2>AVAILABLE LIVESTOCK</h2>
            <div class="product-grid">
                <?php foreach($items as $item): ?>
                    <div class="product-card">
                        <?php if($item['image_path']): ?>
                            <img src="<?php echo htmlspecialchars($item['image_path']); ?>" 
                                 alt="<?php echo htmlspecialchars($item['title']); ?>">
                        <?php else: ?>
                            <img src="images/placeholder.jpg" alt="No image available">
                        <?php endif; ?>
                        
                        <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                        <p class="price">Price: N$ <?php echo number_format($item['price'], 2); ?></p>
                        <p class="category">Category: <?php echo htmlspecialchars($item['category']); ?></p>
                        <p class="seller">Seller: <?php echo htmlspecialchars($item['username']); ?></p>
                        
                        <div class="product-actions">
                            <button class="btn message-btn" 
                                    onclick="startChat(<?php echo $item['user_id']; ?>)">
                                Message Seller
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Livestock Marketplace. All rights reserved.</p>
        </div>
    </footer>

    <script>
    function startChat(sellerId) {
        // Redirect to chat page with seller ID
        window.location.href = `chat.php?seller_id=${sellerId}`;
    }
    </script>
</body>
</html>