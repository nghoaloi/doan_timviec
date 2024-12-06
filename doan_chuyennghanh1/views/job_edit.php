<section class="mt-5 pt-5">
    <div class="container-fluid py-5 mb-5 mt-5">
        <h2>CHỈNH SỬA THÔNG TIN CÔNG VIỆC</h2>
        <div class="row">
            <!-- Form chỉnh sửa công việc -->
            <div class="col-md-6 offset-md-3">
                <form action="index.php?act=job_edit" method="post" enctype="multipart/form-data" class="d-flex flex-column">
                    <input type="hidden" name="jobID" value="<?php echo $job['JobID']; ?>">

                    <label for="companyID" class="mb-1">Company ID:</label>
                    <input type="number" name="companyID" id="companyID" class="mb-3 form-control" value="<?php echo $job['CompanyID']; ?>" required>

                    <label for="jobTitle" class="mb-1">Tiêu đề công việc:</label>
                    <input type="text" name="jobTitle" id="jobTitle" class="mb-3 form-control" value="<?php echo $job['JobTitle']; ?>" required>

                    <label for="jobDescription" class="mb-1">Mô tả công việc:</label>
                    <textarea name="jobDescription" id="jobDescription" class="mb-3 form-control" required><?php echo $job['JobDescription']; ?></textarea>

                    <label for="requirements" class="mb-1">Yêu cầu:</label>
                    <textarea name="requirements" id="requirements" class="mb-3 form-control"><?php echo $job['Requirements']; ?></textarea>

                    <label for="salaryRange" class="mb-1">Mức lương:</label>
                    <input type="text" name="salaryRange" id="salaryRange" class="mb-3 form-control" value="<?php echo $job['SalaryRange']; ?>">

                    <label for="location" class="mb-1">Vị trí:</label>
                    <input type="text" name="location" id="location" class="mb-3 form-control" value="<?php echo $job['Location']; ?>">

                    <label for="employmentType" class="mb-1">Loại công việc:</label>
                    <select name="employmentType" id="employmentType" class="mb-3 form-control" required>
                        <option value="Full-Time" <?php echo ($job['EmploymentType'] == 'Full-Time') ? 'selected' : ''; ?>>Full-Time</option>
                        <option value="Part-Time" <?php echo ($job['EmploymentType'] == 'Part-Time') ? 'selected' : ''; ?>>Part-Time</option>
                        <option value="Internship" <?php echo ($job['EmploymentType'] == 'Internship') ? 'selected' : ''; ?>>Internship</option>
                        <option value="Freelance" <?php echo ($job['EmploymentType'] == 'Freelance') ? 'selected' : ''; ?>>Freelance</option>
                    </select>

                    <label for="expiryDate" class="mb-1">Ngày hết hạn:</label>
                    <input type="date" name="expiryDate" id="expiryDate" class="mb-3 form-control" value="<?php echo $job['ExpiryDate']; ?>">

                    <input type="submit" name="updateJob" value="Lưu thay đổi" class="btn btn-primary">
                    <a href="index.php?act=job" class="btn btn-secondary mt-2">Hủy</a>
                </form>
            </div>
        </div>
    </div>
</section>
