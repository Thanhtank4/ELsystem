<?php
session_start();
require_once '../model/db.php';
require_once '../model/LessonModel.php';
require_once '../controller/LessonController.php';

// Lấy thông tin bài học đã hoàn thành
$lesson_id = $_GET['lesson_id'] ?? null;

if (!$lesson_id) {
    // Nếu không có lesson_id, quay lại danh sách bài học
    header('Location: grammar_lessons.php');
    exit;
}

$controller = new LessonController($pdo);
$lesson = $controller->getLesson($lesson_id);

// Kiểm tra xem bài học có tồn tại không
if (!$lesson) {
    echo 'Lesson not found!';
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lesson Complete - <?php echo htmlspecialchars($lesson['title']); ?></title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="../public/css/lessons.css">
    <link rel="stylesheet" href="../public/css/navbar.css">
    <style>
        .complete-container {
            text-align: center;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
            max-width: 600px;
        }

        .complete-container h1 {
            color: #28a745;
            font-size: 2rem;
        }

        .complete-container p {
            margin: 15px 0;
            font-size: 1.1rem;
        }

        .complete-container .actions {
            margin-top: 20px;
        }

        .complete-container .actions a {
            display: inline-block;
            margin: 10px;
            padding: 10px 20px;
            font-size: 1rem;
            color: #fff;
            background-color: #007bff;
            border-radius: 5px;
            text-decoration: none;
        }

        .complete-container .actions a:hover {
            background-color: #0056b3;
        }

        .next-lesson-btn {
            background-color: #28a745;
        }

        .next-lesson-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <?php include '../view/page/navbar.php'; ?>

    <div class="container">
        <div class="complete-container">
            <h1>🎉 Congratulations! 🎉</h1>
            <p>You have successfully completed the lesson: <strong><?php echo htmlspecialchars($lesson['title']); ?></strong>.</p>

            <div class="actions">
                <!-- Quay lại danh sách bài học -->
                <a href="lessons.php">Back to Lesson List</a>

                <!-- Bài học tiếp theo -->
               
                    <p>No more lessons available. Great job!</p>
            </div>
        </div>
    </div>
</body>
</html>
