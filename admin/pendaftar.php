<?php
/**
 * Admin Sekolah - Data Pendaftar
 */
$pageTitle = 'Data Pendaftar';
require_once 'includes/header.php';

// Filters
$filterJalur = $_GET['jalur'] ?? '';
$filterStatus = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';

// Build query
$where = "p.id_smk_pilihan1 = ?";
$params = [$smkId];

if ($filterJalur) {
    $where .= " AND p.id_jalur = ?";
    $params[] = $filterJalur;
}
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

$pendaftarList = db()->fetchAll(
    "SELECT p.*, s.nama_lengkap, s.nisn, s.jenis_kelamin, j.nama_jalur, j.kode_jalur
     FROM tb_pendaftaran p
     JOIN tb_siswa s ON p.id_siswa = s.id_siswa
     JOIN tb_jalur j ON p.id_jalur = j.id_jalur
     WHERE $where
     ORDER BY p.tanggal_daftar DESC",
    $params
);

$jalurList = getAllJalur();
?>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Cari nama/NISN/no pendaftaran" 
                       value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-2">
                <select name="jalur" class="form-select">
                    <option value="">Semua Jalur</option>
                    <?php foreach ($jalurList as $j): ?>
                    <option value="<?= $j['id_jalur'] ?>" <?= $filterJalur == $j['id_jalur'] ? 'selected' : '' ?>><?= $j['nama_jalur'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="draft" <?= $filterStatus === 'draft' ? 'selected' : '' ?>>Draft</option>
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
            <div class="col-md-3 text-end">
                <a href="export.php?type=pendaftar" class="btn btn-outline-primary">
                    <i class="bi bi-download me-1"></i> Export Excel
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-people me-2"></i>Daftar Pendaftar</h5>
        <span class="badge bg-primary"><?= count($pendaftarList) ?> data</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>No. Pendaftaran</th>
                        <th>Nama Siswa</th>
                        <th>NISN</th>
                        <th>L/P</th>
                        <th>Jalur</th>
                        <th>Status</th>
                        <th>Tanggal Daftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendaftarList as $i => $p): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><code><?= $p['nomor_pendaftaran'] ?></code></td>
                        <td><?= htmlspecialchars($p['nama_lengkap']) ?></td>
                        <td><?= $p['nisn'] ?></td>
                        <td><?= $p['jenis_kelamin'] ?></td>
                        <td><?= getJalurBadge($p['kode_jalur']) ?></td>
                        <td><?= getStatusBadge($p['status']) ?></td>
                        <td><?= formatDate($p['tanggal_daftar'], 'd M Y') ?></td>
                        <td>
                            <a href="detail-siswa.php?id=<?= $p['id_pendaftaran'] ?>" class="btn btn-sm btn-outline-primary" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <?php if ($p['status'] === 'submitted'): ?>
                            <a href="verifikasi.php?id=<?= $p['id_pendaftaran'] ?>" class="btn btn-sm btn-primary" title="Verifikasi">
                                <i class="bi bi-check2"></i>
                            </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($pendaftarList)): ?>
                    <tr><td colspan="9" class="text-center text-muted py-4">Tidak ada data</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
