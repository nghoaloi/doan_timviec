<form action="index.php?act=company_edit" method="post" enctype="multipart/form-data" class="d-flex flex-column">
    <input type="hidden" name="companyID" value="<?php echo $company['CompanyID']; ?>">
    <input type="hidden" name="currentLogoURL" value="<?php echo $company['LogoURL']; ?>">
    <input type="hidden" name="userID" value="<?php echo $company['UserID']; ?>"> <!-- Thêm trường userID -->

    <label for="companyName" class="mb-1">Tên công ty:</label>
    <input type="text" name="companyName" id="companyName" class="mb-3 form-control" value="<?php echo $company['CompanyName']; ?>" required>

    <label for="industry" class="mb-1">Ngành công nghiệp:</label>
    <input type="text" name="industry" id="industry" class="mb-3 form-control" value="<?php echo $company['Industry']; ?>">

    <label for="websiteURL" class="mb-1">URL Trang web:</label>
    <input type="url" name="websiteURL" id="websiteURL" class="mb-3 form-control" value="<?php echo $company['WebsiteURL']; ?>">

    <label for="logoURL" class="mb-1">URL Logo:</label>
    <div class="d-flex align-items-center">
        <?php if (!empty($company['LogoURL'])): ?>
            <img id="logoPreview" src="uploads/<?php echo $company['LogoURL']; ?>" alt="logo" width="100" height="100" class="rounded-circle me-3">
        <?php else: ?>
            <img id="logoPreview" src="https://via.placeholder.com/100" alt="logo" width="100" height="100" class="rounded-circle me-3">
        <?php endif; ?>
        <button type="button" class="btn btn-secondary" id="changeLogoButton">Thay đổi</button>
    </div>
    <input type="file" name="logoURL" id="logoInput" class="form-control d-none" accept="image/*">

    <label for="location" class="mb-1">Vị trí:</label>
    <input type="text" name="location" id="location" class="mb-3 form-control" value="<?php echo $company['Location']; ?>">

    <label for="description" class="mb-1">Mô tả:</label>
    <textarea name="description" id="description" class="mb-3 form-control"><?php echo $company['Description']; ?></textarea>

    <input type="submit" name="updateCompany" value="Cập nhật" class="btn btn-primary">
</form>

<script>
document.getElementById('changeLogoButton').addEventListener('click', function () {
    document.getElementById('logoInput').click(); // Mở cửa sổ chọn tệp
});
document.getElementById('logoInput').addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const preview = document.getElementById('logoPreview');
            preview.src = e.target.result; // Cập nhật hình ảnh xem trước
        };
        reader.readAsDataURL(file);
    }
});
</script>
