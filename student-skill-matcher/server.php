<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ข้อมูลเซิร์ฟเวอร์ – <?php echo APP_NAME; ?></title>
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
          <li class="nav-item"><a class="nav-link" href="about.php">เกี่ยวกับเรา</a></li>
          <li class="nav-item"><a class="nav-link active" aria-current="page" href="server.php">ข้อมูลเซิร์ฟเวอร์</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <main class="container py-4 py-lg-5">
    <div class="mb-4">
      <h1 class="h3 fw-bold text-navy mb-1">ข้อมูลเซิร์ฟเวอร์ของทีม</h1>
      <p class="text-secondary">สภาพแวดล้อมการทำงานของระบบ <?php echo APP_NAME; ?></p>
    </div>

    <div class="card p-4 shadow-sm">
      <div class="table-responsive">
        <table class="table table-bordered align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th scope="col" style="width: 40%;">รายการสภาพแวดล้อม</th>
              <th scope="col">ค่าที่เซิร์ฟเวอร์รายงาน</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><strong>เวอร์ชัน PHP (PHP_VERSION)</strong></td>
              <td><span class="badge bg-primary fs-6"><?php echo PHP_VERSION; ?></span></td>
            </tr>
            <tr>
              <td><strong>ซอฟต์แวร์เว็บเซิร์ฟเวอร์ ($_SERVER['SERVER_SOFTWARE'])</strong></td>
              <td><?php echo htmlspecialchars($_SERVER['SERVER_SOFTWARE'] ?? 'Apache', ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <tr>
              <td><strong>ชื่อเครื่องและพอร์ต ($_SERVER['HTTP_HOST'])</strong></td>
              <td><code><?php echo htmlspecialchars($_SERVER['HTTP_HOST'] ?? 'localhost', ENT_QUOTES, 'UTF-8'); ?></code></td>
            </tr>
            <tr>
              <td><strong>เขตเวลาเริ่มต้น (date_default_timezone_get)</strong></td>
              <td><span class="badge bg-success"><?php echo date_default_timezone_get(); ?></span></td>
            </tr>
            <tr>
              <td><strong>เวลาปัจจุบันบนเซิร์ฟเวอร์</strong></td>
              <td><?php echo date('d/m/Y H:i:s'); ?></td>
            </tr>
          </tbody>
        </table>
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
