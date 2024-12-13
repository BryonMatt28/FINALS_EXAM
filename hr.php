<?php
session_start();
if ($_SESSION['role'] != 'HR') {
    header('Location: index.php');
    exit;
}

require_once 'includes/models.php';

$message = '';
if (isset($_GET['success'])) {
    $message = "Action completed successfully!";
} elseif (isset($_GET['error'])) {
    $message = "Failed to process the action. Please try again.";
}

$jobPosts = getJobPosts();

$jobApplications = [];
foreach ($jobPosts as $jobPost) {
    $jobApplications[$jobPost['id']] = getApplicationsForJob($jobPost['id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $applicationId = intval($_POST['application_id']);
    $action = $_POST['action'];

    if ($action === 'accept') {
        if (acceptApplication($applicationId)) {
            header('Location: hr.php?success=1');
            exit;
        } else {
            header('Location: hr.php?error=1');
            exit;
        }
    } elseif ($action === 'deny') {
        if (denyApplication($applicationId)) {
            header('Location: hr.php?success=1');
            exit;
        } else {
            header('Location: hr.php?error=1');
            exit;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Dashboard</title>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --background-color: #f4f4f4;
            --card-background: #ffffff;
            --text-color: #333333;
            --border-color: #e0e0e0;
        }
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--background-color);
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: var(--card-background);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            color: var(--secondary-color);
            text-align: center;
            margin-bottom: 30px;
        }
        .message {
            text-align: center;
            padding: 15px;
            margin-bottom: 30px;
            border-radius: 4px;
            font-weight: bold;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
        }
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            max-width: 500px;
            margin: 0 auto 40px;
        }
        input, textarea, button {
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 16px;
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
            font-weight: bold;
        }
        button:hover {
            background-color: #2980b9;
        }
        .job-list {
            display: grid;
            gap: 30px;
        }
        .job-item {
            background-color: var(--card-background);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .job-title {
            font-size: 20px;
            font-weight: bold;
            color: var(--secondary-color);
            margin-bottom: 15px;
        }
        .job-description {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 4px;
            font-size: 14px;
        }
        .application {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 4px;
            font-size: 14px;
        }
        .application.accepted {
            background-color: #e8f5e9;
            border-left: 4px solid #4caf50;
        }
        .application.rejected {
            background-color: #ffebee;
            border-left: 4px solid #f44336;
        }
        .application p {
            margin: 5px 0;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        .action-buttons button {
            flex: 1;
            padding: 8px;
            font-size: 14px;
        }
        .links {
            margin-top: 40px;
            text-align: center;
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .links a {
            display: inline-block;
            padding: 10px 20px;
            background-color: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .links a:hover {
            background-color: #2980b9;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>HR Dashboard</h1>

        <?php if ($message): ?>
            <p class="message <?= isset($_GET['success']) ? 'success-message' : 'error-message' ?>">
                <?= htmlspecialchars($message) ?>
            </p>
        <?php endif; ?>

        <form action="includes/handleForms.php" method="POST">
            <input type="hidden" name="action" value="post_job">
            <input type="text" name="title" placeholder="Job Title" required>
            <textarea name="description" placeholder="Job Description" required></textarea>
            <button type="submit">Post New Job</button>
        </form>

        <h2>Job Applications</h2>
        <?php if (!empty($jobApplications)): ?>
            <div class="job-list">
                <?php foreach ($jobPosts as $jobPost): ?>
                    <div class="job-item">
                        <div class="job-title"><?= htmlspecialchars($jobPost['title']) ?></div>
                        <div class="job-description"><?= nl2br(htmlspecialchars($jobPost['description'])) ?></div>
                        <?php if (!empty($jobApplications[$jobPost['id']])): ?>
                            <?php foreach ($jobApplications[$jobPost['id']] as $application): ?>
                                <div class="application <?= $application['status'] ?>">
                                    <p><strong><?= htmlspecialchars($application['applicant_name']) ?></strong></p>
                                    <p><?= htmlspecialchars($application['message']) ?></p>
                                    <?php if (!empty($application['resume'])): ?>
                                        <p><a href="uploads/<?= htmlspecialchars($application['resume']) ?>" target="_blank">View Resume</a></p>
                                    <?php else: ?>
                                        <p>No resume uploaded.</p>
                                    <?php endif; ?>

                                    <?php if ($application['status'] == 'pending'): ?>
                                        <form action="hr.php" method="POST" class="action-buttons">
                                            <input type="hidden" name="application_id" value="<?= $application['id'] ?>">
                                            <button type="submit" name="action" value="accept">Accept</button>
                                            <button type="submit" name="action" value="deny">Deny</button>
                                        </form>
                                    <?php else: ?>
                                        <p>Status: <?= ucfirst($application['status']) ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No applications yet.</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No job posts available.</p>
        <?php endif; ?>

        <div class="links">
            <a href="index.php">Back to Homepage</a>
            <a href="inbox.php">Go to Inbox</a>
        </div>
    </div>
</body>
</html>