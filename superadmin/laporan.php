<?php

/**
 * Super Admin - Laporan Global
 */
$pageTitle = 'Laporan';
require_once 'includes/header.php';

// Get global statistics
$stats = [
    'total_smk' => db()->count('tb_smk'),
    'total_pendaftar' => db()->count('tb_pendaftaran'),
    'total_admin' => db()->count('tb_admin_sekolah'),
    'draft' => db()->count('tb_pendaftaran', 'status = ?', ['draft']),
    'submitted' => db()->count('tb_pendaftaran', 'status = ?', ['submitted']),
    'verified' => db()->count('tb_pendaftaran', 'status = ?', ['verified']),
    'accepted' => db()->count('tb_pendaftaran', 'status = ?', ['accepted']),
    'rejected' => db()->count('tb_pendaftaran', 'status = ?', ['rejected']),
];

?>

<div class="row g-4">
    <!-- Stats Summary -->
    <div class="col-12">
        <div class="row g-3">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="fs-2 fw-bold text-primary"><?= $stats['total_smk'] ?></div>
                        <small class="text-muted">Total SMK</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="fs-2 fw-bold text-success"><?= $stats['total_pendaftar'] ?></div>
                        <small class="text-muted">Total Pendaftar</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="fs-2 fw-bold text-info"><?= $stats['accepted'] ?></div>
                        <small class="text-muted">Diterima</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="fs-2 fw-bold text-warning"><?= $stats['total_admin'] ?></div>
                        <small class="text-muted">Admin Sekolah</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Statistik Status Pendaftaran Global</h5>
            </div>
            <div class="card-body">
                <canvas id="chartStatus" height="200"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-download me-2"></i>Download Laporan</h6>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="export.php?type=pendaftar" class="btn btn-outline-primary">
                    <i class="bi bi-file-excel me-2"></i>Export Semua Pendaftar
                </a>
                <a href="export.php?type=smk" class="btn btn-outline-primary">
                    <i class="bi bi-file-excel me-2"></i>Export Data SMK
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