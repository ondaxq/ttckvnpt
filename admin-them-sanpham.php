<?php
include 'db.php';

// Kiểm tra nếu có dữ liệu gửi đến
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $id = $_POST['id'];
    $ten = $_POST['ten'];
    $phanLoai = $_POST['phanLoai'];
    $nhaCungCap = $_POST['nhaCungCap'];
    $dungTich = $_POST['dungTich'];
    $moTa = $_POST['moTa'];
    $gia = $_POST['gia'];
    $giamGia = $_POST['giamGia'];
    $soLuongTonKho = $_POST['soLuongTonKho'];
    $ngayNhapHang = $_POST['ngayNhapHang'];

    // Xử lý hình ảnh
    $hinhAnh = '';
    if (isset($_FILES['hinhAnh']) && $_FILES['hinhAnh']['error'] == 0) {
        $fileTmpPath = $_FILES['hinhAnh']['tmp_name'];
        $fileName = $_FILES['hinhAnh']['name'];
        $uploadDir = 'uploads/';
        
        // Kiểm tra nếu thư mục tồn tại
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Nếu thư mục không tồn tại, tạo thư mục uploads
        }

        $filePath = $uploadDir . $fileName;
        if (move_uploaded_file($fileTmpPath, $filePath)) {
            $hinhAnh = $filePath;
        } else {
            // Nếu không upload được file, trả về thông báo lỗi
            echo json_encode(['success' => false, 'message' => 'Không thể upload hình ảnh.']);
            exit;
        }
    }

    // Thêm dữ liệu vào cơ sở dữ liệu
    try {
        $stmt = $pdo->prepare("INSERT INTO sanpham (Id, Ten, PhanLoai, NhaCungCap, DungTich, MoTa, Gia, GiamGia, SoLuongTonKho, HinhAnh, NgayNhapHang) 
                               VALUES (:id, :ten, :phanLoai, :nhaCungCap, :dungTich, :moTa, :gia, :giamGia, :soLuongTonKho, :hinhAnh, :ngayNhapHang)");
        $stmt->execute([
            'id' => $id,
            'ten' => $ten,
            'phanLoai' => $phanLoai,
            'nhaCungCap' => $nhaCungCap,
            'dungTich' => $dungTich,
            'moTa' => $moTa,
            'gia' => $gia,
            'giamGia' => $giamGia,
            'soLuongTonKho' => $soLuongTonKho,
            'hinhAnh' => $hinhAnh,
            'ngayNhapHang' => $ngayNhapHang
        ]);
        
        // Trả về JSON thành công nếu không có lỗi
        echo json_encode(['success' => true, 'message' => 'Sản phẩm đã được thêm thành công!']);
    } catch (PDOException $e) {
        // Nếu có lỗi trong quá trình thêm dữ liệu
        echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi thêm sản phẩm: ' . $e->getMessage()]);
    }
} else {
    // Trả về thông báo lỗi nếu không phải là POST request
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ.']);
}
?>
