<?php
require 'db.php';
session_start();

// Debug session
if (!isset($_SESSION['user_id'])) {
    error_log("Session missing user_id. Session data: " . print_r($_SESSION, true));
    echo "You need to log in to book a package.";
    exit;
} else {
    error_log("Session found. User ID: " . $_SESSION['user_id']);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['package_id'])) {
    if (!isset($_SESSION['user_id'])) {
        error_log("User not logged in.");
        echo "You need to log in to book a package.";
        exit;
    }

    $userId = $_SESSION['user_id'];
    $packageId = intval($_POST['package_id']);
    $firstname = htmlspecialchars(trim($_POST['firstname']));
    $lastname = htmlspecialchars(trim($_POST['lastname']));
    $email = htmlspecialchars(trim($_POST['email']));
    $trekStartDate = htmlspecialchars(trim($_POST['trek_start_date']));
    $specialRequests = htmlspecialchars(trim($_POST['special_requests'] ?? ''));

    // Input validation
    if (empty($firstname) || empty($lastname) || empty($email) || empty($trekStartDate)) {
        echo "All fields are required.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        exit;
    }

    // Reformat date
    $trekStartDate = date('Y-m-d', strtotime($trekStartDate));

    // Prepare SQL
    $query = "INSERT INTO bookings (user_id, package_id, firstname, lastname, email, trek_start_date, special_requests, status)
              VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending')";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        error_log("SQL prepare error: " . $conn->error);
        echo "An unexpected error occurred. Please try again.";
        exit;
    }

    $stmt->bind_param("iisssss", $userId, $packageId, $firstname, $lastname, $email, $trekStartDate, $specialRequests);

    if ($stmt->execute()) {
        echo "Booking successful! Your request is pending approval.";
    } else {
        error_log("SQL execution error: " . $stmt->error);
        echo "Error processing booking. Please try again.";
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}
?>
