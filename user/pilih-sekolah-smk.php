<?php

/**
 * PPDB SMK - Pilih Sekolah SMK
 * Halaman pemilihan sekolah dan jurusan untuk pendaftaran SMK
 * Sesuai Juknis SPMB 2025/2026
 */

$pageTitle = 'Pilih Sekolah SMK';
require_once 'includes/header.php';
require_once dirname(__DIR__) . '/config/scoring.php';

// Cek apakah sudah ada pendaftaran
if ($pendaftaran && $pendaftaran['status'] !== 'draft') {
    Session::flash('error', 'Anda sudah memiliki pendaftaran yang aktif.');
    redirect(SITE_URL . '/user/status.php');
}

// Get semua SMK
$sekolahList = db()->fetchAll("
    SELECT s.*, 
           COUNT(DISTINCT k.id_program) as jumlah_jurusan
    FROM tb_smk s
    LEFT JOIN tb_kejuruan k ON s.id_smk = k.id_smk
    GROUP BY s.id_smk
    ORDER BY s.nama_sekolah
");

// Get filter kota
$kotaList = db()->fetchAll("SELECT DISTINCT kecamatan FROM tb_smk WHERE kecamatan IS NOT NULL ORDER BY kecamatan");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Session::verifyCsrf($_POST['csrf_token'] ?? '')) {
        Session::flash('error', 'Token keamanan tidak valid.');
    } else {
        $pilihanMode = sanitize($_POST['pilihan_mode'] ?? '');
        $sekolah1 = (int)($_POST['sekolah_pilihan1'] ?? 0);
        $kejuruan1 = (int)($_POST['kejuruan_pilihan1'] ?? 0);
        $sekolah2 = (int)($_POST['sekolah_pilihan2'] ?? 0);
        $kejuruan2 = (int)($_POST['kejuruan_pilihan2'] ?? 0);

        // Validasi
        $errors = [];
        if (!$sekolah1) $errors[] = 'Pilih sekolah pilihan 1';
        if (!$kejuruan1) $errors[] = 'Pilih jurusan pilihan 1';

        // Variabel untuk jenis pilihan dan lokasi tes
        $jenisPilihan = 'single';
        $lokasiTesSMK = $sekolah1;

        // Validasi berdasarkan Juknis SPMB Sumbar 2025
        if ($sekolah2 && $kejuruan2) {
            if ($sekolah1 === $sekolah2) {
                // Satu sekolah, dua jurusan - pastikan jurusan berbeda
                if ($kejuruan1 === $kejuruan2) {
                    $errors[] = 'Jurusan pilihan 1 dan 2 tidak boleh sama jika memilih sekolah yang sama';
                }
                $pilihanMode = 'satu_sekolah_dua_jurusan';
                $jenisPilihan = 'same_school';
                $lokasiTesSMK = $sekolah1; // Tes di sekolah tersebut (2x tes berbeda)
            } else {
                // Dua sekolah berbeda - cek apakah jurusan sama (by nama)
                $jurusan1Data = db()->fetch("SELECT nama_kejuruan FROM tb_kejuruan WHERE id_program = ?", [$kejuruan1]);
                $jurusan2Data = db()->fetch("SELECT nama_kejuruan FROM tb_kejuruan WHERE id_program = ?", [$kejuruan2]);

                if ($jurusan1Data && $jurusan2Data && $jurusan1Data['nama_kejuruan'] !== $jurusan2Data['nama_kejuruan']) {
                    $errors[] = 'Sesuai Juknis SPMB 2025: Jika memilih 2 sekolah berbeda, jurusan harus SAMA';
                }

                $pilihanMode = 'dua_sekolah_satu_jurusan';
                $jenisPilihan = 'diff_school';
                $lokasiTesSMK = $sekolah1; // Tes di pilihan 1 saja
            }
        }

        // Cek syarat buta warna
        $kejuruanData1 = db()->fetch("SELECT * FROM tb_kejuruan WHERE id_program = ?", [$kejuruan1]);
        if ($kejuruanData1 && $kejuruanData1['syarat_tidak_buta_warna'] && $siswa['status_buta_warna'] !== 'tidak') {
            $errors[] = 'Jurusan ' . $kejuruanData1['nama_kejuruan'] . ' memerlukan syarat tidak buta warna';
        }

        if (empty($errors)) {
            if ($pendaftaran) {
                // Update existing - jangan ubah nomor pendaftaran
                $data = [
                    'id_smk_pilihan1' => $sekolah1,
                    'id_smk_pilihan2' => $sekolah2 ?: null,
                    'id_kejuruan_pilihan1' => $kejuruan1,
                    'id_kejuruan_pilihan2' => $kejuruan2 ?: null,
                    'pilihan_mode' => $pilihanMode,
                    'jenis_pilihan' => $jenisPilihan,
                    'lokasi_tes_smk' => $lokasiTesSMK
                ];
                db()->update('tb_pendaftaran', $data, 'id_pendaftaran = :id', ['id' => $pendaftaran['id_pendaftaran']]);
            } else {
                // Create new
                $tahap = Session::get('tahap_pendaftaran') ?? 1;
                $nomorPendaftaran = generateNomorPendaftaranSMK($userId, $tahap);
                $data = [
                    'nomor_pendaftaran' => $nomorPendaftaran,
                    'id_siswa' => $userId,
                    'id_smk_pilihan1' => $sekolah1,
                    'id_smk_pilihan2' => $sekolah2 ?: null,
                    'id_kejuruan_pilihan1' => $kejuruan1,
                    'id_kejuruan_pilihan2' => $kejuruan2 ?: null,
                    'id_jalur' => 1, // Default jalur
                    'pilihan_mode' => $pilihanMode,
                    'jenis_pilihan' => $jenisPilihan,
                    'lokasi_tes_smk' => $lokasiTesSMK,
                    'tahun_ajaran' => getPengaturan('tahun_ajaran'),
                    'tahap_pendaftaran' => $tahap,
                    'status' => 'draft'
                ];
                db()->insert('tb_pendaftaran', $data);
            }

            Session::flash('success', 'Pilihan sekolah berhasil disimpan. Silakan lengkapi data rapor.');
            redirect(SITE_URL . '/user/input-rapor.php');
        } else {
            Session::flash('error', implode('<br>', $errors));
        }
    }
}
?>

<div class="row g-4">
    <!-- Filter Section -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center g-3">
                    <div class="col-md-4">
                        <h5 class="mb-0"><i class="bi bi-building me-2 text-primary"></i>Pilih Sekolah SMK</h5>
                    </div>
                    <div class="col-md-4">
                        <select id="filterKota" class="form-select">
                            <option value="">Semua Kecamatan</option>
                            <?php foreach ($kotaList as $kota): ?>
                                <option value="<?= htmlspecialchars($kota['kecamatan']) ?>">
                                    <?= htmlspecialchars($kota['kecamatan']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" id="searchSekolah" class="form-control" placeholder="Cari nama sekolah...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Pilihan -->
    <div class="col-12">
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Petunjuk:</strong> Pilih <strong>Sekolah dan Jurusan</strong> untuk Pilihan 1 (wajib) dan Pilihan 2 (opsional).
        </div>
    </div>

    <!-- Pilihan 1 -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="bi bi-1-circle me-2"></i>Pilihan 1</h6>
            </div>
            <div class="card-body">
                <div id="pilihan1Container">
                    <p class="text-muted text-center py-4">
                        <i class="bi bi-hand-index-thumb fs-1 d-block mb-2"></i>
                        Klik sekolah di bawah untuk memilih
                    </p>
                </div>
                <input type="hidden" id="sekolah_pilihan1" name="sekolah_pilihan1" value="">
                <input type="hidden" id="kejuruan_pilihan1" name="kejuruan_pilihan1" value="">
            </div>
        </div>
    </div>

    <!-- Pilihan 2 -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-secondary text-white">
                <h6 class="mb-0"><i class="bi bi-2-circle me-2"></i>Pilihan 2</h6>
            </div>
            <div class="card-body">
                <div id="pilihan2Container">
                    <p class="text-muted text-center py-4">
                        <i class="bi bi-hand-index-thumb fs-1 d-block mb-2"></i>
                        Pilih sekolah/jurusan kedua setelah pilihan 1
                    </p>
                </div>
                <input type="hidden" id="sekolah_pilihan2" name="sekolah_pilihan2" value="">
                <input type="hidden" id="kejuruan_pilihan2" name="kejuruan_pilihan2" value="">
            </div>
        </div>
    </div>

    <!-- Daftar Sekolah -->
    <div class="col-12">
        <h5 class="mb-3"><i class="bi bi-list-ul me-2"></i>Daftar SMK Negeri</h5>
        <div class="row g-3" id="sekolahGrid">
            <?php foreach ($sekolahList as $sekolah): ?>
                <div class="col-md-6 col-lg-4 sekolah-item"
                    data-kecamatan="<?= htmlspecialchars($sekolah['kecamatan'] ?? '') ?>"
                    data-nama="<?= strtolower($sekolah['nama_sekolah']) ?>">
                    <div class="card h-100 sekolah-card" data-id="<?= $sekolah['id_smk'] ?>">
                        <div class="card-body">
                            <h6 class="card-title text-primary">
                                <i class="bi bi-building me-2"></i>
                                <?= htmlspecialchars($sekolah['nama_sekolah']) ?>
                            </h6>
                            <p class="card-text small text-muted mb-2">
                                <i class="bi bi-geo-alt me-1"></i>
                                <?= htmlspecialchars($sekolah['alamat']) ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-info">
                                    <i class="bi bi-mortarboard me-1"></i>
                                    <?= $sekolah['jumlah_jurusan'] ?> Jurusan
                                </span>
                                <button type="button" class="btn btn-sm btn-primary btn-pilih-sekolah"
                                    data-id="<?= $sekolah['id_smk'] ?>"
                                    data-nama="<?= htmlspecialchars($sekolah['nama_sekolah']) ?>">
                                    <i class="bi bi-plus-circle me-1"></i>Pilih
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="col-12">
        <form method="POST" id="formPilihan">
            <?= Session::csrfField() ?>
            <input type="hidden" name="pilihan_mode" id="inputPilihanMode" value="satu_sekolah_dua_jurusan">
            <input type="hidden" name="sekolah_pilihan1" id="inputSekolah1" value="">
            <input type="hidden" name="kejuruan_pilihan1" id="inputKejuruan1" value="">
            <input type="hidden" name="sekolah_pilihan2" id="inputSekolah2" value="">
            <input type="hidden" name="kejuruan_pilihan2" id="inputKejuruan2" value="">

            <div class="d-flex justify-content-between">
                <a href="<?= SITE_URL ?>/user/" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
                <button type="submit" class="btn btn-primary btn-lg" id="btnSubmit" disabled>
                    <i class="bi bi-check-circle me-2"></i>Simpan Pilihan & Lanjutkan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Pilih Jurusan -->
<div class="modal fade" id="modalJurusan" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-mortarboard me-2"></i>Pilih Jurusan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6 id="modalSekolahNama" class="mb-3 text-primary"></h6>
                <div id="jurusanList" class="row g-3">
                    <!-- Jurusan akan dimuat via AJAX -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = new bootstrap.Modal(document.getElementById('modalJurusan'));
        let currentPilihan = 1;
        let selectedData = {
            pilihan1: {
                sekolah: <?= $pendaftaran['id_smk_pilihan1'] ?? 'null' ?>,
                sekolahNama: '<?= htmlspecialchars($pendaftaran['sekolah_pilihan1'] ?? '') ?>',
                kejuruan: <?= $pendaftaran['id_kejuruan_pilihan1'] ?? 'null' ?>,
                kejuruanNama: '<?= htmlspecialchars(getKejuruanNamaById($pendaftaran['id_kejuruan_pilihan1'] ?? 0)) ?>'
            },
            pilihan2: {
                sekolah: <?= $pendaftaran['id_smk_pilihan2'] ?? 'null' ?>,
                sekolahNama: '<?= htmlspecialchars($pendaftaran['sekolah_pilihan2'] ?? '') ?>',
                kejuruan: <?= $pendaftaran['id_kejuruan_pilihan2'] ?? 'null' ?>,
                kejuruanNama: '<?= htmlspecialchars(getKejuruanNamaById($pendaftaran['id_kejuruan_pilihan2'] ?? 0)) ?>'
            }
        };

        // Load existing data on page load
        if (selectedData.pilihan1.sekolah) {
            updatePilihanDisplay();
            validateForm();
        }

        // Filter sekolah
        document.getElementById('filterKota').addEventListener('change', filterSekolah);
        document.getElementById('searchSekolah').addEventListener('input', filterSekolah);

        function filterSekolah() {
            const kota = document.getElementById('filterKota').value.toLowerCase();
            const search = document.getElementById('searchSekolah').value.toLowerCase();

            document.querySelectorAll('.sekolah-item').forEach(item => {
                const itemKota = item.dataset.kecamatan.toLowerCase();
                const itemNama = item.dataset.nama;
                const showKota = !kota || itemKota.includes(kota);
                const showSearch = !search || itemNama.includes(search);
                item.style.display = showKota && showSearch ? 'block' : 'none';
            });
        }

        // Mode pilihan
        document.querySelectorAll('.pilihan-mode-card').forEach(card => {
            card.addEventListener('click', function() {
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
                document.querySelectorAll('.pilihan-mode-card').forEach(c => c.classList.remove('border-primary'));
                this.classList.add('border-primary');
                document.getElementById('inputPilihanMode').value = radio.value;
            });
        });

        // Pilih sekolah
        document.querySelectorAll('.btn-pilih-sekolah').forEach(btn => {
            btn.addEventListener('click', function() {
                const sekolahId = this.dataset.id;
                const sekolahNama = this.dataset.nama;

                // Tentukan pilihan mana
                if (!selectedData.pilihan1.sekolah) {
                    currentPilihan = 1;
                } else if (!selectedData.pilihan2.sekolah) {
                    currentPilihan = 2;
                } else {
                    alert('Anda sudah memilih 2 pilihan. Hapus salah satu untuk mengganti.');
                    return;
                }

                document.getElementById('modalSekolahNama').textContent = sekolahNama;
                loadJurusan(sekolahId, sekolahNama);
                modal.show();
            });
        });

        // Load jurusan via AJAX
        function loadJurusan(sekolahId, sekolahNama) {
            fetch('<?= SITE_URL ?>/api/get-kejuruan.php?id_smk=' + sekolahId)
                .then(res => res.json())
                .then(data => {
                    const container = document.getElementById('jurusanList');
                    container.innerHTML = '';

                    if (data.success && data.data.length > 0) {
                        data.data.forEach(j => {
                            const butaWarnaClass = j.syarat_tidak_buta_warna ? 'text-warning' : 'text-success';
                            const butaWarnaText = j.syarat_tidak_buta_warna ? 'Ya' : 'Tidak';

                            container.innerHTML += `
                            <div class="col-12">
                                <div class="card jurusan-card" data-id="${j.id_program}" 
                                     data-nama="${j.nama_kejuruan}" data-sekolah="${sekolahId}"
                                     data-sekolah-nama="${sekolahNama}">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">${j.nama_kejuruan}</h6>
                                            <small class="text-muted">${j.kode_kejuruan}</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-light text-dark me-2">Kuota: ${j.kuota || 36}</span>
                                            <span class="badge ${j.syarat_tidak_buta_warna ? 'bg-warning' : 'bg-success'}">
                                                <i class="bi bi-eye me-1"></i>Buta Warna: ${butaWarnaText}
                                            </span>
                                            <button class="btn btn-primary btn-sm ms-2 btn-pilih-jurusan">
                                                <i class="bi bi-check-circle"></i> Pilih
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        });

                        // Bind event
                        container.querySelectorAll('.btn-pilih-jurusan').forEach(btn => {
                            btn.addEventListener('click', function() {
                                const card = this.closest('.jurusan-card');
                                selectJurusan(
                                    card.dataset.sekolah,
                                    card.dataset.sekolahNama,
                                    card.dataset.id,
                                    card.dataset.nama
                                );
                            });
                        });
                    } else {
                        container.innerHTML = '<div class="col-12"><p class="text-muted text-center">Tidak ada jurusan tersedia</p></div>';
                    }
                });
        }

        function selectJurusan(sekolahId, sekolahNama, kejuruanId, kejuruanNama) {
            const key = 'pilihan' + currentPilihan;
            selectedData[key] = {
                sekolah: sekolahId,
                sekolahNama: sekolahNama,
                kejuruan: kejuruanId,
                kejuruanNama: kejuruanNama
            };

            updatePilihanDisplay();
            modal.hide();
            validateForm();

            // Auto-save to database
            autoSave();
        }

        // Auto-save function to prevent data loss
        async function autoSave() {
            const data = {
                pilihan_mode: document.getElementById('inputPilihanMode').value,
                sekolah_pilihan1: selectedData.pilihan1.sekolah,
                kejuruan_pilihan1: selectedData.pilihan1.kejuruan,
                sekolah_pilihan2: selectedData.pilihan2.sekolah,
                kejuruan_pilihan2: selectedData.pilihan2.kejuruan
            };

            try {
                const response = await fetch('<?= SITE_URL ?>/api/save-pilihan.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                if (result.success) {
                    console.log('Auto-saved successfully');
                }
            } catch (error) {
                console.error('Auto-save failed:', error);
            }
        }

        function updatePilihanDisplay() {
            // Update pilihan 1
            const container1 = document.getElementById('pilihan1Container');
            if (selectedData.pilihan1.sekolah) {
                container1.innerHTML = `
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-primary mb-1">${selectedData.pilihan1.sekolahNama}</h6>
                        <p class="mb-0"><i class="bi bi-mortarboard me-1"></i>${selectedData.pilihan1.kejuruanNama}</p>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearPilihan(1)">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            `;
                document.getElementById('inputSekolah1').value = selectedData.pilihan1.sekolah;
                document.getElementById('inputKejuruan1').value = selectedData.pilihan1.kejuruan;
            }

            // Update pilihan 2
            const container2 = document.getElementById('pilihan2Container');
            if (selectedData.pilihan2.sekolah) {
                container2.innerHTML = `
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-primary mb-1">${selectedData.pilihan2.sekolahNama}</h6>
                        <p class="mb-0"><i class="bi bi-mortarboard me-1"></i>${selectedData.pilihan2.kejuruanNama}</p>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearPilihan(2)">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            `;
                document.getElementById('inputSekolah2').value = selectedData.pilihan2.sekolah;
                document.getElementById('inputKejuruan2').value = selectedData.pilihan2.kejuruan;
            }
        }

        window.clearPilihan = function(pilihan) {
            const key = 'pilihan' + pilihan;
            selectedData[key] = {
                sekolah: null,
                sekolahNama: '',
                kejuruan: null,
                kejuruanNama: ''
            };

            const container = document.getElementById('pilihan' + pilihan + 'Container');
            container.innerHTML = `
            <p class="text-muted text-center py-4">
                <i class="bi bi-hand-index-thumb fs-1 d-block mb-2"></i>
                Klik sekolah di bawah untuk memilih
            </p>
        `;

            document.getElementById('inputSekolah' + pilihan).value = '';
            document.getElementById('inputKejuruan' + pilihan).value = '';
            validateForm();

            // Auto-save after clearing
            autoSave();
        };

        function validateForm() {
            const btn = document.getElementById('btnSubmit');
            btn.disabled = !selectedData.pilihan1.sekolah || !selectedData.pilihan1.kejuruan;
        }
    });
</script>

<?php require_once 'includes/footer.php'; ?>