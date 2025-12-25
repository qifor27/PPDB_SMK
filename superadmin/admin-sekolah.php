<?php
/**
 * Super Admin - Kelola Admin Sekolah
 */
$pageTitle = 'Admin Sekolah';
require_once 'includes/header.php';

// Handle delete
if (isset($_GET['delete'])) {
    db()->delete('tb_admin_sekolah', 'id_admin_sekolah = ?', [(int)$_GET['delete']]);
    Session::flash('success', 'Admin berhasil dihapus.');
    redirect('admin-sekolah.php');
}

// Handle toggle status
if (isset($_GET['toggle'])) {
    $admin = db()->fetch("SELECT is_active FROM tb_admin_sekolah WHERE id_admin_sekolah = ?", [(int)$_GET['toggle']]);
    db()->update('tb_admin_sekolah', ['is_active' => !$admin['is_active']], 'id_admin_sekolah = ?', ['id_admin_sekolah' => (int)$_GET['toggle']]);
    Session::flash('success', 'Status admin berhasil diubah.');
    redirect('admin-sekolah.php');
}

// Handle add/edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'id_smk' => (int)$_POST['id_smk'],
        'username' => sanitize($_POST['username']),
        'nama_lengkap' => sanitize($_POST['nama_lengkap']),
        'email' => sanitize($_POST['email']),
        'no_telepon' => sanitize($_POST['no_telepon']),
        'jabatan' => sanitize($_POST['jabatan'])
    ];
    
    if (!empty($_POST['password'])) {
        $data['password'] = hashPassword($_POST['password']);
    }
    
    if (isset($_POST['id_admin']) && $_POST['id_admin']) {
        if (empty($_POST['password'])) unset($data['password']);
        db()->update('tb_admin_sekolah', $data, 'id_admin_sekolah = ?', ['id_admin_sekolah' => (int)$_POST['id_admin']]);
        Session::flash('success', 'Admin berhasil diupdate.');
    } else {
        if (empty($_POST['password'])) {
            Session::flash('error', 'Password wajib diisi.');
            redirect('admin-sekolah.php');
        }
        db()->insert('tb_admin_sekolah', $data);
        Session::flash('success', 'Admin berhasil ditambahkan.');
    }
    redirect('admin-sekolah.php');
}

$adminList = db()->fetchAll(
    "SELECT a.*, s.nama_sekolah FROM tb_admin_sekolah a 
     LEFT JOIN tb_smk s ON a.id_smk = s.id_smk 
     ORDER BY a.nama_lengkap"
);
$smkList = getAllSMK();

$editData = null;
if (isset($_GET['edit'])) {
    $editData = db()->fetch("SELECT * FROM tb_admin_sekolah WHERE id_admin_sekolah = ?", [(int)$_GET['edit']]);
}
?>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-<?= $editData ? 'pencil' : 'plus' ?> me-2"></i><?= $editData ? 'Edit' : 'Tambah' ?> Admin</h6>
            </div>
            <div class="card-body">
                <form method="POST">
                    <?php if ($editData): ?>
                    <input type="hidden" name="id_admin" value="<?= $editData['id_admin_sekolah'] ?>">
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label class="form-label">SMK *</label>
                        <select name="id_smk" class="form-select" required>
                            <option value="">-- Pilih SMK --</option>
                            <?php foreach ($smkList as $smk): ?>
                            <option value="<?= $smk['id_smk'] ?>" <?= ($editData['id_smk'] ?? '') == $smk['id_smk'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($smk['nama_sekolah']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" class="form-control" required value="<?= htmlspecialchars($editData['nama_lengkap'] ?? '') ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Username *</label>
                        <input type="text" name="username" class="form-control" required value="<?= htmlspecialchars($editData['username'] ?? '') ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Password <?= $editData ? '(kosongkan jika tidak diubah)' : '*' ?></label>
                        <input type="password" name="password" class="form-control" <?= $editData ? '' : 'required' ?>>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($editData['email'] ?? '') ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">No Telepon</label>
                        <input type="text" name="no_telepon" class="form-control" value="<?= htmlspecialchars($editData['no_telepon'] ?? '') ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Jabatan</label>
                        <input type="text" name="jabatan" class="form-control" placeholder="cth: Operator PPDB" value="<?= htmlspecialchars($editData['jabatan'] ?? '') ?>">
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i><?= $editData ? 'Update' : 'Simpan' ?></button>
                        <?php if ($editData): ?>
                        <a href="admin-sekolah.php" class="btn btn-dark">Batal</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-person-badge me-2"></i>Daftar Admin</h6>
                <span class="badge bg-primary"><?= count($adminList) ?> admin</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark mb-0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>SMK</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($adminList as $admin): ?>
                            <tr>
                                <td><?= htmlspecialchars($admin['nama_lengkap']) ?></td>
                                <td><code><?= $admin['username'] ?></code></td>
                                <td class="small"><?= htmlspecialchars(truncate($admin['nama_sekolah'] ?? '-', 25)) ?></td>
                                <td>
                                    <?php if ($admin['is_active']): ?>
                                    <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                    <span class="badge bg-secondary">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="?edit=<?= $admin['id_admin_sekolah'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <a href="?toggle=<?= $admin['id_admin_sekolah'] ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-toggle-<?= $admin['is_active'] ? 'on' : 'off' ?>"></i></a>
                                    <a href="?delete=<?= $admin['id_admin_sekolah'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus admin ini?')"><i class="bi bi-trash"></i></a>
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
