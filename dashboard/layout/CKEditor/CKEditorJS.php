<!-- CKEditor -->
<script type="importmap">
    {
        "imports": {
            "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/42.0.0/ckeditor5.js",
            "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/42.0.0/"
        }
    }
</script>
<script type="module" src="<?= base_url('assets/js/ckeditor.js'); ?>"></script>

<!-- Preview Image -->
<script>
    document.getElementById('img').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const errorImg = document.getElementById('error-img');
        const previewImg = document.getElementById('preview-img');

        // Reset error message
        errorImg.textContent = '';

        // Check if file exists
        if (!file) {
            return;
        }

        // Validate file type
        const validExtensions = ['image/jpg', 'image/jpeg', 'image/png', 'image/webp'];
        if (!validExtensions.includes(file.type)) {
            previewImg.src = '<?= base_url('assets/img/static/no-image-placeholder.png'); ?>';
            errorImg.textContent = 'Format gambar tidak valid. Hanya JPG, JPEG, PNG, dan WEBP yang diperbolehkan.';
            return;
        }

        // Validate file size
        const maxSize = 2 * 1024 * 1024; // 2 MB
        if (file.size > maxSize) {
            previewImg.src = '<?= base_url('assets/img/static/no-image-placeholder.png'); ?>';
            errorImg.textContent = 'Ukuran gambar maksimal 2 MB.';
            return;
        }

        // Create a URL for the image and set it as the src of the preview
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });
</script>