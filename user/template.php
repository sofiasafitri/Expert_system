<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sistem Pakar Anoreksia Nervosa <?= ($page_name !== 'Beranda') ? ('| ' . $page_name) : ''; ?></title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= get_image_url(base_url('assets/img/static/logo-dark-an.png')); ?>" type="image/png">
    <link rel="apple-touch-icon" href="<?= get_image_url(base_url('assets/img/static/logo-dark-an.png')); ?>">

    <!-- Main CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/user.css'); ?>">

    <!-- Another CSS -->
    <?php include('layout/AOS/aosCSS.php') ?>

    <?php yieldSection('css'); ?>
</head>

<body>
    <!-- Init theme -->
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/static/js/initTheme.js"></script>
    <script>
        // Function to update the logo based on the current theme (dark or light)
        function updateThemeLogo() {
            const themeLogoElements = document.querySelectorAll('.theme-logo');

            const currentTheme = document.documentElement.getAttribute('data-bs-theme');

            themeLogoElements.forEach(logo => {
                if (currentTheme === 'dark') {
                    logo.src = "<?= get_image_url(base_url('assets/img/static/logo-light-an.png')); ?>";
                } else {
                    logo.src = "<?= get_image_url(base_url('assets/img/static/logo-dark-an.png')); ?>";
                }
            });
        }

        document.addEventListener("DOMContentLoaded", updateThemeLogo);

        const observer = new MutationObserver(updateThemeLogo);
        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['data-bs-theme']
        });
    </script>
    <!-- End of init theme -->

    <!-- Content -->
    <div class="page-container">
        <!-- Header -->
        <header class="fixed-top">
            <?php include('layout/header.php') ?>
        </header>

        <!-- Main content -->
        <main class="main-content">
            <?php yieldSection('content'); ?>
        </main>

        <!-- Footer -->
        <footer class="footer mt-auto">
            <?php include('layout/footer.php') ?>
        </footer>
    </div>
    <!-- End of content -->

    <!-- Scroll to top -->
    <div class="position-fixed bottom-0 end-0 mb-4 me-4 z-1">
        <button class="btn btn-primary icon" id="scrollToTopBtn"><i class="bi bi-chevron-up"></i></button>
    </div>

    <!-- Main JS -->
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/static/js/components/dark.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
    <script src="<?= base_url('assets/js/user.js'); ?>"></script>
    <script>
        <?php if (isset($_SESSION['email']) && ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Pengguna')): ?>
            // Logout button functionality for Admin and Pengguna
            document.addEventListener('DOMContentLoaded', function() {
                const logoutButtons = document.querySelectorAll('.btn-logout');

                logoutButtons.forEach(function(logoutButton) {
                    logoutButton.addEventListener('click', function(e) {
                        e.preventDefault();

                        Swal.fire({
                            title: 'Akhiri Sesi',
                            text: 'Apakah Anda yakin ingin keluar?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#435ebe',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Ya, keluar!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "<?= base_url('auth/logout.php'); ?>";
                            }
                        });
                    });
                });
            });
        <?php endif; ?>

        // Display success, warning, or error popups on page load
        window.onload = function() {
            <?php if (isset($_SESSION['popup-success'])): ?>
                Swal.fire({
                    title: "Sukses!",
                    text: "<?= $_SESSION['popup-success']; ?>",
                    icon: "success",
                });
            <?php unset($_SESSION['popup-success']);
            endif; ?>

            <?php if (isset($_SESSION['popup-warning'])): ?>
                Swal.fire({
                    title: "Peringatan!",
                    text: "<?= $_SESSION['popup-warning']; ?>",
                    icon: "warning",
                });
            <?php unset($_SESSION['popup-warning']);
            endif; ?>

            <?php if (isset($_SESSION['popup-error'])): ?>
                Swal.fire({
                    title: "Error!",
                    text: "<?= $_SESSION['popup-error']; ?>",
                    icon: "error",
                });
            <?php unset($_SESSION['popup-error']);
            endif; ?>
        }
    </script>

    <!-- Another JS -->
    <?php include('layout/AOS/aosJS.php') ?>

    <?php yieldSection('script'); ?>
</body>

</html>