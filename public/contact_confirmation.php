<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed!</title>
    <!-- Link to the CSS file -->
    <link rel="stylesheet" href="/TrekSmart/assets/CSS/style.css">
</head>
<body class="confirmation-page">
    <?php
    // Include the database connection and header
    require '../includes/db.php';
    include '../includes/header.php';
    ?>
    <div class="confirmation">
        <h1>Message <span class="highlight">Delivered!</span></h1>
        <p>Thank you for reaching out, we will get back to you shortly!</p>
    </div>
    <?php
    // Include the footer
    include '../includes/footer.php';
    ?>
</body>
<!-- Link to the JavaScript file -->
<script src="/TrekSmart/assets/JS/script.js"></script>
</html>
