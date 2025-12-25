<?php
/**
 * PPDB SMK - Konfigurasi Utama
 * Sistem Penerimaan Peserta Didik Baru SMK
 */

// Prevent direct access
if (!defined('PPDB_SMK')) {
    define('PPDB_SMK', true);
}

// Error Reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'dbesemka');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Site Configuration
define('SITE_NAME', 'SPMB SMK Kota Padang');
define('SITE_DESCRIPTION', 'Sistem Penerimaan Murid Baru SMK Kota Padang');
define('SITE_URL', 'http://localhost/PPDB_SMK');
define('SITE_EMAIL', 'spmb@smkpadang.id');

// Path Configuration
define('BASE_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('CONFIG_PATH', BASE_PATH . 'config' . DIRECTORY_SEPARATOR);
define('ASSETS_PATH', BASE_PATH . 'assets' . DIRECTORY_SEPARATOR);
define('UPLOADS_PATH', BASE_PATH . 'uploads' . DIRECTORY_SEPARATOR);

// URL Configuration
define('ASSETS_URL', SITE_URL . '/assets');
define('UPLOADS_URL', SITE_URL . '/uploads');

// Session Configuration
define('SESSION_NAME', 'PPDB_SMK_SESSION');
define('SESSION_LIFETIME', 7200); // 2 hours

// Upload Configuration
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif']);
define('ALLOWED_DOC_TYPES', ['pdf', 'jpg', 'jpeg', 'png']);

// PPDB Settings
define('TAHUN_AJARAN', '2025/2026');
define('RADIUS_ZONASI', 3000); // dalam meter

// Jalur Pendaftaran
define('JALUR_AFIRMASI', 'afirmasi');
define('JALUR_PRESTASI', 'prestasi');
define('JALUR_ZONASI', 'zonasi');
define('JALUR_KEPINDAHAN', 'kepindahan');

// Kuota Default (dalam persen)
define('KUOTA_AFIRMASI', 15);
define('KUOTA_PRESTASI', 25);
define('KUOTA_ZONASI', 50);
define('KUOTA_KEPINDAHAN', 10);

// Status Pendaftaran
define('STATUS_DRAFT', 'draft');
define('STATUS_SUBMITTED', 'submitted');
define('STATUS_VERIFIED', 'verified');
define('STATUS_ACCEPTED', 'accepted');
define('STATUS_REJECTED', 'rejected');

// User Roles
define('ROLE_SUPERADMIN', 'superadmin');
define('ROLE_ADMIN', 'admin');
define('ROLE_SISWA', 'siswa');

// Point Prestasi
$PRESTASI_POINTS = [
    'Internasional' => [
        'Juara 1' => 100,
        'Juara 2' => 90,
        'Juara 3' => 80,
        'Peserta' => 50
    ],
    'Nasional' => [
        'Juara 1' => 80,
        'Juara 2' => 70,
        'Juara 3' => 60,
        'Peserta' => 30
    ],
    'Provinsi' => [
        'Juara 1' => 60,
        'Juara 2' => 50,
        'Juara 3' => 40,
        'Peserta' => 20
    ],
    'Kota/Kabupaten' => [
        'Juara 1' => 40,
        'Juara 2' => 30,
        'Juara 3' => 20,
        'Peserta' => 10
    ]
];
