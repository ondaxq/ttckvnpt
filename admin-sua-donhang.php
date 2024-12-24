<?php
// admin-donhang-chitiet.php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true); // Lấy dữ liệu từ request

if (isset($data['id'])) {
    $orderId = $data['id'];

    // Truy vấn thông tin chi tiết đơn hàng
    $stmt = $pdo->prepare("
        SELECT 
            donhang.iddonhang,
            donhang.thoigiandat,
            donhang.sdtnguoinhan,
            donhang.diachi,
            nguoidung.hoten AS tennguoidhang,
            GROUP_CONCAT(CONCAT(sanpham.ten, ' (x', chitietdonhang.soluong, ')') ORDER BY chitietdonhang.idsanpham SEPARATOR '<br>') AS sanpham,
            SUM(chitietdonhang.soluong * sanpham.gia) AS thanhtien
        FROM donhang
        JOIN nguoidung ON donhang.idnguoidung = nguoidung.idnguoidung
        JOIN chitietdonhang ON donhang.iddonhang = chitietdonhang.iddonhang
        JOIN sanpham ON chitietdonhang.idsanpham = sanpham.id
        WHERE donhang.iddonhang = :iddonhang
        GROUP BY donhang.iddonhang;
    ");
    $stmt->execute(['iddonhang' => $orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($order) {
        // Trả về dữ liệu chi tiết đơn hàng
        echo json_encode([
            'success' => true,
            'order' => $order
        ]);
    } else {
        // Nếu không tìm thấy đơn hàng
        echo json_encode([
            'success' => false,
            'message' => 'Không tìm thấy đơn hàng.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'ID đơn hàng không hợp lệ.'
    ]);
}
