<?php

use app\internal\Auth;
use app\internal\db\Database;

require_once $_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php";

Auth::check();

$itemId = (int)($_POST["item_id"] ?? 0);
$rating = (int)($_POST["rating"] ?? 0);
$comment = trim($_POST["comment"] ?? "");
$user = $_SESSION["user"];
$item = Database::getItems()->getById($itemId);

if ($item === null) {
    header("Location: /pages/items/index.php");
    exit();
}

if ($item->vendor === $user->id) {
    header("Location: /pages/items/view.php?id=" . $itemId . "&error=" . urlencode("You cannot review your own item."));
    exit();
}

if ($rating < 1 || $rating > 5) {
    header("Location: /pages/items/view.php?id=" . $itemId . "&error=" . urlencode("Rating must be between 1 and 5."));
    exit();
}

Database::getReviews()->create($itemId, $user->id, $rating, $comment);
header("Location: /pages/items/view.php?id=" . $itemId . "&message=" . urlencode("Review added."));
exit();
