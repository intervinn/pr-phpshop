<?php

use app\internal\Auth;
use app\internal\db\Database;

require_once $_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php";

Auth::check();

$user = $_SESSION["user"];
$id = (int)($_POST["id"] ?? 0);
$active = ((int)($_POST["active"] ?? 0)) === 1;

if ($id <= 0) {
    header("Location: /pages/items/manage.php?error=" . urlencode("Invalid item."));
    exit();
}

Database::getItems()->setVisibility($id, $user->id, $active);
header("Location: /pages/items/manage.php?message=" . urlencode($active ? "Item published." : "Item hidden."));
exit();
