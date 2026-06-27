-- ============================================
-- DATABASE: Sistem Inventaris Barang
-- Mata Kuliah: Pemrograman Web (UAS)
-- ============================================

-- Membuat Database
CREATE DATABASE IF NOT EXISTS db_inventaris
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE db_inventaris;

-- ============================================
-- Tabel: barang
-- Menyimpan data inventaris barang
-- ============================================
CREATE TABLE IF NOT EXISTS barang (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    kode_barang VARCHAR(20) NOT NULL UNIQUE,
    nama_barang VARCHAR(100) NOT NULL,
    kategori ENUM('Elektronik', 'Furniture', 'Alat Tulis', 'Perlengkapan', 'Lainnya') NOT NULL DEFAULT 'Lainnya',
    jumlah INT(11) NOT NULL DEFAULT 0,
    harga DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    lokasi VARCHAR(100) DEFAULT NULL,
    tanggal_masuk DATE NOT NULL,
    keterangan TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Data Awal (Dummy Data) - 50 Record
-- ============================================
INSERT INTO barang (kode_barang, nama_barang, kategori, jumlah, harga, lokasi, tanggal_masuk, keterangan) VALUES
('BRG-001', 'Laptop ASUS VivoBook 15', 'Elektronik', 10, 8500000.00, 'Gudang A - Rak 1', '2026-01-15', 'Laptop untuk kebutuhan kantor divisi IT'),
('BRG-002', 'Meja Kerja Minimalis Jati', 'Furniture', 25, 1250000.00, 'Gudang B - Rak 3', '2026-02-20', 'Meja kerja kayu jati ukuran 120x60 cm'),
('BRG-003', 'Printer Epson EcoTank L3210', 'Elektronik', 5, 2750000.00, 'Gudang A - Rak 2', '2026-03-10', 'Printer multifungsi ink tank system'),
('BRG-004', 'Pulpen Pilot G-2 0.5mm', 'Alat Tulis', 200, 15000.00, 'Gudang C - Rak 1', '2026-04-05', 'Pulpen gel 0.5mm warna hitam 1 boks'),
('BRG-005', 'Kursi Ergonomis Mesh Jaring', 'Furniture', 15, 3200000.00, 'Gudang B - Rak 5', '2026-05-12', 'Kursi kantor dengan sandaran adjustable'),
('BRG-006', 'Proyektor Epson EB-X51 XGA', 'Elektronik', 3, 7500000.00, 'Gudang A - Rak 4', '2026-05-28', 'Proyektor 3800 lumens untuk ruang meeting'),
('BRG-007', 'Whiteboard Magnetic 90x120', 'Perlengkapan', 8, 450000.00, 'Gudang C - Rak 2', '2026-06-01', 'Papan tulis magnetik dengan bingkai aluminium'),
('BRG-008', 'Monitor Dell 24 Inch IPS', 'Elektronik', 18, 2300000.00, 'Gudang A - Rak 1', '2026-01-20', 'Monitor FHD untuk staf desain dan pemrograman'),
('BRG-009', 'Kertas Hips A4 80 GSM', 'Alat Tulis', 150, 55000.00, 'Gudang C - Rak 3', '2026-02-11', 'Kertas print fotokopi per rim'),
('BRG-010', 'Lemari Arsip Besi 4 Pintu', 'Furniture', 6, 2800000.00, 'Gudang B - Rak 1', '2026-03-05', 'Lemari penyimpan dokumen rahasia HRD'),
('BRG-011', 'Kabel HDMI 2.0 Gold Plated 3M', 'Perlengkapan', 30, 85000.00, 'Gudang C - Rak 4', '2026-03-15', 'Kabel konektor proyektor ke laptop'),
('BRG-012', 'Mouse Wireless Logitech M170', 'Elektronik', 40, 120000.00, 'Gudang A - Rak 3', '2026-03-22', 'Mouse nirkabel baterai AA'),
('BRG-013', 'Keyboard Mekanikal Keychron C1', 'Elektronik', 12, 850000.00, 'Gudang A - Rak 3', '2026-04-01', 'Keyboard TKL Brown Switch'),
('BRG-014', 'Spidol Whiteboard Snowman Hitam', 'Alat Tulis', 120, 10000.00, 'Gudang C - Rak 1', '2026-04-10', 'Spidol papan tulis yang bisa dihapus'),
('BRG-015', 'Penghapus Whiteboard Magnetic', 'Alat Tulis', 45, 12000.00, 'Gudang C - Rak 1', '2026-04-12', 'Penghapus berkain pembersih halus'),
('BRG-016', 'AC Daikin 1.5 PK Inverter', 'Elektronik', 4, 6500000.00, 'Gudang A - Rak 5', '2026-04-18', 'AC untuk ruangan rapat utama'),
('BRG-017', 'Sofa Tamu Eksekutif 3 Seater', 'Furniture', 2, 5500000.00, 'Gudang B - Rak 2', '2026-04-25', 'Sofa kulit hitam untuk lobi depan'),
('BRG-018', 'Rak Besi Gudang 5 Susun', 'Perlengkapan', 10, 1100000.00, 'Gudang D - Rak 1', '2026-05-01', 'Rak beban berat kapasitas 200kg/susun'),
('BRG-019', 'Stapler Kenko HD-10D', 'Alat Tulis', 60, 25000.00, 'Gudang C - Rak 2', '2026-05-03', 'Stapler kecil untuk dokumen harian'),
('BRG-020', 'Isi Stapler No. 10 Kenko', 'Alat Tulis', 250, 3500.00, 'Gudang C - Rak 2', '2026-05-05', 'Isi staples kemasan kotak kecil'),
('BRG-021', 'Router TP-Link Archer AX50', 'Elektronik', 7, 1450000.00, 'Gudang A - Rak 2', '2026-05-08', 'Router Wi-Fi 6 Dual Band Gigabit'),
('BRG-022', 'Switch Hub D-Link 24 Port', 'Elektronik', 3, 1850000.00, 'Gudang A - Rak 2', '2026-05-10', 'Switch jaringan untuk server room'),
('BRG-023', 'kabel LAN Cat6 Belden 305M', 'Perlengkapan', 2, 2100000.00, 'Gudang D - Rak 2', '2026-05-14', 'Roll kabel UTP untuk instalasi jaringan'),
('BRG-024', 'Tangga Aluminium 2 Meter', 'Perlengkapan', 3, 650000.00, 'Gudang D - Rak 3', '2026-05-15', 'Tangga lipat untuk perawatan lampu & AC'),
('BRG-025', 'Lampu LED Philips 13 Watt', 'Perlengkapan', 80, 45000.00, 'Gudang D - Rak 4', '2026-05-19', 'Bohlam putih terang hemat energi'),
('BRG-026', 'Meja Meeting Oval 10 Orang', 'Furniture', 1, 8500000.00, 'Gudang B - Rak 4', '2026-05-20', 'Meja kayu solid finishing melamine'),
('BRG-027', 'Dispenser Modena Hot & Cold', 'Elektronik', 4, 2400000.00, 'Gudang A - Rak 4', '2026-05-22', 'Dispenser galon bawah stainless steel'),
('BRG-028', 'Tempat Sampah Injak 20 Liter', 'Perlengkapan', 25, 150000.00, 'Gudang D - Rak 5', '2026-05-24', 'Tempat sampah plastik tertutup'),
('BRG-029', 'Gunting Kantor Kenko SC-838', 'Alat Tulis', 35, 18000.00, 'Gudang C - Rak 3', '2026-05-25', 'Gunting stainless steel anti karat'),
('BRG-030', 'Cutter Kenko L-500 Besar', 'Alat Tulis', 40, 15000.00, 'Gudang C - Rak 3', '2026-05-26', 'Pisau cutter untuk unboxing paket'),
('BRG-031', 'UPS APC Back-UPS 1200VA', 'Elektronik', 8, 2100000.00, 'Gudang A - Rak 1', '2026-05-27', 'UPS cadangan listrik komputer PC'),
('BRG-032', 'Webcam Logitech C920 HD Pro', 'Elektronik', 14, 1350000.00, 'Gudang A - Rak 3', '2026-05-29', 'Webcam 1080p untuk zoom meeting'),
('BRG-033', 'Headset Jabra Evolve 20 MS', 'Elektronik', 20, 750000.00, 'Gudang A - Rak 3', '2026-05-30', 'Headset call center noise cancelling'),
('BRG-034', 'Paper Shredder Krisbow 10 Lembar', 'Perlengkapan', 2, 1950000.00, 'Gudang D - Rak 1', '2026-06-02', 'Mesin penghancur kertas cross-cut'),
('BRG-035', 'Binder Clip Kenko No. 107', 'Alat Tulis', 100, 8500.00, 'Gudang C - Rak 4', '2026-06-03', 'Jepit kertas hitam ukuran sedang'),
('BRG-036', 'Map Folder Plastik Kancing A4', 'Alat Tulis', 200, 4500.00, 'Gudang C - Rak 5', '2026-06-04', 'Map penyimpan arsip transparan'),
('BRG-037', 'Buku Agenda Kerja Cover Kulit', 'Alat Tulis', 50, 45000.00, 'Gudang C - Rak 5', '2026-06-05', 'Buku catatan rapat tahunan staf'),
('BRG-038', 'Kursi Lipat Chitose Hallo', 'Furniture', 30, 350000.00, 'Gudang B - Rak 5', '2026-06-06', 'Kursi cadangan acara gathering kantor'),
('BRG-039', 'Podium Pidato Kayu Minimalis', 'Furniture', 1, 3500000.00, 'Gudang B - Rak 2', '2026-06-07', 'Podium untuk ruang auditorium'),
('BRG-040', 'Microphone Wireless Shure BLX288', 'Elektronik', 2, 6800000.00, 'Gudang A - Rak 4', '2026-06-08', 'Mic nirkabel ganda suara jernih'),
('BRG-041', 'Sound System Portable Baretone 15"', 'Elektronik', 2, 4500000.00, 'Gudang A - Rak 5', '2026-06-09', 'Speaker aktif dengan aki rechargeable'),
('BRG-042', 'Kalkulator Citizen CT-8614', 'Alat Tulis', 15, 145000.00, 'Gudang C - Rak 2', '2026-06-10', 'Kalkulator meja 14 digit'),
('BRG-043', 'Lakban Coklat Daimaru 2 Inch', 'Perlengkapan', 80, 14000.00, 'Gudang D - Rak 3', '2026-06-11', 'Lakban pengemas kardus barang'),
('BRG-044', 'Kotak P3K Dinding Lengkap', 'Lainnya', 5, 350000.00, 'Gudang D - Rak 4', '2026-06-12', 'Obat-obatan pertolongan pertama darurat'),
('BRG-045', 'Alat Pemadam Api APAR 3 Kg', 'Lainnya', 12, 450000.00, 'Gudang D - Rak 5', '2026-06-13', 'Tabung pemadam kebakaran Dry Chemical Powder'),
('BRG-046', 'Masker Medis Sensi 3 Ply (Box)', 'Lainnya', 50, 35000.00, 'Gudang D - Rak 4', '2026-06-14', 'Masker sekali pakai per boks 50 pcs'),
('BRG-047', 'Hand Sanitizer Nuvo 500ml', 'Lainnya', 30, 40000.00, 'Gudang D - Rak 4', '2026-06-15', 'Pembersih tangan antiseptik cair pompa'),
('BRG-048', 'Jam Dinding Seiko Sweep Second', 'Perlengkapan', 10, 380000.00, 'Gudang D - Rak 2', '2026-06-16', 'Jam dinding jarum senyap tanpa detak'),
('BRG-049', 'Kemoceng Bulu Domba Asli', 'Perlengkapan', 15, 65000.00, 'Gudang D - Rak 3', '2026-06-17', 'Alat pembersih debu perabot kantor'),
('BRG-050', 'Sapu Lantai Nagata Senar Halus', 'Perlengkapan', 20, 35000.00, 'Gudang D - Rak 3', '2026-06-18', 'Sapu plastik awet tahan rontok');
