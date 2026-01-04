<?php

/**
 * PPDB SMK - Hasil Pendaftaran
 * Menampilkan 2 kartu pilihan dengan status verifikasi
 * Sesuai UI SPMB 2025/2026
 */

$pageTitle = 'Hasil Pendaftaran';
require_once 'includes/header.php';

// Cek pendaftaran
if (!$pendaftaran) {
    Session::flash('error', 'Anda belum memiliki pendaftaran.');
    redirect(SITE_URL . '/user/');
}

// Get data sekolah dan jurusan
$sekolah1 = db()->fetch("SELECT * FROM tb_smk WHERE id_smk = ?", [$pendaftaran['id_smk_pilihan1']]);
$sekolah2 = $pendaftaran['id_smk_pilihan2'] ?
    db()->fetch("SELECT * FROM tb_smk WHERE id_smk = ?", [$pendaftaran['id_smk_pilihan2']]) : null;

$kejuruan1 = $pendaftaran['id_kejuruan_pilihan1'] ?
    db()->fetch("SELECT * FROM tb_kejuruan WHERE id_program = ?", [$pendaftaran['id_kejuruan_pilihan1']]) : null;
$kejuruan2 = $pendaftaran['id_kejuruan_pilihan2'] ?
    db()->fetch("SELECT * FROM tb_kejuruan WHERE id_program = ?", [$pendaftaran['id_kejuruan_pilihan2']]) : null;

// Get dokumen status
$dokumenCount = db()->fetch("
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status_verifikasi = 'valid' THEN 1 ELSE 0 END) as valid,
        SUM(CASE WHEN status_verifikasi = 'pending' THEN 1 ELSE 0 END) as pending
    FROM tb_dokumen 
    WHERE id_pendaftaran = ?
", [$pendaftaran['id_pendaftaran']]);
?>

<div class="row g-4">
    <!-- Info Alert -->
    <div class="col-12">
        <div class="alert alert-info d-flex align-items-start">
            <i class="bi bi-info-circle-fill me-3 fs-4"></i>
            <div>
                <strong>Informasi</strong>
                <p class="mb-0 small">
                    Halaman ini menampilkan hasil dari proses pendaftaran. Segera cetak kartu pendaftaran
                    sebagai bukti bahwa kamu telah mendaftar di sekolah pilihanmu, ya!
                </p>
            </div>
        </div>
    </div>

    <?php if ($pendaftaran['jenis_pilihan'] === 'diff_school' && $sekolah2): ?>
        <!-- Info Lokasi Tes untuk 2 Sekolah Berbeda -->
        <div class="col-12">
            <div class="alert alert-warning d-flex align-items-start">
                <i class="bi bi-geo-alt-fill me-3 fs-4"></i>
                <div>
                    <strong>Informasi Lokasi Tes Bakat & Minat</strong>
                    <p class="mb-0 small">
                        Karena Anda memilih <strong>2 sekolah berbeda dengan jurusan yang sama</strong>,
                        maka Tes Bakat dan Minat dilaksanakan <strong>hanya di <?= htmlspecialchars($sekolah1['nama_sekolah']) ?></strong> (Pilihan 1).
                        Hasil tes ini akan berlaku untuk kedua pilihan sekolah Anda.
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Pilihan 1 -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-1-circle me-2"></i>Pilihan 1</h6>
                <?= getStatusBadge($pendaftaran['status']) ?>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block">No. Pendaftaran</small>
                    <strong class="fs-5 text-primary"><?= $pendaftaran['nomor_pendaftaran'] ?></strong>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted d-block">Tahap</small>
                        <strong>Tahap <?= $pendaftaran['tahap_pendaftaran'] ?? '1' ?></strong>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Satuan</small>
                        <strong>SMK</strong>
                    </div>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Jalur</small>
                    <strong>Seleksi Nilai Rapor</strong>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Sekolah</small>
                    <strong class="text-primary"><?= htmlspecialchars($sekolah1['nama_sekolah'] ?? '-') ?></strong>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Pilihan Jurusan</small>
                    <strong><?= htmlspecialchars($kejuruan1['nama_kejuruan'] ?? '-') ?></strong>
                </div>

                <hr>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <?php if ($pendaftaran['status'] === 'verified' || $pendaftaran['status'] === 'accepted'): ?>
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <small>Sudah Diverifikasi</small>
                            <?php else: ?>
                                <i class="bi bi-clock-fill text-warning me-2"></i>
                                <small>Belum Diverifikasi</small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <?php if ($dokumenCount['total'] > 0): ?>
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <small>Sudah Disampaikan</small>
                            <?php else: ?>
                                <i class="bi bi-clock-fill text-warning me-2"></i>
                                <small>Belum Disampaikan</small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if ($pendaftaran['status'] === 'draft'): ?>
                    <div class="alert alert-warning small mb-3">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Data pendaftaran kamu masih <strong>belum dikirim</strong>. Yuk segera
                        datang ke sekolah untuk melakukan verifikasi data, ya!
                    </div>
                <?php endif; ?>

                <a href="cetak-kartu.php?pilihan=1" class="btn btn-primary w-100" target="_blank">
                    <i class="bi bi-printer me-2"></i>Unduh Kartu Pendaftaran
                </a>
            </div>
        </div>
    </div>

    <!-- Pilihan 2 -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-2-circle me-2"></i>Pilihan 2</h6>
                <?php if ($sekolah2 || $kejuruan2): ?>
                    <?= getStatusBadge($pendaftaran['status']) ?>
                <?php else: ?>
                    <span class="badge bg-light text-dark">Tidak ada</span>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php if ($sekolah2 || $kejuruan2): ?>
                    <div class="mb-3">
                        <small class="text-muted d-block">No. Pendaftaran</small>
                        <strong class="fs-5 text-primary"><?= $pendaftaran['nomor_pendaftaran'] ?>2</strong>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <small class="text-muted d-block">Tahap</small>
                            <strong>Tahap <?= $pendaftaran['tahap_pendaftaran'] ?? '1' ?></strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Satuan</small>
                            <strong>SMK</strong>
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Jalur</small>
                        <strong>Seleksi Nilai Rapor</strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Sekolah</small>
                        <strong class="text-primary">
                            <?= htmlspecialchars($sekolah2['nama_sekolah'] ?? '-') ?>
                        </strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Pilihan Jurusan</small>
                        <strong><?= htmlspecialchars($kejuruan2['nama_kejuruan'] ?? '-') ?></strong>
                    </div>

                    <hr>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <?php if ($pendaftaran['status'] === 'verified' || $pendaftaran['status'] === 'accepted'): ?>
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <small>Sudah Diverifikasi</small>
                                <?php else: ?>
                                    <i class="bi bi-clock-fill text-warning me-2"></i>
                                    <small>Belum Diverifikasi</small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <?php if ($dokumenCount['total'] > 0): ?>
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <small>Sudah Disampaikan</small>
                                <?php else: ?>
                                    <i class="bi bi-clock-fill text-warning me-2"></i>
                                    <small>Belum Disampaikan</small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <?php if ($pendaftaran['status'] === 'draft'): ?>
                        <div class="alert alert-warning small mb-3">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            Data pendaftaran kamu masih <strong>belum dikirim</strong>. Yuk segera
                            datang ke sekolah untuk melakukan verifikasi data, ya!
                        </div>
                    <?php endif; ?>

                    <a href="cetak-kartu.php?pilihan=2" class="btn btn-secondary w-100" target="_blank">
                        <i class="bi bi-printer me-2"></i>Unduh Kartu Pendaftaran
                    </a>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-dash-circle fs-1 d-block mb-2"></i>
                        <p class="mb-0">Anda tidak memilih pilihan kedua</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="dokumen.php" class="btn btn-outline-primary w-100">
                            <i class="bi bi-folder me-2"></i>Kelola Dokumen
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="status.php" class="btn btn-outline-info w-100">
                            <i class="bi bi-clock-history me-2"></i>Status Pendaftaran
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="prestasi.php" class="btn btn-outline-warning w-100">
                            <i class="bi bi-trophy me-2"></i>Data Prestasi
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="pembatalan.php" class="btn btn-outline-danger w-100">
                            <i class="bi bi-x-circle me-2"></i>Pembatalan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>