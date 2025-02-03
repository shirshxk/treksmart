<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];        
            $_SESSION['username'] = $user['username'];  
            $_SESSION['admin_check'] = $user['admin_check']; 
            $_SESSION['avatar'] = $user['avatar'];

            header('Location: index.php');
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


<link rel="stylesheet" href="/TrekSmart/public/style.css">

<main class="auth-page">
    <div class="auth-container">
        <!-- Left Side with Background Image -->
        <div class="auth-left" style="background-image: url('/TrekSmart/assets/image/bg-login.webp');">
            <img src="/TrekSmart/assets/logo/logowhite.png" alt="TrekSmart Logo">
            <div class="auth-caption">
                <h2>Capturing <b class="highlight">Moments</b>, Creating <b class="highlight">Memories</b></h2>
            </div>
        </div>

        <!-- Right Side with Login Form -->
        <div class="auth-right">
            <h2>Login</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <!-- username Field -->
                <div class="form-group">
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                </div>
                <!-- Password Field -->
                <div class="form-group">
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <!-- Submit Button -->
                <button type="submit" class="btn-primary">Login</button>
            </form>
            <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
        </div>
    </div>
</main>
<script src="/TrekSmart/JS/script.js"></script>
</body>