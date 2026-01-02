<?php

/**
 * PPDB SMK - Helper Functions
 * Common utility functions used throughout the application
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';

/**
 * Calculate distance between two coordinates using Haversine formula
 * @param float $lat1 Latitude of point 1
 * @param float $lon1 Longitude of point 1
 * @param float $lat2 Latitude of point 2
 * @param float $lon2 Longitude of point 2
 * @return float Distance in meters
 */
function calculateDistance($lat1, $lon1, $lat2, $lon2)
{
    $earthRadius = 6371000; // meters

    $lat1Rad = deg2rad($lat1);
    $lat2Rad = deg2rad($lat2);
    $deltaLat = deg2rad($lat2 - $lat1);
    $deltaLon = deg2rad($lon2 - $lon1);

    $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
        cos($lat1Rad) * cos($lat2Rad) *
        sin($deltaLon / 2) * sin($deltaLon / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return $earthRadius * $c;
}

/**
 * Format distance to readable string
 */
function formatDistance($meters)
{
    if ($meters < 1000) {
        return round($meters) . ' m';
    }
    return number_format($meters / 1000, 2, ',', '.') . ' km';
}

/**
 * Format currency to Indonesian Rupiah
 */
function formatCurrency($amount)
{
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

/**
 * Format date to Indonesian format
 */
function formatDate($date, $format = 'd F Y')
{
    $months = [
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember'
    ];

    $formatted = date($format, strtotime($date));
    return strtr($formatted, $months);
}

/**
 * Format datetime to Indonesian format
 */
function formatDateTime($datetime)
{
    return formatDate($datetime, 'd F Y H:i') . ' WIB';
}

/**
 * Generate unique registration number
 */
function generateNomorPendaftaran($jalur)
{
    $prefix = [
        'afirmasi' => 'AF',
        'prestasi' => 'PR',
        'zonasi' => 'ZN',
        'kepindahan' => 'KP'
    ];

    $year = date('Y');
    $code = $prefix[$jalur] ?? 'XX';
    $random = strtoupper(substr(md5(uniqid()), 0, 6));

    return "PPDB-{$code}-{$year}-{$random}";
}

/**
 * Validate NISN format (10 digits)
 */
function validateNISN($nisn)
{
    return preg_match('/^\d{10}$/', $nisn);
}

/**
 * Validate email format
 */
function validateEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone number (Indonesian format)
 */
function validatePhone($phone)
{
    // Remove spaces and dashes
    $phone = preg_replace('/[\s\-]/', '', $phone);
    // Check format: 08xx or +628xx
    return preg_match('/^(\+62|62|0)8[1-9][0-9]{7,10}$/', $phone);
}

/**
 * Sanitize input string
 */
function sanitize($input)
{
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Clean filename for upload
 */
function cleanFilename($filename)
{
    // Remove non-ASCII characters
    $filename = preg_replace('/[^\x20-\x7E]/', '', $filename);
    // Replace spaces with underscores
    $filename = str_replace(' ', '_', $filename);
    // Remove special characters except dots and underscores
    $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);
    return strtolower($filename);
}

/**
 * Generate unique filename for upload
 */
function generateUniqueFilename($originalName)
{
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    $basename = pathinfo($originalName, PATHINFO_FILENAME);
    $cleanBasename = cleanFilename($basename);
    $unique = uniqid();

    return "{$cleanBasename}_{$unique}.{$extension}";
}

/**
 * Check if file extension is allowed
 */
function isAllowedExtension($filename, $allowedTypes = null)
{
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $allowed = $allowedTypes ?? ALLOWED_DOC_TYPES;
    return in_array($extension, $allowed);
}

/**
 * Upload file with validation
 */
function uploadFile($file, $destination, $allowedTypes = null, $maxSize = null)
{
    $maxSize = $maxSize ?? MAX_FILE_SIZE;
    $allowedTypes = $allowedTypes ?? ALLOWED_DOC_TYPES;

    // Check for errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'File terlalu besar (melebihi batas server)',
            UPLOAD_ERR_FORM_SIZE => 'File terlalu besar (melebihi batas form)',
            UPLOAD_ERR_PARTIAL => 'File hanya terupload sebagian',
            UPLOAD_ERR_NO_FILE => 'Tidak ada file yang diupload',
            UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary tidak ditemukan',
            UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk',
            UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh ekstensi PHP'
        ];
        return ['success' => false, 'error' => $errors[$file['error']] ?? 'Error tidak diketahui'];
    }

    // Check file size
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'error' => 'Ukuran file melebihi batas maksimum (' . formatFileSize($maxSize) . ')'];
    }

    // Check extension
    if (!isAllowedExtension($file['name'], $allowedTypes)) {
        return ['success' => false, 'error' => 'Tipe file tidak diizinkan. Tipe yang diizinkan: ' . implode(', ', $allowedTypes)];
    }

    // Create destination directory if not exists
    if (!is_dir($destination)) {
        mkdir($destination, 0755, true);
    }

    // Generate unique filename
    $newFilename = generateUniqueFilename($file['name']);
    $fullPath = rtrim($destination, '/') . '/' . $newFilename;

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $fullPath)) {
        return [
            'success' => true,
            'filename' => $newFilename,
            'path' => $fullPath,
            'size' => $file['size']
        ];
    }

    return ['success' => false, 'error' => 'Gagal memindahkan file'];
}

/**
 * Format file size to readable string
 */
function formatFileSize($bytes)
{
    $units = ['B', 'KB', 'MB', 'GB'];
    $unitIndex = 0;

    while ($bytes >= 1024 && $unitIndex < count($units) - 1) {
        $bytes /= 1024;
        $unitIndex++;
    }

    return round($bytes, 2) . ' ' . $units[$unitIndex];
}

/**
 * Generate random password
 */
function generatePassword($length = 8)
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    return substr(str_shuffle($chars), 0, $length);
}

/**
 * Hash password using MD5 (for compatibility with existing data)
 * Note: Consider using password_hash() for new implementations
 */
function hashPassword($password)
{
    return md5($password);
}

/**
 * Verify password
 */
function verifyPassword($password, $hash)
{
    return md5($password) === $hash;
}

/**
 * Get status badge HTML
 */
function getStatusBadge($status)
{
    $badges = [
        'draft' => '<span class="badge bg-secondary">Draft</span>',
        'submitted' => '<span class="badge bg-info">Diajukan</span>',
        'verified' => '<span class="badge bg-primary">Terverifikasi</span>',
        'accepted' => '<span class="badge bg-success">Diterima</span>',
        'rejected' => '<span class="badge bg-danger">Ditolak</span>',
        'pending' => '<span class="badge bg-warning">Pending</span>',
        'valid' => '<span class="badge bg-success">Valid</span>',
        'invalid' => '<span class="badge bg-danger">Invalid</span>'
    ];

    return $badges[$status] ?? '<span class="badge bg-secondary">' . ucfirst($status) . '</span>';
}

/**
 * Get jalur badge HTML
 */
function getJalurBadge($jalur)
{
    $badges = [
        'afirmasi' => '<span class="badge bg-purple-soft">Afirmasi</span>',
        'prestasi' => '<span class="badge bg-warning-soft">Prestasi</span>',
        'zonasi' => '<span class="badge bg-success-soft">Zonasi</span>',
        'kepindahan' => '<span class="badge bg-info-soft">Kepindahan</span>'
    ];

    return $badges[$jalur] ?? '<span class="badge bg-secondary">' . ucfirst($jalur) . '</span>';
}

/**
 * Redirect helper
 */
function redirect($url)
{
    // Check if headers already sent
    if (headers_sent()) {
        // Use JavaScript redirect as fallback
        echo '<script>window.location.href="' . $url . '";</script>';
        echo '<noscript><meta http-equiv="refresh" content="0;url=' . $url . '"></noscript>';
        exit;
    }
    header("Location: {$url}");
    exit;
}

/**
 * Check if request is AJAX
 */
function isAjax()
{
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * Send JSON response
 */
function jsonResponse($data, $statusCode = 200)
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Get client IP address
 */
function getClientIP()
{
    $ipKeys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];

    foreach ($ipKeys as $key) {
        if (!empty($_SERVER[$key])) {
            $ip = $_SERVER[$key];
            if (strpos($ip, ',') !== false) {
                $ip = trim(explode(',', $ip)[0]);
            }
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
    }

    return 'UNKNOWN';
}

/**
 * Log activity
 */
function logActivity($action, $description = '', $userId = null)
{
    // Implement activity logging if needed
    $logData = [
        'timestamp' => date('Y-m-d H:i:s'),
        'user_id' => $userId ?? Session::getUserId(),
        'action' => $action,
        'description' => $description,
        'ip' => getClientIP(),
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
    ];

    // Log to file
    $logFile = BASE_PATH . 'logs/activity_' . date('Y-m-d') . '.log';
    $logDir = dirname($logFile);

    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    file_put_contents($logFile, json_encode($logData) . PHP_EOL, FILE_APPEND);
}

/**
 * Get pengaturan value from database
 */
function getPengaturan($key, $default = null)
{
    try {
        $result = db()->fetch(
            "SELECT value_pengaturan FROM tb_pengaturan WHERE key_pengaturan = ?",
            [$key]
        );
        return $result ? $result['value_pengaturan'] : $default;
    } catch (Exception $e) {
        return $default;
    }
}

/**
 * Check if PPDB is open
 */
function isPPDBOpen()
{
    return getPengaturan('is_open', '0') === '1';
}

/**
 * Get current tahun ajaran
 */
function getTahunAjaran()
{
    return getPengaturan('tahun_ajaran', TAHUN_AJARAN);
}

/**
 * Truncate text
 */
function truncate($text, $length = 100, $suffix = '...')
{
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . $suffix;
}

/**
 * Convert age from date
 */
function calculateAge($birthDate)
{
    $birth = new DateTime($birthDate);
    $today = new DateTime();
    $age = $today->diff($birth);
    return $age->y;
}

/**
 * Get all SMK data for map
 */
function getAllSMK()
{
    return db()->fetchAll("SELECT * FROM tb_smk ORDER BY nama_sekolah");
}

/**
 * Get SMK by ID
 */
function getSMKById($id)
{
    return db()->fetch("SELECT * FROM tb_smk WHERE id_smk = ?", [$id]);
}

/**
 * Get kejuruan by SMK ID
 */
function getKejuruanBySMK($smkId)
{
    return db()->fetchAll(
        "SELECT * FROM tb_kejuruan WHERE id_smk = ? ORDER BY nama_kejuruan",
        [$smkId]
    );
}

/**
 * Get kejuruan name by ID
 */
function getKejuruanNamaById($id)
{
    if (!$id) return '';
    $kejuruan = db()->fetch("SELECT nama_kejuruan FROM tb_kejuruan WHERE id_program = ?", [$id]);
    return $kejuruan ? $kejuruan['nama_kejuruan'] : '';
}

/**
 * Get jalur data
 */
function getAllJalur()
{
    return db()->fetchAll("SELECT * FROM tb_jalur WHERE is_active = 1 ORDER BY id_jalur");
}

/**
 * Count pendaftar by status
 */
function countPendaftarByStatus($status = null)
{
    if ($status) {
        return db()->count('tb_pendaftaran', 'status = ?', [$status]);
    }
    return db()->count('tb_pendaftaran');
}

/**
 * Count pendaftar by jalur
 */
function countPendaftarByJalur($jalurId)
{
    return db()->count('tb_pendaftaran', 'id_jalur = ?', [$jalurId]);
}

// === JALUR AFIRMASI START ===
/**
 * Get list of afirmasi document types
 * @return array
 */
function getAfirmasiDokumenTypes()
{
    return [
        'KIP' => 'Kartu Indonesia Pintar (KIP)',
        'KKS' => 'Kartu Keluarga Sejahtera (KKS)',
        'PKH' => 'Program Keluarga Harapan (PKH)',
        'KIS' => 'Kartu Indonesia Sehat (KIS)',
        'SKTM' => 'Surat Keterangan Tidak Mampu (SKTM)'
    ];
}

/**
 * Get list of jenis bantuan options
 * @return array
 */
function getJenisBantuanOptions()
{
    return [
        'KIP' => 'Kartu Indonesia Pintar (KIP)',
        'PKH' => 'Program Keluarga Harapan (PKH)',
        'KIS' => 'Kartu Indonesia Sehat (KIS)',
        'KKS' => 'Kartu Keluarga Sejahtera (KKS)',
        'LAINNYA' => 'Bantuan Lainnya'
    ];
}

/**
 * Get penghasilan range options
 * @return array
 */
function getRangePenghasilan()
{
    return [
        'dibawah_500rb' => 'Di bawah Rp 500.000',
        '500rb_1jt' => 'Rp 500.000 - Rp 1.000.000',
        '1jt_2jt' => 'Rp 1.000.000 - Rp 2.000.000',
        '2jt_3jt' => 'Rp 2.000.000 - Rp 3.000.000',
        'diatas_3jt' => 'Di atas Rp 3.000.000'
    ];
}

/**
 * Validate afirmasi dokumen completeness
 * @param int $pendaftaranId
 * @return array
 */
function validateAfirmasiDokumen($pendaftaranId)
{
    $afirmasiDocs = ['Kartu Indonesia Pintar (KIP)/PKH/KIS', 'SKTM dari Kelurahan'];

    $uploadedDocs = db()->fetchAll(
        "SELECT jenis_dokumen FROM tb_dokumen WHERE id_pendaftaran = ?",
        [$pendaftaranId]
    );

    $uploadedTypes = array_column($uploadedDocs, 'jenis_dokumen');

    $hasKIPorPKH = false;
    $hasSKTM = false;

    foreach ($uploadedTypes as $type) {
        if (stripos($type, 'KIP') !== false || stripos($type, 'PKH') !== false || stripos($type, 'KIS') !== false) {
            $hasKIPorPKH = true;
        }
        if (stripos($type, 'SKTM') !== false) {
            $hasSKTM = true;
        }
    }

    return [
        'is_complete' => $hasKIPorPKH || $hasSKTM,
        'has_kip_pkh_kis' => $hasKIPorPKH,
        'has_sktm' => $hasSKTM,
        'uploaded_docs' => $uploadedTypes,
        'message' => (!$hasKIPorPKH && !$hasSKTM)
            ? 'Minimal upload 1 dokumen bantuan (KIP/PKH/KIS atau SKTM)'
            : 'Dokumen afirmasi sudah dilengkapi'
    ];
}

/**
 * Get afirmasi status for pendaftaran
 * @param int $pendaftaranId
 * @return array
 */
function getAfirmasiStatus($pendaftaranId)
{
    $dokumenStatus = validateAfirmasiDokumen($pendaftaranId);

    // Get verified docs count
    $verifiedCount = db()->count(
        'tb_dokumen',
        'id_pendaftaran = ? AND status_verifikasi = ?',
        [$pendaftaranId, 'valid']
    );

    $totalCount = db()->count(
        'tb_dokumen',
        'id_pendaftaran = ?',
        [$pendaftaranId]
    );

    return [
        'dokumen_complete' => $dokumenStatus['is_complete'],
        'dokumen_message' => $dokumenStatus['message'],
        'verified_count' => $verifiedCount,
        'total_count' => $totalCount,
        'all_verified' => ($totalCount > 0 && $verifiedCount === $totalCount)
    ];
}

/**
 * Get count of afirmasi pendaftar
 * @param int|null $smkId
 * @return int
 */
function countAfirmasiPendaftar($smkId = null)
{
    $sql = "SELECT COUNT(*) as total FROM tb_pendaftaran p 
            JOIN tb_jalur j ON p.id_jalur = j.id_jalur 
            WHERE j.kode_jalur = 'afirmasi'";
    $params = [];

    if ($smkId) {
        $sql .= " AND p.id_smk_pilihan1 = ?";
        $params[] = $smkId;
    }

    $result = db()->fetch($sql, $params);
    return $result ? (int) $result['total'] : 0;
}

/**
 * Get afirmasi verification checklist
 * @return array
 */
function getAfirmasiVerificationChecklist()
{
    return [
        'KIP/KKS' => [
            'Nomor kartu terlihat jelas',
            'Nama sesuai dengan data siswa',
            'Kartu masih berlaku (tidak expired)',
            'Foto kartu tidak blur/rusak'
        ],
        'PKH' => [
            'SK PKH dari Kemensos',
            'Nama kepala keluarga/anggota tertera',
            'Tahun anggaran masih berlaku',
            'Stempel/tanda tangan resmi ada'
        ],
        'KIS' => [
            'Nomor kartu BPJS Kesehatan PBI',
            'Nama peserta tertera',
            'Status kepesertaan aktif',
            'Kelas rawat sesuai PBI'
        ],
        'SKTM' => [
            'Dikeluarkan oleh Kelurahan/Desa',
            'Stempel dan tanda tangan Lurah/Kades',
            'Tanggal penerbitan tidak lebih dari 6 bulan',
            'Nama dan alamat sesuai KK'
        ]
    ];
}
// === JALUR AFIRMASI END ===

// === PRESTASI FUNCTIONS START ===
/**
 * Get prestasi list by pendaftaran ID
 */
function getPrestasiByPendaftaran($pendaftaranId)
{
    return db()->fetchAll(
        "SELECT * FROM tb_prestasi_siswa WHERE id_pendaftaran = ? ORDER BY tingkat DESC, poin DESC",
        [$pendaftaranId]
    );
}

/**
 * Get total prestasi poin (only valid)
 */
function getTotalPrestasiPoin($pendaftaranId)
{
    $result = db()->fetch(
        "SELECT COALESCE(SUM(poin), 0) as total FROM tb_prestasi_siswa 
         WHERE id_pendaftaran = ? AND status_verifikasi = 'valid'",
        [$pendaftaranId]
    );
    return (int)($result['total'] ?? 0);
}
// === PRESTASI FUNCTIONS END ===
