<?php
// Menyertakan konfigurasi
include('../../config/config.php');

// Memulai sesi
session_start();

// Cek login dan role
isAdmin($connection);

$page_name = 'Profil';

// Ambil data pengguna berdasarkan email
$email = $_SESSION['email'];
$query = $connection->query("SELECT id, fullname, phone FROM users WHERE email='$email'");
$result = mysqli_fetch_assoc($query);
$user_id = $result['id'];
$fullname = $result['fullname'];
$phone = substr($result['phone'], 3);
?>

<?php startSection('css'); ?>
<?php endSection('css'); ?>

<?php startSection('content'); ?>
<div class="page-heading">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-6">
            <div class="d-flex justify-content-center align-items-center gap-2">
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
                    <?php include('../layout/alerts.php') ?>

                    <form method="post" action="<?= base_url('auth/action.php?update-profile=' . $user_id); ?>">
                        <div class="row g-4 mb-4">
                            <div class="col-md-12">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control <?= isset($_SESSION['errors']['email']) ? 'is-invalid' : '' ?>" id="email" autofocus placeholder="email@example.com" value="<?= $email; ?>">
                                <div class="invalid-feedback">
                                    <?= isset($_SESSION['errors']['email']) ? $_SESSION['errors']['email'] : '' ?>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label for="fullname" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="fullname" class="form-control <?= isset($_SESSION['errors']['fullname']) ? 'is-invalid' : '' ?>" id="fullname" placeholder="Jhon Doe" value="<?= $fullname; ?>">
                                <div class="invalid-feedback">
                                    <?= isset($_SESSION['errors']['fullname']) ? $_SESSION['errors']['fullname'] : '' ?>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label for="phone" class="form-label">Nomor HP</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1">+62</span>
                                    <input type="number" name="phone" class="form-control <?= isset($_SESSION['errors']['phone']) ? 'is-invalid' : '' ?>" id="phone" placeholder="81*********" value="<?= $phone; ?>">
                                    <div class="invalid-feedback">
                                        <?= isset($_SESSION['errors']['phone']) ? $_SESSION['errors']['phone'] : '' ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label for="password" class="form-label">Kata Sandi Lama</label>
                                <input type="password" name="password" class="form-control <?= isset($_SESSION['errors']['password']) ? 'is-invalid' : '' ?>" id="password">
                                <small>Hanya diisi ketika memperbarui kata sandi.</small>
                                <div class="invalid-feedback">
                                    <?= isset($_SESSION['errors']['password']) ? $_SESSION['errors']['password'] : '' ?>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label for="new_password" class="form-label">Kata Sandi Baru</label>
                                <input type="password" name="new_password" class="form-control bg-light <?= isset($_SESSION['errors']['new_password']) ? 'is-invalid' : '' ?>" id="new_password" readonly minlength="8">
                                <small id="new_password_feedback" class="text-danger"></small>
                                <div class="invalid-feedback">
                                    <?= isset($_SESSION['errors']['new_password']) ? $_SESSION['errors']['new_password'] : '' ?>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label for="new_password_confirm" class="form-label">Konfirmasi Kata Sandi Baru</label>
                                <input type="password" name="new_password_confirm" class="form-control bg-light <?= isset($_SESSION['errors']['new_password_confirm']) ? 'is-invalid' : '' ?>" id="new_password_confirm" readonly>
                                <small id="new_password_confirm_feedback" class="text-danger"></small>
                                <div class="invalid-feedback">
                                    <?= isset($_SESSION['errors']['new_password_confirm']) ? $_SESSION['errors']['new_password_confirm'] : '' ?>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary text-white px-3">Simpan</button>
                    </form>

                    <?php unset($_SESSION['errors']); ?>
                </div>
            </div>
        </div>
    </section>
</div>
<?php endSection('content'); ?>

<?php startSection('script'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const newPasswordInput = document.getElementById('new_password');
        const newPasswordConfirmInput = document.getElementById('new_password_confirm');
        const newPasswordFeedback = document.getElementById('new_password_feedback');
        const newPasswordConfirmFeedback = document.getElementById('new_password_confirm_feedback');

        passwordInput.addEventListener('input', function() {
            const isReadOnly = !passwordInput.value;

            // Mengatur readOnly untuk input baru
            newPasswordInput.readOnly = isReadOnly;
            newPasswordConfirmInput.readOnly = isReadOnly;

            // Menambahkan atau menghapus kelas bg-light
            if (isReadOnly) {
                newPasswordInput.classList.add('bg-light');
                newPasswordConfirmInput.classList.add('bg-light');
            } else {
                newPasswordInput.classList.remove('bg-light');
                newPasswordConfirmInput.classList.remove('bg-light');
            }

            // Mengosongkan feedback
            newPasswordFeedback.textContent = '';
            newPasswordConfirmFeedback.textContent = '';
        });


        newPasswordInput.addEventListener('input', validatePassword);
        newPasswordConfirmInput.addEventListener('input', validatePassword);

        function validatePassword() {
            const newPassword = newPasswordInput.value;
            const newPasswordConfirm = newPasswordConfirmInput.value;

            // Validasi untuk kata sandi baru
            if (newPassword.length < 8) {
                newPasswordFeedback.textContent = 'Kata sandi baru minimal 8 karakter.';
            } else {
                newPasswordFeedback.textContent = ''; // Hapus pesan jika valid
            }

            // Validasi untuk konfirmasi kata sandi baru
            if (newPasswordConfirm) {
                if (newPassword && newPassword !== newPasswordConfirm) {
                    newPasswordConfirmFeedback.textContent = 'Kata sandi baru dan konfirmasi tidak cocok.';
                } else {
                    newPasswordConfirmFeedback.textContent = ''; // Hapus pesan jika valid
                }
            }
        }
    });
</script>
<?php endSection('script'); ?>

<?php include('../template.php') ?>