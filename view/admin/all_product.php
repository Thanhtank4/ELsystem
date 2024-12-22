<?php
session_start();
include '../../models/connect_db.php';

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch products
$query = "SELECT * FROM products LIMIT 20";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN - Liệt kê thương hiệu sản phẩm</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --purple-700: #6D28D9;
            --purple-600: #7C3AED;
            --purple-400: #A78BFA;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom right, var(--purple-600), #FCD7D7, var(--purple-400));
            min-height: 100vh;
        }

        .header {
            background-color: var(--purple-700);
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 1rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .title {
            background-color: #E8F5E9;
            padding: 1rem;
            margin: -1rem -1rem 1rem -1rem;
            border-radius: 8px 8px 0 0;
            text-align: center;
            font-weight: bold;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .checkbox {
            width: 20px;
            height: 20px;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn {
            padding: 0.25rem 0.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-edit {
            color: #2196F3;
        }

        .btn-delete {
            color: #F44336;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .page-link {
            padding: 0.5rem 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
        }

        .page-link.active {
            background-color: var(--purple-600);
            color: white;
            border-color: var(--purple-600);
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">ADMIN</div>
        <div class="user-menu">
            <i class="fas fa-moon"></i>
            <div class="user-info">
            <span>Xin chào, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        </div>
        </div>
    </div>

    <div class="container">
        <div class="title">LIỆT KÊ THƯƠNG HIỆU SẢN PHẨM</div>

        <div class="actions">
            <div>
                <select name="bulk_action" id="bulk_action">
                    <option value="">Bulk action</option>
                    <option value="delete">Delete</option>
                    <option value="hide">Hide</option>
                </select>
                <button class="btn">Apply</button>
            </div>
            <div>
                <input type="text" placeholder="Search...">
                <button class="btn">Go!</button>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" class="checkbox"></th>
                    <th>Tên sách</th>
                    <th>Thể loại</th>
                    <th>Hiển thị</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><input type="checkbox" class="checkbox"></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                    <td><i class="fas fa-thumbs-down"></i></td>
                    <td class="action-buttons">
                        <button class="btn btn-edit"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-delete"><i class="fas fa-times"></i></button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="pagination">
            <a href="#" class="page-link">«</a>
            <a href="#" class="page-link active">1</a>
            <a href="#" class="page-link">2</a>
            <a href="#" class="page-link">3</a>
            <a href="#" class="page-link">4</a>
            <a href="#" class="page-link">»</a>
        </div>
    </div>

    <footer style="text-align: center; padding: 1rem; color: #666;">
        ban quyen cua ai W3layouts
    </footer>
</body>
</html>