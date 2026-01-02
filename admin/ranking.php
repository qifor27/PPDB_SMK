<?php

/**
 * Admin Sekolah - Ranking Pendaftar
 */
$pageTitle = 'Ranking Pendaftar';
require_once 'includes/header.php';
require_once dirname(__DIR__) . '/config/scoring.php';

// Filters
$filterJurusan = $_GET['jurusan'] ?? '';
$filterTahap = $_GET['tahap'] ?? '';

// Get jurusan for this school
$jurusanList = db()->fetchAll("SELECT * FROM tb_kejuruan WHERE id_smk = ?", [$smkId]);

// Handle recalculate ranking
if (isset($_POST['recalculate'])) {
    // Get all pendaftar for this school
    $pendaftarList = db()->fetchAll(
        "SELECT p.*, s.tanggal_lahir 
         FROM tb_pendaftaran p
         JOIN tb_siswa s ON p.id_siswa = s.id_siswa
         WHERE p.id_smk_pilihan1 = ? AND p.status IN ('submitted', 'verified')",
        [$smkId]
    );

    foreach ($pendaftarList as $p) {
        // Calculate bobot_rapor
        $bobotRapor = getBobotRapor($p['nilai_rata_rata'] ?? 0);

        // Calculate nilai_akumulasi (30% rapor + 70% tes)
        $nilaiAkumulasi = null;
        if ($p['nilai_tes']) {
            $nilaiAkumulasi = hitungNilaiAkhirSMK($bobotRapor, $p['nilai_tes']);
        }

        // Calculate umur in months
        $umurBulan = 0;
        if ($p['tanggal_lahir']) {
            $birthDate = new DateTime($p['tanggal_lahir']);
            $now = new DateTime();
            $diff = $now->diff($birthDate);
            $umurBulan = ($diff->y * 12) + $diff->m;
        }

        // Update record
        db()->update('tb_pendaftaran', [
            'bobot_rapor' => $bobotRapor,
            'nilai_akumulasi' => $nilaiAkumulasi,
            'umur_bulan' => $umurBulan
        ], 'id_pendaftaran = :id', ['id' => $p['id_pendaftaran']]);
    }

    // Now assign rankings per jurusan per tahap
    $jurusanIds = array_column($jurusanList, 'id_program');
    foreach ($jurusanIds as $jId) {
        foreach ([1, 2] as $tahap) {
            $ranked = db()->fetchAll(
                "SELECT id_pendaftaran FROM tb_pendaftaran 
                 WHERE id_smk_pilihan1 = ? AND id_kejuruan_pilihan1 = ? AND tahap_pendaftaran = ?
                 AND status IN ('submitted', 'verified')
                 ORDER BY nilai_akumulasi DESC, umur_bulan DESC, tanggal_daftar ASC",
                [$smkId, $jId, $tahap]
            );

            foreach ($ranked as $i => $r) {
                db()->update(
                    'tb_pendaftaran',
                    ['ranking_sekolah' => $i + 1],
                    'id_pendaftaran = :id',
                    ['id' => $r['id_pendaftaran']]
                );
            }
        }
    }

    Session::flash('success', 'Ranking berhasil dihitung ulang.');
    redirect('ranking.php');
}

// Build query for display
$where = "p.id_smk_pilihan1 = ?";
$params = [$smkId];

if ($filterJurusan) {
    $where .= " AND p.id_kejuruan_pilihan1 = ?";
    $params[] = $filterJurusan;
}
if ($filterTahap) {
    $where .= " AND p.tahap_pendaftaran = ?";
    $params[] = $filterTahap;
}

$rankingData = db()->fetchAll(
    "SELECT p.*, s.nama_lengkap, s.nisn, k.nama_kejuruan
     FROM tb_pendaftaran p
     JOIN tb_siswa s ON p.id_siswa = s.id_siswa
     LEFT JOIN tb_kejuruan k ON p.id_kejuruan_pilihan1 = k.id_program
     WHERE $where AND p.status IN ('submitted', 'verified', 'accepted')
     ORDER BY p.id_kejuruan_pilihan1, p.tahap_pendaftaran, p.ranking_sekolah ASC, p.nilai_akumulasi DESC",
    $params
);
?>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Jurusan</label>
                        <select name="jurusan" class="form-select">
                            <option value="">Semua Jurusan</option>
                            <?php foreach ($jurusanList as $j): ?>
                                <option value="<?= $j['id_program'] ?>" <?= $filterJurusan == $j['id_program'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($j['nama_kejuruan']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tahap</label>
                        <select name="tahap" class="form-select">
                            <option value="">Semua Tahap</option>
                            <option value="1" <?= $filterTahap === '1' ? 'selected' : '' ?>>Tahap 1</option>
                            <option value="2" <?= $filterTahap === '2' ? 'selected' : '' ?>>Tahap 2</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-filter"></i> Filter
                        </button>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <form method="POST" class="w-100">
                            <button type="submit" name="recalculate" class="btn btn-outline-primary w-100">
                                <i class="bi bi-arrow-repeat"></i> Hitung Ulang
                            </button>
                        </form>
                    </div>
                </form>
            </div>
        </div>

        <!-- Ranking Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-trophy me-2"></i>Ranking Pendaftar</h5>
                <span class="badge bg-primary"><?= count($rankingData) ?> data</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" width="60">Rank</th>
                                <th>No. Pendaftaran</th>
                                <th>Nama Siswa</th>
                                <th>Jurusan</th>
                                <th class="text-center">Tahap</th>
                                <th class="text-center">Akumulasi</th>
                                <th class="text-center">TMB</th>
                                <th class="text-center">Rapor</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rankingData as $r): ?>
                                <tr>
                                    <td class="text-center">
                                        <span class="badge <?= $r['ranking_sekolah'] <= 3 ? 'bg-warning text-dark' : 'bg-secondary' ?> fs-6">
                                            <?= $r['ranking_sekolah'] ?? '-' ?>
                                        </span>
                                    </td>
                                    <td><code><?= $r['nomor_pendaftaran'] ?></code></td>
                                    <td><?= htmlspecialchars($r['nama_lengkap']) ?></td>
                                    <td><small><?= htmlspecialchars($r['nama_kejuruan'] ?? '-') ?></small></td>
                                    <td class="text-center">
                                        <span class="badge <?= ($r['tahap_pendaftaran'] ?? 1) == 1 ? 'bg-primary' : 'bg-info' ?>">
                                            <?= $r['tahap_pendaftaran'] ?? 1 ?>
                                        </span>
                                    </td>
                                    <td class="text-center fw-bold text-primary">
                                        <?= $r['nilai_akumulasi'] ? number_format($r['nilai_akumulasi'], 2) : '-' ?>
                                    </td>
                                    <td class="text-center"><?= $r['nilai_tes'] ? number_format($r['nilai_tes'], 1) : '-' ?></td>
                                    <td class="text-center"><?= $r['bobot_rapor'] ? number_format($r['bobot_rapor'], 1) : '-' ?></td>
                                    <td><?= getStatusBadge($r['status']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($rankingData)): ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">Belum ada data ranking</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Panduan Ranking</h6>
            </div>
            <div class="card-body">
                <p class="small mb-2"><strong>Formula Nilai Akhir:</strong></p>
                <div class="alert alert-info py-2 mb-3">
                    <code>30% Bobot Rapor + 70% Nilai TMB</code>
                </div>
                <p class="small mb-2"><strong>Urutan Prioritas:</strong></p>
                <ol class="small mb-0">
                    <li>Nilai Akumulasi (tertinggi)</li>
                    <li>Umur (tertua)</li>
                    <li>Tanggal Daftar (terdahulu)</li>
                </ol>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-lightning me-2"></i>Aksi Cepat</h6>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="seleksi.php" class="btn btn-primary">
                    <i class="bi bi-pencil-square me-2"></i>Input Nilai Tes
                </a>
                <a href="export.php?type=ranking" class="btn btn-outline-primary">
                    <i class="bi bi-download me-2"></i>Export Ranking
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>