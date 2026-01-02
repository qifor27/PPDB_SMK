<?php

/**
 * Admin Sekolah - Profil Admin
 */
$pageTitle = 'Profil Saya';
require_once 'includes/header.php';

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Session::verifyCsrf($_POST['csrf_token'] ?? '')) {
        $error = 'Token tidak valid.';
    } else {
        $data = [
            'nama_lengkap' => sanitize($_POST['nama_lengkap']),
            'email' => sanitize($_POST['email']),
        ];

        // Handle password change
        if (!empty($_POST['password_baru'])) {
            if ($_POST['password_baru'] !== $_POST['konfirm_password']) {
                $error = 'Konfirmasi password tidak cocok.';
            } elseif (strlen($_POST['password_baru']) < 6) {
                $error = 'Password minimal 6 karakter.';
            } else {
                $data['password'] = hashPassword($_POST['password_baru']);
            }
        }

        if (!$error) {
            db()->update('tb_admin_sekolah', $data, 'id_admin_sekolah = :id', ['id' => $adminId]);
            Session::flash('success', 'Profil berhasil diperbarui.');
            redirect('profil.php');
        }
    }
}

// Refresh admin data
$admin = db()->fetch(
    "SELECT a.*, s.nama_sekolah FROM tb_admin_sekolah a 
     LEFT JOIN tb_smk s ON a.id_smk = s.id_smk 
     WHERE a.id_admin_sekolah = ?",
    [$adminId]
);
?>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card text-center">
            <div class="card-body">
                <div class="mb-3">
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto"
                        style="width:120px;height:120px;font-size:3rem;color:white;">
                        <?= strtoupper(substr($admin['nama_lengkap'], 0, 1)) ?>
                    </div>
                </div>
                <h5><?= htmlspecialchars($admin['nama_lengkap']) ?></h5>
                <p class="text-muted mb-2">Admin Sekolah</p>
                <span class="badge bg-primary"><?= htmlspecialchars($admin['nama_sekolah']) ?></span>

                <hr>

                <div class="text-start">
                    <p class="small mb-2">
                        <i class="bi bi-person me-2 text-primary"></i>
                        <?= htmlspecialchars($admin['username']) ?>
                    </p>
                    <p class="small mb-2">
                        <i class="bi bi-envelope me-2 text-primary"></i>
                        <?= htmlspecialchars($admin['email'] ?? '-') ?>
                    </p>
                    <p class="small mb-0">
                        <i class="bi bi-calendar me-2 text-primary"></i>
                        Bergabung: <?= formatDate($admin['created_at'] ?? date('Y-m-d'), 'd M Y') ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-pencil me-2"></i>Edit Profil</h5>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST">
                    <?= Session::csrfField() ?>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($admin['username']) ?>" disabled>
                            <small class="text-muted">Username tidak dapat diubah</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sekolah</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($admin['nama_sekolah']) ?>" disabled>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control"
                                value="<?= htmlspecialchars($admin['nama_lengkap']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                value="<?= htmlspecialchars($admin['email'] ?? '') ?>">
                        </div>
                    </div>

                    <hr>
                    <h6 class="mb-3">Ubah Password</h6>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password_baru" class="form-control"
                                placeholder="Kosongkan jika tidak ingin mengubah">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="konfirm_password" class="form-control">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>