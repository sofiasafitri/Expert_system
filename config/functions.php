<?php
// Fungsi untuk mengembalikan URL dasar (base URL) dengan penambahan path tambahan
function base_url($path = '')
{
    return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
}

// Fungsi untuk memformat tanggal dan waktu sesuai format Indonesia
function format_indonesian_time($datetime)
{
    $months = [
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];

    // Mengonversi string tanggal menjadi timestamp
    $timestamp = strtotime($datetime);

    // Menarik informasi tanggal, bulan, tahun, dan waktu dari timestamp
    $day = date('d', $timestamp);
    $month = $months[(int)date('m', $timestamp)];
    $year = date('Y', $timestamp);
    $time = date('H:i', $timestamp);

    return "$day $month $year $time"; // Format hasil: DD Bulan YYYY HH:MM
}

// Fungsi untuk mendapatkan URL gambar, baik dari server lokal atau URL eksternal
function get_image_url($image_url)
{
    // Jika URL gambar kosong, mengembalikan gambar placeholder
    if (empty($image_url)) {
        return base_url('assets/img/static/no-image-placeholder.png');
    }

    // Cek apakah URL adalah URL eksternal
    if (filter_var($image_url, FILTER_VALIDATE_URL)) {
        // Memeriksa apakah URL eksternal mengarah ke gambar yang valid (status 200 dan content-type 'image')
        $headers = get_headers($image_url, 1);

        if (isset($headers['Content-Type']) && strpos($headers['Content-Type'], 'image') !== false) {
            return $image_url; // URL eksternal valid, kembalikan URL gambar
        }
    }

    // Jika bukan URL eksternal, cek apakah gambar ada di server
    $image_path = $_SERVER['DOCUMENT_ROOT'] . parse_url($image_url, PHP_URL_PATH);

    // Jika file tidak ditemukan di server, gunakan gambar placeholder
    if (!file_exists($image_path)) {
        return base_url('assets/img/static/no-image-placeholder.png');
    }

    // Jika file gambar ada, kembalikan URL gambar
    return $image_url;
}

// Fungsi untuk membersihkan teks dari tag HTML dan karakter yang tidak diinginkan
function clean_text($text)
{
    // Hapus tag HTML dari teks
    $text = strip_tags($text);

    // Hapus karakter selain huruf, angka, dan tanda baca yang diizinkan
    $text = preg_replace("/[^a-zA-Z0-9\s,\.?!']/u", "", $text);

    // Potong teks jika lebih dari 100 karakter dan tambahkan "..."
    if (strlen($text) > 100) {
        $text = substr($text, 0, 100) . "...";
    }

    return $text; // Kembalikan teks yang sudah dibersihkan dan dipotong
}

// Fungsi untuk memformat nilai menjadi persentase dengan jumlah desimal tertentu
function format_percentage($value, $decimals = 2)
{
    return number_format($value * 100, $decimals, '.', '') . ' %'; // Format hasil: XX.XX %
}
