<?php session_start();
include './model/db.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hi English - App học tiếng Anh miễn phí</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="./public/css/style.css">
    <link rel="stylesheet" href="./public/css/index.css">
</head>
<style>
    .premium-banner {
        background: linear-gradient(135deg, #2a2a72 0%, #009ffd 74%);
        padding: 1.5rem 0;
        margin: 2rem 0;
    }

    .premium-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: white;
    }

    .premium-info h2 {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .premium-info p {
        font-size: 1rem;
        opacity: 0.9;
    }

    .premium-btn {
        background-color: #ffd700;
        color: #2a2a72;
        padding: 0.8rem 1.5rem;
        border-radius: 25px;
        text-decoration: none;
        font-weight: bold;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: transform 0.3s ease;
    }

    .premium-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .fa-crown {
        color: #ffd700;
        margin-right: 0.5rem;
    }
</style>

<body>
    <!-- Banner -->
    <nav class="navbar">
        <section class="banner">
            <img src="./public/img/index.jpg" alt="Banner" class="banner-image">
        </section>
        <div class="container-logo">
            <div class="logo">
                <a href="index.php">English Learning</a>
            </div>
            <div class="login-success">
                <?php if (isset($_SESSION['username'])): ?>
                    <div class="user-menu">
                        <div class="welcome-message">
                            <p>Chào mừng, <?php echo htmlspecialchars($_SESSION['username']); ?> <i class="fas fa-chevron-down"></i></p>
                        </div>
                        <div class="user-menu-content">
                            <?php if ($_SESSION['username'] == 'thanhtan'): ?>
                                <a href="./view/admin/home_product.php"><i class="fas fa-tachometer-alt"></i> Bảng điều khiển quản trị</a>
                            <?php endif; ?>
                            <a href="./view/login/profile.php"><i class="fas fa-user"></i> Hồ sơ</a>
                            <a href="./view/login/progress.php"><i class="fas fa-chart-line"></i> Tiến độ học tập</a>
                            <a href="./view/login/change_password.php"><i class="fas fa-key"></i> Đổi mật khẩu</a>
                            <a href="./view/login/logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
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
    <!-- Section Navigation Menu -->
    <div class="nav-menu">
        <div class="container">
            <button class="nav-btn active" data-section="grammar">
                <i class="fas fa-book"></i> GRAMMAR LESSONS
            </button>
            <button class="nav-btn" data-section="practice">
                <i class="fas fa-tasks"></i> PRACTICE TESTS
            </button>
            <button class="nav-btn" data-section="conversation">
                <i class="fas fa-comments"></i> CONVERSATIONS
            </button>
        </div>
    </div>
    <!-- Thêm phần Premium riêng biệt -->
    <div class="premium-banner">
        <div class="container">
            <div class="premium-content">
                <div class="premium-info">
                    <h2><i class="fas fa-crown"></i> PREMIUM COURSES</h2>
                    <p>Nâng cao kỹ năng với các khóa học chuyên sâu</p>
                </div>
                <a href="./view/shop.php" class="premium-btn">
                    <span>Khám phá ngay</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="container main-content">
        <section class="course-section" id="grammar-section">
            <div class="section-header">
                <h2><i class="fas fa-book"></i> GRAMMAR LESSONS</h2>
            </div>
            <div class="course-grid">
                <?php
                for ($i = 1; $i <= 6; $i++) {
                    $progress = isset($progressData[$i]) ? $progressData[$i] : 0;
                    echo "
    <div class='course-card'>
        <div class='card-header'>
            <h3>Level $i</h3>
            <div class='lesson-count'>15 lessons</div>
        </div>
        <div class='card-content'>
            <div class='progress-bar'>
                <div class='progress' style='width: {$progress}%'></div>
            </div>
            <p>{$progress}% Complete</p>
            <a href='./view/lessons.php?level=$i' class='start-btn'>
                <i class='fas fa-play'></i> Start Learning
            </a>
        </div>
    </div>";
                }
                ?>
            </div>
        </section>
        <section class="course-section hidden" id="practice-section">
            <div class="section-header">
                <h2><i class="fas fa-tasks"></i> PRACTICE TESTS</h2>
            </div>
            <div class="course-grid">
                <?php
                for ($i = 1; $i <= 6; $i++) {
                    echo "
                    <div class='course-card'>
                        <div class='card-header'>
                            <h3>Level $i</h3>
                            <div class='lesson-count'>50 tests</div>
                        </div>
                        <div class='card-content'>
                            <a href='./view/partice.php?level=$i' class='start-btn'>Start Test</a>
                        </div>
                    </div>";
                }
                ?>
            </div>
        </section>
        <!-- Conversation Section -->
        <section class="course-section hidden" id="conversation-section">
            <div class="section-header">
                <h2><i class="fas fa-comments"></i> CONVERSATIONS</h2>
            </div>
            <div class="course-grid">
                <?php
                // Lấy tất cả các cuộc hội thoại từ bảng `conver`
                $stmt = $pdo->prepare("SELECT * FROM conver WHERE type = 'conversation'");
                $stmt->execute();
                $conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($conversations)): // Kiểm tra nếu có dữ liệu
                    foreach ($conversations as $conversation): ?>
                        <div class="course-card">
                            <div class="card-header">
                                <h3><?php echo htmlspecialchars($conversation['title']); ?></h3>
                            </div>
                            <div class="card-content">
                                <p><?php echo htmlspecialchars($conversation['description']); ?></p>
                                <p>Duration: <?php echo htmlspecialchars($conversation['duration']); ?> minutes</p>
                                <p>Difficulty: <?php echo htmlspecialchars($conversation['difficulty']); ?></p>
                                <a href="./view/conver.php?title=<?php echo urlencode($conversation['title']); ?>" class="start-btn">
                                    <i class="fas fa-play"></i> Start Practice
                                </a>
                            </div>
                        </div>
                    <?php endforeach;
                else: // Trường hợp không có dữ liệu
                    ?>
                    <p>No conversations available at the moment. Please check back later.</p>
                <?php endif; ?>
            </div>
        </section>

    </div>
    <!-- Footer -->
    <?php include './view/page/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navButtons = document.querySelectorAll('.nav-btn');
            const sections = document.querySelectorAll('.course-section');

            const premiumSection = document.getElementById('premium-section');
            if (premiumSection) {
                premiumSection.classList.add('hidden');
            }

            navButtons.forEach(button => {
                button.addEventListener('click', function() {
                    navButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');

                    sections.forEach(section => section.classList.add('hidden'));

                    const sectionId = `${button.dataset.section}-section`;
                    const targetSection = document.getElementById(sectionId);
                    if (targetSection) {
                        targetSection.classList.remove('hidden');
                    }
                });
            });
        });
    </script>
</body>

</html>