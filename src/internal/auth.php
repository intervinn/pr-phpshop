<?php
namespace app\internal;

use app\internal\db\Database;

class Auth {
    public static function check() {
        session_start();

        $users = Database::getUsers();

        if (isset($_SESSION["user_id"])) {
            try {
                $_SESSION["user"] = $users->getById($_SESSION["user_id"]);
            } catch (\PDOException $e) {
                error_log($e->getMessage());
                header("Location: /pages/login.php");
                exit();
            }
        } else {
            header("Location: /pages/login.php");
            exit();
        }
    }
}