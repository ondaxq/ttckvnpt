<?php
try {
    $pdo = new PDO('mysql:host=localhost:3306;dbname=qldoanmypham1', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Kết nối thất bại: ' . $e->getMessage();
    exit;
}
?>
