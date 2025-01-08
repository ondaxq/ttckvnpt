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
        <a href="home.php" class="logo">
            <span>Cửa Hàng Mỹ Phẩm</span>
        </a>
        <div id="mega-menu" class="mega-menu">
            <ul class="menu-list">
                <li><a href="home.php" class="menu-item">Trang chủ</a></li>
                <li class="danh-muc-dropdown">
    <a href="#" class="menu-item">Danh mục</a>
    <ul class="danh-muc-dropdown-content">
        <?php foreach ($categories as $category): ?>
            <li>
                <a href="phan-loai.php?query=<?= urlencode($category['PhanLoai']) ?>" class="dropdown-item">
                    <?= htmlspecialchars($category['PhanLoai']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</li>
                <li><a href="lien-he.php" class="menu-item">Liên hệ</a></li>
                <li class="search-container">
                    <form action="tim-kiem.php" method="get">
                        <input type="text" name="query" placeholder="Tìm kiếm sản phẩm..." class="search-input">
                        <button type="submit" class="find-btn"><i class="fas fa-search"></i></button>
                    </form>
                </li>
            </ul>
        </div>
        <div class="nav-buttons">           
            <button id="loginBtn" class="register-login-btn" type="button">
                <span class="tooltip-text">Đăng nhập</span>
            </button>
        </div>
    </div>
</nav>
  

<div id="loginModal" class="modal">
    <div class="modal-content">
        <span id="closeModal" class="close">&times;</span>
        <h2>Đăng nhập</h2>
        <form method="post" action="dang-nhap.php">
            <div class="input-box">
                Username: 
                <input type="text" name="tendangnhap" placeholder="Tên đăng nhập" required>
            </div>
            <div class="input-box">
                Password: 
                <input type="password" name="matkhau" placeholder="Mật khẩu" required>
            </div>
            <button class="Btn-login" id="loginBtn" type="submit">Đăng nhập</button>
        </form>
        <div class="register-link">
            <p>Không có tài khoản? <a href="#" id="showRegisterModal">Đăng ký</a></p>
        </div>
    </div>
</div>
<div id="registerModal" class="modal">
    <div class="modal-content">
        <span id="closeRegisterModal" class="close">&times;</span>
        <h2>Đăng ký</h2>
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message <?php echo ($_SESSION['message_type'] == 'success') ? 'success' : 'error'; ?>">
                <?php 
                    echo $_SESSION['message']; 
                    unset($_SESSION['message']); // Xóa thông báo sau khi hiển thị
                ?>
            </div>
        <?php endif; ?>
        
        <form method="post" action="dang-ky.php"> <!-- Chỉnh sửa action thành home.php -->
            <div class="input-box">
                Họ tên
                <input type="text" name="hoten" placeholder="Họ và tên" required>
            </div>
            <div class="input-box">
                Email    
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-box">
                Số điện thoại
                <input type="number" name="sdt" placeholder="Số điện thoại" required>
            </div>
            <div class="input-box">
                Username
                <input type="text" name="tendangnhap" placeholder="Tên đăng nhập" required>
            </div>
            <div class="input-box">
                Password
                <input type="password" name="matkhau" placeholder="Mật khẩu" required>
            </div>
            <button class="Btn-register" id="registerBtn" type="submit">Đăng ký</button>
        </form>
        <div class="login-link">
            <p>Đã có tài khoản? <a href="#" id="showLoginModal">Đăng nhập</a></p>
        </div>
    </div>
</div>
<script>


    // Lấy các phần tử modal và nút
    var loginModal = document.getElementById("loginModal");
    var loginBtn = document.getElementById("loginBtn");
    var closeLoginModal = document.getElementById("closeModal");

    var registerBtn = document.getElementById("registerBtn");
    var registerModal = document.getElementById("registerModal");
    var closeRegisterModal = document.getElementById("closeRegisterModal");

    var showRegisterModal = document.getElementById("showRegisterModal");
    var showLoginModal = document.getElementById("showLoginModal");

    // Mở modal đăng nhập
    loginBtn.onclick = function() {
        loginModal.style.display = "block";
    }

    // Đóng modal đăng nhập
    closeLoginModal.onclick = function() {
        loginModal.style.display = "none";
    }

    // Mở modal đăng ký
    registerBtn.onclick = function() {
        registerModal.style.display = "block"; // Mở modal đăng ký
    }

    // Đóng modal đăng ký
    closeRegisterModal.onclick = function() {
        registerModal.style.display = "none";
    }

    // Mở modal đăng nhập từ modal đăng ký
    showLoginModal.onclick = function() {
        registerModal.style.display = "none"; // Đóng modal đăng ký nếu đang mở
        loginModal.style.display = "block"; // Mở modal đăng nhập
    }

    showRegisterModal.onclick = function() {
        loginModal.style.display = "none";
        registerModal.style.display = "block";
    }
    // Đóng modal khi nhấp bên ngoài
    window.onclick = function(event) {
        if (event.target == loginModal) {
            loginModal.style.display = "none";
        } else if (event.target == registerModal) {
            registerModal.style.display = "none";
        }
    }

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