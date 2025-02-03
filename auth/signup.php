<?php
// Include the database connection file
require '../includes/db.php';

// Process the signup form when submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $lastname  = $_POST['lastname'];
    $email     = $_POST['email'];
    $password  = $_POST['password'];
    $username  = $_POST['username'];

    // Validate password and username
    $passwordPattern = '/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
    if (!preg_match($passwordPattern, $password)) {
        $error = "Password must be at least 8 characters, contain one uppercase letter, one number, and one special character.";
    } elseif (empty($username) || strlen($username) < 3) {
        $error = "Username must be at least 3 characters long.";
    } else {
        // Set a default avatar, then update if a profile picture is uploaded
        $avatar = '/TrekSmart/assets/image/default-avatar.png';
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
            // Build the absolute file system path using DOCUMENT_ROOT
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/TrekSmart/uploads/';
            // Create a unique filename
            $fileName = time() . '_' . basename($_FILES['profile_picture']['name']);
            $targetPath = $uploadDir . $fileName;
            
            // Attempt to move the uploaded file
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetPath)) {
                // Store the public URL (relative to your web root)
                $avatar = '/TrekSmart/uploads/' . $fileName;
            }
        }

        // Hash the password using BCRYPT
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        try {
            // Check if email or username already exists
            $checkQuery = "SELECT email, username FROM users WHERE email = ? OR username = ?";
            $stmt = $conn->prepare($checkQuery);
            $stmt->bind_param("ss", $email, $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error = "Email or username already exists. Please choose another.";
            } else {
                // Insert the new user into the database
                $query = "INSERT INTO users (firstname, lastname, email, password, username, avatar) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssssss", $firstname, $lastname, $email, $hashedPassword, $username, $avatar);

                if ($stmt->execute()) {
                    // Redirect to the login page after successful signup
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

<!-- Link to the CSS file -->
<link rel="stylesheet" href="/TrekSmart/assets/CSS/style.css">

<main class="auth-page">
    <div class="auth-container">
        <!-- Left side: Background image with logo and caption -->
        <div class="auth-left" style="background-image: url('/TrekSmart/assets/image/bg-login.webp');">
            <img src="/TrekSmart/assets/logo/logowhite.png" alt="TrekSmart Logo">
            <div class="auth-caption">
                <h2>Capturing <b class="highlight">Moments</b>, Creating <b class="highlight">Memories</b></h2>
            </div>
        </div>

        <!-- Right side: Signup form -->
        <div class="auth-right">
            <h2>Create an Account</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <form action="signup.php" method="POST" enctype="multipart/form-data">
                <!-- First and Last Name Fields -->
                <div class="form-group">
                    <input type="text" id="firstname" name="firstname" placeholder="First Name" required>
                </div>
                <div class="form-group">
                    <input type="text" id="lastname" name="lastname" placeholder="Last Name" required>
                </div>
                <!-- Email Field -->
                <div class="form-group">
                    <input type="email" id="email" name="email" placeholder="Email" required>
                </div>
                <!-- Username Field -->
                <div class="form-group">
                    <input type="text" id="username" name="username" placeholder="Username" required>
                </div>
                <!-- Password Field -->
                <div class="form-group">
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <small id="passwordHelp" class="helper-text"></small>
                <!-- Profile Picture Field -->
                <div class="form-group">
                    <label for="profile_picture">Profile Picture</label>
                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
                </div>
                <!-- Terms & Conditions Checkbox -->
                <div class="checkbox-container">
                    <input type="checkbox" id="terms" required>
                    <label for="terms">I agree to the Terms & Conditions!</label>
                </div>
                <!-- Submit Button -->
                <button type="submit" class="btn-primary">Create account</button>
            </form>
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>
</main>

<!-- Link to the JavaScript file -->
<script src="/TrekSmart/assets/JS/script.js"></script>
