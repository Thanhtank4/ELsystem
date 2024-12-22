<?php
session_start();
include '../model/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username']; // Giả sử tên người dùng được lưu trong session khi đăng nhập
    $score = 0;
    $totalQuestions = 0;

    $sql = "SELECT * FROM practice";
    $result = $db->query($sql);

    if ($result->num_rows > 0) {
        while ($question = $result->fetch_assoc()) {
            $totalQuestions++;
            $question_id = $question['id'];
            $correct_answer = $question['correct_answer'];

            if (isset($_POST['answer_' . $question_id])) {
                $user_answer = $_POST['answer_' . $question_id];
                if ($user_answer === $correct_answer) {
                    $score++;
                }
            }
        }
    }

    $percentage = ($score / $totalQuestions) * 100;

    // Lưu kết quả vào database
    $stmt = $db->prepare("INSERT INTO results (username, score, total_questions, percentage) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siid", $username, $score, $totalQuestions, $percentage);
    $stmt->execute();
    $stmt->close();

    // Lưu kết quả vào session
    $_SESSION['quiz_results'] = [
        'score' => $score,
        'total' => $totalQuestions,
        'percentage' => $percentage
    ];

    // Chuyển hướng đến trang kết quả
    header('Location: ../view/results.php');
exit();

}
?>
