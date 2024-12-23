<?php
session_start(); 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cửa Hàng Mỹ Phẩm</title>
    <link rel="stylesheet" type="text/css" href="style.css?<?php echo time(); ?>" />
    <link rel="stylesheet" type="text/css" href="header.css?<?php echo time(); ?>" />
    <link rel="stylesheet" type="text/css" href="footer.css?<?php echo time(); ?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    let lastScrollTop = 0; 
    const menu = document.querySelector('.left-menu'); 

    window.addEventListener('scroll', function() {
        let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

        if (currentScroll > lastScrollTop) {
            menu.classList.add('hidden');
        } else {
            menu.classList.remove('hidden');
        }

        lastScrollTop = currentScroll <= 0 ? 0 : currentScroll; 
    });
</script>
    <section>
    <div class="banner">
        <img class="banner-image" src="./img/banner1.jpg" alt="Banner Image 1" />
        <img class="banner-image" src="./img/banner2.jpg" alt="Banner Image 2" />
        <img class="banner-image" src="./img/banner3.jpg" alt="Banner Image 3" />
        <button class="prev" onclick="showPreviousImage()">&#10094;</button>
        <button class="next" onclick="showNextImage()">&#10095;</button>
    </div>
    <script>
        let currentIndex = 0;
        const images = document.querySelectorAll('.banner-image');
        const totalImages = images.length;

        images[currentIndex].classList.add('active');

        function showNextImage() {
            images[currentIndex].classList.remove('active'); 
            currentIndex = (currentIndex + 1) % totalImages; 
            images[currentIndex].classList.add('active');
        }

        function showPreviousImage() {
            images[currentIndex].classList.remove('active'); 
            currentIndex = (currentIndex - 1 + totalImages) % totalImages; 
            images[currentIndex].classList.add('active');
        }
        setInterval(showNextImage, 3000); 
    </script>

        <section class="product-list"></section>
    </main>

    <?php include 'footer.php'; ?>

    <script>
        function fetchProducts() {
            $.ajax({
                url: 'behome.php',
                type: 'GET',
                success: function(products) {
                    renderProducts(products);
                },
                error: function() {
                    alert('Không thể tải dữ liệu');
                }
            });
        }

        function renderProducts(products) {
            const productList = $('.product-list');
            productList.empty();

            products.forEach(product => {
                const originalPrice = product.Gia;
                const discountPercentage = product.GiamGia;
                const discountedPrice = originalPrice - (originalPrice * discountPercentage / 100);

                productList.append(`
                    <div class="product-item">
                        <a href="chi-tiet.php?Id=${product.Id}" style="text-decoration: none; color: inherit;">
                            <img src="${product.HinhAnh}" alt="${product.Ten}">
                            <h3>${product.Ten}</h3>
                            <span>Kho: ${product.SoLuongTonKho}</span>
                            <div>
                                ${discountPercentage > 0
                                    ? `<p style="color: red;">${discountedPrice.toLocaleString()}₫</p>
                                       <p style="text-decoration: line-through; color: gray;">${originalPrice.toLocaleString()}₫</p>`
                                    : `<p>${originalPrice.toLocaleString()}₫</p>`}
                            </div>
                            <button onclick="alert('Bạn cần đăng nhập để thêm vào giỏ!');">Thêm vào giỏ</button>
                        </a>
                    </div>
                `);
            });
        }
        $(document).ready(function() {
            fetchProducts();
        });
    </script>
</body>
</html>
