<?php

/**
 * PPDB SMK - Landing Page
 * Sistem Penerimaan Peserta Didik Baru SMK Kota Padang
 */

require_once 'config/database.php';
require_once 'config/functions.php';
require_once 'config/session.php';

// Get data
$smkList = getAllSMK();
$jalurList = getAllJalur();
$totalPendaftar = countPendaftarByStatus();
$tahunAjaran = getTahunAjaran();
$isOpen = isPPDBOpen();

// Prepare SMK data for map
$smkJson = json_encode(array_map(function ($smk) {
    return [
        'id_smk' => $smk['id_smk'],
        'nama_sekolah' => $smk['nama_sekolah'],
        'alamat' => $smk['alamat'],
        'latitude' => $smk['latitude'],
        'longitude' => $smk['longitude'],
        'jumlah_siswa' => $smk['jumlah_siswa'],
        'jumlah_guru' => $smk['jumlah_guru']
    ];
}, $smkList));
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= SITE_DESCRIPTION ?>">
    <title><?= SITE_NAME ?> - Tahun Ajaran <?= $tahunAjaran ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Custom CSS -->
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

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list fs-4"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto me-3">
                    <li class="nav-item">
                        <a class="nav-link active" href="#home">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#statistik">Statistik</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#jalur">Jalur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#jadwal">Tahapan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#sekolah">SMK</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="perangkingan.php">Perangkingan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#kontak">Kontak</a>
                    </li>
                </ul>

                <div class="d-flex gap-2 nav-buttons">
                    <?php if (Session::isLoggedIn()): ?>
                        <?php
                        $dashboardUrl = match (Session::getRole()) {
                            ROLE_SUPERADMIN => 'superadmin/',
                            ROLE_ADMIN => 'admin/',
                            default => 'user/'
                        };
                        ?>
                        <a href="<?= $dashboardUrl ?>" class="btn btn-primary btn-sm">
                            Dashboard
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-primary btn-sm">
                            Masuk
                        </a>
                        <?php if ($isOpen): ?>
                            <a href="register.php" class="btn btn-primary btn-sm">
                                Daftar
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="floating-shapes">
            <div class="floating-shape"></div>
            <div class="floating-shape"></div>
            <div class="floating-shape"></div>
        </div>

        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6 hero-content" data-aos="fade-right">
                    <span class="badge bg-primary-soft text-primary mb-3 px-3 py-2">
                        <i class="bi bi-calendar-check me-1"></i>
                        Tahun Ajaran <?= $tahunAjaran ?>
                    </span>

                    <h1 class="hero-title">
                        Sistem Penerimaan Murid Baru
                        <span class="text-gradient">SMK Kota Padang</span>
                    </h1>

                    <p class="hero-subtitle">
                        Selamat datang di SPMB SMK Kota Padang. Daftarkan diri Anda sekarang
                        dan raih masa depan cerah bersama SMK terbaik di Kota Padang.
                    </p>

                    <div class="d-flex gap-3 flex-wrap mb-4">
                        <?php if ($isOpen): ?>
                            <a href="register.php" class="btn btn-primary btn-lg">
                                <i class="bi bi-person-plus-fill me-2"></i>
                                Daftar Sekarang
                            </a>
                        <?php else: ?>
                            <button class="btn btn-secondary btn-lg" disabled>
                                <i class="bi bi-lock-fill me-2"></i>
                                Pendaftaran Ditutup
                            </button>
                        <?php endif; ?>
                        <a href="#jalur" class="btn btn-outline-primary btn-lg">
                            <i class="bi bi-info-circle me-2"></i>
                            Info Jalur
                        </a>
                    </div>


                    <div class="hero-stats">
                        <div class="hero-stat" data-aos="fade-up" data-aos-delay="100">
                            <div class="hero-stat-number counter" data-target="<?= count($smkList) ?>">0</div>
                            <div class="hero-stat-label">SMK Tersedia</div>
                        </div>
                        <div class="hero-stat" data-aos="fade-up" data-aos-delay="200">
                            <div class="hero-stat-number counter" data-target="<?= $totalPendaftar ?>">0</div>
                            <div class="hero-stat-label">Pendaftar</div>
                        </div>
                        <div class="hero-stat" data-aos="fade-up" data-aos-delay="300">
                            <div class="hero-stat-number counter" data-target="4">0</div>
                            <div class="hero-stat-label">Jalur Seleksi</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 text-center" data-aos="fade-left">
                    <img src="assets/img/hero-student.jpg" alt="Siswa SMK" class="img-fluid hero-image"
                        style="max-height: 600px; width: 100%; object-fit: cover; filter: drop-shadow(0 20px 40px rgba(139, 92, 246, 0.25)); border-radius: 24px;"
                        onerror="this.src='https://illustrations.popsy.co/amber/student-with-a-laptop.svg'">
                </div>
            </div>
        </div>

        <a href="#statistik" class="position-absolute bottom-0 start-50 translate-middle-x mb-4 text-primary">
            <i class="bi bi-chevron-double-down fs-3" style="animation: bounce 2s infinite;"></i>
        </a>
    </section>

    <!-- Wave Separator -->
    <div class="section-wave">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path d="M0 120L48 105C96 90 192 60 288 45C384 30 480 30 576 37.5C672 45 768 60 864 67.5C960 75 1056 75 1152 67.5C1248 60 1344 45 1392 37.5L1440 30V0H1392C1344 0 1248 0 1152 0C1056 0 960 0 864 0C768 0 672 0 576 0C480 0 384 0 288 0C192 0 96 0 48 0H0V120Z" fill="url(#waveGradient)" />
            <defs>
                <linearGradient id="waveGradient" x1="0" y1="0" x2="1440" y2="0">
                    <stop offset="0%" stop-color="#E0E7FF" />
                    <stop offset="50%" stop-color="#DDD6FE" />
                    <stop offset="100%" stop-color="#E0E7FF" />
                </linearGradient>
            </defs>
        </svg>
    </div>

    <!-- Statistik Section -->
    <section id="statistik" class="py-5 bg-light-gradient">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-primary-soft text-primary mb-3 px-3 py-2">
                    <i class="bi bi-bar-chart-fill me-1"></i> Statistik Real-time
                </span>
                <h2 class="mb-3">Data Pendaftaran SPMB</h2>
                <p class="text-muted mx-auto" style="max-width: 600px;">
                    Pantau statistik pendaftaran secara real-time untuk setiap jalur seleksi.
                </p>
            </div>

            <div class="row g-4">
                <?php foreach ($jalurList as $index => $jalur):
                    $pendaftarJalur = countPendaftarByJalur($jalur['id_jalur']);
                ?>
                    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                        <div class="card stat-card-jalur h-100">
                            <div class="card-body text-center">
                                <div class="stat-jalur-icon <?= $jalur['kode_jalur'] ?> mx-auto mb-3">
                                    <i class="bi <?= $jalur['icon'] ?? 'bi-bookmark-star' ?>"></i>
                                </div>
                                <h5 class="fw-bold mb-3"><?= htmlspecialchars($jalur['nama_jalur']) ?></h5>
                                <div class="row g-2 text-start">
                                    <div class="col-6">
                                        <div class="stat-mini">
                                            <div class="stat-mini-value text-primary"><?= number_format($jalur['kuota_persen']) ?>%</div>
                                            <div class="stat-mini-label">Kuota</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stat-mini">
                                            <div class="stat-mini-value"><?= $pendaftarJalur ?></div>
                                            <div class="stat-mini-label">Pendaftar</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Jalur Pendaftaran Section -->
    <section id="jalur" class="py-5 bg-dark-alt">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-primary-soft text-primary mb-3 px-3 py-2">
                    <i class="bi bi-signpost-split me-1"></i> Jalur Pendaftaran
                </span>
                <h2 class="mb-3">Pilih Jalur Pendaftaran</h2>
                <p class="text-muted mx-auto" style="max-width: 600px;">
                    SPMB SMK menyediakan 4 jalur pendaftaran yang dapat disesuaikan dengan kondisi dan prestasi Anda.
                </p>
            </div>

            <div class="row g-4">
                <?php foreach ($jalurList as $index => $jalur): ?>
                    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                        <div class="card jalur-card <?= $jalur['kode_jalur'] ?> h-100">
                            <div class="jalur-icon">
                                <i class="bi <?= $jalur['icon'] ?? 'bi-bookmark-star' ?>"></i>
                            </div>
                            <h4 class="jalur-title"><?= htmlspecialchars($jalur['nama_jalur']) ?></h4>
                            <p class="jalur-desc"><?= htmlspecialchars(truncate($jalur['deskripsi'] ?? '', 100)) ?></p>
                            <div class="jalur-quota">
                                <i class="bi bi-pie-chart-fill me-1"></i>
                                Kuota <?= number_format($jalur['kuota_persen'], 0) ?>%
                            </div>
                            <div class="mt-3">
                                <a href="daftar.php?jalur=<?= $jalur['kode_jalur'] ?>" class="btn btn-sm btn-outline-primary">
                                    Selengkapnya <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section id="peta" class="map-section py-5">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-primary-soft text-primary mb-3 px-3 py-2">
                    <i class="bi bi-geo-alt me-1"></i> Peta Interaktif
                </span>
                <h2 class="mb-3" style="color: var(--text-dark);">Temukan SMK Terdekat</h2>
                <p class="text-muted mx-auto" style="max-width: 600px;">
                    Gunakan peta interaktif untuk melihat lokasi semua SMK dan mengukur jarak dari lokasi Anda.
                </p>
            </div>

            <div class="row g-4">
                <!-- Map Column -->
                <div class="col-lg-8" data-aos="fade-right">
                    <div class="card map-card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">
                                <i class="bi bi-map me-2 text-primary"></i>Peta Lokasi SMK
                            </h5>
                            <div class="map-legend d-flex gap-2">
                                <span class="badge bg-primary-soft text-primary"><i class="bi bi-geo-alt-fill me-1"></i>SMK</span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="map-container">
                                <div id="mapLeaflet"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detection Location Column -->
                <div class="col-lg-4" data-aos="fade-left">
                    <div class="card detection-card h-100">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-crosshair me-2 text-primary"></i>Deteksi Lokasi</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-3">
                                Klik tombol di bawah untuk mendeteksi lokasi Anda dan melihat SMK terdekat.
                            </p>

                            <button id="btnDetectLocation" class="btn btn-primary w-100 mb-3">
                                <i class="bi bi-geo-alt-fill me-2"></i>
                                Deteksi Lokasi Saya
                            </button>

                            <div id="locationStatus" class="alert alert-info small d-none">
                                <i class="bi bi-info-circle me-1"></i>
                                <span>Mendeteksi lokasi...</span>
                            </div>

                            <hr>

                            <h6 class="mb-3">
                                <i class="bi bi-building me-2"></i>SMK Terdekat
                            </h6>

                            <div id="nearbySchools" class="nearby-schools">
                                <p class="text-muted small">Klik "Deteksi Lokasi" untuk melihat SMK terdekat.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Jadwal Section -->
    <section id="jadwal" class="py-5 bg-dark-alt">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-4 mb-lg-0" data-aos="fade-right">
                    <span class="badge bg-primary-soft text-primary mb-3 px-3 py-2">
                        <i class="bi bi-calendar3 me-1"></i> Jadwal PPDB
                    </span>
                    <h2 class="mb-3">Timeline Pendaftaran</h2>
                    <p class="text-muted">
                        Pastikan Anda tidak melewatkan jadwal penting dalam proses PPDB.
                        Siapkan dokumen yang diperlukan sebelum jadwal dimulai.
                    </p>
                    <a href="daftar.php" class="btn btn-primary">
                        <i class="bi bi-calendar-plus me-2"></i>Lihat Jadwal Lengkap
                    </a>
                </div>

                <div class="col-lg-7" data-aos="fade-left">
                    <div class="timeline">
                        <div class="timeline-item completed">
                            <div class="timeline-date">15 Januari 2025</div>
                            <div class="timeline-title">Pembukaan Pendaftaran</div>
                            <div class="timeline-desc">Pendaftaran online dibuka untuk semua jalur</div>
                        </div>
                        <div class="timeline-item active">
                            <div class="timeline-date">15 Jan - 31 Mar 2025</div>
                            <div class="timeline-title">Periode Pendaftaran</div>
                            <div class="timeline-desc">Pengisian formulir dan upload dokumen</div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-date">1 - 10 April 2025</div>
                            <div class="timeline-title">Verifikasi Dokumen</div>
                            <div class="timeline-desc">Pemeriksaan kelengkapan dan keabsahan dokumen</div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-date">15 April 2025</div>
                            <div class="timeline-title">Pengumuman Hasil</div>
                            <div class="timeline-desc">Pengumuman hasil seleksi PPDB</div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-date">20 - 30 April 2025</div>
                            <div class="timeline-title">Daftar Ulang</div>
                            <div class="timeline-desc">Pendaftaran ulang bagi yang diterima</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Daftar SMK Section -->
    <section id="sekolah" class="py-5">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-primary-soft text-primary mb-3 px-3 py-2">
                    <i class="bi bi-building me-1"></i> Daftar Sekolah
                </span>
                <h2 class="mb-3">SMK di Kota Padang</h2>
                <p class="text-muted mx-auto" style="max-width: 600px;">
                    Pilih sekolah yang sesuai dengan minat dan bakat Anda.
                </p>
            </div>

            <div class="row g-4">
                <?php foreach (array_slice($smkList, 0, 8) as $index => $smk): ?>
                    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="<?= $index * 50 ?>">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="stat-icon primary me-3" style="width:50px;height:50px;font-size:1.25rem;">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0"><?= htmlspecialchars($smk['nama_sekolah']) ?></h6>
                                        <small class="text-muted"><?= htmlspecialchars($smk['kecamatan'] ?? 'Padang') ?></small>
                                    </div>
                                </div>
                                <p class="small text-muted mb-3">
                                    <?= htmlspecialchars(truncate($smk['alamat'] ?? '-', 80)) ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-success-soft">
                                        <i class="bi bi-people me-1"></i><?= $smk['jumlah_siswa'] ?> siswa
                                    </span>
                                    <a href="info-sekolah.php?id=<?= $smk['id_smk'] ?>" class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (count($smkList) > 8): ?>
                <div class="text-center mt-4" data-aos="fade-up">
                    <a href="daftar-sekolah.php" class="btn btn-outline-primary">
                        Lihat Semua SMK <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="kontak" class="py-5 bg-light-gradient">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-primary-soft text-primary mb-3 px-3 py-2">
                    <i class="bi bi-headset me-1"></i> Hubungi Kami
                </span>
                <h2 class="mb-3">Butuh Bantuan?</h2>
                <p class="text-muted mx-auto" style="max-width: 600px;">
                    Jika ada pertanyaan atau kendala, silakan hubungi kami melalui form di bawah atau WhatsApp.
                </p>
            </div>

            <div class="row g-4 justify-content-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="card h-100">
                        <div class="card-body p-4">
                            <h5 class="mb-4"><i class="bi bi-chat-dots-fill text-primary me-2"></i>Form Pengaduan</h5>
                            <form>
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" placeholder="Masukkan nama lengkap">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" placeholder="email@example.com">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Pesan</label>
                                    <textarea class="form-control" rows="4" placeholder="Tulis pesan Anda..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-send-fill me-2"></i>Kirim Pesan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4" data-aos="fade-left">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body p-4 d-flex flex-column justify-content-center">
                            <div class="text-center mb-4">
                                <i class="bi bi-whatsapp display-1"></i>
                            </div>
                            <h5 class="text-center mb-3">Hubungi via WhatsApp</h5>
                            <p class="text-center opacity-75 mb-4">Respon cepat untuk pertanyaan mendesak</p>
                            <a href="https://wa.me/6282112345678" target="_blank" class="btn btn-light btn-lg w-100">
                                <i class="bi bi-whatsapp me-2"></i>Chat Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="footer-brand">
                        <img src="assets/img/sumbar.png" alt="Logo Sumbar" style="height: 36px; margin-right: 10px;">
                        SPMB SMK
                    </div>
                    <p class="footer-desc">
                        Sistem Penerimaan Murid Baru SMK Kota Padang.
                        Mendukung pendidikan berkualitas untuk generasi Indonesia.
                    </p>
                    <div class="social-links mt-4">
                        <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>

                <div class="col-6 col-lg-2">
                    <h6 class="footer-title">Menu</h6>
                    <ul class="footer-links">
                        <li><a href="#home">Beranda</a></li>
                        <li><a href="#statistik">Statistik</a></li>
                        <li><a href="#jalur">Jalur Pendaftaran</a></li>
                        <li><a href="#jadwal">Tahapan</a></li>
                    </ul>
                </div>

                <div class="col-6 col-lg-2">
                    <h6 class="footer-title">Informasi</h6>
                    <ul class="footer-links">
                        <li><a href="faq.php">FAQ</a></li>
                        <li><a href="syarat.php">Persyaratan</a></li>
                        <li><a href="panduan.php">Panduan</a></li>
                        <li><a href="kontak.php">Kontak</a></li>
                    </ul>
                </div>

                <div class="col-lg-4">
                    <h6 class="footer-title">Kontak</h6>
                    <ul class="footer-links">
                        <li><i class="bi bi-geo-alt me-2 text-primary"></i>Jl. Pendidikan No. 1, Padang</li>
                        <li><i class="bi bi-telephone me-2 text-primary"></i>0751-123456</li>
                        <li><i class="bi bi-envelope me-2 text-primary"></i>spmb@smkpadang.id</li>
                        <li><i class="bi bi-whatsapp me-2 text-success"></i>0821-1234-5678</li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p class="mb-0">
                    &copy; <?= date('Y') ?> SPMB SMK Kota Padang. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Custom JS -->
    <script src="assets/js/map.js"></script>
    <script src="assets/js/main.js"></script>

    <script>
        // Initialize map with schools data
        const schoolsData = <?= $smkJson ?>;
        let ppdbMap;

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize map
            ppdbMap = new PPDBMap('mapLeaflet', {
                schools: schoolsData,
                radiusZonasi: <?= RADIUS_ZONASI ?>,
                onSchoolSelect: function(school) {
                    console.log('Selected:', school);
                }
            });

            // Detect location button
            const btnDetect = document.getElementById('btnDetectLocation');
            const locationStatus = document.getElementById('locationStatus');
            const nearbySchools = document.getElementById('nearbySchools');

            btnDetect.addEventListener('click', async function() {
                this.disabled = true;
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mendeteksi...';
                locationStatus.classList.remove('d-none');
                locationStatus.innerHTML = '<i class="bi bi-info-circle me-1"></i>Mendeteksi lokasi Anda...';

                try {
                    const nearby = await ppdbMap.getCurrentLocation();
                    locationStatus.className = 'alert alert-success small';
                    locationStatus.innerHTML = '<i class="bi bi-check-circle me-1"></i>Lokasi berhasil terdeteksi!';

                    // Show nearby schools
                    if (nearby.length > 0) {
                        nearbySchools.innerHTML = nearby.slice(0, 5).map(school => `
                            <div class="d-flex align-items-center justify-content-between py-2 border-bottom border-subtle">
                                <div>
                                    <div class="fw-semibold small">${school.nama_sekolah}</div>
                                    <small class="text-muted">${formatDistance(school.distance)}</small>
                                </div>
                                <span class="badge ${school.distance <= <?= RADIUS_ZONASI ?> ? 'bg-success' : 'bg-warning'}">
                                    ${school.distance <= <?= RADIUS_ZONASI ?> ? 'Dalam Radius' : 'Luar Radius'}
                                </span>
                            </div>
                        `).join('');
                    } else {
                        nearbySchools.innerHTML = '<p class="text-muted small">Tidak ada SMK dalam radius zonasi.</p>';
                    }
                } catch (error) {
                    locationStatus.className = 'alert alert-danger small';
                    locationStatus.innerHTML = '<i class="bi bi-exclamation-circle me-1"></i>' + error.message;
                }

                this.disabled = false;
                this.innerHTML = '<i class="bi bi-crosshair me-2"></i>Deteksi Lokasi Saya';
            });
        });
    </script>

    <style>
        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-10px);
            }

            60% {
                transform: translateY(-5px);
            }
        }

        .bg-primary-soft {
            background: rgba(16, 185, 129, 0.15);
        }
    </style>
</body>

</html>