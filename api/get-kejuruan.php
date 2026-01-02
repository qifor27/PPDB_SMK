<?php

/**
 * API - Get Kejuruan/Jurusan per SMK
 * Returns list of jurusan with kuota and requirements
 */

header('Content-Type: application/json');

require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/config/functions.php';

$idSmk = (int)($_GET['id_smk'] ?? 0);

if (!$idSmk) {
    echo json_encode(['success' => false, 'message' => 'ID SMK diperlukan']);
    exit;
}

try {
    $kejuruan = db()->fetchAll("
        SELECT k.*, 
               COALESCE(kk.kuota_afirmasi + kk.kuota_domisili + kk.kuota_prestasi + kk.kuota_nilai_akhir, k.kuota, 36) as kuota,
               COALESCE(kk.terisi_afirmasi + kk.terisi_domisili + kk.terisi_prestasi + kk.terisi_nilai_akhir, k.kuota_terisi, 0) as terisi
        FROM tb_kejuruan k
        LEFT JOIN tb_kuota_kejuruan kk ON k.id_program = kk.id_kejuruan AND kk.tahun_ajaran = ?
        WHERE k.id_smk = ?
        ORDER BY k.nama_kejuruan
    ", [getPengaturan('tahun_ajaran'), $idSmk]);

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
