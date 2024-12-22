<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "english";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

class UserModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function login($username, $password) {
        $sql = "SELECT id, username, password, role FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($user = $result->fetch_assoc()) {
            if(password_verify($password, $user['password'])) {
                $this->updateLastLogin($user['id']);
                return [
                    'success' => true,
                    'user' => $user
                ];
            }
        }
        return [
            'success' => false,
            'message' => 'Tên đăng nhập hoặc mật khẩu không đúng'
        ];
    }

    public function register($username, $email, $password) {
        // Kiểm tra username đã tồn tại
        if($this->checkUserExists($username)) {
            return [
                'success' => false,
                'message' => 'Tên đăng nhập đã tồn tại'
            ];
        }

        // Kiểm tra email đã tồn tại
        if($this->checkEmailExists($email)) {
            return [
                'success' => false,
                'message' => 'Email đã được sử dụng'
            ];
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $role = 'user'; // Mặc định role là user
        $sql = "INSERT INTO users (username, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $email, $hashedPassword, $role);
        
        if($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Đăng ký thành công'
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Có lỗi xảy ra, vui lòng thử lại'
        ];
    }

    private function checkUserExists($username) {
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    private function checkEmailExists($email) {
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    private function updateLastLogin($userId) {
        $sql = "UPDATE users SET last_login = NOW() WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }

    public function isAdmin($username) {
        $sql = "SELECT role FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if($row = $result->fetch_assoc()) {
            return $row['role'] === 'admin';
        }
        return false;
    }

    public function getUserInfo($username) {
        $sql = "SELECT username, email, role, created_at FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

}
?>