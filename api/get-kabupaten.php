<?php

/**
 * API - Get Kabupaten/Kota Sumatera Barat
 * Data dari wilayah.id API (Sumatera Barat = kode 13)
 */

header('Content-Type: application/json');

// Cache file untuk mengurangi API calls
$cacheFile = dirname(__DIR__) . '/cache/wilayah_kabupaten.json';
$cacheTime = 86400; // 24 jam

// Cek cache
if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTime) {
    echo file_get_contents($cacheFile);
    exit;
}

// Fetch dari API jika cache tidak ada atau expired
$apiUrl = 'https://wilayah.id/api/regencies/13.json';
$response = @file_get_contents($apiUrl);

if ($response) {
    $data = json_decode($response, true);

    if (isset($data['data'])) {
        $result = [
            'success' => true,
            'data' => $data['data']
        ];

        // Simpan ke cache
        if (!is_dir(dirname($cacheFile))) {
            mkdir(dirname($cacheFile), 0755, true);
        }
        file_put_contents($cacheFile, json_encode($result));

        echo json_encode($result);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid API response']);
    }
} else {
    // Fallback ke data statis jika API gagal
    $staticData = [
        ['code' => '13.71', 'name' => 'Kota Padang'],
        ['code' => '13.72', 'name' => 'Kota Solok'],
        ['code' => '13.73', 'name' => 'Kota Sawahlunto'],
        ['code' => '13.74', 'name' => 'Kota Padang Panjang'],
        ['code' => '13.75', 'name' => 'Kota Bukittinggi'],
        ['code' => '13.76', 'name' => 'Kota Payakumbuh'],
        ['code' => '13.77', 'name' => 'Kota Pariaman'],
        ['code' => '13.01', 'name' => 'Kabupaten Pesisir Selatan'],
        ['code' => '13.02', 'name' => 'Kabupaten Solok'],
        ['code' => '13.03', 'name' => 'Kabupaten Sijunjung'],
        ['code' => '13.04', 'name' => 'Kabupaten Tanah Datar'],
        ['code' => '13.05', 'name' => 'Kabupaten Padang Pariaman'],
        ['code' => '13.06', 'name' => 'Kabupaten Agam'],
        ['code' => '13.07', 'name' => 'Kabupaten Lima Puluh Kota'],
        ['code' => '13.08', 'name' => 'Kabupaten Pasaman'],
        ['code' => '13.09', 'name' => 'Kabupaten Kepulauan Mentawai'],
        ['code' => '13.10', 'name' => 'Kabupaten Dharmasraya'],
        ['code' => '13.11', 'name' => 'Kabupaten Solok Selatan'],
        ['code' => '13.12', 'name' => 'Kabupaten Pasaman Barat']
    ];

    echo json_encode(['success' => true, 'data' => $staticData, 'source' => 'static']);
}
