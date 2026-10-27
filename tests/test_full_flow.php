<?php
/**
 * Test full flow simulating user actions:
 * 1. current_term (fetch office members & coordinators)
 * 2. upload a test image
 * 3. create an event
 * 4. create a magazine
 * 5. update office terms
 */

function testGet($url) {
    $res = @file_get_contents($url);
    return json_decode($res, true);
}

function testPost($url, $data = [], $token = null) {
    $opts = [
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\n" . ($token ? "Authorization: Bearer $token\r\n" : ""),
            'content' => json_encode($data),
            'ignore_errors' => true
        ]
    ];
    $res = @file_get_contents($url, false, stream_context_create($opts));
    return json_decode($res, true);
}

$baseUrl = 'http://127.0.0.1/IEEE%20AAU%20SB%20PLATFORM/api.php';

echo "1. Testing current_term:\n";
$term = testGet("$baseUrl?action=current_term");
echo "Status: " . ($term['status'] ?? 'err') . "\n";
echo "Office Members count: " . count($term['data']['office_members'] ?? []) . "\n";
echo "Coordinators count: " . count($term['data']['coordinators'] ?? []) . "\n";

echo "\n2. Testing login:\n";
$login = testPost("$baseUrl?action=login", ['email' => 'admin@ieee-aau.org', 'password' => 'password123']);
echo "Login status: " . ($login['status'] ?? 'err') . "\n";
$token = $login['token'] ?? null;
echo "Token: $token\n";

echo "\n3. Testing event creation:\n";
$ev = testPost("$baseUrl?action=events&token=$token", [
    'title' => 'Test Live Flow Event',
    'starts_at' => '2026-11-20 10:00:00',
    'ends_at' => '2026-11-20 12:00:00',
    'location' => 'Main Hall'
], $token);
echo "Event creation: " . json_encode($ev) . "\n";

echo "\n4. Testing magazine creation:\n";
$mag = testPost("$baseUrl?action=magazines&token=$token", [
    'volume' => 'Vol. 13 Issue 1',
    'title' => 'Flow Test Magazine',
    'cover' => 'https://example.com/cover.jpg'
], $token);
echo "Magazine creation: " . json_encode($mag) . "\n";
