<?php

namespace app\internal\models;

class Review {
    public int $id;
    public int $item_id;
    public int $user_id;
    public int $rating;
    public ?string $comment = null;
    public string $created_at;
    public ?string $username = null;
}
