<?php
$allowedSortColumns = ['u.FullName ASC', 'u.FullName DESC', 'a.AppliedAt ASC', 'a.AppliedAt DESC'];
$sortColumn = isset($_GET['sort']) && in_array($_GET['sort'], $allowedSortColumns) ? $_GET['sort'] : 'u.FullName ASC';

$applications = getApplycation_Accepted($sortColumn);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Người Dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <h2 class="text-center mb-4">Danh sách người dùng đã ứng tuyển</h2>
    <div class="d-flex justify-content-end mb-3">
        <div class="d-flex align-items-center">
            <label for="sortSelect" class="form-label mb-0 me-2">Sắp xếp theo:</label>
            <select id="sortSelect" class="form-select-sm w-auto">
                <option value="u.FullName ASC" <?= $sortColumn === 'u.FullName ASC' ? 'selected' : '' ?>>Họ tên (A-Z)</option>
                <option value="u.FullName DESC" <?= $sortColumn === 'u.FullName DESC' ? 'selected' : '' ?>>Họ tên (Z-A)</option>
                <option value="a.AppliedAt DESC" <?= $sortColumn === 'a.AppliedAt DESC' ? 'selected' : '' ?>>Ngày ứng tuyển (Mới nhất)</option>
                <option value="a.AppliedAt ASC" <?= $sortColumn === 'a.AppliedAt ASC' ? 'selected' : '' ?>>Ngày ứng tuyển (Cũ nhất)</option>
            </select>
        </div>
    </div>
    <table class="table table-hover align-middle">
        <thead class="table-primary text-center">
            <tr>
                <th>STT</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Công việc đã ứng tuyển</th>
                <th>Ngày ứng tuyển</th>
                <th>Trạng thái</th>
            </tr>
        </thead>
        <tbody id="applicationTable">
            <?php foreach ($applications as $index => $application): ?>
                <tr>
                    <td class="text-center"><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($application['Tên User']) ?></td>
                    <td><?= htmlspecialchars($application['Email']) ?></td>
                    <td><?= htmlspecialchars($application['Số điện thoại']) ?></td>
                    <td><?= htmlspecialchars($application['Công việc đã ứng tuyển']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($application['Ngày ứng tuyển']) ?></td>
                    <td class="text-center">
                        <span class="badge bg-success"><?= htmlspecialchars($application['Trạng thái ứng tuyển']) ?></span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('sortSelect').addEventListener('change', function() {
        const sortValue = this.value;
        window.location.href = `?sort=${encodeURIComponent(sortValue)}`;
    });
</script>
</body>
</html>
