<?php
// File: behome.php
include 'db.php'; // Kết nối với cơ sở dữ liệu

// Xác định số trang và số sản phẩm mỗi trang
$items_per_page = 28;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$current_page = max(1, $current_page);
$offset = ($current_page - 1) * $items_per_page;

// Truy vấn số lượng sản phẩm
$total_stmt = $pdo->query("SELECT COUNT(*) FROM sanpham");
$total_items = $total_stmt->fetchColumn();
$total_pages = ceil($total_items / $items_per_page);

// Lấy danh sách sản phẩm
$stmt = $pdo->prepare("SELECT * FROM sanpham LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Chuẩn bị dữ liệu JSON
$response = [
    'products' => $products,
    'current_page' => $current_page,
    'total_pages' => $total_pages
];
echo json_encode($response);
?>
