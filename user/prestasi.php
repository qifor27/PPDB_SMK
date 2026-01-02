<?php

/**
 * User - Data Prestasi
 */
$pageTitle = 'Data Prestasi';
require_once 'includes/header.php';

// Cek pendaftaran - allow for all jalur
if (!$pendaftaran) {
    Session::flash('error', 'Silakan lengkapi pendaftaran terlebih dahulu.');
    redirect(SITE_URL . '/user/pilih-sekolah-smk.php');
}

// Get prestasi list
$prestasiList = getPrestasiByPendaftaran($pendaftaran['id_pendaftaran']);
$totalPoin = getTotalPrestasiPoin($pendaftaran['id_pendaftaran']);
?>

<div class="row">
    <div class="col-12">
        <!-- Info Card -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h5 class="mb-1">Data Prestasi Siswa</h5>
                        <p class="text-muted mb-0">Daftar prestasi yang diajukan untuk penambahan poin seleksi</p>
                    </div>
                    <div class="text-end">
                        <small class="text-muted d-block">Total Poin Valid</small>
                        <h2 class="mb-0 text-primary fw-bold"><?= $totalPoin ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <?php if (empty($prestasiList)): ?>
            <div class="text-center py-5">
                <img src="<?= SITE_URL ?>/assets/img/empty.svg" alt="Empty" style="width: 150px; opacity: 0.5" class="mb-3">
                <h5>Belum ada data prestasi</h5>
                <p class="text-muted">Anda belum menambahkan data prestasi.</p>
                <a href="tambah-prestasi.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Prestasi
                </a>
            </div>
        <?php else: ?>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="bi bi-trophy me-2"></i>Daftar Prestasi</h6>
                    <a href="tambah-prestasi.php" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Prestasi
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Nama Prestasi</th>
                                <th>Tingkat</th>
                                <th>Peringkat</th>
                                <th>Poin</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($prestasiList as $p): ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold"><?= htmlspecialchars($p['nama_prestasi']) ?></div>
                                        <small class="text-muted">
                                            <?= htmlspecialchars($p['jenis_prestasi']) ?> • <?= $p['tahun'] ?>
                                        </small>
                                        <?php if (!empty($p['file_sertifikat'])): ?>
                                            <div class="mt-1">
                                                <a href="<?= UPLOADS_URL ?>prestasi/<?= $pendaftaran['id_pendaftaran'] ?>/<?= $p['file_sertifikat'] ?>"
                                                    target="_blank" class="small text-decoration-none">
                                                    <i class="bi bi-paperclip me-1"></i>Lihat Sertifikat
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <div class="mt-1">
                                                <small class="text-warning"><i class="bi bi-exclamation-circle me-1"></i>Belum
                                                    upload sertifikat</small>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($p['tingkat']) ?></td>
                                    <td><?= htmlspecialchars($p['peringkat']) ?></td>
                                    <td>
                                        <span class="badge bg-primary-soft text-primary">+<?= $p['poin'] ?></span>
                                    </td>
                                    <td>
                                        <?php if ($p['status_verifikasi'] === 'valid'): ?>
                                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Valid</span>
                                        <?php elseif ($p['status_verifikasi'] === 'invalid'): ?>
                                            <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Invalid</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning"><i class="bi bi-clock me-1"></i>Pending</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>