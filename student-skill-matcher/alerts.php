<?php
// หน้าแจ้งเตือนทั้งหมดของระบบ
require_once 'data.php';

$alerts = buildAlerts($students, $competitions, $skills);
$alertCount = count($alerts); // int

// ระดับความสำคัญ: ชื่อที่แสดง, class ของ Bootstrap, ลำดับการเรียง (น้อย = สำคัญกว่า)
$levels = [
    'danger'  => ['label' => 'เร่งด่วน', 'order' => 1],
    'warning' => ['label' => 'ควรติดตาม', 'order' => 2],
    'info'    => ['label' => 'ข้อมูล',    'order' => 3],
];

// เรียงจากสำคัญมากไปน้อย
usort($alerts, fn($a, $b) => $levels[$a['level']]['order'] <=> $levels[$b['level']]['order']);

// นับจำนวนแต่ละระดับ
$levelCounts = ['danger' => 0, 'warning' => 0, 'info' => 0];
foreach ($alerts as $alert) {
    $levelCounts[$alert['level']]++;
}

$pageTitle = 'การแจ้งเตือน';
$activePage = 'alerts';
require 'includes/header.php';
?>

  <main class="container py-4 py-lg-5">

    <div class="mb-4">
      <h1 class="h3 fw-bold text-navy mb-1">การแจ้งเตือนทั้งหมด</h1>
      <p class="text-secondary mb-0">เรื่องที่ครูควรติดตาม คำนวณจากระดับทักษะของนักเรียนเทียบกับเกณฑ์ของแต่ละสายแข่ง</p>
    </div>

    <!-- สรุปจำนวนตามระดับ -->
    <div class="row row-cols-1 row-cols-sm-3 g-3 mb-4">
      <?php foreach ($levels as $key => $level): ?>
        <div class="col">
          <div class="card count-card lv-<?php echo $key; ?> h-100 p-3">
            <div class="text-secondary fw-medium"><?php echo $level['label']; ?></div>
            <div class="count-value"><?php echo $levelCounts[$key]; ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- รายการแจ้งเตือน -->
    <?php if ($alertCount === 0): ?>
      <div class="alert alert-success mb-0" role="status">ไม่มีการแจ้งเตือน ทุกอย่างพร้อมแล้ว</div>
    <?php else: ?>
      <ul class="list-unstyled d-flex flex-column gap-3 mb-0">
        <?php foreach ($alerts as $alert): ?>
          <li class="card alert-item lv-<?php echo $alert['level']; ?> p-3 shadow-sm">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
              <span class="badge level-pill"><?php echo $levels[$alert['level']]['label']; ?></span>
              <span class="badge text-bg-light border"><?php echo h($alert['category']); ?></span>
            </div>
            <div class="fw-bold"><?php echo h($alert['title']); ?></div>
            <div class="text-secondary"><?php echo h($alert['message']); ?></div>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <p class="mt-4 mb-0"><a href="index.php">← กลับหน้าภาพรวม</a></p>
  </main>

<?php require 'includes/footer.php'; ?>
