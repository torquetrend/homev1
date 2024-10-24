<?php
// admin/create_article.php
require 'auth.php';
require '../config.php';

$title = $content = '';
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize inputs
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    // Validate inputs
    if (empty($title)) {
        $errors[] = "Title is required.";
    }

    if (empty($content)) {
        $errors[] = "Content is required.";
    }

    // Handle image upload
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] != UPLOAD_ERR_NO_FILE) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            if (in_array($_FILES['image']['type'], $allowedTypes)) {
                $targetDir = '../uploads/';
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                $filename = basename($_FILES['image']['name']);
                $targetFile = $targetDir . time() . '_' . preg_replace("/[^a-zA-Z0-9.\-_]/", "_", $filename);
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $imagePath = $targetFile;
                } else {
                    $errors[] = "Failed to upload image.";
                }
            } else {
                $errors[] = "Invalid image format. Allowed types: JPEG, PNG, GIF.";
            }
        } else {
            $errors[] = "Error uploading image.";
        }
    }

    // If no errors, insert into database
    if (empty($errors)) {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO articles (title, content, image) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $content, $imagePath);

        if ($stmt->execute()) {
            header("Location: dashboard.php?message=Article created successfully.");
            exit();
        } else {
            $errors[] = "Database error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Article - Admin - TorqueTrend</title>
    <link rel="stylesheet" href="../styles.css">
    <!-- Optional: Include TinyMCE for rich text editing -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#content',
            plugins: 'advlist autolink lists link image charmap preview anchor',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            images_upload_url: 'upload_image.php',
            automatic_uploads: true,
            relative_urls: false,
            remove_script_host: false,
            document_base_url: "<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/",
            images_reuse_filename: true
        });
    </script>
</head>
<body>
    <header class="header">
        <div class="container">
            <h1>Create New Article</h1>
            <nav>
                <a href="dashboard.php" class="btn-secondary">Back to Dashboard</a>
                <a href="logout.php" class="btn-secondary">Logout</a>
            </nav>
        </div>
    </header>
    <main class="container" style="margin-top: 80px;">
        <h2>New Article</h2>
        <?php if (!empty($errors)): ?>
            <div class="error-messages">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <form action="create_article.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content" rows="10" required><?php echo htmlspecialchars($content); ?></textarea>
            </div>
            <div class="form-group">
                <label for="image">Insert Image</label>
                <input type="file" id="image" name="image" accept="image/*">
                <p>If you want to keep the existing image, leave this field empty.</p>
            </div>
            <button type="submit" class="btn-primary">Publish Article</button>
        </form>
    </main>
</body>
</html>
<?php
$conn->close();
?>

