<?php

use app\internal\db\Database;

session_start();

$itemId = (int)($_GET["id"] ?? 0);
$item = Database::getItems()->getById($itemId);

if ($item === null || (!$item->active && (!isset($_SESSION["user_id"]) || $_SESSION["user_id"] !== $item->vendor))) {
    http_response_code(404);
    echo "Item not found";
    exit();
}

$reviews = Database::getReviews()->getByItem($item->id);
$canReview = isset($_SESSION["user_id"]) && $_SESSION["user_id"] !== $item->vendor;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($item->name); ?></title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
    <?php include $_SERVER["DOCUMENT_ROOT"] . '/internal/components/navbar.php'; ?>

    <main class="page-shell">
        <?php if (isset($_GET["message"])): ?>
            <div class="alert success"><?php echo htmlspecialchars($_GET["message"]); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET["error"])): ?>
            <div class="alert error"><?php echo htmlspecialchars($_GET["error"]); ?></div>
        <?php endif; ?>

        <section class="grid two-column">
            <article class="card stack">
                <div class="split-header">
                    <div>
                        <h1 class="page-title" style="font-size: clamp(1.7rem, 2.6vw, 2.4rem); margin-bottom: 8px;"><?php echo htmlspecialchars($item->name); ?></h1>
                        <p class="meta">Seller: <?php echo htmlspecialchars($item->vendor_name ?? 'Unknown'); ?></p>
                    </div>
                    <span class="badge <?php echo $item->active ? 'success' : 'muted'; ?>">
                        <?php echo $item->active ? 'Published' : 'Hidden'; ?>
                    </span>
                </div>

                <p class="meta">
                    <span class="price">$<?php echo number_format($item->price, 2); ?></span>
                    | Average rating:
                    <?php echo $item->review_count ? number_format((float)$item->average_rating, 1) . ' / 5' : 'No ratings yet'; ?>
                    | Reviews: <?php echo (int)($item->review_count ?? 0); ?>
                </p>
                <p class="description"><?php echo nl2br(htmlspecialchars($item->description)); ?></p>

                <?php if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] !== $item->vendor): ?>
                    <form action="/actions/orders/create.php" method="post" class="actions">
                        <input type="hidden" name="item_id" value="<?php echo $item->id; ?>">
                        <input class="primary" type="submit" value="Place order">
                    </form>

                    <form action="/actions/cart/add.php" method="post" class="actions">
                        <input type="hidden" name="item_id" value="<?php echo $item->id; ?>">
                        <input class="primary" type="submit" value="Add to cart">
                    </form>
                <?php endif; ?>
            </article>

            <?php if ($canReview): ?>
                <section class="form-panel">
                    <h2 class="section-title">Leave a review</h2>
                    <form action="/actions/reviews/create.php" method="post" class="form-grid">
                        <input type="hidden" name="item_id" value="<?php echo $item->id; ?>">

                        <div class="field-row">
                            <label for="rating">Rating</label>
                            <input id="rating" name="rating" type="number" min="1" max="5" required>
                        </div>

                        <div class="field-row">
                            <label for="comment">Comment</label>
                            <textarea id="comment" name="comment"></textarea>
                        </div>

                        <div class="actions">
                            <input class="primary" type="submit" value="Submit review">
                        </div>
                    </form>
                </section>
            <?php else: ?>
                <aside class="notice-panel card-muted">
                    <h2 class="section-title">Reviews</h2>
                    <p class="meta">Sign in as a buyer to leave feedback on this item.</p>
                </aside>
            <?php endif; ?>
        </section>

        <section style="margin-top: 24px;">
            <h2 class="section-title">Reviews</h2>

            <?php if (count($reviews) === 0): ?>
                <div class="empty-state">No reviews yet.</div>
            <?php else: ?>
                <div class="compact-list">
                    <?php foreach ($reviews as $review): ?>
                        <article class="card card-muted">
                            <div class="split-header">
                                <strong><?php echo htmlspecialchars($review->username ?? 'User'); ?></strong>
                                <span class="badge">Rating: <?php echo (int)$review->rating; ?>/5</span>
                            </div>
                            <p class="description" style="margin-top: 12px;"><?php echo nl2br(htmlspecialchars($review->comment ?? '')); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
