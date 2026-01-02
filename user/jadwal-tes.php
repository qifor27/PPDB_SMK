<?php

/**
 * User - Jadwal Tes Minat dan Bakat
 */
$pageTitle = 'Jadwal Tes';
require_once 'includes/header.php';

// Cek pendaftaran - hanya bisa diakses setelah mendaftar
if (!$pendaftaran) {
    Session::flash('error', 'Silakan lengkapi pendaftaran terlebih dahulu untuk melihat jadwal tes.');
    redirect(SITE_URL . '/user/pilih-sekolah-smk.php');
}

// Get jadwal tes dari pengaturan atau database
$jadwalTes = [
    [
        'nama' => 'Tes Minat dan Bakat (Tahap 1)',
        'tanggal' => '2026-01-15',
        'waktu' => '08:00 - 12:00 WIB',
        'lokasi' => 'Sekolah Pilihan 1',
        'keterangan' => 'Bawa kartu pendaftaran dan alat tulis',
        'status' => 'upcoming'
    ],
    [
        'nama' => 'Tes Minat dan Bakat (Tahap 2)',
        'tanggal' => '2026-01-17',
        'waktu' => '08:00 - 12:00 WIB',
        'lokasi' => 'Sekolah Pilihan 1',
        'keterangan' => 'Untuk yang tidak hadir di Tahap 1',
        'status' => 'upcoming'
    ],
    [
        'nama' => 'Pengumuman Hasil Seleksi',
        'tanggal' => '2026-01-25',
        'waktu' => '10:00 WIB',
        'lokasi' => 'Website PPDB SMK',
        'keterangan' => 'Cek status pendaftaran di dashboard',
        'status' => 'upcoming'
    ],
    [
        'nama' => 'Daftar Ulang',
        'tanggal' => '2026-01-27',
        'waktu' => '08:00 - 15:00 WIB',
        'lokasi' => 'Sekolah yang diterima',
        'keterangan' => 'Bawa dokumen asli dan fotokopi',
        'status' => 'upcoming'
    ]
];

// Get sekolah pilihan
$sekolah1 = db()->fetch("SELECT nama_sekolah, alamat FROM tb_smk WHERE id_smk = ?", [$pendaftaran['id_smk_pilihan1']]);
?>

<div class="row g-4">
    <!-- Info Card -->
    <div class="col-12">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-2"><i class="bi bi-calendar-check me-2"></i>Jadwal Tes Minat dan Bakat</h5>
                        <p class="mb-0 opacity-75">
                            Berikut jadwal seleksi untuk pendaftaran Anda. Pastikan hadir tepat waktu dan membawa perlengkapan yang diperlukan.
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <span class="badge bg-white text-primary fs-6">
                            No: <?= $pendaftaran['nomor_pendaftaran'] ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sekolah Pilihan Info -->
    <div class="col-12">
        <div class="alert alert-info">
            <i class="bi bi-building me-2"></i>
            <strong>Lokasi Tes:</strong> <?= htmlspecialchars($sekolah1['nama_sekolah'] ?? 'Sekolah Pilihan 1') ?>
            <?php if (!empty($sekolah1['alamat'])): ?>
                <br><small class="text-muted"><?= htmlspecialchars($sekolah1['alamat']) ?></small>
            <?php endif; ?>
        </div>
    </div>

    <!-- Timeline Jadwal -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-list-check me-2"></i>Timeline Jadwal Seleksi</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <?php foreach ($jadwalTes as $index => $jadwal):
                        $tanggal = date('d M Y', strtotime($jadwal['tanggal']));
                        $isPast = strtotime($jadwal['tanggal']) < time();
                        $isToday = date('Y-m-d') === $jadwal['tanggal'];
                    ?>
                        <div class="timeline-item <?= $isPast ? 'completed' : ($isToday ? 'active' : '') ?>">
                            <div class="timeline-marker">
                                <?php if ($isPast): ?>
                                    <i class="bi bi-check-lg"></i>
                                <?php else: ?>
                                    <?= $index + 1 ?>
                                <?php endif; ?>
                            </div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="mb-0"><?= htmlspecialchars($jadwal['nama']) ?></h6>
                                    <?php if ($isToday): ?>
                                        <span class="badge bg-danger">Hari Ini</span>
                                    <?php elseif ($isPast): ?>
                                        <span class="badge bg-secondary">Selesai</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Akan Datang</span>
                                    <?php endif; ?>
                                </div>
                                <div class="small text-muted mb-2">
                                    <i class="bi bi-calendar me-1"></i><?= $tanggal ?>
                                    <span class="mx-2">•</span>
                                    <i class="bi bi-clock me-1"></i><?= $jadwal['waktu'] ?>
                                </div>
                                <div class="small mb-2">
                                    <i class="bi bi-geo-alt me-1 text-primary"></i><?= htmlspecialchars($jadwal['lokasi']) ?>
                                </div>
                                <?php if (!empty($jadwal['keterangan'])): ?>
                                    <div class="small text-muted">
                                        <i class="bi bi-info-circle me-1"></i><?= htmlspecialchars($jadwal['keterangan']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Persiapan Tes</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Kartu Pendaftaran (cetak)
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Kartu Identitas (KTP/Kartu Pelajar)
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Alat Tulis (pensil 2B, penghapus)
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Hadir 30 menit sebelum tes
                    </li>
                    <li>
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Pakaian rapi (seragam sekolah)
                    </li>
                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-printer me-2"></i>Cetak Kartu</h6>
            </div>
            <div class="card-body">
                <p class="small text-muted">Cetak kartu pendaftaran sebagai bukti kehadiran saat tes.</p>
                <a href="cetak-kartu.php" class="btn btn-primary w-100" target="_blank">
                    <i class="bi bi-printer me-2"></i>Cetak Kartu Pendaftaran
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding-left: 50px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 30px;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-marker {
        position: absolute;
        left: -50px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #6c757d;
        z-index: 1;
    }

    .timeline-item.completed .timeline-marker {
        background: #10B981;
        color: white;
    }

    .timeline-item.active .timeline-marker {
        background: #667eea;
        color: white;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.3);
    }

    .timeline-content {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
    }

    .timeline-item.active .timeline-content {
        background: rgba(102, 126, 234, 0.1);
        border: 1px solid rgba(102, 126, 234, 0.3);
    }
</style>

<?php require_once 'includes/footer.php'; ?>