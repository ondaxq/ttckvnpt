<?php
session_start();
include 'db.php'; 
if (isset($_SESSION['tendangnhap'])) {
    $tendangnhap = $_SESSION['tendangnhap'];

    $stmt = $pdo->prepare("SELECT hoten, sdt FROM nguoidung WHERE tendangnhap = :tendangnhap");
    $stmt->execute(['tendangnhap' => $tendangnhap]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    $user = null;
}
$sql = "SELECT SUM(SoLuongTonKho) AS total_stock FROM sanpham";
$stmt = $pdo->prepare($sql); 
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_stock = $row['total_stock'] ?? 0; 
$sql = "SELECT COUNT(idnguoidung) AS total_users FROM nguoidung";
$stmt = $pdo->prepare($sql); 
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_users = $row['total_users'] ?? 0;
$sql = "SELECT SUM(sanpham.Gia * chitietdonhang.soluong) AS total_revenue
        FROM chitietdonhang
        JOIN sanpham ON chitietdonhang.idsanpham = sanpham.Id";
$stmt = $pdo->prepare($sql); 
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_revenue = $row['total_revenue'] ?? 0;  
$sql = "SELECT COUNT(iddonhang) AS total_orders_today
        FROM donhang
        WHERE thoigiandat > NOW() - INTERVAL 1 DAY";
$stmt = $pdo->prepare($sql); 
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_orders_today = $row['total_orders_today'] ?? 0;  
$sql = "SELECT donhang.iddonhang, nguoidung.hoten, donhang.thoigiandat, 
               SUM(sanpham.Gia * chitietdonhang.soluong) AS total
        FROM donhang
        JOIN nguoidung ON donhang.idnguoidung = nguoidung.idnguoidung
        JOIN chitietdonhang ON donhang.iddonhang = chitietdonhang.iddonhang
        JOIN sanpham ON chitietdonhang.idsanpham = sanpham.Id
        GROUP BY donhang.iddonhang";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt = $pdo->prepare("SELECT idnguoidung, hoten, sdt,email, vaitro FROM nguoidung");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt = $pdo->prepare("SELECT * FROM lienhe");
$stmt->execute();
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$data = [
    'total_stock' => $total_stock,
    'total_users' => $total_users,
    'total_revenue' => $total_revenue,
    'total_orders_today' => $total_orders_today,
    'orders' => $orders,
    'users' => $users,
    'contacts' => $contacts
];

echo json_encode($data);
?>
