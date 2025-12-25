<?php
/**
 * User - Upload Dokumen
 */
$pageTitle = 'Upload Dokumen';
require_once 'includes/header.php';

if (!$pendaftaran) {
    redirect('pilih-jalur.php');
}

// Required documents based on jalur
$requiredDocs = [
    'Kartu Keluarga (KK)' => true,
    'Akta Kelahiran' => true,
    'Ijazah/SKL SMP' => true,
    'Raport Semester Terakhir' => true,
    'Pas Foto 3x4' => true
];

// Add jalur-specific documents
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
        break;
}

// Get uploaded documents
$uploadedDocs = db()->fetchAll(
    "SELECT * FROM tb_dokumen WHERE id_pendaftaran = ?",
    [$pendaftaran['id_pendaftaran']]
);
$uploadedDocsMap = [];
foreach ($uploadedDocs as $doc) {
    $uploadedDocsMap[$doc['jenis_dokumen']] = $doc;
}

$error = '';
$success = '';

// Handle upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['dokumen'])) {
    $jenisDokumen = sanitize($_POST['jenis_dokumen'] ?? '');

    if (empty($jenisDokumen)) {
        $error = 'Pilih jenis dokumen.';
    } else {
        $uploadPath = UPLOADS_PATH . 'dokumen/' . $pendaftaran['id_pendaftaran'] . '/';
        $result = uploadFile($_FILES['dokumen'], $uploadPath);

        if ($result['success']) {
            // Delete old file if exists
            if (isset($uploadedDocsMap[$jenisDokumen])) {
                $oldFile = $uploadedDocsMap[$jenisDokumen]['path_file'];
                if (file_exists($oldFile))
                    unlink($oldFile);

                db()->update('tb_dokumen', [
                    'nama_file' => $result['filename'],
                    'path_file' => $result['path'],
                    'ukuran_file' => $result['size'],
                    'status_verifikasi' => 'pending'
                ], 'id_dokumen = :where_id_dokumen', ['where_id_dokumen' => $uploadedDocsMap[$jenisDokumen]['id_dokumen']]);
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

// Refresh uploaded docs
$uploadedDocs = db()->fetchAll(
    "SELECT * FROM tb_dokumen WHERE id_pendaftaran = ?",
    [$pendaftaran['id_pendaftaran']]
);
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

<!-- === JALUR AFIRMASI START === -->
<?php if ($pendaftaran['kode_jalur'] === 'afirmasi'): ?>
    <div class="alert alert-purple mb-4"
        style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.1), rgba(139, 92, 246, 0.05)); border: 1px solid rgba(139, 92, 246, 0.3);">
        <div class="d-flex align-items-start">
            <i class="bi bi-heart-fill text-purple me-3 fs-4"></i>
            <div>
                <h6 class="mb-2 text-purple"><i class="bi bi-info-circle me-1"></i> Dokumen Jalur Afirmasi</h6>
                <p class="mb-2 small">Untuk jalur afirmasi, Anda <strong>wajib</strong> mengupload minimal salah satu
                    dokumen berikut:</p>
                <ul class="mb-2 small">
                    <li><strong>KIP/KKS</strong> - Kartu Indonesia Pintar atau Kartu Keluarga Sejahtera</li>
                    <li><strong>PKH</strong> - Surat/Kartu Program Keluarga Harapan</li>
                    <li><strong>KIS</strong> - Kartu Indonesia Sehat (BPJS PBI)</li>
                    <li><strong>SKTM</strong> - Surat Keterangan Tidak Mampu dari Kelurahan/Desa</li>
                </ul>
                <?php
                $afirmasiStatus = validateAfirmasiDokumen($pendaftaran['id_pendaftaran']);
                if ($afirmasiStatus['is_complete']):
                    ?>
                    <div class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Dokumen afirmasi sudah dilengkapi
                    </div>
                <?php else: ?>
                    <div class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle me-1"></i> Belum ada dokumen
                        bantuan yang diupload</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
<!-- === JALUR AFIRMASI END === -->

<div class="row g-4">
    <!-- Upload Form -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-cloud-upload me-2"></i>Upload Dokumen</h6>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <?= Session::csrfField() ?>

                    <div class="mb-3">
                        <label class="form-label">Jenis Dokumen</label>
                        <select name="jenis_dokumen" class="form-select" required>
                            <option value="">-- Pilih Jenis --</option>
                            <?php foreach ($requiredDocs as $doc => $required): ?>
                                <option value="<?= htmlspecialchars($doc) ?>">
                                    <?= htmlspecialchars($doc) ?>     <?= $required ? ' *' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">File Dokumen</label>
                        <div class="file-upload">
                            <input type="file" name="dokumen" accept=".pdf,.jpg,.jpeg,.png" required>
                            <div class="file-upload-icon"><i class="bi bi-cloud-arrow-up"></i></div>
                            <div class="file-upload-text">Klik atau drag file ke sini</div>
                            <div class="file-upload-hint">PDF, JPG, PNG (max 5MB)</div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-upload me-2"></i>Upload
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Document List -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-folder me-2"></i>Daftar Dokumen</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark mb-0">
                        <thead>
                            <tr>
                                <th>Jenis Dokumen</th>
                                <th>Status</th>
                                <th>File</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($requiredDocs as $doc => $required): ?>
                                <tr>
                                    <td>
                                        <?= htmlspecialchars($doc) ?>
                                        <?php if ($required): ?>
                                            <span class="text-danger">*</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (isset($uploadedDocsMap[$doc])): ?>
                                            <?= getStatusBadge($uploadedDocsMap[$doc]['status_verifikasi']) ?>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Belum Upload</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (isset($uploadedDocsMap[$doc])): ?>
                                            <small><?= $uploadedDocsMap[$doc]['nama_file'] ?></small>
                                        <?php else: ?>
                                            <small class="text-muted">-</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (isset($uploadedDocsMap[$doc])): ?>
                                            <a href="<?= UPLOADS_URL ?>/dokumen/<?= $pendaftaran['id_pendaftaran'] ?>/<?= $uploadedDocsMap[$doc]['nama_file'] ?>"
                                                target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="pendaftaran.php" class="btn btn-dark">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
            <a href="status.php" class="btn btn-primary">
                Lanjut ke Status <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>