<?php
session_start();
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
$discountedPrice = $originalPrice - ($originalPrice * $discountPercentage / 100);
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
    <?php include 'header-da-dang-nhap.php'; ?>
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

        function addToCart(button) {
            const productId = button.getAttribute('data-id');
            const productName = button.getAttribute('data-name');
            const productPrice = button.getAttribute('data-price');
            const productImg = button.getAttribute('data-img');
            const quantity = document.getElementById('quantity').value; // Lấy số lượng từ ô nhập

            // Gửi yêu cầu AJAX để thêm sản phẩm vào giỏ hàng
            fetch('home-da-dang-nhap.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    'action': 'add_to_cart',
                    'product_id': productId,
                    'product_name': productName,
                    'product_price': productPrice,
                    'product_img': productImg,
                    'quantity': quantity // Gửi số lượng
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Sản phẩm đã được thêm vào giỏ hàng! Bạn có ' + data.cart_count + ' sản phẩm trong giỏ hàng.');
                } else {
                    alert('Có lỗi xảy ra: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
        </script>

        <div class="container">
            <div class="breadcrumb">
                <a href="home-da-dang-nhap.php">Trang chủ</a>
                /
                <a href="#"><?= htmlspecialchars($product['PhanLoai']) ?></a>
                /
                <?= htmlspecialchars($product['Ten']) ?>
            </div>
            <div class="product">
                <div class="product-image">
                    <img width="600" height="600" alt="<?= htmlspecialchars($product['Ten']) ?>" src="<?= $product['HinhAnh']?>" decoding="async" fetchpriority="high" sizes="(max-width:767px) 480px, 600px" draggable="false"/>
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
                        <!-- Hiển thị giá gốc với dấu gạch ngang và giá sau giảm trên cùng một dòng -->
                        <p class="price">
                        <span class="discounted-price"><?= number_format($discountedPrice, 0, ',', '.') ?>₫</span>
                            <span class="original-price"><?= number_format($originalPrice, 0, ',', '.') ?>₫</span>
                        </p>
                    <?php else: ?>
                        <!-- Chỉ hiển thị giá gốc nếu không có giảm giá -->
                        <p class="price"><?= number_format($originalPrice, 0, ',', '.') ?>₫</p>
                    <?php endif; ?>
                </div>
                    
                    <div class="quantity">
                        <button class="special" id="decrease">-</button>
                        <input type="text" id="quantity" value="1" />
                        <button class="special" id="increase">+</button>
                    </div>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <button type="button" class="add-to-cart" 
                                data-id="<?= $product['Id'] ?>" 
                                data-name="<?= htmlspecialchars($product['Ten']) ?>" 
                                data-price="<?= $product['Gia'] ?>" 
                                data-img="<?= $product['HinhAnh']?>"
                                onclick="addToCart(this)">Thêm vào giỏ hàng
                        </button>
                    <?php else: ?>
                        <button type="button" onclick="alert('Bạn cần đăng nhập để thêm vào giỏ!');" class="add-to-cart">Thêm vào giỏ hàng</button>
                    <?php endif; ?>
                    <div class="brand">
                        <?= htmlspecialchars($product['MoTa']) ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
