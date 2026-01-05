<?php

/**
 * Superadmin - Kelola Beranda
 * Mengelola konten yang tampil di halaman utama (index.php)
 */

$pageTitle = 'Kelola Beranda';
require_once 'includes/header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Session::verifyCsrf($_POST['csrf_token'] ?? '')) {
        Session::flash('error', 'Token tidak valid.');
    } else {
        $action = $_POST['action'] ?? '';

        // Update Pengaturan Umum
        if ($action === 'update_general') {
            $settings = [
                'hero_title' => sanitize($_POST['hero_title'] ?? ''),
                'hero_subtitle' => sanitize($_POST['hero_subtitle'] ?? ''),
                'hero_image' => sanitize($_POST['hero_image'] ?? ''),
                'contact_phone' => sanitize($_POST['contact_phone'] ?? ''),
                'contact_email' => sanitize($_POST['contact_email'] ?? ''),
                'contact_address' => sanitize($_POST['contact_address'] ?? ''),
                'social_facebook' => sanitize($_POST['social_facebook'] ?? ''),
                'social_instagram' => sanitize($_POST['social_instagram'] ?? ''),
                'social_youtube' => sanitize($_POST['social_youtube'] ?? ''),
            ];

            foreach ($settings as $key => $value) {
                $exists = db()->fetch("SELECT * FROM tb_pengaturan WHERE key_pengaturan = ?", [$key]);
                if ($exists) {
                    db()->update('tb_pengaturan', ['value_pengaturan' => $value], 'key_pengaturan = :k', ['k' => $key]);
                } else {
                    db()->insert('tb_pengaturan', ['key_pengaturan' => $key, 'value_pengaturan' => $value, 'kategori' => 'beranda']);
                }
            }
            Session::flash('success', 'Pengaturan beranda berhasil disimpan.');
        }

        // Upload Dokumen
        if ($action === 'upload_doc') {
            $docType = sanitize($_POST['doc_type']);
            $docTitle = sanitize($_POST['doc_title']);

            if (isset($_FILES['doc_file']) && $_FILES['doc_file']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = dirname(__DIR__) . '/uploads/docs/';
                if (!is_dir($uploadDir))
                    mkdir($uploadDir, 0755, true);

                $ext = strtolower(pathinfo($_FILES['doc_file']['name'], PATHINFO_EXTENSION));
                $filename = $docType . '-' . time() . '.' . $ext;

                if (move_uploaded_file($_FILES['doc_file']['tmp_name'], $uploadDir . $filename)) {
                    // Save to database
                    $exists = db()->fetch("SELECT * FROM tb_dokumen_beranda WHERE tipe = ?", [$docType]);
                    if ($exists) {
                        // Delete old file
                        @unlink($uploadDir . $exists['filename']);
                        db()->update('tb_dokumen_beranda', [
                            'judul' => $docTitle,
                            'filename' => $filename,
                            'updated_at' => date('Y-m-d H:i:s')
                        ], 'tipe = :t', ['t' => $docType]);
                    } else {
                        db()->insert('tb_dokumen_beranda', [
                            'tipe' => $docType,
                            'judul' => $docTitle,
                            'filename' => $filename
                        ]);
                    }
                    Session::flash('success', 'Dokumen berhasil diupload.');
                } else {
                    Session::flash('error', 'Gagal mengupload dokumen.');
                }
            }
        }

        // Update Jadwal
        if ($action === 'update_jadwal') {
            $jadwalData = $_POST['jadwal'] ?? [];

            // Clear existing
            db()->query("DELETE FROM tb_jadwal_spmb");

            foreach ($jadwalData as $j) {
                if (!empty($j['nama']) && !empty($j['tanggal_mulai'])) {
                    db()->insert('tb_jadwal_spmb', [
                        'nama_kegiatan' => sanitize($j['nama']),
                        'tanggal_mulai' => $j['tanggal_mulai'],
                        'tanggal_selesai' => $j['tanggal_selesai'] ?: null,
                        'keterangan' => sanitize($j['keterangan'] ?? ''),
                        'urutan' => (int) ($j['urutan'] ?? 0)
                    ]);
                }
            }
            Session::flash('success', 'Jadwal SPMB berhasil diperbarui.');
        }

        redirect('beranda.php');
    }
}

// Get current settings
$settings = [];
$settingsDb = db()->fetchAll("SELECT key_pengaturan, value_pengaturan FROM tb_pengaturan WHERE kategori = 'beranda'");
foreach ($settingsDb as $s) {
    $settings[$s['key_pengaturan']] = $s['value_pengaturan'];
}

// Get documents
$documents = db()->fetchAll("SELECT * FROM tb_dokumen_beranda ORDER BY tipe");
$docsMap = [];
foreach ($documents as $d) {
    $docsMap[$d['tipe']] = $d;
}

// Get jadwal
$jadwalList = db()->fetchAll("SELECT * FROM tb_jadwal_spmb ORDER BY urutan, tanggal_mulai");
?>

<div class="row g-4">
    <!-- Sidebar Navigation -->
    <div class="col-md-3">
        <div class="card">
            <div class="card-body p-0">
                <nav class="nav flex-column nav-pills">
                    <a class="nav-link active" data-bs-toggle="pill" href="#tab-hero">
                        <i class="bi bi-house-door me-2"></i>Hero Section
                    </a>
                    <a class="nav-link" data-bs-toggle="pill" href="#tab-dokumen">
                        <i class="bi bi-file-earmark-pdf me-2"></i>Dokumen
                    </a>
                    <a class="nav-link" data-bs-toggle="pill" href="#tab-jadwal">
                        <i class="bi bi-calendar-event me-2"></i>Jadwal SPMB
                    </a>
                    <a class="nav-link" data-bs-toggle="pill" href="#tab-kontak">
                        <i class="bi bi-telephone me-2"></i>Kontak & Sosmed
                    </a>
                </nav>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body text-center">
                <a href="<?= SITE_URL ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-eye me-1"></i>Lihat Beranda
                </a>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="col-md-9">
        <div class="tab-content">
            <!-- Hero Section -->
            <div class="tab-pane fade show active" id="tab-hero">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-house-door me-2"></i>Hero Section</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <?= Session::csrfField() ?>
                            <input type="hidden" name="action" value="update_general">

                            <div class="mb-3">
                                <label class="form-label">Judul Hero</label>
                                <input type="text" name="hero_title" class="form-control"
                                    value="<?= htmlspecialchars($settings['hero_title'] ?? 'Sistem Penerimaan Murid Baru SMK Kota Padang') ?>">
                                <small class="text-muted">Judul utama yang tampil di hero section</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Subtitle Hero</label>
                                <textarea name="hero_subtitle" class="form-control"
                                    rows="3"><?= htmlspecialchars($settings['hero_subtitle'] ?? 'Selamat datang di SPMB SMK Kota Padang. Daftarkan diri Anda sekarang dan raih masa depan cerah bersama SMK terbaik di Kota Padang.') ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">URL Gambar Hero</label>
                                <input type="text" name="hero_image" class="form-control"
                                    value="<?= htmlspecialchars($settings['hero_image'] ?? 'assets/img/hero-students.png') ?>">
                                <small class="text-muted">Path relatif dari root, contoh: assets/img/hero.png</small>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Dokumen -->
            <div class="tab-pane fade" id="tab-dokumen">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-file-earmark-pdf me-2"></i>Kelola Dokumen</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <?php
                            $docTypes = [
                                'juknis' => ['icon' => 'bi-file-earmark-text', 'color' => 'primary', 'title' => 'Juknis SPMB'],
                                'manual' => ['icon' => 'bi-book', 'color' => 'success', 'title' => 'Manual Book'],
                                'formulir' => ['icon' => 'bi-file-earmark-richtext', 'color' => 'warning', 'title' => 'Formulir'],
                                'persyaratan' => ['icon' => 'bi-list-check', 'color' => 'info', 'title' => 'Persyaratan']
                            ];
                            foreach ($docTypes as $type => $info):
                                $doc = $docsMap[$type] ?? null;
                            ?>
                                <div class="col-md-6">
                                    <div class="card h-100 border">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <div
                                                    class="bg-<?= $info['color'] ?>-soft text-<?= $info['color'] ?> rounded-circle p-3 me-3">
                                                    <i class="bi <?= $info['icon'] ?> fs-4"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0"><?= $info['title'] ?></h6>
                                                    <?php if ($doc): ?>
                                                        <small class="text-success"><i
                                                                class="bi bi-check-circle me-1"></i>Tersedia</small>
                                                    <?php else: ?>
                                                        <small class="text-muted">Belum diupload</small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <form method="POST" enctype="multipart/form-data">
                                                <?= Session::csrfField() ?>
                                                <input type="hidden" name="action" value="upload_doc">
                                                <input type="hidden" name="doc_type" value="<?= $type ?>">

                                                <div class="mb-2">
                                                    <input type="text" name="doc_title" class="form-control form-control-sm"
                                                        placeholder="Judul dokumen"
                                                        value="<?= htmlspecialchars($doc['judul'] ?? $info['title']) ?>">
                                                </div>
                                                <div class="mb-2">
                                                    <input type="file" name="doc_file" class="form-control form-control-sm"
                                                        accept=".pdf,.doc,.docx">
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <button type="submit" class="btn btn-<?= $info['color'] ?> btn-sm">
                                                        <i class="bi bi-upload"></i> Upload
                                                    </button>
                                                    <?php if ($doc): ?>
                                                        <a href="<?= SITE_URL ?>/uploads/docs/<?= $doc['filename'] ?>"
                                                            class="btn btn-outline-secondary btn-sm" target="_blank">
                                                            <i class="bi bi-eye"></i> Lihat
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jadwal -->
            <div class="tab-pane fade" id="tab-jadwal">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-calendar-event me-2"></i>Jadwal SPMB</h5>
                        <button type="button" class="btn btn-primary btn-sm" id="btnAddJadwal">
                            <i class="bi bi-plus-lg"></i> Tambah
                        </button>
                    </div>
                    <div class="card-body">
                        <form method="POST" id="formJadwal">
                            <?= Session::csrfField() ?>
                            <input type="hidden" name="action" value="update_jadwal">

                            <div class="table-responsive">
                                <table class="table table-bordered" id="tableJadwal">
                                    <thead>
                                        <tr>
                                            <th width="30">No</th>
                                            <th>Nama Kegiatan</th>
                                            <th width="130">Tanggal Mulai</th>
                                            <th width="130">Tanggal Selesai</th>
                                            <th>Keterangan</th>
                                            <th width="50">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1;
                                        foreach ($jadwalList as $j): ?>
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="jadwal[<?= $no ?>][urutan]"
                                                        value="<?= $no ?>">
                                                    <?= $no ?>
                                                </td>
                                                <td>
                                                    <input type="text" name="jadwal[<?= $no ?>][nama]"
                                                        class="form-control form-control-sm"
                                                        value="<?= htmlspecialchars($j['nama_kegiatan']) ?>" required>
                                                </td>
                                                <td>
                                                    <input type="date" name="jadwal[<?= $no ?>][tanggal_mulai]"
                                                        class="form-control form-control-sm"
                                                        value="<?= $j['tanggal_mulai'] ?>" required>
                                                </td>
                                                <td>
                                                    <input type="date" name="jadwal[<?= $no ?>][tanggal_selesai]"
                                                        class="form-control form-control-sm"
                                                        value="<?= $j['tanggal_selesai'] ?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="jadwal[<?= $no ?>][keterangan]"
                                                        class="form-control form-control-sm"
                                                        value="<?= htmlspecialchars($j['keterangan'] ?? '') ?>">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm btn-remove-row">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php $no++;
                                        endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Simpan Jadwal
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Kontak -->
            <div class="tab-pane fade" id="tab-kontak">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-telephone me-2"></i>Kontak & Media Sosial</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <?= Session::csrfField() ?>
                            <input type="hidden" name="action" value="update_general">
                            <!-- Hidden field for other settings -->
                            <input type="hidden" name="hero_title"
                                value="<?= htmlspecialchars($settings['hero_title'] ?? '') ?>">
                            <input type="hidden" name="hero_subtitle"
                                value="<?= htmlspecialchars($settings['hero_subtitle'] ?? '') ?>">
                            <input type="hidden" name="hero_image"
                                value="<?= htmlspecialchars($settings['hero_image'] ?? '') ?>">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-telephone me-1"></i>Telepon</label>
                                    <input type="text" name="contact_phone" class="form-control"
                                        value="<?= htmlspecialchars($settings['contact_phone'] ?? '') ?>"
                                        placeholder="(0751) xxxxxx">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-envelope me-1"></i>Email</label>
                                    <input type="email" name="contact_email" class="form-control"
                                        value="<?= htmlspecialchars($settings['contact_email'] ?? '') ?>"
                                        placeholder="info@smk.sch.id">
                                </div>
                                <div class="col-12">
                                    <label class="form-label"><i class="bi bi-geo-alt me-1"></i>Alamat</label>
                                    <textarea name="contact_address" class="form-control"
                                        rows="2"><?= htmlspecialchars($settings['contact_address'] ?? '') ?></textarea>
                                </div>

                                <div class="col-12">
                                    <hr>
                                    <h6>Media Sosial</h6>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label"><i
                                            class="bi bi-facebook me-1 text-primary"></i>Facebook</label>
                                    <input type="url" name="social_facebook" class="form-control"
                                        value="<?= htmlspecialchars($settings['social_facebook'] ?? '') ?>"
                                        placeholder="https://facebook.com/...">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label"><i
                                            class="bi bi-instagram me-1 text-danger"></i>Instagram</label>
                                    <input type="url" name="social_instagram" class="form-control"
                                        value="<?= htmlspecialchars($settings['social_instagram'] ?? '') ?>"
                                        placeholder="https://instagram.com/...">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label"><i
                                            class="bi bi-youtube me-1 text-danger"></i>YouTube</label>
                                    <input type="url" name="social_youtube" class="form-control"
                                        value="<?= htmlspecialchars($settings['social_youtube'] ?? '') ?>"
                                        placeholder="https://youtube.com/...">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-4">
                                <i class="bi bi-save me-1"></i>Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let rowCount = <?= count($jadwalList) ?>;

        // Add new jadwal row
        document.getElementById('btnAddJadwal').addEventListener('click', function() {
            rowCount++;
            const tbody = document.querySelector('#tableJadwal tbody');
            const newRow = `
            <tr>
                <td>
                    <input type="hidden" name="jadwal[${rowCount}][urutan]" value="${rowCount}">
                    ${rowCount}
                </td>
                <td><input type="text" name="jadwal[${rowCount}][nama]" class="form-control form-control-sm" required></td>
                <td><input type="date" name="jadwal[${rowCount}][tanggal_mulai]" class="form-control form-control-sm" required></td>
                <td><input type="date" name="jadwal[${rowCount}][tanggal_selesai]" class="form-control form-control-sm"></td>
                <td><input type="text" name="jadwal[${rowCount}][keterangan]" class="form-control form-control-sm"></td>
                <td><button type="button" class="btn btn-danger btn-sm btn-remove-row"><i class="bi bi-trash"></i></button></td>
            </tr>
        `;
            tbody.insertAdjacentHTML('beforeend', newRow);
        });

        // Remove row
        document.getElementById('tableJadwal').addEventListener('click', function(e) {
            if (e.target.closest('.btn-remove-row')) {
                e.target.closest('tr').remove();
            }
        });
    });
</script>

<?php require_once 'includes/footer.php'; ?>