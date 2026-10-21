<?php
// ข้อมูลจำลอง + ฟังก์ชันที่ใช้ร่วมกัน อยู่ใน data.php (ซึ่งเรียก config.php ให้แล้ว)
require_once 'data.php';

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

// จำนวนสายแข่งที่ยังไม่มีใครผ่านเกณฑ์ครบ (ดูจากอันดับ 1 ของแต่ละสาย)
$uncoveredCount = 0;
foreach ($compCards as $card) {
    $topPercent = $card['top'][0]['percent'] ?? 0;
    if ($topPercent < 100) {
        $uncoveredCount++;
    }
}

// เงื่อนไขการเตือน: ตัวแทนไม่ถึงเป้า หรือ มีสายที่ยังส่งตัวแทนไม่ได้
$isShortOfTarget = $passedCount < TARGET_REPRESENTATIVES;       // bool
$hasUncoveredCompetition = $uncoveredCount > 0;                 // bool
$isAlertNeeded = $isShortOfTarget || $hasUncoveredCompetition;  // bool

// การแจ้งเตือนทั้งหมด (ใช้นับจำนวนบนเมนูและปุ่ม)
$alerts = buildAlerts($students, $competitions, $skills);
$alertCount = count($alerts); // int

$stats = [
    ['label' => 'นักเรียนทั้งหมด',              'value' => $totalStudents,                     'note' => 'คนที่มีข้อมูลทักษะในระบบ', 'accent' => false],
    ['label' => 'สายการแข่งขัน',               'value' => $totalCompetitions,                 'note' => 'สายที่เปิดรับสมัคร',       'accent' => false],
    ['label' => 'ทักษะที่ประเมิน',              'value' => $totalSkills,                       'note' => 'ระดับ 1–5 ต่อทักษะ',      'accent' => false],
    ['label' => 'ผ่านเกณฑ์ครบอย่างน้อย 1 สาย', 'value' => $passedCount . ' (' . $passedPercent . '%)', 'note' => 'เป้าหมายอย่างน้อย ' . TARGET_REPRESENTATIVES . ' คน', 'accent' => true],
];

$pageTitle = 'ภาพรวมการจับคู่ทักษะ';
$activePage = 'index';
require 'includes/header.php';
?>

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
          <strong>แจ้งเตือนความพร้อม:</strong>
          <?php if ($isShortOfTarget): ?>
            ขณะนี้มีนักเรียนผ่านเกณฑ์ครบ 100% จำนวน <strong><?php echo $passedCount; ?> คน</strong> (เป้าหมายคืออย่างน้อย <?php echo TARGET_REPRESENTATIVES; ?> คน) ยังขาดอีก <strong><?php echo $neededStudents; ?> คน</strong>
          <?php endif; ?>
          <?php if ($hasUncoveredCompetition): ?>
            มี <strong><?php echo $uncoveredCount; ?> สาย</strong> ที่ยังไม่มีผู้ผ่านเกณฑ์ครบ
          <?php endif; ?>
          กรุณาเปิดติวเสริมทักษะเพิ่มเติม
        </div>
        <a href="alerts.php" class="btn btn-sm btn-outline-dark ms-md-2 text-nowrap">ดูแจ้งเตือนทั้งหมด (<?php echo $alertCount; ?>) →</a>
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
                  <?php $level = skillLevel($row['student'], $key); ?>
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

<?php require 'includes/footer.php'; ?>
