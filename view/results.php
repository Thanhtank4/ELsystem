<?php
session_start();
include '../model/db.php';

if (!isset($_SESSION['quiz_results'])) {
    header('Location: index.php');
    exit();
}

$quizResults = $_SESSION['quiz_results'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Results</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <style>
        .result-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        .result-container h2 {
            margin-bottom: 1rem;
            color: #2a5298;
        }
        .result-container p {
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }
        .back-btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background: #2a5298;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.3s ease;
        }
        .back-btn:hover {
            background: #1e3c72;
        }
    </style>
</head>
<body>
    <div class="result-container">
        <h2>Your Quiz Results</h2>
        <p>Score: <?php echo $quizResults['score']; ?> / <?php echo $quizResults['total']; ?></p>
        <p>Percentage: <?php echo round($quizResults['percentage'], 2); ?>%</p>
        <a href="partice.php" class="back-btn">Take Another Quiz</a>
    </div>
</body>
</html>
