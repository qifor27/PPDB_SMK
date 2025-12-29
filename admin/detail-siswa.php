<?php
/**
 * Admin Sekolah - Detail Siswa
 */
$pageTitle = 'Detail Pendaftar';
require_once 'includes/header.php';

$pendaftaranId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Get pendaftaran with siswa data
$data = db()->fetch(
    "SELECT p.*, s.*, j.nama_jalur, j.kode_jalur, smk1.nama_sekolah as sekolah1, smk2.nama_sekolah as sekolah2
     FROM tb_pendaftaran p
     JOIN tb_siswa s ON p.id_siswa = s.id_siswa
     JOIN tb_jalur j ON p.id_jalur = j.id_jalur
     LEFT JOIN tb_smk smk1 ON p.id_smk_pilihan1 = smk1.id_smk
     LEFT JOIN tb_smk smk2 ON p.id_smk_pilihan2 = smk2.id_smk
     WHERE p.id_pendaftaran = ? AND p.id_smk_pilihan1 = ?",
    [$pendaftaranId, $smkId]
);

if (!$data) {
    Session::flash('error', 'Data tidak ditemukan.');
    redirect('pendaftar.php');
}

// Get documents
$dokumen = db()->fetchAll("SELECT * FROM tb_dokumen WHERE id_pendaftaran = ?", [$pendaftaranId]);

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newStatus = $_POST['status'];
    $alasan = sanitize($_POST['alasan'] ?? '');

    $updateData = ['status' => $newStatus];
    if ($newStatus === 'rejected') {
        $updateData['alasan_penolakan'] = $alasan;
    }
    if ($newStatus === 'verified') {
        $updateData['tanggal_verifikasi'] = date('Y-m-d H:i:s');
        $updateData['verified_by'] = $adminId;
    }
    if ($newStatus === 'accepted') {
        $updateData['tanggal_pengumuman'] = date('Y-m-d H:i:s');
    }

    db()->update('tb_pendaftaran', $updateData, 'id_pendaftaran = :where_id', ['where_id' => $pendaftaranId]);
    Session::flash('success', 'Status pendaftaran berhasil diperbarui.');
    redirect("detail-siswa.php?id={$pendaftaranId}");
}
?>

<div class="mb-3">
    <a href="pendaftar.php" class="btn btn-dark"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <!-- Data Siswa -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-person me-2"></i>Data Siswa</h5>
                <div>
                    <?= getJalurBadge($data['kode_jalur']) ?>
                    <?= getStatusBadge($data['status']) ?>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Nama Lengkap</label>
                        <div class="fw-bold"><?= htmlspecialchars($data['nama_lengkap']) ?></div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">NISN</label>
                        <div><?= $data['nisn'] ?></div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Jenis Kelamin</label>
                        <div><?= $data['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Tempat, Tanggal Lahir</label>
                        <div><?= htmlspecialchars($data['tempat_lahir']) ?>, <?= formatDate($data['tanggal_lahir']) ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Agama</label>
                        <div><?= htmlspecialchars($data['agama'] ?? '-') ?></div>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small">Usia</label>
                        <div><?= calculateAge($data['tanggal_lahir']) ?> tahun</div>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small">Alamat</label>
                        <div><?= htmlspecialchars($data['alamat'] ?? '-') ?></div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Email</label>
                        <div><?= htmlspecialchars($data['email']) ?></div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">No. HP</label>
                        <div><?= htmlspecialchars($data['no_hp'] ?? '-') ?></div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Asal Sekolah</label>
                        <div><?= htmlspecialchars($data['asal_sekolah'] ?? '-') ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Orang Tua -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-people me-2"></i>Data Orang Tua</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Nama Ayah</label>
                        <div><?= htmlspecialchars($data['nama_ayah'] ?? '-') ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Pekerjaan Ayah</label>
                        <div><?= htmlspecialchars($data['pekerjaan_ayah'] ?? '-') ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Nama Ibu</label>
                        <div><?= htmlspecialchars($data['nama_ibu'] ?? '-') ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Pekerjaan Ibu</label>
                        <div><?= htmlspecialchars($data['pekerjaan_ibu'] ?? '-') ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">No. HP Orang Tua</label>
                        <div><?= htmlspecialchars($data['no_hp_ortu'] ?? '-') ?></div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($data['kode_jalur'] === 'kepindahan'): ?>
            <!-- Data Kepindahan Orang Tua - VELI -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-arrow-left-right me-2"></i>Data Kepindahan Orang Tua</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="text-muted small">Jenis Instansi</label>
                            <div class="fw-bold"><?= htmlspecialchars($data['jenis_instansi_ortu'] ?? '-') ?></div>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small">Instansi Asal</label>
                            <div><?= htmlspecialchars($data['nama_instansi_asal'] ?? '-') ?></div>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small">Instansi Tujuan</label>
                            <div><?= htmlspecialchars($data['nama_instansi_tujuan'] ?? '-') ?></div>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small">Nomor SK Pindah</label>
                            <div class="fw-bold text-primary"><?= htmlspecialchars($data['nomor_sk_pindah'] ?? '-') ?></div>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small">Tanggal SK Pindah</label>
                            <div><?= !empty($data['tanggal_sk_pindah']) ? formatDate($data['tanggal_sk_pindah']) : '-' ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small">Kota/Kabupaten Asal</label>
                            <div><?= htmlspecialchars($data['kota_asal'] ?? '-') ?></div>
                        </div>
                        <?php if (!empty($data['alasan_kepindahan'])): ?>
                            <div class="col-12">
                                <label class="text-muted small">Alasan Kepindahan</label>
                                <div><?= htmlspecialchars($data['alasan_kepindahan']) ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Dokumen -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-folder me-2"></i>Dokumen</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark mb-0">
                        <thead>
                            <tr>
                                <th>Jenis Dokumen</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dokumen as $doc): ?>
                                <tr>
                                    <td><?= htmlspecialchars($doc['jenis_dokumen']) ?></td>
                                    <td><?= getStatusBadge($doc['status_verifikasi']) ?></td>
                                    <td>
                                        <a href="<?= UPLOADS_URL ?>/dokumen/<?= $pendaftaranId ?>/<?= $doc['nama_file'] ?>"
                                            target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Lihat
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Data Pendaftaran -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-file-text me-2"></i>Pendaftaran</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small">No. Pendaftaran</label>
                    <div class="fw-bold text-primary"><?= $data['nomor_pendaftaran'] ?></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Jalur</label>
                    <div><?= getJalurBadge($data['kode_jalur']) ?></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">SMK Pilihan 1</label>
                    <div><?= htmlspecialchars($data['sekolah1']) ?></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">SMK Pilihan 2</label>
                    <div><?= htmlspecialchars($data['sekolah2'] ?? '-') ?></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Nilai Rata-rata</label>
                    <div><?= $data['nilai_rata_rata'] ?? '-' ?></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Jarak ke Sekolah</label>
                    <div><?= $data['jarak_ke_sekolah'] ? formatDistance($data['jarak_ke_sekolah']) : '-' ?></div>
                </div>
                <div>
                    <label class="text-muted small">Tanggal Daftar</label>
                    <div><?= formatDateTime($data['tanggal_daftar']) ?></div>
                </div>
            </div>
        </div>

        <!-- Update Status -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Update Status</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="submitted" <?= $data['status'] === 'submitted' ? 'selected' : '' ?>>Submitted
                            </option>
                            <option value="verified" <?= $data['status'] === 'verified' ? 'selected' : '' ?>>Verified
                            </option>
                            <option value="accepted" <?= $data['status'] === 'accepted' ? 'selected' : '' ?>>Accepted
                            </option>
                            <option value="rejected" <?= $data['status'] === 'rejected' ? 'selected' : '' ?>>Rejected
                            </option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alasan (jika ditolak)</label>
                        <textarea name="alasan" class="form-control"
                            rows="2"><?= htmlspecialchars($data['alasan_penolakan'] ?? '') ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-lg me-2"></i>Update Status
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>