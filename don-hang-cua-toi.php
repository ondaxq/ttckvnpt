<?php
include 'db.php'; // Kết nối cơ sở dữ liệu
session_start(); // Bắt đầu phiên làm việc

// Lấy idnguoidung từ cơ sở dữ liệu
$tendangnhap = $_SESSION['tendangnhap'] ?? ''; // Lấy tên đăng nhập nếu có
$stmt = $pdo->prepare("SELECT idnguoidung FROM nguoidung WHERE tendangnhap = :tendangnhap");
$stmt->execute(['tendangnhap' => $tendangnhap]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $idnguoidung = $user['idnguoidung'];

    // Lấy danh sách đơn hàng của người dùng, nhóm sản phẩm theo id đơn hàng
    $stmt = $pdo->prepare("
        SELECT d.iddonhang, d.thoigiandat, d.sdtnguoinhan, d.diachi, d.tt ,
               GROUP_CONCAT(CONCAT('<a href=\"chi-tiet-da-dang-nhap.php?Id=', s.Id, '\">', s.Ten, ' (x', ct.soluong, ')</a>') ORDER BY ct.idsanpham SEPARATOR '<br>') AS sanpham
        FROM donhang d
        JOIN chitietdonhang ct ON d.iddonhang = ct.iddonhang
        JOIN sanpham s ON ct.idsanpham = s.Id
        WHERE d.idnguoidung = :idnguoidung
        GROUP BY d.iddonhang
    ");
    $stmt->execute(['idnguoidung' => $idnguoidung]);
    $don_hang = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $don_hang = []; // Nếu không có đơn hàng
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn hàng của tôi</title>
    
    <link rel="stylesheet" type="text/css" href="header.css?<?php echo time(); ?>" />
    <link rel="stylesheet" type="text/css" href="footer.css?<?php echo time(); ?>" />
    <link rel="stylesheet" type="text/css" href="dat-hang.css?<?php echo time(); ?>" />
    <link rel="stylesheet" type="text/css" href="don-hang-cua-toi.css?<?php echo time(); ?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
    <?php include 'header-da-dang-nhap.php'; ?>
    <main>
    <label1 class="font-semibold">Đơn hàng của tôi</label1>
    <?php if (empty($don_hang)): ?>
        <p class="no-orders">Không có đơn hàng nào.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID Đơn hàng</th>
                    <th>Thời gian đặt</th>
                    <th>SĐT người nhận</th>
                    <th>Địa chỉ</th>
                    <th>Sản phẩm</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($don_hang as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['iddonhang']) ?></td>
                        <td><?= htmlspecialchars($order['thoigiandat']) ?></td>
                        <td><?= htmlspecialchars($order['sdtnguoinhan']) ?></td>
                        <td><?= htmlspecialchars($order['diachi']) ?></td>
                        <td><?= htmlspecialchars_decode($order['sanpham']) ?></td> <!-- Hiển thị tên sản phẩm và số lượng -->
                        <td><?php if($order['tt'] == 1)
                                     { echo ('Đã xác nhận');} 
                                     else if($order['tt'] == 2)
                                     { echo ('Đã giao');} 
                                     else {
                                        echo ('Chưa xác nhận');
                                     } ?></td>
                                   <td><?php if($order['tt'] == 2)
                                     {
                                     ?>
                                     <button class="text-red-500 hover:text-red-700 ml-2"  disabled>
                                    <i class="fas fa-trash"></i> Hủy đơn
                                    </button>
                                     <?php
                                     } else {?>
                                     <button class="text-red-500 hover:text-red-700 ml-2" onclick="deleteOrder(<?php echo $order['iddonhang']; ?>)">
                                     <i class="fas fa-trash"></i> Hủy đơn
                                 </button>
                                 <?php }?>
                                </td>  
                    </tr>
                    
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
<script>
    function deleteOrder(orderId) {
    if (confirm("Bạn có chắc chắn muốn hủy đơn hàng này không?")) {
        fetch('admin-donhang-xoa.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: orderId }) 
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const orderElement = document.getElementById('order-' + orderId);
                if (orderElement) {
                    orderElement.remove();  
                }
                alert("Đơn hàng đã được hủy thành công.");
                location.reload();
            } else {
                alert("Lỗi: " + (data.error || "Không thể hủy đơn hàng."));
            }
        })
        .catch(error => {
            alert("Lỗi kết nối: " + error.message);  
        });
    }
}
</script>