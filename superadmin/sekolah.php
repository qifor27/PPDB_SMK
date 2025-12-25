<?php
/**
 * Super Admin - Kelola SMK
 */
$pageTitle = 'Data SMK';
require_once 'includes/header.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        db()->delete('tb_smk', 'id_smk = ?', [$id]);
        Session::flash('success', 'SMK berhasil dihapus.');
    } catch (Exception $e) {
        Session::flash('error', 'Gagal menghapus: SMK masih memiliki data terkait.');
    }
    redirect('sekolah.php');
}

// Handle add/edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'npsn' => sanitize($_POST['npsn']),
        'nama_sekolah' => sanitize($_POST['nama_sekolah']),
        'alamat' => sanitize($_POST['alamat']),
        'kelurahan' => sanitize($_POST['kelurahan']),
        'kecamatan' => sanitize($_POST['kecamatan']),
        'kode_pos' => sanitize($_POST['kode_pos']),
        'latitude' => (float)$_POST['latitude'],
        'longitude' => (float)$_POST['longitude'],
        'telepon' => sanitize($_POST['telepon']),
        'email' => sanitize($_POST['email']),
        'website' => sanitize($_POST['website']),
        'jumlah_siswa' => (int)$_POST['jumlah_siswa'],
        'jumlah_guru' => (int)$_POST['jumlah_guru'],
        'nama_kepsek' => sanitize($_POST['nama_kepsek']),
        'deskripsi' => sanitize($_POST['deskripsi'])
    ];
    
    if (isset($_POST['id_smk']) && $_POST['id_smk']) {
        db()->update('tb_smk', $data, 'id_smk = ?', ['id_smk' => (int)$_POST['id_smk']]);
        Session::flash('success', 'SMK berhasil diupdate.');
    } else {
        db()->insert('tb_smk', $data);
        Session::flash('success', 'SMK berhasil ditambahkan.');
    }
    redirect('sekolah.php');
}

$smkList = getAllSMK();
$editData = null;
if (isset($_GET['edit'])) {
    $editData = getSMKById((int)$_GET['edit']);
}
?>

<div class="row g-4">
    <!-- Form -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-<?= $editData ? 'pencil' : 'plus' ?> me-2"></i><?= $editData ? 'Edit' : 'Tambah' ?> SMK</h6>
            </div>
            <div class="card-body">
                <form method="POST">
                    <?php if ($editData): ?>
                    <input type="hidden" name="id_smk" value="<?= $editData['id_smk'] ?>">
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label class="form-label">NPSN</label>
                        <input type="text" name="npsn" class="form-control" value="<?= htmlspecialchars($editData['npsn'] ?? '') ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Sekolah *</label>
                        <input type="text" name="nama_sekolah" class="form-control" required value="<?= htmlspecialchars($editData['nama_sekolah'] ?? '') ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2"><?= htmlspecialchars($editData['alamat'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label">Kelurahan</label>
                            <input type="text" name="kelurahan" class="form-control" value="<?= htmlspecialchars($editData['kelurahan'] ?? '') ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" name="kecamatan" class="form-control" value="<?= htmlspecialchars($editData['kecamatan'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label">Latitude *</label>
                            <input type="text" name="latitude" class="form-control" required value="<?= $editData['latitude'] ?? '' ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Longitude *</label>
                            <input type="text" name="longitude" class="form-control" required value="<?= $editData['longitude'] ?? '' ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label">Telepon</label>
                            <input type="text" name="telepon" class="form-control" value="<?= htmlspecialchars($editData['telepon'] ?? '') ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Kode Pos</label>
                            <input type="text" name="kode_pos" class="form-control" value="<?= htmlspecialchars($editData['kode_pos'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($editData['email'] ?? '') ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Website</label>
                        <input type="url" name="website" class="form-control" value="<?= htmlspecialchars($editData['website'] ?? '') ?>">
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label">Jml Siswa</label>
                            <input type="number" name="jumlah_siswa" class="form-control" value="<?= $editData['jumlah_siswa'] ?? 0 ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Jml Guru</label>
                            <input type="number" name="jumlah_guru" class="form-control" value="<?= $editData['jumlah_guru'] ?? 0 ?>">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Kepala Sekolah</label>
                        <input type="text" name="nama_kepsek" class="form-control" value="<?= htmlspecialchars($editData['nama_kepsek'] ?? '') ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($editData['deskripsi'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i><?= $editData ? 'Update' : 'Simpan' ?>
                        </button>
                        <?php if ($editData): ?>
                        <a href="sekolah.php" class="btn btn-dark">Batal</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Table -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-building me-2"></i>Daftar SMK</h6>
                <span class="badge bg-primary"><?= count($smkList) ?> SMK</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Sekolah</th>
                                <th>NPSN</th>
                                <th>Kecamatan</th>
                                <th>Siswa</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($smkList as $i => $smk): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($smk['nama_sekolah']) ?></td>
                                <td><?= $smk['npsn'] ?: '-' ?></td>
                                <td><?= htmlspecialchars($smk['kecamatan'] ?? '-') ?></td>
                                <td><?= $smk['jumlah_siswa'] ?></td>
                                <td>
                                    <a href="?edit=<?= $smk['id_smk'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <a href="?delete=<?= $smk['id_smk'] ?>" class="btn btn-sm btn-outline-danger" 
                                       onclick="return confirm('Hapus SMK ini?')"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
