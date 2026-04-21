<?php

use app\internal\Auth;
use app\internal\db\Database;

require_once $_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php";

Auth::check();

$currentPassword = $_POST["current_password"] ?? "";
$newPassword = $_POST["new_password"] ?? "";
$user = $_SESSION["user"];

if ($currentPassword === "" || $newPassword === "") {
    header("Location: /pages/profile/index.php?error=" . urlencode("Both password fields are required."));
    exit();
}

if (!password_verify($currentPassword, $user->password_hash)) {
    header("Location: /pages/profile/index.php?error=" . urlencode("Current password is incorrect."));
    exit();
}

Database::getUsers()->updatePassword($user->id, password_hash($newPassword, PASSWORD_BCRYPT));
header("Location: /pages/profile/index.php?message=" . urlencode("Password updated successfully."));
exit();
