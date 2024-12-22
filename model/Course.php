<?php
include_once 'db.php';

class Course
{
    // Hàm thêm khóa học vào database
    public static function addCourse($name, $price, $description, $duration, $image = '')
    {
        global $db;

        // Kiểm tra nếu có hình ảnh được tải lên
        if ($image) {
            $query = "INSERT INTO courses (name, price, description, duration" . ($image ? ", image" : "") . ") VALUES (?, ?, ?, ?" . ($image ? ", ?" : "") . ")";
            $stmt = mysqli_prepare($db, $query);

            if (!$stmt) {
                error_log("MySQL Prepare Error: " . mysqli_error($db));
                return false;
            }

            mysqli_stmt_bind_param($stmt, 'sssss', $name, $price, $description, $duration, $image);
        } else {
            $query = "INSERT INTO courses (name, price, description, duration) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($db, $query);

            if (!$stmt) {
                error_log("MySQL Prepare Error: " . mysqli_error($db));
                return false;
            }

            mysqli_stmt_bind_param($stmt, 'ssss', $name, $price, $description, $duration);
        }
        if (!mysqli_stmt_execute($stmt)) {
            error_log("MySQL Execute Error: " . mysqli_stmt_error($stmt));
            return false;
        }

        return true;
    }

    // Hàm xóa khóa học
    public static function deleteCourse($id)
    {
        global $db;
        $query = "DELETE FROM courses WHERE id = ?";
        $stmt = mysqli_prepare($db, $query);

        if (!$stmt) {
            error_log("MySQL Prepare Error: " . mysqli_error($db));
            return false;
        }

        mysqli_stmt_bind_param($stmt, 'i', $id);

        if (!mysqli_stmt_execute($stmt)) {
            error_log("MySQL Execute Error: " . mysqli_stmt_error($stmt));
            return false;
        }

        return true;
    }

    // Hàm cập nhật thông tin khóa học
    public static function updateCourse($id, $name, $price, $description, $duration, $image = '')
{
    global $db;
    
    // Bắt đầu câu lệnh UPDATE
    $sql = "UPDATE courses SET name=?, price=?, description=?, duration=?";
    $types = "ssss"; // string types for the first 4 parameters
    $params = [&$name, &$price, &$description, &$duration]; // Truyền tham số theo tham chiếu

    // Nếu có hình ảnh mới, thêm vào câu lệnh và danh sách tham số
    if ($image) {
        $sql .= ", image=?";
        $types .= "s"; // add string type for image
        $params[] = &$image; // Truyền tham số image theo tham chiếu
    }

    // Thêm phần WHERE để chỉ cập nhật khóa học có id cụ thể
    $sql .= " WHERE id=?";
    $types .= "i"; // integer type for id
    $params[] = &$id; // Truyền tham số id theo tham chiếu

    // Chuẩn bị câu truy vấn
    $stmt = mysqli_prepare($db, $sql);
    if (!$stmt) {
        error_log("MySQL Prepare Error: " . mysqli_error($db));
        return false;
    }

    // Bind tham số động với câu truy vấn đã chuẩn bị
    $bind_params = array_merge([$stmt, $types], $params);
    call_user_func_array('mysqli_stmt_bind_param', $bind_params);

    // Thực thi câu lệnh SQL
    if (!mysqli_stmt_execute($stmt)) {
        error_log("MySQL Execute Error: " . mysqli_stmt_error($stmt));
        return false;
    }

    return true;
}

    // Hàm lấy tất cả khóa học
    public static function getAllCourses()
    {
        global $db;
        $query = "SELECT * FROM courses";
        $result = mysqli_query($db, $query);

        if (!$result) {
            error_log("MySQL Query Error: " . mysqli_error($db));
            return [];
        }

        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    public static function getCourseById($id)
    {
        global $db;
        $query = "SELECT * FROM courses WHERE id = ?";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }
}
