<?php
use app\internal\db\Database;

session_start();

$items = Database::getItems()->getActive(0, 12);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
    <?php include $_SERVER["DOCUMENT_ROOT"] . '/internal/components/navbar.php'; ?>

    <main class="page-shell">
        <section class="page-header">
            <h1 class="page-title">Marketplace</h1>
            <p class="page-subtitle">Browse published items, search the catalog, and manage your own listings in one clean workspace.</p>
        </section>

        <section class="form-panel hero-search">
            <form action="/pages/items/index.php" method="get" class="form-grid">
                <div class="field-row">
                    <label for="q">Search items</label>
                    <div class="search-row">
                        <input id="q" name="q" type="text" placeholder="Search by name or description">
                        <input class="primary" type="submit" value="Search">
                    </div>
                </div>
            </form>
        </section>

        <section>
            <h2 class="section-title">Latest published items</h2>

            <?php if (count($items) === 0): ?>
                <div class="empty-state">No items have been published yet.</div>
            <?php else: ?>
                <div class="grid cards">
                    <?php foreach ($items as $item): ?>
                        <article class="card">
                            <h3 class="card-title">
                                <a href="/pages/items/view.php?id=<?php echo $item->id; ?>">
                                    <?php echo htmlspecialchars($item->name); ?>
                                </a>
                            </h3>
                            <p class="meta">
                                Seller: <?php echo htmlspecialchars($item->vendor_name ?? 'Unknown'); ?>
                            </p>
                            <p class="meta">
                                <span class="price">$<?php echo number_format($item->price, 2); ?></span>
                                | Reviews: <?php echo (int)($item->review_count ?? 0); ?>
                            </p>
                            <p class="description"><?php echo nl2br(htmlspecialchars($item->description)); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
