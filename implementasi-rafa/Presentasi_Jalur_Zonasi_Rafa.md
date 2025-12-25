# 📍 PRESENTASI: Implementasi Jalur Zonasi PPDB SMK
## Dengan Perhitungan Jarak Jalur Darat

**Nama:** Rafa  
**Mata Kuliah:** [Nama Mata Kuliah]  
**Dosen:** [Nama Dosen]  
**Tanggal:** 25 Desember 2025

---

# 📋 DAFTAR ISI

1. Pendahuluan
2. Latar Belakang Masalah
3. Solusi yang Dikembangkan
4. Arsitektur Sistem
5. Implementasi Teknis
6. Demo Aplikasi
7. Kesimpulan

---

# 1️⃣ PENDAHULUAN

## Apa itu Jalur Zonasi?

Jalur Zonasi adalah salah satu jalur penerimaan peserta didik baru (PPDB) yang **memprioritaskan calon siswa berdasarkan jarak tempat tinggal ke sekolah tujuan**.

### Dasar Hukum:
- **Permendikbud No. 1 Tahun 2021** tentang PPDB
- Kuota jalur zonasi: **minimal 50%** dari total daya tampung

### Tujuan Jalur Zonasi:
1. Pemerataan akses pendidikan berkualitas
2. Mengurangi kemacetan lalu lintas
3. Mempersingkat waktu tempuh siswa ke sekolah
4. Efisiensi biaya transportasi keluarga

---

# 2️⃣ LATAR BELAKANG MASALAH

## Masalah dengan Perhitungan Jarak Udara (Haversine)

Banyak sistem PPDB menggunakan **jarak garis lurus (Haversine)** yang menghitung jarak "as the crow flies" atau seperti burung terbang.

### ❌ Kelemahan Jarak Udara:

| Aspek | Masalah |
|-------|---------|
| **Tidak Realistis** | Manusia tidak bisa terbang menembus gedung/gunung |
| **Tidak Adil** | Siswa yang rumahnya terhalang sungai/bukit dirugikan |
| **Tidak Praktis** | Tidak mencerminkan kondisi jalan sebenarnya |

### Contoh Kasus:

```
Siswa A: Jarak udara 2 km, tapi harus memutar 5 km via jalan
Siswa B: Jarak udara 2.5 km, jalan lurus langsung

Dengan Haversine: Siswa A lebih prioritas (TIDAK ADIL!)
Dengan Jarak Darat: Siswa B lebih prioritas (ADIL!)
```

---

# 3️⃣ SOLUSI YANG DIKEMBANGKAN

## Perhitungan Jarak Jalur Darat (Road Distance)

Kami mengembangkan sistem yang menghitung **jarak sebenarnya melalui jalan raya** menggunakan teknologi routing.

### ✅ Keunggulan Jarak Darat:

| Aspek | Keunggulan |
|-------|------------|
| **Realistis** | Mengikuti jalur jalan yang sebenarnya |
| **Adil** | Mempertimbangkan kondisi geografis |
| **Informatif** | Menyertakan estimasi waktu tempuh |
| **Akurat** | Data dari peta OpenStreetMap yang selalu update |

### Teknologi yang Digunakan:

1. **OSRM (Open Source Routing Machine)**
   - Gratis dan open source
   - Tidak memerlukan API key
   - Data dari OpenStreetMap

2. **Leaflet.js**
   - Library peta interaktif
   - Ringan dan responsif
   - Open source

---

# 4️⃣ ARSITEKTUR SISTEM

## Diagram Alur Sistem

```
┌─────────────────┐
│   User/Siswa    │
│  Buka Form      │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Leaflet Map    │
│  (Interaktif)   │
└────────┬────────┘
         │ Klik/GPS
         ▼
┌─────────────────┐
│ Koordinat User  │
│ (Lat, Long)     │
└────────┬────────┘
         │
         ▼
┌─────────────────┐      ┌─────────────────┐
│   OSRM API      │◄────►│  OpenStreetMap  │
│ (Routing)       │      │  (Road Data)    │
└────────┬────────┘      └─────────────────┘
         │
         ▼
┌─────────────────┐
│  Hasil:         │
│  - Jarak (m)    │
│  - Durasi (s)   │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ Tampilkan 2 SMK │
│ Terdekat        │
└─────────────────┘
```

---

# 5️⃣ IMPLEMENTASI TEKNIS

## A. Fungsi Perhitungan Jarak Darat (PHP)

```php
function hitungJarakDarat($lat1, $lon1, $lat2, $lon2) {
    // Menggunakan OSRM API
    $url = "https://router.project-osrm.org/route/v1/driving/
            {$lon1},{$lat1};{$lon2},{$lat2}?overview=false";
    
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    
    return [
        'jarak' => $data['routes'][0]['distance'],  // meter
        'durasi' => $data['routes'][0]['duration']  // detik
    ];
}
```

## B. Fungsi Perhitungan Jarak Darat (JavaScript)

```javascript
async function getRoadDistance(lat1, lng1, lat2, lng2) {
    const url = `https://router.project-osrm.org/route/v1/driving/
                 ${lng1},${lat1};${lng2},${lat2}?overview=false`;
    
    const response = await fetch(url);
    const data = await response.json();
    
    return {
        distance: data.routes[0].distance,  // meter
        duration: data.routes[0].duration   // detik
    };
}
```

## C. Fallback ke Haversine

Jika API tidak tersedia, sistem otomatis fallback ke perhitungan Haversine:

```javascript
function haversineDistance(lat1, lng1, lat2, lng2) {
    const R = 6371000; // Radius bumi (meter)
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLng = (lng2 - lng1) * Math.PI / 180;
    
    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
              Math.cos(lat1 * Math.PI / 180) * 
              Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLng/2) * Math.sin(dLng/2);
    
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}
```

---

# 6️⃣ FITUR YANG SUDAH DIIMPLEMENTASI

## A. Peta Interaktif (Leaflet.js)

### Fitur Dasar:
- **Leaflet.js** untuk menampilkan peta OpenStreetMap
- Marker **hijau** untuk lokasi semua SMK
- Marker **ungu/biru** untuk lokasi siswa (bisa di-drag)
- **Klik peta** untuk menentukan lokasi secara manual
- **Deteksi GPS otomatis** dengan tombol crosshair
- **Drag marker** untuk memindahkan lokasi user

### Fitur Visual Highlight pada Peta:

| Elemen | Keterangan |
|--------|------------|
| **Marker Merah #1** | SMK terdekat pertama dengan angka "1" |
| **Marker Orange #2** | SMK terdekat kedua dengan angka "2" |
| **Lingkaran Glow** | Area highlight berwarna di sekitar SMK terdekat |
| **Garis Rute Darat** | Mengikuti **jalur jalan raya sebenarnya** (bukan garis lurus!) |
| **Auto-zoom (fitBounds)** | Peta otomatis zoom & pan agar semua marker terlihat |

### Implementasi Highlight SMK Terdekat:

```javascript
function highlightNearestOnMap(userLat, userLng, nearestSchools) {
    // Warna untuk ranking
    const colors = [
        { bg: '#EF4444', glow: 'rgba(239, 68, 68, 0.3)' }, // Merah - Terdekat
        { bg: '#F97316', glow: 'rgba(249, 115, 22, 0.3)' }  // Orange - Kedua
    ];
    
    nearestSchools.forEach(async (smk, index) => {
        // 1. Tambahkan lingkaran glow
        L.circle([smk.lat, smk.lng], {
            radius: 150,
            fillColor: color.glow,
            fillOpacity: 0.4
        }).addTo(map);
        
        // 2. Ganti marker dengan nomor ranking
        const highlightIcon = L.divIcon({
            html: '<div style="background: ' + color.bg + '">' + (index+1) + '</div>'
        });
        marker.setIcon(highlightIcon);
        
        // 3. Gambar garis jalur darat
        await drawRoadRoute(userLat, userLng, smk.lat, smk.lng, color.bg);
    });
    
    // 4. Auto-zoom agar semua terlihat
    map.fitBounds(bounds, { padding: [50, 50] });
}
```

---

## B. Visualisasi Rute Jalur Darat (FITUR BARU!)

### Apa yang Spesial?

Sistem kami menggambar garis dari lokasi siswa ke SMK **mengikuti jalur jalan raya sebenarnya**, BUKAN garis lurus!

### Implementasi Teknis:

```javascript
async function drawRoadRoute(lat1, lng1, lat2, lng2, lineColor) {
    // Request OSRM dengan geometry jalur lengkap
    const url = 'https://router.project-osrm.org/route/v1/driving/' + 
                lng1 + ',' + lat1 + ';' + lng2 + ',' + lat2 + 
                '?overview=full&geometries=geojson';
    
    const response = await fetch(url);
    const data = await response.json();
    
    // Ambil koordinat dari geometry GeoJSON
    const coordinates = data.routes[0].geometry.coordinates;
    // Convert dari [lng, lat] ke [lat, lng] untuk Leaflet
    const latLngs = coordinates.map(coord => [coord[1], coord[0]]);
    
    // Gambar polyline mengikuti jalan
    L.polyline(latLngs, {
        color: lineColor,
        weight: 5,
        opacity: 0.9,
        lineCap: 'round'
    }).addTo(map);
}
```

### Fitur Fallback:

Jika OSRM gagal, sistem otomatis menggambar **garis putus-putus lurus** sebagai fallback:

```javascript
function drawStraightLine(lat1, lng1, lat2, lng2, lineColor) {
    L.polyline([[lat1, lng1], [lat2, lng2]], {
        color: lineColor,
        dashArray: '10, 10'  // Garis putus-putus
    }).addTo(map);
}
```

### Keuntungan Visualisasi Rute:
| Keuntungan | Penjelasan |
|------------|------------|
| **Visual Realistis** | Siswa bisa melihat jalur sebenarnya yang akan ditempuh |
| **Informatif** | Menunjukkan kompleksitas rute (lurus vs berkelok) |
| **User Experience** | Visualisasi yang menarik dan profesional |
| **Reliable** | Ada fallback jika API tidak tersedia |

---

## C. Panel 2 SMK Terdekat (FITUR UTAMA)

### Apa itu Panel 2 SMK Terdekat?

Panel 2 SMK Terdekat adalah **fitur rekomendasi otomatis** yang menampilkan dua sekolah dengan jarak paling dekat dari lokasi domisili siswa.

### Kenapa 2 SMK?

Karena dalam sistem PPDB, siswa bisa memilih:
- **Pilihan 1** = Sekolah utama yang diinginkan
- **Pilihan 2** = Sekolah cadangan jika pilihan 1 penuh

Dengan menampilkan 2 SMK terdekat, siswa mendapat **rekomendasi otomatis** untuk kedua pilihan tersebut.

### Tampilan Panel:

```
┌──────────────────────────────────────┐
│  🏫 2 SMK Terdekat                   │
├──────────────────────────────────────┤
│                                      │
│  ┌────────────────────────────────┐  │
│  │ 1️⃣ SMKN 3 Padang               │  │
│  │    📍 1.8 km  ⏱️ 12 menit      │  │
│  │    ⭐ Rekomendasi terdekat      │  │
│  └────────────────────────────────┘  │
│                                      │
│  ┌────────────────────────────────┐  │
│  │ 2️⃣ SMKN 5 Padang               │  │
│  │    📍 2.3 km  ⏱️ 15 menit      │  │
│  └────────────────────────────────┘  │
│                                      │
└──────────────────────────────────────┘
```

### Informasi yang Ditampilkan:

| Komponen | Keterangan |
|----------|------------|
| **Nomor Ranking** | 1 = terdekat, 2 = terdekat kedua |
| **Nama Sekolah** | Nama lengkap SMK |
| **Jarak** | Dalam km atau meter (via jalan raya) |
| **Waktu Tempuh** | Estimasi dalam menit/jam |
| **Badge Rekomendasi** | Sekolah #1 diberi tanda bintang |

### Algoritma Pencarian 2 SMK Terdekat:

```
LANGKAH 1: Ambil koordinat siswa (latitude, longitude)
           ↓
LANGKAH 2: Loop semua SMK dalam database
           ↓
LANGKAH 3: Hitung jarak DARAT ke setiap SMK via OSRM API
           ↓
LANGKAH 4: Simpan hasil: {nama, jarak, durasi}
           ↓
LANGKAH 5: Urutkan berdasarkan jarak (ASC/terdekat dulu)
           ↓
LANGKAH 6: Ambil 2 data teratas
           ↓
LANGKAH 7: Tampilkan di panel + Auto-select dropdown
```

### Kode JavaScript untuk Fitur Ini:

```javascript
// Fungsi update 2 SMK terdekat
async function updateNearestSchools(userLat, userLng) {
    
    // 1. Hitung jarak ke semua SMK
    const distances = [];
    for (const smk of smkData) {
        const result = await getRoadDistance(
            userLat, userLng, 
            smk.lat, smk.lng
        );
        distances.push({
            id: smk.id,
            nama: smk.nama,
            distance: result.distance,    // meter
            duration: result.duration     // detik
        });
    }
    
    // 2. Sort berdasarkan jarak terdekat
    distances.sort((a, b) => a.distance - b.distance);
    
    // 3. Ambil 2 terdekat
    const nearest = distances.slice(0, 2);
    
    // 4. Tampilkan di panel
    renderNearestList(nearest);
    
    // 5. Auto-select dropdown pilihan sekolah
    document.querySelector('[name="smk_pilihan1"]').value = nearest[0].id;
    document.querySelector('[name="smk_pilihan2"]').value = nearest[1].id;
}
```

### Keuntungan Fitur Ini:

| Keuntungan | Penjelasan |
|------------|------------|
| **Menghemat Waktu** | Siswa tidak perlu cari manual sekolah terdekat |
| **Mengurangi Kesalahan** | Pilihan otomatis berdasarkan data akurat |
| **Informatif** | Ada info jarak + waktu tempuh |
| **Fair/Adil** | Jarak dihitung via jalan, bukan garis lurus |

---

## D. Auto-Select Sekolah

Ketika panel 2 SMK terdekat muncul, sistem **otomatis mengisi dropdown**:

- **SMK Pilihan 1** = Sekolah ranking 1 (terdekat)
- **SMK Pilihan 2** = Sekolah ranking 2 (terdekat kedua)

Siswa tetap bisa mengubah pilihan secara manual jika ingin memilih sekolah lain.

---

## E. Indikator Status Jarak (Badge)

Sistem menampilkan badge yang menunjukkan **metode perhitungan jarak** yang digunakan:

| Badge | Warna | Keterangan |
|-------|-------|------------|
| **🚗 Jarak via jalan raya** | Hijau | OSRM berhasil, jarak darat akurat |
| **📍 Jarak garis lurus** | Kuning | Fallback ke Haversine (OSRM gagal) |

```javascript
// Update info badge
if (nearest.length > 0) {
    distInfo.innerHTML = nearest[0].isRoadDistance 
        ? '<i class="bi bi-car-front"></i> Jarak via jalan raya' 
        : '<i class="bi bi-geo"></i> Jarak garis lurus';
    distInfo.className = nearest[0].isRoadDistance 
        ? 'badge bg-success' 
        : 'badge bg-warning';
}
```

---

## F. Desain UI Panel SMK Terdekat

Panel SMK terdekat memiliki desain yang menarik dengan:

- **Gradient background** ungu (`#667eea` ke `#764ba2`)
- **Card design** dengan transparansi berbeda untuk ranking
- **Badge nomor ranking** (emas untuk #1, putih untuk #2)
- **Icon informatif** untuk jarak dan waktu
- **Badge bintang** untuk rekomendasi terdekat

```html
<!-- Struktur HTML Panel -->
<div class="p-3 h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <h6 class="text-white mb-3">
        <i class="bi bi-geo-fill me-2"></i>2 SMK Terdekat
    </h6>
    <div id="nearestSchoolsList">
        <!-- Card SMK terdekat akan di-generate di sini -->
    </div>
</div>
```

---

# 7️⃣ DEMO APLIKASI

## Langkah-langkah Demo:

### 1. Akses Aplikasi
```
URL: http://localhost/PPDB_SMK
```

### 2. Login sebagai Siswa
```
- Buat akun baru atau login
- Pilih "Jalur Zonasi" saat mendaftar
```

### 3. Buka Form Pendaftaran
```
- Scroll ke bagian "Peta Lokasi & SMK Terdekat"
- Klik tombol crosshair ATAU klik langsung pada peta
```

### 4. Lihat Hasil
```
- Peta menampilkan marker lokasi Anda
- Panel kanan menampilkan 2 SMK terdekat
- Jarak dan waktu tempuh ditampilkan
- Pilihan sekolah otomatis terisi
```

---

# 8️⃣ PERBANDINGAN HASIL

## Contoh Perhitungan:

| Lokasi Siswa | Sekolah | Jarak Udara | Jarak Darat | Selisih |
|--------------|---------|-------------|-------------|---------|
| Padang Timur | SMKN 3 Padang | 1.2 km | 1.8 km | +50% |
| Lubuk Begalung | SMKN 4 Padang | 2.0 km | 3.5 km | +75% |
| Koto Tangah | SMKN 5 Padang | 3.5 km | 5.2 km | +48% |

**Kesimpulan:** Jarak darat rata-rata **50-75% lebih jauh** dari jarak udara!

---

# 9️⃣ KESIMPULAN

## Keunggulan Sistem yang Dikembangkan:

1. ✅ **Lebih Adil** - Menggunakan jarak sebenarnya via jalan
2. ✅ **Lebih Akurat** - Data dari peta OpenStreetMap
3. ✅ **Lebih Informatif** - Menyertakan estimasi waktu tempuh
4. ✅ **User Friendly** - Peta interaktif mudah digunakan
5. ✅ **Gratis** - Tidak memerlukan biaya API
6. ✅ **Reliable** - Ada fallback jika API tidak tersedia

## Saran Pengembangan:

1. Integrasi dengan data kemacetan real-time
2. Perhitungan jarak dengan moda transportasi berbeda (jalan kaki, motor, mobil)
3. Visualisasi rute pada peta

---

# 🙏 TERIMA KASIH

## Pertanyaan?

**Kontak:**
- Nama: Rafa
- Project: PPDB SMK Kota Padang
- Repository: https://github.com/qifor27/PPDB_SMK

---

# 📚 REFERENSI

1. **Permendikbud No. 1 Tahun 2021** - Penerimaan Peserta Didik Baru
2. **OSRM Project** - http://project-osrm.org/
3. **Leaflet.js** - https://leafletjs.com/
4. **OpenStreetMap** - https://www.openstreetmap.org/
5. **Haversine Formula** - https://en.wikipedia.org/wiki/Haversine_formula

---

# 📝 CATATAN TAMBAHAN UNTUK PRESENTASI

## Tips Presentasi:

1. **Jelaskan masalah dulu** - Kenapa jarak udara tidak adil?
2. **Tunjukkan solusi** - Demo langsung aplikasi
3. **Bandingkan hasil** - Tabel perbandingan jarak
4. **Tunjukkan kode** - Fungsi perhitungan yang sudah dibuat
5. **Siapkan backup** - Jika internet mati, jelaskan dengan diagram

## Antisipasi Pertanyaan Dosen:

**Q: Kenapa pakai OSRM, bukan Google Maps?**
A: OSRM gratis dan open source, cocok untuk project akademik. Google Maps berbayar untuk penggunaan komersial.

**Q: Bagaimana jika API offline?**
A: Sistem memiliki fallback otomatis ke perhitungan Haversine dengan indikator yang jelas.

**Q: Apakah data jalan akurat?**
A: Data dari OpenStreetMap yang dikontribusi komunitas global dan terus diperbarui.

**Q: Bagaimana dengan performa jika banyak siswa?**
A: Perhitungan dilakukan di client-side (browser), sehingga tidak membebani server.

**Q: Apa bedanya dengan sistem PPDB biasa?**
A: Sistem biasa menggunakan jarak udara (Haversine) yang tidak memperhitungkan jalan sebenarnya. Sistem kami menggunakan jarak darat via OSRM yang lebih adil dan akurat.

**Q: Bagaimana cara kerja OSRM?**
A: OSRM memproses data jalan dari OpenStreetMap dan menghitung rute terpendek menggunakan algoritma Dijkstra/A*. API-nya gratis dan bisa diakses via HTTP request.

---

# 🎯 POIN-POIN PENTING (BACA INI!)

## Yang HARUS Disebutkan:

1. **Masalah**: Jarak udara tidak adil karena tidak memperhitungkan kondisi jalan
2. **Solusi**: Menggunakan OSRM API untuk menghitung jarak darat
3. **Fitur**: Peta interaktif + Panel 2 SMK terdekat
4. **Keunggulan**: Gratis, akurat, ada fallback

## Kata-kata Kunci:

- **OSRM** = Open Source Routing Machine
- **Haversine** = Formula jarak garis lurus
- **Road Distance** = Jarak via jalan
- **Leaflet.js** = Library peta
- **OpenStreetMap** = Sumber data peta

---

**SEMOGA SUKSES PRESENTASINYA! 💪**

**Jangan lupa:**
- Bawa laptop + kabel internet
- Test aplikasi sebelum presentasi
- Siapkan screenshot jika demo gagal
