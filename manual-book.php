<?php
/**
 * PPDB SMK - Manual Book
 * Panduan Lengkap Penggunaan Sistem SPMB Online
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
    <meta name="description" content="Manual Book SPMB SMK Kota Padang">
    <title>Manual Book -
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
                        <span class="badge bg-success-soft text-success mb-3 px-3 py-2">
                            <i class="bi bi-book me-1"></i> Panduan Pengguna
                        </span>
                        <h1 class="mb-3">Manual Book SPMB</h1>
                        <p class="text-muted">Panduan langkah demi langkah untuk mendaftar SPMB SMK Online</p>
                    </div>

                    <!-- Quick Navigation -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h6 class="text-primary mb-3"><i class="bi bi-list-ul me-2"></i>Daftar Isi</h6>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <a href="#step1" class="text-decoration-none d-block p-2 border rounded hover-lift">
                                        <small><i class="bi bi-1-circle me-1"></i>Registrasi Akun</small>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="#step2" class="text-decoration-none d-block p-2 border rounded hover-lift">
                                        <small><i class="bi bi-2-circle me-1"></i>Login Sistem</small>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="#step3" class="text-decoration-none d-block p-2 border rounded hover-lift">
                                        <small><i class="bi bi-3-circle me-1"></i>Melengkapi Biodata</small>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="#step4" class="text-decoration-none d-block p-2 border rounded hover-lift">
                                        <small><i class="bi bi-4-circle me-1"></i>Upload Dokumen</small>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="#step5" class="text-decoration-none d-block p-2 border rounded hover-lift">
                                        <small><i class="bi bi-5-circle me-1"></i>Memilih Sekolah</small>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="#step6" class="text-decoration-none d-block p-2 border rounded hover-lift">
                                        <small><i class="bi bi-6-circle me-1"></i>Submit Pendaftaran</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 1: Registrasi -->
                    <div id="step1" class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-1-circle me-2"></i>Langkah 1: Registrasi Akun</h5>
                        </div>
                        <div class="card-body">
                            <ol>
                                <li class="mb-3">
                                    <strong>Buka halaman utama SPMB SMK</strong>
                                    <p class="text-muted small mb-0">Akses website melalui browser di komputer atau
                                        smartphone Anda</p>
                                </li>
                                <li class="mb-3">
                                    <strong>Klik tombol "Daftar" di pojok kanan atas</strong>
                                    <p class="text-muted small mb-0">Atau klik tombol "Daftar Sekarang" di halaman utama
                                    </p>
                                </li>
                                <li class="mb-3">
                                    <strong>Isi formulir registrasi:</strong>
                                    <ul class="mt-2">
                                        <li>NISN (Nomor Induk Siswa Nasional) - 10 digit</li>
                                        <li>Nama Lengkap sesuai Akta Kelahiran</li>
                                        <li>Email aktif (untuk aktivasi akun)</li>
                                        <li>Nomor HP (WhatsApp aktif)</li>
                                        <li>Password (minimal 8 karakter)</li>
                                    </ul>
                                </li>
                                <li>
                                    <strong>Klik tombol "Daftar"</strong>
                                    <p class="text-muted small mb-0">Sistem akan memverifikasi NISN Anda</p>
                                </li>
                            </ol>
                            <div class="alert alert-info small mb-0">
                                <i class="bi bi-lightbulb me-1"></i>
                                <strong>Tips:</strong> Pastikan NISN sudah terdaftar di Dapodik. Jika belum, hubungi
                                operator sekolah asal Anda.
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Login -->
                    <div id="step2" class="card shadow-sm mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="bi bi-2-circle me-2"></i>Langkah 2: Login ke Sistem</h5>
                        </div>
                        <div class="card-body">
                            <ol>
                                <li class="mb-3">
                                    <strong>Klik tombol "Masuk" di pojok kanan atas</strong>
                                </li>
                                <li class="mb-3">
                                    <strong>Masukkan Username dan Password</strong>
                                    <p class="text-muted small mb-0">Username adalah NISN atau username yang Anda
                                        daftarkan</p>
                                </li>
                                <li>
                                    <strong>Klik tombol "Masuk"</strong>
                                    <p class="text-muted small mb-0">Anda akan diarahkan ke Dashboard Siswa</p>
                                </li>
                            </ol>
                            <div class="alert alert-warning small mb-0">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                <strong>Lupa Password?</strong> Klik link "Lupa Password" dan ikuti instruksi untuk
                                reset password melalui email.
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Biodata -->
                    <div id="step3" class="card shadow-sm mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="bi bi-3-circle me-2"></i>Langkah 3: Melengkapi Biodata</h5>
                        </div>
                        <div class="card-body">
                            <ol>
                                <li class="mb-3">
                                    <strong>Klik menu "Biodata" di sidebar kiri</strong>
                                </li>
                                <li class="mb-3">
                                    <strong>Lengkapi data pribadi:</strong>
                                    <ul class="mt-2">
                                        <li>Data Diri (NIK, tempat & tanggal lahir, jenis kelamin, agama)</li>
                                        <li>Alamat domisili lengkap (akan digunakan untuk zonasi)</li>
                                        <li>Data Orang Tua/Wali</li>
                                        <li>Data Sekolah Asal</li>
                                    </ul>
                                </li>
                                <li>
                                    <strong>Klik tombol "Simpan"</strong>
                                    <p class="text-muted small mb-0">Data akan tersimpan otomatis</p>
                                </li>
                            </ol>
                            <div class="alert alert-danger small mb-0">
                                <i class="bi bi-exclamation-circle me-1"></i>
                                <strong>Penting:</strong> Alamat yang Anda masukkan harus sesuai dengan Kartu Keluarga.
                                Alamat ini akan digunakan untuk menghitung jarak zonasi.
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Upload Dokumen -->
                    <div id="step4" class="card shadow-sm mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="bi bi-4-circle me-2"></i>Langkah 4: Upload Dokumen</h5>
                        </div>
                        <div class="card-body">
                            <ol>
                                <li class="mb-3">
                                    <strong>Klik menu "Dokumen" di sidebar kiri</strong>
                                </li>
                                <li class="mb-3">
                                    <strong>Upload dokumen yang diperlukan:</strong>
                                    <ul class="mt-2">
                                        <li>Kartu Keluarga (KK)</li>
                                        <li>Akta Kelahiran</li>
                                        <li>Ijazah/SKL SMP</li>
                                        <li>Raport Semester Terakhir</li>
                                        <li>Pas Foto 3x4</li>
                                        <li>Dokumen pendukung sesuai jalur</li>
                                    </ul>
                                </li>
                                <li>
                                    <strong>Pastikan format dan ukuran file sesuai</strong>
                                    <p class="text-muted small mb-0">Format: PDF/JPG/PNG, Maksimal: 2MB per file</p>
                                </li>
                            </ol>
                            <div class="alert alert-success small mb-0">
                                <i class="bi bi-check-circle me-1"></i>
                                <strong>Tips:</strong> Scan dokumen dengan resolusi yang jelas (minimal 150 DPI) agar
                                mudah diverifikasi.
                            </div>
                        </div>
                    </div>

                    <!-- Step 5: Pilih Sekolah -->
                    <div id="step5" class="card shadow-sm mb-4">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0"><i class="bi bi-5-circle me-2"></i>Langkah 5: Memilih Sekolah & Jalur</h5>
                        </div>
                        <div class="card-body">
                            <ol>
                                <li class="mb-3">
                                    <strong>Klik menu "Pendaftaran" di sidebar kiri</strong>
                                </li>
                                <li class="mb-3">
                                    <strong>Pilih Tahap Seleksi</strong>
                                    <p class="text-muted small mb-0">Pilih tahap 1 atau 2 sesuai jadwal yang berlaku</p>
                                </li>
                                <li class="mb-3">
                                    <strong>Pilih Jalur Pendaftaran:</strong>
                                    <ul class="mt-2">
                                        <li><span class="badge bg-danger">Afirmasi</span> - Untuk siswa dari keluarga
                                            tidak mampu</li>
                                        <li><span class="badge bg-warning text-dark">Prestasi</span> - Untuk siswa
                                            berprestasi</li>
                                        <li><span class="badge bg-success">Zonasi</span> - Berdasarkan jarak domisili
                                        </li>
                                        <li><span class="badge bg-info">Kepindahan</span> - Untuk siswa yang orang
                                            tuanya pindah tugas</li>
                                    </ul>
                                </li>
                                <li class="mb-3">
                                    <strong>Pilih SMK Pilihan 1 dan Pilihan 2</strong>
                                    <p class="text-muted small mb-0">Gunakan peta untuk melihat jarak SMK dari lokasi
                                        Anda</p>
                                </li>
                                <li>
                                    <strong>Pilih Program Kejuruan yang diminati</strong>
                                </li>
                            </ol>
                        </div>
                    </div>

                    <!-- Step 6: Submit -->
                    <div id="step6" class="card shadow-sm mb-4">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0"><i class="bi bi-6-circle me-2"></i>Langkah 6: Submit Pendaftaran</h5>
                        </div>
                        <div class="card-body">
                            <ol>
                                <li class="mb-3">
                                    <strong>Periksa kembali semua data</strong>
                                    <p class="text-muted small mb-0">Pastikan biodata, dokumen, dan pilihan sekolah
                                        sudah benar</p>
                                </li>
                                <li class="mb-3">
                                    <strong>Centang pernyataan persetujuan</strong>
                                </li>
                                <li class="mb-3">
                                    <strong>Klik tombol "Submit Pendaftaran"</strong>
                                    <p class="text-muted small mb-0">Data yang sudah disubmit tidak dapat diubah</p>
                                </li>
                                <li>
                                    <strong>Cetak atau Simpan Bukti Pendaftaran</strong>
                                    <p class="text-muted small mb-0">Simpan nomor pendaftaran untuk referensi</p>
                                </li>
                            </ol>
                            <div class="alert alert-primary small mb-0">
                                <i class="bi bi-info-circle me-1"></i>
                                <strong>Selanjutnya:</strong> Tunggu proses verifikasi dokumen oleh admin. Status dapat
                                dipantau melalui menu "Status Pendaftaran".
                            </div>
                        </div>
                    </div>

                    <!-- FAQ -->
                    <div class="card shadow-sm mb-4 border-warning">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="bi bi-question-circle me-2"></i>Pertanyaan Umum (FAQ)</h5>
                        </div>
                        <div class="card-body">
                            <div class="accordion" id="faqAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#faq1">
                                            Bagaimana jika NISN tidak ditemukan?
                                        </button>
                                    </h2>
                                    <div id="faq1" class="accordion-collapse collapse show"
                                        data-bs-parent="#faqAccordion">
                                        <div class="accordion-body small">
                                            Hubungi operator Dapodik di sekolah asal Anda untuk memverifikasi dan
                                            mengaktifkan NISN.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq2">
                                            Apakah bisa mengubah data setelah submit?
                                        </button>
                                    </h2>
                                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body small">
                                            Data yang sudah disubmit tidak dapat diubah mandiri. Hubungi admin melalui
                                            menu pengaduan jika ada kesalahan data.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faq3">
                                            Bagaimana cara melihat hasil seleksi?
                                        </button>
                                    </h2>
                                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body small">
                                            Login ke akun Anda dan buka menu "Status Pendaftaran" atau lihat di halaman
                                            "Perangkingan" pada tanggal pengumuman.
                                        </div>
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