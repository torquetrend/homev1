<?php
// admin/dashboard.php
require 'auth.php';
require '../config.php';

// Fetch all articles
$sql = "SELECT id, title, created_at, updated_at FROM articles ORDER BY created_at DESC";
$result = $conn->query($sql);

// Handle messages
$message = '';
$message_type = '';

if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
    $message_type = 'success';
} elseif (isset($_GET['error'])) {
    $message = htmlspecialchars($_GET['error']);
    $message_type = 'error';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - TorqueTrend</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <h1>Admin Dashboard</h1>
            <nav>
                <a href="create_article.php" class="btn-primary">Create New Article</a>
                <a href="logout.php" class="btn-secondary">Logout</a>
            </nav>
        </div>
    </header>
    <main class="container" style="margin-top: 80px;">
        <?php if ($message): ?>
            <div class="toast-container">
                <div class="toast toast-<?php echo $message_type; ?>">
                    <?php echo $message; ?>
                </div>
            </div>
        <?php endif; ?>
        <h2>Manage Articles</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($article = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($article['id']); ?></td>
                            <td><?php echo htmlspecialchars($article['title']); ?></td>
                            <td><?php echo htmlspecialchars($article['created_at']); ?></td>
                            <td><?php echo htmlspecialchars($article['updated_at']); ?></td>
                            <td>
                                <a href="edit_article.php?id=<?php echo $article['id']; ?>" class="btn-secondary">Edit</a>
                                <a href="delete_article.php?id=<?php echo $article['id']; ?>" class="btn-error" onclick="return confirm('Are you sure you want to delete this article?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No articles found. <a href="create_article.php">Create the first article</a>.</p>
        <?php endif; ?>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.querySelector('.toast');
            if (toast) {
                setTimeout(() => {
                    toast.classList.add('fade-out');
                    toast.addEventListener('transitionend', () => toast.remove());
                }, 3000);
            }
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>

