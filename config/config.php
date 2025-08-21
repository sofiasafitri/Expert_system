<?php
// Mengatur zona waktu ke Asia/Makassar
date_default_timezone_set('Asia/Makassar');

/**
 * Konfigurasi Base URL
 * Contoh Base URL: 'http://localhost/project-name/'
 * Pastikan untuk mengganti URL pada file .htaccess agar sesuai dengan Base URL yang sama.
 */
define('BASE_URL', 'http://localhost/expert-system/');

// Menentukan environment: development atau production
define('ENVIRONMENT', 'development');

// Penanganan Error
if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL); // Menampilkan semua jenis error
    ini_set('display_errors', 1); // Tampilkan error di browser
} else {
    error_reporting(0); // Tidak menampilkan error
    ini_set('display_errors', 0); // Tidak menampilkan error di browser
    ini_set('log_errors', 1); // Menyalakan log error
    ini_set('error_log', __DIR__ . '/../logs/error.log'); // Simpan error ke file log
}

// Konfigurasi Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'expert_system');

// Membuat koneksi ke database
$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Mengecek apakah koneksi gagal
if ($connection->connect_error) {
    die("Koneksi ke basis data gagal: " . $connection->connect_error); // Menampilkan pesan error jika gagal
}

// Menyertakan functions
include('functions.php');

// Menyertakan helpers
include('helpers.php');
