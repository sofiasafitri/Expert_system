<?php
// Menyertakan konfigurasi
include('../../config/config.php');

// Memulai sesi
session_start();

// Cek login dan role
isAdmin($connection);

// Bersihkan notifikasi
if (isset($_GET['clear-notifications']) && $_GET['clear-notifications'] === 'true') {
    // Cek data yang statusnya 'Belum Dilihat'
    $check_data = $connection->query("SELECT status FROM notifications WHERE status = 'Belum Dilihat'");

    if (mysqli_num_rows($check_data) > 0) {
        // Update status menjadi 'Dilihat'
        $query = $connection->query("UPDATE notifications SET status = 'Dilihat' WHERE status = 'Belum Dilihat'");

        if ($query) {
            $_SESSION['popup-success'] = "Notifikasi berhasil dibersihkan.";
            echo "<script>window.location.href = '" . base_url('dashboard/notification/show.php') . "';</script>";
        } else {
            $_SESSION['popup-error'] = "Notifikasi gagal dibersihkan.";
            echo "<script>window.location.href = '" . base_url('dashboard/notification/show.php') . "';</script>";
        }
        exit();
    } else {
        $_SESSION['popup-success'] = "Tidak ada notifikasi yang perlu dibersihkan.";
        echo "<script>window.location.href = '" . base_url('dashboard/notification/show.php') . "';</script>";
    }
    exit();
}
