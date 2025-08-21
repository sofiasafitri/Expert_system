<?php
$contacts = $connection->query("SELECT * FROM site_contacts LIMIT 1");

if ($contacts->num_rows > 0) {
    $result_contacts = $contacts->fetch_assoc();

    $contact_owner      = $result_contacts['owner'];
    $contact_email      = $result_contacts['email'];
    $contact_phone      = $result_contacts['phone'];
    $contact_instagram  = $result_contacts['instagram'];
}
?>


<div class="card rounded-0 m-0">
    <div class="card-body px-0 pb-0 pt-1 border-top">
        <div class="container p-4">
            <div class="row">
                <div class="col-lg-6">
                    <img src="<?= get_image_url(base_url('assets/img/static/logo-light-an.png')); ?>" alt="Logo" class="d-inline-block mb-4" height="64">

                    <div class="d-flex flex-column gap-1 mb-4">
                        <h4 class="text-white mb-0">Sistem Pakar Anoreksia Nervosa</h4>
                        <span>Metode Certainty Factor</span>
                    </div>

                    <div class="d-flex flex-column gap-2 mb-4">
                        <a class="link-footer d-flex gap-3 align-items-center" target="_blank" href="<?= "mailto:" . $contact_email; ?>">
                            <span class="icon"><i class="bi bi-envelope"></i></span>
                            <span><?= $contact_email; ?></span>
                        </a>
                        <a class="link-footer d-flex gap-3 align-items-center" target="_blank" href="<?= "https://wa.me/{$contact_phone}"; ?>">
                            <span class="icon"><i class="bi bi-whatsapp"></i></span>
                            <span><?= $contact_phone; ?></span>
                        </a>
                    
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="row mb-4">
                        <div class="col">
                            <h5 class="text-white mb-2">Menu</h5>

                            <div class="d-flex flex-column gap-2">
                                <a href="<?= base_url('user/index.php'); ?>" class="link-footer">Beranda</a>
                                <a href="<?= base_url('user/about.php'); ?>" class="link-footer">Tentang</a>
                                <a href="<?= base_url('user/disease.php'); ?>" class="link-footer">Penyakit</a>
                                <a href="<?= base_url('user/consultation.php'); ?>" class="link-footer">Konsultasi</a>

                                <?php if (!isset($_SESSION['email'])) : ?>
                                    <a href="<?= base_url('auth/login.php'); ?>" class="link-footer">Masuk</a>
                                <?php elseif (isset($_SESSION['email']) && $_SESSION['role'] === 'Admin') : ?>
                                    <a href="<?= base_url('dashboard/index.php'); ?>" class="link-footer">Dashboard</a>
                                <?php elseif (isset($_SESSION['email']) && $_SESSION['role'] === 'Pengguna') : ?>
                                    <div class="dropdown">
                                        <a class="link-footer dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Akun
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="<?= base_url('user/profile.php'); ?>">Profil</a></li>
                                            <li><a class="dropdown-item" href="<?= base_url('user/consultation-history.php'); ?>">Riwayat Konsultasi</a></li>
                                            <li><a class="dropdown-item btn-logout" href="#">Keluar</a></li>
                                        </ul>
                                    </div>
                                <?php endif ?>
                            </div>
                        </div>

                        <div class="col">
                            <h5 class="text-white mb-2">Ikuti kami</h5>

                            <div class="d-flex flex-wrap gap-2">
                                <?php if (!empty($contact_instagram)): ?>
                                    <a href="<?= htmlspecialchars($contact_instagram); ?>" target="_blank" class="btn btn-sm btn-footer icon">
                                        <i class="bi bi-instagram"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="row justify-content-center align-items-center g-2 border-top pt-3 mt-2">
                        <div class="col-lg-6">
                            <div class="text-lg-start text-center fw-bold">
                                &copy; <?= date('Y'); ?> Sistem Pakar Anoreksia Nervosa
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="text-lg-end text-center fw-bold">
                                Dibuat dengan <span class="text-danger"><i class="bi bi-heart-fill icon-mid"></i></span>
                                oleh <?= $contact_owner; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>