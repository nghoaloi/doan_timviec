<?php
function getApplycation_Accepted($sortColumn = 'u.FullName ASC') {
    $allowedSortColumns = ['u.FullName ASC', 'u.FullName DESC', 'a.AppliedAt ASC', 'a.AppliedAt DESC'];
    if (!in_array($sortColumn, $allowedSortColumns)) {
        $sortColumn = 'u.FullName ASC'; // Giá trị mặc định an toàn
    }

    $conn = connectdb();
    $query = "SELECT 
        u.FullName AS `Tên User`, 
        u.Email AS `Email`, 
        u.PhoneNumber AS `Số điện thoại`, 
        j.JobTitle AS `Công việc đã ứng tuyển`, 
        a.AppliedAt AS `Ngày ứng tuyển`, 
        a.ApplicationStatus AS `Trạng thái ứng tuyển`
    FROM 
        applications a
    JOIN 
        users u ON a.UserID = u.UserID
    JOIN 
        jobs j ON a.JobID = j.JobID
    WHERE 
        a.ApplicationStatus = 'Accepted'
    ORDER BY $sortColumn";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTopJobs($sortOrder = 'DESC') {
    $conn = connectdb();
    $query = "SELECT 
    j.JobTitle AS `Tên công việc`,
    c.CompanyName AS `Tên công ty`,
    COUNT(a.UserID) AS `Số lượng user`,
    j.JobDescription AS `Mô tả công việc`
FROM 
    jobs j
JOIN 
    companies c ON j.CompanyID = c.CompanyID
JOIN 
    applications a ON j.JobID = a.JobID
GROUP BY 
    j.JobID, j.JobTitle, c.CompanyName, j.JobDescription
ORDER BY 
    COUNT(a.UserID) $sortOrder";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPendingJobs($sortOrder = 'DESC') {
    $conn = connectdb();
    $query = "SELECT 
    j.JobTitle AS `Tên công việc`,
    c.CompanyName AS `Tên công ty`,
    COUNT(a.UserID) AS `Số lượng user Pending`
FROM 
    jobs j
JOIN 
    companies c ON j.CompanyID = c.CompanyID
JOIN 
    applications a ON j.JobID = a.JobID
WHERE 
    a.ApplicationStatus = 'Pending'
GROUP BY 
    j.JobID, j.JobTitle, c.CompanyName
ORDER BY 
    COUNT(a.UserID) $sortOrder";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>