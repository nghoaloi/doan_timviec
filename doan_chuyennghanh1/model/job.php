<?php

// Kết nối cơ sở dữ liệu
// function connectdb() {
//     try {
//         $conn = new PDO("mysql:host=localhost;dbname=your_database", "username", "password");
//         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//         return $conn;
//     } catch (PDOException $e) {
//         echo "Connection failed: " . $e->getMessage();
//     }
// }

// Thêm công việc mới
function addJob($companyID, $jobTitle, $jobDescription, $requirements, $salaryRange, $location, $employmentType, $expiryDate) {
    $conn = connectdb();
    $stmt = $conn->prepare("INSERT INTO jobs (CompanyID, JobTitle, JobDescription, Requirements, SalaryRange, Location, EmploymentType, ExpiryDate) VALUES (:companyID, :jobTitle, :jobDescription, :requirements, :salaryRange, :location, :employmentType, :expiryDate)");
    $stmt->bindParam(':companyID', $companyID);
    $stmt->bindParam(':jobTitle', $jobTitle);
    $stmt->bindParam(':jobDescription', $jobDescription);
    $stmt->bindParam(':requirements', $requirements);
    $stmt->bindParam(':salaryRange', $salaryRange);
    $stmt->bindParam(':location', $location);
    $stmt->bindParam(':employmentType', $employmentType);
    $stmt->bindParam(':expiryDate', $expiryDate);
    return $stmt->execute();
}


// Lấy danh sách tất cả các công việc
function getJobs() {
    $conn = connectdb();
    // Thực hiện join giữa bảng jobs và bảng companies để lấy tên công ty
    $stmt = $conn->prepare("
        SELECT jobs.*, companies.CompanyName,companies.LogoURL
        FROM jobs 
        JOIN companies ON jobs.CompanyID = companies.CompanyID
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// Lấy thông tin công việc theo ID
function getJobByID($jobID) {
    $conn = connectdb();
    $stmt = $conn->prepare("SELECT jobs.*, companies.CompanyName,companies.LogoURL FROM jobs join companies ON jobs.CompanyID = companies.CompanyID WHERE JobID = :jobID");
    $stmt->bindParam(':jobID', $jobID, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
//lấy thông tin công việc đã apply

function getJobByID_user($userid, $jobID) {
    $conn = connectdb();
    $stmt = $conn->prepare("SELECT savedjobs.*,jobs.*,companies.CompanyName,companies.LogoURL
    FROM savedjobs 
    JOIN jobs ON savedjobs.JobID = jobs.JobID
    join companies ON jobs.CompanyID = companies.CompanyID
    WHERE savedjobs.UserID = :userid AND jobs.JobID = :jobID");
    $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
    $stmt->bindParam(':jobID', $jobID, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
// lấy tất id công việc của user hiện hành
function getallidjob_by_userid($userid){
    $conn = connectdb();
    $stmt = $conn->prepare("SELECT jobs.*,companies.*
    FROM savedjobs
    join jobs on savedjobs.JobID = jobs.JobID
    join companies  on companies.CompanyID = jobs.CompanyID
    WHERE savedjobs.UserID = :userid");
    $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// kiểm tra nếu công việc có trong savejob không
function checkjob_in_list($userid,$jobID){
    $conn = connectdb();
    $stmt = $conn->prepare("SELECT savedjobs.JobID
    FROM savedjobs
    WHERE savedjobs.UserID = :userid and savedjobs.JobID = :jobID");
    $stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
    $stmt->bindParam(':jobID', $jobID, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
// Xóa công việc
function delJob($jobID) {
    $conn = connectdb();
    $stmt = $conn->prepare("DELETE FROM jobs WHERE JobID = :jobID");
    $stmt->bindParam(':jobID', $jobID, PDO::PARAM_INT);
    return $stmt->execute();
}

// Cập nhật thông tin công việc
function updateJob($jobID, $companyID, $jobTitle, $jobDescription, $requirements, $salaryRange, $location, $employmentType, $expiryDate) {
    $conn = connectdb();
    $stmt = $conn->prepare("UPDATE jobs 
                            SET CompanyID = :companyID, JobTitle = :jobTitle, JobDescription = :jobDescription, Requirements = :requirements, SalaryRange = :salaryRange, Location = :location, EmploymentType = :employmentType, ExpiryDate = :expiryDate 
                            WHERE JobID = :jobID");
    $stmt->bindParam(':jobID', $jobID, PDO::PARAM_INT);
    $stmt->bindParam(':companyID', $companyID);
    $stmt->bindParam(':jobTitle', $jobTitle);
    $stmt->bindParam(':jobDescription', $jobDescription);
    $stmt->bindParam(':requirements', $requirements);
    $stmt->bindParam(':salaryRange', $salaryRange);
    $stmt->bindParam(':location', $location);
    $stmt->bindParam(':employmentType', $employmentType);
    $stmt->bindParam(':expiryDate', $expiryDate);
    return $stmt->execute();
}

?>
