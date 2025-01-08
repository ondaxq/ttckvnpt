<?php
session_start();
include 'db.php';    // Kết nối cơ sở dữ liệu

// Kiểm tra nếu người dùng đã đăng nhập
$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    echo "Bạn chưa đăng nhập!";
    exit();
}

// Lấy thông tin khách hàng từ form (nếu có)
$customerName = '';
$customerPhone = '';
$customerAddress = '';
$paymentMethod = ''; // Phương thức thanh toán (QR hoặc Tiền mặt)
$total_price=0;
// Kiểm tra nếu form được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy thông tin từ form
    $customerName = $_POST['customer_name'] ?? '';
    $customerPhone = $_POST['customer_phone'] ?? '';
    $customerAddress = $_POST['customer_address'] ?? '';
    $paymentMethod = $_POST['payment_method'] ?? 0;
    $total_price = $_POST['total_price'] ?? 0;
    $cart = json_decode($_POST['cart'], true); // Giỏ hàng được mã hóa dưới dạng JSON
        echo "<pre>";
        print_r($_POST); // Kiểm tra tất cả dữ liệu gửi qua form
        echo "</pre>";
    // Kiểm tra nếu giỏ hàng rỗng
    if (empty($cart)) {
        echo "Giỏ hàng của bạn trống!";
        exit();
    }

    // Bắt đầu giao dịch (transaction) để đảm bảo tính toàn vẹn dữ liệu
    $pdo->beginTransaction();

    try {
        // 1. Lưu thông tin đơn hàng vào bảng donhang
        $stmt = $pdo->prepare("INSERT INTO donhang (idnguoidung, thoigiandat, sdtnguoinhan, diachi, ptthanhtoan) 
                               VALUES (?, NOW(), ?, ?, ?)");
        $stmt->execute([$userId, $customerPhone, $customerAddress, $paymentMethod]);
        
        // Lấy ID của đơn hàng vừa tạo
        $orderId = $pdo->lastInsertId();
        $_SESSION['order_id'] = $orderId;

        // 2. Lưu thông tin chi tiết đơn hàng vào bảng chitietdonhang
        foreach ($cart as $item) {
            // Trước khi lưu vào chi tiết đơn hàng, kiểm tra và trừ số lượng trong kho
            $productId = $item['product_id'];
            $productQuantity = $item['soLuong'];

            // Cập nhật số lượng sản phẩm trong kho
            $stmt = $pdo->prepare("SELECT SoLuongTonKho, DaBan FROM sanpham WHERE Id = ?");
            $stmt->execute([$productId]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                $newStock = $product['SoLuongTonKho'] - $productQuantity; // Số lượng mới sau khi trừ
                $newSold = $product['DaBan'] + $productQuantity; // Số lượng đã bán

                // Kiểm tra xem số lượng tồn kho có đủ không
                if ($newStock < 0) {
                    throw new Exception("Sản phẩm {$item['product_name']} không đủ trong kho.");
                }

                // Cập nhật số lượng tồn kho và số lượng đã bán
                $stmt = $pdo->prepare("UPDATE sanpham SET SoLuongTonKho = ?, DaBan = ? WHERE Id = ?");
                $stmt->execute([$newStock, $newSold, $productId]);
            }

            // Lưu chi tiết đơn hàng vào bảng chitietdonhang
            $stmt = $pdo->prepare("INSERT INTO chitietdonhang (iddonhang, idsanpham, soluong) 
                                   VALUES (?, ?, ?)");
            $stmt->execute([$orderId, $productId, $productQuantity]);
        }

        // 3. Xóa tất cả sản phẩm trong giỏ hàng của người dùng sau khi đặt hàng
        $stmt = $pdo->prepare("DELETE FROM giohang WHERE idnguoidung = ?");
        $stmt->execute([$userId]);

        // Commit giao dịch nếu không có lỗi
        $pdo->commit();

        $_SESSION['customer_name'] = $customerName;
        $_SESSION['customer_phone'] = $customerPhone;
        $_SESSION['customer_address'] = $customerAddress;
        $_SESSION['paymentMethod'] = $paymentMethod;
        
        // Thành công, hiển thị thông báo đặt hàng thành công
        echo "Đặt hàng thành công!";
        header("Location: hoan-thanh-dat-hang.php"); // Chuyển hướng đến trang thông báo thành công
        exit();

    } catch (Exception $e) {
        // Nếu có lỗi xảy ra, rollback giao dịch để tránh dữ liệu bị hỏng
        $pdo->rollBack();
        echo "Có lỗi xảy ra: " . $e->getMessage();
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác Nhận Đặt Hàng</title>
    
    <link rel="stylesheet" type="text/css" href="header.css?<?php echo time(); ?>" />
    <link rel="stylesheet" type="text/css" href="footer.css?<?php echo time(); ?>" />
    <link rel="stylesheet" type="text/css" href="dat-hang.css?<?php echo time(); ?>" />
    <link rel="stylesheet" type="text/css" href="hoan-thanh-dat-hang.css?<?php echo time(); ?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'header-da-dang-nhap.php'; ?>
    <?php
   
    $customerName = $_SESSION['customer_name'] ?? '';
    $customerPhone = $_SESSION['customer_phone'] ?? '';
    $customerAddress = $_SESSION['customer_address'] ?? '';
    $paymentMethod = $_SESSION['paymentMethod'] ?? 0;
    $total_price = $_SESSION['tt'] ?? 0;
    $orderId=$_SESSION['order_id']?? 0;

    // Xóa SESSION để tránh lặp lại
    unset($_SESSION['customer_name'], $_SESSION['customer_phone'], $_SESSION['customer_address']);
    ?>
    <main>
    <div class="success-message" style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; min-height: 100vh;">
           <?php if($paymentMethod == 1){
              ?>
             <!-- Chỉ hiển thị mã QR khi phương thức thanh toán là QR -->
             <h2>Quét mã QR để thanh toán</h2>
             <?php
             $bankCode = "mbbank";
             $accountNumber = "666664250503"; 
             $receiverName = "NGUYEN XUAN QUYEN"; 
             $amount = number_format($total_price, 0, '', ''); 
             $description = $orderId; 

             $qrUrl = "https://img.vietqr.io/image/{$bankCode}-{$accountNumber}-compact.png?amount={$total_price}&addInfo={$description}&accountName={$receiverName}";
             echo "<img src='{$qrUrl}' alt='Mã QR thanh toán MB Bank' />";
           }
             else if($paymentMethod == 0){
                ?>
                
                <!-- Nếu không phải QR, hiển thị thông tin đơn hàng -->
                <h1><ql class="font-semibold">Đặt hàng thành công!</ql> </h1>
                <p>Cảm ơn bạn, <ql class="font-semibold"> <?= htmlspecialchars($customerName) ?></ql>! Đơn hàng của bạn đã được đặt thành công.</p>
                <p>Chúng tôi sẽ liên hệ với bạn qua số điện thoại: <?= htmlspecialchars($customerPhone) ?>.</p>
                <p>Địa chỉ giao hàng: <?= htmlspecialchars($customerAddress) ?>.</p>
           

            <a href='home-da-dang-nhap.php'><i class="fas fa-arrow-left align-center"></i> 
                <ql class="font-semibold">Quay lại trang chủ</ql> 
            </a>
            <?php
             }
           else{
           ?>
           <h3>Thanh toán thất bại</h3>
           <a href='home-da-dang-nhap.php'><i class="fas fa-arrow-left align-center"></i> 
                <ql class="font-semibold">Quay lại trang chủ</ql> 
            </a>
           <?php
           }
            ?>
               
           
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
