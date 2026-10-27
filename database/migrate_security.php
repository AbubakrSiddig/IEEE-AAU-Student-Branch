<?php
/**
 * Security Database Migration:
 * - user_tokens: Secure cryptographic database-backed bearer tokens
 * - rate_limits: IP/Action based rate limiting table
 * - recruitment_campaigns: Ensure default active campaign with deadline
 */

$host = '127.0.0.1';
$dbname = 'ieee_aau_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // 1. User Tokens table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `user_tokens` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NOT NULL,
            `token_hash` VARCHAR(64) NOT NULL UNIQUE,
            `name` VARCHAR(100) DEFAULT 'web_session',
            `last_used_at` DATETIME NULL,
            `expires_at` DATETIME NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX (`user_id`),
            INDEX (`token_hash`),
            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "[+] Table `user_tokens` verified/created.\n";

    // 2. Rate Limits table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `rate_limits` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `ip_address` VARCHAR(45) NOT NULL,
            `action_key` VARCHAR(50) NOT NULL,
            `attempts` INT NOT NULL DEFAULT 1,
            `last_attempt` DATETIME NOT NULL,
            UNIQUE KEY `ip_action_idx` (`ip_address`, `action_key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
    echo "[+] Table `rate_limits` verified/created.\n";

    // 3. Ensure recruitment_campaigns has active campaign for current season
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM recruitment_campaigns WHERE status = 'active'");
    if ($stmt->fetch()['count'] == 0) {
        $ins = $pdo->prepare("INSERT INTO recruitment_campaigns (title, opens_at, closes_at, status, terms_version) VALUES (?, ?, ?, 'active', 'v2.0')");
        $ins->execute([
            'حملة التطوع السنوية 2026/2027',
            date('Y-m-d 00:00:00', strtotime('-7 days')),
            date('Y-m-d 23:59:59', strtotime('+30 days'))
        ]);
        echo "[+] Seeded active recruitment campaign.\n";
    }

    echo "[✓] Security migration successfully completed.\n";

} catch (Exception $e) {
    echo "[-] Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
