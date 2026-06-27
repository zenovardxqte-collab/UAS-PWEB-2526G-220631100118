<?php
// ============================================
// File: logout.php
// Proses Logout (Hapus Session)
// ============================================
session_start();

// Hapus semua data session
$_SESSION = [];

// Hancurkan session
session_destroy();

// Redirect ke halaman login
header("Location: login.php");
exit;
?>
