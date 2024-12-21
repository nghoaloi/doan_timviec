<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Đánh Giá</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        h2 {
            color: #343a40;
        }
        .container-fluid {
            background-color: #fff;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-control, .form-select {
            height: 38px;
            font-size: 14px;
        }
        .form-textarea {
            font-size: 14px;
        }
        .btn {
            border-radius: 5px;
        }
        .table th {
            background-color: #343a40;
            color: #fff;
        }
        .table td, .table th {
            vertical-align: middle;
        }
        .card-header {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
    </style>
</head>
<body>
<section class="mt-5 pt-5">
    <div class="container-fluid py-5">
        <h2 class="text-center text-uppercase mb-4">Quản lý đánh giá</h2>
        <div class="row gx-5">
            <!-- Danh sách đánh giá -->
            <div class="col-lg-12">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-dark text-white">
                        <h5 class="card-title mb-0">Danh sách đánh giá</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Công ty</th>
                                        <th>Người dùng</th>
                                        <th>Đánh giá</th>
                                        <th>Nội dung</th>
                                        <th>Ngày tạo</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (getReviews() as $index => $review): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= getCompanyNameByID($review['CompanyID']) ?></td>
                                            <td><?= getUserNameByID($review['UserID']) ?></td>
                                            <td><?= $review['Rating'] ?></td>
                                            <td><?= $review['ReviewText'] ?></td>
                                            <td><?= $review['CreatedAt'] ?></td>
                                            <td>
                                                <button class="btn btn-warning btn-sm" onclick="viewReview(<?= $review['ReviewID'] ?>)">Xem chi tiết</button>
                                                <a href="index.php?act=del_review&id=<?= $review['ReviewID'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa đánh giá này không?')">Xóa</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty(getReviews())): ?>
                                        <tr>
                                            <td colspan="7" class="text-center">Không có đánh giá nào.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Xem Chi Tiết -->
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewModalLabel">Chi tiết đánh giá</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Nội dung chi tiết đánh giá sẽ được cập nhật bằng JavaScript -->
                    <div id="reviewDetails"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function viewReview(reviewID) {
    fetch(`index.php?act=get_review&id=${reviewID}`)
        .then(response => response.json())
        .then(data => {
            if (data) {
                let reviewDetails = `
                    <p><strong>Công ty:</strong> ${data.CompanyID}</p>
                    <p><strong>Người dùng:</strong> ${data.UserID}</p>
                    <p><strong>Đánh giá:</strong> ${data.Rating}</p>
                    <p><strong>Nội dung:</strong> ${data.ReviewText}</p>
                    <p><strong>Ngày tạo:</strong> ${data.CreatedAt}</p>
                `;
                document.getElementById('reviewDetails').innerHTML = reviewDetails;
                let modal = new bootstrap.Modal(document.getElementById('reviewModal'), {});
                modal.show();
            }
        })
        .catch(error => console.error('Error:', error));
}
</script>
</body>
</html>
