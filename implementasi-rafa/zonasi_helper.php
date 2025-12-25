<?php
/**
 * RAFA - Helper Functions untuk Jalur Zonasi
 * Kumpulan fungsi yang digunakan untuk fitur zonasi
 * 
 * UPDATED: Menggunakan perhitungan jarak DARAT (road distance)
 * Bukan jarak udara (straight line)
 */

// Konfigurasi API (pilih salah satu)
define('ROUTING_API', 'osrm'); // Pilihan: 'osrm', 'openrouteservice'
define('OPENROUTESERVICE_API_KEY', ''); // Isi jika menggunakan OpenRouteService

/**
 * ========================================
 * FUNGSI UTAMA: PERHITUNGAN JARAK DARAT
 * ========================================
 */

/**
 * Menghitung jarak DARAT antara dua titik koordinat
 * Menggunakan routing API untuk jalur jalan raya
 * 
 * @param float $lat1 Latitude titik 1 (siswa)
 * @param float $lon1 Longitude titik 1 (siswa)
 * @param float $lat2 Latitude titik 2 (sekolah)
 * @param float $lon2 Longitude titik 2 (sekolah)
 * @return array ['jarak' => meter, 'durasi' => detik, 'success' => bool]
 */
function hitungJarakDarat($lat1, $lon1, $lat2, $lon2)
{
    $api = defined('ROUTING_API') ? ROUTING_API : 'osrm';

    switch ($api) {
        case 'openrouteservice':
            return hitungJarakOpenRouteService($lat1, $lon1, $lat2, $lon2);
        case 'osrm':
        default:
            return hitungJarakOSRM($lat1, $lon1, $lat2, $lon2);
    }
}

/**
 * Menghitung jarak darat menggunakan OSRM (Open Source Routing Machine)
 * GRATIS - Tidak perlu API key
 * 
 * @param float $lat1 Latitude asal
 * @param float $lon1 Longitude asal
 * @param float $lat2 Latitude tujuan
 * @param float $lon2 Longitude tujuan
 * @return array
 */
function hitungJarakOSRM($lat1, $lon1, $lat2, $lon2)
{
    // OSRM public server (gratis)
    $url = "https://router.project-osrm.org/route/v1/driving/{$lon1},{$lat1};{$lon2},{$lat2}?overview=false";

    $response = buatRequestAPI($url);

    if ($response && isset($response['routes'][0])) {
        $route = $response['routes'][0];
        return [
            'jarak' => $route['distance'], // dalam meter
            'durasi' => $route['duration'], // dalam detik
            'success' => true,
            'source' => 'OSRM'
        ];
    }

    // Fallback ke Haversine jika API gagal
    return [
        'jarak' => hitungJarakHaversine($lat1, $lon1, $lat2, $lon2),
        'durasi' => null,
        'success' => false,
        'source' => 'Haversine (fallback)',
        'error' => 'OSRM API tidak tersedia'
    ];
}

/**
 * Menghitung jarak darat menggunakan OpenRouteService
 * GRATIS dengan limit - Perlu API key (daftar di openrouteservice.org)
 * 
 * @param float $lat1 Latitude asal
 * @param float $lon1 Longitude asal
 * @param float $lat2 Latitude tujuan
 * @param float $lon2 Longitude tujuan
 * @return array
 */
function hitungJarakOpenRouteService($lat1, $lon1, $lat2, $lon2)
{
    $apiKey = defined('OPENROUTESERVICE_API_KEY') ? OPENROUTESERVICE_API_KEY : '';

    if (empty($apiKey)) {
        // Fallback ke OSRM jika tidak ada API key
        return hitungJarakOSRM($lat1, $lon1, $lat2, $lon2);
    }

    $url = "https://api.openrouteservice.org/v2/directions/driving-car";

    $data = [
        'coordinates' => [
            [$lon1, $lat1],
            [$lon2, $lat2]
        ]
    ];

    $options = [
        'http' => [
            'header' => [
                "Content-Type: application/json",
                "Authorization: " . $apiKey
            ],
            'method' => 'POST',
            'content' => json_encode($data)
        ]
    ];

    $context = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);

    if ($result) {
        $response = json_decode($result, true);
        if (isset($response['routes'][0]['summary'])) {
            $summary = $response['routes'][0]['summary'];
            return [
                'jarak' => $summary['distance'], // dalam meter
                'durasi' => $summary['duration'], // dalam detik
                'success' => true,
                'source' => 'OpenRouteService'
            ];
        }
    }

    // Fallback ke OSRM
    return hitungJarakOSRM($lat1, $lon1, $lat2, $lon2);
}

/**
 * Helper function untuk membuat HTTP request ke API
 * 
 * @param string $url URL endpoint
 * @return array|null Response data
 */
function buatRequestAPI($url)
{
    $options = [
        'http' => [
            'header' => "User-Agent: PPDB-SMK-Zonasi/1.0\r\n",
            'timeout' => 10
        ]
    ];

    $context = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);

    if ($result) {
        return json_decode($result, true);
    }

    return null;
}

/**
 * ========================================
 * FUNGSI BACKUP: JARAK GARIS LURUS (UDARA)
 * ========================================
 */

/**
 * Menghitung jarak UDARA (garis lurus) menggunakan formula Haversine
 * Digunakan sebagai fallback jika API routing tidak tersedia
 * 
 * @param float $lat1 Latitude titik 1 (siswa)
 * @param float $lon1 Longitude titik 1 (siswa)
 * @param float $lat2 Latitude titik 2 (sekolah)
 * @param float $lon2 Longitude titik 2 (sekolah)
 * @return float Jarak dalam meter
 */
function hitungJarakHaversine($lat1, $lon1, $lat2, $lon2)
{
    $earthRadius = 6371000; // Radius bumi dalam meter

    // Konversi ke radian
    $latFrom = deg2rad($lat1);
    $lonFrom = deg2rad($lon1);
    $latTo = deg2rad($lat2);
    $lonTo = deg2rad($lon2);

    // Selisih koordinat
    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

    // Formula Haversine
    $a = sin($latDelta / 2) * sin($latDelta / 2) +
        cos($latFrom) * cos($latTo) *
        sin($lonDelta / 2) * sin($lonDelta / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return $earthRadius * $c; // Hasil dalam meter
}

/**
 * Menghitung skor zonasi berdasarkan jarak
 * Semakin dekat, skor semakin tinggi
 * 
 * @param float $jarak Jarak dalam meter
 * @param float $radiusMax Radius maksimum zonasi (default 3000m)
 * @return float Skor zonasi (0-100)
 */
function hitungSkorZonasi($jarak, $radiusMax = 3000)
{
    if ($jarak <= 0) {
        return 100;
    }

    if ($jarak >= $radiusMax) {
        return 0;
    }

    // Skor berbanding terbalik dengan jarak
    $skor = (1 - ($jarak / $radiusMax)) * 100;
    return round($skor, 4);
}

/**
 * Cek apakah siswa berada dalam radius zonasi
 * 
 * @param float $jarak Jarak dalam meter
 * @param float $radiusZonasi Radius zonasi (default dari config: 3000m)
 * @return bool
 */
function dalamRadiusZonasi($jarak, $radiusZonasi = 3000)
{
    return $jarak <= $radiusZonasi;
}

/**
 * Format jarak untuk ditampilkan
 * 
 * @param float $jarak Jarak dalam meter
 * @return string Jarak terformat
 */
function formatJarak($jarak)
{
    if ($jarak >= 1000) {
        return round($jarak / 1000, 2) . ' km';
    }
    return round($jarak) . ' m';
}

/**
 * Dapatkan semua SMK dengan jarak DARAT dari koordinat siswa
 * 
 * @param float $latSiswa Latitude siswa
 * @param float $lonSiswa Longitude siswa
 * @param PDO $db Koneksi database
 * @param bool $useCache Gunakan cache untuk mengurangi API calls (default: true)
 * @return array Data SMK dengan jarak darat
 */
function getSekolahDenganJarak($latSiswa, $lonSiswa, $db, $useCache = true)
{
    $sql = "SELECT id_smk, nama_sekolah, alamat, latitude, longitude FROM tb_smk";
    $stmt = $db->query($sql);
    $sekolahList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result = [];
    foreach ($sekolahList as $sekolah) {
        // Gunakan jarak DARAT (road distance)
        $jarakData = hitungJarakDarat(
            $latSiswa,
            $lonSiswa,
            $sekolah['latitude'],
            $sekolah['longitude']
        );

        $jarak = $jarakData['jarak'];

        $sekolah['jarak'] = $jarak;
        $sekolah['jarak_format'] = formatJarak($jarak);
        $sekolah['dalam_zonasi'] = dalamRadiusZonasi($jarak);
        $sekolah['skor_zonasi'] = hitungSkorZonasi($jarak);
        $sekolah['durasi_tempuh'] = isset($jarakData['durasi']) ? formatDurasi($jarakData['durasi']) : null;
        $sekolah['jarak_source'] = $jarakData['source'];
        $sekolah['is_road_distance'] = $jarakData['success'];

        $result[] = $sekolah;
    }

    // Urutkan berdasarkan jarak terdekat
    usort($result, function ($a, $b) {
        return $a['jarak'] <=> $b['jarak'];
    });

    return $result;
}

/**
 * Format durasi tempuh untuk ditampilkan
 * 
 * @param float $durasi Durasi dalam detik
 * @return string Durasi terformat
 */
function formatDurasi($durasi)
{
    if ($durasi === null) {
        return '-';
    }

    $menit = round($durasi / 60);

    if ($menit >= 60) {
        $jam = floor($menit / 60);
        $sisaMenit = $menit % 60;
        return $jam . ' jam ' . $sisaMenit . ' menit';
    }

    return $menit . ' menit';
}

/**
 * Validasi format koordinat
 * 
 * @param float $lat Latitude
 * @param float $lon Longitude
 * @return bool
 */
function validasiKoordinat($lat, $lon)
{
    // Latitude: -90 to 90
    // Longitude: -180 to 180
    if ($lat < -90 || $lat > 90) {
        return false;
    }
    if ($lon < -180 || $lon > 180) {
        return false;
    }
    return true;
}

/**
 * Validasi koordinat untuk area Kota Padang (Sumatera Barat)
 * Approximate bounds untuk Kota Padang
 * 
 * @param float $lat Latitude
 * @param float $lon Longitude
 * @return bool
 */
function validasiKoordinatPadang($lat, $lon)
{
    // Approximate bounds untuk Kota Padang
    $latMin = -1.05;
    $latMax = -0.80;
    $lonMin = 100.30;
    $lonMax = 100.50;

    return ($lat >= $latMin && $lat <= $latMax &&
        $lon >= $lonMin && $lon <= $lonMax);
}
