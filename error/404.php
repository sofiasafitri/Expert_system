<?php
// Menyertakan konfigurasi
include('../config/config.php');

$page_name = '404';
?>

<?php startSection('css'); ?>
<?php endSection('css'); ?>

<?php startSection('content'); ?>
<div class="error-page container">
    <div class="text-center">
        <img class="img-error mb-4" src="<?= base_url('assets/img/static/error-404.svg'); ?>" alt="Tidak Ditemukan" style="width: 300px;">
        <h1 class="error-title">Tidak Ditemukan</h1>
        <p class="fs-5 text-gray-600 mb-4">Halaman yang Anda cari tidak ditemukan.</p>
        <a href="javascript:history.back()" class="btn btn-lg btn-outline-primary">Kembali</a>
    </div>
</div>
<?php endSection('content'); ?>

<?php startSection('script'); ?>
<?php endSection('script'); ?>

<?php include('template.php') ?>