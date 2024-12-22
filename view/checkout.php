<?php
session_start();
include '../model/db.php';
include '../model/Cart.php';
include '../model/Course.php';

$cartItems = Cart::getCartItems();
$subtotal = array_sum(array_column($cartItems, 'price'));
$shipping = 30000;
$total = $subtotal + $shipping;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Hi English</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        :root {
            --primary-color: #2575fc;
            --secondary-color: #6a11cb;
            --success-color: #28a745;
            --error-color: #dc3545;
            --transition-speed: 0.3s;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            color: #333;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .checkout-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 1.5rem;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .checkout-form, .order-summary {
            background: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            transition: all var(--transition-speed) ease;
            backdrop-filter: blur(10px);
        }

        .checkout-form:hover, .order-summary:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .form-group {
            margin-bottom: 1.8rem;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.7rem;
            color: #444;
            font-weight: 600;
            transform: translateY(0);
            transition: all var(--transition-speed) ease;
        }

        .form-group input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: all var(--transition-speed) ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-group input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 117, 252, 0.2);
            outline: none;
        }

        .form-group input:focus + label {
            color: var(--primary-color);
            transform: translateY(-3px);
        }

        .payment-methods {
            margin: 2rem 0;
        }

        .payment-method {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            padding: 1.2rem;
            border: 2px solid #ddd;
            border-radius: 10px;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all var(--transition-speed) ease;
            background: white;
        }

        .payment-method:hover {
            border-color: var(--primary-color);
            background: rgba(37, 117, 252, 0.05);
            transform: scale(1.02);
        }

        .payment-method.selected {
            border-color: var(--primary-color);
            background: rgba(37, 117, 252, 0.1);
        }

        .payment-method img {
            width: 48px;
            height: 48px;
            object-fit: contain;
            transition: transform var(--transition-speed) ease;
        }

        .payment-method:hover img {
            transform: scale(1.1);
        }

        .order-summary {
            position: sticky;
            top: 2rem;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1.2rem;
            padding-bottom: 1.2rem;
            border-bottom: 1px solid #eee;
            transition: all var(--transition-speed) ease;
        }

        .summary-item:hover {
            transform: translateX(5px);
            color: var(--primary-color);
        }

        .summary-item:last-child {
            border-bottom: none;
            font-weight: 700;
            font-size: 1.3rem;
            color: var(--primary-color);
        }

        .btn-checkout, .btn-back {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 1.2rem;
            margin: 1rem 0;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition-speed) ease;
            text-decoration: none;
            gap: 0.5rem;
        }

        .btn-checkout {
            background: var(--primary-color);
            color: white;
        }

        .btn-checkout:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 117, 252, 0.3);
        }

        .btn-back {
            background: #f8f9fa;
            color: #333;
        }

        .btn-back:hover {
            background: #e9ecef;
            transform: translateY(-2px);
        }

        /* Success Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            text-align: center;
            max-width: 400px;
            width: 90%;
            animation: modalSlideUp 0.5s ease-out;
        }

        @keyframes modalSlideUp {
            from {
                opacity: 0;
                transform: translateY(100px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success-icon {
            font-size: 4rem;
            color: var(--success-color);
            margin-bottom: 1rem;
            animation: successPop 0.5s ease-out;
        }

        @keyframes successPop {
            0% {
                transform: scale(0);
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
            }
        }

        .loading-spinner {
            display: none;
            width: 40px;
            height: 40px;
            margin: 0 auto;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .checkout-container {
                grid-template-columns: 1fr;
                padding: 1rem;
            }

            .order-summary {
                position: static;
            }
        }
    </style>
</head>
<body>
    <div class="checkout-container animate__animated animate__fadeIn">
        <div class="checkout-form">
            <h2>Thông tin thanh toán</h2>
            <form id="checkoutForm" onsubmit="return handleSubmit(event)">
                <div class="form-group">
                    <label>Họ và tên</label>
                    <input type="text" name="fullname" required>
                </div>
                <div class="form-group">
                    <label>Số điện thoại</label>
                    <input type="tel" name="phone" required pattern="[0-9]{10}" title="Vui lòng nhập số điện thoại hợp lệ">
                </div>
                <div class="form-group">
                    <label>Địa chỉ</label>
                    <input type="text" name="address" required>
                </div>

                <h3>Phương thức thanh toán</h3>
                <div class="payment-methods">
                    <label class="payment-method">
                        <input type="radio" name="payment_method" value="momo" required>
                        <img src="../public/img/momo-logo.png" alt="MoMo">
                        <span>Ví MoMo</span>
                    </label>
                    <label class="payment-method">
                        <input type="radio" name="payment_method" value="vnpay" required>
                        <img src="../public/img/vnpay-logo.png" alt="VNPay">
                        <span>VNPay</span>
                    </label>
                    <label class="payment-method">
                        <input type="radio" name="payment_method" value="bank" required>
                        <img src="../public/img/bank-transfer.png" alt="Bank Transfer">
                        <span>Chuyển khoản ngân hàng</span>
                    </label>
                </div>

                <div class="loading-spinner" id="loadingSpinner"></div>

                <button type="submit" class="btn-checkout">
                    <i class="fas fa-lock"></i> Thanh toán an toàn
                </button>
            </form>
            <a href="shop.php" class="btn-back">
                <i class="fas fa-arrow-left"></i> Quay lại mua khóa học
            </a>
        </div>

        <div class="order-summary">
            <h3>Đơn hàng của bạn</h3>
            <?php foreach ($cartItems as $item): ?>
                <div class="summary-item">
                    <span><?php echo htmlspecialchars($item['name']); ?></span>
                    <span><?php echo number_format($item['price']); ?> VNĐ</span>
                </div>
            <?php endforeach; ?>
            <div class="summary-item">
                <span>Phí vận chuyển</span>
                <span><?php echo number_format($shipping); ?> VNĐ</span>
            </div>
            <div class="summary-item">
                <span>Tổng cộng</span>
                <span><?php echo number_format($total); ?> VNĐ</span>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal" id="successModal">
        <div class="modal-content">
            <i class="fas fa-check-circle success-icon"></i>
            <h2>Thanh toán thành công!</h2>
            <p>Cảm ơn bạn đã đặt hàng. Chúng tôi sẽ liên hệ với bạn sớm nhất!</p>
            <button class="btn-checkout" onclick="window.location.href='../index.php'">
                <i class="fas fa-home"></i> Về trang chủ
            </button>
        </div>
    </div>

    <script>
        // Highlight selected payment method
        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', function() {
                document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('selected'));
                this.classList.add('selected');
            });
        });

        // Form validation and submission
        function handleSubmit(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            // Show loading spinner
            document.getElementById('loadingSpinner').style.display = 'block';
            
            // Simulate API call
            setTimeout(() => {
                document.getElementById('loadingSpinner').style.display = 'none';
                showSuccessModal();
            }, 2000);

            return false;
        }

        // Show success modal
        function showSuccessModal() {
            const modal = document.getElementById('successModal');
            modal.style.display = 'flex';
            
            // Add click outside to close
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });
        }

        // Add input animation
        document.querySelectorAll('.form-group input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.classList.remove('focused');
                }
            });
        });
    </script>
</body>
</html>