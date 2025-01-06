<?php
include 'db.php';

// Lấy ID sản phẩm từ URL
$Id = isset($_GET['Id']) && is_numeric($_GET['Id']) ? intval($_GET['Id']) : null;

// Truy vấn để lấy thông tin sản phẩm
$stmt = $pdo->prepare("SELECT * FROM sanpham WHERE Id = :Id");
$stmt->execute(['Id' => $Id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// Tính toán giá sau giảm (nếu có giảm giá)
$originalPrice = $product['Gia'];
$discountPercentage = $product['GiamGia']; // Giảm giá theo phần trăm
$discountedPrice = $originalPrice - ($originalPrice * $discountPercentage / 100); // Tính giá sau giảm
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cửa Hàng Mỹ Phẩm</title>
    <link rel="stylesheet" type="text/css" href="chi-tiet.css?<?php echo time(); ?>" />
    <link rel="stylesheet" type="text/css" href="header.css?<?php echo time(); ?>" />
    <link rel="stylesheet" type="text/css" href="footer.css?<?php echo time(); ?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const decreaseButton = document.getElementById('decrease');
        const increaseButton = document.getElementById('increase');
        const quantityInput = document.getElementById('quantity');

        // Hàm giảm số lượng
        decreaseButton.addEventListener('click', () => {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });

        // Hàm tăng số lượng
        increaseButton.addEventListener('click', () => {
            let currentValue = parseInt(quantityInput.value);
            quantityInput.value = currentValue + 1;
        });
    });
    </script>

    <div class="container">
        <div class="breadcrumb">
            <a href="home.php">Trang chủ</a>
            /
            <a href="#"><?= htmlspecialchars($product['PhanLoai']) ?></a>
            /
            <?= htmlspecialchars($product['Ten']) ?>
        </div>
    <div class="product">
        <div class="product-image">
            <img width="600" height="600" alt="<?= htmlspecialchars($product['Ten']) ?>" height="500" src="<?= $product['HinhAnh']?>" decoding="async" fetchpriority="high" sizes="(max-width:767px) 480px, 600px" draggable="false"/>
        </div>
        <div class="product-details">
            <div class="product-title">
                <?= htmlspecialchars($product['Ten']) ?>
            </div>
            <div class="brand">
                <?= htmlspecialchars($product['NhaCungCap']) ?>
            </div>
            <div class="product-price">
                <?php if ($discountPercentage > 0): ?>
                    <!-- Hiển thị giá gốc và giá sau giảm trên cùng một dòng -->
                    <p class="price">
                        <span class="original-price"><?= number_format($originalPrice, 0, ',', '.') ?>₫</span>
                        <span class="discounted-price"><?= number_format($discountedPrice, 0, ',', '.') ?>₫</span>
                    </p>
                <?php else: ?>
                    <!-- Hiển thị chỉ giá gốc nếu không có giảm giá -->
                    <p class="price"><?= number_format($originalPrice, 0, ',', '.') ?>₫</p>
                <?php endif; ?>
            </div>
            <div class="quantity">
                <button class="special" id="decrease">-</button>
                <input type="text" id="quantity" value="1"/>
                <button class="special" id="increase">+</button>
            </div>
            <?php if (isset($_SESSION['tendangnhap'])): ?>
                <button type="button" class="add-to-cart" data-name="<?= htmlspecialchars($product['Ten']) ?>" data-price="<?= $discountedPrice ?>">Thêm vào giỏ hàng</button>
            <?php else: ?>
                <button type="button" onclick="alert('Bạn cần đăng nhập để thêm vào giỏ!');" class="add-to-cart">Thêm vào giỏ</button>
            <?php endif; ?>
            </a>
        <div class="brand">
            <?= htmlspecialchars($product['MoTa']) ?>
        </div>
    </div>
    </main>
</body>
    <?php include 'footer.php'; ?>
</body>
</html>
