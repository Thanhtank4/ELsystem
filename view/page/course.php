<?php
require_once __DIR__ . '/../../model/db.php';
require_once __DIR__ . '/../../model/LessonModel.php';
require_once __DIR__ . '/../../controller/LessonController.php';
?>
<!-- HTML Structure -->
<section class="course-section" id="premium-section">
    <div class="section-header">
        <h2><i class="fas fa-crown"></i> PREMIUM COURSES</h2>
        <p class="premium-description">Unlock advanced features and exclusive content with our premium courses.</p>
    </div>
    <div class="course-grid">
        <?php if (!empty($courses) && is_array($courses)): ?>
            <?php foreach ($courses as $course): ?>
                <div class="course-card premium-card">
                    <div class="card-header premium-header">
                        <h3><?php echo htmlspecialchars($course['name']); ?></h3>
                        <div class="price">$<?php echo number_format($course['price'], 2); ?></div>
                        <div class="lesson-count"><?php echo htmlspecialchars($course['lesson_count']); ?> lessons</div>
                    </div>
                    <div class="card-content">
                        <ul class="premium-features">
                            <?php 
                            $features = json_decode($course['features'], true);
                            foreach ($features as $feature): 
                            ?>
                                <li><i class="fas fa-check" aria-hidden="true"></i> <?php echo htmlspecialchars($feature); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button class="add-to-cart-btn" 
                                onclick="addToCart(<?php echo $course['id']; ?>, '<?php echo htmlspecialchars($course['name']); ?>', <?php echo $course['price']; ?>)">
                            <i class="fas fa-cart-plus"></i> Thêm vào giỏ hàng
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No premium courses available at the moment. Please check back later!</p>
        <?php endif; ?>
    </div>
</section>


<script>
function addToCart(courseId, courseName, price) {
    fetch('../controllers/cart_actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'add',
            courseId: courseId,
            courseName: courseName,
            price: price
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartCount();
            showAddToCartConfirmation(courseName);
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>