<?php
include 'db.php'; // Kết nối cơ sở dữ liệu

session_start();
if (!isset($_SESSION['tendangnhap'])) {
    // Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
    header('Location: login.php');
    exit();
}

$tendangnhap = $_SESSION['tendangnhap'];

// Lấy thông tin người dùng từ cơ sở dữ liệu
$stmt = $pdo->prepare("SELECT matkhau, hoten, sdt, email FROM nguoidung WHERE tendangnhap = :tendangnhap");
$stmt->execute(['tendangnhap' => $tendangnhap]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy các thông tin từ form
    $oldPassword = trim($_POST['old-password']);
    $newPassword = trim($_POST['new-password']);
    $confirmPassword = trim($_POST['confirm-password']);
    $hoten = trim($_POST['hoten']);
    $sdt = trim($_POST['sdt']);
    $email = trim($_POST['email']);

    // Kiểm tra mật khẩu cũ
    if (!password_verify($oldPassword, $user['matkhau'])) {
        echo "<script>alert('Mật khẩu cũ không chính xác.'); window.location.href = 'home-da-dang-nhap.php';</script>";
        exit();
    }

    // Kiểm tra nếu mật khẩu mới và xác nhận mật khẩu không khớp
    if ($newPassword !== $confirmPassword) {
        echo "<script>alert('Mật khẩu mới và nhập lại mật khẩu không khớp.'); window.location.href = 'home-da-dang-nhap.php';</script>";
        exit();
    }

    // Mã hóa mật khẩu mới nếu có thay đổi
    if (!empty($newPassword)) {
        $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $updatePasswordStmt = $pdo->prepare("UPDATE nguoidung SET matkhau = :matkhau WHERE tendangnhap = :tendangnhap");
        $updatePasswordStmt->execute(['matkhau' => $hashedNewPassword, 'tendangnhap' => $tendangnhap]);
    }

    // Cập nhật thông tin khác (họ tên, số điện thoại, email)
    $updateInfoStmt = $pdo->prepare("UPDATE nguoidung SET hoten = :hoten, sdt = :sdt, email = :email WHERE tendangnhap = :tendangnhap");
    $updateInfoStmt->execute(['hoten' => $hoten, 'sdt' => $sdt, 'email' => $email, 'tendangnhap' => $tendangnhap]);

    echo "<script>alert('Thông tin đã được cập nhật thành công.'); window.location.href = 'home-da-dang-nhap.php';</script>";
    exit();
}
?>
