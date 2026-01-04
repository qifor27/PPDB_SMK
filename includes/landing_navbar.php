<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-ppdb fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="assets/img/sumbar.png" alt="Logo" style="height: 36px;" onerror="this.style.display='none'">
            <span class="brand-text">SPMB SMK</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <i class="bi bi-list fs-4"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto me-3">
                <li class="nav-item">
                    <a class="nav-link active" href="#home">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#statistik">Informasi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#seleksi">Seleksi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#jadwal">Tahapan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#sekolah">SMK</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="perangkingan.php">Perangkingan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#kontak">Kontak</a>
                </li>
            </ul>

            <div class="d-flex gap-2 nav-buttons">
                <?php if (Session::isLoggedIn()): ?>
                    <?php
                    $dashboardUrl = match (Session::getRole()) {
                        ROLE_SUPERADMIN => 'superadmin/',
                        ROLE_ADMIN => 'admin/',
                        default => 'user/'
                    };
                    ?>
                    <a href="<?= $dashboardUrl ?>" class="btn btn-primary btn-sm">
                        Dashboard
                    </a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-primary btn-sm">
                        Masuk
                    </a>
                    <?php if ($isOpen ?? false): ?>
                        <a href="register.php" class="btn btn-primary btn-sm">
                            Daftar
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>