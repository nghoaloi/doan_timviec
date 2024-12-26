<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Công Việc</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        h2 {
            color: #343a40;
        }
        .container-fluid {
            background-color: #fff;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-control, .form-select {
            height: 38px;
            font-size: 14px;
        }
        .form-textarea {
            font-size: 14px;
        }
        .btn {
            border-radius: 5px;
        }
        .table th {
            background-color: #343a40;
            color: #fff;
        }
        .table td, .table th {
            vertical-align: middle;
        }
        .card-header {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
    </style>
</head>
<body>
<section class="mt-5 pt-5">
    <div class="container-fluid py-5">
        <h2 class="text-center text-uppercase mb-4">Quản lý công việc</h2>
        <div class="row gx-5">
            <!-- Form Thêm công việc -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Thêm công việc mới</h5>
                    </div>
                    <div class="card-body">
                        <form action="index.php?act=job_add" method="post">
                            <div class="mb-2">
                                <label for="companyID" class="form-label">Tên công ty:</label>
                                <select name="companyID" id="companyID" class="form-select" required>
                                    <option value="">Chọn tên công ty</option>
                                    <?php foreach (getCompaniesForJob() as $company): ?>
                                        <option value="<?= $company['CompanyID'] ?>"><?= $company['CompanyName'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label for="jobTitle" class="form-label">Tiêu đề công việc:</label>
                                <input type="text" name="jobTitle" id="jobTitle" class="form-control" required>
                            </div>
                            <div class="mb-2">
                                <label for="jobDescription" class="form-label">Mô tả công việc:</label>
                                <textarea name="jobDescription" id="jobDescription" class="form-control form-textarea" rows="2" required></textarea>
                            </div>
                            <div class="mb-2">
                                <label for="requirements" class="form-label">Yêu cầu:</label>
                                <textarea name="requirements" id="requirements" class="form-control form-textarea" rows="2"></textarea>
                            </div>
                            <div class="mb-2">
                                <label for="salaryRange" class="form-label">Mức lương:</label>
                                <input type="text" name="salaryRange" id="salaryRange" class="form-control">
                            </div>
                            <div class="mb-2">
                                <label for="location" class="form-label">Địa điểm:</label>
                                <input type="text" name="location" id="location" class="form-control">
                            </div>
                            <div class="mb-2">
                                <label for="employmentType" class="form-label">Loại công việc:</label>
                                <select name="employmentType" id="employmentType" class="form-select" required>
                                    <option value="Full-Time">Toàn thời gian</option>
                                    <option value="Part-Time">Bán thời gian</option>
                                    <option value="Internship">Thực tập</option>
                                    <option value="Freelance">Freelance</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label for="expiryDate" class="form-label">Ngày hết hạn:</label>
                                <input type="date" name="expiryDate" id="expiryDate" class="form-control">
                            </div>
                            <button type="submit" name="addJob" class="btn btn-primary w-100">Thêm mới</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Danh sách công việc -->
            <div class="col-lg-8">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-dark text-white">
                        <h5 class="card-title mb-0">Danh sách công việc</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Công ty</th>
                                        <th>Tiêu đề</th>
                                        <th>Mô tả</th>
                                        <th>Yêu cầu</th>
                                        <th>Mức lương</th>
                                        <th>Địa điểm</th>
                                        <th>Loại công việc</th>
                                        <th>Ngày hết hạn</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (getJobs() as $index => $job): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= $job['CompanyName'] ?></td>
                                            <td><?= $job['JobTitle'] ?></td>
                                            <td><?= $job['JobDescription'] ?></td>
                                            <td><?= $job['Requirements'] ?></td>
                                            <td><?= $job['SalaryRange'] ?></td>
                                            <td><?= $job['Location'] ?></td>
                                            <td><?= $job['EmploymentType'] ?></td>
                                            <td><?= $job['ExpiryDate'] ?></td>
                                            <td>
                                                <a href="index.php?act=editform_job&id=<?= $job['JobID'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                                                <a href="index.php?act=del_job&id=<?= $job['JobID'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa công việc này không?')">Xóa</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty(getJobs())): ?>
                                        <tr>
                                            <td colspan="10" class="text-center">Không có công việc nào.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
