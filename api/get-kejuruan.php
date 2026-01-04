<?php

/**
 * API - Get Kejuruan/Jurusan per SMK
 * Returns list of jurusan with kuota and requirements
 */

header('Content-Type: application/json');

require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/config/functions.php';

// Support both id_smk and smk_id parameters
$idSmk = (int)($_GET['id_smk'] ?? $_GET['smk_id'] ?? 0);

if (!$idSmk) {
    echo json_encode(['success' => false, 'message' => 'ID SMK diperlukan']);
    exit;
}

try {
    $kejuruan = db()->fetchAll("
        SELECT k.id_program, k.nama_kejuruan, k.kode_kejuruan, k.deskripsi,
               COALESCE(kj.kuota, 36) as kuota,
               COALESCE(kj.terisi, 0) as terisi
        FROM tb_kejuruan k
        LEFT JOIN tb_kuota_jurusan kj ON k.id_program = kj.id_kejuruan AND kj.tahun_ajaran = '2025/2026'
        WHERE k.id_smk = ?
        ORDER BY k.nama_kejuruan
    ", [$idSmk]);

    echo json_encode([
        'success' => true,
        'data' => $kejuruan
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ]);
}
