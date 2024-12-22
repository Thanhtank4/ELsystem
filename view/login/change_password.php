<?php
session_start();
require_once '../../model/db.php';

$error = '';  

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit();
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Kiểm tra mật khẩu hiện tại
    $username = $_SESSION['username'];
    $stmt = $db->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if (password_verify($currentPassword, $user['password'])) {
        if ($newPassword === $confirmPassword) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            // Cập nhật mật khẩu mới trong cơ sở dữ liệu
            $updateStmt = $db->prepare("UPDATE users SET password = ? WHERE username = ?");
            $updateStmt->bind_param("ss", $hashedPassword, $username);
            
            if ($updateStmt->execute()) {
                $message = "Password changed successfully!";
            } else {
                $error = "An error occurred while updating the password. Please try again.";
            }
            
            $updateStmt->close();
        } else {
            $error = "New password and confirmation password do not match.";
        }
    } else {
        $error = "Current password is incorrect.";
    }
    
    $stmt->close();
}

$db->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Hi English</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../public/css/login.css">
</head>
<body>
    <video class="video-background" autoplay muted loop>
        <source src="../../public/img/login.mp4" type="video/mp4">
        Trình duyệt của bạn không hỗ trợ video.
    </video>

    <div class="login-container">
        <a href="../../index.php" class="back-button">
            <i class="fas fa-arrow-left"></i>
        </a>

        <h2>Đổi mật khẩu</h2>
        
        <?php if (!empty($message)): ?>
            <p style="color: green;"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        
        <form method="post" action="">
            <div class="form-group">
                <label for="current_password">Mật khẩu hiện tại</label>
                <input type="password" id="current_password" name="current_password" placeholder="Nhập mật khẩu hiện tại" required>
                <i class="fas fa-lock"></i>
            </div>
            
            <div class="form-group">
                <label for="new_password">Mật khẩu mới</label>
                <input type="password" id="new_password" name="new_password" placeholder="Nhập mật khẩu mới" required>
                <i class="fas fa-lock"></i>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Xác nhận mật khẩu mới</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu mới" required>
                <i class="fas fa-lock"></i>
            </div>
            
            <button type="submit" class="btn-login">
                Đổi mật khẩu
            </button>
        </form>
        
        <div class="register-link">
            <a href="../login.php">Quay lại đăng nhập</a>
        </div>
    </div>
</body>
</html>
