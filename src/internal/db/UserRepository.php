<?php
namespace app\internal\db;

use app\internal\models\User;
use PDO;

class UserRepository {
    private \PDO $pdo;

    function __construct($pdo) {
        $this->pdo = $pdo;
    }

    function getByEmail($email): ?User {
        $stmt = $this->pdo->prepare("SELECT id, username, email, password_hash, created_at FROM users WHERE email = ?");
        $stmt->setFetchMode(PDO::FETCH_CLASS, User::class);
        $stmt->execute([$email]);
        $res = $stmt->fetch();
        if ($res == false) {
            return null;
        }
        return $res;
    }

    function getById($id): ?User {
        $stmt = $this->pdo->prepare("SELECT id, username, email, password_hash, created_at FROM users WHERE id = ?");
        $stmt->setFetchMode(PDO::FETCH_CLASS, User::class);
        $stmt->execute([$id]);
        $res = $stmt->fetch();
        if ($res == false) {
            return null;
        }
        return $res;
    }

    function save(User $u): void {
        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->execute([$u->username, $u->email, $u->password_hash]);
    }

    function updatePassword(int $id, string $passwordHash): void {
        $stmt = $this->pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        $stmt->execute([$passwordHash, $id]);
    }

    function updateCart(int $id, array $cart): void {
        $stmt = $this->pdo->prepare("UPDATE users SET cart = ? WHERE id = ?");
        $stmt->execute([json_encode($cart), $id]);
    }

    function getCart(int $id): array {
        $stmt = $this->pdo->prepare("SELECT cart FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $col = $stmt->fetchColumn();
        $ids = json_decode($col, true);

        $objs = array_map(function ($id) {
            $items = Database::getItems();
            return $items->getById($id);
        }, $ids);

        return $objs;
    }

    function getCartIds(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT cart FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $col = $stmt->fetchColumn();
        if ($col == null) {
            return null;
        }
        
        $ids = json_decode($col, true);

        return $ids;
    }
}
