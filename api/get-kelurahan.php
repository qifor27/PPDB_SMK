<?php

/**
 * API - Get Kelurahan/Desa berdasarkan Kecamatan
 * Data dari wilayah.id API
 */

header('Content-Type: application/json');

$kodeKecamatan = $_GET['kode'] ?? '';

if (empty($kodeKecamatan)) {
    echo json_encode(['success' => false, 'message' => 'Kode kecamatan diperlukan']);
    exit;
}

// Validasi format kode (harus 13.xx.xx)
if (!preg_match('/^13\.\d{2}\.\d{2}$/', $kodeKecamatan)) {
    echo json_encode(['success' => false, 'message' => 'Format kode kecamatan tidak valid']);
    exit;
}

// Cache file
$cacheDir = dirname(__DIR__) . '/cache/kelurahan/';
$cacheFile = $cacheDir . str_replace('.', '_', $kodeKecamatan) . '.json';
$cacheTime = 86400; // 24 jam

// Cek cache
if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTime) {
    echo file_get_contents($cacheFile);
    exit;
}

// Fetch dari API
$apiUrl = 'https://wilayah.id/api/villages/' . $kodeKecamatan . '.json';
$response = @file_get_contents($apiUrl);

if ($response) {
    $data = json_decode($response, true);

    if (isset($data['data'])) {
        $result = [
            'success' => true,
            'data' => $data['data']
        ];

        // Simpan ke cache
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }
        file_put_contents($cacheFile, json_encode($result));

        echo json_encode($result);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid API response']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal mengambil data kelurahan']);
}
