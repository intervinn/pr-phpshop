<?php

use app\internal\Auth;
use app\internal\db\Database;

Auth::check();

$user = $_SESSION["user"];
$items = Database::getItems()->getByVendor($user->id);
$orders = Database::getOrders()->getByBuyer($user->id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
    <?php include $_SERVER["DOCUMENT_ROOT"] . '/internal/components/navbar.php'; ?>

    <main class="page-shell">
        <section class="page-header">
            <h1 class="page-title">Profile</h1>
            <p class="page-subtitle">Signed in as <?php echo htmlspecialchars($user->username); ?> (<?php echo htmlspecialchars($user->email); ?>)</p>
        </section>

        <?php if (isset($_GET["message"])): ?>
            <div class="alert success"><?php echo htmlspecialchars($_GET["message"]); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET["error"])): ?>
            <div class="alert error"><?php echo htmlspecialchars($_GET["error"]); ?></div>
        <?php endif; ?>

        <section class="grid two-column">
            <div class="form-panel">
                <h2 class="section-title">Change password</h2>
                <form action="/actions/profile/change-password.php" method="post" class="form-grid">
                    <div class="field-row">
                        <label for="current_password">Current password</label>
                        <input id="current_password" name="current_password" type="password">
                    </div>

                    <div class="field-row">
                        <label for="new_password">New password</label>
                        <input id="new_password" name="new_password" type="password">
                    </div>

                    <div class="actions">
                        <input class="primary" type="submit" value="Update password">
                    </div>
                </form>
            </div>

            <aside class="notice-panel card-muted">
                <h2 class="section-title">Quick access</h2>
                <p class="meta">Use your profile to keep account details current, review your published items, and track orders from a single page.</p>
                <div class="actions" style="margin-top: 16px;">
                    <a class="button-link" href="/pages/items/manage.php">Open item management</a>
                </div>
            </aside>
        </section>

        <section style="margin-top: 24px;">
            <h2 class="section-title">Published items</h2>

            <?php if (count($items) === 0): ?>
                <div class="empty-state">You have not created any items yet.</div>
            <?php else: ?>
                <div class="compact-list">
                    <?php foreach ($items as $item): ?>
                        <article class="card">
                            <div class="split-header">
                                <strong><?php echo htmlspecialchars($item->name); ?></strong>
                                <span class="badge <?php echo $item->active ? 'success' : 'muted'; ?>">
                                    <?php echo $item->active ? 'Published' : 'Hidden'; ?>
                                </span>
                            </div>
                            <p class="meta" style="margin-top: 10px;">
                                <span class="price">$<?php echo number_format($item->price, 2); ?></span>
                            </p>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <section style="margin-top: 24px;">
            <h2 class="section-title">Your orders</h2>

            <?php if (count($orders) === 0): ?>
                <div class="empty-state">You do not have any orders yet.</div>
            <?php else: ?>
                <div class="compact-list">
                    <?php foreach ($orders as $order): ?>
                        <article class="card">
                            <div class="split-header">
                                <strong><?php echo htmlspecialchars($order->item_name ?? 'Item'); ?></strong>
                                <span class="badge"><?php echo htmlspecialchars($order->status); ?></span>
                            </div>
                            <p class="meta" style="margin-top: 10px;">
                                <span class="price">$<?php echo number_format((float)($order->item_price ?? 0), 2); ?></span>
                                | Seller: <?php echo htmlspecialchars($order->vendor_name ?? 'Unknown'); ?>
                            </p>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
