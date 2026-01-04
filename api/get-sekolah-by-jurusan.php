<?php

/**
 * API - Get Sekolah berdasarkan Nama Jurusan
 * Untuk mode "2 Sekolah dengan 1 Jurusan Sama"
 */

header('Content-Type: application/json');
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';

$namaJurusan = $_GET['nama'] ?? '';

if (empty($namaJurusan)) {
    echo json_encode(['success' => false, 'message' => 'Nama jurusan diperlukan']);
    exit;
}

try {
    // Ambil semua sekolah yang memiliki jurusan dengan nama yang sama
    $data = db()->fetchAll("
        SELECT 
            k.id_program,
            k.nama_kejuruan,
            k.kode_kejuruan,
            s.id_smk,
            s.nama_sekolah,
            s.alamat,
            s.kecamatan,
            COALESCE(q.kuota, 36) as kuota,
            COALESCE(q.terisi, 0) as terisi
        FROM tb_kejuruan k
        JOIN tb_smk s ON k.id_smk = s.id_smk
        LEFT JOIN tb_kuota_jurusan q ON k.id_program = q.id_kejuruan AND s.id_smk = q.id_smk
        WHERE k.nama_kejuruan = ?
        ORDER BY s.nama_sekolah
    ", [$namaJurusan]);

    echo json_encode([
        'success' => true,
        'data' => $data,
        'count' => count($data)
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Gagal mengambil data: ' . $e->getMessage()
    ]);
}
