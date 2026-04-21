<?php

namespace app\internal\models;

class User {
    public int $id;
    public string $username;
    public string $email;
    public string $password_hash;
    public string $created_at;
}