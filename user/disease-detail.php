<?php
// Menyertakan konfigurasi
include('../config/config.php');

// Memulai sesi
session_start();

// Nama halaman
$page_name = 'Detail Penyakit';

// Ambil ID konsultasi dari parameter GET
$disease_id = htmlspecialchars($_GET['disease-id']) ?? 0;
$query = $connection->query("SELECT * FROM diseases WHERE id = '$disease_id'");
if (!mysqli_num_rows($query) > 0) {
    $_SESSION['popup-error'] = "Data tidak ditemukan.";
    echo "<script>window.location.href = '" . base_url('user/disease.php') . "';</script>";
    exit();
} else {
    $result = mysqli_fetch_assoc($query);
}
?>

<?php startSection('css'); ?>
<?php endSection('css'); ?>

<?php startSection('content'); ?>
<section class="page-section" id="pageSection">
    <div class="container px-4">
        <div class="row justify-content-center mb-4" data-aos="zoom-in">
            <div class="col-12">
                <div class="d-flex justify-content-start align-items-center gap-2">
                    <a href="<?= base_url('user/disease.php'); ?>" class="btn btn-sm border-0"><i class="bi bi-arrow-left"></i></a>
                    <h3 class="mb-0"><?= $page_name; ?></h3>
                </div>
            </div>
        </div>

        <div class="row g-4 justify-content-center" data-aos="fade-up">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title text-center"><?= htmlspecialchars($result['disease_name']); ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-center mb-5">
                            <div class="ratio ratio-4x3 image-show">
                                <img src="<?= get_image_url(base_url('assets/img/disease/' . htmlspecialchars($result['img']))); ?>" alt="<?= htmlspecialchars($result['disease_name']); ?>" class="rounded-4 object-fit-cover">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-xl-12">
                                <h5 class="mb-3">Deskripsi</h5>
                                <article><?= nl2br($result['description']); ?></article>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endSection('content'); ?>

<?php startSection('script'); ?>
<?php endSection('script'); ?>

<?php include('template.php') ?>