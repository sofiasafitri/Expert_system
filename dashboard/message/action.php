<?php
// Menyertakan konfigurasi
include('../../config/config.php');

// Memulai sesi
session_start();

// Cek login dan role
isAdmin($connection);

// Delete
if (isset($_GET['delete-message'])) {
    $message_id = htmlspecialchars($_GET['delete-message']);

    // Cek data
    $check_data = $connection->query("SELECT id FROM messages WHERE id = '$message_id'");

    if (mysqli_num_rows($check_data) > 0) {
        // Delete
        $query = $connection->query("DELETE FROM messages WHERE id = '$message_id'");

        if ($query) {
            $_SESSION['success'] = "Pesan berhasil dihapus.";
            echo "<script>window.location.href = '" . base_url('dashboard/message/show.php') . "';</script>";
        } else {
            $_SESSION['error'] = "Pesan gagal dihapus.";
            echo "<script>window.location.href = '" . base_url('dashboard/message/show.php') . "';</script>";
        }
        exit();
    } else {
        $_SESSION['warning'] = "Data tidak ditemukan.";
        echo "<script>window.location.href = '" . base_url('dashboard/message/show.php') . "';</script>";
    }
    exit();
}

// Update
if (isset($_GET['update-status-message'])) {
    $message_id = htmlspecialchars($_GET['update-status-message']);

    // Cek data
    $check_data = $connection->query("SELECT status FROM messages WHERE id = '$message_id'");

    if (mysqli_num_rows($check_data) > 0) {
        $query = $connection->query("UPDATE messages SET status='Dibaca' WHERE id = '$message_id'");

        if ($query) {
            // Simpan untuk notifikasi terkait pesan ke dashboard
            $update_message_notification = "UPDATE notifications SET status = 'Dilihat' WHERE message_id = '$message_id'";
            $connection->query($update_message_notification);

            $_SESSION['success'] = "Pesan sudah dibaca.";
            echo "<script>window.location.href = '" . base_url('dashboard/message/show.php') . "';</script>";
            exit();
        } else {
            $_SESSION['error'] = 'Terjadi kesalahan saat mendaftar, silakan coba lagi.';
            echo "<script>window.location.href = '" . base_url('dashboard/message/show.php') . "';</script>";
            exit();
        }
    } else {
        $_SESSION['warning'] = "Data tidak ditemukan.";
        echo "<script>window.location.href = '" . base_url('dashboard/message/show.php') . "';</script>";
        exit();
    }
}
