<?php
/**
 * Admin Sekolah - Verifikasi Dokumen
 */
$pageTitle = 'Verifikasi Dokumen';
require_once 'includes/header.php';

$pendaftaranId = $_GET['id'] ?? null;

// Handle verifikasi action
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $docId = (int)$_POST['doc_id'];
    $action = $_POST['action'];
    $catatan = sanitize($_POST['catatan'] ?? '');
    
    $status = $action === 'valid' ? 'valid' : 'invalid';
    db()->update('tb_dokumen', [
        'status_verifikasi' => $status,
        'catatan' => $catatan,
        'verified_by' => $adminId,
        'verified_at' => date('Y-m-d H:i:s')
    ], 'id_dokumen = ?', ['id_dokumen' => $docId]);
    
    Session::flash('success', 'Status dokumen berhasil diupdate.');
    redirect('verifikasi.php' . ($pendaftaranId ? "?id=$pendaftaranId" : ''));
}

// Handle pendaftaran verification
if (isset($_POST['verify_pendaftaran'])) {
    $pId = (int)$_POST['pendaftaran_id'];
    db()->update('tb_pendaftaran', [
        'status' => 'verified',
        'tanggal_verifikasi' => date('Y-m-d H:i:s'),
        'verified_by' => $adminId
    ], 'id_pendaftaran = ?', ['id_pendaftaran' => $pId]);
    
    Session::flash('success', 'Pendaftaran berhasil diverifikasi.');
    redirect('verifikasi.php');
}

if ($pendaftaranId) {
    // Show specific pendaftaran documents
    $pendaftaran = db()->fetch(
        "SELECT p.*, s.nama_lengkap, s.nisn, j.nama_jalur, j.kode_jalur
         FROM tb_pendaftaran p
         JOIN tb_siswa s ON p.id_siswa = s.id_siswa
         JOIN tb_jalur j ON p.id_jalur = j.id_jalur
         WHERE p.id_pendaftaran = ? AND p.id_smk_pilihan1 = ?",
        [$pendaftaranId, $smkId]
    );
    
    if (!$pendaftaran) {
        Session::flash('error', 'Data tidak ditemukan.');
        redirect('verifikasi.php');
    }
    
    $dokumen = db()->fetchAll(
        "SELECT * FROM tb_dokumen WHERE id_pendaftaran = ?",
        [$pendaftaranId]
    );
    
    $allVerified = true;
    foreach ($dokumen as $d) {
        if ($d['status_verifikasi'] !== 'valid') $allVerified = false;
    }
    ?>
    
    <div class="mb-3">
        <a href="verifikasi.php" class="btn btn-dark"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
    </div>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0"><?= htmlspecialchars($pendaftaran['nama_lengkap']) ?></h5>
                <small class="text-muted">NISN: <?= $pendaftaran['nisn'] ?> | <?= $pendaftaran['nomor_pendaftaran'] ?></small>
            </div>
            <div>
                <?= getJalurBadge($pendaftaran['kode_jalur']) ?>
                <?= getStatusBadge($pendaftaran['status']) ?>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <?php foreach ($dokumen as $doc): ?>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><?= htmlspecialchars($doc['jenis_dokumen']) ?></span>
                    <?= getStatusBadge($doc['status_verifikasi']) ?>
                </div>
                <div class="card-body">
                    <?php 
                    $ext = strtolower(pathinfo($doc['nama_file'], PATHINFO_EXTENSION));
                    $fileUrl = UPLOADS_URL . '/dokumen/' . $pendaftaranId . '/' . $doc['nama_file'];
                    ?>
                    
                    <?php if (in_array($ext, ['jpg','jpeg','png','gif'])): ?>
                    <img src="<?= $fileUrl ?>" alt="Preview" class="img-fluid rounded mb-3" style="max-height:200px;">
                    <?php else: ?>
                    <div class="bg-dark-alt rounded p-4 text-center mb-3">
                        <i class="bi bi-file-pdf text-danger fs-1"></i>
                        <div class="small mt-2"><?= $doc['nama_file'] ?></div>
                    </div>
                    <?php endif; ?>
                    
                    <a href="<?= $fileUrl ?>" target="_blank" class="btn btn-sm btn-outline-primary mb-3">
                        <i class="bi bi-eye me-1"></i>Lihat File
                    </a>
                    
                    <?php if ($doc['status_verifikasi'] === 'pending'): ?>
                    <form method="POST" class="mt-3">
                        <input type="hidden" name="doc_id" value="<?= $doc['id_dokumen'] ?>">
                        <div class="mb-2">
                            <input type="text" name="catatan" class="form-control form-control-sm" placeholder="Catatan (opsional)">
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" name="action" value="valid" class="btn btn-success btn-sm flex-fill">
                                <i class="bi bi-check-lg"></i> Valid
                            </button>
                            <button type="submit" name="action" value="invalid" class="btn btn-danger btn-sm flex-fill">
                                <i class="bi bi-x-lg"></i> Invalid
                            </button>
                        </div>
                    </form>
                    <?php elseif ($doc['catatan']): ?>
                    <div class="small text-muted mt-2">Catatan: <?= htmlspecialchars($doc['catatan']) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <?php if ($allVerified && count($dokumen) > 0 && $pendaftaran['status'] === 'submitted'): ?>
    <div class="card mt-4">
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="pendaftaran_id" value="<?= $pendaftaranId ?>">
                <p class="mb-3">Semua dokumen sudah terverifikasi. Verifikasi pendaftaran ini?</p>
                <button type="submit" name="verify_pendaftaran" class="btn btn-primary">
                    <i class="bi bi-check-all me-2"></i>Verifikasi Pendaftaran
                </button>
            </form>
        </div>
    </div>
    <?php endif; ?>
    
<?php } else {
    // List all pending verification
    $pendingList = db()->fetchAll(
        "SELECT p.*, s.nama_lengkap, s.nisn, j.nama_jalur, j.kode_jalur,
                (SELECT COUNT(*) FROM tb_dokumen d WHERE d.id_pendaftaran = p.id_pendaftaran) as total_docs,
                (SELECT COUNT(*) FROM tb_dokumen d WHERE d.id_pendaftaran = p.id_pendaftaran AND d.status_verifikasi = 'pending') as pending_docs
         FROM tb_pendaftaran p
         JOIN tb_siswa s ON p.id_siswa = s.id_siswa
         JOIN tb_jalur j ON p.id_jalur = j.id_jalur
         WHERE p.id_smk_pilihan1 = ? AND p.status IN ('submitted', 'verified')
         ORDER BY p.tanggal_submit ASC",
        [$smkId]
    );
    ?>
    
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-check2-square me-2"></i>Daftar Verifikasi Dokumen</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark mb-0">
                    <thead>
                        <tr>
                            <th>No. Pendaftaran</th>
                            <th>Nama</th>
                            <th>Jalur</th>
                            <th>Dokumen</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pendingList as $p): ?>
                        <tr>
                            <td><code><?= $p['nomor_pendaftaran'] ?></code></td>
                            <td><?= htmlspecialchars($p['nama_lengkap']) ?></td>
                            <td><?= getJalurBadge($p['kode_jalur']) ?></td>
                            <td>
                                <?php if ($p['pending_docs'] > 0): ?>
                                <span class="badge bg-warning"><?= $p['pending_docs'] ?> pending</span>
                                <?php else: ?>
                                <span class="badge bg-success">Semua verified</span>
                                <?php endif; ?>
                                <small class="text-muted">/ <?= $p['total_docs'] ?></small>
                            </td>
                            <td><?= getStatusBadge($p['status']) ?></td>
                            <td>
                                <a href="verifikasi.php?id=<?= $p['id_pendaftaran'] ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-check2-square me-1"></i>Verifikasi
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($pendingList)): ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada data menunggu verifikasi</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php } ?>

<?php require_once 'includes/footer.php'; ?>
