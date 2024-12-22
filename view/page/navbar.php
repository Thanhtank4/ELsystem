<nav class="navbar">
    <div class="container nav-container">
        <div class="nav-left">
            <a href="../index.php" class="logo">English Learning</a>
        </div>

        <div class="nav-right">
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="user-menu">
                    <span class="user-progress">Level: <?php echo isset($_SESSION['user_level']) ? $_SESSION['user_level'] : '1'; ?></span>
                    <div class="dropdown">
                        <button class="dropbtn">
                            <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'User'; ?>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-content">
                            <a href="profile.php">My Profile</a>
                            <a href="settings.php">Settings</a>
                            <div class="dropdown-divider"></div>
                            <a href="logout.php">Logout</a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <a href="login.php" class="nav-btn login">Login</a>
                <a href="register.php" class="nav-btn register">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>