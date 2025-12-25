<?php
/**
 * Info Sekolah - Detail SMK
 */

require_once 'config/database.php';
require_once 'config/functions.php';
require_once 'config/session.php';

$smkId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$smk = getSMKById($smkId);

if (!$smk) {
    header('Location: index.php');
    exit;
}

$kejuruan = getKejuruanBySMK($smkId);
$galeri = db()->fetchAll("SELECT * FROM tb_galeri WHERE id_smk = ? ORDER BY urutan", [$smkId]);

// Get kuota
$kuota = db()->fetchAll(
    "SELECT k.*, j.nama_jalur, j.kode_jalur FROM tb_kuota k
     JOIN tb_jalur j ON k.id_jalur = j.id_jalur
     WHERE k.id_smk = ? AND k.tahun_ajaran = ?",
    [$smkId, getTahunAjaran()]
);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($smk['nama_sekolah']) ?> - <?= SITE_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-ppdb fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-mortarboard-fill text-primary"></i>
                <span>PPDB SMK</span>
            </a>
            <a href="index.php" class="btn btn-dark">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </nav>

    <div style="padding-top: 80px;"></div>

    <!-- Hero -->
    <section class="py-5 bg-dark-alt">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="badge bg-primary mb-3"><?= $smk['npsn'] ?: 'SMK' ?></span>
                    <h1 class="mb-3"><?= htmlspecialchars($smk['nama_sekolah']) ?></h1>
                    <p class="text-muted mb-4">
                        <i class="bi bi-geo-alt me-2"></i><?= htmlspecialchars($smk['alamat']) ?>
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <div class="stat-card">
                            <div class="stat-icon primary"><i class="bi bi-people"></i></div>
                            <div class="stat-info">
                                <h3><?= $smk['jumlah_siswa'] ?></h3>
                                <p>Siswa</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon info"><i class="bi bi-person-workspace"></i></div>
                            <div class="stat-info">
                                <h3><?= $smk['jumlah_guru'] ?></h3>
                                <p>Guru</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon warning"><i class="bi bi-mortarboard"></i></div>
                            <div class="stat-info">
                                <h3><?= count($kejuruan) ?></h3>
                                <p>Jurusan</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-center mt-4 mt-lg-0">
                    <?php if (isPPDBOpen()): ?>
                    <a href="register.php" class="btn btn-primary btn-lg">
                        <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Content -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-8">
                    <!-- Description -->
                    <?php if ($smk['deskripsi']): ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Tentang Sekolah</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-0"><?= nl2br(htmlspecialchars($smk['deskripsi'])) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Kejuruan -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-book me-2"></i>Program Keahlian</h5>
                        </div>
                        <div class="card-body">
                            <?php if (count($kejuruan) > 0): ?>
                            <div class="row g-3">
                                <?php foreach ($kejuruan as $kj): ?>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-3 bg-dark-alt rounded">
                                        <div class="stat-icon primary me-3" style="width:50px;height:50px;font-size:1.25rem;">
                                            <i class="bi bi-mortarboard"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0"><?= htmlspecialchars($kj['nama_kejuruan']) ?></h6>
                                            <small class="text-muted"><?= $kj['kode_kejuruan'] ?></small>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php else: ?>
                            <p class="text-muted mb-0">Data program keahlian belum tersedia.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Gallery -->
                    <?php if (count($galeri) > 0): ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-images me-2"></i>Galeri</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <?php foreach (array_slice($galeri, 0, 6) as $foto): ?>
                                <div class="col-4">
                                    <img src="<?= $foto['foto'] ?>" alt="Galeri" class="img-fluid rounded" style="height:150px;width:100%;object-fit:cover;">
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Map -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Lokasi</h5>
                        </div>
                        <div class="card-body p-0">
                            <div id="map" style="height: 400px;"></div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <!-- Contact -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-telephone me-2"></i>Kontak</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <?php if ($smk['telepon']): ?>
                                <li class="mb-3">
                                    <i class="bi bi-telephone text-primary me-2"></i>
                                    <?= htmlspecialchars($smk['telepon']) ?>
                                </li>
                                <?php endif; ?>
                                <?php if ($smk['email']): ?>
                                <li class="mb-3">
                                    <i class="bi bi-envelope text-primary me-2"></i>
                                    <?= htmlspecialchars($smk['email']) ?>
                                </li>
                                <?php endif; ?>
                                <?php if ($smk['website']): ?>
                                <li class="mb-3">
                                    <i class="bi bi-globe text-primary me-2"></i>
                                    <a href="<?= htmlspecialchars($smk['website']) ?>" target="_blank"><?= htmlspecialchars($smk['website']) ?></a>
                                </li>
                                <?php endif; ?>
                                <?php if ($smk['nama_kepsek']): ?>
                                <li>
                                    <i class="bi bi-person text-primary me-2"></i>
                                    Kepala Sekolah: <?= htmlspecialchars($smk['nama_kepsek']) ?>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Kuota -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Kuota PPDB <?= getTahunAjaran() ?></h5>
                        </div>
                        <div class="card-body">
                            <?php foreach ($kuota as $k): ?>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span><?= getJalurBadge($k['kode_jalur']) ?></span>
                                    <span class="small"><?= $k['terisi'] ?>/<?= $k['kuota'] ?></span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <?php $pct = $k['kuota'] > 0 ? ($k['terisi'] / $k['kuota']) * 100 : 0; ?>
                                    <div class="progress-bar bg-primary" style="width: <?= min($pct, 100) ?>%"></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-bottom pt-0 mt-0 border-0">
                <p class="mb-0">&copy; <?= date('Y') ?> PPDB SMK Kota Padang.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const map = L.map('map').setView([<?= $smk['latitude'] ?>, <?= $smk['longitude'] ?>], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        L.marker([<?= $smk['latitude'] ?>, <?= $smk['longitude'] ?>])
            .addTo(map)
            .bindPopup('<b><?= addslashes($smk['nama_sekolah']) ?></b>').openPopup();
    </script>
</body>
</html>
