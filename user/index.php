<?php
/**
 * User Dashboard - Main Page
 */
$pageTitle = 'Dashboard';
require_once 'includes/header.php';

// Get documents count
$docsCount = $pendaftaran ? db()->count('tb_dokumen', 'id_pendaftaran = ?', [$pendaftaran['id_pendaftaran']]) : 0;
?>

<!-- Welcome Card -->
<div class="card mb-4" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); border:none;">
    <div class="card-body p-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="text-white mb-2">Selamat Datang, <?= htmlspecialchars($siswa['nama_lengkap']) ?>! 👋</h3>
                <p class="text-white-50 mb-0">
                    <?php if (!$pendaftaran): ?>
                        Anda belum melakukan pendaftaran. Silakan pilih jalur pendaftaran untuk memulai.
                    <?php else: ?>
                        Status pendaftaran Anda: <strong class="text-white"><?= ucfirst($pendaftaran['status']) ?></strong>
                    <?php endif; ?>
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <?php if (!$pendaftaran): ?>
                <a href="pilih-jalur.php" class="btn btn-light">
                    <i class="bi bi-plus-lg me-2"></i>Mulai Daftar
                </a>
                <?php else: ?>
                <a href="status.php" class="btn btn-light">
                    <i class="bi bi-eye me-2"></i>Lihat Status
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="bi bi-person-vcard"></i>
            </div>
            <div class="stat-info">
                <h3><?= $siswa['is_verified'] ? 'Verified' : 'Pending' ?></h3>
                <p>Status Profil</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon info">
                <i class="bi bi-file-earmark-text"></i>
            </div>
            <div class="stat-info">
                <h3><?= $pendaftaran ? 'Aktif' : 'Belum' ?></h3>
                <p>Pendaftaran</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="bi bi-folder"></i>
            </div>
            <div class="stat-info">
                <h3><?= $docsCount ?></h3>
                <p>Dokumen Upload</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon <?= ($pendaftaran['status'] ?? '') === 'accepted' ? 'primary' : 'danger' ?>">
                <i class="bi bi-<?= ($pendaftaran['status'] ?? '') === 'accepted' ? 'check-circle' : 'hourglass-split' ?>"></i>
            </div>
            <div class="stat-info">
                <h3><?= ucfirst($pendaftaran['status'] ?? 'N/A') ?></h3>
                <p>Status Seleksi</p>
            </div>
        </div>
    </div>
</div>

<?php if ($pendaftaran): ?>
<!-- Pendaftaran Info -->
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Informasi Pendaftaran</h5>
                <?= getStatusBadge($pendaftaran['status']) ?>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Nomor Pendaftaran</label>
                        <div class="fw-semibold"><?= $pendaftaran['nomor_pendaftaran'] ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Jalur Pendaftaran</label>
                        <div><?= getJalurBadge($pendaftaran['kode_jalur']) ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Sekolah Pilihan 1</label>
                        <div class="fw-semibold"><?= htmlspecialchars($pendaftaran['sekolah_pilihan1'] ?? '-') ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Sekolah Pilihan 2</label>
                        <div class="fw-semibold"><?= htmlspecialchars($pendaftaran['sekolah_pilihan2'] ?? '-') ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Tanggal Daftar</label>
                        <div><?= formatDateTime($pendaftaran['tanggal_daftar']) ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Jarak ke Sekolah</label>
                        <div><?= $pendaftaran['jarak_ke_sekolah'] ? formatDistance($pendaftaran['jarak_ke_sekolah']) : '-' ?></div>
                    </div>
                </div>
                
                <?php if ($pendaftaran['status'] === 'draft'): ?>
                <hr>
                <div class="alert alert-warning mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Pendaftaran Anda masih berstatus draft. Lengkapi data dan dokumen, lalu submit pendaftaran.
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Langkah Pendaftaran</h5>
            </div>
            <div class="card-body">
                <div class="steps flex-column">
                    <div class="step completed mb-3 text-start">
                        <div class="d-flex align-items-center">
                            <div class="step-number me-3">1</div>
                            <div class="step-title">Pilih Jalur</div>
                        </div>
                    </div>
                    <div class="step <?= $pendaftaran['status'] !== 'draft' ? 'completed' : 'active' ?> mb-3 text-start">
                        <div class="d-flex align-items-center">
                            <div class="step-number me-3">2</div>
                            <div class="step-title">Lengkapi Data</div>
                        </div>
                    </div>
                    <div class="step <?= $docsCount > 0 ? ($pendaftaran['status'] !== 'draft' ? 'completed' : 'active') : '' ?> mb-3 text-start">
                        <div class="d-flex align-items-center">
                            <div class="step-number me-3">3</div>
                            <div class="step-title">Upload Dokumen</div>
                        </div>
                    </div>
                    <div class="step <?= in_array($pendaftaran['status'], ['submitted','verified','accepted']) ? 'completed' : '' ?> mb-3 text-start">
                        <div class="d-flex align-items-center">
                            <div class="step-number me-3">4</div>
                            <div class="step-title">Submit</div>
                        </div>
                    </div>
                    <div class="step <?= $pendaftaran['status'] === 'accepted' ? 'completed' : '' ?> text-start">
                        <div class="d-flex align-items-center">
                            <div class="step-number me-3">5</div>
                            <div class="step-title">Pengumuman</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<!-- No Registration Yet -->
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-signpost-split text-primary" style="font-size: 4rem;"></i>
                <h4 class="mt-3">Belum Ada Pendaftaran</h4>
                <p class="text-muted">Pilih jalur pendaftaran untuk memulai proses PPDB.</p>
                <a href="pilih-jalur.php" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-lg me-2"></i>Pilih Jalur Pendaftaran
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informasi</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Siapkan NISN dan data diri
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Siapkan dokumen persyaratan
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Pilih jalur sesuai kondisi
                    </li>
                    <li>
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Pilih SMK tujuan
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
