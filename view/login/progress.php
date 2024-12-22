<?php
session_start();
include '../../model/db.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
$sql = "SELECT * FROM results WHERE username = ? ORDER BY created_at DESC";
$stmt = $db->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$progress_data = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$sql_vocab = "SELECT lesson_id, learned_count, progress_date FROM vocabulary_progress WHERE username = ? ORDER BY progress_date DESC";
$stmt_vocab = $db->prepare($sql_vocab);
$stmt_vocab->bind_param('s', $username);
$stmt_vocab->execute();
$result_vocab = $stmt_vocab->get_result();
$vocabulary_progress_data = $result_vocab->fetch_all(MYSQLI_ASSOC);
$stmt_vocab->close();

// Tính điểm trung bình
$total_score = 0;
$total_records = count($progress_data);
if ($total_records > 0) {
    foreach ($progress_data as $progress) {
        $total_score += $progress['score'];
    }
    $average_score = round($total_score / $total_records, 2);
} else {
    $average_score = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiến độ học tập - Hi English</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f9;
        }

        .progress-container {
            max-width: 1000px;
            margin: 2rem auto;
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .progress-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        h1 {
            color: #003366;
        }

        .progress-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .progress-table th, .progress-table td {
            text-align: center;
            padding: 1rem;
            border: 1px solid #ddd;
        }

        .progress-table th {
            background: #003366;
            color: white;
            font-weight: bold;
        }

        .progress-table tr:nth-child(even) {
            background: #f9f9f9;
        }

        .progress-table tr:hover {
            background: #f1f1f1;
        }

        .progress-summary {
            text-align: center;
            margin-top: 2rem;
        }

        .progress-summary span {
            display: inline-block;
            background: #003366;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: bold;
        }

        /* Hiệu ứng */
        .progress-table td {
            transform: scale(1);
        }

        .progress-table td:hover {
            transform: scale(1.1);
        }

        .back-button {
            display: inline-block;
            margin-bottom: 1.5rem;
            padding: 0.5rem 1rem;
            background: #003366;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .back-button:hover {
            background: #00509e;
        }

        @media (max-width: 768px) {
            .progress-container {
                padding: 1rem;
            }

            .progress-table th, .progress-table td {
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="progress-container">
        <!-- Nút quay lại trang chủ -->
        <a href="/index.php" class="back-button"><i class="fas fa-arrow-left"></i> Quay lại Trang chủ</a>

        <div class="progress-header">
            <h1>Tiến độ học tập</h1>
            <p>Theo dõi kết quả bài tập gần đây và điểm trung bình:</p>
        </div>

        <!-- Bảng tiến độ -->
        <table class="progress-table">
            <thead>
                <tr>
                    <th>Ngày làm bài</th>
                    <th>Điểm</th>
                    <th>Tổng số câu</th>
                    <th>Phần trăm</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($progress_data) > 0): ?>
                    <?php foreach ($progress_data as $progress): ?>
                        <tr>
                            <td><?php echo date('d/m/Y H:i', strtotime($progress['created_at'])); ?></td>
                            <td><?php echo $progress['score']; ?></td>
                            <td><?php echo $progress['total_questions']; ?></td>
                            <td><?php echo round($progress['percentage'], 2); ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Chưa có dữ liệu.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Tổng hợp -->
        <div class="progress-summary">
            <h2>Điểm trung bình</h2>
            <span><?php echo $average_score; ?></span>
        </div>
        <div class="progress-header">
            <h1>Tiến độ từ vựng</h1>
            <p>Danh sách các bài học từ vựng đã học:</p>
        </div>

        <table class="progress-table">
            <thead>
                <tr>
                    <th>Bài học</th>
                    <th>Số từ đã học</th>
                    <th>Ngày cập nhật</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($vocabulary_progress_data) > 0): ?>
                    <?php foreach ($vocabulary_progress_data as $vocab): ?>
                        <tr>
                            <td>Bài <?php echo $vocab['lesson_id']; ?></td>
                            <td><?php echo $vocab['learned_count']; ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($vocab['progress_date'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">Chưa có dữ liệu.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
