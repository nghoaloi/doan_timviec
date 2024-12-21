<?php
function connectdb() {
    $servername = "auth-db1637.hstgr.io";
    $username = "u773112933_cn_loi_huy";
    $password = "12345678@Lh";
    $dbname = "u773112933_cn_loi_huy";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "Connected successfully"; // Loại bỏ dòng này trong môi trường sản xuất
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        return null; // Trả về null nếu kết nối thất bại
    }
    return $conn; // Trả về đối tượng kết nối
}
?>
