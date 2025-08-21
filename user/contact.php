<?php
// Menyertakan konfigurasi
include('../config/config.php');

// Memulai sesi
session_start();

// Nama halaman
$page_name = 'Kontak';
?>

<?php startSection('css'); ?>
<?php include('layout/dataTables/dataTablesCSS.php') ?>
<?php endSection('css'); ?>

<?php startSection('content'); ?>
<section class="page-section" id="pageSection">
    <div class="container px-4">
        <div class="row justify-content-center mb-4" data-aos="zoom-in">
            <div class="col-12">
                <div class="d-flex justify-content-center align-items-center gap-2">
                    <h3 class="mb-0"><?= $page_name . ' Pakar'; ?></h3>
                </div>
            </div>
        </div>

        <div class="row g-4 justify-content-center" data-aos="fade-up">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-light-info alert-mode mb-4" role="alert">
                            <p>Demi kenyamanan bersama, mohon untuk menghubungi pakar pada jam kerja, yaitu Senin hingga Sabtu, pukul 08:00 - 16:00 WITA. Pada hari Minggu dan hari libur, layanan tidak tersedia.</p>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover" id="table">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama</th>
                                        <th>Nomor HP</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Query untuk mengambil semua data dari tabel users
                                    $query = "SELECT * FROM users WHERE role = 'Pakar' AND status = 'Aktif' ORDER BY fullname ASC";
                                    $result = $connection->query($query);

                                    $no = 1;
                                    while ($row = $result->fetch_assoc()) :
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= htmlspecialchars($row['fullname']); ?></td>
                                            <td><?= !empty($row['phone']) ? htmlspecialchars($row['phone']) : 'N/A'; ?></td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <?php if (!empty($row['phone'])): ?>
                                                        <?php if ($row['status'] === 'Aktif'): ?>
                                                            <button type="button" class="btn btn-sm btn-success btn-wa" data-phone="<?= htmlspecialchars($row['phone']); ?>"><i class="bi bi-whatsapp"></i></button>
                                                        <?php else: ?>
                                                            <span class="text-danger"><?= $row['status']; ?></span>
                                                        <?php endif; ?>
                                                    <?php else : ?>
                                                        <span>N/A</span>
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
        </div>
    </div>
</section>
<?php endSection('content'); ?>

<?php startSection('script'); ?>
<?php include('layout/dataTables/dataTablesJS.php') ?>

<script>
    // Function to handle whatsapp button
    $(document).on('click', '.btn-wa', function(e) {
        e.preventDefault();

        let phone = $(this).data('phone');
        let message = 'Salam sehat, saya ingin konsultasi terkait dengan kesehatan.';
        let encodedMessage = encodeURIComponent(message);
        let urlWA = `https://wa.me/${phone}?text=${encodedMessage}`;

        if (!phone) {
            Swal.fire('Error', 'Nomor telepon tidak ditemukan!', 'error');
            return;
        }

        Swal.fire({
            title: 'Hubungi Pakar?',
            text: "Anda akan dialihkan ke WhatsApp untuk konsultasi.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#435ebe',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.open(urlWA, '_blank');
            }
        });
    });
</script>
<?php endSection('script'); ?>

<?php include('template.php') ?>