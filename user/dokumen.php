<?php
/**
 * User - Upload Dokumen
 */
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/config/functions.php';
require_once dirname(__DIR__) . '/config/session.php';

Session::requireRole(ROLE_SISWA, SITE_URL . '/login.php');
$userId = Session::getUserId();

$pendaftaran = db()->fetch(
    "SELECT p.*, j.nama_jalur, j.kode_jalur FROM tb_pendaftaran p
     LEFT JOIN tb_jalur j ON p.id_jalur = j.id_jalur
     WHERE p.id_siswa = ? ORDER BY p.id_pendaftaran DESC LIMIT 1",
    [$userId]
);

if (!$pendaftaran) {
    redirect('pilih-jalur.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['dokumen'])) {
    $jenisDokumen = sanitize($_POST['jenis_dokumen'] ?? '');
    if (empty($jenisDokumen)) {
        $error = 'Pilih jenis dokumen.';
    } else {
        $uploadPath = UPLOADS_PATH . 'dokumen/' . $pendaftaran['id_pendaftaran'] . '/';
        $result = uploadFile($_FILES['dokumen'], $uploadPath);
        if ($result['success']) {
            $existingDoc = db()->fetch(
                "SELECT * FROM tb_dokumen WHERE id_pendaftaran = ? AND jenis_dokumen = ?",
                [$pendaftaran['id_pendaftaran'], $jenisDokumen]
            );
            if ($existingDoc) {
                if (file_exists($existingDoc['path_file'])) unlink($existingDoc['path_file']);
                db()->update('tb_dokumen', [
                    'nama_file' => $result['filename'],
                    'path_file' => $result['path'],
                    'ukuran_file' => $result['size'],
                    'status_verifikasi' => 'pending'
                ], 'id_dokumen = :where_id', ['where_id' => $existingDoc['id_dokumen']]);
            } else {
                db()->insert('tb_dokumen', [
                    'id_pendaftaran' => $pendaftaran['id_pendaftaran'],
                    'jenis_dokumen' => $jenisDokumen,
                    'nama_file' => $result['filename'],
                    'path_file' => $result['path'],
                    'ukuran_file' => $result['size']
                ]);
            }
            Session::flash('success', 'Dokumen berhasil diupload.');
            redirect('dokumen.php');
        } else {
            $error = $result['error'];
        }
    }
}

$pageTitle = 'Upload Dokumen';
require_once 'includes/header.php';

$requiredDocs = [
    'Kartu Keluarga (KK)' => true,
    'Akta Kelahiran' => true,
    'Ijazah/SKL SMP' => true,
    'Raport Semester Terakhir' => true,
    'Pas Foto 3x4' => true
];

switch ($pendaftaran['kode_jalur']) {
    case 'afirmasi':
        $requiredDocs['Kartu Indonesia Pintar (KIP)/PKH/KIS'] = true;
        $requiredDocs['SKTM dari Kelurahan'] = true;
        break;
    case 'prestasi':
        $requiredDocs['Sertifikat Prestasi'] = true;
        $requiredDocs['Surat Rekomendasi Sekolah'] = false;
        break;
    case 'zonasi':
        $requiredDocs['Bukti Domisili (KK/Surat Keterangan)'] = true;
        break;
    case 'kepindahan':
        $requiredDocs['SK Pindah Tugas Orang Tua'] = true;
        $requiredDocs['Surat Keterangan dari Instansi'] = true;
        $requiredDocs['KK Baru (Setelah Pindah)'] = true;
        $requiredDocs['Surat Pindah Sekolah'] = true;
        break;
}

$uploadedDocs = db()->fetchAll("SELECT * FROM tb_dokumen WHERE id_pendaftaran = ?", [$pendaftaran['id_pendaftaran']]);
$uploadedDocsMap = [];
foreach ($uploadedDocs as $doc) {
    $uploadedDocsMap[$doc['jenis_dokumen']] = $doc;
}
?>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">Upload Dokumen Persyaratan</h5>
            <small class="text-muted">No: <?= $pendaftaran['nomor_pendaftaran'] ?></small>
        </div>
        <?= getJalurBadge($pendaftaran['kode_jalur']) ?>
    </div>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i><?= $error ?></div>
<?php endif; ?>

<?php if ($pendaftaran['kode_jalur'] === 'afirmasi'): ?>
<div class="alert alert-purple mb-4" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.1), rgba(139, 92, 246, 0.05)); border: 1px solid rgba(139, 92, 246, 0.3);">
    <div class="d-flex align-items-start">
        <i class="bi bi-heart-fill text-purple me-3 fs-4"></i>
        <div>
            <h6 class="mb-2 text-purple"><i class="bi bi-info-circle me-1"></i> Dokumen Jalur Afirmasi</h6>
            <p class="mb-2 small">Untuk jalur afirmasi, Anda <strong>wajib</strong> mengupload minimal salah satu dokumen berikut:</p>
            <ul class="mb-2 small">
                <li><strong>KIP/KKS</strong> - Kartu Indonesia Pintar atau Kartu Keluarga Sejahtera</li>
                <li><strong>PKH</strong> - Surat/Kartu Program Keluarga Harapan</li>
                <li><strong>KIS</strong> - Kartu Indonesia Sehat (BPJS PBI)</li>
                <li><strong>SKTM</strong> - Surat Keterangan Tidak Mampu dari Kelurahan/Desa</li>
            </ul>
            <?php $afirmasiStatus = validateAfirmasiDokumen($pendaftaran['id_pendaftaran']); ?>
            <?php if ($afirmasiStatus['is_complete']): ?>
                <div class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Dokumen afirmasi sudah dilengkapi</div>
            <?php else: ?>
                <div class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle me-1"></i> Belum ada dokumen bantuan</div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h6 class="mb-0"><i class="bi bi-cloud-upload me-2"></i>Upload Dokumen</h6></div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <?= Session::csrfField() ?>
                    <div class="mb-3">
                        <label class="form-label">Jenis Dokumen</label>
                        <select name="jenis_dokumen" class="form-select" required>
                            <option value="">-- Pilih Jenis --</option>
                            <?php foreach ($requiredDocs as $doc => $required): ?>
                                <option value="<?= htmlspecialchars($doc) ?>"><?= htmlspecialchars($doc) ?><?= $required ? ' *' : '' ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">File Dokumen</label>
                        <label for="fileInput" class="file-upload" style="display: block;">
                            <input type="file" name="dokumen" id="fileInput" accept=".pdf,.jpg,.jpeg,.png" required style="display: none;">
                            <div class="file-upload-icon"><i class="bi bi-cloud-arrow-up"></i></div>
                            <div class="file-upload-text" id="fileText">Klik atau drag file ke sini</div>
                            <div class="file-upload-hint">PDF, JPG, PNG (max 5MB)</div>
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-upload me-2"></i>Upload</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><h6 class="mb-0"><i class="bi bi-folder me-2"></i>Daftar Dokumen</h6></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light"><tr><th>Jenis Dokumen</th><th>Status</th><th>File</th><th>Aksi</th></tr></thead>
                        <tbody>
                            <?php foreach ($requiredDocs as $doc => $required): ?>
                            <tr>
                                <td><?= htmlspecialchars($doc) ?><?php if ($required): ?><span class="text-danger">*</span><?php endif; ?></td>
                                <td><?php if (isset($uploadedDocsMap[$doc])): ?><?= getStatusBadge($uploadedDocsMap[$doc]['status_verifikasi']) ?><?php else: ?><span class="badge bg-secondary">Belum Upload</span><?php endif; ?></td>
                                <td><?php if (isset($uploadedDocsMap[$doc])): ?><small><?= $uploadedDocsMap[$doc]['nama_file'] ?></small><?php else: ?><small class="text-muted">-</small><?php endif; ?></td>
                                <td><?php if (isset($uploadedDocsMap[$doc])): ?><a href="<?= UPLOADS_URL ?>/dokumen/<?= $pendaftaran['id_pendaftaran'] ?>/<?= $uploadedDocsMap[$doc]['nama_file'] ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a><?php endif; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between mt-4">
            <a href="pendaftaran.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
            <a href="status.php" class="btn btn-primary">Lanjut ke Status <i class="bi bi-arrow-right ms-2"></i></a>
        </div>
    </div>
</div>

<script>
document.getElementById('fileInput').addEventListener('change', function(e) {
    const fileText = document.getElementById('fileText');
    const fileUpload = document.querySelector('.file-upload');
    if (this.files.length > 0) {
        fileText.textContent = this.files[0].name;
        fileUpload.classList.add('has-file');
    } else {
        fileText.textContent = 'Klik atau drag file ke sini';
        fileUpload.classList.remove('has-file');
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>