<?php $owner = $connection->query("SELECT owner FROM site_contacts LIMIT 1")->fetch_assoc()['owner']; ?>

<footer class="footer row align-items-center mt-auto">
    <div class="col-12 col-md-6">
        <div class="d-flex justify-content-center justify-content-md-start">
            <p class="text-center text-md-start">&copy; <?= date('Y'); ?> Sistem Pakar</p>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="d-flex justify-content-center justify-content-md-end">
            <p class="text-center text-md-end">Dibuat dengan <span class="text-danger"><i class="bi bi-heart-fill icon-mid"></i></span>
                oleh <?= $owner; ?></p>
        </div>
    </div>
</footer>