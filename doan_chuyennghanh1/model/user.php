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
function addUser($email, $password, $fullname, $phone, $usertype, $status, $profilePictureURL = '') {
    // Kết nối cơ sở dữ liệu
    $conn = connectdb();
    
    // Mã hóa mật khẩu trước khi lưu trữ
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
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
function searchUsers($search) {
    $conn = connectdb();
    $stmt = $conn->prepare("SELECT FullName
                            FROM users 
                            WHERE FullName LIKE :search");
    $stmt->bindValue(':search', '%' . $search . '%');
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
function FindUserByID($id){
    $conn = connectdb();
    $stmt = $conn->prepare("SELECT UserID, FullName, Email, PhoneNumber,UserStatus FROM users WHERE UserID = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Gán giá trị cho tham số truy vấn
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


?>
