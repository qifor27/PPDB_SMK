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
        echo '<script>window.location.href = "pendaftaran.php";</script>';
        exit;
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
    echo '<script>window.location.href = "pilih-jalur.php";</script>';
    exit;
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
        ], 'id_pendaftaran = :id_pendaftaran', ['id_pendaftaran' => $pendaftaran['id_pendaftaran']]);

        // Update siswa data
        db()->update('tb_siswa', [
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
        ], 'id_siswa = :id_siswa', ['id_siswa' => $userId]);

        Session::flash('success', 'Data pendaftaran berhasil disimpan.');
        echo '<script>window.location.href = "pendaftaran.php";</script>';
        exit;
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

    <!-- === JALUR PRESTASI START === -->
    <?php if ($pendaftaran['kode_jalur'] === 'prestasi'): ?>
        <?php $prestasiList = getPrestasiByPendaftaran($pendaftaran['id_pendaftaran']); ?>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-trophy me-2"></i>Data Prestasi</h6>
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                    data-bs-target="#modalTambahPrestasi">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Prestasi
                </button>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-3">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Sistem Poin:</strong> Internasional (50-100), Nasional (30-80), Provinsi (20-60), Kota/Kab
                    (10-40)
                </div>

                <!-- Daftar Prestasi -->
                <div id="prestasiList">
                    <?php if (empty($prestasiList)): ?>
                        <div class="text-center text-muted py-4" id="emptyPrestasi">
                            <i class="bi bi-trophy fs-1 mb-2 d-block opacity-50"></i>
                            <p>Belum ada data prestasi. Klik tombol "Tambah Prestasi" untuk menambahkan.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0" id="tablePrestasi">
                                <thead>
                                    <tr>
                                        <th>Nama Prestasi</th>
                                        <th>Jenis</th>
                                        <th>Tingkat</th>
                                        <th>Peringkat</th>
                                        <th>Tahun</th>
                                        <th>Poin</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($prestasiList as $p): ?>
                                        <tr data-id="<?= $p['id_prestasi_siswa'] ?>">
                                            <td><?= htmlspecialchars($p['nama_prestasi']) ?></td>
                                            <td><span class="badge bg-secondary"><?= $p['jenis_prestasi'] ?></span></td>
                                            <td><?= $p['tingkat'] ?></td>
                                            <td><span class="badge bg-warning text-dark"><?= $p['peringkat'] ?></span></td>
                                            <td><?= $p['tahun'] ?></td>
                                            <td><strong class="text-success"><?= $p['poin'] ?></strong></td>
                                            <td><?= getStatusBadge($p['status_verifikasi']) ?></td>
                                            <td>
                                                <?php if (in_array($pendaftaran['status'], ['draft', 'submitted'])): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-danger btn-delete-prestasi"
                                                        data-id="<?= $p['id_prestasi_siswa'] ?>">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-primary">
                                        <td colspan="5" class="text-end"><strong>Total Poin:</strong></td>
                                        <td colspan="3">
                                            <strong class="fs-5 text-warning" id="totalPoin">
                                                <?= array_sum(array_column($prestasiList, 'poin')) ?>
                                            </strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <!-- === JALUR PRESTASI END === -->

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

<!-- === JALUR PRESTASI MODAL (Outside Form) START === -->
<?php if ($pendaftaran['kode_jalur'] === 'prestasi'): ?>
    <div class="modal fade" id="modalTambahPrestasi" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-trophy me-2"></i>Tambah Data Prestasi</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formTambahPrestasi">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Nama Prestasi/Lomba <span class="text-danger">*</span></label>
                                <input type="text" name="nama_prestasi" class="form-control" required
                                    placeholder="Contoh: Olimpiade Matematika Tingkat Nasional">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Jenis Prestasi <span class="text-danger">*</span></label>
                                <select name="jenis_prestasi" class="form-select" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="Akademik">Akademik</option>
                                    <option value="Non-Akademik">Non-Akademik</option>
                                    <option value="Olahraga">Olahraga</option>
                                    <option value="Seni">Seni</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tingkat <span class="text-danger">*</span></label>
                                <select name="tingkat" class="form-select" required id="selectTingkat">
                                    <option value="">-- Pilih Tingkat --</option>
                                    <option value="Kota/Kabupaten">Kota/Kabupaten</option>
                                    <option value="Provinsi">Provinsi</option>
                                    <option value="Nasional">Nasional</option>
                                    <option value="Internasional">Internasional</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Peringkat <span class="text-danger">*</span></label>
                                <select name="peringkat" class="form-select" required id="selectPeringkat">
                                    <option value="">-- Pilih Peringkat --</option>
                                    <option value="Juara 1">Juara 1</option>
                                    <option value="Juara 2">Juara 2</option>
                                    <option value="Juara 3">Juara 3</option>
                                    <option value="Peserta">Peserta/Finalis</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tahun <span class="text-danger">*</span></label>
                                <select name="tahun" class="form-select" required>
                                    <?php for ($y = date('Y'); $y >= date('Y') - 3; $y--): ?>
                                        <option value="<?= $y ?>"><?= $y ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Penyelenggara</label>
                                <input type="text" name="penyelenggara" class="form-control"
                                    placeholder="Contoh: Kementerian Pendidikan">
                            </div>
                            <div class="col-12">
                                <div class="alert alert-success mb-0">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="bi bi-star-fill fs-3 text-warning"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted">Estimasi Poin</small>
                                            <h4 class="mb-0" id="estimasiPoin">0</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btnSimpanPrestasi">
                        <i class="bi bi-save me-2"></i>Simpan Prestasi
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<!-- === JALUR PRESTASI MODAL END === -->

<?php
// === JALUR PRESTASI SCRIPTS START ===
$prestasiScripts = '';
if ($pendaftaran['kode_jalur'] === 'prestasi') {
    $prestasiScripts = <<<PRESTASI
// Sistem Poin Prestasi
const POIN_PRESTASI = {
    'Internasional': {'Juara 1': 100, 'Juara 2': 90, 'Juara 3': 80, 'Peserta': 50},
    'Nasional': {'Juara 1': 80, 'Juara 2': 70, 'Juara 3': 60, 'Peserta': 30},
    'Provinsi': {'Juara 1': 60, 'Juara 2': 50, 'Juara 3': 40, 'Peserta': 20},
    'Kota/Kabupaten': {'Juara 1': 40, 'Juara 2': 30, 'Juara 3': 20, 'Peserta': 10}
};

// Calculate and display estimated points
function hitungEstimasiPoin() {
    const tingkat = document.getElementById('selectTingkat').value;
    const peringkat = document.getElementById('selectPeringkat').value;
    let poin = 0;
    
    if (tingkat && peringkat && POIN_PRESTASI[tingkat] && POIN_PRESTASI[tingkat][peringkat]) {
        poin = POIN_PRESTASI[tingkat][peringkat];
    }
    
    document.getElementById('estimasiPoin').textContent = poin;
}

document.getElementById('selectTingkat').addEventListener('change', hitungEstimasiPoin);
document.getElementById('selectPeringkat').addEventListener('change', hitungEstimasiPoin);

// Save Prestasi
document.getElementById('btnSimpanPrestasi').addEventListener('click', async function() {
    const form = document.getElementById('formTambahPrestasi');
    const formData = new FormData(form);
    
    // Validate
    const required = ['nama_prestasi', 'jenis_prestasi', 'tingkat', 'peringkat', 'tahun'];
    for (let field of required) {
        if (!formData.get(field)) {
            alert('Mohon lengkapi semua field yang wajib diisi.');
            return;
        }
    }
    
    this.disabled = true;
    this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';
    
    try {
        const data = Object.fromEntries(formData);
        const response = await fetch('../api/prestasi.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Reload page to show updated list
            window.location.reload();
        } else {
            alert('Error: ' + result.error);
        }
    } catch (error) {
        alert('Terjadi kesalahan: ' + error.message);
    } finally {
        this.disabled = false;
        this.innerHTML = '<i class="bi bi-save me-2"></i>Simpan Prestasi';
    }
});

// Delete Prestasi
document.querySelectorAll('.btn-delete-prestasi').forEach(btn => {
    btn.addEventListener('click', async function() {
        if (!confirm('Yakin ingin menghapus prestasi ini?')) return;
        
        const id = this.dataset.id;
        this.disabled = true;
        
        try {
            const response = await fetch('../api/prestasi.php?id=' + id, {
                method: 'DELETE'
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Remove row from table
                this.closest('tr').remove();
                
                // Recalculate total
                let total = 0;
                document.querySelectorAll('#tablePrestasi tbody tr').forEach(row => {
                    const poinCell = row.querySelector('td:nth-child(6) strong');
                    if (poinCell) total += parseInt(poinCell.textContent) || 0;
                });
                
                const totalEl = document.getElementById('totalPoin');
                if (totalEl) totalEl.textContent = total;
                
                // If no more rows, show empty state
                if (document.querySelectorAll('#tablePrestasi tbody tr').length === 0) {
                    window.location.reload();
                }
            } else {
                alert('Error: ' + result.error);
            }
        } catch (error) {
            alert('Terjadi kesalahan: ' + error.message);
        } finally {
            this.disabled = false;
        }
    });
});

// Reset form when modal is closed
document.getElementById('modalTambahPrestasi').addEventListener('hidden.bs.modal', function() {
    document.getElementById('formTambahPrestasi').reset();
    document.getElementById('estimasiPoin').textContent = '0';
});
PRESTASI;
}
// === JALUR PRESTASI SCRIPTS END ===

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

{$prestasiScripts}
</script>
EOT;
require_once 'includes/footer.php';
?>