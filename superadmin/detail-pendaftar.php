<?php

/**
 * Super Admin - Detail Pendaftar
 * Menampilkan detail lengkap data pendaftar
 */
$pageTitle = 'Detail Pendaftar';
require_once 'includes/header.php';

$idPendaftaran = $_GET['id'] ?? 0;

// Get pendaftar data
$pendaftar = db()->fetch("
    SELECT p.id_pendaftaran, p.nomor_pendaftaran, p.status, p.tanggal_daftar, p.tahap_pendaftaran,
           p.nilai_rata_rata, p.bobot_rapor, p.nilai_tes, p.nilai_akumulasi,
           s.nisn, s.nama_lengkap, s.tempat_lahir, s.tanggal_lahir, s.jenis_kelamin, s.no_hp,
           smk1.nama_sekolah as sekolah_1,
           smk2.nama_sekolah as sekolah_2,
           k1.nama_kejuruan as kejuruan_1,
           k2.nama_kejuruan as kejuruan_2
    FROM tb_pendaftaran p
    JOIN tb_siswa s ON p.id_siswa = s.id_siswa
    LEFT JOIN tb_smk smk1 ON p.id_smk_pilihan1 = smk1.id_smk
    LEFT JOIN tb_smk smk2 ON p.id_smk_pilihan2 = smk2.id_smk
    LEFT JOIN tb_kejuruan k1 ON p.id_kejuruan_pilihan1 = k1.id_program
    LEFT JOIN tb_kejuruan k2 ON p.id_kejuruan_pilihan2 = k2.id_program
    WHERE p.id_pendaftaran = ?
", [$idPendaftaran]);

if (!$pendaftar) {
    Session::flash('error', 'Data pendaftar tidak ditemukan.');
    redirect('pendaftar.php');
}

// Get dokumen
$dokumen = db()->fetchAll("SELECT * FROM tb_dokumen WHERE id_pendaftaran = ?", [$idPendaftaran]);

// Get prestasi
$prestasi = db()->fetchAll("SELECT * FROM tb_prestasi WHERE id_pendaftaran = ?", [$idPendaftaran]);
?>

<div class="row g-4">
    <!-- Back Button -->
    <div class="col-12">
        <a href="pendaftar.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <!-- Info Pendaftaran -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Informasi Pendaftaran</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td width="150">No. Pendaftaran</td>
                        <td><strong><?= $pendaftar['nomor_pendaftaran'] ?></strong></td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td><?= getStatusBadge($pendaftar['status']) ?></td>
                    </tr>
                    <tr>
                        <td>Tanggal Daftar</td>
                        <td><?= formatDate($pendaftar['tanggal_daftar'], 'd M Y H:i') ?> WIB</td>
                    </tr>
                    <tr>
                        <td>Tahap</td>
                        <td><span class="badge bg-info">Tahap <?= $pendaftar['tahap_pendaftaran'] ?? 1 ?></span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Data Pribadi -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="bi bi-person me-2"></i>Data Pribadi</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td width="150">NISN</td>
                        <td><?= $pendaftar['nisn'] ?></td>
                    </tr>
                    <tr>
                        <td>Nama Lengkap</td>
                        <td><strong><?= htmlspecialchars($pendaftar['nama_lengkap']) ?></strong></td>
                    </tr>
                    <tr>
                        <td>Tempat, Tgl Lahir</td>
                        <td><?= htmlspecialchars($pendaftar['tempat_lahir'] ?? '-') ?>, <?= formatDate($pendaftar['tanggal_lahir'], 'd M Y') ?></td>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin</td>
                        <td><?= $pendaftar['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                    </tr>
                    <tr>
                        <td>No. HP</td>
                        <td><?= htmlspecialchars($pendaftar['no_hp'] ?? '-') ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Pilihan Sekolah -->
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="bi bi-building me-2"></i>Pilihan Sekolah & Jurusan</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Pilihan 1</h6>
                        <table class="table table-sm">
                            <tr>
                                <td width="100">Sekolah</td>
                                <td><strong><?= htmlspecialchars($pendaftar['sekolah_1']) ?></strong></td>
                            </tr>
                            <tr>
                                <td>Jurusan</td>
                                <td><?= htmlspecialchars($pendaftar['kejuruan_1'] ?? '-') ?></td>
                            </tr>
                        </table>
                    </div>
                    <?php if ($pendaftar['sekolah_2']): ?>
                        <div class="col-md-6">
                            <h6 class="text-info">Pilihan 2</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td width="100">Sekolah</td>
                                    <td><strong><?= htmlspecialchars($pendaftar['sekolah_2']) ?></strong></td>
                                </tr>
                                <tr>
                                    <td>Jurusan</td>
                                    <td><?= htmlspecialchars($pendaftar['kejuruan_2'] ?? '-') ?></td>
                                </tr>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Nilai -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-warning">
                <h6 class="mb-0"><i class="bi bi-graph-up me-2"></i>Nilai</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td width="150">Nilai Rapor</td>
                        <td><strong><?= $pendaftar['nilai_rata_rata'] ? number_format($pendaftar['nilai_rata_rata'], 2) : '-' ?></strong></td>
                    </tr>
                    <tr>
                        <td>Bobot Rapor (30%)</td>
                        <td><?= $pendaftar['bobot_rapor'] ? number_format($pendaftar['bobot_rapor'], 2) : '-' ?></td>
                    </tr>
                    <tr>
                        <td>Nilai Tes</td>
                        <td><strong><?= $pendaftar['nilai_tes'] ? number_format($pendaftar['nilai_tes'], 2) : '-' ?></strong></td>
                    </tr>
                    <tr>
                        <td>Nilai Akumulasi</td>
                        <td>
                            <h5 class="mb-0 text-primary"><?= $pendaftar['nilai_akumulasi'] ? number_format($pendaftar['nilai_akumulasi'], 2) : '-' ?></h5>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Dokumen -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h6 class="mb-0"><i class="bi bi-folder me-2"></i>Dokumen (<?= count($dokumen) ?>)</h6>
            </div>
            <div class="card-body">
                <?php if ($dokumen): ?>
                    <div class="list-group">
                        <?php foreach ($dokumen as $dok): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?= htmlspecialchars($dok['jenis_dokumen']) ?></strong><br>
                                        <small class="text-muted"><?= formatDate($dok['tanggal_upload'], 'd M Y') ?></small>
                                    </div>
                                    <div>
                                        <?= getStatusBadge($dok['status_verifikasi']) ?>
                                        <a href="<?= UPLOADS_URL ?>/dokumen/<?= $dok['nama_file'] ?>" target="_blank" class="btn btn-sm btn-outline-primary ms-2">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center mb-0">Belum ada dokumen</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Prestasi -->
    <?php if ($prestasi): ?>
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning">
                    <h6 class="mb-0"><i class="bi bi-trophy me-2"></i>Prestasi (<?= count($prestasi) ?>)</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Jenis</th>
                                    <th>Nama Lomba</th>
                                    <th>Tingkat</th>
                                    <th>Peringkat</th>
                                    <th>Tahun</th>
                                    <th>Poin</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($prestasi as $p): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($p['jenis_prestasi']) ?></td>
                                        <td><?= htmlspecialchars($p['nama_lomba']) ?></td>
                                        <td><?= htmlspecialchars($p['tingkat']) ?></td>
                                        <td><?= htmlspecialchars($p['peringkat']) ?></td>
                                        <td><?= $p['tahun'] ?></td>
                                        <td><strong><?= $p['poin'] ?></strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>