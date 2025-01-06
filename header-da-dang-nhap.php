<?php
include 'db.php'; // Bắt đầu phiên làm việc

// Kiểm tra xem người dùng đã đăng nhập chưa
if (isset($_SESSION['tendangnhap'])) {
    $tendangnhap = $_SESSION['tendangnhap'];
    
    // Lấy thông tin người dùng từ cơ sở dữ liệu
    $stmt = $pdo->prepare("SELECT hoten, sdt,email FROM nguoidung WHERE tendangnhap = :tendangnhap");
    $stmt->execute(['tendangnhap' => $tendangnhap]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    $user = null; // Nếu chưa đăng nhập, thiết lập user là null
}
?>
<?php
// Kết nối CSDL
include 'db.php';

// Truy vấn danh sách các phân loại sản phẩm từ CSDL
$stmt = $pdo->prepare("SELECT DISTINCT PhanLoai FROM sanpham");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<nav class="nav">
    <div class="nav-container">
        <a href="home-da-dang-nhap.php" class="logo">
            <span>Cửa Hàng Mỹ Phẩm</span>
        </a>
        <div id="mega-menu" class="mega-menu">
            <ul class="menu-list">
                <li><a href="home-da-dang-nhap.php" class="menu-item">Trang chủ</a></li>
                <li class="danh-muc-dropdown">
    <a href="#" class="menu-item">Danh mục</a>
    <ul class="danh-muc-dropdown-content">
        <?php foreach ($categories as $category): ?>
            <li>
                <a href="phan-loai-ddn.php?query=<?= urlencode($category['PhanLoai']) ?>" class="dropdown-item">
                    <?= htmlspecialchars($category['PhanLoai']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</li>
                <li><a href="lien-he-da-dang-nhap.php" class="menu-item">Liên hệ</a></li>
                <li class="search-container">
                    <form action="tim-kiem.php" method="get">
                        <input type="text" name="query" placeholder="Tìm kiếm sản phẩm..." class="search-input">
                        <button type="submit" class="find-btn"><i class="fas fa-search"></i></button>
                    </form>
                </li>
            </ul>
        </div>
        <div class="nav-buttons"> 
            <a href="dat-hang.php" class="register-login-btn"> <i class="fas fa-shopping-cart"></i> Giỏ hàng </a> 
            <button type="button" id="user-menu-button">
    <i class="fas fa-user-circle" style="font-size:28px;"></i>
            </button>

            <!-- Dropdown Menu -->
            <div id="user-dropdown" style="display: none;">
                <div>
                    <span><?= $user['hoten'] ?></span>
                    <span><?= $user['sdt'] ?></span>
                </div>
                <ul>
                    <li>
                        <a href="javascript:void(0)" onclick="openUserInfoModal()">Thông tin người dùng</a>
                    </li>
                    <li>
                        <a href="don-hang-cua-toi.php">Đơn hàng của tôi</a>
                    </li>
                    <li>
                        <a href="home.php">Đăng xuất</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<div class="overlay" id="overlay"></div>

<div id="userInfoModal" class="modal-userinformation">
    <div class="modal-content">
        <div class="flex justify-between">
            <h2 class="text-2xl">Thông Tin Người Dùng</h2>
            <button onclick="closeUserInfoModal()" class="close">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="userInfoForm" action="thaydoithongtin.php" method="POST">
            <input type="hidden" name="idnguoidung" value="<?= $idnguoidung ?>">
            
            <div class="input-box">
                <label for="hoten">Họ và tên</label>
                <input type="text" name="hoten" id="hoten" required value="<?= $user['hoten'] ?>">
            </div>

            <div class="input-box">
                <label for="sdt">Số điện thoại</label>
                <input type="text" name="sdt" id="sdt" required value="<?= $user['sdt'] ?>">
            </div>
            
            <div class="input-box">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required value="<?= $user['email'] ?>">
            </div>

            <div class="input-box">
                <label for="old-password">Mật khẩu cũ</label>
                <input type="password" name="old-password" id="old-password" required>
            </div>

            <div class="input-box">
                <label for="new-password">Mật khẩu mới</label>
                <input type="password" name="new-password" id="new-password" required>
            </div>

            <div class="input-box">
                <label for="confirm-password">Nhập lại mật khẩu</label>
                <input type="password" name="confirm-password" id="confirm-password" required>
            </div>

            <div class="btn-container">
                <button type="submit" class="Btn-login">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const userMenuButton = document.getElementById('user-menu-button');
    const userDropdown = document.getElementById('user-dropdown');
    const userInfoModal = document.getElementById('userInfoModal');
    const overlay = document.getElementById('overlay'); 


    userMenuButton.addEventListener('click', function () {
        const isVisible = userDropdown.style.display === 'block';
        if (isVisible) {
            userDropdown.style.display = 'none';
        } else {
            userDropdown.style.display = 'block';
        }
    });

    window.addEventListener('click', function (event) {
        if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
            userDropdown.style.display = 'none';
        }
    });

    function closeUserInfoModal() {
        userInfoModal.style.display = 'none';
        overlay.style.display = 'none'; 
    }

    window.openUserInfoModal = function () {
        userInfoModal.style.display = 'flex';
        overlay.style.display = 'block'; 
    }

    userInfoModal.addEventListener('click', function(event) {
        event.stopPropagation(); 
    });

    const closeButton = document.querySelector('.close');
    closeButton.addEventListener('click', function () {
        closeUserInfoModal(); 
    });
});
var openSidebar = document.getElementById("openSidebar");
var sidebar = document.getElementById("sidebar");
var overlay = document.getElementById("overlay");

openSidebar.onclick = function() {
    sidebar.classList.add("show");
    overlay.classList.add("show");
    document.body.classList.add("sidebar-open"); 
};

overlay.onclick = function() {
    sidebar.classList.remove("show");
    overlay.classList.remove("show");
    document.body.classList.remove("sidebar-open"); 
};

window.onclick = function(event) {
    if (event.target == overlay) {
        sidebar.classList.remove("show");
        overlay.classList.remove("show");
        document.body.classList.remove("sidebar-open");
    }
};
</script>
