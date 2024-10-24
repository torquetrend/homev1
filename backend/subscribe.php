<?php
// backend/subscribe.php

header('Content-Type: application/json');

require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');

    // Validate email
    if (empty($email)) {
        echo json_encode(['status' => 'error', 'message' => 'Email is required.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email address.']);
        exit;
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM subscribers WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(['status' => 'info', 'message' => 'You are already subscribed to our newsletter.']);
        $stmt->close();
        $conn->close();
        exit;
    }

    $stmt->close();

    // Insert new subscriber
    $stmt = $conn->prepare("INSERT INTO subscribers (email, subscribed_at) VALUES (?, NOW())");
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        // Send confirmation email
        $to = $email;
        $subject = "Thank You for Subscribing to TorqueTrend";
        $body = "Hi,\n\nThank you for subscribing to TorqueTrend's newsletter! Stay tuned for the latest automotive news, reviews, and exclusive offers.\n\nBest Regards,\nTorqueTrend Team";
        $headers = "From: no-reply@torquetrend.com";

        if (mail($to, $subject, $body, $headers)) {
            echo json_encode(['status' => 'success', 'message' => 'Thank you for subscribing!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Subscription successful but failed to send confirmation email.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'There was an error subscribing you. Please try again later.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
