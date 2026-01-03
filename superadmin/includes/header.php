<?php

/**
 * Super Admin Dashboard - Header Include
 */

require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/config/functions.php';
require_once dirname(__DIR__, 2) . '/config/session.php';

// Require superadmin login
Session::requireRole(ROLE_SUPERADMIN, SITE_URL . '/login.php');

$currentUser = Session::getUserData();
$superadminId = Session::getUserId();

// Get superadmin data
$superadmin = db()->fetch("SELECT * FROM tb_superadmin WHERE id_superadmin = ?", [$superadminId]);

// Global stats
$totalSMK = db()->count('tb_smk');
$totalPendaftar = db()->count('tb_pendaftaran');
$totalAdminSekolah = db()->count('tb_admin_sekolah');

$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$currentDir = basename(dirname($_SERVER['PHP_SELF']));
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Dashboard' ?> - Super Admin PPDB SMK</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
</head>

<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="<?= SITE_URL ?>" class="sidebar-brand">
                <i class="bi bi-mortarboard-fill text-primary"></i>
                PPDB SMK
            </a>
        </div>

        <div class="px-3 py-2 border-bottom border-subtle">
            <small class="text-muted">Super Administrator</small>
            <div class="fw-semibold text-truncate"><?= htmlspecialchars($superadmin['nama_lengkap']) ?></div>
        </div>

        <nav class="sidebar-menu">
            <div class="sidebar-menu-label">Menu Utama</div>

            <a href="<?= SITE_URL ?>/superadmin/index.php" class="sidebar-link <?= $currentPage === 'index' && $currentDir === 'superadmin' ? 'active' : '' ?>">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>

            <div class="sidebar-menu-label">Data Master</div>

            <a href="<?= SITE_URL ?>/superadmin/sekolah.php" class="sidebar-link <?= $currentPage === 'sekolah' ? 'active' : '' ?>">
                <i class="bi bi-building"></i>
                <span>Data SMK</span>
            </a>

            <a href="<?= SITE_URL ?>/superadmin/admin-sekolah.php" class="sidebar-link <?= $currentPage === 'admin-sekolah' ? 'active' : '' ?>">
                <i class="bi bi-person-badge"></i>
                <span>Admin Sekolah</span>
            </a>

            <a href="<?= SITE_URL ?>/superadmin/jalur.php" class="sidebar-link <?= $currentPage === 'jalur' ? 'active' : '' ?>">
                <i class="bi bi-calendar-check"></i>
                <span>Tahap Seleksi</span>
            </a>

            <div class="sidebar-menu-label">Pendaftaran</div>

            <a href="<?= SITE_URL ?>/superadmin/pendaftar.php" class="sidebar-link <?= $currentPage === 'pendaftar' ? 'active' : '' ?>">
                <i class="bi bi-people-fill"></i>
                <span>Semua Pendaftar</span>
            </a>

            <a href="<?= SITE_URL ?>/superadmin/kuota.php" class="sidebar-link <?= $currentPage === 'kuota' ? 'active' : '' ?>">
                <i class="bi bi-pie-chart"></i>
                <span>Kuota Global</span>
            </a>

            <div class="sidebar-menu-label">Pengaturan</div>

            <a href="<?= SITE_URL ?>/superadmin/pengaturan.php" class="sidebar-link <?= $currentPage === 'pengaturan' ? 'active' : '' ?>">
                <i class="bi bi-gear"></i>
                <span>Pengaturan Sistem</span>
            </a>

            <a href="<?= SITE_URL ?>/superadmin/laporan.php" class="sidebar-link <?= $currentPage === 'laporan' ? 'active' : '' ?>">
                <i class="bi bi-file-earmark-bar-graph"></i>
                <span>Laporan</span>
            </a>

            <div class="sidebar-menu-label">Akun</div>

            <a href="<?= SITE_URL ?>/superadmin/profil.php" class="sidebar-link <?= $currentPage === 'profil' ? 'active' : '' ?>">
                <i class="bi bi-person-fill"></i>
                <span>Profil Saya</span>
            </a>

            <a href="<?= SITE_URL ?>" class="sidebar-link">
                <i class="bi bi-house"></i>
                <span>Lihat Website</span>
            </a>

            <a href="<?= SITE_URL ?>/logout.php" class="sidebar-link text-danger">
                <i class="bi bi-box-arrow-left"></i>
                <span>Keluar</span>
            </a>
        </nav>
    </aside>

    <main class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <button class="btn btn-outline-secondary sidebar-toggle d-lg-none me-2"><i class="bi bi-list"></i></button>
                <h4 class="mb-0 d-inline"><?= $pageTitle ?? 'Dashboard' ?></h4>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-md-block">
                    <div class="fw-semibold"><?= htmlspecialchars($superadmin['nama_lengkap']) ?></div>
                    <small class="text-muted">Super Admin</small>
                </div>
                <div class="dropdown">
                    <button class="btn btn-primary rounded-circle" data-bs-toggle="dropdown" style="width:45px;height:45px;">
                        <i class="bi bi-shield-check"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item text-danger" href="<?= SITE_URL ?>/logout.php"><i class="bi bi-box-arrow-left me-2"></i>Keluar</a></li>
                    </ul>
                </div>
            </div>
        </div>

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