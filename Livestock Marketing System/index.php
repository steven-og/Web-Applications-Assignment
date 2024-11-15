<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livestock Marketing</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f4f9;
            padding-top: 60px;
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

        .language-selector {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 2;
        }

        .language-selector select {
            padding: 8px 16px;
            background-color: #1f5437;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            font-size: 12px;
        }

        .main-header {
            text-align: center;
            padding: 20px 0;
        }

        .logo-image {
            max-width: 200px;
            height: auto;
        }

        .categories {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .categories h1 {
            background-color: #d4edda;
            color: #593c15;
            padding: 10px;
            text-align: center;
            border-radius: 8px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .categories h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin: 20px -15px;
        }

        .Categorie-col {
            flex: 0 0 calc(33.333% - 30px);
            margin: 15px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            overflow: hidden;
        }

        .Categorie-col img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .Categorie-col h3 {
            color: #2c3e50;
            padding: 15px;
            margin: 0;
            text-align: center;
            background-color: #f8f9fa;
        }

        .Hfooter {
            background-color: #8b4513;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }

        .form {
            display: none;
        }

        .form.active {
            display: block;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .Categorie-col {
                flex: 0 0 calc(50% - 30px);
            }
        }

        @media (max-width: 480px) {
            .Categorie-col {
                flex: 0 0 calc(100% - 30px);
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="#">Home</a>
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

        <!-- English version -->
        <div id="form-en" class="form active" lang="en">
            <div class="container">
                <header class="main-header">
                    <img class="logo-image" src="images/Logo.png" alt="Page logo">             
                </header> 
            </div>
            <section class="categories">
                <h1>Welcome to the Livestock Marketplace</h1>
                <h2>Find the best deals for buying, selling, and trading livestock.</h2>

                <div class="row">
                    <div class="Categorie-col">
                        <img src="images/Cow1.jpg" alt="Cows">
                        <h3>Cattle up for grabs</h3>
                    </div>
                    <div class="Categorie-col">
                        <img src="images/Pig1.jpg" alt="Pigs">
                        <h3>Pig for sale</h3>
                    </div>
                    <div class="Categorie-col">
                        <img src="images/Sheep1.jpg" alt="Sheep">
                        <h3>Sheep</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="Categorie-col">
                        <img src="images/Donkey2.jpg" alt="Donkey">
                        <h3>Donkey For sale</h3>
                    </div>
                    <div class="Categorie-col">
                        <img src="images/Horse1.jpg" alt="Horse">
                        <h3>Horse For sale</h3>
                    </div>
                    <div class="Categorie-col">
                        <img src="images/Chicken1.jpg" alt="Chicken">
                        <h3>Chicken for sale</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="Categorie-col">
                        <img src="images/Duck1.jpg" alt="Ducks">
                        <h3>Duck For sale</h3>
                    </div>
                    <div class="Categorie-col">
                        <img src="images/Chicken3.jpg" alt="Chicken and chicks">
                        <h3>Hen and it's chicks Up for grabs</h3>
                    </div>
                    <div class="Categorie-col">
                        <img src="images/Goat1.jpg" alt="Goat">
                        <h3>Breeding goat for sale</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="Categorie-col">
                        <img src="images/Horse2.jpg" alt="Horse">
                        <h3>Horse For sale</h3>
                    </div>
                    <div class="Categorie-col">
                        <img src="images/Pig2.jpg" alt="Pigs">
                        <h3>Pig up for grabs</h3>
                    </div>
                    <div class="Categorie-col">
                        <img src="images/Cow2.jpg" alt="Cow">
                        <h3>Cow for sale</h3>
                    </div>
                </div>
            </section>

            <footer class="Hfooter">
                <p>&copy; 2023 Livestock Marketplace. All rights reserved.</p>
            </footer>
        </div>

        <!-- Afrikaans version -->
        <div id="form-af" class="form" lang="af">
            <div class="container">
                <header class="main-header">
                    <img class="logo-image" src="images/Logo.png" alt="Page logo">             
                </header> 
            </div>
            <section class="categories">
                <h1>Welkom by die Veemark</h1>
                <h2>Vind die beste aanbiedinge vir koop, verkoop en ruil van vee.</h2>

                <div class="row">
                    <div class="Categorie-col">
                        <img src="images/Cow1.jpg" alt="Beeste">
                        <h3>Beeste beskikbaar</h3>
                    </div>
                    <div class="Categorie-col">
                        <img src="images/Pig1.jpg" alt="Varke">
                        <h3>Vark te koop</h3>
                    </div>
                    <div class="Categorie-col">
                        <img src="images/Sheep1.jpg" alt="Skape">
                        <h3>Skape</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="Categorie-col">
                        <img src="images/Donkey2.jpg" alt="Donkie">
                        <h3>Donkie te koop</h3>
                    </div>
                    <div class="Categorie-col">
                        <img src="images/Horse1.jpg" alt="Perd">
                        <h3>Perd te koop</h3>
                    </div>
                    <div class="Categorie-col">
                        <img src="images/Chicken1.jpg" alt="Hoender">
                        <h3>Hoender te koop</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="Categorie-col">
                        <img src="images/Duck1.jpg" alt="Eende">
                        <h3>Eend te koop</h3>
                    </div>
                    <div class="Categorie-col">
                        <img src="images/Chicken3.jpg" alt="Hoender en kuikens">
                        <h3>Hen en kuikens beskikbaar</h3>
                    </div>
                    <div class="Categorie-col">
                        <img src="images/Goat1.jpg" alt="Bok">
                        <h3>Teelbokkie te koop</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="Categorie-col">
                        <img src="images/Horse2.jpg" alt="Perd">
                        <h3>Perd te koop</h3>
                    </div>
                    <div class="Categorie-col">
                        <img src="images/Pig2.jpg" alt="Varke">
                        <h3>Vark beskikbaar</h3>
                    </div>
                    <div class="Categorie-col">
                        <img src="images/Cow2.jpg" alt="Koei">
                        <h3>Koei te koop</h3>
                    </div>
                </div>
            </section>

            <footer class="Hfooter">
                <p>&copy; 2023 Veemark. Alle regte voorbehou.</p>
            </footer>
        </div>
    </div>

    <script>
        function toggleLanguage() {
            const selectedLanguage = document.getElementById('language').value;
            const formEn = document.getElementById('form-en');
            const formAf = document.getElementById('form-af');
            
            formEn.classList.remove('active');
            formAf.classList.remove('active');
            
            if (selectedLanguage === 'en') {
                formEn.classList.add('active');
                formEn.style.display = 'block';
                formAf.style.display = 'none';
            } else {
                formAf.classList.add('active');
                formEn.style.display = 'none';
                formAf.style.display = 'block';
            }
        }
    </script>
</body>
</html>