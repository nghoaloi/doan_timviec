<form action="index.php?act=company_edit" method="post" enctype="multipart/form-data" class="container p-4 bg-light rounded shadow">
    <input type="hidden" name="companyID" value="<?php echo $company['CompanyID']; ?>">
    <input type="hidden" name="currentLogoURL" value="<?php echo $company['LogoURL']; ?>">
    <input type="hidden" name="userID" value="<?php echo $company['UserID']; ?>">

    <h3 class="text-center mb-4">Chỉnh Sửa Thông Tin Công Ty</h3>

    <!-- Tên công ty -->
    <div class="mb-3">
        <label for="companyName" class="form-label fw-bold">Tên công ty:</label>
        <input type="text" name="companyName" id="companyName" class="form-control" 
               value="<?php echo $company['CompanyName']; ?>" required>
    </div>

    <!-- Ngành công nghiệp -->
    <div class="mb-3">
        <label for="industry" class="form-label fw-bold">Ngành công nghiệp:</label>
        <input type="text" name="industry" id="industry" class="form-control" 
               value="<?php echo $company['Industry']; ?>">
    </div>

    <!-- URL Trang web -->
    <div class="mb-3">
        <label for="websiteURL" class="form-label fw-bold">URL Trang web:</label>
        <input type="url" name="websiteURL" id="websiteURL" class="form-control" 
               value="<?php echo $company['WebsiteURL']; ?>">
    </div>

    <!-- Logo -->
    <div class="mb-3">
        <label for="logoURL" class="form-label fw-bold">Logo công ty:</label>
        <div class="d-flex align-items-center">
            <?php if (!empty($company['LogoURL'])): ?>
                <img id="logoPreview" src="uploads/<?php echo $company['LogoURL']; ?>" 
                     alt="logo" width="100" height="100" class="rounded-circle me-3 border">
            <?php else: ?>
                <img id="logoPreview" src="https://via.placeholder.com/100" 
                     alt="logo" width="100" height="100" class="rounded-circle me-3 border">
            <?php endif; ?>
            <button type="button" class="btn btn-outline-secondary" id="changeLogoButton">Thay đổi logo</button>
        </div>
        <input type="file" name="logoURL" id="logoInput" class="form-control d-none" accept="image/*">
    </div>

    <!-- Vị trí -->
    <div class="mb-3">
        <label for="location" class="form-label fw-bold">Vị trí:</label>
        <input type="text" name="location" id="location" class="form-control" 
               value="<?php echo $company['Location']; ?>">
    </div>

    <!-- Mô tả -->
    <div class="mb-3">
        <label for="description" class="form-label fw-bold">Mô tả:</label>
        <textarea name="description" id="description" rows="5" class="form-control"><?php echo $company['Description']; ?></textarea>
    </div>

    <!-- Nút bấm -->
    <div class="text-center">
        <input type="submit" name="updateCompany" value="Cập nhật" class="btn btn-primary px-5">
    </div>
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
