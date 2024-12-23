<?php
include 'db.php'; 
if (isset($_SESSION['tendangnhap'])) {
    $tendangnhap = $_SESSION['tendangnhap'];
    $stmt = $pdo->prepare("SELECT hoten, sdt,email FROM nguoidung WHERE tendangnhap = :tendangnhap");
    $stmt->execute(['tendangnhap' => $tendangnhap]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    $user = null; 
}
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
                <li><a href="home.php" class="menu-item">Trang chủ</a></li>
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
            <?php if ($user): ?>
                <a href="dat-hang.php" class="register-login-btn"> <i class="fas fa-shopping-cart"></i> Giỏ hàng </a>
                <button type="button" id="user-menu-button">
                    <i class="fas fa-user-circle" style="font-size:28px;"></i>
                </button>
                <div id="user-dropdown" style="display: none;">
                    <div>
                        <span><?= $user['hoten'] ?></span>
                        <br>
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
                            <a href="logout.php">Đăng xuất</a>
                        </li>
                    </ul>
                </div>
            <?php else: ?>
                <div class="nav-buttons">           
            <button id="loginBtn" class="register-login-btn" type="button">
                <span class="tooltip-text">Đăng nhập</span>
            </button>
        </div>
        
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
                    unset($_SESSION['message']); 
                ?>
            </div>
        <?php endif; ?>
        
        <form method="post" action="dang-ky.php"> 
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
    var loginModal = document.getElementById("loginModal");
    var loginBtn = document.getElementById("loginBtn");
    var closeLoginModal = document.getElementById("closeModal");

    var registerBtn = document.getElementById("registerBtn");
    var registerModal = document.getElementById("registerModal");
    var closeRegisterModal = document.getElementById("closeRegisterModal");

    var showRegisterModal = document.getElementById("showRegisterModal");
    var showLoginModal = document.getElementById("showLoginModal");
    loginBtn.onclick = function() {
        loginModal.style.display = "block";
    }

    closeLoginModal.onclick = function() {
        loginModal.style.display = "none";
    }

    registerBtn.onclick = function() {
        registerModal.style.display = "block"; 
    }

    closeRegisterModal.onclick = function() {
        registerModal.style.display = "none";
    }
    showLoginModal.onclick = function() {
        registerModal.style.display = "none"; 
        loginModal.style.display = "block"; 
    }

    showRegisterModal.onclick = function() {
        loginModal.style.display = "none";
        registerModal.style.display = "block";
    }
   
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
            <?php endif; ?>
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
</script>
