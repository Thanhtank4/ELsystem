<?php
include_once '../models/Cart.php';

class CartController {
    public function add() {
        $courseId = $_GET['id'];
        Cart::addItem($courseId);
        header('Location: ../view/cart/cart.php');
    }

    public function checkout() {
        $cartItems = Cart::getCartItems();
        include '../views/cart/checkout.php';
    }

    public function remove() {
        $courseId = $_GET['id'];
        Cart::removeItem($courseId);
        header('Location: ../view/cart/cart.php');
    }
}
