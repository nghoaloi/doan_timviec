
<body>
<section class="mt-5 pt-5">
    <div class="container py-5">
        <h2 class="text-center text-uppercase mb-5">Quản lý công việc</h2>
        <div class="row">
            <!-- Mục nhập công việc -->
            <div class="col-md-4 mb-5">
                <div class="card shadow-sm p-4">
                    <h4 class="text-center mb-4">Thêm công việc</h4>
                    <form action="index.php?act=job_add" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="companyID" class="form-label">Tên công ty:</label>
                            <select name="companyID" id="companyID" class="form-select" required>
                                <option value="">Chọn tên công ty</option>
                                <?php
                                // Lấy danh sách công ty từ cơ sở dữ liệu
                                $companies = getCompaniesForJob();
                                if (isset($companies) && count($companies) > 0) {
                                    foreach ($companies as $company) {
                                        echo '<option value="' . $company['CompanyID'] . '">' . $company['CompanyName'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="jobTitle" class="form-label">Tiêu đề công việc:</label>
                            <input type="text" name="jobTitle" id="jobTitle" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="jobDescription" class="form-label">Mô tả công việc:</label>
                            <textarea name="jobDescription" id="jobDescription" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="requirements" class="form-label">Yêu cầu:</label>
                            <textarea name="requirements" id="requirements" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="salaryRange" class="form-label">Mức lương:</label>
                            <input type="text" name="salaryRange" id="salaryRange" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">Vị trí:</label>
                            <input type="text" name="location" id="location" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="employmentType" class="form-label">Loại công việc:</label>
                            <select name="employmentType" id="employmentType" class="form-select" required>
                                <option value="Full-Time">Full-Time</option>
                                <option value="Part-Time">Part-Time</option>
                                <option value="Internship">Internship</option>
                                <option value="Freelance">Freelance</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="expiryDate" class="form-label">Ngày hết hạn:</label>
                            <input type="date" name="expiryDate" id="expiryDate" class="form-control">
                        </div>
                        <input type="submit" name="addJob" class="btn btn-primary w-100" value="Thêm mới">
                    </form>
                </div>
            </div>

            <!-- Mục hiển thị công việc -->
            <div class="col-md-8">
                <div class="card shadow-sm p-4">
                    <h4 class="text-center mb-4">Danh sách công việc</h4>
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">STT</th>
                                    <th scope="col">Tên công ty</th>
                                    <th scope="col">Tiêu đề công việc</th>
                                    <th scope="col">Mô tả</th>
                                    <th scope="col">Yêu cầu</th>
                                    <th scope="col">Mức lương</th>
                                    <th scope="col">Vị trí</th>
                                    <th scope="col">Loại</th>
                                    <th scope="col">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $jobs = getJobs();
                                if (isset($jobs) && count($jobs) > 0) {
                                    $i = 1;
                                    foreach ($jobs as $job) {
                                        echo '<tr>
                                                <th scope="row">'.$i.'</th>
                                                <td>'.$job['CompanyName'].'</td>
                                                <td>'.$job['JobTitle'].'</td>
                                                <td class="text-truncate" style="max-width: 200px;">'.$job['JobDescription'].'</td>
                                                <td class="text-truncate" style="max-width: 150px;">'.$job['Requirements'].'</td>
                                                <td>'.$job['SalaryRange'].'</td>
                                                <td>'.$job['Location'].'</td>
                                                <td>'.$job['EmploymentType'].'</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="index.php?act=editform_job&id='.$job['JobID'].'" class="btn btn-warning btn-sm">Sửa</a>
                                                        <a href="index.php?act=del_job&id='.$job['JobID'].'" class="btn btn-danger btn-sm" onclick="return confirm(\'Bạn có chắc chắn muốn xóa công việc này không?\')">Xóa</a>
                                                    </div>
                                                </td>
                                            </tr>';
                                        $i++;
                                    }
                                } else {
                                    echo '<tr><td colspan="9" class="text-center">Không có công việc nào.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
