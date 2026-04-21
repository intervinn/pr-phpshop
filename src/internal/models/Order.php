<?php

namespace app\internal\models;

class Order {
    public int $id;
    public int $buyer_id;
    public int $item_id;
    public string $status;
    public string $created_at;
    public ?string $item_name = null;
    public ?float $item_price = null;
    public ?string $vendor_name = null;
}
