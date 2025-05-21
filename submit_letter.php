<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipient = isset($_POST['recipient']) ? trim($_POST['recipient']) : null;
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    if (empty($message)) {
        // Message is required
        header("Location: index.php?error=Message+is+required");
        exit;
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO letters (recipient, message) VALUES (?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Set parameters
    $recipient_param = !empty($recipient) ? $recipient : '';
    $message_param = $message;

    $stmt->bind_param("ss", $recipient_param, $message_param);

    // Execute
    if ($stmt->execute()) {
        header("Location: index.php?success=Letter+submitted+successfully");
        exit;
    } else {
        header("Location: index.php?error=Failed+to+submit+letter");
        exit;
    }
} else {
    // Invalid request method
    header("Location: index.php");
    exit;
}
?>
