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
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $admin_check = intval($_POST['admin_check']); // 0 for User, 1 for Admin

    $query = "INSERT INTO users (username, email, password, admin_check) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $username, $email, $password, $admin_check);

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
    $id = intval($_POST['id']);
    $username = $_POST['username'];
    $email = $_POST['email'];
    $admin_check = intval($_POST['admin_check']);  // This value comes from the dropdown
    
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, admin_check = ? WHERE id = ?");
    $stmt->bind_param("ssii", $username, $email, $admin_check, $id);
    $query = "UPDATE users SET username = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $username, $email, $id);

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
    $stmt->bind_param("ssisss", $title, $description, $duration, $difficulty, $price, $imagePath);

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
            $stmt->bind_param("ssisssi", $title, $description, $duration, $difficulty, $price, $imagePath, $id);
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
    $stmt->bind_param("sssds", $title, $excerpt, $content, $readTime, $imagePath);

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