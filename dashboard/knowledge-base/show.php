<?php
// Menyertakan konfigurasi
include('../../config/config.php');

// Memulai sesi
session_start();

// Cek login dan role
isAdmin($connection);

$page_name = 'Basis Pengetahuan';
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
                <a href="<?= base_url('dashboard/knowledge-base/create.php'); ?>" class="btn btn-sm btn-primary">Tambah</a>
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
                                    <th>Nama Penyakit</th>
                                    <th>Nama Gejala</th>
                                    <th>Nilai MB</th>
                                    <th>Nilai MD</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Query untuk mengambil semua data dari tabel knowledge_bases, diseases, symptoms
                                $query = "SELECT knowledge_bases.*, 
                                                knowledge_bases.id AS knowledge_bases_id, 
                                                diseases.disease_name, 
                                                symptoms.symptom_name 
                                            FROM knowledge_bases
                                            INNER JOIN diseases ON diseases.id = knowledge_bases.disease_id
                                            INNER JOIN symptoms ON symptoms.id = knowledge_bases.symptom_id
                                            ORDER BY diseases.disease_name ASC";

                                $result = $connection->query($query);

                                $no = 1;
                                while ($row = $result->fetch_assoc()) :
                                ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= htmlspecialchars($row['disease_name']); ?></td>
                                        <td><?= htmlspecialchars($row['symptom_name']); ?></td>
                                        <td><?= htmlspecialchars($row['mb_value']); ?></td>
                                        <td><?= htmlspecialchars($row['md_value']); ?></td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="<?= base_url('dashboard/knowledge-base/edit.php?kb-id=' . htmlspecialchars($row['knowledge_bases_id'])); ?>" class="btn btn-sm btn-primary"><i class="bi bi-pencil-fill"></i></a>
                                                <button type="button" class="btn btn-sm btn-danger btn-delete" data-delete-url="<?= base_url('dashboard/knowledge-base/action.php?delete-kb=' . htmlspecialchars($row['knowledge_bases_id'])); ?>"><i class="bi bi-trash-fill"></i></button>
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