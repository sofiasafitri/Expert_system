<?php
// Menyertakan konfigurasi
include('../../config/config.php');

// Memulai sesi
session_start();

// Cek login dan role
isAdmin($connection);

$page_name = 'Ubah Pengguna';

// Ambil ID konsultasi dari parameter GET
$user_id = htmlspecialchars($_GET['user-id']) ?? 0;
$query = $connection->query("SELECT * FROM users WHERE id = '$user_id'");
if (!mysqli_num_rows($query) > 0) {
    $_SESSION['warning'] = "Data tidak ditemukan.";
    echo "<script>window.location.href = '" . base_url('dashboard/manage-user/show.php') . "';</script>";
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
                <a href="<?= base_url('dashboard/manage-user/show.php'); ?>" class="btn btn-sm border-0"><i class="bi bi-arrow-left"></i></a>
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
                    // Value Form
                    $email = $result['email'];
                    $fullname = $result['fullname'];
                    $role = $result['role'];
                    $status = $result['status'];
                    $phone = substr($result['phone'], 3) // untuk menghapus +62;
                    ?>

                    <form method="post" action="<?= base_url('dashboard/manage-user/action.php'); ?>">
                        <input type="hidden" name="user_id" value="<?= $user_id; ?>">

                        <div class="row g-4 mb-4">
                            <div class="col-md-12">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control <?= isset($_SESSION['errors']['email']) ? 'is-invalid' : '' ?>" id="email" value="<?= $email; ?>">
                                <div class="invalid-feedback">
                                    <?= isset($_SESSION['errors']['email']) ? $_SESSION['errors']['email'] : '' ?>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label for="fullname" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="fullname" class="form-control <?= isset($_SESSION['errors']['fullname']) ? 'is-invalid' : '' ?>" id="fullname" value="<?= $fullname; ?>">
                                <div class="invalid-feedback">
                                    <?= isset($_SESSION['errors']['fullname']) ? $_SESSION['errors']['fullname'] : '' ?>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label for="role" class="form-label">Peran <span class="text-danger">*</span></label>
                                <select class="form-select <?= isset($_SESSION['errors']['role']) ? 'is-invalid' : '' ?>" id="role" name="role">
                                    <option value="" disabled selected>-- Pilih Peran --</option>
                                    <option value="Admin" <?= ($role === 'Admin') ? 'selected' : ''; ?>>Admin</option>
                                    <option value="Pakar" <?= ($role === 'Pakar') ? 'selected' : ''; ?>>Pakar</option>
                                    <option value="Pengguna" <?= ($role === 'Pengguna') ? 'selected' : ''; ?>>Pengguna</option>
                                </select>
                                <div class="invalid-feedback">
                                    <?= isset($_SESSION['errors']['role']) ? $_SESSION['errors']['role'] : '' ?>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select <?= isset($_SESSION['errors']['status']) ? 'is-invalid' : '' ?>" id="status" name="status">
                                    <option value="" disabled selected>-- Pilih Status --</option>
                                    <option value="Aktif" <?= ($status === 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                                    <option value="Tidak Aktif" <?= ($status === 'Tidak Aktif') ? 'selected' : ''; ?>>Tidak Aktif</option>
                                </select>
                                <div class="invalid-feedback">
                                    <?= isset($_SESSION['errors']['status']) ? $_SESSION['errors']['status'] : '' ?>
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
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn btn-primary text-white px-3" name="update-user">Simpan</button>
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