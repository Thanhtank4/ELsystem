<?php
// controller/UserController.php
include '../model/User_model.php';

class UserController {
    private $userModel;

    public function __construct($conn) {
        $this->userModel = new UserModel($conn);
    }

    public function checkUserRole() {
        if(!isset($_SESSION['username'])) {
            return ['isLoggedIn' => false];
        }

        $username = $_SESSION['username'];
        $isAdmin = $this->userModel->isAdmin($username);
        $userInfo = $this->userModel->getUserInfo($username);

        return [
            'isLoggedIn' => true,
            'username' => $username,
            'isAdmin' => $isAdmin,
            'userInfo' => $userInfo
        ];
    }

    public function requireAdmin() {
        if(!isset($_SESSION['username']) || !$this->userModel->isAdmin($_SESSION['username'])) {
            header('Location: ../view/login.php');
            exit();
        }
    }

    public function requireLogin() {
        if(!isset($_SESSION['username'])) {
            header('Location: login.php');
            exit();
        }
    }
}
?>