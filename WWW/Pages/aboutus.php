<?php
session_start();

// Use @ to suppress warnings for session variables
if (@$_SESSION["userid"] == "") {
    $_SESSION["userid"] = "";
    $_SESSION["username"] = "";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About Us - GalaExtremists</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../css/styles.css">
<link rel="stylesheet" href="../css/mobile_fix.css">

<style>
/* Page specific styles */
body {
    background: #f9f9f9;
}

.hero-section {
    background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('../images/pic1.jpg');
    background-size: cover;
    background-position: center;
    height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-align: center;
    border-radius: 0 0 50px 50px;
    margin-bottom: 60px;
}

.hero-section h1 {
    font-size: 56px;
    font-weight: 800;
    margin-bottom: 10px;
}

.hero-section p {
    font-size: 20px;
    opacity: 0.9;
    max-width: 600px;
    margin: 0 auto;
}

.content-container {
    width: 90%;
    max-width: 1000px;
    margin: 0 auto;
}

.section {
    margin-bottom: 80px;
}

.section-title {
    font-size: 32px;
    font-weight: 700;
    color: #333;
    margin-bottom: 24px;
    position: relative;
    padding-left: 20px;
}

.section-title::before {
    content: '';
    position: absolute;
    left: 0;
    top: 5px;
    bottom: 5px;
    width: 5px;
    background: #7C3AED;
    border-radius: 10px;
}

.text-block {
    font-size: 16px;
    line-height: 1.8;
    color: #555;
    text-align: justify;
}

.story-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    align-items: center;
}

.story-img {
    width: 100%;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
}

.value-card {
    background: white;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    border-top: 5px solid #7C3AED;
    transition: 0.3s;
}

.value-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.value-card h3 {
    font-size: 20px;
    margin-bottom: 12px;
    color: #333;
}

.team-grid {
    display: flex;
    gap: 30px;
    overflow-x: auto;
    padding-bottom: 20px;
}

.team-card {
    min-width: 250px;
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.team-img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    background: #eee;
}

.team-info {
    padding: 20px;
    text-align: center;
}

.team-name {
    font-weight: 700;
    font-size: 18px;
    color: #333;
}

.team-role {
    color: #7C3AED;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 10px;
}

.stat-box {
    text-align: center;
    background: white;
    padding: 40px;
    border-radius: 20px;
    margin-top: 60px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}

.stat-number {
    font-size: 48px;
    font-weight: 800;
    color: #7C3AED;
}

.stat-label {
    font-size: 16px;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 1px;
}
</style>
</head>
<body>

<!-- Navigation Bar -->
<!-- Navigation Bar -->
<?php require_once "../otherreqs/navigationbar.php"; ?>

<!-- Hero Header -->
<div class="hero-section">
    <div>
        <h1>About Us</h1>
        <p>Redefining how you discover the Philippines, one island at a time.</p>
    </div>
</div>

<div class="content-container">

    <!-- Our Story -->
    <div class="section story-grid">
        <div>
            <div class="section-title">Our Story</div>
            <div class="text-block">
                <p>The world is vast, filled with horizons that stretch beyond the imagination. It began not as a business, but as a pact between friends under a starlit sky in Palawan. We looked out at the endless sea and realized that the true essence of travel wasn't just about reaching a destination—it was about the chaos, the laughter, and the unexpected detours along the way.</p>
                <br>
                <p>Thus, <strong>GalaExtremist</strong> was forged. We are the guild for the restless, a beacon for those who seek to conquer every island and traverse every hidden path. Our mission is to equip fellow adventurers with the ultimate tool to chart their own legends. No more lost maps or forgotten bookings; only the pure thrill of the journey ahead.</p>
                <br>
                <p>We are not mere guides; we are fellow travelers on this grand expedition. Every resort listed, every activity curated, is a chapter in a story waiting to be written by you. The world is calling, and the adventure of a lifetime awaits those brave enough to answer.</p>
            </div>
        </div>
        <div>
            <!-- Using a generic travel image from the existing assets -->
            <img src="../images/pic2.jpg" class="story-img" alt="Our Journey">
        </div>
    </div>

    <!-- Mission & Vision -->
    <div class="section">
        <div class="values-grid">
            <div class="value-card">
                <h3>Our Mission</h3>
                <p>To simplify travel in the Philippines by connecting adventurers with trusted local businesses, making every "Gala" stress-free and memorable.</p>
            </div>
            <div class="value-card">
                <h3>Our Vision</h3>
                <p>To be the number one go-to platform for domestic tourism, showcasing the beauty of our 7,641 islands to the rest of the world.</p>
            </div>
            <div class="value-card">
                <h3>The "Extremist" Promise</h3>
                <p>We go to the extreme to ensure quality. If a place isn't up to our standards, it doesn't make it to our list. Period.</p>
            </div>
        </div>
    </div>


    <!-- Stats -->
    <div class="section">
        <div class="values-grid">
            <div class="stat-box">
                <div class="stat-number">30+</div>
                <div class="stat-label">Partner Hotels</div>
            </div>
            <div class="stat-box">
                <div class="stat-number">10k+</div>
                <div class="stat-label">Happy Travelers</div>
            </div>
            <div class="stat-box">
                <div class="stat-number">100%</div>
                <div class="stat-label">Filipino Owned</div>
            </div>
        </div>
    </div>

</div>

<!-- Footer -->
<?php require_once "../otherreqs/footerdetails.php"; ?>

</body>
</html>
