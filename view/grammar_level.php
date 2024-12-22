<?php
session_start();
require_once '../model/db.php';
require_once '../model/VocabularyModel.php';
require_once '../controller/VocabularyController.php';

// Lấy lesson_id từ URL, nếu không có thì mặc định là 1
$lesson_id = $_GET['lesson_id'] ?? 1;
$controller = new VocabularyController($pdo);
$vocabulary = $controller->showVocabularyLesson($lesson_id);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_progress'])) {
    $username = $_SESSION['username'];
    $learned_count = $_POST['learned_count'];

    $sql = "INSERT INTO vocabulary_progress (username, lesson_id, learned_count, progress_date) 
            VALUES (:username, :lesson_id, :learned_count, NOW()) 
            ON DUPLICATE KEY UPDATE learned_count = :learned_count, progress_date = NOW()";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':username' => $username,
        ':lesson_id' => $lesson_id,
        ':learned_count' => $learned_count
    ]);

    echo "<script>alert('Tiến độ đã được lưu!'); window.location.href='../index.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Học từ vựng - Bài <?php echo $lesson_id; ?></title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="../public/css/lessons.css">
    <link rel="stylesheet" href="../public/css/navbar.css">
    <link rel="stylesheet" href="../public/css/grammar_level.css">
</head>
<body>
    <?php include '../view/page/navbar.php'; ?>
    <!-- Thêm nút quay lại trang index.php -->
    <a href="../index.php" class="back-button">
        <button class="back-button-style">Quay lại Trang Chủ</button>
    </a>
    <div class="container">
        <div class="vocabulary-container">
            <div class="word-list">
                <?php foreach ($vocabulary as $word): ?>
                    <div class="word-card" id="word-<?php echo $word['id']; ?>">
                        <div class="word-info">
                            <span class="word"><?php echo htmlspecialchars($word['word']); ?></span>
                            <span class="pronunciation"><?php echo htmlspecialchars($word['pronunciation']); ?></span>
                            <div class="word-type"><?php echo htmlspecialchars($word['word_type']); ?></div>
                            <div class="meaning"><?php echo htmlspecialchars($word['meaning']); ?></div>
                        </div>
                        <div class="examples">
                            <?php if (isset($word['examples']) && is_array($word['examples'])): ?>
                                <?php foreach ($word['examples'] as $example): ?>
                                    <div class="example">
                                        <div class="english"><?php echo htmlspecialchars($example['english_sentence']); ?></div>
                                        <div class="vietnamese"><?php echo htmlspecialchars($example['vietnamese_sentence']); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>Không có ví dụ cho từ này.</p>
                            <?php endif; ?>
                        </div>


                        <button class="mark-learned" data-id="<?php echo $word['id']; ?>">
                            Đánh dấu đã học
                        </button>

                    </div>
                <?php endforeach; ?>
                <div class="notification" id="notification">Đánh dấu từ thành công!</div>
                <button class="mark-complete" data-id="<?php echo $word['id']; ?>">Hoàn thành</button>
            </div>

            <div class="practice-section">
                <h2>Luyện tập</h2>
                <div id="practice-area">
                    <p><strong>Tổng số từ:</strong> <span id="total-count">0</span></p>
                    <p><strong>Đã học:</strong> <span id="learned-count">0</span></p>
                    <p><strong>Chưa học:</strong> <span id="not-learned-count">0</span></p>
                </div>
                <form method="POST">
                    <input type="hidden" id="learned-count-input" name="learned_count" value="0">
                    <button type="submit" name="save_progress" class="complete-button">Hoàn thành</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.querySelector('.complete-button').addEventListener('click', function() {
            const learnedCount = document.getElementById('learned-count').textContent;
            document.getElementById('learned-count-input').value = learnedCount;
        });
        // Hàm cập nhật số lượng từ đã học và chưa học
        function updateCounts() {
            const totalCountElem = document.getElementById('total-count');
            const learnedCountElem = document.getElementById('learned-count');
            const notLearnedCountElem = document.getElementById('not-learned-count');
            const markLearnedButtons = document.querySelectorAll('.mark-learned');

            // Tổng số từ
            const total = markLearnedButtons.length;
            totalCountElem.textContent = total;

            // Số từ đã học
            let learnedCount = 0;
            markLearnedButtons.forEach(button => {
                if (button.disabled) {
                    learnedCount++;
                }
            });
            learnedCountElem.textContent = learnedCount;
            notLearnedCountElem.textContent = total - learnedCount;
        }
        updateCounts();
        // Xử lý đánh dấu từ đã học
        document.querySelectorAll('.mark-learned').forEach(button => {
            button.addEventListener('click', function() {
                this.textContent = 'Đã học';
                this.disabled = true;
                // Cập nhật lại số lượng từ sau khi đánh dấu
                updateCounts();
            });
        });
        document.querySelectorAll('.mark-complete').forEach(button => {
            button.addEventListener('click', function() {
                const wordId = this.getAttribute('data-id');
                // Gửi AJAX để lưu trạng thái vào cơ sở dữ liệu
                fetch('../controller/VocabularyController.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `word_id=${wordId}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Đã lưu trạng thái hoàn thành!');
                            this.textContent = 'Đã hoàn thành';
                            this.disabled = true;
                        } else {
                            alert('Lỗi khi lưu dữ liệu.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    </script>
</body>

</html>