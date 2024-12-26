<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Công Việc và Ứng Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
  <h2 class="text-center mb-4">Danh sách công việc và số lượng ứng viên</h2>
  <div class="d-flex justify-content-end mb-3">
    <div class="d-flex align-items-center">
      <label for="sortSelect" class="form-label mb-0 me-2">Sắp xếp theo:</label>
      <select id="sortSelect" class="form-select form-select-sm w-auto">
        <option value="DESC">Giảm dần</option>
        <option value="ASC">Tăng dần</option>
      </select>
    </div>
  </div>
  <table class="table table-bordered mt-3">
    <thead class="table-primary text-center">
      <tr>
        <th>STT</th>
        <th>Tên công việc</th>
        <th>Tên công ty</th>
        <th>Số lượng user</th>
        <th>Mô tả công việc</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sortOrder = isset($_GET['sort']) ? $_GET['sort'] : 'DESC';
      $jobs = getTopJobs($sortOrder);
      foreach ($jobs as $index => $job) {
          echo "<tr>";
          echo "<td class='text-center'>" . ($index + 1) . "</td>";
          echo "<td>" . htmlspecialchars($job['Tên công việc']) . "</td>";
          echo "<td>" . htmlspecialchars($job['Tên công ty']) . "</td>";
          echo "<td class='text-center'>" . htmlspecialchars($job['Số lượng user']) . "</td>";
          echo "<td>" . htmlspecialchars($job['Mô tả công việc']) . "</td>";
          echo "</tr>";
      }
      ?>
    </tbody>
  </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.getElementById('sortSelect').addEventListener('change', function() {
    const sortValue = this.value;
    window.location.href = `?sort=${sortValue}`;
  });
</script>
</body>
</html>
