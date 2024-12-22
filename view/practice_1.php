<?php
session_start();
include '../model/db.php';

$sql = "SELECT * FROM practice";
$result = $db->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practice Test</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="../public/css/lessons.css">
    <link rel="stylesheet" href="../public/css/navbar.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .quiz-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
        }

        .quiz-info {
            display: flex;
            justify-content: space-around;
            margin-top: 1.5rem;
            padding: 1rem;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
        }

        .info-item {
            text-align: center;
            padding: 0.5rem 1rem;
        }

        .info-label {
            display: block;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            opacity: 0.9;
        }

        .info-value {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .questions-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .question-item {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #2a5298;
        }

        .question-header {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .question-number {
            background: #2a5298;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-weight: bold;
        }

        .options-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-top: 1rem;
        }

        .option-item {
            position: relative;
            padding: 1rem;
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .option-item:hover {
            border-color: #2a5298;
            transform: translateY(-2px);
        }

        .option-item input[type="radio"] {
            display: none;
        }

        .option-item label {
            display: flex;
            align-items: center;
            margin: 0;
            cursor: pointer;
        }

        .option-item label::before {
            content: '';
            width: 20px;
            height: 20px;
            border: 2px solid #2a5298;
            border-radius: 50%;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .option-item input[type="radio"]:checked + label::before {
            background: #2a5298;
            box-shadow: inset 0 0 0 4px white;
        }

        .quiz-navigation {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 1rem;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-btn {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .submit-btn {
            background: #2a5298;
            color: white;
        }

        .submit-btn:hover {
            background: #1e3c72;
        }

        .progress-container {
            flex-grow: 1;
            margin: 0 2rem;
        }

        .progress-bar {
            height: 8px;
            background: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress {
            height: 100%;
            background: #2a5298;
            width: 0%;
            transition: width 0.3s ease;
        }

        @media (max-width: 768px) {
            .options-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <?php include '../view/page/navbar.php'; ?>

    <div class="container main-content">
        <div class="quiz-header">
            <h1>Practice Test</h1>
            <div class="quiz-info">
                <div class="info-item">
                    <span class="info-label">Time Remaining</span>
                    <span class="info-value" id="timer">30:00</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Questions</span>
                    <span class="info-value"><?php echo $result->num_rows; ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Progress</span>
                    <span class="info-value" id="progress-text">0%</span>
                </div>
            </div>
        </div>

        <?php if ($result->num_rows > 0) { ?>
            <form action="../controller/submit_answer.php" method="POST" id="quiz-form">
                <div class="questions-container">
                    <?php 
                    $questionNumber = 1;
                    while ($question = $result->fetch_assoc()) {
                        // Lấy câu hỏi và các lựa chọn từ cơ sở dữ liệu
                        $question_id = $question['id'];
                        $question_text = $question['question'];
                        $options = isset($question['options']) ? json_decode($question['options'], true) : [];
                        echo '<div class="question-item">';
                        echo '<div class="question-header">';
                        echo '<div class="question-number">' . $questionNumber . '</div>';
                        echo '<h3>' . $question_text . '</h3>';
                        echo '</div>'; // question-header

                        echo '<div class="options-grid">';
                        foreach ($options as $key => $option) {
                            echo '<div class="option-item">';
                            echo '<input type="radio" id="answer_' . $question_id . '_' . $key . '" name="answer_' . $question_id . '" value="' . $option . '" required>';
                            echo '<label for="answer_' . $question_id . '_' . $key . '">' . $option . '</label>';
                            echo '</div>'; // option-item
                        }
                        echo '</div>'; // options-grid
                        echo '</div>'; // question-item
                        $questionNumber++;
                    }
                    ?>
                </div> <!-- questions-container -->
                
                <div class="quiz-navigation">
                    <button type="submit" class="nav-btn submit-btn">Submit</button>
                </div> <!-- quiz-navigation -->
            </form>
        <?php } else { ?>
            <p>No questions available.</p>
        <?php } ?>
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST') { ?>
            <div class="result-container">
                <h2>Quiz Results</h2>
                <p>Score: <?php echo $_SESSION['quiz_results']['score']; ?> / <?php echo $_SESSION['quiz_results']['total']; ?></p>
                <p>Percentage: <?php echo round($_SESSION['quiz_results']['percentage'], 2); ?>%</p>
            </div>
        <?php } ?>
    </div> <!-- container main-content -->

    <script>
        // Timer logic (optional)
        let timeLeft = 1800; // 30 minutes in seconds
        const timerElement = document.getElementById('timer');
        const progressText = document.getElementById('progress-text');
        const progressBar = document.querySelector('.progress');

        function updateTimer() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            timerElement.textContent = `${minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
            const progress = ((1800 - timeLeft) / 1800) * 100;
            progressText.textContent = Math.round(progress) + '%';
            progressBar.style.width = progress + '%';

            if (timeLeft > 0) {
                timeLeft--;
                setTimeout(updateTimer, 1000);
            } else {
                alert('Time is up!');
                document.getElementById('quiz-form').submit();
            }
        }

        updateTimer();
    </script>
</body>
</html>
