<?php
// practice_tests.php
session_start();
include '../model/db.php';

$level = isset($_GET['level']) ? (int)$_GET['level'] : 1;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practice Tests - Level <?php echo $level; ?></title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="../public/css/lessons.css">
    <link rel="stylesheet" href="../public/css/navbar.css">
</head>

<body>
    <?php include '../view/page/navbar.php'; ?>

    <div class="container main-content">
        <div class="breadcrumb">
            <a href="../index.php">Home</a> > Practice Tests > Level <?php echo $level; ?>
        </div>

        <div class="level-header">
            <h1>Practice Tests Level <?php echo $level; ?></h1>
            <div class="test-overview">
                <div class="overview-item">
                    <span class="overview-label">Total Tests:</span>
                    <span class="overview-value">50</span>
                </div>
                <div class="overview-item">
                    <span class="overview-label">Completed:</span>
                    <span class="overview-value">10</span>
                </div>
                <div class="overview-item">
                    <span class="overview-label">Average Score:</span>
                    <span class="overview-value">85%</span>
                </div>
            </div>
        </div>

        <div class="tests-grid">
            <!-- Example test cards -->
            <div class="test-card">
                <div class="test-header">
                    <h3>Test 1</h3>
                    <span class="test-score">Score: 90%</span>
                </div>
                <div class="test-content">
                    <div class="test-info">
                        <p>Topics: Present Tense, Vocabulary</p>
                        <p>Duration: 30 minutes</p>
                        <p>Questions: 25</p>
                    </div>
                    <a href="practice_1.php?id=1" class="test-btn">Retake Test</a>
                </div>
            </div>

        </div>
    </div>
</body>

</html>