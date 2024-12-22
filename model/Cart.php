<?php
class Cart {
    // Giỏ hàng sẽ được lưu trong session
    public static function addItem($courseId) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $_SESSION['cart'][] = $courseId;
    }

    public static function getCartItems() {
        if (!isset($_SESSION['cart'])) {
            return [];
        }

        $items = [];
        foreach ($_SESSION['cart'] as $courseId) {
            $items[] = Course::getCourseById($courseId);
        }
        return $items;
    }

    public static function removeItem($courseId) {
        if (($key = array_search($courseId, $_SESSION['cart'])) !== false) {
            unset($_SESSION['cart'][$key]);
        }
    }
}
