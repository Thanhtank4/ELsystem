<?php
session_start();
include '../model/db.php';
include '../model/Course.php';  // Bao gồm model khóa học
include '../model/Cart.php';    // Bao gồm model giỏ hàng

// Lấy danh sách khóa học từ database
$courses = Course::getAllCourses();

if (isset($_GET['action'])) {
    $courseId = $_GET['id'];

    if ($_GET['action'] == 'add') {
        Cart::addItem($courseId);
    } elseif ($_GET['action'] == 'remove') {
        Cart::removeItem($courseId);
    }

    header('Location: shop.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hi English - Shop Khóa Học</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="../public/css/index.css">
    <link rel="stylesheet" href="../public/css/shop.css">
</head>
<style>
   /* Container cho danh sách khóa học */
.course-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
    padding: 20px 0;
}

/* Style cho từng card khóa học */
.course-item {
    display: flex;
    flex-direction: column;
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 15px;
    transition: transform 0.2s ease;
    height: 100%;
}

.course-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

/* Style cho hình ảnh khóa học */
.course-image {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 6px;
    margin-bottom: 12px;
}

/* Style cho tiêu đề khóa học */
.course-item h3 {
    font-size: 1.2rem;
    margin: 0 0 10px 0;
    color: #333;
}

/* Style cho mô tả khóa học */
.course-description {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 10px;
    flex-grow: 1;
    line-height: 1.4;
}

/* Style cho thời gian và giá */
.course-duration {
    font-size: 0.9rem;
    color: #555;
    margin-bottom: 8px;
}

.price {
    font-size: 1.1rem;
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 12px;
}

/* Style cho nút thêm vào giỏ hàng */
.btn-add-to-cart {
    background: #3498db;
    color: white;
    padding: 8px 15px;
    border-radius: 4px;
    text-decoration: none;
    text-align: center;
    transition: background 0.2s ease;
}

.btn-add-to-cart:hover {
    background: #2980b9;
}

.btn-add-to-cart i {
    margin-right: 5px;
}
</style>
<body>
    <!-- Banner -->
    <nav class="navbar">
        <section class="banner">
            <img src="../public/img/index.jpg" alt="Banner" class="banner-image">
        </section>
        <div class="container-logo">
            <div class="logo">
                <a href="../index.php">English Learning</a>
            </div>
            <div class="login-success">
                <?php if (isset($_SESSION['username'])): ?>
                    <div class="user-menu">
                        <div class="welcome-message">
                            <p>Chào mừng, <?php echo htmlspecialchars($_SESSION['username']); ?> <i class="fas fa-chevron-down"></i></p>
                        </div>
                        <div class="user-menu-content">
                            <?php if ($_SESSION['username'] == 'thanhtan'): ?>
                                <a href="../view/admin/home_product.php"><i class="fas fa-tachometer-alt"></i> Bảng điều khiển quản trị</a>
                            <?php endif; ?>
                            <a href="../view/login/profile.php"><i class="fas fa-user"></i> Hồ sơ</a>
                            <a href="../view/login/progress.php"><i class="fas fa-chart-line"></i> Tiến độ học tập</a>
                            <a href="../view/login/change_password.php"><i class="fas fa-key"></i> Đổi mật khẩu</a>
                            <a href="../view/login/logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="nav-buttons">
                        <a href="../view/login.php" class="btn"><i class="fas fa-sign-in-alt"></i> Đăng nhập</a>
                        <a href="../view/register.php" class="btn"><i class="fas fa-user-plus"></i> Đăng ký</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Nav Menu -->
    <div class="nav-menu">
        <div class="container">
            <a href="../index.php" class="nav-btn">
                <i class="fas fa-book"></i> GRAMMAR LESSONS
            </a>
            <a href="../index.php" class="nav-btn">
                <i class="fas fa-tasks"></i> PRACTICE TESTS
            </a>
            <a href="../index.php" class="nav-btn">
                <i class="fas fa-comments"></i> CONVERSATIONS
            </a>
        </div>
    </div>

    <!-- Premium Banner -->
    <div class="premium-header">
        <div class="container">
            <div class="premium-content">
                <div class="premium-title">
                    <i class="fas fa-crown"></i>
                    <h1>Premium Courses</h1>
                    <p>Nâng cao kỹ năng với các khóa học chuyên sâu</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content: Hiển thị danh sách khóa học -->
    <div class="container main-content">
    <h2>Premium Courses</h2>
    <div class="course-list">
        <?php if (!empty($courses)): ?>
            <?php foreach ($courses as $course): ?>
                <div class="course-item">
                    <?php if (!empty($course['image'])): ?>
                        <img src="/ELsystem/public/img/<?php echo htmlspecialchars($course['image']); ?>" 
                             alt="<?php echo htmlspecialchars($course['name']); ?>" 
                             class="course-image">
                    <?php else: ?>
                        <img src="/ELsystem/public/img/default-course.jpg" 
                             alt="Hình ảnh mặc định" 
                             class="course-image">
                    <?php endif; ?>
                    <h3><?php echo htmlspecialchars($course['name']); ?></h3>
                    <p class="course-description"><?php echo htmlspecialchars($course['description']); ?></p>
                    <div class="course-duration">Thời gian: <?php echo htmlspecialchars($course['duration']); ?> tháng</div>
                    <div class="price"><?php echo number_format($course['price']); ?> VNĐ</div>
                    <a href="shop.php?action=add&id=<?php echo $course['id']; ?>" class="btn-add-to-cart">
                        <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Không có khóa học nào để hiển thị.</p>
        <?php endif; ?>
    </div>
</div>

    <!-- Giỏ hàng -->
    <div class="cart-summary" id="cartSummary">
        <h3>
            <span>
                <i class="fas fa-shopping-cart"></i>
                Giỏ Hàng
            </span>
            <button class="cart-toggle" onclick="toggleCart()">
                <i class="fas fa-minus" id="cartIcon"></i>
            </button>
        </h3>
        <div id="cartContent">
            <?php
            $cartItems = Cart::getCartItems();
            $total = 0;
            if (empty($cartItems)): ?>
                <p>Giỏ hàng trống</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($cartItems as $item):
                        $total += $item['price'];
                    ?>
                        <li>
                            <?php echo htmlspecialchars($item['name']); ?> - <?php echo number_format($item['price']); ?> VNĐ
                            <a href="shop.php?action=remove&id=<?php echo $item['id']; ?>" class="btn-remove">
                                <i class="fas fa-trash"></i> Xóa
                            </a>
                        </li>
                    <?php endforeach; ?>

                </ul>
                <div class="cart-total">
                    Tổng: <?php echo number_format($total); ?> VNĐ
                </div>
                <a href="checkout.php" class="btn">Thanh toán</a>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function toggleCart() {
            const cart = document.getElementById('cartSummary');
            const content = document.getElementById('cartContent');
            const icon = document.getElementById('cartIcon');

            cart.classList.toggle('minimized');
            content.style.display = content.style.display === 'none' ? 'block' : 'none';
            icon.className = content.style.display === 'none' ? 'fas fa-plus' : 'fas fa-minus';
        }
    </script>

    <!-- Footer -->
    <?php include '../view/page/footer.php'; ?>
</body>

</html>