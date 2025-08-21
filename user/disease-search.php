<?php
// Menyertakan konfigurasi
include('../config/config.php');

// Cek apakah ada query pencarian
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Jika query kosong, tampilkan semua data
if ($query == '') {
    $query_string = "SELECT * FROM diseases ORDER BY disease_name";
} else {
    $search_term = "%" . $query . "%";
    $query_string = "SELECT * FROM diseases WHERE disease_name LIKE '$search_term' ORDER BY disease_name";
}

// Eksekusi query
$result = $connection->query($query_string);
?>

<?php if ($result->num_rows > 0) : ?>
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
    <?php endwhile; ?>
<?php else : ?>
    <div class="col-12">
        <img class="rounded-2 object-fit-cover mb-4 mx-auto d-block" src="<?= get_image_url(base_url('assets/img/static/data-not-found.svg')); ?>" alt="Data tidak ditemukan" height="250" width="250">
        <h6 class="text-center">Data Tidak Ditemukan</h6>
    </div>
<?php endif ?>