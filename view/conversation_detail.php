<?php
session_start();
include '../model/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql_conversation = "SELECT * FROM conver WHERE id = ?";
$stmt_conversation = $db->prepare($sql_conversation);
$stmt_conversation->bind_param('i', $id);
$stmt_conversation->execute();
$result_conversation = $stmt_conversation->get_result();

if ($result_conversation->num_rows > 0) {
    $conversation = $result_conversation->fetch_assoc();
} else {
    die('Conversation not found.');
}

$sql_texts = "SELECT * FROM conversation_texts WHERE conversation_id = ?";
$stmt_texts = $db->prepare($sql_texts);
$stmt_texts->bind_param('i', $id);
$stmt_texts->execute();
$result_texts = $stmt_texts->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($conversation['title']); ?></title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="../public/css/lessons.css">
    <link rel="stylesheet" href="../public/css/navbar.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Reuse style from Practice Test for consistency */
        .conversation-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .conversation-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
        }

        .conversation-text p {
            margin-bottom: 1rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #2a5298;
        }

        .audio-container {
            margin-top: 2rem;
            text-align: center;
        }

        audio {
            width: 100%;
            max-width: 600px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <?php include '../view/page/navbar.php'; ?>
    <a href="../index.php" class="back-button">
    <button class="back-button-style">Quay lại Trang Chủ</button>
</a>
    <div class="container main-content">
        <div class="conversation-header">
            <h1><?php echo htmlspecialchars($conversation['title']); ?></h1>
            <p><?php echo htmlspecialchars($conversation['description']); ?></p>
        </div>

        <div class="conversation-container">
            <h2>Conversation</h2>
            <div class="conversation-text">
                <?php
                while ($text = $result_texts->fetch_assoc()) {
                    echo '<p>' . nl2br(htmlspecialchars($text['text'])) . '</p>';
                }
                ?>
            </div>

            <div class="audio-container">
                <h2>Listen to the Conversation</h2>
                <audio controls>
                    <source src="../<?php echo htmlspecialchars($conversation['audio_file']); ?>" type="audio/mp3">
                    Your browser does not support the audio element.
                </audio>
            </div>
        </div>
    </div>
</body>
</html>
