<?php
// backend/search.php

header('Content-Type: application/json');

require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $query = trim($_GET['query'] ?? '');

    if (empty($query)) {
        echo json_encode(['status' => 'error', 'message' => 'Search query is required.']);
        exit;
    }

    // Prepare and execute the statement
    $stmt = $conn->prepare("SELECT id, title, content, image, created_at FROM articles WHERE title LIKE ? OR content LIKE ? ORDER BY created_at DESC");
    $like_query = '%' . $query . '%';
    $stmt->bind_param("ss", $like_query, $like_query);
    $stmt->execute();
    $result = $stmt->get_result();

    $articles = [];
    while ($row = $result->fetch_assoc()) {
        $articles[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'content' => $row['content'],
            'image' => $row['image'],
            'created_at' => $row['created_at']
        ];
    }

    echo json_encode(['status' => 'success', 'data' => $articles]);

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
