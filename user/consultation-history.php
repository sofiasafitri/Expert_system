<?php
// Menyertakan konfigurasi
include('../config/config.php');

// Memulai sesi
session_start();

// Nama halaman
$page_name = 'Riwayat Konsultasi';

// Cek login dan role
checkLogin($connection);

// ID user
$user_id = $_SESSION['user_id'];
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
                    <h3 class="mb-0"><?= $page_name; ?></h3>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4" data-aos="fade-up">
            <div class="col-lg-4">
                <div class="card mb-0">
                    <div class="card-body px-4 py-4-5">
                        <h5 class="text-center mb-4">Ringkasan Konsultasi</h5>

                        <?php $fullname_session = isset($_SESSION['fullname']) ? htmlspecialchars($_SESSION['fullname']) : ''; ?>
                        <?php $email_session = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>

                        <a href="<?= base_url('user/profile.php'); ?>">
                            <div class="d-flex justify-content-center align-items-center gap-2 mb-4">
                                <img src="<?= get_image_url('https://avatar.iran.liara.run/username?username=' . urlencode($fullname_session)); ?>" class="rounded-circle" alt="<?= $fullname_session; ?>" height="64" width="64">
                            </div>
                            <h6 class="text-center mb-1 text-uppercase"><?= $fullname_session; ?></h6>
                            <h6 class="text-center mb-0 fst-italic"><?= $email_session; ?></h6>
                        </a>

                        <?php
                        // Query untuk menghitung jumlah konsultasi hari ini, bulan ini, dan tahun ini
                        $query = "
                                    SELECT 
                                        SUM(CASE WHEN DATE(consultations.consultation_date) = CURDATE() THEN 1 ELSE 0 END) AS today_count,
                                        SUM(CASE WHEN MONTH(consultations.consultation_date) = MONTH(CURDATE()) AND YEAR(consultations.consultation_date) = YEAR(CURDATE()) THEN 1 ELSE 0 END) AS this_month_count,
                                        SUM(CASE WHEN YEAR(consultations.consultation_date) = YEAR(CURDATE()) THEN 1 ELSE 0 END) AS this_year_count
                                    FROM consultations 
                                    INNER JOIN (
                                        SELECT DISTINCT consultation_id
                                        FROM histories
                                        WHERE user_id = '$user_id'
                                    ) AS unique_histories ON unique_histories.consultation_id = consultations.id
                                    INNER JOIN users ON users.id = '$user_id'
                                    WHERE users.id = '$user_id'
                                ";

                        $result = $connection->query($query);
                        $data = $result->fetch_assoc();

                        $today = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'][date('l')] . ', ' . date('d');
                        $this_mounth = ['January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April', 'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'][date('F')];
                        $this_year = date('Y');
                        ?>

                        <div class="d-flex flex-column mt-4">
                            <h6 class="text-muted font-semibold">Hari ini (<?= $today; ?>)</h6>
                            <h6 class="font-extrabold mb-0"><?= number_format($data['today_count']); ?></h6>
                        </div>

                        <div class="d-flex flex-column border-top border-bottom my-3 py-3">
                            <h6 class="text-muted font-semibold">Bulan ini (<?= $this_mounth; ?>)</h6>
                            <h6 class="font-extrabold mb-0"><?= number_format($data['this_month_count']); ?></h6>
                        </div>

                        <div class="d-flex flex-column border-bottom pb-3 mb-3">
                            <h6 class="text-muted font-semibold">Tahun ini (<?= $this_year; ?>)</h6>
                            <h6 class="font-extrabold mb-0"><?= number_format($data['this_year_count']); ?></h6>
                        </div>

                        <div class="row mt-4">
                            <div class="col">
                                <a href="<?= base_url('user/testimonial.php'); ?>" class="btn btn-primary w-100 rounded-pill">Beri Ulasan</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card mb-0">
                    <div class="card-body px-4 py-4-5">
                        <h5 class="mb-4">Gafik Konsultasi</h5>

                        <?php
                        $year = $this_year; // Ambil tahun saat ini
                        $query = "SELECT MONTH(consultation_date) AS month, COUNT(DISTINCT consultations.id) AS consultation_count
                                    FROM consultations 
                                    INNER JOIN histories ON histories.consultation_id = consultations.id 
                                    INNER JOIN users ON users.id = histories.user_id
                                    WHERE YEAR(consultation_date) = $year AND users.id = '$user_id'
                                    GROUP BY MONTH(consultation_date)
                                    ORDER BY month";

                        // Eksekusi query dan simpan hasilnya
                        $result = mysqli_query($connection, $query);
                        $data_chart = [];
                        while ($row = mysqli_fetch_assoc($result)) {
                            $data_chart[] = $row;
                        }
                        ?>

                        <!-- Grafik Line Area Chart -->
                        <div id="consultation-chart"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4" data-aos="fade-up">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center mb-4">Semua Riwayat</h4>

                        <div class="table-responsive">
                            <table class="table table-hover" id="table">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Waktu Konsultasi</th>
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
                                                                diseases.disease_name, 
                                                                histories.accuracy
                                                            FROM consultations
                                                            INNER JOIN histories 
                                                                ON histories.consultation_id = consultations.id
                                                            INNER JOIN users 
                                                                ON users.id = histories.user_id
                                                            INNER JOIN diseases 
                                                                ON diseases.id = histories.disease_id
                                                            WHERE users.id = '$user_id'
                                                            AND histories.id = (
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
                                        $disease_name = $row_consultation['disease_name'] ?? '-';
                                        $accuracy = isset($row_consultation['accuracy']) ? format_percentage($row_consultation['accuracy']) : 'N/A';
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= format_indonesian_time(htmlspecialchars($consultation_date)); ?></td>
                                            <td><?= htmlspecialchars($disease_name); ?></td>
                                            <td><?= $accuracy; ?></td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="<?= base_url('user/consultation-detail.php?consultation-id=' . htmlspecialchars($consultation_id)); ?>"
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
        </div>
    </div>
</section>
<?php endSection('content'); ?>

<?php startSection('script'); ?>
<?php include('layout/dataTables/dataTablesJS.php') ?>

<!-- Apexchart -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Mengambil data hasil query dari PHP
    let consultationsData = <?= json_encode($data_chart); ?>;

    // Inisialisasi bulan dan jumlah konsultasi
    let months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    let consultationCounts = new Array(12).fill(0); // Inisialisasi array dengan 0 untuk setiap bulan

    // Masukkan data konsultasi ke dalam array berdasarkan bulan
    consultationsData.forEach(function(item) {
        consultationCounts[item.month - 1] = item.consultation_count;
    });

    // Membuat data untuk grafik
    let options = {
        chart: {
            type: 'area', // Jenis chart: area (line area chart)
            height: 400,
            defaultLocale: 'en',
            locales: [{
                name: 'en',
                options: {
                    toolbar: {
                        download: 'Unduh',
                        selection: 'Seleksi',
                        selectionZoom: 'Perbesar Seleksi',
                        zoomIn: 'Perbesar',
                        zoomOut: 'Perkecil',
                        pan: 'Geser',
                        reset: 'Reset Zoom',
                    }
                }
            }]
        },
        title: {
            text: 'Tahun <?= $year; ?>', // Menampilkan tahun saat ini pada judul
            align: 'center'
        },
        xaxis: {
            categories: months, // Bulan Januari - Desember
        },
        yaxis: {
            title: {
                text: 'Jumlah Konsultasi'
            }
        },
        series: [{
            name: 'Jumlah Konsultasi',
            data: consultationCounts // Data jumlah konsultasi per bulan
        }],
        colors: ['#435ebe'], // Warna area chart
        fill: {
            type: 'gradient', // Menggunakan gradien untuk area
            gradient: {
                shadeIntensity: 0.3,
                opacityFrom: 0.7,
                opacityTo: 0.2,
                stops: [0, 100, 100, 100]
            }
        }
    };

    // Menampilkan grafik di dalam div dengan id 'consultation-chart'
    let chart = new ApexCharts(document.querySelector("#consultation-chart"), options);
    chart.render();
</script>
<?php endSection('script'); ?>

<?php include('template.php') ?>