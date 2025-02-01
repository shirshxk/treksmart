<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Server-side password validation
    $passwordPattern = '/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
    if (!preg_match($passwordPattern, $password)) {
        $error = "Password must be at least 8 characters long, contain at least one uppercase letter, one number, and one special character.";
    } else {
        // Password is valid, hash it
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        try {
            // Check if the email already exists
            $checkQuery = "SELECT email FROM users WHERE email = ?";
            $stmt = $conn->prepare($checkQuery);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error = "An account with this email already exists. Please use another email or log in.";
            } else {
                // Insert the new user
                $query = "INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssss", $firstname, $lastname, $email, $hashedPassword);

                if ($stmt->execute()) {
                    header('Location: login.php');
                    exit;
                } else {
                    $error = "Signup failed. Please try again.";
                }
            }
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            $error = "Something went wrong. Please try again later.";
        }
    }
}
?>


<link rel="stylesheet" href="/TrekSmart/public/style.css">
<main class="auth-page">
    <div class="auth-container">
        <div class="auth-left" style="background-image: url('/TrekSmart/assets/image/bg-login.webp');">
            <img src="/TrekSmart/assets/logo/logowhite.png" alt="TrekSmart Logo">
            <div class="auth-caption">
                <h2>Capturing <b class="highlight">Moments</b>, Creating <b class="highlight">Memories</b></h2>
            </div>
        </div>
        <div class="auth-right">
            <h2>Create an Account</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <form action="signup.php" method="POST">
                <!-- Row for First and Last Name -->
                <div class="form-group">
                    <input type="text" id="firstname" name="firstname" placeholder="First Name" required>
                    <input type="text" id="lastname" name="lastname" placeholder="Last Name" required>
                </div>
                <!-- Email Field -->
                <div class="form-group">
                    <input type="email" id="email" name="email" placeholder="Email" required>
                </div>
                <!-- Password Field -->
                <div class="form-group">
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
    
                </div>
                <small id="passwordHelp" class="helper-text"></small>
                <!-- Checkbox -->
                <div class="checkbox-container">
                    <input type="checkbox" id="terms" required>
                    <label for="terms">I agree to the <a href="#">Terms & Conditions</a></label>
                </div>
                <!-- Submit Button -->
                <button type="submit" class="btn-primary">Create account</button>
            </form>
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>
</main>

<script src="/TrekSmart/JS/script.js"></script>
