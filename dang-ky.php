<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hoten = $_POST['hoten'];
    $email = $_POST['email'];
    $tendangnhap = $_POST['tendangnhap'];
    $matkhau = password_hash($_POST['matkhau'], PASSWORD_BCRYPT);
    $sdt = $_POST['sdt'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Email không hợp lệ!";
        $_SESSION['message_type'] = 'error';
        header("Location: home.php#registerModal");
        exit;
    }
    if (!preg_match('/^[0-9]{10}$/', $sdt)) {
        $_SESSION['message'] = "Số điện thoại không hợp lệ!";
        $_SESSION['message_type'] = 'error';
        header("Location: home.php#registerModal");
        exit;
    }
    $stmt = $pdo->prepare("SELECT * FROM nguoidung WHERE tendangnhap = :tendangnhap OR email = :email");
    $stmt->bindParam(':tendangnhap', $tendangnhap);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $_SESSION['message'] = "Đăng ký thất bại: Username hoặc Email đã tồn tại!";
        $_SESSION['message_type'] = 'error';
        header("Location: home.php#registerModal");
        exit;
    } else {
      
        $stmt = $pdo->prepare("INSERT INTO nguoidung (hoten, tendangnhap, matkhau, sdt, email) VALUES (:hoten, :tendangnhap, :matkhau, :sdt, :email)");
        $stmt->bindParam(':hoten', $hoten);
        $stmt->bindParam(':tendangnhap', $tendangnhap);
        $stmt->bindParam(':matkhau', $matkhau);
        $stmt->bindParam(':sdt', $sdt);
        $stmt->bindParam(':email', $email);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Đăng ký thành công!";
            $_SESSION['message_type'] = 'success';
            header("Location: home.php#registerModal");
            exit;
        } else {
            $_SESSION['message'] = "Đăng ký thất bại: Có lỗi xảy ra!";
            $_SESSION['message_type'] = 'error';
            header("Location: home.php#registerModal");
            exit;
        }
    }
}
?>
