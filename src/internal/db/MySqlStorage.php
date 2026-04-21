<?php
namespace app\internal\db;

use PDO;

class MySqlStorage {
    public \PDO $pdo;

    function __construct($host, $user, $pwd, $db) {
        try {
            $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
            $this->pdo = new PDO($dsn, $user, $pwd);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die("connection failed: " . $e->getMessage());
        }
    }
}