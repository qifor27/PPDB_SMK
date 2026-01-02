<?php

/**
 * Admin Sekolah - Laporan
 */
$pageTitle = 'Laporan';
require_once 'includes/header.php';

// Get statistics
$stats = [
    'total' => $totalPendaftar,
    'draft' => db()->count('tb_pendaftaran', 'id_smk_pilihan1 = ? AND status = ?', [$smkId, 'draft']),
    'submitted' => db()->count('tb_pendaftaran', 'id_smk_pilihan1 = ? AND status = ?', [$smkId, 'submitted']),
    'verified' => db()->count('tb_pendaftaran', 'id_smk_pilihan1 = ? AND status = ?', [$smkId, 'verified']),
    'accepted' => db()->count('tb_pendaftaran', 'id_smk_pilihan1 = ? AND status = ?', [$smkId, 'accepted']),
    'rejected' => db()->count('tb_pendaftaran', 'id_smk_pilihan1 = ? AND status = ?', [$smkId, 'rejected']),
];

$statsTahap = [
    1 => db()->count('tb_pendaftaran', 'id_smk_pilihan1 = ? AND tahap_pendaftaran = ?', [$smkId, 1]),
    2 => db()->count('tb_pendaftaran', 'id_smk_pilihan1 = ? AND tahap_pendaftaran = ?', [$smkId, 2]),
];

$statsGender = db()->fetchAll(
    "SELECT s.jenis_kelamin, COUNT(*) as total
     FROM tb_pendaftaran p
     JOIN tb_siswa s ON p.id_siswa = s.id_siswa
     WHERE p.id_smk_pilihan1 = ?
     GROUP BY s.jenis_kelamin",
    [$smkId]
);
$genderMap = [];
foreach ($statsGender as $g) $genderMap[$g['jenis_kelamin']] = $g['total'];
?>

<div class="row g-4">
    <!-- Stats Summary -->
    <div class="col-12">
        <div class="row g-3">
            <div class="col-md-2">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="fs-2 fw-bold text-primary"><?= $stats['total'] ?></div>
                        <small class="text-muted">Total</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="fs-2 fw-bold text-secondary"><?= $stats['draft'] ?></div>
                        <small class="text-muted">Draft</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="fs-2 fw-bold text-info"><?= $stats['submitted'] ?></div>
                        <small class="text-muted">Submitted</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="fs-2 fw-bold text-warning"><?= $stats['verified'] ?></div>
                        <small class="text-muted">Verified</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="fs-2 fw-bold text-success"><?= $stats['accepted'] ?></div>
                        <small class="text-muted">Diterima</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="fs-2 fw-bold text-danger"><?= $stats['rejected'] ?></div>
                        <small class="text-muted">Ditolak</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Statistik Status Pendaftaran</h5>
            </div>
            <div class="card-body">
                <canvas id="chartStatus" height="200"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Per Tahap</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <span>Tahap 1</span>
                    <span class="badge bg-primary"><?= $statsTahap[1] ?></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Tahap 2</span>
                    <span class="badge bg-warning text-dark"><?= $statsTahap[2] ?></span>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-gender-ambiguous me-2"></i>Jenis Kelamin</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <span>Laki-laki</span>
                    <span class="badge bg-info"><?= $genderMap['L'] ?? 0 ?></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Perempuan</span>
                    <span class="badge bg-pink"><?= $genderMap['P'] ?? 0 ?></span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-download me-2"></i>Download Laporan</h6>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="export.php?type=pendaftar" class="btn btn-outline-primary">
                    <i class="bi bi-file-excel me-2"></i>Export Data Pendaftar
                </a>
                <a href="export.php?type=rekap" class="btn btn-outline-primary">
                    <i class="bi bi-file-pdf me-2"></i>Rekap Pendaftaran
                </a>
            </div>
        </div>
    </div>
</div>

<?php
$extraScripts = <<<EOT
<script>
new Chart(document.getElementById('chartStatus'), {
    type: 'bar',
    data: {
        labels: ['Draft', 'Submitted', 'Verified', 'Diterima', 'Ditolak'],
        datasets: [{
            label: 'Jumlah',
            data: [{$stats['draft']}, {$stats['submitted']}, {$stats['verified']}, {$stats['accepted']}, {$stats['rejected']}],
            backgroundColor: ['#6c757d', '#17a2b8', '#ffc107', '#28a745', '#dc3545'],
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
            x: { grid: { display: false } }
        }
    }
});
</script>
EOT;
require_once 'includes/footer.php';
?>