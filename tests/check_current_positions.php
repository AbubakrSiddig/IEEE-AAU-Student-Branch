<?php
$pdo = new PDO("mysql:host=127.0.0.1;dbname=ieee_aau_db;charset=utf8mb4", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$stmt = $pdo->query("SELECT status, id, title FROM office_terms");
print_r($stmt->fetchAll());

$currentTerm = $pdo->query("SELECT * FROM office_terms WHERE status = 'current'")->fetch();
echo "CURRENT TERM ID: " . ($currentTerm['id'] ?? 'NONE') . "\n";

$positions = $pdo->query("SELECT tp.id, tp.term_id, tp.position_group, tp.position_key, tm.name_ar, tm.name_en 
                          FROM term_positions tp 
                          JOIN term_members tm ON tp.member_id = tm.id 
                          WHERE tp.term_id = " . ($currentTerm['id'] ?? 0))->fetchAll();
echo "COUNT POSITIONS IN CURRENT TERM: " . count($positions) . "\n";
print_r($positions);
