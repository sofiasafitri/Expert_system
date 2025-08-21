<?php
// Menyertakan konfigurasi
include('../../config/config.php');

// Memulai sesi
session_start();

// Cek login dan role
isAdmin($connection);

$page_name = 'Penyakit';
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
                <a href="<?= base_url('dashboard/disease/create.php'); ?>" class="btn btn-sm btn-primary">Tambah</a>
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
                                    <th>Gambar Penyakit</th>
                                    <th>Kode Penyakit</th>
                                    <th>Nama Penyakit</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Query untuk mengambil semua data dari tabel diseases
                                $query = "SELECT * FROM diseases";
                                $result = $connection->query($query);

                                while ($row = $result->fetch_assoc()) :
                                ?>
                                    <tr>
                                        <td>
                                            <!-- Ratio ukuran gambar 4:3 dengan height="100" dan width="133" -->
                                            <img class="rounded-3 object-fit-cover shadow-sm" height="100" width="133" src="<?= get_image_url(base_url('assets/img/disease/' . htmlspecialchars($row['img']))); ?>" alt="<?= htmlspecialchars($row['disease_name']); ?>" loading="lazy">
                                        </td>
                                        <td><?= 'P' . sprintf("%03d", htmlspecialchars($row['id'])); ?></td>
                                        <td><?= htmlspecialchars($row['disease_name']); ?></td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="<?= base_url('dashboard/disease/edit.php?disease-id=' . htmlspecialchars($row['id'])); ?>" class="btn btn-sm btn-primary"><i class="bi bi-pencil-fill"></i></a>
                                                <button type="button" class="btn btn-sm btn-danger btn-delete" data-delete-url="<?= base_url('dashboard/disease/action.php?delete-disease=' . htmlspecialchars($row['id'])); ?>"><i class="bi bi-trash-fill"></i></button>
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
<?php endSection('script'); ?>

<?php include('../template.php') ?>