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

            <div class="product-list" id="product-list">
                <!-- Sản phẩm sẽ được load ở đây thông qua AJAX -->
            </div>

            <div class="pagination" id="pagination">
                <!-- Phân trang sẽ được load ở đây thông qua AJAX -->
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>

    <script>
document.addEventListener('DOMContentLoaded', function () {
    // Hàm tải dữ liệu sản phẩm từ backend
    function loadProducts(page = 1) {
        fetch(`behome.php?page=${page}`)
            .then(response => response.json())
            .then(data => {
                console.log(data); // Kiểm tra dữ liệu nhận được từ backend
                const productList = document.getElementById('product-list');
                const pagination = document.getElementById('pagination');
                productList.innerHTML = ''; // Xóa danh sách sản phẩm cũ
                pagination.innerHTML = ''; // Xóa phân trang cũ

                // Kiểm tra nếu có sản phẩm
                if (data.products && data.products.length > 0) {
                    // Hiển thị sản phẩm
                    data.products.forEach(product => {
                        const originalPrice = product.Gia;
                        const discountPercentage = product.GiamGia;
                        const discountedPrice = originalPrice - (originalPrice * discountPercentage / 100);

                        const productHTML = `
                            <div class="product-item">
                                <a href="chi-tiet.php?Id=${product.Id}" style="display: block; color: inherit; text-decoration: none;">
                                    <img src="${product.HinhAnh}" alt="${product.Ten}" />
                                    <h3>${product.Ten}</h3>
                                    <span class="stock">Kho: ${product.SoLuongTonKho}</span>
                                    <div class="price-stock">
                                        ${discountPercentage > 0 ? `
                                            <p class="discounted-price" style="color: #d9534f;">Giá: ${discountedPrice.toFixed(0)}₫</p>
                                            <p class="original-price" style="text-decoration: line-through; color: #aaa;">${originalPrice.toFixed(0)}₫</p>
                                        ` : `
                                            <p class="price">Giá: ${originalPrice.toFixed(0)}₫</p>
                                        `}
                                    </div>
                                    <button type="button" onclick="alert('Bạn cần đăng nhập để thêm vào giỏ!');" class="add-to-cart">Thêm vào giỏ</button>
                                </a>
                            </div>
                        `;
                        productList.innerHTML += productHTML;
                    });
                } else {
                    // Nếu không có sản phẩm
                    productList.innerHTML = '<p>Không có sản phẩm nào.</p>';
                }

                // Hiển thị phân trang
                if (data.current_page > 1) {
                    pagination.innerHTML += `<a href="#" onclick="loadProducts(${data.current_page - 1})">« Trang trước</a>`;
                }

                for (let i = 1; i <= data.total_pages; i++) {
                    pagination.innerHTML += `<a href="#" onclick="loadProducts(${i})" ${i === data.current_page ? 'class="active"' : ''}>${i}</a>`;
                }

                if (data.current_page < data.total_pages) {
                    pagination.innerHTML += `<a href="#" onclick="loadProducts(${data.current_page + 1})">Trang sau »</a>`;
                }
            })
            .catch(error => {
                console.error('Lỗi khi tải sản phẩm:', error);
                const productList = document.getElementById('product-list');
                productList.innerHTML = '<p>Không thể tải sản phẩm. Vui lòng thử lại sau.</p>';
            });
    }

    // Tải sản phẩm mặc định khi trang web được tải
    loadProducts();
});

    </script>
</body>
</html>
