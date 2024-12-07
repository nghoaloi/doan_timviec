<?php
   $userid =  $_SESSION['UserID'];     
   $user = getUserByID($userid);
?>
<section class="mt-5 pt-5">
    <div class="container-fluid py-5 mb-5 mt-5">
        <h2>QUẢN LÝ NGƯỜI DÙNG</h2>
        <div class="row">
            <!-- Mục nhập -->
            <div class="col-md-4">
                <form action="index.php?act=updateuser_foruser" method="post" enctype="multipart/form-data" class="d-flex flex-column">
                    <input type="hidden" name="userid" value="<?php echo $userid; ?>">  

                    <label for="fullname" class="mb-1">Họ và tên:&nbsp;&nbsp;&nbsp;<?php echo htmlspecialchars($user['FullName'] ?? ''); ?></label>
                    <br>
                    <label for="email" class="mb-1">Email:&nbsp;&nbsp;&nbsp;<?php echo htmlspecialchars($user['Email'] ?? ''); ?></label>
                    <br>
                    <label for="number" class="mb-1">Số điện thoại:&nbsp;&nbsp;&nbsp;<?php echo htmlspecialchars($user['PhoneNumber'] ?? ''); ?></label>
                    <br>
                    <label for="status" class="mb-1">Trạng thái:&nbsp;&nbsp;&nbsp;<?php echo htmlspecialchars($user['UserStatus'] ?? ''); ?></label>
                    <br>
                    <label for="currentPassword" class="mb-1">Mật khẩu hiện tại:</label>
                    <input type="password" name="currentPassword" id="currentPassword" class="form-control" required>
                    <br>
                    <label for="newPassword" class="mb-1">Mật khẩu mới:</label>
                    <input type="password" name="newPassword" id="newPassword" class="form-control">
                    <br>

                    <a href="index.php?act=mo_updateuser_foruser&id=<?php echo $user['UserID']; ?>" class="btn btn-warning btn-sm">Sửa</a> 
                </form>
            </div>

            <!-- Mục hiển thị người dùng -->
            <div class="col-md-4">
                <img src="<?php echo htmlspecialchars($user['ProfilePictureURL'] ?? 'https://via.placeholder.com/150'); ?>" alt="ảnh đại diện" class="img-fluid">
            </div>
        </div>
    </div>
</section>

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
</script>
