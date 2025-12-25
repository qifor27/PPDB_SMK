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
        $requiredDocs['Surat Rekomendasi Sekolah'] = false;
        // Note: Sertifikat prestasi diupload per prestasi di bawah
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
                ], 'id_dokumen = :id_dokumen', ['id_dokumen' => $uploadedDocsMap[$jenisDokumen]['id_dokumen']]);
            } else {
                db()->insert('tb_dokumen', [
                    'id_pendaftaran' => $pendaftaran['id_pendaftaran'],
                    'jenis_dokumen' => $jenisDokumen,
                    'nama_file' => $result['filename'],
                    'path_file' => $result['path'],
                    'ukuran_file' => $result['size']
                ]);
            }

            $success = 'Dokumen berhasil diupload. Halaman akan di-refresh...';
            echo '<script>setTimeout(function(){ window.location.href = "dokumen.php"; }, 500);</script>';
        } else {
            $error = $result['error'];
        }
    }
}

// === JALUR PRESTASI SERTIFIKAT UPLOAD START ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['sertifikat']) && isset($_POST['prestasi_id'])) {
    $prestasiId = (int) $_POST['prestasi_id'];

    // Verify prestasi belongs to this pendaftaran
    $prestasi = db()->fetch(
        "SELECT * FROM tb_prestasi_siswa WHERE id_prestasi_siswa = ? AND id_pendaftaran = ?",
        [$prestasiId, $pendaftaran['id_pendaftaran']]
    );

    if ($prestasi) {
        $uploadPath = UPLOADS_PATH . 'prestasi/' . $pendaftaran['id_pendaftaran'] . '/';
        $result = uploadFile($_FILES['sertifikat'], $uploadPath);

        if ($result['success']) {
            // Delete old file if exists
            if (!empty($prestasi['file_sertifikat'])) {
                $oldFile = $uploadPath . $prestasi['file_sertifikat'];
                if (file_exists($oldFile))
                    unlink($oldFile);
            }

            db()->update('tb_prestasi_siswa', [
                'file_sertifikat' => $result['filename']
            ], 'id_prestasi_siswa = :id_prestasi_siswa', ['id_prestasi_siswa' => $prestasiId]);

            $success = 'Sertifikat prestasi berhasil diupload. Halaman akan di-refresh...';
            echo '<script>setTimeout(function(){ window.location.href = "dokumen.php"; }, 500);</script>';
        } else {
            $error = $result['error'];
        }
    } else {
        $error = 'Data prestasi tidak ditemukan.';
    }
}
// === JALUR PRESTASI SERTIFIKAT UPLOAD END ===

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

        <!-- === JALUR PRESTASI SERTIFIKAT UPLOAD UI START === -->
        <?php if ($pendaftaran['kode_jalur'] === 'prestasi'): ?>
            <?php $prestasiList = getPrestasiByPendaftaran($pendaftaran['id_pendaftaran']); ?>
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="bi bi-trophy me-2"></i>Upload Sertifikat Prestasi</h6>
                    <span class="badge bg-warning"><?= count($prestasiList) ?> prestasi</span>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($prestasiList)): ?>
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-trophy fs-1 mb-2 d-block opacity-50"></i>
                            <p>Belum ada data prestasi. <a href="pendaftaran.php">Tambahkan prestasi terlebih dahulu</a>.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-dark mb-0">
                                <thead>
                                    <tr>
                                        <th>Nama Prestasi</th>
                                        <th>Tingkat</th>
                                        <th>Peringkat</th>
                                        <th>Poin</th>
                                        <th>Sertifikat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($prestasiList as $p): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($p['nama_prestasi']) ?></td>
                                            <td><?= $p['tingkat'] ?></td>
                                            <td><span class="badge bg-warning text-dark"><?= $p['peringkat'] ?></span></td>
                                            <td><strong class="text-success"><?= $p['poin'] ?></strong></td>
                                            <td>
                                                <?php if (!empty($p['file_sertifikat'])): ?>
                                                    <span class="badge bg-success"><i class="bi bi-check"></i> Sudah Upload</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Belum Upload</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($p['file_sertifikat'])): ?>
                                                    <a href="<?= UPLOADS_URL ?>/prestasi/<?= $pendaftaran['id_pendaftaran'] ?>/<?= $p['file_sertifikat'] ?>"
                                                        target="_blank" class="btn btn-sm btn-outline-primary" title="Lihat">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <button type="button" class="btn btn-sm btn-primary btn-upload-sertifikat"
                                                    data-id="<?= $p['id_prestasi_siswa'] ?>"
                                                    data-nama="<?= htmlspecialchars($p['nama_prestasi']) ?>" data-bs-toggle="modal"
                                                    data-bs-target="#modalUploadSertifikat">
                                                    <i class="bi bi-upload"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Modal Upload Sertifikat -->
            <div class="modal fade" id="modalUploadSertifikat" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content bg-dark">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="bi bi-upload me-2"></i>Upload Sertifikat</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="modal-body">
                                <input type="hidden" name="prestasi_id" id="inputPrestasiId">
                                <p class="mb-3">Upload sertifikat untuk: <strong id="labelPrestasi"></strong></p>
                                <div class="mb-3">
                                    <label class="form-label">File Sertifikat</label>
                                    <div class="file-upload">
                                        <input type="file" name="sertifikat" accept=".pdf,.jpg,.jpeg,.png" required>
                                        <div class="file-upload-icon"><i class="bi bi-cloud-arrow-up"></i></div>
                                        <div class="file-upload-text">Klik atau drag file ke sini</div>
                                        <div class="file-upload-hint">PDF, JPG, PNG (max 5MB)</div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-upload me-2"></i>Upload
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                document.querySelectorAll('.btn-upload-sertifikat').forEach(btn => {
                    btn.addEventListener('click', function () {
                        document.getElementById('inputPrestasiId').value = this.dataset.id;
                        document.getElementById('labelPrestasi').textContent = this.dataset.nama;
                    });
                });
            </script>
        <?php endif; ?>
        <!-- === JALUR PRESTASI SERTIFIKAT UPLOAD UI END === -->

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

<?php
$extraScripts = <<<EOT
<script>
// File Upload Click Handler
document.querySelectorAll('.file-upload').forEach(function(uploadBox) {
    const fileInput = uploadBox.querySelector('input[type="file"]');
    const textEl = uploadBox.querySelector('.file-upload-text');
    const originalText = textEl ? textEl.textContent : '';
    
    // Click on wrapper triggers file input
    uploadBox.addEventListener('click', function(e) {
        if (e.target !== fileInput) {
            fileInput.click();
        }
    });
    
    // Show selected file name
    fileInput.addEventListener('change', function() {
        if (this.files && this.files.length > 0) {
            if (textEl) {
                textEl.textContent = this.files[0].name;
                textEl.style.color = '#5DD39E';
            }
            uploadBox.style.borderColor = '#5DD39E';
            uploadBox.style.background = 'rgba(93, 211, 158, 0.1)';
        } else {
            if (textEl) {
                textEl.textContent = originalText;
                textEl.style.color = '';
            }
            uploadBox.style.borderColor = '';
            uploadBox.style.background = '';
        }
    });
    
    // Drag and drop
    uploadBox.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadBox.style.borderColor = 'var(--primary)';
        uploadBox.style.background = 'rgba(255, 127, 92, 0.1)';
    });
    
    uploadBox.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadBox.style.borderColor = '';
        uploadBox.style.background = '';
    });
    
    uploadBox.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadBox.style.borderColor = '';
        uploadBox.style.background = '';
        
        if (e.dataTransfer.files.length > 0) {
            fileInput.files = e.dataTransfer.files;
            const event = new Event('change', { bubbles: true });
            fileInput.dispatchEvent(event);
        }
    });
});
</script>
EOT;
require_once 'includes/footer.php';
?>