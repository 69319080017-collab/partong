<?php
date_default_timezone_set('Asia/Bangkok'); // ถ้าตั้งใน php.ini แล้ว ไม่ต้องมีบรรทัดนี้
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ข้อมูลเซิร์ฟเวอร์</title>
  <style>
    body { font-family: 'Sarabun', Tahoma, sans-serif; margin: 2rem; line-height: 1.6; }
    nav a { text-decoration: none; color: #0066cc; font-weight: bold; margin: 0 5px; }
    nav a:hover { text-decoration: underline; }
    table { border-collapse: collapse; width: 600px; margin-top: 15px; }
    th, td { border: 1px solid #ccc; padding: 8px 12px; text-align: left; }
    th { background-color: #f2f2f2; }
  </style>
</head>
<body>
  <!-- แถบเมนูนำทาง (nav) -->
  <nav>
    <a href="index.php">หน้าแรก</a> | 
    <a href="about.php">เกี่ยวกับเรา</a> | 
    <a href="server.php">ข้อมูลเซิร์ฟเวอร์</a>
  </nav>

  <hr>

  <h1>เซิร์ฟเวอร์ของทีม</h1>
  <table>
    <tr><th>รายการ</th><th>ค่า</th></tr>
    <tr><td>เวอร์ชัน PHP</td><td><?php echo PHP_VERSION; ?></td></tr>
    <tr><td>ซอฟต์แวร์เว็บเซิร์ฟเวอร์</td><td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td></tr>
    <tr><td>ชื่อเครื่อง:พอร์ต</td><td><?php echo $_SERVER['HTTP_HOST']; ?></td></tr>
    <tr><td>Timezone</td><td><?php echo date_default_timezone_get(); ?></td></tr>
    <tr><td>เวลาบนเซิร์ฟเวอร์</td><td><?php echo date('d/m/Y H:i:s'); ?></td></tr>
  </table>

  <hr>
  <p><em>อัปเดตล่าสุด: <?php echo date('d/m/Y H:i'); ?></em></p>
</body>
</html>
