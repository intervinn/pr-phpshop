<?php
namespace app\internal\db;

use app\internal\models\Order;
use PDO;

class OrderRepository {
    private \PDO $pdo;

    function __construct($pdo) {
        $this->pdo = $pdo;
    }

    function create(int $buyerId, int $itemId): void {
        $stmt = $this->pdo->prepare("INSERT INTO orders (buyer_id, item_id) VALUES (?, ?)");
        $stmt->execute([$buyerId, $itemId]);
    }

    function getByBuyer(int $buyerId): array {
        $stmt = $this->pdo->prepare("
            SELECT
                o.id,
                o.buyer_id,
                o.item_id,
                o.status,
                o.created_at,
                i.name AS item_name,
                i.price AS item_price,
                u.username AS vendor_name
            FROM orders o
            JOIN items i ON i.id = o.item_id
            JOIN users u ON u.id = i.vendor
            WHERE o.buyer_id = ?
            ORDER BY o.created_at DESC
        ");
        $stmt->execute([$buyerId]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, Order::class);
    }
}
