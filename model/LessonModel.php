<?php
class LessonModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getLessonById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM lessons WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getQuestionsByLessonId($lesson_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM grammar_questions WHERE lesson_id = ? ORDER BY question_order");
        $stmt->execute([$lesson_id]);
        return $stmt->fetchAll();
    }

    public function saveUserAnswer($user_id, $question_id, $answer, $is_correct) {
        $stmt = $this->pdo->prepare("INSERT INTO user_answers (user_id, question_id, answer, is_correct, answered_at) 
                                    VALUES (?, ?, ?, ?, NOW())");
        return $stmt->execute([$user_id, $question_id, $answer, $is_correct]);
    }

    public function updateLessonProgress($user_id, $lesson_id, $score) {
        $stmt = $this->pdo->prepare("INSERT INTO lesson_progress 
                                    (user_id, lesson_id, score, completed_at) 
                                    VALUES (?, ?, ?, NOW())
                                    ON DUPLICATE KEY UPDATE 
                                    score = ?, completed_at = NOW()");
        return $stmt->execute([$user_id, $lesson_id, $score, $score]);
    }

    public function getQuestionById($question_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM grammar_questions WHERE id = ?");
        $stmt->execute([$question_id]);
        return $stmt->fetch();
    }

}
