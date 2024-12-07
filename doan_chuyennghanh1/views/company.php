<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Công Ty</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<section class="mt-5 pt-5">
    <div class="container-fluid py-5">
        <h2 class="text-center mb-4">QUẢN LÝ CÔNG TY</h2>
        <div class="row">
            <!-- Form nhập công ty -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Thêm Công Ty Mới</h5>
                    </div>
                    <div class="card-body">
                        <form action="index.php?act=company_add" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="userID" class="form-label">Họ và tên:</label>
                                <select name="userID" id="userID" class="form-select" required>
                                    <option value="">Chọn họ và tên</option>
                                    <?php
                                    // Lấy danh sách người dùng từ cơ sở dữ liệu
                                    $users = getUsersByUserType();
                                    if (isset($users) && count($users) > 0) {
                                        foreach ($users as $user) {
                                            echo '<option value="' . $user['UserID'] . '">' . $user['FullName'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="companyName" class="form-label">Tên công ty:</label>
                                <input type="text" name="companyName" id="companyName" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="industry" class="form-label">Ngành công nghiệp:</label>
                                <input type="text" name="industry" id="industry" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="websiteURL" class="form-label">URL Trang web:</label>
                                <input type="url" name="websiteURL" id="websiteURL" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="logoURL" class="form-label">Logo công ty:</label>
                                <input type="file" name="logoURL" id="logoURL" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="location" class="form-label">Vị trí:</label>
                                <input type="text" name="location" id="location" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả:</label>
                                <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                            </div>

                            <button type="submit" name="addCompany" class="btn btn-primary w-100">Thêm mới</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Danh sách công ty -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="card-title mb-0">Danh Sách Công Ty</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">STT</th>
                                        <th scope="col">Tên công ty</th>
                                        <th scope="col">Ngành công nghiệp</th>
                                        <th scope="col">URL Trang web</th>
                                        <th scope="col">Logo công ty</th>
                                        <th scope="col">Vị trí</th>
                                        <th scope="col">Mô tả</th>
                                        <th scope="col">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $companies = getCompanies();
                                    if (isset($companies) && count($companies) > 0) {
                                        $i = 1;
                                        foreach ($companies as $company) {
                                            $logoPath = !empty($company['LogoURL']) ? 'uploads/' . $company['LogoURL'] : 'https://via.placeholder.com/100';
                                            echo '<tr>
                                                    <th scope="row">' . $i . '</th>
                                                    <td>' . htmlspecialchars($company['CompanyName']) . '</td>
                                                    <td>' . htmlspecialchars($company['Industry']) . '</td>
                                                    <td><a href="' . htmlspecialchars($company['WebsiteURL']) . '" target="_blank">Xem trang</a></td>
                                                    <td><img src="' . htmlspecialchars($logoPath) . '" alt="Logo" width="50" height="50" class="rounded-circle"></td>
                                                    <td>' . htmlspecialchars($company['Location']) . '</td>
                                                    <td>' . htmlspecialchars($company['Description']) . '</td>
                                                    <td>
                                                        <a href="index.php?act=editform_company&id=' . $company['CompanyID'] . '" class="btn btn-warning btn-sm">Sửa</a>
                                                        <a href="index.php?act=del_company&id=' . $company['CompanyID'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Bạn có chắc chắn muốn xóa công ty này không?\')">Xóa</a>
                                                    </td>
                                                </tr>';
                                            $i++;
                                        }
                                    } else {
                                        echo '<tr><td colspan="8" class="text-center">Không có công ty nào.</td></tr>';
                                    }
                                    ?>
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
