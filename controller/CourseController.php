<?php
include_once '../model/Course.php';

class CourseController
{

    // Lấy danh sách khóa học
    public function index()
    {
        $courses = Course::getAllCourses();
        include '../view/admin/manage_courses.php'; // Chuyển hướng tới trang quản lý khóa học
    }
}

?>
