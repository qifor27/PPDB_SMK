/**
 * PPDB SMK - Leaflet Map Integration
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
        return this.getNearbySchools();
    }
    
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
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                  Math.cos(lat1 * Math.PI/180) * Math.cos(lat2 * Math.PI/180) *
                  Math.sin(dLon/2) * Math.sin(dLon/2);
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
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
window.formatDistance = m => m < 1000 ? Math.round(m) + ' m' : (m/1000).toFixed(2) + ' km';
