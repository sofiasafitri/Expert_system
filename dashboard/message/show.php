<?php
// Menyertakan konfigurasi
include('../../config/config.php');

// Memulai sesi
session_start();

// Cek login dan role
isAdmin($connection);

$page_name = 'Pesan';
?>

<?php startSection('css'); ?>
<?php include('../layout/dataTables/dataTablesCSS.php') ?>
<style>
    /* Custom class untuk popup */
    .large-popup {
        max-width: 90vw;
        max-height: 80vh;
        overflow-y: auto;
    }
</style>
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
                                    <th>Waktu</th>
                                    <th>Email</th>
                                    <th>Nama</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Query untuk mengambil semua data
                                $query = "SELECT * FROM messages ORDER BY message_date DESC";
                                $result = $connection->query($query);

                                $no = 1;
                                while ($row = $result->fetch_assoc()) :
                                ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= format_indonesian_time(htmlspecialchars($row['message_date'])); ?></td>
                                        <td class="fst-italic"><?= htmlspecialchars($row['email']); ?></td>
                                        <td><?= htmlspecialchars($row['fullname']); ?></td>
                                        <td><?= htmlspecialchars($row['subject']); ?></td>
                                        <td>
                                            <span class="badge <?= (htmlspecialchars($row['status']) === 'Dibaca') ? 'bg-success' : 'bg-warning'; ?>">
                                                <?= htmlspecialchars($row['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-info text-white btn-detail"
                                                    data-message="<?= htmlspecialchars($row['message']); ?>"
                                                    data-status="<?= htmlspecialchars($row['status']); ?>"
                                                    data-update-url="<?= base_url('dashboard/message/action.php?update-status-message=' . htmlspecialchars($row['id'])); ?>">
                                                    <i class="bi bi-info-circle"></i>
                                                </button>

                                                <a class="btn btn-sm btn-primary" href="mailto:<?= htmlspecialchars($row['email']); ?>" target="_blank"><i class="bi bi-send-fill"></i></a>

                                                <button type="button" class="btn btn-sm btn-danger btn-delete" data-delete-url="<?= base_url('dashboard/message/action.php?delete-message=' . htmlspecialchars($row['id'])); ?>"><i class="bi bi-trash-fill"></i></button>
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
    $(document).on('click', '.btn-detail', function(e) {
        e.preventDefault();

        // Ambil pesan, URL, dan status dari tombol
        let message = $(this).data('message');
        let status = $(this).data('status'); // Status pesan
        let updateUrl = $(this).data('update-url');

        // Cek apakah updateUrl ada
        if (!updateUrl) {
            Swal.fire('Error', 'URL tidak ditemukan!', 'error');
            return;
        }

        // Menentukan apakah tombol "Sudah Dibaca" akan ditampilkan
        let showConfirmButton = (status !== 'Dibaca'); // Jika status bukan 'Dibaca', tampilkan tombol

        // Tampilkan pesan menggunakan SweetAlert
        Swal.fire({
            title: 'Pesan',
            html: `<p style="margin: 0; text-align: start;">${message}</p>`, // Menampilkan pesan sebagai HTML
            showCancelButton: true,
            confirmButtonColor: '#435ebe',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sudah Dibaca',
            cancelButtonText: 'Tutup',
            showConfirmButton: showConfirmButton, // Mengatur tombol konfirmasi berdasarkan status
            customClass: {
                popup: 'large-popup' // Menambahkan kelas khusus untuk responsivitas
            },
            willOpen: () => {
                // Pastikan popup menyesuaikan ukuran saat dibuka
                const popup = Swal.getPopup();
                popup.style.maxWidth = '90vw'; // Sesuaikan lebar dengan 90% dari viewport
                popup.style.maxHeight = '80vh'; // Sesuaikan tinggi dengan 80% dari viewport
                popup.style.overflowY = 'auto'; // Agar bisa di-scroll jika konten terlalu panjang
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect ke URL setelah tombol 'Sudah Dibaca' ditekan
                window.location.href = updateUrl;
            }
        });
    });
</script>
<?php endSection('script'); ?>

<?php include('../template.php') ?>