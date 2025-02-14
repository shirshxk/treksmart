<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Please Login!</title>
    <link rel="icon" type="image/png" href="/TrekSmart/assets/logo/favicon.png">
    <!-- Link to the CSS file -->
    <link rel="stylesheet" href="/TrekSmart/assets/CSS/style.css">
</head>
<body class="confirmation-page">
    <?php
    // Connect to the database and include the header component
    require '../includes/db.php';
    include '../includes/header.php';
    ?>
    <div class="confirmation">
        <h1><span class="highlight-red">404 error</span></h1>
        <p>You need to be logged in first to use this feature!</p>
    </div>
    <?php 
    // Include the footer component
    include '../includes/footer.php'; 
    ?>
</body>
<!-- Link to the JavaScript file -->
<script src="/TrekSmart/assets/JS/script.js"></script>
</html>
<<<<<<< HEAD
=======


>>>>>>> ea3dcc8 (update)
