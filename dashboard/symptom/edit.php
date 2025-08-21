<?php
// Menyertakan konfigurasi
include('../../config/config.php');

// Memulai sesi
session_start();

// Cek login dan role
isAdmin($connection);

$page_name = 'Ubah Gejala';

// Ambil ID konsultasi dari parameter GET
$symptom_id = htmlspecialchars($_GET['symptom-id']) ?? 0;
$query = $connection->query("SELECT * FROM symptoms WHERE id = '$symptom_id'");
if (!mysqli_num_rows($query) > 0) {
    $_SESSION['warning'] = "Data tidak ditemukan.";
    echo "<script>window.location.href = '" . base_url('dashboard/symptom/show.php') . "';</script>";
    exit();
} else {
    $result = mysqli_fetch_assoc($query);
}
?>

<?php startSection('css'); ?>
<?php endSection('css'); ?>

<?php startSection('content'); ?>
<div class="page-heading">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-6">
            <div class="d-flex justify-content-start align-items-center gap-2">
                <a href="<?= base_url('dashboard/symptom/show.php'); ?>" class="btn btn-sm border-0"><i class="bi bi-arrow-left"></i></a>
                <h3 class="mb-0"><?= $page_name; ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="row justify-content-center">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <?php
                    // Value Form dari session (jika ada error)
                    $symptom_name = $result['symptom_name'];
                    ?>

                    <form method="post" action="<?= base_url('dashboard/symptom/action.php'); ?>">
                        <input type="hidden" name="symptom_id" value="<?= $symptom_id; ?>">

                        <div class="row g-4 mb-4">
                            <div class="col-md-12">
                                <label for="symptom_name" class="form-label">Nama Gejala <span class="text-danger">*</span></label>
                                <input type="text" name="symptom_name" class="form-control <?= isset($_SESSION['errors']['symptom_name']) ? 'is-invalid' : '' ?>" id="symptom_name" value="<?= $symptom_name; ?>">
                                <div class="invalid-feedback">
                                    <?= isset($_SESSION['errors']['symptom_name']) ? $_SESSION['errors']['symptom_name'] : '' ?>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn btn-primary text-white px-3" name="update-symptom">Simpan</button>
                        </div>
                    </form>

                    <?php unset($_SESSION['errors']); ?>
                </div>
            </div>
        </div>
    </section>
</div>
<?php endSection('content'); ?>

<?php startSection('script'); ?>
<?php endSection('script'); ?>

<?php include('../template.php') ?>