<?php
/**
 * Admin Sekolah Dashboard - Header Include
 */

require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/config/functions.php';
require_once dirname(__DIR__, 2) . '/config/session.php';

// Require admin login
Session::requireRole(ROLE_ADMIN, SITE_URL . '/login.php');

$currentUser = Session::getUserData();
$adminId = Session::getUserId();

// Get admin data with school
$admin = db()->fetch(
    "SELECT a.*, s.nama_sekolah, s.id_smk FROM tb_admin_sekolah a 
     LEFT JOIN tb_smk s ON a.id_smk = s.id_smk 
     WHERE a.id_admin_sekolah = ?",
    [$adminId]
);
$smkId = $admin['id_smk'];

// Get stats for this school
$totalPendaftar = db()->count('tb_pendaftaran', 'id_smk_pilihan1 = ?', [$smkId]);
$pendingVerif = db()->count('tb_pendaftaran', 'id_smk_pilihan1 = ? AND status = ?', [$smkId, 'submitted']);

$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Dashboard' ?> - Admin <?= htmlspecialchars($admin['nama_sekolah']) ?></title>
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
            <small class="text-muted">Admin Sekolah</small>
            <div class="fw-semibold text-truncate"><?= htmlspecialchars($admin['nama_sekolah']) ?></div>
        </div>
        
        <nav class="sidebar-menu">
            <div class="sidebar-menu-label">Menu Utama</div>
            
            <a href="index.php" class="sidebar-link <?= $currentPage === 'index' ? 'active' : '' ?>">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>
            
            <div class="sidebar-menu-label">Pendaftaran</div>
            
            <a href="pendaftar.php" class="sidebar-link <?= $currentPage === 'pendaftar' ? 'active' : '' ?>">
                <i class="bi bi-people-fill"></i>
                <span>Data Pendaftar</span>
                <?php if ($pendingVerif > 0): ?>
                <span class="badge bg-danger ms-auto"><?= $pendingVerif ?></span>
                <?php endif; ?>
            </a>
            
            <a href="verifikasi.php" class="sidebar-link <?= $currentPage === 'verifikasi' ? 'active' : '' ?>">
                <i class="bi bi-check2-square"></i>
                <span>Verifikasi Dokumen</span>
            </a>
            
            <a href="seleksi.php" class="sidebar-link <?= $currentPage === 'seleksi' ? 'active' : '' ?>">
                <i class="bi bi-funnel-fill"></i>
                <span>Seleksi & Ranking</span>
            </a>
            
            <div class="sidebar-menu-label">Data Sekolah</div>
            
            <a href="kuota.php" class="sidebar-link <?= $currentPage === 'kuota' ? 'active' : '' ?>">
                <i class="bi bi-pie-chart-fill"></i>
                <span>Kuota Jalur</span>
            </a>
            
            <a href="laporan.php" class="sidebar-link <?= $currentPage === 'laporan' ? 'active' : '' ?>">
                <i class="bi bi-file-earmark-bar-graph"></i>
                <span>Laporan</span>
            </a>
            
            <div class="sidebar-menu-label">Akun</div>
            
            <a href="profil.php" class="sidebar-link <?= $currentPage === 'profil' ? 'active' : '' ?>">
                <i class="bi bi-person-fill"></i>
                <span>Profil Saya</span>
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
                <button class="btn btn-dark sidebar-toggle d-lg-none me-2"><i class="bi bi-list"></i></button>
                <h4 class="mb-0 d-inline"><?= $pageTitle ?? 'Dashboard' ?></h4>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-md-block">
                    <div class="fw-semibold"><?= htmlspecialchars($admin['nama_lengkap']) ?></div>
                    <small class="text-muted">Admin Sekolah</small>
                </div>
                <div class="dropdown">
                    <button class="btn btn-dark rounded-circle" data-bs-toggle="dropdown" style="width:45px;height:45px;">
                        <i class="bi bi-person-fill"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="profil.php"><i class="bi bi-person me-2"></i>Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
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
