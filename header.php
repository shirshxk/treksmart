<?php
// Start session
session_start();
require 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrekSmart</title>
    <link rel="stylesheet" href="/TrekSmart/public/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<nav>
    <div class="logo">
        <img src="/TrekSmart/assets/logo/logowhite.png" alt="TrekSmart Logo">
    </div>
    <div class="nav-wrapper">
        <ul>
            <li><a href="/TrekSmart/index.php">Home</a></li>
            <li><a href="/public/about.php">About</a></li>
            <li><a href="/public/trekking.php">Trekking</a></li>
            <li><a href="/public/shop.php">Shop</a></li>
            <li><a href="/public/blog.php">Blog</a></li>
            <li><a href="/public/contact.php">Contact</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="/TrekSmart/logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="/TrekSmart/login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
