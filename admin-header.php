<?php

include 'db.php'; 
if (isset($_SESSION['tendangnhap'])) {
    $tendangnhap = $_SESSION['tendangnhap'];
   
    $stmt = $pdo->prepare("SELECT hoten, sdt, email FROM nguoidung WHERE tendangnhap = :tendangnhap");
    $stmt->execute(['tendangnhap' => $tendangnhap]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
   
    $user = null;
}
?>
<nav class="bg-white border-gray-200 dark:bg-gray-900">
    <div class="flex items-center justify-between mx-auto p-4 ml-2 mr-2">
        <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Quản Lý Cửa Hàng</span>

        <div class="relative">
            <button type="button" class="avatar text-sm rounded-full" id="user-menu-button" aria-expanded="false" onclick="toggleDropdown(event)">
                <span class="sr-only">Open user menu</span>
                <i class="fas fa-user-circle" style="font-size:28px"></i>
            </button>
            <div id="user-dropdown" class="dropdown-menu absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-md dark:bg-gray-700 dark:border-gray-600 z-50 hidden">
                <div class="user-dropdown px-4 py-3">

                    <?php if ($user): ?>
                        <span class="block text-sm text-gray-900 dark:text-white"><?= htmlspecialchars($user['hoten']) ?></span>
                        <span class="block text-sm text-gray-500 truncate dark:text-gray-400"><?= htmlspecialchars($user['sdt']) ?></span>
                    <?php else: ?>
                        <span class="block text-sm text-gray-900 dark:text-white">Khách hàng</span>
                        <span class="block text-sm text-gray-500 dark:text-gray-400">Chưa đăng nhập</span>
                    <?php endif; ?>
                </div>
                <ul class="log-out">
                    <?php if ($user): ?>
                        <li><a href="logout.php" class="dropdown-item">Đăng xuất</a></li>
                    <?php else: ?>
                        <li><a href="login.php" class="dropdown-item">Đăng nhập</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</nav>

<script>
function toggleDropdown(event) {
    event.stopPropagation(); 
    const dropdown = document.getElementById('user-dropdown');
    dropdown.classList.toggle('show'); 
}

document.addEventListener('click', function (e) {
    const dropdown = document.getElementById('user-dropdown');
    const button = document.getElementById('user-menu-button');
    if (!button.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.remove('show');
    }
});


</script>