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

$pageTitle = 'เกี่ยวกับเรา';
$activePage = 'about';
require 'includes/header.php';
?>

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
          <li class="mb-2"><?php echo h($goal); ?></li>
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
                <span class="fw-bold"><?php echo h($m['name']); ?></span>
                <span class="badge bg-navy"><?php echo h($m['role']); ?></span>
              </div>
              <div class="small text-secondary"><?php echo h($m['desc']); ?></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </main>

<?php require 'includes/footer.php'; ?>
