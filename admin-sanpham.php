<?php
session_start();
include 'db.php'; 

// Xử lý tìm kiếm
$query = isset($_GET['query']) ? $_GET['query'] : '';  // Lấy từ khóa tìm kiếm từ URL
$tendangnhap = isset($_SESSION['tendangnhap']) ? $_SESSION['tendangnhap'] : null;

// Lấy thông tin người dùng (nếu có)
if ($tendangnhap) {
    $stmt = $pdo->prepare("SELECT hoten, sdt FROM nguoidung WHERE tendangnhap = :tendangnhap");
    $stmt->execute(['tendangnhap' => $tendangnhap]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    $user = null;
}

// Số sản phẩm trên mỗi trang
$productsPerPage = 15;

// Lấy số trang hiện tại từ URL, nếu không có thì mặc định là 1
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Tính toán tổng số sản phẩm
$totalProductsQuery = $pdo->query("SELECT COUNT(*) FROM sanpham");
$totalProducts = $totalProductsQuery->fetchColumn();

// Tính toán tổng số trang
$totalPages = ceil($totalProducts / $productsPerPage);

// Tính toán offset cho truy vấn
$offset = ($currentPage - 1) * $productsPerPage;

// Xử lý tìm kiếm sản phẩm
$sql = "SELECT * FROM sanpham";
if ($query) {
    $sql .= " WHERE Ten LIKE :query OR NhaCungCap LIKE :query";
}
$sql .= " LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);

if ($query) {
    $stmt->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);
}
$stmt->bindValue(':limit', $productsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Cửa Hàng</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="admin.css?<?php echo time(); ?>" />
    <link rel="stylesheet" type="text/css" href="admin-sanpham.css?<?php echo time(); ?>" />
</head>
<body class="bg-gray-100">   
<?php include 'admin-header.php'; ?>
<div class="flex flex-col">
    <div class="flex flex-1">
        <?php include 'admin-sideitems.php'; ?>
        <main class="flex-1 p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold">Quản lý sản phẩm</h1>
            <div class="find flex flex-col items-center justify-center">
                <form action="" method="get" class="flex items-center justify-center">
                    <input type="text" name="query" placeholder="Tìm kiếm sản phẩm..." class="border rounded-l-lg px-3 py-2 w-64" value="<?php echo htmlspecialchars($query); ?>">
                    <button type="submit" class="btn-find ">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
            <button onclick="openAddModal()" class="new-product text-white rounded-md flex items-center px-4 py-2">
                <i class="fas fa-plus mr-2"></i> Thêm sản phẩm mới
            </button>
        </div>
            
            <ul class="bg-white shadow-md rounded-lg border border-gray-200">
                <?php if ($products): ?>
                    <?php foreach ($products as $product): ?>
                        <li class="p-4 border-b border-gray-200 flex justify-between items-center">
                            <div class="flex items-center">
                                <img alt="Product Image" class="product-img" height="50" src="<?php echo $product['HinhAnh']; ?>" width="50"/>
                                <div>
                                    <h3 class="text-lg font-semibold"><?php echo htmlspecialchars($product['Ten']); ?></h3>
                                    <p class="text-gray-600"><?php echo htmlspecialchars($product['NhaCungCap']); ?></p>
                                </div>
                            </div>
                            <div>
                                <button class="btn-edit text-blue-500 hover:text-blue-700" onclick="openEditModal(<?php echo $product['Id']; ?>)">
                                    <i class="fas fa-edit"></i> Chỉnh sửa
                                </button>
                                <button class="btn-delete text-red-500 hover:text-red-700 ml-2" onclick="deleteProduct(<?php echo $product['Id']; ?>)">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="p-4">
                        <p class="text-gray-600">Không có sản phẩm nào trong hệ thống.</p>
                    </li>
                <?php endif; ?>
            </ul>

            <div class="pagination">
                <?php if ($currentPage > 1): ?>
                    <a href="?query=<?php echo htmlspecialchars($query); ?>&page=<?php echo $currentPage - 1; ?>">« Trang trước</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?query=<?php echo htmlspecialchars($query); ?>&page=<?php echo $i; ?>" class="<?php echo $i === $currentPage ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <a href="?query=<?php echo htmlspecialchars($query); ?>&page=<?php echo $currentPage + 1; ?>">Trang sau »</a>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>

<!-- Modal Edit sản phẩm-->
<div id="editModal" class="hidden fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50">
    <div class="modal-content bg-white p-6 rounded-lg shadow-lg w-150">
        <h2 class="text-xl font-semibold mb-4">Chỉnh sửa sản phẩm</h2>
        
        <form id="editProductForm" action="admin-sua-sanpham.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" id="productId">
            <div class="prob">
                <label for="ten" class="prob text-sm font-medium text-gray-700">Tên sản phẩm</label>
                <input type="text" name="ten" id="ten" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
            </div>
            <div class="prob">
                <label for="phanLoai" class=" text-sm font-medium text-gray-700">Phân loại</label>
                <input type="text" name="phanLoai" id="phanLoai" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
            </div>
            <div class="prob">
                <label for="nhaCungCap" class="text-sm font-medium text-gray-700">Nhà cung cấp</label>
                <input type="text" name="nhaCungCap" id="nhaCungCap" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
            </div>
            <div class="prob">
                <label for="dungTich" class="text-sm font-medium text-gray-700">Dung tích</label>
                <input type="text" name="dungTich" id="dungTich" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
            </div>
            <div class="prob">
                <label for="moTa" class="text-sm font-medium text-gray-700">Mô tả</label>
                <textarea name="moTa" id="moTa" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required></textarea>
            </div>
            <div class="prob">
                <label for="gia" class="text-sm font-medium text-gray-700">Giá</label>
                <input type="number" name="gia" id="gia" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
            </div>
            <div class="prob">
                <label for="giamGia" class="text-sm font-medium text-gray-700">Giảm giá (%)</label>
                <input type="number" name="giamGia" id="giamGia" class="mt-1 p-2 w-full border border-gray-300 rounded-md">
            </div>
            <div class="prob">
                <label for="soLuongTonKho" class="text-sm font-medium text-gray-700">Số lượng tồn kho</label>
                <input type="number" name="soLuongTonKho" id="soLuongTonKho" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
            </div>
            <div class="prob">
                <label for="hinhAnh" class=" text-sm font-medium text-gray-700">Hình ảnh</label>
                <input type="file" name="hinhAnh" id="hinhAnh" class="mt-1 p-2 w-full border border-gray-300 rounded-md" accept="image/*">
                <div id="imagePreview" class="mt-2">
                    <?php if (!empty($product['HinhAnh'])): ?>
                        <img src="<?php echo htmlspecialchars($product['HinhAnh']); ?>" alt="Current Image" class="w-20 h-20 object-cover rounded-md">
                    <?php else: ?>
                        <p class="text-gray-600">Chưa có hình ảnh.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="prob">
                <label for="ngayNhapHang" class="block text-sm font-medium text-gray-700">Ngày nhập hàng</label>
                <input type="date" name="ngayNhapHang" id="ngayNhapHang" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
            </div>
            <div class="sc-button">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md">Cập nhật</button>
                <button type="button" onclick="closeEditModal()" class="close bg-red-500 text-white px-4 py-2 rounded-md">Đóng</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Thêm sản phẩm-->
<div id="addModal" class="hidden fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50">
    <div class="modal-content bg-white p-6 rounded-lg shadow-lg w-150">
        <h2 class="text-xl font-semibold mb-4">Thêm sản phẩm mới</h2>
        <form id="addProductForm" action="admin-them-sanpham.php" method="POST" enctype="multipart/form-data">
            <div>
                <label for="id" class="block text-sm font-medium text-gray-700">Id sản phẩm</label>
                <input type="text" name="id" id="id" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
            </div>
            <div>
                <label for="ten" class="block text-sm font-medium text-gray-700">Tên sản phẩm</label>
                <input type="text" name="ten" id="ten" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
            </div>
            <div>
                <label for="phanLoai" class="block text-sm font-medium text-gray-700">Phân loại</label>
                <input type="text" name="phanLoai" id="phanLoai" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
            </div>
            <div>
                <label for="nhaCungCap" class="block text-sm font-medium text-gray-700">Nhà cung cấp</label>
                <input type="text" name="nhaCungCap" id="nhaCungCap" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
            </div>
            <div>
                <label for="dungTich" class="block text-sm font-medium text-gray-700"> Dung tích</label>
                <input type="text" name="dungTich" id="dungTich" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
            </div>
            <div>
                <label for="moTa" class="block text-sm font-medium text-gray-700">Mô tả</label>
                <textarea name="moTa" id="moTa" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required></textarea>
            </div>
            <div>
                <label for="gia" class="block text-sm font-medium text-gray-700">Giá</label>
                <input type="number" name="gia" id="gia" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
            </div>
            <div>
                <label for="giamGia" class="block text-sm font-medium text-gray-700">Giảm giá (%)</label>
                <input type="number" name="giamGia" id="giamGia" class="mt-1 p-2 w-full border border-gray-300 rounded-md">
            </div>
            <div>
                <label for="soLuongTonKho" class="block text-sm font-medium text-gray-700">Số lượng tồn kho</label>
                <input type="number" name="soLuongTonKho" id="soLuongTonKho" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
            </div>
            <div>
                <label for="hinhAnh" class="block text-sm font-medium text-gray-700">Hình ảnh</label>
                <input type="file" name="hinhAnh" id="hinhAnh" class="mt-1 p-2 w-full border border-gray-300 rounded-md" accept="image/*">
                <div id="imagePreview" class="mt-2">
                    <p class="text-gray-600">Chưa có hình ảnh.</p>
                </div>
            </div>
            <div>
                <label for="ngayNhapHang" class="block text-sm font-medium text-gray-700">Ngày nhập hàng</label>
                <input type="date" name="ngayNhapHang" id="ngayNhapHang" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
            </div>
            <div class="mt-4 text-right">
                <button type="submit" class="submit bg-green-500 text-white px-4 py-2 rounded-md">Thêm sản phẩm</button>
                <button type="button" onclick="closeAddModal()" class="ml-2 bg-gray-500 text-white p-2 rounded-md">Đóng</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Lắng nghe sự kiện click ở ngoài modal để đóng modal
    window.addEventListener('click', function(event) {
        const addModal = document.getElementById('addModal');
        const editModal = document.getElementById('editModal');
        
        if (event.target === addModal) {
            closeAddModal();
        } else if (event.target === editModal) {
            closeEditModal();
        }
    });

    // Mở modal thêm sản phẩm mới
    function openAddModal() {
        const modal = document.getElementById('addModal');
        modal.classList.add('show');
        modal.classList.remove('hidden');
    }

    // Đóng modal thêm sản phẩm mới
    function closeAddModal() {
        const modal = document.getElementById('addModal');
        modal.classList.remove('show');
        modal.classList.add('hidden');
    }

    // Lắng nghe sự kiện submit của form thêm sản phẩm
    document.getElementById('addProductForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch('admin-them-sanpham.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeAddModal();
                alert('Sản phẩm đã được thêm thành công!');
                location.reload();
            } else {
                alert('Có lỗi xảy ra khi thêm sản phẩm! Vui lòng thử lại.');
            }
        })
        .catch(error => {
            console.error('Lỗi khi thêm sản phẩm:', error);
            alert('Không thể thêm sản phẩm. Vui lòng thử lại sau.');
        });
    });

    // Lắng nghe sự kiện thay đổi hình ảnh trong modal
    document.getElementById('hinhAnh').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const imagePreview = document.getElementById('imagePreview');
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-20 h-20 object-cover rounded-md">`;
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.innerHTML = '<p class="text-gray-600">Chưa có hình ảnh.</p>';
        }
    });

    // Mở modal sửa thông tin sản phẩm
    function openEditModal(productId) {
        fetch('admin-dssp.php?Id=' + productId)
            .then(response => response.json())
            .then(data => {
                document.getElementById('productId').value = data.Id;
                document.getElementById('ten').value = data.Ten;
                document.getElementById('phanLoai').value = data.PhanLoai;
                document.getElementById('nhaCungCap').value = data.NhaCungCap;
                document.getElementById('dungTich').value = data.DungTich;
                document.getElementById('moTa').value = data.MoTa;
                document.getElementById('gia').value = data.Gia;
                document.getElementById('giamGia').value = data.GiamGia;
                document.getElementById('soLuongTonKho').value = data.SoLuongTonKho;
                document.getElementById('ngayNhapHang').value = data.NgayNhapHang;

                const imagePreview = document.getElementById('imagePreview');
                if (data.HinhAnh) {
                    imagePreview.innerHTML = '<img src="' + data.HinhAnh + '" alt="Current Image" class="w-20 h-20 object-cover rounded-md">';
                } else {
                    imagePreview.innerHTML = '<p class="text-gray-600">Chưa có hình ảnh.</p>';
                }

                const modal = document.getElementById('editModal');
                modal.classList.add('show');
            })
            .catch(error => console.error('Error fetching product data:', error));
    }

    // Đóng modal sửa thông tin sản phẩm
    function closeEditModal() {
        const modal = document.getElementById('editModal');
        modal.classList.remove('show');
    }

    // Lắng nghe sự kiện submit của form sửa sản phẩm
    document.getElementById('editProductForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch('admin-sua-sanpham.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeEditModal();
                alert('Sản phẩm đã được sửa thành công!');
                location.reload();
            } else {
                alert(data.message || 'Có lỗi xảy ra khi sửa sản phẩm!');
            }
        })
        .catch(error => {
            console.error('Lỗi khi sửa sản phẩm:', error);
            alert('Không thể sửa sản phẩm. Vui lòng thử lại sau.');
        });
    });

    // Xóa sản phẩm
    function deleteProduct(productId) {
        if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?')) {
            fetch('admin-xoa-sanpham.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: productId }) 
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Sản phẩm đã được xóa thành công!');
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra khi xóa sản phẩm!');
                }
            })
            .catch(error => {
                console.error('Lỗi khi xóa sản phẩm:', error);
                alert('Không thể xóa sản phẩm. Vui lòng thử lại sau.');
            });
        }
    }
</script>

</body>
</html>