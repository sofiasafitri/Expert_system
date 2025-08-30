<div id="sidebar">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="sidebar-toggler  x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <div class="d-flex justify-content-center align-items-center gap-2">
                <img src="<?= get_image_url(base_url('assets/img/static/logo-light-an.png')); ?>" alt="Logo" class="rounded-2 theme-logo" height="52" width="52">
                <h5 class="mb-0">Sistem Pakar<br>Anoreksia Nervosa</h5>
            </div>

            <div class="divider">
                <div class="divider-text fw-semibold">Menu</div>
            </div>

            <ul class="menu">
                <li
                    class="sidebar-item <?= ($page_name === 'Dashboard') ? 'active' : ''; ?> ">
                    <a href="<?= base_url('dashboard/index.php'); ?>" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li
                    class="sidebar-item <?= ($page_name === 'Penyakit') ? 'active' : ''; ?> ">
                    <a href="<?= base_url('dashboard/disease/show.php'); ?>" class='sidebar-link'>
                        <i class="bi bi-virus2"></i>
                        <span>Penyakit</span>
                    </a>
                </li>
                <li
                    class="sidebar-item <?= ($page_name === 'Gejala') ? 'active' : ''; ?> ">
                    <a href="<?= base_url('dashboard/symptom/show.php'); ?>" class='sidebar-link'>
                        <i class="bi bi-heart-pulse-fill"></i>
                        <span>Gejala</span>
                    </a>
                </li>
                <li
                    class="sidebar-item <?= ($page_name === 'Basis Pengetahuan') ? 'active' : ''; ?> ">
                    <a href="<?= base_url('dashboard/knowledge-base/show.php'); ?>" class='sidebar-link'>
                        <i class="bi bi-lightbulb-fill"></i>
                        <span>Basis Pengetahuan</span>
                    </a>
                </li>
                <li
                    class="sidebar-item <?= ($page_name === 'Riwayat Konsultasi') ? 'active' : ''; ?> ">
                    <a href="<?= base_url('dashboard/consultation-history/show.php'); ?>" class='sidebar-link'>
                        <i class="bi bi-file-earmark-fill"></i>
                        <span>Riwayat Konsultasi</span>
                    </a>
                </li>
                <li
                    class="sidebar-item <?= ($page_name === 'Pesan') ? 'active' : ''; ?> ">
                    <a href="<?= base_url('dashboard/message/show.php'); ?>" class='sidebar-link'>
                        <i class="bi bi-chat-left-dots-fill"></i>
                        <span>Pesan</span>
                    </a>
                </li>
                <li
                    class="sidebar-item <?= ($page_name === 'Testimoni') ? 'active' : ''; ?> ">
                    <a href="<?= base_url('dashboard/testimonial/show.php'); ?>" class='sidebar-link'>
                        <i class="bi bi-chat-left-quote-fill"></i>
                        <span>Testimoni</span>
                    </a>
                </li>
                <li
                    class="sidebar-item <?= ($page_name === 'Kelola Pengguna') ? 'active' : ''; ?> ">
                    <a href="<?= base_url('dashboard/manage-user/show.php'); ?>" class='sidebar-link'>
                        <i class="bi bi-people-fill"></i>
                        <span>Kelola Pengguna</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>