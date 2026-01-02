<?php

/**
 * User - Tambah Prestasi
 */
$pageTitle = 'Tambah Prestasi';
require_once 'includes/header.php';

// Cek pendaftaran
if (!$pendaftaran) {
    Session::flash('error', 'Silakan lengkapi pendaftaran terlebih dahulu.');
    redirect(SITE_URL . '/user/pilih-sekolah-smk.php');
}

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Session::verifyCsrf($_POST['csrf_token'] ?? '')) {
        $error = 'Token tidak valid.';
    } else {
        $nama = sanitize($_POST['nama_prestasi'] ?? '');
        $jenis = sanitize($_POST['jenis_prestasi'] ?? '');
        $tingkat = sanitize($_POST['tingkat'] ?? '');
        $peringkat = sanitize($_POST['peringkat'] ?? '');
        $tahun = (int) ($_POST['tahun'] ?? date('Y'));

        // Calculate poin based on tingkat and peringkat
        $poinMatrix = [
            'internasional' => ['juara_1' => 100, 'juara_2' => 90, 'juara_3' => 80, 'harapan' => 70],
            'nasional' => ['juara_1' => 80, 'juara_2' => 70, 'juara_3' => 60, 'harapan' => 50],
            'provinsi' => ['juara_1' => 60, 'juara_2' => 50, 'juara_3' => 40, 'harapan' => 30],
            'kota' => ['juara_1' => 40, 'juara_2' => 30, 'juara_3' => 20, 'harapan' => 10],
            'kecamatan' => ['juara_1' => 20, 'juara_2' => 15, 'juara_3' => 10, 'harapan' => 5],
        ];
        $poin = $poinMatrix[$tingkat][$peringkat] ?? 0;

        // Handle file upload
        $fileSertifikat = null;
        if (isset($_FILES['file_sertifikat']) && $_FILES['file_sertifikat']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = UPLOADS_PATH . 'prestasi/' . $pendaftaran['id_pendaftaran'] . '/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $ext = pathinfo($_FILES['file_sertifikat']['name'], PATHINFO_EXTENSION);
            $fileSertifikat = uniqid('sertifikat_') . '.' . $ext;
            move_uploaded_file($_FILES['file_sertifikat']['tmp_name'], $uploadDir . $fileSertifikat);
        }

        // Insert to database
        db()->insert('tb_prestasi_siswa', [
            'id_pendaftaran' => $pendaftaran['id_pendaftaran'],
            'nama_prestasi' => $nama,
            'jenis_prestasi' => $jenis,
            'tingkat' => $tingkat,
            'peringkat' => $peringkat,
            'tahun' => $tahun,
            'poin' => $poin,
            'file_sertifikat' => $fileSertifikat,
            'status_verifikasi' => 'pending'
        ]);

        Session::flash('success', 'Prestasi berhasil ditambahkan!');
        redirect(SITE_URL . '/user/prestasi.php');
    }
}
?>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-trophy me-2"></i>Tambah Data Prestasi</h5>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <?= Session::csrfField() ?>

                    <div class="mb-3">
                        <label class="form-label">Nama Prestasi <span class="text-danger">*</span></label>
                        <input type="text" name="nama_prestasi" class="form-control"
                            placeholder="Contoh: Juara 1 Lomba Robotik" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Jenis Prestasi <span class="text-danger">*</span></label>
                            <select name="jenis_prestasi" class="form-select" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="Akademik">Akademik</option>
                                <option value="Non-Akademik">Non-Akademik</option>
                                <option value="Olahraga">Olahraga</option>
                                <option value="Seni">Seni</option>
                                <option value="Keagamaan">Keagamaan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tahun <span class="text-danger">*</span></label>
                            <select name="tahun" class="form-select" required>
                                <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                                    <option value="<?= $y ?>"><?= $y ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tingkat <span class="text-danger">*</span></label>
                            <select name="tingkat" class="form-select" required>
                                <option value="">-- Pilih Tingkat --</option>
                                <option value="internasional">Internasional</option>
                                <option value="nasional">Nasional</option>
                                <option value="provinsi">Provinsi</option>
                                <option value="kota">Kota/Kabupaten</option>
                                <option value="kecamatan">Kecamatan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Peringkat <span class="text-danger">*</span></label>
                            <select name="peringkat" class="form-select" required>
                                <option value="">-- Pilih Peringkat --</option>
                                <option value="juara_1">Juara 1</option>
                                <option value="juara_2">Juara 2</option>
                                <option value="juara_3">Juara 3</option>
                                <option value="harapan">Harapan / Finalis</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">File Sertifikat</label>
                        <input type="file" name="file_sertifikat" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        <small class="form-text text-muted">Format: PDF, JPG, PNG. Max 2MB</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Simpan Prestasi
                        </button>
                        <a href="prestasi.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Panduan Poin</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-bordered small">
                    <thead class="table-light">
                        <tr>
                            <th>Tingkat</th>
                            <th>Juara 1</th>
                            <th>Juara 2</th>
                            <th>Juara 3</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Internasional</td>
                            <td>100</td>
                            <td>90</td>
                            <td>80</td>
                        </tr>
                        <tr>
                            <td>Nasional</td>
                            <td>80</td>
                            <td>70</td>
                            <td>60</td>
                        </tr>
                        <tr>
                            <td>Provinsi</td>
                            <td>60</td>
                            <td>50</td>
                            <td>40</td>
                        </tr>
                        <tr>
                            <td>Kota/Kab</td>
                            <td>40</td>
                            <td>30</td>
                            <td>20</td>
                        </tr>
                        <tr>
                            <td>Kecamatan</td>
                            <td>20</td>
                            <td>15</td>
                            <td>10</td>
                        </tr>
                    </tbody>
                </table>
                <small class="text-muted">Poin akan dihitung otomatis berdasarkan tingkat dan peringkat.</small>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>