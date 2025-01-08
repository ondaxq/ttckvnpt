<?php
session_start(); 
include 'db.php';

$items_per_page = 32;

$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$current_page = max(1, $current_page);

$offset = ($current_page - 1) * $items_per_page;

$total_stmt = $pdo->query("SELECT COUNT(*) FROM sanpham ");
$total_items = $total_stmt->fetchColumn();
$total_pages = ceil($total_items / $items_per_page);

//Những sản phẩm bán được > 50 thì sẽ là sản phẩm bán chạy
$stmt = $pdo->prepare("SELECT * FROM sanpham where DaBan > 50 LIMIT :limit OFFSET :offset");

$stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$login_error = isset($_SESSION['login_error']) ? $_SESSION['login_error'] : '';
if ($login_error) {
    echo "<script>alert('$login_error');</script>";
    unset($_SESSION['login_error']); 
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cửa Hàng Mỹ Phẩm - Bán chạy</title>
    <link rel="stylesheet" type="text/css" href="style.css?<?php echo time(); ?>" />
    <link rel="stylesheet" type="text/css" href="header.css?<?php echo time(); ?>" />
    <link rel="stylesheet" type="text/css" href="footer.css?<?php echo time(); ?>" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    
    <?php include 'header.php'; ?>
    <main>
    <div class="left-menu">
                <ul>
                    <li><a href="san-pham-moi.php">Sản Phẩm Mới</a></li>
                    <li><a href="giam-gia.php">Giảm Giá</a></li>
                    <li><a href="ban-chay.php">Bán Chạy</a></li>
                </ul>
            </div>
  <script>
    let lastScrollTop = 0; // Vị trí cuộn trước đó
    const menu = document.querySelector('.left-menu'); // Chọn phần tử menu

    window.addEventListener('scroll', function() {
        let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

        // Nếu cuộn xuống thì ẩn menu
        if (currentScroll > lastScrollTop) {
            menu.classList.add('hidden');
        } else {
            // Nếu cuộn lên thì hiện lại menu
            menu.classList.remove('hidden');
        }

        lastScrollTop = currentScroll <= 0 ? 0 : currentScroll; // Đảm bảo không cuộn lên quá đầu trang
    });
</script>
    <tl class="font-semibold ">Sản phẩm bán chạy</tl>
    <section>
    <div class="product-list">
        
        <?php foreach ($products as $product): 
            // Lấy giá gốc và tỷ lệ giảm giá
            $originalPrice = $product['Gia'];
            $discountPercentage = $product['GiamGia']; // Giảm giá theo phần trăm
            $discountedPrice = $originalPrice - ($originalPrice * $discountPercentage / 100); // Tính giá sau giảm
            ?>
                <div class="product-item">
                    <a href="chi-tiet.php?Id=<?= $product['Id'] ?>" style="display: block; color: inherit; text-decoration: none;">
                        <img src="<?= $product['HinhAnh']?>" alt="<?= htmlspecialchars($product['Ten']) ?>" />            
                        <h3>
                            <a href="chi-tiet.php?Id=<?= $product['Id'] ?>" style="display: block; color: inherit; text-decoration: none;">
                                <?= htmlspecialchars($product['Ten']) ?>
                            </a>
                        </h3>
                        <span class="stock">Kho: <?= htmlspecialchars($product['SoLuongTonKho']) ?></span>

                        <div class="price-stock">
                            <?php if ($discountPercentage > 0): ?>
                                <p class="discounted-price" style="color: #d9534f;">
                                    Giá: <?= number_format($discountedPrice, 0, ',', '.') ?>₫
                                </p>
                                <p class="original-price" style="text-decoration: line-through; color: #aaa;">
                                    <?= number_format($originalPrice, 0, ',', '.') ?>₫
                                </p>
                            <?php else: ?>
                                <p class="price">
                                    Giá: <?= number_format($originalPrice, 0, ',', '.') ?>₫
                                </p>
                            <?php endif; ?>                        
                        </div>
                        <button type="button" onclick="alert('Bạn cần đăng nhập để thêm vào giỏ!');" class="add-to-cart">Thêm vào giỏ</button>
                    </a>
                </div>
        <?php endforeach;?>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <?php if ($current_page > 1): ?>
            <a href="?page=<?= $current_page - 1 ?>">« Trang trước</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>" <?= $i === $current_page ? 'class="active"' : '' ?>><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($current_page < $total_pages): ?>
            <a href="?page=<?= $current_page + 1 ?>">Trang sau »</a>
        <?php endif; ?>
    </div>
    </section>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>

