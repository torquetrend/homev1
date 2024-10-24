<?php
// index.php
require 'backend/config.php';

// Fetch latest articles
$stmt = $conn->prepare("SELECT id, title, content, image, created_at FROM articles ORDER BY created_at DESC LIMIT 5");
$stmt->execute();
$result = $stmt->get_result();

$articles = [];
while ($row = $result->fetch_assoc()) {
    $articles[] = $row;
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TorqueTrend</title>
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="manifest" href="manifest.json">
</head>
<body>
    <header class="header">
        <div class="container">
            <h1><a href="index.php">TorqueTrend</a></h1>
            <nav>
                <a href="index.php" class="nav-link">Home</a>
                <a href="admin/login.php" class="nav-link">Admin</a>
                <a href="#contact" class="nav-link">Contact</a>
                <a href="#subscribe" class="nav-link">Subscribe</a>
            </nav>
        </div>
    </header>
    <main class="container" style="margin-top: 80px;">
        <section id="latest-articles">
            <h2>Latest Articles</h2>
            <?php if (!empty($articles)): ?>
                <div class="articles">
                    <?php foreach ($articles as $article): ?>
                        <div class="article-card">
                            <?php if ($article['image']): ?>
                                <img src="<?php echo htmlspecialchars($article['image']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" class="article-image" loading="lazy">
                            <?php endif; ?>
                            <div class="article-content">
                                <h3><?php echo htmlspecialchars($article['title']); ?></h3>
                                <p><?php echo htmlspecialchars(substr($article['content'], 0, 150)) . '...'; ?></p>
                                <a href="article.php?id=<?php echo $article['id']; ?>" class="btn-secondary">Read More</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No articles found.</p>
            <?php endif; ?>
        </section>
        <section id="contact" class="contact-section">
            <h2>Contact Us</h2>
            <form id="contact-form">
                <div class="form-group">
                    <label for="contact-name">Name</label>
                    <input type="text" id="contact-name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="contact-email">Email</label>
                    <input type="email" id="contact-email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="contact-message">Message</label>
                    <textarea id="contact-message" name="message" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn-primary">Send Message</button>
                <div id="contact-response" class="response-message"></div>
            </form>
        </section>
        <section id="subscribe" class="subscribe-section">
            <h2>Subscribe to Our Newsletter</h2>
            <form id="subscribe-form">
                <div class="form-group">
                    <label for="subscribe-email">Email</label>
                    <input type="email" id="subscribe-email" name="email" required>
                </div>
                <button type="submit" class="btn-primary">Subscribe</button>
                <div id="subscribe-response" class="response-message"></div>
            </form>
        </section>
    </main>
    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> TorqueTrend. All rights reserved.</p>
        </div>
    </footer>
    <script src="scripts.js"></script>
    <script>
        // Handle Contact Form Submission
        document.getElementById('contact-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const responseDiv = document.getElementById('contact-response');
            const formData = new FormData(this);
    
            fetch('backend/contact.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                responseDiv.textContent = data.message;
                responseDiv.className = 'response-message ' + data.status;
                if(data.status === 'success') {
                    this.reset();
                }
            })
            .catch(error => {
                responseDiv.textContent = 'An error occurred. Please try again.';
                responseDiv.className = 'response-message error';
            });
        });
    
        // Handle Subscribe Form Submission
        document.getElementById('subscribe-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const responseDiv = document.getElementById('subscribe-response');
            const formData = new FormData(this);
    
            fetch('backend/subscribe.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                responseDiv.textContent = data.message;
                responseDiv.className = 'response-message ' + data.status;
                if(data.status === 'success') {
                    this.reset();
                }
            })
            .catch(error => {
                responseDiv.textContent = 'An error occurred. Please try again.';
                responseDiv.className = 'response-message error';
            });
        });
    </script>
</body>
</html>
