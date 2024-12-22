<?php
require_once __DIR__ . '/../model/LessonModel.php'; 
require_once __DIR__ . '/../model/db.php';
include '../view/grammar_level.php';


class LessonController {
    public $model;
    public $user_id;

    public function __construct($pdo) {
        $this->model = new LessonModel($pdo);
        $this->user_id = $_SESSION['user_id'] ?? null;
    }
    public function getModel() {
        return $this->model;
    }

    public function getLesson($lesson_id) {
        return $this->model->getLessonById($lesson_id);
    }

    public function getQuestions($lesson_id) {
        return $this->model->getQuestionsByLessonId($lesson_id);
    }

    public function processAnswer($question_id, $user_answer) {
        $question = $this->getQuestionById($question_id);  // Gọi phương thức mới
        $is_correct = $this->checkAnswer($question, $user_answer);
        
        if ($this->user_id) {
            $this->model->saveUserAnswer($this->user_id, $question_id, $user_answer, $is_correct);
        }
        
        return [
            'is_correct' => $is_correct,
            'correct_answer' => $question['correct_answer'],
            'explanation' => $question['explanation']
        ];
    }

    // Thêm phương thức getQuestionById trong LessonController
    public function getQuestionById($question_id) {
        return $this->model->getQuestionById($question_id);  // Gọi phương thức từ LessonModel
    }

    private function checkAnswer($question, $user_answer) {
        return strtolower(trim($user_answer)) === strtolower(trim($question['correct_answer']));
    }

    public function calculateScore($lesson_id, $answers) {
        $questions = $this->getQuestions($lesson_id);
        $correct_count = 0;
        
        foreach ($answers as $q_id => $answer) {
            foreach ($questions as $question) {
                if ($question['id'] == $q_id && $this->checkAnswer($question, $answer)) {
                    $correct_count++;
                }
            }
        }
        
        return ($correct_count / count($questions)) * 100;
    }
}
