<?php
session_start();

// Use @ to suppress warnings
if (@$_SESSION["userid"] == "") {
    $_SESSION["userid"] = "";
    $_SESSION["username"] = "";
}

// Simple form handling simulation
$submitted = 0;
if (@$_POST["send_message"] != "") {
    $submitted = 1;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us - GalaExtremists</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../css/styles.css">
<link rel="stylesheet" href="../css/mobile_fix.css">

<style>
body { background: #f9f9f9; }
/* * { box-sizing: border-box; } - Removed to use global styles */

.contact-hero {
    background: #7C3AED;
    height: 340px; /* Increased height for better spacing */
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
    border-radius: 0 0 50px 50px;
    padding-bottom: 40px; /* Push text up slightly */
}

.contact-hero h1 { font-size: 48px; font-weight: 800; }
.contact-hero p { font-size: 18px; opacity: 0.8; }

.main-container {
    width: 90%;
    max-width: 1100px;
    margin: -80px auto 60px auto;
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 40px;
}

.info-card {
    background: #2D2D2D;
    color: white;
    padding: 50px; /* Matched with form-card for heading alignment */
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    height: fit-content;
}

.info-item {
    margin-bottom: 30px;
}

.info-label {
    font-size: 14px;
    text-transform: uppercase;
    color: #bbb;
    margin-bottom: 8px;
    letter-spacing: 1px;
}

.info-value {
    font-size: 18px;
    font-weight: 600;
}

.form-card {
    background: white;
    padding: 50px;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.05);
}

.form-group {
    margin-bottom: 24px;
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #444;
}

.form-group input, .form-group textarea {
    width: 100%;
    padding: 16px;
    border: 1px solid #ddd;
    border-radius: 12px;
    font-size: 16px;
    transition: 0.3s;
    background: #fdfdfd;
}

.form-group input:focus, .form-group textarea:focus {
    border-color: #7C3AED;
    outline: none;
    box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.1);
}

.send-btn {
    background: linear-gradient(135deg, #7C3AED 0%, #6D28D9 100%);
    color: white;
    border: none;
    padding: 16px 32px;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    width: 100%;
    transition: 0.3s;
}

.send-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(124, 58, 237, 0.3);
}

.faq-section {
    max-width: 800px;
    margin: 60px auto;
    padding: 0 20px;
}

.faq-title {
    text-align: center;
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 40px;
    color: #333;
}

.faq-item {
    background: white;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    border: 1px solid #f1f1f1;
}

.faq-question {
    font-weight: 700;
    color: #333;
    font-size: 18px;
    margin-bottom: 10px;
}

.faq-answer {
    color: #666;
    line-height: 1.6;
}

.success-message {
    background: #d1fae5;
    color: #065f46;
    padding: 20px;
    border-radius: 12px;
    text-align: center;
    margin-bottom: 20px;
    font-weight: 600;
}

@media(max-width: 800px) {
    .main-container {
        grid-template-columns: 1fr;
        margin-top: -40px;
    }
}
</style>
</head>
<body>

<!-- Navigation Bar -->
<!-- Navigation Bar -->
<?php require_once "../otherreqs/navigationbar.php"; ?>

<!-- Hero -->
<div class="contact-hero">
    <div>
        <h1>Get in Touch</h1>
        <p>Questions? Collaborations? Or just want to say hi?</p>
    </div>
</div>

<div class="main-container">

    <!-- Contact Info Side -->
    <div class="info-card">
        <h2 style="margin-bottom:30px;">Contact Information</h2>
        
        <div class="info-item">
            <div class="info-label">Office Address</div>
            <div class="info-value">Unit 402, High Street Corporate Plaza,<br>Bonifacio Global City, Taguig 1634</div>
        </div>

        <div class="info-item">
            <div class="info-label">Email Us</div>
            <div class="info-value">support@galaextremist.ph</div>
            <div class="info-value">partners@galaextremist.ph</div>
        </div>

        <div class="info-item">
            <div class="info-label">Call Us</div>
            <div class="info-value">(02) 8-123-4567</div>
            <div class="info-value">+63 917 123 4567</div>
        </div>

        <div class="info-item">
            <div class="info-label">Operating Hours</div>
            <div class="info-value">Monday - Friday</div>
            <div style="color:#ccc;">9:00 AM - 6:00 PM</div>
        </div>
    </div>

    <!-- Contact Form Side -->
    <div class="form-card">
        <?php if ($submitted == 1) { ?>
            <div class="success-message">
                Thanks for reaching out! We've received your message and our team will get back to you within 24 hours.
            </div>
            <a href="start.php" class="send-btn" style="text-align:center;display:block;text-decoration:none;">Back to Home</a>
        <?php } else { ?>
            <h2 style="margin-bottom:10px;color:#333;">Send us a specific inquiry</h2>
            <p style="margin-bottom:30px;color:#666;">Fill out the form below and we'll help you plan your next adventure.</p>

            <form method="POST">
                <input type="hidden" name="send_message" value="1">
                
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="fullname" placeholder="John Doe" required>
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="john@example.com" required>
                </div>

                <div class="form-group">
                    <label>Subject</label>
                    <select name="subject" style="width:100%;padding:16px;border:1px solid #ddd;border-radius:12px;background:#fdfdfd;">
                        <option>General Inquiry</option>
                        <option>Booking Issue</option>
                        <option>Business Partnership</option>
                        <option>Report a Bug</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Message</label>
                    <textarea name="message" rows="5" placeholder="How can we help you today?" required></textarea>
                </div>

                <button type="submit" class="send-btn">Send Message</button>
            </form>
        <?php } ?>
    </div>

</div>

<!-- FAQ Section -->
<div class="faq-section">
    <div class="faq-title">Frequently Asked Questions</div>

    <div class="faq-item">
        <div class="faq-question">How do I cancel a booking?</div>
        <div class="faq-answer">You can cancel any booking by going to your "My Bookings" page. Please note that cancellations made less than 24 hours before the date may be subject to a fee.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">Do you accept GCash?</div>
        <div class="faq-answer">Yes! We accept GCash, Maya, and all major credit/debit cards through our secure payment gateway.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">Is my personal data safe?</div>
        <div class="faq-answer">Absolutely. We use industry-standard encryption to protect your data. We never sell your information to third parties.</div>
    </div>
</div>

<!-- Footer -->
<?php require_once "../otherreqs/footerdetails.php"; ?>

</body>
</html>
