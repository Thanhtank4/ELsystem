<?php
// conversations.php
session_start();
include '../model/db.php';

$level = isset($_GET['level']) ? (int)$_GET['level'] : 1;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversations - Level <?php echo $level; ?></title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="../public/css/lessons.css">
    <link rel="stylesheet" href="../public/css/navbar.css">
</head>
<body>
<?php include '../view/page/navbar.php'; ?>

    <div class="container main-content">
        <div class="breadcrumb">
            <a href="../index.php">Home</a> > Conversations > Level <?php echo $level; ?>
        </div>

        <div class="level-header">
            <h1>Conversation Practice Level <?php echo $level; ?></h1>
            <div class="level-description">
                <p>Practice real-world conversations at your level</p>
            </div>
        </div>

        <div class="conversations-grid">
            <!-- Example conversation cards -->
            <div class="conversation-card">
                <div class="conversation-image">
                    <img src="assets/images/conversation1.jpg" alt="At the Restaurant">
                </div>
                <div class="conversation-content">
                    <h3>At the Restaurant</h3>
                    <p>Learn how to order food and interact with restaurant staff</p>
                    <div class="conversation-meta">
                        <span>Duration: 10 minutes</span>
                        <span>Difficulty: Easy</span>
                    </div>
                    <div class="conversation-actions">
                        <a href="conversation_detail.php?id=1" class="practice-btn">Start Practice</a>
                        <button class="preview-btn">Preview</button>
                    </div>
                </div>
            </div>

            <div class="conversation-card">
                <div class="conversation-image">
                    <img src="assets/images/conversation2.jpg" alt="Job Interview">
                </div>
                <div class="conversation-content">
                    <h3>Job Interview</h3>
                    <p>Practice common job interview questions and responses</p>
                    <div class="conversation-meta">
                        <span>Duration: 15 minutes</span>
                        <span>Difficulty: Medium</span>
                    </div>
                    <div class="conversation-actions">
                        <a href="conversation_detail.php?id=2" class="practice-btn">Start Practice</a>
                        <button class="preview-btn">Preview</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Audio Preview Modal -->
    <div id="previewModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Conversation Preview</h2>
            <audio id="previewAudio" controls>
                <source src="" type="audio/mp3">
                Your browser does not support the audio element.
            </audio>
        </div>
    </div>

    <script src="assets/js/conversations.js"></script>
</body>
</html>