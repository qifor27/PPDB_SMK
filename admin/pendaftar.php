<?php

/**
 * Admin Sekolah - Data Pendaftar
 * Updated: 2026-01-02 - Disesuaikan dengan alur tahap pendaftaran
 */
$pageTitle = 'Data Pendaftar';
require_once 'includes/header.php';

// Filters
$filterTahap = $_GET['tahap'] ?? '';
$filterJurusan = $_GET['jurusan'] ?? '';
$filterStatus = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';

// Build query
$where = "p.id_smk_pilihan1 = ?";
$params = [$smkId];

if ($filterTahap) {
    $where .= " AND p.tahap_pendaftaran = ?";
    $params[] = $filterTahap;
}
if ($filterJurusan) {
    $where .= " AND p.id_kejuruan_pilihan1 = ?";
    $params[] = $filterJurusan;
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
    "SELECT p.*, s.nama_lengkap, s.nisn, s.jenis_kelamin, k.nama_kejuruan
     FROM tb_pendaftaran p
     JOIN tb_siswa s ON p.id_siswa = s.id_siswa
     LEFT JOIN tb_kejuruan k ON p.id_kejuruan_pilihan1 = k.id_program
     WHERE $where
     ORDER BY p.tanggal_daftar DESC",
    $params
);

// Get jurusan list for this school
$jurusanList = db()->fetchAll("SELECT * FROM tb_kejuruan WHERE id_smk = ?", [$smkId]);
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
                <select name="tahap" class="form-select">
                    <option value="">Semua Tahap</option>
                    <option value="1" <?= $filterTahap === '1' ? 'selected' : '' ?>>Tahap 1</option>
                    <option value="2" <?= $filterTahap === '2' ? 'selected' : '' ?>>Tahap 2</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="jurusan" class="form-select">
                    <option value="">Semua Jurusan</option>
                    <?php foreach ($jurusanList as $j): ?>
                        <option value="<?= $j['id_program'] ?>" <?= $filterJurusan == $j['id_program'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($j['nama_kejuruan']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="draft" <?= $filterStatus === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="submitted" <?= $filterStatus === 'submitted' ? 'selected' : '' ?>>Submitted</option>
                    <option value="verified" <?= $filterStatus === 'verified' ? 'selected' : '' ?>>Verified</option>
                    <option value="accepted" <?= $filterStatus === 'accepted' ? 'selected' : '' ?>>Diterima</option>
                    <option value="rejected" <?= $filterStatus === 'rejected' ? 'selected' : '' ?>>Ditolak</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i>
                </button>
            </div>
            <div class="col-md-2 text-end">
                <a href="export.php?type=pendaftar" class="btn btn-outline-primary">
                    <i class="bi bi-download me-1"></i>Export
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Quick Filter Buttons -->
<div class="d-flex gap-2 mb-4 flex-wrap">
    <span class="text-muted align-self-center me-2">Quick Filter:</span>
    <a href="pendaftar.php?tahap=1" class="btn btn-sm <?= $filterTahap === '1' ? 'btn-primary' : 'btn-outline-primary' ?>">
        <i class="bi bi-1-circle me-1"></i>Tahap 1
    </a>
    <a href="pendaftar.php?tahap=2" class="btn btn-sm <?= $filterTahap === '2' ? 'btn-warning' : 'btn-outline-warning' ?>">
        <i class="bi bi-2-circle me-1"></i>Tahap 2
    </a>
    <a href="pendaftar.php?status=submitted" class="btn btn-sm <?= $filterStatus === 'submitted' ? 'btn-info' : 'btn-outline-info' ?>">
        <i class="bi bi-hourglass me-1"></i>Perlu Verifikasi
    </a>
    <?php if ($filterTahap || $filterStatus || $filterJurusan): ?>
        <a href="pendaftar.php" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-x-circle me-1"></i>Reset
        </a>
    <?php endif; ?>
</div>

<!-- Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-people me-2"></i>Daftar Pendaftar</h5>
        <span class="badge bg-primary"><?= count($pendaftarList) ?> data</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>No. Pendaftaran</th>
                        <th>Nama Siswa</th>
                        <th>NISN</th>
                        <th>L/P</th>
                        <th>Tahap</th>
                        <th>Jurusan</th>
                        <th>Status</th>
                        <th>Tanggal</th>
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
                            <td>
                                <?php $tahap = $p['tahap_pendaftaran'] ?? 1; ?>
                                <span class="badge <?= $tahap == 1 ? 'bg-primary' : 'bg-warning text-dark' ?>">
                                    Tahap <?= $tahap ?>
                                </span>
                            </td>
                            <td><small><?= htmlspecialchars($p['nama_kejuruan'] ?? '-') ?></small></td>
                            <td><?= getStatusBadge($p['status']) ?></td>
                            <td><small><?= formatDate($p['tanggal_daftar'], 'd M Y') ?></small></td>
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
                            <td colspan="10" class="text-center text-muted py-4">Tidak ada data pendaftar</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>