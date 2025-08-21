<?php
// Menyertakan konfigurasi
include('../config/config.php');

// Memulai sesi
session_start();

// Nama halaman
$page_name = 'Penyakit';
?>

<?php startSection('css'); ?>
<style>
    #search-input::placeholder {
        text-align: center;
    }
</style>
<?php endSection('css'); ?>

<?php startSection('content'); ?>
<section class="page-section" id="pageSection">
    <div class="container px-4">
        <div class="row justify-content-center mb-4" data-aos="zoom-in">
            <div class="col-12">
                <div class="d-flex justify-content-center align-items-center gap-2">
                    <h3 class="mb-0"><?= 'Daftar ' . $page_name; ?></h3>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mb-4" data-aos="fade-up">
            <div class="col-sm-12 col-md-8 col-lg-6">
                <form action="" role="search" id="search-form">
                    <div class="form-group has-icon-left">
                        <div class="position-relative">
                            <input id="search-input" class="form-control form-control-lg rounded-pill me-2" type="search" placeholder="Cari nama penyakit" aria-label="Search" autofocus oninput="searchDisease()">
                            <div class="form-control-icon" style="top: 7px; left: 7px;">
                                <i class="bi bi-search"></i>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row g-4 justify-content-center align-items-center" id="results-container">
            <?php
            // Query untuk mengambil semua data dari tabel diseases
            $query = "SELECT * FROM diseases ORDER BY disease_name";
            $result = $connection->query($query);

            if ($result->num_rows > 0) :
            ?>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12" data-aos="fade-up">
                        <div class="card rounded-4 shadow-sm">
                            <div class="card-content">
                                <div class="ratio ratio-4x3">
                                    <img class="object-fit-cover rounded-top-4" src="<?= get_image_url(base_url('assets/img/disease/' . htmlspecialchars($row['img']))); ?>" alt="<?= htmlspecialchars($row['disease_name']); ?>" loading="lazy" />
                                </div>
                                <div class="card-body">
                                    <h4 class="card-title text-clamp"><?= htmlspecialchars($row['disease_name']); ?></h4>

                                    <p class="card-text"><?= clean_text(nl2br($row['description'])); ?></p>

                                    <hr>

                                    <div class="row justify-content-between align-items-center g-3">
                                        <div class="col-12">
                                            <a href="<?= base_url('user/disease-detail.php?disease-id=') . htmlspecialchars($row['id']); ?>" class="btn btn-lg btn-primary w-100 stretched-link rounded-pill">Detail</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile ?>
            <?php else: ?>
                <div class="col-12" data-aos="fade-up">
                    <img class="rounded-2 object-fit-cover mb-4 mx-auto d-block" src="<?= get_image_url(base_url('assets/img/static/data-not-found.svg')); ?>" alt="Data tidak ditemukan" height="250" width="250">
                    <h6 class="text-center">Data Tidak Ditemukan</h6>
                </div>
            <?php endif ?>
        </div>
    </div>
</section>
<?php endSection('content'); ?>

<?php startSection('script'); ?>
<script>
    function searchDisease() {
        const searchQuery = document.getElementById('search-input').value;
        const resultsContainer = document.getElementById('results-container');

        // Jika input kosong, tidak melakukan pencarian
        if (searchQuery.trim() === "") {
            resultsContainer.innerHTML = ''; // Kosongkan hasil pencarian
            return;
        }

        // Kirim request menggunakan fetch
        fetch(`disease-search.php?query=${encodeURIComponent(searchQuery)}`)
            .then(response => response.text())
            .then(data => {
                // Menampilkan hasil pencarian
                resultsContainer.innerHTML = data;
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
</script>
<?php endSection('script'); ?>

<?php include('template.php') ?>