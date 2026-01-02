<?php

/**
 * Admin Sekolah - Dashboard
 * Updated: 2026-01-02 - Sesuaikan dengan alur tahap pendaftaran
 */
$pageTitle = 'Dashboard';
require_once 'includes/header.php';

// Get statistics by tahap
$statsByTahap = db()->fetchAll(
    "SELECT 
        COALESCE(tahap_pendaftaran, 1) as tahap,
        COUNT(*) as total
     FROM tb_pendaftaran 
     WHERE id_smk_pilihan1 = ?
     GROUP BY tahap_pendaftaran",
    [$smkId]
);
$tahapMap = [];
foreach ($statsByTahap as $t) $tahapMap[$t['tahap']] = $t['total'];

// Get statistics by status
$statsByStatus = db()->fetchAll(
    "SELECT status, COUNT(*) as total FROM tb_pendaftaran 
     WHERE id_smk_pilihan1 = ? GROUP BY status",
    [$smkId]
);
$statusMap = [];
foreach ($statsByStatus as $s) $statusMap[$s['status']] = $s['total'];

// Get recent pendaftar
$recentPendaftar = db()->fetchAll(
    "SELECT p.*, s.nama_lengkap, s.nisn, k.nama_kejuruan
     FROM tb_pendaftaran p
     JOIN tb_siswa s ON p.id_siswa = s.id_siswa
     LEFT JOIN tb_kejuruan k ON p.id_kejuruan_pilihan1 = k.id_program
     WHERE p.id_smk_pilihan1 = ?
     ORDER BY p.tanggal_daftar DESC LIMIT 5",
    [$smkId]
);

// Get jurusan statistics
$statsByJurusan = db()->fetchAll(
    "SELECT k.nama_kejuruan, COUNT(p.id_pendaftaran) as total
     FROM tb_kejuruan k
     LEFT JOIN tb_pendaftaran p ON p.id_kejuruan_pilihan1 = k.id_program AND p.id_smk_pilihan1 = ?
     WHERE k.id_smk = ?
     GROUP BY k.id_program
     ORDER BY total DESC",
    [$smkId, $smkId]
);
?>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon primary"><i class="bi bi-people-fill"></i></div>
            <div class="stat-info">
                <h3><?= $totalPendaftar ?></h3>
                <p>Total Pendaftar</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon warning"><i class="bi bi-hourglass-split"></i></div>
            <div class="stat-info">
                <h3><?= ($statusMap['submitted'] ?? 0) + ($statusMap['draft'] ?? 0) ?></h3>
                <p>Menunggu Proses</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon info"><i class="bi bi-check-circle"></i></div>
            <div class="stat-info">
                <h3><?= $statusMap['verified'] ?? 0 ?></h3>
                <p>Terverifikasi</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon success"><i class="bi bi-trophy"></i></div>
            <div class="stat-info">
                <h3><?= $statusMap['accepted'] ?? 0 ?></h3>
                <p>Diterima</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Chart -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Statistik per Jurusan</h5>
            </div>
            <div class="card-body">
                <canvas id="chartJurusan" height="200"></canvas>
            </div>
        </div>

        <!-- Recent Pendaftar -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Pendaftar Terbaru</h5>
                <a href="pendaftar.php" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No. Pendaftaran</th>
                                <th>Nama Siswa</th>
                                <th>Jurusan</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentPendaftar as $p): ?>
                                <tr>
                                    <td><a href="detail-siswa.php?id=<?= $p['id_pendaftaran'] ?>"><?= $p['nomor_pendaftaran'] ?></a></td>
                                    <td><?= htmlspecialchars($p['nama_lengkap']) ?></td>
                                    <td><span class="badge bg-primary"><?= htmlspecialchars($p['nama_kejuruan'] ?? '-') ?></span></td>
                                    <td><?= getStatusBadge($p['status']) ?></td>
                                    <td><?= formatDate($p['tanggal_daftar'], 'd M Y') ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($recentPendaftar)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada pendaftar</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Tahap Pendaftaran -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Pendaftar per Tahap</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 p-3 rounded" style="background: rgba(102, 126, 234, 0.1);">
                    <div>
                        <strong>Tahap 1</strong>
                        <div class="small text-muted">1-6 Jan 2026</div>
                    </div>
                    <span class="badge bg-primary fs-5"><?= $tahapMap[1] ?? 0 ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center p-3 rounded" style="background: rgba(245, 158, 11, 0.1);">
                    <div>
                        <strong>Tahap 2</strong>
                        <div class="small text-muted">7-15 Jan 2026</div>
                    </div>
                    <span class="badge bg-warning text-dark fs-5"><?= $tahapMap[2] ?? 0 ?></span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Aksi Cepat</h5>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="verifikasi.php" class="btn btn-primary">
                    <i class="bi bi-check2-square me-2"></i>Verifikasi Dokumen
                    <?php if ($pendingVerif > 0): ?>
                        <span class="badge bg-danger ms-2"><?= $pendingVerif ?></span>
                    <?php endif; ?>
                </a>
                <a href="seleksi.php" class="btn btn-outline-primary">
                    <i class="bi bi-funnel me-2"></i>Input Nilai Tes
                </a>
                <a href="laporan.php" class="btn btn-outline-primary">
                    <i class="bi bi-download me-2"></i>Download Laporan
                </a>
            </div>
        </div>
    </div>
</div>

<?php
$chartLabels = json_encode(array_column($statsByJurusan, 'nama_kejuruan'));
$chartData = json_encode(array_column($statsByJurusan, 'total'));
$extraScripts = <<<EOT
<script>
new Chart(document.getElementById('chartJurusan'), {
    type: 'bar',
    data: {
        labels: {$chartLabels},
        datasets: [{
            label: 'Jumlah Pendaftar',
            data: {$chartData},
            backgroundColor: ['#667eea', '#764ba2', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#06B6D4'],
            borderRadius: 8
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
            y: { grid: { display: false } }
        }
    }
});
</script>
EOT;
require_once 'includes/footer.php';
?>