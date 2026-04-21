<?php
namespace app\internal\db;

use app\internal\models\Item;
use PDO;

class ItemRepository {
    private \PDO $pdo;

    function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    function getById($id): ?Item {
        $stmt = $this->pdo->prepare("
            SELECT
                i.id,
                i.vendor,
                i.price,
                i.name,
                i.description,
                i.created_at,
                i.active,
                u.username AS vendor_name,
                AVG(r.rating) AS average_rating,
                COUNT(r.id) AS review_count
            FROM items i
            JOIN users u ON u.id = i.vendor
            LEFT JOIN reviews r ON r.item_id = i.id
            WHERE i.id = ?
            GROUP BY i.id, i.vendor, i.price, i.name, i.description, i.created_at, i.active, u.username
        ");
        $stmt->setFetchMode(PDO::FETCH_CLASS, Item::class);
        $stmt->execute([$id]);
        $res = $stmt->fetch();
        if ($res == false) {
            return null;
        }
        return $res;
    }

    function getByVendorId($id): ?Item {
        $stmt = $this->pdo->prepare("
            SELECT id, vendor, price, name, description, created_at, active
            FROM items
            WHERE vendor = ?
            ORDER BY created_at DESC
            LIMIT 1
        ");
        $stmt->setFetchMode(PDO::FETCH_CLASS, Item::class);
        $stmt->execute([$id]);
        $res = $stmt->fetch();
        if ($res == false) {
            return null;
        }
        return $res;
    }

    function getAll(int $page = 0, int $limit = 10): array {
        $offset = $page * $limit;
        $stmt = $this->pdo->prepare("
            SELECT
                i.id,
                i.vendor,
                i.price,
                i.name,
                i.description,
                i.created_at,
                i.active,
                u.username AS vendor_name,
                AVG(r.rating) AS average_rating,
                COUNT(r.id) AS review_count
            FROM items i
            JOIN users u ON u.id = i.vendor
            LEFT JOIN reviews r ON r.item_id = i.id
            GROUP BY i.id, i.vendor, i.price, i.name, i.description, i.created_at, i.active, u.username
            ORDER BY i.created_at DESC
            LIMIT $limit OFFSET $offset
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, Item::class);
    }

    function getActive(int $page = 0, int $limit = 10): array {
        $offset = $page * $limit;
        $stmt = $this->pdo->prepare("
            SELECT
                i.id,
                i.vendor,
                i.price,
                i.name,
                i.description,
                i.created_at,
                i.active,
                u.username AS vendor_name,
                AVG(r.rating) AS average_rating,
                COUNT(r.id) AS review_count
            FROM items i
            JOIN users u ON u.id = i.vendor
            LEFT JOIN reviews r ON r.item_id = i.id
            WHERE i.active = 1
            GROUP BY i.id, i.vendor, i.price, i.name, i.description, i.created_at, i.active, u.username
            ORDER BY i.created_at DESC
            LIMIT $limit OFFSET $offset
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, Item::class);
    }

    function searchActive(string $query, int $page = 0, int $limit = 10): array {
        $offset = $page * $limit;
        $pattern = "%" . $query . "%";
        $stmt = $this->pdo->prepare("
            SELECT
                i.id,
                i.vendor,
                i.price,
                i.name,
                i.description,
                i.created_at,
                i.active,
                u.username AS vendor_name,
                AVG(r.rating) AS average_rating,
                COUNT(r.id) AS review_count
            FROM items i
            JOIN users u ON u.id = i.vendor
            LEFT JOIN reviews r ON r.item_id = i.id
            WHERE i.active = 1 AND (i.name LIKE ? OR i.description LIKE ?)
            GROUP BY i.id, i.vendor, i.price, i.name, i.description, i.created_at, i.active, u.username
            ORDER BY i.created_at DESC
            LIMIT $limit OFFSET $offset
        ");
        $stmt->execute([$pattern, $pattern]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, Item::class);
    }

    function getByVendor(int $vendorId): array {
        $stmt = $this->pdo->prepare("
            SELECT id, vendor, price, name, description, created_at, active
            FROM items
            WHERE vendor = ?
            ORDER BY created_at DESC
        ");
        $stmt->execute([$vendorId]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, Item::class);
    }

    function create(Item $item): int {
        $stmt = $this->pdo->prepare("
            INSERT INTO items (vendor, price, name, description, active)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $item->vendor,
            $item->price,
            $item->name,
            $item->description,
            $item->active ? 1 : 0,
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    function update(Item $item): void {
        $stmt = $this->pdo->prepare("
            UPDATE items
            SET price = ?, name = ?, description = ?, active = ?
            WHERE id = ? AND vendor = ?
        ");
        $stmt->execute([
            $item->price,
            $item->name,
            $item->description,
            $item->active ? 1 : 0,
            $item->id,
            $item->vendor,
        ]);
    }

    function setVisibility(int $id, int $vendorId, bool $active): void {
        $stmt = $this->pdo->prepare("UPDATE items SET active = ? WHERE id = ? AND vendor = ?");
        $stmt->execute([$active ? 1 : 0, $id, $vendorId]);
    }
}
