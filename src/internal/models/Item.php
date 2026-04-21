<?php

namespace app\internal\models;

class Item {
    public int $id;
    public string $name;
    public string $description;
    public int $vendor;
    public bool $active;
    public float $price;
    public string $created_at;
    public ?string $vendor_name = null;
    public ?float $average_rating = null;
    public ?int $review_count = null;
}
