<?php
function checkEmailExists($email) {
    $conn = connectdb();
    $stmt = $conn->prepare("SELECT * FROM users WHERE Email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    return $stmt->fetch() !== false;
}

function addUser($email, $password, $fullname, $phone, $usertype, $status, $profilePicture) {
    // Kết nối cơ sở dữ liệu
    $conn = connectdb();
    
    // Mã hóa mật khẩu trước khi lưu trữ
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    // Xử lý upload hình ảnh
    $profilePictureURL = '';
    if (is_array($profilePicture) && $profilePicture['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $profilePicture['tmp_name'];
        $fileExtension = pathinfo($profilePicture['name'], PATHINFO_EXTENSION);
        $currentTime = time();
        $formattedDate = date('Ymd_His', $currentTime);
        $newFileName = uniqid() . '_' . $formattedDate . '.' . $fileExtension;
        $uploadDir = 'uploads/';
        $destPath = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            $profilePictureURL = $newFileName;
        }
    }

    // Chuẩn bị câu lệnh SQL để thêm người dùng mới
    $stmt = $conn->prepare("INSERT INTO users (Email, PasswordHash, FullName, PhoneNumber, UserType, UserStatus, ProfilePictureURL) 
                            VALUES (:email, :password, :fullname, :phone, :usertype, :status, :profilePictureURL)");
    
    // Gán giá trị cho các tham số
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $passwordHash);
    $stmt->bindParam(':fullname', $fullname);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':usertype', $usertype);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':profilePictureURL', $profilePictureURL);
    
    // Thực thi câu lệnh SQL và trả về kết quả
    return $stmt->execute();
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

function searchUsers($query) {
    $conn = connectdb();
    $stmt = $conn->prepare("SELECT UserID, FullName FROM users WHERE FullName LIKE :query OR UserID LIKE :query LIMIT 10");
    $query = "%" . $query . "%";
    $stmt->bindParam(':query', $query, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function FindUserByID($id) {
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
