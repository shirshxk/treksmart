<?php
// Include the database connection file
require '../includes/db.php';

// Process the login form when submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query the database for the given username
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt  = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // If a matching user is found, verify the password
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['admin_check'] = $user['admin_check'];
            $_SESSION['avatar'] = $user['avatar'];

            // Redirect to the landing page
            header('Location: ../public/index.php');
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No account found with this username.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TrekSmart</title>
    <link rel="icon" type="image/png" href="/TrekSmart/assets/logo/favicon.png">
    <!-- Link to the CSS file -->
    <link rel="stylesheet" href="/TrekSmart/assets/CSS/style.css">
</head>
<body>
    <main class="auth-page">
        <div class="auth-container">
            <!-- Left side: Background image with logo and caption -->
            <div class="auth-left" style="background-image: url('/TrekSmart/assets/image/bg-login.webp');">
                <img src="/TrekSmart/assets/logo/logowhite.png" alt="TrekSmart Logo">
                <div class="auth-caption">
                    <h2>Capturing <b class="highlight">Moments</b>, Creating <b class="highlight">Memories</b></h2>
                </div>
            </div>

            <!-- Right side: Login form -->
            <div class="auth-right">
                <h2>Login</h2>
                <?php if (isset($error)): ?>
                    <p class="error"><?php echo $error; ?></p>
                <?php endif; ?>
                <form action="login.php" method="POST">
                    <div class="form-group">
                        <input type="text" id="username" name="username" placeholder="Enter your username" required>
                    </div>
                    <div class="form-group">
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" class="btn-primary">Login</button>
                </form>
                <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
            </div>
        </div>
    </main>

    <!-- Link to the JavaScript file -->
    <script src="/TrekSmart/assets/JS/script.js"></script>
</body>
</html>
