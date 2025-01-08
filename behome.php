<?php
include 'db.php';

try {
    $stmt = $pdo->query("SELECT * FROM sanpham");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} catch (PDOException $e) {
    echo json_encode([
        'error' => true,
        'message' => 'Không thể kết nối cơ sở dữ liệu: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
?>
