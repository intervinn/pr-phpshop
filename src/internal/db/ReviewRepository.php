<?php
namespace app\internal\db;

use app\internal\models\Review;
use PDO;

class ReviewRepository {
    private \PDO $pdo;

    function __construct($pdo) {
        $this->pdo = $pdo;
    }

    function create(int $itemId, int $userId, int $rating, string $comment): void {
        $stmt = $this->pdo->prepare("
            INSERT INTO reviews (item_id, user_id, rating, comment)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$itemId, $userId, $rating, $comment]);
    }

    function getByItem(int $itemId): array {
        $stmt = $this->pdo->prepare("
            SELECT
                r.id,
                r.item_id,
                r.user_id,
                r.rating,
                r.comment,
                r.created_at,
                u.username
            FROM reviews r
            JOIN users u ON u.id = r.user_id
            WHERE r.item_id = ?
            ORDER BY r.created_at DESC
        ");
        $stmt->execute([$itemId]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, Review::class);
    }
}
