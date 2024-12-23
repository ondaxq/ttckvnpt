<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Sản Phẩm</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Danh sách sản phẩm</h1>
            <input type="text" id="search" placeholder="Tìm kiếm sản phẩm..." oninput="searchProducts()">
            <button onclick="openAddModal()">Thêm sản phẩm</button>
        </header>
        <div id="product-list" class="product-list"></div>
    </div>

    <!-- Modal thêm sản phẩm -->
    <div id="addModal" class="modal hidden">
        <form id="addProductForm">
            <h2>Thêm sản phẩm</h2>
            <input type="text" name="ten" placeholder="Tên sản phẩm" required>
            <input type="text" name="phanLoai" placeholder="Phân loại" required>
            <input type="text" name="nhaCungCap" placeholder="Nhà cung cấp" required>
            <input type="number" name="gia" placeholder="Giá" required>
            <input type="file" name="hinhAnh">
            <button type="submit">Thêm</button>
            <button type="button" onclick="closeAddModal()">Đóng</button>
        </form>
    </div>

    <script>
        // Lấy danh sách sản phẩm
        async function fetchProducts(query = '') {
            const response = await fetch(`beadmin-sanpham.php?query=${query}`);
            const products = await response.json();
            renderProducts(products);
        }

        // Hiển thị danh sách sản phẩm
        function renderProducts(products) {
            const productList = document.getElementById('product-list');
            productList.innerHTML = products.map(product => `
                <div class="product">
                    <img src="${product.HinhAnh}" alt="${product.Ten}">
                    <h3>${product.Ten}</h3>
                    <p>${product.NhaCungCap}</p>
                    <p>${product.Gia} VND</p>
                </div>
            `).join('');
        }

        // Tìm kiếm sản phẩm
        function searchProducts() {
            const query = document.getElementById('search').value;
            fetchProducts(query);
        }

        // Gửi form thêm sản phẩm
        document.getElementById('addProductForm').addEventListener('submit', async function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            const response = await fetch('add-product.php', { method: 'POST', body: formData });
            const result = await response.json();
            if (result.success) {
                alert('Thêm sản phẩm thành công!');
                closeAddModal();
                fetchProducts();
            } else {
                alert('Có lỗi xảy ra.');
            }
        });

        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        // Khởi động
        fetchProducts();
    </script>
</body>
</html>
