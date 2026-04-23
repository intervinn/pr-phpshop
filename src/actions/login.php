<?php
use app\internal\db\Database;

$email = $_POST["email"];
$password = $_POST["password"];

try {
    $users = Database::getUsers();
    $user = $users->getByEmail($email);

    if ($user == null) {
        header("Location: /pages/login.php?error=" . urlencode("User does not exist."));
        exit();
    }

    if (password_verify($password, $user->password_hash)) {
        session_start();
        $_SESSION["user_id"] = $user->id;
        header("Location: /");
        exit();
    } else {
        header("Location: /pages/login.php?error=" . urlencode("Invalid password."));
        exit();
    }
} catch (\Exception $e) {
    error_log("login failed: " . $e->getMessage());
    header("Location: /pages/login.php?error=" . urlencode("Invalid data, try again later."));
    exit();
}
