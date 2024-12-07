<?php
// Thêm công ty mới
function addCompany($userID, $companyName, $industry, $websiteURL, $logoURL, $location, $description) {
    $conn = connectdb();
    $stmt = $conn->prepare("INSERT INTO companies (UserID, CompanyName, Industry, WebsiteURL, LogoURL, Location, Description) 
                            VALUES (:userID, :companyName, :industry, :websiteURL, :logoURL, :location, :description)");
    
    $stmt->bindParam(':userID', $userID);
    $stmt->bindParam(':companyName', $companyName);
    $stmt->bindParam(':industry', $industry);
    $stmt->bindParam(':websiteURL', $websiteURL);
    $stmt->bindParam(':logoURL', $logoURL);
    $stmt->bindParam(':location', $location);
    $stmt->bindParam(':description', $description);
    
    return $stmt->execute();
}


// Lấy danh sách tất cả các công ty
function getCompanies() {
    $conn = connectdb();
    $stmt = $conn->prepare("SELECT * FROM companies");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Lấy thông tin công ty theo ID
function getCompanyByID($companyID) {
    $conn = connectdb();
    $stmt = $conn->prepare("SELECT * FROM companies WHERE CompanyID = :companyID");
    $stmt->bindParam(':companyID', $companyID, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


// Xóa công ty
function delCompany($companyID) {
    $conn = connectdb();
    
    $stmt = $conn->prepare("DELETE FROM companies WHERE CompanyID = :companyID");
    $stmt->bindParam(':companyID', $companyID, PDO::PARAM_INT);
    
    return $stmt->execute();
}


// Cập nhật thông tin công ty
function updateCompany($companyID, $userID, $companyName, $industry, $websiteURL, $logoURL, $location, $description) {
    $conn = connectdb();
    
    $stmt = $conn->prepare("UPDATE companies SET 
        UserID = :userID,
        CompanyName = :companyName,
        Industry = :industry,
        WebsiteURL = :websiteURL,
        LogoURL = :logoURL,
        Location = :location,
        Description = :description,
        UpdatedAt = NOW()
        WHERE CompanyID = :companyID");
    
    $stmt->bindParam(':companyID', $companyID);
    $stmt->bindParam(':userID', $userID);
    $stmt->bindParam(':companyName', $companyName);
    $stmt->bindParam(':industry', $industry);
    $stmt->bindParam(':websiteURL', $websiteURL);
    $stmt->bindParam(':logoURL', $logoURL);
    $stmt->bindParam(':location', $location);
    $stmt->bindParam(':description', $description);
    
    return $stmt->execute();
}

function getCompaniesForJob() {
    $conn = connectdb();
    $stmt = $conn->prepare("SELECT CompanyID, CompanyName FROM companies");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
