<section class="mt-5 pt-5">
    <div class="container-fluid py-5 mb-5 mt-5">
        <h2>QUẢN LÝ CÔNG VIỆC</h2>
        <div class="row">
            <!-- Mục nhập công việc -->
            <div class="col-md-4">
                <form action="index.php?act=job_add" method="post" enctype="multipart/form-data" class="d-flex flex-column">
                    <label for="companyID" class="mb-1">Company ID:</label>
                    <input type="number" name="companyID" id="companyID" class="mb-3 form-control" required>

                    <label for="jobTitle" class="mb-1">Tiêu đề công việc:</label>
                    <input type="text" name="jobTitle" id="jobTitle" class="mb-3 form-control" required>

                    <label for="jobDescription" class="mb-1">Mô tả công việc:</label>
                    <textarea name="jobDescription" id="jobDescription" class="mb-3 form-control" required></textarea>

                    <label for="requirements" class="mb-1">Yêu cầu:</label>
                    <textarea name="requirements" id="requirements" class="mb-3 form-control"></textarea>

                    <label for="salaryRange" class="mb-1">Mức lương:</label>
                    <input type="text" name="salaryRange" id="salaryRange" class="mb-3 form-control">

                    <label for="location" class="mb-1">Vị trí:</label>
                    <input type="text" name="location" id="location" class="mb-3 form-control">

                    <label for="employmentType" class="mb-1">Loại công việc:</label>
                    <select name="employmentType" id="employmentType" class="mb-3 form-control" required>
                        <option value="Full-Time">Full-Time</option>
                        <option value="Part-Time">Part-Time</option>
                        <option value="Internship">Internship</option>
                        <option value="Freelance">Freelance</option>
                    </select>

                    <label for="expiryDate" class="mb-1">Ngày hết hạn:</label>
                    <input type="date" name="expiryDate" id="expiryDate" class="mb-3 form-control">

                    <input type="submit" name="addJob" value="Thêm mới" class="btn btn-primary">
                </form>
            </div>

            <!-- Mục hiển thị công việc -->
            <div class="col-md-8">
                <h1>Danh sách công việc</h1>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-bordered mt-2">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">STT</th>
                                <th scope="col">Tên công ty</th>
                                <th scope="col">Tiêu đề công việc</th>
                                <th scope="col">Mô tả công việc</th>
                                <th scope="col">Yêu cầu</th>
                                <th scope="col">Mức lương</th>
                                <th scope="col">Vị trí</th>
                                <th scope="col">Loại công việc</th>
                                <th scope="col">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // include "model/job.php";
                            $jobs = getJobs();
                            if (isset($jobs) && count($jobs) > 0) {
                                $i = 1;
                                foreach ($jobs as $job) {
                                    echo '<tr>
                                            <th scope="row">'.$i.'</th>
                                            <td>'.$job['CompanyID'].'</td>
                                            <td>'.$job['JobTitle'].'</td>
                                            <td>'.$job['JobDescription'].'</td>
                                            <td>'.$job['Requirements'].'</td>
                                            <td>'.$job['SalaryRange'].'</td>
                                            <td>'.$job['Location'].'</td>
                                            <td>'.$job['EmploymentType'].'</td>
                                            <td>
                                                <a href="index.php?act=editform_job&id='.$job['JobID'].'" class="btn btn-warning btn-sm">Sửa</a> 
                                                <a href="index.php?act=del_job&id='.$job['JobID'].'" class="btn btn-danger btn-sm" onclick="return confirm(\'Bạn có chắc chắn muốn xóa công việc này không?\')">Xóa</a>
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
</section>
