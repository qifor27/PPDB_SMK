<?php

/**
 * PPDB SMK - Input Nilai Rapor
 * Halaman input nilai rapor semester 1-5
 * Sesuai Juknis SPMB 2025/2026
 */

$pageTitle = 'Input Nilai Rapor';
require_once 'includes/header.php';
require_once dirname(__DIR__) . '/config/scoring.php';

// Cek pendaftaran
if (!$pendaftaran) {
    Session::flash('error', 'Silakan pilih sekolah terlebih dahulu.');
    redirect(SITE_URL . '/user/pilih-sekolah-smk.php');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Session::verifyCsrf($_POST['csrf_token'] ?? '')) {
        Session::flash('error', 'Token keamanan tidak valid.');
    } else {
        $semester1 = (float)($_POST['semester1'] ?? 0);
        $semester2 = (float)($_POST['semester2'] ?? 0);
        $semester3 = (float)($_POST['semester3'] ?? 0);
        $semester4 = (float)($_POST['semester4'] ?? 0);
        $semester5 = (float)($_POST['semester5'] ?? 0);
        $peringkat = (int)($_POST['peringkat_paralel'] ?? 0);

        // Validasi
        $errors = [];
        if ($semester1 < 0 || $semester1 > 100) $errors[] = 'Nilai semester 1 tidak valid';
        if ($semester2 < 0 || $semester2 > 100) $errors[] = 'Nilai semester 2 tidak valid';
        if ($semester3 < 0 || $semester3 > 100) $errors[] = 'Nilai semester 3 tidak valid';
        if ($semester4 < 0 || $semester4 > 100) $errors[] = 'Nilai semester 4 tidak valid';
        if ($semester5 < 0 || $semester5 > 100) $errors[] = 'Nilai semester 5 tidak valid';

        if (empty($errors)) {
            // Hitung rerata dan bobot
            $rerata = hitungRerataRapor([$semester1, $semester2, $semester3, $semester4, $semester5]);
            $bobot = getBobotRapor($rerata);

            // Update pendaftaran
            db()->update('tb_pendaftaran', [
                'nilai_rapor_semester1' => $semester1,
                'nilai_rapor_semester2' => $semester2,
                'nilai_rapor_semester3' => $semester3,
                'nilai_rapor_semester4' => $semester4,
                'nilai_rapor_semester5' => $semester5,
                'rerata_nilai_rapor' => $rerata,
                'bobot_nilai_rapor' => $bobot,
                'peringkat_paralel' => $peringkat
            ], 'id_pendaftaran = :id', ['id' => $pendaftaran['id_pendaftaran']]);

            Session::flash('success', 'Data nilai rapor berhasil disimpan.');
            redirect(SITE_URL . '/user/dokumen.php');
        } else {
            Session::flash('error', implode('<br>', $errors));
        }
    }
}

// Get existing data
$nilaiRapor = [
    'semester1' => $pendaftaran['nilai_rapor_semester1'] ?? '',
    'semester2' => $pendaftaran['nilai_rapor_semester2'] ?? '',
    'semester3' => $pendaftaran['nilai_rapor_semester3'] ?? '',
    'semester4' => $pendaftaran['nilai_rapor_semester4'] ?? '',
    'semester5' => $pendaftaran['nilai_rapor_semester5'] ?? '',
    'peringkat' => $pendaftaran['peringkat_paralel'] ?? ''
];
?>

<div class="row g-4">
    <!-- Info Card -->
    <div class="col-12">
        <div class="alert alert-info">
            <h6><i class="bi bi-info-circle me-2"></i>Petunjuk Pengisian</h6>
            <ul class="mb-0 small">
                <li>Masukkan <strong>rerata nilai kompetensi pengetahuan</strong> seluruh mata pelajaran untuk setiap semester</li>
                <li>Nilai harus sesuai dengan rapor asli yang akan diupload</li>
                <li>Peringkat paralel adalah peringkat Anda di seluruh kelas (bukan per kelas)</li>
            </ul>
        </div>
    </div>

    <!-- Form Input Nilai -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-journal-text me-2"></i>Nilai Rapor Semester 1-5</h5>
            </div>
            <div class="card-body">
                <form method="POST" id="formRapor">
                    <?= Session::csrfField() ?>

                    <div class="row g-3">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <div class="col-md-6 col-lg-4">
                                <label class="form-label">Semester <?= $i ?> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="0.01" min="0" max="100"
                                        class="form-control nilai-input"
                                        name="semester<?= $i ?>"
                                        id="semester<?= $i ?>"
                                        value="<?= htmlspecialchars($nilaiRapor['semester' . $i]) ?>"
                                        placeholder="0.00" required>
                                    <span class="input-group-text">/ 100</span>
                                </div>
                            </div>
                        <?php endfor; ?>

                        <div class="col-md-6 col-lg-4">
                            <label class="form-label">Peringkat Paralel</label>
                            <input type="number" min="1" class="form-control"
                                name="peringkat_paralel"
                                value="<?= htmlspecialchars($nilaiRapor['peringkat']) ?>"
                                placeholder="Peringkat di seluruh kelas">
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between">
                        <a href="<?= SITE_URL ?>/user/pilih-sekolah-smk.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Simpan & Lanjutkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Preview Kalkulasi -->
    <div class="col-lg-4">
        <div class="card sticky-top" style="top: 1rem;">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="bi bi-calculator me-2"></i>Kalkulasi Nilai</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="display-4 fw-bold text-primary" id="rerataDisplay">-</div>
                    <small class="text-muted">Rerata Nilai Rapor</small>
                </div>

                <hr>

                <div class="d-flex justify-content-between mb-2">
                    <span>Bobot Nilai Rapor:</span>
                    <strong id="bobotDisplay">-</strong>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Kontribusi (30%):</span>
                    <strong id="kontribusiDisplay">-</strong>
                </div>

                <hr>

                <div class="alert alert-secondary small mb-0">
                    <i class="bi bi-lightbulb me-1"></i>
                    Nilai Akhir SMK = 30% Rapor + 70% Tes Bakat Minat
                </div>
            </div>
        </div>

        <!-- Tabel Bobot -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-table me-2"></i>Tabel Pembobotan</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-striped mb-0 small">
                    <thead>
                        <tr>
                            <th>Nilai Rapor</th>
                            <th>Bobot</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>>= 98</td>
                            <td>94</td>
                        </tr>
                        <tr>
                            <td>97 - 97.99</td>
                            <td>93</td>
                        </tr>
                        <tr>
                            <td>96 - 96.99</td>
                            <td>92</td>
                        </tr>
                        <tr>
                            <td>95 - 95.99</td>
                            <td>91</td>
                        </tr>
                        <tr>
                            <td>94 - 94.99</td>
                            <td>90</td>
                        </tr>
                        <tr>
                            <td>93 - 93.99</td>
                            <td>89</td>
                        </tr>
                        <tr>
                            <td>92 - 92.99</td>
                            <td>88</td>
                        </tr>
                        <tr>
                            <td>
                                < 85</td>
                            <td>80</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.nilai-input');

        function calculateRerata() {
            let total = 0;
            let count = 0;

            inputs.forEach(input => {
                const val = parseFloat(input.value);
                if (!isNaN(val) && val > 0) {
                    total += val;
                    count++;
                }
            });

            if (count === 0) {
                document.getElementById('rerataDisplay').textContent = '-';
                document.getElementById('bobotDisplay').textContent = '-';
                document.getElementById('kontribusiDisplay').textContent = '-';
                return;
            }

            const rerata = total / count;
            document.getElementById('rerataDisplay').textContent = rerata.toFixed(2);

            // Calculate bobot
            let bobot = 80;
            if (rerata >= 98) bobot = 94;
            else if (rerata >= 97) bobot = 93;
            else if (rerata >= 96) bobot = 92;
            else if (rerata >= 95) bobot = 91;
            else if (rerata >= 94) bobot = 90;
            else if (rerata >= 93) bobot = 89;
            else if (rerata >= 92) bobot = 88;
            else if (rerata >= 91) bobot = 87;
            else if (rerata >= 90) bobot = 86;
            else if (rerata >= 89) bobot = 85;
            else if (rerata >= 88) bobot = 84;
            else if (rerata >= 87) bobot = 83;
            else if (rerata >= 86) bobot = 82;
            else if (rerata >= 85) bobot = 81;

            document.getElementById('bobotDisplay').textContent = bobot;
            document.getElementById('kontribusiDisplay').textContent = (bobot * 0.3).toFixed(2);
        }

        inputs.forEach(input => {
            input.addEventListener('input', calculateRerata);
        });

        // Initial calculation
        calculateRerata();
    });
</script>

<?php require_once 'includes/footer.php'; ?>