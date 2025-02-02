<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="/TrekSmart/public/style.css">
</head>
<body class="cont">
    <?php
    require 'db.php';
    include 'header.php';
    ?>

    <main class="contact-page">
        <div class="container">
            <h1>Enquiry</h1>
            <p class="highlight">Please let us know if you have any queries.</p>
            <form id="contactForm" action="submit_contact.php" method="POST">
                <div class="contact-form-group">
                    <label for="first_name">First Name *</label>
                    <input type="text" id="first_name" name="first_name" placeholder="First Name" required>
                </div>
                <div class="contact-form-group">
                    <label for="last_name">Last Name *</label>
                    <input type="text" id="last_name" name="last_name" placeholder="Last Name" required>
                </div>
                <div class="contact-form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" placeholder="Email Address" required>
                </div>
                <div class="contact-form-group">
                    <label for="phone_number">Phone Number *</label>
                    <input type="text" id="phone_number" name="phone_number" placeholder="Phone Number" required>
                </div>
                <div class="contact-form-group">
                    <label for="address">Address *</label>
                    <input type="text" id="address" name="address" placeholder="Current Address" required>
                </div>
                <div class="contact-form-group">
                    <label for="title">Title *</label>
                    <input type="text" id="title" name="title" placeholder="Title of the Message" required>
                </div>
                <div class="contact-form-group">
                    <label for="message">Message *</label>
                    <textarea id="message" name="message" placeholder="Enter your message" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn-primary">Send</button>
            </form>

        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
<script src="/TrekSmart/JS/script.js"></script>
</html>
