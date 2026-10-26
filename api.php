<?php
/**
 * IEEE AAU Student Branch Platform — Core REST API Backend
 * Connects to MySQL (ieee_aau_db) with full RBAC, Event Management & Volunteer Form v0.3.3
 */

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=utf-8');

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

date_default_timezone_set('Africa/Khartoum');

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
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Request Helper
function getJsonInput() {
    return json_decode(file_get_contents('php://input'), true) ?: [];
}

// Simple Session / Bearer Token User Auth Simulator
function getCurrentUser($pdo) {
    $token = '';

    // 1. Case-insensitive header check
    $headers = function_exists('getallheaders') ? getallheaders() : [];
    foreach ($headers as $k => $v) {
        if (strtolower($k) === 'authorization') {
            if (preg_match('/Bearer\s+(\S+)/i', $v, $matches)) {
                $token = $matches[1];
            }
            break;
        }
    }

    // 2. Apache Environment variables check
    if (!$token) {
        $authEnv = $_SERVER['HTTP_AUTHORIZATION'] ?? ($_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '');
        if (preg_match('/Bearer\s+(\S+)/i', $authEnv, $matches)) {
            $token = $matches[1];
        }
    }

    // 3. Query string or body fallback
    if (!$token) {
        $token = $_GET['token'] ?? ($_POST['token'] ?? '');
    }

    if ($token) {
        $decoded = base64_decode($token, true);
        if ($decoded && strpos($decoded, ':') !== false) {
            list($email, $id) = explode(':', $decoded, 2);
            $stmt = $pdo->prepare("SELECT id, name, email, role, phone, is_active FROM users WHERE (id = ? AND email = ?) OR email = ?");
            $stmt->execute([$id, $email, $email]);
            $u = $stmt->fetch();
            if ($u) return $u;
        }

        // Direct ID or Email fallback
        $stmt = $pdo->prepare("SELECT id, name, email, role, phone, is_active FROM users WHERE id = ? OR email = ?");
        $stmt->execute([$token, $token]);
        $u = $stmt->fetch();
        if ($u) return $u;
    }

    return null;
}

// Permission Matrix enforcement according to Change Requests v0.3.1 Section 5.2
function checkPermission($user, $module, $action = 'view') {
    if (!$user) return false;
    $role = $user['role'];

    // Office Member (Super Admin) has full access to everything
    if ($role === 'office_member') return true;

    // Module specific permissions:
    $matrix = [
        'dashboard' => ['office_member' => true, 'hr' => true, 'programs_coordinator' => true, 'magazine_coordinator' => true, 'media_coordinator' => true],
        'applications' => ['office_member' => true, 'hr' => true],
        'members' => ['office_member' => true, 'hr' => true, 'programs_coordinator' => ($action === 'view')],
        'volunteers' => ['office_member' => true, 'hr' => true, 'programs_coordinator' => ($action === 'view')],
        'events' => ['office_member' => true, 'programs_coordinator' => true, 'hr' => ($action === 'view')],
        'magazine' => ['office_member' => true, 'magazine_coordinator' => true],
        'news' => ['office_member' => true, 'media_coordinator' => true],
        'articles' => ['office_member' => true, 'media_coordinator' => true],
        'reports' => ['office_member' => true, 'hr' => true, 'programs_coordinator' => ($action === 'events_only')],
        'terms' => ['office_member' => true, 'hr' => ($action === 'view')],
        'office_terms' => ['office_member' => true, 'chairman' => true],
        'users_management' => ['office_member' => true],
        'settings' => ['office_member' => true]
    ];

    if (isset($matrix[$module][$role])) {
        return (bool)$matrix[$module][$role];
    }
    return false;
}

// Chairman Policy Check (Spec v0.3.4 Section 2.1)
function isChairman($user) {
    if (!$user) return false;
    if ($user['role'] === 'chairman') return true;
    if ($user['role'] === 'office_member' && strtolower($user['email']) === 'admin@ieee-aau.org') return true;
    return false;
}

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {

    // ==========================================
    // 🔴 1. NEXT UPCOMING EVENT (CRITICAL FIX 6 & 7)
    // ==========================================
    case 'next_event':
        $now = date('Y-m-d H:i:s');
        
        // 1. Check if there is an event currently LIVE (now between starts_at and ends_at)
        $stmt = $pdo->prepare("SELECT * FROM events WHERE status != 'cancelled' AND starts_at <= ? AND ends_at >= ? ORDER BY starts_at ASC LIMIT 1");
        $stmt->execute([$now, $now]);
        $liveEvent = $stmt->fetch();

        if ($liveEvent) {
            echo json_encode([
                'status' => 'success',
                'event_state' => 'live',
                'server_time' => $now,
                'timezone' => 'Africa/Khartoum',
                'event' => $liveEvent
            ]);
            break;
        }

        // 2. Otherwise find the nearest upcoming event where starts_at > now
        $stmt = $pdo->prepare("SELECT * FROM events WHERE status != 'cancelled' AND starts_at > ? ORDER BY is_featured DESC, starts_at ASC LIMIT 1");
        $stmt->execute([$now]);
        $nextEvent = $stmt->fetch();

        if ($nextEvent) {
            echo json_encode([
                'status' => 'success',
                'event_state' => 'upcoming',
                'server_time' => $now,
                'timezone' => 'Africa/Khartoum',
                'event' => $nextEvent
            ]);
        } else {
            echo json_encode([
                'status' => 'success',
                'event_state' => 'none',
                'server_time' => $now,
                'timezone' => 'Africa/Khartoum',
                'message' => 'ترقبوا فعاليتنا القادمة',
                'message_en' => 'Stay tuned for our upcoming event',
                'event' => null
            ]);
        }
        break;

    // ==========================================
    // 🔴 2. EVENTS CRUD (WITH REAL DATETIME PICKER)
    // ==========================================
    case 'events':
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            $stmt = $pdo->query("SELECT * FROM events ORDER BY starts_at ASC");
            $events = $stmt->fetchAll();
            echo json_encode(['status' => 'success', 'data' => $events]);
            break;
        }

        // Mutation requires permissions (Office Member or Programs Coordinator)
        $currentUser = getCurrentUser($pdo);
        if (!checkPermission($currentUser, 'events', 'edit')) {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'غير مصرح لك بإدارة الفعاليات (صلاحية منسق البرامج / أعضاء المكتب مطلوبة)']);
            break;
        }

        $input = getJsonInput();

        if ($method === 'POST') {
            // Validation: starts_at and ends_at must be valid Date-Times
            $title = trim($input['title'] ?? '');
            $startsAt = $input['starts_at'] ?? '';
            $endsAt = $input['ends_at'] ?? '';
            $location = trim($input['location'] ?? '');

            if (!$title || !$startsAt || !$endsAt || !$location) {
                http_response_code(422);
                echo json_encode(['status' => 'error', 'message' => 'جميع الحقول الأساسية وتواريخ البداية والنهاية إلزامية']);
                break;
            }

            if (strtotime($endsAt) <= strtotime($startsAt)) {
                http_response_code(422);
                echo json_encode(['status' => 'error', 'message' => 'تاريخ ووقت النهاية يجب أن يكون بعد تاريخ البداية']);
                break;
            }

            $id = 'EV-' . date('ymd') . '-' . rand(100, 999);
            $stmt = $pdo->prepare("INSERT INTO events (id, title, description, starts_at, ends_at, registration_deadline, location, event_type, meeting_link, speakers, seats, status, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $id,
                $title,
                $input['description'] ?? '',
                $startsAt,
                $endsAt,
                !empty($input['registration_deadline']) ? $input['registration_deadline'] : null,
                $location,
                $input['event_type'] ?? 'in_person',
                $input['meeting_link'] ?? null,
                $input['speakers'] ?? '',
                intval($input['seats'] ?? 100),
                $input['status'] ?? 'upcoming',
                intval($input['is_featured'] ?? 0)
            ]);

            // Audit Log
            $pdo->prepare("INSERT INTO audit_logs (user_name, action, details) VALUES (?, ?, ?)")
                ->execute([$currentUser['name'], 'Create Event', "Created event $title ($id)"]);

            echo json_encode(['status' => 'success', 'message' => 'تم إنشاء الفعالية بنجاح', 'id' => $id]);
        } elseif ($method === 'PUT') {
            $id = $_GET['id'] ?? ($input['id'] ?? null);
            if (!$id) {
                http_response_code(422);
                echo json_encode(['status' => 'error', 'message' => 'Event ID required']);
                break;
            }

            $startsAt = $input['starts_at'] ?? '';
            $endsAt = $input['ends_at'] ?? '';
            if (strtotime($endsAt) <= strtotime($startsAt)) {
                http_response_code(422);
                echo json_encode(['status' => 'error', 'message' => 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية']);
                break;
            }

            $stmt = $pdo->prepare("UPDATE events SET title = ?, description = ?, starts_at = ?, ends_at = ?, registration_deadline = ?, location = ?, event_type = ?, meeting_link = ?, speakers = ?, seats = ?, status = ?, is_featured = ? WHERE id = ?");
            $stmt->execute([
                $input['title'],
                $input['description'] ?? '',
                $startsAt,
                $endsAt,
                !empty($input['registration_deadline']) ? $input['registration_deadline'] : null,
                $input['location'],
                $input['event_type'] ?? 'in_person',
                $input['meeting_link'] ?? null,
                $input['speakers'] ?? '',
                intval($input['seats'] ?? 100),
                $input['status'] ?? 'upcoming',
                intval($input['is_featured'] ?? 0),
                $id
            ]);

            echo json_encode(['status' => 'success', 'message' => 'تم تحديث بيانات الفعالية']);
        } elseif ($method === 'DELETE') {
            $id = $_GET['id'] ?? null;
            if ($id) {
                $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
                $stmt->execute([$id]);
                echo json_encode(['status' => 'success', 'message' => 'تم حذف الفعالية']);
            }
        }
        break;

    // ==========================================
    // 3. AUTHENTICATION & USER ACCOUNTS (CR v0.3.1)
    // ==========================================
    case 'login':
        $input = getJsonInput();
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1");
        $stmt->execute([$email]);
        $u = $stmt->fetch();

        if ($u && password_verify($password, $u['password'])) {
            $token = base64_encode($u['email'] . ':' . $u['id']);
            unset($u['password']);
            
            // Log activity
            $pdo->prepare("INSERT INTO audit_logs (user_id, user_name, action, details) VALUES (?, ?, ?, ?)")
                ->execute([$u['id'], $u['name'], 'Login', "User logged in with role {$u['role']}"]);

            echo json_encode([
                'status' => 'success',
                'token' => $token,
                'user' => $u,
                'redirect' => ($u['role'] === 'registered_user') ? '/' : '/admin'
            ]);
        } else {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة']);
        }
        break;

    case 'register':
        $input = getJsonInput();
        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';
        $phone = trim($input['phone'] ?? '');

        if (!$name || !$email || !$password) {
            http_response_code(422);
            echo json_encode(['status' => 'error', 'message' => 'الاسم والبريد الإلكتروني وكلمة المرور مطلوبة']);
            break;
        }

        // Check duplicate
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            http_response_code(422);
            echo json_encode(['status' => 'error', 'message' => 'هذا البريد الإلكتروني مسجل بالفعل']);
            break;
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, phone, role) VALUES (?, ?, ?, ?, 'registered_user')");
        $stmt->execute([$name, $email, $hash, $phone]);
        $newId = $pdo->lastInsertId();

        $token = base64_encode($email . ':' . $newId);
        echo json_encode([
            'status' => 'success',
            'token' => $token,
            'user' => [
                'id' => $newId,
                'name' => $name,
                'email' => $email,
                'role' => 'registered_user',
                'phone' => $phone
            ]
        ]);
        break;

    case 'me':
        $user = getCurrentUser($pdo);
        if ($user) {
            echo json_encode(['status' => 'success', 'user' => $user]);
        } else {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Unauthenticated']);
        }
        break;

    case 'update_profile':
        $user = getCurrentUser($pdo);
        if (!$user) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Unauthenticated']);
            break;
        }
        $input = getJsonInput();
        $name = trim($input['name'] ?? $user['name']);
        $phone = trim($input['phone'] ?? $user['phone']);
        
        $sql = "UPDATE users SET name = ?, phone = ?";
        $params = [$name, $phone];

        if (!empty($input['password'])) {
            $sql .= ", password = ?";
            $params[] = password_hash($input['password'], PASSWORD_BCRYPT);
        }
        $sql .= " WHERE id = ?";
        $params[] = $user['id'];

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        echo json_encode(['status' => 'success', 'message' => 'تم تحديث الملف الشخصي بنجاح']);
        break;

    // ==========================================
    // 4. EVENT REGISTRATION (CR v0.3.1 Section 4)
    // ==========================================
    case 'register_for_event':
        $user = getCurrentUser($pdo);
        if (!$user) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'التسجيل في الفعاليات يتطلب تسجيل الدخول أولاً']);
            break;
        }

        $input = getJsonInput();
        $eventId = $input['event_id'] ?? '';
        if (!$eventId) {
            http_response_code(422);
            echo json_encode(['status' => 'error', 'message' => 'معرف الفعالية مطلوب']);
            break;
        }

        // Verify Event
        $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->execute([$eventId]);
        $ev = $stmt->fetch();
        if (!$ev) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'الفعالية غير موجودة']);
            break;
        }

        // Check if registration deadline passed
        if ($ev['registration_deadline'] && strtotime($ev['registration_deadline']) < time()) {
            http_response_code(422);
            echo json_encode(['status' => 'error', 'message' => 'انتهى الموعد النهائي المتاح للتسجيل في هذه الفعالية']);
            break;
        }

        // Check Duplicate registration
        $stmt = $pdo->prepare("SELECT id, status FROM event_registrations WHERE event_id = ? AND (user_id = ? OR email = ?)");
        $stmt->execute([$eventId, $user['id'], $user['email']]);
        $existing = $stmt->fetch();
        if ($existing && $existing['status'] !== 'cancelled') {
            http_response_code(422);
            echo json_encode(['status' => 'error', 'message' => 'أنت مسجل بالفعل في هذه الفعالية']);
            break;
        }

        // Check seats capacity
        $stmt = $pdo->prepare("SELECT COUNT(*) as registered_count FROM event_registrations WHERE event_id = ? AND status = 'registered'");
        $stmt->execute([$eventId]);
        $regCount = $stmt->fetch()['registered_count'];

        $status = ($regCount >= $ev['seats']) ? 'waitlist' : 'registered';

        if ($existing) {
            // Re-activate cancelled
            $stmt = $pdo->prepare("UPDATE event_registrations SET status = ?, registered_at = NOW() WHERE id = ?");
            $stmt->execute([$status, $existing['id']]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO event_registrations (event_id, user_id, name, email, phone, status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$eventId, $user['id'], $user['name'], $user['email'], $user['phone'], $status]);
        }

        echo json_encode([
            'status' => 'success',
            'registration_status' => $status,
            'message' => ($status === 'registered') ? 'تم تأكيد تسجيلك في الفعالية بنجاح!' : 'اكتملت المقاعد، تم إضافتك إلى قائمة الانتظار.'
        ]);
        break;

    case 'my_events':
        $user = getCurrentUser($pdo);
        if (!$user) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Unauthenticated']);
            break;
        }

        $stmt = $pdo->prepare("SELECT er.id as reg_id, er.status as reg_status, er.registered_at, e.* 
                               FROM event_registrations er 
                               JOIN events e ON er.event_id = e.id 
                               WHERE er.user_id = ? OR er.email = ? 
                               ORDER BY e.starts_at DESC");
        $stmt->execute([$user['id'], $user['email']]);
        echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll()]);
        break;

    case 'cancel_registration':
        $user = getCurrentUser($pdo);
        if (!$user) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Unauthenticated']);
            break;
        }
        $input = getJsonInput();
        $regId = $input['reg_id'] ?? null;
        $stmt = $pdo->prepare("UPDATE event_registrations SET status = 'cancelled' WHERE id = ? AND (user_id = ? OR email = ?)");
        $stmt->execute([$regId, $user['id'], $user['email']]);
        echo json_encode(['status' => 'success', 'message' => 'تم إلغاء تسجيلك في الفعالية بنجاح']);
        break;

    // ==========================================
    // 5. VOLUNTEER SPEC v0.3.3 API
    // ==========================================
    case 'volunteer_submit':
        $user = getCurrentUser($pdo);
        $input = getJsonInput();

        $fullName = trim($input['full_name'] ?? '');
        $parts = preg_split('/\s+/', $fullName);
        if (count($parts) < 4) {
            http_response_code(422);
            echo json_encode(['status' => 'error', 'message' => 'الاسم الرباعي إلزامي (4 أسماء على الأقل)']);
            break;
        }

        $teamId = $input['team_id'] ?? '';
        if (!$teamId) {
            http_response_code(422);
            echo json_encode(['status' => 'error', 'message' => 'اختيار الفريق إلزامي']);
            break;
        }

        $id = 'VOL-' . date('y') . '-' . rand(1000, 9999);

        $stmt = $pdo->prepare("INSERT INTO volunteer_applications (
            id, user_id, full_name, university, college, major, batch, email, phone, whatsapp, team_id, specialty_id, general_answers, team_answers, why_choose_you, terms_version, terms_accepted_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

        $stmt->execute([
            $id,
            $user ? $user['id'] : null,
            $fullName,
            $input['university'] ?? 'جامعة الزعيم الأزهري',
            $input['college'] ?? '',
            $input['major'] ?? '',
            $input['batch'] ?? '',
            $input['email'] ?? '',
            $input['phone'] ?? '',
            $input['whatsapp'] ?? '',
            $teamId,
            $input['specialty_id'] ?? null,
            json_encode($input['general_answers'] ?? []),
            json_encode($input['team_answers'] ?? []),
            $input['why_choose_you'] ?? '',
            'v2.0'
        ]);

        echo json_encode([
            'status' => 'success',
            'id' => $id,
            'message' => 'تم إرسال طلب التطوع بنجاح! سيتم مراجعته والتواصل معك قريباً.'
        ]);
        break;

    case 'volunteer_applications':
        $user = getCurrentUser($pdo);
        if (!checkPermission($user, 'volunteers', 'view')) {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'غير مصرح لك باستعراض طلبات التطوع']);
            break;
        }

        // Coordinators see their own team only (Spec v0.3.3 Section 9.4)
        $role = $user['role'];
        if ($role === 'office_member' || $role === 'hr') {
            $stmt = $pdo->query("SELECT * FROM volunteer_applications ORDER BY submitted_at DESC");
        } else {
            // Coordinator team mapping
            $map = [
                'programs_coordinator' => 'program',
                'magazine_coordinator' => 'magazine',
                'media_coordinator' => 'media'
            ];
            $allowedTeam = $map[$role] ?? 'none';
            $stmt = $pdo->prepare("SELECT * FROM volunteer_applications WHERE team_id = ? ORDER BY submitted_at DESC");
            $stmt->execute([$allowedTeam]);
        }
        echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll()]);
        break;

    case 'update_volunteer_status':
        $user = getCurrentUser($pdo);
        if (!checkPermission($user, 'volunteers', 'edit')) {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'غير مصرح لك بتعديل حالة المتطوع']);
            break;
        }
        $input = getJsonInput();
        $id = $input['id'] ?? '';
        $newStatus = $input['status'] ?? 'new';
        $notes = $input['notes'] ?? '';

        $stmt = $pdo->prepare("UPDATE volunteer_applications SET status = ?, reviewer_notes = ? WHERE id = ?");
        $stmt->execute([$newStatus, $notes, $id]);

        echo json_encode(['status' => 'success', 'message' => 'تم تحديث حالة الطلب']);
        break;

    // ==========================================
    // 6. TEAMS & SPECIALTIES
    // ==========================================
    case 'teams':
        $stmt = $pdo->query("SELECT * FROM teams WHERE is_active = 1 ORDER BY order_index ASC");
        $teams = $stmt->fetchAll();

        // Attach specialties
        $stmtSpec = $pdo->query("SELECT * FROM team_specialties");
        $allSpecs = $stmtSpec->fetchAll();

        foreach ($teams as &$t) {
            $t['specialties'] = array_values(array_filter($allSpecs, function($s) use ($t) {
                return $s['team_id'] === $t['id'];
            }));
        }
        echo json_encode(['status' => 'success', 'data' => $teams]);
        break;

    // ==========================================
    // 7. MAGAZINE MANAGEMENT (CR Item 2)
    // ==========================================
    case 'magazines':
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method === 'GET') {
            $stmt = $pdo->query("SELECT * FROM magazine_issues ORDER BY id DESC");
            $issues = $stmt->fetchAll();

            // If empty in DB, seed default sample issues
            if (empty($issues)) {
                $defaults = [
                    ['volume' => 'Vol. 12 | Issue 3 | 2025', 'title' => 'Engineering a Smarter Tomorrow', 'cover' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=600&q=80', 'description' => 'Cutting-edge breakthroughs in renewable grid integration, quantum computing, and student innovations from AAU.', 'file_url' => '#', 'status' => 'published', 'year' => '2025', 'published_date' => 'April 2025'],
                    ['volume' => 'Vol. 12 Issue 1', 'title' => 'The AI Renaissance', 'cover' => 'https://images.unsplash.com/photo-1634017839464-5c339ebe3cb4?auto=format&fit=crop&w=600&q=80', 'description' => 'Generative computing in academia and research.', 'file_url' => '#', 'status' => 'published', 'year' => '2025', 'published_date' => 'January 2025'],
                    ['volume' => 'Vol. 11 Issue 4', 'title' => 'Smart Grids & Africa', 'cover' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=600&q=80', 'description' => 'Sustainable energy transitions.', 'file_url' => '#', 'status' => 'published', 'year' => '2024', 'published_date' => 'November 2024'],
                    ['volume' => 'Vol. 11 Issue 3', 'title' => 'Robotics in Surgery', 'cover' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&w=600&q=80', 'description' => 'Biomedical engineering milestones.', 'file_url' => '#', 'status' => 'published', 'year' => '2024', 'published_date' => 'August 2024']
                ];
                $ins = $pdo->prepare("INSERT INTO magazine_issues (volume, title, cover, description, file_url, status, year, published_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                foreach ($defaults as $d) {
                    $ins->execute([$d['volume'], $d['title'], $d['cover'], $d['description'], $d['file_url'], $d['status'], $d['year'], $d['published_date']]);
                }
                $issues = $pdo->query("SELECT * FROM magazine_issues ORDER BY id DESC")->fetchAll();
            }

            echo json_encode(['status' => 'success', 'data' => $issues]);
            break;
        }

        // Mutation requires permission (Magazine Coordinator or Office Member)
        $user = getCurrentUser($pdo);
        if (!checkPermission($user, 'magazine', 'edit')) {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'غير مصرح لك بإدارة أعداد المجلة']);
            break;
        }

        $input = getJsonInput();

        if ($method === 'POST') {
            $volume = trim($input['volume'] ?? '');
            $title = trim($input['title'] ?? '');
            if (!$volume || !$title) {
                http_response_code(422);
                echo json_encode(['status' => 'error', 'message' => 'عنوان العدد ورقم النسخة مطلوبان']);
                break;
            }

            $stmt = $pdo->prepare("INSERT INTO magazine_issues (volume, title, cover, description, file_url, status, year, published_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $volume,
                $title,
                $input['cover'] ?? 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=600&q=80',
                $input['description'] ?? '',
                $input['file_url'] ?? '#',
                $input['status'] ?? 'published',
                $input['year'] ?? date('Y'),
                $input['published_date'] ?? date('F Y')
            ]);

            echo json_encode(['status' => 'success', 'message' => 'تمت إضافة العدد الجديد بنجاح', 'id' => $pdo->lastInsertId()]);
        } elseif ($method === 'PUT') {
            $id = $_GET['id'] ?? ($input['id'] ?? null);
            if (!$id) {
                http_response_code(422);
                echo json_encode(['status' => 'error', 'message' => 'ID required']);
                break;
            }

            $stmt = $pdo->prepare("UPDATE magazine_issues SET volume = ?, title = ?, cover = ?, description = ?, file_url = ?, status = ?, year = ?, published_date = ? WHERE id = ?");
            $stmt->execute([
                $input['volume'],
                $input['title'],
                $input['cover'],
                $input['description'] ?? '',
                $input['file_url'] ?? '#',
                $input['status'] ?? 'published',
                $input['year'] ?? date('Y'),
                $input['published_date'] ?? date('F Y'),
                $id
            ]);

            echo json_encode(['status' => 'success', 'message' => 'تم تحديث بيانات عدد المجلة بنجاح']);
        } elseif ($method === 'DELETE') {
            $id = $_GET['id'] ?? null;
            if ($id) {
                $pdo->prepare("DELETE FROM magazine_issues WHERE id = ?")->execute([$id]);
                echo json_encode(['status' => 'success', 'message' => 'تم حذف عدد المجلة']);
            }
        }
        break;

    // ==========================================
    // 8. NEWS & MEDIA MANAGEMENT (CR Item 3)
    // ==========================================
    case 'news':
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method === 'GET') {
            $stmt = $pdo->query("SELECT * FROM news ORDER BY id DESC");
            $news = $stmt->fetchAll();

            // Seed if empty
            if (empty($news)) {
                $seedNews = [
                    ['title' => 'IEEE AAU Organizes a Technical Workshop', 'category' => 'Events', 'snippet' => 'A hands-on workshop on Embedded Systems and IoT architectures for engineering undergraduates.', 'content' => 'Full workshop coverage and outcomes...', 'image' => 'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=800&q=80', 'author' => 'Media Team', 'status' => 'published'],
                    ['title' => 'New IEEE Magazine Issue Released', 'category' => 'Announcements', 'snippet' => 'Explore the latest issue of IEEE AAU Magazine featuring student innovations, AI ethics, and interviews.', 'content' => 'Issue release notes...', 'image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=800&q=80', 'author' => 'Editorial Committee', 'status' => 'published'],
                    ['title' => 'Upcoming Event: AI & Future Technologies', 'category' => 'Events', 'snippet' => 'Register now for our flagship symposium featuring guest keynote speakers from regional tech firms.', 'content' => 'Symposium schedule and topics...', 'image' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80', 'author' => 'Events Team', 'status' => 'published'],
                    ['title' => 'IEEE AAU Participates in IEEE Region 8 Event', 'category' => 'Achievements', 'snippet' => 'Our branch represented Alzaeim Alazhari University at the annual cross-sectional congress.', 'content' => 'Congress representation recap...', 'image' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80', 'author' => 'Branch Chair', 'status' => 'published']
                ];
                $insN = $pdo->prepare("INSERT INTO news (title, category, snippet, content, image, author, status, published_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
                foreach ($seedNews as $sn) {
                    $insN->execute([$sn['title'], $sn['category'], $sn['snippet'], $sn['content'], $sn['image'], $sn['author'], $sn['status']]);
                }
                $news = $pdo->query("SELECT * FROM news ORDER BY id DESC")->fetchAll();
            }
            echo json_encode(['status' => 'success', 'data' => $news]);
            break;
        }

        // Mutations require Media Lead or Office Member
        $user = getCurrentUser($pdo);
        if (!checkPermission($user, 'news', 'edit')) {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'غير مصرح لك بإدارة الأخبار والمحتوى الإعلامي']);
            break;
        }

        $input = getJsonInput();

        if ($method === 'POST') {
            $stmt = $pdo->prepare("INSERT INTO news (title, category, snippet, content, image, author, status, published_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([
                $input['title'],
                $input['category'] ?? 'Announcements',
                $input['snippet'] ?? '',
                $input['content'] ?? '',
                $input['image'] ?? 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80',
                $input['author'] ?? $user['name'],
                $input['status'] ?? 'published'
            ]);
            echo json_encode(['status' => 'success', 'message' => 'تم نشر الخبر بنجاح', 'id' => $pdo->lastInsertId()]);
        } elseif ($method === 'PUT') {
            $id = $_GET['id'] ?? ($input['id'] ?? null);
            $stmt = $pdo->prepare("UPDATE news SET title = ?, category = ?, snippet = ?, content = ?, image = ?, author = ?, status = ? WHERE id = ?");
            $stmt->execute([
                $input['title'],
                $input['category'],
                $input['snippet'],
                $input['content'],
                $input['image'],
                $input['author'],
                $input['status'],
                $id
            ]);
            echo json_encode(['status' => 'success', 'message' => 'تم تحديث الخبر بنجاح']);
        } elseif ($method === 'DELETE') {
            $id = $_GET['id'] ?? null;
            if ($id) {
                $pdo->prepare("DELETE FROM news WHERE id = ?")->execute([$id]);
                echo json_encode(['status' => 'success', 'message' => 'تم حذف الخبر']);
            }
        }
        break;

    // ==========================================
    // 9. ARTICLES MANAGEMENT (CR Item 3)
    // ==========================================
    case 'articles':
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method === 'GET') {
            $stmt = $pdo->query("SELECT * FROM articles ORDER BY id DESC");
            $articles = $stmt->fetchAll();

            // Seed if empty
            if (empty($articles)) {
                $seedArts = [
                    ['title' => 'The Future of AI in Education', 'category' => 'Technology', 'snippet' => 'How artificial intelligence is transforming adaptive learning and digital curriculum design.', 'content' => 'Full article text on AI...', 'image' => 'https://images.unsplash.com/photo-1620712943543-bcc4688e7485?auto=format&fit=crop&w=600&q=80', 'author' => 'Sara Ali', 'status' => 'published'],
                    ['title' => 'Getting Started with Embedded Systems', 'category' => 'Technology', 'snippet' => 'A practical guide for engineering students stepping into microcontrollers, ARM, and real-time OS.', 'content' => 'Full article text on embedded systems...', 'image' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=600&q=80', 'author' => 'Ahmed Mohamed', 'status' => 'published'],
                    ['title' => 'Why IEEE Membership Matters', 'category' => 'IEEE', 'snippet' => 'Unlocking access to IEEE Xplore digital library, global grant competitions, and international conferences.', 'content' => 'Full article on IEEE membership value...', 'image' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=600&q=80', 'author' => 'Branch Chair', 'status' => 'published']
                ];
                $insA = $pdo->prepare("INSERT INTO articles (title, category, snippet, content, image, author, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
                foreach ($seedArts as $sa) {
                    $insA->execute([$sa['title'], $sa['category'], $sa['snippet'], $sa['content'], $sa['image'], $sa['author'], $sa['status']]);
                }
                $articles = $pdo->query("SELECT * FROM articles ORDER BY id DESC")->fetchAll();
            }
            echo json_encode(['status' => 'success', 'data' => $articles]);
            break;
        }

        // Mutation requires permission
        $user = getCurrentUser($pdo);
        if (!checkPermission($user, 'articles', 'edit')) {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'غير مصرح لك بإدارة المقالات']);
            break;
        }

        $input = getJsonInput();
        if ($method === 'POST') {
            $stmt = $pdo->prepare("INSERT INTO articles (title, category, snippet, content, image, author, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $input['title'],
                $input['category'] ?? 'Technology',
                $input['snippet'] ?? '',
                $input['content'] ?? '',
                $input['image'] ?? 'https://images.unsplash.com/photo-1620712943543-bcc4688e7485?auto=format&fit=crop&w=600&q=80',
                $input['author'] ?? $user['name'],
                $input['status'] ?? 'published'
            ]);
            echo json_encode(['status' => 'success', 'message' => 'تمت إضافة المقال بنجاح', 'id' => $pdo->lastInsertId()]);
        } elseif ($method === 'PUT') {
            $id = $_GET['id'] ?? ($input['id'] ?? null);
            $stmt = $pdo->prepare("UPDATE articles SET title = ?, category = ?, snippet = ?, content = ?, image = ?, author = ?, status = ? WHERE id = ?");
            $stmt->execute([
                $input['title'],
                $input['category'],
                $input['snippet'],
                $input['content'],
                $input['image'],
                $input['author'],
                $input['status'],
                $id
            ]);
            echo json_encode(['status' => 'success', 'message' => 'تم تحديث المقال']);
        } elseif ($method === 'DELETE') {
            $id = $_GET['id'] ?? null;
            if ($id) {
                $pdo->prepare("DELETE FROM articles WHERE id = ?")->execute([$id]);
                echo json_encode(['status' => 'success', 'message' => 'تم حذف المقال']);
            }
        }
        break;

    // ==========================================
    // 10. FILE UPLOADS (PDF & IMAGES)
    // ==========================================
    case 'upload':
        $user = getCurrentUser($pdo);
        if (!$user) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'يجب تسجيل الدخول كمسؤول أو منسق لرفع الملفات']);
            break;
        }

        if (empty($_FILES['file'])) {
            http_response_code(422);
            echo json_encode(['status' => 'error', 'message' => 'لم يتم استلام أي ملف من النموذج']);
            break;
        }

        $file = $_FILES['file'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errMap = [
                UPLOAD_ERR_INI_SIZE => 'حجم الملف أكبر من الحد المسموح به في إعدادات السيرفر (upload_max_filesize)',
                UPLOAD_ERR_FORM_SIZE => 'حجم الملف أكبر من الحد المسموح به في النموذج',
                UPLOAD_ERR_PARTIAL => 'تم رفع جزء من الملف فقط، يرجى إعادة المحاولة',
                UPLOAD_ERR_NO_FILE => 'لم يتم اختيار أي ملف',
                UPLOAD_ERR_NO_TMP_DIR => 'مجلد الرفع المؤقت غير متاح على السيرفر',
                UPLOAD_ERR_CANT_WRITE => 'فشل في حفظ الملف على القرص',
                UPLOAD_ERR_EXTENSION => 'توقف الرفع بواسطة إضافة في السيرفر'
            ];
            $msg = $errMap[$file['error']] ?? ('رمز خطأ الرفع: ' . $file['error']);
            http_response_code(422);
            echo json_encode(['status' => 'error', 'message' => $msg]);
            break;
        }

        $folder = preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['folder'] ?? 'media');
        $targetDir = __DIR__ . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $folder;
        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0777, true);
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedExts = ['pdf', 'jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (!in_array($ext, $allowedExts)) {
            http_response_code(422);
            echo json_encode(['status' => 'error', 'message' => 'نوع الملف غير مسموح به. الأنواع المسموحة: ' . implode(', ', $allowedExts)]);
            break;
        }

        $newFileName = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $destination = $targetDir . DIRECTORY_SEPARATOR . $newFileName;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            // Also ensure copy exists in dist/uploads so both URLs resolve
            $distTarget = __DIR__ . DIRECTORY_SEPARATOR . 'dist' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $folder;
            if (!is_dir($distTarget)) {
                @mkdir($distTarget, 0777, true);
            }
            @copy($destination, $distTarget . DIRECTORY_SEPARATOR . $newFileName);

            $webPath = 'uploads/' . $folder . '/' . $newFileName;
            echo json_encode([
                'status' => 'success',
                'message' => 'تم رفع الملف بنجاح',
                'url' => $webPath,
                'fileName' => $file['name'],
                'size' => $file['size']
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'تعذر نقل الملف إلى مجلد التخزين. تحقق من صلاحيات المجلد']);
        }
        break;

    // ==========================================
    // 11. OUR TEAM & OFFICE TERMS (Spec v0.3.4)
    // ==========================================
    case 'current_term':
        $termId = $_GET['id'] ?? null;
        if ($termId) {
            $stmt = $pdo->prepare("SELECT * FROM office_terms WHERE id = ?");
            $stmt->execute([$termId]);
            $term = $stmt->fetch();
        } else {
            $stmt = $pdo->query("SELECT * FROM office_terms WHERE status = 'current' ORDER BY starts_on DESC LIMIT 1");
            $term = $stmt->fetch();
            if (!$term) {
                // Fallback to latest published
                $stmt = $pdo->query("SELECT * FROM office_terms WHERE status != 'draft' ORDER BY starts_on DESC LIMIT 1");
                $term = $stmt->fetch();
            }
        }

        if (!$term) {
            echo json_encode(['status' => 'error', 'message' => 'لا توجد دورة مكتبية متاحة حالياً']);
            break;
        }

        // Fetch positions and joined member details
        $stmtP = $pdo->prepare("
            SELECT tp.id as position_id, tp.term_id, tp.member_id, tp.position_group, tp.position_key, tp.team_id, 
                   tp.title_en, tp.title_ar, tp.sort_order, tp.is_vacant,
                   tm.name_en, tm.name_ar, tm.photo_path, tm.bio_en, tm.bio_ar, tm.linkedin_url, tm.email_public, tm.user_id
            FROM term_positions tp
            JOIN term_members tm ON tp.member_id = tm.id
            WHERE tp.term_id = ?
            ORDER BY tp.sort_order ASC
        ");
        $stmtP->execute([$term['id']]);
        $allPositions = $stmtP->fetchAll();

        $officeMembers = array_values(array_filter($allPositions, function($p) {
            return $p['position_group'] === 'office';
        }));

        $coordinators = array_values(array_filter($allPositions, function($p) {
            return $p['position_group'] === 'coordinator';
        }));

        // Archive dropdown terms list
        $allTerms = $pdo->query("SELECT id, title, starts_on, ends_on, status FROM office_terms WHERE status != 'draft' ORDER BY starts_on DESC")->fetchAll();

        echo json_encode([
            'status' => 'success',
            'data' => [
                'term' => $term,
                'office_members' => $officeMembers,
                'coordinators' => $coordinators,
                'archive_terms' => $allTerms
            ]
        ]);
        break;

    case 'office_terms':
        $method = $_SERVER['REQUEST_METHOD'];
        $user = getCurrentUser($pdo);

        // Viewing terms in admin requires at least office_member view permission
        if ($method === 'GET') {
            $termId = $_GET['id'] ?? null;
            if ($termId) {
                $stmt = $pdo->prepare("SELECT * FROM office_terms WHERE id = ?");
                $stmt->execute([$termId]);
                $term = $stmt->fetch();
                if (!$term) {
                    http_response_code(404);
                    echo json_encode(['status' => 'error', 'message' => 'الدورة غير موجودة']);
                    break;
                }

                $stmtP = $pdo->prepare("
                    SELECT tp.id as position_id, tp.term_id, tp.member_id, tp.position_group, tp.position_key, tp.team_id, 
                           tp.title_en, tp.title_ar, tp.sort_order, tp.is_vacant,
                           tm.name_en, tm.name_ar, tm.photo_path, tm.bio_en, tm.bio_ar, tm.linkedin_url, tm.email_public, tm.user_id
                    FROM term_positions tp
                    JOIN term_members tm ON tp.member_id = tm.id
                    WHERE tp.term_id = ?
                    ORDER BY tp.sort_order ASC
                ");
                $stmtP->execute([$termId]);
                $positions = $stmtP->fetchAll();

                echo json_encode([
                    'status' => 'success',
                    'data' => [
                        'term' => $term,
                        'positions' => $positions
                    ]
                ]);
                break;
            }

            // List of all terms
            $stmt = $pdo->query("SELECT ot.*, (SELECT COUNT(*) FROM term_positions tp WHERE tp.term_id = ot.id) as positions_count FROM office_terms ot ORDER BY ot.starts_on DESC");
            $terms = $stmt->fetchAll();
            echo json_encode(['status' => 'success', 'data' => $terms]);
            break;
        }

        // Mutations require Chairman Role according to Spec v0.3.4 Section 2.1
        if (!isChairman($user)) {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'هذه الصلاحية مخصصة لرئيس الفرع (Chairman) فقط وفق المواصفة v0.3.4']);
            break;
        }

        $input = getJsonInput();

        if ($method === 'POST') {
            $startsOn = $input['starts_on'] ?? '';
            $endsOn = $input['ends_on'] ?? '';
            $title = trim($input['title'] ?? '');

            if (!$startsOn || !$endsOn) {
                http_response_code(422);
                echo json_encode(['status' => 'error', 'message' => 'تاريخا بداية ونهاية الدورة مطلوبان']);
                break;
            }

            if (strtotime($endsOn) <= strtotime($startsOn)) {
                http_response_code(422);
                echo json_encode(['status' => 'error', 'message' => 'تاريخ نهاية الدورة يجب أن يكون بعد تاريخ البداية']);
                break;
            }

            // Check overlapping terms (Rule 2.3)
            $stmtOverlap = $pdo->prepare("SELECT id, title FROM office_terms WHERE (starts_on <= ? AND ends_on >= ?) OR (starts_on <= ? AND ends_on >= ?)");
            $stmtOverlap->execute([$endsOn, $startsOn, $startsOn, $endsOn]);
            $overlap = $stmtOverlap->fetch();
            if ($overlap) {
                http_response_code(422);
                echo json_encode(['status' => 'error', 'message' => "فترة الدورة تتعارض مع دورة مسجلة مسبقاً ({$overlap['title']})"]);
                break;
            }

            if (!$title) {
                $yStart = date('Y', strtotime($startsOn));
                $yEnd = date('Y', strtotime($endsOn));
                $title = "{$yStart} – {$yEnd}";
            }

            $stmt = $pdo->prepare("INSERT INTO office_terms (title, starts_on, ends_on, status, created_by) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$title, $startsOn, $endsOn, $input['status'] ?? 'draft', $user['id']]);
            $newTermId = $pdo->lastInsertId();

            // Duplicate from previous term if copy_from_id is requested (Feature 2.2-ج)
            $copyFromId = $input['copy_from_id'] ?? null;
            if ($copyFromId) {
                $prevMembers = $pdo->prepare("SELECT * FROM term_members WHERE term_id = ?");
                $prevMembers->execute([$copyFromId]);
                $oldMembers = $prevMembers->fetchAll();

                $memberIdMap = [];
                $insMem = $pdo->prepare("INSERT INTO term_members (term_id, user_id, name_en, name_ar, photo_path, bio_en, bio_ar, linkedin_url, email_public) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                foreach ($oldMembers as $om) {
                    $insMem->execute([
                        $newTermId, $om['user_id'], $om['name_en'], $om['name_ar'], 
                        $om['photo_path'], $om['bio_en'], $om['bio_ar'], $om['linkedin_url'], $om['email_public']
                    ]);
                    $memberIdMap[$om['id']] = $pdo->lastInsertId();
                }

                $prevPositions = $pdo->prepare("SELECT * FROM term_positions WHERE term_id = ?");
                $prevPositions->execute([$copyFromId]);
                $oldPositions = $prevPositions->fetchAll();

                $insPos = $pdo->prepare("INSERT INTO term_positions (term_id, member_id, position_group, position_key, team_id, title_en, title_ar, sort_order, is_vacant) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                foreach ($oldPositions as $op) {
                    $newMemberId = $memberIdMap[$op['member_id']] ?? null;
                    if ($newMemberId) {
                        $insPos->execute([
                            $newTermId, $newMemberId, $op['position_group'], $op['position_key'],
                            $op['team_id'], $op['title_en'], $op['title_ar'], $op['sort_order'], $op['is_vacant']
                        ]);
                    }
                }
            }

            // Log action in audit logs
            $pdo->prepare("INSERT INTO audit_logs (user_id, user_name, action, details) VALUES (?, ?, 'Create Term', ?)")
                ->execute([$user['id'], $user['name'], "Created new office term: {$title} (#{$newTermId})"]);

            echo json_encode([
                'status' => 'success', 
                'message' => 'تم إنشاء دورة المكتب بنجاح', 
                'id' => $newTermId
            ]);
        } elseif ($method === 'PUT') {
            $id = $_GET['id'] ?? ($input['id'] ?? null);
            if (!$id) {
                http_response_code(422);
                echo json_encode(['status' => 'error', 'message' => 'معرف الدورة مطلوب']);
                break;
            }

            $startsOn = $input['starts_on'] ?? '';
            $endsOn = $input['ends_on'] ?? '';
            $title = trim($input['title'] ?? '');
            $status = $input['status'] ?? 'draft';

            if (strtotime($endsOn) <= strtotime($startsOn)) {
                http_response_code(422);
                echo json_encode(['status' => 'error', 'message' => 'تاريخ نهاية الدورة يجب أن يكون بعد تاريخ البداية']);
                break;
            }

            // Check overlapping terms excluding current term
            $stmtOverlap = $pdo->prepare("SELECT id, title FROM office_terms WHERE id != ? AND ((starts_on <= ? AND ends_on >= ?) OR (starts_on <= ? AND ends_on >= ?))");
            $stmtOverlap->execute([$id, $endsOn, $startsOn, $startsOn, $endsOn]);
            $overlap = $stmtOverlap->fetch();
            if ($overlap) {
                http_response_code(422);
                echo json_encode(['status' => 'error', 'message' => "فترة الدورة تتعارض مع دورة أخرى مسجلة ({$overlap['title']})"]);
                break;
            }

            $pdo->prepare("UPDATE office_terms SET title = ?, starts_on = ?, ends_on = ?, status = ? WHERE id = ?")
                ->execute([$title, $startsOn, $endsOn, $status, $id]);

            // If updating positions & members (Full term editor save)
            if (isset($input['positions']) && is_array($input['positions'])) {
                // Keep track of HR member id so that Office HR and Coordinator HR use the identical record (Spec v0.3.4 Section 1.3)
                $hrMemberId = null;

                foreach ($input['positions'] as $pos) {
                    $posId = $pos['position_id'] ?? null;
                    $memberId = $pos['member_id'] ?? null;
                    $isVacant = !empty($pos['is_vacant']) ? 1 : 0;

                    // If this is the HR coordinator and we already processed the office HR, link directly
                    if ($pos['position_group'] === 'coordinator' && $pos['position_key'] === 'hr' && $hrMemberId) {
                        $memberId = $hrMemberId;
                    }

                    if ($memberId) {
                        // Update existing member record
                        $stmtM = $pdo->prepare("UPDATE term_members SET user_id = ?, name_en = ?, name_ar = ?, photo_path = ?, bio_en = ?, bio_ar = ?, linkedin_url = ?, email_public = ? WHERE id = ?");
                        $stmtM->execute([
                            $pos['user_id'] ?: null,
                            $pos['name_en'] ?? '',
                            $pos['name_ar'] ?? '',
                            $pos['photo_path'] ?? '',
                            $pos['bio_en'] ?? '',
                            $pos['bio_ar'] ?? '',
                            $pos['linkedin_url'] ?? '',
                            $pos['email_public'] ?? '',
                            $memberId
                        ]);

                        if ($pos['position_group'] === 'office' && $pos['position_key'] === 'hr') {
                            $hrMemberId = $memberId;
                        }
                    } else {
                        // Insert new member
                        $stmtM = $pdo->prepare("INSERT INTO term_members (term_id, user_id, name_en, name_ar, photo_path, bio_en, bio_ar, linkedin_url, email_public) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                        $stmtM->execute([
                            $id,
                            $pos['user_id'] ?: null,
                            $pos['name_en'] ?? '',
                            $pos['name_ar'] ?? '',
                            $pos['photo_path'] ?? '',
                            $pos['bio_en'] ?? '',
                            $pos['bio_ar'] ?? '',
                            $pos['linkedin_url'] ?? '',
                            $pos['email_public'] ?? ''
                        ]);
                        $memberId = $pdo->lastInsertId();

                        if ($pos['position_group'] === 'office' && $pos['position_key'] === 'hr') {
                            $hrMemberId = $memberId;
                        }
                    }

                    // Update position row
                    if ($posId) {
                        $stmtPos = $pdo->prepare("UPDATE term_positions SET member_id = ?, title_en = ?, title_ar = ?, sort_order = ?, is_vacant = ? WHERE id = ?");
                        $stmtPos->execute([
                            $memberId,
                            $pos['title_en'] ?? '',
                            $pos['title_ar'] ?? '',
                            $pos['sort_order'] ?? 0,
                            $isVacant,
                            $posId
                        ]);
                    } else {
                        $stmtPos = $pdo->prepare("INSERT INTO term_positions (term_id, member_id, position_group, position_key, team_id, title_en, title_ar, sort_order, is_vacant) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                        $stmtPos->execute([
                            $id,
                            $memberId,
                            $pos['position_group'],
                            $pos['position_key'],
                            $pos['team_id'] ?? null,
                            $pos['title_en'] ?? '',
                            $pos['title_ar'] ?? '',
                            $pos['sort_order'] ?? 0,
                            $isVacant
                        ]);
                    }
                }
            }

            // Log edit
            $pdo->prepare("INSERT INTO audit_logs (user_id, user_name, action, details) VALUES (?, ?, 'Update Term', ?)")
                ->execute([$user['id'], $user['name'], "Updated office term details & positions: {$title} (#{$id})"]);

            echo json_encode(['status' => 'success', 'message' => 'تم حفظ وتحديث بيانات الدورة بنجاح']);
        } elseif ($method === 'DELETE') {
            $id = $_GET['id'] ?? null;
            if (!$id) {
                http_response_code(422);
                echo json_encode(['status' => 'error', 'message' => 'معرف الدورة مطلوب']);
                break;
            }

            $stmtCheck = $pdo->prepare("SELECT status, title FROM office_terms WHERE id = ?");
            $stmtCheck->execute([$id]);
            $t = $stmtCheck->fetch();

            if ($t && $t['status'] === 'current') {
                http_response_code(422);
                echo json_encode(['status' => 'error', 'message' => 'لا يمكن حذف الدورة الحالية النشطة مباشرة، يرجى تفعيل دورة أخرى أولاً']);
                break;
            }

            $pdo->prepare("DELETE FROM term_positions WHERE term_id = ?")->execute([$id]);
            $pdo->prepare("DELETE FROM term_members WHERE term_id = ?")->execute([$id]);
            $pdo->prepare("DELETE FROM office_terms WHERE id = ?")->execute([$id]);

            $pdo->prepare("INSERT INTO audit_logs (user_id, user_name, action, details) VALUES (?, ?, 'Delete Term', ?)")
                ->execute([$user['id'], $user['name'], "Deleted office term #{$id} ({$t['title']})"]);

            echo json_encode(['status' => 'success', 'message' => 'تم حذف الدورة وأرشيفها بنجاح']);
        }
        break;

    // Term Activation & Role Synchronization (Spec v0.3.4 Section 3)
    case 'activate_term':
        $user = getCurrentUser($pdo);
        if (!isChairman($user)) {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'تفعيل الدورة مخصص لرئيس الفرع (Chairman) فقط وفق المواصفة v0.3.4']);
            break;
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(422);
            echo json_encode(['status' => 'error', 'message' => 'معرف الدورة مطلوب']);
            break;
        }

        // 1. Move all other terms to archived
        $pdo->prepare("UPDATE office_terms SET status = 'archived' WHERE status = 'current' AND id != ?")->execute([$id]);
        
        // 2. Set this term to current & published
        $pdo->prepare("UPDATE office_terms SET status = 'current', published_at = NOW() WHERE id = ?")->execute([$id]);

        // 3. Role Sync with Users Table (Section 3: Automatic Role Granting)
        $stmtP = $pdo->prepare("
            SELECT tp.position_key, tp.position_group, tm.user_id, tm.name_ar, tm.name_en
            FROM term_positions tp
            JOIN term_members tm ON tp.member_id = tm.id
            WHERE tp.term_id = ? AND tm.user_id IS NOT NULL AND tp.is_vacant = 0
        ");
        $stmtP->execute([$id]);
        $linkedPositions = $stmtP->fetchAll();

        $roleMap = [
            'chairman' => 'office_member',
            'vice_chair' => 'office_member',
            'secretary' => 'office_member',
            'treasurer' => 'office_member',
            'hr' => 'hr',
            'program' => 'programs_coordinator',
            'magazine' => 'magazine_coordinator',
            'media' => 'media_coordinator'
        ];

        $syncedCount = 0;
        foreach ($linkedPositions as $lp) {
            $assignedRole = $roleMap[$lp['position_key']] ?? null;
            if ($assignedRole && $lp['user_id']) {
                $pdo->prepare("UPDATE users SET role = ? WHERE id = ?")->execute([$assignedRole, $lp['user_id']]);
                $syncedCount++;
            }
        }

        // Audit Log
        $pdo->prepare("INSERT INTO audit_logs (user_id, user_name, action, details) VALUES (?, ?, 'Activate Term', ?)")
            ->execute([$user['id'], $user['name'], "Activated office term #{$id} as CURRENT and synced {$syncedCount} user administrative roles"]);

        echo json_encode([
            'status' => 'success', 
            'message' => 'تم تفعيل الدورة بنجاح ومزامنة صلاحيات أعضاء المكتب والمنسقين',
            'synced_roles' => $syncedCount
        ]);
        break;

    // Default System Ping
    default:
        echo json_encode([
            'status' => 'success',
            'branch' => 'IEEE AAU Student Branch',
            'server_time' => date('Y-m-d H:i:s'),
            'timezone' => 'Africa/Khartoum',
            'version' => '0.3.3'
        ]);
        break;
}
