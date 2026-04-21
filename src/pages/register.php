<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
    <?php include $_SERVER["DOCUMENT_ROOT"] . '/internal/components/navbar.php'; ?>

    <main class="page-shell">
        <section class="page-header">
            <h1 class="page-title">Register</h1>
            <p class="page-subtitle">Create your account to start publishing items and using the marketplace features.</p>
        </section>

        <section class="grid two-column">
            <div class="form-panel">
                <?php if (isset($_GET["error"])): ?>
                    <div class="alert error"><?php echo htmlspecialchars($_GET["error"]); ?></div>
                <?php endif; ?>

                <form action="/actions/register.php" method="post" class="form-grid">
                    <div class="field-row">
                        <label for="username">Username</label>
                        <input name="username" id="username" type="text">
                    </div>
                    
                    <div class="field-row">
                        <label for="email">Email</label>
                        <input name="email" id="email" type="text">
                    </div>
                    
                    <div class="field-row">
                        <label for="password">Password</label>
                        <input name="password" id="password" type="password">
                    </div>

                    <div class="actions">
                        <input class="primary" type="submit" value="Create account">
                    </div>
                </form>
            </div>

            <aside class="notice-panel card-muted">
                <h2 class="section-title">Already registered?</h2>
                <p class="meta">If you already have an account, sign in to continue managing items and orders.</p>
                <div class="actions" style="margin-top: 16px;">
                    <a class="button-link" href="/pages/login.php">Go to login</a>
                </div>
            </aside>
        </section>
    </main>
</body>
</html>
