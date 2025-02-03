<?php
// Include the database connection file
require '../includes/db.php';

// If an "id" parameter is provided, output package details for the modal and exit
if (isset($_GET['id'])) {
    $packageId = intval($_GET['id']);
    $query = "SELECT * FROM trekking_packages WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $packageId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        ?>
        <!-- Modal content for package details -->
        <h2 class="detail-title"><?= htmlspecialchars($row['title']); ?></h2>
        <p><?= htmlspecialchars($row['description']); ?></p>
        <ul>
            <li>Duration: <?= htmlspecialchars($row['duration']); ?> days</li>
            <li>Price: $<?= htmlspecialchars($row['price']); ?></li>
        </ul>
        <img src="<?= htmlspecialchars($row['image_path']); ?>" alt="<?= htmlspecialchars($row['title']); ?>" style="width:100%; border-radius: 8px;">
        <?php
    } else {
        echo "<p>Package not found.</p>";
    }
    $stmt->close();
    exit; // Exit after outputting modal content
}

// Include the header file
include '../includes/header.php';

// Check if a user is logged in; if not, redirect to the "nologin" page
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: ../auth/nologin.php");
    exit();
}

$userId = $_SESSION['user_id']; // Retrieve logged-in user's ID

// Handle booking submission when the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['package_id'])) {
    // Sanitize and retrieve booking data
    $packageId       = intval($_POST['package_id']);
    $firstname       = trim($_POST['firstname']);
    $lastname        = trim($_POST['lastname']);
    $email           = trim($_POST['email']);
    $trekStartDate   = $_POST['trek_start_date'];
    $specialRequests = isset($_POST['special_requests']) ? trim($_POST['special_requests']) : null;

    // Ensure all required fields are filled
    if (empty($firstname) || empty($lastname) || empty($email) || empty($trekStartDate)) {
        die("All required fields must be filled.");
    }

    // Insert the booking into the database with a default status of 'Pending'
    $query = "INSERT INTO bookings (user_id, package_id, firstname, lastname, email, trek_start_date, special_requests, status) 
              VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iisssss", $userId, $packageId, $firstname, $lastname, $email, $trekStartDate, $specialRequests);

    if ($stmt->execute()) {
        // Redirect to the confirmation page after successful booking
        header("Location: ../user/confirmation_page.php");
        exit();
    } else {
        die("Error while booking: " . $stmt->error);
    }
}

// Fetch all trekking packages for display
$query  = "SELECT id, title, description, duration, price, image_path FROM trekking_packages";
$result = $conn->query($query);

if (!$result) {
    die("Error fetching trekking packages: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trekking Packages</title>
    <!-- Link to the CSS file -->
    <link rel="stylesheet" href="/TrekSmart/assets/CSS/style.css">
</head>
<body>
    <main class="trekking-page">
        <!-- Hero Section -->
        <div class="trek-hero">
            <section class="packages-header">
                <h1 class="highlight">Choose your Package</h1>
                <p>Find the Perfect Trekking Adventure for You!</p>
            </section>
        </div>
        
        <!-- Package Cards Section -->
        <section class="packages-container">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="package-card">
                    <img src="<?= htmlspecialchars($row['image_path']); ?>" alt="<?= htmlspecialchars($row['title']); ?>">
                    <div class="card-content">
                        <h3><?= htmlspecialchars($row['title']); ?></h3>
                        <p><?= htmlspecialchars($row['description']); ?></p>
                        <ul class="package-features">
                            <li><?= htmlspecialchars($row['duration']); ?> days tour</li>
                            <li>Michelin-rated meal included</li>
                        </ul>
                        <div class="price">$<?= htmlspecialchars($row['price']); ?></div>
                        <div class="card-buttons">
                            <button class="btn-primary" style="font-size:20px;font-weight:bold;height:60px;margin-bottom:0;" onclick="openBookingModal(<?= $row['id']; ?>)">Book Now</button>
                            <button class="btn-secondary" data-id="<?= $row['id']; ?>">View Details</button>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </section>
    </main>

    <!-- Modal for Package Details -->
    <div id="packageModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('packageModal')">&times;</span>
            <div id="modalBody"></div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div id="bookingModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeBookingModal()">&times;</span>
            <div class="modal-body">
                <h2>Book Your Adventure</h2>
                <form id="bookingForm" method="POST" action="trekking.php">
                    <input type="hidden" id="bookingPackageId" name="package_id" value="">
                    <div class="booking-form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" id="firstname" name="firstname" placeholder="Enter your first name" required>
                    </div>
                    <div class="booking-form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" id="lastname" name="lastname" placeholder="Enter your last name" required>
                    </div>
                    <div class="booking-form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="booking-form-group">
                        <label for="trek_start_date">Trek Start Date</label>
                        <input type="date" id="trekStartDate" name="trek_start_date" required>
                    </div>
                    <div class="booking-form-group">
                        <label for="special_requests">Special Requests</label>
                        <textarea id="special_requests" name="special_requests" placeholder="Enter special requests"></textarea>
                    </div>
                    <button type="submit" class="btn-primary">Confirm Booking</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Include the footer -->
    <?php include '../includes/footer.php'; ?>
    <!-- Link to the JavaScript file -->
    <script src="/TrekSmart/assets/JS/script.js"></script>
</body>
</html>
