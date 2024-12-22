<?php
session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'thanhtan') {
    header("Location: ../../index.php");
    exit();
}

include '../../model/db.php';
include '../../model/Course.php';  // Bao gồm model Course

// Xử lý thêm, chỉnh sửa và xóa khóa học
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['edit_course'])) {
        $id = $_POST['id'];
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

        if (Course::updateCourse($id, $name, $price, $description, $duration, $image)) {
            $_SESSION['success'] = 'Chỉnh sửa khóa học thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật khóa học!';
        }
    }
}

// Xử lý xóa khóa học
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];
    Course::deleteCourse($course_id);
    $_SESSION['success'] = 'Xóa khóa học thành công!';
}

// Lấy tất cả khóa học từ cơ sở dữ liệu
$courses = Course::getAllCourses();
$products_count = count($courses);  // Lấy danh sách tất cả khóa học
?>

<!DOCTYPE html>
<html lang="vi">
<style>
    .product-item {
        display: flex;
        align-items: start;
        gap: 20px;
        padding: 15px;
    }

    .product-image {
        width: 200px;
        height: 150px;
        overflow: hidden;
        border-radius: 8px;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/home_product.css">
    <link rel="stylesheet" href="../../public/css/manage_course.css">

</head>
<style>

</style>

<body>
    <?php if (isset($_SESSION['error'])): ?>
        <div style="color: red;"><?php echo $_SESSION['error'];
                                    unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div style="color: green;"><?php echo $_SESSION['success'];
                                    unset($_SESSION['success']); ?></div>
    <?php endif; ?>
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
                <a href="add_course.php">
                    <i class="fas fa-plus-circle"></i>
                    <span>Thêm khóa học</span>
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
        <h2>Quản lí khoá học</h2>
        <div class="user-info">
            <span>Xin chào, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <h1>Chào mừng trở lại, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            <p>Đây là quản lí khoá học</p>
        </div>

        <!-- Statistics -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon" style="background: #E8F5E9; color: #2E7D32;">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $products_count; ?></h3>
                    <p>Tổng số khóa học</p>
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
        <!-- Course Management -->
        <div class="recent-products">
            <h2>Danh sách khóa học</h2>
            <ul class="product-list">
                <?php foreach ($courses as $course): ?>
                    <li class="product-item">
                        <div>
                            <h3><?php echo htmlspecialchars($course['name']); ?></h3>
                            <p>Giá: <?php echo htmlspecialchars($course['price']); ?></p>
                            <p>Mô tả: <?php echo htmlspecialchars($course['description']); ?></p>
                            <p>Thời gian: <?php echo htmlspecialchars($course['duration']); ?></p>
                        </div>
                        <div class="product-actions">
                            <button type="button" class="edit-btn" onclick="openEditModal(<?php echo $course['id']; ?>, '<?php echo addslashes(htmlspecialchars($course['name'])); ?>', '<?php echo addslashes(htmlspecialchars($course['price'])); ?>', '<?php echo addslashes(htmlspecialchars($course['description'])); ?>', '<?php echo addslashes(htmlspecialchars($course['duration'])); ?>', '<?php echo addslashes(htmlspecialchars($course['image'])); ?>')">Edit</button>

                            <a href="manage_courses.php?action=delete&course_id=<?php echo $course['id']; ?>"
                                onclick="return confirm('Are you sure you want to delete?');">Delete</a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <!-- Modal Edit Form -->
    <!-- Trong phần Modal Edit Form, thêm trường input file -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Chỉnh sửa khóa học</h2>
            <form id="editForm" action="manage_courses.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="edit_id">

                <div class="form-group">
                    <label for="edit_name">Tên khóa học:</label>
                    <input type="text" id="edit_name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="edit_price">Giá:</label>
                    <input type="text" id="edit_price" name="price" required>
                </div>

                <div class="form-group">
                    <label for="edit_description">Mô tả:</label>
                    <textarea id="edit_description" name="description" required></textarea>
                </div>

                <div class="form-group">
                    <label for="edit_duration">Thời gian:</label>
                    <input type="text" id="edit_duration" name="duration" required>
                </div>

                <!-- Thêm trường upload hình ảnh -->
                <div class="form-group">
                    <label for="course_image">Hình ảnh khóa học:</label>
                    <input type="file" id="course_image" name="course_image" accept="image/*">
                    <div id="current_image"></div>
                </div>

                <div class="form-actions">
                    <button type="submit" name="edit_course" class="save-btn">Lưu thay đổi</button>
                    <button type="button" class="cancel-btn" onclick="closeModal()">Hủy</button>
                </div>
            </form>
        </div>
    </div>
</body>
<script>
    const modal = document.getElementById("editModal");
    const closeBtn = document.getElementsByClassName("close")[0];

    function openEditModal(id, name, price, description, duration, image = '') {
        console.log("id: ", id); // Kiểm tra giá trị id
        console.log("name: ", name); // Kiểm tra giá trị name
        console.log("price: ", price); // Kiểm tra giá trị price
        console.log("description: ", description); // Kiểm tra giá trị description
        console.log("duration: ", duration); // Kiểm tra giá trị duration
        // Cập nhật các trường input của form
        document.getElementById("edit_id").value = id;
        document.getElementById("edit_name").value = name;
        document.getElementById("edit_price").value = price;
        document.getElementById("edit_description").value = description;
        document.getElementById("edit_duration").value = duration;
        const currentImageDiv = document.getElementById("current_image");
        if (image) {
            currentImageDiv.innerHTML = `<img src="../../public/img/${image}" 
            alt="Current image" style="max-width: 200px; margin-top: 10px;">`;
        } else {
            currentImageDiv.innerHTML = 'No image available';
        }

        // Mở modal
        modal.style.display = "block";
    }

    // Hàm đóng modal
    function closeModal() {
        modal.style.display = "none";
    }

    // Đóng modal khi click vào nút close
    closeBtn.onclick = closeModal;

    // Đóng modal khi click bên ngoài modal
    window.onclick = function(event) {
        if (event.target == modal) {
            closeModal();
        }
    }
</script>

</html>