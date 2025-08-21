<?php
// Menyertakan konfigurasi
include('../../config/config.php');

// Memulai sesi
session_start();

// Cek login dan role
isAdmin($connection);

$page_name = 'Tambah Penyakit';
?>

<?php startSection('css'); ?>
<!-- CKEditor -->
<?php include('../layout/CKEditor/CKEditorCSS.php') ?>
<?php endSection('css'); ?>

<?php startSection('content'); ?>
<div class="page-heading">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="d-flex justify-content-start align-items-center gap-2">
                <a href="<?= base_url('dashboard/disease/show.php'); ?>" class="btn btn-sm border-0"><i class="bi bi-arrow-left"></i></a>
                <h3 class="mb-0"><?= $page_name; ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <?php
                    // Value Form dari session (jika ada error)
                    $disease_name = isset($_SESSION['form_data']['disease_name']) ? htmlspecialchars($_SESSION['form_data']['disease_name']) : '';
                    $description = isset($_SESSION['form_data']['description']) ? htmlspecialchars($_SESSION['form_data']['description']) : '';
                    ?>

                    <form method="post" action="<?= base_url('dashboard/disease/action.php'); ?>" enctype="multipart/form-data" data-form-page="create">
                        <div class="row g-4 mb-4 justify-content-center">
                            <div class="col-md-12">
                                <label for="disease_name" class="form-label">Nama Penyakit <span class="text-danger">*</span></label>
                                <input type="text" name="disease_name" class="form-control <?= isset($_SESSION['errors']['disease_name']) ? 'is-invalid' : '' ?>" id="disease_name" value="<?= $disease_name; ?>">
                                <div class="invalid-feedback">
                                    <?= isset($_SESSION['errors']['disease_name']) ? $_SESSION['errors']['disease_name'] : '' ?>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label for="description" class="form-label">Deskripsi & Saran <span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control <?= isset($_SESSION['errors']['description']) ? 'is-invalid' : '' ?>" id="description"><?= $description; ?></textarea>
                                <div class="invalid-feedback">
                                    <?= isset($_SESSION['errors']['description']) ? $_SESSION['errors']['description'] : '' ?>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="img" class="form-label d-flex justify-content-center">
                                    <div class="ratio ratio-4x3 img-show">
                                        <img id="preview-img" src="<?= get_image_url(base_url('assets/img/static/image-placeholder.jpg')); ?>" alt="Pratinjau Gambar" class="rounded-3 object-fit-cover cursor-pointer img-preview">
                                    </div>
                                </label>
                                <div class="text-center mt-3">
                                    <div>Klik Untuk Unggah Gambar <span class="text-danger">*</span></div>
                                    <span id="error-img" class="text-danger"></span>
                                </div>
                                <input type="file" class="form-control d-none <?= isset($_SESSION['errors']['img']) ? 'is-invalid' : '' ?>" id="img" name="img" accept="image/jpg, image/jpeg, image/png, image/webp">
                                <div class="invalid-feedback text-center">
                                    <?= isset($_SESSION['errors']['img']) ? $_SESSION['errors']['img'] : '' ?>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn btn-primary text-white px-3" name="create-disease">Simpan</button>
                        </div>
                    </form>

                    <?php
                    unset($_SESSION['errors']);
                    unset($_SESSION['form_data']);
                    ?>
                </div>
            </div>
        </div>
    </section>
</div>
<?php endSection('content'); ?>

<?php startSection('script'); ?>
<!-- CKEditor -->
<?php include('../layout/CKEditor/CKEditorJS.php') ?>
<?php endSection('script'); ?>

<?php include('../template.php') ?>