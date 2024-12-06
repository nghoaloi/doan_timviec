<section class="mt-5 pt-5">
    <div class="container-fluid py-5 mb-5 mt-5">
        <h2>QUẢN LÝ CÔNG TY</h2>
        <div class="row">
            <!-- Mục nhập -->
            <div class="col-md-4">
                <form action="index.php?act=company_add" method="post" enctype="multipart/form-data" class="d-flex flex-column">
                    <label for="userID" class="mb-1">User ID:</label>
                    <input type="number" name="userID" id="userID" class="mb-3 form-control" required>

                    <label for="companyName" class="mb-1">Tên công ty:</label>
                    <input type="text" name="companyName" id="companyName" class="mb-3 form-control" required>

                    <label for="industry" class="mb-1">Ngành công nghiệp:</label>
                    <input type="text" name="industry" id="industry" class="mb-3 form-control">

                    <label for="websiteURL" class="mb-1">URL Trang web:</label>
                    <input type="url" name="websiteURL" id="websiteURL" class="mb-3 form-control">

                    <label for="logoURL" class="mb-1">URL Logo:</label>
                    <input type="url" name="logoURL" id="logoURL" class="mb-3 form-control">

                    <label for="location" class="mb-1">Vị trí:</label>
                    <input type="text" name="location" id="location" class="mb-3 form-control">

                    <label for="description" class="mb-1">Mô tả:</label>
                    <textarea name="description" id="description" class="mb-3 form-control"></textarea>

                    <input type="submit" name="addCompany" value="Thêm mới" class="btn btn-primary">
                </form>
            </div>

            <!-- Mục hiển thị công ty -->
            <div class="col-md-8">
                <h1>Danh sách công ty</h1>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-bordered mt-2">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">STT</th>
                                <th scope="col">Tên công ty</th>
                                <th scope="col">Ngành công nghiệp</th>
                                <th scope="col">URL Trang web</th>
                                <th scope="col">URL Logo</th>
                                <th scope="col">Vị trí</th>
                                <th scope="col">Mô tả</th>
                                <th scope="col">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // include "model/company.php";
                            $companies = getCompanies();
                            if (isset($companies) && count($companies) > 0) {
                                $i = 1;
                                foreach ($companies as $company) {
                                    echo '<tr>
                                            <th scope="row">'.$i.'</th>
                                            <td>'.$company['CompanyName'].'</td>
                                            <td>'.$company['Industry'].'</td>
                                            <td>'.$company['WebsiteURL'].'</td>
                                            <td>'.$company['LogoURL'].'</td>
                                            <td>'.$company['Location'].'</td>
                                            <td>'.$company['Description'].'</td>
                                            <td>
                                                <a href="index.php?act=editform_company&id='.$company['CompanyID'].'" class="btn btn-warning btn-sm">Sửa</a> 
                                                <a href="index.php?act=del_company&id='.$company['CompanyID'].'" class="btn btn-danger btn-sm" onclick="return confirm(\'Bạn có chắc chắn muốn xóa công ty này không?\')">Xóa</a>
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
</section>
