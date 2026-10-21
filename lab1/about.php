<?php
$topic = 'Student Skill Matcher (ระบบจับคู่ทักษะนักเรียนกับสายการแข่งขัน)';
$goals = [
    'เพื่อเป็นศูนย์กลางในการบันทึกและประเมินระดับทักษะความถนัดของนักเรียน',
    'เพื่อช่วยอาจารย์จับคู่นักเรียนที่มีทักษะตรงกับเกณฑ์การแข่งขันได้อย่างรวดเร็วและแม่นยำ',
    'เพื่อเปิดโอกาสให้นักเรียนทุกคนได้รับการคัดเลือกอย่างโปร่งใสตามความสามารถจริง'
];
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>เกี่ยวกับเรา - <?php echo $topic; ?></title>
  <style>
    body { font-family: 'Sarabun', Tahoma, sans-serif; margin: 2rem; line-height: 1.6; }
    nav a { text-decoration: none; color: #0066cc; font-weight: bold; margin: 0 5px; }
    nav a:hover { text-decoration: underline; }
    ol li { margin-bottom: 8px; }
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

  <h1>เกี่ยวกับโครงงาน</h1>
  <h2><?php echo $topic; ?></h2>

  <h3>เป้าหมายของระบบ (ทั้งหมด <?php echo count($goals); ?> ข้อ)</h3>
  <ol>
    <?php foreach ($goals as $goal): ?>
      <li><?php echo $goal; ?></li>
    <?php endforeach; ?>
  </ol>

  <hr>
  <p><em>อัปเดตล่าสุด: <?php echo date('d/m/Y H:i'); ?></em></p>
</body>
</html>
