<?php
session_start();
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../config/functions.php');
require_once(__DIR__ . '/../config/helpers.php');

// Cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . base_url('auth/login.php'));
    exit;
}

$user_id = $_SESSION['user_id'];
$consultation_id = $_GET['consultation-id'] ?? '';

if (empty($consultation_id)) {
    header('Location: ' . base_url('user/consultation-history.php'));
    exit;
}

// Validasi consultation milik user
$query_check = "SELECT COUNT(*) as count FROM consultations 
                INNER JOIN histories ON histories.consultation_id = consultations.id 
                WHERE consultations.id = ? AND histories.user_id = ?";
$stmt_check = $connection->prepare($query_check);
$stmt_check->bind_param("ii", $consultation_id, $user_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$row_check = $result_check->fetch_assoc();

if ($row_check['count'] == 0) {
    header('Location: ' . base_url('user/consultation-history.php'));
    exit;
}

// Ambil data konsultasi
$query_consultation = "SELECT consultations.id AS consultation_id, 
                              consultations.consultation_date, 
                              users.fullname as user_name,
                              users.email as user_email
                       FROM consultations
                       INNER JOIN histories ON histories.consultation_id = consultations.id
                       INNER JOIN users ON users.id = histories.user_id
                       WHERE consultations.id = ? AND users.id = ?
                       LIMIT 1";
$stmt = $connection->prepare($query_consultation);
if (!$stmt) {
    die("Query error: " . $connection->error);
}

$stmt->bind_param("ii", $consultation_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$consultation_data = $result->fetch_assoc();

// Jika data tidak ditemukan, ambil dari session
if (!$consultation_data) {
    $consultation_data = [
        'user_name' => $_SESSION['fullname'] ?? 'N/A',
        'user_email' => $_SESSION['email'] ?? 'N/A',
        'consultation_date' => date('Y-m-d H:i:s')
    ];
}

// Ambil semua hasil diagnosis untuk konsultasi ini dengan detail penyakit
$query_results = "SELECT diseases.id as disease_id,
                         diseases.disease_name, 
                         histories.accuracy,
                         diseases.description,
                         diseases.img
                  FROM histories
                  INNER JOIN diseases ON diseases.id = histories.disease_id
                  WHERE histories.consultation_id = ?
                  ORDER BY histories.accuracy DESC";

$stmt_results = $connection->prepare($query_results);
if (!$stmt_results) {
    die("Query error: " . $connection->error);
}

$stmt_results->bind_param("i", $consultation_id);
$stmt_results->execute();
$result_results = $stmt_results->get_result();

// Ambil penyakit dengan akurasi tertinggi
$main_disease = null;
$other_diseases = [];

while ($disease = $result_results->fetch_assoc()) {
    if ($main_disease === null) {
        $main_disease = $disease;
    } else {
        $other_diseases[] = $disease;
    }
}

// Ambil gejala yang dipilih dari JSON
$query_symptoms_json = "SELECT symptom FROM consultations WHERE id = ?";
$stmt_symptoms_json = $connection->prepare($query_symptoms_json);
if (!$stmt_symptoms_json) {
    die("Query error: " . $connection->error);
}
$stmt_symptoms_json->bind_param("i", $consultation_id);
$stmt_symptoms_json->execute();
$result_symptoms_json = $stmt_symptoms_json->get_result();
$symptoms_data = $result_symptoms_json->fetch_assoc();

$selected_symptoms = [];
if ($symptoms_data && !empty($symptoms_data['symptom'])) {
    $decoded_symptoms = json_decode($symptoms_data['symptom'], true);
    if (is_array($decoded_symptoms)) {
        foreach ($decoded_symptoms as $symptom) {
            if (isset($symptom['symptom_id']) && isset($symptom['condition_value']) && $symptom['condition_value'] > 0) {
                $selected_symptoms[] = [
                    'symptom_id' => $symptom['symptom_id'],
                    'condition_value' => $symptom['condition_value']
                ];
            }
        }
    }
}

// Ambil nama gejala dan kondisi
$symptom_names = [];
$condition_names = [];

if (!empty($selected_symptoms)) {
    // Get symptom names
    $symptom_ids = array_column($selected_symptoms, 'symptom_id');
    $symptom_ids_str = implode(',', $symptom_ids);
    $query_symptom_names = "SELECT id, symptom_name FROM symptoms WHERE id IN ($symptom_ids_str)";
    $result_symptom_names = $connection->query($query_symptom_names);
    while ($row = $result_symptom_names->fetch_assoc()) {
        $symptom_names[$row['id']] = $row['symptom_name'];
    }
    
    // Get condition names
    $query_conditions = "SELECT condition_value, condition_name FROM conditions ORDER BY condition_value";
    $result_conditions = $connection->query($query_conditions);
    while ($row = $result_conditions->fetch_assoc()) {
        $condition_names[$row['condition_value']] = $row['condition_name'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Konsultasi - <?= format_indonesian_time($consultation_data['consultation_date']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif, sans-serif;
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
        .symptoms-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        .symptom-tag {
            background-color: #e9ecef;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 14px;
            border: 1px solid #ddd;
        }
        .disease-detail {
            margin-top: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        .main-disease-highlight {
            background-color: #fff3cd;
            padding: 15px;
            border-left: 4px solid #ffc107;
            margin-bottom: 20px;
        }
        .accuracy-high { color: #dc3545; font-weight: bold; }
        .accuracy-medium { color: #fd7e14; font-weight: bold; }
        .accuracy-low { color: #6c757d; }
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
        <h3>Informasi Konsultasi</h3>
        <table class="info-table">
            <tr>
                <td>Nama Pasien</td>
                <td>: <?= htmlspecialchars($consultation_data['user_name']); ?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td>: <?= htmlspecialchars($consultation_data['user_email']); ?></td>
            </tr>
            <tr>
                <td>Tanggal Konsultasi</td>
                <td>: <?= format_indonesian_time($consultation_data['consultation_date']); ?></td>
            </tr>
            <tr>
                <td>ID Konsultasi</td>
                <td>: #<?= str_pad($consultation_id, 6, '0', STR_PAD_LEFT); ?></td>
            </tr>
        </table>
    </div>

    <?php if ($main_disease): ?>
    <div class="main-disease-highlight">
        <h4>Diagnosis Utama</h4>
        <p><strong><?= htmlspecialchars($main_disease['disease_name']); ?></strong> dengan tingkat keyakinan <strong class="accuracy-high"><?= format_percentage($main_disease['accuracy']); ?></strong></p>
    </div>
    <?php endif; ?>

    <div class="info-section">
        <h3>Gejala yang Dilaporkan</h3>
        <?php if (!empty($selected_symptoms)): ?>
            <div class="table-responsive">
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Gejala</th>
                            <th>Nama Gejala</th>
                            <th>Kondisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach ($selected_symptoms as $symptom): 
                            $symptom_id = $symptom['symptom_id'];
                            $condition_value = $symptom['condition_value'];
                            
                            // Cari kondisi yang sesuai
                            $condition_name = 'Tidak Diketahui';
                            foreach ($condition_names as $value => $name) {
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
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="no-data">Tidak ada gejala yang tercatat</div>
        <?php endif; ?>
    </div>

    <div class="info-section">
        <h3>Hasil Diagnosis</h3>
        <?php if ($main_disease): ?>
            <table class="results-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kode Penyakit</th>
                        <th>Penyakit</th>
                        <th>Tingkat Keyakinan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    // Penyakit utama
                    $accuracy_class = $main_disease['accuracy'] >= 0.7 ? 'accuracy-high' : 
                                    ($main_disease['accuracy'] >= 0.4 ? 'accuracy-medium' : 'accuracy-low');
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td>P<?= sprintf("%03d", $main_disease['disease_id']); ?></td>
                        <td><?= htmlspecialchars($main_disease['disease_name']); ?></td>
                        <td class="<?= $accuracy_class; ?>"><?= format_percentage($main_disease['accuracy']); ?></td>
                        <td><strong>Diagnosis Utama</strong></td>
                    </tr>
                    
                    <?php foreach ($other_diseases as $disease): 
                        $accuracy_class = $disease['accuracy'] >= 0.7 ? 'accuracy-high' : 
                                        ($disease['accuracy'] >= 0.4 ? 'accuracy-medium' : 'accuracy-low');
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td>P<?= sprintf("%03d", $disease['disease_id']); ?></td>
                        <td><?= htmlspecialchars($disease['disease_name']); ?></td>
                        <td class="<?= $accuracy_class; ?>"><?= format_percentage($disease['accuracy']); ?></td>
                        <td>Kemungkinan Lain</td>
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
        <h3>Detail Penyakit Utama: <?= htmlspecialchars($main_disease['disease_name']); ?></h3>
        <div class="description">
            <?= $main_disease['description']; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="info-section">
        <h3>Interpretasi Hasil</h3>
        <div style="padding: 15px; background-color: #e3f2fd; border-radius: 5px;">
            <?php if ($main_disease): ?>
                <?php if ($main_disease['accuracy'] >= 0.8): ?>
                    <p><strong>Tingkat Keyakinan Tinggi (≥80%):</strong> Hasil menunjukkan kemungkinan besar menderita <?= htmlspecialchars($main_disease['disease_name']); ?>. Disarankan untuk segera berkonsultasi dengan tenaga medis profesional.</p>
                <?php elseif ($main_disease['accuracy'] >= 0.6): ?>
                    <p><strong>Tingkat Keyakinan Sedang (60-79%):</strong> Terdapat kemungkinan menderita <?= htmlspecialchars($main_disease['disease_name']); ?>. Perlu pemeriksaan lebih lanjut oleh dokter untuk memastikan diagnosis.</p>
                <?php elseif ($main_disease['accuracy'] >= 0.4): ?>
                    <p><strong>Tingkat Keyakinan Rendah (40-59%):</strong> Ada indikasi kemungkinan <?= htmlspecialchars($main_disease['disease_name']); ?>, namun perlu evaluasi gejala yang lebih detail dan pemeriksaan medis.</p>
                <?php else: ?>
                    <p><strong>Tingkat Keyakinan Sangat Rendah (<40%):</strong> Gejala yang dilaporkan tidak menunjukkan pola yang jelas untuk diagnosis <?= htmlspecialchars($main_disease['disease_name']); ?>. Disarankan konsultasi langsung dengan dokter.</p>
                <?php endif; ?>
            <?php else: ?>
                <p>Tidak dapat menentukan diagnosis berdasarkan gejala yang dilaporkan. Silakan berkonsultasi dengan tenaga medis profesional.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="footer">
        <p><strong>DISCLAIMER:</strong> Hasil diagnosis ini hanya sebagai referensi awal dan tidak menggantikan konsultasi medis profesional. 
        Untuk diagnosis yang akurat dan penanganan yang tepat, silakan berkonsultasi dengan dokter atau tenaga medis yang berkompeten.</p>
        <p><strong>PENTING:</strong> Sistem pakar ini menggunakan metode Certainty Factor untuk menghitung tingkat keyakinan berdasarkan gejala yang dilaporkan. 
        Akurasi hasil sangat bergantung pada keakuratan informasi gejala yang diberikan.</p>
        <p>Dicetak pada: <?= date('d/m/Y H:i:s'); ?> | ID Konsultasi: #<?= str_pad($consultation_id, 6, '0', STR_PAD_LEFT); ?></p>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background-color: #435ebe; color: white; border: none; border-radius: 5px; cursor: pointer; margin-right: 10px;">
            <i class="bi bi-printer"></i> Cetak PDF
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background-color: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer;">
            <i class="bi bi-x-circle"></i> Tutup
        </button>
    </div>

    <script>
        // Auto print saat halaman dimuat (opsional - uncomment jika diperlukan)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>