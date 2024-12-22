<?php
session_start();
include '../../model/db.php'; // Bao gồm kết nối cơ sở dữ liệu

// Kiểm tra người dùng đã đăng nhập chưa
if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit();
}

$username = $_SESSION['username'];

// Lấy thông tin người dùng từ cơ sở dữ liệu
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];
    $current_password = $_POST['current_password'] ?? null;
    $new_password = $_POST['new_password'] ?? null;
    $confirm_password = $_POST['confirm_password'] ?? null;

    // Cập nhật tên và email (không cần kiểm tra mật khẩu)
    if (!empty($new_username) && !empty($new_email)) {
        $update_sql = "UPDATE users SET username = ?, email = ? WHERE username = ?";
        $update_stmt = $db->prepare($update_sql);
        $update_stmt->bind_param("sss", $new_username, $new_email, $username);

        if ($update_stmt->execute()) {
            $success = "Thông tin đã được cập nhật!";
            $_SESSION['username'] = $new_username; // Cập nhật session nếu tên thay đổi
            $username = $new_username; // Cập nhật lại biến tên
        } else {
            $error = "Đã xảy ra lỗi khi cập nhật thông tin.";
        }
    }

    // Nếu người dùng muốn thay đổi mật khẩu
    if (!empty($new_password)) {
        if (!password_verify($current_password, $user['password'])) {
            $error = "Mật khẩu hiện tại không đúng.";
        } elseif ($new_password !== $confirm_password) {
            $error = "Mật khẩu mới và xác nhận mật khẩu không khớp.";
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_password_sql = "UPDATE users SET password = ? WHERE username = ?";
            $update_password_stmt = $db->prepare($update_password_sql);
            $update_password_stmt->bind_param("ss", $hashed_password, $username);

            if ($update_password_stmt->execute()) {
                $success = "Mật khẩu đã được cập nhật!";
            } else {
                $error = "Đã xảy ra lỗi khi cập nhật mật khẩu.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hi English - App học tiếng Anh miễn phí</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/index.css">
    <style>
        /* Thêm style cho phần cập nhật hồ sơ */
.form-container {
    max-width: 800px;
    margin: 50px auto;
    padding: 30px;
    background-color: #fff;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    border-radius: 12px;
}

h2 {
    font-size: 28px;
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

label {
    font-size: 16px;
    color: #555;
    margin-bottom: 8px;
    display: block;
}

input[type="text"],
input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.3s ease;
}

input[type="text"]:focus,
input[type="email"]:focus,
input[type="password"]:focus {
    border-color: #007bff;
    outline: none;
}

.password-section {
    margin-top: 20px;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    border: 1px solid #ddd;
}

.password-section label {
    margin-bottom: 12px;
}

.password-section input {
    margin-bottom: 10px;
}

button[type="submit"] {
    background-color: #007bff;
    color: white;
    padding: 12px 25px;
    font-size: 16px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease;
    width: 100%;
}

button[type="submit"]:hover {
    background-color: #0056b3;
    transform: translateY(-3px);
}

a.btn-back {
    display: inline-block;
    background-color: #ccc;
    color: #333;
    padding: 10px 20px;
    font-size: 16px;
    text-decoration: none;
    border-radius: 8px;
    margin-top: 20px;
    width: 100%;
    text-align: center;
}

a.btn-back:hover {
    background-color: #bbb;
}

.error {
    color: red;
    font-size: 16px;
    text-align: center;
    margin-bottom: 20px;
}

.success {
    color: green;
    font-size: 16px;
    text-align: center;
    margin-bottom: 20px;
}

/* Đảm bảo responsive cho các màn hình nhỏ */
@media (max-width: 768px) {
    .form-container {
        padding: 20px;
        margin: 20px;
    }

    h2 {
        font-size: 24px;
    }

    label {
        font-size: 14px;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
        font-size: 14px;
        padding: 8px;
    }

    button[type="submit"] {
        padding: 10px 20px;
        font-size: 14px;
    }

    a.btn-back {
        padding: 8px 16px;
        font-size: 14px;
    }
}

    </style>
</head>

<body>
    <!-- Banner -->
    <nav class="navbar">
        <section class="banner">
            <img src="/public/img/index.jpg" alt="Banner" class="banner-image">
        </section>
        <div class="container-logo">
            <div class="logo">
                <a href="/index.php">English Learning</a>
            </div>
            <div class="login-success">
                <?php if (isset($_SESSION['username'])): ?>
                    <div class="user-menu">
                        <div class="welcome-message">
                            <p>Chào mừng, <?php echo htmlspecialchars($_SESSION['username']); ?> <i class="fas fa-chevron-down"></i></p>
                        </div>
                        <div class="user-menu-content">
                            <?php if ($_SESSION['username'] == 'thanhtan'): ?>
                                <!-- Thêm quyền quản trị viên cho Thanh Tân -->
                            <?php endif; ?>
                            <a href="/view/login/profile.php"><i class="fas fa-user"></i> Hồ sơ</a>
                            <a href="/view/login/progress.php"><i class="fas fa-chart-line"></i> Tiến độ học tập</a>
                            <a href="/view/admin/admin.php"><i class="fas fa-tachometer-alt"></i> Bảng điều khiển quản trị</a>
                            <a href="/view/login/change_password.php"><i class="fas fa-key"></i> Đổi mật khẩu</a>
                            <a href="/view/login/logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="nav-buttons">
                        <a href="./view/login.php" class="btn"><i class="fas fa-sign-in-alt"></i> Đăng nhập</a>
                        <a href="./view/register.php" class="btn"><i class="fas fa-user-plus"></i> Đăng ký</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="form-container">
        <h2>Cập nhật hồ sơ</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="username">Tên người dùng:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <div class="password-section">
                <label for="current_password">Mật khẩu hiện tại:</label>
                <input type="password" id="current_password" name="current_password" placeholder="Chỉ nhập khi muốn đổi mật khẩu">

                <label for="new_password">Mật khẩu mới:</label>
                <input type="password" id="new_password" name="new_password" placeholder="Chỉ nhập khi muốn đổi mật khẩu">

                <label for="confirm_password">Xác nhận mật khẩu mới:</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Chỉ nhập khi muốn đổi mật khẩu">
            </div>

            <button type="submit">Lưu thay đổi</button>
            <a href="/view/login/profile.php" class="btn-back">Quay lại</a>
        </form>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Về chúng tôi</h3>
                <p>Hi English là nền tảng học tiếng Anh trực tuyến miễn phí, giúp người học cải thiện kỹ năng ngôn ngữ một cách hiệu quả.</p>
            </div>
            <div class="footer-section">
                <h3>Liên kết nhanh</h3>
                <ul>
                    <li><a href="#">Trang chủ</a></li>
                    <li><a href="#">Khóa học</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Liên hệ</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Kết nối với chúng tôi</h3>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; 2024 Hi English. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>
