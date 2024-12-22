<?php
session_start();
require_once '../model/db.php';
require_once '../controller/AuthController.php';

$error = '';  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $authController = new AuthController($conn);
    $result = $authController->processLogin($_POST['username'], $_POST['password']);
    
    if ($result['success']) {
        header('Location: ../index.php');
        exit();
    } else {
        $error = $result['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Hi English</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../public/css/login.css">
</head>
<body>
    <video class="video-background" autoplay muted loop>
        <source src="../public/img/login.mp4" type="video/mp4">
        Trình duyệt của bạn không hỗ trợ video.
    </video>

    <div class="login-container">
        <a href="../index.php" class="back-button">
            <i class="fas fa-arrow-left"></i>
        </a>

        <h2>Đăng nhập</h2>
        <?php if ($error): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Tên đăng nhập</label>
                <input type="text" id="username" name="username" placeholder="Nhập tên đăng nhập của bạn" required>
                <i class="fas fa-user"></i>
            </div>
            
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" placeholder="Nhập mật khẩu của bạn" required>
                <i class="fas fa-lock"></i>
            </div>
            
            <button type="submit" class="btn-login">
                Đăng nhập
            </button>
        </form>
        
        <div class="register-link">
            Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a>
        </div>
    </div>
</body>
</html>