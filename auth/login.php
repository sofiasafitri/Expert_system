<?php
// Menyertakan konfigurasi
include('../config/config.php');

// Memulai session
session_start();

$page_name = 'Masuk';
?>

<?php startSection('css'); ?>
<?php endSection('css'); ?>

<?php startSection('content'); ?>
<div class="col-12">
    <div class="py-2">
        <h5 class="text-center mb-0 fs-4"><?= $page_name; ?></h5>
        <p class="text-center small">Silakan masuk dengan akun Anda</p>
    </div>

    <!-- Alerts -->
    <?php include('alerts.php'); ?>

    <?php
    // Ambil data admin di tabel users
    $user = $connection->query("SELECT * FROM users WHERE role='Admin'");

    // Cek data
    if (mysqli_num_rows($user) > 0) :
    ?>
        <form class="row g-3 py-2" method="post" action="<?= base_url('auth/action.php'); ?>">
            <div class="col-12">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control <?= isset($_SESSION['errors']['email']) ? 'is-invalid' : '' ?>" id="email" placeholder="email@example.com" autofocus>
                <div class="invalid-feedback">
                    <?= isset($_SESSION['errors']['email']) ? $_SESSION['errors']['email'] : '' ?>
                </div>
            </div>

            <div class="col-12">
                <label for="password" class="form-label">Kata Sandi</label>
                <input type="password" name="password" class="form-control <?= isset($_SESSION['errors']['password']) ? 'is-invalid' : '' ?>" id="password">
                <div class="invalid-feedback">
                    <?= isset($_SESSION['errors']['password']) ? $_SESSION['errors']['password'] : '' ?>
                </div>
            </div>

            <div class="col-12">
                <button class="btn btn-primary w-100 fw-semibold" type="submit" name="login">Masuk</button>
            </div>

            <div class="col-12 text-center">
                <p class="small mb-3">Belum punya akun? <a href="<?= base_url('auth/register.php'); ?>" class="link-primary">Daftar</a></p>
                <p class="small mb-0"><a href="<?= base_url(); ?>" class="link-primary">Kembali ke Beranda</a></p>
            </div>
        </form>

        <?php unset($_SESSION['errors']); ?>
    <?php else: ?>
        <form class="row g-3 py-2" method="post" action="<?= base_url('auth/action.php'); ?>">
            <div class="col-12">
                <button class="btn btn-primary w-100 fw-semibold" type="submit" name="create-admin-account">Buat Akun</button>
            </div>
        </form>
    <?php endif ?>
</div>
<?php endSection('content'); ?>

<?php startSection('script'); ?>
<?php endSection('script'); ?>

<?php include('template.php') ?>