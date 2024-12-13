<?php
session_start();
require_once 'includes/models.php';


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['receiver_id'], $_POST['message']) && !empty($_POST['receiver_id']) && !empty($_POST['message'])) {

        $receiverId = $_POST['receiver_id']; 
        $senderId = $_SESSION['user_id']; 
        $message = $_POST['message']; 


        global $conn;
        $query = "INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iis", $senderId, $receiverId, $message);


        if ($stmt->execute()) {
            header('Location: inbox.php?success=1'); 
            exit;
        } else {
            echo "Error sending message! Please try again later.";
        }
    } else {
        echo "Please submit the form to send a message.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send a New Message</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
            color: #555;
        }
        select, textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            height: 100px;
            resize: vertical;
        }
        button {
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #2980b9;
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
        <h1>Send a New Message</h1>

        <form method="POST" action="sendmessages.php">
            <label for="receiver_id">Receiver:</label>
            <select name="receiver_id" id="receiver_id" required>
                <option value="">Select a receiver</option>
 
                <option value="1">HR</option>
                <option value="2">Applicant</option>
            </select>

            <label for="message">Message:</label>
            <textarea name="message" id="message" required placeholder="Write your message here..."></textarea>

            <button type="submit">Send Message</button>
        </form>

        <div class="links">
            <a href="inbox.php">Back to Inbox</a>
        </div>
    </div>
</body>
</html>