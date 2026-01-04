<?php
/**
 * PPDB SMK - Persyaratan Dokumen
 * Daftar dokumen yang diperlukan untuk pendaftaran SPMB
 */

require_once 'config/database.php';
require_once 'config/functions.php';
require_once 'config/session.php';

$tahunAjaran = getTahunAjaran();
$jalurList = getAllJalur();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Persyaratan Dokumen SPMB SMK Kota Padang">
    <title>Persyaratan Dokumen -
        <?= SITE_NAME ?>
    </title>

    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-ppdb fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="assets/img/sumbar.png" alt="Logo" style="height: 36px;" onerror="this.style.display='none'">
                <span class="brand-text">SPMB SMK</span>
            </a>
            <a href="index.php" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </nav>

    <!-- Content -->
    <section class="py-5" style="margin-top: 80px;">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- Header -->
                    <div class="text-center mb-5">
                        <span class="badge bg-warning-soft text-warning mb-3 px-3 py-2">
                            <i class="bi bi-folder-check me-1"></i> Dokumen Persyaratan
                        </span>
                        <h1 class="mb-3">Persyaratan Dokumen</h1>
                        <p class="text-muted">Siapkan dokumen berikut sebelum melakukan pendaftaran SPMB SMK</p>
                    </div>

                    <!-- Dokumen Umum -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-files me-2"></i>Dokumen Wajib (Semua Jalur)</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                                        <div>
                                            <strong>Kartu Keluarga (KK)</strong>
                                            <p class="text-muted small mb-0">Scan KK terbaru, format PDF/JPG, maks. 2MB
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                                        <div>
                                            <strong>Akta Kelahiran</strong>
                                            <p class="text-muted small mb-0">Scan akta kelahiran asli, format PDF/JPG,
                                                maks. 2MB</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                                        <div>
                                            <strong>Ijazah/SKL SMP</strong>
                                            <p class="text-muted small mb-0">Ijazah atau Surat Keterangan Lulus, format
                                                PDF/JPG</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                                        <div>
                                            <strong>Raport Semester Terakhir</strong>
                                            <p class="text-muted small mb-0">Scan raport semester 5, format PDF/JPG</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                                        <div>
                                            <strong>Pas Foto 3x4</strong>
                                            <p class="text-muted small mb-0">Background merah, format JPG/PNG, maks.
                                                500KB</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                                        <div>
                                            <strong>NISN Aktif</strong>
                                            <p class="text-muted small mb-0">Nomor Induk Siswa Nasional yang terdaftar
                                                di Dapodik</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dokumen per Jalur -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0"><i class="bi bi-heart-fill me-2"></i>Jalur Afirmasi - Dokumen Tambahan</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><i class="bi bi-arrow-right-circle text-danger me-2"></i>Kartu
                                    Indonesia Pintar (KIP) / Kartu Keluarga Sejahtera (KKS)</li>
                                <li class="mb-2"><i class="bi bi-arrow-right-circle text-danger me-2"></i>Surat
                                    Keterangan Tidak Mampu (SKTM) dari Kelurahan</li>
                                <li class="mb-2"><i class="bi bi-arrow-right-circle text-danger me-2"></i>Kartu PKH
                                    (Program Keluarga Harapan) yang masih berlaku</li>
                                <li><i class="bi bi-arrow-right-circle text-danger me-2"></i>Kartu KIS (Kartu Indonesia
                                    Sehat) yang masih berlaku</li>
                            </ul>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="bi bi-trophy-fill me-2"></i>Jalur Prestasi - Dokumen Tambahan
                            </h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><i
                                        class="bi bi-arrow-right-circle text-warning me-2"></i>Sertifikat/Piagam
                                    Prestasi asli (minimal tingkat Kota/Kabupaten)</li>
                                <li class="mb-2"><i class="bi bi-arrow-right-circle text-warning me-2"></i>Prestasi
                                    harus diraih dalam 3 tahun terakhir</li>
                                <li class="mb-2"><i class="bi bi-arrow-right-circle text-warning me-2"></i>Surat
                                    rekomendasi dari sekolah asal</li>
                                <li><i class="bi bi-arrow-right-circle text-warning me-2"></i>Dokumentasi pendukung
                                    (jika ada)</li>
                            </ul>
                            <div class="alert alert-warning small mt-3 mb-0">
                                <i class="bi bi-info-circle me-1"></i>
                                <strong>Poin Prestasi:</strong> Internasional (100 poin), Nasional (80 poin), Provinsi
                                (60 poin), Kota/Kabupaten (40 poin)
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="bi bi-geo-alt-fill me-2"></i>Jalur Zonasi - Dokumen Tambahan</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><i class="bi bi-arrow-right-circle text-success me-2"></i>Kartu
                                    Keluarga yang menunjukkan domisili di Kota Padang</li>
                                <li class="mb-2"><i class="bi bi-arrow-right-circle text-success me-2"></i>Minimal
                                    domisili 1 tahun sebelum pendaftaran</li>
                                <li class="mb-2"><i class="bi bi-arrow-right-circle text-success me-2"></i>Bukti
                                    kepemilikan rumah atau surat kontrak (jika diperlukan)</li>
                                <li><i class="bi bi-arrow-right-circle text-success me-2"></i>Surat keterangan domisili
                                    dari RT/RW (opsional)</li>
                            </ul>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="bi bi-arrow-left-right me-2"></i>Jalur Kepindahan Orang Tua -
                                Dokumen Tambahan</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><i class="bi bi-arrow-right-circle text-info me-2"></i>Surat Keputusan
                                    (SK) Pindah Tugas Orang Tua</li>
                                <li class="mb-2"><i class="bi bi-arrow-right-circle text-info me-2"></i>Surat Keterangan
                                    dari instansi terkait (untuk ASN, TNI, POLRI)</li>
                                <li class="mb-2"><i class="bi bi-arrow-right-circle text-info me-2"></i>Kartu Keluarga
                                    baru di tempat tugas</li>
                                <li><i class="bi bi-arrow-right-circle text-info me-2"></i>Surat pindah dari sekolah
                                    asal</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Ketentuan Format -->
                    <div class="card shadow-sm mb-4 border-primary">
                        <div class="card-body">
                            <h5 class="text-primary mb-3"><i class="bi bi-exclamation-triangle me-2"></i>Ketentuan
                                Format Dokumen</h5>
                            <div class="row g-3">
                                <div class="col-md-4 text-center">
                                    <div class="border rounded p-3">
                                        <i class="bi bi-file-earmark-pdf fs-1 text-danger"></i>
                                        <p class="mb-0 mt-2"><strong>PDF</strong></p>
                                        <small class="text-muted">Untuk dokumen teks</small>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="border rounded p-3">
                                        <i class="bi bi-file-earmark-image fs-1 text-success"></i>
                                        <p class="mb-0 mt-2"><strong>JPG/PNG</strong></p>
                                        <small class="text-muted">Untuk foto & scan</small>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="border rounded p-3">
                                        <i class="bi bi-hdd fs-1 text-primary"></i>
                                        <p class="mb-0 mt-2"><strong>Maks. 2MB</strong></p>
                                        <small class="text-muted">Ukuran per file</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali ke Beranda
                        </a>
                        <a href="register.php" class="btn btn-primary">
                            <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container text-center py-4">
            <p class="mb-0">&copy;
                <?= date('Y') ?> SPMB SMK Kota Padang. All rights reserved.
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>