<?php
    session_start();
    ob_start();
    include "model/connectdb.php";
    include "model/user.php";
    include "model/company.php";
    include "model/job.php";
    include "model/review.php";
    include "model/statistical.php";
    
    if(isset($_SESSION['UserType'])&&($_SESSION['UserType']=='Admin')){
        include "views/header.php";
    if (isset($_GET['act'])){
    switch ($_GET['act']) {
        //xử lý quản lý user
        case 'user':
            include "views/user.php";
            break;
            case 'user_add':
                if (isset($_POST['adduser']) && ($_POST['adduser'])) {
                    $email = htmlspecialchars($_POST['email']);
                    $password = htmlspecialchars($_POST['password']);
                    $fullname = htmlspecialchars($_POST['fullname']);
                    $phone = htmlspecialchars($_POST['phone']);
                    $usertype = htmlspecialchars($_POST['usertype']);
                    $status = htmlspecialchars($_POST['status']);
                    $emailExists = checkEmailExists($email);
            
                    // Mã hóa mật khẩu
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            
                    // Xử lý upload hình ảnh
                    $profilePicture = null; // Khởi tạo biến $profilePicture
                    $uploadOk = 1;
            
                    if (isset($_FILES['profilePictureURL']) && $_FILES['profilePictureURL']['error'] === 0) {
                        $profilePicture = $_FILES['profilePictureURL']; // Gán $_FILES vào $profilePicture
                        $target_dir = "uploads/profile_pictures/"; // Đặt đường dẫn đầy đủ và tạo thư mục con
                        if (!is_dir($target_dir)) {
                            mkdir($target_dir, 0777, true);
                        }
            
                        $fileExtension = strtolower(pathinfo($profilePicture["name"], PATHINFO_EXTENSION));
            
                        if (!in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                            echo "<script>alert('Chỉ cho phép các tệp JPG, JPEG, PNG, GIF, và WEBP.');</script>";
                            $uploadOk = 0;
                        }
            
                        if ($profilePicture["size"] > 5000000) {
                            echo "<script>alert('Tệp quá lớn (tối đa 5MB).');</script>";
                            $uploadOk = 0;
                        }
                    }
            
                    if ($emailExists) {
                        echo "<script>alert('Email đã tồn tại!');</script>";
                    } else {
                        // Kiểm tra $uploadOk TRƯỚC khi gọi addUser
                        if ($uploadOk == 1) {
                            $result = addUser($email, $passwordHash, $fullname, $phone, $usertype, $status, $profilePicture);
                        } else {
                            // Nếu upload không thành công, vẫn thêm user nhưng không có ảnh
                            $result = addUser($email, $passwordHash, $fullname, $phone, $usertype, $status, null);
                        }
            
                        if ($result) {
                            echo "<script>alert('Thêm người dùng thành công!');</script>";
                        } else {
                            echo "<script>alert('Có lỗi xảy ra khi thêm người dùng.');</script>";
                        }
                    }
                }
                include "views/user.php";
                break;
        case 'user_search':
            if (isset($_POST['searchuser']) && ($_POST['searchuser'])) {
                $search = $_POST['search'];
                $users = searchUsers($search);
            } else {
                $users = getUsers();
            }
            include "views/user.php";
            break;
        case 'del_user': 
            if (isset($_GET['id']) && !empty($_GET['id'])) { 
                $id = $_GET['id']; 
                $result = del_user($id); 
                if ($result) { 
                    echo "<script>alert('Xóa người dùng thành công!');</script>"; 
                } else { 
                    echo "<script>alert('Có lỗi xảy ra khi xóa người dùng.');</script>"; 
                } 
            } 
            $users = getUsers(); 
            include "views/user.php"; 
            break;
            case 'editform_user':
                if (isset($_GET['id']) && !empty($_GET['id'])) {
                    $user=getUserByID($_GET['id']);
                }
                include "views/edit_user.php";
                break;
                case 'user_update':
                    if (isset($_POST['updateuser']) && ($_POST['updateuser'])) {
                        // Lấy dữ liệu từ form
                        $userID = htmlspecialchars($_POST['userid']);
                        $email = htmlspecialchars($_POST['email']);
                        $fullname = htmlspecialchars($_POST['fullname']);
                        $phone = htmlspecialchars($_POST['phone']);
                        $usertype = htmlspecialchars($_POST['usertype']);
                        $status = htmlspecialchars($_POST['status']);
                        $address = htmlspecialchars($_POST['address']);
                        $dateOfBirth = htmlspecialchars($_POST['dateOfBirth']);
                        $gender = htmlspecialchars($_POST['gender']);
                        $bio = htmlspecialchars($_POST['bio']);
                        // Kiểm tra dữ liệu bắt buộc
        if (empty($userID) || empty($email) || empty($fullname) || empty($usertype) || empty($status)) {
            echo "<script>alert('Vui lòng điền đầy đủ thông tin bắt buộc.');</script>";
            include "views/user.php";
            break;
        }

        // Kiểm tra định dạng email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Email không hợp lệ.');</script>";
            include "views/user.php";
            break;
        }

        // Kiểm tra userID hợp lệ
        if ($userID === false) {
            echo "<script>alert('ID người dùng không hợp lệ.');</script>";
            include "views/user.php";
            break;
        }

        // 2. Xử lý mật khẩu
        $password = null;
        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        // 3. Xử lý upload ảnh
        $profilePictureURL = $_POST['currentProfilePictureURL']; // Giá trị mặc định là ảnh cũ
        $uploadOk = true;
        $newFileName = null; // Khởi tạo biến này

        if (isset($_FILES['profilePictureURL']) && $_FILES['profilePictureURL']['error'] === UPLOAD_ERR_OK) {
            $profilePicture = $_FILES['profilePictureURL'];
            $targetDir = "uploads/profile_pictures/";

            // Tạo thư mục nếu chưa tồn tại
            if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true)) {
                echo "<script>alert('Lỗi khi tạo thư mục upload.');</script>";
                $uploadOk = false;
            }

            if ($uploadOk) {
                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $fileMimeType = mime_content_type($profilePicture['tmp_name']);

                if (!in_array($fileMimeType, $allowedMimeTypes)) {
                    echo "<script>alert('Định dạng tệp không được phép.');</script>";
                    $uploadOk = false;
                }

                if ($profilePicture["size"] > 5000000) {
                    echo "<script>alert('Tệp quá lớn (tối đa 5MB).');</script>";
                    $uploadOk = false;
                }

                if ($uploadOk) {
                    $fileExtension = strtolower(pathinfo($profilePicture["name"], PATHINFO_EXTENSION));
                    $timestamp = time();
                    $date = date('Ymd', $timestamp);
                    $newFileName = $userID . '_' . $timestamp . '_' . $date . '.' . $fileExtension;
                    $targetFile = $targetDir . $newFileName;

                    if (file_exists($targetFile)) {
                        echo "<script>alert('Tệp đã tồn tại.');</script>";
                        $uploadOk = false;
                    }

                    if ($uploadOk && !move_uploaded_file($profilePicture['tmp_name'], $targetFile)) {
                        echo "<script>alert('Lỗi khi tải tệp lên.');</script>";
                        $uploadOk = false;
                    } else if ($uploadOk){
                        // Xóa ảnh cũ nếu upload thành công và ảnh cũ khác rỗng
                        if (!empty($profilePictureURL) && file_exists("uploads/profile_pictures/" . $profilePictureURL)) {
                            unlink("uploads/profile_pictures/" . $profilePictureURL);
                        }
                    }
                }
            }
        }
        $profilePictureURL = $uploadOk && $newFileName !== null ? $newFileName : $profilePictureURL;
        // 4. Gọi hàm updateUser nếu không có lỗi upload
        if ($uploadOk) {
            $result = updateUser($userID, $email, $password, $fullname, $phone, $usertype, $status, $profilePictureURL, $address, $dateOfBirth, $gender, $bio);
            if ($result) {
                echo "<script>alert('Cập nhật người dùng thành công!');</script>";
            } else {
                echo "<script>alert('Có lỗi xảy ra khi cập nhật người dùng.');</script>";
            }
        }
    }

    $users = getUsers();
    include "views/user.php";
    break;                      
        // Xử lý thêm công ty
        case 'company':
            include "views/company.php";
            break;
            case 'company_add':
                if (isset($_POST['addCompany']) && $_POST['addCompany']) {
                    // Lọc và kiểm tra dữ liệu đầu vào
                    $userID = htmlspecialchars($_POST['userID']);
                    $companyName = htmlspecialchars($_POST['companyName']);
                    $industry = htmlspecialchars($_POST['industry']);
                    $websiteURL = htmlspecialchars($_POST['websiteURL']);
                    $location = htmlspecialchars($_POST['location']);
                    $description = htmlspecialchars($_POST['description']);
        
                    // Kiểm tra các trường bắt buộc
                    if ($userID === false || empty($companyName)) {
                        echo "<script>alert('Vui lòng nhập đầy đủ thông tin bắt buộc (UserID và Tên công ty).');</script>";
                        include "views/company.php"; // Hoặc redirect
                        exit();
                    }
        
                    $uploadOk = 1;
                    $logoURL = '';
        
                    // Xử lý upload file logo
                    if (isset($_FILES['logoURL']) && $_FILES['logoURL']['error'] === UPLOAD_ERR_OK) {
                        $target_dir = "uploads/";
        
                        // Tạo thư mục nếu chưa tồn tại, set quyền 0755
                        if (!is_dir($target_dir) && !mkdir($target_dir, 0755, true)) {
                            echo "<script>alert('Lỗi khi tạo thư mục upload.');</script>";
                            $uploadOk = 0;
                        }
        
                        $timestamp = time();
                        $date = date('Ymd', $timestamp);
                        $unique_id = uniqid();
                        $fileExtension = strtolower(pathinfo($_FILES['logoURL']['name'], PATHINFO_EXTENSION));
                        $allowed_file_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
                        if (!in_array($fileExtension, $allowed_file_types)) {
                            echo "<script>alert('Chỉ cho phép các tệp JPG, JPEG, PNG, GIF và WEBP.');</script>";
                            $uploadOk = 0;
                        }
        
                        if ($_FILES['logoURL']['size'] > 5000000) { // Giới hạn 5MB
                            echo "<script>alert('Tệp quá lớn (tối đa 5MB).');</script>";
                            $uploadOk = 0;
                        }
        
                        if ($uploadOk == 1) {
                            $newFileName = $unique_id . "_" . $timestamp . "_" . $date . "." . $fileExtension;
                            $target_file = $target_dir . $newFileName;
        
                            // Debug upload (giữ lại phần debug này để kiểm tra nếu có lỗi)
                            echo "<pre>";
                            var_dump($_FILES['logoURL']);
                            echo "target_dir: " . $target_dir . "<br>";
                            echo "newFileName: " . $newFileName . "<br>";
                            echo "target_file: " . $target_file . "<br>";
                            echo "is_writable(target_dir): " . is_writable($target_dir) . "<br>";
                            echo "</pre>";
        
                            if (move_uploaded_file($_FILES['logoURL']['tmp_name'], $target_file)) {
                                $logoURL = $newFileName; // Lưu tên file vào biến $logoURL
                            } else {
                                echo "<script>alert('Lỗi khi tải file lên. Vui lòng thử lại.');</script>";
                                $uploadOk = 0;
                            }
                        }
                    } else if ($_FILES['logoURL']['error'] !== UPLOAD_ERR_NO_FILE) { // Xử lý các lỗi upload khác
                        echo "<script>alert('Lỗi upload: " . $_FILES['logoURL']['error'] . "');</script>";
                        $uploadOk = 0;
                    }
        
                    // Thêm công ty vào CSDL nếu upload thành công
                    if ($uploadOk) {
                        $result = addCompany($userID, $companyName, $industry, $websiteURL, $logoURL, $location, $description);
                        if ($result) {
                            echo "<script>alert('Thêm công ty thành công!');</script>";
                            // Redirect để tránh việc submit lại form khi refresh trang
                            //  header("Location: company.php"); // Thay company.php bằng trang bạn muốn chuyển hướng đến
                            exit();
                        } else {
                            echo "<script>alert('Có lỗi xảy ra khi thêm công ty.');</script>";
                        }
                    }
                }
                $companies = getCompanies(); // Lấy danh sách công ty sau khi thêm (nếu có)
                include "views/company.php";
                break;
            
        case 'del_company':
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $id = $_GET['id'];
                $result = delCompany($id);
                if ($result) {
                    echo "<script>alert('Xóa công ty thành công!');</script>";
                } else {
                    echo "<script>alert('Có lỗi xảy ra khi xóa công ty.');</script>";
                }
            }
            $companies = getCompanies();
            include "views/company.php";
            break;
        case 'editform_company':
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $company = getCompanyByID($_GET['id']);
            }
            include "views/company_edit.php";
            break;
            case 'company_edit':
                if (isset($_POST['updateCompany']) && ($_POST['updateCompany'])) {
                    // Lấy dữ liệu từ form
                    $companyID = htmlspecialchars($_POST['companyID']);
                    $userID = htmlspecialchars($_POST['userID']);
                    $companyName = htmlspecialchars($_POST['companyName']);
                    $industry = htmlspecialchars($_POST['industry']);
                    $websiteURL = htmlspecialchars($_POST['websiteURL']);
                    $currentLogoURL = htmlspecialchars($_POST['currentLogoURL']); // Lấy URL logo hiện tại
                    $location = htmlspecialchars($_POST['location']);
                    $description = htmlspecialchars($_POST['description']);
                    
                    // Kiểm tra `UserID` hợp lệ
                    $user = getUserByID($userID);
                    if (!$user) {
                        echo "<script>alert('UserID không hợp lệ. Vui lòng kiểm tra lại.');</script>";
                        return;
                    }
            
                    // Xử lý logo công ty
                    $logoURL = $currentLogoURL; // Sử dụng logo cũ mặc định
                    $uploadOk = 1; // Đảm bảo biến $uploadOk được khởi tạo
            
                    if (isset($_FILES['logoURL']) && $_FILES['logoURL']['error'] === UPLOAD_ERR_OK) {
                        $target_dir = "uploads/"; // Thư mục lưu file
                        if (!is_dir($target_dir)) {
                            mkdir($target_dir, 0777, true); // Tạo thư mục nếu chưa tồn tại
                        }
            
                        // Định dạng tên file
                        $unique_name = uniqid() . "_" . basename($_FILES["logoURL"]["name"]);
                        $target_file = $target_dir . $unique_name;
            
                        // Kiểm tra định dạng file
                        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                        $allowed_file_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                        if (!in_array($imageFileType, $allowed_file_types)) {
                            echo "<script>alert('Chỉ cho phép các tệp JPG, JPEG, PNG, GIF và WEBP.');</script>";
                            $uploadOk = 0;
                        }
            
                        // Kiểm tra kích thước file (tối đa 5MB)
                        if ($_FILES["logoURL"]["size"] > 5000000) {
                            echo "<script>alert('Tệp quá lớn (tối đa 5MB).');</script>";
                            $uploadOk = 0;
                        }
            
                        // Nếu không có lỗi, xử lý upload và xóa ảnh cũ
                        if ($uploadOk == 1) {
                            if (!empty($currentLogoURL) && file_exists("uploads/" . $currentLogoURL)) {
                                unlink("uploads/" . $currentLogoURL); // Xóa ảnh cũ
                            }
                            if (move_uploaded_file($_FILES["logoURL"]["tmp_name"], $target_file)) {
                                $logoURL = $unique_name; // Cập nhật tên file mới
                            } else {
                                echo "<script>alert('Lỗi khi tải file lên. Vui lòng thử lại.');</script>";
                                return;
                            }
                        }
                    } elseif ($_FILES['logoURL']['error'] !== UPLOAD_ERR_NO_FILE) {
                        echo "<script>alert('Có lỗi xảy ra khi tải tệp lên.');</script>";
                        return;
                    }
            
                    // Cập nhật dữ liệu công ty vào cơ sở dữ liệu
                    $result = updateCompany($companyID, $userID, $companyName, $industry, $websiteURL, $logoURL, $location, $description);
            
                    // Thông báo kết quả
                    if ($result) {
                        echo "<script>alert('Cập nhật công ty thành công!');</script>";
                    } else {
                        echo "<script>alert('Có lỗi xảy ra khi cập nhật công ty.');</script>";
                    }
                }
            
                // Lấy lại danh sách công ty
                $companies = getCompanies();
                include "views/company.php";
                break;
            
                case 'get_users':
                    if (isset($_POST['query'])) {
                        $query = $_POST['query'];
                        $users = searchUsers($query); // Giả sử bạn có hàm `searchUsers` để tìm kiếm người dùng
                        if (count($users) > 0) {
                            foreach ($users as $user) {
                                echo '<option value="' . $user['UserID'] . '">' . $user['FullName'] . '</option>';
                            }
                        }
                    }
                    break;
                
                
            
        // Công việc
        case 'job':
            include "views/job.php";
            break;
        case 'job_add':
            if (isset($_POST['addJob']) && ($_POST['addJob'])) {
                $companyID = $_POST['companyID'];
                $jobTitle = $_POST['jobTitle'];
                $jobDescription = $_POST['jobDescription'];
                $requirements = $_POST['requirements'];
                $salaryRange = $_POST['salaryRange'];
                $location = $_POST['location'];
                $employmentType = $_POST['employmentType'];
                $expiryDate = $_POST['expiryDate'];
                $result = addJob($companyID, $jobTitle, $jobDescription, $requirements, $salaryRange, $location, $employmentType, $expiryDate);
                if ($result) {
                    echo "<script>alert('Thêm công việc thành công!');</script>";
                } else {
                    echo "<script>alert('Có lỗi xảy ra khi thêm công việc.');</script>";
                }
            }
            $jobs = getJobs();
            include "views/job.php";
            break;
        case 'del_job':
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $id = $_GET['id'];
                $result = delJob($id);
                if ($result) {
                    echo "<script>alert('Xóa công việc thành công!');</script>";
                } else {
                    echo "<script>alert('Có lỗi xảy ra khi xóa công việc.');</script>";
                }
            }
            $jobs = getJobs();
            include "views/job.php";
            break;
        case 'editform_job':
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $job = getJobByID($_GET['id']);
            }
            include "views/job_edit.php";
            break;
        case 'job_edit':
            if (isset($_POST['updateJob']) && ($_POST['updateJob'])) {
                $jobID = $_POST['jobID'];
                $companyID = $_POST['companyID'];
                $jobTitle = $_POST['jobTitle'];
                $jobDescription = $_POST['jobDescription'];
                $requirements = $_POST['requirements'];
                $salaryRange = $_POST['salaryRange'];
                $location = $_POST['location'];
                $employmentType = $_POST['employmentType'];
                $expiryDate = $_POST['expiryDate'];
                $result = updateJob($jobID, $companyID, $jobTitle, $jobDescription, $requirements, $salaryRange, $location, $employmentType, $expiryDate);
                if ($result) {
                    echo "<script>alert('Cập nhật công việc thành công!');</script>";
                } else {
                    echo "<script>alert('Có lỗi xảy ra khi cập nhật công việc.');</script>";
                }
            }
            $jobs = getJobs();
            include "views/job.php";
            break;

        //danhgia
        case 'review':
            include "views/review.php";
            break;
        //Thống kê
        case 'appliedUsers':
            include "views/appliedUser.php";
            break;
        case 'popularJobs':
            include "views/popularJobs.php";
            break;
        case 'pendingJobs':
            include "views/pendingJobs.php";
            break;
        //Thoát
        case 'thoat':
            unset($_SESSION['UserType']);
            header('location:index.php');    
            break;
        default:
            include "views/home.php";
            break;
    }
    }else{
        include "views/home.php";
    }
        include "views/footer.php";
    } 
    else if(isset($_SESSION['UserType'])&&($_SESSION['UserType']=='Employer')){
        include "views/header.php";
        if (isset($_GET['act'])){
            switch ($_GET['act']) {
                // sử lý các view của user
                case 'home':
                    
                    include "views/home_employer.php";
                    break;
                case 'dangbai':                   
                    if (isset($_POST['addJob']) && ($_POST['addJob'])) {
                        $companyID = $_POST['companyID'];
                        $jobTitle = $_POST['jobTitle'];
                        $jobDescription = $_POST['jobDescription'];
                        $requirements = $_POST['requirements'];
                        $salaryRange = $_POST['salaryRange'];
                        $location = $_POST['location'];
                        $employmentType = $_POST['employmentType'];
                        $expiryDate = $_POST['expiryDate'];
                        $result = addJob($companyID, $jobTitle, $jobDescription, $requirements, $salaryRange, $location, $employmentType, $expiryDate);
                        if ($result) {
                            echo "<script>alert('Thêm công việc thành công!');</script>";
                        } else {
                            echo "<script>alert('Có lỗi xảy ra khi thêm công việc.');</script>";
                        }
                    }
                    $jobs = getJobs();
                    include "views/job.php";
                    break;

                case 'quanglydon':
                    # code...
                    break;
                case 'thoat':
                    unset($_SESSION['UserType']);
                    header('location:index.php');  
                    break;
                default:
                    include "views/home.php";
                    break;
            }
            }else{
                include "views/home.php";
            }
                include "views/footer.php";
    }
     
    else if(isset($_SESSION['UserType'])&&($_SESSION['UserType']=='Candidate ')){
        include "views/header.php";
        include "views/home.php";
        include "views/footer.php";
    }else {
        include "views/header.php";
        if (isset($_GET['act'])){
            
            switch ($_GET['act']) {
                case 'home':
                    
                    include "views/home_candidate.php";
                    break;
                case 'joblist':
                    
                    include "views/joblisting.php";
                    break;
                case 'profile':
                    $_SESSION['UserID'];
                    include "views/profile.php";
                    break;
                case 'thoat':
                    unset($_SESSION['UserType']);
                    header('location:index.php');  
                    break;
                default:
                    include "views/home.php";
                    break;
            }
            }else{
                include "views/home.php";
            }
        include "views/footer.php";
    }
?>
