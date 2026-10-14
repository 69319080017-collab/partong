<?php
$team = 'partong';
$topic = 'ระบบจับคู่ทักษะนักเรียน';
$problem = [
    'ครูไม่มีข้อมูลกลางว่าใครเก่งด้านไหน ต้องนึกเอาเองเวลาคัดเลือกนักเรียนไปแข่ง',
    'การคัดเลือกไม่โปร่งใส ขึ้นอยู่กับความคุ้นเคยส่วนตัว ไม่ใช่ความสามารถจริง',
    'นักเรียนไม่รู้ว่าตัวเองเหมาะกับสายแข่งไหน และต้องพัฒนาทักษะอะไรเพิ่ม',
    'ข้อมูลกระจายอยู่ใน Excel / กระดาษ / Line ไม่มีที่รวมศูนย์'
];
$members = ['ณัฐกานต์ บัวทุม (Developer A)', 'นัฎฐนิชา บุญมั่น (Developer B)'];
?>
<!DOCTYPE html>
<html lang="th">
<head><meta charset="UTF-8"><title><?php echo $topic; ?></title></head>
<body>
  <h1><?php echo $topic; ?></h1>
  <p>ทีม: <?php echo $team; ?></p>
  <ul>
    <?php foreach ($problem as $p): ?>
      <li><?php echo $p; ?></li>
    <?php endforeach; ?>
  </ul>
  <h2>สมาชิก</h2>
  <ul>
    <?php foreach ($members as $m): ?>
      <li><?php echo $m; ?></li>
    <?php endforeach; ?>
  </ul>
  <p>อัปเดตล่าสุด: <?php echo date('d/m/Y H:i'); ?></p>
</body>
</html>
