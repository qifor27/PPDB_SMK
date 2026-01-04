// Data SMK dan Radius Zonasi harus didefinisikan sebelum file ini di-load
// const smkData = ...;
// const radiusZonasi = ...;

let map, userMarker, userCircle;
const schoolMarkers = [];
let routeLines = [];
let highlightCircles = [];

document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('mapLeaflet')) {
        initMap();
    }

    // Detect location button
    const btnDetect = document.getElementById('btnDetectLocation');
    const locationStatus = document.getElementById('locationStatus');
    const nearbySchools = document.getElementById('nearbySchools');

    if (btnDetect) {
        btnDetect.addEventListener('click', function () {
            if (navigator.geolocation) {
                this.disabled = true;
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mendeteksi...';

                if (locationStatus) {
                    locationStatus.classList.remove('d-none');
                    locationStatus.className = 'alert alert-info small';
                    locationStatus.innerHTML = '<i class="bi bi-info-circle me-1"></i>Mendeteksi lokasi dan menghitung jarak...';
                }

                navigator.geolocation.getCurrentPosition(
                    pos => {
                        const lat = pos.coords.latitude;
                        const lng = pos.coords.longitude;
                        if (map) {
                            map.setView([lat, lng], 14);
                            addUserMarker(lat, lng);
                        }
                        updateNearestSchools(lat, lng);

                        this.disabled = false;
                        this.innerHTML = '<i class="bi bi-geo-alt-fill me-2"></i>Deteksi Lokasi Saya';
                    },
                    err => {
                        if (locationStatus) {
                            locationStatus.className = 'alert alert-danger small';
                            locationStatus.innerHTML = '<i class="bi bi-exclamation-circle me-1"></i>Gagal: ' + err.message;
                        }
                        this.disabled = false;
                        this.innerHTML = '<i class="bi bi-geo-alt-fill me-2"></i>Deteksi Lokasi Saya';
                    }
                );
            } else {
                alert('Geolocation tidak didukung browser Anda');
            }
        });
    }
});

function initMap() {
    map = L.map('mapLeaflet').setView([-0.9471, 100.4172], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Custom school icon
    const schoolIcon = L.divIcon({
        className: 'custom-marker',
        html: '<div style="background: #10B981; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;"><i class="bi bi-building" style="font-size: 10px; color: white;"></i></div>',
        iconSize: [20, 20],
        iconAnchor: [10, 10]
    });

    // Tambahkan marker untuk semua SMK
    if (typeof smkData !== 'undefined') {
        smkData.forEach(smk => {
            const lat = parseFloat(smk.latitude);
            const lng = parseFloat(smk.longitude);
            if (lat && lng) {
                const marker = L.marker([lat, lng], {
                    icon: schoolIcon
                })
                    .addTo(map)
                    .bindPopup('<strong>' + smk.nama_sekolah + '</strong><br><small>' + (smk.alamat || '') + '</small>');
                marker.smkData = smk;
                schoolMarkers.push(marker);
            }
        });
    }
}

function addUserMarker(lat, lng) {
    if (userMarker) map.removeLayer(userMarker);
    if (userCircle) map.removeLayer(userCircle);

    const userIcon = L.divIcon({
        className: 'custom-marker',
        html: '<div style="background: #EF4444; width: 24px; height: 24px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.4);"></div>',
        iconSize: [24, 24],
        iconAnchor: [12, 12]
    });

    userMarker = L.marker([lat, lng], {
        icon: userIcon
    })
        .addTo(map)
        .bindPopup('<strong>Lokasi Anda</strong>')
        .openPopup();

    if (typeof radiusZonasi !== 'undefined') {
        userCircle = L.circle([lat, lng], {
            radius: radiusZonasi,
            color: '#10B981',
            fillOpacity: 0.1
        }).addTo(map);
    }
}

// Hitung jarak darat menggunakan OSRM
async function getRoadDistance(lat1, lng1, lat2, lng2) {
    try {
        const url = 'https://router.project-osrm.org/route/v1/driving/' + lng1 + ',' + lat1 + ';' + lng2 + ',' + lat2 + '?overview=false';
        const response = await fetch(url);
        const data = await response.json();

        if (data.routes && data.routes[0]) {
            return {
                distance: data.routes[0].distance,
                duration: data.routes[0].duration,
                success: true
            };
        }
    } catch (error) {
        console.log('OSRM error, using Haversine fallback');
    }

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
    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
        Math.sin(dLng / 2) * Math.sin(dLng / 2);
    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
}

function formatDistance(meters) {
    if (meters >= 1000) return (meters / 1000).toFixed(2) + ' km';
    return Math.round(meters) + ' m';
}

function formatDuration(seconds) {
    if (!seconds) return '-';
    const minutes = Math.round(seconds / 60);
    if (minutes >= 60) {
        return Math.floor(minutes / 60) + ' jam ' + (minutes % 60) + ' mnt';
    }
    return minutes + ' menit';
}

function clearMapHighlights() {
    routeLines.forEach(line => map.removeLayer(line));
    routeLines = [];
    highlightCircles.forEach(circle => map.removeLayer(circle));
    highlightCircles = [];

    // Reset marker SMK ke default
    const defaultIcon = L.divIcon({
        className: 'custom-marker',
        html: '<div style="background: #10B981; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;"><i class="bi bi-building" style="font-size: 10px; color: white;"></i></div>',
        iconSize: [20, 20],
        iconAnchor: [10, 10]
    });
    schoolMarkers.forEach(m => m.setIcon(defaultIcon));
}

// Update daftar SMK terdekat
async function updateNearestSchools(userLat, userLng) {
    const locationStatus = document.getElementById('locationStatus');
    const nearbySchools = document.getElementById('nearbySchools');

    if (nearbySchools) {
        nearbySchools.innerHTML = '<div class="text-center py-3"><span class="spinner-border spinner-border-sm"></span> Menghitung jarak darat...</div>';
    }

    clearMapHighlights();

    // Hitung jarak ke semua SMK
    const distances = [];
    if (typeof smkData !== 'undefined') {
        for (const smk of smkData) {
            const lat = parseFloat(smk.latitude);
            const lng = parseFloat(smk.longitude);
            if (lat && lng) {
                const result = await getRoadDistance(userLat, userLng, lat, lng);
                distances.push({
                    ...smk,
                    lat: lat,
                    lng: lng,
                    distance: result.distance,
                    duration: result.duration,
                    isRoadDistance: result.success
                });
            }
        }
    }

    distances.sort((a, b) => a.distance - b.distance);
    const nearest = distances.slice(0, 2);

    // Update status
    if (distances.length > 0 && locationStatus) {
        const isRoad = distances[0].isRoadDistance;
        locationStatus.className = isRoad ? 'alert alert-success small' : 'alert alert-warning small';
        locationStatus.innerHTML = isRoad ?
            '<i class="bi bi-car-front me-1"></i>Jarak via jalur darat (OSRM)' :
            '<i class="bi bi-geo me-1"></i>Jarak garis lurus (fallback)';
    }

    // Highlight dan gambar garis ke 2 SMK terdekat
    await highlightNearestOnMap(userLat, userLng, nearest);

    // Render daftar SMK
    if (nearbySchools) {
        nearbySchools.innerHTML = distances.slice(0, 5).map((smk, i) => `
            <div class="d-flex align-items-center justify-content-between py-2 ${i < 4 ? 'border-bottom border-light' : ''}">
                <div>
                    <div class="fw-semibold small">${i < 2 ? '<span class="badge bg-' + (i === 0 ? 'danger' : 'warning') + ' me-1">#' + (i + 1) + '</span>' : ''}${smk.nama_sekolah}</div>
                    <small class="text-muted">
                        <i class="bi bi-signpost-2 me-1"></i>${formatDistance(smk.distance)}
                        ${smk.duration ? ' <i class="bi bi-clock ms-1 me-1"></i>' + formatDuration(smk.duration) : ''}
                    </small>
                </div>
                <span class="badge ${smk.distance <= (typeof radiusZonasi !== 'undefined' ? radiusZonasi : 0) ? 'bg-success' : 'bg-secondary'}">${smk.distance <= (typeof radiusZonasi !== 'undefined' ? radiusZonasi : 0) ? 'Dalam Radius' : 'Luar Radius'}</span>
            </div>
        `).join('');
    }

    // Fit bounds
    if (nearest.length > 0) {
        const bounds = L.latLngBounds([
            [userLat, userLng], ...nearest.map(s => [s.lat, s.lng])
        ]);
        map.fitBounds(bounds, {
            padding: [50, 50]
        });
    }
}

// Highlight dan gambar garis ke SMK terdekat
async function highlightNearestOnMap(userLat, userLng, nearestSchools) {
    const colors = [{
        bg: '#EF4444',
        glow: 'rgba(239, 68, 68, 0.3)'
    },
    {
        bg: '#F97316',
        glow: 'rgba(249, 115, 22, 0.3)'
    }
    ];

    for (const [index, smk] of nearestSchools.entries()) {
        const color = colors[index];

        // Lingkaran glow
        const glowCircle = L.circle([smk.lat, smk.lng], {
            radius: 150,
            color: color.bg,
            fillColor: color.glow,
            fillOpacity: 0.4,
            weight: 2
        }).addTo(map);
        highlightCircles.push(glowCircle);

        // Marker highlight
        const highlightIcon = L.divIcon({
            className: 'highlight-marker',
            html: '<div style="background: ' + color.bg + '; width: 32px; height: 32px; border-radius: 50%; border: 3px solid white; box-shadow: 0 4px 12px rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">' + (index + 1) + '</div>',
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        });

        schoolMarkers.forEach(marker => {
            if (marker.smkData && marker.smkData.id_smk === smk.id_smk) {
                marker.setIcon(highlightIcon);
                marker.bindPopup(
                    '<div style="text-align: center;"><div style="background: ' + color.bg + '; color: white; padding: 5px 10px; border-radius: 5px; margin-bottom: 8px;"><strong>#' + (index + 1) + ' Terdekat</strong></div>' +
                    '<strong>' + smk.nama_sekolah + '</strong><br><small>📍 ' + formatDistance(smk.distance) + '</small>' +
                    (smk.duration ? '<br><small>⏱️ ' + formatDuration(smk.duration) + '</small>' : '') + '</div>'
                );
            }
        });

        // Gambar garis rute
        await drawRoadRoute(userLat, userLng, smk.lat, smk.lng, color.bg, index);
    }
}

// Gambar garis jalur darat
async function drawRoadRoute(lat1, lng1, lat2, lng2, lineColor, index) {
    try {
        const url = 'https://router.project-osrm.org/route/v1/driving/' + lng1 + ',' + lat1 + ';' + lng2 + ',' + lat2 + '?overview=full&geometries=geojson';
        const response = await fetch(url);
        const data = await response.json();

        if (data.routes && data.routes[0] && data.routes[0].geometry) {
            const coords = data.routes[0].geometry.coordinates;
            const latLngs = coords.map(c => [c[1], c[0]]);

            const routeLine = L.polyline(latLngs, {
                color: lineColor,
                weight: index === 0 ? 5 : 4,
                opacity: index === 0 ? 0.9 : 0.7,
                lineCap: 'round',
                lineJoin: 'round'
            }).addTo(map);
            routeLines.push(routeLine);
            return;
        }
    } catch (error) {
        console.log('OSRM route error');
    }

    // Fallback garis lurus putus-putus
    const routeLine = L.polyline([
        [lat1, lng1],
        [lat2, lng2]
    ], {
        color: lineColor,
        weight: index === 0 ? 4 : 3,
        opacity: 0.7,
        dashArray: '10, 10'
    }).addTo(map);
    routeLines.push(routeLine);
}
