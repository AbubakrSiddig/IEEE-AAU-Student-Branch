<?php
$pdo = new PDO("mysql:host=127.0.0.1;dbname=ieee_aau_db;charset=utf8mb4", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

print_r($pdo->query("SELECT * FROM rate_limits")->fetchAll());
