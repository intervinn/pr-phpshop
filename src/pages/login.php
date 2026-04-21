<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
    <?php include $_SERVER["DOCUMENT_ROOT"] . '/internal/components/navbar.php'; ?>

    <main class="page-shell">
        <section class="page-header">
            <h1 class="page-title">Login</h1>
            <p class="page-subtitle">Sign in to manage listings, place orders, and leave reviews.</p>
        </section>

        <section class="grid two-column">
            <div class="form-panel">
                <?php if (isset($_GET["error"])): ?>
                    <div class="alert error"><?php echo htmlspecialchars($_GET["error"]); ?></div>
                <?php endif; ?>

                <form action="/actions/login.php" method="post" class="form-grid">
                    <div class="field-row">
                        <label for="email">Email</label>
                        <input name="email" id="email" type="text">
                    </div>
                    
                    <div class="field-row">
                        <label for="password">Password</label>
                        <input name="password" id="password" type="password">
                    </div>

                    <div class="actions">
                        <input class="primary" type="submit" value="Sign in">
                    </div>
                </form>
            </div>

            <aside class="notice-panel card-muted">
                <h2 class="section-title">New here?</h2>
                <p class="meta">Create an account to publish items, manage visibility, view orders, and collect reviews from buyers.</p>
                <div class="actions" style="margin-top: 16px;">
                    <a class="button-link" href="/pages/register.php">Create account</a>
                </div>
            </aside>
        </section>
    </main>
</body>
</html>
