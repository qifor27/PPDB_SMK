<?php

/**
 * API - Get Kecamatan berdasarkan Kabupaten/Kota
 * Data dari wilayah.id API
 */

header('Content-Type: application/json');

$kodeKabupaten = $_GET['kode'] ?? '';

if (empty($kodeKabupaten)) {
    echo json_encode(['success' => false, 'message' => 'Kode kabupaten diperlukan']);
    exit;
}

// Validasi format kode (harus 13.xx)
if (!preg_match('/^13\.\d{2}$/', $kodeKabupaten)) {
    echo json_encode(['success' => false, 'message' => 'Format kode kabupaten tidak valid']);
    exit;
}

// Cache file
$cacheDir = dirname(__DIR__) . '/cache/kecamatan/';
$cacheFile = $cacheDir . str_replace('.', '_', $kodeKabupaten) . '.json';
$cacheTime = 86400; // 24 jam

// Cek cache
if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTime) {
    echo file_get_contents($cacheFile);
    exit;
}

// Fetch dari API
$apiUrl = 'https://wilayah.id/api/districts/' . $kodeKabupaten . '.json';
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
    echo json_encode(['success' => false, 'message' => 'Gagal mengambil data kecamatan']);
}
