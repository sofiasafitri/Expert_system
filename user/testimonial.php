<?php
// Menyertakan konfigurasi
include('../config/config.php');

// Memulai sesi
session_start();

// Nama halaman
$page_name = 'Testimoni';

// Cek login dan role
checkLogin($connection);

// Ambil data pengguna berdasarkan ID User
$user_id = $_SESSION['user_id'];
?>

<?php startSection('css'); ?>
<?php endSection('css'); ?>

<?php startSection('content'); ?>
<section class="page-section" id="pageSection">
    <div class="container px-4">
        <div class="row justify-content-center mb-4" data-aos="zoom-in">
            <div class="col-lg-6">
                <div class="d-flex justify-content-start align-items-center gap-2">
                    <a href="<?= base_url('user/consultation-history.php'); ?>" class="btn btn-sm border-0"><i class="bi bi-arrow-left"></i></a>
                    <h3 class="mb-0"><?= $page_name; ?></h3>
                </div>
            </div>
        </div>

        <div class="row g-4 justify-content-center" data-aos="fade-up">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <?php include('layout/alerts.php') ?>

                        <?php
                        $query = $connection->query("SELECT * FROM testimonials WHERE user_id = '$user_id' LIMIT 1");

                        // Jika data kosong, buat ulasan baru
                        if ($query->num_rows == 0) :
                        ?>
                            <?php
                            // Value Form dari session (jika ada error)
                            $rating = isset($_SESSION['form_data']['rating']) ? htmlspecialchars($_SESSION['form_data']['rating']) : '';
                            $review = isset($_SESSION['form_data']['review']) ? htmlspecialchars($_SESSION['form_data']['review']) : '';
                            ?>

                            <h5 class="text-center mb-4">Buat Testimoni</h5>

                            <form method="post" action="<?= base_url('user/action.php'); ?>">
                                <input type="hidden" name="user_id" value="<?= $user_id; ?>">

                                <div class="row g-4 mb-4">
                                    <!-- Penilaian Section -->
                                    <div class="col-md-12">
                                        <fieldset class="row mb-3">
                                            <legend class="col-form-label col-12 pt-0">Penilaian <span class="text-danger">*</span></legend>
                                            <div class="col-12">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rating" id="inlineRadio1" value="1" <?= (isset($rating) && $rating == 1) || empty($rating) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="inlineRadio1">1</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rating" id="inlineRadio2" value="2" <?= isset($rating) && $rating == 2 ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="inlineRadio2">2</label>
                                                </div>

                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rating" id="inlineRadio3" value="3" <?= isset($rating) && $rating == 3 ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="inlineRadio3">3</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rating" id="inlineRadio4" value="4" <?= isset($rating) && $rating == 4 ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="inlineRadio4">4</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rating" id="inlineRadio5" value="5" <?= isset($rating) && $rating == 5 ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="inlineRadio5">5</label>
                                                </div>
                                                <div class="invalid-feedback">
                                                    <?= isset($_SESSION['errors']['rating']) ? $_SESSION['errors']['rating'] : ''; ?>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>

                                    <!-- Ulasan Section -->
                                    <div class="col-md-12">
                                        <label for="review" class="form-label">Ulasan <span class="text-danger">*</span></label>
                                        <textarea name="review" class="form-control <?= isset($_SESSION['errors']['review']) ? 'is-invalid' : '' ?>" id="review" placeholder="Tulis ulasan Anda" rows="6"><?= $review; ?></textarea>
                                        <div class="invalid-feedback">
                                            <?= isset($_SESSION['errors']['review']) ? $_SESSION['errors']['review'] : ''; ?>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary text-white px-3" name="create-review">Simpan</button>
                            </form>
                            <?php unset($_SESSION['errors']); ?>
                            <?php unset($_SESSION['form_data']); ?>

                        <?php else: ?>
                            <?php
                            $result = $query->fetch_assoc();

                            $testimonial_id = $result['id'];
                            $rating = $result['rating'];
                            $review = $result['review'];
                            ?>

                            <h5 class="text-center mb-4">Perbarui Testimoni</h5>

                            <form method="post" action="<?= base_url('user/action.php'); ?>">
                                <input type="hidden" name="testimonial_id" value="<?= isset($testimonial_id) ? $testimonial_id : ''; ?>">
                                <input type="hidden" name="user_id" value="<?= $user_id; ?>">

                                <div class="row g-4 mb-4">
                                    <!-- Penilaian Section -->
                                    <div class="col-md-12">
                                        <fieldset class="row mb-3">
                                            <legend class="col-form-label col-12 pt-0">Penilaian <span class="text-danger">*</span></legend>
                                            <div class="col-12">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rating" id="inlineRadio1" value="1" <?= isset($rating) && $rating == 1 ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="inlineRadio1">1</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rating" id="inlineRadio2" value="2" <?= isset($rating) && $rating == 2 ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="inlineRadio2">2</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rating" id="inlineRadio3" value="3" <?= isset($rating) && $rating == 3 ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="inlineRadio3">3</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rating" id="inlineRadio4" value="4" <?= isset($rating) && $rating == 4 ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="inlineRadio4">4</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="rating" id="inlineRadio5" value="5" <?= isset($rating) && $rating == 5 ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="inlineRadio5">5</label>
                                                </div>
                                                <div class="invalid-feedback">
                                                    <?= isset($_SESSION['errors']['rating']) ? $_SESSION['errors']['rating'] : ''; ?>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>

                                    <!-- Ulasan Section -->
                                    <div class="col-md-12">
                                        <label for="review" class="form-label">Ulasan <span class="text-danger">*</span></label>
                                        <textarea name="review" class="form-control <?= isset($_SESSION['errors']['review']) ? 'is-invalid' : '' ?>" id="review" placeholder="Masukkan ulasan Anda" rows="6"><?= isset($review) ? $review : ''; ?></textarea>
                                        <div class="invalid-feedback">
                                            <?= isset($_SESSION['errors']['review']) ? $_SESSION['errors']['review'] : ''; ?>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary text-white px-3" name="update-review">Simpan</button>
                            </form>

                            <?php unset($_SESSION['errors']); ?>

                        <?php endif ?>
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