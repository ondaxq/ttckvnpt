<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Cửa Hàng</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="admin.css?<?php echo time(); ?>" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100">
    <?php include 'admin-header.php'; ?>
    <div class="flex flex-col">
        <div class="flex flex-1">
            <?php include 'admin-sideitems.php'; ?>
            <main class="flex-1 p-6">
                <div class="container mx-auto" id="data-container">
                    
                </div>
            </main>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $.ajax({
                url: 'beadmin.php', 
                type: 'GET',
                success: function(response) {
                    var data = JSON.parse(response);

                    $('#data-container').html(`
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                            <div class="column bg-white p-4 rounded-lg shadow-md">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full bg-blue-500 text-white mr-4">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-600">Tổng sản phẩm trong kho</p>
                                        <p class="text-2xl font-semibold">${data.total_stock}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="column bg-white p-4 rounded-lg shadow-md">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full bg-green-500 text-white mr-4">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-600">Tổng số người dùng</p>
                                        <p class="text-2xl font-semibold">${data.total_users}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="column bg-white p-4 rounded-lg shadow-md">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full bg-yellow-500 text-white mr-4">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-600">Tổng doanh thu</p>
                                        <p class="text-2xl font-semibold">${data.total_revenue.toLocaleString()}₫</p>
                                    </div>
                                </div>
                            </div>
                            <div class="column bg-white p-4 rounded-lg shadow-md">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full bg-red-500 text-white mr-4">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-600">Đơn hàng mới</p>
                                        <p class="text-2xl font-semibold">${data.total_orders_today}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h2 class="text-xl font-semibold text-gray-700 mb-4">Quản lý đơn hàng</h2>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white">
                                    <thead>
                                        <tr>
                                            <th class="py-2 px-4 border-b border-gray-200 text-center">ID đơn hàng</th>
                                            <th class="py-2 px-4 border-b border-gray-200 text-center">Họ tên</th>
                                            <th class="py-2 px-4 border-b border-gray-200 text-center">Ngày đặt</th>
                                            <th class="py-2 px-4 border-b border-gray-200 text-center">Thành tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${data.orders.map(order => `
                                            <tr>
                                                <td class="py-2 px-4 border-b border-gray-200 text-center">#${order.iddonhang}</td>
                                                <td class="py-2 px-4 border-b border-gray-200 text-center">${order.hoten}</td>
                                                <td class="py-2 px-4 border-b border-gray-200 text-center">${new Date(order.thoigiandat).toLocaleDateString()}</td>
                                                <td class="py-2 px-4 border-b border-gray-200 text-center">${order.total.toLocaleString()}₫</td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow-md mt-6">
                            <h2 class="text-xl font-semibold text-gray-700 mb-4">Quản lý người dùng</h2>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white">
                                    <thead>
                                        <tr>
                                            <th class="py-2 px-4 border-b border-gray-200 text-center">ID người dùng</th>
                                            <th class="py-2 px-4 border-b border-gray-200 text-center">Họ tên</th>
                                            <th class="py-2 px-4 border-b border-gray-200 text-center">Số điện thoại</th>
                                            <th class="py-2 px-4 border-b border-gray-200 text-center">Email</th>
                                            <th class="py-2 px-4 border-b border-gray-200 text-center">Vai trò</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${data.users.map(user => `
                                            <tr>
                                                <td class="py-2 px-4 border-b border-gray-200 text-center">#${user.idnguoidung}</td>
                                                <td class="py-2 px-4 border-b border-gray-200 text-center">${user.hoten}</td>
                                                <td class="py-2 px-4 border-b border-gray-200 text-center">${user.sdt}</td>
                                                <td class="py-2 px-4 border-b border-gray-200 text-center">${user.email}</td>
                                                <td class="py-2 px-4 border-b border-gray-200 text-center">${user.vaitro == 1 ? 'Admin' : 'User'}</td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow-md mt-6">
                            <h2 class="text-xl font-semibold text-gray-700 mb-4">Quản lý liên hệ</h2>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white">
                                    <thead>
                                        <tr>
                                            <th class="py-2 px-4 border-b border-gray-200 text-center">ID liên hệ</th>
                                            <th class="py-2 px-4 border-b border-gray-200 text-center">Họ tên</th>
                                            <th class="py-2 px-4 border-b border-gray-200 text-center">Số điện thoại</th>
                                            <th class="py-2 px-4 border-b border-gray-200 text-center">Nội dung</th>
                                            <th class="py-2 px-4 border-b border-gray-200 text-center">Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${data.contacts.map(contact => `
                                            <tr>
                                                <td class="py-2 px-4 border-b border-gray-200 text-center">#${contact.idlienhe}</td>
                                                <td class="py-2 px-4 border-b border-gray-200 text-center">${contact.hoten}</td>
                                                <td class="py-2 px-4 border-b border-gray-200 text-center">${contact.sdt}</td>
                                                <td class="py-2 px-4 border-b border-gray-200 text-center">${contact.noidung}</td>
                                                <td class="py-2 px-4 border-b border-gray-200 text-center">${contact.idnguoidung == null ? 'Chưa tạo tài khoản' : 'Đã tạo tài khoản'}</td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    `);
                },
                error: function() {
                    alert('Không thể tải dữ liệu');
                }
            });
        });
    </script>
</body>
</html>
