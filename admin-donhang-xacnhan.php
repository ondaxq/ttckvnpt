<?php
session_start();
include 'db.php';  // Đảm bảo rằng bạn đã kết nối cơ sở dữ liệu.

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ client
    $data = json_decode(file_get_contents('php://input'), true);
    $orderId = $data['id'];

    if (isset($orderId) && is_numeric($orderId)) {
        try {
           
            $stmt = $pdo->prepare("UPDATE donhang SET tt = 1 WHERE iddonhang = :iddonhang");
            $stmt->execute(['iddonhang' => $orderId]);

            // Trả về kết quả thành công
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            // Trả về lỗi nếu có
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid order ID']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
