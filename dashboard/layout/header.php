<header class="card">
    <div class="card-body d-flex justify-content-between align-items-center p-2">
        <!-- Toggle -->
        <div class="d-flex justify-content-between align-items-center gap-3 ps-2">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>

            <div class="vr d-block d-xl-none"></div>

            <div class="theme-toggle d-flex align-items-center">
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
        </div>

        <div class="ms-lg-auto">
            <div class="d-flex gap-2">
                <!-- Notification -->
                <a class="btn border-0 py-1 px-2 position-relative me-1" href="<?= base_url('dashboard/notification/show.php'); ?>">
                    <i class="bi bi-bell-fill fs-4"></i>
                    <span class="notification bg-warning text-white position-absolute rounded top-0 end-0 d-flex justify-content-center align-items-center d-none" style="width: 1.25rem; height: 1.25rem; font-size: .75rem;"></span>
                </a>

                <!-- Profile -->
                <div class="dropdown">
                    <?php $fullname_session = isset($_SESSION['fullname']) ? htmlspecialchars($_SESSION['fullname']) : ''; ?>
                    <button class="btn border-0 dropdown-toggle dropdown-toggle-no-caret" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?= get_image_url('assets/img/static/no-image-placeholder.png' . urlencode($fullname_session)); ?>" class="rounded-circle" alt="<?= $fullname_session; ?>" height="34" width="34">
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom shadow-sm">
                        <li>
                            <h6 class="dropdown-header text-center"><?= $fullname_session; ?></h6>
                        </li>
                        <li><a class="dropdown-item" href="<?= base_url('dashboard/manage-user/profile.php'); ?>"><i class="bi bi-person-circle me-2"></i> Profil</a></li>
                        <li><a class="dropdown-item" href="#" id="btn-logout"><i class="bi bi-box-arrow-in-right me-2"></i> Keluar</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>