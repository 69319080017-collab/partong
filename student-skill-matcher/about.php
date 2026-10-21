<?php
require_once 'config.php';

$topic = 'Student Skill Matcher (ระบบจับคู่ทักษะนักเรียนกับสายการแข่งขัน)';
$problem = 'ช่วยแก้ปัญหาการคัดเลือกตัวแทนแข่งขันที่ไม่ตรงกับความถนัดจริง และขาดศูนย์กลางข้อมูลทักษะของนักเรียน';

$members = [
    ['name' => 'นายณัฐกานต์ บัวทุม', 'role' => 'Developer A', 'desc' => 'ดูแลฝั่งข้อมูลนักเรียนและระบบประมวลผล Match'],
    ['name' => 'นางสาวนัฏฐนิชา บุญมั่น', 'role' => 'Developer B', 'desc' => 'ดูแลฝั่งสายการแข่งขันและระบบผู้ดูแลระบบ (Admin)'],
];

$goals = [
    'เพื่อเป็นศูนย์กลางในการบันทึกและประเมินระดับทักษะความถนัดของนักเรียนอย่างเป็นระบบ',
    'เพื่อช่วยอาจารย์จับคู่นักเรียนที่มีทักษะตรงกับเกณฑ์การแข่งขันได้อย่างรวดเร็ว แม่นยำ และโปร่งใส',
    'เพื่อเปิดโอกาสให้นักเรียนทุกคนได้รับการคัดเลือกตามความสามารถจริง และมีเป้าหมายในการพัฒนาทักษะตนเอง'
];
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>เกี่ยวกับเรา – <?php echo APP_NAME; ?></title>
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
            <a class="nav-link position-relative" href="alerts.php">
              การแจ้งเตือน
              <span class="badge rounded-pill bg-danger ms-1">5</span>
            </a>
          </li>
          <li class="nav-item"><a class="nav-link active" aria-current="page" href="about.php">เกี่ยวกับเรา</a></li>
          <li class="nav-item"><a class="nav-link" href="server.php">ข้อมูลเซิร์ฟเวอร์</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <main class="container py-4 py-lg-5">
    <div class="mb-4">
      <h1 class="h3 fw-bold text-navy mb-1">เกี่ยวกับโครงงาน</h1>
      <p class="text-secondary"><?php echo $topic; ?></p>
    </div>

    <!-- ปัญหาที่ระบบแก้ -->
    <div class="card p-4 mb-4 shadow-sm border-start border-4 border-danger">
      <h2 class="h5 fw-bold text-navy mb-2">📌 ปัญหาที่ระบบนี้แก้</h2>
      <p class="mb-0 text-secondary"><?php echo $problem; ?></p>
    </div>

    <!-- เป้าหมายของระบบ -->
    <div class="card p-4 mb-4 shadow-sm">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h5 fw-bold text-navy mb-0">🎯 เป้าหมายของระบบ</h2>
        <span class="badge bg-primary">ทั้งหมด <?php echo count($goals); ?> ข้อ</span>
      </div>
      <ol class="mb-0 ps-3 text-secondary">
        <?php foreach ($goals as $goal): ?>
          <li class="mb-2"><?php echo htmlspecialchars($goal, ENT_QUOTES, 'UTF-8'); ?></li>
        <?php endforeach; ?>
      </ol>
    </div>

    <!-- สมาชิกในทีม -->
    <div class="card p-4 mb-4 shadow-sm">
      <h2 class="h5 fw-bold text-navy mb-3">👥 สมาชิกในทีม (Skill Match Team)</h2>
      <div class="row row-cols-1 row-cols-md-2 g-3">
        <?php foreach ($members as $m): ?>
          <div class="col">
            <div class="border rounded-3 p-3 bg-light">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="fw-bold"><?php echo htmlspecialchars($m['name'], ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="badge bg-navy text-white bg-dark"><?php echo $m['role']; ?></span>
              </div>
              <div class="small text-secondary"><?php echo htmlspecialchars($m['desc'], ENT_QUOTES, 'UTF-8'); ?></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </main>

  <footer class="border-top bg-white mt-5">
    <div class="container py-3 d-flex flex-wrap justify-content-between gap-2 small text-secondary">
      <span>Skill Match Team · วิชา 31903-2008 การพัฒนาเว็บแอปพลิเคชันด้วย PHP</span>
      <span>อัปเดตล่าสุด: <?php echo date('d/m/Y H:i'); ?></span>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
