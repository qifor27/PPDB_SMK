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
                        <a class="nav-link" href="#statistik">Informasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#seleksi">Seleksi</a>
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
                        <a href="#seleksi" class="btn btn-outline-primary btn-lg">
                            <i class="bi bi-calendar-check me-2"></i>
                            Jadwal Seleksi
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
                    <img src="assets/img/hero-students.png" alt="Siswa SMK" class="img-fluid hero-image"
                        style="max-height: 600px; width: 100%; object-fit: contain; filter: drop-shadow(0 20px 40px rgba(139, 92, 246, 0.25)); border-radius: 24px; image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges;">
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

    <!-- Pusat Informasi Section -->
    <section id="statistik" class="py-5 bg-light-gradient">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-primary-soft text-primary mb-3 px-3 py-2">
                    <i class="bi bi-journal-bookmark me-1"></i> Pusat Informasi
                </span>
                <h2 class="mb-3">Dokumen & Panduan SPMB</h2>
                <p class="text-muted mx-auto" style="max-width: 600px;">
                    Unduh dokumen penting dan pelajari panduan lengkap untuk mengikuti proses SPMB SMK Kota Padang.
                </p>
            </div>

            <div class="row g-4">
                <!-- Juknis SPMB -->
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="0">
                    <div class="card h-100 text-center border-0 shadow-sm hover-lift">
                        <div class="card-body p-4">
                            <div class="info-icon bg-primary-soft text-primary mx-auto mb-3" style="width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-file-earmark-text fs-2"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Juknis SPMB</h5>
                            <p class="text-muted small mb-3">Petunjuk teknis lengkap pelaksanaan SPMB SMK tahun ajaran <?= $tahunAjaran ?></p>
                            <a href="uploads/docs/juknis-spmb-2025.pdf" class="btn btn-outline-primary btn-sm" target="_blank">
                                <i class="bi bi-download me-1"></i> Unduh PDF
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Manual Book -->
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 text-center border-0 shadow-sm hover-lift">
                        <div class="card-body p-4">
                            <div class="info-icon bg-success-soft text-success mx-auto mb-3" style="width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-book fs-2"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Manual Book</h5>
                            <p class="text-muted small mb-3">Panduan lengkap cara mendaftar dan menggunakan sistem SPMB online</p>
                            <a href="uploads/docs/manual-book-spmb.pdf" class="btn btn-outline-success btn-sm" target="_blank">
                                <i class="bi bi-download me-1"></i> Unduh PDF
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Persyaratan Dokumen -->
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="card h-100 text-center border-0 shadow-sm hover-lift">
                        <div class="card-body p-4">
                            <div class="info-icon bg-warning-soft text-warning mx-auto mb-3" style="width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-folder-check fs-2"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Persyaratan</h5>
                            <p class="text-muted small mb-3">Daftar dokumen yang harus disiapkan untuk proses pendaftaran</p>
                            <a href="uploads/docs/persyaratan-dokumen.pdf" class="btn btn-outline-warning btn-sm" target="_blank">
                                <i class="bi bi-download me-1"></i> Unduh PDF
                            </a>
                        </div>
                    </div>
                </div>

                <!-- FAQ & Bantuan -->
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="card h-100 text-center border-0 shadow-sm hover-lift">
                        <div class="card-body p-4">
                            <div class="info-icon bg-info-soft text-info mx-auto mb-3" style="width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-question-circle fs-2"></i>
                            </div>
                            <h5 class="fw-bold mb-2">FAQ & Bantuan</h5>
                            <p class="text-muted small mb-3">Pertanyaan umum dan panduan troubleshooting pendaftaran</p>
                            <a href="#kontak" class="btn btn-outline-info btn-sm">
                                <i class="bi bi-chat-dots me-1"></i> Lihat FAQ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Penjadwalan Tahap Seleksi Section -->
    <?php
    // Jadwal tahap seleksi
    $jadwalSeleksi = [
        1 => [
            'nama' => 'Tahap 1',
            'mulai' => '2026-01-01 00:00:00',
            'selesai' => '2026-01-06 23:59:59',
            'tes' => '2026-01-08',
            'keterangan' => 'Gelombang pertama pendaftaran dan tes minat bakat',
            'icon' => 'bi-1-circle-fill',
            'color' => 'primary'
        ],
        2 => [
            'nama' => 'Tahap 2',
            'mulai' => '2026-01-07 08:00:00',
            'selesai' => '2026-01-15 23:59:59',
            'tes' => '2026-01-17',
            'keterangan' => 'Gelombang kedua untuk kuota yang tersisa',
            'icon' => 'bi-2-circle-fill',
            'color' => 'warning'
        ]
    ];
    $nowTime = date('Y-m-d H:i:s');
    ?>
    <section id="seleksi" class="py-5 bg-dark-alt">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-primary-soft text-primary mb-3 px-3 py-2">
                    <i class="bi bi-calendar-check me-1"></i> Penjadwalan Seleksi
                </span>
                <h2 class="mb-3">Jadwal Tahap Seleksi</h2>
                <p class="text-muted mx-auto" style="max-width: 600px;">
                    SPMB SMK dilaksanakan dalam 2 tahap seleksi. Pilih tahap yang sesuai dan ikuti proses pendaftaran.
                </p>
            </div>

            <div class="row g-4 justify-content-center">
                <?php foreach ($jadwalSeleksi as $tahap => $jadwal):
                    $isAktif = ($nowTime >= $jadwal['mulai'] && $nowTime <= $jadwal['selesai']);
                    $isBelum = ($nowTime < $jadwal['mulai']);
                    $isSelesai = ($nowTime > $jadwal['selesai']);
                ?>
                    <div class="col-md-6 col-lg-5" data-aos="fade-up" data-aos-delay="<?= ($tahap - 1) * 150 ?>">
                        <div class="card h-100 <?= $isAktif ? 'border-' . $jadwal['color'] . ' shadow-lg' : '' ?>" style="border-width: 2px;">
                            <div class="card-header bg-<?= $isAktif ? $jadwal['color'] : 'secondary' ?> <?= $jadwal['color'] === 'warning' && $isAktif ? 'text-dark' : 'text-white' ?>">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="bi <?= $jadwal['icon'] ?> me-2"></i><?= $jadwal['nama'] ?>
                                    </h5>
                                    <?php if ($isAktif): ?>
                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>BUKA</span>
                                    <?php elseif ($isBelum): ?>
                                        <span class="badge bg-info">Akan Datang</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Selesai</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-3 mb-3">
                                    <div class="col-12">
                                        <div class="d-flex align-items-center">
                                            <div class="stat-icon primary me-3" style="width:45px;height:45px;">
                                                <i class="bi bi-calendar-range"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">Periode Pendaftaran</small>
                                                <strong><?= date('d M', strtotime($jadwal['mulai'])) ?> - <?= date('d M Y', strtotime($jadwal['selesai'])) ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex align-items-center">
                                            <div class="stat-icon success me-3" style="width:45px;height:45px;">
                                                <i class="bi bi-pencil-square"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">Tes Minat & Bakat</small>
                                                <strong class="text-<?= $jadwal['color'] ?>"><?= date('d M Y', strtotime($jadwal['tes'])) ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-muted small mb-3"><?= $jadwal['keterangan'] ?></p>
                                <?php if ($isAktif): ?>
                                    <a href="user/pilih-tahap.php" class="btn btn-<?= $jadwal['color'] ?> w-100">
                                        <i class="bi bi-arrow-right-circle me-2"></i>Daftar Sekarang
                                    </a>
                                <?php elseif ($isBelum): ?>
                                    <button class="btn btn-outline-secondary w-100" disabled>
                                        <i class="bi bi-clock me-2"></i>Dibuka <?= date('d M Y', strtotime($jadwal['mulai'])) ?>
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-secondary w-100" disabled>
                                        <i class="bi bi-x-circle me-2"></i>Pendaftaran Ditutup
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Info Tambahan -->
            <div class="row justify-content-center mt-4" data-aos="fade-up">
                <div class="col-lg-8">
                    <div class="alert alert-info border-0" style="background: rgba(59, 130, 246, 0.1);">
                        <h6 class="mb-2"><i class="bi bi-info-circle me-2"></i>Informasi Penting</h6>
                        <ul class="mb-0 small">
                            <li>Setiap siswa hanya dapat mendaftar pada <strong>satu tahap</strong></li>
                            <li>Tahap 2 adalah kesempatan bagi yang tidak lolos atau belum mendaftar di Tahap 1</li>
                            <li>Pastikan dokumen sudah siap sebelum melakukan pendaftaran</li>
                        </ul>
                    </div>
                </div>
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
                        <li><a href="#seleksi">Jadwal Seleksi</a></li>
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

    <script src="assets/js/main.js"></script>

    <script>
        // Data SMK dari database
        const smkData = <?= $smkJson ?>;
        const radiusZonasi = <?= RADIUS_ZONASI ?>;
        let map, userMarker, userCircle;
        const schoolMarkers = [];
        let routeLines = [];
        let highlightCircles = [];

        document.addEventListener('DOMContentLoaded', function() {
            initMap();

            // Detect location button
            const btnDetect = document.getElementById('btnDetectLocation');
            const locationStatus = document.getElementById('locationStatus');
            const nearbySchools = document.getElementById('nearbySchools');

            btnDetect.addEventListener('click', function() {
                if (navigator.geolocation) {
                    this.disabled = true;
                    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mendeteksi...';
                    locationStatus.classList.remove('d-none');
                    locationStatus.className = 'alert alert-info small';
                    locationStatus.innerHTML = '<i class="bi bi-info-circle me-1"></i>Mendeteksi lokasi dan menghitung jarak...';

                    navigator.geolocation.getCurrentPosition(
                        pos => {
                            const lat = pos.coords.latitude;
                            const lng = pos.coords.longitude;
                            map.setView([lat, lng], 14);
                            addUserMarker(lat, lng);
                            updateNearestSchools(lat, lng);

                            this.disabled = false;
                            this.innerHTML = '<i class="bi bi-geo-alt-fill me-2"></i>Deteksi Lokasi Saya';
                        },
                        err => {
                            locationStatus.className = 'alert alert-danger small';
                            locationStatus.innerHTML = '<i class="bi bi-exclamation-circle me-1"></i>Gagal: ' + err.message;
                            this.disabled = false;
                            this.innerHTML = '<i class="bi bi-geo-alt-fill me-2"></i>Deteksi Lokasi Saya';
                        }
                    );
                } else {
                    alert('Geolocation tidak didukung browser Anda');
                }
            });
        });

        function initMap() {
            map = L.map('mapLeaflet').setView([-0.9471, 100.4172], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Custom school icon
            const schoolIcon = L.divIcon({
                className: 'custom-marker',
                html: '<div style="background: #10B981; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;"><i class="bi bi-building" style="font-size: 10px; color: white;"></i></div>',
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            });

            // Tambahkan marker untuk semua SMK
            smkData.forEach(smk => {
                const lat = parseFloat(smk.latitude);
                const lng = parseFloat(smk.longitude);
                if (lat && lng) {
                    const marker = L.marker([lat, lng], {
                            icon: schoolIcon
                        })
                        .addTo(map)
                        .bindPopup('<strong>' + smk.nama_sekolah + '</strong><br><small>' + (smk.alamat || '') + '</small>');
                    marker.smkData = smk;
                    schoolMarkers.push(marker);
                }
            });
        }

        function addUserMarker(lat, lng) {
            if (userMarker) map.removeLayer(userMarker);
            if (userCircle) map.removeLayer(userCircle);

            const userIcon = L.divIcon({
                className: 'custom-marker',
                html: '<div style="background: #EF4444; width: 24px; height: 24px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.4);"></div>',
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            });

            userMarker = L.marker([lat, lng], {
                    icon: userIcon
                })
                .addTo(map)
                .bindPopup('<strong>Lokasi Anda</strong>')
                .openPopup();

            userCircle = L.circle([lat, lng], {
                radius: radiusZonasi,
                color: '#10B981',
                fillOpacity: 0.1
            }).addTo(map);
        }

        // Hitung jarak darat menggunakan OSRM - SAMA PERSIS dengan pendaftaran.php
        async function getRoadDistance(lat1, lng1, lat2, lng2) {
            try {
                const url = 'https://router.project-osrm.org/route/v1/driving/' + lng1 + ',' + lat1 + ';' + lng2 + ',' + lat2 + '?overview=false';
                const response = await fetch(url);
                const data = await response.json();

                if (data.routes && data.routes[0]) {
                    return {
                        distance: data.routes[0].distance,
                        duration: data.routes[0].duration,
                        success: true
                    };
                }
            } catch (error) {
                console.log('OSRM error, using Haversine fallback');
            }

            return {
                distance: haversineDistance(lat1, lng1, lat2, lng2),
                duration: null,
                success: false
            };
        }

        // Haversine formula (fallback)
        function haversineDistance(lat1, lng1, lat2, lng2) {
            const R = 6371000;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLng = (lng2 - lng1) * Math.PI / 180;
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLng / 2) * Math.sin(dLng / 2);
            return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        }

        function formatDistance(meters) {
            if (meters >= 1000) return (meters / 1000).toFixed(2) + ' km';
            return Math.round(meters) + ' m';
        }

        function formatDuration(seconds) {
            if (!seconds) return '-';
            const minutes = Math.round(seconds / 60);
            if (minutes >= 60) {
                return Math.floor(minutes / 60) + ' jam ' + (minutes % 60) + ' mnt';
            }
            return minutes + ' menit';
        }

        function clearMapHighlights() {
            routeLines.forEach(line => map.removeLayer(line));
            routeLines = [];
            highlightCircles.forEach(circle => map.removeLayer(circle));
            highlightCircles = [];

            // Reset marker SMK ke default
            const defaultIcon = L.divIcon({
                className: 'custom-marker',
                html: '<div style="background: #10B981; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"></div>',
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            });
            schoolMarkers.forEach(m => m.setIcon(defaultIcon));
        }

        // Update daftar SMK terdekat - SAMA dengan pendaftaran.php
        async function updateNearestSchools(userLat, userLng) {
            const locationStatus = document.getElementById('locationStatus');
            const nearbySchools = document.getElementById('nearbySchools');

            nearbySchools.innerHTML = '<div class="text-center py-3"><span class="spinner-border spinner-border-sm"></span> Menghitung jarak darat...</div>';

            clearMapHighlights();

            // Hitung jarak ke semua SMK
            const distances = [];
            for (const smk of smkData) {
                const lat = parseFloat(smk.latitude);
                const lng = parseFloat(smk.longitude);
                if (lat && lng) {
                    const result = await getRoadDistance(userLat, userLng, lat, lng);
                    distances.push({
                        ...smk,
                        lat: lat,
                        lng: lng,
                        distance: result.distance,
                        duration: result.duration,
                        isRoadDistance: result.success
                    });
                }
            }

            distances.sort((a, b) => a.distance - b.distance);
            const nearest = distances.slice(0, 2);

            // Update status
            if (distances.length > 0) {
                const isRoad = distances[0].isRoadDistance;
                locationStatus.className = isRoad ? 'alert alert-success small' : 'alert alert-warning small';
                locationStatus.innerHTML = isRoad ?
                    '<i class="bi bi-car-front me-1"></i>Jarak via jalur darat (OSRM)' :
                    '<i class="bi bi-geo me-1"></i>Jarak garis lurus (fallback)';
            }

            // Highlight dan gambar garis ke 2 SMK terdekat
            await highlightNearestOnMap(userLat, userLng, nearest);

            // Render daftar SMK
            nearbySchools.innerHTML = distances.slice(0, 5).map((smk, i) => `
                <div class="d-flex align-items-center justify-content-between py-2 ${i < 4 ? 'border-bottom border-light' : ''}">
                    <div>
                        <div class="fw-semibold small">${i < 2 ? '<span class="badge bg-' + (i===0 ? 'danger' : 'warning') + ' me-1">#' + (i+1) + '</span>' : ''}${smk.nama_sekolah}</div>
                        <small class="text-muted">
                            <i class="bi bi-signpost-2 me-1"></i>${formatDistance(smk.distance)}
                            ${smk.duration ? ' <i class="bi bi-clock ms-1 me-1"></i>' + formatDuration(smk.duration) : ''}
                        </small>
                    </div>
                    <span class="badge ${smk.distance <= radiusZonasi ? 'bg-success' : 'bg-secondary'}">${smk.distance <= radiusZonasi ? 'Dalam Radius' : 'Luar Radius'}</span>
                </div>
            `).join('');

            // Fit bounds
            if (nearest.length > 0) {
                const bounds = L.latLngBounds([
                    [userLat, userLng], ...nearest.map(s => [s.lat, s.lng])
                ]);
                map.fitBounds(bounds, {
                    padding: [50, 50]
                });
            }
        }

        // Highlight dan gambar garis ke SMK terdekat - SAMA dengan pendaftaran.php
        async function highlightNearestOnMap(userLat, userLng, nearestSchools) {
            const colors = [{
                    bg: '#EF4444',
                    glow: 'rgba(239, 68, 68, 0.3)'
                },
                {
                    bg: '#F97316',
                    glow: 'rgba(249, 115, 22, 0.3)'
                }
            ];

            for (const [index, smk] of nearestSchools.entries()) {
                const color = colors[index];

                // Lingkaran glow
                const glowCircle = L.circle([smk.lat, smk.lng], {
                    radius: 150,
                    color: color.bg,
                    fillColor: color.glow,
                    fillOpacity: 0.4,
                    weight: 2
                }).addTo(map);
                highlightCircles.push(glowCircle);

                // Marker highlight
                const highlightIcon = L.divIcon({
                    className: 'highlight-marker',
                    html: '<div style="background: ' + color.bg + '; width: 32px; height: 32px; border-radius: 50%; border: 3px solid white; box-shadow: 0 4px 12px rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">' + (index + 1) + '</div>',
                    iconSize: [32, 32],
                    iconAnchor: [16, 16]
                });

                schoolMarkers.forEach(marker => {
                    if (marker.smkData && marker.smkData.id_smk === smk.id_smk) {
                        marker.setIcon(highlightIcon);
                        marker.bindPopup(
                            '<div style="text-align: center;"><div style="background: ' + color.bg + '; color: white; padding: 5px 10px; border-radius: 5px; margin-bottom: 8px;"><strong>#' + (index + 1) + ' Terdekat</strong></div>' +
                            '<strong>' + smk.nama_sekolah + '</strong><br><small>📍 ' + formatDistance(smk.distance) + '</small>' +
                            (smk.duration ? '<br><small>⏱️ ' + formatDuration(smk.duration) + '</small>' : '') + '</div>'
                        );
                    }
                });

                // Gambar garis rute
                await drawRoadRoute(userLat, userLng, smk.lat, smk.lng, color.bg, index);
            }
        }

        // Gambar garis jalur darat - SAMA dengan pendaftaran.php
        async function drawRoadRoute(lat1, lng1, lat2, lng2, lineColor, index) {
            try {
                const url = 'https://router.project-osrm.org/route/v1/driving/' + lng1 + ',' + lat1 + ';' + lng2 + ',' + lat2 + '?overview=full&geometries=geojson';
                const response = await fetch(url);
                const data = await response.json();

                if (data.routes && data.routes[0] && data.routes[0].geometry) {
                    const coords = data.routes[0].geometry.coordinates;
                    const latLngs = coords.map(c => [c[1], c[0]]);

                    const routeLine = L.polyline(latLngs, {
                        color: lineColor,
                        weight: index === 0 ? 5 : 4,
                        opacity: index === 0 ? 0.9 : 0.7,
                        lineCap: 'round',
                        lineJoin: 'round'
                    }).addTo(map);
                    routeLines.push(routeLine);
                    return;
                }
            } catch (error) {
                console.log('OSRM route error');
            }

            // Fallback garis lurus putus-putus
            const routeLine = L.polyline([
                [lat1, lng1],
                [lat2, lng2]
            ], {
                color: lineColor,
                weight: index === 0 ? 4 : 3,
                opacity: 0.7,
                dashArray: '10, 10'
            }).addTo(map);
            routeLines.push(routeLine);
        }
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