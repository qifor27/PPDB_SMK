<?php

/**
 * User - Pilih Tahap Pendaftaran
 * Siswa memilih Tahap 1 atau Tahap 2 sebelum mendaftar
 */
$pageTitle = 'Pilih Tahap Pendaftaran';
require_once 'includes/header.php';

// Cek apakah sudah punya pendaftaran
if ($pendaftaran) {
    redirect(SITE_URL . '/user/');
}

// Jadwal tahap pendaftaran
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

// Tentukan tahap yang aktif
$tahap1Aktif = ($now >= $jadwalTahap[1]['mulai'] && $now <= $jadwalTahap[1]['selesai']);
$tahap2Aktif = ($now >= $jadwalTahap[2]['mulai'] && $now <= $jadwalTahap[2]['selesai']);

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tahapDipilih = (int)($_POST['tahap'] ?? 0);

    if ($tahapDipilih === 1 && $tahap1Aktif) {
        Session::set('tahap_pendaftaran', 1);
        redirect(SITE_URL . '/user/pilih-sekolah-smk.php');
    } elseif ($tahapDipilih === 2 && $tahap2Aktif) {
        Session::set('tahap_pendaftaran', 2);
        redirect(SITE_URL . '/user/pilih-sekolah-smk.php');
    }
}
?>

<div class="row g-4 justify-content-center">
    <div class="col-lg-10">
        <!-- Info Header -->
        <div class="card mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border:none;">
            <div class="card-body text-center text-white py-5">
                <i class="bi bi-calendar-check display-3 mb-3"></i>
                <h2 class="mb-2">Pilih Tahap Pendaftaran</h2>
                <p class="mb-0 opacity-75">Silakan pilih tahap pendaftaran yang tersedia untuk mengikuti seleksi PPDB SMK</p>
            </div>
        </div>

        <!-- Tahap Cards -->
        <div class="row g-4">
            <!-- Tahap 1 -->
            <div class="col-md-6">
                <div class="card h-100 <?= $tahap1Aktif ? 'border-primary' : 'border-secondary opacity-75' ?>">
                    <div class="card-header <?= $tahap1Aktif ? 'bg-primary text-white' : 'bg-secondary text-white' ?>">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="bi bi-1-circle me-2"></i>Tahap 1</h5>
                            <?php if ($tahap1Aktif): ?>
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>BUKA</span>
                            <?php elseif ($now < $jadwalTahap[1]['mulai']): ?>
                                <span class="badge bg-warning text-dark">Segera</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Ditutup</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">Periode Pendaftaran</small>
                            <div class="fw-semibold">
                                <?= date('d M Y', strtotime($jadwalTahap[1]['mulai'])) ?> -
                                <?= date('d M Y', strtotime($jadwalTahap[1]['selesai'])) ?>
                            </div>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Jadwal Tes Minat & Bakat</small>
                            <div class="fw-semibold text-primary">
                                <i class="bi bi-calendar-event me-1"></i>
                                <?= date('d M Y', strtotime($jadwalTahap[1]['tes'])) ?>
                            </div>
                        </div>
                        <p class="small text-muted mb-3"><?= $jadwalTahap[1]['keterangan'] ?></p>

                        <?php if ($tahap1Aktif): ?>
                            <form method="POST">
                                <?= Session::csrfField() ?>
                                <input type="hidden" name="tahap" value="1">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-arrow-right-circle me-2"></i>Daftar Tahap 1
                                </button>
                            </form>
                        <?php else: ?>
                            <button class="btn btn-secondary w-100" disabled>
                                <?= $now < $jadwalTahap[1]['mulai'] ? 'Belum Dibuka' : 'Sudah Ditutup' ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Tahap 2 -->
            <div class="col-md-6">
                <div class="card h-100 <?= $tahap2Aktif ? 'border-warning' : 'border-secondary opacity-75' ?>">
                    <div class="card-header <?= $tahap2Aktif ? 'bg-warning text-dark' : 'bg-secondary text-white' ?>">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="bi bi-2-circle me-2"></i>Tahap 2</h5>
                            <?php if ($tahap2Aktif): ?>
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>BUKA</span>
                            <?php elseif ($now < $jadwalTahap[2]['mulai']): ?>
                                <span class="badge bg-info">Akan Datang</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Ditutup</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">Periode Pendaftaran</small>
                            <div class="fw-semibold">
                                <?= date('d M Y', strtotime($jadwalTahap[2]['mulai'])) ?> -
                                <?= date('d M Y', strtotime($jadwalTahap[2]['selesai'])) ?>
                            </div>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Jadwal Tes Minat & Bakat</small>
                            <div class="fw-semibold text-warning">
                                <i class="bi bi-calendar-event me-1"></i>
                                <?= date('d M Y', strtotime($jadwalTahap[2]['tes'])) ?>
                            </div>
                        </div>
                        <p class="small text-muted mb-3"><?= $jadwalTahap[2]['keterangan'] ?></p>

                        <?php if ($tahap2Aktif): ?>
                            <form method="POST">
                                <?= Session::csrfField() ?>
                                <input type="hidden" name="tahap" value="2">
                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="bi bi-arrow-right-circle me-2"></i>Daftar Tahap 2
                                </button>
                            </form>
                        <?php else: ?>
                            <button class="btn btn-secondary w-100" disabled>
                                <?= $now < $jadwalTahap[2]['mulai'] ? 'Dibuka ' . date('d M Y', strtotime($jadwalTahap[2]['mulai'])) : 'Sudah Ditutup' ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Tambahan -->
        <div class="card mt-4">
            <div class="card-body">
                <h6><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Penting</h6>
                <ul class="mb-0 small">
                    <li>Setiap siswa hanya dapat mendaftar pada <strong>satu tahap</strong></li>
                    <li>Pastikan dokumen dan data sudah siap sebelum mendaftar</li>
                    <li>Ikuti tes minat & bakat sesuai jadwal tahap yang dipilih</li>
                    <li>Tahap 2 adalah kesempatan kedua bagi yang tidak lolos atau belum mendaftar di Tahap 1</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>