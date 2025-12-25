# Prompt untuk Tim Kolaborasi PPDB SMK

## 📋 Cara Menggunakan Prompt Ini

1. Buka Claude Opus
2. Copy-paste prompt sesuai dengan nama/tugas kamu
3. Gunakan prompt ini sebagai konteks awal sebelum mulai coding

---

## 1️⃣ PROMPT UNTUK ROFIQ (CRUD Admin & Superadmin)

```
Saya adalah Rofiq, anggota tim pengembangan sistem PPDB SMK Kota Padang. Tugas saya adalah menangani CRUD Admin dan Superadmin.

## Konteks Project
- Project: PPDB SMK (Penerimaan Peserta Didik Baru SMK Kota Padang)
- Tech Stack: PHP Native, MySQL, Bootstrap 5
- Database: dbesemka

## Tugas Saya Secara Spesifik:
1. Manajemen Admin Sekolah (tb_admin_sekolah)
   - Create, Read, Update, Delete admin sekolah
   - Assign admin ke sekolah tertentu
   
2. Manajemen Superadmin (tb_superadmin)
   - CRUD superadmin
   - Manajemen hak akses
   
3. Dashboard Admin & Superadmin
   - Statistik pendaftaran
   - Grafik per jalur
   
4. Sistem Authentication
   - Login/logout
   - Session management
   - Password hashing

5. Pengaturan Sistem (tb_pengaturan)
   - Konfigurasi tahun ajaran
   - Tanggal pendaftaran
   - Parameter sistem

## File Utama yang Saya Kerjakan:
- admin/index.php (dashboard)
- admin/includes/* (header, sidebar)
- superadmin/index.php (dashboard superadmin)
- superadmin/admin-sekolah.php (CRUD admin)
- superadmin/pengaturan.php
- superadmin/sekolah.php
- config/functions.php (fungsi admin)
- config/session.php
- login.php
- logout.php

## Tabel Database Saya:
- tb_admin
- tb_superadmin
- tb_admin_sekolah
- tb_pengaturan
- tb_log_aktivitas

## PENTING - Hindari Conflict:
- JANGAN edit file di folder user/ kecuali koordinasi
- JANGAN ubah logic jalur (afirmasi/prestasi/zonasi/kepindahan)
- JANGAN ubah struktur tb_jalur, tb_pendaftaran, tb_dokumen
- Fokus hanya pada admin, superadmin, dan pengaturan sistem

## Branch Git Saya:
feature/rofiq-admin-crud

Tolong bantu saya untuk [tulis permintaan spesifik kamu di sini]
```

---

## 2️⃣ PROMPT UNTUK SABRINA (Jalur Afirmasi)

```
Saya adalah Sabrina, anggota tim pengembangan sistem PPDB SMK Kota Padang. Tugas saya adalah menangani Jalur Afirmasi.

## Konteks Project
- Project: PPDB SMK (Penerimaan Peserta Didik Baru SMK Kota Padang)
- Tech Stack: PHP Native, MySQL, Bootstrap 5
- Database: dbesemka

## Tentang Jalur Afirmasi:
- Kode Jalur: 'afirmasi'
- Kuota: 15% dari total
- Ditujukan untuk siswa dari keluarga kurang mampu
- Memerlukan bukti: KIP, PKH, KIS, atau SKTM

## Tugas Saya Secara Spesifik:
1. Form Pendaftaran Jalur Afirmasi
   - Field khusus untuk data kemiskinan
   - Validasi kelengkapan dokumen afirmasi
   
2. Upload Dokumen Afirmasi
   - Kartu Indonesia Pintar (KIP)
   - Kartu Keluarga Sejahtera (KKS)
   - Program Keluarga Harapan (PKH)
   - Kartu Indonesia Sehat (KIS)
   - Surat Keterangan Tidak Mampu (SKTM)
   
3. Verifikasi Pendaftar Afirmasi
   - Halaman admin untuk verifikasi dokumen
   - Validasi keaslian dokumen
   
4. Logika Seleksi Afirmasi
   - Prioritas berdasarkan kelengkapan dokumen
   - Status verifikasi dokumen

## File yang Saya Kerjakan:
- user/pendaftaran.php (HANYA bagian kode if jalur == afirmasi)
- user/dokumen.php (HANYA bagian upload dokumen afirmasi)
- user/status.php (tampilkan status afirmasi)
- admin/verifikasi.php (HANYA bagian filter/verifikasi afirmasi)
- admin/pendaftar.php (HANYA filter jalur afirmasi)

## Query Database Penting:
```sql
-- Filter pendaftar jalur afirmasi
SELECT * FROM tb_pendaftaran p
JOIN tb_jalur j ON p.id_jalur = j.id_jalur
WHERE j.kode_jalur = 'afirmasi'

-- Dokumen yang diperlukan untuk afirmasi
-- jenis_dokumen: 'KIP', 'KKS', 'PKH', 'KIS', 'SKTM'
```

## Tabel Database yang Saya Gunakan:
- tb_jalur (WHERE kode_jalur = 'afirmasi')
- tb_pendaftaran (pendaftaran jalur afirmasi)
- tb_dokumen (dokumen KIP, PKH, SKTM, dll)
- tb_siswa (data siswa)
- tb_kuota (kuota jalur afirmasi)

## PENTING - Hindari Conflict:
- JANGAN edit bagian jalur lain (prestasi, zonasi, kepindahan)
- JANGAN ubah file admin utama (dashboard, CRUD admin)
- JANGAN ubah struktur database
- JANGAN edit config/database.php atau config/config.php
- Fokus HANYA pada logic dan UI untuk jalur afirmasi

## Penanda Code Block untuk Afirmasi:
Gunakan comment berikut untuk menandai kode afirmasi:
```php
// === JALUR AFIRMASI START ===
// kode untuk afirmasi
// === JALUR AFIRMASI END ===
```

## Branch Git Saya:
feature/sabrina-jalur-afirmasi

Tolong bantu saya untuk [tulis permintaan spesifik kamu di sini]
```

---

## 3️⃣ PROMPT UNTUK MUTIA (Jalur Prestasi)

```
Saya adalah Mutia, anggota tim pengembangan sistem PPDB SMK Kota Padang. Tugas saya adalah menangani Jalur Prestasi.

## Konteks Project
- Project: PPDB SMK (Penerimaan Peserta Didik Baru SMK Kota Padang)
- Tech Stack: PHP Native, MySQL, Bootstrap 5
- Database: dbesemka

## Tentang Jalur Prestasi:
- Kode Jalur: 'prestasi'
- Kuota: 25% dari total
- Ditujukan untuk siswa berprestasi
- Dinilai berdasarkan akumulasi poin dari sertifikat

## Sistem Poin Prestasi:
| Tingkat | Juara 1 | Juara 2 | Juara 3 | Peserta |
|---------|---------|---------|---------|---------|
| Internasional | 100 | 90 | 80 | 50 |
| Nasional | 80 | 70 | 60 | 30 |
| Provinsi | 60 | 50 | 40 | 20 |
| Kota/Kabupaten | 40 | 30 | 20 | 10 |

## Tugas Saya Secara Spesifik:
1. Form Pendaftaran Jalur Prestasi
   - Input data prestasi (multiple)
   - Kategori: Akademik, Non-Akademik, Olahraga, Seni
   
2. Upload Sertifikat Prestasi
   - Multiple upload untuk banyak prestasi
   - Preview sertifikat
   
3. Sistem Perhitungan Poin
   - Hitung poin berdasarkan tingkat & peringkat
   - Akumulasi total poin
   
4. Ranking Jalur Prestasi
   - Urutkan berdasarkan total poin
   - Tie-breaker jika poin sama

## File yang Saya Kerjakan:
- user/pendaftaran.php (HANYA bagian kode if jalur == prestasi)
- user/dokumen.php (HANYA bagian upload sertifikat)
- admin/verifikasi.php (HANYA bagian verifikasi prestasi)
- admin/pendaftar.php (HANYA filter jalur prestasi)

## Tabel Database KHUSUS PRESTASI:
```sql
-- TABEL UTAMA SAYA: tb_prestasi_siswa
CREATE TABLE tb_prestasi_siswa (
    id_prestasi_siswa INT PRIMARY KEY,
    id_pendaftaran INT,
    nama_prestasi VARCHAR(200),
    jenis_prestasi ENUM('Akademik','Non-Akademik','Olahraga','Seni','Lainnya'),
    tingkat ENUM('Kota/Kabupaten','Provinsi','Nasional','Internasional'),
    tahun INT,
    penyelenggara VARCHAR(200),
    peringkat VARCHAR(50),
    file_sertifikat VARCHAR(255),
    poin INT,
    status_verifikasi ENUM('pending','valid','invalid')
);
```

## Query Penting:
```sql
-- Total poin prestasi per pendaftar
SELECT p.id_pendaftaran, SUM(ps.poin) as total_poin
FROM tb_pendaftaran p
JOIN tb_prestasi_siswa ps ON p.id_pendaftaran = ps.id_pendaftaran
WHERE p.id_jalur = (SELECT id_jalur FROM tb_jalur WHERE kode_jalur = 'prestasi')
GROUP BY p.id_pendaftaran
ORDER BY total_poin DESC
```

## PENTING - Hindari Conflict:
- JANGAN edit bagian jalur lain (afirmasi, zonasi, kepindahan)
- JANGAN ubah file admin utama (dashboard, CRUD admin)
- JANGAN edit perhitungan jarak zonasi
- JANGAN edit config/database.php atau config/config.php
- Fokus HANYA pada logic prestasi dan tabel tb_prestasi_siswa

## Penanda Code Block untuk Prestasi:
```php
// === JALUR PRESTASI START ===
// kode untuk prestasi
// === JALUR PRESTASI END ===
```

## Branch Git Saya:
feature/mutia-jalur-prestasi

Tolong bantu saya untuk [tulis permintaan spesifik kamu di sini]
```

---

## 4️⃣ PROMPT UNTUK RAFA (Jalur Zonasi)

```
Saya adalah Rafa, anggota tim pengembangan sistem PPDB SMK Kota Padang. Tugas saya adalah menangani Jalur Zonasi.

## Konteks Project
- Project: PPDB SMK (Penerimaan Peserta Didik Baru SMK Kota Padang)
- Tech Stack: PHP Native, MySQL, Bootstrap 5
- Database: dbesemka

## Tentang Jalur Zonasi:
- Kode Jalur: 'zonasi'
- Kuota: 50% dari total (terbesar)
- Seleksi berdasarkan jarak domisili ke sekolah
- Semakin dekat = semakin prioritas

## Komponen GPS/Koordinat:
- Siswa: tb_siswa.latitude, tb_siswa.longitude
- Sekolah: tb_smk.latitude, tb_smk.longitude
- Hasil jarak: tb_pendaftaran.jarak_ke_sekolah
- Skor: tb_pendaftaran.skor_zonasi

## Tugas Saya Secara Spesifik:
1. Form Pendaftaran Jalur Zonasi
   - Input alamat lengkap
   - Ambil koordinat GPS (bisa manual atau geolocation)
   
2. Perhitungan Jarak
   - Rumus Haversine untuk jarak antar koordinat
   - Hitung jarak siswa ke sekolah pilihan
   
3. Validasi Domisili
   - Verifikasi KK dengan alamat
   - Minimal domisili 1 tahun
   
4. Sistem Ranking Zonasi
   - Urutkan dari jarak terdekat
   - Filter berdasarkan radius (default 3km)

## Formula Haversine (JavaScript/PHP):
```javascript
function haversineDistance(lat1, lon1, lat2, lon2) {
    const R = 6371; // Radius bumi dalam km
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c; // Jarak dalam km
}
```

## File yang Saya Kerjakan:
- user/pendaftaran.php (HANYA bagian kode if jalur == zonasi)
- user/dokumen.php (HANYA upload KK, bukti domisili)
- admin/verifikasi.php (HANYA verifikasi zonasi)
- admin/pendaftar.php (HANYA filter jalur zonasi)
- api/get-smk.php (data koordinat sekolah)

## Kolom Database Penting:
```sql
-- Kolom di tb_siswa yang saya kelola:
- latitude DECIMAL(10,8)
- longitude DECIMAL(11,8)
- alamat TEXT
- kelurahan, kecamatan, kota, provinsi

-- Kolom di tb_pendaftaran yang saya kelola:
- jarak_ke_sekolah DECIMAL(10,2)
- skor_zonasi DECIMAL(10,4)

-- Data sekolah untuk perhitungan:
SELECT id_smk, nama_sekolah, latitude, longitude FROM tb_smk;
```

## Query Ranking Zonasi:
```sql
SELECT p.*, s.nama_lengkap, p.jarak_ke_sekolah
FROM tb_pendaftaran p
JOIN tb_siswa s ON p.id_siswa = s.id_siswa
JOIN tb_jalur j ON p.id_jalur = j.id_jalur
WHERE j.kode_jalur = 'zonasi'
ORDER BY p.jarak_ke_sekolah ASC
```

## PENTING - Hindari Conflict:
- JANGAN edit bagian jalur lain (afirmasi, prestasi, kepindahan)
- JANGAN ubah file admin utama (dashboard, CRUD admin)
- JANGAN edit sistem poin prestasi
- JANGAN edit config/database.php atau config/config.php
- Fokus HANYA pada perhitungan jarak dan koordinat

## Penanda Code Block untuk Zonasi:
```php
// === JALUR ZONASI START ===
// kode untuk zonasi
// === JALUR ZONASI END ===
```

## Branch Git Saya:
feature/rafa-jalur-zonasi

Tolong bantu saya untuk [tulis permintaan spesifik kamu di sini]
```

---

## 5️⃣ PROMPT UNTUK VELI (Jalur Kepindahan)

```
Saya adalah Veli, anggota tim pengembangan sistem PPDB SMK Kota Padang. Tugas saya adalah menangani Jalur Kepindahan Orang Tua.

## Konteks Project
- Project: PPDB SMK (Penerimaan Peserta Didik Baru SMK Kota Padang)
- Tech Stack: PHP Native, MySQL, Bootstrap 5
- Database: dbesemka

## Tentang Jalur Kepindahan:
- Kode Jalur: 'kepindahan'
- Kuota: 10% dari total
- Untuk siswa yang orang tuanya pindah tugas (ASN, TNI, POLRI)
- Dibuktikan dengan SK Pindah Tugas

## Dokumen yang Diperlukan:
1. SK (Surat Keputusan) Pindah Tugas Orang Tua
2. Surat Keterangan dari Instansi
3. Kartu Keluarga Baru (KK)
4. Surat Pindah dari Sekolah Asal
5. Raport Semester Terakhir
6. Ijazah/SKL

## Tugas Saya Secara Spesifik:
1. Form Pendaftaran Jalur Kepindahan
   - Data instansi orang tua (TNI/POLRI/ASN/Swasta)
   - Tanggal SK pindah
   - Asal daerah sebelumnya
   
2. Upload Dokumen Kepindahan
   - SK Pindah Tugas
   - Surat Keterangan Instansi
   - Surat Pindah Sekolah
   
3. Verifikasi Dokumen Kepindahan
   - Validasi keaslian SK
   - Cek masa berlaku
   
4. Logika Seleksi Kepindahan
   - Prioritas berdasarkan tanggal pindah
   - Kelengkapan dokumen

## File yang Saya Kerjakan:
- user/pendaftaran.php (HANYA bagian kode if jalur == kepindahan)
- user/dokumen.php (HANYA upload SK pindah, surat instansi)
- admin/verifikasi.php (HANYA verifikasi kepindahan)
- admin/pendaftar.php (HANYA filter jalur kepindahan)

## Field Khusus Kepindahan (bisa ditambahkan):
```sql
-- Pertimbangkan menambah field di tb_pendaftaran atau tb_siswa:
- instansi_ortu VARCHAR(100) -- TNI/POLRI/ASN/Swasta
- no_sk_pindah VARCHAR(50)
- tanggal_sk_pindah DATE
- asal_kota VARCHAR(100)
- asal_provinsi VARCHAR(100)
```

## Query Penting:
```sql
-- Filter pendaftar jalur kepindahan
SELECT p.*, s.nama_lengkap, s.asal_sekolah
FROM tb_pendaftaran p
JOIN tb_siswa s ON p.id_siswa = s.id_siswa
JOIN tb_jalur j ON p.id_jalur = j.id_jalur
WHERE j.kode_jalur = 'kepindahan'

-- Dokumen kepindahan
-- jenis_dokumen: 'SK_PINDAH', 'SURAT_INSTANSI', 'KK_BARU', 'SURAT_PINDAH_SEKOLAH'
```

## Tabel Database yang Saya Gunakan:
- tb_jalur (WHERE kode_jalur = 'kepindahan')
- tb_pendaftaran
- tb_dokumen (dokumen SK pindah, surat instansi)
- tb_siswa (asal_sekolah, alamat_sekolah_asal)
- tb_kuota

## PENTING - Hindari Conflict:
- JANGAN edit bagian jalur lain (afirmasi, prestasi, zonasi)
- JANGAN ubah file admin utama (dashboard, CRUD admin)
- JANGAN edit perhitungan poin atau jarak
- JANGAN edit config/database.php atau config/config.php
- Fokus HANYA pada logic kepindahan dan dokumen terkait

## Penanda Code Block untuk Kepindahan:
```php
// === JALUR KEPINDAHAN START ===
// kode untuk kepindahan
// === JALUR KEPINDAHAN END ===
```

## Branch Git Saya:
feature/veli-jalur-kepindahan

Tolong bantu saya untuk [tulis permintaan spesifik kamu di sini]
```

---

## 💡 Tips Menggunakan Prompt

1. **Copy prompt sesuai namamu** ke Claude Opus
2. **Tambahkan request spesifik** di bagian akhir prompt
3. **Jelaskan konteks lebih detail** jika perlu
4. **Selalu minta Claude untuk fokus** hanya pada bagianmu
5. **Minta Claude untuk menandai kode** dengan comment block

## 🔄 Contoh Request Spesifik

```
Tolong bantu saya untuk:
1. Membuat form upload dokumen KIP dengan validasi file
2. Menampilkan preview gambar sebelum upload
3. Simpan path file ke database tb_dokumen
```

---

**Selamat coding! 🚀**
