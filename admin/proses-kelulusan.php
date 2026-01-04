<?php

/**
 * Admin Sekolah - Proses Kelulusan
 * Menentukan status diterima/ditolak berdasarkan ranking dan kuota
 */
$pageTitle = 'Proses Kelulusan';
require_once 'includes/header.php';
require_once dirname(__DIR__) . '/config/scoring.php';

// Get jurusan for this school
$jurusanList = db()->fetchAll("
    SELECT k.id_program, k.nama_kejuruan, k.kode_kejuruan,
           COALESCE(kj.kuota, 36) as kuota,
           COALESCE(kj.terisi, 0) as terisi,
           (SELECT COUNT(*) FROM tb_pendaftaran p WHERE p.id_smk_pilihan1 = k.id_smk AND p.id_kejuruan_pilihan1 = k.id_program AND p.status = 'verified') as pending,
           (SELECT COUNT(*) FROM tb_pendaftaran p WHERE p.id_smk_pilihan1 = k.id_smk AND p.id_kejuruan_pilihan1 = k.id_program AND p.status = 'accepted') as diterima,
           (SELECT COUNT(*) FROM tb_pendaftaran p WHERE p.id_smk_pilihan1 = k.id_smk AND p.id_kejuruan_pilihan1 = k.id_program AND p.status = 'rejected') as ditolak
    FROM tb_kejuruan k
    LEFT JOIN tb_kuota_jurusan kj ON k.id_program = kj.id_kejuruan AND kj.tahun_ajaran = '2025/2026'
    WHERE k.id_smk = ?
    ORDER BY k.nama_kejuruan
", [$smkId]);

// Handle process kelulusan
if (isset($_POST['proses_kelulusan']) && isset($_POST['id_kejuruan'])) {
    $kejuruanId = (int)$_POST['id_kejuruan'];

    // Get kuota for this jurusan
    $kuotaInfo = db()->fetch("
        SELECT COALESCE(kj.kuota, 36) as kuota
        FROM tb_kejuruan k
        LEFT JOIN tb_kuota_jurusan kj ON k.id_program = kj.id_kejuruan AND kj.tahun_ajaran = '2025/2026'
        WHERE k.id_program = ?
    ", [$kejuruanId]);

    $kuota = (int)$kuotaInfo['kuota'];

    // Get all verified pendaftar for this jurusan, sorted by ranking
    $pendaftar = db()->fetchAll("
        SELECT p.id_pendaftaran, p.nilai_akumulasi, p.umur_bulan, p.tanggal_daftar,
               s.nama_lengkap
        FROM tb_pendaftaran p
        JOIN tb_siswa s ON p.id_siswa = s.id_siswa
        WHERE p.id_smk_pilihan1 = ? AND p.id_kejuruan_pilihan1 = ? AND p.status = 'verified'
        ORDER BY p.nilai_akumulasi DESC, p.umur_bulan DESC, p.tanggal_daftar ASC
    ", [$smkId, $kejuruanId]);

    $accepted = 0;
    $rejected = 0;

    foreach ($pendaftar as $i => $p) {
        $ranking = $i + 1;
        $newStatus = $ranking <= $kuota ? 'accepted' : 'rejected';

        db()->update('tb_pendaftaran', [
            'status' => $newStatus,
            'ranking_sekolah' => $ranking,
            'tanggal_pengumuman' => date('Y-m-d H:i:s')
        ], 'id_pendaftaran = :id', ['id' => $p['id_pendaftaran']]);

        if ($newStatus === 'accepted') {
            $accepted++;
        } else {
            $rejected++;
        }
    }

    // Update kuota terisi
    db()->execute("
        UPDATE tb_kuota_jurusan SET terisi = ? 
        WHERE id_smk = ? AND id_kejuruan = ? AND tahun_ajaran = '2025/2026'
    ", [$accepted, $smkId, $kejuruanId]);

    Session::flash('success', "Proses kelulusan berhasil! Diterima: $accepted, Ditolak: $rejected");
    redirect('proses-kelulusan.php');
}

// Handle update kuota
if (isset($_POST['update_kuota'])) {
    $kejuruanId = (int)$_POST['id_kejuruan'];
    $kuotaBaru = (int)$_POST['kuota'];

    // Check if exists
    $existing = db()->fetch("SELECT * FROM tb_kuota_jurusan WHERE id_smk = ? AND id_kejuruan = ? AND tahun_ajaran = '2025/2026'", [$smkId, $kejuruanId]);

    if ($existing) {
        db()->update('tb_kuota_jurusan', ['kuota' => $kuotaBaru], 'id_kuota_jurusan = :id', ['id' => $existing['id_kuota_jurusan']]);
    } else {
        db()->insert('tb_kuota_jurusan', [
            'id_smk' => $smkId,
            'id_kejuruan' => $kejuruanId,
            'tahun_ajaran' => '2025/2026',
            'kuota' => $kuotaBaru
        ]);
    }

    Session::flash('success', 'Kuota berhasil diupdate!');
    redirect('proses-kelulusan.php');
}
?>

<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-trophy me-2"></i>Proses Kelulusan per Jurusan</h5>
                <span class="badge bg-primary"><?= count($jurusanList) ?> Jurusan</span>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Petunjuk:</strong> Proses kelulusan akan mengubah status pendaftar yang sudah diverifikasi menjadi <span class="badge bg-success">Diterima</span> atau <span class="badge bg-danger">Ditolak</span> berdasarkan ranking dan kuota.
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Jurusan</th>
                                <th class="text-center">Kuota</th>
                                <th class="text-center">Pending</th>
                                <th class="text-center">Diterima</th>
                                <th class="text-center">Ditolak</th>
                                <th class="text-center">Sisa</th>
                                <th class="text-center" width="200">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($jurusanList as $j):
                                $sisa = $j['kuota'] - $j['diterima'];
                            ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($j['nama_kejuruan']) ?></strong>
                                        <br><small class="text-muted"><?= $j['kode_kejuruan'] ?></small>
                                    </td>
                                    <td class="text-center">
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="id_kejuruan" value="<?= $j['id_program'] ?>">
                                            <input type="number" name="kuota" value="<?= $j['kuota'] ?>"
                                                class="form-control form-control-sm text-center" style="width: 70px; display: inline-block;"
                                                min="1" max="200">
                                            <button type="submit" name="update_kuota" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-check"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($j['pending'] > 0): ?>
                                            <span class="badge bg-warning text-dark"><?= $j['pending'] ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">0</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($j['diterima'] > 0): ?>
                                            <span class="badge bg-success"><?= $j['diterima'] ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">0</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($j['ditolak'] > 0): ?>
                                            <span class="badge bg-danger"><?= $j['ditolak'] ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">0</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($sisa > 0): ?>
                                            <span class="badge bg-info"><?= $sisa ?></span>
                                        <?php elseif ($sisa == 0): ?>
                                            <span class="badge bg-secondary">Penuh</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Over <?= abs($sisa) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($j['pending'] > 0): ?>
                                            <form method="POST" class="d-inline" onsubmit="return confirm('Proses kelulusan untuk <?= htmlspecialchars($j['nama_kejuruan']) ?>?\n\nPendaftar Pending: <?= $j['pending'] ?>\nKuota: <?= $j['kuota'] ?>\n\nDiterima: <?= min($j['pending'], $j['kuota'] - $j['diterima']) ?>\nDitolak: <?= max(0, $j['pending'] - ($j['kuota'] - $j['diterima'])) ?>');">
                                                <input type="hidden" name="id_kejuruan" value="<?= $j['id_program'] ?>">
                                                <button type="submit" name="proses_kelulusan" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-play-fill me-1"></i>Proses
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-muted small">Tidak ada pending</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                            <?php if (empty($jurusanList)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        Belum ada data jurusan untuk sekolah ini
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th>Total</th>
                                <th class="text-center"><?= array_sum(array_column($jurusanList, 'kuota')) ?></th>
                                <th class="text-center"><?= array_sum(array_column($jurusanList, 'pending')) ?></th>
                                <th class="text-center"><?= array_sum(array_column($jurusanList, 'diterima')) ?></th>
                                <th class="text-center"><?= array_sum(array_column($jurusanList, 'ditolak')) ?></th>
                                <th class="text-center"><?= array_sum(array_column($jurusanList, 'kuota')) - array_sum(array_column($jurusanList, 'diterima')) ?></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Panduan Proses Kelulusan</h6>
            </div>
            <div class="card-body">
                <ol class="mb-0">
                    <li class="mb-2">Pastikan semua pendaftar sudah <strong>diverifikasi</strong> dan memiliki <strong>nilai tes</strong></li>
                    <li class="mb-2">Atur <strong>kuota</strong> per jurusan sesuai daya tampung</li>
                    <li class="mb-2">Klik <strong>"Proses"</strong> untuk menentukan kelulusan</li>
                    <li class="mb-2">Sistem akan otomatis mengurutkan berdasarkan:
                        <ul>
                            <li>Nilai Akumulasi (tertinggi)</li>
                            <li>Umur (tertua)</li>
                            <li>Tanggal Daftar (terdahulu)</li>
                        </ul>
                    </li>
                    <li>Ranking 1 s/d Kuota = <span class="badge bg-success">Diterima</span></li>
                    <li>Ranking > Kuota = <span class="badge bg-danger">Ditolak</span></li>
                </ol>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-calculator me-2"></i>Formula Nilai Akhir</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-primary mb-3">
                    <code class="fs-6">Nilai Akhir = (30% × Bobot Rapor) + (70% × Nilai Tes)</code>
                </div>
                <p class="small text-muted mb-0">
                    Sesuai Juknis SPMB Provinsi Sumatera Barat Tahun 2025
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>