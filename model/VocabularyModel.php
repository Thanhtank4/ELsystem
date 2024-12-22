<?php
class VocabularyModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Lấy danh sách từ vựng theo lesson_id và kèm theo ví dụ
    public function getVocabularyByLesson($lesson_id) {
        $sql = "SELECT * FROM vocabulary WHERE lesson_id = :lesson_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':lesson_id', $lesson_id, PDO::PARAM_INT);
        $stmt->execute();
        $vocabularyList = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Lấy ví dụ cho từng từ vựng
        foreach ($vocabularyList as &$word) {
            $sql = "SELECT * FROM example_sentences WHERE vocabulary_id = :vocabulary_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':vocabulary_id', $word['id'], PDO::PARAM_INT);
            $stmt->execute();
            $word['examples'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $vocabularyList;
    }

    // Cập nhật tiến độ học từ vựng
    public function updateProgress($user_id, $vocabulary_id, $status) {
        $sql = "INSERT INTO vocabulary_progress (user_id, vocabulary_id, status) 
                VALUES (:user_id, :vocabulary_id, :status) 
                ON DUPLICATE KEY UPDATE status = :status";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':vocabulary_id', $vocabulary_id, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        return $stmt->execute();
    }
}
?>
