<?php
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'thanhtan') {
    header("Location: ../../index.php");
    exit();
}

include '../../model/db.php';
include '../../model/Course.php';

if (isset($_POST['add_course'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $duration = $_POST['duration'];

    // Xử lý upload hình ảnh
    $image = '';
    if (isset($_FILES['course_image']) && $_FILES['course_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['course_image']['name'];
        $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed)) {
            $new_filename = uniqid() . '.' . $file_ext;

            // Sử dụng đường dẫn tuyệt đối đến thư mục public/img
            $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/ELsystem/public/img/';

            // Tạo thư mục nếu chưa tồn tại
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $upload_path = $upload_dir . $new_filename;

            if (move_uploaded_file($_FILES['course_image']['tmp_name'], $upload_path)) {
                $image = $new_filename;
            } else {
                $_SESSION['error'] = "Không thể upload file. Lỗi: " . error_get_last()['message'];
            }
        } else {
            $_SESSION['error'] = "Chỉ cho phép file ảnh có định dạng: " . implode(', ', $allowed);
        }
    }

    // Gọi hàm addCourse để thêm khóa học mới vào database
    if (Course::addCourse($name, $price, $description, $duration, $image)) {
        $_SESSION['success'] = 'Thêm khóa học thành công!';
    } else {
        $_SESSION['error'] = 'Có lỗi xảy ra khi thêm khóa học!';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Khóa Học Mới</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/home_product.css">
    <style>
        /* Các style hiện tại của bạn */
    </style>
</head>

<body>
    <!-- Sidebar -->
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

    <!-- Header -->
    <div class="header">
        <h2>Thêm Khóa Học Mới</h2>
        <div class="user-info">
            <span>Xin chào, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <?php if (isset($_SESSION['error'])): ?>
            <div style="color: red; margin-bottom: 15px;">
                <?php
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <form action="add_course.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Tên khóa học:</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="price">Giá khóa học:</label>
                    <input type="text" id="price" name="price" required>
                </div>

                <div class="form-group">
                    <label for="description">Mô tả khóa học:</label>
                    <textarea id="description" name="description" required></textarea>
                </div>

                <div class="form-group">
                    <label for="duration">Thời gian khóa học (tháng):</label>
                    <input type="number" id="duration" name="duration" required>
                </div>

                <div class="form-group">
                    <label for="course_image">Hình ảnh khóa học:</label>
                    <input type="file" id="course_image" name="course_image" accept="image/*">
                </div>

                <div class="form-actions">
                    <button type="submit" name="add_course" class="submit-btn">
                        <i class="fas fa-plus"></i> Thêm khóa học
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>