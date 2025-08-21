<?php
// Menyertakan konfigurasi
include('../../config/config.php');

// Memulai sesi
session_start();

// Cek login dan role
isAdmin($connection);

$page_name = 'Testimoni';
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
                                    <th>Peran</th>
                                    <th>Penilaian</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Query untuk mengambil semua data
                                $query = "SELECT testimonials.*, users.*, testimonials.id AS testimonial_id 
                                            FROM testimonials 
                                            INNER JOIN users ON users.id = testimonials.user_id 
                                            ORDER BY testimonials.review_date DESC";

                                $result = $connection->query($query);

                                $no = 1;
                                while ($row = $result->fetch_assoc()) :
                                ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= format_indonesian_time(htmlspecialchars($row['review_date'])); ?></td>
                                        <td class="fst-italic"><?= htmlspecialchars($row['email']); ?></td>
                                        <td><?= htmlspecialchars($row['fullname']); ?></td>
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
                                            <?php $rating = htmlspecialchars($row['rating']); ?>
                                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                <?php if ($i <= $rating) : ?>
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                <?php else: ?>
                                                    <i class="bi bi-star text-warning"></i>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-info text-white btn-detail"
                                                    data-review="<?= htmlspecialchars($row['review']); ?>">
                                                    <i class="bi bi-info-circle"></i>
                                                </button>

                                                <button type="button" class="btn btn-sm btn-danger btn-delete" data-delete-url="<?= base_url('dashboard/testimonial/action.php?delete-testimonial=' . htmlspecialchars($row['testimonial_id'])); ?>"><i class="bi bi-trash-fill"></i></button>
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

        // Ambil pesan dari data-attribute tombol
        let review = $(this).data('review');

        // Tampilkan pesan menggunakan SweetAlert
        Swal.fire({
            title: 'Ulasan',
            html: `<p style="margin: 0; text-align: start;">${review}</p>`, // Menampilkan pesan sebagai HTML
            showConfirmButton: false, // Hanya menampilkan tombol 'Tutup', tanpa tombol konfirmasi
            showCancelButton: true,
            cancelButtonText: 'Tutup', // Teks tombol tutup
            cancelButtonColor: '#6c757d', // Warna tombol tutup
            customClass: {
                popup: 'large-popup' // Menambahkan kelas khusus untuk responsivitas
            },
            willOpen: () => {
                const popup = Swal.getPopup();
                popup.style.maxWidth = '90vw'; // Sesuaikan lebar dengan 90% dari viewport
                popup.style.maxHeight = '80vh'; // Sesuaikan tinggi dengan 80% dari viewport
                popup.style.overflowY = 'auto'; // Agar bisa di-scroll jika konten terlalu panjang
            }
        });
    });
</script>
<?php endSection('script'); ?>

<?php include('../template.php') ?>