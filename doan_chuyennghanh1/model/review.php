<?php
// // Thêm đánh giá mới
// function addReview($companyID, $userID, $rating, $reviewText) {
//     $conn = connectdb();
//     if ($conn) {
//         $stmt = $conn->prepare("INSERT INTO reviews (CompanyID, UserID, Rating, ReviewText) 
//                                 VALUES (:companyID, :userID, :rating, :reviewText)");
//         $stmt->bindParam(':companyID', $companyID);
//         $stmt->bindParam(':userID', $userID);
//         $stmt->bindParam(':rating', $rating);
//         $stmt->bindParam(':reviewText', $reviewText);
//         return $stmt->execute();
//     }
//     return false;
// }

// Lấy danh sách tất cả các đánh giá
// Lấy danh sách đánh giá 
function getReviews() {
    $conn = connectdb(); $stmt = $conn->prepare("SELECT * FROM reviews");
    $stmt->execute(); 
    return $stmt->fetchAll(PDO::FETCH_ASSOC); 
}

// Lấy thông tin đánh giá theo ID
function getReviewByID($reviewID) {
    $conn = connectdb();
    if ($conn) {
        $stmt = $conn->prepare("SELECT * FROM reviews WHERE ReviewID = :reviewID");
        $stmt->bindParam(':reviewID', $reviewID, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}

// Xóa đánh giá
function deleteReview($reviewID) {
    $conn = connectdb();
    if ($conn) {
        $stmt = $conn->prepare("DELETE FROM reviews WHERE ReviewID = :reviewID");
        $stmt->bindParam(':reviewID', $reviewID, PDO::PARAM_INT);
        return $stmt->execute();
    }
    return false;
}
if (isset($_GET['act'])) { 
    $action = $_GET['act']; 
    if ($action == 'del_review' && isset($_GET['id'])) { 
        $reviewID = $_GET['id']; 
        if (deleteReview($reviewID)) { 
            echo "Đánh giá đã được xóa thành công."; 
        } else { 
            echo "Lỗi khi xóa đánh giá."; 
        } 
    } elseif ($action == 'get_review' && isset($_GET['id'])) { 
        $reviewID = $_GET['id']; $review = getReviewByID($reviewID); 
        if ($review) { echo json_encode($review); 
        } else { 
            echo "Đánh giá không tồn tại."; 
        } 
    }
}
?>
