<?php

/**
 * API - Auto Save Pilihan Sekolah
 * Menyimpan pilihan sekolah dan jurusan secara real-time
 */

require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/config/functions.php';
require_once dirname(__DIR__) . '/config/session.php';
require_once dirname(__DIR__) . '/config/scoring.php';

header('Content-Type: application/json');

// Require login
if (!Session::isLoggedIn() || Session::getRole() !== ROLE_SISWA) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$userId = Session::getUserId();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$pilihanMode = sanitize($input['pilihan_mode'] ?? 'satu_sekolah_dua_jurusan');
$sekolah1 = (int)($input['sekolah_pilihan1'] ?? 0);
$kejuruan1 = (int)($input['kejuruan_pilihan1'] ?? 0);
$sekolah2 = (int)($input['sekolah_pilihan2'] ?? 0);
$kejuruan2 = (int)($input['kejuruan_pilihan2'] ?? 0);

try {
    // Cek apakah sudah ada pendaftaran
    $pendaftaran = db()->fetch(
        "SELECT * FROM tb_pendaftaran WHERE id_siswa = ? ORDER BY id_pendaftaran DESC LIMIT 1",
        [$userId]
    );

    $data = [
        'id_smk_pilihan1' => $sekolah1 ?: null,
        'id_smk_pilihan2' => $sekolah2 ?: null,
        'id_kejuruan_pilihan1' => $kejuruan1 ?: null,
        'id_kejuruan_pilihan2' => $kejuruan2 ?: null,
        'pilihan_mode' => $pilihanMode
    ];

    if ($pendaftaran) {
        // Update existing
        db()->update('tb_pendaftaran', $data, 'id_pendaftaran = :id', ['id' => $pendaftaran['id_pendaftaran']]);
        $idPendaftaran = $pendaftaran['id_pendaftaran'];
    } else {
        // Create new
        $nomorPendaftaran = generateNomorPendaftaranSMK($userId, 1);
        $data['nomor_pendaftaran'] = $nomorPendaftaran;
        $data['id_siswa'] = $userId;
        $data['id_jalur'] = 1; // Default
        $data['tahun_ajaran'] = getPengaturan('tahun_ajaran');
        $data['status'] = 'draft';

        $idPendaftaran = db()->insert('tb_pendaftaran', $data);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Pilihan berhasil disimpan',
        'id_pendaftaran' => $idPendaftaran
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Gagal menyimpan: ' . $e->getMessage()
    ]);
}
