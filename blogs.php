<?php
require 'db.php';

// Fetch blogs from the database
$query = "SELECT id, title, excerpt, image_path, publish_date, read_time FROM blogs ORDER BY publish_date DESC";
$result = $conn->query($query);

if (!$result) {
    die("Error fetching blogs: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog and News</title>
    <link rel="stylesheet" href="/TrekSmart/public/style.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="blog-section">
        <section class="blog-hero">
            <div class="hero-text">
            <h1 class="highlight">Blog and News</h1>
            <p>Stay Updated: Travel Tips, Stories, and the Latest News from TrekSmart</p>
            </div>
        </section>
        <div class="featured-blog">
            <?php if ($row = $result->fetch_assoc()): ?>
                <div class="featured-image">
                    <img src="<?= htmlspecialchars($row['image_path']); ?>" alt="<?= htmlspecialchars($row['title']); ?>">
                </div>
                <div class="featured-content">
                    <h2><?= htmlspecialchars($row['title']); ?></h2>
                    <p><?= htmlspecialchars($row['excerpt']); ?></p>
                    <p class="blog-meta">
                        <?= date('F d, Y', strtotime($row['publish_date'])); ?> • <?= htmlspecialchars($row['read_time']); ?> min read
                    </p>
                    <a href="blog_details.php?id=<?= $row['id']; ?>" class="read-more">Read More →</a>
                </div>
            <?php endif; ?>
        </div>

        <div class="blog-grid">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="blog-card">
                    <img src="<?= htmlspecialchars($row['image_path']); ?>" alt="<?= htmlspecialchars($row['title']); ?>">
                    <div class="blog-card-content">
                        <h3><?= htmlspecialchars($row['title']); ?></h3>
                        <p class="blog-meta">
                            <?= date('F d, Y', strtotime($row['publish_date'])); ?> • <?= htmlspecialchars($row['read_time']); ?> min read
                        </p>
                        <a href="blog_details.php?id=<?= $row['id']; ?>" class="read-more">Read More →</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
<script src="/TrekSmart/JS/script.js"></script>

</html>
