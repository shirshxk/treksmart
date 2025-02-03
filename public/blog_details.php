<?php
// Include the database connection file
require '../includes/db.php';

// Check if a blog ID is provided
if (!isset($_GET['id'])) {
    die("Blog not found.");
}

$id = intval($_GET['id']);

// Prepare and execute the query to fetch the blog details
$query = "SELECT title, content, image_path, publish_date, read_time FROM blogs WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Ensure exactly one blog is found
if ($result->num_rows !== 1) {
    die("Blog not found.");
}

$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($row['title']); ?></title>
    <!-- Link to the CSS file -->
    <link rel="stylesheet" href="/TrekSmart/assets/CSS/style.css">
</head>
<body>
    <!-- Include header -->
    <?php include '../includes/header.php'; ?>

    <main class="blog-details">
        <!-- Blog Image -->
        <div class="blog-image">
            <img src="<?= htmlspecialchars($row['image_path']); ?>" alt="<?= htmlspecialchars($row['title']); ?>">
        </div>
        <!-- Blog Content -->
        <div class="blog-content">
            <h1><?= htmlspecialchars($row['title']); ?></h1>
            <p class="blog-meta">
                <?= date('F d, Y', strtotime($row['publish_date'])); ?> • <?= htmlspecialchars($row['read_time']); ?> min read
            </p>
            <div class="blog-body">
                <p><?= $row['content']; ?></p>
            </div>
        </div>
    </main>

    <!-- Include footer -->
    <?php include '../includes/footer.php'; ?>
    
    <!-- Link to the JavaScript file -->
    <script src="/TrekSmart/assets/JS/script.js"></script>
</body>
</html>
