<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php";

use app\internal\db\Database;
use app\internal\models\User;

$username = $_POST["username"];
$email = $_POST["email"];
$password = $_POST["password"];

if ($username === "" || $email === "" || $password === "") {
    header("Location: /pages/register.php?error=" . urlencode("All fields are required."));
    exit();
}

$users = Database::getUsers();

$user = new User();
$user->username = $username;
$user->email = $email;
$user->password_hash = password_hash($password, PASSWORD_BCRYPT);

try {
    session_start();
    $users->save($user);
    $user = $users->getByEmail($email);
    $_SESSION["user_id"] = $user->id;
    header("Location: /");
    exit();
} catch (\PDOException $e) {
    header("Location: /pages/register.php?error=" . urlencode("Register failed. Try a different email."));
    exit();
}
