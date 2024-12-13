<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'undefined';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
        }
        .page-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            padding: 20px;
            box-sizing: border-box;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
        }
        .button-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        a {
            display: block;
            background-color: #3498db;
            color: #fff;
            padding: 12px 15px;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.3s;
            text-align: center;
            font-size: 16px;
        }
        a:hover {
            background-color: #2980b9;
        }
        p {
            text-align: center;
            margin-top: 20px;
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="container">
            <h1>Welcome, <?= htmlspecialchars(ucfirst($role)) ?>!</h1>
            <div class="button-container">
                <?php if ($role === 'applicant'): ?>
                    <a href="applicant.php">Go to Applicant Dashboard</a>
                <?php elseif ($role === 'HR'): ?>
                    <a href="hr.php">Go to HR Dashboard</a>
                <?php endif; ?>
                <a href="logout.php">Logout</a>
            </div>
            <?php if ($role === 'undefined'): ?>
                <p>Your role is not defined. Please contact support.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>