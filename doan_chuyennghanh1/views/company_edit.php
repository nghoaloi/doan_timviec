<section class="mt-5 pt-5">
    <div class="container-fluid py-5 mb-5 mt-5">
        <h2>CHỈNH SỬA THÔNG TIN CÔNG TY</h2>
        <div class="row">
            <!-- Form chỉnh sửa công ty -->
            <div class="col-md-6 offset-md-3">
                <form action="index.php?act=company_edit" method="post" enctype="multipart/form-data" class="d-flex flex-column">
                    <input type="hidden" name="companyID" value="<?php echo $company['CompanyID']; ?>">

                    <label for="userID" class="mb-1">User ID:</label>
                    <input type="number" name="userID" id="userID" class="mb-3 form-control" value="<?php echo $company['UserID']; ?>" required readonly>

                    <label for="companyName" class="mb-1">Tên công ty:</label>
                    <input type="text" name="companyName" id="companyName" class="mb-3 form-control" value="<?php echo $company['CompanyName']; ?>" required>

                    <label for="industry" class="mb-1">Ngành công nghiệp:</label>
                    <input type="text" name="industry" id="industry" class="mb-3 form-control" value="<?php echo $company['Industry']; ?>">

                    <label for="websiteURL" class="mb-1">URL Trang web:</label>
                    <input type="url" name="websiteURL" id="websiteURL" class="mb-3 form-control" value="<?php echo $company['WebsiteURL']; ?>">

                    <label for="logoURL" class="mb-1">URL Logo:</label>
                    <input type="url" name="logoURL" id="logoURL" class="mb-3 form-control" value="<?php echo $company['LogoURL']; ?>">

                    <label for="location" class="mb-1">Vị trí:</label>
                    <input type="text" name="location" id="location" class="mb-3 form-control" value="<?php echo $company['Location']; ?>">

                    <label for="description" class="mb-1">Mô tả:</label>
                    <textarea name="description" id="description" class="mb-3 form-control"><?php echo $company['Description']; ?></textarea>

                    <input type="submit" name="updateCompany" value="Lưu thay đổi" class="btn btn-primary">
                    <a href="index.php?act=company" class="btn btn-secondary mt-2">Hủy</a>
                </form>
            </div>
        </div>
    </div>
</section>
