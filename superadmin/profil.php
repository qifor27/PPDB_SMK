<?php

/**
 * Super Admin - Profil
 */
$pageTitle = 'Profil Saya';
require_once 'includes/header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Session::verifyCsrf($_POST['csrf_token'] ?? '')) {
        Session::flash('error', 'Token keamanan tidak valid.');
        redirect('profil.php');
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'update_profile') {
        $data = [
            'nama_lengkap' => sanitize($_POST['nama_lengkap']),
            'email' => sanitize($_POST['email']),
        ];

        db()->update('tb_superadmin', $data, 'id_superadmin = :id', ['id' => $superadminId]);
        Session::flash('success', 'Profil berhasil diperbarui');
        redirect('profil.php');
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
</div>

<?php require_once 'includes/footer.php'; ?>