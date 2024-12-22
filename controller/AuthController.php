<?php
// controller/AuthController.php
require_once '../model/User_model.php';

class AuthController {
    private $userModel;

    public function __construct($conn) {
        $this->userModel = new UserModel($conn);
    }

    public function processLogin($username, $password) {
        // Validate input
        if(empty($username) || empty($password)) {
            return [
                'success' => false,
                'message' => 'Vui lòng nhập đầy đủ thông tin'
            ];
        }

        // Attempt login
        $result = $this->userModel->login($username, $password);
        
        if($result['success']) {
            // Set session
            $_SESSION['username'] = $result['user']['username'];
            $_SESSION['user_id'] = $result['user']['id'];
            $_SESSION['role'] = $result['user']['role'];
        }

        return $result;
    }

    public function processRegister($username, $email, $password, $confirmPassword) {
        // Validate input
        $errors = $this->validateRegistrationInput($username, $email, $password, $confirmPassword);
        if(!empty($errors)) {
            return [
                'success' => false,
                'message' => $errors[0] // Return first error
            ];
        }

        // Process registration
        return $this->userModel->register($username, $email, $password);
    }

    private function validateRegistrationInput($username, $email, $password, $confirmPassword) {
        $errors = [];

        if(empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
            $errors[] = 'Vui lòng điền đầy đủ thông tin';
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email không hợp lệ';
        }

        if(strlen($username) < 3 || strlen($username) > 20) {
            $errors[] = 'Tên đăng nhập phải từ 3-20 ký tự';
        }

        if(strlen($password) < 6) {
            $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
        }

        if($password !== $confirmPassword) {
            $errors[] = 'Mật khẩu xác nhận không khớp';
        }

        if(!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $errors[] = 'Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới';
        }

        return $errors;
    }

    public function logout() {
        session_destroy();
        header('Location: ../view/login.php');
        exit();
    }
}
?>