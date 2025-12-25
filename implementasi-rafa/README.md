# 📍 Implementasi Jalur Zonasi - RAFA

## 🎯 Fokus Area
- Form pendaftaran jalur zonasi
- Integrasi perhitungan jarak (koordinat GPS)
- Validasi domisili berdasarkan KK
- Ranking berdasarkan jarak terdekat

---

## 📁 Struktur File yang Dikerjakan

```
├── user/
│   ├── pendaftaran.php (bagian zonasi)
│   ├── dokumen.php (upload KK, bukti domisili)
├── admin/
│   ├── verifikasi.php (verifikasi zonasi)
│   ├── pendaftar.php (filter jalur zonasi)
├── api/
│   ├── get-smk.php (data koordinat sekolah)
```

---

## 🗃️ Tabel Database yang Digunakan

| Tabel | Keterangan |
|-------|------------|
| `tb_jalur` | kode_jalur = 'zonasi' |
| `tb_pendaftaran` | field: jarak_ke_sekolah, skor_zonasi |
| `tb_siswa` | field: latitude, longitude, alamat |
| `tb_smk` | field: latitude, longitude sekolah |
| `tb_kuota` | kuota jalur zonasi |

---

## 🔧 Fitur Khusus yang Harus Diimplementasi

### 1. Perhitungan Jarak DARAT (Road Distance)
Menggunakan **OSRM API** (Open Source Routing Machine) untuk menghitung jarak melalui jalur jalan raya, bukan garis lurus (udara).

```php
// Fungsi utama - sudah tersedia di zonasi_helper.php
$hasil = hitungJarakDarat($latSiswa, $lonSiswa, $latSekolah, $lonSekolah);

// Response:
// [
//     'jarak' => 3500,           // dalam meter
//     'durasi' => 600,           // dalam detik (estimasi waktu tempuh)
//     'success' => true,         // true = jarak darat, false = fallback ke udara
//     'source' => 'OSRM'         // sumber perhitungan
// ]
```

**API yang Digunakan:**
| API | Kelebihan | Kekurangan |
|-----|-----------|------------|
| **OSRM** (default) | Gratis, tanpa API key | Limit tidak jelas |
| **OpenRouteService** | Gratis 2000 req/hari | Perlu daftar API key |

**Konfigurasi di `zonasi_helper.php`:**
```php
define('ROUTING_API', 'osrm'); // atau 'openrouteservice'
define('OPENROUTESERVICE_API_KEY', 'your-api-key'); // jika pakai ORS
```

### 2. Radius Zonasi
- Default: **3000 meter (3 km)**
- Prioritas: Siswa dengan jarak terdekat

### 3. Mapping Koordinat
- Validasi koordinat siswa berdasarkan alamat di KK
- Integrasi dengan peta (Leaflet/Google Maps)

---

## 📋 Checklist Tugas

### Form Pendaftaran (user/pendaftaran.php)
- [x] Form input alamat lengkap
- [x] Input/deteksi koordinat GPS (latitude, longitude)
- [x] **PETA INTERAKTIF** dengan Leaflet.js
- [x] **Tampilan 2 SMK Terdekat** dengan jarak darat
- [x] Pilihan sekolah tujuan (dengan info jarak)
- [x] Auto-select SMK berdasarkan jarak terdekat
- [ ] Validasi alamat sesuai KK

### Upload Dokumen (user/dokumen.php)
- [ ] Upload Kartu Keluarga (KK)
- [ ] Upload bukti domisili
- [ ] Validasi format dan ukuran file

### Verifikasi Admin (admin/verifikasi.php)
- [ ] Filter pendaftar jalur zonasi
- [ ] Verifikasi kesesuaian alamat dengan KK
- [ ] Validasi koordinat domisili
- [ ] Approve/reject pendaftaran

### API Koordinat (api/get-smk.php)
- [x] Endpoint untuk data koordinat sekolah - terintegrasi di form
- [x] Perhitungan jarak dari posisi siswa - via OSRM API
- [ ] Response JSON untuk mapping (opsional)

### Ranking & Seleksi
- [ ] Sistem ranking berdasarkan jarak terdekat
- [ ] Cek kuota jalur zonasi per sekolah
- [ ] Logika seleksi otomatis

---

## 🗺️ Fitur Peta yang Sudah Diimplementasi

### Peta Interaktif (Leaflet.js)
- Menampilkan lokasi semua SMK dengan marker hijau
- User dapat klik peta untuk menentukan lokasi
- Marker user dapat di-drag untuk pindah posisi
- Tombol deteksi lokasi GPS otomatis

### Panel 2 SMK Terdekat
- Menampilkan 2 sekolah dengan jarak paling dekat
- Jarak dihitung via **jalur jalan raya** (OSRM API)
- Menampilkan estimasi waktu tempuh
- Auto-select pilihan sekolah 1 & 2

---

## 🌿 Git Branch
```bash
git checkout -b feature/rafa-jalur-zonasi
```

## 📝 Format Commit
```bash
git commit -m "[RAFA] - Deskripsi perubahan"
```

Contoh:
- `[RAFA] - Integrasi perhitungan jarak zonasi`
- `[RAFA] - Form upload dokumen KK`
- `[RAFA] - API get koordinat sekolah`

---

## 🔗 Referensi Kode Jalur Zonasi

**Kode di Database:** `zonasi`

**Query Filter Jalur Zonasi:**
```sql
SELECT * FROM tb_pendaftaran p
JOIN tb_jalur j ON p.id_jalur = j.id_jalur
WHERE j.kode_jalur = 'zonasi'
ORDER BY p.jarak_ke_sekolah ASC
```

---

**Selamat mengerjakan! 💪**
