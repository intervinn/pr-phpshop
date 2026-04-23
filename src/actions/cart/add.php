<?php

use app\internal\Auth;
use app\internal\db\Database;

Auth::check();

$itemId = $_POST["item_id"];
$user = $_SESSION["user"];

$cart = Database::getUsers()->getCartIds($user->id);

if (in_array($itemId, $cart)) {
    header("Location: /pages/items/view.php?id=" . $itemId . "&error=" . urlencode("Item is already in the cart."));
    exit();
}

array_push($cart, $itemId);
Database::getUsers()->updateCart($user->id, $cart);
header("Location: /pages/items/view.php?id=" . $itemId . "&message=" . urlencode("Item added to cart."));