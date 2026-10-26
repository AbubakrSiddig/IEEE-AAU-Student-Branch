<?php
// Migration for Office Terms & Our Team (Spec v0.3.4)
$host = '127.0.0.1';
$dbname = 'ieee_aau_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // 1. Create Tables
    $pdo->exec("
    CREATE TABLE IF NOT EXISTS `office_terms` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `title` VARCHAR(191) NOT NULL,
      `starts_on` DATE NOT NULL,
      `ends_on` DATE NOT NULL,
      `status` ENUM('draft', 'current', 'archived') DEFAULT 'draft',
      `published_at` DATETIME NULL,
      `created_by` INT NULL,
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE IF NOT EXISTS `term_members` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `term_id` INT NOT NULL,
      `user_id` INT NULL,
      `name_en` VARCHAR(191) NOT NULL,
      `name_ar` VARCHAR(191) NOT NULL,
      `photo_path` VARCHAR(500) NULL,
      `bio_en` TEXT NULL,
      `bio_ar` TEXT NULL,
      `linkedin_url` VARCHAR(255) NULL,
      `email_public` VARCHAR(191) NULL,
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      INDEX (`term_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

    CREATE TABLE IF NOT EXISTS `term_positions` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `term_id` INT NOT NULL,
      `member_id` INT NOT NULL,
      `position_group` ENUM('office', 'coordinator') NOT NULL,
      `position_key` VARCHAR(50) NOT NULL,
      `team_id` VARCHAR(50) NULL,
      `title_en` VARCHAR(191) NOT NULL,
      `title_ar` VARCHAR(191) NOT NULL,
      `sort_order` INT DEFAULT 0,
      `is_vacant` TINYINT(1) DEFAULT 0,
      INDEX (`term_id`),
      INDEX (`member_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    echo "Tables office_terms, term_members, term_positions created successfully.\n";

    // 2. Check if terms already seeded
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM office_terms");
    if ($stmt->fetch()['count'] == 0) {
        // A) Current Term: 2025 – 2026
        $insTerm = $pdo->prepare("INSERT INTO office_terms (title, starts_on, ends_on, status, published_at, created_by) VALUES (?, ?, ?, ?, NOW(), 1)");
        $insTerm->execute(['2025 – 2026', '2025-09-01', '2026-08-31', 'current']);
        $currentTermId = $pdo->lastInsertId();

        // Helper to insert member
        $insMem = $pdo->prepare("INSERT INTO term_members (term_id, user_id, name_en, name_ar, photo_path, bio_en, bio_ar, linkedin_url, email_public) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insPos = $pdo->prepare("INSERT INTO term_positions (term_id, member_id, position_group, position_key, team_id, title_en, title_ar, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        // 1. Chairman
        $insMem->execute([
            $currentTermId,
            1, // user admin@ieee-aau.org
            'Mohamed Al-Amin',
            'محمد الأمين',
            'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80',
            'Senior electrical engineering undergraduate leading branch growth, global partnerships, and student innovation.',
            'طالب هندسة كهربائية يقود نمو الفرع والشراكات الإقليمية والمبادرات الابتكارية للطلاب.',
            'https://linkedin.com',
            'chairman@ieee-aau.org'
        ]);
        $chairMemId = $pdo->lastInsertId();
        $insPos->execute([$currentTermId, $chairMemId, 'office', 'chairman', null, 'IEEE AAU Chairman', 'رئيس فرع IEEE AAU', 1]);

        // 2. Vice Chair
        $insMem->execute([
            $currentTermId,
            null,
            'Maryam Osman',
            'مريم عثمان',
            'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=400&q=80',
            'Telecommunications engineering enthusiast focused on executive operations and strategic planning.',
            'مهندسة اتصالات مهتمة بالعمليات التنفيذية والتخطيط الإستراتيجي للفعاليات.',
            'https://linkedin.com',
            'vicechair@ieee-aau.org'
        ]);
        $vcMemId = $pdo->lastInsertId();
        $insPos->execute([$currentTermId, $vcMemId, 'office', 'vice_chair', null, 'Vice Chair', 'نائب الرئيس', 2]);

        // 3. Secretary
        $insMem->execute([
            $currentTermId,
            null,
            'Yusuf Ibrahim',
            'يوسف إبراهيم',
            'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80',
            'Computer systems undergraduate managing branch official correspondence, reporting, and archival records.',
            'طالب هندسة حاسوب مسؤول عن المراسلات والتقارير الدورية وأرشفة وثائق الفرع.',
            'https://linkedin.com',
            'secretary@ieee-aau.org'
        ]);
        $secMemId = $pdo->lastInsertId();
        $insPos->execute([$currentTermId, $secMemId, 'office', 'secretary', null, 'Secretary', 'أمين السر', 3]);

        // 4. Treasurer
        $insMem->execute([
            $currentTermId,
            null,
            'Fatima Hassan',
            'فاطمة حسن',
            'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80',
            'Financial planning and budget auditing for branch student activities and technical projects.',
            'إدارة الميزانيات والتدقيق المالي لجميع الأنشطة الطلابية والمشاريع التقنية.',
            'https://linkedin.com',
            'treasurer@ieee-aau.org'
        ]);
        $trMemId = $pdo->lastInsertId();
        $insPos->execute([$currentTermId, $trMemId, 'office', 'treasurer', null, 'Treasurer', 'أمين الصندوق', 4]);

        // 5. HR Officer (SAME PERSON for Office HR and HR Team Coordinator according to Spec v0.3.4 Section 1.3)
        $insMem->execute([
            $currentTermId,
            2, // user hr@ieee-aau.org
            'Sara Ahmed',
            'سارة أحمد',
            'https://images.unsplash.com/photo-1567532939604-b6b5b0db2604?auto=format&fit=crop&w=400&q=80',
            'Talent management, volunteer recruitment screening, onboarding, and organizational culture building.',
            'إدارة المواهب والفرز الأكاديمي للمتطوعين وصقل المهارات القيادية بالفرع.',
            'https://linkedin.com',
            'hr@ieee-aau.org'
        ]);
        $hrMemId = $pdo->lastInsertId();
        // Insert in Office
        $insPos->execute([$currentTermId, $hrMemId, 'office', 'hr', null, 'HR Officer', 'مسؤول الموارد البشرية', 5]);
        // Insert in Team Coordinators (Shares identical member_id!)
        $insPos->execute([$currentTermId, $hrMemId, 'coordinator', 'hr', 'hr', 'HR Team Coordinator', 'منسق فريق الموارد البشرية', 11]);

        // 6. Media Coordinator
        $insMem->execute([
            $currentTermId,
            5, // media@ieee-aau.org
            'Tariq Zein',
            'طارق زين',
            'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=400&q=80',
            'Creative direction, visual branding, social media coverage, and live event video production.',
            'الإخراج الإبداعي والهوية البصرية والتغطيات الإعلامية وتصميم المحتوى الرقمي.',
            'https://linkedin.com',
            'media@ieee-aau.org'
        ]);
        $mediaMemId = $pdo->lastInsertId();
        $insPos->execute([$currentTermId, $mediaMemId, 'coordinator', 'media', 'media', 'Media Team Coordinator', 'منسق فريق الإعلام', 6]);

        // 7. Technical Coordinator
        $insMem->execute([
            $currentTermId,
            null,
            'Omer Khalid',
            'عمر خالد',
            'https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?auto=format&fit=crop&w=400&q=80',
            'Hands-on embedded systems developer leading audio/visual logistics, booth setups, and hackathons.',
            'مطور أنظمة مدمجة يشرف على اللوجستيات التقنية والصوتية وتنظيم الهاكاثونات البرمجية.',
            'https://github.com',
            'technical@ieee-aau.org'
        ]);
        $techMemId = $pdo->lastInsertId();
        $insPos->execute([$currentTermId, $techMemId, 'coordinator', 'technical', 'technical', 'Technical Team Coordinator', 'منسق الفريق التقني', 7]);

        // 8. Magazine Coordinator
        $insMem->execute([
            $currentTermId,
            4, // magazine@ieee-aau.org
            'Hiba El-Nour',
            'هبة النور',
            'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80',
            'Chief editor overseeing technical writing, peer reviewing, science journalism, and volume publishing.',
            'رئيسة تحرير مجلة الفرع، مسؤولة عن تدقيق الأبحاث والمقالات العلمية وإصدار النسخ.',
            'https://linkedin.com',
            'magazine@ieee-aau.org'
        ]);
        $magMemId = $pdo->lastInsertId();
        $insPos->execute([$currentTermId, $magMemId, 'coordinator', 'magazine', 'magazine', 'Magazine Team Coordinator', 'منسق فريق المجلة', 8]);

        // 9. Program Coordinator
        $insMem->execute([
            $currentTermId,
            3, // programs@ieee-aau.org
            'Ali Babiker',
            'علي بابكر',
            'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=400&q=80',
            'Event scheduling, venue coordination, crowd logistics, and speaker hospitality management.',
            'جدولة المؤتمرات، التنسيق الميداني للقاعات، وإدارة تدفق الفعاليات والضيوف.',
            'https://linkedin.com',
            'programs@ieee-aau.org'
        ]);
        $progMemId = $pdo->lastInsertId();
        $insPos->execute([$currentTermId, $progMemId, 'coordinator', 'program', 'program', 'Program Team Coordinator', 'منسق فريق البرامج', 9]);

        // 10. PR Coordinator
        $insMem->execute([
            $currentTermId,
            null,
            'Zainab Mahmoud',
            'زينب محمود',
            'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&w=400&q=80',
            'External outreach, corporate sponsorship relations, partner communication, and institutional ties.',
            'بناء الشراكات مع الشركات الراعية، وإدارة العلاقات الخارجية والتواصل المؤسسي.',
            'https://linkedin.com',
            'pr@ieee-aau.org'
        ]);
        $prMemId = $pdo->lastInsertId();
        $insPos->execute([$currentTermId, $prMemId, 'coordinator', 'pr', 'pr', 'PR Coordinator', 'منسق العلاقات العامة', 10]);

        // 11. Presenters Coordinator
        $insMem->execute([
            $currentTermId,
            null,
            'Mustafa Jalal',
            'مصطفى جلال',
            'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=400&q=80',
            'Stage presentation training, ceremonial MCing, welcoming delegations, and public speaking coaching.',
            'عرافة الحفل، تدريب مقدمي المنصة، وإلقاء الخطابات الرسمية في الاحتفالات.',
            'https://linkedin.com',
            'presenters@ieee-aau.org'
        ]);
        $presMemId = $pdo->lastInsertId();
        $insPos->execute([$currentTermId, $presMemId, 'coordinator', 'presenters', 'presenters', 'Presenters Team Coordinator', 'منسق فريق التقديم', 12]);

        // 12. Speaker Coordinator
        $insMem->execute([
            $currentTermId,
            null,
            'Rania Siddig',
            'رانيا صديق',
            'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=400&q=80',
            'Keynote speaker research, outreach, briefing kits, and technical session topic synchronization.',
            'البحث عن المتحدثين والخبراء، والتنسيق معهم لإعداد الجلسات العلمية وتسهيل مشاركتهم.',
            'https://linkedin.com',
            'speakers@ieee-aau.org'
        ]);
        $spkMemId = $pdo->lastInsertId();
        $insPos->execute([$currentTermId, $spkMemId, 'coordinator', 'speaker', 'speaker', 'Speaker Relations Coordinator', 'منسق المتحدثين', 13]);

        echo "Current Term (2025-2026) seeded with 5 office members and 8 coordinators.\n";

        // B) Archived Term: 2024 – 2025
        $insTerm->execute(['2024 – 2025', '2024-09-01', '2025-08-31', 'archived']);
        $archivedTermId = $pdo->lastInsertId();

        // Seed 1 past chairman for the archive
        $insMem->execute([
            $archivedTermId,
            null,
            'Hassan Abdelrahman',
            'حسن عبد الرحمن',
            'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80',
            'Past Branch Chair 2024-2025 who chartered major community initiatives.',
            'رئيس الفرع للدورة السابقة 2024-2025.',
            'https://linkedin.com',
            'alumni@ieee-aau.org'
        ]);
        $pastChairId = $pdo->lastInsertId();
        $insPos->execute([$archivedTermId, $pastChairId, 'office', 'chairman', null, 'IEEE AAU Chairman', 'رئيس فرع IEEE AAU', 1]);

        echo "Archived Term (2024-2025) seeded successfully.\n";
    }

    echo "Office terms migration completed successfully!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
