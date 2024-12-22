<?php
session_start();
include '../model/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request.");
}

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to submit a test.");
}

$user_id = $_SESSION['user_id']; // ID người dùng
$test_id = (int)$_POST['test_id'];
$answers = $_POST['answers'] ?? [];

// Lấy câu hỏi và đáp án đúng từ bảng Questions
$stmt = $pdo->prepare("SELECT id, correct_answer FROM Questions WHERE test_id = ?");
$stmt->execute([$test_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$score = 0;
$total_questions = count($questions);

// Tính điểm
foreach ($questions as $question) {
    $qid = $question['id'];
    $correct_answer = $question['correct_answer'];

    if (isset($answers[$qid]) && $answers[$qid] == $correct_answer) {
        $score++;
    }
}

// Lưu kết quả vào bảng Scores
$stmt = $pdo->prepare("INSERT INTO Scores (user_id, test_id, score, total_questions) VALUES (?, ?, ?, ?)");
$stmt->execute([$user_id, $test_id, $score, $total_questions]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Result</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../public/css//navbar.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border: none;
            background: white;
            text-align: center;
        }

        .score-display {
            font-size: 2rem;
            font-weight: bold;
            color: #007bff;
        }

        .btn-group {
            margin-top: 20px;
        }

        .btn {
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <!-- Menu -->
    <?php include '../view/page/navbar.php'; ?>

    <div class="container">
        <div class="card p-4">
            <h1 class="text-primary">Test Completed!</h1>
            <p class="score-display">You scored <?php echo $score; ?> out of <?php echo $total_questions; ?>.</p>
            <div class="btn-group">
                <!-- Nút làm lại bài kiểm tra -->
                <a href="test_start.php?id=<?php echo $test_id; ?>" class="btn btn-outline-primary">Retake Test</a>
                <!-- Nút quay lại trang chính -->
                <a href="../index.php" class="btn btn-primary">Go to Home</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
