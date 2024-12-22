<?php
session_start();
include '../../model/db.php';

if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit();
}

$username = $_SESSION['username'];
$stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ người dùng - Hi English</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../public/css/footer.css">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --background-color: #f3f4f6;
            --text-color: #1f2937;
            --card-background: #ffffff;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
        }

        .navbar {
            background: hotpink;
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-brand {
            color: white;
            font-size: 1.5rem;
            text-decoration: none;
            font-weight: bold;
        }

        .home-button {
            background: white;
            color: var(--primary-color);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .home-button:hover {
            background: var(--secondary-color);
            color: white;
            transform: translateY(-2px);
        }

        .profile-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 2rem;
            background: var(--card-background);
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .profile-container:hover {
            transform: translateY(-5px);
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 2.5rem;
            margin-bottom: 2.5rem;
            padding-bottom: 2rem;
            border-bottom: 2px solid var(--background-color);
        }

        .profile-avatar {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3.5rem;
            transition: transform 0.3s ease;
        }

        .profile-avatar:hover {
            transform: scale(1.05);
        }

        .profile-info {
            flex: 1;
        }

        .profile-info h1 {
            color: var(--text-color);
            margin-bottom: 1rem;
            font-size: 2.5rem;
        }

        .profile-info p {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0.5rem 0;
            color: #4b5563;
        }

        .edit-profile-btn {
            background: var(--primary-color);
            color: white;
            padding: 0.8rem 2rem;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            margin-top: 1rem;
        }

        .edit-profile-btn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin: 2.5rem 0;
        }

        .stat-card {
            background: var(--card-background);
            padding: 2rem;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .stat-card i {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .stat-card h3 {
            color: var(--text-color);
            margin: 1rem 0;
            font-size: 1.2rem;
        }

        .stat-card p {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        .achievements {
            margin-top: 3rem;
        }

        .achievements h2 {
            color: var(--text-color);
            margin-bottom: 2rem;
            font-size: 2rem;
        }

        .achievement-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .achievement {
            background: var(--card-background);
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .achievement:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .achievement i {
            font-size: 2.5rem;
            color: #fbbf24;
            margin-bottom: 1rem;
        }

        .achievement h3 {
            color: var(--text-color);
            margin: 1rem 0;
            font-size: 1.2rem;
        }

        .achievement p {
            color: #4b5563;
        }

        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
            }

            .profile-avatar {
                margin: 0 auto;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .achievement-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-content">
            <a href="#" class="nav-brand">Hi English</a>
            <a href="../../index.php" class="home-button">
                <i class="fas fa-home"></i>
                Về trang chủ
            </a>
        </div>
    </nav>

    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="profile-info">
                <h1><?php echo htmlspecialchars($user['username']); ?></h1>
                <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><i class="fas fa-calendar"></i> Tham gia: <?php echo date('d/m/Y', strtotime($user['created_at'])); ?></p>
                <button class="edit-profile-btn">
                    <i class="fas fa-edit"></i>
                    Chỉnh sửa hồ sơ
                </button>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-book"></i>
                <h3>Bài học đã hoàn thành</h3>
                <p>45</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-tasks"></i>
                <h3>Bài kiểm tra đã làm</h3>
                <p>28</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-star"></i>
                <h3>Điểm trung bình</h3>
                <p>85%</p>
            </div>
        </div>

        <div class="achievements">
            <h2>Thành tích đạt được</h2>
            <div class="achievement-grid">
                <div class="achievement">
                    <i class="fas fa-trophy"></i>
                    <h3>Người học chăm chỉ</h3>
                    <p>Hoàn thành 30 bài học</p>
                </div>
                <div class="achievement">
                    <i class="fas fa-medal"></i>
                    <h3>Điểm số xuất sắc</h3>
                    <p>Đạt điểm tuyệt đối</p>
                </div>
                <div class="achievement">
                    <i class="fas fa-fire"></i>
                    <h3>Streak 7 ngày</h3>
                    <p>Học 7 ngày liên tiếp</p>
                </div>
            </div>
        </div>
    </div>

    <?php include '../page/footer.php'; ?>

    <script>
        // Add smooth scroll behavior
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>