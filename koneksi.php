<?php
// ============================================
// File: koneksi.php
// Koneksi ke Database MySQL
// ============================================

$host   = "localhost";
$user   = "root";
$pass   = "";
$dbname = "db_inventaris";

// Membuat koneksi ke MySQL
$koneksi = mysqli_connect($host, $user, $pass, $dbname);

// Cek koneksi berhasil atau gagal (percabangan if/else)
if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
} else {
    // Set charset ke utf8mb4 untuk mendukung karakter khusus
    mysqli_set_charset($koneksi, "utf8mb4");
}

// ============================================
// FUNGSI BUATAN SENDIRI (Custom Functions)
// ============================================

/**
 * Fungsi 1: Format mata uang Rupiah
 * Mengubah angka menjadi format Rupiah (Rp 1.000.000)
 * 
 * @param float $angka Angka yang akan diformat
 * @return string Angka dalam format Rupiah
 */
function formatRupiah($angka) {
    if ($angka === null || $angka === '') {
        return 'Rp 0';
    }
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

/**
 * Fungsi 2: Format tanggal ke bahasa Indonesia
 * Mengubah format tanggal Y-m-d menjadi format Indonesia (dd Bulan YYYY)
 * 
 * @param string $tanggal Tanggal dalam format Y-m-d
 * @return string Tanggal dalam format Indonesia
 */
function formatTanggal($tanggal) {
    if (empty($tanggal)) {
        return '-';
    }
    
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    
    $pecah = explode('-', $tanggal);
    $tgl   = (int)$pecah[2];
    $bln   = (int)$pecah[1];
    $thn   = $pecah[0];
    
    return $tgl . ' ' . $bulan[$bln] . ' ' . $thn;
}

/**
 * Fungsi 3: Sanitasi input untuk mencegah SQL Injection & XSS
 * 
 * @param mysqli $koneksi Objek koneksi database
 * @param string $data Input yang akan disanitasi
 * @return string Input yang sudah disanitasi
 */
function sanitasi($koneksi, $data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    $data = mysqli_real_escape_string($koneksi, $data);
    return $data;
}
?>
