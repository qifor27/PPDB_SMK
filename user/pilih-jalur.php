<?php

/**
 * User - Pilih Jalur Pendaftaran
 * DEPRECATED: Redirect ke pilih-sekolah-smk.php untuk alur SPMB 2025/2026
 * 
 * Halaman ini tidak lagi digunakan karena SMK menggunakan alur seleksi baru
 * yang langsung memilih sekolah dan jurusan tanpa jalur terpisah.
 */

require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/config/functions.php';
require_once dirname(__DIR__) . '/config/session.php';

// Redirect ke halaman pilih sekolah SMK baru
redirect(SITE_URL . '/user/pilih-sekolah-smk.php');
exit;
