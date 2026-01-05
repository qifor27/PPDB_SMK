<?php

/**
 * PPDB SMK - Juknis SPMB
 * Petunjuk Teknis Pelaksanaan SPMB SMK Kota Padang
 */

require_once 'config/database.php';
require_once 'config/functions.php';
require_once 'config/session.php';

$tahunAjaran = getTahunAjaran();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Petunjuk Teknis SPMB SMK Kota Padang">
    <title>Juknis SPMB - <?= SITE_NAME ?></title>

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
                        <span class="badge bg-primary-soft text-primary mb-3 px-3 py-2">
                            <i class="bi bi-file-earmark-text me-1"></i> Dokumen Resmi
                        </span>
                        <h1 class="mb-3">Petunjuk Teknis SPMB</h1>
                        <p class="text-muted">SMK Kota Padang Tahun Ajaran <?= $tahunAjaran ?></p>
                    </div>

                    <!-- Juknis Content -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-1-circle me-2"></i>Dasar Hukum</h5>
                        </div>
                        <div class="card-body">
                            <ol>
                                <li>Undang-Undang Nomor 20 Tahun 2003 tentang Sistem Pendidikan Nasional</li>
                                <li>Peraturan Pemerintah Nomor 17 Tahun 2010 tentang Pengelolaan dan Penyelenggaraan
                                    Pendidikan</li>
                                <li>Peraturan Menteri Pendidikan dan Kebudayaan Nomor 1 Tahun 2021 tentang PPDB</li>
                                <li>Peraturan Daerah Kota Padang tentang Penyelenggaraan Pendidikan</li>
                                <li>Keputusan Kepala Dinas Pendidikan Kota Padang tentang SPMB SMK</li>
                            </ol>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="bi bi-2-circle me-2"></i>Tujuan SPMB</h5>
                        </div>
                        <div class="card-body">
                            <ol>
                                <li>Menjamin penerimaan peserta didik baru berjalan secara objektif, transparan,
                                    akuntabel, nondiskriminatif, dan berkeadilan</li>
                                <li>Meningkatkan akses layanan pendidikan SMK bagi masyarakat</li>
                                <li>Memastikan peserta didik mendapat sekolah yang sesuai dengan domisili, prestasi, dan
                                    kemampuan</li>
                                <li>Mendorong peningkatan kualitas pendidikan kejuruan di Kota Padang</li>
                            </ol>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="bi bi-3-circle me-2"></i>Tahapan Seleksi</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tahap</th>
                                            <th>Kegiatan</th>
                                            <th>Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Pendaftaran Online</td>
                                            <td>1 - 6 Januari 2026</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Verifikasi Dokumen</td>
                                            <td>7 - 8 Januari 2026</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Tes Minat & Bakat</td>
                                            <td>8 Januari 2026</td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Pengumuman Hasil</td>
                                            <td>10 Januari 2026</td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>Daftar Ulang</td>
                                            <td>11 - 15 Januari 2026</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0"><i class="bi bi-4-circle me-2"></i>Ketentuan Umum</h5>
                        </div>
                        <div class="card-body">
                            <ol>
                                <li>Calon peserta didik hanya dapat memilih <strong>1 (satu) jalur</strong> pendaftaran
                                </li>
                                <li>Calon peserta didik dapat memilih maksimal <strong>2 (dua) SMK</strong> pilihan</li>
                                <li>Verifikasi dokumen dilakukan secara online dan/atau offline</li>
                                <li>Seleksi dilaksanakan berdasarkan kriteria masing-masing jalur</li>
                                <li>Pengumuman hasil seleksi dapat dilihat melalui website resmi</li>
                                <li>Peserta yang diterima wajib melakukan daftar ulang sesuai jadwal</li>
                                <li>Peserta yang tidak daftar ulang dianggap mengundurkan diri</li>
                            </ol>
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
            <p class="mb-0">&copy; <?= date('Y') ?> SPMB SMK Kota Padang. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>