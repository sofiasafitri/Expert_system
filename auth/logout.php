<?php
// Menyertakan konfigurasi
include('../config/config.php');

// Memulai sesi
session_start();

// Hapus semua variabel sesi
$_SESSION = [];

// Hapus sesi
session_destroy();

// Mulai ulang sesi baru untuk menyimpan pesan logout
session_start();
$_SESSION['success'] = 'Anda berhasil keluar.';

// Redirect ke beranda
echo "<script>window.location.href = '" . base_url('auth/login.php') . "';</script>";
exit();
