<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../../index.php");
    exit();
}

include '../../model/db.php';
include '../../model/User_model.php';

$userModel = new UserModel($conn);

// Xử lý các action CRUD
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'delete':
                if (isset($_POST['user_id'])) {
                    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
                    $stmt->bind_param("i", $_POST['user_id']);
                    $stmt->execute();
                }
                break;
            case 'update':
                if (isset($_POST['user_id'], $_POST['username'], $_POST['email'], $_POST['role'])) {
                    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
                    $stmt->bind_param("sssi", $_POST['username'], $_POST['email'], $_POST['role'], $_POST['user_id']);
                    $stmt->execute();
                }
                break;
            case 'add':
                if (isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['role'])) {
                    $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())");
                    $stmt->bind_param("ssss", $_POST['username'], $_POST['email'], $hashedPassword, $_POST['role']);
                    $stmt->execute();
                }
                break;
        }
    }
}

// Lấy danh sách thành viên
$sql = "SELECT id, username, email, role, created_at FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý thành viên</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/home_product.css">
    <style>
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .stats-container {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .stat-icon {
            padding: 15px;
            border-radius: 50%;
            font-size: 24px;
        }

        .member-list {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .modal-header {
            background: #f8f9fa;
        }

        .table-container {
            margin-top: 20px;
            overflow-x: auto;
        }

        .add-member-btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .add-member-btn:hover {
            background-color: #45a049;
        }
        /* Adjust the main content to be correctly positioned */
.main-content {
    margin-left: 250px; /* Adjust as needed */
    padding: 20px;
    margin-top: 20px; /* Add top margin to avoid overlap */
}

/* Adjust sidebar width if necessary */

/* Adjust the stats-container for proper spacing */
.stats-container {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
    flex-wrap: wrap; /* Allow wrapping on smaller screens */
}

/* Ensure the stat cards don't get too wide */
.stat-card {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 15px;
    width: 100%; /* Ensure they are responsive */
    max-width: 300px; /* Add a max width */
}

/* Adjust table container */
.table-container {
    margin-top: 20px;
    overflow-x: auto;
}

/* Make sure the page doesn't break layout */
@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
        padding: 10px;
    }
    .sidebar {
        width: 200px;
    }
}

    </style>
</head>

<body>
    <!-- Giữ nguyên sidebar từ file gốc -->
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
        <div class="d-flex justify-content-between align-items-center">
            <h2>Quản lý thành viên</h2>
            <div class="user-info">
                <span>Xin chào, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="main-content">
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon" style="background: #E8F5E9; color: #2E7D32;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $result->num_rows; ?></h3>
                    <p>Tổng số thành viên</p>
                </div>
            </div>
        </div>

        <!-- Danh sách thành viên -->
        <div class="member-list">
            <div class="table-container">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên người dùng</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Ngày đăng ký</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['role']); ?></td>
                                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                <td class="action-buttons">
                                    <button class="btn btn-sm btn-warning" onclick="editMember(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteMember(<?php echo $row['id']; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Thêm thành viên -->
    <!-- Modal Sửa thành viên -->
    <div class="modal fade" id="editMemberModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sửa thông tin thành viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editMemberForm" method="POST">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="user_id" id="edit_user_id">
                        <div class="mb-3">
                            <label class="form-label">Tên người dùng</label>
                            <input type="text" class="form-control" name="username" id="edit_username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="edit_email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Vai trò</label>
                            <select class="form-control" name="role" id="edit_role" required>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editMember(member) {
            document.getElementById('edit_user_id').value = member.id;
            document.getElementById('edit_username').value = member.username;
            document.getElementById('edit_email').value = member.email;
            document.getElementById('edit_role').value = member.role;
            new bootstrap.Modal(document.getElementById('editMemberModal')).show();
        }

        function deleteMember(userId) {
            if (confirm('Bạn có chắc chắn muốn xóa thành viên này?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="user_id" value="${userId}">
                `;
                document.body.append(form);
                form.submit();
            }
        }
    </script>
</body>

</html>