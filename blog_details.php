<?php
require 'db.php';

if (!isset($_GET['id'])) {
    die("Blog not found.");
}

$id = intval($_GET['id']);
$query = "SELECT title, content, image_path, publish_date, read_time FROM blogs WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

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
    <link rel="stylesheet" href="/TrekSmart/public/style.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="blog-details">
        <div class="blog-image">
            <img src="<?= htmlspecialchars($row['image_path']); ?>" alt="<?= htmlspecialchars($row['title']); ?>">
        </div>
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

    <?php include 'footer.php'; ?>
</body>
<script src="/TrekSmart/JS/script.js"></script>

</html>
