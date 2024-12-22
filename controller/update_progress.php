<?php
require_once '../model/db.php';
require_once '../controller/VocabularyController.php';

$data = json_decode(file_get_contents('php://input'), true);
$controller = new VocabularyController($pdo);

if ($data && isset($data['vocabulary_id']) && isset($data['status'])) {
    // Giả sử user_id lấy từ session
    $user_id = $_SESSION['user_id'] ?? 0;
    $controller->processLearning($user_id, $data['vocabulary_id'], $data['status']);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
