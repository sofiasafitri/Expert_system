<?php
// Menyertakan konfigurasi
include('../../config/config.php');

// Memulai sesi
session_start();

// Cek login dan role
isAdmin($connection);

$page_name = 'Riwayat Konsultasi';
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
                                    <th>Waktu Konsultasi</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Peran</th>
                                    <th>Penyakit</th>
                                    <th>Akurasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Query utama sudah dioptimalkan untuk mengambil penyakit utama sekaligus
                                $query_consultation = "SELECT consultations.id AS consultation_id, 
                                                            consultations.consultation_date, 
                                                            users.fullname, 
                                                            users.email, 
                                                            users.role, 
                                                            diseases.disease_name, 
                                                            histories.accuracy
                                                        FROM consultations
                                                        INNER JOIN histories ON histories.consultation_id = consultations.id
                                                        INNER JOIN users ON users.id = histories.user_id
                                                        INNER JOIN diseases ON diseases.id = histories.disease_id
                                                        WHERE histories.id = (
                                                            SELECT h.id
                                                            FROM histories h
                                                            WHERE h.consultation_id = consultations.id
                                                            ORDER BY h.accuracy DESC, h.id ASC
                                                            LIMIT 1
                                                        )
                                                        ORDER BY consultations.consultation_date DESC";

                                $result_consultation = $connection->query($query_consultation);

                                $no = 1;

                                while ($row_consultation = $result_consultation->fetch_assoc()) :
                                    $consultation_id = $row_consultation['consultation_id'];
                                    $consultation_date = $row_consultation['consultation_date'];
                                    $fullname = $row_consultation['fullname'];
                                    $email = $row_consultation['email'];
                                    $role = $row_consultation['role'];
                                    $disease_name = $row_consultation['disease_name'] ?? '-';
                                    $accuracy = isset($row_consultation['accuracy']) ? format_percentage($row_consultation['accuracy']) : 'N/A';
                                ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= format_indonesian_time(htmlspecialchars($consultation_date)); ?></td>
                                        <td><?= htmlspecialchars($fullname); ?></td>
                                        <td class="fst-italic"><?= htmlspecialchars($email); ?></td>
                                        <td>
                                            <?php
                                            $role_color = 'bg-light-secondary';
                                            if ($role === 'Admin') :
                                                $role_color = 'bg-light-primary';
                                            elseif ($role === 'Pakar') :
                                                $role_color = 'bg-light-info';
                                            endif;
                                            ?>
                                            <span class="badge <?= $role_color; ?>"><?= $role; ?></span>
                                        </td>
                                        <td><?= htmlspecialchars($disease_name); ?></td>
                                        <td><?= $accuracy; ?></td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="<?= base_url('dashboard/consultation-history/detail.php?consultation-id=' . htmlspecialchars($consultation_id)); ?>"
                                                    class="btn btn-sm btn-info text-white">
                                                    <i class="bi bi-info-circle"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
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