<?php
// article.php
require 'backend/config.php';

// Get the article ID from the URL
$article_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the article from the database
$stmt = $conn->prepare("SELECT title, content, image, created_at FROM articles WHERE id = ?");
$stmt->bind_param("i", $article_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($title, $content, $image, $created_at);
    $stmt->fetch();
} else {
    // Article not found
    header("Location: index.php?error=Article not found.");
    exit();
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($title); ?> - TorqueTrend</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <h1><a href="index.php">TorqueTrend</a></h1>
            <nav>
                <a href="index.php" class="nav-link">Home</a>
                <a href="admin/login.php" class="nav-link">Admin</a>
            </nav>
        </div>
    </header>
    <main class="container" style="margin-top: 80px;">
        <article class="article">
            <h2><?php echo htmlspecialchars($title); ?></h2>
            <p class="article-meta">Published on <?php echo date("F j, Y", strtotime($created_at)); ?></p>
            <?php if ($image): ?>
                <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($title); ?>" class="article-image" loading="lazy">
            <?php endif; ?>
            <div class="article-content">
                <?php echo nl2br(htmlspecialchars($content)); ?>
            </div>
        </article>
        <a href="index.php" class="btn-secondary">Back to Home</a>
    </main>
    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> TorqueTrend. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
