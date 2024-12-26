<?php
// Kiểm tra xem biến $user có tồn tại không
if (isset($user)) {
    $userID = $user['UserID'];
    $fullName = $user['FullName'];
    $email = $user['Email'];
    $phone = $user['PhoneNumber'];
    $userType = $user['UserType'];
    $status = $user['UserStatus'];
    $profilePictureURL = $user['ProfilePictureURL'];
    $address = $user['Address'];
    $dateOfBirth = $user['DateOfBirth'];
    $gender = $user['Gender'];
    $bio = $user['Bio'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa thông tin người dùng</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Chỉnh sửa thông tin người dùng</h2>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <form action="index.php?act=user_update" method="post" enctype="multipart/form-data" class="card p-4" onsubmit="return validateForm()">
                <input type="hidden" name="userid" value="<?php echo $userID; ?>">
                <input type="hidden" name="currentPassword" value="<?php echo $user['PasswordHash']; ?>">
                <input type="hidden" name="currentProfilePictureURL" value="<?php echo $profilePictureURL; ?>">

                <div class="mb-3">
                    <label for="fullname" class="form-label">Họ và tên:</label>
                    <input type="text" name="fullname" id="fullname" class="form-control" value="<?php echo $fullName; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?php echo $email; ?>" required>
                    <div id="emailError" class="text-danger"></div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu (để trống nếu không muốn thay đổi):</label>
                    <input type="password" name="password" id="password" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Số điện thoại:</label>
                    <input type="tel" name="phone" id="phone" class="form-control" value="<?php echo $phone; ?>">
                </div>
                
                <div class="mb-3">
                    <label for="usertype" class="form-label">Loại người dùng:</label>
                    <select name="usertype" id="usertype" class="form-select">
                        <option value="candidate" <?php echo $userType == 'candidate' ? 'selected' : ''; ?>>Ứng viên</option>
                        <option value="employer" <?php echo $userType == 'employer' ? 'selected' : ''; ?>>Nhà tuyển dụng</option>
                        <option value="admin" <?php echo $userType == 'admin' ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Trạng thái:</label>
                    <select name="status" id="status" class="form-select">
                        <option value="active" <?php echo $status == 'active' ? 'selected' : ''; ?>>Hoạt động</option>
                        <option value="inactive" <?php echo $status == 'inactive' ? 'selected' : ''; ?>>Không hoạt động</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="profilePictureURL" class="form-label">Hình đại diện:</label>
                    <div class="d-flex align-items-center">
                        <?php if (!empty($profilePictureURL)): ?>
                            <img id="profilePicturePreview" src="uploads/profile_pictures/<?php echo $profilePictureURL; ?>" alt="avatar" width="100" height="100" class="rounded-circle me-3">
                        <?php else: ?>
                            <img id="profilePicturePreview" src="https://via.placeholder.com/100" alt="avatar" width="100" height="100" class="rounded-circle me-3">
                        <?php endif; ?>
                        <button type="button" class="btn btn-secondary" id="changePictureButton">Thay đổi</button>
                    </div>
                    <input type="file" name="profilePictureURL" id="profilePictureInput" class="form-control d-none" accept="image/*">
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Địa chỉ:</label>
                    <input type="text" name="address" id="address" class="form-control" value="<?php echo $address; ?>">
                </div>

                <div class="mb-3">
                    <label for="dateOfBirth" class="form-label">Ngày sinh:</label>
                    <input type="date" name="dateOfBirth" id="dateOfBirth" class="form-control" value="<?php echo $dateOfBirth; ?>">
                </div>

                <div class="mb-3">
                    <label for="gender" class="form-label">Giới tính:</label>
                    <select name="gender" id="gender" class="form-select">
                        <option value="Male" <?php echo $gender == 'Male' ? 'selected' : ''; ?>>Nam</option>
                        <option value="Female" <?php echo $gender == 'Female' ? 'selected' : ''; ?>>Nữ</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="bio" class="form-label">Tiểu sử:</label>
                    <textarea name="bio" id="bio" rows="4" class="form-control"><?php echo $bio; ?></textarea>
                </div>

                <input type="submit" name="updateuser" class="btn btn-primary" value="Cập nhật">
            </form>
        </div>
    </div>
</div>

<script>
async function checkEmailExists(email) {
    const response = await fetch('index.php?act=user_add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=check_email&email=' + encodeURIComponent(email),
    });
    const result = await response.text();
    return result === 'exists';
}

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

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
