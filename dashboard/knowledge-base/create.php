<?php
// Menyertakan konfigurasi
include('../../config/config.php');

// Memulai sesi
session_start();

// Cek login dan role
isAdmin($connection);

$page_name = 'Tambah Basis Pengetahuan';
?>

<?php startSection('css'); ?>
<?php endSection('css'); ?>

<?php startSection('content'); ?>
<div class="page-heading">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-6">
            <div class="d-flex justify-content-start align-items-center gap-2">
                <a href="<?= base_url('dashboard/knowledge-base/show.php'); ?>" class="btn btn-sm border-0"><i class="bi bi-arrow-left"></i></a>
                <h3 class="mb-0"><?= $page_name; ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="row justify-content-center">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <?php
                    // Value Form dari session (jika ada error)
                    $disease_id = isset($_SESSION['form_data']['disease_id']) ? htmlspecialchars($_SESSION['form_data']['disease_id']) : '';
                    $symptom_id = isset($_SESSION['form_data']['symptom_id']) ? htmlspecialchars($_SESSION['form_data']['symptom_id']) : '';
                    $mb_value = isset($_SESSION['form_data']['mb_value']) ? htmlspecialchars($_SESSION['form_data']['mb_value']) : '';
                    $md_value = isset($_SESSION['form_data']['md_value']) ? htmlspecialchars($_SESSION['form_data']['md_value']) : '';
                    ?>

                    <form method="post" action="<?= base_url('dashboard/knowledge-base/action.php'); ?>">
                        <div class="row g-4 mb-4">
                            <div class="col-md-12">
                                <label for="disease_id" class="form-label">Penyakit <span class="text-danger">*</span></label>
                                <select class="form-select <?= isset($_SESSION['errors']['disease_id']) ? 'is-invalid' : '' ?>" id="disease_id" name="disease_id">
                                    <option value="" disabled selected>-- Pilih Penyakit --</option>
                                    <?php
                                    $diseases = $connection->query("SELECT id, disease_name FROM diseases");
                                    while ($result_disease = $diseases->fetch_assoc()) :
                                    ?>
                                        <option value="<?= $result_disease['id']; ?>" <?= (isset($_SESSION['form_data']['disease_id']) && $_SESSION['form_data']['disease_id'] == $result_disease['id']) ? 'selected' : ''; ?>>
                                            <?= htmlspecialchars($result_disease['disease_name']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                                <div class="invalid-feedback">
                                    <?= isset($_SESSION['errors']['disease_id']) ? $_SESSION['errors']['disease_id'] : '' ?>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label for="symptom_id" class="form-label">Gejala <span class="text-danger">*</span></label>
                                <select class="form-select <?= isset($_SESSION['errors']['symptom_id']) ? 'is-invalid' : '' ?>" id="symptom_id" name="symptom_id">
                                    <option value="" disabled selected>-- Pilih Gejala --</option>
                                    <?php
                                    $symptoms = $connection->query("SELECT id, symptom_name FROM symptoms");
                                    while ($result_symptom = $symptoms->fetch_assoc()) :
                                    ?>
                                        <option value="<?= $result_symptom['id']; ?>" <?= (isset($_SESSION['form_data']['symptom_id']) && $_SESSION['form_data']['symptom_id'] == $result_symptom['id']) ? 'selected' : ''; ?>>
                                            <?= htmlspecialchars($result_symptom['symptom_name']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                                <div class="invalid-feedback">
                                    <?= isset($_SESSION['errors']['symptom_id']) ? $_SESSION['errors']['symptom_id'] : '' ?>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label for="mb_value" class="form-label">Nilai MB <span class="text-danger">*</span></label>
                                <select class="form-select <?= isset($_SESSION['errors']['mb_value']) ? 'is-invalid' : '' ?>" id="mb_value" name="mb_value">
                                    <option value="" disabled selected>-- Pilih Nilai --</option>
                                    <?php
                                    $mb_values = [0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1.0];
                                    foreach ($mb_values as $value) {
                                        $selected = (isset($_SESSION['form_data']['mb_value']) && $_SESSION['form_data']['mb_value'] == (string)$value) ? 'selected' : '';
                                        echo "<option value=\"$value\" $selected>$value</option>";
                                    }
                                    ?>
                                </select>
                                <div class="invalid-feedback">
                                    <?= isset($_SESSION['errors']['mb_value']) ? $_SESSION['errors']['mb_value'] : '' ?>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label for="md_value" class="form-label">Nilai MD <span class="text-danger">*</span></label>
                                <select class="form-select <?= isset($_SESSION['errors']['md_value']) ? 'is-invalid' : '' ?>" id="md_value" name="md_value">
                                    <option value="" disabled selected>-- Pilih Nilai --</option>
                                    <?php
                                    $md_values = [0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1.0];
                                    foreach ($md_values as $value) {
                                        $selected = (isset($_SESSION['form_data']['md_value']) && $_SESSION['form_data']['md_value'] == (string)$value) ? 'selected' : '';
                                        echo "<option value=\"$value\" $selected>$value</option>";
                                    }
                                    ?>
                                </select>
                                <div class="invalid-feedback">
                                    <?= isset($_SESSION['errors']['md_value']) ? $_SESSION['errors']['md_value'] : '' ?>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn btn-primary text-white px-3" name="create-kb">Simpan</button>
                        </div>
                    </form>

                    <?php
                    unset($_SESSION['errors']);
                    unset($_SESSION['form_data']);
                    ?>
                </div>
            </div>
        </div>
    </section>
</div>
<?php endSection('content'); ?>

<?php startSection('script'); ?>
<?php endSection('script'); ?>

<?php include('../template.php') ?>