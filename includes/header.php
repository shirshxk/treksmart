<?php
// Include the database connection and start the session if not already started
require 'db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Determine the current page's filename
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!-- Navigation Bar -->
<nav>
    <div class="logo">
        <img src="/TrekSmart/assets/logo/logowhite.png" alt="TrekSmart Logo">
    </div>
    <div class="nav-wrapper">
        <ul>
            <li>
                <a href="/TrekSmart/public/index.php" class="<?= ($currentPage == 'index.php') ? 'active' : '' ?>">Home</a>
            </li>
            <li>
                <a href="/TrekSmart/public/about.php" class="<?= ($currentPage == 'about.php') ? 'active' : '' ?>">About</a>
            </li>
            <li>
                <a href="/TrekSmart/public/trekking.php" class="<?= ($currentPage == 'trekking.php') ? 'active' : '' ?>">Trekking</a>
            </li>
            <li>
                <a href="/TrekSmart/public/blogs.php" class="<?= ($currentPage == 'blogs.php') ? 'active' : '' ?>">Blog</a>
            </li>
            <li>
                <a href="/TrekSmart/public/contact.php" class="<?= ($currentPage == 'contact.php') ? 'active' : '' ?>">Contact</a>
            </li>
            <?php if (isset($_SESSION['username'])): ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">
                        <img src="<?= isset($_SESSION['avatar']) ? $_SESSION['avatar'] : '/TrekSmart/assets/image/default-avatar.png'; ?>" 
                             alt="Avatar" class="user-avatar">
                        <?= strtoupper($_SESSION['username']); ?> ▼
                    </a>
                    <ul class="dropdown-menu">
                        <?php if ($_SESSION['admin_check'] == 1): ?>
                            <li><a href="/TrekSmart/admin/admin_dashboard.php">Admin Dashboard</a></li>
                        <?php else: ?>
                            <li><a href="/TrekSmart/user/user_dashboard.php">User Dashboard</a></li>
                        <?php endif; ?>
                        <li><a href="/TrekSmart/auth/logout.php">Logout</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <li><a href="/TrekSmart/auth/login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
