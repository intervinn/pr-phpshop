<?php
namespace app\internal\db;

use app\internal\db\MySqlStorage;
use app\internal\db\UserRepository;

class Database {
    private static $host = "mysql";
    private static $user = "username";
    private static $pass = "userpassword";
    private static $db = "app_db";

    public static function getMySql(): MySqlStorage {
        if (isset($GLOBALS["pdo_mysql"])) {
            return $GLOBALS["pdo_mysql"];
        }
        $GLOBALS["pdo_mysql"] = new MySqlStorage(self::$host, self::$user, self::$pass, self::$db);
        return $GLOBALS["pdo_mysql"];
    }

    public static function getUsers(): UserRepository {
        if (isset($GLOBALS["pdo_users"])) {
            return $GLOBALS["pdo_users"];
        }
        $mysql = self::getMySql();
        $GLOBALS["pdo_users"] = new UserRepository($mysql->pdo);
        return $GLOBALS["pdo_users"];
    }

    public static function getItems(): ItemRepository {
        if (isset($GLOBALS["pdo_items"])) {
            return $GLOBALS["pdo_items"];
        }
        $mysql = self::getMySql();
        $GLOBALS["pdo_items"] = new ItemRepository($mysql->pdo);
        return $GLOBALS["pdo_items"];
    }

    public static function getOrders(): OrderRepository {
        if (isset($GLOBALS["pdo_orders"])) {
            return $GLOBALS["pdo_orders"];
        }
        $mysql = self::getMySql();
        $GLOBALS["pdo_orders"] = new OrderRepository($mysql->pdo);
        return $GLOBALS["pdo_orders"];
    }

    public static function getReviews(): ReviewRepository {
        if (isset($GLOBALS["pdo_reviews"])) {
            return $GLOBALS["pdo_reviews"];
        }
        $mysql = self::getMySql();
        $GLOBALS["pdo_reviews"] = new ReviewRepository($mysql->pdo);
        return $GLOBALS["pdo_reviews"];
    }
}
