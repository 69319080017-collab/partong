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
  <title><?php echo $topic; ?></title>
  <style>
    body { font-family: 'Sarabun', Tahoma, sans-serif; margin: 2rem; line-height: 1.6; }
    nav a { text-decoration: none; color: #0066cc; font-weight: bold; margin: 0 5px; }
    nav a:hover { text-decoration: underline; }
  </style>
</head>
<body>
  <!-- แถบเมนูนำทาง (nav) ตามโจทย์ข้อ ฉ -->
  <nav>
    <a href="index.php">หน้าแรก</a> | 
    <a href="about.php">เกี่ยวกับเรา</a> | 
    <a href="server.php">ข้อมูลเซิร์ฟเวอร์</a>
  </nav>

  <hr>

  <h1><?php echo $topic; ?></h1>
  <p><strong>ทีม:</strong> <?php echo $team; ?></p>
  <p><strong>ปัญหาที่แก้:</strong> <?php echo $problem; ?></p>
  
  <h2>สมาชิก</h2>
  <ul>
    <?php foreach ($members as $m): ?>
      <li><?php echo $m; ?></li>
    <?php endforeach; ?>
  </ul>
  
  <hr>
  <p><em>อัปเดตล่าสุด: <?php echo date('d/m/Y H:i'); ?></em></p>
</body>
</html>
