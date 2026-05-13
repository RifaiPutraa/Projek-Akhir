<?php // /includes/footer.php ?>

<!-- FOOTER -->
<footer class="bg-dark text-light pt-5 pb-3 mt-5" id="kontak">
    <div class="container">
        <div class="row g-4">

            <!-- Brand + Tentang -->
            <div class="col-md-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <?php $logo_path = __DIR__ . '/../assets/img/logo.png';
                    if (file_exists($logo_path)): ?>
                        <img src="<?= SITE_URL ?>/assets/img/logo.png" alt="Logo" height="40">
                    <?php endif; ?>
                    <h5 class="fw-bold mb-0">
                        <span class="text-primary">RIVV</span> STORE
                    </h5>
                </div>
                <p class="text-light opacity-75 small" id="tentang">
                    RIVV Store adalah platform top up game yang menyediakan layanan cepat,
                    harga terjangkau, dan transaksi yang aman. Kami membantu gamer mendapatkan
                    diamond dan item game favorit dengan proses yang mudah dan terpercaya.
                </p>
            </div>

            <!-- Menu -->
            <div class="col-md-2">
                <h6 class="fw-semibold mb-3">Menu</h6>
                <ul class="list-unstyled small" style="color: #adb5bd;">
                    <li class="mb-1"><a href="<?= SITE_URL ?>" class="text-decoration-none" style="color: #adb5bd;">Beranda</a></li>
                    <li class="mb-1"><a href="<?= SITE_URL ?>/games.php" class="text-decoration-none" style="color: #adb5bd;">Games</a></li>
                    <li class="mb-1"><a href="#tentang" class="text-decoration-none" style="color: #adb5bd;">Tentang</a></li>
                    <li class="mb-1"><a href="#kontak" class="text-decoration-none" style="color: #adb5bd;">Kontak</a></li>
                </ul>
            </div>

            <!-- Kontak -->
            <div class="col-md-3">
                <h6 class="fw-semibold mb-3">Kontak</h6>
                <ul class="list-unstyled small" style="color: #adb5bd;">
                    <li class="mb-2"><i class="bi bi-envelope me-2"></i>rivvstore@email.com</li>
                    <li class="mb-2"><i class="bi bi-telephone me-2"></i>0895-5216-9526</li>
                    <li class="mb-2"><i class="bi bi-geo-alt me-2"></i>Indonesia</li>
                </ul>
            </div>

            <!-- Sosial Media -->
            <div class="col-md-3">
                <h6 class="fw-semibold mb-3">Sosial Media</h6>
                <div class="d-flex gap-2">
                    <a href="#" class="btn btn-sm btn-outline-light"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="btn btn-sm btn-outline-light"><i class="bi bi-whatsapp"></i></a>
                    <a href="#" class="btn btn-sm btn-outline-light"><i class="bi bi-tiktok"></i></a>
                </div>
                <p style="color: #adb5bd;" class="small mt-3">
                    <i class="bi bi-shield-check me-1 text-success"></i>
                    Transaksi 100% Aman
                </p>
            </div>

        </div><!-- /.row -->

        <hr class="border-secondary mt-4">

        <p class="text-center small mb-0" style="color: #adb5bd;">
            &copy; <?= date('Y') ?> RIVV STORE. Top Up Game Cepat, Murah, dan Aman. All Rights Reserved.
        </p>
    </div><!-- /.container -->
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="<?= SITE_URL ?>/assets/js/main.js"></script>
<?= isset($extra_js) ? $extra_js : '' ?>
</body>
</html>