<?php
require_once 'config.php';

$pageTitle = 'ข้อมูลเซิร์ฟเวอร์';
$activePage = 'server';
require 'includes/header.php';
?>

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

<?php require 'includes/footer.php'; ?>
