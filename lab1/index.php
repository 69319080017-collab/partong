<?php
$team = 'Skill Match Team';
$topic = 'Student Skill Matcher (ระบบจับคู่ทักษะนักเรียนกับสายการแข่งขัน)';
$problem = 'ช่วยแก้ปัญหาการคัดเลือกตัวแทนแข่งขันที่ไม่ตรงกับความถนัดจริง และขาดศูนย์กลางข้อมูลทักษะของนักเรียน';
$members = [
    'นายณัฐกานต์ บัวทุม (Developer A)',
    'นางสาวนัฏฐนิชา บุญมั่น (Developer B)'
];
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $topic; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { font-family: 'Sarabun', Tahoma, sans-serif; }
  </style>
</head>
<body>

  <!-- แถบเมนูนำทาง (Bootstrap navbar) -->
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container">
      <a class="navbar-brand fw-bold" href="index.php"><?php echo $team; ?></a>

      <button class="navbar-toggler" type="button"
              data-bs-toggle="collapse" data-bs-target="#mainNavbar"
              aria-controls="mainNavbar" aria-expanded="false" aria-label="เปิดเมนู">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="mainNavbar">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link active" href="index.php">หน้าแรก</a></li>
          <li class="nav-item"><a class="nav-link" href="about.php">เกี่ยวกับเรา</a></li>
          <li class="nav-item"><a class="nav-link" href="server.php">ข้อมูลเซิร์ฟเวอร์</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <main class="container my-4">
    <h1 class="h3 mb-3"><?php echo $topic; ?></h1>

    <div class="card mb-4">
      <div class="card-body">
        <p class="mb-2"><strong>ทีม:</strong> <?php echo $team; ?></p>
        <p class="mb-0"><strong>ปัญหาที่แก้:</strong> <?php echo $problem; ?></p>
      </div>
    </div>

    <h2 class="h5">สมาชิก</h2>
    <ul class="list-group mb-4">
      <?php foreach ($members as $m): ?>
        <li class="list-group-item"><?php echo $m; ?></li>
      <?php endforeach; ?>
    </ul>

    <p class="text-muted"><em>อัปเดตล่าสุด: <?php echo date('d/m/Y H:i'); ?></em></p>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>