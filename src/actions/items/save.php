<?php

use app\internal\Auth;
use app\internal\db\Database;
use app\internal\models\Item;

require_once $_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php";

Auth::check();

$user = $_SESSION["user"];
$id = (int)($_POST["id"] ?? 0);
$name = trim($_POST["name"] ?? "");
$description = trim($_POST["description"] ?? "");
$price = (float)($_POST["price"] ?? 0);
$active = isset($_POST["active"]);

if ($name === "" || $description === "" || $price < 0) {
    header("Location: /pages/items/manage.php?error=" . urlencode("Name, description, and a valid price are required."));
    exit();
}

$item = new Item();
$item->id = $id;
$item->vendor = $user->id;
$item->name = $name;
$item->description = $description;
$item->price = $price;
$item->active = $active;

if ($id > 0) {
    Database::getItems()->update($item);
    header("Location: /pages/items/manage.php?message=" . urlencode("Item updated."));
    exit();
}

Database::getItems()->create($item);
header("Location: /pages/items/manage.php?message=" . urlencode("Item created."));
exit();
