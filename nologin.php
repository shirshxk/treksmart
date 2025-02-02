<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Please Login!</title>
    <link rel="stylesheet" href="/TrekSmart/public/style.css">
</head>
<body class="confirmation-page">
    <?php
    require 'db.php';
    include 'header.php';
    ?>
    <div class="confirmation">
        <h1><span class="highlight-red">404 error</span></h1>
        <p>You need to be logged in first to use this feature!</p>
    </div>

    <?php include 'footer.php'; ?>
</body>
<script src="/TrekSmart/JS/script.js"></script>
</html>
