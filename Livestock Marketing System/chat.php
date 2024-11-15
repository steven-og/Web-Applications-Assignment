<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "marketplacedb";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Get conversation partner's ID from URL
$partner_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

// Fetch partner's username
$stmt = $pdo->prepare("SELECT username FROM users WHERE user_id = ?");
$stmt->execute([$partner_id]);
$partner = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livestock Chat Page</title>
    <style>
        body {             
            background-color: #f5f5dc;             
            font-family: Arial, sans-serif;             
            margin: 0;             
            padding: 0;             
            display: flex;             
            justify-content: center;             
            align-items: center;             
            height: 100vh;         
        }
        
        .navbar {
            background-color: #8b4513; 
            padding: 10px;
            text-align: center;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            display: inline-block;
        }

        .navbar a:hover {
            background-color: rgb(120, 60, 38);
        }

        .navbar a:visited {
            color: lightgray;
        }
        
        .chat-container {             
            width: 100%;             
            max-width: 600px;             
            border: 2px solid #8b4513;             
            border-radius: 10px;             
            overflow: hidden;             
            background-color: #fff;             
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);             
            margin-top: 60px;        
        }          
        
        .chat-header {             
            background-color: #a0522d;             
            color: white;             
            padding: 10px;             
            text-align: center;             
            font-size: 20px;             
            font-weight: bold;         
        }          
        
        .chat-messages {             
            height: 300px;             
            overflow-y: auto;             
            padding: 15px;             
            background-color: #f8f4e3;         
        }          
        
        .chat-input-area {             
            display: flex;             
            border-top: 1px solid #8b4513;         
        }          
        
        .chat-input {             
            flex: 1;             
            padding: 10px;             
            border: none;             
            outline: none;             
            font-size: 16px;         
        }          
        
        .send-button {             
            background-color: #8b4513;             
            color: white;             
            border: none;             
            padding: 10px 20px;             
            cursor: pointer;             
            font-size: 16px;         
        }          
        
        .send-button:hover {             
            background-color: #a0522d;         
        }          
        
        .quick-buttons {             
            display: flex;             
            gap: 10px;             
            justify-content: space-around;             
            padding: 10px;             
            border-top: 1px solid #8b4513;             
            background-color: #f8f4e3;         
        }          
        
        .quick-buttons button {             
            background-color: #d2b48c;             
            border: none;             
            padding: 10px;             
            font-size: 14px;             
            cursor: pointer;             
            border-radius: 5px;         
        }          
        
        .quick-buttons button:hover {             
            background-color: #e0c097;         
        }          
        
        .message {             
            margin: 10px 0;             
            padding: 10px;             
            border-radius: 5px;             
            background-color: #d2b48c;         
        }          
        
        .message.you {             
            background-color: #e0c097;             
            text-align: right;         
        }     

        .message-time {
            font-size: 0.8em;
            color: #666;
            margin-top: 5px;
        }
        .message.received {
            background-color: #f0e6d9;
            text-align: left;
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

    <div class="chat-container">
        <div class="chat-header">
            <span id="chatTitle">Chat with <?php echo htmlspecialchars($partner['username'] ?? 'User'); ?></span>
            <select id="languageSelect" onchange="toggleLanguage()">
                <option value="en">English</option>
                <option value="af">Afrikaans</option>
            </select>
        </div>

        <div class="chat-messages" id="chatMessages"></div>

        <div class="chat-input-area">
            <input type="text" id="chatInput" class="chat-input" placeholder="Type your message..." autocomplete="off">
            <button class="send-button" id="sendButton">Send</button>
        </div>

        <div class="quick-buttons" id="quickButtons">
            <button onclick="quickResponse('Is this available?')">Is this available?</button>
            <button onclick="quickResponse('Where are you?')">Where are you?</button>
            <button onclick="quickResponse('Any room for negotiations?')">Any room for negotiations?</button>
        </div>
    </div>

    <script>
        const chatBox = document.getElementById('chatMessages');
        const messageInput = document.getElementById('chatInput');
        const sendButton = document.getElementById('sendButton');
        const currentUserId = <?php echo $_SESSION['user_id']; ?>;
        const partnerId = <?php echo $partner_id; ?>;
        let lastMessageId = 0;

        const translations = {
            en: {
                title: "Chat with <?php echo htmlspecialchars($partner['username'] ?? 'User'); ?>",
                placeholder: "Type your message...",
                sendButton: "Send",
                quickButtons: ["Is this available?", "Where are you?", "Any room for negotiations?"]
            },
            af: {
                title: "Gesels met <?php echo htmlspecialchars($partner['username'] ?? 'Gebruiker'); ?>",
                placeholder: "Tik jou boodskap...",
                sendButton: "Stuur",
                quickButtons: ["Is dit beskikbaar?", "Waar is jy?", "Is daar ruimte vir onderhandelinge?"]
            }
        };

        // Load initial messages
        loadMessages();

        // Poll for new messages every 3 seconds
        setInterval(loadMessages, 3000);

        function loadMessages() {
            fetch(`get_messages.php?partner_id=${partnerId}&last_id=${lastMessageId}`)
                .then(response => response.json())
                .then(messages => {
                    messages.forEach(message => {
                        if (message.message_id > lastMessageId) {
                            displayMessage(message.content, 
                                        message.user_id == currentUserId ? 'you' : 'received',
                                        message.sent_at);
                            lastMessageId = message.message_id;
                        }
                    });
                })
                .catch(error => console.error('Error loading messages:', error));
        }

        function sendMessage() {
            const messageText = messageInput.value.trim();
            if (messageText) {
                const formData = new FormData();
                formData.append('partner_id', partnerId);
                formData.append('message', messageText);

                fetch('send_message.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        messageInput.value = '';
                        loadMessages();
                    } else {
                        console.error('Error sending message:', data.error);
                    }
                })
                .catch(error => console.error('Error sending message:', error));
            }
        }

        function displayMessage(text, className = '', timestamp = '') {
            const message = document.createElement('div');
            message.classList.add('message', className);
            
            const content = document.createElement('div');
            content.textContent = text;
            message.appendChild(content);
            
            if (timestamp) {
                const time = document.createElement('div');
                time.classList.add('message-time');
                time.textContent = new Date(timestamp).toLocaleTimeString();
                message.appendChild(time);
            }
            
            chatBox.appendChild(message);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function quickResponse(text) {
            messageInput.value = text;
            sendMessage();
        }

        function toggleLanguage() {
            const lang = languageSelect.value;
            const translation = translations[lang];
            
            document.getElementById('chatTitle').textContent = translation.title;
            messageInput.placeholder = translation.placeholder;
            sendButton.textContent = translation.sendButton;
            
            const quickButtonsContainer = document.getElementById('quickButtons');
            Array.from(quickButtonsContainer.children).forEach((button, index) => {
                button.textContent = translation.quickButtons[index];
            });
        }

        messageInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });

        sendButton.addEventListener('click', sendMessage);
    </script>
</body>
</html>