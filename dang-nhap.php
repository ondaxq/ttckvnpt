<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$database = "qldoanmypham";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tendangnhap = $_POST['tendangnhap'] ?? '';
    $matkhau = $_POST['matkhau'] ?? ''; 

    if (empty($tendangnhap)) {
        $_SESSION['login_error'] = "Tên đăng nhập không được để trống!";
        header("Location: home.php");
        exit;
    }
    if (empty($matkhau)) {
        $_SESSION['login_error'] = "Mật khẩu không được để trống!";
        header("Location: home.php");
        exit;
    }
    $stmt = $conn->prepare("SELECT * FROM nguoidung WHERE tendangnhap = ?");
    $stmt->bind_param("s", $tendangnhap);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($matkhau, $user['matkhau'])) {

            $_SESSION['user_id'] = $user['idnguoidung'];
            $_SESSION['tendangnhap'] = $tendangnhap;
            $_SESSION['user'] = $user; 
            if ($user['vaitro'] == 0) {
                header("Location: home-da-dang-nhap.php");  
            } else {
                header("Location: admin.php");  
            }
            exit;
        } else {
            $_SESSION['login_error'] = "Tên đăng nhập hoặc mật khẩu không đúng!";
            header("Location: home.php");
            exit;
        }
    } else {
        $_SESSION['login_error'] = "Tên đăng nhập hoặc mật khẩu không đúng!";
        header("Location: home.php");
        exit;
    }

    $stmt->close();
}

$conn->close();
?>
