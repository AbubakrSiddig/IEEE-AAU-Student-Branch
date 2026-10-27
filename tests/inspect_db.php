<?php
$pdo = new PDO("mysql:host=127.0.0.1;dbname=ieee_aau_db;charset=utf8mb4", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

echo "=== USERS ===\n";
$stmt = $pdo->query("SELECT id, name, email, role, is_active FROM users");
foreach ($stmt->fetchAll() as $row) {
    echo "ID: {$row['id']} | Email: {$row['email']} | Role: {$row['role']} | Active: {$row['is_active']} | Name: {$row['name']}\n";
}

echo "\n=== TABLES ===\n";
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
print_r($tables);
