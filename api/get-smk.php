<?php
/**
 * API - Get SMK Data for Map
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/config/functions.php';

$smkList = db()->fetchAll(
    "SELECT id_smk, npsn, nama_sekolah, alamat, kelurahan, kecamatan, 
            latitude, longitude, jumlah_siswa, jumlah_guru, nama_kepsek
     FROM tb_smk ORDER BY nama_sekolah"
);

// Get kejuruan for each SMK
foreach ($smkList as &$smk) {
    $smk['kejuruan'] = db()->fetchAll(
        "SELECT nama_kejuruan, kode_kejuruan FROM tb_kejuruan WHERE id_smk = ?",
        [$smk['id_smk']]
    );
}

echo json_encode([
    'success' => true,
    'data' => $smkList
]);
