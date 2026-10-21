<?php
// ข้อมูลและฟังก์ชันที่ใช้ร่วมกันทุกหน้า (index.php, alerts.php)
require_once __DIR__ . '/config.php';

// ===== ข้อมูลจำลอง (สัปดาห์ 8 จะเปลี่ยนไปดึงจากฐานข้อมูลด้วย PDO) =====

// ทักษะที่ประเมิน: key => ชื่อที่แสดง
$skills = [
    'fe' => 'Web Frontend',
    'be' => 'Backend',
    'db' => 'Database',
    'nw' => 'Network',
    'ux' => 'UI/UX',
];

// นักเรียน: ระดับทักษะ 1-5 (ชื่อสมมติ)
$students = [
    ['name' => 'กิตติพงษ์ ใจดี',       'skills' => ['fe' => 5, 'be' => 4, 'db' => 3, 'nw' => 2, 'ux' => 3]],
    ['name' => 'ศิริพร แสงทอง',        'skills' => ['fe' => 3, 'be' => 2, 'db' => 2, 'nw' => 1, 'ux' => 5]],
    ['name' => 'ธนากร มีสุข',          'skills' => ['fe' => 2, 'be' => 3, 'db' => 3, 'nw' => 5, 'ux' => 1]],
    ['name' => 'พิมพ์ชนก รักเรียน',     'skills' => ['fe' => 4, 'be' => 5, 'db' => 4, 'nw' => 2, 'ux' => 2]],
    ['name' => 'วรเชษฐ์ ทองคำ',        'skills' => ['fe' => 1, 'be' => 2, 'db' => 3, 'nw' => 4, 'ux' => 1]],
    ['name' => 'อรอุมา ศรีสวัสดิ์',      'skills' => ['fe' => 4, 'be' => 2, 'db' => 2, 'nw' => 1, 'ux' => 4]],
    ['name' => 'ภานุวัฒน์ พลเยี่ยม',     'skills' => ['fe' => 3, 'be' => 4, 'db' => 5, 'nw' => 3, 'ux' => 2]],
    ['name' => 'ชลธิชา บุญเรือง',       'skills' => ['fe' => 2, 'be' => 1, 'db' => 2, 'nw' => 3, 'ux' => 3]],
];

// สายการแข่งขัน: ทักษะที่ต้องการ => ระดับขั้นต่ำ
$competitions = [
    ['name' => 'พัฒนาเว็บไซต์',      'en' => 'Web Technologies',                 'requires' => ['fe' => 4, 'be' => 3, 'db' => 3]],
    ['name' => 'ระบบเครือข่าย',      'en' => 'IT Network Systems',               'requires' => ['nw' => 4, 'db' => 2]],
    ['name' => 'พัฒนาแอปพลิเคชัน',   'en' => 'Software Application Development', 'requires' => ['be' => 4, 'db' => 4, 'fe' => 3]],
    ['name' => 'ออกแบบ UI/UX',      'en' => 'UI/UX Design',                     'requires' => ['ux' => 4, 'fe' => 3]],
];

// รายการประเมินล่าสุด 1 รายการ (ตามข้อกำหนดใบงานที่ 2 ขั้นที่ 4-6)
$latestEvaluation = [
    'student_name'  => 'ชลธิชา บุญเรือง',
    'skill_key'     => 'ux',
    'skill_level'   => 3,
    'eval_date'     => '2026-10-21',
];

// ===== ฟังก์ชัน =====

// กันอักขระพิเศษก่อนแสดงผลใน HTML
function h($text)
{
    return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
}

// ระดับทักษะของนักเรียน ถ้ายังไม่มีข้อมูลทักษะนั้นให้เป็น 0
function skillLevel(array $student, $skill)
{
    return $student['skills'][$skill] ?? 0;
}

// % Match = ค่าเฉลี่ยของ min(ระดับที่มี ÷ ขั้นต่ำ, 1) ของทุกทักษะที่สายต้องการ
function matchPercent(array $student, array $competition)
{
    $requires = $competition['requires'] ?? [];
    if (count($requires) === 0) {
        return 0; // สายที่ยังไม่กำหนดเกณฑ์ ไม่นำมาคำนวณ (กันหารด้วยศูนย์)
    }

    $sum = 0;
    foreach ($requires as $skill => $minLevel) {
        // ขั้นต่ำ 0 หรือติดลบ = ไม่มีเกณฑ์ ถือว่าผ่านทักษะนี้ (กันหารด้วยศูนย์)
        $sum += $minLevel > 0 ? min(skillLevel($student, $skill) / $minLevel, 1) : 1;
    }
    return (int) round($sum / count($requires) * 100);
}

// สีของ % Match: เทียบกับค่าคงที่ใน config.php
function matchClass($percent)
{
    if ($percent >= MATCH_HIGH_THRESHOLD) {
        return 'match-high';
    }
    if ($percent >= MATCH_MID_THRESHOLD) {
        return 'match-mid';
    }
    return 'match-low';
}


// รวบรวมการแจ้งเตือนทั้งหมดของระบบ
// แต่ละรายการ: level (danger/warning/info), category, title, message
function buildAlerts(array $students, array $competitions, array $skills)
{
    $alerts = [];

    // 1) ความพร้อมของทีม: คนที่ผ่านเกณฑ์ครบอย่างน้อย 1 สาย ยังไม่ถึงเป้าหมาย
    $passedCount = 0;
    foreach ($students as $student) {
        foreach ($competitions as $comp) {
            if (matchPercent($student, $comp) === 100) {
                $passedCount++;
                break;
            }
        }
    }
    if ($passedCount < TARGET_REPRESENTATIVES) {
        $alerts[] = [
            'level'    => 'warning',
            'category' => 'ความพร้อมของทีม',
            'title'    => 'ตัวแทนยังไม่ครบตามเป้าหมาย',
            'message'  => "ผ่านเกณฑ์ครบแล้ว $passedCount คน จากเป้าหมาย " . TARGET_REPRESENTATIVES . ' คน ยังขาดอีก ' . (TARGET_REPRESENTATIVES - $passedCount) . ' คน',
        ];
    }

    // 2) สายแข่งที่ยังไม่มีใครผ่านเกณฑ์ครบเลย
    foreach ($competitions as $comp) {
        $hasQualified = false;
        foreach ($students as $student) {
            if (matchPercent($student, $comp) === 100) {
                $hasQualified = true;
                break;
            }
        }
        if (!$hasQualified) {
            $alerts[] = [
                'level'    => 'danger',
                'category' => 'สายการแข่งขัน',
                'title'    => "สาย{$comp['name']} ยังไม่มีผู้ผ่านเกณฑ์ครบ",
                'message'  => 'ยังส่งตัวแทนสายนี้ไม่ได้ ควรหานักเรียนเพิ่มหรือจัดติวทักษะที่สายนี้ต้องการ',
            ];
        }
    }

    // 3) นักเรียนที่ยังไม่ผ่านเกณฑ์ครบสายใดเลย: บอกสายที่ใกล้ที่สุดและทักษะที่ยังขาด
    foreach ($students as $student) {
        $best = null;
        foreach ($competitions as $comp) {
            $percent = matchPercent($student, $comp);
            if ($best === null || $percent > $best['percent']) {
                $best = ['comp' => $comp, 'percent' => $percent];
            }
        }
        if ($best['percent'] < 100) {
            $gaps = [];
            foreach (($best['comp']['requires'] ?? []) as $key => $minLevel) {
                $missing = $minLevel - skillLevel($student, $key);
                if ($missing > 0) {
                    $gaps[] = "{$skills[$key]} อีก $missing ระดับ";
                }
            }
            $alerts[] = [
                'level'    => $best['percent'] >= MATCH_HIGH_THRESHOLD ? 'info' : 'warning',
                'category' => 'นักเรียน',
                'title'    => "{$student['name']} ยังไม่ผ่านเกณฑ์ครบสายใด",
                'message'  => "ใกล้ที่สุดคือสาย{$best['comp']['name']} ({$best['percent']}%) ต้องเพิ่ม " . implode(', ', $gaps),
            ];
        }
    }

    // 4) ทักษะที่ค่าเฉลี่ยของนักเรียนทั้งหมดต่ำกว่าเกณฑ์
    $studentCount = count($students);
    foreach ($skills as $key => $skillName) {
        $sum = 0;
        foreach ($students as $student) {
            $sum += skillLevel($student, $key);
        }
        $average = $studentCount > 0 ? round($sum / $studentCount, 2) : 0; // กันหารด้วยศูนย์
        if ($average < SKILL_AVG_ALERT) {
            $alerts[] = [
                'level'    => 'info',
                'category' => 'ทักษะ',
                'title'    => "ทักษะ $skillName เฉลี่ยต่ำกว่าเกณฑ์",
                'message'  => "ค่าเฉลี่ยทั้งวิทยาลัย $average จาก " . MAX_SKILL_LEVEL . ' (เกณฑ์ ' . SKILL_AVG_ALERT . ') ควรพิจารณาเปิดติวเสริม',
            ];
        }
    }

    return $alerts;
}
