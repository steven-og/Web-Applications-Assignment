<?php
session_start();
require_once 'config.php';

$login_error = '';
$register_error = '';
$register_success = '';

// Handle Registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $experience = mysqli_real_escape_string($conn, $_POST['experience']);
    $specialty = mysqli_real_escape_string($conn, $_POST['specialty']);
    
    // Handle profile image upload
    $profile_image = '';
    if(isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['profile_image']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if(in_array(strtolower($filetype), $allowed)) {
            $new_filename = uniqid() . '.' . $filetype;
            $upload_path = 'uploads/' . $new_filename; // Make sure this directory exists
            
            if(move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
                $profile_image = $new_filename;
            }
        }
    }
    
    // Check if username or email already exists
    $check_query = "SELECT * FROM Users WHERE username = ? OR email = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("ss", $username, $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        $register_error = "Username or email already exists!";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new user with all fields
        $insert_query = "INSERT INTO Users (username, email, password, phone, location, experience, specialty, profile_image, created_at) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("ssssssss", $username, $email, $hashed_password, $phone, $location, $experience, $specialty, $profile_image);
        
        if ($insert_stmt->execute()) {
            $register_success = "Registration successful! Please login.";
        } else {
            $register_error = "Registration failed. Please try again.";
        }
        $insert_stmt->close();
    }
    $check_stmt->close();
}

// Keep existing login handling code
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['login_username']);
    $password = $_POST['login_password'];
    
    $login_query = "SELECT user_id, username, password FROM Users WHERE username = ?";
    $login_stmt = $conn->prepare($login_query);
    $login_stmt->bind_param("s", $username);
    $login_stmt->execute();
    $result = $login_stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            header("Location: userprofile.php");
            exit();
        } else {
            $login_error = "Invalid password!";
        }
    } else {
        $login_error = "User not found!";
    }
    $login_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Register form</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        /* Keep existing styles */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-image: url('f2eac89c38b54db58156225f0aca49cf.jpg');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
        }

        .navbar {
            background-color: #8b4513;
            padding: 15px 0;
            text-align: center;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 10px;
            display: inline-block;
        }

        .navbar a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .form-container {
            max-width: 1000px;
            margin: 100px auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            display: flex;
            min-height: 600px;
        }

        .info-section {
            background-color: #8b4513;
            color: white;
            padding: 40px;
            width: 40%;
            position: relative;
            overflow: hidden;
        }

        .info-section::after {
            content: '';
            position: absolute;
            top: 0;
            right: -50px;
            width: 100px;
            height: 100%;
            background-color: #8b4513;
            transform: skew(-10deg);
        }

        .info-section h2 {
            font-size: 2.5em;
            margin-bottom: 20px;
        }

        .info-section p {
            font-size: 1.2em;
            margin-bottom: 30px;
        }

        .form-section {
            width: 60%;
            padding: 40px;
            background: white;
        }

        /* New styles for tabs */
        .tabs {
            display: flex;
            margin-bottom: 30px;
            border-bottom: 2px solid #ddd;
        }

        .tab {
            padding: 10px 30px;
            cursor: pointer;
            border: none;
            background: none;
            font-size: 16px;
            color: #666;
            position: relative;
        }

        .tab.active {
            color: #8b4513;
        }

        .tab.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #8b4513;
        }

        .form-content {
            display: none;
        }

        .form-content.active {
            display: block;
        }

        .input-group {
            margin-bottom: 25px;
            position: relative;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            border: none;
            border-bottom: 2px solid #ddd;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .input-group input:focus {
            outline: none;
            border-bottom-color: #8b4513;
        }

        .input-group label {
            position: absolute;
            left: 0;
            top: 10px;
            color: #999;
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .input-group input:focus + label,
        .input-group input:valid + label {
            top: -20px;
            font-size: 12px;
            color: #8b4513;
        }

        .input-group i {
            position: absolute;
            right: 10px;
            top: 10px;
            color: #8b4513;
        }

        .btn {
            background-color: #8b4513;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #6d3610;
        }

        .error-message {
            color: red;
            margin-bottom: 15px;
        }

        .success-message {
            color: green;
            margin-bottom: 15px;
        }
    </style>
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
        <div class="info-section">
            <h2>WELCOME<br>BACK!</h2>
            <p>Where Quality<br>Livestock Meets<br>Demand</p>
        </div>

        <div class="form-section">
            <?php if($login_error): ?>
                <div class="error-message"><?php echo $login_error; ?></div>
            <?php endif; ?>
            <?php if($register_error): ?>
                <div class="error-message"><?php echo $register_error; ?></div>
            <?php endif; ?>
            <?php if($register_success): ?>
                <div class="success-message"><?php echo $register_success; ?></div>
            <?php endif; ?>

            <div class="tabs">
                <button class="tab active" onclick="showForm('login')">Login</button>
                <button class="tab" onclick="showForm('register')">Register</button>
            </div>

            <!-- Login Form -->
            <div class="form-content active" id="login-form">
                <form method="POST" action="">
                    <div class="input-group">
                        <input type="text" name="login_username" required>
                        <label>Username</label>
                        <i class='bx bxs-user'></i>
                    </div>

                    <div class="input-group">
                        <input type="password" name="login_password" required>
                        <label>Password</label>
                        <i class='bx bxs-lock-alt'></i>
                    </div>

                    <button type="submit" name="login" class="btn">Login</button>
                </form>
            </div>

            <!-- Registration Form -->
            <div class="form-content" id="register-form">
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="input-group">
                        <input type="text" name="username" required>
                        <label>Username</label>
                        <i class='bx bxs-user'></i>
                    </div>

                    <div class="input-group">
                        <input type="email" name="email" required>
                        <label>Email</label>
                        <i class='bx bxs-envelope'></i>
                    </div>

                    <div class="input-group">
                        <input type="password" name="password" required>
                        <label>Password</label>
                        <i class='bx bxs-lock-alt'></i>
                    </div>

                    <div class="input-group">
                        <input type="tel" name="phone" required>
                        <label>Phone Number</label>
                        <i class='bx bxs-phone'></i>
                    </div>

                    <div class="input-group">
                        <input type="text" name="location" required>
                        <label>Location</label>
                        <i class='bx bxs-map'></i>
                    </div>

                    <div class="input-group">
                        <input type="text" name="experience" required>
                        <label>Experience (years)</label>
                        <i class='bx bxs-briefcase'></i>
                    </div>

                    <div class="input-group">
                        <input type="text" name="specialty" required>
                        <label>Specialty</label>
                        <i class='bx bxs-star'></i>
                    </div>

                    <div class="input-group">
                        <input type="file" name="profile_image" accept="image/*">
                        <label>Profile Image</label>
                        <i class='bx bxs-image'></i>
                    </div>

                    <button type="submit" name="register" class="btn">Sign Up</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showForm(formType) {
            // Update tabs
            document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
            event.target.classList.add('active');
            
            // Update forms
            document.querySelectorAll('.form-content').forEach(form => form.classList.remove('active'));
            document.getElementById(formType + '-form').classList.add('active');
            
            // Update info section text
            const title = document.querySelector('.info-section h2');
            if (formType === 'login') {
                title.innerHTML = 'WELCOME<br>BACK!';
            } else {
                title.innerHTML = 'SIGN UP<br>WITH US!';
            }
        }
    </script>
</body>
</html>