<?php

use app\internal\db\Database;

session_start();

$query = trim($_GET["q"] ?? "");
$items = $query === ""
    ? Database::getItems()->getActive(0, 50)
    : Database::getItems()->searchActive($query, 0, 50);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
    <?php include $_SERVER["DOCUMENT_ROOT"] . '/internal/components/navbar.php'; ?>

    <main class="page-shell">
        <section class="page-header">
            <h1 class="page-title">Items</h1>
            <p class="page-subtitle">Search the published catalog and explore what other sellers have listed.</p>
        </section>

        <section class="form-panel hero-search">
            <form method="get" action="/pages/items/index.php" class="form-grid">
                <div class="field-row">
                    <label for="q">Search</label>
                    <div class="search-row">
                        <input id="q" name="q" type="text" value="<?php echo htmlspecialchars($query); ?>" placeholder="Name or description">
                        <input class="primary" type="submit" value="Find items">
                    </div>
                </div>
            </form>
        </section>

        <?php if ($query !== ""): ?>
            <div class="notice-panel" style="margin-bottom: 20px;">
                <p class="meta">Showing results for "<?php echo htmlspecialchars($query); ?>"</p>
            </div>
        <?php endif; ?>

        <?php if (count($items) === 0): ?>
            <div class="empty-state">No published items matched your search.</div>
        <?php else: ?>
            <div class="grid cards">
                <?php foreach ($items as $item): ?>
                    <article class="card">
                        <h2 class="card-title">
                            <a href="/pages/items/view.php?id=<?php echo $item->id; ?>">
                                <?php echo htmlspecialchars($item->name); ?>
                            </a>
                        </h2>
                        <p class="meta">
                            Seller: <?php echo htmlspecialchars($item->vendor_name ?? 'Unknown'); ?>
                        </p>
                        <p class="meta">
                            <span class="price">$<?php echo number_format($item->price, 2); ?></span>
                            | Average rating:
                            <?php echo $item->review_count ? number_format((float)$item->average_rating, 1) : 'No ratings'; ?>
                        </p>
                        <p class="description"><?php echo nl2br(htmlspecialchars($item->description)); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
