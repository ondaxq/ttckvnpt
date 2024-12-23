<?php
include 'db.php';

$query = isset($_GET['query']) ? $_GET['query'] : '';

$sql = "SELECT * FROM sanpham";
if ($query) {
    $sql .= " WHERE Ten LIKE :query OR NhaCungCap LIKE :query";
}

$stmt = $pdo->prepare($sql);
if ($query) {
    $stmt->execute(['query' => '%' . $query . '%']);
} else {
    $stmt->execute();
}

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($products);
?>
