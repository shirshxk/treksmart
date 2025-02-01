<?php
include 'db.php';

function resizeImage($file, $targetWidth, $targetHeight, $destination) {
    list($originalWidth, $originalHeight, $imageType) = getimagesize($file);

    // Maintain aspect ratio
    $aspectRatio = $originalWidth / $originalHeight;
    if ($targetWidth / $targetHeight > $aspectRatio) {
        $targetWidth = $targetHeight * $aspectRatio;
    } else {
        $targetHeight = $targetWidth / $aspectRatio;
    }

    // Create new image resource
    $image = null;
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $image = imagecreatefromjpeg($file);
            break;
        case IMAGETYPE_PNG:
            $image = imagecreatefrompng($file);
            break;
        case IMAGETYPE_GIF:
            $image = imagecreatefromgif($file);
            break;
        default:
            return false; // Unsupported image type
    }

    $resizedImage = imagecreatetruecolor($targetWidth, $targetHeight);
    imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $targetWidth, $targetHeight, $originalWidth, $originalHeight);

    // Save resized image
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            imagejpeg($resizedImage, $destination, 90); // 90 is quality
            break;
        case IMAGETYPE_PNG:
            imagepng($resizedImage, $destination);
            break;
        case IMAGETYPE_GIF:
            imagegif($resizedImage, $destination);
            break;
    }

    // Free resources
    imagedestroy($image);
    imagedestroy($resizedImage);

    return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $username = $_POST['username'];

    // Validate username and password
    $passwordPattern = '/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
    if (!preg_match($passwordPattern, $password)) {
        $error = "Password must be at least 8 characters, contain one uppercase letter, one number, and one special character.";
    } elseif (empty($username) || strlen($username) < 3) {
        $error = "Username must be at least 3 characters long.";
    } else {
        $avatarPath = '/TrekSmart/assets/default-avatar.png'; // Default avatar

        // Process avatar upload if provided
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $avatarDir = $_SERVER['DOCUMENT_ROOT'] . '/TrekSmart/uploads/avatars/';

            // Ensure the directory exists
            if (!is_dir($avatarDir)) {
                mkdir($avatarDir, 0777, true); // Create directory if it doesn't exist
            }

            $avatarName = uniqid() . "_" . basename($_FILES['avatar']['name']);
            $avatarFullPath = $avatarDir . $avatarName;

            // Resize and save the uploaded image
            if (resizeImage($_FILES['avatar']['tmp_name'], 200, 200, $avatarFullPath)) {
                $avatarPath = '/TrekSmart/uploads/avatars/' . $avatarName; // Relative path saved to the database
            }
        }

        // Hash the password
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
                // Insert new user with avatar
                $query = "INSERT INTO users (firstname, lastname, email, password, username, avatar) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssssss", $firstname, $lastname, $email, $hashedPassword, $username, $avatarPath);

                if ($stmt->execute()) {
                    // Redirect to login page after successful signup
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
                   
                </div>
                <div class="form-group">
                    <input type="text" id="lastname" name="lastname" placeholder="Last Name" required>
                </div>
                <!-- Email Field -->
                <div class="form-group">
                    <input type="email" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="text" id="username" name="username" placeholder="Username" required>
                </div>
                <!-- Password Field -->
                <div class="form-group">
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <small id="passwordHelp" class="helper-text"></small>
                <!-- Checkbox -->
                <div class="form-group">
                    <label for="avatar">Upload Avatar</label>
                    <input type="file" id="avatar" name="avatar" accept="image/*">
                </div>
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
