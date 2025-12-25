<?php
/**
 * Super Admin - Pengaturan Sistem
 */
$pageTitle = 'Pengaturan Sistem';
require_once 'includes/header.php';

// Handle save
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'setting_') === 0) {
            $settingKey = substr($key, 8);
            db()->query(
                "UPDATE tb_pengaturan SET value_pengaturan = ? WHERE key_pengaturan = ?",
                [sanitize($value), $settingKey]
            );
        }
    }
    Session::flash('success', 'Pengaturan berhasil disimpan.');
    redirect('pengaturan.php');
}

$settings = db()->fetchAll("SELECT * FROM tb_pengaturan ORDER BY id_pengaturan");
$settingsMap = [];
foreach ($settings as $s) $settingsMap[$s['key_pengaturan']] = $s['value_pengaturan'];
?>

<form method="POST">
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-calendar me-2"></i>Jadwal PPDB</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Tahun Ajaran</label>
                        <input type="text" name="setting_tahun_ajaran" class="form-control" value="<?= htmlspecialchars($settingsMap['tahun_ajaran'] ?? '') ?>">
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="setting_tanggal_mulai" class="form-control" value="<?= $settingsMap['tanggal_mulai'] ?? '' ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" name="setting_tanggal_akhir" class="form-control" value="<?= $settingsMap['tanggal_akhir'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Pengumuman</label>
                        <input type="date" name="setting_tanggal_pengumuman" class="form-control" value="<?= $settingsMap['tanggal_pengumuman'] ?? '' ?>">
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label">Daftar Ulang Mulai</label>
                            <input type="date" name="setting_tanggal_daftar_ulang_mulai" class="form-control" value="<?= $settingsMap['tanggal_daftar_ulang_mulai'] ?? '' ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Daftar Ulang Akhir</label>
                            <input type="date" name="setting_tanggal_daftar_ulang_akhir" class="form-control" value="<?= $settingsMap['tanggal_daftar_ulang_akhir'] ?? '' ?>">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-geo me-2"></i>Pengaturan Zonasi</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Radius Zonasi (meter)</label>
                        <input type="number" name="setting_radius_zonasi" class="form-control" value="<?= $settingsMap['radius_zonasi'] ?? 3000 ?>">
                        <small class="form-text">Radius dalam meter untuk jalur zonasi. Default: 3000m (3km)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Max Pilihan Sekolah</label>
                        <input type="number" name="setting_max_pilihan_sekolah" class="form-control" value="<?= $settingsMap['max_pilihan_sekolah'] ?? 2 ?>">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-toggle-on me-2"></i>Status Pendaftaran</h6>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="isOpen" name="setting_is_open" value="1" <?= ($settingsMap['is_open'] ?? 0) == 1 ? 'checked' : '' ?> style="width:3rem;height:1.5rem;">
                        <label class="form-check-label ms-2" for="isOpen">
                            <strong>Pendaftaran Dibuka</strong>
                            <div class="small text-muted">Jika dinonaktifkan, siswa tidak bisa mendaftar</div>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informasi Kontak</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Situs</label>
                        <input type="text" name="setting_site_name" class="form-control" value="<?= htmlspecialchars($settingsMap['site_name'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="setting_site_description" class="form-control" rows="2"><?= htmlspecialchars($settingsMap['site_description'] ?? '') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Kontak</label>
                        <input type="email" name="setting_contact_email" class="form-control" value="<?= htmlspecialchars($settingsMap['contact_email'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Telepon Kontak</label>
                        <input type="text" name="setting_contact_phone" class="form-control" value="<?= htmlspecialchars($settingsMap['contact_phone'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="setting_contact_address" class="form-control" rows="2"><?= htmlspecialchars($settingsMap['contact_address'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-lg w-100">
                <i class="bi bi-save me-2"></i>Simpan Pengaturan
            </button>
        </div>
    </div>
</form>

<?php require_once 'includes/footer.php'; ?>
