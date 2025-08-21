<?php
// Menyertakan konfigurasi
include('../../config/config.php');

// Memulai sesi
session_start();

// Cek login dan role
isAdmin($connection);

$page_name = 'Kelola Pengguna';
?>

<?php startSection('css'); ?>
<?php include('../layout/dataTables/dataTablesCSS.php') ?>
<?php endSection('css'); ?>

<?php startSection('content'); ?>
<div class="page-heading">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center gap-2">
                <h3 class="mb-0"><?= $page_name; ?></h3>
                <a href="<?= base_url('dashboard/manage-user/create.php'); ?>" class="btn btn-sm btn-primary">Tambah</a>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <?php include('../layout/alerts.php') ?>

                    <div class="table-responsive">
                        <table class="table table-hover" id="table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Nomor HP</th>
                                    <th>Peran</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Query untuk mengambil semua data dari tabel users
                                $query = "SELECT * FROM users ORDER BY fullname ASC";
                                $result = $connection->query($query);

                                $no = 1;
                                while ($row = $result->fetch_assoc()) :
                                ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= htmlspecialchars($row['fullname']); ?></td>
                                        <td class="fst-italic"><?= htmlspecialchars($row['email']); ?></td>
                                        <td><?= !empty($row['phone']) ? htmlspecialchars($row['phone']) : 'N/A'; ?></td>
                                        <td>
                                            <?php
                                            $role = htmlspecialchars($row['role']);
                                            $role_color = '';
                                            if ($role === 'Admin') :
                                                $role_color = 'bg-light-primary';
                                            elseif ($role === 'Pakar') :
                                                $role_color = 'bg-light-info';
                                            else:
                                                $role_color = 'bg-light-secondary';
                                            endif
                                            ?>
                                            <span class="badge <?= $role_color; ?>"><?= $role; ?></span>
                                        </td>
                                        <td>
                                            <span class="badge <?= (htmlspecialchars($row['status']) === 'Aktif') ? 'bg-success' : 'bg-danger'; ?>">
                                                <?= htmlspecialchars($row['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="<?= base_url('dashboard/manage-user/edit.php?user-id=' . htmlspecialchars($row['id'])); ?>" class="btn btn-sm btn-primary"><i class="bi bi-pencil-fill"></i></a>
                                                <button type="button" class="btn btn-sm btn-danger btn-delete" data-delete-url="<?= base_url('dashboard/manage-user/action.php?delete-user=' . htmlspecialchars($row['id'])); ?>"><i class="bi bi-trash-fill"></i></button>
                                                <?php if ($role === 'Admin') : ?>
                                                    <button type="button" class="btn btn-sm btn-secondary btn-reset" data-reset-url="<?= base_url('dashboard/manage-user/action.php?reset-password-user=' . htmlspecialchars($row['id'])); ?>" data-reset-fullname="<?= htmlspecialchars($row['fullname']); ?>" data-reset-role="<?= $role; ?>"><i class="bi bi-key"></i></button>
                                                <?php endif ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php endSection('content'); ?>

<?php startSection('script'); ?>
<?php include('../layout/dataTables/dataTablesJS.php') ?>
<script>
    // Fungsi untuk reset password
    $(document).on('click', '.btn-reset', function(e) {
        e.preventDefault();

        // Ambil data dari atribut tombol
        let resetUrl = $(this).data('reset-url');
        let fullName = $(this).data('reset-fullname');
        let role = $(this).data('reset-role');

        if (!resetUrl) {
            Swal.fire('Error', 'URL tidak ditemukan!', 'error');
            return;
        }

        Swal.fire({
            title: 'Konfirmasi',
            html: `Apakah Anda yakin ingin mereset kata sandi untuk <b>${fullName}</b> dengan peran <b>${role}</b>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#435ebe',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, reset!',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect ke URL reset password
                window.location.href = resetUrl;
            }
        });
    });
</script>
<?php endSection('script'); ?>

<?php include('../template.php') ?>