<?php
// Thêm công ty mới
function addCompany($userID, $companyName, $industry, $websiteURL, $logoURL, $location, $description) {
    $conn = connectdb();

    try {
        // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
        $conn->beginTransaction();

        // Chuẩn bị câu lệnh SQL
        $stmt = $conn->prepare("INSERT INTO companies (UserID, CompanyName, Industry, WebsiteURL, LogoURL, Location, Description) 
                                VALUES (:userID, :companyName, :industry, :websiteURL, :logoURL, :location, :description)");

        // Bind các tham số với kiểu dữ liệu rõ ràng để tránh SQL Injection
        $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        $stmt->bindParam(':companyName', $companyName, PDO::PARAM_STR);
        $stmt->bindParam(':industry', $industry, PDO::PARAM_STR);
        $stmt->bindParam(':websiteURL', $websiteURL, PDO::PARAM_STR);
        $stmt->bindParam(':logoURL', $logoURL, PDO::PARAM_STR);
        $stmt->bindParam(':location', $location, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);

        // Thực thi câu lệnh
        if (!$stmt->execute()) {
            $errorInfo = $stmt->errorInfo();
            error_log("Lỗi SQL khi thêm công ty: " . $errorInfo[2]); // Ghi log lỗi chi tiết
            $conn->rollBack(); // Rollback transaction nếu có lỗi
            return false;
        }

        // Commit transaction nếu thành công
        $conn->commit();
        return true;

    } catch (PDOException $e) {
        // Bắt lỗi PDOException (lỗi kết nối CSDL, lỗi SQL...)
        error_log("Lỗi CSDL: " . $e->getMessage());
        $conn->rollBack(); // Rollback transaction nếu có lỗi
        return false;
    } catch (Exception $e) {
        // Bắt các loại exception khác
        error_log("Lỗi: " . $e->getMessage());
        $conn->rollBack(); // Rollback transaction nếu có lỗi
        return false;
    }
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
function getCompanyNameByID($companyID) {
    $conn = connectdb();
    if ($conn) {
        $stmt = $conn->prepare("SELECT CompanyName FROM companies WHERE CompanyID = :companyID");
        $stmt->bindParam(':companyID', $companyID, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result['CompanyName'];
        }
    }
    return null;
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
