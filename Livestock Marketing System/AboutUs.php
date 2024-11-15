<!DOCTYPE php>
<php lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Livestock Marketplace</title>
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

        .about-title {
            background-color: #d4edda;
            color: #593c15;
            padding: 10px;
            text-align: center;
            border-radius: 8px;
            font-weight: bold;
        }

        .language-toggle {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 8px 16px;
            background-color: #1f5437;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            font-size: 12px;
            z-index: 2;
        }

        section {
            margin: 20px auto;
            max-width: 600px;
        }

        .section-container {
            display: flex;
            align-items: flex-start;
            margin-top: 20px;
        }

        .text-content {
            flex: 1;
            margin-right: 20px;
        }

        .image-content {
            flex: 0 0 200px;
        }

        .image-content img {
            width: 100%;
            border-radius: 8px;
        }

        h2 {
            color: #2c3e50;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
        }

        /* Student Table Styles */
        .student-table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            border-radius: 8px;
            overflow: hidden;
        }

        .student-table th {
            background-color: #8b4513;
            color: white;
            padding: 12px;
            text-align: left;
        }

        .student-table td {
            padding: 12px;
            border-top: 1px solid #ddd;
        }

        .student-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .student-table tr:hover {
            background-color: #f5f5f5;
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
    <button class="language-toggle" onclick="toggleLanguage()">ENG</button>

    <header>
        <h1 class="about-title">About Us</h1>
    </header>

    <section id="content">
        <div class="section-container">
            <div class="text-content">
                <h2 id="mission-title"></h2>
                <p id="mission-text"></p>
            </div>
            <div class="image-content">
                <img src="images/Livestock.webp" alt="Mission Image">
            </div>
        </div>

        <div class="section-container">
            <div class="text-content">
                <h2 id="vision-title"></h2>
                <p id="vision-text"></p>
            </div>
            <div class="image-content">
                <img src="images/hq720.jpg" alt="Vision Image">
            </div>
        </div>

        <div class="section-container">
            <div class="text-content">
                <h2 id="contact-title"></h2>
                <p id="contact-email"></p>
                <p id="contact-phone"></p>
                <p id="contact-address"></p>
                <p id="contact-hours"></p>
            </div>
        </div>

        <div class="section-container">
            <div class="text-content">
                <h2 id="team-title"></h2>
                <table class="student-table">
                    <thead>
                        <tr>
                            <th>Student Number</th>
                            <th>Name</th>
                            <th>Contribution</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>223005509</td>
                            <td>Stefanus Faustinus</td>
                            <td>Frontend Development, UI/UX Design</td>
                        </tr>
                        <tr>
                            <td>223071781</td>
                            <td>Reben-moi Mbambi</td>
                            <td>Backend Development, Database Management</td>
                        </tr>
                        <tr>
                            <td>223068772</td>
                            <td>Matti Ndeshitila</td>
                            <td>Authentication System, Security Implementation</td>
                        </tr>
                        <tr>
                            <td>223098523</td>
                            <td>Nakaleke Fillemon</td>
                            <td>Product Listing Module, Search Functionality</td>
                        </tr>
                        <tr>
                            <td>220056789</td>
                            <td>Michael Brown</td>
                            <td>Chat System, Notification Services</td>
                        </tr>
                        <tr>
                            <td>220067890</td>
                            <td>Emma Davis</td>
                            <td>Testing, Documentation, Quality Assurance</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <script>
        const content = {
            en: {
                missionTitle: "Mission",
                missionText: "To create a transparent and trusted marketplace that connects livestock farmers and buyers worldwide, promoting ethical and sustainable practices in livestock trading.",
                visionTitle: "Vision",
                visionText: "To be the leading global platform for livestock trading, fostering a community where ethical standards, sustainability, and innovation drive the future of livestock commerce.",
                contactTitle: "Contact Details",
                contactEmail: "Email: support@livestockmarketplace.com",
                contactPhone: "Phone: 061255 244",
                contactAddress: "Office Address: 1000 Brahms Street, Windhoek West, Windhoek",
                contactHours: "Customer Support Hours: Monday – Friday: 9:00 AM – 6:00 PM (CST), Saturday: 10:00 AM – 4:00 PM (CST), Closed on Sundays and Public Holidays",
                missionImage: "Livestock.webp",
                visionImage: "hq720.jpg",
                teamTitle: "Development Team"
            },
            af: {
                missionTitle: "Missie",
                missionText: "Om 'n deursigtige en vertroude mark te skep wat veeboere en kopers wêreldwyd verbind en etiese en volhoubare praktyke in veemark verbruik bevorder.",
                visionTitle: "Visie",
                visionText: "Om die toonaangewende globale platform vir veemarkverhandeling te wees, 'n gemeenskap te bevorder waar etiese standaarde, volhoubaarheid en innovasie die toekoms van veemarkverhandeling dryf.",
                contactTitle: "Kontakbesonderhede",
                contactEmail: "E-pos: support@livestockmarketplace.com",
                contactPhone: "Telefoon: 061255 244",
                contactAddress: "Kantoor Adres: 1000 Brahms Strasse, Windhoek West, Windhoek",
                contactHours: "Kliëntediens Ure: Maandag – Vrydag: 9:00 VM – 6:00 NM (CST), Saterdag: 10:00 VM – 4:00 NM (CST), Gesluit op Sondae en Openbare Vakansiedae",
                missionImage: "Livestock.webp",
                visionImage: "hq720.jpg",
                teamTitle: "Ontwikkelingspan"
            }
        };

        let currentLang = 'en';

        function toggleLanguage() {
            currentLang = currentLang === 'en' ? 'af' : 'en';
            loadContent();
            document.querySelector('.language-toggle').innerText = currentLang === 'en' ? 'ENG' : 'AFR';
        }

        function loadContent() {
            document.getElementById("mission-title").innerText = content[currentLang].missionTitle;
            document.getElementById("mission-text").innerText = content[currentLang].missionText;
            document.getElementById("vision-title").innerText = content[currentLang].visionTitle;
            document.getElementById("vision-text").innerText = content[currentLang].visionText;
            document.getElementById("contact-title").innerText = content[currentLang].contactTitle;
            document.getElementById("contact-email").innerText = content[currentLang].contactEmail;
            document.getElementById("contact-phone").innerText = content[currentLang].contactPhone;
            document.getElementById("contact-address").innerText = content[currentLang].contactAddress;
            document.getElementById("contact-hours").innerText = content[currentLang].contactHours;
            document.getElementById("team-title").innerText = content[currentLang].teamTitle;
        }

        loadContent();
    </script>
</body>
</php>