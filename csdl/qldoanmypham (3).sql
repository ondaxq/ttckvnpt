-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 23, 2024 at 02:47 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qldoanmypham`
--

-- --------------------------------------------------------

--
-- Table structure for table `chitietdonhang`
--

CREATE TABLE `chitietdonhang` (
  `idchitiet` int(11) NOT NULL,
  `iddonhang` int(11) DEFAULT NULL,
  `idsanpham` varchar(10) DEFAULT NULL,
  `soluong` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chitietdonhang`
--

INSERT INTO `chitietdonhang` (`idchitiet`, `iddonhang`, `idsanpham`, `soluong`) VALUES
(1, 2, '1', 1),
(2, 2, '2', 1),
(3, 1, '1', 1);

-- --------------------------------------------------------

--
-- Table structure for table `donhang`
--

CREATE TABLE `donhang` (
  `iddonhang` int(11) NOT NULL,
  `idnguoidung` int(6) DEFAULT NULL,
  `thoigiandat` datetime DEFAULT NULL,
  `sdtnguoinhan` varchar(15) DEFAULT NULL,
  `diachi` text DEFAULT NULL,
  `ptthanhtoan` int(6) NOT NULL,
  `tt` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `donhang`
--

INSERT INTO `donhang` (`iddonhang`, `idnguoidung`, `thoigiandat`, `sdtnguoinhan`, `diachi`, `ptthanhtoan`, `tt`) VALUES
(1, 2, '2024-12-20 16:05:33', '0123456789', 'Hà Tĩnh', 0, 0),
(2, 2, '2024-12-20 16:05:33', '0123456789', 'Hà Nội', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `giohang`
--

CREATE TABLE `giohang` (
  `idgiohang` int(11) NOT NULL,
  `idnguoidung` int(6) NOT NULL,
  `Id` varchar(10) NOT NULL,
  `soLuong` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lienhe`
--

CREATE TABLE `lienhe` (
  `idlienhe` int(11) NOT NULL,
  `idnguoidung` int(6) DEFAULT NULL,
  `hoten` varchar(100) DEFAULT NULL,
  `sdt` varchar(11) DEFAULT NULL,
  `noidung` text DEFAULT NULL,
  `ngaygui` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lienhe`
--

INSERT INTO `lienhe` (`idlienhe`, `idnguoidung`, `hoten`, `sdt`, `noidung`, `ngaygui`) VALUES
(1, 2, 'a', '0123456789', 'test', '2024-12-20 16:01:39');

-- --------------------------------------------------------

--
-- Table structure for table `nguoidung`
--

CREATE TABLE `nguoidung` (
  `idnguoidung` int(6) NOT NULL,
  `tendangnhap` varchar(50) NOT NULL,
  `matkhau` varchar(255) NOT NULL,
  `hoten` varchar(50) NOT NULL,
  `sdt` varchar(11) NOT NULL,
  `vaitro` int(2) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nguoidung`
--

INSERT INTO `nguoidung` (`idnguoidung`, `tendangnhap`, `matkhau`, `hoten`, `sdt`, `vaitro`, `email`) VALUES
(1, 'admin', '$2y$10$kL.Ifb5Y4q6XdNWsT1hO5O82RHy1boR5eJnQ8ul..NV9APcE3gD2i', 'admin', '0123456788', 1, 'admin@gmail.com'),
(2, 'a', 'a', 'a', '0123456789', 0, 'a@gmail.com'),
(26, 'bb', '$2y$10$2PIkBOSMkQP6sYexhft9reOldxfxjZCj//o31jFXYI61xSodK3.P2', 'Anhhhhhhhhh', '0123456784', 0, 'daaa@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `sanpham`
--

CREATE TABLE `sanpham` (
  `Id` varchar(10) NOT NULL,
  `Ten` varchar(100) NOT NULL,
  `PhanLoai` varchar(50) NOT NULL,
  `NhaCungCap` varchar(30) NOT NULL,
  `DungTich` int(4) NOT NULL,
  `MoTa` varchar(255) NOT NULL,
  `Gia` int(8) NOT NULL,
  `GiamGia` int(4) NOT NULL,
  `SoLuongTonKho` int(4) NOT NULL,
  `DaBan` int(5) NOT NULL,
  `HinhAnh` varchar(255) NOT NULL,
  `NgayNhapHang` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sanpham`
--

INSERT INTO `sanpham` (`Id`, `Ten`, `PhanLoai`, `NhaCungCap`, `DungTich`, `MoTa`, `Gia`, `GiamGia`, `SoLuongTonKho`, `DaBan`, `HinhAnh`, `NgayNhapHang`) VALUES
('1', 'Sữa rửa mặt tạo bọt Glucoside', 'Sữa rửa mặt', 'The Ordinary', 150, 'Một loại sữa rửa mặt tạo bọt nhẹ nhàng giúp làm sạch da hiệu quả đồng thời duy trì hàng rào độ ẩm cho da.\r\nLoại da: Bình thường, Khô, Hỗn hợp và Dầu', 316000, 10, 78, 113, 'img/1.webp', '2024-06-18'),
('10', 'Nước hoa hồng tẩy tế bào chết mini Glycolic Acid 7', 'Toner', 'The Ordinary', 100, 'Một chất tẩy tế bào chết bề mặt hàng ngày giúp làm mịn kết cấu da, làm đều màu da và tăng độ sáng.\r\nLoại da: Bình thường, Khô, Hỗn hợp và Dầu', 202000, 0, 63, 17, 'img/10.webp', '2024-11-06'),
('11', 'Dưa hấu Glow PHA + BHA Pore-Tight Toner', 'Toner', 'Glow Recipe', 40, 'Nước hoa hồng dưa hấu bán chạy nhất có chứa PHA và BHA giúp dưỡng ẩm, tẩy tế bào chết nhẹ nhàng cho da và giúp giảm thiểu sự xuất hiện của lỗ chân lông\r\nLoại da: Bình thường, Khô, Hỗn hợp và Dầu', 405000, 30, 85, 35, 'img/11.webp', '2024-11-06'),
('12', 'Kem dưỡng da & dưỡng ẩm có thể nạp lại với Ceramid', 'Toner', 'LANEIGE', 170, 'Một loại nước hoa hồng kết hợp kem sữa dễ chịu đựng trong một chai có thể tái sử dụng, được làm giàu với ceramide và peptide nuôi dưỡng cho làn da rạng rỡ, ngậm nước.\r\nLoại da: Bình thường, Khô, Hỗn hợp và Dầu', 911000, 8, 20, 15, 'img/12.webp', '2024-11-06'),
('13', ' Nước hoa hồng thu nhỏ lỗ chân lông Squalane + BHA', 'Toner', 'Biossance', 120, 'Một dung dịch làm săn chắc da không chứa cồn chứa AHA và BHA có nguồn gốc tự nhiên giúp se khít lỗ chân lông, làm rõ, thanh lọc và dưỡng ẩm cùng một lúc.\r\nLoại da: Bình thường, Khô, Hỗn hợp và Dầu', 759000, 20, 68, 21, 'img/13.webp', '2024-11-06'),
('14', 'Nước hoa hồng bổ sung cao cấp RESIST với axit Hyal', 'Toner', 'Paula\'s Choice', 118, ' Một loại mực làm mềm giúp bổ sung các chất dinh dưỡng thiết yếu, axit béo và axit hyaluronic để làm đầy đặn và làm dịu làn da của bạn sau khi rửa mặt.\r\nLoại da: Bình thường và khô', 734000, 0, 45, 50, 'img/14.webp', '2024-11-06'),
('15', 'Toner đa chức năng', 'Toner', 'Dermalogica', 250, 'Một loại xịt toner nhẹ nhàng giúp cấp ẩm và làm mới làn da.\r\nLoại da: Da thường, Da khô, Da hỗn hợp và Da dầu', 1110000, 5, 89, 14, 'img/15.webp', '2024-11-06'),
('16', 'Nước hoa hồng dưỡng ẩm sâu Hyaluronic Acid', 'Toner', 'fresh', 250, 'Một loại toner dùng hàng ngày không gây bong tróc da với cánh hoa hồng thật và axit hyaluronic giúp giải quyết tình trạng da mất nước đồng thời thu nhỏ lỗ chân lông.\r\nLoại da:  Bình thường, Khô, Hỗn hợp và Dầu', 1000000, 0, 36, 55, 'img/16.webp', '2024-11-06'),
('17', 'Superfood Skin Drip Smooth + Glow Barrier với Pept', 'Serum', 'Youth To The People', 30, 'tăng cường hàng rào bảo vệ 3 trong 1 này cấp ẩm tức thì, làm sáng + làm đều màu da. Được cung cấp bởi peptide + vitamin, sản phẩm bổ sung cho làn da sáng mịn.\r\nLoại da: Bình thường, Khô, Hỗn hợp và Dầu', 999999, 15, 233, 121, 'img/17.webp', '2024-11-06'),
('18', 'Serum làm dịu và hỗ trợ hàng rào bảo vệ', 'Serum', 'The Ordinary', 30, 'Một giải pháp đa hoạt tính được thiết kế để hỗ trợ phục hồi hàng rào bảo vệ da đồng thời làm dịu cảm giác khó chịu và giảm mẩn đỏ.\r\nLoại da: Bình thường, Khô, Hỗn hợp và Dầu', 400000, 0, 57, 0, 'img/18.webp', '2024-11-06'),
('19', ' Serum làm đầy nhanh chóng Squalane + Hyaluronic A', 'Serum', 'Biossance', 50, 'dưỡng ẩm mạnh mẽ giúp làm đầy đặn da ngay lập tức và rõ rệt với phức hợp chất dưỡng ẩm đã được chứng minh lâm sàng và peptide đồng tăng cường collagen.\r\nLoại da: Bình thường, Khô, Hỗn hợp và Dầu', 1720000, 0, 24, 0, 'img/19.webp', '2024-11-06'),
('2', 'Sữa rửa mặt Squalane mini', 'Sữa rửa mặt', 'The Ordinary', 50, 'Một loại sữa rửa mặt nhẹ nhàng, dưỡng ẩm.\r\nLoại da: Bình thường, Khô, Hỗn hợp và Dầu', 265000, 0, 56, 79, 'img/2.webp', '2024-08-21'),
('20', 'Serum phục hồi đa năng Advanced Night Repair với a', 'Serum', 'Estée Lauder', 50, 'có chứa Estée Lauder\'s Night Peptide giúp giảm rõ rệt nhiều dấu hiệu lão hóa, mang lại vẻ ngoài mịn màng, trẻ trung và rạng rỡ hơn.\r\nLoại da: Da thường, da khô và da hỗn hợp', 3200000, 26, 14, 123, 'img/20.webp', '2024-11-06'),
('21', 'Serum dưỡng da Double Serum Light Texture', 'Serum', 'Clarins', 50, 'mang tính biểu tượng với 21 chiết xuất thực vật giúp da săn chắc, mịn màng và rạng rỡ hơn rõ rệt—giờ đây có kết cấu nhẹ lý tưởng cho da dầu và khí hậu ẩm ướt.\r\nLoại da: Da hỗn hợp và da dầu', 3400000, 0, 8, 0, 'img/21.webp', '2024-06-20'),
('22', 'Serum  tăng cường độ sáng da Advanced Génifique', 'Serum', 'Lancôme', 50, 'Có chứa bifidus prebiotic, axit hyaluronic và vitamin C giúp tăng cường hàng rào độ ẩm của da để làm mịn, cấp nước, căng mọng, đều màu và giảm nếp nhăn rõ rệt.\r\nLoại da: Bình thường, Khô, Hỗn hợp và Dầu', 2999999, 0, 18, 4, 'img/22.webp', '2024-11-06'),
('23', ' Serum chống lão hóa kép giúp săn chắc, tăng cường', 'Serum', 'Clarins', 30, 'Hai trong một, bao gồm 22 chiết xuất thực vật và năm phân tử hoạt tính, có tác dụng bắt chước làn da để làm săn chắc rõ rệt, tăng cường độ rạng rỡ và cải thiện nếp nhăn và lỗ chân lông chỉ sau bảy ngày.\r\nLoại da: Bình thường, Khô, Hỗn hợp và Dầu', 2400000, 32, 25, 42, 'img/23.webp', '2024-06-04'),
('24', 'Charlotte’s Magic Serum với Vitamin C', 'Serum', 'Charlotte Tilbury', 30, ' chống lão hóa chứa Vitamin C và Axit Polyglutamic giúp làm giảm sự xuất hiện của các đốm đen, nếp nhăn và vết chân chim, làm sáng da rõ rệt và làm căng mịn da để có lớp trang điểm hoàn hảo.\r\nLoại da: Bình thường, Khô, Hỗn hợp và Dầu', 2199999, 19, 17, 72, 'img/24.webp', '2024-11-06'),
('25', 'Dưỡng ẩm tự nhiên + HA', 'Kem dưỡng ẩm', 'The Ordinary', 100, 'Công thức dưỡng ẩm với axit amin, lipid da và axit hyaluronic.', 354000, 0, 132, 25, 'img/25.webp', '2024-11-06'),
('26', 'Kem dưỡng ẩm Superfood Air-Whip', 'Kem dưỡng ẩm', 'Youth To The People', 59, 'Một loại kem dưỡng ẩm dạng gel nhẹ có tác dụng làm đầy đặn rõ rệt, dưỡng ẩm lên đến 48H và tăng cường hàng rào độ ẩm cho da trong một giờ để có làn da sáng như sương.\r\nLoại da: Bình thường, Hỗn hợp và Da dầu', 1200000, 10, 73, 56, 'img/26.webp', '2024-11-06'),
('27', ' Kem dưỡng ẩm Ultra Facial Refillable', 'Kem dưỡng ẩm', 'Kiehl\'s Since 1851', 50, ' Một loại kem dưỡng da mặt bán chạy nhất có công thức chứa squalane, glycoprotein băng và pro-ceramides để hỗ trợ hàng rào bảo vệ da của bạn cho khả năng dưỡng ẩm lên đến 72 giờ.\r\nLoại da: Bình thường, Khô, Hỗn hợp và Dầu', 987000, 0, 31, 136, 'img/27.webp', '2024-11-06'),
('28', 'Dưỡng ẩm tự nhiên mini + HA', 'Kem dưỡng ẩm', 'The Ordinary', 30, 'Công thức dưỡng ẩm với axit amin, lipid da và axit hyaluronic.\r\nLoại da: khô\r\n', 169000, 5, 43, 11, 'img/28.webp', '2024-11-06'),
('29', 'Kem dưỡng ẩm cường độ cao Ultra Repair®', 'Kem dưỡng ẩm', 'First Aid Beauty', 180, 'Loại kem dưỡng ẩm từng đoạt giải thưởng này ngay lập tức làm giảm tình trạng da khô, da khó chịu và bệnh chàm, đồng thời củng cố hàng rào bảo vệ da trong 7 ngày để mang lại làn da bình tĩnh, thoải mái.\r\nLoại da: Bình thường, khô và hỗn hợp', 961000, 0, 45, 87, 'img/29.webp', '2024-11-06'),
('3', ' Sữa rửa mặt Glycolic', 'Sữa rửa mặt', 'Dermalogica', 150, 'Sữa rửa mặt AHA làm sáng và dưỡng ẩm giúp tẩy tế bào chết cho làn da xỉn màu và loại bỏ sự tích tụ do ô nhiễm và các yếu tố môi trường khác.\r\nLoại da: Bình thường, Hỗn hợp và Da dầu', 961000, 31, 257, 15, 'img/3.webp', '2024-11-06'),
('30', 'Kem dưỡng ẩm Ceramide siêu dưỡng ẩm', 'Kem dưỡng ẩm', 'Farmacy', 50, 'Một loại kem dưỡng ẩm ceramide siêu dưỡng ẩm đã được chứng minh lâm sàng giúp làm đầy đặn rõ rệt, cải thiện vẻ ngoài của nếp nhăn và bổ sung làn da khô, căng thẳng.\r\nLoại da: Bình thường và khô', 1200000, 0, 46, 57, 'img/30.webp', '2024-11-06'),
('31', 'Kem dưỡng ẩm ban ngày đa hoạt động với Niacinamide', 'Kem dưỡng ẩm', 'Clarins', 50, 'Một loại kem dưỡng ẩm hàng ngày nhắm vào các dấu hiệu lão hóa đầu tiên có thể nhìn thấy để làm mờ các đường nhăn, cải thiện sự xuất hiện của lỗ chân lông và hỗ trợ hàng rào độ ẩm mạnh mẽ — cho làn da sáng khỏe.\r\nLoại da: Bình thường, Khô, Hỗn hợp và Dầu', 1490000, 10, 12, 24, 'img/31.webp', '2024-11-06'),
('32', 'Kem dưỡng ẩm thu nhỏ lỗ chân lông không chứa dầu', 'Kem dưỡng ẩm', 'Tatcha', 50, 'Một loại kem gel nhẹ, không chứa dầu, có thể tái sử dụng, mang lại khả năng hydrat hóa gấp 3 lần* đồng thời tinh chỉnh rõ rệt lỗ chân lông & làm mịn kết cấu bằng sản phẩm thay thế BHA.\r\nLoại da: Bình thường, Da dầu và Hỗn hợp', 1820000, 10, 32, 25, 'img/32.webp', '2024-11-06'),
('33', ' Kem chống nắng vô hình Phổ rộng vô hình SPF 40 PA', 'Kem chống nắng', 'Supergoop!', 50, 'Kem chống nắng vô hình 100%, không trọng lượng, không mùi, cung cấp khả năng bảo vệ phổ rộng SPF và có tác dụng như kem lót với lớp nền tự nhiên.\r\nLoại da:  Bình thường, Khô, Hỗn hợp và Dầu', 965000, 20, 45, 25, 'img/33.webp', '2024-11-06'),
('34', 'Kem chống nắng dạng thỏi Clear Sunscreen Stick SPF', 'Kem chống nắng', 'Shiseido', 20, 'Que chống nắng di động có chỉ số SPF 50+ và lớp nền dưỡng ẩm vô hình có thể thoa lại bất cứ lúc nào, trên hoặc dưới lớp trang điểm, trên mọi loại da.\r\nLoại da: Bình thường, Khô, Hỗn hợp và Dầu', 812000, 0, 25, 1, 'img/34.webp', '2023-01-07'),
('35', ' Kem chống nắng phổ rộng SPF 36 chống tia UV hàng ', 'Kem chống nắng', 'innisfree', 50, 'Kem chống nắng dùng hàng ngày, không để lại vệt trắng với trà xanh và dầu hạt hướng dương giúp bảo vệ da khỏi tia UVA và UVB đồng thời mang lại làn da tươi tắn, căng bóng.\r\nLoại da: Bình thường, Khô, Hỗn hợp và Dầu', 457000, 10, 56, 13, 'img/35.webp', '2024-11-06'),
('36', 'Kem chống nắng Hydro UV Defense phổ rộng SPF 50+', 'Kem chống nắng', 'LANEIGE', 50, 'Kem chống nắng Hàn Quốc phổ rộng SPF 50+ dưỡng ẩm, cung cấp khả năng bảo vệ khỏi tia UVA và UVB, đồng thời dưỡng ẩm dịu nhẹ mà không để lại vệt trắng.\r\nLoại da: Bình thường, Khô, Hỗn hợp và Dầu', 762000, 0, 123, 4, 'img/36.webp', '2024-11-06'),
('37', 'Kem chống nắng Urban Environment Fresh-Moisture phổ rộng SPF 42 với axit hyaluronic', 'Kem chống nắng', 'Shiseido', 50, 'Kem chống nắng dưỡng ẩm hàng ngày cho da khô, bảo vệ da khỏi tia UVA/UVB có hại với SPF 42, đồng thời dưỡng ẩm và làm da căng mịn rõ rệt.\r\nLoại da: Da thường, da khô và da hỗn hợp', 965000, 0, 156, 34, 'img/37.webp', '2024-11-06'),
('38', 'Kem chống nắng khoáng chất không trọng lượng The Silk Sunscreen SPF 50', 'Kem chống nắng', 'Tatcha', 50, 'Kem chống nắng khoáng chất SPF 50 không trọng lượng cung cấp khả năng bảo vệ tăng cường chống lại các dấu hiệu lão hóa sớm, cấp ẩm nhanh chóng và làm đều màu da rõ rệt theo thời gian.\r\nLoại da: Da thường, Da khô, Da hỗn hợp và Da dầu', 162000, 0, 50, 45, 'img/38.webp', '2024-11-06'),
('39', 'Kem chống nắng phổ rộng UV Daily Cream SPF 40', 'Kem chống nắng', 'Sulwhasoo', 40, 'Kem chống nắng phổ rộng SPF 40 dưỡng ẩm hòa quyện hoàn hảo vào da mà không để lại vệt trắng.\r\nLoại da: Da thường, Da khô, Da hỗn hợp và Da dầu', 1900000, 0, 85, 5, 'img/39.webp', '2024-11-06'),
('4', ' Squalane + Dầu tẩy trang chống oxy hóa', 'Sữa rửa mặt', 'Biossance', 200, 'Một loại dầu làm sạch giàu chất chống oxy hóa, nhẹ nhàng loại bỏ lớp trang điểm lâu trôi và các tạp chất để mang lại làn da cảm giác căng bóng.\r\nLoại da: Bình thường, Khô, Hỗn hợp và Dầu', 810000, 10, 121, 0, 'img/4.webp', '2024-11-06'),
('40', 'Kem chống nắng phổ rộng SPF 50 cho mặt', 'Kem chống nắng', 'La Mer', 50, 'Một loại kem dưỡng da nhẹ, dùng hàng ngày, có tác dụng bảo vệ da khỏi tia UV.\r\nLoại da: Da thường, Da khô, Da hỗn hợp và Da dầu', 3100000, 12, 12, 25, 'img/40.webp', '2024-11-06'),
('41', 'Kem nền dạng thỏi nhẹ che phủ toàn diện Good Apple', 'Kem nền', 'KVD Beauty', 10, 'Kem nền dưỡng ẩm, che phủ hoàn toàn, có thể tạo lớp với công thức nhẹ, lâu trôi và lớp nền lì tươi mới', 1070000, 0, 42, 34, 'img/41.webp', '2024-04-11'),
('42', 'Kem nền và kem che khuyết điểm Minimalist Perfecting Complexion Stick', 'Kem nền', 'MERIT', 7, 'Một thỏi kem nền mỏng nhẹ, dễ tán, có thể dùng làm kem nền hoặc kem che khuyết điểm để có lớp nền tự nhiên khi di chuyển', 1070000, 10, 42, 1, 'img/42.webp', '2024-11-06'),
('43', 'Kem nền lâu trôi Bounce™ Liquid Whip', 'Kem nền', 'Beautyblender', 30, 'Kem nền dạng lỏng che phủ toàn diện, không chứa dầu với lớp nền lì tự nhiên, bám trên da tới 24 giờ—hiện có bao bì được nâng cấp, bền vững. Độ che phủ: Hoàn thiện toàn diện : Tự nhiên Công thức: Dạng lỏng Thành phần nổi bật: - Hyaluronic Acid: Làm căng da', 355000, 13, 47, 12, 'img/43.webp', '2024-11-06'),
('44', 'Kem nền không dầu Luminous Silk Perfect Glow Flawless Oil-Free', 'Kem nền', 'Armani Beauty', 30, 'Kem nền dạng lỏng không chứa dầu, từng đoạt giải thưởng, mang lại độ che phủ trung bình và lớp nền sáng mịn cho làn da rạng rỡ, tự nhiên.', 1750000, 30, 15, 23, 'img/44.webp', '2024-11-06'),
('45', 'Kem nền CC+ Natural Matte có SPF 40', 'Kem nền', 'IT Cosmetics', 32, 'Kem nền lì che phủ toàn diện cho da dầu, huyết thanh cân bằng da và kem chống nắng phổ rộng SPF 40 trong một sản phẩm.', 1194000, 15, 42, 10, 'img/45.webp', '2023-11-02'),
('46', 'Kem nền và che khuyết điểm có thể điều chỉnh Vision Cream Cover', 'Kem nền', 'Danessa Myricks Beauty', 10, 'Công thức kết hợp kem nền và kem che khuyết điểm cải tiến được thiết kế để mang đến cho bạn độ che phủ có thể điều chỉnh tối ưu hoặc tạo lớp nền mỏng nhẹ.', 558000, 0, 43, 5, 'img/46.webp', '2024-11-06'),
('47', 'Kem lót nền không dầu Mini Photo Finish Smooth & Blur', 'Kem nền', 'Smashbox', 30, 'Gel lót trong suốt, không chứa dầu giúp làm mịn da và lỗ chân lông để lớp trang điểm của bạn lâu trôi.', 406000, 32, 53, 32, 'img/47.webp', '2024-03-05'),
('48', 'Kem nền chống thấm nước tự đông Face Bond', 'Kem nền', 'Urban Decay', 12, 'Kem nền chống nước nhẹ với lợi ích chăm sóc da của huyết thanh và phấn phủ tự cố định tạo hiệu ứng làm mờ với độ che phủ trung bình, lớp nền lì', 1000000, 14, 14, 25, 'img/48.webp', '2024-11-06'),
('5', 'Sữa rửa mặt tạo bọt nghệ', 'Sữa rửa mặt', 'KORA Organics', 30, 'Một loại sữa rửa mặt dạng gel có độ pH tiếp thêm sinh lực, thân thiện với da, chuyển đổi thành bọt để làm sạch hoàn toàn bụi bẩn, dầu và các tạp chất khác có thể nhìn thấy.\r\nLoại da: Bình thường, Khô, Hỗn hợp và Dầu', 413000, 10, 47, 100, 'img/5.webp', '2024-11-06'),
('6', 'Sữa rửa mặt mini Pure Skin', 'Sữa rửa mặt', 'First Aid Beauty', 56, ' Một loại sữa rửa mặt nhẹ nhàng, không mùi thơm, giúp loại bỏ hiệu quả lớp trang điểm, bụi bẩn và bụi bẩn, mang lại làn da mềm mại và dẻo dai.\r\nLoại da: Bình thường, khô và hỗn hợp', 300000, 0, 123, 15, 'img/6.webp', '2024-11-06'),
('7', ' Sữa rửa mặt cải xoăn', 'Sữa rửa mặt', 'Youth To The People', 237, 'Sữa rửa mặt dạng gel không làm khô, chứa chất chống oxy hóa, nhẹ nhàng nhưng hiệu quả loại bỏ lớp trang điểm, SPF và dầu thừa, giúp da ngậm nước và sáng mịn.\r\nLoại da: Bình thường, Khô, Hỗn hợp và Dầu', 987000, 10, 58, 45, 'img/7.webp', '2023-11-05'),
('8', 'Sữa rửa mặt tạo bọt sữa chua Hy Lạp', 'Sữa rửa mặt', 'KORRES', 150, 'Một loại sữa rửa mặt siêu thực phẩm bán chạy nhất được pha chế từ sữa chua Hy Lạp giàu prebiotic và probiotic giúp làn da trông tinh khiết và được nuôi dưỡng chỉ sau một lần làm sạch.\r\nLoại da: Bình thường, Khô, Hỗn hợp và Dầu', 708000, 20, 75, 35, 'img/8.webp', '2023-08-14'),
('9', 'Saccharomyces lên men 30% Milky Toner', 'Toner', 'The Ordinary', 100, 'Một loại nước hoa hồng dạng sữa nhẹ nhàng tẩy tế bào chết, thích hợp cho làn da nhạy cảm, giúp mang lại làn da mịn màng, tươi sáng hơn đồng thời tăng cường dưỡng ẩm.\r\nLoại da: Bình thường, Khô, Hỗn hợp và Dầu', 354000, 0, 121, 35, 'img/9.webp', '2024-08-13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD PRIMARY KEY (`idchitiet`),
  ADD KEY `iddonhang` (`iddonhang`),
  ADD KEY `idsanpham` (`idsanpham`);

--
-- Indexes for table `donhang`
--
ALTER TABLE `donhang`
  ADD PRIMARY KEY (`iddonhang`),
  ADD KEY `idnguoidung` (`idnguoidung`);

--
-- Indexes for table `giohang`
--
ALTER TABLE `giohang`
  ADD PRIMARY KEY (`idgiohang`),
  ADD KEY `idnguoidung` (`idnguoidung`),
  ADD KEY `giohang_ibfk_2` (`Id`);

--
-- Indexes for table `lienhe`
--
ALTER TABLE `lienhe`
  ADD PRIMARY KEY (`idlienhe`),
  ADD KEY `idnguoidung` (`idnguoidung`);

--
-- Indexes for table `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD PRIMARY KEY (`idnguoidung`);

--
-- Indexes for table `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  MODIFY `idchitiet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `donhang`
--
ALTER TABLE `donhang`
  MODIFY `iddonhang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `giohang`
--
ALTER TABLE `giohang`
  MODIFY `idgiohang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `lienhe`
--
ALTER TABLE `lienhe`
  MODIFY `idlienhe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `nguoidung`
--
ALTER TABLE `nguoidung`
  MODIFY `idnguoidung` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD CONSTRAINT `chitietdonhang_ibfk_1` FOREIGN KEY (`iddonhang`) REFERENCES `donhang` (`iddonhang`),
  ADD CONSTRAINT `chitietdonhang_ibfk_2` FOREIGN KEY (`idsanpham`) REFERENCES `sanpham` (`Id`);

--
-- Constraints for table `donhang`
--
ALTER TABLE `donhang`
  ADD CONSTRAINT `donhang_ibfk_1` FOREIGN KEY (`idnguoidung`) REFERENCES `nguoidung` (`idnguoidung`);

--
-- Constraints for table `giohang`
--
ALTER TABLE `giohang`
  ADD CONSTRAINT `giohang_ibfk_1` FOREIGN KEY (`idnguoidung`) REFERENCES `nguoidung` (`idnguoidung`) ON DELETE CASCADE,
  ADD CONSTRAINT `giohang_ibfk_2` FOREIGN KEY (`Id`) REFERENCES `sanpham` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `lienhe`
--
ALTER TABLE `lienhe`
  ADD CONSTRAINT `lienhe_ibfk_1` FOREIGN KEY (`idnguoidung`) REFERENCES `nguoidung` (`idnguoidung`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
