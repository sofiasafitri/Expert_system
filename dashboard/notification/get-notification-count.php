<?php
// Menyertakan konfigurasi database
include('../../config/config.php');

session_start();

// Cek login dan role
isAdmin($connection);

header('Content-Type: application/json');

// Query untuk mendapatkan jumlah notifikasi
$query = "SELECT COUNT(*) AS result_count FROM notifications WHERE status = 'Belum Dilihat'";
$result = $connection->query($query);

if ($result) {
    $data = $result->fetch_assoc();
    echo json_encode(['result_count' => (int) $data['result_count']]); // Pastikan tipe data integer
} else {
    echo json_encode(['error' => 'Terjadi kesalahan', 'result_count' => 0]);
}
