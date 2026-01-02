<?php

/**
 * Admin Sekolah - Data Jurusan
 */
$pageTitle = 'Data Jurusan';
require_once 'includes/header.php';

// Get jurusan with pendaftar count
$jurusanList = db()->fetchAll(
    "SELECT k.*, 
        (SELECT COUNT(*) FROM tb_pendaftaran p WHERE p.id_kejuruan_pilihan1 = k.id_program AND p.id_smk_pilihan1 = ?) as pendaftar_pilihan1,
        (SELECT COUNT(*) FROM tb_pendaftaran p WHERE p.id_kejuruan_pilihan2 = k.id_program AND p.id_smk_pilihan2 = ?) as pendaftar_pilihan2
     FROM tb_kejuruan k
     WHERE k.id_smk = ?
     ORDER BY k.nama_kejuruan",
    [$smkId, $smkId, $smkId]
);
?>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-mortarboard me-2"></i>Daftar Jurusan</h5>
                <span class="badge bg-primary"><?= count($jurusanList) ?> jurusan</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Kode</th>
                                <th>Nama Jurusan</th>
                                <th class="text-center">Pilihan 1</th>
                                <th class="text-center">Pilihan 2</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($jurusanList as $i => $j): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><code><?= htmlspecialchars($j['kode_kejuruan']) ?></code></td>
                                    <td>
                                        <strong><?= htmlspecialchars($j['nama_kejuruan']) ?></strong>
                                        <?php if (!empty($j['deskripsi'])): ?>
                                            <br><small class="text-muted"><?= htmlspecialchars(substr($j['deskripsi'], 0, 80)) ?>...</small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary"><?= $j['pendaftar_pilihan1'] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary"><?= $j['pendaftar_pilihan2'] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success"><?= $j['pendaftar_pilihan1'] + $j['pendaftar_pilihan2'] ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($jurusanList)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Belum ada data jurusan</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Distribusi Pendaftar</h6>
            </div>
            <div class="card-body">
                <canvas id="chartJurusan" height="250"></canvas>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informasi</h6>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-0">
                    Data jurusan dikelola oleh Superadmin. Hubungi admin pusat jika perlu menambah atau mengubah data jurusan.
                </p>
            </div>
        </div>
    </div>
</div>

<?php
$chartLabels = json_encode(array_column($jurusanList, 'nama_kejuruan'));
$chartData = json_encode(array_map(function ($j) {
    return $j['pendaftar_pilihan1'] + $j['pendaftar_pilihan2'];
}, $jurusanList));
$extraScripts = <<<EOT
<script>
new Chart(document.getElementById('chartJurusan'), {
    type: 'doughnut',
    data: {
        labels: {$chartLabels},
        datasets: [{
            data: {$chartData},
            backgroundColor: ['#667eea', '#764ba2', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#06B6D4']
        }]
    },
    options: {
        responsive: true,
        plugins: { 
            legend: { 
                position: 'bottom',
                labels: { boxWidth: 12, padding: 8 }
            } 
        }
    }
});
</script>
EOT;
require_once 'includes/footer.php';
?>