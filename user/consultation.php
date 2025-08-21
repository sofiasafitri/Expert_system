<?php
// Menyertakan konfigurasi
include('../config/config.php');

// Memulai sesi
session_start();

// Nama halaman
$page_name = 'Konsultasi';
?>

<?php startSection('css'); ?>
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

        <div class="row g-4 justify-content-center" data-aos="fade-up">
            <?php if (isset($_SESSION['email'])): ?>
                <div class="col-lg-3">
                    <div class="card mb-0">
                        <div class="card-body px-4 py-4-5">
                            <h5 class="text-center mb-4">Pengguna Aktif</h5>

                            <?php $fullname_session = isset($_SESSION['fullname']) ? htmlspecialchars($_SESSION['fullname']) : ''; ?>
                            <?php $email_session = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>

                            <a href="<?= base_url('user/profile.php'); ?>">
                                <div class="d-flex justify-content-center align-items-center gap-2 mb-4">
                                    <img src="https://avatar.iran.liara.run/username?username=<?= urlencode($fullname_session); ?>" class="rounded-circle" alt="<?= $fullname_session; ?>" height="64" width="64">
                                </div>
                                <h6 class="text-center mb-1 text-uppercase"><?= $fullname_session; ?></h6>
                                <h6 class="text-center mb-0 fst-italic"><?= $email_session; ?></h6>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif ?>
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <?php if (!isset($_SESSION['email'])): ?>
                            <div class="alert alert-light-warning text-center mb-4" role="alert">
                                <p>Untuk memulai konsultasi, silakan masuk ke sistem menggunakan akun Anda. Terima kasih!</p>
                            </div>
                        <?php endif ?>

                        <div class="accordion mb-4" id="accordionFlushExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                        Petunjuk Kosultasi
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <h6 class="fw-bold">Mohon diperhatikan</h6>
                                        <ol class="mb-3">
                                            <li>Pilih kondisi yang sesuai dengan gejala yang dialami.</li>
                                            <li>Kondisi yang dapat dipilih adalah:
                                                <ul>
                                                    <li><strong>Tidak Tahu</strong>: Gejala tidak dialami sama sekali.</li>
                                                    <li><strong>Tidak Yakin</strong>: Tidak yakin apakah gejala tersebut ada atau tidak.</li>
                                                    <li><strong>Mungkin Ya</strong>: Gejala bisa saja ada, tetapi tidak pasti.</li>
                                                    <li><strong>Kemungkinan Besar Ya</strong>: Gejala sangat mungkin ada, namun belum dapat dipastikan.</li>
                                                    <li><strong>Hampir Pasti Ya</strong>: Gejala pasti ada dan sangat jelas.</li>
                                                    <li><strong>Pasti Ya</strong>: Gejala sangat jelas dan dipastikan ada dengan tingkat kepastian yang tinggi.</li>
                                                </ul>
                                            </li>
                                            <li>Setelah memilih kondisi yang sesuai, klik tombol "<b>Mulai Diagnosis</b>" untuk melihat hasil diagnosis.</li>
                                        </ol>

                                        <h6 class="fw-bold">Penjelasan</h6>
                                        <p>
                                            Sistem akan menganalisis gejala yang Anda pilih dan memberikan hasil berupa kemungkinan penyakit beserta tingkat akurasinya.
                                            Penyakit dengan akurasi tertinggi akan ditampilkan sebagai hasil utama beserta deskripsinya, namun Anda juga dapat melihat kemungkinan penyakit lain
                                            yang memiliki kemiripan dengan gejala yang Anda alami.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form action="action.php" method="post">

                            <div class="table-responsive">
                                <table class="table table-hover" id="table">
                                    <thead>
                                        <tr>
                                            <th>Kode Gejala</th>
                                            <th>Nama Gejala</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Query untuk mengambil semua data dari tabel symptoms
                                        $query = "SELECT * FROM symptoms";
                                        $result = $connection->query($query);

                                        while ($row = $result->fetch_assoc()) :
                                        ?>
                                            <tr>
                                                <td><?= 'G' . sprintf("%03d", htmlspecialchars($row['id'])); ?></td>
                                                <td><?= htmlspecialchars($row['symptom_name']); ?></td>
                                                <td style="width: 12rem;">
                                                    <select class="form-select" id="condition_name_<?= $row['id'] ?>" name="condition[<?= $row['id'] ?>]">
                                                        <option value="" selected>-- Pilih Kondisi --</option>
                                                        <?php
                                                        $conditions = $connection->query("SELECT id, condition_name FROM conditions");
                                                        while ($result_condition = $conditions->fetch_assoc()) :
                                                        ?>
                                                            <option value="<?= $result_condition['id']; ?>">
                                                                <?= htmlspecialchars($result_condition['condition_name']); ?>
                                                            </option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </td>
                                            </tr>
                                        <?php endwhile ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-center mt-2">
                                <button type="submit" class="btn btn-lg rounded-pill btn-primary text-white px-3" name="send-consultation">Mulai Diagnosis</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endSection('content'); ?>

<?php startSection('script'); ?>
<?php endSection('script'); ?>

<?php include('template.php') ?>