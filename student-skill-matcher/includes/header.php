<?php
// ส่วนหัวของทุกหน้า: <head> + แถบเมนู
// ก่อน require ไฟล์นี้ ให้กำหนด $pageTitle (ชื่อแท็บ) และ $activePage (index / alerts / about / server)
require_once __DIR__ . '/../data.php';

$pageTitle = $pageTitle ?? APP_NAME;
$activePage = $activePage ?? '';

// จำนวนแจ้งเตือนบนเมนู นับจากข้อมูลจริงทุกหน้า
$navAlertCount = count(buildAlerts($students, $competitions, $skills));

$navItems = [
    'index'  => ['href' => 'index.php',  'label' => 'หน้าแรก'],
    'alerts' => ['href' => 'alerts.php', 'label' => 'การแจ้งเตือน'],
    'about'  => ['href' => 'about.php',  'label' => 'เกี่ยวกับเรา'],
    'server' => ['href' => 'server.php', 'label' => 'ข้อมูลเซิร์ฟเวอร์'],
];
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo h($pageTitle); ?> – <?php echo APP_NAME; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
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
          <?php foreach ($navItems as $key => $item): ?>
            <?php $isActive = $key === $activePage; ?>
            <li class="nav-item">
              <a class="nav-link<?php echo $isActive ? ' active' : ''; ?>" href="<?php echo $item['href']; ?>"<?php echo $isActive ? ' aria-current="page"' : ''; ?>>
                <?php echo $item['label']; ?>
                <?php if ($key === 'alerts' && $navAlertCount > 0): ?>
                  <span class="badge rounded-pill bg-danger ms-1"><?php echo $navAlertCount; ?></span>
                <?php endif; ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </nav>
