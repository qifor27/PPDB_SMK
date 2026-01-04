# Implementation Plan: Sistem Penentuan Kelulusan PPDB SMK

## 📜 Dasar Hukum: Juknis SPMB Sumatera Barat 2025/2026

Berdasarkan **Petunjuk Teknis SPMB Online Tahun 2025** yang diterbitkan oleh **Dinas Pendidikan Provinsi Sumatera Barat** (14 April 2025), berikut adalah aturan resmi sistem perangkingan dan seleksi SMK:

---

## 🎯 Jalur Seleksi SMK

Seleksi SMK dilakukan berdasarkan **kombinasi kriteria**:

| Jalur/Kriteria | Keterangan | Bobot |
|----------------|------------|-------|
| **Seleksi Rapor** | Nilai rata-rata semester 1-5 | Komponen utama |
| **Prestasi Akademik** | Sertifikat kejuaraan bidang sains, teknologi, riset | Nilai tambah |
| **Prestasi Non-Akademik** | Sertifikat olahraga, seni, keagamaan | Nilai tambah |
| **Tes Bakat & Minat** | Tes dilaksanakan di sekolah tujuan | Komponen utama |

### Catatan Penting dari Juknis:
> ⚠️ **Untuk SMK Berasrama (Boarding School):**
> - Nilai Akhir = **85% Hasil Tes** + **15% Prestasi Non-Akademik**
> 
> ⚠️ **Prioritas Domisili SMK:**
> - SMK Negeri dapat memprioritaskan siswa berdomisili terdekat **maksimal 10%** dari daya tampung
> - Dibuktikan dengan KK minimal 1 tahun sebelum pendaftaran

---

## 📊 Formula Perangkingan SMK (Sesuai Juknis)

### Formula Nilai Akhir:
```
Nilai Akhir = (30% × Bobot Rapor) + (70% × Nilai Tes Bakat Minat)
```

### Tabel Konversi Bobot Rapor:
| Rata-rata Nilai | Bobot |
|-----------------|-------|
| ≥ 98 | 94 |
| ≥ 97 | 93 |
| ≥ 96 | 92 |
| ≥ 95 | 91 |
| ≥ 94 | 90 |
| ≥ 93 | 89 |
| ≥ 92 | 88 |
| ≥ 91 | 87 |
| ≥ 90 | 86 |
| ≥ 89 | 85 |
| ≥ 88 | 84 |
| ≥ 87 | 83 |
| ≥ 86 | 82 |
| ≥ 85 | 81 |
| < 85 | 80 |

### Urutan Prioritas Jika Nilai Sama (Tie-Breaker):
1. **Nilai Akumulasi tertinggi**
2. **Umur tertua** (dalam bulan)
3. **Tanggal pendaftaran terdahulu**

---

## 🏆 Tabel Bobot Prestasi (Sesuai Juknis)

### Prestasi Akademik/Non-Akademik:
| Tingkat | Juara I/Emas | Juara II/Perak | Juara III/Perunggu |
|---------|--------------|----------------|-------------------|
| Internasional | 100 | 99 | 98 |
| Nasional | 97 | 96 | 95 |
| Provinsi | 94 | 93 | 92 |
| Kabupaten/Kota | 91 | 90 | 89 |

### Hafidz Qur'an:
| Jumlah Juz | Bobot |
|------------|-------|
| ≥ 13 Juz | 100 |
| 12 Juz | 99 |
| 11 Juz | 98 |
| 10 Juz | 97 |
| 9 Juz | 96 |
| 8 Juz | 95 |
| 7 Juz | 94 |
| 6 Juz | 93 |
| 5 Juz | 92 |
| 4 Juz | 91 |
| 3 Juz | 90 |
| 2 Juz | 89 |

---

## 📈 Data Kuota SMK Sumatera Barat 2025/2026

### Total Provinsi:
- **Total SMK Negeri**: 110 sekolah
- **Total Daya Tampung**: 38.184 kursi
- **Total Rombongan Belajar**: 1.052 rombel
- **Rata-rata per Rombel**: ±36 siswa

### Estimasi Kuota per SMK Padang:
| id_smk | Nama SMK | Jurusan | Kuota Total | Kuota/Jurusan |
|--------|----------|---------|-------------|---------------|
| 1 | SMAK Padang | 1 | 144 | 144 |
| 2 | SMKN 4 Padang | 7 | 252 | 36 |
| 3 | SMKN 7 Padang | 4 | 144 | 36 |
| 4 | SMKN 8 Padang | 2 | 108 | 54 |
| 6 | SMKN 1 Padang | 14 | 504 | 36 |
| 7 | SMKN 10 Padang | 5 | 180 | 36 |
| 8 | SMTI Padang | 2 | 180 | 90 |
| 9 | SMK PP Negeri | 5 | 180 | 36 |
| 10 | SMKN 9 Padang | 2 | 144 | 72 |
| 11 | SMKN 3 Padang | 7 | 252 | 36 |
| 12 | SMKN 2 Padang | 6 | 240 | 40 |
| 13 | SMKN 5 Padang | 9 | 324 | 36 |
| 14 | SMKN 6 Padang | 6 | 216 | 36 |
| 15 | SMKN 1 Sumbar | 12 | 432 | 36 |

---

## 🎯 Algoritma Penentuan Kelulusan

### Flow Proses:
```
┌─────────────────────────────────────────────────────────────┐
│  STEP 1: FILTER PENDAFTAR                                   │
│  - Status = 'verified'                                      │
│  - Per SMK, Per Jurusan, Per Tahap                          │
└──────────────────────────┬──────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────┐
│  STEP 2: HITUNG NILAI AKHIR                                 │
│  Nilai = (30% × Bobot Rapor) + (70% × Nilai Tes)            │
│  + Bonus Prestasi (jika ada)                                │
└──────────────────────────┬──────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────┐
│  STEP 3: URUTKAN (RANKING)                                  │
│  ORDER BY:                                                  │
│    1. nilai_akumulasi DESC                                  │
│    2. umur_bulan DESC                                       │
│    3. tanggal_daftar ASC                                    │
└──────────────────────────┬──────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────┐
│  STEP 4: TENTUKAN STATUS                                    │
│  Kuota = tb_kuota_jurusan.kuota                             │
│                                                             │
│  IF ranking <= kuota THEN status = 'accepted'               │
│  ELSE status = 'rejected'                                   │
└──────────────────────────┬──────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────────┐
│  STEP 5: PROSES PILIHAN KE-2 (Opsional)                     │
│  - Ambil siswa dengan status 'rejected'                     │
│  - Masukkan ke ranking pilihan 2                            │
│  - Jika masih ada kuota → 'accepted'                        │
└─────────────────────────────────────────────────────────────┘
```

---

## 📁 Proposed Changes

### 1. Database Schema

#### [NEW] `database/add_kuota_jurusan.sql`
```sql
-- Tabel kuota per jurusan per tahun
CREATE TABLE IF NOT EXISTS `tb_kuota_jurusan` (
    `id_kuota_jurusan` INT(11) NOT NULL AUTO_INCREMENT,
    `id_smk` INT(11) NOT NULL,
    `id_kejuruan` INT(11) NOT NULL,
    `tahun_ajaran` VARCHAR(10) NOT NULL DEFAULT '2025/2026',
    `kuota` INT(11) NOT NULL DEFAULT 36,
    `terisi` INT(11) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_kuota_jurusan`),
    UNIQUE KEY `unique_kuota` (`id_smk`, `id_kejuruan`, `tahun_ajaran`),
    FOREIGN KEY (`id_kejuruan`) REFERENCES `tb_kejuruan`(`id_program`) ON DELETE CASCADE
);
```

---

### 2. File Baru

#### [NEW] `admin/proses-kelulusan.php`
Halaman untuk admin sekolah melakukan proses penentuan kelulusan:
- Tampilkan statistik pendaftar per jurusan
- Tampilkan kuota vs pendaftar
- Tombol "Proses Kelulusan" per jurusan
- Konfirmasi sebelum proses
- Log hasil proses

#### [NEW] `admin/kuota-jurusan.php`
Halaman kelola kuota per jurusan:
- Tabel jurusan dengan kolom kuota
- Inline edit kuota
- Tampilkan sisa kuota tersedia

---

### 3. Modifikasi File Existing

#### [MODIFY] `admin/ranking.php`
Tambahkan:
- Info kuota di header
- Kolom "Status Kelulusan" di tabel
- Tombol "Proses Kelulusan"
- Filter berdasarkan status kelulusan

---

## ✅ Verification Plan

### Test Queries:
```sql
-- Cek ranking pendaftar per jurusan
SELECT 
    p.nomor_pendaftaran,
    s.nama_lengkap,
    k.nama_kejuruan,
    p.nilai_akumulasi,
    p.ranking_sekolah,
    p.status
FROM tb_pendaftaran p
JOIN tb_siswa s ON p.id_siswa = s.id_siswa
JOIN tb_kejuruan k ON p.id_kejuruan_pilihan1 = k.id_program
WHERE p.id_smk_pilihan1 = 12
ORDER BY p.id_kejuruan_pilihan1, p.nilai_akumulasi DESC;

-- Cek kuota vs terisi
SELECT 
    k.nama_kejuruan,
    q.kuota,
    q.terisi,
    (q.kuota - q.terisi) as sisa
FROM tb_kuota_jurusan q
JOIN tb_kejuruan k ON q.id_kejuruan = k.id_program
WHERE q.id_smk = 12;
```

---

## ⏱️ Estimasi Waktu

| Task | Durasi |
|------|--------|
| add_kuota_jurusan.sql | 15 menit |
| seed_kuota_jurusan.sql | 20 menit |
| admin/kuota-jurusan.php | 30 menit |
| admin/proses-kelulusan.php | 60 menit |
| Modify ranking.php | 20 menit |
| Testing & Debug | 30 menit |
| **Total** | **≈2.5 jam** |

---

## 🚀 Langkah Implementasi

Setelah plan disetujui:

1. **Database**: Import `add_kuota_jurusan.sql`
2. **Seed Data**: Import kuota default per jurusan
3. **UI Kuota**: Buat `admin/kuota-jurusan.php`
4. **UI Proses**: Buat `admin/proses-kelulusan.php`
5. **Modify**: Update `admin/ranking.php`
6. **Test**: Jalankan proses kelulusan di 1 sekolah
7. **Deploy**: Push ke main
