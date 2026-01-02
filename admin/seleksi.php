<?php

/**
 * Admin Sekolah - Input Nilai Tes Minat & Bakat
 */
$pageTitle = 'Input Nilai Tes';
require_once 'includes/header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idPendaftaran = (int) $_POST['id_pendaftaran'];
    $nilaiTes = (float) $_POST['nilai_tes'];
    $catatanTes = sanitize($_POST['catatan_tes'] ?? '');

    // Get pendaftaran data to find siswa
    $pendaftaran = db()->fetch(
        "SELECT p.*, s.tanggal_lahir FROM tb_pendaftaran p 
         JOIN tb_siswa s ON p.id_siswa = s.id_siswa
         WHERE p.id_pendaftaran = ?",
        [$idPendaftaran]
    );

    if ($pendaftaran) {
        // Calculate bobot_rapor and nilai_akumulasi
        require_once dirname(__DIR__) . '/config/scoring.php';
        $bobotRapor = getBobotRapor($pendaftaran['nilai_rata_rata'] ?? 0);
        $nilaiAkumulasi = hitungNilaiAkhirSMK($bobotRapor, $nilaiTes);

        // Calculate umur in months
        $umurBulan = 0;
        if ($pendaftaran['tanggal_lahir']) {
            $birthDate = new DateTime($pendaftaran['tanggal_lahir']);
            $now = new DateTime();
            $diff = $now->diff($birthDate);
            $umurBulan = ($diff->y * 12) + $diff->m;
        }

        // Update nilai tes for pilihan 1
        db()->update('tb_pendaftaran', [
            'nilai_tes' => $nilaiTes,
            'bobot_rapor' => $bobotRapor,
            'nilai_akumulasi' => $nilaiAkumulasi,
            'umur_bulan' => $umurBulan,
            'catatan_admin' => $catatanTes,
            'status' => 'verified'
        ], 'id_pendaftaran = :id AND id_smk_pilihan1 = :smk', [
            'id' => $idPendaftaran,
            'smk' => $smkId
        ]);

        // Link nilai_tes to pilihan 2 (if exists)
        // Siswa tes di pilihan 1, nilai dipakai untuk ranking di pilihan 2
        $pilihan2 = db()->fetch(
            "SELECT id_pendaftaran, nilai_rata_rata FROM tb_pendaftaran 
             WHERE id_siswa = ? AND id_smk_pilihan2 IS NOT NULL AND id_pendaftaran != ?",
            [$pendaftaran['id_siswa'], $idPendaftaran]
        );

        if ($pilihan2) {
            $bobotRapor2 = getBobotRapor($pilihan2['nilai_rata_rata'] ?? 0);
            $nilaiAkumulasi2 = hitungNilaiAkhirSMK($bobotRapor2, $nilaiTes);

            db()->update('tb_pendaftaran', [
                'nilai_tes' => $nilaiTes,
                'bobot_rapor' => $bobotRapor2,
                'nilai_akumulasi' => $nilaiAkumulasi2,
                'umur_bulan' => $umurBulan
            ], 'id_pendaftaran = :id', ['id' => $pilihan2['id_pendaftaran']]);
        }
    }

    Session::flash('success', 'Nilai tes berhasil disimpan dan terhubung ke pilihan 2.');
    redirect('seleksi.php');
}

// Get pendaftar yang sudah submit
$pendaftarList = db()->fetchAll(
    "SELECT p.*, s.nama_lengkap, s.nisn, k.nama_kejuruan
     FROM tb_pendaftaran p
     JOIN tb_siswa s ON p.id_siswa = s.id_siswa
     LEFT JOIN tb_kejuruan k ON p.id_kejuruan_pilihan1 = k.id_program
     WHERE p.id_smk_pilihan1 = ? AND p.status IN ('submitted', 'verified')
     ORDER BY p.tanggal_daftar ASC",
    [$smkId]
);
?>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Daftar Peserta Tes</h5>
                <span class="badge bg-primary"><?= count($pendaftarList) ?> peserta</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>No. Pendaftaran</th>
                                <th>Nama Siswa</th>
                                <th>Jurusan</th>
                                <th>Nilai Tes</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendaftarList as $i => $p): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><code><?= $p['nomor_pendaftaran'] ?></code></td>
                                    <td><?= htmlspecialchars($p['nama_lengkap']) ?></td>
                                    <td><small><?= htmlspecialchars($p['nama_kejuruan'] ?? '-') ?></small></td>
                                    <td>
                                        <?php if ($p['nilai_tes']): ?>
                                            <span class="badge bg-success"><?= number_format($p['nilai_tes'], 1) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= getStatusBadge($p['status']) ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary"
                                            data-bs-toggle="modal" data-bs-target="#modalNilai"
                                            onclick="setNilai(<?= $p['id_pendaftaran'] ?>, '<?= htmlspecialchars($p['nama_lengkap']) ?>', <?= $p['nilai_tes'] ?? 0 ?>, '<?= htmlspecialchars($p['catatan_admin'] ?? '') ?>')">
                                            <i class="bi bi-pencil"></i> Input
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($pendaftarList)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Belum ada peserta tes</td>
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
                <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Panduan Penilaian</h6>
            </div>
            <div class="card-body">
                <p class="small">Masukkan nilai tes minat dan bakat siswa dengan skala:</p>
                <ul class="small mb-0">
                    <li><strong>90-100:</strong> Sangat Baik</li>
                    <li><strong>80-89:</strong> Baik</li>
                    <li><strong>70-79:</strong> Cukup</li>
                    <li><strong>60-69:</strong> Kurang</li>
                    <li><strong>&lt;60:</strong> Tidak Lulus</li>
                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Statistik Nilai</h6>
            </div>
            <div class="card-body">
                <?php
                $nilaiStats = db()->fetch(
                    "SELECT 
                        AVG(nilai_tes) as avg_nilai,
                        MAX(nilai_tes) as max_nilai,
                        MIN(nilai_tes) as min_nilai,
                        COUNT(CASE WHEN nilai_tes >= 70 THEN 1 END) as lulus
                     FROM tb_pendaftaran 
                     WHERE id_smk_pilihan1 = ? AND nilai_tes IS NOT NULL",
                    [$smkId]
                );
                ?>
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="fs-4 fw-bold text-primary"><?= number_format($nilaiStats['avg_nilai'] ?? 0, 1) ?></div>
                        <small class="text-muted">Rata-rata</small>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="fs-4 fw-bold text-success"><?= $nilaiStats['lulus'] ?? 0 ?></div>
                        <small class="text-muted">Lulus (≥70)</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Input Nilai -->
<div class="modal fade" id="modalNilai" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Input Nilai Tes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_pendaftaran" id="input_id">
                    <div class="mb-3">
                        <label class="form-label">Nama Siswa</label>
                        <input type="text" class="form-control" id="input_nama" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nilai Tes (0-100)</label>
                        <input type="number" name="nilai_tes" id="input_nilai" class="form-control"
                            min="0" max="100" step="0.1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan (opsional)</label>
                        <textarea name="catatan_tes" id="input_catatan" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Nilai</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function setNilai(id, nama, nilai, catatan) {
        document.getElementById('input_id').value = id;
        document.getElementById('input_nama').value = nama;
        document.getElementById('input_nilai').value = nilai || '';
        document.getElementById('input_catatan').value = catatan || '';
    }
</script>

<?php require_once 'includes/footer.php'; ?>