<?php
// Kiểm tra 

if (isset($user)) {
    $userID_user = $user['UserID'];
    $fullName_user = $user['FullName'];  
    $email_user = $user['Email'];
    $phone_user = $user['PhoneNumber'];
    $profilePictureURL_user = $user['ProfilePictureURL'];
    $address_user = $user['Address']??'';
    $dateOfBirth_user = $user['DateOfBirth']??'';
    $gender_user = $user['Gender'];
    $bio_user = $user['Bio'];
}
?>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Chỉnh sửa thông tin người dùng</h2>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <form action="index.php?act=updateuser_foruser" method="post" enctype="multipart/form-data" class="card p-4">
                <input type="hidden" name="userid" value="<?php echo $userID_user; ?>">
                <input type="hidden" name="currentProfilePictureURL" value="<?php echo $profilePictureURL_user; ?>">

                <div class="mb-3">
                    <label for="fullname" class="form-label">Họ và tên:</label>
                    <input type="text" name="fullname" id="fullname" class="form-control" value="<?php echo $fullName_user; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?php echo $email_user; ?>" required>
                    <div id="emailError" class="text-danger"></div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu :</label>
                    <input type="password" name="password" id="password" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Số điện thoại:</label>
                    <input type="tel" name="phone" id="phone" class="form-control" value="<?php echo $phone_user; ?>">
                </div>

                <div class="mb-3">
                    <label for="profilePictureURL" class="form-label">Hình đại diện:</label>
                    <div class="d-flex align-items-center">
                        <?php if (!empty($profilePictureURL_user)): ?>
                            <img id="profilePicturePreview" src="uploads/<?php echo $profilePictureURL_user; ?>" alt="avatar" width="100" height="100" class="rounded-circle me-3">
                        <?php else: ?>
                            <img id="profilePicturePreview" src="https://via.placeholder.com/100" alt="avatar" width="100" height="100" class="rounded-circle me-3">
                        <?php endif; ?>
                        <button type="button" class="btn btn-secondary" id="changePictureButton">Thay đổi</button>
                    </div>
                    <input type="file" name="profilePictureURL" id="profilePictureInput" class="form-control d-none" accept="image/*">
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Địa chỉ:</label>
                    <input type="text" name="address" id="address" class="form-control" value="<?php echo $address_user; ?>">
                </div>

                <div class="mb-3">
                    <label for="dateOfBirth" class="form-label">Ngày sinh:</label>
                    <input type="date" name="dateOfBirth" id="dateOfBirth" class="form-control" value="<?php echo $dateOfBirth_user; ?>">
                </div>

                <div class="mb-3">
                    <label for="gender" class="form-label">Giới tính:</label>
                    <select name="gender" id="gender" class="form-select">
                        <option value="Male" <?php echo $gender_user == 'Male' ? 'selected' : ''; ?>>Nam</option>
                        <option value="Female" <?php echo $gender_user == 'Female' ? 'selected' : ''; ?>>Nữ</option>
                        <option value="Other" <?php echo $gender_user == 'Other' ? 'selected' : ''; ?>>Khác</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="bio" class="form-label">Tiểu sử:</label>
                    <textarea name="bio" id="bio" rows="4" class="form-control"><?php echo $bio_user; ?></textarea>
                </div>

                <input type="submit" name="updateuser_foruser" value="cập nhật" class="btn btn-primary">
            </form>
        </div>
    </div>
</div>

<script>


async function validateForm() {
    const email = document.getElementById('email').value;
    const emailError = document.getElementById('emailError');
    const emailExists = await checkEmailExists(email);

    if (emailExists) {
        emailError.innerText = 'Email đã tồn tại!';
        return false; // Ngăn form gửi đi
    }

    emailError.innerText = '';
    return true; // Cho phép form gửi đi
}


document.getElementById('changePictureButton').addEventListener('click', function () {
    document.getElementById('profilePictureInput').click(); // Mở cửa sổ chọn tệp
});
document.getElementById('profilePictureInput').addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const preview = document.getElementById('profilePicturePreview');
            preview.src = e.target.result; // Cập nhật hình ảnh xem trước
        };
        reader.readAsDataURL(file);
    }
});
</script>

</script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
