/**
 * PPDB SMK - Leaflet Map Integration with OSRM Road Distance
 * Updated to use road distance calculation (jalur darat) via OSRM API
 */

const MAP_CONFIG = {
    center: [-0.9471, 100.4172],
    zoom: 12,
    minZoom: 10,
    maxZoom: 18,
    radiusZonasi: 2000
};

class PPDBMap {
    constructor(containerId, options = {}) {
        this.containerId = containerId;
        this.options = { ...MAP_CONFIG, ...options };
        this.map = null;
        this.markers = [];
        this.schoolMarkers = {};
        this.userMarker = null;
        this.userCircle = null;
        this.userPosition = null;
        this.routeLines = [];
        this.highlightCircles = [];
        this.onSchoolSelect = options.onSchoolSelect || null;
        this.init();
    }

    init() {
        this.map = L.map(this.containerId, {
            center: this.options.center,
            zoom: this.options.zoom
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(this.map);

        this.addStyles();
        if (this.options.schools) this.loadSchools(this.options.schools);
    }

    addStyles() {
        if (document.getElementById('map-styles')) return;
        const style = document.createElement('style');
        style.id = 'map-styles';
        style.innerHTML = `
            .marker-pin{width:36px;height:36px;border-radius:50% 50% 50% 0;background:#10B981;position:absolute;transform:rotate(-45deg);left:50%;top:50%;margin:-20px 0 0 -18px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 10px rgba(0,0,0,0.3)}
            .marker-pin i{transform:rotate(45deg);font-size:16px;color:white}
            .marker-pin.user{background:#EF4444;animation:pulse 2s infinite}
            .marker-pin.highlight-1{background:#EF4444;width:42px;height:42px}
            .marker-pin.highlight-2{background:#F97316;width:40px;height:40px}
            @keyframes pulse{0%{box-shadow:0 0 0 0 rgba(239,68,68,0.7)}70%{box-shadow:0 0 0 20px rgba(239,68,68,0)}100%{box-shadow:0 0 0 0 rgba(239,68,68,0)}}
        `;
        document.head.appendChild(style);
    }

    loadSchools(schools) {
        schools.forEach(s => this.addSchoolMarker(s));
    }

    addSchoolMarker(school) {
        const lat = parseFloat(school.latitude);
        const lng = parseFloat(school.longitude);
        if (isNaN(lat) || isNaN(lng)) return;

        const icon = L.divIcon({
            className: 'marker-school',
            html: '<div class="marker-pin"><i class="bi bi-building"></i></div>',
            iconSize: [40, 40],
            iconAnchor: [20, 40]
        });

        const marker = L.marker([lat, lng], { icon });
        marker.schoolData = school;
        marker.bindPopup(`<b>${school.nama_sekolah}</b><br>${school.alamat || ''}`);
        marker.on('click', () => this.onSchoolSelect && this.onSchoolSelect(school));
        marker.addTo(this.map);
        this.markers.push(marker);
        this.schoolMarkers[school.id_smk] = marker;
    }

    // ========================================
    // OSRM ROAD DISTANCE CALCULATION
    // ========================================

    async getRoadDistance(lat1, lng1, lat2, lng2) {
        try {
            const url = `https://router.project-osrm.org/route/v1/driving/${lng1},${lat1};${lng2},${lat2}?overview=false`;
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
            distance: this.calcDistance(lat1, lng1, lat2, lng2),
            duration: null,
            success: false
        };
    }

    // ========================================
    // DRAW ROAD ROUTE LINE
    // ========================================

    async drawRoadRoute(lat1, lng1, lat2, lng2, lineColor, index = 0) {
        console.log('Drawing route from', [lat1, lng1], 'to', [lat2, lng2]);

        try {
            const url = `https://router.project-osrm.org/route/v1/driving/${lng1},${lat1};${lng2},${lat2}?overview=full&geometries=geojson`;
            const response = await fetch(url);
            const data = await response.json();

            if (data.code === 'Ok' && data.routes && data.routes[0] && data.routes[0].geometry) {
                const coordinates = data.routes[0].geometry.coordinates;
                const latLngs = coordinates.map(coord => [coord[1], coord[0]]);
                console.log('OSRM route success, drawing', latLngs.length, 'points');

                const routeLine = L.polyline(latLngs, {
                    color: lineColor,
                    weight: index === 0 ? 5 : 4,
                    opacity: index === 0 ? 0.9 : 0.7,
                    lineCap: 'round',
                    lineJoin: 'round'
                }).addTo(this.map);

                this.routeLines.push(routeLine);
                return true;
            } else {
                console.log('OSRM no valid route, falling back to straight line');
                this.drawStraightLine(lat1, lng1, lat2, lng2, lineColor, index);
                return false;
            }
        } catch (error) {
            console.log('OSRM error:', error.message, '- using straight line');
            this.drawStraightLine(lat1, lng1, lat2, lng2, lineColor, index);
            return false;
        }
    }

    drawStraightLine(lat1, lng1, lat2, lng2, lineColor, index = 0) {
        console.log('Drawing straight line from', [lat1, lng1], 'to', [lat2, lng2]);

        const routeLine = L.polyline(
            [[lat1, lng1], [lat2, lng2]],
            {
                color: lineColor,
                weight: index === 0 ? 4 : 3,
                opacity: index === 0 ? 0.8 : 0.6,
                dashArray: '10, 10'
            }
        ).addTo(this.map);
        this.routeLines.push(routeLine);
        console.log('Straight line added to map');
    }

    clearRoutes() {
        this.routeLines.forEach(line => this.map.removeLayer(line));
        this.routeLines = [];
        this.highlightCircles.forEach(circle => this.map.removeLayer(circle));
        this.highlightCircles = [];
    }

    setUserPosition(lat, lng) {
        this.userPosition = { lat, lng };
        if (this.userMarker) this.map.removeLayer(this.userMarker);
        if (this.userCircle) this.map.removeLayer(this.userCircle);

        const icon = L.divIcon({
            className: 'marker-user',
            html: '<div class="marker-pin user"><i class="bi bi-person-fill"></i></div>',
            iconSize: [40, 40],
            iconAnchor: [20, 40]
        });

        this.userMarker = L.marker([lat, lng], { icon }).addTo(this.map);
        this.userCircle = L.circle([lat, lng], {
            radius: this.options.radiusZonasi,
            color: '#10B981',
            fillOpacity: 0.1
        }).addTo(this.map);

        this.map.setView([lat, lng], 14);
        return this.getNearbySchoolsWithRoadDistance();
    }

    // ========================================
    // GET NEARBY SCHOOLS WITH ROAD DISTANCE
    // ========================================

    async getNearbySchoolsWithRoadDistance() {
        if (!this.userPosition) return [];

        const schoolsWithDistance = [];

        // Calculate road distance for each school
        for (const marker of Object.values(this.schoolMarkers)) {
            const school = marker.schoolData;
            const result = await this.getRoadDistance(
                this.userPosition.lat,
                this.userPosition.lng,
                parseFloat(school.latitude),
                parseFloat(school.longitude)
            );

            schoolsWithDistance.push({
                ...school,
                distance: result.distance,
                duration: result.duration,
                isRoadDistance: result.success
            });
        }

        // Sort by distance
        schoolsWithDistance.sort((a, b) => a.distance - b.distance);

        // Draw routes to nearest 2 schools
        this.clearRoutes();
        const colors = ['#EF4444', '#F97316'];
        const nearest = schoolsWithDistance.slice(0, 2);

        for (let i = 0; i < nearest.length; i++) {
            const school = nearest[i];

            // Add highlight circle
            const glowCircle = L.circle([school.latitude, school.longitude], {
                radius: 150,
                color: colors[i],
                fillColor: colors[i],
                fillOpacity: 0.3,
                weight: 2
            }).addTo(this.map);
            this.highlightCircles.push(glowCircle);

            // Draw route
            await this.drawRoadRoute(
                this.userPosition.lat,
                this.userPosition.lng,
                parseFloat(school.latitude),
                parseFloat(school.longitude),
                colors[i],
                i
            );
        }

        // Fit bounds
        if (nearest.length > 0) {
            const bounds = L.latLngBounds([
                [this.userPosition.lat, this.userPosition.lng],
                ...nearest.map(s => [parseFloat(s.latitude), parseFloat(s.longitude)])
            ]);
            this.map.fitBounds(bounds, { padding: [50, 50] });
        }

        return schoolsWithDistance;
    }

    // Legacy method for backward compatibility
    getNearbySchools(radius) {
        if (!this.userPosition) return [];
        radius = radius || this.options.radiusZonasi;
        const nearby = [];

        Object.values(this.schoolMarkers).forEach(m => {
            const dist = this.calcDistance(
                this.userPosition.lat, this.userPosition.lng,
                parseFloat(m.schoolData.latitude), parseFloat(m.schoolData.longitude)
            );
            m.schoolData.distance = dist;
            if (dist <= radius) nearby.push(m.schoolData);
        });

        return nearby.sort((a, b) => a.distance - b.distance);
    }

    calcDistance(lat1, lon1, lat2, lon2) {
        const R = 6371000;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    }

    getCurrentLocation() {
        return new Promise((resolve, reject) => {
            if (!navigator.geolocation) {
                reject(new Error('Geolocation tidak didukung'));
                return;
            }
            navigator.geolocation.getCurrentPosition(
                pos => resolve(this.setUserPosition(pos.coords.latitude, pos.coords.longitude)),
                err => reject(new Error('Gagal mendapatkan lokasi'))
            );
        });
    }
}

window.PPDBMap = PPDBMap;
window.formatDistance = m => m < 1000 ? Math.round(m) + ' m' : (m / 1000).toFixed(2) + ' km';
window.formatDuration = seconds => {
    if (!seconds) return '-';
    const minutes = Math.round(seconds / 60);
    if (minutes >= 60) {
        const hours = Math.floor(minutes / 60);
        const mins = minutes % 60;
        return hours + ' jam ' + mins + ' menit';
    }
    return minutes + ' menit';
};
