<?php

/**
 * Super Admin - Semua Pendaftar
 */
$pageTitle = 'Semua Pendaftar';
require_once 'includes/header.php';

// Filters
$filterStatus = $_GET['status'] ?? '';
$filterJalur = $_GET['jalur'] ?? '';
$filterSmk = $_GET['smk'] ?? '';
$search = $_GET['search'] ?? '';

// Build query
$where = [];
$params = [];

if ($filterStatus) {
    $where[] = 'p.status = ?';
    $params[] = $filterStatus;
}
if ($filterJalur) {
    $where[] = 'p.id_jalur = ?';
    $params[] = $filterJalur;
}
if ($filterSmk) {
    $where[] = 'p.id_smk_pilihan1 = ?';
    $params[] = $filterSmk;
}
if ($search) {
    $where[] = '(s.nama_lengkap LIKE ? OR p.nomor_pendaftaran LIKE ?)';
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$pendaftar = db()->fetchAll(
    "SELECT p.*, s.nama_lengkap, s.nisn, j.nama_jalur, j.kode_jalur, smk.nama_sekolah
     FROM tb_pendaftaran p
     JOIN tb_siswa s ON p.id_siswa = s.id_siswa
     LEFT JOIN tb_jalur j ON p.id_jalur = j.id_jalur
     LEFT JOIN tb_smk smk ON p.id_smk_pilihan1 = smk.id_smk
     $whereClause
     ORDER BY p.created_at DESC
     LIMIT 100",
    $params
);

$jalurList = getAllJalur();
$smkList = getAllSMK();
?>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i>Data Semua Pendaftar</h5>
            </div>
            <div class="col-md-6">
                <form method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama/nomor..." value="<?= htmlspecialchars($search) ?>">
                    <select name="status" class="form-select form-select-sm" style="width: auto;">
                        <option value="">Semua Status</option>
                        <option value="draft" <?= $filterStatus === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="submitted" <?= $filterStatus === 'submitted' ? 'selected' : '' ?>>Submitted</option>
                        <option value="verified" <?= $filterStatus === 'verified' ? 'selected' : '' ?>>Verified</option>
                        <option value="accepted" <?= $filterStatus === 'accepted' ? 'selected' : '' ?>>Diterima</option>
                        <option value="rejected" <?= $filterStatus === 'rejected' ? 'selected' : '' ?>>Ditolak</option>
                    </select>
                    <button class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No. Daftar</th>
                        <th>Nama</th>
                        <th>SMK Tujuan</th>
                        <th>Jalur</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pendaftar)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Tidak ada data pendaftar</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pendaftar as $p): ?>
                            <tr>
                                <td><code><?= $p['nomor_pendaftaran'] ?></code></td>
                                <td>
                                    <strong><?= htmlspecialchars($p['nama_lengkap']) ?></strong>
                                    <br><small class="text-muted">NISN: <?= $p['nisn'] ?></small>
                                </td>
                                <td><?= htmlspecialchars($p['nama_sekolah'] ?? '-') ?></td>
                                <td><?= getJalurBadge($p['kode_jalur']) ?></td>
                                <td><?= getStatusBadge($p['status']) ?></td>
                                <td><small><?= formatDate($p['created_at']) ?></small></td>
                                <td>
                                    <a href="detail-pendaftar.php?id=<?= $p['id_pendaftaran'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>