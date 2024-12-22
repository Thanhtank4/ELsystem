<?php
session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'thanhtan') {
    header("Location: ../../index.php");
    exit();
}

include '../../model/db.php';
include '../../model/Course.php';  // Bao gồm model Course

// Lấy tất cả khóa học từ cơ sở dữ liệu
$courses = Course::getAllCourses();  // Lấy danh sách tất cả khóa học
$products_count = count($courses);   // Đếm số lượng khóa học
?>


<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/home_product.css">
</head>

<body>
    <!-- Giữ nguyên sidebar từ file trước -->
    <div class="sidebar">
        <ul class="sidebar-menu">
            <li>
                <a href="home_product.php" class="active">
                    <i class="fas fa-home"></i>
                    <span>Trang chủ</span>
                </a>
            </li>

            <!-- Thêm mục quản lý tài khoản thành viên -->
            <li>
                <a href="manage_members.php">
                    <i class="fas fa-users"></i>
                    <span>Quản lý thành viên</span>
                </a>
            </li>
            <!-- Thêm mục quản lý khóa học -->
            <li>
                <a href="manage_courses.php">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Quản lý khóa học</span>
                </a>
            </li>
            <li>
            <a href="../../index.php">
                <i class="fas fa-arrow-left"></i>
                <span>Quay lại Website</span>
            </a>
        </li>
            <li>
                <a href="../login/logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Đăng xuất</span>
                </a>
            </li>
        </ul>
    </div>


    <div class="header">
        <h2>Bảng điều khiển</h2>
        <div class="user-info">
            <span>Xin chào, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        </div>
    </div>

    <div class="main-content">
        <div class="welcome-banner">
            <h1>Chào mừng trở lại, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            <p>Đây là tổng quan về cửa hàng của bạn</p>
        </div>

        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon" style="background: #E8F5E9; color: #2E7D32;">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $products_count; ?></h3>
                    <p>Tổng số sản phẩm</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #E3F2FD; color: #1565C0;">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stat-info">
                    <h3></h3>
                    <p>Danh mục</p>
                </div>
            </div>
        </div>

        <div class="recent-products">
            <h2>Quản lí khoá học</h2>
            <ul class="product-list">
                <?php foreach ($courses as $course): ?>
                    <li class="product-item">
                        <div>
                            <h3><?php echo htmlspecialchars($course['name']); ?></h3>
                            <p>Giá: <?php echo htmlspecialchars($course['price']); ?></p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        </ul>
    </div>
    </div>
</body>

</html>