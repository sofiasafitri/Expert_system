<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $page_name; ?></title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= get_image_url(base_url('assets/img/static/logo-dark-theme.png')); ?>" type="image/png">
    <link rel="apple-touch-icon" href="<?= get_image_url(base_url('assets/img/static/logo-dark-theme.png')); ?>">

    <!-- Main CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css'); ?>">

    <!-- Another CSS -->
    <?php yieldSection('css'); ?>
</head>

<body class="d-flex justify-content-center align-items-center" style="height: 100vh;">
    <!-- Init theme -->
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/static/js/initTheme.js"></script>
    <script>
        // Fungsi untuk mengganti logo berdasarkan tema
        function updateThemeLogo() {
            const themeLogoElements = document.querySelectorAll('.theme-logo'); // Ambil semua elemen logo
            const currentTheme = document.documentElement.getAttribute('data-bs-theme');

            themeLogoElements.forEach(logo => {
                if (currentTheme === 'dark') {
                    logo.src = "<?= get_image_url(base_url('assets/img/static/logo-dark-theme.png')); ?>";
                } else {
                    logo.src = "<?= get_image_url(base_url('assets/img/static/logo-light-theme.png')); ?>";
                }
            });
        }

        // Jalankan fungsi saat halaman dimuat
        document.addEventListener("DOMContentLoaded", updateThemeLogo);

        // Event listener jika tema berubah
        const observer = new MutationObserver(updateThemeLogo);
        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['data-bs-theme']
        });
    </script>
    <!-- End of init theme -->

    <!-- Content -->
    <div id="error">
        <!-- Main content -->
        <?php yieldSection('content'); ?>
        <!-- End of content -->
    </div>
    <!-- End of content -->

    <!-- Main JS -->
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/static/js/components/dark.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/js/app.js"></script>

    <!-- Another JS -->
    <?php yieldSection('script'); ?>
</body>

</html>