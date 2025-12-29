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

if (!$pendaftaran) { redirect('pilih-jalur.php'); }

$error = '';
$success = '';

// Handle document upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['dokumen'])) {
    $jenisDokumen = sanitize($_POST['jenis_dokumen'] ?? '');
    if (empty($jenisDokumen)) {
        $error = 'Pilih jenis dokumen.';
    } else {
        $uploadPath = UPLOADS_PATH . 'dokumen/' . $pendaftaran['id_pendaftaran'] . '/';
        $result = uploadFile($_FILES['dokumen'], $uploadPath);
        if ($result['success']) {
            $existingDoc = db()->fetch("SELECT * FROM tb_dokumen WHERE id_pendaftaran = ? AND jenis_dokumen = ?", [$pendaftaran['id_pendaftaran'], $jenisDokumen]);
            if ($existingDoc) {
                if (file_exists($existingDoc['path_file'])) unlink($existingDoc['path_file']);
                db()->update('tb_dokumen', ['nama_file' => $result['filename'], 'path_file' => $result['path'], 'ukuran_file' => $result['size'], 'status_verifikasi' => 'pending'], 'id_dokumen = :where_id', ['where_id' => $existingDoc['id_dokumen']]);
            } else {
                db()->insert('tb_dokumen', ['id_pendaftaran' => $pendaftaran['id_pendaftaran'], 'jenis_dokumen' => $jenisDokumen, 'nama_file' => $result['filename'], 'path_file' => $result['path'], 'ukuran_file' => $result['size']]);
            }
            Session::flash('success', 'Dokumen berhasil diupload.');
            redirect('dokumen.php');
        } else {
            $error = $result['error'];
        }
    }
}

// Handle sertifikat prestasi upload - MUTIA
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['sertifikat']) && isset($_POST['prestasi_id'])) {
    $prestasiId = (int) $_POST['prestasi_id'];
    $prestasi = db()->fetch("SELECT * FROM tb_prestasi_siswa WHERE id_prestasi_siswa = ? AND id_pendaftaran = ?", [$prestasiId, $pendaftaran['id_pendaftaran']]);
    if ($prestasi) {
        $uploadPath = UPLOADS_PATH . 'prestasi/' . $pendaftaran['id_pendaftaran'] . '/';
        $result = uploadFile($_FILES['sertifikat'], $uploadPath);
        if ($result['success']) {
            if (!empty($prestasi['file_sertifikat']) && file_exists($uploadPath . $prestasi['file_sertifikat'])) {
                unlink($uploadPath . $prestasi['file_sertifikat']);
            }
            db()->update('tb_prestasi_siswa', ['file_sertifikat' => $result['filename']], 'id_prestasi_siswa = :where_id', ['where_id' => $prestasiId]);
            Session::flash('success', 'Sertifikat prestasi berhasil diupload.');
            redirect('dokumen.php');
        } else {
            $error = $result['error'];
        }
    } else {
        $error = 'Data prestasi tidak ditemukan.';
    }
}

$pageTitle = 'Upload Dokumen';
require_once 'includes/header.php';

$requiredDocs = ['Kartu Keluarga (KK)' => true, 'Akta Kelahiran' => true, 'Ijazah/SKL SMP' => true, 'Raport Semester Terakhir' => true, 'Pas Foto 3x4' => true];

switch ($pendaftaran['kode_jalur']) {
    case 'afirmasi':
        $requiredDocs['Kartu Indonesia Pintar (KIP)/PKH/KIS'] = true;
        $requiredDocs['SKTM dari Kelurahan'] = true;
        break;
    case 'prestasi':
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
foreach ($uploadedDocs as $doc) { $uploadedDocsMap[$doc['jenis_dokumen']] = $doc; }
?>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div><h5 class="mb-0">Upload Dokumen Persyaratan</h5><small class="text-muted">No: <?= $pendaftaran['nomor_pendaftaran'] ?></small></div>
        <?= getJalurBadge($pendaftaran['kode_jalur']) ?>
    </div>
</div>

<?php if ($error): ?><div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i><?= $error ?></div><?php endif; ?>

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
                    <table class="table table-dark mb-0">
                        <thead><tr><th>Jenis Dokumen</th><th>Status</th><th>File</th><th>Aksi</th></tr></thead>
                        <tbody>
                            <?php foreach ($requiredDocs as $doc => $required): ?>
                            <tr>
                                <td><?= htmlspecialchars($doc) ?><?= $required ? '<span class="text-danger">*</span>' : '' ?></td>
                                <td><?= isset($uploadedDocsMap[$doc]) ? getStatusBadge($uploadedDocsMap[$doc]['status_verifikasi']) : '<span class="badge bg-secondary">Belum Upload</span>' ?></td>
                                <td><?= isset($uploadedDocsMap[$doc]) ? '<small>'.$uploadedDocsMap[$doc]['nama_file'].'</small>' : '<small class="text-muted">-</small>' ?></td>
                                <td><?php if (isset($uploadedDocsMap[$doc])): ?><a href="<?= UPLOADS_URL ?>/dokumen/<?= $pendaftaran['id_pendaftaran'] ?>/<?= $uploadedDocsMap[$doc]['nama_file'] ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a><?php endif; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

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
                            <thead><tr><th>Nama Prestasi</th><th>Tingkat</th><th>Peringkat</th><th>Poin</th><th>Sertifikat</th><th>Aksi</th></tr></thead>
                            <tbody>
                                <?php foreach ($prestasiList as $p): ?>
                                <tr>
                                    <td><?= htmlspecialchars($p['nama_prestasi']) ?></td>
                                    <td><?= $p['tingkat'] ?></td>
                                    <td><span class="badge bg-warning text-dark"><?= $p['peringkat'] ?></span></td>
                                    <td><strong class="text-success"><?= $p['poin'] ?></strong></td>
                                    <td><?= !empty($p['file_sertifikat']) ? '<span class="badge bg-success"><i class="bi bi-check"></i> Sudah Upload</span>' : '<span class="badge bg-secondary">Belum Upload</span>' ?></td>
                                    <td>
                                        <?php if (!empty($p['file_sertifikat'])): ?><a href="<?= UPLOADS_URL ?>/prestasi/<?= $pendaftaran['id_pendaftaran'] ?>/<?= $p['file_sertifikat'] ?>" target="_blank" class="btn btn-sm btn-outline-primary" title="Lihat"><i class="bi bi-eye"></i></a><?php endif; ?>
                                        <button type="button" class="btn btn-sm btn-primary btn-upload-sertifikat" data-id="<?= $p['id_prestasi_siswa'] ?>" data-nama="<?= htmlspecialchars($p['nama_prestasi']) ?>" data-bs-toggle="modal" data-bs-target="#modalUploadSertifikat"><i class="bi bi-upload"></i></button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="modal fade" id="modalUploadSertifikat" tabindex="-1">
            <div class="modal-dialog"><div class="modal-content bg-dark">
                <div class="modal-header"><h5 class="modal-title"><i class="bi bi-upload me-2"></i>Upload Sertifikat</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="prestasi_id" id="inputPrestasiId">
                        <p class="mb-3">Upload sertifikat untuk: <strong id="labelPrestasi"></strong></p>
                        <div class="mb-3"><label class="form-label">File Sertifikat</label>
                            <div class="file-upload"><input type="file" name="sertifikat" accept=".pdf,.jpg,.jpeg,.png" required><div class="file-upload-icon"><i class="bi bi-cloud-arrow-up"></i></div><div class="file-upload-text">Klik atau drag file ke sini</div><div class="file-upload-hint">PDF, JPG, PNG (max 5MB)</div></div>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="bi bi-upload me-2"></i>Upload</button></div>
                </form>
            </div></div>
        </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between mt-4">
            <a href="pendaftaran.php" class="btn btn-dark"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
            <a href="status.php" class="btn btn-primary">Lanjut ke Status <i class="bi bi-arrow-right ms-2"></i></a>
        </div>
    </div>
</div>

<script>
document.getElementById('fileInput').addEventListener('change', function(e) {
    const fileText = document.getElementById('fileText');
    const fileUpload = document.querySelector('.file-upload');
    if (this.files.length > 0) { fileText.textContent = this.files[0].name; fileUpload.classList.add('has-file'); }
    else { fileText.textContent = 'Klik atau drag file ke sini'; fileUpload.classList.remove('has-file'); }
});
document.querySelectorAll('.btn-upload-sertifikat').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('inputPrestasiId').value = this.dataset.id;
        document.getElementById('labelPrestasi').textContent = this.dataset.nama;
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>
