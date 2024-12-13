<?php
session_start();
if ($_SESSION['role'] != 'applicant') {
    header('Location: index.php');
    exit();
}

require_once 'includes/models.php';
$jobs = getJobPosts();

$successMessage = isset($_GET['success']) ? "Application submitted successfully!" : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Jobs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
            color: #2c3e50;
        }
        .job-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        .job-square {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background-color: #ffffff;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 15px;
            text-align: center;
            transition: transform 0.3s;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .job-square:hover {
            transform: translateY(-5px);
        }
        .job-title {
            font-size: 18px;
            font-weight: bold;
            color: #3498db;
            margin-bottom: 10px;
        }
        .job-description {
            font-size: 14px;
            color: #555;
            margin-bottom: 15px;
        }
        .apply-button {
            background-color: #3498db;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .apply-button:hover {
            background-color: #2980b9;
        }
        .notification {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }
        .navigation {
            text-align: center;
            margin-top: 20px;
        }
        .navigation a {
            display: inline-block;
            background-color: #3498db;
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            margin: 0 10px;
        }
        .navigation a:hover {
            background-color: #2980b9;
        }
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .modal-content form {
            display: flex;
            flex-direction: column;
        }
        .modal-content label {
            margin-bottom: 5px;
        }
        .modal-content input,
        .modal-content textarea {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .modal-close {
            background-color: #e74c3c;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .modal-close:hover {
            background-color: #c0392b;
        }
    </style>
    <script>
        function openModal(jobId) {
            document.getElementById('job_id').value = jobId;
            document.getElementById('applyModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('applyModal').style.display = 'none';
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Available Jobs</h1>

        <?php if ($successMessage): ?>
            <div class="notification"> <?= htmlspecialchars($successMessage) ?> </div>
        <?php endif; ?>

        <div class="job-grid">
            <?php foreach ($jobs as $job): ?>
                <div class="job-square">
                    <div class="job-title"> <?= htmlspecialchars($job['title']) ?> </div>
                    <div class="job-description"> <?= htmlspecialchars($job['description']) ?> </div>
                    <button class="apply-button" onclick="openModal(<?= htmlspecialchars($job['id']) ?>)">Apply</button>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="navigation">
            <a href="inbox.php">View Inbox</a>
            <a href="index.php">Go to Homepage</a>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal" id="applyModal">
        <div class="modal-content">
            <h2>Apply for Job</h2>
            <form action="includes/handleForms.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="apply">
                <input type="hidden" name="job_id" id="job_id">
                <label for="message">Message:</label>
                <textarea name="message" id="message" rows="4" required></textarea>
                <label for="resume">Upload Resume (PDF only):</label>
                <input type="file" name="resume" id="resume" accept=".pdf" required>
                <button type="submit" class="apply-button">Submit Application</button>
                <button type="button" class="modal-close" onclick="closeModal()">Cancel</button>
            </form>
        </div>
    </div>
</body>
</html>
