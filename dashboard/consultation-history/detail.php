<?php
// Menyertakan konfigurasi
include('../../config/config.php');

// Memulai sesi
session_start();

// Cek login dan role
isAdmin($connection);

$page_name = 'Detail Konsultasi';

// Ambil ID konsultasi dari parameter GET
$consultation_id = htmlspecialchars($_GET['consultation-id']) ?? 0;

// Query pertama - Ambil data konsultasi
$query_consultation = $connection->query(
    "SELECT consultations.id, consultations.consultation_date, users.fullname, users.email, users.role 
        FROM consultations
        INNER JOIN histories ON histories.consultation_id = consultations.id
        INNER JOIN users ON users.id = histories.user_id
        WHERE consultations.id = '$consultation_id'
        LIMIT 1"
);

// Cek apakah data konsultasi ditemukan
if ($query_consultation && mysqli_num_rows($query_consultation) > 0) {
    // Ambil data konsultasi
    $consultation = mysqli_fetch_assoc($query_consultation);
} else {
    $_SESSION['warning'] = "Data tidak ditemukan.";
    echo "<script>window.location.href = '" . base_url('dashboard/consultation-history/show.php') . "';</script>";
    exit();
}

// Query kedua - Ambil penyakit utama (akurasi tertinggi)
$query_main_disease = $connection->query(
    "SELECT diseases.id AS disease_id, diseases.disease_name, diseases.description, diseases.img, histories.accuracy 
        FROM histories
        INNER JOIN diseases ON diseases.id = histories.disease_id
        WHERE histories.consultation_id = '$consultation_id'
        ORDER BY histories.accuracy DESC
        LIMIT 1"
);

$main_disease = mysqli_fetch_assoc($query_main_disease);

// Query ketiga - Ambil kemungkinan penyakit lain selain penyakit utama
// Ambil ID penyakit utama
$main_disease_id = $main_disease['disease_id'];
$query_other_disease = "SELECT diseases.id AS disease_id, diseases.disease_name, histories.accuracy
                        FROM histories
                        INNER JOIN diseases ON diseases.id = histories.disease_id
                        WHERE histories.consultation_id = '$consultation_id'
                        AND diseases.id != '$main_disease_id'
                        ORDER BY histories.accuracy DESC";

$result_other_disease = $connection->query($query_other_disease);

$other_disease = [];

while ($row = mysqli_fetch_assoc($result_other_disease)) {
    $other_disease[] = $row;
}

// Query keempat - Ambil gejala yang dipilih
$query_symptoms = $connection->query(
    "SELECT symptom FROM consultations WHERE id = '$consultation_id' LIMIT 1"
);

$symptom_list = [];

if ($query_symptoms) {
    $symptom_data = mysqli_fetch_assoc($query_symptoms);

    // Decode JSON dengan validasi
    $decoded_symptoms = json_decode($symptom_data['symptom'], true);

    // Pastikan hasil decode adalah array dan memiliki data yang benar
    if (is_array($decoded_symptoms)) {
        foreach ($decoded_symptoms as $item) {
            if (isset($item['symptom_id']) && isset($item['condition_value'])) {
                $symptom_list[] = [
                    'symptom_id' => $item['symptom_id'],
                    'condition_value' => $item['condition_value']
                ];
            }
        }
    }
}

// Ambil semua gejala dari database
$symptom_names = [];
$query_all_symptoms = $connection->query("SELECT id, symptom_name FROM symptoms");

while ($row = mysqli_fetch_assoc($query_all_symptoms)) {
    $symptom_names[$row['id']] = $row['symptom_name'];
}

// Ambil semua kondisi dari database
$condition_list = [];
$query_conditions = $connection->query("SELECT condition_value, condition_name FROM conditions");

while ($row = mysqli_fetch_assoc($query_conditions)) {
    $condition_list[$row['condition_value']] = $row['condition_name'];
}

// Ambil data semua penyakit unuk tampilan grafik
$disease_list = [];

if ($main_disease) {
    $disease_list[$main_disease['disease_id']] = $main_disease['accuracy'];
}

foreach ($other_disease as $disease) {
    $disease_list[$disease['disease_id']] = $disease['accuracy'];
}
?>

<?php startSection('css'); ?>
<?php include('../layout/dataTables/dataTablesCSS.php') ?>
<?php endSection('css'); ?>

<?php startSection('content'); ?>
<div class="page-heading">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="d-flex justify-content-start align-items-center gap-2">
                <a href="<?= base_url('dashboard/consultation-history/show.php'); ?>" class="btn btn-sm border-0"><i class="bi bi-arrow-left"></i></a>
                <h3 class="mb-0"><?= $page_name; ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="row">
        <!-- Hasil diagnosis -->
        <div class="col-xl-6">
            <div class="row">
                <div class="col-12">
                    <!-- Ringkasan -->
                    <div class="card">
                        <div class="card-body">
                            <article>
                                <h4 class="card-title">Ringkasan Diagnosis</h4>

                                <p>Berdasarkan hasil konsultasi yang dilakukan oleh <strong><?= htmlspecialchars($consultation['fullname']); ?></strong> pada tanggal <strong><?= format_indonesian_time(htmlspecialchars($consultation['consultation_date'])); ?></strong>, terdapat kemungkinan menderita penyakit <strong class="text-danger"><?= htmlspecialchars($main_disease['disease_name']); ?></strong> dengan tingkat akurasi sebesar <strong class="text-danger"><?= format_percentage(htmlspecialchars($main_disease['accuracy'])); ?></strong> seperti pada grafik berikut:</p>
                            </article>

                            <!-- Grafik Pie Chart -->
                            <div id="disease-chart"></div>

                            <hr class="my-5">

                            <!-- Kemungkinan penyakit lain -->
                            <div>
                                <h4 class="card-title">Kemungkinan Penyakit Lain</h4>

                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Kode Penyakit</th>
                                                <th>Nama Penyakit</th>
                                                <th>Akurasi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $has_other_diseases = false; // Inisialisasi flag

                                            // Iterasi melalui array $other_disease
                                            foreach ($other_disease as $disease) :
                                                if ($disease['accuracy'] > 0) :
                                                    $has_other_diseases = true; // Set flag ke true jika ada penyakit dengan akurasi > 0
                                            ?>
                                                    <tr>
                                                        <td><?= 'P' . sprintf("%03d", htmlspecialchars($disease['disease_id'])); ?></td>
                                                        <td><?= htmlspecialchars($disease['disease_name'] ?? 'Tidak Diketahui'); ?></td>
                                                        <td><?= format_percentage(htmlspecialchars($disease['accuracy'])); ?></td>
                                                    </tr>
                                                <?php
                                                endif;
                                            endforeach;

                                            // Jika tidak ada kemungkinan penyakit lain dengan akurasi > 0
                                            if (!$has_other_diseases) :
                                                ?>
                                                <tr>
                                                    <td colspan="3" class="text-center">Tidak ada kemungkinan penyakit lain</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <!-- Gejala yang dipilih -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title text-center">Gejala Yang Dipilih</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover" id="table">
                                    <thead>
                                        <tr>
                                            <th>Kode Gejala</th>
                                            <th>Nama Gejala</th>
                                            <th>Kondisi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($symptom_list as $symptom) : ?>
                                            <?php
                                            $symptom_id = $symptom['symptom_id'];
                                            $condition_value = $symptom['condition_value'];

                                            // Validate if data is available
                                            $symptom_code = 'G' . sprintf("%03d", htmlspecialchars($symptom_id));
                                            $symptom_name = $symptom_names[$symptom_id] ?? 'Unknown';

                                            // Find the closest condition in the condition list
                                            $condition_name = 'Unknown';
                                            foreach ($condition_list as $value => $name) {
                                                if ($condition_value <= $value) {
                                                    $condition_name = $name;
                                                    break;
                                                }
                                            }
                                            ?>
                                            <tr>
                                                <td><?= $symptom_code; ?></td>
                                                <td><?= htmlspecialchars($symptom_name); ?></td>
                                                <td><?= htmlspecialchars($condition_name); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Penyakit -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title text-center"><?= htmlspecialchars($main_disease['disease_name']); ?></h3>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-center mb-5">
                        <div class="ratio ratio-4x3 img-show">
                            <img src="<?= get_image_url(base_url('assets/img/disease/' . htmlspecialchars($main_disease['img']))); ?>" alt="<?= htmlspecialchars($main_disease['disease_name']); ?>" class="rounded-4 object-fit-cover">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-xl-12">
                            <h5 class="mb-3">Deskripsi & Saran</h5>
                            <article><?= nl2br($main_disease['description']); ?></article>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php endSection('content'); ?>

<?php startSection('script'); ?>
<?php include('../layout/dataTables/dataTablesJS.php') ?>

<!-- ApexChart -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var labelsData = [];
        var seriesData = [];

        <?php foreach ($disease_list as $disease_id => $accuracy) : ?>
            <?php
            $query_disease = $connection->query("SELECT disease_name FROM diseases WHERE id = '$disease_id'");
            $disease_data = mysqli_fetch_assoc($query_disease);
            ?>
            labelsData.push("<?= htmlspecialchars($disease_data['disease_name'] ?? 'Tidak Diketahui'); ?>");
            seriesData.push(<?= $accuracy * 100; ?>);
        <?php endforeach; ?>

        var options = {
            chart: {
                type: 'pie',
                height: 350
            },
            series: seriesData,
            labels: labelsData,
            legend: {
                position: 'bottom'
            },
            dataLabels: {
                enabled: false
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val.toFixed(2) + "%";
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#disease-chart"), options);
        chart.render();
    });
</script>

<?php endSection('script'); ?>

<?php include('../template.php') ?>