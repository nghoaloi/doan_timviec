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
        case 'user':
            include "views/user.php";
            break;
        case 'user_add':
            if (isset($_POST['adduser']) && ($_POST['adduser'])) {
                $email = $_POST['email']; 
                $password = $_POST['password']; 
                $fullname = $_POST['fullname']; 
                $phone = $_POST['phone']; 
                $usertype = $_POST['usertype']; 
                $status = $_POST['status']; 
                $emailExists = checkEmailExists($email); 
                if ($emailExists) { 
                    echo "<script>alert('Email đã tồn tại!');</script>"; 
                } else { 
                    $result = addUser($email, $password, $fullname, $phone, $usertype, $status); 
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
        
            
// Xử lý thêm công ty
case 'company':
    include "views/company.php";
    break;
case 'company_add':
    if (isset($_POST['addCompany']) && ($_POST['addCompany'])) {
        $userID = $_POST['userID'];
        $companyName = $_POST['companyName'];
        $industry = $_POST['industry'];
        $websiteURL = $_POST['websiteURL'];
        $logoURL = $_POST['logoURL'];
        $location = $_POST['location'];
        $description = $_POST['description'];
        $result = addCompany($userID, $companyName, $industry, $websiteURL, $logoURL, $location, $description);
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
        $companyID = $_POST['companyID'];
        $companyName = $_POST['companyName'];
        $industry = $_POST['industry'];
        $websiteURL = $_POST['websiteURL'];
        $logoURL = $_POST['logoURL'];
        $location = $_POST['location'];
        $description = $_POST['description'];
        $result = updateCompany($companyID, $companyName, $industry, $websiteURL, $logoURL, $location, $description);
        if ($result) {
            echo "<script>alert('Cập nhật công ty thành công!');</script>";
        } else {
            echo "<script>alert('Có lỗi xảy ra khi cập nhật công ty.');</script>";
        }
    }
    $companies = getCompanies();
    include "views/company.php";
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
    } else if(isset($_SESSION['UserType'])&&($_SESSION['UserType']=='Candidate')){
        include "views/header.php";
        if (isset($_GET['act'])){
            switch ($_GET['act']) {
                case 'register_ntd':
                    include "views/register_ntd.php";
                    break;
                case 'login':
                    include "views/login_ntd.php";
                    break;
                default:
                    include "views/home.php";
                    break;
            }
            }else{
                include "views/home.php";
            }
                include "views/footer.php";
    } else if(isset($_SESSION['UserType'])&&($_SESSION['UserType']=='Candidate')){
        include "views/header.php";
        include "views/home.php";
        include "views/footer.php";
    }else {
        include "views/header.php";
        if (isset($_GET['act'])){
            switch ($_GET['act']) {
                case 'user':
                    include "views/user.php";
                    break;
                case 'register_ntd':
                    include "views/register_ntd.php";
                    break;
                case 'login':
                    include "views/login_ntd.php";
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
