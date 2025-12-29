<?php
/**
 * User - Form Pendaftaran
 */
$pageTitle = 'Form Pendaftaran';
require_once 'includes/header.php';

$smkList = getAllSMK();
$error = '';

// Create new pendaftaran if jalur is selected
if (isset($_GET['jalur']) && !$pendaftaran) {
    $jalurId = (int) $_GET['jalur'];
    $jalur = db()->fetch("SELECT * FROM tb_jalur WHERE id_jalur = ? AND is_active = 1", [$jalurId]);

    if ($jalur) {
        $nomorPendaftaran = generateNomorPendaftaran($jalur['kode_jalur']);
        db()->insert('tb_pendaftaran', [
            'nomor_pendaftaran' => $nomorPendaftaran,
            'id_siswa' => $userId,
            'id_smk_pilihan1' => $smkList[0]['id_smk'],
            'id_jalur' => $jalurId,
            'tahun_ajaran' => getTahunAjaran(),
            'status' => 'draft'
        ]);
        redirect('pendaftaran.php');
    }
}

// Get fresh pendaftaran data
$pendaftaran = db()->fetch(
    "SELECT p.*, j.nama_jalur, j.kode_jalur FROM tb_pendaftaran p
     LEFT JOIN tb_jalur j ON p.id_jalur = j.id_jalur
     WHERE p.id_siswa = ? ORDER BY p.id_pendaftaran DESC LIMIT 1",
    [$userId]
);

if (!$pendaftaran) {
    redirect('pilih-jalur.php');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Session::verifyCsrf($_POST['csrf_token'] ?? '')) {
        $error = 'Token tidak valid.';
    } else {
        $smkPilihan1 = (int) $_POST['smk_pilihan1'];
        $smkPilihan2 = !empty($_POST['smk_pilihan2']) ? (int) $_POST['smk_pilihan2'] : null;
        $nilaiRataRata = !empty($_POST['nilai_rata_rata']) ? (float) $_POST['nilai_rata_rata'] : null;

        // Update pendaftaran
        db()->update('tb_pendaftaran', [
            'id_smk_pilihan1' => $smkPilihan1,
            'id_smk_pilihan2' => $smkPilihan2,
            'nilai_rata_rata' => $nilaiRataRata
        ], 'id_pendaftaran = :where_id', ['where_id' => $pendaftaran['id_pendaftaran']]);

        // Update siswa data
        $siswaData = [
            'alamat' => sanitize($_POST['alamat'] ?? ''),
            'kelurahan' => sanitize($_POST['kelurahan'] ?? ''),
            'kecamatan' => sanitize($_POST['kecamatan'] ?? ''),
            'kode_pos' => sanitize($_POST['kode_pos'] ?? ''),
            'agama' => sanitize($_POST['agama'] ?? ''),
            'asal_sekolah' => sanitize($_POST['asal_sekolah'] ?? ''),
            'nama_ayah' => sanitize($_POST['nama_ayah'] ?? ''),
            'pekerjaan_ayah' => sanitize($_POST['pekerjaan_ayah'] ?? ''),
            'nama_ibu' => sanitize($_POST['nama_ibu'] ?? ''),
            'pekerjaan_ibu' => sanitize($_POST['pekerjaan_ibu'] ?? ''),
            'no_hp_ortu' => sanitize($_POST['no_hp_ortu'] ?? ''),
            'latitude' => !empty($_POST['latitude']) ? (float) $_POST['latitude'] : null,
            'longitude' => !empty($_POST['longitude']) ? (float) $_POST['longitude'] : null
        ];

        // Tambahan data kepindahan orang tua - VELI
        if ($pendaftaran['kode_jalur'] === 'kepindahan') {
            $siswaData['jenis_instansi_ortu'] = sanitize($_POST['jenis_instansi_ortu'] ?? '');
            $siswaData['nama_instansi_asal'] = sanitize($_POST['nama_instansi_asal'] ?? '');
            $siswaData['nama_instansi_tujuan'] = sanitize($_POST['nama_instansi_tujuan'] ?? '');
            $siswaData['nomor_sk_pindah'] = sanitize($_POST['nomor_sk_pindah'] ?? '');
            $siswaData['tanggal_sk_pindah'] = !empty($_POST['tanggal_sk_pindah']) ? $_POST['tanggal_sk_pindah'] : null;
            $siswaData['kota_asal'] = sanitize($_POST['kota_asal'] ?? '');
            $siswaData['alasan_kepindahan'] = sanitize($_POST['alasan_kepindahan'] ?? '');
        }

        db()->update('tb_siswa', $siswaData, 'id_siswa = :where_id', ['where_id' => $userId]);

        Session::flash('success', 'Data pendaftaran berhasil disimpan.');
        redirect('pendaftaran.php');
    }
}

// Refresh siswa data
$siswa = db()->fetch("SELECT * FROM tb_siswa WHERE id_siswa = ?", [$userId]);
$kejuruanList = getKejuruanBySMK($pendaftaran['id_smk_pilihan1']);
?>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">Data Pendaftaran</h5>
            <small class="text-muted">No: <?= $pendaftaran['nomor_pendaftaran'] ?></small>
        </div>
        <?= getJalurBadge($pendaftaran['kode_jalur']) ?>
    </div>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i><?= $error ?></div>
<?php endif; ?>

<form method="POST" class="needs-validation" novalidate>
    <?= Session::csrfField() ?>

    <!-- Data Pribadi -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0"><i class="bi bi-person me-2"></i>Data Pribadi</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($siswa['nama_lengkap']) ?>"
                        disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label">NISN</label>
                    <input type="text" class="form-control" value="<?= $siswa['nisn'] ?>" disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <input type="text" class="form-control"
                        value="<?= $siswa['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?>" disabled>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($siswa['tempat_lahir']) ?>"
                        disabled>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="text" class="form-control" value="<?= formatDate($siswa['tanggal_lahir']) ?>" disabled>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Agama</label>
                    <select name="agama" class="form-select" required>
                        <option value="">Pilih Agama</option>
                        <?php foreach (['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $agama): ?>
                            <option value="<?= $agama ?>" <?= ($siswa['agama'] ?? '') === $agama ? 'selected' : '' ?>>
                                <?= $agama ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Alamat -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Alamat Domisili</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control" rows="2"
                        required><?= htmlspecialchars($siswa['alamat'] ?? '') ?></textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kelurahan</label>
                    <input type="text" name="kelurahan" class="form-control"
                        value="<?= htmlspecialchars($siswa['kelurahan'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kecamatan</label>
                    <input type="text" name="kecamatan" class="form-control"
                        value="<?= htmlspecialchars($siswa['kecamatan'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kode Pos</label>
                    <input type="text" name="kode_pos" class="form-control"
                        value="<?= htmlspecialchars($siswa['kode_pos'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Koordinat (untuk zonasi)</label>
                    <div class="input-group">
                        <input type="text" name="latitude" class="form-control" placeholder="Latitude"
                            value="<?= $siswa['latitude'] ?? '' ?>" id="inputLat">
                        <input type="text" name="longitude" class="form-control" placeholder="Longitude"
                            value="<?= $siswa['longitude'] ?? '' ?>" id="inputLng">
                        <button type="button" class="btn btn-primary" id="btnGetLocation">
                            <i class="bi bi-crosshair"></i>
                        </button>
                    </div>
                    <small class="form-text">Klik tombol untuk mendeteksi lokasi otomatis</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Asal Sekolah -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0"><i class="bi bi-building me-2"></i>Asal Sekolah</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Nama Sekolah Asal (SMP/MTs)</label>
                    <input type="text" name="asal_sekolah" class="form-control" required
                        value="<?= htmlspecialchars($siswa['asal_sekolah'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nilai Rata-rata Raport</label>
                    <input type="number" name="nilai_rata_rata" class="form-control" step="0.01" min="0" max="100"
                        value="<?= $pendaftaran['nilai_rata_rata'] ?? '' ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Data Orang Tua -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0"><i class="bi bi-people me-2"></i>Data Orang Tua</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Ayah</label>
                    <input type="text" name="nama_ayah" class="form-control"
                        value="<?= htmlspecialchars($siswa['nama_ayah'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Pekerjaan Ayah</label>
                    <input type="text" name="pekerjaan_ayah" class="form-control"
                        value="<?= htmlspecialchars($siswa['pekerjaan_ayah'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Ibu</label>
                    <input type="text" name="nama_ibu" class="form-control"
                        value="<?= htmlspecialchars($siswa['nama_ibu'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Pekerjaan Ibu</label>
                    <input type="text" name="pekerjaan_ibu" class="form-control"
                        value="<?= htmlspecialchars($siswa['pekerjaan_ibu'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">No. HP Orang Tua</label>
                    <input type="text" name="no_hp_ortu" class="form-control"
                        value="<?= htmlspecialchars($siswa['no_hp_ortu'] ?? '') ?>">
                </div>
            </div>
        </div>
    </div>

    <?php if ($pendaftaran['kode_jalur'] === 'kepindahan'): ?>
        <!-- Data Kepindahan Orang Tua - VELI -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="bi bi-arrow-left-right me-2"></i>Data Kepindahan Orang Tua</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-3">
                    <i class="bi bi-info-circle me-2"></i>
                    Lengkapi data kepindahan orang tua untuk verifikasi jalur kepindahan.
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Jenis Instansi Orang Tua <span class="text-danger">*</span></label>
                        <select name="jenis_instansi_ortu" class="form-select" required>
                            <option value="">-- Pilih Jenis Instansi --</option>
                            <?php foreach (['ASN', 'TNI', 'POLRI', 'BUMN', 'Swasta'] as $jenis): ?>
                                <option value="<?= $jenis ?>" <?= ($siswa['jenis_instansi_ortu'] ?? '') === $jenis ? 'selected' : '' ?>><?= $jenis ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nama Instansi Asal <span class="text-danger">*</span></label>
                        <input type="text" name="nama_instansi_asal" class="form-control" required
                            placeholder="Contoh: Dinas Pendidikan Kota Jakarta"
                            value="<?= htmlspecialchars($siswa['nama_instansi_asal'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nama Instansi Tujuan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_instansi_tujuan" class="form-control" required
                            placeholder="Contoh: Dinas Pendidikan Kota Padang"
                            value="<?= htmlspecialchars($siswa['nama_instansi_tujuan'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nomor SK Pindah Tugas <span class="text-danger">*</span></label>
                        <input type="text" name="nomor_sk_pindah" class="form-control" required
                            placeholder="Contoh: SK/123/IV/2025"
                            value="<?= htmlspecialchars($siswa['nomor_sk_pindah'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal SK Pindah <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_sk_pindah" class="form-control" required
                            value="<?= $siswa['tanggal_sk_pindah'] ?? '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kota/Kabupaten Asal <span class="text-danger">*</span></label>
                        <input type="text" name="kota_asal" class="form-control" required
                            placeholder="Contoh: Jakarta Selatan"
                            value="<?= htmlspecialchars($siswa['kota_asal'] ?? '') ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Alasan/Keterangan Kepindahan</label>
                        <textarea name="alasan_kepindahan" class="form-control" rows="2"
                            placeholder="Jelaskan alasan kepindahan orang tua (opsional)"><?= htmlspecialchars($siswa['alasan_kepindahan'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Pilihan Sekolah -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0"><i class="bi bi-building me-2"></i>Pilihan Sekolah Tujuan</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">SMK Pilihan 1 <span class="text-danger">*</span></label>
                    <select name="smk_pilihan1" class="form-select" required>
                        <?php foreach ($smkList as $smk): ?>
                            <option value="<?= $smk['id_smk'] ?>" <?= $pendaftaran['id_smk_pilihan1'] == $smk['id_smk'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($smk['nama_sekolah']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">SMK Pilihan 2 (Opsional)</label>
                    <select name="smk_pilihan2" class="form-select">
                        <option value="">-- Tidak Ada --</option>
                        <?php foreach ($smkList as $smk): ?>
                            <option value="<?= $smk['id_smk'] ?>" <?= ($pendaftaran['id_smk_pilihan2'] ?? '') == $smk['id_smk'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($smk['nama_sekolah']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <a href="index.php" class="btn btn-dark">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
        <div>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-2"></i>Simpan Data
            </button>
            <a href="dokumen.php" class="btn btn-outline-primary">
                Lanjut Upload Dokumen <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</form>

<?php
$extraScripts = <<<EOT
<script>
document.getElementById('btnGetLocation').addEventListener('click', function() {
    if (navigator.geolocation) {
        this.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        navigator.geolocation.getCurrentPosition(
            pos => {
                document.getElementById('inputLat').value = pos.coords.latitude.toFixed(8);
                document.getElementById('inputLng').value = pos.coords.longitude.toFixed(8);
                this.innerHTML = '<i class="bi bi-check-lg"></i>';
            },
            err => {
                alert('Gagal mendapatkan lokasi: ' + err.message);
                this.innerHTML = '<i class="bi bi-crosshair"></i>';
            }
        );
    }
});
</script>
EOT;
require_once 'includes/footer.php';
?>