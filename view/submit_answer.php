<?php
session_start();
include '../../model/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $score = 0;
    $total = count($_POST); // Tổng số câu hỏi
    $username = $_SESSION['username'];

    // Lấy đáp án đúng từ cơ sở dữ liệu
    $sql = "SELECT id, correct_answer FROM practice";
    $result = $db->query($sql);
    $answers = [];
    while ($row = $result->fetch_assoc()) {
        $answers[$row['id']] = $row['correct_answer'];
    }

    // Kiểm tra câu trả lời
    foreach ($_POST as $question_id => $user_answer) {
        $question_id = str_replace('answer_', '', $question_id); // Lấy ID câu hỏi
        if (isset($answers[$question_id]) && $answers[$question_id] === $user_answer) {
            $score++;
        }
    }

    // Tính phần trăm hoàn thành
    $percentage = ($score / $total) * 100;

    // Lưu kết quả vào session
    $_SESSION['quiz_results'] = [
        'score' => $score,
        'total' => $total,
        'percentage' => $percentage
    ];

    // Lưu vào cơ sở dữ liệu
    $stmt = $db->prepare("INSERT INTO progress (username, score, total, percentage, date) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param('siid', $username, $score, $total, $percentage);
    $stmt->execute();
    $stmt->close();

    // Quay lại trang kết quả
    header('Location: ../view/page/progress.php');
    exit();
}
?>
