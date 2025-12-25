<?php
/**
 * Admin Sekolah - Dashboard
 */
$pageTitle = 'Dashboard';
require_once 'includes/header.php';

// Get statistics by jalur
$statsByJalur = db()->fetchAll(
    "SELECT j.nama_jalur, j.kode_jalur, COUNT(p.id_pendaftaran) as total
     FROM tb_jalur j
     LEFT JOIN tb_pendaftaran p ON p.id_jalur = j.id_jalur AND p.id_smk_pilihan1 = ?
     GROUP BY j.id_jalur",
    [$smkId]
);

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
    "SELECT p.*, s.nama_lengkap, s.nisn, j.nama_jalur, j.kode_jalur
     FROM tb_pendaftaran p
     JOIN tb_siswa s ON p.id_siswa = s.id_siswa
     JOIN tb_jalur j ON p.id_jalur = j.id_jalur
     WHERE p.id_smk_pilihan1 = ?
     ORDER BY p.tanggal_daftar DESC LIMIT 5",
    [$smkId]
);

// Get kuota
$kuota = db()->fetchAll(
    "SELECT k.*, j.nama_jalur, j.kode_jalur FROM tb_kuota k
     JOIN tb_jalur j ON k.id_jalur = j.id_jalur
     WHERE k.id_smk = ? AND k.tahun_ajaran = ?",
    [$smkId, getTahunAjaran()]
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
                <h3><?= $statusMap['submitted'] ?? 0 ?></h3>
                <p>Menunggu Verifikasi</p>
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
            <div class="stat-icon primary"><i class="bi bi-trophy"></i></div>
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
                <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Statistik per Jalur</h5>
            </div>
            <div class="card-body">
                <canvas id="chartJalur" height="200"></canvas>
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
                    <table class="table table-dark mb-0">
                        <thead>
                            <tr>
                                <th>No. Pendaftaran</th>
                                <th>Nama Siswa</th>
                                <th>Jalur</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentPendaftar as $p): ?>
                            <tr>
                                <td><a href="detail-siswa.php?id=<?= $p['id_pendaftaran'] ?>"><?= $p['nomor_pendaftaran'] ?></a></td>
                                <td><?= htmlspecialchars($p['nama_lengkap']) ?></td>
                                <td><?= getJalurBadge($p['kode_jalur']) ?></td>
                                <td><?= getStatusBadge($p['status']) ?></td>
                                <td><?= formatDate($p['tanggal_daftar'], 'd M Y') ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($recentPendaftar)): ?>
                            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada pendaftar</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Kuota Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Kuota per Jalur</h5>
            </div>
            <div class="card-body">
                <?php foreach ($kuota as $k): ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span><?= $k['nama_jalur'] ?></span>
                        <span class="text-muted"><?= $k['terisi'] ?>/<?= $k['kuota'] ?></span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <?php $pct = $k['kuota'] > 0 ? ($k['terisi'] / $k['kuota']) * 100 : 0; ?>
                        <div class="progress-bar bg-primary" style="width: <?= min($pct, 100) ?>%"></div>
                    </div>
                </div>
                <?php endforeach; ?>
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
                </a>
                <a href="seleksi.php" class="btn btn-outline-primary">
                    <i class="bi bi-funnel me-2"></i>Proses Seleksi
                </a>
                <a href="laporan.php" class="btn btn-outline-primary">
                    <i class="bi bi-download me-2"></i>Download Laporan
                </a>
            </div>
        </div>
    </div>
</div>

<?php 
$chartLabels = json_encode(array_column($statsByJalur, 'nama_jalur'));
$chartData = json_encode(array_column($statsByJalur, 'total'));
$extraScripts = <<<EOT
<script>
new Chart(document.getElementById('chartJalur'), {
    type: 'bar',
    data: {
        labels: {$chartLabels},
        datasets: [{
            label: 'Jumlah Pendaftar',
            data: {$chartData},
            backgroundColor: ['#8B5CF6', '#F59E0B', '#10B981', '#3B82F6'],
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.1)' }, ticks: { color: '#94A3B8' } },
            x: { grid: { display: false }, ticks: { color: '#94A3B8' } }
        }
    }
});
</script>
EOT;
require_once 'includes/footer.php'; 
?>
