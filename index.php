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

// Get hero settings from database
$heroTitle = getPengaturan('hero_title', 'Sistem Penerimaan Murid Baru SMK Kota Padang');
$heroSubtitle = getPengaturan('hero_subtitle', 'Selamat datang di SPMB SMK Kota Padang. Daftarkan diri Anda sekarang dan raih masa depan cerah bersama SMK terbaik di Kota Padang.');
$heroImage = getPengaturan('hero_image', 'assets/img/hero-students.png');

// Get contact settings from database
$contactPhone = getPengaturan('contact_phone', '6282112345678');
$contactEmail = getPengaturan('contact_email', 'info@smk.sch.id');
$contactAddress = getPengaturan('contact_address', 'Kota Padang, Sumatera Barat');
$socialFacebook = getPengaturan('social_facebook', '');
$socialInstagram = getPengaturan('social_instagram', '');
$socialYoutube = getPengaturan('social_youtube', '');

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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

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
    <link rel="stylesheet" href="assets/css/landing.css">
</head>

<body>

    <?php include 'includes/landing_navbar.php'; ?>

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
                        <?= htmlspecialchars($heroTitle) ?>
                    </h1>

                    <p class="hero-subtitle">
                        <?= htmlspecialchars($heroSubtitle) ?>
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
                    </div>
                </div>

                <div class="col-lg-6 text-center" data-aos="fade-left">
                    <img src="<?= htmlspecialchars($heroImage) ?>" alt="Siswa SMK" class="img-fluid hero-image"
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
            <path
                d="M0 120L48 105C96 90 192 60 288 45C384 30 480 30 576 37.5C672 45 768 60 864 67.5C960 75 1056 75 1152 67.5C1248 60 1344 45 1392 37.5L1440 30V0H1392C1344 0 1248 0 1152 0C1056 0 960 0 864 0C768 0 672 0 576 0C480 0 384 0 288 0C192 0 96 0 48 0H0V120Z"
                fill="url(#waveGradient)" />
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
                            <div class="info-icon bg-primary-soft text-primary mx-auto mb-3"
                                style="width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-file-earmark-text fs-2"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Juknis SPMB</h5>
                            <p class="text-muted small mb-3">Petunjuk teknis lengkap pelaksanaan SPMB SMK tahun ajaran
                                <?= $tahunAjaran ?>
                            </p>
                            <a href="juknis.php" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye me-1"></i> Lihat Juknis
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Manual Book -->
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 text-center border-0 shadow-sm hover-lift">
                        <div class="card-body p-4">
                            <div class="info-icon bg-success-soft text-success mx-auto mb-3"
                                style="width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-book fs-2"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Manual Book</h5>
                            <p class="text-muted small mb-3">Panduan lengkap cara mendaftar dan menggunakan sistem SPMB
                                online</p>
                            <a href="manual-book.php" class="btn btn-outline-success btn-sm">
                                <i class="bi bi-eye me-1"></i> Lihat Manual
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Persyaratan Dokumen -->
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="card h-100 text-center border-0 shadow-sm hover-lift">
                        <div class="card-body p-4">
                            <div class="info-icon bg-warning-soft text-warning mx-auto mb-3"
                                style="width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-folder-check fs-2"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Persyaratan</h5>
                            <p class="text-muted small mb-3">Daftar dokumen yang harus disiapkan untuk proses
                                pendaftaran</p>
                            <a href="persyaratan.php" class="btn btn-outline-warning btn-sm">
                                <i class="bi bi-eye me-1"></i> Lihat Syarat
                            </a>
                        </div>
                    </div>
                </div>

                <!-- FAQ & Bantuan -->
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="card h-100 text-center border-0 shadow-sm hover-lift">
                        <div class="card-body p-4">
                            <div class="info-icon bg-info-soft text-info mx-auto mb-3"
                                style="width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
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
    // Get jadwal from database
    $jadwalDb = db()->fetchAll("SELECT * FROM tb_jadwal_spmb ORDER BY urutan, tanggal_mulai LIMIT 4");

    // Build jadwal seleksi array
    $jadwalSeleksi = [];
    $colors = ['primary', 'warning', 'success', 'info'];
    $icons = ['bi-1-circle-fill', 'bi-2-circle-fill', 'bi-3-circle-fill', 'bi-4-circle-fill'];

    foreach ($jadwalDb as $index => $j) {
        $jadwalSeleksi[$index + 1] = [
            'nama' => $j['nama_kegiatan'],
            'mulai' => $j['tanggal_mulai'] . ' 00:00:00',
            'selesai' => ($j['tanggal_selesai'] ?? $j['tanggal_mulai']) . ' 23:59:59',
            'tes' => $j['tanggal_selesai'] ?? $j['tanggal_mulai'],
            'keterangan' => $j['keterangan'] ?? '',
            'icon' => $icons[$index] ?? 'bi-circle-fill',
            'color' => $colors[$index] ?? 'primary'
        ];
    }

    // Fallback if no data in database
    if (empty($jadwalSeleksi)) {
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
    }
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
                        <div class="card h-100 <?= $isAktif ? 'border-' . $jadwal['color'] . ' shadow-lg' : '' ?>"
                            style="border-width: 2px;">
                            <div
                                class="card-header bg-<?= $isAktif ? $jadwal['color'] : 'secondary' ?> <?= $jadwal['color'] === 'warning' && $isAktif ? 'text-dark' : 'text-white' ?>">
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
                                                <strong><?= date('d M', strtotime($jadwal['mulai'])) ?> -
                                                    <?= date('d M Y', strtotime($jadwal['selesai'])) ?></strong>
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
                                                <strong
                                                    class="text-<?= $jadwal['color'] ?>"><?= date('d M Y', strtotime($jadwal['tes'])) ?></strong>
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
                                <span class="badge bg-primary-soft text-primary"><i
                                        class="bi bi-geo-alt-fill me-1"></i>SMK</span>
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
                <?php foreach ($smkList as $index => $smk): ?>
                    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="<?= $index * 50 ?>">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="stat-icon primary me-3" style="width:50px;height:50px;font-size:1.25rem;">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0"><?= htmlspecialchars($smk['nama_sekolah']) ?></h6>
                                        <small
                                            class="text-muted"><?= htmlspecialchars($smk['kecamatan'] ?? 'Padang') ?></small>
                                    </div>
                                </div>
                                <p class="small text-muted mb-3">
                                    <?= htmlspecialchars(truncate($smk['alamat'] ?? '-', 80)) ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-success-soft">
                                        <i class="bi bi-people me-1"></i><?= $smk['jumlah_siswa'] ?> siswa
                                    </span>
                                    <a href="info-sekolah.php?id=<?= $smk['id_smk'] ?>"
                                        class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
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
                                    <textarea class="form-control" rows="4"
                                        placeholder="Tulis pesan Anda..."></textarea>
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
                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $contactPhone) ?>" target="_blank" class="btn btn-light btn-lg w-100">
                                <i class="bi bi-whatsapp me-2"></i>Chat Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/landing_footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script src="assets/js/main.js"></script>

    <script>
        // Pass PHP data to JS
        const smkData = <?= $smkJson ?>;
        const radiusZonasi = <?= RADIUS_ZONASI ?>;
    </script>
    <script src="assets/js/landing-map.js"></script>

</body>

</html>