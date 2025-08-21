<?php
// Helpers for layout
$sections = [];

// Fungsi untuk memulai section dan menangani output buffer
function startSection($name)
{
    global $sections;
    ob_start(); // Memulai output buffering
    $sections[$name] = ''; // Inisialisasi array sections
}

// Fungsi untuk mengakhiri section dan menyimpan hasil buffer
function endSection($name)
{
    global $sections;
    $sections[$name] = ob_get_clean(); // Menyimpan hasil buffer ke array sections
}

// Fungsi untuk menampilkan isi dari section
function yieldSection($name)
{
    global $sections;
    // Jika section ditemukan, tampilkan; jika tidak, kosongkan
    echo isset($sections[$name]) ? $sections[$name] : '';
}

// Helpers for auth
// Fungsi untuk mendapatkan data pengguna berdasarkan email
function getUserByEmail($connection, $email)
{
    $query = $connection->query("SELECT role, status FROM users WHERE email = '$email'");

    // Jika tidak ada data pengguna dengan email yang diberikan
    if ($query->num_rows === 0) {
        return null; // Tidak ada pengguna, kembalikan null
    }

    return $query->fetch_assoc(); // Mengembalikan data pengguna yang ditemukan
}

// Fungsi untuk mengecek login
function checkLogin($connection)
{
    // Pastikan email ada di session, jika tidak redirect ke halaman login
    if (!isset($_SESSION['email'])) {
        $_SESSION['warning'] = 'Silakan masuk dengan akun Anda.'; // Pesan peringatan
        echo "<script>window.location.href = '" . base_url('auth/login.php') . "';</script>"; // Arahkan ke login
        exit(); // Menghentikan eksekusi kode lebih lanjut
    }

    // Ambil email dari session
    $email = $_SESSION['email'];
    $user = getUserByEmail($connection, $email); // Ambil data pengguna berdasarkan email

    // Jika pengguna tidak ditemukan dalam database
    if (!$user) {
        $_SESSION['warning'] = 'Akun tidak ditemukan. Silakan masuk kembali.'; // Pesan peringatan
        session_destroy(); // Hapus session
        echo "<script>window.location.href = '" . base_url('auth/login.php') . "';</script>"; // Arahkan ke login
        exit();
    }

    // Jika akun tidak aktif
    if ($user['status'] !== 'Aktif') {
        $_SESSION['warning'] = 'Akun Anda tidak aktif. Hubungi administrator.'; // Pesan peringatan
        session_destroy(); // Hapus session
        echo "<script>window.location.href = '" . base_url('auth/login.php') . "';</script>"; // Arahkan ke login
        exit();
    }

    return $user; // Kembalikan data pengguna yang valid
}

// Fungsi untuk memeriksa apakah pengguna adalah admin
function isAdmin($connection)
{
    // Periksa login terlebih dahulu
    $user = checkLogin($connection);

    // Cek apakah pengguna adalah admin
    if ($user['role'] !== 'Admin') {
        echo "<script>window.location.href = '" . base_url('error/403.php') . "';</script>"; // Arahkan ke halaman error 403
        exit();
    }

    return true; // Pengguna adalah admin
}

// Fungsi untuk memeriksa apakah pengguna adalah user biasa
function isUser($connection)
{
    // Periksa login terlebih dahulu
    $user = checkLogin($connection);

    // Cek apakah pengguna adalah user biasa
    if ($user['role'] !== 'Pengguna') {
        echo "<script>window.location.href = '" . base_url('error/403.php') . "';</script>"; // Arahkan ke halaman error 403
        exit();
    }

    return true; // Pengguna adalah user biasa
}
