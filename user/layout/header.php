<div class="container p-4">
    <div class="card border-0 m-0" id="navbar">
        <div class="card-body p-0">
            <nav class="navbar navbar-expand-lg p-2">
                <div class="container px-2">
                    <a class="navbar-brand text-uppercase" href="<?= base_url('user/index.php'); ?>">
                        <div class="d-flex justify-content-start align-items-center gap-2">
                            <div class="navbar-logo-container">
                                <img class="theme-logo navbar-logo" src="<?= get_image_url(base_url('assets/img/static/logo-light-an.png')); ?>" alt="Logo">
                            </div>
                            <div class="d-flex flex-column text-start">
                                <span class="navbar-brand-title fw-bold">Sistem Pakar Anoreksia Nervosa</span>
                                <span class="navbar-brand-subtitle fw-semibold">Metode Certainty Factor</span>
                            </div>
                        </div>
                    </a>
                    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse py-2" id="navbarNav">
                        <ul class="navbar-nav ms-auto text-center">
                            <li class="nav-item">
                                <a class="nav-link <?= ($page_name == 'Beranda') ? 'active' : ''; ?>" href="<?= base_url('user/index.php'); ?>">Beranda</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= ($page_name == 'Tentang') ? 'active' : ''; ?>" href=" <?= base_url('user/about.php'); ?>">Tentang</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= ($page_name == 'Penyakit') ? 'active' : ''; ?>" href=" <?= base_url('user/disease.php'); ?>">Penyakit</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= ($page_name == 'Konsultasi') ? 'active' : ''; ?>" href=" <?= base_url('user/consultation.php'); ?>">Konsultasi</a>
                            </li>
                            <?php if (!isset($_SESSION['email'])) : ?>
                                <li class="nav-item">
                                    <a class="nav-link <?= ($page_name == 'Masuk') ? 'active' : ''; ?>" href="<?= base_url('auth/login.php'); ?>">Masuk</a>
                                </li>
                            <?php elseif (isset($_SESSION['email']) && $_SESSION['role'] === 'Admin') : ?>
                                <!-- Link untuk Admin -->
                                <li class="nav-item">
                                    <a class="nav-link <?= ($page_name == 'Dashboard') ? 'active' : ''; ?>" href="<?= base_url('dashboard/index.php'); ?>">Dashboard</a>
                                </li>
                            <?php elseif (isset($_SESSION['email']) && $_SESSION['role'] === 'Pengguna') : ?>
                                <!-- Link untuk Pengguna Biasa -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle <?= ($page_name == 'Profil' || $page_name == 'Riwayat Konsultasi') ? 'active' : ''; ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Akun
                                    </a>
                                    <ul class="dropdown-menu  px-2">
                                        <li><a class="dropdown-item <?= ($page_name == 'Profil') ? 'active' : ''; ?>" href="<?= base_url('user/profile.php'); ?>">Profil</a></li>
                                        <li><a class="dropdown-item <?= ($page_name == 'Riwayat Konsultasi') ? 'active' : ''; ?>" href="<?= base_url('user/consultation-history.php'); ?>">Riwayat Konsultasi</a></li>
                                        <li><a class="dropdown-item btn-logout" href="#">Keluar</a></li>
                                    </ul>
                                </li>
                            <?php endif; ?>
                        </ul>

                        <div class="vr ms-2 me-3 d-none d-lg-inline"></div>

                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <!-- Theme toggle -->
                                <div class="theme-toggle d-flex justify-content-center justify-content-lg-start mt-2 mt-lg-0 align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                                        role="img" class="iconify iconify--system-uicons" width="20" height="20"
                                        preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                                        <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path
                                                d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2"
                                                opacity=".3"></path>
                                            <g transform="translate(-210 -1)">
                                                <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                                <circle cx="220.5" cy="11.5" r="4"></circle>
                                                <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path>
                                            </g>
                                        </g>
                                    </svg>
                                    <div class="form-check form-switch fs-6 ms-2 me-0">
                                        <input class="form-check-input  me-0" type="checkbox" id="toggle-dark" style="cursor: pointer">
                                        <label class="form-check-label"></label>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                                        role="img" class="iconify iconify--mdi" width="20" height="20" preserveAspectRatio="xMidYMid meet"
                                        viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z">
                                        </path>
                                    </svg>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</div>