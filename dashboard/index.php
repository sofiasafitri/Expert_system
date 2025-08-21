<?php
// Menyertakan konfigurasi
include('../config/config.php');

// Memulai sesi
session_start();

// Cek login dan role
isAdmin($connection);

$page_name = 'Dashboard';
?>

<?php startSection('css'); ?>
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
    <section class="row g-4 justify-content-center mb-4">
        <div class="col-sm-6 col-md-6 col-lg-3">
            <div class="card mb-0">
                <div class="card-body px-4 py-4-5">
                    <div class="row g-3">
                        <div class="col-xxl-3 d-flex justify-content-start ">
                            <div class="dashboard-icon-container text-bg-primary rounded-3">
                                <i class="dashboard-icon bi bi-virus2"></i>
                            </div>
                        </div>
                        <div class="col-xxl-9">
                            <h6 class="text-muted font-semibold">Penyakit</h6>
                            <h6 class="font-extrabold mb-0"><?= number_format($connection->query("SELECT COUNT(*) AS total FROM diseases")->fetch_assoc()['total']); ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-3">
            <div class="card mb-0">
                <div class="card-body px-4 py-4-5">
                    <div class="row g-3">
                        <div class="col-xxl-3 d-flex justify-content-start ">
                            <div class="dashboard-icon-container text-bg-primary rounded-3">
                                <i class="dashboard-icon bi bi-heart-pulse-fill"></i>
                            </div>
                        </div>
                        <div class="col-xxl-9">
                            <h6 class="text-muted font-semibold">Gejala</h6>
                            <h6 class="font-extrabold mb-0"><?= number_format($connection->query("SELECT COUNT(*) AS total FROM symptoms")->fetch_assoc()['total']); ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-3">
            <div class="card mb-0">
                <div class="card-body px-4 py-4-5">
                    <div class="row g-3">
                        <div class="col-xxl-3 d-flex justify-content-start ">
                            <div class="dashboard-icon-container text-bg-primary rounded-3">
                                <i class="dashboard-icon bi bi-lightbulb-fill"></i>
                            </div>
                        </div>
                        <div class="col-xxl-9">
                            <h6 class="text-muted font-semibold">Basis Pengetahuan</h6>
                            <h6 class="font-extrabold mb-0"><?= number_format($connection->query("SELECT COUNT(*) AS total FROM knowledge_bases")->fetch_assoc()['total']); ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-3">
            <div class="card mb-0">
                <div class="card-body px-4 py-4-5">
                    <div class="row g-3">
                        <div class="col-xxl-3 d-flex justify-content-start ">
                            <div class="dashboard-icon-container text-bg-primary rounded-3">
                                <i class="dashboard-icon bi bi-people-fill"></i>
                            </div>
                        </div>
                        <div class="col-xxl-9">
                            <h6 class="text-muted font-semibold">Pengguna</h6>
                            <h6 class="font-extrabold mb-0"><?= number_format($connection->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total']); ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="row g-4 mb-4">
        <div class="col-md-4 col-lg-3">
            <div class="card mb-0">
                <div class="card-body px-4 py-4-5">
                    <h5 class="mb-4">Ringkasan Konsultasi</h5>

                    <?php
                    // Query untuk menghitung jumlah konsultasi hari ini, bulan ini, dan tahun ini
                    $query = "
                                SELECT 
                                    SUM(CASE WHEN DATE(consultations.consultation_date) = CURDATE() THEN 1 ELSE 0 END) AS today_count,
                                    SUM(CASE WHEN MONTH(consultations.consultation_date) = MONTH(CURDATE()) AND YEAR(consultations.consultation_date) = YEAR(CURDATE()) THEN 1 ELSE 0 END) AS this_month_count,
                                    SUM(CASE WHEN YEAR(consultations.consultation_date) = YEAR(CURDATE()) THEN 1 ELSE 0 END) AS this_year_count
                                FROM consultations
                            ";
                    $result = $connection->query($query);
                    $data = $result->fetch_assoc();


                    $today = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'][date('l')] . ', ' . date('d');
                    $this_mounth = ['January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April', 'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'][date('F')];
                    $this_year = date('Y');
                    ?>

                    <div class="d-flex flex-column">
                        <h6 class="text-muted font-semibold">Hari ini (<?= $today; ?>)</h6>
                        <h6 class="font-extrabold mb-0"><?= number_format($data['today_count']); ?></h6>
                    </div>

                    <div class="d-flex flex-column border-top border-bottom my-3 py-3">
                        <h6 class="text-muted font-semibold">Bulan ini (<?= $this_mounth; ?>)</h6>
                        <h6 class="font-extrabold mb-0"><?= number_format($data['this_month_count']); ?></h6>
                    </div>

                    <div class="d-flex flex-column">
                        <h6 class="text-muted font-semibold">Tahun ini (<?= $this_year; ?>)</h6>
                        <h6 class="font-extrabold mb-0"><?= number_format($data['this_year_count']); ?></h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-lg-9">
            <div class="card mb-0">
                <div class="card-body px-4 py-4-5">
                    <h5 class="mb-4">Gafik Konsultasi</h5>

                    <?php
                    $year = $this_year; // Ambil tahun saat ini
                    $query = "SELECT MONTH(consultation_date) AS month, COUNT(*) AS consultation_count
                            FROM consultations 
                            WHERE YEAR(consultation_date) = $year
                            GROUP BY MONTH(consultation_date)
                            ORDER BY month";

                    // Eksekusi query dan simpan hasilnya
                    $result = mysqli_query($connection, $query);
                    $data = [];
                    while ($row = mysqli_fetch_assoc($result)) {
                        $data[] = $row;
                    }
                    ?>

                    <!-- Grafik Line Area Chart -->
                    <div id="consultation-chart"></div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php endSection('content'); ?>

<?php startSection('script'); ?>
<!-- Apexchart -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    // Mengambil data hasil query dari PHP
    let consultationsData = <?php echo json_encode($data); ?>;

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
            text: 'Tahun <?php echo $year; ?>', // Menampilkan tahun saat ini pada judul
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