<?php
    session_start();
    ob_start();
    include "model/connectdb.php";
    include "model/user.php";
    include "model/company.php";
    include "model/job.php";

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
                    $password = password_hash($password, PASSWORD_DEFAULT);
            
                    // Xử lý upload hình ảnh
                    $profilePictureURL = '';
                    $uploadOk = 1;
            
                    if (isset($_FILES['profilePictureURL']) && $_FILES['profilePictureURL']['error'] == 0) {
                        $target_dir = "uploads/";
                        if (!is_dir($target_dir)) {
                            mkdir($target_dir, 0777, true);
                        }
                        $unique_name = uniqid() . "_" . basename($_FILES["profilePictureURL"]["name"]);
                        $target_file = $target_dir . $unique_name;
                        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            
                        // Kiểm tra định dạng file
                        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                            echo "<script>alert('Chỉ cho phép các tệp JPG, JPEG, PNG, GIF, và WEBP.');</script>";
                            $uploadOk = 0;
                        }
            
                        // Kiểm tra kích thước file
                        if ($_FILES["profilePictureURL"]["size"] > 5000000) {
                            echo "<script>alert('Tệp quá lớn (tối đa 5MB).');</script>";
                            $uploadOk = 0;
                        }
            
                        // Upload file
                        if ($uploadOk == 1) {
                            if (move_uploaded_file($_FILES["profilePictureURL"]["tmp_name"], $target_file)) {
                                $profilePictureURL = $unique_name; // Lưu tên tệp ngẫu nhiên
                            } else {
                                echo "<script>alert('Lỗi khi tải tệp lên.');</script>";
                            }
                        }
                    }
            
                    if ($emailExists) {
                        echo "<script>alert('Email đã tồn tại!');</script>";
                    } else {
                        $result = addUser($email, $password, $fullname, $phone, $usertype, $status, $profilePictureURL);
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
                        
                        // Kiểm tra và mã hóa mật khẩu nếu có thay đổi
                        if (!empty($_POST['password'])) {
                            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                        } else {
                            $password = $_POST['currentPassword']; // Sử dụng mật khẩu cũ nếu không nhập mới
                        }
                
                        // Xử lý ảnh đại diện
                        $profilePictureURL = $_POST['currentProfilePictureURL']; // Ảnh cũ mặc định
                        $uploadOk = 1; // Đảm bảo biến $uploadOk được khởi tạo
                
                        if (isset($_FILES['profilePictureURL']) && $_FILES['profilePictureURL']['error'] === UPLOAD_ERR_OK) {
                            $target_dir = "uploads/"; // Thư mục lưu file
                            if (!is_dir($target_dir)) {
                                mkdir($target_dir, 0777, true); // Tạo thư mục nếu chưa tồn tại
                            }
                
                            // Định dạng tên file
                            $unique_name = uniqid() . "_" . basename($_FILES["profilePictureURL"]["name"]);
                            $target_file = $target_dir . $unique_name;
                
                            // Kiểm tra định dạng file
                            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                            $allowed_file_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                            if (!in_array($imageFileType, $allowed_file_types)) {
                                echo "<script>alert('Chỉ cho phép các tệp JPG, JPEG, PNG, GIF và WEBP.');</script>";
                                $uploadOk = 0;
                            }
                
                            // Kiểm tra kích thước file (tối đa 5MB)
                            if ($_FILES["profilePictureURL"]["size"] > 5000000) {
                                echo "<script>alert('Tệp quá lớn (tối đa 5MB).');</script>";
                                $uploadOk = 0;
                            }
                
                            // Nếu không có lỗi, xử lý upload và xóa ảnh cũ
                            if ($uploadOk == 1) {
                                if (!empty($profilePictureURL) && file_exists("uploads/" . $profilePictureURL)) {
                                    unlink("uploads/" . $profilePictureURL); // Xóa ảnh cũ
                                }
                                if (move_uploaded_file($_FILES["profilePictureURL"]["tmp_name"], $target_file)) {
                                    $profilePictureURL = $unique_name; // Cập nhật tên file mới
                                } else {
                                    echo "<script>alert('Lỗi khi tải file lên. Vui lòng thử lại.');</script>";
                                    return;
                                }
                            }
                        } elseif ($_FILES['profilePictureURL']['error'] !== UPLOAD_ERR_NO_FILE) {
                            echo "<script>alert('Có lỗi xảy ra khi tải tệp lên.');</script>";
                            return;
                        }
                
                        // Cập nhật dữ liệu người dùng vào cơ sở dữ liệu
                        $result = updateUser($userID, $email, $password, $fullname, $phone, $usertype, $status, $profilePictureURL, $address, $dateOfBirth, $gender, $bio);
                
                        // Thông báo kết quả
                        if ($result) {
                            echo "<script>alert('Cập nhật người dùng thành công!');</script>";
                        } else {
                            echo "<script>alert('Có lỗi xảy ra khi cập nhật người dùng. Vui lòng kiểm tra thông tin nhập vào.');</script>";
                        }
                    }
                
                    // Lấy lại danh sách người dùng
                    $users = getUsers();
                    include "views/user.php";
                    break;                           
        // Xử lý thêm công ty
        case 'company':
            include "views/company.php";
            break;
            case 'company_add':
                if (isset($_POST['addCompany']) && ($_POST['addCompany'])) {
                    $userID = htmlspecialchars($_POST['userID']);
                    $companyName = htmlspecialchars($_POST['companyName']);
                    $industry = htmlspecialchars($_POST['industry']);
                    $websiteURL = htmlspecialchars($_POST['websiteURL']);
                    $location = htmlspecialchars($_POST['location']);
                    $description = htmlspecialchars($_POST['description']);
                    
                    // Khởi tạo biến $uploadOk
                    $uploadOk = 1;
            
                    // Xử lý ảnh logo
                    $logoURL = '';
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
            
                        // Nếu không có lỗi, xử lý upload
                        if ($uploadOk == 1) {
                            if (move_uploaded_file($_FILES["logoURL"]["tmp_name"], $target_file)) {
                                $logoURL = $unique_name; // Cập nhật tên file mới
                            } else {
                                echo "<script>alert('Lỗi khi tải file lên. Vui lòng thử lại.');</script>";
                                return;
                            }
                        }
                    }
            
                    // Thêm công ty vào cơ sở dữ liệu
                    $result = addCompany($userID, $companyName, $industry, $websiteURL, $logoURL, $location, $description);
                    
                    // Thông báo kết quả
                    if ($result) {
                        echo "<script>alert('Thêm công ty thành công!');</script>";
                    } else {
                        echo "<script>alert('Có lỗi xảy ra khi thêm công ty.');</script>";
                    }
                }
                $companies = getCompanies();
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

                case 'updateuser_foruser':
                    if (isset($_POST['updateuser_foruser']) && ($_POST['updateuser_foruser'])) {
                        // Lấy dữ liệu từ form
                        var_dump($_POST['updateuser_foruser']);
                        $userID = htmlspecialchars($_POST['userid']);
                        $email = htmlspecialchars($_POST['email']);
                        $fullname = htmlspecialchars($_POST['fullname']);
                        $phone = htmlspecialchars($_POST['phone']);
                        $address = htmlspecialchars($_POST['address']);
                        $dateOfBirth = htmlspecialchars($_POST['dateOfBirth']);
                        $gender = htmlspecialchars($_POST['gender']);
                        $bio = htmlspecialchars($_POST['bio']);
                        
                        
                        // Kiểm tra và mã hóa mật khẩu nếu có thay đổi
                        if (!empty($_POST['password'])) {
                            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                        } else {
                            $password = $_POST['currentPassword']; // Sử dụng mật khẩu cũ nếu không nhập mới
                        }
                
                        // Xử lý ảnh đại diện
                        $profilePictureURL = $_POST['currentProfilePictureURL']; // Ảnh cũ mặc định
                        $uploadOk = 1; // Đảm bảo biến $uploadOk được khởi tạo
                
                        if (isset($_FILES['profilePictureURL']) && $_FILES['profilePictureURL']['error'] === UPLOAD_ERR_OK) {
                            $target_dir = "uploads/"; // Thư mục lưu file
                            if (!is_dir($target_dir)) {
                                mkdir($target_dir, 0777, true); // Tạo thư mục nếu chưa tồn tại
                            }
                
                            // Định dạng tên file
                            $unique_name = uniqid() . "_" . basename($_FILES["profilePictureURL"]["name"]);
                            $target_file = $target_dir . $unique_name;
                
                            // Kiểm tra định dạng file
                            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                            $allowed_file_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                            if (!in_array($imageFileType, $allowed_file_types)) {
                                echo "<script>alert('Chỉ cho phép các tệp JPG, JPEG, PNG, GIF và WEBP.');</script>";
                                $uploadOk = 0;
                            }
                
                            // Kiểm tra kích thước file (tối đa 5MB)
                            if ($_FILES["profilePictureURL"]["size"] > 5000000) {
                                echo "<script>alert('Tệp quá lớn (tối đa 5MB).');</script>";
                                $uploadOk = 0;
                            }
                
                            // Nếu không có lỗi, xử lý upload và xóa ảnh cũ
                            if ($uploadOk == 1) {
                                if (!empty($profilePictureURL) && file_exists("uploads/" . $profilePictureURL)) {
                                    unlink("uploads/" . $profilePictureURL); // Xóa ảnh cũ
                                }
                                if (move_uploaded_file($_FILES["profilePictureURL"]["tmp_name"], $target_file)) {
                                    $profilePictureURL = $unique_name; // Cập nhật tên file mới
                                } else {
                                    echo "<script>alert('Lỗi khi tải file lên. Vui lòng thử lại.');</script>";
                                    return;
                                }
                            }
                        } elseif ($_FILES['profilePictureURL']['error'] !== UPLOAD_ERR_NO_FILE) {
                            echo "<script>alert('Có lỗi xảy ra khi tải tệp lên.');</script>";
                            return;
                        }
                
                        // Cập nhật dữ liệu người dùng vào cơ sở dữ liệu
                        $result = updateUser_foruser($email, $password, $fullname, $phone, $profilePictureURL, $address, $dateOfBirth, $gender, $bio);
                
                        // Thông báo kết quả
                        if ($result) {
                            echo "<script>alert('Cập nhật người dùng thành công!');</script>";
                        } else {
                            echo "<script>alert('Có lỗi xảy ra khi cập nhật người dùng. Vui lòng kiểm tra thông tin nhập vào.');</script>";
                        }
                    }
                
                        include "views/profile.php";
                    break;
                case 'mo_updateuser_foruser':
                    if (isset($_GET['id']) && !empty($_GET['id'])) {
                        $user=getUserByID($_GET['id']);
                    }
                    include "views/edit_user_for_user.php";
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
