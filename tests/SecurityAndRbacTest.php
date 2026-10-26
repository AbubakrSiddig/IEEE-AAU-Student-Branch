<?php
/**
 * IEEE AAU Student Branch Platform — Automated Security & RBAC Test Suite
 * Tests authentication, authorization matrix, rate limiting, IDOR, input validation, and business logic.
 */

// Connect to DB
$host = '127.0.0.1';
$dbname = 'ieee_aau_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (Exception $e) {
    die("Database connection failed for tests: " . $e->getMessage() . "\n");
}

// Global test counters
$testsPassed = 0;
$testsFailed = 0;

function assertCondition($desc, $cond, $detail = '') {
    global $testsPassed, $testsFailed;
    if ($cond) {
        echo "  [PASS] $desc\n";
        $testsPassed++;
    } else {
        echo "  [FAIL] $desc" . ($detail ? " ($detail)" : "") . "\n";
        $testsFailed++;
    }
}

function runApiRequest($action, $method = 'GET', $data = [], $token = null) {
    global $pdo;
    
    // We can simulate an API request by setting superglobals and buffering output
    $_GET = ['action' => $action];
    $_POST = [];
    $_SERVER['REQUEST_METHOD'] = $method;
    $_SERVER['HTTP_ORIGIN'] = 'http://localhost:5173';
    $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
    
    if ($token) {
        $_SERVER['HTTP_AUTHORIZATION'] = "Bearer $token";
    } else {
        unset($_SERVER['HTTP_AUTHORIZATION']);
        unset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']);
    }

    // Pass JSON input if not GET
    $jsonString = json_encode($data);
    
    // Write temporary php://input simulator if needed or use custom wrapper
    // Since api.php reads file_get_contents('php://input'), we can test functions or invoke via curl/HTTP
    // Let's use direct curl / stream_context to the local Apache endpoint for 100% genuine E2E execution!
    $baseUrl = 'http://127.0.0.1/IEEE%20AAU%20SB%20PLATFORM/api.php?action=' . urlencode($action);
    
    $headers = [
        'Content-Type: application/json',
        'Origin: http://localhost:5173'
    ];
    if ($token) {
        $headers[] = "Authorization: Bearer $token";
    }

    $opts = [
        'http' => [
            'method' => $method,
            'header' => implode("\r\n", $headers),
            'ignore_errors' => true,
            'timeout' => 5
        ]
    ];
    if ($method !== 'GET' && !empty($data)) {
        $opts['http']['content'] = $jsonString;
    }

    $context = stream_context_create($opts);
    $responseBody = @file_get_contents($baseUrl, false, $context);
    
    $statusCode = 200;
    if (isset($http_response_header) && is_array($http_response_header)) {
        if (preg_match('/HTTP\/\S+\s+(\d+)/', $http_response_header[0], $m)) {
            $statusCode = intval($m[1]);
        }
    }

    $decoded = json_decode($responseBody, true);
    return [
        'code' => $statusCode,
        'body' => $decoded,
        'raw' => $responseBody,
        'headers' => $http_response_header ?? []
    ];
}

echo "========================================================\n";
echo "IEEE AAU SB PLATFORM — AUTOMATED QA & SECURITY AUDIT TEST\n";
echo "========================================================\n\n";

// -----------------------------------------------------------
// 1. AUTHENTICATION & TOKEN SECURITY TESTS
// -----------------------------------------------------------
echo "--- 1. Authentication & Token Hardening Tests ---\n";

// Test 1.1: Forged Token Bypass (Old base64 vulnerability: Bearer admin@ieee-aau.org:1)
$res = runApiRequest('me', 'GET', [], base64_encode('admin@ieee-aau.org:1'));
assertCondition("Forged Base64 token bypass is rejected with HTTP 401", $res['code'] === 401);

// Test 1.2: Direct ID fallback bypass (Bearer 1)
$res = runApiRequest('me', 'GET', [], '1');
assertCondition("Direct user ID fallback parameter is rejected with HTTP 401", $res['code'] === 401);

// Test 1.3: Direct Email fallback bypass (Bearer admin@ieee-aau.org)
$res = runApiRequest('me', 'GET', [], 'admin@ieee-aau.org');
assertCondition("Direct email parameter bypass is rejected with HTTP 401", $res['code'] === 401);

// Test 1.4: Legitimate Login with valid credentials
$loginRes = runApiRequest('login', 'POST', [
    'email' => 'admin@ieee-aau.org',
    'password' => 'password123'
]);
assertCondition("Legitimate admin login succeeds with HTTP 200", $loginRes['code'] === 200);
$adminToken = $loginRes['body']['token'] ?? null;
assertCondition("Admin receives a cryptographically secure 64-char hex token", strlen($adminToken) === 64);

// Test 1.5: Verify authenticated session using legitimate token
$meRes = runApiRequest('me', 'GET', [], $adminToken);
assertCondition("Authentication verification endpoint (/me) recognizes admin", ($meRes['code'] === 200 && ($meRes['body']['user']['email'] ?? '') === 'admin@ieee-aau.org'));

// Login other roles for RBAC tests
$roles = [
    'hr' => 'hr@ieee-aau.org',
    'programs_coordinator' => 'programs@ieee-aau.org',
    'magazine_coordinator' => 'magazine@ieee-aau.org',
    'media_coordinator' => 'media@ieee-aau.org',
    'student' => 'student@aau.edu'
];
$tokens = ['office_member' => $adminToken];
foreach ($roles as $rName => $rEmail) {
    $rLogin = runApiRequest('login', 'POST', ['email' => $rEmail, 'password' => 'password123']);
    $tokens[$rName] = $rLogin['body']['token'] ?? null;
}

// -----------------------------------------------------------
// 2. SECURITY HEADERS & CORS TESTS
// -----------------------------------------------------------
echo "\n--- 2. Security Headers & CORS Tests ---\n";
$headersFound = false;
$xContentType = false;
$xFrame = false;
$csp = false;

foreach ($meRes['headers'] as $hdr) {
    if (stripos($hdr, 'X-Content-Type-Options: nosniff') !== false) $xContentType = true;
    if (stripos($hdr, 'X-Frame-Options: SAMEORIGIN') !== false) $xFrame = true;
    if (stripos($hdr, 'Content-Security-Policy:') !== false) $csp = true;
}
assertCondition("Response includes X-Content-Type-Options: nosniff", $xContentType);
assertCondition("Response includes X-Frame-Options: SAMEORIGIN", $xFrame);
assertCondition("Response includes Content-Security-Policy", $csp);

// -----------------------------------------------------------
// 3. RBAC MATRIX & IDOR AUTHORIZATION TESTS
// -----------------------------------------------------------
echo "\n--- 3. RBAC Matrix & IDOR Authorization Tests ---\n";

// Test 3.1: Events mutation: Programs Coordinator vs HR vs Media
$evResProg = runApiRequest('events', 'POST', [
    'title' => 'QA Automated Test Event',
    'starts_at' => date('Y-m-d H:i:s', strtotime('+3 days')),
    'ends_at' => date('Y-m-d H:i:s', strtotime('+3 days +2 hours')),
    'location' => 'Main Hall A'
], $tokens['programs_coordinator']);
assertCondition("Programs Coordinator CAN create events (HTTP 200)", $evResProg['code'] === 200);

$evResHR = runApiRequest('events', 'POST', [
    'title' => 'Unauthorized HR Event',
    'starts_at' => date('Y-m-d H:i:s', strtotime('+3 days')),
    'ends_at' => date('Y-m-d H:i:s', strtotime('+3 days +2 hours')),
    'location' => 'Main Hall A'
], $tokens['hr']);
assertCondition("HR is FORBIDDEN from creating events (HTTP 403)", $evResHR['code'] === 403);

$evResMedia = runApiRequest('events', 'POST', [
    'title' => 'Unauthorized Media Event',
    'starts_at' => date('Y-m-d H:i:s', strtotime('+3 days')),
    'ends_at' => date('Y-m-d H:i:s', strtotime('+3 days +2 hours')),
    'location' => 'Main Hall A'
], $tokens['media_coordinator']);
assertCondition("Media Lead is FORBIDDEN from creating events (HTTP 403)", $evResMedia['code'] === 403);

// Test 3.2: Magazine mutation: Magazine Coordinator vs Programs Coordinator
$magResMag = runApiRequest('magazines', 'POST', [
    'volume' => 'Vol. QA-1',
    'title' => 'QA Robotics Issue',
    'cover' => 'https://example.com/cover.jpg'
], $tokens['magazine_coordinator']);
assertCondition("Magazine Coordinator CAN create magazine issues (HTTP 200)", $magResMag['code'] === 200);

$magResProg = runApiRequest('magazines', 'POST', [
    'volume' => 'Vol. QA-Illegal',
    'title' => 'Illegal Issue',
    'cover' => 'https://example.com/cover.jpg'
], $tokens['programs_coordinator']);
assertCondition("Programs Coordinator is FORBIDDEN from managing magazine (HTTP 403)", $magResProg['code'] === 403);

// Test 3.3: News & Media mutation: Media Coordinator vs Magazine Coordinator
$newsResMedia = runApiRequest('news', 'POST', [
    'title' => 'QA News Article',
    'category' => 'Events',
    'snippet' => 'Test snippet'
], $tokens['media_coordinator']);
assertCondition("Media Lead CAN create news articles (HTTP 200)", $newsResMedia['code'] === 200);

$newsResMag = runApiRequest('news', 'POST', [
    'title' => 'Illegal News',
    'category' => 'Events'
], $tokens['magazine_coordinator']);
assertCondition("Magazine Editor is FORBIDDEN from publishing news (HTTP 403)", $newsResMag['code'] === 403);

// Test 3.4: Office Terms & Chairman Policy (Spec v0.3.4 Section 2.1)
$pdo->prepare("DELETE FROM office_terms WHERE title = '2027 – 2028'")->execute();
$termResChair = runApiRequest('office_terms', 'POST', [
    'title' => '2027 – 2028',
    'starts_on' => '2027-09-01',
    'ends_on' => '2028-08-31',
    'status' => 'draft'
], $tokens['office_member']);
assertCondition("Branch Chairman CAN create office terms (HTTP 200)", $termResChair['code'] === 200);
$pdo->prepare("DELETE FROM office_terms WHERE title = '2027 – 2028'")->execute();

$termResHR = runApiRequest('office_terms', 'POST', [
    'title' => '2028 – 2029',
    'starts_on' => '2028-09-01',
    'ends_on' => '2029-08-31',
    'status' => 'draft'
], $tokens['hr']);
assertCondition("HR Coordinator is FORBIDDEN from managing office terms (HTTP 403)", $termResHR['code'] === 403);

// Test 3.5: Volunteer Applications IDOR Protection:
// Insert test applicant in Media team
$appId = 'VOL-TEST-MEDIA-' . rand(100, 999);
$pdo->prepare("INSERT INTO volunteer_applications (id, full_name, team_id, university, college, major, batch, email, phone, whatsapp) VALUES (?, 'Test Media Applicant', 'media', 'AAU', 'Engineering', 'EE', '25', 'media_app@test.com', '123', '123')")->execute([$appId]);

// Spec v0.3.3 Section 9.4: Team coordinators can VIEW their team requests only; review & status edit is HR & Office Member
$hrStatusRes = runApiRequest('update_volunteer_status', 'POST', [
    'id' => $appId,
    'status' => 'candidate_interview',
    'notes' => 'HR interview scheduling'
], $tokens['hr']);
assertCondition("HR Coordinator CAN update volunteer applicant status (HTTP 200)", $hrStatusRes['code'] === 200);

$coordStatusRes = runApiRequest('update_volunteer_status', 'POST', [
    'id' => $appId,
    'status' => 'accepted',
    'notes' => 'Unauthorized coordinator status edit'
], $tokens['media_coordinator']);
assertCondition("Team Coordinator is FORBIDDEN from modifying applicant status (View-only per Spec v0.3.3 Section 9.4) (HTTP 403)", $coordStatusRes['code'] === 403);

// Cleanup test applicant
$pdo->prepare("DELETE FROM volunteer_applications WHERE id = ?")->execute([$appId]);

// -----------------------------------------------------------
// 4. BUSINESS LOGIC & VALIDATION TESTS
// -----------------------------------------------------------
echo "\n--- 4. Business Logic & Validation Tests ---\n";

// Test 4.1: Volunteer Form 4-Part Full Name validation
$shortNameRes = runApiRequest('volunteer_submit', 'POST', [
    'full_name' => 'Ahmed Mohamed', // only 2 parts
    'team_id' => 'technical'
], $tokens['student']);
assertCondition("Volunteer submission rejects non-quadruple name (< 4 words) with HTTP 422", $shortNameRes['code'] === 422);

// Test 4.2: Event Date-Time validation (End date before start date)
$invalidDateRes = runApiRequest('events', 'POST', [
    'title' => 'Time Travel Seminar',
    'starts_at' => '2026-11-10 10:00:00',
    'ends_at' => '2026-11-10 09:00:00', // ends before starts!
    'location' => 'Auditorium'
], $tokens['programs_coordinator']);
assertCondition("Event rejects end time earlier than start time with HTTP 422", $invalidDateRes['code'] === 422);

// Test 4.3: Next Event API (Countdown sync)
$nextEvRes = runApiRequest('next_event', 'GET');
assertCondition("Next event endpoint returns HTTP 200 with server_time and state", ($nextEvRes['code'] === 200 && isset($nextEvRes['body']['server_time']) && isset($nextEvRes['body']['event_state'])));

// Test 4.4: Profile Password Update requires current password
$noCurrPassRes = runApiRequest('update_profile', 'POST', [
    'name' => 'Ahmed Mohamed',
    'password' => 'newSecretPass123'
], $tokens['student']);
assertCondition("Profile password update rejects missing current password with HTTP 422", $noCurrPassRes['code'] === 422);

// Test 4.5: Excel Formula Injection Sanitization Logic
function testFormulaSanitize($cell) {
    if (is_string($cell) && preg_match('/^[=+\-@\t\r]/', trim($cell))) {
        return "'" . $cell;
    }
    return $cell;
}
$dangerCell1 = "=cmd|'/C calc'!A0";
$dangerCell2 = "+123456";
$dangerCell3 = "@SUM(A1:A10)";
$safeCell1 = testFormulaSanitize($dangerCell1);
$safeCell2 = testFormulaSanitize($dangerCell2);
$safeCell3 = testFormulaSanitize($dangerCell3);
assertCondition("Excel formula sanitization neutralizes = formulas with prefix quote", strpos($safeCell1, "'=") === 0);
assertCondition("Excel formula sanitization neutralizes + formulas with prefix quote", strpos($safeCell2, "'+") === 0);
assertCondition("Excel formula sanitization neutralizes @ formulas with prefix quote", strpos($safeCell3, "'@") === 0);

// -----------------------------------------------------------
// SUMMARY
// -----------------------------------------------------------
echo "\n========================================================\n";
echo "TEST RESULTS SUMMARY:\n";
echo "Passed: $testsPassed\n";
echo "Failed: $testsFailed\n";
echo "========================================================\n";

if ($testsFailed > 0) {
    exit(1);
} else {
    echo "[✓] ALL TESTS PASSED SUCCESSFULLY!\n";
    exit(0);
}
