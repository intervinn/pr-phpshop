<?php

use app\internal\Auth;
use app\internal\db\Database;

Auth::check();

$user = $_SESSION["user"];
$items = Database::getItems()->getByVendor($user->id);
$editId = (int)($_GET["edit"] ?? 0);
$editItem = null;

if ($editId > 0) {
    foreach ($items as $ownedItem) {
        if ($ownedItem->id === $editId) {
            $editItem = $ownedItem;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Items</title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
    <?php include $_SERVER["DOCUMENT_ROOT"] . '/internal/components/navbar.php'; ?>

    <main class="page-shell">
        <section class="page-header">
            <h1 class="page-title">Manage Items</h1>
            <p class="page-subtitle">Create new listings, update current ones, and control whether each item is visible to buyers.</p>
        </section>

        <?php if (isset($_GET["message"])): ?>
            <div class="alert success"><?php echo htmlspecialchars($_GET["message"]); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET["error"])): ?>
            <div class="alert error"><?php echo htmlspecialchars($_GET["error"]); ?></div>
        <?php endif; ?>

        <section class="grid two-column">
            <div class="form-panel">
                <h2 class="section-title"><?php echo $editItem ? 'Edit item' : 'Add new item'; ?></h2>
                <form action="/actions/items/save.php" method="post" class="form-grid">
                    <input type="hidden" name="id" value="<?php echo $editItem ? $editItem->id : 0; ?>">

                    <div class="field-row">
                        <label for="name">Name</label>
                        <input id="name" name="name" type="text" value="<?php echo htmlspecialchars($editItem->name ?? ''); ?>" required>
                    </div>

                    <div class="field-row">
                        <label for="price">Price</label>
                        <input id="price" name="price" type="number" min="0" step="0.01" value="<?php echo htmlspecialchars((string)($editItem->price ?? '')); ?>" required>
                    </div>

                    <div class="field-row">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" required><?php echo htmlspecialchars($editItem->description ?? ''); ?></textarea>
                    </div>

                    <label class="badge" style="width: fit-content;">
                        <input type="checkbox" name="active" value="1" <?php echo ($editItem ? $editItem->active : true) ? 'checked' : ''; ?>>
                        Published
                    </label>

                    <div class="actions">
                        <input class="primary" type="submit" value="<?php echo $editItem ? 'Save changes' : 'Create item'; ?>">
                    </div>
                </form>
            </div>

            <aside class="notice-panel card-muted">
                <h2 class="section-title">Listing notes</h2>
                <p class="meta">Published items appear in search and on the marketplace home page. Hidden items stay available for editing and private review.</p>
            </aside>
        </section>

        <section style="margin-top: 24px;">
            <h2 class="section-title">Your items</h2>

            <?php if (count($items) === 0): ?>
                <div class="empty-state">You have not added any items yet.</div>
            <?php else: ?>
                <div class="compact-list">
                    <?php foreach ($items as $item): ?>
                        <article class="card">
                            <div class="split-header">
                                <div>
                                    <strong><?php echo htmlspecialchars($item->name); ?></strong>
                                    <p class="meta">
                                        <span class="price">$<?php echo number_format($item->price, 2); ?></span>
                                    </p>
                                </div>
                                <span class="badge <?php echo $item->active ? 'success' : 'muted'; ?>">
                                    <?php echo $item->active ? 'Published' : 'Hidden'; ?>
                                </span>
                            </div>
                            <p class="description" style="margin-top: 12px;"><?php echo nl2br(htmlspecialchars($item->description)); ?></p>
                            <div class="actions">
                                <a class="button-link" href="/pages/items/manage.php?edit=<?php echo $item->id; ?>">Edit</a>
                                <form action="/actions/items/toggle.php" method="post" class="inline-form">
                                    <input type="hidden" name="id" value="<?php echo $item->id; ?>">
                                    <input type="hidden" name="active" value="<?php echo $item->active ? 0 : 1; ?>">
                                    <input type="submit" value="<?php echo $item->active ? 'Hide' : 'Publish'; ?>">
                                </form>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
