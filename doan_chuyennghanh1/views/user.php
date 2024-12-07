<section class="mt-5 pt-5">
    <div class="container-fluid py-5 mb-5 mt-5">
        <h2>QUẢN LÝ NGƯỜI DÙNG</h2>
        <div class="row">
            <!-- Mục nhập -->
            <div class="col-md-4">
                <form action="index.php?act=user_add" method="post" enctype="multipart/form-data" class="d-flex flex-column" onsubmit="return validateForm()">
                    <label for="fullname" class="mb-1">Họ và tên:</label>
                    <input type="text" name="fullname" id="fullname" class="mb-3 form-control" required>
                    
                    <label for="email" class="mb-1">Email:</label>
                    <input type="email" name="email" id="email" class="mb-3 form-control" required>
                    <div id="emailError" style="color: red;"></div>
                    
                    <label for="password" class="mb-1">Mật khẩu:</label>
                    <input type="password" name="password" id="password" class="mb-3 form-control" required>
                    
                    <label for="phone" class="mb-1">Số điện thoại:</label>
                    <input type="tel" name="phone" id="phone" class="mb-3 form-control">
                    
                    <label for="usertype" class="mb-1">Loại tài khoản:</label>
                    <select name="usertype" id="usertype" class="mb-3 form-control" required>
                        <option value="Employer">Ứng viên</option>
                        <option value="Candidate">Nhà tuyển dụng</option>
                        <option value="Admin">Quản trị viên</option>
                    </select>
                    
                    <label for="status" class="mb-1">Trạng thái:</label>
                    <select name="status" id="status" class="mb-3 form-control" required>
                        <option value="Active">Hoạt động</option>
                        <option value="Inactive">Không hoạt động</option>
                        <option value="Banned">Bị cấm</option>
                    </select>
                    
                    <label for="profilePictureURL" class="mb-1">Hình đại diện:</label>
                    <input type="file" name="profilePictureURL" id="profilePictureURL" class="mb-3 form-control">

                    <input type="submit" name="adduser" value="Thêm mới" class="btn btn-primary">
                </form>
            </div>

            <!-- Mục hiển thị người dùng -->
            <div class="col-md-8">
                <h1>Danh sách người dùng</h1>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-bordered mt-2">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">STT</th>
                                <th scope="col">Họ và tên</th>
                                <th scope="col">Email</th>
                                <th scope="col">Số điện thoại</th>
                                <th scope="col">Loại tài khoản</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $users = getUsers();
                            if (isset($users) && count($users) > 0) {
                                $i = 1;
                                foreach ($users as $user) {
                                    // $profilePicture = isset($user['ProfilePictureURL']) && !empty($user['ProfilePictureURL']) ? $user['ProfilePictureURL'] : 'default_avatar.png';
                                    echo '<tr>
                                            <th scope="row">'.$i.'</th>
                                            <td>
                                                <img src="uploads/'.$user['ProfilePictureURL'].'" alt="avatar" width="30" height="30" style="border-radius:50%; margin-right:8px;"> '.$user['FullName'].'
                                            </td>
                                            <td>'.$user['Email'].'</td>
                                            <td>'.$user['PhoneNumber'].'</td>
                                            <td>'.$user['UserType'].'</td>
                                            <td>'.$user['UserStatus'].'</td>
                                            <td>
                                                <a href="index.php?act=editform_user&id='.$user['UserID'].'" class="btn btn-warning btn-sm">Sửa</a> 
                                                <a href="index.php?act=del_user&id='.$user['UserID'].'" class="btn btn-danger btn-sm">Xóa</a>
                                            </td>
                                        </tr>';
                                    $i++;
                                }
                            } else {
                                echo '<tr><td colspan="7" class="text-center">Không có người dùng nào.</td></tr>';
                            }
                            ?>
                        </tbody>
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
