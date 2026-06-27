<?php
// ============================================
// File: hapus.php
// Proses Hapus Data Barang (DELETE)
// ============================================

// Menyertakan file koneksi database (require)
require 'koneksi.php';

// Percabangan: Cek apakah parameter ID ada di URL (metode GET)
if (isset($_GET['id'])) {
    // Ambil dan validasi ID
    $id = (int) $_GET['id'];
    
    // Percabangan: Pastikan ID valid (lebih dari 0)
    if ($id > 0) {
        // Cek apakah data dengan ID tersebut ada
        $cek_query = "SELECT id, nama_barang FROM barang WHERE id = $id";
        $cek_result = mysqli_query($koneksi, $cek_query);
        
        if (mysqli_num_rows($cek_result) > 0) {
            // Query DELETE
            $query = "DELETE FROM barang WHERE id = $id";
            
            if (mysqli_query($koneksi, $query)) {
                // Berhasil hapus - redirect dengan pesan sukses
                header("Location: index.php?pesan=hapus_sukses");
                exit;
            } else {
                // Gagal hapus - redirect dengan pesan gagal
                header("Location: index.php?pesan=hapus_gagal");
                exit;
            }
        } else {
            // Data tidak ditemukan
            header("Location: index.php");
            exit;
        }
    } else {
        // ID tidak valid
        header("Location: index.php");
        exit;
    }
} else {
    // Tidak ada parameter ID
    header("Location: index.php");
    exit;
}
?>
