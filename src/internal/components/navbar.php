<nav class="topbar">
    <div class="topbar-inner">
        <a class="brand" href="/index.php">
            <span class="brand-mark"></span>
            <span>Marketplace</span>
        </a>

        <div class="nav-links">
            <a class="nav-link" href="/index.php">Home</a>
            <a class="nav-link" href="/pages/items/index.php">Items</a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <a class="nav-link" href="/pages/items/manage.php">Manage Items</a>
                <a class="nav-link" href="/pages/items/cart.php">Cart</a>
                <a class="nav-link" href="/pages/profile/index.php">Profile</a>
                <a class="nav-link primary" href="/actions/logout.php">Logout</a>
            <?php else: ?>
                <a class="nav-link" href="/pages/login.php">Login</a>
                <a class="nav-link primary" href="/pages/register.php">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
