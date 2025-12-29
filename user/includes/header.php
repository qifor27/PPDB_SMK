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
    "SELECT p.*, j.nama_jalur, j.kode_jalur, s1.nama_sekolah as sekolah_pilihan1, s2.nama_sekolah as sekolah_pilihan2
     FROM tb_pendaftaran p
     LEFT JOIN tb_jalur j ON p.id_jalur = j.id_jalur
     LEFT JOIN tb_smk s1 ON p.id_smk_pilihan1 = s1.id_smk
     LEFT JOIN tb_smk s2 ON p.id_smk_pilihan2 = s2.id_smk
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
                <a href="pilih-jalur.php" class="sidebar-link <?= $currentPage === 'pilih-jalur' ? 'active' : '' ?>">
                    <i class="bi bi-signpost-split-fill"></i>
                    <span>Pilih Jalur</span>
                </a>
            <?php else: ?>
                <a href="pendaftaran.php" class="sidebar-link <?= $currentPage === 'pendaftaran' ? 'active' : '' ?>">
                    <i class="bi bi-file-earmark-text-fill"></i>
                    <span>Form Pendaftaran</span>
                </a>
                <a href="dokumen.php" class="sidebar-link <?= $currentPage === 'dokumen' ? 'active' : '' ?>">
                    <i class="bi bi-folder-fill"></i>
                    <span>Upload Dokumen</span>
                </a>
                <?php if ($pendaftaran['kode_jalur'] === 'prestasi'): ?>
                    <a href="prestasi.php" class="sidebar-link <?= $currentPage === 'prestasi' ? 'active' : '' ?>">
                        <i class="bi bi-trophy-fill"></i>
                        <span>Data Prestasi</span>
                    </a>
                <?php endif; ?>
                <a href="status.php" class="sidebar-link <?= $currentPage === 'status' ? 'active' : '' ?>">
                    <i class="bi bi-clock-history"></i>
                    <span>Status Pendaftaran</span>
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
                    <button class="btn btn-dark rounded-circle" data-bs-toggle="dropdown"
                        style="width:45px;height:45px;">
                        <i class="bi bi-person-fill"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="profil.php"><i class="bi bi-person me-2"></i>Profil</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="<?= SITE_URL ?>/logout.php"><i
                                    class="bi bi-box-arrow-left me-2"></i>Keluar</a></li>
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