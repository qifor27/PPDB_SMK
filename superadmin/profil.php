<?php

/**
 * Super Admin - Profil
 */
$pageTitle = 'Profil Saya';
require_once 'includes/header.php';

$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Session::verifyCsrf();

    $action = $_POST['action'] ?? '';

    if ($action === 'update_profile') {
        $data = [
            'nama_lengkap' => sanitize($_POST['nama_lengkap']),
            'email' => sanitize($_POST['email']),
        ];

        db()->update('tb_superadmin', $data, 'id_superadmin = ?', [$superadminId]);
        Session::flash('success', 'Profil berhasil diperbarui');
        redirect('profil.php');
    } elseif ($action === 'change_password') {
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if (!password_verify($currentPassword, $superadmin['password'])) {
            $error = 'Password saat ini salah';
        } elseif (strlen($newPassword) < 6) {
            $error = 'Password baru minimal 6 karakter';
        } elseif ($newPassword !== $confirmPassword) {
            $error = 'Konfirmasi password tidak sesuai';
        } else {
            db()->update('tb_superadmin', [
                'password' => password_hash($newPassword, PASSWORD_DEFAULT)
            ], 'id_superadmin = ?', [$superadminId]);
            Session::flash('success', 'Password berhasil diubah');
            redirect('profil.php');
        }
    }
}

// Refresh data
$superadmin = db()->fetch("SELECT * FROM tb_superadmin WHERE id_superadmin = ?", [$superadminId]);
?>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person me-2"></i>Data Profil</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <?= Session::csrfField() ?>
                    <input type="hidden" name="action" value="update_profile">

                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($superadmin['username']) ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($superadmin['nama_lengkap']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($superadmin['email'] ?? '') ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-key me-2"></i>Ubah Password</h5>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST">
                    <?= Session::csrfField() ?>
                    <input type="hidden" name="action" value="change_password">

                    <div class="mb-3">
                        <label class="form-label">Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="new_password" class="form-control" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-key me-2"></i>Ubah Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>