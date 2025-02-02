<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['package_id'])) {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $userId = $_SESSION['user_id'];
    $packageId = intval($_POST['package_id']);

    // Validate package existence
    $packageCheckQuery = "SELECT id FROM trekking_packages WHERE id = ?";
    $packageCheckStmt = $conn->prepare($packageCheckQuery);
    $packageCheckStmt->bind_param("i", $packageId);
    $packageCheckStmt->execute();
    $packageCheckResult = $packageCheckStmt->get_result();

    if ($packageCheckResult->num_rows === 0) {
        header("Location: error.php?msg=invalid_package");
        exit;
    }

    // Prevent duplicate bookings
    $checkQuery = "SELECT * FROM bookings WHERE user_id = ? AND package_id = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("ii", $userId, $packageId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        header("Location: error.php?msg=duplicate_booking");
        exit;
    }

    // Insert booking
    $query = "INSERT INTO bookings (user_id, package_id, status) VALUES (?, ?, 'Pending')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $userId, $packageId);

    if ($stmt->execute()) {
        header("Location: my_bookings.php");
        exit;
    } else {
        error_log("Error booking the package: " . $stmt->error);
        header("Location: error.php?msg=booking_failed");
        exit;
    }

    $stmt->close();
}
?>
