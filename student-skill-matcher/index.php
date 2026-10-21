<?php
// เรียกใช้ไฟล์ตั้งค่าของระบบ (ขั้นที่ 2 ตามใบงานที่ 2)
require_once 'config.php';

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

// % Match = ค่าเฉลี่ยของ min(ระดับที่มี ÷ ขั้นต่ำ, 1) ของทุกทักษะที่สายต้องการ
function matchPercent(array $student, array $competition)
{
    $sum = 0;
    foreach ($competition['requires'] as $skill => $minLevel) {
        $sum += min($student['skills'][$skill] / $minLevel, 1);
    }
    return (int) round($sum / count($competition['requires']) * 100);
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

// ===== คำนวณข้อมูลที่จะแสดง (ตามตาราง 3.1 และ 3.2 ในใบงาน) =====

// 1) ภาพรวมตัวเลข (Dashboard Stats)
$totalStudents = count($students);         // int
$totalCompetitions = count($competitions); // int
$totalSkills = count($skills);             // int

// การ์ดสายแข่ง: เรียงนักเรียนตาม % Match แล้วเอา 3 อันดับแรก
$compCards = [];
foreach ($competitions as $comp) {
    $ranked = [];
    foreach ($students as $student) {
        $ranked[] = ['name' => $student['name'], 'percent' => matchPercent($student, $comp)];
    }
    usort($ranked, fn($a, $b) => $b['percent'] <=> $a['percent']);
    $compCards[] = ['comp' => $comp, 'top' => array_slice($ranked, 0, 3)];
}

// ตารางทักษะ + นับคนที่ผ่านเกณฑ์ครบอย่างน้อย 1 สาย
$rows = [];
$passedCount = 0;
foreach ($students as $student) {
    $best = null;
    $hasPassed = false;
    foreach ($competitions as $comp) {
        $percent = matchPercent($student, $comp);
        if ($percent === 100) {
            $hasPassed = true;
        }
        if ($best === null || $percent > $best['percent']) {
            $best = ['name' => $comp['name'], 'percent' => $percent];
        }
    }
    if ($hasPassed) {
        $passedCount++;
    }
    $rows[] = ['student' => $student, 'best' => $best];
}

// สูตรคำนวณ % นักเรียนที่ผ่านเกณฑ์ครบ (ป้องกันการหารด้วยศูนย์ตามกรณีทดสอบ T4)
$passedPercent = $totalStudents > 0 ? round($passedCount / $totalStudents * 100, 1) : 0; // float

// จำนวนตัวแทนที่ยังขาดเทียบกับเป้าหมาย
$neededStudents = TARGET_REPRESENTATIVES - $passedCount > 0 ? TARGET_REPRESENTATIVES - $passedCount : 0; // int

// เงื่อนไขการเตือน (Warning condition)
$isAlertNeeded = $passedCount < TARGET_REPRESENTATIVES; // bool

$stats = [
    ['label' => 'นักเรียนทั้งหมด',              'value' => $totalStudents,                     'note' => 'คนที่มีข้อมูลทักษะในระบบ', 'accent' => false],
    ['label' => 'สายการแข่งขัน',               'value' => $totalCompetitions,                 'note' => 'สายที่เปิดรับสมัคร',       'accent' => false],
    ['label' => 'ทักษะที่ประเมิน',              'value' => $totalSkills,                       'note' => 'ระดับ 1–5 ต่อทักษะ',      'accent' => false],
    ['label' => 'ผ่านเกณฑ์ครบอย่างน้อย 1 สาย', 'value' => $passedCount . ' (' . $passedPercent . '%)', 'note' => 'เป้าหมายอย่างน้อย ' . TARGET_REPRESENTATIVES . ' คน', 'accent' => true],
];
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo APP_NAME; ?> – ภาพรวมการจับคู่ทักษะ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --navy: #1E3A8A;
      --red: #C8102E;
    }
    body { font-family: 'Sarabun', Tahoma, sans-serif; background: #F4F6FB; color: #0F172A; }
    .text-navy { color: var(--navy); }

    /* Navbar: ขีดแดงใต้เมนูที่เปิดอยู่ */
    .navbar-brand { color: var(--navy); font-weight: 700; }
    .brand-mark { width: 36px; height: 36px; border-radius: 9px; background: var(--navy); }
    .navbar .nav-link { font-weight: 500; }
    .navbar .nav-link.active { color: var(--navy); font-weight: 700; box-shadow: inset 0 -3px 0 var(--red); }

    /* การ์ด */
    .card { border-color: #E2E8F0; border-radius: 12px; }
    .stat-card { border-top: 4px solid var(--navy); }
    .stat-card.accent { border-top-color: var(--red); }
    .stat-value { font-size: 2.1rem; font-weight: 700; color: var(--navy); line-height: 1.1; }

    /* ป้ายเกณฑ์ / ผ่านครบ */
    .chip { background: #EFF4FF; color: var(--navy); font-weight: 500; border-radius: 999px; padding: .25rem .65rem; font-size: .85rem; }
    .chip-demo { background: #FFF1F2; color: #9F1239; }
    .badge-pass { background: var(--navy); font-weight: 600; }

    /* อันดับ */
    .rank { width: 28px; height: 28px; border-radius: 50%; background: #E8EEFB; color: var(--navy); font-weight: 700; font-size: .875rem; }
    .rank-1 { background: var(--navy); color: #fff; }

    /* % Match */
    .progress { height: 8px; background: #E2E8F0; }
    .match-high { --c: #1D4ED8; }
    .match-mid  { --c: #3B82F6; }
    .match-low  { --c: var(--red); }
    .match-text { color: var(--c); font-weight: 700; }
    .progress-bar.match-high, .progress-bar.match-mid, .progress-bar.match-low { background: var(--c); }

    /* ระดับทักษะ 1-5 ไล่สีน้ำเงิน */
    .lvl { display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 30px; border-radius: 6px; font-weight: 700; }
    .lvl-1 { background: #EFF6FF; color: var(--navy); }
    .lvl-2 { background: #DBEAFE; color: var(--navy); }
    .lvl-3 { background: #93C5FD; color: #0F172A; }
    .lvl-4 { background: #3B82F6; color: #fff; }
    .lvl-5 { background: var(--navy); color: #fff; }
    .lvl-sm { width: 26px; height: 22px; font-size: .75rem; }
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
          <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php">หน้าแรก</a></li>
          <li class="nav-item">
            <a class="nav-link position-relative" href="alerts.php">
              การแจ้งเตือน
              <span class="badge rounded-pill bg-danger ms-1">5</span>
            </a>
          </li>
          <li class="nav-item"><a class="nav-link" href="about.php">เกี่ยวกับเรา</a></li>
          <li class="nav-item"><a class="nav-link" href="server.php">ข้อมูลเซิร์ฟเวอร์</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <main class="container py-4 py-lg-5">

    <!-- หัวเรื่อง -->
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-3">
      <div>
        <h1 class="h3 fw-bold text-navy mb-1">ภาพรวมการจับคู่ทักษะกับสายการแข่งขัน</h1>
        <p class="text-secondary mb-0">ดูว่านักเรียนคนไหนเหมาะกับสายแข่งใดมากที่สุด คำนวณจากระดับทักษะเทียบกับเกณฑ์ขั้นต่ำของแต่ละสาย</p>
      </div>
      <span class="chip chip-demo">เวอร์ชัน <?php echo APP_VERSION; ?> · ข้อมูลทดสอบ</span>
    </div>

    <!-- ข้อความแจ้งเตือนสถานะความพร้อม (ตามเกณฑ์ใบงานที่ 2 ขั้นที่ 5-6) -->
    <?php if ($isAlertNeeded): ?>
      <div class="alert alert-warning d-flex align-items-center gap-2 mb-4 shadow-sm" role="alert">
        <svg width="22" height="22" fill="currentColor" class="flex-shrink-0" viewBox="0 0 16 16">
          <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
        </svg>
        <div class="flex-grow-1">
          <strong>แจ้งเตือนความพร้อม:</strong> ขณะนี้มีนักเรียนผ่านเกณฑ์ครบ 100% จำนวน <strong><?php echo $passedCount; ?> คน</strong> (เป้าหมายคืออย่างน้อย <?php echo TARGET_REPRESENTATIVES; ?> คน) ยังขาดอีก <strong><?php echo $neededStudents; ?> คน</strong> กรุณาเปิดติวเสริมทักษะเพิ่มเติม
        </div>
        <a href="alerts.php" class="btn btn-sm btn-outline-dark ms-md-2 text-nowrap">ดูแจ้งเตือนทั้งหมด (5) →</a>
      </div>
    <?php else: ?>
      <div class="alert alert-success d-flex align-items-center gap-2 mb-4 shadow-sm" role="alert">
        <svg width="22" height="22" fill="currentColor" class="flex-shrink-0" viewBox="0 0 16 16">
          <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
        </svg>
        <div>
          <strong>สถานะทีมพร้อม:</strong> มีนักเรียนผ่านเกณฑ์ครบตามเป้าหมายแล้ว (<?php echo $passedCount; ?> คน) พร้อมส่งแข่งขัน!
        </div>
      </div>
    <?php endif; ?>

    <!-- กล่องตัวเลขสถิติ 4 กล่อง (ตามเกณฑ์ใบงานที่ 2 ขั้นที่ 3-6) -->
    <div class="row row-cols-2 row-cols-lg-4 g-3 mb-4">
      <?php foreach ($stats as $stat): ?>
        <div class="col">
          <div class="card stat-card h-100 p-3<?php echo $stat['accent'] ? ' accent' : ''; ?>">
            <div class="text-secondary fw-medium"><?php echo h($stat['label']); ?></div>
            <div class="stat-value my-1"><?php echo h($stat['value']); ?></div>
            <div class="small text-secondary d-none d-sm-block"><?php echo h($stat['note']); ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- รายการประเมินล่าสุด 1 รายการ (ตามเกณฑ์ใบงานที่ 2 ขั้นที่ 4-6) -->
    <div class="card p-3 mb-5 bg-white border-start border-4 border-primary shadow-sm">
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="d-flex align-items-center gap-2">
          <span class="badge bg-primary px-2 py-1">รายการประเมินล่าสุด</span>
          <span class="fw-bold"><?php echo h($latestEvaluation['student_name']); ?></span>
          <span class="text-secondary">อัปเดตทักษะ <strong><?php echo h($skills[$latestEvaluation['skill_key']]); ?></strong></span>
          <span class="lvl lvl-sm lvl-<?php echo $latestEvaluation['skill_level']; ?>"><?php echo $latestEvaluation['skill_level']; ?></span>
        </div>
        <div class="small text-secondary">
          บันทึกเมื่อ: <strong><?php echo h($latestEvaluation['eval_date']); ?></strong>
        </div>
      </div>
    </div>

    <!-- สายการแข่งขัน: Top 3 -->
    <div class="d-flex flex-wrap justify-content-between align-items-baseline gap-2 mb-3">
      <h2 class="h4 fw-bold mb-0">สายการแข่งขัน · 3 อันดับที่เหมาะที่สุด</h2>
      <span class="small text-secondary">% Match = ค่าเฉลี่ยของ min(ระดับที่มี ÷ ขั้นต่ำ, 1)</span>
    </div>
    <div class="row row-cols-1 row-cols-md-2 g-3 mb-5">
      <?php foreach ($compCards as $card): ?>
        <div class="col">
          <article class="card h-100 p-4 shadow-sm">
            <h3 class="h5 fw-bold text-navy mb-0"><?php echo h($card['comp']['name']); ?></h3>
            <div class="small text-secondary mb-3"><?php echo h($card['comp']['en']); ?></div>

            <div class="d-flex flex-wrap gap-2 mb-3">
              <?php foreach ($card['comp']['requires'] as $skill => $minLevel): ?>
                <span class="chip"><?php echo h($skills[$skill]); ?> ≥ <?php echo $minLevel; ?></span>
              <?php endforeach; ?>
            </div>

            <ol class="list-unstyled mb-0 d-flex flex-column gap-3">
              <?php foreach ($card['top'] as $i => $item): ?>
                <?php $cls = matchClass($item['percent']); ?>
                <li>
                  <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="rank d-inline-flex align-items-center justify-content-center flex-shrink-0<?php echo $i === 0 ? ' rank-1' : ''; ?>"><?php echo $i + 1; ?></span>
                    <span class="fw-semibold flex-grow-1"><?php echo h($item['name']); ?></span>
                    <?php if ($item['percent'] === 100): ?>
                      <span class="badge badge-pass">ผ่านเกณฑ์ครบ</span>
                    <?php endif; ?>
                    <span class="match-text <?php echo $cls; ?>"><?php echo $item['percent']; ?>%</span>
                  </div>
                  <div class="progress" role="progressbar" aria-label="Match ของ <?php echo h($item['name']); ?>"
                       aria-valuenow="<?php echo $item['percent']; ?>" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar <?php echo $cls; ?>" style="width: <?php echo $item['percent']; ?>%"></div>
                  </div>
                </li>
              <?php endforeach; ?>
            </ol>
          </article>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- ตารางระดับทักษะ -->
    <section class="card p-3 p-lg-4 shadow-sm">
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h2 class="h4 fw-bold mb-0">ระดับทักษะของนักเรียน</h2>
        <div class="d-flex align-items-center gap-1 small text-secondary">
          ระดับคะแนน:
          <?php for ($level = 1; $level <= MAX_SKILL_LEVEL; $level++): ?>
            <span class="lvl lvl-sm lvl-<?php echo $level; ?>"><?php echo $level; ?></span>
          <?php endfor; ?>
        </div>
      </div>
      <div class="table-responsive border rounded-3">
        <table class="table align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th scope="col">นักเรียน</th>
              <?php foreach ($skills as $skillName): ?>
                <th scope="col" class="text-center text-nowrap"><?php echo h($skillName); ?></th>
              <?php endforeach; ?>
              <th scope="col" class="text-nowrap">สายที่เหมาะที่สุด</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $row): ?>
              <tr>
                <th scope="row" class="text-nowrap"><?php echo h($row['student']['name']); ?></th>
                <?php foreach ($skills as $key => $skillName): ?>
                  <?php $level = $row['student']['skills'][$key]; ?>
                  <td class="text-center"><span class="lvl lvl-<?php echo $level; ?>"><?php echo $level; ?></span></td>
                <?php endforeach; ?>
                <td class="text-nowrap">
                  <span class="fw-semibold"><?php echo h($row['best']['name']); ?></span>
                  <span class="match-text <?php echo matchClass($row['best']['percent']); ?>"><?php echo $row['best']['percent']; ?>%</span>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>

  </main>

  <footer class="border-top bg-white mt-5">
    <div class="container py-3 d-flex flex-wrap justify-content-between gap-2 small text-secondary">
      <span>Skill Match Team (Developer A: ณัฐกานต์ / Developer B: นัฏฐนิชา) · วิชา 31903-2008</span>
      <span>อัปเดตล่าสุด: <?php echo date('d/m/Y H:i'); ?></span>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
