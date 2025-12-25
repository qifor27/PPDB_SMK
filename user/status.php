<?php
/**
 * User - Status Pendaftaran
 */
$pageTitle = 'Status Pendaftaran';
require_once 'includes/header.php';

if (!$pendaftaran) {
    redirect('pilih-jalur.php');
}

// Get documents count
$docsCount = db()->count('tb_dokumen', 'id_pendaftaran = ?', [$pendaftaran['id_pendaftaran']]);
$verifiedDocs = db()->count('tb_dokumen', 'id_pendaftaran = ? AND status_verifikasi = ?', [$pendaftaran['id_pendaftaran'], 'valid']);

// Handle submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_pendaftaran'])) {
    if ($pendaftaran['status'] === 'draft') {
        db()->update('tb_pendaftaran', [
            'status' => 'submitted',
            'tanggal_submit' => date('Y-m-d H:i:s')
        ], 'id_pendaftaran = ?', ['id_pendaftaran' => $pendaftaran['id_pendaftaran']]);
        
        Session::flash('success', 'Pendaftaran berhasil disubmit! Silakan tunggu proses verifikasi.');
        redirect('status.php');
    }
}

// Refresh pendaftaran
$pendaftaran = db()->fetch(
    "SELECT p.*, j.nama_jalur, j.kode_jalur, s1.nama_sekolah as sekolah_pilihan1,
            s2.nama_sekolah as sekolah_pilihan2, k1.nama_kejuruan as kejuruan1
     FROM tb_pendaftaran p
     LEFT JOIN tb_jalur j ON p.id_jalur = j.id_jalur
     LEFT JOIN tb_smk s1 ON p.id_smk_pilihan1 = s1.id_smk
     LEFT JOIN tb_smk s2 ON p.id_smk_pilihan2 = s2.id_smk
     LEFT JOIN tb_kejuruan k1 ON p.id_kejuruan_pilihan1 = k1.id_program
     WHERE p.id_siswa = ?",
    [$userId]
);
?>

<div class="row g-4">
    <div class="col-lg-8">
        <!-- Status Card -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Status Pendaftaran</h5>
                <?= getStatusBadge($pendaftaran['status']) ?>
            </div>
            <div class="card-body">
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="text-muted small">Nomor Pendaftaran</label>
                        <div class="fw-bold fs-5 text-primary"><?= $pendaftaran['nomor_pendaftaran'] ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Jalur Pendaftaran</label>
                        <div><?= getJalurBadge($pendaftaran['kode_jalur']) ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Sekolah Pilihan 1</label>
                        <div class="fw-semibold"><?= htmlspecialchars($pendaftaran['sekolah_pilihan1']) ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Sekolah Pilihan 2</label>
                        <div><?= htmlspecialchars($pendaftaran['sekolah_pilihan2'] ?? '-') ?></div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Tanggal Daftar</label>
                        <div><?= formatDate($pendaftaran['tanggal_daftar']) ?></div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Dokumen Terupload</label>
                        <div><?= $docsCount ?> dokumen</div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Dokumen Terverifikasi</label>
                        <div><?= $verifiedDocs ?> dokumen</div>
                    </div>
                </div>
                
                <?php if ($pendaftaran['status'] === 'draft'): ?>
                <div class="alert alert-warning">
                    <h6><i class="bi bi-exclamation-triangle me-2"></i>Pendaftaran Belum Disubmit</h6>
                    <p class="mb-2">Pastikan semua data dan dokumen sudah lengkap sebelum submit.</p>
                    <form method="POST" class="d-inline">
                        <?= Session::csrfField() ?>
                        <button type="submit" name="submit_pendaftaran" class="btn btn-primary"
                                onclick="return confirm('Apakah Anda yakin ingin submit pendaftaran? Data tidak dapat diubah setelah submit.')">
                            <i class="bi bi-send me-2"></i>Submit Pendaftaran
                        </button>
                    </form>
                </div>
                <?php elseif ($pendaftaran['status'] === 'submitted'): ?>
                <div class="alert alert-info">
                    <h6><i class="bi bi-hourglass-split me-2"></i>Menunggu Verifikasi</h6>
                    <p class="mb-0">Pendaftaran Anda sedang dalam proses verifikasi oleh admin sekolah.</p>
                </div>
                <?php elseif ($pendaftaran['status'] === 'verified'): ?>
                <div class="alert alert-primary">
                    <h6><i class="bi bi-check-circle me-2"></i>Dokumen Terverifikasi</h6>
                    <p class="mb-0">Dokumen Anda sudah terverifikasi. Tunggu pengumuman hasil seleksi.</p>
                </div>
                <?php elseif ($pendaftaran['status'] === 'accepted'): ?>
                <div class="alert alert-success">
                    <h6><i class="bi bi-trophy me-2"></i>Selamat! Anda Diterima</h6>
                    <p class="mb-2">Anda diterima di <strong><?= htmlspecialchars($pendaftaran['sekolah_pilihan1']) ?></strong></p>
                    <a href="cetak-bukti.php" class="btn btn-success">
                        <i class="bi bi-printer me-2"></i>Cetak Bukti Penerimaan
                    </a>
                </div>
                <?php elseif ($pendaftaran['status'] === 'rejected'): ?>
                <div class="alert alert-danger">
                    <h6><i class="bi bi-x-circle me-2"></i>Maaf, Anda Belum Diterima</h6>
                    <p class="mb-0"><?= htmlspecialchars($pendaftaran['alasan_penolakan'] ?? 'Tidak memenuhi kriteria seleksi.') ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Timeline -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Timeline Pendaftaran</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item completed">
                        <div class="timeline-date"><?= formatDate($pendaftaran['tanggal_daftar']) ?></div>
                        <div class="timeline-title">Pendaftaran Dibuat</div>
                        <div class="timeline-desc">Memilih jalur <?= $pendaftaran['nama_jalur'] ?></div>
                    </div>
                    
                    <div class="timeline-item <?= in_array($pendaftaran['status'], ['submitted','verified','accepted','rejected']) ? 'completed' : ($pendaftaran['status'] === 'draft' ? 'active' : '') ?>">
                        <div class="timeline-date"><?= $pendaftaran['tanggal_submit'] ? formatDate($pendaftaran['tanggal_submit']) : 'Belum' ?></div>
                        <div class="timeline-title">Submit Pendaftaran</div>
                        <div class="timeline-desc">Mengirim data dan dokumen</div>
                    </div>
                    
                    <div class="timeline-item <?= in_array($pendaftaran['status'], ['verified','accepted','rejected']) ? 'completed' : ($pendaftaran['status'] === 'submitted' ? 'active' : '') ?>">
                        <div class="timeline-date"><?= $pendaftaran['tanggal_verifikasi'] ? formatDate($pendaftaran['tanggal_verifikasi']) : 'Menunggu' ?></div>
                        <div class="timeline-title">Verifikasi Dokumen</div>
                        <div class="timeline-desc">Pemeriksaan oleh admin</div>
                    </div>
                    
                    <div class="timeline-item <?= in_array($pendaftaran['status'], ['accepted','rejected']) ? 'completed' : ($pendaftaran['status'] === 'verified' ? 'active' : '') ?>">
                        <div class="timeline-date"><?= $pendaftaran['tanggal_pengumuman'] ? formatDate($pendaftaran['tanggal_pengumuman']) : 'Menunggu' ?></div>
                        <div class="timeline-title">Pengumuman Hasil</div>
                        <div class="timeline-desc">Hasil seleksi PPDB</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-lightning me-2"></i>Aksi Cepat</h6>
            </div>
            <div class="card-body d-grid gap-2">
                <?php if ($pendaftaran['status'] === 'draft'): ?>
                <a href="pendaftaran.php" class="btn btn-outline-primary">
                    <i class="bi bi-pencil me-2"></i>Edit Data
                </a>
                <a href="dokumen.php" class="btn btn-outline-primary">
                    <i class="bi bi-folder me-2"></i>Upload Dokumen
                </a>
                <?php endif; ?>
                <a href="cetak-bukti.php" class="btn btn-outline-primary">
                    <i class="bi bi-printer me-2"></i>Cetak Bukti
                </a>
                <a href="<?= SITE_URL ?>" class="btn btn-dark">
                    <i class="bi bi-house me-2"></i>Beranda
                </a>
            </div>
        </div>
        
        <!-- Info Card -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informasi</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0 small">
                    <li class="mb-2">
                        <i class="bi bi-check text-success me-2"></i>
                        Pastikan data sudah benar
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check text-success me-2"></i>
                        Upload semua dokumen wajib
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check text-success me-2"></i>
                        Simpan nomor pendaftaran
                    </li>
                    <li>
                        <i class="bi bi-check text-success me-2"></i>
                        Pantau status secara berkala
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
