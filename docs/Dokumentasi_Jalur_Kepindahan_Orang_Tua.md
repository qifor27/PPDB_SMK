# 📚 Dokumentasi Jalur Kepindahan Orang Tua
## Sistem PPDB SMK Online

**Dikerjakan oleh:** VELI  
**Tanggal:** 25 Desember 2025

---

## 📋 Daftar Isi

1. [Pendahuluan](#pendahuluan)
2. [Definisi Jalur Kepindahan](#definisi-jalur-kepindahan)
3. [Persyaratan Pendaftaran](#persyaratan-pendaftaran)
4. [Alur Pendaftaran](#alur-pendaftaran)
5. [Struktur Database](#struktur-database)
6. [Implementasi Sistem](#implementasi-sistem)
7. [Tampilan Antarmuka](#tampilan-antarmuka)
8. [Kesimpulan](#kesimpulan)

---

## 🎯 Pendahuluan

Jalur Kepindahan Orang Tua adalah salah satu dari lima jalur penerimaan peserta didik baru (PPDB) di SMK. Jalur ini diperuntukkan bagi calon siswa yang orang tuanya mengalami perpindahan tugas kedinasan ke wilayah baru.

### Jalur PPDB yang Tersedia

| No | Jalur | Kode | Penanggung Jawab |
|----|-------|------|------------------|
| 1 | Zonasi | `zonasi` | Rafa |
| 2 | Afirmasi | `afirmasi` | Sabrina |
| 3 | Prestasi | `prestasi` | Mutia |
| 4 | **Kepindahan** | `kepindahan` | **Veli** |

---

## 📖 Definisi Jalur Kepindahan

### Apa itu Jalur Kepindahan Orang Tua?

Jalur kepindahan adalah jalur penerimaan khusus bagi calon siswa yang **orang tuanya berpindah tugas** ke daerah baru karena keperluan dinas. Jalur ini mengakomodasi anak-anak dari pegawai:

- **ASN** (Aparatur Sipil Negara)
- **TNI** (Tentara Nasional Indonesia)
- **POLRI** (Kepolisian Republik Indonesia)
- **BUMN** (Badan Usaha Milik Negara)
- **Swasta** (Perusahaan Swasta dengan SK Mutasi)

### Tujuan Jalur Kepindahan

1. ✅ Memfasilitasi kelanjutan pendidikan anak pegawai yang pindah tugas
2. ✅ Memastikan tidak ada hambatan pendidikan akibat mutasi orang tua
3. ✅ Memberikan kemudahan administrasi bagi siswa pendatang baru

---

## 📝 Persyaratan Pendaftaran

### Dokumen Wajib

| No | Dokumen | Keterangan |
|----|---------|------------|
| 1 | **SK Pindah Tugas Orang Tua** | Surat Keputusan resmi dari instansi (ASN/TNI/POLRI/BUMN) |
| 2 | **Surat Keterangan dari Instansi** | Surat resmi yang menerangkan perpindahan tugas |
| 3 | **KK Baru** | Kartu Keluarga setelah pindah domisili |
| 4 | **Surat Pindah Sekolah** | Dari sekolah asal siswa |

### Data yang Harus Dilengkapi

**Data Kepindahan:**
- Jenis Instansi Orang Tua (ASN/TNI/POLRI/BUMN/Swasta)
- Nama Instansi Asal
- Nama Instansi Tujuan
- Nomor SK Pindah Tugas
- Tanggal SK Pindah
- Kota/Kabupaten Asal
- Alasan/Keterangan Kepindahan (opsional)

---

## 🔄 Alur Pendaftaran

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                        ALUR PENDAFTARAN JALUR KEPINDAHAN                     │
└─────────────────────────────────────────────────────────────────────────────┘

    ┌──────────────┐        ┌──────────────┐        ┌──────────────┐
    │   REGISTRASI │   →    │ PILIH JALUR  │   →    │  ISI DATA    │
    │   AKUN SISWA │        │ KEPINDAHAN   │        │ PENDAFTARAN  │
    └──────────────┘        └──────────────┘        └──────────────┘
           │                       │                       │
           ▼                       ▼                       ▼
    • Buat Akun           • Pilih Jalur           • Data Pribadi
    • Login               • Kepindahan            • Data Alamat
                                                  • Data Orang Tua
                                                  • Data Kepindahan
                                                  • Pilih SMK Tujuan

                                    │
                                    ▼
                          ┌──────────────┐
                          │   UPLOAD     │
                          │   DOKUMEN    │
                          └──────────────┘
                                 │
                                 ▼
                          • SK Pindah Tugas
                          • Surat Keterangan Instansi
                          • KK Baru
                          • Surat Pindah Sekolah

                                    │
                                    ▼
    ┌──────────────┐        ┌──────────────┐        ┌──────────────┐
    │   SUBMIT     │   →    │  VERIFIKASI  │   →    │   HASIL      │
    │  PENDAFTARAN │        │  OLEH ADMIN  │        │  SELEKSI     │
    └──────────────┘        └──────────────┘        └──────────────┘
           │                       │                       │
           ▼                       ▼                       ▼
    • Status: Submitted    • Cek Dokumen          • Accepted ✅
                          • Validasi SK           • Rejected ❌
                          • Status: Verified
```

### Status Pendaftaran

| Status | Warna | Keterangan |
|--------|-------|------------|
| `draft` | ⚪ Abu-abu | Pendaftaran belum selesai |
| `submitted` | 🟡 Kuning | Menunggu verifikasi admin |
| `verified` | 🔵 Biru | Dokumen terverifikasi |
| `accepted` | 🟢 Hijau | **Diterima** |
| `rejected` | 🔴 Merah | Ditolak |

---

## 🗄️ Struktur Database

### Kolom Tambahan pada `tb_siswa`

```sql
-- Kolom untuk data kepindahan orang tua
ALTER TABLE tb_siswa ADD COLUMN jenis_instansi_ortu VARCHAR(50) 
    COMMENT 'ASN/TNI/POLRI/BUMN/Swasta';

ALTER TABLE tb_siswa ADD COLUMN nama_instansi_asal VARCHAR(200) 
    COMMENT 'Nama instansi sebelum pindah';

ALTER TABLE tb_siswa ADD COLUMN nama_instansi_tujuan VARCHAR(200) 
    COMMENT 'Nama instansi setelah pindah';

ALTER TABLE tb_siswa ADD COLUMN nomor_sk_pindah VARCHAR(100) 
    COMMENT 'Nomor SK Pindah Tugas';

ALTER TABLE tb_siswa ADD COLUMN tanggal_sk_pindah DATE 
    COMMENT 'Tanggal SK Pindah Tugas';

ALTER TABLE tb_siswa ADD COLUMN kota_asal VARCHAR(100) 
    COMMENT 'Kota/Kabupaten sebelum pindah';

ALTER TABLE tb_siswa ADD COLUMN alasan_kepindahan TEXT 
    COMMENT 'Alasan/keterangan kepindahan';
```

### Tabel yang Digunakan

| Tabel | Fungsi |
|-------|--------|
| `tb_jalur` | Menyimpan data jalur (kode_jalur = 'kepindahan') |
| `tb_pendaftaran` | Data pendaftaran siswa |
| `tb_siswa` | Data lengkap siswa termasuk data kepindahan |
| `tb_dokumen` | Dokumen yang diupload (SK Pindah, dll) |
| `tb_kuota` | Kuota penerimaan per jalur |

---

## 💻 Implementasi Sistem

### File-file yang Dikerjakan

```
📁 PPDB_SMK/
├── 📁 user/
│   ├── 📄 pendaftaran.php      → Form isi data kepindahan
│   ├── 📄 dokumen.php          → Upload dokumen kepindahan
│   └── 📄 status.php           → Cek status pendaftaran
│
├── 📁 admin/
│   ├── 📄 kepindahan-selection.php  → Halaman seleksi kepindahan
│   ├── 📄 verifikasi.php            → Verifikasi dokumen
│   ├── 📄 pendaftar.php             → Daftar pendaftar
│   └── 📄 detail-siswa.php          → Detail siswa
│
└── 📁 database/
    └── 📄 kepindahan_columns.sql    → SQL kolom tambahan
```

### Kode Utama - Form Kepindahan

**Lokasi:** `user/pendaftaran.php` (baris 261-319)

```php
<?php if ($pendaftaran['kode_jalur'] === 'kepindahan'): ?>
    <!-- Data Kepindahan Orang Tua - VELI -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h6 class="mb-0">
                <i class="bi bi-arrow-left-right me-2"></i>
                Data Kepindahan Orang Tua
            </h6>
        </div>
        <div class="card-body">
            <!-- Form fields untuk:
                - Jenis Instansi Orang Tua
                - Nama Instansi Asal
                - Nama Instansi Tujuan
                - Nomor SK Pindah
                - Tanggal SK Pindah
                - Kota Asal
                - Alasan Kepindahan
            -->
        </div>
    </div>
<?php endif; ?>
```

### Kode Utama - Halaman Admin Seleksi

**Lokasi:** `admin/kepindahan-selection.php`

```php
// Get pendaftar jalur kepindahan
$pendaftarList = db()->fetchAll(
    "SELECT p.*, s.*, j.nama_jalur, j.kode_jalur
     FROM tb_pendaftaran p
     JOIN tb_siswa s ON p.id_siswa = s.id_siswa
     JOIN tb_jalur j ON p.id_jalur = j.id_jalur
     WHERE p.id_smk_pilihan1 = ? AND j.kode_jalur = 'kepindahan'
     ORDER BY p.status, p.tanggal_submit ASC",
    [$smkId]
);
```

---

## 🖥️ Tampilan Antarmuka

### 1. Form Pendaftaran (User)

**Fitur:**
- ✅ Form data kepindahan dengan validasi
- ✅ Dropdown jenis instansi (ASN/TNI/POLRI/BUMN/Swasta)
- ✅ Input nomor dan tanggal SK Pindah
- ✅ Text area untuk alasan kepindahan

### 2. Halaman Seleksi Admin

**Fitur:**
- ✅ Statistik pendaftar (Kuota, Menunggu, Diterima, Ditolak)
- ✅ Filter berdasarkan status
- ✅ Pencarian (nama/NISN/nomor pendaftaran)
- ✅ Tabel daftar pendaftar dengan informasi lengkap

**Kolom Tabel:**
| # | No. Pendaftaran | Nama Siswa | Jenis Instansi | Kota Asal | No. SK Pindah | Status | Aksi |

### 3. Info Box Persyaratan

```html
<div class="alert alert-info mt-4">
    <h6 class="alert-heading">
        <i class="bi bi-info-circle me-2"></i>
        Persyaratan Jalur Kepindahan
    </h6>
    <ul class="mb-0">
        <li>SK Pindah Tugas Orang Tua (ASN/TNI/POLRI/BUMN)</li>
        <li>Surat Keterangan dari Instansi</li>
        <li>KK Baru (Setelah Pindah)</li>
        <li>Surat Pindah Sekolah dari sekolah asal</li>
    </ul>
</div>
```

---

## ✅ Kesimpulan

### Yang Sudah Dikerjakan

| No | Item | Status |
|----|------|--------|
| 1 | Kolom database untuk data kepindahan | ✅ Selesai |
| 2 | Form pendaftaran jalur kepindahan | ✅ Selesai |
| 3 | Halaman admin seleksi kepindahan | ✅ Selesai |
| 4 | Validasi dokumen kepindahan | ✅ Selesai |
| 5 | Integrasi dengan sistem verifikasi | ✅ Selesai |

### Keunggulan Implementasi

1. **Modular** - Kode terpisah per jalur, mudah di-maintain
2. **Validasi Lengkap** - Semua field wajib tervalidasi
3. **User Friendly** - Antarmuka mudah dipahami
4. **Responsive** - Tampilan menyesuaikan berbagai ukuran layar

---

## 📞 Kontak

**Developer:** VELI  
**Branch Git:** `feature/veli-jalur-kepindahan`  
**Format Commit:** `[VELI] - Deskripsi perubahan`

---

*Dokumentasi ini dibuat untuk keperluan presentasi tugas PPDB SMK*
