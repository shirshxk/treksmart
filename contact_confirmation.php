<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed!</title>
    <link rel="stylesheet" href="/TrekSmart/public/style.css">
</head>
<body class="confirmation-page">
    <?php
    require 'db.php';
    include 'header.php';
    ?>
    <div class="confirmation">
        <h1>Message <span class="highlight">Delivered!</span></h1>
        <p>Thank you for reaching us, we will get right back to you!</p>
    </div>


    <?php include 'footer.php'; ?>
</body>
<script src="/TrekSmart/JS/script.js"></script>
</html>
