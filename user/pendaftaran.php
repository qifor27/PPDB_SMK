<?php
/**
 * User - Form Pendaftaran
 */
$pageTitle = 'Form Pendaftaran';
require_once 'includes/header.php';

$smkList = getAllSMK();
$error = '';

// Create new pendaftaran if jalur is selected
if (isset($_GET['jalur']) && !$pendaftaran) {
    $jalurId = (int) $_GET['jalur'];
    $jalur = db()->fetch("SELECT * FROM tb_jalur WHERE id_jalur = ? AND is_active = 1", [$jalurId]);

    if ($jalur) {
        $nomorPendaftaran = generateNomorPendaftaran($jalur['kode_jalur']);
        db()->insert('tb_pendaftaran', [
            'nomor_pendaftaran' => $nomorPendaftaran,
            'id_siswa' => $userId,
            'id_smk_pilihan1' => $smkList[0]['id_smk'],
            'id_jalur' => $jalurId,
            'tahun_ajaran' => getTahunAjaran(),
            'status' => 'draft'
        ]);
        redirect('pendaftaran.php');
    }
}

// Get fresh pendaftaran data
$pendaftaran = db()->fetch(
    "SELECT p.*, j.nama_jalur, j.kode_jalur FROM tb_pendaftaran p
     LEFT JOIN tb_jalur j ON p.id_jalur = j.id_jalur
     WHERE p.id_siswa = ? ORDER BY p.id_pendaftaran DESC LIMIT 1",
    [$userId]
);

if (!$pendaftaran) {
    redirect('pilih-jalur.php');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Session::verifyCsrf($_POST['csrf_token'] ?? '')) {
        $error = 'Token tidak valid.';
    } else {
        $smkPilihan1 = (int) $_POST['smk_pilihan1'];
        $smkPilihan2 = !empty($_POST['smk_pilihan2']) ? (int) $_POST['smk_pilihan2'] : null;
        $nilaiRataRata = !empty($_POST['nilai_rata_rata']) ? (float) $_POST['nilai_rata_rata'] : null;

        // Update pendaftaran
        db()->update('tb_pendaftaran', [
            'id_smk_pilihan1' => $smkPilihan1,
            'id_smk_pilihan2' => $smkPilihan2,
            'nilai_rata_rata' => $nilaiRataRata
        ], 'id_pendaftaran = ?', ['id_pendaftaran' => $pendaftaran['id_pendaftaran']]);

        // Update siswa data
        db()->update('tb_siswa', [
            'alamat' => sanitize($_POST['alamat'] ?? ''),
            'kelurahan' => sanitize($_POST['kelurahan'] ?? ''),
            'kecamatan' => sanitize($_POST['kecamatan'] ?? ''),
            'kode_pos' => sanitize($_POST['kode_pos'] ?? ''),
            'agama' => sanitize($_POST['agama'] ?? ''),
            'asal_sekolah' => sanitize($_POST['asal_sekolah'] ?? ''),
            'nama_ayah' => sanitize($_POST['nama_ayah'] ?? ''),
            'pekerjaan_ayah' => sanitize($_POST['pekerjaan_ayah'] ?? ''),
            'nama_ibu' => sanitize($_POST['nama_ibu'] ?? ''),
            'pekerjaan_ibu' => sanitize($_POST['pekerjaan_ibu'] ?? ''),
            'no_hp_ortu' => sanitize($_POST['no_hp_ortu'] ?? ''),
            'latitude' => !empty($_POST['latitude']) ? (float) $_POST['latitude'] : null,
            'longitude' => !empty($_POST['longitude']) ? (float) $_POST['longitude'] : null
        ], 'id_siswa = ?', ['id_siswa' => $userId]);

        Session::flash('success', 'Data pendaftaran berhasil disimpan.');
        redirect('pendaftaran.php');
    }
}

// Refresh siswa data
$siswa = db()->fetch("SELECT * FROM tb_siswa WHERE id_siswa = ?", [$userId]);
$kejuruanList = getKejuruanBySMK($pendaftaran['id_smk_pilihan1']);
?>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">Data Pendaftaran</h5>
            <small class="text-muted">No: <?= $pendaftaran['nomor_pendaftaran'] ?></small>
        </div>
        <?= getJalurBadge($pendaftaran['kode_jalur']) ?>
    </div>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i><?= $error ?></div>
<?php endif; ?>

<form method="POST" class="needs-validation" novalidate>
    <?= Session::csrfField() ?>

    <!-- Data Pribadi -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0"><i class="bi bi-person me-2"></i>Data Pribadi</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($siswa['nama_lengkap']) ?>"
                        disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label">NISN</label>
                    <input type="text" class="form-control" value="<?= $siswa['nisn'] ?>" disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <input type="text" class="form-control"
                        value="<?= $siswa['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?>" disabled>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($siswa['tempat_lahir']) ?>"
                        disabled>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="text" class="form-control" value="<?= formatDate($siswa['tanggal_lahir']) ?>" disabled>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Agama</label>
                    <select name="agama" class="form-select" required>
                        <option value="">Pilih Agama</option>
                        <?php foreach (['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $agama): ?>
                            <option value="<?= $agama ?>" <?= ($siswa['agama'] ?? '') === $agama ? 'selected' : '' ?>>
                                <?= $agama ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Alamat -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Alamat Domisili</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control" rows="2"
                        required><?= htmlspecialchars($siswa['alamat'] ?? '') ?></textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kelurahan</label>
                    <input type="text" name="kelurahan" class="form-control"
                        value="<?= htmlspecialchars($siswa['kelurahan'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kecamatan</label>
                    <input type="text" name="kecamatan" class="form-control"
                        value="<?= htmlspecialchars($siswa['kecamatan'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kode Pos</label>
                    <input type="text" name="kode_pos" class="form-control"
                        value="<?= htmlspecialchars($siswa['kode_pos'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Koordinat (untuk zonasi)</label>
                    <div class="input-group">
                        <input type="text" name="latitude" class="form-control" placeholder="Latitude"
                            value="<?= $siswa['latitude'] ?? '' ?>" id="inputLat">
                        <input type="text" name="longitude" class="form-control" placeholder="Longitude"
                            value="<?= $siswa['longitude'] ?? '' ?>" id="inputLng">
                        <button type="button" class="btn btn-primary" id="btnGetLocation">
                            <i class="bi bi-crosshair"></i>
                        </button>
                    </div>
                    <small class="form-text">Klik tombol untuk mendeteksi lokasi atau klik pada peta</small>
                </div>
            </div>
        </div>
    </div>

    <?php if ($pendaftaran['kode_jalur'] === 'zonasi'): ?>
        <!-- Peta Zonasi - Hanya tampil untuk jalur zonasi -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-map me-2"></i>Peta Lokasi & SMK Terdekat</h6>
                <span class="badge bg-success" id="distanceInfo">Klik peta untuk menentukan lokasi</span>
            </div>
            <div class="card-body p-0">
                <div class="row g-0">
                    <!-- Peta -->
                    <div class="col-md-8">
                        <div id="mapZonasi" style="height: 400px; width: 100%;"></div>
                    </div>
                    <!-- List SMK Terdekat -->
                    <div class="col-md-4">
                        <div class="p-3 h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <h6 class="text-white mb-3">
                                <i class="bi bi-geo-fill me-2"></i>2 SMK Terdekat
                            </h6>
                            <div id="nearestSchoolsList">
                                <div class="text-white-50 text-center py-4">
                                    <i class="bi bi-geo-alt" style="font-size: 2rem;"></i>
                                    <p class="mt-2 mb-0">Tentukan lokasi Anda terlebih dahulu</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leaflet CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <?php endif; ?>

    <!-- Asal Sekolah -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0"><i class="bi bi-building me-2"></i>Asal Sekolah</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Nama Sekolah Asal (SMP/MTs)</label>
                    <input type="text" name="asal_sekolah" class="form-control" required
                        value="<?= htmlspecialchars($siswa['asal_sekolah'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nilai Rata-rata Raport</label>
                    <input type="number" name="nilai_rata_rata" class="form-control" step="0.01" min="0" max="100"
                        value="<?= $pendaftaran['nilai_rata_rata'] ?? '' ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Data Orang Tua -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0"><i class="bi bi-people me-2"></i>Data Orang Tua</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Ayah</label>
                    <input type="text" name="nama_ayah" class="form-control"
                        value="<?= htmlspecialchars($siswa['nama_ayah'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Pekerjaan Ayah</label>
                    <input type="text" name="pekerjaan_ayah" class="form-control"
                        value="<?= htmlspecialchars($siswa['pekerjaan_ayah'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Ibu</label>
                    <input type="text" name="nama_ibu" class="form-control"
                        value="<?= htmlspecialchars($siswa['nama_ibu'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Pekerjaan Ibu</label>
                    <input type="text" name="pekerjaan_ibu" class="form-control"
                        value="<?= htmlspecialchars($siswa['pekerjaan_ibu'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">No. HP Orang Tua</label>
                    <input type="text" name="no_hp_ortu" class="form-control"
                        value="<?= htmlspecialchars($siswa['no_hp_ortu'] ?? '') ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Pilihan Sekolah -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0"><i class="bi bi-building me-2"></i>Pilihan Sekolah Tujuan</h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">SMK Pilihan 1 <span class="text-danger">*</span></label>
                    <select name="smk_pilihan1" class="form-select" required>
                        <?php foreach ($smkList as $smk): ?>
                            <option value="<?= $smk['id_smk'] ?>" <?= $pendaftaran['id_smk_pilihan1'] == $smk['id_smk'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($smk['nama_sekolah']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">SMK Pilihan 2 (Opsional)</label>
                    <select name="smk_pilihan2" class="form-select">
                        <option value="">-- Tidak Ada --</option>
                        <?php foreach ($smkList as $smk): ?>
                            <option value="<?= $smk['id_smk'] ?>" <?= ($pendaftaran['id_smk_pilihan2'] ?? '') == $smk['id_smk'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($smk['nama_sekolah']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <a href="index.php" class="btn btn-dark">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
        <div>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-2"></i>Simpan Data
            </button>
            <a href="dokumen.php" class="btn btn-outline-primary">
                Lanjut Upload Dokumen <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</form>

<?php
// Prepare SMK data for JavaScript
$smkDataJson = json_encode(array_map(function ($smk) {
    return [
        'id' => $smk['id_smk'],
        'nama' => $smk['nama_sekolah'],
        'alamat' => $smk['alamat'],
        'lat' => (float) $smk['latitude'],
        'lng' => (float) $smk['longitude']
    ];
}, $smkList));

$isZonasi = $pendaftaran['kode_jalur'] === 'zonasi';
$isZonasiJs = $isZonasi ? 'true' : 'false';
$initialLat = $siswa['latitude'] ?? -0.9471;
$initialLng = $siswa['longitude'] ?? 100.4172;

$extraScripts = <<<EOT
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Data SMK dari database
const smkData = {$smkDataJson};
const isZonasi = {$isZonasiJs};
let map, userMarker;
const schoolMarkers = [];

// Inisialisasi peta jika jalur zonasi
if (isZonasi && document.getElementById('mapZonasi')) {
    initMap();
}

function initMap() {
    // Inisialisasi peta dengan pusat di Padang
    map = L.map('mapZonasi').setView([{$initialLat}, {$initialLng}], 13);
    
    // Tile layer OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Custom icons
    const userIcon = L.divIcon({
        className: 'custom-marker',
        html: '<div style="background: #667eea; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>',
        iconSize: [20, 20],
        iconAnchor: [10, 10]
    });
    
    const schoolIcon = L.divIcon({
        className: 'custom-marker',
        html: '<div style="background: #10B981; width: 16px; height: 16px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>',
        iconSize: [16, 16],
        iconAnchor: [8, 8]
    });
    
    // Tambahkan marker untuk semua SMK
    smkData.forEach(smk => {
        if (smk.lat && smk.lng) {
            const marker = L.marker([smk.lat, smk.lng], { icon: schoolIcon })
                .addTo(map)
                .bindPopup('<strong>' + smk.nama + '</strong><br><small>' + smk.alamat + '</small>');
            marker.smkData = smk;
            schoolMarkers.push(marker);
        }
    });
    
    // Tambahkan marker user jika sudah ada koordinat
    const initLat = parseFloat(document.getElementById('inputLat').value);
    const initLng = parseFloat(document.getElementById('inputLng').value);
    if (initLat && initLng) {
        addUserMarker(initLat, initLng, userIcon);
        updateNearestSchools(initLat, initLng);
    }
    
    // Event klik pada peta
    map.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        
        document.getElementById('inputLat').value = lat.toFixed(8);
        document.getElementById('inputLng').value = lng.toFixed(8);
        
        addUserMarker(lat, lng, userIcon);
        updateNearestSchools(lat, lng);
    });
}

function addUserMarker(lat, lng, icon) {
    if (userMarker) {
        map.removeLayer(userMarker);
    }
    
    const userIcon = icon || L.divIcon({
        className: 'custom-marker',
        html: '<div style="background: #667eea; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>',
        iconSize: [20, 20],
        iconAnchor: [10, 10]
    });
    
    userMarker = L.marker([lat, lng], { icon: userIcon, draggable: true })
        .addTo(map)
        .bindPopup('<strong>Lokasi Anda</strong>')
        .openPopup();
    
    // Event drag
    userMarker.on('dragend', function(e) {
        const pos = e.target.getLatLng();
        document.getElementById('inputLat').value = pos.lat.toFixed(8);
        document.getElementById('inputLng').value = pos.lng.toFixed(8);
        updateNearestSchools(pos.lat, pos.lng);
    });
}

// Hitung jarak darat menggunakan OSRM
async function getRoadDistance(lat1, lng1, lat2, lng2) {
    try {
        const url = 'https://router.project-osrm.org/route/v1/driving/' + lng1 + ',' + lat1 + ';' + lng2 + ',' + lat2 + '?overview=false';
        const response = await fetch(url);
        const data = await response.json();
        
        if (data.routes && data.routes[0]) {
            return {
                distance: data.routes[0].distance, // meter
                duration: data.routes[0].duration, // detik
                success: true
            };
        }
    } catch (error) {
        console.log('OSRM error, using Haversine fallback');
    }
    
    // Fallback ke Haversine
    return {
        distance: haversineDistance(lat1, lng1, lat2, lng2),
        duration: null,
        success: false
    };
}

// Haversine formula (fallback)
function haversineDistance(lat1, lng1, lat2, lng2) {
    const R = 6371000;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLng = (lng2 - lng1) * Math.PI / 180;
    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLng/2) * Math.sin(dLng/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}

// Format jarak
function formatDistance(meters) {
    if (meters >= 1000) {
        return (meters / 1000).toFixed(2) + ' km';
    }
    return Math.round(meters) + ' m';
}

// Format durasi
function formatDuration(seconds) {
    if (!seconds) return '-';
    const minutes = Math.round(seconds / 60);
    if (minutes >= 60) {
        const hours = Math.floor(minutes / 60);
        const mins = minutes % 60;
        return hours + ' jam ' + mins + ' menit';
    }
    return minutes + ' menit';
}

// Update daftar 2 SMK terdekat
let routeLines = [];
let highlightCircles = [];

async function updateNearestSchools(userLat, userLng) {
    const listContainer = document.getElementById('nearestSchoolsList');
    listContainer.innerHTML = '<div class="text-white text-center py-3"><span class="spinner-border spinner-border-sm"></span> Menghitung jarak...</div>';
    
    // Clear previous highlights
    clearMapHighlights();
    
    // Hitung jarak ke semua SMK
    const distances = [];
    for (const smk of smkData) {
        if (smk.lat && smk.lng) {
            const result = await getRoadDistance(userLat, userLng, smk.lat, smk.lng);
            distances.push({
                ...smk,
                distance: result.distance,
                duration: result.duration,
                isRoadDistance: result.success
            });
        }
    }
    
    // Sort by distance dan ambil 2 terdekat
    distances.sort((a, b) => a.distance - b.distance);
    const nearest = distances.slice(0, 2);
    
    // Update info badge
    if (nearest.length > 0) {
        const distInfo = document.getElementById('distanceInfo');
        distInfo.innerHTML = nearest[0].isRoadDistance 
            ? '<i class="bi bi-car-front me-1"></i>Jarak via jalan raya' 
            : '<i class="bi bi-geo me-1"></i>Jarak garis lurus';
        distInfo.className = nearest[0].isRoadDistance ? 'badge bg-success' : 'badge bg-warning';
    }
    
    // ========================================
    // HIGHLIGHT 2 SMK TERDEKAT PADA PETA
    // ========================================
    highlightNearestOnMap(userLat, userLng, nearest);
    
    // Render list
    let html = '';
    nearest.forEach((smk, index) => {
        const isFirst = index === 0;
        html += '<div class="school-card mb-2 p-3" style="background: rgba(255,255,255,' + (isFirst ? '0.2' : '0.1') + '); border-radius: 10px; ' + (isFirst ? 'border: 2px solid rgba(255,255,255,0.5);' : '') + '">';
        html += '  <div class="d-flex align-items-start">';
        html += '    <div class="me-2">';
        html += '      <span class="badge ' + (isFirst ? 'bg-warning text-dark' : 'bg-light text-dark') + '" style="font-size: 0.9rem;">' + (index + 1) + '</span>';
        html += '    </div>';
        html += '    <div class="flex-grow-1">';
        html += '      <h6 class="text-white mb-1" style="font-size: 0.9rem;">' + smk.nama + '</h6>';
        html += '      <div class="d-flex flex-wrap gap-2">';
        html += '        <span class="badge bg-light text-dark"><i class="bi bi-signpost-2 me-1"></i>' + formatDistance(smk.distance) + '</span>';
        if (smk.duration) {
            html += '        <span class="badge bg-light text-dark"><i class="bi bi-clock me-1"></i>' + formatDuration(smk.duration) + '</span>';
        }
        html += '      </div>';
        if (isFirst) {
            html += '      <small class="text-white-50 mt-1 d-block"><i class="bi bi-star-fill text-warning me-1"></i>Rekomendasi terdekat</small>';
        }
        html += '    </div>';
        html += '  </div>';
        html += '</div>';
    });
    
    listContainer.innerHTML = html;
    
    // Auto-select pilihan 1 dan 2 dengan sekolah terdekat
    if (nearest.length >= 1) {
        document.querySelector('select[name="smk_pilihan1"]').value = nearest[0].id;
    }
    if (nearest.length >= 2) {
        document.querySelector('select[name="smk_pilihan2"]').value = nearest[1].id;
    }
}

// Event handler untuk tombol get location
document.getElementById('btnGetLocation').addEventListener('click', function() {
    if (navigator.geolocation) {
        this.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        navigator.geolocation.getCurrentPosition(
            pos => {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                
                document.getElementById('inputLat').value = lat.toFixed(8);
                document.getElementById('inputLng').value = lng.toFixed(8);
                this.innerHTML = '<i class="bi bi-check-lg"></i>';
                
                // Update peta jika ada
                if (isZonasi && map) {
                    map.setView([lat, lng], 14);
                    addUserMarker(lat, lng);
                    updateNearestSchools(lat, lng);
                }
            },
            err => {
                alert('Gagal mendapatkan lokasi: ' + err.message);
                this.innerHTML = '<i class="bi bi-crosshair"></i>';
            }
        );
    }
});

// ========================================
// FUNGSI HIGHLIGHT SMK TERDEKAT DI PETA
// ========================================

function clearMapHighlights() {
    // Hapus garis route sebelumnya
    routeLines.forEach(line => map.removeLayer(line));
    routeLines = [];
    
    // Hapus lingkaran highlight sebelumnya
    highlightCircles.forEach(circle => map.removeLayer(circle));
    highlightCircles = [];
    
    // Reset semua marker SMK ke warna default (hijau)
    schoolMarkers.forEach(marker => {
        const defaultIcon = L.divIcon({
            className: 'custom-marker',
            html: '<div style="background: #10B981; width: 16px; height: 16px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>',
            iconSize: [16, 16],
            iconAnchor: [8, 8]
        });
        marker.setIcon(defaultIcon);
    });
}

async function highlightNearestOnMap(userLat, userLng, nearestSchools) {
    // Warna untuk ranking
    const colors = [
        { bg: '#EF4444', border: '#DC2626', glow: 'rgba(239, 68, 68, 0.3)' }, // Merah - Terdekat
        { bg: '#F97316', border: '#EA580C', glow: 'rgba(249, 115, 22, 0.3)' }  // Orange - Kedua
    ];
    
    // Gunakan for...of agar bisa await (sequential) untuk menghindari race condition/limit request
    for (const [index, smk] of nearestSchools.entries()) {
        const color = colors[index];
        const ranking = index + 1;
        
        // 1. Tambahkan lingkaran glow STATIS (tanpa animasi pulse yang bug)
        const glowCircle = L.circle([smk.lat, smk.lng], {
            radius: 150,
            color: color.bg,
            fillColor: color.glow,
            fillOpacity: 0.4,
            weight: 2,
            opacity: 0.6
        }).addTo(map);
        highlightCircles.push(glowCircle);
        
        // 2. Ganti marker SMK dengan marker khusus berwarna merah/orange + nomor ranking
        const highlightIcon = L.divIcon({
            className: 'highlight-marker',
            html: '<div style="' +
                'position: relative;' +
                'background: ' + color.bg + ';' +
                'width: 32px;' +
                'height: 32px;' +
                'border-radius: 50%;' +
                'border: 3px solid white;' +
                'box-shadow: 0 4px 12px rgba(0,0,0,0.4);' +
                'display: flex;' +
                'align-items: center;' +
                'justify-content: center;' +
                'color: white;' +
                'font-weight: bold;' +
                'font-size: 14px;' +
                '">' + ranking + '</div>',
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        });
        
        // Update marker yang sesuai
        schoolMarkers.forEach(marker => {
            if (marker.smkData && marker.smkData.id === smk.id) {
                marker.setIcon(highlightIcon);
                
                // Update popup dengan info jarak
                marker.bindPopup(
                    '<div style="text-align: center;">' +
                    '<div style="background: ' + color.bg + '; color: white; padding: 5px 10px; border-radius: 5px; margin-bottom: 8px;">' +
                    '<strong>#' + ranking + ' Terdekat</strong>' +
                    '</div>' +
                    '<strong>' + smk.nama + '</strong><br>' +
                    '<small>📍 ' + formatDistance(smk.distance) + '</small>' +
                    (smk.duration ? '<br><small>⏱️ ' + formatDuration(smk.duration) + '</small>' : '') +
                    '</div>'
                );
            }
        });
        
        // 3. Gambar garis mengikuti JALUR DARAT dari OSRM
        // Await di sini penting agar request dilakukan satu per satu
        const lineColor = index === 0 ? '#EF4444' : '#F97316';
        await drawRoadRoute(userLat, userLng, smk.lat, smk.lng, lineColor, index);
    }
    
    // 4. Fit bounds agar semua marker terlihat
    if (nearestSchools.length > 0) {
        setTimeout(() => {
            const bounds = L.latLngBounds([
                [userLat, userLng],
                ...nearestSchools.map(s => [s.lat, s.lng])
            ]);
            map.fitBounds(bounds, { padding: [50, 50] });
        }, 500);
    }
}

// Fungsi untuk menggambar rute jalur darat
async function drawRoadRoute(lat1, lng1, lat2, lng2, lineColor, index) {
    try {
        // Request OSRM dengan geometry
        const url = 'https://router.project-osrm.org/route/v1/driving/' + 
                    lng1 + ',' + lat1 + ';' + lng2 + ',' + lat2 + 
                    '?overview=full&geometries=geojson';
        
        const response = await fetch(url);
        const data = await response.json();
        
        if (data.routes && data.routes[0] && data.routes[0].geometry) {
            // Ambil koordinat dari geometry GeoJSON
            const coordinates = data.routes[0].geometry.coordinates;
            // Convert dari [lng, lat] ke [lat, lng] untuk Leaflet
            const latLngs = coordinates.map(coord => [coord[1], coord[0]]);
            
            // Gambar polyline mengikuti jalan
            const routeLine = L.polyline(latLngs, {
                color: lineColor,
                weight: index === 0 ? 5 : 4,
                opacity: index === 0 ? 0.9 : 0.7,
                lineCap: 'round',
                lineJoin: 'round'
            }).addTo(map);
            
            routeLines.push(routeLine);
        } else {
            // Fallback ke garis lurus jika OSRM gagal
            drawStraightLine(lat1, lng1, lat2, lng2, lineColor, index);
        }
    } catch (error) {
        console.log('OSRM route error, using straight line');
        drawStraightLine(lat1, lng1, lat2, lng2, lineColor, index);
    }
}

// Fallback garis lurus
function drawStraightLine(lat1, lng1, lat2, lng2, lineColor, index) {
    const routeLine = L.polyline(
        [[lat1, lng1], [lat2, lng2]], 
        {
            color: lineColor,
            weight: index === 0 ? 4 : 3,
            opacity: index === 0 ? 0.8 : 0.6,
            dashArray: '10, 10'
        }
    ).addTo(map);
    routeLines.push(routeLine);
}

// Inject CSS untuk animasi
const styleSheet = document.createElement('style');
styleSheet.textContent = `
    @keyframes pulse {
        0% { transform: scale(1); opacity: 0.8; }
        50% { transform: scale(1.3); opacity: 0.4; }
        100% { transform: scale(1); opacity: 0.8; }
    }
    
    @keyframes bounce {
        0% { transform: translateY(-10px); }
        50% { transform: translateY(5px); }
        100% { transform: translateY(0); }
    }
    
    .pulse-circle {
        animation: pulse 1.5s infinite;
    }
    
    .pulse-1 {
        animation: pulse 1.2s infinite;
    }
    
    .pulse-2 {
        animation: pulse 1.8s infinite;
    }
    
    .highlight-marker {
        z-index: 1000 !important;
    }
    
    .route-line {
        transition: all 0.3s ease;
    }
`;
document.head.appendChild(styleSheet);
</script>
EOT;
require_once 'includes/footer.php';
?>