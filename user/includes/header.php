<?php

/**
 * User Dashboard - Header Include
 */
ob_start(); // Start output buffering to prevent headers already sent warning

require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/config/functions.php';
require_once dirname(__DIR__, 2) . '/config/session.php';

// Require login
Session::requireRole(ROLE_SISWA, SITE_URL . '/login.php');

// Get current user data
$currentUser = Session::getUserData();
$userId = Session::getUserId();

// Get siswa data
$siswa = db()->fetch("SELECT * FROM tb_siswa WHERE id_siswa = ?", [$userId]);

// Get pendaftaran if exists
$pendaftaran = db()->fetch(
    "SELECT p.*, j.nama_jalur, j.kode_jalur, 
            s1.nama_sekolah as sekolah_pilihan1, s2.nama_sekolah as sekolah_pilihan2,
            k1.nama_kejuruan as kejuruan_pilihan1, k2.nama_kejuruan as kejuruan_pilihan2
     FROM tb_pendaftaran p
     LEFT JOIN tb_jalur j ON p.id_jalur = j.id_jalur
     LEFT JOIN tb_smk s1 ON p.id_smk_pilihan1 = s1.id_smk
     LEFT JOIN tb_smk s2 ON p.id_smk_pilihan2 = s2.id_smk
     LEFT JOIN tb_kejuruan k1 ON p.id_kejuruan_pilihan1 = k1.id_program
     LEFT JOIN tb_kejuruan k2 ON p.id_kejuruan_pilihan2 = k2.id_program
     WHERE p.id_siswa = ?
     ORDER BY p.id_pendaftaran DESC LIMIT 1",
    [$userId]
);

// Get current page
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Dashboard' ?> - <?= SITE_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
</head>

<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="<?= SITE_URL ?>" class="sidebar-brand">
                <i class="bi bi-mortarboard-fill text-primary"></i>
                PPDB SMK
            </a>
        </div>

        <nav class="sidebar-menu">
            <div class="sidebar-menu-label">Menu Utama</div>

            <a href="index.php" class="sidebar-link <?= $currentPage === 'index' ? 'active' : '' ?>">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>

            <a href="profil.php" class="sidebar-link <?= $currentPage === 'profil' ? 'active' : '' ?>">
                <i class="bi bi-person-fill"></i>
                <span>Profil Saya</span>
            </a>

            <div class="sidebar-menu-label">Pendaftaran</div>

            <?php if (!$pendaftaran): ?>
                <a href="pilih-sekolah-smk.php" class="sidebar-link <?= $currentPage === 'pilih-sekolah-smk' ? 'active' : '' ?>">
                    <i class="bi bi-building"></i>
                    <span>Pilih Sekolah</span>
                </a>
            <?php else: ?>
                <a href="hasil-pendaftaran.php" class="sidebar-link <?= $currentPage === 'hasil-pendaftaran' ? 'active' : '' ?>">
                    <i class="bi bi-card-checklist"></i>
                    <span>Hasil Pendaftaran</span>
                </a>
                <a href="input-rapor.php" class="sidebar-link <?= $currentPage === 'input-rapor' ? 'active' : '' ?>">
                    <i class="bi bi-journal-text"></i>
                    <span>Nilai Rapor</span>
                </a>
                <a href="dokumen.php" class="sidebar-link <?= $currentPage === 'dokumen' ? 'active' : '' ?>">
                    <i class="bi bi-folder-fill"></i>
                    <span>Upload Dokumen</span>
                </a>
                <a href="prestasi.php" class="sidebar-link <?= $currentPage === 'prestasi' ? 'active' : '' ?>">
                    <i class="bi bi-trophy-fill"></i>
                    <span>Data Prestasi</span>
                </a>
                <a href="jadwal-tes.php" class="sidebar-link <?= $currentPage === 'jadwal-tes' ? 'active' : '' ?>">
                    <i class="bi bi-calendar-check"></i>
                    <span>Jadwal Tes</span>
                </a>
                <a href="status.php" class="sidebar-link <?= $currentPage === 'status' ? 'active' : '' ?>">
                    <i class="bi bi-clock-history"></i>
                    <span>Status Pendaftaran</span>
                </a>
                <a href="pembatalan.php" class="sidebar-link <?= $currentPage === 'pembatalan' ? 'active' : '' ?>">
                    <i class="bi bi-x-circle"></i>
                    <span>Pembatalan</span>
                </a>
            <?php endif; ?>

            <div class="sidebar-menu-label">Lainnya</div>

            <a href="<?= SITE_URL ?>" class="sidebar-link">
                <i class="bi bi-house-fill"></i>
                <span>Beranda</span>
            </a>

            <a href="<?= SITE_URL ?>/logout.php" class="sidebar-link text-danger">
                <i class="bi bi-box-arrow-left"></i>
                <span>Keluar</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Bar -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <button class="btn btn-dark sidebar-toggle d-lg-none me-2">
                    <i class="bi bi-list"></i>
                </button>
                <h4 class="mb-0 d-inline"><?= $pageTitle ?? 'Dashboard' ?></h4>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-md-block">
                    <div class="fw-semibold"><?= htmlspecialchars($siswa['nama_lengkap']) ?></div>
                    <small class="text-muted">NISN: <?= $siswa['nisn'] ?></small>
                </div>
                <div class="dropdown">
                    <?php
                    $fotoUrl = !empty($siswa['foto']) && file_exists(UPLOADS_PATH . 'foto/' . $siswa['foto'])
                        ? UPLOADS_URL . 'foto/' . $siswa['foto']
                        : null;
                    ?>
                    <button class="btn rounded-circle p-0 overflow-hidden" data-bs-toggle="dropdown"
                        style="width:45px;height:45px;border:2px solid #667eea;">
                        <?php if ($fotoUrl): ?>
                            <img src="<?= $fotoUrl ?>" alt="Foto" class="w-100 h-100" style="object-fit:cover;">
                        <?php else: ?>
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-primary text-white fw-bold">
                                <?= strtoupper(substr($siswa['nama_lengkap'], 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="profil.php"><i class="bi bi-person me-2"></i>Profil</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="<?= SITE_URL ?>/logout.php"><i class="bi bi-box-arrow-left me-2"></i>Keluar</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        <?php if ($flash = Session::getFlash('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i><?= $flash ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($flash = Session::getFlash('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-circle me-2"></i><?= $flash ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>