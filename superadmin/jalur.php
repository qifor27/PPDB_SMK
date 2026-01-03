<?php

/**
 * Super Admin - Tahap Seleksi
 */
$pageTitle = 'Tahap Seleksi';
require_once 'includes/header.php';

// Get jadwal tahap from config or database
$jadwalTahap = [
    1 => [
        'nama' => 'Tahap 1',
        'mulai' => '2026-01-01 00:00:00',
        'selesai' => '2026-01-06 23:59:59',
        'tes' => '2026-01-08',
        'keterangan' => 'Gelombang pertama pendaftaran dan tes minat bakat'
    ],
    2 => [
        'nama' => 'Tahap 2',
        'mulai' => '2026-01-07 08:00:00',
        'selesai' => '2026-01-15 23:59:59',
        'tes' => '2026-01-17',
        'keterangan' => 'Gelombang kedua untuk kuota yang tersisa'
    ]
];

$now = date('Y-m-d H:i:s');

// Stats per tahap
$statsTahap = [
    1 => db()->count('tb_pendaftaran', 'tahap_pendaftaran = ?', [1]),
    2 => db()->count('tb_pendaftaran', 'tahap_pendaftaran = ?', [2]),
];

// Stats per jalur (tetap ada untuk referensi)
$statsJalur = db()->fetchAll(
    "SELECT j.nama_jalur, j.kode_jalur, j.icon, COUNT(p.id_pendaftaran) as total
     FROM tb_jalur j
     LEFT JOIN tb_pendaftaran p ON j.id_jalur = p.id_jalur
     WHERE j.is_active = 1
     GROUP BY j.id_jalur
     ORDER BY j.id_jalur"
);
?>

<div class="row g-4">
    <!-- Info 2 Tahap Seleksi -->
    <div class="col-12">
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Info:</strong> Sistem SPMB menggunakan <strong>2 Tahap Seleksi</strong> dengan masing-masing jadwal pendaftaran dan tes yang berbeda.
        </div>
    </div>

    <!-- Tahap 1 Card -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-1-circle me-2"></i>Tahap 1 - Gelombang Pertama</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label text-muted small">Periode Pendaftaran</label>
                        <div class="d-flex gap-2">
                            <div class="flex-fill">
                                <label class="form-label small">Mulai</label>
                                <input type="datetime-local" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($jadwalTahap[1]['mulai'])) ?>">
                            </div>
                            <div class="flex-fill">
                                <label class="form-label small">Selesai</label>
                                <input type="datetime-local" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($jadwalTahap[1]['selesai'])) ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Tanggal Tes</label>
                        <input type="date" class="form-control" value="<?= $jadwalTahap[1]['tes'] ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" rows="2"><?= $jadwalTahap[1]['keterangan'] ?></textarea>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <span class="text-muted">Total Pendaftar Tahap 1:</span>
                    <span class="badge bg-primary fs-6"><?= $statsTahap[1] ?></span>
                </div>

                <?php
                $tahap1Aktif = ($now >= $jadwalTahap[1]['mulai'] && $now <= $jadwalTahap[1]['selesai']);
                ?>
                <div class="mt-3">
                    <?php if ($tahap1Aktif): ?>
                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>SEDANG BUKA</span>
                    <?php elseif ($now < $jadwalTahap[1]['mulai']): ?>
                        <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>AKAN DATANG</span>
                    <?php else: ?>
                        <span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i>SELESAI</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Tahap 2 Card -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="bi bi-2-circle me-2"></i>Tahap 2 - Gelombang Kedua</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label text-muted small">Periode Pendaftaran</label>
                        <div class="d-flex gap-2">
                            <div class="flex-fill">
                                <label class="form-label small">Mulai</label>
                                <input type="datetime-local" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($jadwalTahap[2]['mulai'])) ?>">
                            </div>
                            <div class="flex-fill">
                                <label class="form-label small">Selesai</label>
                                <input type="datetime-local" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($jadwalTahap[2]['selesai'])) ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Tanggal Tes</label>
                        <input type="date" class="form-control" value="<?= $jadwalTahap[2]['tes'] ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" rows="2"><?= $jadwalTahap[2]['keterangan'] ?></textarea>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <span class="text-muted">Total Pendaftar Tahap 2:</span>
                    <span class="badge bg-warning text-dark fs-6"><?= $statsTahap[2] ?></span>
                </div>

                <?php
                $tahap2Aktif = ($now >= $jadwalTahap[2]['mulai'] && $now <= $jadwalTahap[2]['selesai']);
                ?>
                <div class="mt-3">
                    <?php if ($tahap2Aktif): ?>
                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>SEDANG BUKA</span>
                    <?php elseif ($now < $jadwalTahap[2]['mulai']): ?>
                        <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>AKAN DATANG</span>
                    <?php else: ?>
                        <span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i>SELESAI</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Jalur (Referensi) -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-signpost-split me-2"></i>Distribusi per Jalur Pendaftaran</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <?php foreach ($statsJalur as $jalur): ?>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center gap-2 p-3 rounded" style="background: rgba(139, 92, 246, 0.08);">
                                <i class="bi <?= $jalur['icon'] ?? 'bi-bookmark-star' ?> text-primary fs-4"></i>
                                <div>
                                    <div class="fw-semibold"><?= htmlspecialchars($jalur['nama_jalur']) ?></div>
                                    <small class="text-muted"><?= $jalur['total'] ?> pendaftar</small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>