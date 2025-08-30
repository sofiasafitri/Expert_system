<?php
// Menyertakan konfigurasi
include('../../config/config.php');

// Memulai sesi
session_start();

// Cek login dan role
isAdmin($connection);

// Ambil ID konsultasi dari parameter GET
$consultation_id = htmlspecialchars($_GET['consultation-id']) ?? 0;

if (empty($consultation_id)) {
    header('Location: ' . base_url('dashboard/consultation-history/show.php'));
    exit;
}

// Query pertama - Ambil data konsultasi
$query_consultation = $connection->query(
    "SELECT consultations.id, consultations.consultation_date, users.fullname, users.email, users.role, users.phone 
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

// Query keempat - Ambil gejala yang dipilih dari JSON
$query_symptoms = $connection->query(
    "SELECT symptom FROM consultations WHERE id = '$consultation_id' LIMIT 1"
);

$symptom_list = [];
if ($query_symptoms) {
    $symptom_data = mysqli_fetch_assoc($query_symptoms);
    $decoded_symptoms = json_decode($symptom_data['symptom'], true);

    if (is_array($decoded_symptoms)) {
        foreach ($decoded_symptoms as $item) {
            if (isset($item['symptom_id']) && isset($item['condition_value']) && $item['condition_value'] > 0) {
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
$query_conditions = $connection->query("SELECT condition_value, condition_name FROM conditions ORDER BY condition_value");
while ($row = mysqli_fetch_assoc($query_conditions)) {
    $condition_list[$row['condition_value']] = $row['condition_name'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Konsultasi - <?= htmlspecialchars($consultation['fullname']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
            line-height: 1.6;
        }
        .kop-surat {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #000;
            position: relative;
        }
        .logo2 {
            width: 80px;
            height: 80px;
            margin-right: 20px;
            object-fit: contain;
        }
        .logo {
            width: 80px;
            height: 80px;
            margin-left: 20px;
            object-fit: contain;
        }
        .teks {
            flex: 1;
            text-align: center;
        }
        .teks h4 {
            margin: 2px 0;
            font-size: 16px;
            color: #000;
        }
        .teks h5 {
            margin: 2px 0;
            font-size: 14px;
            color: #000;
        }
        .teks p {
            margin: 5px 0;
            font-size: 12px;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #435ebe;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #435ebe;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            color: #666;
            margin: 5px 0 0 0;
        }
        .info-section {
            margin-bottom: 25px;
        }
        .info-section h3 {
            color: #435ebe;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            font-size: 18px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 8px;
            vertical-align: top;
        }
        .info-table td:first-child {
            font-weight: bold;
            width: 150px;
        }
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .results-table th, .results-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .results-table th {
            background-color: #435ebe;
            color: white;
            font-weight: bold;
        }
        .results-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .main-disease-highlight {
            background-color: #fff3cd;
            padding: 15px;
            border-left: 4px solid #ffc107;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .admin-note {
            background-color: #e8f4fd;
            padding: 15px;
            border-left: 4px solid #007bff;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .accuracy-high { color: #dc3545; font-weight: bold; }
        .accuracy-medium { color: #fd7e14; font-weight: bold; }
        .accuracy-low { color: #6c757d; }
        .disease-detail {
            margin-top: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .no-data {
            color: #999;
            font-style: italic;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .admin-signature {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
            width: 200px;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 60px;
            padding-top: 5px;
        }
        @media print {
            body { 
                margin: 0; 
                font-size: 12px;
            }
            .no-print { 
                display: none; 
            }
            .header h1 {
                font-size: 20px;
            }
            .info-section h3 {
                font-size: 16px;
            }
            .disease-detail {
                break-inside: avoid;
            }
            .kop-surat {
                border-bottom: 3px solid #000 !important;
            }
            .kop-surat .teks h4,
            .kop-surat .teks h5,
            .kop-surat .teks p {
                color: #000 !important;
            }
        }
    </style>
</head>
<body>
    <!-- Kop Surat -->
    <div class="kop-surat">
        <img src="<?= base_url('assets/img/static/dinkes.png'); ?>" alt="Logo Dinkes" class="logo2">
        <div class="teks">
            <h4><b>PEMERINTAH KOTA TANGERANG SELATAN</b></h4>
            <h4><b>DINAS KESEHATAN</b></h4>
            <h4><b>PUSKESMAS SETU</b></h4>
            <h5><b>KECAMATAN SETU</b></h5>
            <p><b>Jl. Raya Puspitek No.1, Kec.Setu, Kota Tangerang Selatan, Banten</b></p>
        </div>                  
        <img src="<?= base_url('assets/img/static/puskes.png'); ?>" alt="Logo Puskesmas" class="logo">
    </div>
    

    <div class="info-section">
        <h3>Data Pasien & Konsultasi</h3>
        <table class="info-table">
            <tr>
                <td>Nama Lengkap</td>
                <td>: <?= htmlspecialchars($consultation['fullname']); ?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td>: <?= htmlspecialchars($consultation['email']); ?></td>
            </tr>
            <tr>
                <td>No. Telepon</td>
                <td>: <?= htmlspecialchars($consultation['phone'] ?? 'Tidak tersedia'); ?></td>
            </tr>
            <tr>
                <td>Status Pengguna</td>
                <td>: <?= htmlspecialchars($consultation['role']); ?></td>
            </tr>
            <tr>
                <td>Tanggal Konsultasi</td>
                <td>: <?= format_indonesian_time($consultation['consultation_date']); ?></td>
            </tr>
            <tr>
                <td>ID Konsultasi</td>
                <td>: #<?= str_pad($consultation_id, 6, '0', STR_PAD_LEFT); ?></td>
            </tr>
        </table>
    </div>

    <?php if ($main_disease): ?>
    <div class="main-disease-highlight">
        <h4><i class="bi bi-clipboard-check"></i> Hasil Diagnosis Utama</h4>
        <p><strong><?= htmlspecialchars($main_disease['disease_name']); ?></strong> dengan tingkat keyakinan <strong class="accuracy-high"><?= format_percentage($main_disease['accuracy']); ?></strong></p>
        <p><small>Diagnosis ini diperoleh berdasarkan analisis gejala menggunakan metode Certainty Factor dengan tingkat keyakinan tertinggi.</small></p>
    </div>
    <?php endif; ?>

    <div class="info-section">
        <h3>Data Gejala yang Dilaporkan</h3>
        <?php if (!empty($symptom_list)): ?>
            <table class="results-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No.</th>
                        <th style="width: 100px;">Kode</th>
                        <th>Nama Gejala</th>
                        <th style="width: 150px;">Kondisi Pasien</th>
                        <th style="width: 100px;">Nilai CF</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach ($symptom_list as $symptom): 
                        $symptom_id = $symptom['symptom_id'];
                        $condition_value = $symptom['condition_value'];
                        
                        // Cari kondisi yang sesuai
                        $condition_name = 'Tidak Diketahui';
                        foreach ($condition_list as $value => $name) {
                            if ((float)$condition_value == (float)$value) {
                                $condition_name = $name;
                                break;
                            }
                        }
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td>G<?= sprintf("%03d", $symptom_id); ?></td>
                        <td><?= htmlspecialchars($symptom_names[$symptom_id] ?? 'Gejala Tidak Dikenal'); ?></td>
                        <td><?= htmlspecialchars($condition_name); ?></td>
                        <td><?= $condition_value; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">Tidak ada gejala yang tercatat dalam sistem</div>
        <?php endif; ?>
    </div>

    <div class="info-section">
        <h3>Analisis Hasil Diagnosis</h3>
        <?php if ($main_disease): ?>
            <table class="results-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">Rank</th>
                        <th style="width: 100px;">Kode</th>
                        <th>Nama Penyakit</th>
                        <th style="width: 120px;">Certainty Factor</th>
                        <th style="width: 120px;">Persentase</th>
                        <th style="width: 100px;">Kategori</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $rank = 1;
                    // Penyakit utama
                    $accuracy_class = $main_disease['accuracy'] >= 0.7 ? 'accuracy-high' : 
                                    ($main_disease['accuracy'] >= 0.4 ? 'accuracy-medium' : 'accuracy-low');
                    $category = $main_disease['accuracy'] >= 0.7 ? 'Tinggi' : 
                               ($main_disease['accuracy'] >= 0.4 ? 'Sedang' : 'Rendah');
                    ?>
                    <tr>
                        <td><strong><?= $rank++; ?></strong></td>
                        <td>P<?= sprintf("%03d", $main_disease['disease_id']); ?></td>
                        <td><strong><?= htmlspecialchars($main_disease['disease_name']); ?></strong></td>
                        <td class="<?= $accuracy_class; ?>"><?= number_format($main_disease['accuracy'], 4); ?></td>
                        <td class="<?= $accuracy_class; ?>"><?= format_percentage($main_disease['accuracy']); ?></td>
                        <td><span class="<?= $accuracy_class; ?>"><?= $category; ?></span></td>
                    </tr>
                    
                    <?php foreach ($other_disease as $disease): 
                        $accuracy_class = $disease['accuracy'] >= 0.7 ? 'accuracy-high' : 
                                        ($disease['accuracy'] >= 0.4 ? 'accuracy-medium' : 'accuracy-low');
                        $category = $disease['accuracy'] >= 0.7 ? 'Tinggi' : 
                                   ($disease['accuracy'] >= 0.4 ? 'Sedang' : 'Rendah');
                    ?>
                    <tr>
                        <td><?= $rank++; ?></td>
                        <td>P<?= sprintf("%03d", $disease['disease_id']); ?></td>
                        <td><?= htmlspecialchars($disease['disease_name']); ?></td>
                        <td class="<?= $accuracy_class; ?>"><?= number_format($disease['accuracy'], 4); ?></td>
                        <td class="<?= $accuracy_class; ?>"><?= format_percentage($disease['accuracy']); ?></td>
                        <td><span class="<?= $accuracy_class; ?>"><?= $category; ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">Tidak ada hasil diagnosis yang tersedia</div>
        <?php endif; ?>
    </div>

    <?php if ($main_disease && !empty($main_disease['description'])): ?>
    <div class="disease-detail">
        <h3><i class="bi bi-file-medical"></i> Informasi Detail Penyakit</h3>
        <h4><?= htmlspecialchars($main_disease['disease_name']); ?></h4>
        <div class="description">
            <?= $main_disease['description']; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="footer">
        <p><strong>CATATAN PENTING:</strong></p>
        <p>1. Dokumen ini adalah laporan administratif hasil konsultasi sistem pakar diagnosis penyakit</p>
        <p>2. Hasil diagnosis sistem pakar TIDAK menggantikan pemeriksaan medis profesional</p>
        <p>3. Untuk diagnosis definitif dan penanganan medis, pasien wajib berkonsultasi dengan dokter</p>
        <p>4. Dokumen ini bersifat rahasia dan hanya untuk keperluan medis dan administratif</p>
        <hr style="margin: 15px 0;">
        <p><strong>Dicetak pada:</strong> <?= date('d/m/Y H:i:s'); ?> | <strong>ID Konsultasi:</strong> #<?= str_pad($consultation_id, 6, '0', STR_PAD_LEFT); ?> | <strong>Versi Sistem:</strong> 1.0</p>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background-color: #435ebe; color: white; border: none; border-radius: 5px; cursor: pointer; margin-right: 10px;">
            <i class="bi bi-printer"></i> Cetak Laporan
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background-color: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer;">
            <i class="bi bi-x-circle"></i> Tutup
        </button>
    </div>

    <script>
        // Auto print saat halaman dimuat (opsional)
        // window.onload = function() { window.print(); }
        
        // Function untuk download PDF
        function downloadPDF() {
            window.print();
        }
    </script>
</body>
</html>