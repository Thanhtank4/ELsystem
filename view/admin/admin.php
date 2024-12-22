<?php
session_start();
require_once '../model/db.php';
require_once '../controller/UserController.php';

$userController = new UserController($conn);
$userController->requireAdmin(); // Sẽ redirect nếu không phải admin

// Rest of your admin dashboard code
?>