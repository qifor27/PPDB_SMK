<?php

/**
 * Super Admin - Dashboard
 */
$pageTitle = 'Dashboard';
require_once 'includes/header.php';

// Stats by jalur
$statsByJalur = db()->fetchAll(
    "SELECT j.nama_jalur, j.kode_jalur, COUNT(p.id_pendaftaran) as total
     FROM tb_jalur j
     LEFT JOIN tb_pendaftaran p ON p.id_jalur = j.id_jalur
     GROUP BY j.id_jalur"
);

// Stats by status
$statsByStatus = db()->fetchAll("SELECT status, COUNT(*) as total FROM tb_pendaftaran GROUP BY status");
$statusMap = [];
foreach ($statsByStatus as $s) $statusMap[$s['status']] = $s['total'];

// Stats by school
$statsBySchool = db()->fetchAll(
    "SELECT s.nama_sekolah, COUNT(p.id_pendaftaran) as total
     FROM tb_smk s
     LEFT JOIN tb_pendaftaran p ON p.id_smk_pilihan1 = s.id_smk
     GROUP BY s.id_smk
     ORDER BY total DESC
     LIMIT 10"
);

// Recent pendaftar
$recentPendaftar = db()->fetchAll(
    "SELECT p.*, s.nama_lengkap, s.nisn, j.kode_jalur, smk.nama_sekolah
     FROM tb_pendaftaran p
     JOIN tb_siswa s ON p.id_siswa = s.id_siswa
     JOIN tb_jalur j ON p.id_jalur = j.id_jalur
     JOIN tb_smk smk ON p.id_smk_pilihan1 = smk.id_smk
     ORDER BY p.tanggal_daftar DESC LIMIT 10"
);
?>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon primary"><i class="bi bi-building"></i></div>
            <div class="stat-info">
                <h3><?= $totalSMK ?></h3>
                <p>Total SMK</p>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon info"><i class="bi bi-people-fill"></i></div>
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
            <div class="stat-icon primary"><i class="bi bi-person-badge"></i></div>
            <div class="stat-info">
                <h3><?= $totalAdminSekolah ?></h3>
                <p>Admin Sekolah</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Charts -->
    <div class="col-lg-8">
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Per Jalur</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="chartJalur" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Per Status</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="chartStatus" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Pendaftar -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i>Pendaftar Terbaru</h6>
                <a href="pendaftar.php" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>No. Daftar</th>
                                <th>Nama</th>
                                <th>SMK Tujuan</th>
                                <th>Jalur</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentPendaftar as $p): ?>
                                <tr>
                                    <td><code><?= $p['nomor_pendaftaran'] ?></code></td>
                                    <td><?= htmlspecialchars($p['nama_lengkap']) ?></td>
                                    <td class="small"><?= htmlspecialchars(truncate($p['nama_sekolah'], 30)) ?></td>
                                    <td><?= getJalurBadge($p['kode_jalur']) ?></td>
                                    <td><?= getStatusBadge($p['status']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Top Schools -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-trophy me-2"></i>SMK Terpopuler</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php foreach ($statsBySchool as $i => $school): ?>
                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center">
                            <span class="small"><?= $i + 1 ?>. <?= htmlspecialchars(truncate($school['nama_sekolah'], 25)) ?></span>
                            <span class="badge bg-primary"><?= $school['total'] ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-lightning me-2"></i>Aksi Cepat</h6>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="sekolah.php" class="btn btn-outline-primary">
                    <i class="bi bi-plus me-2"></i>Tambah SMK
                </a>
                <a href="admin-sekolah.php" class="btn btn-outline-primary">
                    <i class="bi bi-person-plus me-2"></i>Tambah Admin
                </a>
                <a href="pengaturan.php" class="btn btn-outline-primary">
                    <i class="bi bi-gear me-2"></i>Pengaturan
                </a>
                <a href="laporan.php" class="btn btn-primary">
                    <i class="bi bi-download me-2"></i>Download Laporan
                </a>
            </div>
        </div>
    </div>
</div>

<?php
$jalurLabels = json_encode(array_column($statsByJalur, 'nama_jalur'));
$jalurData = json_encode(array_column($statsByJalur, 'total'));
$statusLabels = json_encode(array_keys($statusMap));
$statusData = json_encode(array_values($statusMap));

$extraScripts = <<<EOT
<script>
new Chart(document.getElementById('chartJalur'), {
    type: 'doughnut',
    data: {
        labels: {$jalurLabels},
        datasets: [{
            data: {$jalurData},
            backgroundColor: ['#8B5CF6', '#F59E0B', '#10B981', '#3B82F6']
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom', labels: { color: '#94A3B8' } } }
    }
});

new Chart(document.getElementById('chartStatus'), {
    type: 'bar',
    data: {
        labels: {$statusLabels},
        datasets: [{
            data: {$statusData},
            backgroundColor: '#10B981',
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