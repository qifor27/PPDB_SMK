<?php

/**
 * Admin Sekolah - Seleksi Jalur Kepindahan
 * Tugas: VELI
 */
$pageTitle = 'Seleksi Jalur Kepindahan';
require_once 'includes/header.php';

// Get kepindahan jalur ID
$jalurKepindahan = db()->fetch("SELECT id_jalur FROM tb_jalur WHERE kode_jalur = 'kepindahan'");
$jalurId = $jalurKepindahan['id_jalur'] ?? 0;

// Get kuota for kepindahan
$kuota = db()->fetch(
    "SELECT * FROM tb_kuota WHERE id_smk = ? AND id_jalur = ? AND tahun_ajaran = ?",
    [$smkId, $jalurId, getTahunAjaran()]
);

// Filters
$filterStatus = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';

// Build query
$where = "p.id_smk_pilihan1 = ? AND j.kode_jalur = 'kepindahan'";
$params = [$smkId];

if ($filterStatus) {
    $where .= " AND p.status = ?";
    $params[] = $filterStatus;
}
if ($search) {
    $where .= " AND (s.nama_lengkap LIKE ? OR s.nisn LIKE ? OR p.nomor_pendaftaran LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Get pendaftar jalur kepindahan
$pendaftarList = db()->fetchAll(
    "SELECT p.*, s.*, j.nama_jalur, j.kode_jalur
     FROM tb_pendaftaran p
     JOIN tb_siswa s ON p.id_siswa = s.id_siswa
     JOIN tb_jalur j ON p.id_jalur = j.id_jalur
     WHERE $where
     ORDER BY 
        CASE p.status 
            WHEN 'verified' THEN 1 
            WHEN 'submitted' THEN 2 
            WHEN 'accepted' THEN 3 
            WHEN 'rejected' THEN 4 
            ELSE 5 
        END,
        p.tanggal_submit ASC",
    $params
);

// Count by status
$countByStatus = [
    'submitted' => 0,
    'verified' => 0,
    'accepted' => 0,
    'rejected' => 0
];
foreach ($pendaftarList as $p) {
    if (isset($countByStatus[$p['status']])) {
        $countByStatus[$p['status']]++;
    }
}
?>

<!-- Header -->
<div class="card mb-4">
    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0"><i class="bi bi-arrow-left-right me-2"></i>Seleksi Jalur Kepindahan Orang Tua</h5>
            <small>Manajemen pendaftar jalur kepindahan kedinasan</small>
        </div>
        <a href="pendaftar.php" class="btn btn-light btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h3 class="text-primary mb-0"><?= $kuota['kuota'] ?? 0 ?></h3>
                <small class="text-muted">Kuota</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h3 class="text-warning mb-0"><?= $countByStatus['submitted'] + $countByStatus['verified'] ?></h3>
                <small class="text-muted">Menunggu</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h3 class="text-success mb-0"><?= $countByStatus['accepted'] ?></h3>
                <small class="text-muted">Diterima</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h3 class="text-danger mb-0"><?= $countByStatus['rejected'] ?></h3>
                <small class="text-muted">Ditolak</small>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari nama/NISN/no pendaftaran"
                    value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="submitted" <?= $filterStatus === 'submitted' ? 'selected' : '' ?>>Submitted</option>
                    <option value="verified" <?= $filterStatus === 'verified' ? 'selected' : '' ?>>Verified</option>
                    <option value="accepted" <?= $filterStatus === 'accepted' ? 'selected' : '' ?>>Accepted</option>
                    <option value="rejected" <?= $filterStatus === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search me-1"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-people me-2"></i>Daftar Pendaftar Jalur Kepindahan</h5>
        <span class="badge bg-info"><?= count($pendaftarList) ?> pendaftar</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>No. Pendaftaran</th>
                        <th>Nama Siswa</th>
                        <th>Jenis Instansi</th>
                        <th>Kota Asal</th>
                        <th>No. SK Pindah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendaftarList as $i => $p): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><code><?= $p['nomor_pendaftaran'] ?></code></td>
                            <td>
                                <?= htmlspecialchars($p['nama_lengkap']) ?>
                                <br><small class="text-muted">NISN: <?= $p['nisn'] ?></small>
                            </td>
                            <td>
                                <span
                                    class="badge bg-primary"><?= htmlspecialchars($p['jenis_instansi_ortu'] ?? '-') ?></span>
                            </td>
                            <td><?= htmlspecialchars($p['kota_asal'] ?? '-') ?></td>
                            <td>
                                <small><?= htmlspecialchars($p['nomor_sk_pindah'] ?? '-') ?></small>
                                <?php if (!empty($p['tanggal_sk_pindah'])): ?>
                                    <br><small class="text-muted"><?= formatDate($p['tanggal_sk_pindah']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?= getStatusBadge($p['status']) ?></td>
                            <td>
                                <a href="detail-siswa.php?id=<?= $p['id_pendaftaran'] ?>"
                                    class="btn btn-sm btn-outline-primary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <?php if ($p['status'] === 'submitted'): ?>
                                    <a href="verifikasi.php?id=<?= $p['id_pendaftaran'] ?>" class="btn btn-sm btn-primary"
                                        title="Verifikasi">
                                        <i class="bi bi-check2"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($pendaftarList)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Tidak ada pendaftar jalur kepindahan</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Info Box -->
<div class="alert alert-info mt-4">
    <h6 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Persyaratan Jalur Kepindahan</h6>
    <ul class="mb-0">
        <li>SK Pindah Tugas Orang Tua (ASN/TNI/POLRI/BUMN)</li>
        <li>Surat Keterangan dari Instansi</li>
        <li>KK Baru (Setelah Pindah)</li>
        <li>Surat Pindah Sekolah dari sekolah asal</li>
    </ul>
</div>

<?php require_once 'includes/footer.php'; ?>