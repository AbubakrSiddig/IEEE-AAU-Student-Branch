<?php
// Database Migration & Seeder Script
$host = '127.0.0.1';
$dbname = 'ieee_aau_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    // 1. Run Schema
    $sql = file_get_contents(__DIR__ . '/schema.sql');
    $pdo->exec($sql);
    echo "Schema tables created or updated successfully.\n";

    // 2. Seed Default Admin Users (Office Member, HR, Programs, Magazine, Media, Regular User)
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    if ($stmt->fetch()['count'] == 0) {
        $insertUser = $pdo->prepare("INSERT INTO users (name, email, password, role, phone, is_active) VALUES (?, ?, ?, ?, ?, ?)");
        
        // Passwords hashed with standard bcrypt
        $defaultPass = password_hash('password123', PASSWORD_BCRYPT);

        // Office Member (Super Admin)
        $insertUser->execute(['Branch Chair (Super Admin)', 'admin@ieee-aau.org', $defaultPass, 'office_member', '+249912000001', 1]);
        
        // HR Coordinator
        $insertUser->execute(['HR Coordinator', 'hr@ieee-aau.org', $defaultPass, 'hr', '+249912000002', 1]);
        
        // Programs Coordinator
        $insertUser->execute(['Programs Coordinator', 'programs@ieee-aau.org', $defaultPass, 'programs_coordinator', '+249912000003', 1]);
        
        // Magazine Coordinator
        $insertUser->execute(['Magazine Editor', 'magazine@ieee-aau.org', $defaultPass, 'magazine_coordinator', '+249912000004', 1]);
        
        // Media Coordinator
        $insertUser->execute(['Media Lead', 'media@ieee-aau.org', $defaultPass, 'media_coordinator', '+249912000005', 1]);
        
        // Sample Student / Regular Registered User
        $insertUser->execute(['Ahmed Mohamed (Student)', 'student@aau.edu', $defaultPass, 'registered_user', '+249912345678', 1]);

        echo "Default role users seeded successfully.\n";
    }

    // 3. Seed Default Teams (8 Teams as specified in v0.3.3)
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM teams");
    if ($stmt->fetch()['count'] == 0) {
        $insertTeam = $pdo->prepare("INSERT INTO teams (id, name_en, name_ar, description_en, description_ar, tasks_en, tasks_ar, order_index) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        $teams = [
            ['media', 'Media', 'الإعلام والتغطيات', 'Creative production, visual branding, social coverage', 'إدارة الإنتاج الإبداعي والتصميم والتغطية الرقمية', 'Graphic design, photo & video coverage, content writing', 'تصميم الجرافيك، المونتاج، التصوير، كتابة المحتوى', 1],
            ['technical', 'Technical', 'الفريق التقني', 'Audio/visual systems, logistics tech, coding bootcamps', 'إدارة الأجهزة الصوتية والمرئية، البرمجة والأنظمة', 'Sound setup, projection, IT logistics, tech workshops', 'تجهيز الصوت، العرض، الدعم الفني، وورش البرمجة', 2],
            ['magazine', 'Magazine', 'المجلة العلمية', 'Branch editorial magazine and scientific writing', 'إعداد وتحرير أعداد مجلة الفرع الدورية والأبحاث', 'Editing articles, science journalism, peer reviews', 'تحرير المقالات، الصحافة العلمية، والمراجعة', 3],
            ['program', 'Program', 'البرامج والتنظيم', 'Event planning, agenda management, execution flow', 'تخطيط الفعاليات والمؤتمرات وإدارة الأجندة', 'Logistics coordination, crowd management, scheduling', 'التنسيق اللوجستي، إدارة الحشود، وضبط التوقيت', 4],
            ['pr', 'PR', 'العلاقات العامة', 'Partnerships, external outreach, sponsor relations', 'بناء الشراكات والتواصل مع الجهات الخارجية والرعاة', 'Sponsorship outreach, speaker invitations, guest hosting', 'التواصل مع الرعاة، دعوة المتحدثين، واستقبال الضيوف', 5],
            ['hr', 'HR', 'الموارد البشرية', 'Member onboarding, evaluations, conflict resolution', 'إدارة وتوجيه المتطوعين، التقييم، وفض النزاعات', 'Attendance tracking, team morale, interviews & onboarding', 'متابعة الحضور، تحفيز الفرق، والمقابلات الشخصية', 6],
            ['presenters', 'Presenters', 'عرافة الحفل والتقديم', 'Public speaking, stage presence, MCing official ceremonies', 'التقديم المسرحي وإدارة منصات الفعاليات الرسمية', 'Master of ceremonies, welcoming guests, speeches delivery', 'عرافة الحفل، إدارة المنصة، إلقاء الكلمات الرسمية', 7],
            ['speaker', 'Speaker', 'المتحدثون والتنسيق', 'Speaker acquisition, communication, session prep', 'البحث والتنسيق مع المحاضرين والمتحدثين المتخصصين', 'Speaker outreach, bio vetting, topic harmonization', 'البحث عن المحاضرين، التنسيق، وضبط محاور الجلسات', 8],
        ];

        foreach ($teams as $t) {
            $insertTeam->execute($t);
        }
        echo "8 Teams seeded successfully.\n";

        // Media Specialties
        $insertSpec = $pdo->prepare("INSERT INTO team_specialties (id, team_id, name_en, name_ar) VALUES (?, ?, ?, ?)");
        $specs = [
            ['graphic_designer', 'media', 'Graphic Designer', 'مصمم جرافيك'],
            ['photographer', 'media', 'Photographer', 'مصور فوتوغرافي'],
            ['content_writer', 'media', 'Content Writer', 'كاتب محتوى'],
            ['video_editor', 'media', 'Video Editor', 'محرر فيديو / مونتير']
        ];
        foreach ($specs as $s) {
            $insertSpec->execute($s);
        }
        echo "Media specialties seeded.\n";
    }

    // 4. Seed Real Datetime Events (Africa/Khartoum timezone)
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM events");
    if ($stmt->fetch()['count'] == 0) {
        $insertEv = $pdo->prepare("INSERT INTO events (id, title, description, starts_at, ends_at, registration_deadline, location, event_type, meeting_link, speakers, seats, status, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Calculate realistic upcoming dates in the future
        $now = new DateTime('now', new DateTimeZone('Africa/Khartoum'));
        
        $ev1_start = (clone $now)->modify('+5 days')->setTime(10, 0, 0)->format('Y-m-d H:i:s');
        $ev1_end = (clone $now)->modify('+5 days')->setTime(14, 0, 0)->format('Y-m-d H:i:s');
        $ev1_deadline = (clone $now)->modify('+4 days')->setTime(23, 59, 59)->format('Y-m-d H:i:s');
        
        $ev2_start = (clone $now)->modify('+12 days')->setTime(9, 30, 0)->format('Y-m-d H:i:s');
        $ev2_end = (clone $now)->modify('+12 days')->setTime(13, 0, 0)->format('Y-m-d H:i:s');
        
        $ev3_start = (clone $now)->modify('+20 days')->setTime(16, 0, 0)->format('Y-m-d H:i:s');
        $ev3_end = (clone $now)->modify('+20 days')->setTime(19, 0, 0)->format('Y-m-d H:i:s');

        $insertEv->execute([
            'EV-01',
            'AI & Future Technologies Symposium',
            'Premier international conference delving into Generative AI, machine perception, and engineering solutions.',
            $ev1_start,
            $ev1_end,
            $ev1_deadline,
            'AAU Main Conference Hall',
            'in_person',
            null,
            'Dr. Al-Amin & Eng. Maryam',
            150,
            'upcoming',
            1
        ]);

        $insertEv->execute([
            'EV-02',
            'Fullstack Web Architecture Bootcamp',
            'Hands-on practical development workshop building scalable REST APIs and modern reactive user interfaces.',
            $ev2_start,
            $ev2_end,
            null,
            'Computer Lab 2 - Engineering Campus',
            'in_person',
            null,
            'Lead Dev Mohamed Hassan',
            45,
            'upcoming',
            0
        ]);

        $insertEv->execute([
            'EV-03',
            'Global IEEE Day & Student Innovation Pitch',
            'Annual branch gathering celebrating regional achievements, IEEE membership milestones, and student awards.',
            $ev3_start,
            $ev3_end,
            null,
            'Virtual Zoom / Campus Garden',
            'hybrid',
            'https://ieee.zoom.us/j/aau-branch',
            'Branch Counselor & Alumni Panel',
            250,
            'upcoming',
            0
        ]);

        echo "Sample real-datetime events seeded in Africa/Khartoum timezone.\n";
    }

    echo "Migration completed successfully!\n";
} catch (Exception $e) {
    echo "Migration Error: " . $e->getMessage() . "\n";
    exit(1);
}
