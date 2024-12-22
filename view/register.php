<?php
session_start();
require_once '../model/db.php';
require_once '../controller/AuthController.php';

$error = '';  // Chỉ khai báo biến lỗi

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $authController = new AuthController($conn);
    $result = $authController->processRegister(
        $_POST['username'],
        $_POST['email'],
        $_POST['password'],
        $_POST['confirm_password']
    );
    
    if (!$result['success']) {
        $error = $result['message'];
    } else {
        header("Location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Hi English</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../public/css/login.css">

</head>
<body>
    <video class="video-background" autoplay muted loop playsinline>
        <source src="../public/img/login.mp4" type="video/mp4">
        Trình duyệt của bạn không hỗ trợ video.
    </video>
    <div class="login-container">
        <a href="../index.php" class="back-button">
            <i class="fas fa-arrow-left"></i>
        </a>

    <div class="register-container">
        <h2>Đăng ký</h2>
        <?php if ($error): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Tên đăng nhập</label>
                <input type="text" id="username" name="username" placeholder="Nhập tên đăng nhập" required>
                <i class="fas fa-user"></i>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Nhập email" required>
                <i class="fas fa-envelope"></i>
            </div>
            
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
                <i class="fas fa-lock"></i>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Xác nhận mật khẩu</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu" required>
                <i class="fas fa-lock"></i>
            </div>
            
            <button type="submit" class="btn-login">
                Đăng ký
                <i class="fas fa-user-plus"></i>
            </button>
        </form>

        <div class="register-link">
            Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a>
        </div>
    </div>
</body>
</html>
