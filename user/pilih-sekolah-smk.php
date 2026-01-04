<?php

/**
 * PPDB SMK - Pilih Sekolah SMK v2
 * Flow baru sesuai Juknis SPMB 2025/2026:
 * 1. Pilih mode: 1 sekolah 2 jurusan ATAU 2 sekolah 1 jurusan
 * 2. Jika mode 1: Pilih sekolah -> pilih 2 jurusan
 * 3. Jika mode 2: Pilih jurusan -> pilih 2 sekolah yang punya jurusan sama
 */

$pageTitle = 'Pilih Sekolah SMK';
require_once 'includes/header.php';
require_once dirname(__DIR__) . '/config/scoring.php';

// Cek apakah sudah ada pendaftaran aktif yang bukan draft
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

// Get semua jurusan unik (untuk mode 2 sekolah)
$jurusanUnikList = db()->fetchAll("
    SELECT nama_kejuruan, COUNT(DISTINCT id_smk) as jumlah_sekolah
    FROM tb_kejuruan
    GROUP BY nama_kejuruan
    HAVING jumlah_sekolah >= 2
    ORDER BY nama_kejuruan
");

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

        $errors = [];
        if (!$sekolah1) $errors[] = 'Pilih sekolah pilihan 1';
        if (!$kejuruan1) $errors[] = 'Pilih jurusan pilihan 1';

        // Set jenis_pilihan dan lokasi_tes
        $jenisPilihan = 'single';
        $lokasiTesSMK = $sekolah1;

        if ($pilihanMode === 'satu_sekolah_dua_jurusan') {
            if (!$kejuruan2) $errors[] = 'Pilih jurusan pilihan 2';
            if ($kejuruan1 === $kejuruan2) $errors[] = 'Jurusan pilihan 1 dan 2 tidak boleh sama';
            $jenisPilihan = 'same_school';
            $sekolah2 = $sekolah1; // Sekolah sama
        } elseif ($pilihanMode === 'dua_sekolah_satu_jurusan') {
            if (!$sekolah2) $errors[] = 'Pilih sekolah pilihan 2';
            if ($sekolah1 === $sekolah2) $errors[] = 'Sekolah pilihan 1 dan 2 tidak boleh sama';
            $jenisPilihan = 'diff_school';
            $lokasiTesSMK = $sekolah1; // Tes di pilihan 1
        }

        // Cek syarat buta warna
        $kejuruanData1 = db()->fetch("SELECT * FROM tb_kejuruan WHERE id_program = ?", [$kejuruan1]);
        if ($kejuruanData1 && $kejuruanData1['syarat_tidak_buta_warna'] && ($siswa['status_buta_warna'] ?? '') !== 'tidak') {
            $errors[] = 'Jurusan ' . $kejuruanData1['nama_kejuruan'] . ' memerlukan syarat tidak buta warna';
        }

        if (empty($errors)) {
            if ($pendaftaran) {
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
                $tahap = Session::get('tahap_pendaftaran') ?? 1;
                $nomorPendaftaran = generateNomorPendaftaranSMK($userId, $tahap);
                $data = [
                    'nomor_pendaftaran' => $nomorPendaftaran,
                    'id_siswa' => $userId,
                    'id_smk_pilihan1' => $sekolah1,
                    'id_smk_pilihan2' => $sekolah2 ?: null,
                    'id_kejuruan_pilihan1' => $kejuruan1,
                    'id_kejuruan_pilihan2' => $kejuruan2 ?: null,
                    'id_jalur' => 1,
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

// Current mode from database
$currentMode = $pendaftaran['pilihan_mode'] ?? null;
?>

<style>
    .mode-card {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid #e9ecef;
        border-radius: 15px;
        overflow: hidden;
    }

    .mode-card:hover {
        border-color: #667eea;
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }

    .mode-card.active {
        border-color: #667eea;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.15), rgba(118, 75, 162, 0.15));
    }

    .mode-card .icon-wrapper {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 1rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .step-section {
        display: none;
    }

    .step-section.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .school-card,
    .jurusan-card {
        cursor: pointer;
        transition: all 0.2s ease;
        border: 2px solid transparent;
        border-radius: 12px;
    }

    .school-card:hover,
    .jurusan-card:hover {
        border-color: #667eea;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.15);
    }

    .school-card.selected,
    .jurusan-card.selected {
        border-color: #667eea;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.15), rgba(118, 75, 162, 0.15));
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.25);
    }

    .selection-badge {
        position: absolute;
        top: -10px;
        right: -10px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: 0 3px 10px rgba(102, 126, 234, 0.4);
    }

    .step-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .summary-card {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
        border: 1px solid rgba(102, 126, 234, 0.2);
        border-radius: 12px;
    }

    .pilihan-badge-1 {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
    }

    .pilihan-badge-2 {
        background: linear-gradient(135deg, #764ba2 0%, #c56cd6 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
    }

    .btn-purple {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-purple:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .jurusan-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        color: white;
        font-size: 1.5rem;
    }

    .school-card h6,
    .jurusan-card h6 {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
</style>

<form method="POST" id="formPilihan">
    <?= Session::csrfField() ?>
    <input type="hidden" name="pilihan_mode" id="inputPilihanMode" value="<?= $currentMode ?>">
    <input type="hidden" name="sekolah_pilihan1" id="inputSekolah1" value="<?= $pendaftaran['id_smk_pilihan1'] ?? '' ?>">
    <input type="hidden" name="kejuruan_pilihan1" id="inputKejuruan1" value="<?= $pendaftaran['id_kejuruan_pilihan1'] ?? '' ?>">
    <input type="hidden" name="sekolah_pilihan2" id="inputSekolah2" value="<?= $pendaftaran['id_smk_pilihan2'] ?? '' ?>">
    <input type="hidden" name="kejuruan_pilihan2" id="inputKejuruan2" value="<?= $pendaftaran['id_kejuruan_pilihan2'] ?? '' ?>">

    <!-- STEP 1: Pilih Mode -->
    <div class="step-section active" id="step1">
        <div class="text-center mb-4">
            <h4><i class="bi bi-1-circle-fill text-primary me-2"></i>Pilih Cara Mendaftar</h4>
            <p class="text-muted">Sesuai Juknis SPMB Sumatera Barat 2025</p>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Mode 1: 1 Sekolah, 2 Jurusan -->
            <div class="col-md-5">
                <div class="card mode-card h-100 text-center p-4" data-mode="satu_sekolah_dua_jurusan">
                    <div class="icon-wrapper">
                        <i class="bi bi-building"></i>
                    </div>
                    <h5 class="mb-3">1 Sekolah, 2 Jurusan</h5>
                    <p class="text-muted small mb-3">
                        Pilih <strong>satu sekolah</strong> dengan <strong>dua jurusan berbeda</strong>
                    </p>
                    <div class="alert alert-light small mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Tes Bakat Minat dilakukan 2x di sekolah yang sama
                    </div>
                </div>
            </div>

            <!-- Mode 2: 2 Sekolah, 1 Jurusan -->
            <div class="col-md-5">
                <div class="card mode-card h-100 text-center p-4" data-mode="dua_sekolah_satu_jurusan">
                    <div class="icon-wrapper">
                        <i class="bi bi-buildings"></i>
                    </div>
                    <h5 class="mb-3">2 Sekolah, 1 Jurusan</h5>
                    <p class="text-muted small mb-3">
                        Pilih <strong>dua sekolah berbeda</strong> dengan <strong>jurusan yang sama</strong>
                    </p>
                    <div class="alert alert-light small mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Tes Bakat Minat hanya 1x di sekolah pilihan 1
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- STEP 2A: Mode 1 Sekolah - Pilih Sekolah -->
    <div class="step-section" id="step2a">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4><i class="bi bi-2-circle-fill text-primary me-2"></i>Pilih Sekolah</h4>
                <p class="text-muted mb-0">Pilih 1 sekolah untuk mendaftar</p>
            </div>
            <button type="button" class="btn btn-outline-secondary btn-back" data-step="1">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </button>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <input type="text" id="searchSekolah2a" class="form-control" placeholder="Cari nama sekolah...">
            </div>
        </div>

        <div class="row g-3" id="sekolahList2a">
            <?php foreach ($sekolahList as $s): ?>
                <div class="col-md-4 sekolah-item" data-nama="<?= strtolower($s['nama_sekolah']) ?>">
                    <div class="card school-card h-100" data-id="<?= $s['id_smk'] ?>" data-nama="<?= htmlspecialchars($s['nama_sekolah']) ?>">
                        <div class="card-body">
                            <h6 class="text-primary mb-2"><?= htmlspecialchars($s['nama_sekolah']) ?></h6>
                            <p class="small text-muted mb-2"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($s['alamat']) ?></p>
                            <span class="badge bg-info"><?= $s['jumlah_jurusan'] ?> Jurusan</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- STEP 3A: Mode 1 Sekolah - Pilih 2 Jurusan -->
    <div class="step-section" id="step3a">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4><i class="bi bi-3-circle-fill text-primary me-2"></i>Pilih 2 Jurusan</h4>
                <p class="text-muted mb-0">Sekolah: <strong id="selectedSchoolName2a"></strong></p>
            </div>
            <button type="button" class="btn btn-outline-secondary btn-back" data-step="2a">
                <i class="bi bi-arrow-left me-1"></i>Ganti Sekolah
            </button>
        </div>

        <div class="row g-3 mb-4" id="jurusanList3a">
            <!-- Loaded via JavaScript -->
        </div>

        <div class="card summary-card">
            <div class="card-body">
                <h6>Jurusan Dipilih:</h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="p-3 rounded pilihan-badge-1 mb-2 mb-md-0">
                            <small class="opacity-75">Pilihan 1</small>
                            <div id="selectedJurusan1A" class="fw-bold">-</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded pilihan-badge-2">
                            <small class="opacity-75">Pilihan 2</small>
                            <div id="selectedJurusan2A" class="fw-bold">-</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- STEP 2B: Mode 2 Sekolah - Pilih Jurusan Dulu -->
    <div class="step-section" id="step2b">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4><i class="bi bi-2-circle-fill text-primary me-2"></i>Pilih Jurusan</h4>
                <p class="text-muted mb-0">Pilih jurusan yang tersedia di minimal 2 sekolah</p>
            </div>
            <button type="button" class="btn btn-outline-secondary btn-back" data-step="1">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </button>
        </div>

        <div class="row g-3" id="jurusanUnikList">
            <?php foreach ($jurusanUnikList as $j): ?>
                <div class="col-md-4">
                    <div class="card jurusan-card h-100" data-nama="<?= htmlspecialchars($j['nama_kejuruan']) ?>">
                        <div class="card-body text-center">
                            <div class="jurusan-icon"><i class="bi bi-mortarboard"></i></div>
                            <h6><?= htmlspecialchars($j['nama_kejuruan']) ?></h6>
                            <span class="badge pilihan-badge-1"><?= $j['jumlah_sekolah'] ?> Sekolah</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- STEP 3B: Mode 2 Sekolah - Pilih 2 Sekolah -->
    <div class="step-section" id="step3b">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4><i class="bi bi-3-circle-fill text-primary me-2"></i>Pilih 2 Sekolah</h4>
                <p class="text-muted mb-0">Jurusan: <strong id="selectedJurusanName2b"></strong></p>
            </div>
            <button type="button" class="btn btn-outline-secondary btn-back" data-step="2b">
                <i class="bi bi-arrow-left me-1"></i>Ganti Jurusan
            </button>
        </div>

        <div class="row g-3 mb-4" id="sekolahListByJurusan">
            <!-- Loaded via JavaScript -->
        </div>

        <div class="card summary-card">
            <div class="card-body">
                <h6>Sekolah Dipilih:</h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="p-3 rounded pilihan-badge-1 mb-2 mb-md-0">
                            <small class="opacity-75">Pilihan 1 (Lokasi Tes)</small>
                            <div id="selectedSekolah1B" class="fw-bold">-</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded pilihan-badge-2">
                            <small class="opacity-75">Pilihan 2</small>
                            <div id="selectedSekolah2B" class="fw-bold">-</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="mt-4 text-center" id="submitSection" style="display: none;">
        <hr>
        <button type="submit" class="btn btn-purple btn-lg px-5">
            <i class="bi bi-check-circle me-2"></i>Simpan Pilihan & Lanjutkan
        </button>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentStep = 'step1';
        let selectedMode = '<?= $currentMode ?>';
        let selectedData = {
            sekolah1: {
                id: null,
                nama: ''
            },
            sekolah2: {
                id: null,
                nama: ''
            },
            jurusan1: {
                id: null,
                nama: ''
            },
            jurusan2: {
                id: null,
                nama: ''
            },
            jurusanNamaUntuk2Sekolah: ''
        };

        // Mode card click
        document.querySelectorAll('.mode-card').forEach(card => {
            card.addEventListener('click', function() {
                selectedMode = this.dataset.mode;
                document.getElementById('inputPilihanMode').value = selectedMode;

                document.querySelectorAll('.mode-card').forEach(c => c.classList.remove('active'));
                this.classList.add('active');

                // Reset selections
                resetSelections();

                // Go to next step based on mode
                if (selectedMode === 'satu_sekolah_dua_jurusan') {
                    showStep('step2a');
                } else {
                    showStep('step2b');
                }
            });
        });

        // Back buttons
        document.querySelectorAll('.btn-back').forEach(btn => {
            btn.addEventListener('click', function() {
                const targetStep = 'step' + this.dataset.step;
                showStep(targetStep);
            });
        });

        // Search filter for step 2a
        document.getElementById('searchSekolah2a').addEventListener('input', function() {
            const search = this.value.toLowerCase();
            document.querySelectorAll('#sekolahList2a .sekolah-item').forEach(item => {
                const nama = item.dataset.nama;
                item.style.display = nama.includes(search) ? 'block' : 'none';
            });
        });

        // Step 2A: Select school (Mode 1)
        document.querySelectorAll('#sekolahList2a .school-card').forEach(card => {
            card.addEventListener('click', function() {
                selectedData.sekolah1.id = this.dataset.id;
                selectedData.sekolah1.nama = this.dataset.nama;
                selectedData.sekolah2.id = this.dataset.id; // Same school

                document.getElementById('inputSekolah1').value = this.dataset.id;
                document.getElementById('inputSekolah2').value = this.dataset.id;
                document.getElementById('selectedSchoolName2a').textContent = this.dataset.nama;

                loadJurusanForSchool(this.dataset.id);
                showStep('step3a');
            });
        });

        // Step 2B: Select jurusan (Mode 2)
        document.querySelectorAll('#jurusanUnikList .jurusan-card').forEach(card => {
            card.addEventListener('click', function() {
                selectedData.jurusanNamaUntuk2Sekolah = this.dataset.nama;
                document.getElementById('selectedJurusanName2b').textContent = this.dataset.nama;

                loadSekolahByJurusan(this.dataset.nama);
                showStep('step3b');
            });
        });

        function showStep(step) {
            document.querySelectorAll('.step-section').forEach(s => s.classList.remove('active'));
            document.getElementById(step).classList.add('active');
            currentStep = step;

            // Show submit button only on final steps
            document.getElementById('submitSection').style.display =
                (step === 'step3a' || step === 'step3b') ? 'block' : 'none';
        }

        function resetSelections() {
            selectedData = {
                sekolah1: {
                    id: null,
                    nama: ''
                },
                sekolah2: {
                    id: null,
                    nama: ''
                },
                jurusan1: {
                    id: null,
                    nama: ''
                },
                jurusan2: {
                    id: null,
                    nama: ''
                },
                jurusanNamaUntuk2Sekolah: ''
            };
            ['inputSekolah1', 'inputSekolah2', 'inputKejuruan1', 'inputKejuruan2'].forEach(id => {
                document.getElementById(id).value = '';
            });
            updateDisplays();
        }

        function updateDisplays() {
            document.getElementById('selectedJurusan1A').textContent = selectedData.jurusan1.nama || '-';
            document.getElementById('selectedJurusan2A').textContent = selectedData.jurusan2.nama || '-';
            document.getElementById('selectedSekolah1B').textContent = selectedData.sekolah1.nama || '-';
            document.getElementById('selectedSekolah2B').textContent = selectedData.sekolah2.nama || '-';
        }

        // Load jurusan for a school (Mode 1)
        async function loadJurusanForSchool(sekolahId) {
            const container = document.getElementById('jurusanList3a');
            container.innerHTML = '<div class="col-12 text-center"><span class="spinner-border"></span></div>';

            try {
                const res = await fetch('<?= SITE_URL ?>/api/get-kejuruan.php?id_smk=' + sekolahId);
                const data = await res.json();

                if (data.success && data.data.length > 0) {
                    container.innerHTML = '';
                    data.data.forEach(j => {
                        container.innerHTML += `
                    <div class="col-md-4">
                        <div class="card jurusan-card h-100" data-id="${j.id_program}" data-nama="${j.nama_kejuruan}">
                            <div class="card-body">
                                <h6>${j.nama_kejuruan}</h6>
                                <small class="text-muted">${j.kode_kejuruan}</small>
                                <div class="mt-2">
                                    <span class="badge ${j.syarat_tidak_buta_warna ? 'bg-warning' : 'bg-success'}">
                                        ${j.syarat_tidak_buta_warna ? 'Tidak Buta Warna' : 'Umum'}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>`;
                    });

                    // Bind click events
                    container.querySelectorAll('.jurusan-card').forEach(card => {
                        card.addEventListener('click', function() {
                            selectJurusanMode1(this.dataset.id, this.dataset.nama);
                        });
                    });
                }
            } catch (err) {
                container.innerHTML = '<div class="col-12 text-danger">Gagal memuat jurusan</div>';
            }
        }

        function selectJurusanMode1(id, nama) {
            if (!selectedData.jurusan1.id) {
                selectedData.jurusan1 = {
                    id,
                    nama
                };
                document.getElementById('inputKejuruan1').value = id;
            } else if (!selectedData.jurusan2.id && id !== selectedData.jurusan1.id) {
                selectedData.jurusan2 = {
                    id,
                    nama
                };
                document.getElementById('inputKejuruan2').value = id;
            } else if (id === selectedData.jurusan1.id) {
                selectedData.jurusan1 = {
                    id: null,
                    nama: ''
                };
                document.getElementById('inputKejuruan1').value = '';
            } else if (id === selectedData.jurusan2.id) {
                selectedData.jurusan2 = {
                    id: null,
                    nama: ''
                };
                document.getElementById('inputKejuruan2').value = '';
            }

            // Update card visuals
            document.querySelectorAll('#jurusanList3a .jurusan-card').forEach(card => {
                card.classList.remove('selected');
                const existingBadge = card.querySelector('.selection-badge');
                if (existingBadge) existingBadge.remove();

                if (card.dataset.id === selectedData.jurusan1.id) {
                    card.classList.add('selected');
                    card.style.position = 'relative';
                    card.insertAdjacentHTML('beforeend', '<span class="selection-badge bg-primary">1</span>');
                } else if (card.dataset.id === selectedData.jurusan2.id) {
                    card.classList.add('selected');
                    card.style.position = 'relative';
                    card.insertAdjacentHTML('beforeend', '<span class="selection-badge bg-secondary">2</span>');
                }
            });

            updateDisplays();
        }

        // Load schools by jurusan name (Mode 2)
        async function loadSekolahByJurusan(jurusanNama) {
            const container = document.getElementById('sekolahListByJurusan');
            container.innerHTML = '<div class="col-12 text-center"><span class="spinner-border"></span></div>';

            try {
                const res = await fetch('<?= SITE_URL ?>/api/get-sekolah-by-jurusan.php?nama=' + encodeURIComponent(jurusanNama));
                const data = await res.json();

                if (data.success && data.data.length > 0) {
                    container.innerHTML = '';
                    data.data.forEach(s => {
                        container.innerHTML += `
                    <div class="col-md-4">
                        <div class="card school-card h-100" data-id="${s.id_smk}" data-nama="${s.nama_sekolah}" data-kejuruan="${s.id_program}">
                            <div class="card-body">
                                <h6 class="text-success">${s.nama_sekolah}</h6>
                                <p class="small text-muted mb-1"><i class="bi bi-geo-alt"></i> ${s.alamat || '-'}</p>
                                <span class="badge bg-info">Kuota: ${s.kuota || 36}</span>
                            </div>
                        </div>
                    </div>`;
                    });

                    // Bind click events
                    container.querySelectorAll('.school-card').forEach(card => {
                        card.addEventListener('click', function() {
                            selectSekolahMode2(this.dataset.id, this.dataset.nama, this.dataset.kejuruan);
                        });
                    });
                }
            } catch (err) {
                container.innerHTML = '<div class="col-12 text-danger">Gagal memuat sekolah</div>';
            }
        }

        function selectSekolahMode2(id, nama, kejuruanId) {
            if (!selectedData.sekolah1.id) {
                selectedData.sekolah1 = {
                    id,
                    nama
                };
                selectedData.jurusan1 = {
                    id: kejuruanId,
                    nama: selectedData.jurusanNamaUntuk2Sekolah
                };
                document.getElementById('inputSekolah1').value = id;
                document.getElementById('inputKejuruan1').value = kejuruanId;
            } else if (!selectedData.sekolah2.id && id !== selectedData.sekolah1.id) {
                selectedData.sekolah2 = {
                    id,
                    nama
                };
                selectedData.jurusan2 = {
                    id: kejuruanId,
                    nama: selectedData.jurusanNamaUntuk2Sekolah
                };
                document.getElementById('inputSekolah2').value = id;
                document.getElementById('inputKejuruan2').value = kejuruanId;
            } else if (id === selectedData.sekolah1.id) {
                selectedData.sekolah1 = {
                    id: null,
                    nama: ''
                };
                document.getElementById('inputSekolah1').value = '';
                document.getElementById('inputKejuruan1').value = '';
            } else if (id === selectedData.sekolah2.id) {
                selectedData.sekolah2 = {
                    id: null,
                    nama: ''
                };
                document.getElementById('inputSekolah2').value = '';
                document.getElementById('inputKejuruan2').value = '';
            }

            // Update card visuals
            document.querySelectorAll('#sekolahListByJurusan .school-card').forEach(card => {
                card.classList.remove('selected');
                const existingBadge = card.querySelector('.selection-badge');
                if (existingBadge) existingBadge.remove();

                if (card.dataset.id === selectedData.sekolah1.id) {
                    card.classList.add('selected');
                    card.style.position = 'relative';
                    card.insertAdjacentHTML('beforeend', '<span class="selection-badge bg-success">1</span>');
                } else if (card.dataset.id === selectedData.sekolah2.id) {
                    card.classList.add('selected');
                    card.style.position = 'relative';
                    card.insertAdjacentHTML('beforeend', '<span class="selection-badge bg-secondary">2</span>');
                }
            });

            updateDisplays();
        }
    });
</script>

<?php require_once 'includes/footer.php'; ?>