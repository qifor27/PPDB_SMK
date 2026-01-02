<?php

/**
 * PPDB SMK - Halaman Perangkingan Publik
 * Menampilkan ranking pendaftar per sekolah/jurusan/tahap
 */
require_once 'config/database.php';
require_once 'config/functions.php';
require_once 'config/scoring.php';

$pageTitle = 'Perangkingan';

// Get filters
$filterSekolah = $_GET['sekolah'] ?? '';
$filterJurusan = $_GET['jurusan'] ?? '';
$filterTahap = $_GET['tahap'] ?? '';

// Get all SMK for filter dropdown
$smkList = db()->fetchAll("SELECT id_smk, nama_sekolah FROM tb_smk ORDER BY nama_sekolah");

// Get jurusan if sekolah selected
$jurusanList = [];
if ($filterSekolah) {
    $jurusanList = db()->fetchAll(
        "SELECT id_program, nama_kejuruan FROM tb_kejuruan WHERE id_smk = ? ORDER BY nama_kejuruan",
        [$filterSekolah]
    );
}

// Get ranking data
$rankingData = [];
$schoolInfo = null;

if ($filterSekolah) {
    $schoolInfo = db()->fetch("SELECT * FROM tb_smk WHERE id_smk = ?", [$filterSekolah]);

    $where = "p.id_smk_pilihan1 = ? AND p.status IN ('submitted', 'verified', 'accepted')";
    $params = [$filterSekolah];

    if ($filterJurusan) {
        $where .= " AND p.id_kejuruan_pilihan1 = ?";
        $params[] = $filterJurusan;
    }
    if ($filterTahap) {
        $where .= " AND p.tahap_pendaftaran = ?";
        $params[] = $filterTahap;
    }

    $rankingData = db()->fetchAll(
        "SELECT p.*, s.nama_lengkap, s.tanggal_lahir, k.nama_kejuruan,
                TIMESTAMPDIFF(MONTH, s.tanggal_lahir, CURDATE()) as umur_bulan
         FROM tb_pendaftaran p
         JOIN tb_siswa s ON p.id_siswa = s.id_siswa
         LEFT JOIN tb_kejuruan k ON p.id_kejuruan_pilihan1 = k.id_program
         WHERE $where
         ORDER BY p.nilai_akumulasi DESC, umur_bulan DESC, p.tanggal_daftar ASC",
        $params
    );
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - PPDB SMK</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
    <style>
        .hero-ranking {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 0 40px;
        }

        .filter-card {
            margin-top: -40px;
            position: relative;
            z-index: 10;
        }

        .rank-badge {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .rank-1 {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: #333;
        }

        .rank-2 {
            background: linear-gradient(135deg, #C0C0C0, #A0A0A0);
            color: #333;
        }

        .rank-3 {
            background: linear-gradient(135deg, #CD7F32, #8B4513);
            color: white;
        }

        .rank-other {
            background: #f0f0f0;
            color: #666;
        }

        .nama-samaran {
            font-weight: 500;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?= SITE_URL ?>">
                <i class="bi bi-mortarboard-fill text-primary me-2"></i>PPDB SMK
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="<?= SITE_URL ?>">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= SITE_URL ?>/info-sekolah.php">Info Sekolah</a></li>
                    <li class="nav-item"><a class="nav-link active" href="<?= SITE_URL ?>/perangkingan.php">Perangkingan</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= SITE_URL ?>/login.php">Masuk</a></li>
                    <li class="nav-item"><a class="btn btn-primary ms-2" href="<?= SITE_URL ?>/register.php">Daftar</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <div class="hero-ranking">
        <div class="container text-center">
            <h1 class="fw-bold mb-2">Perangkingan Calon Peserta Didik</h1>
            <p class="mb-0">Seleksi SMK Se-Sumatera Barat Tahun Ajaran 2025/2026</p>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="container">
        <div class="card filter-card shadow-lg mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Sekolah</label>
                        <select name="sekolah" id="sekolah" class="form-select" required>
                            <option value="">-- Pilih Sekolah --</option>
                            <?php foreach ($smkList as $smk): ?>
                                <option value="<?= $smk['id_smk'] ?>" <?= $filterSekolah == $smk['id_smk'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($smk['nama_sekolah']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Jurusan</label>
                        <select name="jurusan" id="jurusan" class="form-select">
                            <option value="">Semua Jurusan</option>
                            <?php foreach ($jurusanList as $j): ?>
                                <option value="<?= $j['id_program'] ?>" <?= $filterJurusan == $j['id_program'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($j['nama_kejuruan']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tahap</label>
                        <select name="tahap" class="form-select">
                            <option value="">Semua</option>
                            <option value="1" <?= $filterTahap === '1' ? 'selected' : '' ?>>Tahap 1</option>
                            <option value="2" <?= $filterTahap === '2' ? 'selected' : '' ?>>Tahap 2</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-2"></i>Lihat Hasil
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($filterSekolah && $schoolInfo): ?>
            <!-- School Info -->
            <div class="alert alert-info mb-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-1"><?= htmlspecialchars($schoolInfo['nama_sekolah']) ?></h5>
                        <small class="text-muted">Jalur: Seleksi Nilai Rapor |
                            <?= $filterTahap ? "Tahap $filterTahap" : 'Semua Tahap' ?> |
                            Data Perangkingan Bersifat Hasil
                        </small>
                    </div>
                    <div class="col-md-4 text-end">
                        <span class="badge bg-primary fs-6"><?= count($rankingData) ?> Pendaftar</span>
                    </div>
                </div>
            </div>

            <!-- Ranking Table -->
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" width="60">No</th>
                                    <th>Nomor Pendaftaran</th>
                                    <th>Nama Siswa</th>
                                    <th class="text-center">Pilihan Ke</th>
                                    <th class="text-center">Akumulasi</th>
                                    <th class="text-center">Nilai TMB</th>
                                    <th class="text-center">Nilai Rapor</th>
                                    <th class="text-center">Jarak</th>
                                    <th class="text-center">Umur</th>
                                    <th class="text-center">Prestasi</th>
                                    <th class="text-center">Hasil</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($rankingData)): ?>
                                    <tr>
                                        <td colspan="11" class="text-center text-muted py-5">
                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                            Belum ada data perangkingan
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($rankingData as $i => $r):
                                        $rank = $i + 1;
                                        $rankClass = $rank <= 3 ? "rank-$rank" : "rank-other";

                                        // Calculate umur in years.months
                                        $umurTahun = floor($r['umur_bulan'] / 12);
                                        $umurBulan = $r['umur_bulan'] % 12;
                                    ?>
                                        <tr>
                                            <td class="text-center">
                                                <div class="rank-badge <?= $rankClass ?> mx-auto"><?= $rank ?></div>
                                            </td>
                                            <td><code><?= $r['nomor_pendaftaran'] ?></code></td>
                                            <td class="fw-medium"><?= htmlspecialchars($r['nama_lengkap']) ?></td>
                                            <td class="text-center">
                                                <span class="badge bg-primary">1</span>
                                            </td>
                                            <td class="text-center fw-bold text-primary">
                                                <?= $r['nilai_akumulasi'] ? number_format($r['nilai_akumulasi'], 2) : '-' ?>
                                            </td>
                                            <td class="text-center">
                                                <?= $r['nilai_tes'] ? number_format($r['nilai_tes'], 1) : '-' ?>
                                            </td>
                                            <td class="text-center">
                                                <?= $r['bobot_rapor'] ? number_format($r['bobot_rapor'], 1) : '-' ?>
                                            </td>
                                            <td class="text-center">
                                                <?= $r['jarak_ke_sekolah'] ? number_format($r['jarak_ke_sekolah'], 2) . ' km' : '-' ?>
                                            </td>
                                            <td class="text-center">
                                                <small><?= $umurTahun ?> th <?= $umurBulan ?> bln</small>
                                            </td>
                                            <td class="text-center">
                                                <?= $r['skor_prestasi'] > 0 ? "<span class='badge bg-warning text-dark'>{$r['skor_prestasi']}</span>" : '-' ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($r['status'] === 'accepted'): ?>
                                                    <span class="badge bg-success">Diterima</span>
                                                <?php elseif ($r['status'] === 'rejected'): ?>
                                                    <span class="badge bg-danger">Tidak Lolos</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Proses</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php elseif (!$filterSekolah): ?>
            <!-- Empty State -->
            <div class="text-center py-5">
                <i class="bi bi-building fs-1 text-muted mb-3 d-block"></i>
                <h5>Pilih Sekolah untuk Melihat Perangkingan</h5>
                <p class="text-muted">Gunakan filter di atas untuk memilih sekolah tujuan</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; 2025 PPDB SMK Sumatera Barat</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-load jurusan when school changes
        document.getElementById('sekolah').addEventListener('change', function() {
            const smkId = this.value;
            const jurusanSelect = document.getElementById('jurusan');

            jurusanSelect.innerHTML = '<option value="">Semua Jurusan</option>';

            if (smkId) {
                fetch('<?= SITE_URL ?>/api/get-kejuruan.php?smk_id=' + smkId)
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            data.data.forEach(j => {
                                jurusanSelect.innerHTML += `<option value="${j.id_program}">${j.nama_kejuruan}</option>`;
                            });
                        }
                    });
            }
        });
    </script>
</body>

</html>