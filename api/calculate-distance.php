<?php
/**
 * API - Calculate Distance
 */

header('Content-Type: application/json');

require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/config/functions.php';

$lat = isset($_GET['lat']) ? (float)$_GET['lat'] : null;
$lng = isset($_GET['lng']) ? (float)$_GET['lng'] : null;
$smkId = isset($_GET['smk_id']) ? (int)$_GET['smk_id'] : null;

if (!$lat || !$lng) {
    echo json_encode(['success' => false, 'error' => 'Koordinat tidak valid']);
    exit;
}

if ($smkId) {
    // Calculate distance to specific SMK
    $smk = getSMKById($smkId);
    if (!$smk) {
        echo json_encode(['success' => false, 'error' => 'SMK tidak ditemukan']);
        exit;
    }
    
    $distance = calculateDistance($lat, $lng, $smk['latitude'], $smk['longitude']);
    
    echo json_encode([
        'success' => true,
        'data' => [
            'smk' => $smk['nama_sekolah'],
            'distance' => $distance,
            'formatted' => formatDistance($distance),
            'in_radius' => $distance <= RADIUS_ZONASI
        ]
    ]);
} else {
    // Calculate distance to all SMK
    $smkList = getAllSMK();
    $results = [];
    
    foreach ($smkList as $smk) {
        $distance = calculateDistance($lat, $lng, $smk['latitude'], $smk['longitude']);
        $results[] = [
            'id_smk' => $smk['id_smk'],
            'nama_sekolah' => $smk['nama_sekolah'],
            'distance' => $distance,
            'formatted' => formatDistance($distance),
            'in_radius' => $distance <= RADIUS_ZONASI
        ];
    }
    
    // Sort by distance
    usort($results, fn($a, $b) => $a['distance'] <=> $b['distance']);
    
    echo json_encode([
        'success' => true,
        'user_location' => ['lat' => $lat, 'lng' => $lng],
        'radius' => RADIUS_ZONASI,
        'data' => $results
    ]);
}
