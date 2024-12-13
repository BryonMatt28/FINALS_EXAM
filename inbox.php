<?php
session_start();
require_once 'includes/models.php';



if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}


$userId = $_SESSION['user_id'];
$messages = getMessagesForUser($userId);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message_id'], $_POST['reply'])) {
    $messageId = $_POST['message_id'];
    $replyContent = $_POST['reply'];
    $receiverId = $_POST['receiver_id'];


    $result = sendReply($messageId, $replyContent, $userId, $receiverId);
    if ($result === true) {
        header('Location: inbox.php?success=1'); 
        exit;
    } else {
        echo '<div class="error">' . htmlspecialchars($result) . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }
        .message-item {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }
        .message-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .message-sender {
            font-weight: bold;
            color: #3498db;
        }
        .message-content {
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .reply-form {
            margin-top: 20px;
        }
        .reply-form textarea {
            width: 100%;
            height: 100px;
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .reply-form button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        .reply-form button:hover {
            background-color: #2980b9;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
        }
        .links {
            text-align: center;
            margin-top: 20px;
        }
        .links a {
            color: #3498db;
            text-decoration: none;
        }
        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Inbox</h1>

        <?php if (isset($_GET['success'])): ?>
            <div class="success-message">Your reply has been sent successfully!</div>
        <?php endif; ?>

        <?php if (count($messages) > 0): ?>
            <?php foreach ($messages as $message): ?>
                <div class="message-item">
                    <div class="message-header">
                        <span class="message-sender"><?= htmlspecialchars($message['sender_name']) ?></span>
                        <span class="message-status"><?= htmlspecialchars($message['status']) ?></span>
                    </div>
                    <div class="message-content">
                        <p><?= nl2br(htmlspecialchars($message['message'])) ?></p>
                    </div>

                    <div class="reply-form">
                        <?php if (isset($message['status']) && $message['status'] != 'Replied'): ?>
                            <form method="POST" action="inbox.php">
                                <input type="hidden" name="message_id" value="<?= htmlspecialchars($message['id']) ?>
                                <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($message['sender_id']) ?>
                                <textarea name="reply" placeholder="Write your reply here..." required></textarea><br>
                                <button type="submit">Send Reply</button>
                            </form>
                        <?php else: ?>
                            <p><strong>Your Reply:</strong> 
                                <?= isset($message['reply']) && !empty($message['reply']) ? nl2br(htmlspecialchars($message['reply'])) : 'No reply sent yet.' ?>
                            </p>
                            <p><strong>Replied at:</strong> 
                                <?= isset($message['replied_at']) && !empty($message['replied_at']) ? htmlspecialchars($message['replied_at']) : 'N/A' ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>You have no messages.</p>
        <?php endif; ?>


        <div class="links">
            <a href="index.php">Back to Homepage</a> | 
            <a href="sendmessages.php">Send a New Message</a>
        </div>
    </div>
</body>
</html>
