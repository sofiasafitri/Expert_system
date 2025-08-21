<?php
// Menyertakan konfigurasi
include('../../config/config.php');

// Memulai sesi
session_start();

// Cek login dan role
isAdmin($connection);

$page_name = 'Notifikasi';
?>

<?php startSection('css'); ?>
<?php endSection('css'); ?>

<?php startSection('content'); ?>
<div class="page-heading">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center gap-2">
                <h3 class="mb-0"><?= $page_name; ?></h3>
                <a href="<?= base_url('dashboard/notification/action.php?clear-notifications=true'); ?>" class="btn btn-sm btn-primary">Bersihkan</a>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="row">
        <?php
        // Query gabungan notifikasi belum dilihat
        $query_notification = "
                                SELECT 
                                    'message' AS type,
                                    m.message_date AS date,
                                    m.subject AS title,
                                    m.email,
                                    m.fullname,
                                    m.status AS item_status
                                FROM messages m
                                LEFT JOIN notifications n ON n.message_id = m.id
                                WHERE n.status = 'Belum Dilihat'

                                UNION ALL

                                SELECT 
                                    'consultation' AS type,
                                    c.consultation_date AS date,
                                    d.disease_name AS title,
                                    u.email,
                                    u.fullname,
                                    h.accuracy AS item_status
                                FROM consultations c
                                INNER JOIN histories h ON h.consultation_id = c.id
                                INNER JOIN diseases d ON d.id = h.disease_id
                                INNER JOIN users u ON u.id = h.user_id
                                INNER JOIN notifications n ON n.consultation_id = c.id
                                WHERE n.status = 'Belum Dilihat' 
                                AND h.accuracy = (
                                    SELECT MAX(h2.accuracy) FROM histories h2 WHERE h2.consultation_id = c.id
                                )

                                UNION ALL

                                SELECT 
                                    'testimonial' AS type,
                                    t.review_date AS date,
                                    t.review AS title,
                                    u.email,
                                    u.fullname,
                                    t.rating AS item_status
                                FROM testimonials t
                                LEFT JOIN notifications n ON n.testimonial_id = t.id
                                LEFT JOIN users u ON t.user_id = u.id
                                WHERE n.status = 'Belum Dilihat'

                                ORDER BY date DESC
                            ";

        $result_notification = $connection->query($query_notification);
        ?>

        <?php if ($result_notification && $result_notification->num_rows > 0): ?>
            <div class="col-12">
                <div class="card rounded-4">
                    <div class="card-body">
                        <div class="list-group rounded-4">
                            <?php while ($row = $result_notification->fetch_assoc()): ?>
                                <?php
                                // Tentukan URL berdasarkan tipe notifikasi
                                switch ($row['type']) {
                                    case 'message':
                                        $url = base_url('dashboard/message/show.php');
                                        break;
                                    case 'consultation':
                                        $url = base_url('dashboard/consultation-history/show.php');
                                        break;
                                    case 'testimonial':
                                        $url = base_url('dashboard/testimonial/show.php');
                                        break;
                                    default:
                                        $url = '#';
                                }
                                ?>
                                <a href="<?= $url ?>" class="list-group-item list-group-item-action">
                                    <h5 class="mb-2">
                                        <?=
                                        $row['type'] === 'message' ? 'Pesan Masuk' : ($row['type'] === 'consultation' ? 'Hasil Konsultasi' : 'Testimoni Pengguna');
                                        ?>
                                    </h5>
                                    <p class="mb-2">
                                        <?=
                                        (mb_strlen($row['title']) > 30)
                                            ? htmlspecialchars(mb_substr($row['title'], 0, 30)) . '...'
                                            : htmlspecialchars($row['title'])
                                        ?>
                                    </p>
                                    <small class="text-body-secondary">
                                        <?= htmlspecialchars($row['fullname']); ?> (<span class="fst-italic"><?= htmlspecialchars($row['email']); ?></span>)
                                    </small>
                                    <div class="d-flex w-100 justify-content-between gap-2 mt-2">
                                        <small class="text-body-secondary">
                                            <?php
                                            if ($row['type'] === 'consultation') {
                                                echo 'Akurasi: ' . format_percentage($row['item_status']);
                                            } elseif ($row['type'] === 'testimonial') {
                                                $rating = (int)$row['item_status'];
                                                for ($i = 1; $i <= 5; $i++) {
                                                    echo '<i class="bi bi-star' . ($i <= $rating ? '-fill' : '') . ' text-warning"></i>';
                                                }
                                            } else {
                                                $badgeClass = $row['item_status'] === 'Dibaca' ? 'bg-success' : 'bg-warning';
                                                echo "<span class='badge {$badgeClass}'>{$row['item_status']}</span>";
                                            }
                                            ?>
                                        </small>
                                        <small class="text-body-secondary"><?= format_indonesian_time($row['date']); ?></small>
                                    </div>
                                </a>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="col-12 text-center">
                <img class="rounded-2 object-fit-cover mb-4" src="<?= get_image_url(base_url('assets/img/static/notification.svg')); ?>" alt="Tidak ada notifikasi" height="250" width="250">
                <h6>Tidak ada notifikasi</h6>
            </div>
        <?php endif; ?>
    </section>
</div>
<?php endSection('content'); ?>

<?php startSection('script'); ?>
<?php endSection('script'); ?>

<?php include('../template.php') ?>