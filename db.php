<?php
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=qldoanmypham;charset=utf8mb4', 
        'root', 
        '',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'" 
        ]
    );
} catch (PDOException $e) {
    echo json_encode([
        'error' => true,
        'message' => 'Không thể kết nối tới cơ sở dữ liệu. Vui lòng thử lại sau!'
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}
?>
