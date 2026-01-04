<?php

/**
 * User - Profil Saya
 */
$pageTitle = 'Profil Saya';
require_once 'includes/header.php';

$error = '';
$smkList = getAllSMK();

// Handle foto upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    if (!Session::verifyCsrf($_POST['csrf_token'] ?? '')) {
        $error = 'Token tidak valid.';
    } else {
        $fotoPath = UPLOADS_PATH . 'foto/';
        if (!is_dir($fotoPath)) mkdir($fotoPath, 0755, true);

        // Delete old foto
        if (!empty($siswa['foto']) && file_exists($fotoPath . $siswa['foto'])) {
            unlink($fotoPath . $siswa['foto']);
        }

        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $fotoFilename = 'foto_' . $userId . '_' . time() . '.' . $ext;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $fotoPath . $fotoFilename)) {
            db()->update('tb_siswa', ['foto' => $fotoFilename], 'id_siswa = :id', ['id' => $userId]);
            Session::flash('success', 'Foto profil berhasil diupload!');
            redirect('profil.php');
        } else {
            $error = 'Gagal mengupload foto.';
        }
    }
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_FILES['foto'])) {
    if (!Session::verifyCsrf($_POST['csrf_token'] ?? '')) {
        $error = 'Token tidak valid.';
    } else {
        $data = [
            'email' => sanitize($_POST['email']),
            'no_hp' => sanitize($_POST['no_hp']),
            'alamat' => sanitize($_POST['alamat']),
            'kota_kabupaten' => sanitize($_POST['kota_kabupaten_nama'] ?? ''),
            'kelurahan' => sanitize($_POST['kelurahan']),
            'kecamatan' => sanitize($_POST['kecamatan']),
            'agama' => sanitize($_POST['agama']),
            'latitude' => !empty($_POST['latitude']) ? (float) $_POST['latitude'] : null,
            'longitude' => !empty($_POST['longitude']) ? (float) $_POST['longitude'] : null
        ];

        // Handle password change
        if (!empty($_POST['password_baru'])) {
            if ($_POST['password_baru'] !== $_POST['konfirm_password']) {
                $error = 'Konfirmasi password tidak cocok.';
            } elseif (strlen($_POST['password_baru']) < 6) {
                $error = 'Password minimal 6 karakter.';
            } else {
                $data['password'] = hashPassword($_POST['password_baru']);
            }
        }

        if (!$error) {
            db()->update('tb_siswa', $data, 'id_siswa = :where_id_siswa', ['where_id_siswa' => $userId]);
            Session::flash('success', 'Profil berhasil diperbarui.');
            redirect('profil.php');
        }
    }
}

// Refresh data
$siswa = db()->fetch("SELECT * FROM tb_siswa WHERE id_siswa = ?", [$userId]);

// Check foto URL
$fotoUrl = !empty($siswa['foto']) && file_exists(UPLOADS_PATH . 'foto/' . $siswa['foto'])
    ? UPLOADS_URL . 'foto/' . $siswa['foto']
    : null;
?>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card text-center">
            <div class="card-body">
                <!-- Foto Profil dengan Upload -->
                <div class="mb-3 position-relative d-inline-block">
                    <?php if ($fotoUrl): ?>
                        <img src="<?= $fotoUrl ?>" alt="Foto Profil"
                            class="rounded-circle" style="width:120px;height:120px;object-fit:cover;border:4px solid #667eea;">
                    <?php else: ?>
                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto"
                            style="width:120px;height:120px;font-size:3rem;color:white;border:4px solid #667eea;">
                            <?= strtoupper(substr($siswa['nama_lengkap'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data" id="fotoForm">
                        <?= Session::csrfField() ?>
                        <label for="fotoInput" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                            style="width:35px;height:35px;cursor:pointer;border:2px solid white;" title="Ganti Foto">
                            <i class="bi bi-camera-fill"></i>
                            <input type="file" name="foto" id="fotoInput" accept=".jpg,.jpeg,.png" style="display:none;"
                                onchange="document.getElementById('fotoForm').submit();">
                        </label>
                    </form>
                </div>

                <h5><?= htmlspecialchars($siswa['nama_lengkap']) ?></h5>
                <p class="text-muted">NISN: <?= $siswa['nisn'] ?></p>

                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge bg-<?= $siswa['jenis_kelamin'] === 'L' ? 'info' : 'pink' ?>-soft">
                        <?= $siswa['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?>
                    </span>
                    <?php if ($siswa['is_verified']): ?>
                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Verified</span>
                    <?php endif; ?>
                </div>

                <hr>

                <div class="text-start">
                    <p class="small mb-2"><i
                            class="bi bi-envelope me-2 text-primary"></i><?= htmlspecialchars($siswa['email']) ?></p>
                    <p class="small mb-2"><i
                            class="bi bi-phone me-2 text-primary"></i><?= htmlspecialchars($siswa['no_hp'] ?? '-') ?>
                    </p>
                    <p class="small mb-0"><i class="bi bi-calendar me-2 text-primary"></i>Bergabung:
                        <?= formatDate($siswa['created_at'], 'd M Y') ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-pencil me-2"></i>Edit Profil</h5>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i><?= $error ?></div>
                <?php endif; ?>

                <form method="POST">
                    <?= Session::csrfField() ?>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control"
                                value="<?= htmlspecialchars($siswa['nama_lengkap']) ?>" disabled>
                            <small class="form-text">Nama tidak dapat diubah</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NISN</label>
                            <input type="text" class="form-control" value="<?= $siswa['nisn'] ?>" disabled>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                value="<?= htmlspecialchars($siswa['email']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. HP / WhatsApp</label>
                            <input type="text" name="no_hp" class="form-control"
                                value="<?= htmlspecialchars($siswa['no_hp'] ?? '') ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Agama</label>
                            <select name="agama" class="form-select">
                                <?php foreach (['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $agama): ?>
                                    <option value="<?= $agama ?>" <?= ($siswa['agama'] ?? '') === $agama ? 'selected' : '' ?>>
                                        <?= $agama ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tempat, Tanggal Lahir</label>
                            <input type="text" class="form-control"
                                value="<?= htmlspecialchars($siswa['tempat_lahir']) ?>, <?= formatDate($siswa['tanggal_lahir']) ?>"
                                disabled>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control"
                                rows="2"><?= htmlspecialchars($siswa['alamat'] ?? '') ?></textarea>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Kota/Kabupaten</label>
                            <select name="kota_kabupaten" id="selectKota" class="form-select" required>
                                <option value="">-- Pilih Kota/Kabupaten --</option>
                            </select>
                            <input type="hidden" name="kota_kabupaten_nama" id="inputKotaNama" value="<?= htmlspecialchars($siswa['kota_kabupaten'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kecamatan</label>
                            <select name="kecamatan" id="selectKecamatan" class="form-select" disabled>
                                <option value="">-- Pilih Kecamatan --</option>
                            </select>
                            <input type="hidden" name="kecamatan_kode" id="inputKecamatanKode" value="">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kelurahan/Nagari</label>
                            <select name="kelurahan" id="selectKelurahan" class="form-select" disabled>
                                <option value="">-- Pilih Kelurahan --</option>
                            </select>
                        </div>

                        <!-- Lokasi Rumah dengan Peta -->
                        <div class="col-12">
                            <hr>
                            <h6><i class="bi bi-geo-alt me-2"></i>Lokasi Rumah</h6>
                            <p class="text-muted small">Tentukan lokasi rumah Anda untuk menghitung jarak ke sekolah pilihan</p>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Latitude</label>
                            <input type="text" name="latitude" id="inputLat" class="form-control"
                                value="<?= htmlspecialchars($siswa['latitude'] ?? '') ?>" placeholder="-0.9471">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Longitude</label>
                            <input type="text" name="longitude" id="inputLng" class="form-control"
                                value="<?= htmlspecialchars($siswa['longitude'] ?? '') ?>" placeholder="100.4172">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-outline-primary d-block w-100" id="btnGetLocation">
                                <i class="bi bi-crosshair me-2"></i>Deteksi Lokasi
                            </button>
                        </div>

                        <div class="col-12">
                            <div id="mapLokasi" style="height: 350px; width: 100%; border-radius: 10px; overflow: hidden;"></div>
                            <small class="form-text">Klik pada peta untuk menentukan lokasi atau gunakan tombol deteksi</small>
                        </div>

                        <?php if ($pendaftaran && $pendaftaran['id_smk_pilihan1']): ?>
                            <div class="col-12">
                                <div class="alert alert-info mb-0" id="distanceAlert">
                                    <i class="bi bi-signpost-2 me-2"></i>
                                    <span id="distanceInfo">Menghitung jarak ke sekolah pilihan...</span>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="col-12">
                            <hr>
                            <h6>Ubah Password</h6>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password_baru" class="form-control"
                                placeholder="Kosongkan jika tidak diubah">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="konfirm_password" class="form-control">
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data SMK dari database
        const smkData = <?= json_encode(array_map(function ($smk) {
                            return [
                                'id' => $smk['id_smk'],
                                'nama' => $smk['nama_sekolah'],
                                'lat' => (float)$smk['latitude'],
                                'lng' => (float)$smk['longitude']
                            ];
                        }, $smkList)) ?>;

        const selectedSchool1 = <?= $pendaftaran['id_smk_pilihan1'] ?? 'null' ?>;
        const selectedSchool2 = <?= $pendaftaran['id_smk_pilihan2'] ?? 'null' ?>;

        // Initialize map
        const initialLat = parseFloat(document.getElementById('inputLat').value) || -0.9471;
        const initialLng = parseFloat(document.getElementById('inputLng').value) || 100.4172;

        const map = L.map('mapLokasi').setView([initialLat, initialLng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
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

        // Selected school icons
        const pilihan1Icon = L.divIcon({
            className: 'custom-marker',
            html: '<div style="background: #EF4444; width: 24px; height: 24px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 14px;">1</div>',
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });

        const pilihan2Icon = L.divIcon({
            className: 'custom-marker',
            html: '<div style="background: #F59E0B; width: 24px; height: 24px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 14px;">2</div>',
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });

        let userMarker = null;
        let routeLine1 = null;
        let routeLine2 = null;

        // Add all SMK markers
        smkData.forEach(smk => {
            if (smk.lat && smk.lng) {
                let icon = schoolIcon;

                // Use special icon for selected schools
                if (smk.id == selectedSchool1) {
                    icon = pilihan1Icon;
                } else if (smk.id == selectedSchool2) {
                    icon = pilihan2Icon;
                }

                L.marker([smk.lat, smk.lng], {
                        icon: icon
                    })
                    .addTo(map)
                    .bindPopup('<strong>' + smk.nama + '</strong>');
            }
        });

        // Add user marker if exists
        if (document.getElementById('inputLat').value && document.getElementById('inputLng').value) {
            addUserMarker(initialLat, initialLng);
        }

        function addUserMarker(lat, lng) {
            if (userMarker) map.removeLayer(userMarker);

            userMarker = L.marker([lat, lng], {
                    icon: userIcon,
                    draggable: true
                })
                .addTo(map)
                .bindPopup('<strong>Lokasi Rumah Anda</strong>')
                .openPopup();

            userMarker.on('dragend', function(e) {
                const pos = e.target.getLatLng();
                document.getElementById('inputLat').value = pos.lat.toFixed(8);
                document.getElementById('inputLng').value = pos.lng.toFixed(8);
                calculateDistances(pos.lat, pos.lng);
            });

            calculateDistances(lat, lng);
        }

        // Map click handler
        map.on('click', function(e) {
            document.getElementById('inputLat').value = e.latlng.lat.toFixed(8);
            document.getElementById('inputLng').value = e.latlng.lng.toFixed(8);
            addUserMarker(e.latlng.lat, e.latlng.lng);
        });

        // Get location button
        document.getElementById('btnGetLocation').addEventListener('click', function() {
            if (navigator.geolocation) {
                this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Mendeteksi...';
                navigator.geolocation.getCurrentPosition(
                    pos => {
                        const lat = pos.coords.latitude;
                        const lng = pos.coords.longitude;

                        document.getElementById('inputLat').value = lat.toFixed(8);
                        document.getElementById('inputLng').value = lng.toFixed(8);

                        map.setView([lat, lng], 15);
                        addUserMarker(lat, lng);

                        this.innerHTML = '<i class="bi bi-check-lg me-2"></i>Terdeteksi!';
                        setTimeout(() => {
                            this.innerHTML = '<i class="bi bi-crosshair me-2"></i>Deteksi Lokasi';
                        }, 2000);
                    },
                    err => {
                        alert('Gagal mendeteksi lokasi: ' + err.message);
                        this.innerHTML = '<i class="bi bi-crosshair me-2"></i>Deteksi Lokasi';
                    }
                );
            }
        });

        // Calculate road distance to both schools
        async function calculateDistances(userLat, userLng) {
            const distInfo = document.getElementById('distanceInfo');
            if (!distInfo) return;

            // Clear old routes
            if (routeLine1) map.removeLayer(routeLine1);
            if (routeLine2) map.removeLayer(routeLine2);

            distInfo.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menghitung jarak...';

            let results = [];
            const allBounds = [];

            // Calculate for pilihan 1
            if (selectedSchool1) {
                const school1 = smkData.find(s => s.id == selectedSchool1);
                if (school1 && school1.lat && school1.lng) {
                    const result1 = await calculateRouteDistance(userLat, userLng, school1, '#EF4444', 1);
                    if (result1) {
                        results.push(result1);
                        if (result1.line) allBounds.push(...result1.line.getLatLngs());
                    }
                }
            }

            // Calculate for pilihan 2
            if (selectedSchool2 && selectedSchool2 != selectedSchool1) {
                const school2 = smkData.find(s => s.id == selectedSchool2);
                if (school2 && school2.lat && school2.lng) {
                    const result2 = await calculateRouteDistance(userLat, userLng, school2, '#F59E0B', 2);
                    if (result2) {
                        results.push(result2);
                        if (result2.line) allBounds.push(...result2.line.getLatLngs());
                    }
                }
            }

            // Update display
            if (results.length > 0) {
                let html = '<div class="row g-2">';
                results.forEach((r, i) => {
                    const color = i === 0 ? '#EF4444' : '#F59E0B';
                    const label = i === 0 ? 'Pilihan 1' : 'Pilihan 2';
                    html += `<div class="col-md-6">
                    <div class="p-2 rounded" style="background: ${color}15; border-left: 4px solid ${color};">
                        <small class="text-muted">${label}</small>
                        <div class="fw-semibold small">${r.nama}</div>
                        <div class="small">
                            <i class="bi bi-signpost-2 me-1"></i>${r.distance} • 
                            <i class="bi bi-clock me-1"></i>${r.duration}
                        </div>
                    </div>
                </div>`;
                });
                html += '</div>';
                distInfo.innerHTML = html;

                // Fit bounds to show all routes
                if (allBounds.length > 0) {
                    map.fitBounds(L.latLngBounds(allBounds), {
                        padding: [30, 30]
                    });
                }
            } else {
                distInfo.innerHTML = '<span class="text-muted">Belum ada sekolah pilihan</span>';
            }
        }

        async function calculateRouteDistance(userLat, userLng, school, color, num) {
            try {
                const url = `https://router.project-osrm.org/route/v1/driving/${userLng},${userLat};${school.lng},${school.lat}?overview=full&geometries=geojson`;
                const response = await fetch(url);
                const data = await response.json();

                if (data.routes && data.routes[0]) {
                    const distance = data.routes[0].distance;
                    const duration = data.routes[0].duration;

                    const distanceText = distance >= 1000 ? (distance / 1000).toFixed(2) + ' km' : Math.round(distance) + ' m';
                    const minutes = Math.round(duration / 60);
                    const durationText = minutes >= 60 ? Math.floor(minutes / 60) + 'j ' + (minutes % 60) + 'm' : minutes + ' menit';

                    let line = null;
                    if (data.routes[0].geometry) {
                        const coords = data.routes[0].geometry.coordinates.map(c => [c[1], c[0]]);
                        line = L.polyline(coords, {
                            color: color,
                            weight: 4,
                            opacity: 0.8
                        }).addTo(map);
                        if (num === 1) routeLine1 = line;
                        else routeLine2 = line;
                    }

                    return {
                        nama: school.nama,
                        distance: distanceText,
                        duration: durationText,
                        line: line
                    };
                }
            } catch (error) {
                // Fallback Haversine
                const R = 6371000;
                const dLat = (school.lat - userLat) * Math.PI / 180;
                const dLng = (school.lng - userLng) * Math.PI / 180;
                const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) + Math.cos(userLat * Math.PI / 180) * Math.cos(school.lat * Math.PI / 180) * Math.sin(dLng / 2) * Math.sin(dLng / 2);
                const distance = R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                const distanceText = distance >= 1000 ? (distance / 1000).toFixed(2) + ' km' : Math.round(distance) + ' m';
                return {
                    nama: school.nama,
                    distance: distanceText,
                    duration: '-',
                    line: null
                };
            }
            return null;
        }

        // ========================================
        // CASCADING LOCATION DROPDOWNS
        // ========================================
        const selectKota = document.getElementById('selectKota');
        const selectKecamatan = document.getElementById('selectKecamatan');
        const selectKelurahan = document.getElementById('selectKelurahan');
        const inputKotaNama = document.getElementById('inputKotaNama');
        const inputKecamatanKode = document.getElementById('inputKecamatanKode');

        // Saved values for pre-selection
        const savedKota = '<?= htmlspecialchars($siswa['kota_kabupaten'] ?? '') ?>';
        const savedKecamatan = '<?= htmlspecialchars($siswa['kecamatan'] ?? '') ?>';
        const savedKelurahan = '<?= htmlspecialchars($siswa['kelurahan'] ?? '') ?>';

        // Load Kabupaten/Kota
        loadKabupaten();

        async function loadKabupaten() {
            try {
                const response = await fetch('<?= SITE_URL ?>/api/get-kabupaten.php');
                const result = await response.json();

                if (result.success) {
                    selectKota.innerHTML = '<option value="">-- Pilih Kota/Kabupaten --</option>';
                    result.data.forEach(item => {
                        const selected = item.name === savedKota ? 'selected' : '';
                        selectKota.innerHTML += `<option value="${item.code}" data-name="${item.name}" ${selected}>${item.name}</option>`;
                    });

                    // Trigger kecamatan load if already has saved value
                    if (savedKota && selectKota.value) {
                        loadKecamatan(selectKota.value);
                    }
                }
            } catch (error) {
                console.error('Error loading kabupaten:', error);
            }
        }

        selectKota.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            inputKotaNama.value = selectedOption.dataset.name || '';

            // Reset kecamatan and kelurahan
            selectKecamatan.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
            selectKelurahan.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
            selectKecamatan.disabled = true;
            selectKelurahan.disabled = true;

            if (this.value) {
                loadKecamatan(this.value);
            }
        });

        async function loadKecamatan(kodeKabupaten) {
            selectKecamatan.innerHTML = '<option value="">Memuat...</option>';
            selectKecamatan.disabled = true;

            try {
                const response = await fetch('<?= SITE_URL ?>/api/get-kecamatan.php?kode=' + kodeKabupaten);
                const result = await response.json();

                if (result.success) {
                    selectKecamatan.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                    result.data.forEach(item => {
                        const selected = item.name === savedKecamatan ? 'selected' : '';
                        selectKecamatan.innerHTML += `<option value="${item.code}" data-name="${item.name}" ${selected}>${item.name}</option>`;
                    });
                    selectKecamatan.disabled = false;

                    // Trigger kelurahan load if already has saved value
                    if (savedKecamatan && selectKecamatan.value) {
                        loadKelurahan(selectKecamatan.value);
                    }
                }
            } catch (error) {
                console.error('Error loading kecamatan:', error);
                selectKecamatan.innerHTML = '<option value="">Gagal memuat</option>';
            }
        }

        selectKecamatan.addEventListener('change', function() {
            inputKecamatanKode.value = this.value;

            // Reset kelurahan
            selectKelurahan.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
            selectKelurahan.disabled = true;

            if (this.value) {
                loadKelurahan(this.value);
            }
        });

        async function loadKelurahan(kodeKecamatan) {
            selectKelurahan.innerHTML = '<option value="">Memuat...</option>';
            selectKelurahan.disabled = true;

            try {
                const response = await fetch('<?= SITE_URL ?>/api/get-kelurahan.php?kode=' + kodeKecamatan);
                const result = await response.json();

                if (result.success) {
                    selectKelurahan.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
                    result.data.forEach(item => {
                        const selected = item.name === savedKelurahan ? 'selected' : '';
                        selectKelurahan.innerHTML += `<option value="${item.name}" ${selected}>${item.name}</option>`;
                    });
                    selectKelurahan.disabled = false;
                }
            } catch (error) {
                console.error('Error loading kelurahan:', error);
                selectKelurahan.innerHTML = '<option value="">Gagal memuat</option>';
            }
        }
    });
</script>

<?php require_once 'includes/footer.php'; ?>