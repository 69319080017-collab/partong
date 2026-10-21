<?php
require_once 'config.php';

// ===== ข้อมูลจำลองสำหรับประมวลผลการแจ้งเตือน =====

$skills = [
    'fe' => 'Web Frontend',
    'be' => 'Backend',
    'db' => 'Database',
    'nw' => 'Network',
    'ux' => 'UI/UX',
];

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

$competitions = [
    ['name' => 'พัฒนาเว็บไซต์',      'en' => 'Web Technologies',                 'requires' => ['fe' => 4, 'be' => 3, 'db' => 3]],
    ['name' => 'ระบบเครือข่าย',      'en' => 'IT Network Systems',               'requires' => ['nw' => 4, 'db' => 2]],
    ['name' => 'พัฒนาแอปพลิเคชัน',   'en' => 'Software Application Development', 'requires' => ['be' => 4, 'db' => 4, 'fe' => 3]],
    ['name' => 'ออกแบบ UI/UX',      'en' => 'UI/UX Design',                     'requires' => ['ux' => 4, 'fe' => 3]],
];

function h($text) {
    return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
}

function matchPercent(array $student, array $competition) {
    $sum = 0;
    foreach ($competition['requires'] as $skill => $minLevel) {
        $sum += min($student['skills'][$skill] / $minLevel, 1);
    }
    return (int) round($sum / count($competition['requires']) * 100);
}

// วิเคราะห์ผู้ผ่านเกณฑ์ 100% รายสายการแข่งขัน
$compPassed = [];
foreach ($competitions as $comp) {
    $passedStudents = [];
    foreach ($students as $student) {
        if (matchPercent($student, $comp) === 100) {
            $passedStudents[] = $student['name'];
        }
    }
    $compPassed[$comp['name']] = $passedStudents;
}

// รายการแจ้งเตือนทั้งหมดในระบบ (Dynamic + Business Logic)
$alerts = [
    [
        'id'        => 1,
        'type'      => 'danger', // สีแดง: วิกฤต
        'title'     => 'สายพัฒนาแอปพลิเคชัน มีตัวแทนไม่เพียงพอ',
        'message'   => 'มีผู้ผ่านเกณฑ์ครบ 100% เพียง 1 คน (' . implode(', ', $compPassed['พัฒนาแอปพลิเคชัน']) . ') ต้องการอย่างน้อย 2 คนสำหรับตัวจริงและตัวสำรอง',
        'category'  => 'ขาดตัวแทนแข่งขัน',
        'action'    => 'แนะนำให้นักเรียนที่มีคะแนน Database และ Backend ใกล้เคียง (เช่น กิตติพงษ์ ใจดี) ติวเสริมเพื่อดันระดับทักษะ',
        'time'      => 'ตรวจพบล่าสุดเมื่อ 10 นาทีที่แล้ว',
        'is_urgent' => true
    ],
    [
        'id'        => 2,
        'type'      => 'warning', // สีเหลือง: เฝ้าระวัง
        'title'     => 'จำนวนตัวแทนรวมทั้งแผนกยังไม่ถึงเป้าหมาย',
        'message'   => 'ปัจจุบันมีนักเรียนผ่านเกณฑ์ครบอย่างน้อย 1 สายจำนวน 7 คน จากเป้าหมายรวม ' . TARGET_REPRESENTATIVES . ' คน ขาดอีก 1 คน',
        'category'  => 'เป้าหมายแผนก',
        'action'    => 'เปิดรับการประเมินทักษะรอบใหม่สำหรับนักศึกษาชั้นปีที่ 1 และ 2',
        'time'      => 'คำนวณจากเกณฑ์ ' . APP_NAME,
        'is_urgent' => true
    ],
    [
        'id'        => 3,
        'type'      => 'warning',
        'title'     => 'คะแนนเฉลี่ยทักษะ Network อยู่ในเกณฑ์ต่ำสุด',
        'message'   => 'ทักษะด้าน Network มีค่าเฉลี่ยของทั้งห้องเพียง 2.6 / 5 ซึ่งอาจส่งผลต่อการแข่งขันระดับภาคและชาติ',
        'category'  => 'คุณภาพทักษะ',
        'action'    => 'ประสานงานอาจารย์ผู้สอนรายวิชาเครือข่าย เพื่อจัด Workshop เพิ่มเติมในคาบแล็บ',
        'time'      => 'วิเคราะห์จาก 8 นักเรียน',
        'is_urgent' => false
    ],
    [
        'id'        => 4,
        'type'      => 'info', // สีน้ำเงิน: ข้อมูล
        'title'     => 'มีการอัปเดตคะแนนประเมินทักษะล่าสุด',
        'message'   => 'นางสาวชลธิชา บุญเรือง ได้รับการประเมินทักษะ UI/UX ปรับขึ้นเป็นระดับ 3',
        'category'  => 'อัปเดตข้อมูล',
        'action'    => 'ส่งผลให้ % Match สายระบบเครือข่ายขยับขึ้นเป็น 88%',
        'time'      => 'บันทึกเมื่อ 21/10/2026',
        'is_urgent' => false
    ],
    [
        'id'        => 5,
        'type'      => 'success', // สีเขียว: สำเร็จ
        'title'     => 'สายพัฒนาเว็บไซต์ และระบบเครือข่าย ตัวแทนพร้อมสมบูรณ์',
        'message'   => 'มีนักเรียนผ่านเกณฑ์ 100% ครบตามโควตาแล้ว (พัฒนาเว็บไซต์: ' . count($compPassed['พัฒนาเว็บไซต์']) . ' คน, ระบบเครือข่าย: ' . count($compPassed['ระบบเครือข่าย']) . ' คน)',
        'category'  => 'ความพร้อมแข่งขัน',
        'action'    => 'เริ่มจัดตารางฝึกซ้อมโจทย์แข่งขันระดับจังหวัดได้ทันที',
        'time'      => 'สถานะพร้อมแข่ง',
        'is_urgent' => false
    ]
];

// นับสถิติแต่ละประเภท
$dangerCount  = count(array_filter($alerts, fn($a) => $a['type'] === 'danger'));
$warningCount = count(array_filter($alerts, fn($a) => $a['type'] === 'warning'));
$infoCount    = count(array_filter($alerts, fn($a) => $a['type'] === 'info'));
$successCount = count(array_filter($alerts, fn($a) => $a['type'] === 'success'));
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ศูนย์การแจ้งเตือน – <?php echo APP_NAME; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --navy: #1E3A8A;
      --red: #C8102E;
    }
    body { font-family: 'Sarabun', Tahoma, sans-serif; background: #F4F6FB; color: #0F172A; }
    .text-navy { color: var(--navy); }
    .navbar-brand { color: var(--navy); font-weight: 700; }
    .brand-mark { width: 36px; height: 36px; border-radius: 9px; background: var(--navy); }
    .navbar .nav-link { font-weight: 500; }
    .navbar .nav-link.active { color: var(--navy); font-weight: 700; box-shadow: inset 0 -3px 0 var(--red); }
    .card { border-color: #E2E8F0; border-radius: 12px; }

    .alert-card { border-left-width: 5px !important; }
    .alert-card.danger { border-left-color: #DC2626 !important; }
    .alert-card.warning { border-left-color: #D97706 !important; }
    .alert-card.info { border-left-color: #2563EB !important; }
    .alert-card.success { border-left-color: #16A34A !important; }
  </style>
</head>
<body>

  <!-- แถบเมนูนำทาง -->
  <nav class="navbar navbar-expand-lg bg-white border-bottom">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
        <span class="brand-mark d-inline-flex align-items-center justify-content-center" aria-hidden="true">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round"><circle cx="8" cy="8" r="3"/><circle cx="16" cy="16" r="3"/><path d="M10.5 10.5l3 3"/></svg>
        </span>
        <?php echo APP_NAME; ?>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
              aria-controls="mainNavbar" aria-expanded="false" aria-label="เปิดเมนู">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="mainNavbar">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="index.php">หน้าแรก</a></li>
          <li class="nav-item">
            <a class="nav-link active position-relative" aria-current="page" href="alerts.php">
              การแจ้งเตือน
              <span class="badge rounded-pill bg-danger ms-1"><?php echo count($alerts); ?></span>
            </a>
          </li>
          <li class="nav-item"><a class="nav-link" href="about.php">เกี่ยวกับเรา</a></li>
          <li class="nav-item"><a class="nav-link" href="server.php">ข้อมูลเซิร์ฟเวอร์</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <main class="container py-4 py-lg-5">
    
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-4">
      <div>
        <h1 class="h3 fw-bold text-navy mb-1">ศูนย์การแจ้งเตือนและการเตือนภัยระบบ</h1>
        <p class="text-secondary mb-0">รวบรวมรายการเตือนความพร้อมของตัวแทน ข้อควรระวัง และสถานะทักษะที่ต้องเร่งพัฒนา</p>
      </div>
      <a href="index.php" class="btn btn-outline-primary btn-sm">← กลับหน้าภาพรวม</a>
    </div>

    <!-- สรุปตัวเลขแจ้งเตือน 4 ประเภท -->
    <div class="row row-cols-2 row-cols-md-4 g-3 mb-4">
      <div class="col">
        <div class="card p-3 bg-white border-top border-4 border-danger shadow-sm">
          <div class="text-danger fw-bold small">วิกฤต / ต้องแก้ไขทันที</div>
          <div class="fs-2 fw-bold text-danger"><?php echo $dangerCount; ?> <span class="fs-6 fw-normal text-muted">เรื่อง</span></div>
        </div>
      </div>
      <div class="col">
        <div class="card p-3 bg-white border-top border-4 border-warning shadow-sm">
          <div class="text-warning fw-bold small">เฝ้าระวัง / ขาดเกณฑ์</div>
          <div class="fs-2 fw-bold text-warning"><?php echo $warningCount; ?> <span class="fs-6 fw-normal text-muted">เรื่อง</span></div>
        </div>
      </div>
      <div class="col">
        <div class="card p-3 bg-white border-top border-4 border-primary shadow-sm">
          <div class="text-primary fw-bold small">ข้อมูลอัปเดตล่าสุด</div>
          <div class="fs-2 fw-bold text-primary"><?php echo $infoCount; ?> <span class="fs-6 fw-normal text-muted">เรื่อง</span></div>
        </div>
      </div>
      <div class="col">
        <div class="card p-3 bg-white border-top border-4 border-success shadow-sm">
          <div class="text-success fw-bold small">สายแข่งที่พร้อมสมบูรณ์</div>
          <div class="fs-2 fw-bold text-success"><?php echo $successCount; ?> <span class="fs-6 fw-normal text-muted">เรื่อง</span></div>
        </div>
      </div>
    </div>

    <!-- รายการการ์ดแจ้งเตือนทั้งหมด -->
    <div class="d-flex flex-column gap-3">
      <?php foreach ($alerts as $a): ?>
        <?php
          $badgeClass = match($a['type']) {
            'danger'  => 'bg-danger',
            'warning' => 'bg-warning text-dark',
            'info'    => 'bg-primary',
            'success' => 'bg-success',
            default   => 'bg-secondary'
          };
          $icon = match($a['type']) {
            'danger'  => '🚨',
            'warning' => '⚠️',
            'info'    => 'ℹ️',
            'success' => '✅',
            default   => '📌'
          };
        ?>
        <div class="card p-4 bg-white alert-card <?php echo $a['type']; ?> shadow-sm">
          <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2">
            <div class="d-flex align-items-center gap-2">
              <span class="fs-5"><?php echo $icon; ?></span>
              <span class="badge <?php echo $badgeClass; ?>"><?php echo h($a['category']); ?></span>
              <h2 class="h5 fw-bold mb-0 text-navy"><?php echo h($a['title']); ?></h2>
            </div>
            <span class="small text-secondary"><?php echo h($a['time']); ?></span>
          </div>

          <p class="text-secondary mb-3 ps-4"><?php echo h($a['message']); ?></p>

          <div class="bg-light rounded-3 p-3 ms-4 border-start border-3 border-secondary">
            <div class="small fw-bold text-dark mb-1">💡 สิ่งที่ระบบแนะนำให้ทำ:</div>
            <div class="small text-secondary"><?php echo h($a['action']); ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  </main>

  <footer class="border-top bg-white mt-5">
    <div class="container py-3 d-flex flex-wrap justify-content-between gap-2 small text-secondary">
      <span>Skill Match Team · ศูนย์แจ้งเตือน <?php echo APP_NAME; ?></span>
      <span>อัปเดตล่าสุด: <?php echo date('d/m/Y H:i:s'); ?></span>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
