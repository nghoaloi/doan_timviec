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
    $conn->beginTransaction();

    try {
        // 1. Insert user (lấy UserID)
        $stmt = $conn->prepare("INSERT INTO users (Email, PasswordHash, FullName, PhoneNumber, UserType, UserStatus) 
                                VALUES (:email, :password, :fullname, :phone, :usertype, :status)");

        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindParam(':usertype', $usertype, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);

        if (!$stmt->execute()) {
            $errorInfo = $stmt->errorInfo();
            throw new Exception("Lỗi khi thêm người dùng: " . $errorInfo[2]); // Ghi log lỗi chi tiết
        }

        $userId = $conn->lastInsertId();

        // 2. Xử lý ảnh (nếu có)
        $profilePictureURL = null; // Khởi tạo là null
        if ($profilePicture && is_array($profilePicture) && $profilePicture['error'] === 0) {
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $fileMimeType = mime_content_type($profilePicture['tmp_name']);

            if (!in_array($fileMimeType, $allowedMimeTypes)) {
                throw new Exception("Định dạng tệp không được phép. Chỉ cho phép JPG, JPEG, PNG, GIF, và WEBP.");
            }

            $fileExtension = strtolower(pathinfo($profilePicture['name'], PATHINFO_EXTENSION));
            $timestamp = time();
            $date = date('Ymd', $timestamp);
            $newFileName = $userId . '_' . $timestamp . '_' . $date . '.' . $fileExtension;
            $targetDir = "uploads/profile_pictures/";
            $targetFile = $targetDir . $newFileName;

            if (!is_dir($targetDir)) {
                if (!mkdir($targetDir, 0755, true)) {
                    throw new Exception("Không thể tạo thư mục uploads/profile_pictures/");
                }
            }

            if ($profilePicture['size'] > 5000000) {
                throw new Exception("Tệp quá lớn (tối đa 5MB).");
            }

            if (file_exists($targetFile)) {
                throw new Exception("Tệp đã tồn tại.");
            }

            if (move_uploaded_file($profilePicture['tmp_name'], $targetFile)) {
                $profilePictureURL = $newFileName; // CHỈ LƯU TÊN FILE
            } else {
                throw new Exception("Lỗi khi tải tệp lên.");
            }
        }

        // 3. Cập nhật đường dẫn ảnh (nếu có)
        if ($profilePictureURL !== null) { // Chỉ cập nhật nếu có ảnh được upload thành công
            $stmtUpdate = $conn->prepare("UPDATE users SET ProfilePictureURL = :profilePictureURL WHERE UserID = :userId");
            $stmtUpdate->bindParam(':profilePictureURL', $profilePictureURL, PDO::PARAM_STR);
            $stmtUpdate->bindParam(':userId', $userId, PDO::PARAM_INT);
            if (!$stmtUpdate->execute()) {
                $errorInfo = $stmtUpdate->errorInfo();
                throw new Exception("Lỗi khi cập nhật đường dẫn ảnh: " . $errorInfo[2]);
            }
        }

        $conn->commit();
        return true;

    } catch (Exception $e) {
        $conn->rollBack();
        error_log($e->getMessage()); // Ghi log lỗi vào file log của server
        echo "<script>alert('" . htmlspecialchars($e->getMessage()) . "');</script>"; // Hiển thị thông báo lỗi thân thiện cho người dùng
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
    $conn->beginTransaction();

    try {
        $stmtSelect = $conn->prepare("SELECT ProfilePictureURL FROM users WHERE UserID = :userID");
        $stmtSelect->bindParam(':userID', $userID, PDO::PARAM_INT);
        $stmtSelect->execute();
        $currentProfilePicture = $stmtSelect->fetchColumn();

        $hashed_password = null;
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        }

        $sql = "UPDATE users SET Email = :email, FullName = :fullname, PhoneNumber = :phone, UserType = :usertype, UserStatus = :status, Address = :address, DateOfBirth = :dateOfBirth, Gender = :gender, Bio = :bio, UpdatedAt = NOW()";

        if ($hashed_password !== null) {
            $sql .= ", PasswordHash = :password";
        }
        if ($profilePictureURL !== null) {
            $sql .= ", ProfilePictureURL = :profilePictureURL";
        }

        $sql .= " WHERE UserID = :userID";

        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindParam(':usertype', $usertype, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':address', $address, PDO::PARAM_STR);
        $stmt->bindParam(':dateOfBirth', $dateOfBirth, PDO::PARAM_STR);
        $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
        $stmt->bindParam(':bio', $bio, PDO::PARAM_STR);

        if ($hashed_password !== null) {
            $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
        }
        if ($profilePictureURL !== null) {
            $stmt->bindParam(':profilePictureURL', $profilePictureURL, PDO::PARAM_STR);
        }

        if (!$stmt->execute()) {
            $errorInfo = $stmt->errorInfo();
            throw new Exception("Lỗi khi cập nhật người dùng: " . $errorInfo[2]);
        }

        $conn->commit();
        return true;

    } catch (Exception $e) {
        $conn->rollBack();
        error_log($e->getMessage());
        echo "<script>alert('" . htmlspecialchars($e->getMessage()) . "');</script>";
        return false;
    }
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
