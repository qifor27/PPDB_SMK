<?php

/**
 * Super Admin - Kuota Global
 */
$pageTitle = 'Kuota Global';
require_once 'includes/header.php';

// Get quota per jalur
$jalurList = getAllJalur();

// Get pendaftar count per jalur
foreach ($jalurList as &$jalur) {
    $jalur['pendaftar'] = db()->count('tb_pendaftaran', 'id_jalur = ?', [$jalur['id_jalur']]);
    $jalur['diterima'] = db()->count('tb_pendaftaran', 'id_jalur = ? AND status = ?', [$jalur['id_jalur'], 'accepted']);
}
unset($jalur);
?>

<div class="row g-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Kuota per Jalur Pendaftaran</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <?php foreach ($jalurList as $jalur): ?>
                        <div class="col-md-6 col-lg-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="bi <?= $jalur['icon'] ?? 'bi-bookmark-star' ?> text-primary" style="font-size: 2rem;"></i>
                                    </div>
                                    <h6 class="fw-bold"><?= htmlspecialchars($jalur['nama_jalur']) ?></h6>
                                    <p class="text-muted small mb-3"><?= htmlspecialchars(truncate($jalur['deskripsi'] ?? '', 60)) ?></p>

                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Kuota</span>
                                        <span class="fw-bold"><?= number_format($jalur['kuota_persen']) ?>%</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Pendaftar</span>
                                        <span class="fw-bold text-primary"><?= $jalur['pendaftar'] ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Diterima</span>
                                        <span class="fw-bold text-success"><?= $jalur['diterima'] ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Grafik Distribusi Kuota</h5>
            </div>
            <div class="card-body">
                <canvas id="chartKuota" height="200"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informasi</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small">Kuota dihitung berdasarkan persentase dari total daya tampung seluruh SMK di Kota Padang.</p>
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span>Total SMK</span>
                    <span class="badge bg-primary"><?= $totalSMK ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Total Pendaftar</span>
                    <span class="badge bg-success"><?= $totalPendaftar ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$jalurLabels = json_encode(array_column($jalurList, 'nama_jalur'));
$jalurPendaftar = json_encode(array_column($jalurList, 'pendaftar'));
$jalurDiterima = json_encode(array_column($jalurList, 'diterima'));

$extraScripts = <<<EOT
<script>
new Chart(document.getElementById('chartKuota'), {
    type: 'bar',
    data: {
        labels: $jalurLabels,
        datasets: [
            {
                label: 'Pendaftar',
                data: $jalurPendaftar,
                backgroundColor: '#8B5CF6',
                borderRadius: 8
            },
            {
                label: 'Diterima',
                data: $jalurDiterima,
                backgroundColor: '#10B981',
                borderRadius: 8
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
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