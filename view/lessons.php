<?php
session_start();
include '../model/db.php';

$level = isset($_GET['level']) ? (int)$_GET['level'] : 1;

$lesson_id = isset($_GET['lesson_id']) ? (int)$_GET['lesson_id'] : 1;

// Truy vấn bài học theo lesson_id
$stmt = $pdo->prepare("SELECT * FROM lessons WHERE id = ?");
$stmt->execute([$lesson_id]);
$lesson = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grammar Lessons - Level <?php echo $level; ?></title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="../public/css/lessons.css">
    <link rel="stylesheet" href="../public/css/navbar.css">
</head>
<body>
    <?php include '../view/page/navbar.php'; ?>

    <div class="container main-content">
        <div class="breadcrumb">
            <a href="../index.php">Home</a> > Grammar Lessons > Level <?php echo $level; ?>
        </div>

        <div class="level-header">
            <h1>Grammar Level <?php echo $level; ?></h1>
            <div class="level-description">
                <p>Master essential grammar concepts for level <?php echo $level; ?></p>
            </div>
        </div>

        <div class="lessons-grid">
            <!-- Example lesson items -->
            <div class="lesson-card">
                <div class="lesson-status completed">
                    <span class="status-icon">✓</span>
                </div>
                <div class="lesson-content">
                    <h3>Present Simple Tense</h3>
                    <p>Learn when and how to use the present simple tense</p>
                    <div class="lesson-meta">
                        <span>15 minutes</span>
                        <span>10 exercises</span>
                    </div>
                </div>
                <a href="grammar_level.php" class="lesson-btn">Start Lesson</a>
            </div>

            <div class="lesson-card">
                <div class="lesson-status locked">
                    <span class="status-icon">🔒</span>
                </div>
                <div class="lesson-content">
                    <h3>Present Continuous Tense</h3>
                    <p>Understanding ongoing actions in the present</p>
                    <div class="lesson-meta">
                        <span>20 minutes</span>
                        <span>12 exercises</span>
                    </div>
                </div>
                <a href="lesson_detail.php?id=2" class="lesson-btn disabled">Complete Previous Lesson</a>
            </div>
        </div>
    </div>
</body>
</html>