<?php

use app\internal\Auth;
use app\internal\db\Database;

Auth::check();
$user = $_SESSION["user"];
$items = Database::getUsers()->getCart($user->id);

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
        <h1 class="page-title">Cart</h1>

        <?php if (isset($_GET["message"])): ?>
            <div class="alert success"><?php echo htmlspecialchars($_GET["message"]); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET["error"])): ?>
            <div class="alert error"><?php echo htmlspecialchars($_GET["error"]); ?></div>
        <?php endif; ?>

        <section>
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
        </section>
    </main>
</body>
</html>
