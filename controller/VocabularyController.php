<?php
class VocabularyController {
    private $model;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->model = new VocabularyModel($pdo);
    }

    // Lấy danh sách từ vựng theo lesson_id và hiển thị câu ví dụ
    public function showVocabularyLesson($lesson_id) {
        $vocabularyList = $this->model->getVocabularyByLesson($lesson_id);
        return $vocabularyList;
    }

    // Cập nhật tiến độ học từ vựng
    public function processLearning($user_id, $vocabulary_id, $status) {
        return $this->model->updateProgress($user_id, $vocabulary_id, $status);
    }
    
}
