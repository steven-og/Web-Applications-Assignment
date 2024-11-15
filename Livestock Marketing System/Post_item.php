<?php
session_start();
require_once 'config.php'; // Assume this contains database connection details

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $health = mysqli_real_escape_string($conn, $_POST['health']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
   // Handle file upload
$image_path = '';
if(isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
    $allowed = array('jpg', 'jpeg', 'png', 'gif');
    $filename = $_FILES['file']['name'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    
    if(in_array(strtolower($ext), $allowed)) {
        if($_FILES['file']['size'] <= 2097152) { // 2MB limit
            $upload_path = 'uploads/';
            
            // Create directory if it doesn't exist
            if (!file_exists($upload_path)) {
                mkdir($upload_path, 0777, true);
            }
            
            // Ensure directory is writable
            chmod($upload_path, 0777);
            
            $new_filename = uniqid() . '.' . $ext;
            $full_path = $upload_path . $new_filename;
            
            if(move_uploaded_file($_FILES['file']['tmp_name'], $full_path)) {
                $image_path = $full_path;
            } else {
                $error_message = "Failed to upload file. Error: " . error_get_last()['message'];
            }
        } else {
            $error_message = "File is too large. Maximum size is 2MB.";
        }
    } else {
        $error_message = "Invalid file type. Allowed types: jpg, jpeg, png, gif";
    }
}
    // Insert into database
    $query = "INSERT INTO Items (user_id, title, price, category, description, image_path) 
              VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isdsss", $user_id, $title, $price, $category, $description, $image_path);
    
    if($stmt->execute()) {
        $success_message = "Item posted successfully!";
    } else {
        $error_message = "Error posting item. Please try again.";
    }
    
    $stmt->close();
}

// Fetch categories from database
$categories_query = "SELECT category_name FROM Categories";
$categories_result = $conn->query($categories_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post an Item</title>
    <link rel="stylesheet" href="post.css">
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

    <div class="form-container">
        <div class="language-selector">
            <label for="language">Choose Language:</label>
            <select id="language" onchange="toggleLanguage()">
                <option value="en">English</option>
                <option value="af">Afrikaans</option>
            </select>
        </div>

        <?php if(isset($success_message)): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if(isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- English Version -->
        <div id="form-en" class="form" lang="en">
            <h2>What are you selling?</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                <label for="file-upload" class="upload-button">+ Upload</label>
                <input id="file-upload" type="file" name="file" accept="image/*" hidden>
                <p class="file-size-info">Max: 2 MB</p>

                <input type="text" name="title" placeholder="Title" required>
                <input type="number" name="price" placeholder="Price N$" step="0.01" required>
                <select name="category" required>
                    <option value="" disabled selected>Category</option>
                    <?php while($category = $categories_result->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($category['category_name']); ?>">
                            <?php echo htmlspecialchars($category['category_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <input type="text" name="health" placeholder="Health" required>
                <textarea name="description" placeholder="Description" required></textarea>
                <button type="submit">Publish</button>
            </form>
        </div>

        <!-- Afrikaans Version -->
        <div id="form-af" class="form" lang="af" style="display: none;">
            <h2>Wat verkoop jy?</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                <label for="file-upload-af" class="upload-button">+ Laai op</label>
                <input id="file-upload-af" type="file" name="file" accept="image/*" hidden>
                <p class="file-size-info">Maks: 2 MB</p>

                <input type="text" name="title" placeholder="Titel" required>
                <input type="number" name="price" placeholder="Prys N$" step="0.01" required>
                <select name="category" required>
                    <option value="" disabled selected>Kategorie</option>
                    <?php 
                    $categories_result->data_seek(0); // Reset result pointer
                    while($category = $categories_result->fetch_assoc()): 
                    ?>
                        <option value="<?php echo htmlspecialchars($category['category_name']); ?>">
                            <?php echo htmlspecialchars($category['category_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <input type="text" name="health" placeholder="Gesondheid" required>
                <textarea name="description" placeholder="Beskrywing" required></textarea>
                <button type="submit">Publiseer</button>
            </form>
        </div>
    </div>

    <script>
        function toggleLanguage() {
            const selectedLanguage = document.getElementById('language').value;
            const formEn = document.getElementById('form-en');
            const formAf = document.getElementById('form-af');

            if (selectedLanguage === 'en') {
                formEn.style.display = 'block';
                formAf.style.display = 'none';
            } else {
                formEn.style.display = 'none';
                formAf.style.display = 'block';
            }
        }
    </script>
</body>
</html>