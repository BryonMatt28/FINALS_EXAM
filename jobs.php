<?php

session_start();
require_once 'includes/models.php';


$jobPosts = getJobPosts();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Job Posts</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <h1>Available Job Posts</h1>

    <?php if (!empty($jobPosts)): ?>
        <div class="job-list">
            <?php foreach ($jobPosts as $job): ?>
                <div class="job-item">
                    <h2><?= htmlspecialchars($job['title']) ?></h2>
                    <p><?= htmlspecialchars($job['description']) ?></p>
                    <p><strong>Posted on:</strong> <?= htmlspecialchars($job['created_at']) ?></p>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'applicant'): ?>
                        <form action="includes/handleForms.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="apply">
                            <input type="hidden" name="job_id" value="<?= $job['id'] ?>">
                            <textarea name="message" placeholder="Why are you a good fit for this job?" required></textarea>
                            <label>Upload Resume (PDF only):</label>
                            <input type="file" name="resume" accept="application/pdf" required>
                            <button type="submit">Apply</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No job posts available at the moment.</p>
    <?php endif; ?>

    <a href="index.php">Back to Homepage</a>
</body>
</html>
