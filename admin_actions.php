<?php
require 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['admin_check'] != 1) {
    header("Location: login.php");
    exit;
}


$action = $_GET['action'] ?? '';


/**
 * ✅ ADD USER
 */
if ($action === 'addUser' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username    = $_POST['username'];
    $email       = $_POST['email'];
    $password    = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $admin_check = intval($_POST['admin_check']);

    // Default avatar path if none uploaded
    // Default avatar path if none uploaded
    $avatar = '/TrekSmart/assets/default-avatar.png';
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
        // Build the absolute path on the file system
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/TrekSmart/uploads/';
        $fileName  = time() . '_' . basename($_FILES['profile_picture']['name']);
        $targetPath = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetPath)) {
            // Save only the public URL
            $avatar = '/TrekSmart/uploads/' . $fileName;
        }
    }


    $query = "INSERT INTO users (firstname, lastname, username, email, password, admin_check, avatar) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt  = $conn->prepare($query);
    $stmt->bind_param("sssssis", $firstname, $lastname, $username, $email, $password, $admin_check, $avatar);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php?section=manageUsers");
        exit;
    } else {
        echo "Failed to add user.";
    }
}

/**
 * ✅ EDIT USER
 */
if ($action === 'editUser' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id          = intval($_POST['id']);
    $firstname   = $_POST['firstname'];
    $lastname    = $_POST['lastname'];
    $username    = $_POST['username'];
    $email       = $_POST['email'];
    $admin_check = intval($_POST['admin_check']);

    // Fetch the current avatar
    $query = "SELECT avatar FROM users WHERE id = ?";
    $stmt  = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($currentAvatar);
    $stmt->fetch();
    $stmt->close();

    $avatar = $currentAvatar; // Default to the existing avatar

    // Process new profile picture upload if provided
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/TrekSmart/uploads/';
        $fileName   = time() . '_' . basename($_FILES['profile_picture']['name']);
        $targetPath = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetPath)) {
            $avatar = '/TrekSmart/uploads/' . $fileName;
        }
    }

    $updateQuery = "UPDATE users SET firstname = ?, lastname = ?, username = ?, email = ?, admin_check = ?, avatar = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssssisi", $firstname, $lastname, $username, $email, $admin_check, $avatar, $id);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php?section=manageUsers");
        exit;
    } else {
        echo "Failed to update user.";
    }
}

/**
 * ✅ DELETE USER
 */
if ($action === 'deleteUser') {
    $id = intval($_GET['id']);

    $query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php?section=manageUsers");
        exit;
    } else {
        echo "Failed to delete user.";
    }
}




if ($action === 'addPackage' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $duration = intval($_POST['duration']);
    $difficulty = $_POST['difficulty'];
    $price = floatval($_POST['price']);

    // Handle Image Upload
    $imagePath = '/TrekSmart/uploads/default-package.png'; // Default
    if (!empty($_FILES['image']['name'])) {
        $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/TrekSmart/uploads/';
        $imageName = uniqid() . "_" . basename($_FILES['image']['name']);
        $targetFilePath = $targetDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
            $imagePath = '/TrekSmart/uploads/' . $imageName;
        }
    }

    $query = "INSERT INTO trekking_packages (title, description, duration, difficulty, price, image_path) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssisds", $title, $description, $duration, $difficulty, $price, $imagePath);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php?section=managePackages");
        exit;
    } else {
        echo "Failed to add package.";
    }
}

/**
 * EDIT PACKAGE ✅
 */
if ($action === 'editPackage' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $title = $_POST['title'];
    $description = $_POST['description'];
    $duration = intval($_POST['duration']);
    $difficulty = $_POST['difficulty'];
    $price = floatval($_POST['price']);

    if (!empty($_FILES['image']['name'])) {
        $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/TrekSmart/uploads/';
        $imageName = uniqid() . "_" . basename($_FILES['image']['name']);
        $targetFilePath = $targetDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
            $imagePath = '/TrekSmart/uploads/' . $imageName;
            $query = "UPDATE trekking_packages SET title = ?, description = ?, duration = ?, difficulty = ?, price = ?, image_path = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssisdsi", $title, $description, $duration, $difficulty, $price, $imagePath, $id);
        }
    } else {
        $query = "UPDATE trekking_packages SET title = ?, description = ?, duration = ?, difficulty = ?, price = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssissi", $title, $description, $duration, $difficulty, $price, $id);
    }

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php?section=managePackages");
        exit;
    } else {
        echo "Failed to update package.";
    }
}


/**
 * DELETE PACKAGE ✅
 */
if ($action === 'deletePackage') {
    $id = intval($_GET['id']);
    $query = "DELETE FROM trekking_packages WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php?section=managePackages");
        exit;
    } else {
        echo "Failed to delete package.";
    }
}

/**
 * ADD BLOG ✅
 */
if ($action === 'addBlog' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $excerpt = $_POST['excerpt'];
    $content = $_POST['content'];
    $readTime = intval($_POST['read_time']);

    // Handle Image Upload
    $imagePath = '/TrekSmart/uploads/default-blog.png'; // Default
    if (!empty($_FILES['image']['name'])) {
        $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/TrekSmart/uploads/';
        $imageName = uniqid() . "_" . basename($_FILES['image']['name']);
        $targetFilePath = $targetDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
            $imagePath = '/TrekSmart/uploads/' . $imageName;
        }
    }

    $query = "INSERT INTO blogs (title, excerpt, content, read_time, image_path) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssis", $title, $excerpt, $content, $readTime, $imagePath);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php?section=manageBlogs");
        exit;
    } else {
        echo "Failed to add blog.";
    }
}

/**
 * EDIT BLOG ✅
 */
if ($action === 'editBlog' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $title = $_POST['title'];
    $excerpt = $_POST['excerpt'];
    $content = $_POST['content'];
    $readTime = intval($_POST['read_time']);

    // Check if a new image is uploaded
    if (!empty($_FILES['image']['name'])) {
        $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/TrekSmart/uploads/';
        $imageName = uniqid() . "_" . basename($_FILES['image']['name']);
        $targetFilePath = $targetDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
            $imagePath = '/TrekSmart/uploads/' . $imageName;
            
            // Update with new image
            $query = "UPDATE blogs SET title = ?, excerpt = ?, content = ?, read_time = ?, image_path = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssisi", $title, $excerpt, $content, $readTime, $imagePath, $id);
        }
    } else {
        // Update without changing the image
        $query = "UPDATE blogs SET title = ?, excerpt = ?, content = ?, read_time = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssii", $title, $excerpt, $content, $readTime, $id);
    }

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php?section=manageBlogs");
        exit;
    } else {
        echo "Failed to update blog.";
    }
}

/**
 * DELETE BLOG
 */
if ($action === 'deleteBlog') {
    $id = intval($_GET['id']);

    $query = "DELETE FROM blogs WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php?section=manageBlogs");
        exit;
    } else {
        echo "Failed to delete blog.";
    }
}

// Approve Booking

if ($action === 'approveBooking') {
    $id = $_GET['id'];

    $query = "UPDATE bookings SET status = 'Booked' WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php?section=manageBookings");
    } else {
        echo "Failed to approve booking.";
    }
    exit;
}


// Cancel Booking
if ($action === 'cancelBooking') {
$id = $_GET['id'];

$query = "UPDATE bookings SET status = 'Cancelled' WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    header("Location: admin_dashboard.php?section=manageBookings");
} else {
    echo "Failed to cancel booking.";
}
exit;
}

// Delete Booking
if ($action === 'deleteBooking') {
$id = $_GET['id'];

$query = "DELETE FROM bookings WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    header("Location: admin_dashboard.php?section=manageBookings");
} else {
    echo "Failed to delete booking.";
}
exit;
}