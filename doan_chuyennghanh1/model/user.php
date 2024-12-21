<?php
function checkuser($email) {
    $conn = connectdb();
    $stmt = $conn->prepare("SELECT * FROM users WHERE Email = :email");
    $stmt->bindParam(':email', $email);
    // $stmt->bindParam(':pass', $passHash);
    $stmt->execute();

    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $kq = $stmt->fetch();

    if (is_array($kq) && !empty($kq)) {
        return $kq; // Trả về toàn bộ mảng thông tin người dùng
    } else {
        return null; // Trả về null nếu không tìm thấy người dùng
    }
}

function checkEmailExists($email) { 
    $conn = connectdb(); 
    $stmt = $conn->prepare("SELECT * FROM users WHERE Email = :email"); 
    $stmt->bindParam(':email', $email); 
    $stmt->execute(); 
    return $stmt->fetch() !== false; 
} 
function addUser_register($email, $password,$usertype) { 
    $conn = connectdb(); 
    $stmt = $conn->prepare("INSERT INTO users (Email, PasswordHash, UserType) VALUES (:email, :password, :usertype)"); 
    $stmt->bindParam(':email', $email); 
    $stmt->bindParam(':password', $password); 
    $stmt->bindParam(':usertype',$usertype);
    return $stmt->execute(); 
}
function addUser($email, $password, $fullname, $phone, $usertype, $status, $profilePicture = null) {
    $conn = connectdb();

    // Mã hóa mật khẩu
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
    $conn->beginTransaction();

    try {
        // Thực hiện insert user trước để lấy ID
        $stmt = $conn->prepare("INSERT INTO users (Email, PasswordHash, FullName, PhoneNumber, UserType, UserStatus) 
                                VALUES (:email, :password, :fullname, :phone, :usertype, :status)");

        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $passwordHash);
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':usertype', $usertype);
        $stmt->bindParam(':status', $status);

        if (!$stmt->execute()) {
            throw new Exception("Lỗi khi thêm người dùng.");
        }

        // Lấy ID vừa được insert
        $userId = $conn->lastInsertId();

        // Xử lý ảnh nếu có
        $profilePictureURL = '';
        if ($profilePicture && is_array($profilePicture) && $profilePicture['error'] === 0) { // Kiểm tra lỗi upload
            $fileExtension = strtolower(pathinfo($profilePicture['name'], PATHINFO_EXTENSION));
            $timestamp = time();
            $date = date('Ymd', $timestamp); // Định dạng ngày YYYYMMDD

            // Tạo tên file mới: id_thoigianluu_ngayluu.extension
            $newFileName = $userId . '_' . $timestamp . '_' . $date . '.' . $fileExtension;
            $targetDir = "uploads/profile_pictures/";
            $targetFile = $targetDir . $newFileName;

            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            if ($profilePicture['size'] > 5000000) {
                throw new Exception("Tệp quá lớn (tối đa 5MB).");
            }

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array($fileExtension, $allowedExtensions)) {
                throw new Exception("Chỉ cho phép các tệp JPG, JPEG, PNG, GIF, và WEBP.");
            }

            if (move_uploaded_file($profilePicture['tmp_name'], $targetFile)) {
                $profilePictureURL = $targetFile;

                 // Cập nhật lại user với đường dẫn ảnh
                $stmtUpdate = $conn->prepare("UPDATE users SET ProfilePictureURL = :profilePictureURL WHERE UserID = :userId");
                $stmtUpdate->bindParam(':profilePictureURL', $profilePictureURL);
                $stmtUpdate->bindParam(':userId', $userId);
                $stmtUpdate->execute();

            } else {
                throw new Exception("Lỗi khi tải tệp lên.");
            }
        }

        $conn->commit(); // Commit transaction nếu mọi thứ thành công
        return true;

    } catch (Exception $e) {
        $conn->rollBack(); // Rollback nếu có lỗi
        echo "<script>alert('" . $e->getMessage() . "');</script>";
        return false;
    }
}
function getUsers() {
    $conn = connectdb();
    $stmt = $conn->prepare("SELECT * FROM users");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_POST['action']) && $_POST['action'] == 'check_email') {
    $email = $_POST['email'];
    $emailExists = checkEmailExists($email);
    echo $emailExists ? 'exists' : 'not_exists';
    exit;
}
function searchUsers($query) {
    $conn = connectdb();
    $stmt = $conn->prepare("SELECT UserID, FullName FROM users WHERE FullName LIKE :query OR UserID LIKE :query LIMIT 10");
    $query = "%" . $query . "%";
    $stmt->bindParam(':query', $query, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function del_user($id) {
    // Kết nối cơ sở dữ liệu
    $conn = connectdb();
    
    // Chuẩn bị câu lệnh SQL để xóa người dùng
    $stmt = $conn->prepare("DELETE FROM users WHERE UserID = :id");
    
    // Gán giá trị cho tham số
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    // Thực thi câu lệnh SQL và trả về kết quả
    return $stmt->execute();
}

function updateUser($userID, $email, $password, $fullname, $phone, $usertype, $status, $profilePictureURL, $address, $dateOfBirth, $gender, $bio) {
    $conn = connectdb();

    // Kiểm tra và băm mật khẩu nếu được cung cấp
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    } else {
        // Lấy mật khẩu hiện tại từ cơ sở dữ liệu nếu không có mật khẩu mới
        $stmt = $conn->prepare("SELECT PasswordHash FROM users WHERE UserID = :userID");
        $stmt->bindParam(':userID', $userID);
        $stmt->execute();
        $hashed_password = $stmt->fetchColumn();
    }

    $stmt = $conn->prepare("UPDATE users SET Email = :email, PasswordHash = :password, FullName = :fullname, PhoneNumber = :phone, UserType = :usertype, UserStatus = :status, ProfilePictureURL = :profilePictureURL, Address = :address, DateOfBirth = :dateOfBirth, Gender = :gender, Bio = :bio, UpdatedAt = NOW() WHERE UserID = :userID");

    $stmt->bindParam(':userID', $userID);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':fullname', $fullname);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':usertype', $usertype);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':profilePictureURL', $profilePictureURL);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':dateOfBirth', $dateOfBirth);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':bio', $bio);

    return $stmt->execute();
}



function getUserByID($UserID) {
    $conn = connectdb();
    $stmt = $conn->prepare("SELECT * FROM users WHERE UserID = :UserID");
    $stmt->bindParam(':UserID', $UserID, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
function getUserNameByID($userID) {
    $conn = connectdb();
    if ($conn) {
        $stmt = $conn->prepare("SELECT FullName FROM users WHERE UserID = :UserID");
        $stmt->bindParam(':UserID', $userID, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result['FullName'];
        }
    }
    return null;
}

function FindUserByID($id){
    $conn = connectdb();
    $stmt = $conn->prepare("SELECT UserID, FullName, Email, PhoneNumber,UserStatus FROM users WHERE UserID = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Gán giá trị cho tham số truy vấn
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
function getUsersByUserType() {
    $conn = connectdb();
    $stmt = $conn->prepare("SELECT UserID, FullName FROM users WHERE UserType = 'Employer'");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


?>
