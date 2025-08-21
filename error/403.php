<?php
// Menyertakan konfigurasi
include('../config/config.php');

$page_name = '403';
?>

<?php startSection('css'); ?>
<?php endSection('css'); ?>

<?php startSection('content'); ?>
<div class="error-page container">
    <div class="text-center">
        <img class="img-error mb-4" src="<?= base_url('assets/img/static/error-403.svg'); ?>" alt="Tidak Dapat Diakses" style="width: 300px;">
        <h1 class="error-title">Tidak Dapat Diakses</h1>
        <p class="fs-5 text-gray-600 mb-4">Anda tidak memiliki izin untuk melihat halaman ini.</p>
        <a href="javascript:history.back()" class="btn btn-lg btn-outline-primary">Kembali</a>
    </div>
</div>
<?php endSection('content'); ?>

<?php startSection('script'); ?>
<?php endSection('script'); ?>

<?php include('template.php') ?>