<?php
// Get contact settings from database for footer
$footerPhone = getPengaturan('contact_phone', '0751-123456');
$footerEmail = getPengaturan('contact_email', 'spmb@smkpadang.id');
$footerAddress = getPengaturan('contact_address', 'Jl. Pendidikan No. 1, Padang');
$footerFacebook = getPengaturan('social_facebook', '');
$footerInstagram = getPengaturan('social_instagram', '');
$footerYoutube = getPengaturan('social_youtube', '');
?>
<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="footer-brand">
                    <img src="assets/img/sumbar.png" alt="Logo Sumbar" style="height: 36px; margin-right: 10px;">
                    SPMB SMK
                </div>
                <p class="footer-desc">
                    Sistem Penerimaan Murid Baru SMK Kota Padang.
                    Mendukung pendidikan berkualitas untuk generasi Indonesia.
                </p>
                <div class="social-links mt-4">
                    <?php if ($footerFacebook): ?>
                        <a href="<?= htmlspecialchars($footerFacebook) ?>" class="social-link" target="_blank"><i class="bi bi-facebook"></i></a>
                    <?php endif; ?>
                    <?php if ($footerInstagram): ?>
                        <a href="<?= htmlspecialchars($footerInstagram) ?>" class="social-link" target="_blank"><i class="bi bi-instagram"></i></a>
                    <?php endif; ?>
                    <?php if ($footerYoutube): ?>
                        <a href="<?= htmlspecialchars($footerYoutube) ?>" class="social-link" target="_blank"><i class="bi bi-youtube"></i></a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-6 col-lg-2">
                <h6 class="footer-title">Menu</h6>
                <ul class="footer-links">
                    <li><a href="#home">Beranda</a></li>
                    <li><a href="#statistik">Statistik</a></li>
                    <li><a href="#seleksi">Jadwal Seleksi</a></li>
                    <li><a href="#jadwal">Tahapan</a></li>
                </ul>
            </div>

            <div class="col-6 col-lg-2">
                <h6 class="footer-title">Informasi</h6>
                <ul class="footer-links">
                    <li><a href="faq.php">FAQ</a></li>
                    <li><a href="syarat.php">Persyaratan</a></li>
                    <li><a href="panduan.php">Panduan</a></li>
                    <li><a href="kontak.php">Kontak</a></li>
                </ul>
            </div>

            <div class="col-lg-4">
                <h6 class="footer-title">Kontak</h6>
                <ul class="footer-links">
                    <li><i class="bi bi-geo-alt me-2 text-primary"></i><?= htmlspecialchars($footerAddress) ?></li>
                    <li><i class="bi bi-telephone me-2 text-primary"></i><?= htmlspecialchars($footerPhone) ?></li>
                    <li><i class="bi bi-envelope me-2 text-primary"></i><?= htmlspecialchars($footerEmail) ?></li>
                    <li><i class="bi bi-whatsapp me-2 text-success"></i><?= htmlspecialchars($footerPhone) ?></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p class="mb-0">
                &copy;
                <?= date('Y') ?> SPMB SMK Kota Padang. All rights reserved.
            </p>
        </div>
    </div>
</footer>