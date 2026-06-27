<?php
// ============================================
// File: index.php
// Beranda & Daftar Data Inventaris Barang
// ============================================
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

// Menyertakan file koneksi database (require)
require 'koneksi.php';

// Variabel untuk pesan notifikasi
$pesan = '';
$tipe_pesan = '';

// Percabangan: Cek apakah ada parameter pesan dari URL (metode GET)
if (isset($_GET['pesan'])) {
    $kode_pesan = $_GET['pesan'];
    
    if ($kode_pesan == 'tambah_sukses') {
        $pesan = 'Data barang berhasil ditambahkan!';
        $tipe_pesan = 'success';
    } elseif ($kode_pesan == 'edit_sukses') {
        $pesan = 'Data barang berhasil diperbarui!';
        $tipe_pesan = 'success';
    } elseif ($kode_pesan == 'hapus_sukses') {
        $pesan = 'Data barang berhasil dihapus!';
        $tipe_pesan = 'success';
    } elseif ($kode_pesan == 'hapus_gagal') {
        $pesan = 'Gagal menghapus data barang!';
        $tipe_pesan = 'danger';
    } else {
        $pesan = '';
        $tipe_pesan = '';
    }
}

// Konfigurasi Pagination (10 data per halaman)
$limit = 10;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
if ($halaman < 1) { $halaman = 1; }
$offset = ($halaman - 1) * $limit;

// Fitur Pencarian (metode GET)
$keyword = '';
$where_clause = "";
if (isset($_GET['cari']) && trim($_GET['cari']) !== '') {
    $keyword = sanitasi($koneksi, $_GET['cari']);
    $where_clause = "WHERE kode_barang LIKE '%$keyword%' OR 
                           nama_barang LIKE '%$keyword%' OR 
                           kategori LIKE '%$keyword%' OR 
                           lokasi LIKE '%$keyword%'";
}

// Hitung total data sesuai filter pencarian untuk pagination
$query_count = "SELECT COUNT(*) as total FROM barang $where_clause";
$result_count = mysqli_query($koneksi, $query_count);
$row_count = mysqli_fetch_assoc($result_count);
$total_data = $row_count['total'];
$total_halaman = ceil($total_data / $limit);

// Query utama dengan LIMIT dan OFFSET
$query = "SELECT * FROM barang $where_clause ORDER BY id DESC LIMIT $offset, $limit";
$result = mysqli_query($koneksi, $query);

// Hitung total barang dan total nilai inventaris keseluruhan
$query_total = "SELECT COUNT(*) as total_item, SUM(jumlah) as total_stok, SUM(jumlah * harga) as total_nilai FROM barang";
$result_total = mysqli_query($koneksi, $query_total);
$data_total = mysqli_fetch_assoc($result_total);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Inventaris Barang - Aplikasi CRUD untuk manajemen inventaris gudang">
    <title>Sistem Inventaris Barang | Beranda</title>
    <!-- Bootstrap 5 CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- CSS Eksternal -->
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
                        <a class="nav-link active" href="index.php"><i class="bi bi-house-door me-1"></i> Beranda</a>
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

    <!-- ============ HERO SECTION ============ -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content text-center">
                <h1 class="hero-title">
                    <i class="bi bi-box-seam-fill"></i>
                    Sistem Inventaris Barang
                </h1>
                <p class="hero-subtitle">Kelola data inventaris gudang Anda dengan mudah, cepat, dan terorganisir.</p>
            </div>

            <!-- Statistik Ringkasan -->
            <div class="row g-4 mt-3 stats-row">
                <div class="col-md-4">
                    <div class="stat-card stat-card-items">
                        <div class="stat-icon"><i class="bi bi-box-seam"></i></div>
                        <div class="stat-info">
                            <span class="stat-number"><?= $data_total['total_item'] ?? 0 ?></span>
                            <span class="stat-label">Jenis Barang</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card stat-card-stock">
                        <div class="stat-icon"><i class="bi bi-archive"></i></div>
                        <div class="stat-info">
                            <span class="stat-number"><?= number_format($data_total['total_stok'] ?? 0, 0, ',', '.') ?></span>
                            <span class="stat-label">Total Stok</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card stat-card-value">
                        <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
                        <div class="stat-info">
                            <span class="stat-number"><?= formatRupiah($data_total['total_nilai'] ?? 0) ?></span>
                            <span class="stat-label">Nilai Inventaris</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ KONTEN UTAMA ============ -->
    <main class="container main-content">

        <!-- Notifikasi Pesan -->
        <?php if (!empty($pesan)) : ?>
            <div class="alert alert-<?= $tipe_pesan ?> alert-dismissible fade show alert-custom" role="alert">
                <i class="bi bi-<?= ($tipe_pesan == 'success') ? 'check-circle-fill' : 'exclamation-triangle-fill' ?> me-2"></i>
                <?= $pesan ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Toolbar: Pencarian & Tombol Tambah -->
        <div class="toolbar-section">
            <div class="row align-items-center g-3">
                <div class="col-md-7">
                    <form method="GET" action="index.php" class="search-form" id="formCari">
                        <div class="input-group">
                            <span class="input-group-text search-icon"><i class="bi bi-search"></i></span>
                            <input type="text" name="cari" class="form-control search-input" 
                                   placeholder="Cari berdasarkan kode, nama, kategori, atau lokasi..." 
                                   value="<?= htmlspecialchars($keyword) ?>" id="inputCari">
                            <button type="submit" class="btn btn-search">Cari</button>
                            <?php if (!empty($keyword)) : ?>
                                <a href="index.php" class="btn btn-reset" title="Reset"><i class="bi bi-x-lg"></i></a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
                <div class="col-md-5 text-md-end">
                    <a href="tambah.php" class="btn btn-add" id="btnTambahBarang">
                        <i class="bi bi-plus-lg me-2"></i>Tambah Barang Baru
                    </a>
                </div>
            </div>
        </div>

        <!-- Tabel Data Barang -->
        <div class="table-section">
            <div class="table-header">
                <h2 class="table-title">
                    <i class="bi bi-list-ul me-2"></i>Daftar Inventaris Barang
                </h2>
                <?php if (!empty($keyword)) : ?>
                    <span class="badge bg-info search-badge">
                        Hasil pencarian: "<?= htmlspecialchars($keyword) ?>" (<?= mysqli_num_rows($result) ?> data)
                    </span>
                <?php endif; ?>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-custom" id="tabelBarang">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">Kode</th>
                            <th width="18%">Nama Barang</th>
                            <th width="10%">Kategori</th>
                            <th width="7%">Jumlah</th>
                            <th width="13%">Harga Satuan</th>
                            <th width="12%">Lokasi</th>
                            <th width="12%">Tgl Masuk</th>
                            <th width="13%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Perulangan untuk menampilkan data barang
                        $no = $offset + 1;
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><span class="badge-kode"><?= htmlspecialchars($row['kode_barang']) ?></span></td>
                            <td class="fw-semibold"><?= htmlspecialchars($row['nama_barang']) ?></td>
                            <td>
                                <?php
                                // Percabangan untuk badge warna kategori
                                $badge_class = 'badge-kategori-lainnya';
                                if ($row['kategori'] == 'Elektronik') {
                                    $badge_class = 'badge-kategori-elektronik';
                                } elseif ($row['kategori'] == 'Furniture') {
                                    $badge_class = 'badge-kategori-furniture';
                                } elseif ($row['kategori'] == 'Alat Tulis') {
                                    $badge_class = 'badge-kategori-alattulis';
                                } elseif ($row['kategori'] == 'Perlengkapan') {
                                    $badge_class = 'badge-kategori-perlengkapan';
                                }
                                ?>
                                <span class="badge-kategori <?= $badge_class ?>"><?= htmlspecialchars($row['kategori']) ?></span>
                            </td>
                            <td class="text-center">
                                <?php
                                // Percabangan: Tampilkan warning jika stok rendah
                                if ($row['jumlah'] <= 5) {
                                    echo '<span class="stok-rendah" title="Stok Rendah!">' . $row['jumlah'] . ' <i class="bi bi-exclamation-triangle-fill"></i></span>';
                                } else {
                                    echo '<span class="stok-normal">' . $row['jumlah'] . '</span>';
                                }
                                ?>
                            </td>
                            <td class="text-end"><?= formatRupiah($row['harga']) ?></td>
                            <td><?= htmlspecialchars($row['lokasi'] ?? '-') ?></td>
                            <td><?= formatTanggal($row['tanggal_masuk']) ?></td>
                            <td class="text-center">
                                <div class="btn-group-actions">
                                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-action btn-edit" title="Edit" id="btnEdit<?= $row['id'] ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-action btn-delete" title="Hapus"
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus barang \'<?= addslashes(htmlspecialchars($row['nama_barang'])) ?>\'?')" 
                                       id="btnHapus<?= $row['id'] ?>">
                                        <i class="bi bi-trash3"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php
                            } // end while
                        } else {
                        ?>
                        <tr>
                            <td colspan="9" class="text-center empty-state">
                                <div class="empty-icon"><i class="bi bi-inbox"></i></div>
                                <p class="empty-text">
                                    <?php if (!empty($keyword)) : ?>
                                        Tidak ada data yang cocok dengan pencarian "<strong><?= htmlspecialchars($keyword) ?></strong>".
                                    <?php else : ?>
                                        Belum ada data barang. Silakan tambahkan barang baru.
                                    <?php endif; ?>
                                </p>
                                <a href="tambah.php" class="btn btn-add btn-sm mt-2">
                                    <i class="bi bi-plus-lg me-1"></i>Tambah Barang
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- ============ PAGINATION ============ -->
            <?php if ($total_halaman > 1) : ?>
            <div class="pagination-section d-flex justify-content-between align-items-center flex-wrap gap-3 p-3 border-top border-secondary border-opacity-10">
                <div class="pagination-info text-muted small">
                    Menampilkan <strong><?= $offset + 1 ?></strong> - <strong><?= min($offset + $limit, $total_data) ?></strong> dari total <strong><?= $total_data ?></strong> barang
                </div>
                <nav aria-label="Navigasi Halaman">
                    <ul class="pagination pagination-custom mb-0">
                        <!-- Tombol Sebelumnya -->
                        <li class="page-item <?= ($halaman <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?halaman=<?= $halaman - 1 ?><?= !empty($keyword) ? '&cari='.urlencode($keyword) : '' ?>">
                                <i class="bi bi-chevron-left me-1"></i>Prev
                            </a>
                        </li>

                        <!-- Angka Halaman -->
                        <?php
                        $start_page = max(1, $halaman - 2);
                        $end_page = min($total_halaman, $halaman + 2);
                        for ($i = $start_page; $i <= $end_page; $i++) :
                        ?>
                            <li class="page-item <?= ($halaman == $i) ? 'active' : '' ?>">
                                <a class="page-link" href="?halaman=<?= $i ?><?= !empty($keyword) ? '&cari='.urlencode($keyword) : '' ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <!-- Tombol Selanjutnya -->
                        <li class="page-item <?= ($halaman >= $total_halaman) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?halaman=<?= $halaman + 1 ?><?= !empty($keyword) ? '&cari='.urlencode($keyword) : '' ?>">
                                Next<i class="bi bi-chevron-right ms-1"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
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

    <!-- Bootstrap 5 JS via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
