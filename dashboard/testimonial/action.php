<?php
// Menyertakan konfigurasi
include('../../config/config.php');

// Memulai sesi
session_start();

// Cek login dan role
isAdmin($connection);

// Delete
if (isset($_GET['delete-testimonial'])) {
    $testimonial_id = htmlspecialchars($_GET['delete-testimonial']);

    // Cek data
    $check_data = $connection->query("SELECT id FROM testimonials WHERE id = '$testimonial_id'");

    if (mysqli_num_rows($check_data) > 0) {
        // Delete
        $query = $connection->query("DELETE FROM testimonials WHERE id = '$testimonial_id'");

        if ($query) {
            $_SESSION['success'] = "Ulasan berhasil dihapus.";
            echo "<script>window.location.href = '" . base_url('dashboard/testimonial/show.php') . "';</script>";
        } else {
            $_SESSION['error'] = "Ulasan gagal dihapus.";
            echo "<script>window.location.href = '" . base_url('dashboard/testimonial/show.php') . "';</script>";
        }
        exit();
    } else {
        $_SESSION['warning'] = "Data tidak ditemukan.";
        echo "<script>window.location.href = '" . base_url('dashboard/testimonial/show.php') . "';</script>";
    }
    exit();
}
