<?php
// ============================================
// File: edit.php
// Form Edit Data Barang (UPDATE)
// ============================================
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

// Menyertakan file koneksi database (include)
include 'koneksi.php';

// Variabel
$error = '';
$barang = null;

// Ambil ID dari URL (metode GET)
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    
    // Query untuk mengambil data barang berdasarkan ID
    $query = "SELECT * FROM barang WHERE id = $id";
    $result = mysqli_query($koneksi, $query);
    
    // Percabangan: Cek apakah data ditemukan
    if (mysqli_num_rows($result) > 0) {
        $barang = mysqli_fetch_assoc($result);
    } else {
        // Redirect jika data tidak ditemukan
        header("Location: index.php");
        exit;
    }
} else {
    // Redirect jika tidak ada parameter ID
    header("Location: index.php");
    exit;
}

// Proses form saat metode POST (update data)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil dan sanitasi data dari form
    $id           = (int) $_POST['id'];
    $kode_barang  = sanitasi($koneksi, $_POST['kode_barang']);
    $nama_barang  = sanitasi($koneksi, $_POST['nama_barang']);
    $kategori     = sanitasi($koneksi, $_POST['kategori']);
    $jumlah       = (int) $_POST['jumlah'];
    $harga        = (float) $_POST['harga'];
    $lokasi       = sanitasi($koneksi, $_POST['lokasi']);
    $tanggal_masuk = sanitasi($koneksi, $_POST['tanggal_masuk']);
    $keterangan   = sanitasi($koneksi, $_POST['keterangan']);

    // Validasi input
    if (empty($kode_barang) || empty($nama_barang) || empty($kategori) || empty($tanggal_masuk)) {
        $error = 'Kode barang, nama barang, kategori, dan tanggal masuk wajib diisi!';
    } elseif ($jumlah < 0) {
        $error = 'Jumlah barang tidak boleh negatif!';
    } elseif ($harga < 0) {
        $error = 'Harga barang tidak boleh negatif!';
    } else {
        // Cek duplikat kode barang (kecuali barang yang sedang diedit)
        $cek_query = "SELECT id FROM barang WHERE kode_barang = '$kode_barang' AND id != $id";
        $cek_result = mysqli_query($koneksi, $cek_query);

        if (mysqli_num_rows($cek_result) > 0) {
            $error = 'Kode barang "' . htmlspecialchars($kode_barang) . '" sudah digunakan oleh barang lain!';
        } else {
            // Query UPDATE
            $query = "UPDATE barang SET 
                        kode_barang = '$kode_barang',
                        nama_barang = '$nama_barang',
                        kategori = '$kategori',
                        jumlah = $jumlah,
                        harga = $harga,
                        lokasi = '$lokasi',
                        tanggal_masuk = '$tanggal_masuk',
                        keterangan = '$keterangan'
                      WHERE id = $id";

            if (mysqli_query($koneksi, $query)) {
                header("Location: index.php?pesan=edit_sukses");
                exit;
            } else {
                $error = 'Gagal memperbarui data: ' . mysqli_error($koneksi);
            }
        }
    }

    // Update variabel $barang dengan data dari POST agar form tetap terisi
    $barang['kode_barang']  = $kode_barang;
    $barang['nama_barang']  = $nama_barang;
    $barang['kategori']     = $kategori;
    $barang['jumlah']       = $jumlah;
    $barang['harga']        = $harga;
    $barang['lokasi']       = $lokasi;
    $barang['tanggal_masuk'] = $tanggal_masuk;
    $barang['keterangan']   = $keterangan;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Edit Data Barang - Sistem Inventaris Barang">
    <title>Edit Barang | InvenTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- ============ NAVBAR ============ -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <i class="bi bi-box-seam-fill me-2 brand-icon"></i>
                <span class="brand-text">InvenTrack</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="bi bi-house-door me-1"></i> Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tambah.php"><i class="bi bi-plus-circle me-1"></i> Tambah Barang</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="nav-link btn-logout" href="logout.php" onclick="return confirm('Yakin ingin logout?')">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ============ KONTEN UTAMA ============ -->
    <main class="container main-content">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb custom-breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door"></i> Beranda</a></li>
                        <li class="breadcrumb-item active">Edit Barang</li>
                    </ol>
                </nav>

                <!-- Card Form -->
                <div class="form-card">
                    <div class="form-card-header form-card-header-edit">
                        <h1 class="form-title">
                            <i class="bi bi-pencil-square me-2"></i>Edit Barang
                        </h1>
                        <p class="form-subtitle">Perbarui informasi barang <strong>"<?= htmlspecialchars($barang['nama_barang']) ?>"</strong></p>
                    </div>
                    <div class="form-card-body">

                        <!-- Pesan Error -->
                        <?php if (!empty($error)) : ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <?= $error ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Form Edit (metode POST) -->
                        <form method="POST" action="edit.php?id=<?= $barang['id'] ?>" id="formEdit">
                            <input type="hidden" name="id" value="<?= $barang['id'] ?>">

                            <div class="row g-3">
                                <!-- Kode Barang -->
                                <div class="col-md-6">
                                    <label for="kode_barang" class="form-label">
                                        <i class="bi bi-upc-scan me-1"></i>Kode Barang <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control custom-input" id="kode_barang" name="kode_barang" 
                                           required maxlength="20" value="<?= htmlspecialchars($barang['kode_barang']) ?>">
                                </div>

                                <!-- Nama Barang -->
                                <div class="col-md-6">
                                    <label for="nama_barang" class="form-label">
                                        <i class="bi bi-tag me-1"></i>Nama Barang <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control custom-input" id="nama_barang" name="nama_barang" 
                                           required maxlength="100" value="<?= htmlspecialchars($barang['nama_barang']) ?>">
                                </div>

                                <!-- Kategori -->
                                <div class="col-md-6">
                                    <label for="kategori" class="form-label">
                                        <i class="bi bi-grid me-1"></i>Kategori <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select custom-input" id="kategori" name="kategori" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <?php
                                        $kategori_list = ['Elektronik', 'Furniture', 'Alat Tulis', 'Perlengkapan', 'Lainnya'];
                                        for ($i = 0; $i < count($kategori_list); $i++) {
                                            $selected = ($barang['kategori'] == $kategori_list[$i]) ? 'selected' : '';
                                            echo '<option value="' . $kategori_list[$i] . '" ' . $selected . '>' . $kategori_list[$i] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <!-- Jumlah -->
                                <div class="col-md-6">
                                    <label for="jumlah" class="form-label">
                                        <i class="bi bi-123 me-1"></i>Jumlah <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control custom-input" id="jumlah" name="jumlah" 
                                           required min="0" value="<?= $barang['jumlah'] ?>">
                                </div>

                                <!-- Harga -->
                                <div class="col-md-6">
                                    <label for="harga" class="form-label">
                                        <i class="bi bi-currency-exchange me-1"></i>Harga Satuan (Rp) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control custom-input" id="harga" name="harga" 
                                           required min="0" step="0.01" value="<?= $barang['harga'] ?>">
                                </div>

                                <!-- Lokasi -->
                                <div class="col-md-6">
                                    <label for="lokasi" class="form-label">
                                        <i class="bi bi-geo-alt me-1"></i>Lokasi Penyimpanan
                                    </label>
                                    <input type="text" class="form-control custom-input" id="lokasi" name="lokasi" 
                                           maxlength="100" value="<?= htmlspecialchars($barang['lokasi'] ?? '') ?>">
                                </div>

                                <!-- Tanggal Masuk -->
                                <div class="col-md-6">
                                    <label for="tanggal_masuk" class="form-label">
                                        <i class="bi bi-calendar-event me-1"></i>Tanggal Masuk <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control custom-input" id="tanggal_masuk" name="tanggal_masuk" 
                                           required value="<?= $barang['tanggal_masuk'] ?>">
                                </div>

                                <!-- Keterangan -->
                                <div class="col-md-6">
                                    <label for="keterangan" class="form-label">
                                        <i class="bi bi-chat-left-text me-1"></i>Keterangan
                                    </label>
                                    <textarea class="form-control custom-input" id="keterangan" name="keterangan" 
                                              rows="1"><?= htmlspecialchars($barang['keterangan'] ?? '') ?></textarea>
                                </div>

                                <!-- Tombol Aksi -->
                                <div class="col-12 mt-4">
                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-submit btn-update" id="btnUpdate">
                                            <i class="bi bi-check-lg me-2"></i>Perbarui Data
                                        </button>
                                        <a href="index.php" class="btn btn-cancel">
                                            <i class="bi bi-arrow-left me-2"></i>Kembali
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- ============ FOOTER ============ -->
    <footer class="footer-section">
        <div class="container text-center">
            <p class="footer-text">
                <i class="bi bi-box-seam-fill me-1"></i>
                <strong>InvenTrack</strong> &mdash; Sistem Inventaris Barang &copy; <?= date('Y') ?>
            </p>
            <p class="footer-credit">
                Dibuat untuk UAS Pemrograman Web | Dikembangkan dengan bantuan <span class="ai-badge">🤖 GenAI</span>
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
