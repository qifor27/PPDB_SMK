<?php
/**
 * User - Profil Saya
 */
$pageTitle = 'Profil Saya';
require_once 'includes/header.php';

$error = '';

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Session::verifyCsrf($_POST['csrf_token'] ?? '')) {
        $error = 'Token tidak valid.';
    } else {
        $data = [
            'email' => sanitize($_POST['email']),
            'no_hp' => sanitize($_POST['no_hp']),
            'alamat' => sanitize($_POST['alamat']),
            'kelurahan' => sanitize($_POST['kelurahan']),
            'kecamatan' => sanitize($_POST['kecamatan']),
            'agama' => sanitize($_POST['agama'])
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
            db()->update('tb_siswa', $data, 'id_siswa = :where_id_siswa', ['where_id_siswa' => $userId]);
            Session::flash('success', 'Profil berhasil diperbarui.');
            redirect('profil.php');
        }
    }
}

// Refresh data
$siswa = db()->fetch("SELECT * FROM tb_siswa WHERE id_siswa = ?", [$userId]);
?>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card text-center">
            <div class="card-body">
                <div class="mb-3">
                    <div class="stat-icon primary mx-auto" style="width:100px;height:100px;font-size:2.5rem;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                </div>
                <h5><?= htmlspecialchars($siswa['nama_lengkap']) ?></h5>
                <p class="text-muted">NISN: <?= $siswa['nisn'] ?></p>

                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge bg-<?= $siswa['jenis_kelamin'] === 'L' ? 'info' : 'pink' ?>-soft">
                        <?= $siswa['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?>
                    </span>
                    <?php if ($siswa['is_verified']): ?>
                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Verified</span>
                    <?php endif; ?>
                </div>

                <hr>

                <div class="text-start">
                    <p class="small mb-2"><i
                            class="bi bi-envelope me-2 text-primary"></i><?= htmlspecialchars($siswa['email']) ?></p>
                    <p class="small mb-2"><i
                            class="bi bi-phone me-2 text-primary"></i><?= htmlspecialchars($siswa['no_hp'] ?? '-') ?>
                    </p>
                    <p class="small mb-0"><i class="bi bi-calendar me-2 text-primary"></i>Bergabung:
                        <?= formatDate($siswa['created_at'], 'd M Y') ?></p>
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
                    <div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i><?= $error ?></div>
                <?php endif; ?>

                <form method="POST">
                    <?= Session::csrfField() ?>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control"
                                value="<?= htmlspecialchars($siswa['nama_lengkap']) ?>" disabled>
                            <small class="form-text">Nama tidak dapat diubah</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NISN</label>
                            <input type="text" class="form-control" value="<?= $siswa['nisn'] ?>" disabled>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                value="<?= htmlspecialchars($siswa['email']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. HP / WhatsApp</label>
                            <input type="text" name="no_hp" class="form-control"
                                value="<?= htmlspecialchars($siswa['no_hp'] ?? '') ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Agama</label>
                            <select name="agama" class="form-select">
                                <?php foreach (['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $agama): ?>
                                    <option value="<?= $agama ?>" <?= ($siswa['agama'] ?? '') === $agama ? 'selected' : '' ?>>
                                        <?= $agama ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tempat, Tanggal Lahir</label>
                            <input type="text" class="form-control"
                                value="<?= htmlspecialchars($siswa['tempat_lahir']) ?>, <?= formatDate($siswa['tanggal_lahir']) ?>"
                                disabled>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control"
                                rows="2"><?= htmlspecialchars($siswa['alamat'] ?? '') ?></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kelurahan</label>
                            <input type="text" name="kelurahan" class="form-control"
                                value="<?= htmlspecialchars($siswa['kelurahan'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" name="kecamatan" class="form-control"
                                value="<?= htmlspecialchars($siswa['kecamatan'] ?? '') ?>">
                        </div>

                        <div class="col-12">
                            <hr>
                            <h6>Ubah Password</h6>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password_baru" class="form-control"
                                placeholder="Kosongkan jika tidak diubah">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="konfirm_password" class="form-control">
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>