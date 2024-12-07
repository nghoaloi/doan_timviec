<?php
    $usreid =  $_SESSION['UserID'];
    $user = FindUserByID($usreid);
?>
<section class="mt-5 pt-5">
    <div class="container-fluid py-5 mb-5 mt-5">
        <h2>QUẢN LÝ NGƯỜI DÙNG</h2>
        <div class="row">
            <!-- Mục nhập -->
            <div class="col-md-4">
                <form action="index.php?act=profile" method="post" enctype="multipart/form-data" class="d-flex flex-column">
                    <label for="fullname" class="mb-1">Họ và tên:</label>
                    
                    
                    <label for="email" class="mb-1">Email:</label>
                    
                    
                    <label for="password" class="mb-1">Mật khẩu:</label>
                    
                    
                    <label for="number" class="mb-1">Số điện thoại:</label>
                   
                    
                    <label for="status" class="mb-1">Trạng thái:</label>
                    
                    <input type="submit" name="updateuser" value="sửa đổi thông tin" class="btn btn-primary">
                </form>
            </div>

            <!-- Mục hiển thị người dùng -->
            <div class="col-md-8">
                <h1>ảnh</h1>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-bordered mt-2">
                        
                    </table>
                </div>
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
