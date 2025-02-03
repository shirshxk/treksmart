<?php
// Include the database connection file from the includes folder
require '../includes/db.php';

// Check if the user is logged in; if not, redirect to the login page in the auth folder
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Fetch all bookings for the logged-in user with package details
$query = "SELECT b.*, p.title AS package_title 
          FROM bookings b
          JOIN trekking_packages p ON b.package_id = p.id
          WHERE b.user_id = ? 
          ORDER BY b.booking_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$bookings = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Handle form submission for editing a booking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_booking_id'])) {
    $bookingId = intval($_POST['edit_booking_id']);
    $trekStartDate = $_POST['trek_start_date'];
    $specialRequests = $_POST['special_requests'];

    $updateQuery = "UPDATE bookings SET trek_start_date = ?, special_requests = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssii", $trekStartDate, $specialRequests, $bookingId, $userId);

    if ($stmt->execute()) {
        $success = "Booking updated successfully.";
    } else {
        $error = "Failed to update booking. Please try again.";
    }
    $stmt->close();
}

// Handle cancellation of a booking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_booking_id'])) {
    $bookingId = intval($_POST['cancel_booking_id']);
    $cancelReason = htmlspecialchars($_POST['cancel_reason']);

    $cancelQuery = "UPDATE bookings SET status = 'Cancelled', cancellation_reason = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($cancelQuery);
    $stmt->bind_param("sii", $cancelReason, $bookingId, $userId);

    if ($stmt->execute()) {
        $success = "Booking canceled successfully.";
    } else {
        $error = "Failed to cancel booking. Please try again.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - My Bookings</title>
    <!-- Link to the CSS file -->
    <link rel="stylesheet" href="/TrekSmart/assets/CSS/style.css">
</head>
<body class="user-dash">
    <!-- Include the header from the includes folder -->
    <?php include '../includes/header.php'; ?>

    <main class="dashboard-page">
        <!-- Dashboard Hero Section -->
        <section class="dash-hero">
            <div class="hero-text">
                <h1 class="highlight">My Bookings</h1>
                <p>Manage all of your bookings here!</p>
            </div>
        </section>

        <!-- Dashboard Container -->
        <div class="dashboard-container">
            <?php if (isset($success)): ?>
                <p class="success"><?= $success; ?></p>
            <?php elseif (isset($error)): ?>
                <p class="error"><?= $error; ?></p>
            <?php endif; ?>

            <?php if (count($bookings) > 0): ?>
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th style="background-color: #333;">#</th>
                            <th style="background-color: #333;">Package</th>
                            <th style="background-color: #333;">Trek Date</th>
                            <th style="background-color: #333;">Status</th>
                            <th style="background-color: #333;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $index => $booking): ?>
                            <tr>
                                <td><?= $index + 1; ?></td>
                                <td><?= htmlspecialchars($booking['package_title']); ?></td>
                                <td><?= htmlspecialchars($booking['trek_start_date']); ?></td>
                                <td><?= htmlspecialchars($booking['status']); ?></td>
                                <td>
                                    <button class="edit-btn" onclick="openEditModal(<?= $booking['id']; ?>, '<?= htmlspecialchars($booking['trek_start_date']); ?>', '<?= htmlspecialchars($booking['special_requests']); ?>')">
                                        Edit
                                    </button>
                                    <button class="cancel-btn" onclick="openCancelModal(<?= $booking['id']; ?>)">
                                        Cancel
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color:black; text-align:center">You have no bookings yet.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- Edit Booking Modal -->
    <div id="editModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Edit Booking</h2>
            <form method="POST">
                <input type="hidden" id="edit_booking_id" name="edit_booking_id" value="">
                <div class="booking-form-group">
                    <label for="trek_start_date">Trek Start Date</label>
                    <input type="date" id="edit_trek_start_date" name="trek_start_date" required>
                </div>
                <div class="booking-form-group">
                    <label for="special_requests" class="dash-label">Special Requests</label>
                    <textarea id="edit_special_requests" name="special_requests"></textarea>
                </div>
                <button type="submit" class="btn-primary">Update Booking</button>
            </form>
        </div>
    </div>

    <!-- Cancel Booking Modal -->
    <div id="cancelModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeCancelModal()">&times;</span>
            <h2>Cancel Booking</h2>
            <form method="POST">
                <input type="hidden" id="cancel_booking_id" name="cancel_booking_id" value="">
                <div class="booking-form-group">
                    <label for="cancel_reason">Reason for Cancellation</label>
                    <textarea id="cancel_reason" name="cancel_reason" required></textarea>
                </div>
                <button type="submit" class="btn-danger">Cancel Booking</button>
            </form>
        </div>
    </div>

    <!-- Inline JavaScript for Modal Controls -->
    <script>
        // Open edit modal and populate fields
        function openEditModal(bookingId, trekStartDate, specialRequests) {
            document.getElementById('edit_booking_id').value = bookingId;
            document.getElementById('edit_trek_start_date').value = trekStartDate;
            document.getElementById('edit_special_requests').value = specialRequests;
            document.getElementById('editModal').style.display = 'block';
        }

        // Close edit modal
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Open cancel modal and set the booking ID
        function openCancelModal(bookingId) {
            document.getElementById('cancel_booking_id').value = bookingId;
            document.getElementById('cancelModal').style.display = 'block';
        }

        // Close cancel modal
        function closeCancelModal() {
            document.getElementById('cancelModal').style.display = 'none';
        }
    </script>

    <!-- Link to the JavaScript file -->
    <script src="/TrekSmart/assets/JS/script.js"></script>

    <!-- Include the footer from the includes folder -->
    <?php include '../includes/footer.php'; ?>
</body>
</html>
