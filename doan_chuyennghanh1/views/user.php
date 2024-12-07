<section class="mt-5">
    <div class="container-fluid py-5">
        <h2 class="text-center mb-4">QUẢN LÝ NGƯỜI DÙNG</h2>
        <div class="row d-flex align-items-stretch">
            <!-- Form thêm người dùng -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Thêm Người Dùng Mới</h5>
                    </div>
                    <div class="card-body">
                        <form action="index.php?act=user_add" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                            <!-- Các trường nhập liệu -->
                            <div class="mb-3">
                                <label for="fullname" class="form-label">Họ và tên:</label>
                                <input type="text" name="fullname" id="fullname" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                                <div id="emailError" class="text-danger mt-1"></div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu:</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại:</label>
                                <input type="tel" name="phone" id="phone" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="usertype" class="form-label">Loại tài khoản:</label>
                                <select name="usertype" id="usertype" class="form-select" required>
                                    <option value="Employer">Ứng viên</option>
                                    <option value="Candidate">Nhà tuyển dụng</option>
                                    <option value="Admin">Quản trị viên</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Trạng thái:</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="Active">Hoạt động</option>
                                    <option value="Inactive">Không hoạt động</option>
                                    <option value="Banned">Bị cấm</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="profilePictureURL" class="form-label">Hình đại diện:</label>
                                <input type="file" name="profilePictureURL" id="profilePictureURL" class="form-control">
                            </div>
                            <button type="submit" name="adduser" class="btn btn-primary w-100">Thêm mới</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Danh sách người dùng -->
            <div class="col-lg-8">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-dark text-white">
                        <h5 class="card-title mb-0">Danh Sách Người Dùng</h5>
                    </div>
                    <div class="card-body" style="overflow-y: auto;">
                        <div class="table-responsive">
                            <!-- Bảng người dùng -->
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
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
                                    // Duyệt danh sách người dùng
                                    $users = getUsers();
                                    if (isset($users) && count($users) > 0) {
                                        $i = 1;
                                        foreach ($users as $user) {
                                            $profilePicture = isset($user['ProfilePictureURL']) && !empty($user['ProfilePictureURL'])
                                                ? 'uploads/' . htmlspecialchars($user['ProfilePictureURL'])
                                                : 'https://via.placeholder.com/30';

                                            echo '<tr>
                                                    <th scope="row">' . $i . '</th>
                                                    <td>
                                                        <img src="' . $profilePicture . '" alt="avatar" width="30" height="30" class="rounded-circle me-2"> 
                                                        ' . htmlspecialchars($user['FullName'] ?? 'Không có tên') . '
                                                    </td>
                                                    <td>' . htmlspecialchars($user['Email'] ?? 'Không có email') . '</td>
                                                    <td>' . htmlspecialchars($user['PhoneNumber'] ?? 'Không có số điện thoại') . '</td>
                                                    <td>' . htmlspecialchars($user['UserType'] ?? 'Không xác định') . '</td>
                                                    <td>' . htmlspecialchars($user['UserStatus'] ?? 'Không xác định') . '</td>
                                                    <td>
                                                        <a href="index.php?act=editform_user&id=' . htmlspecialchars($user['UserID']) . '" 
                                                           class="btn btn-warning btn-sm">Sửa</a> 
                                                        <a href="index.php?act=del_user&id=' . htmlspecialchars($user['UserID']) . '" 
                                                           class="btn btn-danger btn-sm" 
                                                           onclick="return confirm(\'Bạn có chắc chắn muốn xóa người dùng này không?\')">Xóa</a>
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
        </div>
    </div>
</section>
