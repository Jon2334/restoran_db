CREATE DATABASE IF NOT EXISTS db_restoran;
USE db_restoran;

-- Tabel User
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    level ENUM('Admin', 'Kasir') NOT NULL
);

-- Tabel Menu
CREATE TABLE menu (
    kode_menu VARCHAR(10) PRIMARY KEY,
    nama_menu VARCHAR(100) NOT NULL,
    jenis ENUM('Makanan','Minuman','Snack') NOT NULL,
    harga DECIMAL(12,2) NOT NULL
);

-- Tabel Pelanggan
CREATE TABLE pelanggan (
    kode_pel VARCHAR(10) PRIMARY KEY,
    nama_pel VARCHAR(100) NOT NULL,
    alamat TEXT
);

-- Tabel Karyawan
CREATE TABLE karyawan (
    kode_karyawan VARCHAR(10) PRIMARY KEY,
    nama_karyawan VARCHAR(100) NOT NULL,
    jabatan VARCHAR(50) NOT NULL
);

-- Tabel Transaksi (Header)
CREATE TABLE transaksi (
    no_faktur VARCHAR(20) PRIMARY KEY,
    tanggal DATE NOT NULL,
    kode_pel VARCHAR(10) NOT NULL,
    total DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (kode_pel) REFERENCES pelanggan(kode_pel) ON UPDATE CASCADE ON DELETE RESTRICT
);

-- Tabel Detail Transaksi
CREATE TABLE detail_transaksi (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    no_faktur VARCHAR(20) NOT NULL,
    kode_menu VARCHAR(10) NOT NULL,
    harga DECIMAL(12,2) NOT NULL,
    jumlah INT NOT NULL,
    subtotal DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (no_faktur) REFERENCES transaksi(no_faktur) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (kode_menu) REFERENCES menu(kode_menu) ON UPDATE CASCADE ON DELETE RESTRICT
);

-- INSERT DUMMY DATA

-- Password untuk Admin: 'admin123', Kasir: 'kasir123'
-- Dihash menggunakan BCRYPT (password_hash)
INSERT INTO user (username, password, nama, level) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'Admin'),
('kasir', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Kasir Utama', 'Kasir');

-- Dummy Menu (20 item)
INSERT INTO menu (kode_menu, nama_menu, jenis, harga) VALUES
('M001', 'Nasi Goreng Spesial', 'Makanan', 25000),
('M002', 'Mie Goreng Seafood', 'Makanan', 28000),
('M003', 'Ayam Bakar Madu', 'Makanan', 30000),
('M004', 'Sate Ayam Madura', 'Makanan', 22000),
('M005', 'Gurame Asam Manis', 'Makanan', 45000),
('M006', 'Sop Buntut', 'Makanan', 55000),
('M007', 'Rendang Sapi', 'Makanan', 35000),
('M008', 'Gado-Gado', 'Makanan', 18000),
('M009', 'Es Teh Manis', 'Minuman', 5000),
('M010', 'Es Jeruk', 'Minuman', 8000),
('M011', 'Jus Alpukat', 'Minuman', 15000),
('M012', 'Jus Mangga', 'Minuman', 15000),
('M013', 'Kopi Hitam', 'Minuman', 10000),
('M014', 'Kopi Susu', 'Minuman', 12000),
('M015', 'Air Mineral', 'Minuman', 4000),
('M016', 'Kentang Goreng', 'Snack', 15000),
('M017', 'Pisang Goreng Keju', 'Snack', 12000),
('M018', 'Tahu Crispy', 'Snack', 10000),
('M019', 'Tempe Mendoan', 'Snack', 10000),
('M020', 'Roti Bakar Coklat', 'Snack', 18000);

-- Dummy Pelanggan (20 orang)
INSERT INTO pelanggan (kode_pel, nama_pel, alamat) VALUES
('P001', 'Budi Santoso', 'Jl. Merdeka No. 1, Jakarta'),
('P002', 'Ani Yudhoyono', 'Jl. Sudirman No. 2, Bandung'),
('P003', 'Cici Paramida', 'Jl. Thamrin No. 3, Surabaya'),
('P004', 'Dedi Mulyadi', 'Jl. Gatot Subroto No. 4, Medan'),
('P005', 'Eka Kurniawan', 'Jl. Asia Afrika No. 5, Bali'),
('P006', 'Fajar Sadboy', 'Jl. Kebon Jeruk No. 6, Semarang'),
('P007', 'Gita Gutawa', 'Jl. Pahlawan No. 7, Makassar'),
('P008', 'Hendra Setiawan', 'Jl. Diponegoro No. 8, Yogyakarta'),
('P009', 'Iwan Fals', 'Jl. Malioboro No. 9, Solo'),
('P010', 'Joko Widodo', 'Jl. Veteran No. 10, Bogor'),
('P011', 'Kartini', 'Jl. Siliwangi No. 11, Cirebon'),
('P012', 'Lukman Hakim', 'Jl. Ahmad Yani No. 12, Malang'),
('P013', 'Maya Rumantir', 'Jl. Pemuda No. 13, Kediri'),
('P014', 'Nina Zatulini', 'Jl. Gajah Mada No. 14, Madiun'),
('P015', 'Oman Rachman', 'Jl. Hayam Wuruk No. 15, Tegal'),
('P016', 'Putri Marino', 'Jl. Supratman No. 16, Purwokerto'),
('P017', 'Qori Akbar', 'Jl. Sudirman No. 17, Jember'),
('P018', 'Rina Nose', 'Jl. Thamrin No. 18, Banyuwangi'),
('P019', 'Sule', 'Jl. Gatot Subroto No. 19, Tasikmalaya'),
('P020', 'Tukul Arwana', 'Jl. Asia Afrika No. 20, Garut');

-- Dummy Karyawan (10 orang)
INSERT INTO karyawan (kode_karyawan, nama_karyawan, jabatan) VALUES
('K001', 'Andi', 'Manager'),
('K002', 'Bety', 'Kasir'),
('K003', 'Candra', 'Koki'),
('K004', 'Dika', 'Pelayan'),
('K005', 'Evi', 'Pelayan'),
('K006', 'Feri', 'Koki'),
('K007', 'Gani', 'Pelayan'),
('K008', 'Hani', 'Kasir'),
('K009', 'Indra', 'Pelayan'),
('K010', 'Jaya', 'Cleaning Service');

-- Dummy Transaksi (15 transaksi)
INSERT INTO transaksi (no_faktur, tanggal, kode_pel, total) VALUES
('TRX-20231001-001', '2023-10-01', 'P001', 55000),
('TRX-20231002-002', '2023-10-02', 'P002', 80000),
('TRX-20231003-003', '2023-10-03', 'P003', 45000),
('TRX-20231004-004', '2023-10-04', 'P004', 110000),
('TRX-20231005-005', '2023-10-05', 'P005', 68000),
('TRX-20231006-006', '2023-10-06', 'P006', 40000),
('TRX-20231007-007', '2023-10-07', 'P007', 95000),
('TRX-20231008-008', '2023-10-08', 'P008', 35000),
('TRX-20231009-009', '2023-10-09', 'P009', 72000),
('TRX-20231010-010', '2023-10-10', 'P010', 53000),
('TRX-20231011-011', '2023-10-11', 'P011', 125000),
('TRX-20231012-012', '2023-10-12', 'P012', 45000),
('TRX-20231013-013', '2023-10-13', 'P013', 60000),
('TRX-20231014-014', '2023-10-14', 'P014', 85000),
('TRX-20231015-015', '2023-10-15', 'P015', 38000);

-- Dummy Detail Transaksi
INSERT INTO detail_transaksi (no_faktur, kode_menu, harga, jumlah, subtotal) VALUES
('TRX-20231001-001', 'M001', 25000, 2, 50000),
('TRX-20231001-001', 'M009', 5000, 1, 5000),
('TRX-20231002-002', 'M005', 45000, 1, 45000),
('TRX-20231002-002', 'M007', 35000, 1, 35000),
('TRX-20231003-003', 'M002', 28000, 1, 28000),
('TRX-20231003-003', 'M011', 15000, 1, 15000),
('TRX-20231003-003', 'M015', 4000, 1, 4000),
('TRX-20231004-004', 'M006', 55000, 2, 110000),
('TRX-20231005-005', 'M001', 25000, 2, 50000),
('TRX-20231005-005', 'M013', 10000, 1, 10000),
('TRX-20231005-005', 'M010', 8000, 1, 8000),
('TRX-20231006-006', 'M004', 22000, 1, 22000),
('TRX-20231006-006', 'M020', 18000, 1, 18000),
('TRX-20231007-007', 'M003', 30000, 2, 60000),
('TRX-20231007-007', 'M012', 15000, 1, 15000),
('TRX-20231007-007', 'M016', 15000, 1, 15000),
('TRX-20231007-007', 'M009', 5000, 1, 5000),
('TRX-20231008-008', 'M008', 18000, 1, 18000),
('TRX-20231008-008', 'M014', 12000, 1, 12000),
('TRX-20231008-008', 'M009', 5000, 1, 5000),
('TRX-20231009-009', 'M002', 28000, 2, 56000),
('TRX-20231009-009', 'M010', 8000, 2, 16000),
('TRX-20231010-010', 'M007', 35000, 1, 35000),
('TRX-20231010-010', 'M017', 12000, 1, 12000),
('TRX-20231010-010', 'M009', 5000, 1, 5000),
('TRX-20231011-011', 'M005', 45000, 2, 90000),
('TRX-20231011-011', 'M001', 25000, 1, 25000),
('TRX-20231011-011', 'M013', 10000, 1, 10000),
('TRX-20231012-012', 'M008', 18000, 2, 36000),
('TRX-20231012-012', 'M010', 8000, 1, 8000),
('TRX-20231013-013', 'M003', 30000, 1, 30000),
('TRX-20231013-013', 'M011', 15000, 2, 30000),
('TRX-20231014-014', 'M006', 55000, 1, 55000),
('TRX-20231014-014', 'M016', 15000, 2, 30000),
('TRX-20231015-015', 'M004', 22000, 1, 22000),
('TRX-20231015-015', 'M018', 10000, 1, 10000),
('TRX-20231015-015', 'M009', 5000, 1, 5000);
