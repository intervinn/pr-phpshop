<?php

use app\internal\Auth;
use app\internal\db\Database;

require_once $_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php";

Auth::check();

$itemId = (int)($_POST["item_id"] ?? 0);
$user = $_SESSION["user"];
$item = Database::getItems()->getById($itemId);

if ($item === null || !$item->active) {
    header("Location: /pages/items/index.php");
    exit();
}

if ($item->vendor === $user->id) {
    header("Location: /pages/items/view.php?id=" . $itemId . "&error=" . urlencode("You cannot order your own item."));
    exit();
}

Database::getOrders()->create($user->id, $itemId);
header("Location: /pages/profile/index.php?message=" . urlencode("Order created."));
exit();
