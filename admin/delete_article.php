<?php
// admin/delete_article.php
require 'auth.php';
require '../config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: dashboard.php?error=Invalid article ID.");
    exit();
}

$article_id = intval($_GET['id']);

// Fetch the article to get the image path
$stmt = $conn->prepare("SELECT image FROM articles WHERE id = ?");
$stmt->bind_param("i", $article_id);
$stmt->execute();
$stmt->bind_result($imagePath);
$stmt->store_result();

if ($stmt->num_rows != 1) {
    header("Location: dashboard.php?error=Article not found.");
    exit();
}

$stmt->fetch();
$stmt->close();

// Delete the article from the database
$stmt = $conn->prepare("DELETE FROM articles WHERE id = ?");
$stmt->bind_param("i", $article_id);

if ($stmt->execute()) {
    // Delete the image file if it exists
    if ($imagePath && file_exists($imagePath)) {
        unlink($imagePath);
    }
    header("Location: dashboard.php?message=Article deleted successfully.");
    exit();
} else {
    header("Location: dashboard.php?error=Failed to delete the article.");
    exit();
}

$stmt->close();
$conn->close();
?>
